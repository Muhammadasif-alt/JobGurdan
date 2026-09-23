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
 * "Local SEO Assistant Jobs" - the third SEO page, kept apart from the other
 * two by subject rather than by angle. The WordPress SEO guide owns the job
 * market, the on-page guide owns what Google documents about page-level work,
 * and this one owns Business Profiles, reviews and maps.
 *
 * Two findings shape it, and neither appears in any competing article:
 *
 * 1. The Pakistani market for this specific title is very small. Counting
 *    across three boards found roughly eight to twelve live listings that even
 *    mention local SEO or Business Profiles, of which about three carry it in
 *    the job title. Rozee's live sitemap of 2,597 jobs contained no slug with
 *    gmb, local-seo or google-business in it at all. A guide that implies a
 *    busy local market would be selling the reader a queue that is not there.
 *
 * 2. The two tasks these roles advertise most are the two Google's own
 *    documentation warns against. Keyword-stuffing a Business Profile name is
 *    an explicit suspension trigger in Google's words, and citations and NAP
 *    consistency appear nowhere in Google's only published local ranking page.
 *
 * Corrections and cautions applied (checked 23 September 2026):
 *
 * - Search results for this niche are almost entirely expired listings. Five
 *   Rozee pages presented as current by search summaries were, on their own
 *   pages, closed between March 2025 and January 2026. One summary supplied a
 *   salary and shift for a listing that shows no salary at all. Every figure
 *   below was read from the listing's own page.
 *
 * - Where a listing's structured fields contradict its prose, the prose is
 *   published and the disagreement is named.
 *
 * - No listing is described as currently open, because the closest match
 *   closes the day after writing.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LocalSeoAssistantJobsBlogSeeder extends Seeder
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
        $title = 'Local SEO Assistant Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The two tasks these jobs advertise most are the two Google warns against, and the Pakistani market is far smaller than the courses suggest. What Google actually documents, what the work really pays, and where the money is.',
                'content' => $content,
                'featured_image' => 'blogs/local-seo-assistant-jobs.jpg',
                'tags' => 'local seo assistant jobs, gmb expert jobs pakistan, google business profile jobs, local seo executive lahore, citations nap consistency, google review policy, map pack ranking, remote local seo jobs',
                'meta_title' => 'Local SEO Assistant Jobs: What Google Actually Says',
                'meta_description' => 'Local SEO assistant jobs in Pakistan: what Google documents about rankings and reviews, why citations are not a ranking factor, and what the work really pays.',
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
            ['name' => 'Employers Hiring Local SEO Assistants (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'local-seo-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Nationwide', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'marketing'],
            ['name' => 'Marketing']
        );

        Job::updateOrCreate(
            [
                'position' => 'Local SEO Assistant',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site or Remote',
                'work_hours' => 'Agency roles serving US clients commonly run evening or night hours',
                'language' => 'English, because the work includes writing review replies and profile content for foreign clients',
                // Local listings and foreign remote listings in this niche differ
                // by roughly an order of magnitude, so a single band would
                // misrepresent both. Dated listings are quoted in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aggregated local SEO and Google Business Profile roles, with what Google documents about local ranking and what the work actually pays.',
                'seo_keywords' => 'local seo assistant jobs, gmb expert, google business profile, local seo executive, map pack',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of local SEO and Google Business Profile roles, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own site or on the board where the role appears.</p>

<h3>What the work involves</h3>
<p>Setting up and maintaining a Business Profile, choosing categories, keeping hours and service areas accurate, adding photos, replying to reviews, and reporting on how the profile performs in Search and Maps.</p>

<h3>Two instructions to refuse</h3>
<p>Adding keywords or a city to a client's business name is prohibited by Google and risks suspending the profile. Soliciting only positive reviews, or offering anything in exchange for a review, breaches Google's review policy. Both appear routinely in job descriptions.</p>

<h3>The risk is yours, not only the client's</h3>
<p>Google's third-party policy says it may suspend the Business Profile <em>and</em> the Google Account used to manage it, and that in serious cases it may contact the client's customers. If you manage profiles from your own account, that account is what is at stake.</p>

<h3>Before you accept</h3>
<p>Ask to be added as a manager, never as owner, and never share passwords. Confirm the shift, because agency work on American accounts is frequently evening or night hours.</p>

<p>Requirements, hours and pay are set by individual employers &mdash; not by JobGader. Never pay anyone to secure a job, and never claim a Business Profile without the owner's written consent.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Local SEO is the part of search work that deals with Business Profiles, map results and reviews. It is sold heavily as a career path in Pakistan, and there are two things about it nobody tells beginners.</p>

<p>The first is that the local market for this exact job is <strong>very small</strong>. The second is that the two tasks these roles advertise most often are the two that Google's own documentation warns against, and one of them can cost you your personal Google Account rather than the client's profile.</p>

<p>This guide covers both, using Google's own published rules and listings read from their own pages. For page-level SEO work see <a href="/blog/on-page-seo-assistant-jobs">On-Page SEO Assistant Jobs</a>, and for the employer landscape see <a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a>.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/local-seo-assistant-jobs-map.jpg" alt="A local SEO assistant reviewing map results and business listings on a laptop" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Most of this job is keeping one profile accurate. Very little of it is the tactics the courses sell.</figcaption>
</figure>

<h2>How Big Is This Market, Honestly?</h2>

<p>We counted rather than guessed, across three boards on the same day.</p>

<ul>
    <li><strong>Rozee's live jobs sitemap carried 2,597 vacancies.</strong> The number whose web address contained <code>gmb</code>, <code>local-seo</code>, <code>google-business</code> or <code>google-my-business</code> was <strong>zero</strong>.</li>
    <li><strong>Mustakbil:</strong> of 109 job cards across SEO, marketing and night-shift pages for six cities, <strong>four</strong> mentioned local SEO or Business Profiles anywhere, and <strong>one</strong> was titled as a local SEO role.</li>
    <li><strong>LinkedIn Pakistan:</strong> 84 unique results across six searches. <strong>None</strong> had local SEO or GMB in the job title; four mentioned it in the body.</li>
</ul>

<p>A realistic total is <strong>eight to twelve live listings in the whole country</strong> that touch this work, of which roughly three are genuinely titled as local SEO jobs. Seven of the nine we could read were in <strong>Lahore</strong>. Karachi is a distant second and the other cities barely register.</p>

<p>That is not a reason to avoid the skill. It is a reason to learn it <em>alongside</em> general SEO rather than as a standalone career, and to aim at foreign clients rather than the domestic market. The pay section below shows why.</p>

<h2>A Warning About Searching for These Jobs</h2>

<p>Search results in this niche are unusually stale, and this will waste your time if you do not know it.</p>

<p>We opened every local SEO listing that search engines presented as current. Five of them, on their own pages, had closed between <strong>March 2025 and January 2026</strong>. One search summary confidently reported a salary of PKR 40,000 to 50,000 and a 7pm to 4am shift for a listing whose page shows <strong>no salary at all</strong> and was posted in December 2025.</p>

<p>So: open the listing and read its own <strong>posting date</strong> and <strong>apply before</strong> fields. On Rozee there is a quick test &mdash; an expired job redirects away to a different domain rather than showing you the page. If you land somewhere other than the job, it is closed.</p>

<h2>The First Thing These Jobs Ask For, and Why You Should Refuse</h2>

<p>Open almost any local SEO job description or course and you will be told to put the service and the city into the business name. "Plumber Lahore &ndash; Best 24/7 Service" instead of the business's actual name.</p>

<p>Google's guidelines for representing a business could not be clearer that this is prohibited:</p>

<blockquote style="border-left:4px solid #2563eb;margin:20px 0;padding:8px 18px;color:#374151;">
    <p>"Your name should reflect your business's real-world name, as used consistently on your storefront, website, stationery, and as known to customers."</p>
    <p>"Including unnecessary information in your business name isn't permitted, and could result in the suspension of your Business Profile."</p>
</blockquote>

<p>Google then lists exactly what counts as unnecessary, with its own examples. All of these must be removed from the name:</p>

<ul>
    <li><strong>Service or product information</strong> &mdash; Google's example: "Midas Auto Service Experts" should be "Midas".</li>
    <li><strong>Location information</strong> &mdash; "Equinox near SOHO" should be "Equinox SOHO".</li>
    <li><strong>Marketing taglines</strong> &mdash; "GNC Live Well" should be "GNC".</li>
    <li><strong>Business hours</strong> &mdash; "Regal Pizzeria Open 24 hours" should be "Regal Pizzeria".</li>
    <li><strong>Phone numbers or web addresses</strong>, <strong>store codes</strong>, <strong>trademark symbols</strong>, and <strong>fully capitalised words</strong> &mdash; "SUBWAY" should be "Subway".</li>
</ul>

<p>Google adds that the information belongs elsewhere: <em>"Add additional details like address, service area, business hours, and category in the other sections of your business information."</em></p>

<p>So the single most-taught local SEO tactic is, in Google's own words, a suspension risk. If an employer asks you to do it, that is worth knowing before you agree, because of who carries the consequence. We come to that below.</p>

<h2>Citations and NAP Consistency: Not a Google Ranking Factor</h2>

<p>This is the core deliverable of most local SEO assistant jobs. Build listings for the business across dozens of directories, and make sure the name, address and phone number match exactly everywhere.</p>

<p>Here is what we found when we checked Google's documentation for it. Google publishes exactly one page on local ranking. The words <strong>"citation", "citations", "NAP" and "directories" do not appear on it.</strong> They do not appear in the Business Profile guidelines either, and Search Central's local guidance covers Business Profiles and structured data without mentioning directory listings at all.</p>

<p>What Google actually names is three factors:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Factor</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Google's own description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Relevance</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">"How well a Business Profile matches what someone is searching for" &mdash; improved by complete, detailed business information.</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Distance</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">"How far each business is from the customer who's searching." Nothing you do changes this.</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Prominence</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">"How well-known a business is", based on "how many websites link to your business and how many reviews you have".</td>
        </tr>
    </tbody>
</table>

<p>Note what prominence is built from: <strong>links and reviews</strong>. Not a count of directory entries, and not byte-identical address strings.</p>

<p>Does that make citation work pointless? No, and this is the honest version. Google does say it builds profiles partly from outside sources, listing <em>"publicly-available information, such as crawled web content"</em> and <em>"licensed data from third parties"</em> among them. So wrong information scattered across the web can produce a wrong or conflicting profile. Fixing that is <strong>data accuracy work, and it is worth doing.</strong></p>

<p>What is not supported is the claim sold by the tools and agencies that more citations, or more perfectly matched ones, raise your ranking. Google has never published that. Treat it as an industry belief, and if an employer asks how many citations you can build per month, you now know what question to ask back.</p>

<h2>Reviews: Where the Job Becomes Dangerous</h2>

<p>Every local SEO role touches reviews, and this is where job descriptions most often ask for something Google prohibits. Google's policy names each one:</p>

<ul>
    <li><strong>Paying for reviews, in money or in kind.</strong> Prohibited: content posted "due to an incentive offered by a business - such as payment, discounts, free goods and/or services". This includes incentives offered to get a bad review changed or removed.</li>
    <li><strong>Review gating.</strong> Google's exact wording is that merchants may not "discourage or prohibit negative reviews, or selectively solicit positive reviews from customers". Asking only the happy customers is the single most common instruction in these jobs, and it is named in the policy.</li>
    <li><strong>Bulk or patterned solicitation.</strong> Google removes "content exhibiting unusual volumes or patterns of review contributions that are indicative of efforts to manipulate a place's rating". A quota of reviews per month is exactly such a pattern.</li>
    <li><strong>Reviews from people connected to the business,</strong> including "a contractual or consultory relationship" &mdash; which is you, if you review your own client.</li>
</ul>

<p>What is allowed is narrower and simpler: asking for honest reviews from real customers, without incentives and without steering the rating or the wording. Google's own tips page adds advice worth repeating to a nervous client: <em>"A mix of positive and negative feedback often feels more trustworthy."</em></p>

<h2>The Part That Makes This Your Problem</h2>

<p>You might reason that if a client instructs it, the risk is the client's. Google's third-party policy says otherwise, and this is the most important paragraph on this page.</p>

<p>Google defines a third party as an agency managing a profile it does not own, explicitly including <em>"a third-party SEO/SEM company"</em>. It then sets out enforcement in four stages, ending here:</p>

<blockquote style="border-left:4px solid #dc2626;margin:20px 0;padding:8px 18px;color:#374151;">
    <p>"We may suspend a Business Profile and/or the Google Account you use to manage the Business Profile if you commit a serious policy violation. In cases of repeated or grave policy violations, you may no longer be able to manage a Business Profile. Furthermore, we may contact your customers to notify them accordingly."</p>
</blockquote>

<p>Read that twice. The penalty reaches <strong>your Google Account</strong>, it can bar you from this work permanently, and Google says it may tell the client's other customers. Elsewhere Google confirms the restriction is account-level: <em>"As a result of an account restriction, the Business Profiles you manage are suspended and you won't be able to create or claim other profiles."</em></p>

<p>This is not theoretical. In 2025 Google reported removing <strong>over 13 million fake Business Profiles</strong> and placing posting restrictions on <strong>more than 782,000 accounts</strong>, alongside blocking or removing 292 million policy-violating reviews. It has also sued fake-review sellers, in 2023 and again in 2025.</p>

<p>Google also publishes rules that protect you if you follow them. Consent must be real: <em>"To respond to reviews on behalf of the end customer, you must have an explicit approval. Verbal consent isn't sufficient."</em> And on account hygiene, Google's instructions are specific and worth making your standard practice:</p>

<ul>
    <li>Make the business owner the <strong>owner</strong> of the profile and yourself the <strong>manager</strong>.</li>
    <li>If the client already has a profile, ask to be invited as a manager, <strong>not as an owner</strong>.</li>
    <li><strong>Do not share passwords with clients</strong>, and do not accept theirs.</li>
    <li>Remove profiles from your account when you stop managing them.</li>
    <li>Clients must be able to disconnect from you within seven business days of asking.</li>
</ul>

<p>One more line worth carrying into an interview, because agencies routinely promise it: Google states flatly that <em>"there's no way to request or pay for a better local ranking on Google"</em>, and its third-party policy lists "guarantees top placement on Google" as a prohibited claim.</p>

<h2>What the Work Actually Pays</h2>

<p>Every figure below was read from the listing's own page, with the date it was posted. Listings close quickly in this niche, so treat these as dated examples of what employers advertised, not as vacancies to apply to now.</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Role</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Advertised</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Setup</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Posted</th>
        </tr>
    </thead>
    <tbody>
        <tr style="background:#ecfdf5;">
            <td style="border:1px solid #e5e7eb;padding:10px;">Local SEO specialist, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>PKR 270,000 to 450,000</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Remote, foreign agency, 3 years</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">20 Jul 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Digital marketing assistant, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 50,000 to 80,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Remote, 2 years, local SEO a minor duty</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">30 Jun 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Local SEO executive and GMB expert, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 50,000 to 60,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">On site, night shift 6pm to 3am, 2 years</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">18 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">SEO manager, Multan</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 20,000 to 40,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Remote, 1 year</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">27 Aug 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Business development, GMB agency, Karachi</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 10,000 to 25,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Remote, night shift, entry level</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">25 Jun 2026</td>
        </tr>
    </tbody>
</table>

<p>The top row is the entire argument of this page. <strong>A remote role for a foreign agency advertised five to nine times what the comparable Lahore office role pays</strong>, for the same skill. The domestic ceiling for a titled local SEO job sat around PKR 60,000, and that one came with a 6pm to 3am shift on site.</p>

<p>Two honest caveats. High-paying remote listings are rarer and harder to win than local ones, so the figure is a ceiling rather than an expectation. And the Lahore listing above had a <em>salary badge</em> reading PKR 50,000 to 50,000 while its own description said 50,000 to 60,000, and a shift field reading "First Shift (Day)" while the description specified a 6pm to 3am night shift. Employers fill those dropdowns carelessly. <strong>Read the description, not the badge</strong>, and confirm both in writing.</p>

<h2>The Tools, and What a Beginner Can Genuinely Use</h2>

<p>The important thing first: <strong>the main tool is free.</strong> Google says so directly &mdash; <em>"creating a Business Profile and listing your business on Google is free"</em> &mdash; and it requires agencies to tell clients this, stating that if you charge a management fee you must let customers know the profile itself costs nothing.</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Tool</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Cost</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Card needed?</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Google Business Profile</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free, including bulk management</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">No</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Google Search Console</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">No</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Whitespark free tools and Citation Finder starter</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free (1 campaign, 3 searches a day)</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">No</td>
        </tr>
        <tr style="background:#ecfdf5;">
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>BrightLocal trial</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">14 days full access, then about USD 41 a month</td>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>No &mdash; "no card needed"</strong></td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Semrush Local</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">From USD 30 a month per location, billed annually</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Yes, for the 7 day trial</td>
        </tr>
    </tbody>
</table>

<p>The BrightLocal row is the one to use. A 14 day full-access trial that <strong>explicitly requires no credit card</strong> is rare, and it is the practical answer for anyone in Pakistan without an international card &mdash; which is the real barrier here, more than the price. Run a complete audit of one business during those 14 days and you have a portfolio piece. BrightLocal also states you keep read-only access to that data afterwards.</p>

<p>Semrush's permanent free plan does not include the Local toolkit, so do not plan around it.</p>

<h2>Building a Portfolio With No Client</h2>

<p>You need a real profile to work on, and you must not claim someone else's. The route that stays inside the rules:</p>

<ol>
    <li><strong>Ask a family business, or a shop you actually use, for permission in writing.</strong> A message saying they agree to you managing their profile is enough, and Google requires consent to be more than verbal.</li>
    <li><strong>Have them add you as a manager,</strong> not as owner. Never ask for their password.</li>
    <li><strong>Do the unglamorous work:</strong> accurate categories, correct hours including holidays, a real service area, genuine photos, a description with no links and no price claims.</li>
    <li><strong>Audit the web for wrong information</strong> about them and fix what you can. That is the defensible version of citation work.</li>
    <li><strong>Write review replies</strong> to the reviews they already have, including any negative one. A calm reply to a bad review is the best single work sample in this field.</li>
    <li><strong>Record what changed</strong> over six to eight weeks using the profile's own performance data.</li>
</ol>

<p>Do not buy reviews for your sample, and do not ask friends to post reviews of a business they have not used. Both breach the policy, and both are visible.</p>

<h2>How to Apply</h2>

<p><strong>Start here:</strong> of the Pakistani boards, <a href="https://www.mustakbil.com/" rel="nofollow noopener" target="_blank">https://www.mustakbil.com/</a> is the one to search first, for a practical reason &mdash; it publishes a salary band on effectively every listing. Of 46 SEO job cards we pulled across six cities, every one carried a salary. Rozee shows a figure on roughly half, and LinkedIn Pakistan almost never does, so use Mustakbil to learn what the market pays before you negotiate anywhere else.</p>

<ol>
    <li><strong>Search titles, not the phrase.</strong> Very few listings say "local SEO assistant". Search SEO Executive, SEO Specialist, Digital Marketing Executive and GMB, then read the descriptions for Business Profile work.</li>
    <li><strong>Aim at remote roles for foreign agencies.</strong> That is where the pay gap above lives, and the work is the same.</li>
    <li><strong>Lead with a real profile you improved,</strong> with written permission, and show the review replies you wrote.</li>
    <li><strong>Say what you will not do.</strong> Naming the name-stuffing and review-gating rules in an interview marks you as someone who will not get a client suspended. Good employers value it; the answer you get also tells you which kind you are talking to.</li>
    <li><strong>Get the shift and the salary in writing,</strong> and check them against the description rather than the listing badge.</li>
</ol>

<h2>Before You Accept a Role: A Checklist</h2>

<ul>
    <li>Has the client given written consent for you to manage the profile?</li>
    <li>Are you being added as a manager rather than an owner?</li>
    <li>Whose Google Account will the work run from?</li>
    <li>Does the job expect keywords in the business name?</li>
    <li>Is there a monthly review quota, or an instruction to ask only satisfied customers?</li>
    <li>Does the employer promise clients guaranteed rankings?</li>
    <li>What is the shift, in writing, and does the description agree with the badge?</li>
</ul>

<p>If the answers to the middle three are the wrong ones, the job is asking you to stake your own Google Account on someone else's shortcut. That is worth more than the difference in salary.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a local SEO assistant do?</h3>
<p>Sets up and maintains Google Business Profiles: accurate categories, hours, service areas and photos, replying to reviews, keeping business information consistent across the web, and reporting on how the profile performs in Search and Maps. It usually sits alongside general SEO work rather than being a full job on its own.</p>

<h3>Can I put keywords in a client's business name to rank better?</h3>
<p>No. Google's guidelines state that including unnecessary information in a business name is not permitted and could result in suspension of the profile, and they specifically name service descriptions, locations, taglines, hours and phone numbers as things that must be removed. It is the most commonly taught local SEO tactic and it is explicitly against the rules.</p>

<h3>Do citations and NAP consistency improve local rankings?</h3>
<p>Google has never documented that they do. Its only published page on local ranking names relevance, distance and prominence, and describes prominence as based on links and reviews. Google does say it builds profiles partly from crawled and licensed third-party data, so correcting wrong information across the web is genuine accuracy work &mdash; just not the ranking lever the tools sell it as.</p>

<h3>Is it safe to ask customers for reviews?</h3>
<p>Asking for honest reviews from real customers is allowed. What is prohibited is offering payment, discounts or free goods for a review, discouraging negative reviews, selectively asking only happy customers, and soliciting volumes or patterns that look like rating manipulation. Reviewing a business you have a contractual relationship with is also prohibited.</p>

<h3>What happens if a client asks me to break the rules?</h3>
<p>The consequence can reach you personally. Google states it may suspend the Business Profile and the Google Account used to manage it, that repeat or serious violations can bar you from managing profiles at all, and that it may contact the client's customers. Manage profiles from an account you can afford to lose, or decline the instruction.</p>

<h3>How many local SEO jobs are there in Pakistan?</h3>
<p>Fewer than the courses imply. Counting across three boards on the same day found roughly eight to twelve live listings mentioning local SEO or Business Profiles, of which about three carried it in the job title, and most were in Lahore. Learn it as part of a broader SEO skill set rather than as a standalone career.</p>

<h3>What does local SEO work pay?</h3>
<p>Domestic listings for a titled local SEO role topped out around PKR 60,000 a month in September 2026, with entry-level and adjacent roles from PKR 10,000 to 40,000. A remote listing for a foreign agency advertised PKR 270,000 to 450,000 for the same skill set. The gap between local and foreign clients is far larger than the gap between junior and senior.</p>

<h3>Which tools do I need to buy?</h3>
<p>None to start. The Business Profile itself is free, Search Console is free, and Whitespark offers free tools plus a free citation-finder tier. BrightLocal's 14 day trial requires no credit card, which makes it the practical option in Pakistan where an international card is usually the real obstacle. Paid subscriptions are for agencies with many locations to report on.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Local SEO assistant jobs in Pakistan</li>
    <li>GMB expert jobs Lahore salary</li>
    <li>Can you put keywords in Google Business Profile name</li>
    <li>Do citations help local SEO ranking</li>
    <li>Google review policy for businesses</li>
    <li>Google Business Profile suspended reinstatement</li>
    <li>Local SEO tools free for beginners</li>
    <li>Remote local SEO jobs for Pakistanis</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/on-page-seo-assistant-jobs">On-Page SEO Assistant Jobs</a> &mdash; what Google documents about page-level work, and the checklist rules with no official basis.</li>
    <li><a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a> &mdash; who hires, the pay ladder, and how to spot a fake SEO agency.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; the production role next door, and how it is priced.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; where this skill set leads internationally.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic entry points and what they pay.</li>
</ul>
HTML;
    }
}
