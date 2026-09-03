<?php

/**
 * @return array<string, string>
 */
function landingPageBodies(): array
{
    $shared = ['_seo-landing', 'about', 'contact', 'disclaimer', 'privacy', 'terms'];
    $bodies = [];

    foreach (glob(resource_path('views/pages/*.blade.php')) as $file) {
        $name = basename($file, '.blade.php');

        if (! in_array($name, $shared, true)) {
            $bodies[$name] = (string) file_get_contents($file);
        }
    }

    return $bodies;
}

it('gives every landing page its own FAQ set', function () {
    $bodies = landingPageBodies();

    expect($bodies)->toHaveCount(36);

    foreach ($bodies as $name => $body) {
        expect($body)->toContain("'faqs' => [");
    }

    // Identical FAQ blocks across 36 pages meant 36 identical FAQPage graphs.
    $firstQuestions = collect($bodies)->map(function (string $body): string {
        preg_match("/'q' => '(.*?)',/", $body, $matches);

        return $matches[1] ?? '';
    });

    expect($firstQuestions->unique())->toHaveCount(36);
});

it('gives every landing page a unique meta description within the length limit', function () {
    $descriptions = collect(landingPageBodies())->map(function (string $body): string {
        preg_match("/@section\('meta_description', '(.*?)'\)/", $body, $matches);

        return $matches[1] ?? '';
    });

    expect($descriptions->unique())->toHaveCount(36)
        ->and($descriptions->filter(fn (string $d): bool => $d === '' || strlen($d) > 160))->toBeEmpty();
});

it('drops the claims carried over from the old site', function () {
    $bodies = landingPageBodies();
    $bodies['_seo-landing'] = (string) file_get_contents(resource_path('views/pages/_seo-landing.blade.php'));

    foreach ($bodies as $name => $body) {
        expect($body)
            ->not->toContain('trust and safety team')
            ->not->toContain('all 50 U.S. states')
            ->not->toContain('U.S. States')
            ->not->toContain('Create Free Account');
    }
});

it('renders a landing page with its own copy and FAQ', function () {
    $this->get('/warehouse-jobs')->assertOk()
        ->assertSee('Warehouse Jobs')
        ->assertSee('Visa Sponsorship — the Straight Answer', false)
        ->assertSee('Can I get UK visa sponsorship for a warehouse job?')
        ->assertSee('"FAQPage"', false)
        ->assertDontSee('50</strong>', false);
});
