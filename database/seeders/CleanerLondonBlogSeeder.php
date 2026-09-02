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
 * "Cleaner Jobs in London (No Experience Needed)" — cleaning sits below the
 * Skilled Worker visa threshold, so the matching listing is written for people
 * who already hold the right to work rather than advertising sponsorship.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CleanerLondonBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-cleaning-l-london-jobs.html?vjk=145bc3777d84d4f3';

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
        $title = 'Cleaner Jobs in London (No Experience Needed)';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Office, hotel, school and domestic cleaning roles across London that need no prior experience — shift patterns, real 2026 pay rates, the honest answer on visa sponsorship, and how to start this week.',
                'content' => $content,
                'featured_image' => 'blogs/cleaner-jobs-in-london.jpg',
                'tags' => 'cleaner jobs london, cleaning jobs no experience, part time cleaner jobs, office cleaner jobs, domestic cleaner jobs, london living wage, immediate start jobs',
                'meta_title' => 'Cleaner Jobs in London, No Experience Needed (2026 Pay Guide)',
                'meta_description' => 'Office, hotel, school and domestic cleaning jobs in London with no experience needed. Shift patterns, 2026 pay rates and the truth about visa sponsorship.',
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
            ['name' => 'London Cleaning Contractors (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'london-cleaning-contractors']
        );

        $location = Location::firstOrCreate(
            ['name' => 'London'],
            ['area' => 'Greater London', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'cleaning-facilities'],
            ['name' => 'Cleaning & Facilities']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cleaner — London (No Experience Needed)',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Flexible shifts — early morning, evening, night and weekend slots',
                'language' => 'English',
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 11,
                'salary_maximum' => 16,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Cleaner jobs across London with no experience needed — office, hotel, school and domestic roles, GBP 11-16 per hour, immediate starts available.',
                'seo_keywords' => 'cleaner jobs london, cleaning jobs no experience, part time cleaner jobs london, office cleaner jobs, immediate start cleaning jobs, domestic cleaner london',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Cleaning contractors and agencies across London hire year-round for office, hotel, retail, school, hospital and domestic cleaning. Most entry-level roles need no prior experience &mdash; training is given on the job &mdash; and many agencies can place you within the same week.</p>

<h3>Roles typically covered</h3>
<ul>
    <li>Office and commercial cleaning &mdash; early morning or evening shifts</li>
    <li>Hotel housekeeping &mdash; room turnover, laundry, public areas</li>
    <li>School and hospital cleaning &mdash; enhanced DBS check required</li>
    <li>Domestic and end-of-tenancy cleaning through an agency</li>
    <li>Airbnb and short-let turnovers, paid per clean</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>Right to work in the UK &mdash; cleaning roles sit below the Skilled Worker visa skill threshold and cannot be sponsored</li>
    <li>No formal qualifications or prior cleaning experience needed for most entry-level roles</li>
    <li>Reliability, punctuality and attention to detail</li>
    <li>A reference &mdash; a previous employer, landlord, or anyone who can vouch for you</li>
    <li>Enhanced DBS check for school and hospital contracts; a basic check for most other roles</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Roughly &pound;11&ndash;&pound;16 an hour, at or above the 2026 London Living Wage of &pound;13.85</li>
    <li>&pound;19,000&ndash;&pound;26,000 a year for full-time salaried positions</li>
    <li>Flexible shift patterns &mdash; part-time 2&ndash;4 hour slots, nights, or weekend-only work</li>
    <li>On-the-job training and induction with the larger facilities contractors</li>
</ul>

<p><strong>Note:</strong> pay, hours and contracts are set by the individual employer or agency &mdash; not by JobGader. Standard cleaning roles are not eligible for UK visa sponsorship, so be cautious of anyone claiming otherwise, and never pay a fee for a job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Cleaning is one of the few sectors in London where you can genuinely walk into paid, full-time work with zero prior experience, flexible hours, and a same-week start. From offices and hotels to schools, hospitals, and private homes, demand for reliable cleaners across the city is constant &mdash; which is exactly why <strong>cleaner jobs in London</strong> consistently rank among the easiest entry points into paid work, whether you're just starting out, between jobs, or looking for flexible part-time hours around other commitments.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-cleaning-l-london-jobs.html?vjk=145bc3777d84d4f3" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🧹 Browse Cleaner Jobs in London →
    </a>
</div>

<h2>Cleaning Jobs in London No Experience Required</h2>

<p>Most entry-level cleaning roles genuinely don't require prior experience &mdash; employers typically care more about reliability, attention to detail, and passing a basic background/reference check than a CV full of cleaning history. Common no-experience-required roles include:</p>

<ul>
    <li>Office and commercial cleaning</li>
    <li>Hotel housekeeping</li>
    <li>Retail and school cleaning</li>
    <li>General domestic cleaning through an agency</li>
</ul>

<p>Training is usually provided on the job, especially for larger cleaning contractors and facilities companies that run their own induction process.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cleaner-jobs-in-london-office.jpg"
         alt="Cleaner mopping a London office floor with Big Ben in the background — cleaner jobs in London, no experience needed"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Types of Cleaner Jobs in London</h2>

<h3>Part-Time and Full-Time Cleaner Jobs</h3>
<p>Cleaning is one of the most flexible sectors for choosing your hours. Part-time roles are common for early morning or evening shift patterns (often 2&ndash;4 hours a day), while full-time roles &mdash; typically 35&ndash;40 hours a week &mdash; are widely available with facilities management companies, hotels, and hospital contractors.</p>

<h3>Domestic and House Cleaner Jobs</h3>
<p>Domestic cleaning covers private homes, either through an agency (which handles client matching, insurance, and payment) or self-employed/freelance work you book directly with clients. Agency work tends to offer more consistent hours and less admin; going freelance offers higher hourly rates but means managing your own bookings, insurance, and tax.</p>

<h3>Office Cleaner Jobs</h3>
<p>Usually early morning (before staff arrive) or evening (after close) shifts in commercial buildings. These are some of the highest-volume postings in London because every office building needs a cleaning contract.</p>

<h3>Hotel Cleaner Jobs</h3>
<p>Hotel housekeeping involves room turnover, laundry, and public area cleaning. London's hotel sector hires constantly given tourist volume and staff turnover, and many properties offer shift flexibility across morning, evening, and weekend slots.</p>

<h3>School and Hospital Cleaner Jobs</h3>
<p>Both sectors typically require an enhanced DBS (background) check given the vulnerable populations involved, but they offer some of the more stable, term-time or contracted hours in the sector. Hospital cleaning in particular often falls under NHS facilities contracts or specialist healthcare cleaning contractors, with additional training on infection control standards.</p>

<h3>Evening, Morning, Night, and Weekend Cleaner Jobs</h3>
<p>Cleaning is unusual in that shift timing is often the main selling point of a posting rather than the job itself. Morning and evening slots dominate office and retail cleaning; night shifts are common in hospitals, transport hubs, and 24-hour facilities; weekend-only roles are popular with people balancing another job or studies during the week.</p>

<h3>Self-Employed and Freelance Cleaner Jobs</h3>
<p>Going self-employed means registering with HMRC, handling your own tax and National Insurance, and typically earning more per hour than agency-employed roles &mdash; domestic clients in London commonly pay agencies &pound;22&ndash;&pound;26 an hour for a booked session, though a meaningful share of that goes to the platform or agency rather than directly to you if you're not fully independent.</p>

<h3>Airbnb Cleaner Jobs</h3>
<p>A fast-growing niche tied to short-let turnover &mdash; quick, thorough cleans between guest stays, often on tight same-day turnaround windows. These are typically freelance or agency-based and pay per clean rather than per hour, so earnings depend heavily on how many properties you can turn around in a day.</p>

<h3>End of Tenancy and Deep Cleaning Jobs</h3>
<p>More intensive, one-off jobs (oven, carpet, and full-property deep cleans) that typically pay a higher rate than routine maintenance cleaning because of the extra time and physical effort involved. Agencies specializing in end-of-tenancy cleaning are a common entry point if you want higher-paying one-off work over standard recurring contracts.</p>

<h3>Window Cleaner Jobs</h3>
<p>A more physical, often outdoor role, ranging from residential window rounds to high-rise commercial window cleaning (the latter requiring specific safety training and certification for working at height).</p>

<h2>How Much Do Cleaners Earn in London?</h2>

<p>Pay varies significantly by employment type, shift, and whether you're agency-employed, salaried, or self-employed:</p>

<ul>
    <li><strong>Typical hourly pay for employed cleaners:</strong> roughly &pound;11&ndash;&pound;16 an hour, broadly in line with or just above the London Living Wage, which stands at <strong>&pound;13.85 an hour</strong> in 2026.</li>
    <li><strong>Salaried cleaning roles:</strong> commonly &pound;19,000&ndash;&pound;26,000 a year for full-time positions, with supervisory or specialist roles (hospital, deep cleaning) at the higher end.</li>
    <li><strong>Self-employed/domestic cleaning:</strong> clients in London typically pay &pound;20&ndash;&pound;30 an hour for booked agency cleans, though your actual take-home depends on the agency's cut or, if fully independent, your own client base and expenses.</li>
    <li><strong>Entry-level vs. experienced:</strong> early-career cleaners tend to earn closer to &pound;9&ndash;&pound;11 an hour, rising toward &pound;14&ndash;&pound;16+ with experience, specialization (hospital, deep cleaning), or supervisory responsibility.</li>
</ul>

<h2>Cleaner Jobs London Visa Sponsorship: The Honest Answer</h2>

<p>If you're outside the UK and searching for <strong>cleaner jobs London visa sponsorship</strong>, it's important to know upfront that standard cleaning roles generally do not qualify for the UK's main sponsorship route, the Skilled Worker visa. Here's why:</p>

<ul>
    <li>The Skilled Worker visa requires a role to meet a minimum skill level, and as of July 2025 that threshold sits at RQF Level 6 (broadly degree-level) for the general route.</li>
    <li>Cleaning roles are classified well below this &mdash; typically RQF Level 1 &mdash; so "cleaner" isn't on the list of eligible occupations regardless of salary offered.</li>
    <li>Some higher-skilled, higher-paid supervisory or facilities management roles within the cleaning industry might meet a different occupation code and salary threshold, but a standard cleaner position won't.</li>
</ul>

<p><strong>What can still work:</strong> if you're eligible for the UK's <a href="https://www.gov.uk/youth-mobility" target="_blank" rel="noopener">Youth Mobility Scheme</a> (available to young people from a specific list of partner countries), that visa lets you work in any job, including cleaning, without needing employer sponsorship at all &mdash; worth checking if you meet the age and nationality criteria. Otherwise, if you already hold the right to work in the UK through another route (settled status, dependant visa, student visa work rights, etc.), you can apply for cleaning jobs like any other candidate &mdash; no sponsorship needed in that case either.</p>

<p>Be cautious of any site or recruiter claiming to offer direct visa sponsorship specifically for a cleaning role &mdash; given the eligibility rules above, that claim doesn't hold up under the current system.</p>

<h2>A Note on "Cash in Hand" Cleaner Jobs</h2>

<p><strong>Cleaner jobs cash in hand</strong> is a common search, and it's worth being clear-eyed about what this actually means. Being paid in cash isn't automatically illegal &mdash; plenty of legitimate domestic cleaning work is paid this way &mdash; but the income still legally has to be declared for tax and National Insurance purposes if you're self-employed or working casually. Genuinely undeclared "cash in hand" arrangements carry real risks: no paper trail if a client disputes pay or hours, no employment protections, and potential legal and tax consequences down the line. If you don't currently have the right to work in the UK, cash-in-hand cleaning work is also a common route used to exploit workers with no legal recourse &mdash; it's worth being cautious of any arrangement that specifically avoids paperwork as a selling point.</p>

<h2>How to Get a Cleaner Job in London</h2>

<ul>
    <li><strong>Apply directly through job boards</strong> like Indeed, filtering by shift pattern (morning/evening/weekend) and role type (office, hotel, domestic).</li>
    <li><strong>Register with a cleaning agency</strong> &mdash; agencies handle client matching and often offer flexible or immediate-start shifts, which is useful if you're searching for <strong>cleaner jobs London immediate start</strong>.</li>
    <li><strong>Check facilities management companies directly</strong> (ISS, Mitie, Servest, Sodexo, and similar) since they hold large office, school, and hospital cleaning contracts across London and hire in volume.</li>
    <li><strong>Get a basic DBS check sorted early</strong> if you're targeting school or hospital roles, since this is usually a hard requirement before you can start.</li>
    <li><strong>Be ready with references</strong>, even informal ones (a previous employer, landlord, or someone who can vouch for reliability), since formal cleaning experience often isn't required but some form of character reference usually helps.</li>
</ul>

<h2>Best Cleaning Companies Hiring in London</h2>

<p>Large facilities and cleaning contractors are consistently among the biggest volume hirers in the sector, including national contract cleaning firms (ISS Facility Services, Mitie, Servest, Sodexo) and domestic cleaning agencies operating across London boroughs. Hotel groups and NHS trust facilities contractors are also worth checking directly, since housekeeping and hospital cleaning roles are frequently advertised on their own careers pages as well as on general job boards.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need experience to get a cleaning job in London?</h3>
<p>No &mdash; most entry-level roles (office, hotel, retail, school cleaning) don't require prior experience and provide on-the-job training.</p>

<h3>How much do cleaners earn in London?</h3>
<p>Roughly &pound;11&ndash;&pound;16 an hour for employed roles, &pound;19,000&ndash;&pound;26,000 a year for salaried positions, and &pound;20&ndash;&pound;30 an hour for booked domestic/agency cleans, though your actual take-home varies by agency cut or self-employment costs.</p>

<h3>Can I get a cleaner job in London with visa sponsorship?</h3>
<p>Standard cleaning roles don't meet the skill threshold for the UK Skilled Worker visa. The Youth Mobility Scheme (for eligible nationalities and ages) is the main route that allows working in any job, including cleaning, without employer sponsorship.</p>

<h3>Is cash-in-hand cleaning work legal?</h3>
<p>Being paid cash isn't automatically illegal, but the income must still be declared for tax purposes. Fully undeclared work carries tax risk and offers no employment protections if something goes wrong.</p>

<h3>Where's the fastest way to find an immediate-start cleaning job?</h3>
<p>Job boards filtered by "immediate start," and registering directly with a cleaning agency, are typically the two fastest routes since agencies often have same-week placements available.</p>

<h2>People Also Search For</h2>

<h3>Cleaning jobs in London no experience</h3>
<p>Office, hotel, retail and school cleaning roles rarely require prior experience. Employers look for reliability, attention to detail and a reference; training is given on the job.</p>

<h3>Cleaner jobs London part time</h3>
<p>Early morning and evening shifts of 2&ndash;4 hours a day are the most common part-time patterns, mainly in office and retail cleaning contracts.</p>

<h3>Cleaner jobs London immediate start</h3>
<p>Registering with a cleaning agency is usually the fastest route &mdash; many hold same-week placements. Filtering job boards by "immediate start" is the other quick option.</p>

<h3>Cleaner jobs London cash in hand</h3>
<p>Cash payment is not automatically illegal, but the income must still be declared for tax and National Insurance. Fully undeclared work leaves you with no employment protections and real tax risk.</p>

<h3>Cleaner jobs London visa sponsorship</h3>
<p>Standard cleaning roles sit below the Skilled Worker visa's RQF Level 6 threshold, so they cannot be sponsored. The Youth Mobility Scheme, or any existing right to work, is the realistic route.</p>

<h3>How much do cleaners get paid in London</h3>
<p>Around &pound;11&ndash;&pound;16 an hour employed, &pound;19,000&ndash;&pound;26,000 a year salaried, and &pound;20&ndash;&pound;30 an hour for booked domestic cleans before the agency's cut.</p>

<h3>Self employed cleaner jobs London</h3>
<p>Higher hourly rates but you register with HMRC and handle your own tax, National Insurance, insurance and bookings. Domestic sessions in London commonly bill at &pound;22&ndash;&pound;26 an hour.</p>

<h3>Airbnb and end of tenancy cleaner jobs London</h3>
<p>Short-let turnovers pay per clean rather than per hour, so earnings depend on how many properties you cover in a day. End-of-tenancy and deep cleans pay above routine maintenance work.</p>

<h3>Office cleaner jobs London evening</h3>
<p>The highest-volume postings in the city &mdash; commercial buildings need cleaning before staff arrive or after close, so early morning and evening slots dominate.</p>

<h2>More Job Guides</h2>

<p>Looking at other sectors or countries? These guides cover the routes we track:</p>

<ul>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; what the July 2025 care worker route closure means for applicants.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; H-2B and J-1 hospitality sponsorship and which roles get hired.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; H-2B, EB-3 and H-1B routes plus 2026 pay benchmarks.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; EB-3 and H-2B routes and CDL requirements.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-cleaning-l-london-jobs.html?vjk=145bc3777d84d4f3" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Cleaner Jobs in London →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and isn't legal, tax, or immigration advice. Visa rules and salary thresholds change &mdash; confirm current requirements directly on gov.uk or with a licensed immigration adviser before making decisions.</p>
HTML;
    }
}
