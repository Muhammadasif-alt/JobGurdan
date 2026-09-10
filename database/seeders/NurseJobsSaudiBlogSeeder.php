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
 * "Nurse Jobs in Saudi Arabia" — hospital nursing for foreign-trained nurses.
 * It sits beside the security guard, driver and cleaner guides for the Kingdom
 * and borrows their Labour Law and no-minimum-wage reading rather than
 * repeating it, and beside the US and UK nursing and healthcare guides.
 *
 * Corrections to the draft:
 *
 * 1. It calls the SCFHS licence "the Prometric exam". Prometric runs the test
 *    centres where the SCFHS classification exam is booked; the licence is
 *    SCFHS professional classification and registration through Mumaris Plus,
 *    with DataFlow primary source verification before it.
 *
 * 2. It lists a nursing diploma as equivalent to a BSN. SCFHS classifies each
 *    nurse on verified qualifications and experience, and that classification,
 *    not the advert, decides the grade a nurse is registered at.
 *
 * 3. Its staff nurse range starts at SAR 4,000, the figure most often mistaken
 *    for a minimum wage. Saudi Arabia has no statutory minimum wage for
 *    expatriate workers; SAR 4,000 is the Saudization threshold.
 *
 * 4. It tells nurses to follow up with "hospitals or recruitment agencies".
 *    For Indian nurses the Embassy of India in Riyadh says recruitment runs
 *    only through eMigrate: designated agents for Ministry of Health and
 *    military hospitals, and six State-run agencies for every other hospital.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NurseJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/q-nurse-jobs.html';

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
        $title = 'Nurse Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The Prometric exam is one step of SCFHS registration, not the licence itself. SAR 4,000 is not a minimum wage for foreign nurses, and Indian nurses can only be recruited through eMigrate. What to check before you accept a Saudi hospital offer.',
                'content' => $content,
                'featured_image' => 'blogs/nurse-jobs-in-saudi-arabia.jpg',
                'tags' => 'nurse jobs in saudi arabia, scfhs license for nurses, saudi prometric exam for nurses, mumaris plus, dataflow verification, staff nurse salary in saudi arabia, icu nurse jobs riyadh, moh nurse jobs for indian nurses, nurse jobs in saudi arabia for pakistani nurses',
                'meta_title' => 'Nurse Jobs in Saudi Arabia 2026: SCFHS, Salary and Visa',
                'meta_description' => 'Nurse jobs in Saudi Arabia: why the Prometric exam is only one step of SCFHS registration, how pay is built, and the legal routes from India.',
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
            ['name' => 'Saudi Hospitals & Licensed Agencies (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'saudi-hospitals-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Riyadh, Jeddah and Dammam', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Staff Nurse — Government, Military and Private Hospitals, Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Hospital shift rosters, including nights and weekends',
                'language' => 'English; Arabic is an advantage with patients',
                // Pay depends on the SCFHS classification, the employer and the
                // package split, and there is no statutory minimum wage for
                // expatriate workers to anchor a range to.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Staff, ICU, ER and OR nurse roles in Saudi hospitals. SCFHS classification and registration required; check the package split before accepting.',
                'seo_keywords' => 'nurse jobs in saudi arabia, staff nurse jobs riyadh, icu nurse jobs saudi arabia, scfhs license, saudi prometric exam',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Ministry of Health, military, university and private hospitals across Saudi Arabia recruit foreign-trained nurses for wards, intensive care, emergency departments, operating theatres and clinics. Most posts are filled through licensed recruitment agencies in the nurse's home country, and every one of them depends on a licence from the Saudi Commission for Health Specialties (SCFHS).</p>

<h3>What the work involves</h3>
<p>Assessing and caring for patients, giving medication and treatments, monitoring and recording observations, working with doctors and the wider clinical team, and educating patients and families. ICU, ER, OR and paediatric posts add specialist care and usually ask for specialist experience.</p>

<h3>Requirements</h3>
<ul>
    <li>A nursing qualification and current registration in good standing in your home country</li>
    <li><strong>SCFHS professional classification and registration</strong>, applied for through Mumaris Plus, with DataFlow primary source verification and the SCFHS classification exam booked through Prometric</li>
    <li>Clinical experience, commonly one to two years, and more for specialist units</li>
    <li>BLS, and ACLS or PALS for acute and paediatric roles, where the hospital asks for them</li>
    <li>Clear English; Arabic helps with patients</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>No statutory minimum wage</strong> applies to expatriate workers, so the offer itself is the only floor</li>
    <li>Your SCFHS classification, the type of hospital and the recruiting agency all move the figure</li>
    <li>Get basic salary, housing, transport, flights and medical cover as separate lines in writing</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Use a legal recruitment route and never pay for a visa.</strong> Indian nurses are recruited only through eMigrate. Pakistani nurses should use an agency licensed by the Bureau of Emigration and Overseas Employment, and Filipino nurses a Department of Migrant Workers-licensed agency.</p>

<p><strong>Note:</strong> pay, licensing requirements, contract terms and recruitment rules are set by each hospital, SCFHS and the authorities in your home country &mdash; not by JobGader. Confirm the details with the employer and the official sources before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Saudi Arabia is one of the largest employers of foreign-trained nurses in the world. New hospitals, specialist centres and the healthcare targets of Vision 2030 keep demand high for ward, intensive care, emergency and theatre nurses. It is also a market where the most repeated advice gets three things wrong: what the licence is, what the lowest salary means, and which recruitment routes are legal.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-nurse-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse Nurse Jobs in Saudi Arabia &rarr;
    </a>
</div>

<h2>The Prometric Exam Is Not the Licence</h2>

<p>Guides describe the licence you need as "the SCFHS licence, known as the Prometric exam". That runs two different things together. <strong>Prometric runs the test centres</strong> where the Saudi Commission for Health Specialties (SCFHS) classification exam is booked and taken. It does not license anyone. <strong>The Prometric exam is not the licence</strong>; it is one step in SCFHS professional classification and registration.</p>

<p>The steps, in the order most nurses meet them:</p>

<ol>
    <li><strong>Create a Mumaris Plus account.</strong> Mumaris Plus is the SCFHS online portal. You upload your qualifications, experience letters, passport and current nursing registration, and pay the fees there.</li>
    <li><strong>Primary source verification through DataFlow.</strong> DataFlow checks your degree, licence and experience directly with the university, nursing council and employers that issued them.</li>
    <li><strong>Sit the classification exam</strong> once SCFHS confirms you are eligible, booking your appointment through Prometric.</li>
    <li><strong>Receive your professional classification</strong>, which sets the grade you are registered at.</li>
    <li><strong>Register with SCFHS</strong>, which is what allows you to practise in the Kingdom.</li>
</ol>

<p>The practical lesson is that <strong>verification is where most applications slow down</strong>. If DataFlow cannot confirm a document with the institution that issued it, the application waits. Before you pay for anything, check that your college, your nursing council and your past employers will answer a verification request &mdash; and be wary of anyone offering to "arrange" a pass or a classification for a fee.</p>

<h2>Diploma or Degree: Your Classification Sets the Grade</h2>

<p>The same guides list the requirement as "a Bachelor's degree in Nursing (BSN) or equivalent nursing diploma", as though the two open the same doors. They do not. SCFHS classifies each nurse on <strong>verified qualifications and experience</strong>, and that classification &mdash; not the job title in an advert &mdash; decides the grade you are registered at. A diploma and a bachelor's degree are not classified at the same grade, and the grade shapes which posts and which salary band a hospital can offer you.</p>

<p>So ask the recruiter <strong>which SCFHS classification the post needs</strong> before you pay for verification or an exam. A diploma nurse who applies for a post written for a degree-level classification can pass every step and still not be registered for that job.</p>

<h2>SAR 4,000 Is Not a Minimum Wage</h2>

<p>Guides give nursing salaries in three bands:</p>

<ul>
    <li><strong>Staff nurse:</strong> SAR 4,000 to 6,500 a month</li>
    <li><strong>Specialist or ICU nurse:</strong> SAR 6,500 to 9,000 a month</li>
    <li><strong>Senior or charge nurse:</strong> SAR 9,000 to 12,000 or more</li>
</ul>

<p>Read the bottom of the first band carefully. <strong>Saudi Arabia has no statutory minimum wage for expatriate workers.</strong> The SAR 4,000 figure repeated online is the threshold at which a Saudi national counts as a full employee for Saudization. It is not a floor under a foreign nurse's salary, and an offer below it is not unlawful for that reason. Our <a href="/blog/security-guard-jobs-in-saudi-arabia">security guard jobs in Saudi Arabia guide</a> explains that threshold in full.</p>

<p>That makes the structure of the offer matter more than the headline. Offers vary widely between Ministry of Health, military, university and private hospitals, and between recruitment agencies for the same hospital. <strong>Get every line in writing</strong>: basic salary, housing or provided accommodation, transport, annual flights, medical cover and the end-of-service benefit. Two offers with the same monthly total can be very different contracts.</p>

<p>Two more points the guides skip:</p>

<ul>
    <li><strong>The visa is the employer's cost.</strong> The Saudi Labour Law puts recruitment costs on the employer, so a recruiter asking you for a "visa fee" is asking you to pay the hospital's bill.</li>
    <li><strong>"Tax-free" is true only in Saudi Arabia.</strong> The Kingdom does not tax employment income, but your home country may still treat you as tax resident until you meet its own non-resident rules.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nurse-jobs-in-saudi-arabia-licence.jpg"
         alt="A nurse in scrubs holding a tablet outside a hospital in Riyadh with the Kingdom Centre behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Indian Nurses: eMigrate Is the Only Legal Route</h2>

<p>For nurses from India, "follow up with hospitals or recruitment agencies" is advice that can end at the airport. The Embassy of India in Riyadh is explicit: <strong>all recruitment of ECR category workers, including nurses, to ECR countries must be done only through the eMigrate portal</strong>, and Saudi Arabia is an ECR country.</p>

<p>The embassy describes two routes:</p>

<ul>
    <li><strong>Government and military hospitals.</strong> The Saudi Ministries of Health and of Defence and Aviation may recruit nurses through Indian recruiting agents designated for this purpose.</li>
    <li><strong>Every other hospital.</strong> A hospital that wants to recruit Indian nurses must register on eMigrate and use one of six State-run agencies: <strong>NORKA Roots, ODEPC, OMCL, UPFC, OMCAP and TOMCOM</strong>.</li>
</ul>

<p>That leaves no role for a private agent on a private hospital offer. If the person offering you a Saudi nursing job is not one of these routes, the offer will not get emigration clearance, however genuine the hospital is.</p>

<h2>Pakistani, Filipino and Other Nurses</h2>

<p>Every major sending country runs its own licensing of recruiters, and the rule is the same everywhere: <strong>use a licensed agency and never pay for a visa</strong>.</p>

<ul>
    <li><strong>Pakistan:</strong> an Overseas Employment Promoter licensed by the Bureau of Emigration and Overseas Employment, with your registration completed at the Protector of Emigrants. Your Pakistan Nursing and Midwifery Council registration is one of the documents DataFlow verifies.</li>
    <li><strong>Philippines:</strong> an agency licensed by the Department of Migrant Workers.</li>
    <li><strong>Bangladesh:</strong> an agency registered with BMET.</li>
</ul>

<p>The wider rules on Saudi contracts &mdash; the 48-hour week, the Qiwa platform, the Wage Protection System and changing employer &mdash; apply to private hospital contracts under the Labour Law. Our <a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">driver jobs in Saudi Arabia guide</a> sets them out.</p>

<h2>Where the Jobs Are</h2>

<p>Nursing posts sit with four kinds of employer, and each recruits a little differently:</p>

<ul>
    <li><strong>Ministry of Health hospitals</strong> &mdash; the largest employer, recruiting in large campaigns through designated agencies.</li>
    <li><strong>Military and National Guard hospitals</strong> &mdash; large medical cities with their own recruitment programmes and specialist units.</li>
    <li><strong>University hospitals</strong> &mdash; teaching hospitals attached to the major universities.</li>
    <li><strong>Private hospital groups</strong> &mdash; the fastest-growing part of the market, hiring for new hospitals and clinics in every major city.</li>
</ul>

<p>By city, <strong>Riyadh</strong> has the largest concentration of hospitals and specialist centres, <strong>Jeddah</strong> is the main hub on the west coast, <strong>Dammam and the Eastern Province</strong> have growing hospital networks, and <strong>Makkah and Madinah</strong> add staffing demand around the Hajj and Umrah seasons.</p>

<p>The specialties hospitals find hardest to fill are <strong>intensive care, emergency, operating theatre, neonatal and paediatric intensive care, dialysis and oncology</strong>. Experience in one of them is the strongest bargaining position a nurse brings to a Saudi offer.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nurse-jobs-in-saudi-arabia-hospital.jpg"
         alt="A nurse standing in a Saudi hospital intensive care unit while colleagues care for patients"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Documents to Prepare</h2>

<ul>
    <li>Your nursing degree or diploma certificate and transcripts</li>
    <li>Your current nursing registration and a good standing certificate from your nursing council</li>
    <li>Experience letters on letterhead, with exact start and end dates</li>
    <li>A passport valid well beyond your intended start date</li>
    <li>BLS, and ACLS or PALS for acute or paediatric posts</li>
    <li>Attested educational certificates, which many employers ask for at the visa stage</li>
</ul>

<p>Start the DataFlow verification early, because it depends on other institutions answering. Keep scanned copies of everything in one folder; you will upload the same documents to Mumaris Plus, the recruiter and the hospital.</p>

<h2>Language and Working Life</h2>

<p>English is the working language in most Saudi hospitals, and many departments are staffed by nurses from a dozen countries. Arabic is not usually required, but basic Arabic makes a real difference at the bedside and with families. Hospitals roster shifts that include nights and weekends, and specialist units often run long shifts. Check the roster pattern, the annual leave and the flight entitlement before you sign, because they decide how the contract feels far more than the job title does.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a licence to work as a nurse in Saudi Arabia?</h3>
<p>Yes. You need professional classification and registration with the Saudi Commission for Health Specialties (SCFHS), applied for through Mumaris Plus, alongside current registration in your home country.</p>

<h3>Is the Prometric exam the same as the SCFHS licence?</h3>
<p>No. Prometric runs the test centres for the SCFHS classification exam. The exam is one step; the licence is SCFHS classification and registration, which also needs DataFlow verification of your documents.</p>

<h3>What is DataFlow verification?</h3>
<p>Primary source verification. DataFlow confirms your qualification, licence and experience directly with the institutions that issued them, and SCFHS relies on that report before classifying you.</p>

<h3>How much do nurses earn in Saudi Arabia?</h3>
<p>Guides quote SAR 4,000 to 6,500 a month for staff nurses and more for ICU and senior nurses. The real figure depends on your SCFHS classification, the hospital and the agency, so compare the full package line by line.</p>

<h3>Is there a minimum wage for foreign nurses in Saudi Arabia?</h3>
<p>No. There is no statutory minimum wage for expatriate workers. The SAR 4,000 figure often quoted is the threshold at which a Saudi national counts as a full employee for Saudization.</p>

<h3>Can Indian nurses go to Saudi Arabia through any agency?</h3>
<p>No. The Embassy of India in Riyadh says nurses are recruited only through eMigrate: designated agents for Ministry of Health and military hospitals, and six State-run agencies, including NORKA Roots and ODEPC, for every other hospital.</p>

<h3>Is a nursing salary in Saudi Arabia tax-free?</h3>
<p>Saudi Arabia does not tax employment income. Your home country may still tax you until you meet its non-resident rules, so check before you assume the whole salary is tax-free.</p>

<h3>Do I need to speak Arabic to work as a nurse in Saudi Arabia?</h3>
<p>Usually not. English is the working language in most hospitals, but basic Arabic helps with patients and families and counts in your favour.</p>

<h2>People Also Search For</h2>

<h3>Saudi Prometric exam for nurses</h3>
<p>The SCFHS classification exam, booked through Prometric once SCFHS confirms your eligibility. It is one step of registration, not the licence.</p>

<h3>Mumaris Plus registration for nurses</h3>
<p>The SCFHS online portal where you create your profile, upload documents and pay fees for classification and registration.</p>

<h3>DataFlow verification for SCFHS</h3>
<p>Checks your degree, licence and experience at source. Delays usually come from institutions slow to answer, so start early.</p>

<h3>Staff nurse salary in Saudi Arabia</h3>
<p>Quoted from SAR 4,000 a month, which is not a minimum wage. Compare basic salary, housing, transport and flights separately.</p>

<h3>ICU nurse jobs in Riyadh</h3>
<p>Among the hardest posts to fill, in government, military and private hospitals. Specialist experience is the strongest bargaining position.</p>

<h3>MOH nurse jobs for Indian nurses</h3>
<p>Recruited through Indian agents designated for the Ministry of Health, and only through eMigrate.</p>

<h3>Nurse jobs in Saudi Arabia for Pakistani nurses</h3>
<p>Through an agency licensed by the Bureau of Emigration and Overseas Employment, with Pakistan Nursing and Midwifery Council registration verified by DataFlow.</p>

<h3>Female nurse jobs in Saudi Arabia</h3>
<p>Most nursing posts are open to women, and many hospitals recruit female nurses in large numbers for women's and children's services.</p>

<h2>More Job Guides</h2>

<p>Comparing nursing destinations or other Gulf routes? These cover them:</p>

<ul>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the American route for registered nurses, and the green card queue behind it.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; where UK sponsorship is genuinely available for clinical staff.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; why the care worker route closed to overseas applicants in July 2025.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; the SAR 4,000 Saudization threshold, explained in full.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Labour Law, Qiwa and Wage Protection System rules on a Saudi contract.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; hospital and hotel cleaning routes into the Kingdom.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; a Gulf contract where the basic salary split decides your gratuity.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, careers or financial advice. Licensing requirements, recruitment rules, salaries and contract terms change and differ by employer and by country of origin. Confirm the current position with the Saudi Commission for Health Specialties, your home country's emigration authority and the employer before applying or accepting an offer.</p>
HTML;
    }
}
