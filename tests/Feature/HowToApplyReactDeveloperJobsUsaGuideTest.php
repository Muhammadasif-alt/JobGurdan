<?php

use App\Models\Blog;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;

const APPLY_REACT_SLUG = 'entry-level-react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
});

it('republishes none of the job board figures the brief carried', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    foreach (['6,764', '129,348', '134,300', '106,000', '157,000'] as $figure) {
        expect($content)->not->toContain($figure);
    }
});

it('sets out the federal hiring stages from OPM', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('Vacancy announcement')
        ->toContain('Rating and ranking')
        ->toContain('Certification')
        ->toContain('career or career-conditional appointment')
        // Category rating replaced the rule of three, which changes the target
        // from being ranked first to landing in the top category.
        ->toContain('pre-determined quality categories')
        ->toContain('rule of three')
        ->toContain('absolute preference within each category');
});

it('states what an employer may not ask, from the EEOC', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('age (40 or older), disability or genetic information')
        ->toContain('recent college graduates')
        ->toContain('pre-offer inquiries about disability')
        // There is no federal salary history ban, and saying otherwise is the
        // most common error in guides on this topic.
        ->toContain('There is no single federal ban');
});

it('gives the FCRA background check sequence in full', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('stand-alone format')
        ->toContain('written permission to do the background check')
        ->toContain('A Summary of Your Rights Under the Fair Credit Reporting Act')
        ->toContain('free copy within 60 days');
});

it('gives the I-9 deadline and the FTC scam test', function () {
    $content = Blog::where('slug', APPLY_REACT_SLUG)->value('content');

    expect($content)->toContain('within three business days of the date employment begins')
        ->toContain('section 274A')
        ->toContain('will never ask you to pay to get a job')
        ->toContain('send you a check to deposit');
});
