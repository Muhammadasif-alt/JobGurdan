<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\WarehouseOperativeSalaryUkBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(WarehouseOperativeSalaryUkBlogSeeder::class);
    $this->blog = Blog::where('slug', WarehouseOperativeSalaryUkBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/warehouse-operative-salary-uk.jpg')
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
})->with(['warehouse-operative-salary-uk']);

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
})->with(['£12.71', '£26,437', '£2,203', '£29,120', '£2,427', '£31,200', '£2,600', '£33,280', '£2,773', 'PKR 826,125', 'PKR 910,125', 'PKR 975,000', 'PKR 1,039,875', 'PKR 4,766', 'PKR 705,000', '5.6 weeks']);

it('cross-links to the sibling guides and the category', function () {
    foreach (['/blog/warehouse-operative-jobs-uk-visa-sponsorship-2026', '/blog/warehouse-worker-salary-usa-2026', '/categories/general-labour'] as $link) {
        expect($this->blog->content)->toContain($link);
    }
});

it('renders the guide', function () {
    get('/blog/'.WarehouseOperativeSalaryUkBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(WarehouseOperativeSalaryUkBlogSeeder::class);

    expect(Blog::where('slug', WarehouseOperativeSalaryUkBlogSeeder::SLUG)->count())->toBe(1);
});
