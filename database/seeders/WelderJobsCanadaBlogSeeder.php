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
 * "Welder Jobs in Canada" — structural, pipeline, manufacturing and shipyard
 * welding. The farm worker guide covers seasonal agricultural routes into
 * Canada, and this one owns welder wages by province, trade certification,
 * CWB and pressure tickets, and the Express Entry and LMIA rules for trades.
 *
 * Corrections to the draft:
 *
 * 1. It presents Red Seal as the licence welders need. Welding is compulsory
 *    certification only in Alberta and Quebec; in Ontario, British Columbia,
 *    Saskatchewan and Nova Scotia it is voluntary. What employers check
 *    first is often a CWB ticket, which is tied to a certified company.
 *
 * 2. Its demand claims contradict Job Bank's 2025-2027 outlook, which rates
 *    Alberta, British Columbia and Quebec "Limited" and Ontario "Very
 *    limited". Only Nova Scotia is "Good".
 *
 * 3. Its pay bands put certified welders at $28 to $38 an hour. That is the
 *    spread between provinces, not experience: the Job Bank median is $28 in
 *    Ontario and Quebec and $38 in Alberta.
 *
 * 4. Its visa section says listings offer LMIA support. At median wages most
 *    welder LMIAs fall in the low-wage stream, which cannot be processed in
 *    Toronto, Calgary, Edmonton or Vancouver while unemployment is 6% or more
 *    (construction is exempt), and job offers no longer earn Express Entry
 *    points since 25 March 2025.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WelderJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-welder-jobs.html';

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
        $title = 'Welder Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Red Seal is voluntary for welders in most provinces, Job Bank rates the outlook as limited in Alberta and very limited in Ontario, median pay runs from $28 to $38 an hour by province, and most welder LMIAs fall in the low-wage stream.',
                'content' => $content,
                'featured_image' => 'blogs/welder-jobs-in-canada.jpg',
                'tags' => 'welder jobs canada, welder salary canada, red seal welder, cwb ticket, pressure welder alberta, welder lmia, federal skilled trades program, express entry trades draw, welder jobs nova scotia',
                'meta_title' => 'Welder Jobs in Canada 2026: Pay, Red Seal and LMIA Rules',
                'meta_description' => 'Welder jobs in Canada: Job Bank pay by province, where Red Seal is compulsory, CWB tickets, the 2025-2027 outlook, and the LMIA and Express Entry rules.',
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
            ['name' => 'Canadian Fabricators, Shipyards & Energy Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-welder-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Welder — Structural, Pipeline, Manufacturing and Shipyard Work, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Day and shift work in shops and plants; rotations and camp schedules on pipeline and industrial projects',
                'language' => 'English, French',
                // Welder pay differs by province by up to $10 an hour at the
                // median, so no single national range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Welding roles in Canadian fabrication shops, shipyards, pipelines and plants. Trade certification and a CWB ticket often required.',
                'seo_keywords' => 'welder jobs canada, red seal welder jobs, pipeline welder jobs, structural welder jobs, shipyard welder jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Steel fabricators, manufacturers, shipyards, pipeline contractors and industrial plants across Canada hire welders, from apprentices to certified journeypersons.</p>

<h3>What the work involves</h3>
<p>Reading drawings and weld symbols, preparing and fitting joints, welding with MIG, TIG, stick and flux-core processes, and inspecting welds to the code the job is built to.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>Trade certification where the province requires it</strong> &mdash; compulsory in Alberta and Quebec, voluntary in Ontario, BC, Saskatchewan and Nova Scotia</li>
    <li>A CWB welder qualification for work under CSA W47.1, and an ABSA pressure welder certificate for pressure piping in Alberta</li>
    <li>The right to work in Canada, or an employer able to obtain an LMIA for the position</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank medians.</strong> $38 an hour in Alberta, $35 in BC, $32 in Saskatchewan, $29 in Nova Scotia and $28 in Ontario and Quebec</li>
    <li><strong>Apprentices in Alberta.</strong> At least 60%, 75% and 90% of the lowest-paid journeyperson's rate across the three periods</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for a job offer or an LMIA.</strong> The employer pays the $1,000 LMIA fee, and it cannot be recovered from the worker.</p>

<p><strong>Note:</strong> wages, certification rules and immigration eligibility are set by employers, provincial apprenticeship authorities and the Government of Canada &mdash; not by JobGader. Confirm the details with the employer and on canada.ca before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Welders build Canada's bridges, pipelines, ships and factory equipment, and it is a trade with a clear path from apprentice to journeyperson. Before you apply, especially from abroad, it helps to know four things most guides get wrong: where Red Seal is actually required, what welders earn in each province, where the jobs really are, and how narrow the LMIA and Express Entry routes have become.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-welder-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128293; Browse Welder Jobs in Canada &rarr;
    </a>
</div>

<h2>Red Seal Is Voluntary in Most Provinces</h2>

<p>Guides describe welding as a licensed trade and Red Seal as the standard you need. Welder is a <strong>Red Seal trade</strong> in every province and territory, but whether certification is <strong>compulsory</strong> is a provincial decision. The Red Seal program's Ellis Chart lists welding as:</p>

<ul>
    <li><strong>Compulsory</strong> in <strong>Alberta</strong> and <strong>Quebec</strong></li>
    <li><strong>Voluntary</strong> in <strong>Ontario</strong>, <strong>British Columbia</strong>, <strong>Saskatchewan</strong> and <strong>Nova Scotia</strong></li>
</ul>

<p>In a voluntary province you can work as a welder without a certificate, although many employers still ask for one. In Alberta you must be a registered apprentice or a certified journeyperson to do the work. The Red Seal exam has 125 questions and a <strong>pass mark of 70%</strong>.</p>

<h3>Apprenticeship and experienced-worker routes</h3>

<ul>
    <li><strong>Alberta:</strong> three periods, each with <strong>1,560 hours</strong> of work and 8 weeks of classroom training. Apprentices must be paid at least <strong>60%, 75% and 90%</strong> of the lowest-paid journeyperson welder's rate in periods one to three.</li>
    <li><strong>Ontario (456A):</strong> <strong>6,000 hours</strong>, about three years, including 720 hours in school.</li>
    <li><strong>British Columbia:</strong> three levels of technical training and 4,620 hours of work-based training.</li>
    <li><strong>Experienced welders without an apprenticeship</strong> can challenge the exam. Alberta's Trades Qualifier route needs 54 months and 7,020 hours of verifiable welding experience, and Ontario's Trade Equivalency Assessment costs $235 plus HST.</li>
</ul>

<h2>The Tickets Employers Check First</h2>

<ul>
    <li><strong>CWB tickets.</strong> Welders on certain buildings, bridges and products must be qualified by the Canadian Welding Bureau and work for a company certified to CSA W47.1 or related standards. A ticket is <strong>only valid while you work for a CWB-certified company</strong>, most last <strong>two years</strong> with continuous employment, and a transferable ticket lapses if you are not rehired by a certified company within three months.</li>
    <li><strong>Pressure welding in Alberta.</strong> No one may weld a boiler, pressure vessel or pressure piping unless they hold an ABSA Pressure Welder Certificate of Competency and a valid performance qualification card. The usual certificate for welders is <strong>Grade B</strong>, which needs journeyperson or Red Seal status.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/welder-jobs-in-canada-pipeline.jpg"
         alt="A welder in a helmet welding a large steel pipe at a Canadian worksite with the Toronto skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Welders Earn by Province</h2>

<p>Guides put certified welders at $28 to $38 an hour and pipeline welders at $38 to $50 or more. Job Bank's wage data for <strong>welders and related machine operators (NOC 72106)</strong>, covering 2023-2024, shows the spread is mostly between provinces:</p>

<ul>
    <li><strong>Alberta:</strong> median <strong>$38.00</strong> an hour, from $25.00 to $52.18</li>
    <li><strong>British Columbia:</strong> median $35.00, from $23.25 to $51.87</li>
    <li><strong>Saskatchewan:</strong> median $32.00, from $22.00 to $46.00</li>
    <li><strong>Nova Scotia:</strong> median $29.00, from $21.00 to $43.00</li>
    <li><strong>Ontario:</strong> median <strong>$28.00</strong>, from $21.00 to $41.28</li>
    <li><strong>Quebec:</strong> median $28.00, from $22.00 to $35.38</li>
    <li><strong>Canada:</strong> median $30.00, from $22.00 to $47.00</li>
</ul>

<p>So a typical Ontario welder earns at the bottom of the guides' certified range, and the $50-an-hour pipeline figure sits near the top of Alberta's recorded wages, not in the middle. Pipeline and camp jobs can also mean long rotations away from home, so compare the hours as well as the rate.</p>

<h2>Where the Jobs Are: Job Bank's Outlook</h2>

<p>Guides name Alberta and Ontario as strong markets. Job Bank's official <strong>2025-2027 employment outlook</strong> for welders says otherwise:</p>

<ul>
    <li><strong>Nova Scotia:</strong> <strong>Good</strong></li>
    <li><strong>Saskatchewan:</strong> Moderate</li>
    <li><strong>Alberta:</strong> <strong>Limited</strong></li>
    <li><strong>British Columbia:</strong> Limited</li>
    <li><strong>Quebec:</strong> Limited</li>
    <li><strong>Ontario:</strong> <strong>Very limited</strong></li>
</ul>

<p>Alberta still pays best, but more experienced welders are competing for fewer openings there. Shipbuilding is real, too: the National Shipbuilding Strategy's three partner yards are <strong>Irving Shipbuilding in Halifax</strong>, <strong>Seaspan in Vancouver</strong> and <strong>Chantier Davie in L&eacute;vis, Quebec</strong> &mdash; which helps explain Nova Scotia's better rating.</p>

<h2>Coming From Abroad: The LMIA Is Harder Than It Looks</h2>

<p>Guides say many listings include LMIA support. Most Canadian employers need a <strong>Labour Market Impact Assessment</strong> to hire a foreign worker, and the stream depends on the wage against the provincial threshold. For LMIAs received from <strong>17 July 2026</strong>, the high-wage thresholds are:</p>

<ul>
    <li><strong>Alberta:</strong> $37.50 an hour</li>
    <li><strong>British Columbia:</strong> $38.40</li>
    <li><strong>Ontario:</strong> $36.92</li>
    <li><strong>Quebec:</strong> $36.00</li>
    <li><strong>Saskatchewan:</strong> $34.62</li>
    <li><strong>Nova Scotia:</strong> $31.96</li>
</ul>

<p>Compare those with the welder medians above: outside Alberta, a welder paid the median wage falls into the <strong>low-wage stream</strong>. That stream has two hard limits:</p>

<ul>
    <li><strong>No processing in high-unemployment cities.</strong> Low-wage LMIAs are refused in census metropolitan areas with unemployment of 6% or more. For applications from 10 July to 8 October 2026 that includes <strong>Toronto (7.3%)</strong>, <strong>Calgary (7.0%)</strong>, <strong>Edmonton (7.2%)</strong> and <strong>Vancouver (6.7%)</strong>. Positions in construction are exempt.</li>
    <li><strong>A 10% cap</strong> on the share of low-wage foreign workers at a worksite, or 20% in construction, food manufacturing, hospitals and nursing facilities.</li>
</ul>

<p>The employer pays a <strong>$1,000 LMIA fee</strong> for each position, and it cannot be paid by or recovered from the worker. As IRCC puts it, you should not have to pay for a job offer.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/welder-jobs-in-canada-structural.jpg"
         alt="A welder joining steel beams on a structural job with the Canadian flag and Toronto skyline in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Permanent Residence for Welders</h2>

<ul>
    <li><strong>Federal Skilled Trades Program.</strong> You need at least <strong>two years</strong> of full-time experience (3,120 hours) in the trade within the last five years, language scores of <strong>CLB 5</strong> for speaking and listening and <strong>CLB 4</strong> for reading and writing, and either a job offer of at least a year or a Canadian certificate of qualification. Welders (72106) are eligible.</li>
    <li><strong>The trades draw.</strong> Welders are on the Express Entry trade occupations category, which needs 12 months of full-time experience in the last three years. The latest trades draw was on <strong>2 April 2026</strong>, with 3,000 invitations and a minimum score of <strong>477</strong>, and there had been no trades draw since by early September.</li>
    <li><strong>No points for a job offer.</strong> Since <strong>25 March 2025</strong>, a job offer no longer adds points to your Express Entry score.</li>
    <li><strong>Provincial routes.</strong> British Columbia's PNP names welders on its construction trades list, but you need a SkilledTradesBC certificate or registered apprenticeship. Alberta's Opportunity Stream accepts most occupations, and a compulsory trade like welding needs an Alberta trade certificate.</li>
</ul>

<p>Canada is also admitting fewer newcomers: the 2026 levels plan targets <strong>380,000</strong> new permanent residents and 60,000 arrivals through the Temporary Foreign Worker Program.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need Red Seal certification to work as a welder in Canada?</h3>
<p>Only where certification is compulsory, which the Red Seal program lists as Alberta and Quebec. In Ontario, BC, Saskatchewan and Nova Scotia it is voluntary, though many employers prefer it.</p>

<h3>How much do welders earn in Canada?</h3>
<p>Job Bank's median is $30 an hour nationally, from $28 in Ontario and Quebec to $38 in Alberta. The highest recorded wages reach $52.18 in Alberta and $51.87 in BC.</p>

<h3>Which province is best for welder jobs?</h3>
<p>Job Bank rates Nova Scotia's 2025-2027 outlook as Good and Saskatchewan's as Moderate. Alberta pays the most but its outlook is Limited, and Ontario's is Very limited.</p>

<h3>What is a CWB ticket?</h3>
<p>A Canadian Welding Bureau qualification for work under CSA W47.1 and related standards. It is only valid while you work for a CWB-certified company, and most last two years with continuous employment.</p>

<h3>Can foreign welders get an LMIA job in Canada?</h3>
<p>Yes, but at median wages most welder positions outside Alberta fall in the low-wage stream, which cannot be processed in Toronto, Calgary, Edmonton or Vancouver while unemployment there is 6% or more, unless the job is in construction.</p>

<h3>Do welders qualify for Express Entry?</h3>
<p>Yes, through the Federal Skilled Trades Program and the trade occupations category. The latest trades draw, on 2 April 2026, had a minimum score of 477, and job offers no longer add points.</p>

<h3>How long is a welding apprenticeship in Canada?</h3>
<p>About three years. Alberta has three periods of 1,560 hours each, and Ontario's program is 6,000 hours including 720 hours of school.</p>

<h3>Do I need a special certificate for pressure welding in Alberta?</h3>
<p>Yes. You must hold an ABSA Pressure Welder Certificate of Competency, usually Grade B, and a valid performance qualification card.</p>

<h2>People Also Search For</h2>

<h3>Welder salary Alberta</h3>
<p>A Job Bank median of $38 an hour, the highest in Canada, with an outlook rated Limited for 2025-2027.</p>

<h3>Welder jobs Nova Scotia</h3>
<p>The only one of the main provinces with a Good outlook, near Irving Shipbuilding's Halifax yard.</p>

<h3>Red Seal welder exam</h3>
<p>125 questions with a pass mark of 70%.</p>

<h3>CWB ticket validity</h3>
<p>Usually two years, and only while you work for a CWB-certified company.</p>

<h3>Welder LMIA jobs</h3>
<p>Mostly in the low-wage stream at median pay, with a 10% cap and no processing in high-unemployment cities.</p>

<h3>Express Entry trades draw</h3>
<p>The latest, on 2 April 2026, invited 3,000 candidates with a minimum score of 477.</p>

<h3>Pipeline welder jobs Canada</h3>
<p>Alberta pays up to $52.18 an hour, often on rotations, and pressure work needs an ABSA certificate.</p>

<h3>Welding apprenticeship Ontario</h3>
<p>Trade 456A, 6,000 hours, with voluntary certification.</p>

<h2>More Job Guides</h2>

<p>Comparing skilled trades and routes into Canada? These cover it:</p>

<ul>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the seasonal agricultural route, and how its LMIA works.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; a Canadian job where bilingual skills set the pay.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; site work in another country with a trade skills route.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a qualified trade with a real certification barrier.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; a licensed trade, and what the licence takes.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; trades sponsorship across the border.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, trade certification rules, LMIA thresholds and Express Entry draws change often. Confirm the current position with the employer, the provincial apprenticeship authority, Job Bank and IRCC, or a licensed immigration consultant, before applying.</p>
HTML;
    }
}
