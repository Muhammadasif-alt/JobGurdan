<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\HowToApplyReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const FE_CODING_TESTS_SLUG = 'frontend-developer-coding-tests-in-usa';

beforeEach(function () {
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', FE_CODING_TESTS_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/frontend-developer-coding-tests-usa.jpg')
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
    $blog = Blog::where('slug', FE_CODING_TESTS_SLUG)->first();

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
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.FE_CODING_TESTS_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Front End Developer Assessments%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the job board figures the brief carried', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    foreach (['1,974', '84,912', '195,946', '549,881', '2,925', '90,930'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('defines a coding test as a selection procedure, from the regulation', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect($content)->toContain('29 CFR Part 1607')
        ->toContain('Any measure, combination of measures, or procedure used as a basis for any employment decision')
        // The second sentence is the load-bearing one: a casual chat counts too.
        ->toContain('performance tests')
        ->toContain('informal or casual interviews and unscored application forms');
});

it('states the job-relatedness standard and who owns a bought-in test', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect($content)->toContain('job-related and consistent with business necessity')
        ->toContain('necessary to the safe and efficient performance of the job')
        ->toContain('as related to the particular job in question')
        ->toContain('the employer is still responsible for ensuring that its tests are valid under UGESP')
        // EEOC guidance explains the law rather than creating it, and says so.
        ->toContain('do not have the force and effect of law');
});

it('gives the four-fifths rule with the caveat that it is not a legal test', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect($content)->toContain('less than four-fifths (4/5) (or eighty percent) of the rate for the group with the highest rate')
        ->toContain('is not intended as a legal definition')
        ->toContain('speaks only to the question of adverse impact')
        // 1607.3(B): prefer the equally valid procedure with less adverse impact.
        ->toContain('lesser adverse impact');
});

it('sets out the accommodation right on a timed test and how to ask', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect($content)->toContain('29 CFR 1630.11')
        ->toContain('allow more time to complete the test')
        ->toContain('the individual with a disability must inform the employer that an accommodation is needed')
        ->toContain("may use 'plain English'")
        ->toContain('do not need to be in writing')
        ->toContain('at any time during the application process');
});

it('answers the unpaid question without inventing a federal rule', function () {
    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    expect($content)->toContain('There is no federal regulation and no Department of Labor fact sheet specifically about unpaid work trials')
        ->toContain('to suffer or permit to work')
        ->toContain('Work not requested but suffered or permitted to be performed is work time that must be paid for by the employer')
        // Fact Sheet #71 was written for interns; three factors are academic.
        ->toContain('it was written for interns and students')
        ->toContain('January 2018');
});

it('links into the cluster in both directions', function () {
    $this->seed(FrontEndDeveloperJobsUsaBlogSeeder::class);
    $this->seed(HowToApplyReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content');

    foreach ([
        'front-end-developer-jobs-in-usa',
        'how-to-apply-for-react-developer-jobs-in-usa',
        'entry-level-react-developer-jobs-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.FE_CODING_TESTS_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Front End Developer Assessments%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', FE_CODING_TESTS_SLUG)->update(['content' => 'stale copy']);

    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Front End Developer Assessments%')->count())->toBe(1)
        ->and(Blog::where('slug', FE_CODING_TESTS_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', FE_CODING_TESTS_SLUG)->value('content'))->not->toBe('stale copy');
});
