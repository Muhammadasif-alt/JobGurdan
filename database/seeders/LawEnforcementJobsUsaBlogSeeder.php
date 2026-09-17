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
 * "Law Enforcement Jobs in USA" — the overview of every law enforcement
 * career path: patrol, detective, transit police, fish and game warden,
 * federal agent and civilian roles. The police officer guide owns state pay
 * and non-citizen hiring, and the federal police guide owns federal pay and
 * age limits, so this page compares the paths and links to them.
 *
 * Corrections to the draft (checked against the BLS Occupational Outlook
 * Handbook, nypdrecruit.com, fbijobs.gov and dea.gov, September 2026):
 *
 * 1. The draft says NYPD's minimum appointment age is 21. NYPD lets
 *    candidates sit the exam at 17 and be appointed at 20 years and 6 months,
 *    and only people under 35 on the first day of the application period may
 *    be appointed.
 *
 * 2. The draft leaves NYPD's education rule vague. It is 24 college semester
 *    credits with a 2.0 index, or a high school diploma plus two years of
 *    honorable full-time US military service. Starting pay is $60,884 and
 *    total pay after 5 1/2 years is $126,410.
 *
 * 3. The draft says BLS academy training covers report writing and
 *    "supervised practical training". The BLS lists state and local law,
 *    constitutional law, civil rights and police ethics, plus supervised
 *    experience in patrol, traffic control, firearm use, self-defense, first
 *    aid and emergency response.
 *
 * 4. The draft says the BLS names communication, observation,
 *    decision-making and working under demanding conditions as important
 *    qualities. The BLS list is communication skills, empathy, good judgment,
 *    leadership skills, perceptiveness, physical stamina and physical
 *    strength.
 *
 * 5. The draft gives the FBI education rule loosely. For GL-10 entry it is a
 *    bachelor's degree and two years of full-time professional work, or an
 *    advanced degree and one year; fbijobs.gov says apply before the 38th
 *    birthday while the USAJOBS posting says before the 37th on appointment.
 *
 * 6. The draft has no growth split. BLS projects 3% for patrol and transit
 *    police, 0% for detectives and -6% for fish and game wardens, and says
 *    detectives typically begin as police officers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LawEnforcementJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-law-enforcement-jobs.html';

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
        $title = 'Law Enforcement Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Police and detectives earned a $77,310 median in May 2025, with about 60,100 openings a year. Patrol, detective, transit, game warden, FBI and DEA jobs compared, plus the NYPD, FBI and DEA age, degree and citizenship rules.',
                'content' => $content,
                'featured_image' => 'blogs/law-enforcement-jobs-in-usa.jpg',
                'tags' => 'law enforcement jobs usa, law enforcement careers, sheriff deputy jobs, detective jobs usa, fish and game warden jobs, transit police jobs, dea special agent requirements, nypd police officer requirements',
                'meta_title' => 'Law Enforcement Jobs in USA: Requirements and Pay',
                'meta_description' => 'Law enforcement jobs in the USA: patrol, detective, transit, game warden and federal roles, the $77,310 BLS median, and NYPD, FBI and DEA entry rules.',
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
            ['name' => 'US Police, Sheriff, Transit and Federal Law Enforcement Agencies (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'us-law-enforcement-aggregated']
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
                'position' => 'Law Enforcement Officer — Police Officer, Sheriff\'s Deputy, Detective, Transit Police, Fish and Game Warden and Federal Agent Roles, US Agencies',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time shift work, with paid overtime common',
                'language' => 'English',
                // Every city, county, state and federal agency sets its own
                // pay scale, so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Police, sheriff, detective, transit, game warden and federal agent roles in US agencies. Each agency sets its own age, education and citizenship rules.',
                'seo_keywords' => 'law enforcement jobs, police officer jobs, sheriff deputy jobs, detective jobs, federal agent jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>City police departments, county sheriff's offices, state police, transit and campus police, state wildlife agencies and federal agencies such as the FBI and DEA hire law enforcement officers across the United States.</p>

<h3>What the work involves</h3>
<p>Responding to emergency and non-emergency calls, patrolling, traffic stops, arrests, collecting evidence, writing detailed reports and testifying in court. Detectives and special agents investigate crimes, and fish and game wardens enforce hunting, fishing and boating laws.</p>

<h3>Common requirements</h3>
<ul>
    <li>At least a high school diploma or equivalent; some agencies want college credits or a degree</li>
    <li>A minimum age, usually 21 for police officers</li>
    <li>US citizenship for federal agencies and most states and cities</li>
    <li>A written exam, fitness test, medical and psychological screening, drug test and background investigation</li>
    <li>Completion of the agency's training academy</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, eligibility and hiring rules are set by each agency and by federal and state law &mdash; not by JobGader. Read the official job announcement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Law enforcement jobs in the USA include police officer, sheriff's deputy, detective, criminal investigator, transit and railroad police, fish and game warden and federal agent roles.</strong> The Bureau of Labor Statistics (BLS) counts about <strong>824,200 police and detective jobs</strong> in 2025, with a <strong>median wage of $77,310</strong> in May 2025. Most need at least a high school diploma, a minimum age (usually 21), background and medical screening and an academy, but every agency sets its own rules.</p>

<p>This guide compares the main career paths, their pay and outlook, and the real entry rules at the NYPD, FBI and DEA.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-law-enforcement-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128110; Browse Law Enforcement Jobs &rarr;
    </a>
</div>

<h2>What Are Law Enforcement Jobs?</h2>

<p>Law enforcement officers protect lives and property, respond to incidents, enforce laws and investigate crimes. The BLS splits police and detectives into four occupations:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Jobs in 2025</th>
            <th style="padding:10px;text-align:left;">Median pay, May 2025</th>
            <th style="padding:10px;text-align:left;">Growth 2025&ndash;35</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Police and sheriff's patrol officers</strong></td><td style="padding:10px;">693,200</td><td style="padding:10px;">$76,210</td><td style="padding:10px;">3%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Detectives and criminal investigators</strong></td><td style="padding:10px;">120,500</td><td style="padding:10px;">$93,790</td><td style="padding:10px;">0%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Fish and game wardens</strong></td><td style="padding:10px;">6,200</td><td style="padding:10px;">$74,060</td><td style="padding:10px;">-6%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Transit and railroad police</strong></td><td style="padding:10px;">4,400</td><td style="padding:10px;">$90,230</td><td style="padding:10px;">3%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All police and detectives</strong></td><td style="padding:10px;">824,200</td><td style="padding:10px;">$77,310</td><td style="padding:10px;">3%</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Patrol officers and sheriff's deputies</strong> are the most common type. They patrol, answer calls and have general law enforcement duties. Some later join special units such as narcotics, motorcycle or SWAT, usually after several years on patrol.</li>
    <li><strong>Detectives and criminal investigators</strong>, sometimes called agents or special agents, gather facts and evidence on crimes such as assaults, robberies and homicides.</li>
    <li><strong>Transit and railroad police</strong> patrol train yards, subway stations and other transportation hubs.</li>
    <li><strong>Fish and game wardens</strong> enforce fishing, hunting and boating laws, run search and rescue and investigate complaints and accidents.</li>
    <li><strong>Federal agents</strong> at agencies such as the FBI and DEA investigate crimes that break federal law or cross state lines.</li>
</ul>

<p>Correctional officers and 911 dispatchers work alongside police but are separate BLS occupations. Correctional officers and jailers had a median of $58,940 in May 2025; our <a href="/blog/emergency-dispatcher-jobs-in-usa">Emergency Dispatcher Jobs in USA</a> guide covers dispatchers.</p>

<h2>What Does a Law Enforcement Officer Do?</h2>

<p>According to the BLS, police officers, detectives and criminal investigators typically:</p>

<ul>
    <li>respond to emergency and non-emergency calls;</li>
    <li>patrol assigned areas, observing people and activities;</li>
    <li>conduct traffic stops and issue citations;</li>
    <li>search restricted-access databases for vehicle records and warrants;</li>
    <li>obtain and serve warrants for arrests and searches;</li>
    <li>arrest people suspected of committing crimes;</li>
    <li>collect and secure evidence from crime scenes;</li>
    <li>write detailed reports and fill out forms;</li>
    <li>prepare cases for legal proceedings and testify in court.</li>
</ul>

<p>Police and detectives are required by law to write detailed reports and keep meticulous records. The work can be physically demanding, stressful and dangerous, and some federal agencies, such as the FBI, require extensive travel at short notice.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/law-enforcement-jobs-in-usa-k9-unit.jpg" alt="A police K9 officer with a German shepherd beside a patrol car, looking across the river at a city skyline at sunset" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Special units such as K9 are usually open only after several years on patrol.</figcaption>
</figure>

<h2>Where Law Enforcement Officers Work</h2>

<p>The BLS says <strong>96% of police and detectives work in government</strong> (excluding state and local education and hospitals), and 3% work in educational services. The employers include:</p>

<ul>
    <li><strong>City police departments</strong>, such as the NYPD;</li>
    <li><strong>County sheriff's offices</strong>, which hire deputies;</li>
    <li><strong>State police and highway patrols</strong>, and state wildlife agencies that hire game wardens;</li>
    <li><strong>Transit, railroad and campus police</strong>;</li>
    <li><strong>Federal agencies</strong>, such as the FBI, DEA, US Marshals and Customs and Border Protection.</li>
</ul>

<p>Rules do not carry over from one agency to the next. For state-by-state pay and which states hire non-citizens, see <a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a>. For federal pay tables and age limits, see <a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a>.</p>

<h2>What Qualifications Do You Need?</h2>

<p>The BLS says education requirements range from a high school diploma to a college degree. Candidates <strong>usually must be at least 21</strong>, meet physical and personal standards, graduate from the agency's academy and complete on-the-job training. A felony conviction or drug use may disqualify a candidate. Three large employers show how much the rules differ:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Rule</th>
            <th style="padding:10px;text-align:left;">NYPD Police Officer</th>
            <th style="padding:10px;text-align:left;">FBI Special Agent</th>
            <th style="padding:10px;text-align:left;">DEA Special Agent</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Minimum age</strong></td><td style="padding:10px;">Exam at 17; appointed at <strong>20 years and 6 months</strong></td><td style="padding:10px;">23</td><td style="padding:10px;">21</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Maximum age</strong></td><td style="padding:10px;">Under 35 on the first day of the application period, with extra time for military service</td><td style="padding:10px;">Apply before your 38th birthday, with exceptions</td><td style="padding:10px;">No older than 36 at appointment, with exceptions</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Education</strong></td><td style="padding:10px;"><strong>24 college semester credits</strong> with a 2.0 index, or a high school diploma plus two years of honorable full-time US military service</td><td style="padding:10px;">Bachelor's degree and two years of full-time professional work, or an advanced degree and one year</td><td style="padding:10px;">Bachelor's degree with a 2.95 GPA, a master's or law degree, or other experience-based routes</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Citizenship</strong></td><td style="padding:10px;">US citizen at appointment</td><td style="padding:10px;">US citizen</td><td style="padding:10px;">US citizen</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Other</strong></td><td style="padding:10px;">Valid New York State driver license; live in NYC or Nassau, Westchester, Suffolk, Orange, Rockland or Putnam county</td><td style="padding:10px;">Valid driver's license, fitness test, Top Secret/SCI clearance</td><td style="padding:10px;">Valid US driver's license, willing to relocate anywhere in the US</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Training</strong></td><td style="padding:10px;">Six months at the Police Academy</td><td style="padding:10px;">18 weeks at Quantico</td><td style="padding:10px;">14-week Basic Agent Training Program at Quantico</td></tr>
    </tbody>
</table>
</div>

<p>The FBI's age rule appears differently in two places: its own site says apply before your 38th birthday, while the current USAJOBS posting says you must not have reached your 37th birthday on appointment. Check the posting you are applying to.</p>

<h2>Do You Need a College Degree?</h2>

<p><strong>Not for most police jobs.</strong> The BLS lists a high school diploma or equivalent as the minimum for police and detective applicants. Many entry-level applicants have some college, though, and some departments ask for it: the NYPD wants 24 college credits unless you have two years of military service.</p>

<p>A degree matters more in three places:</p>

<ul>
    <li><strong>Federal agents.</strong> The FBI and DEA special agent routes start from a bachelor's degree.</li>
    <li><strong>Fish and game wardens</strong> typically need a bachelor's degree in a field such as wildlife science, biology or natural resources, although Federal Wildlife Officers and some state wardens do not.</li>
    <li><strong>Promotion.</strong> A bachelor's degree may be required to reach lieutenant or higher.</li>
</ul>

<h2>Law Enforcement Jobs at Major U.S. Agencies</h2>

<h3>New York City Police Department (NYPD)</h3>

<p>The NYPD hires police officers through a written civil service exam, followed by medical and psychological exams, drug and alcohol screening, a background investigation and a Job Standard Test. Registration for the latest exam ran from 1 to 14 September 2026. Recruits are paid from the first day of the six-month academy: <strong>$60,884 to start</strong>, rising to <strong>$126,410</strong> in total salary after 5 1/2 years.</p>

<p>The NYPD also hires civilians, including <strong>Police Communications Technicians</strong>, <strong>Traffic Enforcement Agents</strong>, <strong>School Safety Agents</strong>, <strong>Evidence &amp; Property Control Specialists</strong>, Police Administrative Aides and School Crossing Guards, and runs a Police Cadet program. See <a href="https://www.nyc.gov/site/nypd/careers/careers.page" target="_blank" rel="noopener">NYPD Careers</a> and <a href="https://nypdrecruit.com" target="_blank" rel="noopener">NYPD Recruit</a>.</p>

<h3>Federal Bureau of Investigation (FBI)</h3>

<p>The FBI lists <strong>over 200 career types</strong>: special agents, intelligence analysts and linguists, STEM and business roles, FBI Police, forensics and security. Its hiring process runs from application and eligibility review through interviews and testing, a <strong>conditional job offer</strong>, a background investigation that includes a polygraph, and a final job offer. Special agent candidates also take a three-hour Phase I test, a fitness test and a panel interview. Apply at <a href="https://fbijobs.gov/" target="_blank" rel="noopener">FBI Jobs</a>.</p>

<h3>Drug Enforcement Administration (DEA)</h3>

<p>DEA careers include <strong>Special Agent</strong>, <strong>Diversion Investigator</strong>, <strong>Forensic Sciences</strong>, <strong>Intelligence Research Specialist</strong> and <strong>Professional and Administrative</strong> jobs, plus student and entry-level programs. DEA advertises vacancies through OPM's USA Staffing system and USAJOBS. Every applicant must pass a background investigation and drug test, and pre-employment checks may also include a polygraph and medical exam. See <a href="https://www.dea.gov/careers" target="_blank" rel="noopener">DEA Careers</a>.</p>

<h3>Local police and sheriff's departments</h3>

<p>Most law enforcement jobs are with cities, counties and states, and each writes its own rules on age, education, residency and citizenship. Use the official website of the department or sheriff's office you want to join, not a national checklist.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/law-enforcement-jobs-in-usa-capitol-patrol.jpg" alt="A female police officer in a tactical vest stands beside a patrol car near the US Capitol as other officers and a police helicopter patrol" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">City, county, state and federal agencies each set their own hiring rules.</figcaption>
</figure>

<h2>How Much Do Law Enforcement Jobs Pay?</h2>

<p>The BLS reports a <strong>median annual wage of $77,310</strong> for police and detectives in May 2025, against $50,980 for all workers. The lowest 10% earned less than $48,160 and the highest 10% more than $121,600. Detectives and criminal investigators have the highest median of the four occupations, at $93,790.</p>

<p>These are national figures, not a department's pay scale. Pay rises with seniority, and overtime is common: the NYPD figures above show how much a big-city salary can grow in five years. Federal criminal investigators also receive availability pay on top of base pay, explained in our <a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> guide.</p>

<h2>Is There Demand for Law Enforcement Workers?</h2>

<p>The BLS projects police and detective employment to grow <strong>3% from 2025 to 2035</strong>, about as fast as the average for all occupations, with about <strong>60,100 openings a year</strong>. Many openings will replace officers who retire or move to other occupations.</p>

<p>Growth is not even. Patrol and transit police are projected to grow 3%, detectives 0% and fish and game wardens <strong>-6%</strong>. The BLS also says demand will vary by location, driven largely by local and state budgets.</p>

<h2>What Training Is Required?</h2>

<p>Most officers attend an academy and then complete on-the-job training. The BLS says academy training includes classroom instruction in <strong>state and local laws, constitutional law, civil rights and police ethics</strong>, plus supervised experience in patrol, traffic control, firearm use, self-defense, first aid and emergency response.</p>

<p>Federal agents usually train at Quantico, Virginia, or at a Federal Law Enforcement Training Center. Detectives typically start as police officers, and officers usually become eligible for promotion to corporal, sergeant, lieutenant and captain after probation, through written exams and job performance.</p>

<h2>What Skills Help With Law Enforcement Jobs?</h2>

<p>The BLS names these as important qualities for police and detectives:</p>

<ul>
    <li><strong>Communication skills</strong>, to speak with people and describe incidents in writing;</li>
    <li><strong>Empathy</strong>, to understand different people's perspectives and help the public;</li>
    <li><strong>Good judgment</strong>, to find the best way to solve a range of problems;</li>
    <li><strong>Leadership skills</strong>, as a highly visible member of the community;</li>
    <li><strong>Perceptiveness</strong>, to anticipate people's reactions;</li>
    <li><strong>Physical stamina</strong>, to pass entry tests and keep up with the job, and <strong>physical strength</strong>, to apprehend suspects and help people in danger.</li>
</ul>

<p>Most police and detectives work full time with shift work, and paid overtime is common. FBI special agents must work at least 50 hours a week and are on call 24 hours a day.</p>

<h2>Can Foreign Workers Apply for Law Enforcement Jobs in the USA?</h2>

<p><strong>Rarely, and never on a work visa.</strong> The BLS says most states and local jurisdictions require US citizens, some do not, and <strong>federal officers must be US citizens</strong>. The NYPD requires US citizenship at appointment, the FBI requires it, and the DEA lists US citizenship as a condition of all DEA employment.</p>

<p>A few states, such as California and Illinois, accept some non-citizens who are authorized to work, but US firearms law bars most visa holders from carrying a gun. Our <a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> guide explains those rules. There is no US visa for coming to work as a police officer.</p>

<h2>How to Apply for Law Enforcement Jobs in the USA</h2>

<ol>
    <li><strong>Choose the path:</strong> city police, sheriff's office, state police, transit police, wildlife agency, federal agency or a civilian job.</li>
    <li><strong>Check the official rules</strong> for age, education, citizenship, residency, driver's license, fitness and background.</li>
    <li><strong>Watch the exam dates.</strong> Big departments such as the NYPD open exam registration for only a couple of weeks.</li>
    <li><strong>Prepare your application.</strong> Show customer service, security, military, community or report-writing experience, and answer every background question honestly.</li>
    <li><strong>Train for the tests:</strong> written exam, fitness test, interview, medical and psychological screening and, at federal agencies, a polygraph.</li>
    <li><strong>Complete the academy</strong> and field training before working on your own.</li>
</ol>

<h2>Related Law Enforcement Careers</h2>

<p>You do not have to carry a badge to work in law enforcement. Related careers include:</p>

<ul>
    <li><strong>911 dispatcher or police communications technician</strong>;</li>
    <li><strong>Intelligence analyst</strong> &mdash; see <a href="/blog/intelligence-analyst-jobs-in-usa">Intelligence Analyst Jobs in USA</a>;</li>
    <li><strong>Forensic specialist</strong> or DEA forensic chemist;</li>
    <li><strong>Diversion investigator</strong> at the DEA;</li>
    <li><strong>Evidence and property control, traffic enforcement and school safety</strong> jobs at city police departments;</li>
    <li><strong>Correctional officer or bailiff</strong>;</li>
    <li><strong>Administrative and technology</strong> roles inside police departments and federal agencies.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum education for a law enforcement job?</h3>
<p>A high school diploma or equivalent, according to the BLS. Some departments want college credits, such as the NYPD's 24 credits, and FBI and DEA special agents need a bachelor's degree.</p>

<h3>How old do you have to be to become a police officer?</h3>
<p>There is no single national age. The BLS says candidates usually must be at least 21. The NYPD appoints officers from 20 years and 6 months, the DEA from 21 and the FBI from 23.</p>

<h3>Can I apply for federal law enforcement without police experience?</h3>
<p>Yes, for some jobs. The FBI accepts special agents with a bachelor's degree and two years of full-time professional work, or an advanced degree and one year. Detectives in local departments, by contrast, typically start as police officers.</p>

<h3>Are law enforcement jobs full-time?</h3>
<p>Mostly. The BLS says most police and detectives work full time, shift work is needed to cover every hour, and paid overtime is common.</p>

<h3>How much do law enforcement officers make?</h3>
<p>The BLS median was $77,310 in May 2025: $76,210 for patrol officers, $93,790 for detectives, $90,230 for transit and railroad police and $74,060 for fish and game wardens.</p>

<h3>Which law enforcement job pays the most?</h3>
<p>Of the four BLS occupations, detectives and criminal investigators have the highest median, $93,790. Federal special agents also receive availability pay on top of base salary.</p>

<h3>Can non-citizens work in US law enforcement?</h3>
<p>Federal agencies, the NYPD and most states require US citizenship. A few states accept some work-authorized non-citizens, but most visa holders cannot legally carry a firearm.</p>

<h3>Where can I find legitimate law enforcement jobs?</h3>
<p>Use official city, county, state and federal career sites, such as NYPD Recruit, FBI Jobs, DEA Careers and USAJOBS. Check that the web address is the agency's own before you apply.</p>

<h2>People Also Search For</h2>

<h3>Sheriff deputy jobs</h3>
<p>County sheriff's offices hire deputies; the BLS counts them with police patrol officers.</p>

<h3>Detective requirements</h3>
<p>Detectives typically start as police officers and are promoted after experience.</p>

<h3>Fish and game warden jobs</h3>
<p>6,200 jobs, a $74,060 median and a projected 6% decline to 2035.</p>

<h3>Transit police jobs</h3>
<p>4,400 transit and railroad police jobs with a $90,230 median.</p>

<h3>NYPD police officer salary</h3>
<p>$60,884 to start and $126,410 in total salary after 5 1/2 years.</p>

<h3>FBI special agent requirements</h3>
<p>At least 23, a US citizen, a bachelor's degree and work experience.</p>

<h3>DEA special agent age limit</h3>
<p>21 to 36 at appointment, with exceptions for veterans and prior federal law enforcement.</p>

<h3>Law enforcement jobs without a degree</h3>
<p>Most police and sheriff's patrol jobs need only a high school diploma.</p>

<h2>More Job Guides</h2>

<p>Comparing public safety careers? These go deeper:</p>

<ul>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; state pay, the citizenship and firearms rules and what disqualifies applicants.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; FBI, CBP, ICE and US Marshals pay, availability pay and age limits.</li>
    <li><a href="/blog/emergency-dispatcher-jobs-in-usa">Emergency Dispatcher Jobs in USA</a> &mdash; 911 pay, typing tests and the civilian route into public safety.</li>
    <li><a href="/blog/intelligence-analyst-jobs-in-usa">Intelligence Analyst Jobs in USA</a> &mdash; the analyst side of federal security work.</li>
    <li><a href="/blog/public-safety-jobs-in-canada">Public Safety Jobs in Canada</a> &mdash; CBSA, RCMP, Correctional Service Canada and emergency management careers.</li>
    <li><a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> &mdash; federal and provincial corrections routes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the BLS Occupational Outlook Handbook, NYPD, FBI and DEA career pages. Pay, requirements and exam dates change and differ by agency. Always read the current official job announcement before applying.</p>
HTML;
    }
}
