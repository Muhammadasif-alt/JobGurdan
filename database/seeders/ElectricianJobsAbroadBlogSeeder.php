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
 * "Electrician Jobs Abroad with Visa Sponsorship" — the Canada and Australia
 * route for electricians. The pay comparison and the industrial-versus-house
 * wiring guides own earnings; the existing Electrician Jobs in UK guide owns
 * the UK trade itself. This page owns certification order and visa routes.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Eight Indeed and SEEK buttons, five named vacancies with pay quoted out
 *    of job-board listings, and a recruitment-agency listing the draft itself
 *    could not trace to an employer.
 *
 * 2. "Certification is compulsory in Newfoundland and Labrador and Ontario,
 *    and voluntary in British Columbia and most other provinces." That line
 *    belongs to the domestic and rural electrician only. For construction
 *    electricians the federal profile lists nine compulsory provinces, and
 *    British Columbia made construction and industrial electrician
 *    compulsory from 1 December 2022. "Work first, certify later" is the
 *    exception for electricians in Canada, not the rule.
 *
 * 3. An MLTSSL "conflict" that does not exist: Electrician (General) is on
 *    the MLTSSL and the Core Skills Occupation List.
 *
 * 4. A TRA description that skipped the step that matters: licensed trades
 *    on a permanent visa go through OSAP, receive an Offshore Technical
 *    Skills Record, and work on a provisional licence while finishing gap
 *    training. The 482 visa uses a separate TSS skills assessment program.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class ElectricianJobsAbroadBlogSeeder extends Seeder
{
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
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Electrician Jobs Abroad with Visa Sponsorship';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Canada and Australia both want electricians, but neither lets you wire first and certify later. Canada requires certification in ten provinces; Australia issues an offshore skills record, then a provisional licence. Here is the order of steps.',
                'content' => $content,
                'featured_image' => 'blogs/electrician-jobs-abroad-visa-sponsorship.jpg',
                'tags' => 'electrician jobs abroad, electrician visa sponsorship, electrician jobs canada, electrician jobs australia, noc 72200 electrician, anzsco 341111, express entry trades draw, trades recognition australia',
                'meta_title' => 'Electrician Jobs Abroad with Visa Sponsorship 2026',
                'meta_description' => 'Electrician jobs abroad: which Canadian provinces make certification compulsory, the Express Entry trades draws, and the Australian OSAP licence route.',
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

        $canadaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Canadian Electrical Contractors & Industrial Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-electrician-aggregated']
        );

        $canadaLocation = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        Job::updateOrCreate(
            [
                'position' => 'Construction and Industrial Electrician — Canadian Electrical Contractors and Plants',
                'advertiser_id' => $canadaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $canadaLocation->id,
                'description' => $this->canadaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with apprenticeships alternating paid site work and block technical training',
                'language' => 'English',
                // Wages are set by province and apprenticeship level under
                // collective agreements, so no single range describes the group.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::CANADA_APPLY_URL,
                'meta_description' => 'Construction and industrial electrician roles with Canadian contractors and plants. Certification is compulsory in ten provinces, including British Columbia.',
                'seo_keywords' => 'electrician jobs canada, construction electrician, industrial electrician canada, red seal electrician, noc 72200',
            ]
        );

        $australiaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Australian Electrical Contractors & Maintenance Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'australia-electrician-aggregated']
        );

        $australiaLocation = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Licensed Electrician — Australian Electrical Contractors and Maintenance Teams',
                'advertiser_id' => $australiaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $australiaLocation->id,
                'description' => $this->australiaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, including roster-based maintenance and fly-in fly-out roles',
                'language' => 'English',
                // Pay follows the relevant modern award and the state licence
                // held, so no single range applies.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::AUSTRALIA_APPLY_URL,
                'meta_description' => 'Licensed electrician roles with Australian contractors and maintenance teams. Every state licenses electrical work; overseas electricians start on a provisional licence.',
                'seo_keywords' => 'electrician jobs australia, electrical licence australia, anzsco 341111, osap electrician, 482 visa electrician',
            ]
        );
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Electrical contractors, industrial plants and maintenance employers across Canada recruit certified electricians and register apprentices. Construction electricians are NOC 72200 and industrial electricians NOC 72201, both TEER 2.</p>

<h3>Certification is compulsory almost everywhere</h3>
<ul>
    <li><strong>Construction electrician, compulsory:</strong> Newfoundland and Labrador, Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan, Alberta, and British Columbia since 1 December 2022</li>
    <li><strong>Voluntary:</strong> Yukon, the Northwest Territories and Nunavut</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>A provincial Certificate of Qualification, or registration as an apprentice</li>
    <li>Red Seal endorsement, often preferred for work across provinces</li>
    <li>For industrial roles, motor control, PLC and instrumentation experience</li>
</ul>

<p><strong>Note:</strong> certification rules are set by each province, not by JobGader. Confirm the requirement with the provincial apprenticeship authority before applying or relocating.</p>
JOBHTML;
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Electrical contractors, mining and resources operators and facilities maintenance teams across Australia recruit licensed electricians. Electrician (General) is ANZSCO 341111.</p>

<h3>The licence comes first</h3>
<p>Electrical work in every state and territory requires a licence from that jurisdiction's regulator. Overseas-trained electricians on a permanent visa go through Trades Recognition Australia's Offshore Skills Assessment Program, receive an Offshore Technical Skills Record, and then work under a provisional licence while finishing Australian gap training.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>A current electrical licence for the state the work is in, or eligibility for a provisional one</li>
    <li>Certificate III in Electrotechnology Electrician, or the equivalent recognised by TRA</li>
    <li>A White Card for construction sites</li>
    <li>A driver's licence for service and maintenance roles</li>
</ul>

<p><strong>Note:</strong> licence classes and conditions are set by each state and territory regulator, not by JobGader. Confirm the requirement with the regulator before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canada and Australia are the two countries most electricians look at first, and both genuinely list the trade as one they want. What most guides get backwards is the order of steps. Neither country lets an overseas electrician simply arrive, start wiring and sort out the paperwork later. This page sets out what each one requires, in the order it requires it.</p>

<h2>Which Countries Sponsor Electricians</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Occupation code</th>
            <th style="padding:10px;text-align:left;">Where it stands</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">NOC 72200 and 72201, TEER 2</td><td style="padding:10px;">Both on the 2026 Express Entry trade occupations list</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia</strong></td><td style="padding:10px;">ANZSCO 341111</td><td style="padding:10px;">On the MLTSSL and the Core Skills Occupation List</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>United Kingdom</strong></td><td style="padding:10px;">SOC 5241</td><td style="padding:10px;">On the Temporary Shortage List, going rate £38,800</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-abroad-visa-sponsorship-dubai.jpg"
         alt="An electrician in a white hard hat and safety harness working on a distribution board on a rooftop, with an aircraft overhead and a city skyline beside the sea"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Canada: Certification Is Compulsory in Ten Provinces</h2>

<p>The claim repeated in most electrician-abroad guides is that Canada lets you work first and certify afterwards in most provinces. For a construction electrician that is the reverse of the truth.</p>

<p>The federal occupational profile for NOC 72200 says trade certification for construction electricians is <strong>compulsory in Newfoundland and Labrador, Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta</strong>. The same profile still lists British Columbia as voluntary, but that line is out of date: <strong>British Columbia made construction electrician, industrial electrician and powerline technician compulsory trades from 1 December 2022</strong>, with a one-year window for uncertified workers to register as apprentices or challenge the exam.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Construction electrician certification</th>
            <th style="padding:10px;text-align:left;">Jurisdictions</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Compulsory</strong></td><td style="padding:10px;">Newfoundland and Labrador, Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan, Alberta, British Columbia</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Voluntary</strong></td><td style="padding:10px;">Yukon, Northwest Territories, Nunavut</td></tr>
    </tbody>
</table>
</div>

<p>Where the "compulsory only in Newfoundland and Labrador and Ontario" line comes from is a different, much narrower trade: <strong>electrician (domestic and rural)</strong>. Applying it to construction electricians is how the error spreads.</p>

<p>In practice, that means an overseas electrician arriving in any of the ten provinces works as a registered apprentice under supervision, or challenges the provincial certification exam, before working on their own ticket.</p>

<h3>The Red Seal</h3>

<p>The Red Seal endorsement is added to a provincial certificate by passing the interprovincial examination, and it lets the certificate be recognised across Canada. It is optional for immigration. The provincial certificate is the part that can be compulsory.</p>

<h2>The Canadian Immigration Routes</h2>

<h3>Express Entry trades draws</h3>

<p>Electricians (NOC 72200) and industrial electricians (NOC 72201) are both on the <strong>2026 trade occupations list</strong>. IRCC's own round records show the two most recent trades draws:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Date</th>
            <th style="padding:10px;text-align:left;">Invitations</th>
            <th style="padding:10px;text-align:left;">Lowest CRS invited</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">1 October 2026</td><td style="padding:10px;">3,500</td><td style="padding:10px;">476</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">2 April 2026</td><td style="padding:10px;">3,000</td><td style="padding:10px;">477</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">18 September 2025</td><td style="padding:10px;">1,250</td><td style="padding:10px;">505</td></tr>
    </tbody>
</table>
</div>

<p>To be eligible for the category you need at least <strong>12 months of full-time work experience in the past three years</strong> in an eligible trade. The category does not add CRS points. It changes who is invited against which cut-off; you are still ranked on the score you already have.</p>

<h3>The Federal Skilled Trades Program</h3>

<ul>
    <li>At least <strong>two years of full-time work experience, or 3,120 hours</strong>, in a skilled trade within the five years before you apply</li>
    <li><strong>Either</strong> a valid offer of full-time employment for at least one year, <strong>or</strong> a certificate of qualification issued by a Canadian provincial, territorial or federal authority</li>
    <li>Language at CLB 5 for speaking and listening and CLB 4 for reading and writing</li>
    <li>No education requirement</li>
</ul>

<p>For an electrician the certificate route matters, because wherever certification is compulsory it is the same document you need to work anyway. CRS points for a job offer were removed on 25 March 2025, but an offer still establishes FSTP eligibility.</p>

<h3>Provincial nominee programs</h3>

<p>Provinces run their own streams for in-demand trades. A provincial nomination is the only thing that adds 600 CRS points; a category draw adds none.</p>

<h2>Australia: Skills Record, Provisional Licence, Then the Full Licence</h2>

<p>Electrician (General) is <strong>ANZSCO 341111</strong>. It is on the <strong>Medium and Long-term Strategic Skills List</strong>, which opens the points-tested Skilled Independent visa (subclass 189), and on the <strong>Core Skills Occupation List</strong>, which opens the employer-sponsored Skills in Demand visa (subclass 482) and the Employer Nomination Scheme (subclass 186). The "sources disagree on the MLTSSL" warning in many drafts is not a real conflict.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-abroad-visa-sponsorship-sydney.jpg"
         alt="An electrician in a yellow hard hat and safety glasses working on a switchboard on a construction site overlooking Sydney Harbour Bridge and the Opera House"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>The licensed-trade route for permanent visas</h3>

<ol>
    <li><strong>Offshore Skills Assessment Program.</strong> Trades Recognition Australia runs OSAP, and it is compulsory for electricians seeking permanent residence. The assessment is carried out by a TRA-approved registered training organisation.</li>
    <li><strong>Offshore Technical Skills Record.</strong> A successful assessment produces an OTSR. It certifies that you have <em>partly</em> met the technical requirements of the Australian Certificate III, not all of them.</li>
    <li><strong>Provisional licence.</strong> With the OTSR you apply to the state or territory licensing authority for a provisional licence, which lets you work under supervision.</li>
    <li><strong>Gap training and supervised employment.</strong> You complete Australian-context gap training and a period of supervised work to finish the qualification.</li>
    <li><strong>Full licence.</strong> Only then does the state issue the unrestricted electrical licence.</li>
</ol>

<h3>The 482 visa uses a different assessment</h3>

<p>Electricians applying for the Skills in Demand (subclass 482) visa use TRA's <strong>TSS Skills Assessment Program</strong>, not OSAP. Whether a 482 applicant needs one depends on the occupation and the passport held; TRA publishes the list. Either way, the visa does not license you. The state licence is still a separate step.</p>

<h2>Canada or Australia: The Steps Side by Side</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Canada</th>
            <th style="padding:10px;text-align:left;">Australia</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Can you work before certifying?</strong></td><td style="padding:10px;">Only as a registered apprentice, in ten provinces</td><td style="padding:10px;">Only on a provisional licence, under supervision</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who licenses you</strong></td><td style="padding:10px;">The province</td><td style="padding:10px;">The state or territory regulator</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Overseas credential route</strong></td><td style="padding:10px;">Challenge the provincial exam, or register as an apprentice</td><td style="padding:10px;">OSAP, then an OTSR, then gap training</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Main immigration route</strong></td><td style="padding:10px;">Express Entry trades category, FSTP, provincial nomination</td><td style="padding:10px;">Subclass 189, 190, 491, 482 and 186</td></tr>
    </tbody>
</table>
</div>

<h2>Where to Look for the Jobs</h2>

<ul>
    <li><strong>Canada:</strong> the Government of Canada Job Bank lists employers by province, and its postings state whether the employer has a positive LMIA.</li>
    <li><strong>Australia:</strong> Workforce Australia is the government job board. The large electrical contractors and resources operators also post directly on their own careers sites.</li>
    <li><strong>Never pay a recruiter for a job offer.</strong> Neither Canadian nor Australian sponsorship requires a worker to buy a job, and an offer sold for a fee is the commonest scam aimed at tradespeople.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can I work as an electrician in Canada before getting certified?</h3>
<p>Only as a registered apprentice under supervision in the ten provinces where construction electrician certification is compulsory. Yukon, the Northwest Territories and Nunavut are the jurisdictions where it is voluntary.</p>

<h3>Is electrician a compulsory trade in British Columbia?</h3>
<p>Yes. British Columbia made construction electrician, industrial electrician and powerline technician compulsory trades from 1 December 2022, with a transition year to 1 December 2023.</p>

<h3>Are electricians included in Express Entry trades draws?</h3>
<p>Yes. NOC 72200 and 72201 are both on the 2026 list. The 1 October 2026 trades round invited 3,500 candidates with a lowest score of 476.</p>

<h3>Do I need a Red Seal to immigrate to Canada as an electrician?</h3>
<p>No. The Red Seal is an optional endorsement. A provincial certificate of qualification is what can be compulsory, and it also satisfies the certificate option of the Federal Skilled Trades Program.</p>

<h3>Is Electrician (General) on Australia's MLTSSL?</h3>
<p>Yes. ANZSCO 341111 is on the MLTSSL and the Core Skills Occupation List, so the 189, 190, 491, 482 and 186 routes are all open to it.</p>

<h3>What is an Offshore Technical Skills Record?</h3>
<p>The document an overseas electrician receives after a successful OSAP assessment. It shows partial completion of the Australian Certificate III and lets you apply for a provisional licence while you finish gap training.</p>

<h3>Does a 482 visa let me work as an electrician straight away?</h3>
<p>No. The visa gives you the right to work for your sponsor; the state or territory licence is what lets you do electrical work. Plan the licence into your start date.</p>

<h3>Should I pay an agent for an electrician job abroad?</h3>
<p>No. Neither Canadian nor Australian sponsorship requires the worker to pay for a job offer. Treat any request for a fee for an offer as a warning sign.</p>

<h2>People Also Search For</h2>

<h3>Electrician jobs in Canada with visa sponsorship</h3>
<p>Certification is compulsory in ten provinces, so most sponsored electricians start as registered apprentices.</p>

<h3>Electrician jobs in Australia with visa sponsorship</h3>
<p>482 and 186 through an employer, or 189 on the MLTSSL.</p>

<h3>NOC 72200 electrician</h3>
<p>TEER 2, on the 2026 Express Entry trade occupations list.</p>

<h3>ANZSCO 341111 skills assessment</h3>
<p>OSAP for permanent visas, the TSS program for the 482.</p>

<h3>Express Entry trades draw 2026</h3>
<p>3,500 invitations at 476 on 1 October 2026.</p>

<h3>Red Seal electrician</h3>
<p>Optional endorsement that lets a provincial certificate travel across Canada.</p>

<h3>Provisional electrical licence Australia</h3>
<p>Issued on an OTSR so you can work under supervision during gap training.</p>

<h3>BC compulsory trades electrician</h3>
<p>Compulsory since 1 December 2022.</p>

<h2>More Job Guides</h2>

<p>The rest of the electrician cluster, and the routes around it:</p>

<ul>
    <li><a href="/blog/electrician-salary-in-the-uae-uk-and-usa">Electrician Salary in the UAE, UK and USA</a> &mdash; the BLS figures, the JIB rates and why UAE averages mislead.</li>
    <li><a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">Industrial vs House Wiring Electrician: Which Pays More</a> &mdash; what the BLS industry data shows.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the wiring regulations, Part P and CIS.</li>
    <li><a href="/blog/how-to-become-a-licensed-plumber-in-canada-or-australia">How to Become a Licensed Plumber in Canada or Australia</a> &mdash; the same two systems for the plumbing trade.</li>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; another compulsory trade in most provinces, with Job Bank wages.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how LMIA-based sponsorship works alongside Express Entry.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; employer sponsorship and the lists it runs on.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; another Express Entry trade, and its certification rules.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Certification, licensing and immigration rules change; confirm the current position with the provincial apprenticeship authority, the state electrical regulator, canada.ca, tradesrecognitionaustralia.gov.au and immi.homeaffairs.gov.au before applying or relocating.</p>
HTML;
    }
}
