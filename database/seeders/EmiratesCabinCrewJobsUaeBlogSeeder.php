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
 * "How to Apply for Emirates Cabin Crew Jobs in the UAE" — an employer guide
 * written from Emirates' own cabin crew pages, with UAE government sources
 * for the tax and end-of-service rules.
 *
 * Corrections to the draft (checked against emiratesgroupcareers.com and
 * u.ae, September 2026):
 *
 * 1. The draft attributes the pay table to "third-party salary guides" and
 *    hedges it against a conflicting AED 4,430 basic. Those figures are
 *    Emirates' own published numbers: basic AED 4,980 a month, flying pay
 *    AED 69.6 an hour over 80 to 100 hours, average AED 11,244 a month. The
 *    AED 4,430 figure appears nowhere on Emirates' site and is dropped.
 *
 * 2. The draft lists "furnished shared accommodation... according to salary
 *    guides". Emirates states it directly, and states it is free including
 *    utilities, alongside company transport and 30 days of annual leave with
 *    one free ticket home a year.
 *
 * 3. The draft says crew "could be rostered to your home country". Emirates
 *    says crew are not assigned to a specific region and fly different routes
 *    each month, which is a different claim.
 *
 * 4. The draft's job search URL is malformed, with a duplicated and encoded
 *    query string. It loads but does not filter to cabin crew.
 *
 * 5. The draft has no fraud section. Emirates publishes an explicit
 *    recruitment fraud warning, which belongs in any guide aimed at
 *    applicants paying attention to unsolicited offers.
 *
 * 6. The draft omits the UAE end-of-service gratuity rule, which is the part
 *    of the package most applicants misjudge.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EmiratesCabinCrewJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.emiratesgroupcareers.com/cabin-crew/';

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
        $title = 'How to Apply for Emirates Cabin Crew Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Emirates publishes its own cabin crew pay: a basic of AED 4,980 a month plus flying pay of AED 69.6 an hour, averaging AED 11,244, with free furnished accommodation. Here are the real requirements and how the Open Day works.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-emirates-cabin-crew-jobs-in-uae.jpg',
                'tags' => 'emirates cabin crew jobs, cabin crew jobs uae, emirates open day, emirates cabin crew salary, flight attendant jobs dubai, emirates group careers, cabin crew requirements, dubai airline jobs',
                'meta_title' => 'Emirates Cabin Crew Jobs in UAE: Pay and How to Apply',
                'meta_description' => 'Emirates cabin crew jobs: the height, age and experience rules, the pay Emirates publishes, Open Day process, training and UAE gratuity rules.',
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
            ['name' => 'Emirates Group, Dubai'],
            ['type' => 'Company', 'display_reference' => 'emirates-group-dubai']
        );

        // Matches the shared location row the other UAE guides use, so the
        // listing card reads "United Arab Emirates, ..." like its siblings
        // rather than repeating the city.
        $location = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cabin Crew, Emirates, Dubai Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Monthly roster with approximately eight days off a month in Dubai',
                'language' => 'English',
                // Emirates publishes a basic plus variable flying pay rather
                // than a single salary, so the guide sets out the components
                // instead of a band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Dubai-based cabin crew roles with Emirates, recruited worldwide through Open Days and assessment days, with relocation to the UAE.',
                'seo_keywords' => 'emirates cabin crew jobs, cabin crew jobs dubai, flight attendant jobs uae, emirates open day',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Emirates recruits cabin crew worldwide for roles based in Dubai, hiring through Open Days and assessment days rather than through a conventional interview process.</p>

<h3>What the work involves</h3>
<p>Safety and emergency duties on board, cabin service across Economy, Business and First Class, and customer care on long and ultra-long haul routes, working a monthly roster from a Dubai base.</p>

<h3>Requirements Emirates publishes</h3>
<ul>
    <li>At least 21 years old</li>
    <li>At least 160cm tall and able to reach 212cm high</li>
    <li>Fluent in written and spoken English</li>
    <li>At least 1 year of hospitality or customer service experience</li>
    <li>A minimum of high school (Grade 12) education</li>
    <li>No visible tattoos while in the Emirates cabin crew uniform</li>
    <li>Able to meet the UAE's employment visa requirements</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> the requirements, pay and recruitment events are set and published by the Emirates Group &mdash; not by JobGader. Emirates states that any job offer appearing to come from it that asks you for money is fraudulent.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Register on the Emirates Group Careers site, check you meet the physical and experience requirements, and attend an Open Day or assessment day in person.</strong> Emirates says it is not currently offering online assessment days, so the process happens face to face.</p>

<p>The requirements are unusually specific, and one of them stops more applicants than all the others combined: you must be able to <strong>reach 212cm</strong>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.emiratesgroupcareers.com/cabin-crew/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9992; Emirates Cabin Crew Careers &rarr;
    </a>
</div>

<h2>The Requirements, Exactly as Emirates States Them</h2>

<ul>
    <li><strong>At least 21 years old.</strong> Emirates publishes no maximum age.</li>
    <li><strong>At least 160cm tall, and able to reach 212cm high.</strong> The reach is measured, usually on tiptoes against a wall, because it is what lets you access the overhead safety equipment. Height alone is not the test.</li>
    <li><strong>Fluent in written and spoken English.</strong> Other languages are an advantage rather than a requirement.</li>
    <li><strong>At least 1 year of hospitality or customer service experience.</strong> Retail, restaurant, hotel and call centre work all count.</li>
    <li><strong>A minimum of high school, Grade 12, education.</strong> No degree required.</li>
    <li><strong>No visible tattoos while in the Emirates cabin crew uniform.</strong> The test is visibility in uniform, not whether you have tattoos.</li>
    <li><strong>Able to meet the UAE's employment visa requirements.</strong></li>
</ul>

<p>These apply equally to all candidates. There is no separate standard for men and women.</p>

<h2>What Emirates Actually Pays</h2>

<p>This is worth stating plainly, because most guides present these numbers as third-party estimates and hedge them against a conflicting basic salary figure. <strong>They are Emirates' own published figures</strong> for Grade II, Economy Class crew:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Component</th>
            <th style="padding:10px;text-align:left;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Basic salary</td><td style="padding:10px;"><strong>AED 4,980 a month</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Flying pay</td><td style="padding:10px;"><strong>AED 69.6 an hour</strong>, on an average of 80 to 100 hours a month</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Average total</strong></td><td style="padding:10px;"><strong>AED 11,244 a month</strong> (about USD 3,061)</td></tr>
    </tbody>
</table>
</div>

<p>The structure matters more than the headline. Roughly <strong>55% of your pay is variable</strong>, because it depends on hours flown. A light roster month pays noticeably less than a heavy one, and that is normal rather than a problem with your contract.</p>

<p>On top of the cash, Emirates lists:</p>

<ul>
    <li><strong>Free furnished accommodation, including utilities</strong>, shared with other crew.</li>
    <li><strong>Company transport</strong> to and from the airport.</li>
    <li><strong>30 days of annual leave</strong> plus one free ticket home a year.</li>
    <li><strong>Medical and dental cover</strong> at Emirates Clinics, plus life and accident insurance.</li>
    <li><strong>A non-contributory end of service benefit.</strong></li>
</ul>

<p>Because accommodation and utilities are provided, the take-home figure goes much further than the same number would elsewhere. <strong>The UAE does not levy income tax on individuals</strong>, so the AED 11,244 is not reduced by payroll tax either.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-emirates-cabin-crew-jobs-in-uae-cabin.jpg" alt="Emirates cabin crew on duty in the aircraft cabin" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Gratuity Rule Most Applicants Misjudge</h2>

<p>The "non-contributory end of service benefit" is a real UAE legal entitlement, not a company perk, and it is worth knowing how it accrues before you plan around it. Under UAE private sector rules:</p>

<ul>
    <li>You qualify after <strong>one year or more of continuous service</strong>. Leave before that and you get nothing.</li>
    <li>You earn <strong>21 days' salary for each of the first five years</strong>, then <strong>30 days' salary for each year after that</strong>.</li>
    <li>The total is capped at <strong>two years' wages</strong>.</li>
    <li>It is calculated on <strong>basic salary only</strong>, excluding housing, transport and other allowances.</li>
</ul>

<p>That last point is the one people get wrong. For cabin crew the basic is AED 4,980, not the AED 11,244 average, so the gratuity accrues against the smaller number.</p>

<h2>How the Open Day Works</h2>

<p>Emirates does not run a conventional application-to-interview pipeline. You register an account, then attend a recruitment event, and Emirates holds these in cities around the world. If nothing is scheduled where you live, you can attend one elsewhere, and Emirates says it will tell you when it is visiting somewhere nearby.</p>

<ol>
    <li><strong>Introduction.</strong> A presentation about the role and the company, and the first look at candidates.</li>
    <li><strong>Assessment.</strong> Group activities testing teamwork and communication, plus an online test taken at the venue. The reach test happens around this stage.</li>
    <li><strong>Final interview.</strong> A longer one-to-one about your motivation and your customer service experience.</li>
</ol>

<p><strong>What to bring:</strong> an up-to-date CV in English, a copy of your education certificate, and a copy of your passport as a PDF. If you do not have a passport yet, bring valid photo identification, though you will need a passport if you are selected.</p>

<p>Dress as you would for a formal interview in a customer-facing industry. Emirates shows examples rather than publishing written rules, so err toward business formal and conservative grooming.</p>

<h2>Training and the First Year</h2>

<ul>
    <li><strong>Training runs seven and a half weeks in Dubai</strong>, covering induction, safety and emergency procedures, group medical training, security, hospitality, uniform and service.</li>
    <li><strong>Probation is the first six months.</strong></li>
    <li><strong>The roster gives approximately eight days off a month</strong> in Dubai, and you receive it at graduation.</li>
    <li><strong>You are not assigned to a region.</strong> Emirates states crew fly different routes each month rather than being allocated to one part of the network, so plan on variety rather than on being routed home.</li>
    <li><strong>Progression runs Economy, Business, First Class, Cabin Supervisor, then Purser</strong>, and Emirates says strong performers can reach Purser within about five years.</li>
</ul>

<p>The safety component is the largest single block of the course, which tells you what the job actually is. Service is the visible part; the reason the role exists is the thirteen days on emergency procedures.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-emirates-cabin-crew-jobs-in-uae-training.jpg" alt="Cabin crew candidates at an Emirates recruitment and training session" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Recruitment Fraud: Emirates' Own Warning</h2>

<p>Airline cabin crew recruitment is one of the most impersonated hiring processes in the world, and Emirates publishes a direct warning about it. In its words, <strong>any job offer seemingly from the Emirates Group that asks you for money is fraudulent</strong>, and the Emirates Group will never ask for money transfers or payment of any kind from job applicants, nor for advance payment toward travel expenses, visas or work permits if your application succeeds.</p>

<p>So: no agent fee, no "assessment registration fee", no visa deposit, no training payment. Register directly on the Emirates Group Careers site, and treat any offer arriving by WhatsApp or a free email account as fake until proven otherwise.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do Emirates cabin crew earn?</h3>
<p>Emirates publishes a basic salary of AED 4,980 a month plus flying pay of AED 69.6 an hour over 80 to 100 hours, averaging AED 11,244 a month, with free furnished accommodation on top.</p>

<h3>What is the height requirement for Emirates cabin crew?</h3>
<p>At least 160cm tall and able to reach 212cm high. The reach is the test that matters, because it is what lets you access overhead safety equipment.</p>

<h3>Do I need a degree to be Emirates cabin crew?</h3>
<p>No. A minimum of high school, Grade 12, education is required, along with at least one year of hospitality or customer service experience.</p>

<h3>Can I have tattoos as Emirates cabin crew?</h3>
<p>Emirates requires no visible tattoos while in the cabin crew uniform. The rule is about visibility in uniform, not about having tattoos at all.</p>

<h3>Is there an online assessment for Emirates cabin crew?</h3>
<p>Not currently. Emirates says it is not offering online assessment days, so you need to attend an Open Day or assessment day in person.</p>

<h3>How long is Emirates cabin crew training?</h3>
<p>Seven and a half weeks in Dubai, covering induction, safety and emergency procedures, medical training, security, hospitality, uniform and service.</p>

<h3>Is Emirates cabin crew salary tax-free?</h3>
<p>The UAE does not levy income tax on individuals, so the salary is not reduced by payroll tax. Accommodation and utilities are also provided free.</p>

<h3>Does Emirates charge a fee to apply?</h3>
<p>No. Emirates states that any job offer seemingly from it that asks you for money is fraudulent, and that it never asks applicants for payment of any kind.</p>

<h2>People Also Search For</h2>

<h3>Emirates cabin crew salary in dirhams</h3>
<p>A published average of AED 11,244 a month, made up of AED 4,980 basic plus flying pay.</p>

<h3>Emirates open day requirements</h3>
<p>CV in English, education certificate copy and a passport copy, plus the 21-year, 160cm and 212cm reach standards.</p>

<h3>Emirates cabin crew reach test</h3>
<p>You must be able to reach 212cm high, which is how overhead safety equipment is accessed.</p>

<h3>Cabin crew accommodation Dubai</h3>
<p>Emirates provides free furnished shared accommodation including utilities, plus transport to the airport.</p>

<h3>UAE end of service gratuity</h3>
<p>21 days' basic salary a year for the first five years, 30 days a year after that, capped at two years' wages.</p>

<h3>Emirates cabin crew days off</h3>
<p>Approximately eight days off a month in Dubai, on a roster issued at graduation.</p>

<h3>Emirates purser promotion</h3>
<p>Progression runs Economy to Business to First Class to Cabin Supervisor to Purser, reachable in about five years for strong performers.</p>

<h3>Emirates recruitment scam</h3>
<p>Emirates says any offer in its name that asks you for money is fraudulent, including visa and travel advance payments.</p>

<h2>More Job Guides</h2>

<p>Looking at Gulf and hospitality work more widely? These cover it:</p>

<ul>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-of-house work in Dubai and Abu Dhabi.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the hospitality experience Emirates asks for, and where to get it.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the other big Gulf market and how its sponsorship works.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; another route into the Emirates with employer sponsorship.</li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a> &mdash; the professional end of the UAE market.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; driving and delivery work in the Emirates.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the Emirates Group Careers cabin crew pages for requirements, pay, process and training, the Emirates recruitment fraud notice, and UAE government guidance for tax and end-of-service rules. Requirements and recruitment events change between hiring cycles. Always check the official cabin crew page before you travel to an Open Day.</p>
HTML;
    }
}
