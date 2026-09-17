<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Government Security Jobs in Australia" — a guide to protective, border,
 * intelligence and security vetting work with Australian Government agencies:
 * who hires, what each agency pays and requires, how security clearances work,
 * and why most roles are open only to Australian citizens.
 *
 * Corrections and clarifications to the draft (checked against the Australian
 * Federal Police jobs pages, the Department of Home Affairs and Australian
 * Border Force careers pages, ASIO's careers portal and live vacancies, and
 * APSJobs, September 2026; Defence, ASD, the Graduate Program and AGSVA pages
 * would not load and were checked through their official search listings):
 *
 * 1. The draft says Home Affairs lists "Assistant Border Force Officer"
 *    opportunities. No role by that name was advertised. The ABF's entry-level
 *    route is the Border Force Officer Recruit Trainee (BFORT) program.
 *
 * 2. The draft lists "Assessments" and "Security Force & T4" as separate ASIO
 *    job categories. They are one category, "Assessments, Security Force & T4".
 *    ASIO roles also need a TOP SECRET-Privileged Access clearance, which the
 *    draft does not name.
 *
 * 3. The draft says ASD applicants undergo a security assessment and obtain a
 *    clearance through AGSVA as one step. ASD runs its own Organisational
 *    Suitability Assessment, and the clearance is vetted separately.
 *
 * 4. The draft presents a Home Affairs Intelligence Stream graduate pathway.
 *    Home Affairs recruits graduates through the whole-of-government Australian
 *    Government Graduate Program streams, where you must nominate Home Affairs
 *    as a preferred employer.
 *
 * 5. The draft's list of Defence and ASD security job titles could not be
 *    confirmed on the official pages, so the guide uses the agencies' own
 *    descriptions. It also adds AFP protective service officer pay,
 *    eligibility, training and hiring locations, which the draft leaves out.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class GovernmentSecurityJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.apsjobs.gov.au/';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Government Security Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Government security jobs in Australia include AFP protective service officers, Border Force officers, ASIO and ASD roles and Defence security staff. Most need Australian citizenship and a security clearance. AFP PSO pay starts at $82,499.88.',
                'content' => $content,
                'featured_image' => 'blogs/government-security-jobs-in-australia.jpg',
                'tags' => 'government security jobs australia, afp protective service officer, australian border force jobs, asio jobs, asd careers, defence security jobs, australian government security clearance, baseline security clearance',
                'meta_title' => 'Government Security Jobs in Australia: Pay and Clearances',
                'meta_description' => 'Government security jobs in Australia at the AFP, Border Force, ASIO, ASD and Defence: pay, citizenship rules, security clearances and how to apply.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Australian Government Security, Border and Intelligence Agencies (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'au-government-security-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Government Security Officer — Protective Service, Border Force, Intelligence and Security Vetting Roles, Australian Government',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; some roles involve shift work',
                'language' => 'English',
                // Each agency sets its own classification and pay, so no single
                // band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Protective service, border, intelligence and security vetting roles with Australian Government agencies. Most require Australian citizenship and a clearance.',
                'seo_keywords' => 'government security jobs australia, protective service officer jobs, border force officer jobs, asio jobs, security vetting jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian Government agencies, including the Australian Federal Police, the Australian Border Force, ASIO, ASD and Defence, hire staff to protect people, places, borders, information and systems.</p>

<h3>Common requirements</h3>
<ul>
    <li>Australian citizenship for most roles</li>
    <li>The ability to obtain and keep an Australian Government security clearance, from Baseline up to TOP SECRET-Privileged Access</li>
    <li>Employment suitability or integrity screening, and drug testing for some agencies</li>
    <li>For AFP protective service officers: aged 18 or over, fit and active, with a driver's licence</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> eligibility, clearances and hiring decisions are set by each agency &mdash; not by JobGader. Apply only through official government career sites and APSJobs.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Government security jobs in Australia range from frontline protection and border work to intelligence, cyber security and security vetting, with employers such as the Australian Federal Police (AFP), the Australian Border Force (ABF), ASIO, the Australian Signals Directorate (ASD) and the Department of Defence.</strong> Almost all of them need <strong>Australian citizenship</strong> and the ability to hold a <strong>security clearance</strong>. An entry-level AFP protective service officer starts on <strong>$82,499.88</strong> including allowances, and ASIO was advertising team member roles at <strong>$100,425 to $107,794</strong> in September 2026.</p>

<p>This guide explains what each agency hires for, what it pays and requires, how clearances work and how to apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.apsjobs.gov.au/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128737; Search Australian Government Jobs on APSJobs &rarr;
    </a>
</div>

<h2>Who Hires for Government Security Jobs?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Agency</th>
            <th style="padding:10px;text-align:left;">Security work</th>
            <th style="padding:10px;text-align:left;">Key entry requirements</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australian Federal Police</strong></td><td style="padding:10px;">Protective service officers at airports, Parliament House and Defence sites; police</td><td style="padding:10px;">18+, Australian citizen, good character, security clearance, drug testing</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australian Border Force / Home Affairs</strong></td><td style="padding:10px;">Border Force officers, investigations, intelligence, policy</td><td style="padding:10px;">Employment suitability screening and at least a Baseline clearance</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>ASIO</strong></td><td style="padding:10px;">Intelligence officers, assessments, security force, technical and surveillance roles</td><td style="padding:10px;">Australian citizen, TOP SECRET-Privileged Access clearance</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australian Signals Directorate</strong></td><td style="padding:10px;">Cyber security, security and intelligence roles</td><td style="padding:10px;">Australian citizen, suitability assessment and clearance</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Department of Defence</strong></td><td style="padding:10px;">Security advice, vetting, investigations, policy and training</td><td style="padding:10px;">Set by each role; clearance required</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Official agency career pages, September 2026.</p>
</div>

<h2>AFP Protective Service Officers</h2>

<p>The most accessible frontline security job in the Australian Government is the AFP <strong>protective service officer (PSO)</strong>. The AFP says PSOs protect important people and key locations, including <strong>major airports, Parliament House in Canberra, Department of Defence sites across Australia and the Australian Nuclear Science and Technology Organisation (ANSTO)</strong>. The work includes:</p>

<ul>
    <li>patrolling critical infrastructure by vehicle, bike or on foot;</li>
    <li>controlling access to critical sites;</li>
    <li>running surveillance and responding to alarms;</li>
    <li>providing static and mobile protection to the diplomatic community;</li>
    <li>responding to national security threats.</li>
</ul>

<p><strong>Pay and conditions:</strong> salary starts at <strong>$82,499.88</strong>, which includes base salary, a 22% composite allowance and a Use of Force allowance, with extra pay in remote locations. Superannuation is <strong>15.4%</strong>. You earn while you learn during <strong>15 weeks of paid training</strong>, graduate with a POL41225 Certificate IV in Protective Services, and get 6 weeks of holidays.</p>

<p><strong>Eligibility:</strong> aged 18 or over, an Australian citizen, fit and active, with a driver's licence and good character. The 8-stage application process can take as little as 6 months but often takes up to 9. In September 2026 the AFP was actively recruiting PSOs for <strong>Canberra, Perth, Exmouth, Geraldton and Pine Gap</strong>.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/government-security-jobs-in-australia-protective-service.jpg" alt="Two uniformed protective security officers standing guard at the gates of Parliament House in Canberra" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">AFP protective service officers guard sites such as Parliament House, major airports and Defence bases.</figcaption>
</figure>

<h2>Australian Border Force and Home Affairs</h2>

<p>The ABF's entry-level route is the <strong>Border Force Officer Recruit Trainee (BFORT) program</strong>, which combines instructional and operational (on-the-job) training. For sea-going roles there is a separate Marine Tactical Officer program.</p>

<p>Screening is strict. Home Affairs says <strong>"All staff must go through the ESS process to be employed or engaged by the department."</strong> An <strong>Employment Suitability Clearance</strong> is required for all staff in ABF roles, an Onboarding Check for departmental roles, and <strong>"All staff must also hold a minimum Baseline level Commonwealth Security Clearance."</strong></p>

<p><strong>Where to apply:</strong> the <a href="https://www.homeaffairs.gov.au/about-us/careers/vacancies" target="_blank" rel="noopener">Home Affairs vacancies page</a>, which links to its Online Recruitment website, and APSJobs.</p>

<h2>ASIO</h2>

<p>ASIO advertises on its own <a href="https://www.careers.asio.gov.au/" target="_blank" rel="noopener">careers portal</a>, with job categories including <strong>Assessments, Security Force &amp; T4</strong>; Business &amp; Corporate Services; Information Technology; Intelligence Officer; Legal; Linguists; Surveillance Officer; Technical &amp; Engineering; Graduate; and Trades &amp; Property. Examples open in September 2026:</p>

<ul>
    <li><strong>AE5 Team Member &ndash; Intelligence Response Centre &ndash; Shift Work</strong>, Canberra: $100,425 to $107,794, closing 28 September 2026.</li>
    <li><strong>Intelligence Development Program 2028 Intakes</strong>: $100,425 to $118,220, closing 16 November 2026.</li>
</ul>

<p>Every ASIO applicant must be <strong>"An Australian citizen"</strong> and <strong>"Assessed as suitable to hold and maintain a TOP SECRET-Privileged Access security clearance."</strong> Staff receive a <strong>7.5% service allowance</strong> for maintaining that clearance. ASIO also asks for discretion: <strong>"Please do not discuss your application with others as doing so may adversely affect your potential employment."</strong></p>

<h2>Australian Signals Directorate</h2>

<p>ASD works in foreign signals intelligence and cyber security, and hires technical, cyber security, intelligence, security and corporate staff through <a href="https://www.asd.gov.au/careers" target="_blank" rel="noopener">ASD careers</a>. You must be an Australian citizen. ASD says prospective staff complete an <strong>Organisational Suitability Assessment</strong> run by ASD and, separately, obtain an Australian Government security clearance, and that the process can take between <strong>3 and 18 months</strong>. Any offer before then is conditional on passing both.</p>

<h2>Department of Defence</h2>

<p>Defence's APS <a href="https://www.defence.gov.au/jobs-careers/defence-aps-jobs/job-categories/security" target="_blank" rel="noopener">security job category</a> covers civilian security roles that provide security services to Defence and Defence industry, including security countermeasures, intelligence analysis, vetting of new personnel, psychological evaluations, policy and security training. Search Defence's current APS vacancies for security adviser, vetting and investigator roles.</p>

<h2>How Security Clearances Work</h2>

<p>Australian Government clearances run from <strong>Baseline</strong> at the entry level up to <strong>TOP SECRET-Privileged Access</strong> for the most sensitive roles, such as ASIO's. The level depends on the information and places the job gives you access to. Three things to know:</p>

<ul>
    <li><strong>Citizenship:</strong> only Australian citizens are eligible for an Australian Government security clearance, unless the sponsoring agency waives the requirement for an exceptional business need.</li>
    <li><strong>Sponsorship:</strong> the agency or employer that hires you sponsors the clearance, so you do not apply for one on your own.</li>
    <li><strong>Time:</strong> vetting looks at your background, finances, travel, associations and character, and higher levels take months. Answer every question honestly and completely.</li>
</ul>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/government-security-jobs-in-australia-entry-screening.jpg" alt="A security officer standing beside an X-ray screening point at the entrance to a government building in Canberra" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Access control and screening are core protective security tasks.</figcaption>
</figure>

<h2>Graduate Pathways</h2>

<p>Graduates can enter through the <strong>Australian Government Graduate Program (AGGP) Intelligence Stream</strong>, managed by the Office of National Intelligence. It asks for an Australian Qualifications Framework Level 7 qualification (a bachelor degree) or higher, Australian citizenship at the time of application, and the ability to obtain and keep a security clearance. Successful candidates join a merit pool and are matched to participating agencies.</p>

<p>Home Affairs does not run a separate intelligence graduate stream. It says <strong>"If you wish to be considered for a Department of Home Affairs and ABF Graduate role through one of the streams, you must apply directly to that stream. You must indicate the Department of Home Affairs as a preferred employer."</strong></p>

<h2>Can Non-Citizens Get Government Security Jobs?</h2>

<p>Very rarely. The AFP, ASIO and ASD all require Australian citizenship, and clearances are open only to citizens unless an agency grants a waiver. Permanent residents and visa holders are better placed looking at <strong>private security</strong>, which is licensed by each state and territory, or at non-security roles in the public service. If you are not yet in Australia, our <a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> guide compares the visa routes.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Choose your area:</strong> frontline protection, border work, intelligence, cyber security or security vetting and policy.</li>
    <li><strong>Check eligibility first:</strong> citizenship, age, fitness and driver's licence rules differ by agency, and the AFP offers an online eligibility tool.</li>
    <li><strong>Apply on official sites only:</strong> APSJobs, the AFP, Home Affairs, ASIO, ASD and Defence careers pages.</li>
    <li><strong>Write a targeted pitch</strong> that shows judgement, integrity, communication and teamwork with real examples.</li>
    <li><strong>Prepare for testing:</strong> expect online tests, interviews and, for AFP recruits, a fitness assessment.</li>
    <li><strong>Be ready for vetting:</strong> gather addresses, employment, travel and referee details for the past several years, and keep your application confidential.</li>
</ol>

<h2>Government vs Private Security Jobs</h2>

<p>Government security roles protect national interests: government people, information, sites, borders and systems. They need citizenship, a clearance and integrity screening, and they pay according to agency enterprise agreements. Private security guards protect businesses, events and property, and need a state security licence instead of a government clearance.</p>

<h2>Frequently Asked Questions</h2>

<h3>Which Australian Government agencies hire for security jobs?</h3>
<p>The main ones are the Australian Federal Police, the Australian Border Force and Department of Home Affairs, ASIO, the Australian Signals Directorate and the Department of Defence.</p>

<h3>How much does an AFP protective service officer earn?</h3>
<p>Salary starts at $82,499.88, including base salary, a 22% composite allowance and a Use of Force allowance, plus 15.4% superannuation and extra pay in remote locations.</p>

<h3>Do government security jobs require Australian citizenship?</h3>
<p>Almost always. The AFP, ASIO and ASD require citizenship, and security clearances are open only to Australian citizens unless an agency waives the requirement.</p>

<h3>What security clearance do I need?</h3>
<p>It depends on the role. Home Affairs requires at least a Baseline clearance for all staff, while ASIO requires TOP SECRET-Privileged Access.</p>

<h3>Can I get a security clearance before I apply for a job?</h3>
<p>No. The agency or employer that hires you sponsors the clearance during recruitment.</p>

<h3>Do I need a degree for a government security job?</h3>
<p>Not for all of them. AFP protective service officers need no degree, while graduate intelligence pathways such as the AGGP Intelligence Stream require a bachelor degree or higher.</p>

<h3>How long does recruitment take?</h3>
<p>The AFP's PSO process often takes 6 to 9 months, and ASD says its process can take 3 to 18 months because of suitability and clearance checks.</p>

<h3>Where do I apply for government security jobs?</h3>
<p>On APSJobs and on each agency's official careers site: AFP, Home Affairs, ASIO, ASD and Defence.</p>

<h2>People Also Search For</h2>

<h3>AFP protective service officer salary</h3>
<p>From $82,499.88 including allowances.</p>

<h3>Australian Border Force jobs</h3>
<p>Entry through the Border Force Officer Recruit Trainee program.</p>

<h3>ASIO jobs requirements</h3>
<p>Australian citizenship and a TOP SECRET-Privileged Access clearance.</p>

<h3>Baseline security clearance</h3>
<p>The entry-level clearance Home Affairs requires for all staff.</p>

<h3>ASD careers</h3>
<p>Cyber, intelligence and security roles; citizenship required.</p>

<h3>Defence security jobs</h3>
<p>Civilian APS roles in security advice, vetting and investigations.</p>

<h3>Government security jobs for permanent residents</h3>
<p>Rare; most need citizenship, so look at private security or other APS roles.</p>

<h3>Intelligence graduate program Australia</h3>
<p>The AGGP Intelligence Stream, managed by the Office of National Intelligence.</p>

<h2>More Job Guides</h2>

<p>Comparing security and law enforcement careers? These cover it:</p>

<ul>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; federal law enforcement careers in the US.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the cyber side of security work.</li>
    <li><a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> &mdash; another public safety career.</li>
    <li><a href="/blog/security-specialist-jobs-in-uae">Security Specialist Jobs in UAE</a> &mdash; security roles in the Gulf.</li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; visa routes for non-citizens.</li>
    <li><a href="/blog/emergency-dispatcher-jobs-in-usa">Emergency Dispatcher Jobs in USA</a> &mdash; 911 telecommunicator pay, experience rules, typing and CritiCall tests, and 2026 openings in Houston, NYC and LA County.</li>
    <li><a href="/blog/public-safety-jobs-in-canada">Public Safety Jobs in Canada</a> &mdash; CBSA border officer pay and rules, RCMP and CSC requirements, emergency management jobs and who can apply.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Vacancies, pay, eligibility rules and clearance requirements change. Confirm the current requirements in the official vacancy and on each agency's careers site before applying.</p>
HTML;
    }
}
