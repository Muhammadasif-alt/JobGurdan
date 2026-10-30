<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How to Verify a Job Offer from Another Country"
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
class VerifyOverseasJobOfferBlogSeeder extends Seeder
{
    public const SLUG = 'verify-overseas-job-offer';

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
                'title' => 'How to Verify a Job Offer from Another Country',
                'excerpt' => 'Verify an overseas job offer by confirming the employer, recruiter, vacancy and written terms independently, then checking the claimed immigration route in official registers.',
                'content' => $content,
                'featured_image' => 'blogs/verify-overseas-job-offer.jpg',
                'tags' => 'how to verify overseas job offer, check if job offer is real, verify foreign employer, authentic job offer abroad, uk sponsor register check, companies house search, recognised sponsor netherlands, verify overseas recruiter pakistan',
                'meta_title' => 'How to Verify a Job Offer from Another Country',
                'meta_description' => 'How to verify an overseas job offer: confirm the employer, recruiter and contract independently and check UK, Dutch and NZ sponsor registers.',
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
<p><strong>Verify an overseas job offer by confirming the employer, recruiter, vacancy and written terms independently, then checking the claimed immigration route through official sources.</strong> A logo, a signed PDF or a sponsor-register entry does not prove the person contacting you is authorised. No single check guarantees authenticity, so use several.</p>

<h2>1. Identify the Legal Employer</h2>
<p>Ask for the legal company name, worksite, website and registration details, and separate the employer from any recruiter or brand name. For a UK employer, the <a href="https://find-and-update.company-information.service.gov.uk/" rel="noopener">Companies House search</a> shows whether the company exists and who is registered. A real company can still be impersonated, so registration is only part of the picture.</p>

<h2>2. Contact the Employer Independently</h2>
<p>Find the company's own website yourself and use its published recruitment channel to confirm the vacancy and the sender. Do not verify an offer by calling a number printed on the same document. Check the full email address carefully, because look-alike domains and hacked accounts are common.</p>

<h2>3. Match the Vacancy and the Process</h2>
<p>Ask for the job reference and compare duties, location, dates and requirements with the employer's careers page. Not every real vacancy is public, so a missing advert does not prove fraud, but unusually high pay for little work and pressure to accept immediately are reasons to investigate.</p>

<h2>4. Check Claimed UK Sponsorship</h2>
<p>If the offer claims UK sponsorship, search the <a href="https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers" rel="noopener">register of licensed sponsors</a>. It lists licensed organisations, not vacancies, and a scammer can copy a genuine sponsor's name. Licensing, role eligibility and your own eligibility are separate checks.</p>

<h2>5. Other Employer Approval Systems</h2>
<ul>
<li><strong>Netherlands:</strong> the IND publishes a <a href="https://ind.nl/en/public-register-recognised-sponsors" rel="noopener">public register of recognised sponsors</a>.</li>
<li><strong>New Zealand:</strong> Immigration New Zealand has an <a href="https://www.immigration.govt.nz/work/requirements-for-work-visas/approved-employers/accredited-employer-list/" rel="noopener">accredited employer search</a>. Some employers choose not to be published, so a missing result needs a question to the employer, not an automatic conclusion of fraud.</li>
</ul>

<h2>6. Read the Whole Contract</h2>
<p>Look for the employer and worker details, job title, duties and worksite, salary currency and payment basis, hours and contract dates, disclosed deductions, and who is responsible for recruitment and immigration. Ask for written clarification of any inconsistency, and never sign what you cannot understand.</p>

<h2>7. Examine Payment Requests</h2>
<p>Guaranteed jobs or visas, unexplained charges and pressure to pay quickly are serious warning signs. Ask who receives the money, what it covers and which rule supports it. Never deposit a cheque from an alleged employer and forward part of it, and never buy gift cards. Report fraud through <a href="https://reportfraud.ftc.gov" rel="noopener">reportfraud.ftc.gov</a> if a US scam is involved.</p>

<h2>8. Verify a Recruiter in Pakistan</h2>
<p>Overseas Employment Promoters must be licensed by Pakistan's Bureau of Emigration and Overseas Employment, which publishes its directory on <a href="https://beoe.gov.pk" rel="noopener">beoe.gov.pk</a>. A licence number pasted into a message does not prove the sender belongs to that agency, so contact the registered office through details from the directory and ask it to confirm the representative and the vacancy.</p>

<h2>9. Protect Documents and Keep Evidence</h2>
<p>Keep the advert, messages, contract and references, and record when the employer confirmed the offer. Send identity documents only when needed and only through verified channels, and never share banking passwords or one-time codes. If you have already paid a suspected scammer, contact your bank or payment provider at once; recovery is not guaranteed, and a "recovery agent" asking for a fee is a second scam.</p>

<h2>Final Checklist</h2>

<table>
<thead><tr><th>Check</th><th>Evidence to seek</th></tr></thead>
<tbody>
<tr><td>Employer</td><td>Matching legal identity and independent contact</td></tr>
<tr><td>Representative</td><td>Confirmed authority to recruit</td></tr>
<tr><td>Vacancy</td><td>Employer-confirmed duties and terms</td></tr>
<tr><td>Immigration</td><td>Appropriate route and employer process</td></tr>
<tr><td>Payment</td><td>Explained lawful costs, verified channel</td></tr>
</tbody>
</table>

<p>A question worth sending through the independently verified channel: "Did your organisation issue this offer for this role, location and start date, and is this person authorised to handle my recruitment?" Do not resign or buy non-refundable travel until the answers are consistent. See also our <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">fake overseas job offer guide</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Does a signed offer prove authenticity?</h3>
<p>No. Confirm it with the genuine employer through details you found yourself.</p>

<h3>Does a sponsor register entry guarantee a visa?</h3>
<p>No. It does not show that your role qualifies or that the sender is genuine.</p>

<h3>Is a free email address proof of fraud?</h3>
<p>No single feature proves fraud. Verify the whole recruitment process.</p>

<h3>Can a genuine company be impersonated?</h3>
<p>Yes. Confirm the representative and the specific offer, not just the company.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer (2026)</a>: verify before you pay</li>
    <li><a href="/blog/mistakes-applying-jobs-abroad">Top Mistakes People Make When Applying for Jobs Abroad</a>: avoid errors</li>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a>: apply yourself</li>
    <li><a href="/blog/documents-needed-to-work-abroad-2026">Documents Needed to Work Abroad (2026)</a>: what to prepare</li>
</ul>

HTML;
    }
}
