<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\get;

beforeEach(function () {
    config(['cache.default' => 'file']);
    $this->cachePath = storage_path('framework/testing/prune-cache');
    config(['cache.stores.file.path' => $this->cachePath]);
    File::deleteDirectory($this->cachePath);
    File::ensureDirectoryExists($this->cachePath);
    Cache::forgetDriver('file');
});

afterEach(function () {
    File::deleteDirectory($this->cachePath);
});

it('caches the plain jobs board and its first pages', function (string $url) {
    get($url)->assertOk()->assertHeader('X-Page-Cache', 'MISS');
    get($url)->assertOk()->assertHeader('X-Page-Cache', 'HIT');
})->with(['plain' => '/jobs', 'page two' => '/jobs?page=2']);

it('writes no page cache for filtered, deep or junk URLs', function (string $url) {
    get($url)->assertOk();
    get($url)->assertOk()->assertHeader('X-Page-Cache', 'MISS');

    expect(Cache::has('page.jobs.v2.'.md5(url($url))))->toBeFalse();
})->with([
    'keyword' => '/jobs?q=nurse',
    'deep page' => '/jobs?page=900',
    'junk' => '/jobs?utm_source=abc123',
]);

it('deletes expired and empty cache files and keeps live ones', function () {
    File::put($this->cachePath.'/expired', (time() - 60).'s:3:"old";');
    File::put($this->cachePath.'/empty', '');
    File::put($this->cachePath.'/live', (time() + 600).'s:3:"new";');

    Artisan::call('cache:prune-expired-files', ['--dry-run' => true]);
    expect(File::exists($this->cachePath.'/expired'))->toBeTrue();

    Artisan::call('cache:prune-expired-files');

    expect(File::exists($this->cachePath.'/expired'))->toBeFalse()
        ->and(File::exists($this->cachePath.'/empty'))->toBeFalse()
        ->and(File::exists($this->cachePath.'/live'))->toBeTrue();
});
