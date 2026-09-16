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
 * "Call Center Jobs in Pakistan" — the entry hub for one of the most common
 * first formal jobs in Pakistan. The virtual assistant, data entry and online
 * jobs guides own the freelance side; this one owns the call-centre reality:
 * inbound vs outbound, what campaigns really pay against the minimum wage,
 * where the jobs cluster, the export numbers behind the hiring, and the scam
 * patterns the FIA has warned about.
 *
 * Corrections to the draft (checked September 2026 against State Bank of
 * Pakistan export data reported by ProPakistani/Business Recorder, provincial
 * minimum-wage notifications, Glassdoor/ERI salary data and FIA advisories):
 *
 * 1. The draft's "$328 million" is correct but needs context. That is the
 *    call-centre/BPO segment's export earnings for FY2024-25 (up from $263
 *    million), per the State Bank of Pakistan. It is a small slice of the
 *    country's total IT and IT-enabled services exports, which hit a record
 *    $3.8 billion the same year. The two must not be conflated.
 *
 * 2. The draft's entry-level local band starts at PKR 30,000 a month. That is
 *    below Pakistan's legal minimum wage, which was PKR 37,000 in 2024-25 and
 *    rose to PKR 40,000 for 2025-26 in Sindh and Punjab. The floor is raised
 *    accordingly and the minimum wage is named.
 *
 * 3. The draft says top agents reach "PKR 1 million or more in strong months".
 *    That is a rare best case for elite commission closers, not a norm. Top
 *    international sales agents more typically clear PKR 200,000+ in a strong
 *    month, with about PKR 405,600 at the 90th percentile. It is framed as an
 *    exception, not a benchmark.
 *
 * 4. The draft says "most CRMs are not Mac-compatible". Mainstream CRMs such
 *    as Salesforce and Zendesk are browser-based and run on a Mac. The real
 *    reason employers require Windows is that the predictive dialers and VoIP
 *    softphones on many international campaigns are Windows-only. A wired
 *    connection matters as much as raw speed, and ~25 Mbps is the common floor.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CallCenterJobsPakistanBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://pk.indeed.com/q-pakistan-call-center-jobs.html';

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
        $title = 'Call Center Jobs in Pakistan';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Pakistan\'s call centres earned $328 million from foreign clients in 2024-25, but the PKR 30,000 entry floor is below the minimum wage, PKR 1 million months are a rare exception, and the real blocker is Windows-only dialers, not Mac-incompatible CRMs.',
                'content' => $content,
                'featured_image' => 'blogs/call-center-jobs-in-pakistan.jpg',
                'tags' => 'call center jobs in pakistan, call center jobs karachi, call center jobs lahore, international call center jobs, bpo jobs pakistan, inbound outbound call center, call center salary pakistan, work from home call center pakistan, customer service jobs pakistan, night shift jobs pakistan',
                'meta_title' => 'Call Center Jobs in Pakistan 2026: Real Pay & Scams',
                'meta_description' => 'Call center jobs in Pakistan 2026: inbound vs outbound, real monthly pay by city and campaign, the equipment you need, and how to spot job scams.',
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
            ['name' => 'Pakistan BPO & Call Centre Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'pk-call-center-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Nationwide', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Call Center Agent — Inbound, Outbound and International Campaigns, Pakistan BPOs',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time shifts; international campaigns commonly run night shifts to match US and UK hours',
                'language' => 'English, Urdu',
                // Pay ranges widely by campaign, city and commission, so no
                // single figure is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Inbound, outbound and international call centre roles with Pakistani BPO companies in Karachi, Lahore, Islamabad and Faisalabad.',
                'seo_keywords' => 'call center jobs in pakistan, international call center jobs, bpo jobs pakistan, call center jobs karachi, call center jobs lahore',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>BPO companies across Karachi, Lahore, Islamabad, Rawalpindi and Faisalabad hire agents for inbound customer support and outbound sales on both local and international campaigns. International accounts, mostly serving US and UK clients, run night shifts and pay more.</p>

<h3>What the work involves</h3>
<p>Taking or making calls, resolving queries or pitching a product, logging every interaction in a CRM, and meeting quality and, on sales campaigns, conversion targets.</p>

<h3>Requirements</h3>
<ul>
    <li>Clear spoken English, and often Urdu, with confident communication</li>
    <li>Basic computer literacy and comfort with CRM and dialer software</li>
    <li>Matric to Bachelor's education is commonly accepted; many roles train freshers for 4 to 7 days</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Above the minimum wage.</strong> Local campaigns start around the PKR 40,000 minimum wage; international campaigns commonly pay PKR 60,000 to 90,000 for freshers</li>
    <li><strong>Commission.</strong> Outbound sales agents add commission, so a strong closer earns well above base</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay a "registration" or "processing" fee,</strong> and confirm the company and its client are real before you share documents.</p>

<p><strong>Note:</strong> pay, shifts and campaign details are set by employers &mdash; not by JobGader. Confirm them with the company before accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Call centre work is still one of the fastest ways into Pakistan's formal job market, and 2026 has been a strong year for it: the sector earned record export revenue and keeps hiring English-speaking agents for international campaigns. But the pay, the equipment rules and the scam risks are all widely misreported. Before you apply, it helps to know what the work really pays against the minimum wage, which cities hire most, what a genuine remote role needs, and how to spot a fake offer.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/q-pakistan-call-center-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127477;&#127472; Browse Call Center Jobs in Pakistan &rarr;
    </a>
</div>

<h2>Inbound vs Outbound: Which Suits You</h2>

<p><strong>Inbound roles</strong> take calls from customers who need help, have a question or want to complain. Customer service, technical support and helpdesk work is steadier and better suited to beginners.</p>

<p><strong>Outbound roles</strong> make calls, usually for sales or follow-ups. They are higher pressure but often pay more once commission is added, which suits confident people who are not put off by rejection.</p>

<h2>What Call Centre Jobs Really Pay in 2026</h2>

<p>Pay depends on the campaign (local or international), the shift and experience. One correction first: Pakistan's minimum wage is <strong>PKR 40,000 a month</strong> for 2025-26 (up from PKR 37,000 in 2024-25), so a legitimate call-centre job should not pay below it. The bands below start at that floor:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Experience / campaign</th>
            <th style="padding:10px;text-align:left;">Approx. monthly (PKR)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Entry level, local campaign</td><td style="padding:10px;">40,000 &ndash; 60,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Entry level, international campaign</td><td style="padding:10px;">60,000 &ndash; 90,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">1 to 2 years' experience</td><td style="padding:10px;">90,000 &ndash; 140,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Senior / team lead</td><td style="padding:10px;">140,000 &ndash; 250,000+</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Sales (base + commission)</td><td style="padding:10px;">100,000 &ndash; 180,000+</td></tr>
    </tbody>
</table>
</div>

<p><strong>About the "PKR 1 million a month" claim.</strong> It circulates a lot, but it is a rare best case for elite closers on high-ticket US accounts in an exceptional month. A strong top agent more realistically clears PKR 200,000 or more, with about PKR 405,600 at the 90th percentile. Treat a million-rupee month as the exception, not the plan.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/call-center-jobs-in-pakistan-team.jpg"
         alt="A row of Pakistani call centre agents wearing headsets at their computers, a woman in a green shalwar kameez in front, Minar-e-Pakistan and a Pakistan flag visible in the office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Export Boom Behind the Hiring</h2>

<p>The hiring is real, and the numbers explain why, but they are often quoted wrongly. Two separate figures matter, both from the State Bank of Pakistan for FY2024-25:</p>

<ul>
    <li><strong>The call-centre and BPO segment earned $328 million</strong> from foreign clients, up from $263 million the year before.</li>
    <li><strong>Total IT and IT-enabled services exports hit a record $3.8 billion</strong> the same year. The call-centre figure is a slice of this, not the whole thing, so do not confuse the two.</li>
</ul>

<p>More than 1,000 call centres are registered with the Pakistan Software Export Board, with many more operating alongside software houses and agencies. Industry bodies estimate the wider sector employs over a million people, though that is an association estimate, not a government census.</p>

<h2>Which Cities Have the Most Jobs</h2>

<ul>
    <li><strong>Karachi.</strong> The largest hub, with the biggest talent pool and the most inbound and outbound international campaigns.</li>
    <li><strong>Lahore.</strong> A fast-growing second hub with strong demand for both local and international roles.</li>
    <li><strong>Islamabad / Rawalpindi.</strong> Competitive pay, with some employers starting entry-level international roles around PKR 75,000.</li>
    <li><strong>Faisalabad.</strong> An emerging hub, especially for night-shift English and Urdu campaigns.</li>
</ul>

<h2>International Campaigns and Remote Work: What to Know</h2>

<p>International campaigns pay noticeably more than local ones, but legitimate ones come through established BPO firms with a verifiable client, not WhatsApp groups. For a remote or work-from-home role, most genuine employers expect:</p>

<ul>
    <li><strong>A stable, wired connection.</strong> Around 25 Mbps is the common minimum, but a wired Ethernet link matters as much as raw speed for VoIP stability. 50 Mbps or more is preferred.</li>
    <li><strong>A USB noise-cancelling headset.</strong> Not optional &mdash; background noise on a live call is a serious problem at most BPOs.</li>
    <li><strong>A Windows 10 or 11 machine.</strong> Not because CRMs need it &mdash; Salesforce and Zendesk run in a browser on a Mac &mdash; but because the predictive dialers and softphones many campaigns use are Windows-only.</li>
    <li><strong>A quiet, dedicated workspace</strong> for the length of the shift.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/call-center-jobs-in-pakistan-agents.jpg"
         alt="Pakistani call centre agents in headsets working at laptops in a bright office, Faisal Mosque and a Pakistan flag visible through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Requirements to Apply</h2>

<ul>
    <li>Fluent spoken English, and often Urdu, with clear communication</li>
    <li>Basic computer literacy and comfort navigating CRM software</li>
    <li>Employers commonly prefer ages 18 to 32, but this is a preference, not a legal rule</li>
    <li>Matric to Bachelor's education is usually accepted; a degree is not always required</li>
    <li>Prior experience helps for technical and international campaigns, but many entry roles train freshers for 4 to 7 paid days</li>
</ul>

<h2>How to Apply for a Call Centre Job</h2>

<ol>
    <li><strong>Choose inbound or outbound</strong> based on how you handle sales pressure versus steady support work.</li>
    <li><strong>Target international campaigns</strong> if your English is strong and you want higher pay.</li>
    <li><strong>Use verified sources</strong> &mdash; Indeed Pakistan, LinkedIn and company career pages, not unverified group chats.</li>
    <li><strong>Keep a simple resume</strong> that leads with communication skills, computer literacy and any relevant experience.</li>
    <li><strong>Prepare for a spoken assessment</strong> &mdash; most interviews test English and basic problem-solving on the spot.</li>
    <li><strong>Verify the employer</strong> and the campaign's client before you accept or hand over documents.</li>
</ol>

<h2>How to Avoid Call Centre Job Scams</h2>

<p>Legitimate employers never charge an upfront "registration" or "processing" fee, and never guarantee a job before an interview. If a recruiter asks for payment, walk away.</p>

<ul>
    <li><strong>Beware fake overseas offers.</strong> The FIA has repeatedly warned that fake overseas call-centre and IT job offers are used to traffic Pakistanis into scam compounds in Southeast Asia, where passports are seized and victims forced into cyber-fraud.</li>
    <li><strong>Verify any foreign offer</strong> through the Bureau of Emigration and Overseas Employment or the nearest Protector of Emigrants office, and report suspicious offers to the FIA.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Do call centre jobs in Pakistan require a degree?</h3>
<p>No. Most entry-level roles accept Matric to Bachelor's level. Strong spoken English and communication matter more than formal education, especially on international campaigns.</p>

<h3>Which pays more, inbound or outbound?</h3>
<p>Outbound sales roles generally pay more once commission is added, but they carry more pressure. Inbound roles offer steadier, lower-stress pay.</p>

<h3>What does a fresher earn in an international call centre?</h3>
<p>Entry-level international campaign roles typically pay PKR 60,000 to 90,000 a month, against PKR 40,000 to 60,000 for local campaigns.</p>

<h3>Is PKR 30,000 a month legal for a call centre job?</h3>
<p>No. Pakistan's minimum wage is PKR 40,000 a month for 2025-26 (PKR 37,000 in 2024-25), so a full-time role should not pay below it.</p>

<h3>Can top agents really earn PKR 1 million a month?</h3>
<p>Only rarely. That is a best case for elite commission closers in an exceptional month. A strong top agent more typically clears PKR 200,000 or more.</p>

<h3>Do I need a Windows laptop, or is a Mac fine?</h3>
<p>Many international campaigns require Windows because their dialers and softphones are Windows-only, even though mainstream CRMs run on a Mac. Check the tools before you buy hardware.</p>

<h3>Are remote call centre jobs in Pakistan legitimate?</h3>
<p>Genuine remote roles exist through established BPOs, but they need reliable wired internet, a proper headset and a verified employer. Avoid listings promising high pay for little work.</p>

<h3>How do I avoid call centre job scams?</h3>
<p>Never pay a fee, never trust a guaranteed job, and verify the company and its client. For overseas offers, check with the Bureau of Emigration and report suspicious ones to the FIA.</p>

<h2>People Also Search For</h2>

<h3>Call center jobs in Karachi</h3>
<p>The largest concentration of BPO employers, running inbound and outbound international campaigns at scale.</p>

<h3>Call center jobs in Lahore</h3>
<p>A fast-growing hub with strong demand for both local and international campaign roles.</p>

<h3>International call center jobs Pakistan</h3>
<p>Night-shift campaigns for US and UK clients, paying PKR 60,000 to 90,000 for freshers and more with commission.</p>

<h3>Night shift jobs in Pakistan</h3>
<p>International call-centre campaigns run overnight to match client hours, one of the most common night-shift roles.</p>

<h3>Work from home call center jobs Pakistan</h3>
<p>Legitimate through established BPOs, needing a wired connection, a USB headset and a Windows machine for the dialer.</p>

<h3>Call center salary in Pakistan</h3>
<p>From the PKR 40,000 minimum wage locally to PKR 140,000 and above for experienced and team-lead roles.</p>

<h3>Inbound vs outbound call center</h3>
<p>Inbound is steadier support work; outbound is higher-pressure sales that pays more with commission.</p>

<h3>BPO jobs in Pakistan</h3>
<p>A sector that earned $328 million in call-centre exports in 2024-25 and keeps hiring across the major cities.</p>

<h2>More Job Guides</h2>

<p>Exploring other ways to earn in Pakistan? These cover them:</p>

<ul>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; remote support work for overseas clients, and what it pays.</li>
    <li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; an entry-level desk role, and how to avoid the scams around it.</li>
    <li><a href="/blog/online-jobs-in-pakistan">Online Jobs in Pakistan</a> &mdash; the wider remote and freelance market beyond the phones.</li>
    <li><a href="/blog/private-jobs-in-pakistan-for-fresh-graduates">Private Jobs in Pakistan for Fresh Graduates</a> &mdash; where call-centre work fits among first jobs.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the same skills for international remote employers.</li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a> &mdash; how the same role is paid and structured abroad.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and reflects publicly available hiring and pay trends as of 2026. Salaries, minimum-wage rates and campaign details change. Confirm current pay and verify any employer before accepting an offer or sharing personal documents.</p>
HTML;
    }
}
