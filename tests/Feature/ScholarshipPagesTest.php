<?php

use App\Models\Scholarship;
use Database\Seeders\AnuRtpScholarshipSeeder;
use Database\Seeders\AustraliaScholarshipsWithoutIeltsScholarshipSeeder;
use Database\Seeders\FranceScholarshipsWithoutIeltsScholarshipSeeder;
use Database\Seeders\InsubriaScholarshipSeeder;
use Database\Seeders\MelbourneRtpScholarshipSeeder;
use Database\Seeders\MonashRtpScholarshipSeeder;
use Database\Seeders\PaviaScholarshipSeeder;
use Database\Seeders\PolytechnicMarcheScholarshipSeeder;
use Database\Seeders\PortugalScholarshipsSeeder;
use Database\Seeders\SydneyBusinessSchoolPhdScholarshipSeeder;
use Database\Seeders\SydneyRtpDomesticScholarshipSeeder;
use Database\Seeders\SydneyRtpInternationalScholarshipSeeder;
use Database\Seeders\UniversityOfCoimbraMastersScholarshipSeeder;
use Database\Seeders\UniversityOfLeedsCommonwealthMastersScholarshipSeeder;
use Database\Seeders\UniversityOfSouthAustraliaScholarshipSeeder;
use Database\Seeders\YaleUniversityScholarshipSeeder;
use Database\Seeders\YesProgramPakistanScholarshipSeeder;
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
    'melbourne' => [MelbourneRtpScholarshipSeeder::class, MelbourneRtpScholarshipSeeder::SLUG, 3],
    'pavia' => [PaviaScholarshipSeeder::class, PaviaScholarshipSeeder::SLUG, 3],
    'insubria' => [InsubriaScholarshipSeeder::class, InsubriaScholarshipSeeder::SLUG, 3],
    'marche' => [PolytechnicMarcheScholarshipSeeder::class, PolytechnicMarcheScholarshipSeeder::SLUG, 2],
    'yes pakistan' => [YesProgramPakistanScholarshipSeeder::class, YesProgramPakistanScholarshipSeeder::SLUG, 2],
    'australia without ielts' => [AustraliaScholarshipsWithoutIeltsScholarshipSeeder::class, AustraliaScholarshipsWithoutIeltsScholarshipSeeder::SLUG, 2],
    'south australia' => [UniversityOfSouthAustraliaScholarshipSeeder::class, UniversityOfSouthAustraliaScholarshipSeeder::SLUG, 1],
    'france without ielts' => [FranceScholarshipsWithoutIeltsScholarshipSeeder::class, FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG, 3],
    'portugal' => [PortugalScholarshipsSeeder::class, PortugalScholarshipsSeeder::SLUG, 2],
    'coimbra' => [UniversityOfCoimbraMastersScholarshipSeeder::class, UniversityOfCoimbraMastersScholarshipSeeder::SLUG, 2],
    'leeds commonwealth' => [UniversityOfLeedsCommonwealthMastersScholarshipSeeder::class, UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG, 2],
    'yale' => [YaleUniversityScholarshipSeeder::class, YaleUniversityScholarshipSeeder::SLUG, 2],
]);

it('sends UniSA applicants to Adelaide University with the stipend, rounds and contacts the brief gets wrong', function () {
    // The brief quotes a $32,500 stipend, the IPRS, "Adelaide Scholarships
    // International" full funding and a stale Australia Awards allowance, and
    // gives contacts and a URL the university does not use. One poster
    // carries Monash's $37,145 rate. The Conditions of Award and the
    // university's own pages say otherwise.
    $this->seed(UniversityOfSouthAustraliaScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', UniversityOfSouthAustraliaScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('4 August 2025')
        ->toContain('AUD $36,500 a year at the 2026 rate')
        ->toContain("Monash University's rate")
        ->toContain('<strong>no extension</strong>')
        ->toContain('Up to AUD $1,500 for international students')
        ->toContain('<strong>single</strong> BUPA Comprehensive OSHC policy')
        ->toContain('Expressions of interest 31 August to 30 September 2026')
        ->toContain('Australian or New Zealand qualification')
        ->toContain('28 February 2027')
        ->toContain('Applications opened on 24 August 2026')
        ->toContain('IELTS Academic 6.5 overall with at least 6.0')
        ->toContain('Pakistan is not on that list')
        ->toContain('written confirmation of support from an eligible principal supervisor')
        ->toContain('AUD $36,230 a year')
        ->toContain('the Research Training Program replaced it in 2017')
        ->toContain('+61 8 7420 5115')
        ->toContain('research.scholarships@adelaide.edu.au')
        ->not->toContain('study@adelaide.edu.au')
        ->not->toContain('8313 7000')
        ->not->toContain('adelaideuni.edu.au/scholarships');

    $this->travelTo(Carbon::parse('2026-09-30 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 30 Sep 2026');

    $this->travelTo(Carbon::parse('2026-10-01 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Next round: Talent Scheme Round 1, early Oct to early Nov 2026');

    $this->travelBack();

    foreach ([MelbourneRtpScholarshipSeeder::class, AnuRtpScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::class, AustraliaScholarshipsWithoutIeltsScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    expect(Scholarship::where('slug', '!=', UniversityOfSouthAustraliaScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.UniversityOfSouthAustraliaScholarshipSeeder::SLUG.'%')->count())->toBe(4);
});

it('gives France applicants the Eiffel rates, the closed call and the English rules the brief gets wrong', function () {
    // The brief quotes PhD stipends of €1,400 or €2,100, promises full tuition,
    // treats the closed 8 January 2026 deadline as open and names Sciences Po.
    foreach ([FranceScholarshipsWithoutIeltsScholarshipSeeder::class, AustraliaScholarshipsWithoutIeltsScholarshipSeeder::class, PaviaScholarshipSeeder::class, InsubriaScholarshipSeeder::class, PolytechnicMarcheScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    $scholarship = Scholarship::where('slug', FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('&euro;1,181')
        ->toContain('&euro;1,800 (since 1 January 2024)')
        ->toContain('Eiffel does not pay tuition fees.')
        ->toContain('Only applications submitted by French higher education institutions are accepted.')
        ->toContain('8 January 2026')
        ->toContain('From 30 March 2026')
        ->toContain('up to 29 years old at master\'s level and up to 35 at PhD level')
        ->toContain('Dual nationals whose second nationality is French are not eligible')
        ->toContain('Sciences Po will not take part')
        ->toContain('the level of English is also taken into account')
        ->toContain('28 years old maximum')
        ->toContain('<strong>PKR 30,000</strong>')
        ->toContain('&euro;2,902 a year for a bachelor\'s')
        ->toContain('&euro;3,950 a year for a master\'s')
        ->toContain('Pakistan is not on the list')
        ->toContain('/scholarships/'.AustraliaScholarshipsWithoutIeltsScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.PaviaScholarshipSeeder::SLUG)
        ->not->toContain('{slug}')
        ->not->toContain('&euro;1,400 a month')
        ->not->toContain('&euro;2,100 a month');

    expect($scholarship->deadlineLabel())->toBe($scholarship->deadline_note)
        ->and(Scholarship::where('slug', '!=', FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG.'%')->count())->toBe(4);
});

it('gives Portugal applicants the DGES warning, the FCT rates and the Porto facts, and links its European siblings', function () {
    // The brief and posters push a fully funded "Portugal Government Scholarship
    // 2026" for all nationalities, imply free tuition and no IELTS. DGES calls
    // that false; international-statute students get no direct social support;
    // FCT pays EUR 1,359.64 a month (secondary sites still quote EUR 1,259.64);
    // Porto opened 712 places and charges international fees.
    foreach ([PortugalScholarshipsSeeder::class, FranceScholarshipsWithoutIeltsScholarshipSeeder::class, PaviaScholarshipSeeder::class, InsubriaScholarshipSeeder::class, PolytechnicMarcheScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    $scholarship = Scholarship::where('slug', PortugalScholarshipsSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('does not correspond to any scholarship programme promoted by the Portuguese State')
        ->toContain('20 November 2025')
        ->toContain('&euro;1,359.64')
        ->toContain('&euro;1,259.64')
        ->toContain('1,600 planned, including 600 in non-academic environments')
        ->toContain('833 applications')
        ->toContain('31 March 2026')
        ->toContain('provisional results on 31 July 2026')
        ->toContain('myFCT')
        ->toContain('712')
        ->toContain('489 of 712')
        ->toContain('&euro;3,810 a year')
        ->toContain('&euro;3,500 to &euro;16,500')
        ->toContain('international-student regime do not have access to direct social support')
        ->toContain('reduction of up to <strong>45%</strong>')
        ->toContain('/scholarships/'.FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.PaviaScholarshipSeeder::SLUG)
        ->not->toContain('{portugal}')
        ->not->toContain('slugFig');

    expect($scholarship->deadlineLabel())->toBe($scholarship->deadline_note)
        ->and(Scholarship::where('slug', '!=', PortugalScholarshipsSeeder::SLUG)->where('content', 'like', '%/scholarships/'.PortugalScholarshipsSeeder::SLUG.'%')->count())->toBe(4);
});

it('frames the Coimbra award as an up-to-EUR-2,000 UC fee exemption, not a government scholarship, and links Portugal', function () {
    // The brief and banner headline EUR 2,000. It is really a tuition-fee
    // exemption of up to EUR 2,000, scaled by admission score, with CPLP and
    // continuity top-ups; "scientific production" is the purpose, not a
    // deliverable; tuition is ~EUR 7,000 (varies by course); and it is a UC
    // award, not a nationwide "Portugal Government Scholarship".
    foreach ([UniversityOfCoimbraMastersScholarshipSeeder::class, PortugalScholarshipsSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    $scholarship = Scholarship::where('slug', UniversityOfCoimbraMastersScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('tuition-fee exemption of up to &euro;2,000 a year')
        ->toContain('160 points or more')
        ->toContain('Portuguese-speaking (CPLP) countries')
        ->toContain('admission score')
        ->toContain('not a portfolio of publications you must already have')
        ->toContain('typically about &euro;7,000 a year')
        ->toContain('&euro;18,000')
        ->toContain('&euro;648')
        ->toContain('Inforestudante')
        ->toContain('&euro;50')
        ->toContain('international-student status')
        ->toContain('not a nationwide government grant')
        ->toContain('/scholarships/'.PortugalScholarshipsSeeder::SLUG)
        ->toContain('/scholarships/'.FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG)
        ->not->toContain('{$applyUrl}')
        ->not->toContain('slugFig');

    expect($scholarship->deadlineLabel())->toBe($scholarship->deadline_note)
        ->and(Scholarship::where('slug', '!=', UniversityOfCoimbraMastersScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.UniversityOfCoimbraMastersScholarshipSeeder::SLUG.'%')->count())->toBe(1);
});

it('gives Leeds Commonwealth applicants the fully-funded facts, the deadline and the two-process split, with corrected fees', function () {
    // The award is fully funded (FCDO/CSC): tuition, maintenance, return
    // airfare. Deadline 20 October 2026, 4pm BST; opened 8 September 2026.
    // Apply via CSC Central + a nominator, not to Leeds. Fees are full
    // programme totals, IELTS is course-specific, visa proof GBP 1,171/month.
    foreach ([UniversityOfLeedsCommonwealthMastersScholarshipSeeder::class, FranceScholarshipsWithoutIeltsScholarshipSeeder::class, YaleUniversityScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    $scholarship = Scholarship::where('slug', UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('one-year fully funded scholarship')
        ->toContain('20 October 2026 at 4pm BST')
        ->toContain('8 September 2026')
        ->toContain('CSC Central')
        ->toContain('six CSC development themes')
        ->toContain('&pound;1,171 a month for up to nine months')
        ->toContain('full programme totals')
        ->toContain('&pound;33,500')
        ->toContain('&pound;320 a week')
        ->toContain('Russell Group')
        ->toContain('at least one month before the scholarship deadline')
        ->toContain('/scholarships/'.FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.YaleUniversityScholarshipSeeder::SLUG)
        ->not->toContain('{france}')
        ->not->toContain('{$applyUrl}');

    expect($scholarship->deadlineLabel())->toBe('Closes 20 Oct 2026')
        ->and(Scholarship::where('slug', '!=', UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG.'%')->count())->toBe(2);
});

it('gives Yale applicants the income limits, costs, deadlines and PhD rates the brief gets wrong', function () {
    // The brief quotes $65,000 and $75,000 income limits, a $70,000 cap, a
    // $60,000 average grant, a $98,085 total, 2025-26 deadlines, April results,
    // a 4.6% admit rate and "international financial aid forms".
    foreach ([YaleUniversityScholarshipSeeder::class, YesProgramPakistanScholarshipSeeder::class, AustraliaScholarshipsWithoutIeltsScholarshipSeeder::class, FranceScholarshipsWithoutIeltsScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    $scholarship = Scholarship::where('slug', YaleUniversityScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('merit-based scholarships are not offered by Yale')
        ->toContain('below <strong>$100,000</strong>')
        ->toContain('Scholarships meet or exceed the cost of tuition')
        ->toContain('$2,000 start-up grant')
        ->toContain('<strong>$75,220</strong>')
        ->toContain('<strong>$78,742</strong>')
        ->toContain('<td>$72,500</td>')
        ->toContain('<td>$12,080</td>')
        ->toContain('<td>$9,520</td>')
        ->toContain('<strong>$97,985</strong>, plus travel')
        ->toContain('<strong>1 November 2026</strong>')
        ->toContain('<strong>2 January 2027</strong>')
        ->toContain('<td>1 December 2026</td>')
        ->toContain('<td>15 February 2027</td>')
        ->toContain('Late March')
        ->toContain('2,328 of 54,919 applicants, about 4.2%')
        ->toContain('must include scores from the <strong>SAT or ACT</strong>')
        ->toContain('two or more years of enrollment in an English-medium school')
        ->toContain('PTE Academic is not on Yale\'s list.')
        ->toContain('code 3987')
        ->toContain('IDOC')
        ->toContain('at least $52,046')
        ->toContain('<strong>$53,629</strong>')
        ->toContain('$1,500')
        ->toContain('do not receive financial support from the Graduate School')
        ->toContain('/scholarships/'.YesProgramPakistanScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.SydneyRtpInternationalScholarshipSeeder::SLUG)
        ->not->toContain('{yes}')
        ->not->toContain('{apply}')
        ->not->toContain('$65,000 per year')
        ->not->toContain('4.6%')
        ->not->toContain('Results announced in April');

    $this->travelTo(Carbon::parse('2026-11-01 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 1 Nov 2026');

    $this->travelTo(Carbon::parse('2026-11-02 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Regular Decision closes 2 Jan 2027 (fall 2027 entry)');

    $this->travelBack();

    expect(Scholarship::where('slug', '!=', YaleUniversityScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.YaleUniversityScholarshipSeeder::SLUG.'%')->count())->toBe(4);
});

it('replaces the "no IELTS" promise with the English rules each Australian award really sets', function () {
    // The brief and posters say no language test is needed, list Duolingo,
    // quote a stale Australia Awards allowance and RMIT stipend, sell
    // Destination Australia as open and call Deakin's 20% award STEM-only.
    $this->seed(AustraliaScholarshipsWithoutIeltsScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', AustraliaScholarshipsWithoutIeltsScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('overall score of at least 6.5, with no band less than 6.0')
        ->toContain('TOEFL score of at least 84')
        ->toContain('PTE Academic overall score of 58')
        ->toContain('first language is English and you were educated in English')
        ->toContain('AUD $99.26 a day')
        ->toContain('AUD $36,230 a year')
        ->toContain('within half a point of IELTS 6.5')
        ->toContain('does not accept scores from these tests or other at-home or online tests')
        ->toContain('Duolingo English Test')
        ->toContain('$36,245 a year')
        ->toContain('$15,000 a year')
        ->toContain('20 per cent fee sponsorship')
        ->toContain('US$10,000 or less')
        ->toContain('It is not limited to STEM courses')
        ->toContain('no further funding rounds of the program from 1 July 2024')
        ->toContain('/scholarships/'.MonashRtpScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.AnuRtpScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.MelbourneRtpScholarshipSeeder::SLUG)
        ->toContain('/scholarships/'.SydneyRtpInternationalScholarshipSeeder::SLUG)
        ->not->toContain('{monash}');

    expect($scholarship->deadlineLabel())->toBe('Varies by scholarship: see the deadlines table');
});

it('corrects the length, the departure date and the "100% free" claim on the YES posters', function () {
    // All four posters say "One Year", three say "April 2027 Departure" and
    // every one of them says "YOUR COST: $0 (100% FREE!)". The round open now
    // is a January-June 2027 semester, and the passport and medical costs fall
    // on the family. The brief also gives a state.gov contact address.
    $this->seed(YesProgramPakistanScholarshipSeeder::class);

    expect(Scholarship::where('slug', YesProgramPakistanScholarshipSeeder::SLUG)->value('content'))
        ->toContain('Spring 2027 semester')
        ->toContain('January 2027')
        ->toContain('June 2027')
        ->toContain('about six months, not a year')
        ->toContain('15 September 2026')
        ->toContain('1 January 2010 and 1 January 2012')
        ->toContain('publishes a cash value for a YES place')
        ->toContain('info@yesprogram.pk')
        // Applications go on paper, by post or courier, and must arrive by the
        // deadline. Figures found only in news coverage are left out.
        ->toContain('by post or courier')
        ->toContain('only accepted through courier or postal services')
        ->toContain('black ballpoint pen')
        ->toContain('school attested')
        ->toContain('a modest monthly stipend')
        ->toContain('established by Congress in October 2002')
        ->not->toContain('$200')
        ->not->toContain('visa costs')
        ->not->toContain('tuition at')
        // The wrong claims are quoted once, to be knocked down: a reader
        // holding the poster has to recognise what they are holding.
        ->toContain('are describing something that is not on offer')
        ->toContain('is not the programme\'s Pakistani contact');

    // No next round is announced: open until the date, closed after it, and
    // never a "closed" line on the page while applications are still open.
    $scholarship = Scholarship::where('slug', YesProgramPakistanScholarshipSeeder::SLUG)->firstOrFail();

    $this->travelTo(Carbon::parse('2026-09-14 12:00:00'));
    expect($scholarship->deadlineLabel())->toBe('Closes 15 Sep 2026');
    get(route('scholarships.show', YesProgramPakistanScholarshipSeeder::SLUG))->assertOk()
        ->assertSee('Closes 15 Sep 2026')
        ->assertDontSee('Applications closed')
        ->assertDontSee('Closed 15 Sep 2026');

    $this->travelTo(Carbon::parse('2026-09-16 12:00:00'));
    expect($scholarship->hasClosed())->toBeTrue()
        ->and($scholarship->deadlineLabel())->toBe('Closed 15 Sep 2026');

    $this->travelBack();
});

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

it('gives Melbourne applicants the stipend, faculty deadlines, entry marks and contacts the brief gets wrong', function () {
    // The brief and posters say ~$38,500, rolling deadlines, IELTS 6.5 for
    // all, a GPA of 3.0 and a 1,000-word proposal, and give contacts and a URL
    // Melbourne does not use. The award pages, terms and course pages differ.
    $this->seed(MelbourneRtpScholarshipSeeder::class);

    $scholarship = Scholarship::where('slug', MelbourneRtpScholarshipSeeder::SLUG)->firstOrFail();

    expect($scholarship->content)
        ->toContain('AUD $39,500 a year at the 2026 rate')
        ->toContain('has not published a 2027 rate')
        ->toContain('18 September 2026')
        ->toContain('International 15 August 2026')
        ->toContain('single cover only')
        ->toContain('weighted average mark (WAM) of 75%')
        ->toContain('IELTS <strong>7.0 overall</strong>')
        ->toContain('up to 500 words')
        ->toContain('Line up two referees')
        ->toContain('withholds tax')
        ->toContain('#1 in Australia')
        ->toContain('Stop 1')
        ->not->toContain('38,500 a year at')
        ->not->toContain('gradresearch@unimelb.edu.au')
        ->not->toContain('rto@unimelb.edu.au')
        ->not->toContain('9035 3500')
        ->not->toContain('study.unimelb.edu.au/degrees')
        ->not->toContain('GPA')
        ->not->toContain('tax-free');

    expect($scholarship->deadlineLabel())->toBe('Varies by course: Round 1 closes 18 Sep–31 Oct 2026');

    foreach ([AnuRtpScholarshipSeeder::class, MonashRtpScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    expect(Scholarship::where('slug', '!=', MelbourneRtpScholarshipSeeder::SLUG)->where('content', 'like', '%/scholarships/'.MelbourneRtpScholarshipSeeder::SLUG.'%')->count())->toBe(3);
});

it('gives Pavia applicants the waiver count, the flat rate, the ranking and the contacts the brief gets wrong', function () {
    // The brief and posters say 50 fee waivers, call Pavia Italy's oldest
    // university, put it in the top 400, leave out the non-EU flat rate,
    // shrink CICOPS to 6 awards and cap EDiSU at €3,967. The bando, the fee
    // rules, EDiSU's own call and the ranking tables say otherwise.
    $this->seed(PaviaScholarshipSeeder::class);

    expect(Scholarship::where('slug', PaviaScholarshipSeeder::SLUG)->value('content'))
        ->toContain('<strong>120</strong>')
        ->toContain('&euro;390 to &euro;4,550')
        ->toContain('&euro;32,000')
        ->toContain('&euro;26,887.93')
        ->toContain('15 September 2026 at 15:00')
        ->toContain('10 scholarships a year')
        ->toContain('QS World University Rankings 2026: =423')
        ->toContain('Bologna (1088) is older')
        ->toContain('35 km south of Milan')
        ->toContain('welcomeoffice@unipv.it')
        ->toContain('+39 0382 984020')
        ->toContain('do not appear on any official page')
        ->not->toContain('3,967')
        ->not->toContain('100 km');
});

it('gives Insubria applicants the campuses, the fee floor and the IUPALS facts the brief gets wrong', function () {
    // The brief lists Saronno as a campus, says 11,414 students and a flat
    // €156 fee, pays the excellence award "per semester", invents a €12,000
    // "CRUI IUPALS" package and lists essay-contest sites as scholarships.
    $this->seed(InsubriaScholarshipSeeder::class);

    expect(Scholarship::where('slug', InsubriaScholarshipSeeder::SLUG)->value('content'))
        ->toContain('Varese, Como and Busto Arsizio')
        ->toContain('More than 12,000 students')
        ->toContain('&euro;146')
        ->toContain('four instalments of &euro;2,500')
        ->toContain('Italian Universities for Palestinian Students')
        ->toContain('5 October 2026 at 12:00')
        ->toContain('30 September 2026 at 15:00')
        ->toContain('&euro;7,171.11')
        ->toContain('40 ECTS')
        ->toContain('relint@uninsubria.it')
        ->toContain('is not a current campus')
        ->not->toContain('11,414')
        ->not->toContain('per semester')
        ->not->toContain('ServiceScape')
        ->not->toContain('IvyPanda');
});

it('gives Marche applicants the Ancona income limits, the closed call and the ranking the brief gets wrong', function () {
    // The brief says founded 1969 and top-730 in QS, assumes the national
    // ISEE ceilings rather than Marche's lower ones, reads the ERDIS room
    // and meals as an extra on top of the grant, lists Fulbright and Tata
    // as UNIVPM aid, and never mentions the visa money proof.
    $this->seed(PolytechnicMarcheScholarshipSeeder::class);

    expect(Scholarship::where('slug', PolytechnicMarcheScholarshipSeeder::SLUG)->value('content'))
        ->toContain('ISEE of &euro;24,000 or less')
        ->toContain('ISPE of &euro;50,000 or less')
        ->toContain('closed on 28 August 2026')
        ->toContain('120 scholarships of &euro;2,000')
        ->toContain('deducted from the grant rather than added to it')
        ->toContain('&euro;10,179.85')
        ->toContain('&euro;16,243')
        ->toContain('801 to 850')
        ->toContain('90.6%')
        ->toContain('18 January 1971')
        ->toContain('not funded, awarded or administered by UNIVPM')
        ->toContain('matches no published edition')
        ->not->toContain('60122');
});

it('links the three Italian guides to each other and from the Australian ones', function () {
    foreach ([PaviaScholarshipSeeder::class, InsubriaScholarshipSeeder::class, PolytechnicMarcheScholarshipSeeder::class, MelbourneRtpScholarshipSeeder::class, SydneyRtpInternationalScholarshipSeeder::class] as $seeder) {
        $this->seed($seeder);
    }

    foreach ([PaviaScholarshipSeeder::SLUG, InsubriaScholarshipSeeder::SLUG, PolytechnicMarcheScholarshipSeeder::SLUG] as $slug) {
        expect(Scholarship::where('slug', '!=', $slug)->where('content', 'like', '%/scholarships/'.$slug.'%')->count())
            ->toBe(4, 'inbound links to '.$slug);
    }
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
