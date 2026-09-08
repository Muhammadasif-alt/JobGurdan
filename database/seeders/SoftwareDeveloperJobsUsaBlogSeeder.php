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
 * "Software Developer Jobs in USA" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup. The draft's link carried a ?vjk= search-preview parameter, stripped
 * here.
 *
 * Deliberately distinct from the web developer guide, which is a separate BLS
 * occupation on roughly $43,000 less at the median. That guide names this one
 * as the step up and keeps its immigration section short; this page owns the
 * work authorisation depth, since that was the draft's main thrust and is the
 * binding constraint for much of this site's audience. The two link both ways.
 *
 * Corrections to the draft:
 *
 * 1. Every salary band it gave was low. It put entry level at $65,000-$90,000
 *    when the bottom tenth of the entire occupation is $82,460, and senior at
 *    $140,000-$190,000 when the top tenth is above $214,670. The median is
 *    $135,980 as of May 2025. A reader negotiating from the draft's numbers
 *    would leave money on the table at every level.
 *
 * 2. It asserted an "ongoing shortage of skilled tech talent". The projection
 *    is genuinely strong -- 10% growth to 2035, about 106,100 openings a year
 *    -- but shortage language is employer framing, and the junior end of this
 *    market is competitive. The guide gives the figures and says which part is
 *    hard.
 *
 * 3. Its visa section listed routes without the details that decide outcomes:
 *    that the H-1B is a lottery against a 65,000 + 20,000 cap already reached
 *    for FY2027; that cap-exempt employers -- universities, affiliated and
 *    other nonprofit research organisations, government research organisations
 *    -- can file at any time with no lottery, which is the single most useful
 *    and least covered fact for this audience; that OPT is 12 months with a
 *    24-month STEM extension; that the L-1 needs a year with the related
 *    foreign entity first; and where the contested $100,000 payment stands.
 *
 * The featured image is a placeholder pending a dedicated set; replacing the
 * file under storage/app/public/blogs swaps it without touching this seeder.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SoftwareDeveloperJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-software-developer-jobs.html';

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
        $title = 'Software Developer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What software developer jobs in the USA pay against the BLS median of $135,980, why the salary bands in most guides run low, the H-1B lottery and the cap-exempt employers that skip it, and where the $100,000 payment now stands.',
                'content' => $content,
                'featured_image' => 'blogs/software-developer-jobs-in-usa.jpg',
                'tags' => 'software developer jobs in usa, software engineer jobs usa, h1b visa sponsorship jobs, junior software developer jobs, software engineer salary usa, opt stem jobs, cap exempt h1b employers, entry level developer jobs',
                'meta_title' => 'Software Developer Jobs in USA',
                'meta_description' => 'Software developer jobs in USA: the BLS pay and outlook, why the usual salary bands are low, and the H-1B routes including cap-exempt employers.',
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
            ['name' => 'US Technology Employers & Engineering Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-software-engineering-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Software Developer — US Technology Employers and Engineering Teams',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard US business hours, with on-call rotations on infrastructure and platform teams',
                'language' => 'English',
                // The band runs from about $82,460 to above $214,670 before
                // equity, so no single range would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Software engineering roles with US technology employers, startups, consultancies and enterprise teams, including sponsoring employers. Apply through the employer listing.',
                'seo_keywords' => 'software developer jobs in usa, software engineer jobs usa, h1b sponsorship software engineer, junior software developer, opt stem software jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Technology companies, startups, consultancies, banks, healthcare systems and enterprise engineering teams across the United States hire software developers to design, build and maintain applications and the systems behind them. Some roles are remote; sponsorship-eligible roles are more often on-site or hybrid, because the employer is committing to a location.</p>

<h3>What the work involves</h3>
<p>Building features against a specification you will often help shape, reading far more existing code than you write, reviewing colleagues' work and having yours reviewed, writing tests, and owning what you ship once it is in production. On infrastructure and platform teams that includes an on-call rotation. The proportion of time spent actually typing code is smaller than most people entering the field expect.</p>

<h3>Requirements</h3>
<ul>
    <li>Proficiency in at least one language used in production &mdash; Python, Java, JavaScript, C#, Go</li>
    <li>Git, testing, and an understanding of how code reaches production</li>
    <li>Databases and data modelling, and comfort with APIs and system boundaries</li>
    <li>Problem-solving assessed through technical interviews, which are their own skill and worth practising separately</li>
    <li>A bachelor's degree in computing or a related field is the typical stated requirement; bootcamp and self-taught candidates are hired on demonstrable work, though sponsorship cases usually rest on the degree</li>
    <li>For cloud, data, mobile and security specialisms, depth in that area rather than breadth</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Base salary</strong> sits against a national median of $135,980 as of May 2025, with the lowest tenth under $82,460 and the highest tenth above $214,670</li>
    <li><strong>Total compensation</strong> at larger employers adds bonus and equity, which is where offers diverge most and where the headline figures for big technology companies come from</li>
    <li><strong>Level, not title,</strong> determines pay at most large employers &mdash; ask which level a role maps to</li>
</ul>

<h3>Before you apply</h3>
<p><strong>If you need sponsorship, ask before the final round.</strong> Whether an employer sponsors, and whether they are cap-subject or cap-exempt, changes your realistic timeline far more than anything on your resume. It is a normal question and a straight answer saves everyone weeks.</p>

<p><strong>Note:</strong> pay, levelling, remote policy and any sponsorship decision are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Software development pays better than almost any other field covered on this site, and its official projection is one of the strongest in the American economy. Both of those things are true and neither is the reason most guides on this subject are unhelpful. They are unhelpful because they quote salary bands that are consistently below what the occupation actually pays, and because they list visa routes without any of the detail that decides whether you get one. This guide fixes both.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-software-developer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        ⚙️ Browse Software Developer Jobs in the USA &rarr;
    </a>
</div>

<h2>Software Developer Salary in the USA</h2>

<p>The Bureau of Labor Statistics puts the <strong>median annual wage for software developers at $135,980 as of May 2025</strong>. The <strong>lowest ten per cent earned under $82,460</strong>; the <strong>highest ten per cent above $214,670</strong>. Those are base wages, before bonus and equity.</p>

<p>Now compare that to the bands most articles print, including the draft this guide is based on: entry level at $65,000 to $90,000, senior at $140,000 to $190,000. <strong>Both are low.</strong> If the bottom tenth of the entire occupation &mdash; which includes people in the lowest-paying regions and industries &mdash; earns $82,460, then quoting $65,000 as a normal starting salary is describing an outlier as the rule. And a senior ceiling of $190,000 sits below where the top decile actually starts.</p>

<p>A more honest picture:</p>

<ul>
    <li><strong>Entry level and new grad:</strong> commonly $80,000 to $110,000, higher at large technology employers, lower at non-tech companies and in low-cost metros</li>
    <li><strong>Mid-level, two to five years:</strong> roughly $110,000 to $150,000, straddling the median</li>
    <li><strong>Senior:</strong> $150,000 to $215,000 in base alone, with the top tenth beginning above $214,670</li>
    <li><strong>Large technology employers:</strong> total compensation of $200,000 and up is normal at senior levels once equity is counted &mdash; but it is levelled, so ask what level an offer maps to rather than reading the title</li>
</ul>

<p>San Francisco, Seattle and New York pay above the national figure and cost more to live in. Increasingly the more useful question is not the city but the employer's compensation band and whether it is adjusted by location.</p>

<h2>The Outlook, Honestly</h2>

<p>BLS projects employment of software developers to <strong>grow 10 per cent from 2025 to 2035</strong> &mdash; much faster than the 3 per cent average across all occupations &mdash; with about <strong>106,100 openings a year</strong> for the combined developer, QA and testing occupation. That is one of the strongest outlooks in the economy, and roughly eight times the annual openings of <a href="/blog/web-developer-jobs-in-usa">web development</a>, which is the adjacent occupation about $43,000 lower at the median.</p>

<p>One honest qualification. Drafts of articles like this usually cite an "ongoing shortage of skilled tech talent". That is employer-side framing, and it is not quite what the data says. The projection is genuinely strong at the aggregate level, but demand is concentrated in experienced hires, and <strong>the junior end of this market is competitive</strong> &mdash; new graduates compete with each other and with mid-level engineers displaced from other roles. The field is a good bet over a career. The first job is still hard, and anyone telling you otherwise is selling a bootcamp.</p>

<h2>Software Developer Jobs for Foreigners: What Actually Decides It</h2>

<p>For readers outside the United States this section matters more than everything above it, because the constraint is authorisation rather than ability. Most guides list the visa names and stop. Here is the detail that changes outcomes.</p>

<h3>H-1B: a lottery before it is anything else</h3>
<p>The H-1B is the main specialty-occupation route, capped at <strong>65,000 a year plus 20,000 reserved for holders of a US advanced degree</strong>. Registrations routinely exceed the cap, so selection is by lottery, and USCIS has confirmed both caps were reached for fiscal year 2027. A cap-subject petition depends on being drawn before it depends on your experience. Plan on that being outside your control, and never pay anyone who claims they can improve your odds.</p>

<h3>Cap-exempt employers: the route almost nobody mentions</h3>
<p>This is the most useful thing in this guide for anyone who needs sponsorship. <strong>Universities and their affiliated nonprofit entities, nonprofit research organisations and government research organisations are exempt from the H-1B cap.</strong> They can file <strong>at any time of year, with no lottery</strong>.</p>
<p>Universities, medical centres, research institutes and national laboratories all employ software engineers &mdash; research computing, data platforms, clinical and scientific systems. The pay is typically below a technology company's, and the trade is that the sponsorship path is not a coin flip. For many people it is the difference between a route and no route, and it is worth searching for deliberately rather than waiting for one to appear.</p>

<h3>OPT and STEM OPT</h3>
<p>If you study in the United States, <strong>Optional Practical Training gives up to 12 months</strong> of work authorisation after graduation, and graduates of qualifying <strong>STEM programmes can add a further 24 months &mdash; 36 in total</strong>. The STEM extension requires an employer enrolled in <strong>E-Verify</strong> and a formal training plan submitted through your school. Three years is enough time for multiple H-1B lottery attempts, which is the main reason the study route remains the most reliable entry into the US job market.</p>

<h3>L-1</h3>
<p>The intra-company transfer route. It has no lottery, but it requires that you have <strong>already worked for a related entity of the same employer abroad</strong>, generally for at least a year. It is a route you plan into over time by joining a multinational's office in your own country &mdash; not one you apply to directly.</p>

<h3>The $100,000 payment: where it actually stands</h3>
<p>This is the most misreported item in the field, so here is the sequence. A presidential proclamation of <strong>19 September 2025</strong> required an additional <strong>$100,000 payment</strong> with certain H-1B petitions filed from <strong>21 September 2025</strong>, directed at beneficiaries outside the United States; petitions for people already in the US seeking an amendment, change of status or extension were not covered. On <strong>8 June 2026</strong> the US District Court for the District of Massachusetts <strong>vacated the guidance implementing it</strong>, and on <strong>24 July 2026</strong> the First Circuit <strong>denied the government's motion to stay</strong>. DHS has said it disagrees but is complying while it considers next steps.</p>
<p>So it is <strong>not currently being enforced, and the litigation is live.</strong> That can change. Verify the position with USCIS directly before making any decision, and treat with suspicion any recruiter or consultant who states it as settled in either direction &mdash; particularly one charging you for the answer.</p>

<h2>Junior Roles and Getting the First Job</h2>

<p>The first role is the hard one, so aim the effort where it counts:</p>

<ul>
    <li><strong>New grad programmes.</strong> Large employers run structured intakes specifically for people with no experience, they open on a predictable annual cycle, and they are the single best-value application you can make as a fresher. Miss the window and you wait a year.</li>
    <li><strong>Practise interviews as a separate skill.</strong> Technical interviews test something adjacent to the job rather than the job itself. Treating them as a distinct thing to prepare for is not cynical, it is accurate.</li>
    <li><strong>Ship something and let people read the code.</strong> A deployed project with readable commit history and a README explaining the trade-offs demonstrates you can finish, which is what juniors are actually screened on.</li>
    <li><strong>Specialise slightly.</strong> Cloud, data engineering, mobile or security narrows the competition and matches where sponsoring employers have the most demand.</li>
    <li><strong>If you need sponsorship, filter for it early.</strong> Ask in the first conversation, and search cap-exempt employers deliberately.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do software developers make in the USA?</h3>
<p>The BLS median was $135,980 as of May 2025, with the lowest ten per cent under $82,460 and the highest ten per cent above $214,670 in base pay. Bonus and equity at larger employers sit on top of those figures.</p>

<h3>Is $65,000 a normal entry-level developer salary?</h3>
<p>No, it is low. The bottom tenth of the entire occupation earns $82,460, so a genuine new-grad range is closer to $80,000 to $110,000 depending on employer and location. Bands quoted below that describe outliers.</p>

<h3>Is software development still growing?</h3>
<p>Yes. BLS projects 10 per cent growth from 2025 to 2035 with about 106,100 annual openings, among the strongest outlooks tracked. The qualification is that demand concentrates in experienced hires, so the first job remains competitive.</p>

<h3>Which visa do I need as a foreign software developer?</h3>
<p>Usually the H-1B, capped at 65,000 plus 20,000 for US advanced degree holders and allocated by lottery. If you studied in the US, OPT gives 12 months with a 24-month STEM extension. The L-1 requires prior employment with a related foreign entity of the same company.</p>

<h3>What is a cap-exempt H-1B employer?</h3>
<p>Universities and their affiliated nonprofits, nonprofit research organisations and government research organisations. They are exempt from the annual cap and can file at any time with no lottery, which makes them the most reliable sponsorship route for many engineers.</p>

<h3>Is the $100,000 H-1B fee still being charged?</h3>
<p>Not currently. The September 2025 proclamation imposed it on certain petitions, but a Massachusetts federal court vacated the implementing guidance on 8 June 2026 and the First Circuit denied a stay on 24 July 2026. It is active litigation, so confirm the current position with USCIS.</p>

<h3>Can I work for a US company remotely from my own country?</h3>
<p>Some US employers hire internationally, usually through a contractor arrangement or an employer of record. It avoids the visa question entirely, but pay is often adjusted to your local market and you are taxed where you live, not in the US.</p>

<h3>Do I need a computer science degree?</h3>
<p>Not for every employer &mdash; bootcamp and self-taught engineers are hired on demonstrable work. But a degree carries more weight in sponsorship cases, where the specialty-occupation argument usually rests on it, so it matters more if you need a visa than if you do not.</p>

<h2>People Also Search For</h2>

<h3>Software engineer jobs in USA with visa sponsorship</h3>
<p>Concentrated in large technology employers, consultancies and fintech, plus cap-exempt universities and research organisations that can file year-round without a lottery.</p>

<h3>Junior software developer jobs USA</h3>
<p>New grad programmes at larger employers are the best-value route, opening on an annual cycle. Realistic pay is $80,000 to $110,000, not the $65,000 often quoted.</p>

<h3>H1B sponsorship jobs 2026</h3>
<p>Cap-subject filings depend on a lottery against 65,000 plus 20,000 places. Cap-exempt employers avoid it entirely and are the route worth searching for deliberately.</p>

<h3>Software developer salary USA</h3>
<p>A $135,980 median in base pay, from under $82,460 at the tenth percentile to over $214,670 at the ninetieth, with equity on top at larger employers.</p>

<h3>OPT jobs for international students</h3>
<p>Twelve months of work authorisation after graduation, extendable by 24 months for STEM graduates with an E-Verify employer and an approved training plan.</p>

<h3>Entry level software developer jobs no experience</h3>
<p>New grad intakes, internships and contract-to-hire. A deployed project with readable commit history is the strongest evidence available to you.</p>

<h3>Best companies for H1B sponsorship</h3>
<p>Large technology firms and consultancies file the most petitions, but volume does not improve your lottery odds. Cap-exempt employers change the mechanics rather than the odds.</p>

<h3>Software developer vs web developer</h3>
<p>Separate BLS occupations about $43,000 apart at the median, with software development also projected to grow twice as fast.</p>

<h2>More Job Guides</h2>

<p>Comparing the technical paths? These cover them:</p>

<ul>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a> &mdash; the adjacent occupation, its pay distribution and how to move between the two.</li>
    <li><a href="/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern">Senior Frontend Developer &mdash; React and Next.js, Lahore</a> &mdash; a live engineering vacancy with a defined stack.</li>
    <li><a href="/blog/full-stack-developer-jobs-in-usa">Full Stack Developer Jobs in USA</a> &mdash; the title that spans this occupation and web development, and how to tell which one a posting is.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the non-technical digital path, with the most annual openings.</li>
    <li><a href="/blog/graphic-designer-jobs-in-usa">Graphic Designer Jobs in USA</a> &mdash; the creative side of the same product teams.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Wage data, employment projections and immigration rules change, and the H-1B payment described above is subject to ongoing litigation &mdash; confirm the current position with the Bureau of Labor Statistics, USCIS, a licensed immigration attorney and the employer's own advertisement before applying or paying any fee.</p>
HTML;
    }
}
