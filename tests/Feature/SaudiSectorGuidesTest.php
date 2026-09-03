<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CleanerJobsSaudiBlogSeeder;
use Database\Seeders\ConstructionJobsSaudiBlogSeeder;
use Database\Seeders\SecurityGuardJobsSaudiBlogSeeder;

use function Pest\Laravel\get;

/**
 * The three Saudi sector guides added alongside the driver guide. Each links to
 * an Indeed search rather than one vacancy, so none of them may carry
 * JobPosting markup.
 *
 * @return list<array{0: class-string, 1: string, 2: string, 3: string}>
 */
function saudiGuides(): array
{
    return [
        'cleaner' => [
            CleanerJobsSaudiBlogSeeder::class,
            'cleaner-jobs-in-saudi-arabia-for-foreigners',
            'blogs/cleaner-jobs-in-saudi-arabia-for-foreigners.jpg',
            'https://sa.indeed.com/q-visa-sponsorship,cleaning-jobs-%D9%88%D8%B8%D8%A7%D8%A6%D9%81.html?vjk=c65c3f2948f20c94',
        ],
        'construction' => [
            ConstructionJobsSaudiBlogSeeder::class,
            'construction-jobs-in-saudi-arabia-with-visa-sponsorship',
            'blogs/construction-jobs-in-saudi-arabia-visa-sponsorship.jpg',
            'https://www.indeed.com/jobs?q=saudi+arabia+construction&l=&from=searchOnDesktopSerp&vjk=1d2cbf0c07acc448',
        ],
        'security guard' => [
            SecurityGuardJobsSaudiBlogSeeder::class,
            'security-guard-jobs-in-saudi-arabia',
            'blogs/security-guard-jobs-in-saudi-arabia.jpg',
            'https://www.indeed.com/jobs?q=security+guard&l=Saudi+Arabia',
        ],
    ];
}

it('publishes each guide with the SEO fields filled in', function (string $seeder, string $slug, string $image) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($image)
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and($blog->tags)->not->toBeEmpty()
        ->and($blog->reading_time)->toBeGreaterThan(3)
        // Google truncates a title past roughly 60 characters and a
        // description past roughly 160.
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
})->with(saudiGuides());

it('renders each guide with its inline image and sibling guides', function (string $seeder, string $slug, string $image, string $applyUrl) {
    $this->seed($seeder);

    $response = get('/blog/'.$slug)->assertOk()->assertSee($applyUrl, false);

    // Both images must be referenced, or the post ships with a broken figure.
    $body = Blog::where('slug', $slug)->value('content');
    $stem = str_replace(['blogs/', '.jpg'], '', $image);

    expect($body)->toContain('/public/storage/blogs/')
        ->and($response->getContent())->toContain('More Job Guides')
        ->and($stem)->not->toBeEmpty();
})->with(saudiGuides());

it('carries FAQ markup built from each post body', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', $slug)->value('content'));

    // The "People Also Search For" block uses the same h3/p shape, so the
    // extractor must stop at the end of the FAQ section rather than sweep it up.
    expect($faqs)->toHaveCount(8);

    get('/blog/'.$slug)->assertSee('"FAQPage"', false);
})->with(saudiGuides());

it('drops JobPosting markup because every apply link is a search page', function (string $seeder, string $slug, string $image, string $applyUrl) {
    $this->seed($seeder);

    expect(app(StructuredDataService::class)->describesSingleVacancy($applyUrl))->toBeFalse();

    expect(get('/blog/'.$slug)->getContent())->not->toContain('"JobPosting"');
})->with(saudiGuides());

it('creates one Saudi listing per guide and does not duplicate on a re-run', function (string $seeder, string $slug, string $image, string $applyUrl) {
    $this->seed($seeder);
    $this->seed($seeder);

    $job = Job::where('application_url', $applyUrl)->get();

    expect($job)->toHaveCount(1)
        ->and(Blog::where('slug', $slug)->count())->toBe(1)
        ->and($job->first()->salary_currency)->toBe('SAR')
        ->and($job->first()->salary_period)->toBe('Monthly')
        ->and($job->first()->location->name)->toBe('Saudi Arabia')
        ->and($job->first()->description)->toContain('Never pay for a visa');
})->with(saudiGuides());

it('names the real licensing authority rather than a trade association', function () {
    $this->seed(SecurityGuardJobsSaudiBlogSeeder::class);

    $body = Blog::where('slug', 'security-guard-jobs-in-saudi-arabia')->value('content');

    // POEPA is the promoters' association, not the licensing body. Sending
    // applicants to check the wrong credential is what scam agents rely on.
    expect($body)->toContain('Bureau of Emigration and Overseas Employment')
        ->not->toContain('POEPA');
});

it('explains that the widely quoted guard salary is a per-post contract price', function () {
    $this->seed(SecurityGuardJobsSaudiBlogSeeder::class);

    get('/blog/security-guard-jobs-in-saudi-arabia')
        ->assertOk()
        ->assertSee('The Salary Figure That Is Not a Salary')
        ->assertSee('contract price, not one person', false);
});

it('carries the summer midday work ban in the construction guide', function () {
    $this->seed(ConstructionJobsSaudiBlogSeeder::class);

    get('/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship')
        ->assertOk()
        ->assertSee('12pm and 3pm from 15 June to 15 September');
});

it('separates the Labour Law and Musaned routes in the cleaner guide', function () {
    $this->seed(CleanerJobsSaudiBlogSeeder::class);

    get('/blog/cleaner-jobs-in-saudi-arabia-for-foreigners')
        ->assertOk()
        ->assertSee('Musaned')
        ->assertSee('Wage Protection System');
});
