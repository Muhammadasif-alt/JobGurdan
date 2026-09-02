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
 * "Hotel Jobs in USA for Foreigners" — the hospitality visa-sponsorship guide,
 * plus the matching job listing the article links out to.
 *
 * Both use updateOrCreate, so re-running is safe; note that it will overwrite
 * edits made in the admin panel for these two records.
 */
class HotelJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-usa-hotel-jobs-with-visa-sponsorship-jobs.html?vjk=211f8bcb51a8380f';

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
        $title = 'Hotel Jobs in USA for Foreigners';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'H-2B, J-1, H-1B and EB-3 explained for hospitality work — which roles U.S. hotels actually sponsor, realistic hourly pay by position, and how to spot a recruitment scam.',
                'content' => $content,
                'featured_image' => 'blogs/hotel-jobs-in-usa-for-foreigners.jpg',
                'tags' => 'hotel jobs usa, visa sponsorship, H-2B visa, J-1 visa, hospitality jobs, housekeeping jobs, front desk jobs, jobs for foreigners',
                'meta_title' => 'Hotel Jobs in USA for Foreigners with Visa Sponsorship (2026)',
                'meta_description' => 'H-2B, J-1 and EB-3 routes into U.S. hotel work, which roles get sponsored, hourly pay by position, and how to avoid recruitment scams.',
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
            ['name' => 'US Hotels & Resorts (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'us-hotels-resorts']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Hotel Staff (Housekeeping, Front Desk & Kitchen) — Visa Sponsorship Available',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work, including weekends and holidays',
                'language' => 'English',
                'salary_currency' => 'USD',
                'salary_period' => 'Hourly',
                'salary_minimum' => 14,
                'salary_maximum' => 21,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'U.S. hotel and resort openings with visa sponsorship — housekeeping, front desk and kitchen roles on H-2B and J-1 routes, $14-$21/hour.',
                'seo_keywords' => 'hotel jobs usa, visa sponsorship, H-2B visa, J-1 visa, housekeeping jobs, front desk jobs, hospitality jobs for foreigners',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>U.S. hotels and resorts hire seasonally and year-round for line-level hospitality roles, and many sponsor foreign workers through the H-2B and J-1 visa programmes. This listing points to current openings tagged with visa sponsorship.</p>

<h3>Roles typically covered</h3>
<ul>
    <li>Room attendant / housekeeper</li>
    <li>Front desk &amp; guest services representative</li>
    <li>Breakfast attendant and banquet staff</li>
    <li>Kitchen back-of-house &mdash; dishwasher, prep cook</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>No formal qualification needed for most entry-level roles; prior hospitality experience is an advantage</li>
    <li>English sufficient to serve guests and follow shift instructions</li>
    <li>Availability for shift work, including weekends and holidays during peak season</li>
    <li>Ability to pass a standard background check</li>
    <li>Physical stamina &mdash; most roles involve standing, lifting and moving throughout the shift</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Visa sponsorship through H-2B (seasonal) or J-1 (exchange/training) routes, depending on the property</li>
    <li>Roughly $14&ndash;$16 an hour for housekeeping, $16&ndash;$20 for front desk, and $18&ndash;$21 for kitchen roles in resort markets</li>
    <li>Common extras: employee meals, resort discounts, and in some seasonal contracts, staff housing</li>
    <li>Management and specialised roles (H-1B / EB-3 track) run well above these ranges</li>
</ul>

<p><strong>Note:</strong> sponsorship terms, timelines and eligibility are set by the individual hotel or staffing agency and by USCIS &mdash; not by JobGader. Read each posting in full before applying, and never pay an upfront "guaranteed job" fee for sponsorship.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>America's hospitality industry runs on seasonal peaks &mdash; ski resorts in winter, beach towns in summer, convention hotels year-round &mdash; and staffing those peaks with domestic workers alone has never been enough. That gap is what makes <strong>hotel jobs in USA</strong> one of the more accessible visa-sponsored entry points into the American job market for foreign workers, from housekeeping and front desk roles to culinary and management positions. Here's how the process actually works, what it pays, and how to avoid the scams that circle this space.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-usa-hotel-jobs-with-visa-sponsorship-jobs.html?vjk=211f8bcb51a8380f" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🏨 Browse Hotel Jobs in USA with Visa Sponsorship →
    </a>
</div>

<h2>Why Hotels Sponsor Foreign Workers</h2>

<p>Resort towns and seasonal destinations regularly can't fill entry-level roles &mdash; housekeeping, breakfast service, dishwashing, guest services &mdash; fast enough with local labor, especially during peak season. Large chains and independent resorts alike turn to visa programs to bridge that gap, which is exactly why postings for <strong>usa hotel jobs salary</strong> and sponsorship show up year-round on major job boards, not just around holidays.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hotel-jobs-in-usa-front-desk.jpg"
         alt="Hotel front desk agent greeting a guest — hotel jobs in USA for foreigners with visa sponsorship"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Visa Pathways for Hotel Work</h2>

<p>There's no single "hotel visa." Sponsorship runs through a handful of existing employment categories, each suited to a different type of role:</p>

<h3>H-2B Visa (Seasonal / Temporary)</h3>

<p>The most common route for line-level hotel roles &mdash; housekeeping, front desk, breakfast attendants, dishwashers, banquet staff. It's tied to a specific employer and a defined season of need, and it does not lead to permanent residency on its own. The annual statutory cap sits at 66,000 visas split across two half-year allocations, and demand from resort and hospitality employers regularly pushes that cap to its limit &mdash; in fiscal year 2026, the government released a temporary allocation of over 64,000 additional visas across sectors that included hospitality, and even that expanded pool filled quickly. Employers post current temporary hospitality roles on the <a href="https://seasonaljobs.dol.gov/jobs" target="_blank" rel="noopener">U.S. Department of Labor's Seasonal Jobs portal</a>, which is a genuinely useful place to see real H-2B postings rather than recycled aggregator listings.</p>

<h3>J-1 Visa (Exchange Visitor / Internship-Training)</h3>

<p>Common for hospitality management trainees, culinary interns, and students on cultural exchange programs. It's time-limited and administered through designated sponsor organizations rather than direct employer petitions, so most J-1 hotel placements go through an approved exchange program rather than a standard job application.</p>

<h3>H-1B Visa (Specialty Occupation)</h3>

<p>Reserved for higher-skill, degree-requiring roles &mdash; think revenue management, hotel operations management, or corporate hospitality positions &mdash; not entry-level service jobs. It's also subject to an annual lottery, which makes it far more competitive and less predictable than H-2B for hospitality staffing.</p>

<h3>EB-3 Visa (Permanent / Green Card)</h3>

<p>Used less often for hotel roles than H-2B, but it exists for employers willing to sponsor permanent, year-round positions and complete PERM labor certification. It's the slower, more committed route &mdash; better suited to someone aiming for long-term settlement than a seasonal contract.</p>

<h2>What Roles Are Typically Sponsored</h2>

<p>Real postings from hotel groups (High Hotels, Marriott-branded properties, and independent resorts) show the pattern clearly: seasonal and year-round openings for room attendants/housekeepers, breakfast attendants, guest services representatives, and back-of-house kitchen roles like dishwashers are the volume hires &mdash; and dishwasher and housekeeping roles are among the most common H-2B-eligible positions at resort hotels. For example, a mountain-resort hotel in Colorado recently advertised an H-2B dishwasher role at <strong>$20.53 an hour</strong> plus resort discounts and employee meals &mdash; a useful real-world reference point, though pay like this varies heavily by location and cost of living.</p>

<h2>Hotel Jobs in USA for Foreigners: Salary Expectations</h2>

<p>Pay depends heavily on role, location, and whether you're in a high-cost resort market or a smaller town. Rough current benchmarks:</p>

<ul>
    <li><strong>Housekeeping/room attendant:</strong> roughly $14&ndash;$16 an hour nationally (about $30,000&ndash;$38,000/year), though high-cost resort markets and unionized properties often pay noticeably more.</li>
    <li><strong>Front desk/guest services:</strong> roughly $16&ndash;$20 an hour (about $34,000&ndash;$42,000/year), with top-earning markets and full-service hotels at the higher end.</li>
    <li><strong>Kitchen/back-of-house (dishwasher, prep):</strong> often $18&ndash;$21 an hour in resort markets, sometimes higher where the cost of living or seasonal demand pushes wages up.</li>
    <li><strong>Management and specialized roles (H-1B/EB-3 track):</strong> salaries climb well above $50,000&ndash;$70,000+, since these typically require a degree or several years of supervisory experience.</li>
</ul>

<p>Room and board, employee meals, and resort discounts are common non-cash benefits in seasonal hospitality jobs and are worth factoring into any offer.</p>

<h2>Applying as an Indian, Pakistani, or Other Foreign National</h2>

<p>For <strong>motel jobs in USA for Indian</strong> applicants and other foreign nationals more broadly, the process doesn't differ by nationality &mdash; it comes down to which visa category the employer is set up to sponsor. Two practical things matter more than where you're from:</p>

<ol>
    <li><strong>Which visa the employer already uses.</strong> Large hotel chains and staffing agencies that have sponsored H-2B or J-1 workers before tend to have a repeatable, faster process than a small independent motel doing it for the first time.</li>
    <li><strong>Documentation and interview readiness.</strong> Having your work history, references, and (for J-1) program enrollment paperwork organized ahead of your visa interview meaningfully speeds up processing.</li>
</ol>

<h2>Using a Hotel Jobs Abroad Recruitment Agency</h2>

<p>A legitimate <strong>hotel jobs abroad recruitment agency</strong> can be a genuine shortcut &mdash; connecting you with vetted H-2B or J-1 program employers &mdash; but this space also attracts scams. Before paying any agency:</p>

<ul>
    <li>Confirm they're a registered, licensed H-2B recruiter or a designated J-1 sponsor organization (J-1 sponsors are listed on the U.S. State Department's exchange visitor program directory).</li>
    <li>Never pay large upfront "guaranteed job" fees &mdash; legitimate H-2B recruitment fees are regulated, and reputable agencies are transparent about them in writing.</li>
    <li>Cross-check any hotel or resort name they mention directly with the hotel's own careers page.</li>
</ul>

<h2>A Note on "Free Visa" Hotel Jobs in Europe</h2>

<p>Search interest around <strong>free visa hotel job in Europe</strong> and <strong>hotel jobs in Europe for foreigners</strong> often overlaps with USA hotel job searches, since candidates are usually comparing destinations rather than committed to one country. A few honest points if you're weighing both:</p>

<ul>
    <li>No visa is truly "free" &mdash; there are always some processing costs, even when the employer covers sponsorship fees, and "free visa" is a phrase heavily used in recruitment scams to create urgency.</li>
    <li>European hospitality visas (seasonal work permits, EU Blue Card for higher-skill roles) work similarly to the U.S. system: an employer or program sponsors you, and you still need to meet language, documentation, and sometimes bilateral-agreement requirements specific to the country.</li>
    <li>If comparing offers, evaluate net pay after cost of living, contract length, and whether the visa can be renewed or leads to longer-term status &mdash; not just the sponsorship claim itself.</li>
</ul>

<h2>5-Star Hotel Jobs in America</h2>

<p>Luxury and 5-star properties (Four Seasons, Ritz-Carlton, major resort brands) do sponsor foreign talent, particularly for specialized culinary, spa, and management roles, but competition is steeper than for standard H-2B service roles. Checking a brand's own careers portal directly &mdash; for example <a href="https://careers.fourseasons.com/us/en/search-results" target="_blank" rel="noopener">Four Seasons Careers</a> &mdash; is usually more productive than searching <strong>5 star hotel job vacancy in America</strong> on generic aggregators, since luxury brands often route international hiring through their own corporate recruitment teams rather than mass job boards.</p>

<h2>Frequently Asked Questions</h2>

<h3>What's the fastest visa route into a U.S. hotel job?</h3>
<p>H-2B is generally the fastest to start working under, since it's built for seasonal hospitality demand &mdash; but it's temporary and tied to one employer, unlike EB-3's permanent (but much slower) path.</p>

<h3>Do I need hospitality experience to qualify?</h3>
<p>Not always for entry-level roles like housekeeping or dishwashing, but prior experience makes you more competitive, especially with employers who receive many applications per opening.</p>

<h3>Can H-2B lead to a green card later?</h3>
<p>Not directly &mdash; H-2B is a temporary, non-immigrant visa. Some workers later pursue EB-3 sponsorship with the same or a different employer, but that's a separate application process.</p>

<h3>Are unpaid or "training only" hotel placements legitimate?</h3>
<p>Be cautious. Legitimate J-1 training programs are structured, paid, and run through State Department-designated sponsors &mdash; a placement asking you to pay heavily upfront with no clear sponsor organization is a red flag.</p>

<h3>Where can I find real, current openings?</h3>
<p>Start with the platforms below, and check individual hotel brand career pages directly for higher-end roles.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-usa-hotel-jobs-with-visa-sponsorship-jobs.html?vjk=211f8bcb51a8380f" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Hotel Jobs in USA with Visa Sponsorship →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and isn't legal or immigration advice. Visa eligibility, caps, and requirements change &mdash; confirm current rules with USCIS, the U.S. Department of State, or a licensed immigration attorney before making decisions.</p>
HTML;
    }
}
