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
 * "Medical Receptionist Jobs in the UK" - built on NHS Jobs, the NHS Employers
 * Agenda for Change scales and the GP contract, rather than the aggregator
 * listing and the assumption that every medical receptionist works for the NHS.
 *
 * Corrections to the draft (checked against jobs.nhs.uk, nhsemployers.org
 * 2026/27 pay scales, england.nhs.uk GP contract material and Home Office
 * guidance, September 2026):
 *
 * 1. The draft made an aggregator the second apply route. Dropped; NHS Jobs is
 *    the only link.
 *
 * 2. The draft's biggest error: it says pay is "in line with the NHS Agenda for
 *    Change pay scale". Most medical receptionists work in GP practices, and
 *    GP practices are independent businesses that are not bound by Agenda for
 *    Change at all. The guide separates the two employers and gives the real
 *    figures for each.
 *
 * 3. The draft conflates clinical systems. EMIS Web and SystmOne are patient
 *    record systems; Docman is document management. They are not alternatives
 *    to each other.
 *
 * 4. The draft omits care navigation entirely, which is the part of the job
 *    that has actually changed. Under the GP contract Directed Enhanced
 *    Service, reception staff are trained to ask what a call is about and
 *    signpost the patient to the right service.
 *
 * 5. The draft omits call volume, which is what the job is really like:
 *    GP reception adverts commonly describe 40 to 60 calls a session.
 *
 * 6. The draft ignores right to work and sponsorship. A receptionist salary is
 *    far below the Skilled Worker threshold, so this is not a visa route.
 *
 * 7. "Generous annual leave" and "staff discounts" are NHS terms, not GP
 *    practice terms, and are scoped accordingly.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MedicalReceptionistJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.jobs.nhs.uk/candidate/search/results?keyword=Medical%20Receptionist';

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
        $title = 'Medical Receptionist Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Most medical receptionists work for a GP practice, not the NHS, and GP practices are not bound by the NHS pay scale. This guide separates the two employers, gives the real 2026/27 figures, and covers care navigation and the systems you need.',
                'content' => $content,
                'featured_image' => 'blogs/medical-receptionist-jobs-in-the-uk.jpg',
                'tags' => 'medical receptionist jobs uk, gp receptionist jobs, nhs receptionist pay, care navigator jobs, emis web training, systmone receptionist, nhs band 2 pay, gp practice jobs uk',
                'meta_title' => 'Medical Receptionist Jobs UK: Pay, Systems, How to Apply',
                'meta_description' => 'Medical receptionist jobs in the UK: why GP pay is not NHS pay, the 2026/27 band figures, care navigation, EMIS and SystmOne, and how to apply.',
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
            ['name' => 'UK GP Practices and NHS Trusts'],
            ['type' => 'Company', 'display_reference' => 'uk-gp-practices-nhs']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Medical Receptionist, UK GP Practices and NHS Trusts',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Practice opening hours, commonly early starts and late surgeries, with part-time and job-share posts widely advertised',
                'language' => 'English',
                // This listing spans NHS trusts, which pay Agenda for Change,
                // and GP practices, which set their own pay and are not bound
                // by it. One band cannot honestly represent both, so the
                // scales are quoted in the guide with their scope attached.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Reception and care navigation roles at GP practices and NHS trusts across the UK, for applicants with the right to work in the United Kingdom.',
                'seo_keywords' => 'medical receptionist jobs uk, gp receptionist jobs, care navigator jobs, nhs receptionist pay, emis web training',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>GP practices and NHS trusts across the United Kingdom recruit medical receptionists and care navigators as the first point of contact for patients.</p>

<h3>What the work involves</h3>
<p>Answering a high volume of calls, booking and managing appointments, processing repeat prescription requests, handling patient records on the practice clinical system, and signposting patients to the right service under care navigation.</p>

<h3>Common requirements</h3>
<ul>
    <li>The right to work in the United Kingdom</li>
    <li>Clear, calm communication with patients who may be distressed or unwell</li>
    <li>Confidence with a clinical system such as EMIS Web or SystmOne, or willingness to be trained</li>
    <li>Strict confidentiality, because you are handling patient data</li>
    <li>A background check appropriate to a patient-facing role</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay and terms are set by the individual GP practice or NHS trust &mdash; not by JobGader. A GP practice is an independent business and does not have to follow NHS pay bands. Apply through NHS Jobs or the practice directly, and never pay anyone for a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Search NHS Jobs, but read the employer line before the salary line.</strong> Most medical receptionists work in a GP surgery, and a GP surgery is an independent business that is not bound by NHS pay bands.</p>

<p>That single distinction explains almost every confusing thing you will read about this job: why two adverts in the same town offer different money, why some mention an NHS pension and some do not, and why "NHS receptionist pay" articles so often do not match the advert in front of you.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.jobs.nhs.uk/candidate/search/results?keyword=Medical%20Receptionist" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#005eb8;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Search NHS Jobs &rarr;
    </a>
</div>

<h2>Two Different Employers, Two Different Deals</h2>

<p>Medical receptionist posts are advertised by two kinds of organisation, and they are not the same employer:</p>

<ul>
    <li><strong>An NHS trust</strong> &mdash; a hospital or community service. Pay follows <strong>Agenda for Change</strong>, with the NHS pension, NHS annual leave and NHS terms.</li>
    <li><strong>A GP practice</strong> &mdash; an independent business holding a contract with the NHS. It sets its own pay, its own leave and its own terms. Many practices pay around the equivalent band, some pay less, some more.</li>
</ul>

<p>Both advertise on NHS Jobs, which is exactly why people assume they are the same. <strong>They are not.</strong> If an advert does not name a band, you are almost certainly looking at a practice, and the salary in the advert is the salary &mdash; there is no national scale behind it.</p>

<h2>What It Pays</h2>

<p>For NHS trust posts, the scale is published. Agenda for Change rates for <strong>2026/27</strong>, effective 1 April 2026 after a 3.3% uplift:</p>

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

<p>Band 2 no longer has step points; it is a flat rate. Band 3 has one step, reached after two full years of service, and Band 3 is typically where <strong>care navigation training and clinical system competence</strong> take you.</p>

<p>For a GP practice post, the floor is simply the law: the <strong>National Living Wage is &pound;12.71 an hour</strong> from 1 April 2026 for workers aged 21 and over. A good practice pays well above that. The point is that nothing obliges it to match Band 2.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/medical-receptionist-jobs-in-the-uk-reception.jpg" alt="Reception desk at a medical practice with a member of staff on the phone" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Care Navigation Changed the Job</h2>

<p>The old picture of a medical receptionist &mdash; book the appointment, hand over the prescription &mdash; is out of date. Under the GP contract's <strong>Directed Enhanced Service</strong>, reception staff are trained to ask what the call is about and <strong>signpost the patient to the right service or clinician first time</strong>. Many practices now advertise the post as <strong>Receptionist / Care Navigator</strong>.</p>

<p>That is why the job is harder than it looks from the waiting room:</p>

<ul>
    <li>You are asking clinical-sounding questions without being clinical, to a script you have been trained on</li>
    <li>You are the person who says there is no appointment today, to someone who is frightened</li>
    <li>GP reception adverts commonly describe <strong>40 to 60 calls a session</strong></li>
</ul>

<p>Practices know this is demanding, which is why care navigation training and the ability to stay calm under volume are what lift a candidate from Band 2 territory to Band 3 territory.</p>

<h2>The Systems, Correctly Explained</h2>

<p>Adverts name software, and guides routinely garble it. Here is the actual division of labour:</p>

<ul>
    <li><strong>EMIS Web</strong> and <strong>SystmOne</strong> are the <em>clinical record systems</em>. A practice runs one or the other. Between them they cover most of English general practice, and hospital settings may use something else such as Cerner Millennium.</li>
    <li><strong>Docman</strong> is <em>document management</em> &mdash; it handles incoming letters and clinical correspondence. It sits alongside the record system, it does not replace it.</li>
</ul>

<p>So "EMIS or Docman experience" is not an either/or the way a job board makes it sound. <strong>The one that matters most on your application is the record system the practice actually runs</strong>, because that is the one you will be in all day. If you have used one and the practice uses the other, say so plainly &mdash; most practices train for it, and pretending otherwise is found out in week one.</p>

<h2>Do You Need Experience?</h2>

<p>Often not. Practices recruit from retail, contact centres and hospitality all the time, because the transferable skill is handling people under pressure, not medical knowledge.</p>

<p>What genuinely helps:</p>

<ul>
    <li><strong>Any evidence of handling volume calmly</strong> &mdash; a busy till, a complaints line, a reception desk</li>
    <li><strong>Discretion you can describe.</strong> You will see neighbours' medical records. Practices ask about confidentiality, and vague answers lose offers</li>
    <li><strong>Typing and system speed</strong>; some practices run a short computer test</li>
    <li><strong>Availability for early starts and late surgeries</strong>, since practices open before and after normal office hours</li>
</ul>

<p>You will also need a background check appropriate to a patient-facing post. The level depends on the role and the setting, and the employer arranges it.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/medical-receptionist-jobs-in-the-uk-records.jpg" alt="Administrator working with patient records at a desk" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Can You Get This Job From Overseas?</h2>

<p>Realistically, no. Two reasons, and neither is about your ability:</p>

<ul>
    <li><strong>The salary does not clear the bar.</strong> The Skilled Worker general salary threshold is <strong>&pound;41,700 a year</strong>, or the going rate for the occupation, whichever is higher. A receptionist post at Band 2 pays &pound;26,618. It is not close.</li>
    <li><strong>The occupation is not a shortage route.</strong> Reception and general administrative work sits below the skill level the Skilled Worker route is built for, so a sponsor licence at the trust or practice does not help you.</li>
</ul>

<p>If you already have the right to work in the UK &mdash; settled or pre-settled status, a dependant visa, a Graduate visa &mdash; this is a very reachable first UK job. If you do not, treat any agent offering you a sponsored GP reception job as a scam.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Search NHS Jobs</strong> and filter by your travel distance, because practice hours rarely suit a long commute.</li>
    <li><strong>Read the employer name and decide which deal you are looking at</strong> &mdash; trust band, or practice rate.</li>
    <li><strong>Note the clinical system</strong> named in the advert and mirror it in your application if you have used it.</li>
    <li><strong>Write to the person specification</strong>, not the job title. NHS Jobs applications are scored against it line by line.</li>
    <li><strong>Prepare a confidentiality example</strong> and a busy-day example before the interview.</li>
    <li><strong>Expect a short computer or typing test</strong> at some practices.</li>
    <li><strong>Apply direct.</strong> No agent should charge you for an NHS or GP practice job.</li>
</ol>

<p>If you want the clinical side rather than the desk, our <a href="/blog/healthcare-assistant-jobs-in-uk">healthcare assistant guide</a> and our <a href="/blog/healthcare-support-jobs-in-uk">healthcare support guide</a> cover the routes in.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do medical receptionists get NHS pay?</h3>
<p>Only if the employer is an NHS trust. GP practices are independent businesses and set their own pay, even though they advertise on NHS Jobs.</p>

<h3>What band is a medical receptionist?</h3>
<p>In an NHS trust, typically Band 2 at &pound;26,618, or Band 3 at &pound;27,106 rising to &pound;28,850 for 2026/27. A GP practice uses no band at all.</p>

<h3>What is a care navigator?</h3>
<p>A reception role extended under the GP contract Directed Enhanced Service, trained to ask what a call is about and signpost the patient to the right service first time.</p>

<h3>Do I need EMIS or SystmOne experience?</h3>
<p>It helps, but most practices train. EMIS Web and SystmOne are the clinical record systems; Docman is separate document management software.</p>

<h3>Do I need experience to be a medical receptionist?</h3>
<p>Often not. Practices recruit from retail, contact centres and hospitality, because handling people under pressure is the transferable skill.</p>

<h3>How busy is GP reception work?</h3>
<p>Busy. Adverts commonly describe 40 to 60 calls a session, alongside the desk, prescriptions and the clinical system.</p>

<h3>Is a DBS check required?</h3>
<p>A background check appropriate to a patient-facing role is standard. The level depends on the post and the setting, and the employer arranges it.</p>

<h3>Can I get a visa for a medical receptionist job?</h3>
<p>No. The Skilled Worker threshold is &pound;41,700 or the going rate, and the occupation is below the skill level the route is built for.</p>

<h2>People Also Search For</h2>

<h3>NHS Band 2 pay 2026/27</h3>
<p>&pound;26,618 a year, &pound;13.61 an hour, a single flat rate with no step points.</p>

<h3>NHS Band 3 pay 2026/27</h3>
<p>&pound;27,106 rising to &pound;28,850 after two full years, or &pound;13.86 to &pound;14.75 an hour.</p>

<h3>GP receptionist pay UK</h3>
<p>Set by the practice, not by Agenda for Change. The legal floor is the National Living Wage of &pound;12.71.</p>

<h3>Care navigator training</h3>
<p>Delivered by the practice under the GP contract Directed Enhanced Service, and often what takes a post from Band 2 to Band 3.</p>

<h3>EMIS Web vs SystmOne</h3>
<p>The two main GP clinical record systems. A practice runs one; you will use it all day.</p>

<h3>Docman NHS software</h3>
<p>Document management for incoming clinical correspondence. It sits alongside the record system, not instead of it.</p>

<h3>NHS Jobs application person specification</h3>
<p>Applications are scored against it line by line, so answer it point by point rather than writing a general letter.</p>

<h3>Medical receptionist visa sponsorship</h3>
<p>Not available. The salary is far below the &pound;41,700 Skilled Worker threshold.</p>

<h2>More Job Guides</h2>

<p>Looking across UK healthcare and admin work? These cover the neighbouring routes:</p>

<ul>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the clinical side of the same setting.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the wider support workforce and its bands.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; how the sector is structured and who employs whom.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; the same admin skills outside healthcare.</li>
    <li><a href="/blog/school-administrator-jobs-in-uk">School Administrator Jobs in UK</a> &mdash; another public-facing admin route with term-time hours.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the honest position on care sponsorship now.</li>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; the same people skills, on a published salary.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; a faster route into UK hourly work.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; what the sponsorship rules actually allow.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; if the commute to a practice does not work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using NHS Jobs, the NHS Employers Agenda for Change pay scales for 2026/27, NHS England general practice contract material and Home Office immigration guidance. Pay scales, contract terms and immigration rules change, and GP practices set their own terms. Always read the live advert and the person specification before you apply.</p>
HTML;
    }
}
