<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\WarehouseWorkerSalaryUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(WarehouseWorkerSalaryUsaBlogSeeder::class);
    $this->blog = Blog::where('slug', WarehouseWorkerSalaryUsaBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/warehouse-worker-salary-usa.jpg')
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
})->with(['warehouse-worker-salary-usa']);

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
})->with(['$15.03', '$17.35', '$19.35', '$22.56', '$26.51', '$31,270', '$36,090', '$40,240', '$46,920', '$55,140', '$20.32', '$2,606', '$3,008', '$3,353', '$3,910', '$4,595', 'PKR 5,418', 'PKR 938,840', 'PKR 11,267,200']);

it('cross-links to the sibling guides and the category', function () {
    foreach (['/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026', '/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026', '/categories/general-labour'] as $link) {
        expect($this->blog->content)->toContain($link);
    }
});

it('renders the guide', function () {
    get('/blog/'.WarehouseWorkerSalaryUsaBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(WarehouseWorkerSalaryUsaBlogSeeder::class);

    expect(Blog::where('slug', WarehouseWorkerSalaryUsaBlogSeeder::SLUG)->count())->toBe(1);
});
