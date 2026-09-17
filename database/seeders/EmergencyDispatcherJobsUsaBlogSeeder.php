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
 * "Emergency Dispatcher Jobs in USA" — a guide to 911 call-taker, telecommunicator
 * and dispatcher work in US city and county emergency communications centers.
 * The Police Officer and Federal Police guides cover sworn officers and the
 * Customer Service guide covers private call centers, so this one stays on
 * civilian 911 roles: pay, entry rules, tests, training and who is hiring.
 *
 * Corrections and clarifications to the draft (checked against the BLS
 * Occupational Outlook Handbook and OEWS May 2025, the City of Houston's 9-1-1
 * Telecommunicator posting of 5 August 2026, NYC DCAS Notice of Examination 6329,
 * Los Angeles County's Fire Dispatcher I, Fire Dispatcher II and Public Response
 * Dispatcher I postings, Seattle CARE's 911 Communications Center page and
 * Denver 9-1-1's call taker and police dispatcher postings, September 2026):
 *
 * 1. The draft implies Houston hires without experience because technical
 *    skills "can be learned during on-the-job training". The same posting
 *    requires one year in a high-volume telephone, customer service or high
 *    stress environment (a Houston Community College Public Safety
 *    Telecommunicator certificate can substitute) and a TCOLE telecommunicator
 *    licence within a year of hire.
 *
 * 2. The draft calls the Los Angeles County Fire Dispatcher I posting a 2026
 *    recruitment. It opened on 31 July 2025 and filing closed in August 2025.
 *    The Fire Dispatcher II pay in its table comes from a 2021 promotional
 *    posting open only to County Fire employees, so it is dropped from the pay
 *    table. The open 2026 County posting is the Sheriff's Public Response
 *    Dispatcher I, at the same $54,648 to $73,644.
 *
 * 3. The draft says Seattle's average answer time is under 15 seconds for
 *    94.14% of calls. Seattle reports that 94.14% of 911 calls were answered in
 *    under 15 seconds against a 90% national standard.
 *
 * 4. The draft leaves out NYC's entry rules. Exam 6329 requires a high school
 *    diploma plus a year of clerical or public-contact work, 30 college credits
 *    or two years of military service; the $68 application window closed on
 *    17 July 2026, the minimum salary is $51,790 and probation lasts 18 months.
 *
 * 5. The draft says Denver dispatcher roles can require prior dispatch
 *    experience without separating its two routes. Denver's entry-level call
 *    taker academy (June 2026: $29.00 an hour, 11 weeks of classroom and
 *    13 weeks of on-the-job training) is separate from its police dispatcher
 *    job, which asks for a year of 911, dispatch or first-responder experience.
 *
 * 6. The draft has no national pay or outlook figures. BLS data are added:
 *    a $53,040 median in May 2025, 105,600 jobs and about 9,000 openings a year.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EmergencyDispatcherJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-911-dispatcher-jobs.html';

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
        $title = 'Emergency Dispatcher Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => '911 dispatchers earned a $53,040 median in May 2025. Houston pays $20.91 to $23.23 an hour but wants a year of phone or customer service work, and LA County pays $54,648 to $73,644. Tests, training and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/emergency-dispatcher-jobs-in-usa.jpg',
                'tags' => 'emergency dispatcher jobs usa, 911 dispatcher jobs, 911 telecommunicator jobs, public safety telecommunicator, police dispatcher jobs, fire dispatcher jobs, 911 dispatcher salary, criticall test',
                'meta_title' => 'Emergency Dispatcher Jobs in USA: Pay and How to Apply',
                'meta_description' => 'Emergency dispatcher jobs in the USA: 911 pay ($53,040 median), experience rules, typing and CritiCall tests, and 2026 openings in Houston, NYC and LA.',
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
            ['name' => 'US City and County 911 Emergency Communications Centers (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'us-emergency-dispatch-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Emergency Dispatcher — 911 Call Taker, Telecommunicator and Police, Fire and EMS Dispatcher Roles, US Emergency Communications Centers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time rotating shifts, including nights, weekends and holidays',
                'language' => 'English',
                // Each city or county sets its own pay scale, so no single band
                // is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => '911 call taker, telecommunicator and dispatcher roles in US city and county emergency communications centers. Typing, skills tests and background checks apply.',
                'seo_keywords' => 'emergency dispatcher jobs, 911 dispatcher jobs, 911 telecommunicator jobs, police dispatcher jobs, fire dispatcher jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US city and county emergency communications centers hire 911 call takers, telecommunicators and dispatchers to answer emergency and non-emergency calls, work out what help is needed and send police, fire or medical responders.</p>

<h3>Common requirements</h3>
<ul>
    <li>A high school diploma or GED</li>
    <li>A typing test, often 30 words per minute, and a dispatcher skills or written test</li>
    <li>Some employers want a year of customer service, clerical, call center or dispatch experience</li>
    <li>A background check, drug test and, at many centers, hearing and psychological screening</li>
    <li>Availability for rotating shifts, nights, weekends and holidays</li>
    <li>Authorization to work in the United States</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, hiring and training are set by each city, county or agency &mdash; not by JobGader. Read the official job announcement for the current requirements and deadline.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Emergency dispatcher jobs in the USA involve answering 911 calls, getting the caller's location and details, deciding how urgent the call is and sending police, fire or medical help.</strong> Most are with city and county governments. The Bureau of Labor Statistics (BLS) reports a <strong>median wage of $53,040 a year</strong> in May 2025, and most jobs need a <strong>high school diploma or GED</strong>, a typing test and a background check. Many employers also ask for a year of phone, customer service or clerical experience.</p>

<p>This guide covers what the job involves, pay, entry rules, tests and training, and where major US cities are hiring in 2026.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-911-dispatcher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128222; Browse 911 Dispatcher Jobs &rarr;
    </a>
</div>

<h2>What Does an Emergency Dispatcher Do?</h2>

<p>The BLS groups these workers as <strong>public safety telecommunicators</strong>, including 911 operators and fire dispatchers. They answer emergency and non-emergency calls and send help to the people who need it. Typical duties include:</p>

<ul>
    <li>answering 911 and non-emergency calls, and at many centers, texts to 911;</li>
    <li>getting the caller's location and what is happening;</li>
    <li>deciding the type and priority of the emergency;</li>
    <li>entering details into a computer-aided dispatch (CAD) system;</li>
    <li>sending police, fire or EMS units and relaying updates by radio;</li>
    <li>giving medical instructions over the phone before help arrives, where the center uses Emergency Medical Dispatch;</li>
    <li>keeping accurate records of every call.</li>
</ul>

<p>Employers use different titles for the same work:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job title</th>
            <th style="padding:10px;text-align:left;">Example employer</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>9-1-1 Telecommunicator</strong></td><td style="padding:10px;">City of Houston, Houston Emergency Center</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Police Communications Technician</strong></td><td style="padding:10px;">New York City Police Department</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Public Response Dispatcher</strong></td><td style="padding:10px;">Los Angeles County Sheriff's Department</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Fire Dispatcher</strong></td><td style="padding:10px;">Los Angeles County Fire Department</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Call Taker / Police Dispatcher</strong></td><td style="padding:10px;">Denver 9-1-1 Emergency Communications</td></tr>
    </tbody>
</table>
</div>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/emergency-dispatcher-jobs-in-usa-call-taker.jpg" alt="A 911 dispatcher wearing a headset works at a multi-screen CAD console as police cars and a fire truck wait outside" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Dispatchers enter call details into a computer-aided dispatch system and send the right units.</figcaption>
</figure>

<h2>Call Taker vs Dispatcher</h2>

<p>Some centers split the work. A <strong>call taker</strong> answers the 911 call and gathers the information, then a <strong>dispatcher</strong> sends units and handles radio traffic with officers, firefighters or paramedics. Other centers combine both jobs.</p>

<ul>
    <li><strong>Combined:</strong> NYC Police Communications Technicians serve as 911 call takers and radio dispatchers of police resources.</li>
    <li><strong>Split:</strong> Seattle's call takers work with precinct-focused dispatchers. Denver hires entry-level call takers through an academy and fills police dispatcher jobs separately.</li>
</ul>

<h2>How Much Do Emergency Dispatchers Make?</h2>

<p>The BLS reports a <strong>median annual wage of $53,040</strong> ($25.50 an hour) for public safety telecommunicators in May 2025. The lowest 10% earned less than $37,320 and the highest 10% more than $79,830. Local government employs 79% of them at a median of $54,280, and state government pays a median of $60,840.</p>

<p>Pay in recent official postings:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Employer and job</th>
            <th style="padding:10px;text-align:left;">Published pay</th>
            <th style="padding:10px;text-align:left;">Posting status</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">City of Houston &mdash; 9-1-1 Telecommunicator</td><td style="padding:10px;"><strong>$20.91&ndash;$23.23/hour</strong></td><td style="padding:10px;">Opened 5 Aug 2026, continuous</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Los Angeles County Sheriff &mdash; Public Response Dispatcher I</td><td style="padding:10px;"><strong>$54,648&ndash;$73,644/year</strong></td><td style="padding:10px;">Filing from 10 Sep 2026, continuous</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Los Angeles County Fire &mdash; Fire Dispatcher I</td><td style="padding:10px;"><strong>$54,648&ndash;$73,644/year</strong></td><td style="padding:10px;">2025 posting, filing closed</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">NYPD &mdash; Police Communications Technician</td><td style="padding:10px;"><strong>$51,790 minimum/year</strong></td><td style="padding:10px;">Exam 6329, applications closed 17 Jul 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Denver 9-1-1 &mdash; Call Taker</td><td style="padding:10px;"><strong>$29.00/hour</strong></td><td style="padding:10px;">June 2026 academy</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">BLS Occupational Outlook Handbook and OEWS, May 2025; official City of Houston, Los Angeles County, NYC DCAS and Denver job postings, checked September 2026. Pay changes, so check the live announcement.</p>
</div>

<h2>What Qualifications Do You Need?</h2>

<p>The BLS lists a <strong>high school diploma or equivalent</strong> as the typical entry-level education, followed by moderate-term on-the-job training. The Houston, NYC and Los Angeles County postings all ask for a diploma or GED. Experience rules differ much more:</p>

<ul>
    <li><strong>Houston:</strong> one year in a high-volume telephone, customer service or high stress environment. A Public Safety Telecommunicator certificate from Houston Community College, or an equivalent program, can substitute. Houston says the technical skills are learned during on-the-job training, but the experience rule still applies.</li>
    <li><strong>New York City:</strong> a high school diploma plus <strong>one</strong> of these: a year of full-time clerical, typing or secretarial work; a year of full-time work dealing with the public; 30 college semester credits; or two years of active US military duty with an honorable discharge.</li>
    <li><strong>Los Angeles County:</strong> the entry-level Public Response Dispatcher I and Fire Dispatcher I jobs ask for high school graduation or its equivalent and typing at 30 net words per minute, with no set experience.</li>
    <li><strong>Denver:</strong> police dispatcher postings have asked for a year as a 911 operator or emergency dispatcher, a year of general dispatching, or a year as a paramedic, firefighter or police officer. Denver also trains entry-level call takers through its academy.</li>
</ul>

<p>Houston highly prefers Spanish speakers. If you were educated outside the US, Los Angeles County and NYC both require your diploma to be evaluated for US equivalence.</p>

<p>Useful skills include fast, accurate typing, active listening, clear speech, map reading, multitasking and staying calm on difficult calls. The Los Angeles County Fire posting asks dispatchers to work the phone, radio and computer at the same time.</p>

<h2>What Tests Are Required?</h2>

<p>The BLS says candidates usually pass an exam and a typing test, and may also face a background check, lie detector and drug tests, and hearing and vision tests. Examples:</p>

<ul>
    <li><strong>Houston:</strong> a 30 wpm keyboard test and a <strong>CritiCall</strong> skills assessment, then a criminal background check, hearing test, psychological evaluation, interview and pre-employment drug test.</li>
    <li><strong>NYC:</strong> a computer-based multiple-choice civil service exam with a 70% pass mark, testing reasoning, memory, reading and writing. Medical, psychological and drug screening follow.</li>
    <li><strong>Los Angeles County Sheriff:</strong> an online 30 wpm typing test, then the <strong>Entry Level Dispatcher Selection Battery</strong>, which tests verbal, reasoning, memory and perceptual abilities.</li>
    <li><strong>Los Angeles County Fire:</strong> a typing test, a multiple-choice test and a performance test covering data entry, multitasking, listening, map reading and prioritization, followed by a background check and medical exam.</li>
</ul>

<h2>Training and Certification</h2>

<p>The BLS notes that many states and localities require certification, such as <strong>Emergency Medical Dispatcher (EMD)</strong>. Training is long and paid, and failing it can cost you the job:</p>

<ul>
    <li><strong>Los Angeles County Fire:</strong> about <strong>10 months</strong> of training, with EMD certification after appointment and a one-year probation.</li>
    <li><strong>Denver:</strong> 11 weeks of paid classroom training and 13 weeks of paid on-the-job training, with CPR and EMD certification required by the end of the academy.</li>
    <li><strong>NYC:</strong> an <strong>18-month probation</strong>. You must pass a 911 call-taker course and a radio dispatcher course, and failing them leads to termination.</li>
    <li><strong>Houston:</strong> a Texas Commission on Law Enforcement (TCOLE) telecommunicator training program within one year, and an active TCOLE licence for as long as you work there.</li>
    <li><strong>Los Angeles County Sheriff:</strong> the California POST Public Safety Dispatcher Course and the Sheriff's own training before final appointment.</li>
</ul>

<h2>Do Emergency Dispatchers Work Nights and Weekends?</h2>

<p><strong>Yes.</strong> The BLS says most work full time, often in 8- to 12-hour shifts, including evenings, weekends and holidays.</p>

<ul>
    <li><strong>Houston</strong> runs 24/7 with 40-hour weeks on rotating shifts. New full-time staff must work any shift for the first two years, and overtime is mandatory during critical incidents.</li>
    <li><strong>Seattle's</strong> 911 Communications Center is staffed 24 hours a day, 365 days a year.</li>
    <li><strong>NYC</strong> Police Communications Technicians work tours around the clock, including weekends and holidays.</li>
    <li><strong>Los Angeles County Fire</strong> dispatchers may be recalled for up to 36 continuous hours (with sleep breaks) and may not leave the center during a shift except in an emergency.</li>
</ul>

<h2>Emergency Dispatcher Jobs With Major US Employers</h2>

<p>Most 911 centers are run by cities and counties, so hiring happens on government career sites rather than through recruiters.</p>

<h3>1. City of Houston &mdash; 9-1-1 Telecommunicator</h3>
<p>The Houston Emergency Center answers 911 calls for police, fire and EMS, decides the nature and priority of each emergency and transfers calls to the right agency. The 2026 posting pays <strong>$20.91&ndash;$23.23 an hour</strong>, is full time and accepts applications from all interested persons. Apply at <a href="https://www.governmentjobs.com/careers/houston" target="_blank" rel="noopener">City of Houston jobs</a>.</p>

<h3>2. New York City Police Department &mdash; Police Communications Technician</h3>
<p>NYPD Police Communications Technicians are 911 call takers and radio dispatchers, and they review photos, videos and audio sent through Text to 911. Hiring runs through civil service exam 6329: applications (fee $68) closed on 17 July 2026, and testing began on 8 September 2026. City residency is not required. Watch for the next exam on <a href="https://www.nyc.gov/examsforjobs" target="_blank" rel="noopener">NYC Exams for Jobs</a> and the <a href="https://www.nyc.gov/site/nypd/careers/civilians/police-communications-technicians.page" target="_blank" rel="noopener">NYPD careers page</a>.</p>

<h3>3. Los Angeles County &mdash; Sheriff and Fire Dispatchers</h3>
<p>The Sheriff's Department is filling <strong>Public Response Dispatcher I</strong> jobs at <strong>$54,648&ndash;$73,644</strong>, with filing open from 10 September 2026 until enough candidates apply. The Fire Department's <strong>Fire Dispatcher I</strong> exam, at the same pay, last opened in 2025 and capped filing at the first 1,000 applications. Fire Dispatcher II is a promotion open only to County Fire employees with a year as Fire Dispatcher I. Search <a href="https://www.governmentjobs.com/careers/lacounty" target="_blank" rel="noopener">Los Angeles County jobs</a>.</p>

<h3>4. Seattle CARE Department &mdash; 911 Communications Center</h3>
<p>Seattle's Community Assisted Response and Engagement (CARE) Department runs the city's 911 Communications Center. It answers 911 calls, texts to 911 and the non-emergency line, and sends police, firefighters, medics and Community Crisis Responders. Its call takers can translate nearly 300 languages. In 2025 the center handled <strong>675,801 emergency calls</strong> (1,852 a day) and <strong>176,573 non-emergency calls</strong>, and answered <strong>94.14% of 911 calls in under 15 seconds</strong>, against a national standard of 90%. See <a href="https://www.seattle.gov/care/jobs" target="_blank" rel="noopener">Seattle CARE jobs</a>.</p>

<h3>5. City and County of Denver &mdash; Denver 9-1-1</h3>
<p>Denver 9-1-1 works with Denver Police, Denver Fire and Denver Health Paramedics. It trains new call takers in academy classes; the June 2026 academy paid <strong>$29.00 an hour</strong>. Experienced dispatchers can apply for police dispatcher jobs. Check <a href="https://denver.wd1.myworkdayjobs.com/CCD-denver-department-of-safety" target="_blank" rel="noopener">Denver Department of Safety jobs</a> for the next academy.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/emergency-dispatcher-jobs-in-usa-dispatch-center.jpg" alt="Telecommunicators with headsets at dispatch consoles showing city maps and traffic cameras in a 911 emergency communications center" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Local government employs 79% of public safety telecommunicators.</figcaption>
</figure>

<h2>Job Outlook</h2>

<p>The BLS counted <strong>105,600 public safety telecommunicator jobs</strong> in 2025 and projects <strong>4% growth</strong> from 2025 to 2035, about as fast as average. It expects about <strong>9,000 openings a year</strong>, mostly to replace workers who change jobs or retire.</p>

<h2>Can Foreign Workers Apply?</h2>

<p>Only if you are already <strong>authorized to work in the United States</strong>. NYC's notice says that under the Immigration Reform and Control Act of 1986 you must prove your identity and your right to work in the US before you are hired. None of the postings in this guide mention visa sponsorship. Government 911 jobs also involve criminal background checks, and Houston's requires federally mandated security clearances where needed.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Search official city and county career sites</strong> for "911 telecommunicator", "call taker", "public safety dispatcher", "emergency communications operator" and "police communications technician".</li>
    <li><strong>Check the experience rule.</strong> If the posting asks for a year of customer service, clerical or call center work, make sure your resume shows it clearly with dates and hours.</li>
    <li><strong>Practice typing</strong> until you can pass 30 net words per minute, and practice dispatcher skills tests such as CritiCall.</li>
    <li><strong>Prepare for screening:</strong> criminal background, drug test, and at many centers hearing and psychological evaluations. Answer every question honestly.</li>
    <li><strong>Plan for training.</strong> Expect months of paid classroom and on-the-job training, and certifications such as EMD or your state's telecommunicator licence.</li>
    <li><strong>Watch deadlines.</strong> Some exams, such as NYC's, open only for a few weeks, and some filings stop after a set number of applications.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum education for a 911 dispatcher?</h3>
<p>A high school diploma or GED. The BLS lists this as the typical entry-level education, and Houston, NYC and Los Angeles County all require it.</p>

<h3>Do I need experience to become a 911 dispatcher?</h3>
<p>It depends on the employer. Houston wants a year in a high-volume telephone, customer service or high stress job. NYC wants a year of clerical or public-contact work, 30 college credits or two years of military service. Los Angeles County's entry-level dispatcher jobs set no experience requirement.</p>

<h3>How much do emergency dispatchers make?</h3>
<p>The BLS median was $53,040 a year, or $25.50 an hour, in May 2025. Houston pays $20.91 to $23.23 an hour, and Los Angeles County pays $54,648 to $73,644 a year.</p>

<h3>What tests do 911 dispatchers take?</h3>
<p>Usually a typing test, often 30 words per minute, plus a dispatcher skills or written test such as CritiCall, the Entry Level Dispatcher Selection Battery or a civil service exam. Background, drug, hearing and psychological checks often follow.</p>

<h3>How long is 911 dispatcher training?</h3>
<p>Months. Los Angeles County Fire trains for about 10 months, Denver's academy runs 11 weeks in class plus 13 weeks on the job, and NYC has an 18-month probation.</p>

<h3>Do emergency dispatchers work nights and weekends?</h3>
<p>Yes. 911 centers run 24 hours a day, so rotating shifts that include nights, weekends and holidays are normal, and overtime can be mandatory.</p>

<h3>Can foreign workers apply for 911 dispatcher jobs?</h3>
<p>Only with existing US work authorization. Employers such as NYC require proof of your right to work before hiring, and none of the postings reviewed offer visa sponsorship.</p>

<h3>Where can I find legitimate emergency dispatcher jobs?</h3>
<p>On official city and county career sites, such as City of Houston jobs, NYC Exams for Jobs, Los Angeles County jobs, Seattle CARE jobs and Denver's Department of Safety jobs.</p>

<h2>People Also Search For</h2>

<h3>911 dispatcher jobs near me</h3>
<p>Check your city and county career site; local government employs 79% of dispatchers.</p>

<h3>911 dispatcher salary</h3>
<p>$53,040 median in May 2025.</p>

<h3>911 dispatcher requirements</h3>
<p>A high school diploma or GED, a typing test and a background check.</p>

<h3>CritiCall test</h3>
<p>A dispatcher skills assessment used by employers such as Houston.</p>

<h3>Police dispatcher jobs</h3>
<p>Often need a year of 911 or dispatch experience.</p>

<h3>Fire dispatcher jobs</h3>
<p>Los Angeles County Fire trains new dispatchers for about 10 months.</p>

<h3>911 call taker jobs</h3>
<p>The entry-level route in centers that split call taking and dispatch.</p>

<h3>Emergency Medical Dispatcher certification</h3>
<p>Required by many states and centers, often during training.</p>

<h2>More Job Guides</h2>

<p>Comparing public safety and phone-based careers? These cover it:</p>

<ul>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; the sworn officer route in state and local departments.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; FBI, CBP, ICE and US Marshals jobs and their pay.</li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; the phone experience many 911 centers ask for.</li>
    <li><a href="/blog/government-security-jobs-in-australia">Government Security Jobs in Australia</a> &mdash; public safety and security careers in Australia.</li>
    <li><a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> &mdash; another public safety career.</li>
    <li><a href="/blog/law-enforcement-jobs-in-usa">Law Enforcement Jobs in USA</a> &mdash; patrol, detective, transit, game warden and federal paths compared, with BLS pay and NYPD, FBI and DEA entry rules.</li>
    <li><a href="/blog/public-safety-jobs-in-canada">Public Safety Jobs in Canada</a> &mdash; CBSA border officer pay and rules, RCMP and CSC requirements, emergency management jobs and who can apply.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Pay, requirements, exams and application dates change and differ by city, county and agency. Always read the current official job announcement before applying.</p>
HTML;
    }
}
