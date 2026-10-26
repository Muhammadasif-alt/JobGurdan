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

    public const POCKETFUL_POSITION = 'Associate, Operations and Back Office - Pocketful (Delhi)';

    public const POCKETFUL_URL = 'https://www.pocketful.in/careers/open-roles/associate-operations-back-office-pocketful';

    public const IBEX_POSITION = 'Customer Success Specialist, Voice - ibex (Karachi, Sindh)';

    public const IBEX_URL = 'https://careers.ibex.co/jobs/26984?lang=en-us';

    public const ACCOR_POSITION = 'Guest Relations Officer / Receptionist - Queen Elizabeth 2, Accor (Dubai)';

    public const ACCOR_URL = 'https://careers.accor.com/global/en/job/guest-relations-officer-receptionist-in-queen-elizabeth-2-dubai-united-arab-emirates-jid-114474';

    /**
     * @return array<string, array{position: string, url: string}>
     */
    public static function vacancies(): array
    {
        return [
            'hays' => ['position' => self::HAYS_POSITION, 'url' => self::HAYS_URL],
            'pocketful' => ['position' => self::POCKETFUL_POSITION, 'url' => self::POCKETFUL_URL],
            'ibex' => ['position' => self::IBEX_POSITION, 'url' => self::IBEX_URL],
            'accor' => ['position' => self::ACCOR_POSITION, 'url' => self::ACCOR_URL],
        ];
    }

    public function run(): void
    {
        $this->seedHaysDataEntryCoordinator();
        $this->seedPocketfulOperationsAssociate();
        $this->seedIbexCustomerSuccessSpecialist();
        $this->seedAccorQe2Receptionist();
    }

    private function adminCategory(): Category
    {
        return Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );
    }

    /**
     * Fetched 26 October 2026: HTTP 200, posted 22 October 2026, Houston TX
     * onsite, full-time, labelled Entry Level, resume-upload form present.
     */
    private function seedHaysDataEntryCoordinator(): void
    {
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
                'category_id' => $this->adminCategory()->id,
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

    /**
     * Fetched 26 October 2026: HTTP 200, Delhi, full time, onsite, 1 to 4
     * years, NISM preferred, no salary, application form on the page.
     */
    private function seedPocketfulOperationsAssociate(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Pocketful'],
            ['type' => 'Private', 'display_reference' => 'pocketful']
        );

        $delhi = Location::firstOrCreate(
            ['name' => 'Delhi'],
            ['area' => 'Delhi', 'country' => 'India']
        );

        Job::updateOrCreate(
            ['position' => self::POCKETFUL_POSITION, 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $this->adminCategory()->id,
                'location_id' => $delhi->id,
                'description' => <<<'JOBHTML'
<p>Pocketful, a SEBI-registered stock broker (Pocketful Fintech Capital Private Limited, a member of NSE, BSE and MCX), is recruiting an Associate for its Operations and Back Office team in Delhi. The advert, checked by JobGader on 26 October 2026, is full time and onsite.</p>

<h3>What the role involves</h3>
<ul>
    <li>Back office processing for a stock-broking operation: client onboarding and KYC, trade settlements and reconciliation</li>
    <li>Depository (DP) operations, liaison with exchanges and depositories, and support for audits and compliance</li>
    <li>Resolving operational issues and maintaining accurate records</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>1 to 4 years of experience in stock-broking operations, back office, DP operations or financial services operations</li>
    <li>NISM certification preferred, not required; familiarity with CDSL and NSDL processes and broking back-office software is also preferred</li>
    <li>This is an experienced role, not a fresher opening</li>
</ul>

<p><strong>Pay:</strong> the employer publishes no figure. The advert says "Compensation will be aligned with market standards". JobGader found no official average for the role in Delhi and repeats no job-board estimate.</p>

<p><strong>Note:</strong> this advert and its requirements are set by Pocketful, not by JobGader. The link opens the employer's own page, which carries the application form. Employer adverts close without notice, so check that the position is still shown before you apply, and never pay anyone to apply.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, set by the employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::POCKETFUL_URL,
                'meta_description' => 'Associate, Operations and Back Office at Pocketful, Delhi: full time, onsite, 1 to 4 years in broking operations, NISM preferred, checked 26 October 2026.',
                'seo_keywords' => 'back office jobs delhi, operations associate delhi, stock broking back office jobs, pocketful careers',
            ]
        );
    }

    /**
     * Rendered through headless Chrome on 26 October 2026: Karachi, voice
     * programme for a US client, O Levels/Matric or above, English, MS Office,
     * 18 or over with a CNIC, no experience line, Apply button on the page.
     */
    private function seedIbexCustomerSuccessSpecialist(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'ibex Pakistan'],
            ['type' => 'Private', 'display_reference' => 'ibex-pakistan']
        );

        $karachi = Location::firstOrCreate(
            ['name' => 'Karachi'],
            ['area' => 'Sindh', 'country' => 'Pakistan']
        );

        Job::updateOrCreate(
            ['position' => self::IBEX_POSITION, 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $this->adminCategory()->id,
                'location_id' => $karachi->id,
                'description' => <<<'JOBHTML'
<p>ibex Pakistan is recruiting a Customer Success Specialist for a voice programme in its Karachi operations department, assisting a US-based client's customers by call and resolving their queries under the defined processes. The advert was checked by JobGader on 26 October 2026 and lists no minimum experience; it says the company cares more about personality, passion and work ethic than international customer experience.</p>

<h3>Requirements</h3>
<ul>
    <li>O Levels, Matric or above</li>
    <li>Excellent English written and verbal communication, with an American or neutral accent</li>
    <li>Proficiency with MS Office, particularly MS Excel and MS Word</li>
    <li>At least 18 years old and holding a valid CNIC</li>
</ul>

<p><strong>Pay and shift:</strong> the advert publishes no salary and no shift times. A voice programme for US customers normally follows US hours, so ask about the shift, transport and fixed pay versus incentives before you accept.</p>

<p><strong>Note:</strong> this advert and its requirements are set by ibex, not by JobGader. The link opens the employer's own careers page, where the Apply button leads to its application. Employer adverts close without notice, so check that the position is still shown before you apply, and never pay anyone to apply.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shifts set by the employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::IBEX_URL,
                'meta_description' => 'ibex Karachi: Customer Success Specialist (voice). Matric or O Levels, strong English, MS Office, 18 or over with a CNIC, no experience line.',
                'seo_keywords' => 'call centre jobs karachi freshers, ibex karachi jobs, customer success specialist karachi, customer support jobs karachi',
            ]
        );
    }

    /**
     * Fetched 26 October 2026: HTTP 200, Dubai, full-time, permanent, requires
     * UAE hotel front desk experience, Opera and Microsoft, English; no
     * salary; applies through SmartRecruiters.
     */
    private function seedAccorQe2Receptionist(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Accor'],
            ['type' => 'Private', 'display_reference' => 'accor']
        );

        $dubai = Location::firstOrCreate(
            ['name' => 'Dubai'],
            ['area' => 'Dubai', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            ['position' => self::ACCOR_POSITION, 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $dubai->id,
                'description' => <<<'JOBHTML'
<p>Accor is recruiting a Guest Relations Officer / Receptionist for the Queen Elizabeth 2, its floating hotel in Dubai, United Arab Emirates. The advert (reference REF123286K), checked by JobGader on 26 October 2026, is full-time and permanent in the Rooms department.</p>

<h3>What the role involves</h3>
<ul>
    <li>Greeting guests, handling requests, answering enquiries and calls, and checking guests in and out on the Opera system</li>
    <li>Creating, confirming and cancelling reservations, advising guests of rates and services, and managing room allocation and availability</li>
    <li>Guest safe deposit boxes and key control, occupancy and arrival reports, assisting the concierge and knowing the emergency procedures</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Work experience as a hotel front desk agent, receptionist or similar role in the UAE</li>
    <li>Proficiency in Opera and Microsoft software</li>
    <li>Fluency in English; other languages are a plus</li>
    <li>Excellent communication and organisational skills</li>
</ul>

<p><strong>Pay:</strong> the employer publishes no salary or benefits on the advert, and the UAE Labour Law sets no minimum salary. Wages in the UAE are paid through the Wage Protection System, and an employer may not charge you recruitment costs under Article 6 of Federal Decree-Law No. 33 of 2021.</p>

<p><strong>Note:</strong> this advert and its requirements are set by Accor, not by JobGader. The link opens the employer's own careers page, where the Apply button leads to its application system. Employer adverts close without notice, so check that the position is still shown before you apply, and never pay anyone for a job or a visa.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shifts set by the employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::ACCOR_URL,
                'meta_description' => 'Guest Relations Officer / Receptionist, Accor Queen Elizabeth 2, Dubai: full-time, permanent, UAE hotel front desk experience, Opera, English.',
                'seo_keywords' => 'receptionist jobs dubai, hotel receptionist jobs dubai, guest relations officer dubai, accor careers dubai',
            ]
        );
    }
}
