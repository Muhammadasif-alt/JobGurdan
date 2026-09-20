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
 * "How to Apply for Ferrari Factory Jobs in Italy" — an employer guide whose
 * honest centre of gravity is not Ferrari at all. It is Italy's quota-based
 * immigration system, which decides whether a non-EU reader can take a
 * factory job here regardless of how good their application is.
 *
 * Corrections to the draft (checked against jobs.ferrari.com, Ferrari N.V.'s
 * FY2025 Form 20-F filed with the SEC, gazzettaufficiale.it,
 * integrazionemigranti.gov.it, lavoro.gov.it, Federmeccanica and ISTAT,
 * September 2026):
 *
 * 1. The draft treats Ferrari as an employer a non-EU applicant can simply
 *    apply to. Ferrari's reachable careers pages say nothing at all about
 *    visa sponsorship, work authorisation or language requirements, and
 *    Italy admits non-EU workers through an annual quota decree. The guide
 *    leads with that instead.
 *
 * 2. The draft quotes salary figures. Ferrari publishes no pay figure for
 *    any production role, so the guide publishes the metalworkers'
 *    collective agreement increases and ISTAT averages instead, labelled
 *    as what they are.
 *
 * 3. The widely repeated Ferrari employee bonus figure could not be
 *    confirmed on any Ferrari source, so it is not published here.
 *
 * 4. The draft's headcount is replaced with Ferrari's own filed figure:
 *    5,718 employees at 31 December 2025, 5,367 of them in Italy.
 *
 * 5. Job-ID links are never published; they expire. The guide links the
 *    Ferrari job search root only.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FerrariFactoryJobsItalyBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://jobs.ferrari.com/search/';

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
        $title = 'How to Apply for Ferrari Factory Jobs in Italy';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Ferrari publishes no pay for factory roles and says nothing about sponsoring non-EU workers. Italy admits them through an annual quota decree instead. Here is the real route, and how narrow it is.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-ferrari-factory-jobs-in-italy.jpg',
                'tags' => 'ferrari factory jobs, ferrari careers italy, maranello jobs, italy work visa, decreto flussi, factory jobs in italy, italy job for foreigners, ccnl metalmeccanici',
                'meta_title' => 'Ferrari Factory Jobs in Italy: How to Apply',
                'meta_description' => 'Ferrari factory jobs in Italy: the real careers portal, what Ferrari never publishes about pay or sponsorship, and how Italy quota system actually works.',
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
            ['name' => 'Ferrari, Maranello'],
            ['type' => 'Company', 'display_reference' => 'ferrari-maranello']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Italy'],
            ['area' => 'Maranello and Modena', 'country' => 'Italy']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Factory Operator, Ferrari, Maranello Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift based, under the Italian metalworkers collective agreement',
                'language' => 'Italian and English',
                // Ferrari publishes no pay figure for any production role in
                // its careers material or its filed annual report.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Production and manufacturing roles at Ferrari in Maranello and Modena, advertised through the Ferrari careers portal.',
                'seo_keywords' => 'ferrari factory jobs, maranello jobs, ferrari careers, factory jobs in italy',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Ferrari builds cars at Maranello and chassis at Modena, and advertises production, engineering, quality and support roles through its own careers portal.</p>

<h3>What the work involves</h3>
<p>Assembly, machining, quality and logistics work on low-volume, high-specification production, on shifts covered by the Italian metalworkers' national collective agreement.</p>

<h3>What the company publishes</h3>
<ul>
    <li>A job search portal with country, region and professional area filters</li>
    <li>A distinct internship route alongside experienced hiring</li>
    <li>A phishing warning stating it will never request personal data outside that portal</li>
    <li>5,718 employees at 31 December 2025, 5,367 of them based in Italy</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> Ferrari publishes no pay figure for production roles and says nothing about sponsoring non-EU workers. Italy admits non-EU workers through an annual government quota decree &mdash; not by JobGader. Never pay an agent for a job or a visa.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>The honest version of this guide is that Ferrari is the easy part.</strong> Its jobs portal is open to anyone, the application takes twenty minutes, and the company publishes a clear phishing warning. The hard part is Italy, and no amount of effort on your CV changes it.</p>

<p>So this guide does the Ferrari bit quickly and then spends the time where it actually matters: on whether a non-EU national can legally take a factory job in Italy at all.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobs.ferrari.com/search/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128663; Ferrari Job Search &rarr;
    </a>
</div>

<h2>Where Ferrari Actually Advertises</h2>

<p>The careers portal is at <strong>jobs.ferrari.com</strong>, with the searchable listing at <strong>jobs.ferrari.com/search/</strong>. It filters by country, region and professional area, and internships appear as a distinct category alongside experienced roles.</p>

<p><strong>Do not use a job-ID link someone sends you.</strong> Individual postings expire, and a dead link is the most common reason people think Ferrari is not hiring. Go to the search page and filter.</p>

<h3>Where the work is</h3>

<p>In its annual report filed with the US Securities and Exchange Commission, Ferrari states that on <strong>31 December 2025 it had 5,718 employees</strong>, of whom <strong>5,367 were based in Italy</strong>, primarily at Maranello. Chassis work sits at Modena.</p>

<p>That is a small company by carmaker standards. Ferrari's entire Italian workforce is smaller than a single shift at some mass-market plants, which tells you how few production vacancies exist in any given year.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-ferrari-factory-jobs-in-italy-maranello.jpg" alt="Ferrari production facility in Maranello, Italy" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What Ferrari Does Not Say, And Why It Matters</h2>

<p>We searched Ferrari's reachable careers pages for any mention of visa sponsorship, work authorisation, EU citizenship or an Italian language requirement. <strong>None of those words appear anywhere.</strong></p>

<p>That silence is not an oversight, and it is not a yes. European employers that actively sponsor non-EU workers almost always say so, because it is a recruiting advantage. A company that says nothing is a company that expects applicants to arrive already entitled to work in Italy.</p>

<p>Treat it that way. If you hold an EU passport, an Italian residence permit that allows work, or you are applying from inside Italy, Ferrari is a normal employer you can apply to today. If you do not, read the next section before you spend another hour on this.</p>

<h2>Italy Admits Non-EU Workers by Quota, and the Numbers Are Public</h2>

<p>Italy does not let employers sponsor non-EU workers freely. It sets a national quota by decree &mdash; the <strong>decreto flussi</strong> &mdash; and applications open on fixed "click days" when the quota for each category unlocks.</p>

<p>The current decree was made on <strong>2 October 2025</strong> and published in the <strong>Gazzetta Ufficiale on 15 October 2025</strong>. It sets:</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Period</th>
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Places</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">2026</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">164,850</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">2027</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">165,850</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">2028</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">166,850</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>Total, 2026 to 2028</strong></td>
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>497,550</strong></td>
        </tr>
    </tbody>
</table>

<p>Those look like large numbers until you see how they are split. <strong>The quota is dominated by seasonal work</strong> &mdash; agriculture and tourism &mdash; along with care work and specific listed trades. Non-seasonal subordinate work in manufacturing is a slice of the remainder, spread across the entire country and every manufacturer in it.</p>

<h3>The nulla osta is applied for by the employer</h3>

<p>Under this system <strong>the employer files for the work authorisation, the nulla osta, on a click day, before you can apply for a visa.</strong> You cannot obtain it yourself, and no agent can obtain it for you. An employer must want to spend its own administrative effort on a quota slot for you specifically.</p>

<p>That is the real barrier at Ferrari. Not the interview. A company hiring a small number of production staff in Maranello has a large local and EU applicant pool and no reason to enter the quota process for an overseas factory applicant.</p>

<h3>The qualified routes sit outside the quota</h3>

<p>Italy also operates the <strong>EU Blue Card</strong>, introduced into Italian law by Legislative Decree 152/2023, which transposes EU Directive 2021/1883, with a joint implementing circular issued on 28 March 2024. The Blue Card sits outside the quota and is aimed at highly qualified workers with a recognised degree or equivalent professional experience, above a salary threshold set by the authorities.</p>

<p>The practical reading: <strong>Italy has a route for qualified engineers and a very narrow one for factory operators.</strong> If your background is engineering rather than assembly, the Blue Card is the door worth studying. Check the current salary threshold with the Italian authorities before relying on any figure, including ones quoted on immigration blogs.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-ferrari-factory-jobs-in-italy-line.jpg" alt="Assembly line workers building a Ferrari sports car" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Pay: Ferrari Publishes Nothing, So Here Is the Official Floor</h2>

<p><strong>Ferrari does not publish a salary figure for any production role</strong>, in its careers material or in its filed annual report. What does exist is the national collective agreement that sets minimum pay for Italian metalworkers, which covers this kind of work.</p>

<p>The <strong>CCNL Metalmeccanici</strong> was renewed on <strong>22 November 2025</strong> and runs from <strong>January 2025 to June 2028</strong>. The agreed increases on the reference grade:</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">From</th>
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Increase</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">June 2025 (already paid)</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">&euro;27.70 a month</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">June 2026</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">&euro;53 a month</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">June 2027</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">&euro;59 a month</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">June 2028</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">&euro;65 a month</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>Total over the agreement</strong></td>
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>&euro;205.32 a month</strong></td>
        </tr>
    </tbody>
</table>

<p>Flexible benefits under the same agreement rise from <strong>&euro;200 to &euro;250 a year</strong>.</p>

<p>For the wider picture, ISTAT's structural earnings release puts the <strong>national average gross annual salary at &euro;37,302</strong> on a full-time equivalent basis, and the industry macro-sector at <strong>&euro;38,760 a year</strong>. Note the reference year: these are <strong>2022 figures</strong>, the most recent structural release available, so treat them as a baseline rather than today's pay.</p>

<p><strong>The often-quoted Ferrari employee bonus is not in this guide on purpose.</strong> Ferrari has announced staff bonuses publicly in the past, but the specific figure circulating online could not be confirmed on any Ferrari source, so we are not repeating it.</p>

<h2>Ferrari's Own Phishing Warning</h2>

<p>Ferrari publishes this on its jobs portal, and it is worth quoting exactly:</p>

<p><strong>"Beware of phishing attempts: During the recruitment process, Ferrari will never request personal data from candidates except through https://jobs.ferrari.com website."</strong></p>

<p>Read that as a rule rather than a warning. If a recruiter asks for your passport scan, bank details or a payment over email or WhatsApp, that is not Ferrari, no matter what the letterhead looks like. The brand's fame makes it one of the most impersonated employers in fake-job schemes.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Settle the work authorisation question first.</strong> If you do not already have the right to work in Italy, understand that Ferrari has said nothing about sponsoring anyone.</li>
    <li><strong>Go to jobs.ferrari.com/search/</strong> and filter by country and professional area. Ignore job-ID links.</li>
    <li><strong>Consider the internship route</strong> if you are early in your career; it is a distinct category on the portal.</li>
    <li><strong>Write the CV in English, and learn Italian anyway.</strong> Ferrari publishes no language requirement, but the shop floor at Maranello runs in Italian.</li>
    <li><strong>If you are a qualified engineer,</strong> research the EU Blue Card route rather than the quota route.</li>
    <li><strong>Submit only through the portal,</strong> and never pay anyone for an Italian work visa.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Does Ferrari sponsor non-EU workers?</h3>
<p>Ferrari says nothing about sponsorship, work authorisation or visas anywhere on its reachable careers pages. It does not advertise sponsorship, and Italy admits non-EU workers through a quota decree rather than free employer sponsorship.</p>

<h3>Where do I apply for Ferrari jobs?</h3>
<p>At jobs.ferrari.com, with the searchable listing at jobs.ferrari.com/search/. Individual job-ID links expire, so use the search page.</p>

<h3>How many people work at Ferrari?</h3>
<p>5,718 employees at 31 December 2025, of whom 5,367 were based in Italy, primarily at Maranello, according to Ferrari's own filed annual report.</p>

<h3>What does Ferrari pay factory workers?</h3>
<p>Ferrari publishes no pay figure for any production role. Minimum pay for this work is set by the Italian metalworkers' national collective agreement, renewed on 22 November 2025.</p>

<h3>What is the decreto flussi?</h3>
<p>Italy's annual quota decree for admitting non-EU workers. The decree of 2 October 2025 sets 497,550 places across 2026 to 2028, unlocked on fixed click days by category.</p>

<h3>Who applies for the nulla osta?</h3>
<p>The employer, on a click day, before you can apply for a visa. You cannot obtain it yourself and no agent can obtain it for you.</p>

<h3>Is there an EU Blue Card route in Italy?</h3>
<p>Yes. It was introduced by Legislative Decree 152/2023, transposing EU Directive 2021/1883, with an implementing circular of 28 March 2024. It targets highly qualified workers and sits outside the quota.</p>

<h3>Do I need Italian to work at Ferrari?</h3>
<p>Ferrari publishes no language requirement. In practice production work in Maranello is carried out in Italian, and Italy applies its own language conditions to longer-term residence permits.</p>

<h2>People Also Search For</h2>

<h3>Ferrari careers Maranello</h3>
<p>Maranello is Ferrari's assembly site and headquarters, with chassis work at Modena. Vacancies for both appear on the same careers portal.</p>

<h3>Ferrari internship Italy</h3>
<p>Internship appears as a distinct listing category on the Ferrari job search, alongside experienced roles.</p>

<h3>Ferrari salary for workers</h3>
<p>Not published by Ferrari. The floor comes from the metalworkers' collective agreement rather than from the company.</p>

<h3>Factory jobs in Italy for foreigners</h3>
<p>Legally routed through the decreto flussi quota for non-EU nationals, which is dominated by seasonal agriculture and tourism work rather than manufacturing.</p>

<h3>Italy work visa click day</h3>
<p>The fixed date when a quota category opens and employers file nulla osta applications. Missing it means waiting for the next window.</p>

<h3>CCNL metalmeccanici 2026</h3>
<p>The Italian metalworkers' national agreement, renewed 22 November 2025, running to June 2028 with staged monthly increases of &euro;53, &euro;59 and &euro;65.</p>

<h3>Average salary in Italy</h3>
<p>ISTAT put the national average gross annual salary at &euro;37,302 full-time equivalent, and the industry macro-sector at &euro;38,760, in its 2022 structural earnings data.</p>

<h3>Ferrari job scam</h3>
<p>Ferrari warns that it will never request personal data from candidates except through its own jobs website. Anything asking for documents or money elsewhere is not Ferrari.</p>

<h2>More Job Guides</h2>

<p>Looking at European manufacturing or comparing employers? These cover the ground:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; the same question in a country with a clearer skilled-worker route.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; general factory work and what it actually pays.</li>
    <li><a href="/blog/how-to-get-a-transport-job-in-germany">How to Get a Transport Job in Germany</a> &mdash; another route into the EU labour market.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; a sponsorship system that works very differently from Italy's quota.</li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; another employer that publishes no pay figure.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Ferrari's own careers portal, Ferrari N.V.'s annual report filed with the US Securities and Exchange Commission, the Gazzetta Ufficiale and the Italian government's integration and labour portals, Federmeccanica's published collective agreement material and ISTAT earnings data. Ferrari publishes no pay figure for production roles and states nothing about sponsoring non-EU workers, and Italian quota rules change every year. Always check the current decree and the live job posting.</p>
HTML;
    }
}
