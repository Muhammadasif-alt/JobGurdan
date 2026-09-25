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
 * "Nursing Assistant Jobs in the UK" - built on NHS Jobs, the NHS Employers
 * Agenda for Change scales, the Care Certificate standards and current Home
 * Office immigration rules, rather than the aggregator listing and the stale
 * pay figures the draft carried.
 *
 * Corrections to the draft (checked against jobs.nhs.uk, nhsemployers.org
 * 2026/27 pay scales, Care Certificate guidance and Home Office rules,
 * September 2026):
 *
 * 1. The draft made an aggregator the second apply route. Dropped; NHS Jobs is
 *    the only link.
 *
 * 2. The draft's "GBP 11.45 an hour" is not a lawful adult rate in 2026. The
 *    National Living Wage is GBP 12.71 and Agenda for Change Band 2 is
 *    GBP 13.61. The figure is several pay rounds out of date.
 *
 * 3. The draft's "GBP 20.95" top is an agency or enhanced-rate figure, not a
 *    band rate, and is not comparable with the bottom of its own range.
 *
 * 4. The draft never mentions the Care Certificate, which is the actual
 *    induction standard. It has sixteen standards since the March 2025 update
 *    added learning disability and autism awareness, and is completed within
 *    twelve weeks of starting.
 *
 * 5. The draft treats nursing assistant, healthcare assistant and nursing
 *    associate as interchangeable. Nursing associate is a separate role
 *    registered with the Nursing and Midwifery Council. The guide separates
 *    all three.
 *
 * 6. The draft ignores immigration, which is the single most important fact
 *    for an overseas reader. The care worker and senior care worker routes
 *    closed to new overseas applicants on 22 July 2025, with in-country
 *    switching and extensions running only to 22 July 2028.
 *
 * 7. "NHS pension, generous annual leave" is scoped: private care homes and
 *    agencies are not bound by Agenda for Change and set their own terms.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NursingAssistantJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.jobs.nhs.uk/candidate/search/results?keyword=Nursing%20Assistant';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on finding work, applying well and understanding what a job really pays.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Nursing Assistant Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Nursing assistant, healthcare assistant and nursing associate are three different jobs, and only one is registered. This guide separates them, gives the real 2026/27 band figures, explains the Care Certificate, and answers the visa question straight.',
                'content' => $content,
                'featured_image' => 'blogs/nursing-assistant-jobs-in-the-uk.jpg',
                'tags' => 'nursing assistant jobs uk, nhs band 2 pay, care certificate standards, nursing associate route, ward assistant jobs, clinical observations training, nhs jobs apply, healthcare career uk',
                'meta_title' => 'Nursing Assistant Jobs UK: Pay, Training, How to Apply',
                'meta_description' => 'Nursing assistant jobs in the UK: the 2026/27 band figures, the Care Certificate, the route to nursing associate, and the visa answer in full.',
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
            ['name' => 'NHS Trusts and UK Care Providers'],
            ['type' => 'Company', 'display_reference' => 'nhs-trusts-uk-care']
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
                'position' => 'Nursing Assistant and Healthcare Assistant, UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work including long days, nights, weekends and bank holidays, with bank and part-time posts widely available',
                'language' => 'English',
                // This listing spans NHS trusts, which pay Agenda for Change,
                // and private care providers and agencies, which set their own
                // rates above the National Living Wage. One band cannot
                // honestly represent both, so the scales are quoted in the
                // guide with their scope attached.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Ward and community nursing assistant and healthcare assistant roles with NHS trusts and UK care providers, for applicants with the right to work in the UK.',
                'seo_keywords' => 'nursing assistant jobs uk, healthcare assistant jobs, nhs band 2 pay, care certificate, nhs jobs apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS trusts and UK care providers recruit nursing assistants and healthcare assistants to deliver hands-on patient care under the supervision of registered nurses.</p>

<h3>What the work involves</h3>
<p>Supporting patients with washing, dressing, eating and mobility, taking and recording clinical observations, helping with safe transfers, and escalating any change in a patient's condition to the nurse in charge.</p>

<h3>Common requirements</h3>
<ul>
    <li>The right to work in the United Kingdom</li>
    <li>No prior healthcare experience for many posts, since training is provided</li>
    <li>Completion of the Care Certificate within twelve weeks of starting</li>
    <li>Physical stamina for long shifts, including nights and weekends</li>
    <li>An enhanced background check, arranged by the employer</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay and terms are set by the employing NHS trust or care provider &mdash; not by JobGader. Private care homes and agencies are not bound by the NHS pay scale. Apply through NHS Jobs or the provider directly, and never pay anyone for a job or a certificate of sponsorship.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Search NHS Jobs, and start by working out which of three similar-sounding jobs the advert is actually for.</strong> Nursing assistant, healthcare assistant and nursing associate are not the same role, and only one of them is registered with a regulator.</p>

<p>Get that wrong and you will apply for the wrong posts for months. Get it right and the career ladder in front of you is one of the clearest in UK healthcare.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.jobs.nhs.uk/candidate/search/results?keyword=Nursing%20Assistant" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#005eb8;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Search NHS Jobs &rarr;
    </a>
</div>

<h2>Three Job Titles, Correctly Separated</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#005eb8;color:#fff;">
            <th style="padding:10px;text-align:left;">Title</th>
            <th style="padding:10px;text-align:left;">What it is</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Healthcare assistant</strong></td><td style="padding:10px;">The broad support role, used across hospitals, community services and care homes. Not registered.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Nursing assistant</strong></td><td style="padding:10px;">The same family of work, but the title trusts tend to use on wards, usually with more clinical task training. Not registered.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Nursing associate</strong></td><td style="padding:10px;"><strong>A separate, regulated profession</strong>, registered with the Nursing and Midwifery Council and reached through a two-year training programme.</td></tr>
    </tbody>
</table>
</div>

<p>The practical difference: you can apply for nursing assistant and healthcare assistant posts tomorrow with no qualification. <strong>You cannot call yourself a nursing associate</strong> without completing the programme and joining the NMC register &mdash; it is a protected title, not a promotion you are given.</p>

<h2>What the Job Actually Involves</h2>

<p>On a ward, a nursing assistant works under the supervision of registered nurses and does the hands-on care:</p>

<ul>
    <li>Helping patients wash, dress, eat and move safely</li>
    <li><strong>Taking and recording clinical observations</strong> &mdash; temperature, pulse, respiration rate, blood pressure and oxygen saturation</li>
    <li>Assisting with safe transfers and repositioning</li>
    <li>Keeping the bay clean, stocked and safe</li>
    <li><strong>Escalating change.</strong> You are often the first person to notice a patient has deteriorated, and saying so is part of the job</li>
</ul>

<p>That last point is why the role is more clinical than the phrase "assistant" suggests. The observations you record feed the early warning score the nurses act on.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/nursing-assistant-jobs-in-the-uk-ward.jpg" alt="Hospital ward with beds and clinical equipment" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>What It Pays</h2>

<p>For an NHS trust post the scale is published. <strong>Agenda for Change 2026/27</strong>, effective 1 April 2026 after a 3.3% uplift:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#005eb8;color:#fff;">
            <th style="padding:10px;text-align:left;">Band</th>
            <th style="padding:10px;text-align:left;">Annual</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Band 2</strong> (single spot rate)</td><td style="padding:10px;">&pound;26,618</td><td style="padding:10px;">&pound;13.61</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Band 3</strong> entry</td><td style="padding:10px;">&pound;27,106</td><td style="padding:10px;">&pound;13.86</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Band 3</strong> top, after two years</td><td style="padding:10px;">&pound;28,850</td><td style="padding:10px;">&pound;14.75</td></tr>
    </tbody>
</table>
</div>

<p>Two corrections worth making loudly, because both circulate widely:</p>

<ul>
    <li><strong>&pound;11.45 an hour is not a current rate.</strong> It is below the National Living Wage of <strong>&pound;12.71</strong> and well below Band 2. Any guide quoting it is several pay rounds out of date.</li>
    <li><strong>Figures around &pound;20 an hour are agency or enhanced rates</strong>, not band rates. Nights, weekends and bank holidays attract unsocial hours enhancements on top of the band, and agency shifts price differently again. Comparing an agency headline with a band bottom tells you nothing.</li>
</ul>

<p>In a <strong>private care home or agency</strong>, Agenda for Change does not apply at all. The legal floor is the National Living Wage; everything above it is the provider's choice.</p>

<h2>The Care Certificate</h2>

<p>This is the induction standard the draft guides always miss. The Care Certificate is completed <strong>within twelve weeks of starting</strong>, and since the <strong>March 2025 update it has sixteen standards</strong> &mdash; the new one covering learning disability and autism awareness.</p>

<p>Two things follow that matter when you apply:</p>

<ul>
    <li><strong>You do not need it before you start.</strong> Employers deliver it. So "no experience" adverts genuinely mean it.</li>
    <li><strong>If you already hold it, say so on the application.</strong> It is a real advantage over another candidate with the same blank CV.</li>
</ul>

<h2>Where It Leads</h2>

<p>The ladder from here is unusually well defined:</p>

<ol>
    <li><strong>Nursing assistant or healthcare assistant</strong>, Band 2, learning on the ward</li>
    <li><strong>Band 3</strong> with extended clinical skills and responsibility</li>
    <li><strong>Trainee nursing associate</strong> &mdash; a two-year programme, often funded by the trust while you keep earning</li>
    <li><strong>Nursing associate</strong>, registered with the NMC</li>
    <li><strong>Registered nurse</strong>, via a shortened top-up route from nursing associate</li>
</ol>

<p>That is the strongest argument for taking an NHS post over an agency one early on: the agency pays more this month, the trust pays for the qualification that changes the next decade.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/nursing-assistant-jobs-in-the-uk-care.jpg" alt="Care worker supporting a patient" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Visa Answer, In Full</h2>

<p>This has changed, and a great deal of advice online is now simply wrong.</p>

<div style="background:#fff7ed;border-left:4px solid #c2410c;padding:16px 20px;margin:24px 0;">
    <p style="margin:0;"><strong>The care worker and senior care worker routes closed to new overseas applicants on 22 July 2025.</strong> Providers can no longer recruit care workers from abroad on that route. People already in the UK on those visas can extend and switch in-country, but only until <strong>22 July 2028</strong>.</p>
</div>

<p>What that means for you, plainly:</p>

<ul>
    <li><strong>If you are outside the UK:</strong> you cannot be sponsored into a care worker role. Anyone offering you one is either misinformed or taking your money.</li>
    <li><strong>If you are already in the UK with the right to work</strong> &mdash; settled or pre-settled status, a dependant visa, a Graduate visa, or an existing care visa &mdash; these jobs are open to you and are actively recruiting.</li>
    <li><strong>The general Skilled Worker threshold is &pound;41,700</strong> a year or the going rate, whichever is higher. A Band 2 post at &pound;26,618 does not reach it, so no ordinary route replaces the closed one.</li>
</ul>

<p>Registered nursing is a different matter, and remains a sponsorable profession. If your long-term aim is the UK, the honest route is qualifying as a nurse, not an assistant post.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Search NHS Jobs</strong> for both "nursing assistant" and "healthcare assistant", since trusts use the titles differently.</li>
    <li><strong>Check the band</strong> in the advert. Band 3 posts usually want extended clinical skills you can evidence.</li>
    <li><strong>Answer the person specification point by point.</strong> NHS Jobs applications are scored against it.</li>
    <li><strong>Lead with care, not clinical terms.</strong> Trusts recruit for compassion and reliability and train the rest.</li>
    <li><strong>Be honest about shifts.</strong> Long days, nights and weekends are the pattern, not the exception.</li>
    <li><strong>Expect an enhanced background check</strong> and references, arranged by the employer.</li>
    <li><strong>Never pay for a job or a certificate of sponsorship.</strong> It is illegal for an employer to charge you for one.</li>
</ol>

<p>If you want the care sector outside hospital, our <a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">caregiver guide</a> covers what the closed route means in practice, and our <a href="/blog/healthcare-assistant-jobs-in-uk">healthcare assistant guide</a> covers the wider support workforce.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a nursing assistant earn in the UK?</h3>
<p>In an NHS trust, Band 2 is &pound;26,618 or &pound;13.61 an hour for 2026/27, and Band 3 runs &pound;27,106 to &pound;28,850. Private providers set their own rates.</p>

<h3>Is a nursing assistant the same as a nursing associate?</h3>
<p>No. Nursing associate is a regulated profession registered with the Nursing and Midwifery Council, reached through a two-year training programme.</p>

<h3>Do I need experience to become a nursing assistant?</h3>
<p>Many posts need none. Employers train you and you complete the Care Certificate within twelve weeks of starting.</p>

<h3>How many standards are in the Care Certificate?</h3>
<p>Sixteen, since the March 2025 update added a standard covering learning disability and autism awareness.</p>

<h3>Can I get a visa as a care worker in the UK?</h3>
<p>Not as a new overseas applicant. The care worker and senior care worker routes closed on 22 July 2025, with in-country switching only until 22 July 2028.</p>

<h3>What observations does a nursing assistant take?</h3>
<p>Temperature, pulse, respiration rate, blood pressure and oxygen saturation, recorded so the nursing team can act on any change.</p>

<h3>Can a nursing assistant become a registered nurse?</h3>
<p>Yes. The usual route is trainee nursing associate, then nursing associate, then a shortened top-up to registered nurse, often funded by the trust.</p>

<h3>Is agency work better paid than NHS work?</h3>
<p>Per shift, often yes. But the NHS post carries the pension, the band progression and the funded route to a qualification the agency does not pay for.</p>

<h2>People Also Search For</h2>

<h3>NHS Band 2 pay 2026/27</h3>
<p>&pound;26,618 a year, &pound;13.61 an hour, a single flat rate with no step points.</p>

<h3>Care Certificate 16 standards</h3>
<p>Completed within twelve weeks of starting; the sixteenth standard covers learning disability and autism awareness.</p>

<h3>Nursing associate NMC register</h3>
<p>A regulated profession with a protected title, reached through a two-year training programme.</p>

<h3>Care worker visa UK closed</h3>
<p>Closed to new overseas applicants on 22 July 2025; in-country switching and extensions run to 22 July 2028.</p>

<h3>Healthcare assistant vs nursing assistant</h3>
<p>The same family of work. Trusts tend to use nursing assistant on wards, often with more clinical task training.</p>

<h3>NHS unsocial hours enhancement</h3>
<p>Paid on top of the band for nights, weekends and bank holidays, which is why headline hourly figures vary so much.</p>

<h3>Trainee nursing associate programme</h3>
<p>A two-year funded route that lets you keep earning while you qualify.</p>

<h3>National Living Wage 2026</h3>
<p>&pound;12.71 an hour from 1 April 2026 for workers aged 21 and over, the legal floor for private care providers.</p>

<h2>More Job Guides</h2>

<p>Looking across UK healthcare work? These cover the neighbouring routes:</p>

<ul>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the broader support role and where it is advertised.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the wider support workforce and its bands.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; how the sector is structured and who employs whom.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; what the closed route means in practice.</li>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; the qualified route in another market.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; where nursing pay and sponsorship work differently.</li>
    <li><a href="/blog/housekeeper-jobs-in-uk">Housekeeper Jobs in UK</a> &mdash; another hands-on role in the same settings.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; if you want the admin side instead.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; what the sponsorship rules actually allow.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; a faster route into UK hourly work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using NHS Jobs, the NHS Employers Agenda for Change pay scales for 2026/27, Care Certificate guidance and Home Office immigration rules. Pay scales, training standards and immigration rules change, and private care providers set their own terms. Always read the live advert and the current immigration guidance before you apply.</p>
HTML;
    }
}
