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
 * "Data Scientist Jobs in USA" — the counterweight to the data entry guide.
 * Two occupations on this site, same country, same decade, moving in opposite
 * directions: data entry keyers down 25.9 per cent, data scientists up 34.
 *
 * Corrections to the draft:
 *
 * 1. It gives Junior Data Scientist as $82,850 and Entry-Level as $104,847.
 *    Those are the same seniority, and the figures are $21,997 apart. One of
 *    the two titles is being paid as something else.
 *
 * 2. It leads with an average of $131,121 without an anchor. The Bureau of
 *    Labor Statistics median for data scientists is $112,590, and the gap is
 *    the difference between an average of advertised postings and a measured
 *    median of what employers actually pay.
 *
 * 3. It puts a $131,121 salary figure and a "$300,000+" total compensation
 *    figure in the same paragraph. Base salary and base plus bonus plus
 *    equity are different quantities, and mixing them implies a ladder from
 *    one to the other that does not exist.
 *
 * 4. It lists Big Data Engineer among data science roles. That is data
 *    engineering — the pipelines that feed the team — and a candidate
 *    applying from a modelling background is a mismatch on both sides.
 *
 * 5. It undersells the best fact it has. "Fastest-growing" is an adjective;
 *    34 per cent growth to 2034 and about 23,400 openings a year is the
 *    reason to take the field seriously.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DataScientistJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-data-scientist-jobs.html';

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
        $title = 'Data Scientist Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Why the published entry-level figure sits $21,997 above the junior one, what separates a job board average from the federal median, and why a base salary and a total compensation number should never share a paragraph.',
                'content' => $content,
                'featured_image' => 'blogs/data-scientist-jobs-in-usa.jpg',
                'tags' => 'data scientist jobs, data scientist jobs in usa, data scientist salary, entry level data scientist jobs, remote data scientist jobs, machine learning engineer jobs, senior data scientist salary, data science career',
                'meta_title' => 'Data Scientist Jobs in USA',
                'meta_description' => 'Data scientist jobs in the USA: the federal median behind the job board average, the 34 per cent growth figure, and what total comp really means.',
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
            ['name' => 'US Data Science & Analytics Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-data-scientist-aggregated']
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
                'position' => 'Data Scientist — Modelling, Experimentation and Applied Machine Learning, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours, with a large share of roles fully remote past entry level',
                'language' => 'English',
                // Published figures for this title mix base salary with total
                // compensation, and the job board average sits $18,531 above
                // the federal median. One band would repeat that confusion.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Modelling, experimentation and applied machine learning roles with US employers. Check whether a quoted figure is base salary or total compensation.',
                'seo_keywords' => 'data scientist jobs, data scientist salary, entry level data scientist jobs, remote data scientist jobs, machine learning engineer jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Banks, insurers, health systems, retailers, consumer technology companies and government contractors across the United States hire data scientists to build statistical and machine learning models against their own data. It is one of the fastest-growing occupations the federal government tracks, and one where the job title covers several genuinely different jobs.</p>

<h3>What the work involves</h3>
<p>Framing a business question as something measurable, building and validating models against it, designing and reading experiments, and getting the result into production where it changes a decision. In practice a large share of the week is data preparation and stakeholder conversation rather than modelling, which is the part the courses do not show.</p>

<h3>Requirements</h3>
<ul>
    <li>Python or R for statistical modelling and machine learning, and <strong>SQL</strong> at a level most candidates underestimate</li>
    <li>Machine learning frameworks, and experience of a model that reached production rather than a notebook</li>
    <li>Cloud analytics platforms for building and scaling, named specifically on most postings</li>
    <li>Business intelligence tools &mdash; Power BI, Tableau, Alteryx &mdash; on the more analyst-leaning roles</li>
    <li>The ability to explain a result to someone who will act on it and does not want the method</li>
    <li>Domain knowledge in finance, healthcare or the employer's sector, weighted more heavily than candidates expect</li>
</ul>

<h3>How the pay is reported</h3>
<ul>
    <li><strong>The measured figure</strong> is the federal median of <strong>$112,590</strong> a year for data scientists</li>
    <li><strong>Job board averages run higher</strong> &mdash; around $131,121 &mdash; because they average advertised postings rather than measuring paid wages</li>
    <li><strong>Total compensation figures are a different quantity.</strong> Packages quoted above $300,000 are base plus bonus plus equity at a small number of employers, not salaries</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask which of the several jobs this title covers.</strong> Modelling, experimentation, analytics and machine learning engineering are advertised under one heading and screened very differently. And when a number is quoted, establish whether it is base salary or total compensation before you compare it with anything.</p>

<p><strong>Note:</strong> pay, equity, remote eligibility and any sponsorship decision are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Data science is one of the few fields where the optimistic headline is actually true, and the salary reporting around it is still a mess. This page separates the two: the growth figure is better than the guides say, and the pay figures are less comparable than they look.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-data-scientist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128202; Browse Data Scientist Jobs in the USA &rarr;
    </a>
</div>

<h2>Entry Level Is Reported $21,997 Above Junior</h2>

<p>Read the seniority ladder as it is normally published:</p>

<ul>
    <li>Junior Data Scientist &mdash; <strong>$82,850</strong></li>
    <li>Entry-Level Data Scientist &mdash; <strong>$104,847</strong></li>
    <li>Senior Data Scientist &mdash; <strong>$159,664</strong></li>
    <li>Principal Data Scientist &mdash; <strong>$180,879</strong></li>
</ul>

<p>"Junior" and "entry-level" describe the same rung. The figures are <strong>$21,997 apart</strong>.</p>

<p>Nothing dishonest is happening; the two labels are simply attached to different sets of postings. "Junior Data Scientist" tends to appear on genuinely first-job roles, often at smaller employers. "Entry-Level Data Scientist" gets used by large companies for structured graduate programmes that pay considerably better and screen much harder.</p>

<p>That is worth knowing as a search strategy rather than as a complaint. <strong>The two titles reach different employers.</strong> Search both, and expect the process behind the higher number to be longer and more competitive.</p>

<h2>$131,121 and $112,590 Are Measuring Different Things</h2>

<p>The figure quoted everywhere is a national average of <strong>$131,121</strong>. The Bureau of Labor Statistics puts the median annual wage for data scientists at <strong>$112,590</strong>.</p>

<p>The gap is <strong>$18,531</strong>, and there are two reasons for it.</p>

<p><strong>Average versus median.</strong> Data science pay is skewed hard to the right by a small number of very large packages. An average is dragged up by them; a median is not. For any skewed distribution the median describes the typical person better, which is what you are trying to find out.</p>

<p><strong>Postings versus wages.</strong> Job board figures come from advertisements, which quote what an employer hopes to attract. Federal figures come from what employers report actually paying. Advertised bands run high, and they over-represent employers who advertise at all &mdash; larger, better-funded, in expensive cities.</p>

<p>So use $112,590 to judge whether an offer is normal, and treat $131,121 as the top of the market's own marketing.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/data-scientist-jobs-in-usa-salary.jpg"
         alt="A data scientist reviewing model results across multiple screens in a US office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>A Salary and a Total Compensation Figure Should Never Share a Paragraph</h2>

<p>This is the error that does the most damage to expectations, and every guide to this field makes it.</p>

<p>A national salary average of <strong>$131,121</strong> is stated, and then, two sentences later, that top offers reach <strong>"well into the $300,000+ range"</strong>. Read together they suggest a ladder: work hard, climb from one to the other.</p>

<p>They are not the same quantity.</p>

<p><strong>Salary</strong> is base pay. <strong>Total compensation</strong> is base plus annual bonus plus equity, and at large technology employers the equity is often the largest of the three. A $300,000 package might be $190,000 of base with the rest in stock vesting over four years, and the stock is worth what it is worth when it vests, not what it was worth when you signed.</p>

<p>Three things follow, and they are the practical part:</p>

<p><strong>Compare like with like.</strong> When you get an offer, ask for the split. Base, target bonus, equity value and vesting schedule are four separate numbers, and a competing offer with a higher headline can be worth less.</p>

<p><strong>Equity is not cash.</strong> It is subject to a vesting cliff, to the company still existing, and to the share price. Treat it as upside rather than as budget.</p>

<p><strong>Those packages exist at a handful of employers.</strong> They are real, and they are not the market. The market is $112,590.</p>

<h2>The Number the Guides Bury</h2>

<p>Now the good news, which the drafts describe with adjectives and should describe with figures.</p>

<p>Employment of data scientists is projected to grow <strong>34 per cent between 2024 and 2034</strong> &mdash; among the fastest-growing occupations the federal government publishes, against a total US employment picture growing at a small fraction of that. About <strong>23,400 openings</strong> are projected each year over the decade.</p>

<p>It is worth seeing that next to the occupation at the other end of the same building. <a href="/blog/data-entry-jobs-in-usa">Data entry keyers are projected to fall 25.9 per cent</a> over exactly the same decade, as automation absorbs work that requires no judgement. Same country, same ten years, opposite directions &mdash; and the thing that separates them is whether the job involves a decision.</p>

<p>That is the honest case for this field, and it is much stronger than a salary figure. You are not being told to chase a high number; you are being told the demand curve is going the right way for a long time.</p>

<h2>"Data Scientist" Is Several Jobs Under One Title</h2>

<p>The lists in most guides quietly include a role that is not data science. <strong>Big Data Engineer</strong> is listed among data science roles, described as designing and maintaining the pipelines that feed data science teams &mdash; which is an accurate description of <strong>data engineering</strong>, a different occupation with a different skill set and a different interview.</p>

<p>The distinction matters because the fragmentation is real and growing:</p>

<ul>
    <li><strong>Data scientist.</strong> Statistical modelling, experimentation and inference. Python or R, statistics, and the ability to frame a business question.</li>
    <li><strong>Machine learning engineer.</strong> Getting models into production and keeping them there. Closer to software engineering than to statistics.</li>
    <li><strong>Data engineer.</strong> Pipelines, warehouses and reliability. Rarely builds models at all.</li>
    <li><strong>Data analyst or analytics engineer.</strong> SQL, BI tools and reporting. The most common genuine entry point into all of the above.</li>
    <li><strong>Decision scientist.</strong> Experimentation and causal work aimed at a specific business decision.</li>
</ul>

<p>Employers use these titles inconsistently, so <strong>read the responsibilities rather than the heading</strong>. A "Data Scientist" posting that lists Spark, Airflow and warehouse design is a data engineering job, and a strong modeller will interview badly for it through no fault of their own. Our <a href="/blog/python-developer-jobs-in-usa">Python developer guide</a> covers the engineering half of that boundary.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/data-scientist-jobs-in-usa-skills.jpg"
         alt="A data scientist working with charts and analytics dashboards on a laptop"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Actually Gets Screened</h2>

<p>The skills lists in these guides are accurate and badly ordered. In terms of what decides interviews:</p>

<ul>
    <li><strong>SQL.</strong> Consistently the most underestimated item on the list. Nearly every screen includes it, and candidates who spent their preparation on modelling fail here.</li>
    <li><strong>A model that reached production.</strong> One deployed model that changed a decision outweighs a portfolio of notebooks. Be ready to describe the monitoring and what went wrong.</li>
    <li><strong>Python or R</strong>, with real familiarity with the libraries rather than syntax.</li>
    <li><strong>Explaining a result to someone who will act on it.</strong> Named on almost every posting as "communication", and tested in the final round more seriously than candidates expect.</li>
    <li><strong>Cloud platforms</strong>, named specifically. Match the one the employer runs; the concepts transfer but the interview asks about theirs.</li>
    <li><strong>Domain knowledge.</strong> Finance, healthcare and insurance weight this heavily, and it is often what separates two otherwise identical candidates.</li>
</ul>

<h2>Remote Work, and the Sponsorship Question</h2>

<p>A large share of mid-to-senior data science roles are advertised fully remote, which genuinely does make the field accessible regardless of location within the US. Entry-level roles are much less likely to be, because the on-the-job learning happens next to people.</p>

<p>On work authorisation, this is one of the occupations where the honest answer is more positive than most on this site. Data science sits in the specialty occupation category that <strong>H-1B</strong> is designed for, and larger technology, finance and pharmaceutical employers do sponsor for it. Two caveats worth setting expectations by: the H-1B route runs through an annual lottery rather than merit, and <strong>government and defence-related data science work is frequently clearance-gated</strong>, which requires US citizenship. Our <a href="/blog/cybersecurity-analyst-jobs-in-usa">cybersecurity analyst guide</a> sets out how clearances actually work.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do data scientists make in the USA?</h3>
<p>The federal median is $112,590 a year. Job board averages run higher, around $131,121, because they average advertised postings rather than measuring what employers pay, and an average is pulled up by a small number of very large packages.</p>

<h3>Why is entry-level pay reported higher than junior pay?</h3>
<p>Because the two titles reach different employers. "Junior" appears mostly on first jobs at smaller companies; "entry-level" is used by large employers for structured graduate programmes that pay more and screen harder. Search both.</p>

<h3>Is a $300,000 data science job real?</h3>
<p>Those figures are total compensation &mdash; base plus bonus plus equity &mdash; at a small number of employers, not salaries. Ask any offer to be split into base, target bonus, equity value and vesting schedule before comparing it.</p>

<h3>Is data science still growing as a career?</h3>
<p>Yes, and strongly. Employment is projected to grow 34 per cent between 2024 and 2034, with about 23,400 openings a year, against data entry work in the same country falling 25.9 per cent over the same decade.</p>

<h3>What is the difference between a data scientist and a data engineer?</h3>
<p>A data scientist builds models and runs experiments; a data engineer builds and maintains the pipelines and warehouses that feed them, and rarely builds models. They are separate occupations frequently advertised under one title.</p>

<h3>What skill do data science candidates most underestimate?</h3>
<p>SQL. It appears in nearly every screening round, and candidates who spent their preparation on machine learning are the ones who fail it.</p>

<h3>Are data scientist jobs remote?</h3>
<p>Many mid-to-senior roles are fully remote. Entry-level positions are much less likely to be, because early learning depends on working alongside a team.</p>

<h3>Can I get H-1B sponsorship as a data scientist?</h3>
<p>It is one of the better-placed occupations for it, and larger technology, finance and pharmaceutical employers do sponsor. The route runs through an annual lottery rather than on merit, and government or defence work is often clearance-gated, which requires citizenship.</p>

<h2>People Also Search For</h2>

<h3>Data scientist salary USA</h3>
<p>A federal median of $112,590, against a job board average of $131,121 that measures advertisements rather than wages.</p>

<h3>Entry level data scientist jobs</h3>
<p>Search both "junior" and "entry-level" &mdash; the two titles reach different employers and are reported $21,997 apart.</p>

<h3>Remote data scientist jobs</h3>
<p>Common past entry level, scarcer at the start, where sitting near a team is most of the learning.</p>

<h3>Machine learning engineer jobs</h3>
<p>The production half of the field, closer to software engineering than to statistics.</p>

<h3>Data scientist vs data analyst</h3>
<p>Analyst work is SQL, BI and reporting, and is the most common genuine route into modelling roles.</p>

<h3>Senior data scientist salary</h3>
<p>Reported around $159,664, with principal roles around $180,879 &mdash; base salary, not total compensation.</p>

<h3>Data science job growth 2034</h3>
<p>34 per cent projected growth and about 23,400 openings a year over the decade to 2034.</p>

<h3>Data scientist jobs with visa sponsorship</h3>
<p>Genuinely available at larger employers through H-1B, subject to an annual lottery. Clearance-gated roles need citizenship.</p>

<h2>More Job Guides</h2>

<p>Comparing technical career paths? These cover them:</p>

<ul>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; the same country and decade, moving 25.9 per cent the other way.</li>
    <li><a href="/blog/python-developer-jobs-in-usa">Python Developer Jobs in USA</a> &mdash; the engineering side of the same language and the boundary with modelling.</li>
    <li><a href="/blog/software-developer-jobs-in-usa">Software Developer Jobs in USA</a> &mdash; the occupation machine learning engineering is really counted under.</li>
    <li><a href="/blog/cloud-engineer-jobs-in-usa">Cloud Engineer Jobs in USA</a> &mdash; the platforms these models actually run on.</li>
    <li><a href="/blog/aws-cloud-engineer-jobs-in-usa">AWS Cloud Engineer Jobs in USA</a> &mdash; the largest of those platforms, and its certification trap.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; how security clearances work, which gates a lot of government data science.</li>
    <li><a href="/blog/full-stack-developer-jobs-in-usa">Full Stack Developer Jobs in USA</a> &mdash; another route where one job title covers several jobs.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the same salary-reporting problems at the other end of the wage scale.</li>
    <li><a href="/blog/database-administrator-jobs-in-usa">Database Administrator Jobs in USA</a> &mdash; who keeps the data you analyse available, and the certification that no longer exists.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, financial or immigration advice. Wage data, employment projections and visa rules change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with the Bureau of Labor Statistics and the employer's own advertisement before applying or paying for any course.</p>
HTML;
    }
}
