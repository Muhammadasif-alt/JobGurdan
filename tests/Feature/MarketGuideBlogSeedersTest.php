<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;

/**
 * The nine 2026 market guides share a shape: a published post with its own
 * images and SEO fields, eight FAQs, a People Also Search For block, and an
 * aggregated listing that quotes no salary it cannot support. Asserting that
 * shape once against a dataset is worth more than nine near-identical files,
 * and it means a tenth guide only has to add a row.
 */
dataset('market guides', [
    'delivery driver uk' => [
        'delivery-driver-jobs-in-uk',
        Database\Seeders\DeliveryDriverJobsUkBlogSeeder::class,
        ['delivery-driver-jobs-in-uk-self-employed.jpg', 'delivery-driver-jobs-in-uk-licence.jpg'],
        'https://uk.indeed.com/q-delivery-driver-jobs.html',
        'Delivery Driver',
    ],
    'customer service canada' => [
        'customer-service-jobs-in-canada',
        Database\Seeders\CustomerServiceJobsCanadaBlogSeeder::class,
        ['customer-service-jobs-in-canada-bilingual.jpg', 'customer-service-jobs-in-canada-remote.jpg'],
        'https://ca.indeed.com/q-customer-service-jobs.html',
        'Customer Service Representative',
    ],
    'construction australia' => [
        'construction-worker-jobs-in-australia',
        Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class,
        ['construction-worker-jobs-in-australia-white-card.jpg', 'construction-worker-jobs-in-australia-casual.jpg'],
        'https://au.indeed.com/q-construction-worker-jobs.html',
        'Construction Labourer',
    ],
    'data entry usa' => [
        'data-entry-jobs-in-usa',
        Database\Seeders\DataEntryJobsUsaBlogSeeder::class,
        ['data-entry-jobs-in-usa-remote.jpg', 'data-entry-jobs-in-usa-skills.jpg'],
        'https://www.indeed.com/q-data-entry-jobs.html',
        'Data Entry Clerk',
    ],
    'data entry pakistan' => [
        'data-entry-jobs-in-pakistan',
        Database\Seeders\DataEntryJobsPakistanBlogSeeder::class,
        ['data-entry-jobs-in-pakistan-government.jpg'],
        'https://www.rozee.pk/category/data-entry-jobs',
        'Data Entry Operator',
    ],
    'security guard uae' => [
        'security-guard-jobs-in-uae',
        Database\Seeders\SecurityGuardJobsUaeBlogSeeder::class,
        ['security-guard-jobs-in-uae-sira.jpg', 'security-guard-jobs-in-uae-salary.jpg'],
        'https://ae.indeed.com/q-security-guard-jobs.html',
        'Security Guard',
    ],
    'office assistant australia' => [
        'office-assistant-jobs-in-australia',
        Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
        ['office-assistant-jobs-in-australia-salary.jpg', 'office-assistant-jobs-in-australia-checks.jpg'],
        'https://au.indeed.com/q-office-assistant-jobs.html',
        'Office Assistant',
    ],
    'retail usa' => [
        'retail-jobs-in-usa',
        Database\Seeders\RetailJobsUsaBlogSeeder::class,
        ['retail-jobs-in-usa-pay.jpg', 'retail-jobs-in-usa-store.jpg'],
        'https://www.indeed.com/q-retail-jobs-jobs.html',
        'Retail Sales Associate',
    ],
    'factory worker germany' => [
        'factory-worker-jobs-in-germany',
        Database\Seeders\FactoryWorkerJobsGermanyBlogSeeder::class,
        ['factory-worker-jobs-in-germany-visa.jpg', 'factory-worker-jobs-in-germany-shifts.jpg'],
        'https://de.indeed.com/q-fabrikarbeiter,-verpackung,-bandarbeit,-fabrikhilfer-jobs.html',
        'Production Worker',
    ],
    'farm worker canada' => [
        'farm-worker-jobs-in-canada',
        Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class,
        ['farm-worker-jobs-in-canada-sawp.jpg', 'farm-worker-jobs-in-canada-greenhouse.jpg'],
        'https://ca.indeed.com/q-farm-worker-jobs.html',
        'Farm Worker',
    ],
    'data scientist usa' => [
        'data-scientist-jobs-in-usa',
        Database\Seeders\DataScientistJobsUsaBlogSeeder::class,
        ['data-scientist-jobs-in-usa-salary.jpg', 'data-scientist-jobs-in-usa-skills.jpg'],
        'https://www.indeed.com/q-data-scientist-jobs.html',
        'Data Scientist',
    ],
    'electrician uk' => [
        'electrician-jobs-in-uk',
        Database\Seeders\ElectricianJobsUkBlogSeeder::class,
        ['electrician-jobs-in-uk-qualifications.jpg', 'electrician-jobs-in-uk-selfemployed.jpg'],
        'https://uk.indeed.com/q-electrician-jobs.html',
        'Electrician',
    ],
    'plumber australia' => [
        'plumber-jobs-in-australia',
        Database\Seeders\PlumberJobsAustraliaBlogSeeder::class,
        ['plumber-jobs-in-australia-licence.jpg', 'plumber-jobs-in-australia-selfemployed.jpg'],
        'https://au.indeed.com/q-plumber-jobs.html',
        'Plumber',
    ],
    'receptionist uae' => [
        'receptionist-jobs-in-uae',
        Database\Seeders\ReceptionistJobsUaeBlogSeeder::class,
        ['receptionist-jobs-in-uae-freezone.jpg', 'receptionist-jobs-in-uae-package.jpg'],
        'https://ae.indeed.com/q-receptionist-jobs.html',
        'Receptionist',
    ],
    'delivery driver usa' => [
        'delivery-driver-jobs-in-usa',
        Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class,
        ['delivery-driver-jobs-in-usa-gig.jpg', 'delivery-driver-jobs-in-usa-route.jpg'],
        'https://www.indeed.com/q-delivery-driver-jobs.html',
        'Package and Food Delivery Driver',
    ],
]);

it('publishes the guide with its own images and SEO fields', function (string $slug, string $seeder, array $inline) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.$slug.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        // VARCHAR(255) on MySQL; SQLite ignores the limit, so it is asserted.
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);

    foreach ($inline as $image) {
        expect($blog->content)->toContain($image);
    }
})->with('market guides');

it('renders with its long-tail sections and the search link', function (string $slug, string $seeder, array $inline, string $applyUrl) {
    $this->seed($seeder);

    $this->get('/blog/'.$slug)
        ->assertOk()
        ->assertSee(Blog::where('slug', $slug)->value('title'))
        ->assertSee('People Also Search For')
        ->assertSee($applyUrl, false);
})->with('market guides');

it('carries exactly eight FAQs built from the post body', function (string $slug, string $seeder) {
    $this->seed($seeder);

    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', $slug)->value('content'));

    expect($faqs)->toHaveCount(8);

    foreach ($faqs as $faq) {
        expect($faq['question'])->not->toBeEmpty()
            ->and($faq['answer'])->not->toBeEmpty();
    }

    $this->get('/blog/'.$slug)->assertSee('"FAQPage"', false);
})->with('market guides');

it('carries no stray non-ASCII in the fields search engines index', function (string $slug, string $seeder) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
})->with('market guides');

it('creates an aggregated listing that quotes no salary it cannot support', function (string $slug, string $seeder, array $inline, string $applyUrl, string $position) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
})->with('market guides');

it('links out to at least four other guides on the site', function (string $slug, string $seeder) {
    $this->seed($seeder);

    preg_match_all('#/blog/([a-z0-9-]+)#', Blog::where('slug', $slug)->value('content'), $matches);

    expect(count(array_unique($matches[1])))->toBeGreaterThanOrEqual(4)
        ->and($matches[1])->not->toContain($slug);
})->with('market guides');
