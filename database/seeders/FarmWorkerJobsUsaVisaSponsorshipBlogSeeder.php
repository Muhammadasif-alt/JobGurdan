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
 * "Farm Worker Jobs in USA With Visa Sponsorship (2026)".
 *
 * The brief was sound on H-2A and H-2B. What changed:
 *
 * 1. Ten identical Apply Now buttons to seasonaljobs.dol.gov are gone. The
 *    guide links only to our own pages; the job listing carries the one
 *    official link.
 *
 * 2. The illustrative hourly table is dropped. The salary guide covers pay with
 *    BLS figures read from the API.
 *
 * 3. "In September 2026 DOL announced possible future wage adjustments" could
 *    not be verified (the DOL pages refuse automated requests). The guide says
 *    only that the wage method has been challenged in court and tells readers
 *    to check DOL's current rates.
 *
 * 4. The brief had five FAQs; the house format is eight, with eight People Also
 *    Search For entries.
 *
 * 5. Pakistan: the DHS rule that removed the H-2 eligible-country lists took
 *    effect on 17 January 2025, so a nationality list no longer decides
 *    eligibility. Pakistan is not on the December 2025 entry-restriction lists,
 *    but those change, so the guide points readers to the current State
 *    Department position instead of asserting it.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class FarmWorkerJobsUsaVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'farm-worker-jobs-usa-visa-sponsorship-2026';

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
                'title' => 'Farm Worker Jobs in USA With Visa Sponsorship (2026): Requirements and How to Apply',
                'excerpt' => 'Farm worker jobs in the USA with visa sponsorship normally use the H-2A temporary agricultural visa. Here is who can apply, what the employer must provide, how Pakistani applicants should check an offer, and how to avoid recruitment scams.',
                'content' => $content,
                'featured_image' => 'blogs/farm-worker-jobs-usa-visa-sponsorship.jpg',
                'tags' => 'farm worker jobs usa, h-2a visa, farm worker visa sponsorship, farm jobs for foreigners usa, h-2b visa farm worker, seasonal farm jobs usa, farm worker jobs from pakistan, usa farm vacancies 2026',
                'meta_title' => 'Farm Worker Jobs in USA With Visa Sponsorship (2026)',
                'meta_description' => 'Farm worker jobs in the USA with visa sponsorship: H-2A rules, requirements, housing, how Pakistani applicants should apply and how to spot scams.',
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
            ['name' => 'US Agricultural Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'usa-farm-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            ['position' => 'Farm Worker — US Farms (H-2A Job Orders)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Seasonal, set by each job order',
                'language' => 'English',
                // Pay is set per job order under the H-2A wage rules; this site
                // does not republish job-board rates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Seasonal farm worker job orders on the US Department of Labor portal. Sponsorship runs through the H-2A visa and the employer must file first.',
                'seo_keywords' => 'farm worker jobs usa, h-2a job orders, seasonal jobs usa, farm worker visa sponsorship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>The US Department of Labor's seasonal jobs portal lists agricultural job orders from employers who plan to hire temporary workers under the H-2A programme.</p>

<h3>Requirements</h3>
<ul>
    <li>Each job order states its own duties, dates, location and experience requirements; read it before contacting the employer</li>
    <li>The employer must obtain labor certification and petition USCIS before you can apply for the visa</li>
    <li>An H-2A worker pays no recruitment or certification fees to the employer, and eligible workers who cannot return home daily receive housing at no cost</li>
</ul>

<p><strong>Note:</strong> visa and wage rules are set by the US Government, not by JobGader. This link opens the official listing portal; it is not an application form, and a visible order does not prove recruitment is still open.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Farm worker jobs in the USA with visa sponsorship normally use the <strong>H-2A temporary agricultural visa</strong>. You need an employer offering eligible seasonal work, an approved petition and a visa decision. Apply through verified job orders, check the written wage and contract, and follow the employer's recruitment instructions. Pay depends on the location and the occupation; there is no single nationwide farmworker salary. Pakistani applicants should verify current consular requirements before paying any visa expenses or arranging travel.</p>

<h2>Which Visa Covers Farm Work?</h2>

<p>H-2A covers temporary or seasonal agricultural employment. The employer first obtains the required labor certification from the Department of Labor, then petitions USCIS. Workers apply for their visas afterwards. Sending a CV does not create work authorisation.</p>

<p>The search phrase <strong>H-2B visa farm worker</strong> causes confusion. H-2B covers temporary <em>non-agricultural</em> work. A landscaping, processing or other seasonal vacancy needs its own classification checked; the word "farm" alone does not establish eligibility.</p>

<p>For farm worker visa sponsorship in the USA, confirm that the advertisement names agricultural duties, a real employer and the relevant job order. The official portal for these orders is the US Department of Labor's seasonal jobs listing, which the JobGader listing for this guide links to.</p>

<h2>Requirements for Foreign Applicants</h2>

<p>Requirements differ between vacancies. Read each job order instead of assuming every farm accepts beginners.</p>

<ul>
    <li>A valid passport and truthful identity information.</li>
    <li>Availability for the whole advertised employment period.</li>
    <li>Ability to perform the stated duties safely.</li>
    <li>Experience where the employer specifically requires it.</li>
    <li>Willingness to follow workplace and equipment instructions.</li>
    <li>The documents the visa application and interview ask for.</li>
</ul>

<p>A university degree is not a universal H-2A requirement. Tractor operation, livestock handling and other specialist tasks may require experience, though. State your actual skills; claiming machinery experience you do not have creates serious safety problems.</p>

<h2>Can Pakistani Applicants Apply?</h2>

<p>Distinguish employer recruitment from visa approval. The Department of Homeland Security removed the H-2 eligible-country-list requirement with a rule that took effect on 17 January 2025. An old nationality list on its own therefore no longer establishes whether a petition can be filed.</p>

<p>That change does not guarantee a Pakistani applicant a job or a visa. Ask whether the employer recruits applicants living in Pakistan, then check the State Department's current instructions for your consulate and any US entry restrictions in force. Petition approval, individual visa eligibility and admission at the border remain three separate decisions.</p>

<p>Do not resign or buy a non-refundable ticket because someone sends an offer letter. Get the contract, verify the employer and follow the official application sequence.</p>

<h2>How Much Do Farm Workers Earn?</h2>

<p>Use the vacancy's hourly rate and scheduled hours, not a number from a social post. H-2A pay must satisfy the applicable required wage for the occupation and worksite, and the Department of Labor publishes those rates. The method used to set them has been challenged in court, so check DOL's current figures alongside the job order rather than trusting an older table.</p>

<p>Salary benchmarks, the hourly-rate table by occupation and a month-by-month income worked example are in <a href="/blog/farm-worker-salary-usa-2026">Farm Worker Salary in USA (2026)</a>. Earnings also change with hours, contract length and lawful deductions, so a four-week example is not a calendar month.</p>

<h2>Housing, Transport and Contract Checks</h2>

<p>Eligible H-2A workers who cannot reasonably return home daily must receive employer-provided housing at no cost, and the employer must provide meals or cooking facilities. Required tools and daily transport between the housing and the worksite are also covered.</p>

<p>Inbound transport and subsistence costs are generally reimbursed once you complete half the contract; return transport depends on completing the contract or qualifying circumstances. Workers also receive a three-fourths guarantee: employment for at least 75% of the workdays in the contract period, rather than guaranteed full hours every week.</p>

<p>Read the written contract for wages, dates, location, deductions and working conditions. Ask about meals and any authorised charges before you accept.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/farm-worker-jobs-usa-visa-sponsorship-inline.jpg"
         alt="A smiling man and woman in work gloves picking vegetables and peppers into wooden crates on a farm, with a red barn, a tractor and the New York skyline behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply Step by Step</h2>

<ol>
    <li>Search the official portal for crops, harvest, farmworkers or equipment operators.</li>
    <li>Check the employment dates, location, duties, experience and pay.</li>
    <li>Prepare a concise CV listing agricultural skills and availability.</li>
    <li>Follow the contact instructions in the job order.</li>
    <li>Ask whether the employer recruits overseas and from your country.</li>
    <li>Confirm the employer's certification and petition process.</li>
    <li>After petition approval, follow the official DS-160 and interview instructions.</li>
    <li>Travel only after you receive the necessary authorisation.</li>
</ol>

<p>Keep a record of each application: the employer's name, the job order number, the contact person, the advertised wage and the date you applied. Save the original advertisement and every written reply. Before an interview, prepare examples of planting, harvesting, irrigation, animal care or machinery tasks you have actually done. Ask how workers receive safety training and who handles accommodation questions. Never send original identity documents to an unverified intermediary.</p>

<p>The official portal does not submit a visa application, confirm an open position or guarantee recruitment from Pakistan.</p>

<h2>Finding Suitable Vacancies in 2026</h2>

<p>Check the start date carefully. Expired listings can stay discoverable, and a visible order does not prove recruitment is still open. Useful searches include fruit harvesting, vegetable production, orchard work, nursery production and agricultural equipment operation. Check the classification and duties on every result.</p>

<p>Compare several farm worker jobs by contract length, required experience and total cost, and choose work you can do safely rather than the highest advertised hourly figure. For other countries, see <a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> and <a href="/blog/fruit-picking-and-farm-jobs-in-australia-canada-and-the-uk">Fruit Picking and Farm Jobs in Australia, Canada and the UK</a>.</p>

<h2>Avoid Recruitment Scams</h2>

<p>Verify the company name, the job order and the contact details independently. An official listing supports verification but does not validate every person claiming to represent that employer.</p>

<p>H-2A workers must not be charged employer recruitment or labor-certification costs. Ask for a written explanation of any payment request. A guaranteed visa, a transfer to a personal account and a refusal to provide a contract are all warning signs.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need previous farm experience?</h3>
<p>Only where the vacancy requires it. Beginner roles and equipment roles can have different requirements.</p>

<h3>Is sponsorship an offer of permanent residence?</h3>
<p>No. H-2A is a temporary work route and does not automatically lead to permanent residence.</p>

<h3>Can I apply without an agent?</h3>
<p>Yes, you can contact employers using the instructions in their job orders. The employer still has to take part in the petition process.</p>

<h3>Is accommodation always free?</h3>
<p>The H-2A housing duty covers eligible workers who cannot return home daily. Check your contract for the exact terms.</p>

<h3>Does an official job listing guarantee that I will be selected?</h3>
<p>No. Employers decide recruitment through their own stated process, and a listed order may already be filled.</p>

<h3>What is the difference between H-2A and H-2B?</h3>
<p>H-2A is for temporary agricultural work. H-2B is for temporary non-agricultural work, so a vacancy's duties decide which applies.</p>

<h3>Can Pakistani citizens get H-2A visas?</h3>
<p>The eligible-country lists were removed in January 2025, so nationality alone is not the test. You still need an employer who recruits from Pakistan, an approved petition and a visa decision.</p>

<h3>Should I pay an agent for a farm job in the USA?</h3>
<p>Be very careful. H-2A workers must not be charged employer recruitment or certification costs, and nobody can guarantee a visa.</p>

<h2>People Also Search For</h2>

<h3>Farm worker jobs in USA with visa sponsorship</h3>
<p>Seasonal agricultural work sponsored through the H-2A visa.</p>

<h3>Farm worker visa sponsorship USA</h3>
<p>The employer files first; the worker applies for the visa after approval.</p>

<h3>Farm worker jobs for foreigners in USA</h3>
<p>Open to foreign workers only through an eligible visa route and a real job order.</p>

<h3>H-2B visa farm worker</h3>
<p>H-2B covers non-agricultural seasonal work, so check the classification.</p>

<h3>Farm worker vacancies USA 2026</h3>
<p>Check start dates, because expired orders stay visible.</p>

<h3>Apply for a farm worker job in the USA from Pakistan</h3>
<p>Ask whether the employer recruits in Pakistan and check consular instructions.</p>

<h3>H-2A visa requirements</h3>
<p>A job order, labor certification, an approved petition and a visa interview.</p>

<h3>Farm worker salary in USA</h3>
<p>Benchmarks and worked examples are in the farm worker salary guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/farm-worker-salary-usa-2026">Farm Worker Salary in USA (2026)</a> &mdash; BLS averages, hourly rates and monthly examples.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the Canadian route in detail.</li>
    <li><a href="/blog/fruit-picking-and-farm-jobs-in-australia-canada-and-the-uk">Fruit Picking and Farm Jobs in Australia, Canada and the UK</a> &mdash; three more seasonal routes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Information is drawn from the US Department of Labor, the US Department of State and the Department of Homeland Security, reviewed on 7 October 2026. Immigration and wage rules change, so confirm them with the official authority before applying.</p>
HTML;
    }
}
