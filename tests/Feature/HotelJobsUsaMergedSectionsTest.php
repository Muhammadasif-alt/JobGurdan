<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\HotelJobsBlogSeeder;

use function Pest\Laravel\get;

const HOTEL_SLUG = 'hotel-jobs-in-usa-for-foreigners';

beforeEach(function () {
    $this->seed(HotelJobsBlogSeeder::class);
});

it('covers restaurant roles and UK applicants on the one hotel page', function () {
    // A separate "hotel jobs USA with visa sponsorship" post would compete with
    // this one for the same query, so the extra material lives here.
    $response = get('/blog/'.HOTEL_SLUG)->assertOk();

    $response->assertSee('Restaurant and Food Service Roles Inside Hotels')
        ->assertSee('Applying as a UK, Irish or EU National')
        ->assertSee('no separate hospitality visa');
});

it('warns about J-1 intermediaries and tipped pay', function () {
    $body = Blog::where('slug', HOTEL_SLUG)->value('content');

    expect($body)->toContain('designated sponsors before paying any programme fee')
        ->toContain('tipped pay makes the advertised hourly rate a poor guide');
});

it('ships every image the merged post references', function () {
    $blog = Blog::where('slug', HOTEL_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('keeps its FAQ markup after the merge', function () {
    expect(app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', HOTEL_SLUG)->value('content')))->not->toBeEmpty();

    get('/blog/'.HOTEL_SLUG)->assertSee('"FAQPage"', false);
});
