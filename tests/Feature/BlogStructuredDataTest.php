<?php

use App\Services\StructuredDataService;
use Database\Seeders\FrontendDeveloperLahoreSeeder;
use Database\Seeders\WarehouseUkBlogSeeder;

/**
 * @return list<array<string, mixed>>
 */
function schemaBlocks(string $html): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    return array_map(function (string $json): array {
        $decoded = json_decode(trim($json), true);

        expect(json_last_error())->toBe(JSON_ERROR_NONE, 'JSON-LD block did not parse');

        return $decoded;
    }, $matches[1]);
}

/**
 * @param  list<array<string, mixed>>  $blocks
 */
function schemaTypes(array $blocks): array
{
    return array_map(fn (array $block) => $block['@type'] ?? null, $blocks);
}

it('extracts FAQ pairs from a post body but ignores the keyword block', function () {
    $html = <<<'HTML'
    <h2>Intro</h2><p>Not a FAQ.</p>
    <h2>Frequently Asked Questions</h2>
    <h3>First question?</h3><p>First answer.</p>
    <h3>Second question?</h3><p>Second answer.</p>
    <h2>People Also Search For</h2>
    <h3>keyword phrase</h3><p>Not a question.</p>
    HTML;

    $faqs = app(StructuredDataService::class)->faqsFromHtml($html);

    expect($faqs)->toHaveCount(2)
        ->and($faqs[0]['question'])->toBe('First question?')
        ->and($faqs[0]['answer'])->toBe('First answer.')
        ->and($faqs[1]['question'])->toBe('Second question?');
});

it('treats board searches and permalinks differently', function (string $url, bool $expected) {
    expect(app(StructuredDataService::class)->describesSingleVacancy($url))->toBe($expected);
})->with([
    'indeed search' => ['https://www.indeed.com/jobs?q=construction+visa+sponsorship', false],
    'indeed search with preview param' => ['https://uk.indeed.com/q-cleaning-l-london-jobs.html?vjk=145bc3', false],
    'simplyhired co.uk search' => ['https://www.simplyhired.co.uk/q-uk-warehouse-visa-sponsorship-jobs.html', false],
    'indeed permalink' => ['https://pk.indeed.com/viewjob?jk=6775ed0027cf10b0', true],
    'linkedin permalink' => ['https://www.linkedin.com/jobs/view/123456', true],
    'employer site' => ['https://careers.fourseasons.com/us/en/apply', true],
    'no apply url' => ['', true],
]);

it('gives a spotlight post all four schema types', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    $response = $this->get('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern');
    $response->assertOk();

    $types = schemaTypes(schemaBlocks($response->getContent()));

    expect($types)->toContain('BlogPosting')
        ->toContain('FAQPage')
        ->toContain('JobPosting')
        ->toContain('Organization')
        ->toContain('BreadcrumbList');
});

it('points the spotlight JobPosting at the job page node', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    $blocks = schemaBlocks(
        $this->get('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern')->getContent()
    );

    $posting = collect($blocks)->firstWhere('@type', 'JobPosting');

    expect($posting)->not->toBeNull()
        ->and($posting['@id'])->toEndWith('/jobs/senior-frontend-developer-react-nextjs-mern-lahore#jobposting')
        ->and($posting['hiringOrganization']['name'])->toBe('ERS Tech')
        ->and($posting['baseSalary']['value']['unitText'])->toBe('MONTH')
        ->and($posting['validThrough'])->not->toBeEmpty();
});

it('leaves JobPosting off a guide post', function () {
    $this->seed(WarehouseUkBlogSeeder::class);

    $response = $this->get('/blog/warehouse-jobs-uk-visa-sponsorship');
    $response->assertOk();

    $types = schemaTypes(schemaBlocks($response->getContent()));

    // A guide is a round-up, not one vacancy, so JobPosting would misdescribe it.
    expect($types)->toContain('BlogPosting')
        ->toContain('FAQPage')
        ->toContain('Organization')
        ->not->toContain('JobPosting');
});

it('builds the FAQ markup from questions the reader can see', function () {
    $this->seed(WarehouseUkBlogSeeder::class);

    $response = $this->get('/blog/warehouse-jobs-uk-visa-sponsorship');
    $faq = collect(schemaBlocks($response->getContent()))->firstWhere('@type', 'FAQPage');

    expect($faq['mainEntity'])->not->toBeEmpty();

    foreach ($faq['mainEntity'] as $question) {
        $response->assertSee($question['name'], false);
    }
});
