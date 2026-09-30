<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REMOTE_REACT_SLUG = 'remote-react-developer-jobs-in-usa';

const ENTRY_REACT_SLUG = 'entry-level-react-developer-jobs-in-usa';

const REACT_HUB_SLUG = 'react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
});

dataset('react spokes', [
    'remote' => [REMOTE_REACT_SLUG, 'blogs/remote-react-developer-jobs-usa.jpg', 'Remote React Developer'],
    'entry level' => [ENTRY_REACT_SLUG, 'blogs/entry-level-react-developer-jobs-usa.jpg', 'Entry Level React Developer'],
]);

it('publishes each spoke inside the snippet limits', function (string $slug, string $image) {
    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($image)
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->meta_title)->not->toMatch('/[&<>"]/');

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }

    expect(count(explode(',', $blog->tags)))->toBe(8);
})->with('react spokes');

it('ships every image each spoke references', function (string $slug, string $image) {
    $blog = Blog::where('slug', $slug)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy');
    }

    expect($blog->content)->not->toContain('.png');
})->with('react spokes');

it('carries eight FAQs and eight People Also Search For entries', function (string $slug) {
    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.$slug)->assertOk()->assertSee('"FAQPage"', false);
})->with('react spokes');

it('sends nobody to an aggregator and applies through usajobs', function (string $slug, string $image, string $position) {
    $content = Blog::where('slug', $slug)->value('content');
    $job = Job::where('position', 'like', $position.'%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
})->with('react spokes');

it('republishes none of the job board figures the briefs carried', function (string $slug) {
    $content = Blog::where('slug', $slug)->value('content');

    foreach (['6,764', '129,348', '1,624', '100,265', '88,976', '63,500', '134,300'] as $figure) {
        expect($content)->not->toContain($figure);
    }
})->with('react spokes');

it('anchors the remote guide on IRS and DOL rules', function () {
    $content = Blog::where('slug', REMOTE_REACT_SLUG)->value('content');

    expect($content)
        // The threshold nearly every published guide still gets wrong.
        ->toContain('$2,000, not $600')
        ->toContain('15.3 per cent')
        ->toContain('$184,500')
        ->toContain('92.35 per cent')
        // The IRS position that catches remote employees every year.
        ->toContain('Employees are not eligible to claim the home office deduction')
        ->toContain('$5 per square foot')
        ->toContain('Form SS-8');
});

it('dates the Washington income tax rather than calling the state tax free', function () {
    $content = Blog::where('slug', REMOTE_REACT_SLUG)->value('content');

    expect($content)->toContain('Senate Bill 6346')
        ->toContain('1 January 2028')
        ->toContain('P.L. 86-272');
});

it('does not claim a federal definition of employer of record', function () {
    $content = Blog::where('slug', REMOTE_REACT_SLUG)->value('content');

    expect($content)->toContain('no federal definition')
        ->toContain('Certified Professional Employer Organization')
        ->toContain('section 7705')
        // State law decides this, so no blanket federal claim is made.
        ->toContain('decided by <strong>state</strong> law');
});

it('anchors the entry level guide on the BLS quick facts', function () {
    $content = Blog::where('slug', ENTRY_REACT_SLUG)->value('content');

    expect($content)
        ->toContain('Work Experience in a Related Occupation: <strong>None</strong>')
        ->toContain('may not need specific education credentials')
        ->toContain('$48,100')
        ->toContain('$82,460')
        // Labelled as a decile, not passed off as entry level pay.
        ->toContain('are not "entry level pay"')
        ->toContain('5 per cent from 2025 to 2035')
        ->toContain('13,600');
});

it('takes the portfolio advice from React rather than from job adverts', function () {
    $content = Blog::where('slug', ENTRY_REACT_SLUG)->value('content');

    expect($content)->toContain('we recommend starting with a framework')
        ->toContain('Create React App has been deprecated')
        ->toContain('14 February 2025')
        ->toContain('Escape Hatches')
        // React publishes no prerequisite statement, so none is attributed.
        ->toContain('React publishes no formal prerequisite statement');
});

it('states the apprenticeship terms from the regulation', function () {
    $content = Blog::where('slug', ENTRY_REACT_SLUG)->value('content');

    expect($content)->toContain('29 CFR Part 29')
        ->toContain('<strong>2,000 hours</strong>')
        ->toContain('144 hours')
        ->toContain('must not be less than the minimum wage prescribed by the Fair Labor Standards Act')
        ->toContain('An unpaid "apprenticeship" is not a Registered Apprenticeship');
});

it('sets out what junior adverts ask beyond React, on the right WCAG version', function () {
    $body = Blog::where('slug', ENTRY_REACT_SLUG)->value('content');

    expect($body)->toContain('WCAG 2.1 Level AA')
        ->toContain('WCAG 2.2 became a W3C Recommendation on 12 December 2024')
        ->toContain('nine success criteria')
        // Only four of the nine are Level AA, so those are the answerable ones.
        ->toContain('Focus Not Obscured (Minimum)')
        ->toContain('Dragging Movements')
        ->toContain('Target Size (Minimum)')
        ->toContain('Accessible Authentication (Minimum)')
        ->toContain('asking for more than the federal rule requires');
});

it('warns that a junior title can still carry a clearance requirement', function () {
    $body = Blog::where('slug', ENTRY_REACT_SLUG)->value('content');

    expect($body)->toContain('by the work rather than by the seniority')
        ->toContain('/blog/'.REACT_HUB_SLUG);
});

it('links hub and spokes in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);

    $hub = Blog::where('slug', REACT_HUB_SLUG)->value('content');

    foreach ([REMOTE_REACT_SLUG, ENTRY_REACT_SLUG] as $slug) {
        expect($hub)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REACT_HUB_SLUG);
    }

    // The two spokes also point at each other, so neither is a dead end.
    expect(Blog::where('slug', REMOTE_REACT_SLUG)->value('content'))->toContain('/blog/'.ENTRY_REACT_SLUG)
        ->and(Blog::where('slug', ENTRY_REACT_SLUG)->value('content'))->toContain('/blog/'.REMOTE_REACT_SLUG);
});

it('renders each aggregated job overview', function (string $slug, string $image, string $position) {
    $job = Job::where('position', 'like', $position.'%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
})->with('react spokes');

it('repairs each spoke instead of duplicating it', function (string $slug, string $image, string $position) {
    Blog::where('slug', $slug)->update(['content' => 'stale copy']);

    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', $position.'%')->count())->toBe(1)
        ->and(Blog::where('slug', $slug)->count())->toBe(1)
        ->and(Blog::where('slug', $slug)->value('content'))->not->toBe('stale copy');
})->with('react spokes');
