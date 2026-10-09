<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Can a Foreigner Get a Caregiver Job in the USA With Visa Sponsorship in 2026?"
 *
 * Checked on 9 October 2026 against BLS (API, May 2025, occupation 31-1120),
 * the October 2026 Visa Bulletin and the text of H.R. 9234. Corrections:
 *
 * 1. "Pakistan has not been on recent DHS H-2B eligible-country lists" and
 *    "you must come from a country on the DHS eligible list" are out of date:
 *    DHS removed the lists when its H-2 rule took effect on 17 January 2025.
 *
 * 2. "Waits have run about 3 to 5 years" is replaced with a figure that can be
 *    checked: the October 2026 Visa Bulletin final action date for EB-3 Other
 *    Workers, all chargeability areas, is 1 January 2022.
 *
 * 3. The Medford, Oregon figure and the "17% growth 2024 to 2034" line could not
 *    be confirmed against the current projections cycle and are dropped. Texas
 *    ($24,230) and Pennsylvania ($29,420) match the BLS state estimates.
 *
 * 4. The PKR conversions use the illustrative PKR 280 per dollar of the other
 *    guides, not "277", which could not be verified. They were recomputed.
 *
 * No job order is created: EB-3 is a green card process, not a vacancy.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class CaregiverJobsUsaVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'caregiver-jobs-usa-visa-sponsorship-2026';

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
                'title' => 'Can a Foreigner Get a Caregiver Job in the USA With Visa Sponsorship in 2026?',
                'excerpt' => 'Yes, mostly through the EB-3 Other Workers green card, not H-2B. Caregiving is year-round work, EB-3 needs a PERM labor certification and takes years, and the employer pays the costs. See the routes, pay, requirements and scam warnings.',
                'content' => $content,
                'featured_image' => 'blogs/caregiver-jobs-usa-visa-sponsorship.jpg',
                'tags' => 'caregiver jobs usa, caregiver visa sponsorship, eb-3 other workers, home health aide visa, caregiver jobs for foreigners, caregiver salary usa, careworker visa act, caregiver job from pakistan',
                'meta_title' => 'Caregiver Jobs in USA With Visa Sponsorship (2026)',
                'meta_description' => 'Caregiver jobs in the USA with visa sponsorship: the EB-3 green card route, why H-2B rarely fits, pay, requirements and scam warnings.',
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
<p>Yes, a foreigner can get a caregiver job in the USA with employer sponsorship, but <strong>mostly through the EB-3 "Other Workers" green card, not the H-2B visa</strong>. Caregiving is year-round work, so it rarely meets the H-2B rule that the need must be temporary. EB-3 needs an employer to sponsor you through a labor certification process and takes years. A legitimate employer pays the sponsorship costs, not you.</p>

<h2>Which Visa Can a Caregiver Get for the USA?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Type</th>
            <th style="padding:10px;text-align:left;">Best for</th>
            <th style="padding:10px;text-align:left;">Reality check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>EB-3 "Other Workers"</strong></td><td style="padding:10px;">Permanent (green card)</td><td style="padding:10px;">Home health aides, CNAs, personal care aides</td><td style="padding:10px;">The most realistic route, but slow</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>EB-3 skilled / Schedule A</strong></td><td style="padding:10px;">Permanent (green card)</td><td style="padding:10px;">Registered nurses</td><td style="padding:10px;">Needs US nursing licensing steps</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>H-2B</strong></td><td style="padding:10px;">Temporary, non-farm</td><td style="padding:10px;">A genuinely seasonal or short-term need</td><td style="padding:10px;">Hard to prove for caregiving; capped at 66,000 a year</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>J-1 au pair</strong></td><td style="padding:10px;">Exchange</td><td style="padding:10px;">Childcare only, young adults</td><td style="padding:10px;">Not for eldercare or home health work</td></tr>
    </tbody>
</table>
</div>

<p>For aides and personal caregivers, EB-3 "Other Workers" is the main legal sponsorship route.</p>

<h2>How Does EB-3 Sponsorship Work for Caregivers?</h2>

<p>Most caregiver roles, such as home health aides and certified nursing assistants, fall into the "Other Workers" group, which covers jobs that need less than two years of training. The steps are:</p>

<ol>
    <li><strong>The employer starts a PERM labor certification.</strong> It must test the US job market, show that no qualified US workers are available and pay at least the prevailing wage.</li>
    <li><strong>The employer files Form I-140</strong> with USCIS for you.</li>
    <li><strong>You wait for your priority date to become current</strong> in the monthly State Department Visa Bulletin.</li>
    <li><strong>You attend an immigrant visa interview</strong> at a US embassy or consulate and enter the US as a permanent resident.</li>
</ol>

<p>The wait is long. In the October 2026 Visa Bulletin the final action date for EB-3 "Other Workers" for all countries, including Pakistan, was 1 January 2022. That means applicants whose priority date is later than that were still waiting. The date moves every month, so check the current bulletin.</p>

<h2>Can You Get a Caregiver Visa on H-2B?</h2>

<p>Rarely. The H-2B requires a temporary need: seasonal, peak-load, intermittent or one-time. Most caregiver jobs are ongoing, so employers struggle to prove it. The annual cap is 66,000 new H-2B workers, split between the two halves of the year.</p>

<p><strong>Pakistan note:</strong> the lists of eligible countries that used to limit H-2B were removed when the DHS rule took effect on 17 January 2025, so Pakistan is not excluded by name. You would still need an employer that recruits from Pakistan, an approved petition and a visa decision, and H-2B is not a normal option for caregivers. EB-3 does not use country lists, but few employers sponsor workers from abroad. The H-2B process is explained in <a href="/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026">How to Get a Warehouse Worker Job in USA From Pakistan</a>.</p>

<h2>Is There a New Caregiver Visa on the Way?</h2>

<p>A bill called the Careworker Visa Act of 2026 (H.R. 9234) was introduced in the US House on 9 June 2026. It would create a new W visa for caregivers, limited to private households and small caregiving businesses. It is only a proposal and is not law, so do not pay anyone who claims to offer a "W caregiver visa".</p>

<h2>How Much Do Caregivers Earn in the USA?</h2>

<p>The median pay for home health and personal care aides is <strong>$35,800 a year, or $17.21 an hour</strong> (Bureau of Labor Statistics, May 2025). The mean is $17.36 an hour. Most aides earn between about $13.00 an hour (lowest 10%) and $21.65 an hour (top 10%).</p>

<p>At the median that is roughly $2,983 a month before taxes, or about PKR 835,240 at an illustrative PKR 280 to the dollar. That rate is an example, not an exchange quote, so check your bank's current rate. Pay varies a lot by state: the BLS median is $24,230 in Texas ($11.65 an hour) and $29,420 in Pennsylvania.</p>

<p>Employers petitioning for you must pay at least the prevailing wage listed in the labor certification.</p>

<h2>What Are the Requirements for a Caregiver Job With Sponsorship?</h2>

<ul>
    <li>A job offer from a US employer willing to sponsor you.</li>
    <li>Physical ability to lift, transfer and assist clients.</li>
    <li>Basic English to follow care plans and talk with clients and nurses.</li>
    <li>Training or certification as the state requires, such as a home health aide or CNA programme. Federal rules set at least 75 training hours for aides at Medicare-certified agencies, and states may require more.</li>
    <li>A clean record for background checks and the immigrant visa process.</li>
    <li>A valid passport.</li>
</ul>

<p>A college degree is not required for aide roles. Experience in elder care or nursing helps.</p>

<h2>How Do You Find Real Caregiver Jobs With Visa Sponsorship?</h2>

<ol>
    <li>Search official and major job sites, such as the Department of Labor's CareerOneStop, and look for "EB-3", "visa sponsorship" or "international recruitment".</li>
    <li>Target employers that use foreign workers: nursing homes, assisted living facilities and home care agencies.</li>
    <li>Ask directly whether the employer will file a PERM and an I-140, who pays the costs and how long it usually takes.</li>
    <li>Look up the employer on its real website and confirm a published phone number and address.</li>
</ol>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/healthcare" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Healthcare Jobs on JobGader →
    </a>
</div>

<h2>How Do You Avoid Caregiver Visa Scams?</h2>

<p>Fake "caregiver visa" offers are common, and the labor certification costs belong to the employer. Be careful if you see:</p>

<ul>
    <li>Any fee from you for the job, PERM or sponsorship. The employer must pay labor certification costs.</li>
    <li>"Guaranteed visa in 30 days" promises. EB-3 takes years.</li>
    <li>Fake "W visa" or "care worker visa" claims.</li>
    <li>Contact only through WhatsApp, Telegram or a free email address.</li>
    <li>No named employer or no address.</li>
</ul>

<p>Report scams to the Federal Trade Commission.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a green card as a caregiver?</h3>
<p>Yes, through EB-3 "Other Workers" if a US employer sponsors you. The employer must complete a PERM labor certification and file an I-140 petition, and you wait for a visa number.</p>

<h3>Do I need experience to be a caregiver in the USA?</h3>
<p>Usually not for aide roles, but you will need state-required training or certification. Experience makes sponsorship offers easier to get.</p>

<h3>Can I bring my family?</h3>
<p>If you get an EB-3 green card, your spouse and unmarried children under 21 can generally immigrate with you.</p>

<h3>Can I apply for a caregiver job from Pakistan?</h3>
<p>You can apply for roles that offer EB-3 sponsorship, but few employers sponsor from abroad, and H-2B is not a normal option for caregivers. Verify every offer and never pay a fee.</p>

<h3>How much does a caregiver earn in the USA?</h3>
<p>The national median is $17.21 an hour or $35,800 a year (BLS, May 2025), but pay varies widely by state.</p>

<h3>How long does EB-3 sponsorship take?</h3>
<p>Years. The October 2026 Visa Bulletin final action date for EB-3 Other Workers was 1 January 2022, and the date changes every month.</p>

<h3>Is there a W visa for caregivers?</h3>
<p>Not yet. The Careworker Visa Act of 2026 is only a bill, so any offer of a W visa is a scam.</p>

<h3>Who pays for the sponsorship?</h3>
<p>The employer must pay the labor certification costs. Never pay an agent or employer for a sponsored job.</p>

<h2>People Also Search For</h2>

<h3>Caregiver jobs in USA with visa sponsorship</h3>
<p>Mostly through EB-3 Other Workers sponsorship.</p>

<h3>Home health aide visa sponsorship</h3>
<p>The same EB-3 route applies to aides and CNAs.</p>

<h3>Caregiver visa USA</h3>
<p>There is no dedicated caregiver visa today.</p>

<h3>EB-3 other workers wait time</h3>
<p>Check the monthly Visa Bulletin; the October 2026 date was 1 January 2022.</p>

<h3>Caregiver salary in USA</h3>
<p>$17.21 an hour at the median, BLS May 2025.</p>

<h3>Careworker Visa Act 2026</h3>
<p>A proposed bill, H.R. 9234, not law.</p>

<h3>H-2B caregiver</h3>
<p>Rare, because caregiving is not usually temporary work.</p>

<h3>Caregiver jobs from Pakistan</h3>
<p>Possible only with an employer willing to sponsor you from abroad.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026">How to Get a Warehouse Worker Job in USA From Pakistan</a> &mdash; how the H-2B process works for Pakistani applicants.</li>
    <li><a href="/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026">Hotel Housekeeper Jobs in USA With Visa Sponsorship (2026)</a> &mdash; another service job with H-2B rules.</li>
    <li><a href="/blog/truck-driver-salary-usa-2026">Truck Driver Salary in USA (2026)</a> &mdash; pay data for another US occupation.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Wage figures are from the US Bureau of Labor Statistics, May 2025 national and state estimates for occupation 31-1120; the Visa Bulletin date is from the October 2026 bulletin; both were reviewed on 9 October 2026. PKR conversions are editorial examples, not official statistics or job offers. Check current rules with USCIS and the Department of State before you act.</p>
HTML;
    }
}
