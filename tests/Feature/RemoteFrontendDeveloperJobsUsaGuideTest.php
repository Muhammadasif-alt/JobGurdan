<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\RemoteFrontendDeveloperJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REMOTE_FE_SLUG = 'remote-frontend-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(RemoteFrontendDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', REMOTE_FE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/remote-frontend-developer-jobs-usa.jpg')
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
    $blog = Blog::where('slug', REMOTE_FE_SLUG)->first();

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
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.REMOTE_FE_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Remote Front End Developer%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the job board figures the brief carried', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    foreach (['766', '2,925', '84,912', '195,946', '36,065', '549,881', '1,435'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('explains that a remote advert may lawfully exclude a state', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect($content)->toContain('There is no federal law requiring an employer to hire in any particular state')
        // The EEOC's protected list is exhaustive and residence is not on it.
        ->toContain('age (40 or older), disability or genetic information')
        ->toContain('nexus based solely on the activities of that employee')
        ->toContain('P.L. 86-272')
        // The position rests on an absence, and the page says so rather than
        // implying an agency has affirmatively permitted it.
        ->toContain('follows from the <em>absence</em>');
});

it('gives the general sourcing rule and the convenience of the employer trap', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect($content)->toContain('services performed entirely outside of Connecticut')
        ->toContain('non-Pennsylvania source income')
        ->toContain('unless your employer has established a bona fide employer office at your telecommuting location')
        ->toContain('contains or is near specialized facilities')
        ->toContain('Delaware')
        ->toContain('convenience rule wages')
        // New Jersey and Connecticut mirror the worker's home state rule.
        ->toContain("apply another state's Convenience of the Employer Rule");
});

it('refuses to list Pennsylvania as a convenience of the employer state', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    // Published lists include PA; its own Department of Revenue says otherwise.
    expect($content)->toContain('Pennsylvania is not a convenience of the employer state');
});

it('covers reciprocity and the certificate it requires', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect($content)->toContain('exempt from any income tax imposed by a reciprocal state')
        ->toContain('Wisconsin, Indiana, Kentucky, Illinois, Ohio and Minnesota')
        ->toContain('statement of nonresidence');
});

it('explains why a cleared remote role is not remote, without overclaiming', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect($content)->toContain('32 CFR Part 117')
        ->toContain('GSA)-approved security containers')
        ->toContain('The CSA must authorize the system before the contractor can use the system to process classified information')
        ->toContain('permits interception by unauthorized persons')
        // No official source forbids a residence outright, so the page says the
        // rules impose conditions a home cannot meet instead.
        ->toContain('do not contain a sentence forbidding a home office')
        // DoDI 1035.01 binds the DoD workforce, not a contractor's staff.
        ->toContain('its own civilian employees and Service members');
});

it('states that a clearance cannot be self-initiated and is not Public Trust', function () {
    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    expect($content)->toContain('Applicants cannot initiate a security clearance application on their own')
        ->toContain('Public Trust is a type of background investigation, but it is not a security clearance');
});

it('links across the cluster in both directions', function () {
    $this->seed(FrontEndDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', REMOTE_FE_SLUG)->value('content');

    foreach ([
        'front-end-developer-jobs-in-usa',
        'react-developer-jobs-in-usa',
        'remote-react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
        'senior-react-developer-jobs-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REMOTE_FE_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Remote Front End Developer%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', REMOTE_FE_SLUG)->update(['content' => 'stale copy']);

    $this->seed(RemoteFrontendDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Remote Front End Developer%')->count())->toBe(1)
        ->and(Blog::where('slug', REMOTE_FE_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', REMOTE_FE_SLUG)->value('content'))->not->toBe('stale copy');
});
