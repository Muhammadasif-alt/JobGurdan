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
 * "B2B Lead Research Jobs" - the third page in the lead generation cluster, so
 * it is deliberately confined to the part the other two do not cover: the
 * research and data craft itself.
 *
 * The sibling guides own the adjacent ground. Lead Generation Assistant Jobs
 * carries pay, the outreach regulations and the fake-job warnings; the LinkedIn
 * guide carries searching and self-presentation. Repeating either would split
 * this cluster's own rankings, so this page answers one question the other two
 * do not: how do you produce a list that is actually correct, and what happens
 * when it is not.
 *
 * Corrections to the draft (checked 23 September 2026):
 *
 * 1. The draft's central omission is verification. It tells beginners to
 *    "verify the data" without saying that a verification tool cannot confirm
 *    an address on a catch-all domain, which is where a large share of business
 *    email lives. A researcher who ships those as valid is the reason a
 *    client's domain gets blocked. That is now the centre of the guide.
 *
 * 2. Every firmographic field the draft lists is treated as a fact. Employee
 *    counts, revenue bands and technology stacks in B2B databases are inferred
 *    or self-reported. The guide names the free official registries that carry
 *    filed figures instead.
 *
 * 3. The draft's PKR figure comes from an undated advertisement. Pay belongs on
 *    the sibling guide, where it is presented with dated listings.
 *
 * 4. The BLS citation is checked and kept only as context, because B2B Lead
 *    Researcher is not a BLS occupation and the quoted occupation is broader.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class B2bLeadResearchJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.rozee.pk/';

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
        $title = 'B2B Lead Research Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'B2B lead research is judged on accuracy, and most guides skip the part that decides it. What a verified email label really means, why firmographic data is estimated, and the free official registries that carry filed figures.',
                'content' => $content,
                'featured_image' => 'blogs/b2b-lead-research-jobs.jpg',
                'tags' => 'b2b lead research jobs, lead researcher jobs, ideal customer profile, prospect list building, email verification, data enrichment jobs, apollo jobs, remote research jobs pakistan',
                'meta_title' => 'B2B Lead Research Jobs: Skills, Tools and How to Apply',
                'meta_description' => 'B2B lead research jobs explained: the ICP, why a verified email is often not verified, free official company registries, and how to pass the research test.',
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
            ['name' => 'Employers Hiring B2B Lead Researchers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'b2b-lead-research-aggregated']
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
                'position' => 'B2B Lead Researcher',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Often aligned to a client time zone; Pakistani listings for US accounts are commonly evening or night shift',
                'language' => 'English, because research notes and lead records are read by the sales team',
                // Pay sits on the sibling assistant guide, where it can be shown
                // against dated listings instead of a single undated figure.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aggregated B2B lead research and prospect list building roles, covering ICP research, decision maker identification and data verification.',
                'seo_keywords' => 'b2b lead research jobs, lead researcher, prospect list building, ideal customer profile, data verification jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of B2B lead research roles, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own site or on the board where the role appears.</p>

<h3>What the role covers</h3>
<p>Researching companies against an ideal customer profile, identifying the decision makers whose role is relevant to what is being sold, confirming that company and contact information is current, removing duplicates, and keeping the resulting list in a consistent structure that a sales team can work from.</p>

<h3>What it does not cover</h3>
<p>A research role usually stops before outreach. Where a listing also asks you to send the emails or run the LinkedIn messaging, you are being hired for two jobs, and the sending half is regulated. Read the listing carefully.</p>

<h3>The skill that gets you hired</h3>
<p>Accuracy, and specifically knowing when a record cannot be confirmed. An address on a catch-all domain cannot be verified by any tool, because the receiving server accepts every address at that domain. Flagging that row is correct work. Marking it valid is what produces a list that bounces.</p>

<h3>Tools that appear in listings</h3>
<p>Apollo, LinkedIn Sales Navigator, Google Sheets, Excel and a CRM are the common four. Learn the ones named in the listing you are applying to rather than paying for a stack in advance.</p>

<p>Requirements, eligibility and pay are set by individual employers &mdash; not by JobGader. Never pay anyone to secure a job, and never publish a client's prospect data in a portfolio.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>B2B lead research is the job of deciding which companies a sales team should talk to, and who inside them to talk to. It is research work, not sales work, and it is judged on one thing above all others: <strong>whether the list is correct</strong>.</p>

<p>Almost every guide to this job says "verify the data" and moves on. That sentence is hiding the entire skill. This page is about what verification actually can and cannot do, why the company information in prospecting databases is estimated rather than filed, and where to get figures that are neither.</p>

<p>Two sibling guides cover the ground either side of this one. <a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> carries the pay, the outreach regulations and the fake-job warnings. <a href="/blog/how-to-find-lead-generation-jobs-on-linkedin">How to Find Lead Generation Jobs on LinkedIn</a> covers searching and how to present yourself. This page is about the craft.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/b2b-lead-research-jobs-workflow.jpg" alt="A researcher reviewing a prospect list on a laptop with company and contact records" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">The output of the job is a table. Everything that matters is whether the rows in it are true.</figcaption>
</figure>

<h2>Researcher, Not Sender: Where the Job Starts and Stops</h2>

<p>A clean B2B research role ends when the list is handed over. Someone else writes the emails, someone else makes the calls, and someone else owns the reply rate.</p>

<p>This boundary matters for a practical reason. The sending half of the work is regulated in every market you are likely to sell into, and the penalties attach to the sender. If a listing asks you to research <em>and</em> send from your own account or your own domain, you have been handed the liability along with the task. That is a different job at a different rate, and the rules are set out on the <a href="/blog/lead-generation-assistant-jobs">assistant guide</a>.</p>

<p>Read the listing for the verb. "Build and maintain prospect lists" is research. "Run outbound campaigns" is not.</p>

<h2>What These Jobs Are Actually Called</h2>

<p>"B2B Lead Researcher" is a real title but not a common one. Searching only that phrase will show you a fraction of the market. The same work is advertised as:</p>

<ul>
    <li><strong>Lead Generation Specialist</strong> and <strong>Lead Research Specialist</strong> &mdash; the closest international equivalents.</li>
    <li><strong>Market Research Executive</strong> &mdash; broader, sometimes genuinely analytical rather than list building.</li>
    <li><strong>Data Enrichment Specialist</strong> and <strong>List Building Specialist</strong> &mdash; agency and freelance phrasing.</li>
    <li><strong>Prospect Researcher</strong> &mdash; also used in charity fundraising, where it means something different, so check the sector before applying.</li>
    <li><strong>Sales Research Analyst</strong> and <strong>Account Researcher</strong> &mdash; used inside larger sales organisations.</li>
    <li><strong>Business Development Executive (Lead Generation)</strong> &mdash; the standard Pakistani phrasing, and usually includes outreach.</li>
    <li><strong>Online Bidder</strong> &mdash; a Pakistani title with no international equivalent. It is Upwork and Fiverr proposal work, not research, despite often being grouped with it.</li>
</ul>

<p>Search several of these rather than one. On the Pakistani boards the research-only roles are the minority; most local listings bundle research with outreach.</p>

<h2>The Ideal Customer Profile Is Not Yours to Invent</h2>

<p>An ideal customer profile describes the kind of <em>business</em> worth selling to. It is not a buyer persona, which describes a person. Employers use the two terms loosely; the work is different.</p>

<p>A usable ICP is written down before research starts and contains criteria you can actually check:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Criterion</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Checkable?</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Where from</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Industry</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Yes</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The company's own site, in its own words</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Country and operating market</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Yes</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Registered address, official register</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Headcount</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Roughly</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Filed accounts where they exist; otherwise an estimate</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Revenue band</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Rarely, for private firms</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Filings if public or if the jurisdiction requires them</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Technology used</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Partly</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Detected from the public site; misses internal systems</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Hiring or expansion signals</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Yes</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The company's own careers page and announcements</td>
        </tr>
    </tbody>
</table>

<p>Two rules follow from that table. Work to the employer's ICP rather than a better one you invented, and when a criterion cannot be checked, say so in the row instead of filling it in. A researcher who quietly guesses revenue bands produces a list that looks complete and is not.</p>

<h2>The Workflow, Step by Step</h2>

<ol>
    <li><strong>Write the brief down.</strong> Restate the ICP in your own sheet before you open a single tool. If you cannot state the criteria in one paragraph, you do not have them yet and should ask.</li>
    <li><strong>Build the company list first.</strong> Companies, not people. Getting the account list right is most of the value; contacts are comparatively easy once the account qualifies.</li>
    <li><strong>Qualify each company against the site itself.</strong> A database record says a company does X. Its home page is the authority on whether it still does.</li>
    <li><strong>Then find the roles.</strong> Which title is the buyer depends on company size. In a twenty-person firm the founder decides. In a two-thousand-person firm the founder has never heard of the problem.</li>
    <li><strong>Verify, and record what verification returned.</strong> Not "verified" as a word, but the actual result: confirmed, accept-all, unknown.</li>
    <li><strong>Normalise and deduplicate.</strong> Covered below, and it is where most lists quietly go wrong.</li>
</ol>

<h2>Why a "Verified" Email Is Often Not Verified</h2>

<p>This is the part every other guide skips, and it is the difference between a researcher an employer keeps and one they quietly replace.</p>

<p>An email verification tool does not read the recipient's inbox. It opens a conversation with the receiving mail server and asks, in effect, whether that address exists. On most domains the server answers honestly, and the result is reliable.</p>

<p>On a <strong>catch-all domain</strong> it does not. A catch-all is configured to accept mail for every address at that domain, so that messages to a misspelled name still arrive. Ask such a server about <code>ceo@company.com</code> and it says yes. Ask it about <code>notarealperson@company.com</code> and it also says yes. The answer carries no information.</p>

<p>You do not have to take our word for this, because the verification companies say it themselves. Hunter's own help documentation states that on these domains <em>"no verification tool, including Hunter, can fully confirm deliverability"</em>. ZeroBounce puts it more bluntly still: catch-all domains <em>"always return a 'Valid' response from the SMTP service, whether the address is valid or invalid"</em>, and so <em>"there is no way to verify whether the address is valid or invalid"</em>.</p>

<p>Read that again, because it is the whole point. The industry that sells verification publishes, in its own support pages, the fact that a large category of business email cannot be verified by anyone.</p>

<p>That is why results are not a yes or a no but a set of categories, and the categories matter:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Result</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">What it actually means</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">What to do with the row</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Valid / deliverable</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The server confirmed this specific mailbox</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Deliver it</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Accept-all / catch-all</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The server accepts every address, so nothing was confirmed</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Deliver it labelled, never as verified</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Risky / unknown</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The check could not complete, often a timeout or a greylist</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Retry, then label</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Invalid</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">The server rejected the address</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Drop it</td>
        </tr>
    </tbody>
</table>

<p>There is a second trap alongside it, and it catches people who think they have avoided the first. Many tools attach a <strong>confidence score</strong> to an address they <em>guessed</em> from a name and a domain, using the company's observed email pattern. Hunter describes its own score as being estimated <em>"from patterns like how the address's format matches known naming conventions at that domain"</em>, and then says the part that matters in six words: <em>"It's not a live mailbox check."</em></p>

<p>So a high confidence score means the pattern is well evidenced. It does not mean anyone confirmed that this person has that address. A column of 90% scores is a column of educated guesses, and if you hand it over labelled "verified" you have misrepresented your own work.</p>

<p>Hunter is equally direct about the limits of a clean result: <em>"Email verification is never 100% certain, since a mailbox can change status at any time after it's checked."</em> Treat any vendor's headline accuracy figure the same way, and read its exclusions before you quote it to a client.</p>

<p>So the practical rule for the job is short:</p>

<ul>
    <li>Keep the raw verification result in its own column. Never collapse four categories into the word "verified".</li>
    <li>Keep guessed addresses separate from confirmed ones, whatever score they carry.</li>
    <li>If an employer's brief says "verified only", ask whether accept-all rows are wanted at all. There is no correct default, and guessing costs you either coverage or accuracy.</li>
</ul>

<p>Understanding this is also the answer to a question you will be asked in interviews: why the list bounced. A researcher who can explain accept-all domains is demonstrably not the reason.</p>

<h2>Where the Figures Actually Come From</h2>

<p>Every firmographic field in a prospecting database has a provenance, and almost none of them are filed figures. Knowing which is which stops you from qualifying accounts on numbers that were never true.</p>

<p><strong>The employee count on a professional network is not headcount.</strong> LinkedIn's own help pages define the figure as <em>"the total number of LinkedIn members employed at the company over time"</em> &mdash; that is, a count of profiles, not of staff. LinkedIn then warns directly that <em>"actual employee counts may differ from what we have computed"</em>, citing the company's industry, how often it updates its self-reported numbers, and <em>"differences between what a company counts as an employee and how an employee represents themself on LinkedIn"</em>.</p>

<p>In practice that means the number runs high where ex-employees never updated their profiles, and low in industries and countries where fewer people use the platform at all. It is a proxy, and LinkedIn says so.</p>

<p><strong>Revenue for a private company is a model, and the vendors admit it.</strong> Apollo's own published explainer states that <em>"revenue and headcount are fundamentally difficult to pin down because most companies are not required to disclose them publicly"</em>, and that vendors <em>"fill this gap by modeling"</em> from job boards, web traffic, technology footprints and filings. For small private businesses it grades its own category as <em>"lowest accuracy, often estimated from proxy signals with wide confidence bands"</em>. ZoomInfo publishes the same concession: financial estimates for private companies are <em>"modeled approximations, not audited figures"</em>, and it advises verifying critical figures <em>"through direct inquiry"</em>.</p>

<p>Note what that means when you are working. The revenue field arrives in your spreadsheet as a plain number with no asterisk on it. Nothing in the export tells you it was inferred. You have to know.</p>

<p>The remaining fields deserve the same scepticism:</p>

<ul>
    <li><strong>The company size band</strong> on a profile page is self-declared by whoever administers it, sometimes set years earlier and never revisited.</li>
    <li><strong>Technology detected on a site</strong> is read from public pages. It finds the marketing stack, misses everything internal, and lingers after a tool has been removed but its tag has not.</li>
    <li><strong>Industry tags</strong> are assigned by classifier, so a company that does two things gets one label.</li>
    <li><strong>Crowd-contributed profiles</strong> carry whatever the contributor entered. Crunchbase describes direct input from companies, investors and partners, including <em>"600,000 active contributors"</em>, as one of its data sources alongside automated ingestion and validation.</li>
</ul>

<p>None of this makes the databases useless. It makes them a starting point that needs confirming, which is precisely what you are employed to do. An employer who has been burned by a list of modelled revenue bands will hire the researcher who raises this unprompted.</p>

<h2>Free Official Sources That Beat Paid Databases</h2>

<p>For the fields that matter most, there are public registers that carry filed figures rather than estimates, and they cost nothing. A researcher who uses them produces better work than one with an expensive subscription and no habit of checking.</p>

<ul>
    <li><strong>The company's own website.</strong> Unfashionable and still the best source for what a business currently does, who its leadership is, and whether it is hiring. An about page and a careers page answer most qualifying questions.</li>
    <li><strong>The UK register</strong> at <a href="https://find-and-update.company-information.service.gov.uk/" rel="nofollow noopener" target="_blank">find-and-update.company-information.service.gov.uk</a>. Free to search, and free to read: registered address, incorporation date, current and resigned officers, previous names, insolvency information and the filed accounts themselves. There is a free API, and you can set free email alerts when a company changes its details. For a UK ICP this is better than any paid database, because these are filed figures rather than estimates. One caveat the register states about itself: Companies House does not check the accuracy of what is filed with it.</li>
    <li><strong>SEC EDGAR</strong> for US entities with a filing obligation. Free, and full-text searchable across filings since 2001. Its limitation is the important part: it covers SEC filers, so a private US company with no filing obligation is simply not there. Do not conclude that a company is small because EDGAR is silent about it.</li>
    <li><strong>Job advertisements.</strong> A company's own careers page signals growth, shows which functions are expanding, and frequently names the technology used, because the tools appear in the requirements. It is free, current, and published by the company itself.</li>
</ul>

<p>For Pakistan specifically, one correction worth having: the SECP name search at <a href="https://eservices.secp.gov.pk/eServices/NameSearch.jsp" rel="nofollow noopener" target="_blank">eservices.secp.gov.pk</a> is a <em>name availability</em> checker for people incorporating a company. It is not a company profile database and will not give you officers, addresses or financials. The main <code>secp.gov.pk</code> site also refuses connections from many non-Pakistani networks, which is worth knowing before you conclude a source is down.</p>

<p>Where no filed figure exists, the honest entry is an estimate labelled as one. That is not a gap in your work; it is your work.</p>

<h2>Where the Data Comes From, and the Rule Nobody Mentions</h2>

<p>Two constraints sit on this job, and drafts of this article routinely omit both.</p>

<p>The first is platform terms. Professional networks prohibit scraping, bulk extraction and the browser tools built to automate it, and the consequence falls on the account that performed the activity. Where an employer expects you to run an extraction tool, ask whose account it runs on before you agree. If the answer is yours, the possible cost of the job is your own professional profile. The <a href="/blog/how-to-find-lead-generation-jobs-on-linkedin">LinkedIn guide</a> sets out what the platform actually says.</p>

<p>The second is data protection law, and it has a specific provision that applies to researchers rather than senders. Where personal data is obtained from somewhere other than the person themselves, which is the definition of buying or building a prospect list, European rules place an obligation on the controller to inform that person, within a set period or at the latest when they are first contacted. Business contact details identifying a named individual are personal data under that regime; a generic company inbox is a different case.</p>

<p>That obligation belongs to the employer, not to you. But two things follow for you in practice. Keep a source note against every record, because a controller who cannot say where data came from cannot comply. And treat a client who reacts badly to that question as information about the client.</p>

<h2>Deduplicate on the Domain, Never the Company Name</h2>

<p>"Acme Inc.", "Acme, Inc.", "ACME Incorporated" and "Acme" are four rows and one company. No spreadsheet function will catch that reliably, because the strings genuinely differ.</p>

<p>The root domain will. <code>acme.com</code> is the same company however the name was typed, so the practical rule is:</p>

<ul>
    <li>Add a column holding the root domain only, stripped of <code>https://</code>, <code>www.</code> and any path.</li>
    <li>Deduplicate on that column, not on the company name.</li>
    <li>Watch for country sites. <code>acme.co.uk</code> may be the same group as <code>acme.com</code> or a separate legal entity with its own buyer. Check before merging.</li>
    <li>Deduplicate contacts on the email address, and separately check whether the same person appears twice under two employers. If so, one of the two records is out of date.</li>
</ul>

<p>Doing this before delivery rather than after is the difference between a researcher and a data entry operator.</p>

<h2>The Tools, and the Order to Learn Them</h2>

<p>Job listings name tools, and beginners respond by trying to learn all of them. That is the wrong order, and it is expensive.</p>

<p>The tools divide into four jobs, and you only need one from each:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">What it does</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Names you will see in listings</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Priority</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Holds and shapes the list</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Google Sheets, Excel</td>
            <td style="border:1px solid #e5e7eb;padding:10px;"><strong>Learn first, properly</strong></td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Finds companies and contacts</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Apollo, Sales Navigator, ZoomInfo, Crunchbase</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Learn the one the job names</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Finds or checks an address</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Hunter, Snov, Skrapp and similar</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Learn the concepts, not the brand</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Stores the record for the sales team</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">HubSpot, Salesforce, Pipedrive, Zoho, GoHighLevel</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Whichever the employer runs</td>
        </tr>
    </tbody>
</table>

<p>Three things worth knowing before you spend anything:</p>

<ul>
    <li><strong>Spreadsheet skill is the only one that transfers everywhere.</strong> Lookups, text functions to normalise a domain, deduplication, conditional formatting to surface blanks. Every employer values it and no employer has to train you on it. It is also the cheapest thing on this list to get genuinely good at.</li>
    <li><strong>Free tiers exist but are smaller than articles imply, and several of these tools have no free tier at all.</strong> They also change. Check the vendor's own pricing page on the day you need it rather than trusting any guide, including this one, on what the current allowance is.</li>
    <li><strong>Do not buy a subscription before you are hired.</strong> Employers who run a paid database provide seats. A listing that expects you to supply paid credits out of your own fee is quietly transferring a cost, and that belongs in your rate.</li>
</ul>

<p>If you are starting from nothing, the honest sequence is: spreadsheets to fluency, then one prospecting database's free tier to understand what the fields mean, then whatever the job in front of you names.</p>

<h2>Do You Need a Degree?</h2>

<p>There is no standard qualification for this title, and no occupational licence. Listings weight practical research skill, spreadsheet fluency, written English and tool familiarity far above a specific degree, and many are explicitly open to beginners.</p>

<p>Be careful with the salary statistics that circulate for this role, because there is a specific trick in how they are produced. <strong>B2B Lead Researcher is not an occupation the US Bureau of Labor Statistics tracks at all.</strong> Neither is Lead Generation Specialist, nor Sales Development Representative. The federal classification is built around tasks, not job titles, and none of these titles appear in it.</p>

<p>So articles substitute a different occupation and quote its wage as though it were this job's. The one they nearly always pick is Market Research Analysts and Marketing Specialists, where BLS states that <em>"the median annual wage for market research analysts was $78,760 in May 2025"</em>. That number is real. The substitution is not.</p>

<p>The problem is visible as soon as you look at the other occupations this job could equally be mapped to:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Occupation used as a stand-in</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">US median, May 2025</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Is it this job?</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Market Research Analysts and Marketing Specialists</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">$78,760</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Closest for analytical work; assumes a degree</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Data Entry Keyers</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">$41,340</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Only if the role is pure transcription</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Telemarketers</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">$35,450</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Matches cold outbound, not research</td>
        </tr>
    </tbody>
</table>

<p>Depending on which stand-in an author picks, the same job "pays" $35,450 or $78,760. That is a spread of more than forty thousand dollars produced entirely by the choice of substitute, and none of it describes a remote list building contract paid from Pakistan.</p>

<p>Treat all of it as context about the American labour market rather than a benchmark. What matters for your rate is what comparable remote listings actually advertise, which is on the <a href="/blog/lead-generation-assistant-jobs">assistant guide</a> with the dates attached.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/b2b-lead-research-jobs-data.jpg" alt="A prospect database of company and contact records being reviewed on screen" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Every field in a prospecting database has a provenance. Knowing which ones are filed and which are inferred is the job.</figcaption>
</figure>

<h2>Passing the Research Test</h2>

<p>Most serious employers set a paid or unpaid sample task: here is an ICP, return twenty to fifty rows. Candidates assume they are being marked on volume. They are not.</p>

<p>What is actually assessed:</p>

<ul>
    <li><strong>Do the rows match the brief?</strong> Five companies outside the ICP cost more than twenty missing ones, because they prove you did not read it.</li>
    <li><strong>Did you flag uncertainty or hide it?</strong> A row marked "accept-all, unconfirmed" scores better than a confident guess. This is the single strongest signal in a sample task.</li>
    <li><strong>Is there a source column?</strong> If a reviewer cannot retrace where a field came from, they cannot trust any of it.</li>
    <li><strong>Is the formatting consistent?</strong> One date format, one country spelling, one capitalisation rule, no stray whitespace.</li>
    <li><strong>Did you ask anything?</strong> One clarifying question before starting reads as professional. Silence followed by a wrong list reads as guessing.</li>
</ul>

<p>Deliver slightly fewer rows, all correct, with the uncertain ones labelled. That wins these tests.</p>

<h2>Building a Portfolio Without Publishing Anyone's Data</h2>

<p>You need a work sample, and you cannot use a client's list. Both problems are solved the same way: build a sample against a public, invented brief, and use companies rather than individuals.</p>

<p>A defensible portfolio piece contains a written ICP, a short method note, and a table like this:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Company</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Root domain</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Country</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Headcount (source)</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Target role</th>
            <th style="border:1px solid #e5e7eb;padding:10px;text-align:left;">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Sample Co One</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">sampleco-one.example</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">United Kingdom</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">62 (filed accounts)</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Operations Director</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Qualified</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Sample Co Two</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">sampleco-two.example</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">United States</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">~120 (estimate only)</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Head of Operations</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Qualified, headcount unconfirmed</td>
        </tr>
        <tr>
            <td style="border:1px solid #e5e7eb;padding:10px;">Sample Co Three</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">sampleco-three.example</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">United States</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">~30 (estimate only)</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Founder</td>
            <td style="border:1px solid #e5e7eb;padding:10px;">Excluded, below ICP size floor</td>
        </tr>
    </tbody>
</table>

<p>Note the third row. Showing a company you <em>rejected</em>, with the reason, demonstrates judgement in a way twenty accepted rows cannot. Keep individuals' names out of it entirely; the target role is enough to show the thinking.</p>

<h2>The Metrics You Will Be Measured On</h2>

<p>Research roles are reported on separately from outreach roles, and you should agree which numbers are yours before you start:</p>

<ul>
    <li><strong>Accounts researched</strong> and <strong>accounts qualified</strong> &mdash; volume, and the share that met the brief.</li>
    <li><strong>Data accuracy rate</strong> &mdash; usually sampled by a reviewer rather than measured on the whole list.</li>
    <li><strong>Bounce rate on delivered contacts</strong> &mdash; fair to measure you on, but only if unconfirmed rows were excluded from sending.</li>
    <li><strong>Duplicate rate</strong> &mdash; should be near zero if you deduplicate on the domain.</li>
    <li><strong>Coverage</strong> &mdash; the share of target accounts where a relevant decision maker was found at all.</li>
</ul>

<p>Reply rate, meetings booked and revenue are <em>not</em> research metrics. They depend on the message, the offer, the timing and the sender's reputation, none of which you control. Push back politely if a contract ties your pay to them while your role stops at the list.</p>

<h2>Where This Leads</h2>

<p>The common progression runs research, then specialist, then into outreach as an SDR or BDR. That is not the only route and not always the best paid one.</p>

<p>The two quieter routes are worth knowing. A researcher who gets good at the data side moves into CRM administration and then sales or revenue operations, which pays better than early SDR work and does not require you to be on calls. A researcher who gets good at the analysis side moves toward market and competitive research, which is closer to the occupation those borrowed salary statistics actually describe.</p>

<p>Both routes reward the same habit: learning why a field is wrong, not just that it is.</p>

<h2>How to Apply</h2>

<p><strong>Start here:</strong> Pakistani listings for these roles are concentrated on Rozee at <a href="https://www.rozee.pk/" rel="nofollow noopener" target="_blank">https://www.rozee.pk/</a>, usually under Business Development Executive or Lead Generation Executive rather than the research titles. International remote work is contracted through the freelance platforms and through employers' own sites.</p>

<p>Then, in order:</p>

<ol>
    <li><strong>Build the sample first, apply second.</strong> One portfolio table with a written ICP, a source column and a documented rejection outweighs any claim on a CV. It also means the research test is something you have already done once.</li>
    <li><strong>Name only tools you have actually opened.</strong> Employers test this in the first call, and it is an unpleasant way to lose a role you could have had.</li>
    <li><strong>Put the accuracy habit on the CV explicitly.</strong> "Flags unconfirmable records rather than estimating them" says more to a hiring manager than a list of software names.</li>
    <li><strong>Ask what happens to the list.</strong> If the answer is that you will also be sending from your own domain or your own LinkedIn account, price that separately and read the <a href="/blog/lead-generation-assistant-jobs">rules that then apply to you</a>.</li>
    <li><strong>Confirm eligibility, hours and payment route before accepting.</strong> Remote does not always mean open to Pakistan, and the shift attached to a US account is usually an evening or night one.</li>
</ol>

<h2>Before You Apply: A Checklist</h2>

<ul>
    <li>Can you state an ICP in one paragraph and apply it consistently?</li>
    <li>Can you strip a URL to its root domain and deduplicate on it?</li>
    <li>Do you know what an accept-all result means and what to do with the row?</li>
    <li>Can you say where each field in your sheet came from?</li>
    <li>Do you have one portfolio table, with no real individuals named in it?</li>
    <li>Have you agreed which metrics are yours and which belong to whoever sends the emails?</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is a B2B lead research job?</h3>
<p>It is the job of identifying companies that match an employer's ideal customer profile, finding the decision makers inside them whose role is relevant to what is being sold, confirming that the information is current, and delivering it as a structured list a sales team can work from. It is research and data work; outreach is usually a separate role.</p>

<h3>Is B2B lead research just data entry?</h3>
<p>No, although it includes data entry. Data entry is transcription, where the source is assumed correct. Research is judgement: deciding whether a company qualifies, which role is the buyer at that company size, whether a record is still true, and which fields cannot be confirmed. The judgement is what is being paid for.</p>

<h3>Which tools should I learn first?</h3>
<p>Google Sheets or Excel to a genuinely fluent level, then whichever CRM and prospecting database are named in the listing you are applying to. Spreadsheet skill transfers to every employer; a specific database does not. Learn the tool the job asks for rather than buying a stack in advance.</p>

<h3>Do I need sales experience to be a lead researcher?</h3>
<p>Not to get hired, but understanding the basics helps you research better, because you can tell which job title is likely to feel the problem being sold against. A researcher who knows why an account qualifies produces a sharper list than one applying filters mechanically.</p>

<h3>Can I do this job remotely from Pakistan?</h3>
<p>Yes, and it is one of the more realistically remote roles in this cluster because the output is a file rather than a meeting. Check country eligibility, the time zone you are expected to overlap with, and whether you are being engaged as a contractor before you accept. Many listings for US accounts expect evening or night hours.</p>

<h3>What does a verified email actually mean?</h3>
<p>Less than it sounds. Verification tools test whether a mail server accepts an address, which works on most domains but not on catch-all domains, where the server accepts every address by design. On those, no tool can confirm deliverability, and an honest result is marked accept-all or risky rather than valid. Passing those on as verified is how lists end up bouncing.</p>

<h3>How much do B2B lead researchers earn?</h3>
<p>It varies too much by country, employer and whether the role includes outreach for a single figure to be meaningful, and the US statistics quoted in most articles describe a different, more analytical occupation. Our <a href="/blog/lead-generation-assistant-jobs">lead generation assistant guide</a> sets out what Pakistani listings in this cluster actually advertise, with the dates attached.</p>

<h3>Can beginners get B2B lead research jobs?</h3>
<p>Yes. It is one of the more accessible entry points into remote work, because a sample project demonstrates competence directly and no qualification is required. Build one portfolio piece with a written ICP, a source column and at least one documented rejection, and you will be ahead of most applicants.</p>

<h2>People Also Search For</h2>

<ul>
    <li>B2B lead research jobs in Pakistan</li>
    <li>Lead generation specialist job description</li>
    <li>What is an ideal customer profile in B2B sales</li>
    <li>How to build a B2B prospect list</li>
    <li>Email verification catch all domain meaning</li>
    <li>Apollo io free plan limits</li>
    <li>Remote data research jobs no experience</li>
    <li>Lead generation portfolio sample</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> &mdash; what these roles pay in Pakistan, and the outreach rules that apply once you start sending.</li>
    <li><a href="/blog/how-to-find-lead-generation-jobs-on-linkedin">How to Find Lead Generation Jobs on LinkedIn</a> &mdash; the titles that actually surface vacancies, and the tools that get accounts restricted.</li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a> &mdash; the adjacent role, and how to tell a genuine listing from a scam.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic entry points and what they pay.</li>
    <li><a href="/blog/how-to-become-a-remote-virtual-assistant">How to Become a Remote Virtual Assistant</a> &mdash; a broader remote route that often includes research work.</li>
    <li><a href="/blog/contact-list-building-jobs">Contact List Building Jobs</a> &mdash; what the daily quotas really mean once you do the arithmetic.</li>
</ul>
HTML;
    }
}
