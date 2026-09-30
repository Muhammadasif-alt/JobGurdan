<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactJsDeveloperContractJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REACT_CONTRACT_SLUG = 'react-js-developer-contract-jobs-in-usa';

beforeEach(function () {
    $this->seed(ReactJsDeveloperContractJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', REACT_CONTRACT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/react-js-developer-contract-jobs-usa.jpg')
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
    $blog = Blog::where('slug', REACT_CONTRACT_SLUG)->first();

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
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.REACT_CONTRACT_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');
    $job = Job::where('position', 'like', 'React JS Developer Contract%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the job board figures the brief carried', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    foreach (['4,781', '5,582', '129,348', '106,000', '157,000'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('states that unpaid bench time is non-payment of wages', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect($content)->toContain('20 CFR 655.731(c)(7)')
        ->toContain('because of lack of assigned work')
        ->toContain('the full pro-rata amount due')
        // The genuine exceptions, so a reader can tell one from an excuse.
        ->toContain('at his/her voluntary request and convenience')
        ->toContain('bona fide termination of the employment relationship');
});

it('gives the deadline on which the required wage starts', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect($content)->toContain('waiting for an assignment')
        ->toContain('30 days after the worker is first admitted')
        ->toContain('60 days after becoming eligible to work');
});

it('lists what may not be deducted, including a signed-for deduction', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect($content)->toContain('may not recoup a business expense(s) of the employer')
        ->toContain('pay a penalty for ceasing employment')
        ->toContain('whether directly or indirectly, voluntarily or involuntarily')
        // Signing a contract is expressly not voluntary authorisation.
        ->toContain('does not constitute voluntary authorization')
        ->toContain('Any unauthorized deduction taken from wages');
});

it('leaves the superseded statutory fee amount out', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    // The regulation still names the 1998 and 2000 amounts; they are stale.
    expect($content)->not->toContain('$500')
        ->and($content)->not->toContain('$1,000 additional filing fee')
        ->and($content)->toContain('should be checked against USCIS');
});

it('corrects the STEM OPT staffing agency rule rather than repeating the myth', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect($content)->toContain('may find their training opportunity with assistance from a temporary or staffing agency')
        ->toContain('the agency cannot complete and sign the Form I-983')
        ->toContain('must be the same entity that employs the student and provides the practical training experience')
        ->toContain('a new Form I-983 for every new training opportunity')
        ->toContain('enrolled in E-Verify')
        ->toContain('20 hours per week per employer');
});

it('gives the OPT clocks exactly', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    expect($content)->toContain('<strong>12 months</strong>')
        ->toContain('<strong>24 months</strong>')
        ->toContain('<strong>90 days</strong>')
        ->toContain('<strong>150 days</strong>')
        ->toContain('<strong>180 days</strong>');
});

it('does not tell visa holders they have a citizenship status claim', function () {
    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    // 8 USC 1324b(a)(3) excludes H-1B, OPT and CPT holders from "protected
    // individuals", and (a)(4) permits preferring an equally qualified citizen.
    expect($content)->toContain('are not in that class')
        ->toContain('if the two individuals are equally qualified')
        ->toContain('national origin')
        ->toContain('document abuse')
        // The provision does constrain adverts in both directions.
        ->toContain('H-1Bs and OPT Preferred');
});

it('links across the React cluster in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);

    $content = Blog::where('slug', REACT_CONTRACT_SLUG)->value('content');

    foreach ([
        'react-developer-jobs-in-usa',
        'remote-react-developer-jobs-in-usa',
        'senior-react-developer-jobs-in-usa',
        'entry-level-react-developer-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REACT_CONTRACT_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'React JS Developer Contract%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', REACT_CONTRACT_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ReactJsDeveloperContractJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'React JS Developer Contract%')->count())->toBe(1)
        ->and(Blog::where('slug', REACT_CONTRACT_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', REACT_CONTRACT_SLUG)->value('content'))->not->toBe('stale copy');
});
