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
 * "Federal Police Jobs in USA" — uniformed officers and special agents at the
 * FBI, CBP, ICE, US Marshals and other federal agencies. The police officer
 * guide covers state and local departments and non-citizen hiring, and this
 * one owns federal pay tables, availability pay, age limits and retirement.
 *
 * Corrections to the draft:
 *
 * 1. Its entry band starts at $45,000. The 2026 law enforcement base rate for
 *    GS-5 is $42,919, and with the lowest locality payment (17.06%) no GS-5
 *    officer earns less than $50,241; covered agencies pay $51,632.
 *
 * 2. It says nothing about the 2026 raise. General Schedule staff got 1%, but
 *    OPM gave most federal law enforcement officers 3.8% under Executive
 *    Order 14368.
 *
 * 3. Its FBI and DEA band of $80,000 to $120,000 leaves out availability pay.
 *    Criminal investigators receive 25% on top of basic pay, and the FBI's
 *    posting runs from $103,236 to $197,200.
 *
 * 4. It gives one age range of 21 to 37. The FBI takes applications until the
 *    38th birthday, CBP until the 40th, the DEA and US Marshals appoint up to
 *    36, and ICE removed its maximum age in August 2025.
 *
 * 5. It implies all agencies train at FLETC and that the Park Police patrol
 *    national parks. FBI agents train at Quantico, and Park Police officers
 *    are based in Washington DC, New York City and San Francisco.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FederalPoliceJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-federal-police-jobs.html';

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
        $title = 'Federal Police Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'No GS-5 federal officer earns less than $50,241 with locality in 2026, most law enforcement staff got 3.8% rather than 1%, special agents add 25% availability pay, and age limits run from 36 to none at ICE.',
                'content' => $content,
                'featured_image' => 'blogs/federal-police-jobs-in-usa.jpg',
                'tags' => 'federal police jobs usa, federal law enforcement jobs, fbi special agent salary, cbp officer salary, ice officer jobs, us marshals jobs, leo special base rates 2026, law enforcement availability pay, federal law enforcement age limit',
                'meta_title' => 'Federal Police Jobs in USA 2026: Pay, Age Limits, Agencies',
                'meta_description' => 'Federal police jobs in the USA: 2026 GS and LEO pay with locality, agency age limits from 36 to none at ICE, FLETC training, and why citizenship is required.',
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
            ['name' => 'US Federal Law Enforcement Agencies (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'us-federal-le-aggregated']
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
                'position' => 'Federal Police Officer and Special Agent — FBI, CBP, ICE, US Marshals and Other Agencies',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work, overtime and unscheduled duty, with travel and relocation common',
                'language' => 'English',
                // Federal pay depends on grade, step, locality and whether the
                // agency is covered by the 2026 law enforcement special rates,
                // so no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Federal law enforcement officer and special agent roles at the FBI, CBP, ICE, US Marshals and other agencies. US citizenship required.',
                'seo_keywords' => 'federal police jobs, federal law enforcement jobs, fbi special agent jobs, cbp officer jobs, us marshals jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Federal agencies including the FBI, Customs and Border Protection, Immigration and Customs Enforcement, the US Marshals Service, the DEA and the Transportation Security Administration hire uniformed officers and special agents across the United States.</p>

<h3>What the work involves</h3>
<p>Depending on the agency: protecting federal buildings and people, inspecting travellers and cargo at ports of entry, apprehending fugitives, providing court security, flying as an air marshal, or investigating federal crimes as a special agent.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>US citizenship</strong></li>
    <li>An agency age limit, from appointment before 37 at the DEA and US Marshals to no maximum age at ICE</li>
    <li>A background investigation, medical and fitness tests, and often a polygraph</li>
    <li>A degree or qualifying work experience; FBI special agents need a bachelor's degree and two years of professional experience</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Entry level.</strong> A GS-5 law enforcement officer earns at least $50,241 with locality in 2026</li>
    <li><strong>Special agents.</strong> Criminal investigators add 25% availability pay on top of basic pay</li>
    <li><strong>Retirement.</strong> Special law enforcement retirement at 50 with 20 years of service, or at any age with 25</li>
</ul>

<p><strong>Note:</strong> pay, eligibility and hiring rules are set by each federal agency and the Office of Personnel Management &mdash; not by JobGader. Confirm the details on the official USAJOBS or agency posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Federal law enforcement covers very different jobs &mdash; officers at ports of entry, deputy marshals, air marshals and FBI special agents &mdash; but they share one pay system, one retirement system and one hard rule: you must be a US citizen. Before you apply, it helps to know four things most guides get wrong: what the 2026 pay tables really say, how much availability pay adds for special agents, which age limit applies to which agency, and where the hiring is right now.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-federal-police-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128110; Browse Federal Police Jobs in USA &rarr;
    </a>
</div>

<h2>Entry Pay Starts Higher Than the Guides Say</h2>

<p>Guides put entry-level federal officers at <strong>$45,000 to $55,000</strong>. Federal law enforcement officers at grades GS-3 to GS-10 are paid on OPM's <strong>special base rates for law enforcement officers</strong>, and every federal employee also receives a <strong>locality payment</strong>. For 2026 the step 1 base rates are:</p>

<ul>
    <li><strong>GS-5:</strong> $42,919</li>
    <li><strong>GS-7:</strong> $48,854</li>
    <li><strong>GS-9:</strong> $54,485</li>
    <li><strong>GS-10:</strong> $59,999</li>
</ul>

<p>The lowest locality rate is <strong>17.06%</strong>, for the "Rest of U.S." area. So a new GS-5 officer anywhere in the country earns at least <strong>$50,241</strong>, a GS-7 $57,188 and a GS-9 $63,780. In Washington DC the locality rate is 33.94%, in New York 37.95% and in San Francisco 46.34%, which lifts a GS-10 officer in DC to $80,363 and a GS-13 to $121,785.</p>

<h2>Most Federal Officers Got 3.8% in 2026, Not 1%</h2>

<p><strong>Executive Order 14368</strong>, signed on 18 December 2025, gave General Schedule employees a <strong>1% raise</strong> for 2026 and froze locality rates at their 2025 levels. It also told OPM to consider up to 3.8% for law enforcement, and OPM did: special rate tables give a <strong>3.8% increase</strong> over 2025 pay to criminal investigators at the FBI, DEA, ATF and US Marshals, CBP officers, Border Patrol agents, ICE, the Secret Service and Bureau of Prisons staff, capped at $197,200.</p>

<p>Under those tables a covered GS-5 officer earns <strong>$51,632</strong> in the Rest of U.S. area, a GS-9 $65,545, and in Washington DC a GS-10 $82,589 and a GS-13 $125,158. OPM has already published 2027 special rates that repeat the 3.8% for law enforcement while freezing General Schedule pay, though those can still change.</p>

<h2>Special Agents Earn 25% More in Availability Pay</h2>

<p>Guides put FBI and DEA agents at $80,000 to $120,000 or more. That leaves out <strong>Law Enforcement Availability Pay (LEAP)</strong>: criminal investigators in the GS-1811 and GS-1812 series receive <strong>25% of their basic pay</strong>, including locality, in return for averaging at least two hours a day of unscheduled duty.</p>

<ul>
    <li><strong>FBI special agents:</strong> the current posting, for grades GL-10 to GL-13, pays <strong>$103,236 to $197,200</strong> a year including base pay, locality and availability pay.</li>
    <li><strong>DEA special agents:</strong> hired at GS-7 to GS-11, on $48,854 to $82,938 in base pay, plus locality and 25% LEAP.</li>
    <li><strong>CBP officers:</strong> uniformed officers are not criminal investigators, so they do not get LEAP. They earn overtime instead, which Congress caps at <strong>$45,000</strong> a year, with waivers.</li>
</ul>

<p>For comparison, the Bureau of Labor Statistics put the May 2025 median for detectives and criminal investigators at $93,790 across all employers, and the mean in the federal executive branch at $127,760.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/federal-police-jobs-in-usa-capitol.jpg"
         alt="A federal police officer in a tactical vest facing the US Capitol with the American flag flying"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Age Limits Differ by Agency &mdash; and ICE Has None</h2>

<p>Guides give a single age range of 21 to 37. OPM says the maximum entry age for law enforcement jobs varies from agency to agency, and each agency sets its own rule:</p>

<ul>
    <li><strong>FBI special agents:</strong> at least 23, and must apply <strong>before their 38th birthday</strong>, unless they have veterans' preference or qualifying federal law enforcement service.</li>
    <li><strong>DEA special agents:</strong> 21 to 36 at the time of appointment.</li>
    <li><strong>Deputy US Marshals:</strong> 21 to 36, and appointed before their 37th birthday.</li>
    <li><strong>CBP officers and Border Patrol agents:</strong> referred for selection <strong>before their 40th birthday</strong>, with exceptions for veterans and prior federal law enforcement service.</li>
    <li><strong>Federal Air Marshals:</strong> 21 to 36, with a waiver for veterans up to their 40th birthday.</li>
    <li><strong>ICE:</strong> since August 2025, ICE law enforcement jobs have <strong>no maximum age</strong>.</li>
</ul>

<h2>Degrees, Experience and Training</h2>

<ul>
    <li><strong>FBI special agents</strong> need a bachelor's degree and at least <strong>two years</strong> of full-time professional work experience, or an advanced degree and one year.</li>
    <li><strong>Federal Air Marshals</strong> need three years of work experience, a bachelor's degree, or a combination of the two.</li>
    <li><strong>CBP officers</strong> can qualify at GS-5 with three years of general work experience instead of a degree.</li>
    <li><strong>Training.</strong> Many agencies send new officers and agents to the Federal Law Enforcement Training Centers, where the Criminal Investigator Training Program runs <strong>59 training days</strong> before agency-specific courses. FBI special agents train at their own <strong>Quantico</strong> academy instead.</li>
</ul>

<h2>Which Agency Does What</h2>

<ul>
    <li><strong>Federal Protective Service officers</strong> protect federal buildings and the people in them.</li>
    <li><strong>US Park Police officers</strong> are based in the <strong>Washington DC, New York City and San Francisco</strong> areas, with jurisdiction across federal parks &mdash; not stationed in national parks nationwide, as guides suggest.</li>
    <li><strong>CBP officers</strong> inspect travellers and cargo at ports of entry, and <strong>Border Patrol agents</strong> work between them.</li>
    <li><strong>Federal Air Marshals</strong> fly on commercial flights for the TSA.</li>
    <li><strong>Deputy US Marshals</strong> find fugitives, protect federal courts and move prisoners.</li>
    <li><strong>FBI and DEA special agents</strong> investigate federal crimes, from cybercrime and fraud to drug trafficking.</li>
</ul>

<h2>Where the Hiring Is in 2026</h2>

<ul>
    <li><strong>ICE</strong> said on 3 January 2026 that it had hired more than <strong>12,000</strong> officers and agents in under a year, more than doubling its law enforcement workforce from 10,000 to 22,000, after receiving over 220,000 applications.</li>
    <li><strong>CBP</strong> offers new Border Patrol agents up to <strong>$60,000</strong> in incentives, including $10,000 after the academy and $10,000 more for a remote posting, and up to $60,000 for CBP officers in hard-to-fill locations.</li>
    <li><strong>Where to apply.</strong> Most jobs are posted on USAJOBS, and agencies such as the FBI and CBP also run their own careers sites.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/federal-police-jobs-in-usa-agencies.jpg"
         alt="A collage of federal law enforcement officers from the FBI, US Marshals, CBP, DEA and Secret Service in front of the Capitol"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Retirement and Leave</h2>

<ul>
    <li><strong>Early retirement.</strong> Law enforcement officers can retire at <strong>50 with 20 years</strong> of service, or at <strong>any age with 25</strong>, and must retire at 57.</li>
    <li><strong>The pension.</strong> 1.7% of your highest three-year average salary for each of the first 20 years, plus 1% for each year after.</li>
    <li><strong>The cost.</strong> Officers hired since 2014 contribute 4.9% of basic pay, against 4.4% for other federal staff.</li>
    <li><strong>Leave.</strong> Four hours of annual leave per pay period in the first three years, six hours from three to fifteen years and eight after that, plus 11 federal holidays.</li>
</ul>

<h2>Can a Non-Citizen Apply?</h2>

<p>No. US citizenship is required for federal law enforcement jobs, and a green card is not enough. If you are not yet a citizen, some state and local police departments hire lawful permanent residents; our <a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> guide explains which, and this page does not repeat it.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do federal police officers earn in 2026?</h3>
<p>A GS-5 law enforcement officer earns at least $50,241 with locality, or $51,632 at agencies covered by the 2026 law enforcement special rates. Pay rises with grade and locality, to $125,158 for a covered GS-13 in Washington DC.</p>

<h3>Did federal law enforcement get a raise in 2026?</h3>
<p>Most did. General Schedule employees got 1%, but OPM gave FBI, DEA, ATF and US Marshals criminal investigators, CBP officers, Border Patrol agents, ICE, the Secret Service and Bureau of Prisons staff 3.8%.</p>

<h3>How much does an FBI special agent make?</h3>
<p>The FBI's current posting pays $103,236 to $197,200 a year for grades GL-10 to GL-13, including locality and 25% availability pay.</p>

<h3>What is the age limit for federal law enforcement?</h3>
<p>It depends on the agency: the FBI takes applications before the 38th birthday, CBP refers candidates before the 40th, the DEA and US Marshals appoint up to 36, and ICE has had no maximum age since August 2025.</p>

<h3>Do I need a degree for federal law enforcement?</h3>
<p>Not for every job. CBP officers can qualify with three years of work experience, but FBI special agents need a bachelor's degree and two years of professional experience.</p>

<h3>What is LEAP pay?</h3>
<p>Law Enforcement Availability Pay: 25% of basic pay, including locality, for criminal investigators in the GS-1811 and GS-1812 series who average two or more hours a day of unscheduled duty.</p>

<h3>Where do federal agents train?</h3>
<p>Many train at the Federal Law Enforcement Training Centers, where the Criminal Investigator Training Program runs 59 training days. FBI special agents train at Quantico.</p>

<h3>Can a green card holder become a federal police officer?</h3>
<p>No. Federal law enforcement jobs require US citizenship. Some state and local police departments hire permanent residents.</p>

<h2>People Also Search For</h2>

<h3>LEO special base rates 2026</h3>
<p>$42,919 at GS-5 step 1 and $59,999 at GS-10, before locality.</p>

<h3>CBP officer salary</h3>
<p>From GS-5, with promotion potential to GS-12 and overtime of up to $45,000 a year.</p>

<h3>ICE officer jobs</h3>
<p>More than 12,000 hired in under a year, with no maximum age since August 2025.</p>

<h3>US Marshals age limit</h3>
<p>21 to 36, with appointment before the 37th birthday.</p>

<h3>Federal law enforcement retirement</h3>
<p>At 50 with 20 years or any age with 25, mandatory at 57, with a 1.7% multiplier for the first 20 years.</p>

<h3>US Park Police jobs</h3>
<p>Officers based in the Washington DC, New York City and San Francisco areas.</p>

<h3>Border Patrol agent bonus</h3>
<p>Up to $60,000 in incentives for new agents, including remote posting payments.</p>

<h3>FBI special agent requirements</h3>
<p>US citizenship, age 23 to before the 38th birthday at application, a bachelor's degree and two years of professional experience.</p>

<h2>More Job Guides</h2>

<p>Comparing public service and security careers? These cover it:</p>

<ul>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; state and local departments, and which ones hire non-citizens.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the civilian route into cyber investigations and clearances.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; another public service career, with pay set by state and district.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; private security work in the Gulf.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; the licensing a Gulf security job needs.</li>
    <li><a href="/blog/government-jobs-in-pakistan">Government Jobs in Pakistan</a> &mdash; public service careers at home, and how the tests work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, careers or financial advice. Federal pay tables, special rates, age limits and hiring incentives change every year and differ by agency. Confirm the current position on the OPM website, USAJOBS and the agency's own careers site before applying.</p>
HTML;
    }
}
