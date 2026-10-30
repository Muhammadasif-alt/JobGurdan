<?php

use App\Models\Blog;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;

const REACT_INTERN_SLUG = 'entry-level-react-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
});

it('leads with whether the internship has to be paid', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain("The FLSA requires 'for-profit' employers to pay employees for their work.")
        ->toContain('primary beneficiary test')
        ->toContain("examine the 'economic reality' of the intern-employer relationship")
        ->toContain('entitled to both minimum wage and overtime pay under the FLSA')
        ->toContain('public sector and non-profit charitable organizations');
});

it('gives all seven factors and refuses to turn them into a checklist', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    foreach ([
        'there is no expectation of compensation',
        'similar to that which would be given in an educational environment',
        'integrated coursework or the receipt of academic credit',
        'corresponding to the academic calendar',
        'the period in which the internship provides the intern with beneficial learning',
        'complements, rather than displaces, the work of paid employees',
        'without entitlement to a paid job at the conclusion of the internship',
    ] as $factor) {
        expect($content)->toContain($factor);
    }

    expect($content)->toContain('a flexible test, and no single factor is determinative')
        // The six-factor version was rescinded and is still widely quoted.
        ->toContain('5 January 2018');
});

it('sets out CPT from the regulation, including the OPT trap', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain('an integral part of an established curriculum')
        ->toContain('alternative work/study, internship, cooperative education')
        ->toContain('A request for authorization for curricular practical training must be made to the DSO')
        ->toContain('only after receiving their Form I-20')
        // A year of full-time CPT permanently costs post-completion OPT.
        ->toContain('one year or more of full time curricular practical training are ineligible')
        ->toContain('deducted from the post-completion period');
});

it('refuses to present an annualised figure or the occupation median as intern pay', function () {
    $content = Blog::where('slug', REACT_INTERN_SLUG)->value('content');

    expect($content)->toContain('An annualised figure is not what you will be paid')
        ->toContain('The occupation median is not an intern wage')
        ->toContain('$135,980')
        // The employer-specific annualised figure the brief quoted is not used.
        ->and($content)->not->toContain('109,395');
});
