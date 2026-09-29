<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\HowToApplyReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const APPLY_REACT_SLUG = 'how-to-apply-for-react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(HowToApplyReactDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', APPLY_REACT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/how-to-apply-react-developer-jobs-usa.jpg')
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
    $blog = Blog::where('slug', APPLY_REACT_SLUG)->first();

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
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.APPLY_REACT_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');
    $job = Job::where('position', 'like', 'React Developer Applications%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the job board figures the brief carried', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    foreach (['6,764', '129,348', '134,300', '106,000', '157,000'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('sets out the federal hiring stages from OPM', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('Vacancy announcement')
        ->toContain('Rating and ranking')
        ->toContain('Certification')
        ->toContain('career or career-conditional appointment')
        // Category rating replaced the rule of three, which changes the target
        // from being ranked first to landing in the top category.
        ->toContain('pre-determined quality categories')
        ->toContain('rule of three')
        ->toContain('absolute preference within each category');
});

it('states what an employer may not ask, from the EEOC', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('age (40 or older), disability or genetic information')
        ->toContain('recent college graduates')
        ->toContain('pre-offer inquiries about disability')
        // There is no federal salary history ban, and saying otherwise is the
        // most common error in guides on this topic.
        ->toContain('There is no single federal ban');
});

it('gives the FCRA background check sequence in full', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('stand-alone format')
        ->toContain('written permission to do the background check')
        ->toContain('A Summary of Your Rights Under the Fair Credit Reporting Act')
        ->toContain('free copy within 60 days');
});

it('gives the I-9 deadline and the FTC scam test', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('within three business days of the date employment begins')
        ->toContain('section 274A')
        ->toContain('will never ask you to pay to get a job')
        ->toContain('send you a check to deposit');
});

it('links across the whole React cluster in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);

    $apply = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    foreach ([
        'react-developer-jobs-in-usa',
        'remote-react-developer-jobs-in-usa',
        'entry-level-react-developer-jobs-in-usa',
    ] as $slug) {
        expect($apply)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.APPLY_REACT_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'React Developer Applications%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', APPLY_REACT_SLUG)->update(['content' => 'stale copy']);

    $this->seed(HowToApplyReactDeveloperJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'React Developer Applications%')->count())->toBe(1)
        ->and(Blog::where('slug', APPLY_REACT_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', APPLY_REACT_SLUG)->value('content'))->not->toBe('stale copy');
});
