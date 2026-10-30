<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "English Language Requirements for Working Abroad"
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
class EnglishLanguageRequirementsWorkingAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'english-language-requirements-working-abroad';

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
                'title' => 'English Language Requirements for Working Abroad',
                'excerpt' => 'IELTS is not compulsory for every overseas job. English rules depend on the visa route, the employer and the profession. See what the UK, New Zealand, Australia, Canada and Germany ask for, and how to avoid buying the wrong test.',
                'content' => $content,
                'featured_image' => 'blogs/english-language-requirements-working-abroad.jpg',
                'tags' => 'english requirement working abroad, ielts for work visa, do you need english to work overseas, language test work visa, uk skilled worker english b2, new zealand aewv english, express entry language test, nmc english requirements',
                'meta_title' => 'English Language Requirements for Working Abroad',
                'meta_description' => 'English requirements for working abroad: UK B2 rule, NZ skill level 3 to 5, Express Entry tests, nursing regulators and when IELTS is not needed.',
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
<p><strong>The English requirement for working abroad depends on your visa route, the employer and your profession.</strong> Some routes need an approved language test, others accept qualifying education or an exemption, and regulated professions add their own evidence. IELTS is not compulsory for every overseas job, so identify the exact route before you pay for a test.</p>

<h2>Check Three Separate Requirements</h2>

<table>
<thead><tr><th>Requirement</th><th>Who sets it</th><th>What to check</th></tr></thead>
<tbody>
<tr><td>Immigration</td><td>Government authority</td><td>Accepted evidence, scores and exemptions</td></tr>
<tr><td>Recruitment</td><td>Employer</td><td>Communication needed for the real role</td></tr>
<tr><td>Professional registration</td><td>Regulator</td><td>Accepted tests and other evidence</td></tr>
</tbody>
</table>

<p>A successful interview does not satisfy immigration rules, and a visa test result does not automatically satisfy a professional regulator. Ask each authority what applies to you.</p>

<h2>United Kingdom: Skilled Worker English</h2>

<p>The <a href="https://www.gov.uk/skilled-worker-visa/knowledge-of-english" rel="noopener">GOV.UK Knowledge of English page</a> says you must prove English to at least level B2 on the CEFR scale. If you held this visa before 8 January 2026 and are extending or updating it, level B1 applies instead. You can prove English with a UK school qualification, a UK degree, a degree taught in English outside the UK (you need an Ecctis assessment, not just a university letter) or an approved Secure English Language Test. Nationals of listed majority English-speaking countries do not need to prove it.</p>

<h2>New Zealand: Skill Level Decides</h2>

<p>The <a href="https://www.immigration.govt.nz/visas/accredited-employer-work-visa/" rel="noopener">Accredited Employer Work Visa page</a> says that if your job is ANZSCO or NOL skill level 3 to 5 you must show you can speak and understand English, either through citizenship, study and qualifications or an English test. Jobs at skill level 1 or 2 do not need it. Test results must meet the minimum score and be no more than 2 years old when you apply.</p>

<h2>Australia: It Depends on the Visa</h2>

<p>The <a href="https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/skills-in-demand-visa-subclass-482" rel="noopener">Skills in Demand visa (subclass 482)</a> page lists its English requirement for each stream. Do not carry a student-visa score over to a work visa; read the stream and the date your test counts from.</p>

<h2>Canada: Work Permit Is Not Express Entry</h2>

<p>Express Entry, Canada's permanent residence system, requires approved language-test results under its own rules. A temporary work permit has separate eligibility, so one IELTS score is not a universal Canadian checklist. Check the category you are applying under on the <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada.html" rel="noopener">IRCC work in Canada page</a>.</p>

<h2>Germany and Other Non-English Destinations</h2>

<p>English helps, but it does not replace the local language where a job needs it. Requirements vary by visa purpose; see the <a href="https://www.make-it-in-germany.com/en/visa-residence/types" rel="noopener">Make it in Germany visa pages</a>, and check the workplace language for the actual duties.</p>

<h2>Healthcare and Regulated Professions</h2>

<p>Nurses and other regulated staff check their regulator first. The UK Nursing and Midwifery Council publishes its own accepted English evidence on its <a href="https://www.nmc.org.uk/registration/joining-the-register/english-language-requirements/" rel="noopener">English language requirements page</a>. A general visa score does not automatically meet it.</p>

<h2>Is IELTS Always Necessary?</h2>

<p>No. IELTS is one family of tests, not a universal overseas work requirement. Academic, General Training and UKVI versions are not interchangeable, and an overall score alone may fail if one component is below the minimum. Confirm the exact product the authority accepts before booking.</p>

<h2>How to Choose a Test</h2>

<ol>
<li>Identify the destination and the exact visa route.</li>
<li>Read the current government evidence rules.</li>
<li>Check the employer and the regulator separately.</li>
<li>Confirm the accepted tests, products and providers.</li>
<li>Verify the required scores and how long a result stays valid.</li>
<li>Book only after confirming it will be accepted.</li>
</ol>

<p>Keep your original result and your record of which official page you checked. Avoid any course that promises a score, a job or a visa.</p>

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

<h3>Does every overseas job require IELTS?</h3>
<p>No. It depends on the visa route, the employer and the profession.</p>

<h3>What English level does the UK Skilled Worker visa need?</h3>
<p>Level B2, with B1 for people who held the visa before 8 January 2026 and are extending or updating it.</p>

<h3>Can an English-medium degree replace a test?</h3>
<p>Sometimes. For the UK a non-UK degree needs an Ecctis assessment; check the rules of your own route.</p>

<h3>Does New Zealand ask for English for every job?</h3>
<p>No. It applies to skill level 3 to 5 jobs; skill level 1 or 2 jobs do not need it.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/documents-needed-to-work-abroad-2026">Documents Needed to Work Abroad (2026)</a>: what to prepare</li>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a>: apply yourself</li>
    <li><a href="/blog/countries-visa-sponsorship-skilled-workers-2026">Countries That Offer Visa Sponsorship for Skilled Workers</a>: compare routes</li>
    <li><a href="/blog/how-long-work-visa-takes-2026">How Long Does it Take to Get a Work Visa</a>: realistic timelines</li>
</ul>

HTML;
    }
}
