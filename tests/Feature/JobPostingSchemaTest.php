<?php

use Database\Seeders\DigitalMarketingSeoSeeder;
use Database\Seeders\FrontendDeveloperLahoreSeeder;

/**
 * @return array<string, mixed>|null
 */
function jobPostingFrom(string $html): ?array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);

    foreach ($matches[1] as $block) {
        $decoded = json_decode(trim($block), true);

        if (is_array($decoded) && ($decoded['@type'] ?? null) === 'JobPosting') {
            return $decoded;
        }
    }

    return null;
}

it('marks a fully remote role as telecommute without also asserting a workplace', function () {
    $this->seed(DigitalMarketingSeoSeeder::class);

    $posting = jobPostingFrom($this->get('/jobs/digital-marketing-expert-seo-pakistan')->assertOk()->getContent());

    // Google reports an invalid item when TELECOMMUTE is paired with a physical
    // jobLocation, or when applicantLocationRequirements is missing.
    expect($posting)->not->toBeNull()
        ->and($posting['jobLocationType'])->toBe('TELECOMMUTE')
        ->and($posting['applicantLocationRequirements'])->toBe(['@type' => 'Country', 'name' => 'Pakistan'])
        ->and($posting)->not->toHaveKey('jobLocation');
});

it('publishes the real country and pay period rather than a hardcoded US year', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    $posting = jobPostingFrom($this->get('/jobs/senior-frontend-developer-react-nextjs-mern-lahore')->assertOk()->getContent());

    expect($posting)->not->toBeNull()
        ->and($posting['jobLocation']['address']['addressCountry'])->toBe('PK')
        ->and($posting['jobLocation']['address']['addressLocality'])->toBe('Lahore')
        ->and($posting['baseSalary']['currency'])->toBe('PKR')
        ->and($posting['baseSalary']['value']['unitText'])->toBe('MONTH');
});

it('gives the spotlight post and the job page one shared posting node', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    $fromJob = jobPostingFrom($this->get('/jobs/senior-frontend-developer-react-nextjs-mern-lahore')->getContent());
    $fromPost = jobPostingFrom($this->get('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern')->getContent());

    expect($fromPost)->not->toBeNull()
        ->and($fromPost['@id'])->toBe($fromJob['@id'])
        ->and($fromPost['jobLocation'])->toBe($fromJob['jobLocation'])
        ->and($fromPost['baseSalary'])->toBe($fromJob['baseSalary'])
        ->and($fromPost['employmentType'])->toBe($fromJob['employmentType']);
});
