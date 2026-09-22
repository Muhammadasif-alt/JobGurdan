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
 * "How to Apply for ASML Semiconductor Jobs in Netherlands" — one of the rare
 * employers where the overseas answer is yes. ASML is an IND-recognised
 * sponsor, it publishes salary ranges on its own vacancies, and the Dutch
 * highly skilled migrant threshold is published too.
 *
 * Putting those two published numbers side by side is what this guide does and
 * no other article seems to: ASML's cleanroom and technician bands sit BELOW
 * the IND salary threshold once holiday allowance is stripped out, so those
 * jobs cannot carry a highly skilled migrant permit. The engineering bands
 * clear it comfortably. That single comparison decides which ASML jobs a
 * reader in Pakistan should even open.
 *
 * Corrections to the draft (checked against asml.com and ind.nl, 22 September
 * 2026):
 *
 * 1. The draft says ASML publishes no salary figures. It does — an expected
 *    fixed annual range sits on each Netherlands vacancy.
 *
 * 2. The draft never tests those salaries against the IND threshold, which is
 *    the whole question for an overseas applicant. This guide does the sum.
 *
 * 3. The draft omits the 28 January 2026 restructuring: a net reduction of
 *    around 1,700 positions, predominantly in the Netherlands.
 *
 * 4. The draft presents ASML's internship allowance as general. ASML frames
 *    both the allowance and the housing allowance as EU-student entitlements
 *    and publishes nothing about non-EU students.
 *
 * 5. The draft omits that the work-study programme is taught in Dutch, which
 *    closes it to almost every overseas reader.
 *
 * 6. The draft's screening paragraph drops ASML's sector qualifier; the extra
 *    criminal-record and credit checks apply to certain vacancies only.
 *
 * 7. "ASML B.V." is not a registered entity. The IND-recognised sponsor that
 *    employs highly skilled migrants is ASML Netherlands B.V.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AsmlSemiconductorJobsNetherlandsBlogSeeder extends Seeder
{
    // The Netherlands facet is appended in the guide body. The unfiltered
    // search is used here because an ampersand cannot survive both an href
    // attribute and a database column unescaped.
    private const APPLY_URL = 'https://www.asml.com/en/careers/find-your-job';

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
        $title = 'How to Apply for ASML Semiconductor Jobs in Netherlands';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'ASML publishes a salary range on every Dutch vacancy, and the IND publishes the visa threshold. Put them together and half of ASML jobs cannot sponsor you. Here is which half, and how to tell before you apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-asml-semiconductor-jobs-in-netherlands.jpg',
                'tags' => 'asml jobs, asml careers netherlands, semiconductor jobs netherlands, asml veldhoven, highly skilled migrant netherlands, asml salary, asml internship, ind salary threshold 2026',
                'meta_title' => 'ASML Semiconductor Jobs in Netherlands: How to Apply',
                'meta_description' => 'ASML jobs in the Netherlands: published salary ranges, which roles clear the 2026 Dutch visa threshold, internships, work-study and how to apply.',
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
            ['name' => 'ASML Netherlands B.V.'],
            ['type' => 'Company', 'display_reference' => 'asml-netherlands']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Netherlands'],
            ['area' => 'Nationwide', 'country' => 'Netherlands']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Semiconductor and Engineering Roles, ASML, Netherlands',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Day work, or two-shift and five-shift rosters in the factories',
                'language' => 'English across the business; Dutch required for the work-study programme',
                // ASML publishes a different expected range on each vacancy,
                // from around EUR 47,500 for a cleanroom technician to over
                // EUR 100,000 for senior engineering. A single figure on an
                // aggregated listing would misrepresent every one of them.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Engineering, software, manufacturing and support roles at ASML in Veldhoven, Eindhoven, Oirschot and Delft. ASML publishes a salary range on each vacancy.',
                'seo_keywords' => 'asml jobs, asml careers netherlands, semiconductor jobs netherlands, asml veldhoven jobs, highly skilled migrant netherlands',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the roles ASML advertises at its Netherlands sites, not a single vacancy and not a job advertised by JobGader. Applications are made on ASML's own careers portal, which hands off to Workday.</p>

<h3>Read this before you apply</h3>
<p>ASML Netherlands B.V. is an IND-recognised sponsor, so it can hold a highly skilled migrant permit for an overseas hire. But the permit has a published salary floor, and not every ASML vacancy clears it. Cleanroom and technician bands sit below the threshold once holiday allowance is excluded; engineering bands clear it comfortably. Check the range printed on the vacancy before you apply from abroad.</p>

<h3>Where the work is</h3>
<ul>
    <li>Veldhoven &mdash; global headquarters, and ASML's biggest R&amp;D and manufacturing site.</li>
    <li>Eindhoven and Oirschot &mdash; Oirschot focuses on stage motor design and assembly.</li>
    <li>Delft &mdash; metrology and inspection solutions.</li>
</ul>

<h3>Pay</h3>
<p>ASML prints an expected fixed annual salary on its Netherlands vacancies. Examples live when we checked: Assembler Technician and Technician Cleanroom EUR 47,500 to EUR 53,500; EUV International System Install Engineer EUR 57,000 to EUR 64,000; Facility Lay out Engineer EUR 88,000 to EUR 100,000; Data Engineer EUR 88,577 to EUR 99,649. ASML states these include holiday allowance and a 13th-month payment, and exclude profit sharing and shift allowance.</p>

<h3>Shifts</h3>
<p>ASML states the EUV factory runs a two-shift schedule and the TWINSCAN factory a five-shift schedule, with a morning shift from 6.30 to 15.30 or an evening shift from 15.00 to 00.00. It notes you may work six days in a row followed by four days off, with a shift allowance on top.</p>

<h3>Benefits ASML publishes for manufacturing</h3>
<p>13th month salary, 8 per cent holiday allowance, 40 days of paid leave (27 vacation days and 13 ADV days), variable pay, pension plan, collective health insurance, employee share purchase plan and commuting allowance. ASML notes differences by salary grade and position may apply.</p>

<p>Pay, eligibility and immigration rules are set by ASML and the Dutch IND &mdash; not by JobGader. Confirm the range and requirements on the live vacancy, and never pay anyone to secure a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to ASML jobs tell you the company is hiring and wish you luck. This one is going to do something more useful: take two numbers ASML and the Dutch government both publish, put them next to each other, and tell you which ASML jobs can actually sponsor a visa and which cannot.</p>

<p>The short version, for anyone applying from outside the EU: <strong>ASML's cleanroom and technician jobs cannot carry a Dutch work visa. Its engineering jobs can.</strong> The gap is not about skill or luck. It is arithmetic, and you can check it yourself before you spend an evening on an application.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-asml-semiconductor-jobs-in-netherlands-cleanroom.jpg" alt="ASML engineer in a cleanroom suit inspecting a silicon wafer outside the ASML campus in the Netherlands" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">ASML employs more than 24,000 people in the Netherlands across Veldhoven, Eindhoven, Oirschot and Delft.</figcaption>
</figure>

<h2>Good News First: ASML Really Does Sponsor</h2>

<p>Unlike most of the employers we write up, ASML is a genuine sponsorship route. Three ASML entities appear in the Dutch IND's public register of recognised sponsors, under the labour register:</p>

<ul>
    <li><strong>ASML Netherlands B.V.</strong> (KVK 17052456) &mdash; the entity that employs highly skilled migrants.</li>
    <li>ASML Holding N.V. (KVK 17085815).</li>
    <li>ASML Trading B.V. (KVK 69631557).</li>
</ul>

<p>Note the name. If an article or a recruiter refers to "ASML B.V.", that entity does not exist in the register. Being a recognised sponsor matters because the IND states plainly: <em>"Only an employer recognised by the IND can apply for your permit."</em></p>

<p>ASML also publishes a real relocation package: <em>"We provide immigration support, and assist you with your travel arrangements and the shipping of your personal belongings. We also offer cultural awareness and language training, as well as tax advice."</em> Its rewards page adds temporary housing, house-search support, free Dutch lessons and the 30 per cent tax ruling for eligible employees.</p>

<h2>Now the Arithmetic That Decides Everything</h2>

<p>The Dutch highly skilled migrant permit has a published salary floor. For 2026 the IND requires, gross per month and <strong>excluding holiday allowance</strong>:</p>

<ul>
    <li><strong>EUR 5,942</strong> if you are 30 or older &mdash; about EUR 71,304 a year.</li>
    <li><strong>EUR 4,357</strong> if you are under 30 &mdash; about EUR 52,284 a year.</li>
    <li><strong>EUR 3,122</strong> under the reduced salary criterion, which applies only in specific graduate situations described below.</li>
    <li><strong>EUR 5,942</strong> for the EU Blue Card, or <strong>EUR 4,754</strong> under its reduced criterion for recent graduates.</li>
</ul>

<p>These are calendar-year amounts. The IND revises them every 1 January, and states the applicable amount is the one in force on the date of the application.</p>

<p>Now ASML's own published ranges. It prints an expected fixed annual salary on each Netherlands vacancy, and states each range <em>includes holiday allowance and a 13th-month payment</em>. Here is what was live when we checked, with the holiday allowance stripped out at 8 per cent so the figures are comparable to the IND rule:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">ASML role</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Published range</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Clears under-30 bar?</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Clears 30+ bar?</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Assembler Technician / Technician Cleanroom</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUR 47,500 &ndash; 53,500</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUV International System Install Engineer</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUR 57,000 &ndash; 64,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">System Install Coordinator</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUR 58,500 &ndash; 66,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Facility Lay out Engineer</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUR 88,000 &ndash; 100,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Data Engineer</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EUR 88,577 &ndash; 99,649</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes</td>
        </tr>
    </tbody>
</table>

<p><strong>Read the top row again.</strong> A cleanroom technician at the very top of ASML's published band, EUR 53,500, comes to about EUR 49,500 once holiday allowance is excluded &mdash; below even the under-30 threshold of EUR 52,284. Those jobs are open to people who already hold Dutch or EU work rights. They are not a route in from Pakistan.</p>

<p>Two honest caveats. First, this comparison is ours: ASML publishes the ranges, the IND publishes the thresholds, and we have put them together. Neither organisation states the conclusion. Second, it may be worse than the table shows. The IND counts a 13th month only if it is <em>"transferred monthly"</em> in twelve equal amounts, and ASML's rewards page describes paying it as a lump sum every December. If the IND applies that strictly, every figure above drops by a further 8 per cent or so. Ask ASML's recruiter directly rather than assuming.</p>

<h2>The Reduced Threshold, and Who Gets It</h2>

<p>The EUR 3,122 reduced criterion is the one that changes the picture for graduates, and it is widely misdescribed. It has nothing to do with university rankings. The IND says it applies in three cases:</p>

<ul>
    <li>You apply while holding an orientation year permit for highly educated persons.</li>
    <li>You held that permit, and apply within three years of graduating, of your doctoral defence, or of a research permit expiring.</li>
    <li>You never held it but meet its requirements, and apply within the same three-year window.</li>
</ul>

<p>At EUR 3,122 a month, an ASML cleanroom technician role would clear the bar. That is the realistic sequence for a Pakistani graduate: study in the Netherlands, take the orientation year, then convert. Applying cold from Pakistan into a technician vacancy does not work.</p>

<h2>Where ASML Actually Is</h2>

<p>ASML's headquarters is in <strong>Veldhoven</strong>, and it is the company's biggest R&amp;D and manufacturing site &mdash; 19,000-plus employees, 121 nationalities, over 225,000 square metres of office space on its own figures.</p>

<p>ASML states: <em>"We also have locations in nearby Eindhoven and Oirschot, and further north in Delft. The Netherlands is where most of our R&amp;D and operations are based, and it is home to more than half of all ASML employees."</em> Against a global headcount of more than 44,000, the Dutch sites account for 24,000-plus people across 39 buildings.</p>

<ul>
    <li><strong>Oirschot</strong> &mdash; ASML's words: <em>"our teams focus on stage motor design and assembly."</em></li>
    <li><strong>Delft</strong> &mdash; <em>"our teams focus on metrology and inspection solutions."</em> A small office site, with no published headcount.</li>
</ul>

<p>One oddity worth knowing if you are checking sources: ASML's corporate locations page lists only two Dutch addresses, Veldhoven and Delft, and its own careers FAQ omits Eindhoven while the paragraph above it includes it. The careers pages are the better guide for job hunting.</p>

<h2>What Changed in 2026, and Why It Matters</h2>

<p>Two announcements sit awkwardly together, and any guide that mentions only one is selling you something.</p>

<p><strong>28 January 2026 &mdash; a restructuring with net job losses.</strong> ASML reorganised its Technology and IT organisations so that <em>"most of our engineers will be dedicated to a specific product and module"</em>, with an expected <strong>net reduction of around 1,700 positions, predominantly in the Netherlands</strong>, mostly at leadership level. ASML says it will still create new engineering roles and continue hiring in Manufacturing, Customer Support and Sales.</p>

<p><strong>8 September 2026 &mdash; a new campus.</strong> ASML broke ground on a second industrial campus at Brainport Industries Campus North in Eindhoven: eventually around 350,000 square metres with capacity for up to 20,000 workplaces, with phase one due by 2029 housing at least 3,000 employees.</p>

<p>So: fewer leadership and matrix roles, more manufacturing and engineering capacity. If you read that ASML is simply "hiring hard", the article predates January 2026.</p>

<p>One more thing to get right, because it circulates as a jobs story: <strong>Project Beethoven is not a hiring programme.</strong> It is a regional covenant on housing, infrastructure and talent, to which ASML has committed EUR 93 million for mobility works including cycle paths and a new underground bus station at Eindhoven Centraal.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-asml-semiconductor-jobs-in-netherlands-veldhoven.jpg" alt="The ASML campus in Veldhoven with wafer inspection equipment and a cleanroom production hall" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">ASML's Veldhoven headquarters is its biggest R&amp;D and manufacturing site; a second campus is being built at Eindhoven.</figcaption>
</figure>

<h2>Working in the Factories</h2>

<p>If you already have the right to work in the Netherlands, ASML's manufacturing roles are among the more approachable entries into the semiconductor industry. ASML publishes the shift structure honestly:</p>

<p style="border-left:4px solid #0b4ea2;padding:12px 18px;background:#f5f8fc;margin:22px 0;">"The EUV factory has a two-shift schedule, while TWINSCAN factory employees work in a five-shift schedule. For example, you will work the morning shift from 6.30 to 15.30 or evening shift from 15.00 to 00.00. You may work six days in a row, but that will be followed by four days off. ... You will also receive a generous shift allowance."</p>

<p>The published manufacturing package is unusually specific: <strong>13th month salary, 8 per cent holiday allowance, 40 days of paid leave, variable pay, pension plan, collective health insurance, employee share purchase plan and commuting allowance.</strong> ASML breaks the 40 days down as 27 vacation days plus 13 ADV days, pays the 13th month at 8.33 per cent of annual salary every December, and the holiday allowance at 8 per cent every June.</p>

<h2>Internships: Read the Two Words "EU Students"</h2>

<p>ASML internships last <em>"between 3 and 12 months. Most internships are 5 to 6 months long"</em>, and ASML recommends applying at least three months before your start date, with four more weeks needed for pre-onboarding.</p>

<p>The allowance is where overseas students need to pay attention. ASML's wording is: <em>"EU students receive a full-time monthly allowance depending on their education level: Vocational (mbo) students &ndash; EUR 400. Associate, Bachelor's and Master's &ndash; EUR 600."</em> The EUR 450 monthly housing allowance is framed the same way, for students living 80km or more away who relocate within 30km of Veldhoven.</p>

<p><strong>What non-EU students receive is not published anywhere on that page.</strong> ASML does not say they get less, and it does not say they get the same. The only non-EU sentence on the page is administrative: students with a non-EU nationality studying in the Netherlands must sign a tripartite agreement with their institution. If this applies to you, ask before you accept &mdash; do not assume either way.</p>

<p>The selection route is four steps: apply with a CV and motivation letter, complete a pre-recorded video interview, attend an interview, then receive an offer.</p>

<h2>The Work-Study Programme Is Taught in Dutch</h2>

<p>ASML's work-study route pays your salary and your tuition while you study one day a week &mdash; a two to three year mbo assignment or a four-year hbo assignment. ASML publishes the salary for it: <strong>EUR 2,549 to EUR 2,800</strong> a month, with full tuition and book costs reimbursed annually.</p>

<p>It is a genuinely good deal, and it is almost certainly closed to you. ASML's Dutch page answers the question directly: <em>"De studie volg je in het Nederlands"</em> &mdash; the study is in Dutch, though ASML also expects you to speak English. The English page omits this.</p>

<p>Note also that the "four days work, one day study" split is not universal: shift workers do three days work, a rest day and one school day. Most vacancies are in precision manufacturing in the cleanrooms, with some in management assistance and HR services.</p>

<h2>Screening: The Sector Detail Most Articles Drop</h2>

<p>ASML states that pre-employment screening happens <em>after</em> you have accepted the offer and agreed to be screened. The standard check covers identity and education as provided by you, public sanction lists and social media.</p>

<p>The extra checks are where the detail matters: resume, references, criminal record and credit risk checks apply <em>"for certain vacancies in the Finance, Procurement, IT, Sales, Security and Airfreight sectors"</em>. Guides that present these as universal are overstating it.</p>

<h2>How to Apply, Step by Step</h2>

<p><strong>Official application route:</strong> <a href="https://www.asml.com/en/careers/find-your-job?job_country=Netherlands&amp;job_type=Fix" rel="nofollow noopener" target="_blank">https://www.asml.com/en/careers/find-your-job?job_country=Netherlands&amp;job_type=Fix</a> &mdash; ASML's own job search, filtered to the Netherlands. The apply button hands off to Workday at asml.wd3.myworkdayjobs.com. The old asml.com/careers/vacancies board is gone and returns a 404.</p>

<ol>
    <li>Filter the job search to the Netherlands. Use job_type=Internship instead if you are a student.</li>
    <li><strong>Open the vacancy and find the expected fixed annual salary.</strong> This is the single most useful line on the page for an overseas applicant.</li>
    <li>Strip roughly 8 per cent from it for holiday allowance, then compare against the IND threshold for your age. If it does not clear, that vacancy cannot carry your permit.</li>
    <li>Check the requirements honestly, including the language expectation for the specific team.</li>
    <li>Apply through the ASML link, which takes you into Workday. Newer postings carry J-00 style job IDs.</li>
    <li>If you receive an offer, expect pre-employment screening after acceptance, not before.</li>
    <li>Raise relocation, the 30 per cent ruling and the 13th-month payment structure with the recruiter before you sign.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Does ASML sponsor work visas for the Netherlands?</h3>
<p>Yes. ASML Netherlands B.V. is listed in the IND's public register of recognised sponsors under the labour register, and ASML publishes a relocation package that includes immigration support. But the role itself must pay above the IND salary threshold, and not all of them do.</p>

<h3>Can a Pakistani engineer get a job at ASML?</h3>
<p>Yes, if the vacancy pays enough to carry a highly skilled migrant permit. ASML's engineering bands around EUR 88,000 to EUR 100,000 clear the 2026 threshold comfortably. Its cleanroom technician bands of EUR 47,500 to EUR 53,500 do not.</p>

<h3>What is the Dutch highly skilled migrant salary requirement for 2026?</h3>
<p>Gross per month excluding holiday allowance: EUR 5,942 if you are 30 or older, EUR 4,357 if under 30, and EUR 3,122 under the reduced criterion. The EU Blue Card is EUR 5,942, or EUR 4,754 under its reduced criterion. The IND revises these every 1 January.</p>

<h3>How much does ASML pay in the Netherlands?</h3>
<p>ASML prints an expected fixed annual range on each vacancy. Live examples when we checked ran from EUR 47,500 to EUR 53,500 for a cleanroom technician up to EUR 88,000 to EUR 100,000 for a facility layout engineer. The ranges include holiday allowance and a 13th month but exclude profit sharing and shift allowance.</p>

<h3>Do I need to speak Dutch to work at ASML?</h3>
<p>Generally no for professional roles, where English is the working language. But the work-study programme is studied in Dutch, and ASML expects English on top of it, so that route is effectively closed without Dutch.</p>

<h3>Where are ASML's offices in the Netherlands?</h3>
<p>Veldhoven is the headquarters and the largest R&amp;D and manufacturing site, with further locations in Eindhoven, Oirschot and Delft. Oirschot focuses on stage motor design and assembly, Delft on metrology and inspection.</p>

<h3>Is ASML still hiring after the 2026 restructuring?</h3>
<p>Yes, but selectively. ASML announced a net reduction of around 1,700 positions in January 2026, predominantly in the Netherlands and mostly at leadership level, while saying it will keep creating engineering roles and hiring in manufacturing, customer support and sales.</p>

<h3>Do non-EU students get the ASML internship allowance?</h3>
<p>ASML does not publish an answer. It frames both the monthly allowance and the housing allowance as entitlements for EU students, and says nothing about non-EU students beyond a tripartite agreement requirement. Ask the recruiter before accepting.</p>

<h2>People Also Search For</h2>

<h3>ASML careers login</h3>
<p>Job search sits at asml.com/en/careers/find-your-job and applications hand off to Workday at asml.wd3.myworkdayjobs.com. The legacy asml.com/careers/vacancies board returns a 404.</p>

<h3>ASML salary for freshers</h3>
<p>ASML does not publish a graduate scale, but it does print a range on each vacancy. The published work-study salary of EUR 2,549 to EUR 2,800 a month is the clearest entry-level figure the company puts in public.</p>

<h3>ASML Veldhoven jobs</h3>
<p>Veldhoven is the headquarters and the largest site, with over 19,000 employees and 121 nationalities on ASML's own figures. Most Dutch vacancies are based there.</p>

<h3>ASML internship for international students</h3>
<p>Internships run 3 to 12 months, most commonly 5 to 6. Apply at least three months ahead. The published EUR 400 and EUR 600 allowances are stated for EU students; non-EU treatment is not published.</p>

<h3>Netherlands work visa salary requirement</h3>
<p>For 2026 the IND requires EUR 5,942 gross per month for highly skilled migrants aged 30 or older, EUR 4,357 under 30, and EUR 3,122 under the reduced criterion, all excluding holiday allowance.</p>

<h3>IND recognised sponsor list</h3>
<p>The public register of recognised sponsors is published by the IND and lists nearly 13,000 organisations. ASML appears as ASML Netherlands B.V., ASML Holding N.V. and ASML Trading B.V., all in the labour register.</p>

<h3>30 percent ruling Netherlands</h3>
<p>ASML lists a tax benefit of up to 30 per cent among its relocation rewards for eligible employees, alongside tax advice, temporary housing and visa support for immediate family.</p>

<h3>ASML new Eindhoven campus</h3>
<p>Construction began in September 2026 at Brainport Industries Campus North, planned at around 350,000 square metres with capacity for up to 20,000 workplaces, phase one due by 2029 with at least 3,000 employees.</p>

<h2>More Job Guides</h2>

<p>If it is European engineering you are after rather than this one employer, these cover the same ground from different angles:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; and the difference between Siemens AG and Siemens Energy that trips most applicants.</li>
    <li><a href="/blog/how-to-apply-for-airbus-aerospace-jobs-in-france">How to Apply for Airbus Aerospace Jobs in France</a> &mdash; how many French vacancies need a security clearance.</li>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; published pay bands, and Italian as the real barrier.</li>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; another European factory route with a language gate.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; the wider market around these employers.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using ASML's own careers, locations, benefits and newsroom pages together with the Netherlands Immigration and Naturalisation Service published required amounts, highly skilled migrant conditions and public register of recognised sponsors, checked on 22 September 2026. The comparison between ASML's published salary ranges and the IND thresholds is our own. Vacancies, pay ranges and immigration amounts change, and IND amounts are revised every 1 January. Always check the live vacancy and the official government source before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
