<?php

use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

beforeEach(function () {
    Cache::flush();
});

/**
 * The home page FAQPage schema, decoded.
 *
 * @return array<string, mixed>
 */
function homeFaqSchema(string $html): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    foreach ($blocks[1] as $block) {
        $decoded = json_decode(trim($block), true);

        if (($decoded['@type'] ?? null) === 'FAQPage') {
            return $decoded;
        }
    }

    return [];
}

/**
 * The rendered CV writing row of the home page.
 */
function homeCvRow(): string
{
    $html = get('/')->assertOk()->getContent();

    preg_match('#<section class="home-split-section[^"]*" aria-labelledby="tailored-cv-heading">.*?</section>#s', $html, $match);

    return $match[0] ?? '';
}

it('speaks to students and scholarship seekers in the title, description and hero', function () {
    $html = get('/')->assertOk()->getContent();

    preg_match('#<title>(.*?)</title>#s', $html, $title);
    preg_match('#<meta name="description" content="([^"]*)"#', $html, $description);
    preg_match('#<h1[^>]*>(.*?)</h1>#s', $html, $heading);

    $title = html_entity_decode(trim($title[1] ?? ''));
    $description = html_entity_decode($description[1] ?? '');

    expect($title)->toContain('Scholarships')->toContain('Students')
        ->and(mb_strlen($title))->toBeLessThanOrEqual(60)
        ->and($description)->toContain('scholarships')->toContain('official sources')
        ->and(mb_strlen($description))->toBeLessThanOrEqual(160)
        ->and(strip_tags($heading[1] ?? ''))->toContain('Scholarships')->toContain('Students');
});

it('links the hero straight to the student pages', function (string $routeName) {
    get('/')->assertOk()->assertSee('href="'.route($routeName).'"', false);
})->with([
    'scholarships' => 'scholarships.index',
    'internships' => 'pages.internship-jobs',
    'graduate jobs' => 'pages.graduate-jobs',
    'part-time remote' => 'pages.part-time-remote-jobs',
    'no experience' => 'pages.no-experience-jobs',
]);

it('says plainly that JobGader is a third party, not the employer or recruiter', function () {
    $text = html_entity_decode(strip_tags(get('/')->assertOk()->getContent()));

    expect($text)->toContain('third-party information site')
        ->toContain('not an employer, recruiter or visa agent')
        ->toContain('We do not collect applications or decide who is hired');
});

it('renders the same eight questions in the FAQ and its FAQPage schema', function () {
    $html = get('/')->assertOk()->getContent();

    $schema = homeFaqSchema($html);

    preg_match_all('#<summary>(.*?)</summary>#s', $html, $summaries);
    preg_match_all('#<div class="home-faq-answer">(.*?)</div>#s', $html, $answers);

    $visibleQuestions = array_map(fn (string $question): string => html_entity_decode($question, ENT_QUOTES), $summaries[1]);
    $visibleAnswers = array_map(fn (string $answer): string => html_entity_decode($answer, ENT_QUOTES), $answers[1]);

    expect($schema['mainEntity'] ?? [])->toHaveCount(8)
        ->and(array_column($schema['mainEntity'], 'name'))->toBe($visibleQuestions)
        ->and(array_map(fn (array $entity): string => $entity['acceptedAnswer']['text'], $schema['mainEntity']))->toBe($visibleAnswers);
});

it('answers the questions students ask about cost, checking, scholarships and the paid CV', function () {
    $questions = array_column(homeFaqSchema(get('/')->assertOk()->getContent())['mainEntity'], 'name');

    expect($questions)->toContain('Is JobGader an employer, recruiter or visa agent?')
        ->toContain('How do you check information before publishing it?')
        ->toContain('Does JobGader award scholarships or apply for me?')
        ->toContain('How does the paid CV writing service work?');
});

it('offers CV writing as a paid service ordered on WhatsApp', function () {
    config(['site.whatsapp' => '+92 346 4929466']);

    $cv = homeCvRow();

    expect($cv)->toContain('Paid Service')
        ->toContain('href="https://wa.me/923464929466?text=')
        ->toContain('Order Your CV on WhatsApp')
        ->toContain('href="'.route('resume-writing').'#resume-enquiry"');
});

it('keeps the free CV review button when no WhatsApp number is configured', function () {
    config(['site.whatsapp' => null]);

    $cv = homeCvRow();

    expect($cv)->not->toContain('wa.me/')
        ->toContain('Get a Free CV Review');
});

it('drops claims that clash with the paid CV service or the listings themselves', function () {
    $text = html_entity_decode(strip_tags(get('/')->assertOk()->getContent()));

    expect($text)->not->toContain('we will never ask a job seeker for money')
        ->not->toContain('Every listing carries a researched pay range')
        ->not->toContain('Every listing, before it goes live')
        ->not->toContain('Track every response from your dashboard')
        ->not->toContain('U.S. job')
        ->not->toContain("America's trusted");
});
