<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AccountantBookkeeperJobsGulfEuropeBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(AccountantBookkeeperJobsGulfEuropeBlogSeeder::class);
    $this->blog = Blog::where('slug', AccountantBookkeeperJobsGulfEuropeBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/accountant-bookkeeper-jobs-gulf-europe.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/accountant-bookkeeper-jobs-gulf-europe.jpg')))->toBeTrue();
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

    expect(strtolower($this->blog->content))->not->toContain('indeed')
        ->not->toContain('apply now')
        ->not->toContain('findability check');
});

it('states the verified facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'article 6' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'visit visa' => 'A tourist or visit visa does not allow you to work',
    'recognition' => 'whether a profession is regulated in the destination country',
    'bookkeeping not designation' => 'does not by itself authorise you to use a protected accounting designation',
    'lmia' => 'whether an LMIA is required or an exemption applies',
    'uk separate' => 'The UK has its own immigration and professional arrangements',
]);

it('does not republish unverified claims', function (string $banned) {
    expect($this->blog->content)->not->toContain($banned);
})->with(['Indeed', 'per hour', 'PwC Middle East page']);

it('cross-links to existing guides and the category', function () {
    expect($this->blog->content)->toContain('/blog/accountant-jobs-in-uae')
        ->toContain('/blog/jobs-in-canada-for-foreign-workers')
        ->toContain('/categories/finance-accounting');
});

it('creates each listing with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country, string $categorySlug) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe($categorySlug)
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae' => ['Accountant — UAE', 'https://careers.ey.com/', 'United Arab Emirates', 'finance-accounting'],
    'canada' => ['Bookkeeper — Canadian', 'https://www.jobbank.gc.ca/jobsearch/jobsearch?fcc=ca&fn21=12200&page=1&sort=M', 'Canada', 'finance-accounting'],
]);

it('renders the guide', function () {
    get('/blog/'.AccountantBookkeeperJobsGulfEuropeBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(AccountantBookkeeperJobsGulfEuropeBlogSeeder::class);

    expect(Blog::where('slug', AccountantBookkeeperJobsGulfEuropeBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::count())->toBe(2);
});
