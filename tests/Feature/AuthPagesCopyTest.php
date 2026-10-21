<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;

it('shows the real country count and no unsupported claims on the sign-in and register pages', function (string $path) {
    $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
    $advertiser = Advertiser::create(['name' => 'Acme Engineering', 'type' => 'Private', 'display_reference' => 'acme-engineering']);

    foreach (['United Kingdom', 'Pakistan', 'Kenya', 'Ireland'] as $country) {
        $location = Location::firstOrCreate(['name' => $country], ['area' => 'Nationwide', 'country' => $country]);
        Job::create([
            'advertiser_id' => $advertiser->id,
            'location_id' => $location->id,
            'category_id' => $category->id,
            'position' => 'Engineer in '.$country,
            'description' => '<p>Test.</p>',
            'employment_type' => 'Full-time',
            'application_url' => 'https://example.com/jobs',
        ]);
    }

    $html = $this->get($path)->assertOk()->getContent();

    expect($html)->toMatch('#<strong>4</strong><span>Countries</span>#')
        ->not->toContain('<strong>3</strong><span>Countries</span>')
        ->not->toContain('50+')
        ->not->toContain('Trusted by top U.S. employers')
        ->not->toContain('100,000+');
})->with(['/login', '/register']);
