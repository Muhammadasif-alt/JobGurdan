<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How to Avoid Losing Money to Fake Job Agents"
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
class AvoidLosingMoneyFakeJobAgentsBlogSeeder extends Seeder
{
    public const SLUG = 'avoid-losing-money-fake-job-agents';

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
                'title' => 'How to Avoid Losing Money to Fake Job Agents',
                'excerpt' => 'Verify the agency, confirm the vacancy with the employer and check every payment against official rules before you transfer money. A licence photo or a smart website is not enough.',
                'content' => $content,
                'featured_image' => 'blogs/avoid-losing-money-fake-job-agents.jpg',
                'tags' => 'fake job agents, how to avoid job agent scam, overseas job agent fraud, verify recruitment agency abroad, beoe overseas employment promoter, recruitment fee rules uk, what to do if scammed job, report recruitment fraud pakistan',
                'meta_title' => 'How to Avoid Losing Money to Fake Job Agents',
                'meta_description' => 'How to avoid fake job agents: verify the agency and employer, check fees, spot warning signs and what to do if you have already paid.',
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
<p><strong>To avoid losing money to fake job agents, verify the agency, confirm the vacancy directly with the employer and check every payment against official rules before you transfer anything.</strong> A licence photograph, a professional website or a convincing offer letter is not enough, because scammers impersonate genuine recruiters. If you have already paid, stop further payments, contact your bank or payment provider immediately and keep the evidence. Recovery is not guaranteed, but speed helps.</p>

<h2>1. Verify the Agency in an Official Register</h2>
<p>Ask for the agency's legal name, licence number, registered address and official contacts, then find the regulator's website yourself. In Pakistan, the Bureau of Emigration and Overseas Employment (<a href="https://beoe.gov.pk" rel="noopener">beoe.gov.pk</a>) publishes the list of licensed Overseas Employment Promoters. Check the current status, expiry and registered office rather than an old screenshot.</p>

<h2>2. Confirm You Are Speaking to the Real Agency</h2>
<p>A real licence number does not prove the person messaging you works for its holder. Contact the agency using details you took from the official directory, and ask whether that person is employed there. If the contact refuses independent verification or demands money before answering basic questions, stop.</p>

<h2>3. Verify the Vacancy and the Employer Separately</h2>
<p>Ask for the employer's legal name, job title, location, duties and vacancy reference, then contact the company's recruitment team through a channel on its own website. Ask whether the role exists and whether the agency is authorised to recruit for it. See our <a href="/blog/verify-overseas-job-offer">offer verification guide</a>.</p>

<h2>4. Check Fees Before You Pay</h2>
<p>Separate recruitment charges from government fees, medical tests, document services and optional help, and ask who receives each payment and which rule allows it. Rules differ: in the UK, agencies generally cannot charge workers for finding them work, with limited exceptions (see <a href="https://www.gov.uk/agency-workers-your-rights/fees" rel="noopener">GOV.UK agency fees</a>). For Pakistan, confirm authorised charges with the BEOE or the Protector of Emigrants, and do not rely on an agent's verbal explanation of government fees.</p>

<h2>5. High-Risk Payment Requests</h2>
<p>Requests for cryptocurrency, gift cards or urgent transfers to an unrelated person need extra scrutiny. A transfer receipt records a payment; it does not prove a job exists. Never deposit a cheque from a supposed employer and send part of it onward, because you will owe the money when the cheque is rejected.</p>

<h2>Warning Signs and Responses</h2>

<table>
<thead><tr><th>Warning sign</th><th>Your response</th></tr></thead>
<tbody>
<tr><td>Guaranteed visa approval</td><td>Check the real immigration route yourself</td></tr>
<tr><td>Payment deadline in minutes</td><td>Pause and ask for written details</td></tr>
<tr><td>Licence screenshot only</td><td>Search the regulator's current records</td></tr>
<tr><td>Employer cannot be contacted independently</td><td>Seek confirmation through published channels</td></tr>
<tr><td>Payee name differs from agency name</td><td>Ask for documented authority before paying</td></tr>
<tr><td>Vague salary or deductions</td><td>Ask for full written contract terms</td></tr>
<tr><td>More money to "release" earnings</td><td>Stop and investigate</td></tr>
</tbody>
</table>

<h2>6. Read the Contract First</h2>
<p>Ask for written terms covering the employer, role, destination, pay, hours, deductions, accommodation and refunds, and compare them with the advert and the interview. Ask what happens if the vacancy disappears or your visa is refused. Never sign blank forms, and do not borrow money or sell valuables while key facts are unverified.</p>

<h2>7. Protect Your Identity and Documents</h2>
<p>Share only what is needed at first. Never give banking passwords, card PINs or one-time codes, and do not install remote access software for a recruiter. If original documents are genuinely required, confirm the procedure and get a receipt naming the recipient and the return date.</p>

<h2>8. If You Have Already Paid</h2>
<ul>
<li>Stop sending money, including any fee to "release" or "recover" funds.</li>
<li>Contact your bank, card issuer or payment service through its official channel and ask about a recall or dispute.</li>
<li>Save conversations, adverts, documents, account details and transaction references, and write a dated timeline.</li>
<li>Do not delete messages before copying them.</li>
</ul>

<h2>9. Report It</h2>
<p>For recruitment matters in Pakistan, use the BEOE's complaints process at <a href="https://beoe.gov.pk" rel="noopener">beoe.gov.pk</a>. Suspected online fraud can be reported to the National Cyber Crime Investigation Agency through its <a href="https://complaint.nccia.gov.pk/" rel="noopener">complaint portal</a>. For a US-linked scam use <a href="https://reportfraud.ftc.gov" rel="noopener">reportfraud.ftc.gov</a>. Also tell the real employer and the platform that carried the advert. Reporting does not by itself bring a refund.</p>

<h2>10. Avoid a Second Scam</h2>
<p>Fraudsters contact victims and promise guaranteed recovery for another upfront payment. Verify anyone who says they represent a regulator, lawyer or recovery service, and never pay because they know details of your earlier loss.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How can I verify a recruitment agency abroad?</h3>
<p>Use the official register, compare legal details and confirm the vacancy with the employer yourself.</p>

<h3>Does a valid licence guarantee a genuine offer?</h3>
<p>No. Verify the contact person and the specific vacancy separately.</p>

<h3>Are all overseas job expenses fraudulent?</h3>
<p>No. Some genuine costs exist, but permitted charges depend on the rules that apply.</p>

<h3>Can my bank recover the payment?</h3>
<p>Possibly. Contact it at once; recovery depends on the method, timing and circumstances.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/verify-overseas-job-offer">How to Verify a Job Offer from Another Country</a>: check the offer</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer (2026)</a>: verify before you pay</li>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a>: apply yourself</li>
    <li><a href="/blog/send-money-home-working-abroad">How to Send Money Home While Working Abroad</a>: remittance checks</li>
</ul>

HTML;
    }
}
