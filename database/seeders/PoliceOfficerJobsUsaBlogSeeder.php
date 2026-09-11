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
 * "Police Officer Jobs in USA" — local, state and federal law enforcement.
 * Most readers are outside the United States, so citizenship, firearms and
 * age rules get as much space as pay.
 *
 * Corrections to the draft:
 *
 * 1. Its pay bands are low. BLS puts the May 2025 median at $76,210 for patrol
 *    officers and $93,790 for detectives; state government patrol officers,
 *    the nearest measure of state troopers, have a median of $88,400, above
 *    the draft's $85,000 ceiling; federal detectives and criminal
 *    investigators have a median of $119,500.
 *
 * 2. It says "U.S. citizenship or permanent residency, depending on the
 *    agency". Federal officers must be citizens, and so must NYPD and Texas
 *    officers (Texas excepts only certain veterans), while California since
 *    1 January 2026 and Illinois since 1 January 2024 accept non-citizens
 *    with federal work authorization.
 *
 * 3. It omits the firearms rule. 18 U.S.C. 922(g)(5) bars nonimmigrant visa
 *    holders from possessing firearms, and USCIS guidance of 26 September 2025
 *    says DACA recipients may not either.
 *
 * 4. It says every officer completes a state-certified academy. Federal agents
 *    train at federal academies, and state certificates do not transfer
 *    automatically.
 *
 * 5. It lists the top five states by number of officers as if they were the
 *    best-paying. Texas and Florida pay below the national mean.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PoliceOfficerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-police-officer-jobs.html';

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
        $title = 'Police Officer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The patrol officer median is $76,210, not $45,000 to $60,000. Federal agencies, the NYPD and Texas require citizenship, California and Illinois accept work-authorized non-citizens, and federal law bars visa holders from carrying a gun.',
                'content' => $content,
                'featured_image' => 'blogs/police-officer-jobs-in-usa.jpg',
                'tags' => 'police officer jobs usa, police officer salary, how to become a police officer, state trooper jobs, police academy requirements, fbi special agent requirements, can a green card holder be a police officer, police jobs for non citizens, nypd hiring age',
                'meta_title' => 'Police Officer Jobs in USA 2026: Pay, Age and Citizenship',
                'meta_description' => 'Police officer jobs in the USA: the BLS patrol median of $76,210, which states accept non-citizens, why visa holders cannot carry a gun, and age rules.',
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
            ['name' => 'US Local, State & Federal Law Enforcement Agencies (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'us-police-aggregated']
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
                'position' => 'Police Officer — Local, State and Federal Agencies, USA',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts, including nights, weekends and holidays, with overtime and court appearances',
                'language' => 'English',
                // Pay is set by each department and state, from a lowest-state
                // mean near $47,000 to a California mean near $113,000, so no
                // single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Police officer, deputy, trooper and federal agent roles across the US. Citizenship, age and firearms eligibility rules differ by agency.',
                'seo_keywords' => 'police officer jobs usa, police officer salary, state trooper jobs, police academy requirements, police jobs for non citizens',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>City police departments, county sheriff's offices, state police and highway patrols, transit and campus police, and federal agencies recruit officers across the United States throughout the year. Hiring runs in cycles of written exams, physical tests and academy intakes.</p>

<h3>What the work involves</h3>
<p>Patrolling an assigned area, responding to calls, enforcing laws, making arrests, writing reports, giving evidence in court and working with the community. Detectives investigate crimes, and federal agents investigate violations of federal law.</p>

<h3>Requirements</h3>
<ul>
    <li>Eligibility set by the agency: federal agencies, the NYPD and Texas require a US citizen, while California and Illinois accept non-citizens with federal work authorization</li>
    <li>The ability to lawfully possess a <strong>firearm</strong> under federal law, which rules out nonimmigrant visa holders</li>
    <li>A minimum age that is usually 21, and lower in some states and departments</li>
    <li>A written exam, physical fitness test, background investigation, and psychological and medical evaluations</li>
    <li>Completion of the state academy, or a federal academy for federal agents</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured medians.</strong> $76,210 for patrol officers and $93,790 for detectives in May 2025, according to the BLS</li>
    <li><strong>Location.</strong> State mean wages run from about $47,000 to about $113,000</li>
    <li><strong>Federal agents.</strong> Criminal investigators receive availability pay of 25 per cent of basic pay</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the agency's citizenship, age and education rules before you prepare for the exam.</strong> They differ between departments in the same state.</p>

<p><strong>Note:</strong> pay, eligibility and hiring rules are set by each agency and by federal and state law &mdash; not by JobGader. Confirm the details on the official recruitment page before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Police departments across the United States are recruiting, from big-city forces and county sheriff's offices to state police and federal agencies. It is a career with a pension, a clear promotion ladder and real public service. It is also one the common advice gets wrong on three things that decide whether you should apply at all: what it pays, who is allowed to apply, and whether the badge travels.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-police-officer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128110; Browse Police Officer Jobs in USA &rarr;
    </a>
</div>

<h2>Police Pay Is Higher Than the Guides Say</h2>

<p>Guides put entry-level officers at <strong>$45,000 to $60,000</strong>, experienced officers and detectives at $60,000 to $80,000, state troopers at $55,000 to $85,000 and federal agents at $70,000 to $100,000 or more. The Bureau of Labor Statistics measured something noticeably higher in May 2025:</p>

<ul>
    <li><strong>Police and sheriff's patrol officers:</strong> a median of <strong>$76,210</strong> a year</li>
    <li><strong>Detectives and criminal investigators:</strong> a median of <strong>$93,790</strong></li>
    <li><strong>Transit and railroad police:</strong> a median of $90,230</li>
</ul>

<p>The spread matters too. The lowest-paid tenth of patrol officers earned less than <strong>$47,510</strong>, and the highest-paid tenth more than $115,120. So the guides' entry range sits mostly at the very bottom of the market. A large department shows the shape: the NYPD starts officers on $55,942 and pays $109,352 after five and a half years.</p>

<p>The other two bands are low as well. Patrol officers employed by <strong>state governments</strong> &mdash; the nearest BLS measure of state troopers &mdash; have a median of <strong>$88,400</strong>, above the guides' $85,000 ceiling. <strong>Detectives and criminal investigators in the federal government</strong> have a median of <strong>$119,500</strong>.</p>

<h2>The Top States for Jobs Are Not the Top States for Pay</h2>

<p>Guides name California, Texas, New York, Florida and Illinois as the top states. That is accurate &mdash; for the number of officers. The BLS figures for patrol officers:</p>

<ul>
    <li><strong>California:</strong> 65,940 officers, mean wage $112,980</li>
    <li><strong>Texas:</strong> 63,100 officers, mean wage <strong>$76,160</strong></li>
    <li><strong>New York:</strong> 53,470 officers, mean wage $87,110</li>
    <li><strong>Florida:</strong> 43,630 officers, mean wage <strong>$75,320</strong></li>
    <li><strong>Illinois:</strong> 29,180 officers, mean wage $92,450</li>
</ul>

<p>The national mean for patrol officers is <strong>$79,200</strong>, so Texas and Florida hire in large numbers but pay below it. The best-paying states are California, Washington ($104,140), Alaska ($101,770), New Jersey ($93,700) and Delaware ($93,210). The lowest are Mississippi ($47,010), Arkansas ($49,850) and Louisiana ($51,250).</p>

<h2>Citizenship: Not "Citizenship or Permanent Residency"</h2>

<p>Guides say agencies require "U.S. citizenship or permanent residency, depending on the agency". The real picture is sharper at both ends. The BLS puts it plainly: most states and local jurisdictions require candidates to be US citizens, some do not, and <strong>federal officers must be US citizens</strong>.</p>

<ul>
    <li><strong>Federal agencies</strong> &mdash; the FBI, DEA, US Marshals and others &mdash; require US citizenship.</li>
    <li><strong>New York.</strong> The NYPD requires US citizenship at appointment, and so does the New York State Police. A green card is not enough.</li>
    <li><strong>Texas.</strong> State licensing rules require a US citizen. The only exception is a permanent resident who is an honorably discharged US military veteran with at least two years of service and has applied for citizenship.</li>
    <li><strong>California.</strong> Since <strong>1 January 2026</strong>, state law requires peace officers to be <strong>legally authorized to work in the United States</strong> under federal law, rather than citizens.</li>
    <li><strong>Illinois.</strong> Since <strong>1 January 2024</strong>, a non-citizen legally authorized to work in the US may apply to be a police officer or deputy, provided they are also allowed to possess a firearm under federal law. Each city decides whether to hire them.</li>
</ul>

<p>So a green card holder is ruled out in New York and in federal agencies, and is ruled in by California and Illinois &mdash; which also accept other work-authorized non-citizens. Check the individual agency, not a national rule.</p>

<h2>A Visa Holder Cannot Legally Carry a Gun</h2>

<p>This is the rule that matters most to readers outside the US, and the guides do not mention it. Under <strong>18 U.S.C. 922(g)(5)</strong>, anyone admitted to the United States on a <strong>nonimmigrant visa</strong> &mdash; a work visa, a student visa or a visitor visa &mdash; is generally prohibited from possessing firearms or ammunition.</p>

<p>The exceptions in the law cover hunters with a valid licence, accredited foreign government representatives, officials designated by the State Department and foreign police officers of friendly governments on official business. None of them is written for a visa holder who wants to join a US police department, and every police academy requires firearms qualification.</p>

<p>Recent guidance narrows the options further. On <strong>26 September 2025</strong>, USCIS issued guidance stating that DACA recipients may not lawfully possess or receive firearms &mdash; which undercuts the DACA routes some states opened.</p>

<p>The practical route for someone abroad is therefore long: permanent residence first, then an agency that accepts permanent residents or work-authorized non-citizens, or US citizenship for every agency. There is no visa that brings you to the US to work as a police officer.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/police-officer-jobs-in-usa-patrol.jpg"
         alt="A uniformed police officer standing beside a patrol car with a city skyline behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Age Limits Run in Both Directions</h2>

<p>Guides say the minimum age is typically 21. That is the usual rule, but several large employers set it differently, and federal agencies also set a maximum:</p>

<ul>
    <li><strong>Texas:</strong> 21, or 18 with an associate degree, or <strong>60 semester hours</strong> of college, or an honorable discharge after at least two years of active military service.</li>
    <li><strong>California:</strong> at least 21 at appointment.</li>
    <li><strong>NYPD:</strong> you can sit the exam at 17 and be appointed at <strong>20 years and 6 months</strong>, and must be under 35 at the start of the application period, with military service deductible.</li>
    <li><strong>Federal agencies:</strong> a maximum entry age, usually before your 37th birthday. The US Marshals take applicants from 21 to 36. The limit exists because federal law enforcement officers face mandatory retirement at 57 once they have 20 years of service.</li>
</ul>

<p>The FBI's own pages give its age limit differently in different places, so check the current posting before you plan around it.</p>

<h2>Federal Agents Do Not Go Through a State Academy</h2>

<p>Guides say all police officers must complete a state-certified academy. State and local officers do. Federal agents train at federal academies instead, such as the FBI Academy at Quantico.</p>

<p>The FBI special agent route is also more demanding than a local department's:</p>

<ul>
    <li>US citizenship, a bachelor's degree and a valid driver's licence</li>
    <li>Professional experience, or an advanced degree, depending on the grade you enter at</li>
    <li>A physical fitness test of four events &mdash; pull-ups or chin-ups, a 300-metre sprint, push-ups and a 1.5-mile run &mdash; that <strong>no longer includes sit-ups</strong>, so older preparation guides are out of date</li>
</ul>

<p>A recent FBI posting paid <strong>$93,749 to $133,200</strong> in Washington, DC, including locality pay and <strong>availability pay of 25 per cent</strong>. That availability pay goes only to criminal investigators who average at least two hours of unscheduled duty a day, not to every federal officer.</p>

<h2>A Police Certificate Does Not Travel Between States</h2>

<p>Each state certifies its own officers, so moving means requalifying. California shows how it works: an officer from another state needs a Basic Course Waiver, which requires at least a year of full-time police work elsewhere and enough previous training to reach California's 664-hour standard. The waiver lasts three years, after which a <strong>160-hour</strong> requalification course applies. Other states run their own supplementary courses and exams.</p>

<h2>What Disqualifies Applicants</h2>

<ul>
    <li><strong>Felony convictions.</strong> Federal law bars anyone convicted of a crime punishable by more than a year in prison from possessing firearms, which ends a police application.</li>
    <li><strong>Domestic violence.</strong> The Lautenberg Amendment, <strong>18 U.S.C. 922(g)(9)</strong>, bars anyone convicted of a misdemeanor crime of domestic violence from possessing firearms &mdash; and unlike most firearms rules, it applies to officers on duty too.</li>
    <li><strong>Department standards.</strong> Texas bars anyone convicted of an offense above a Class B misdemeanor, or a Class B misdemeanor within ten years, or any family violence offense. The NYPD bars anyone convicted of a felony or a domestic violence misdemeanor.</li>
    <li><strong>The hiring process itself.</strong> Background investigations, psychological and medical evaluations, and fitness tests remove many applicants. Honesty on the background forms matters more than a perfect record.</li>
</ul>

<h2>How the Hiring Process Works</h2>

<ol>
    <li><strong>Choose the agency</strong> and read its own rules on citizenship, age, education and residency.</li>
    <li><strong>Pass the written exam</strong>, which many large departments run in scheduled windows.</li>
    <li><strong>Pass the physical fitness test</strong>. Start training months before, not weeks.</li>
    <li><strong>Complete the background investigation</strong>, often with a polygraph, plus psychological and medical evaluations.</li>
    <li><strong>Attend the academy</strong>, then a period of field training with an experienced officer.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much do police officers make in the USA?</h3>
<p>The BLS median was $76,210 for patrol officers and $93,790 for detectives in May 2025. State government patrol officers had a median of $88,400, and federal detectives and criminal investigators $119,500.</p>

<h3>Can a green card holder become a police officer in the USA?</h3>
<p>In some places. Federal agencies, the NYPD and Texas require US citizenship, with a narrow veterans' exception in Texas. California, since 1 January 2026, and Illinois, since 1 January 2024, accept non-citizens with federal work authorization.</p>

<h3>Can I become a US police officer on a work or student visa?</h3>
<p>No, in practice. Under 18 U.S.C. 922(g)(5), nonimmigrant visa holders are generally barred from possessing firearms, and every police academy requires firearms qualification.</p>

<h3>What is the minimum age to become a police officer?</h3>
<p>Usually 21. Texas allows 18 with an associate degree, 60 semester hours of college or military service, and the NYPD appoints officers from 20 years and 6 months.</p>

<h3>Do federal agents attend a police academy?</h3>
<p>They attend federal academies rather than state ones. FBI special agents, for example, train at the FBI Academy at Quantico.</p>

<h3>What is on the FBI physical fitness test?</h3>
<p>Four events: pull-ups or chin-ups, a 300-metre sprint, push-ups and a 1.5-mile run. Sit-ups are no longer part of it.</p>

<h3>Does a police certificate transfer to another state?</h3>
<p>Not automatically. Each state certifies its own officers. California, for example, requires out-of-state officers to obtain a Basic Course Waiver, and a 160-hour requalification course once the waiver expires.</p>

<h3>What disqualifies you from becoming a police officer?</h3>
<p>A felony conviction, a misdemeanor domestic violence conviction under the Lautenberg Amendment, or failing the background, psychological or medical evaluation. Departments add their own standards on top.</p>

<h2>People Also Search For</h2>

<h3>State trooper salary</h3>
<p>State government patrol officers had a BLS median of $88,400 in May 2025.</p>

<h3>NYPD hiring age</h3>
<p>Exam at 17, appointment from 20 years and 6 months, and under 35 when the application period opens.</p>

<h3>How to become an FBI agent</h3>
<p>US citizenship, a bachelor's degree, professional experience and a four-event fitness test, before the agency's maximum entry age.</p>

<h3>Police jobs for non-citizens</h3>
<p>California and Illinois accept work-authorized non-citizens. Federal agencies, the NYPD and Texas require citizenship.</p>

<h3>Highest paying states for police officers</h3>
<p>California, Washington, Alaska, New Jersey and Delaware, by mean wage for patrol officers.</p>

<h3>Detective salary in the USA</h3>
<p>A BLS median of $93,790, and $119,500 for detectives and criminal investigators in the federal government.</p>

<h3>Police academy length in California</h3>
<p>The Regular Basic Course sets a minimum of 664 hours of training.</p>

<h3>Lateral police officer jobs</h3>
<p>For experienced officers moving departments. Moving states usually means a waiver or requalification course.</p>

<h2>More Job Guides</h2>

<p>Comparing security and public service careers? These cover them:</p>

<ul>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the investigative side of technology, and the clearance rules in full.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; licensed security work in the Gulf, and the licence that only covers one emirate.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; security work under the Saudi system.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the visa routes that do exist for work in the US.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; another route into American work, and what it really pays.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; the most common first job in America, priced state by state.</li>
    <li><a href="/blog/government-jobs-in-pakistan">Government Jobs in Pakistan</a> &mdash; public service careers at home, and how the tests work.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; FBI, CBP, ICE and US Marshals jobs, their 2026 pay and age limits.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Hiring standards, pay, citizenship rules, firearms law and immigration guidance change and differ between agencies and states. Confirm the current position with the agency's official recruitment page, the relevant state and federal authorities and an immigration lawyer before applying.</p>
HTML;
    }
}
