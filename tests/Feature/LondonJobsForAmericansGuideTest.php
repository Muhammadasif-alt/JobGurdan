<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\JobsInLondonForAmericansBlogSeeder;
use Database\Seeders\JobsInUkForForeignersBlogSeeder;
use Database\Seeders\UkJobsWithVisaSponsorshipBlogSeeder;
use Database\Seeders\VacanciesInLondonBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const LONDON_US_SLUG = 'jobs-in-london-england-for-american-applicants';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    $blog = Blog::where('slug', LONDON_US_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.LONDON_US_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(LONDON_US_SLUG.'-westminster.jpg')
        ->and($blog->content)->toContain(LONDON_US_SLUG.'-riverside.jpg')
        ->and($blog->content)->toContain(LONDON_US_SLUG.'-citizens.jpg')
        ->and($blog->content)->toContain(LONDON_US_SLUG.'-sectors.jpg')
        ->and($blog->content)->toContain(LONDON_US_SLUG.'-skyline.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    $content = Blog::where('slug', LONDON_US_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('states the rules that make this page different from the UK hub guide', function (string $claim) {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    expect(Blog::where('slug', LONDON_US_SLUG)->value('content'))->toContain($claim);
})->with([
    // The draft never named a salary figure at all.
    'general threshold' => '£41,700',
    // Going rates come from national ASHE data; London gets no uplift.
    'no London weighting' => 'Annual Survey of Hours and Earnings',
    // Raised from B1 on 8 January 2026, and US nationals are exempt from it.
    'english level' => 'B2',
    'english exemption' => 'majority English-speaking',
    // The route most guides imply is open to Americans, and is not.
    'youth mobility' => 'The United States is not on the list',
    // The sponsor-free route that is genuinely open to them.
    'hpi universities' => '33 US universities',
    'hpi fee' => '£880',
    // Needed to attend the interview, and absent from the draft.
    'eta' => '£20',
    // Statutory floor and the voluntary London rate, which is not the same thing.
    'national living wage' => '£12.71',
    'london living wage' => '£14.80',
    // US filing obligations do not stop on arrival.
    'feie' => '$132,900',
    'fbar' => 'FinCEN Form 114',
    // The professional-registration gate that sits in front of the visa.
    'medical regulator' => 'General Medical Council',
    'nursing regulator' => 'Nursing and Midwifery Council',
    'allied health regulator' => 'Health and Care Professions Council',
    'finance regime' => 'Senior Managers and Certification Regime',
    'school teaching' => 'Qualified Teacher Status',
    'solicitor route' => 'Solicitors Qualifying Examination',
]);

it('does not repeat the old Skilled Worker threshold or claim a London uplift', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    $content = Blog::where('slug', LONDON_US_SLUG)->value('content');

    expect($content)->not->toContain('£26,200')
        ->and($content)->not->toContain('£38,700')
        ->and($content)->not->toContain('shortage occupation list');
});

it('links to no aggregator', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', LONDON_US_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'totaljobs', 'reed', 'cv-library'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    get('/blog/'.LONDON_US_SLUG)
        ->assertOk()
        ->assertSee('Jobs in London England for American Applicants')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('creates a listing that applies through the government service and quotes no salary', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);

    $job = Job::where('position', 'like', 'Skilled Worker Visa Jobs in London%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.gov.uk/find-a-job')
        ->and($job->description)->toContain('not by JobGader')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->not->toContain(',');
});

it('cross-links with the UK hub guide in both directions', function () {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);
    $this->seed(JobsInUkForForeignersBlogSeeder::class);

    expect(Blog::where('slug', LONDON_US_SLUG)->value('content'))
        ->toContain('/blog/jobs-in-uk-for-foreigners')
        ->and(Blog::where('slug', 'jobs-in-uk-for-foreigners')->value('content'))
        ->toContain('/blog/'.LONDON_US_SLUG);
});

it('cross-links with the two new London and sponsorship guides in both directions', function (string $seeder, string $slug) {
    $this->seed(JobsInLondonForAmericansBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', LONDON_US_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.LONDON_US_SLUG);
})->with([
    'london vacancies' => [VacanciesInLondonBlogSeeder::class, 'vacancies-in-london'],
    'uk sponsorship' => [UkJobsWithVisaSponsorshipBlogSeeder::class, 'uk-jobs-with-visa-sponsorship'],
]);
