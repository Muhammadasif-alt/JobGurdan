<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\LandscaperSalaryUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(LandscaperSalaryUsaBlogSeeder::class);
    $this->blog = Blog::where('slug', LandscaperSalaryUsaBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/landscaper-salary-usa.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/landscaper-salary-usa.jpg')))->toBeTrue();
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
        ->not->toContain('apply now');
});

it('carries the BLS May 2025 figures read from the API', function (string $figure) {
    expect($this->blog->content)->toContain($figure);
})->with(['$20.33', '$18.82', '$42,290', '$26.91', '$24.50', '$55,970', '$29.31', '$28.09', '$60,960']);

it('has monthly, overtime, seasonal and PKR arithmetic that adds up', function () {
    foreach ([18 => 3120, 20 => 3467, 22 => 3813] as $rate => $monthly) {
        expect((int) round($rate * 40 * 52 / 12))->toBe($monthly)
            ->and($this->blog->content)->toContain('$'.number_format($monthly))
            ->toContain('PKR '.number_format($monthly * 280));
    }

    expect((int) round(20.33 * 40 * 52 / 12))->toBe(3524)
        ->and(20 * 40 + 10 * 30)->toBe(1100)
        ->and(20 * 40 * 26)->toBe(20800)
        ->and(3467 - 1200)->toBe(2267)
        ->and($this->blog->content)->toContain('$3,524')
        ->toContain('$1,100')
        ->toContain('$20,800')
        ->toContain('$2,267');
});

it('cross-links to the visa guide and the category', function () {
    expect($this->blog->content)->toContain('/blog/landscaper-jobs-usa-visa-sponsorship-2026')
        ->toContain('/categories/general-labour');
});

it('renders the guide', function () {
    get('/blog/'.LandscaperSalaryUsaBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(LandscaperSalaryUsaBlogSeeder::class);

    expect(Blog::where('slug', LandscaperSalaryUsaBlogSeeder::SLUG)->count())->toBe(1);
});
