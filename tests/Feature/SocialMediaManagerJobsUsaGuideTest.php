<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DigitalMarketingJobsUsaBlogSeeder;
use Database\Seeders\SocialMediaManagerJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const SOCIAL_SLUG = 'social-media-manager-jobs-in-usa';

const SOCIAL_APPLY_URL = 'https://www.indeed.com/q-social-media-manager-jobs.html';

beforeEach(function () {
    $this->seed(SocialMediaManagerJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', SOCIAL_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/social-media-manager-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', SOCIAL_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    // Only two images were supplied for this post: featured plus one inline.
    expect($matches[1])->toHaveCount(1);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('raises the entry-level figure the draft set too low', function () {
    // The draft said $35,000-$45,000. Reported pay under a year is nearer
    // $53,000 and the comparable federal occupation's bottom tenth is $44,110.
    $body = Blog::where('slug', SOCIAL_SLUG)->value('content');

    expect($body)->toContain('That is too low')
        ->toContain('$53,000')
        ->toContain('$44,110')
        ->toContain('$135,150');
});

it('carries the FTC rule that prohibits buying followers', function () {
    // Effective 21 October 2024; buying follower growth is a standard shortcut
    // in this job and is now specifically unlawful.
    $response = get('/blog/'.SOCIAL_SLUG)->assertOk();

    $response->assertSee('21 October 2024', false)
        ->assertSee('fake indicators of social media influence', false)
        ->assertSee('$51,744 per violation', false);
});

it('carries the disclosure duty for posting on a brand behalf', function () {
    $body = Blog::where('slug', SOCIAL_SLUG)->value('content');

    expect($body)->toContain('Endorsement Guides took effect on 26 July 2023')
        ->toContain('clearly and conspicuously');
});

it('treats freelance retainers as self-employment with contract terms to fix', function () {
    $body = Blog::where('slug', SOCIAL_SLUG)->value('content');

    expect($body)->toContain('15.3%')
        ->toContain('Schedule C')
        // Account ownership is the term freelancers most often leave undefined.
        ->toContain('who owns the account and its login credentials');
});

it('names paid budget as the lever on pay rather than tenure alone', function () {
    $body = Blog::where('slug', SOCIAL_SLUG)->value('content');

    expect($body)->toContain('largest lever on your pay is whether you touch paid budget');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', SOCIAL_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Community manager jobs');

    get('/blog/'.SOCIAL_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= search-preview parameter; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(SOCIAL_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.SOCIAL_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(SocialMediaManagerJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', SOCIAL_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', SOCIAL_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('$74,750 median');
});

it('links to and from the digital marketing guide it sits inside', function () {
    $this->seed(DigitalMarketingJobsUsaBlogSeeder::class);

    get('/blog/'.SOCIAL_SLUG)->assertOk()
        ->assertSee('/blog/digital-marketing-jobs-in-usa', false)
        ->assertSee('/blog/copywriter-jobs-in-usa', false)
        ->assertSee('/blog/graphic-designer-jobs-in-usa', false);

    get('/blog/digital-marketing-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.SOCIAL_SLUG, false);
});
