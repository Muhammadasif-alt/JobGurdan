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
use Illuminate\Support\Facades\DB;

/**
 * Home healthcare assistant careers in Pakistan, checked on 29 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. Both Indeed links are gone, along with the two passages describing what
 *     "current Indeed results" show for Lahore and for remote work.
 *  2. The brief's only salary figure was an Indeed listing for Holistic
 *     Healthcare Services in Lahore "with pay of up to Rs 40,000 per month".
 *     Aggregator salary data is not republished here, and neither is a single
 *     advert presented as a market rate.
 *  3. The brief linked the Aga Khan University vacancy by its job identifier.
 *     That URL form is never published here. The vacancies index is linked
 *     instead.
 *  4. More importantly, the brief presented that vacancy as a beginner job.
 *     AKU describes it as a four-week traineeship programme that prepares
 *     candidates for a Health Care Assistant position, and states that a
 *     stipend is provided upon successful completion. A conditional stipend
 *     after four weeks is not a salaried post, and a reader planning around
 *     it deserves to know that before applying.
 *  5. The brief referred throughout to CNA, a United States state credential.
 *     Pakistan has no CNA licence. The regulator is the Pakistan Nursing and
 *     Midwifery Council, which registers nurses, midwives, LHVs, community
 *     midwives and nursing assistants. Employers do advertise the CNA title,
 *     so the guide explains the gap rather than pretending it is not there.
 *  6. The old regulator domain pnc.org.pk no longer resolves. The council is
 *     now the PNMC at pnmc.gov.pk, with a public register that verifies a
 *     licence by CNIC. That check is the most useful thing in this guide.
 *  7. Six FAQs and no People Also Search For block. Now eight of each.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class HomeHealthcareAssistantJobsPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'home-healthcare-assistant-jobs-in-pakistan';

    public const APPLY_URL = 'https://saharahomehealthcare.com/jobs.php';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'Home Healthcare Providers, Pakistan (Aggregated)'],
                ['display_reference' => 'pk-home-healthcare-assistant-aggregated', 'type' => 'Private']
            );
            $location = Location::firstOrCreate(
                ['name' => 'Pakistan'],
                ['area' => 'Nationwide', 'country' => 'Pakistan']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Home Healthcare Assistant, Pakistan Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Commonly 12-hour day or night shifts, with live-in, part-time and per-visit arrangements also advertised',
                    'language' => 'English, Urdu',
                    // Providers advertise these roles without publishing pay,
                    // and clinical and non-clinical rates differ sharply.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'home healthcare jobs in pakistan, home health aide jobs karachi, caregiver jobs pakistan, home care nurse jobs, patient care assistant jobs',
                    'meta_description' => 'Home healthcare assistant, caregiver and home care nursing roles with providers in Karachi, Lahore, Islamabad and Rawalpindi, covering clinical and non-clinical work.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'career-advice'],
                ['name' => 'Career Advice', 'description' => 'Practical guides on finding work, applying and building a career.']
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Home Healthcare Assistant Jobs in Pakistan',
                    'excerpt' => 'Pakistan has no CNA licence, whatever the job adverts say. The regulator is the PNMC, and its register verifies any nurse, LHV or midwife by CNIC. Aga Khan University Hospital pays its home care traineeship as a stipend only on completion.',
                    'content' => $content,
                    'featured_image' => 'blogs/home-healthcare-assistant-jobs-pakistan.jpg',
                    'tags' => 'home healthcare jobs in pakistan, home health aide jobs karachi, caregiver jobs pakistan, home care nurse jobs, patient care assistant jobs, pnmc registration check, live in caregiver jobs, elderly care jobs pakistan',
                    'meta_title' => 'Home Healthcare Assistant Jobs in Pakistan: Pay and Rules',
                    'meta_description' => 'Home healthcare assistant jobs in Pakistan: which roles need PNMC registration, how to verify a licence by CNIC, shift patterns, and how to apply safely.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p>This is an overview of home healthcare work with providers across Pakistan. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Home healthcare covers two different kinds of job. Non-clinical caregiver and home health aide work supports daily living: personal care, mobility, meals, companionship, medication reminders and household safety. Clinical home care is nursing, physiotherapy and procedure work delivered in a patient's home by a registered professional.</p>
<p>The dividing line is regulatory, not a matter of preference. Practising as a nurse, midwife or LHV in Pakistan requires registration with the Pakistan Nursing and Midwifery Council. Never accept clinical tasks you are not registered and trained to perform.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Home healthcare is the fastest-growing corner of Pakistani patient care, and the least regulated in practice. Families hire someone to look after a parent recovering from surgery, and that person may be a registered nurse, a trained caregiver, or somebody who answered an advertisement last week.</p>

<p>If you want to work in this field, the two things that matter most are which side of the clinical line your job sits on, and whether you can prove your credentials. This guide covers both, with the checks you can run yourself.</p>

<img src="/public/storage/blogs/home-healthcare-assistant-jobs-pakistan-patient-care.jpg" alt="Home healthcare assistant supporting a patient at home in Pakistan" />

<h2>The line that decides everything: clinical or not</h2>

<p>Home healthcare job titles in Pakistan are used loosely, but the law underneath them is not loose at all.</p>

<p><strong>Non-clinical roles</strong> are caregiver, home health aide, attendant and elderly care assistant. The work is personal care, mobility support, meal preparation, companionship, light housekeeping, medication reminders and keeping the home safe. No licence is required, and employers train people into these roles.</p>

<p><strong>Clinical roles</strong> are registered nurse, home care nurse, LHV, midwife and physiotherapist. This work involves assessment, medication administration, wound care, injections, catheterisation and procedures. Practising as a nurse, midwife or LHV in Pakistan requires registration with the <a href="https://pnmc.gov.pk/">Pakistan Nursing and Midwifery Council</a>.</p>

<p>A medication <em>reminder</em> is non-clinical. Medication <em>administration</em> is clinical. That single distinction is where most home care goes wrong, and it is the one to be strict about, because you carry the consequences personally.</p>

<h2>There is no CNA licence in Pakistan</h2>

<p>This needs saying plainly, because it appears in job advertisements constantly.</p>

<p>Certified Nursing Assistant, or CNA, is a United States credential regulated by individual American states. Pakistan has no CNA licence and no body that issues one. When a Pakistani employer advertises a CNA role, it is borrowing an American job title, not asking for an American credential.</p>

<p>Pakistan's regulator is the Pakistan Nursing and Midwifery Council, formerly the Pakistan Nursing Council. Its categories are nurse, midwife, LHV, community midwife, family welfare worker and nursing assistant. Note also that the old website address pnc.org.pk no longer resolves; the council is now at pnmc.gov.pk.</p>

<p>So if you see a CNA vacancy, apply if the duties match your training, but understand what is actually being asked: practical patient-care skills, not a certificate you could not obtain in Pakistan anyway.</p>

<h2>Check any licence yourself, by CNIC</h2>

<p>This is the most useful thing in this guide, and it works in both directions.</p>

<p>The council runs a public register at <a href="https://online.pnmc.gov.pk/">online.pnmc.gov.pk</a> where anyone can look up a nurse, midwife, LHV, community midwife or family welfare worker by CNIC number and see whether they are currently licensed to practise.</p>

<ul>
<li><strong>If you are qualified</strong>, make sure your own registration is current and renewed. Serious employers check, and an expired registration will cost you the offer.</li>
<li><strong>If you are hiring or being placed</strong>, the register tells you whether the "nurse" an agency is sending into a home is registered at all.</li>
<li><strong>If you are not registered</strong>, do not accept a job title that implies you are. Presenting yourself as a nurse without registration is a real risk to you, not a technicality.</li>
</ul>

<h2>Who is actually hiring</h2>

<p>Three employers publish home healthcare vacancies on their own websites, which means you can read the role as the employer wrote it.</p>

<p><strong>Sahara Home Healthcare, Karachi.</strong> Its <a href="https://saharahomehealthcare.com/jobs.php">careers page</a> lists Registered Nurse, Certified Nursing Assistant, Physical Therapist and Home Health Aide roles across Karachi and surrounding areas. The Home Health Aide duties are described as companionship, assistance with daily activities, personal care, meal preparation, light housekeeping, medication reminders and maintaining patient safety and comfort. The company states that it provides training and certification programmes. It does not publish salary figures.</p>

<p><strong>Care Nest, Islamabad and Rawalpindi.</strong> Its <a href="https://carenest.pk/careers.html">recruitment page</a> states it is hiring Registered Nurses, Home Care Nurses, Physiotherapists and Caregivers across the twin cities, with shift options listed as 12-hour day or night, 24/7 live-in, part-time, and per-procedure clinical visits. Applications go through an online form.</p>

<p><strong>Aga Khan University Hospital, Karachi.</strong> AKU advertises a Trainee Health Care Assistant position within its Home Health Care Services, published through <a href="https://www.aku.edu/vacancies/pages/home.aspx">its vacancies portal</a>. Read the next section before you plan around it.</p>

<h2>The AKU traineeship is a traineeship, not a salaried job</h2>

<p>This role is widely described online as an entry-level home healthcare job for beginners. AKU's own wording is more specific: it is a four-week traineeship programme offered to prepare candidates for a Health Care Assistant position, and upon successful completion of the traineeship, incumbents will be provided a stipend.</p>

<p>Three things follow from that, and they matter if you are deciding whether you can afford to take it:</p>

<ul>
<li>The programme runs four weeks before it leads anywhere.</li>
<li>The stipend is tied to successful completion, not paid as a monthly salary from day one.</li>
<li>Completing it prepares you for the Health Care Assistant position. It is not itself that position.</li>
</ul>

<p>None of that makes it a bad opportunity. Structured training at a hospital of that standing is genuinely valuable, and it is one of the few formal entry routes into home care in Pakistan. But go in knowing it is a training programme, budget for four weeks accordingly, and confirm the current terms directly with AKU before committing.</p>

<img src="/public/storage/blogs/home-healthcare-assistant-jobs-pakistan-home-visit.jpg" alt="Home care nurse arriving for a scheduled patient visit" />

<h2>Shifts, and what they do to your life</h2>

<p>Home healthcare schedules are built around patients, not staff. The patterns advertised in Pakistan are:</p>

<ul>
<li><strong>12-hour day or night shifts.</strong> The standard for continuous patient cover.</li>
<li><strong>24/7 live-in.</strong> You stay in the patient's home. Ask specifically about rest hours, your own room, days off and how relief is arranged, because live-in work is where boundaries erode fastest.</li>
<li><strong>Part-time and per-visit.</strong> Common for physiotherapy and procedure work, paid per visit rather than per month.</li>
</ul>

<p>Before accepting any assignment, confirm the shift length, rest arrangements, who pays for travel between visits, whether accommodation and meals are included on live-in work, and what happens if the patient's condition changes and the assignment ends early.</p>

<h2>Qualifications and requirements</h2>

<p>For non-clinical caregiver and aide roles, employers look for reliability, patience, communication, respect for privacy, basic record keeping, safe mobility support and the ability to work alone in someone's home. Formal qualifications are often not required, and training is provided.</p>

<p>For clinical roles, you need the actual credential: a nursing diploma or degree, LHV or midwifery qualification, or a physiotherapy degree, plus current PNMC registration where the category requires it. Employers may also ask for patient-care experience.</p>

<p>The qualities that get people kept on, across both types, are punctuality, honest reporting of what changed with the patient, and knowing the limit of your own competence well enough to escalate rather than improvise.</p>

<h2>Pay, and why no figure is quoted here</h2>

<p>Home healthcare pay in Pakistan varies by city, by employer, by shift length, by whether the work is clinical, and by whether accommodation and meals are included on a live-in placement. The providers who publish vacancies mostly do not publish rates.</p>

<p>What you can hold on to: Punjab's notified minimum wage for an unskilled adult worker is <a href="https://labour.punjab.gov.pk/minimum-wages-notification">PKR 40,000 a month</a>, which is the floor for covered employment. A qualified nurse doing clinical home visits should expect meaningfully more than a non-clinical caregiver, and per-visit work should be priced per visit rather than converted into a vague monthly promise.</p>

<p>Get the rate, the shift, the travel arrangements and the payment schedule in writing before the first assignment.</p>

<h2>Working safely in someone's home</h2>

<p>This job puts you alone in a stranger's house, often at night. Take that seriously.</p>

<ul>
<li>Work through an employer or agency with a verifiable office and website, not a direct arrangement made over a messaging app.</li>
<li>Tell someone you trust the address and the shift timing for every assignment.</li>
<li>Never pay a fee to be placed. Legitimate providers do not charge staff for work.</li>
<li>Do not hand over original documents. Copies only, and only after a written offer.</li>
<li>Keep your own brief notes of the care you provided and anything you escalated.</li>
<li>Refuse clinical tasks outside your training and registration, every time, no matter who asks.</li>
</ul>

<h2>Where the career goes</h2>

<p>Home care is one of the few Pakistani healthcare fields where a non-clinical start can lead somewhere clinical, if you use it deliberately.</p>

<p>A caregiver who is reliable and observant can move into a trained health care assistant role, then into nursing assistant work, and from there into a formal nursing or LHV qualification with PNMC registration. Beyond that, experienced staff move into coordination, supervision and training roles within provider agencies, or into hospital-based care.</p>

<p>The people who progress are the ones who get registered. Everything else is a ceiling.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a home healthcare assistant do?</h3>
<p>You support a patient in their own home with personal care, mobility, meals, companionship, medication reminders, household safety and reporting changes to the family or the supervising nurse. Clinical tasks belong to registered professionals.</p>

<h3>Do I need a nursing qualification?</h3>
<p>Not for caregiver or home health aide work, which is non-clinical and usually comes with training. You do need the qualification and current PNMC registration to work as a nurse, home care nurse, LHV or midwife.</p>

<h3>Is there a CNA certification in Pakistan?</h3>
<p>No. CNA is a United States state credential and Pakistan does not issue one. Employers here use the title loosely. The Pakistani regulator is the Pakistan Nursing and Midwifery Council, whose categories include nurse, midwife, LHV, community midwife and nursing assistant.</p>

<h3>How do I check whether a nurse is really registered?</h3>
<p>Use the council's public register at online.pnmc.gov.pk, which lets you look up a nurse, midwife, LHV, community midwife or family welfare worker by CNIC number and see whether they are currently licensed.</p>

<h3>Can beginners get home healthcare jobs?</h3>
<p>Yes, into non-clinical caregiver and aide roles, and some employers train new staff. Aga Khan University Hospital also runs a four-week traineeship for home care, though it is a training programme with a stipend on completion rather than a salaried post from day one.</p>

<h3>Are live-in home care jobs available?</h3>
<p>Yes. Providers in the twin cities advertise 24/7 live-in placements alongside 12-hour day and night shifts, part-time work and per-procedure visits. Confirm rest hours, days off, accommodation and relief cover before accepting.</p>

<h3>What is the difference between a home health aide and a home care nurse?</h3>
<p>A home health aide supports daily living and comfort within the limits of their training. A home care nurse is a registered professional who can assess, administer medication and perform clinical procedures within their scope of practice.</p>

<h3>Which cities have the most home healthcare work?</h3>
<p>Karachi has the most established providers, with active recruitment across Islamabad and Rawalpindi and a growing market in Lahore. Demand follows elderly populations and post-surgical discharge.</p>

<h2>People Also Search For</h2>

<h3>Home health aide jobs in Karachi</h3>
<p>Specialist providers recruit aides, nursing assistants, nurses and physiotherapists for home visits across the city and surrounding areas.</p>

<h3>Caregiver jobs in Pakistan</h3>
<p>Non-clinical daily living support, often with employer training, and the most accessible entry point into patient care without a qualification.</p>

<h3>PNMC registration check by CNIC</h3>
<p>The council's online register verifies whether a nurse, midwife, LHV, community midwife or family welfare worker currently holds a licence to practise.</p>

<h3>Home care nurse jobs in Islamabad</h3>
<p>Providers across Islamabad and Rawalpindi advertise registered nurse and home care nurse roles with 12-hour, live-in and per-visit options.</p>

<h3>Elderly care jobs in Pakistan</h3>
<p>Largely home-based companionship and personal care work, with demand concentrated where families are supporting parents at home.</p>

<h3>Live-in caregiver salary in Pakistan</h3>
<p>Rarely published, and it depends on whether accommodation and meals are included. Agree rest hours and days off in writing before starting.</p>

<h3>Patient care assistant jobs</h3>
<p>A title used both in hospitals and in home care. Check whether the advertised duties are clinical before applying, because the required credential follows from that.</p>

<h3>Nursing jobs in Pakistan for freshers</h3>
<p>Newly qualified nurses need current PNMC registration first. Home care agencies and hospital home health services both recruit at entry level once you are registered.</p>

<h2>Related career guides</h2>
<ul>
<li><a href="/blog/medical-billing-assistant-jobs-in-pakistan">Medical Billing Assistant Jobs in Pakistan</a> &mdash; the non-clinical healthcare route, with pay checked against the minimum wage.</li>
<li><a href="/blog/healthcare-administrator-jobs-in-pakistan">Healthcare Administrator Jobs in Pakistan</a> &mdash; hospital and clinic administration, and the public-sector qualification rules.</li>
<li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; how the same assistant work is certified and paid in the United States.</li>
<li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; an alternative entry-level route for candidates who would rather not work shifts.</li>
</ul>

<h2>Official sources and verification</h2>
<p>Checked on 29 September 2026: <a href="https://pnmc.gov.pk/">the Pakistan Nursing and Midwifery Council</a> and <a href="https://online.pnmc.gov.pk/">its online register</a> for registration categories and licence verification by CNIC; <a href="https://saharahomehealthcare.com/jobs.php">Sahara Home Healthcare</a> for its listed Karachi roles and the home health aide duties; <a href="https://carenest.pk/careers.html">Care Nest</a> for the twin cities vacancies and the advertised shift options; <a href="https://www.aku.edu/vacancies/pages/home.aspx">Aga Khan University</a> for the Trainee Health Care Assistant traineeship and its stipend-on-completion terms; and <a href="https://labour.punjab.gov.pk/minimum-wages-notification">the Punjab Labour and Human Resource Department</a> for the notified minimum wage. Safety and career guidance is editorial. No aggregator salary data or vacancy counts are republished here, and no vacancy is linked by job identifier. Vacancies close without notice.</p>
HTML;
    }
}
