<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\JavascriptReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactJsDeveloperContractJobsUsaBlogSeeder;
use Database\Seeders\ReactTypescriptDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const JS_REACT_SLUG = 'javascript-react-developer-jobs-in-usa';

const TS_REACT_SLUG = 'react-typescript-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(JavascriptReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);
});

dataset('language spokes', [
    'javascript' => [JS_REACT_SLUG, 'blogs/javascript-react-developer-jobs-usa.jpg', 'JavaScript React Developer'],
    'typescript' => [TS_REACT_SLUG, 'blogs/react-typescript-developer-jobs-usa.jpg', 'React TypeScript Developer'],
]);

it('publishes each guide inside the snippet limits', function (string $slug, string $image) {
    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($image)
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->meta_title)->not->toMatch('/[&<>"]/')
        ->and(count(explode(',', $blog->tags)))->toBe(8);

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
})->with('language spokes');

it('ships every image each guide references', function (string $slug, string $image) {
    $blog = Blog::where('slug', $slug)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy');
    }

    expect($blog->content)->not->toContain('.png');
})->with('language spokes');

it('carries eight FAQs and eight People Also Search For entries', function (string $slug) {
    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.$slug)->assertOk()->assertSee('"FAQPage"', false);
})->with('language spokes');

it('sends nobody to an aggregator and applies through usajobs', function (string $slug, string $image, string $position) {
    $content = Blog::where('slug', $slug)->value('content');
    $job = Job::where('position', 'like', $position.'%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
})->with('language spokes');

it('republishes none of the salary site figures either brief carried', function () {
    $js = Blog::where('slug', JS_REACT_SLUG)->value('content');
    $ts = Blog::where('slug', TS_REACT_SLUG)->value('content');

    foreach (['129,348', '106,000', '157,000', '110,412', '122,190', '121,000'] as $figure) {
        expect($js)->not->toContain($figure)
            ->and($ts)->not->toContain($figure);
    }
});

it('separates the language standard from the browser standard', function () {
    $content = Blog::where('slug', JS_REACT_SLUG)->value('content');

    expect($content)->toContain('ECMA-262')
        ->toContain('WHATWG HTML Standard')
        // The event loop and timers are not in the language specification.
        ->toContain('It does <em>not</em> define the environment the language runs in')
        ->toContain('the <code>setTimeout()</code> and <code>setInterval()</code> methods allow authors to schedule timer-based callbacks')
        ->toContain('after five such nested timers, however, the interval is forced to be at least four milliseconds');
});

it('dates the ES6 baseline that adverts still ask for', function () {
    $content = Blog::where('slug', JS_REACT_SLUG)->value('content');

    expect($content)->toContain('17 June 2015')
        ->toContain('ECMAScript 2015')
        ->toContain('16th edition in June 2025')
        ->toContain('annual release cycle');
});

it('states that TypeScript types are erased before the code runs', function () {
    $content = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($content)->toContain('Type annotations never change the runtime behavior of your program')
        ->toContain('Most TypeScript-specific code gets erased away')
        // The consequence the brief got wrong.
        ->toContain('An interface describing an API response is a claim about data you have not received yet')
        ->toContain('Validate at the boundary, at runtime')
        ->toContain('Derive the type from the validator');
});

it('gives the TC39 proposal with its stage rather than as settled', function () {
    $content = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($content)->toContain('at runtime, a JavaScript engine ignores them, treating the types as comments')
        ->toContain('stage 1')
        // TypeScript is one vendor's language; JavaScript is a standard.
        ->toContain("aren't part of JavaScript (or ECMAScript to be pedantic)")
        ->toContain('maintained by Microsoft');
});

it('benchmarks both guides on BLS rather than on a salary site', function () {
    $ts = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($ts)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670')
        ->toContain('$92,650');
});

it('links the two guides to each other and into the cluster', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);
    $this->seed(ReactJsDeveloperContractJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);

    $js = Blog::where('slug', JS_REACT_SLUG)->value('content');
    $ts = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($js)->toContain('/blog/'.TS_REACT_SLUG)
        ->and($ts)->toContain('/blog/'.JS_REACT_SLUG);

    foreach ([
        'react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
        'react-js-developer-contract-jobs-in-usa',
        'entry-level-react-developer-jobs-in-usa',
    ] as $slug) {
        expect($js)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.JS_REACT_SLUG);
    }

    foreach ([
        'react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
        'react-js-developer-contract-jobs-in-usa',
        'senior-react-developer-jobs-in-usa',
    ] as $slug) {
        expect($ts)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.TS_REACT_SLUG);
    }
});

it('renders each aggregated job overview', function (string $slug, string $image, string $position) {
    $job = Job::where('position', 'like', $position.'%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
})->with('language spokes');

it('repairs each post instead of duplicating it', function (string $slug, string $image, string $position) {
    Blog::where('slug', $slug)->update(['content' => 'stale copy']);

    $this->seed(JavascriptReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', $position.'%')->count())->toBe(1)
        ->and(Blog::where('slug', $slug)->count())->toBe(1)
        ->and(Blog::where('slug', $slug)->value('content'))->not->toBe('stale copy');
})->with('language spokes');
