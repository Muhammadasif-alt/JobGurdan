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
 * "Dental Assistant Jobs in USA" — the American counterpart to the Canadian
 * guide. It owns the BLS wage and outlook figures for SOC 31-9091, the way
 * state dental boards decide what training and licensing you need, DANB's
 * certifications and their eligibility pathways, and the CODA route into an
 * accredited program.
 *
 * Checked on 28 September 2026 against the BLS Occupational Outlook Handbook
 * entries for dental assistants and dental hygienists, DANB's state
 * requirements and CDA exam pages, and CODA's program search.
 *
 * Corrections to the brief:
 *
 * 1. Every "Apply Now" in the brief pointed at Indeed, and one pay figure was
 *    lifted from an Indeed listing ("up to $32 an hour"). Neither is
 *    published here.
 *
 * 2. The brief's second route, the "ADAA Career Center" at jobs.adaausa.org,
 *    does not resolve at all. The ADAA's own site is a temporary one marked
 *    "under construction" whose menu is Home, FAQ, Radiography Coursework and
 *    Journals — there is no job board behind it.
 *
 * 3. "Indeed currently shows roughly 27,000 Dental Assistant openings" is a
 *    count from an aggregator. BLS projects about 53,000 openings a year,
 *    which is the figure a reader can check.
 *
 * 4. The brief answers "Do you need a degree or certification?" with "it
 *    depends on your state" and stops. BLS names a postsecondary nondegree
 *    award as the typical entry-level education, and says states typically do
 *    not license entry-level assistants. The thing that actually decides it
 *    is the state dental board, and DANB publishes all 51 of them.
 *
 * 5. The brief never mentions DANB's pathways, which are the real answer to
 *    "can I do this without school": Pathway II is a high school diploma plus
 *    3,500 hours of approved work experience.
 *
 * 6. The brief's hygienist comparison says a hygienist "needs a separate
 *    license and degree" without the numbers. BLS: an associate's degree,
 *    licensure in every state, and a $98,100 median against $48,070.
 *
 * The brief's BLS figures were all correct and are kept: $48,070 median as of
 * May 2025, 7 per cent growth from 2025 to 2035, described by BLS as much
 * faster than average, and about 53,000 openings a year.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DentalAssistantJobsUsaBlogSeeder extends Seeder
{
    /**
     * The federal job board, run by the Office of Personnel Management. The
     * VA, the Indian Health Service and military dental clinics all hire
     * assistants through it, it is free, and it is not an aggregator. Private
     * practices are reached through their own careers sites, which the guide
     * and the job description both name.
     */
    private const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=dental%20assistant';

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
        $title = 'Dental Assistant Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'BLS puts the dental assistant median at $48,070 and projects 7% growth to 2035. Whether you need a program, an exam or an x-ray permit is decided by your state board, and DANB will certify you on 3,500 hours if you skip school.',
                'content' => $content,
                'featured_image' => 'blogs/dental-assistant-jobs-in-usa.jpg',
                'tags' => 'dental assistant jobs usa, dental assistant salary, danb cda certification, coda accredited dental assisting program, dental assistant state requirements, radiography certification dental assistant, entry level dental assistant jobs, dental assistant vs dental hygienist',
                'meta_title' => 'Dental Assistant Jobs in USA 2026: Pay and How to Apply',
                'meta_description' => 'Dental assistant jobs in the USA: the $48,070 BLS median, the state rules that decide if you need a program or an exam, and where to apply.',
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
            ['name' => 'US Dental Practices, Dental Groups & Federal Dental Clinics (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-dental-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Dental Assistant — General, Orthodontic and Pediatric Practices, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time; many practices run four-day weeks and some Saturdays',
                'language' => 'English',
                // What an assistant may legally do, and so what they are paid,
                // is set state by state, so no single national range is quoted
                // beyond the BLS median in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Dental assistant roles with US general, orthodontic and pediatric practices and federal dental clinics. Check your state board before you apply.',
                'seo_keywords' => 'dental assistant jobs usa, registered dental assistant jobs, entry level dental assistant, orthodontic assistant jobs, federal dental assistant jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>General dental practices, orthodontic and pediatric offices, oral surgery clinics, community health centers and federal dental clinics across the United States hire dental assistants for full-time and part-time roles.</p>

<h3>What the work involves</h3>
<p>Preparing treatment rooms and sterilizing instruments under the practice's infection-control protocol, assisting the dentist chairside, taking and processing radiographs where the state allows it, recording medical histories and treatment notes, and handling scheduling and supplies. Expanded functions such as coronal polishing, sealants and fluoride depend entirely on the state and on the permits you hold.</p>

<h3>Requirements</h3>
<ul>
    <li>Whatever your state dental board requires. Some states require graduation from an accredited program and an exam; others set no formal educational requirement at all</li>
    <li>A separate radiography permit in many states before you may operate an x-ray machine</li>
    <li>Current hands-on CPR or BLS, which DANB requires for every certification pathway and most employers ask for anyway</li>
    <li>The right to work in the United States</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>National median.</strong> BLS reports $48,070 a year as of May 2025 for dental assistants</li>
    <li><strong>What moves it.</strong> State and metro cost of living, the permits you hold, whether the setting is general practice or a surgical or orthodontic specialty</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Look up your state's rules first.</strong> DANB publishes the requirements for all 50 states and the District of Columbia, including which duties you may perform and which exams your state accepts.</p>

<p><strong>Note:</strong> duties, permits and pay are set by state dental boards and by employers &mdash; not by JobGader. Confirm the requirements with your state board before enrolling in a program or accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Dental assisting is one of the few clinical jobs in American health care you can sometimes enter without a degree &mdash; and one of the few where the rules change completely when you cross a state line. In one state you need an accredited program and a board exam before you touch a patient. In the next there is no formal educational requirement at all.</p>

<p>This guide uses the figures the Bureau of Labor Statistics publishes, the requirements the Dental Assisting National Board (DANB) lists for each state, and the program list the American Dental Association's Commission on Dental Accreditation (CODA) maintains. It does not send you to a job aggregator, and it does not quote pay from one.</p>

<h2>What the Official Data Says</h2>

<ul>
    <li><strong>Median pay:</strong> <strong>$48,070 a year</strong> as of <strong>May 2025</strong></li>
    <li><strong>Growth:</strong> <strong>7 per cent from 2025 to 2035</strong>, which BLS describes as <strong>much faster than the average</strong> for all occupations</li>
    <li><strong>Openings:</strong> about <strong>53,000 a year</strong>, on average, over that decade</li>
    <li><strong>Typical entry-level education:</strong> a <strong>postsecondary nondegree award</strong></li>
</ul>

<p>Most of those 53,000 openings come from people leaving the occupation rather than from new posts being created, which is why the hiring stays steady even in a slow year.</p>

<h2>What a Dental Assistant Does</h2>

<ul>
    <li>Preparing treatment rooms and sterilizing instruments under the practice's infection-control protocol</li>
    <li>Working chairside: passing instruments, managing suction, keeping the field dry</li>
    <li>Taking and processing dental x-rays, where the state permits it</li>
    <li>Recording medical histories, vitals and treatment notes</li>
    <li>Scheduling, billing support and ordering supplies</li>
</ul>

<p>Expanded functions &mdash; coronal polishing, sealants, topical fluoride, taking impressions &mdash; are the part that varies. Each state decides which of them an assistant may perform and what permit is needed first.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/dental-assistant-jobs-in-usa-chairside.jpg"
         alt="A dental assistant in navy scrubs and blue gloves working chairside with a mirror and probe while a patient reclines, with a tooth x-ray on the monitor behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Do You Need a Degree or a Certificate?</h2>

<p>BLS puts it plainly: <em>"Some states require assistants to graduate from an accredited program and pass an exam. In other states, there are no formal educational requirements."</em> On licensing it adds that states <em>"typically do not require licenses for entry-level dental assistants"</em>, though some require assistants to be licensed, registered or certified to enter the job or to move up.</p>

<p>So there is no national answer. There are fifty-one of them, and the body that holds yours is your <strong>state dental board</strong>. DANB publishes the requirements for <strong>all 50 states and the District of Columbia</strong> &mdash; job titles, allowable duties, which exams count and which state practice act governs them &mdash; at <a href="https://www.danb.org/state-requirements" target="_blank" rel="noopener">danb.org/state-requirements</a>. DANB also administers state-specific exams in seven states: <strong>Arizona, Maryland, Missouri, New Jersey, New York, Oregon and Washington</strong>.</p>

<div style="background:#f5f8fc;border-left:3px solid #2f7fc9;border-radius:0 12px 12px 0;padding:18px 22px;margin:26px 0;">
    <p style="margin:0;"><strong>Radiography is usually its own permit.</strong> BLS notes that states may require assistants to meet specific licensing requirements to work in radiography, infection control or other specialties. Many job ads ask for an x-ray certification precisely because the practice cannot let you take images without one.</p>
</div>

<h2>DANB Certification, and the Route Without School</h2>

<p>The national credential most American job ads name is DANB's <strong>Certified Dental Assistant (CDA)</strong>. It is not one exam but three, taken together or separately:</p>

<ul>
    <li><strong>General Chairside Assisting (GC)</strong></li>
    <li><strong>Infection Control (ICE)</strong></li>
    <li><strong>Radiation Health and Safety (RHS)</strong></li>
</ul>

<p>There are three ways to become eligible, and the second is the one worth knowing if you cannot afford to stop working and study:</p>

<ul>
    <li><strong>Pathway I:</strong> graduate from a CODA-accredited dental assisting or dental hygiene program</li>
    <li><strong>Pathway II:</strong> a <strong>high school diploma or equivalent plus 3,500 hours of approved work experience</strong></li>
    <li><strong>Pathway III:</strong> former DANB CDA status, or graduation from or enrollment in a CODA-accredited DDS or DMD program, or a dental degree earned outside the US or Canada</li>
</ul>

<p><strong>Every pathway requires current, hands-on CPR, BLS or ACLS</strong> from a provider DANB accepts. An online-only card will not do.</p>

<p>DANB also offers the <strong>National Entry Level Dental Assistant (NELDA)</strong> for people at the start, and the standalone <strong>RHS</strong> and <strong>ICE</strong> credentials, which are often what a state permit or an employer actually wants.</p>

<h2>Finding an Accredited Program</h2>

<p>If your state requires an accredited program &mdash; or you want Pathway I rather than 3,500 hours &mdash; the list you need is CODA's. The Commission on Dental Accreditation is the American Dental Association's accrediting body and it evaluates more than 1,400 dental and dental-related programs, including allied programs in <strong>dental assisting</strong>. Search it by state or city at <a href="https://coda.ada.org/find-a-program" target="_blank" rel="noopener">coda.ada.org/find-a-program</a>.</p>

<p>Accreditation is not a detail. A program that CODA has not accredited may leave you ineligible for Pathway I and, in some states, unable to sit the board exam at all.</p>

<h2>What You Can Expect to Earn</h2>

<p>The national median is <strong>$48,070 a year</strong> (BLS, May 2025). Half of dental assistants earn more than that and half earn less. What moves an individual figure:</p>

<ul>
    <li><strong>State and metro.</strong> Pay tracks cost of living closely; BLS publishes the state and metropolitan figures on the <a href="https://www.bls.gov/ooh/healthcare/dental-assistants.htm#tab-5" target="_blank" rel="noopener">State &amp; Area Data</a> tab of its dental assistant page</li>
    <li><strong>Permits.</strong> A radiography permit or a state registration widens the set of jobs you can hold, and the ones you can hold pay more</li>
    <li><strong>Setting.</strong> Oral surgery and orthodontic practices pay differently from general family practices</li>
    <li><strong>Schedule.</strong> Some practices run four-day full-time weeks with benefits, which changes the hourly comparison</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/dental-assistant-jobs-in-usa-credentials.jpg"
         alt="A dental assistant wearing a DENTAL ASSISTANT name badge examining a patient with a mirror and explorer in a bright American dental practice"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Roles You Will See Advertised</h2>

<table style="width:100%;border-collapse:collapse;margin:22px 0;">
    <thead>
        <tr style="background:#f5f8fc;">
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Title</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">What it means</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Dental Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Chairside support in a general or family practice</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Registered / Licensed Dental Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">A state-credentialed title, with duties the state has widened</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Expanded Functions Dental Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Permitted to perform listed procedures the state has delegated</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Orthodontic Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Braces and aligner work in an orthodontic practice</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Pediatric Dental Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Children's dentistry, with the behaviour management it needs</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Oral Surgery Assistant</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Surgical assisting, often with sedation monitoring duties</td></tr>
    </tbody>
</table>

<h2>Where to Apply</h2>

<p>Dental assisting is hired locally. There is no national employer, so the useful routes are these:</p>

<ol>
    <li><strong>Federal dental clinics.</strong> The Department of Veterans Affairs, the Indian Health Service and military dental clinics hire assistants through <a href="https://www.usajobs.gov/Search/Results?k=dental%20assistant" target="_blank" rel="noopener">USAJOBS</a>, the federal government's own job site. It is run by the Office of Personnel Management, it is free, and federal roles carry federal benefits.</li>
    <li><strong>Dental group careers pages.</strong> Multi-location groups post their own openings and hire continuously &mdash; for example <a href="https://smilebrands.com/careers/" target="_blank" rel="noopener">Smile Brands</a> and <a href="https://careers.aspendental.com/" target="_blank" rel="noopener">Aspen Dental</a>.</li>
    <li><strong>Independent practices near you.</strong> Most American dentistry is single-site. Many of those practices never advertise beyond a sign in the window and a note on their own site, so a short email with your résumé attached reaches people no search ever will.</li>
    <li><strong>Your program's externship.</strong> If you take an accredited program, the clinical placement is the single most common route to a first offer.</li>
    <li><strong>Community health centers.</strong> Federally Qualified Health Centers run dental clinics and hire assistants, often with loan-repayment programs attached.</li>
</ol>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Look up your state first</strong> on <a href="https://www.danb.org/state-requirements" target="_blank" rel="noopener">DANB's state requirements</a>. This decides everything that follows &mdash; whether you need a program, an exam, a registration, an x-ray permit.</li>
    <li><strong>Get the CPR card.</strong> Hands-on BLS from an accepted provider. Every DANB pathway needs it and most employers ask regardless.</li>
    <li><strong>Close the training gap</strong> if your state has one: a CODA-accredited program, or start counting toward Pathway II's 3,500 hours in a practice that will train you.</li>
    <li><strong>Sit the exams your state accepts</strong> &mdash; the state exam where there is one, or DANB's RHS, ICE and GC toward the CDA.</li>
    <li><strong>Write the résumé around the words in the ad:</strong> sterilization and infection control, chairside assisting, radiographs, the practice software, and the permits you hold with their numbers.</li>
    <li><strong>Apply through the employer</strong>, not through a middleman, and say in the first line which permits you already hold.</li>
</ol>

<h2>Dental Assistant or Dental Hygienist?</h2>

<p>They are frequently confused and they are not close. An assistant supports the dentist; a hygienist performs cleanings and preventive care under their own licence.</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;">
    <thead>
        <tr style="background:#f5f8fc;">
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;"></th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Dental assistant</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Dental hygienist</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Typical entry education</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Postsecondary nondegree award</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Associate's degree</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Licence</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Usually not required at entry level</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Required in every state</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Median pay (May 2025)</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$48,070</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$98,100</td></tr>
    </tbody>
</table>

<p>Assisting is a common way into hygiene: you learn the practice, then take an accredited hygiene program.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do dental assistants make in the USA?</h3>
<p>The national median is $48,070 a year as of May 2025, according to the Bureau of Labor Statistics. Pay varies widely by state, by the permits you hold and by whether the practice is general or a specialty.</p>

<h3>Can I become a dental assistant with no experience?</h3>
<p>In some states, yes. BLS says that in some states there are no formal educational requirements and assistants learn on the job, while other states require graduation from an accredited program and an exam first. Your state dental board decides, and DANB lists the rules for all 51 jurisdictions.</p>

<h3>Do I need a certification to work as a dental assistant?</h3>
<p>States typically do not license entry-level assistants, but some require registration or certification to enter the job or to advance. A separate radiography permit is commonly required before you may take x-rays.</p>

<h3>What is the DANB CDA and how do I qualify for it?</h3>
<p>The Certified Dental Assistant is made up of three exams: General Chairside Assisting, Infection Control, and Radiation Health and Safety. You qualify through a CODA-accredited program, through 3,500 hours of approved work experience with a high school diploma, or through one of the dental-degree routes. All of them need current hands-on CPR, BLS or ACLS.</p>

<h3>How long does dental assistant training take?</h3>
<p>It depends on the route. A CODA-accredited certificate or diploma program is the usual one; the alternative is DANB's Pathway II, which asks for 3,500 hours of approved work experience instead of a program.</p>

<h3>Are dental assistants in demand?</h3>
<p>BLS projects employment to grow 7 per cent from 2025 to 2035, which it describes as much faster than the average for all occupations, with about 53,000 openings a year over the decade.</p>

<h3>Where can I apply for dental assistant jobs without using a job aggregator?</h3>
<p>USAJOBS carries federal dental clinic roles at the VA, the Indian Health Service and military clinics. Dental groups such as Smile Brands and Aspen Dental post on their own careers sites. Beyond that, most American dentistry is single-site, so applying directly to practices near you reaches jobs that are never advertised.</p>

<h3>What is the difference between a dental assistant and a dental hygienist?</h3>
<p>An assistant supports the dentist chairside and handles preparation, sterilization, x-rays and records. A hygienist performs cleanings and preventive care, needs an associate's degree, is licensed in every state, and had a median wage of $98,100 in May 2025 against the assistant's $48,070.</p>

<h2>People Also Search For</h2>

<h3>Dental assistant state requirements</h3>
<p>DANB publishes them for all 50 states and DC, including allowable duties and which exams each state accepts.</p>

<h3>DANB CDA exam pathways</h3>
<p>Three routes: a CODA-accredited program, 3,500 hours of work experience with a high school diploma, or a dental-degree route.</p>

<h3>CODA accredited dental assisting programs</h3>
<p>Searchable by state or city on the American Dental Association's accreditation site.</p>

<h3>Dental assistant x-ray certification</h3>
<p>A separate state permit in many states, and one of the most common requirements in job ads.</p>

<h3>Entry level dental assistant jobs</h3>
<p>Realistic in states with no formal educational requirement, where practices train on the job.</p>

<h3>Expanded functions dental assistant</h3>
<p>A state-delegated role permitted to perform listed procedures such as coronal polishing and sealants.</p>

<h3>Federal dental assistant jobs</h3>
<p>VA, Indian Health Service and military dental clinics, advertised on USAJOBS.</p>

<h3>Dental assistant salary by state</h3>
<p>Published by BLS on the State &amp; Area Data tab of its dental assistant page.</p>

<h2>More Job Guides</h2>

<p>Comparing health care support roles, or the same job in another country? These cover them:</p>

<ul>
    <li><a href="/blog/dental-assistant-jobs-in-canada">Dental Assistant Jobs in Canada</a> &mdash; the same job across the border, where registration is required in every province except Ontario and Quebec.</li>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the clinic role that mixes admin with clinical duties, and what it pays.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; an entry-level health care job priced by NHS pay bands.</li>
    <li><a href="/blog/entry-level-healthcare-jobs">Entry Level Healthcare Jobs</a> &mdash; the roles you can start without a degree, and which ones lead somewhere.</li>
    <li><a href="/blog/medical-receptionist-jobs-in-australia">Medical Receptionist Jobs in Australia</a> &mdash; front-desk health care work and the award that sets its pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or careers advice. Dental assisting duties, permits, exams and wages are set by individual state dental boards and by employers, and they change. Confirm the current position with your state board, DANB and BLS before enrolling in a program, sitting an exam or accepting an offer.</p>
HTML;
    }
}
