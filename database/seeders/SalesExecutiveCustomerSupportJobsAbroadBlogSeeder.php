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
 * "Sales Executive and Customer Support Jobs Abroad".
 *
 * The brief was already built on official sources, so it is kept close. What
 * changed:
 *
 * 1. Three Indeed "Apply Now" buttons for Dubai and repeated TTEC, Concentrix
 *    and Al-Futtaim links. The guide links only to our own pages; the job
 *    listings carry one official link each (Al-Futtaim, TTEC).
 *
 * 2. "TP states that it does not ask applicants to pay for equipment or
 *    employment opportunities" and the claim about TTEC's listings being
 *    labelled for the USA and Canada could not be confirmed, so the guide says
 *    only that outsourcing employers recruit by location. The Concentrix and
 *    TP pages are not linked: Concentrix returned 403 when checked and the TP
 *    page is its US section.
 *
 * 3. The brief's "remote does not mean worldwide" point is kept and made the
 *    centre of the remote listing.
 *
 * No pay figures are published; the brief had none.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class SalesExecutiveCustomerSupportJobsAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'sales-executive-and-customer-support-jobs-abroad';

    private const UAE_APPLY_URL = 'https://www.alfuttaim.com/en/careers/';

    private const REMOTE_APPLY_URL = 'https://www.ttecjobs.com/en/work-from-home';

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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Sales Executive and Customer Support Jobs Abroad',
                'excerpt' => 'Dubai sales roles need a work permit the employer arranges, and most remote customer service jobs only accept applicants from named countries. How to check your location, the commission plan and the offer before you apply.',
                'content' => $content,
                'featured_image' => 'blogs/sales-executive-customer-support-jobs-abroad.jpg',
                'tags' => 'sales executive jobs dubai, customer service jobs remote, telesales jobs abroad, work from home customer support, field sales jobs uae, sales commission plan, remote jobs for foreigners, customer support jobs abroad',
                'meta_title' => 'Sales Executive and Customer Support Jobs Abroad',
                'meta_description' => 'Sales executive jobs in Dubai and remote customer service jobs: skills, location limits, commission, work permits and how to check an offer.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $listings = [
            [
                'advertiser' => ['UAE Sales Employers (Aggregated)', 'uae-sales-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Sales Executive — UAE Employers (Work Permit Arranged by Employer)',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Office or field-based; varies by employer',
                'language' => 'English',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Sales executive roles with UAE employers. Ask for the fixed salary and the commission plan separately, and confirm the work permit in writing.',
                'keywords' => 'sales executive jobs dubai, field sales uae, telesales dubai, sales jobs uae',
                'category' => ['sales', 'Sales'],
                'job_type' => 'On-site',
            ],
            [
                'advertiser' => ['Remote Customer Support Employers (Aggregated)', 'remote-support-aggregated'],
                'location' => ['Remote', 'Remote'],
                'position' => 'Remote Customer Support Representative — Employer-Specified Countries (Check Your Location)',
                'apply' => self::REMOTE_APPLY_URL,
                'hours' => 'Shift-based; check the time zone',
                'language' => 'English',
                'description' => $this->remoteJobDescription(),
                'meta' => 'Remote customer support roles. Most employers restrict which countries you may work from, so check your location before applying.',
                'keywords' => 'customer service jobs remote, work from home customer support, remote chat support, remote call centre jobs',
                'category' => ['customer-support-admin', 'Customer Support & Admin'],
                'job_type' => 'Remote',
            ],
        ];

        foreach ($listings as $listing) {
            $category = Category::firstOrCreate(
                ['slug' => $listing['category'][0]],
                ['name' => $listing['category'][1]]
            );

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
                    'job_type' => $listing['job_type'],
                    'work_hours' => $listing['hours'],
                    'language' => $listing['language'],
                    // Pay depends on the employer and the contract; this site
                    // does not republish job-board rates.
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

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>UAE employers across retail, automotive, property, business services and technical sales recruit sales executives. The link goes to the careers page of Al-Futtaim, one large UAE employer with several divisions.</p>

<h3>Requirements</h3>
<ul>
    <li>Experience and language skills that vary by sector; field roles may need a driving licence</li>
    <li>A work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs</li>
</ul>

<p><strong>Note:</strong> some positions have nationality requirements, and compensation and permit rules are set by the employer and the UAE authorities, not by JobGader. Ask for the fixed salary and commission plan in writing.</p>
JOBHTML;
    }

    private function remoteJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Large outsourcing employers recruit remote customer support representatives for calls, chat and email. The link goes to TTEC's work-from-home careers page as a starting point.</p>

<h3>Requirements</h3>
<ul>
    <li>Residence in a country the employer accepts for the role: "remote" is usually limited to specified countries or regions, not worldwide</li>
    <li>Work authorisation and payroll location that match the vacancy</li>
    <li>Equipment and internet requirements, shift time zone and whether you would be an employee or a contractor</li>
</ul>

<p><strong>Note:</strong> a global careers page lists remote, hybrid and office roles, so confirm the working arrangement for each vacancy. Eligibility is set by the employer, not by JobGader.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You can find sales executive and customer support jobs abroad through employer career pages and job boards. For Dubai sales roles, check the experience, language skills, salary, commission and employment authorisation. For remote customer service positions, confirm which countries or regions you are allowed to work from. "Remote" often means working from home inside a specified location, not from anywhere in the world. Apply only when you meet the vacancy's requirements: neither a job advert nor a company's international presence guarantees sponsorship or permission to work.</p>

<h2>Sales or Customer Support: Which Fits You?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Main responsibilities</th>
            <th style="padding:10px;text-align:left;">Useful strengths</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Sales executive</strong></td><td style="padding:10px;">Finding prospects, explaining products, following leads and closing business</td><td style="padding:10px;">Persuasion, product knowledge and target management</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Customer support representative</strong></td><td style="padding:10px;">Answering questions, resolving issues and documenting interactions</td><td style="padding:10px;">Listening, accuracy, patience and clear communication</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Telesales representative</strong></td><td style="padding:10px;">Contacting prospects and managing sales conversations by telephone</td><td style="padding:10px;">Confident speaking and organised follow-up</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Chat or email support</strong></td><td style="padding:10px;">Handling written enquiries and keeping case records</td><td style="padding:10px;">Writing, typing and problem solving</td></tr>
    </tbody>
</table>
</div>

<p>Read the duties carefully. Some customer service roles carry sales targets, and some sales positions need regular after-sales support.</p>

<h2>What Do Sales Executive Jobs in Dubai Require?</h2>

<p>Requirements vary by sector. Retail, business services, automotive, property and technical sales involve different customers and product knowledge, and an employer may ask for earlier sales experience, relevant education, language skills or familiarity with customer relationship management (CRM) software. Use accurate examples: describe how you qualified leads, kept records, explained products and followed up, and include figures only if you can substantiate them. A clear account of your sales process is worth more than unsupported claims of exceptional performance.</p>

<p>Field sales roles may involve travel or need a suitable driving licence. Ask whether transport is supplied, whether expenses are reimbursed and how much time is spent visiting clients. Do not assume every Dubai sales job is office based.</p>

<p>Ask for the fixed salary and the commission plan separately. Find out what triggers commission, when it is paid and how cancellations, refunds or unpaid customer invoices affect your earnings, and check whether targets change during probation and whether the role is commission-only. Advertised earning potential is not guaranteed pay, so compare dependable income with accommodation, food and transport costs and get the important terms in writing before you relocate.</p>

<p>UAE private sector employment needs the applicable work permit, and the employer completes the authorisation process. A tourist or visit visa does not allow you to work, and a sales offer alone does not give you permission to start. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly. Ask who manages the employment documents, the residency arrangements and your proposed start date, and verify the employer and written offer through official channels. Al-Futtaim, for example, runs a careers page that gives access to recruitment across its divisions; select a vacancy that matches your experience and eligibility, because some positions have specific nationality requirements. For comparison, see <a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/sales-executive-customer-support-jobs-abroad-inline.jpg"
         alt="A smiling customer support agent with a headset at a laptop in a bright home office, with a world map, a passport and an airplane beside her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Do Remote Customer Service Jobs Require?</h2>

<p>Location comes first. A remote vacancy can name a country, state, city or approved employment region, and large outsourcing employers such as TTEC and Concentrix organise recruitment by location. Someone living elsewhere cannot assume eligibility just because the duties are done online. Check residency, work authorisation, the payroll location and whether the employer accepts applicants in your country. Moving abroad for a job and working remotely from your current home are different arrangements.</p>

<p>Customer support can involve calls, chat, email or several channels. Employers may assess your language ability, writing, typing, computer skills and handling of difficult conversations, and technical support roles can need extra troubleshooting knowledge. Review the education and experience in each advert. Some companies provide training, but that does not mean every position accepts beginners. Prepare examples that show how you listened, checked information, explained a solution and escalated an issue properly. See also <a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> and <a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a>.</p>

<p>Ask about equipment, internet specifications, workspace requirements, training attendance and technical support. A laptop and a home connection may not satisfy every employer's security or performance standards, so clarify whether equipment is provided or approved personal equipment is allowed. Check the shift's time zone and any weekend or overnight duties, because remote work does not necessarily mean flexible hours, and ask how breaks, attendance, monitoring and performance measures are handled before you accept.</p>

<p>A global career page can include remote, hybrid and office roles, so confirm the working arrangement for each vacancy, and review the country filters before you apply. Search for "customer service remote" only after you have checked that your country is allowed.</p>

<h2>How Do I Build a Focused Application?</h2>

<p>Prepare a readable CV, a professional email address, your employment history and references. For sales, highlight your sector and your sales responsibilities. For support, highlight the channels you have used, your problem solving, the software you know and your accurate record keeping. Match the essential criteria honestly, and state your current location, availability, languages and work permission. Never include confidential customer information when you describe achievements, and practise a short sales conversation or support reply relevant to the role.</p>

<h2>How Do I Check the Offer Before Accepting?</h2>

<p>Ask whether you would be an employee or an independent contractor, and which company signs the agreement. Confirm the working hours, currency, payment schedule, probation, leave, equipment responsibilities and any deductions. For sales, ask for worked examples of how commission is calculated and paid. Keep a tracker with vacancy references and recruiter contact details, and verify unexpected messages through the employer's official website. A genuine employer does not ask you to pay for a job, and you should not send money in response to an unverified recruitment message. Keep copies of the offer, the contract and written answers.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/sales" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Sales Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply?</h3>
<p>Some vacancies accept beginners or transferable experience. Check the essential criteria and training arrangements for each role.</p>

<h3>Does remote mean worldwide?</h3>
<p>No. Many roles restrict where employees may live and work, so confirm the permitted location before you apply.</p>

<h3>Are Dubai sales salaries always fixed?</h3>
<p>No. Offers can combine fixed pay and commission or use another structure. Ask for the complete written compensation plan.</p>

<h3>Is sponsorship guaranteed?</h3>
<p>No. Ask the employer about the specific vacancy and verify the immigration requirements.</p>

<h3>Can I work in Dubai on a visit visa?</h3>
<p>No. You need a work permit arranged by the employer.</p>

<h3>Who pays recruitment costs for a UAE sales job?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars it from charging you recruitment and employment costs.</p>

<h3>Am I an employee or a contractor in a remote role?</h3>
<p>It depends on the offer. Ask which company signs the agreement and who pays for equipment.</p>

<h3>What is a commission-only sales job?</h3>
<p>A role with no fixed salary, where your income depends on what you sell. Check for this before you relocate.</p>

<h2>People Also Search For</h2>

<h3>Sales executive jobs Dubai</h3>
<p>Check sector, experience, fixed salary, commission and work permit.</p>

<h3>Customer service jobs remote</h3>
<p>Confirm the countries the employer accepts before applying.</p>

<h3>Telesales jobs abroad</h3>
<p>Phone-based sales roles; ask about targets and commission rules.</p>

<h3>Work from home customer support</h3>
<p>Check equipment, internet and shift time zone.</p>

<h3>Field sales jobs UAE</h3>
<p>Ask about transport, expenses and client visits.</p>

<h3>Sales commission plan</h3>
<p>Ask what triggers commission and how refunds affect it.</p>

<h3>Chat support jobs</h3>
<p>Written enquiries and case records; typing and writing matter.</p>

<h3>Remote jobs for foreigners</h3>
<p>Work location and work authorisation decide eligibility.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; how remote support hiring works.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the Canadian route.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; another sales market.</li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a> &mdash; another UAE office role.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UAE rules are from u.ae, reviewed on 6 October 2026. Career pages do not guarantee openings, worldwide remote eligibility or sponsorship, and conditions change, so confirm them with the employer before applying.</p>
HTML;
    }
}
