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
 * "No Experience Jobs in Australia" — entry-level retail, hospitality, aged
 * care, warehouse and office work. The construction, office assistant, sales
 * and plumber guides each own one occupation, so this one owns the award
 * floors for entry-level work, junior rates, the checks aged care needs and
 * the work rights a visa holder has.
 *
 * Corrections to the draft:
 *
 * 1. It says superannuation is 11.5%. The super guarantee rose to 12% on
 *    1 July 2025, and that was the final scheduled increase.
 *
 * 2. Its salary table sits on both sides of the award. Retail and hospitality
 *    start at about $52,255 to $54,954 a year full-time, so the bottom of its
 *    $45,000 range is below the legal floor, while warehouse work starts at
 *    about $53,513, below its $55,000 floor, and aged care direct care pays
 *    about $68,006, above its $65,000 ceiling.
 *
 * 3. It never mentions junior rates. A 17-year-old in retail is paid 60% of
 *    the adult rate, so age changes the number more than the sector does.
 *
 * 4. Its mining section is unsupported. Jobs and Skills Australia puts most
 *    mining jobs in Western Australia and Queensland, not Tasmania, and
 *    Queensland coal work needs the Standard 11 induction first.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NoExperienceJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-no-experience-jobs.html';

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
        $title = 'No Experience Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Australian award rates rose 4.75% on 1 July 2026, entry-level retail pays $27.81 an hour and aged care direct care about $68,000 a year, super is 12% rather than 11.5%, and a 17-year-old in retail is paid 60% of the adult rate.',
                'content' => $content,
                'featured_image' => 'blogs/no-experience-jobs-in-australia.jpg',
                'tags' => 'no experience jobs australia, entry level jobs australia, retail award rate 2026, aged care worker pay australia, warehouse jobs australia, junior pay rates australia, casual loading australia, working holiday visa work rules, student visa work hours',
                'meta_title' => 'No Experience Jobs in Australia 2026: Real Pay and Rules',
                'meta_description' => 'No experience jobs in Australia: award pay from 1 July 2026, junior rates, the 12% super rate, aged care checks and the student visa work limit.',
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
            ['name' => 'Australian Retail, Hospitality, Care & Warehouse Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-entry-level-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Entry-Level Jobs — Retail, Hospitality, Aged Care, Warehouse and Admin Roles, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Casual, part-time and full-time; evening, weekend and early morning shifts common',
                'language' => 'English',
                // Entry-level pay is set by the award and the worker's age, so
                // no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry-level retail, hospitality, aged care, warehouse and office roles with Australian employers. Training provided; award rates apply.',
                'seo_keywords' => 'no experience jobs australia, entry level jobs australia, retail assistant jobs, aged care assistant jobs, warehouse jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian retailers, cafes, aged care providers, warehouses and offices hire staff with no formal experience and train them on the job. Pay is set by the relevant modern award, not by the employer alone.</p>

<h3>What the work involves</h3>
<p>Serving customers and handling registers, preparing food and drinks, supporting residents and clients with daily care, picking and packing stock, or answering phones and keeping records.</p>

<h3>Requirements</h3>
<ul>
    <li>Reliability, clear communication and the right to work in Australia</li>
    <li>For aged care and disability work, a police certificate or an NDIS worker screening check</li>
    <li>For construction sites, a White Card; for Queensland coal mines, the Standard 11 induction</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Award minimums from 1 July 2026.</strong> $27.81 an hour in retail, $1,004.90 a week in hospitality, $1,024.70 for a clerk and $1,307.80 for an aged care direct carer</li>
    <li><strong>Casuals</strong> get a 25% loading instead of paid leave, and superannuation of 12% is paid on top of your wage</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask whether the rate is "plus super" or "inclusive of super", in writing</strong>, and whether the job is casual, part-time or permanent.</p>

<p><strong>Note:</strong> pay, hours and entitlements are set by the Fair Work Commission's awards and by each employer &mdash; not by JobGader. Check the current pay guide on fairwork.gov.au before you accept an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Australian employers do hire people with no experience, and they train them. What most guides get wrong is the money. Entry-level pay here is set by law through modern awards, and those rates changed on 1 July 2026, so you can check any offer against a published number before you accept it.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-no-experience-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127482; Browse No Experience Jobs in Australia &rarr;
    </a>
</div>

<h2>What Entry-Level Work Pays From 1 July 2026</h2>

<p>The Fair Work Commission raised award wages by <strong>4.75%</strong> from 1 July 2026. The <strong>National Minimum Wage is $26.44 an hour</strong>, or $1,004.90 a week, which is about <strong>$52,255</strong> across a full-time year. Entry classifications in the awards that cover these jobs:</p>

<ul>
    <li><strong>Retail, Level 1:</strong> <strong>$1,056.80</strong> a week, or $27.81 an hour &mdash; about $54,954 a year</li>
    <li><strong>Hospitality, food and beverage attendant grade 1:</strong> $1,004.90 a week &mdash; about $52,255. The introductory level is $978.10.</li>
    <li><strong>Warehouse, storeworker grade 1:</strong> $1,029.10 a week, or $27.08 an hour, on commencement, rising to $1,041.60 after three months &mdash; about $53,513 a year</li>
    <li><strong>Office, clerk Level 1 Year 1:</strong> $1,024.70 a week, or $26.97 an hour &mdash; about $53,284</li>
    <li><strong>Aged care, direct carer:</strong> <strong>$1,307.80</strong> a week &mdash; about $68,006 a year, after the work value case increases. The introductory rate is $1,239.00.</li>
    <li><strong>Disability and community services (SCHADS), Level 1:</strong> $1,046.90 a week &mdash; about $54,439</li>
</ul>

<p>So the guides' $45,000 floor for retail and hospitality is below the legal minimum, their $55,000 floor for warehousing is above what the award actually starts at, and aged care pays more than the $65,000 ceiling they give it. Casuals are paid a <strong>25% loading</strong> instead of paid leave: a Level 1 hospitality casual must get at least <strong>$33.05 an hour</strong>.</p>

<h2>Your Age Changes the Rate</h2>

<p>No guide mentions this, and it decides what a first job pays. Under the General Retail Industry Award, juniors are paid a percentage of the adult rate:</p>

<ul>
    <li><strong>Under 16:</strong> 45%</li>
    <li><strong>16:</strong> 50%</li>
    <li><strong>17:</strong> <strong>60%</strong></li>
    <li><strong>18:</strong> 70%</li>
    <li><strong>19:</strong> 80%</li>
    <li><strong>20, in the first six months with the employer:</strong> 90%, then 100%</li>
</ul>

<h2>Superannuation Is 12%, Not 11.5%</h2>

<p>Guides still quote 11.5%, which was the 2024-25 rate. The super guarantee rose to <strong>12% on 1 July 2025</strong>, and the ATO says that was the final scheduled increase. It is paid on top of your wage, so always ask whether an advertised salary includes it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/no-experience-jobs-in-australia-team.jpg"
         alt="A group of entry-level workers in construction, cafe, student, office and healthcare clothing in front of Sydney Harbour"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Aged Care and Disability: The Best-Paid Entry Route</h2>

<p>Guides are right that aged care hires newcomers, and the award proves it: it sets a pay point for a direct carer with <strong>less than 3 months' aged care experience</strong>, so a Certificate III is not needed to start. What you do need is a check:</p>

<ul>
    <li><strong>Aged care:</strong> a police certificate no older than three years, or an NDIS worker screening check instead. New rules under the Aged Care Act took effect on 1 November 2025.</li>
    <li><strong>Disability work:</strong> an <strong>NDIS Worker Screening Check</strong> for risk-assessed roles with a registered provider, valid for up to five years.</li>
</ul>

<h2>The Market You Are Applying Into</h2>

<p>The ABS put the seasonally adjusted unemployment rate at <strong>4.5%</strong> in July 2026, with youth unemployment at <strong>10.4%</strong>, down 0.3 points. So competition for first jobs is real, but the market is not closed.</p>

<h2>Mining and Site Work: Check the Ticket First</h2>

<p>Guides promise entry-level mining work in regional Queensland, Western Australia and Tasmania. Jobs and Skills Australia puts most mining jobs in <strong>Western Australia and Queensland</strong>, and nothing official supports Tasmania as an entry-level mining market. Before a start date you normally need a ticket:</p>

<ul>
    <li><strong>Queensland coal mines:</strong> the <strong>Standard 11</strong> induction, covering units such as working safely and applying initial first aid, before you start work.</li>
    <li><strong>Construction sites:</strong> a <strong>White Card</strong>. In NSW you must be at least 14 to do the training, cards from other states are accepted, and a card becomes void if you have not done construction work for two years.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/no-experience-jobs-in-australia-workers.jpg"
         alt="Entry-level workers including a tradesperson, nurse, chef and delivery driver smiling by Sydney Harbour with a laptop on the table"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>If You Are Here on a Visa</h2>

<ul>
    <li><strong>Working Holiday Maker (417 and 462):</strong> you may normally work only <strong>6 months with one employer</strong>. Since 1 January 2024 that limit does not apply in critical sectors, including agriculture, food processing, health, aged and disability care, childcare, tourism and hospitality anywhere in Australia, and construction and mining in northern Australia.</li>
    <li><strong>Student visa (500):</strong> <strong>48 hours a fortnight</strong> while your course is in session, and unlimited hours when it is not.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum wage in Australia in 2026?</h3>
<p>$26.44 an hour, or $1,004.90 a week, from 1 July 2026, after a 4.75% increase. That is about $52,255 a year full-time.</p>

<h3>How much does an entry-level retail job pay?</h3>
<p>Retail Level 1 is $1,056.80 a week, or $27.81 an hour, for an adult from 1 July 2026 &mdash; about $54,954 a year, plus 12% super.</p>

<h3>Is superannuation 11.5% or 12%?</h3>
<p>12%. The rate rose on 1 July 2025 and that was the final scheduled increase.</p>

<h3>Do I need a Certificate III to work in aged care?</h3>
<p>Not to start. The award has a pay point for a direct carer with less than three months' experience, but you do need a police certificate or an NDIS worker screening check.</p>

<h3>What do juniors get paid?</h3>
<p>A percentage of the adult rate: 45% under 16, 50% at 16, 60% at 17, 70% at 18, 80% at 19 and 90% or 100% at 20, depending on how long you have been with the employer.</p>

<h3>What is the casual loading?</h3>
<p>25% on top of the base rate, in place of paid leave. A Level 1 hospitality casual must receive at least $33.05 an hour.</p>

<h3>Can I get a mining job with no experience?</h3>
<p>Entry-level site roles exist, but Queensland coal mines require the Standard 11 induction first, and most mining jobs are in Western Australia and Queensland.</p>

<h3>How many hours can I work on a student visa?</h3>
<p>48 hours a fortnight while studying, and unlimited hours during course breaks.</p>

<h2>People Also Search For</h2>

<h3>Australian minimum wage 2026</h3>
<p>$26.44 an hour from 1 July 2026, after the 4.75% annual wage review increase.</p>

<h3>Retail award rate 2026</h3>
<p>$27.81 an hour at Level 1 for adults, or about $34.76 for casuals with the loading.</p>

<h3>Aged care worker pay Australia</h3>
<p>$1,307.80 a week for a direct carer, about $68,006 a year, after the work value increases.</p>

<h3>Warehouse jobs Australia pay</h3>
<p>Storeworker grade 1 starts at $1,029.10 a week, about $53,513 a year.</p>

<h3>Junior pay rates Australia</h3>
<p>60% of the adult rate at 17 in retail, rising to 100% at 20 after six months.</p>

<h3>NDIS worker screening check</h3>
<p>Required for risk-assessed roles with registered providers, and valid for up to five years.</p>

<h3>Working holiday visa 6 month rule</h3>
<p>Six months with one employer, with exemptions since January 2024 for care, hospitality, agriculture and other critical sectors.</p>

<h3>Youth unemployment Australia</h3>
<p>10.4% in July 2026, against an overall rate of 4.5%.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level work in Australia and elsewhere? These cover it:</p>

<ul>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the award floor, the casual loading and what the White Card really requires.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; administrative pay against the same minimum wage.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; retail and field sales pay, and when commission may replace a wage.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; where an apprenticeship leads, and what the licence takes.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest position on American entry-level visas.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; the same counter work priced by state wage floors.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; the American version of a first shop-floor job, and how its hiring works.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or careers advice. Award rates, superannuation, screening rules and visa conditions change. Check the current pay guide on fairwork.gov.au and your visa conditions on immi.homeaffairs.gov.au before accepting a job.</p>
HTML;
    }
}
