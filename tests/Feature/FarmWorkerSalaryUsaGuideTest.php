<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\FarmWorkerSalaryUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(FarmWorkerSalaryUsaBlogSeeder::class);
    $this->blog = Blog::where('slug', FarmWorkerSalaryUsaBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/farm-worker-salary-usa.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/farm-worker-salary-usa.jpg')))->toBeTrue();
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
        ->not->toContain('september 2026');
});

it('carries the BLS May 2025 figures read from the API', function (string $figure) {
    expect($this->blog->content)->toContain($figure);
})->with(['$18.09', '$17.15', '$37,630', '$18.88', '$17.63', '$39,260', '$21.02', '$20.06', '$43,710']);

it('has monthly and PKR arithmetic that adds up', function () {
    foreach ([15 => 2600, 18 => 3120, 20 => 3467] as $rate => $monthly) {
        expect((int) round($rate * 40 * 52 / 12))->toBe($monthly)
            ->and($this->blog->content)->toContain('$'.number_format($monthly))
            ->toContain('PKR '.number_format($monthly * 280));
    }

    expect((int) round(18.09 * 40 * 52 / 12))->toBe(3136)
        ->and(18 * 40 * 26)->toBe(18720)
        ->and($this->blog->content)->toContain('$18,720');
});

it('cross-links to the visa guide and the category', function () {
    expect($this->blog->content)->toContain('/blog/farm-worker-jobs-usa-visa-sponsorship-2026')
        ->toContain('/categories/general-labour');
});

it('renders the guide', function () {
    get('/blog/'.FarmWorkerSalaryUsaBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(FarmWorkerSalaryUsaBlogSeeder::class);

    expect(Blog::where('slug', FarmWorkerSalaryUsaBlogSeeder::SLUG)->count())->toBe(1);
});
