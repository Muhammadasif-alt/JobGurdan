<?php

/**
 * The host serves this application with the document root set to the project
 * root, because twenty-nine views build their URLs with asset('public/...')
 * and the repository ships a root index.php. That puts .env, .git and the
 * compiled views inside the web root, so the rules that keep them out of reach
 * are load-bearing rather than decorative. These tests read the shipped files,
 * since a request test cannot exercise rules the web server applies.
 */
function rootHtaccess(): string
{
    return file_get_contents(base_path('.htaccess'));
}

it('refuses every application directory from the web root', function (string $dir) {
    expect(rootHtaccess())->toMatch('/RewriteRule \^\([^)]*\b'.preg_quote($dir, '/').'\b[^)]*\)\(\$\|\/\) - \[F,L\]/');
})->with(['app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'storage', 'tests', 'vendor']);

it('refuses the files that reveal how the application is built', function (string $file) {
    expect(rootHtaccess())->toContain($file);
})->with(['artisan', 'composer\.(json|lock)', 'phpunit\.xml']);

it('blocks dotfiles but leaves the ACME challenge path reachable', function () {
    $htaccess = rootHtaccess();

    expect($htaccess)->toContain('RewriteRule (^|/)\. - [F,L]')
        ->and($htaccess)->toContain('RewriteCond %{REQUEST_URI} !^/\.well-known/');
});

it('denies the secrets a second time in case mod_rewrite is absent', function () {
    expect(rootHtaccess())
        ->toContain('<FilesMatch')
        ->toContain('\.env.*')
        ->toContain('Require all denied');
});

it('sends the front controller the requests that are not real files', function () {
    expect(rootHtaccess())->toContain('RewriteRule ^ index.php [L]');
});

it('redirects the duplicate /public/ copy of every page to the canonical URL', function () {
    // Reachable at both / and /public/ under this document root, the site would
    // otherwise compete with itself in search.
    expect(file_get_contents(public_path('.htaccess')))
        ->toContain('RewriteCond %{REQUEST_URI} ^/public/(.+)$')
        ->toContain('RewriteRule ^ /%1 [L,R=301]');
});

it('serves a robots.txt at the document root that names the sitemap', function () {
    // The file is static, so it carries the production host rather than the
    // test environment's APP_URL; what matters is that the line is an absolute
    // https URL, which is what Google and Bing require.
    $robots = file_get_contents(base_path('robots.txt'));

    expect($robots)
        ->toMatch('#^Sitemap: https://[a-z0-9.-]+/sitemap\.xml$#m')
        ->toContain('Disallow: /admin/')
        ->toContain('Allow: /public/storage/');
});

it('keeps the two robots files pointing at the same sitemap', function () {
    $sitemapLine = fn (string $path): string => collect(file(base_path($path)))
        ->first(fn (string $line): bool => str_starts_with($line, 'Sitemap:')) ?? '';

    expect(trim($sitemapLine('robots.txt')))
        ->toBe(trim($sitemapLine('public/robots.txt')));
});

it('hides the dashboards and auth pages from crawlers', function (string $path) {
    expect(file_get_contents(base_path('robots.txt')))->toContain('Disallow: '.$path);
})->with(['/admin/', '/dashboard', '/login', '/register', '/api/']);

it('has no seeder that empties a content table wholesale', function () {
    // BlogContentSeeder opened with Blog::query()->delete(). It sorted on "B",
    // so a plain A-to-Z seed run destroyed every post whose seeder came before
    // it, and the failure was silent. Nothing may do that again.
    $offenders = [];

    foreach (glob(database_path('seeders').'/*.php') as $file) {
        $src = file_get_contents($file);

        foreach (['Blog', 'Job', 'Scholarship', 'BlogCatgories', 'Category', 'User'] as $model) {
            if (preg_match('/'.$model.'::(query\(\)->(delete|truncate)|truncate)\s*\(/', $src)) {
                $offenders[] = basename($file).' wipes '.$model;
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('keeps the seeders limited to the guides the owner wrote', function (string $gone) {
    // These three shipped with the theme: stock photography, credited to
    // "Jobs in USA Editorial". They were removed from the site and must not
    // reappear on the next seed.
    expect(file_exists(database_path('seeders/'.$gone.'.php')))->toBeFalse();
})->with(['BlogContentSeeder', 'NewBlogPostsSeeder', 'AdditionalBlogsSeeder']);
