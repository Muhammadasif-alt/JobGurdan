<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Eight live vacancies checked on the recruiter's own site between 18 and 26 November 2026, published as real
 * listings (listing_type vacancy) so the jobs board and the homepage carry current, indexable openings rather than
 * portal round-ups. Each description is written from the official advert, quotes no pay the recruiter did not
 * publish, links the recruiter's own page (no job-ID permalinks) and carries the closing date as expires_at, so
 * jobs:expire-old retires it on time.
 *
 * Sources: PPSC Advertisement 12/2026, SPSC Consolidated Advertisement 07/2026, KPPSC Advertisement 10/2026, the
 * Rescue 1122 Punjab advert of 14 Nov 2026, the Pakistan Railways Moghalpura apprentice advert of 13 Nov 2026,
 * Neon Link's careers page, Pavago's Himalayas listing and Haeman Tech's careers page.
 *
 * updateOrCreate, so re-running is safe.
 */
class OfficialVacanciesNov2026Seeder extends Seeder
{
    public const PPSC_POSITION = 'Provincial Management Service and Other BS-17 Posts (CCE-2026) - Punjab Public Service Commission';

    public const PPSC_URL = 'https://ppsc.gop.pk/Jobs.aspx';

    public const SPSC_POSITION = 'Assistant (BS-16) and Other Posts, Advertisement 07/2026 - Sindh Public Service Commission (Karachi)';

    public const SPSC_URL = 'https://spsc.gov.pk/advertisement/2026/adv-07-2026.pdf';

    public const KPPSC_POSITION = 'Sub Accountant, Treasuries and Accounts (BPS-16) - Khyber Pakhtunkhwa Public Service Commission';

    public const KPPSC_URL = 'https://www.kppsc.gov.pk/public/assets/adarchive/files/Advertisement_No_10_2026.pdf';

    public const RESCUE_POSITION = 'Emergency Medical Technician, Fire Rescuer and Rescue Driver (310 Posts) - Rescue 1122 Punjab';

    public const RESCUE_URL = 'https://rescue.gov.pk/jobs/ActiveJobs.aspx';

    public const RAILWAYS_POSITION = 'Skilled C-Class Apprentice (BPS-06), Moghalpura Workshops and Signal Shops - Pakistan Railways (Lahore)';

    public const RAILWAYS_URL = 'https://www.pakrail.gov.pk/Career.aspx';

    public const NEONLINK_POSITION = 'AI-Enabled Executive Assistant (Remote, Pakistan) - Neon Link';

    public const NEONLINK_URL = 'https://neonlink.ai/careers/job/ai-enabled-executive-assistant-pakistan/';

    public const PAVAGO_POSITION = 'Executive Assistant (Remote, Pakistan, US Hours) - Pavago';

    public const PAVAGO_URL = 'https://himalayas.app/companies/pavago/jobs/executive-assistant';

    public const HAEMAN_POSITION = 'Embedded Firmware Engineer (Hybrid) - Haeman Tech (Lahore)';

    public const HAEMAN_URL = 'https://haemantech.com/careers/embedded-firmware-engineer/';

    /**
     * @return array<string, array{position: string, url: string, closes: ?string}>
     */
    public static function vacancies(): array
    {
        return [
            'ppsc' => ['position' => self::PPSC_POSITION, 'url' => self::PPSC_URL, 'closes' => '2026-11-30'],
            'spsc' => ['position' => self::SPSC_POSITION, 'url' => self::SPSC_URL, 'closes' => '2026-12-14'],
            'kppsc' => ['position' => self::KPPSC_POSITION, 'url' => self::KPPSC_URL, 'closes' => '2026-12-17'],
            'rescue' => ['position' => self::RESCUE_POSITION, 'url' => self::RESCUE_URL, 'closes' => '2026-12-05'],
            'railways' => ['position' => self::RAILWAYS_POSITION, 'url' => self::RAILWAYS_URL, 'closes' => '2026-12-05'],
            'neonlink' => ['position' => self::NEONLINK_POSITION, 'url' => self::NEONLINK_URL, 'closes' => null],
            'pavago' => ['position' => self::PAVAGO_POSITION, 'url' => self::PAVAGO_URL, 'closes' => '2026-12-21'],
            'haeman' => ['position' => self::HAEMAN_POSITION, 'url' => self::HAEMAN_URL, 'closes' => null],
        ];
    }

    public function run(): void
    {
        $government = Category::firstOrCreate(['slug' => 'government-public-sector'], ['name' => 'Government & Public Sector']);
        $admin = Category::firstOrCreate(['slug' => 'customer-support-admin'], ['name' => 'Customer Support & Admin']);
        $it = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
        $trades = Category::firstOrCreate(['slug' => 'construction-trades'], ['name' => 'Construction & Trades']);

        $punjab = Location::firstOrCreate(['name' => 'Punjab'], ['area' => 'Province-wide', 'country' => 'Pakistan']);
        $karachi = Location::firstOrCreate(['name' => 'Karachi'], ['area' => 'Sindh', 'country' => 'Pakistan']);
        $kp = Location::firstOrCreate(['name' => 'Khyber Pakhtunkhwa'], ['area' => 'Province-wide', 'country' => 'Pakistan']);
        $lahore = Location::firstOrCreate(['name' => 'Lahore'], ['area' => 'Punjab', 'country' => 'Pakistan']);
        $pakistan = Location::firstOrCreate(['name' => 'Pakistan'], ['area' => 'Nationwide', 'country' => 'Pakistan']);

        foreach ($this->listings() as $key => $listing) {
            $vacancy = self::vacancies()[$key];
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser']],
                ['type' => $listing['public'] ? 'Government' : 'Private', 'display_reference' => $listing['reference']]
            );

            $location = match ($listing['location']) {
                'punjab' => $punjab,
                'karachi' => $karachi,
                'kp' => $kp,
                'lahore' => $lahore,
                default => $pakistan,
            };

            $category = match ($listing['category']) {
                'admin' => $admin,
                'it' => $it,
                'trades' => $trades,
                default => $government,
            };

            Job::updateOrCreate(
                ['position' => $vacancy['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => $listing['employment_type'],
                    'job_type' => $listing['job_type'],
                    'work_hours' => $listing['work_hours'],
                    'language' => 'English',
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $vacancy['url'],
                    'expires_at' => $vacancy['closes'] === null
                        ? null
                        : Carbon::parse($vacancy['closes'].' '.$listing['closes_at'], 'Asia/Karachi')->utc(),
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    /**
     * @return array<string, array{advertiser: string, reference: string, public: bool, location: string, category: string, employment_type: string, job_type: string, work_hours: string, closes_at: string, meta: string, keywords: string, description: string}>
     */
    private function listings(): array
    {
        return [
            'ppsc' => [
                'advertiser' => 'Punjab Public Service Commission',
                'reference' => 'ppsc',
                'public' => true,
                'location' => 'punjab',
                'category' => 'government',
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Government office hours',
                'closes_at' => '23:59:59',
                'meta' => 'PPSC CCE-2026: 48 BS-17 posts including 45 PMS, for graduates (2nd division) aged 21 to 32 with Punjab domicile. Fee Rs 1,000, closes 30 November 2026.',
                'keywords' => 'ppsc cce 2026, pms jobs punjab, ppsc advertisement 12/2026, bs-17 jobs punjab, excise and taxation officer jobs',
                'description' => <<<'JOBHTML'
<p>The Punjab Public Service Commission's Advertisement No. 12/2026, published on 8 November 2026 and checked by JobGader on 19 November 2026, invites online applications for the Combined Competitive Examination 2026. It covers 48 BS-17 posts: 45 in the Provincial Management Service (41 open merit, 2 minority quota and 2 for special persons) in the Services and General Administration Department, 1 Excise and Taxation Officer and 2 Assistant Directors Local Fund Audit. Posting can be anywhere in Punjab.</p>

<h3>Eligibility</h3>
<ul>
    <li>A graduate degree in at least second division from a recognised university; no work experience is required</li>
    <li>Age 21 to 32 for general candidates, counted on 1 January 2027, after the Punjab government's one-time relaxation for the 2026 and 2027 exams (attempts also raised from 3 to 4); 35 for serving officials with four years' service and up to 45 for special persons</li>
    <li>Punjab domicile; male, female and transgender candidates may apply</li>
</ul>

<h3>Selection</h3>
<p>Ten compulsory papers plus one optional subject, including general knowledge, a Mental Ability and Reasoning Test, English essay, English and Urdu, Islamic Studies or Ethics, two Pakistan Studies papers, International Affairs and Economy and Development. Candidates need 33% in the reasoning test, 40 marks in each other paper and 550 marks overall to reach the interview, then 50% in the interview. Wrong answers in the general knowledge MCQ paper lose 0.25 marks.</p>

<p><strong>Fee and closing date:</strong> Rs 1,000, paid online through PPSC's e-Pay system; applications are online only and close on 30 November 2026. Pay is at BS-17 under the Punjab government's current pay scale; the advert gives no rupee figure.</p>

<p><strong>Note:</strong> this recruitment and its rules are set by the Punjab Public Service Commission, not by JobGader. The link opens PPSC's Jobs page; never pay anyone other than PPSC's official fee channel.</p>
JOBHTML,
            ],
            'spsc' => [
                'advertiser' => 'Sindh Public Service Commission',
                'reference' => 'spsc',
                'public' => true,
                'location' => 'karachi',
                'category' => 'government',
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Government office hours',
                'closes_at' => '17:00:00',
                'meta' => 'SPSC Advertisement 07/2026: Assistant (BS-16) posts in Karachi for graduates (2nd division) aged 18 to 28, plus technical posts. Closes 14 December 2026, 5 PM.',
                'keywords' => 'spsc jobs 2026, spsc advertisement 07/2026, assistant bs-16 jobs karachi, sindh government jobs, spsc assistant jobs',
                'description' => <<<'JOBHTML'
<p>The Sindh Public Service Commission's Consolidated Advertisement No. 07/2026, dated 12 November 2026 and checked by JobGader on 19 November 2026, invites online applications for posts in several Government of Sindh departments. The posts most open to fresh graduates are Assistant (BS-16) positions in Karachi offices, including the Inspectorate of Coal Mines, the Directorate General Coal Mines Development, Power Development Sindh and the Electric Inspectorates (Regions I and II).</p>

<h3>Assistant (BS-16) eligibility</h3>
<ul>
    <li>A bachelor's degree in at least second division from an HEC-recognised university, and for most offices a certificate in MS Office or computing from a government-recognised institute</li>
    <li>Age 18 to 28 or 21 to 28, depending on the office</li>
    <li>Sindh domicile, with posts split between urban and rural quotas</li>
</ul>

<h3>Other posts in the same advert</h3>
<p>Stenographer (BS-16) posts need a graduate degree in second division, a shorthand and typing certificate, and speeds of 120 words a minute in shorthand and 35 in typing. Assistant Director (Technical) posts at the coal mines directorates in Karachi and Jamshoro need a master's in geology or a bachelor's in mining engineering (age 22 to 28), and the Medical Inspector post in Karachi needs MBBS with PMDC registration (age 23 to 30). Some posts, such as Programmer and Prosecuting Inspector, require three to five years of experience, so check each line.</p>

<p><strong>Closing date:</strong> 14 December 2026. Applications are online only, and the portal closes at 5:00 PM on the last day. The advert states pay scales, not rupee salaries.</p>

<p><strong>Note:</strong> this recruitment is run by the Sindh Public Service Commission, not by JobGader. The link opens SPSC's own advertisement; apply through spsc.gov.pk only.</p>
JOBHTML,
            ],
            'kppsc' => [
                'advertiser' => 'Khyber Pakhtunkhwa Public Service Commission',
                'reference' => 'kppsc',
                'public' => true,
                'location' => 'kp',
                'category' => 'government',
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Government office hours',
                'closes_at' => '17:00:00',
                'meta' => 'KPPSC Advt 10/2026: 49 Sub Accountant (BPS-16) posts for second-class B.Com, BBA, ACMA or MBA holders aged 21 to 35. Fee Rs 500, closes 17 December 2026.',
                'keywords' => 'kppsc sub accountant 2026, kppsc advertisement 10/2026, kp government jobs, sub accountant bps-16, kppsc jobs december 2026',
                'description' => <<<'JOBHTML'
<p>The Khyber Pakhtunkhwa Public Service Commission's Advertisement No. 10/2026, dated 25 November 2026 and checked by JobGader on 26 November 2026, includes 49 posts of Sub Accountant (Treasuries and Accounts) at BPS-16 in the Finance Department. Applications are open to Pakistani citizens with a Khyber Pakhtunkhwa domicile.</p>

<h3>Eligibility</h3>
<ul>
    <li>At least a second-class bachelor's degree in commerce or business administration, or ACMA, or an MBA, from a recognised university; no experience is asked for</li>
    <li>Age 21 to 35; open to men and women</li>
    <li>Khyber Pakhtunkhwa domicile</li>
</ul>

<h3>How the 49 posts are split</h3>
<p>Thirty-nine are general quota posts allocated by merit and by KP's six zones, six are reserved for women, three for minorities and one for a disabled candidate. Your zone of domicile therefore affects which merit list you compete on.</p>

<h3>Other posts in Advertisement 10/2026</h3>
<p>The same advert has 21 Sub Divisional Forest Officer posts (BPS-17, second-class M.Sc or four-year BS in forestry, age 21 to 32), 17 Sub Divisional Wildlife Officer posts (BPS-17), Veterinary Officer posts, a GIS Specialist and a female Librarian post on the minority quota.</p>

<p><strong>Fee and closing date:</strong> Rs 500 (excluding service charges), paid through the 1Bill option in banking apps or Easypaisa and JazzCash. Applications are online at kppsc.gov.pk and close on 17 December 2026 at 5:00 PM. The advert gives the pay scale, not a rupee salary.</p>

<p><strong>Note:</strong> this recruitment is run by KPPSC, not by JobGader. The link opens KPPSC's own advertisement.</p>
JOBHTML,
            ],
            'rescue' => [
                'advertiser' => 'Rescue 1122 Punjab (Emergency Services Department)',
                'reference' => 'rescue-1122-punjab',
                'public' => true,
                'location' => 'punjab',
                'category' => 'government',
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work, set by the department',
                'closes_at' => '23:59:59',
                'meta' => 'Rescue 1122 Punjab: 310 posts (EMT, Fire DERT Rescuer, Rescue Driver, Field Worker) across Punjab districts, age 20 to 30. Apply through CTS by 5 December 2026.',
                'keywords' => 'rescue 1122 jobs 2026, rescue 1122 punjab jobs, emergency medical technician jobs punjab, rescue driver jobs, fire rescuer jobs punjab',
                'description' => <<<'JOBHTML'
<p>Punjab's Emergency Services Department (Rescue 1122) advertised 310 posts on 14 November 2026, checked by JobGader on 19 November 2026. The posts are for districts across Punjab and for the Rescue headquarters and academy.</p>

<h3>Posts and qualifications</h3>
<ul>
    <li><strong>Emergency Medical Technician (32 posts, PPS-3):</strong> nursing or paramedical degree or diploma in second division</li>
    <li><strong>Fire DERT Rescuer (140 posts, PPS-3):</strong> Diploma of Associate Engineering (chemical, electronics, mining, mechanical, civil, electrical, telecom or architecture) in second division</li>
    <li><strong>Rescue Driver, LTV (113 posts, PPS-2):</strong> Matric in second division and a valid LTV licence</li>
    <li><strong>Field Worker (25 posts, PPS-1):</strong> Matric in second division with a NAVTTC electrical or cooking certificate</li>
</ul>

<h3>Conditions</h3>
<p>Age 20 to 30 for every post, with no relaxation for age or qualification. Candidates need 6/6 vision in each eye without glasses, no colour blindness and a BMI under 30. Preference goes to candidates taller than 5 feet 6 inches, and the minimum chest is 33 inches. Fifteen per cent of posts are reserved for women and five per cent for minorities, and local residents (by CNIC) get preference for district posts. The posts are temporary and non-transferable. Selection includes written, psychological, physical and skill tests, then a final interview at Rescue headquarters in Lahore. Gujrat, Gujranwala, Chakwal, Sargodha, Khushab, Sialkot, Narowal, Jhelum, Rawalpindi and Bahawalpur have the most district posts.</p>

<p><strong>How to apply:</strong> online through Central Testing Service (ctspak.com), or the link on rescue.gov.pk, by 5 December 2026, then post the printed form to CTS. Salary follows the government notification; the advert gives no rupee figure.</p>

<p><strong>Note:</strong> this recruitment is run by the Emergency Services Department, Government of the Punjab, not by JobGader.</p>
JOBHTML,
            ],
            'railways' => [
                'advertiser' => 'Pakistan Railways',
                'reference' => 'pakistan-railways',
                'public' => true,
                'location' => 'lahore',
                'category' => 'trades',
                'employment_type' => 'Contract',
                'job_type' => 'On-site',
                'work_hours' => 'Workshop hours, set by Pakistan Railways',
                'closes_at' => '23:59:59',
                'meta' => 'Pakistan Railways: 227 C-Class apprentice posts (BPS-06) in Lahore for Matric (2nd division) plus a one-year trade certificate. Apply by post by 5 Dec 2026.',
                'keywords' => 'pakistan railways apprenticeship 2026, moghalpura workshops jobs, railway apprentice lahore, c-class apprentice jobs, jobs after matric lahore',
                'description' => <<<'JOBHTML'
<p>Pakistan Railways' Divisional Superintendent, Workshops Division Moghalpura, advertised on 13 November 2026 (checked by JobGader on 19 November 2026) the remaining posts from its 2 April 2026 advert: 227 Skilled (BPS-06) C-Class apprentices, 190 at the Moghalpura Workshops and 37 at the Signal Shops in Lahore. The apprenticeship is a two-year contract with training at the Apprentice Training Centre, Moghalpura, on a fixed stipend at the initial BS-06 stage.</p>

<h3>Trades</h3>
<p>The largest intakes are Coach Builder (65), C&amp;W Fitter (27), Welder (17), Diesel Fitter (15) and Black Smith (14) at Moghalpura, and Fitter (13), Moulder (10) and Furnace Man (9) at the Signal Shops. Smaller numbers are open for Millwright, Machinist, Painter, Electronics, Wireman Diesel, Boiler Maker, Mason and other trades.</p>

<h3>Eligibility</h3>
<ul>
    <li>Matric in second division plus a one-year certificate or diploma in the trade from a government-recognised technical training institute</li>
    <li>Age 18 to 25, plus 5 years of general relaxation (maximum 30), counted on the closing date</li>
    <li>Posts are split by provincial quota and by sub-quota (open merit, son of railway employee, ex-serviceman, orphan, minority)</li>
</ul>

<p><strong>How to apply:</strong> by post only. Download the form from pakrail.gov.pk, attach attested copies, a character certificate, three photographs and four stamped envelopes, plus an original postal order for Rs 1,000 (non-refundable). Send it by registered post or courier to the Divisional Superintendent, Pakistan Railways, Workshops Division Moghalpura, Lahore, by 5 December 2026, marking the envelope Workshops or Signal Shops. Written test centres are Lahore, Rawalpindi and Multan. Hand-delivered applications are not accepted.</p>

<p><strong>Note:</strong> this recruitment is run by Pakistan Railways, not by JobGader.</p>
JOBHTML,
            ],
            'neonlink' => [
                'advertiser' => 'Neon Link',
                'reference' => 'neon-link',
                'public' => false,
                'location' => 'pakistan',
                'category' => 'admin',
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Monday to Friday, US business hours (night shift in Pakistan)',
                'closes_at' => '23:59:59',
                'meta' => 'Remote AI-Enabled Executive Assistant for US founders, anywhere in Pakistan: USD 200 to 435 a month, US hours, 3+ years assistant or office experience.',
                'keywords' => 'remote executive assistant pakistan, neon link executive assistant, work from home executive assistant, ai executive assistant jobs, us night shift remote jobs pakistan',
                'description' => <<<'JOBHTML'
<p>Neon Link, a US-based company, is hiring AI-enabled executive assistants who work from anywhere in Pakistan to support US founders and small-business owners. The listing was posted on 2 November 2026, updated on 7 November and checked by JobGader on 18 November 2026, when it was still accepting applications with no closing date given.</p>

<h3>What the role involves</h3>
<ul>
    <li>Managing inboxes, calendars, travel, CRM records and follow-ups for a US client</li>
    <li>Using AI tools to draft replies, summarise long email threads and meetings, and research</li>
    <li>Building simple automations for repetitive tasks with tools such as Zapier, Make, n8n or Power Automate</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Three or more years as an executive assistant, virtual assistant, office manager or chief of staff; experience with a US company is not essential</li>
    <li>Strong written English, judgement and comfort with Google Workspace or Microsoft 365 and AI tools such as ChatGPT or Claude</li>
    <li>Any bachelor's degree is preferred but not required</li>
</ul>

<p><strong>Pay and hours:</strong> USD 200 to 435 a month, which the employer puts at about USD 1 to 3 an hour or roughly PKR 55,000 to 120,000. Work is Monday to Friday on US business hours, a night shift in Pakistan starting between 6 PM and 10 PM, with weekends off.</p>

<p><strong>Note:</strong> this vacancy is set by Neon Link, not by JobGader. Neon Link says it never charges a fee to apply, test, train or start, and contacts applicants only from neonlink.ai email addresses.</p>
JOBHTML,
            ],
            'pavago' => [
                'advertiser' => 'Pavago',
                'reference' => 'pavago',
                'public' => false,
                'location' => 'pakistan',
                'category' => 'admin',
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Full-time, US business hours',
                'closes_at' => '23:59:59',
                'meta' => 'Remote Executive Assistant for a US client through Pavago, open to Pakistan: US business hours, 3+ years of EA experience. Applications close 21 December 2026.',
                'keywords' => 'pavago executive assistant, remote executive assistant pakistan, executive assistant us hours, remote ea jobs pakistan, offshore executive assistant',
                'description' => <<<'JOBHTML'
<p>Pavago, an offshore recruitment company, is hiring a full-time remote Executive Assistant for one of its clients, whom it does not name. The listing on Himalayas was posted on 6 September 2026 and checked by JobGader on 18 November 2026; its location requirement is Pakistan and applications close on 21 December 2026.</p>

<h3>What the role involves</h3>
<ul>
    <li>Managing complex calendars and resolving scheduling conflicts for senior leadership</li>
    <li>Handling inboxes, preparing briefing notes and arranging travel</li>
    <li>Coordinating across teams, tracking priorities and preparing research summaries for decision-makers</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>At least three years of executive assistant experience</li>
    <li>Strong Google Workspace and Microsoft Office skills and excellent English communication</li>
    <li>Comfort with tools such as Slack, Zoom, Notion or Asana, and discretion with confidential information</li>
    <li>Availability during US business hours, which is evening and night in Pakistan</li>
</ul>

<p><strong>Pay:</strong> the listing states no salary. Ask for the monthly figure, currency, payment date and whether you are engaged as an employee or a contractor before accepting.</p>

<p><strong>Note:</strong> this vacancy is set by Pavago and its client, not by JobGader. The link opens Pavago's listing; never pay anyone to be considered.</p>
JOBHTML,
            ],
            'haeman' => [
                'advertiser' => 'Haeman Tech',
                'reference' => 'haeman-tech',
                'public' => false,
                'location' => 'lahore',
                'category' => 'it',
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, hybrid',
                'closes_at' => '23:59:59',
                'meta' => 'Embedded Firmware Engineer at Haeman Tech, Lahore (hybrid): bare-metal and FreeRTOS firmware on STM32 and ESP32, 3+ years. Rust, Zephyr and BLE a plus.',
                'keywords' => 'embedded firmware engineer lahore, haeman tech careers, stm32 esp32 jobs lahore, freertos jobs pakistan, embedded jobs lahore',
                'description' => <<<'JOBHTML'
<p>Haeman Tech is recruiting an Embedded Firmware Engineer to develop bare-metal and RTOS firmware on STM32 and ESP32 for production devices. The role is full-time and hybrid in Lahore, and asks for three or more years of experience. JobGader checked the employer's careers page on 18 November 2026.</p>

<h3>What the role involves</h3>
<ul>
    <li>Writing bare-metal and FreeRTOS firmware for ARM Cortex-M microcontrollers</li>
    <li>Implementing and debugging UART, SPI, I2C, CAN and USB peripherals</li>
    <li>Designing over-the-air (OTA) update pipelines with rollback safety</li>
    <li>Profiling and optimising firmware for power use and latency</li>
</ul>

<h3>Skills</h3>
<ul>
    <li>Required: STM32, ESP32, FreeRTOS, C, and UART, SPI and I2C</li>
    <li>Preferred: Rust, Zephyr, DSP and BLE</li>
</ul>

<p>A short portfolio helps: one or two documented projects on STM32 or ESP32 with a driver you wrote from a datasheet, logic-analyser captures and a note on how you tested it will show more than a list of skills. Expect technical questions on pointers, volatile, interrupt safety and how you would debug a board that will not boot.</p>

<p><strong>Pay:</strong> the employer publishes no figure. The careers page gives official@haemantech.com as its contact and also lists hybrid Embedded Linux Engineer and Windows Kernel Driver Engineer roles and an on-site Kernel Engineering Intern role.</p>

<p><strong>Note:</strong> this vacancy is set by Haeman Tech, not by JobGader. Check the careers page for the current status before applying.</p>
JOBHTML,
            ],
        ];
    }
}
