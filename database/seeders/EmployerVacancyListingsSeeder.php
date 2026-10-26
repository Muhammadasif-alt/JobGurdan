<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Database\Seeder;

/**
 * Single-employer vacancies that a guide brief supplied and that were checked
 * on the employer's own page before seeding. Unlike GuideJobListingsSeeder,
 * these are real adverts from named employers (listing_type vacancy, so they
 * carry JobPosting markup), link the employer's own application page, quote
 * no pay the employer did not publish, and record the date they were checked.
 *
 * Re-check each advert before re-deploying; an employer advert closes without
 * notice, and the admin panel can unpublish a closed one.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class EmployerVacancyListingsSeeder extends Seeder
{
    public const HAYS_POSITION = 'Administrative & Data Entry Coordinator - Hays Electrical Services (Houston, Texas)';

    public const HAYS_URL = 'https://hayselectricalservices.applytojob.com/apply/wG2s7J3Mb5/Administrative-Data-Entry-Coordinator';

    public function run(): void
    {
        $this->seedHaysDataEntryCoordinator();
    }

    /**
     * Fetched 26 October 2026: HTTP 200, posted 22 October 2026, Houston TX
     * onsite, full-time, labelled Entry Level, resume-upload form present.
     */
    private function seedHaysDataEntryCoordinator(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Hays Electrical Services'],
            ['type' => 'Private', 'display_reference' => 'hays-electrical-services']
        );

        $houston = Location::firstOrCreate(
            ['name' => 'Houston'],
            ['area' => 'Texas', 'country' => 'United States']
        );

        Job::updateOrCreate(
            ['position' => self::HAYS_POSITION, 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $houston->id,
                'description' => <<<'JOBHTML'
<p>Hays Electrical Services, an electrical contractor whose site says it has 19 years in business and licences in 39 states, is recruiting an Administrative & Data Entry Coordinator for its training and development department in Houston, Texas. The advert, posted on 22 October 2026 and checked by JobGader on 26 October 2026, is labelled Entry Level, full-time and onsite.</p>

<h3>What the role involves</h3>
<ul>
    <li>Editing and maintaining Word and Excel documents, including confidential material, and keeping electronic and physical files organised</li>
    <li>High-volume data entry with consistent speed and accuracy, including entering data into RAPIDS, the federal registered apprenticeship records system, and internal systems</li>
    <li>Reviewing and updating data to programme specifications, troubleshooting basic data issues and resolving discrepancies with internal teams</li>
    <li>Keeping an inventory of books, tools and documents, helping with event preparation and covering other clerical duties</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Strong attention to detail and organisational skills, and discretion with confidential information</li>
    <li>Proficiency with Microsoft Word and Excel, and the ability to perform repetitive, high-volume data entry accurately</li>
    <li>Effective written and verbal communication, handling several priorities, and basic problem solving on data and system issues</li>
    <li>The form asks whether you are at least 18, legally authorised to work in the United States, and whether you will need sponsorship; no minimum years of experience are listed</li>
</ul>

<p><strong>Pay:</strong> the employer publishes no figure. The advert says "Competitive salary based on experience" and lists medical, dental, vision and 401(k) benefits, and the form asks for your desired compensation range. For reference only, the US Bureau of Labor Statistics May 2025 median for data entry keyers in the Houston metro area is $19.00 an hour.</p>

<p><strong>Note:</strong> this advert and its requirements are set by Hays Electrical Services, not by JobGader. The link opens the employer's own application form, which takes a resume of up to 5 MB. Employer adverts close without notice, so check that the position is still shown before you prepare an application, and never pay anyone to apply.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, set by the employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::HAYS_URL,
                'meta_description' => 'Entry-level Administrative & Data Entry Coordinator at Hays Electrical Services in Houston, Texas: full-time, onsite, Word and Excel, posted 22 October 2026.',
                'seo_keywords' => 'data entry coordinator houston, entry level data entry jobs houston, hays electrical services jobs, administrative coordinator houston texas',
            ]
        );
    }
}
