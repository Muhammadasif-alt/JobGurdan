<?php

namespace App\Services;

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

    private function normalise(string $text): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }
}
