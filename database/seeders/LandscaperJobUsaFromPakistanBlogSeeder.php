<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How to Get a Landscaper Job in USA From Pakistan (2026)".
 *
 * Built on the farm worker "from Pakistan" guide, with the H-2B facts of the
 * landscaper visa guide. What changed against the brief:
 *
 * 1. Ten Apply Now buttons are gone; the guide links only to our own pages and
 *    the one official link sits on the landscaper job listing.
 *
 * 2. The brief tells readers to check the H-2B numerical limits but names no
 *    position. The FY2026 supplemental allocation stopped taking petitions
 *    after 15 September 2026, so the guide says so.
 *
 * 3. The brief had five FAQs; the house format is eight, with eight People Also
 *    Search For entries.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class LandscaperJobUsaFromPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-get-landscaper-job-usa-from-pakistan-2026';

    public function run(): void
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
                'title' => 'How to Get a Landscaper Job in USA From Pakistan: Step-by-Step Guide (2026)',
                'excerpt' => 'Pakistani applicants cannot buy a landscaper visa on their own. Find a verified US employer, ask whether it recruits from Pakistan, and follow the H-2B petition and visa steps in order. Here is the process, with scam warnings.',
                'content' => $content,
                'featured_image' => 'blogs/landscaper-job-usa-from-pakistan.jpg',
                'tags' => 'landscaper job usa from pakistan, landscaper job usa for pakistanis, landscaper work visa usa, landscaper job without experience usa, find landscaper job abroad, landscaper usa requirements, h-2b visa pakistan, how to apply h-2b',
                'meta_title' => 'Landscaper Job in USA From Pakistan: 9-Step Guide (2026)',
                'meta_description' => 'How Pakistani applicants can find US landscaping employers, check H-2B requirements, prepare a CV and follow the job and visa application steps in order.',
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
<p>To get a landscaper job in the USA from Pakistan, <strong>find a verified employer, confirm that it recruits overseas applicants and follow its eligible H-2B hiring process</strong>. The employer handles the required labor certification and the USCIS petition; you follow the current visa instructions after the petition is approved. Beginners should apply only where the vacancy allows their experience level. A CV, an offer letter or a payment to a recruiter does not guarantee work authorisation, so confirm the Pakistan-specific consular requirements before you commit money or arrange travel.</p>

<h2>Step 1: Understand the Landscaper Work Visa Route</h2>

<p>Qualifying temporary non-agricultural landscaping can use <strong>H-2B</strong>. Agricultural work generally uses H-2A, so check the actual duties instead of relying on the word "gardener" or "landscaper" alone.</p>

<p>H-2B is employer-led and has conditions, including numerical limits. For fiscal year 2026, USCIS stopped accepting petitions under the supplemental allocation after 15 September 2026, so ask whether the employer can support your proposed employment dates; a past sponsorship record does not confirm a present opening. Do not plan to enter as a tourist and start work. Get the correct authorisation before you begin employment. The route, pay and cap position are covered in <a href="/blog/landscaper-jobs-usa-visa-sponsorship-2026">Landscaper Jobs in USA With Visa Sponsorship (2026)</a>.</p>

<h2>Step 2: Confirm Recruitment From Pakistan</h2>

<p>Employer recruitment and visa issuance are separate checks. Ask directly whether the company considers applicants living in Pakistan.</p>

<p>The Department of Homeland Security removed the H-2 eligible-country-list requirement with a rule that took effect on 17 January 2025. Older State Department summaries still carry country-list wording, so confirm the current consular position with the US Embassy in Pakistan instead of treating an old list or a recruiter's promise as final. This rule change does not guarantee selection, petition approval or a visa.</p>

<h2>Step 3: Check the Landscaper Requirements</h2>

<p>Read the complete vacancy requirements. Important points include:</p>

<ul>
    <li>Employment dates and your availability.</li>
    <li>The landscaping experience required.</li>
    <li>Physical tasks and your ability to work safely.</li>
    <li>Mower, trimmer or other equipment experience.</li>
    <li>Driving duties and any required qualifications.</li>
    <li>Ability to understand workplace instructions.</li>
    <li>Accurate identity details and valid travel documents.</li>
</ul>

<p>Grounds maintenance workers generally do not need a formal educational credential and commonly learn on the job. States may require licences for pesticide or fertiliser application, and a Pakistani credential does not automatically authorise regulated tasks in the United States.</p>

<h2>Step 4: Find Jobs Without Previous Experience</h2>

<p>If you search for a landscaper job without experience, look for orders that explicitly allow beginners. A short advertisement that says "labourer" does not prove experience is unnecessary.</p>

<p>Search terms can include lawn maintenance, planting, mulching, groundskeeping and landscape labour. These are suggestions, not confirmed beginner vacancies. Explain genuine transferable skills such as outdoor work, reliability and following instructions, and ask what training and supervision are provided. Never claim chainsaw, pesticide or machinery skills you lack, and describe general garden experience accurately instead of presenting it as specialist employment.</p>

<h2>Step 5: Prepare Your CV and First Message</h2>

<p>Write a concise CV with your contact details, your location in Pakistan, your work history, relevant tasks and your availability. Include gardening or construction experience only where it is genuine and relevant.</p>

<p>Use this message as a starting point:</p>

<blockquote>I am based in Pakistan and interested in your advertised landscaping position. My relevant skills are [details], and I am available from [date]. Do you recruit applicants residing in Pakistan through the H-2B process?</blockquote>

<p>Replace the brackets with truthful information and follow the application method stated in the job order. Record the employer's name, the job order number, the date you applied and the reply.</p>

<h2>Step 6: Verify the Employer and the Written Offer</h2>

<p>Match the company name, worksite, duties and contact details against the official job order, and independently confirm anyone who claims to represent the employer. Read the written terms for hourly pay, scheduled hours, overtime, dates and disclosed deductions, and ask about accommodation, travel between customer sites, required equipment and supervision.</p>

<p>Do not assume H-2B landscaping includes free housing; ask for the cost and the deduction arrangements before you calculate savings. A published order supports verification, but it does not prove that every recruiter who contacts you is authorised. Pay benchmarks and worked examples are in <a href="/blog/landscaper-salary-usa-2026">Landscaper Salary in USA (2026)</a>.</p>

<h2>Step 7: Understand the Employer's Sponsorship Steps</h2>

<p>The employer obtains the required Department of Labor labor certification and files its petition with USCIS. A personal visa application does not replace those employer steps. Ask the employer for the petition information you need for your own application, keep your correspondence and do not send original identity documents to an unverified intermediary.</p>

<p>Treat employment dates as planning information, not a guaranteed arrival deadline. Petition processing, consular appointments and visa decisions all affect timing, and no recruiter controls them.</p>

<h2>Step 8: Follow the Visa and Interview Instructions</h2>

<p>After the petition is approved, follow the current instructions of the US embassy or consulate that handles your application. The process typically involves the DS-160 form, an appropriate photograph, appointment arrangements and supporting information.</p>

<p>Prepare your passport, the DS-160 confirmation page, the petition receipt details and any fee receipt or extra evidence requested. Answer questions truthfully about your employer, your work and your temporary stay. Do not buy a non-refundable ticket before the visa decision, and remember that a visa does not guarantee admission at the border.</p>

<h2>Step 9: Check Costs and Prepare for Arrival</h2>

<p>When you try to find a landscaper job abroad, ask for a written breakdown of expenses. H-2B workers must not pay the employer's prohibited recruitment, attorney or petition costs, and required job tools are subject to employer obligations. Ask how travel costs and reimbursements are handled under the applicable rules, and confirm the accommodation, the pickup arrangements and your supervisor's contact details.</p>

<p>Carry accessible copies of your contract and documents, keep your own record of hours, review your payslips and ask promptly about any unexpected deduction. Budget from the actual contract rather than a national salary headline.</p>

<p>Prepare for an employer interview by explaining the tasks you have actually completed, the tools you can safely use and when you are available. Ask whether the crew works at one property or moves between customer sites, who provides protective equipment and how unfamiliar tasks are taught. If you cannot meet a listed requirement, say so clearly instead of promising to learn everything at once, and save the written answers to important questions.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply directly without an agent?</h3>
<p>You can contact employers through their published instructions. The employer still has to take part in the immigration process.</p>

<h3>Is previous experience compulsory?</h3>
<p>Only where the job order requires it. Beginners should target suitable vacancies.</p>

<h3>Do I need IELTS for every landscaping job?</h3>
<p>Do not assume a universal IELTS requirement. Follow the vacancy and the official visa instructions.</p>

<h3>Does sponsorship lead to permanent residence?</h3>
<p>No. H-2B is temporary work authorisation, not automatic permanent residence.</p>

<h3>Does the official job portal submit my application?</h3>
<p>No. It lists job orders. Select one and follow that employer's recruitment instructions.</p>

<h3>Can I work on a tourist visa?</h3>
<p>No. Do not start work without the correct authorisation.</p>

<h3>Should I pay an agent?</h3>
<p>Be very careful. H-2B workers must not pay the employer's recruitment or petition costs, and nobody can guarantee a visa.</p>

<h3>Is there a limit on H-2B visas?</h3>
<p>Yes, an annual statutory limit applies, with supplemental allocations in some years. Check USCIS's current notices.</p>

<h2>People Also Search For</h2>

<h3>How to get a landscaper job in USA from Pakistan</h3>
<p>Find a verified employer, ask about Pakistan recruitment and follow the H-2B steps in order.</p>

<h3>Landscaper job USA for Pakistanis</h3>
<p>Possible only where an employer recruits from Pakistan and the visa is approved.</p>

<h3>Landscaper work visa USA</h3>
<p>H-2B, sought through an employer's petition.</p>

<h3>Landscaper job without experience USA</h3>
<p>Look for orders that explicitly accept beginners.</p>

<h3>Find landscaper job abroad</h3>
<p>Use official job listings and verify every recruiter.</p>

<h3>Landscaper USA requirements</h3>
<p>Set by each job order, plus visa conditions.</p>

<h3>H-2B visa for Pakistan</h3>
<p>The country lists are gone, but consular approval is still individual.</p>

<h3>Landscaper salary in USA</h3>
<p>Benchmarks and worked examples are in the landscaper salary guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/landscaper-jobs-usa-visa-sponsorship-2026">Landscaper Jobs in USA With Visa Sponsorship (2026)</a> &mdash; H-2B rules and requirements.</li>
    <li><a href="/blog/landscaper-salary-usa-2026">Landscaper Salary in USA (2026)</a> &mdash; BLS averages and monthly examples.</li>
    <li><a href="/blog/how-to-get-farm-worker-job-usa-from-pakistan-2026">How to Get a Farm Worker Job in USA From Pakistan</a> &mdash; the agricultural route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Information is drawn from the US Department of Labor, USCIS, the US Department of State, the Department of Homeland Security and the Bureau of Labor Statistics, reviewed on 8 October 2026. It does not confirm that a visa will be issued to any individual applicant, so check the US Embassy in Pakistan before you pay for anything.</p>
HTML;
    }
}
