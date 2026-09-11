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
 * "Administrative Assistant Jobs in USA" — general, executive, medical and
 * legal administrative work. The US data entry guide covers clerical entry
 * work and the Pakistan virtual assistant guide covers remote contracting,
 * so this one owns American administrative pay, outlook and certification.
 *
 * Corrections to the draft:
 *
 * 1. Its executive assistant band of $55,000 to $75,000 tops out below the
 *    median. The BLS put executive secretaries and executive administrative
 *    assistants at a median of $76,590 in May 2025.
 *
 * 2. Its office manager band of $50,000 to $65,000 is below the $69,500
 *    median for first-line supervisors of office workers, and far below the
 *    $114,130 median for administrative services managers.
 *
 * 3. It says hybrid work and legal sector growth are raising demand. The BLS
 *    projects a 2% fall for the whole group from 2025 to 2035, 6% for general
 *    administrative assistants and 5% for legal secretaries, because
 *    technology including AI lets staff do the work themselves. Only medical
 *    secretaries grow, by 5%.
 *
 * 4. Its top hiring states are right, but it implies they pay best. The
 *    highest means are in DC, Connecticut, Massachusetts and Washington, and
 *    Texas ranks 35th.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AdministrativeAssistantJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-administrative-assistant-jobs.html';

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
        $title = 'Administrative Assistant Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Executive assistants earn a median of $76,590, above the range most guides quote, general administrative assistant jobs are projected to fall 6% by 2035, and medical administrative work is the part still growing.',
                'content' => $content,
                'featured_image' => 'blogs/administrative-assistant-jobs-in-usa.jpg',
                'tags' => 'administrative assistant jobs usa, executive assistant salary, medical administrative assistant jobs, legal secretary jobs, office manager salary, administrative assistant salary by state, cap certification, cmaa certification, remote administrative assistant jobs',
                'meta_title' => 'Administrative Assistant Jobs in USA 2026: Pay and Outlook',
                'meta_description' => 'Administrative assistant jobs in the USA: 2025 pay for executive, medical and legal assistants, the top hiring states, and why the BLS projects a decline.',
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
            ['name' => 'US Offices, Medical Practices & Law Firms (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-admin-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Administrative Assistant — Offices, Medical Practices and Law Firms, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard office hours, with some hybrid and remote roles',
                'language' => 'English',
                // General, executive, medical and legal assistants sit on very
                // different pay scales and state minimums, so no single range
                // holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Administrative, executive, medical and legal assistant roles with US employers. High school diploma typical; Microsoft Office skills expected.',
                'seo_keywords' => 'administrative assistant jobs usa, executive assistant jobs, medical administrative assistant jobs, legal secretary jobs, office assistant jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Businesses, medical practices, law firms, schools and government offices across the United States hire administrative assistants to keep offices running. Most entry-level roles ask for a high school diploma, and many employers train new staff on their own systems.</p>

<h3>What the work involves</h3>
<p>Managing calendars and correspondence, answering phones, preparing documents and spreadsheets, keeping records and filing systems, and coordinating meetings and travel. Medical assistants also handle patient scheduling and billing records, and legal assistants prepare court documents.</p>

<h3>Requirements</h3>
<ul>
    <li>A high school diploma or equivalent, and a degree for many executive assistant roles</li>
    <li>Strong Microsoft Office or Google Workspace skills</li>
    <li>Clear written and spoken English</li>
    <li>For medical and legal roles, knowledge of the terminology, often shown by a certification</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>General administrative assistants.</strong> A BLS median of $47,540 in May 2025</li>
    <li><strong>Executive assistants.</strong> A median of $76,590</li>
    <li><strong>Medical and legal assistants.</strong> Medians of $45,930 and $55,570</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check whether the role is employed or contract.</strong> Many virtual assistant roles are independent contracts, which do not carry minimum wage, overtime or benefits.</p>

<p><strong>Note:</strong> pay, hours and benefits are set by each employer and by federal and state law &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Administrative assistants work in almost every American workplace, from medical practices and law firms to schools and head offices, and most jobs start with a high school diploma. Before you apply, it helps to know three things most guides get wrong: how much the senior roles really pay, which part of the field is shrinking, and where the pay is highest.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-administrative-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Administrative Assistant Jobs in USA &rarr;
    </a>
</div>

<h2>Senior Administrative Roles Pay More Than the Guides Say</h2>

<p>Guides put entry-level administrative assistants at $35,000 to $42,000, experienced assistants at $42,000 to $52,000, executive assistants at <strong>$55,000 to $75,000</strong> and office managers at <strong>$50,000 to $65,000</strong>. The Bureau of Labor Statistics measured these annual wages in May 2025:</p>

<ul>
    <li><strong>Secretaries and administrative assistants (general):</strong> a median of <strong>$47,540</strong>, with the bottom 10% under $33,280 and the top 10% over $66,350. The guides' entry-level and experienced bands are broadly right.</li>
    <li><strong>Executive secretaries and executive administrative assistants:</strong> a median of <strong>$76,590</strong> &mdash; above the top of the guides' range. Even the bottom 10% earned $50,560.</li>
    <li><strong>Legal secretaries and administrative assistants:</strong> a median of $55,570.</li>
    <li><strong>Medical secretaries and administrative assistants:</strong> a median of $45,930.</li>
    <li><strong>First-line supervisors of office workers</strong>, the closest match to most office manager jobs: a median of <strong>$69,500</strong>.</li>
    <li><strong>Administrative services managers:</strong> a median of $114,130, though the BLS lists a bachelor's degree as the typical entry requirement.</li>
</ul>

<p>So the step from assistant to executive assistant is worth about $29,000 a year at the median, and the office manager range in guides undersells the job by a similar margin.</p>

<h2>Demand Is Shrinking, Except in Healthcare</h2>

<p>Guides say hybrid work and growth in healthcare and law are raising demand. The BLS projects the opposite for most of the field from 2025 to 2035:</p>

<ul>
    <li><strong>All secretaries and administrative assistants:</strong> a <strong>2% decline</strong>.</li>
    <li><strong>General administrative assistants:</strong> <strong>down 6%</strong>, the largest group and the steepest fall.</li>
    <li><strong>Legal secretaries:</strong> <strong>down 5%</strong>, despite the guides' claim of legal sector growth.</li>
    <li><strong>Executive assistants:</strong> no change.</li>
    <li><strong>Medical secretaries and administrative assistants:</strong> <strong>up 5%</strong>, because of growth in healthcare.</li>
</ul>

<p>The BLS explains why: technology, including <strong>artificial intelligence systems and digital tools</strong>, lets staff prepare their own documents, and many executive assistants now support more than one manager. Jobs still open up &mdash; about <strong>314,400 a year</strong> &mdash; but mostly to replace people who retire or move on. If you are choosing a specialism, medical administration is the one the projections favour.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/administrative-assistant-jobs-in-usa-desk.jpg"
         alt="An administrative assistant working on a laptop at an office desk with the US Capitol visible through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Jobs Are, and Where the Pay Is</h2>

<p>The guides' list of top hiring states matches the BLS count of general administrative assistants in May 2025:</p>

<ul>
    <li><strong>California:</strong> 158,630</li>
    <li><strong>Texas:</strong> 149,650</li>
    <li><strong>New York:</strong> 122,490</li>
    <li><strong>Florida:</strong> 104,000</li>
    <li><strong>Illinois:</strong> 74,280</li>
</ul>

<p>The highest pay is elsewhere. The mean wage was highest in the <strong>District of Columbia ($61,270)</strong>, followed by Connecticut ($58,390), Massachusetts ($58,380), Washington ($58,130) and California ($57,710). Illinois ranked 11th and New York 12th, Florida 23rd at $48,730, and <strong>Texas 35th at $45,850</strong> &mdash; so the second-largest market pays below the national median.</p>

<h2>Certifications That Carry Weight</h2>

<p>No license is needed to work as an administrative assistant, but two certifications are widely recognised:</p>

<ul>
    <li><strong>Certified Administrative Professional (CAP)</strong>, from the International Association of Administrative Professionals. You need <strong>four years</strong> of administrative experience without a degree, three with an associate degree or two with a bachelor's degree. The three-hour exam costs <strong>$375 for members and $575 for non-members</strong>, and the certification must be renewed every three years.</li>
    <li><strong>Certified Medical Administrative Assistant (CMAA)</strong>, from the National Healthcareer Association. You need a high school diploma or equivalent, plus either a medical administrative assistant training program completed in the last five years or a year of work experience in the last three.</li>
</ul>

<h2>Virtual Assistant Roles: Check the Contract</h2>

<p>Guides list virtual assistant work as one of the main types of administrative job. Many of these roles are <strong>independent contracts</strong>, not employment. The federal minimum wage and overtime rules cover employees, not independent contractors, and contractors do not get employer health insurance or paid time off. Before you accept a remote role, check whether you will be on the payroll or invoicing as a contractor, and price your hours accordingly.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/administrative-assistant-jobs-in-usa-phone.jpg"
         alt="An administrative assistant taking notes during a phone call beside labelled office binders"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Is This a Visa Route?</h2>

<p>Rarely. The H-1B visa is for specialty occupations that need at least a bachelor's degree in a specific field, and the BLS lists a <strong>high school diploma</strong> as the typical entry education for secretaries and administrative assistants. If you are outside the US, treat these jobs as open to people who already have the right to work there.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do administrative assistants earn in the USA?</h3>
<p>The BLS median for general secretaries and administrative assistants was $47,540 in May 2025. Executive assistants earned a median of $76,590, legal assistants $55,570 and medical assistants $45,930.</p>

<h3>How much do executive assistants make?</h3>
<p>A median of $76,590 in May 2025, with the bottom 10% earning $50,560 and the top 10% more than $109,850.</p>

<h3>Are administrative assistant jobs in demand?</h3>
<p>Overall, no. The BLS projects a 2% decline from 2025 to 2035 and a 6% fall for general administrative assistants, but medical administrative assistants are projected to grow 5%, with about 314,400 openings a year across the field.</p>

<h3>Do I need a degree to become an administrative assistant?</h3>
<p>No. The BLS lists a high school diploma as the typical entry education, though many executive assistant roles prefer a degree.</p>

<h3>Which state pays administrative assistants the most?</h3>
<p>The District of Columbia, with a mean of $61,270 in May 2025, followed by Connecticut, Massachusetts, Washington and California. Texas ranked 35th.</p>

<h3>What is the CAP certification?</h3>
<p>The Certified Administrative Professional credential from IAAP. It needs two to four years of experience depending on your education, costs $375 for members or $575 for non-members, and is renewed every three years.</p>

<h3>How much does an office manager make?</h3>
<p>First-line supervisors of office workers earned a median of $69,500 in May 2025, and administrative services managers, who usually need a bachelor's degree, $114,130.</p>

<h3>Are virtual assistants covered by minimum wage?</h3>
<p>Only if they are employees. Independent contractors are not covered by federal minimum wage and overtime rules, so check the contract before accepting a remote role.</p>

<h2>People Also Search For</h2>

<h3>Executive assistant salary</h3>
<p>A BLS median of $76,590 in May 2025.</p>

<h3>Medical administrative assistant jobs</h3>
<p>The one part of the field projected to grow, by 5% to 2035, with a median of $45,930.</p>

<h3>Legal secretary salary</h3>
<p>A median of $55,570, in a job projected to shrink 5% by 2035.</p>

<h3>Administrative assistant salary by state</h3>
<p>Highest in DC at a $61,270 mean, then Connecticut, Massachusetts, Washington and California.</p>

<h3>Remote administrative assistant jobs</h3>
<p>Available, but often as independent contracts without minimum wage, overtime or benefits.</p>

<h3>CMAA certification requirements</h3>
<p>A high school diploma plus a training program in the last five years or a year of experience in the last three.</p>

<h3>Office manager salary</h3>
<p>A $69,500 median for office supervisors and $114,130 for administrative services managers.</p>

<h3>Entry level administrative assistant jobs</h3>
<p>A high school diploma is typical, and the bottom 10% of general assistants earned under $33,280 in May 2025.</p>

<h2>More Job Guides</h2>

<p>Comparing office work in the US and abroad? These cover it:</p>

<ul>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; clerical entry work, and the steeper decline projected for it.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; the same work under Australia's award pay system.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; administrative skills sold remotely to overseas clients.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-office work in the Gulf, and the package split behind it.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; another office skill set that works from home.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; a technical step up from office support.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Wage data, projections, certification fees and employment rules change. Confirm the current position with the employer, the Bureau of Labor Statistics and the certifying body before applying.</p>
HTML;
    }
}
