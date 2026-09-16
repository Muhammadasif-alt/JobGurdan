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
 * "Occupational Therapist Jobs in Canada" — a guide for a therapist, at home or
 * abroad, weighing where to work, what registration really involves and how
 * long it takes. Registration and the internationally-educated route are the
 * questions that decide whether the pay is even reachable, so those get the
 * space, and the sector guides own the wider Canada job market.
 *
 * Corrections and clarifications to the draft (checked against Job Bank NOC
 * 31203, CAOT, ACOTRO and IRCC on canada.ca, September 2026):
 *
 * 1. The draft gives median pay as a "$85,000-$95,000" range. Job Bank reports
 *    a single national median of $46.00 an hour for occupational therapists,
 *    with a low of $36.17 and a high of $55.00, updated 19 November 2025. At a
 *    37.5-hour week the median is about $90,000 a year. The band is defensible,
 *    but the guide anchors it to the measured figure so a reader can check it.
 *
 * 2. The draft's top-end bands run to "$130,000" for community work and
 *    "$140,000+" for private practice and "$145,000+" for northern contracts.
 *    Job Bank's national high wage is $55.00 an hour, about $107,000-$114,000
 *    full-time. Salaried grid roles do not reach $130,000; those figures are
 *    exceptional fee-for-service, insurer-funded or northern-premium outliers,
 *    not standard ranges, and the guide labels them that way.
 *
 * 3. The draft describes Express Entry without the 2026 change. Since 18
 *    February 2026 IRCC requires one year of qualifying work experience, not
 *    six months, for a category-based selection draw. It also overstates the
 *    Atlantic route: New Brunswick paused the Atlantic Immigration Program for
 *    the rest of 2025 and OT is not always named, so the guide softens it to
 *    "some Atlantic provinces through an employer-endorsed job offer".
 *
 * 4. The draft names SEAS but not its language step or the current framework.
 *    ACOTRO's Substantial Equivalency Assessment System also includes a
 *    language readiness assessment, and the NOTCE is measured against the
 *    Competencies for Occupational Therapists in Canada (2021/2024) from 30
 *    September 2026. The order that saves the most time is SEAS first, then the
 *    provincial regulator, then the exam.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class OccupationalTherapistJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-occupational-therapist-jobs.html';

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
        $title = 'Occupational Therapist Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the occupational therapist median near $46 an hour, about $90,000 a year, not the $130,000+ some guides quote; registration is provincial through the NOTCE, and internationally trained OTs start with the ACOTRO SEAS assessment.',
                'content' => $content,
                'featured_image' => 'blogs/occupational-therapist-jobs-in-canada.jpg',
                'tags' => 'occupational therapist jobs in canada, occupational therapy canada, ot jobs canada, notce exam, acotro seas, internationally educated occupational therapist, ot salary canada, occupational therapist registration, express entry occupational therapist',
                'meta_title' => 'Occupational Therapist Jobs in Canada 2026: Pay & Licence',
                'meta_description' => 'Occupational therapist jobs in Canada in 2026: Job Bank pay by setting, provincial registration and the NOTCE, the SEAS route for internationally trained OTs.',
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
            ['name' => 'Canadian Healthcare Employers Hiring Occupational Therapists (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-occupational-therapist-aggregated']
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
                'position' => 'Occupational Therapist — Hospital, Community, School and Private Practice Roles, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Typically 37.5 hours a week; community and contract roles vary',
                'language' => 'English, French',
                // Grid, community and insurer-funded pay diverge sharply, and none
                // of it is reachable before provincial registration, so no figure
                // is quoted on the listing itself.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Occupational therapist roles with Canadian hospitals, health authorities, school boards and community providers. Provincial registration and the NOTCE come before any offer.',
                'seo_keywords' => 'occupational therapist jobs in canada, ot jobs canada, notce, acotro seas, occupational therapist registration canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Occupational therapy is a regulated profession in every Canadian province, so a job offer is only half the story: you cannot practise until the provincial regulator has registered you. Hospitals, health authorities, school boards, long-term care and community providers all hire, and the pay and security differ sharply between them.</p>

<h3>How it works</h3>
<p>You register with the college in the province where you will practise, and passing the National Occupational Therapy Certification Examination (NOTCE), administered by the Canadian Association of Occupational Therapists (CAOT), is a requirement everywhere except Quebec, which runs its own process.</p>

<h3>Requirements</h3>
<ul>
    <li>An accredited occupational therapy degree, or a substantial equivalency assessment if you trained abroad</li>
    <li>Registration with the provincial regulatory college and professional liability insurance</li>
    <li>A passing NOTCE result (outside Quebec); most provinces allow provisional registration while you wait for a sitting</li>
    <li>English or French proficiency, and often a valid driver's licence for community roles</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank median.</strong> $46.00 an hour nationally, about $90,000 a year at a 37.5-hour week, with a low of $36.17 and a high of $55.00 (updated 19 November 2025)</li>
    <li><strong>Reading the high figures.</strong> Salaried grid roles do not reach $130,000; the highest numbers are fee-for-service, insurer-funded or northern-premium work, not standard pay</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> registration standards, exam eligibility and immigration rules are set by the provincial colleges, CAOT, ACOTRO and IRCC &mdash; not by JobGader. Confirm the current position with your provincial regulator and on canada.ca before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canada has a real and uneven shortage of occupational therapists. A permanent hospital posting in Toronto or Vancouver draws dozens of applicants, while a community role in northern Ontario or rural Saskatchewan can sit open for months. For a therapist deciding where to aim, the useful questions are not whether jobs exist &mdash; they do &mdash; but which setting suits you, what registration actually involves, and how long it takes if you trained abroad. This guide answers all three, with pay anchored to Job Bank rather than to the bands most articles copy.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-occupational-therapist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127464;&#127462; Browse Occupational Therapist Jobs in Canada &rarr;
    </a>
</div>

<h2>What Occupational Therapists Earn in Canada</h2>

<p>Most guides give a single tidy salary, or a range with no source. Job Bank measures it. For occupational therapists (NOC 31203), the national wage is:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job Bank wage (NOC 31203)</th>
            <th style="padding:10px;text-align:left;">Hourly</th>
            <th style="padding:10px;text-align:left;">Approx. annual, 37.5h (CAD)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Low</td><td style="padding:10px;">$36.17</td><td style="padding:10px;">$70,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$46.00</strong></td><td style="padding:10px;"><strong>$89,700</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">High</td><td style="padding:10px;">$55.00</td><td style="padding:10px;">$107,300</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Job Bank national wages for NOC 31203, reference period 2023&ndash;2024, updated 19 November 2025.</p>
</div>

<p>The single median of <strong>$46.00 an hour, about $90,000 a year</strong>, conceals two different worlds. Public hospital and health-authority jobs are paid on union grids: predictable steps, a strong pension, generous leave, and a ceiling. Private and community work can pay more at the top, but it is often tied to billable visits and carries less security.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Setting</th>
            <th style="padding:10px;text-align:left;">Typical annual (CAD)</th>
            <th style="padding:10px;text-align:left;">Notes</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hospital / health authority (grid)</td><td style="padding:10px;">$72,000 &ndash; $100,000</td><td style="padding:10px;">Pension, steps, security</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">School boards, children's services</td><td style="padding:10px;">$70,000 &ndash; $95,000</td><td style="padding:10px;">Term-time patterns</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Long-term care and rehab</td><td style="padding:10px;">$75,000 &ndash; $98,000</td><td style="padding:10px;">Steady caseloads</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Community and home care</td><td style="padding:10px;">$85,000 &ndash; $107,000</td><td style="padding:10px;">Driving; report-heavy</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Private practice / insurer assessments</td><td style="padding:10px;">Fee-for-service, can exceed the grid</td><td style="padding:10px;">Outlier, not standard</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Northern and remote contracts</td><td style="padding:10px;">High end plus allowances</td><td style="padding:10px;">Housing, travel, premium</td></tr>
    </tbody>
</table>
</div>

<p><strong>One caution on the biggest numbers.</strong> You will see "$130,000" and "$145,000+" quoted for community and northern work. Job Bank's national high wage is $55.00 an hour, about $107,000&ndash;$114,000 full-time. Salaried grid roles do not reach $130,000. Those figures are exceptional fee-for-service, insurer-funded or northern-premium arrangements &mdash; real for some, but not a base salary you should plan around.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/occupational-therapist-jobs-in-canada-therapy.jpg"
         alt="An occupational therapist in navy scrubs guiding an older woman through a coloured-block fine motor exercise at a table, the CN Tower and Toronto skyline through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Demand Concentrates</h2>

<ul>
    <li><strong>Ontario.</strong> The largest market by volume, strongest in community rehab, insurer-funded assessment work and paediatrics.</li>
    <li><strong>British Columbia.</strong> High demand across health authorities, but high housing costs in the Lower Mainland; Vancouver Island and the interior are easier to enter.</li>
    <li><strong>Alberta.</strong> Strong hospital and continuing-care hiring, competitive grid salaries, a lower cost of living than Toronto or Vancouver.</li>
    <li><strong>Atlantic provinces.</strong> Smaller markets but chronic vacancies and active recruitment incentives.</li>
    <li><strong>Northern and rural regions.</strong> The shortest hiring timelines and the largest financial incentives &mdash; a northern living allowance, subsidised housing and paid travel home are common.</li>
</ul>

<h2>Registration: Provincial, Not National</h2>

<p>Occupational therapy is regulated in every province, and each has its own college &mdash; the College of Occupational Therapists of Ontario in Ontario, and the Ordre des ergoth&eacute;rapeutes du Qu&eacute;bec in Quebec. You register in the province where you will practise, and if you move provinces later, you register again.</p>

<p>The common thread nationally is the <strong>National Occupational Therapy Certification Examination (NOTCE)</strong>, administered by the Canadian Association of Occupational Therapists (CAOT). Passing it is a registration requirement in every province <strong>except Quebec</strong>, which runs its own process. The exam is case-based, and from <strong>30 September 2026</strong> it is measured against the Competencies for Occupational Therapists in Canada (2021/2024).</p>

<p>For a Canadian-educated graduate the path is short: finish an accredited master's programme with the required fieldwork, apply to your provincial regulator, pass the NOTCE, and carry liability insurance. Most provinces grant provisional registration so you can start working while you wait for a sitting, which matters because the exam runs only a few times a year.</p>

<h2>If You Were Educated Outside Canada</h2>

<p>Internationally educated therapists &mdash; including Canadians who trained abroad &mdash; take one extra step first, and most of the delays people report come from starting in the wrong place.</p>

<ol>
    <li><strong>SEAS.</strong> The Substantial Equivalency Assessment System, run by ACOTRO since 2015, decides how closely your education matches a Canadian-trained OT. It includes an academic credential review, a curriculum and fieldwork review, a jurisprudence knowledge test, a competency assessment and a language readiness assessment.</li>
    <li><strong>Provincial application.</strong> Once SEAS confirms substantial equivalency, you apply to the regulator in your chosen province.</li>
    <li><strong>Language proficiency.</strong> Most regulators accept IELTS, CELPIP or an equivalent, unless you were educated in English or French; Quebec requires French.</li>
    <li><strong>NOTCE.</strong> Your regulator confirms your eligibility to CAOT and you sit the exam, with provisional registration available in most provinces while you wait.</li>
    <li><strong>Immigration.</strong> Occupational therapy (NOC 31203, TEER 1) is eligible for Express Entry and appears in the Healthcare and Social Services category-based draws.</li>
</ol>

<p><strong>Start SEAS before you start job hunting.</strong> Employers can move quickly, but no one can hire you into a regulated role until the regulator has acted. Plan for twelve to eighteen months end to end and several thousand dollars in assessment, exam and immigration fees.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/occupational-therapist-jobs-in-canada-rehab.jpg"
         alt="An occupational therapist helping a smiling man use tongs on a wooden peg board during a hand rehabilitation session, a Canadian flag and the Toronto waterfront behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The 2026 Immigration Change to Know</h2>

<p>Occupational therapy sits in a favourable position for skilled immigration, but one rule tightened this year. <strong>Since 18 February 2026, IRCC requires one year of qualifying work experience</strong>, not six months, to be eligible for a category-based Express Entry draw. Several provinces also include OTs in their Provincial Nominee healthcare streams, though the lists change, so check the province you are targeting. The Atlantic route is narrower than guides suggest: some Atlantic provinces hire through an employer-endorsed job offer, but New Brunswick paused its Atlantic Immigration Program for the rest of 2025, and OT is not always named.</p>

<h2>Making Your Application Competitive</h2>

<ul>
    <li><strong>Write for the setting, not the profession.</strong> A hospital wants acute discharge planning and functional assessment; a community provider wants home safety assessments and assistive-device prescription; a paediatric employer wants sensory processing and school collaboration. Same degree, three different r&eacute;sum&eacute;s.</li>
    <li><strong>Say whether you drive.</strong> A large share of Canadian OT work is community-based, and postings routinely require a licence and a vehicle. If you have both, put them near the top; if not, filter for hospital, clinic and school roles.</li>
    <li><strong>Name your funders and documentation.</strong> Experience with auto-insurance, workers' compensation and long-term-disability files is directly valuable in private and community practice, because those reports follow a specific standard.</li>
    <li><strong>Ask about caseload size and travel radius.</strong> The two questions that predict your quality of life are how many active clients you carry and how far apart they are &mdash; ask how mileage and documentation time are paid, not just the salary.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What does an occupational therapist earn in Canada?</h3>
<p>Job Bank puts the national median at $46.00 an hour, about $90,000 a year at a 37.5-hour week, with a low of $36.17 and a high of $55.00 (updated 19 November 2025). Community and insurer-funded work can pay more, but grid salaries do not reach the $130,000 figures some guides quote.</p>

<h3>Do I need a licence to work as an occupational therapist in Canada?</h3>
<p>Yes. OT is regulated in every province, so you must register with the provincial college before you practise, and passing the NOTCE is a requirement everywhere except Quebec.</p>

<h3>Who administers the NOTCE, and where is it required?</h3>
<p>The Canadian Association of Occupational Therapists (CAOT) administers the National Occupational Therapy Certification Examination. It is required for registration in every province except Quebec, which runs its own process.</p>

<h3>I trained abroad. What is the first step?</h3>
<p>Start with SEAS, the Substantial Equivalency Assessment System run by ACOTRO. It reviews your credentials, curriculum, fieldwork, jurisprudence knowledge, competencies and language before you apply to a provincial regulator or sit the NOTCE.</p>

<h3>Can I work while I wait for the exam?</h3>
<p>Usually yes. Most provinces grant provisional or restricted registration so you can start working under conditions while you wait for a NOTCE sitting, which is offered only a few times a year.</p>

<h3>Is occupational therapy a good route for Express Entry?</h3>
<p>It is favourable. OT is NOC 31203, TEER 1, eligible for Express Entry and included in the Healthcare and Social Services category draws &mdash; but since 18 February 2026 you need one year of qualifying work experience, not six months, to qualify for those draws.</p>

<h3>Which provinces are easiest to get hired in?</h3>
<p>Northern, rural and Atlantic regions have the shortest timelines and the largest incentives. Ontario has the most roles by volume, and Alberta pairs strong hospital hiring with a lower cost of living than Toronto or Vancouver.</p>

<h3>Do I need a driver's licence?</h3>
<p>For community and home-care roles, usually yes &mdash; postings routinely require a valid licence and a vehicle. Hospital, clinic and school-based roles are the alternative if you do not drive.</p>

<h2>People Also Search For</h2>

<h3>NOTCE exam Canada</h3>
<p>The national certification exam administered by CAOT, required for OT registration in every province except Quebec, and case-based against the current competencies from 30 September 2026.</p>

<h3>ACOTRO SEAS assessment</h3>
<p>The Substantial Equivalency Assessment System, the first step for internationally educated occupational therapists before provincial registration.</p>

<h3>Occupational therapist salary Canada</h3>
<p>Job Bank puts the median at $46.00 an hour, about $90,000 a year, with a national high of $55.00.</p>

<h3>COTO registration Ontario</h3>
<p>Registration with the College of Occupational Therapists of Ontario, the provincial regulator for OTs practising in Ontario.</p>

<h3>Occupational therapist NOC code</h3>
<p>Occupational therapists are NOC 31203, TEER 1, which makes the occupation eligible for Express Entry.</p>

<h3>Internationally educated occupational therapist Canada</h3>
<p>A therapist trained abroad who registers through SEAS, a provincial college and the NOTCE, outside Quebec.</p>

<h3>Occupational therapy jobs Ontario</h3>
<p>The largest OT market by volume, strongest in community rehab, insurer-funded assessments and paediatrics.</p>

<h3>Express Entry healthcare category 2026</h3>
<p>Category-based draws for healthcare and social-services occupations, now requiring one year of qualifying experience since 18 February 2026.</p>

<h2>More Job Guides</h2>

<p>Weighing a move into Canadian healthcare or a work permit? These cover the neighbouring routes:</p>

<ul>
    <li><a href="/blog/dental-assistant-jobs-in-canada">Dental Assistant Jobs in Canada</a> &mdash; another regulated Canadian health role, and how its provincial certification works.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA, the language rules and the routes to permanent residence in full.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; what the 2026 levels plan changed and which sectors actually hire.</li>
    <li><a href="/blog/recruiter-jobs-in-canada">Recruiter Jobs in Canada</a> &mdash; the people on the other side of the healthcare hiring process.</li>
    <li><a href="/blog/physical-therapist-jobs-in-usa">Physical Therapist Jobs in the USA</a> &mdash; the neighbouring allied-health market, its licensing and pay.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; a care-sector route with a genuine visa pathway.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration or professional-registration advice. Registration requirements are set by each provincial college and change over time. Confirm the current position with your provincial regulator, and with ACOTRO and CAOT for SEAS and NOTCE, before applying.</p>
HTML;
    }
}
