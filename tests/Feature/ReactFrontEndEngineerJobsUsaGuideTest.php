<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactFrontEndEngineerJobsUsaBlogSeeder;
use Database\Seeders\ReactSoftwareEngineerJobsUsaBlogSeeder;
use Database\Seeders\RemoteFrontendDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REACT_FEE_SLUG = 'react-front-end-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(ReactFrontEndEngineerJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', REACT_FEE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/react-front-end-engineer-jobs-usa.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->meta_title)->not->toMatch('/[&<>"]/')
        ->and(count(explode(',', $blog->tags)))->toBe(8);

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', REACT_FEE_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy');
    }

    expect($blog->content)->not->toContain('.png');
});

it('carries eight FAQs and eight People Also Search For entries', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.REACT_FEE_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator or a vendor salary guide', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');
    $job = Job::where('position', 'like', 'React Front End Engineer%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com', 'kore1'] as $source) {
        expect(strtolower($content))->not->toContain($source)
            ->and(strtolower($job->application_url))->not->toContain($source);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the salary guide figures the brief carried', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    foreach (['75,000', '190,000', '185,000', '176,000', '112,000', '64,000', '142,000'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('states the absence of a federal requirement plainly', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    expect($content)->toContain('no federal law requires a US employer to publish a salary range in a job advertisement')
        ->toContain('never left committee')
        // EO 11246 was revoked, so the OFCCP rule is not a live federal duty.
        ->toContain('21 January 2025');
});

it('gives each jurisdiction with its own date and threshold', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    foreach ([
        '1 January 2021',   // Colorado
        '1 November 2022',  // New York City
        '1 January 2023',   // Washington and California
        '17 September 2023', // New York State
        '1 October 2024',   // Maryland
        '1 January 2025',   // Minnesota
        '1 June 2025',      // New Jersey
        '1 July 2025',      // Vermont
        '29 October 2025',  // Massachusetts, corrected from 31 October
    ] as $date) {
        expect($content)->toContain($date);
    }

    expect($content)->toContain('30 or more')
        ->toContain('15 or more employees')
        ->toContain('the range cannot be open ended');
});

it('does not assert anything about Illinois', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    // Every Illinois government host was unreachable during checking, so the
    // page names the gap instead of repeating a secondary source.
    expect($content)->toContain('could not be confirmed against an official state source');
});

it('sets out how the rules reach remote roles', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    expect($content)->toContain('could be filled by a Washington-based employee')
        ->toContain('cannot avoid disclosing wage and salary information requirements')
        ->toContain('may ever be filled in California')
        ->toContain('reports to a supervisor, office, or other work site in New York')
        ->toContain('an occasional meeting or conference')
        // Colorado's carve-out runs out in 2029.
        ->toContain('1 July 2029');
});

it('treats a published band as a good faith estimate rather than an offer', function () {
    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    expect($content)->toContain('reasonably and in good faith expects to pay')
        ->toContain('A wide band usually means several levels')
        ->toContain('$135,980')
        ->toContain('$92,650');
});

it('links into the cluster in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactSoftwareEngineerJobsUsaBlogSeeder::class);
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteFrontendDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);

    $content = Blog::where('slug', REACT_FEE_SLUG)->value('content');

    foreach ([
        'react-developer-jobs-in-usa',
        'react-software-engineer-jobs-in-usa',
        'senior-react-developer-jobs-in-usa',
        'remote-frontend-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REACT_FEE_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'React Front End Engineer%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', REACT_FEE_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ReactFrontEndEngineerJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'React Front End Engineer%')->count())->toBe(1)
        ->and(Blog::where('slug', REACT_FEE_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', REACT_FEE_SLUG)->value('content'))->not->toBe('stale copy');
});
