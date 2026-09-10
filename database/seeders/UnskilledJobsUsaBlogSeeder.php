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
 * "Unskilled Jobs in USA for Foreigners" — a sector guide rather than one
 * vacancy, so the apply link goes to an Indeed search and the post carries no
 * JobPosting markup.
 *
 * Two corrections. The draft put the EB-3 "other worker" green card at 24 to 36
 * months; that category is retrogressed for every country and is the slowest
 * part of EB-3, which matters because "EB-3 unskilled green card" is sold
 * aggressively to workers in South Asia at high cost. And it presented H-2A and
 * H-2B pay as employer-set, when both are floors fixed by the Department of
 * Labor — the Adverse Effect Wage Rate for H-2A and a prevailing wage
 * determination for H-2B — which a worker can look up and hold an employer to.
 *
 * The draft's apply link was a Google Ads click-tracking URL that would expire;
 * it is replaced with the plain Indeed search.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class UnskilledJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-unskilled-jobs.html';

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
        $title = 'Unskilled Jobs in USA for Foreigners';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Which visa routes actually exist for jobs needing no degree, why H-2A and H-2B pay is set by the government rather than the employer, how long the EB-3 other-worker green card really takes, and what nobody is allowed to charge you.',
                'content' => $content,
                'featured_image' => 'blogs/unskilled-jobs-in-usa-for-foreigners.jpg',
                'tags' => 'unskilled jobs in usa, unskilled jobs usa for foreigners, h-2a visa, h-2b visa, eb-3 other worker green card, farm jobs usa visa sponsorship, warehouse jobs usa, jobs in usa with free visa and air ticket',
                'meta_title' => 'Unskilled Jobs in USA for Foreigners',
                'meta_description' => 'Unskilled jobs in the USA for foreigners: the H-2A, H-2B and EB-3 routes, who sets the wage, how long the green card queue is, and the fees nobody may charge.',
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
            ['name' => 'US Seasonal & Labour Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-unskilled-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'General Labour & Seasonal Work — US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, often with overtime; agricultural and seasonal work follows the season',
                'language' => 'English',
                // H-2A pay is the state Adverse Effect Wage Rate and H-2B pay is
                // a prevailing wage determination, so the figure is per job and
                // per state rather than a single national range.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'US roles needing no degree or certification: farm and seasonal work, warehousing, food processing, cleaning and general labour. Apply through the employer or the DOL portal.',
                'seo_keywords' => 'unskilled jobs usa, h-2a visa jobs, h-2b visa jobs, eb-3 other worker, farm jobs usa, warehouse jobs usa sponsorship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US employers hire year-round for roles that need no degree and no certificate: farm and nursery labour, landscaping and groundskeeping, housekeeping and cleaning, warehouse and stock handling, light assembly and line work in manufacturing and food processing, kitchen and dishwashing roles, and general construction labour. Training is on the job; what is screened is reliability, stamina and the ability to follow a process.</p>

<h3>Who sets the wage &mdash; and why that matters</h3>
<p>On the sponsored routes, the pay floor is not the employer's choice. <strong>H-2A</strong> agricultural jobs must be paid at least the <strong>Adverse Effect Wage Rate</strong>, published per state each year. <strong>H-2B</strong> jobs must be paid at least a <strong>prevailing wage</strong> determined by the Department of Labor for that occupation and area. Both are usually above the federal or state minimum wage, and both are published, so you can check what a job is legally required to pay before you accept it.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree, certificate or prior experience for most roles</li>
    <li>Physical fitness for standing, lifting and outdoor work</li>
    <li>A valid passport and the documents your employer's immigration counsel requests</li>
    <li>Basic English for safety instructions in most workplaces, though many crews work in mixed languages</li>
    <li>Willingness to work a season away from home for the temporary routes</li>
</ul>

<h3>What is on offer</h3>
<ul>
    <li>H-2A: free housing, and inbound and outbound travel costs reimbursed by the employer under the programme rules</li>
    <li>H-2B: housing sometimes provided or arranged, varying by employer</li>
    <li>Overtime in warehousing, manufacturing and peak agricultural periods</li>
    <li>Returning-worker relationships &mdash; many seasonal employers rehire the same crews each year</li>
</ul>

<h3>Before you apply</h3>
<p><strong>You may not be charged a recruitment fee.</strong> Both the H-2A and H-2B programmes prohibit workers being charged for recruitment, and H-2A employers are required to cover inbound travel and provide housing at no cost. Anyone asking you to pay for a job offer, a visa slot or a place on a list is operating outside the rules. Real temporary postings are published on the US Department of Labor's Seasonal Jobs portal.</p>

<p><strong>Note:</strong> wage rates, programme rules and visa availability are set by the US government &mdash; not by JobGader. Verify any offer against the official job order before paying anything or travelling.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Thousands of people move to the United States every year for work that needs no degree and no certificate &mdash; picking fruit, cleaning rooms, stacking a warehouse, working a food processing line. The routes are real and the wages are set by law rather than by whoever is hiring. What ruins this search is everything built around it: agencies selling green cards on timelines that do not exist, and fees that nobody is permitted to charge. This guide separates the two.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-unskilled-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🛠️ Browse General Labour Jobs in the USA &rarr;
    </a>
</div>

<h2>What Counts as an &quot;Unskilled&quot; Job</h2>

<p>It is an immigration term rather than a judgement. It means a role that does not require a university degree, a licence or a formal certification &mdash; the employer trains you. In practice that covers agriculture, hospitality, warehousing, food processing, cleaning services and general construction labour: physical, on-site work that cannot be automated or moved offshore, which is exactly why the shortages are persistent enough to justify bringing workers in.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/unskilled-jobs-in-usa-categories.jpg"
         alt="Unskilled job categories in the USA — warehouse, cleaning, food service, construction and delivery"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Wage Is Set by the Government, Not the Employer</h2>

<p>This is the most useful thing on this page, and almost no guide says it. On the sponsored routes you are not negotiating a rate &mdash; there is a legal floor, it is published, and you can look it up:</p>

<ul>
    <li><strong>H-2A (agriculture)</strong> must pay at least the <strong>Adverse Effect Wage Rate</strong>, set separately for each state and revised annually. It is designed to sit above the local minimum wage so that hiring abroad does not undercut domestic workers.</li>
    <li><strong>H-2B (non-agricultural)</strong> must pay at least a <strong>prevailing wage determination</strong> issued by the Department of Labor for that occupation in that area.</li>
</ul>

<p>Two consequences follow. First, the ranges you see quoted &mdash; roughly $14 to $20 an hour on H-2A and $15 to $25 on H-2B &mdash; are not employer generosity; they are what the rate happens to be in that state for that job. Second, and more importantly, <strong>an offer below the applicable rate is unlawful</strong>, and you can check the figure yourself rather than taking a recruiter's word for it.</p>

<h2>The Three Routes, Honestly Described</h2>

<h3>H-2A &mdash; seasonal agricultural work</h3>

<p>Fruit and vegetable harvesting, planting, nursery and general farm labour. Tied to one employer and one season, typically a few months, and renewable in later years &mdash; many employers rehire the same crews. The programme requires the employer to provide <strong>housing at no cost</strong> and to <strong>reimburse inbound and outbound travel</strong>. There is no annual cap on H-2A, which is why it is the largest of these routes by volume.</p>

<h3>H-2B &mdash; seasonal non-agricultural work</h3>

<p>Landscaping, hospitality, resort staffing, housekeeping, seafood processing, amusement parks. Also employer-tied and season-tied. Unlike H-2A it has a hard annual cap &mdash; 66,000 visas split across two half-year allocations, with supplemental releases in some years &mdash; and demand exceeds it, so timing matters and early applications genuinely help. Our <a href="/blog/hotel-jobs-in-usa-for-foreigners">guide to hotel jobs in the USA</a> covers how that cap plays out in practice.</p>

<h3>EB-3 &quot;other workers&quot; &mdash; the permanent route, and the slow one</h3>

<p>This is the category sold hardest and understood least. EB-3 has three sub-groups, and unskilled roles fall into <strong>&quot;other workers&quot;</strong> &mdash; the smallest allocation and the slowest-moving of the three. It leads to a green card rather than a temporary permit, which is why it is attractive, and it requires the employer to complete PERM labour certification first.</p>

<p><strong>It does not take 24 to 36 months.</strong> The whole EB-3 category is currently retrogressed for every country of birth, meaning approved petitions sit in a queue waiting for a visa number. Final action dates as of early 2026 sit at November 2013 for India, May 2021 for mainland China, August 2023 for the Philippines and October 2023 for Mexico, with everywhere else &mdash; Pakistan included &mdash; also behind the present. The &quot;other workers&quot; sub-category typically moves more slowly still.</p>

<p>None of that makes EB-3 a bad route. It makes it a <em>long</em> one, and anyone quoting you a two-to-three year certainty is either not checking the Visa Bulletin or is selling you something. Look up the current month's bulletin for your country of birth before you plan around any date.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/unskilled-jobs-in-usa-workers.jpg"
         alt="Workers in general labour roles in the United States"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Jobs Actually Are</h2>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;text-align:left;">
            <th style="padding:10px;border:1px solid #e5e7eb;">Industry</th>
            <th style="padding:10px;border:1px solid #e5e7eb;">Common roles</th>
            <th style="padding:10px;border:1px solid #e5e7eb;">Usual route</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Agriculture and nursery</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Farm labourer, harvester, greenhouse assistant</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">H-2A</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Hospitality and food service</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Housekeeper, dishwasher, kitchen helper</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">H-2B</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Landscaping and grounds</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Groundskeeper, landscape labourer</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">H-2B</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Manufacturing and food processing</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Line operator, light assembly, packer</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EB-3 other workers</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Warehousing and cleaning</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Stock handler, janitorial, facilities</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">EB-3 other workers</td>
        </tr>
    </tbody>
</table>

<p>The pattern is worth noticing: the seasonal routes cover seasonal industries, and the permanent route covers year-round ones. An employer offering a green card for fruit picking, or a seasonal visa for a permanent factory job, has the structure backwards.</p>

<h2>&quot;Free Visa and Air Ticket&quot; &mdash; What Is Actually Required</h2>

<p>This phrase is searched constantly and is half true. Under <strong>H-2A</strong>, the employer is <em>required</em> to provide housing at no cost to the worker and to reimburse inbound and outbound transport &mdash; that is programme rule, not a perk. Under <strong>H-2B</strong>, travel and housing arrangements vary by employer and are worth confirming in writing.</p>

<p>What is never true is the version where you pay for it. <strong>Both programmes prohibit charging workers recruitment fees.</strong> If someone asks you for money to secure a job offer, a visa appointment, a place on a list or a &quot;processing&quot; step, that is the fraud this search attracts, and paying it is how people lose years of savings. The petition and filing costs are the employer's to bear.</p>

<h2>How to Find Real Postings</h2>

<ol>
    <li><strong>Start with the Department of Labor's Seasonal Jobs portal.</strong> Employers with approved H-2A and H-2B job orders publish them there, with the wage, the hours and the housing terms stated. It is the closest thing to a verified list.</li>
    <li><strong>Check the employer exists independently</strong> &mdash; a real farm, resort or plant with an address and a phone number.</li>
    <li><strong>Match the route to the job.</strong> Seasonal work means H-2A or H-2B; a permanent factory or cleaning role means EB-3 and a much longer horizon.</li>
    <li><strong>Look up the wage floor</strong> for that state and occupation, and compare it with what you are being offered.</li>
    <li><strong>Read the job order, not the advertisement.</strong> The official order states the period of need, the hours guaranteed, the deductions and the housing arrangement.</li>
    <li><strong>Pay nobody.</strong> No fee, at any stage, to anyone.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much do unskilled workers earn in the USA?</h3>
<p>Broadly $14 to $25 an hour, but on sponsored routes the figure is not the employer's choice: H-2A pays at least the state Adverse Effect Wage Rate and H-2B at least a Department of Labor prevailing wage for that occupation and area. Both are published, so you can check what a job must pay.</p>

<h3>How can I get an unskilled job in the USA as a foreigner?</h3>
<p>Through employer sponsorship on H-2A for seasonal farm work, H-2B for seasonal non-farm work, or EB-3 &quot;other workers&quot; for permanent roles. You cannot apply for these visas without an employer petition, and you may not be charged a recruitment fee for one.</p>

<h3>How long does the EB-3 other worker green card take?</h3>
<p>Longer than commonly advertised. The category is retrogressed for every country of birth, with final action dates in early 2026 ranging from November 2013 for India to 2023 for the Philippines and Mexico, and &quot;other workers&quot; moves more slowly than the rest of EB-3. Check the current Visa Bulletin rather than any quoted timeline.</p>

<h3>Which unskilled jobs include free housing and flights?</h3>
<p>H-2A agricultural jobs must provide housing at no cost and reimburse inbound and outbound travel &mdash; that is a programme requirement. On H-2B it varies by employer and should be confirmed in writing before you accept.</p>

<h3>Can I be asked to pay for a US job offer or visa?</h3>
<p>No. Both the H-2A and H-2B programmes prohibit charging workers recruitment fees, and petition costs belong to the employer. Any request for payment to secure an offer, an appointment or a place on a list is the fraud this category attracts.</p>

<h3>Is there a cap on these visas?</h3>
<p>H-2A has no annual cap. H-2B is capped at 66,000 a year across two half-year allocations, with supplemental releases in some years, and demand regularly exhausts it &mdash; so applying early in the cycle genuinely matters.</p>

<h3>Can a seasonal visa lead to a green card?</h3>
<p>Not on its own. H-2A and H-2B are temporary and employer-tied. A move to permanent residence needs a separate employer petition on an immigrant route such as EB-3, with its own timeline.</p>

<h3>What is the easiest unskilled job to get sponsored?</h3>
<p>Seasonal agricultural work through H-2A, because there is no annual cap and the volume is the largest. Dishwashing, housekeeping and groundskeeping on H-2B are next, subject to the cap.</p>

<h2>People Also Search For</h2>

<h3>Unskilled jobs in USA for foreigners</h3>
<p>Farm labour, housekeeping, warehousing, food processing and general construction, sponsored through H-2A, H-2B or EB-3 depending on whether the work is seasonal or permanent.</p>

<h3>H-2A visa jobs</h3>
<p>Seasonal agricultural work with no annual cap, free employer-provided housing, reimbursed travel and pay at the state Adverse Effect Wage Rate.</p>

<h3>H-2B visa jobs</h3>
<p>Seasonal non-agricultural work &mdash; landscaping, hospitality, seafood processing &mdash; capped at 66,000 a year and paid at a Department of Labor prevailing wage.</p>

<h3>EB-3 unskilled green card</h3>
<p>The permanent route for year-round roles. Retrogressed for every country, with the &quot;other workers&quot; sub-category the slowest part of it.</p>

<h3>Jobs in USA with free visa and air ticket</h3>
<p>H-2A requires housing at no cost and reimbursed travel. Nobody may charge you for the visa or the job offer on either temporary route.</p>

<h3>Farm jobs in USA with visa sponsorship</h3>
<p>The largest sponsored category for work needing no qualification, published as official job orders on the Department of Labor's seasonal portal.</p>

<h3>Warehouse jobs in USA for foreigners</h3>
<p>Year-round rather than seasonal, so usually the EB-3 route with a PERM labour certification and a long visa queue behind it.</p>

<h3>Unskilled jobs in USA with visa sponsorship 2026</h3>
<p>Apply early in the H-2B cycle, check the wage floor for the state, and verify every posting against the official job order.</p>

<h2>More Job Guides</h2>

<p>Looking at specific sectors or other destinations? These cover them:</p>

<ul>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the H-2B and J-1 routes into hospitality, and how the cap works in practice.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; trades sponsorship and where the demand actually sits.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; CDL requirements and the EB-3 route for drivers.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the licensed route, and how the same green card queue affects it.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the largest American entry point, and the wage floor that varies by state.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; clerical entry work, and the projection to see before choosing it.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; delivery work in the US, and why it is not a visa route.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; the most common first job in America, and why it is not a visa route either.</li>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; why a visa holder cannot carry a gun, and which states hire non-citizens.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Wage rates, visa caps and Visa Bulletin dates change &mdash; confirm the current position with the US Department of Labor, the monthly Visa Bulletin, or a licensed immigration attorney before paying any fee or travelling.</p>
HTML;
    }
}
