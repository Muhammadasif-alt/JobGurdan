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
 * "School Administrator Jobs in UK" — a guide for office and admin workers who
 * want to run a school office. The Office Assistant Jobs in UK guide covers
 * general office work, so this one stays on schools: the titles, the gap
 * between FTE and actual pay on term-time contracts, the routes in, the
 * safeguarding checks and where schools advertise.
 *
 * Corrections and clarifications to the draft (checked against the National
 * Careers Service school secretary and school business manager profiles,
 * GOV.UK Teaching Vacancies, GOV.UK Find a job, Skills England apprenticeship
 * standards, Keeping children safe in education 2026 and the Skilled Worker
 * eligible occupations table, September 2026):
 *
 * 1. The draft quotes a Bristol School Administrator listing at an actual
 *    salary of about £17,506 to £17,785. No such listing was on Teaching
 *    Vacancies, so the guide uses three live listings instead.
 *
 * 2. The draft labels jobs.service.gov.uk "GOV.UK Work for government" with
 *    "current School Administrator vacancies". It is GOV.UK Find a job, for
 *    jobs in England, Scotland and Wales. Teaching Vacancies covers England
 *    only, which the draft does not say despite its UK title.
 *
 * 3. The draft tells international applicants to check each listing for
 *    sponsorship. School secretaries (SOC 4213) are ineligible for the Skilled
 *    Worker visa, and the admin listings on Teaching Vacancies say visas cannot
 *    be sponsored.
 *
 * 4. The draft leaves out the hours (36 to 38 a week), the T Level entry
 *    requirements, the apprenticeship durations, the school business manager
 *    pay range and the enhanced DBS check with barred list information that
 *    Keeping children safe in education sets for regulated activity.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SchoolAdministratorJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://teaching-vacancies.service.gov.uk/jobs?keyword=school+administrator';

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
        $title = 'School Administrator Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'School administrators run the school office: records, attendance, admissions and parents. The National Careers Service lists £22,000 to £28,000 for school secretaries, no degree is needed, and jobs in England are on GOV.UK Teaching Vacancies.',
                'content' => $content,
                'featured_image' => 'blogs/school-administrator-jobs-in-uk.jpg',
                'tags' => 'school administrator jobs uk, school secretary jobs, school office jobs, school administrator salary uk, school business manager, teaching vacancies admin jobs, school business professional apprenticeship, term time admin jobs',
                'meta_title' => 'School Administrator Jobs in UK: Pay and How to Apply',
                'meta_description' => 'School administrator jobs in the UK: duties, pay of GBP 22,000 to 28,000, term-time and FTE salaries, routes in, DBS checks and where schools advertise.',
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
            ['name' => 'UK Schools & Academy Trusts Hiring Administrators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-school-administrator-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'England, Scotland, Wales and Northern Ireland', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'School Administrator — School Secretary, Office and Admissions Roles, UK Schools and Academy Trusts',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time and term-time only',
                'language' => 'English',
                // Schools advertise FTE and pro rata pay that differ by hours
                // and weeks worked, so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'School administrator and school secretary roles in UK schools and academy trusts: records, attendance, admissions, reception and finance admin.',
                'seo_keywords' => 'school administrator jobs uk, school secretary jobs, school office administrator, school admissions administrator, school business manager jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK schools and academy trusts hire school administrators and school secretaries to run the school office: pupil records, attendance, admissions, reception, parent enquiries, ordering and invoices.</p>

<h3>Common requirements</h3>
<ul>
    <li>A good standard of general education and office experience</li>
    <li>Confidence with common office software, including spreadsheets</li>
    <li>An enhanced DBS check before you start</li>
    <li>The right to work in the UK, because school secretary roles cannot be sponsored on the Skilled Worker visa</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, hours and hiring decisions are set by each school, academy trust and local authority &mdash; not by JobGader. Many jobs are term-time only, so check the actual salary as well as the FTE salary.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>School administrators run the non-teaching side of a school: pupil records, attendance, admissions, reception, parent enquiries, ordering and invoices.</strong> Schools advertise the work as school administrator, school secretary, office manager or admissions and attendance administrator. The National Careers Service lists pay of <strong>£22,000 to £28,000</strong> for school secretaries, but many jobs are term-time or part-time, so the salary you are actually paid is often lower than the full-time equivalent (FTE). You do not need a degree, and most jobs in English state schools are listed on <strong>GOV.UK Teaching Vacancies</strong>.</p>

<p>This guide covers the duties, real pay examples, the routes in, the checks you need and where to apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://teaching-vacancies.service.gov.uk/jobs?keyword=school+administrator" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127979; Search School Administrator Jobs on Teaching Vacancies &rarr;
    </a>
</div>

<h2>What Does a School Administrator Do?</h2>

<p>According to the National Careers Service, a school secretary's day-to-day tasks can include:</p>

<ul>
    <li>acting as <strong>"the main contact for parents, governors and pupils"</strong>;</li>
    <li>answering queries face to face, by email and by phone, and greeting visitors;</li>
    <li>keeping records up to date;</li>
    <li>using IT systems to run financial reports and other data analysis;</li>
    <li>ordering resources, paying invoices and dealing with banking;</li>
    <li>managing the school meal payment system.</li>
</ul>

<p>Real listings add attendance records, pupil databases, admissions support, letters to parents and papers for meetings. The mix depends on the size of the school: in a small primary one secretary may do everything, while a large secondary school or academy trust splits the work into reception, attendance, admissions, data and finance roles.</p>

<h2>Job Titles to Search For</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Job title</th>
            <th style="padding:10px;text-align:left;">Typical focus</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School Administrator</strong></td><td style="padding:10px;">General school office work, records and reception</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School Secretary</strong></td><td style="padding:10px;">Office, reception and first contact for parents</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Office Manager</strong></td><td style="padding:10px;">Running the office and supervising admin staff</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Attendance Officer</strong></td><td style="padding:10px;">Attendance records and follow-up with families</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Admissions Administrator</strong></td><td style="padding:10px;">Pupil admissions and new starters</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Data Administrator</strong></td><td style="padding:10px;">The pupil information system and reports</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School Business Manager</strong></td><td style="padding:10px;">Finance, HR, premises and operations</td></tr>
    </tbody>
</table>
</div>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/school-administrator-jobs-in-uk-school-office.jpg" alt="A school administrator taking a phone call and writing notes at her desk in a UK school office" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">The school office is the first contact for parents, visitors and pupils.</figcaption>
</figure>

<h2>How Much Do School Administrators Earn?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Starter to experienced</th>
            <th style="padding:10px;text-align:left;">Typical hours</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School secretary</strong></td><td style="padding:10px;"><strong>£22,000 &ndash; £28,000</strong></td><td style="padding:10px;">36 to 38 a week, variable; term-time possible</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>School business manager</strong></td><td style="padding:10px;"><strong>£26,000 &ndash; £52,000</strong></td><td style="padding:10px;">36 to 40 a week</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">National Careers Service job profiles, September 2026.</p>
</div>

<h3>FTE salary vs actual salary</h3>

<p>The National Careers Service warns that <strong>"Advertised pay rates are often listed as full-time equivalent (FTE) or pro rata. Actual pay will depend on the number of hours you work over a year."</strong> A term-time or part-time job can show a £25,000 FTE salary but pay much less. These School Administrator listings were live on Teaching Vacancies on 17 September 2026:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">School</th>
            <th style="padding:10px;text-align:left;">FTE salary</th>
            <th style="padding:10px;text-align:left;">Actual salary</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Darite Primary Academy, Liskeard (part time)</td><td style="padding:10px;">£25,721</td><td style="padding:10px;"><strong>£11,651.76</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Chiltern Way Academy Trust, Aylesbury</td><td style="padding:10px;">£29,114 &ndash; £31,230</td><td style="padding:10px;"><strong>£24,690.91 &ndash; £26,485.44</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nishkam School West London, Osterley (full time)</td><td style="padding:10px;">£28,195 &ndash; £29,852</td><td style="padding:10px;">Full time, so no reduction</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">GOV.UK Teaching Vacancies, 17 September 2026. Listings close and change; check the live advert.</p>
</div>

<p>Many schools pay support staff on the local government (NJC) pay spine, which started at <strong>£24,796 a year in 2025/26</strong>, and then pro rata the salary for the weeks and hours you work.</p>

<h2>Full-Time, Part-Time and Term-Time Jobs</h2>

<p>All three are common. The National Careers Service says school secretaries <strong>"may be employed term-time only. A working week can vary from a few hours up to full time."</strong> Teaching Vacancies lets you filter by full time, part time and job share, and also lists term-time and flexible working jobs. Before applying, check:</p>

<ul>
    <li>the weekly hours and how many weeks a year you are paid for;</li>
    <li>the FTE salary <strong>and</strong> the actual salary;</li>
    <li>whether the contract is permanent or fixed term;</li>
    <li>holiday arrangements;</li>
    <li>the closing date.</li>
</ul>

<h2>What Qualifications Do You Need?</h2>

<p>You do not need a degree. The National Careers Service lists four routes into school secretary work: a college course, an apprenticeship, working towards the role and applying directly.</p>

<h3>Applying directly</h3>
<p>This is how most people with office experience get in. <strong>"Employers would expect you to have a good standard of general education and experience of office work."</strong> You should also be able to use common office software, including spreadsheets, and knowledge of accounts packages helps.</p>

<h3>College courses</h3>
<p>Courses in support work in schools or business administration are relevant, as is the <strong>T Level in Management and Administration</strong>. A T Level usually needs <strong>4 or 5 GCSEs at grades 9 to 4 (A* to C)</strong>, or equivalent, including English and maths.</p>

<h3>Apprenticeships</h3>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Apprenticeship</th>
            <th style="padding:10px;text-align:left;">Level</th>
            <th style="padding:10px;text-align:left;">Typical duration</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Administration assistant (ST1472)</td><td style="padding:10px;">Level 2</td><td style="padding:10px;">12 months</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Business administrator (ST0070)</td><td style="padding:10px;">Level 3</td><td style="padding:10px;">18 months</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">School business professional (ST0575)</td><td style="padding:10px;">Level 4</td><td style="padding:10px;">18 months</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Skills England apprenticeship standards, September 2026.</p>
</div>

<h3>Working towards the role</h3>
<p>The National Careers Service says you could <strong>start as an admin assistant in a larger school or multi-academy trust</strong> and work your way into a secretary post with more responsibility.</p>

<h2>Skills Schools Look For</h2>

<p>The National Careers Service lists these skills for school secretaries:</p>

<ul>
    <li>administration skills;</li>
    <li>being thorough and paying attention to detail;</li>
    <li>working well with others and on your own;</li>
    <li>sensitivity and understanding;</li>
    <li>flexibility and openness to change;</li>
    <li>excellent verbal communication and customer service skills;</li>
    <li>using a computer and the main software packages confidently.</li>
</ul>

<p>Confidentiality matters too, because you handle pupil records, safeguarding information and family details every day.</p>

<h2>DBS and Safeguarding Checks</h2>

<p>The National Careers Service says you will need to <strong>pass enhanced background checks</strong>. Schools in England follow <strong>Keeping children safe in education 2026</strong>, which tells them to obtain <strong>"an enhanced DBS check (including children's barred list information, for those who will be engaging in regulated activity with children)"</strong>. Expect references to be checked before you start, and safeguarding training once you join.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/school-administrator-jobs-in-uk-front-office.jpg" alt="A smiling school secretary on the phone at the front office desk beside files labelled students, staff and school records" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">School office staff handle confidential pupil and family records, so enhanced checks are standard.</figcaption>
</figure>

<h2>Where to Find School Administrator Jobs</h2>

<h3>1. GOV.UK Teaching Vacancies (England)</h3>
<p><a href="https://teaching-vacancies.service.gov.uk/" target="_blank" rel="noopener">Teaching Vacancies</a> is the Department for Education's official job site for schools and colleges in <strong>England only</strong>. Its <strong>"Administration, HR, data and finance"</strong> category had 569 jobs on 17 September 2026. You can filter by academies (including free schools) or local authority maintained schools, by working pattern, and by whether the job offers visa sponsorship.</p>

<h3>2. GOV.UK Find a job</h3>
<p><a href="https://www.gov.uk/find-a-job" target="_blank" rel="noopener">GOV.UK Find a job</a> lists full-time and part-time jobs in England, Scotland and Wales, and now runs on jobs.service.gov.uk. Search for "school administrator" and "school office".</p>

<h3>3. Council job sites</h3>
<p>Local authority maintained schools often advertise on the council's own careers site as well. Outside England, where Teaching Vacancies does not apply, council and school websites are the main place to look.</p>

<h3>4. Academy trusts</h3>
<p>Multi-academy trusts hire administrators for several schools at once, and some roles sit in the trust's central team. Check each trust's careers page, and search for trust administrator and finance administrator as well.</p>

<h3>5. Independent schools</h3>
<p>Independent schools hire admissions, reception, exams and finance staff through their own websites. Pay and holidays are set by each school, so read the job description carefully.</p>

<h2>Can International Applicants Get These Jobs?</h2>

<p>Only if they already have the right to work in the UK. On the Skilled Worker visa eligible occupations table, <strong>school secretaries (SOC code 4213) are "Ineligible"</strong>, so schools cannot sponsor you for this role, and the admin listings we checked on Teaching Vacancies say <strong>"Visas cannot be sponsored"</strong>. People who already hold another visa with work rights, such as a dependant or Graduate visa, can apply like anyone else. For other routes, see our <a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> guide.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Search several titles:</strong> school administrator, school secretary, school office administrator, admissions administrator and attendance officer.</li>
    <li><strong>Read the person specification</strong> and note the essential criteria, hours, weeks and actual salary.</li>
    <li><strong>Tailor your CV or application form</strong> to the school: show office, data entry, customer service and finance experience, and any work with children.</li>
    <li><strong>Write a supporting statement</strong> that answers each essential criterion with a real example. Many schools ask for an application form rather than a CV alone as part of safer recruitment.</li>
    <li><strong>Prepare for the interview.</strong> Expect questions on a busy reception, confidential information, an upset parent and competing deadlines, plus safeguarding questions.</li>
    <li><strong>Complete the checks:</strong> the enhanced DBS check, references and proof of your right to work.</li>
</ol>

<h2>Career Progression</h2>

<p>A typical path is <strong>administrative assistant &rarr; school administrator &rarr; office manager &rarr; school business manager</strong>. The National Careers Service says that with experience you could become <strong>a school business manager, or personal assistant (PA) to the headteacher</strong>. School business managers "oversee the efficient day-to-day running of a school and support the headteacher and leadership team", earning up to £52,000, and the Level 4 School business professional apprenticeship is one way to get there.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does a school administrator earn in the UK?</h3>
<p>The National Careers Service lists £22,000 to £28,000 for school secretaries. Many jobs are term-time or part-time, so the actual salary is often lower than the advertised FTE salary.</p>

<h3>Do I need a degree to be a school administrator?</h3>
<p>No. Employers expect a good standard of general education and office experience. College courses and apprenticeships at Levels 2 to 4 are other routes in.</p>

<h3>Are school administrator jobs term-time only?</h3>
<p>Some are. The National Careers Service says school secretaries may be employed term-time only, and working weeks range from a few hours to full time.</p>

<h3>What is the difference between FTE and actual salary?</h3>
<p>FTE is what the job would pay full time, all year. Actual salary is what you are paid for your real hours and weeks, for example £11,651.76 on a £25,721 FTE part-time listing.</p>

<h3>Do school administrators need a DBS check?</h3>
<p>Yes. The National Careers Service says you need to pass enhanced background checks, and Keeping children safe in education sets out enhanced DBS checks for school staff.</p>

<h3>Can a school sponsor a visa for a school administrator?</h3>
<p>No. School secretaries (SOC code 4213) are ineligible for the Skilled Worker visa, so you need existing UK work rights.</p>

<h3>Where are school administrator jobs advertised?</h3>
<p>In England, on GOV.UK Teaching Vacancies. Also check GOV.UK Find a job, council careers sites, academy trust websites and independent school websites.</p>

<h3>What is the difference between a school administrator and a school secretary?</h3>
<p>The roles overlap. A school secretary is usually the first contact for parents and visitors, while a school administrator title may carry more data, admissions, attendance or finance work.</p>

<h2>People Also Search For</h2>

<h3>School secretary salary UK</h3>
<p>£22,000 to £28,000 per the National Careers Service.</p>

<h3>School business manager salary</h3>
<p>£26,000 to £52,000 per the National Careers Service.</p>

<h3>Term time only admin jobs</h3>
<p>Common in schools; check the actual salary, not only the FTE.</p>

<h3>School admin jobs no experience</h3>
<p>Start as an admin assistant or take a Level 2 or 3 apprenticeship.</p>

<h3>School business professional apprenticeship</h3>
<p>Level 4, typically 18 months.</p>

<h3>Teaching Vacancies admin jobs</h3>
<p>The Administration, HR, data and finance category, England only.</p>

<h3>School receptionist jobs</h3>
<p>Often advertised as school secretary or school office administrator.</p>

<h3>School secretary visa sponsorship</h3>
<p>Not possible on the Skilled Worker visa; SOC 4213 is ineligible.</p>

<h2>More Job Guides</h2>

<p>Looking at other office or education work? These cover it:</p>

<ul>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; general office work outside schools.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; the teaching side of UK schools.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; work rights and sponsored routes.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; remote admin and support roles.</li>
    <li><a href="/blog/education-assistant-jobs-in-australia">Education Assistant Jobs in Australia</a> &mdash; school support work in Australia.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; teacher assistant and paraprofessional pay, the Title I rule, the ParaPro test and who is hiring.</li>
    <li><a href="/blog/medical-receptionist-jobs-in-the-uk">Medical Receptionist Jobs in the UK</a> &mdash; why GP practice pay is not NHS pay, and what care navigation added to the job.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Salaries, vacancies, apprenticeship standards and visa rules change. Check the live advert, the National Careers Service and GOV.UK before applying.</p>
HTML;
    }
}
