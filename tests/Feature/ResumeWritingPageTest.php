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
