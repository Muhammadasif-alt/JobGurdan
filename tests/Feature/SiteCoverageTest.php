<?php

use App\Services\SiteCoverage;
use Database\Seeders\CleanerJobsSaudiBlogSeeder;
use Database\Seeders\CleanerLondonBlogSeeder;
use Database\Seeders\ConstructionJobsSaudiBlogSeeder;
use Database\Seeders\ConstructionUsaBlogSeeder;
use Database\Seeders\DriverJobsSaudiBlogSeeder;
use Database\Seeders\FrontendDeveloperLahoreSeeder;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

beforeEach(function () {
    Cache::flush();
});

it('falls back to the configured list when nothing is listed yet', function () {
    expect(app(SiteCoverage::class)->countries())->toBe(config('site.fallback_countries'));
});

it('reads the covered countries from the listings themselves, busiest first', function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);
    $this->seed(CleanerJobsSaudiBlogSeeder::class);
    $this->seed(ConstructionJobsSaudiBlogSeeder::class);
    $this->seed(ConstructionUsaBlogSeeder::class);

    $coverage = app(SiteCoverage::class);

    // Saudi Arabia carries three listings to the one in the US, so the copy
    // leads with where the work actually is.
    expect($coverage->countries())->toBe(['Saudi Arabia', 'United States'])
        ->and($coverage->count())->toBe(2)
        ->and($coverage->countWord())->toBe('Two')
        ->and($coverage->countWordLower())->toBe('two')
        ->and($coverage->shortList())->toBe('Saudi Arabia and the USA')
        ->and($coverage->fullList())->toBe('Saudi Arabia and United States');
});

it('abbreviates the country names that read badly in running copy', function () {
    $this->seed(ConstructionUsaBlogSeeder::class);
    $this->seed(CleanerLondonBlogSeeder::class);
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    expect(app(SiteCoverage::class)->shortList())
        ->toContain('the USA')
        ->toContain('the UK')
        ->toContain('Pakistan')
        ->not->toContain('United States')
        ->not->toContain('United Kingdom');
});

it('never renders a definite article in front of a country that does not take one', function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);
    $this->seed(ConstructionUsaBlogSeeder::class);

    // The list is ordered by listing count, so whichever country leads it has
    // to read correctly. A "the" written into the copy produced "across the
    // Saudi Arabia, the UK, ..." the moment Saudi Arabia took the lead.
    foreach (['/', '/about-us', '/companies', '/contact-us', '/jobs', '/blog', '/it-jobs'] as $path) {
        $body = get($path)->assertOk()->getContent();

        expect($body)->not->toContain('the Saudi Arabia')
            ->not->toContain('the Pakistan')
            ->not->toContain('the the ');
    }
});

it('builds areaServed nodes for the Organization graph', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    expect(app(SiteCoverage::class)->areaServedNodes())
        ->toBe([['@type' => 'Country', 'name' => 'Pakistan']]);
});

it('names a newly covered country everywhere the old copy hardcoded three', function () {
    $this->seed(ConstructionUsaBlogSeeder::class);
    $this->seed(CleanerLondonBlogSeeder::class);
    $this->seed(FrontendDeveloperLahoreSeeder::class);
    $this->seed(DriverJobsSaudiBlogSeeder::class);

    // The first Saudi listing used to leave two dozen files still claiming the
    // board covered three countries. These pages read from one source now.
    foreach (['/', '/about-us', '/companies', '/contact-us', '/jobs', '/blog', '/remote-jobs-usa'] as $path) {
        $body = get($path)->assertOk()->getContent();

        expect($body)->toContain('Saudi Arabia')
            ->not->toContain('USA, UK and Pakistan');
    }
});

it('keeps the Organization schema naming the same countries as the copy', function () {
    $this->seed(ConstructionUsaBlogSeeder::class);
    $this->seed(DriverJobsSaudiBlogSeeder::class);

    // The homepage described three countries and then declared a single one in
    // areaServed.
    $body = get('/')->assertOk()->getContent();

    expect($body)->toContain('{"@type":"Country","name":"Saudi Arabia"}')
        ->toContain('{"@type":"Country","name":"United States"}');
});

it('does not leak the concatenation syntax into rendered copy', function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);

    // A country list dropped into HTML rather than a PHP string would print
    // the concatenation verbatim.
    foreach (['/', '/about-us', '/companies', '/contact-us', '/jobs', '/blog', '/it-jobs'] as $path) {
        expect(get($path)->assertOk()->getContent())
            ->not->toContain('$coverage->');
    }
});

it('keeps US-only and old-brand copy from creeping back into the views', function () {
    $offenders = [];

    foreach (glob(resource_path('views/**/*.blade.php')) + glob(resource_path('views/**/**/*.blade.php')) as $file) {
        $body = file_get_contents($file);

        foreach (['across the United States', 'all 50 states', 'All 50 States', 'USA, UK and Pakistan', 'jobs in usa support'] as $phrase) {
            if (str_contains($body, $phrase)) {
                $offenders[] = basename($file).': '.$phrase;
            }
        }
    }

    expect($offenders)->toBeEmpty();
});

it('counts the covered countries on the about page instead of hardcoding three', function () {
    // The stat card still read "3" after Saudi Arabia became the fourth
    // country with listings, contradicting the sentence above it.
    $coverage = app(App\Services\SiteCoverage::class);

    expect(get('/about-us')->assertOk()->getContent())
        ->toContain('<div class="stat-num">'.$coverage->count().'</div>');
});

it('sends the duplicate contact page to the one that is in the sitemap', function () {
    // /contact and /contact-us were both indexable and carried the same form,
    // and the footer linked to the one the sitemap left out.
    get('/contact')->assertRedirect('/contact-us')->assertMovedPermanently();

    $body = get('/')->assertOk()->getContent();

    expect($body)->toContain(route('contact.us'))
        ->not->toContain('href="'.url('/contact').'"');
});
