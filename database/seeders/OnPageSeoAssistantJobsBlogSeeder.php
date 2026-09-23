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
 * "On-Page SEO Assistant Jobs" - the craft counterpart to the WordPress SEO
 * Assistant guide, which owns the job market, the employers and the pay ladder.
 * This page answers a different question: which of the things an SEO checklist
 * tells you to do are actually documented by Google, and which are folklore the
 * industry repeats to itself.
 *
 * That split matters commercially as well as editorially. Two pages competing
 * on "SEO assistant jobs" would cannibalise each other; a page on what Google
 * actually publishes has its own audience and its own queries.
 *
 * Corrections to the draft (checked 23 September 2026):
 *
 * 1. Both salary claims were sourced to a Reddit thread and neither survives.
 *    No live listing matches PKR 40,000-60,000 for a remote SEO Assistant, nor
 *    PKR 35,000-45,000 for a junior SEO/WordPress role. The guide publishes
 *    dated primary listings instead, which show a materially lower entry level.
 *
 * 2. The draft recommends FAQ structured data. Google reduced FAQ rich results
 *    to authoritative government and health sites on 8 August 2023, deprecated
 *    HowTo on 13 September 2023, and both have since left the search gallery.
 *
 * 3. The draft implies a single H1 and a fixed heading hierarchy are required.
 *    Google's own starter guide says heading order does not matter to Search
 *    and that there is no ideal number of headings.
 *
 * 4. The draft implies 1-2 years of experience is the floor. Live listings
 *    explicitly welcoming freshers are quoted instead.
 *
 * 5. ChatGPT citation artifacts are not published.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class OnPageSeoAssistantJobsBlogSeeder extends Seeder
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
        $title = 'On-Page SEO Assistant Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Half of what on-page SEO checklists tell you to do is not in Google documentation at all. Here is what Google actually publishes, what Pakistani listings really pay with the dates attached, and how to pass the practical test.',
                'content' => $content,
                'featured_image' => 'blogs/on-page-seo-assistant-jobs.jpg',
                'tags' => 'on page seo assistant jobs, seo assistant jobs pakistan, junior seo executive salary, google search central, yoast rank math, seo practical test, entry level seo jobs, search console',
                'meta_title' => 'On-Page SEO Assistant Jobs: Skills, Pay and How to Apply',
                'meta_description' => 'On-page SEO assistant jobs: what Google actually documents, which checklist rules are folklore, real dated Pakistani salaries, and how to pass the SEO test.',
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
            ['name' => 'Employers Hiring On-Page SEO Assistants (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'on-page-seo-assistant-aggregated']
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
                'position' => 'On-Page SEO Assistant',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site or Remote',
                'work_hours' => 'Agency roles serving US clients are commonly night shift, around 6pm to 3am',
                'language' => 'English, because the work is editing pages that readers and search engines parse',
                // Advertised rates differ by an order of magnitude between intern
                // and specialist listings, so the guide quotes dated adverts
                // individually rather than compressing them into one range.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aggregated on-page SEO assistant roles covering keyword research, titles, internal linking, content updates and Search Console reporting.',
                'seo_keywords' => 'on page seo assistant jobs, junior seo executive, seo intern pakistan, search console, keyword research jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of on-page SEO assistant roles, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own site or on the board where the role appears.</p>

<h3>What the work involves</h3>
<p>Researching the term a page should target, checking that the page answers what a searcher actually wants, writing titles and descriptions, structuring headings, adding internal links, optimising images, and reporting on performance from Search Console.</p>

<h3>What employers actually test</h3>
<p>Most hire on a practical task rather than a CV: here is a page, improve it, and explain your reasoning. Being able to say why you changed something matters more than the change itself.</p>

<h3>Before you accept</h3>
<p>Agency roles serving American clients are frequently night shift, and that is often stated only at interview. Ask about hours early. Ask also whether a salary figure exists, because a minority of Pakistani listings publish one at all.</p>

<p>Requirements, hours and pay are set by individual employers &mdash; not by JobGader. Never pay anyone to secure a job or a training place.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>On-page SEO assistant work is one of the most accessible ways into digital marketing in Pakistan. It is also taught almost entirely from checklists, and a surprising share of what those checklists insist on is not in Google's documentation at all.</p>

<p>This guide separates the two. Everything below is either quoted from Google's own Search Central documentation or taken from a live, dated job advertisement, and where a popular rule turns out to have no official basis, we say so.</p>

<p>For the employer side of this job &mdash; who hires, the pay ladder, and how to spot a fake SEO agency &mdash; see our companion guide on <a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a>. This page is about the work itself.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/on-page-seo-assistant-jobs-checklist.jpg" alt="An on-page SEO checklist being worked through on a laptop" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">The checklist is the easy part. Knowing which items Google actually documents is what separates an assistant from a specialist.</figcaption>
</figure>

<h2>What an On-Page SEO Assistant Does</h2>

<p>The role is page-level work on a site that already exists. In practice:</p>

<ul>
    <li>Deciding what a page should target, and whether it currently answers that.</li>
    <li>Writing the title and the description, and structuring the headings.</li>
    <li>Improving the content itself, which is usually the largest part and the least discussed.</li>
    <li>Adding internal links to and from related pages.</li>
    <li>Handling images: file size, dimensions, and alt text where it carries meaning.</li>
    <li>Checking that pages are indexable, that canonicals point where they should, and that links are not broken.</li>
    <li>Reporting from Search Console.</li>
</ul>

<p>Notice what is not on that list: link building, technical migrations and strategy. Those belong to more senior roles, and a listing that bundles them into an assistant salary is telling you something.</p>

<h2>The Rules Google Does Not Actually Endorse</h2>

<p>These come up in nearly every SEO course sold in Pakistan. None of them are Google guidance.</p>

<p><strong>"Every page must have exactly one H1, in strict order."</strong> Google's own starter guide says the opposite, under a section headed with things it thinks you should not focus on: <em>"Having your headings in semantic order is fantastic for screen readers, but from Google Search perspective, it doesn't matter if you're using them out of order."</em> It goes further: <em>"There's also no magical, ideal amount of headings a given page should have."</em> When a reader challenged this directly, citing an SEO tool that said otherwise, a Google Search team member answered that the guide <em>"is as accurate as it can get"</em>, adding that a non-Google tool calling something good or bad <em>"doesn't make it relevant for Google"</em>.</p>

<p>Asked whether the title and the H1 must match, the same team answered: <em>"No, just do whatever makes sense from a user's perspective."</em></p>

<p>There is a trap here worth naming, because it is how this myth survives. Google does publish a page saying each page should have a unique H1 &mdash; in its <em>developer documentation style guide</em>, which is an internal writing standard for Google's own technical docs. It is not search guidance, and citing it as such is a category error.</p>

<p><strong>"Aim for a keyword density of one to two per cent."</strong> Google has never published a density target. What it publishes is a definition of the failure mode: keyword stuffing is <em>"the practice of filling a web page with keywords or numbers in an attempt to manipulate rankings"</em>, with examples including <em>"repeating the same words or phrases so often that it sounds unnatural"</em>. There is a spam threshold, not an optimum.</p>

<p><strong>"Write at least 1,500 words."</strong> Google's starter guide: <em>"The length of the content alone doesn't matter for ranking purposes (there's no magical word count target, minimum or maximum, though you probably want to have at least one word)."</em> Its helpful content page lists <em>"Are you writing to a particular word count because you've heard or read that Google has a preferred word count? (No, we don't.)"</em> as a warning sign.</p>

<p><strong>"Improve the page's E-E-A-T score."</strong> There is no such score. Google lists <em>"thinking E-E-A-T is a ranking factor"</em> among SEO myths and answers it plainly: <em>"No, it's not."</em> E-E-A-T describes what its quality raters look for, which is a different thing from a signal in the ranking systems.</p>

<p><strong>"The meta description is not a ranking factor, Google says so."</strong> This one is half right and misattributed, which matters if you are asked about it in an interview. The famous <em>"no effect on indexing and ranking at all"</em> quote is about the <strong>keywords</strong> meta tag, not the description. On descriptions, what Google actually documents is how they are used: <em>"Snippets are primarily created from the page content itself. However, Google sometimes uses the meta description HTML element if it might give users a more accurate description of the page than content taken directly from the page."</em> Google publishes no figure for how often it rewrites a snippet, so any percentage you see quoted for that did not come from Google.</p>

<h2>The Green Light Is Not a Ranking</h2>

<p>Yoast and Rank Math give a page a score, and a great deal of junior SEO work consists of turning that score green. It is worth knowing what the score is and is not.</p>

<p>Google publishes a page on exactly this, and its wording is unambiguous: <em>"Third-party tools don't have access to our internal ranking data. They can't guarantee performance. Any predictions are their own and like predictions generally, may not happen."</em></p>

<p>That does not make the plugins useless. They are good at catching things you forgot: a missing description, an image with no alt text, a page with no internal links pointing at it. Treat the score as a completeness check on your own work, not as a measurement of how the page will perform. An employer will notice the difference between a candidate who says "I got it to green" and one who says "the plugin flagged three things, two were worth fixing and one was not."</p>

<h2>FAQ Schema: The Advice That Expired in 2023</h2>

<p>Almost every current SEO guide still tells you to add FAQ structured data to win rich results. That advice is three years out of date, and knowing this is a genuinely useful thing to bring to an interview.</p>

<p>On <strong>8 August 2023</strong> Google announced it was <em>"reducing the visibility of FAQ rich results, and limiting How-To rich results to desktop devices"</em>. The specifics:</p>

<ul>
    <li><em>"Going forward, FAQ (from FAQPage structured data) rich results will only be shown for well-known, authoritative government and health websites. For all other sites, this rich result will no longer be shown regularly."</em></li>
    <li>A follow-up on 14 September 2023 finished the job for the other type: <em>"As of September 13, Google Search no longer shows How-to rich results on desktop, which means this result type is now deprecated."</em></li>
    <li>Neither type appears in Google's current structured data gallery.</li>
</ul>

<p>Should you strip it out of existing pages? Google says not to bother: <em>"While you can drop this structured data from your site, there's no need to proactively remove it. Structured data that's not being used does not cause problems for Search, but also has no visible effects in Google Search."</em></p>

<p>The types that <em>do</em> still produce search features include Article, Breadcrumb, Event, Job posting, Local business, Product, Recipe, Review snippet and Video. If a client wants structured data work that changes how their results look, that is where it is.</p>

<h2>What Google Does Document</h2>

<p>Having cleared the folklore, here is the part with official backing.</p>

<p><strong>Titles are used most of the time, but not always.</strong> Google states that title link generation <em>"is completely automated and takes into account both the content of a page and references to it that appear on the web"</em>, drawing on the title element, the visible heading, og:title, anchor text and more. The current figure, published in September 2021: <em>"title elements are now used around 87% of the time, rather than around 80% before."</em> If you have seen 80% quoted, that number was superseded on the same page that announced it.</p>

<p><strong>Links must be real links.</strong> This is the single most practical thing on this list: <em>"Generally, Google can only crawl your link if it's an <code>&lt;a&gt;</code> HTML element (also known as anchor element) with an href attribute. Most links in other formats won't be parsed and extracted by Google's crawlers."</em> A button wired with JavaScript and no href is not a link as far as crawling goes. On a site built with page builders, this is a real and common defect, and finding it is high-value junior work.</p>

<p><strong>Updating old content is legitimate; faking updates is not.</strong> Google documents freshness systems <em>"designed to show fresher content for queries where it would be expected"</em>, and advises checking in on published content and updating or deleting it. But its helpful content guidance lists two warning questions: <em>"Are you changing the date of pages to make them seem fresh when the content has not substantially changed?"</em> and whether you are adding or removing content <em>"primarily because you believe it will help your search rankings overall by somehow making your site seem 'fresh?' (No, it won't)"</em>. Some agencies ask juniors to bulk-update dates. Now you know what that is.</p>

<h2>What These Jobs Actually Pay in Pakistan</h2>

<p>Salary figures for this role circulate widely and most trace back to forum posts rather than advertisements. We checked the boards directly. Every figure below is from a listing that was live when we looked, with the date it was posted.</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Role and city</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Advertised</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Experience asked</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Posted</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">SEO and content writer interns, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 40,000 to 50,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Under 1 year, entry level</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">7 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Junior SEO executive, e-commerce, Sialkot</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 35,000 flat</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">1 year</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">27 Aug 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Junior SEO executive, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 30,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">1 year, off-page focus</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">10 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">SEO specialist, Sialkot</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 25,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">1 year</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">4 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Local SEO executive, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 50,000 to 60,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">2 years, on-site night shift</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">18 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">SEO, Islamabad</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 60,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">5 years</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">19 Sep 2026</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">SEO and digital marketing interns, Lahore</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">PKR 10,000</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Fresh</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">15 to 21 Sep 2026</td>
        </tr>
    </tbody>
</table>

<p>The shape of that is the useful part. <strong>Interns are advertised between PKR 10,000 and 25,000. Junior executives sit around PKR 25,000 to 35,000. Reaching PKR 50,000 to 60,000 took two years of experience, and in one case a night shift on site.</strong> That is materially lower than the numbers usually quoted for this role online, and it is better to know before you negotiate than after.</p>

<p>Two cautions from the same search. One Lahore agency posted nine near-identical intern listings at PKR 10,000, split by neighbourhood, which inflates how many vacancies the market appears to have. And one listing for an SEO trainee stated its compensation as <strong>starting from one rupee per month</strong>. That is not a typographical error to assume away; it is a listing to walk past, and a reminder to read the salary line rather than the job title.</p>

<h2>Where Salaries Are Shown, and Where They Are Not</h2>

<p>A detail that saves time. Salary disclosure in Pakistan varies enormously by platform, so the board you search changes what you can compare.</p>

<ul>
    <li><strong>Mustakbil</strong> displays a salary band on the large majority of its SEO listings. If you want to know what the market pays, start there.</li>
    <li><strong>Rozee</strong> shows a figure on roughly half, and its counts are inflated by syndicated and duplicated posts.</li>
    <li><strong>LinkedIn Pakistan</strong> listings for junior SEO roles essentially never display pay.</li>
</ul>

<p>So do not conclude that the market hides salaries. Some of it publishes them, and that is where to calibrate your expectations before applying somewhere that does not.</p>

<h2>Do You Need Experience? The Honest Answer</h2>

<p>The common claim is that one to two years is the floor. That is true of most <em>paid junior executive</em> roles, and false as a general statement, because listings that explicitly want freshers are live right now.</p>

<p>Examples from the same week: a Lahore agency advertising an SEO trainee role stating that <em>"no prior SEO experience is necessary, as comprehensive training will be provided"</em>; a Lahore intern listing whose experience field simply reads "Fresh", describing itself as <em>"ideal for fresh candidates who have a basic understanding of computers and the internet"</em>; a Karachi listing saying <em>"fresh graduates or current students are encouraged to apply"</em> with SEO experience <em>"an advantage but not mandatory"</em>; and an Islamabad role welcoming <em>"fresh graduates with relevant knowledge or internship experience"</em>.</p>

<p>So the route exists. The trade is that fresher roles are mostly internships paying at the bottom of the table above. The question worth asking at interview is what happens after the internship, and whether anyone from the last intake is still there.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/on-page-seo-assistant-jobs-console.jpg" alt="Search performance data being reviewed on a laptop screen" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Search Console is free, official, and the only tool on the list that shows you real data about a real site.</figcaption>
</figure>

<h2>The Tools, and Which Are Genuinely Free</h2>

<p>You can become employable in this role without spending anything. The distinction that matters is between tools that are free and tools that are demonstrations.</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Tool</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Free tier</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Worth knowing</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Search Console</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free, no card</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">16 months of data; the interface exports a maximum of 1,000 rows</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Analytics</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free, no card</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Standard retention is capped at 14 months</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Yoast</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free plugin</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Premium is about USD 119 a year for one site</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Rank Math</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free plugin</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Rank tracking and Analytics integration are paid</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Screaming Frog</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free to 500 URLs</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Enough for most small sites; a licence is about GBP 199 a year</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Ahrefs Webmaster Tools</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Free, verified sites only</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Site audit and your own backlinks; no competitor research</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Semrush</strong></td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Demo only</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">One project and ten reports a day is not a workflow</td>
        </tr>
    </tbody>
</table>

<p>The genuinely free stack, then: <strong>Search Console, Analytics, Screaming Frog under 500 URLs, Ahrefs Webmaster Tools on a site you control, and either SEO plugin.</strong> That is enough to do real work and to build a portfolio. Competitor research is the thing that actually costs money, and no employer expects a junior to be funding it personally.</p>

<h2>Building a Portfolio Without a Client</h2>

<p>You do not need a client. You need one site you control and the discipline to document what you did.</p>

<ol>
    <li><strong>Put up a small site</strong> on any topic you know, with five or six real pages. Free hosting is fine.</li>
    <li><strong>Verify it in Search Console</strong> on day one, so you accumulate real data rather than screenshots of someone else's.</li>
    <li><strong>Run Screaming Frog over it</strong> and fix what it finds. Missing titles, duplicate descriptions, broken links, images without alt text, and any navigation that is not a real anchor element.</li>
    <li><strong>Write a before and after note</strong> for two pages: what the page targeted, what you changed, and why. The "why" is the portfolio.</li>
    <li><strong>Show a decision you did not take.</strong> A note saying the plugin wanted the keyword in the first sentence and you judged it would read badly demonstrates more than any score.</li>
</ol>

<p>Do not claim ranking or traffic improvements unless you measured them, and say over what period. Interviewers in this field have heard a great many unverifiable traffic claims.</p>

<h2>Passing the Practical Test</h2>

<p>Most employers test rather than interview. You get a page and an instruction to improve it. What is actually being assessed:</p>

<ul>
    <li><strong>Did you work out what the page is for</strong> before changing anything? Say it out loud in your notes.</li>
    <li><strong>Is the title written for a person?</strong> Descriptive and honest beats keyword-loaded, and Google's own helpful content questions ask whether a heading <em>"avoids exaggerating or being shocking in nature"</em>.</li>
    <li><strong>Did you improve the content,</strong> or only the metadata? Editing the page is the part juniors skip and seniors notice.</li>
    <li><strong>Did you add internal links in both directions?</strong> To the page as well as from it.</li>
    <li><strong>Did you check the links are anchors with an href?</strong> Almost nobody does this, and it is exactly the kind of finding that gets remembered.</li>
    <li><strong>Can you justify each change?</strong> "Because the plugin said so" is the answer that loses the role.</li>
</ul>

<h2>How to Apply</h2>

<p><strong>Start here:</strong> Pakistani SEO listings with published salary bands are concentrated on <a href="https://www.mustakbil.com/" rel="nofollow noopener" target="_blank">https://www.mustakbil.com/</a>. Search Rozee alongside it, and treat LinkedIn as a source of employers to approach rather than of comparable pay.</p>

<ol>
    <li><strong>Search several titles.</strong> On-Page SEO Assistant, SEO Executive, Junior SEO Executive, SEO Intern and Digital Marketing Executive all cover this work.</li>
    <li><strong>Lead the CV with what you changed,</strong> not with tool names. "Rewrote 12 product pages and added internal linking between categories" reads better than a list of plugins.</li>
    <li><strong>Attach the portfolio link in the application itself.</strong> Do not wait to be asked.</li>
    <li><strong>Ask about the shift before the second interview.</strong> Agency work on American accounts is frequently night shift and that is often disclosed late.</li>
    <li><strong>Check the salary line exists.</strong> If it does not, ask for the band before the technical test, not after it.</li>
</ol>

<h2>Before You Apply: A Checklist</h2>

<ul>
    <li>Can you explain what search intent is and identify it for a given page?</li>
    <li>Can you write a title for a person rather than for a plugin?</li>
    <li>Do you know why heading order is a usability matter more than a ranking one?</li>
    <li>Can you find links that are not real anchors on a page?</li>
    <li>Have you verified a site in Search Console yourself?</li>
    <li>Do you have two before and after notes with reasoning?</li>
    <li>Do you know what a page's structured data can and cannot win in 2026?</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What does an on-page SEO assistant do?</h3>
<p>Page-level work on an existing site: choosing what a page should target, improving the content so it answers that, writing the title and description, structuring headings, adding internal links, optimising images, checking indexing and canonicals, and reporting from Search Console. Link building and technical migrations normally sit with more senior roles.</p>

<h3>Do I need a degree or a certification?</h3>
<p>No. Pakistani listings for this role weight practical skill and a portfolio far above qualifications, and several live listings explicitly welcome fresh graduates with no SEO experience at all. A documented sample of work you actually did is worth more than any paid course certificate.</p>

<h3>Does every page need exactly one H1?</h3>
<p>No, and this is one of the most repeated myths in the field. Google's own starter guide states that heading order does not matter from a Search perspective and that there is no ideal number of headings. Sensible heading structure still matters a great deal for readers and for screen readers, which is the real reason to get it right.</p>

<h3>Should I add FAQ schema to win rich results?</h3>
<p>Not for that reason. Since August 2023 Google has shown FAQ rich results only for well-known, authoritative government and health sites, and HowTo results were deprecated entirely in September 2023. Google says there is no need to remove existing markup, but expecting it to change how your result looks is out of date advice.</p>

<h3>What is a realistic starting salary in Pakistan?</h3>
<p>Based on listings live in September 2026, internships are advertised from about PKR 10,000 to 25,000 a month and junior executive roles from about PKR 25,000 to 35,000. Listings at PKR 50,000 to 60,000 asked for two or more years of experience. Figures higher than that circulating online usually come from forums rather than advertisements.</p>

<h3>Do I need to know coding?</h3>
<p>No. Basic familiarity with HTML helps you understand headings, links, images and page structure, and it lets you spot the common defect of navigation built without real anchor elements. Beyond that, advanced programming belongs to technical SEO roles rather than assistant ones.</p>

<h3>Are the Yoast and Rank Math scores reliable?</h3>
<p>They are reliable as completeness checks and unreliable as predictions. Google states that third-party tools have no access to its ranking data and cannot guarantee performance. Use the plugin to catch what you forgot, and be prepared to explain any item you deliberately chose to ignore.</p>

<h3>Can I do this job remotely from Pakistan?</h3>
<p>Yes, and many listings are remote or hybrid. Check the shift, because agency work serving American clients is frequently night hours, and check whether the role is employment or contract. Salary transparency also varies by board, so compare on a platform that publishes bands before negotiating on one that does not.</p>

<h2>People Also Search For</h2>

<ul>
    <li>On page SEO assistant jobs in Pakistan</li>
    <li>Junior SEO executive salary Pakistan</li>
    <li>Does Google require one H1 per page</li>
    <li>Is FAQ schema still worth adding</li>
    <li>Yoast vs Rank Math free version</li>
    <li>SEO intern jobs Lahore</li>
    <li>How to pass an SEO practical test</li>
    <li>Free SEO tools for beginners</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/wordpress-seo-assistant-jobs">WordPress SEO Assistant Jobs</a> &mdash; the employer side of this role, the pay ladder and how to spot a fake SEO agency.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; the production role next door, and how it is priced.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; where this skill set leads internationally.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic entry points and what they pay.</li>
    <li><a href="/blog/b2b-lead-research-jobs">B2B Lead Research Jobs</a> &mdash; another research-led remote route with a low barrier to entry.</li>
</ul>
HTML;
    }
}
