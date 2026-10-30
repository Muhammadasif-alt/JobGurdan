<?php

use App\Models\Blog;
use Database\Seeders\ReactTypescriptDeveloperJobsUsaBlogSeeder;

const JS_REACT_SLUG = 'react-typescript-developer-jobs-in-usa';

const TS_REACT_SLUG = 'react-typescript-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);
});

dataset('language spokes', [
    'javascript' => [JS_REACT_SLUG, 'blogs/javascript-react-developer-jobs-usa.jpg', 'JavaScript React Developer'],
    'typescript' => [TS_REACT_SLUG, 'blogs/react-typescript-developer-jobs-usa.jpg', 'React TypeScript Developer'],
]);

it('republishes none of the salary site figures either brief carried', function () {
    $js = Blog::where('slug', JS_REACT_SLUG)->value('content');
    $ts = Blog::where('slug', TS_REACT_SLUG)->value('content');

    foreach (['129,348', '106,000', '157,000', '110,412', '122,190', '121,000'] as $figure) {
        expect($js)->not->toContain($figure)
            ->and($ts)->not->toContain($figure);
    }
});

it('separates the language standard from the browser standard', function () {
    $content = Blog::where('slug', JS_REACT_SLUG)->value('content');

    expect($content)->toContain('ECMA-262')
        ->toContain('WHATWG HTML Standard')
        // The event loop and timers are not in the language specification.
        ->toContain('It does <em>not</em> define the environment the language runs in')
        ->toContain('the <code>setTimeout()</code> and <code>setInterval()</code> methods allow authors to schedule timer-based callbacks')
        ->toContain('after five such nested timers, however, the interval is forced to be at least four milliseconds');
});

it('dates the ES6 baseline that adverts still ask for', function () {
    $content = Blog::where('slug', JS_REACT_SLUG)->value('content');

    expect($content)->toContain('17 June 2015')
        ->toContain('ECMAScript 2015')
        ->toContain('16th edition in June 2025')
        ->toContain('annual release cycle');
});

it('states that TypeScript types are erased before the code runs', function () {
    $content = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($content)->toContain('Type annotations never change the runtime behavior of your program')
        ->toContain('Most TypeScript-specific code gets erased away')
        // The consequence the brief got wrong.
        ->toContain('An interface describing an API response is a claim about data you have not received yet')
        ->toContain('Validate at the boundary, at runtime')
        ->toContain('Derive the type from the validator');
});

it('gives the TC39 proposal with its stage rather than as settled', function () {
    $content = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($content)->toContain('at runtime, a JavaScript engine ignores them, treating the types as comments')
        ->toContain('stage 1')
        // TypeScript is one vendor's language; JavaScript is a standard.
        ->toContain("aren't part of JavaScript (or ECMAScript to be pedantic)")
        ->toContain('maintained by Microsoft');
});

it('benchmarks both guides on BLS rather than on a salary site', function () {
    $ts = Blog::where('slug', TS_REACT_SLUG)->value('content');

    expect($ts)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670')
        ->toContain('$92,650');
});
