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
 * "Medical Records Clerk Jobs in USA" — the health information counterpart to
 * the medical assistant guide. It owns the BLS wage and outlook figures for
 * SOC 29-2072, the distinction between that occupation and SOC 43-4071 file
 * clerks, and AHIMA's entry-level credential route.
 *
 * Checked on 28 September 2026 against the BLS Occupational Employment and
 * Wage Statistics API for May 2025, the BLS National Employment Matrix for
 * 2025-35 at data.bls.gov/projections/occupationProj, the Occupational
 * Outlook Handbook entry for medical records specialists, and AHIMA's own
 * CCA and RHIT certification pages.
 *
 * Corrections to the brief:
 *
 * 1. Every "Apply Now" pointed at Indeed, and two of the three supplied
 *    images carried an "Apply Now on Indeed" strip. The links are not
 *    published here and the strips were cropped out of the artwork.
 *
 * 2. Every figure was a year out of date. May 2024 has been superseded by
 *    May 2025: the median is $51,140 not $50,250, the tenth percentile
 *    $37,000 not $35,780, the ninetieth $81,150 not $80,950, hospitals
 *    $59,120 not $56,520, physician offices $47,120 not $45,620.
 *
 * 3. The outlook was also a cycle behind. BLS now projects 8 per cent growth
 *    from 2025 to 2035 with about 14,000 openings a year, not 7 per cent
 *    from 2024 to 2034 with 14,200.
 *
 * 4. "Indeed lists around 2,000 openings under this title" is an aggregator
 *    count standing in for demand. BLS projects about 14,000 openings a year,
 *    which is a figure a reader can check.
 *
 * 5. The brief's headline answer — "most roles ask for a high school diploma
 *    or GED" — is attached to the pay of an occupation whose typical entry
 *    education BLS records as a postsecondary nondegree award. The brief
 *    states that correctly three paragraphs later and never reconciles the
 *    two. A records job that is only filing is SOC 43-4071, file clerks, on a
 *    $43,600 median in an occupation projected to shrink 15.8 per cent by
 *    2035. That gap is the most important thing on this page.
 *
 * 6. The brief never names a credential. AHIMA's CCA asks for nothing beyond
 *    a high school diploma and costs $199 for members, which is the concrete
 *    answer to the question the brief leaves open.
 *
 * The brief's AHIMA Career Center route was sound and is kept: AHIMA links to
 * careerassist.ahima.org from its own certification and careers pages.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MedicalRecordsClerkJobsUsaBlogSeeder extends Seeder
{
    /**
     * The federal job board, run by the Office of Personnel Management. The
     * Veterans Health Administration, the Indian Health Service and military
     * treatment facilities all hire records staff through it, it is free, and
     * it is not an aggregator. Private health systems are reached through
     * their own careers sites, which the guide names.
     */
    private const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=medical%20records';

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
        $title = 'Medical Records Clerk Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'BLS puts the medical records specialist median at $51,140 and projects 8% growth to 2035. But the filing-only version of this job is a file clerk on $43,600, in an occupation shrinking 15.8%. AHIMA will examine you on a high school diploma alone.',
                'content' => $content,
                'featured_image' => 'blogs/medical-records-clerk-jobs-in-usa.jpg',
                'tags' => 'medical records clerk jobs usa, medical records specialist salary, ahima cca certification, rhit certification, health information technician jobs, medical records clerk requirements, entry level medical records jobs, ahima career center',
                'meta_title' => 'Medical Records Clerk Jobs USA 2026: Pay and How to Apply',
                'meta_description' => 'Medical records clerk jobs in the USA: the $51,140 BLS median, why the job title covers two very different careers, and the exam that needs only a diploma.',
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
            ['name' => 'US Hospitals, Health Systems & Federal Health Agencies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-medical-records-aggregated']
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
                'position' => 'Medical Records Clerk / Health Information Technician, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time; hospital records departments often run evening and weekend shifts',
                'language' => 'English',
                // The same job title covers a $43,600 filing role and a
                // $51,140 coding role, so no single range is quoted beyond
                // the BLS medians the guide sets out.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Medical records and health information roles with US hospitals, physician groups and federal health agencies. Check which of the two jobs the ad is really for.',
                'seo_keywords' => 'medical records clerk jobs usa, health information technician jobs, medical records specialist jobs, release of information jobs, federal medical records jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hospitals, physician groups, outpatient centers, nursing facilities, federal health agencies and the law firms that review medical evidence all hire medical records staff across the United States, full-time and part-time.</p>

<h3>What the work involves</h3>
<p>Retrieving, scanning and filing inpatient and outpatient charts; logging and fulfilling release-of-information requests under HIPAA; reviewing records for missing or incomplete documentation and chasing the clinician who owes it; maintaining the electronic health record so that coders, billers and auditors can work from it. Where the role extends into coding, it also means abstracting clinical data and assigning ICD and CPT codes.</p>

<h3>Requirements</h3>
<ul>
    <li>A high school diploma or GED for the filing end of the work; a postsecondary certificate or an AHIMA credential for the records specialist end, which is where the pay sits</li>
    <li>Working knowledge of medical terminology and anatomy</li>
    <li>Experience with an electronic health record system, most commonly Epic or Cerner</li>
    <li>A practical grasp of HIPAA and of what may and may not be released, to whom, and on what authority</li>
    <li>The right to work in the United States</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>National median.</strong> BLS reports $51,140 a year, or $24.59 an hour, as of May 2025 for medical records specialists</li>
    <li><strong>What moves it.</strong> Whether the job codes or only files, the setting, and whether you hold an AHIMA credential</li>
    <li><strong>Setting.</strong> Hospitals pay a $59,120 median against $47,120 in physician offices</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Read the duties, not the title.</strong> "Medical Records Clerk" is used for two different jobs: one that files and retrieves, and one that abstracts and codes. BLS tracks them as separate occupations with a $7,500 gap between their medians, and only one of the two is growing.</p>

<p><strong>Note:</strong> duties, pay and credential requirements are set by employers and by AHIMA &mdash; not by JobGader. Confirm the requirements with the employer and with AHIMA before enrolling in a program, booking an exam or accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"Medical Records Clerk" is one job title covering two quite different careers, and the gap between them is about $7,500 a year and the direction of travel. One is filing and retrieving. The other abstracts clinical data and assigns codes. Employers use the same words for both, which is why so many people take the first and assume they are on the pay scale of the second.</p>

<p>This guide uses the figures the Bureau of Labor Statistics published for May 2025 and its 2025-35 employment projections, and the eligibility rules the American Health Information Management Association (AHIMA) publishes for its own exams. It does not send you to a job aggregator, and it does not quote pay from one.</p>

<h2>What the Official Data Says</h2>

<ul>
    <li><strong>Median pay:</strong> <strong>$51,140 a year</strong>, or <strong>$24.59 an hour</strong>, as of <strong>May 2025</strong>, for medical records specialists</li>
    <li><strong>The range:</strong> the lowest tenth earned under <strong>$37,000</strong>; the highest tenth over <strong>$81,150</strong></li>
    <li><strong>Growth:</strong> <strong>8 per cent from 2025 to 2035</strong>, which BLS describes as <strong>much faster than the average</strong> for all occupations. The average across all occupations is 3.5 per cent</li>
    <li><strong>Openings:</strong> about <strong>14,000 a year</strong>, on average, over that decade</li>
    <li><strong>Typical entry-level education:</strong> a <strong>postsecondary nondegree award</strong> &mdash; a certificate, not a degree</li>
    <li><strong>Work experience required:</strong> none. <strong>On-the-job training:</strong> none</li>
</ul>

<p>Note the shape of that: no prior experience, no training period, but a certificate expected before you start. This is a job you qualify for by examination rather than by apprenticeship, and that is the single most useful thing to know about it.</p>

<h2>The Two Jobs Behind One Title</h2>

<p>BLS counts the filing job and the records job as different occupations. If an advert describes only retrieving, copying and filing charts, it is describing <strong>file clerks</strong> &mdash; and that occupation is shrinking.</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;">
    <thead>
        <tr style="background:#f5f8fc;">
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Occupation</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Median (May 2025)</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">2025-35</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Typical entry education</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>File clerks</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$43,600</td><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>&minus;15.8%</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">High school diploma</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Medical secretaries and administrative assistants</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$45,930</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">+4.8%</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">High school diploma</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Medical records specialists</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$51,140</td><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>+7.8%</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Postsecondary nondegree award</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Health information technologists and medical registrars</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$68,020</td><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>+15.9%</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Associate's degree</td></tr>
    </tbody>
</table>

<p>Read that column of growth rates from top to bottom. The work that is only handling paper is disappearing; the work that structures and interprets the data is not. A medical records clerk job is worth taking either way &mdash; but take it knowing which rung you are standing on and what the next one costs.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/medical-records-clerk-jobs-in-usa-overview.jpg"
         alt="A medical records clerk in navy scrubs sorting a stack of patient files at a desk in an American clinic, with a laptop and the New York skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What a Medical Records Clerk Actually Does</h2>

<ul>
    <li>Retrieving, scanning and filing inpatient and outpatient charts</li>
    <li>Logging and fulfilling release-of-information requests &mdash; the paperwork that sends a record to another provider, an insurer, a lawyer or the patient</li>
    <li>Reviewing charts for missing or incomplete documentation, and chasing the clinician who owes it</li>
    <li>Keeping the electronic health record clean enough for coders, billers and auditors to work from</li>
    <li>Protecting patient privacy at every step, which under HIPAA is a legal duty and not an office courtesy</li>
</ul>

<p>Where the role tips over into records <em>specialist</em> work, it adds abstracting clinical data from the chart and assigning ICD diagnosis codes and CPT procedure codes. That is the part that gets paid for, and the part an exam certifies.</p>

<h2>Do You Need a Degree?</h2>

<p><strong>No. But a diploma on its own is the low-paid half of this job.</strong></p>

<p>BLS names a <strong>postsecondary nondegree award</strong> as the typical entry-level education for medical records specialists. That is a certificate rather than an associate's or bachelor's degree, and the fastest version of it is an exam you can sit without going back to school at all.</p>

<div style="background:#f5f8fc;border-left:3px solid #2f7fc9;border-radius:0 12px 12px 0;padding:18px 22px;margin:26px 0;">
    <p style="margin:0 0 10px;"><strong>AHIMA's CCA is the door.</strong> The Certified Coding Associate has exactly one eligibility requirement, in AHIMA's own words: <em>"Candidates who want to sit for the CCA exam must have a high school diploma."</em> Coding experience and a training program are <strong>recommended, not required</strong>.</p>
    <p style="margin:0;">The exam is <strong>105 questions in two hours</strong> and costs <strong>$199 for AHIMA members, $299 for non-members</strong>. AHIMA describes it as aimed at early-career professionals starting out in healthcare.</p>
</div>

<p>If you want the credential most hospital records departments name in their adverts, that is the <strong>RHIT</strong> &mdash; Registered Health Information Technician. It is a bigger commitment: AHIMA requires an <strong>associate degree from a Health Information Management program accredited by CAHIIM</strong>, the Commission on Accreditation for Health Informatics and Information Management Education. The exam is 150 questions in three and a half hours, at $229 for members and $299 for non-members, sat at a Pearson VUE center.</p>

<p>Both are listed, with AHIMA's other credentials, on <a href="https://www.ahima.org/certification-careers/certifications-overview/" target="_blank" rel="noopener">AHIMA's certifications overview</a>. CAHIIM's accredited programs are searchable at <a href="https://www.cahiim.org/programs/program-directory" target="_blank" rel="noopener">cahiim.org</a> &mdash; and accreditation is not a detail, because an unaccredited program will not make you eligible for the RHIT.</p>

<p>The honest order for someone starting from a high school diploma: take the clerk job, sit the CCA while you are in it, and let the employer's tuition assistance pay for the CAHIIM program that leads to the RHIT.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/medical-records-clerk-jobs-in-usa-guide.jpg"
         alt="A health information clerk in blue scrubs and glasses reading a patient record beside binders marked Patient Files, Medical Records and Health Information"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What You Can Expect to Earn</h2>

<p>The national median is <strong>$51,140 a year</strong> (BLS, May 2025) &mdash; almost exactly the median for all American occupations, which is $50,980. Half of medical records specialists earn more and half earn less. Where you work moves it more than anything else:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;">
    <thead>
        <tr style="background:#f5f8fc;">
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Where you work</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Median (May 2025)</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Jobs</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Management of companies and enterprises</td><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>$60,980</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">14,870</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Hospitals</td><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>$59,120</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">55,530</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Government</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$58,680</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">940</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Outpatient care centers</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$50,900</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">9,510</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Administrative and support services</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$49,490</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">16,040</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Offices of physicians</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$47,120</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">34,860</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;">Professional, scientific and technical services</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">$46,800</td><td style="padding:10px 12px;border:1px solid #e2e8f0;">19,340</td></tr>
    </tbody>
</table>

<p>A hospital pays roughly <strong>$12,000 a year more</strong> than a physician's office for the same job title. If you are choosing between two offers and one is a hospital, that is usually the whole argument. BLS publishes the state and metropolitan figures on the <a href="https://www.bls.gov/ooh/healthcare/medical-records-and-health-information-technicians.htm#tab-5" target="_blank" rel="noopener">State &amp; Area Data</a> tab of its medical records specialists page.</p>

<h2>Where Medical Records Clerks Work</h2>

<table style="width:100%;border-collapse:collapse;margin:22px 0;">
    <thead>
        <tr style="background:#f5f8fc;">
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">Setting</th>
            <th style="text-align:left;padding:10px 12px;border:1px solid #e2e8f0;">What the work looks like</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Hospitals and health systems</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Chart completion, discharge record review, release of information, shift work</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Physician offices and clinics</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Filing, referrals, chasing outside records, often front-desk duties too</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Federal health agencies</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">VA and Indian Health Service records technician roles, with background checks and federal benefits</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Nursing and residential care</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Long-stay records, care-plan documentation, survey readiness</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Law firms and insurers</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Organising and summarising medical evidence for injury and disability cases</td></tr>
        <tr><td style="padding:10px 12px;border:1px solid #e2e8f0;"><strong>Remote and hybrid</strong></td><td style="padding:10px 12px;border:1px solid #e2e8f0;">Document processing, release of information and coding support, usually after experience</td></tr>
    </tbody>
</table>

<h2>Where to Apply</h2>

<ol>
    <li><strong>AHIMA's own job board.</strong> <a href="https://careerassist.ahima.org/" target="_blank" rel="noopener">AHIMA CareerAssist</a> is run by the profession's own association and is the one board on this page written for this job rather than for everything. AHIMA links to it from its careers pages.</li>
    <li><strong>Federal health agencies.</strong> The Veterans Health Administration, the Indian Health Service and military treatment facilities hire records staff through <a href="https://www.usajobs.gov/Search/Results?k=medical%20records" target="_blank" rel="noopener">USAJOBS</a>, the federal government's own site, run by the Office of Personnel Management. It is free, and federal roles carry federal benefits and a published pay grade. The VA also runs its own hiring site at <a href="https://vacareers.va.gov/" target="_blank" rel="noopener">vacareers.va.gov</a>.</li>
    <li><strong>Health system careers pages.</strong> Large systems post everything on their own sites and hire continuously &mdash; for example <a href="https://www.kaiserpermanentejobs.org/" target="_blank" rel="noopener">Kaiser Permanente</a> and <a href="https://jobs.sutterhealth.org/" target="_blank" rel="noopener">Sutter Health</a>. Set up the alert there rather than anywhere else.</li>
    <li><strong>Your local hospital, directly.</strong> Records departments recruit quietly and often internally. A registration or front-desk job in the same building is a common way in.</li>
</ol>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Work out which of the two jobs the advert is for.</strong> If the duties stop at retrieving, copying and filing, it is the $43,600 version. If they include abstracting or coding, it is the $51,140 one.</li>
    <li><strong>Learn the vocabulary before you apply.</strong> Medical terminology and anatomy are what separate a general office application from a credible one, and they are the cheapest thing on this list to acquire.</li>
    <li><strong>Put the systems in the first three lines of the resume.</strong> Epic, Cerner, ICD-10-CM, CPT, HIPAA, release of information, chart deficiency. These are the words the screening filter is looking for.</li>
    <li><strong>Book the CCA if you have no certificate.</strong> A high school diploma is the only requirement, it is $199 as a member, and it converts "no healthcare experience" into a credential on the page.</li>
    <li><strong>Apply through the employer</strong>, not through a middleman, and say in the opening line which EHR you have used and whether you hold or are sitting an AHIMA credential.</li>
    <li><strong>Expect a privacy check.</strong> Offers usually come with background screening and mandatory HIPAA and compliance training before you touch a live record.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much do medical records clerks make in the USA?</h3>
<p>The national median for medical records specialists is $51,140 a year, or $24.59 an hour, as of May 2025 according to the Bureau of Labor Statistics. The lowest tenth earned under $37,000 and the highest tenth over $81,150. Hospitals pay a $59,120 median against $47,120 in physician offices.</p>

<h3>Can I become a medical records clerk with no experience?</h3>
<p>Yes. BLS records no required work experience and no on-the-job training period for the occupation. What it does record is a postsecondary nondegree award as the typical entry-level education, so the gap to close is a certificate rather than experience &mdash; and AHIMA's CCA exam asks only for a high school diploma.</p>

<h3>Do I need a degree or certification to work in medical records?</h3>
<p>Not a degree. AHIMA's Certified Coding Associate requires only a high school diploma and costs $199 for members. The RHIT, which hospital records departments more often ask for, requires an associate degree from a CAHIIM-accredited Health Information Management program.</p>

<h3>What is the difference between a medical records clerk and a medical coder?</h3>
<p>A clerk files, retrieves and processes records and fulfils release-of-information requests. A coder abstracts clinical detail from the chart and assigns ICD and CPT codes for billing and reporting. BLS counts the filing-only role as file clerks, median $43,600, and the coding role within medical records specialists, median $51,140.</p>

<h3>Are medical records clerk jobs in demand in 2026?</h3>
<p>The records specialist occupation is. BLS projects 8 per cent growth from 2025 to 2035, which it describes as much faster than the average for all occupations, with about 14,000 openings a year. File clerks, by contrast, are projected to fall 15.8 per cent over the same decade.</p>

<h3>How much does the AHIMA CCA exam cost and what is on it?</h3>
<p>$199 for AHIMA members and $299 for non-members. It is 105 questions &mdash; 90 scored and 15 pretest &mdash; with two hours to complete it. AHIMA recommends, but does not require, six months of coding experience or a coding training program first.</p>

<h3>What qualifications do I need for an RHIT?</h3>
<p>An associate degree from a Health Information Management program accredited by CAHIIM, or graduation from a foreign program under one of AHIMA's reciprocity agreements. The exam is 150 questions in three and a half hours at a Pearson VUE center, $229 for members and $299 for non-members.</p>

<h3>Where can I apply for medical records jobs without using a job aggregator?</h3>
<p>AHIMA runs its own board, CareerAssist, for this profession. Federal records roles at the VA, the Indian Health Service and military facilities are advertised on USAJOBS. Large health systems such as Kaiser Permanente and Sutter Health post everything on their own careers sites.</p>

<h2>People Also Search For</h2>

<h3>Medical records clerk salary</h3>
<p>A $51,140 national median as of May 2025, ranging from under $37,000 to over $81,150 depending on setting and credentials.</p>

<h3>AHIMA CCA certification requirements</h3>
<p>A high school diploma, and nothing else. Coding experience and training are recommended rather than required.</p>

<h3>RHIT certification</h3>
<p>Registered Health Information Technician, requiring an associate degree from a CAHIIM-accredited HIM program.</p>

<h3>Health information technician jobs</h3>
<p>A separate and better-paid BLS occupation at a $68,020 median, projected to grow 15.9 per cent by 2035.</p>

<h3>Entry level medical records jobs</h3>
<p>Realistic on a high school diploma, most often in physician offices and nursing facilities rather than hospitals.</p>

<h3>Medical records clerk job description</h3>
<p>Retrieval and filing, release of information, chart deficiency review and electronic health record maintenance.</p>

<h3>Remote medical records jobs</h3>
<p>Document processing, release of information and coding support, usually offered after on-site experience.</p>

<h3>Medical records salary by state</h3>
<p>Published by BLS on the State &amp; Area Data tab of its medical records specialists page.</p>

<h2>More Job Guides</h2>

<p>Comparing health care and office roles you can enter without a degree? These cover them:</p>

<ul>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the clinic role that mixes admin with clinical duties, and what it pays.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the same office skills outside health care, and where they lead.</li>
    <li><a href="/blog/entry-level-healthcare-jobs">Entry Level Healthcare Jobs</a> &mdash; the roles you can start without a degree, and which ones lead somewhere.</li>
    <li><a href="/blog/dental-assistant-jobs-in-usa">Dental Assistant Jobs in USA</a> &mdash; another certificate-entry health care job, decided state by state.</li>
    <li><a href="/blog/medical-receptionist-jobs-in-the-uk">Medical Receptionist Jobs in the UK</a> &mdash; the front-desk equivalent, priced by NHS pay bands.</li>
    <li><a href="/blog/clinic-receptionist-jobs-in-usa">Clinic Receptionist Jobs in USA</a> &mdash; the same clinic's front desk, where the median is $13,000 lower.</li>
    <li><a href="/blog/medical-billing-assistant-jobs-in-pakistan">Medical Billing Assistant Jobs in Pakistan</a> &mdash; the same claims and coding work done offshore for US providers.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or careers advice. Wages, job outlook figures and certification requirements are published by the Bureau of Labor Statistics and by AHIMA, and they change. Confirm the current position with BLS, AHIMA and the employer before enrolling in a program, booking an exam or accepting an offer.</p>
HTML;
    }
}
