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
 * "Registered Nurse Jobs in USA" — pay, states, settings and the multistate
 * license. The older "Nurse Jobs in the US" guide owns the visa and
 * VisaScreen route for nurses trained abroad, so this one only points to it.
 *
 * Corrections to the draft:
 *
 * 1. Its pay bands are low. The BLS May 2025 median for registered nurses is
 *    $97,550 and the mean $101,420; the lowest-paid tenth earn under $68,940,
 *    so the draft's $60,000 to $70,000 entry band sits almost entirely below
 *    that.
 *
 * 2. Its ICU and ER band of $80,000 to $100,000+ has no official source: the
 *    BLS does not publish pay by specialty. Its travel nurse figure of $2,000
 *    to $3,000 a week has none either, and untaxed stipends depend on the IRS
 *    tax home rules.
 *
 * 3. Its top hiring states are right but out of order (Florida employs more
 *    RNs than New York), and none but California is among the best-paying
 *    states; California's mean is $150,280.
 *
 * 4. It says a multistate license lets a nurse practise in participating
 *    states without saying it is only issued to residents of a compact state,
 *    or that California, New York and Illinois are not in the compact.
 *
 * 5. It calls the shortage well documented. HRSA projects an 8% RN shortage
 *    in 2028 easing to 3% by 2038, concentrated in rural areas (11%).
 *
 * 6. The NCLEX-RN fee rises from $200 to $350 on 1 February 2027.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RegisteredNurseJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-registered-nurse-jobs.html';

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
        $title = 'Registered Nurse Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The BLS median RN wage is $97,550, not $70,000 to $85,000, and California pays a mean of $150,280. A multistate license needs residence in a compact state, and California and New York are not in the compact.',
                'content' => $content,
                'featured_image' => 'blogs/registered-nurse-jobs-in-usa.jpg',
                'tags' => 'registered nurse jobs usa, rn salary by state, registered nurse salary 2026, nurse licensure compact states, multistate nursing license, travel nurse jobs, nclex rn fee, ccrn requirements, highest paying states for nurses',
                'meta_title' => 'Registered Nurse Jobs in USA 2026: Pay by State and NLC',
                'meta_description' => 'Registered nurse jobs in the USA: the BLS median of $97,550, why California pays $150,280, the multistate license residency rule and the 2027 NCLEX fee.',
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
            ['name' => 'US Hospitals, Clinics & Home Health Agencies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-rn-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Registered Nurse — Hospitals, Outpatient Care and Home Health, USA',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Eight- or twelve-hour shifts, including nights, weekends and holidays in hospital roles',
                'language' => 'English',
                // RN pay runs from a lowest-paid tenth under $68,940 to a
                // California mean of $150,280, so no single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Registered nurse roles in US hospitals, outpatient centers, home health and government. A state license or a multistate license is required.',
                'seo_keywords' => 'registered nurse jobs usa, rn jobs, rn salary by state, travel nurse jobs, nurse licensure compact',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hospitals, outpatient care centers, physicians' offices, home health agencies, nursing homes and government employers across the United States recruit registered nurses throughout the year, from new graduates to specialty and travel nurses.</p>

<h3>What the work involves</h3>
<p>Assessing patients, giving medications and treatments, monitoring and recording their condition, working with physicians and the wider care team, and educating patients and families.</p>

<h3>Requirements</h3>
<ul>
    <li>Graduation from a state-approved nursing program, ADN or BSN</li>
    <li>A pass in the <strong>NCLEX-RN</strong></li>
    <li>A license from the state you work in, or a <strong>multistate license</strong> issued by your home compact state</li>
    <li>Specialty certification such as CCRN or CEN for some critical care and emergency posts</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured median.</strong> $97,550 a year in May 2025, according to the BLS</li>
    <li><strong>Location.</strong> State means run up to $150,280 in California</li>
    <li><strong>Setting.</strong> Government and hospital nurses have the highest medians</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check which license the post accepts.</strong> A multistate license is not valid in states outside the compact, including California and New York.</p>

<p><strong>Note:</strong> pay, licensing and immigration rules are set by employers, state boards of nursing and federal law &mdash; not by JobGader. Confirm the details with the employer and the state board before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>More than 3.3 million registered nurses work in the United States. The jobs are there, in hospitals, clinics and patients' homes. But the usual advice undersells the pay, gets the multistate license backwards and quotes travel nurse money no official source can confirm.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-registered-nurse-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse Registered Nurse Jobs in USA &rarr;
    </a>
</div>

<h2>What Registered Nurses Actually Earn</h2>

<p>Guides put entry-level RNs at <strong>$60,000 to $70,000</strong> and experienced medical-surgical nurses at $70,000 to $85,000. The Bureau of Labor Statistics measured much higher pay across <strong>3,379,720</strong> registered nurses in May 2025:</p>

<ul>
    <li><strong>Median:</strong> <strong>$97,550</strong> a year</li>
    <li><strong>Mean:</strong> $101,420 a year, or $48.76 an hour</li>
    <li><strong>Lowest-paid tenth:</strong> under <strong>$68,940</strong></li>
    <li><strong>Lowest-paid quarter:</strong> under $80,330</li>
    <li><strong>Highest-paid quarter:</strong> over $112,350, and the top tenth over $137,470</li>
</ul>

<p>So the guides' entry band sits almost entirely below the lowest-paid tenth of nurses, and their experienced band sits below the median. Percentiles are not the same as experience levels, but a new graduate in most markets should not accept the guides' figures as normal.</p>

<h2>Specialty Pay: What the Data Cannot Tell You</h2>

<p>Guides put ICU and ER nurses at <strong>$80,000 to $100,000 or more</strong>. The BLS does not publish RN pay by specialty, so no official figure backs that range &mdash; and the mean for all RNs is already $101,420. What it does publish is pay by setting:</p>

<ul>
    <li><strong>Government:</strong> median $110,780 (5% of RN jobs)</li>
    <li><strong>Hospitals:</strong> median $100,220 (59% of RN jobs)</li>
    <li><strong>Ambulatory care, such as outpatient centers and physicians' offices:</strong> median $91,230 (19%)</li>
    <li><strong>Nursing and residential care:</strong> median $84,300 (6%)</li>
    <li><strong>Educational services:</strong> median $78,620 (3%)</li>
</ul>

<p>By industry, outpatient care centers pay a mean of $112,690, general hospitals $104,360, home health care $93,580 and nursing care facilities $89,330. Nurses in the federal executive branch have a mean of $129,260.</p>

<h2>The Top States for Jobs Are Not the Top States for Pay</h2>

<p>Guides name California, Texas, New York, Florida and Pennsylvania as the top hiring states. That is right for the number of nurses, though Florida employs more than New York:</p>

<ul>
    <li><strong>California:</strong> 338,940 RNs</li>
    <li><strong>Texas:</strong> 271,380</li>
    <li><strong>Florida:</strong> 229,940</li>
    <li><strong>New York:</strong> 205,810</li>
    <li><strong>Pennsylvania:</strong> 146,520, just ahead of Ohio</li>
</ul>

<p>The best-paying states are a different list. <strong>California</strong> leads with a mean of <strong>$150,280</strong>, followed by Hawaii ($124,340), Oregon ($123,140), Washington ($121,540) and Massachusetts ($117,960). Only California appears on both lists.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/registered-nurse-jobs-in-usa-station.jpg"
         alt="A registered nurse in navy scrubs working at a hospital nurses' station"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Multistate License Has a Residency Catch</h2>

<p>Guides describe the Nurse Licensure Compact as a license that lets you practise in any participating state. That is true, but it leaves out who can hold one and where it does not work:</p>

<ul>
    <li><strong>43 jurisdictions</strong> are part of the compact. Massachusetts, Guam and the US Virgin Islands have joined but have not fully implemented it.</li>
    <li><strong>You must live in a compact state.</strong> A multistate license is issued only by your primary state of residence, and only if you legally declare residency in a compact state.</li>
    <li><strong>It does not cover the biggest non-members.</strong> California, New York, Illinois, Oregon, Hawaii, Nevada, Michigan, Minnesota, Alaska and the District of Columbia are not in the compact, so each still needs its own license. That includes the best-paying state and the fourth-largest employer.</li>
    <li><strong>Moving home means reapplying.</strong> A nurse who moves to another compact state must apply for a license there within 60 days.</li>
    <li><strong>Nurses educated abroad</strong> need a credentials review, an English test where it applies and a valid US Social Security number to meet the compact's uniform requirements.</li>
</ul>

<h2>NCLEX-RN: The Fee Is About to Rise</h2>

<p>Both associate degree (ADN) and bachelor's (BSN) graduates of an approved program sit the same <strong>NCLEX-RN</strong>, run by the National Council of State Boards of Nursing. The Next Generation NCLEX has been in use since 1 April 2023.</p>

<ul>
    <li><strong>Fee:</strong> $200, plus $150 to test outside the US</li>
    <li><strong>From 1 February 2027:</strong> the US registration fee rises to <strong>$350</strong></li>
    <li><strong>Where abroad:</strong> international test centers include India, <strong>Pakistan</strong>, the Philippines, Kenya, the United Kingdom and others. Nigeria is not on the list.</li>
</ul>

<h2>CCRN and CEN: The Hours Behind the Letters</h2>

<ul>
    <li><strong>CCRN (Adult)</strong>, from the AACN Certification Corporation, needs <strong>1,750 hours</strong> of direct care of acutely or critically ill adults in the previous two years, with 875 in the most recent year &mdash; or 2,000 hours over five years, with 144 in the most recent year.</li>
    <li><strong>CEN</strong>, from the Board of Certification for Emergency Nursing, has no required experience. BCEN recommends two years in emergency nursing.</li>
</ul>

<h2>Travel Nursing: Read the Contract, Not the Weekly Number</h2>

<p>Guides say travel contracts pay <strong>$2,000 to $3,000 or more a week</strong>. No official source publishes travel nurse pay. The BLS counts nurses employed by staffing agencies under employment services, with a mean of $95,660, and that figure does not include stipends.</p>

<p>Stipends are where the headline numbers come from, and they carry a tax condition. Under IRS rules, your tax home is generally your regular place of business. Someone with no regular place of business and no regular home is treated as itinerant and cannot deduct travel expenses. Before you rely on untaxed housing and meal stipends, check that you keep a genuine tax home, ideally with a tax adviser.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/registered-nurse-jobs-in-usa-bedside.jpg"
         alt="A registered nurse talking with a patient at the bedside in a hospital room"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Is There Really a Nursing Shortage?</h2>

<p>There is, but it is smaller and more local than guides suggest:</p>

<ul>
    <li><strong>BLS projections:</strong> RN jobs grow <strong>6% from 2025 to 2035</strong>, faster than the 3% average, with about <strong>180,800 openings a year</strong>, mostly to replace nurses who leave or retire.</li>
    <li><strong>HRSA projections</strong> (December 2025): an <strong>8% shortage in 2028</strong>, easing to <strong>3% by 2038</strong>.</li>
    <li><strong>Rural areas</strong> face the worst of it: an 11% shortage in 2038, against 2% in metro areas.</li>
</ul>

<h2>Trained Abroad? Start With the Visa Guide</h2>

<p>For nurses educated outside the US, the visa is the longer part of the journey. Staff RN roles rarely meet the H-1B rule that the job itself must require a specific bachelor's degree, so most nurses immigrate on an employment-based green card. Registered nurses are on the Department of Labor's Schedule A, which removes the labor market test but not the queue. Two recent changes matter:</p>

<ul>
    <li><strong>VisaScreen</strong> certificates are issued by CGFNS, now branded TruMerit. Its English test score requirements changed for tests taken after 12 May 2026.</li>
    <li><strong>The $100,000 H-1B payment</strong> announced in September 2025 does not apply to green card routes, and a federal court vacated the guidance implementing it on 8 June 2026.</li>
</ul>

<p>Our <a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> guide covers the full licensing and green card route, so this page does not repeat it.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do registered nurses make in the USA?</h3>
<p>The BLS median was $97,550 a year in May 2025, and the mean $101,420. The lowest-paid tenth earned under $68,940 and the highest-paid tenth over $137,470.</p>

<h3>Which state pays registered nurses the most?</h3>
<p>California, with a mean of $150,280, followed by Hawaii, Oregon, Washington and Massachusetts.</p>

<h3>Which states employ the most registered nurses?</h3>
<p>California, Texas, Florida, New York and Pennsylvania, in that order.</p>

<h3>Can I use a multistate license in California or New York?</h3>
<p>No. Neither state is in the Nurse Licensure Compact, so you need a license from each. A multistate license is also only issued if your primary state of residence is a compact state.</p>

<h3>How much does the NCLEX-RN cost?</h3>
<p>$200, plus $150 to test outside the US. The US registration fee rises to $350 on 1 February 2027.</p>

<h3>Can I take the NCLEX in Pakistan?</h3>
<p>Yes. Pakistan is on the NCSBN's list of international testing locations, along with India and the Philippines.</p>

<h3>How much do travel nurses make?</h3>
<p>No official source publishes travel nurse pay. Weekly figures include stipends, and untaxed stipends depend on keeping a genuine tax home under IRS rules.</p>

<h3>How many hours do I need for the CCRN?</h3>
<p>1,750 hours of direct care of acutely or critically ill adults in the previous two years, with 875 in the most recent year, or 2,000 hours over five years with 144 in the most recent year.</p>

<h2>People Also Search For</h2>

<h3>RN salary by state 2026</h3>
<p>California leads with a mean of $150,280, followed by Hawaii, Oregon, Washington and Massachusetts.</p>

<h3>Nurse Licensure Compact states</h3>
<p>43 jurisdictions, not including California, New York, Illinois, Oregon or Hawaii.</p>

<h3>New grad RN salary</h3>
<p>The BLS does not measure pay by experience; the lowest-paid tenth of RNs earned under $68,940 in May 2025.</p>

<h3>Highest paying nursing settings</h3>
<p>Government, with a median of $110,780, then hospitals at $100,220.</p>

<h3>NCLEX international testing locations</h3>
<p>Including India, Pakistan, the Philippines, Kenya and the United Kingdom.</p>

<h3>Travel nurse tax home rules</h3>
<p>Your tax home is generally your regular place of business; without one, you cannot deduct travel expenses.</p>

<h3>Nursing shortage projections</h3>
<p>HRSA projects an 8% RN shortage in 2028, easing to 3% by 2038, and 11% in rural areas.</p>

<h3>CEN certification requirements</h3>
<p>No required experience; BCEN recommends two years in emergency nursing.</p>

<h2>More Job Guides</h2>

<p>Comparing nursing and healthcare careers in different countries? These cover them:</p>

<ul>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the licensing and green card route for nurses trained abroad.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; NHS support work, its 2026 pay bands and the HCA visa rule.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; registered clinical roles and NMC registration.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; the Gulf route for nurses and the SCFHS licence.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; another entry into American work, with its own outlook problem.</li>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; public service pay in America, state by state.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, tax, careers or financial advice. Pay, licensing rules, exam fees, compact membership and immigration policy change and differ between states. Confirm the current position with the employer, your state board of nursing, NCSBN, the IRS and an immigration lawyer before applying.</p>
HTML;
    }
}
