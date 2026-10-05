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
 * "Car Mechanic Jobs in Australia, UK and Canada" — the demand and
 * qualification page of the mechanic cluster, built on Jobs and Skills
 * Australia, the National Careers Service and Job Bank (NOC 72410).
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Individual vacancies with job-ID links and their pay: an Arnold Clark
 *    advert at GBP 40-50k, a JLR vacancy at GBP 48,764, and Sydney City
 *    Toyota, CMI Toyota and Dilawri adverts. Employers are named without links
 *    or pay.
 *
 * 2. "1,200 jobs on Job Bank" and similar live counts, which change daily.
 *
 * 3. An MTAA "39 per cent fill rate" that could not be traced to Jobs and
 *    Skills Australia.
 *
 * 4. Nothing on how a foreign mechanic actually qualifies. Added: TRA skills
 *    assessment for ANZSCO 321211, the UK Skilled Worker skill threshold after
 *    22 July 2025, and Red Seal and Ontario's compulsory 310S trade.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class CarMechanicJobsAustraliaUkCanadaBlogSeeder extends Seeder
{
    private const AUSTRALIA_APPLY_URL = 'https://www.workforceaustralia.gov.au/individuals/jobs/search';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch';

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
        $title = 'Car Mechanic Jobs in Australia, UK and Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Australia employs about 112,600 motor mechanics, the UK pays GBP 22,000 to GBP 42,000 and Canada a C$29.89 median an hour. How to get skills assessed, licensed or certified in each country.',
                'content' => $content,
                'featured_image' => 'blogs/car-mechanic-jobs-australia-uk-canada.jpg',
                'tags' => 'car mechanic jobs australia, motor mechanic jobs uk, automotive service technician canada, anzsco 321211, tra skills assessment, noc 72410, red seal automotive, 310s ontario',
                'meta_title' => 'Car Mechanic Jobs in Australia, UK and Canada',
                'meta_description' => 'Car mechanic jobs in Australia, the UK and Canada on official data: employment, pay, TRA assessment, UK visa rules and Red Seal certification.',
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

        $australiaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Australian Dealerships & Independent Workshops (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'australia-motor-mechanic-aggregated']
        );

        $australiaLocation = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Motor Mechanic — Australian Dealerships and Independent Workshops',
                'advertiser_id' => $australiaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $australiaLocation->id,
                'description' => $this->australiaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, typically around 44 hours a week',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::AUSTRALIA_APPLY_URL,
                'meta_description' => 'Motor mechanic roles with Australian dealerships and workshops. A Certificate III or a TRA skills assessment for ANZSCO 321211 is expected.',
                'seo_keywords' => 'motor mechanic jobs australia, car mechanic australia, anzsco 321211, light vehicle technician, mechanic jobs',
            ]
        );

        $canadaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Canadian Dealerships & Auto Service Centres (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-automotive-technician-aggregated']
        );

        $canadaLocation = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        Job::updateOrCreate(
            [
                'position' => 'Automotive Service Technician — Canadian Dealerships and Service Centres',
                'advertiser_id' => $canadaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $canadaLocation->id,
                'description' => $this->canadaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::CANADA_APPLY_URL,
                'meta_description' => 'Automotive service technician roles in Canada (NOC 72410). A Red Seal trade; certification is compulsory in Ontario under trade code 310S.',
                'seo_keywords' => 'automotive service technician canada, noc 72410, red seal automotive, 310s ontario, mechanic jobs canada',
            ]
        );
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Car dealerships, fleet operators and independent workshops across Australia recruit motor mechanics to service, diagnose and repair light vehicles.</p>

<h3>What the work involves</h3>
<ul>
    <li>Scheduled servicing, brakes, suspension and steering work</li>
    <li>Engine, electrical and computer-based diagnostics</li>
    <li>Recording work and parts on job cards</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>A Certificate III in Light Vehicle Mechanical Technology, or a Trades Recognition Australia skills assessment for ANZSCO 321211 if you trained overseas</li>
    <li>A driving licence</li>
    <li>Any state licence the work requires, such as a NSW tradesperson certificate or refrigerant handling licence for air conditioning work</li>
</ul>

<p><strong>Pay:</strong> Jobs and Skills Australia reports median full-time earnings of A$1,622 a week for motor mechanics. Individual employers set their own rates.</p>

<p><strong>Note:</strong> visa and skills assessment rules are set by the Department of Home Affairs and TRA, not by JobGader. Check them before applying.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Car dealerships, tire and service chains, fleet operators and independent garages across Canada recruit automotive service technicians under NOC 72410.</p>

<h3>What the work involves</h3>
<ul>
    <li>Inspecting, diagnosing and repairing engines, drivetrains, brakes and electrical systems</li>
    <li>Using computerised diagnostic equipment</li>
    <li>Explaining repairs and estimates to service advisers and customers</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>An apprenticeship of about four years, or equivalent experience plus trade courses</li>
    <li>Trade certification, which is <strong>compulsory in Ontario (310S)</strong> and in several other provinces</li>
    <li>The Red Seal endorsement for work across provinces</li>
</ul>

<p><strong>Pay:</strong> Job Bank reports a national median of C$29.89 an hour. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and immigration rules are set by provincial apprenticeship authorities and IRCC, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Car mechanics are needed in every country with cars on the road, but Australia, the United Kingdom and Canada are the three English-speaking markets where a qualified foreign mechanic has a realistic route in. Each one regulates the trade differently. This guide sets out the official employment and pay figures and, more importantly, the paperwork each country expects before you can work. For a fuller pay comparison across more countries, see <a href="/blog/mechanic-salary-by-country">Mechanic Salary by Country</a>.</p>

<h2>Australia: Motor Mechanics</h2>

<p>Jobs and Skills Australia publishes the official occupation profile for motor mechanics (ANZSCO 3212). Its current figures:</p>

<ul>
    <li><strong>About 112,600 people employed</strong> as motor mechanics</li>
    <li><strong>Median full-time earnings of A$1,622 a week</strong> before tax</li>
    <li><strong>44 hours</strong> a week on average for full-time workers, with 91 per cent working full time</li>
</ul>

<p>Most mechanics work for dealerships, independent workshops, fleet operators and tyre and service chains. Large dealer groups and franchises such as Toyota dealerships recruit continually, but so do small suburban garages, which advertise less.</p>

<h3>How to qualify from overseas</h3>

<ol>
    <li><strong>Skills assessment.</strong> The general motor mechanic occupation is <strong>ANZSCO 321211</strong>. Overseas-trained mechanics are assessed by <strong>Trades Recognition Australia (TRA)</strong>, usually through an approved registered training organisation that checks your evidence and runs a technical interview and practical assessment.</li>
    <li><strong>Visa.</strong> A positive assessment is the first step for employer-sponsored and skilled migration visas. Check the current occupation lists with the Department of Home Affairs, which change.</li>
    <li><strong>State licences.</strong> Some work needs a separate licence: refrigerant handling for vehicle air conditioning, and in some states a tradesperson certificate or licence to run a repair business.</li>
</ol>

<p>The step-by-step Australian route, including apprenticeships and award pay while training, is in <a href="/blog/how-to-become-an-auto-mechanic-in-australia">How to Become an Auto Mechanic in Australia</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/car-mechanic-jobs-australia-uk-canada-underbody.jpg"
         alt="A mechanic in a navy cap and safety glasses working with wrenches under a car raised on a lift, beside photos of the Sydney Opera House, Big Ben and the Toronto skyline"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>United Kingdom: Motor Mechanics and Vehicle Technicians</h2>

<p>The National Careers Service job profile for motor mechanics gives:</p>

<ul>
    <li><strong>&pound;22,000 a year</strong> for a starter</li>
    <li><strong>&pound;42,000 a year</strong> for an experienced mechanic</li>
    <li><strong>38 to 45 hours</strong> a week, which may include evenings and weekends</li>
</ul>

<p>UK routes into the trade are a college course in light vehicle maintenance, a <strong>Level 3 Motor Vehicle Service and Maintenance Technician apprenticeship</strong> of two to three years, or a breakdown company's own training academy. Main dealers such as Arnold Clark and Jaguar Land Rover run their own apprenticeship schemes.</p>

<h3>Can a foreign mechanic get a UK work visa?</h3>

<p>This is where most online guides are out of date. Vehicle technicians, mechanics and electricians sit under SOC 2020 code <strong>5231</strong>, a skilled trade below degree level. Since <strong>22 July 2025</strong>, new Skilled Worker visas generally need a job at RQF level 6 (graduate level) or above. Roles below that level qualify only if they appear on the Temporary Shortage List or Immigration Salary List. Check the current gov.uk Skilled Worker eligible occupations list for code 5231 before paying for any application, and treat any agent who promises a UK mechanic visa with suspicion.</p>

<h2>Canada: Automotive Service Technicians</h2>

<p>Job Bank lists the trade as <strong>NOC 72410</strong>, automotive service technicians, truck and bus mechanics and mechanical repairers. Its wage data, updated on 19 November 2025, gives a <strong>national median of C$29.89 an hour</strong>, with medians of C$35.00 in British Columbia and C$30.00 in Alberta and Ontario. At the national median, a 40-hour week over 52 weeks comes to about C$62,000 before overtime.</p>

<h3>How to qualify</h3>

<ol>
    <li><strong>Red Seal.</strong> Automotive service technician is a <strong>Red Seal trade</strong>. The Red Seal endorsement lets a certified technician work in any province without re-testing.</li>
    <li><strong>Compulsory certification.</strong> In Ontario the trade is <strong>310S Automotive Service Technician</strong>, a compulsory trade administered by Skilled Trades Ontario: you must be certified or a registered apprentice to do the work. Other provinces set their own rules.</li>
    <li><strong>Foreign experience.</strong> Experienced mechanics can apply to the provincial apprenticeship authority to have their experience assessed and, if accepted, sit the certification exam without repeating the apprenticeship.</li>
    <li><strong>Work permit.</strong> A job offer usually needs a Labour Market Impact Assessment, or you need a permanent residence route such as a provincial nominee programme. Check IRCC directly.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/car-mechanic-jobs-australia-uk-canada-engine.jpg"
         alt="A mechanic in safety glasses leaning over an open engine bay in a busy workshop, with Sydney, London and Toronto skylines shown alongside"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Country Should You Target?</h2>

<ul>
    <li><strong>Australia</strong> has the clearest assessment route for an overseas mechanic through TRA, and full-time mechanics work long weeks.</li>
    <li><strong>Canada</strong> rewards certification: once you hold a Red Seal you can move between provinces, and British Columbia pays the highest provincial median.</li>
    <li><strong>The United Kingdom</strong> pays experienced mechanics well, but since July 2025 its work visa route for the trade is narrow.</li>
</ul>

<p>Electric vehicles are changing the work in all three countries. High-voltage training is covered in <a href="/blog/ev-technician-jobs-training-and-career-guide">EV Technician Jobs: Training and Career Guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>How many motor mechanics work in Australia?</h3>
<p>Jobs and Skills Australia puts employment at about 112,600, with median full-time earnings of A$1,622 a week and a 44-hour average week.</p>

<h3>Who assesses overseas mechanics for Australia?</h3>
<p>Trades Recognition Australia (TRA), for the motor mechanic (general) occupation ANZSCO 321211, usually through an approved training organisation.</p>

<h3>How much does a mechanic earn in the UK?</h3>
<p>The National Careers Service gives &pound;22,000 a year for a starter and &pound;42,000 for an experienced motor mechanic.</p>

<h3>Can I get a UK Skilled Worker visa as a mechanic?</h3>
<p>Only in limited cases. Since 22 July 2025 new Skilled Worker visas generally need an RQF level 6 job; roles below that qualify only through the Temporary Shortage List or Immigration Salary List. Check SOC code 5231 on gov.uk.</p>

<h3>How much does an automotive service technician earn in Canada?</h3>
<p>Job Bank reports a national median of C$29.89 an hour for NOC 72410, with C$35.00 in British Columbia.</p>

<h3>Is automotive service technician a Red Seal trade?</h3>
<p>Yes. A Red Seal endorsement lets a certified technician work across Canadian provinces.</p>

<h3>Is certification compulsory in Ontario?</h3>
<p>Yes. 310S Automotive Service Technician is a compulsory trade in Ontario, so you must be certified or a registered apprentice.</p>

<h3>Which country is easiest for a foreign mechanic?</h3>
<p>Australia has the clearest skills assessment route through TRA. Canada needs provincial certification, and the UK visa route narrowed in July 2025.</p>

<h2>People Also Search For</h2>

<h3>Motor mechanic jobs Australia visa sponsorship</h3>
<p>Start with a TRA skills assessment for ANZSCO 321211.</p>

<h3>ANZSCO 321211</h3>
<p>Motor mechanic (general), assessed by Trades Recognition Australia.</p>

<h3>Mechanic salary Australia per week</h3>
<p>A$1,622 median full-time, from Jobs and Skills Australia.</p>

<h3>Motor mechanic salary UK</h3>
<p>&pound;22,000 starting to &pound;42,000 experienced.</p>

<h3>SOC code 5231</h3>
<p>Vehicle technicians, mechanics and electricians in the UK.</p>

<h3>NOC 72410</h3>
<p>Automotive service technicians and mechanical repairers in Canada.</p>

<h3>310S Ontario</h3>
<p>Automotive Service Technician, a compulsory trade.</p>

<h3>Red Seal automotive service technician</h3>
<p>The interprovincial endorsement for certified technicians.</p>

<h2>More Job Guides</h2>

<p>The rest of the mechanic cluster and related trades:</p>

<ul>
    <li><a href="/blog/mechanic-salary-by-country">Mechanic Salary by Country</a> &mdash; official pay figures for the USA, Australia, the UK, Canada and the Gulf rules.</li>
    <li><a href="/blog/ev-technician-jobs-training-and-career-guide">EV Technician Jobs: Training and Career Guide</a> &mdash; high-voltage qualifications and where the work is.</li>
    <li><a href="/blog/how-to-become-an-auto-mechanic-in-australia">How to Become an Auto Mechanic in Australia</a> &mdash; apprenticeships, award pay and state licences.</li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a> &mdash; the Gulf route for mechanics.</li>
    <li><a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a> &mdash; the same certification systems for a neighbouring trade.</li>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; another Red Seal trade in demand.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or immigration advice. Australian data is from Jobs and Skills Australia, UK pay from the National Careers Service and Canadian wages from Job Bank. Confirm visa and licensing rules with Home Affairs, TRA, UK Visas and Immigration or the provincial apprenticeship authority.</p>
HTML;
    }
}
