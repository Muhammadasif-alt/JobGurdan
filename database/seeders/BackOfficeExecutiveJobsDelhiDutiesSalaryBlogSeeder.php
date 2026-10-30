<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Back Office Executive Jobs in Delhi: Duties and Salary"
 *
 * Checked on 26 October 2026 against the pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief's eight job-board search buttons and its job-board salary page (Rs 16,787 a month dated February 2024, plus three listed ranges) are dropped: no aggregator average or range is repeated, and the guide says no official Delhi average was found. The Delhi Labour Department site did not resolve, so no minimum wage figure is stated.
 * 2. Apex Sourcing & Info Services' careers page was fetched on 26 October 2026. The "Back Office Executive, 3 to 5 years" role in the brief is no longer on it; the page lists back office roles such as a US Payroll Specialist in Mohan Cooperative, New Delhi (3 to 6 years, any graduate, advanced Excel, QuickBooks, night shift, no cab). The guide describes what the page showed and links the careers page.
 * 3. Pocketful's Associate, Operations and Back Office advert was fetched on 26 October 2026: Delhi, full time, onsite, 1 to 4 years in stock-broking or financial services operations, NISM certification preferred, no salary ("Compensation will be aligned with market standards"), application form on the page. It is listed in EmployerVacancyListingsSeeder.
 * 4. The National Career Service statements come from the home page checked on 22 October 2026 for the Mumbai guide.
 * 5. The Rs 20,000 x 12 = Rs 2,40,000 line is kept as labelled arithmetic. Five FAQs rewritten and expanded to eight; eight People Also Search For entries added. The poster prints a salary range; the text does not repeat it.
 *
 * The portal listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class BackOfficeExecutiveJobsDelhiDutiesSalaryBlogSeeder extends Seeder
{
    public const SLUG = 'back-office-executive-jobs-delhi-duties-salary';

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
                'title' => 'Back Office Executive Jobs in Delhi: Duties and Salary',
                'excerpt' => 'What a back office executive in Delhi actually does, how to read a salary offer (gross, take-home or CTC), two employer adverts checked on 26 October 2026, the National Career Service portal and the skills freshers can show.',
                'content' => $content,
                'featured_image' => 'blogs/back-office-executive-jobs-delhi-duties-salary.jpg',
                'tags' => 'back office executive jobs delhi, back office executive salary delhi, back office duties and skills, back office jobs delhi freshers, office executive jobs delhi, national career service jobs, non voice back office delhi, data entry back office delhi',
                'meta_title' => 'Back Office Executive Jobs in Delhi: Duties and Salary',
                'meta_description' => 'Back office executive jobs in Delhi: daily duties, how to read a salary offer, two employer adverts checked in October 2026, the NCS portal and fresher skills.',
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
<p><strong>Back office executive jobs in Delhi cover data entry, document checking, reporting, email coordination, billing support and internal administration, and the duties depend on the industry.</strong> To judge an offer, compare the actual tasks, the required skills, the working hours, the office location and a written salary breakdown. We found no official average salary for the role in Delhi, so this guide gives you the questions to ask instead of a number to expect. It uses the National Career Service portal and two employer careers pages we checked on 26 October 2026, and it cannot promise a job, a wage or an interview.</p>

<h2>What a Back Office Executive Does</h2>

<p>Back office staff keep internal processes running. A working day can include updating records, checking documents, preparing spreadsheets, following up on missing information and supporting another department's workflow. The title does not always mean a job without calls or customer contact: some positions include answering enquiries, coordinating with suppliers or explaining account information. Read the full description to see the balance between administrative work, communication and specialist tasks.</p>

<h2>Start With the National Career Service</h2>

<p>The National Career Service (NCS) is the portal of the Ministry of Labour and Employment, Government of India. Its home page says it offers job search, job matching, career content, counselling and job fair information, and that it lists internships as well as jobs. It also says NCS does not charge any fee at any stage of registration, job application or interview processing, and that jobseekers should not pay fees to employers. Search it for back office, office executive and data entry roles in Delhi and read each posting's requirements. Official page: <a href="https://www.ncs.gov.in/" rel="noopener">National Career Service</a>.</p>

<h2>How to Read Salary Information</h2>

<p>Job-board salary pages show an average built from a few dozen self-reported figures, often dated a year or two back, and listed ranges belong to individual adverts with different duties. Neither is a market survey, so this guide repeats none of them. Experience, software skills, shift pattern and industry knowledge all change the offer, and the only figure that matters is the one in your written offer.</p>

<h2>Separate Monthly Salary From Total Package</h2>

<p>As arithmetic only, a stated monthly salary of Rs 20,000 is Rs 2,40,000 over twelve months before deductions or extra components. That does not tell you take-home pay or the employer's total annual cost. Ask for a breakdown showing fixed pay, variable incentives, benefits, probation terms and payment frequency, and ask whether the advertised amount is gross salary, take-home pay or cost to company. Compare offers on the same basis, and weigh travel costs and working days against the headline amount.</p>

<h2>What Employer Adverts in Delhi Look Like</h2>

<p>Apex Sourcing & Info Services, a Delhi analytics and KPO firm, lists its vacancies on its own careers page. When we checked on 26 October 2026 the page carried back office roles at its Mohan Cooperative office in New Delhi, including a US Payroll Specialist asking for 3 to 6 years of experience, any graduate, advanced Excel and QuickBooks, with duties such as collecting timesheet data and payroll information, entering data into payroll databases, maintaining payroll records and preparing reports, on a night shift with no cab. That is an experienced role, not a fresher opening, and it shows how much reporting a back office title can carry. Careers page: <a href="https://www.apexsinfo.com/careers/" rel="noopener">Apex Sourcing & Info Services careers</a>.</p>

<p>Pocketful, a SEBI-registered stock broker, advertised an Associate, Operations and Back Office role in Delhi on the same date: full time, onsite, asking for 1 to 4 years of experience in stock-broking operations, back office, DP operations or financial services operations, with client onboarding and KYC, trade settlements and reconciliation, depository operations and audit support among the duties. NISM certification preferred is listed, not required, and on pay the page says only "Compensation will be aligned with market standards". The application form is on the page. Careers page: <a href="https://www.pocketful.in/careers" rel="noopener">Pocketful careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/back-office-executive-jobs-delhi-duties-salary-desk.jpg"
         alt="A young woman working at a laptop in a Delhi office with India Gate behind her, under the words Back Office Executive Jobs in Delhi, Duties and Salary"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Recognise Specialised Industry Roles</h2>

<p>If you want a specialist position such as broking operations, assess the industry knowledge separately from general computer skills. Read whether credentials are mandatory or preferred, and do not enrol in unrelated training because one employer mentioned it. A general back office role in a different industry may ask for none of it.</p>

<h2>Build Practical Computer Skills</h2>

<p>For general office applications, practise word processing, email and spreadsheets using fictional records. Learn to enter information consistently, sort complete datasets, apply filters and check totals, and follow the specific software named in each advert. Prepare a short exercise showing how you organise a file and find missing or inconsistent information, and be ready to explain how you would verify an error before changing it. A clear checking process is worth more than claiming advanced software knowledge you cannot demonstrate in an assessment.</p>

<h2>Find Suitable Fresher Opportunities</h2>

<p>Freshers can search for office assistant, junior back office executive, trainee operations and data entry roles. Read the qualifications carefully, because a result labelled entry level may still require previous work or particular software. Include genuine examples from education, volunteering or other employment: maintaining records, organising an event, checking quantities and coordinating tasks all show relevant ability. Ask whether training is provided, who reviews your work and how performance is measured in the first months.</p>

<h2>Check Location, Shift and Workload</h2>

<p>Delhi and Delhi NCR searches cover very different commuting distances, so verify the actual office address rather than the city label. Check working days, shift times, breaks and whether overtime or weekend attendance is expected. If a role is described as non-voice, ask whether occasional calls are still required. For remote or hybrid work, confirm equipment and attendance rules. A plain conversation about the daily workload prevents surprises about sales targets, customer service or extra administrative duties.</p>

<h2>Prepare Your Resume and Interview Examples</h2>

<p>Write a concise resume showing your education, relevant responsibilities, software knowledge and availability, describing what you actually did rather than broad claims. Prepare examples of correcting an error, meeting a deadline, handling private information and asking for clarification. In an assessment, read the instructions before starting and prioritise accuracy. If a task uses an unfamiliar system, explain how you would learn its process and check your work.</p>

<h2>Verify the Employer and the Written Offer</h2>

<p>Find the employer's official website and confirm the vacancy through its published recruitment channel. Treat any request for money to secure an interview, release an appointment letter or guarantee placement as a warning sign; the National Career Service says jobseekers should not pay fees to employers. A logo or familiar company name does not verify a recruiter. Before accepting, get written duties, location, compensation, reporting arrangements and joining details, and check whether you are employed directly or through a staffing company.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can freshers apply for back office jobs in Delhi?</h3>
<p>Some roles accept freshers while others require experience. Both adverts we checked asked for experience, so compare the actual qualifications and duties rather than assuming every back office position suits beginners.</p>

<h3>Is a degree compulsory?</h3>
<p>Requirements vary by employer and industry. The Apex roles asked for any graduate; other adverts accept other qualifications. Check whether graduation is mandatory, preferred or replaced by something else.</p>

<h3>Does back office mean no customer contact?</h3>
<p>Not necessarily. Some positions involve email queries, supplier coordination or occasional calls. Ask about communication duties and any sales expectations at the interview.</p>

<h3>What salary should I expect in Delhi?</h3>
<p>We found no official average for the role, and job-board averages are self-reported and dated. Use current comparable adverts and your written offer, and compare offers on the same basis: gross, take-home or cost to company.</p>

<h3>What is the difference between gross pay and CTC?</h3>
<p>Gross pay is your salary before deductions; cost to company adds the employer's other costs such as provident fund contributions and benefits. Take-home pay is what reaches your account. Ask which one a figure means.</p>

<h3>Are these roles available from home?</h3>
<p>Some adverts allow remote or hybrid work, but both adverts we checked were onsite in Delhi. Confirm the arrangement for every vacancy.</p>

<h3>Do I need NISM certification?</h3>
<p>Only for some financial roles. Pocketful lists it as preferred, not required, for its broking operations role, and a general back office job in another industry will not ask for it.</p>

<h3>Is it safe to pay a consultant for a back office placement?</h3>
<p>No. The National Career Service charges no fee at any stage and says jobseekers should not pay employers. Apply through the employer's own careers page or the NCS portal.</p>

<h2>Bottom Line</h2>

<p>Back office executive jobs in Delhi range from routine administration to specialised operational work. Compare duties and salary together, check the experience requirement, and show accuracy and computer skills with examples you can repeat in an assessment. A clear written offer lets you judge whether the pay, commute, schedule and responsibilities suit you. This guide cannot promise work, income or an interview.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.ncs.gov.in/" rel="noopener">National Career Service, Ministry of Labour and Employment, Government of India</a></li>
<li><a href="https://www.apexsinfo.com/careers/" rel="noopener">Apex Sourcing & Info Services: careers page</a>, checked 26 October 2026</li>
<li><a href="https://www.pocketful.in/careers/open-roles/associate-operations-back-office-pocketful" rel="noopener">Pocketful: Associate, Operations and Back Office advert</a>, checked 26 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/bpo-jobs-pune-what-to-expect-day-one">BPO Jobs in Pune: What to Expect on Day One</a>, joining, training and shifts in process work.</li>
    <li><a href="/blog/seo-executive-jobs-mumbai-salary-skills">SEO Executive Jobs in Mumbai</a>, another Indian office role with the same salary questions.</li>
    <li><a href="/blog/data-entry-jobs-houston-no-experience">Data Entry Jobs in Houston With No Experience</a>, how to practise accuracy for an assessment.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, check this before you pay anyone.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, tax, financial or career advice. Reviewed on 26 October 2026 against the pages named in Sources. It does not promise a vacancy, an income or an interview, and employer adverts, pay and postings can change.</p>
HTML;
    }
}
