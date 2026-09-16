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
 * "Dental Assistant Jobs in Canada" — chairside and expanded-duty assistants
 * in general, orthodontic and pediatric dental clinics. It owns the Job Bank
 * wage and outlook data for NOC 33100, the provincial regulators, the NDAEB
 * exams, the Ontario rules on Level II duties and x-rays, and the immigration
 * position.
 *
 * Corrections to the draft:
 *
 * 1. It calls dental assisting one of the fastest-growing health care support
 *    careers. No official source says so. The federal COPS projection for
 *    2024-2033 is Balance: 18,300 job openings against 18,200 job seekers,
 *    with employment growing 2.6 per cent a year.
 *
 * 2. It names Ontario as a province with a regulatory college that requires
 *    registration. The RCDSO says dental assistants in Ontario are not
 *    regulated; registration is required in every province except Ontario and
 *    Quebec.
 *
 * 3. It says NDAEB recognises training programs. The Commission on Dental
 *    Accreditation of Canada accredits programs; NDAEB runs the Theory Exam
 *    and the Clinical Practice Evaluation, and from 1 January 2026 graduates
 *    of non-accredited programs must pass the evaluation first.
 *
 * 4. It says CPR certification is almost universally required. The College of
 *    Alberta Dental Assistants says CPR is not needed to renew a practice
 *    permit, only for sedation teams.
 *
 * 5. It puts entry pay at CAD 38,000 and experienced pay at CAD 45,000 to
 *    55,000. Job Bank's national wages are $21 low, $27 median and $35 high an
 *    hour, about $40,950, $52,650 and $68,250 a year at 37.5 hours.
 *
 * 6. It spells the software Cleardent. The Canadian product is ClearDent.
 *
 * 7. It says nothing about immigration. NOC 33100 is TEER 3, so it counts for
 *    the Federal Skilled Worker Program, but it is not on IRCC's Express Entry
 *    health care and social services category list.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DentalAssistantJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-dental-assistant-jobs.html';

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
        $title = 'Dental Assistant Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the median dental assistant wage at $27 an hour, registration is required in every province except Ontario and Quebec, the NDAEB exam order changed in 2026, and the job is not in the Express Entry health care category.',
                'content' => $content,
                'featured_image' => 'blogs/dental-assistant-jobs-in-canada.jpg',
                'tags' => 'dental assistant jobs canada, dental assistant salary canada, level 2 dental assistant ontario, ndaeb exam, certified dental assistant canada, cdac accredited dental assisting program, noc 33100, dental assistant jobs toronto, dental assistant jobs bc, dental assistant immigration canada',
                'meta_title' => 'Dental Assistant Jobs in Canada 2026: Pay and Licensing',
                'meta_description' => 'Dental assistant jobs in Canada: Job Bank wages by province, where registration is required, the NDAEB exams and fees, and why Express Entry is no shortcut.',
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
            ['name' => 'Canadian General, Orthodontic & Pediatric Dental Clinics (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-dental-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Dental Assistant — General, Orthodontic and Pediatric Dental Clinics, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time; many clinics open evenings and Saturdays',
                'language' => 'English',
                // Wages differ widely by province and by registration status,
                // so no single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Dental assistant roles in Canadian general, orthodontic and pediatric clinics. Chairside and expanded-duty positions.',
                'seo_keywords' => 'dental assistant jobs canada, level 2 dental assistant jobs, registered dental assistant alberta, dental assistant jobs toronto, dental assistant jobs vancouver',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>General dental practices, orthodontic and pediatric clinics, oral surgery offices and community dental programs across Canada hire dental assistants for full-time and part-time roles.</p>

<h3>What the work involves</h3>
<p>Preparing operatories and sterilizing instruments, assisting the dentist chairside, taking radiographs where authorized, patient education, charting in software such as ABELDent, ClearDent or Dentrix, and ordering supplies. Expanded duties such as coronal polishing, fluoride and sealants depend on the province and your certification.</p>

<h3>Requirements</h3>
<ul>
    <li>An accredited dental assisting program, usually 8 months to a year</li>
    <li>Registration with the provincial regulator in every province except Ontario and Quebec, which normally needs the NDAEB certificate</li>
    <li>In Ontario, the NDAEB certificate for Level II duties and approved x-ray training before operating a dental x-ray machine</li>
    <li>The right to work in Canada</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>National wages.</strong> Job Bank reports $21 low, $27 median and $35 high an hour for NOC 33100</li>
    <li><strong>By province.</strong> Medians are higher in Alberta ($32) and British Columbia ($31) than in Ontario and Quebec ($26)</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the registration rules of the province you want to work in</strong> before you enrol in a program or accept an offer.</p>

<p><strong>Note:</strong> pay, duties and immigration eligibility are set by employers, provincial regulators and IRCC &mdash; not by JobGader. Confirm the details with the employer and the regulator before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Dental assistants work beside dentists and hygienists in almost every Canadian town, from large Toronto and Vancouver group practices to single-dentist clinics. The training takes a year or less, and in most provinces the job is regulated. Before you enrol or apply, it helps to know what official data says about demand and pay, which provinces require registration, how the national exams changed in 2026, and what the job does and does not do for immigration.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-dental-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129463; Browse Dental Assistant Jobs in Canada &rarr;
    </a>
</div>

<h2>What the Official Data Says About Demand</h2>

<p>Guides call dental assisting one of the fastest-growing health care support careers. Official projections are more measured. Dental assistants are <strong>NOC 33100</strong> (dental assistants and dental laboratory assistants), a <strong>TEER 3</strong> occupation.</p>

<ul>
    <li><strong>National projection.</strong> The Canadian Occupational Projection System rates 2024 to 2033 as <strong>Balance</strong>: about <strong>18,300 job openings against 18,200 job seekers</strong>. Employment is projected to grow <strong>2.6 per cent a year</strong>, faster than the 1.2 per cent average, from about 38,000 workers in 2023.</li>
    <li><strong>Provincial outlook.</strong> Job Bank's outlook for 2025 to 2027 is <strong>Good in Ontario</strong>, <strong>Moderate in British Columbia and Alberta</strong>, and <strong>Limited in Quebec</strong>.</li>
    <li><strong>Public dental coverage.</strong> The Canadian Dental Care Plan opened to eligible adults aged 18 to 64 in May 2025, with coverage from 1 June 2025. By November 2025 close to 6 million people had enrolled and more than 27,000 oral health providers were participating.</li>
</ul>

<p>So demand is growing faster than average, but the national projection does not show a shortage. Your chances depend heavily on the province.</p>

<h2>What a Dental Assistant Does</h2>

<ul>
    <li>Preparing treatment rooms and sterilizing instruments</li>
    <li>Assisting the dentist chairside during procedures</li>
    <li>Taking and processing radiographs where you are trained and authorized</li>
    <li>Explaining oral hygiene and post-treatment care to patients</li>
    <li>Charting, scheduling and billing in dental software</li>
    <li>Ordering and maintaining supplies and equipment</li>
</ul>

<p>Expanded duties such as coronal polishing, topical fluoride, pit and fissure sealants and impressions depend on the province and your certification, as the sections below explain. The practice software named most often in Canadian job ads is <strong>ABELDent</strong>, <strong>ClearDent</strong> and <strong>Dentrix</strong>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/dental-assistant-jobs-in-canada-chairside.jpg"
         alt="A smiling dental assistant in navy scrubs and blue gloves holding a dental mirror while talking with a patient in the dental chair, with a panoramic x-ray on the screen behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Registration Is Required</h2>

<p>Guides list Ontario alongside British Columbia and Alberta as provinces with regulatory colleges. <strong>Ontario is the exception.</strong> The Royal College of Dental Surgeons of Ontario states that in Ontario, <strong>dental assistants are not regulated</strong> and are not authorized to perform any controlled acts. Registration is required in every province <strong>except Ontario and Quebec</strong>, and the territories do not regulate the role.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Province</th>
            <th style="padding:10px;text-align:left;">Who regulates dental assistants</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">BC College of Oral Health Professionals</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">College of Alberta Dental Assistants</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Saskatchewan</td><td style="padding:10px;">College of Dental Assistants of Saskatchewan</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Manitoba</td><td style="padding:10px;">Manitoba Dental Association</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">New Brunswick</td><td style="padding:10px;">New Brunswick Dental Society</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia</td><td style="padding:10px;">Nova Scotia Regulator of Dentistry and Dental Assisting</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Prince Edward Island</td><td style="padding:10px;">Prince Edward Island Dental College</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Newfoundland and Labrador</td><td style="padding:10px;">Newfoundland and Labrador Dental Board</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario and Quebec</td><td style="padding:10px;">Not regulated as a profession</td></tr>
    </tbody>
</table>
</div>

<h2>Level I and Level II in Ontario</h2>

<p>"Level I" and "Level II" are Ontario terms, not a national system. Under the RCDSO standard, a <strong>Level II dental assistant</strong> holds the NDAEB certificate. A dentist may direct a Level II assistant to perform listed intra-oral procedures, including <strong>mechanical coronal polishing, fluoride application, pit and fissure sealants and impressions</strong>. <strong>Level I assistants</strong>, without the certificate, must not perform them. Other provinces use their own titles and scopes, such as Registered Dental Assistant in Alberta.</p>

<p>Taking x-rays in Ontario has its own rule. Under the Healing Arts Radiation Protection Act, anyone operating a dental x-ray machine must complete an <strong>approved dental radiation safety course</strong> or an approved college dental assisting program.</p>

<h2>Training and the NDAEB Exams</h2>

<p>Guides say NDAEB recognizes training programs. It does not. <strong>The Commission on Dental Accreditation of Canada (CDAC) accredits dental assisting programs</strong>, which run for <strong>8 months to a year</strong>. The National Dental Assisting Examining Board sets the national certification exams, and every regulator except Quebec's requires its certificate.</p>

<ul>
    <li><strong>Two parts.</strong> The written part is now called the <strong>Theory Exam</strong>, and the <strong>Clinical Practice Evaluation</strong> still exists.</li>
    <li><strong>New order from 1 January 2026.</strong> Graduates of programs that are not CDAC-accredited must pass the Clinical Practice Evaluation before they can take the Theory Exam. Graduates of accredited programs normally take only the Theory Exam.</li>
    <li><strong>Fees.</strong> The Theory Exam costs <strong>$730</strong> ($100 application plus $630), and the Clinical Practice Evaluation <strong>$1,785</strong> ($100 application plus $1,685).</li>
    <li><strong>CPR.</strong> Guides say CPR is almost universally required. The College of Alberta Dental Assistants says you <strong>do not need CPR to renew your practice permit</strong> unless you work on a sedation team. Many schools and employers still ask for it.</li>
</ul>

<h2>Dental Assistant Salary in Canada</h2>

<p>Guides put entry-level pay at CAD 38,000 to 45,000 and experienced pay at CAD 45,000 to 55,000. Job Bank's wage data for NOC 33100 is higher. Annual figures below assume a 37.5-hour week:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Canada</td><td style="padding:10px;">$21 ($40,950)</td><td style="padding:10px;">$27 ($52,650)</td><td style="padding:10px;">$35 ($68,250)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$20 ($39,000)</td><td style="padding:10px;">$26 ($50,700)</td><td style="padding:10px;">$34 ($66,300)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">$24 ($46,800)</td><td style="padding:10px;">$31 ($60,450)</td><td style="padding:10px;">$36 ($70,200)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">$24 ($46,800)</td><td style="padding:10px;">$32 ($62,400)</td><td style="padding:10px;">$38 ($74,100)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">$21 ($40,950)</td><td style="padding:10px;">$26 ($50,700)</td><td style="padding:10px;">$30.16 ($58,812)</td></tr>
    </tbody>
</table>
</div>

<p>Hourly wages are Job Bank figures updated in November 2025. Minimum wages set the floor for a new or uncertified assistant: <strong>$17.60 an hour in Ontario</strong>, rising to $17.95 on 1 October 2026, <strong>$18.25 in British Columbia</strong> from 1 June 2026, <strong>$16.60 in Quebec</strong> from 1 May 2026, and <strong>$15 in Alberta</strong>. Registered and Level II assistants with expanded duties sit toward the high end.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/dental-assistant-jobs-in-canada-clinic.jpg"
         alt="A dental assistant in light blue scrubs holding a tray of dental instruments in a bright clinic, with the Toronto skyline, the CN Tower and a Canadian flag by the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Immigration: What the Job Does and Does Not Do</h2>

<ul>
    <li><strong>Federal Skilled Worker Program.</strong> NOC 33100 is TEER 3, so a year of full-time skilled experience in it can count toward the program's minimum requirements.</li>
    <li><strong>Express Entry health care category.</strong> Dental assistants are <strong>not on IRCC's list of occupations for the health care and social services category</strong>. The TEER 3 occupations listed are other health roles, so a category-based health care draw does not apply to this job.</li>
    <li><strong>BC Provincial Nominee Program.</strong> 33100 is a health care priority occupation, but only for assistants <strong>licensed in British Columbia</strong>.</li>
    <li><strong>Credentials.</strong> Overseas training does not transfer automatically. Graduates of non-accredited programs apply to NDAEB for a credential assessment and, from 2026, pass the Clinical Practice Evaluation first.</li>
</ul>

<h2>Where to Find Dental Assistant Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Search Indeed Canada and the federal Job Bank, and set alerts for Toronto, Vancouver, Calgary and Edmonton.</li>
    <li><strong>Clinic and dental group websites.</strong> Independent clinics and multi-location groups post openings directly.</li>
    <li><strong>Provincial associations and regulators.</strong> Several run job boards or list registrants employers can check.</li>
    <li><strong>Practicum placements.</strong> The clinical placement in an accredited program often leads to a first offer.</li>
</ol>

<h2>Tips for Landing a Dental Assistant Job</h2>

<ul>
    <li><strong>Register before you apply.</strong> Outside Ontario and Quebec, clinics cannot use you in the role without registration.</li>
    <li><strong>List your certifications clearly.</strong> NDAEB certificate, provincial registration number, and HARP-approved x-ray training in Ontario.</li>
    <li><strong>Name your software.</strong> ABELDent, ClearDent or Dentrix experience shortens training.</li>
    <li><strong>Prepare for practical questions.</strong> Expect to talk through infection control, a nervous child patient and an equipment problem mid-procedure.</li>
</ul>

<h2>Career Progression</h2>

<p>With experience, dental assistants move into expanded-duty or orthodontic assisting, office or treatment coordination, practice management, dental sales, or teaching in an assisting program. Becoming a <strong>dental hygienist</strong> requires a separate accredited hygiene program and registration as a hygienist.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do dental assistants earn in Canada?</h3>
<p>Job Bank reports a national median of $27 an hour for NOC 33100, about $52,650 a year at 37.5 hours, with medians of $31 in British Columbia and $32 in Alberta.</p>

<h3>Do dental assistants need to be registered in Canada?</h3>
<p>In every province except Ontario and Quebec. The RCDSO states that dental assistants in Ontario are not regulated.</p>

<h3>What is a Level II dental assistant?</h3>
<p>An Ontario term for an assistant who holds the NDAEB certificate. A dentist may direct a Level II assistant to perform listed procedures such as coronal polishing, fluoride and sealants.</p>

<h3>Does NDAEB accredit dental assisting programs?</h3>
<p>No. The Commission on Dental Accreditation of Canada accredits programs. NDAEB runs the Theory Exam and the Clinical Practice Evaluation.</p>

<h3>What changed in the NDAEB exams in 2026?</h3>
<p>From 1 January 2026, graduates of non-accredited programs must pass the Clinical Practice Evaluation before taking the Theory Exam.</p>

<h3>How long does dental assistant training take?</h3>
<p>Accredited dental assisting programs run for about 8 months to a year.</p>

<h3>Is CPR required to work as a dental assistant?</h3>
<p>Not always. The College of Alberta Dental Assistants says CPR is not needed to renew a practice permit unless you work on a sedation team, though many employers still ask for it.</p>

<h3>Can dental assistants immigrate through Express Entry?</h3>
<p>The job is TEER 3, so it can count for the Federal Skilled Worker Program, but dental assistants are not on the Express Entry health care and social services category list.</p>

<h2>People Also Search For</h2>

<h3>Level 2 dental assistant jobs Ontario</h3>
<p>Roles for NDAEB-certified assistants who can perform delegated intra-oral procedures.</p>

<h3>Dental assistant salary Alberta</h3>
<p>The highest provincial median on Job Bank, at $32 an hour.</p>

<h3>NDAEB Theory Exam fee</h3>
<p>$730 including the $100 application fee.</p>

<h3>CDAC accredited dental assisting programs</h3>
<p>Programs of 8 months to a year that lead straight to the Theory Exam.</p>

<h3>Registered dental assistant BC</h3>
<p>Licensed by the BC College of Oral Health Professionals.</p>

<h3>Dental assistant jobs Toronto</h3>
<p>Ontario's outlook is rated Good for 2025 to 2027, and assistants there are not regulated.</p>

<h3>HARP certificate dental assistant</h3>
<p>Ontario's required radiation safety training before operating a dental x-ray machine.</p>

<h3>Dental assistant NOC code</h3>
<p>NOC 33100, a TEER 3 occupation.</p>

<h2>More Job Guides</h2>

<p>Comparing health care support and Canadian jobs? These cover them:</p>

<ul>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the American clinic role that mixes admin with clinical duties.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; an entry-level health care job with NHS pay bands.</li>
    <li><a href="/blog/medical-receptionist-jobs-in-australia">Medical Receptionist Jobs in Australia</a> &mdash; front-desk work in health care, and the award that sets its pay.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how the LMIA works, and the limits on low-wage jobs.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; an entry-level Canadian job priced by the provincial minimum wage.</li>
    <li><a href="/blog/janitor-jobs-in-canada">Janitor Jobs in Canada</a> &mdash; hospital and building cleaning work across the provinces.</li>
    <li><a href="/blog/occupational-therapist-jobs-in-canada">Occupational Therapist Jobs in Canada</a> &mdash; the allied-health route, its provincial registration and pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Registration rules, scopes of practice, exam fees, wages and Express Entry categories change and differ between provinces. Confirm the current position with the provincial regulator, NDAEB, Job Bank and IRCC before enrolling, applying or accepting an offer.</p>
HTML;
    }
}
