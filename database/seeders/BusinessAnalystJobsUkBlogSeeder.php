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
 * "Business Analyst Jobs in UK" — the analyst route into UK business and
 * digital change work. The Jobs in UK for Foreigners guide owns the Skilled
 * Worker rules in general, so this one owns what SOC 2431 means for a
 * business analyst specifically.
 *
 * Corrections to the draft (checked against nationalcareers.service.gov.uk,
 * gov.uk, civil-service-careers.gov.uk, the DDaT capability framework, ONS
 * ASHE, careers.capgemini.com and HSBC careers, September 2026):
 *
 * 1. The draft calls GBP 35,100 the "lower going rate" for SOC 2431. The
 *    lower going rate is GBP 36,000, and it applies only to Health and Care
 *    Worker applicants or people whose first certificate of sponsorship
 *    predates 4 April 2024. GBP 35,100 is 70% of the standard going rate,
 *    from the separate table for applicants under 26, studying, training or
 *    in a postdoctoral role.
 *
 * 2. The draft has no salary anchor beyond the National Careers Service band.
 *    ONS ASHE (released 19 November 2025) puts the median for management
 *    consultants and business analysts in England at GBP 52,970, on 207,000
 *    jobs.
 *
 * 3. The draft says the National Careers Service lists internships and work
 *    placements as entry routes. It lists three: a university course, an
 *    apprenticeship, and applying directly.
 *
 * 4. The draft describes the DDaT framework loosely. It sets six levels, from
 *    trainee business analyst at EO grade to head of business analysis at G6,
 *    and publishes grades rather than salaries.
 *
 * 5. The draft quotes a Forestry Commission vacancy and NatWest job details.
 *    Civil Service Jobs and jobs.natwestgroup.com both blocked automated
 *    checks, so neither is published here as verified.
 *
 * 6. The draft says a Capgemini posting asks for Azure DevOps. The live
 *    Junior Business Analyst posting names JIRA and Confluence, BPMN and UML,
 *    and Power BI or Tableau.
 *
 * 7. The draft dates the register of licensed sponsors to 15 September 2026.
 *    It was last updated on 18 September 2026.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BusinessAnalystJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-business-analyst-jobs.html';

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
        $title = 'Business Analyst Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'ONS puts the median for business analysts and management consultants in England at 52,970 pounds, well above the careers-service band. Entry routes, the level 4 apprenticeship, Civil Service and Capgemini openings, and the SOC 2431 visa rules.',
                'content' => $content,
                'featured_image' => 'blogs/business-analyst-jobs-in-uk.jpg',
                'tags' => 'business analyst jobs uk, business analyst salary uk, soc 2431, business analyst level 4 apprenticeship, digital fast stream business analyst, ddat business analyst, skilled worker visa business analyst, junior business analyst uk',
                'meta_title' => 'Business Analyst Jobs in UK: Salary, Routes and Visas',
                'meta_description' => 'Business analyst jobs in the UK: the ONS median of 52,970 pounds, apprenticeship and Civil Service routes, live employer openings and the SOC 2431 visa rules.',
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
            ['name' => 'UK Banks, Consultancies and Government Departments (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'uk-business-analyst-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Business Analyst — Requirements, Process and Change Roles at UK Banks, Consultancies and Government Departments',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, typically 37 to 39 hours a week',
                'language' => 'English',
                // Bands differ by employer, grade and location, so only
                // official published figures appear in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Business analyst roles with UK banks, consultancies, technology firms and government departments. Apprenticeship, graduate and experienced routes.',
                'seo_keywords' => 'business analyst jobs uk, junior business analyst jobs, business analyst apprenticeship, ddat business analyst, business analyst london',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK banks, consultancies, technology firms, retailers and government departments hire business analysts to work out how a business runs today, where it loses time or money, and what should change.</p>

<h3>What the work involves</h3>
<p>Interviewing stakeholders, documenting processes, gathering and writing requirements and user stories, analysing costs, benefits and risks, supporting testing and helping teams put changes in place.</p>

<h3>Common requirements</h3>
<ul>
    <li>Analytical thinking and clear written and verbal communication</li>
    <li>Requirements gathering, process mapping and stakeholder management</li>
    <li>A degree, a level 4 apprenticeship, or experience in project management, consulting or IT</li>
    <li>Working knowledge of Agile delivery and tools such as Jira and Confluence</li>
    <li>The right to work in the UK, or sponsorship under an eligible occupation code</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, grades, sponsorship and hiring rules are set by each employer and by the Home Office &mdash; not by JobGader. Read the current vacancy before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Business analyst jobs in the UK sit between the business and the technology team: understanding how an organisation works, finding the problems, writing the requirements and helping deliver the change.</strong> The National Careers Service gives a band of <strong>&pound;23,000 to &pound;55,000</strong>, but ONS earnings data puts the median for the official occupation code at <strong>&pound;52,970</strong>. You can enter with a degree, a level 4 apprenticeship or experience in project management, consulting or IT.</p>

<p>This guide covers what the job pays, the three entry routes, the Civil Service and employer openings, and what the Skilled Worker rules mean for SOC 2431.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-business-analyst-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128202; Browse Business Analyst Jobs in UK &rarr;
    </a>
</div>

<h2>What Does a Business Analyst Do in the UK?</h2>

<p>The National Careers Service describes the day-to-day work as:</p>

<ul>
    <li>speaking to managers to learn what their business needs are;</li>
    <li>writing down what the business does and how;</li>
    <li>analysing the findings to suggest changes and improvements;</li>
    <li>explaining the possible effects of changes, such as the costs, benefits and risks;</li>
    <li>organising testing and quality checks;</li>
    <li>supporting staff to make changes.</li>
</ul>

<p>Government's own Digital and Data capability framework puts it more sharply: business analysts research how a business area works &mdash; people, organisation, processes, information, data and technology &mdash; identify areas for improvement, explore feasible options, analyse the effects of change and <strong>define success measures</strong>, then use that analysis to decide priorities and the minimum viable product.</p>

<p>Typical hours are <strong>37 to 39 a week</strong>, with occasional evening work.</p>

<h2>How Much Do Business Analysts Earn in the UK?</h2>

<p>Two official sources, two very different pictures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Source</th>
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">What it covers</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>National Careers Service</strong></td><td style="padding:10px;">&pound;23,000 starter to &pound;55,000 experienced</td><td style="padding:10px;">The government careers site's average band</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>ONS ASHE, released 19 Nov 2025</strong></td><td style="padding:10px;"><strong>&pound;52,970 median</strong>, &pound;59,211 mean</td><td style="padding:10px;">SOC 2431, management consultants and business analysts, England, 207,000 jobs</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">ONS percentiles</td><td style="padding:10px;">10th &pound;28,747 &middot; 25th &pound;39,260 &middot; 75th &pound;71,008 &middot; 90th &pound;93,273</td><td style="padding:10px;">Same dataset and occupation code</td></tr>
    </tbody>
</table>
</div>

<p>The careers service band describes where most people start and finish in a business analyst job title. The ONS code is broader, because it also holds management consultants, so the top of it is higher than a typical in-house BA earns. Read them together: <strong>the bottom quarter of the market is under &pound;39,260 and the top quarter is over &pound;71,008</strong>, and the difference is mostly sector, London weighting and whether you sit in consulting or in-house.</p>

<h2>What Qualifications Do You Need?</h2>

<p>The National Careers Service lists exactly <strong>three routes</strong> into the job:</p>

<ol>
    <li><strong>University.</strong> Any degree that teaches analytical skills can be useful; the named subjects are business information systems, business management, computing and systems development, and computer science.</li>
    <li><strong>An apprenticeship.</strong> The <strong>Business Analyst Level 4 Higher Apprenticeship</strong>, or the Project Manager Level 6 Degree Apprenticeship.</li>
    <li><strong>Applying directly</strong>, if you have several years of experience in project management, consulting or IT.</li>
</ol>

<p>There is no college route and no volunteering route on the official profile, which is worth knowing before you pay for a course that promises one.</p>

<h3>Skills employers ask for</h3>

<p>The careers service lists business management skills, analytical thinking, flexibility and openness to change, initiative, customer service skills, teamwork, excellent verbal communication and a thorough understanding of computer systems and applications. Vacancies add the craft itself: requirements gathering, process mapping, user stories and acceptance criteria, stakeholder management, data analysis and Agile delivery.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/business-analyst-jobs-in-uk-requirements.jpg" alt="Three colleagues reviewing analysis on a laptop in a London office with Tower Bridge behind them" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Most of the job is conversations with stakeholders, then writing down what was agreed.</figcaption>
</figure>

<h2>Where to Find Business Analyst Jobs in the UK</h2>

<h3>Civil Service: Digital Fast Stream and TechTrack</h3>

<p>The <strong>Digital Fast Stream</strong> includes business analyst as one of its non-technical roles, alongside product manager, delivery manager and user researcher, and you complete <strong>12-month postings each year</strong>, gaining exposure to all four roles.</p>

<p><strong>TechTrack</strong> is an <strong>18-month apprenticeship</strong>, delivered with BPP. As a TechTrack Apprentice Business Analyst you learn to analyse a business problem or opportunity, research how a business area works, and make prioritisation and minimum viable product decisions from analysis-led insight.</p>

<p>Once you are in government, the <strong>DDaT capability framework</strong> sets six levels for the profession: trainee business analyst (EO), junior business analyst (EO&ndash;HEO), business analyst (HEO&ndash;SEO), senior business analyst (SEO&ndash;G7), lead business analyst (G7) and head of business analysis (G6). The framework publishes grades, not salaries, so check each vacancy's band on Civil Service Jobs.</p>

<h3>Capgemini</h3>

<p>Capgemini advertises business analysis as its own professional community. Its live <strong>Junior Business Analyst</strong> posting, put up on <strong>3 September 2026</strong>, covers five locations at once &mdash; <strong>London, Manchester, Newcastle upon Tyne, Bristol and Birmingham</strong> &mdash; and names the tools it expects you to meet: <strong>JIRA and Confluence</strong>, modelling with BPMN and UML, and BI tools such as Power BI or Tableau. A <strong>Graduate Business Analyst 2026</strong> vacancy runs alongside it.</p>

<h3>HSBC</h3>

<p>HSBC's careers portal carried two UK business analyst vacancies when we checked, both in <strong>London</strong> and both hybrid, within Asset and Wealth Management: an <strong>Alternatives Senior Business Analyst</strong> and a <strong>Business Analyst &mdash; Operations Design and Delivery</strong> on a two-year fixed-term contract.</p>

<h3>Banks, consultancies and Civil Service Jobs</h3>

<p>NatWest, Lloyds, Barclays and the large consultancies all run their own portals, and government vacancies are advertised on <strong>Civil Service Jobs</strong>. Both Civil Service Jobs and the NatWest careers site block automated checks, so we have not published vacancy counts or titles from either &mdash; search them directly.</p>

<h2>What Tools Should a UK Business Analyst Know?</h2>

<ul>
    <li><strong>Jira and Confluence</strong> for backlogs, tickets and documentation;</li>
    <li><strong>Process and requirements modelling</strong>: BPMN, UML, user stories and acceptance criteria;</li>
    <li><strong>Excel</strong> for analysis, and <strong>SQL</strong> for pulling your own data;</li>
    <li><strong>Power BI or Tableau</strong> for visualising findings;</li>
    <li><strong>Agile delivery</strong>: Scrum and Kanban ways of working.</li>
</ul>

<p>You do not need all of them on day one. The Capgemini junior posting is a good checklist of what a UK entry-level vacancy actually asks for.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/business-analyst-jobs-in-uk-workshop.jpg" alt="A business analyst walking colleagues through dashboards on a screen in a London office overlooking the river" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Analysis only counts when the team acts on it, so presenting well matters as much as the spreadsheet.</figcaption>
</figure>

<h2>Can Foreigners Apply for Business Analyst Jobs in the UK?</h2>

<p>Yes, but the job has to be sponsored. Business analysts fall under <strong>SOC 2431, management consultants and business analysts</strong>, which the Home Office classifies as a <strong>higher skilled</strong> eligible occupation. The numbers that decide your application:</p>

<ul>
    <li><strong>General Skilled Worker threshold:</strong> at least <strong>&pound;41,700</strong> a year, or the going rate for the job, whichever is higher.</li>
    <li><strong>Standard going rate for SOC 2431:</strong> <strong>&pound;50,200</strong> a year (&pound;25.74 an hour).</li>
    <li><strong>Lower going rate:</strong> &pound;36,000 &mdash; but only for Health and Care Worker applicants or people whose first certificate of sponsorship predates 4 April 2024.</li>
    <li><strong>Under 26, studying, training or postdoctoral:</strong> 70% of the standard going rate, which is <strong>&pound;35,100</strong> for this code, with an absolute floor of <strong>&pound;33,400</strong>.</li>
    <li><strong>PhD holders:</strong> 80% of the going rate for a STEM PhD, or 90% for a non-STEM PhD with a floor of &pound;37,500.</li>
</ul>

<p>So a business analyst offer below roughly &pound;50,000 usually fails the going-rate test unless one of those reduced-rate routes applies to you. Before you apply from abroad: check the vacancy's sponsorship wording, confirm the employer appears on the Home Office <strong>register of licensed sponsors</strong> (last updated <strong>18 September 2026</strong>), and compare the salary with the rules above. Our <a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> guide covers the visa routes in full.</p>

<h2>How to Apply for Business Analyst Jobs in the UK</h2>

<ol>
    <li><strong>Pick a sector:</strong> banking, consulting, government, retail, healthcare or technology. The domain knowledge is what gets you shortlisted.</li>
    <li><strong>Prove the analysis,</strong> not the job title. Show a problem you investigated, what you found and what changed.</li>
    <li><strong>Learn the techniques:</strong> stakeholder interviews, process mapping, requirements documents, user stories and acceptance criteria.</li>
    <li><strong>Add data skills:</strong> Excel first, then SQL and a BI tool.</li>
    <li><strong>Apply through employer portals</strong> such as Capgemini, HSBC and Civil Service Jobs rather than job boards alone.</li>
    <li><strong>Prepare for competency interviews:</strong> conflicting stakeholders, unclear requirements, and how you would explain a recommendation to a non-technical manager.</li>
</ol>

<h2>Career Progression</h2>

<p>The National Careers Service says that with experience you could become a business project manager, work as a consultant, move into different industries, set up your own company or work freelance. Inside a large employer the ladder usually runs junior analyst, business analyst, senior analyst, lead analyst, and then head of business analysis or a move into product ownership, change management or consulting.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the salary of a business analyst in the UK?</h3>
<p>The National Careers Service gives &pound;23,000 for starters to &pound;55,000 for experienced analysts. ONS data for SOC 2431 puts the median at &pound;52,970 in England, with the top 10% above &pound;93,273.</p>

<h3>Do I need a degree to become a business analyst in the UK?</h3>
<p>No. The official routes are a university course, the Business Analyst Level 4 Higher Apprenticeship, or applying directly with several years of project management, consulting or IT experience.</p>

<h3>Can I get a business analyst job with no experience?</h3>
<p>Through apprenticeships and graduate schemes. The Civil Service TechTrack apprenticeship runs 18 months, the Digital Fast Stream rotates through business analysis, and employers such as Capgemini advertise junior and graduate business analyst roles.</p>

<h3>Can a business analyst get a Skilled Worker visa?</h3>
<p>Yes. SOC 2431 is a higher-skilled eligible occupation. You normally need at least &pound;41,700 or the &pound;50,200 going rate, whichever is higher, from a licensed sponsor.</p>

<h3>What is the going rate for SOC 2431?</h3>
<p>&pound;50,200 a year, or &pound;25.74 an hour. Reduced rates apply if you are under 26, studying, training, in a postdoctoral role or hold a PhD.</p>

<h3>Which tools should I learn first?</h3>
<p>Jira and Confluence, process modelling with BPMN or UML, Excel, then SQL and Power BI or Tableau.</p>

<h3>What hours do business analysts work?</h3>
<p>Typically 37 to 39 hours a week, with occasional evening work, according to the National Careers Service.</p>

<h3>Which employers hire business analysts in the UK?</h3>
<p>Banks, consultancies, technology firms, retailers, insurers and government departments. Capgemini, HSBC and the Civil Service all advertise business analysis roles directly.</p>

<h2>People Also Search For</h2>

<h3>Business analyst salary UK</h3>
<p>&pound;52,970 median for SOC 2431 in England, on ONS figures released in November 2025.</p>

<h3>Junior business analyst jobs</h3>
<p>Capgemini's junior posting covers London, Manchester, Newcastle, Bristol and Birmingham.</p>

<h3>Business analyst apprenticeship</h3>
<p>The Level 4 Higher Apprenticeship, or the Civil Service's 18-month TechTrack route.</p>

<h3>Digital Fast Stream business analyst</h3>
<p>One of four non-technical roles, with 12-month postings each year.</p>

<h3>DDaT business analyst levels</h3>
<p>Six levels, from trainee at EO grade to head of business analysis at G6.</p>

<h3>Business analyst visa sponsorship UK</h3>
<p>SOC 2431 is eligible; the going rate is &pound;50,200 and the general threshold &pound;41,700.</p>

<h3>Business analyst jobs London</h3>
<p>Where most vacancies sit, including both current HSBC business analyst roles.</p>

<h3>Business analyst tools</h3>
<p>Jira, Confluence, BPMN, UML, Excel, SQL and Power BI.</p>

<h2>More Job Guides</h2>

<p>Comparing UK and analyst careers? These cover them:</p>

<ul>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the Skilled Worker threshold, the salary lists and who can work without a sponsor.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the technical route into UK technology teams.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a> &mdash; another office career priced from National Careers Service and ONS figures.</li>
    <li><a href="/blog/finance-analyst-jobs-in-canada">Finance Analyst Jobs in Canada</a> &mdash; the finance analyst route, with Job Bank pay and the CFA rule.</li>
    <li><a href="/blog/account-manager-jobs-in-usa">Account Manager Jobs in USA</a> &mdash; the client-facing commercial career in the US market.</li>
    <li><a href="/blog/data-scientist-jobs-in-usa">Data Scientist Jobs in USA</a> &mdash; where analysis meets code, priced from BLS.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the National Careers Service, GOV.UK Skilled Worker guidance, the DDaT capability framework, ONS ASHE data and the Capgemini and HSBC career sites. Salaries, going rates and vacancies change. Always check the current vacancy and immigration rules before applying.</p>
HTML;
    }
}
