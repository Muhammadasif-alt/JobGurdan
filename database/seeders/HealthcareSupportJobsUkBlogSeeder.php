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
 * "Healthcare Support Jobs in UK" — the umbrella hub for the family of support
 * roles (healthcare assistant, support worker, nursing associate, therapy and
 * maternity support workers), the NHS-versus-social-care split, the Care
 * Certificate and the progression ladder. The healthcare assistant guide owns
 * the HCA pay-band deep dive, so this one cross-links to it rather than
 * repeating it.
 *
 * Corrections to the draft (checked 15 September 2026 against NHS Employers
 * pay scales, Health Careers, Skills for Care and gov.uk):
 *
 * 1. NHS pay "Band 2 GBP 22,000-24,000, Band 3 GBP 24,000-27,000, Band 4 GBP
 *    27,000-30,000+". The 2026/27 Agenda for Change scales for England are
 *    Band 2 GBP 25,272 (a single spine point, not a range), Band 3
 *    GBP 25,760-27,476 and Band 4 GBP 28,392-31,157, from 1 April 2026. The
 *    draft's ranges are roughly 2023-era and misstate Band 2 as a range.
 *
 * 2. "Care Certificate, 15 core competencies". Since March 2025 it is 16
 *    standards. There is also a separate Level 2 Adult Social Care Certificate
 *    qualification, which coexists with rather than replaces it.
 *
 * 3. "NVQ/QCF qualifications". QCF was replaced by the RQF years ago; the
 *    current awards are the Level 2 and Level 3 Diploma in Adult Care (RQF).
 *
 * 4. "An enhanced DBS check is required". Correct, but it is requested and
 *    paid for by the employer, not bought by the applicant, and Scotland uses
 *    PVG while Northern Ireland uses AccessNI.
 *
 * 5. The draft is silent on the overseas route. The Health and Care Worker
 *    visa closed to new overseas care workers and senior care workers (SOC
 *    6135 and 6136) on 22 July 2025; in-country switching runs until 22 July
 *    2028. So a healthcare support role is a domestic entry job, not a
 *    guaranteed sponsorship route.
 *
 * 6. Its apply link searches Indeed with a query string rather than the site's
 *    own healthcare support worker search page.
 *
 * The banners are used as supplied; the text carries the corrections. Both
 * records use updateOrCreate, so re-running is safe; it overwrites admin-panel
 * edits to these two rows.
 */
class HealthcareSupportJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-healthcare-support-worker-jobs.html';

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
        $title = 'Healthcare Support Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Healthcare support is a family of roles, not one job. NHS Band 2 pays GBP 25,272 in 2026/27, the Care Certificate is now 16 standards, and the overseas care-worker visa closed on 22 July 2025. Here is how the roles, settings and progression really work.',
                'content' => $content,
                'featured_image' => 'blogs/healthcare-support-jobs-in-uk.jpg',
                'tags' => 'healthcare support jobs uk, healthcare support worker, healthcare assistant jobs uk, nhs band 2 jobs, care certificate, nursing associate, healthcare support worker visa, hca jobs nhs',
                'meta_title' => 'Healthcare Support Jobs in UK 2026: Roles, Pay, Routes',
                'meta_description' => 'Healthcare support jobs in the UK: the real roles and settings, 2026/27 NHS pay, the 16-standard Care Certificate, the closed care-worker visa, and progression.',
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
            ['name' => 'NHS Trusts, Care Providers & Private Hospitals (Aggregated)'],
            ['type' => 'Public', 'display_reference' => 'uk-healthcare-support-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'England, Scotland, Wales and Northern Ireland', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Healthcare Support Worker — Hospital, Care Home and Community Support Roles, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time; shift work including nights, weekends and bank holidays',
                'language' => 'English',
                // NHS roles sit on Agenda for Change bands; social care and
                // agency pay differ, so no single range is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Healthcare support roles across the NHS, care homes and community services in the UK: healthcare assistants, support workers and nursing associates.',
                'seo_keywords' => 'healthcare support worker jobs, healthcare assistant jobs, nhs band 2 jobs, support worker jobs uk, care assistant jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS trusts, private hospitals, care homes and community services across the UK hire healthcare support workers &mdash; healthcare assistants, support workers, nursing associates and therapy assistants &mdash; to support patients alongside registered nurses and clinical staff.</p>

<h3>What the work involves</h3>
<p>Helping patients wash, dress, eat and move, recording observations such as blood pressure, temperature and pulse, keeping the environment safe and clean, offering emotional support, and reporting changes in a patient's condition to nursing staff.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree; employers ask for good literacy and numeracy, sometimes GCSEs in English and maths, and value any care experience, paid or voluntary</li>
    <li>The Care Certificate (16 standards) is usually completed in the first 12 weeks</li>
    <li>An enhanced DBS check (PVG in Scotland, AccessNI in Northern Ireland), arranged by the employer</li>
    <li>Values-based recruitment: NHS applicants show how they apply the NHS values</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>NHS Agenda for Change.</strong> In England for 2026/27, Band 2 is GBP 25,272, Band 3 GBP 25,760 to GBP 27,476 and Band 4 GBP 28,392 to GBP 31,157</li>
    <li><strong>Enhancements.</strong> Nights, weekends and bank holidays are paid at higher rates under Section 2</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check whether the role is NHS or social care,</strong> the band or hourly rate, and whether the employer funds the Care Certificate and further diplomas.</p>

<p><strong>Note:</strong> pay, bands and training support are set by each employer &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"Healthcare support" is not one job. It is a family of roles &mdash; healthcare assistant, support worker, nursing associate, therapy and maternity support worker &mdash; that keep the NHS and social care running alongside nurses and doctors. They are among the most accessible ways into healthcare, usually without a degree, and they lead somewhere. But the pay, the employer and even the background check depend on which role and which setting you pick, and there is one recent visa change that overseas readers need to know before they plan around a support role.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-healthcare-support-worker-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse Healthcare Support Jobs &rarr;
    </a>
</div>

<h2>The Roles Under "Healthcare Support"</h2>

<p>The job title varies by employer and setting, but these are the main support roles and where they sit on the NHS Agenda for Change (AfC) bands:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Usual NHS band</th>
            <th style="padding:10px;text-align:left;">What it adds</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Healthcare assistant (HCA) / support worker</td><td style="padding:10px;">Band 2, Band 3 with more duties</td><td style="padding:10px;">Personal care, observations, helping nurses</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Maternity support worker</td><td style="padding:10px;">Band 2&ndash;3</td><td style="padding:10px;">Supporting midwives before and after birth</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Therapy / allied health assistant</td><td style="padding:10px;">Band 3&ndash;4</td><td style="padding:10px;">Supporting physiotherapy or occupational therapy</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Assistant practitioner</td><td style="padding:10px;">Band 4</td><td style="padding:10px;">Delegated clinical tasks under supervision</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nursing associate</td><td style="padding:10px;">Band 4 (registered with the NMC)</td><td style="padding:10px;">A regulated role between HCA and nurse</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Care assistant (social care)</td><td style="padding:10px;">Not on AfC; hourly, set by the employer</td><td style="padding:10px;">Care homes and home care, outside the NHS</td></tr>
    </tbody>
</table>
</div>

<p>For the healthcare assistant role specifically &mdash; the most common entry job &mdash; our <a href="/blog/healthcare-assistant-jobs-in-uk">healthcare assistant guide</a> breaks the pay, bands and duties down in detail. This page is the map of the whole family.</p>

<h2>NHS or Social Care: The Split That Changes Everything</h2>

<p>The single most useful thing to understand is that "healthcare support" covers two different worlds:</p>

<ul>
    <li><strong>The NHS</strong> pays on the Agenda for Change bands, has a national pay deal, and offers a pension and structured progression. HCAs, nursing associates and therapy assistants sit here.</li>
    <li><strong>Social care</strong> &mdash; care homes and home care run by councils and private providers &mdash; pays hourly rates set by each employer, regulated by the Care Quality Commission rather than employed by the NHS. Care assistant roles sit here.</li>
</ul>

<p>They share skills and often staff move between them, but the pay, the contract and the regulator are not the same. Read which one a listing is before you compare its pay to another.</p>

<h2>What Healthcare Support Roles Pay</h2>

<p>For NHS roles in <strong>England, 2026/27</strong> (from 1 April 2026), the Agenda for Change salaries are:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Band</th>
            <th style="padding:10px;text-align:left;">2026/27 salary (England)</th>
            <th style="padding:10px;text-align:left;">Typical role</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Band 2</td><td style="padding:10px;">GBP 25,272 (single point)</td><td style="padding:10px;">Entry healthcare assistant</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Band 3</td><td style="padding:10px;">GBP 25,760 &ndash; GBP 27,476</td><td style="padding:10px;">Experienced or senior HCA</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Band 4</td><td style="padding:10px;">GBP 28,392 &ndash; GBP 31,157</td><td style="padding:10px;">Assistant practitioner, nursing associate</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Band 2 is a single spine point now,</strong> not a range &mdash; the widely quoted "GBP 22,000 to GBP 24,000" band is a few years out of date.</li>
    <li><strong>Scotland pays its HCAs more</strong> than England on the equivalent bands, and Wales and Northern Ireland negotiate their own deals.</li>
    <li><strong>Social care and agency pay differ,</strong> sometimes with a higher hourly rate but less security than an NHS contract, and London weighting lifts NHS pay in the capital.</li>
    <li><strong>Nights, weekends and bank holidays</strong> are paid at enhanced rates on NHS contracts.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-support-jobs-in-uk-team.jpg"
         alt="A team of NHS healthcare support workers in blue scrubs with a patient, beside a Healthcare Support Jobs in UK banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Qualifications and the Care Certificate</h2>

<p>Most support roles need <strong>no formal qualifications or degree</strong>. Health Careers says employers expect good literacy and numeracy, may ask for GCSEs in English and maths, and value any care experience, paid or voluntary. NHS employers also use values-based recruitment, asking how you would apply the NHS values in practice.</p>

<p>Two credentials come up once you start:</p>

<ul>
    <li><strong>The Care Certificate.</strong> A set of <strong>16 standards</strong> (updated from 15 in March 2025, adding learning disability and autism awareness), usually completed in the first 12 weeks of employment. Employers deliver it.</li>
    <li><strong>The Level 2 Adult Social Care Certificate qualification.</strong> A newer, Ofqual-regulated qualification built on the same standards. It coexists with the Care Certificate rather than replacing it, and is becoming the preferred route in England.</li>
</ul>

<p>To progress, many workers take a <strong>Level 2 or Level 3 Diploma in Adult Care</strong>. Note the naming: the old "NVQ" and "QCF" labels are gone &mdash; QCF was replaced by the Regulated Qualifications Framework (RQF), so the current award is the Diploma in Adult Care (RQF), not an "NVQ/QCF in Health and Social Care".</p>

<h2>DBS: The Check Depends on the Nation</h2>

<p>Every support role with patient contact needs a criminal record check, because it is regulated activity. It is an <strong>enhanced DBS check</strong> in England and Wales, a <strong>PVG scheme</strong> membership in Scotland, and an <strong>AccessNI</strong> check in Northern Ireland. In every case the <strong>employer arranges and pays for it</strong> as part of onboarding &mdash; it is not something you buy yourself before applying, and anyone asking you to pay upfront for a "mandatory DBS" to get a job is a warning sign.</p>

<h2>Can an Overseas Applicant Be Sponsored?</h2>

<p>This is the change that matters most for international readers, and most guides written before mid-2025 get it wrong. The Health and Care Worker visa route for <strong>care workers and senior care workers (occupation codes SOC 6135 and 6136) closed to new applicants from overseas on 22 July 2025</strong>. In-country switching for people already working lawfully in adult social care in the UK is kept open until 22 July 2028, under review.</p>

<p>What that means in practice:</p>

<ul>
    <li>A social care <strong>care assistant</strong> role can no longer bring you to the UK from abroad on that route.</li>
    <li>The Health and Care Worker visa itself <strong>remains open for other eligible health roles</strong>, such as registered nurses, nursing associates and doctors, on the eligible occupation list.</li>
    <li>An NHS healthcare support worker post is primarily a <strong>domestic entry job</strong>. Whether any specific vacancy can be sponsored depends on its exact occupation code being on the eligible list &mdash; do not assume a support role is a guaranteed visa route.</li>
</ul>

<p>For the wider visa picture, our <a href="/blog/jobs-in-uk-for-foreigners">jobs in UK for foreigners guide</a> sets out the Skilled Worker thresholds and the routes that are still open.</p>

<h2>Is Demand Still Strong?</h2>

<p>Yes, though the gap is narrowing. Skills for Care's State of the Adult Social Care Sector 2025 report put the vacancy rate at <strong>6.2 per cent</strong>, about 96,000 vacant posts on any given day &mdash; down from 7.0 per cent (around 108,000) the year before, and the lowest since 2015/16. NHS support roles are recruited year-round. Demand is real, but partly because turnover is high, so read the terms of any role rather than assuming security.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-support-jobs-in-uk-care.jpg"
         alt="A healthcare support worker in blue scrubs helping an elderly patient in a care setting, beside a Healthcare Support Jobs in UK banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Progression Ladder</h2>

<p>Healthcare support is one of the clearest routes into a clinical career. A common path runs:</p>

<ol>
    <li><strong>Healthcare assistant (Band 2)</strong> &mdash; the entry role.</li>
    <li><strong>Senior HCA or Band 3</strong> &mdash; more responsibility and a higher band.</li>
    <li><strong>Nursing associate (Band 4)</strong> &mdash; a role regulated by the Nursing and Midwifery Council, trained through the Nursing Associate Apprenticeship, that sits between HCA and registered nurse.</li>
    <li><strong>Registered nurse</strong> &mdash; via a Registered Nurse Degree Apprenticeship (RNDA), which your employer supports while you study part-time, or a shortened route from nursing associate.</li>
</ol>

<p>Allied health assistants can progress toward therapy roles the same way. Many NHS trusts actively fund staff who want to train as nurses, which is why support work is treated as a first step rather than a dead end.</p>

<h2>Skills Employers Look For</h2>

<ul>
    <li>Genuine compassion and a patient-centred approach</li>
    <li>Clear communication with patients, families and clinical teams</li>
    <li>Physical stamina for lifting, standing and long shifts</li>
    <li>Reliability and responsibility with vulnerable people</li>
    <li>Calm under pressure in emotionally hard situations</li>
    <li>Teamwork within a larger clinical team</li>
    <li>Willingness to keep training</li>
</ul>

<h2>Where to Find Healthcare Support Jobs</h2>

<ol>
    <li><strong>NHS Jobs.</strong> The official NHS recruitment site lists trust vacancies across all four nations.</li>
    <li><strong>Job boards.</strong> Search "healthcare assistant", "support worker" and "nursing associate", not only "healthcare support".</li>
    <li><strong>Care provider websites.</strong> Larger care groups post directly on their own careers pages.</li>
    <li><strong>Healthcare recruitment agencies.</strong> Many place support staff with flexible or agency shifts.</li>
    <li><strong>Local job centres and community boards.</strong> Useful for care home and community roles outside the cities.</li>
</ol>

<h2>Tips for Landing a Healthcare Support Job</h2>

<ul>
    <li><strong>Highlight caregiving experience,</strong> paid or unpaid, including caring for family.</li>
    <li><strong>Start the Care Certificate if you can,</strong> or show you understand what it involves.</li>
    <li><strong>Prepare for values-based questions</strong> about compassion, dignity and respect, not just technical ones.</li>
    <li><strong>Be flexible on shifts and settings.</strong> Nights and care-home roles widen your options.</li>
    <li><strong>Show you want to progress</strong> toward nursing associate or registered nurse training.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is a healthcare support worker?</h3>
<p>An umbrella term for support roles in health and social care &mdash; healthcare assistants, support workers, nursing associates and therapy assistants &mdash; who help patients alongside nurses and clinical staff.</p>

<h3>How much do healthcare support jobs pay in the UK?</h3>
<p>For NHS roles in England in 2026/27, Band 2 pays GBP 25,272, Band 3 GBP 25,760 to GBP 27,476 and Band 4 GBP 28,392 to GBP 31,157. Scotland pays more, and social care pay is hourly and set by the employer.</p>

<h3>Do I need a qualification to become a healthcare support worker?</h3>
<p>No degree is required. Employers expect good literacy and numeracy, sometimes GCSEs, and value care experience. New staff usually complete the Care Certificate in their first 12 weeks.</p>

<h3>How many standards are in the Care Certificate?</h3>
<p>Sixteen, since the March 2025 update added learning disability and autism awareness. A separate Level 2 Adult Social Care Certificate qualification builds on the same standards.</p>

<h3>Do I need a DBS check?</h3>
<p>Yes. Patient-facing roles need an enhanced DBS check in England and Wales, PVG in Scotland or AccessNI in Northern Ireland, arranged and paid for by the employer.</p>

<h3>Can I move to the UK from abroad as a care worker?</h3>
<p>Not on the care-worker route as of 22 July 2025, when it closed to new overseas applicants. In-country switching runs until 22 July 2028, and the Health and Care Worker visa stays open for nurses and other eligible health roles.</p>

<h3>What is a nursing associate?</h3>
<p>A role regulated by the Nursing and Midwifery Council, usually Band 4, that sits between a healthcare assistant and a registered nurse and is trained through an apprenticeship.</p>

<h3>Can a healthcare support worker become a nurse?</h3>
<p>Yes. A common route is HCA to nursing associate to registered nurse, often through a Registered Nurse Degree Apprenticeship that the employer supports while you study part-time.</p>

<h2>People Also Search For</h2>

<h3>Healthcare assistant jobs</h3>
<p>The most common support role, at Band 2 or Band 3; our healthcare assistant guide covers the pay in detail.</p>

<h3>NHS Band 2 salary</h3>
<p>GBP 25,272 in England for 2026/27, as a single spine point rather than a range.</p>

<h3>Support worker jobs UK</h3>
<p>Cover health and social care settings; check whether a role is NHS Agenda for Change or hourly social care pay.</p>

<h3>Nursing associate jobs</h3>
<p>A Band 4 role regulated by the NMC, reached through an apprenticeship from a support role.</p>

<h3>Care Certificate</h3>
<p>Sixteen standards completed in the first 12 weeks, delivered by the employer.</p>

<h3>Healthcare support worker visa UK</h3>
<p>The overseas care-worker route closed on 22 July 2025; support roles are mainly domestic entry jobs.</p>

<h3>Care assistant jobs</h3>
<p>Social care roles in care homes and home care, paid hourly and regulated by the Care Quality Commission.</p>

<h3>How to become a nurse from a healthcare assistant</h3>
<p>HCA to nursing associate to registered nurse, often via an employer-supported degree apprenticeship.</p>

<h2>More Job Guides</h2>

<p>Working out the wider UK picture? These cover it:</p>

<ul>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the HCA pay bands, nights and weekends, and the check by nation, in depth.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the social care side and what the visa change means.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; the wider NHS and clinical picture.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the Skilled Worker thresholds and the routes still open.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; another public-sector route, with its own pay scale.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers or immigration advice. NHS pay scales, the Care Certificate, qualification names and visa rules change and differ across England, Scotland, Wales and Northern Ireland. Confirm the current details with NHS Employers, Skills for Care, gov.uk and the employer before applying.</p>
HTML;
    }
}
