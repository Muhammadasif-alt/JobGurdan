<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FarmWorkerJobUsaFromPakistanBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(FarmWorkerJobUsaFromPakistanBlogSeeder::class);
    $this->blog = Blog::where('slug', FarmWorkerJobUsaFromPakistanBlogSeeder::SLUG)->first();
});

it('publishes with its images and SEO fields', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/farm-worker-job-usa-from-pakistan.jpg')
        ->and($this->blog->content)->toContain('/public/storage/blogs/farm-worker-job-usa-from-pakistan-inline.jpg')
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
})->with(['farm-worker-job-usa-from-pakistan', 'farm-worker-job-usa-from-pakistan-inline']);

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
        ->not->toContain('passport validity exceptions');
});

it('states the verified rules and promises nothing about a visa', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'country lists removed' => 'took effect on 17 January 2025',
    'no guaranteed consular approval' => 'does not guarantee consular approval',
    'employer petitions' => 'files its petition with USCIS',
    'free housing' => 'housing at no cost',
    'no recruitment costs' => 'must not be charged the employer',
    'no tourist visa work' => 'enter as a tourist and start farm work',
]);

it('cross-links to the sibling guides and the category', function () {
    expect($this->blog->content)->toContain('/blog/farm-worker-jobs-usa-visa-sponsorship-2026')
        ->toContain('/blog/farm-worker-salary-usa-2026')
        ->toContain('/categories/general-labour');
});

it('creates no job listing of its own', function () {
    expect(Job::count())->toBe(0);
});

it('renders the guide', function () {
    get('/blog/'.FarmWorkerJobUsaFromPakistanBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(FarmWorkerJobUsaFromPakistanBlogSeeder::class);

    expect(Blog::where('slug', FarmWorkerJobUsaFromPakistanBlogSeeder::SLUG)->count())->toBe(1);
});
