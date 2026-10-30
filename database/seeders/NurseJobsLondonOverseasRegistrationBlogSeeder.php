<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Nurse Jobs in London, England for Overseas Nurses: Registration Steps"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The brief's three Indeed search buttons are dropped; NHS Jobs, Imperial, NMC and GOV.UK stay as links.
 * 2. NMC costs page fetched: fees increased on 1 October 2026; evaluation £167, CBT £83 (full resit £83, clinical-only
 *    resit £70, numeracy-only £50), OSCE £794 (full resit £794 for eight or more failed stations, partial £397 for up to
 *    seven), registration £182, total £1,226. The step-by-step page still showed £140 for evaluation, so the guide uses
 *    the costs page and says so.
 * 3. NMC English page fetched: IELTS Academic 7, 7, 6.5 (writing), 7; OET B (350) and C+ (300) for writing; these are the
 *    only two accepted tests. CBT page fetched: Part A numeracy 30 minutes and 15 questions, Part B clinical 2 hours 30
 *    minutes and 100 questions, Pearson VUE, either order with the OSCE.
 * 4. Imperial fetched: sponsorship only where it meets Home Office requirements; "The Trust will cover the cost of a
 *    certificate of sponsorship but not the visa."
 * 5. GOV.UK Health and Care Worker visa fetched: confirmed job offer, approved employer, certificate of sponsorship,
 *    minimum salary for the job; fee £324 up to 3 years and £628 over 3 years, the same inside or outside the UK;
 *    £1,270 savings unless the sponsor certifies maintenance.
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for Pakistani nurses
 *    added.
 *
 * No poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class NurseJobsLondonOverseasRegistrationBlogSeeder extends Seeder
{
    public const SLUG = 'nurse-jobs-london-overseas-registration';

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
                'title' => 'Nurse Jobs in London, England for Overseas Nurses: Registration Steps',
                'excerpt' => 'NMC registration for overseas nurses seeking London jobs: IELTS and OET scores, the CBT and OSCE, the fees raised on 1 October 2026 (GBP 1,226 in total), how London trusts handle sponsorship, and the Health and Care Worker visa fee.',
                'content' => $content,
                'featured_image' => 'blogs/nurse-jobs-london-overseas-registration-poster.jpg',
                'tags' => 'nurse jobs in london for overseas nurses, nmc registration steps, overseas nurse registration uk, london international nursing jobs, cbt osce requirements, nmc fees 2026, ielts for nmc, health and care worker visa nurse',
                'meta_title' => 'Nurse Jobs in London for Overseas Nurses: NMC Steps',
                'meta_description' => 'Overseas nurses in London: NMC registration, IELTS and OET scores, CBT and OSCE, the new fees from 1 October 2026, sponsorship and the visa fee.',
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
<p><strong>To work as a registered nurse in London after training overseas, you need registration with the Nursing and Midwifery Council (NMC), a job offer from an approved employer and a Health and Care Worker visa.</strong> NMC registration means a qualification evaluation, English evidence, the Test of Competence (a computer-based test and a practical OSCE) where required, and health, character and identity checks. The NMC raised its fees on 1 October 2026, and its costs page now puts the total at £1,226. This guide uses the NMC, GOV.UK and Imperial College Healthcare NHS Trust pages checked on 28 October 2026.</p>

<h2>Step 1: Check Your NMC Registration Route</h2>

<p>Start with the NMC's step-by-step guide for nurses trained outside the UK and its eligibility checklist. You apply online for a qualification evaluation; once the NMC confirms you are eligible, it may ask you to take the Test of Competence and complete the registration application. Keep control of your own NMC account even if an employer helps you. Source: <a href="https://www.nmc.org.uk/registration/joining-the-register/register-nurse-midwife/trained-outside-uk/how-to-guide/" rel="noopener">NMC: Register if you trained outside the UK, step by step</a>.</p>

<h2>Step 2: Gather Qualification and Identity Evidence</h2>

<p>Prepare your passport, nursing qualification and transcripts, and your current registration details. The application then covers health evidence, character evidence, English and professional indemnity, and the NMC may contact referees. Check names and dates match across documents, and follow the NMC's instructions for translations and direct verification rather than sending photocopies.</p>

<h2>Step 3: Meet the English Language Requirement</h2>

<p>The NMC accepts only two English tests:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Test</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Listening</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Reading</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Writing</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Speaking</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">IELTS Academic</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">7</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">7</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">6.5</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">7</td></tr>
<tr><td style="padding:8px;">OET</td><td style="padding:8px;">B (350)</td><td style="padding:8px;">B (350)</td><td style="padding:8px;">C+ (300)</td><td style="padding:8px;">B (350)</td></tr>
</tbody>
</table>

<p>You can combine results from more than one sitting under the NMC's rules. Other evidence routes exist through qualifying education or practice, but the NMC decides whether your evidence meets them. Sources: <a href="https://www.nmc.org.uk/registration/joining-the-register/english-language-requirements/accepted-english-language-tests/" rel="noopener">NMC: Accepted English language tests</a> and <a href="https://www.nmc.org.uk/nm-os-language/" rel="noopener">NMC: English language evidence</a>.</p>

<h2>Step 4: Complete the CBT and OSCE When Required</h2>

<p>The Test of Competence has two parts. The computer-based test (CBT), run by Pearson VUE, has Part A numeracy (15 questions in 30 minutes) and Part B clinical (100 questions in 2 hours 30 minutes). The OSCE is a practical exam taken in the UK. You can sit them in either order, but you must pass both. Use the NMC's official preparation materials rather than recalled questions. Source: <a href="https://www.nmc.org.uk/registration/joining-the-register/toc/toc-nursing-and-midwifery/cbt/" rel="noopener">NMC: CBT</a>.</p>

<h2>Step 5: Budget Using the Updated Fees</h2>

<p>The NMC's costs page, after the 1 October 2026 increase, lists:</p>

<ul>
    <li>Qualification evaluation: £167, non-refundable.</li>
    <li>CBT: £83 first sitting; resits £83 for both parts, £70 clinical only, £50 numeracy only.</li>
    <li>OSCE: £794 first sitting; £794 full resit if you fail eight or more stations, £397 partial resit for up to seven.</li>
    <li>Registration: £182.</li>
    <li>Total: £1,226 without resits.</li>
</ul>

<p>The NMC's step-by-step page still showed the old £140 evaluation fee when we checked, so budget from the costs page. These fees exclude English tests, document costs, flights, the visa and housing. Ask your employer in writing which costs it pays upfront or reimburses, and read any repayment clause. Source: <a href="https://www.nmc.org.uk/registration/joining-the-register/register-nurse-midwife/trained-outside-uk/how-to-guide/check-ready/costs/" rel="noopener">NMC: How much is it going to cost?</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nurse-jobs-london-overseas-registration-fees.jpg"
         alt="Chart of NMC overseas registration costs from 1 October 2026: evaluation 167, CBT 83, OSCE 794, registration 182, total 1,226 pounds"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Step 6: Apply to Suitable London Employers</h2>

<p>Search NHS Jobs for London nursing posts and read each person specification: a post that needs active NMC registration may not suit someone still awaiting the OSCE. Imperial College Healthcare NHS Trust, for example, says it requests sponsorship only where the role meets Home Office requirements, and that it covers the cost of the certificate of sponsorship but not the visa. State your exact NMC progress on every application. Sources: <a href="https://www.jobs.nhs.uk/candidate/search?keyword=nurse&amp;location=London" rel="noopener">NHS Jobs: Nurse vacancies in London</a> and <a href="https://www.imperial.nhs.uk/careers/help-to-apply" rel="noopener">Imperial College Healthcare: Help to apply</a>.</p>

<h2>Step 7: Check Sponsorship and Visa Eligibility</h2>

<p>The Health and Care Worker visa needs a qualified nurse working in an eligible job for a Home Office-approved employer, a certificate of sponsorship and a salary at the minimum for the job. You must have a confirmed job offer before you apply. The fee is £324 for up to three years and £628 for longer, the same inside or outside the UK, and you need £1,270 in savings unless your sponsor certifies maintenance. NMC registration and the visa are separate decisions. Source: <a href="https://www.gov.uk/health-care-worker-visa" rel="noopener">GOV.UK: Health and Care Worker visa</a>.</p>

<h2>Step 8: Finish Registration and Employer Checks</h2>

<p>The NMC completes an identity check with your original documents before registration. Do not work as a registered nurse until the NMC confirms you are on the register. Then complete your employer's checks and induction, confirm the actual hospital site, and compare housing, commute and shifts before accepting a relocation package.</p>

<h2>A Note for Nurses in Pakistan</h2>

<p>Many Pakistani nurses reach London through NHS trusts that recruit internationally. Apply only to trusts and agencies you can verify, check any agent's licence with the Bureau of Emigration and Overseas Employment, and never pay for a "guaranteed" NHS job. Read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Which English tests does the NMC accept?</h3>
<p>Only IELTS Academic and OET. Other evidence routes exist through education or practice.</p>

<h3>What IELTS score does the NMC need?</h3>
<p>7 in listening, reading and speaking, and 6.5 in writing.</p>

<h3>How much does NMC registration cost from overseas?</h3>
<p>£1,226 in total since 1 October 2026, before any resits.</p>

<h3>Do I take the CBT or OSCE first?</h3>
<p>Either order is allowed, but you must pass both.</p>

<h3>Can I apply for jobs before NMC registration?</h3>
<p>Yes, where the advert accepts registration in progress. Some posts need active registration.</p>

<h3>Does every London hospital sponsor overseas nurses?</h3>
<p>No. Check each vacancy and get written confirmation.</p>

<h3>How much is the Health and Care Worker visa?</h3>
<p>£324 for up to three years or £628 for longer.</p>

<h3>Is passing the OSCE the final step?</h3>
<p>Not always; identity checks and outstanding evidence may remain before the NMC registers you.</p>

<h2>Bottom Line</h2>

<p>Follow the NMC's current route, meet the English and Test of Competence requirements, budget from the October 2026 fees and apply only to roles that match your registration status. Treat registration, the job and the visa as separate approvals. This guide cannot promise registration, a job or a visa.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.nmc.org.uk/registration/joining-the-register/register-nurse-midwife/trained-outside-uk/how-to-guide/" rel="noopener">NMC: Step-by-step guide for nurses trained outside the UK</a></li>
<li><a href="https://www.nmc.org.uk/registration/joining-the-register/english-language-requirements/accepted-english-language-tests/" rel="noopener">NMC: Accepted English language tests</a></li>
<li><a href="https://www.nmc.org.uk/registration/joining-the-register/toc/toc-nursing-and-midwifery/cbt/" rel="noopener">NMC: CBT</a></li>
<li><a href="https://www.nmc.org.uk/registration/joining-the-register/register-nurse-midwife/trained-outside-uk/how-to-guide/check-ready/costs/" rel="noopener">NMC: Costs</a></li>
<li><a href="https://www.imperial.nhs.uk/careers/help-to-apply" rel="noopener">Imperial College Healthcare: Help to apply</a></li>
<li><a href="https://www.gov.uk/health-care-worker-visa" rel="noopener">GOV.UK: Health and Care Worker visa</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nursing-jobs-riyadh-requirements-contract-basics">Nursing Jobs in Riyadh</a>, the Saudi route for overseas nurses.</li>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, how US licensing compares.</li>
    <li><a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a>, the UK visa rules in context.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan (2026)</a>, the checks before you leave.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. It does not promise registration, a job, sponsorship or a visa; fees can change.</p>
HTML;
    }
}
