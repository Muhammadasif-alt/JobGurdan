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
    'teacher pakistan' => [
        'teacher-jobs-in-pakistan',
        Database\Seeders\TeacherJobsPakistanBlogSeeder::class,
        ['teacher-jobs-in-pakistan-government.jpg', 'teacher-jobs-in-pakistan-private.jpg'],
        'https://pk.indeed.com/q-teacher-jobs.html',
        'Teacher',
    ],
    'nurse saudi arabia' => [
        'nurse-jobs-in-saudi-arabia',
        Database\Seeders\NurseJobsSaudiBlogSeeder::class,
        ['nurse-jobs-in-saudi-arabia-licence.jpg', 'nurse-jobs-in-saudi-arabia-hospital.jpg'],
        'https://sa.indeed.com/q-nurse-jobs.html',
        'Staff Nurse',
    ],
    'it support uk' => [
        'it-support-jobs-in-uk',
        Database\Seeders\ItSupportJobsUkBlogSeeder::class,
        ['it-support-jobs-in-uk-helpdesk.jpg', 'it-support-jobs-in-uk-second-line.jpg'],
        'https://uk.indeed.com/q-it-support-jobs.html',
        'IT Support Technician',
    ],
    'cashier usa' => [
        'cashier-jobs-in-usa',
        Database\Seeders\CashierJobsUsaBlogSeeder::class,
        ['cashier-jobs-in-usa-grocery.jpg', 'cashier-jobs-in-usa-pay.jpg'],
        'https://www.indeed.com/q-cashier-jobs.html',
        'Cashier',
    ],
    'cook uk' => [
        'cook-jobs-in-uk',
        Database\Seeders\CookJobsUkBlogSeeder::class,
        ['cook-jobs-in-uk-hygiene.jpg', 'cook-jobs-in-uk-kitchen.jpg'],
        'https://uk.indeed.com/q-cook-jobs.html',
        'Cook',
    ],
    'accountant uae' => [
        'accountant-jobs-in-uae',
        Database\Seeders\AccountantJobsUaeBlogSeeder::class,
        ['accountant-jobs-in-uae-tax.jpg', 'accountant-jobs-in-uae-audit.jpg'],
        'https://ae.indeed.com/q-accountant-jobs.html',
        'Accountant',
    ],
    'police officer usa' => [
        'police-officer-jobs-in-usa',
        Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        ['police-officer-jobs-in-usa-patrol.jpg'],
        'https://www.indeed.com/q-police-officer-jobs.html',
        'Police Officer',
    ],
    'sales australia' => [
        'sales-jobs-in-australia',
        Database\Seeders\SalesJobsAustraliaBlogSeeder::class,
        ['sales-jobs-in-australia-interview.jpg', 'sales-jobs-in-australia-retail.jpg'],
        'https://au.indeed.com/q-sales-jobs.html',
        'Sales',
    ],
    'healthcare assistant uk' => [
        'healthcare-assistant-jobs-in-uk',
        Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
        ['healthcare-assistant-jobs-in-uk-ward.jpg', 'healthcare-assistant-jobs-in-uk-patient-care.jpg'],
        'https://uk.indeed.com/q-healthcare-assistant-jobs.html',
        'Healthcare Assistant',
    ],
    'registered nurse usa' => [
        'registered-nurse-jobs-in-usa',
        Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class,
        ['registered-nurse-jobs-in-usa-station.jpg', 'registered-nurse-jobs-in-usa-bedside.jpg'],
        'https://www.indeed.com/q-registered-nurse-jobs.html',
        'Registered Nurse — Hospitals, Outpatient',
    ],
    'help desk usa' => [
        'help-desk-technician-jobs-in-usa',
        Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class,
        ['help-desk-technician-jobs-in-usa-tickets.jpg', 'help-desk-technician-jobs-in-usa-support.jpg'],
        'https://www.indeed.com/q-help-desk-technician-jobs.html',
        'Help Desk Technician',
    ],
    'housekeeper uk' => [
        'housekeeper-jobs-in-uk',
        Database\Seeders\HousekeeperJobsUkBlogSeeder::class,
        ['housekeeper-jobs-in-uk-hotel.jpg', 'housekeeper-jobs-in-uk-private-home.jpg'],
        'https://uk.indeed.com/q-housekeeper-jobs.html',
        'Housekeeper',
    ],
    'teacher usa' => [
        'teacher-jobs-in-usa',
        Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        ['teacher-jobs-in-usa-students.jpg', 'teacher-jobs-in-usa-classroom.jpg'],
        'https://www.indeed.com/q-teacher-jobs.html',
        'Teacher — Elementary',
    ],
    'administrative assistant usa' => [
        'administrative-assistant-jobs-in-usa',
        Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder::class,
        ['administrative-assistant-jobs-in-usa-desk.jpg', 'administrative-assistant-jobs-in-usa-phone.jpg'],
        'https://www.indeed.com/q-administrative-assistant-jobs.html',
        'Administrative Assistant',
    ],
    'devops engineer germany' => [
        'devops-engineer-jobs-in-germany',
        Database\Seeders\DevOpsEngineerJobsGermanyBlogSeeder::class,
        ['devops-engineer-jobs-in-germany-berlin.jpg', 'devops-engineer-jobs-in-germany-career.jpg'],
        'https://de.indeed.com/q-devops-engineer-jobs.html',
        'DevOps Engineer — Cloud, CI/CD',
    ],
    'federal police usa' => [
        'federal-police-jobs-in-usa',
        Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        ['federal-police-jobs-in-usa-capitol.jpg', 'federal-police-jobs-in-usa-agencies.jpg'],
        'https://www.indeed.com/q-federal-police-jobs.html',
        'Federal Police Officer and Special Agent',
    ],
    'welder canada' => [
        'welder-jobs-in-canada',
        Database\Seeders\WelderJobsCanadaBlogSeeder::class,
        ['welder-jobs-in-canada-pipeline.jpg', 'welder-jobs-in-canada-structural.jpg'],
        'https://ca.indeed.com/q-welder-jobs.html',
        'Welder — Structural',
    ],
    'bus driver canada' => [
        'bus-driver-jobs-in-canada',
        Database\Seeders\BusDriverJobsCanadaBlogSeeder::class,
        ['bus-driver-jobs-in-canada-operator.jpg', 'bus-driver-jobs-in-canada-driving.jpg'],
        'https://ca.indeed.com/q-bus-driver-jobs.html',
        'Bus Driver — Transit',
    ],
    'remote jobs usa' => [
        'remote-jobs-in-usa',
        Database\Seeders\RemoteJobsUsaBlogSeeder::class,
        ['remote-jobs-in-usa-home-office.jpg', 'remote-jobs-in-usa-workspace.jpg'],
        'https://www.indeed.com/q-remote-jobs.html',
        'Remote Jobs — Customer Service',
    ],
    'visa sponsorship canada' => [
        'visa-sponsorship-jobs-in-canada',
        Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        ['visa-sponsorship-jobs-in-canada-workers.jpg', 'visa-sponsorship-jobs-in-canada-team.jpg'],
        'https://ca.indeed.com/q-visa-sponsorship-jobs.html',
        'Visa Sponsorship Jobs — LMIA',
    ],
    'no experience australia' => [
        'no-experience-jobs-in-australia',
        Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
        ['no-experience-jobs-in-australia-team.jpg', 'no-experience-jobs-in-australia-workers.jpg'],
        'https://au.indeed.com/q-no-experience-jobs.html',
        'Entry-Level Jobs — Retail',
    ],
    'kitchen helper saudi' => [
        'kitchen-helper-jobs-in-saudi-arabia',
        Database\Seeders\KitchenHelperJobsSaudiBlogSeeder::class,
        ['kitchen-helper-jobs-in-saudi-arabia-kitchen.jpg', 'kitchen-helper-jobs-in-saudi-arabia-hotel.jpg'],
        'https://sa.indeed.com/q-kitchen-helper-jobs.html',
        'Kitchen Helper — Hotels',
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
