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
 * "Lead Generation Assistant Jobs" — a role whose whole function is collecting
 * strangers' contact details and emailing them, written for an audience nobody
 * warns about the consequences of doing that badly.
 *
 * The draft omitted the legal position entirely. Three regimes govern this work
 * and one of them carries a per-email penalty; LinkedIn separately bans the
 * tools most of these jobs ask you to run, usually on the worker's own account.
 * That is the centre of this guide, because it is the part no competing article
 * carries and the part that can cost a reader their career.
 *
 * Corrections to the draft (checked 22 September 2026):
 *
 * 1. All three PKR claims fail. "PKR 90,000 remote" is a Lahore ONSITE advert
 *    whose range is 90,000 to 100,000. The "PKR 15,000 internship on a
 *    three-month probation" does not exist; no Pakistani advert reviewed put a
 *    probation rate in writing. The "Lahore, 6 months to 1 year" requirement
 *    comes from a Karachi advert that expired in October 2025.
 *
 * 2. The BLS figure is accurate but misapplied. Advertising sales agents is a
 *    poor match, and BLS projects it to decline 7 per cent to 2035. The draft
 *    also risks the common error of quoting the mean, $80,670, as the median.
 *
 * 3. The draft conflates assistant and SDR. Verified live adverts show a
 *    tenfold spread between them, so the guide separates the tiers.
 *
 * 4. Several tools the draft implies are free have no free tier at all.
 *
 * 5. ChatGPT citation artifacts are not published.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LeadGenerationAssistantJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.mustakbil.com/';

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
        $title = 'Lead Generation Assistant Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'This job asks you to collect strangers contact details and email them. Three laws govern that, one fines per email, and LinkedIn bans the tools most of these roles hand you. Here is the job, the real pay, and the risk.',
                'content' => $content,
                'featured_image' => 'blogs/lead-generation-assistant-jobs.jpg',
                'tags' => 'lead generation assistant jobs, lead generation jobs pakistan, sdr jobs pakistan, b2b prospecting, cold email law, apollo io, crm jobs, remote sales jobs',
                'meta_title' => 'Lead Generation Assistant Jobs: Skills and How to Apply',
                'meta_description' => 'Lead generation assistant jobs: dated Pakistani pay, the tools that are really free, the cold-email laws nobody mentions, and how to apply safely.',
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
            ['name' => 'Agencies, SaaS and Outbound Sales Teams (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'lead-gen-aggregated']
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
                'position' => 'Lead Generation Assistant, Remote and Pakistan',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Frequently night shift on Pakistani listings, to overlap US client hours',
                'language' => 'Written and spoken English, since outreach and calls are in English',
                // Advertised pay spans from intern rates to experienced SDR
                // packages, a tenfold range, and most employers publish none.
                // A single figure on an aggregated listing would mislead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Lead generation and B2B prospecting roles advertised across Pakistan and remotely, from intern level to experienced sales development.',
                'seo_keywords' => 'lead generation assistant jobs, lead generation jobs pakistan, sdr jobs pakistan, b2b prospecting jobs, remote sales jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of lead generation and B2B prospecting roles advertised across Pakistani job boards and remotely, not a single vacancy and not a job advertised by JobGader.</p>

<h3>Read this before you apply</h3>
<p>Two things every advert leaves out. First, cold outreach is regulated: US anti-spam law carries a penalty of up to USD 53,088 per email, and EU and UK rules impose disclosure and opt-out duties on lists you did not collect yourself. Second, LinkedIn's user agreement prohibits the scraping and automation tools many of these roles expect you to run, and the account that gets restricted is usually the worker's own.</p>

<h3>What the work involves</h3>
<ul>
    <li>Researching companies and decision-makers against an ideal customer profile.</li>
    <li>Building and cleaning B2B prospect lists, and verifying contact data.</li>
    <li>Maintaining CRM records and pipeline hygiene.</li>
    <li>Supporting email and LinkedIn outreach, and tracking replies.</li>
    <li>Basic lead qualification against the employer's stated criteria.</li>
    <li>Weekly reporting on activity and source performance.</li>
</ul>

<h3>Assistant or SDR?</h3>
<p>These are different jobs at different pay. An assistant researches, builds lists and keeps the CRM clean. A sales development representative carries a quota, cold calls and books meetings. Verified Pakistani adverts in September 2026 ranged from PKR 7,000 to 10,000 for outreach interns up to PKR 90,000 to 100,000 plus commission for an experienced onsite SDR in Lahore.</p>

<h3>Pay</h3>
<p>Set per employer, and most publish nothing. Where figures were published, remote roles ran from around PKR 20,000 to 30,000 at student level up to PKR 75,000 to 150,000 for experienced sales roles.</p>

<p>Requirements, pay and compliance obligations are set by individual employers and by the law of the country you are emailing &mdash; not by JobGader. Confirm terms in writing before starting, and never pay anyone to secure work.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every other guide to this job will tell you it is an easy way into sales. Before we get to that, here is what none of them mention.</p>

<p>This job asks you to <strong>collect strangers' contact details and email them</strong>. In the United States that is governed by a law carrying a penalty of <strong>up to USD 53,088 per email</strong>. In Europe you owe those people a disclosure within one month of taking their data. And LinkedIn's own user agreement <strong>bans the tools most of these jobs will hand you</strong> &mdash; usually to run on your own personal account.</p>

<p>The job is real, it pays, and Pakistanis do get hired. But you should walk in knowing what you are being asked to do, because the person who carries the risk is usually you.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/lead-generation-assistant-jobs-funnel.jpg" alt="Lead generation workflow showing email, LinkedIn, phone and web channels feeding a funnel into a list of qualified contacts" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Research, verify, qualify, record. The outreach is the visible part; the list is the job.</figcaption>
</figure>

<h2>Assistant or SDR? They Are Not the Same Job</h2>

<p>The single biggest mistake in articles on this subject is treating these as one role. Verified adverts show a <strong>tenfold pay difference</strong> between them.</p>

<ul>
    <li><strong>Lead Generation Assistant.</strong> Research, list building, data cleaning, CRM hygiene, sometimes first-touch outreach. No quota. Entry level.</li>
    <li><strong>Sales Development Representative (SDR).</strong> Carries a quota, cold calls, handles objections, books meetings for a closer. A sales job with sales pressure and sales money.</li>
</ul>

<p>When you read a listing, find the quota. If there is one, it is an SDR role whatever the title says, and it should be paid like one.</p>

<h2>What Pakistani Employers Are Actually Advertising</h2>

<p>Every figure below was live when we checked on 22 September 2026, from a named employer. We are dating them because the numbers circulating elsewhere are not: one widely repeated "6 months to 1 year, Lahore" requirement actually comes from a <strong>Karachi</strong> advert that <strong>expired in October 2025</strong>.</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Employer and role</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Mode</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Advertised PKR</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Experience</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">AAA Digital &mdash; SDR (Cold Caller), Lahore</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>Onsite</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">90,000 &ndash; 100,000 + commission</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">1 year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Nokhba.AI &mdash; Sales Executive, HR Tech / SaaS</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Remote</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">75,000 &ndash; 150,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">3 years</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Revora AI &mdash; Online Sales Agent</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Remote, part time</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">75,000 &ndash; 120,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Fresh graduate</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Freight Hop &mdash; Cold Caller / Outbound SDR</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Remote</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">60,000 &ndash; 80,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">1 year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">HomeCrestProperty &mdash; Real Estate Cold Caller (USA)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Remote</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">50,000 &ndash; 55,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">1 year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">DevCore System &mdash; Business Development Executive</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Remote</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">20,000 &ndash; 30,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Student</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Empire Digital &mdash; Cold Caller, Karachi</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Part time</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">10,000 &ndash; 15,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Under 1 year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Imaginex Dynamics &mdash; BD and Outreach Interns, Karachi</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Part time</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">7,000 &ndash; 10,000</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Intern</td>
        </tr>
    </tbody>
</table>

<p>Three things that table tells you, none of them the headline.</p>

<p><strong>The highest-paying role on it is onsite, not remote.</strong> The widely repeated claim that a remote lead-gen job pays PKR 90,000 is a misreading of this advert: it is onsite in Lahore, and it is a range topping out at 100,000.</p>

<p><strong>Most Pakistani employers publish no figure at all.</strong> Of sixty live LinkedIn Pakistan listings for this kind of role, none showed a salary. Anything claiming to be "the market rate" is drawn from the minority who chose to publish.</p>

<p><strong>Published ranges are advertising, not compensation.</strong> Some run as wide as PKR 100,000 to 500,000. Treat the bottom of a range as the realistic offer.</p>

<h2>About That American Salary Figure</h2>

<p>You will see "$64,820" quoted in articles on this job. It is a real number, and using it is still a mistake.</p>

<p>It comes from the US Bureau of Labor Statistics for <strong>advertising sales agents</strong> &mdash; people who sell advertising space against a quota. That is a closing role, not a research role. <strong>BLS has no occupation at all for lead generation, SDR or appointment setting</strong>, and saying so plainly is more honest than borrowing a number that does not fit.</p>

<p>Two further problems. The same BLS table shows a <em>mean</em> of $80,670, which several articles misquote as the median. And BLS projects advertising sales agents to <strong>decline 7 per cent between 2025 and 2035</strong> &mdash; an odd anchor for an article selling a growth career.</p>

<p>If you want a US comparison, the closest honest buckets are <strong>Sales and Related Workers, All Other</strong> at roughly $48,280, which is where an unclassified lead-gen assistant most likely lands, or <strong>Sales Representatives, Wholesale and Manufacturing</strong> at $72,080 for a full SDR path. And remember it is a <strong>US wage for a US worker</strong>. It is not what you will be offered.</p>

<h2>How You Actually Build a Lead List</h2>

<h3>1. Start from the ideal customer profile</h3>

<p>Get the employer's criteria in writing: industry, country, city, company size, job titles, technology used, revenue band. If they cannot give you this, they do not know who they are selling to, and no list you build will work.</p>

<h3>2. Find companies, then people</h3>

<p>Companies first, decision-makers second. Depending on the sector that is a founder, owner, marketing manager, operations manager, HR manager or procurement lead. <strong>The goal is fit, not volume.</strong> A hundred matched contacts beat a thousand random ones, and the thousand will damage the sender domain.</p>

<h3>3. Verify before you record</h3>

<p>Check the name, title, company and website. People change jobs constantly, and a list built six months ago is substantially wrong today.</p>

<h3>4. Keep the CRM consistent</h3>

<p>Consistent fields matter more than clever ones: Company, Website, Contact, Job Title, Location, Industry, Email, Lead Source, Status, Notes. <strong>Record the source of every contact.</strong> That is good practice, and under EU rules it is also a legal obligation, as the next section explains.</p>

<h3>5. Qualify against stated criteria</h3>

<p>Does the company match the target industry and market? Does the contact hold a relevant role? Is the information current? Does it meet the employer's own definition of qualified?</p>

<p><strong>Get that definition from the sales team, never invent it.</strong> An assistant marking leads qualified on their own judgement creates arguments and wastes the closers' time.</p>

<h2>The Part Nobody Tells You: Cold Outreach Is Regulated</h2>

<p>If you take one thing from this guide, take this. These are not guidelines. They are laws with penalties attached, and "my employer told me to" is not a defence anyone has successfully run.</p>

<h3>United States: CAN-SPAM</h3>

<p>The FTC's compliance guide states it plainly:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Each separate email in violation of the CAN-SPAM Act is subject to penalties of up to $53,088, so non-compliance can be costly."</p>

<p><strong>Per email.</strong> Not per campaign. The seven requirements are: no false or misleading header information, no deceptive subject lines, identify the message as an advertisement, give a valid physical postal address, tell recipients how to opt out, honour opt-outs within ten business days, and <em>monitor what others are doing on your behalf</em>.</p>

<p>That last one matters most to you. <strong>Liability reaches both the company being promoted and the party sending on its behalf.</strong> Being a contractor in Pakistan does not put you outside it.</p>

<h3>European Union: GDPR, and the clause that kills scraped lists</h3>

<p>Two separate rules apply: GDPR governs <em>holding</em> the data, and the ePrivacy rules govern <em>sending</em> the email. Passing one does not satisfy the other.</p>

<p><strong>Article 14 is the one nobody mentions.</strong> When you obtain someone's data from anywhere other than the person themselves &mdash; scraping, a data platform, a purchased list &mdash; you must inform them <em>"within a reasonable period after obtaining the personal data, but at the latest within one month"</em>, and you must tell them <em>"from which source the personal data originate"</em>.</p>

<p>Read that again with your job in mind. <strong>Scrape someone's email, and a one-month clock starts.</strong></p>

<p><strong>Article 21 gives an absolute right to object</strong> to direct marketing. Not a balancing test &mdash; once they object, processing for that purpose stops. And the right must be <em>"explicitly brought to the attention of the data subject... at the latest at the time of the first communication"</em> and <em>"presented clearly and separately from any other information"</em>. An opt-out buried in grey text at the bottom does not meet that standard.</p>

<p>Legitimate interest is available for direct marketing, but it is not automatic: it <em>"may be regarded as"</em> a legitimate interest, subject to a three-part test.</p>

<h3>United Kingdom: PECR, and a penalty that just grew 35 times</h3>

<p>The UK rule has a split that trips up every scraped list:</p>

<ul>
    <li><strong>Corporate subscribers</strong> &mdash; a company, LLP or government body &mdash; can be emailed without prior consent.</li>
    <li><strong>Sole traders and some partnerships are treated as individuals</strong>, and can only be emailed with consent or an existing customer relationship.</li>
</ul>

<p>A scraped address does not tell you which one you are looking at. That is the trap.</p>

<p><strong>And the ceiling moved.</strong> Since 5 February 2026, under the Data (Use and Access) Act 2025, the ICO can issue fines under PECR of <strong>up to £17.5 million or 4 per cent of global turnover</strong>. The old £500,000 cap is gone, although some ICO guidance pages have not caught up.</p>

<h3>A real case, and the sentence to remember</h3>

<p>In January 2026 the ICO fined <strong>ZMLUK Limited £105,000</strong> for <strong>67,772,285</strong> marketing emails sent on third-party-sourced data. The ICO found the company <em>"relied heavily on third-party data without carrying out sufficient due diligence checks to understand how consent was obtained"</em>.</p>

<p><strong>"The list vendor said it was opted-in" is not a defence.</strong> Put that on a sticky note.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/lead-generation-assistant-jobs-crm.jpg" alt="CRM dashboard showing contact records, pipeline stages and activity charts for a lead generation campaign" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Record the source of every contact. Good practice in any market, and a legal duty in the EU.</figcaption>
</figure>

<h2>LinkedIn: The Clause That Can End Your Account</h2>

<p>This is the risk most specific to you, and the reason to read a job advert carefully.</p>

<p>LinkedIn's User Agreement, in its list of things you must not do, prohibits members from:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Develop, support or use software, devices, scripts, robots or any other means or processes (such as crawlers, browser plugins and add-ons or any other technology) to scrape or copy the Services, including profiles and other data from the Services"</p>

<p>It separately bans using <em>"bots or other unauthorized automated methods to access the Services, add or download contacts, send or redirect messages"</em>, and copying or distributing information obtained from the Services <em>"whether directly or through third parties (such as search tools or data aggregators or brokers)"</em>. LinkedIn's prohibited-software policy states members <em>"risk having their accounts restricted or shut down"</em>.</p>

<p>Now connect that to the job. <strong>A large share of lead generation adverts instruct the hire to run exactly these tools &mdash; and to run them on their own personal LinkedIn account, not the employer's.</strong> The employer keeps the leads. You absorb the restriction.</p>

<p>For someone in Pakistan whose LinkedIn profile is their main route to international work, a permanent restriction is a career-level loss taken on someone else's behalf.</p>

<p><strong>So ask this in the interview, in these words: "Whose account will the automation run on?"</strong> If the answer is yours, price that risk or decline. A serious employer provides its own accounts, its own seats and its own sending domains.</p>

<h2>The Tools, and Which Are Genuinely Free</h2>

<p>Several tools the guides call free are not. Here is what is actually true.</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Tool</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Free tier?</th>
            <th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">What to know</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">HubSpot CRM</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>Yes</strong>, no card</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">2 users, 1,000 contacts. The cap was cut from 1,000,000 in 2024; older articles still quote the old number.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Hunter</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>Yes</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">50 credits a month, no expiry.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Salesforce</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>Yes</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">A free suite for 2 users, plus a free Developer Edition. "No free Salesforce" is out of date.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Apollo.io</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes, <strong>with a catch</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Sign up with Gmail and you get <strong>100 credits a month</strong>; a verified corporate domain gets 10,000. A hundredfold difference.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Clay (clay.com)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Yes, small</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Free actions and credits, but paid plans start around $167 a month. Not clay.earth, which is a different product.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">LinkedIn Sales Navigator</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">About $119.99 a month, roughly PKR 34,000. The trial requires a card and auto-converts.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Pipedrive</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">14-day trial only. Plans were renamed in 2025 to Lite, Growth, Premium and Ultimate.</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Instantly, Smartlead, GoHighLevel</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>No</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;">14-day trials, then $39 to $97 a month. Real cost is higher still once you add domains and inboxes.</td>
        </tr>
    </tbody>
</table>

<p><strong>The card-free stack that actually works from Pakistan:</strong> HubSpot free CRM, Hunter, Clay's free tier and the Salesforce Developer Edition. That is enough to learn the whole workflow and build a portfolio without paying anything.</p>

<p>A practical note: <strong>PayPal is still unavailable in Pakistan</strong>, and international transactions are switched off by default on most Pakistani bank cards. If you need to pay for a tool, you will need that enabled or a Payoneer card. Do not let a paywall stop you applying &mdash; a serious employer provides the seats.</p>

<h2>Do You Need Experience?</h2>

<p>Not always. The table above includes a student-level remote role and two intern positions. But be realistic about what entry level pays here: <strong>PKR 7,000 to 30,000</strong>, frequently part-time or night shift.</p>

<p>The jump to PKR 50,000 and above consistently requires about a year of demonstrable experience. So the sequence is: build a portfolio, take an intern or junior role, survive a year, then negotiate.</p>

<h2>Building a Portfolio Without Breaking the Rules</h2>

<p>You can demonstrate this whole skill set without touching a single real person's data &mdash; and given the previous two sections, you should.</p>

<ol>
    <li>Write an <strong>ideal customer profile</strong> for a fictional product, with your reasoning.</li>
    <li>Build a list of <strong>20 to 30 real, publicly listed businesses</strong> that match it &mdash; company-level information only.</li>
    <li>Name the <strong>decision-maker roles</strong> you would target, by title rather than by person.</li>
    <li>Show a <strong>clean spreadsheet structure</strong> with consistent fields and a source column.</li>
    <li>Write your <strong>qualification criteria</strong> and apply them, showing which you rejected and why.</li>
    <li>Build a <strong>sample CRM pipeline</strong> in HubSpot's free tier.</li>
    <li>Produce a <strong>short performance report</strong> of the kind an employer would receive weekly.</li>
</ol>

<p><strong>Never publish private contact details, personal emails or phone numbers in a public portfolio.</strong> Doing so is both a data protection problem and a signal to any competent employer that you should not be trusted with a list.</p>

<h2>The Metrics You Will Be Measured On</h2>

<p>Expect to report some combination of: prospects researched, new leads added, verified contacts, emails sent, replies received, qualified leads, meetings booked, follow-ups completed and source performance.</p>

<p>One caution worth raising early with any employer: <strong>volume targets and compliance pull in opposite directions.</strong> A manager demanding a thousand contacts a week is asking for a list nobody verified. Agree quality criteria alongside the quantity, in writing, or you will be blamed for both.</p>

<h2>Remote Work, and Getting Paid From Pakistan</h2>

<p>These roles are genuinely available remotely, and several in the table above are. But settle the money before you start, not after.</p>

<p><strong>You will be a contractor, not an employee.</strong> No paid leave, no notice period, no EOBI, no gratuity. Price accordingly.</p>

<p><strong>The correct account is an Exporters' Special Foreign Currency Account.</strong> The State Bank of Pakistan's framework for freelancers' accounts lets a freelancer open an ESFCA alongside a normal PKR account, in person or digitally, with proceeds processed on a self-declaration basis where there is no formal export contract.</p>

<p><strong>A Roshan Digital Account is not for you.</strong> The RDA is for <em>non-resident</em> Pakistanis. If you live in Pakistan, you are not eligible, however often that advice is repeated.</p>

<p><strong>Register with PSEB.</strong> It materially reduces the tax applied to export receipts, and the deduction for unregistered exporters is adjustable rather than final. PSEB now operates under the Pakistan Tech Destination brand, so older pseb.org.pk links redirect; registration is at portal.techdestination.com, helpline 0800-01010. <strong>Confirm the current fee and current rates with PSEB and the FBR directly</strong> &mdash; they change, and this is the one area where acting on a blog post can cost you money.</p>

<h2>How to Spot a Fake Lead Generation Job</h2>

<p>The US Federal Trade Commission's job-scam guidance names virtual assistant postings specifically as a scam vector, and the patterns carry straight across to this role:</p>

<ul>
    <li><strong>Any request for money.</strong> The FTC puts it flatly: <em>"Honest employers, including the federal government, will never ask you to pay to get a job."</em></li>
    <li><strong>A cheque you are asked to deposit and partly return.</strong> <em>"Never bank on a 'cleared' check."</em> The payment reverses and the money you sent is gone.</li>
    <li><strong>Large money for little work.</strong> <em>"If someone offers you a job and claims that you can make a lot of money in a short period of time with little work, that's almost certainly a scam."</em></li>
    <li><strong>Urgency to move off-platform</strong> to WhatsApp or Telegram, from a Gmail address rather than a company domain.</li>
    <li><strong>Credentials or ID requested before a contract exists.</strong></li>
    <li><strong>Being asked to buy your own equipment and ship it to them.</strong></li>
</ul>

<h3>Two scams specific to this job</h3>

<p><strong>The credential harvest.</strong> An "employer" requires you to log into <em>your own</em> LinkedIn or email account on their machine, VPN or shared tooling. What they are collecting is your account and your network, not leads.</p>

<p><strong>The unpaid trial task.</strong> You are asked to deliver a sample prospect list as a test. You deliver a real, usable list. The account goes quiet. A one-page sample is a fair test; a hundred verified contacts is unpaid production work.</p>

<p><strong>Where to report in Pakistan:</strong> cybercrime is now handled by the <strong>National Cyber Crime Investigation Agency</strong>, complaint portal complaint.nccia.gov.pk. The FIA Cyber Crime Wing that older articles cite was superseded. Verify the portal in your browser before you need it.</p>

<h2>Free Training That Is Actually Free</h2>

<p>Four corrections before you spend anything:</p>

<ul>
    <li><strong>Google Digital Garage is gone.</strong> The platform is permanently offline. Any course list still recommending it is stale.</li>
    <li><strong>e-Rozgaar is no longer free.</strong> Its own FAQ states a fee is charged for the selected course. This is the most dangerous outdated claim in Pakistani career articles.</li>
    <li><strong>DigiSkills is free but enrolment is not always open.</strong> Batch 04 began in August 2026 with enrolment already closed; watch the site rather than assuming you can join today.</li>
    <li><strong>LinkedIn Learning is not free</strong>, and Google Career Certificates on Coursera are paid at around $49 a month. Coursera financial aid exists and is the realistic route.</li>
</ul>

<p>What is genuinely free and open right now:</p>

<ul>
    <li><strong>HubSpot Academy</strong> &mdash; free account, free courses, free certificates, no card. Start with Inbound Sales, then Sales Hub Software, then Email Marketing. Note the correct product certificate is <em>Sales Hub Software</em>; there is no "HubSpot CRM Certification".</li>
    <li><strong>LinkedIn Sales Solutions Learning Center</strong> &mdash; free, and you do <em>not</em> need a Sales Navigator subscription to use it. Given the subscription costs about PKR 34,000 a month, this is the best free resource on this page.</li>
    <li><strong>Google Skillshop</strong> for GA4 and digital marketing fundamentals, and Applied Digital Skills for spreadsheets.</li>
</ul>

<h2>How to Apply</h2>

<p><strong>Where these are advertised:</strong> most of the live Pakistani listings above came from <a href="https://www.mustakbil.com/" rel="nofollow noopener" target="_blank">https://www.mustakbil.com/</a> and rozee.pk. Check the posting date on anything you find, because these adverts expire within weeks and stale listings are exactly how wrong salary figures spread.</p>

<ol>
    <li><strong>Build the portfolio first</strong>, using only public company information.</li>
    <li><strong>Write a CV naming the tools you have actually used.</strong> Never claim one you have not; the practical test will find it.</li>
    <li><strong>Read the listing for a quota.</strong> If there is one, it is an SDR role and should pay like one.</li>
    <li><strong>Ask whose accounts the automation runs on</strong>, and whose sending domain is used.</li>
    <li><strong>Ask which markets you will be emailing.</strong> US, EU and UK contacts each bring different obligations, and the answer tells you how seriously the employer takes compliance.</li>
    <li><strong>Expect a practical test.</strong> Research a named market and produce a sample list. Accuracy and fit beat volume every time.</li>
    <li><strong>Set up your ESFCA and PSEB registration</strong> before your first overseas invoice.</li>
</ol>

<h2>Where This Leads</h2>

<p>The realistic progressions, based on what live listings ask for:</p>

<p><strong>Lead generation assistant &rarr; lead generation specialist &rarr; SDR &rarr; senior SDR &rarr; business development representative.</strong></p>

<p>Or, away from quota-carrying sales: <strong>assistant &rarr; CRM specialist &rarr; sales operations &rarr; revenue operations</strong>, which suits people who prefer systems and data to conversations.</p>

<p>Or toward marketing: <strong>assistant &rarr; digital marketing specialist &rarr; growth marketing</strong>.</p>

<h2>Before You Apply: A Checklist</h2>

<ul>
    <li>Research companies and identify decision-makers from public sources.</li>
    <li>Build an organised prospect list with a source column.</li>
    <li>Use Google Sheets or Excel confidently.</li>
    <li>Maintain CRM records and keep a pipeline clean.</li>
    <li>Verify and clean lead data.</li>
    <li>Apply an employer's qualification criteria without inventing your own.</li>
    <li>Explain, in one sentence each, what CAN-SPAM, GDPR Article 14 and PECR require.</li>
    <li>Explain why running scraping tools on your own LinkedIn account is a risk to you.</li>
    <li>Communicate professionally in written English.</li>
    <li>Produce a simple weekly activity report.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What does a lead generation assistant do?</h3>
<p>Researches companies and decision-makers against an ideal customer profile, builds and cleans B2B prospect lists, verifies contact data, maintains CRM records, supports email and LinkedIn outreach, applies basic qualification criteria and reports weekly activity.</p>

<h3>What do lead generation jobs pay in Pakistan?</h3>
<p>Advertised figures in September 2026 ran from PKR 7,000 to 10,000 for outreach interns in Karachi, PKR 20,000 to 30,000 for student-level remote work, PKR 50,000 to 80,000 for remote cold callers with a year of experience, and PKR 90,000 to 100,000 plus commission for an experienced onsite SDR in Lahore. Most employers publish nothing.</p>

<h3>Is a lead generation assistant the same as an SDR?</h3>
<p>No. An assistant researches, builds lists and keeps the CRM clean with no quota. An SDR carries a quota, cold calls and books meetings. Verified adverts show roughly a tenfold pay difference, so check the listing for a quota before accepting the title.</p>

<h3>Is cold emailing legal?</h3>
<p>It is regulated, not banned. US law allows it with strict conditions and penalties of up to USD 53,088 per non-compliant email. EU rules require you to tell people within one month when their data came from elsewhere, and to offer a prominent objection right in the first message. UK rules permit emailing companies but not sole traders without consent.</p>

<h3>Can I use scraping tools on LinkedIn?</h3>
<p>LinkedIn's user agreement prohibits scrapers, bots and browser extensions that copy profile data or automate activity, and states accounts may be restricted or shut down. The serious risk is that many employers ask you to run these on your own account, so ask whose account will be used.</p>

<h3>Which lead generation tools are actually free?</h3>
<p>HubSpot CRM, Hunter, Salesforce's free suite and Clay's small free tier. Instantly, Smartlead, GoHighLevel and Pipedrive offer trials only. Apollo is free but drops to 100 credits a month if you sign up with a Gmail address rather than a corporate domain.</p>

<h3>Do I need sales experience to start?</h3>
<p>Not necessarily. Student-level and intern roles exist, though they pay PKR 7,000 to 30,000. Understanding the difference between a prospect, a lead, a qualified lead and a customer matters more at entry level than previous sales employment.</p>

<h3>How do I get paid from Pakistan for remote lead generation work?</h3>
<p>Through an Exporters' Special Foreign Currency Account alongside a PKR account, under the State Bank framework for freelancers. A Roshan Digital Account is for non-resident Pakistanis only. PayPal is unavailable in Pakistan; Payoneer is the mainstream route.</p>

<h2>People Also Search For</h2>

<h3>Lead generation jobs in Lahore</h3>
<p>Lahore carries most of Pakistan's advertised lead generation roles, frequently night shift to overlap US client hours. September 2026 listings ran from PKR 20,000 for student-level remote work to PKR 90,000 to 100,000 plus commission for an onsite SDR.</p>

<h3>Apollo.io free plan limits</h3>
<p>The free plan is genuinely free, but the credit allowance depends on your email domain. A verified corporate domain gets 10,000 credits a month while a personal Gmail address gets 100, which is the single biggest practical barrier for a beginner.</p>

<h3>Cold email laws</h3>
<p>US CAN-SPAM carries penalties of up to USD 53,088 per email and requires a physical address and a working opt-out. EU rules add a one-month disclosure duty for data obtained from third parties. UK rules distinguish companies from sole traders.</p>

<h3>LinkedIn automation tools ban</h3>
<p>LinkedIn's user agreement prohibits scrapers, bots and automation that copies data or drives activity, and its prohibited-software policy warns accounts may be restricted or shut down. The account at risk is whichever one the tool runs on.</p>

<h3>B2B lead generation portfolio</h3>
<p>Build one from public company information only, showing an ideal customer profile, a matched company list, target job titles, qualification criteria and a sample report. Never publish individuals' contact details.</p>

<h3>SDR salary Pakistan</h3>
<p>SDR roles pay well above assistant roles because they carry a quota. The highest verified Pakistani figure in September 2026 was PKR 90,000 to 100,000 plus commission, and that role was onsite in Lahore rather than remote.</p>

<h3>HubSpot free CRM limits</h3>
<p>The free tier covers 2 users and 1,000 contacts with no card required. The contact cap was reduced from 1,000,000 in 2024, so older articles quoting the higher number are out of date.</p>

<h3>Freelancer bank account Pakistan</h3>
<p>The State Bank framework lets freelancers open an Exporters' Special Foreign Currency Account alongside a PKR account, with proceeds accepted on self-declaration where no export contract exists. A Roshan Digital Account is not available to residents.</p>

<h2>More Job Guides</h2>

<p>These pair with this one if you are building a remote career from Pakistan:</p>

<ul>
    <li><a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a> &mdash; a remote role with similar entry requirements and no cold-outreach risk.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; the lowest-barrier remote work on this site, and how to price it.</li>
    <li><a href="/blog/how-to-become-a-remote-virtual-assistant">How to Become a Remote Virtual Assistant</a> &mdash; a wider remote role with the same payment questions.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; the realistic starting points, ranked.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the wider market this role sits inside.</li>
    <li><a href="/blog/how-to-find-lead-generation-jobs-on-linkedin">How to Find Lead Generation Jobs on LinkedIn</a> &mdash; the titles that actually work, and the tools that get accounts restricted.</li>
    <li><a href="/blog/b2b-lead-research-jobs">B2B Lead Research Jobs</a> &mdash; the research craft behind a prospect list, and why a verified email often is not.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Job listings and advertised pay were checked on 22 September 2026 and expire within weeks. Data protection, anti-spam and platform rules differ by country and change; confirm your obligations for the markets you contact, and take professional advice before running outreach at scale. Tax rates, registration fees and banking rules are set by the FBR, PSEB and the State Bank of Pakistan. Never pay anyone to secure a job.</p>
HTML;
    }
}
