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
 * "How to Apply for BMW Factory Jobs in Germany" — an employer guide built on
 * BMW Group Careers, the IG Metall collective agreement and Make it in
 * Germany, rather than the job-board estimates the draft relied on.
 *
 * Corrections to the draft (checked against bmwgroup.jobs, bmwgroup-werke.com,
 * press.bmwgroup.com, igmetall.de, destatis.de and make-it-in-germany.com,
 * September 2026):
 *
 * 1. The draft's collective agreement figures, "3% on 1 June 2026 and 2.5% in
 *    June 2027", do not appear in the IG Metall source it cites. The actual
 *    agreement covering BMW's Bavarian plants gave 2.0% from 1 April 2025 and
 *    3.1% from 1 April 2026, and runs to 31 October 2026.
 *
 * 2. The draft says the Munich plant has about 7,800 employees from
 *    50 countries. BMW's own plant page says roughly 6,000 employees from
 *    more than 60 nations.
 *
 * 3. The draft says Regensburg builds nine models on one line. BMW's current
 *    plant page does not say that, so the claim is dropped.
 *
 * 4. The draft's StepStone, Indeed and Jooble pay estimates are replaced. BMW
 *    publishes no role pay at all, and says apprentice pay varies by location,
 *    so the guide uses the Destatis manufacturing average of EUR 4,913 a month
 *    instead.
 *
 * 5. The draft says unskilled factory work is "not an easy route" for non-EU
 *    citizens. Both the skilled worker visa and the Opportunity Card gate on a
 *    recognised qualification, so for practical purposes there is no general
 *    route at all. The guide says that plainly.
 *
 * 6. The draft has no application timeline. BMW opens applications for the
 *    following year's apprenticeships on staggered dates by location.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BmwFactoryJobsGermanyBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.bmwgroup.jobs/de/en/job-fields/production.html';

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
        $title = 'How to Apply for BMW Factory Jobs in Germany';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'BMW runs nine German plants and about 30 apprenticeships, all taught in German. It publishes no pay for any role, so this guide uses the IG Metall agreement and Destatis earnings, and answers the unskilled visa question honestly.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-bmw-factory-jobs-in-germany.jpg',
                'tags' => 'bmw jobs germany, bmw factory jobs, bmw ausbildung, german car factory jobs, ig metall pay, opportunity card germany, skilled worker visa germany, munich plant jobs',
                'meta_title' => 'BMW Factory Jobs in Germany: Pay, Visa and How to Apply',
                'meta_description' => 'BMW factory jobs in Germany: the nine plants, apprenticeship routes and German language rules, real collective agreement pay, and the visa truth.',
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
            ['name' => 'BMW Group, German Plants'],
            ['type' => 'Company', 'display_reference' => 'bmw-group-germany']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Germany'],
            ['area' => 'Nationwide', 'country' => 'Germany']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Production Associate and Apprentice, BMW German Plants',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work under the metal and electrical industry collective agreement',
                'language' => 'German',
                // BMW publishes no pay for any role and says apprentice pay
                // varies by location, so the guide uses the collective
                // agreement and Destatis rather than a band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Production, logistics, quality and apprenticeship roles at BMW plants across Germany, with collective agreement pay and German language requirements.',
                'seo_keywords' => 'bmw jobs germany, bmw factory jobs, bmw ausbildung, car factory jobs germany, production jobs germany',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>BMW Group hires for production, logistics, planning and quality roles across its German plants, alongside apprenticeships and dual study programmes for school leavers.</p>

<h3>What the work involves</h3>
<p>Assembly line and body shop work, press shop and paint shop operations, plant logistics, production scheduling and quality assurance, all on shift patterns.</p>

<h3>Common requirements</h3>
<ul>
    <li>Good German, since training, vocational school and the IHK qualification are all in German</li>
    <li>A recognised vocational qualification or degree for skilled and direct-entry roles</li>
    <li>Willingness to work shifts</li>
    <li>The right to work in Germany, or a visa route that fits your qualification</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay is set by the metal and electrical industry collective agreement and by BMW, and visa rules are set by the German authorities &mdash; not by JobGader. Apply directly on BMW Group Careers and never pay an agent for a BMW job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on BMW Group Careers, filter the Production job field by a German plant, and submit online.</strong> BMW runs nine German locations, roughly 30 apprenticeship trades and 14 dual study programmes.</p>

<p>Before you spend time on it, two honest answers. <strong>BMW publishes no pay figure for any role</strong>, so every salary you have read for a BMW factory job is a job board's estimate. And if you are outside the EU without a recognised qualification, there is effectively no visa route into this work at all &mdash; that part deserves a straight answer rather than "it is competitive".</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.bmwgroup.jobs/de/en/job-fields/production.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128295; BMW Production Jobs &rarr;
    </a>
</div>

<h2>Where BMW Builds in Germany</h2>

<p>BMW Group Careers lists nine German locations: <strong>Berlin, Dingolfing, Eisenach, Irlbach-Stra&szlig;kirchen, Landshut, Leipzig, Munich, Regensburg and Wackersdorf</strong>. The work spans production, planning, logistics and quality assurance.</p>

<p>Munich is both the headquarters and a plant, and has produced cars and motorcycles <strong>since 1922</strong>. On BMW's own current plant page it employs <strong>roughly 6,000 people from more than 60 nations</strong>, with the whole process on site from press shop and body shop through paint shop and assembly. Guides quoting 7,800 employees from 50 countries are working from an older figure.</p>

<p>Dingolfing is BMW's largest European plant and the centre of its electric drive production, and Leipzig and Regensburg both run mixed drivetrain lines. If you are flexible about location, that matters: the plants outside Munich are in much cheaper housing markets on the same collective agreement pay.</p>

<h2>The Routes In</h2>

<ul>
    <li><strong>Ausbildung (apprenticeship).</strong> Around 30 trades, two to three and a half years, paid, with vocational school alongside plant work and an IHK qualification at the end. This is the main route for school leavers.</li>
    <li><strong>Dual study programmes.</strong> Fourteen of them, combining a degree with paid practical blocks at a plant.</li>
    <li><strong>Direct entry.</strong> For people who already hold a trade qualification or degree, applied for through the professionals section.</li>
</ul>

<h3>The German requirement is not optional</h3>

<p>BMW's own wording is that <strong>you need good German skills, because training, vocational school and the IHK qualification all take place in German</strong>. Note what it does not say: BMW does not publish a specific CEFR level such as B2, so guides quoting one are adding a number BMW has not set. What it clearly does mean is that the apprenticeship route is closed to you until your German is genuinely working-level.</p>

<h3>Apply a year ahead</h3>

<p>Applications for an intake open in the <strong>summer of the previous year</strong>, on staggered dates by location. For the 2027 intake, Bavarian dual study places opened on 1 June 2026, Leipzig and Eisenach from 3 July, Berlin from 8 July, and Dingolfing, Landshut, Munich and Regensburg from 31 July. Most places open around 1 August, a few in September, and Mannheim on 1 October.</p>

<p>The practical point: if you are reading this in autumn hoping to start the following summer, you are already late for some locations and on time for others. Check the plant you want rather than assuming one date.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bmw-factory-jobs-in-germany-line.jpg" alt="Technician working on a vehicle at a German car plant" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What It Pays, and Why Nobody Can Tell You Exactly</h2>

<p><strong>BMW does not publish pay for any role.</strong> Its own pages say apprentice pay varies by location and point readers to individual plant pages. So the tables you find quoting a single BMW production salary are estimates built from user-submitted job board data.</p>

<p>What can be checked is the framework BMW's Bavarian plants sit inside, the <strong>metal and electrical industry collective agreement</strong> negotiated by IG Metall:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Agreed term</th>
            <th style="padding:10px;text-align:left;">Detail</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Pay rise</td><td style="padding:10px;"><strong>2.0% from 1 April 2025</strong>, then <strong>3.1% from 1 April 2026</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">One-off payment</td><td style="padding:10px;">&euro;600 by February 2025</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Apprentice pay</td><td style="padding:10px;">Up &euro;140 a month from January 2025, plus the 3.1% in April 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Agreement runs to</td><td style="padding:10px;"><strong>31 October 2026</strong>, with the next round opening 7 October 2026</td></tr>
    </tbody>
</table>
</div>

<p>Guides quoting "3% from 1 June 2026 with 2.5% more in June 2027" are citing figures that do not appear in the agreement. Those dates and percentages are wrong, and the next settlement has not been negotiated yet.</p>

<p>For an order of magnitude, the Federal Statistical Office puts average full-time gross earnings in <strong>manufacturing at &euro;4,913 a month</strong> excluding special payments, and German median gross annual earnings at <strong>&euro;54,066</strong>. A collective agreement employer such as BMW sits at the stronger end of manufacturing, but the honest answer to "what does BMW pay" is that you find out in the offer.</p>

<h2>The Visa Answer Most Guides Soften</h2>

<p>EU and EEA citizens need no visa. For everyone else, here is the part that saves wasted months:</p>

<ul>
    <li><strong>Skilled Worker Visa.</strong> Requires a recognised vocational qualification of at least two years or a recognised degree, plus a concrete job offer, and as a rule approval from the Federal Employment Agency. If you are over 45 and entering for the first time, the minimum salary is <strong>&euro;55,770 a year</strong> for 2026. The visa fee is &euro;75 and the residence permit up to &euro;100.</li>
    <li><strong>Opportunity Card (Chancenkarte).</strong> A points-based route that lets you come and look for work. You need at least <strong>six points</strong>, <strong>German at A1 or English at B2</strong> as a minimum, and proof of funds of <strong>&euro;1,091 a month</strong>, which is &euro;13,092 for a year. It allows part-time work while you search and short trial employment with a potential employer.</li>
</ul>

<p><strong>Both routes gate on a recognised qualification.</strong> That means there is no general legal route for an unskilled non-EU worker to take a German factory job, whatever a recruitment agent tells you. The narrow exception is the quota-limited Western Balkans arrangement for six named countries, which is not qualification-based and is heavily oversubscribed.</p>

<p>If your trade qualification was earned outside Germany, start the <strong>recognition process (Anerkennung)</strong> before you apply for anything, through the official recognition portal. A decision normally takes three to four months once your documents are complete, and the visa route depends on it.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bmw-factory-jobs-in-germany-apprentice.jpg" alt="Apprentice learning vehicle assembly in a German training workshop" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>How to Apply</h2>

<ol>
    <li><strong>Search BMW Group Careers</strong> and filter by the Production job field and the plant location you want.</li>
    <li><strong>Read the language line first.</strong> It decides whether the rest of the posting is relevant to you.</li>
    <li><strong>Write a German-style application.</strong> A tabular CV with a photo, dates and a short covering letter is the expected format, not a two-page marketing resume.</li>
    <li><strong>Apply through BMW's own system</strong> rather than a reposting site, so your application reaches the plant.</li>
    <li><strong>Start qualification recognition early</strong> if you trained outside Germany, because the visa depends on it and it takes months.</li>
    <li><strong>Never pay an agent for a BMW job.</strong> Registered temporary employment agencies exist in Germany and are paid by the employer, not by you.</li>
</ol>

<p>For the wider German market, including the minimum wage and how German gross pay converts to take-home, our <a href="/blog/factory-worker-jobs-in-germany">factory worker jobs in Germany guide</a> covers it in detail.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need to speak German to work at BMW in Germany?</h3>
<p>For an apprenticeship, yes. BMW says you need good German because training, vocational school and the IHK qualification are all in German. It does not publish a specific CEFR level.</p>

<h3>What do BMW factory workers earn?</h3>
<p>BMW does not publish pay for any role. Its Bavarian plants sit under the metal and electrical collective agreement, and Destatis puts average manufacturing full-time earnings at &euro;4,913 a month.</p>

<h3>Which German cities does BMW have plants in?</h3>
<p>Berlin, Dingolfing, Eisenach, Irlbach-Stra&szlig;kirchen, Landshut, Leipzig, Munich, Regensburg and Wackersdorf.</p>

<h3>Can a non-EU citizen get an unskilled factory job at BMW?</h3>
<p>Realistically no. Both the skilled worker visa and the Opportunity Card require a recognised vocational qualification or degree, so there is no general route for unskilled work.</p>

<h3>What is the Opportunity Card worth to a factory applicant?</h3>
<p>It lets you job hunt in Germany for up to a year if you score six points, have German at A1 or English at B2, and can show &euro;1,091 a month in funds. It still needs a qualification behind it.</p>

<h3>When do BMW apprenticeship applications open?</h3>
<p>In the summer of the previous year, on staggered dates by location. For the 2027 intake they ran from 1 June 2026 for Bavarian dual study places through to 1 October for Mannheim.</p>

<h3>How long is a BMW apprenticeship?</h3>
<p>German apprenticeships typically run two to three and a half years depending on the trade, paid throughout, with an IHK qualification at the end.</p>

<h3>Does BMW pay more than other German manufacturers?</h3>
<p>BMW is covered by the same metal and electrical collective agreement as its peers, so the framework is common. What differs is the grade you are placed in and plant-level supplements.</p>

<h2>People Also Search For</h2>

<h3>BMW Ausbildung 2027</h3>
<p>Around 30 trades and 14 dual study programmes, with applications opening from June 2026 by location.</p>

<h3>IG Metall pay rise 2026</h3>
<p>3.1% from 1 April 2026, under an agreement running to 31 October 2026.</p>

<h3>Germany manufacturing average salary</h3>
<p>&euro;4,913 a month gross for full-time work, excluding special payments, per Destatis.</p>

<h3>Opportunity Card points requirement</h3>
<p>At least six points, German A1 or English B2, and &euro;1,091 a month in proof of funds.</p>

<h3>Skilled worker visa Germany salary</h3>
<p>&euro;55,770 a year is the 2026 minimum for first-time applicants over 45.</p>

<h3>BMW Munich plant employees</h3>
<p>Roughly 6,000 people from more than 60 nations, producing cars since 1922.</p>

<h3>Anerkennung qualification recognition</h3>
<p>Handled through the official recognition portal, usually decided within three to four months of complete documents.</p>

<h3>BMW Dingolfing jobs</h3>
<p>BMW's largest European plant and the centre of its electric drive production.</p>

<h2>More Job Guides</h2>

<p>Looking at German and European work more widely? These cover it:</p>

<ul>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; the wider market, the minimum wage and what German gross really means.</li>
    <li><a href="/blog/how-to-get-a-transport-job-in-germany">How to Get a Transport Job in Germany</a> &mdash; the driver shortage and the licence route.</li>
    <li><a href="/blog/devops-engineer-jobs-in-germany">DevOps Engineer Jobs in Germany</a> &mdash; the skilled route where English is often enough.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the nearest alternative market and its sponsorship rules.</li>
    <li><a href="/blog/how-to-apply-for-bhp-mining-jobs-in-australia">How to Apply for BHP Mining Jobs in Australia</a> &mdash; another large employer with real no-experience pathways.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; the same question in Italy, where a quota decree decides it.</li>
    <li><a href="/blog/how-to-apply-for-unilever-factory-jobs-in-indonesia">How to Apply for Unilever Factory Jobs in Indonesia</a> &mdash; a factory employer that lists no factory-floor jobs at all.</li>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; and which of the three Siemens companies actually hires you.</li>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; an employer that publishes real salary bands on its adverts.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using BMW Group Careers and BMW plant pages, BMW Group press releases, IG Metall collective agreement terms, Federal Statistical Office earnings data and the official Make it in Germany information on visas. BMW does not publish pay for individual roles, and collective agreements and visa thresholds change. Always check the live posting and the official visa guidance before you commit.</p>
HTML;
    }
}
