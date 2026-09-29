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
 * Personal care assistant careers in Pakistan, checked on 29 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. All four Indeed links are gone, along with the sentences describing what
 *     "current home-health aide openings in Pakistan" show.
 *  2. The closing FAQ told readers to search Indeed Pakistan. It now points at
 *     employer career pages and the Punjab registration route.
 *  3. The brief's salary section said only that pay "can vary substantially"
 *     and told the reader to confirm with the employer. True, and useless. It
 *     is replaced with the variables that actually move the number.
 *
 * What the brief left out entirely is the point of this guide. Care work in a
 * private household in Punjab is domestic work under the Punjab Domestic
 * Workers Act 2019, which gives a live-in carer a written letter of
 * employment, a weekly day off, paid sick and festival leave, employer-paid
 * annual medical examination, social security registration, and a bar on the
 * employer retaining identification documents. None of that applies in the
 * same way when a registered provider employs you instead. Every section was
 * checked against the Act text published by the Punjab Labour and Human
 * Resource Department.
 *
 * The clinical line and licence checks live in the home healthcare assistant
 * guide; the NAVTTC qualification lives in the elderly care guide. Neither is
 * restated here.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class PersonalCareAssistantJobsPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'personal-care-assistant-jobs-in-pakistan';

    public const APPLY_URL = 'https://saharahomehealthcare.com/jobs.php';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'Personal Care Employers, Pakistan (Aggregated)'],
                ['display_reference' => 'pk-personal-care-assistant-aggregated', 'type' => 'Private']
            );
            $location = Location::firstOrCreate(
                ['name' => 'Pakistan'],
                ['area' => 'Nationwide', 'country' => 'Pakistan']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Personal Care Assistant, Pakistan Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Day, night, 12-hour, part-time and live-in arrangements are all advertised',
                    'language' => 'English, Urdu',
                    // Provider rates and private household rates are set
                    // separately and neither is published as a range.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'personal care assistant jobs in pakistan, home health aide jobs, care assistant jobs pakistan, live in carer jobs, domestic workers act punjab',
                    'meta_description' => 'Personal care assistant and home health aide roles with providers in Karachi, Islamabad and Rawalpindi, plus private household posts and the rights attached to them.',
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
                    'title' => 'Personal Care Assistant Jobs in Pakistan',
                    'excerpt' => 'Who signs your pay decides which law protects you. A private household in Punjab owes a care worker a written letter of employment, a weekly day off, paid sick leave and a yearly medical examination, and cannot keep your CNIC.',
                    'content' => $content,
                    'featured_image' => 'blogs/personal-care-assistant-jobs-pakistan.jpg',
                    'tags' => 'personal care assistant jobs in pakistan, home health aide jobs karachi, care assistant jobs pakistan, live in carer jobs, punjab domestic workers act, caregiver rights pakistan, private home care jobs, pessi registration',
                    'meta_title' => 'Personal Care Assistant Jobs in Pakistan: Pay and Rights',
                    'meta_description' => 'Personal care assistant jobs in Pakistan: what a household legally owes you under the Punjab Domestic Workers Act, and what a provider offers instead.',
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
<p>This is an overview of personal care work with employers across Pakistan. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Personal care assistants help someone with the daily activities they can no longer manage alone: washing, dressing, toileting, moving safely, eating, and getting to appointments. The same work is advertised as Personal Care Assistant, Care Assistant, Home Health Aide, Home Care Assistant, Patient Care Assistant and Caregiver.</p>
<p>The most important question before accepting any of them is who your employer is. A registered provider and a private household give you very different rights over the same tasks.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every guide to personal care work in Pakistan covers the duties. Almost none covers the question that decides how the job actually goes for you: <strong>who is your employer?</strong></p>

<p>A registered home healthcare company and a family who found you through a cousin will ask you to do the same tasks. What you are owed in return is not the same at all, and most carers find that out only when something goes wrong.</p>

<h2>Two employers, two different sets of rights</h2>

<p>If a provider such as a home healthcare company hires you, you are that company's employee. There is a supervisor, a roster, a defined scope of work, and somebody other than the patient's family to raise a problem with.</p>

<p>If a household hires you directly to care for a relative in their home, you are not a company employee. In Punjab you are a <strong>domestic worker</strong>, and the Punjab Domestic Workers Act 2019 applies to you. Most people on both sides of that arrangement have never heard of it.</p>

<img src="/public/storage/blogs/personal-care-assistant-jobs-pakistan-mobility.jpg" alt="Personal care assistant helping a patient move safely at home in Pakistan" />

<h2>What a Punjab household legally owes a care worker</h2>

<p>These come from the Act's own text, as published by the Punjab Labour and Human Resource Department. If you take a private-home post in Punjab, this is your floor, not a wish list.</p>

<ul>
    <li><strong>A written letter of employment.</strong> Section 5 requires that every appointment "shall be subject to issuance of a letter of employment in the prescribed form showing the terms and conditions of his employment including nature of work and amount of wages", and that the employer sends a copy to the Inspector concerned.</li>
    <li><strong>One whole day off every week.</strong> Section 6(1), without qualification.</li>
    <li><strong>Eight days paid sick leave a year</strong>, on full wages, which can be carried forward to a maximum of sixteen days. Section 6(2).</li>
    <li><strong>Ten days of festival holidays on full wages</strong>, with the dates agreed between you and the employer at the start of the calendar year. Section 6(3).</li>
    <li><strong>Six weeks maternity leave</strong> and a maternity benefit of at least six weeks wages. Sections 6(4) and 9.</li>
    <li><strong>A yearly medical examination</strong> by a registered medical practitioner, plus vaccination, with the cost borne by the employer. Section 11.</li>
    <li><strong>Accommodation and decent living conditions if you live in.</strong> Section 10 places that on the employer under the express terms of employment.</li>
    <li><strong>Social security cover.</strong> Section 4(6) extends sickness benefits, medical care for you and your dependents, injury benefits and disablement pension under the Provincial Employees Social Security Ordinance 1965.</li>
    <li><strong>Pay no lower than the notified minimum</strong>, and equal pay regardless of sex for the same work. Section 8.</li>
    <li><strong>No extra work without your agreement and extra pay.</strong> Section 4(4) is explicit that additional duties require "free will of the domestic worker and extra remuneration".</li>
    <li><strong>Not to be called a servant.</strong> Section 4(3) says so in those terms.</li>
</ul>

<p>The Act also sets a floor on age: section 3 bars any child under 15 from working in a household in any capacity, and restricts anyone under 18 to light work.</p>

<h2>They cannot keep your CNIC</h2>

<p>This is the provision worth memorising. Section 14 states that upon termination of employment, "personal belongings and identification documents of a domestic worker or his family shall not be retained". If they are not returned, the Act gives you a route: apply to the Dispute Resolution Committee, which can hear the employer and then order the property returned within a set time.</p>

<p>Holding a live-in carer's CNIC is common, is usually framed as a security measure, and is not lawful in Punjab. You are allowed to say no at the start, and you are allowed to act if it happens.</p>

<h2>Registration is what turns the paper rights into real ones</h2>

<p>Sections 20 and 21 require both the worker and the employer to register. A registered domestic worker receives a security number and identity card, renewable every three years, and registration is what makes the welfare fund and the social security benefits reachable rather than theoretical.</p>

<p>The Punjab Labour and Human Resource Department publishes the <a href="https://labour.punjab.gov.pk/domestic-workers-registration-form" target="_blank" rel="noopener nofollow">domestic workers and employers registration form</a> and the <a href="https://labour.punjab.gov.pk/punjab-domestic-act" target="_blank" rel="noopener nofollow">Act itself</a> on its own site. Reading the Act before you negotiate is the cheapest advantage available in this job.</p>

<p>One honest caveat. This Act is provincial. It covers Punjab. Sindh, Khyber Pakhtunkhwa, Balochistan and the federal territory have their own positions, and a post in Karachi is not covered by the Punjab statute. Enforcement is also weak in practice, which is exactly why having the letter of employment in your hand matters more, not less.</p>

<h2>What a provider job gives you instead</h2>

<img src="/public/storage/blogs/personal-care-assistant-jobs-pakistan-live-in.jpg" alt="Live-in personal care assistant working an overnight shift in a Pakistani home" />

<p>Working for a registered home healthcare company trades some pay for structure. You generally get a defined scope, a roster, clinical supervision, and a complaints route that is not the patient's son.</p>

<p><strong>Sahara Home Healthcare, Karachi</strong> lists a Home Health Aide role across Karachi on its <a href="https://saharahomehealthcare.com/jobs.php" target="_blank" rel="noopener nofollow">jobs page</a>, part-time and full-time, covering companionship, help with daily activities, personal care, meal preparation, light housekeeping, medication reminders and patient safety. The employer states that training is provided.</p>

<p><strong>Care Nest, Islamabad and Rawalpindi</strong> advertises caregiving alongside nursing and physiotherapy on its <a href="https://carenest.pk/careers.html" target="_blank" rel="noopener nofollow">recruitment page</a>, with 12-hour day or night shifts, 24/7 live-in, part-time and per-procedure options.</p>

<p>For elderly clients specifically, and the free NAVTTC qualification that improves your chances with either kind of employer, see our <a href="/blog/elderly-care-assistant-jobs-in-pakistan">elderly care assistant guide</a>.</p>

<h2>Where personal care stops and nursing begins</h2>

<p>Personal care is non-clinical. Washing, dressing, toileting, transfers, meals, companionship and medication reminders are all inside it. Injections, wound dressing, catheter and feeding-tube management and administering rather than prompting medication are not.</p>

<p>That line is regulatory, and taking work on the wrong side of it exposes you personally. Our <a href="/blog/home-healthcare-assistant-jobs-in-pakistan">home healthcare assistant guide</a> sets out where the line sits and how to verify any licence by CNIC through the Pakistan Nursing and Midwifery Council register.</p>

<h2>Pay, and what actually moves it</h2>

<p>No dependable published range exists for personal care work in Pakistan, and the numbers circulating online are lifted from individual adverts, which this guide does not republish. What moves the figure is knowable:</p>

<ul>
    <li><strong>Live-in versus shift.</strong> Live-in pay looks higher monthly and is often lower hourly once you count the hours you are actually available.</li>
    <li><strong>Nights.</strong> Overnight and 24/7 posts should carry a premium; Care Nest advertises night and overtime allowances.</li>
    <li><strong>Complexity.</strong> A mobile, oriented client and a bedbound client with dementia are different jobs at the same job title.</li>
    <li><strong>Employer type.</strong> Providers pay steadily and supervise. Households sometimes pay more and sometimes stop paying.</li>
    <li><strong>City.</strong> Karachi, Lahore and the twin cities do not pay the same.</li>
</ul>

<h2>Before you accept a private-home post</h2>

<ul>
    <li>Get the letter of employment. In Punjab it is the law, not a favour, and it settles nature of work and wages in writing.</li>
    <li>Fix the weekly day off and the hours, in that letter, before you start.</li>
    <li>Refuse to hand over your CNIC, and say why.</li>
    <li>Agree what happens if the client is hospitalised or dies, which is the most common way these posts end without notice.</li>
    <li>Register, and ask the employer to register too.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What does a personal care assistant do?</h3>
<p>Helps someone with daily activities they cannot manage alone: washing, dressing, toileting, safe movement, meals, companionship and medication reminders. Clinical procedures are outside the role.</p>

<h3>Do I need a nursing qualification?</h3>
<p>Not for personal care. You do need registration for nursing acts such as injections, wound dressing or catheter care. Accepting clinical tasks without registration exposes you personally.</p>

<h3>Is a written contract required for private home care work?</h3>
<p>In Punjab, yes. Section 5 of the Punjab Domestic Workers Act 2019 requires a letter of employment in the prescribed form setting out nature of work and wages, with a copy sent to the Inspector concerned.</p>

<h3>How many days off is a live-in carer entitled to?</h3>
<p>In Punjab, at least one whole day a week under section 6(1), plus eight days paid sick leave a year and ten days of festival holidays on full wages.</p>

<h3>Can an employer keep my CNIC while I work for them?</h3>
<p>Not in Punjab. Section 14 bars retaining a domestic worker's identification documents and personal belongings, and provides a Dispute Resolution Committee route to recover them.</p>

<h3>Does this law apply outside Punjab?</h3>
<p>No. The Punjab Domestic Workers Act 2019 is provincial. Sindh, Khyber Pakhtunkhwa, Balochistan and the federal territory have their own arrangements, so a Karachi post is not covered by it.</p>

<h3>Can I start personal care work with no experience?</h3>
<p>Yes, with a provider that trains. Sahara Home Healthcare states training is provided on its Home Health Aide opening. Private households and complex clients usually want prior experience.</p>

<h3>How much do personal care assistants earn in Pakistan?</h3>
<p>There is no reliable published range. Pay moves with live-in versus shift work, night duty, client complexity, city, and whether a provider or a household employs you. Settle the figure in writing first.</p>

<h2>People Also Search For</h2>

<h3>Home health aide jobs in Karachi</h3>
<p>Sahara Home Healthcare lists a Home Health Aide role across Karachi, part-time and full-time, and states training is provided.</p>

<h3>Live in carer jobs in Pakistan</h3>
<p>Common, and the arrangement with the most rights attached in Punjab: accommodation, a weekly day off and a letter of employment.</p>

<h3>Punjab Domestic Workers Act 2019</h3>
<p>The statute covering care work in a private household in Punjab. Sets wages, leave, medical examination, registration and document protections.</p>

<h3>Caregiver rights in Pakistan</h3>
<p>Depend on who employs you. Provider employment follows company terms; household employment in Punjab follows the Domestic Workers Act.</p>

<h3>Care assistant jobs in Islamabad</h3>
<p>Care Nest recruits caregivers across Islamabad and Rawalpindi with 12-hour, live-in and part-time shift options.</p>

<h3>Patient care assistant jobs Pakistan</h3>
<p>The same non-clinical work under a hospital-flavoured title. Check whether the advert expects clinical tasks before applying.</p>

<h3>Personal care assistant salary Pakistan</h3>
<p>No dependable published range. Figures online come from single adverts and are not a market rate.</p>

<h3>Domestic worker registration Punjab</h3>
<p>Sections 20 and 21 require worker and employer registration. The Labour Department publishes the form, and registration unlocks the welfare fund.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/home-healthcare-assistant-jobs-in-pakistan">Home Healthcare Assistant Jobs in Pakistan</a> &mdash; where the clinical line sits and how to verify a licence by CNIC.</li>
    <li><a href="/blog/elderly-care-assistant-jobs-in-pakistan">Elderly Care Assistant Jobs in Pakistan</a> &mdash; the free NAVTTC qualification and what dementia work demands.</li>
    <li><a href="/blog/disability-support-worker-jobs-in-pakistan">Disability Support Worker Jobs in Pakistan</a> &mdash; the same skills for a different client group, and the 3 per cent employment quota.</li>
    <li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; if you want desk work instead, with the same entry-level footing.</li>
    <li><a href="/blog/residential-care-worker-jobs-in-pakistan">Residential Care Worker Jobs in Pakistan</a> &mdash; what changes again when an institution employs you rather than a household.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://labour.punjab.gov.pk/punjab-domestic-act" target="_blank" rel="noopener nofollow">Punjab Domestic Workers Act 2019, Labour and Human Resource Department</a></li>
    <li><a href="https://labour.punjab.gov.pk/domestic-workers-registration-form" target="_blank" rel="noopener nofollow">Domestic workers and employers registration form</a></li>
    <li><a href="https://saharahomehealthcare.com/jobs.php" target="_blank" rel="noopener nofollow">Sahara Home Healthcare jobs, Karachi</a></li>
    <li><a href="https://carenest.pk/careers.html" target="_blank" rel="noopener nofollow">Care Nest recruitment, Islamabad and Rawalpindi</a></li>
</ul>

<p><strong>Note:</strong> pay, shift patterns and duties are set by each employer, not by JobGader. Confirm the details on the employer's own page before applying.</p>
HTML;
    }
}
