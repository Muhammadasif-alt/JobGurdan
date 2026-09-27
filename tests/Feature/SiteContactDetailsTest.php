<?php

use function Pest\Laravel\get;

/**
 * One address and one line, read from config on every page that shows them.
 * They were hardcoded per page once, which is how the site ended up quoting
 * four mailboxes that nobody read.
 */
function siteDigits(): string
{
    return preg_replace('/\D+/', '', (string) config('site.phone'));
}

it('publishes the owner address and nothing else', function () {
    expect(config('site.contact_email'))->toBe('sajaddigitalservices@gmail.com');
});

it('keeps the phone, the local form and the WhatsApp number on one line', function () {
    // wa.me takes digits only, the page shows the readable form, and tel: is
    // built from the readable one — so all three have to agree.
    expect(siteDigits())->toBe('923157033832')
        ->and(preg_replace('/\D+/', '', (string) config('site.whatsapp')))->toBe('923157033832')
        ->and(preg_replace('/\D+/', '', (string) config('site.phone_local')))->toBe('03157033832');
});

it('shows the line and the address on every page that offers contact', function (string $url) {
    $html = get($url)->assertOk()->getContent();

    expect($html)->toContain('tel:'.siteDigits())
        ->toContain(config('site.phone'))
        ->toContain(config('site.contact_email'));
})->with([
    'contact' => '/contact-us',
    'resume writing' => '/resume-writing-services',
    'partners' => '/partners',
]);

it('carries them in the footer of every page', function (string $url) {
    $html = get($url)->assertOk()->getContent();

    expect($html)->toContain('class="footer-contact-line"')
        ->toContain('mailto:'.config('site.contact_email'));
})->with([
    'home' => '/',
    'jobs' => '/jobs',
    'blog' => '/blog',
]);

it('names the phone in the organisation schema', function (string $url) {
    $html = get($url)->assertOk()->getContent();

    expect($html)->toContain('"telephone": "'.config('site.phone').'"');
})->with([
    'home' => '/',
    'about' => '/about-us',
    'contact' => '/contact-us',
]);

it('no longer sends anyone to an empty Calendly page', function () {
    expect(get('/contact-us')->assertOk()->getContent())->not->toContain('calendly.com');
});

it('points the contact schema at a logo that exists', function () {
    $html = get('/contact-us')->assertOk()->getContent();

    preg_match('#"logo": "([^"]+)"#', $html, $m);

    $path = public_path(parse_url($m[1], PHP_URL_PATH));
    $path = str_replace('/public/public/', '/public/', $path);

    expect(file_exists(public_path('user/images/sajjad-dark-logo.png')))->toBeTrue()
        ->and($m[1])->toContain('sajjad-dark-logo.png');
});

it('leaves no page quoting an address the owner does not read', function (string $url) {
    $html = get($url)->assertOk()->getContent();

    expect($html)->not->toContain('infojobgader')
        ->not->toContain('adminjobgader')
        ->not->toContain('jobgader.com');
})->with([
    'home' => '/',
    'contact' => '/contact-us',
    'about' => '/about-us',
    'privacy' => '/privacy-policy',
    'terms' => '/terms-of-service',
]);
