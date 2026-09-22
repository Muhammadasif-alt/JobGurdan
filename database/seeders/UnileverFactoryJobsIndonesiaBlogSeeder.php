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
 * "How to Apply for Unilever Factory Jobs in Indonesia" — an employer guide
 * whose honest centre is not Unilever's hiring process. It is Indonesia's
 * foreign worker law, which requires five years of relevant experience before
 * a foreign national can hold any sponsored job at all, and which therefore
 * closes the factory floor to exactly the reader this title attracts.
 *
 * Corrections to the draft (checked against careers.unilever.com and
 * tka-online.kemnaker.go.id, 21 September 2026):
 *
 * 1. The draft's UPLIFT link, careers.unilever.com/en/indonesiaearlycareers,
 *    returns HTTP 404. Search engines still show it because they are serving
 *    a stale cache. The guide links the careers pages that answer 200.
 *
 * 2. The draft says UPLIFT runs February and August intakes. The live
 *    requisition numbering shows February and September. August could not be
 *    confirmed anywhere.
 *
 * 3. The draft lists UPLIFT benefits (monthly allowance, lunch, clinic,
 *    dental, product packages), December and June application windows and a
 *    six-step selection process. None of these could be confirmed on any live
 *    Unilever page, so none are published here.
 *
 * 4. The draft publishes no salary, which is correct: Unilever publishes no
 *    pay figure for any Indonesian role. The guide says so as a finding, and
 *    explains Indonesia's regency minimum wage mechanism instead of quoting a
 *    rupiah figure that could not be verified on a government page.
 *
 * 5. The draft is built around job R-1188275, which closes on 2 October 2026.
 *    Job-ID links are never published here; they expire. The guide names the
 *    role as an example of what Unilever Indonesia actually advertises and
 *    links the careers hub only.
 *
 * 6. The draft's factory list included Subang and nationwide distribution
 *    centres. Only Cikarang is confirmed on a live Unilever page, so the
 *    others are not stated as fact.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class UnileverFactoryJobsIndonesiaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.unilever.com/en/indonesia';

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
        $title = 'How to Apply for Unilever Factory Jobs in Indonesia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Unilever Indonesia lists no factory-floor vacancy at all right now, and Indonesian law requires five years of experience before a foreigner can hold any sponsored job. Here is what is really open, and to whom.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-unilever-factory-jobs-in-indonesia.jpg',
                'tags' => 'unilever factory jobs, unilever indonesia careers, cikarang jobs, uplift apprenticeship, indonesia work permit, rptka, factory jobs in indonesia, jobs in indonesia for foreigners',
                'meta_title' => 'Unilever Factory Jobs in Indonesia: How to Apply',
                'meta_description' => 'Unilever Indonesia factory jobs: what the careers site actually lists, why no operator roles appear, and the five-year rule that governs foreign applicants.',
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
            ['name' => 'Unilever Indonesia, Cikarang'],
            ['type' => 'Company', 'display_reference' => 'unilever-indonesia-cikarang']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Indonesia'],
            ['area' => 'Cikarang and Tangerang', 'country' => 'Indonesia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Engineering and Supply Chain, Unilever Indonesia, Cikarang Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard Indonesian working hours, set by the employer',
                'language' => 'Bahasa Indonesia and business English',
                // Unilever publishes no pay figure for any Indonesian role on
                // its careers site, including the live Cikarang posting.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the engineering, supply chain and early-career roles Unilever Indonesia advertises on its own careers site, not a single vacancy and not a job advertised by JobGader. Applications are made on Unilever's careers site, which runs on Workday. Unilever's Indonesian head office is Grha Unilever, Green Office Park, BSD City, Tangerang, and its Cikarang site in West Java is the factory location that appears on current postings.</p>

<h3>What Unilever Indonesia is actually advertising</h3>
<p>At the time of writing, every live Indonesian role on Unilever's careers site is a degree-required professional position: engineering management, finance, customs and sales, plus the UFRESH early-career intake. There is no operator, production-line or "operator produksi" vacancy listed. Unilever does advertise machine and line operator roles in other countries, so the absence here is a genuine reflection of what is open in Indonesia, not a gap in the site.</p>

<h3>Requirements on a current Cikarang posting</h3>
<ul>
    <li>A bachelor's degree in engineering, with mechanical or electrical preferred.</li>
    <li>Fluent business English.</li>
    <li>Working knowledge of Excel, PowerPoint and Power BI.</li>
    <li>Unilever states that fresh graduates with strong technical aptitude and a willingness to learn are encouraged to apply.</li>
</ul>

<h3>Pay</h3>
<p>Unilever publishes no salary figure for any Indonesian role, including the live factory postings. Indonesia sets a legally binding minimum wage by province and regency, revised each year by governor's decree, and the Cikarang figure is set for Kabupaten Bekasi. Check the current decree for the regency you would work in rather than relying on any salary estimate site.</p>

<h3>If you are not an Indonesian national</h3>
<p>Indonesia's Ministry of Manpower requires a foreign worker to hold education matching the position and at least five years of relevant experience, and requires the employer to sponsor an RPTKA and appoint an Indonesian counterpart for skills transfer. That rule applies to the job, not to the employer, so entry-level factory work is not an available route into Indonesia for a foreign national.</p>

<h3>Fraud warning</h3>
<p>Unilever states on its own postings: "We will never ask for the exchange of money or credit card details in the Recruitment process."</p>

<p>Pay, requirements, closing dates and work-permit eligibility are set by Unilever and Indonesia's Ministry of Manpower &mdash; not by JobGader. Confirm the details on the live posting and against the current regency wage decree before you apply.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you searched for Unilever factory jobs in Indonesia expecting to find production-line vacancies you could apply to, this guide is going to disagree with almost everything else written on the subject. We checked Unilever's own careers site and the Indonesian government's foreign worker portal on 21 September 2026, and two things are true at once.</p>

<p><strong>Unilever Indonesia is not advertising a single operator or production-line job right now.</strong> And <strong>Indonesian law requires five years of relevant experience before any foreign national can hold a sponsored job at all</strong>, which closes the factory floor to most people reading a page like this.</p>

<p>That is not a reason to stop reading. It is a reason to aim at what is actually open.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-unilever-factory-jobs-in-indonesia-cikarang.jpg" alt="Unilever factory workers in blue uniforms and hard hats outside a Unilever plant in Indonesia" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Unilever's Cikarang site in West Java is the factory location that appears on its current Indonesian postings.</figcaption>
</figure>

<h2>Where Do I Actually Apply?</h2>

<p>Unilever Indonesia's careers page. It runs on Workday, the same applicant system behind Unilever's global hiring, and it is the only place a real Unilever role is posted.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.unilever.com/en/indonesia" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Unilever Indonesia Careers &rarr;</a>
</p>

<p>One correction worth making immediately. Most guides to this subject, and the draft this article was built from, send you to <code>careers.unilever.com/en/indonesiaearlycareers</code> for the apprenticeship programme. <strong>That address returns a 404.</strong> Search engines still list it because they are serving a cached copy of a page that no longer exists. If you have been clicking it and getting an error, the page is gone, not your browser.</p>

<h2>What Is Unilever Indonesia Actually Listing?</h2>

<p>We counted the live Indonesian roles on Unilever's careers site. Every one of them was a degree-required professional position:</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #ddd;">Type of role listed</th>
            <th style="padding:12px;text-align:left;border:1px solid #ddd;">What it needs</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #ddd;">Engineering management (Cikarang)</td>
            <td style="padding:12px;border:1px solid #ddd;">Engineering degree, business English</td>
        </tr>
        <tr style="background:#fafafa;">
            <td style="padding:12px;border:1px solid #ddd;">Finance lead</td>
            <td style="padding:12px;border:1px solid #ddd;">Professional finance background</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #ddd;">Sales and customer roles</td>
            <td style="padding:12px;border:1px solid #ddd;">Commercial experience</td>
        </tr>
        <tr style="background:#fafafa;">
            <td style="padding:12px;border:1px solid #ddd;">UFRESH early-career intake</td>
            <td style="padding:12px;border:1px solid #ddd;">Students and fresh graduates</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #ddd;">Operator / production line</td>
            <td style="padding:12px;border:1px solid #ddd;"><strong>Nothing listed</strong></td>
        </tr>
    </tbody>
</table>

<p><strong>There is no operator-level vacancy on Unilever's Indonesian careers listing.</strong> This is worth stating plainly because it is the single most common thing promised by pages on this topic. Unilever does post literal "Machine Operator" and "Line Operator" roles in other countries, so the site is perfectly capable of carrying them. Indonesia simply does not have one open.</p>

<h2>The Five-Year Rule Nobody Mentions</h2>

<p>This is the part that decides the question for most readers, and it has nothing to do with Unilever.</p>

<p>Indonesia's Ministry of Manpower runs the foreign worker system through an employer-sponsored plan called the RPTKA. Its own portal sets out what the foreign worker must have. In the ministry's wording, a foreign worker must have <strong>education matching the qualifications of the position</strong>, must have <strong>competence or work experience of at least five years</strong> matching that position, and must <strong>transfer their expertise to an Indonesian counterpart worker</strong>.</p>

<p>Read that again with a factory job in mind. A production operator role does not require a degree and does not require five years of specialist experience, which means it cannot satisfy the test. <strong>Entry-level factory work is not a legal route into Indonesia for a foreign national.</strong> No amount of applying changes that, and any agent who tells you otherwise is selling you something.</p>

<p>If you are an Indonesian national or already hold the right to work in Indonesia, none of this applies to you and the careers site is simply where you apply.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-unilever-factory-jobs-in-indonesia-line.jpg" alt="Workers in Unilever branded uniforms inspecting products on a production line inside an Indonesian factory" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Production roles exist inside Unilever's Indonesian plants. They are just not what the careers site is advertising to outside applicants right now.</figcaption>
</figure>

<h2>What About the UPLIFT Apprenticeship?</h2>

<p>UPLIFT is Unilever Indonesia's apprenticeship programme for students and fresh graduates, and it is the closest thing to an entry route the company runs. We have to be careful here, because its page is currently down.</p>

<p>What we could confirm from live Unilever requisition data: the programme runs <strong>February and September intakes</strong>. A great many guides say February and August. We could find no evidence of an August intake anywhere on Unilever's systems.</p>

<p>What we could <em>not</em> confirm, and therefore do not publish as fact: the specific monthly allowance and benefits list that circulates widely, the claim that applications open in December and June, and the exact six-step selection process. Those may well be accurate, but the only page that carried them is returning a 404, and we do not repeat things we cannot check.</p>

<p>The live early-career listing for Indonesia currently shows the <strong>UFRESH</strong> intake rather than UPLIFT. If you are a student or fresh graduate in Indonesia, that is the one to watch.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.unilever.com/en/early-careers" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Unilever Early Careers &rarr;</a>
</p>

<h2>What Does It Pay?</h2>

<p><strong>Unilever publishes no salary figure for any Indonesian role.</strong> Not on the Cikarang engineering posting, not on the early-career intake, not anywhere on its careers site. Every rupiah figure you will find for "Unilever Indonesia salary" comes from a salary-estimate site, and those are guesses built from user submissions.</p>

<p>What is real is the legal floor. Indonesia sets a minimum wage by province (UMP) and by regency or city (UMK), revised every year by governor's decree. Cikarang sits in Kabupaten Bekasi, which sets its own UMK, and Surabaya sets another. These are published decrees, not estimates, and they are the only defensible number in this conversation. Look up the current decree for the regency you would actually work in.</p>

<h2>How Do I Apply, Step by Step?</h2>

<ol>
    <li><strong>Open the Indonesia careers page</strong> and read what is genuinely listed today rather than what a guide told you would be there.</li>
    <li><strong>Check the requirement line first.</strong> If it asks for a degree and you do not have one, that role is closed regardless of how well you write the application.</li>
    <li><strong>Apply through Workday</strong>, which the job page links to. Unilever does not accept applications by email or WhatsApp.</li>
    <li><strong>Set a job alert</strong> so an operator role, if one is ever posted, reaches you the day it appears.</li>
    <li><strong>If you are applying from outside Indonesia</strong>, check the five-year rule against the specific role before spending time on it.</li>
    <li><strong>Never pay anyone.</strong> See the next section.</li>
</ol>

<h2>The Scam Warning, in Unilever's Own Words</h2>

<p>Unilever prints this on its job postings, and it is worth quoting exactly because "Unilever factory jobs Indonesia" is a phrase scammers target:</p>

<blockquote style="border-left:4px solid #b3151a;padding:12px 18px;margin:20px 0;background:#fafafa;color:#374151;">
    "We will never ask for the exchange of money or credit card details in the Recruitment process. Please be aware of any suspicious email activity from people who could be pretending to be recruiters or senior individuals at Unilever. If in doubt, please ignore the message."
</blockquote>

<p>A real Unilever role is on careers.unilever.com and the application happens in Workday. Anything else, including a recruiter offering you a Cikarang factory job for a fee, is not Unilever.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does Unilever Indonesia hire factory operators?</h3>
<p>It employs them, but it is not advertising any operator or production-line vacancy on its careers site at the time of writing. Everything listed for Indonesia is a degree-required professional role or the early-career intake.</p>

<h3>Can a foreigner get a Unilever factory job in Indonesia?</h3>
<p>Realistically, no. Indonesia's Ministry of Manpower requires a foreign worker to have education matching the position and at least five years of relevant experience, which an entry-level factory role cannot satisfy.</p>

<h3>Why does the UPLIFT link not work?</h3>
<p>The address most guides publish, careers.unilever.com/en/indonesiaearlycareers, returns a 404. Search results still show it from a cached copy. Use Unilever's early careers page instead.</p>

<h3>Are UPLIFT intakes in February and August?</h3>
<p>February and September, based on Unilever's live requisition data. We could find no evidence of an August intake.</p>

<h3>How much does a Unilever factory job pay in Indonesia?</h3>
<p>Unilever publishes no pay figure for any Indonesian role. The only reliable number is the legally binding regency minimum wage, set annually by governor's decree.</p>

<h3>Where are Unilever's Indonesian factories?</h3>
<p>Cikarang in West Java is the site named on current postings, and the head office is Grha Unilever in BSD City, Tangerang. Other sites are widely reported but were not confirmable on a live Unilever page when we checked.</p>

<h3>Do I need to speak English?</h3>
<p>The current Cikarang posting asks for fluent business English alongside Bahasa Indonesia.</p>

<h3>Does Unilever charge anything to apply?</h3>
<p>No. Unilever states it will never ask for money or credit card details during recruitment.</p>

<h2>People Also Search For</h2>

<h3>Unilever Indonesia careers</h3>
<p>The official page is careers.unilever.com/en/indonesia. It lists every Indonesian role Unilever is genuinely hiring for.</p>

<h3>Lowongan kerja Unilever Cikarang</h3>
<p>Cikarang is the West Java factory site named on Unilever's current engineering posting. Operator vacancies are not currently listed there.</p>

<h3>UPLIFT Unilever</h3>
<p>Unilever Indonesia's apprenticeship for students and fresh graduates, running February and September intakes. Its dedicated page is currently returning a 404.</p>

<h3>UFRESH Indonesia</h3>
<p>The early-career intake currently appearing on Unilever's Indonesian listing, open to students and fresh graduates.</p>

<h3>RPTKA Indonesia</h3>
<p>The employer-sponsored foreign worker plan required before any foreign national can work in Indonesia, administered by the Ministry of Manpower.</p>

<h3>Indonesia work permit requirements</h3>
<p>Education matching the position, at least five years of relevant experience, and a duty to transfer skills to an Indonesian counterpart worker.</p>

<h3>UMK Bekasi</h3>
<p>The regency minimum wage covering Cikarang, set annually by governor's decree. It is the only verifiable pay floor for factory work in the area.</p>

<h3>Unilever job scam</h3>
<p>Unilever says it will never ask for money or credit card details in recruitment. Real roles appear only on its careers site and are processed in Workday.</p>

<h2>More Job Guides</h2>

<p>Comparing manufacturing employers, or looking for a route that is actually open to you? These cover the ground:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; a country with a real skilled-worker route for foreigners.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; general factory work and what it actually pays.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; another employer that publishes no pay, in a quota system.</li>
    <li><a href="/blog/how-to-apply-for-amazon-fulfillment-center-jobs-in-usa">How to Apply for Amazon Fulfillment Center Jobs in USA</a> &mdash; a large employer that does publish its starting pay.</li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; engineering work in a country built around foreign hiring.</li>
    <li><a href="/blog/how-to-apply-for-telkom-indonesia-it-jobs">How to Apply for Telkom Indonesia IT Jobs</a> &mdash; and the national ID requirement that closes the door first.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Unilever's own careers site and the Indonesian Ministry of Manpower's foreign worker portal, checked on 21 September 2026. Unilever publishes no pay figure for Indonesian roles, its listings change constantly, and Indonesian wage decrees are reissued every year. Always check the live posting and the current decree before acting.</p>
HTML;
    }
}
