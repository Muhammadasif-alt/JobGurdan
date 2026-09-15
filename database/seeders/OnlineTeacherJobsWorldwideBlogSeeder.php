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
 * "Online Teacher Jobs Worldwide" — the global remote-teaching guide, priced
 * from BLS tutor pay and the platforms' own published rates, and written for
 * an audience that is largely outside the US/UK "native speaker" shortlist.
 *
 * Corrections to the draft (checked 15 September 2026 against BLS OEWS May
 * 2025, the platforms' own pages, China's 2021 policy and the FTC):
 *
 * 1. "ESL platforms typically $10-25 per hour." The fixed-rate platforms sit
 *    at the bottom and pay per active minute, not per hour: Cambly pays
 *    $0.17 a minute ($10.20 an hour) and Cambly Kids $0.20 ($12), for time in
 *    lessons only. The $20-25 top used to come from teaching children in
 *    China, which effectively ended in 2021.
 *
 * 2. It treats the China ESL market as current. China's Double Reduction
 *    policy of 24 July 2021 banned for-profit core-subject tutoring for grades
 *    1-9 and the use of foreign-based tutors; VIPKid ended its China program,
 *    with the last classes on 19 October 2021.
 *
 * 3. It is silent on nationality. Marketplaces such as Preply and iTalki are
 *    open to teachers of any nationality, but many child-focused platforms
 *    still require native-speaker status from a short list of countries plus a
 *    degree, which excludes most of this guide's readers.
 *
 * 4. "Accredited TEFL/TESOL." There is no global regulator of TEFL; anyone can
 *    call a course accredited. The common baseline is a 120-hour certificate.
 *
 * 5. "K-12 salaried, comparable to traditional teaching." True, but accredited
 *    US online public schools require a state teaching licence, which is a real
 *    barrier for overseas applicants.
 *
 * 6. Its apply link searches Indeed with a query string rather than the site's
 *    own online teacher search page.
 *
 * The banners are used as supplied; the text carries the corrections. Both
 * records use updateOrCreate, so re-running is safe; it overwrites admin-panel
 * edits to these two rows.
 */
class OnlineTeacherJobsWorldwideBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-online-teacher-jobs.html';

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
        $title = 'Online Teacher Jobs Worldwide';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Online teaching is real, but pays less than the ads suggest: Cambly pays $0.17 a minute, marketplaces let you set your own rate, and the high-paying "teach kids in China" market ended in 2021. Here is what pays, and who each platform hires.',
                'content' => $content,
                'featured_image' => 'blogs/online-teacher-jobs-worldwide.jpg',
                'tags' => 'online teacher jobs worldwide, teach english online, online tutoring jobs, esl teacher jobs, tefl jobs online, preply italki cambly, online teaching no degree, remote teaching jobs',
                'meta_title' => 'Online Teacher Jobs Worldwide 2026: Real Pay and Platforms',
                'meta_description' => 'Online teacher jobs worldwide: what Cambly, Preply and iTalki really pay, why the China ESL boom ended, nationality rules, TEFL facts and where to apply.',
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
            ['name' => 'Online Teaching Platforms & Tutoring Marketplaces (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'global-online-teaching-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Remote (Worldwide)'],
            ['area' => 'Work from anywhere', 'country' => 'Worldwide']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'freelancing-online-work'],
            ['name' => 'Freelancing & Online Work']
        );

        Job::updateOrCreate(
            [
                'position' => 'Online Teacher — ESL, Subject Tutoring and Test Prep on Global Platforms',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Contract',
                'job_type' => 'Remote',
                'work_hours' => 'Flexible; you set availability around your students\' time zones',
                'language' => 'English',
                // Pay is set per platform and, on marketplaces, by the teacher,
                // so no single range is quoted; the guide cites platform rates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Online teaching roles on global platforms: teach English, tutor school and university subjects, or prepare students for exams, from anywhere.',
                'seo_keywords' => 'online teacher jobs, teach english online, online tutor jobs, esl online jobs, tefl online jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Global platforms and tutoring marketplaces hire teachers to work with students anywhere in the world &mdash; teaching English, tutoring school and university subjects, preparing students for exams such as IELTS and the SAT, and running professional training, live over video or through self-paced courses.</p>

<h3>What the work involves</h3>
<p>Live one-to-one or small-group lessons over video, or building and selling recorded courses. You set your availability around your students' time zones, teach from your own materials or the platform's, and are rated on how well students progress.</p>

<h3>Requirements</h3>
<ul>
    <li>For English teaching, a 120-hour TEFL/TESOL certificate is the common baseline; some platforms also want a degree</li>
    <li>Subject expertise for tutoring, and demonstrated knowledge for test prep</li>
    <li>A stable connection, a quiet space, a webcam and a headset</li>
    <li>Fluent English; language platforms may require native or near-native fluency</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Fixed-rate platforms</strong> pay per active minute: Cambly pays $0.17 a minute ($10.20 an hour) and Cambly Kids $0.20 ($12), for lesson time only</li>
    <li><strong>Marketplaces</strong> such as Preply and iTalki let you set your own rate and take a commission</li>
</ul>

<h3>Before you apply</h3>
<p><strong>No legitimate platform charges you to start teaching.</strong> Check the platform's nationality and qualification rules before you invest time in a demo lesson.</p>

<p><strong>Note:</strong> pay and eligibility are set by each platform &mdash; not by JobGader. Confirm the current terms on the platform's own site before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Online teaching is a genuine global career: from a laptop you can teach English to a student in another country, tutor maths, or build a course that sells while you sleep. But the market has changed a lot since the boom years, and the pay is usually lower than the adverts suggest. Before you pay for a certificate or record a demo lesson, it helps to know what each type of platform really pays, which market disappeared, and &mdash; if you are not from the US or UK &mdash; who will actually hire you.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-online-teacher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128218; Browse Online Teacher Jobs &rarr;
    </a>
</div>

<h2>The Types of Online Teaching</h2>

<ul>
    <li><strong>Teaching English (ESL).</strong> The biggest category, from conversation practice to structured lessons.</li>
    <li><strong>Subject tutoring.</strong> One-to-one help in maths, science, coding or languages.</li>
    <li><strong>Test preparation.</strong> IELTS, TOEFL, SAT and GRE coaching, which pays more where the stakes are high.</li>
    <li><strong>K-12 online teaching.</strong> Delivering school curriculum through virtual schools &mdash; usually a salaried role that needs a teaching licence.</li>
    <li><strong>University and adjunct teaching.</strong> Online courses through accredited institutions, needing a relevant degree.</li>
    <li><strong>Course creation.</strong> Building and selling pre-recorded courses rather than teaching live.</li>
</ul>

<h2>What Online Teaching Really Pays</h2>

<p>Pay depends entirely on the model. There are two, and they pay very differently.</p>

<h3>Fixed-rate platforms (they set the price)</h3>
<p>These pay a set rate, and usually <strong>per active minute in a lesson</strong>, not per hour of your time:</p>
<ul>
    <li><strong>Cambly:</strong> $0.17 a minute, which is <strong>$10.20 an hour</strong>, and $0.20 a minute ($12 an hour) on Cambly Kids. You are only paid for time in lessons, so waiting between bookings is unpaid and your real hourly rate is lower.</li>
    <li><strong>Lingoda</strong> does not publish its teacher rate; third-party sources put it around $12 an hour for group classes.</li>
</ul>

<h3>Marketplaces (you set the price)</h3>
<p>On <strong>Preply</strong> and <strong>iTalki</strong> you set your own rate and the platform takes a cut:</p>
<ul>
    <li><strong>Preply</strong> takes a commission of <strong>18% to 33%</strong> of each lesson &mdash; 33% for new tutors, falling as you teach more hours &mdash; and takes <strong>100% of your first trial lesson</strong> with each student, so you earn nothing on it. English tutors commonly list $15 to $25 an hour.</li>
    <li><strong>iTalki</strong> also lets you set your rate, with two tiers: Community Tutor, with lighter requirements, and Professional Teacher, which needs an accredited certificate or a degree.</li>
</ul>

<p>For a US anchor on tutoring pay generally, the Bureau of Labor Statistics tracks tutors (SOC 25-3041) with a May 2025 <strong>median of $43,350 a year</strong> (about $20.84 an hour), the lowest-paid tenth under $29,430 and the top tenth over $75,990. Specialised, test-prep and STEM tutoring reaches the top end; the big ESL platforms sit near the bottom.</p>

<h2>The Market That Disappeared</h2>

<p>Older guides quote $14 to $22 an hour for teaching English to children in China, and treat it as the standard. That market is gone. On <strong>24 July 2021</strong>, China's "Double Reduction" policy banned for-profit tutoring in core school subjects for grades 1 to 9 and prohibited companies from using <strong>foreign-based tutors</strong> to teach children in China. <strong>VIPKid</strong>, once worth billions, ended its China programme, with the last classes on <strong>19 October 2021</strong>. The high-paying end of "teach English online" largely went with it, which is why today's fixed-rate platforms pay $10 to $12 an hour rather than $20-plus.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/online-teacher-jobs-worldwide-classroom.jpg"
         alt="An online teacher wearing a headset waving to a video class of four children on her laptop, beside an Online Teacher Jobs Worldwide banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Each Platform Will Actually Hire</h2>

<p>This is the part most guides skip, and it matters most if you are not from the US, UK, Canada, Ireland, Australia or New Zealand:</p>

<ul>
    <li><strong>Marketplaces (Preply, iTalki, Verbling) are open to any nationality.</strong> You build a profile and students choose you, so non-native speakers and teachers from Pakistan, India and elsewhere can and do teach on them &mdash; often teaching a subject, or English to beginners, or their own first language.</li>
    <li><strong>Many child-focused ESL platforms still require native-speaker status</strong> from that short list of countries, plus a degree. If a platform advertises "native speakers only", it means it, and no certificate changes it.</li>
</ul>

<p>The practical route for most of this guide's readers is a marketplace where you set your own rate and compete on quality, not a company that filters by passport.</p>

<h2>Qualifications</h2>

<ul>
    <li><strong>TEFL/TESOL.</strong> A <strong>120-hour certificate</strong> is the common baseline for teaching English. There is <strong>no global regulator</strong> of TEFL, so "accredited" means different things on different courses &mdash; check what body actually backs it before you pay.</li>
    <li><strong>A degree.</strong> Required by many ESL platforms and virtual schools, though not by all marketplaces.</li>
    <li><strong>A teaching licence.</strong> Needed for accredited K-12 online schools, and for US virtual public schools that means a US state licence &mdash; a real barrier for overseas applicants.</li>
    <li><strong>Subject expertise.</strong> For tutoring, demonstrated knowledge or an academic background in the subject.</li>
    <li><strong>Reliable technology.</strong> A stable connection, a quiet room, a webcam and a headset are checked in the demo.</li>
</ul>

<h2>Skills That Help Online Teachers Succeed</h2>

<ul>
    <li>Explaining clearly through a screen</li>
    <li>Patience and adaptability across cultures and languages</li>
    <li>Comfort with video tools and quick technical fixes</li>
    <li>Engaging delivery that holds attention online</li>
    <li>Time management across platforms and time zones</li>
    <li>Cultural sensitivity with an international student base</li>
</ul>

<h2>Where to Find Online Teaching Jobs</h2>

<ol>
    <li><strong>Tutoring and ESL platforms.</strong> Preply, iTalki, Cambly and similar connect you directly with students.</li>
    <li><strong>Job boards.</strong> Search "online tutor", "online ESL teacher" and "remote teacher", not only "online teacher".</li>
    <li><strong>Virtual school careers pages.</strong> Accredited online schools post licensed teaching roles directly.</li>
    <li><strong>University job postings.</strong> Adjunct and part-time online roles appear on standard faculty boards.</li>
    <li><strong>Course platforms.</strong> For self-paced teaching, course marketplaces let you publish and sell.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/online-teacher-jobs-worldwide-lesson.jpg"
         alt="A student in headphones taking notes during an online lesson with a teacher on the laptop screen, beside an Online Teacher Jobs Worldwide banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Avoiding Scams</h2>

<p>The Federal Trade Commission's rule is simple: a legitimate employer or platform <strong>never asks you to pay upfront</strong> &mdash; not for training materials, equipment, or a "registration". Be wary of offers made by text, requests to move to a personal account, and pay far above the platform norms. Apply through the platform's own site, and report suspected scams at ReportFraud.ftc.gov.</p>

<h2>How Big Is the Market?</h2>

<p>Online learning is growing. One widely cited market-research estimate (Grand View Research, not an official statistic) puts the global online language-learning market at about <strong>$22 billion in 2024</strong>, projected to reach <strong>$54.8 billion by 2030</strong> at roughly 16.6% a year. Treat the exact figure with caution, but the direction &mdash; more students learning online each year &mdash; is real, and it is why platforms keep hiring.</p>

<h2>Career Progression</h2>

<p>Online teaching can lead to senior or lead tutor roles, curriculum development, mentoring new instructors, educational content creation, or coordinating an online programme. Many teachers use it as a flexible complement to classroom work; others build a full-time client base on a marketplace and move up by specialising in test prep or a high-demand subject, where the rates are highest.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do online teachers earn?</h3>
<p>It depends on the model. Fixed-rate platforms like Cambly pay $0.17 a minute ($10.20 an hour); on marketplaces such as Preply and iTalki you set your own rate, commonly $15 to $25 an hour for English, minus commission.</p>

<h3>Can I teach English online without being a native speaker?</h3>
<p>Yes, on marketplaces like Preply and iTalki, which are open to any nationality. Many child-focused platforms still require native-speaker status from a short list of countries, plus a degree.</p>

<h3>Do I need a degree to teach online?</h3>
<p>Not on all marketplaces, but many ESL platforms and virtual schools require one. A 120-hour TEFL certificate is the common baseline for teaching English.</p>

<h3>Is teaching English to students in China still an option?</h3>
<p>No. China's 2021 Double Reduction policy banned for-profit core-subject tutoring for grades 1 to 9 and the use of foreign-based tutors; VIPKid ended its China programme that year.</p>

<h3>What is the best platform to start on?</h3>
<p>For teachers outside the US/UK, a marketplace such as Preply or iTalki, where you set your rate and compete on quality rather than being filtered by nationality.</p>

<h3>Is a TEFL certificate worth it?</h3>
<p>It is the common baseline for English teaching and required by many platforms. Because TEFL is unregulated, check which body backs a course before paying.</p>

<h3>Can I teach K-12 online from another country?</h3>
<p>Rarely. Accredited US online public schools require a US state teaching licence and work authorisation, which most overseas applicants do not have.</p>

<h3>Do online teaching platforms charge a fee to join?</h3>
<p>No legitimate one does. Any platform or "employer" asking you to pay for training, materials or registration is a scam.</p>

<h2>People Also Search For</h2>

<h3>Teach English online jobs</h3>
<p>The largest category; fixed-rate platforms pay around $10 to $12 an hour, marketplaces let you set your rate.</p>

<h3>Online tutoring jobs</h3>
<p>Subject and test-prep tutoring, with a BLS tutor median near $20.84 an hour and specialised work paying more.</p>

<h3>Preply vs iTalki</h3>
<p>Both marketplaces where you set your rate; Preply's commission runs 18% to 33% and takes the first trial lesson.</p>

<h3>Cambly teacher pay</h3>
<p>$0.17 a minute ($10.20 an hour), $0.20 on Cambly Kids, for active lesson time only.</p>

<h3>Online teaching jobs no degree</h3>
<p>Possible on marketplaces and as a community tutor on iTalki; many other platforms require a degree.</p>

<h3>TEFL jobs online</h3>
<p>Need a 120-hour certificate as a baseline; TEFL is unregulated, so check the accreditation.</p>

<h3>Online teaching jobs for Pakistani teachers</h3>
<p>Marketplaces open to any nationality are the realistic route; native-only platforms exclude most applicants.</p>

<h3>Is VIPKid still hiring?</h3>
<p>Its China children's programme ended in 2021; the old high-paying China ESL model is gone.</p>

<h2>More Job Guides</h2>

<p>Looking at teaching and remote work more widely? These cover it:</p>

<ul>
    <li><a href="/blog/teacher-jobs-in-pakistan">Teacher Jobs in Pakistan</a> &mdash; classroom teaching pay and the routes into it at home.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; US teaching pay and the licence it requires.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; the UK route and its pay scale.</li>
    <li><a href="/blog/online-jobs-in-pakistan">Online Jobs in Pakistan</a> &mdash; freelancing and remote work, and how payment from abroad really works.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; another remote path with global clients.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers advice. Platform rates, commissions, nationality rules and market figures change. Confirm the current terms on each platform's own site before you apply or pay for any certification.</p>
HTML;
    }
}
