<?php

namespace App\Services;

use App\Models\Job;
use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;

/**
 * Shared rules for the JSON-LD the public pages emit.
 *
 * Two things live here because both the blog post and the job detail view need
 * them and they must agree: which apply links describe a single real vacancy,
 * and how to pull a post's FAQ pairs out of its stored HTML.
 */
class StructuredDataService
{
    /**
     * Job boards whose links may point at either a search or one posting.
     * Matched without a TLD so country domains count — simplyhired.co.uk and
     * uk.indeed.com are the same aggregators as their .com counterparts.
     *
     * @var list<string>
     */
    private const AGGREGATOR_HOSTS = [
        'indeed.', 'ziprecruiter.', 'glassdoor.', 'linkedin.', 'monster.',
        'simplyhired.', 'totaljobs.', 'reed.co.uk', 'cv-library.', 'jobsite.',
    ];

    /**
     * Markers those boards use in a permalink to one specific vacancy.
     *
     * Note "?jk="/"&jk=" rather than a bare "jk=": Indeed's search pages carry
     * a "vjk=" preview parameter that would otherwise look like a permalink.
     *
     * @var list<string>
     */
    private const SINGLE_POSTING_MARKERS = [
        '/viewjob', '?jk=', '&jk=', '/jobs/view/', 'currentjobid=', '/job-listing/',
    ];

    /**
     * Whether a page whose Apply button points at this URL describes one real
     * opening, and so may carry JobPosting markup.
     *
     * A link into a job-board *search* means the page is a round-up rather than
     * a vacancy. A permalink to one posting, or any link straight to an
     * employer's own site, describes a single opening.
     */
    public function describesSingleVacancy(?string $applyUrl): bool
    {
        $applyUrl = trim((string) $applyUrl);

        if ($applyUrl === '') {
            return true;
        }

        $host = strtolower((string) parse_url($applyUrl, PHP_URL_HOST));

        $onAggregator = false;
        foreach (self::AGGREGATOR_HOSTS as $aggregator) {
            if (str_contains($host, $aggregator)) {
                $onAggregator = true;
                break;
            }
        }

        if (! $onAggregator) {
            return true;
        }

        $lowerUrl = strtolower($applyUrl);
        foreach (self::SINGLE_POSTING_MARKERS as $marker) {
            if (str_contains($lowerUrl, $marker)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Pull the question/answer pairs out of a post's FAQ section.
     *
     * Only the section under the "Frequently Asked Questions" heading is read.
     * A post's "People Also Search For" block uses the same h3/p shape but its
     * entries are keyword phrases rather than questions, so it is left out —
     * marking those up as an FAQ would misdescribe the page.
     *
     * @return list<array{question: string, answer: string}>
     */
    public function faqsFromHtml(?string $html): array
    {
        $html = trim((string) $html);

        if ($html === '') {
            return [];
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $faqs = [];
        $insideFaqSection = false;
        $pendingQuestion = null;

        foreach ($document->getElementsByTagName('*') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($node->nodeName);
            $text = $this->normalise($node->textContent);

            if ($tag === 'h2') {
                // The FAQ block runs until the next h2 starts a new section.
                $insideFaqSection = str_contains(strtolower($text), 'frequently asked question');
                $pendingQuestion = null;

                continue;
            }

            if (! $insideFaqSection) {
                continue;
            }

            if ($tag === 'h3' && $text !== '') {
                $pendingQuestion = $text;

                continue;
            }

            if ($tag === 'p' && $pendingQuestion !== null && $text !== '') {
                $faqs[] = ['question' => $pendingQuestion, 'answer' => $text];
                $pendingQuestion = null;
            }
        }

        return $faqs;
    }

    /**
     * Build the JobPosting node for one vacancy.
     *
     * Both the job detail page and a blog spotlight post describe the same
     * opening, so they share this and carry the same @id — Google then reads
     * one vacancy referenced twice rather than two competing postings.
     *
     * @return array<string, mixed>
     */
    public function jobPosting(Job $job, string $url, ?string $descriptionHtml = null): array
    {
        $organisation = ['@type' => 'Organization', 'name' => $job->advertiser->name ?? 'JobGader'];

        if ($job->advertiser?->id) {
            $organisation['sameAs'] = url('/companies/'.$job->advertiser->id);
        }

        if ($job->advertiser?->logo) {
            $organisation['logo'] = asset('public/storage/'.$job->advertiser->logo);
        }

        $posting = [
            '@type' => 'JobPosting',
            '@id' => $url.'#jobposting',
            'url' => $url,
            'title' => $job->position,
            'description' => $descriptionHtml ?? (string) $job->description,
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $job->advertiser->name ?? 'JobGader',
                'value' => (string) $job->id,
            ],
            'datePosted' => ($job->created_at ?? now())->toIso8601String(),
            'validThrough' => $this->validThrough($job)->toIso8601String(),
            'employmentType' => $this->employmentType($job->employment_type),
            'hiringOrganization' => $organisation,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
        ];

        $posting += $this->locationNodes($job);

        if ($job->salary_minimum || $job->salary_maximum) {
            $posting['baseSalary'] = $this->baseSalary($job);
        }

        if ($job->requirements) {
            $posting['qualifications'] = $this->normalise(strip_tags($job->requirements));
        }

        if ($job->category) {
            $posting['industry'] = $job->category->name;
            $posting['occupationalCategory'] = $job->category->name;
        }

        return $posting;
    }

    /**
     * Location, remote-aware.
     *
     * A fully remote role must declare applicantLocationRequirements alongside
     * TELECOMMUTE, and must not also assert a physical workplace — pairing the
     * two is what Google reports as an invalid JobPosting item.
     *
     * @return array<string, mixed>
     */
    private function locationNodes(Job $job): array
    {
        $country = $job->location->country ?? $job->location->name ?? null;

        if ($this->isRemote($job)) {
            return $country
                ? ['jobLocationType' => 'TELECOMMUTE', 'applicantLocationRequirements' => ['@type' => 'Country', 'name' => $country]]
                : ['jobLocationType' => 'TELECOMMUTE'];
        }

        $address = array_filter([
            '@type' => 'PostalAddress',
            'addressLocality' => $job->location->name ?? null,
            'addressRegion' => $job->location->area ?? null,
            'addressCountry' => $this->countryCode($country),
        ]);

        return count($address) > 1
            ? ['jobLocation' => ['@type' => 'Place', 'address' => $address]]
            : [];
    }

    private function isRemote(Job $job): bool
    {
        $haystack = $job->job_type.' '.($job->location->area ?? '').' '.($job->location->name ?? '');

        return stripos($haystack, 'remote') !== false;
    }

    /**
     * Google wants addressCountry as an ISO 3166-1 alpha-2 code. An unmapped
     * country is left out rather than guessed — the job pages previously
     * hardcoded "US" for every listing, including the UK and Pakistan ones.
     */
    private function countryCode(?string $country): ?string
    {
        $country = trim((string) $country);

        if ($country === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z]{2}$/', $country)) {
            return strtoupper($country);
        }

        $codes = [
            'united states' => 'US', 'united states of america' => 'US', 'usa' => 'US', 'america' => 'US',
            'united kingdom' => 'GB', 'uk' => 'GB', 'great britain' => 'GB', 'england' => 'GB',
            'scotland' => 'GB', 'wales' => 'GB', 'northern ireland' => 'GB',
            'pakistan' => 'PK', 'saudi arabia' => 'SA', 'ksa' => 'SA',
            'united arab emirates' => 'AE', 'uae' => 'AE', 'qatar' => 'QA', 'kuwait' => 'KW',
            'oman' => 'OM', 'bahrain' => 'BH', 'canada' => 'CA', 'australia' => 'AU',
            'new zealand' => 'NZ', 'ireland' => 'IE', 'india' => 'IN', 'bangladesh' => 'BD',
            'philippines' => 'PH', 'germany' => 'DE',
        ];

        return $codes[strtolower($country)] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    private function baseSalary(Job $job): array
    {
        $units = ['HOURLY' => 'HOUR', 'DAILY' => 'DAY', 'WEEKLY' => 'WEEK', 'MONTHLY' => 'MONTH', 'YEARLY' => 'YEAR', 'ANNUAL' => 'YEAR'];
        $unit = $units[strtoupper((string) $job->salary_period)] ?? 'MONTH';

        $value = ['@type' => 'QuantitativeValue', 'unitText' => $unit];

        if ($job->salary_minimum && $job->salary_maximum) {
            $value['minValue'] = (float) $job->salary_minimum;
            $value['maxValue'] = (float) $job->salary_maximum;
        } else {
            $value['value'] = (float) ($job->salary_minimum ?: $job->salary_maximum);
        }

        return [
            '@type' => 'MonetaryAmount',
            'currency' => $job->salary_currency ?: 'USD',
            'value' => $value,
        ];
    }

    private function employmentType(?string $type): string
    {
        $map = [
            'FULL_TIME' => 'FULL_TIME', 'FULLTIME' => 'FULL_TIME',
            'PART_TIME' => 'PART_TIME', 'PARTTIME' => 'PART_TIME',
            'CONTRACT' => 'CONTRACTOR', 'CONTRACTOR' => 'CONTRACTOR',
            'TEMPORARY' => 'TEMPORARY', 'TEMP' => 'TEMPORARY',
            'INTERN' => 'INTERN', 'INTERNSHIP' => 'INTERN',
            'VOLUNTEER' => 'VOLUNTEER', 'PER_DIEM' => 'PER_DIEM',
            'FREELANCE' => 'CONTRACTOR',
        ];

        return $map[strtoupper(str_replace([' ', '-'], '_', (string) ($type ?: 'FULL_TIME')))] ?? 'FULL_TIME';
    }

    /**
     * A posting whose validThrough has passed is dropped from Google Jobs, so
     * an expiry in the past is pushed forward rather than published as-is.
     */
    private function validThrough(Job $job): CarbonInterface
    {
        $candidate = $job->expires_at ?? $job->valid_through ?? $job->created_at?->copy()->addDays(60);

        return ($candidate && $candidate->isFuture()) ? $candidate : now()->addDays(60);
    }

    private function normalise(string $text): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }
}
