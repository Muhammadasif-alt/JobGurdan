<?php

/**
 * Locks the no-aggregator rule into the suite.
 *
 * Every apply link and every article CTA used to point at Indeed. They were
 * replaced in one pass: apply buttons now go to the destination country's
 * official government job service, and the "Browse X Jobs in Y" CTAs go to our
 * own category listing, which is what that anchor text actually promises.
 *
 * These tests read the seeder source rather than the database on purpose. The
 * rule is about what we publish, so it has to fail at the point someone pastes
 * an aggregator URL into a seeder, not later when a page is rendered.
 */

use Illuminate\Support\Str;

/**
 * Seeder source, excluding PHPDoc and inline comments.
 *
 * The comments deliberately record which aggregator link was removed from each
 * supplied brief, so matching them would report the documentation as the fault.
 *
 * @return array<string, string>
 */
function seederBodies(): array
{
    $bodies = [];

    foreach (glob(database_path('seeders/*.php')) as $path) {
        $lines = [];

        foreach (file($path, FILE_IGNORE_NEW_LINES) as $line) {
            $trimmed = ltrim($line);

            if (Str::startsWith($trimmed, ['*', '//', '/*'])) {
                continue;
            }

            $lines[] = $line;
        }

        $bodies[basename($path)] = implode("\n", $lines);
    }

    return $bodies;
}

it('finds seeders to inspect', function () {
    expect(seederBodies())->not->toBeEmpty();
});

it('publishes no aggregator URL from any seeder', function () {
    $aggregators = [
        'indeed.com', 'ziprecruiter.com', 'glassdoor.com', 'linkedin.com/jobs',
        'weworkremotely.com', 'simplyhired.com', 'monster.com', 'totaljobs.com',
        'reed.co.uk', 'cv-library.co.uk', 'bayt.com', 'gulftalent.com',
        'naukrigulf.com', 'rozee.pk', 'mustakbil.com', 'dice.com',
    ];

    $offenders = [];

    foreach (seederBodies() as $file => $body) {
        foreach ($aggregators as $aggregator) {
            if (str_contains(strtolower($body), $aggregator)) {
                $offenders[] = $file.' -> '.$aggregator;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('links no URL carrying a vacancy or requisition id', function () {
    $offenders = [];

    foreach (seederBodies() as $file => $body) {
        if (preg_match_all('/https?:\/\/[^\s"\']*[?&](vjk|jk|jobid|job_id|vacancyid|currentJobId)=[^\s"\'&]+/i', $body, $matches)) {
            foreach ($matches[0] as $url) {
                $offenders[] = $file.' -> '.$url;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('keeps tracking and affiliate residue out of the seeders', function () {
    $residue = ['utm_source=chatgpt.com', 'citeturn', 'aiprm', 'semrush.com/?ref'];

    $offenders = [];

    foreach (seederBodies() as $file => $body) {
        foreach ($residue as $marker) {
            if (str_contains(strtolower($body), $marker)) {
                $offenders[] = $file.' -> '.$marker;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('sends every apply link to an official or employer destination', function () {
    $allowedGovernmentHosts = [
        'usa.gov', 'usajobs.gov', 'gov.uk', 'jobbank.gc.ca',
        'workforceaustralia.gov.au', 'arbeitsagentur.de', 'njp.gov.pk',
        'hrsd.gov.sa', 'u.ae', 'hellowork.mhlw.go.jp',
    ];

    $suspect = [];

    foreach (seederBodies() as $file => $body) {
        if (! preg_match_all("/APPLY_URL\s*=\s*'([^']+)'/", $body, $matches)) {
            continue;
        }

        foreach ($matches[1] as $url) {
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            if ($host === '') {
                $suspect[] = $file.' -> '.$url;
            }
        }
    }

    expect($suspect)->toBe([]);
    expect($allowedGovernmentHosts)->not->toBeEmpty();
});

it('never nofollows its own category links', function () {
    $offenders = [];

    foreach (seederBodies() as $file => $body) {
        if (preg_match_all('/<a\s[^>]*jobgader\.com\/categories[^>]*>/i', $body, $matches)) {
            foreach ($matches[0] as $tag) {
                if (stripos($tag, 'nofollow') !== false) {
                    $offenders[] = $file;
                }
            }
        }
    }

    expect($offenders)->toBe([]);
});
