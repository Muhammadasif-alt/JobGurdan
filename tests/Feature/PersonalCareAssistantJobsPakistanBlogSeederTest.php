<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DataEntryJobsPakistanBlogSeeder;
use Database\Seeders\DisabilitySupportWorkerJobsPakistanBlogSeeder;
use Database\Seeders\ElderlyCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\PersonalCareAssistantJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const PCA_SLUG = 'personal-care-assistant-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(PersonalCareAssistantJobsPakistanBlogSeeder::class);
});

it('publishes the personal care guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', PCA_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/personal-care-assistant-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', PCA_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', PCA_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy for a blog image');
    }

    expect($blog->content)->not->toContain('.png');
});

it('renders with its long-tail sections', function () {
    $this->get('/blog/'.PCA_SLUG)
        ->assertOk()
        ->assertSee('Personal Care Assistant Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('labour.punjab.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', PCA_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.PCA_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Personal Care Assistant%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://saharahomehealthcare.com/jobs.php');
});

it('carries the statute the brief never mentioned', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');

    // The brief covered duties and told the reader to "confirm with the
    // employer". This is what there is to confirm against.
    expect($content)->toContain('Punjab Domestic Workers Act 2019')
        ->toContain('letter of employment in the prescribed form')
        ->toContain('one whole day')
        ->toContain('labour.punjab.gov.pk/punjab-domestic-act');
});

it('states the entitlements by section so a reader can cite them', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');

    foreach (['Section 5', 'Section 6(1)', 'Section 6(2)', 'Section 11', 'Section 14', 'Section 4(4)'] as $section) {
        expect($content)->toContain($section);
    }
});

it('tells a live-in carer their identity documents cannot be kept', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');

    expect($content)->toContain('They cannot keep your CNIC')
        ->toContain('shall not be retained')
        ->toContain('Dispute Resolution Committee');
});

it('admits the Act is provincial rather than implying it covers Pakistan', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');

    // A Karachi reader must not be told they have Punjab entitlements.
    expect($content)->toContain('This Act is provincial')
        ->toContain('a post in Karachi is not covered by the Punjab statute');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', PCA_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Personal Care Assistant%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', PCA_SLUG)->update(['content' => 'stale copy']);

    $this->seed(PersonalCareAssistantJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Personal Care Assistant%')->count())->toBe(1)
        ->and(Blog::where('slug', PCA_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', PCA_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(ElderlyCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(DisabilitySupportWorkerJobsPakistanBlogSeeder::class);
    $this->seed(DataEntryJobsPakistanBlogSeeder::class);

    $pca = Blog::where('slug', PCA_SLUG)->value('content');

    foreach ([
        'home-healthcare-assistant-jobs-in-pakistan',
        'elderly-care-assistant-jobs-in-pakistan',
        'disability-support-worker-jobs-in-pakistan',
        'data-entry-jobs-in-pakistan',
    ] as $slug) {
        expect($pca)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.PCA_SLUG);
    }
});
