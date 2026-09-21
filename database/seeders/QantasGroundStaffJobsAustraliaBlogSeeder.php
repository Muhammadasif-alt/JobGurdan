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
 * "How to Apply for Qantas Ground Staff Jobs in Australia" — the guide that
 * splits one job title into two. Qantas outsourced ramp and baggage loading
 * in 2020 and still employs airport customer service directly, so "ground
 * staff" points at two employers depending on which half you mean.
 *
 * Corrections to the draft (checked against qantasnewsroom.com.au,
 * careers.qantas.com, the Fair Work Commission's copy of the Qantas ASU
 * agreement AE531839, the Airline Operations Ground Staff Award MA000048,
 * auscheck.gov.au, immi.homeaffairs.gov.au and ato.gov.au, 22 September 2026):
 *
 * 1. The draft's pay table is Glassdoor and Indeed estimates. They are
 *    replaced by the enterprise agreement Qantas itself points applicants
 *    to, which the Fair Work Commission publishes in full.
 *
 * 2. The draft treats ramp and baggage loading as a Qantas job. Qantas
 *    outsourced it at ten airports in 2020; the Federal Court fined Qantas
 *    $90 million over it in 2025. Passenger ramp work at those ports is
 *    contractor work now.
 *
 * 3. The draft says lifting up to 30 kg. The live Qantas Ground Services
 *    advert says 32 kg.
 *
 * 4. "Qantas College", Australian Qualifications Framework recognition and
 *    an "attractive superannuation scheme" appear nowhere on Qantas's site.
 *    Superannuation is a legal minimum of 12 per cent, not a Qantas perk.
 *
 * 5. The "more than 7,000 people" figure comes from a page that no longer
 *    exists, and predates the outsourcing of 1,820 ground handlers.
 *
 * 6. The draft's ground operations link redirects to Qantas Freight, and its
 *    minimum age of 18 was attributed to third-party guides; it is on a live
 *    Qantas Group advert.
 *
 * 7. The draft omits the decisive requirement: Qantas advertises these roles
 *    as needing work rights without restrictions or sponsorship, and none of
 *    the occupations appear on the Core Skills Occupation List.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class QantasGroundStaffJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.qantas.com/teams/corporate/customer-service/';

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
        $title = 'How to Apply for Qantas Ground Staff Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Qantas will not publish what its airport staff earn, but the enterprise agreement it points you to is a public document. Here is the real pay table, and why half of these jobs are no longer Qantas jobs at all.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-qantas-ground-staff-jobs-in-australia.jpg',
                'tags' => 'qantas ground staff jobs, qantas airport jobs, qantas careers australia, airport customer service agent, baggage handler jobs australia, asic card, aviation jobs australia, australian work rights',
                'meta_title' => 'Qantas Ground Staff Jobs: How to Apply',
                'meta_description' => 'Qantas ground staff jobs in Australia: the real pay from the public enterprise agreement, the ASIC check, and why ramp work is no longer a Qantas job.',
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
            ['name' => 'Qantas Group, Australian Airports'],
            ['type' => 'Company', 'display_reference' => 'qantas-group-airports']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Airport Customer Service, Qantas Group, Australian Airports',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Part-time, minimum 20 hours a week and a maximum of 30 ordinary hours, on a rotating roster',
                'language' => 'English',
                // Qantas publishes no rate and states salary is discussed during
                // recruitment. The enterprise agreement figure quoted in the
                // guide is a full-time base, and these roles are part-time, so
                // it would mislead as this listing's pay.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Qantas airport customer service roles across Australian airports. Part-time rosters, ASIC check required, and no visa sponsorship.',
                'seo_keywords' => 'qantas ground staff jobs, qantas airport jobs, airport customer service agent, asic card, aviation jobs australia',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the airport customer service roles the Qantas Group advertises across Australian airports, not a single vacancy and not a job advertised by JobGader. Applications are made on Qantas's own careers site. These are in-person roles at an airport.</p>

<h3>What the work involves</h3>
<ul>
    <li>Check-in, bag drop, boarding, document checks and assisting customers through the terminal.</li>
    <li>Baggage Services desk work: enquiries, search and locate, and reconnecting customers with lost property using the WorldTracer system.</li>
    <li>Lounge and premium customer service roles.</li>
</ul>

<h3>Who the employer is</h3>
<p>Not every job at a Qantas airport is a Qantas job. Qantas outsourced ramp and baggage loading at ten Australian airports in 2020, and that work is now done by contractors. Airport customer service was never in scope and remains directly employed. Qantas Ground Services, Jetstar and the regional airlines each employ under their own agreements.</p>

<h3>Hours</h3>
<p>Qantas states that all its airport customer service employees work part-time on a roster system organised into shifts. The enterprise agreement sets a minimum of 20 hours a week, a minimum daily engagement of four hours, and a maximum of 30 ordinary hours a week.</p>

<h3>Pay</h3>
<p>Qantas publishes no figure, stating it provides salary information during recruitment. The agreement it points applicants to is public: the airports entry level is Level 3, and the Level 3 year one annual base is $61,608 from 1 July 2026. That is a full-time base, and these roles are part-time, so expect it pro rata plus shift loadings.</p>

<h3>Pre-employment checks</h3>
<p>Qantas requires successful candidates to pass a pre-employment medical and an Aviation Security Identification Card check, and to provide two professional references. The ASIC background check is run by AusCheck and can take six to eight weeks.</p>

<h3>Work rights</h3>
<p>Qantas Group adverts for these roles require the right to work in Australia or New Zealand without restrictions or sponsorship. None of these occupations appear on Australia's Core Skills Occupation List, so there is no employer-sponsored visa route into them.</p>

<p>Pay, rosters, security clearances and visa rules are set by the Qantas Group, the Fair Work Commission, AusCheck and the Department of Home Affairs &mdash; not by JobGader. Confirm the requirements on the live posting before you apply.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Two things make this job hard to research honestly, and almost every article about it gets both wrong.</p>

<p>The first is that <strong>"Qantas ground staff" is not one job with one employer.</strong> Half of it is no longer a Qantas job at all.</p>

<p>The second is pay. Qantas refuses to publish a figure. So other sites fill the gap with Glassdoor and Indeed estimates. We are not going to do that, because we do not need to: <strong>the real pay table is a public government document</strong>, and we have read it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-qantas-ground-staff-jobs-in-australia-ramp.jpg" alt="Qantas ground crew in high visibility vests loading baggage beside an aircraft on the apron" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Ramp and baggage loading at ten Australian airports was outsourced in 2020. Airport customer service was never in scope and is still employed by Qantas.</figcaption>
</figure>

<h2>Which "Ground Staff" Job Do You Actually Mean?</h2>

<p>This distinction decides which company you apply to, so it comes first.</p>

<p>In August 2020 Qantas announced it was reviewing its ground handling. Its own statement said Qantas and Jetstar directly employed people in ground operations roles including baggage handling and aircraft cleaning at 11 large airports, and that it proposed to outsource that work at ten of them. The same statement drew the line explicitly: <strong>customer facing team members at airports were not impacted or in scope.</strong></p>

<p>The outsourcing went ahead, and it went badly. In 2023 the High Court upheld rulings that it was unlawful. In August 2025 Qantas accepted a <strong>$90 million penalty</strong> from the Federal Court and apologised to the <strong>1,820 ground handling employees</strong> affected, having already paid $120 million into a compensation fund.</p>

<p>So in 2026:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">The job</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Who employs it</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Airport customer service, check-in, boarding, lounges, Baggage Services desk</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Qantas Airways Limited, directly</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Passenger ramp and baggage loading at the ten outsourced airports</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Ground handling contractors, not Qantas</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Freight ground crew</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Qantas Ground Services, a Qantas subsidiary</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Jetstar and regional airline airport crew</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Jetstar and the regional entities, each on their own agreement</td>
        </tr>
    </tbody>
</table>

<p><strong>Watch the wording trap.</strong> Qantas "Baggage Services" and "baggage handling" sound identical and are different jobs. Baggage Services is a customer service desk role tracing lost bags on WorldTracer, still employed by Qantas. Baggage handling is loading aircraft on the ramp, and at those ten ports it is contractor work. If you apply to the wrong one you waste weeks.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.qantas.com/teams/corporate/customer-service/" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Qantas Airport Customer Service Roles &rarr;</a>
</p>

<h2>What Does It Really Pay?</h2>

<p>Ask Qantas and you get this, from its own careers FAQ: it provides all the information you need about salaries during its recruitment process, and customer service employees are employed under enterprise agreements that apply to each employing entity.</p>

<p>That sounds like a dead end. It is actually a map. <strong>Enterprise agreements are public documents published by the Fair Work Commission</strong>, and the one covering Qantas airport customer service is agreement AE531839, approved on 28 January 2026, in force from 4 February 2026 until 30 June 2029.</p>

<p>It says the entry level for positions in airports is Level 3, and new starters sit at Level 3 year one for 18 months. Here is its own salary schedule:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Level</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">From 1 Jul 2026</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">From 1 Jul 2027</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">From 1 Jul 2028</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Level 3.1 (airport entry level)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$61,608</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$63,456</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$65,360</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Level 3.3</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$65,951</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$67,929</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$69,967</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Level 5.1</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$71,499</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$73,644</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$75,854</td>
        </tr>
    </tbody>
</table>

<p>Now the caveat that makes this honest, and that no estimate site will tell you. <strong>Those are full-time annual bases, and Qantas states that all its airport customer service employees work part-time.</strong> The agreement sets part-time work at a minimum of 20 hours a week, a minimum daily engagement of four hours, and a maximum of 30 ordinary hours a week. So do not budget $61,608. Budget that figure pro rata against a full-time week, then add shift loadings, which on this agreement are substantial: time and a half on Saturdays, double time on Sundays, double time and a half on Christmas Day and Good Friday, and up to 27.5 per cent for permanent night shift at Levels 1 to 4.</p>

<p>For context, Australia's National Minimum Wage from 1 July 2026 is $26.44 an hour, or $1,004.90 a week. The Airline Operations Ground Staff Award sets the industry floor, with Level 1 in the aviation transport stream at $27.38 an hour from 1 July 2026. The Qantas agreement pays above that floor, which is the point of having one.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-qantas-ground-staff-jobs-in-australia-terminal.jpg" alt="A Qantas airport customer service agent assisting a traveller at a check-in desk in an Australian terminal" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Qantas states that all its airport customer service employees work part-time on a rostered shift system.</figcaption>
</figure>

<h2>Can I Get This Job on a Visa?</h2>

<p>Almost certainly not, and Qantas says so on the adverts themselves.</p>

<p>A live Qantas Ground Services advert states that applications will only be considered for candidates who have the right to work in Australia or New Zealand <strong>without restrictions or sponsorship</strong>. A Jetstar airport advert lists the requirement as unlimited working rights in Australia, no sponsorship required.</p>

<p>The government side confirms it. We read Australia's <strong>Core Skills Occupation List</strong>, which is the list used for the employer-sponsored Skills in Demand visa. It contains 456 occupations. There is no baggage handler, no airport customer service agent, no ground crew. The only occupation containing the word "handler" in the entire list is Dog Handler or Trainer.</p>

<p>So if you are outside Australia hoping an airline will sponsor you into a ground job, we would rather tell you now: <strong>that route does not exist.</strong> Realistically these roles go to citizens, permanent residents and New Zealand citizens. A student visa's 48-hour-a-fortnight cap is itself a restriction, which is why the adverts word it the way they do.</p>

<h2>The ASIC: What It Costs and How Long It Takes</h2>

<p>Every airport job needs an Aviation Security Identification Card, and this is where timelines go wrong.</p>

<ul>
    <li>It is issued after a background check run by <strong>AusCheck</strong>, part of the Department of Home Affairs.</li>
    <li>The check covers a criminal history check and criminal intelligence assessment by the ACIC, a national security assessment by ASIO, and a right-to-work check through VEVO if you hold a visa.</li>
    <li>A <strong>red ASIC</strong> is the one for airside zones such as the ramp and tarmac.</li>
    <li>The government fee is <strong>$262 for a two-year adult card</strong>, but issuing bodies set their own total charge on top, so the amount you pay will be higher and varies.</li>
    <li>Background checks <strong>take six to eight weeks</strong>, and AusCheck advises applying at least six weeks before you need the card.</li>
</ul>

<p>Two things worth knowing that the adverts do not spell out. <strong>There is no Australian citizenship requirement for an ASIC</strong> &mdash; AusCheck's own identity document list accepts an Australian visa with a foreign passport, and an ImmiCard. The barrier for visa holders is the employer's work-rights rule, not the security card. And on cost: the Qantas agreement says the employer reimburses the application fee, but <strong>it does not reimburse costs incurred before your employment starts, or unsuccessful applications and renewals.</strong></p>

<h2>What Else Do You Need?</h2>

<p>From Qantas's own careers pages and live Qantas Group adverts:</p>

<ul>
    <li><strong>Pre-employment medical and ASIC check</strong>, both of which you must pass. Qantas funds the medical.</li>
    <li><strong>Two professional references.</strong></li>
    <li><strong>Minimum age 18</strong> on Qantas Group airport adverts.</li>
    <li><strong>No aviation experience required</strong> for airport roles, but availability for a seven-day rotating roster is.</li>
    <li>For freight ground crew, the ability to <strong>repetitively lift items up to 32 kg</strong>. Note that figure. Guides quoting 30 kg are copying a page Qantas retired.</li>
</ul>

<p>On benefits, we have to correct three claims that circulate widely. <strong>"Qantas College", Australian Qualifications Framework recognition and an "attractive superannuation scheme" appear nowhere on Qantas's careers site.</strong> What the benefits page does list is staff travel, including heavily discounted standby fares and 25 per cent off confirmed Qantas flights, employee deals and salary packaging, and 18 weeks of primary carer's leave. As for superannuation, it is a legal minimum of <strong>12 per cent</strong> from 1 July 2026, paid by every Australian employer, and from that date it must be paid each payday rather than quarterly. That is the law, not a Qantas perk.</p>

<h2>How Do I Apply, Step by Step?</h2>

<ol>
    <li><strong>Decide which job you mean</strong> using the table above. Customer service means Qantas; ramp loading at the outsourced ports means a contractor.</li>
    <li><strong>Check your work rights first.</strong> If they are not unrestricted, stop here rather than spending weeks on applications.</li>
    <li><strong>Search the customer service team page</strong> on careers.qantas.com and filter by your airport.</li>
    <li><strong>Read the employing entity</strong> on the advert. Qantas Airways, Qantas Ground Services and Jetstar pay under different agreements.</li>
    <li><strong>Apply online</strong> and create an account so you can track applications.</li>
    <li><strong>Line up two professional references</strong> before you are asked for them.</li>
    <li><strong>Budget six to eight weeks for the ASIC</strong> and pass the pre-employment medical.</li>
    <li><strong>Set up job alerts</strong> if nothing is open at your airport, because these roles are advertised in waves.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Does Qantas still employ baggage handlers?</h3>
<p>Not for passenger ramp loading at the ten airports outsourced in 2020. Qantas still employs Baggage Services desk staff, which is a customer service role, and Qantas Ground Services employs freight ground crew.</p>

<h3>How much do Qantas airport staff earn?</h3>
<p>Qantas publishes no figure. Its enterprise agreement, which the Fair Work Commission publishes, sets the airports entry level at Level 3, year one, at $61,608 a year full-time from 1 July 2026.</p>

<h3>Are Qantas airport jobs full-time?</h3>
<p>No. Qantas states all its airport customer service employees work part-time, and the agreement sets 20 hours a week minimum and 30 ordinary hours maximum.</p>

<h3>Does Qantas sponsor visas for ground staff?</h3>
<p>No. Qantas Group adverts require the right to work without restrictions or sponsorship, and none of these occupations appear on the Core Skills Occupation List.</p>

<h3>What is an ASIC and what does it cost?</h3>
<p>An Aviation Security Identification Card, issued after an AusCheck background check. The government fee is $262 for a two-year adult card, plus whatever the issuing body charges on top.</p>

<h3>How long does the ASIC take?</h3>
<p>Six to eight weeks for the background check. AusCheck advises applying at least six weeks before you need the card.</p>

<h3>Can I get an ASIC if I am not an Australian citizen?</h3>
<p>There is no citizenship requirement for the card itself, and AusCheck accepts an Australian visa with a foreign passport as identity. The employer's work rights rule is the real barrier.</p>

<h3>How heavy is the lifting?</h3>
<p>A live Qantas Ground Services freight advert requires the ability to repetitively lift items up to 32 kg. Check the physical requirements on the specific posting.</p>

<h2>People Also Search For</h2>

<h3>Qantas careers airport customer service</h3>
<p>The customer service team page on careers.qantas.com carries the roles, the FAQ and the pre-employment requirements.</p>

<h3>Qantas enterprise agreement pay rates</h3>
<p>Agreement AE531839, published by the Fair Work Commission, in force 4 February 2026 to 30 June 2029, with the full salary schedule inside.</p>

<h3>ASIC card application Australia</h3>
<p>Applied for through an issuing body and background checked by AusCheck. Red for airside zones, grey and white for other areas.</p>

<h3>Airline Operations Ground Staff Award 2020</h3>
<p>Award MA000048, the industry floor. Level 1 in the aviation transport stream is $27.38 an hour from 1 July 2026.</p>

<h3>Australia National Minimum Wage 2026</h3>
<p>$26.44 an hour or $1,004.90 a week from 1 July 2026, after a 4.75 per cent annual wage review increase.</p>

<h3>Core Skills Occupation List Australia</h3>
<p>The 456-occupation list used for the Skills in Demand visa. Airport ground occupations are not on it.</p>

<h3>Qantas Ground Services jobs</h3>
<p>A wholly owned Qantas subsidiary providing ground handling to Qantas Freight, advertising its own ground crew roles.</p>

<h3>Superannuation guarantee rate 2026</h3>
<p>12 per cent from 1 July 2026, payable each payday rather than quarterly.</p>

<h2>More Job Guides</h2>

<p>Working out whether Australia is open to you at all, or comparing airline employers? Start here:</p>

<ul>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; the routes that exist, before you apply to anything.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; which occupations are actually sponsored.</li>
    <li><a href="/blog/how-to-apply-for-bhp-mining-jobs-in-australia">How to Apply for BHP Mining Jobs in Australia</a> &mdash; another Australian employer that keeps its pay quiet.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; where a rotating roster and no experience is enough.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; the same twist, at Abu Dhabi: the airline is not the employer.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Qantas's own newsroom statements and careers pages, the Fair Work Commission's published enterprise agreement AE531839, the Airline Operations Ground Staff Award MA000048, Fair Work Ombudsman minimum wage data, AusCheck guidance on the Aviation Security Identification Card, the Department of Home Affairs Core Skills Occupation List and Australian Taxation Office superannuation rates, checked on 22 September 2026. Agreements are renegotiated, award rates change each July and immigration rules change. Always check the live posting and the official government source before acting.</p>
HTML;
    }
}
