<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Email Marketing Jobs in Vancouver, British Columbia"
 *
 * Checked on 22 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief builds sections around a senior lifecycle post at an education company (full-time, three office days, CAD 65,000 to 75,000, three to five years) and a manager role at an apparel brand (email, SMS and loyalty programme). Both are private-employer vacancies with pay figures that cannot be checked against a live listing and go stale, so the examples, the pay figures and the apply buttons are dropped.
 * 2. The brief gave no source for pay. Job Bank wage data for NOC 11202 in Vancouver was read on 22 October 2026 (low $21.00, median $33.65, high $56.59 an hour, reference period 2023-2024, Labour Force Survey), and is used with a warning that it covers a broad group.
 * 3. The brief said to check CASL. The Government of Canada page on getting consent to send email was read the same day. fightspam.gc.ca now redirects to fightspam-combattrelepourriel.ised-isde.canada.ca (a new host to add to OFFICIAL_HOSTS). Only what that page says is stated: express and implied consent, identification, unsubscribe within 10 business days. No penalty figures appear on the page, so none are given.
 * 4. WorkBC was suggested but its site showed only a maintenance notice on 22 October 2026, so it is not cited or linked.
 * 5. Job Bank NOC 11202 search link for British Columbia showed 87 results on 22 October 2026 and is used as a dated snapshot. Four more FAQs and eight People Also Search For entries are added to reach the required counts.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class EmailMarketingJobsVancouverBritishColumbiaBlogSeeder extends Seeder
{
    public const SLUG = 'email-marketing-jobs-vancouver-british-columbia';

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
                'title' => 'Email Marketing Jobs in Vancouver, British Columbia',
                'excerpt' => 'A guide to email marketing jobs in Vancouver: the job titles to search, the skills and quality checks employers expect, the CASL consent rules, portfolio advice and what Job Bank wage data shows.',
                'content' => $content,
                'featured_image' => 'blogs/email-marketing-jobs-vancouver-british-columbia.jpg',
                'tags' => 'email marketing jobs vancouver, lifecycle marketing jobs vancouver, crm marketing jobs british columbia, email marketing specialist vancouver bc, email marketing coordinator, casl email rules, job bank wages vancouver, email marketing portfolio',
                'meta_title' => 'Email Marketing Jobs in Vancouver, British Columbia',
                'meta_description' => 'Email marketing jobs in Vancouver: job titles, skills, CASL consent rules, portfolio advice and Job Bank wage data for the region.',
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
<p><strong>Email marketing jobs in Vancouver involve planning messages, organising audiences, building campaigns, checking content and measuring results.</strong> Vacancies may be titled email marketing specialist, CRM coordinator, retention marketer or lifecycle marketing manager, so search across these terms and then compare responsibilities, experience requirements and working arrangements. This guide covers the skills, the legal rules for sending and the public wage data, and it does not promise a vacancy, an interview or an income.</p>

<h2>Job Titles You Will See</h2>

<p>The same work travels under several names. Email marketing is the planning, writing, building and measuring of email campaigns. Lifecycle marketing looks at the whole customer journey and may include email, text messages and app messages. CRM marketing uses customer data to decide who gets which message and when.</p>

<p>A title does not tell you how technical the job is. One "email specialist" writes the copy and schedules sends; another builds audience segments and reports on results. Read the duties, not just the heading.</p>

<h2>Where the Official Data Sits</h2>

<p>Job Bank is the Government of Canada's job search. Its occupation filter groups email and marketing communication work under National Occupational Classification (NOC) code 11202, professional occupations in advertising, marketing and public relations. A Job Bank search for that code limited to British Columbia showed 87 results when we checked it on 22 October 2026. Counts change daily, so treat that as a snapshot, not a promise of openings: <a href="https://www.jobbank.gc.ca/jobsearch/jobsearch?fn21=11202&amp;fprov=BC" rel="noopener">Job Bank search for NOC 11202 in British Columbia</a>.</p>

<p>Job Bank also publishes wage data. For an advertising specialist (NOC 11202) in Vancouver, reported under the Lower Mainland-Southwest region, its page lists hourly wages of $21.00 at the low end, a median of $33.65 and $56.59 at the high end. The reference period is 2023-2024, the source is the Labour Force Survey from Statistics Canada, and the page was last updated on 19 November 2025. This covers a broader group than email marketing alone, so use it as context for a salary conversation, not as the pay for a particular job. See the <a href="https://www.jobbank.gc.ca/marketreport/wages-occupation/24722/39070" rel="noopener">Job Bank wage page</a>.</p>

<h2>Start With Coordinator and Assistant Searches</h2>

<p>If you are new, search for email marketing coordinator, marketing assistant, CRM assistant and marketing operations assistant as well as the more senior titles. Senior lifecycle roles usually ask for several years of experience, so a beginner who applies only to those will be disappointed.</p>

<p>Build practice projects that look like the work: a welcome sequence for an imaginary online shop, a monthly newsletter, or a re-engagement email for customers who have gone quiet. Label them clearly as practice work.</p>

<h2>Plan and Write a Campaign</h2>

<p>Each email starts with a brief. Decide the audience, the goal, the single action you want the reader to take, and how you will tell whether it worked. Then write the subject line, the preview text, the body and the call to action.</p>

<p>Adapt the same offer for different readers. A new subscriber needs a short introduction to the brand; an existing customer needs a reason to come back. Before anything is sent, check links, dates, names and spelling.</p>

<h2>Canada's Anti-Spam Legislation (CASL)</h2>

<p>Anyone sending marketing email to people in Canada needs to understand consent. The Government of Canada's anti-spam legislation website explains the rules for commercial emails, and employers will expect you to know them. It says:</p>

<ul>
<li><strong>Express consent</strong> means someone has agreed, verbally or in writing, to receive a commercial electronic message from you. It has no time limit unless the person withdraws consent.</li>
<li><strong>Implied consent</strong> is recognised only in certain circumstances, such as an existing business relationship, and it is generally time-limited. The page notes it may last up to 2 years, or 6 months for inquiries or applications.</li>
<li><strong>Identification:</strong> a message must include your business name and the name of anyone on whose behalf it is sent, a current mailing address and either a phone number, an email or a website address. The contact information must stay valid for at least 60 days after sending.</li>
<li><strong>Unsubscribe:</strong> every message needs a clear unsubscribe mechanism that can be readily performed, and each request must be handled within 10 business days or less, at no cost to the recipient.</li>
</ul>

<p>This is a summary of one government page, not legal advice, and the site itself says it should not be treated as legal advice. Read the full page here: <a href="https://fightspam-combattrelepourriel.ised-isde.canada.ca/site/canada-anti-spam-legislation/en/getting-consent-send-email" rel="noopener">Getting consent to send email</a>. In interviews, it is reasonable to ask how the team records consent and handles unsubscribe requests.</p>

<h2>Platform Workflows and Quality Checks</h2>

<p>Whatever tool a team uses, the quality checks are similar. Use a simple checklist before each send:</p>

<ul>
<li>Is the right list or segment selected, and are suppressed contacts excluded?</li>
<li>Do personalisation fields fall back to something sensible when data is missing?</li>
<li>Do all links work, and do they go to the right pages?</li>
<li>Does the layout read well on a phone?</li>
<li>Has a test message been sent and checked on more than one device?</li>
<li>Do the sender name, address and unsubscribe link meet the rules above?</li>
</ul>

<p>Employers value people who catch mistakes before the customer does.</p>

<h2>Build a Portfolio Around Decisions</h2>

<p>A portfolio for email work is more convincing when it explains choices. Include a welcome sequence, a campaign calendar for a month and a sample results report. For each piece, say who the audience was, what you decided and why. If you show real results, state the period and exactly what you contributed, and never present invented figures as real.</p>

<h2>Reading Results Without Fooling Yourself</h2>

<p>Raw numbers can mislead. A higher number of clicks may only mean the list was bigger, so compare rates as well as counts. If engagement falls, check the audience, the send time, the subject line, whether the message looked different on a phone and whether the list has grown stale. One test should change one thing at a time, otherwise you will not know what worked.</p>

<h2>Remote Work, Pay and Contracts</h2>

<p>A job advertised as remote may still require you to live in British Columbia or to attend the office on set days. Ask before you accept. Salaries are quoted in Canadian dollars, and some roles include bonuses or other variable pay, so ask which part is guaranteed.</p>

<p>If you take freelance or contract work, agree the scope, the number of revisions, the price and the payment date in writing. Keep a record of what was approved.</p>

<h2>Prepare Your Application</h2>

<p>Put a link to your portfolio at the top of your CV and name the sample closest to the role. Read the advertisement for the tools and metrics mentioned, and be honest about what you have used. Ask in the interview how consent, customer preferences and unsubscribe requests are handled, how success is measured and who approves campaigns before they go out.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners enter email marketing?</h3>
<p>Yes, usually through coordinator or assistant roles. Build practice projects such as a welcome sequence, and be honest that they are practice work.</p>

<h3>Is email marketing only copywriting?</h3>
<p>No. Writing matters, but the job also covers audience selection, scheduling, testing, quality checks and reading results.</p>

<h3>Do I need to know how to code?</h3>
<p>Not always. Some roles use simple templates, and others expect basic HTML. The job description will tell you which.</p>

<h3>Are Vancouver email marketing jobs remote?</h3>
<p>Some are, but a remote role may still require you to live in British Columbia or come to the office on set days. Check the posting.</p>

<h3>What do Job Bank wages show for this kind of work?</h3>
<p>For an advertising specialist (NOC 11202) in Vancouver, Job Bank lists hourly wages of $21.00 low, $33.65 median and $56.59 high for 2023-2024. It is a broad group, not an offer.</p>

<h3>Do I need consent to email people in Canada?</h3>
<p>Yes. The Government of Canada says commercial emails need express or implied consent, identification details and a working unsubscribe mechanism.</p>

<h3>How fast must an unsubscribe request be handled?</h3>
<p>The Government of Canada says each request must be handled within 10 business days or less, at no cost to the recipient.</p>

<h3>Is lifecycle marketing the same as email marketing?</h3>
<p>They overlap. Lifecycle marketing looks at the whole customer journey and may include text and app messages as well as email.</p>

<h2>Bottom Line</h2>

<p>Match your search to your experience, show clear decisions in a small portfolio, learn the consent rules that apply to email in Canada and check the working arrangement before you accept. Official wage data is context, not a promise, and no guide can guarantee a vacancy or an interview.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://fightspam-combattrelepourriel.ised-isde.canada.ca/site/canada-anti-spam-legislation/en/getting-consent-send-email" rel="noopener">Government of Canada: Getting consent to send email (CASL)</a></li>
<li><a href="https://www.jobbank.gc.ca/marketreport/wages-occupation/24722/39070" rel="noopener">Job Bank: Wages for Advertising Specialist, Vancouver</a></li>
<li><a href="https://www.jobbank.gc.ca/jobsearch/jobsearch?fn21=11202&amp;fprov=BC" rel="noopener">Job Bank: Job search for NOC 11202 in British Columbia</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a>: related marketing roles and skills in the United States.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a>: how marketing routes are described in the UK.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or career advice. Reviewed on 22 October 2026 against the pages named in Sources. It does not promise a vacancy, an interview, an income or an offer, and wage data and postings can change.</p>
HTML;
    }
}
