<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\LabTechnicianPharmacyAssistantJobsBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(LabTechnicianPharmacyAssistantJobsBlogSeeder::class);
    $this->blog = Blog::where('slug', LabTechnicianPharmacyAssistantJobsBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/lab-technician-pharmacy-assistant-jobs.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/lab-technician-pharmacy-assistant-jobs.jpg')))->toBeTrue();
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    expect(app(StructuredDataService::class)->faqsFromHtml($this->blog->content))->toHaveCount(8);

    $pasf = substr($this->blog->content, (int) strpos($this->blog->content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('links only to our own pages and drops the brief artifacts', function () {
    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $this->blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $href) {
        expect($href)->toMatch('#^/(blog|categories)/#');
    }

    expect(strtolower($this->blog->content))->not->toContain('apply now')
        ->not->toContain('oraclecloud')
        ->not->toContain('findability check');
});

it('states the verified licensing and training facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'pqr' => 'Professional Qualification Requirements',
    'dha pathway' => 'self-assessment, primary source verification',
    'facility activates' => 'The hiring facility completes the activation stage',
    'eligibility not licence' => 'does not authorise you to practise',
    'article 6' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'pharmacy assistant not technician' => 'A pharmacy assistant should not be described as a pharmacy technician',
    'apprenticeship' => 'pharmacy services assistant apprenticeships',
]);

it('cross-links to existing guides and the healthcare category', function () {
    expect($this->blog->content)->toContain('/blog/hospital-support-worker-jobs-in-the-uk')
        ->toContain('/blog/nursing-assistant-jobs-in-the-uk')
        ->toContain('/categories/healthcare');
});

it('creates one listing per country with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('healthcare')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae' => ['Medical Laboratory Technician — UAE', 'https://careers.mediclinic.com/MiddleEast/?locale=en_GB', 'United Arab Emirates'],
    'uk' => ['Pharmacy Assistant — UK', 'https://www.jobs.nhs.uk/candidate/search/results?keyword=pharmacy+assistant', 'United Kingdom'],
]);

it('renders the guide', function () {
    get('/blog/'.LabTechnicianPharmacyAssistantJobsBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(LabTechnicianPharmacyAssistantJobsBlogSeeder::class);

    expect(Blog::where('slug', LabTechnicianPharmacyAssistantJobsBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Pharmacy Assistant — %')->count())->toBe(1);
});
