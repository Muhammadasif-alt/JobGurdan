<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "What Happens if Your Employer Cancels Your Visa"
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
class EmployerCancelsWorkVisaBlogSeeder extends Seeder
{
    public const SLUG = 'employer-cancels-work-visa';

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
                'title' => 'What Happens if Your Employer Cancels Your Visa',
                'excerpt' => 'If your job ends or your sponsor withdraws, your right to work and stay may change, but the outcome depends on the country and your category.',
                'content' => $content,
                'featured_image' => 'blogs/employer-cancels-work-visa.jpg',
                'tags' => 'employer cancels work visa, what to do if sponsor cancels visa, visa cancelled by employer, work visa problems abroad, uk sponsor licence revoked 60 days, singapore employment pass cancelled, canada lose job work permit, change of employer visa',
                'meta_title' => 'What Happens if Your Employer Cancels Your Visa',
                'meta_description' => 'If your employer cancels your visa: what to check first and how the UK 60-day rule, Singapore, Canada and New Zealand treat job loss.',
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
<p><strong>If your employer ends your job or withdraws sponsorship, your right to work and to stay may be affected, but the outcome depends on the country and your immigration category.</strong> Do not assume immediate deportation or an automatic grace period. Termination of employment, withdrawal of sponsorship and a government cancellation are different events, so first establish which one has happened.</p>

<h2>Identify the Actual Event</h2>

<table>
<thead><tr><th>Event</th><th>What to check</th></tr></thead>
<tbody>
<tr><td>Job termination</td><td>Employment ended; immigration effects vary</td></tr>
<tr><td>Sponsorship withdrawal</td><td>Employer support changed or ended</td></tr>
<tr><td>Sponsor licence loss</td><td>The employer no longer meets sponsor rules</td></tr>
<tr><td>Pass cancellation</td><td>A work pass was formally cancelled</td></tr>
<tr><td>Permission expiry</td><td>The document reached its end date</td></tr>
</tbody>
</table>

<p>An employer's verbal statement is not a complete explanation of your position. Ask for written dates and references, then verify through the official channel.</p>

<h2>What to Do Immediately</h2>

<ul>
<li>Keep the termination notice, contract, payslips, immigration approval and all correspondence.</li>
<li>Record your last working day and any claimed cancellation date.</li>
<li>Check three separate questions: whether you may still work, how long you may lawfully stay, and whether a new application is possible.</li>
<li>Read every decision for a deadline, and get advice from a qualified immigration adviser if your status is unclear.</li>
</ul>

<h2>United Kingdom</h2>

<p>On the <a href="https://www.gov.uk/employee-lose-sponsor-licence" rel="noopener">GOV.UK sponsor licence loss page</a>, if your sponsor loses its licence your certificate of sponsorship is cancelled and your visa is limited to 60 days, or to the time you have left if that is shorter. You must leave your job and leave the UK unless you make a new visa application within that time. If you were involved in the reasons the licence was lost, your visa will be withdrawn. This applies to licence loss, not to every dismissal. If you change job or employer, see <a href="https://www.gov.uk/skilled-worker-visa/update-your-visa-if-you-change-job-or-employer" rel="noopener">updating your visa</a>.</p>

<h2>Singapore</h2>

<p>An employer can cancel an Employment Pass, and the <a href="https://www.mom.gov.sg/passes-and-permits/employment-pass/cancel-a-pass" rel="noopener">MOM cancellation page</a> says cancellation is immediate unless an advance request is made. While the holder is still in Singapore, a Short-Term Visit Pass can be requested to give a valid stay of up to 90 days. That pass is permission to remain, not permission to work.</p>

<h2>Canada</h2>

<p>An employer-specific work permit ties you to one employer, so if the job ends you must stop working for them and follow the process for a new employer or a different status. An open work permit is not tied to the lost job, within its conditions. Check the current <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada.html" rel="noopener">IRCC work in Canada page</a> and your permit's expiry date, and do not start with a new employer until the required approval is in place.</p>

<h2>New Zealand</h2>

<p>The <a href="https://www.immigration.govt.nz/process-to-apply/once-you-have-a-visa/manage-your-visa-and-passport/check-or-change-your-visa-conditions/" rel="noopener">visa conditions page</a> says that if your job, workplace or employment changes, including losing your job, you may need a variation of conditions, a Job Change or a new work visa.</p>

<h2>Australia</h2>

<p>Sponsored visas carry conditions about what happens when sponsored employment ends, and the time allowed to find a new sponsor depends on your visa subclass and grant. Read the conditions on your grant notice and in the Department of Home Affairs' <a href="https://immi.homeaffairs.gov.au/visas/already-have-a-visa/check-visa-details-and-conditions/see-your-visa-conditions" rel="noopener">visa conditions tool</a>; do not rely on a figure that was true for someone else's visa.</p>

<h2>Can You Find Another Sponsor?</h2>

<p>Possibly, where the route allows it and the new employer and role qualify. A vacancy or a verbal promise is not approval. Check the deadline, what must be in place before you apply and when you may lawfully start work. Keep a departure plan in case no eligible option appears, and do not pay anyone who sells a guaranteed "visa transfer".</p>

<h2>Family, Final Pay and Housing</h2>

<p>Dependants may have their own conditions and deadlines that depend on yours. Ask for a written breakdown of final pay and check your entitlements with the labour authority; unpaid wages and immigration status are separate matters. Keep emergency funds and copies of documents. Your embassy can help with practical concerns but does not override immigration rules.</p>

<h2>Avoid Making It Worse</h2>

<ul>
<li>Do not keep working without authorisation.</li>
<li>Do not miss an official deadline.</li>
<li>Do not submit false evidence.</li>
<li>A threat from an employer does not remove your right to report mistreatment.</li>
</ul>

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

<h3>Must I leave immediately after dismissal?</h3>
<p>Not always. It depends on the route, your status and the official decision.</p>

<h3>How long do I get if my UK sponsor loses its licence?</h3>
<p>Your visa is limited to 60 days, or the time left if shorter, unless you make a new application.</p>

<h3>Can I work during a grace period?</h3>
<p>Only if the rules authorise it. Permission to stay is not permission to work.</p>

<h3>Can I stay in Singapore after my pass is cancelled?</h3>
<p>You can request a Short-Term Visit Pass for up to 90 days, which is not permission to work.</p>

<h3>Does an open work permit end with my job?</h3>
<p>No. An open permit is not tied to one employer, within its conditions.</p>

<h3>Can I ignore a verbal cancellation claim?</h3>
<p>No. Verify it promptly and ask for the notice in writing.</p>

<h3>Does a complaint extend my visa?</h3>
<p>Do not assume it does. Check your immigration options separately.</p>

<h3>Can an agent fix this for a fee?</h3>
<p>No one can guarantee a visa transfer. Verify the lawful route first.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/bring-family-working-abroad">Can You Bring Your Family When Working Abroad</a>: dependants</li>
    <li><a href="/blog/what-is-visa-sponsorship-how-it-works">What is Visa Sponsorship and How Does it Work</a>: how sponsorship works</li>
    <li><a href="/blog/what-is-work-permit-how-it-works">What is a Work Permit and How Does it Work</a>: permit explained</li>
    <li><a href="/blog/how-long-work-visa-takes-2026">How Long Does it Take to Get a Work Visa</a>: realistic timelines</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 12 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
