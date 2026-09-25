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
 * "How to Apply for Lloyds Graduate Jobs in the UK" - an employer guide
 * rebuilt on Lloyds Banking Group's own talent site, its graduate FAQs, the
 * individual scheme pages and the Group's 2025 Annual Review, rather than the
 * recycled scheme names and job-board salary bands the draft relied on.
 *
 * Corrections to the draft (checked against lloydsbankinggrouptalent.com,
 * lloydsbankinggroup.com, enic.org.uk and the Times Top 100 announcement,
 * September 2026):
 *
 * 1. The draft says "some programmes such as Corporate Banking do not offer
 *    visa sponsorship". That is the most important fact on the page for this
 *    site's readers and it is wrong in the direction that costs people money.
 *    Lloyds' own graduate FAQ answers the question flatly: "No. Sponsorship is
 *    not offered for graduate schemes." Every single scheme page repeats it -
 *    "We cannot sponsor visas", "Please note that we cannot sponsor visas",
 *    "we cannot support visas". It is not some schemes. It is all of them.
 *
 * 2. The draft claims "more than 12 core graduate programmes". Lloyds' own
 *    graduates page lists ten, plus one flagged as coming soon (Commercial and
 *    Wealth Relationship Management).
 *
 * 3. The draft's scheme list is a previous cycle's. Technology Engineering,
 *    Consumer Banking, Human Resources, Finance, Actuarial, Internal Audit and
 *    Marketing appear nowhere on the current page; their old URLs still sit in
 *    search indexes, which is where the draft got them. The ten live schemes
 *    are Accounting & Business Insights, Business and Commercial Banking,
 *    Corporate Banking and Markets, Cyber Security Engineer, Data Science and
 *    AI, Occupational Psychology, People Science, Product and Partnerships,
 *    Risk, and Software Engineer.
 *
 * 4. The draft says the schemes are "each running 2 years". Most are, but
 *    Corporate Banking and Markets runs 2.5 years and Accounting & Business
 *    Insights runs 3 years.
 *
 * 5. The draft's "GBP 42,000 to GBP 45,000 on most schemes" gets the floor
 *    right and the ceiling badly wrong. Lloyds publishes a figure on every
 *    scheme page: GBP 42,000 (Risk, Accounting & Business Insights, People
 *    Science, Occupational Psychology, Product and Partnerships), GBP 45,000
 *    (Business and Commercial Banking), GBP 48,500 (Software Engineer, Data
 *    Science and AI, Cyber Security Engineer) and GBP 55,000 (Corporate
 *    Banking and Markets). Every number in this guide is Lloyds' own; none is
 *    an aggregator average.
 *
 * 6. The draft says "you can choose up to 3 preferred schemes". Lloyds' FAQ:
 *    "Only one application per candidate is accepted per year." One scheme,
 *    one attempt, one cycle - which changes how you should choose.
 *
 * 7. The draft sells the hybrid policy as "only 2 days a week in the office".
 *    Lloyds' wording is a floor, not a cap: "All colleagues are expected to
 *    spend a minimum of two days each week in the office, some roles are five
 *    days per week."
 *
 * 8. The draft refers to "the NARIC tool". Lloyds' FAQ does still say NARIC,
 *    so the draft copied it faithfully, but UK NARIC was renamed UK ENIC on 1
 *    March 2021 and is delivered by Ecctis. The guide names both so a reader
 *    searching for the service actually finds it.
 *
 * 9. The draft says "more than 26 million customers". Lloyds Banking Group's
 *    2025 Annual Review says 28 million customers and around one million
 *    businesses, with c.21.5 million customers using the app.
 *
 * 10. The draft says "GBP 3 billion a year invested in technology and people
 *     development". It is c.GBP 3 billion of strategic investment across 2022
 *     to 2024 in total, rising to c.GBP 4 billion through 2026. Not per year -
 *     the draft has inflated it roughly threefold.
 *
 * 11. The draft says Lloyds is "in the Times Top 100 Graduate Employers list
 *     every year", which is unsourceable filler. The citable fact is that
 *     Lloyds ranked 10th in The Times Top 100 Graduate Employers 2026, its
 *     highest ever, up from 40th in 2021, and 6th in the Top 100 Apprenticeship
 *     Employers 2026, up from 54th in 2024.
 *
 * 12. The draft says eligibility is "a 2:2 degree or better in any subject".
 *     Lloyds says "you must hold or be on track to obtain a degree, ideally a
 *     2:2 or above" - "ideally", not a hard bar - and "any subject" is wrong
 *     for at least one scheme: Occupational Psychology requires a
 *     BPS-accredited Master's in Occupational Psychology.
 *
 * 13. The draft gives no application window at all, which is the difference
 *     between a useful guide and a decorative one. Applications for the
 *     September 2027 intake opened on 10 September 2026 and close between 4
 *     October and 29 November 2026 depending on scheme and location, with
 *     Lloyds warning that programmes may close early. The draft's September
 *     start month is correct.
 *
 * 14. The draft describes a five-stage process ending in a "final interview
 *     and offer". Lloyds publishes three stages and there is no separate final
 *     interview after the assessment centre: apply online with an online
 *     assessment of around 60 minutes, a Job Insight Assessment answered by
 *     video, written and preference responses, then an assessment centre with a
 *     one-to-one interview, a group exercise and individual assessments. Data
 *     Science and AI, Software Engineer and Cyber Security Engineer swap the
 *     first assessment for a technical one - 130 minutes for software and
 *     cyber, 120 for data science - and add a technical exercise on the day.
 *
 * 15. The draft promises "full sponsorship of professional qualifications"
 *     across the board. It is scheme-specific: Accounting & Business Insights
 *     leads to chartered status, Occupational Psychology includes a fully
 *     funded Stage 2 Chartership, and Risk lists FRM, the Global Credit
 *     Certificate, the Chartered Banker Diploma and an ICA Diploma as optional
 *     certifications rather than automatic ones.
 *
 * 16. The draft's location list (London, Birmingham, Edinburgh, Leeds,
 *     Bristol) is short and misleading. Lloyds' scheme pages also name
 *     Manchester, Glasgow, Halifax, Cardiff and Chester, several schemes do not
 *     run in London at all, and closing dates differ by location - London
 *     almost always closes first.
 *
 * The apply link is Lloyds' own graduate hub on lloydsbankinggrouptalent.com,
 * which carries no vacancy ID and survives the cycle. No aggregator is linked
 * and no aggregator salary is republished.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LloydsGraduateJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.lloydsbankinggrouptalent.com/our-opportunities/graduates/';

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
        $title = 'How to Apply for Lloyds Graduate Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Lloyds Banking Group cannot sponsor a visa for any graduate scheme, and you get one application a year. Here are the ten real schemes, the salaries Lloyds publishes itself, the September 2026 closing dates, and the process it actually runs.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-lloyds-graduate-jobs-in-the-uk.jpg',
                'tags' => 'lloyds graduate scheme, lloyds banking group careers, graduate jobs uk, lloyds graduate salary, banking graduate scheme uk, graduate visa sponsorship uk, uk graduate schemes 2027, lloyds banking group talent',
                'meta_title' => 'Lloyds Graduate Jobs UK: Salary and How to Apply',
                'meta_description' => 'Lloyds graduate schemes pay GBP 42,000 to GBP 55,000, but Lloyds cannot sponsor visas for any of them. Real schemes, salaries and closing dates.',
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
            ['name' => 'Lloyds Banking Group, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'lloyds-banking-group-uk']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Graduate Scheme, Lloyds Banking Group UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, with a minimum of two days each week in the office and some roles five days per week',
                'language' => 'English',
                // Every figure is printed by Lloyds on its own scheme pages, not
                // taken from an aggregator. The floor is GBP 42,000 (Risk,
                // Accounting & Business Insights, People Science, Occupational
                // Psychology, Product and Partnerships) and the ceiling is GBP
                // 55,000 (Corporate Banking and Markets), with GBP 45,000 for
                // Business and Commercial Banking and GBP 48,500 for Software
                // Engineer, Data Science and AI and Cyber Security Engineer.
                'salary_currency' => 'GBP',
                'salary_period' => 'Yearly',
                'salary_minimum' => 42000,
                'salary_maximum' => 55000,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Two and three year graduate schemes at Lloyds Banking Group across banking, risk, accounting, software, data and people science. An existing right to work in the UK is required.',
                'seo_keywords' => 'lloyds graduate scheme, lloyds banking group graduate jobs, graduate jobs uk, banking graduate scheme, lloyds graduate salary, uk graduate schemes apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Lloyds Banking Group runs ten graduate schemes across the United Kingdom, based in Birmingham, Bristol, Cardiff, Chester, Edinburgh, Glasgow, Halifax, Leeds, London and Manchester. Most run for two years; Corporate Banking and Markets runs two and a half and Accounting &amp; Business Insights runs three.</p>

<h3>What the work involves</h3>
<p>Structured rotations through different parts of the Group, real responsibility from early on, a named line manager and buddy, and in several schemes a funded professional qualification. Depending on the scheme that might mean relationship management, credit and markets work, risk frameworks, chartered accountancy, software and cloud engineering, data science and AI, cyber security, product ownership or people analytics.</p>

<h3>Common requirements</h3>
<ul>
    <li>The existing right to work in the United Kingdom &mdash; Lloyds states plainly that sponsorship is not offered for graduate schemes</li>
    <li>A degree held or on track, ideally a 2:2 or above, with subject requirements on some schemes</li>
    <li>Technical evidence where the scheme is technical, such as Python and SQL for Data Science and AI</li>
    <li>Willingness to spend a minimum of two days a week in your assigned office, and five days for some roles</li>
    <li>One application per candidate per recruitment year, so the scheme you pick is the scheme you get</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> salaries, scheme lengths, locations and closing dates are set and published by Lloyds Banking Group, and immigration rules are set by the Home Office &mdash; not by JobGader. Apply directly on Lloyds' own talent site and never pay anyone for a graduate place, an interview slot or a referral.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on Lloyds Banking Group's own graduate hub, pick one scheme, and get it in early: applications for the September 2027 intake opened on 10 September 2026 and the first closing dates fall on 4 October 2026.</strong> Lloyds publishes a salary on every scheme page, from &pound;42,000 to &pound;55,000, and it runs a three-stage process rather than the five stages most guides describe.</p>

<p>Two answers before you start filling anything in. <strong>Lloyds cannot sponsor a work visa for any graduate scheme</strong> &mdash; its own FAQ answers the question with a single word, "No" &mdash; so you need a right to work you already hold. And <strong>you get one application per year</strong>, not a shortlist of three preferences. Choosing which scheme to apply to is therefore the most consequential decision in this whole process, and it is the one most guides skip past.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.lloydsbankinggrouptalent.com/our-opportunities/graduates/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#006a4d;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127891; Lloyds Graduate Schemes &rarr;
    </a>
</div>

<h2>The Sponsorship Answer, Because Everything Else Depends On It</h2>

<p>Most write-ups of the Lloyds graduate scheme hedge this. They say sponsorship "varies by programme", or that "some schemes such as Corporate Banking do not sponsor". That hedge is wrong, and it wastes months of people's lives.</p>

<p>Lloyds' graduate FAQ asks whether sponsorship is available and answers: <strong>"No. Sponsorship is not offered for graduate schemes."</strong> Every individual scheme page repeats it in its own words &mdash; "We cannot sponsor visas" on Software Engineer and Cyber Security Engineer, "Please note that we cannot sponsor visas" on Risk, Corporate Banking and Markets and Accounting &amp; Business Insights, "we cannot support visas" on Product and Partnerships. There is no scheme on the list that is an exception, and there is no quiet route in for a strong enough candidate.</p>

<p>What that means in practice is that a Lloyds graduate scheme is open to you if you <em>already</em> hold the right to work: British or Irish citizenship, settled or pre-settled status, indefinite leave to remain, a partner or family visa, a dependant visa that permits work, or a Graduate visa from a UK degree. The Graduate route is the realistic one for most international students reading this &mdash; but note that it is time-limited and does not convert into sponsorship at the end of the programme, so you would need a separate plan for year three. Anyone telling you they can arrange a sponsored Lloyds graduate place is selling you something that does not exist. If you are applying from outside the UK, our guide to <a href="/blog/jobs-in-uk-for-foreigners">jobs in UK for foreigners</a> sets out the routes that genuinely are open.</p>

<h2>The Ten Schemes Lloyds Actually Runs</h2>

<p>This is where recycled guides fall over. You will still find articles listing Technology Engineering, Consumer Banking, Human Resources, Finance, Actuarial, Internal Audit and Marketing as Lloyds graduate schemes. Those are previous-cycle names whose old pages still sit in search indexes. None of them is on the current list. The live schemes number <strong>ten</strong>, not "more than 12", with an eleventh &mdash; Commercial and Wealth Relationship Management &mdash; flagged as coming soon.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#006a4d;color:#fff;">
            <th style="padding:10px;text-align:left;">Scheme</th>
            <th style="padding:10px;text-align:left;">Salary</th>
            <th style="padding:10px;text-align:left;">Length</th>
            <th style="padding:10px;text-align:left;">Locations</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Corporate Banking and Markets</td><td style="padding:10px;"><strong>&pound;55,000</strong></td><td style="padding:10px;">2.5 years</td><td style="padding:10px;">London</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Software Engineer</td><td style="padding:10px;"><strong>&pound;48,500</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">London, Halifax, Leeds, Bristol, Manchester, Edinburgh</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Data Science and AI</td><td style="padding:10px;"><strong>&pound;48,500</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Bristol, Edinburgh, Leeds, Halifax, London, Manchester</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Cyber Security Engineer</td><td style="padding:10px;"><strong>&pound;48,500</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Edinburgh, Leeds, Manchester</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Business and Commercial Banking</td><td style="padding:10px;"><strong>&pound;45,000</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Birmingham, Bristol, Edinburgh, Glasgow, Leeds, London, Manchester</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Accounting &amp; Business Insights</td><td style="padding:10px;"><strong>&pound;42,000</strong></td><td style="padding:10px;">3 years</td><td style="padding:10px;">West Yorkshire, Edinburgh, South West, London</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Risk</td><td style="padding:10px;"><strong>&pound;42,000</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Leeds, Edinburgh, Bristol, Birmingham, London</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Product and Partnerships</td><td style="padding:10px;"><strong>&pound;42,000</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Manchester, Chester, Bristol, Cardiff, Leeds, Halifax, Edinburgh</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">People Science</td><td style="padding:10px;"><strong>&pound;42,000</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Bristol, Leeds, Edinburgh</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Occupational Psychology</td><td style="padding:10px;"><strong>&pound;42,000</strong></td><td style="padding:10px;">2 years</td><td style="padding:10px;">Bristol, Leeds, Edinburgh</td></tr>
    </tbody>
</table>
</div>

<p>Two things that table settles. The often-quoted band of "&pound;42,000 to &pound;45,000" has the floor right and the ceiling wrong by &pound;10,000 &mdash; and the gap between the cheapest and dearest scheme is a <strong>31% pay difference for the same year of your life</strong>. And "each running 2 years" is not true either: Corporate Banking and Markets is two and a half, and Accounting &amp; Business Insights is three, because it carries you to chartered status.</p>

<p>Notice too how few schemes are London schemes. Cyber Security Engineer runs only in Edinburgh, Leeds and Manchester. People Science and Occupational Psychology run only in Bristol, Leeds and Edinburgh. If you assumed a banking graduate scheme meant moving to London, several of the best-paid technical options say otherwise.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-lloyds-graduate-jobs-in-the-uk-office.jpg" alt="Modern corporate office of the kind Lloyds Banking Group graduates are based in" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Deadlines, and Why London Closes First</h2>

<p>Applications for the September 2027 intake opened on <strong>10 September 2026</strong>. Closing dates are not uniform &mdash; they vary by scheme <em>and</em> by location, and London consistently closes weeks before anywhere else.</p>

<ul>
    <li><strong>4 October 2026</strong> &mdash; Corporate Banking and Markets; Risk (London); Accounting &amp; Business Insights (London).</li>
    <li><strong>11 October 2026</strong> &mdash; Business and Commercial Banking; Software Engineer (London); Data Science and AI (London).</li>
    <li><strong>25 October 2026</strong> &mdash; Risk (Edinburgh, Bristol, Birmingham); Accounting &amp; Business Insights (Edinburgh); People Science and Occupational Psychology (Bristol, Edinburgh).</li>
    <li><strong>8 November 2026</strong> &mdash; Risk (Leeds); Accounting &amp; Business Insights (South West, West Yorkshire); People Science and Occupational Psychology (Leeds); Product and Partnerships.</li>
    <li><strong>29 November 2026</strong> &mdash; Cyber Security Engineer; Software Engineer and Data Science and AI outside London.</li>
</ul>

<p>Lloyds attaches a warning to these dates that is worth taking literally: programmes <strong>may close early</strong> if they receive a high number of applications. A published deadline is the last possible date, not a safe one. Combine that with the one-application-a-year rule and the strategy writes itself &mdash; decide early, apply early, and do not sit on a London application waiting to see whether you prefer Leeds.</p>

<h2>One Application a Year, Not Three Choices</h2>

<p>The single most repeated error about this scheme is that you can rank two or three preferences. You cannot. Lloyds' FAQ is unambiguous: <strong>"Only one application per candidate is accepted per year."</strong></p>

<p>So the choice is real and it is binding for the cycle. Some things worth weighing before you commit: the pay gap between schemes is large; the technical schemes pay more but screen you on a technical assessment you cannot bluff; the Accounting scheme is a year longer but hands you a chartered qualification; and the location list differs enough that a scheme you like may not run anywhere you can live. Read the individual scheme page in full, not the summary table &mdash; including this one.</p>

<h2>The Process Lloyds Publishes: Three Stages, Not Five</h2>

<p>There is no separate "final interview and offer" round after the assessment centre, whatever else you have read. Lloyds sets out three stages for its standard schemes:</p>

<ol>
    <li><strong>Apply online.</strong> Fill out the application form and complete an online assessment lasting around <strong>60 minutes</strong>.</li>
    <li><strong>Job Insight Assessment.</strong> Multiple scenarios with questions, giving you a realistic preview of the role, with responses by <strong>video, written answers and preference indications</strong>.</li>
    <li><strong>Assessment centre.</strong> A <strong>one-to-one interview, a group exercise and individual assessments</strong> relating to pre-work you are given well in advance. You also get to meet current graduates and ask about the culture.</li>
</ol>

<p>Three schemes run a variant. For <strong>Data Science and AI, Software Engineer and Cyber Security Engineer</strong>, the first-stage online assessment is replaced by a technical coding or analytical assessment &mdash; <strong>130 minutes</strong> for Software Engineer and Cyber Security Engineer, <strong>120 minutes</strong> for Data Science and AI &mdash; and the final stage becomes a virtual assessment day that adds a <strong>technical exercise</strong> to the interview, group exercise and individual assessments.</p>

<p>Two practical notes from Lloyds' own pages. There is an <strong>Assessment Practice Zone</strong> that previews the kind of assessments you will face, and it is free &mdash; use it rather than paying a third party for practice tests. And if you need accessibility support at any stage, or a laptop and a quiet room for a virtual assessment centre, Lloyds says to ask: it offers access to a laptop within an office plus travel expenses where needed, via its recruitment partner's support line.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-lloyds-graduate-jobs-in-the-uk-team.jpg" alt="Colleagues collaborating in a team meeting during a graduate scheme rotation" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Degrees, Grades and Overseas Qualifications</h2>

<p>Lloyds' wording on grades is softer than the "2:2 minimum" most guides quote: <strong>"you must hold or be on track to obtain a degree, ideally a 2:2 or above."</strong> The word doing the work there is <em>ideally</em>. What the scheme pages emphasise instead is evidence that you can do the job &mdash; "sound knowledge, understanding and technical skills aligned to the role".</p>

<p>"Any subject" is also not quite true. Most schemes are subject-open, but <strong>Occupational Psychology requires a BPS-accredited Master's in Occupational Psychology</strong>, completed or in progress, and pays for a fully funded Stage 2 Chartership on top. The technical schemes expect demonstrable skills rather than a specific degree title: Data Science and AI asks for Python, SQL and statistical modelling; Software Engineer asks for an understanding of cloud development, automation and continuous testing, and treats Java, Python, TypeScript, Kotlin, Swift, Rust or Golang as advantageous.</p>

<p>If your degree is from outside the UK, Lloyds asks for full details of the qualification and converts it to a UK equivalent. Its FAQ still calls this "the NARIC tool" &mdash; but <strong>UK NARIC was renamed UK ENIC on 1 March 2021</strong> and the service is delivered by Ecctis. If you go looking for NARIC you will find a wall of lookalike resellers; the official body is UK ENIC. Have your transcripts and certificates ready, because the conversion is done on the detail you supply.</p>

<h2>Hybrid Working, Benefits and Qualifications</h2>

<p>The hybrid policy is routinely reported as "only two days a week in the office", which reads as a ceiling. Lloyds' sentence says the opposite: <strong>"All colleagues are expected to spend a minimum of two days each week in the office, some roles are five days per week."</strong> Two days is the floor, and some roles are fully office-based. Several scheme pages add that the two days are in the office <em>where you have been placed</em>, which matters if you were planning to commute in from another city.</p>

<p>On professional qualifications, the blanket promise of "full sponsorship" is not how Lloyds describes it. <strong>Accounting &amp; Business Insights</strong> takes you to a professional accountancy qualification recognised in the UK and internationally, chartered on completion &mdash; which is why it runs three years. <strong>Occupational Psychology</strong> includes a fully funded Stage 2 Chartership. <strong>Risk</strong> lists optional certifications including the Financial Risk Manager (FRM), the Global Credit Certificate, the Chartered Banker Diploma and the ICA Diploma in Governance, Risk and Compliance. Optional is not the same as automatic, and the technical schemes are built around engineering practice rather than a certificate.</p>

<h2>Who You Are Actually Joining</h2>

<p>Lloyds Banking Group is one of the UK's largest retail and digital banking groups, and it operates through brands most people know separately: <strong>Lloyds Bank, Halifax, Bank of Scotland and Scottish Widows</strong>, among others. Its 2025 Annual Review puts the customer base at <strong>28 million customers and around one million businesses</strong>, with roughly <strong>21.5 million customers using the app</strong> &mdash; a rise of about 45% since 2021. The "more than 26 million" figure that circulates is a few years out of date.</p>

<p>The investment number is worth correcting too, because it gets quoted at roughly three times its real size. Lloyds committed <strong>around &pound;3 billion of strategic investment across 2022 to 2024 in total</strong>, rising to about &pound;4 billion through 2026 &mdash; not &pound;3 billion a year. Around two thirds of that first phase went on growing and diversifying revenue, and the Group now expects around &pound;2 billion of additional revenues from strategic initiatives by the end of 2026, ahead of its original &pound;1.5 billion target. That is the transformation the technology and data schemes plug into, and it is a good thing to be able to talk about accurately at an assessment centre.</p>

<p>On reputation: rather than the vague claim that Lloyds appears in the Times list "every year", the citable fact is better. Lloyds ranked <strong>10th in The Times Top 100 Graduate Employers 2026</strong>, its highest ever placing, up from 40th in 2021 &mdash; and <strong>6th in the Top 100 Apprenticeship Employers 2026</strong>, up from 54th in 2024. It has also expanded its early-careers range recently, adding Wealth and Commercial Management, Product and Partnerships and People Science alongside the Cyber Security Engineering and Software Engineering pathways.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Confirm your right to work first.</strong> No graduate scheme at Lloyds is sponsored. If you do not already hold permission to work in the UK, stop here and look at a different route.</li>
    <li><strong>Start on Lloyds' own graduate hub</strong> rather than a job board. Individual vacancy links change every cycle; the hub does not.</li>
    <li><strong>Use the compare tool and read the full scheme page.</strong> Salary, length, locations and closing date all differ, and you only get one application.</li>
    <li><strong>Check the closing date for your specific location,</strong> not the scheme in general. London usually closes four to seven weeks before everywhere else.</li>
    <li><strong>Apply well before the deadline.</strong> Lloyds warns that programmes may close early when applications run high.</li>
    <li><strong>Budget 60 minutes for the online assessment,</strong> or 120 to 130 minutes if you are applying to Data Science and AI, Software Engineer or Cyber Security Engineer.</li>
    <li><strong>Prepare for video and written answers</strong> at the Job Insight Assessment, and do the pre-work properly before the assessment centre &mdash; the individual assessments on the day are built on it.</li>
    <li><strong>Use the free Assessment Practice Zone,</strong> and ask for accessibility support or equipment if you need it rather than struggling through.</li>
    <li><strong>Never pay for a graduate place, an interview slot or a referral.</strong> Lloyds does not charge candidates, and nobody can sell you a place.</li>
</ol>

<p>If Lloyds is not the right fit, or the one application you had this year did not land, the same degree and the same right-to-work position carry across to other employers &mdash; our <a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">Barclays customer service</a> guide covers the other end of the same bank's hiring, and <a href="/blog/business-analyst-jobs-in-uk">business analyst jobs in UK</a> covers a common landing spot for the same candidates.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does Lloyds sponsor visas for its graduate schemes?</h3>
<p>No. Lloyds' graduate FAQ answers the question directly: "Sponsorship is not offered for graduate schemes." Every individual scheme page repeats that it cannot sponsor visas. You need an existing right to work in the UK, such as citizenship, settled status, a family visa or a Graduate visa.</p>

<h3>What does a Lloyds graduate scheme pay?</h3>
<p>Lloyds publishes a figure on each scheme page: &pound;42,000 for Risk, Accounting &amp; Business Insights, People Science, Occupational Psychology and Product and Partnerships; &pound;45,000 for Business and Commercial Banking; &pound;48,500 for Software Engineer, Data Science and AI and Cyber Security Engineer; and &pound;55,000 for Corporate Banking and Markets.</p>

<h3>How many graduate schemes does Lloyds run?</h3>
<p>Ten are currently listed, with Commercial and Wealth Relationship Management flagged as coming soon. Older lists naming Technology Engineering, Finance, Human Resources, Actuarial, Internal Audit or Marketing are describing a previous cycle.</p>

<h3>Can I apply to more than one Lloyds scheme?</h3>
<p>No. Lloyds accepts only one application per candidate per year, so there is no ranking of preferences. Pick the scheme deliberately, because the choice is fixed for that recruitment cycle.</p>

<h3>When do applications open and close?</h3>
<p>Applications for the September 2027 intake opened on 10 September 2026. Closing dates run from 4 October to 29 November 2026 depending on scheme and location, with London closing first, and Lloyds warns that programmes may close early if applications are high.</p>

<h3>What degree do I need for a Lloyds graduate scheme?</h3>
<p>Lloyds says you must hold or be on track to obtain a degree, ideally a 2:2 or above. Most schemes are open on subject, but Occupational Psychology requires a BPS-accredited Master's in Occupational Psychology, and the technical schemes expect demonstrable coding or analytical skills.</p>

<h3>What is the assessment process?</h3>
<p>Three stages: an online application with an assessment of around 60 minutes, a Job Insight Assessment answered by video, written responses and preference indications, then an assessment centre with a one-to-one interview, a group exercise and individual assessments based on pre-work.</p>

<h3>How many days a week are graduates in the office?</h3>
<p>A minimum of two days each week in the office you are placed in, and Lloyds notes that some roles are five days per week. Two days is the floor rather than the cap, so check the specific scheme page.</p>

<h2>People Also Search For</h2>

<h3>Lloyds graduate scheme salary</h3>
<p>Between &pound;42,000 and &pound;55,000 depending on the scheme, with Corporate Banking and Markets the highest and the technical schemes at &pound;48,500.</p>

<h3>Lloyds graduate scheme 2027</h3>
<p>The September 2027 intake, with applications opened on 10 September 2026 and closing between 4 October and 29 November 2026 by scheme and location.</p>

<h3>Lloyds Banking Group visa sponsorship</h3>
<p>Not available on any graduate scheme. Lloyds states this in its graduate FAQ and repeats it on every individual scheme page.</p>

<h3>Lloyds Software Engineer graduate scheme</h3>
<p>Two years at &pound;48,500 in London, Halifax, Leeds, Bristol, Manchester or Edinburgh, with a 130-minute technical assessment at the first stage.</p>

<h3>Lloyds Corporate Banking and Markets graduate scheme</h3>
<p>The highest paid at &pound;55,000, running two and a half years, based in London, and the earliest to close at 4 October 2026.</p>

<h3>UK ENIC statement of comparability</h3>
<p>The current name for what Lloyds still calls NARIC. UK NARIC became UK ENIC on 1 March 2021 and the service is delivered by Ecctis for the UK government.</p>

<h3>Lloyds Job Insight Assessment</h3>
<p>The second stage of the process: multiple role-based scenarios answered through video, written responses and preference indications.</p>

<h3>Times Top 100 Graduate Employers Lloyds</h3>
<p>Ranked 10th in the 2026 list, its highest ever placing, up from 40th in 2021, alongside 6th in the Top 100 Apprenticeship Employers 2026.</p>

<h2>More Job Guides</h2>

<p>If a graduate scheme is one option rather than the only one, these cover the rest of the UK market:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; the same industry through a different door, with no degree required.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; where a lot of banking graduates end up, and what the market pays for it.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the entry route into technology that does not need a graduate scheme.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a> &mdash; the discipline Lloyds dropped from its current graduate list, and where it hires instead.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the routes that still exist once unsponsored schemes are ruled out.</li>
    <li><a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">How to Apply for BP Engineering Jobs in the UK</a> &mdash; another large UK employer with a structured early-careers pipeline.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; how to get into a corporate office without a graduate programme.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; the opposite of a minimum two days in the office.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; an employer that publishes its hourly rate, and why no store role is sponsored.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; the union pay agreement behind the rates, and the ineligible occupation codes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Lloyds Banking Group's own talent site for scheme names, salaries, programme lengths, locations, closing dates, eligibility, hybrid working and the application stages; its graduate FAQs for the sponsorship position, degree wording, overseas qualification checks and the one-application-a-year rule; Lloyds Banking Group's 2025 Annual Review and strategy pages for customer numbers and investment figures; The Times Top 100 Graduate Employers 2026 and the Top 100 Apprenticeship Employers 2026 for the rankings; and UK ENIC for the NARIC rename. Graduate schemes close early when applications run high, and salaries, locations and dates change each cycle. Always check the live scheme page before you apply.</p>
HTML;
    }
}
