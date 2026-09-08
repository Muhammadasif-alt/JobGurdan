<?php

use App\Models\ContactMessage;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('publishes the page with SEO fields inside the snippet limits', function () {
    $response = get('/resume-writing-services')->assertOk();

    $html = $response->getContent();

    preg_match('#<title>(.*?)</title>#s', $html, $title);
    preg_match('#<meta name="description" content="(.*?)"#s', $html, $description);

    expect(mb_strlen(trim($title[1] ?? '')))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($description[1] ?? ''))->toBeLessThanOrEqual(160)
        ->and($html)->toContain('rel="canonical"')
        ->and($html)->toContain(url('/resume-writing-services'));
});

it('carries one h1 and the service, breadcrumb and FAQ schema', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect(substr_count($html, '<h1'))->toBe(1)
        ->and($html)->toContain('"@type": "Service"')
        ->toContain('"@type": "BreadcrumbList"')
        ->toContain('"@type": "FAQPage"');

    // Every rendered FAQ has to exist in the markup a reader sees, not only
    // in the schema, or the markup is describing a page that is not there.
    expect(substr_count($html, '<details class="rw-faq"'))->toBe(8);
});

it('emits valid JSON in each schema block', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);

    expect(count($m[1]))->toBeGreaterThanOrEqual(3);

    foreach ($m[1] as $json) {
        expect(json_decode(trim($json), true))->not->toBeNull('invalid JSON-LD: '.substr(trim($json), 0, 120));
    }
});

it('promises nothing it cannot deliver', function () {
    // The reference design leaned on "10,000+ resumes written", a "60-day
    // interview guarantee" and certified-writer credentials. None of that is
    // true here, so none of it is on the page.
    $html = get('/resume-writing-services')->assertOk()->getContent();

    foreach (['10,000+', '60-day', '60 day', 'CPRW', 'Trustpilot', 'guarantee you'] as $claim) {
        expect($html)->not->toContain($claim);
    }

    // And it says so plainly rather than staying quiet about it.
    expect($html)->toContain('Nobody can promise you a job');
});

it('hides the WhatsApp buttons until a number is configured', function () {
    config(['site.whatsapp' => null]);

    expect(get('/resume-writing-services')->getContent())->not->toContain('wa.me');

    config(['site.whatsapp' => '+92 300 1234567']);

    // wa.me only accepts digits, so the configured value is stripped.
    expect(get('/resume-writing-services')->getContent())->toContain('wa.me/923001234567');
});

it('shows the site contact address rather than a hardcoded one', function () {
    config(['site.contact_email' => 'someone@example.test']);

    expect(get('/resume-writing-services')->getContent())->toContain('someone@example.test');
});

it('lays the image sections out as equal halves at the site width', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect($html)->toContain('max-width: 1440px')
        ->toContain('.rw-trust-grid { display: grid; grid-template-columns: 1fr 1fr;')
        ->toContain('object-fit: cover');
});

it('runs the hero photograph across the band and centres the copy on it', function () {
    // The picture used to sit in a panel beside the text. It is now the
    // background of the whole section with the copy centred over a scrim.
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect($html)->toContain("resume-writer.jpg') center 34% / cover no-repeat")
        ->toContain('.rw-hero-inner { max-width: 880px; margin: 0 auto; }')
        ->toContain('position: relative; padding: 108px 0 116px; text-align: center;')
        // The old two-column hero is gone entirely.
        ->not->toContain('rw-hero-grid')
        ->not->toContain('rw-hero-media');

    // A CSS background is invisible to the preload scanner, so the largest
    // paint would otherwise wait for the stylesheet.
    expect($html)->toContain('rel="preload" as="image"')
        ->toContain('fetchpriority="high"');
});

it('sizes the eyebrow pill to its own text', function () {
    // .rw-hero-copy and .rw-trust-copy are flex columns and a flex item
    // stretches to the full track, so the inline-block pill ran the width of
    // the column instead of the width of the label.
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect($html)->toContain('display: inline-block; width: fit-content; align-self: flex-start;');
});

it('stacks six reasons in one column with a rule that draws in on hover', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect(substr_count($html, '<div class="rw-trust-card">'))->toBe(6);

    expect($html)->toContain('.rw-trust-points { display: grid; grid-template-columns: 1fr; gap: 0; }')
        ->toContain('.rw-trust-card:hover::after { width: 100%; }')
        // Two new promises, both things a writer can actually control.
        ->toContain('Written for the role you name')
        ->toContain('You get the editable file');
});

it('walks the process in four steps joined by a rule', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect(substr_count($html, '<div class="rw-step">'))->toBe(4)
        ->and($html)->toContain('Four Steps,')
        ->not->toContain('Three Steps,');

    expect($html)->toContain('.rw-steps { display: grid; grid-template-columns: repeat(4, 1fr);')
        ->toContain('.rw-step + .rw-step::before {')
        // Stacked on a phone there is nothing to join.
        ->toContain('.rw-step + .rw-step::before { display: none; }');
});

it('reads as rows rather than boxes in the dark theme', function () {
    // .rw-trust-card was still in the group that paints a card background and
    // a full border, so the six rows came out as boxes jammed together.
    $html = get('/resume-writing-services')->assertOk()->getContent();

    $start = strpos($html, 'html.dark-mode .rw-trust-card {');
    expect($start)->not->toBeFalse('dark trust rule missing');
    expect(substr($html, $start, 160))->toContain('background: transparent; border: 0;');

    // Brand navy accents sit a few points off the dark background, so every
    // one of them disappeared.
    expect($html)->toContain('html.dark-mode .rw-market { border-left-color: #8fc4f0; }')
        ->toContain('html.dark-mode .rw-step + .rw-step::before');
});

it('gives every card the hover the about page uses on its mission cards', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    // A 3px rule drawing in from the left across the top, plus the lift.
    expect($html)->toContain('content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;')
        ->toContain('transform: scaleX(0); transform-origin: left;')
        ->toContain('transform: scaleX(1);')
        ->toContain('box-shadow: 0 18px 36px rgba(15,23,42,.10);');

    // The service and industry tiles carried a permanent navy edge along the
    // bottom; a rule top and bottom would sandwich the card.
    expect($html)->not->toContain('border-bottom: 4px solid #1b3a6b;')
        ->not->toContain('border-bottom: 3px solid #1b3a6b;');

    // Navy is a few points off the dark background, so the rule changes hue.
    expect($html)->toContain('html.dark-mode .rw-check-col::before, html.dark-mode .rw-readmore a::before {');
});

it('does not reuse a photograph another page already owns', function () {
    // hero-diverse-professionals is the banner for every page without one of
    // its own, and about-founders belongs to the about page. The layout's own
    // stylesheet mentions the first on every page, so read only this page.
    $html = get('/resume-writing-services')->assertOk()->getContent();
    $page = substr($html, strpos($html, '<div class="rw-page">'));

    expect($page)->not->toContain('hero-diverse-professionals')
        ->not->toContain('about-founders');

    foreach (['resume-writer.jpg', 'resume-review.jpg'] as $file) {
        expect(file_exists(public_path('user/images/'.$file)))->toBeTrue($file.' missing');
    }
});

it('carries the market and ATS sections that do the long-tail work', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect($html)->toContain('Written Four Different Ways')
        ->toContain('Machine-Readable')
        // Each market gets its own conventions rather than one generic answer.
        ->toContain('United States &mdash; a resume')
        ->toContain('Saudi Arabia &amp; UAE &mdash; a Gulf CV')
        ->toContain('transferable visa')
        ->toContain('your time zone and your overlap hours near the top')
        // And the advice warns against the obvious way to game a filter.
        ->toContain('never pad a resume with keywords you cannot defend');
});

it('links into the guides and appears in the sitemap and nav', function () {
    $html = get('/resume-writing-services')->assertOk()->getContent();

    expect($html)->toContain('/blog/ats-resume-writer-jobs-in-usa')
        ->toContain('/blog/full-stack-developer-jobs-in-usa');

    get('/sitemap-core.xml')->assertOk()->assertSee('/resume-writing-services', false);

    get('/')->assertOk()->assertSee('Resume Writing');
});

it('takes an enquiry and returns the visitor to the form', function () {
    $response = post(route('contact.store'), [
        'source' => 'resume',
        'subject' => 'Resume writing enquiry',
        'first_name' => 'Asha',
        'last_name' => 'Khan',
        'email' => 'asha@example.test',
        'phone' => '+92 300 1234567',
        'target_role' => 'Full Stack Developer, remote US roles',
        'message' => 'Five years of Laravel and React. I want to move into US remote work.',
        'form_started_at' => now()->subMinute()->timestamp,
    ]);

    $response->assertRedirect(route('resume-writing').'#resume-enquiry')
        ->assertSessionHas('success');

    $saved = ContactMessage::latest()->first();

    // contact_messages has no column for either extra field, so they are folded
    // into the message rather than silently dropped.
    expect($saved->email)->toBe('asha@example.test')
        ->and($saved->message)->toContain('WhatsApp: +92 300 1234567')
        ->and($saved->message)->toContain('Target role: Full Stack Developer, remote US roles');
});

it('still sends the ordinary contact form back to the home page', function () {
    post(route('contact.store'), [
        'subject' => 'General question about listings',
        'first_name' => 'Sam',
        'last_name' => 'Green',
        'email' => 'sam@example.test',
        'message' => 'I would like to ask about posting a job on your board.',
        'form_started_at' => now()->subMinute()->timestamp,
    ])->assertRedirect('/');

    expect(ContactMessage::latest()->first()->message)->not->toContain('WhatsApp:');
});
