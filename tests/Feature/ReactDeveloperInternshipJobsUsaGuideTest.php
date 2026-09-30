<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperInternshipJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactJsDeveloperContractJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REACT_INTERN_SLUG = 'react-developer-internship-jobs-in-usa';

beforeEach(function () {
    $this->seed(ReactDeveloperInternshipJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', REACT_INTERN_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/react-developer-internship-jobs-usa.jpg')
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
    $blog = Blog::where('slug', REACT_INTERN_SLUG)->first();

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
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.REACT_INTERN_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and carries no affiliate residue', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');
    $job = Job::where('position', 'like', 'React Developer Internships%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    // The brief arrived wrapped in AIPRM and Semrush affiliate promotion.
    foreach (['aiprm', 'semrush', 'jummaai', 'utm_source=chatgpt', 'citeturn'] as $residue) {
        expect(strtolower($content))->not->toContain($residue);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        // The brief linked employer adverts by requisition id, which rot.
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i')
        ->and($content)->not->toContain('R-2624145')
        ->and($content)->not->toContain('10552937');
});

it('leads with whether the internship has to be paid', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain("The FLSA requires 'for-profit' employers to pay employees for their work.")
        ->toContain('primary beneficiary test')
        ->toContain("examine the 'economic reality' of the intern-employer relationship")
        ->toContain('entitled to both minimum wage and overtime pay under the FLSA')
        ->toContain('public sector and non-profit charitable organizations');
});

it('gives all seven factors and refuses to turn them into a checklist', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    foreach ([
        'there is no expectation of compensation',
        'similar to that which would be given in an educational environment',
        'integrated coursework or the receipt of academic credit',
        'corresponding to the academic calendar',
        'the period in which the internship provides the intern with beneficial learning',
        'complements, rather than displaces, the work of paid employees',
        'without entitlement to a paid job at the conclusion of the internship',
    ] as $factor) {
        expect($content)->toContain($factor);
    }

    expect($content)->toContain('a flexible test, and no single factor is determinative')
        // The six-factor version was rescinded and is still widely quoted.
        ->toContain('5 January 2018');
});

it('sets out CPT from the regulation, including the OPT trap', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain('an integral part of an established curriculum')
        ->toContain('alternative work/study, internship, cooperative education')
        ->toContain('A request for authorization for curricular practical training must be made to the DSO')
        ->toContain('only after receiving their Form I-20')
        // A year of full-time CPT permanently costs post-completion OPT.
        ->toContain('one year or more of full time curricular practical training are ineligible')
        ->toContain('deducted from the post-completion period');
});

it('refuses to present an annualised figure or the occupation median as intern pay', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain('An annualised figure is not what you will be paid')
        ->toContain('The occupation median is not an intern wage')
        ->toContain('$135,980')
        // The employer-specific annualised figure the brief quoted is not used.
        ->and($content)->not->toContain('109,395');
});

it('links into the cluster in both directions', function () {
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);
    $this->seed(ReactJsDeveloperContractJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    foreach ([
        'entry-level-react-developer-jobs-in-usa',
        'react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
        'react-js-developer-contract-jobs-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REACT_INTERN_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'React Developer Internships%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', REACT_INTERN_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ReactDeveloperInternshipJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'React Developer Internships%')->count())->toBe(1)
        ->and(Blog::where('slug', REACT_INTERN_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', REACT_INTERN_SLUG)->value('content'))->not->toBe('stale copy');
});
