<?php

use function Pest\Laravel\get;

it('uses one contact address across every public page', function () {
    $pages = ['/', '/about-us', '/contact-us', '/privacy-policy', '/terms-of-service', '/contact'];
    $address = config('site.contact_email');

    foreach ($pages as $page) {
        $html = get($page)->assertOk()->getContent();

        // Five different addresses used to be scattered across these pages,
        // none of them a mailbox anyone was reading.
        expect($html)
            ->not->toContain('info@jobgader.com')
            ->not->toContain('support@jobgader.com')
            ->not->toContain('privacy@jobgader.com')
            ->not->toContain('legal@jobgader.com');
    }

    expect(get('/contact-us')->getContent())->toContain($address)
        ->and(get('/privacy-policy')->getContent())->toContain($address)
        ->and(get('/terms-of-service')->getContent())->toContain($address);
});

it('does not restate the legal pages as updated today on every visit', function () {
    foreach (['/privacy-policy', '/terms-of-service'] as $page) {
        expect(get($page)->assertOk()->getContent())
            ->not->toContain('Last updated: '.now()->addDay()->format('F j, Y'))
            ->toContain('Last updated: September 3, 2026');
    }
});

it('drops the old site branding from the employer and candidate directories', function () {
    $companies = get('/companies')->assertOk()->getContent();
    $seekers = get('/job-seekers')->assertOk()->getContent();

    expect($companies)
        ->not->toContain('JobsInUSA')
        ->not->toContain('Fortune 500')
        ->not->toContain('millions of')
        ->and($seekers)
        ->not->toContain('68,000')
        ->not->toContain('2M+')
        ->not->toContain('92%')
        ->not->toContain('millions of Americans');
});

it('keeps the empty candidate directory out of the index', function () {
    // No published profiles yet, so the page has nothing to offer a searcher.
    expect(get('/job-seekers')->assertOk()->getContent())
        ->toContain('content="noindex, follow"');
});

it('carries no invented scale claims anywhere in the views', function () {
    $claims = [
        'JobsinUSA', 'JobsInUSA', 'Fortune 500', 'all 50 U.S. states',
        '10M+', '15K+', '230,000', '68,000', 'trust and safety team',
    ];

    $offenders = [];

    foreach (glob(resource_path('views/**/*.blade.php'), GLOB_BRACE) + glob(resource_path('views/*/*/*.blade.php')) as $file) {
        $body = (string) file_get_contents($file);

        foreach ($claims as $claim) {
            if (str_contains($body, $claim)) {
                $offenders[] = basename($file).': '.$claim;
            }
        }
    }

    expect($offenders)->toBeEmpty();
});
