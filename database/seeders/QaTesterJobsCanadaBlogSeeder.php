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
 * "QA Tester Jobs in Canada" — the standard entry-into-tech article, written
 * as though QA were one occupation with one salary band. Canada's own labour
 * market data splits it across two occupation codes with different education
 * requirements, different pay and very different numbers of advertised jobs.
 *
 * Corrections to the draft:
 *
 * 1. It treats "QA Tester" as a single job. Job Bank maps software tester to
 *    Information systems testing technicians (NOC 22222) and software QA
 *    analyst to Information systems specialists (NOC 21222). The two are not
 *    seniority levels of one role; they are separate classifications.
 *
 * 2. Its salary band of CAD 55,000 to 80,000 hides the bottom of the market.
 *    Job Bank's prevailing wages for NOC 22222 in Canada run 17.50 low, 35.00
 *    median and 51.28 high per hour, updated 19 November 2025 from the Labour
 *    Force Survey. The low end is roughly half the draft's floor, so its claim
 *    that entry-level testers "start on the lower end of this range" is wrong.
 *
 * 3. Its "CAD 85,000 to 100,000+" tier belongs to the other code. NOC 21222
 *    runs 28.85 low, 46.15 median and 68.68 high per hour, which is where the
 *    higher figures actually come from.
 *
 * 4. It says QA values analytical thinking "over formal programming
 *    experience" and attaches that to the whole pay range. Job Bank states
 *    NOC 22222 usually requires a college diploma or two or more years of
 *    apprenticeship training, while NOC 21222 usually requires a university
 *    degree. The low-barrier claim does not carry to the better-paid code.
 *
 * 5. It asserts demand "continues to grow" and offers "strong long-term career
 *    stability". Job Bank rates prospects for both codes as varying by
 *    province or territory, and at the time of writing listed 5 jobs
 *    advertised in Canada under software tester against 331 under the
 *    information systems specialist code.
 *
 * 6. It omits the one fact that decides an international applicant's options:
 *    which NOC code an offer is classified under, because that code, not the
 *    job title on the advertisement, drives immigration eligibility.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class QaTesterJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-qa-tester-jobs.html';

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
        $title = 'QA Tester Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Canada splits QA work across two occupation codes: software testers (NOC 22222) at a median of 35.00 CAD an hour with a college diploma, and QA analysts (NOC 21222) at 46.15 CAD with a university degree. The market low is 17.50.',
                'content' => $content,
                'featured_image' => 'blogs/qa-tester-jobs-in-canada.jpg',
                'tags' => 'qa tester jobs in canada, software tester canada, qa analyst canada, noc 22222, noc 21222, automation testing jobs canada, istqb certification, tech jobs canada',
                'meta_title' => 'QA Tester Jobs in Canada: Real Pay and Two Job Codes',
                'meta_description' => 'QA tester jobs in Canada: Job Bank splits the role across two NOC codes with different pay and education. The median is 35.00 CAD an hour, not the usual range.',
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
            ['name' => 'Canadian Employers Advertising QA and Software Testing Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-qa-tester-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'QA Tester — Manual, Automation and Software Testing Roles, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Typically 37.5 to 40 hours a week, with release cycles driving busier periods',
                'language' => 'English, or English and French in Quebec',
                // QA work is classified under two different NOC codes whose
                // prevailing wages barely overlap, so a single band would
                // misdescribe whichever half of the market it missed.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Manual, automation and specialist QA roles with Canadian employers. Check which NOC code the position falls under, because it changes both the expected pay and your immigration options.',
                'seo_keywords' => 'qa tester jobs canada, software tester jobs canada, automation qa jobs toronto, qa analyst vancouver, noc 22222 jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian software companies, gaming studios, fintech firms and enterprise IT departments recruit QA staff across Toronto, Vancouver, Montreal and Ottawa, and increasingly on a remote or hybrid basis. Roles range from manual test execution through to automation engineering, and they are not all classified the same way.</p>

<h3>What the work involves</h3>
<p>Writing and executing test cases against requirements; reproducing, documenting and tracking defects in Jira, Azure DevOps or similar; regression testing each release; working with developers to confirm fixes; building and maintaining automated suites in tools such as Selenium or Cypress in the more technical roles; and reporting findings back into sprint planning.</p>

<h3>Requirements</h3>
<ul>
    <li>For testing technician roles, a college diploma or two or more years of apprenticeship training, or equivalent supervisory experience</li>
    <li>For quality assurance analyst roles, usually a university degree</li>
    <li>Familiarity with the software development life cycle and common testing methodologies</li>
    <li>Bug-tracking tools, and basic SQL for database checks</li>
    <li>Scripting in Python or JavaScript for automation-focused positions</li>
    <li>Clear written English, because a defect report is a written argument</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Find out which occupation code the role sits under before you talk about money.</strong> Software testing technicians and information systems specialists are separate classifications in Canada with substantially different prevailing wages, and for an international applicant the code also drives which immigration routes are open.</p>

<p><strong>Note:</strong> pay, tooling and eligibility are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Almost every guide to QA work in Canada quotes one salary band and calls it the going rate for a QA Tester. Canada's own labour market data does not recognise a single occupation by that name. It recognises two, with different entry requirements, different pay and a startling difference in how many jobs are actually advertised under each. Knowing which one an advertisement belongs to is the most useful thing you can learn before applying.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/qa-tester-jobs-in-canada-testing.jpg" alt="QA tester working through test cases on a software project in Canada" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Canada Files QA Work Under Two Different Codes</h2>

<p>Job Bank, the federal labour market service, maps the job titles like this:</p>

<ul>
    <li><strong>Software tester</strong> belongs to <strong>Information systems testing technicians (NOC 22222)</strong></li>
    <li><strong>Software QA (quality assurance) analyst</strong> belongs to <strong>Information systems specialists (NOC 21222)</strong></li>
</ul>

<p>These are not two rungs of one ladder. They are separate classifications, and the entry requirement is where they part company. For NOC 22222, Job Bank states the occupation <strong>usually requires a college diploma (community college, institute of technology or CEGEP), an apprenticeship training of 2 or more years, or experience working in a supervisory occupation</strong>. For NOC 21222, it states the occupation <strong>usually requires a university degree</strong>.</p>

<p>That single distinction disposes of the most repeated claim in QA careers advice. It is broadly fair to say testing is an accessible entry point into tech and that a computer science degree is not always required &mdash; but that applies to the technician code. The better-paid analyst code usually does want the degree. Articles that borrow the low barrier from one code and the salary from the other are describing a job that does not exist.</p>

<h2>What QA Actually Pays in Canada</h2>

<p>Job Bank publishes prevailing wages from the Labour Force Survey, <strong>updated on 19 November 2025</strong> for the 2023-2024 reference period. Per hour, across Canada:</p>

<ul>
    <li><strong>Software tester (NOC 22222)</strong> &mdash; low <strong>17.50</strong>, median <strong>35.00</strong>, high <strong>51.28</strong></li>
    <li><strong>Information systems QA analyst (NOC 21222)</strong> &mdash; low <strong>28.85</strong>, median <strong>46.15</strong>, high <strong>68.68</strong></li>
</ul>

<p>Now compare that against the band every guide quotes. A median of 35.00 an hour is roughly 68,000 a year on a 37.5-hour week, which sits comfortably inside the usual "55,000 to 80,000" claim &mdash; so the middle of the article is right. The ends are not.</p>

<p><strong>The bottom of the tester market is 17.50 an hour</strong>, about half the quoted floor. So the standard line that entry-level testers "typically start on the lower end of this range" is not true: entry level often starts well below that range entirely. If you are taking a first QA job, budget against the low figure, not the band.</p>

<p>At the other end, the "85,000 to 100,000+" tier that guides attribute to experienced QA testers is really the analyst code doing the work. A high of 68.68 an hour under NOC 21222 is roughly 134,000 a year. The money is real; it is just attached to the classification that usually asks for a degree.</p>

<p>Regional variation is smaller than the city-by-city framing suggests. In Alberta, for instance, testers run 24.37 low, 36.70 median and 52.42 high, and analysts 27.13, 48.08 and 68.00 &mdash; close to the national picture rather than dramatically apart from it.</p>

<h2>The Demand Claim Deserves a Hard Look</h2>

<p>Careers articles on this subject are unanimous that demand is growing and stability is strong. Job Bank is more restrained. It rates job prospects for both codes as <strong>varying across Canada depending on the province or territory</strong> rather than uniformly strong.</p>

<p>The advertised-volume figures are blunter still. At the time of writing, Job Bank listed <strong>5 jobs advertised in Canada</strong> under software tester, against <strong>331</strong> under the information systems specialist code that contains QA analyst. Treat both numbers as a snapshot of one federal job board rather than the whole market &mdash; but the ratio is the signal. Hiring is concentrated in the professional classification, not the technician one.</p>

<p>The practical reading is not "avoid QA". It is that the pure manual-testing job title is the thinner end of the market, and that moving toward the analyst and automation side is where the volume and the money both sit. That is the same conclusion the standard career-path advice reaches, arrived at from evidence rather than optimism.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/qa-tester-jobs-in-canada-automation.jpg" alt="Automation test scripts running against a build, the higher-paid side of QA work in Canada" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What the Job Is Worth Beyond the Wage</h2>

<p>One figure worth knowing before you compare offers: Job Bank reports that <strong>85.8 per cent</strong> of workers in NOC 22222 across Canada receive at least one type of non-wage benefit. Benefits are close to standard in this occupation rather than a perk to be traded away, so an offer without them is below market in a way the hourly rate alone will not show you.</p>

<p>The skills also transfer unusually well. Reading requirements critically, reproducing a fault reliably, and writing a defect report someone else can act on are the foundations of business analysis, technical support, release management and product work. QA is one of the few tech roles where the daily work is literally learning how the whole system fits together.</p>

<h2>If You Are Applying From Outside Canada</h2>

<p>This is where the two codes stop being trivia. Canadian immigration programmes are organised by NOC code, not by job title, so <strong>the code an employer classifies your offer under is what determines your options</strong> &mdash; and an advertisement calling the role "QA Tester" tells you nothing about which one it is.</p>

<p>Two things follow. First, ask the employer directly which NOC code the position is being classified under, and get it in writing before you rely on it. Second, remember that the education Job Bank associates with each code is not a formality: a university degree sits behind the analyst classification, and that is the classification carrying both the higher wages and the far larger number of advertised positions.</p>

<h2>How to Get Hired</h2>

<ul>
    <li><strong>Work out the code from the job description,</strong> not the title. Requirements-level analysis, test strategy and stakeholder reporting point to the analyst classification; executing test cases points to the technician one</li>
    <li><strong>Learn one automation tool properly.</strong> Beginner-level Selenium or Cypress plus a little Python or JavaScript is what moves you from the thin end of the market to the thick end</li>
    <li><strong>Show a defect report, not a certificate.</strong> A clear, reproducible bug write-up with steps, expected and actual behaviour demonstrates the core skill better than any bullet point</li>
    <li><strong>Treat ISTQB as a tiebreaker,</strong> useful for getting past a first screen, not a substitute for the education the classification expects</li>
    <li><strong>Benchmark the offer against the right median</strong> &mdash; 35.00 an hour for a testing technician role, 46.15 for a QA analyst role</li>
    <li><strong>Ask about benefits explicitly,</strong> since most people in this occupation have them</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-qa-tester-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; See Current Canadian QA Tester Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How much do QA testers earn in Canada?</h3>
<p>It depends which occupation code the role falls under. Job Bank puts software testers (NOC 22222) at 17.50 low, 35.00 median and 51.28 high per hour, and information systems QA analysts (NOC 21222) at 28.85, 46.15 and 68.68. The wages were updated on 19 November 2025.</p>

<h3>Is the usual CAD 55,000 to 80,000 range accurate?</h3>
<p>Only through the middle. A 35.00 median works out near the centre of it, but the bottom of the tester market is 17.50 an hour, roughly half the quoted floor, so entry-level pay often falls below the range rather than at its lower end.</p>

<h3>Do I need a degree to work in QA in Canada?</h3>
<p>For the testing technician code, Job Bank says the occupation usually requires a college diploma, two or more years of apprenticeship training, or supervisory experience. For the QA analyst code, it says a university degree is usually required.</p>

<h3>What is the difference between NOC 22222 and NOC 21222?</h3>
<p>NOC 22222 is Information systems testing technicians, where software tester sits. NOC 21222 is Information systems specialists, where software QA analyst sits. They differ in expected education, in prevailing wages, and in how many positions are advertised.</p>

<h3>Is QA testing in high demand in Canada?</h3>
<p>Job Bank rates prospects for both codes as varying by province or territory rather than strong everywhere. At the time of writing it listed 5 jobs advertised in Canada under software tester against 331 under the specialist code, so hiring concentrates in the professional classification.</p>

<h3>Does the NOC code matter if I am applying from abroad?</h3>
<p>Yes, more than the job title does. Canadian immigration programmes are organised by NOC code, so the code your offer is classified under decides which routes are open. Ask the employer which code applies and get the answer in writing.</p>

<h3>Do QA jobs in Canada come with benefits?</h3>
<p>Usually. Job Bank reports that 85.8 per cent of workers in NOC 22222 nationally receive at least one type of non-wage benefit, so an offer without them is below the norm for the occupation.</p>

<h3>Is ISTQB certification worth it?</h3>
<p>It is a recognised credential that can help an application past a first screen, particularly for entry-level candidates. It does not replace the college diploma or degree that Job Bank associates with each occupation code.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Software tester salary Canada</strong> &mdash; median 35.00 an hour under NOC 22222, per Job Bank wages updated 19 November 2025</li>
    <li><strong>QA analyst salary Toronto</strong> &mdash; the higher figures belong to NOC 21222, median 46.15 an hour nationally</li>
    <li><strong>NOC code for QA tester Canada</strong> &mdash; 22222 for testing technicians, 21222 for QA analysts; the code, not the title, drives immigration eligibility</li>
    <li><strong>Entry level QA jobs Canada no experience</strong> &mdash; the market low is 17.50 an hour, well below the salary bands usually quoted</li>
    <li><strong>Manual testing vs automation testing jobs</strong> &mdash; advertised volume concentrates in the specialist classification, not pure manual testing</li>
    <li><strong>ISTQB certification Canada</strong> &mdash; a screening advantage, not a substitute for the education each code expects</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a></li>
    <li><a href="/blog/software-developer-jobs-in-usa">Software Developer Jobs in USA</a></li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a></li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a></li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a></li>
    <li><a href="/blog/ats-resume-writer-jobs-in-canada">ATS Resume Writer Jobs in Canada</a></li>
</ul>
HTML;
    }
}
