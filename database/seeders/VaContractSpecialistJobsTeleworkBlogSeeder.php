<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "VA Contract Specialist Jobs (Telework) 2026: Vacancies, Requirements, Salary and How to Apply"
 *
 * Checked on 9 November 2026. What changed against the brief:
 *
 * 1. The emoji buttons, "[ADD REAL SOURCE]" notes and the job-ID links (USAJOBS control numbers and an ApplyStart link)
 *    are dropped; the guide gives the announcement and control numbers as text and links USAJOBS itself.
 * 2. GS-12 announcement CCLQ-13052501-26-ZAS (control 882891000) read: open 31 August 2026 to 1 March 2027, open
 *    continuous, $89,508-$116,362, "Remote job: No", "THIS IS NOT A VIRTUAL POSITION OR REMOTE POSITION", return to
 *    office, telework may be authorised and discussed at interview, Monday-Friday 8:00am-4:30pm, US citizens, Direct-Hire
 *    Authority, High Risk, financial disclosure, no clearance, 1-year probation, union, no relocation, promotion
 *    potential none, degree or 24 semester hours, one year at GS-11, USA Hire with a cut score.
 * 3. The brief said the GS-13 (control 882892900) was Fredericksburg only with no salary found. It lists many locations
 *    and $106,437-$138,370, open 31 August 2026 to 1 March 2027, first referral cut-off 8 September 2026 then every
 *    two weeks.
 * 4. The two-page resume date and the 3-hour USA Hire figure were not on the pages read and are dropped.
 *
 * Posters are the owner's (blog-360, 9 Nov 9.43 to 9.45 PM); they say "work from home", which the text corrects.
 * The portal listing is in GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class VaContractSpecialistJobsTeleworkBlogSeeder extends Seeder
{
    public const SLUG = 'va-contract-specialist-jobs-telework-2026';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'VA Contract Specialist Jobs (Telework) 2026: Vacancies, Requirements, Salary and How to Apply',
                'excerpt' => 'VA is hiring GS-1102 Contract Specialists, but the announcements say "Remote job: No" and "not a virtual position". GS-12 pays $89,508 to $116,362 and GS-13 $106,437 to $138,370, open to US citizens until 1 March 2027.',
                'content' => $content,
                'featured_image' => 'blogs/va-contract-specialist-jobs-telework-2026.jpg',
                'tags' => 'va contract specialist jobs, gs-1102 contract specialist, va contract specialist salary, va telework jobs, usajobs contract specialist, federal contract specialist requirements, direct hire authority va, usa hire assessment',
                'meta_title' => 'VA Contract Specialist Jobs 2026: Salary, Requirements',
                'meta_description' => 'VA Contract Specialist jobs 2026: not remote, telework rules, GS-12 and GS-13 salary, requirements, open locations and how to apply on USAJOBS.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>The Department of Veterans Affairs (VA) is hiring Contract Specialists (series GS-1102) across the United States through USAJOBS, but these are not remote jobs.</strong> Both announcements we checked on 9 November 2026 say "Remote job: No" and, in capital letters, "THIS IS NOT A VIRTUAL POSITION OR REMOTE POSITION", requiring you to work on site at VA space under its return-to-office policy. Telework "may be authorized" and is discussed at interview. The GS-12 role pays <strong>$89,508 to $116,362</strong> a year and the GS-13 role <strong>$106,437 to $138,370</strong>, depending on duty location. Only US citizens can apply, and both close on 1 March 2027.</p>

<h2>Are VA Contract Specialist Jobs Remote?</h2>

<p>No, and this matters because many websites label them "remote vacancies". The official GS-12 announcement says:</p>

<ul>
<li><strong>Remote job:</strong> No</li>
<li><strong>Telework eligible:</strong> "Yes, as determined by the agency policy"</li>
<li><strong>Telework:</strong> "This position may be authorized for telework", with eligibility "discussed during the interview process"</li>
<li><strong>Virtual:</strong> "This is not a virtual position"</li>
<li><strong>Schedule:</strong> Monday to Friday, 8:00am to 4:30pm</li>
</ul>

<p>So expect an office job with, at most, some telework agreed by your manager. Do not accept an offer expecting full-time home working unless the hiring office confirms it in writing. Official site: <a href="https://www.usajobs.gov/" rel="noopener">USAJOBS</a>, search "Contract Specialist" and the Department of Veterans Affairs.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/va-contract-specialist-jobs-telework-2026-2.jpg"
         alt="Contract specialist at a laptop with a VA mug in front of a VA office building, VA Contract Specialist Jobs Telework 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Does a VA Contract Specialist Do?</h2>

<p>You buy the supplies and services VA needs, following the Federal Acquisition Regulation (FAR) and VA's own acquisition rules. The work includes market research, reviewing specifications and statements of work, preparing solicitations, negotiating contracts, analysing cost and price proposals, and administering contracts after award, including change orders and performance problems. It is detailed, rules-heavy work with real money at stake.</p>

<h2>The Two Open VA Announcements</h2>

<p><strong>GS-12, announcement CCLQ-13052501-26-ZAS (control number 882891000).</strong> Pays $89,508 to $116,362 a year. Open from 31 August 2026 to 1 March 2027 as an open continuous announcement, with many vacancies at locations including Long Beach, Golden, Houston, San Antonio, Fredericksburg, Milwaukee and Cheyenne. Promotion potential is listed as none, and relocation costs are not paid.</p>

<p><strong>GS-13 (control number 882892900), VA Office of Procurement.</strong> Pays $106,437 to $138,370 a year, also across many locations, open from 31 August 2026 to 1 March 2027. The first referral cut-off was 8 September 2026, with further cut-offs every two weeks.</p>

<p>"Open continuous" means VA sends batches of qualified applicants to hiring managers at each cut-off, so applying early gives you more chances to be considered.</p>

<h2>VA Contract Specialist Salary Per Month</h2>

<p>Dividing the annual ranges by twelve, before tax:</p>

<ul>
<li><strong>GS-12:</strong> about $7,459 to $9,697 a month</li>
<li><strong>GS-13:</strong> about $8,870 to $11,531 a month</li>
</ul>

<p>Where you land in the range depends on the duty location, because federal pay includes a locality adjustment. The announcement describes a full-time, permanent post with federal benefits.</p>

<h2>Who Can Apply?</h2>

<p><strong>Citizenship.</strong> You must be a US citizen. Applicants who are not US citizens, including those living in Pakistan, cannot apply.</p>

<p><strong>Education.</strong> For GS-1102 you need either a bachelor's degree in any field, or at least 24 semester hours in accounting, business, finance, law, contracts, purchasing, economics, industrial management, marketing, quantitative methods or organisation and management.</p>

<p><strong>Specialised experience.</strong> For GS-12, one year of full-time experience equivalent to GS-11, such as recommending contract types, applying acquisition law and policy, reviewing vendor submissions or judging whether a vendor is responsible.</p>

<p><strong>Other conditions:</strong></p>

<ul>
<li>A "High Risk" background check and financial disclosure</li>
<li>No security clearance required and no drug test</li>
<li>A one-year probationary period</li>
<li>Union-represented position in the competitive service</li>
<li>An online USA Hire assessment with a minimum cut score</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/va-contract-specialist-jobs-telework-2026-3.jpg"
         alt="Smiling contract specialist at a desk with a laptop and VA mug, VA Contract Specialist Jobs Telework 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Direct-Hire Authority and Veterans</h2>

<p>The GS-12 role is filled using Direct-Hire Authority and is open to all US citizens. Under direct hire, the usual veterans' preference ranking does not apply in the same way, but veterans who qualify are considered like any other applicant. Current federal employees eligible for career transition programmes (CTAP or ICTAP) should submit the documents the announcement asks for. Under the Fair Chance to Compete Act, VA does not ask about criminal history before a tentative offer.</p>

<h2>How to Apply on USAJOBS</h2>

<ol>
<li><strong>Create or sign in</strong> to your USAJOBS account.</li>
<li><strong>Find the announcement</strong> by its number (CCLQ-13052501-26-ZAS for GS-12) or by searching "Contract Specialist" with the Department of Veterans Affairs.</li>
<li><strong>Write a federal-style resume.</strong> For each job, include your title, duties, start and end month and year, and hours worked per week; VA will not assume experience you have not described. Check the announcement for any page limit.</li>
<li><strong>Answer the questionnaire</strong> honestly.</li>
<li><strong>Attach documents</strong> if needed: transcripts if you qualify on education, SF-50 if you are a federal employee, DD-214 if you are a veteran.</li>
<li><strong>Submit before the cut-off</strong> you are aiming for, then complete the USA Hire assessment when invited.</li>
<li><strong>Track your status</strong> in your USAJOBS account.</li>
</ol>

<h2>The USA Hire Assessment</h2>

<p>USA Hire tests competencies such as reasoning, reading comprehension, writing, decision making, attention to detail, self-management, stress tolerance and teamwork. You need to meet or exceed a cut score to be referred. Overstating your experience or cheating can remove you from consideration.</p>

<h2>How to Strengthen Your Application</h2>

<ul>
<li>Use the announcement's language where it is true: "contract administration", "cost and price analysis", "FAR".</li>
<li>Quantify your work: contract values, number of actions and contract types (firm-fixed-price, cost-reimbursement).</li>
<li>Name contracting systems you have actually used.</li>
<li>Upload a transcript if you are relying on education.</li>
</ul>

<h2>Avoid Job Scams</h2>

<p>Apply only through usajobs.gov. Federal agencies do not charge application fees. Be wary of social media posts promising a "remote VA job" in exchange for money, and remember that these particular jobs are not remote at all.</p>

<h2>Frequently Asked Questions</h2>

<h3>Are VA Contract Specialist jobs remote?</h3>
<p>No. Both announcements say "Remote job: No" and that the role is not virtual; telework may be authorised.</p>

<h3>What is the salary for a VA GS-12 Contract Specialist?</h3>
<p>$89,508 to $116,362 a year, adjusted for duty location. The GS-13 role pays $106,437 to $138,370.</p>

<h3>Can non-US citizens apply?</h3>
<p>No. US citizenship is required.</p>

<h3>Do I need a degree?</h3>
<p>Either a bachelor's degree in any field or 24 semester hours in business-related subjects, plus one year of GS-11-equivalent experience for GS-12.</p>

<h3>When do the applications close?</h3>
<p>Both close on 1 March 2027, but referrals happen at regular cut-offs, so apply early.</p>

<h2>Bottom Line</h2>

<p>If you are a US citizen with contracting experience and can work on site, these are well-paid, stable federal roles. If you need a fully remote job, look elsewhere, because VA's own announcements rule it out.</p>

<h2>Sources</h2>

<ol>
<li>USAJOBS: VA Contract Specialist GS-12, announcement CCLQ-13052501-26-ZAS, control number 882891000</li>
<li>USAJOBS: VA Office of Procurement Contract Specialist GS-13, control number 882892900</li>
<li><a href="https://www.usajobs.gov/" rel="noopener">USAJOBS</a>, all checked 9 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/global-remote-security-specialist-jobs-us-2026">Remote Security Specialist Jobs in the US</a>, roles that really are remote.</li>
    <li><a href="/blog/carfax-remote-us-technology-jobs-2026">CARFAX Technology Jobs</a>, another "remote or hybrid" check.</li>
    <li><a href="/blog/remote-jobs-2026-latest-vacancies">Remote Jobs 2026</a>, where you are allowed to work from.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
