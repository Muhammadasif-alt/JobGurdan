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
 * "Teaching Jobs in UK" — a guide written around the fact that the United
 * Kingdom does not have one teaching profession. It has four, with different
 * regulators, different pay scales and, in Scotland's case, a qualification
 * that does not recognise the English one.
 *
 * Corrections to the draft:
 *
 * 1. It gives newly qualified teachers "£28,000 to £32,000". That is below
 *    the statutory minimum and has been for years. The 2025/26 main pay range
 *    starts at £32,916 outside London, and the accepted 2026/27 award takes it
 *    to £34,069, or £41,729 in inner London.
 *
 * 2. "NQT" has not existed since 1 September 2021. The term is early career
 *    teacher, and statutory induction is two years rather than one. The
 *    training framework was renamed again in September 2025.
 *
 * 3. It gives experienced teachers "£40,000 to £50,000+". The upper pay range
 *    tops out at £52,835 outside London for 2026/27, leadership reaches
 *    £148,829, and the DfE puts the average teacher salary above £52,800.
 *
 * 4. It says QTS is needed "for state schools in England and Wales" and
 *    treats the UK as one market. Scotland does not use QTS at all: GTC
 *    Scotland registration is a legal requirement, and QTS obtained by an
 *    employment-based route can be refused outright. Northern Ireland
 *    requires GTCNI registration and runs its own scale with no M1.
 *
 * 5. It presents the PGCE as the standard route. A PGCE is an academic
 *    qualification that does not by itself confer QTS.
 *
 * 6. It says to keep a DBS check "up to date". A DBS certificate has no
 *    expiry date; it is a snapshot of the day it was issued.
 *
 * 7. It lists TEFL and supply teaching without saying that agency supply
 *    teachers are outside the STPCD entirely, or that academies and free
 *    schools set their own pay and conditions.
 *
 * 8. Written for an international readership that the draft ignores: from
 *    9 September 2026 teachers trained in Ghana, India or Nigeria can no
 *    longer use the apply for QTS in England service.
 *
 * The 2026/27 figures are the STRB recommendation accepted by government in
 * full on 1 July 2026 and reproduced in the draft document. The final STPCD
 * 2026 had not been published when this was written, and the guide says so.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TeachingJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-teacher-jobs.html';

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
        $title = 'Teaching Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The starting salary every guide quotes sits below the statutory minimum, NQT stopped existing in 2021, and QTS does not let you teach in Scotland. Four nations, four regulators and four pay scales, set out separately.',
                'content' => $content,
                'featured_image' => 'blogs/teaching-jobs-in-uk.jpg',
                'tags' => 'teaching jobs uk, teacher jobs uk, qts england, ect induction, teacher salary uk, supply teacher jobs, primary teacher jobs, secondary teacher jobs',
                'meta_title' => 'Teaching Jobs in UK: Pay, QTS and the Four Nations',
                'meta_description' => 'Teaching jobs in the UK: what teachers actually start on in 2026, why QTS does not work in Scotland, and the routes that lead to it.',
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
            ['name' => 'UK Schools, Academy Trusts & Colleges (Aggregated)'],
            ['type' => 'Public', 'display_reference' => 'uk-teaching-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Teacher — Primary, Secondary and Further Education, UK Schools',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => '195 days a year, with 1265 hours of directed time for teachers on the statutory document',
                'language' => 'English',
                // Pay is statutory in England, separately negotiated in
                // Scotland and Northern Ireland, and set by the employer in
                // academies and agencies, so no single band would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Primary, secondary, SEND and further education teaching roles across the UK. Check which nation you are applying in: the qualification does not transfer.',
                'seo_keywords' => 'teaching jobs uk, teacher jobs uk, qts england, supply teacher jobs, primary teacher jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Local authority schools, academy trusts, independent schools and further education colleges across England, Scotland, Wales and Northern Ireland recruit teachers year round, with hiring concentrated before the September start and again for January and Easter appointments. Shortage subjects &mdash; maths, physics, chemistry and computing &mdash; are recruited hardest.</p>

<h3>What the work involves</h3>
<p>Planning and teaching to the relevant curriculum, assessing and tracking progress, managing behaviour, preparing pupils for GCSEs, A levels, National Qualifications or their equivalents, reporting to parents, and contributing to the wider life of the school. Teachers on the statutory document are available for 195 days, of which 190 involve teaching.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>England:</strong> qualified teacher status for local authority maintained schools; academies and free schools set their own requirements</li>
    <li><strong>Scotland:</strong> registration with the General Teaching Council for Scotland &mdash; QTS on its own is not accepted</li>
    <li><strong>Northern Ireland:</strong> registration with the General Teaching Council for Northern Ireland, which is a legal condition of employment in grant-aided schools</li>
    <li>An enhanced DBS check with barred list, or the equivalent PVG scheme membership in Scotland</li>
    <li>Subject knowledge to degree level for most secondary posts</li>
    <li>Completion of statutory induction &mdash; two years in England, and the induction route differs in each nation</li>
</ul>

<h3>What it pays</h3>
<ul>
    <li><strong>England, 2026/27:</strong> the main pay range runs <strong>&pound;34,069 to &pound;46,940</strong> outside London and <strong>&pound;41,729 to &pound;54,131</strong> in inner London</li>
    <li><strong>Scotland:</strong> the main grade scale runs <strong>&pound;43,383 to &pound;54,453</strong>, with probationers on &pound;36,159</li>
    <li><strong>Northern Ireland:</strong> the main scale runs <strong>&pound;32,916 to &pound;43,830</strong>, with no M1 point at all</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check which nation the post is in before you check anything else.</strong> The qualification, the regulator, the pay scale and the induction route all change at the border, and an English qualification does not automatically let you teach in Scotland.</p>

<p><strong>Note:</strong> pay, registration requirements and conditions are set by each nation's statutory arrangements and by individual employers &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>There is no such thing as a UK teaching qualification. There are four school systems, four regulators and four pay scales, and the differences between them are larger than most guides admit &mdash; large enough that a teacher fully qualified in England can be refused registration in Scotland. This page gives the current figures for each, corrects a starting salary that has been quoted below the legal minimum for years, and explains the routes that actually lead to the classroom.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-teacher-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128218; Browse Teaching Jobs in the UK &rarr;
    </a>
</div>

<h2>The Starting Salary Everyone Quotes Is Below the Legal Minimum</h2>

<p>The figure in circulation is <strong>"&pound;28,000 to &pound;32,000 for newly qualified teachers"</strong>. In England that is not a low estimate; it is an impossible one. The main pay range is statutory, and no maintained school may pay below its minimum.</p>

<p>Here is the <strong>main pay range</strong>, minimum to maximum, for <strong>2026/27</strong>:</p>

<ul>
    <li><strong>Rest of England &mdash; &pound;34,069 to &pound;46,940</strong></li>
    <li><strong>London fringe &mdash; &pound;35,602 to &pound;48,479</strong></li>
    <li><strong>Outer London &mdash; &pound;39,196 to &pound;52,241</strong></li>
    <li><strong>Inner London &mdash; &pound;41,729 to &pound;54,131</strong></li>
</ul>

<p>Even the previous year's statutory floor, <strong>&pound;32,916</strong> outside London for 2025/26, sits above the top of the quoted range. A teacher starting in inner London earns <strong>&pound;41,729</strong>, which is around <strong>&pound;9,700 more</strong> than the figure most guides print as a national maximum for new teachers.</p>

<p>The same applies further up. Guides give experienced teachers "&pound;40,000 to &pound;50,000+". The <strong>upper pay range</strong> outside London runs to <strong>&pound;52,835</strong>, leading practitioners to <strong>&pound;81,861</strong>, and the leadership spine to <strong>&pound;148,829</strong> outside London or <strong>&pound;158,863</strong> in inner London. The Department for Education puts the <strong>average teacher salary above &pound;52,800</strong> from September 2026.</p>

<p>One honest caveat on those 2026/27 numbers. They come from the review body's recommendation, which government <strong>accepted in full on 1 July 2026</strong> and which appears in the draft document &mdash; a <strong>3.5 per cent</strong> award, with a further <strong>3 per cent</strong> agreed for September 2027. The <strong>final statutory document had not been published</strong> when this guide was written, with consultation closing on 23 September 2026. Treat them as settled policy rather than as law until it appears.</p>

<h2>"NQT" Has Not Existed Since 2021</h2>

<p>If a careers page still says NQT, it has not been updated in five years. The term was replaced by <strong>early career teacher</strong> on <strong>1 September 2021</strong>, and the change was not cosmetic:</p>

<ul>
    <li><strong>Statutory induction is now two years, not one.</strong> That is the single biggest practical difference for anyone planning their first posts.</li>
    <li>The support framework was renamed again in <strong>September 2025</strong>, from early career framework training to the <strong>early career teacher entitlement</strong>.</li>
</ul>

<p>Using the current terms in an application is a small thing that signals you have read something written this decade.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teaching-jobs-in-uk-classroom.jpg"
         alt="A teacher helping pupils in school uniform with written work at a classroom table in the UK"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>QTS Does Not Let You Teach in Scotland</h2>

<p>This is the correction that matters most, because acting on the usual advice can cost you a year. Guides say QTS is required "for state schools in England and Wales" and leave the reader to assume the rest of the UK follows. It does not.</p>

<p><strong>Scotland.</strong> The General Teaching Council for Scotland states it plainly: holding QTS will not make you eligible to teach in Scottish schools, and registration with GTC Scotland is a <strong>legal requirement</strong> for anyone teaching there. Worse for some applicants, GTCS requires a credit-bearing initial teacher education programme &mdash; so <strong>QTS gained through an employment-based route can be refused outright</strong>. Applying from outside Scotland means the qualified outside Scotland route: an assessment taking <strong>up to three months</strong>, a <strong>&pound;185</strong> assessment fee plus <strong>&pound;59</strong> for PVG scheme membership, then an <strong>&pound;83</strong> annual registration fee. Full registration follows roughly <strong>190 days</strong> of teaching service.</p>

<p><strong>Northern Ireland.</strong> Registration with the General Teaching Council for Northern Ireland is a statutory condition of employment in grant-aided schools, at <strong>&pound;44</strong> a year. Let the fee lapse and your record closes, and with it your eligibility to teach.</p>

<p>The pay gap between the nations is the part nobody mentions:</p>

<ul>
    <li><strong>Scotland's main grade runs &pound;43,383 to &pound;54,453</strong>, with probationers on <strong>&pound;36,159</strong>.</li>
    <li><strong>Northern Ireland's main scale runs &pound;32,916 to &pound;43,830</strong>, and it has <strong>no M1</strong> &mdash; it starts at M2. Its upper scale reaches &pound;50,876.</li>
</ul>

<p>Read those together. <strong>The bottom of Scotland's main grade is above the top of Northern Ireland's</strong>, and roughly <strong>&pound;9,300 above where an English teacher starts</strong>. Scotland's top main-grade point, &pound;54,453, exceeds even the top of Northern Ireland's upper scale. Northern Ireland is also a year behind: its current award is backdated to September 2025, with no 2026/27 settlement published.</p>

<p><strong>Wales.</strong> QTS is required here too, and gained in England it is <strong>automatically recognised</strong> &mdash; but recognition is not registration. You must also register with the <strong>Education Workforce Council</strong>, which is a legal condition of doing the work, and then complete a period of induction before the EWC issues your certificate. Registration costs <strong>&pound;45 a year</strong>, and where you are on a contract your employer is obliged to deduct it from your salary. One trap for cross-border staff: <strong>QTLS is not recognised as a qualified school teacher qualification in Wales</strong>, even though it is in England.</p>

<p>Wales sets its own pay through its own statutory document, and like Northern Ireland it has <strong>no M1</strong>. From 1 September 2025 the main pay scale runs <strong>&pound;33,731 at M2 to &pound;46,595 at M6</strong>, with an upper scale of <strong>&pound;48,304 to &pound;51,942</strong> and leading practitioners from &pound;52,939 to &pound;80,478. For 2026/27 the Welsh pay review body recommended <strong>4.25 per cent</strong>; the Welsh Government said that was not affordable and consulted instead on <strong>3.5 per cent</strong>, so the 2025/26 figures above remain the operative statutory ones.</p>

<h2>A PGCE Is Not a Teaching Qualification</h2>

<p>Guides describe the route as "a degree, then a PGCE". That mis-states what each thing does. <strong>QTS is the legal requirement. A PGCE is an academic qualification, and on its own it does not confer QTS</strong> &mdash; if a course does not lead to QTS, completing it does not make you a qualified teacher. You can also gain QTS without a PGCE.</p>

<p>For 2026/27 the official structure is four core routes: <strong>undergraduate fee-funded, undergraduate salaried, postgraduate fee-funded and postgraduate salaried</strong>. The named programmes sit inside those:</p>

<ul>
    <li><strong>School Direct (salaried)</strong> is still funded and still named in the 2026/27 guidance. School Direct (fee-funded), by contrast, no longer appears there.</li>
    <li><strong>Postgraduate teaching apprenticeship</strong> &mdash; current and funded.</li>
    <li><strong>Teacher degree apprenticeship</strong> &mdash; for 2026/27 still a <strong>pilot, secondary mathematics only, capped at about 150 trainees</strong>. Guides presenting it as widely available are wrong.</li>
    <li><strong>Assessment only</strong> &mdash; the QTS route for experienced teachers who already hold a degree, and the one most overlooked by career changers already working in schools.</li>
</ul>

<p>Bursaries and scholarships remain the main financial pull into shortage subjects, but the offer moves every year: for <strong>2026/27 the maths scholarship has been withdrawn</strong> and several subjects attract <strong>no bursary at all</strong>. Check the current year's list before choosing a subject on the strength of an amount you read somewhere.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teaching-jobs-in-uk-pupils.jpg"
         alt="A teacher working through an exercise book with two primary school pupils in a bright UK classroom"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What the Contract Actually Commits You To</h2>

<p>The statutory document is specific in a way job adverts are not, and these are the numbers to know before you sign:</p>

<ul>
    <li><strong>195 days a year.</strong> 190 on which you may be required to teach, and 5 on which you may only be required to perform other duties.</li>
    <li><strong>1265 hours of directed time</strong>, allocated reasonably across those days.</li>
    <li><strong>PPA time of not less than 10 per cent of your timetabled teaching time</strong>, given in blocks of no less than half an hour during the timetabled week. This is an entitlement, not a favour.</li>
    <li>Directed time provisions <strong>do not apply</strong> to heads, deputy and assistant heads or leading practitioners.</li>
</ul>

<p>Two large exceptions the draft never mentions:</p>

<ul>
    <li><strong>Academies and free schools set their own pay and conditions.</strong> The statutory document binds local authority maintained, foundation, voluntary aided and foundation special schools. With most secondary schools now academies, the scale above is a benchmark in much of the sector rather than a guarantee.</li>
    <li><strong>Academies may still employ teachers without QTS &mdash; but not for much longer.</strong> That freedom has run since 2012. Section 53 of the <strong>Children's Wellbeing and Schools Act 2026</strong>, which received Royal Assent on 29 April 2026, extends the QTS requirement and statutory induction to specified primary and secondary academies. The Department for Education has said this applies from <strong>1 September 2027</strong>, and only to <strong>teachers employed on or after that date</strong> &mdash; it is not retrospective, and the substantive provision was not yet in force when this was written. Independent schools are unaffected.</li>
    <li><strong>Agency supply teachers are outside the document entirely.</strong> The agency sets the rate. Under the Agency Workers Regulations you become entitled to equal pay and basic conditions <strong>after 12 weeks</strong> in the same role with the same school or authority &mdash; and pay follows the job, so a qualified teacher engaged as a cover supervisor is paid as a cover supervisor.</li>
</ul>

<h2>The Checks and the Pension Behind the Salary</h2>

<p><strong>The DBS check does not expire.</strong> Guides tell you to keep it "up to date"; there is no official expiry date, and a certificate is only accurate as at the day it was issued. What keeps it portable is the <strong>Update Service</strong>. Teaching needs an <strong>enhanced check with barred list</strong>, and the fees are about to fall: from <strong>5 October 2026</strong> an enhanced check drops from <strong>&pound;49.50 to &pound;41.00</strong> and the Update Service from <strong>&pound;16 to &pound;15</strong> a year. Standard and enhanced fees are paid by the employer rather than the applicant.</p>

<p><strong>The pension is worth pricing properly</strong>, because it is a large part of what the job pays. The Teachers' Pension Scheme is a <strong>career average</strong> scheme with an accrual rate of <strong>1/57th</strong> of each year's pensionable earnings. Member contributions are banded from <strong>7.4 per cent</strong> up to <strong>12 per cent</strong> for the highest earners, and the <strong>employer contributes 28.68 per cent</strong> &mdash; a figure due to fall to 17.68 per cent from April 2027. On a &pound;34,069 salary the employer contribution alone is worth close to <strong>&pound;9,800</strong> a year.</p>

<p>On SEND, note two current facts: the terminology in England is <strong>SEND</strong>, and the <strong>National Award for SEN Co-ordination was replaced in 2024</strong> by a leadership-level national professional qualification, mandatory for SENCOs appointed from 1 September 2024, to be completed within three years of appointment.</p>

<h2>If You Trained Outside the UK</h2>

<p>England recognises teaching qualifications from a defined list of countries through the <strong>apply for QTS in England</strong> service: Australia, Canada, the EEA, Guernsey, Hong Kong, Jersey, New Zealand, Switzerland, Ukraine and the United States, plus <strong>Jamaica, Singapore and South Africa</strong> where the applicant is already working in a valid teaching role in England.</p>

<p><strong>A change on 9 September 2026 matters to many readers of this site: teachers who trained in Ghana, India or Nigeria can no longer use that service.</strong> Any guide still listing those countries is out of date.</p>

<p>Two routes remain open if your country is not on the list. <strong>Assessment only</strong> awards QTS to experienced teachers with a degree who can demonstrate the standards, and <strong>iQTS</strong> &mdash; international QTS, delivered online by English providers &mdash; leads to the same QTS and is open to non-UK citizens wherever they are.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do teachers earn in the UK?</h3>
<p>In England for 2026/27 the main pay range is &pound;34,069 to &pound;46,940 outside London and &pound;41,729 to &pound;54,131 in inner London. Scotland's main grade is &pound;43,383 to &pound;54,453 and Northern Ireland's is &pound;32,916 to &pound;43,830.</p>

<h3>Is &pound;28,000 a realistic starting salary for a UK teacher?</h3>
<p>No. It is below the statutory minimum in England, where the main pay range starts at &pound;34,069 for 2026/27 and started at &pound;32,916 the year before. No maintained school may pay below that floor.</p>

<h3>Can I teach in Scotland with QTS from England?</h3>
<p>Not automatically. GTC Scotland states that QTS does not make you eligible to teach in Scottish schools; registration with GTCS is a legal requirement. Assessment takes up to three months and QTS gained through an employment-based route may be refused.</p>

<h3>Is NQT still the correct term?</h3>
<p>No. It was replaced by early career teacher on 1 September 2021, and statutory induction is now two years rather than one. The training framework was renamed the early career teacher entitlement in September 2025.</p>

<h3>Do I need a PGCE to teach in England?</h3>
<p>No. QTS is the legal requirement; a PGCE is an academic qualification that does not by itself confer QTS. Several routes award QTS without one, including assessment only for experienced teachers with a degree.</p>

<h3>Does a DBS check expire?</h3>
<p>No. There is no official expiry date; a certificate is accurate only as at the day it was issued. Teaching requires an enhanced check with barred list, and the enhanced fee falls from &pound;49.50 to &pound;41.00 on 5 October 2026.</p>

<h3>Do supply teachers get the national pay scales?</h3>
<p>Not through an agency. Agency supply teachers sit outside the statutory pay document and the agency sets the rate. After 12 weeks in the same role with the same school or authority, the Agency Workers Regulations give equal pay and basic conditions.</p>

<h3>Can overseas teachers get QTS in England?</h3>
<p>From a defined list of countries, yes, through the apply for QTS in England service. From 9 September 2026 teachers trained in Ghana, India or Nigeria are no longer eligible for it, and should look at assessment only or iQTS instead.</p>

<h2>People Also Search For</h2>

<h3>Teacher salary UK 2026</h3>
<p>A &pound;34,069 start outside London, rising to &pound;46,940 on the main range, with an average teacher salary above &pound;52,800.</p>

<h3>How to become a teacher in the UK</h3>
<p>Gain QTS through one of four core training routes. The PGCE is optional; the QTS is not.</p>

<h3>QTS vs PGCE</h3>
<p>QTS is the legal qualification to teach. A PGCE is an academic award that does not confer it on its own.</p>

<h3>Teaching jobs in Scotland</h3>
<p>Require GTC Scotland registration rather than QTS, with a main grade scale of &pound;43,383 to &pound;54,453.</p>

<h3>Supply teacher pay UK</h3>
<p>Set by the agency, outside the statutory document, with equal treatment rights after 12 weeks in the same role.</p>

<h3>Teachers' pension contributions</h3>
<p>A career average scheme accruing at 1/57th, with member contributions from 7.4 to 12 per cent and 28.68 per cent from the employer.</p>

<h3>Teacher training bursary 2026</h3>
<p>Still the main draw into shortage subjects, but the maths scholarship has gone for 2026/27 and several subjects get nothing.</p>

<h3>Teaching jobs UK for foreigners</h3>
<p>QTS recognition covers a defined country list; Ghana, India and Nigeria left it on 9 September 2026. iQTS remains open to everyone.</p>

<h2>More Job Guides</h2>

<p>Comparing teaching and other UK routes? These cover them:</p>

<ul>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; state licensure, the exams behind it and the J-1 and H-1B routes.</li>
    <li><a href="/blog/teacher-jobs-in-pakistan">Teacher Jobs in Pakistan</a> &mdash; government and private school pay, and the qualifications each expects.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; another public service role, priced against NHS bands.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the technical route into the same institutions.</li>
    <li><a href="/blog/housekeeper-jobs-in-uk">Housekeeper Jobs in UK</a> &mdash; school and hospital support work and what it pays.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; catering roles schools recruit for, against the legal minimum.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a trade route with its own qualification ladder.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; British entry-level work and its employment-status trap.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, legal or immigration advice. Pay scales, registration requirements, training routes, bursaries and qualification recognition change, and the 2026/27 England figures were an accepted recommendation rather than a published statutory document when this was written. Confirm the current position with the Department for Education, GTC Scotland, GTCNI, the Teachers' Pension Scheme and the employer's own advertisement before applying.</p>
HTML;
    }
}
