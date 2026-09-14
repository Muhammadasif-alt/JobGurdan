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
 * "Medical Receptionist Jobs in Australia" — front-desk staff in general
 * practice, specialist rooms, radiology and allied health. The office
 * assistant guide covers clerical work under the Clerks Award, and this one
 * owns the award that covers health practice receptionists, the Medicare
 * billing and privacy rules the job runs on, and the visa position.
 *
 * Corrections to the draft:
 *
 * 1. It says hundreds of new positions are posted every week with no source.
 *    Jobs and Skills Australia counts about 46,700 medical receptionists
 *    (ANZSCO 542114), only a third of them full-time, with moderate future
 *    growth.
 *
 * 2. It names no pay or award. Receptionists in medical and dental practices
 *    are covered by the Health Professionals and Support Services Award 2020,
 *    which is being revised under the gender undervaluation review, and no
 *    adult can be paid below the $26.44 National Minimum Wage.
 *
 * 3. It lists Medicare billing without the November 2025 changes. Bulk
 *    billing incentives now apply to every Medicare-eligible patient, and a
 *    practice incentive pays 12.5 per cent to practices that bulk bill every
 *    eligible service.
 *
 * 4. It says some listings mention visa sponsorship. Medical Receptionist is
 *    not on the Core Skills Occupation List, so the job cannot be sponsored
 *    through the Skills in Demand visa's Core Skills stream.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MedicalReceptionistJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-medical-receptionist-jobs.html';

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
        $title = 'Medical Receptionist Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Medical receptionists are covered by the Health Services Award, only a third work full-time, bulk billing incentives cover every Medicare patient since 1 November 2025, and the job is not on the Core Skills Occupation List.',
                'content' => $content,
                'featured_image' => 'blogs/medical-receptionist-jobs-in-australia.jpg',
                'tags' => 'medical receptionist jobs australia, medical receptionist pay australia, gp receptionist jobs, health services award receptionist, medical receptionist sydney, bulk billing incentive, best practice software, certificate iii health administration, anzsco 542114',
                'meta_title' => 'Medical Receptionist Jobs in Australia 2026: Pay and Visas',
                'meta_description' => 'Medical receptionist jobs in Australia: the award that sets pay, part-time work, Medicare bulk billing changes, privacy rules and why visas are rare.',
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
            ['name' => 'Australian GP Clinics, Specialist Practices & Radiology Networks (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-medical-receptionist-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Medical Receptionist — GP Clinics, Specialist Rooms, Radiology and Allied Health, Australian Practices',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time and casual; many clinics need early, evening and Saturday cover',
                'language' => 'English',
                // Award rates for this job are being revised under the gender
                // undervaluation review, so no fixed range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Medical receptionist roles in Australian GP clinics, specialist rooms and radiology. Full-time, part-time and casual shifts.',
                'seo_keywords' => 'medical receptionist jobs australia, gp receptionist jobs, medical receptionist sydney, radiology receptionist jobs, dental receptionist jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>General practices, specialist rooms, radiology networks, allied health clinics and Aboriginal community health services across Australia hire medical receptionists for full-time, part-time and casual shifts.</p>

<h3>What the work involves</h3>
<p>Greeting patients, booking appointments, handling Medicare, DVA and private billing, updating records in practice software such as Best Practice, MedicalDirector or Genie, and keeping patient information confidential.</p>

<h3>Requirements</h3>
<ul>
    <li>Reception or customer service experience; a Certificate II or III, or at least a year of relevant experience, fits the ANZSCO skill level</li>
    <li>Computer skills, basic medical terminology and a clear phone manner</li>
    <li>The right to work in Australia: the occupation is not on the Core Skills Occupation List</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The award.</strong> Health Professionals and Support Services Award 2020, with progression through the support services levels</li>
    <li><strong>The floor.</strong> No adult may be paid below the National Minimum Wage of $26.44 an hour from 1 July 2026, and casuals get a 25 per cent loading</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the current award pay guide on fairwork.gov.au before accepting an offer,</strong> because rates under this award are changing in stages.</p>

<p><strong>Note:</strong> pay, hours and visa eligibility are set by employers, the Fair Work Commission and the Department of Home Affairs &mdash; not by JobGader. Confirm the details with the employer and on fairwork.gov.au before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Medical receptionists are the first and last person a patient speaks to at a GP clinic, specialist practice, radiology centre or community health service. It is a job found in almost every Australian suburb and regional town, and one of the most part-time-friendly jobs in health care. Before you apply, it helps to know what the official data says about the work, which award sets your pay, the billing and privacy rules you will handle every day, and why the job is almost never a visa route.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-medical-receptionist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128222; Browse Medical Receptionist Jobs in Australia &rarr;
    </a>
</div>

<h2>What the Official Data Says</h2>

<p>Guides say hundreds of new positions are posted every week. Job boards change daily, so that number cannot be checked. Jobs and Skills Australia's profile for <strong>medical receptionists (ANZSCO 542114)</strong> gives a steadier picture:</p>

<ul>
    <li>About <strong>46,700</strong> people work in the occupation.</li>
    <li>Only <strong>33 per cent work full-time</strong>, so most jobs are part-time or casual.</li>
    <li>98 per cent are women, and the <strong>median age is 46</strong>.</li>
    <li>Future growth is rated <strong>moderate</strong>.</li>
</ul>

<p>The occupation sits at ANZSCO skill level 4: a Certificate II or III, or at least one year of relevant experience in place of the qualification. Many clinics train new staff on the job, but most listings still ask for reception or customer service experience.</p>

<h2>What a Medical Receptionist Does</h2>

<ul>
    <li>Greeting patients and managing the waiting room</li>
    <li>Answering calls, booking, rescheduling and cancelling appointments</li>
    <li>Processing Medicare, DVA and private health fund billing</li>
    <li>Updating patient records in practice management software</li>
    <li>Coordinating with GPs, specialists, nurses and allied health staff</li>
    <li>Filing, correspondence, recalls and reminders</li>
</ul>

<p>The software most often named is <strong>Best Practice</strong> and <strong>MedicalDirector</strong> in general practice, and <strong>Genie</strong> in specialist rooms. A <strong>Certificate III in Health Administration (HLT37315)</strong> covers medical terminology and records, but it is not a legal requirement.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/medical-receptionist-jobs-in-australia-front-desk.jpg"
         alt="A smiling medical receptionist in navy scrubs answering the phone at a clinic front desk, with the Sydney Opera House and Harbour Bridge in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Award That Sets Your Pay</h2>

<p>Guides do not mention pay at all. In Australia it is set by law. The Fair Work Ombudsman says receptionists working for employers in the health industry, including medical and dental practices, are covered by the <strong>Health Professionals and Support Services Award 2020</strong> (MA000027), often called the Health Services Award.</p>

<ul>
    <li><strong>Your level.</strong> Receptionists are support services employees. With experience you progress to <strong>Support services level 2 or level 3</strong>, depending on your responsibility, accountability, supervision, experience and training.</li>
    <li><strong>The floor.</strong> No adult can be paid less than the National Minimum Wage of <strong>$26.44 an hour</strong>, or $1,004.90 a week, from 1 July 2026.</li>
    <li><strong>Casual work.</strong> Casuals receive a <strong>25 per cent casual loading</strong> instead of paid leave.</li>
    <li><strong>Superannuation.</strong> Employers pay 12 per cent on top of wages.</li>
    <li><strong>Rates are changing.</strong> This award is part of the Fair Work Commission's gender undervaluation review, and increases are being phased in. Check the current MA000027 pay guide on fairwork.gov.au for your level before accepting an offer.</li>
</ul>

<p>The banner's "health insurance" is not a standard benefit here. Australian residents use Medicare, and private health cover is something a few employers offer as an extra.</p>

<h2>The Billing Changes Every Receptionist Must Know</h2>

<p>Billing is the part of the job that changed most recently. From <strong>1 November 2025</strong>:</p>

<ul>
    <li><strong>Bulk billing incentives cover every Medicare-eligible patient.</strong> Before, they applied only to children under 16 and Commonwealth concession card holders.</li>
    <li><strong>The Bulk Billing Practice Incentive Program</strong> pays an extra <strong>12.5 per cent</strong> on the MBS benefits for eligible services, split equally between the GP and the practice, to practices that bulk bill every eligible service.</li>
    <li>Practices joining it need to be registered for <strong>MyMedicare</strong>.</li>
</ul>

<p>So patients now ask front-desk staff whether a clinic bulk bills everyone, and why a gap fee applies. Knowing the answer is a practical advantage in an interview.</p>

<h2>Privacy Rules Apply to Every Clinic</h2>

<p>Guides mention "patient privacy principles" in passing. The law is stricter than most job seekers expect. The Office of the Australian Information Commissioner says all organisations that provide a health service and hold health information are covered by the <strong>Privacy Act 1988</strong>, <strong>whether or not they are a small business</strong>. Health information is sensitive information under the Act.</p>

<p>In practice, that means confirming a patient's identity before discussing results or appointments, never reading records you do not need, keeping screens and paperwork out of view, and following the clinic's procedure if information goes to the wrong person.</p>

<h2>Where the Jobs Are</h2>

<ul>
    <li><strong>Sydney and Melbourne:</strong> the most listings, across GP clinics, specialist rooms and hospital-affiliated practices.</li>
    <li><strong>Regional towns</strong> such as Bendigo and Werribee in Victoria, where medical centres serve large catchments.</li>
    <li><strong>Adelaide and Perth:</strong> radiology networks, podiatry, skin cancer clinics and general practice.</li>
    <li><strong>Northern Territory:</strong> community health roles, including Aboriginal community controlled health services in Alice Springs, which often ask for extra checks.</li>
</ul>

<h2>Visa Sponsorship Is Rarely an Option</h2>

<p>Guides say some listings mention visa sponsorship. The main employer-sponsored route is the <strong>Skills in Demand visa</strong>, and its Core Skills stream only covers occupations on the Core Skills Occupation List. <strong>Medical Receptionist (542114) is not on the Core Skills Occupation List</strong>, so a clinic cannot sponsor the role through that stream. The nomination would also have to meet the Core Skills Income Threshold, which was <strong>$76,515</strong> from 1 July 2025 and is indexed each July.</p>

<p>Most overseas applicants who do this work hold a visa with general work rights, such as a working holiday or student visa, and each has its own work conditions. A listing that promises sponsorship for a medical receptionist role is worth questioning closely.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do medical receptionists earn in Australia?</h3>
<p>Pay is set by the Health Professionals and Support Services Award 2020 and cannot fall below the National Minimum Wage of $26.44 an hour from 1 July 2026. Check the current award pay guide for your level, because rates are being revised.</p>

<h3>Do I need a qualification to be a medical receptionist?</h3>
<p>No licence is required. The ANZSCO skill level is a Certificate II or III or at least a year of relevant experience, and a Certificate III in Health Administration helps.</p>

<h3>Are medical receptionist jobs mostly part-time?</h3>
<p>Yes. Jobs and Skills Australia reports that only 33 per cent of medical receptionists work full-time.</p>

<h3>Which award covers medical receptionists?</h3>
<p>The Health Professionals and Support Services Award 2020, known as the Health Services Award. Receptionists progress to support services level 2 or 3 with responsibility and experience.</p>

<h3>Can a medical receptionist job get visa sponsorship in Australia?</h3>
<p>Very rarely. Medical Receptionist is not on the Core Skills Occupation List, so it cannot be sponsored through the Skills in Demand visa's Core Skills stream.</p>

<h3>What software do medical receptionists use?</h3>
<p>Best Practice and MedicalDirector are common in general practice, and Genie in specialist practices.</p>

<h3>What changed in bulk billing in November 2025?</h3>
<p>From 1 November 2025, bulk billing incentives apply to every Medicare-eligible patient, and practices that bulk bill every eligible service can receive a 12.5 per cent incentive payment.</p>

<h3>Does the Privacy Act apply to small medical clinics?</h3>
<p>Yes. Every organisation that provides a health service and holds health information is covered, whatever its size.</p>

<h2>People Also Search For</h2>

<h3>Medical receptionist jobs Sydney</h3>
<p>The largest market, with GP clinics, specialist rooms and hospital-affiliated practices.</p>

<h3>Medical receptionist part-time jobs</h3>
<p>Most of the occupation: two in three medical receptionists work part-time.</p>

<h3>Health Services Award receptionist pay</h3>
<p>MA000027, with rates being phased up under the gender undervaluation review.</p>

<h3>Certificate III in Health Administration</h3>
<p>HLT37315, covering medical terminology, records and front-desk admin.</p>

<h3>Best Practice software training</h3>
<p>The most common GP practice system, and worth naming on your CV.</p>

<h3>Bulk billing incentive 2025</h3>
<p>Extended to every Medicare-eligible patient from 1 November 2025.</p>

<h3>Dental receptionist jobs Australia</h3>
<p>Covered by the same award as medical practice receptionists.</p>

<h3>Medical receptionist visa sponsorship</h3>
<p>Not available through the Core Skills stream, because the occupation is not on the list.</p>

<h2>More Job Guides</h2>

<p>Comparing admin and health care jobs? These cover them:</p>

<ul>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; clerical work under the Clerks Award, and how it compares.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; entry-level award rates, junior pay and visa work limits.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; how the Skills in Demand visa and its occupation list work.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-desk work in the Gulf, and the gratuity on a basic salary.</li>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the clinic role that mixes admin with clinical duties.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; an entry-level health care job with NHS pay bands.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Award rates, Medicare billing rules, privacy obligations and visa occupation lists change. Confirm the current position with the Fair Work Ombudsman, Services Australia, the OAIC and the Department of Home Affairs before applying or accepting an offer.</p>
HTML;
    }
}
