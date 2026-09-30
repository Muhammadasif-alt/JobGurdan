<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AdjunctFacultyVacanciesUkBlogSeeder;
use Database\Seeders\AdjunctLecturerJobsUkBlogSeeder;
use Database\Seeders\AdjunctProfessorJobsUkBlogSeeder;
use Database\Seeders\AdjunctTeachingJobsUkBlogSeeder;

const VACANCIES_SLUG = 'adjunct-faculty-vacancies-in-the-uk';

beforeEach(function () {
    $this->seed(AdjunctFacultyVacanciesUkBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', VACANCIES_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/adjunct-faculty-vacancies-uk.jpg')
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
    $blog = Blog::where('slug', VACANCIES_SLUG)->first();

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
    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.VACANCIES_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('answers a question about job boards without linking one', function () {
    $content = strtolower(Blog::where('slug', VACANCIES_SLUG)->value('content'));

    // The brief's whole answer was a list of job boards.
    foreach (['indeed.com', 'linkedin.com', 'ziprecruiter', 'glassdoor', 'reed.co.uk', 'totaljobs'] as $aggregator) {
        expect($content)->not->toContain($aggregator);
    }

    expect($content)->not->toContain('href="https://www.jobs.ac.uk')
        ->and($content)->not->toContain('href="https://jobs.ac.uk')
        ->and($content)->toContain('legislation.gov.uk');
});

it('maps four employer types rather than treating universities as the market', function () {
    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    expect($content)->toContain('Further education colleges')
        ->toContain('Distance and online providers')
        ->toContain('Professional and executive education')
        ->toContain('It is roughly a quarter of it');
});

it('gives the regulation that makes further education the accessible door', function () {
    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    expect($content)->toContain("Further Education Teachers' Qualifications (England) (Amendment) Regulations 2012")
        ->toContain('30 September 2012')
        ->toContain('colleges have set their own criteria')
        // Scope, because the Regulations are England only.
        ->toContain('these Regulations apply to England');
});

it('explains why the work is not advertised', function () {
    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    expect($content)->toContain('The register, not the vacancy')
        ->toContain('time spent refreshing vacancy pages is close to wasted');
});

it('refuses to invent a subject ranking the data does not support', function () {
    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    expect($content)->toContain('we are not going to publish a ranked list')
        ->toContain('a plausible-sounding list would be a guess');
});

it('keeps off the ground of the other three UK guides', function () {
    $this->seed(AdjunctProfessorJobsUkBlogSeeder::class);
    $this->seed(AdjunctLecturerJobsUkBlogSeeder::class);
    $this->seed(AdjunctTeachingJobsUkBlogSeeder::class);

    $paragraphs = function (string $html): array {
        preg_match_all('#<p[^>]*>(.*?)</p>#s', $html, $m);

        return array_values(array_filter(
            array_map(fn ($p) => trim(preg_replace('/\s+/', ' ', strip_tags($p))), $m[1]),
            fn ($p) => strlen($p) > 100
        ));
    };

    $mine = $paragraphs(Blog::where('slug', VACANCIES_SLUG)->value('content'));

    foreach ([
        AdjunctProfessorJobsUkBlogSeeder::SLUG,
        AdjunctLecturerJobsUkBlogSeeder::SLUG,
        AdjunctTeachingJobsUkBlogSeeder::SLUG,
    ] as $other) {
        $shared = array_intersect($mine, $paragraphs(Blog::where('slug', $other)->value('content')));
        expect($shared)->toBeEmpty('must not share body copy with '.$other);
    }
});

it('links into the UK academic cluster in both directions', function () {
    $this->seed(AdjunctProfessorJobsUkBlogSeeder::class);
    $this->seed(AdjunctLecturerJobsUkBlogSeeder::class);
    $this->seed(AdjunctTeachingJobsUkBlogSeeder::class);

    $content = Blog::where('slug', VACANCIES_SLUG)->value('content');

    foreach ([
        AdjunctProfessorJobsUkBlogSeeder::SLUG,
        AdjunctLecturerJobsUkBlogSeeder::SLUG,
        AdjunctTeachingJobsUkBlogSeeder::SLUG,
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.VACANCIES_SLUG);
    }
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', VACANCIES_SLUG)->update(['content' => 'stale copy']);

    $this->seed(AdjunctFacultyVacanciesUkBlogSeeder::class);

    expect(Blog::where('slug', VACANCIES_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', VACANCIES_SLUG)->value('content'))->not->toBe('stale copy');
});
