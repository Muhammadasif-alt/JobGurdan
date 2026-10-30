<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Pharmacy Technician Jobs in Columbus, Ohio: Requirements, Registration and Pay"
 *
 * Built on 28 October 2026 from the owner's outline. What changed against the brief:
 *
 * 1. The five Indeed buttons are dropped; OhioMeansJobs and the official pages stay as links. Every "[ADD REAL
 *    SOURCE]" placeholder is replaced with a checked source or removed.
 * 2. pharmacy.ohio.gov rejected our requests ("Request Rejected"), so Board facts come from search excerpts of the
 *    Board's own pages and notices: trainee registration valid 18 months once active, then an 18-month reinstatement if
 *    more time is needed; trainees at least 18 with a high school diploma, GED or foreign equivalent (except Ohio public
 *    high school programmes); FBI and BCI background checks dated within 24 months for all new technician applications;
 *    trainee application fee $45 from 30 September 2025; certified technicians hold PTCB or ExCPT; applications through
 *    eLicense Ohio. The guide says the Board site did not load.
 * 3. BLS API, May 2025: Columbus metro 3,480 pharmacy technicians, median $21.75 an hour or $45,240 a year (the brief
 *    said no Columbus figure existed); Ohio median $40,650, 10th to 90th percentile $34,980 to $56,520; US median
 *    $22.00 an hour.
 * 4. Columbus State fetched: two semesters, 26 to 32 weeks full time, autumn or spring starts, a 140-hour externship
 *    required for ASHP accreditation. PTCB fetched: CPhT application and PTCE cost $129.
 * 5. FAQs expanded to eight and a note for readers in Pakistan added.
 *
 * No Columbus poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class PharmacyTechnicianJobsColumbusOhioRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'pharmacy-technician-jobs-columbus-ohio-requirements';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Pharmacy Technician Jobs in Columbus, Ohio: Requirements, Registration and Pay',
                'excerpt' => 'What you need for a pharmacy technician job in Columbus: Ohio Board of Pharmacy trainee, registered and certified registration, the background check, Columbus State training, PTCB certification, and BLS pay of $21.75 an hour.',
                'content' => $content,
                'featured_image' => 'blogs/pharmacy-technician-jobs-columbus-ohio-requirements.jpg',
                'tags' => 'pharmacy technician jobs columbus ohio, ohio pharmacy technician requirements, pharmacy technician trainee ohio, pharmacy technician salary columbus, ohio board of pharmacy technician registration, ptcb certification ohio, pharmacy technician jobs no certification ohio, columbus state pharmacy technician',
                'meta_title' => 'Pharmacy Technician Jobs in Columbus, OH: Requirements',
                'meta_description' => 'Pharmacy technician jobs in Columbus: Ohio trainee, registered and certified registration, background checks, training, PTCB and BLS pay of $21.75 an hour.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To work as a pharmacy technician in Columbus, you must hold an active registration with the Ohio Board of Pharmacy before doing any technician work, even as a trainee.</strong> Ohio has three levels: Pharmacy Technician Trainee, Registered Pharmacy Technician and Certified Pharmacy Technician. Every new applicant needs FBI and Ohio BCI background checks, and certified status also needs a national credential from PTCB or ExCPT. Columbus pharmacy technicians earned a median of $21.75 an hour, or $45,240 a year, in May 2025, according to the US Bureau of Labor Statistics. The Board's website rejected our requests on 28 October 2026, so the Board rules below come from its published pages and notices; confirm them on the Board site before you apply.</p>

<h2>The Three Types of Pharmacy Technician Registration in Ohio</h2>

<ol>
    <li><strong>Pharmacy Technician Trainee:</strong> the entry point. You work in a licensed pharmacy while completing training. Once active, the registration lasts 18 months; if you need more time, the Board offers an 18-month reinstatement rather than a normal renewal.</li>
    <li><strong>Registered Pharmacy Technician:</strong> you have completed a Board-approved training programme and meet the Board's other requirements.</li>
    <li><strong>Certified Pharmacy Technician:</strong> you have completed approved training and hold current national certification from PTCB or ExCPT.</li>
</ol>

<p>Source: <a href="https://www.pharmacy.ohio.gov/Licensing/Techs/TechTrainee" rel="noopener">Ohio Board of Pharmacy: Technician trainee</a>.</p>

<h2>Can You Work as a Pharmacy Technician in Columbus With No Experience?</h2>

<p>Yes, as a trainee. Trainees must be at least 18 with a high school diploma, GED or foreign equivalent (Ohio public high school technician programmes have their own route). You do not need previous job experience, but your trainee registration must be active before you start, and the Board must have your background checks before it can make it active.</p>

<h2>Can You Work Without National Certification?</h2>

<p>Yes, as a trainee or a registered technician. Only the certified category needs PTCB or ExCPT. Many Columbus hospitals prefer or require certification, so if you search for jobs without certification, look at retail chains and community pharmacies that train, and at trainee posts.</p>

<h2>How Do You Become a Pharmacy Technician in Columbus? Step by Step</h2>

<ol>
    <li>Meet the basics: age 18 and a high school diploma or GED.</li>
    <li>Get FBI and Ohio BCI fingerprint background checks; the Board accepts checks dated within the last 24 months.</li>
    <li>Apply for trainee registration through eLicense Ohio. The Board lists a $45 trainee application fee from 30 September 2025.</li>
    <li>Wait for "Active" status before doing any technician duties.</li>
    <li>Complete a Board-approved training programme, either through an employer or a school.</li>
    <li>Sit the PTCB or ExCPT exam if you want certified status.</li>
    <li>Register as a registered or certified technician before your trainee registration runs out.</li>
</ol>

<p>Source: <a href="https://www.pharmacy.ohio.gov/licensing/techs/apply" rel="noopener">Ohio Board of Pharmacy: Apply as a pharmacy technician</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/pharmacy-technician-jobs-columbus-ohio-requirements-steps.jpg"
         alt="Infographic of the Ohio pharmacy technician path: background check, trainee registration, active status, approved training, PTCB or ExCPT exam, registered or certified status"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Training in Columbus</h2>

<p>Columbus State Community College's Pharmacy Technician Program takes two semesters, normally 26 to 32 weeks full time, with autumn and spring starts, and includes a 140-hour externship required for its ASHP accreditation. Employer training programmes approved by the Board are another route. PTCB's CPhT application and Pharmacy Technician Certification Exam cost $129. Sources: <a href="https://www.cscc.edu/academics/departments/pharmacy-technician/" rel="noopener">Columbus State: Pharmacy Technician Program</a> and <a href="https://www.ptcb.org/credentials/certified-pharmacy-technician" rel="noopener">PTCB: Certified Pharmacy Technician</a>.</p>

<h2>How Much Do Pharmacy Technicians Earn in Columbus?</h2>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Measure (BLS, May 2025)</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Figure</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Columbus metro, employed</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">3,480</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Columbus metro median</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$21.75 an hour, $45,240 a year</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Ohio median</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$40,650 a year</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Ohio 10th to 90th percentile</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$34,980 to $56,520 a year</td></tr>
<tr><td style="padding:8px;">US median</td><td style="padding:8px;">$22.00 an hour</td></tr>
</tbody>
</table>

<p>Hospitals often pay more than retail, and certification helps when you negotiate. Source: <a href="https://www.bls.gov/oes/current/oes_18140.htm" rel="noopener">BLS: Columbus metro occupational wages, May 2025</a>.</p>

<h2>What Does a Pharmacy Technician Do?</h2>

<p>Technicians support pharmacists by preparing and labelling prescriptions, managing inventory, handling insurance claims and helping patients at the counter. What you may do depends on your registration level under Ohio rules; some tasks, such as immunisation support, are limited to registered or certified technicians.</p>

<h2>Where to Find Pharmacy Technician Jobs in Columbus</h2>

<p>Use OhioMeansJobs, the state's official job board, plus hospital and health system careers pages and retail pharmacy chains' own sites. School career services also connect students with local pharmacies for externships. Source: <a href="https://ohiomeansjobs.ohio.gov/" rel="noopener">OhioMeansJobs</a>.</p>

<h2>What to Put on Your Resume With No Experience</h2>

<ul>
    <li>Registration status, such as "Active Pharmacy Technician Trainee, Ohio Board of Pharmacy".</li>
    <li>Training programme and expected completion date.</li>
    <li>Skills: accuracy, customer service, basic maths and computer use.</li>
    <li>Transferable experience: retail, cashier or healthcare support work.</li>
</ul>

<h2>A Note for Readers in Pakistan</h2>

<p>Ohio technician registration requires being in the US with work authorisation; it is not a visa route. If you are qualified in pharmacy in Pakistan, look at roles at home or read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a license to be a pharmacy technician in Ohio?</h3>
<p>Ohio calls it a registration, and you need an active one before doing technician duties, even as a trainee.</p>

<h3>Is a background check required?</h3>
<p>Yes. FBI and Ohio BCI checks, dated within 24 months, for every new technician application.</p>

<h3>How long does trainee registration last?</h3>
<p>18 months once active, with an 18-month reinstatement if you need more time.</p>

<h3>Do I need PTCB or ExCPT certification?</h3>
<p>Only for the Certified Pharmacy Technician category.</p>

<h3>How much is the trainee application?</h3>
<p>$45, according to the Board's fee notice effective 30 September 2025.</p>

<h3>How much do pharmacy technicians earn in Columbus?</h3>
<p>A median of $21.75 an hour, or $45,240 a year, in May 2025.</p>

<h3>How long is the Columbus State programme?</h3>
<p>Two semesters, 26 to 32 weeks full time, with a 140-hour externship.</p>

<h3>Can I work part time?</h3>
<p>Yes. Part-time roles are common in retail and community pharmacies.</p>

<h2>Bottom Line</h2>

<p>Get your background checks, register as a trainee and wait for active status, complete approved training, then move up to registered or certified status before the trainee registration ends. This guide cannot promise a job or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.pharmacy.ohio.gov/Licensing/Techs/TechTrainee" rel="noopener">Ohio Board of Pharmacy: Technician trainee</a> and <a href="https://www.pharmacy.ohio.gov/licensing/techs/apply" rel="noopener">Apply</a>, which rejected our requests on 28 October 2026</li>
<li><a href="https://www.bls.gov/oes/current/oes_18140.htm" rel="noopener">BLS: Columbus metro occupational wages, May 2025</a></li>
<li><a href="https://www.cscc.edu/academics/departments/pharmacy-technician/" rel="noopener">Columbus State: Pharmacy Technician Program</a></li>
<li><a href="https://www.ptcb.org/credentials/certified-pharmacy-technician" rel="noopener">PTCB: Certified Pharmacy Technician</a></li>
<li><a href="https://ohiomeansjobs.ohio.gov/" rel="noopener">OhioMeansJobs</a>, checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/medical-assistant-jobs-phoenix-no-experience">Medical Assistant Jobs in Phoenix</a>, another short-training healthcare route.</li>
    <li><a href="/blog/cna-jobs-atlanta-georgia-certification-pay">CNA Jobs in Atlanta</a>, a nursing assistant route.</li>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, licensed nursing in the US.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 28 October 2026; the Ohio Board of Pharmacy website did not load from our network, so confirm its current rules and fees. It does not promise a job or a wage.</p>
HTML;
    }
}
