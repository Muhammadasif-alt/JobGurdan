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

/**
 * "Landscaper Jobs in USA With Visa Sponsorship (2026)".
 *
 * The three BLS landscaping figures in the brief were read back from the BLS
 * API (May 2025, occupation 37-3011) and match. What changed:
 *
 * 1. Ten Apply Now buttons are gone; the guide links only to our own pages and
 *    the single official link sits on the job listing.
 *
 * 2. The brief says to check current USCIS notices for the H-2B cap but names
 *    none. The FY2026 supplemental allocation stopped taking petitions after
 *    15 September 2026, so the guide says so and sends readers to the FY2027
 *    position instead of implying cap numbers are available.
 *
 * 3. The brief had four FAQs; the house format is eight, with eight People Also
 *    Search For entries.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class LandscaperJobsUsaVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'landscaper-jobs-usa-visa-sponsorship-2026';

    private const APPLY_URL = 'https://seasonaljobs.dol.gov/';

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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Landscaper Jobs in USA With Visa Sponsorship (2026): Requirements, Salary and How to Apply',
                'excerpt' => 'Landscaper jobs in the USA with visa sponsorship can use the H-2B seasonal visa when the employer qualifies. See the requirements, BLS pay figures, the cap position, how Pakistani applicants should apply and how to avoid scams.',
                'content' => $content,
                'featured_image' => 'blogs/landscaper-jobs-usa-visa-sponsorship.jpg',
                'tags' => 'landscaper jobs usa, h-2b visa landscaper, landscaper visa sponsorship usa, landscaper jobs for foreigners, landscaping jobs usa 2026, groundskeeper jobs usa, apply landscaper job from pakistan, h-2b cap 2026',
                'meta_title' => 'Landscaper Jobs in USA With Visa Sponsorship (2026)',
                'meta_description' => 'Landscaper jobs in the USA with visa sponsorship: H-2B rules, requirements, BLS pay figures, the cap position and how Pakistani applicants should apply.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Landscaping Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'usa-landscaping-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            ['position' => 'Landscaper — US Employers (H-2B Job Orders)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Seasonal, set by each job order',
                'language' => 'English',
                // Pay is set per job order under the H-2B wage rules; this site
                // does not republish job-board rates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Seasonal landscaping and groundskeeping job orders on the US Department of Labor portal. Sponsorship runs through the H-2B visa and the employer must file first.',
                'seo_keywords' => 'landscaper jobs usa, h-2b job orders, seasonal landscaping jobs usa, landscaper visa sponsorship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>The US Department of Labor's seasonal jobs portal lists job orders from employers who plan to hire temporary workers, including landscaping and groundskeeping companies hiring under the H-2B programme.</p>

<h3>Requirements</h3>
<ul>
    <li>Each job order states its own duties, dates, location and experience requirements; read it before contacting the employer</li>
    <li>The employer must obtain labor certification and petition USCIS before you can apply for the visa, and H-2B has an annual numerical limit</li>
    <li>An H-2B worker should not pay the employer's recruitment, attorney or petition costs, and housing is not automatically included</li>
</ul>

<p><strong>Note:</strong> visa and wage rules are set by the US Government, not by JobGader. This link opens the official listing portal; it is not an application form, and a visible order does not prove recruitment is still open.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Landscaper jobs in the USA with visa sponsorship can use the <strong>H-2B temporary non-agricultural worker route</strong> when the employer and the job meet the programme's rules. You need a participating employer, an approved petition and a visa decision. US landscaping and groundskeeping workers averaged <strong>$20.33 an hour</strong> in the Bureau of Labor Statistics (BLS) May 2025 estimates. That is a national benchmark, not a guaranteed sponsored wage. Pakistani applicants must confirm that the employer recruits from Pakistan, and check the current consular requirements, before they spend any money.</p>

<h2>What Work Do Sponsored Landscapers Perform?</h2>

<p>Landscaping duties can include mowing, edging, planting, mulching, maintaining lawns and preparing outdoor spaces. Some roles involve irrigation installation, hardscape assistance or equipment operation. Read the actual job order, because requirements differ.</p>

<p>Residential contractors, commercial maintenance companies and other employers may advertise seasonal landscaping work. Those are search directions, not confirmation that every company sponsors overseas applicants. For landscaper jobs for foreigners in the USA, check that the employer supports the immigration process instead of assuming an ordinary vacancy offers sponsorship.</p>

<h2>Understanding the H-2B Visa Landscaper Route</h2>

<p>H-2B covers qualifying temporary non-agricultural employment. The employer's need must satisfy the programme rules, and a landscaping title alone does not establish eligibility. The employer obtains the required labor certification and petitions USCIS before the worker applies for the visa.</p>

<p>The programme has a statutory numerical limit, and extra supplemental visas have separate conditions. For fiscal year 2026, USCIS stopped accepting petitions under the supplemental allocation after 15 September 2026, so an advertisement from earlier in the year does not prove a petition can still be filed. Check USCIS's current notices for fiscal year 2027 instead of assuming a past allocation will repeat.</p>

<p>Landscaper visa sponsorship in the USA is therefore an employer-led process, with recruitment, certification, petition and visa decisions as separate stages. The matching agricultural route, for farm work, is explained in <a href="/blog/farm-worker-jobs-usa-visa-sponsorship-2026">Farm Worker Jobs in USA With Visa Sponsorship (2026)</a>.</p>

<h2>Requirements for Applicants</h2>

<p>Each job order should explain its requirements. Common points to check include:</p>

<ul>
    <li>Availability for the stated employment dates.</li>
    <li>Relevant landscaping experience where it is required.</li>
    <li>Ability to perform the advertised physical tasks safely.</li>
    <li>Mower, trimmer or other equipment skills where specified.</li>
    <li>Driving qualifications if driving is part of the job.</li>
    <li>Willingness to follow instructions and safety procedures.</li>
    <li>Valid travel documents and accurate application details.</li>
</ul>

<p>Grounds maintenance workers generally do not need a formal educational credential. Some chemical-application tasks require state licensing, though, and a Pakistani licence or training certificate does not automatically authorise regulated work in the United States.</p>

<h2>Can Beginners Apply?</h2>

<p>Beginners should target vacancies that explicitly allow applicants without previous experience. General labour roles and specialist equipment roles can have different requirements.</p>

<p>Describe genuine skills such as outdoor work, manual handling, reliability and following instructions. If you have maintained gardens or worked in construction in Pakistan, explain your actual duties without presenting unrelated experience as professional landscaping. Ask whether training is provided, and do not claim experience with pesticides, chainsaws or machinery you have never used. Good training and supervision matter more than a CV that sounds impressive.</p>

<h2>Landscaper Salary in the USA</h2>

<p>The BLS reports these national estimates for landscaping and groundskeeping workers in May 2025:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Pay measure</th>
            <th style="padding:10px;text-align:left;">Official estimate</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Mean hourly wage</strong></td><td style="padding:10px;">$20.33</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median hourly wage</strong></td><td style="padding:10px;">$18.82</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Mean annual wage</strong></td><td style="padding:10px;">$42,290</td></tr>
    </tbody>
</table>
</div>

<p>The mean is the arithmetic average and the median is the midpoint. These statistics cover the occupation in general, not only H-2B workers.</p>

<p>At an illustrative $20 an hour for 40 paid hours a week, gross earnings are $800 a week, or about $3,467 in an average month. A seasonal contract pays only for the period it covers. Actual sponsored pay must meet the wage requirements that apply to the programme, so compare the written rate, hours, deductions and contract dates. A national average does not establish the required wage for your worksite. For a pay breakdown on the farm side, see <a href="/blog/farm-worker-salary-usa-2026">Farm Worker Salary in USA (2026)</a>.</p>

<h2>Housing, Travel and Recruitment Costs</h2>

<p>Do not assume H-2B landscaping jobs include free accommodation. Ask whether housing is offered, what it costs, which deductions apply and how transport works, and read those terms before you estimate savings.</p>

<p>Employers must follow the programme rules on required tools and prohibited recruitment charges. Workers should not pay the employer's recruitment, attorney or petition expenses. Inbound travel and subsistence costs are generally reimbursed after you complete half the employment period, though other wage-law duties can require reimbursement earlier. Return travel depends on contract completion, dismissal and later employment.</p>

<h2>How to Apply From Pakistan</h2>

<ol>
    <li>Search the official listings for landscaping, lawn care or groundskeeping.</li>
    <li>Check the duties, experience, employment dates and pay.</li>
    <li>Prepare a concise CV with truthful skills and availability.</li>
    <li>Follow the employer's published recruitment instructions.</li>
    <li>Ask whether it recruits applicants living in Pakistan.</li>
    <li>Confirm its certification and petition arrangements.</li>
    <li>After petition approval, follow the current visa application instructions.</li>
</ol>

<p>The Department of Homeland Security removed the eligible-country-list requirement with a rule that took effect on 17 January 2025. However, older State Department summaries still carry country-list wording. Confirm the current consular position with the US Embassy in Pakistan; the petition rule change does not guarantee a Pakistani applicant a visa. The full farm-side process is in <a href="/blog/how-to-get-farm-worker-job-usa-from-pakistan-2026">How to Get a Farm Worker Job in USA From Pakistan</a>, and the same steps apply here.</p>

<h2>Finding Landscaper Vacancies in 2026</h2>

<p>Check the start and end dates before you apply. Older orders can stay discoverable, and a visible listing does not prove recruitment is still open. Search by landscaping duties and location, then compare several employers. A higher hourly rate can leave you with lower savings if accommodation and other expenses are greater.</p>

<p>Before accepting, ask who supervises the crew, how workers travel between customer sites and what safety training is provided. Confirm whether driving, lifting or chemical handling is part of your daily work. Save the original advertisement, the contract and the employer's messages so you can compare any later changes. During employment, record your hours and review your payslips promptly. If the advertised role changes substantially, ask for a written explanation instead of assuming the new duties are authorised.</p>

<p>For the same trade in other countries, see <a href="/blog/landscaper-and-gardener-jobs-in-canada-and-the-uae">Landscaper and Gardener Jobs in Canada and the UAE</a>.</p>

<h2>Avoid Sponsorship Scams</h2>

<p>Verify the company, the worksite and the recruiter through independently confirmed employer contacts. Ask for written terms and keep your application records.</p>

<p>Guaranteed visas, unexplained charges, pressure to transfer money and a refusal to name the employer are warning signs. An official job order does not validate every person who claims to represent that employer, and no recruiter controls the consular decision.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is landscaping usually H-2A or H-2B?</h3>
<p>Qualifying temporary non-agricultural landscaping generally falls under H-2B. Agricultural roles need their own classification check.</p>

<h3>Is experience always required?</h3>
<p>No universal experience requirement applies to every vacancy. Read the job order.</p>

<h3>Does sponsorship lead to permanent residence?</h3>
<p>No. H-2B is a temporary work route and does not automatically lead to permanent residence.</p>

<h3>Is free housing compulsory?</h3>
<p>Do not assume it is included. Check the employer's written housing and deduction terms.</p>

<h3>Is there a limit on H-2B visas?</h3>
<p>Yes, a statutory annual limit applies, with separate supplemental allocations in some years. Check USCIS's current notices.</p>

<h3>Can Pakistani citizens get H-2B visas?</h3>
<p>The eligible-country lists were removed in January 2025, but you still need an employer that recruits from Pakistan, an approved petition and a visa decision.</p>

<h3>Should I pay an agent for a landscaping job in the USA?</h3>
<p>Be very careful. H-2B workers should not pay the employer's recruitment or petition costs, and nobody can guarantee a visa.</p>

<h3>Do landscapers need a licence?</h3>
<p>Not generally for basic grounds work, but some chemical-application tasks need state licensing.</p>

<h2>People Also Search For</h2>

<h3>Landscaper jobs in USA with visa sponsorship</h3>
<p>Seasonal landscaping work sponsored through the H-2B visa.</p>

<h3>Landscaper visa sponsorship USA</h3>
<p>The employer files first; the worker applies for the visa after approval.</p>

<h3>Landscaper jobs for foreigners in USA</h3>
<p>Open to foreign workers only through an eligible visa route.</p>

<h3>H-2B visa landscaper</h3>
<p>Temporary non-agricultural work, subject to an annual limit.</p>

<h3>Landscaper vacancies USA 2026</h3>
<p>Check start dates, because older orders stay visible.</p>

<h3>Apply for a landscaper job in the USA from Pakistan</h3>
<p>Ask whether the employer recruits in Pakistan and check consular instructions.</p>

<h3>Landscaper salary in USA</h3>
<p>$20.33 an hour on average in the BLS May 2025 estimates.</p>

<h3>Groundskeeper jobs in USA</h3>
<p>The BLS groups them with landscaping workers.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/farm-worker-jobs-usa-visa-sponsorship-2026">Farm Worker Jobs in USA With Visa Sponsorship (2026)</a> &mdash; the agricultural H-2A route.</li>
    <li><a href="/blog/how-to-get-farm-worker-job-usa-from-pakistan-2026">How to Get a Farm Worker Job in USA From Pakistan</a> &mdash; the step-by-step process.</li>
    <li><a href="/blog/landscaper-and-gardener-jobs-in-canada-and-the-uae">Landscaper and Gardener Jobs in Canada and the UAE</a> &mdash; the same trade elsewhere.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Wage figures are from the US Bureau of Labor Statistics, May 2025 national estimates; other information is drawn from the US Department of Labor, USCIS, the State Department and the Department of Homeland Security, reviewed on 8 October 2026. It does not confirm cap availability or that a visa will be issued to any individual applicant.</p>
HTML;
    }
}
