<?php

it('tells one consistent story about how the site earns money and who it serves', function () {
    $about = $this->get('/about-us')->assertOk()->getContent();

    expect($about)->not->toContain('We make money from employers who pay to post jobs')
        ->not->toContain('posting plan')
        ->not->toContain('eight guides we can stand behind')
        ->not->toContain('thousands of fully remote')
        ->not->toContain('every major U.S. industry')
        ->toContain('display advertising and from an optional paid CV writing service');
});

it('does not promise contact options that do not exist', function () {
    $contact = $this->get('/contact-us')->assertOk()->getContent();

    expect($contact)->not->toContain('calendly.com')
        ->not->toContain('Book a Meeting')
        ->not->toContain('partnerships team')
        ->not->toContain('sales team')
        ->not->toContain('posting plan');
});

it('states where the site is operated from', function () {
    $this->get('/privacy-policy')->assertOk()
        ->assertSee('operated from Lahore, Pakistan')
        ->assertDontSee('operated from the United States');
});

it('does not describe the blog author as hiring experts', function () {
    $this->get('/blog')->assertOk();

    expect(file_get_contents(resource_path('views/user/blog-post.blade.php')))->not->toContain('written by hiring experts');
});

it('keeps trust claims off the sign-up and job seeker pages', function () {
    $register = $this->get('/register')->assertOk()->getContent();

    expect($register)->not->toContain('Apply with one click')
        ->not->toContain('Trusted by top U.S. employers')
        ->not->toContain('Join thousands of job seekers');
});

it('explains Google AdSense advertising cookies in the privacy policy', function () {
    $html = $this->get('/privacy-policy')->assertOk()->getContent();

    expect($html)->toContain('id="advertising"')
        ->toContain('We use Google AdSense to show ads on JobGader')
        ->toContain('Third-party vendors, including Google, use cookies to serve ads based on a user')
        ->toContain('enables it and its partners to serve ads')
        ->toContain('https://www.google.com/settings/ads')
        ->toContain('https://www.aboutads.info')
        ->toContain('https://policies.google.com/technologies/partner-sites')
        ->toContain('Advertising cookies:')
        ->toContain('Last updated: October 20, 2026');
});
