<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\ContentWriterJobsUsaBlogSeeder;

const CONTENT_WRITER_SLUG = 'content-writer-jobs-in-usa';

beforeEach(function () {
    $this->seed(ContentWriterJobsUsaBlogSeeder::class);
});

it('publishes the content writer guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', CONTENT_WRITER_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/content-writer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('content-writer-jobs-in-usa-portfolio.jpg')
        ->and($blog->content)->toContain('content-writer-jobs-in-usa-remote.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.CONTENT_WRITER_SLUG)
        ->assertOk()
        ->assertSee('Content Writer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-content-writer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', CONTENT_WRITER_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('SEO content writer jobs');

    $this->get('/blog/'.CONTENT_WRITER_SLUG)->assertSee('"FAQPage"', false);
});

it('contradicts the draft on demand rather than repeating it', function () {
    // The draft opens on growing demand. BLS projects this occupation flat,
    // and the low barrier is why the market is crowded rather than why it
    // is a good bet.
    $content = Blog::where('slug', CONTENT_WRITER_SLUG)->value('content');

    expect($content)->toContain('little or no change from 2025 to 2035')
        ->toContain('$76,910')
        ->toContain('$44,310')
        ->toContain('11,900 openings');
});

it('warns about work for hire before telling anyone to build a portfolio', function () {
    // Every guide says build a portfolio; none says the contract usually
    // assigns the copyright and the byline to the client.
    $content = Blog::where('slug', CONTENT_WRITER_SLUG)->value('content');

    expect($content)->toContain('most content writing is work for hire')
        ->toContain('almost nothing they are allowed to show')
        ->toContain('Get portfolio permission in writing');

    // And the aggregated listing has to carry the same warning.
    expect(Job::where('position', 'like', 'Content Writer%')->value('description'))
        ->toContain('Ask whether you keep the byline');
});

it('separates salaried work from the self-employed majority', function () {
    $content = Blog::where('slug', CONTENT_WRITER_SLUG)->value('content');

    expect($content)->toContain('65 per cent of writers and authors were self-employed');
});

it('stays off the two writing guides it sits beside', function () {
    // The copywriter guide owns the FTC and copyright rules; the AI guide
    // owns Google's position and per-word pricing. Both are linked, not
    // restated, so the three do not compete for one query.
    $content = Blog::where('slug', CONTENT_WRITER_SLUG)->value('content');

    expect($content)->toContain('/blog/copywriter-jobs-in-usa')
        ->toContain('/blog/ai-content-writer-jobs-in-usa');

    // Naming the FTC rules while pointing at the copywriter guide is how a
    // reader knows what is over there. Explaining them here is what would
    // set the two posts competing, so each mention has to sit next to that
    // link rather than at the head of a section of its own.
    preg_match_all('/FTC/', $content, $mentions, PREG_OFFSET_CAPTURE);
    expect($mentions[0])->not->toBeEmpty('the copywriter link lost its description');

    foreach ($mentions[0] as [$_, $offset]) {
        $nearby = substr($content, max(0, $offset - 220), 260);

        expect($nearby)->toContain('/blog/copywriter-jobs-in-usa');
        expect($nearby)->not->toContain('<h2>')->not->toContain('<h3>');
    }
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Content Writer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-content-writer-jobs.html')
        // 65% of the occupation is self-employed and the range runs from
        // under $44,310, so a single salary band would be an invention.
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('is linked back from the copywriter and AI writing guides', function () {
    $this->seed(Database\Seeders\CopywriterJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\AiContentWriterUsaBlogSeeder::class);

    foreach (['copywriter-jobs-in-usa', 'ai-content-writer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.CONTENT_WRITER_SLUG);
    }
});
