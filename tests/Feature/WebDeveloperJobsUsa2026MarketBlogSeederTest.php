<?php

use App\Models\Blog;
use Database\Seeders\WebDeveloperJobsUsa2026MarketBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

const MARKET_BLOG_SLUG = 'web-developer-jobs-in-usa-2026-market-overview';
const CAREER_BLOG_SLUG = 'web-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(WebDeveloperJobsUsa2026MarketBlogSeeder::class);
});

it('publishes the market overview with its images and SEO fields', function () {
    $blog = Blog::where('slug', MARKET_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/web-developer-jobs-in-usa-2026-market.jpg')
        ->and($blog->content)->toContain('web-developer-jobs-in-usa-2026-market-workstation.jpg')
        ->and($blog->content)->toContain('web-developer-jobs-in-usa-2026-market-remote.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.MARKET_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Web Developer Jobs in USA: 2026 Market Overview')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-web-developer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', MARKET_BLOG_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Full stack developer jobs USA');

    $this->get('/blog/'.MARKET_BLOG_SLUG)->assertSee('"FAQPage"', false);
});

it('corrects the draft by treating advertised pay as a biased sample', function () {
    // Every salary in the draft came off an individual listing. Fourteen
    // states require a range in the posting, several of them the most
    // expensive in the country, so the listings that show a number are
    // weighted towards them — averaging what you can see inflates the market.
    $content = Blog::where('slug', MARKET_BLOG_SLUG)->value('content');

    expect($content)->toContain('fourteen states require pay ranges to be disclosed')
        ->toContain('Virginia joining on 1 July 2026 and Maine on 29 July 2026')
        // The honest anchor, stated once and left to the career guide.
        ->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$162,290');
});

it('stays off the career guide\'s territory and links to it instead', function () {
    // Two posts on one occupation only work if they answer different
    // questions. This one must not restate the career guide's depth.
    $content = Blog::where('slug', MARKET_BLOG_SLUG)->value('content');

    expect($content)->toContain('/blog/'.CAREER_BLOG_SLUG);

    // Subjects that belong to the career guide, not here.
    expect($content)->not->toContain('Toptal')
        ->not->toContain('self-employment tax')
        ->not->toContain('Schedule C');
});

it('is linked back from the career guide it sits beside', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', CAREER_BLOG_SLUG)->value('content'))
        ->toContain('/blog/'.MARKET_BLOG_SLUG);
});

it('does not take the career guide\'s slug', function () {
    // Publishing a second post at the same URL would have replaced blog 27
    // outright rather than adding to it.
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', CAREER_BLOG_SLUG)->value('title'))->toBe('Web Developer Jobs in USA');
    expect(Blog::where('slug', MARKET_BLOG_SLUG)->value('title'))->toBe('Web Developer Jobs in USA: 2026 Market Overview');
    expect(Blog::whereIn('slug', [CAREER_BLOG_SLUG, MARKET_BLOG_SLUG])->count())->toBe(2);
});

it('keeps the excerpt inside the column it has to fit', function () {
    // blogs.excerpt is a VARCHAR(255) and SQLite does not enforce it, so
    // without this the first sign of an overrun is a 1406 on deploy.
    expect(strlen(Blog::where('slug', MARKET_BLOG_SLUG)->value('excerpt')))
        ->toBeLessThanOrEqual(255);
});
