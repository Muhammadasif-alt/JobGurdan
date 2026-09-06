<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\OnlineJobsWithoutInvestmentBlogSeeder;
use Database\Seeders\VirtualAssistantJobsPakistanBlogSeeder;

use function Pest\Laravel\get;

const VA_SLUG = 'virtual-assistant-jobs-in-pakistan';

const VA_APPLY_URL = 'https://pk.indeed.com/q-virtual-assistant-jobs.html?vjk=005d7746a294c32e';

beforeEach(function () {
    $this->seed(VirtualAssistantJobsPakistanBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', VA_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/virtual-assistant-jobs-pakistan.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', VA_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('says plainly that VA work is a contract rather than employment', function () {
    // The draft called these "jobs" throughout. Almost all are contracts with a
    // foreign client, so no minimum wage applies and nothing is contributed to
    // EOBI on the worker's behalf.
    $body = Blog::where('slug', VA_SLUG)->value('content');

    expect($body)->toContain('A Contract, Not a Job')
        ->toContain('No minimum wage applies')
        ->toContain('EOBI');
});

it('covers the HIPAA obligation attached to medical VA work', function () {
    // The draft described a medical VA handling insurance verification and EOB
    // documents without mentioning that this is protected health information.
    $response = get('/blog/'.VA_SLUG)->assertOk();

    $response->assertSee('protected health information')
        ->assertSee('Business Associate Agreement')
        ->assertSee('HIPAA');
});

it('separates Amazon seller work from Amazon itself and warns on paid courses', function () {
    $body = Blog::where('slug', VA_SLUG)->value('content');

    expect($body)->toContain('Amazon does not hire virtual assistants')
        ->toContain('third-party sellers')
        ->toContain('nobody legitimate sells you a client');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', VA_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Virtual assistant jobs in Lahore');

    get('/blog/'.VA_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(VA_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.VA_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one Pakistan listing and does not duplicate it on a re-run', function () {
    $this->seed(VirtualAssistantJobsPakistanBlogSeeder::class);

    $jobs = Job::where('application_url', VA_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', VA_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('contract work, not employment');
});

it('links to the other remote and freelance guides', function () {
    $this->seed(OnlineJobsWithoutInvestmentBlogSeeder::class);

    get('/blog/'.VA_SLUG)->assertOk()
        ->assertSee('/blog/online-jobs-without-investment-in-pakistan', false)
        ->assertSee('/blog/remote-data-entry-jobs', false)
        ->assertSee('/blog/remote-customer-service-jobs', false);
});
