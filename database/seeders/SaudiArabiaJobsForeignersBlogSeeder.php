<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "How to Get a Job in Saudi Arabia as a Foreigner" — the umbrella guide for
 * the Saudi cluster. It owns the sponsorship route, the 2021 labour reforms,
 * the skill tiers, Saudization and the money rules, and hands the role-level
 * queries to the cleaner, construction, driver, nurse and other Saudi guides.
 *
 * Corrections to the draft (checked against stats.gov.sa, hrsd.gov.sa,
 * zatca.gov.sa, gosi.gov.sa, qiwa.sa, musaned.com.sa, pr.gov.sa and spa.gov.sa,
 * September 2026):
 *
 * 1. The draft describes Kafala as though nothing has changed. The Labour
 *    Reform Initiative took effect on 14 March 2021 for all private sector
 *    expatriates: transfer between employers at contract expiry without the
 *    employer's consent, exit and re-entry without the employer's approval,
 *    and final exit without the employer's consent.
 *
 * 2. The draft says VAT is 5%. The standard rate has been 15% since
 *    1 July 2020.
 *
 * 3. The draft tells readers that recruitment agencies commonly charge them
 *    10% to 20% of annual salary and to "clarify who bears this cost". Under
 *    article 40 of the Labour Law the employer bears recruitment fees,
 *    residence fees, the work permit and its renewal. A demand that the
 *    worker pay is not a negotiating point, it is the wrong side of the law.
 *
 * 4. The draft dates the skill classification to 2026. Reclassification of
 *    existing work permits began on 18 June 2025 under ministerial
 *    resolution 4602.
 *
 * 5. The draft omits the Professional Verification Programme entirely, which
 *    is the step that stops people at the embassy. It began on
 *    1 October 2023 and covered 128 countries by 26 August 2024.
 *
 * 6. The draft cites saudiprc.gov.sa for premium residency. That domain does
 *    not resolve; the Premium Residency Center portal is pr.gov.sa.
 *
 * 7. Every sector salary band in the draft comes from job boards and
 *    relocation blogs. There is no official Saudi occupational wage survey
 *    for expatriate workers, so the guide says so rather than repeating
 *    numbers no reader can check.
 *
 * 8. The draft's tourism employment figures are correct and kept: GASTAT put
 *    tourism employment at 1,009,691 in Q3 2025, of whom 764,520 (75.7%)
 *    were non-Saudi.
 *
 * 9. The draft puts the GOSI 2% occupational hazards contribution among the
 *    worker's deductions. Non-Saudis are covered by that branch only and the
 *    employer pays all of it; there is no employee deduction.
 *
 * 10. The draft gives the work permit levy as "SAR 400 to 800". The Council
 *     of Ministers schedule sets SAR 700 a month where foreign headcount does
 *     not exceed Saudi headcount and SAR 800 where it does, and the employer
 *     pays it under article 40.
 *
 * 11. The draft says Saudi Arabia is "23% to 40% cheaper than Dubai". No
 *     official source publishes that comparison; it traces to commercial
 *     cost-of-living indices, so it is dropped.
 *
 * 12. The draft's "77%, 14 million of 18.1 million" is real but stale: it is
 *     GASTAT's register-based count for Q1 2025. The Q1 2026 workbook gives
 *     15,168,982 non-Saudi of 19,390,726 registered workers, 78.2%.
 *
 * 13. The draft's "150 million visitors annually" is quoted as a Vision 2030
 *     target but is not among the numbered targets GASTAT or the Vision 2030
 *     site publish, so it is labelled an ambition. The Ministry of Tourism's
 *     2025 report counted about 123 million visits.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SaudiArabiaJobsForeignersBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/jobs?l=Saudi+Arabia';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
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
        $title = 'How to Get a Job in Saudi Arabia as a Foreigner';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A Saudi employer must sponsor you before any work visa exists, but the 2021 labour reforms let you change employer at contract expiry and leave the country without your employer approving it. Recruitment costs are the employer\'s by law.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-job-in-saudi-arabia-as-a-foreigner.jpg',
                'tags' => 'jobs in saudi arabia for foreigners, saudi work visa, iqama, kafala reform, labour reform initiative, nitaqat saudization, premium residency saudi arabia, saudi labour law',
                'meta_title' => 'Jobs in Saudi Arabia for Foreigners: Visa, Pay and Rules',
                'meta_description' => 'How foreigners get a Saudi job: employer sponsorship, the 2021 labour reforms, skill tiers, Saudization limits, and who legally pays your recruitment fee.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi Arabian Employers Hiring Sponsored Foreign Staff (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'saudi-foreign-hiring-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Sponsored Roles for Foreign Workers Across Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Up to 48 hours a week under the Saudi Labour Law, 36 hours for Muslim workers during Ramadan',
                'language' => 'English',
                // Saudi Arabia publishes no occupational wage survey for
                // expatriate workers, so the guide explains that rather than
                // quoting job-board bands here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Employer-sponsored roles across Saudi Arabia for foreign workers, covering the visa authorisation, work permit and Iqama route.',
                'seo_keywords' => 'jobs in saudi arabia for foreigners, saudi work visa jobs, expat jobs saudi arabia, iqama sponsorship jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Saudi employers across construction, healthcare, hospitality, technology and industry hire foreign staff on sponsored work visas, with the employer holding the visa quota and processing the Iqama after arrival.</p>

<h3>What the route involves</h3>
<p>An employer with an approved quota requests a visa authorisation, you apply for the work visa at a Saudi mission in your country, complete any required professional verification, then the employer issues your Iqama after you land.</p>

<h3>Common requirements</h3>
<ul>
    <li>A signed job offer and a contract uploaded to the Qiwa platform</li>
    <li>Attested qualification certificates, and professional verification where your country and profession are in scope</li>
    <li>A medical examination at an approved centre before the visa is issued</li>
    <li>A passport valid well beyond your intended start date</li>
    <li>A profession that is open to non-Saudis under the current Saudization decisions</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> visa quotas, Saudization percentages, work permit fees and Labour Law rights are set by the Ministry of Human Resources and Social Development, ZATCA and GOSI &mdash; not by JobGader. Under article 40 of the Labour Law the employer pays recruitment and work permit costs, not you.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You cannot move to Saudi Arabia to look for work. A Saudi employer has to hold a visa quota and request a visa authorisation before any work visa exists, and there is no job-seeker route that lets you arrive first and find an employer later.</strong> What has changed is what happens after you get there: since <strong>14 March 2021</strong> the Labour Reform Initiative lets private sector expatriates move between employers at contract expiry, travel out and back, and leave for good without the employer's consent.</p>

<p>This guide covers the route itself, the two checks that stop most people before the embassy, what Saudization actually blocks, and the money rules &mdash; including the one that decides whether an agency asking you for a fee is breaking the law.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/jobs?l=Saudi+Arabia" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Jobs in Saudi Arabia &rarr;
    </a>
</div>

<h2>The Route, Step by Step</h2>

<p>Every legitimate hire follows the same sequence. If a recruiter offers to skip a step, that is the signal to walk away.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Step</th>
            <th style="padding:10px;text-align:left;">Who does it</th>
            <th style="padding:10px;text-align:left;">What it produces</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>1. Visa quota</strong></td><td style="padding:10px;">Employer, through Qiwa</td><td style="padding:10px;">Permission to recruit a set number of foreign workers, limited by the firm's Nitaqat band</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>2. Job offer and contract</strong></td><td style="padding:10px;">Employer and you</td><td style="padding:10px;">A written fixed-term contract documented on Qiwa</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>3. Professional verification</strong></td><td style="padding:10px;">You, before the visa</td><td style="padding:10px;">Proof your qualification and experience are genuine, where your country and profession are in scope</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>4. Visa authorisation number</strong></td><td style="padding:10px;">Employer, via the ministry and MOFA</td><td style="padding:10px;">The number you quote on your own visa application</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>5. Work visa</strong></td><td style="padding:10px;">You, at a Saudi mission</td><td style="padding:10px;">Entry visa, after a medical at an approved centre</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>6. Iqama</strong></td><td style="padding:10px;">Employer, after you land</td><td style="padding:10px;">Your residence permit, normally within 90 days of arrival</td></tr>
    </tbody>
</table>
</div>

<p>The quota at step one is why identical applicants get different answers from different companies. A firm in a low Nitaqat band may simply have no visas left, whatever it thinks of your CV.</p>

<h2>Kafala Was Reformed in 2021, Not Abolished</h2>

<p>This is the part most guides get wrong in both directions: some write as though nothing changed, others announce that sponsorship is gone. Neither is right. Your employer is still your legal sponsor and still holds your Iqama. What the <strong>Labour Reform Initiative</strong>, in force from <strong>14 March 2021</strong> for all private sector expatriates, removed is three specific consent requirements.</p>

<ul>
    <li><strong>Changing employer.</strong> The ministry's wording is that mobility allows expatriate workers "to transfer between employers upon the expiry of the binding work contract without the employer's consent." During the contract, a transfer is still possible but a notice period and set procedures apply, and a worker in their first year normally still needs the current employer's approval.</li>
    <li><strong>Exit and re-entry.</strong> You can travel outside the Kingdom "without the employer's approval after submitting a request", and the employer is notified electronically.</li>
    <li><strong>Final exit.</strong> You can leave for good at the end of the contract "without the employer's consent", with the employer notified electronically and you carrying the financial consequences of breaking a contract early.</li>
</ul>

<p>All three run through Absher and Qiwa, so check that your contract is actually documented on Qiwa. An undocumented contract is the practical thing that strips these rights, not the law itself.</p>

<h2>The Two Checks That Stop People Before the Embassy</h2>

<h3>Professional verification</h3>

<p>The <strong>Professional Verification Programme</strong> is missing from almost every guide, and it is the most common reason a visa stalls. It started on <strong>1 October 2023</strong> for highly skilled professions in an initial group of countries, and by <strong>26 August 2024</strong> the ministry had extended it to <strong>128 countries</strong>, adding engineering and health professions, with the stated aim of covering roughly 160 countries. It runs jointly with the Ministry of Foreign Affairs.</p>

<p>In practice it means your degree and your claimed experience are checked, sometimes with a written or practical test, before the work visa is issued. Start it as soon as you have an offer, because it sits on the critical path.</p>

<h3>Skill classification</h3>

<p>Work permits are now issued against a skill tier rather than a flat category. Reclassification of existing permits began on <strong>18 June 2025</strong> under ministerial resolution 4602, sorting expatriate workers into <strong>high-skilled, skilled and basic</strong> tiers aligned to the Saudi classification of occupations.</p>

<p>Five things decide your tier: <strong>education, experience, professional skills and accreditation, wage, and age</strong>. Your tier follows your work permit, so it affects what your employer pays for you and how easily you move later. The specific wage bands quoted in circulation are not published on a page you can check, so treat any exact figure you read for the tiers with caution and ask your employer which tier your permit was issued in.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-get-a-job-in-saudi-arabia-as-a-foreigner-site.jpg" alt="Engineers and site staff reviewing plans on a Saudi construction project" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Saudization: What You Can and Cannot Be Hired For</h2>

<p>Saudi employers must fill a set share of their roles with Saudi nationals under <strong>Nitaqat</strong>. Firms sit in bands &mdash; Platinum, High Green, Mid Green, Low Green and Red &mdash; and the band controls how many foreign visas the firm gets and how freely it can renew them. A Platinum employer can hire you easily; a Red one often cannot hire you at all.</p>

<p>Separately, the ministry closes or restricts named professions on a rolling schedule. These are real decisions with dates, and they are the reason a job that existed two years ago may not be open to you now:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Field</th>
            <th style="padding:10px;text-align:left;">Saudization level</th>
            <th style="padding:10px;text-align:left;">Effective</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Administrative support professions (69 added)</td><td style="padding:10px;"><strong>100%</strong></td><td style="padding:10px;">5 April 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Procurement professions</td><td style="padding:10px;">70%</td><td style="padding:10px;">31 May 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Engineering professions (46 professions)</td><td style="padding:10px;">30%</td><td style="padding:10px;">30 June 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Project management professions</td><td style="padding:10px;">70%</td><td style="padding:10px;">14 February 2027</td></tr>
    </tbody>
</table>
</div>

<p>Two practical readings. First, <strong>administrative and office-support work is now the hardest door for a foreigner</strong>, because a large block of those professions has been taken to 100% Saudi. Second, the engineering decision also requires accreditation with the Saudi Council of Engineers, so for those roles registration matters as much as the visa.</p>

<p>Check the current decision for your own profession before you invest months in applications. A profession can move from open to closed in a single announcement.</p>

<h2>What the Official Numbers Actually Say</h2>

<p>You will see "expatriates are 77% of the Saudi workforce, about 14 million out of 18.1 million" repeated everywhere. That figure is real, and it comes from GASTAT's register-based labour market statistics &mdash; but it is the <strong>first quarter of 2025</strong>, and the market has moved since.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Registered workers</th>
            <th style="padding:10px;text-align:left;">Q1 2025</th>
            <th style="padding:10px;text-align:left;">Q4 2025</th>
            <th style="padding:10px;text-align:left;">Q1 2026</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saudi</td><td style="padding:10px;">4,068,813</td><td style="padding:10px;">4,211,344</td><td style="padding:10px;">4,221,744</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Non-Saudi</td><td style="padding:10px;">14,020,590</td><td style="padding:10px;">14,822,784</td><td style="padding:10px;"><strong>15,168,982</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Total</td><td style="padding:10px;">18,089,403</td><td style="padding:10px;">19,034,128</td><td style="padding:10px;"><strong>19,390,726</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Non-Saudi share</strong></td><td style="padding:10px;">77.5%</td><td style="padding:10px;">77.9%</td><td style="padding:10px;"><strong>78.2%</strong></td></tr>
    </tbody>
</table>
</div>

<p>So the honest current number is <strong>78.2%</strong>, not 77%, and the foreign workforce grew by over a million in a year. Saudization is tightening profession by profession, but the total number of foreign workers is still rising.</p>

<p>The rates come from a different GASTAT series and are not directly comparable to those headcounts, but they are worth knowing. In the fourth quarter of 2025, Saudi unemployment stood at <strong>7.2%</strong>, and the employment-to-population ratio was <strong>45.9% for Saudis against 80.7% for non-Saudis</strong>. Almost every non-Saudi in the country is there to work, while a much smaller share of the Saudi population is in the labour market at all. On population, GASTAT's 2024 estimates put the Kingdom at <strong>35.3 million people</strong>, of whom <strong>15.7 million (44.4%) are non-Saudi</strong>.</p>

<p>The one sector with a clean official split is tourism. GASTAT's tourism establishments statistics for the third quarter of 2025 counted <strong>1,009,691 people employed</strong> in the sector, of whom <strong>764,520 &mdash; 75.7% &mdash; were non-Saudi</strong>. The tourism figures circulating in most guides are, unusually, correct.</p>

<p>For scale, the Ministry of Tourism's 2025 annual report counted about <strong>123 million visits</strong> &mdash; 29.3 million international and 93.3 million domestic &mdash; with tourism spending of SAR 304 billion. You will also see a "150 million visitors by 2030" target quoted as though it were published policy; it is repeated by ministers but does not appear among the numbered targets on the Vision 2030 site, so treat it as an ambition rather than a figure you can cite.</p>

<h2>Pay: What Can Be Verified and What Cannot</h2>

<p>Here is the uncomfortable part. <strong>Saudi Arabia does not publish an occupational wage survey for expatriate workers.</strong> There is no Saudi equivalent of the American OEWS tables or British ONS earnings data that would let you look up what a foreign site engineer or nurse earns. Every "average expat salary in Saudi Arabia" table you find online &mdash; including the SAR 12,000 to 25,000 range and the sector bands quoted in most guides &mdash; traces back to job boards, recruitment agencies and relocation blogs, not to any Saudi authority.</p>

<p>So treat those numbers as marketing, and price your own offer from things you can check instead:</p>

<ul>
    <li><strong>There is no statutory minimum wage for expatriate workers.</strong> The SAR 4,000 figure often quoted as a minimum is the threshold at which a Saudi national counts as a full employee for Nitaqat purposes. It does not set a floor for your salary.</li>
    <li><strong>Your contract is the floor.</strong> Because there is no wage survey and no minimum, the written contract documented on Qiwa is the only number that binds anyone. Get every allowance into it.</li>
    <li><strong>Compare packages, not salaries.</strong> Housing and transport allowances and the end-of-service award are a large share of what Saudi employment is actually worth.</li>
</ul>

<h2>Tax and Deductions: What Really Comes Off</h2>

<p><strong>There is no personal income tax on employment income in Saudi Arabia.</strong> That part of the pitch is true. But two of the figures repeated in most guides are wrong, and one is on the wrong side of the payslip.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Cost</th>
            <th style="padding:10px;text-align:left;">What applies</th>
            <th style="padding:10px;text-align:left;">Who pays</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Income tax</strong></td><td style="padding:10px;">None on salary</td><td style="padding:10px;">&mdash;</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>VAT</strong></td><td style="padding:10px;"><strong>15%</strong> standard rate, not 5%</td><td style="padding:10px;">You, on what you buy</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>GOSI occupational hazards</strong></td><td style="padding:10px;">2% of the contributory wage, capped at SAR 45,000 a month</td><td style="padding:10px;"><strong>Employer</strong>, not deducted from you</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Work permit levy</strong></td><td style="padding:10px;"><strong>SAR 700 or SAR 800 a month</strong> per foreign worker</td><td style="padding:10px;"><strong>Employer</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Dependent fee</strong></td><td style="padding:10px;">Monthly, per dependent on your Iqama</td><td style="padding:10px;">You, if you bring family</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>End-of-service award</strong></td><td style="padding:10px;">Paid to you when you leave</td><td style="padding:10px;">Employer</td></tr>
    </tbody>
</table>
</div>

<p><strong>The VAT correction matters more than it looks.</strong> Guides that still print 5% were written before 1 July 2020 or copied from something that was. At <strong>15%</strong>, VAT takes a real bite out of the tax-free salary story, and it is the main reason a headline number in Riyadh does not stretch as far as the same number did a few years ago.</p>

<p><strong>The GOSI correction matters for a different reason.</strong> Non-Saudi workers are covered by the occupational hazards branch only, at 2% of the contributory wage, and <strong>the employer pays all of it</strong>. There is no employee deduction. If you see GOSI coming off your payslip, question it, because guides that list it under "what comes out of your salary" have put it on the wrong side. Saudi nationals are the ones who contribute, into annuities and unemployment insurance on top of the same employer-paid 2%.</p>

<p><strong>The work permit levy is also the employer's, and it is larger than most guides say.</strong> Under the Council of Ministers decision that sets it, an employer pays <strong>SAR 700 a month</strong> for each foreign worker where its foreign headcount does not exceed its Saudi headcount, and <strong>SAR 800 a month</strong> where it does, plus a one-off work licence fee. Small establishments with no more than nine staff and a handful of foreign workers have been exempted. The range of "SAR 400 to 800" printed in most guides is out of date at the bottom end, and none of it should ever reach your payslip.</p>

<p>The dependent fee is the one genuine monthly cost of bringing your family, charged per dependent on your Iqama and paid in advance when the dependent's Iqama is issued or renewed. It is widely quoted at SAR 400 a month, but it is administered through the passports authority rather than published on the labour ministry's own pages, so confirm the current amount through Absher before you decide to move a family.</p>

<h2>Who Pays the Recruitment Fee: The Most Important Paragraph Here</h2>

<p>Most guides tell you that agencies charge somewhere between 10% and 20% of your annual salary and advise you to "clarify who bears this cost before proceeding". That advice is wrong, and following it can cost you a year's earnings.</p>

<p><strong>Under article 40 of the Saudi Labour Law, the employer bears the fees for recruiting the non-Saudi worker, the residence fees, the work permit and their renewal</strong>, along with profession-change fees, exit and re-entry documentation and your return ticket home at the end of the relationship. The Musaned platform states the same rule and publishes employer-paid recruitment cost ceilings by country of origin.</p>

<p>So there is nothing to clarify or negotiate. If someone asks you for a percentage of your salary, a "visa fee", a "quota fee" or a deposit to secure a Saudi job, they are asking you to pay a cost the law puts on the employer. Treat it as a red flag, not a price.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-get-a-job-in-saudi-arabia-as-a-foreigner-office.jpg" alt="Professional arriving at a corporate office building in Riyadh" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Your Rights Once You Are Working</h2>

<ul>
    <li><strong>Hours.</strong> Article 98 sets 8 hours a day or 48 hours a week, cut to 6 hours a day or 36 hours a week for Muslim workers during Ramadan with no reduction in pay.</li>
    <li><strong>Overtime.</strong> Article 107 puts overtime at your hourly wage plus 50%.</li>
    <li><strong>Leave.</strong> Article 109 gives paid annual leave of at least 21 days, rising to 30 days once you have completed five continuous years with the employer.</li>
    <li><strong>End of service.</strong> Article 84 gives half a month's wage for each of the first five years and a full month's wage for each year after that, calculated on your last wage.</li>
    <li><strong>Your documents.</strong> Your passport is yours. An employer holding it is not exercising sponsorship, and the labour offices and the ministry's complaint channels exist for exactly this.</li>
</ul>

<h2>Premium Residency Is Not a Job-Seeking Route</h2>

<p>Premium residency is often presented as a back door into the job market. It is not. It is a status for people who already qualify on investment, property, talent or achievement, and the portal is <strong>pr.gov.sa</strong> &mdash; note that, because the domain quoted in several guides does not exist.</p>

<p>The Premium Residency Center offers products including <strong>Special Talent, Gifted, Investor, Entrepreneur and Real Estate Owner</strong> residencies alongside limited and unlimited duration options. Holders can run a business without a local sponsor, own property, sponsor family and travel in and out without a visa.</p>

<p>For the <strong>Special Talent</strong> product the portal's published conditions are an employment contract with an approved entity in a priority specialisation, a bachelor's degree or higher, more than three years of relevant experience, an employer recommendation, a minimum score on a points system and a <strong>minimum monthly wage of SAR 35,000</strong>. The tiered figures quoted elsewhere &mdash; SAR 14,000 for researchers, SAR 80,000 for executives &mdash; are not what the portal publishes today, so check pr.gov.sa directly before planning around them.</p>

<p>The sensible way to use this: take the ordinary sponsored route first, build a senior profile over a few years, and look at premium residency once you actually clear the threshold.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I go to Saudi Arabia to look for a job?</h3>
<p>No. There is no job-seeker visa. An employer must hold a visa quota and obtain a visa authorisation before you can apply for a work visa, so the offer always comes first.</p>

<h3>Is the Kafala system still in place?</h3>
<p>Your employer remains your legal sponsor, but since 14 March 2021 you can change employer at contract expiry, travel out and back, and take a final exit without the employer's consent.</p>

<h3>Should I pay a recruitment agency to find me a Saudi job?</h3>
<p>No. Article 40 of the Labour Law puts recruitment fees, residence fees and work permit costs on the employer. Anyone asking you for a percentage of your salary or a visa fee is asking for money the law says you do not owe.</p>

<h3>Is salary in Saudi Arabia really tax-free?</h3>
<p>Yes, there is no personal income tax on employment income. VAT is 15% on what you buy, though, and a dependent fee applies monthly for each family member on your Iqama.</p>

<h3>Which jobs are closed to foreigners in Saudi Arabia?</h3>
<p>A rolling list. Most recently 69 administrative support professions went to 100% Saudi from 5 April 2026, and procurement, engineering and project management all carry Saudization percentages.</p>

<h3>What is the Professional Verification Programme?</h3>
<p>A check on your qualifications and experience before the work visa is issued. It began on 1 October 2023 and covered 128 countries by August 2024, so start it as soon as you have an offer.</p>

<h3>How long does it take to get an Iqama after arriving?</h3>
<p>Your employer is responsible for issuing it after you enter on the work visa, normally within 90 days of arrival. Chase it, because your legal status and your ability to open a bank account depend on it.</p>

<h3>What happens to my end-of-service pay if I resign?</h3>
<p>Article 85 scales it by service: nothing under two years, a third of the award at two to five years, two thirds at five to ten years, and the full award at ten years or more. Check your years before you hand in notice.</p>

<h2>People Also Search For</h2>

<h3>Saudi work visa requirements</h3>
<p>An employer quota, a documented contract, professional verification where applicable, and a medical before the visa.</p>

<h3>Saudi Arabia average salary for expats</h3>
<p>No official occupational wage survey exists for expatriate workers, so the published averages come from job boards.</p>

<h3>Nitaqat bands explained</h3>
<p>Platinum, High Green, Mid Green, Low Green and Red, with the band controlling a firm's foreign visa quota.</p>

<h3>VAT rate in Saudi Arabia</h3>
<p>15% standard rate since 1 July 2020, not the 5% still quoted in older guides.</p>

<h3>Saudi Arabia dependent fee</h3>
<p>A monthly charge per dependent on your Iqama, paid in advance when the dependent's Iqama is issued or renewed.</p>

<h3>Iqama transfer without employer consent</h3>
<p>Allowed at contract expiry under the 2021 reforms; during the contract a notice period and set procedures apply.</p>

<h3>Saudi labour law end of service benefit</h3>
<p>Half a month's wage per year for the first five years, then a full month's wage for each further year.</p>

<h3>Premium residency Saudi Arabia cost</h3>
<p>Applied for at pr.gov.sa, with separate products for talent, investors, entrepreneurs and property owners.</p>

<h2>More Job Guides</h2>

<p>Going for a specific role in Saudi Arabia? These cover them:</p>

<ul>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the biggest sponsored sector and what the contracts look like.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; SCFHS registration, hospital employers and the licensing route.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; licence conversion and what driving work pays.</li>
    <li><a href="/blog/no-experience-jobs-in-saudi-arabia">No Experience Jobs in Saudi Arabia</a> &mdash; entry-level routes and the rules that protect you.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; licensing, employers and shift patterns.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; contract cleaning work and how it is recruited.</li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a> &mdash; the licence ladder for heavy vehicles.</li>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; Omanisation, the Gulf parallel to Saudization, from an employer side.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using GASTAT labour market, population and tourism statistics, Ministry of Human Resources and Social Development announcements and Labour Law articles, ZATCA guidance, Qiwa and Musaned rules and the Premium Residency Center portal. Saudization decisions, fees and thresholds change often. Always check the current official page for your own profession before you commit.</p>
HTML;
    }
}
