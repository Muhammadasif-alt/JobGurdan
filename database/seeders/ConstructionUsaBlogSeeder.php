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
 * "Construction Jobs in USA for Foreigners" — the H-2B, EB-3 and H-1B routes
 * into U.S. construction work, plus the matching listing the article links to.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ConstructionUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/jobs?q=construction+visa+sponsorship';

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
        $title = 'Construction Jobs in USA for Foreigners';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'H-2B, EB-3 and H-1B explained for U.S. construction work — which roles get sponsored, 2026 salary benchmarks from laborer to construction manager, and how to verify a sponsoring contractor is real.',
                'content' => $content,
                'featured_image' => 'blogs/construction-jobs-in-usa-for-foreigners.jpg',
                'tags' => 'construction jobs usa, visa sponsorship, H-2B visa, EB-3 visa, H-1B visa, construction salary usa, jobs for foreigners, skilled trades',
                'meta_title' => 'Construction Jobs in USA for Foreigners with Visa Sponsorship (2026)',
                'meta_description' => 'H-2B, EB-3 and H-1B routes into U.S. construction work, which roles get sponsored, 2026 salary data, and how to spot a fake sponsoring contractor.',
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
            ['name' => 'US Construction Contractors (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'us-construction-contractors']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Construction Worker & Skilled Trades — Visa Sponsorship Available',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Day shifts, with seasonal overtime on active projects',
                'language' => 'English',
                'salary_currency' => 'USD',
                'salary_period' => 'Yearly',
                'salary_minimum' => 37000,
                'salary_maximum' => 79000,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'U.S. construction openings with visa sponsorship — general labor and skilled trades on H-2B and EB-3 routes, $37k-$79k, nationwide.',
                'seo_keywords' => 'construction jobs usa, visa sponsorship, H-2B visa, EB-3 visa, construction laborer jobs, skilled trades usa, carpenter jobs, welder jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>U.S. contractors are hiring across general labor and the skilled trades, and a number of them sponsor foreign workers through the H-2B and EB-3 routes. This listing points to current openings tagged with visa sponsorship.</p>

<h3>Roles typically covered</h3>
<ul>
    <li>General laborer &mdash; site cleanup, material handling, trenching, demolition support</li>
    <li>Skilled trades &mdash; carpenter, electrician, welder, mason, HVAC installer</li>
    <li>Supervisory and technical &mdash; foreman, field superintendent, cost estimator, project engineer</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Physical fitness and the ability to work outdoors in varying weather</li>
    <li>Clean background check; formal qualifications are usually not required for general labor</li>
    <li>Two or more years of documented trade experience for EB-3 skilled worker roles</li>
    <li>A relevant degree for management and engineering positions on the H-1B track</li>
    <li>English sufficient for site safety briefings and crew communication</li>
    <li>State trade licensing where applicable &mdash; electrical, plumbing and some HVAC work</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Sponsorship through H-2B (temporary/seasonal) or EB-3 (permanent) routes, depending on the contractor</li>
    <li>General laborers around $37,000&ndash;$53,000 a year ($17&ndash;$26 an hour)</li>
    <li>Combined construction occupations average roughly $65,360; superintendents around $79,920&ndash;$86,450</li>
    <li>Higher rates in high-cost states and on industrial or infrastructure projects</li>
</ul>

<p><strong>Note:</strong> sponsorship terms, timelines and eligibility are set by the individual contractor and by USCIS &mdash; not by JobGader. Verify any employer against state contractor licensing boards and the Department of Labor's public H-2B and PERM disclosure data, and never pay an upfront fee for sponsorship.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>America's construction industry has one of the most persistent labor shortages of any sector, with the Associated General Contractors regularly reporting that a large majority of firms struggle to fill open positions &mdash; everything from general laborers to superintendents. That shortage is exactly why <strong>construction jobs in USA</strong> rank among the more realistic visa-sponsored opportunities for foreign workers, spanning entry-level labor roles all the way up to project management. Here's how the visa pathways work, what the roles actually pay, and how to position yourself as a real candidate rather than a target for recruitment scams.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/jobs?q=construction+visa+sponsorship" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🏗️ Browse Construction Jobs in USA with Visa Sponsorship →
    </a>
</div>

<h2>Visa Pathways for Construction Workers</h2>

<p>There's no single "construction visa" &mdash; sponsorship happens through a few existing employment-based categories, each suited to a different skill level and time horizon.</p>

<h3>H-2B Visa (Temporary / Seasonal Labor)</h3>

<p>The most common route for general laborers, framers, roofers, and other trade positions tied to seasonal or peak-demand projects. The employer must first prove there aren't enough qualified U.S. workers available (a temporary labor certification), and the position must fit a genuinely temporary need &mdash; a single project, a seasonal build cycle, or a one-time labor peak. The program has a statutory cap of 66,000 visas a year split across two half-year allocations, and demand from construction and other seasonal industries regularly pushes that cap to its limit, so timing your application around the release windows matters.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/construction-jobs-in-usa-for-foreigners-site.jpg"
         alt="Construction worker on a U.S. building site — construction jobs in USA for foreigners with visa sponsorship"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>EB-3 Visa (Permanent / Green Card Route)</h3>

<p>Used for permanent, year-round positions &mdash; both the "skilled worker" category (trades requiring at least two years of training or experience, like electricians, welders, and carpenters) and the "other workers" category (general laborers in permanent roles). This path requires the employer to complete PERM labor certification and leads to a green card, but processing routinely takes 1&ndash;3+ years, longer for applicants from countries with heavier EB-3 backlogs (India, China, and the Philippines, for example).</p>

<h3>H-1B Visa (Specialty Occupation)</h3>

<p>Reserved for degree-requiring, professional-level roles &mdash; construction managers, project engineers, structural engineers, and estimators with a bachelor's degree in a relevant field. It's subject to an annual lottery because demand vastly exceeds the yearly cap, which makes it far less predictable than H-2B or EB-3 for entering the field.</p>

<p><strong>A note on "Tier 2 visa" searches:</strong> Tier 2 is a <em>UK</em> immigration category (and was actually replaced by the Skilled Worker visa back in December 2020) &mdash; it has no equivalent in the U.S. system. If you're specifically looking for U.S. sponsorship, the categories above (H-2B, EB-3, H-1B) are the relevant ones; Tier 2/Skilled Worker only applies if you're targeting the UK instead.</p>

<h2>Common In-Demand Roles</h2>

<ul>
    <li><strong>General laborers:</strong> site cleanup, material handling, trenching, and demolition support &mdash; typically the highest-volume H-2B hires.</li>
    <li><strong>Skilled trades:</strong> carpenters, electricians, welders, masons, and HVAC installers &mdash; often EB-3 skilled worker candidates given the training/experience requirement.</li>
    <li><strong>Management and engineering:</strong> project engineers, cost estimators, and construction superintendents &mdash; usually H-1B or, for very senior roles, sometimes EB-2/EB-3 depending on qualifications.</li>
</ul>

<h2>How to Find Construction Jobs in USA from Abroad</h2>

<ul>
    <li><strong>Job boards with sponsorship filters:</strong> search major aggregators like Indeed using "visa sponsorship" as a keyword filter alongside your trade.</li>
    <li><strong>U.S. Department of Labor Seasonal Jobs portal:</strong> a good source for genuine, currently-certified H-2B construction postings rather than recycled listings &mdash; browse it at <a href="https://seasonaljobs.dol.gov/jobs" target="_blank" rel="noopener">seasonaljobs.dol.gov</a>.</li>
    <li><strong>National and regional contractors with a track record of sponsorship:</strong> larger firms and specialty trade contractors that have filed PERM labor certifications before tend to have a smoother, faster process than a small local contractor doing it for the first time.</li>
    <li><strong>Trade unions and apprenticeship programs:</strong> some skilled trades unions have international exchange or apprenticeship arrangements that can be a legitimate alternative entry point.</li>
</ul>

<h2>Entry-Level Construction Jobs in USA for Foreigners</h2>

<p>Genuine entry-level opportunities do exist, mostly through the H-2B route for general labor roles or through EB-3's "other workers" category for permanent positions. Employers hiring for these roles are usually looking for physical fitness, reliability, and a clean background check rather than formal credentials &mdash; but be cautious of any posting promising sponsorship with no real employer verification or asking you to pay fees upfront, which is a common scam pattern in this space.</p>

<h2>Construction Jobs in USA Salary</h2>

<p>Pay varies significantly by trade, experience, region, and whether a role is unionized. Current 2026 benchmarks:</p>

<ul>
    <li><strong>General construction laborers:</strong> roughly $37,000&ndash;$53,000 a year ($17&ndash;$26/hour), with the lowest 10% under $26,000 and higher earners well above $65,000 depending on location and specialty.</li>
    <li><strong>All construction and extraction occupations combined (BLS, 2026):</strong> a national mean around $65,360 a year, with state averages ranging from about $48,650 (lower-cost states like Arkansas) up to $84,200 (Hawaii) &mdash; location has a major effect on pay.</li>
    <li><strong>First-line construction supervisors/superintendents:</strong> a median around $79,920 and a mean near $86,450, reflecting how hard these roles are to fill &mdash; superintendents were cited as the hardest role to hire for in a recent industry workforce survey.</li>
    <li><strong>Construction managers:</strong> average around $119,660 a year, though this tier typically requires a degree and significant experience, and is more realistically an H-1B-track role than an entry point.</li>
</ul>

<h2>Highest Paying Construction Jobs in USA for Foreigners</h2>

<p>If you're aiming for the top of the pay scale, the roles most worth targeting &mdash; and the ones most likely to justify H-1B sponsorship given the degree requirement &mdash; are:</p>

<ul>
    <li>Construction managers and project executives</li>
    <li>Field superintendents overseeing multiple crews or large projects</li>
    <li>Specialized trade roles in high-demand, high-cost markets (electricians and welders in industrial or infrastructure projects, for instance)</li>
    <li>Cost estimators and project engineers on large commercial or infrastructure builds</li>
</ul>

<p>These roles pay significantly more than general labor but require either a relevant engineering/construction management degree or years of documented supervisory experience &mdash; both of which strengthen a visa petition regardless of category.</p>

<h2>Construction Jobs in USA for UK Citizens</h2>

<p>The visa process doesn't differ by nationality &mdash; UK citizens go through the same H-2B, EB-3, or H-1B channels as applicants from any other country. The main practical difference is that EB-3 per-country backlogs are generally shorter for UK applicants than for a handful of high-demand countries, which can mean somewhat faster green card processing once a petition is filed. UK tradespeople with formal apprenticeship certifications (City &amp; Guilds, NVQ, etc.) should have their credentials evaluated against U.S. equivalency standards, since some states also require trade-specific licensing (electrical and plumbing work especially) on top of any visa requirement.</p>

<h2>How Construction Companies That Sponsor Foreign Workers Actually Operate</h2>

<p>Legitimate sponsoring employers:</p>

<ul>
    <li>Have an existing, verifiable business with real projects (checkable through state contractor licensing boards).</li>
    <li>File labor certifications through the Department of Labor before petitioning &mdash; this is a matter of public record.</li>
    <li>Don't ask candidates to pay recruitment or "visa guarantee" fees, since charging workers for H-2B sponsorship is restricted under federal rules.</li>
    <li>Can be cross-checked against DOL's public H-2B and PERM disclosure data if you want to verify a specific employer has actually filed on your behalf.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What's the fastest way to start working in U.S. construction as a foreigner?</h3>
<p>H-2B is typically the fastest route to actually start working, since it's built around seasonal/temporary demand &mdash; but it's employer-specific and temporary, unlike EB-3's slower path to a green card.</p>

<h3>Do I need a U.S. trade license to work construction here?</h3>
<p>It depends on the trade and state. General labor roles usually don't require licensing, but electrical, plumbing, and some HVAC work require state-specific licenses regardless of visa status.</p>

<h3>Can H-2B construction work lead to a green card later?</h3>
<p>Not directly. H-2B is a temporary, non-immigrant visa. Some workers later pursue EB-3 sponsorship, either with the same employer or a new one, but that's a separate application process.</p>

<h3>Is "construction jobs with visa sponsorship" a common scam target?</h3>
<p>Yes &mdash; be wary of listings that guarantee sponsorship with no experience, ask for upfront payment, or can't be matched to a verifiable, licensed contractor.</p>

<h3>What about construction companies that sponsor Tier 2 visas?</h3>
<p>Tier 2 is a UK visa category, not a U.S. one &mdash; if you're looking specifically for U.S. sponsorship, look for H-2B, EB-3, or H-1B sponsors instead.</p>

<h2>People Also Search For</h2>

<h3>Construction jobs in USA salary</h3>
<p>General laborers earn roughly $37,000&ndash;$53,000 a year ($17&ndash;$26/hour); the combined construction and extraction average is about $65,360, superintendents sit near $79,920&ndash;$86,450, and construction managers average around $119,660.</p>

<h3>Construction jobs in USA for foreigners with visa sponsorship</h3>
<p>Sponsorship runs through H-2B (temporary/seasonal labor), EB-3 (permanent skilled and other workers), or H-1B (degree-level management and engineering roles). There is no dedicated construction visa.</p>

<h3>Entry level construction jobs in USA for foreigners</h3>
<p>Mostly H-2B general labor roles or EB-3 "other worker" positions. Employers usually want physical fitness, reliability and a clean background check rather than formal qualifications.</p>

<h3>Highest paying construction jobs in USA for foreigners</h3>
<p>Construction managers, project executives, field superintendents, cost estimators and specialised trades in high-cost markets &mdash; all requiring either a relevant degree or documented supervisory experience.</p>

<h3>Construction companies in USA that sponsor foreign workers</h3>
<p>Larger national and regional contractors that have filed PERM labor certifications before. Verify any employer against state contractor licensing boards and the Department of Labor's public H-2B and PERM disclosure data.</p>

<h3>Construction jobs in USA for UK citizens</h3>
<p>The same H-2B, EB-3 and H-1B routes apply. EB-3 backlogs are typically shorter for UK applicants, but City &amp; Guilds or NVQ credentials may need U.S. equivalency evaluation and some states require trade licences.</p>

<h3>Construction companies that sponsor Tier 2 visa</h3>
<p>Tier 2 is a UK category, replaced by the Skilled Worker visa in December 2020. It has no U.S. equivalent &mdash; for the USA, search H-2B, EB-3 or H-1B sponsors instead.</p>

<h3>Construction jobs in USA with visa sponsorship no experience</h3>
<p>Entry-level H-2B labor roles frequently accept candidates without prior construction experience, though competition is high because the annual visa cap fills quickly.</p>

<h2>More Visa Sponsorship Guides</h2>

<p>Comparing options across industries and countries? These guides cover the other routes we track:</p>

<ul>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; EB-3 and H-2B routes, CDL requirements and realistic pay.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; H-2B and J-1 hospitality sponsorship and which roles get hired.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; what the July 2025 care worker route closure means for applicants.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/jobs?q=construction+visa+sponsorship" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Construction Jobs with Visa Sponsorship →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and isn't legal or immigration advice. Visa eligibility, caps, and requirements change &mdash; confirm current rules with USCIS or a licensed immigration attorney before making decisions.</p>
HTML;
    }
}
