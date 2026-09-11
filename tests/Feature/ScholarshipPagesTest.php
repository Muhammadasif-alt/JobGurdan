<?php

use App\Models\Scholarship;
use Database\Seeders\AnuRtpScholarshipSeeder;
use Database\Seeders\MonashRtpScholarshipSeeder;
use Database\Seeders\SydneyBusinessSchoolPhdScholarshipSeeder;
use Database\Seeders\SydneyRtpDomesticScholarshipSeeder;
use Database\Seeders\SydneyRtpInternationalScholarshipSeeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

beforeEach(function () {
    Cache::flush();
});

it('lists twelve scholarships a page and paginates the rest', function () {
    Scholarship::factory()->count(13)->create();

    $firstPage = get(route('scholarships.index'))->assertOk();

    expect(substr_count($firstPage->getContent(), 'class="scholar-card"'))->toBe(12);
    $firstPage->assertSee('page=2', false);

    $secondPage = get(route('scholarships.index', ['page' => 2]))->assertOk();

    expect(substr_count($secondPage->getContent(), 'class="scholar-card"'))->toBe(1);
});

it('keeps drafts and scheduled scholarships off the board and off their own pages', function () {
    $draft = Scholarship::factory()->draft()->create(['title' => 'Hidden Draft Award']);
    $scheduled = Scholarship::factory()->scheduled()->create(['title' => 'Future Scheduled Award']);
    Scholarship::factory()->create(['title' => 'Visible Live Award']);

    get(route('scholarships.index'))->assertOk()
        ->assertSee('Visible Live Award')
        ->assertDontSee('Hidden Draft Award')
        ->assertDontSee('Future Scheduled Award');

    get(route('scholarships.show', $draft->slug))->assertNotFound();
    get(route('scholarships.show', $scheduled->slug))->assertNotFound();
});

it('searches by keyword across the title, the university and the country', function () {
    Scholarship::factory()->create(['title' => 'Chevening Award', 'provider' => 'UK Government', 'country' => 'United Kingdom']);
    Scholarship::factory()->create(['title' => 'DAAD Grant', 'provider' => 'Heidelberg University', 'country' => 'Germany']);

    get(route('scholarships.index', ['q' => 'Heidelberg']))->assertOk()
        ->assertSee('DAAD Grant')
        ->assertDontSee('Chevening Award')
        ->assertSee('noindex, follow', false);

    get(route('scholarships.index', ['q' => 'united kingdom']))->assertOk()
        ->assertSee('Chevening Award')
        ->assertDontSee('DAAD Grant');
});

it('filters by country and study level, and ignores a level it does not offer', function () {
    Scholarship::factory()->create(['title' => 'Aussie Research Award', 'country' => 'Australia', 'study_level' => "PhD, Master's by Research"]);
    Scholarship::factory()->create(['title' => 'Canada Undergraduate Award', 'country' => 'Canada', 'study_level' => "Bachelor's"]);

    get(route('scholarships.index', ['country' => 'Canada']))->assertOk()
        ->assertSee('Canada Undergraduate Award')
        ->assertDontSee('Aussie Research Award');

    get(route('scholarships.index', ['level' => 'master']))->assertOk()
        ->assertSee('Aussie Research Award')
        ->assertDontSee('Canada Undergraduate Award');

    get(route('scholarships.index', ['level' => 'nonsense']))->assertOk()
        ->assertSee('Aussie Research Award')
        ->assertSee('Canada Undergraduate Award');
});

it('says so when nothing matches the search', function () {
    Scholarship::factory()->create();

    get(route('scholarships.index', ['q' => 'no-such-scholarship']))->assertOk()
        ->assertSee('No scholarships match your search')
        ->assertSee('Show All Scholarships');
});

it('publishes each guide with its posters, apply link and SEO fields', function (string $seeder, string $slug, int $contentPosters) {
    $this->seed($seeder);

    $scholarship = Scholarship::where('slug', $slug)->firstOrFail();

    expect($scholarship->isPublished())->toBeTrue()
        ->and($scholarship->featured_image)->toBe('scholarships/'.$slug.'.jpg')
        ->and(substr_count($scholarship->content, '/public/storage/scholarships/'.$slug.'-'))->toBe($contentPosters)
        ->and(strlen($scholarship->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($scholarship->meta_description))->toBeLessThanOrEqual(160)
        // VARCHAR limits on MySQL; SQLite ignores them, so they are asserted.
        ->and(mb_strlen($scholarship->excerpt))->toBeLessThanOrEqual(255)
        ->and(mb_strlen($scholarship->deadline_note))->toBeLessThanOrEqual(150)
        ->and(mb_strlen($scholarship->funding_type))->toBeLessThanOrEqual(60);

    get(route('scholarships.show', $slug))->assertOk()
        ->assertSee($scholarship->title)
        ->assertSee('href="'.$scholarship->apply_url.'"', false)
        ->assertSee('target="_blank" rel="noopener"', false)
        ->assertSee('"FAQPage"', false);
})->with([
    'monash' => [MonashRtpScholarshipSeeder::class, MonashRtpScholarshipSeeder::SLUG, 3],
    'sydney international' => [SydneyRtpInternationalScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::SLUG, 3],
    'sydney domestic' => [SydneyRtpDomesticScholarshipSeeder::class, SydneyRtpDomesticScholarshipSeeder::SLUG, 1],
    'sydney business school' => [SydneyBusinessSchoolPhdScholarshipSeeder::class, SydneyBusinessSchoolPhdScholarshipSeeder::SLUG, 2],
    'anu' => [AnuRtpScholarshipSeeder::class, AnuRtpScholarshipSeeder::SLUG, 3],
]);

it('corrects what the Monash posters and brief get wrong', function () {
    // Round 3 had already closed, IELTS 6.5 is not every faculty's minimum,
    // the stipend does not pay international tuition, and no 2027 rate exists.
    $this->seed(MonashRtpScholarshipSeeder::class);

    expect(Scholarship::where('slug', MonashRtpScholarshipSeeder::SLUG)->value('content'))
        ->toContain('Round 3 closed on 31 July 2026')
        ->toContain('Monash has not confirmed its closing date')
        ->toContain('7.0 overall, at least 6.5 in each part')
        ->toContain('Monash International Tuition Scholarship (MITS)')
        ->toContain('only considered for a scholarship if you apply during an open round')
        ->not->toContain('37,500')
        ->not->toContain('38,000');
});

it('tells Sydney applicants the scholarship form is a separate step', function () {
    // The brief says consideration is automatic; the terms require the form.
    // The posters also carry Monash's contact details.
    $this->seed(SydneyRtpInternationalScholarshipSeeder::class);

    expect(Scholarship::where('slug', SydneyRtpInternationalScholarshipSeeder::SLUG)->value('content'))
        ->toContain('submit the scholarship application form')
        ->toContain('11 September 2026')
        ->toContain('18 December 2026')
        ->toContain('AUD $44,293')
        ->not->toContain('monash.edu')
        ->not->toContain('Departmental');
});

it('gives Sydney domestic applicants the eligibility, tuition and ranking the brief gets wrong', function () {
    // The brief leaves out permanent residents, calls the stipend tax-free,
    // says consideration is automatic and invents a departmental ranking; the
    // posters said there was no tuition reduction. The terms and the selection
    // process document say otherwise.
    $this->seed(SydneyRtpDomesticScholarshipSeeder::class);

    expect(Scholarship::where('slug', SydneyRtpDomesticScholarshipSeeder::SLUG)->value('content'))
        ->toContain('Australian permanent resident')
        ->toContain('16 research periods')
        ->toContain('submit the scholarship application form')
        ->toContain('world ranking of the university')
        ->toContain('registered tax agent')
        ->toContain('/scholarships/'.SydneyBusinessSchoolPhdScholarshipSeeder::SLUG)
        ->not->toContain('tax-free')
        ->not->toContain('Departmental');
});

it('gives Business School applicants the stipend top-up, open round and contacts the brief gets wrong', function () {
    // The brief adds project money on top of the RTP rate, gives a contact
    // email, phone and URL the school does not use, calls the deadlines
    // year-round, asks for IELTS 6.5 and says to find a supervisor first. The
    // scholarship terms, the course page and the English tables say otherwise.
    $this->seed(SydneyBusinessSchoolPhdScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', SydneyBusinessSchoolPhdScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('Business School Supplementary Research Scholarship')
        ->toContain('$28,870')
        ->toContain('30 September 2026')
        ->toContain('considered automatically')
        ->toContain('7.0 overall')
        ->toContain('business.pgresearch@sydney.edu.au')
        ->toContain('do not need to find a supervisor before applying')
        ->not->toContain('business.phd@sydney.edu.au')
        ->not->toContain('9114 8881')
        ->not->toContain('55,000')
        ->not->toContain('business/postgraduate-scholarships');

    $this->travelTo(Carbon::parse('2026-09-30 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 30 Sep 2026');

    $this->travelTo(Carbon::parse('2026-10-01 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Next PhD round closes 1 Feb 2027');

    $this->travelBack();
});

it('gives ANU applicants the closed round, 2027 rate, OSHC, entry marks and contacts the brief gets wrong', function () {
    // The brief shows international Round 1 as upcoming, guesses the 2027
    // rate, denies OSHC, asks for GPA 3.0, stretches the stipend to 3-4
    // years and gives contacts and a URL ANU does not use. ANU's scholarship
    // pages, conditions of award and English policy say otherwise.
    $this->seed(AnuRtpScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', AnuRtpScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('International Round 1 closed on 31 August 2026')
        ->toContain('15 April 2027')
        ->toContain('31 October 2026')
        ->toContain('AUD $40,475')
        ->toContain('Overseas Student Health Cover (OSHC) for you and your immediate family')
        ->toContain('first class honours (H1) or an H1 equivalent')
        ->toContain('6.5 overall, with no band below 6.0')
        ->toContain('3.5 years for a PhD')
        ->toContain('part-time stipends are taxable')
        ->toContain('grs@anu.edu.au')
        ->toContain('fourth in Australia')
        ->not->toContain('scholarships@anu.edu.au')
        ->not->toContain('anu.rtp@anu.edu.au')
        ->not->toContain('6125 8000')
        ->not->toContain('anu.edu.au/students/programs')
        ->not->toContain('HECS')
        ->not->toContain('tax-free');

    $this->travelTo(Carbon::parse('2026-10-31 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 31 Oct 2026');

    $this->travelTo(Carbon::parse('2026-11-01 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Next round closes 15 Apr 2027');

    $this->travelBack();

    foreach ([MonashRtpScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::class, SydneyRtpDomesticScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    expect(Scholarship::where('slug', '!=', AnuRtpScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.AnuRtpScholarshipSeeder::SLUG.'%')->count())->toBe(3);
});

it('moves the Sydney card on to the next round once the first deadline passes', function () {
    $this->seed(SydneyRtpInternationalScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', SydneyRtpInternationalScholarshipSeeder::SLUG)->firstOrFail();

    $this->travelTo(Carbon::parse('2026-09-10 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 11 Sep 2026');

    $this->travelTo(Carbon::parse('2026-09-12 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Next round closes 18 Dec 2026');

    $this->travelBack();
});

it('marks a scholarship closed when its date passes and no later round is noted', function () {
    $scholarship = Scholarship::factory()->make(['deadline' => '2026-01-31', 'deadline_note' => null]);

    $this->travelTo(Carbon::parse('2026-03-01'));

    expect($scholarship->hasClosed())->toBeTrue()
        ->and($scholarship->deadlineLabel())->toBe('Closed 31 Jan 2026');

    $this->travelBack();
});

it('shows the newest scholarships on the homepage, under the latest jobs', function () {
    $this->seed(MonashRtpScholarshipSeeder::class);
    $this->seed(SydneyRtpInternationalScholarshipSeeder::class);

    get(route('home'))->assertOk()
        ->assertSee('id="scholarships-heading"', false)
        ->assertSee(route('scholarships.show', MonashRtpScholarshipSeeder::SLUG), false)
        ->assertSee(route('scholarships.show', SydneyRtpInternationalScholarshipSeeder::SLUG), false)
        ->assertSee('href="'.route('scholarships.index').'"', false);

    $view = file_get_contents(resource_path('views/user/index.blade.php'));

    expect(strpos($view, 'class="home-jobs-section"'))->toBeLessThan(strpos($view, 'class="home-scholar-section"'))
        ->and(strpos($view, 'class="home-scholar-section"'))->toBeLessThan(strpos($view, 'class="industry-section"'));
});

it('links to the scholarships from the main navigation and marks it current there', function () {
    $navigation = function (string $url): string {
        preg_match('#<nav id="navigation">(.*?)</nav>#s', get($url)->assertOk()->getContent(), $match);

        return $match[1] ?? '';
    };

    $scholarshipLink = '#href="'.preg_quote(route('scholarships.index'), '#').'"\s+class="current"#';

    expect($navigation(route('home')))->toContain('href="'.route('scholarships.index').'"')
        ->toContain('Scholarships')
        ->not->toMatch($scholarshipLink);

    expect($navigation(route('scholarships.index')))->toMatch($scholarshipLink);
});

it('leaves the homepage section out when there are no scholarships', function () {
    get(route('home'))->assertOk()->assertDontSee('id="scholarships-heading"', false);
});

it('adds the published scholarship pages to the sitemap', function () {
    $this->seed(MonashRtpScholarshipSeeder::class);
    Scholarship::factory()->draft()->create(['slug' => 'draft-only-award']);

    get('/sitemap.xml')->assertOk()->assertSee(url('/sitemap-scholarships.xml'), false);
    get('/sitemap-core.xml')->assertOk()->assertSee(url('/scholarships'), false);
    get('/sitemap-scholarships.xml')->assertOk()
        ->assertSee(url('/scholarships/'.MonashRtpScholarshipSeeder::SLUG), false)
        ->assertDontSee('draft-only-award', false);
});

it('themes the scholarship pages for dark mode', function (string $selector) {
    expect(file_get_contents(public_path('user/css/site-dark.css')))->toContain('html.dark-mode '.$selector);
})->with([
    '.home-scholar-section',
    '.scholar-card',
    '.scholar-search',
    '.scholar-content',
    '.scholar-side-card',
    '.scholar-detail-hero',
]);
