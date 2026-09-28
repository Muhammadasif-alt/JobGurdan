<?php

use App\Models\Job;
use App\Models\Scholarship;
use App\Services\StructuredDataService;
use Database\Seeders\MedicalRecordsClerkJobsUsaBlogSeeder;

it('keeps career hubs and federal search results out of single vacancy markup', function (string $url, bool $expected) {
    expect(app(StructuredDataService::class)->describesSingleVacancy($url))->toBe($expected);
})->with([
    ['https://www.usajobs.gov/Search/Results?k=medical', false],
    ['https://www.usajobs.gov/job/123456', true],
    ['https://careers.example.com/', false],
    ['https://careers.example.com/search-jobs/receptionist', false],
    ['https://careers.example.com/job/123456', true],
]);

it('keeps a nationwide guide accessible without advertising it as one vacancy', function () {
    (new MedicalRecordsClerkJobsUsaBlogSeeder)->run();
    $response = $this->get('/jobs/medical-records-clerk-health-information-technician-us-employers-united-states')->assertOk();
    $response->assertSee('Medical Records')->assertDontSee('"@type": "JobPosting"', false);
    $job = Job::firstOrFail();
    expect(app(StructuredDataService::class)->describesSingleVacancy('https://example.com/job/123', $job))->toBeFalse();
});

it('does not invent a deadline or roll an expired deadline forward', function () {
    $this->travelTo(now()->setDate(2026, 9, 28)->startOfDay());
    $service = app(StructuredDataService::class);
    $job = new Job(['position' => 'Receptionist', 'application_url' => 'https://example.com/job/1']);
    $job->created_at = now()->subMonths(6);
    expect($service->jobPosting($job, 'https://jobgader.com/jobs/example'))->not->toHaveKey('validThrough');
    $job->expires_at = '2026-09-01 00:00:00';
    expect($service->jobPosting($job, 'https://jobgader.com/jobs/example')['validThrough'])->toStartWith('2026-09-01')
        ->and($service->describesSingleVacancy($job->application_url, $job))->toBeFalse();
    $job->expires_at = '2026-10-10 00:00:00';
    expect($service->describesSingleVacancy($job->application_url, $job))->toBeTrue();
    $job->status = 'expired';
    expect($service->describesSingleVacancy($job->application_url, $job))->toBeFalse();
});

it('does not publish invented sitemap modification dates or empty job sitemaps', function () {
    $this->get('/sitemap-core.xml')->assertOk()->assertDontSee('<lastmod>', false);
    $this->get('/sitemap.xml')->assertOk()->assertDontSee('<lastmod>', false)->assertDontSee('sitemap-jobs-1.xml');
    Scholarship::factory()->create(['updated_at' => '2026-09-10 10:00:00']);
    $this->get('/sitemap-scholarships.xml')->assertOk()->assertSee('<lastmod>2026-09-10</lastmod>', false);
});

it('gives scholarship page two a clean canonical and a collection of visible scholarships', function () {
    Scholarship::factory()->count(13)->create();
    $html = $this->get('/scholarships?page=2&utm_source=test')->assertOk()->getContent();
    expect($html)->toContain('<link rel="canonical" href="'.url('/scholarships').'?page=2">');
    preg_match_all('#<script type="application/ld\\+json">(.*?)</script>#s', $html, $matches);
    $blocks = array_map(fn ($block) => json_decode($block, true, 512, JSON_THROW_ON_ERROR), $matches[1]);
    expect(collect($blocks)->where('@type', 'CollectionPage'))->toHaveCount(1);
    $collection = collect($blocks)->firstWhere('@type', 'CollectionPage');
    expect($collection['url'])->toBe(url('/scholarships').'?page=2')
        ->and($collection['mainEntity']['numberOfItems'])->toBe(1)
        ->and($collection['mainEntity']['itemListElement'][0]['position'])->toBe(13);
});
