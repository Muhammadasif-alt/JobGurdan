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
 * "Janitor Jobs in Canada" — custodians, caretakers and building cleaners.
 * The London and Saudi cleaner guides cover other markets, and this one owns
 * Canadian janitor pay by province, the 2026 minimum wages that set the floor,
 * Job Bank's prospects, and the low-wage LMIA and permanent residence limits.
 *
 * Corrections to the draft:
 *
 * 1. It puts most janitor jobs at $18 to $33 an hour. Job Bank's wage data for
 *    NOC 65312 (2023-2024) runs from $16.00 to $28.72 nationally, with a
 *    median of $21.27; only British Columbia's top wage passes $33, and the
 *    Alberta floor is $15.00.
 *
 * 2. It says openings exist year-round in every city it names. Job Bank rates
 *    prospects Good only in Nova Scotia and New Brunswick, Moderate in Ontario,
 *    Alberta and BC, and Limited in Manitoba and Quebec.
 *
 * 3. It says gig cleaning platforms let workers keep 100% of tips. That is a
 *    platform's own policy, not a legal right: Ontario's Digital Platform
 *    Workers' Rights Act names ride share, delivery and courier work.
 *
 * 4. It says nothing about coming from abroad. Every janitor LMIA is in the
 *    low-wage stream, which is not processed in Toronto, Montreal, Vancouver,
 *    Calgary or Edmonton at current unemployment, and TEER 5 jobs do not
 *    qualify for Express Entry.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class JanitorJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/Janitor-jobs';

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
        $title = 'Janitor Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the median janitor wage at $21.27 an hour, from $20.03 in Alberta to $24.65 in BC, rates prospects Good only in Nova Scotia and New Brunswick, and every janitor LMIA falls in the low-wage stream.',
                'content' => $content,
                'featured_image' => 'blogs/janitor-jobs-in-canada.jpg',
                'tags' => 'janitor jobs canada, janitor salary canada, custodian jobs canada, cleaner jobs toronto, janitor jobs edmonton, minimum wage canada 2026, janitor lmia, noc 65312, cleaning jobs canada for foreigners',
                'meta_title' => 'Janitor Jobs in Canada 2026: Pay, Prospects and LMIA Rules',
                'meta_description' => 'Janitor jobs in Canada: Job Bank pay by province, 2026 minimum wages, job prospects, the low-wage LMIA limits and the permanent residence routes.',
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
            ['name' => 'Canadian Building Services & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-janitor-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'cleaning-facilities'],
            ['name' => 'Cleaning & Facilities']
        );

        Job::updateOrCreate(
            [
                'position' => 'Janitor — Offices, Schools, Hospitals and Residential Buildings, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Day, evening, overnight and weekend shifts; part-time and on-call work is common',
                'language' => 'English, French',
                // Janitor pay starts at each province's minimum wage and the
                // medians differ by up to $5 an hour, so no national range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Janitor and custodian roles in Canadian offices, schools, hospitals and residential buildings. Cleaning experience often preferred.',
                'seo_keywords' => 'janitor jobs canada, custodian jobs canada, building cleaner jobs, caretaker jobs canada, night janitor jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Building service contractors, school boards, hospitals, property managers and retailers across Canada hire janitors and custodians for full-time, part-time and night shifts.</p>

<h3>What the work involves</h3>
<p>Sweeping, mopping and vacuuming floors, cleaning and restocking washrooms, emptying waste and recycling, operating floor machines, and reporting repairs to building staff.</p>

<h3>Requirements</h3>
<ul>
    <li>Completion of secondary school and previous cleaning experience may be required, according to the National Occupational Classification</li>
    <li>No licence: janitorial work is not a regulated occupation in Canada</li>
    <li>The right to work in Canada, or an employer able to obtain an LMIA for the position</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank medians.</strong> $24.65 an hour in British Columbia, $22.00 in Ontario, $21.00 in Quebec, $20.03 in Alberta and $19.00 in Nova Scotia</li>
    <li><strong>The floor.</strong> Pay cannot fall below the provincial minimum wage, which ranges from $15.00 in Alberta to $18.25 in British Columbia</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for a job offer or an LMIA.</strong> The employer pays the $1,000 LMIA fee, and it cannot be recovered from the worker.</p>

<p><strong>Note:</strong> wages, shifts and immigration eligibility are set by employers, provincial employment standards and the Government of Canada &mdash; not by JobGader. Confirm the details with the employer and on canada.ca before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Janitors and custodians keep Canada's offices, schools, hospitals, malls and apartment buildings running, and it is one of the few jobs you can start without a licence or a diploma. Before you apply, especially from abroad, it helps to know four things most guides skip: what janitors really earn in each province, which minimum wage sets the floor, where the jobs are actually easier to find, and why the visa route is so narrow.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/Janitor-jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129529; Browse Janitor Jobs in Canada &rarr;
    </a>
</div>

<h2>What Janitors Do and What Employers Ask For</h2>

<p>Canada classifies the job as <strong>janitors, caretakers and heavy-duty cleaners (NOC 65312)</strong>. The work is what you would expect: floors, washrooms, dusting, waste and recycling, restocking supplies, running buffers and industrial vacuums, and reporting broken fixtures. Caretakers in apartment buildings may also handle minor repairs and tenant requests.</p>

<p>Guides say most roles need no formal education. The National Occupational Classification is a little stricter: <strong>Completion of secondary school may be required</strong>, and previous cleaning experience may be required. There is no licence, because Job Bank lists the occupation as <strong>not regulated in Canada</strong>. About <strong>107,000</strong> people did this work in 2023, and 53% of them were aged 50 or over, so retirements create a steady stream of openings.</p>

<h2>What Janitors Earn by Province</h2>

<p>Guides put most janitor jobs at $18 to $33 an hour. Job Bank's wage data for NOC 65312, covering 2023-2024 and updated on 19 November 2025, is lower at both ends:</p>

<ul>
    <li><strong>Canada:</strong> median <strong>$21.27</strong> an hour, from $16.00 to $28.72</li>
    <li><strong>British Columbia:</strong> median <strong>$24.65</strong>, from $18.25 to $34.00</li>
    <li><strong>Ontario:</strong> median $22.00, from $17.60 to $28.00</li>
    <li><strong>Quebec:</strong> median $21.00, from $17.00 to $26.67</li>
    <li><strong>Saskatchewan:</strong> median $20.62, from $15.35 to $27.00</li>
    <li><strong>Manitoba:</strong> median $20.15, from $16.00 to $28.00</li>
    <li><strong>Alberta:</strong> median <strong>$20.03</strong>, from $15.00 to $28.00</li>
    <li><strong>Nova Scotia:</strong> median $19.00, from $16.75 to $26.00</li>
</ul>

<p>For the cities guides name: Toronto's median is $22.77, Winnipeg's $21.56, Calgary's $21.00, Edmonton's $20.00 and Halifax's $19.00. So $33 an hour is above the recorded top wage everywhere except British Columbia, and the bottom of the range is simply the minimum wage.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/janitor-jobs-in-canada-office.jpg"
         alt="A janitor in a navy uniform and blue gloves mopping a polished office floor beside a cleaning cart, with the Toronto skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Minimum Wage Sets the Floor</h2>

<p>Entry-level janitor pay is usually the provincial minimum wage, so check the rate where the job is:</p>

<ul>
    <li><strong>British Columbia:</strong> $18.25 an hour from 1 June 2026</li>
    <li><strong>Ontario:</strong> $17.60, rising to <strong>$17.95 on 1 October 2026</strong></li>
    <li><strong>Nova Scotia:</strong> $16.75, rising to $17.00 on 1 October 2026</li>
    <li><strong>Quebec:</strong> $16.60 from 1 May 2026</li>
    <li><strong>Manitoba:</strong> $16.00, rising to $16.40 on 1 October 2026</li>
    <li><strong>Saskatchewan:</strong> $15.35, rising to $15.70 on 1 October 2026</li>
    <li><strong>Alberta:</strong> $15.00, unchanged since 2018</li>
</ul>

<p>Cleaning for a federally regulated employer, such as a bank, an airport or a federal building run under the Canada Labour Code, pays at least the <strong>federal minimum of $18.15</strong> from 1 April 2026.</p>

<h2>Where the Jobs Are: Job Bank's Prospects</h2>

<p>Guides list Toronto, Edmonton, Winnipeg and Halifax as places with plenty of openings. Job Bank's official job prospects for janitors are more mixed:</p>

<ul>
    <li><strong>Nova Scotia and New Brunswick:</strong> <strong>Good</strong></li>
    <li><strong>Ontario, Alberta, British Columbia and Saskatchewan:</strong> Moderate</li>
    <li><strong>Newfoundland and Labrador and Prince Edward Island:</strong> Moderate</li>
    <li><strong>Manitoba and Quebec:</strong> <strong>Limited</strong></li>
</ul>

<p>Big cities post the most listings, but they also have the most applicants. Winnipeg in particular sits in a province rated Limited, while Halifax and the rest of the Maritimes are rated Good despite lower pay.</p>

<h2>Full-Time, Part-Time and Gig Cleaning</h2>

<ul>
    <li><strong>Building service contractors</strong> employ many janitors and move them between client sites, often on evening and overnight shifts.</li>
    <li><strong>School boards, hospitals and universities</strong> hire custodians directly, and these are the jobs most likely to be unionized with benefits and pensions.</li>
    <li><strong>Part-time and on-call work</strong> is common, which suits students, but check the guaranteed hours before relying on it as a full income.</li>
    <li><strong>Gig cleaning apps.</strong> Guides say workers on these platforms keep 100% of tips. That is a platform's own policy and <strong>not a legal right</strong>. Many apps treat cleaners as independent contractors rather than employees, and Ontario's Digital Platform Workers' Rights Act, in force since 1 July 2025, names ride share, delivery and courier work. Read the contract to see whether you are an employee before you count on minimum wage, vacation pay or tips.</li>
</ul>

<h2>Coming From Abroad: Every Janitor LMIA Is Low-Wage</h2>

<p>Guides say only that you need legal authorization to work in Canada. From abroad, that usually means an employer-specific work permit backed by a <strong>Labour Market Impact Assessment</strong>, and the stream depends on the wage against the provincial threshold. For LMIAs received from 17 July 2026 the high-wage threshold is $36.92 an hour in Ontario, $38.40 in British Columbia, $37.50 in Alberta and $31.96 in Nova Scotia. The highest recorded janitor wages sit below every one of them, so janitor positions fall in the <strong>low-wage stream</strong>:</p>

<ul>
    <li><strong>No processing in high-unemployment cities.</strong> Low-wage LMIAs are refused in census metropolitan areas with unemployment of 6% or more. For applications from <strong>10 July to 8 October 2026</strong> that includes Toronto (7.3%), Montr&eacute;al (6.8%), Vancouver (6.7%), Calgary (7.0%) and Edmonton (7.2%). Hospitals, nursing care and positions of 120 days or less are exempt.</li>
    <li><strong>A 10% cap</strong> on the share of low-wage foreign workers at a worksite, or 20% in hospitals and nursing care.</li>
    <li><strong>A one-year maximum</strong> on each low-wage work permit.</li>
</ul>

<p>The employer pays a <strong>$1,000 LMIA fee</strong> for each position, and it cannot be paid by or recovered from the worker. Anyone charging you for a cleaning job in Canada is a warning sign.</p>

<h2>Permanent Residence Is Hard From a Janitor Job</h2>

<ul>
    <li><strong>Express Entry.</strong> Janitors are a <strong>TEER 5</strong> occupation. The Federal Skilled Worker Program and the Canadian Experience Class count only TEER 0 to 3 experience, so janitor work does not qualify.</li>
    <li><strong>Your family.</strong> Since <strong>21 January 2025</strong>, only spouses of workers in TEER 0 or 1 jobs and some TEER 2 and 3 jobs can get an open work permit, so a janitor's spouse cannot.</li>
    <li><strong>Provincial routes.</strong> Some provincial nominee streams accept lower-skilled jobs in named sectors, but the lists change often. Check the province's own program page before relying on one.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do janitors earn in Canada?</h3>
<p>Job Bank's median is $21.27 an hour nationally, from $16.00 to $28.72. The median is highest in British Columbia at $24.65 and lowest in Nova Scotia at $19.00.</p>

<h3>Do I need experience or a diploma to be a janitor in Canada?</h3>
<p>Not a licence. The National Occupational Classification says completion of secondary school and previous cleaning experience may be required, and the occupation is not regulated in Canada.</p>

<h3>Which province is best for janitor jobs?</h3>
<p>Job Bank rates prospects Good in Nova Scotia and New Brunswick. British Columbia pays the highest median, $24.65, with Moderate prospects, and Manitoba and Quebec are rated Limited.</p>

<h3>What is the minimum wage for cleaners in Ontario?</h3>
<p>$17.60 an hour, rising to $17.95 on 1 October 2026. Cleaners working for federally regulated employers get at least the federal minimum of $18.15.</p>

<h3>Can foreigners get janitor jobs in Canada with an LMIA?</h3>
<p>It is possible but narrow. Every janitor LMIA is low-wage, so it cannot be processed in Toronto, Montreal, Vancouver, Calgary or Edmonton between 10 July and 8 October 2026 unless the job is in a hospital or nursing care, and the permit lasts at most a year.</p>

<h3>Can a janitor job lead to permanent residence in Canada?</h3>
<p>Not through Express Entry, because janitors are TEER 5. A provincial nominee stream is the main possibility, and its rules change often.</p>

<h3>Do gig cleaners in Canada keep all their tips?</h3>
<p>Only if the platform promises it. Many apps treat cleaners as independent contractors, and Ontario's platform-worker law names ride share, delivery and courier work.</p>

<h3>Who pays the LMIA fee for a janitor job?</h3>
<p>The employer. The $1,000 fee cannot be paid by or recovered from the worker.</p>

<h2>People Also Search For</h2>

<h3>Janitor salary Toronto</h3>
<p>A Job Bank median of $22.77 an hour, with Ontario's minimum wage rising to $17.95 on 1 October 2026.</p>

<h3>Janitor jobs Edmonton</h3>
<p>A median of $20.00 an hour, and low-wage LMIAs are not processed there while unemployment is 7.2%.</p>

<h3>Custodian jobs Canada</h3>
<p>School boards, hospitals and universities hire custodians directly, often with union benefits.</p>

<h3>Cleaner jobs Halifax</h3>
<p>A median of $19.00 an hour, in a province Job Bank rates Good.</p>

<h3>Night janitor jobs</h3>
<p>Common with building service contractors that clean offices after hours.</p>

<h3>NOC 65312</h3>
<p>Janitors, caretakers and heavy-duty cleaners, a TEER 5 occupation.</p>

<h3>Minimum wage Canada 2026</h3>
<p>From $15.00 in Alberta to $18.25 in British Columbia, with a federal rate of $18.15.</p>

<h3>Janitor jobs in Canada for foreigners</h3>
<p>Only through a low-wage LMIA, with a 10% cap and no processing in high-unemployment cities.</p>

<h2>More Job Guides</h2>

<p>Comparing cleaning jobs and routes into Canada? These cover it:</p>

<ul>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London with No Experience</a> &mdash; what UK cleaning pays and why most of it cannot be sponsored.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; the Gulf route for cleaning staff, and the Labour Law split.</li>
    <li><a href="/blog/housekeeper-jobs-in-uk">Housekeeper Jobs in UK</a> &mdash; hotel and care home housekeeping under UK minimum wage rules.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how the LMIA works, and the 2026 limits on low-wage jobs.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the most-approved LMIA jobs, and which farm program you can use.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; another entry-level Canadian job priced by the provincial minimum wage.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; housekeeping on the American seasonal visa route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Wage data, minimum wages, LMIA rules and unemployment lists change often. Confirm the current position with the employer, your provincial employment standards office, Job Bank and IRCC, or a licensed immigration consultant, before applying or paying any fee.</p>
HTML;
    }
}
