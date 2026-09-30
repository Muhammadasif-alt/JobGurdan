<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\HowToApplyReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const SENIOR_REACT_SLUG = 'senior-react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', SENIOR_REACT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/senior-react-developer-jobs-usa.jpg')
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
    $blog = Blog::where('slug', SENIOR_REACT_SLUG)->first();

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
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.SENIOR_REACT_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Senior React Developer%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the salary site figures the brief carried', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    foreach (['128,400', '109,000', '144,000', '167,500', '118,000', '120,462', '98,608', '61.73'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('states that no federal source defines a senior developer', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('15-1252')
        ->toContain('job titles are not determinative')
        // OPM grades its own developers rather than titling them senior.
        ->toContain('GS-2210')
        ->toContain('Information Technology Specialist')
        ->toContain('There Is No Federal Definition of Senior');
});

it('quotes BLS wages and separates the median from the mean', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670')
        ->toContain('$104,300')
        ->toContain('$92,650')
        // The mean is about nine per cent above the median and is widely
        // quoted as if it were typical pay.
        ->toContain('$148,100')
        ->toContain('The mean is not the median')
        ->toContain('a wage distribution, not a career ladder');
});

it('gives the computer employee exemption at the threshold actually in force', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('$684 per week')
        ->toContain('$35,568')
        ->toContain('$27.63 an hour')
        ->toContain('29 CFR 541.400(b)')
        // The duties test, and the express exclusions at 541.401.
        ->toContain('The application of systems analysis techniques and procedures')
        ->toContain('manufacture or repair of computer hardware');
});

it('dates the vacated 2024 overtime rule instead of quoting it as current', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('$1,128 a week')
        ->toContain('15 November 2024')
        ->toContain('30 December 2024')
        ->toContain('the operative version of the Department\'s part 541 regulations')
        ->toContain('has not been updated since the vacatur');
});

it('explains that mentoring duties engage the executive exemption', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('29 CFR 541.402')
        ->toContain('a senior or lead computer programmer who manages the work of two or more other programmers')
        ->toContain('given particular weight')
        ->toContain('also the duties that remove any overtime claim');
});

it('prices the word senior through the four DOL wage levels', function () {
    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    expect($content)->toContain('Level I (entry)')
        ->toContain('Level II (qualified)')
        ->toContain('Level III (experienced)')
        ->toContain('Level IV (fully competent)')
        // The guidance names the title itself as evidence of the level.
        ->toContain("`senior' (senior programmer)")
        ->toContain('shall initially be considered an entry level or Level I wage')
        ->toContain('20 CFR 655.731(a)')
        ->toContain('shall be the greater of the actual wage rate');
});

it('links across the React cluster in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(HowToApplyReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);

    $content = Blog::where('slug', SENIOR_REACT_SLUG)->value('content');

    foreach ([
        'react-developer-jobs-in-usa',
        'remote-react-developer-jobs-in-usa',
        'entry-level-react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.SENIOR_REACT_SLUG);
    }

    expect(Blog::where('slug', 'how-to-apply-for-react-developer-jobs-in-usa')->value('content'))
        ->toContain('/blog/'.SENIOR_REACT_SLUG);
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Senior React Developer%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', SENIOR_REACT_SLUG)->update(['content' => 'stale copy']);

    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Senior React Developer%')->count())->toBe(1)
        ->and(Blog::where('slug', SENIOR_REACT_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', SENIOR_REACT_SLUG)->value('content'))->not->toBe('stale copy');
});
