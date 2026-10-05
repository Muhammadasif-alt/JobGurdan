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
 * "Mechanic Salary by Country" — the pay page of the mechanic cluster, built
 * on BLS OEWS May 2025 (SOC 49-3023 and 49-3031), the BLS 2025-2035
 * projections, Jobs and Skills Australia, the National Careers Service and
 * Job Bank (NOC 72410).
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. A UAE salary of AED 3,908 a month with a range of AED 1,905 to 8,016,
 *    taken from Indeed, plus listing pay of AED 3,500 to 8,000 and a link to
 *    Indeed UAE. The UAE section now states the rules instead: no wage floor
 *    for expatriates, employer-paid recruitment costs and the WPS.
 *
 * 2. Single-vacancy pay: City Toyota at A$79,423 and Arnold Clark's
 *    advertised salary.
 *
 * The BLS figures were checked against the BLS API: median $50,620, 10th
 * percentile $34,660, 90th $81,790, automobile dealers $59,920, diesel
 * $61,770. Job Bank medians (C$29.89; BC C$35.00; AB and ON C$30.00) were
 * checked against Job Bank.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class MechanicSalaryByCountryBlogSeeder extends Seeder
{
    private const US_APPLY_URL = 'https://www.usa.gov/job-search';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Mechanic Salary by Country';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Official mechanic pay compared: a $50,620 US median, A$1,622 a week in Australia, GBP 22,000 to GBP 42,000 in the UK and C$29.89 an hour in Canada, plus the UAE rules that matter more than any salary survey.',
                'content' => $content,
                'featured_image' => 'blogs/mechanic-salary-by-country.jpg',
                'tags' => 'mechanic salary by country, auto mechanic salary usa, mechanic salary australia, mechanic salary uk, mechanic salary canada, mechanic salary uae, diesel mechanic salary, highest paying country for mechanics',
                'meta_title' => 'Mechanic Salary by Country: USA, Australia, UK, Canada',
                'meta_description' => 'Mechanic pay on official data: BLS $50,620 US median, A$1,622 a week in Australia, UK and Canadian figures, and the UAE wage rules.',
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
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $usAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'US Auto Dealerships & Repair Shops (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-automotive-technician-aggregated']
        );

        $usLocation = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            [
                'position' => 'Automotive Service Technician — US Dealerships and Repair Shops',
                'advertiser_id' => $usAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $usLocation->id,
                'description' => $this->usJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, often including Saturdays',
                'language' => 'English',
                // BLS puts the bottom tenth under $34,660 and the top tenth
                // over $81,790; no single range is honest here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::US_APPLY_URL,
                'meta_description' => 'Automotive service technician roles with US dealerships and repair shops. ASE certification is voluntary but widely requested.',
                'seo_keywords' => 'automotive technician jobs usa, auto mechanic jobs, car mechanic usa, ase certified technician, dealership technician',
            ]
        );
    }

    private function usJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Car dealerships, independent repair shops, tire and service chains and fleet operators across the United States recruit automotive service technicians.</p>

<h3>What the work involves</h3>
<ul>
    <li>Inspecting, maintaining and repairing cars and light trucks</li>
    <li>Computer-based diagnostics of engine, electrical and hybrid systems</li>
    <li>Brakes, steering, suspension and drivetrain repairs</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>A postsecondary automotive programme or equivalent experience</li>
    <li>ASE certification, which is voluntary but widely requested</li>
    <li>EPA Section 609 certification for vehicle air conditioning work</li>
    <li>A driving licence; many employers expect you to own basic tools</li>
</ul>

<p><strong>Pay:</strong> BLS reports a May 2025 median of $50,620, and $59,920 at automobile dealers. Individual employers set their own rates, often on a flat-rate basis.</p>

<p><strong>Note:</strong> work authorisation and any state requirements are set by US authorities, not by JobGader. Check them before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"How much does a mechanic earn?" has a different answer in every country, and most salary pages answer it with job-board averages that drift from the official data. This guide uses only government sources: the US Bureau of Labor Statistics, Jobs and Skills Australia, the UK National Careers Service and Canada's Job Bank. For the Gulf, where no government publishes a mechanic wage, it sets out the rules that protect your pay instead. If you want to know how to qualify in each country rather than what it pays, read <a href="/blog/car-mechanic-jobs-in-australia-uk-and-canada">Car Mechanic Jobs in Australia, UK and Canada</a>.</p>

<h2>Mechanic Pay at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Official figure</th>
            <th style="padding:10px;text-align:left;">Source</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">United States</td><td style="padding:10px;">$50,620 a year median</td><td style="padding:10px;">BLS, May 2025</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Australia</td><td style="padding:10px;">A$1,622 a week median, full time</td><td style="padding:10px;">Jobs and Skills Australia</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">United Kingdom</td><td style="padding:10px;">&pound;22,000 starter to &pound;42,000 experienced</td><td style="padding:10px;">National Careers Service</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Canada</td><td style="padding:10px;">C$29.89 an hour median</td><td style="padding:10px;">Job Bank, NOC 72410</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">UAE</td><td style="padding:10px;">No official figure; no wage floor for expatriates</td><td style="padding:10px;">UAE Labour Law</td></tr>
    </tbody>
</table>
</div>

<p>The figures are in different currencies, cover different periods and measure different things (annual, weekly, hourly), so they are not converted here. Taxes, overtime and living costs change the picture more than exchange rates do.</p>

<h2>United States</h2>

<p>BLS Occupational Employment and Wage Statistics, May 2025, for automotive service technicians and mechanics (SOC 49-3023):</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Annual wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Bottom 10 per cent earned less than</td><td style="padding:10px;">$34,660</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$50,620</strong> ($24.34 an hour)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Median at automobile dealers</td><td style="padding:10px;">$59,920</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Top 10 per cent earned more than</td><td style="padding:10px;">$81,790</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Diesel service technicians and mechanics (49-3031), median</td><td style="padding:10px;">$61,770</td></tr>
    </tbody>
</table>
</div>

<p>Two things lift US pay: working at a <strong>dealership</strong>, where the median is about $9,000 above the all-employer figure, and moving into <strong>diesel</strong>, where the median is $61,770. Many technicians are paid on a flat-rate system, earning per job rather than per hour, so a fast, certified technician can beat the median and a slow week can fall short of it.</p>

<p>The BLS Occupational Outlook Handbook, in its <strong>2025 to 2035</strong> projections, expects employment to grow <strong>5 per cent</strong>, faster than average, with about <strong>66,200 openings a year</strong>, most of them to replace technicians who retire or leave.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mechanic-salary-by-country-brakes.jpg"
         alt="A mechanic in a navy cap working on a car's brake disc and hub, beside a world map linking Sydney, London and Toronto and stacks of coins under Australian, British and Canadian flags"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Australia</h2>

<p>Jobs and Skills Australia reports <strong>median full-time earnings of A$1,622 a week</strong> for motor mechanics, before tax. Full-time mechanics average <strong>44 hours</strong> a week, so the weekly figure includes some longer hours. Apprentices and first-year mechanics are paid under the Vehicle Repair, Services and Retail Award, which sets minimum rates rather than typical pay.</p>

<h2>United Kingdom</h2>

<p>The National Careers Service puts motor mechanic pay at <strong>&pound;22,000 a year for a starter</strong> and <strong>&pound;42,000 for an experienced mechanic</strong>, on 38 to 45 hours a week. Main-dealer master technicians and specialists in diagnostics or electric vehicles sit at the upper end. Advertised salaries for single vacancies are not a guide to the market and are not repeated here.</p>

<h2>Canada</h2>

<p>Job Bank reports wages for automotive service technicians (NOC 72410), updated on 19 November 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Median hourly wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;"><strong>C$29.89</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">C$35.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">C$30.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">C$30.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">C$30.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">C$28.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">C$25.00</td></tr>
    </tbody>
</table>
</div>

<p>At the national median, a 40-hour week over 52 weeks comes to about <strong>C$62,000</strong> before overtime. Red Seal certification is what moves a technician from apprentice rates to these figures.</p>

<h2>UAE and the Gulf: The Rules, Not a Salary Survey</h2>

<p>No UAE government body publishes a wage figure for mechanics, and the averages that circulate online come from job adverts. What you can rely on is the law:</p>

<ul>
    <li><strong>No statutory minimum wage for expatriates.</strong> Your pay is whatever your contract says, so read the offer letter and the Ministry of Human Resources and Emiratisation contract before you travel.</li>
    <li><strong>The employer pays recruitment costs.</strong> Under Article 6 of Federal Decree-Law No. 33 of 2021, an employer may not charge a worker recruitment or employment fees. Any agent who asks you to pay for a visa or job is breaking the law.</li>
    <li><strong>Wages are paid through the WPS.</strong> Private-sector salaries go through the Wage Protection System, now governed by Ministerial Resolution No. 340 of 2026, which gives the ministry a record if pay is late.</li>
</ul>

<p>For the Saudi side, see <a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mechanic-salary-by-country-engine.jpg"
         alt="A mechanic tightening a bolt in an open engine bay, with Sydney, London and Toronto scenes above a world map and rising stacks of coins topped with Australian, British and Canadian flags"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Earn More as a Mechanic</h2>

<ol>
    <li><strong>Specialise in diesel or heavy vehicles.</strong> In the US the diesel median is $61,770 against $50,620 for all auto technicians.</li>
    <li><strong>Get certified.</strong> ASE in the US, Red Seal in Canada and a Level 3 qualification in the UK are what employers pay for.</li>
    <li><strong>Learn high-voltage work.</strong> Electric and hybrid vehicles need technicians with safety training; see <a href="/blog/ev-technician-jobs-training-and-career-guide">EV Technician Jobs: Training and Career Guide</a>.</li>
    <li><strong>Work for a dealer.</strong> US dealership technicians earn a $59,920 median.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much does a car mechanic earn in the USA?</h3>
<p>BLS reports a May 2025 median of $50,620 a year, or $24.34 an hour. The bottom tenth earned under $34,660 and the top tenth over $81,790.</p>

<h3>Do diesel mechanics earn more?</h3>
<p>Yes. The BLS median for diesel service technicians and mechanics is $61,770, against $50,620 for automotive technicians.</p>

<h3>Is mechanic demand growing in the USA?</h3>
<p>BLS projects 5 per cent growth from 2025 to 2035, with about 66,200 openings a year.</p>

<h3>How much does a mechanic earn in Australia?</h3>
<p>Jobs and Skills Australia reports median full-time earnings of A$1,622 a week, on an average 44-hour week.</p>

<h3>How much does a mechanic earn in the UK?</h3>
<p>The National Careers Service gives &pound;22,000 for a starter and &pound;42,000 for an experienced motor mechanic.</p>

<h3>How much does a mechanic earn in Canada?</h3>
<p>Job Bank reports a national median of C$29.89 an hour, with C$35.00 in British Columbia and C$30.00 in Alberta and Ontario.</p>

<h3>Is there a minimum wage for mechanics in the UAE?</h3>
<p>No. The UAE sets no statutory minimum wage for expatriate workers, so the contract decides your pay. Salaries must be paid through the Wage Protection System.</p>

<h3>Should I pay an agent for a mechanic job abroad?</h3>
<p>No. In the UAE, Article 6 of Federal Decree-Law No. 33 of 2021 makes recruitment costs the employer's responsibility.</p>

<h2>People Also Search For</h2>

<h3>Auto mechanic salary USA</h3>
<p>$50,620 median in May 2025.</p>

<h3>Diesel mechanic salary USA</h3>
<p>$61,770 median for SOC 49-3031.</p>

<h3>Dealership technician salary</h3>
<p>$59,920 median at US automobile dealers.</p>

<h3>Mechanic salary Australia per week</h3>
<p>A$1,622 median full-time.</p>

<h3>Mechanic salary UK per year</h3>
<p>&pound;22,000 to &pound;42,000.</p>

<h3>Automotive technician wage Canada</h3>
<p>C$29.89 an hour national median.</p>

<h3>Mechanic salary in Dubai</h3>
<p>No official figure; check the contract and the WPS rules.</p>

<h3>Highest paying country for mechanics</h3>
<p>It depends on currency, tax and hours; compare the official figures above.</p>

<h2>More Job Guides</h2>

<p>The rest of the mechanic cluster and related trades:</p>

<ul>
    <li><a href="/blog/car-mechanic-jobs-in-australia-uk-and-canada">Car Mechanic Jobs in Australia, UK and Canada</a> &mdash; skills assessment, visas and certification in each country.</li>
    <li><a href="/blog/ev-technician-jobs-training-and-career-guide">EV Technician Jobs: Training and Career Guide</a> &mdash; high-voltage training and the EV market.</li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a> &mdash; the Gulf route for mechanics.</li>
    <li><a href="/blog/how-to-become-an-auto-mechanic-in-australia">How to Become an Auto Mechanic in Australia</a> &mdash; apprenticeships and award pay.</li>
    <li><a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">Industrial vs House Wiring Electrician: Which Pays More</a> &mdash; the same pay question for electricians.</li>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; a neighbouring trade on BLS and Job Bank data.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or immigration advice. US pay is from BLS OEWS May 2025 and projections from the BLS Occupational Outlook Handbook; Australian data is from Jobs and Skills Australia, UK pay from the National Careers Service and Canadian wages from Job Bank. UAE rules are from Federal Decree-Law No. 33 of 2021 and its implementing resolutions.</p>
HTML;
    }
}
