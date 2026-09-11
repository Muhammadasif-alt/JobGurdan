<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\CaregiverUkBlogSeeder;

use function Pest\Laravel\get;

const CARE_UK_SLUG = 'caregiver-jobs-in-uk-with-visa-sponsorship';

beforeEach(function () {
    $this->seed(CaregiverUkBlogSeeder::class);
});

it('covers the care settings on the one UK care page instead of a second one', function () {
    // A separate "care jobs UK with visa sponsorship" post would compete with
    // this one for the same query, so the setting-by-setting material lives
    // here.
    $response = get('/blog/'.CARE_UK_SLUG)->assertOk();

    $response->assertSee('Care Jobs in the UK by Setting')
        ->assertSee('Domiciliary care')
        ->assertSee('Live-in care')
        ->assertSee('register of licensed sponsors');
});

it('does not let an NHS badge imply overseas sponsorship reopened', function () {
    $body = Blog::where('slug', CARE_UK_SLUG)->value('content');

    expect($body)->toContain('being NHS does not reopen overseas sponsorship')
        ->toContain('Band 3 or higher post paying at least &pound;25,000')
        ->toContain('charging a worker for sponsorship is illegal in the UK');
});

it('dates the Tier 2 terminology instead of repeating it as current', function () {
    $body = Blog::where('slug', CARE_UK_SLUG)->value('content');

    expect($body)->toContain('replaced by the <strong>Skilled Worker</strong> route in December 2020');
});

it('ships every image the merged post references', function () {
    $blog = Blog::where('slug', CARE_UK_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(3);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('keeps its FAQ markup after the merge', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', CARE_UK_SLUG)->value('content'));

    expect($faqs)->not->toBeEmpty();

    get('/blog/'.CARE_UK_SLUG)->assertSee('"FAQPage"', false);
});
