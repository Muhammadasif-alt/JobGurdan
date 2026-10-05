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
 * "Scaffolder, Roofer and Crane Operator Jobs Abroad" — three height trades
 * in the three markets the brief chose: scaffolding in the UK, crane work in
 * Canada and roofing in Australia.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. "SOC 8151 Scaffolders" and roofers presented as Skilled Worker
 *    occupations. Since 22 July 2025 below-degree jobs need a Temporary
 *    Shortage List entry, and neither 8151 nor 5313 roofers is on it.
 *
 * 2. A Find a Job vacancy link with a job ID and its £24.57-£39.27 rate,
 *    SEEK roofer pay ($38-$50, $80,000-$120,000), a link to the ScaffJobs
 *    board, and Job Bank vacancy counts (42 jobs, 14 in BC).
 *
 * 3. No mention of the licences that actually gate these trades: the CISRS
 *    card in the UK, compulsory crane certification in Ontario, and the
 *    high risk work licence for scaffolding and cranes in Australia.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class ScaffolderRooferCraneOperatorJobsBlogSeeder extends Seeder
{
    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch';

    private const AUSTRALIA_APPLY_URL = 'https://www.workforceaustralia.gov.au/individuals/jobs/search';

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
        $title = 'Scaffolder, Roofer and Crane Operator Jobs Abroad';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'UK scaffolders earn £25,000 to £51,000 but cannot currently be newly sponsored. Canadian crane operators earn a C$42.77 median under provincial certification, and Australian scaffolding and crane work needs a high risk work licence.',
                'content' => $content,
                'featured_image' => 'blogs/scaffolder-roofer-crane-operator-jobs.jpg',
                'tags' => 'scaffolder jobs uk, crane operator jobs canada, roofer jobs australia, cisrs card, high risk work licence, mobile crane operator 339a, scaffolder visa uk, construction jobs abroad',
                'meta_title' => 'Scaffolder, Roofer and Crane Operator Jobs Abroad',
                'meta_description' => 'Scaffolder jobs in the UK, crane operator jobs in Canada and roofer jobs in Australia: official pay, the licences each trade needs, and the visa reality.',
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

        $listings = [
            [
                'advertiser' => ['UK Scaffolding Contractors (Aggregated)', 'uk-scaffolder-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Scaffolder — UK Scaffolding Contractors (Right to Work Required)',
                'apply' => self::UK_APPLY_URL,
                'hours' => 'Typically 40 to 50 hours a week, outdoors and at height',
                'description' => $this->ukJobDescription(),
                'meta' => 'Scaffolder roles with UK scaffolding contractors. A CISRS card is expected on site; the occupation is not currently open to new Skilled Worker sponsorship.',
                'keywords' => 'scaffolder jobs uk, cisrs scaffolder, part 1 scaffolder, advanced scaffolder, scaffolding jobs',
            ],
            [
                'advertiser' => ['Canadian Crane Rental & Construction Contractors (Aggregated)', 'canada-crane-operator-aggregated'],
                'location' => ['Canada', 'Canada'],
                'position' => 'Crane Operator — Canadian Construction and Crane Rental Contractors',
                'apply' => self::CANADA_APPLY_URL,
                'hours' => 'Full-time, often with early starts and seasonal overtime',
                'description' => $this->canadaJobDescription(),
                'meta' => 'Mobile and tower crane operator roles in Canada (NOC 72500). Certification is compulsory in Ontario; Job Bank puts the median at C$42.77 an hour.',
                'keywords' => 'crane operator jobs canada, mobile crane operator, tower crane operator, noc 72500, red seal crane operator',
            ],
            [
                'advertiser' => ['Australian Roofing Contractors & Builders (Aggregated)', 'australia-roofer-aggregated'],
                'location' => ['Australia', 'Australia'],
                'position' => 'Roofer and Roof Tiler — Australian Roofing Contractors',
                'apply' => self::AUSTRALIA_APPLY_URL,
                'hours' => 'Full-time, outdoors and at height',
                'description' => $this->australiaJobDescription(),
                'meta' => 'Roofer, roof tiler and roof plumber roles with Australian roofing contractors. Working-at-heights training and Australian work rights are expected.',
                'keywords' => 'roofer jobs australia, roof tiler jobs, roof plumber jobs, roofing jobs australia, metal roofer',
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
                    'language' => 'English',
                    // Pay depends on grade, card level and crane class; this
                    // site does not republish single-vacancy or job-board rates.
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

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Scaffolding contractors across the UK recruit trainees, Part 1, Part 2 and Advanced scaffolders for construction, renovation and industrial sites.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>A <strong>CISRS card</strong> (Construction Industry Scaffolders Record Scheme) or equivalent at the right level</li>
    <li>Experience erecting and dismantling tube-and-fitting or system scaffold</li>
    <li>The right to work in the UK. Scaffolders (SOC 8151) are not on the Temporary Shortage List, so new Skilled Worker sponsorship is not currently available for the role</li>
</ul>

<p><strong>Pay:</strong> the National Careers Service gives £25,000 for starters and £51,000 for experienced scaffolders. Individual employers set their own rates.</p>

<p><strong>Note:</strong> visa rules are set by the Home Office, not by JobGader. Check gov.uk before applying from abroad.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Construction contractors, crane rental firms and industrial employers across Canada recruit mobile and tower crane operators under NOC 72500.</p>

<h3>Requirements</h3>
<ul>
    <li>Provincial certification for the crane class. In Ontario, Hoisting Engineer &mdash; Mobile Crane Operator is a compulsory trade, with Branch 1 (339A) covering cranes of any capacity</li>
    <li>Red Seal endorsement, available for mobile and tower crane operators, allows work across provinces</li>
    <li>Logged hours on the crane types you will operate, and rigging and signalling knowledge</li>
</ul>

<p><strong>Pay:</strong> Job Bank reports a national median of C$42.77 an hour for 2023-2024. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and immigration rules are set by provincial regulators and IRCC, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Roofing contractors and builders across Australia recruit roofers, roof tilers and roof plumbers for residential, commercial and industrial work.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>Roofing experience with tiles, metal sheeting or both</li>
    <li>Working-at-heights training and a construction induction (White Card)</li>
    <li>Australian work rights. A vacancy does not by itself include visa sponsorship</li>
</ul>

<p><strong>Note:</strong> licensing for roof plumbing is set by each state, and visa rules by the Department of Home Affairs, not by JobGader. Confirm both before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Scaffolding, roofing and crane work all happen at height, all pay above general labouring, and all are gated by a licence or card that employers check on the first day. They differ sharply on the question most overseas applicants care about: whether the country will let you in to do the job. This page takes each trade in the market the question is usually asked about &mdash; scaffolders in the UK, crane operators in Canada and roofers in Australia &mdash; and answers it from official sources.</p>

<h2>The Three Trades at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Trade and market</th>
            <th style="padding:10px;text-align:left;">Official pay figure</th>
            <th style="padding:10px;text-align:left;">The card or licence</th>
            <th style="padding:10px;text-align:left;">Visa position</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Scaffolder, UK</strong></td><td style="padding:10px;">£25,000 starter to £51,000 experienced</td><td style="padding:10px;">CISRS card</td><td style="padding:10px;">Not on the Temporary Shortage List</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Crane operator, Canada</strong></td><td style="padding:10px;">C$42.77 an hour median</td><td style="padding:10px;">Provincial certification; compulsory in Ontario</td><td style="padding:10px;">Employer offer or immigration programme, plus provincial certification</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Roofer, Australia</strong></td><td style="padding:10px;">Set by award and contract</td><td style="padding:10px;">White Card, working at heights; licences for roof plumbing</td><td style="padding:10px;">Check the occupation lists for your exact ANZSCO code</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/scaffolder-roofer-crane-operator-jobs-lift.jpg"
         alt="A scaffolder in a harness climbing a tube scaffold, two roofers fixing metal roof sheets and a crane operator in the cab lifting a pallet of blocks over a city construction site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Scaffolder Jobs in the UK</h2>

<h3>Pay and hours</h3>

<p>The National Careers Service puts scaffolder pay at about <strong>£25,000 for starters and £51,000 for experienced scaffolders</strong>, on <strong>40 to 50 hours a week</strong>. Advanced scaffolders and supervisors sit at the top of that range.</p>

<h3>The CISRS card</h3>

<p>To work on a UK construction site you need a <strong>Construction Industry Scaffolders Record Scheme (CISRS)</strong> card or equivalent. The usual progression is:</p>

<p><strong>Labourer or trainee &rarr; Part 1 scaffolder &rarr; Part 2 scaffolder &rarr; Advanced scaffolder &rarr; supervisor or inspector</strong></p>

<p>The <strong>Scaffolder Level 2 Intermediate Apprenticeship</strong> takes up to two years and combines site work with training-provider study. College courses, trainee roles and on-the-job training are the other routes in.</p>

<h3>Can an overseas scaffolder be sponsored?</h3>

<p><strong>Not at present.</strong> Since 22 July 2025, Skilled Worker sponsorship for new applicants in jobs below degree level is only available for occupations on the <strong>Temporary Shortage List</strong>. Scaffolders, stagers and riggers (SOC 8151) are not on it, and neither are roofers (SOC 5313). Pages describing scaffolding as an "eligible occupation" are working from the rules before that date.</p>

<p>The construction occupations that are on the list include steel erectors, plumbers, floorers and wall tilers, painters and decorators, and construction and building trades supervisors (SOC 5330, at a going rate of £41,800). An experienced scaffolding supervisor should check whether the job genuinely fits the supervisor code rather than relabelling a scaffolder role.</p>

<h2>Crane Operator Jobs in Canada</h2>

<h3>Pay</h3>

<p>Job Bank reports hourly wages for crane operators (NOC 72500), based on 2023 to 2024 data and updated on 19 November 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Median hourly wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong> (low C$26.50, high C$52.19)</td><td style="padding:10px;"><strong>C$42.77</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">C$45.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">C$44.31</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">C$43.00</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">C$43.01</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">C$41.20</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">C$40.48</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Newfoundland and Labrador</td><td style="padding:10px;">C$39.63</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New Brunswick</td><td style="padding:10px;">C$35.00</td></tr>
    </tbody>
</table>
</div>

<p>Job Bank also reports that almost 95 per cent of crane operators receive at least one non-wage benefit, such as a pension or insurance.</p>

<h3>Certification</h3>

<ul>
    <li><strong>It is provincial.</strong> Each province sets its own certification for crane operators, by crane type and capacity.</li>
    <li><strong>Ontario makes it compulsory.</strong> Hoisting Engineer &mdash; Mobile Crane Operator Branch 1 (339A) covers mobile cranes of any capacity and is a Red Seal trade; Branch 2 (339C) covers smaller cranes up to 15 tonnes. Ontario residents must register as apprentices before training.</li>
    <li><strong>Red Seal travels.</strong> Mobile and tower crane operators can earn the interprovincial Red Seal endorsement, which lets them work across provinces.</li>
</ul>

<p>A foreign operator usually needs the province to assess their logged hours and experience before they can sit the certification exam, as well as an employer offer or an immigration programme for the work permit. Vacancy counts on job sites change daily and say nothing about whether you can be certified, so start with the provincial authority.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/scaffolder-roofer-crane-operator-jobs-steel.jpg"
         alt="A crane lifting a steel beam while a banksman in a high-visibility vest signals, with scaffolders on a tube scaffold and roofers fixing sheets on a tall building site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Roofer Jobs in Australia</h2>

<h3>The trade titles</h3>

<ul>
    <li><strong>Roof tilers</strong> lay tiles, sheets and shingles to make a roof weatherproof.</li>
    <li><strong>Roof plumbers</strong> install metal roofing, gutters and flashings. Roof plumbing is a licensed trade in several states.</li>
    <li><strong>Roofing labourers</strong> support both, and are the usual entry point.</li>
</ul>

<h3>What you need on site</h3>

<ul>
    <li>A <strong>White Card</strong>, the general construction induction required on Australian building sites</li>
    <li><strong>Working-at-heights training</strong>, which most roofing employers ask about before interview</li>
    <li>For roof plumbing, the state licence or registration where it applies</li>
</ul>

<h3>Scaffolding and crane work in Australia need a licence too</h3>

<p>If you move between these trades, note that in Australia both are <strong>high risk work</strong>. Erecting scaffolding needs a high risk work licence at basic, intermediate or advanced level, and operating most cranes needs a licence for that crane class. These are issued by the state work health and safety regulator after assessment, and an overseas qualification does not transfer automatically.</p>

<h3>Visa position</h3>

<p>Australian vacancies do not include sponsorship by default. Check your exact ANZSCO occupation against the current skilled occupation lists on the Department of Home Affairs website, and whether the employer is an approved sponsor, before treating any roofing advert as a visa route.</p>

<h2>Which Trade Should You Target?</h2>

<ul>
    <li><strong>Crane operation</strong> has the clearest official pay and a certification path that, once earned, travels across Canada.</li>
    <li><strong>Scaffolding</strong> has a well-defined UK card ladder, but the UK is closed to new sponsorship for the trade, so it suits people who already have the right to work there.</li>
    <li><strong>Roofing</strong> is easiest to enter at labourer level, with the licensed roof plumbing route paying more.</li>
</ul>

<p>In every case the deciding factor is the trade where you can already prove hours and hold the card, not the country with the most adverts.</p>

<h2>Avoid Fake Overseas Offers</h2>

<ol>
    <li>Find the vacancy on the employer's own careers page or an official government job portal.</li>
    <li>Never pay a recruiter for a job offer or a visa.</li>
    <li>Ask which occupation code the job will be sponsored under, and check it yourself.</li>
    <li>Never submit a card or certificate you do not hold. Site cards are checked against the issuing scheme.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much does a scaffolder earn in the UK?</h3>
<p>The National Careers Service gives about £25,000 for starters and £51,000 for experienced scaffolders, on 40 to 50 hours a week.</p>

<h3>Can I get a UK Skilled Worker visa as a scaffolder?</h3>
<p>Not as a new applicant at present. Since 22 July 2025, jobs below degree level need a Temporary Shortage List entry, and scaffolders (SOC 8151) are not on it.</p>

<h3>What is a CISRS card?</h3>
<p>The Construction Industry Scaffolders Record Scheme card shows your scaffolding level, from trainee through Part 1, Part 2 and Advanced. UK sites expect it or an equivalent.</p>

<h3>How much do crane operators earn in Canada?</h3>
<p>Job Bank reports a national median of C$42.77 an hour, from C$26.50 to C$52.19. Alberta has the highest provincial median at C$45.00.</p>

<h3>Do crane operators need a licence in Canada?</h3>
<p>Yes, under provincial rules. In Ontario, Hoisting Engineer &mdash; Mobile Crane Operator is a compulsory trade, and Branch 1 (339A) is a Red Seal trade.</p>

<h3>Are roofers on the UK shortage list?</h3>
<p>No. Roofers, roof tilers and slaters (SOC 5313) are not on the Temporary Shortage List.</p>

<h3>What do I need to work as a roofer in Australia?</h3>
<p>A White Card, working-at-heights training and Australian work rights. Roof plumbing also needs a state licence where it applies.</p>

<h3>Do I need a licence to put up scaffolding in Australia?</h3>
<p>Yes. Scaffolding is high risk work and needs a basic, intermediate or advanced high risk work licence from the state regulator.</p>

<h2>People Also Search For</h2>

<h3>Scaffolder jobs UK</h3>
<p>£25,000 to £51,000 on the National Careers Service, with a CISRS card.</p>

<h3>Crane operator jobs Canada</h3>
<p>C$42.77 an hour median under NOC 72500.</p>

<h3>Roofer jobs Australia</h3>
<p>White Card and working-at-heights training as the minimum.</p>

<h3>CISRS Part 1 and Part 2</h3>
<p>The first two scaffolder levels on the UK card scheme.</p>

<h3>Mobile crane operator 339A</h3>
<p>Ontario's compulsory Branch 1 trade, any crane capacity, Red Seal.</p>

<h3>High risk work licence</h3>
<p>Required in Australia for scaffolding and most crane classes.</p>

<h3>Scaffolder visa UK</h3>
<p>Not open to new Skilled Worker applicants since 22 July 2025.</p>

<h3>Construction supervisor shortage list</h3>
<p>SOC 5330 is on the list at a £41,800 going rate.</p>

<h2>More Job Guides</h2>

<p>The rest of the construction trades guides:</p>

<ul>
    <li><a href="/blog/how-to-become-a-site-supervisor-or-foreman-abroad">How to Become a Site Supervisor or Foreman Abroad</a> &mdash; the step from trade to supervision.</li>
    <li><a href="/blog/tiler-plasterer-and-mason-jobs-overseas">Tiler, Plasterer and Mason Jobs Overseas</a> &mdash; the finishing trades and their shortage-list status.</li>
    <li><a href="/blog/carpenter-jobs-in-the-uk-canada-and-australia">Carpenter Jobs in the UK, Canada and Australia</a> &mdash; another trade the UK no longer newly sponsors.</li>
    <li><a href="/blog/construction-labourer-jobs-in-dubai-and-saudi-arabia">Construction Labourer Jobs in Dubai and Saudi Arabia</a> &mdash; Gulf site work and its legal protections.</li>
    <li><a href="/blog/welder-salary-in-usa">Welder Salary in USA</a> &mdash; another structural trade, on BLS data.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UK pay is from the National Careers Service and visa rules from GOV.UK; Canadian wages are from Job Bank. Confirm licensing with the provincial or state regulator before applying.</p>
HTML;
    }
}
