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
 * "How to Become a Site Supervisor or Foreman Abroad" — the top rung of the
 * construction cluster: foreman versus supervisor, the progression ladder,
 * the skills employers test, and the safety qualifications that matter.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Vacancy counts from job boards ("600+", "148") and links to them.
 *
 * 2. Named vacancies from individual employers, including a job-specific
 *    URL with a 30 October 2026 deadline. Vacancy links expire and are not
 *    published; employers can be named in prose only.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class SiteSupervisorForemanAbroadBlogSeeder extends Seeder
{
    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

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
                'description' => 'Practical guidance on building your career, from job search to promotion.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Become a Site Supervisor or Foreman Abroad';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The difference between a foreman and a site supervisor, the route up from the tools, the drawing and safety skills employers test, and which certificates (NEBOSH, IOSH, OSHA) help you land the role abroad.',
                'content' => $content,
                'featured_image' => 'blogs/site-supervisor-foreman-abroad.jpg',
                'tags' => 'site supervisor jobs abroad, foreman jobs gulf, construction foreman dubai, site supervisor uae, nebosh igc, iosh managing safely, toolbox talks, construction career progression',
                'meta_title' => 'How to Become a Site Supervisor or Foreman Abroad',
                'meta_description' => 'Foreman vs site supervisor, the route up from the tools, the drawing and safety skills employers test, and which certificates help you get hired abroad.',
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

        $uaeAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UAE Building, Civil & MEP Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-site-supervisor-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Site Supervisor and Foreman — UAE Building, Civil and MEP Contractors',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time site hours; outdoor work stops from 12:30pm to 3pm between 15 June and 15 September',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'Site supervisor and foreman roles with UAE building, civil and MEP contractors, recruited on MOHRE work permits.',
                'seo_keywords' => 'site supervisor jobs uae, foreman jobs dubai, construction supervisor gulf, mep foreman uae, mohre work permit',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Building, civil and MEP contractors across the UAE recruit foremen and site supervisors from overseas on MOHRE work permits.</p>

<h3>What the work involves</h3>
<ul>
    <li>Running crews of tradesmen and labourers to a daily programme</li>
    <li>Reading drawings and checking work against them</li>
    <li>Toolbox talks, permits to work and daily site reports</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>Employers often ask for 5 to 10 years of site experience, including time leading a crew</li>
    <li>A trade or technical qualification; a diploma in civil or building works helps</li>
    <li>A recognised safety certificate is often preferred</li>
    <li>Working English for reports and coordination</li>
</ul>

<h3>What the law says</h3>
<ul>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021, a MOHRE work permit is compulsory and the employer may not charge the worker recruitment and employment costs</li>
    <li>The UAE sets <strong>no statutory minimum wage</strong> for private-sector expatriate workers. The contract is the only binding figure</li>
</ul>

<p><strong>Never pay anyone for a UAE job.</strong> Visa, wage and permit rules are set by MOHRE, not by JobGader; confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Foreman and site supervisor jobs are where an experienced tradesman stops being paid for his hands and starts being paid for his judgement. They are also the roles Gulf and international contractors find hardest to fill from local labour, which is why they recruit for them abroad. This guide explains the difference between the two jobs, the route up to them, what employers test you on, and which certificates are worth the money.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/site-supervisor-foreman-abroad-blockwork.jpg"
         alt="A site supervisor in a white hard hat and yellow high-visibility vest holding rolled drawings and pointing across a blockwork site while masons lay concrete blocks, with tower cranes and the Dubai skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Foreman or Site Supervisor: What Is the Difference?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Foreman</th>
            <th style="padding:10px;text-align:left;">Site supervisor</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Leads</strong></td><td style="padding:10px;">One trade crew: masons, steel fixers, shuttering, MEP</td><td style="padding:10px;">Several crews or a whole section of the site</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Reports to</strong></td><td style="padding:10px;">Site supervisor or site engineer</td><td style="padding:10px;">Site engineer, construction manager or project manager</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Day to day</strong></td><td style="padding:10px;">Allocating work, checking quality, still on the tools at times</td><td style="padding:10px;">Programme, coordination between trades, inspections and reporting</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Typical background</strong></td><td style="padding:10px;">Skilled tradesman, then charge hand</td><td style="padding:10px;">Foreman, or a diploma holder with site experience</td></tr>
    </tbody>
</table>
</div>

<p>Job titles vary between companies and countries. In the Gulf, "general foreman" and "site supervisor" overlap; in the UK, "site supervisor" often means the person running the site day to day for a smaller contractor. Read the duties, not the title.</p>

<h2>The Ladder Up</h2>

<ol>
    <li><strong>Labourer or helper</strong>: learn the site and attach yourself to one trade.</li>
    <li><strong>Skilled tradesman</strong>: mason, steel fixer, carpenter, electrician, plumber.</li>
    <li><strong>Charge hand</strong>: the senior tradesman who leads a small gang on the tools.</li>
    <li><strong>Foreman</strong>: responsible for a crew's output, quality and safety.</li>
    <li><strong>General foreman or site supervisor</strong>: responsible for several crews and their coordination.</li>
    <li><strong>Site manager or construction manager</strong>: usually needs further qualifications and management experience.</li>
</ol>

<p>Employers recruiting supervisors from abroad often ask for 5 to 10 years of site experience, with part of it spent leading people. The quickest way to qualify is to take every chance to lead a gang in your current job and get it written into your reference letter.</p>

<h2>The Skills Employers Test</h2>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/site-supervisor-foreman-abroad-drawings.jpg"
         alt="A site supervisor in a white hard hat and a foreman in a yellow hard hat reading a construction drawing together beside rebar and formwork, while steel fixers work and an excavator digs in front of the Dubai skyline"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>Reading drawings</h3>
<p>Expect to be handed a drawing at interview. You should be able to read plans, sections and elevations, find setting-out dimensions and levels, and spot where a drawing and the work on site disagree. For MEP supervisors, add services drawings and coordination drawings.</p>

<h3>Planning the day</h3>
<p>Supervisors turn the programme into tomorrow's work: which crew, which area, which materials, which equipment. Being able to explain how you planned a pour or a fit-out sequence is a strong interview answer.</p>

<h3>Running toolbox talks</h3>
<p>A toolbox talk is a short safety briefing before work starts, covering the day's hazards: work at height, lifting operations, excavations, heat. On Gulf sites it is usually the foreman's job, often delivered across several languages. Practise giving one.</p>

<h3>Permits, reports and records</h3>
<p>Permits to work for hot work, confined spaces and lifting; daily progress reports; inspection and test plans. Larger contractors expect supervisors to fill these in properly in English.</p>

<h2>Safety Certificates: Which Ones Matter</h2>

<ul>
    <li><strong>NEBOSH International General Certificate (IGC).</strong> The best-known international safety qualification. Essential if you are moving into an HSE officer or HSE supervisor role, and valued for site supervisors.</li>
    <li><strong>IOSH Managing Safely.</strong> A shorter course aimed at supervisors and managers who are not safety specialists. A practical first certificate for a foreman.</li>
    <li><strong>OSHA outreach cards (10-hour and 30-hour construction).</strong> US-based training cards that some Gulf and US-linked contractors ask for, particularly for American-managed projects.</li>
</ul>

<p>For an HSE role, a safety certificate is usually a requirement. For a site supervisor role it is usually a strong advantage rather than a condition, so start with IOSH Managing Safely unless you are aiming at HSE work.</p>

<h2>Getting Hired Abroad: The UAE Rules</h2>

<ul>
    <li><strong>A work permit is compulsory.</strong> Article 6 of Federal Decree-Law No. 33 of 2021 requires a MOHRE work permit before you can be employed.</li>
    <li><strong>Get the offer letter first.</strong> The offer you sign is the basis of the work permit and contract. Check the job title, basic wage and allowances before you sign, because the contract should match it.</li>
    <li><strong>You do not pay recruitment costs.</strong> The same article bars the employer from charging the worker recruitment and employment costs, directly or indirectly.</li>
    <li><strong>No minimum wage for expatriates.</strong> The contract is the only binding figure. At supervisor level, negotiate the basic wage, housing, transport or a vehicle, and an annual ticket separately.</li>
    <li><strong>The midday break applies to your crew.</strong> From 15 June to 15 September, outdoor work under direct sun stops from 12:30pm to 3pm. As supervisor you will be the person who has to enforce it; fines are AED 5,000 per worker, up to AED 50,000.</li>
</ul>

<p>In Saudi Arabia, contracts are registered on Qiwa and the employer bears recruitment, iqama and work permit costs. Working in a profession different from the one on your work permit is not allowed, so a promotion from tradesman to supervisor needs the profession changed officially.</p>

<h2>Building Your Application</h2>

<ol>
    <li><strong>Lead with the crews you ran</strong>: how many people, which trade, which project, and what you delivered.</li>
    <li><strong>Name the project types</strong>: high-rise, villas, infrastructure, MEP fit-out.</li>
    <li><strong>List your certificates</strong> with dates and issuing bodies.</li>
    <li><strong>Bring a reference</strong> from a site engineer or project manager that says you led people.</li>
    <li><strong>Apply directly</strong> through the contractor's careers system or your country's official overseas employment channel. Never pay a placement fee.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the difference between a foreman and a site supervisor?</h3>
<p>A foreman usually leads one trade crew and may still work on the tools. A site supervisor coordinates several crews or a section of the site and reports to the site engineer or construction manager.</p>

<h3>How many years of experience do I need to be a site supervisor abroad?</h3>
<p>Employers recruiting from abroad often ask for 5 to 10 years of site experience, including time leading a crew.</p>

<h3>Do I need a degree to become a site supervisor?</h3>
<p>Not usually. Many supervisors come up from a trade. A diploma in civil or building works helps, especially for larger contractors.</p>

<h3>Is NEBOSH required for a site supervisor job?</h3>
<p>It is usually required for HSE roles and an advantage for site supervisors. IOSH Managing Safely is a shorter first step for foremen.</p>

<h3>What is a toolbox talk?</h3>
<p>A short safety briefing at the start of the shift covering the day's hazards. On Gulf sites the foreman usually gives it.</p>

<h3>Who pays for a UAE work visa for a supervisor?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs.</p>

<h3>Can I be promoted from mason to foreman in Saudi Arabia?</h3>
<p>Yes, but the profession on your work permit has to be changed officially. Working in a profession different from your permit is not allowed.</p>

<h3>Is OSHA training useful outside the USA?</h3>
<p>Some international contractors, especially on American-managed projects, ask for OSHA 30-hour construction training. NEBOSH and IOSH are more widely recognised in the Gulf.</p>

<h2>People Also Search For</h2>

<h3>Site supervisor jobs in Dubai</h3>
<p>Recruited on MOHRE work permits, with recruitment costs owed by the employer.</p>

<h3>Construction foreman jobs abroad</h3>
<p>Lead with the crews you ran and the projects you delivered.</p>

<h3>Foreman vs supervisor</h3>
<p>One crew versus several; on the tools versus coordinating.</p>

<h3>NEBOSH IGC for supervisors</h3>
<p>Essential for HSE roles, an advantage for site supervisors.</p>

<h3>IOSH Managing Safely</h3>
<p>A shorter safety course aimed at supervisors and managers.</p>

<h3>MEP foreman jobs in UAE</h3>
<p>Add services and coordination drawings to your skills.</p>

<h3>Site supervisor experience requirements</h3>
<p>Often 5 to 10 years, including time leading a crew.</p>

<h3>Toolbox talk topics</h3>
<p>Work at height, lifting, excavations and heat stress.</p>

<h2>More Job Guides</h2>

<p>The rest of the construction cluster:</p>

<ul>
    <li><a href="/blog/construction-labourer-jobs-in-dubai-and-saudi-arabia">Construction Labourer Jobs in Dubai and Saudi Arabia</a> &mdash; the bottom of the ladder and how to start climbing it.</li>
    <li><a href="/blog/tiler-plasterer-and-mason-jobs-overseas">Tiler, Plasterer and Mason Jobs Overseas</a> &mdash; the skilled trades most foremen come from.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the Saudi market from labourer to project manager.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; the Gulf rules for a building services trade.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; the Gulf rules for HVAC work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Visa and labour rules change; confirm the current position on u.ae, mohre.gov.ae and hrsd.gov.sa before accepting an offer or paying anyone.</p>
HTML;
    }
}
