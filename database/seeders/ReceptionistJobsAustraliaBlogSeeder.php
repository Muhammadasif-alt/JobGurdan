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
 * "Receptionist Jobs in Australia" — front-desk work in corporate offices,
 * professional services, hotels and clinics. The medical receptionist guide
 * owns the health practice award and Medicare billing; this one owns the
 * Clerks Award rates most receptionists are paid on, the hotel front office
 * grades, and the qualification and visa position.
 *
 * Corrections to the draft:
 *
 * 1. Its entry-level range starts at AU$50,000. A full-time adult on the
 *    Clerks Award starts on $1,024.70 a week from 1 July 2026, about $53,284
 *    a year, and the National Minimum Wage alone annualises to $52,255.
 *
 * 2. It names no award. Reception and switchboard duties are listed in the
 *    Clerks Award classifications at Level 1, hotel receptionists fall under
 *    the Hospitality Award as front office grade 1, and medical and dental
 *    practices use the Health Professionals and Support Services Award.
 *
 * 3. It recommends a Certificate II or III in Business Administration. The
 *    Certificate III in Business Administration (BSB30415) was superseded by
 *    the Certificate III in Business (BSB30120), which has administration and
 *    medical administration streams.
 *
 * 4. Its senior range runs to AU$75,000+ and says legal receptionists earn
 *    more, with no source. The top Clerks Award level for this work, Level 3,
 *    annualises to about $61,469; anything above is employer choice.
 *
 * 5. Its apply link searches the American Indeed site with an Australia
 *    location. The listing points at au.indeed.com instead.
 *
 * 6. It says nothing about visas. Receptionist occupations are not on the Core
 *    Skills Occupation List, so the job cannot be sponsored through the Skills
 *    in Demand visa's Core Skills stream.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ReceptionistJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-receptionist-jobs.html';

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
        $title = 'Receptionist Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A full-time receptionist on the Clerks Award starts on $1,024.70 a week from 1 July 2026, hotel and clinic desks use other awards, only 43 per cent of receptionists work full-time, and the job is not on the Core Skills list.',
                'content' => $content,
                'featured_image' => 'blogs/receptionist-jobs-in-australia.jpg',
                'tags' => 'receptionist jobs australia, receptionist salary australia, clerks award receptionist pay, hotel receptionist jobs australia, front office grade 1, certificate iii in business bsb30120, receptionist jobs sydney, receptionist jobs melbourne, part time receptionist jobs, anzsco 542111',
                'meta_title' => 'Receptionist Jobs in Australia 2026: Award Pay and Skills',
                'meta_description' => 'Receptionist jobs in Australia: Clerks Award pay from $26.97 an hour, the hotel and clinic awards, the Certificate III in Business, and why visas are rare.',
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
            ['name' => 'Australian Corporate Offices, Hotels & Professional Services Firms (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-receptionist-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Receptionist — Corporate Offices, Law and Accounting Firms, Hotels and Real Estate Agencies, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time and casual; hotel front desks run early, late and weekend shifts',
                'language' => 'English',
                // Pay depends on which of three awards covers the employer, so
                // no single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Receptionist roles in Australian offices, law and accounting firms, hotels and agencies. Full-time, part-time and casual.',
                'seo_keywords' => 'receptionist jobs australia, front desk receptionist, hotel receptionist jobs australia, corporate receptionist sydney, part time receptionist jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Corporate offices, law and accounting firms, real estate agencies, hotels, car dealerships and councils across Australia hire receptionists for full-time, part-time and casual work.</p>

<h3>What the work involves</h3>
<p>Greeting visitors, answering and directing calls, managing bookings and meeting rooms, handling mail and deliveries, and basic data entry and filing. Hotel roles add check-in, check-out and shift work.</p>

<h3>Requirements</h3>
<ul>
    <li>Customer service or reception experience; no licence or formal qualification is required</li>
    <li>Microsoft Office, email and booking or practice software skills, and a clear phone manner</li>
    <li>The right to work in Australia: receptionist occupations are not on the Core Skills Occupation List</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Offices.</strong> Clerks &mdash; Private Sector Award 2020, from $1,024.70 a week ($26.97 an hour) at Level 1 year 1 from 1 July 2026</li>
    <li><strong>Hotels.</strong> Hospitality Industry (General) Award 2020, front office grade 1, from $1,029.10 a week</li>
    <li><strong>Casuals.</strong> A 25 per cent casual loading on top of the hourly rate</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask which award or enterprise agreement covers the job,</strong> and check its current pay guide on fairwork.gov.au before accepting an offer.</p>

<p><strong>Note:</strong> pay, hours and visa eligibility are set by employers, the Fair Work Commission and the Department of Home Affairs &mdash; not by JobGader. Confirm the details with the employer and on fairwork.gov.au before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Receptionists are the first person a visitor meets in a law firm, an accounting practice, a hotel lobby or a real estate agency, and the voice on the phone before anyone else. The job needs no licence, it exists in every Australian city and regional town, and it is a common first step into office work. Before you apply, it helps to know what the official data says, which award sets your pay, what the figures in most salary guides leave out, and which qualification is actually current.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-receptionist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128222; Browse Receptionist Jobs in Australia &rarr;
    </a>
</div>

<h2>What the Official Data Says</h2>

<p>Jobs and Skills Australia's profile for <strong>receptionists (ANZSCO 5421)</strong> covers general, hotel and motel, and medical receptionists together:</p>

<ul>
    <li>Only about <strong>43 per cent work full-time</strong>, so most of the work is part-time or casual.</li>
    <li>The <strong>median age is 41</strong>, and hotel and motel receptionists are younger, with a median age of 33.</li>
    <li>Median full-time earnings are <strong>$1,092 a week</strong> before tax.</li>
</ul>

<p>These ANZSCO-based figures are no longer being updated, and the earnings figure predates the award increases below. Use it as a picture of the occupation and the award as the legal minimum.</p>

<h2>What a Receptionist Does</h2>

<p>The Clerks Award, which covers most office receptionists, lists these <strong>reception or switchboard duties</strong> at its entry level:</p>

<ul>
    <li>Directing telephone callers to the right staff</li>
    <li>Issuing and receiving standard forms</li>
    <li>Relaying internal information</li>
    <li>Greeting visitors</li>
</ul>

<p>At Level 2 the award adds <strong>responding to enquiries</strong> using your knowledge of the organisation's services. Day to day, most employers also expect bookings and calendars, meeting room set-up, mail and deliveries, data entry, filing and keeping the reception area presentable. Hotel receptionists handle check-in, check-out and payments on rotating shifts, and medical receptionists handle patient records and Medicare billing.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/receptionist-jobs-in-australia-front-desk.jpg"
         alt="A smiling receptionist in a black blazer answering the phone at a white front desk, with the Australian flag and the Sydney Opera House and Harbour Bridge behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Award Sets Your Pay</h2>

<p>Salary guides quote one range for every receptionist. In Australia the legal minimum depends on the industry of the employer, and there are three main awards:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Where you work</th>
            <th style="padding:10px;text-align:left;">Award</th>
            <th style="padding:10px;text-align:left;">Starting rate from 1 July 2026</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;">
            <td style="padding:10px;">Corporate offices, law and accounting firms, real estate agencies, dealerships</td>
            <td style="padding:10px;">Clerks &mdash; Private Sector Award 2020 (MA000002)</td>
            <td style="padding:10px;">$1,024.70 a week, $26.97 an hour (Level 1 year 1)</td>
        </tr>
        <tr style="border-bottom:1px solid #e5e7eb;">
            <td style="padding:10px;">Hotels, motels, serviced apartments and resorts</td>
            <td style="padding:10px;">Hospitality Industry (General) Award 2020 (MA000009)</td>
            <td style="padding:10px;">$1,029.10 a week, $27.08 an hour (front office grade 1)</td>
        </tr>
        <tr style="border-bottom:1px solid #e5e7eb;">
            <td style="padding:10px;">GP clinics, specialist rooms and dental practices</td>
            <td style="padding:10px;">Health Professionals and Support Services Award 2020 (MA000027)</td>
            <td style="padding:10px;">Rates being phased up; see the <a href="/blog/medical-receptionist-jobs-in-australia">medical receptionist guide</a></td>
        </tr>
    </tbody>
</table>
</div>

<p>Councils, universities and many large employers pay under their own enterprise agreements, which must leave you better off overall than the award. The agreement is usually named in the job ad or the contract.</p>

<h2>Clerks Award Rates From 1 July 2026</h2>

<p>The Fair Work Commission raised award wages by <strong>4.75 per cent</strong> from the first full pay period on or after 1 July 2026. For full-time adult employees under the Clerks Award:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Classification</th>
            <th style="padding:10px;text-align:left;">Weekly</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
            <th style="padding:10px;text-align:left;">About a year</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 1 &mdash; year 1</td><td style="padding:10px;">$1,024.70</td><td style="padding:10px;">$26.97</td><td style="padding:10px;">$53,284</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 1 &mdash; year 2</td><td style="padding:10px;">$1,073.10</td><td style="padding:10px;">$28.24</td><td style="padding:10px;">$55,801</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 1 &mdash; year 3</td><td style="padding:10px;">$1,106.20</td><td style="padding:10px;">$29.11</td><td style="padding:10px;">$57,522</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 2 &mdash; year 1</td><td style="padding:10px;">$1,119.10</td><td style="padding:10px;">$29.45</td><td style="padding:10px;">$58,193</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 2 &mdash; year 2</td><td style="padding:10px;">$1,139.90</td><td style="padding:10px;">$30.00</td><td style="padding:10px;">$59,275</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Level 3</td><td style="padding:10px;">$1,182.10</td><td style="padding:10px;">$31.11</td><td style="padding:10px;">$61,469</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Casual work.</strong> Casuals receive a <strong>25 per cent casual loading</strong>, so a Level 1 year 1 casual earns $33.71 an hour.</li>
    <li><strong>Superannuation.</strong> Employers pay 12 per cent on top of wages.</li>
    <li><strong>Junior rates.</strong> Employees under 21 can be paid a percentage of the adult rate, so check the pay guide if you are a school leaver.</li>
</ul>

<h2>What the Usual Salary Ranges Leave Out</h2>

<p>Guides commonly quote <strong>AU$50,000 to AU$55,000</strong> for entry-level receptionists. The bottom of that range is not a legal full-time salary. A full-time adult on the Clerks Award earns at least <strong>about $53,284 a year</strong> from 1 July 2026, and the <strong>National Minimum Wage of $1,004.90 a week</strong> alone annualises to about $52,255. A lower figure on a job board usually means part-time hours or older data.</p>

<p>The same guides put senior and executive receptionists at AU$65,000 to AU$75,000 or more, and say legal and medical receptionists sit at the top. There is no official source for that. The highest Clerks Award level for this work, Level 3, annualises to about <strong>$61,469</strong>, legal receptionists are paid under the same award as any office, and anything above the award is the employer's choice. It is achievable at large firms and in Sydney and Canberra head offices, but it is a negotiated figure, not a standard band.</p>

<h2>Qualifications and Skills</h2>

<p>No licence or formal qualification is required. Guides often recommend a <strong>Certificate III in Business Administration</strong>, but that qualification (BSB30415) has been <strong>superseded by the Certificate III in Business (BSB30120)</strong>. The current qualification has streams including administration, customer engagement and medical administration, and it is the one to look for when comparing courses.</p>

<ul>
    <li>Clear phone manner and written English for emails and messages</li>
    <li>Microsoft Office, email and calendar skills</li>
    <li>Booking or practice software: hotel property management systems, and Best Practice or MedicalDirector in general practice</li>
    <li>Calm handling of difficult visitors and confidential information</li>
    <li>Customer service experience, which employers often value more than a certificate</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/receptionist-jobs-in-australia-lobby.jpg"
         alt="A receptionist on the phone at a front desk in a modern office lobby with a waiting area, a wall clock and a framed photo of the Sydney Opera House"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where to Find Receptionist Jobs</h2>

<ul>
    <li><strong>Job boards.</strong> Search the Australian Indeed site rather than the American one, and set alerts for Sydney, Melbourne and Brisbane, where most corporate listings are.</li>
    <li><strong>Recruitment agencies.</strong> Office support agencies fill many temporary and temp-to-permanent reception roles, which are a common way in.</li>
    <li><strong>Hotels and serviced apartments.</strong> Front office roles open year-round in capital cities and in tourist areas such as the Gold Coast and Cairns, with more shift work.</li>
    <li><strong>Company and council career pages.</strong> Larger employers and councils post directly, often under an enterprise agreement.</li>
</ul>

<h2>Visa Sponsorship Is Not a Realistic Route</h2>

<p>The main employer-sponsored route is the <strong>Skills in Demand visa</strong>, and its Core Skills stream only covers occupations on the Core Skills Occupation List. <strong>Receptionist occupations are not on the Core Skills Occupation List</strong>, so an employer cannot sponsor the role through that stream. The nomination would also have to meet the Core Skills Income Threshold, which was <strong>$76,515</strong> from 1 July 2025 and is indexed each July, well above award pay for this job.</p>

<p>Most overseas applicants who do reception work hold a visa with general work rights, such as a working holiday visa, or a student visa limited to 48 hours a fortnight during study periods. A listing that promises sponsorship for a receptionist job is worth questioning closely.</p>

<h2>Tips for Landing a Receptionist Job</h2>

<ul>
    <li><strong>Match your CV to the industry.</strong> Hotels look for shift availability and payment handling; law firms look for discretion and polished phone skills.</li>
    <li><strong>Prepare for scenario questions.</strong> Expect to explain how you would handle an upset visitor, several calls at once, or a request for confidential information.</li>
    <li><strong>Name the software you know.</strong> Booking systems, CRMs and Microsoft 365 all count, even if you learned them informally.</li>
    <li><strong>Ask which award applies.</strong> Knowing your classification level shows you understand the job and protects your pay.</li>
</ul>

<h2>Career Progression</h2>

<p>Reception gives you a view of how the whole business runs. With experience, receptionists commonly move into office manager, executive assistant, administration coordinator, practice manager in medical or legal settings, or human resources assistant roles. Under the Clerks Award, the step up is a higher classification level as your duties grow.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do receptionists earn in Australia?</h3>
<p>Most office receptionists are covered by the Clerks Award, which starts at $1,024.70 a week, or $26.97 an hour, from 1 July 2026. That is about $53,284 a year full-time, rising to about $61,469 at Level 3.</p>

<h3>Which award covers receptionists?</h3>
<p>Office receptionists are usually covered by the Clerks &mdash; Private Sector Award 2020, hotel receptionists by the Hospitality Industry (General) Award 2020, and medical and dental receptionists by the Health Professionals and Support Services Award 2020.</p>

<h3>Is AU$50,000 a normal starting salary for a receptionist?</h3>
<p>Not for a full-time adult. The Clerks Award minimum works out to about $53,284 a year, and the National Minimum Wage alone is about $52,255 a year.</p>

<h3>Do I need a qualification to be a receptionist in Australia?</h3>
<p>No. A Certificate III in Business (BSB30120), which replaced the Certificate III in Business Administration, can help, but customer service experience often matters more.</p>

<h3>Are receptionist jobs mostly part-time?</h3>
<p>Yes. Jobs and Skills Australia reports that about 43 per cent of receptionists work full-time.</p>

<h3>How much does a casual receptionist earn?</h3>
<p>Casuals get a 25 per cent loading, so a Level 1 year 1 casual under the Clerks Award earns $33.71 an hour from 1 July 2026.</p>

<h3>What does a hotel receptionist earn in Australia?</h3>
<p>Hotel receptionists are usually front office grade 1 under the Hospitality Award, paid at least $1,029.10 a week, or $27.08 an hour, from 1 July 2026, plus penalty rates for evenings and weekends.</p>

<h3>Can I get visa sponsorship as a receptionist in Australia?</h3>
<p>Very rarely. Receptionist occupations are not on the Core Skills Occupation List, so they cannot be sponsored through the Skills in Demand visa's Core Skills stream.</p>

<h2>People Also Search For</h2>

<h3>Receptionist jobs Sydney</h3>
<p>The largest market, across law firms, head offices, hotels and medical practices.</p>

<h3>Part-time receptionist jobs</h3>
<p>Most of the occupation: fewer than half of receptionists work full-time.</p>

<h3>Clerks Award receptionist pay rate</h3>
<p>MA000002, from $26.97 an hour at Level 1 year 1 from 1 July 2026.</p>

<h3>Hotel front desk jobs Australia</h3>
<p>Front office grade 1 under the Hospitality Award, with shift and weekend penalty rates.</p>

<h3>Certificate III in Business Administration</h3>
<p>Superseded by the Certificate III in Business, BSB30120.</p>

<h3>Casual receptionist hourly rate</h3>
<p>$33.71 an hour at Level 1 year 1 under the Clerks Award, including the casual loading.</p>

<h3>Medical receptionist jobs Australia</h3>
<p>A different award, and Medicare billing on top of front-desk work.</p>

<h3>Receptionist visa sponsorship Australia</h3>
<p>Not available through the Core Skills stream, because the occupation is not on the list.</p>

<h2>More Job Guides</h2>

<p>Comparing front-desk and office jobs? These cover them:</p>

<ul>
    <li><a href="/blog/medical-receptionist-jobs-in-australia">Medical Receptionist Jobs in Australia</a> &mdash; the health practice award, Medicare billing and privacy rules.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; clerical work under the same Clerks Award, and the checks employers run.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; entry-level award rates, junior pay and visa work limits.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; how the Skills in Demand visa and its occupation list work.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-desk work in the Gulf, and the gratuity on a basic salary.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; American office pay by state, and what an executive assistant earns.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; a genuinely sponsorable aged care route and its real award pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Award rates, the National Minimum Wage, qualification codes and visa occupation lists change. Confirm the current position with the Fair Work Ombudsman, training.gov.au and the Department of Home Affairs before applying or accepting an offer.</p>
HTML;
    }
}
