<?php

use function Pest\Laravel\get;

/** The dark-theme stylesheet, as the browser receives it. */
function darkStylesheet(): string
{
    return file_get_contents(public_path('user/css/site-dark.css'));
}

it('themes the paginations each listing page rolls for itself', function (string $selector) {
    // /jobs, /jobs-categories and /locations style their own pagination
    // rather than reusing .utf-pagination, so the shared rules never reached
    // them: white buttons with navy numerals and arrows on a dark page.
    expect(darkStylesheet())->toContain('html.dark-mode '.$selector);
})->with([
    'jobs' => '.jobs-pagination li a',
    'categories' => '.cat-pagination li a',
    'locations' => '.loc-pagination li a',
]);

it('themes the contact page sections, not just the cards inside them', function (string $selector) {
    // The cards were already themed; the section wrappers holding them kept
    // background: #fff, so dark cards sat on white bands.
    expect(darkStylesheet())->toContain('html.dark-mode '.$selector);
})->with([
    'quick contact' => '.quick-contact-section',
    'form' => '.contact-section',
    'map' => '.contact-map-section',
    'faq' => '.contact-faq-section',
]);

it('still serves the dark stylesheet on the pages that need it', function (string $url) {
    expect(get($url)->assertOk()->getContent())->toContain('site-dark.css');
})->with([
    'jobs' => '/jobs',
    'contact' => '/contact-us',
    'locations' => '/locations',
]);
