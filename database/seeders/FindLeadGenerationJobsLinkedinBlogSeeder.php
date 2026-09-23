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
 * "How to Find Lead Generation Jobs on LinkedIn" — deliberately narrow. The
 * role, the pay and the compliance position live on the sibling Lead
 * Generation Assistant guide; this one covers only searching, presenting
 * yourself, and what not to do. Written that way because the supplied draft
 * duplicated the sibling almost entirely.
 *
 * Corrections to the draft (checked 22 September 2026):
 *
 * 1. The draft recommends Expandi, Waalaxy, Lemlist, Dripify and GetSales.
 *    LinkedIn's user agreement prohibits them and its help centre says accounts
 *    may be restricted or shut down. Those recommendations are replaced with a
 *    warning built from the vendors' own documentation, in which they concede
 *    automation violates the terms and one describes falsifying LinkedIn's
 *    detection response.
 *
 * 2. The "80% of B2B social leads" statistic is a March 2014 Oktopost
 *    infographic measuring its own customers, and its platform split includes
 *    Google+, which shut in 2019. Cut.
 *
 * 3. The "61% biggest challenge" statistic is from a 2013 vendor-sponsored
 *    LinkedIn group poll of 845 self-selected respondents. The current
 *    comparable figure is around 30 per cent. Cut.
 *
 * 4. "Outreach Specialist" returns US healthcare and nonprofit community roles,
 *    not prospecting. Cut. "Demand Generation Associate" is a marketing title;
 *    the outbound equivalent is Representative.
 *
 * 5. The draft advises cold-messaging hiring managers. A free account cannot
 *    message non-connections at all, and gets roughly three personalised
 *    invitation notes a month at 200 characters.
 *
 * 6. Sales Navigator is not a job-search product. Premium Career is, at a third
 *    of the price.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FindLeadGenerationJobsLinkedinBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.linkedin.com/jobs/';

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
        $title = 'How to Find Lead Generation Jobs on LinkedIn';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Searching the phrase lead generation misses most of the market, and the Pakistani listings use different titles again. Here is what to search, what a free account can actually do, and the tools that get accounts banned.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-find-lead-generation-jobs-on-linkedin.jpg',
                'tags' => 'lead generation jobs linkedin, linkedin job search, sdr jobs linkedin, linkedin open to work, linkedin automation ban, appointment setter jobs, linkedin premium career, job alerts linkedin',
                'meta_title' => 'How to Find Lead Generation Jobs on LinkedIn',
                'meta_description' => 'Find lead generation jobs on LinkedIn: the titles that actually work, what a free account allows, and the automation tools that get accounts banned.',
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
            ['name' => 'Employers Advertising on LinkedIn (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'linkedin-lead-gen-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Remote'],
            ['area' => 'Worldwide', 'country' => 'Remote']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );

        Job::updateOrCreate(
            [
                'position' => 'Lead Generation Roles Advertised on LinkedIn',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Pakistani SDR listings are typically night shift, around 6pm to 3am',
                'language' => 'English, since outreach and client calls are in English',
                // Pay for these roles is covered on the sibling guide, where it
                // can be presented with dated listings rather than a range.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Prospecting roles advertised on LinkedIn under titles including SDR, BDR, Appointment Setter and Lead Generation Executive.',
                'seo_keywords' => 'lead generation jobs linkedin, sdr jobs linkedin, appointment setter jobs, linkedin job search, bdr jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of prospecting roles advertised on LinkedIn, not a single vacancy and not a job advertised by JobGader. Applications are made on LinkedIn or on the employer's own site.</p>

<h3>Read this before you apply</h3>
<p>Searching the phrase "lead generation" alone misses most of these jobs. They are advertised as SDR, BDR, Appointment Setter, Market Development Representative and Lead Generation Specialist internationally, and as Lead Generation Executive, Business Development Executive or Online Bidder on Pakistani boards.</p>

<h3>What a free LinkedIn account can do</h3>
<p>You cannot message anyone you are not connected to unless they have Open Profile enabled. Personalised notes on connection requests are limited, currently documented at three a month with a 200 character cap. Plan around that rather than assuming unlimited outreach.</p>

<h3>The tools to avoid</h3>
<p>LinkedIn's user agreement prohibits scrapers, bots and browser extensions that automate activity, and its help centre states accounts may be restricted or shut down. Several employers ask contractors to run these tools from their own personal profile. The restriction attaches to the account that performed the activity, which is yours.</p>

<h3>Remote does not mean open to Pakistan</h3>
<p>Every LinkedIn job post carries a country. Employers can add work authorisation and visa screening questions, mark them as must-have, and automatically archive and reject candidates who do not pass.</p>

<p>Platform rules, eligibility and pay are set by LinkedIn and individual employers &mdash; not by JobGader. Never pay anyone to secure a job, and never run automation from an account you cannot afford to lose.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you have been searching LinkedIn for "lead generation jobs" and finding little, the problem is probably not the market. It is the phrase.</p>

<p>These roles are advertised under half a dozen other titles, and the Pakistani listings use a different vocabulary again. This guide covers three things: <strong>what to actually search</strong>, <strong>what a free account can really do</strong> (much less than most articles claim), and <strong>which tools will get your account restricted</strong> if an employer asks you to run them.</p>

<p>For what the job involves and what it pays, we have a separate guide on <a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> with dated Pakistani salary listings. This page is about the search.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-find-lead-generation-jobs-on-linkedin-search.jpg" alt="LinkedIn search results showing a list of candidate profiles being reviewed on a laptop" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">The same job is advertised under at least eight different titles. Searching one of them finds one slice of the market.</figcaption>
</figure>

<h2>The Titles That Actually Work</h2>

<p>We checked which titles genuinely return prospecting work. Some commonly recommended ones do not.</p>

<h3>Search these</h3>

<ul>
    <li><strong>SDR</strong> and <strong>Sales Development Representative</strong> &mdash; the strongest of the set, used by Notion, Atlassian, Cisco, Salesforce, Oracle and LinkedIn itself.</li>
    <li><strong>BDR</strong> and <strong>Business Development Representative</strong> &mdash; the same function under a different house style.</li>
    <li><strong>Appointment Setter</strong> &mdash; the biggest omission from most guides. High volume, heavily remote, genuinely entry level.</li>
    <li><strong>Lead Generation Specialist</strong> &mdash; the most literal title of all, and often skipped.</li>
    <li><strong>Market Development Representative</strong> &mdash; one verified job description describes spending "roughly 80% of the time on the phone prospecting new clients".</li>
    <li><strong>Business Development Associate</strong> &mdash; broader than pure prospecting, but worth including.</li>
    <li><strong>Virtual Assistant &ndash; Lead Generation</strong> &mdash; the framing most often open to offshore applicants.</li>
</ul>

<h3>Do not bother with these</h3>

<p><strong>"Outreach Specialist" is a trap.</strong> On LinkedIn this search is dominated by American <em>healthcare and nonprofit community outreach</em> roles &mdash; Cityblock Health, Wounded Warrior Project, Oak Street Health. They need local presence and often a clinical or social-services background. You will waste days on it. The word works as a modifier inside a title, such as "SDR &ndash; US Outreach", never on its own.</p>

<p><strong>"Growth Associate" is a real title for the wrong job</strong> &mdash; growth marketing, meaning paid media and analytics, not prospecting.</p>

<p><strong>"Demand Generation Associate" is marketing, not sales.</strong> A verified job description for one covers email campaigns, paid ads, webinars and landing pages, with no cold calling and no meeting quota. Change one word, though, and it becomes useful: <strong>Demand Generation <em>Representative</em></strong> is a genuine outbound title, used by Amazon's cloud sales centre among others. <strong>Associate is marketing. Representative is sales.</strong></p>

<h2>The Pakistani Titles Nobody Lists</h2>

<p>This is where the international advice quietly fails. Search the US titles alone and you will miss most of what is actually hiring in Lahore and Karachi. The local vocabulary is different:</p>

<ul>
    <li><strong>Lead Generation Executive</strong> &mdash; the dominant local title.</li>
    <li><strong>Business Development Executive (Lead Generation)</strong> &mdash; the most common umbrella, sometimes as "Business Development Executive (LinkedIn/Upwork)".</li>
    <li><strong>Business Development Lead Generation Officer</strong> &mdash; note "Officer", a South Asian convention that barely exists in US listings.</li>
    <li><strong>Online Research and Lead Generation Executive</strong>.</li>
    <li><strong>Upwork Bidder</strong>, <strong>Online Bidder</strong>, <strong>Bidding Expert</strong> &mdash; genuinely Pakistan-specific, with no US equivalent at all.</li>
</ul>

<p>One thing worth knowing before you apply: <strong>SDR is used in Pakistan, but it usually means night shift.</strong> Real listings include "Sales Development Representative (SDR) &ndash; US" in Lahore running 6:00pm to 3:00am, and "SDR &ndash; US Outreach (Night Shift)" in Karachi running 9:00pm to 5:30am. The clients are American, so the hours follow them. Factor that in rather than discovering it at the offer stage.</p>

<h2>What LinkedIn's Job Search Can Actually Do</h2>

<p>LinkedIn documents eight job filters: <strong>location, date posted, Easy Apply, company, experience level, employment type, under 10 applicants, and in your network</strong>. A workplace type filter for on-site, remote and hybrid also exists, added in 2021.</p>

<p>Two that repay attention:</p>

<ul>
    <li><strong>Date posted.</strong> Set it to past 24 hours or past week. Applying to a month-old posting on a popular role is close to pointless.</li>
    <li><strong>Under 10 applicants.</strong> Exactly what it says, and the single most useful filter on the page when it is available to you.</li>
</ul>

<p>A caution we would rather give than skip: LinkedIn's help centre documents only those eight, while the live "all filters" panel shows more, and some advanced filtering is sold as part of Premium. <strong>Open the panel yourself and see what your own account offers</strong> before planning around any particular filter.</p>

<h3>Job alerts: useful, but slower than you think</h3>

<p>Run a search, then switch the job alerts toggle on. You can hold <strong>up to 20 alerts</strong> at once, and they arrive <strong>daily or weekly</strong>.</p>

<p><strong>There is no instant or real-time option.</strong> Daily is as fast as it gets, so if you are relying on being early to a posting, alerts alone will not do it. Running the search manually with the date filter set to past 24 hours will.</p>

<h2>What a Free Account Really Allows</h2>

<p>Here is where most advice on this topic collapses. The standard tip is "message hiring managers directly". On a free account, you largely cannot.</p>

<p>LinkedIn's own wording: <em>"If you have a Basic (free) account, then you can only directly message LinkedIn members that you're connected to."</em> To message a non-connection you need InMail, which is a paid feature.</p>

<p><strong>The one free exception is worth knowing:</strong> <em>"If a member has the Open Profile Premium feature enabled, you can message them for free."</em> That is a setting the recipient switches on, so you cannot control it, but you can check for it before assuming you are locked out.</p>

<p>And the personalised note on a connection request, which is the usual workaround, is also capped. LinkedIn's most recently updated help page states you can add a personalised message to <strong>up to three connection requests per month</strong>, at <strong>up to 200 characters</strong>. An older page says five and 300 characters, so LinkedIn's own documentation disagrees with itself &mdash; assume the lower figure and check your account.</p>

<p><strong>What this means in practice.</strong> You are not sending twenty personalised messages a week. You get roughly three notes a month, of two hundred characters. So:</p>

<ul>
    <li><strong>Spend them deliberately</strong> on the three people most worth reaching, not the first three you find.</li>
    <li><strong>Connect first, message after acceptance.</strong> Once connected, messaging is free and unlimited.</li>
    <li><strong>Reply properly to recruiters who contact you first</strong> &mdash; that conversation costs you nothing and is already open.</li>
    <li><strong>Two hundred characters is about two sentences.</strong> Name the role, name one relevant number from your own work, stop.</li>
</ul>

<h3>Premium Career is not Sales Navigator</h3>

<p>This confusion is expensive, so be clear about it:</p>

<ul>
    <li><strong>Premium Career</strong>, around <strong>USD 39.99 a month</strong>, is the job seeker product. Five InMail credits a month, applicant insights, advanced search filters, LinkedIn Learning.</li>
    <li><strong>Sales Navigator Core</strong>, around <strong>USD 119.99 a month</strong>, is a <em>sales</em> tool. It has no job search features at all.</li>
</ul>

<p><strong>If you are buying a subscription to find a job, Sales Navigator is three times the price and does nothing for you.</strong> The only reason to touch it is if an employer buys it so you can do the work.</p>

<p>Given that USD 39.99 is a substantial monthly sum in Pakistan, treat Premium as optional rather than necessary. And <strong>avoid the "cheap LinkedIn Premium" resellers</strong> advertised locally: account sharing breaches LinkedIn's user agreement, which is itself grounds for restriction.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-find-lead-generation-jobs-on-linkedin-network.jpg" alt="LinkedIn profile network illustration showing connections spreading between professionals" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Connect first, message after acceptance. Once connected, messaging costs nothing.</figcaption>
</figure>

<h2>Open to Work, and the Risk It Carries</h2>

<p>Turn it on from your profile under "Open to" and "Finding a new job". There are two settings, and the difference matters:</p>

<ul>
    <li><strong>All LinkedIn members</strong> &mdash; adds the green #OpenToWork photo frame, and includes <em>"recruiters and people at your current company"</em>.</li>
    <li><strong>Recruiters only</strong> &mdash; visible to LinkedIn Recruiter users. LinkedIn adds its own caveat, worth quoting exactly: <em>"we take steps to prevent LinkedIn Recruiter users who work at your current company from seeing your shared career interests, but we can't guarantee complete privacy."</em></li>
</ul>

<p>You will see claims elsewhere that the public frame makes you a certain percentage more likely to receive messages. <strong>We could not find that figure in any LinkedIn source</strong>, including its own launch announcement, so we are not repeating it.</p>

<p>What we can tell you is the downside, which nobody mentions: <strong>the #OpenToWork frame marks you as a target.</strong> Security researchers have documented fake recruiter accounts buying InMail specifically to phish job seekers, with people displaying the open-to-work badge among the favoured targets, using kits designed to capture both the password and the two-factor code.</p>

<p>That is not a reason to avoid the feature. It is a reason to keep two-factor authentication on and to treat every unexpected recruiter message as unverified until you have checked the company yourself.</p>

<h2>Your Profile Is the Work Sample</h2>

<p>For this role specifically, the profile is not a formality. You are applying for a job about written persuasion at scale, so the profile <em>is</em> the audition.</p>

<ul>
    <li><strong>Put a number in the headline.</strong> "Booked 40+ qualified meetings a month through LinkedIn outreach" outperforms "Lead Generation Specialist" because it is a claim rather than a label.</li>
    <li><strong>Name the actual stack</strong> you have used &mdash; a CRM, a data source, a spreadsheet discipline. Employers screen on tool familiarity more than on years.</li>
    <li><strong>Show one campaign properly.</strong> Who you targeted, how you found them, what you sent, what came back. If you have never had a client, run one small campaign for a real local business and report the real numbers, including the bad ones.</li>
    <li><strong>Write it in the English you will actually send.</strong> A profile full of errors answers the only question the employer has.</li>
</ul>

<h2>The Tools the Guides Recommend, and Why We Do Not</h2>

<p>Most articles on this job hand you a list: Expandi, Waalaxy, Lemlist, Dripify, GetSales. We are going to do the opposite, because the evidence here is not close.</p>

<h3>What LinkedIn says</h3>

<p>LinkedIn's user agreement prohibits using <em>"software, devices, scripts, robots or any other means or processes (such as crawlers, browser plugins and add-ons...) to scrape or copy the Services"</em>, and separately bans <em>"bots or other unauthorized automated methods to access the Services, add or download contacts, send or redirect messages"</em>.</p>

<p>Its prohibited-software page states that members <em>"risk having their accounts restricted or shut down"</em>, and that <em>"any prohibited tools they're using may become non-operational without notice."</em></p>

<h3>The number everyone quotes does not exist</h3>

<p>You will read that LinkedIn allows 100 connection requests a week. <strong>LinkedIn publishes no numeric limit anywhere.</strong> What it publishes is this, in its list of reasons an account gets restricted:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"If you send an excessive number of invitations and we suspect the use of an automation tool, we may suspend or restrict your account."</p>

<p>Note the wording. Not "if you exceed a limit". <strong>If we suspect the use of an automation tool.</strong></p>

<h3>The vendors admit it themselves</h3>

<p>This is the part that settles it. These are the tools' own published words:</p>

<ul>
    <li><strong>lemlist:</strong> <em>"Technically, automation violates LinkedIn's terms of service."</em> And: <em>"Accounts that behave like bots get restricted or banned."</em></li>
    <li><strong>Expandi:</strong> <em>"Warming up your account is a precaution that you should take to prevent your LinkedIn account from getting restricted or banned."</em> Its warm-up starts at five actions a day.</li>
    <li><strong>Waalaxy</strong> describes building <em>"an undetectable extension"</em>, explaining that its developers created a security feature that intercepts LinkedIn's detection message and replaces it with one saying <em>"Everything is fine, this user is not using any of the banned extensions"</em>, so that <em>"LinkedIn will never know."</em></li>
    <li><strong>Dripify</strong> concedes <em>"no automatization can overcome the limits set by LinkedIn"</em>, and warns you not to browse LinkedIn yourself while it runs because the two sign-ins <em>"could be flagged as suspicious activity."</em></li>
    <li><strong>GetSales</strong> advertises its ban rate as <em>"as low as 0.5%, versus industry averages of 5-10%"</em> &mdash; a vendor conceding that this industry bans between one in twenty and one in ten accounts.</li>
</ul>

<p>Read the Waalaxy one again. A vendor is publicly describing how it falsifies the answer LinkedIn's own detection code expects &mdash; which also confirms it is on the banned list.</p>

<h3>And the daily limits they recommend are made up</h3>

<p>If these tools knew LinkedIn's threshold, they would agree on it. They do not:</p>

<ul>
    <li>Expandi: a warm-up ceiling of <strong>21 actions a day</strong>.</li>
    <li>lemlist: about <strong>20 invitations a day</strong>.</li>
    <li>GetSales: <strong>24 to 35 a day</strong>.</li>
    <li>Dripify: <strong>75 to 100 a day</strong>.</li>
    <li>Waalaxy: <strong>80 to 100 a day</strong>.</li>
</ul>

<p>That is a <strong>twentyfold spread for the same platform under the same rules</strong>. They also contradict each other on architecture, each insisting the other kind is the detectable one. None of them has LinkedIn's data.</p>

<h3>LinkedIn wins these fights</h3>

<p>The belief that "a court ruled scraping is legal" is a misreading of the hiQ Labs case. The appeal court addressed only whether scraping public profiles breaches a specific American computer-misuse statute. <strong>On the contract question, LinkedIn won outright:</strong> hiQ was found to have breached the user agreement, and in December 2022 a consent judgment imposed <strong>USD 500,000</strong> and a permanent injunction requiring it to delete the data and code. hiQ stopped operating.</p>

<p>It was not an isolated result. LinkedIn settled against Mantheos in 2022, and against Proxycurl in 2025 &mdash; that service <strong>shut down its API in July 2025</strong> under a permanent injunction. In March 2025 several lead-generation vendors, Apollo among them, had their LinkedIn company pages removed over scraping terms.</p>

<p><strong>The distinction to carry:</strong> whether scraping is a <em>crime</em> is one question. Whether LinkedIn can <em>close your account</em> is a contract question, and on that it has not lost.</p>

<h3>The question to ask in the interview</h3>

<p>Here is why all of this is your problem and not an abstraction.</p>

<p>A large share of these jobs will ask you to run this software <strong>from your own personal LinkedIn profile</strong>, because the employer does not want its own account restricted. The restriction attaches to the account that performed the activity. The employer keeps the leads; <strong>you lose the profile.</strong></p>

<p>For someone in Pakistan whose LinkedIn account is their primary route to international work, that is not a policy violation. It is the loss of the channel itself.</p>

<p>So ask, in these words: <strong>"Whose LinkedIn account will the outreach run from, and whose tooling?"</strong> A serious employer provides its own accounts, its own seats and its own sending domains. An employer that expects you to absorb the risk has told you what kind of employer it is.</p>

<h2>"Remote" Does Not Mean Open to Pakistan</h2>

<p>Every LinkedIn job post carries a country, and LinkedIn does not support regions larger than a country. There is no borderless worldwide remote posting.</p>

<p>LinkedIn states the position plainly: <em>"Companies are only able to employ workers who are legally eligible to work in the country in which the job is based."</em></p>

<p>And employers can enforce it automatically. Screening questions include <strong>work authorisation</strong> and <strong>visa status</strong>, and an employer can mark them must-have and <em>"automatically archive candidates who don't pass your screening questions and send an automatic rejection email."</em> That is often why a well-matched application is rejected within minutes.</p>

<p><strong>So read the location and the screening questions before you write anything.</strong> Genuinely offshore-friendly roles usually say so, and often use contractor or virtual assistant framing.</p>

<h2>Spotting Fake Recruiters</h2>

<p>LinkedIn publishes its own red flags. The ones that matter most here:</p>

<ul>
    <li><strong>Any financial request.</strong> LinkedIn's own instruction: do not provide payment or account credentials as part of an application.</li>
    <li><strong>Pressure to move off LinkedIn</strong> to WhatsApp or Telegram early.</li>
    <li><strong>A generic email domain</strong> &mdash; Gmail or Yahoo rather than the company's own.</li>
    <li><strong>Requests for identity or banking details</strong> before any formal hiring.</li>
    <li><strong>Company profiles with incomplete details and few connections.</strong></li>
    <li><strong>Being asked to buy equipment</strong> and ship it or be reimbursed later.</li>
</ul>

<p>The US Federal Trade Commission has documented this exact pattern on LinkedIn: a direct message, a fake virtual interview, an offer, then an invoice for equipment or training. It names appointment-setter roles specifically among the jobs used as bait, and states that <em>"honest employers will never ask you to pay to get a job."</em></p>

<p><strong>To report on LinkedIn:</strong> for a job, open it and use More then Report this job, choosing spam or scam. For a profile, use More then Report, choosing impersonation or not a real person. LinkedIn states that reported members are not told who reported them.</p>

<p>Also worth knowing: LinkedIn is piloting verification badges on some job posts, but it says the badge <em>"does not mean you are more likely to be hired or that the job is a better fit for you"</em>, and most jobs will not have one. <strong>The absence of a badge proves nothing.</strong></p>

<p><strong>In Pakistan</strong>, cybercrime complaints go to the National Cyber Crime Investigation Agency at nccia.gov.pk. The Pakistan Telecommunication Authority also takes complaints on 0800-55055 and through its online complaint portal. Confirm both in your browser before you need them.</p>

<h2>A Search Routine That Works</h2>

<p><strong>Start here:</strong> <a href="https://www.linkedin.com/jobs/" rel="nofollow noopener" target="_blank">https://www.linkedin.com/jobs/</a>, then work through this every few days rather than once a month.</p>

<ol>
    <li><strong>Run five or six searches, not one.</strong> SDR, BDR, Appointment Setter, Lead Generation Specialist, Market Development Representative, and the local titles.</li>
    <li><strong>Set date posted to past 24 hours or past week</strong> every time.</li>
    <li><strong>Save each as a job alert</strong>, up to twenty, and accept that they arrive daily rather than instantly.</li>
    <li><strong>Read the location and screening questions first</strong>, before writing anything.</li>
    <li><strong>Connect rather than message.</strong> Save your three monthly personalised notes for the roles that are genuinely worth them.</li>
    <li><strong>Ask whose account the automation runs on</strong> at the first opportunity.</li>
    <li><strong>Apply on the employer's own site too</strong> where one exists; Easy Apply is convenient and crowded.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What should I search instead of "lead generation jobs"?</h3>
<p>Search SDR, BDR, Appointment Setter, Lead Generation Specialist, Market Development Representative and Business Development Associate. In Pakistan also try Lead Generation Executive, Business Development Executive (Lead Generation) and Online Bidder, which have no US equivalent.</p>

<h3>Can I message a hiring manager on a free LinkedIn account?</h3>
<p>Only if you are already connected, or if they have the Open Profile feature enabled. Otherwise messaging a non-connection needs InMail, which is a paid feature. A free account also gets a limited number of personalised notes on connection requests.</p>

<h3>How many personalised connection notes do I get?</h3>
<p>LinkedIn's most recently updated help page says up to three a month, at up to 200 characters each. An older page says five at 300 characters, so its own documentation disagrees. Plan for the lower figure and check what your account allows.</p>

<h3>Do I need LinkedIn Premium to find these jobs?</h3>
<p>No. If you do buy, buy Premium Career at around USD 39.99 a month, not Sales Navigator at around USD 119.99. Sales Navigator is a sales tool with no job search features, and costs three times as much.</p>

<h3>Are LinkedIn automation tools safe to use?</h3>
<p>LinkedIn prohibits them and says accounts may be restricted or shut down. The vendors concede the point themselves, one stating outright that automation violates LinkedIn's terms, another describing how it falsifies LinkedIn's detection response. Their recommended daily limits differ by a factor of twenty.</p>

<h3>How many connection requests can I send per week?</h3>
<p>LinkedIn publishes no number. It says restrictions follow sending many invitations in a short time, having invitations ignored, or sending an excessive number where it suspects an automation tool. Restrictions typically last about a week.</p>

<h3>Does the Open to Work badge have a downside?</h3>
<p>Yes. It is visible to everyone including your current employer, and security researchers have documented fake recruiters targeting job seekers who display it with phishing designed to capture passwords and two-factor codes. Keep two-factor authentication on.</p>

<h3>Why do remote jobs reject me instantly?</h3>
<p>Every LinkedIn job is posted against a country, and employers can add work authorisation and visa screening questions marked must-have. Failing them can trigger an automatic archive and rejection email within minutes of applying.</p>

<h2>People Also Search For</h2>

<h3>LinkedIn job alerts not working</h3>
<p>Alerts arrive daily or weekly, never instantly, and you can hold up to twenty at once. If you need to be early to a posting, run the search manually with date posted set to past 24 hours instead.</p>

<h3>LinkedIn Easy Apply vs company website</h3>
<p>Easy Apply is convenient and therefore crowded. Where an employer has its own careers page, applying there as well costs little and puts you in a smaller pool.</p>

<h3>Under 10 applicants LinkedIn filter</h3>
<p>It filters to postings with fewer than ten applicants so far, which is the most useful filter on the page. Some advanced filtering is sold with Premium, so check what your own account shows.</p>

<h3>Is LinkedIn Sales Navigator worth it for job seekers</h3>
<p>No. It is a sales prospecting tool with no job search features, at around USD 119.99 a month. Premium Career at about USD 39.99 is the job seeker product, and even that is optional.</p>

<h3>LinkedIn account restricted automation</h3>
<p>LinkedIn restricts accounts where it suspects an automation tool, and says prohibited tools may stop working without notice. Restrictions typically last about a week, and recovery requires disabling the software.</p>

<h3>Appointment setter jobs remote</h3>
<p>One of the most accessible entry points in this field and a title most guides omit. High volume, heavily remote, and usually advertised without a degree requirement.</p>

<h3>Night shift SDR jobs Lahore</h3>
<p>Pakistani SDR roles are typically night shift because the clients are American. Real listings run from around 6:00pm to 3:00am in Lahore and 9:00pm to 5:30am in Karachi.</p>

<h3>LinkedIn fake recruiter report</h3>
<p>Open the profile, choose More then Report, and select impersonation or not a real person. For a job posting use More then Report this job, then spam or scam. LinkedIn does not tell the reported member who reported them.</p>

<h2>More Job Guides</h2>

<p>These pair with this one:</p>

<ul>
    <li><a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> &mdash; what the job involves, dated Pakistani pay, and the cold outreach laws.</li>
    <li><a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a> &mdash; a remote role with similar entry requirements and no account risk.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; the lowest-barrier remote work on this site.</li>
    <li><a href="/blog/how-to-become-a-remote-virtual-assistant">How to Become a Remote Virtual Assistant</a> &mdash; a wider remote role with the same payment questions.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic starting points, ranked.</li>
    <li><a href="/blog/b2b-lead-research-jobs">B2B Lead Research Jobs</a> &mdash; the research craft behind a prospect list, and why a verified email often is not.</li>
    <li><a href="/blog/contact-list-building-jobs">Contact List Building Jobs</a> &mdash; what the daily quotas really mean once you do the arithmetic.</li>
    <li><a href="/blog/appointment-setting-jobs">Appointment Setting Jobs</a> &mdash; the phone role these searches surface most, and what it really pays.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. LinkedIn's features, limits, pricing and documentation change frequently, and in places its own help pages disagree with each other; check your own account before relying on any specific limit. Platform rules and enforcement are set by LinkedIn, not by JobGader. Never pay anyone to secure a job, and never run automation from an account you cannot afford to lose.</p>
HTML;
    }
}
