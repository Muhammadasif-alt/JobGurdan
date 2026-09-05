<?php

it('defaults to a Gemini model a newly issued key can reach', function () {
    // Google closed the 2.x line to new keys: they answer 404 with "no longer
    // available to new users". The site's key was reissued after the old
    // Google account was closed, so the default has to be on the 3.5 line.
    $model = config('services.gemini.model');

    expect($model)->not->toBeEmpty()
        ->and($model)->not->toStartWith('gemini-1.')
        ->and($model)->not->toStartWith('gemini-2.');
});
