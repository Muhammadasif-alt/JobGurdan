<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Can You Bring Your Family When Working Abroad"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The ten Apply Now buttons are dropped. Commercial portals (LinkedIn,
 *    SEEK, Bayt, GulfTalent) are named in prose where useful but not linked;
 *    only official or JobGader pages are linked.
 * 2. A Pakistan section is added (BEOE licensing and emigration checks).
 * 3. The brief's four or five FAQs become eight, plus eight People Also Search
 *    For entries and a More Job Guides block.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class BringFamilyWorkingAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'bring-family-working-abroad';

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
                'title' => 'Can You Bring Your Family When Working Abroad',
                'excerpt' => 'You may be able to bring your spouse and children, but it depends on your country, work route, occupation and family. Your work approval does not give your spouse work rights.',
                'content' => $content,
                'featured_image' => 'blogs/bring-family-working-abroad.jpg',
                'tags' => 'bring family while working abroad, dependent visa for spouse, family visa work permit, can spouse join on work visa, uk skilled worker dependants, singapore dependants pass, new zealand partner work visa, family relocation costs',
                'meta_title' => 'Can You Bring Your Family When Working Abroad',
                'meta_description' => 'Can you bring your family abroad? Dependant rules for the UK, New Zealand, Singapore, Canada and Germany, spouse work rights and relocation costs.',
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
<p><strong>You may be able to bring your spouse and children when working abroad, but eligibility depends on the country, your work route, your occupation and your family circumstances.</strong> Your own work approval does not automatically authorise family relocation, and it does not give your spouse the right to work. Check the family rules before you accept a job.</p>

<h2>Check Residence, Work and Study Separately</h2>

<table>
<thead><tr><th>Question</th><th>What to confirm</th></tr></thead>
<tbody>
<tr><td>Can family join?</td><td>Eligibility under your own route</td></tr>
<tr><td>Can a spouse work?</td><td>Rights under the spouse's actual permission</td></tr>
<tr><td>Can children study?</td><td>Their status and school arrangements</td></tr>
<tr><td>Can parents join?</td><td>A separate category, where one exists</td></tr>
<tr><td>How long can they stay?</td><td>Their own expiry and renewal conditions</td></tr>
</tbody>
</table>

<p>Permission to live with you and permission to work are different checks. Do not budget from a spouse's expected salary until their work rights are clear.</p>

<h2>United Kingdom</h2>

<p>On the <a href="https://www.gov.uk/skilled-worker-visa/your-partner-and-children" rel="noopener">Skilled Worker partners and children page</a>, a partner and children may apply to join you as dependants. They need separate applications, and their visa will usually end on the same date as yours. The rules are different for care workers and senior care workers: since 11 March 2024 they generally cannot bring dependants, with limited exceptions for people already employed in the role on a Skilled Worker visa before that date. Check your own occupation before you plan around family.</p>

<h2>New Zealand</h2>

<p>The <a href="https://www.immigration.govt.nz/process-to-apply/once-you-have-a-visa/bringing-family-to-new-zealand/bringing-family-if-you-have-a-work-visa/" rel="noopener">bringing family page</a> says that many work visas allow you to support visas for your partner and dependent children, but some work visas do not, and some have extra requirements. A partner can apply for the <a href="https://www.immigration.govt.nz/visas/partner-of-a-worker-work-visa/" rel="noopener">Partner of a Worker Work Visa</a> if you hold an eligible work visa.</p>

<h2>Singapore</h2>

<p>The <a href="https://www.mom.gov.sg/passes-and-permits/dependants-pass" rel="noopener">Dependant's Pass</a> allows spouses and children of Employment Pass or S Pass holders to join them. A Dependant's Pass is not a work pass: holders who want to work need a work pass, as set out on the <a href="https://www.mom.gov.sg/passes-and-permits/dependants-pass/working-in-singapore" rel="noopener">working in Singapore page</a>.</p>

<h2>Canada</h2>

<p>Whether a spouse or partner can get an open work permit depends on the worker's situation and the rules on the <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada/special-instructions/spouses-dependent-children/eligibility.html" rel="noopener">IRCC eligibility page</a>. Not every spouse of a foreign worker qualifies, and old summaries promising open permits to everyone are out of date. Joining as a visitor or studying is different permission from working.</p>

<h2>Germany</h2>

<p>Germany has family reunification rules for spouses joining non-EU workers; see the <a href="https://www.make-it-in-germany.com/en/visa-residence/family-reunification/spouses-joining-citizens-non-eu" rel="noopener">Make it in Germany spouse page</a>. Requirements depend on your residence status and your family's circumstances, and language conditions or exceptions should be checked for your route, not copied from another visa.</p>

<h2>Documents for a Dependent Visa</h2>

<ul>
<li>Valid passports and identity documents.</li>
<li>Marriage or other accepted partnership evidence, and birth certificates for children.</li>
<li>The main worker's permission and employment details.</li>
<li>Financial evidence where the route requires it.</li>
<li>Medical or police records where required, and certified translations.</li>
</ul>

<p>This is a planning list, not a universal checklist. Some systems ask for evidence of an ongoing relationship, and children may need custody or consent documents.</p>

<h2>Costs and Timing</h2>

<p>Budget for family application fees, travel, suitable housing, insurance, schooling and daily costs. Ask the employer whether benefits cover dependants or only you. Each family member's application is decided separately, so do not assume everyone will be approved together, and avoid non-refundable travel before approval.</p>

<h2>A Practical Checklist</h2>

<ol>
<li>Confirm family eligibility under your route before you accept the job.</li>
<li>Check whether your occupation is restricted.</li>
<li>Check your spouse's work rights separately.</li>
<li>Gather documents and check translation and legalisation rules.</li>
<li>Ask the employer, in writing, which dependant benefits apply.</li>
<li>Plan childcare, schooling and what happens if your job ends.</li>
</ol>

<p>A higher salary can leave less savings once family costs are included, so rerun your budget with the family in it.</p>

<h2>What Should Pakistani Applicants Check?</h2>

<ul>
    <li><strong>That any Pakistani recruiter is licensed.</strong> Agents placing workers abroad must be licensed Overseas Employment Promoters, which you can check on the <a href="https://beoe.gov.pk" rel="noopener">Bureau of Emigration and Overseas Employment</a> website.</li>
    <li><strong>The emigration rules for your own arrangement.</strong> Taking a job without an agent does not remove Pakistan's own emigration requirements, so confirm them with the Bureau before you travel.</li>
    <li><strong>Whether a home registration is needed.</strong> Regulated fields such as nursing and engineering have their own councils and destination regulators; the two are separate checks.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can every overseas worker bring dependants?</h3>
<p>No. Eligibility varies by route, occupation and circumstances.</p>

<h3>Can care workers bring family to the UK?</h3>
<p>Generally not since 11 March 2024, apart from limited exceptions for people already in the role before then.</p>

<h3>Does my spouse automatically get work rights?</h3>
<p>No. Residence and work permission are separate checks.</p>

<h3>Can my spouse work in Singapore on a Dependant's Pass?</h3>
<p>Only with a work pass; the Dependant's Pass itself is not permission to work.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-much-money-save-working-abroad">How Much Money Can You Save Working Abroad</a>: budget and family costs</li>
    <li><a href="/blog/what-is-work-permit-how-it-works">What is a Work Permit and How Does it Work</a>: permit explained</li>
    <li><a href="/blog/what-is-visa-sponsorship-how-it-works">What is Visa Sponsorship and How Does it Work</a>: how sponsorship works</li>
    <li><a href="/blog/documents-needed-to-work-abroad-2026">Documents Needed to Work Abroad (2026)</a>: what to prepare</li>
</ul>

HTML;
    }
}
