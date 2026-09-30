<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\FullStackDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FullStackReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactSoftwareEngineerJobsUsaBlogSeeder;
use Database\Seeders\ReactTypescriptDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const FS_REACT_SLUG = 'full-stack-react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(FullStackReactDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', FS_REACT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/full-stack-react-developer-jobs-usa.jpg')
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
    $blog = Blog::where('slug', FS_REACT_SLUG)->first();

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
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.FS_REACT_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('links only to employer careers searches, never to a requisition', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Full Stack React Developer%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator);
    }

    // The brief arrived wrapped in affiliate promotion and linked adverts by id.
    foreach (['aiprm', 'semrush', 'jummaai', 'R-2624145', 'R-2607670', '10552937'] as $residue) {
        expect(strtolower($content))->not->toContain(strtolower($residue));
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->toContain('amazon.jobs/en/job-category/software-development')
        ->and($content)->toContain('careers.walmart.com/us/en')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i')
        // No vacancy counts, including the brief's employer totals.
        ->and($content)->not->toContain('2,600');
});

it('quotes the FTC guidance that speaks to developers', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain("Don't collect personal information you don't need.")
        ->toContain('failed to adequately assess their applications for well-known vulnerabilities')
        ->toContain('Structured Query Language (SQL) injection attack')
        ->toContain('predictable resource location')
        ->toContain('tried-and-true industry-tested and accepted methods')
        // The FTC's own caveat about its settlements.
        ->toContain('no findings have been made by a court');
});

it('gives the Safeguards Rule scope and the secure development duty', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain('16 CFR Part 314')
        ->toContain("mortgage lenders, 'pay day' lenders, finance companies")
        ->toContain('Adopt secure development practices for in-house developed applications')
        ->toContain('both in transit over external networks and at rest')
        ->toContain('at least every six months')
        // Notification: 500 consumers, 30 days, in force since 13 May 2024.
        ->toContain('<strong>500 consumers</strong>')
        ->toContain('13 May 2024');
});

it('states the HIPAA position without calling encryption required', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain('business associate')
        ->toContain('subcontractor that creates, receives, maintains, or transmits protected health information')
        ->toContain('Covered entities and business associates must do the following')
        // Encryption is addressable, not required, which most writing gets wrong.
        ->toContain('addressable, not required')
        ->toContain('addressable does not mean optional');
});

it('states that breach notification is state law and the clocks differ', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain('there is no single federal breach notification law')
        ->toContain('within 30 calendar days of discovery or notification of the data breach')
        ->toContain('within 15 calendar days of notifying affected consumers')
        ->toContain('after the date of determination that a security breach has occurred')
        ->toContain('one clock starts on discovery, the other on determination');
});

it('refuses to claim the developer is personally liable', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain('<strong>The duties run to the company, not to you.</strong>')
        ->toContain('No official source says an individual developer bears legal liability')
        // The two genuine exceptions, stated so they are not overread.
        ->toContain('not about writing insecure code')
        ->toContain('senior officer with information security responsibilities');
});

it('carries the NIST framework practice names', function () {
    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    expect($content)->toContain('SP 800-218')
        ->toContain('Produce Well-Secured Software')
        ->toContain('Create Source Code by Adhering to Secure Coding Practices')
        ->toContain('Respond to Vulnerabilities');
});

it('links into the cluster in both directions', function () {
    $this->seed(FullStackDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactSoftwareEngineerJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', FS_REACT_SLUG)->value('content');

    foreach ([
        'full-stack-developer-jobs-in-usa',
        'react-developer-jobs-in-usa',
        'react-software-engineer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
        'react-typescript-developer-jobs-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.FS_REACT_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Full Stack React Developer%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', FS_REACT_SLUG)->update(['content' => 'stale copy']);

    $this->seed(FullStackReactDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Full Stack React Developer%')->count())->toBe(1)
        ->and(Blog::where('slug', FS_REACT_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', FS_REACT_SLUG)->value('content'))->not->toBe('stale copy');
});
