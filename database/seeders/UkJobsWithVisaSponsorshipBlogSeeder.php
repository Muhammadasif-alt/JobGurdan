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
 * "UK Jobs with Visa Sponsorship" — the UK sibling of the Australia and
 * Canada sponsorship guides, which this site already carries. It owns the
 * employer side of sponsorship: how to read the register of licensed
 * sponsors, what a Certificate of Sponsorship is and who pays for what.
 * The route comparison stays on "Jobs in UK for Foreigners" and is not
 * repeated here.
 *
 * Corrections to the draft:
 *
 * 1. The draft says a licensed sponsor "does not mean an employer will
 *    sponsor every vacancy" but never gives the actual second test. It is
 *    the job's SOC 2020 occupation code being listed as Higher Skilled in
 *    Appendix Skilled Occupations. A reader cannot act on the draft.
 *
 * 2. It says "required English-language ability" without the level. It has
 *    been B2 since 8 January 2026, up from B1, and nationals of majority
 *    English-speaking countries are exempt from proving it.
 *
 * 3. It lists the Immigration Salary List as a thing that exists without
 *    saying what it does: it lowers the salary floor to 80 per cent of the
 *    going rate. It also omits the Temporary Shortage List entirely, which
 *    is the only thing keeping medium-skilled codes sponsorable.
 *
 * 4. It never mentions the Immigration Skills Charge, which is the largest
 *    single cost of sponsorship, is paid by the employer, and may not be
 *    recovered from the worker. That omission is what makes "pay for your
 *    own sponsorship" scams work.
 *
 * 5. It gives no fees at all. The applicant pays GBP 819 or GBP 1,618 for
 *    the visa plus the healthcare surcharge, and must hold GBP 1,270 for
 *    28 days unless the sponsor certifies maintenance.
 *
 * 6. It does not mention sponsor ratings. A B-rated sponsor appears on the
 *    register exactly like an A-rated one but cannot assign a Certificate
 *    of Sponsorship to a new worker.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class UkJobsWithVisaSponsorshipBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.gov.uk/find-a-job';

    public function run(): void
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
        $title = 'UK Jobs with Visa Sponsorship';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A sponsor licence proves nothing on its own. A sponsored UK job has to clear two separate tests, the salary floor is £41,700 or the going rate, and the employer pays an Immigration Skills Charge it may never recover from you.',
                'content' => $content,
                'featured_image' => 'blogs/uk-jobs-with-visa-sponsorship.jpg',
                'tags' => 'uk jobs with visa sponsorship, skilled worker visa jobs uk, certificate of sponsorship, register of licensed sponsors, uk sponsor licence check, immigration skills charge, immigration salary list, skilled worker salary threshold 2026',
                'meta_title' => 'UK Jobs with Visa Sponsorship 2026: Sponsors and Salary',
                'meta_description' => 'UK jobs with visa sponsorship: how to read the licensed sponsor register, what a Certificate of Sponsorship is, and the GBP 41,700 salary floor.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );

        $this->seedJob();
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Licensed Sponsors — Skilled Worker Route Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-licensed-sponsors-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Sponsored Jobs with UK Licensed Sponsors — Skilled Worker Route, Eligible Occupations Only',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; going rates are quoted against a 37.5 hour week and pro-rated for other patterns',
                'language' => 'English',
                // Sponsored pay is the higher of the general threshold and the
                // occupation code's going rate, so no single range describes
                // the group. The thresholds are in the description instead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Skilled Worker roles with employers on the Home Office register of licensed sponsors, in occupations listed as eligible in Appendix Skilled Occupations.',
                'seo_keywords' => 'uk jobs with visa sponsorship, skilled worker visa jobs uk, certificate of sponsorship, licensed sponsor jobs uk, uk sponsorship jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Employers on the Home Office register of licensed sponsors recruit overseas workers into roles that meet Skilled Worker rules. A licence alone is not enough: the specific job must also sit in an eligible occupation code.</p>

<h3>Both tests have to pass</h3>
<ul>
    <li>The employer holds a Skilled Worker sponsor licence under the exact legal entity that will employ you, and holds an A-rating</li>
    <li>The job's SOC 2020 occupation code is listed as Higher Skilled in Appendix Skilled Occupations, or qualifies through the Immigration Salary List or Temporary Shortage List</li>
</ul>

<h3>Salary</h3>
<ul>
    <li>&pound;41,700 a year or the occupation's going rate, whichever is higher</li>
    <li>&pound;37,500 with a relevant PhD, at 90 per cent of the going rate</li>
    <li>&pound;33,400 with a STEM PhD, on the Immigration Salary List, or as a new entrant</li>
    <li>Going rates are national. There is no London uplift</li>
</ul>

<h3>What the applicant pays</h3>
<ul>
    <li>&pound;819 from outside the UK for up to three years, or &pound;1,618 for longer</li>
    <li>Immigration health surcharge, usually &pound;1,035 a year</li>
    <li>&pound;1,270 held for 28 days, unless the sponsor certifies maintenance</li>
    <li>English at level B2, which US, Canadian, Australian, New Zealand and Irish nationals are exempt from proving</li>
</ul>

<h3>What the employer pays</h3>
<p><strong>The Immigration Skills Charge is the sponsor's own liability</strong> &mdash; &pound;1,320 per 12 months for a medium or large sponsor, &pound;480 for a small or charitable one &mdash; and it may not be recovered from the worker.</p>

<p><strong>Never pay for sponsorship.</strong> There is no lawful payment from a worker for a sponsor licence or a Certificate of Sponsorship. Visa rules, thresholds and fees are set by the Home Office, not by JobGader; confirm them on gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to UK jobs with visa sponsorship stop at "find an employer on the sponsor register". That is the first of two tests, and on its own it proves nothing. A bank on the register can sponsor its quantitative analysts and not its branch cashiers. A hospital on the register can sponsor a radiographer and not a porter. This page is about the mechanics that decide which it is: how the register really reads, what a Certificate of Sponsorship is, what the salary has to clear, and who pays for each part of it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/uk-jobs-with-visa-sponsorship.jpg"
         alt="A graduate holding a laptop beside Westminster Bridge with a UK passport and the Union Jack, under the heading UK Jobs with Visa Sponsorship"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Two Tests Every Sponsored Job Must Pass</h2>

<p>Sponsorship is decided job by job, not employer by employer. Both of these have to be true at the same time:</p>

<ol>
    <li><strong>The employer holds a sponsor licence</strong> for the route you need, and holds it under the exact legal entity that will employ you.</li>
    <li><strong>The job's occupation code is eligible.</strong> The Immigration Rules work from SOC 2020 codes listed in Appendix Skilled Occupations, where each code is marked Higher Skilled, Medium Skilled or Ineligible. A Medium Skilled code can only be sponsored through the Immigration Salary List or the Temporary Shortage List.</li>
</ol>

<p>Neither test implies the other. This is the single most expensive misunderstanding in an overseas job search, because it sends people to apply for hundreds of vacancies at licensed employers that could never have been sponsored.</p>

<h2>How to Read the Register of Licensed Sponsors</h2>

<p>The Home Office publishes the register as a downloadable file, updated regularly. It is not a job board and it carries no vacancies. Four things in it matter:</p>

<ul>
    <li><strong>Organisation name.</strong> This is the registered legal entity, not the trading brand. A hotel group's licence may sit under a holding company name you have never seen on a sign. Search for fragments, not the brand you know.</li>
    <li><strong>Route.</strong> A licence is route-specific. "Skilled Worker" is the one that matters for ordinary employment; a company licensed only for Global Business Mobility can move its own staff into a UK branch and cannot hire you off the open market.</li>
    <li><strong>Rating.</strong> An A-rating is a sponsor in good standing. A <strong>B-rating</strong> means the sponsor is working through a Home Office action plan and <strong>cannot assign a Certificate of Sponsorship to a new worker</strong> while it lasts. Both appear on the same list.</li>
    <li><strong>Town or city.</strong> Useful for telling two similarly named entities apart, not for finding where the job is.</li>
</ul>

<p>Check the register at <a href="https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers" rel="noopener">gov.uk</a>. If an employer is not on it, no amount of enthusiasm on either side can create sponsorship.</p>

<h2>What a Certificate of Sponsorship Actually Is</h2>

<p>A Certificate of Sponsorship is not a certificate and it is not a document you receive. It is an electronic record the sponsor creates on the Home Office system, holding the job title, occupation code, salary, hours and start date. You get a reference number and you use it in the visa application.</p>

<ul>
    <li><strong>You must apply within three months</strong> of the Certificate of Sponsorship being assigned to you. Miss the window and the sponsor has to start again.</li>
    <li><strong>Applying from outside the UK</strong> on the Skilled Worker route generally needs a <em>defined</em> Certificate of Sponsorship, which the sponsor requests from the Home Office for your specific job before it can be assigned.</li>
    <li><strong>What it says is binding.</strong> If the salary or occupation code on it does not match the rules, the visa is refused however good the job is.</li>
    <li><strong>It is never for sale.</strong> There is no lawful way to buy one, from the employer or from anyone else.</li>
</ul>

<h2>The Salary Floor, and the Four Ways It Drops</h2>

<p>The general threshold is <strong>£41,700 a year, or the going rate for the occupation code, whichever is higher</strong>. The going rates come from ONS earnings data, are quoted against a 37.5 hour week and are pro-rated for other patterns, so a four-day week cuts the salary that counts. They are national figures: <strong>there is no London uplift</strong>.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Situation</th>
            <th style="padding:10px;text-align:left;">General threshold</th>
            <th style="padding:10px;text-align:left;">Going rate you must also clear</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Standard applicant</td><td style="padding:10px;">£41,700</td><td style="padding:10px;">100%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Relevant PhD in the job's subject</td><td style="padding:10px;">£37,500</td><td style="padding:10px;">90%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">STEM PhD in the job's subject</td><td style="padding:10px;">£33,400</td><td style="padding:10px;">80%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Job on the Immigration Salary List</td><td style="padding:10px;">£33,400</td><td style="padding:10px;">80%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New entrant (under 26, recent graduate, postdoctoral or working toward a professional qualification)</td><td style="padding:10px;">£33,400</td><td style="padding:10px;">70%</td></tr>
    </tbody>
</table>
</div>

<p>Health and education occupations on national pay scales are set against those scales instead of the ONS going rates. The Migration Advisory Committee reviewed the general threshold in December 2025 and recommended keeping £41,700, while setting out £48,400 as an option ministers could take. Treat £41,700 as the live figure and check before quoting it back to anyone.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/uk-jobs-with-visa-sponsorship-passport.jpg"
         alt="An applicant holding a passport and boarding pass at a desk with a laptop, with the Union Jack, a departing aircraft and the London skyline behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Pays for What</h2>

<p>Sponsorship costs are split, and knowing the split is what protects you from the commonest scam.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Cost</th>
            <th style="padding:10px;text-align:left;">Amount</th>
            <th style="padding:10px;text-align:left;">Who pays</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Visa application, from outside the UK, up to 3 years</td><td style="padding:10px;">£819</td><td style="padding:10px;">You</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Visa application, from outside the UK, over 3 years</td><td style="padding:10px;">£1,618</td><td style="padding:10px;">You</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Immigration health surcharge</td><td style="padding:10px;">Usually £1,035 a year</td><td style="padding:10px;">You</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Maintenance funds held 28 days</td><td style="padding:10px;">£1,270</td><td style="padding:10px;">You, unless the sponsor certifies it</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Immigration Skills Charge</strong>, medium or large sponsor</td><td style="padding:10px;">£1,320 per 12 months</td><td style="padding:10px;"><strong>The employer, always</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Immigration Skills Charge</strong>, small or charitable sponsor</td><td style="padding:10px;">£480 per 12 months</td><td style="padding:10px;"><strong>The employer, always</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Indefinite leave to remain, after 5 continuous years</td><td style="padding:10px;">£3,226 a person</td><td style="padding:10px;">You</td></tr>
    </tbody>
</table>
</div>

<p>The Immigration Skills Charge is the sponsor's own liability and it may not be passed on to the worker. Any employer or agent asking you to fund it, or to pay for a Certificate of Sponsorship, is either breaking the rules or is not a sponsor at all.</p>

<h2>The English Requirement</h2>

<p>Skilled Worker applicants must prove English at level B2 on the CEFR scale. It rose from B1 on 8 January 2026. Nationals of majority English-speaking countries, including the United States, Canada, Australia, New Zealand and Ireland, are exempt from proving it, as is anyone with a degree taught in English. Everyone else needs an approved secure English language test.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/uk-jobs-with-visa-sponsorship-register.jpg"
         alt="An applicant holding a passport and boarding pass beside a laptop and notebook, with a UK visa document, the Union Jack and the Palace of Westminster at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Sponsorship Scams: The One Rule That Ends Them</h2>

<p><strong>You never pay for sponsorship.</strong> Not for the licence, not for the Certificate of Sponsorship, not for the Immigration Skills Charge, not for a "guaranteed" job offer. Everything you legitimately pay goes to the Home Office through the official application, and nothing goes to a recruiter.</p>

<ul>
    <li><strong>A fee to be "registered" or "shortlisted".</strong> Agencies are paid by employers.</li>
    <li><strong>A job offer before any interview.</strong> Sponsorship involves a real recruitment process because the employer is taking on a legal obligation.</li>
    <li><strong>An email domain that is not the employer's.</strong> Check the domain against the organisation's own website, not against the one in the message.</li>
    <li><strong>A "sponsorship certificate" sent as a PDF.</strong> A Certificate of Sponsorship is a reference number on a Home Office system; a decorative certificate is a forgery.</li>
    <li><strong>An employer you cannot find on the register under any spelling.</strong> Stop there.</li>
    <li><strong>Pressure to pay quickly before a deadline.</strong> No genuine visa process works this way.</li>
</ul>

<h2>Searching So You Do Not Waste Applications</h2>

<ol>
    <li><strong>Start from your occupation code.</strong> Find your SOC 2020 code in Appendix Skilled Occupations and confirm it is Higher Skilled before you look at a single vacancy.</li>
    <li><strong>Read the going rate for that code</strong> and treat it as your minimum acceptable salary. Agreeing to less does not just cost you money; it ends the visa.</li>
    <li><strong>Filter employers through the register</strong> for the Skilled Worker route and an A-rating.</li>
    <li><strong>Apply on the employer's own careers system</strong>, and use the government's <a href="https://www.gov.uk/find-a-job" rel="noopener">Find a Job</a> service, where UK employers post directly.</li>
    <li><strong>Ask the sponsorship question early and in writing.</strong> "Is this role open to candidates requiring Skilled Worker sponsorship?" is one line and saves weeks.</li>
    <li><strong>Keep evidence of your qualifications ready</strong>, including any PhD that would lower your salary floor.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What does visa sponsorship mean for a UK job?</h3>
<p>An employer licensed by the Home Office assigns you a Certificate of Sponsorship for a specific, eligible job, which you then use to apply for a work visa. The job must be an eligible occupation code and must pay at or above the applicable salary floor.</p>

<h3>Does being on the sponsor register mean a company will sponsor me?</h3>
<p>No. The register shows that the employer may sponsor workers for a route. Whether a particular vacancy is sponsored depends on the job's occupation code, its salary and the employer's own decision.</p>

<h3>What is the salary requirement for a sponsored UK job in 2026?</h3>
<p>£41,700 a year or the occupation's going rate, whichever is higher. It falls to £37,500 with a relevant PhD, and to £33,400 with a STEM PhD, on the Immigration Salary List, or as a new entrant.</p>

<h3>What is a Certificate of Sponsorship and how long is it valid?</h3>
<p>An electronic record created by the sponsor containing the job details, identified by a reference number. You must make your visa application within three months of it being assigned.</p>

<h3>Who pays the Immigration Skills Charge?</h3>
<p>The employer, at £1,320 per 12 months for a medium or large sponsor and £480 for a small or charitable one. It cannot lawfully be recovered from the worker.</p>

<h3>What does a B-rated sponsor mean on the register?</h3>
<p>The sponsor is on a Home Office action plan and cannot assign a Certificate of Sponsorship to a new worker until the rating is restored. It appears on the same register as an A-rated sponsor.</p>

<h3>Do I need an English test for a sponsored UK job?</h3>
<p>Level B2 since 8 January 2026. Nationals of majority English-speaking countries such as the USA, Canada, Australia, New Zealand and Ireland are exempt, as is anyone holding a degree taught in English.</p>

<h3>Can a UK employer charge me for sponsorship?</h3>
<p>No. There is no lawful payment from a worker to an employer or agent for a sponsor licence, a Certificate of Sponsorship or the Immigration Skills Charge. A request for one is a scam.</p>

<h2>People Also Search For</h2>

<h3>Skilled Worker visa jobs UK</h3>
<p>The main sponsored route; eligibility runs from the SOC 2020 occupation code, not the job title.</p>

<h3>Register of licensed sponsors</h3>
<p>A Home Office list of organisations and routes, with A or B ratings. It carries no vacancies.</p>

<h3>Certificate of Sponsorship</h3>
<p>An electronic record with a reference number. The visa application must follow within three months.</p>

<h3>Immigration Skills Charge</h3>
<p>£1,320 or £480 per 12 months, paid by the sponsor and never by the worker.</p>

<h3>Immigration Salary List</h3>
<p>Lowers the floor to £33,400 and 80 per cent of the going rate for listed occupations.</p>

<h3>Temporary Shortage List</h3>
<p>The only route by which a medium-skilled occupation code can currently be sponsored.</p>

<h3>Skilled Worker salary threshold 2026</h3>
<p>£41,700 or the going rate, with no London weighting anywhere in the calculation.</p>

<h3>UK sponsor licence check</h3>
<p>Search the register by legal entity name and confirm the route and rating, not the brand.</p>

<h2>More Job Guides</h2>

<p>These pick up where this one stops:</p>

<ul>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; every visa route compared, including the ones that closed.</li>
    <li><a href="/blog/jobs-in-london-england-for-american-applicants">Jobs in London England for American Applicants</a> &mdash; what changes when the passport is American.</li>
    <li><a href="/blog/vacancies-in-london">Vacancies in London</a> &mdash; the London market, the pay floors and the right-to-work check.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; the same mechanics under a different system.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; LMIA-based sponsorship compared.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the care route and what changed.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why these adverts almost never add up.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; a medium-skilled code and what keeps it sponsorable.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration or legal advice. UK visa rules, salary thresholds, fees and eligible occupation lists change often. Confirm the current rules on gov.uk, or with a regulated adviser, before applying or paying anyone.</p>
HTML;
    }
}
