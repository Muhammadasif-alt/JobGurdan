<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Virtual Assistant Jobs in Manila, Philippines: Beginner Guide"
 *
 * Checked on 21 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief has ten apply buttons: seven searches on a commercial job board and three company pages. No buttons are used, and the job board is not named. The FAQ about the ten buttons is removed. A public portal, PhilJobNet, replaces them: it is run by the Philippine Department of Labor and Employment and says job search is free.
 * 2. Wing Assistant's page was read: Makati, six months to one year of experience, completed senior high school, 18 or over, US hours of 20 to 40 hours a week with a graveyard shift, onsite training for all new hires, hybrid for those near the office and fully remote in areas such as Visayas and Mindanao. The brief said "despite remote wording elsewhere"; the page actually says remote hires exist outside commuting distance. The page's salary section lists entry level as one to three years, which the guide flags. The PHP pay line is not repeated because a posting can change.
 * 3. Magic's page was read and its requirements section was empty, so the brief's USD 6 to 7 hourly figure cannot be tied to stated duties or eligibility. The Magic section is dropped. Athena's careers page was read: Philippine locations, two hiring tracks, training onsite at an Athena hub for the executive partner track.
 * 4. Private-employer postings are described as dated illustrations, with no links and no pay figures, in line with the rule that vacancy examples are not republished.
 * 5. Five FAQs and eight People Also Search For entries are added; the brief had three FAQs once the ten-openings question is removed.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class VirtualAssistantJobsManilaPhilippinesBlogSeeder extends Seeder
{
    public const SLUG = 'virtual-assistant-jobs-manila-philippines';

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
                'title' => 'Virtual Assistant Jobs in Manila, Philippines: Beginner Guide',
                'excerpt' => 'A beginner guide to virtual assistant jobs in Manila: the skills to practise, a portfolio built from honest samples, how some employers hire, equipment to check and the free PhilJobNet portal.',
                'content' => $content,
                'featured_image' => 'blogs/virtual-assistant-jobs-manila-philippines.jpg',
                'tags' => 'virtual assistant jobs manila, virtual assistant philippines beginners, remote assistant jobs manila, how to become a virtual assistant, philjobnet, va portfolio, graveyard shift, entry level virtual assistant',
                'meta_title' => 'Virtual Assistant Jobs in Manila: Beginner Guide',
                'meta_description' => 'Virtual assistant jobs in Manila for beginners: skills to build, a practice portfolio, how employers hire, equipment to check and the PhilJobNet portal.',
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
<p><strong>Virtual assistant jobs in Manila involve helping clients with schedules, emails, documents, customer enquiries, research, and other business tasks.</strong> Beginners should build communication and administrative skills, prepare a small portfolio, and apply to roles matching their actual experience. Check the work arrangement carefully: virtual assistant positions can be remote, hybrid, freelance, or employment based. Some require night shifts or onsite training. Start with the government portal and the employers' own hiring pages rather than assuming every advertisement accepts applicants without experience.</p>

<h2>What Does a Virtual Assistant Do?</h2>

<p>A general virtual assistant supports routine operations so clients can manage their work more efficiently. Responsibilities might include arranging appointments, maintaining spreadsheets, organising files, updating customer records, or preparing meeting notes.</p>

<p>Specialist roles can focus on executive support, bookkeeping, social media, ecommerce, or customer service. These positions may require additional knowledge and different portfolios.</p>

<p>Read each task list before applying. A role involving outbound calls or sales targets differs from one centred on inbox management. Ask which duties occupy most of the working week and who sets priorities when several requests arrive together.</p>

<h2>Skills Beginners Should Practise</h2>

<p>Focus on skills you can demonstrate:</p>

<ul>
    <li><strong>Communication:</strong> Write clear emails, ask useful questions, and summarise decisions accurately.</li>
    <li><strong>Organisation:</strong> Maintain task lists, consistent file names, and reliable reminders.</li>
    <li><strong>Calendars:</strong> Check time zones, meeting links, attendee availability, and scheduling conflicts.</li>
    <li><strong>Spreadsheets:</strong> Enter information accurately, use basic formulas, and review your work.</li>
    <li><strong>Research:</strong> Find relevant information and distinguish reliable sources from unsupported claims.</li>
    <li><strong>Confidentiality:</strong> Handle client information carefully and follow access instructions.</li>
</ul>

<p>Practise explaining your progress in a short update. Clients need to know what is finished, what is blocked, and what decision you need from them.</p>

<h2>How Some Employers Hire Virtual Assistants</h2>

<p>PhilJobNet is the Philippine Department of Labor and Employment's job-matching portal. It says jobseekers can create an account for free, which makes it a safe place to compare roles before you give your details to a private recruiter.</p>

<p>Official page: <a href="https://philjobnet.gov.ph/" rel="noopener">PhilJobNet</a>.</p>

<p>Private firms set their own rules, and their postings change or close, so treat these examples as illustrations from 21 October 2026, not as open vacancies. Wing Assistant's Philippines careers page described an entry-level general assistant role based in Makati. It asked for six months to one year of virtual assistant experience, completed senior high school and an age of at least 18. The hours followed US time, 20 to 40 hours a week, including a graveyard shift. All new hires completed onsite training at the Makati office, hybrid attendance applied afterwards to hires near the office, and hires in areas such as Visayas and Mindanao stayed fully remote. The same page listed a salary section that showed entry level as one to three years, so applicants had to confirm the experience requirement directly.</p>

<p>That example shows why "entry level" does not always mean "no experience", and why a commute to Makati matters if you live elsewhere in Metro Manila.</p>

<p>Athena's careers page listed Philippine locations and described two hiring tracks, each with an application and assessment stage and a training programme. For its executive partner track, it said you attend training onsite at an Athena hub. Use any current role listing to confirm the experience, schedule and training you would need before you invest time in assessments.</p>

<p>A careers page is a place to investigate suitable roles. It does not promise that every applicant qualifies.</p>

<h2>Build a Portfolio Without Inventing Experience</h2>

<p>Create three realistic samples using fictional information. For example, prepare a weekly calendar, a short research comparison, and a spreadsheet tracking customer enquiries.</p>

<p>Explain the purpose of each sample and the checks you performed. A calendar sample should show time zones clearly; a spreadsheet should be readable and consistent; a research document should identify its sources.</p>

<p>Label these as practice projects. Do not claim fictional clients or include confidential material from previous employers. If you have retail, office, hospitality, or support experience, describe relevant responsibilities honestly and connect them to the advertised work.</p>

<h2>Prepare Your Equipment and Work Schedule</h2>

<p>Check the employer's exact requirements before buying equipment. Wing's listing specified a USB headset with noise cancellation, a working webcam, wired main internet of at least 25 Mbps and a backup connection of at least 10 Mbps. These were the requirements of that posting, not universal standards across employers.</p>

<p>Test your audio, camera, and connection from your intended workspace. Consider noise, lighting, privacy, and how you would communicate during an outage.</p>

<p>Confirm whether training uses a different schedule from the assigned client. When supporting overseas clients, ask how time zone changes affect your hours. Clarify any onsite attendance before assuming the work can happen entirely from home.</p>

<h2>How to Apply Step by Step</h2>

<h3>1. Choose a Suitable Role</h3>

<p>Compare duties, experience, shift, location, and employment type. Apply selectively to positions where you can explain how your background meets the essential requirements.</p>

<h3>2. Tailor Your Resume</h3>

<p>Highlight relevant tools, administrative tasks, communication experience, and completed projects. Include a portfolio link and use a professional email address. Keep descriptions accurate and easy to scan.</p>

<h3>3. Complete the Application Carefully</h3>

<p>Submit through the official portal and follow file instructions. Record the company, job title, submission date, and next step. Check your inbox and spam folder for legitimate responses.</p>

<h3>4. Prepare for Assessments</h3>

<p>Practise organising competing tasks, drafting a concise email, and checking a spreadsheet. Explain your assumptions and ask clarifying questions when instructions are incomplete.</p>

<h2>Compare Offers Before Accepting</h2>

<p>Request written details covering pay, hours, probation or trial arrangements, training, benefits, equipment, and termination terms. Clarify whether you are being engaged as an employee or freelancer.</p>

<p>For freelance work, ask how hours are approved, when invoices are paid, and which payment fees apply. Do not assume employee benefits accompany a contractor rate.</p>

<p>Verify the recruiter through the official company website before sharing sensitive documents. Be cautious about requests to transfer money or complete substantial unpaid client work during recruitment.</p>

<h2>Improve After Each Application</h2>

<p>Keep a simple record of feedback and recurring requirements. If interviews reveal weak calendar skills, practise scheduling rather than immediately buying another course. If employers ask for clearer samples, improve your portfolio descriptions. Set manageable weekly goals for applications and learning. A consistent routine helps you identify progress while avoiding rushed submissions that overlook important details or instructions.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I Apply With No VA Experience?</h3>
<p>Look for roles explicitly accepting beginners or transferable experience. The reviewed Wing example still requests prior experience, so read requirements beyond the headline.</p>

<h3>Do I Need a College Degree?</h3>
<p>Requirements vary. The Wing role described above asked for completed senior high school, but that does not establish the education requirements of other employers.</p>

<h3>Are Virtual Assistant Jobs Always Remote?</h3>
<p>No. Some include hybrid attendance or onsite training. Confirm the actual location and schedule before accepting an offer.</p>

<h3>Is PhilJobNet free?</h3>
<p>Its About page says jobseekers can create an account for free and that the portal is free for all users.</p>

<h3>Do virtual assistants work night shifts?</h3>
<p>Some do, because the hours follow the client's time zone. The Wing role described above included a graveyard shift, so read the schedule before you accept.</p>

<h3>Do I need my own equipment?</h3>
<p>Often yes. Check the posting for headset, webcam, computer and internet requirements, and buy nothing until the employer confirms them in writing.</p>

<h3>Is a virtual assistant an employee or a freelancer?</h3>
<p>It depends on the arrangement. Ask for the terms in writing, because a contractor rate does not include employee benefits.</p>

<h3>How do I avoid a fake job offer?</h3>
<p>Verify the recruiter through the company's own website, and never pay for a job, training or unpaid client work.</p>

<h2>People Also Search For</h2>

<h3>Virtual assistant jobs Manila</h3>
<p>Search company hiring pages and PhilJobNet, and read each posting's duties and schedule.</p>

<h3>Virtual assistant Philippines beginners</h3>
<p>Build calendar, spreadsheet and email skills and show them in a small practice portfolio.</p>

<h3>Remote assistant jobs Manila</h3>
<p>Check whether a role is remote, hybrid or needs onsite training.</p>

<h3>How to become a virtual assistant</h3>
<p>Practise administrative skills, build honest samples and apply to roles that match your experience.</p>

<h3>PhilJobNet</h3>
<p>It is the free job-matching portal run by the Department of Labor and Employment.</p>

<h3>Entry level virtual assistant Philippines</h3>
<p>Entry level can still ask for six months or more of experience, so read the requirements.</p>

<h3>Virtual assistant portfolio</h3>
<p>Use fictional practice projects and label them as practice work.</p>

<h3>Virtual assistant graveyard shift</h3>
<p>Shifts follow the client's time zone, so confirm the hours before you accept.</p>



<h2>Bottom Line</h2>

<p>To begin pursuing virtual assistant jobs in Manila, develop reliable administrative skills, create honest work samples, and apply through verified channels. Compare experience requirements, schedules, and contract terms carefully so your first role matches your abilities and availability.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://philjobnet.gov.ph/" rel="noopener">PhilJobNet: Philippine Department of Labor and Employment job-matching portal</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/data-analyst-jobs-dublin-ireland">Data Analyst Jobs in Dublin, Ireland</a>: another office skill set with official sources.</li>
    <li><a href="/blog/bug-bounty-hunting-karachi-sindh-beginners">Bug Bounty Hunting From Karachi, Sindh</a>: a beginner route into security work from Pakistan.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or career advice. Reviewed on 21 October 2026 against the pages named in Sources. It does not promise a vacancy, a reward, an income or an offer, and platform rules and postings can change.</p>
HTML;
    }
}
