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
 * "Healthcare Assistant Jobs in UK" — NHS and care sector support work. The
 * broader healthcare guide covers registered clinical roles and the care
 * worker visa page covers the care closure, so this one owns HCA pay, bands,
 * checks and the HCA sponsorship rule.
 *
 * Corrections to the draft:
 *
 * 1. Its NHS bands are a year or more out of date. After the 3.3% award from
 *    1 April 2026, Band 2 in England is a single rate of £25,272 and Band 3
 *    runs from £25,760 to £27,476. Scotland and Wales pay more.
 *
 * 2. Its private care floor of £21,000 is below the National Living Wage.
 *    £12.71 an hour for 37.5 hours a week is £24,784.50 a year, and even the
 *    18 to 20 rate comes to £21,157.50.
 *
 * 3. Its specialist band of £25,000 to £30,000+ is not a basic salary at
 *    Band 2 or 3 in England or Wales; £30,000 needs Band 4, Scotland's Band 3
 *    top point or unsocial hours pay.
 *
 * 4. It says an enhanced DBS check is required everywhere. Scotland uses the
 *    PVG scheme and Northern Ireland uses AccessNI.
 *
 * 5. It says nothing about visas. Care workers (6135, 6136) closed to new
 *    overseas applicants on 22 July 2025, but hospital healthcare assistants
 *    (6131) can still be sponsored for a Band 3 or higher post paying at least
 *    £25,000 where registered nurses work. Band 1 and 2 posts cannot.
 *
 * 6. The Care Certificate has 16 standards since March 2025, and it is not a
 *    legal requirement. The draft's "steady vacancies nationwide" is also
 *    softened: NHS vacancy rates are falling and care worker turnover is at
 *    its lowest recorded level.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HealthcareAssistantJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-healthcare-assistant-jobs.html';

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
        $title = 'Healthcare Assistant Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'NHS Band 2 healthcare assistants earn £25,272 in 2026/27 and Band 3 up to £27,476. A £21,000 care salary is below the legal minimum, and only Band 3 HCA posts paying £25,000 or more can be sponsored.',
                'content' => $content,
                'featured_image' => 'blogs/healthcare-assistant-jobs-in-uk.jpg',
                'tags' => 'healthcare assistant jobs uk, hca jobs uk, nhs band 2 salary, nhs band 3 healthcare assistant, care assistant jobs uk, healthcare assistant visa sponsorship uk, care certificate, pvg scheme, nursing associate apprenticeship',
                'meta_title' => 'Healthcare Assistant Jobs in UK 2026: NHS Pay and Visas',
                'meta_description' => 'Healthcare assistant jobs in the UK: NHS Band 2 pays GBP 25,272 in 2026/27, why GBP 21,000 is below the legal minimum, and when HCAs can be sponsored.',
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
            ['name' => 'NHS Trusts, Care Homes & Home Care Providers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-hca-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Healthcare Assistant — NHS Trusts, Care Homes and Home Care, UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shifts across days, nights, weekends and bank holidays, usually 37.5 hours a week in the NHS',
                'language' => 'English',
                // NHS bands differ by nation and private providers set their
                // own rates above the legal minimum, so no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Healthcare assistant and care assistant roles in NHS hospitals, care homes and home care across the UK. Band 2 and Band 3 NHS posts and private care roles.',
                'seo_keywords' => 'healthcare assistant jobs uk, hca jobs, nhs band 2 jobs, care assistant jobs uk, healthcare support worker jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS hospitals, mental health and community services, care homes and home care agencies across the UK recruit healthcare assistants and healthcare support workers throughout the year.</p>

<h3>What the work involves</h3>
<p>Personal care such as washing, dressing and help with meals, moving and positioning patients, and keeping records. At Band 3, a limited range of clinical tasks under supervision, such as blood pressure, blood glucose monitoring and simple wound dressings.</p>

<h3>Requirements</h3>
<ul>
    <li>No set entry qualifications, though many employers ask for GCSEs in English and maths and some care experience</li>
    <li>A criminal record check: DBS in England and Wales, PVG in Scotland, AccessNI in Northern Ireland</li>
    <li>The right to work in the UK. Overseas applicants can only be sponsored for Band 3 or higher NHS-type posts paying at least &pound;25,000</li>
    <li>Availability for shifts, including nights and weekends</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>NHS England 2026/27.</strong> Band 2 &pound;25,272; Band 3 &pound;25,760 to &pound;27,476</li>
    <li><strong>Unsocial hours.</strong> Band 2 is paid 41% more for Saturdays and nights and 83% more for Sundays and bank holidays</li>
    <li><strong>Private care.</strong> At least the National Living Wage of &pound;12.71 an hour for workers aged 21 or over</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the band in the advert against the duties.</strong> Clinical tasks belong to Band 3, not Band 2.</p>

<p><strong>Note:</strong> pay, bands and visa eligibility are set by NHS pay agreements, employers and UK immigration rules &mdash; not by JobGader. Confirm the details with the employer and on gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Healthcare assistants keep NHS wards, care homes and home care running. It is one of the few healthcare jobs you can start without a qualification, and it can lead on to nursing. Before you apply, it helps to know the three things most guides get wrong: what the NHS pays in 2026, what a care salary legally has to be, and who can actually be sponsored from overseas.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-healthcare-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse Healthcare Assistant Jobs in UK &rarr;
    </a>
</div>

<h2>NHS Pay in 2026/27 Is Higher Than the Guides Say</h2>

<p>Guides put NHS Band 2 healthcare assistants at <strong>&pound;22,000 to &pound;24,000</strong> and Band 3 at &pound;24,000 to &pound;27,000. Those figures are out of date. A <strong>3.3% pay award</strong> took effect from <strong>1 April 2026</strong>, and the Agenda for Change rates in England are now:</p>

<ul>
    <li><strong>Band 2:</strong> <strong>&pound;25,272</strong> a year, about &pound;12.92 an hour. Band 2 is a single spot rate: there is no pay progression within it.</li>
    <li><strong>Band 3:</strong> <strong>&pound;25,760</strong> on entry, rising to <strong>&pound;27,476</strong> after two years.</li>
    <li><strong>Band 4:</strong> from &pound;28,392 &mdash; the band for assistant practitioners and registered nursing associates.</li>
</ul>

<p>The other nations pay differently:</p>

<ul>
    <li><strong>Scotland:</strong> Band 2 &pound;26,696, then &pound;28,988; Band 3 &pound;29,103, then &pound;31,409 &mdash; and on a <strong>36-hour week</strong> from April 2026.</li>
    <li><strong>Wales:</strong> Band 2 &pound;26,300; Band 3 &pound;26,300 to &pound;27,890.</li>
    <li><strong>Northern Ireland:</strong> the latest confirmed rates are 2025/26, with Band 2 at &pound;24,465 and Band 3 from &pound;24,937. Its health minister has said the aim is a 3.3% award for 2026/27.</li>
</ul>

<h2>Band 2 or Band 3: The Duties Decide the Pay</h2>

<p>The NHS job profiles draw the line clearly: <strong>Band 2 is concerned with personal care</strong> &mdash; bathing, toileting, dressing and support with meals &mdash; while <strong>Band 3 covers a limited range of clinical tasks carried out under supervision</strong>, such as taking blood pressure, blood glucose monitoring, wound observations and simple dressings, urinalysis, and removing a peripheral cannula or catheter.</p>

<p>The gap is small on paper &mdash; <strong>&pound;488 a year</strong> between Band 2 and the Band 3 entry point in England &mdash; but it matters twice. UNISON has campaigned for healthcare assistants who routinely do Band 3 clinical tasks on Band 2 contracts to be re-banded. And as the visa section below explains, <strong>Band 3 is the lowest band that can be sponsored</strong>.</p>

<p>Before you accept a post, compare the duties in the job description with the band in the advert.</p>

<h2>A &pound;21,000 Care Salary Is Below the Legal Minimum</h2>

<p>Guides put private care home and home care assistants at <strong>&pound;21,000 to &pound;25,000</strong>. The bottom of that range is not legal for most full-time workers.</p>

<ul>
    <li>The <strong>National Living Wage</strong> is <strong>&pound;12.71 an hour</strong> from 1 April 2026 for workers aged 21 and over. At 37.5 hours a week for 52 weeks, that is <strong>&pound;24,784.50</strong> a year &mdash; <strong>&pound;3,784.50 more than &pound;21,000</strong>.</li>
    <li>Even the <strong>18 to 20 rate of &pound;10.85</strong> comes to &pound;21,157.50 a year full-time.</li>
    <li>Skills for Care measured the median independent sector care worker at <strong>&pound;12.60 an hour in December 2025</strong>, before the April rise, with London highest at &pound;13.00. It found 49% of care workers paid below the Real Living Wage, and experienced care workers earning just 10 pence an hour more than new starters.</li>
</ul>

<p>NHS Band 2's hourly rate of about &pound;12.92 is only 21p above the National Living Wage, so the real difference between NHS and private work is often the extras: unsocial hours pay, pension and leave.</p>

<h2>Nights and Weekends Pay Much More</h2>

<p>NHS unsocial hours enhancements in England, Wales and Northern Ireland add a percentage to your hourly rate. Saturday means all of Saturday; nights means 8pm to 6am on weekdays.</p>

<ul>
    <li><strong>Band 2:</strong> <strong>41%</strong> extra for Saturdays and nights (about &pound;18.22 an hour), <strong>83%</strong> for Sundays and bank holidays (about &pound;23.64)</li>
    <li><strong>Band 3:</strong> <strong>35%</strong> and <strong>69%</strong> extra (about &pound;17.78 and &pound;22.26 at the entry point)</li>
    <li><strong>Scotland:</strong> 44% and 88% for Band 2, and 37% and 74% for Band 3</li>
</ul>

<p>That is how a theatre or mental health HCA reaches the <strong>&pound;30,000 or more</strong> some guides quote. It is not a basic salary at Band 2 or 3 in England or Wales, where Band 3 tops out at &pound;27,476 and &pound;27,890. A basic salary above &pound;30,000 means Band 4, or Scotland's Band 3 top point of &pound;31,409.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-assistant-jobs-in-uk-ward.jpg"
         alt="A healthcare assistant in blue scrubs holding a clipboard on a hospital ward"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What You Need to Start</h2>

<ul>
    <li><strong>No set entry requirements.</strong> The NHS careers service says there are none for healthcare assistants, though employers may ask for GCSEs in English and maths, or a healthcare qualification such as a BTEC or NVQ.</li>
    <li><strong>Some care experience.</strong> The same service says employers expect it &mdash; paid, volunteer or caring for a relative all count.</li>
    <li><strong>The Care Certificate.</strong> New healthcare assistants work towards it after starting. It was updated in March 2025 and now has <strong>16 standards</strong>, including a new one on learning disability and autism. It is a nationally agreed induction standard, not a qualification you must hold before applying.</li>
    <li><strong>The Level 2 Adult Social Care Certificate</strong> is a separate, Ofqual-regulated qualification for social care staff in England. It takes around six to eight months, and employers can claim funding for it. It is optional.</li>
</ul>

<h2>DBS, PVG or AccessNI: The Check Depends on the Nation</h2>

<p>Guides say an enhanced DBS check is required for every role. That is the system in <strong>England and Wales</strong>, where patient-facing healthcare assistants normally need an enhanced check, including the barred lists. Elsewhere it is different:</p>

<ul>
    <li><strong>Scotland</strong> uses the <strong>Protecting Vulnerable Groups (PVG) scheme</strong>, run by Disclosure Scotland. Joining it is a legal requirement for regulated roles.</li>
    <li><strong>Northern Ireland</strong> uses <strong>AccessNI</strong>, whose enhanced checks include a barred list check with the DBS.</li>
    <li><strong>Visa applicants</strong> in healthcare assistant and care worker occupations must also provide a criminal record certificate from every country they have lived in for 12 months or more in the last 10 years.</li>
</ul>

<h2>Can an Overseas Applicant Be Sponsored as a Healthcare Assistant?</h2>

<p>Guides skip this, and it is the question most overseas readers are asking. The answer depends on the <strong>occupation code and the band</strong>, not on whether the employer is the NHS.</p>

<ul>
    <li><strong>Care workers and senior care workers</strong> (codes 6135 and 6136) closed to new overseas applicants on <strong>22 July 2025</strong>. Only people already in the UK can switch into them, after working for the sponsor for at least three months, and only until <strong>22 July 2028</strong>.</li>
    <li><strong>Healthcare assistants in hospital settings</strong> are coded <strong>6131, nursing auxiliaries and assistants</strong>, which remains open to new overseas applicants &mdash; with three conditions.</li>
    <li><strong>The band.</strong> Band 1 and Band 2 jobs cannot be sponsored. The post must be <strong>Band 3 or higher</strong>.</li>
    <li><strong>The salary.</strong> At least <strong>&pound;25,000 a year</strong> and the going rate for the band.</li>
    <li><strong>The setting.</strong> Code 6131 only applies where registered nurses or other registered healthcare professionals also work, so a care home without nurses does not qualify.</li>
</ul>

<p>In practice that rules out most entry-level HCA posts, which are Band 2. It also rules out Northern Ireland's latest confirmed Band 3 entry rate of &pound;24,937 until its 2026/27 award is paid. For the care worker closure and its transitional rules in full, read our <a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">caregiver jobs in UK with visa sponsorship</a> guide; this page does not repeat it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-assistant-jobs-in-uk-patient-care.jpg"
         alt="A healthcare assistant supporting an elderly patient in a wheelchair"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Is Demand Still Strong?</h2>

<p>Yes, but guides overstate it. The official figures show easing, not a growing shortage:</p>

<ul>
    <li><strong>NHS England</strong> reported a vacancy rate of <strong>6.5%</strong> (97,475 posts) at the end of March 2026, down from 6.7% a year earlier. The Royal College of Nursing has warned that up to half of nursing graduates in Wales may be left without a job because posts are frozen.</li>
    <li><strong>Adult social care in England</strong> had a vacancy rate of <strong>7.0%</strong> in 2024/25, down from a peak of 10.5% in 2021/22. Home care was highest at 9.7%, residential care lowest at 4.4%.</li>
    <li><strong>Turnover</strong> for care workers was <strong>28.5%</strong>, which keeps jobs opening, though Skills for Care says it is at its lowest point in its figures.</li>
    <li><strong>International recruitment</strong> into social care halved, from 105,000 people in 2023/24 to 50,000 in 2024/25, which leaves more room for applicants already in the UK.</li>
</ul>

<p>No official source ranks UK regions for healthcare assistant hiring, so treat claims about the top regions with care. Home care has the most vacancies, and London pays care workers the most.</p>

<h2>From Healthcare Assistant to Nurse</h2>

<ul>
    <li><strong>Trainee nursing associate.</strong> A two-year foundation degree apprenticeship, usually paid at Band 3 while you train. You need GCSEs in maths and English at grade 4 or above, or Functional Skills Level 2.</li>
    <li><strong>Registered nursing associate.</strong> Registered with the Nursing and Midwifery Council and usually paid at Band 4.</li>
    <li><strong>Registered nurse degree apprenticeship.</strong> Nursing associate training can shorten it to two years.</li>
</ul>

<h2>What Else the NHS Offers</h2>

<ul>
    <li><strong>Pension.</strong> The employer pays <strong>23.7%</strong> of your pensionable pay. On Band 2 or 3 pay in England you contribute 6.5%.</li>
    <li><strong>Leave.</strong> 27 days plus 8 bank holidays on appointment, rising to 29 after five years and 33 after ten.</li>
    <li><strong>Hours.</strong> 37.5 a week in England, Wales and Northern Ireland; 36 in Scotland.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much does an NHS healthcare assistant earn in 2026?</h3>
<p>In England, Band 2 pays &pound;25,272 and Band 3 pays &pound;25,760 to &pound;27,476 from 1 April 2026. Scotland pays Band 2 &pound;26,696 to &pound;28,988 on a 36-hour week, and Wales pays Band 2 &pound;26,300.</p>

<h3>What is the minimum a care assistant can be paid?</h3>
<p>The National Living Wage of &pound;12.71 an hour for workers aged 21 or over, which is &pound;24,784.50 a year for 37.5 hours a week. A &pound;21,000 full-time salary is below it.</p>

<h3>What is the difference between Band 2 and Band 3?</h3>
<p>Band 2 covers personal care such as washing, dressing and meals. Band 3 adds a limited range of clinical tasks under supervision, such as blood pressure, blood glucose monitoring and simple wound dressings.</p>

<h3>Can I get a healthcare assistant job in the UK with visa sponsorship?</h3>
<p>Only for a Band 3 or higher post paying at least &pound;25,000, in a setting where registered nurses also work. Band 1 and 2 posts cannot be sponsored, and care worker roles closed to new overseas applicants on 22 July 2025.</p>

<h3>Do I need qualifications to become a healthcare assistant?</h3>
<p>There are no set entry requirements. Employers may ask for GCSEs in English and maths and usually expect some care experience, and you work towards the Care Certificate once you start.</p>

<h3>Is a DBS check required in Scotland?</h3>
<p>Scotland uses the PVG scheme run by Disclosure Scotland instead, and joining it is a legal requirement for regulated roles. Northern Ireland uses AccessNI.</p>

<h3>How many standards are in the Care Certificate?</h3>
<p>Sixteen, since the March 2025 update added a standard on learning disability and autism.</p>

<h3>How do I become a nurse from a healthcare assistant job?</h3>
<p>Through a two-year trainee nursing associate apprenticeship, usually paid at Band 3, then a registered nurse degree apprenticeship, which nursing associate training can shorten to two years.</p>

<h2>People Also Search For</h2>

<h3>NHS Band 2 salary 2026</h3>
<p>&pound;25,272 a year in England from 1 April 2026, a single spot rate.</p>

<h3>NHS Band 3 healthcare assistant salary</h3>
<p>&pound;25,760 rising to &pound;27,476 in England, and &pound;29,103 to &pound;31,409 in Scotland.</p>

<h3>Healthcare assistant jobs with visa sponsorship</h3>
<p>Possible only at Band 3 or above, paying at least &pound;25,000, in a setting with registered nurses.</p>

<h3>Care assistant jobs no experience</h3>
<p>There are no set entry requirements, but employers expect some care experience, including volunteering or caring for a relative.</p>

<h3>NHS unsocial hours pay Band 2</h3>
<p>41% extra for Saturdays and nights and 83% for Sundays and bank holidays in England, Wales and Northern Ireland.</p>

<h3>PVG check for healthcare jobs in Scotland</h3>
<p>A legal requirement for regulated roles, run by Disclosure Scotland in place of the DBS.</p>

<h3>Nursing associate apprenticeship</h3>
<p>A two-year foundation degree, usually paid at Band 3 during training and Band 4 once registered with the NMC.</p>

<h3>Care Certificate standards</h3>
<p>Sixteen standards since March 2025, completed as part of your induction.</p>

<h2>More Job Guides</h2>

<p>Comparing healthcare and entry-level work in the UK and abroad? These cover it:</p>

<ul>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; registered clinical roles, NMC registration and how NHS applications are scored.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the care worker closure and its transitional rules in full.</li>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; what nurses earn in America, state by state.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; the Gulf route for nurses and the SCFHS licence.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; another job care homes and hospitals hire for, priced against the legal minimum.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; entry-level work with no experience needed.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Pay awards, minimum wage rates, immigration rules and checking requirements change and differ between the four UK nations. Confirm the current position with the employer, NHS Employers, gov.uk and a regulated immigration adviser before applying.</p>
HTML;
    }
}
