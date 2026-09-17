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
 * "Public Safety Jobs in Canada" — the overview of federal public safety
 * careers: border services, corrections, the RCMP, Public Safety Canada and
 * emergency management. The correctional officer guide owns CSC and
 * provincial corrections in detail, so this page summarises CSC and links to
 * it.
 *
 * Corrections to the draft (checked against cbsa-asfc.gc.ca, canada.ca,
 * rcmp.ca, publicsafety.gc.ca, GC Jobs and Job Bank, September 2026):
 *
 * 1. The draft links Public Safety Canada careers to a canada.ca page that
 *    returns 404. The careers page is on publicsafety.gc.ca, and the
 *    department takes résumés by email.
 *
 * 2. The draft says CSC correctional officers need "relevant direct-interaction
 *    experience". CSC's correctional officer poster lists no required
 *    experience: it asks for a secondary school diploma or equivalent,
 *    Standard First Aid with CPR Level C and AED, a valid unrestricted driver's
 *    licence and Enhanced Reliability Status.
 *
 * 3. The draft names the CBSA's five prerequisites without the rules. Trainees
 *    must be 18 before the Officer Induction Training Program, with no
 *    maximum age; CBSA will not accept a mix of education and experience
 *    instead of a diploma or equivalent; and the licence must be unrestricted.
 *
 * 4. The draft says CBSA publishes pay without giving it. Trainees earn
 *    $80,344 to $89,462 (FB-02) and officers $86,915 to $103,079 (FB-03),
 *    with a $525-a-week tax-free allowance during training (3 weeks online
 *    and 15 weeks at Rigaud). CBSA's steps page still says 4 and 14 weeks.
 *
 * 5. The draft gives no RCMP rules. Police officers must be citizens or
 *    permanent residents with 1,095 days in Canada in the last five years,
 *    can apply at 18 but are hired at 19, and train 26 weeks at Depot on
 *    $1,000 a week.
 *
 * 6. The draft says the Government of Canada lists public safety beside
 *    national security, border services, policing and defence. The jobs page
 *    links only "Jobs in national security and defence", which then lists the
 *    RCMP, CBSA and Public Safety Canada.
 *
 * 7. The draft has no pay figures. Job Bank national medians are added.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PublicSafetyJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-public-safety-jobs.html';

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
        $title = 'Public Safety Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'CBSA trainees earn $80,344 to $89,462, RCMP cadets get $1,000 a week at Depot and CSC runs over 30 careers. Requirements, pay, training and who can apply for border, corrections, RCMP and emergency management jobs.',
                'content' => $content,
                'featured_image' => 'blogs/public-safety-jobs-in-canada.jpg',
                'tags' => 'public safety jobs canada, border services officer jobs, cbsa careers, rcmp careers, correctional service canada jobs, emergency management jobs canada, public safety canada careers, federal government jobs canada',
                'meta_title' => 'Public Safety Jobs in Canada: Requirements and Pay',
                'meta_description' => 'Public safety jobs in Canada: CBSA border officer pay and rules, RCMP and CSC requirements, emergency management roles, Job Bank wages and who can apply.',
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
            ['name' => 'Canadian Federal Public Safety Agencies (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'ca-public-safety-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Public Safety Officer — Border Services, Corrections, RCMP and Emergency Management Roles, Government of Canada',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time shifts on rotation, including weekends and statutory holidays for frontline roles',
                'language' => 'English or French',
                // Each agency and classification has its own pay scale, so no
                // single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Border services, corrections, RCMP and emergency management roles with Canadian federal agencies. Security screening and eligibility rules apply.',
                'seo_keywords' => 'public safety jobs canada, border services officer jobs, rcmp jobs, correctional officer jobs canada, emergency management jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>The Canada Border Services Agency, Correctional Service of Canada, RCMP, Public Safety Canada and other government employers hire for frontline and professional public safety roles across Canada.</p>

<h3>What the work involves</h3>
<p>Border services officers examine travellers and goods at ports of entry, correctional officers keep federal institutions safe, RCMP officers police communities, and analysts and program officers work on emergency management, crime prevention and national security.</p>

<h3>Common requirements</h3>
<ul>
    <li>A secondary school diploma or equivalent for CBSA, CSC and RCMP frontline jobs</li>
    <li>A valid, unrestricted driver's licence for frontline jobs</li>
    <li>Security screening, such as Enhanced Reliability Status or a Secret clearance</li>
    <li>Medical, psychological and fitness standards for officer roles</li>
    <li>Eligibility rules that differ by agency: the RCMP requires citizenship or permanent residence</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, eligibility and selection steps are set by each agency and collective agreement &mdash; not by JobGader. Read the official job poster before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Public safety jobs in Canada include border services officers, correctional officers, RCMP police officers, emergency management staff and the policy, intelligence and analyst roles that support them.</strong> Most federal jobs are posted on the Government of Canada Jobs portal. Many frontline jobs need only a <strong>secondary school diploma or equivalent</strong>, but they add security screening, medical and fitness standards and paid training. A CBSA officer trainee earns <strong>$80,344 to $89,462</strong> a year.</p>

<p>This guide covers the main employers, their requirements and pay, who can apply and how to get started.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-public-safety-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128737; Browse Public Safety Jobs in Canada &rarr;
    </a>
</div>

<h2>What Are Public Safety Jobs in Canada?</h2>

<p>Public safety covers work that protects people, communities and Canada's borders. Public Safety Canada describes its role as coordinating "an integrated approach to emergency management, law enforcement, corrections, crime prevention and national and border security". Its portfolio has <strong>five agencies</strong>, each a large employer:</p>

<ul>
    <li><strong>Canada Border Services Agency (CBSA)</strong>;</li>
    <li><strong>Correctional Service of Canada (CSC)</strong>;</li>
    <li><strong>Royal Canadian Mounted Police (RCMP)</strong>;</li>
    <li><strong>Canadian Security Intelligence Service (CSIS)</strong>;</li>
    <li><strong>Parole Board of Canada</strong>.</li>
</ul>

<p>Provinces and municipalities hire too, for police services, fire departments, paramedic services, 911 centres and provincial corrections, each with its own process. The common career areas are border services, corrections, policing and civilian police support, emergency management, intelligence and security analysis, public safety policy and emergency communications.</p>

<h2>Public Safety Jobs and Pay at a Glance</h2>

<p>Job Bank publishes national hourly wages for each occupation group (updated 19 November 2025):</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job (NOC)</th>
            <th style="padding:10px;text-align:left;">Main employers</th>
            <th style="padding:10px;text-align:left;">Median hourly wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Police officer</strong> (42100)</td><td style="padding:10px;">RCMP, provincial and municipal police</td><td style="padding:10px;">$50.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Border services officer</strong> (43203)</td><td style="padding:10px;">CBSA</td><td style="padding:10px;">$40.10</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Probation and parole officer</strong> (41311)</td><td style="padding:10px;">CSC and provinces</td><td style="padding:10px;">$40.35</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Correctional service officer</strong> (43201)</td><td style="padding:10px;">CSC and provinces</td><td style="padding:10px;">$36.15</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Emergency management analyst</strong> (41400)</td><td style="padding:10px;">Federal, provincial and municipal governments</td><td style="padding:10px;">$43.27</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Emergency management coordinator</strong>, government (40019)</td><td style="padding:10px;">Federal, provincial and municipal governments</td><td style="padding:10px;">$60.51</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>911 or police dispatcher</strong> (14404)</td><td style="padding:10px;">Police services and 911 centres</td><td style="padding:10px;">$28.00</td></tr>
    </tbody>
</table>
</div>

<p>These are medians for whole occupation groups across Canada. Federal jobs pay on their own scales, shown in the agency sections below. For police officers, Job Bank's outlook for 2024&ndash;2033 is a <strong>moderate risk of shortage</strong>; the other groups above are expected to be in balance.</p>

<h2>What Qualifications Do You Need?</h2>

<p><strong>There is no single qualification for every public safety job.</strong> Frontline officer jobs usually ask for:</p>

<ul>
    <li>a secondary school diploma or equivalent;</li>
    <li>a valid, unrestricted driver's licence;</li>
    <li>security screening, from Enhanced Reliability Status up to a Secret clearance or higher;</li>
    <li>medical, psychological and physical standards;</li>
    <li>willingness to work shifts, weekends and statutory holidays, and often to relocate;</li>
    <li>English or French, with some jobs requiring both.</li>
</ul>

<p>Policy, intelligence, scientific and analytical jobs usually ask for a college diploma or university degree. Always compare yourself with the <strong>essential qualifications</strong> on the job poster.</p>

<h2>Border Services Jobs at the CBSA</h2>

<p>Border services officers work at <strong>1,200 points of service</strong> across Canada, including land borders, international airports, marine terminals, rail ports and postal facilities. CBSA hires them through the <strong>Officer Trainee Developmental Program</strong>, and applicants must meet all five requirements when they apply:</p>

<ol>
    <li><strong>Age:</strong> at least <strong>18</strong> before starting the Officer Induction Training Program. There is <strong>no maximum age</strong>.</li>
    <li><strong>Eligibility:</strong> persons residing in Canada, and Canadian citizens and permanent residents abroad.</li>
    <li><strong>Location:</strong> willing to work anywhere in Canada.</li>
    <li><strong>Education:</strong> a secondary school diploma or equivalent, such as a PSC test score or provincial equivalency. CBSA will not consider a combination of education, training and experience instead.</li>
    <li><strong>Licence:</strong> a valid, <strong>unrestricted</strong> driver's licence, such as a Class 5, 5F or G.</li>
</ol>

<p>CBSA accepts applications year-round through a continuous inventory on GC Jobs, and the current officer trainee poster stays open until 24 June 2027. Selection includes an entrance exam, interview, document checks, a psychological assessment, a medical assessment, Canadian firearms courses and <strong>Enhanced Reliability Status plus Secret clearance</strong>. CBSA says the process can take up to 18 months.</p>

<h3>CBSA training and pay</h3>

<ul>
    <li><strong>Training:</strong> 3 weeks online, then <strong>15 weeks in residence</strong> at the Canada Border Services College in Rigaud, Quebec, with a <strong>tax-free allowance of $525 a week</strong> plus lodging and meals.</li>
    <li><strong>Officer trainee (FB-02):</strong> <strong>$80,344 to $89,462</strong> a year during at least 12 months of development at a port of entry.</li>
    <li><strong>Border services officer (FB-03):</strong> <strong>$86,915 to $103,079</strong>, plus a bilingual bonus in some bilingual positions.</li>
</ul>

<p>CBSA also hires in intelligence, targeting, investigations and trade compliance, and runs a <strong>Student Border Services Officer</strong> program. Apply through <a href="https://www.cbsa-asfc.gc.ca/job-emploi/menu-eng.html" target="_blank" rel="noopener">CBSA Careers</a>.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/public-safety-jobs-in-canada-first-responders.jpg" alt="A firefighter, police officer, paramedic and another uniformed officer stand together on a waterfront with a Canadian flag and a rescue helicopter overhead" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Federal agencies, provinces and municipalities all hire for public safety roles.</figcaption>
</figure>

<h2>Public Safety Jobs at Correctional Service Canada</h2>

<p>CSC offers <strong>over 30 careers</strong>, including correctional officers, health care professionals and various trades. Its institutions operate <strong>24 hours a day, 7 days a week</strong>, so correctional officers must work shifts, weekends and statutory holidays. CSC assesses education, knowledge, experience, competencies, abilities and personal suitability, depending on the job.</p>

<p>CSC's most recent correctional officer poster asked for:</p>

<ul>
    <li>a <strong>secondary school diploma</strong>, a satisfactory PSC test score or a provincial equivalency;</li>
    <li><strong>Standard First Aid with CPR Level C and AED</strong>;</li>
    <li>a valid, unrestricted driver's licence before appointment;</li>
    <li>medical and psychological standards and <strong>Enhanced Reliability Status</strong>.</li>
</ul>

<p>Work experience is not an essential qualification; experience with ethnocultural or Indigenous communities is listed as an asset. Recruits take the three-stage Correctional Training Program, with an allowance of <strong>$400 a week</strong>, up to $5,600, during the in-person stage. Our <a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> guide covers the full process, CX pay and provincial routes. See <a href="https://www.canada.ca/en/correctional-service/services/you-csc/working-csc.html" target="_blank" rel="noopener">Correctional Service Canada Careers</a>.</p>

<h2>Public Safety Jobs With the RCMP</h2>

<p>The RCMP hires police officers, plus two groups of non-police staff: <strong>civilian members</strong> and <strong>public service employees</strong>. To become an RCMP police officer you must:</p>

<ul>
    <li>be a <strong>Canadian citizen or permanent resident</strong>; permanent residents need <strong>1,095 days in Canada</strong> in the past five years;</li>
    <li>be 18 to apply and <strong>19 to be hired</strong>, with no maximum age;</li>
    <li>be proficient in English or French;</li>
    <li>hold a valid, unrestricted Canadian driver's licence and a Canadian secondary school diploma or equivalent;</li>
    <li>have Standard First Aid with Level C CPR, and meet the health, vision, hearing and physical standards;</li>
    <li>be willing to live at Depot in Regina for <strong>26 weeks</strong> and relocate anywhere in Canada.</li>
</ul>

<p>Cadets receive <strong>$1,000 a week</strong> at Depot, up to $26,000. The RCMP's latest published constable pay, as of 1 April 2024, runs from <strong>$71,191</strong> at step 1 to <strong>$115,350</strong> at step 5. See <a href="https://rcmp.ca/en/careers" target="_blank" rel="noopener">RCMP Careers</a>.</p>

<h2>Public Safety Jobs With Public Safety Canada</h2>

<p>Public Safety Canada is the department that coordinates the portfolio. Frontline officers are hired by the agencies, while the department's own work covers <strong>emergency management, law enforcement, corrections, crime prevention and national and border security</strong> policy and programs. Its careers page invites r&eacute;sum&eacute;s at recruitment-recrutement@ps-sp.gc.ca. See <a href="https://www.publicsafety.gc.ca/cnt/bt/crrs/index-en.aspx" target="_blank" rel="noopener">Public Safety Canada Careers</a>.</p>

<p>The Government of Canada's <a href="https://www.canada.ca/en/services/defence/jobs.html" target="_blank" rel="noopener">Jobs in national security and defence</a> page links to careers at the RCMP, CBSA and Public Safety Canada, as well as CSIS, the Canadian Armed Forces and the Communications Security Establishment.</p>

<h2>Emergency Management Jobs</h2>

<p>Emergency management staff help governments and organisations prepare for, respond to and recover from emergencies. Common titles are emergency management coordinator, emergency planning officer, preparedness specialist, business continuity specialist and emergency management analyst.</p>

<p>Job Bank places an <strong>emergency management analyst</strong> in NOC 41400, with a median of $43.27 an hour, and an <strong>emergency management coordinator in government services</strong> in NOC 40019, a management group with a median of $60.51. Check each poster for the education and experience it asks for.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/public-safety-jobs-in-canada-emergency-services.jpg" alt="A police officer, firefighter and paramedic stand in front of patrol cars and an ambulance with a Canadian flag and city skyline behind them" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Police, fire and paramedic services are hired locally, while border and federal corrections jobs are federal.</figcaption>
</figure>

<h2>Can You Get a Public Safety Job Without a University Degree?</h2>

<p><strong>Yes.</strong> The three largest frontline routes ask for a secondary school diploma or equivalent:</p>

<ul>
    <li><strong>CBSA officer trainee:</strong> a diploma or an accepted equivalent;</li>
    <li><strong>CSC correctional officer:</strong> a diploma, a PSC test score or a provincial equivalency;</li>
    <li><strong>RCMP police officer:</strong> a Canadian secondary school diploma or equivalent.</li>
</ul>

<p>Policy, analytical, scientific and professional jobs are the ones that usually need a college diploma or degree.</p>

<h2>Can International Applicants Apply for Public Safety Jobs in Canada?</h2>

<p><strong>Only if they already live in Canada, or are Canadian citizens or permanent residents living abroad.</strong></p>

<ul>
    <li><strong>CBSA and CSC</strong> accept persons residing in Canada, and Canadian citizens and permanent residents abroad. CBSA gives <strong>hiring preference</strong> to eligible veterans, Canadian citizens and permanent residents.</li>
    <li><strong>The RCMP</strong> requires Canadian citizenship or permanent residence, with 1,095 days in Canada in the past five years for permanent residents.</li>
</ul>

<p>So someone outside Canada who is not a citizen or permanent resident cannot apply to these processes. If you are planning a move first, see <a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a>.</p>

<h2>Do Public Safety Jobs Require Security Screening?</h2>

<p><strong>Yes, for almost all of them.</strong> CBSA officer trainees need <strong>Enhanced Reliability Status plus Secret clearance</strong>, and CSC correctional officers need Enhanced Reliability Status. The RCMP notes that permanent residents may not qualify for some roles that need Top Secret clearance. Answer every screening question accurately and completely.</p>

<h2>What Skills Are Useful for Public Safety Careers?</h2>

<ul>
    <li>clear spoken and written communication, including report writing;</li>
    <li>sound judgment and decision-making under pressure;</li>
    <li>integrity and respect for others;</li>
    <li>teamwork and working with people from different communities;</li>
    <li>attention to detail and following procedures;</li>
    <li>physical fitness for officer roles, and data or analytical skills for policy and intelligence roles.</li>
</ul>

<h2>How to Apply for Public Safety Jobs in Canada</h2>

<ol>
    <li><strong>Choose a career area:</strong> border services, corrections, policing, emergency management, policy or intelligence.</li>
    <li><strong>Search official sites:</strong> <a href="https://www.canada.ca/en/services/jobs/opportunities/government.html" target="_blank" rel="noopener">Government of Canada Jobs</a> for federal jobs, and provincial and municipal career pages for local police, fire and paramedic jobs.</li>
    <li><strong>Check the essential qualifications:</strong> education, eligibility, language, licence, security, medical and relocation rules.</li>
    <li><strong>Tailor your r&eacute;sum&eacute;</strong> to the poster, showing customer service, communication, community, emergency or government experience.</li>
    <li><strong>Prepare for selection:</strong> exams, interviews, reference checks, medical and psychological assessments and security screening.</li>
    <li><strong>Plan for training:</strong> 15 weeks at Rigaud for CBSA, 26 weeks at Depot for the RCMP, or CSC's Correctional Training Program.</li>
</ol>

<h2>Public Safety Jobs for Students and New Graduates</h2>

<p>Students can get experience before applying for a permanent job. CBSA hires <strong>Student Border Services Officers</strong> and co-op students, and recruits through the <strong>Federal Student Work Experience Program (FSWEP)</strong>. CSC offers FSWEP jobs, co-op placements and a Research Affiliate Program.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the easiest public safety job to get in Canada?</h3>
<p>No job is easiest for everyone, but CBSA, CSC and RCMP frontline jobs ask for a secondary school diploma or equivalent rather than a degree. They still include exams, medical checks and security screening.</p>

<h3>Can I work in public safety with only high school education?</h3>
<p>Yes. CBSA officer trainees, CSC correctional officers and RCMP police officers can all qualify with a secondary school diploma or equivalent.</p>

<h3>How much does a border services officer make in Canada?</h3>
<p>CBSA pays officer trainees $80,344 to $89,462 (FB-02) and border services officers $86,915 to $103,079 (FB-03). Job Bank's national median for the occupation is $40.10 an hour.</p>

<h3>What is the age limit for CBSA and the RCMP?</h3>
<p>Neither has a maximum age. CBSA trainees must be 18 before training starts, and the RCMP accepts applications at 18 but hires police officers from 19.</p>

<h3>Can permanent residents apply for public safety jobs?</h3>
<p>Yes. CBSA and CSC accept permanent residents, and the RCMP accepts permanent residents who have lived in Canada for 1,095 days in the past five years.</p>

<h3>Is the CBSA hiring in 2026?</h3>
<p>Yes. CBSA accepts officer trainee applications year-round through a continuous inventory, and the current poster is open until 24 June 2027.</p>

<h3>Are public safety jobs available across Canada?</h3>
<p>Yes. CBSA officers work at 1,200 points of service, and CBSA and RCMP recruits must be willing to work anywhere in Canada.</p>

<h3>Where can I find legitimate public safety jobs in Canada?</h3>
<p>Use the Government of Canada Jobs portal and the official CBSA, CSC, RCMP and Public Safety Canada career pages, plus provincial and municipal government sites for local jobs.</p>

<h2>People Also Search For</h2>

<h3>Border services officer requirements</h3>
<p>18 before training, a diploma or equivalent, an unrestricted licence and willingness to work anywhere.</p>

<h3>CBSA salary</h3>
<p>$80,344 to $89,462 as a trainee and up to $103,079 as an FB-03 officer.</p>

<h3>RCMP requirements</h3>
<p>Citizen or permanent resident, 19 to be hired, a diploma and 26 weeks at Depot.</p>

<h3>RCMP cadet pay</h3>
<p>$1,000 a week during training, up to $26,000.</p>

<h3>Correctional officer jobs in Canada</h3>
<p>CSC and provincial corrections hire; our full guide compares the routes.</p>

<h3>Emergency management jobs in Canada</h3>
<p>Analyst and coordinator roles in federal, provincial and municipal governments.</p>

<h3>Public Safety Canada careers</h3>
<p>Policy, program and emergency management jobs posted on GC Jobs.</p>

<h3>FSWEP public safety jobs</h3>
<p>CBSA and CSC both hire students through FSWEP.</p>

<h2>More Job Guides</h2>

<p>Comparing public safety careers? These go deeper:</p>

<ul>
    <li><a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> &mdash; federal CSC versus provincial jails, training and pay.</li>
    <li><a href="/blog/law-enforcement-jobs-in-usa">Law Enforcement Jobs in USA</a> &mdash; patrol, detective, transit and federal paths compared, with BLS pay.</li>
    <li><a href="/blog/government-security-jobs-in-australia">Government Security Jobs in Australia</a> &mdash; AFP, Border Force and security clearance jobs.</li>
    <li><a href="/blog/emergency-dispatcher-jobs-in-usa">Emergency Dispatcher Jobs in USA</a> &mdash; 911 pay, typing tests and the civilian route into public safety.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the sectors that hire and the routes into Canada.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA, language rules and routes to permanent residence.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using CBSA, Correctional Service Canada, RCMP, Public Safety Canada, Government of Canada Jobs and Job Bank information. Pay, requirements and job posters change. Always read the current official job poster before applying.</p>
HTML;
    }
}
