<?php

use function Pest\Laravel\get;

it('keeps the orange tail out of gradient heading text', function () {
    // Section headings used linear-gradient(..., #1b3a6b 60%, #ffab40), which
    // rendered the last word of "Common questions from employers" in orange
    // while the heroes had already been re-tuned to brand blue in master.
    $offenders = [];

    foreach (glob(resource_path('views/**/*.blade.php'), GLOB_BRACE) + glob(resource_path('views/**/**/*.blade.php')) as $file) {
        if (str_contains((string) file_get_contents($file), '#1b3a6b 60%, #ffab40')) {
            $offenders[] = str_replace(resource_path('views/'), '', $file);
        }
    }

    expect($offenders)->toBeEmpty('gradient heading text still fades to orange in: '.implode(', ', $offenders));
});

it('renders the employer FAQ heading in the same blue the homepage uses', function () {
    $page = get('/job-seekers')->assertOk()->getContent();

    expect($page)->toContain('Common questions from <span class="accent">employers</span>')
        ->and($page)->toContain('linear-gradient(90deg, #2f7fc9, #1b3a6b 60%, #4a90d9)');
});
