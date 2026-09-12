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
 * "Teacher Jobs in Pakistan" — school and college teaching across the public,
 * private and Cambridge sectors. It sits beside the data entry and government
 * jobs guides, and borrows the basic-versus-gross reading of BPS pay from the
 * first rather than repeating it.
 *
 * Corrections to the draft:
 *
 * 1. It lists PTC and CT certification as required for many public sector
 *    teaching roles. Both were discontinued from the 2011-12 academic session
 *    and replaced by the Associate Degree in Education and the four-year
 *    B.Ed (Hons). A new candidate cannot obtain either certificate, so
 *    advising one to is advising a dead end.
 *
 * 2. It quotes private school teacher pay from PKR 25,000 a month. That is
 *    below the minimum wage for an unskilled worker in every province for
 *    2026-27, and saying so is more useful to a teacher at offer stage than
 *    repeating the range.
 *
 * 3. It treats "government school" as meaning government employment. Punjab
 *    has handed thousands of public schools to private operators and NGOs
 *    through the Punjab Education Foundation and PEIMA, and schools funded
 *    through the Sindh Education Foundation have been reported paying
 *    teachers a fraction of the minimum wage. The employer is the question.
 *
 * 4. It quotes government pay as PKR 30,000 to 60,000 without saying whether
 *    that is basic or gross, in the year the Revised Basic Pay Scales 2026
 *    moved allowances into basic pay.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TeacherJobsPakistanBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://pk.indeed.com/q-teacher-jobs.html';

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
        $title = 'Teacher Jobs in Pakistan';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'PTC and CT have not been awarded for over a decade, the lowest private school salary usually quoted is below the minimum wage in every province, and a job at a government school is increasingly a job with a private operator.',
                'content' => $content,
                'featured_image' => 'blogs/teacher-jobs-in-pakistan.jpg',
                'tags' => 'teacher jobs in pakistan, government teacher jobs, private school teacher salary, b.ed hons, associate degree in education, o level teacher jobs, online teaching jobs pakistan, lecturer jobs pakistan',
                'meta_title' => 'Teacher Jobs in Pakistan',
                'meta_description' => 'Teacher jobs in Pakistan: what replaced PTC and CT, the minimum wage many private schools pay below, and who really runs government schools.',
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
            ['name' => 'Pakistan Public, Private & Cambridge Schools (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'pk-teacher-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Nationwide', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Teacher — Primary, Secondary and Cambridge Schools, Pakistan Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'School hours, with preparation, marking and examination duties outside them',
                'language' => 'English, Urdu',
                // Government pay is set by grade with allowances outside basic,
                // private pay frequently falls below the provincial minimum
                // wage, and operator-run schools sit on neither scale.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Primary, secondary and Cambridge teaching roles in Pakistan. Ask who employs you and check the offer against the provincial minimum wage.',
                'seo_keywords' => 'teacher jobs in pakistan, government teacher jobs, private school teacher salary, o level teacher jobs, online teaching jobs pakistan',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Government schools, low-fee and elite private schools, Cambridge and international schools, colleges and tuition academies across Pakistan hire teachers at every level. It is one of the largest graduate employers in the country, and one where the same job title can mean a civil service post, a private contract or a position with an operator running a school on the government's behalf.</p>

<h3>What the work involves</h3>
<p>Planning and delivering lessons, managing a classroom, setting and marking assessments, preparing students for board or Cambridge examinations, and taking part in the administrative duties every school runs on. Subject specialist and senior roles add departmental responsibility; primary roles add a much wider spread of subjects.</p>

<h3>Requirements</h3>
<ul>
    <li>A bachelor's degree in the subject, or in education, depending on the level taught</li>
    <li>For new entrants, the <strong>Associate Degree in Education</strong> or the four-year <strong>B.Ed (Hons)</strong> &mdash; PTC and CT are no longer awarded</li>
    <li>A master's or MPhil for higher secondary, college and university posts</li>
    <li>For Cambridge and international schools, experience with O and A Level or another foreign curriculum</li>
    <li>Clear English and Urdu, and classroom management that holds up in a demonstration lesson</li>
    <li>For public posts, success in the written test set by the recruiting authority</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Government.</strong> Set by BPS grade, with allowances outside basic pay. The Revised Basic Pay Scales 2026 moved allowances into basic from 1 July 2026, so older charts do not compare</li>
    <li><strong>Private.</strong> Set by the school. Salaries quoted from PKR 25,000 a month are below the minimum wage for an unskilled worker in every province</li>
    <li><strong>Operator-run public schools.</strong> Teachers are employed by the operator, not the government, and are not on the BPS scale</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask who actually employs you.</strong> A post in a school with a government name may be a civil service appointment, a contract, or a job with a private operator or NGO running the school. Then read the salary against the minimum wage for your province.</p>

<p><strong>Note:</strong> pay, qualifications, contract terms and recruitment rules are set by each school and recruiting authority &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Teaching is one of the biggest graduate employers in Pakistan and one of the most respected jobs a young graduate can take. It is also a job where the qualification advice most guides give is out of date, the lowest salaries they quote are below the legal floor, and the word "government" in a school's name no longer tells you who pays you.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/q-teacher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128218; Browse Teacher Jobs in Pakistan &rarr;
    </a>
</div>

<h2>PTC and CT No Longer Exist</h2>

<p>Nearly every guide to teaching jobs in Pakistan lists "PTC/CT certification" among the requirements for public sector roles. For anyone starting out now, that is advice pointing at a door that closed years ago.</p>

<p><strong>PTC and CT were discontinued.</strong> The Primary Teaching Certificate and the Certificate in Teaching were phased out under the National Education Policy 2009, and both programmes ceased from the <strong>2011-12 academic session</strong> onwards. In their place came two linked qualifications:</p>

<ul>
    <li>The <strong>Associate Degree in Education</strong> (ADE) &mdash; two years.</li>
    <li>The <strong>B.Ed (Hons)</strong> &mdash; four years, with the ADE counting towards it.</li>
</ul>

<p>Teachers who already hold PTC or CT have not lost them, and some recruitment rules written for serving teachers still refer to them. But a new candidate cannot enrol for either, and should not pay anyone offering to arrange one. <strong>If you are planning a teaching career today, the route is the ADE or the B.Ed (Hons)</strong>, or a subject degree followed by a B.Ed for the levels that accept that combination.</p>

<p>Read every advertisement's eligibility line carefully rather than relying on a general list. Requirements differ between primary, elementary and secondary posts, between provinces, and between public and private employers &mdash; and the post you want will state its own.</p>

<h2>The Lowest Private Salary Quoted Is Below the Minimum Wage</h2>

<p>Guides put private school teacher pay at <strong>PKR 25,000 to 50,000 a month</strong>. Read the bottom of that range against the law.</p>

<p>For 2026-27, the federal budget set a minimum wage of <strong>PKR 40,700</strong> a month. The provinces set their own, and they sit around or above it: <strong>PKR 40,000</strong> in Punjab, <strong>PKR 43,000</strong> in Sindh and <strong>PKR 45,000</strong> in Khyber Pakhtunkhwa.</p>

<p>That minimum wage is set for an <strong>unskilled worker</strong>. So a teacher &mdash; a graduate, often with a professional degree on top &mdash; being offered PKR 25,000 is being offered less than the legal floor for someone with no qualifications at all. The bottom of that published range is <strong>below the minimum wage for an unskilled worker in every province</strong>.</p>

<p>That tells you something true about the market rather than something false about the guides: a great many low-fee private schools genuinely do pay this. It does not make it a fair benchmark for you. <strong>Check the offer against your province's current minimum wage</strong>, and treat anything below it as the school telling you how it values the job.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teacher-jobs-in-pakistan-private.jpg"
         alt="A teacher helping students with their work at desks in a Pakistani classroom"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>A Government School Is Not Always a Government Job</h2>

<p>This is the change that most guides have not caught up with, and it matters more than any salary figure.</p>

<p>In <strong>Punjab</strong>, the government has handed thousands of public schools to NGOs and private operators to run, through the <strong>Punjab Education Foundation</strong> and <strong>PEIMA</strong>, the Punjab Education Initiatives Management Authority. A further <strong>2,735</strong> schools were approved for outsourcing in 2025. The buildings are still public schools, and the children still attend free. But a teacher hired to work in one is hired by the <strong>operator</strong>, on the operator's terms &mdash; not onto a BPS grade, and not with a civil servant's security or pension.</p>

<p>In <strong>Sindh</strong>, schools funded through the <strong>Sindh Education Foundation</strong> work on a similar principle, and the pay can be very different from the public scale. The Express Tribune has reported teachers at such schools receiving <strong>PKR 10,000 to PKR 15,000</strong> a month &mdash; less than a third of Sindh's PKR 43,000 minimum wage.</p>

<p>None of this makes these jobs worthless. Operator-run schools can be well managed, and for a fresh graduate the classroom experience is real. But the question to ask at interview is no longer "is this a government school?" It is <strong>who actually employs you</strong>: the School Education Department on a sanctioned post, a contract with the department, or a private operator or foundation-supported school. Each answer comes with a different salary structure, different security, and a different future.</p>

<h2>Government Pay: Basic Is Not the Salary</h2>

<p>Guides quote government school teachers at <strong>PKR 30,000 to 60,000</strong> a month. For a public appointment on a BPS grade, that range does not say whether it is <strong>basic pay</strong> or what actually arrives &mdash; and in BPS, those are very different numbers, because house rent, conveyance, medical and other allowances sit outside basic.</p>

<p>There is a second complication specific to this year. The <strong>Revised Basic Pay Scales 2026</strong>, effective from 1 July 2026, merged earlier ad hoc relief allowances into basic pay. So a salary chart published before July 2026 and one published after it are not measuring the same thing, and comparing them will mislead you.</p>

<p>Our <a href="/blog/data-entry-jobs-in-pakistan">data entry jobs in Pakistan guide</a> works through exactly how to read a BPS figure, basic against gross, and what the 2026 revision changed. The same reading applies to every teaching grade.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teacher-jobs-in-pakistan-government.jpg"
         alt="A teacher leading a lesson at a chalkboard in a Pakistani school classroom"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Cambridge and International Schools</h2>

<p>The top of the private market &mdash; O and A Level and international schools &mdash; is quoted at <strong>PKR 50,000 to 100,000 or more</strong> a month, and it is genuinely a different market rather than a better-paid version of the same one.</p>

<p>What these schools screen for is specific: subject depth, fluent English in the classroom, and experience teaching to Cambridge or another foreign syllabus and its examination style. A strong demonstration lesson counts for more here than anywhere else. Karachi, Lahore and Islamabad hold most of these posts, and the best-paid of them are competitive in a way that a degree alone will not win.</p>

<p>The usual route in is through a subject specialism in demand &mdash; mathematics, the sciences, English and computer science &mdash; plus a track record of results, often built first in a good private school.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Primary school teacher.</strong> Foundational subjects across a wide range. The largest number of posts, public and private.</li>
    <li><strong>Secondary school teacher.</strong> Subject teaching for middle and high school, and board examination preparation.</li>
    <li><strong>Subject specialist.</strong> Mathematics, science, English or computer science. The shortage subjects, and the best bargaining position.</li>
    <li><strong>O and A Level teacher.</strong> Cambridge curriculum schools, the top of the private pay range.</li>
    <li><strong>Special education teacher.</strong> Students with learning or physical disabilities. Specialist training, and fewer candidates for each post.</li>
    <li><strong>Lecturer.</strong> Colleges and universities, usually requiring a master's or MPhil, with PKR 60,000 to 150,000 or more quoted depending on the institution.</li>
</ul>

<h2>Where the Posts Are</h2>

<ul>
    <li><strong>Karachi</strong> &mdash; the largest private and international school market, alongside Sindh's public and foundation-supported schools.</li>
    <li><strong>Lahore</strong> &mdash; strong demand across public, private and Cambridge schools, and the centre of Punjab's outsourcing programme.</li>
    <li><strong>Islamabad and Rawalpindi</strong> &mdash; federal schools, well-funded private institutions and international schools.</li>
    <li><strong>Faisalabad, Multan, Peshawar and other cities</strong> &mdash; steady public and private hiring at lower living costs.</li>
</ul>

<h2>Teaching Online</h2>

<p>Online tutoring through academies and platforms has grown into a real option, particularly for subject specialists in mathematics, science and English, and for teachers who can work with students abroad. The same rules apply as for any remote work: a legitimate platform or academy does not charge you a registration fee to give you students. Our <a href="/blog/online-jobs-without-investment-in-pakistan">online jobs without investment guide</a> covers how to tell the genuine ones apart.</p>

<h2>Frequently Asked Questions</h2>

<h3>What qualification do I need to become a teacher in Pakistan?</h3>
<p>A new entrant should take the Associate Degree in Education or the four-year B.Ed (Hons), or a subject degree followed by a B.Ed where the post accepts it. Each advertisement states its own eligibility, and it differs by level and province.</p>

<h3>Can I still get a PTC or CT certificate?</h3>
<p>No. PTC and CT were discontinued and ceased from the 2011-12 academic session, replaced by the ADE and the B.Ed (Hons). Existing holders keep them, but new candidates cannot enrol.</p>

<h3>How much do private school teachers earn in Pakistan?</h3>
<p>Guides quote PKR 25,000 to 50,000 a month, with O and A Level schools higher. The bottom of that range is below the minimum wage for an unskilled worker in every province, so read any offer against your province's rate.</p>

<h3>What is the minimum wage in Pakistan for 2026-27?</h3>
<p>The federal budget set PKR 40,700 a month. Provincial rates are PKR 40,000 in Punjab, PKR 43,000 in Sindh and PKR 45,000 in Khyber Pakhtunkhwa, and the provincial notification is what binds employers in that province.</p>

<h3>Is a job at a government school always a government job?</h3>
<p>No. Punjab has handed thousands of public schools to private operators and NGOs through the Punjab Education Foundation and PEIMA, and Sindh funds schools through the Sindh Education Foundation. Teachers there are employed by the operator, not placed on a BPS grade.</p>

<h3>How much do government school teachers earn?</h3>
<p>Public appointments are paid by BPS grade, and quoted figures often do not say whether they are basic pay or what arrives with allowances. The Revised Basic Pay Scales 2026 also changed how basic pay is built from 1 July 2026.</p>

<h3>What do O and A Level schools look for in a teacher?</h3>
<p>Subject depth, fluent classroom English, experience with the Cambridge syllabus and its examinations, and a strong demonstration lesson. Shortage subjects such as mathematics and the sciences give you the best position.</p>

<h3>Can I teach online from Pakistan?</h3>
<p>Yes, and it has grown into a real option for subject specialists. A genuine academy or platform will never charge you a fee to be given students.</p>

<h2>People Also Search For</h2>

<h3>Government teacher jobs 2026</h3>
<p>Advertised by each province's recruiting authority with a written test. Check whether the post is a department appointment or an operator-run school.</p>

<h3>Private school teacher salary in Pakistan</h3>
<p>Quoted from PKR 25,000, which is below every provincial minimum wage for an unskilled worker.</p>

<h3>B.Ed Hons and ADE admission</h3>
<p>The qualifications that replaced PTC and CT. The ADE takes two years and counts towards the four-year B.Ed (Hons).</p>

<h3>PEF school teacher jobs</h3>
<p>Posts with operators and NGOs running schools through the Punjab Education Foundation, on the operator's terms rather than a BPS grade.</p>

<h3>SEF school teacher salary</h3>
<p>Reported at PKR 10,000 to PKR 15,000 a month in some foundation-supported schools, under a third of Sindh's minimum wage.</p>

<h3>O Level teacher jobs in Lahore and Karachi</h3>
<p>The top of the private market, screened on subject depth, Cambridge experience and a demonstration lesson.</p>

<h3>Lecturer jobs in Pakistan</h3>
<p>Colleges and universities, usually requiring a master's or MPhil in the subject.</p>

<h3>Online teaching jobs in Pakistan</h3>
<p>A real option for subject specialists. Never pay a registration fee to be given students.</p>

<h2>More Job Guides</h2>

<p>Comparing routes for graduates in Pakistan? These cover them:</p>

<ul>
    <li><a href="/blog/government-jobs-in-pakistan">Government Jobs in Pakistan</a> &mdash; how grades, tests and advertisements work across the public sector.</li>
    <li><a href="/blog/private-jobs-in-pakistan-for-fresh-graduates">Private Jobs in Pakistan for Fresh Graduates</a> &mdash; the wider private entry market.</li>
    <li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; how to read a BPS figure, and what the 2026 pay scale revision changed.</li>
    <li><a href="/blog/online-jobs-without-investment-in-pakistan">Online Jobs Without Investment in Pakistan</a> &mdash; the honest version of working from home, including tutoring.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; remote routes that do not need a track record.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; administrative skills paid in foreign currency.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; client-facing work from home for international employers.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; a Gulf office route, and what to check in the package before accepting.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; what American teachers earn by state, and the J-1 route for teachers trained abroad.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; British pay scales and how overseas teachers get QTS recognised.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, careers or financial advice. Qualification rules, recruitment criteria, pay scales, minimum wages and school management arrangements change and differ between provinces. Confirm the current position with the official recruitment advertisement, your provincial education department and the relevant minimum wage notification before applying or accepting an offer.</p>
HTML;
    }
}
