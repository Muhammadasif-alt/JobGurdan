<?php

use App\Http\Middleware\CanonicalRedirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @param  non-empty-string  $uri
 */
function passThroughCanonicalRedirect(string $uri): Response
{
    return (new CanonicalRedirect)->handle(
        Request::create($uri, 'GET'),
        fn (): Response => new Response('ok')
    );
}

it('does not force https outside production', function () {
    $response = passThroughCanonicalRedirect('http://127.0.0.1:8000/jobs');

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getContent())->toBe('ok');
});

it('does not redirect www to non-www outside production', function () {
    $response = passThroughCanonicalRedirect('http://www.example.test/jobs');

    expect($response->getStatusCode())->toBe(200);
});

it('does not send HSTS outside production', function () {
    $response = passThroughCanonicalRedirect('http://127.0.0.1:8000/jobs');

    expect($response->headers->get('Strict-Transport-Security'))->toBeNull();
});

it('still sends the other security headers outside production', function () {
    $response = passThroughCanonicalRedirect('http://127.0.0.1:8000/jobs');

    expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff')
        ->and($response->headers->get('X-Frame-Options'))->toBe('SAMEORIGIN')
        ->and($response->headers->get('Referrer-Policy'))->toBe('strict-origin-when-cross-origin');
});

it('forces https in production', function () {
    app()['env'] = 'production';

    $response = passThroughCanonicalRedirect('http://example.test/jobs');

    expect($response->getStatusCode())->toBe(301)
        ->and($response->headers->get('Location'))->toBe('https://example.test/jobs');
});

it('redirects www to non-www in production', function () {
    app()['env'] = 'production';

    $response = passThroughCanonicalRedirect('https://www.example.test/jobs');

    expect($response->getStatusCode())->toBe(301)
        ->and($response->headers->get('Location'))->toBe('https://example.test/jobs');
});

it('sends HSTS in production', function () {
    app()['env'] = 'production';

    $response = passThroughCanonicalRedirect('https://example.test/jobs');

    expect($response->headers->get('Strict-Transport-Security'))
        ->toBe('max-age=31536000; includeSubDomains; preload');
});

it('strips a trailing slash on non-root paths', function () {
    $response = passThroughCanonicalRedirect('http://127.0.0.1:8000/jobs/');

    expect($response->getStatusCode())->toBe(301)
        ->and($response->headers->get('Location'))->toBe('http://127.0.0.1:8000/jobs');
});
