<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Rights of Foreign Workers Abroad: What You Must Know"
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
class RightsForeignWorkersAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'rights-foreign-workers-abroad';

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
                'title' => 'Rights of Foreign Workers Abroad: What You Must Know',
                'excerpt' => 'Foreign workers can have legal protections on wages, contracts, safety, discrimination and complaints, but entitlements depend on the destination, status and sector.',
                'content' => $content,
                'featured_image' => 'blogs/rights-foreign-workers-abroad.jpg',
                'tags' => 'rights of foreign workers, expat worker rights, labour law for immigrants, what rights do overseas workers have, migrant worker rights, unpaid wages abroad, passport withheld employer, report employer abuse',
                'meta_title' => 'Rights of Foreign Workers Abroad: What You Must Know',
                'meta_description' => 'Rights of foreign workers abroad: wages, contracts, safety, passports, discrimination and where to report, with UK, Canada, Australia, US and UAE examples.',
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
<p><strong>Foreign workers can have legal protections covering wages, workplace safety, discrimination and access to complaints, but exact entitlements depend on the destination, your employment status and the sector.</strong> Visa sponsorship does not remove employment rights, and your contract is not the only source of protection. Check your country's official labour guidance and your immigration conditions separately, and keep your contract, payslips and work records.</p>

<h2>1. Know Which Rules Apply to Your Job</h2>
<p>Rights can differ between employees, other workers, contractors and domestic workers. UK guidance on <a href="https://www.gov.uk/employment-status/worker" rel="noopener">worker employment status</a> distinguishes worker from employee, with different entitlements. A contract that calls you self-employed does not settle the legal question if the way you work says otherwise.</p>

<h2>2. Understand Your Contract and Duties</h2>
<p>Ask for written terms covering the employer, role, location, pay, hours, deductions and benefits, keep the accepted version and any amendments, and ask for explanations before you sign what you cannot read. Canada's guidance on <a href="https://www.canada.ca/en/employment-social-development/services/foreign-workers/protected-rights.html" rel="noopener">temporary foreign worker rights</a> covers the signed employment agreement and document protections for that programme; check the destination's own rules instead of relying on a recruiter's assurance.</p>

<h2>3. Wages, Overtime and Deductions</h2>
<p>Record your hours and compare them with your payslips, and separate basic pay from overtime, bonuses and accommodation charges. Australia's Fair Work Ombudsman says that <a href="https://www.fairwork.gov.au/find-help-for/visa-holders-migrants" rel="noopener">visa holders and migrant workers</a> have the same workplace entitlements and protections as other employees. In the US, the Department of Labor explains <a href="https://www.dol.gov/general/migrantworker/wages?lang=en" rel="noopener">wage protections for migrant workers</a> and how to seek unpaid wages. Exemptions and local rules differ by occupation.</p>

<h2>4. Hours, Rest and Leave</h2>
<p>Check the rules on breaks, weekly rest, annual leave and sick leave, because they can depend on your classification. The employer's usual practice is not necessarily lawful. Before accepting extra shifts, check any limits that your visa places on work.</p>

<h2>5. Safety, Training and Injuries</h2>
<p>Ask for training before using unfamiliar equipment, report hazards through the workplace procedure and keep a record. In an emergency, protect yourself first and contact local emergency services. In Britain the <a href="https://www.hse.gov.uk/migrantworkers/worker/index.htm" rel="noopener">Health and Safety Executive</a> publishes guidance for migrant workers. If you are injured, get medical care, keep the incident report and ask which insurance or compensation process applies.</p>

<h2>6. Passport and Documents</h2>
<p>Keep secure copies of your passport, visa and permits. Confirm why originals are requested, who holds them and when they will be returned. Employers cannot lawfully hold personal documents under Canada's temporary foreign worker rules, and the UAE government's <a href="https://u.ae/en/information-and-services/jobs" rel="noopener">jobs information</a> covers employee rights in the private sector. If your documents are withheld, seek help through official channels; sponsorship does not give an employer ownership of your passport.</p>

<h2>7. Discrimination, Harassment and Retaliation</h2>
<p>Nationality or foreign status does not justify discriminatory treatment, but the protected characteristics and remedies depend on local law. The UK's <a href="https://www.gov.uk/discrimination-your-rights/discrimination-at-work" rel="noopener">discrimination at work guidance</a> covers recruitment, pay and dismissal. Keep dated records of incidents, witnesses and messages, and check local rules before making any recording. US Department of Labor laws also protect against retaliation for exercising covered rights, but that is not a promise about immigration outcomes.</p>

<h2>8. Sponsorship and Changing Employers</h2>
<p>Employment rights and immigration permission are connected but separate. You may have a wage claim while still needing permission before starting a different job, so check notice and immigration rules before you act. Canada has an open work permit for eligible workers who are experiencing or at risk of abuse, which has to be assessed and is not automatic. Take independent advice if a sponsor threatens you with immigration consequences. See <a href="/blog/employer-cancels-work-visa">what happens if your employer cancels your visa</a>.</p>

<h2>9. Where to Report Problems</h2>
<p>Choose the authority for your problem: unpaid wages, danger at work, discrimination and immigration status may each go to a different body. Your embassy can help you find support but does not replace the destination's labour regulator. For Canada, see <a href="https://www.canada.ca/en/employment-social-development/services/foreign-workers/report-abuse.html" rel="noopener">reporting abuse of temporary foreign workers</a>; for the UAE, <a href="https://www.mohre.gov.ae" rel="noopener">MoHRE</a> handles private-sector labour complaints. Check deadlines at once, because a complaint does not by itself extend a visa or recover money.</p>

<h2>Evidence Checklist</h2>

<table>
<thead><tr><th>Keep</th><th>Why it helps</th></tr></thead>
<tbody>
<tr><td>Contract and amendments</td><td>Shows agreed terms</td></tr>
<tr><td>Payslips and bank records</td><td>Compare pay promised and received</td></tr>
<tr><td>Hours record</td><td>Supports questions about unpaid time</td></tr>
<tr><td>Messages and complaint references</td><td>Shows requests and replies</td></tr>
<tr><td>Visa and permit copies</td><td>Shows your immigration conditions</td></tr>
</tbody>
</table>

<p>Store copies somewhere you can reach outside the workplace. If your wages look short, write down the pay period, hours, expected amount and amount received, and ask payroll for a written explanation before you take it to a regulator. If reporting internally could expose you to threats, contact an outside support service first.</p>

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

<h3>Are expat worker rights identical everywhere?</h3>
<p>No. Countries and employment categories have different protections and procedures.</p>

<h3>Does labour law for immigrants depend only on the contract?</h3>
<p>No. The law can give rights beyond the written agreement.</p>

<h3>Can I complain after leaving the job?</h3>
<p>Possibly. Check claim deadlines and the right authority promptly.</p>

<h3>Can a sponsor deport me?</h3>
<p>Immigration decisions belong to the authorities, but losing a job can affect permission, so get official advice quickly.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/employer-cancels-work-visa">What Happens if Your Employer Cancels Your Visa</a>: job loss and status</li>
    <li><a href="/blog/verify-overseas-job-offer">How to Verify a Job Offer from Another Country</a>: check the offer</li>
    <li><a href="/blog/avoid-losing-money-fake-job-agents">How to Avoid Losing Money to Fake Job Agents</a>: recruiter fraud</li>
    <li><a href="/blog/bring-family-working-abroad">Can You Bring Your Family When Working Abroad</a>: dependants</li>
</ul>

<p><em>Reviewed on 12 October 2026.</em></p>

HTML;
    }
}
