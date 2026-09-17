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
 * "How to Get an ESL Teaching Job in Japan" — a guide for a graduate, usually
 * a native English speaker, choosing between the JET Programme, a dispatch
 * ALT contract and an eikaiwa, and trying to work out whether their degree
 * and nationality will get them a visa. The visa rules decide who can apply at
 * all, so they get the space, and the online teaching guide owns remote work.
 *
 * Corrections and clarifications to the draft (checked against the
 * Immigration Services Agency of Japan, MEXT, the JET Programme and EF EPI,
 * September 2026):
 *
 * 1. The draft calls the private-sector visa "Specialist in Humanities/
 *    International Services". Its name is Engineer/Specialist in Humanities/
 *    International Services, and the Immigration Services Agency lists
 *    language teachers at private companies under it. Teaching inside
 *    elementary, junior high and high schools falls under Instructor.
 *
 * 2. The draft says a degree is "the hard requirement" and that ten years of
 *    experience can occasionally replace it. For Instructor, the criteria ask
 *    for a degree or equivalent (or a teaching licence) plus twelve years of
 *    education in the language taught. For language teaching under
 *    International Services, the rule is three years of relevant experience,
 *    waived for university graduates; the ten-year route belongs to the
 *    humanities-knowledge part and does not apply to language teaching. Both
 *    statuses require pay at least equal to a Japanese national's.
 *
 * 3. The draft gives Certificate of Eligibility processing as 4-8 weeks. The
 *    agency's standard processing period is one to three months. A CoE is
 *    valid for three months and has been deliverable by email since
 *    17 March 2023.
 *
 * 4. The draft says registering your address "activates" the residence card.
 *    The card is issued on landing at ten airports and is valid on issue;
 *    registering within 14 days of settling is a separate duty. At other
 *    ports the card is posted after that notification.
 *
 * 5. The draft plans around "April or September school-year starts". The
 *    Japanese school year starts in April; JET participants arrive in late
 *    July or early August.
 *
 * 6. The draft's EF EPI figure is a year old. Japan was 92nd of 116 in 2024
 *    and fell to 96th of 123, in the "very low" band, in EF EPI 2025.
 *
 * 7. The draft quotes monthly ranges and dollar conversions with no official
 *    source. JET publishes its scale: 4.02 million yen in the first year,
 *    rising to 4.32 million in the fourth and fifth. Private ranges are
 *    labelled as advertised figures, and the dollar conversions are dropped.
 *
 * 8. The draft misses the fee change a new teacher will actually pay. From
 *    1 October 2026, renewal and change-of-status fees move from a flat
 *    6,000 yen to a scale by period of stay: 33,000 yen at the counter, or
 *    27,000 yen online, for a one-year grant.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EslTeacherJobsJapanBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://jp.indeed.com/q-english-teacher-jobs.html';

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
        $title = 'How to Get an ESL Teaching Job in Japan';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Japan\'s teaching visas turn on a degree, the Certificate of Eligibility takes one to three months rather than four to eight weeks, JET pays 4.02 million yen in year one, and visa renewal fees rise from 1 October 2026.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-an-esl-teaching-job-in-japan.jpg',
                'tags' => 'esl teaching jobs in japan, teach english in japan, jet programme, alt jobs japan, eikaiwa jobs, instructor visa japan, specialist in humanities visa, certificate of eligibility japan, english teacher salary japan',
                'meta_title' => 'How to Get an ESL Teaching Job in Japan (2026 Guide)',
                'meta_description' => 'How to get an ESL teaching job in Japan in 2026: JET, ALT and eikaiwa routes, the degree rule behind the visa, the CoE timeline and official JET pay.',
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
            ['name' => 'Japanese Schools, Boards of Education & Language Schools (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'jp-english-teacher-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Japan'],
            ['area' => 'Nationwide', 'country' => 'Japan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'English Teacher — ALT, Eikaiwa and Language School Roles, Japanese Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'School hours for ALT roles; afternoons, evenings and weekends for eikaiwa',
                'language' => 'English',
                // JET publishes its own scale, but dispatch and eikaiwa pay is
                // set contract by contract, so no single band is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'English teaching roles in Japanese public schools and private language schools. A degree is the usual visa requirement, and the employer applies for your Certificate of Eligibility.',
                'seo_keywords' => 'esl teaching jobs japan, alt jobs japan, eikaiwa jobs, jet programme, english teacher japan visa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Boards of education, dispatch companies, private conversation schools (eikaiwa) and international schools across Japan hire English teachers every year. Most roles need no Japanese, but almost all of them need a visa that turns on your degree, so check that first.</p>

<h3>What the work involves</h3>
<p>As an Assistant Language Teacher (ALT), you co-teach English lessons with a Japanese teacher in a public elementary, junior high or high school. At an eikaiwa, you teach small classes of children and adults, often in the afternoons, evenings and at weekends.</p>

<h3>Requirements</h3>
<ul>
    <li>A bachelor's degree in any subject, which the usual work visas are built around</li>
    <li>Native-level English; for school teaching under the Instructor status, twelve years of education in English</li>
    <li>A TEFL certificate is often preferred but is not a legal requirement</li>
    <li>For the JET Programme, citizenship of the country you apply from</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>JET Programme.</strong> 4.02 million yen in the first year, 4.14 million in the second, 4.26 million in the third and 4.32 million in the fourth and fifth</li>
    <li><strong>Dispatch ALT and eikaiwa contracts.</strong> Set company by company; there is no official survey, so compare the monthly figure, the hours and whether you are enrolled in employee health insurance and pension</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> visa eligibility, processing times and fees are set by the Immigration Services Agency of Japan, and JET terms by the JET Programme &mdash; not by JobGader. Confirm the current position with the employer and on the agency's website before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To get an ESL teaching job in Japan you need a bachelor's degree in any subject, native-level English and an employer willing to sponsor you.</strong> A 120-hour TEFL certificate helps but is not a legal requirement, and you do not need to speak Japanese. You apply to the JET Programme, a dispatch company that places Assistant Language Teachers (ALTs) in public schools, or a private conversation school (eikaiwa). The employer then applies for your Certificate of Eligibility, which takes one to three months, and you collect the visa at a Japanese embassy or consulate at home.</p>

<p>This guide covers the three routes, the visa rule that decides who can apply, what the work actually pays, and the renewal fee change that takes effect on 1 October 2026.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jp.indeed.com/q-english-teacher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127471;&#127477; Browse English Teacher Jobs in Japan &rarr;
    </a>
</div>

<h2>The Three Routes Into English Teaching in Japan</h2>

<p>Which route you choose decides your employer, your hours, your visa and, above all, your pay.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Where you teach</th>
            <th style="padding:10px;text-align:left;">Usual visa status</th>
            <th style="padding:10px;text-align:left;">Pay</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>JET Programme</strong></td><td style="padding:10px;">Public schools, as an ALT</td><td style="padding:10px;">Instructor</td><td style="padding:10px;">4.02 million yen in year one (published)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Dispatch or direct-hire ALT</strong></td><td style="padding:10px;">Public schools, placed by a company or board of education</td><td style="padding:10px;">Instructor for school teaching</td><td style="padding:10px;">Set by each contract</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Eikaiwa</strong></td><td style="padding:10px;">Private conversation schools, small classes</td><td style="padding:10px;">Engineer/Specialist in Humanities/International Services</td><td style="padding:10px;">Set by each contract</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>International schools</strong></td><td style="padding:10px;">Full subject teaching for international curricula</td><td style="padding:10px;">Depends on the school type</td><td style="padding:10px;">Highest, for licensed teachers</td></tr>
    </tbody>
</table>
</div>

<p><strong>ALT roles</strong> put you in a public elementary, junior high or high school, co-teaching with a Japanese teacher of English. The JET Programme is the government-run version; dispatch companies and some boards of education hire ALTs directly as well. This is the most common first job.</p>

<p><strong>Eikaiwa</strong> are private conversation schools teaching children and adults in small groups. Expect afternoon, evening and weekend shifts, because that is when students are free.</p>

<p><strong>International schools</strong> teach full curricula in English and generally want a home-country teaching licence and classroom experience. They are a strong second or third job, not usually a first one.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-an-esl-teaching-job-in-japan-classroom.jpg"
         alt="A smiling English teacher with a lanyard pointing to a whiteboard in front of Japanese students, a pagoda, cherry blossoms and Mount Fuji through the classroom window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Visa Rule That Decides Whether You Can Apply</h2>

<p>You cannot simply arrive and teach. Each route sits under a specific <strong>status of residence</strong>, and the Immigration Services Agency of Japan sets the criteria for each.</p>

<h3>Instructor: teaching inside Japanese schools</h3>

<p>The <strong>Instructor</strong> status covers teaching at elementary, junior high, high and special-needs schools, which is why JET participants and most ALTs hold it. For a language teacher, the criteria ask for:</p>

<ul>
    <li><strong>A university degree or equivalent</strong> &mdash; or a qualifying Japanese vocational course, or a teaching licence</li>
    <li><strong>Twelve or more years of education in the language you teach</strong> &mdash; which a native English speaker educated in English meets automatically</li>
    <li><strong>Pay at least equal to what a Japanese national would receive</strong> for the same work</li>
</ul>

<h3>Engineer/Specialist in Humanities/International Services: private language schools</h3>

<p>The draft most guides copy calls this the "Specialist in Humanities/International Services visa". Its full name is <strong>Engineer/Specialist in Humanities/International Services</strong>, and the agency lists language teachers at private companies under it &mdash; which covers eikaiwa.</p>

<p>For language teaching, the rule is <strong>three years of relevant experience, waived for university graduates</strong>. That is why a degree matters so much: with one, you qualify on day one; without one, you need three years of documented language-teaching experience. The <strong>ten-year experience route</strong> that is often quoted belongs to the humanities-knowledge part of this status, not to language teaching. The same equal-pay rule applies.</p>

<p><strong>In practice</strong>, almost every employer asks for a degree regardless, because it makes the visa application straightforward. If you do not have one, the <strong>Working Holiday visa</strong> is the other route: it is open to citizens of partner countries, generally aged 18 to 30 at the time of application, and allows work only as something incidental to a holiday, so it suits a year of part-time teaching rather than a career.</p>

<h2>The Visa Process, Step by Step</h2>

<ol>
    <li><strong>Get hired.</strong> Through JET, a dispatch company, an eikaiwa or a school. The job offer comes first.</li>
    <li><strong>Your employer applies for a Certificate of Eligibility (CoE).</strong> The application is filed in Japan, usually by staff of the hiring organisation, and proves you meet the criteria for your status.</li>
    <li><strong>Wait one to three months.</strong> That is the agency's official standard processing period, not the four to eight weeks many guides quote. Since 17 March 2023 the CoE can be sent by email.</li>
    <li><strong>Apply for the visa</strong> at a Japanese embassy or consulate in your country, with your passport and the CoE. The CoE is valid for three months, so you must enter Japan within that window.</li>
    <li><strong>Receive your residence card on landing.</strong> It is issued on the spot at New Chitose, Sendai, Narita, Haneda, Chubu, Kansai, Kobe, Hiroshima, Fukuoka and Naha airports, and is valid from issue. At other ports it is posted to you later.</li>
    <li><strong>Register your address</strong> at the municipal office within <strong>14 days</strong> of settling. This is a separate legal duty &mdash; it does not "activate" the card, as some guides say.</li>
</ol>

<p><strong>Plan for about two to four months</strong> from job offer to arrival. Japanese schools start their year in <strong>April</strong>, not September, and JET participants usually arrive in <strong>late July or early August</strong>.</p>

<h3>The fee change from 1 October 2026</h3>

<p>Your first renewal will cost more than it used to. From <strong>1 October 2026</strong>, the fee to extend or change a status of residence moves from a flat <strong>6,000 yen</strong> to a scale by the length of stay granted. A one-year grant costs <strong>33,000 yen at the counter, or 27,000 yen online</strong>; a three-to-five-year grant costs 64,000 yen at the counter. Applications received by 30 September 2026 pay the old fee.</p>

<h2>What English Teachers in Japan Earn</h2>

<p>Only one of the three routes publishes its pay. The <strong>JET Programme</strong> sets a national scale:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">JET appointment year</th>
            <th style="padding:10px;text-align:left;">Annual pay</th>
            <th style="padding:10px;text-align:left;">Approx. monthly</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">First</td><td style="padding:10px;"><strong>4.02 million yen</strong></td><td style="padding:10px;">335,000 yen</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Second</td><td style="padding:10px;">4.14 million yen</td><td style="padding:10px;">345,000 yen</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Third</td><td style="padding:10px;">4.26 million yen</td><td style="padding:10px;">355,000 yen</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Fourth and fifth</td><td style="padding:10px;">4.32 million yen</td><td style="padding:10px;">360,000 yen</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">JET Programme published remuneration, before tax and deductions.</p>
</div>

<p><strong>Dispatch ALT and eikaiwa contracts</strong> are set company by company, and there is no official survey of them. Job advertisements for full-time eikaiwa roles commonly show about <strong>250,000 to 280,000 yen a month</strong>, and dispatch ALT pay varies by company and by how the school holidays are paid. <strong>International schools</strong> advertise considerably more, but only to licensed, experienced teachers. Treat all of these as advertised figures, not measured earnings.</p>

<p>We have dropped the dollar conversions many guides add: the yen has moved sharply in recent years, and a fixed dollar figure goes out of date within months. Compare offers in yen, and compare what is left after rent.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-an-esl-teaching-job-in-japan-lesson-prep.jpg"
         alt="A teacher wearing a headset planning an English lesson at a laptop beside books on TESOL and teaching English abroad, Mount Fuji and a pagoda outside the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The JET Programme: Who Can Apply</h2>

<p>JET is the best-paid and most structured entry route, and it has rules that dispatch companies and eikaiwa do not:</p>

<ul>
    <li><strong>A bachelor's degree or higher</strong>, or one obtained by the designated arrival date</li>
    <li><strong>Citizenship of the country where you apply</strong> &mdash; permanent residence is not enough</li>
    <li><strong>One-year appointments</strong>, renewable to three years, or five for participants judged exceptional</li>
    <li><strong>Arrival in late July or early August</strong>, and early April for a few countries</li>
</ul>

<p>Recruitment runs through the Japanese embassy or consulate in each participating country, and <strong>deadlines vary by country</strong>. Check your local JET page roughly a year before you want to start.</p>

<h2>Why Schools Keep Hiring English Teachers</h2>

<p>Japan's English proficiency has been falling in international comparisons. In <strong>EF EPI 2024</strong> it ranked <strong>92nd of 116</strong> countries. In the latest <strong>EF EPI 2025</strong> it slipped to <strong>96th of 123</strong>, in the "very low proficiency" band. That gap is part of why public schools, boards of education and private schools continue to recruit native English speakers across cities and rural prefectures alike.</p>

<h2>Before You Sign a Contract</h2>

<ul>
    <li><strong>Check the monthly figure against the hours.</strong> A higher salary with split shifts and Saturday classes can pay less per hour than a lower one.</li>
    <li><strong>Ask how summer and winter breaks are paid.</strong> On a dispatch ALT contract, find out in writing whether your monthly pay continues in the months schools are closed.</li>
    <li><strong>Ask whether you will be enrolled in employee health insurance and pension</strong>, or left to join National Health Insurance yourself.</li>
    <li><strong>Ask who pays the flights, the apartment deposit and the renewal fee.</strong> From October 2026 that fee is no longer a small cost.</li>
    <li><strong>Get the visa status in writing.</strong> Your contract should match the status your employer applied for.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Do I need to speak Japanese to teach English in Japan?</h3>
<p>No. ALT, eikaiwa and most international school roles hire for English ability, and JET does not require Japanese. Some private-sector roles that involve Japanese-language duties ask for it, but standard English-teaching jobs do not.</p>

<h3>What is the difference between ALT and eikaiwa jobs?</h3>
<p>An ALT co-teaches in a public elementary, junior high or high school, through JET, a dispatch company or a board of education, and usually holds the Instructor status. An eikaiwa is a private conversation school with small classes, evening and weekend shifts, and the Engineer/Specialist in Humanities/International Services status.</p>

<h3>How long does it take to get a work visa to teach in Japan?</h3>
<p>The Certificate of Eligibility takes one to three months, the agency's official standard processing period, not four to eight weeks. Add the embassy visa step and travel, and plan for about two to four months from job offer to arrival.</p>

<h3>Can I teach English in Japan without a degree?</h3>
<p>It is hard. The Instructor status needs a degree or equivalent, and language teaching under International Services needs three years of relevant experience unless you are a graduate. The ten-year experience route does not apply to language teaching. A Working Holiday visa is the realistic alternative for eligible nationalities.</p>

<h3>How much does the JET Programme pay?</h3>
<p>4.02 million yen in the first year, 4.14 million in the second, 4.26 million in the third and 4.32 million in the fourth and fifth, before tax and deductions.</p>

<h3>Is a TEFL certificate required?</h3>
<p>Not by immigration law. Many employers prefer a 120-hour TEFL certificate, and it strengthens an application with no teaching experience, but the visa turns on your degree, not your TEFL.</p>

<h3>When do teaching jobs in Japan start?</h3>
<p>The Japanese school year starts in April, so dispatch and school hiring peaks ahead of it. JET participants arrive in late July or early August. Eikaiwa hire year-round.</p>

<h3>How much does it cost to renew a Japanese work visa?</h3>
<p>From 1 October 2026 the fee depends on the length of stay granted: 33,000 yen at the counter or 27,000 yen online for one year, up from a flat 6,000 yen. Applications received by 30 September 2026 pay the old fee.</p>

<h2>People Also Search For</h2>

<h3>JET Programme salary</h3>
<p>4.02 million yen in the first year, rising to 4.32 million in the fourth and fifth.</p>

<h3>Instructor visa Japan requirements</h3>
<p>A degree or equivalent, twelve years of education in the language taught, and pay equal to a Japanese national's.</p>

<h3>Eikaiwa jobs for foreigners</h3>
<p>Private conversation school roles, usually on the Engineer/Specialist in Humanities/International Services status.</p>

<h3>Certificate of Eligibility processing time</h3>
<p>One to three months, the Immigration Services Agency's standard processing period.</p>

<h3>Teach English in Japan without a degree</h3>
<p>Difficult on a work visa; the Working Holiday visa is the main alternative for eligible nationalities aged roughly 18 to 30.</p>

<h3>ALT jobs Japan</h3>
<p>Assistant Language Teacher roles in public schools, through JET, dispatch companies or boards of education.</p>

<h3>Japan English proficiency ranking</h3>
<p>96th of 123 countries in EF EPI 2025, in the very low proficiency band.</p>

<h3>Japan visa renewal fee 2026</h3>
<p>From 1 October 2026, 33,000 yen at the counter or 27,000 yen online for a one-year grant.</p>

<h2>More Job Guides</h2>

<p>Weighing Japan against other teaching routes? These cover the neighbouring options:</p>

<ul>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; teaching English from home, what the platforms really pay, and the nationality rules.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; the UK pay scale and how overseas teachers get QTS recognised.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; state licensure and the J-1 route for teachers trained abroad.</li>
    <li><a href="/blog/teacher-jobs-in-pakistan">Teacher Jobs in Pakistan</a> &mdash; government and private school pay, and the qualifications each expects.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; what tutors earn, what Wyzant, Varsity Tutors, Sylvan, Huntington and Kumon require, and the fees and tax to expect.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration advice. Visa criteria, processing times and fees are set by the Immigration Services Agency of Japan and change over time. Confirm the current position with your employer, the JET Programme and the agency's website before applying.</p>
HTML;
    }
}
