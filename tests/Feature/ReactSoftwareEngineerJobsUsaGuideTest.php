<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontendDeveloperCodingTestsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactJsDeveloperContractJobsUsaBlogSeeder;
use Database\Seeders\ReactSoftwareEngineerJobsUsaBlogSeeder;
use Database\Seeders\RemoteReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SeniorReactDeveloperJobsUsaBlogSeeder;
use Illuminate\Support\Str;

const REACT_SWE_SLUG = 'react-software-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(ReactSoftwareEngineerJobsUsaBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', REACT_SWE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/react-software-engineer-jobs-usa.jpg')
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
    $blog = Blog::where('slug', REACT_SWE_SLUG)->first();

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
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.REACT_SWE_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('sends nobody to an aggregator and applies through usajobs', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');
    $job = Job::where('position', 'like', 'React Software Engineer%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'weworkremotely', 'dice.com'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('republishes none of the salary site figures the brief carried', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    foreach (['147,524', '120,000', '173,000', '205,000', '70.92', 'Nome'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('taxes RSUs at vesting, as wages on the W-2', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('a taxable event does not take place until the vesting of the Restricted Stock Unit')
        ->toContain('are wages subject to federal income tax withholding')
        ->toContain("reported in Box 1 of the employee's Form W-2")
        ->toContain('social security and Medicare (FICA) taxes')
        // Taxed on vest-date value whether or not the shares are sold.
        ->toContain('whether you sell the shares or keep every one of them')
        // Both IRS documents disclaim precedential weight, and the page says so.
        ->toContain('each disclaims precedential status');
});

it('states that an 83(b) election cannot be made on an RSU', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('26 CFR 1.83-3(e)')
        ->toContain('an unfunded and unsecured promise to pay money or property in the future')
        ->toContain('election cannot be made with respect to the grant of a Restricted Stock Unit')
        // Where it does apply, and the unforgiving deadline.
        ->toContain('not later than 30 days after the date the property was transferred')
        ->toContain('Form 15620');
});

it('explains the flat supplemental rate without overclaiming what the IRS says', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('Withhold a flat 22% (no other percentage allowed)')
        ->toContain('$1 million')
        ->toContain("without regard to the employee's Form W-4")
        // No IRS text calls the 22 per cent "not your tax", so Pub 505 is used.
        ->toContain('You should try to have your withholding match your actual tax liability')
        ->toContain('$184,500');
});

it('separates ISO from NSO and gives the AMT escape', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('the fair market value of the stock received on exercise, less the amount paid')
        ->toContain('code V')
        ->toContain("don't include any amount in income when you exercise the option")
        ->toContain('Form 6251, line 2i')
        // Rarely reported, and it changes the practical advice.
        ->toContain('no adjustment is required if you dispose of the stock in the same year you exercise the option')
        ->toContain('within 2 years from the date of the granting of the option')
        ->toContain('$100,000');
});

it('names the right information returns and the ESPP limits', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('<strong>Form 3921</strong>')
        ->toContain('<strong>Form 3922</strong>')
        ->toContain('85 per cent')
        ->toContain('$25,000 of fair market value')
        // The statute never says "15 per cent", and the page says so.
        ->toContain('neither the statute nor the IRS publication uses that number');
});

it('distinguishes a public grant from a private one', function () {
    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    expect($content)->toContain('Form S-8')
        ->toContain('Rule 701')
        ->toContain('not exempt from the antifraud, civil liability, or other provisions');
});

it('links across the React cluster in both directions', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(SeniorReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(RemoteReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactJsDeveloperContractJobsUsaBlogSeeder::class);
    $this->seed(FrontendDeveloperCodingTestsUsaBlogSeeder::class);

    $content = Blog::where('slug', REACT_SWE_SLUG)->value('content');

    foreach ([
        'react-developer-jobs-in-usa',
        'senior-react-developer-jobs-in-usa',
        'remote-react-developer-jobs-in-usa',
        'react-js-developer-contract-jobs-in-usa',
        'frontend-developer-coding-tests-in-usa',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.REACT_SWE_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'React Software Engineer%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', REACT_SWE_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ReactSoftwareEngineerJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'React Software Engineer%')->count())->toBe(1)
        ->and(Blog::where('slug', REACT_SWE_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', REACT_SWE_SLUG)->value('content'))->not->toBe('stale copy');
});
