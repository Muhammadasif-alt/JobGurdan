<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AdjunctLecturerJobsUkBlogSeeder;
use Database\Seeders\AdjunctProfessorJobsUkBlogSeeder;
use Database\Seeders\EducationalSupportJobsUsaBlogSeeder;
use Database\Seeders\EslTeacherJobsJapanBlogSeeder;
use Database\Seeders\HeathrowAirportJobsUkBlogSeeder;
use Database\Seeders\JobsInUkForForeignersBlogSeeder;
use Database\Seeders\OnlineTeacherJobsWorldwideBlogSeeder;

const PROFESSOR_SLUG = 'adjunct-professor-jobs-in-the-uk';
const LECTURER_SLUG = 'how-to-become-an-adjunct-lecturer-in-the-uk';

beforeEach(function () {
    $this->seed(AdjunctProfessorJobsUkBlogSeeder::class);
    $this->seed(AdjunctLecturerJobsUkBlogSeeder::class);
});

it('publishes both guides inside the snippet limits', function (string $slug, string $image) {
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
})->with([
    [PROFESSOR_SLUG, 'blogs/adjunct-professor-jobs-uk.jpg'],
    [LECTURER_SLUG, 'blogs/adjunct-lecturer-jobs-uk.jpg'],
]);

it('ships every image both guides reference', function (string $slug) {
    $blog = Blog::where('slug', $slug)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy');
    }

    expect($blog->content)->not->toContain('.png');
})->with([PROFESSOR_SLUG, LECTURER_SLUG]);

it('carries eight FAQs and eight People Also Search For entries on both', function (string $slug) {
    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.$slug)->assertOk()->assertSee('"FAQPage"', false);
})->with([PROFESSOR_SLUG, LECTURER_SLUG]);

it('links to universities and GOV.UK, never to a job board', function (string $slug) {
    $content = strtolower(Blog::where('slug', $slug)->value('content'));

    // The briefs sent readers to jobs.ac.uk, Times Higher Education jobs and
    // LinkedIn. None of them is linked.
    foreach (['indeed.com', 'linkedin.com', 'ziprecruiter', 'glassdoor', 'reed.co.uk', 'totaljobs'] as $aggregator) {
        expect($content)->not->toContain($aggregator);
    }

    expect($content)->not->toContain('href="https://www.jobs.ac.uk')
        ->and($content)->not->toContain('href="https://jobs.ac.uk')
        ->and($content)->toContain('gov.uk/skilled-worker-visa');
})->with([PROFESSOR_SLUG, LECTURER_SLUG]);

it('proves the honorary title is conferred rather than applied for', function () {
    $content = Blog::where('slug', PROFESSOR_SLUG)->value('content');

    expect($content)->toContain('an honour in the gift of the University')
        ->toContain('There is no appeal process')
        // Bath will not confer it while an employment contract exists.
        ->toContain('The title may not be conferred until any contract of employment between the individual and the University has ended')
        ->toContain('The conferment of the title is on an unpaid basis');
});

it('separates honorary from visiting, which most writing does not', function () {
    $content = Blog::where('slug', PROFESSOR_SLUG)->value('content');

    // Heriot-Watt draws the line in one sentence; this is the page's best fact.
    expect($content)->toContain('Holders of Honorary and Emeritus Titles are not permitted to receive Remuneration from the University. Holders of Visiting Titles are permitted to receive Remuneration from the University.')
        ->toContain('must be put into abeyance for the period of the paid work')
        // Essex and QMUL corroborate from different angles.
        ->toContain('makes very clear that this is not an employment relationship')
        ->toContain('does not create or imply the creation of a contract of employment');
});

it('tells the reader an honorary title is not an immigration route', function () {
    $content = Blog::where('slug', PROFESSOR_SLUG)->value('content');

    expect($content)->toContain('will not get you a UK work visa')
        ->toContain('&pound;41,700')
        ->toContain('certificate of sponsorship')
        ->toContain('It fails every limb of the test at once');
});

it('explains the multiplier that decides what a teaching hour pays', function () {
    $content = Blog::where('slug', LECTURER_SLUG)->value('content');

    // The 2004 JNCHES convention, and the three universities that show it.
    expect($content)->toContain("payment for 1.5 additional hours for each hour's teaching")
        ->toContain('comprehensive rate (2.5 x the simple basic rate)')
        ->toContain('4 hours per 1 hour lecture')
        // St Andrews pays prep separately but caps the repeat.
        ->toContain('Preparation time would only be paid for the first session delivery');
});

it('publishes real rates from named universities', function () {
    $content = Blog::where('slug', LECTURER_SLUG)->value('content');

    expect($content)->toContain('&pound;92.10')
        ->toContain('&pound;18.42')
        ->toContain('&pound;50.26')
        ->toContain('&pound;20.10')
        // The sharpest published number in the sector.
        ->toContain('20 minutes (0.333 hours) of marking for every scheduled teaching hour');
});

it('sets the union data against the multiplier', function () {
    $content = Blog::where('slug', LECTURER_SLUG)->value('content');

    expect($content)->toContain('1,568')
        ->toContain('while the median figure was 50%')
        // UCU's own worked example, which is the clearest thing on the page.
        ->toContain('&pound;18.70')
        ->toContain('&pound;9.35')
        ->toContain('almost invariably too low');
});

it('gives the current fellowship framework and its cutoff', function () {
    $content = Blog::where('slug', LECTURER_SLUG)->value('content');

    expect($content)->toContain('PhD students that teach, graduate teaching assistants')
        ->toContain('D1.3 effective and inclusive practice in at least two of the five Areas of Activity')
        // Guides built on the 2011 UKPSF are closed for new applications.
        ->toContain('from 1 January 2024 Advance HE will only accept new fellowship applications based on the PSF 2023');
});

it('gets the visa arithmetic the right way round', function () {
    $content = Blog::where('slug', LECTURER_SLUG)->value('content');

    // The asymmetry is the whole answer, and most guides state it backwards.
    expect($content)->toContain('The going rate scales down with your hours. The &pound;41,700 general threshold does not.')
        ->toContain('2311')
        ->toContain('&pound;52,600')
        ->toContain('hourly-paid teaching is not your route')
        // And the route off the hourly contract.
        ->toContain('110 or more hours');
});

it('keeps the two guides off each other ground', function () {
    $professor = Blog::where('slug', PROFESSOR_SLUG)->value('content');
    $lecturer = Blog::where('slug', LECTURER_SLUG)->value('content');

    $paragraphs = function (string $html): array {
        preg_match_all('#<p[^>]*>(.*?)</p>#s', $html, $m);

        return array_values(array_filter(
            array_map(fn ($p) => trim(preg_replace('/\s+/', ' ', strip_tags($p))), $m[1]),
            fn ($p) => strlen($p) > 100
        ));
    };

    $shared = array_intersect($paragraphs($professor), $paragraphs($lecturer));

    expect($shared)->toBeEmpty('these two pages must not share body copy');
});

it('links the pair to each other and into the cluster in both directions', function () {
    $this->seed(JobsInUkForForeignersBlogSeeder::class);
    $this->seed(OnlineTeacherJobsWorldwideBlogSeeder::class);
    $this->seed(EslTeacherJobsJapanBlogSeeder::class);
    $this->seed(EducationalSupportJobsUsaBlogSeeder::class);
    $this->seed(HeathrowAirportJobsUkBlogSeeder::class);

    $professor = Blog::where('slug', PROFESSOR_SLUG)->value('content');
    $lecturer = Blog::where('slug', LECTURER_SLUG)->value('content');

    expect($professor)->toContain('/blog/'.LECTURER_SLUG)
        ->and($lecturer)->toContain('/blog/'.PROFESSOR_SLUG);

    foreach (['jobs-in-uk-for-foreigners', 'online-teacher-jobs-worldwide', 'how-to-get-an-esl-teaching-job-in-japan'] as $slug) {
        $sibling = Blog::where('slug', $slug)->value('content');
        expect($sibling)->toContain('/blog/'.PROFESSOR_SLUG)
            ->and($sibling)->toContain('/blog/'.LECTURER_SLUG);
    }
});

it('repairs both posts instead of duplicating them', function (string $slug, string $seeder) {
    Blog::where('slug', $slug)->update(['content' => 'stale copy']);

    $this->seed($seeder);

    expect(Blog::where('slug', $slug)->count())->toBe(1)
        ->and(Blog::where('slug', $slug)->value('content'))->not->toBe('stale copy');
})->with([
    [PROFESSOR_SLUG, AdjunctProfessorJobsUkBlogSeeder::class],
    [LECTURER_SLUG, AdjunctLecturerJobsUkBlogSeeder::class],
]);
