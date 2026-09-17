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
 * "Tutor Jobs in USA" — a guide for students, graduates and teachers looking
 * for tutoring work in the United States. The Teacher Jobs in USA guide covers
 * classroom teaching and licensure, and Online Teacher Jobs Worldwide covers
 * the global English-teaching platforms, so this one stays on US tutoring: BLS
 * pay, what each tutoring company and marketplace requires, what it keeps from
 * your pay, and the tax that comes with contract work.
 *
 * Corrections and clarifications to the draft (checked against the BLS
 * Occupational Outlook Handbook and OEWS, the IRS, AmeriCorps, the Washington
 * education department and the official sites and help centres of Wyzant,
 * Varsity Tutors, Sylvan Learning, Huntington Learning Center and Kumon,
 * September 2026):
 *
 * 1. The draft says Huntington tutors work with groups of up to four students.
 *    Huntington's own site says only one-on-one or small group tutoring, and
 *    states its tutors have four-year degrees plus state or Huntington
 *    certification.
 *
 * 2. The draft's Huntington CareerPlug link shows no jobs. Huntington's careers
 *    page says each local center hires independently.
 *
 * 3. The draft leaves out what Wyzant keeps and who can join. Tutors keep 75%
 *    of their rate after a 25% platform fee, and must be 18, live in the US and
 *    hold a valid Social Security Number.
 *
 * 4. The draft links Varsity Tutors and Kumon home pages. Varsity Tutors hires
 *    online tutors as independent contractors who need proof of higher
 *    education, and Kumon centers are franchises, so the employer is the local
 *    center owner, not Kumon.
 *
 * 5. The draft quotes no pay or outlook. BLS lists a median of $20.84 an hour,
 *    little or no change in employment from 2025 to 2035, and says most tutors
 *    work part time.
 *
 * 6. The draft leaves out tax. Self-employed tutors pay self-employment tax of
 *    15.3% once net earnings reach $400.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TutorJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-tutor-jobs.html';

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
        $title = 'Tutor Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Tutors in the USA earn a median $20.84 an hour and most work part time. Wyzant keeps 25% of your rate, Varsity Tutors hires online contractors, and Sylvan, Huntington and Kumon centers are local franchises that hire for themselves.',
                'content' => $content,
                'featured_image' => 'blogs/tutor-jobs-in-usa.jpg',
                'tags' => 'tutor jobs in usa, online tutor jobs usa, tutoring jobs near me, wyzant tutor requirements, varsity tutors jobs, sylvan learning tutor jobs, huntington learning center jobs, kumon center assistant, tutor salary usa, math tutor jobs',
                'meta_title' => 'Tutor Jobs in USA: Pay, Requirements and How to Apply',
                'meta_description' => 'Tutor jobs in the USA: BLS pay of $20.84 an hour, what Wyzant, Varsity Tutors, Sylvan, Huntington and Kumon require, their fees and how to apply.',
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
            ['name' => 'US Tutoring Centers, Online Platforms & Schools (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-tutor-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Tutor — Math, Reading, Test Prep and Online Tutoring Roles, US Tutoring Centers and Platforms',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Mostly afternoons, evenings and weekends, around school hours',
                'language' => 'English',
                // Center pay and platform rates vary by subject, location and
                // the tutor's own rate, so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Part-time tutoring roles with US learning centers, schools and online platforms in math, reading, science, writing and test prep.',
                'seo_keywords' => 'tutor jobs usa, online tutor jobs, math tutor jobs, reading tutor jobs, test prep tutor jobs, part-time tutoring jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Learning centers, schools, tutoring companies and online platforms across the United States hire tutors to support students one-on-one or in small groups, in person or online.</p>

<h3>Typical subjects</h3>
<ul>
    <li>Math, from elementary to calculus</li>
    <li>Reading, writing and English</li>
    <li>Science</li>
    <li>SAT, ACT and other test preparation</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Strong knowledge of the subjects you tutor; many centers and platforms ask for a bachelor's degree or proof of higher education</li>
    <li>Usually 18 or older; online platforms may require US residence and a background check</li>
    <li>School-based tutors may need a fingerprint background check under state law</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, platform fees and requirements are set by each center, platform and state &mdash; not by JobGader. Confirm the details on the employer's own page before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Tutor jobs in the USA are offered by learning centers such as Sylvan, Huntington and Kumon, by online platforms such as Wyzant and Varsity Tutors, and by schools and AmeriCorps tutoring programs.</strong> The U.S. Bureau of Labor Statistics (BLS) puts the median tutor wage at <strong>$20.84 an hour</strong> and says most tutors work part time. Requirements differ widely: Kumon center assistants need strong high school math or English, while Huntington says its tutors have four-year degrees. On platforms, check the fee first: Wyzant keeps <strong>25%</strong> of your rate.</p>

<p>This guide covers what tutors earn, what each company actually requires, how the platforms pay, and how to apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-tutor-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128218; Browse Tutor Jobs in the USA &rarr;
    </a>
</div>

<h2>What Tutors Earn and the Job Outlook</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Tutors (BLS)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median wage, May 2025</strong></td><td style="padding:10px;"><strong>$20.84 an hour</strong> ($43,350 a year)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 percent</td><td style="padding:10px;">Under $14.15 an hour</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10 percent</td><td style="padding:10px;">Over $36.53 an hour</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Typical entry-level education</td><td style="padding:10px;">Some college, no degree</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Jobs, 2025</td><td style="padding:10px;">208,400</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Outlook, 2025&ndash;35</td><td style="padding:10px;">0%, little or no change</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Openings a year</td><td style="padding:10px;">About 34,200</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">BLS Occupational Outlook Handbook, Tutors, and Occupational Employment and Wage Statistics, May 2025. Wage percentiles exclude self-employed tutors.</p>
</div>

<p>Two things stand out. First, BLS says <strong>most tutors work part time</strong>, so the yearly figure is not what most tutors take home. Second, employment is not growing, but about 34,200 openings a year still come up as tutors leave or move on, which suits students and teachers looking for side income.</p>

<h2>Tutoring Companies and What Each One Requires</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Company</th>
            <th style="padding:10px;text-align:left;">Type</th>
            <th style="padding:10px;text-align:left;">Key requirement</th>
            <th style="padding:10px;text-align:left;">Where to apply</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Wyzant</strong></td><td style="padding:10px;">Marketplace, online and in person</td><td style="padding:10px;">18+, US resident, valid SSN</td><td style="padding:10px;">wyzant.com/tutorsignupstart</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Varsity Tutors</strong></td><td style="padding:10px;">Online, independent contractor</td><td style="padding:10px;">18+, proof of higher education, background check</td><td style="padding:10px;">varsitytutors.com/tutoring-jobs</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sylvan Learning</strong></td><td style="padding:10px;">Franchise centers, plus Marketplace+</td><td style="padding:10px;">Marketplace+: a related bachelor's degree</td><td style="padding:10px;">sylvanlearning.com/careers</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Huntington Learning Center</strong></td><td style="padding:10px;">Franchise centers</td><td style="padding:10px;">Four-year degree plus state or Huntington certification</td><td style="padding:10px;">huntingtonhelps.com/careers</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Kumon</strong></td><td style="padding:10px;">Franchise centers</td><td style="padding:10px;">Center assistants: strong high school math and/or English</td><td style="padding:10px;">kumon.com/employment</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Official sites and help centres of each company, September 2026.</p>
</div>

<h3>1. Wyzant</h3>
<p>Wyzant is a marketplace where you list yourself and students choose you. Tutors "must be at least 18 years old, reside in the United States, and possess a valid Social Security Number", and they are <strong>not required to be certified teachers</strong>. You set your own hourly rate and travel radius, and you can search jobs by subject and by online or in-person lessons. The registration asks about your teaching experience, degrees, licenses and certifications, and some subjects need a short proficiency quiz.</p>
<p>The fee matters: tutors keep <strong>75% of their posted rate</strong> and Wyzant keeps a <strong>25% platform fee</strong>, except for students you refer yourself. Wyzant does not require a background check at registration, but a student or parent can request one, which you must then pass.</p>

<h3>2. Varsity Tutors</h3>
<p>Varsity Tutors' tutoring jobs page is for <strong>online tutoring</strong>, with an application and a video interview, and pay twice a week. Applicants confirm they are 18 or older, have a valid US or Canadian government-issued ID, can provide <strong>proof of higher education and official transcripts</strong>, and can pass a background check. Tutors on the platform are <strong>independent contractors</strong>, not employees.</p>

<h3>3. Sylvan Learning</h3>
<p>Sylvan calls its tutors part-time teachers. Its centers are locally owned franchises, so for center jobs Sylvan tells you to contact a center near you. Sylvan also runs <strong>mySylvan Marketplace+</strong>, where tutors work online or in person and set their own rates and schedules; it asks for a <strong>bachelor's degree in a field related to your tutoring subjects</strong>, and says a state teaching certificate is a plus.</p>

<h3>4. Huntington Learning Center</h3>
<p>Huntington says: "Our tutors have degrees from four-year colleges, plus either state or Huntington certification." It offers one-on-one and small group tutoring. Each local center is independently owned and <strong>handles its own hiring</strong>, so apply through the center near you from Huntington's careers page rather than an old job board link.</p>

<h3>5. Kumon</h3>
<p>Kumon's math and reading centers are independently owned franchises, and Kumon says plainly that if you work at a center, "your employer will not be Kumon". The common starting role is <strong>center assistant</strong>, grading and preparing student classwork, which asks for strong high school math and/or English and evening or weekend availability.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/tutor-jobs-in-usa-homework-help.jpg"
         alt="A smiling tutor helping a boy with his written work at a desk with a laptop and textbooks, with an American flag, a US map and a city skyline in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Other Places Tutors Work</h2>

<ul>
    <li><strong>AmeriCorps tutoring programs:</strong> programs such as Reading Corps and Math Corps pay tutors a stipend every two weeks, and members who complete a term of service receive the Segal Education Award. AmeriCorps members must be 17 or older and a US citizen, US national or lawful permanent resident.</li>
    <li><strong>Schools and districts:</strong> many districts hire tutors directly, often for small group support. Expect a background check: in Washington, for example, new school employees and contractors with regular unsupervised access to children must be fingerprinted.</li>
    <li><strong>College tutoring centers:</strong> colleges hire student and professional tutors for their own courses.</li>
    <li><strong>Private clients:</strong> tutoring families directly, where you set your own rate and handle your own tax.</li>
</ul>

<h2>Subjects You Can Tutor</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Subject area</th>
            <th style="padding:10px;text-align:left;">Example tutor jobs</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Math</strong></td><td style="padding:10px;">Elementary math, algebra, geometry, calculus</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>English</strong></td><td style="padding:10px;">Reading, writing, essay help, ESL</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Science</strong></td><td style="padding:10px;">Biology, chemistry, physics</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Test preparation</strong></td><td style="padding:10px;">SAT, ACT, GED</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Languages and technology</strong></td><td style="padding:10px;">Spanish, French, computer science, coding</td></tr>
    </tbody>
</table>
</div>

<p>Math and reading are where the center jobs are, because Kumon and most learning centers are built around them. On marketplaces such as Wyzant, you choose your subjects and set your own rate.</p>

<h2>Tax: Contract Tutoring Is Self-Employment</h2>

<p>If you tutor through a marketplace, as an independent contractor or for private clients, you are usually self-employed. The IRS says the <strong>self-employment tax rate is 15.3%</strong> (12.4% for Social Security and 2.9% for Medicare), and you must pay it if your net earnings from self-employment are <strong>$400 or more</strong>. Keep records of your income and set money aside, because no employer is withholding it for you.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/tutor-jobs-in-usa-one-to-one.jpg"
         alt="A tutor and a smiling girl in a lavender hoodie working through a notebook together at a desk, with an American flag, the New York skyline and the Statue of Liberty behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get a Tutor Job, Step by Step</h2>

<ol>
    <li><strong>Pick two or three subjects</strong> you can teach confidently, and the grade levels you want.</li>
    <li><strong>Choose center or platform.</strong> Centers give you students and a set wage; platforms let you set a rate but take a fee and leave tax to you.</li>
    <li><strong>Gather proof:</strong> transcripts, degrees, certificates and any tutoring, teaching or coaching experience. Varsity Tutors asks for official transcripts.</li>
    <li><strong>Apply on the official page</strong>: Wyzant's tutor sign-up, Varsity Tutors' tutoring jobs page, or the local Sylvan, Huntington or Kumon center.</li>
    <li><strong>Prepare for a demo or interview.</strong> Be ready to explain a hard concept simply and show how you would help a student who is stuck.</li>
    <li><strong>Be ready for a background check</strong>, especially for school, center and in-person work.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a teaching degree to become a tutor in the USA?</h3>
<p>Not usually. BLS lists some college, no degree, as typical, and Wyzant says tutors are not required to be certified teachers. Some employers ask for more: Huntington says its tutors have four-year degrees, and Sylvan's Marketplace+ asks for a related bachelor's degree.</p>

<h3>How much do tutors make in the USA?</h3>
<p>BLS lists a median of $20.84 an hour in May 2025, with the lowest 10 percent under $14.15 and the highest 10 percent over $36.53. Most tutors work part time.</p>

<h3>How much does Wyzant take from tutors?</h3>
<p>Wyzant keeps a 25% platform fee, so tutors keep 75% of their posted hourly rate. Tutors keep 100% for students they refer to Wyzant themselves.</p>

<h3>Can I tutor online in the USA without a degree?</h3>
<p>On some platforms. Wyzant requires you to be 18, live in the US and hold a valid Social Security Number, but not a teaching certificate. Varsity Tutors asks for proof of higher education and official transcripts.</p>

<h3>Are tutors employees or contractors?</h3>
<p>It depends. Center staff are usually employees of the local franchise owner, while Varsity Tutors says its tutors are independent contractors. Self-employed tutors pay 15.3% self-employment tax once net earnings reach $400.</p>

<h3>Does Kumon hire tutors?</h3>
<p>Kumon centers hire center assistants who grade and prepare classwork. The centers are independently owned franchises, so your employer is the local center owner, not Kumon.</p>

<h3>Is tutoring a growing job in the USA?</h3>
<p>BLS projects little or no change in tutor employment from 2025 to 2035, but about 34,200 openings a year as tutors leave or change jobs.</p>

<h3>Do tutors need a background check?</h3>
<p>Often. Varsity Tutors applicants must be able to pass one, Wyzant tutors must pass one if a student requests it, and states such as Washington require fingerprint checks for school contractors with unsupervised access to children.</p>

<h2>People Also Search For</h2>

<h3>Online tutor jobs USA</h3>
<p>Wyzant and Varsity Tutors both hire tutors for online lessons.</p>

<h3>Tutor salary USA</h3>
<p>A median of $20.84 an hour according to BLS, May 2025.</p>

<h3>Wyzant tutor requirements</h3>
<p>18 or older, US residence and a valid Social Security Number.</p>

<h3>Varsity Tutors application</h3>
<p>An application, video interview, transcripts and a background check.</p>

<h3>Sylvan Learning tutor jobs</h3>
<p>Local franchise centers, plus the online and in-person Marketplace+.</p>

<h3>Kumon center assistant</h3>
<p>Grading and preparing classwork, with strong high school math or English.</p>

<h3>Math tutor jobs</h3>
<p>The most common center and platform subject, from elementary math to calculus.</p>

<h3>AmeriCorps tutoring jobs</h3>
<p>Reading Corps and Math Corps tutors receive a stipend every two weeks.</p>

<h2>More Job Guides</h2>

<p>Looking at teaching work more broadly? These cover it:</p>

<ul>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; classroom teaching, licensure and pay.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; the global online teaching platforms.</li>
    <li><a href="/blog/work-from-home-jobs-in-usa">Work From Home Jobs in USA</a> &mdash; other remote work to combine with tutoring.</li>
    <li><a href="/blog/how-to-get-an-esl-teaching-job-in-japan">How to Get an ESL Teaching Job in Japan</a> &mdash; teaching English abroad.</li>
    <li><a href="/blog/preschool-teacher-jobs-in-canada">Preschool Teacher Jobs in Canada</a> &mdash; early childhood education across the border.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; teacher assistant and paraprofessional pay, the Title I rule, the ParaPro test and who is hiring.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not tax advice. Pay, platform fees, requirements and openings change over time. Confirm the current position with the Bureau of Labor Statistics, the IRS and each company's own site before relying on it.</p>
HTML;
    }
}
