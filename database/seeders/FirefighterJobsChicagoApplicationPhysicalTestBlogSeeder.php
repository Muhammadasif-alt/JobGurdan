<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Firefighter Jobs in Chicago, Illinois: Application and Physical Test Guide"
 *
 * Checked on 27 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The ten identical buttons became links in the text.
 * 2. City of Chicago pages fetched directly: Join CFD (DHR "periodically holds entrance examinations for
 *    Firefighter/EMT", email alerts when applications are posted), Preparing to Join CFD (written test topics
 *    notified beforehand, Firefighter/EMT candidates "NOT required to have any EMT or Paramedic training", CPAT
 *    "completed within the last 12 months"), CFD Job Requirements (18 by the written exam, high school or GED,
 *    "No person may be appointed after age 37", Chicago residency and a US driver's licence by hire, background with
 *    fingerprints, drug test, medical screening; starting salary $74,626 a year as of 1 January 2026), and the
 *    eligibility list (Requisition 8801-CFD-2022-FF-EMT, lottery number, processing order number).
 * 3. IAFF CPAT page fetched: pass/fail time 10 minutes 20 seconds, ranking by time prohibited, 50 lb vest plus a
 *    25 lb hose pack on the stair climb, eight events, orientation sessions and at least two timed practice runs within
 *    30 days of the test since a 2006 EEOC conciliation agreement, waivable only in writing.
 * 4. igniteyourcareercfd.com did not respond and is not linked; the historical FAQ point is dropped.
 * 5. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for readers in
 *    Pakistan added.
 *
 * Posters from public/public/blog-355 (images 13 and 14). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to this row.
 */
class FirefighterJobsChicagoApplicationPhysicalTestBlogSeeder extends Seeder
{
    public const SLUG = 'firefighter-jobs-chicago-application-physical-test';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Firefighter Jobs in Chicago, Illinois: Application and Physical Test Guide',
                'excerpt' => 'How to become a Chicago firefighter: CFD eligibility (18 to apply, appointed by 37, GED, residency), the written test, the eight-event CPAT in 10 minutes 20 seconds, the $74,626 starting salary, and how the eligibility list and email alerts work.',
                'content' => $content,
                'featured_image' => 'blogs/firefighter-jobs-chicago-application-physical-test.jpg',
                'tags' => 'firefighter jobs in chicago illinois, chicago firefighter application, chicago fire department requirements, firefighter physical test chicago, chicago cpat test, chicago firefighter salary, cfd firefighter emt, chicago firefighter age limit',
                'meta_title' => 'Chicago Firefighter Jobs: Application and CPAT Guide',
                'meta_description' => 'Chicago firefighter jobs: CFD eligibility, the written test, the eight-event CPAT in 10:20, the $74,626 starting salary and how CFD hiring works.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To become a firefighter in Chicago, you apply for the Firefighter/EMT entrance examination when the City of Chicago Department of Human Resources opens it, pass the written test, prove a Candidate Physical Ability Test (CPAT) pass from the last 12 months, and clear background, drug and medical screening before hire.</strong> You do not need EMT training first, but you must be appointed by age 37 and live in Chicago by your start date. The city holds the examination periodically rather than continuously, and no Firefighter/EMT application window was open when we checked on 27 October 2026. This guide uses the City of Chicago's own pages and the International Association of Fire Fighters (IAFF) CPAT material.</p>

<h2>Understand Which Chicago Role You Want</h2>

<p>The Chicago Fire Department (CFD) hires through the city's Department of Human Resources, which periodically holds entrance examinations for Firefighter/EMT and separately takes applications for Fire Paramedics. They are different routes: Fire Paramedic applicants must already hold an Illinois EMT-Paramedic licence, while Firefighter/EMT applicants do not. Because the examination is not always open, the city's Join CFD page offers an email sign-up that tells you when applications are posted. Also separate Chicago from suburban departments such as Chicago Heights, which have their own rules and tests. Source: <a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/Join_CFD.html" rel="noopener">City of Chicago: Join CFD</a>.</p>

<h2>Check Eligibility Before Spending Money</h2>

<p>The CFD requirements for Firefighter/EMT are:</p>

<ul>
    <li>At least 18 years old by the date of the written examination.</li>
    <li>A high school diploma or GED by the date of the written examination.</li>
    <li>"No person may be appointed after age 37", so check your age at the likely hire date, not only at application.</li>
    <li>Proof of a CPAT pass within the last 12 months by the date of hire.</li>
    <li>Residency within the Chicago city limits by the date of hire.</li>
    <li>A valid driver's licence issued within the United States.</li>
    <li>Pre-employment screening: background investigation with fingerprints, drug test and medical screening.</li>
</ul>

<p>The city lists the starting salary as $74,626 a year as of 1 January 2026. Source: <a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/CFD_Job_Reqs.html" rel="noopener">City of Chicago: CFD Job Requirements</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/firefighter-jobs-chicago-application-physical-test-cpat.jpg"
         alt="A Chicago firefighter in turnout gear in front of a fire truck, with icons for the application, physical test, requirements, selection and training"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Follow the Application Instructions Carefully</h2>

<p>When the examination opens, apply only through the city's process linked from Join CFD and read the full announcement first. Prepare identification, education records and employment history, use the same name and dates everywhere, and describe your actual duties rather than just job titles. Save the announcement and your submission confirmation, check your email and spam folder, and keep contact details current, because an unfinished profile is not a submitted application.</p>

<h2>Prepare for the Written Examination</h2>

<p>Every candidate must pass a written test held by the City of Chicago before hire. The city says candidates are told the specific topics before the examination, and its Department of Human Resources publishes a preparation guide for city employment tests that it strongly recommends. Use those as your syllabus, practise reading carefully and following written directions under time limits, and treat commercial practice questions as exercises, not leaked content. Source: <a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/CFDJobPrep.html" rel="noopener">City of Chicago: Preparing to Join CFD</a>.</p>

<h2>Understand the CPAT Physical Test</h2>

<p>The CPAT was developed by the IAFF and the International Association of Fire Chiefs. It is pass/fail with a time limit of 10 minutes and 20 seconds, and departments are prohibited from ranking candidates by completion time. You wear a 50 lb weighted vest throughout, plus a 25 lb hose pack on the stair climb. The eight events, in order, are:</p>

<ol>
    <li>Stair climb</li>
    <li>Hose drag</li>
    <li>Equipment carry</li>
    <li>Ladder raise and extension</li>
    <li>Forcible entry</li>
    <li>Search</li>
    <li>Rescue</li>
    <li>Ceiling breach and pull</li>
</ol>

<p>Finishing quickly does not excuse a disqualifying technique error, so learn each station's rules from an authorised CPAT provider. Source: <a href="https://www.iaff.org/cpat/development-administration/" rel="noopener">IAFF: CPAT development and administration</a>.</p>

<h2>Prepare Through Official Practice Opportunities</h2>

<p>Since a 2006 conciliation agreement with the US Equal Employment Opportunity Commission, CPAT programmes include orientation sessions with the equipment and at least two timed practice runs on the actual course within 30 days of the test. Candidates can skip these only by signing a knowing and voluntary written waiver, which is rarely wise. Use the practice runs to find where your performance breaks down, ask the proctors for feedback, and train progressively between sessions rather than improvising ladder drills or confined-space work without supervision.</p>

<h2>Time Your CPAT Certification</h2>

<p>Chicago requires a CPAT pass from within the last 12 months at the date of hire. A pass from early in a long hiring process can expire before you are called, so ask when proof is due, which providers are accepted, and what retests cost. Keep the original result and a clear digital copy, and budget for a possible repeat as well as the first attempt.</p>

<h2>Stay Organised After Testing</h2>

<p>Passing the tests does not create a job offer. Chicago's current Firefighter/EMT eligibility list comes from Requisition 8801-CFD-2022-FF-EMT: every candidate received a random lottery number, and those who passed the written test were given a processing order number that sets the order in which they are contacted. Follow the city's instructions about your own status rather than another candidate's timeline, keep records of every notice, and avoid financial commitments based only on an expected hire date. Source: <a href="https://www.chicago.gov/city/en/depts/dhr/provdrs/careers/svcs/CFD_Firefighter_EMT_eligibility_list.html" rel="noopener">City of Chicago: CFD Firefighter/EMT eligibility list</a>.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>CFD hiring requires Chicago residency and a US driver's licence by hire, and city employment needs existing US work authorisation; it is not a visa route. If you are looking for public-safety work at home, read <a href="/blog/police-constable-jobs-punjab-pakistan">Police Constable Jobs in Punjab, Pakistan</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need an EMT licence first?</h3>
<p>No. The city says Firefighter/EMT candidates are not required to have any EMT or Paramedic training before applying or being hired. Fire Paramedic applicants do need an Illinois licence.</p>

<h3>What is the age limit?</h3>
<p>You must be 18 by the written examination, and no one may be appointed after age 37.</p>

<h3>How much does a Chicago firefighter earn to start?</h3>
<p>$74,626 a year as of 1 January 2026, according to the city.</p>

<h3>Is CPAT the written examination?</h3>
<p>No. CPAT is the physical test; the written examination is a separate requirement.</p>

<h2>Bottom Line</h2>

<p>Sign up for the city's alerts, check that you will meet the age, education, residency and licence rules at hire, prepare for the written test from the city's guide, and train for the CPAT through official orientation and practice runs. Apply only through the city's process when the examination opens.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/Join_CFD.html" rel="noopener">City of Chicago: Join CFD</a></li>
<li><a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/CFDJobPrep.html" rel="noopener">City of Chicago: Preparing to Join CFD</a></li>
<li><a href="https://www.chicago.gov/city/en/depts/cfd/supp_info/CFD_Job_Reqs.html" rel="noopener">City of Chicago: CFD Job Requirements</a>, including the starting salary as of 1 January 2026</li>
<li><a href="https://www.iaff.org/cpat/development-administration/" rel="noopener">IAFF: CPAT development and administration</a> and <a href="https://www.iaff.org/wp-content/uploads/2019/04/CPAT-2nd-Edition.pdf" rel="noopener">CPAT manual, second edition</a></li>
<li><a href="https://www.chicago.gov/city/en/depts/dhr/provdrs/careers/svcs/CFD_Firefighter_EMT_eligibility_list.html" rel="noopener">City of Chicago: CFD Firefighter/EMT eligibility list</a>, all checked 27 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/paramedic-jobs-toronto-training-certification">Paramedic Jobs in Toronto, Ontario</a>, another emergency-services career with an open recruitment.</li>
    <li><a href="/blog/security-guard-jobs-houston-license-training">Security Guard Jobs in Houston, Texas</a>, a faster entry route into US safety work.</li>
    <li><a href="/blog/police-constable-recruitment-uttar-pradesh">Police Constable Recruitment in Uttar Pradesh</a>, how another physical test is structured.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

HTML;
    }
}
