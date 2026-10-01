<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AdjunctLecturerJobsUkBlogSeeder;
use Database\Seeders\AdjunctProfessorJobsUkBlogSeeder;
use Database\Seeders\AdjunctTeachingJobsUkBlogSeeder;

const TEACHING_SLUG = 'adjunct-teaching-jobs-in-the-uk';

beforeEach(function () {
    $this->seed(AdjunctTeachingJobsUkBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', TEACHING_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/adjunct-teaching-jobs-uk.jpg')
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
    $blog = Blog::where('slug', TEACHING_SLUG)->first();

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
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.TEACHING_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('never links a job board', function () {
    $content = strtolower(Blog::where('slug', TEACHING_SLUG)->value('content'));

    foreach (['indeed.com', 'linkedin.com', 'ziprecruiter', 'glassdoor', 'reed.co.uk', 'totaljobs'] as $aggregator) {
        expect($content)->not->toContain($aggregator);
    }

    expect($content)->not->toContain('href="https://www.jobs.ac.uk')
        ->and($content)->not->toContain('href="https://jobs.ac.uk')
        ->and($content)->toContain('legislation.gov.uk');
});

it('reverses the brief on exclusivity clauses, with the statute', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    // The brief said "check each contract for exclusivity clauses". The statute
    // makes them void in exactly the contracts it was warning about.
    expect($content)->toContain('is unenforceable against the worker')
        ->toContain('Section 27A of the Employment Rights Act 1996')
        // The statutory test, so the reader can apply it themselves.
        ->toContain('there is no certainty that any such work or services will be made available to the worker')
        // The second limb, for contracts that do guarantee hours.
        ->toContain('Exclusivity Terms for Zero Hours Workers')
        ->toContain('lower earnings limit');
});

it('points the reader at the constraint that does bind', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect($content)->toContain("Your main employer's policy on outside work")
        ->toContain('says nothing about your day job')
        // And the practical one nobody warns about.
        ->toContain('Availability as a grid, not a sentence');
});

it('is honest about what section 27A does not decide', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    // The page gives the test rather than asserting the answer for any contract.
    expect($content)->toContain('turns on its own wording')
        ->toContain('This is general information, not legal advice');
});

it('states the right to work position without repeating the lecturer guide', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect($content)->toContain('&pound;41,700')
        ->toContain('whether your visa route permits supplementary employment');
});

it('keeps off the ground of the other three UK guides', function () {
    $this->seed(AdjunctProfessorJobsUkBlogSeeder::class);
    $this->seed(AdjunctLecturerJobsUkBlogSeeder::class);

    $paragraphs = function (string $html): array {
        preg_match_all('#<p[^>]*>(.*?)</p>#s', $html, $m);

        return array_values(array_filter(
            array_map(fn ($p) => trim(preg_replace('/\s+/', ' ', strip_tags($p))), $m[1]),
            fn ($p) => strlen($p) > 100
        ));
    };

    $mine = $paragraphs(Blog::where('slug', TEACHING_SLUG)->value('content'));

    foreach ([AdjunctProfessorJobsUkBlogSeeder::SLUG, AdjunctLecturerJobsUkBlogSeeder::SLUG] as $other) {
        $shared = array_intersect($mine, $paragraphs(Blog::where('slug', $other)->value('content')));
        expect($shared)->toBeEmpty('must not share body copy with '.$other);
    }
});

it('links into the UK academic cluster in both directions', function () {
    $this->seed(AdjunctProfessorJobsUkBlogSeeder::class);
    $this->seed(AdjunctLecturerJobsUkBlogSeeder::class);

    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    foreach ([AdjunctProfessorJobsUkBlogSeeder::SLUG, AdjunctLecturerJobsUkBlogSeeder::SLUG] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.TEACHING_SLUG);
    }
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', TEACHING_SLUG)->update(['content' => 'stale copy']);

    $this->seed(AdjunctTeachingJobsUkBlogSeeder::class);

    expect(Blog::where('slug', TEACHING_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', TEACHING_SLUG)->value('content'))->not->toBe('stale copy');
});

it('qualifies the section 27A argument with the HESA zero hours count', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect($content)
        ->toContain('SB274')
        ->toContain('3,440 academic staff on zero hours contracts')
        ->toContain('92% of them paid by the hour')
        ->toContain('https://www.hesa.ac.uk/news/19-02-2026/sb274-higher-education-staff-statistics');
});

it('explains why the zero hours count understates the statutory test', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect($content)
        ->toContain('contract marker')
        ->toContain('atypical')
        ->toContain('tells you how many contracts are <em>labelled</em> zero hours');
});

it('does not let the HESA figure read as a cap on who section 27A protects', function () {
    $content = Blog::where('slug', TEACHING_SLUG)->value('content');

    expect($content)->toContain('turns on what a contract says rather than what it is called');
});
