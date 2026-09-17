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
 * "Physical Therapist Jobs in the USA" — pay, licensing and the route in for
 * both US-trained and foreign-trained therapists. The licensing section and the
 * internationally-trained pathway are where the mistakes are, so those get the
 * detail, and the pay is pinned to the BLS rather than to the round numbers
 * every careers page recycles.
 *
 * Corrections and clarifications to the draft (checked against the BLS OOH/OEWS
 * for Physical Therapists 29-1123, FSBPT, FCCPT, CGFNS and the PT Compact,
 * September 2026):
 *
 * 1. The draft gives national median pay as "$99,000-$101,000". That is the
 *    older data. The BLS median is $102,760 a year, about $49.40 an hour (May
 *    2025 OEWS), with the bottom tenth near $74,420 and the top tenth near
 *    $132,500. The guide uses the current figure.
 *
 * 2. The draft says employment will "grow substantially faster than the
 *    average". The BLS number is 12% from 2025 to 2035, against roughly 3-4%
 *    for all occupations, with about 13,400 openings a year. The guide states
 *    the figure rather than leaving it as an adjective, and does not repeat the
 *    superseded 15% from older editions.
 *
 * 3. The draft says the PT Compact covers "more than 30 states". As of 2026, 41
 *    jurisdictions have enacted the compact and about 38 are actively issuing
 *    privileges. "More than 30" is stale; the guide gives the current count.
 *
 * 4. The draft says the visa screen certificate is "issued by FCCPT (via CGFNS
 *    VisaScreen)". That conflates two separate bodies. VisaScreen is issued by
 *    CGFNS; FCCPT issues its own Type I health care worker certificate. Either
 *    one satisfies the federal certificate a foreign PT needs for a work visa.
 *    The guide also notes that physical therapists are a Schedule A, Group I
 *    shortage occupation, which is what makes EB-3 sponsorship straightforward,
 *    and that the 9-18 month timeline is credentialing, not the full green-card
 *    wait, which depends on country of birth.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PhysicalTherapistJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-physical-therapist-jobs.html';

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
        $title = 'Physical Therapist Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The BLS puts the physical therapist median at $102,760, not the $99,000 older guides quote; there is no national license, every state runs on the NPTE, the PT Compact now spans 41 jurisdictions, and foreign-trained PTs need a CGFNS or FCCPT certificate.',
                'content' => $content,
                'featured_image' => 'blogs/physical-therapist-jobs-in-usa.jpg',
                'tags' => 'physical therapist jobs in usa, physical therapist salary usa, npte exam, fsbpt, pt compact, dpt degree, physical therapist visa sponsorship, foreign trained physical therapist, physical therapy jobs america',
                'meta_title' => 'Physical Therapist Jobs in USA 2026: Pay, License & Visa',
                'meta_description' => 'Physical therapist jobs in USA in 2026: the $102,760 BLS median, state licensing and the NPTE, the PT Compact, and the visa route for foreign-trained PTs.',
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
            ['name' => 'US Healthcare Employers Hiring Physical Therapists (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-physical-therapist-aggregated']
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
                'position' => 'Physical Therapist — Outpatient, Hospital, Skilled Nursing, Home Health and Travel Roles, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Typically 40 hours a week; travel contracts run about 13 weeks',
                'language' => 'English',
                // Setting drives pay more than anything, from outpatient ortho to
                // travel contracts, and nothing is reachable before a state
                // license, so no figure is quoted on the listing itself.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Physical therapist roles with US hospitals, outpatient clinics, skilled nursing facilities, home health agencies and travel staffing. A state license and the NPTE come first.',
                'seo_keywords' => 'physical therapist jobs in usa, physical therapist salary, npte, pt compact, physical therapist visa sponsorship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Physical therapy is one of the few US healthcare professions where demand has outrun supply for over a decade. Clinics compete for candidates, and sign-on bonuses, continuing-education budgets and relocation support now appear routinely. But "there are lots of jobs" is not a plan: pay swings by tens of thousands of dollars by setting, and licensing is handled state by state, not nationally.</p>

<h3>How it works</h3>
<p>There is no national license. Every state licenses independently, and all of them require passing the National Physical Therapy Examination (NPTE), administered by the Federation of State Boards of Physical Therapy (FSBPT). You also need a Doctor of Physical Therapy (DPT) from a CAPTE-accredited program, or a credential-evaluated foreign equivalent.</p>

<h3>Requirements</h3>
<ul>
    <li>A DPT degree from a CAPTE-accredited program, or an evaluated foreign qualification</li>
    <li>A passing NPTE score, plus a state jurisprudence exam in most states</li>
    <li>A background check and BLS/CPR certification</li>
    <li>For foreign-trained PTs: a CGFNS VisaScreen or FCCPT Type I certificate for the visa</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>BLS median.</strong> $102,760 a year, about $49.40 an hour (May 2025), with the bottom tenth near $74,420 and the top tenth near $132,500</li>
    <li><strong>Setting drives it.</strong> Home health, skilled nursing and travel contracts pay highest; hospitals and entry outpatient roles sit lower</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> licensing, exam and immigration rules are set by the state boards, FSBPT, FCCPT, CGFNS and USCIS &mdash; not by JobGader. Confirm current requirements with the board in the state where you intend to practise before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Physical therapy is one of the few US healthcare professions where demand has outrun supply for over a decade and shows no sign of easing. An ageing population, more post-surgical rehab referrals and a push to treat pain without opioids all pull the same way, and it shows in the offers. But pay varies by tens of thousands of dollars by setting and state, licensing is state by state rather than national, and foreign-trained therapists face a credentialing process that has a strict order. This guide covers all three, with pay pinned to the BLS.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-physical-therapist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#0a3161;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127482;&#127480; Browse Physical Therapist Jobs in the USA &rarr;
    </a>
</div>

<h2>What Physical Therapists Actually Earn</h2>

<p>The number most careers pages quote &mdash; "$99,000 to $101,000" &mdash; is a year or two out of date. The BLS puts the current median at <strong>$102,760 a year, about $49.40 an hour</strong> (May 2025), and the single figure hides a wide spread. Setting matters more than almost anything else.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#0a3161;color:#fff;">
            <th style="padding:10px;text-align:left;">BLS wage (SOC 29-1123)</th>
            <th style="padding:10px;text-align:left;">Annual</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Bottom 10%</td><td style="padding:10px;">$74,420</td><td style="padding:10px;">$35.78</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$102,760</strong></td><td style="padding:10px;"><strong>$49.40</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Top 10%</td><td style="padding:10px;">$132,500</td><td style="padding:10px;">$63.70</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">BLS Occupational Employment and Wage Statistics, Physical Therapists (29-1123), May 2025.</p>
</div>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#0a3161;color:#fff;">
            <th style="padding:10px;text-align:left;">Setting</th>
            <th style="padding:10px;text-align:left;">Typical annual range</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Outpatient orthopaedic clinic</td><td style="padding:10px;">$78,000 &ndash; $95,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Acute care hospital</td><td style="padding:10px;">$85,000 &ndash; $105,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Skilled nursing facility (SNF)</td><td style="padding:10px;">$95,000 &ndash; $120,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Home health</td><td style="padding:10px;">$95,000 &ndash; $125,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Travel / contract assignments</td><td style="padding:10px;">$110,000 &ndash; $150,000+</td></tr>
    </tbody>
</table>
</div>

<p>Travel contracts sit at the top for a reason: they are typically 13 weeks, often in hard-to-staff areas, and part of the pay comes as untaxed housing and per-diem stipends. Home health pays well too, but it is usually per-visit rather than salaried. Geography adds another layer &mdash; California, Nevada, New Jersey and Alaska report the highest averages, while several southern and midwestern states run below the median. Always run an offer against local rent before being impressed by it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/physical-therapist-jobs-in-usa-exercise.jpg"
         alt="A physical therapist in navy scrubs guiding a seated man through a dumbbell arm exercise in a bright clinic, the New York skyline and Statue of Liberty through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which States Are Hiring Hardest</h2>

<ul>
    <li><strong>Texas and Florida.</strong> Huge retiree populations and constant skilled-nursing and home-health demand.</li>
    <li><strong>California and New York.</strong> The highest raw salaries, the highest cost of living, and the most competitive outpatient roles.</li>
    <li><strong>Arizona, Nevada, the Carolinas.</strong> Fast population growth and new clinics opening ahead of staffing.</li>
    <li><strong>Rural Midwest and Mountain West.</strong> The smallest applicant pools and the largest sign-on bonuses, frequently $8,000&ndash;$15,000.</li>
</ul>

<h2>Licensing: The Part People Underestimate</h2>

<p>There is no single American license. Every state has its own board, fees and jurisprudence exam. What they share is the <strong>National Physical Therapy Examination (NPTE)</strong>, administered by the FSBPT. Pass the NPTE, meet your chosen state's requirements, and you practise there &mdash; and only there, unless you license in additional states.</p>

<ul>
    <li>A Doctor of Physical Therapy (DPT) from a CAPTE-accredited program, or a credential-evaluated foreign equivalent</li>
    <li>A passing NPTE score</li>
    <li>A state jurisprudence exam in most jurisdictions &mdash; a short test on that state's practice law</li>
    <li>A criminal background check and BLS/CPR certification</li>
</ul>

<p><strong>The PT Compact is worth knowing early.</strong> It lets a licensed therapist work across participating states without a full license in each one. As of 2026, <strong>41 jurisdictions have enacted the compact and about 38 are actively issuing privileges</strong> &mdash; far more than the "30 states" older guides cite. If you plan to take travel contracts, a compact privilege saves months of paperwork and several hundred dollars per state.</p>

<h2>If You Trained Outside the United States</h2>

<p>Foreign-trained therapists are actively recruited, particularly from the Philippines, India, Pakistan, Canada and the UK. The route is well worn but strict in its order, and doing steps out of sequence is what causes most delays.</p>

<ol>
    <li><strong>Credential evaluation.</strong> An agency such as FCCPT, ICA or CGFNS compares your degree against US DPT standards. If your program falls short, you may need extra coursework.</li>
    <li><strong>English proficiency.</strong> Most states require TOEFL iBT or IELTS; some waive it if you trained in English.</li>
    <li><strong>NPTE eligibility and exam.</strong> You apply through your target state board, which authorises you to sit the exam.</li>
    <li><strong>Health care worker certificate.</strong> For a work visa you need one of two certificates &mdash; <strong>CGFNS's VisaScreen or FCCPT's Type I certificate</strong>. VisaScreen is issued by CGFNS, not by FCCPT, so choose one path and follow it.</li>
    <li><strong>Employment and sponsorship.</strong> Most PTs enter on an H-1B, and because physical therapists are a Schedule A, Group I shortage occupation, EB-3 permanent-residency sponsorship is straightforward with large rehab, SNF and home-health employers.</li>
</ol>

<p>Budget <strong>nine to eighteen months</strong> and $3,000&ndash;$5,000 for the credentialing, certification and licensing steps. That window is the paperwork, not the full green-card wait: an EB-3 immigrant visa also depends on your country of birth and the visa bulletin, and for high-demand countries it can run well beyond eighteen months.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/physical-therapist-jobs-in-usa-treatment.jpg"
         alt="A physical therapist supporting a man's leg through a stretch on a treatment table in a rehab gym, an American flag and the New York skyline behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Make Your Application Stand Out</h2>

<ul>
    <li><strong>Lead with your setting and specialty.</strong> An orthopaedic clinic scans for manual therapy, dry needling and return-to-play; an SNF director scans for Medicare Part A documentation and geriatric caseloads. Rewrite the top third of your CV for each.</li>
    <li><strong>Quantify outcomes, not duties.</strong> "Managed a 12-14 patient daily caseload with a 92% plan-of-care completion rate" tells a recruiter you can hold a schedule and finish your notes.</li>
    <li><strong>Name your EMR.</strong> Epic, WebPT, Raintree, Net Health, Casamba. Documentation speed is a real hiring criterion, and knowing the system saves weeks of onboarding.</li>
    <li><strong>Ask about productivity requirements.</strong> A clinic expecting 90% productivity with 30-minute evaluations is a very different job from one expecting 75% with an hour. The answer predicts your quality of life more than the salary does.</li>
</ul>

<h2>Where the Profession Is Heading</h2>

<p>BLS projects physical therapist employment to grow <strong>12% from 2025 to 2035</strong>, much faster than the roughly 3-4% average across all occupations, with about 13,400 openings a year. The baby-boomer cohort, better survival after stroke and major surgery, and direct-access laws in all 50 states are the drivers. The counterweight is reimbursement: Medicare fee schedules have trended down, which pushes clinics toward higher productivity expectations &mdash; which is exactly why the questions you ask before signing matter as much as the salary line.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the average physical therapist salary in the USA?</h3>
<p>The BLS median is $102,760 a year, about $49.40 an hour (May 2025), with the bottom tenth near $74,420 and the top tenth near $132,500. Skilled nursing, home health and travel contracts pay above the median.</p>

<h3>Do I need a national license to work as a physical therapist?</h3>
<p>No &mdash; there is no national license. Every state licenses independently, and all require passing the NPTE administered by the FSBPT, plus a DPT degree and, in most states, a jurisprudence exam.</p>

<h3>What is the PT Compact and how many states are in it?</h3>
<p>The PT Compact lets a licensed therapist practise across member states without a full license in each. As of 2026, 41 jurisdictions have enacted it and about 38 are actively issuing privileges.</p>

<h3>Can a foreign-trained physical therapist work in the USA?</h3>
<p>Yes. You need a credential evaluation (FCCPT, ICA or CGFNS), English proficiency, a passing NPTE, and a health care worker certificate &mdash; CGFNS's VisaScreen or FCCPT's Type I &mdash; for the visa.</p>

<h3>Who issues the VisaScreen certificate?</h3>
<p>CGFNS issues the VisaScreen certificate, not FCCPT. FCCPT issues its own Type I health care worker certificate. Either one satisfies the federal certificate a foreign PT needs for a work visa.</p>

<h3>Which visa do foreign physical therapists usually use?</h3>
<p>Most enter on an H-1B. Because physical therapists are a Schedule A, Group I shortage occupation, EB-3 permanent-residency sponsorship is straightforward, though the green-card wait depends on your country of birth.</p>

<h3>How long does the foreign credentialing process take?</h3>
<p>Plan for nine to eighteen months and $3,000-$5,000 for credentialing, certification and licensing. That is separate from the immigrant-visa wait, which can be longer for high-demand countries.</p>

<h3>Is physical therapy a growing field?</h3>
<p>Yes. The BLS projects 12% growth from 2025 to 2035, much faster than the all-occupation average, with about 13,400 openings a year.</p>

<h2>People Also Search For</h2>

<h3>NPTE exam FSBPT</h3>
<p>The National Physical Therapy Examination, administered by the Federation of State Boards of Physical Therapy, required in every state.</p>

<h3>PT Compact states</h3>
<p>The 41 jurisdictions that have enacted the compact, about 38 of them actively issuing privileges to practise across member states.</p>

<h3>Physical therapist salary by state</h3>
<p>California, Nevada, New Jersey and Alaska report the highest averages; several southern and midwestern states run below the $102,760 median.</p>

<h3>DPT degree requirements</h3>
<p>A Doctor of Physical Therapy from a CAPTE-accredited program, or a credential-evaluated foreign equivalent, is the entry qualification.</p>

<h3>Physical therapist visa sponsorship</h3>
<p>Foreign PTs usually enter on an H-1B and, as a Schedule A shortage occupation, can be sponsored for an EB-3 green card.</p>

<h3>CGFNS VisaScreen certificate</h3>
<p>The health care worker certificate issued by CGFNS that a foreign physical therapist needs for a US work visa, alongside FCCPT's Type I alternative.</p>

<h3>Travel physical therapist pay</h3>
<p>Typically $110,000-$150,000+ on 13-week contracts, part of it as untaxed housing and per-diem stipends.</p>

<h3>Highest paying physical therapy settings</h3>
<p>Home health, skilled nursing and travel contracts pay the most; hospitals and entry outpatient roles pay least.</p>

<h2>More Job Guides</h2>

<p>Looking at neighbouring US healthcare roles or the routes abroad? These cover them:</p>

<ul>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; the largest US healthcare profession, its pay and its licensing.</li>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; an entry-level clinical route with no license and a faster start.</li>
    <li><a href="/blog/school-nurse-jobs-in-usa">School Nurse Jobs in USA</a> &mdash; a term-time nursing role and how its certification works.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the comparable support role across the Atlantic.</li>
    <li><a href="/blog/occupational-therapist-jobs-in-canada">Occupational Therapist Jobs in Canada</a> &mdash; the neighbouring allied-health market, its registration and pay.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; a care-sector route with a genuine visa pathway.</li>
    <li><a href="/blog/entry-level-healthcare-jobs">Entry Level Healthcare Jobs</a> &mdash; the US healthcare roles you can start with a high school diploma or a short course, what they pay and how to apply.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Salary ranges and licensing requirements change over time. Confirm current requirements with the physical therapy board in the state where you intend to practise, and with FSBPT, FCCPT and CGFNS, before applying.</p>
HTML;
    }
}
