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

/**
 * "Hotel and Restaurant Jobs in the UAE and Italy" — blog-289.
 *
 * The draft was rewritten. What it carried could not be published as sent:
 *
 * 1. Every "Apply Now" button went to an Indeed search or company page, one
 *    of them a Hapimag salaries page. The site sends readers to employer or
 *    government pages instead; the job listings carry the official links.
 *
 * 2. "Joint Circular No. 7185" and "Decreto Flussi 2026-2028 page" on esteri.it.
 *    The esteri.it address returns 404 and the circular number could not be
 *    confirmed, so the guide cites the joint circular of 1 October 2026 only.
 *
 * 3. "Good Italian is a core requirement" stated as a visa rule, and Hapimag
 *    roles in named Tuscan and Abruzzo resorts. Neither could be confirmed, so
 *    the guide says employers expect Italian and names no resort.
 *
 * Confirmed from official sources: the 2027 decree allows 89,000 seasonal
 * entries in agriculture and tourism, seasonal tourism applications open on
 * 9 February 2027 through the Interior Ministry ALI portal, and pre-filling
 * runs from 23 October to 7 December 2026.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HotelRestaurantJobsUaeItalyBlogSeeder extends Seeder
{
    public const SLUG = 'hotel-and-restaurant-jobs-in-the-uae-and-italy';

    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

    private const ITALY_APPLY_URL = 'https://www.cliclavoro.gov.it/';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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
        $title = 'Hotel and Restaurant Jobs in the UAE and Italy';

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'In the UAE a hotel or restaurant employer hires you and arranges your work permit. In Italy the employer must request a nulla osta for you under the Decreto Flussi quota, and seasonal tourism applications open on 9 February 2027.',
                'content' => $content,
                'featured_image' => 'blogs/hotel-restaurant-jobs-uae-italy.jpg',
                'tags' => 'hotel jobs dubai, restaurant jobs uae, housekeeping jobs abroad, waiter jobs italy, cameriere jobs italy, decreto flussi 2027, nulla osta seasonal tourism, hospitality jobs for foreigners',
                'meta_title' => 'Hotel and Restaurant Jobs in the UAE and Italy',
                'meta_description' => 'How to get hotel, housekeeping and waiter jobs in Dubai and Italy: employer routes, the Decreto Flussi nulla osta and how to spot fake offers.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        $listings = [
            [
                'advertiser' => ['UAE Hotel and Restaurant Employers (Aggregated)', 'uae-hotel-restaurant-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Hotel and Restaurant Staff — UAE Hotels and Restaurants',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Shift-based, including evenings, weekends and holidays',
                'language' => 'English',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Room attendant, front office and waiter roles with UAE hotels and restaurants. The employer arranges the work permit and may not charge you recruitment costs.',
                'keywords' => 'hotel jobs dubai, housekeeping jobs uae, waiter jobs dubai, restaurant jobs uae, hospitality jobs uae',
            ],
            [
                'advertiser' => ['Italian Hotel and Restaurant Employers (Aggregated)', 'italy-hotel-restaurant-aggregated'],
                'location' => ['Italy', 'Italy'],
                'position' => 'Seasonal Waiter and Kitchen Staff — Italian Hotels and Restaurants (Employer Must File Nulla Osta)',
                'apply' => self::ITALY_APPLY_URL,
                'hours' => 'Seasonal, including evenings and weekends',
                'language' => 'Italian',
                'description' => $this->italyJobDescription(),
                'meta' => 'Seasonal waiter and kitchen roles with Italian hotels and restaurants. A non-EU worker needs the employer to request a nulla osta under the Decreto Flussi quota.',
                'keywords' => 'waiter jobs italy, cameriere jobs, seasonal hotel jobs italy, decreto flussi tourism, nulla osta seasonal work',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['hours'],
                    'language' => $listing['language'],
                    // Pay depends on the employer, the contract and the season;
                    // this site does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hotels and restaurants across the UAE recruit room attendants, front office staff, waiters and kitchen staff from overseas, usually on employer-sponsored work permits.</p>

<h3>Requirements</h3>
<ul>
    <li>A MOHRE work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>A written offer or contract before you travel, from an employer you can find on its own official careers page</li>
    <li>Experience is often preferred, but housekeeping and food service are common entry roles</li>
</ul>

<p><strong>Who pays:</strong> under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge the worker recruitment and employment costs, directly or indirectly.</p>

<p><strong>Note:</strong> work permit and wage rules are set by MOHRE, not by JobGader. Confirm them before accepting an offer.</p>
JOBHTML;
    }

    private function italyJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hotels, resorts and restaurants in Italy hire waiters (camerieri) and kitchen staff for the tourist season, including in lake, coastal and mountain areas.</p>

<h3>Requirements</h3>
<ul>
    <li>For a non-EU worker, an Italian employer must request a nulla osta (work authorisation) naming the worker, within the Decreto Flussi seasonal tourism quota</li>
    <li>A written job offer before you apply for the entry visa</li>
    <li>Employers expect you to communicate with guests and staff, so basic restaurant Italian helps</li>
</ul>

<p><strong>Note:</strong> quotas, dates and procedures are set by the Italian Interior, Labour and Foreign Affairs ministries, not by JobGader. Check the official decree and circular before relying on a date.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Hotel and restaurant work is one of the easiest ways into a new country, because the jobs start at entry level and the demand never stops. The route differs sharply, though. In the UAE the employer hires you and arranges your work permit. In Italy a non-EU worker can only come through a quota, and the employer has to apply first. This guide sets out both, and how to avoid the fake offers that target hospitality seekers.</p>

<h2>The Two Routes at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Who starts the process</th>
            <th style="padding:10px;text-align:left;">What you need first</th>
            <th style="padding:10px;text-align:left;">Cost to you</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UAE</strong></td><td style="padding:10px;">The employer, with a MOHRE work permit</td><td style="padding:10px;">A written offer or contract</td><td style="padding:10px;">None; recruitment costs fall on the employer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Italy</strong></td><td style="padding:10px;">The employer, requesting a nulla osta under the quota</td><td style="padding:10px;">A written job offer from an Italian employer</td><td style="padding:10px;">Check the official decree; never pay anyone for a place in the quota</td></tr>
    </tbody>
</table>
</div>

<h2>How Do I Find Hotel Jobs in Dubai?</h2>

<p>Start with the hotel group's own careers site, because that is where genuine vacancies are advertised. Jumeirah, for example, lists roles across its city hotels, its Dubai head office and its leisure destinations on its official careers page, and Hilton, Marriott and Accor run their own careers sites for their Gulf hotels. Search with the exact job title an employer would use, such as "Room Attendant", "Front Office Agent" or "Waiter".</p>

<p>Whichever site you use, the work permit is arranged by the employer through MOHRE, and you cannot work on a visit or tourist visa. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hotel-restaurant-jobs-uae-italy-dining.jpg"
         alt="A smiling waitress in a black waistcoat and bow tie carrying a plate on a tray beside a chef plating a dish in an open kitchen, with the Dubai skyline and an Italian waterfront behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Are There Housekeeping Jobs Abroad Without Experience?</h2>

<p>Housekeeping, room attendant and food service roles are the usual starting points in hospitality, and hotels hire for them in large numbers. Many adverts still ask for some experience, so describe any hospitality, cleaning or catering work you have done, even informally.</p>

<p>Two UAE rules matter before you accept. Hotel staff work shifts, and the night overtime premium in the UAE labour rules does not apply to workers on shift patterns, so get any night or weekend allowance written into the contract. And wages must be paid through the Wage Protection System, which now runs under <strong>Ministerial Resolution No. 340 of 2026</strong>.</p>

<h2>How Do I Find Waiter Jobs in Italy?</h2>

<p>In Italy a waiter is a <strong>cameriere</strong> (male) or <strong>cameriera</strong> (female). Searching in Italian shows far more vacancies than searching in English. Useful terms are "cameriere di sala" (dining-room waiter), "cameriere ai piani" (floor service) and "aiuto cuoco" (kitchen assistant). Seasonal work is common in tourist areas such as the Amalfi coast, the lakes, Sardinia and the Dolomites.</p>

<p>Employers expect you to deal with guests and colleagues, so learn basic restaurant Italian before you apply.</p>

<h2>Can a Non-EU Citizen Work in Italy's Hotels and Restaurants?</h2>

<p>Yes, but only through the quota system known as the <strong>Decreto Flussi</strong>, and only after an Italian employer asks for you. Here is how the 2027 round works:</p>

<ol>
    <li><strong>The employer applies, not you.</strong> The employer requests a <strong>nulla osta</strong> (work authorisation) naming the worker and the sector, such as tourism.</li>
    <li><strong>Quotas are limited.</strong> The 2027 decree allows <strong>89,000 seasonal entries in agriculture and tourism</strong>.</li>
    <li><strong>Applications open on set "click days".</strong> Seasonal tourism applications open on <strong>9 February 2027</strong>, submitted through the Interior Ministry's ALI portal. Pre-filling runs from 23 October to 7 December 2026.</li>
    <li><strong>Some quotas are reserved by nationality.</strong> Check the official decree for your country before assuming a place is open to you.</li>
    <li><strong>The rules come from a joint circular.</strong> The procedures are set out in the joint circular of 1 October 2026 from the Interior, Labour, Agriculture and Tourism ministries.</li>
</ol>

<p>Because the process starts with the employer, get a written job offer first, then ask the employer to confirm in writing that they will file the nulla osta. Dates and quotas change each round, so check the official decree before you plan around any of them.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hotel-restaurant-jobs-uae-italy-terrace.jpg"
         alt="A waitress serving a couple at a waterfront terrace table while a chef plates food nearby, with the Dubai skyline and an Italian harbour town behind and an airliner overhead"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Should I Trust a Job Board or the Employer's Own Site?</h2>

<p>Use the employer's own careers page to confirm that a role exists, and use job boards only to discover employers. A listing on a board can be a copy of a vacancy that has closed, and some boards carry offers from third parties. Apply links that deep-link to one vacancy go stale quickly, so go to the employer's careers hub and search from there. If an Italian employer says they sponsor foreign workers, ask which quota and which click day they are using.</p>

<h2>How Do I Avoid Fake Hotel Job Offers?</h2>

<p><strong>Real employers do not charge application or visa fees.</strong> Jumeirah's careers page warns that third parties misuse its brand to offer non-existent jobs in return for payment, and states that it does not require fees for processing applications or employment visas. It also says genuine emails come from a jumeirah.com address and that offers follow an interview and a formal contract.</p>

<p>Before you reply to any offer:</p>

<ul>
    <li><strong>Check the sender's email domain</strong> against the company's official website.</li>
    <li><strong>Find the job on the official careers page.</strong> If it is not there, treat the offer as suspect.</li>
    <li><strong>Insist on a formal contract</strong> before paying for anything. In the UAE the employer pays recruitment costs by law; in Italy the nulla osta is requested by the employer, so nobody can sell you a place in the quota.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/hospitality-tourism" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Hospitality Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What documents do I need for a hotel job in Dubai?</h3>
<p>Usually a passport, a CV and proof of experience or certificates. The employer arranges the MOHRE work permit and residence visa, so check the specific job post for anything else.</p>

<h3>Can I work in a Dubai hotel on a visit visa?</h3>
<p>No. You need a work permit arranged by the employer. A visit or tourist visa does not allow you to work.</p>

<h3>Do I have to pay a recruitment fee for a UAE hotel job?</h3>
<p>No. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly.</p>

<h3>Do I need to speak Italian to work as a waiter in Italy?</h3>
<p>In practice, yes. Employers expect you to communicate with guests and staff, so learn basic restaurant Italian before applying.</p>

<h3>Can a non-EU citizen get a hotel or restaurant job in Italy?</h3>
<p>Yes, through the Decreto Flussi quota. An Italian employer must request a nulla osta for you, and seasonal tourism applications for 2027 open on 9 February 2027.</p>

<h3>How many seasonal workers does Italy admit for 2027?</h3>
<p>The 2027 decree allows 89,000 seasonal entries across agriculture and tourism.</p>

<h3>Can I apply for the Italian quota myself?</h3>
<p>No. The employer files the request through the Interior Ministry's ALI portal. You need a written job offer first.</p>

<h3>Is a job board enough, or should I also use official sites?</h3>
<p>Use both. A board shows the widest range of listings, while the employer's own careers page confirms which roles are genuine.</p>

<h2>People Also Search For</h2>

<h3>Hotel jobs in Dubai</h3>
<p>Apply through the hotel group's careers site; the employer arranges the work permit.</p>

<h3>Housekeeping jobs abroad</h3>
<p>Room attendant and housekeeping roles are common entry points in hotels.</p>

<h3>Waiter jobs in Italy</h3>
<p>Search for "cameriere" and expect to need basic Italian.</p>

<h3>Decreto Flussi 2027</h3>
<p>The quota decree: 89,000 seasonal entries in agriculture and tourism.</p>

<h3>Nulla osta seasonal tourism</h3>
<p>The work authorisation the Italian employer requests; applications open 9 February 2027.</p>

<h3>Restaurant jobs in UAE</h3>
<p>Waiter and kitchen roles with employer-sponsored work permits.</p>

<h3>Fake hotel job offers</h3>
<p>Real employers charge no application or visa fees.</p>

<h3>Hospitality jobs for foreigners</h3>
<p>Open to work-permit holders in the UAE and quota workers in Italy.</p>

<h2>More Job Guides</h2>

<p>Related hospitality and Gulf guides:</p>

<ul>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the US hotel route.</li>
    <li><a href="/blog/how-to-apply-for-marriott-hotel-jobs-in-the-usa">How to Apply for Marriott Hotel Jobs in the USA</a> &mdash; one hotel group in detail.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front desk roles in the Emirates.</li>
    <li><a href="/blog/airport-ground-staff-jobs-in-dubai">Airport Ground Staff Jobs in Dubai</a> &mdash; shift work and the night premium exception.</li>
    <li><a href="/blog/kitchen-helper-jobs-in-saudi-arabia">Kitchen Helper Jobs in Saudi Arabia</a> &mdash; the Saudi route for kitchen work.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; working in Italy outside hospitality.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Italian figures and dates are from the Interior and Labour ministries' announcements of the 2027 Decreto Flussi; UAE rules are from u.ae. Confirm them with the official authority before applying.</p>
HTML;
    }
}
