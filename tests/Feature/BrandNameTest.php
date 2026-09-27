<?php

use function Pest\Laravel\get;

/**
 * Every file under the repository that can carry copy, excluding the
 * dependencies and the build output.
 *
 * @return list<string>
 */
function brandSourceFiles(): array
{
    $root = base_path();
    $skip = ['vendor', 'node_modules', 'storage', 'public/public', '.git'];
    $out = [];

    $it = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            function (SplFileInfo $file) use ($root, $skip): bool {
                $rel = str_replace(DIRECTORY_SEPARATOR, '/', substr($file->getPathname(), strlen($root) + 1));

                foreach ($skip as $dir) {
                    if ($rel === $dir || str_starts_with($rel, $dir.'/')) {
                        return false;
                    }
                }

                return true;
            }
        )
    );

    foreach ($it as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'js', 'css', 'json', 'md', 'xml'], true)) {
            $out[] = $file->getPathname();
        }
    }

    return $out;
}

it('spells the brand the way the owner does, with one j', function () {
    // The domain, the founder's name and the logo all read "Sajad"; the copy
    // read "Sajjad" in 627 places, which is a different company to a reader
    // and to a search engine.
    $offenders = [];

    foreach (brandSourceFiles() as $path) {
        if ($path === __FILE__) {
            continue;
        }

        if (str_contains((string) file_get_contents($path), 'Sajjad')) {
            $offenders[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $path);
        }
    }

    expect($offenders)->toBe([]);
});

it('serves the one-j brand on the pages a visitor lands on', function (string $url) {
    $html = get($url)->assertOk()->getContent();

    expect($html)->toContain('Sajad Digital Services')
        ->not->toContain('Sajjad');
})->with([
    'home' => '/',
    'about' => '/about-us',
    'contact' => '/contact-us',
    'partners' => '/partners',
    'jobs' => '/jobs',
]);

it('carries one logo file that works on the light header and the dark one', function () {
    // The flat blue wordmark needed a second, white copy of itself for dark
    // mode. The gold and silver mark reads on either, so there is one file.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('user/images/sajad-navbar.png')
        ->not->toContain('sajjad-navbar')
        ->not->toContain('sajjad-dark-logo');

    foreach (['sajad-navbar.png', 'sajad-logo.png', 'favicon.png', 'apple-touch-icon.png'] as $file) {
        expect(file_exists(public_path('user/images/'.$file)))->toBeTrue($file.' is missing');
    }

    foreach (['sajjad-navbar.png', 'sajjad-navbar-dark.png', 'sajjad-dark-logo.png'] as $file) {
        expect(file_exists(public_path('user/images/'.$file)))->toBeFalse($file.' should have gone');
    }
});
