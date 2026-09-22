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
 * "How to Become a Remote Virtual Assistant" — the global VA guide: what the
 * work really pays once platform fees and tax come out, and how to start
 * without paying anyone for a job.
 *
 * The Virtual Assistant Jobs in Pakistan guide owns the local market, so this
 * page stays on the international picture and links to it.
 *
 * Corrections to the draft (checked against the BLS Occupational Outlook
 * Handbook and OEWS, ONS ASHE 2025, Upwork, Fiverr, IRS, GOV.UK and the FTC,
 * September 2026):
 *
 * 1. The draft presents "$47,526 to $68,026" as a salary average. That is
 *    FlexJobs company data. There is no BLS occupation called virtual
 *    assistant; the nearest official medians are $47,540 for administrative
 *    assistants and $76,590 for executive assistants, May 2025.
 *
 * 2. The draft puts specialised UK VA work at GBP 25,000 to 60,000+. ONS puts
 *    personal assistants and other secretaries at a median of GBP 25,233 for
 *    all employees and GBP 34,954 full-time, with the 90th percentile at
 *    GBP 48,675, so the top of that range is not supported.
 *
 * 3. The draft implies demand is rising. The BLS projects a 2% decline for
 *    secretaries and administrative assistants to 2035, while still expecting
 *    about 314,400 openings a year from turnover.
 *
 * 4. The draft quotes hourly rates without platform fees. Upwork charges a
 *    freelancer service fee of 0% to 15% per contract and Fiverr takes 20%.
 *
 * 5. The draft says nothing about tax. US self-employment tax is 15.3%, a
 *    return is required from $400 of net earnings, and the 1099-NEC threshold
 *    is now $2,000 for tax year 2026, not $600.
 *
 * 6. The draft has no scam warning. The FTC's rule is that honest employers
 *    never ask you to pay to get a job.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RemoteVirtualAssistantBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-virtual-assistant-remote-jobs.html';

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
        $title = 'How to Become a Remote Virtual Assistant';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'No degree is required, and Upwork puts beginners at $10 to $20 an hour. There is no official VA salary, so this guide prices the work from BLS and ONS data and shows what platform fees and tax take out.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-become-a-remote-virtual-assistant.jpg',
                'tags' => 'remote virtual assistant jobs, how to become a virtual assistant, virtual assistant rates, upwork fees, fiverr commission, executive assistant salary, va portfolio, work from home admin jobs',
                'meta_title' => 'How to Become a Remote Virtual Assistant: Pay and Steps',
                'meta_description' => 'How to become a remote virtual assistant: real pay from BLS and ONS data, Upwork and Fiverr fees, the tax rules, and how to start without paying for a job.',
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
            ['name' => 'Remote Employers and Freelance Platforms (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'remote-va-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Remote'],
            ['area' => 'Worldwide', 'country' => 'Remote']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Virtual Assistant — Remote Administrative, Inbox, Scheduling and Client Support Roles',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Contract',
                'job_type' => 'Remote',
                'work_hours' => 'Flexible; hourly, retainer or per-project, often across client time zones',
                'language' => 'English',
                // Rates are set by each client and platform, so the guide
                // quotes official and platform-published figures instead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Remote virtual assistant roles supporting founders, agencies and small businesses with inbox, calendar, research and admin work.',
                'seo_keywords' => 'virtual assistant jobs, remote va jobs, online assistant jobs, work from home admin jobs, freelance assistant jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Founders, coaches, agencies and small businesses hire virtual assistants remotely to take over the administrative work that does not need to be done in an office.</p>

<h3>What the work involves</h3>
<p>Managing inboxes and calendars, scheduling, travel and expenses, research, data entry, CRM updates, customer replies, documents and reports, and keeping projects moving between teams.</p>

<h3>Common requirements</h3>
<ul>
    <li>Clear written and spoken English</li>
    <li>Organisation, time management and accuracy</li>
    <li>Google Workspace or Microsoft 365, and a scheduling tool</li>
    <li>Reliable internet and a quiet place to work</li>
    <li>For freelance work: your own tax registration and invoicing</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> rates, platform fees and tax obligations are set by clients, platforms and tax authorities &mdash; not by JobGader. Never pay a fee to be given a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>You do not need a degree or a certificate to become a remote virtual assistant. You need organisation, clear communication, comfort with Google Workspace or Microsoft 365, and a small portfolio that shows what you can actually do.</strong> Upwork's own guidance puts beginners at <strong>$10 to $20 an hour</strong>, and rates rise with a track record and a specialism.</p>

<p>What almost every guide gets wrong is the pay data. There is <strong>no official occupation called "virtual assistant"</strong> anywhere in government statistics, so this guide prices the work from the occupations that do exist, then shows what platform fees and tax take out of the headline rate.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-virtual-assistant-remote-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Browse Remote Virtual Assistant Jobs &rarr;
    </a>
</div>

<h2>What a Virtual Assistant Actually Does</h2>

<ul>
    <li>inbox triage and replies, and calendar management;</li>
    <li>scheduling meetings, travel and expenses;</li>
    <li>research, data entry and CRM upkeep;</li>
    <li>documents, reports and simple presentations;</li>
    <li>customer or client follow-up;</li>
    <li>coordinating between a client's contractors and teams.</li>
</ul>

<p>The US Bureau of Labor Statistics recognises the arrangement in one line on its administrative assistants page: "Some administrative assistants work out of their own homes as virtual assistants." That sentence is the honest framing of the job &mdash; it is administrative work, done remotely, usually as a contractor.</p>

<h2>What the Work Really Pays</h2>

<p>Three sources, three different things measured:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Source</th>
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">What it is</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Upwork</strong></td><td style="padding:10px;"><strong>$10&ndash;$20 an hour</strong></td><td style="padding:10px;">What most beginner VAs on Upwork charge, per Upwork's own guide</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>BLS, May 2025</strong></td><td style="padding:10px;"><strong>$47,540</strong> median</td><td style="padding:10px;">Employed administrative assistants (10th percentile $33,280, 90th $66,350)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">BLS, May 2025</td><td style="padding:10px;"><strong>$76,590</strong> median</td><td style="padding:10px;">Executive assistants (90th percentile $109,850)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">ONS, 2025</td><td style="padding:10px;">&pound;25,233 all employees; <strong>&pound;34,954</strong> full-time</td><td style="padding:10px;">UK personal assistants and other secretaries (90th percentile &pound;48,675)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">FlexJobs (job board, not official)</td><td style="padding:10px;">$47,526&ndash;$68,026</td><td style="padding:10px;">The company's own VA salary estimate, widely quoted as if it were government data</td></tr>
    </tbody>
</table>
</div>

<p>Two corrections worth carrying away. The <strong>"&pound;60,000 and above" figure repeated for specialist UK VA work sits above the 90th percentile</strong> of the nearest official occupation, so treat it as marketing. And the jump from $47,540 to $76,590 is the real career ladder here: it is the difference between general admin support and executive-level support.</p>

<h2>Platform Fees Change the Rate You Quote</h2>

<ul>
    <li><strong>Upwork:</strong> a freelancer service fee of <strong>0% to 15% per contract</strong>, fixed once the contract begins. Connects cost <strong>$0.15</strong> each, and Freelancer Plus is $19.99 a month for 100 Connects.</li>
    <li><strong>Fiverr:</strong> a <strong>20% commission</strong> on what you earn, which Fiverr tells sellers to factor into their price.</li>
</ul>

<p>So a $20 hourly rate on Fiverr nets $16, and on Upwork nets between $17 and $20 depending on the contract. Direct clients pay no platform fee at all &mdash; which is why experienced VAs move off the marketplaces once they have testimonials.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-become-a-remote-virtual-assistant-home-office.jpg" alt="A virtual assistant working on a laptop calendar at a home desk with a notebook and coffee" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Inbox and calendar work is where most VA roles start.</figcaption>
</figure>

<h2>Is the Work Growing?</h2>

<p>Be honest with yourself about the market. The BLS projects employment of secretaries and administrative assistants to <strong>decline 2% from 2025 to 2035</strong>, a fall of 75,300 jobs, while still expecting about <strong>314,400 openings a year</strong> from people leaving the occupation. Remote work, meanwhile, is normal in this field: <strong>22.8%</strong> of people in US office and administrative support occupations teleworked in August 2026, above the 21.6% average for all workers, and <strong>28%</strong> of workers in Great Britain were hybrid in early 2025.</p>

<p>The practical reading: employed admin roles are shrinking slowly, but turnover keeps hiring going, and the remote share of the work is high. Specialising is how you stay on the right side of that.</p>

<h2>Specialise, and Watch the Job Title</h2>

<p>A generalist doing scattered admin competes with everyone. A VA who owns one outcome &mdash; inbox and calendar systems for founders, research and reporting, CRM and pipeline upkeep, or bookkeeping support &mdash; competes with far fewer people and can charge more.</p>

<p>The title matters too. The same work advertised as "executive assistant", "research assistant" or "operations assistant" often pays more than the same duties advertised as "virtual assistant", and the BLS gap between administrative and executive assistants shows that is not just perception.</p>

<h2>How to Start, Step by Step</h2>

<ol>
    <li><strong>List what you can already do.</strong> Every tool you know and every process you have run &mdash; scheduling, invoicing, travel, reporting, customer replies.</li>
    <li><strong>Pick two or three services</strong> rather than offering everything.</li>
    <li><strong>Build a small portfolio.</strong> A shared document or Notion page with sample work &mdash; an inbox triage system, a meeting brief template, a research summary &mdash; is enough to start.</li>
    <li><strong>Set up a clear profile</strong> with a specific headline, such as "Virtual assistant &mdash; inbox and calendar management for founders".</li>
    <li><strong>Price with fees in mind.</strong> Decide your take-home first, then add the platform's cut.</li>
    <li><strong>Pitch specifically.</strong> Name the problem you noticed and what you would take off their plate this week.</li>
    <li><strong>Write outcomes, not duties.</strong> "Cut scheduling time by five hours a week" beats "managed calendar".</li>
    <li><strong>Raise rates with proof:</strong> testimonials, retained clients and measurable results.</li>
</ol>

<h2>The Tax Side Nobody Mentions</h2>

<p>Freelance VA work is self-employment, and the rules are specific.</p>

<ul>
    <li><strong>United States.</strong> Self-employment tax is <strong>15.3%</strong> &mdash; 12.4% Social Security plus 2.9% Medicare &mdash; and you file Schedule SE once net earnings reach <strong>$400</strong>. Expect to make quarterly estimated payments if you will owe <strong>$1,000</strong> or more. The 1099-NEC reporting threshold is now <strong>$2,000 for tax year 2026</strong>, not the $600 figure still quoted everywhere &mdash; and income is taxable whether or not a form arrives.</li>
    <li><strong>United Kingdom.</strong> Register for Self Assessment as a sole trader once you earn more than <strong>&pound;1,000</strong> in a tax year. Register for VAT if taxable turnover passes <strong>&pound;90,000</strong>. Key dates: tell HMRC by 5 October, paper returns by 31 October, online returns and payment by 31 January.</li>
    <li><strong>Everywhere.</strong> Keep invoices, platform statements and expense records from your first client. You are taxed on profit.</li>
</ul>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-become-a-remote-virtual-assistant-client-call.jpg" alt="A virtual assistant wearing a headset smiling during a client call at a home office desk" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Clear communication is the skill clients actually pay for.</figcaption>
</figure>

<h2>How to Avoid VA Job Scams</h2>

<p>This field attracts them, because it is remote, entry-level and hiring is informal. The rule from the US Federal Trade Commission is short: <strong>"Honest employers, including the federal government, will never ask you to pay to get a job."</strong> The FTC adds that if a placement firm asks for a fee, especially in advance, you should walk away.</p>

<p>Practical checks: never pay for training you were told is required to be hired, never send money for "equipment" you will be reimbursed for, be wary of cheque overpayments, and do not hand over identity documents before a real contract exists.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree or certificate to become a virtual assistant?</h3>
<p>No. The nearest official occupation, administrative assistant, has a high school diploma as its typical entry-level education, and clients hire on demonstrated organisation, communication and tools.</p>

<h3>What should a beginner charge?</h3>
<p>Upwork's own guidance says most beginner VAs on the platform charge $10 to $20 an hour. Set your take-home first, then add the platform fee.</p>

<h3>Is there an official virtual assistant salary?</h3>
<p>No. There is no government occupation by that name. The closest official medians for May 2025 are $47,540 for administrative assistants and $76,590 for executive assistants.</p>

<h3>What do Upwork and Fiverr charge?</h3>
<p>Upwork charges a freelancer service fee of 0% to 15% per contract, plus $0.15 per Connect. Fiverr takes a 20% commission on your earnings.</p>

<h3>Do virtual assistant jobs pay more in the UK?</h3>
<p>ONS puts personal assistants and other secretaries at a median of &pound;25,233 for all employees and &pound;34,954 full-time, with the 90th percentile at &pound;48,675. Claims of &pound;60,000 and above sit outside that range.</p>

<h3>Is virtual assistant work growing?</h3>
<p>The occupation it sits closest to is projected to decline 2% to 2035, but with about 314,400 openings a year from turnover, and the remote share of admin work is high.</p>

<h3>What tax do I pay as a freelance VA?</h3>
<p>In the US, self-employment tax of 15.3% and a return once net earnings reach $400. In the UK, Self Assessment once you earn over &pound;1,000, and VAT registration above &pound;90,000.</p>

<h3>How do I avoid VA scams?</h3>
<p>Follow the FTC rule: honest employers never ask you to pay to get a job. Avoid upfront fees, equipment payments and cheque overpayments.</p>

<h2>People Also Search For</h2>

<h3>Virtual assistant hourly rate</h3>
<p>$10 to $20 an hour for beginners, on Upwork's own figures.</p>

<h3>Executive assistant salary</h3>
<p>$76,590 median in May 2025, against $47,540 for administrative assistants.</p>

<h3>Upwork fees</h3>
<p>0% to 15% freelancer service fee per contract, fixed when the contract starts.</p>

<h3>Fiverr commission</h3>
<p>20% of what you earn as a seller.</p>

<h3>Virtual assistant portfolio</h3>
<p>A simple page of sample systems and templates is enough to start.</p>

<h3>Virtual assistant skills</h3>
<p>Communication, organisation, Google Workspace or Microsoft 365, scheduling tools and CRM basics.</p>

<h3>Self-employment tax rate</h3>
<p>15.3% in the US, with filing required from $400 of net earnings.</p>

<h3>Work from home job scams</h3>
<p>The FTC's test: an honest employer never asks you to pay to get a job.</p>

<h2>More Job Guides</h2>

<p>Looking at remote and admin work? These cover it:</p>

<ul>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; the local market, rates and how to get paid.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the employed version of this job, priced from BLS.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; another remote route with real employers.</li>
    <li><a href="/blog/online-data-entry-jobs">Online Data Entry Jobs</a> &mdash; what the work pays and which offers are scams.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; the British remote market.</li>
    <li><a href="/blog/account-manager-jobs-in-usa">Account Manager Jobs in USA</a> &mdash; where client-facing admin experience can lead.</li>
    <li><a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a> &mdash; live dated pay in Pakistan, and why remote contracts pay several times local salaries.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; the entry-level remote role, and how to price it so it stays worth doing.</li>
    <li><a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> &mdash; dated Pakistani pay, and the cold-outreach laws no other guide mentions.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using BLS Occupational Outlook Handbook and OEWS data, ONS ASHE 2025, Upwork and Fiverr published fees, IRS and GOV.UK tax guidance and FTC consumer advice. Rates, fees and thresholds change. Always check the current rules before you rely on them.</p>
HTML;
    }
}
