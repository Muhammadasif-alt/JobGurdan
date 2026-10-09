<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::class);
    $this->blog = Blog::where('slug', WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/warehouse-operative-jobs-uk-visa-sponsorship-poster.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its images on disk', function (string $image) {
    expect(file_exists(storage_path("app/public/blogs/{$image}.jpg")))->toBeTrue("{$image}.jpg is missing");
})->with(['warehouse-operative-jobs-uk-visa-sponsorship-poster']);

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
        ->not->toContain('apply now');
});

it('states the verified facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with(['22 July 2025', '£41,700', '8 January 2026', '£12.71', '£26,437', '£2,203', 'RQF Level 6', 'Pakistan is not on the list']);

it('cross-links to the sibling guides and the category', function () {
    foreach (['/blog/warehouse-operative-salary-uk-2026', '/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026', '/categories/general-labour'] as $link) {
        expect($this->blog->content)->toContain($link);
    }
});

it('renders the guide', function () {
    get('/blog/'.WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::SLUG)->count())->toBe(1);
});
