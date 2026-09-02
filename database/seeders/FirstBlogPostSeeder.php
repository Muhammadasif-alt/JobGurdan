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
 * The site's first blog post — a guide to U.S. truck-driver visa sponsorship —
 * plus the matching job listing it links to.
 *
 * Both are created with updateOrCreate, so re-running this will not duplicate
 * them, and any edits made in the admin panel are overwritten deliberately.
 */
class FirstBlogPostSeeder extends Seeder
{
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
                'description' => 'Guides on U.S. work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Truck Driver Jobs in USA with Visa Sponsorship';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'How U.S. trucking sponsorship actually works in 2026 — the EB-3 and H-2B routes, CDL and experience requirements, realistic salary ranges, and how to spot a legitimate sponsor.',
                'content' => $content,
                'tags' => 'truck driver jobs, visa sponsorship, EB-3 visa, H-2B visa, CDL jobs, USA jobs, foreign drivers',
                'featured_image' => 'blogs/truck-driver-jobs-usa-visa-sponsorship.jpg',
                'meta_title' => 'Truck Driver Jobs in USA with Visa Sponsorship (2026 Guide)',
                'meta_description' => 'EB-3 and H-2B routes explained, CDL and experience requirements, 2026 salary ranges, and how to find legitimate U.S. trucking sponsors.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Trucking Carriers (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'us-trucking-carriers']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Truck Driver (CDL-A) — Visa Sponsorship Available',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Long-haul / OTR schedules',
                'language' => 'English',
                'salary_currency' => 'USD',
                'salary_period' => 'Yearly',
                'salary_minimum' => 50000,
                'salary_maximum' => 82000,
                'application_url' => 'https://www.indeed.com/q-truck-driver-america-with-visa-sponsorship-jobs.html?vjk=a80f6fa975d8fbce',
                'meta_description' => 'CDL-A truck driver openings in the USA with visa sponsorship — EB-3 and H-2B routes, $50k–$82k, nationwide.',
                'seo_keywords' => 'truck driver jobs usa, visa sponsorship, CDL-A, EB-3 visa, H-2B visa, OTR driver',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>U.S. trucking carriers are hiring long-haul (OTR) drivers, and a number of them sponsor foreign drivers through the EB-3 and H-2B visa routes. This listing points to current openings tagged with visa sponsorship.</p>

<h3>Requirements</h3>
<ul>
    <li>Class A CDL, or willingness to complete employer-supported CDL training</li>
    <li>1&ndash;2 years of verifiable commercial driving experience preferred</li>
    <li>Clean MVR (motor vehicle record) and background check</li>
    <li>Ability to pass a DOT physical exam</li>
    <li>English proficiency sufficient to read road signs and communicate with dispatch</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Visa sponsorship through EB-3 (permanent) or H-2B (temporary) routes, depending on the carrier</li>
    <li>Median pay around $57,000/year; typical range $50,000&ndash;$82,000 depending on route, state and endorsements</li>
    <li>Sign-on bonuses common for new CDL-A drivers</li>
    <li>Higher rates for specialised freight &mdash; hazmat, tanker, refrigerated</li>
</ul>

<p><strong>Note:</strong> sponsorship terms, timelines and eligibility are set by the individual carrier and by USCIS &mdash; not by JobGader. Read each posting in full before applying, and never pay an upfront "processing fee" for sponsorship.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The U.S. trucking industry moves about 70% of the country's freight, and it has been short on qualified drivers for years. That shortage is exactly why <strong>truck driver jobs in USA</strong> are one of the more realistic blue-collar visa sponsorship opportunities available to foreign workers today &mdash; but "visa sponsorship" doesn't mean a guaranteed job offer, and it comes with real requirements around licensing, experience, and paperwork. This guide breaks down how the process actually works, which visas apply, what it pays, and how to position yourself as a strong candidate.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-truck-driver-america-with-visa-sponsorship-jobs.html?vjk=a80f6fa975d8fbce" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🚛 Browse Truck Driver Jobs in USA with Visa Sponsorship →
    </a>
</div>

<h2>Why U.S. Trucking Companies Sponsor Foreign Drivers</h2>

<p>The American Trucking Associations has flagged a persistent driver shortfall for years, driven by an aging workforce, high turnover, and rising freight volumes. To fill the gap, a growing number of <strong>U.S. trucking companies hiring foreign drivers</strong> are willing to sponsor work visas, particularly for long-haul (OTR) routes that many domestic drivers avoid because of time away from home. This demand is what makes driving jobs in USA with visa sponsorship a genuine, if competitive, path into the American labor market.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/truck-driver-jobs-usa-visa-sponsorship-cab.jpg"
         alt="Truck driver on a U.S. highway — truck driver jobs in USA with visa sponsorship"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Two Main Visa Pathways</h2>

<p>There isn't a dedicated "truck driver visa." Instead, sponsorship happens through one of two existing employment-based categories:</p>

<h3>1. EB-3 Visa (Permanent / Green Card Route)</h3>

<p>The EB-3 "skilled worker" and "other worker" categories are the most common path used by long-haul carriers looking to sponsor drivers permanently. Key points:</p>

<ul>
    <li>Leads directly to a <strong>green card</strong> (permanent residency), not just a temporary work permit.</li>
    <li>Requires the employer to complete <strong>PERM labor certification</strong> with the Department of Labor, proving no qualified U.S. worker is available for the role.</li>
    <li>Processing is slow &mdash; often 1&ndash;3+ years depending on your country of origin, since EB-3 has per-country annual limits and backlogs (these are longer for applicants from countries like India, China, and the Philippines).</li>
    <li>Best suited to drivers who want long-term settlement, not a short seasonal stint.</li>
</ul>

<h3>2. H-2B Visa (Temporary / Seasonal Route)</h3>

<p>The H-2B program covers temporary, non-agricultural labor, and trucking is one of the industries regularly included in supplemental allocations when Congress raises the annual cap. Relevant facts for <strong>h2b visa truck driver jobs</strong>:</p>

<ul>
    <li>The statutory cap is 66,000 visas per fiscal year (33,000 for each half), and demand from employers routinely exceeds this &mdash; in fiscal year 2026, the government issued a temporary rule releasing an extra 64,716 supplemental visas across critical sectors, trucking included, but even that expanded pool filled quickly.</li>
    <li>H-2B status is tied to a specific employer and a defined period of "temporary need" (peak season, seasonal contracts, etc.) &mdash; it is not a path to a green card on its own.</li>
    <li>Because the cap fills fast each year, timing your application around the release windows matters as much as finding an employer.</li>
</ul>

<p><strong>Bottom line:</strong> if you want permanent settlement, EB-3 is the realistic (if slow) route. If you want a quicker, temporary way to gain U.S. driving experience, H-2B is worth watching &mdash; but expect real competition for a limited number of slots.</p>

<h2>Core Requirements for Sponsored Truck Driver Jobs</h2>

<p>Whichever visa applies, most employers look for the same baseline:</p>

<ul>
    <li><strong>Commercial Driver's License (CDL):</strong> A foreign license alone isn't enough &mdash; you'll need a U.S. CDL, or an employer willing to support you through CDL training after you arrive. Class A CDL (for tractor-trailers) is the most requested for long-haul freight; see the <a href="https://www.onetonline.org/link/summary/53-3032.00" target="_blank" rel="noopener">O*NET occupational profile</a> for the full skill and licensing breakdown.</li>
    <li><strong>Verifiable driving experience:</strong> Most sponsoring carriers ask for at least 1&ndash;2 years of commercial driving experience, ideally with a clean driving record and no major violations.</li>
    <li><strong>Clean background and driving record:</strong> MVR (motor vehicle record) and criminal background checks are standard.</li>
    <li><strong>Physical requirements:</strong> Ability to pass a DOT physical exam, since federal regulations require drivers to be medically certified to operate commercial vehicles.</li>
    <li><strong>English proficiency:</strong> Enough to read road signs, communicate with dispatch, and pass required exams &mdash; U.S. federal regulations require CDL holders to demonstrate sufficient English competency.</li>
</ul>

<h3>Can You Get Hired With No Experience?</h3>

<p>Genuine <strong>USA truck driver jobs with visa sponsorship no experience</strong> postings do exist, but they're less common and usually tied to a training pipeline &mdash; the employer sponsors your CDL training in exchange for a work commitment once you're licensed. Be cautious of postings that promise sponsorship with zero experience and no training structure; that's a common red flag for scams (more on that below).</p>

<h2>Truck Driver Jobs in USA with Visa Sponsorship: Salary Expectations</h2>

<p>Pay varies widely by route type, state, and experience, so treat any single number as a rough guide rather than a guarantee:</p>

<ul>
    <li>U.S. Bureau of Labor Statistics data puts the <strong>median</strong> annual wage for heavy and tractor-trailer drivers at roughly <strong>$57,000</strong>, with the bottom 10% under $39,000 and the top 10% over $79,000.</li>
    <li>Broader 2026 market estimates (job boards and industry pay reports) put typical earnings closer to <strong>$58,000&ndash;$82,000</strong>, with new CDL-A drivers often starting around $50,000&ndash;$70,000 in year one, boosted by sign-on bonuses.</li>
    <li>Specialized freight &mdash; hazmat, tanker, refrigerated &mdash; and higher-cost states like California, New York, and Washington tend to pay above the national average.</li>
    <li>Long-haul (OTR) pay is often structured per mile rather than as a flat salary, so total take-home depends heavily on miles driven and time on the road.</li>
</ul>

<p>Foreign-sponsored drivers, especially those on H-2B or early in an EB-3 process, often start on the lower end of these ranges while building U.S. experience, with pay improving as tenure and endorsements grow.</p>

<h2>How to Find Legitimate Sponsors</h2>

<ul>
    <li><strong>Job boards and aggregators:</strong> Platforms like Indeed and ZipRecruiter list postings from carriers and staffing agencies that explicitly mention visa or green card sponsorship &mdash; always read the full job description, not just the headline, since "sponsorship" language is sometimes used loosely.</li>
    <li><strong>Mid-size and large carriers with international recruiting arms:</strong> These are more likely to have gone through PERM certification before, since it's a repeatable process for them.</li>
    <li><strong>Immigration attorneys or licensed recruiters:</strong> A one-time consultation can help you verify whether a job offer's sponsorship terms are realistic before you commit time or money.</li>
    <li><strong>Avoid red flags:</strong> legitimate employers do not ask candidates to pay large upfront "processing fees" for sponsorship, and they won't guarantee a visa outcome &mdash; approval always rests with USCIS or the Department of State, not the employer.</li>
</ul>

<h2>Applying from Pakistan or Other Countries</h2>

<p>For <strong>truck driver jobs in USA with visa sponsorship for Pakistani</strong> applicants and other foreign nationals, the process is the same &mdash; EB-3 or H-2B &mdash; but two practical factors matter more:</p>

<ol>
    <li><strong>Per-country visa limits</strong> can mean longer EB-3 waits for applicants from countries with high demand, so ask a prospective employer or attorney about current backlogs for your country before assuming a fast timeline.</li>
    <li><strong>Visa interview and documentation:</strong> you'll go through a U.S. embassy/consulate interview, and having your driving experience, licenses, and employer's labor certification well-documented in advance speeds this up considerably.</li>
</ol>

<h2>A Realistic 2026 Outlook</h2>

<p>Truck driver jobs in USA with visa sponsorship for 2026 remain available, but the H-2B route is more competitive than in past years because supplemental allocations have been filling faster, and EB-3 backlogs continue for several countries. That doesn't rule out sponsorship &mdash; it just means building a strong application (verified experience, clean record, a CDL or a credible training plan) matters more than searching for a shortcut.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is there a dedicated truck driver visa?</h3>
<p>No. Sponsorship happens through the general EB-3 (permanent) or H-2B (temporary) employment visa categories &mdash; there's no trucking-specific visa.</p>

<h3>Do I need a CDL before applying?</h3>
<p>Not always. Some employers sponsor CDL training after you arrive, but most still prefer candidates with prior commercial driving experience, even if it was earned abroad.</p>

<h3>How long does EB-3 sponsorship take?</h3>
<p>Often 1&ndash;3+ years once you include PERM labor certification and visa backlog processing, and it can run longer depending on your country of origin.</p>

<h3>Can I bring my family?</h3>
<p>EB-3 green card holders can generally include a spouse and unmarried children under 21 in the application. H-2B is more limited and tied to the temporary nature of the visa, so check current rules with an immigration professional before assuming dependents are covered.</p>

<h3>Where can I start searching for real openings?</h3>
<p>Job aggregators are a practical starting point &mdash; you can browse current listings tagged with visa sponsorship below.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-truck-driver-america-with-visa-sponsorship-jobs.html?vjk=a80f6fa975d8fbce" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Truck Driver Jobs in USA with Visa Sponsorship →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and isn't legal or immigration advice. Visa eligibility, timelines, and requirements change &mdash; confirm current rules with USCIS or a licensed immigration attorney before making decisions.</p>
HTML;
    }
}
