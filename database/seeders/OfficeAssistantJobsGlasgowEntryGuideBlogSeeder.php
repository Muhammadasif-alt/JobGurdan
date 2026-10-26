<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Office Assistant Jobs in Glasgow, Scotland: Entry Guide"
 *
 * Checked on 26 October 2026 against the pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief's seven job-board search buttons are dropped (the site links only official or employer pages).
 * 2. My World of Work (Skills Development Scotland) administrator profile fetched: "There are no fixed entry routes to becoming an administrator", HNC or HND could help, Foundation and Modern Apprenticeships, no pay figure.
 * 3. The Glasgow City Council Clerical Officer advert GLA15880 was fetched: closed on 4 September 2026, so the guide says so and quotes it only as a requirements and pay-band example (£26,031.26 to £27,680.96, 35 hours, permanent, Exchange House). The myjobscotland Glasgow listings page is linked for current vacancies.
 * 4. The University of Glasgow jobs page now sends applicants to jobs.gla.ac.uk; on 26 October 2026 its Administrative Assistant (Grade 5, £28,031 to £31,236) was open to current employees only, which the guide uses to illustrate internal-only adverts.
 * 5. Added the National Careers Service admin assistant profile (£21,000 starter, £28,000 experienced, 37 to 40 hours, routes in, skills) and the National Minimum Wage figures already checked on 22 October 2026 (£12.71 for 21 and over, £8.00 for apprentices, from 1 April 2026).
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for applicants in Pakistan added.
 *
 * No poster was supplied for this brief; the featured image was generated in
 * the house style and can be replaced from the admin panel. The portal
 * listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class OfficeAssistantJobsGlasgowEntryGuideBlogSeeder extends Seeder
{
    public const SLUG = 'office-assistant-jobs-glasgow-entry-guide';

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
                'title' => 'Office Assistant Jobs in Glasgow, Scotland: Entry Guide',
                'excerpt' => 'How to get into office assistant work in Glasgow: what Skills Development Scotland says about routes in, Modern Apprenticeships, a council clerical advert and university listings checked in October 2026, official pay figures and CV tips.',
                'content' => $content,
                'featured_image' => 'blogs/office-assistant-jobs-glasgow-entry-guide.jpg',
                'tags' => 'office assistant jobs in glasgow, entry level admin jobs glasgow, clerical assistant jobs glasgow, office junior jobs scotland, business administration apprenticeship glasgow, glasgow city council clerical officer, myjobscotland glasgow jobs, admin assistant salary scotland',
                'meta_title' => 'Office Assistant Jobs in Glasgow, Scotland: Entry Guide',
                'meta_description' => 'Office assistant jobs in Glasgow: routes in, apprenticeships, a council clerical advert and university listings checked October 2026, pay figures and CV tips.',
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
<p><strong>You can get into office assistant jobs in Glasgow through direct applications, junior administration roles or a business administration Modern Apprenticeship, and no single qualification is required across employers.</strong> Start with vacancies whose essential criteria match your education and experience, then show accurate computer work, clear communication and organisation through practical examples. Search beyond "office assistant": employers also write administrative assistant, clerical assistant, business support or office junior. This guide uses Skills Development Scotland, the National Careers Service, UK Government pay rules and two Glasgow employer pages we checked on 26 October 2026. It cannot promise a job, a wage or an interview.</p>

<h2>Understand the Work Before Applying</h2>

<p>Office assistants keep administrative work organised for colleagues. Depending on the employer, the tasks include answering enquiries, maintaining records, arranging appointments, preparing documents, processing post and updating spreadsheets. Read the responsibilities to see which activities dominate, and check whether the role includes reception cover, invoice processing or specialist records. Ask who you will support and which systems you will use, so you can pick relevant examples and decide whether the workload suits your strengths.</p>

<h2>What Skills Development Scotland Says About Routes In</h2>

<p>My World of Work, the careers service run by Skills Development Scotland, says of administrators: "There are no fixed entry routes to becoming an administrator." It adds that "Getting qualifications at college, such as an HNC or HND, could help you find an entry-level job in administration", that a Foundation Apprenticeship in S5 and S6 gives a head start, and that with Modern Apprenticeships "You get paid and work towards a qualification at the same time." The skills it lists include good spelling, grammar and typing, clear communication with colleagues and customers, and attention to detail when working with information and records. Source: <a href="https://www.myworldofwork.co.uk/search-job-profiles/job-profiles/administrator" rel="noopener">My World of Work: Administrator</a>.</p>

<p>Read each person specification and separate essential qualifications from desirable ones. If an advert accepts equivalent experience, explain your evidence. If your qualification is unfamiliar to the employer, ask what information they need rather than guessing at its equivalence.</p>

<h2>Consider Apprenticeships and Trainee Roles</h2>

<p>A business administration apprenticeship combines employment with learning towards a qualification. In Scotland that is a Modern Apprenticeship, found through employers and training providers; the UK Government's <a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">Find an apprenticeship</a> service covers England, so check the location before you rely on it. Eligibility, availability and qualification arrangements depend on the opportunity, so inspect the actual vacancy. Search for trainee administrator, business administration apprentice and office junior positions, compare the training, hours, pay and responsibilities, and ask who supervises your work and how learning time is arranged. A trainee title does not guarantee structured teaching.</p>

<p>On pay, the National Minimum Wage is £12.71 an hour for workers aged 21 and over and £8.00 an hour for apprentices, effective 1 April 2026, with the apprentice rate applying only to apprentices under 19 or in the first year. The National Careers Service gives admin assistants an average salary of £21,000 as a starter and £28,000 when experienced, typically 37 to 40 hours a week; those are UK-wide figures, not Glasgow offers. Sources: <a href="https://www.gov.uk/national-minimum-wage-rates" rel="noopener">GOV.UK: National Minimum Wage rates</a> and <a href="https://nationalcareers.service.gov.uk/job-profiles/admin-assistant" rel="noopener">National Careers Service: Admin assistant</a>.</p>

<h2>Build Useful Computer Skills</h2>

<p>Practise creating a clear document, sending a professional email, scheduling a meeting and updating a spreadsheet. Use fictional records to check names, dates, totals and missing information, and learn to sort a complete dataset without separating related entries. Match your preparation to the software in the advert. You do not need to call yourself advanced: explain what you can do independently and where you would need training. A small practice exercise lets you talk confidently about accuracy, file organisation and checking.</p>

<h2>Use Official Employer Recruitment Pages</h2>

<p>The University of Glasgow's jobs page now sends applicants to its own recruitment site, Jobs at UofG. When we checked on 26 October 2026 its Administrative Assistant vacancy (Grade 5, £28,031 to £31,236 a year) was open to current University of Glasgow employees only, which is exactly why you must check whether a university advert is open to external applicants before you prepare an application. Read the closing date, location, grade and experience requirements, and follow the requested process rather than sending the same CV to a general address. Official pages: <a href="https://www.gla.ac.uk/explore/jobs/" rel="noopener">Jobs at Glasgow</a> and <a href="https://jobs.gla.ac.uk/" rel="noopener">Jobs at UofG</a>.</p>

<h2>Read Council Person Specifications Carefully</h2>

<p>Scotland's councils, including Glasgow City Council, recruit through myjobscotland. A Glasgow City Council Clerical Officer advert (reference GLA15880) we reviewed had already closed, on 4 September 2026, so it is a requirements example and not an open vacancy. It was permanent, 35 hours a week at Exchange House, 231 George Street, paid £26,031.26 to £27,680.96 a year, and asked that candidates "communicate well", "be comfortable with numbers", work accurately and follow processes within set deadlines. Knowledge of SAP, Propman, CareFirst, Pulse, EDRMS and Microsoft packages was advantageous, and the advert said full training is given. Search the council's current listings and check each closing date: <a href="https://myjobscotland.gov.uk/councils/glasgow/jobs" rel="noopener">myjobscotland: Glasgow City Council jobs</a>. Address each essential criterion with an example rather than a statement about being hardworking.</p>

<h2>Show Transferable Experience on Your CV</h2>

<p>Retail work, volunteering, education, events and other responsibilities all give evidence: handling enquiries, maintaining a rota, checking orders and organising information. State the task, your contribution and the outcome, for example how you maintained an attendance list and chased missing details. Do not invent performance figures or present coursework as employment. Keep the CV readable, follow the application instructions, and if a supporting statement is required, connect your examples to the person specification instead of repeating your employment history.</p>

<h2>Prepare for Interviews and Practical Exercises</h2>

<p>Practise explaining how you prioritise tasks, respond to an enquiry, correct an error and handle confidential information, describing what you did, why and what happened. An employer may set an administrative exercise, so rehearse proofreading, checking a simple spreadsheet and drafting a courteous email, and read the instructions before you start. If two deadlines conflict, explain how you would clarify priorities with the right colleague. Accuracy, sensible questions and a clear checking method are stronger evidence than claiming you never make mistakes.</p>

<h2>Compare Hours, Pay and Contract Terms</h2>

<p>Check whether the job is permanent, temporary, fixed-term, part-time or term-time, and confirm the workplace and attendance requirements. A Glasgow search includes nearby towns, so calculate the commute before an interview. If a salary is advertised as full-time equivalent or pro rata, ask for the actual annual amount for your contracted hours, and compare like with like. Ask about training, probation and the start date, and do not assume hybrid working, flexible hours or particular benefits unless they are confirmed for the position.</p>

<h2>Apply Consistently and Review Your Results</h2>

<p>Track the employer, role, closing date, application method and outcome, and save the job description for interview preparation. Verify unexpected recruitment messages through the employer's published contact route. If applications get little response, review how clearly you address the essential criteria, improve the relevant skills and adjust your search terms rather than sending an unchanged application to unsuitable vacancies.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>Office assistant is not a role that normally qualifies for a UK Skilled Worker visa, so these vacancies are realistic only if you already have the right to work in the UK. Never pay an agent for a "guaranteed" UK office job or visa, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a university degree?</h3>
<p>No. My World of Work says there are no fixed entry routes, and that an HNC or HND could help. Check the qualifications each employer specifies and consider trainee or apprenticeship routes.</p>

<h3>Can I apply without office experience?</h3>
<p>Some roles accept beginners. Show relevant skills from other activities and avoid vacancies whose essential experience requirements you cannot meet.</p>

<h3>How much do office assistants earn in Glasgow?</h3>
<p>The National Careers Service gives £21,000 as a starter and £28,000 when experienced across the UK, and the closed Glasgow City Council clerical advert paid £26,031.26 to £27,680.96 for 35 hours. Each advert states its own figure.</p>

<h3>What is a Modern Apprenticeship?</h3>
<p>Scotland's apprenticeship route: you are employed, paid and work towards a qualification at the same time. The apprentice minimum wage is £8.00 an hour for those under 19 or in their first year.</p>

<h3>Why are some university adverts closed to me?</h3>
<p>Some are open to current staff only. The University of Glasgow's Administrative Assistant advert we checked was internal, so read the eligibility line before applying.</p>

<h3>Are office assistant jobs remote?</h3>
<p>Some offer flexibility; others require attendance. Confirm the arrangement for the specific vacancy rather than relying on a search filter.</p>

<h3>Which skill should I practise first?</h3>
<p>Accurate document and spreadsheet work, then email communication and task organisation against the vacancy requirements.</p>

<h3>Where do Glasgow council jobs appear?</h3>
<p>On myjobscotland, the recruitment site used by Scotland's councils. Search the Glasgow City Council listings and check each closing date.</p>

<h2>People Also Search For</h2>

<h3>Office assistant jobs in Glasgow</h3>
<p>Search council, university and employer pages under several titles.</p>

<h3>Entry level admin jobs Glasgow</h3>
<p>Separate essential from desirable criteria before applying.</p>

<h3>Clerical assistant jobs Glasgow</h3>
<p>The council's clerical officer advert asked for accuracy, numbers and deadlines.</p>

<h3>Office junior jobs Scotland</h3>
<p>A trainee title does not guarantee structured training; ask.</p>

<h3>Business administration apprenticeship Glasgow</h3>
<p>A Modern Apprenticeship: paid work plus a qualification.</p>

<h3>Glasgow City Council clerical officer</h3>
<p>The GLA15880 advert closed on 4 September 2026; check current listings.</p>

<h3>myjobscotland Glasgow jobs</h3>
<p>The councils' recruitment site, with closing dates on every advert.</p>

<h3>Admin assistant salary Scotland</h3>
<p>UK-wide £21,000 starter to £28,000 experienced, per the National Careers Service.</p>

<h2>Bottom Line</h2>

<p>Target Glasgow office assistant roles that match your qualifications and evidence, build practical computer skills, tailor each application to the person specification and confirm the duties, hours, pay and training before you accept. This guide cannot promise work, income or an interview.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.myworldofwork.co.uk/search-job-profiles/job-profiles/administrator" rel="noopener">Skills Development Scotland, My World of Work: Administrator</a></li>
<li><a href="https://nationalcareers.service.gov.uk/job-profiles/admin-assistant" rel="noopener">National Careers Service: Admin assistant</a></li>
<li><a href="https://www.gov.uk/national-minimum-wage-rates" rel="noopener">GOV.UK: National Minimum Wage and National Living Wage rates</a></li>
<li><a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">GOV.UK: Find an apprenticeship</a></li>
<li><a href="https://www.gla.ac.uk/explore/jobs/" rel="noopener">University of Glasgow: Jobs at Glasgow</a> and <a href="https://jobs.gla.ac.uk/" rel="noopener">Jobs at UofG</a>, checked 26 October 2026</li>
<li><a href="https://myjobscotland.gov.uk/councils/glasgow/jobs/clerical-officer-486135?sType=myjobscotland" rel="noopener">Glasgow City Council: Clerical Officer, GLA15880</a>, closed 4 September 2026, and <a href="https://myjobscotland.gov.uk/councils/glasgow/jobs" rel="noopener">current Glasgow City Council listings</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/copywriter-jobs-birmingham-england-beginners">Copywriter Jobs in Birmingham for Beginners</a>, another UK entry route with apprenticeship and pay rules.</li>
    <li><a href="/blog/back-office-executive-jobs-delhi-duties-salary">Back Office Executive Jobs in Delhi</a>, the same administrative skills in another market.</li>
    <li><a href="/blog/data-entry-jobs-houston-no-experience">Data Entry Jobs in Houston With No Experience</a>, how to practise accuracy for an assessment.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, check this before you send money or documents.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration, financial or career advice. Reviewed on 26 October 2026 against the pages named in Sources. It does not promise a vacancy, an income or an interview, and employer adverts, pay and postings can change.</p>
HTML;
    }
}
