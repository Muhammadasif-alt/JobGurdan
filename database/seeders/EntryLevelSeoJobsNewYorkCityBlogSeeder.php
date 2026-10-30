<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Entry-Level SEO Jobs in New York City, New York"
 *
 * Checked on 22 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief has ten application buttons and search links to a commercial job board. All are dropped, and the job board is not named.
 * 2. Dropped every private-employer example and figure: the Sosemo SmartRecruiters advertisement (degree, internship, office address, office days) and the Amsive internship (dates, hourly pay, hours). Private advertisements go stale and cannot be checked on a live page; the guide instead says honestly that titles vary and lists what to check in any advertisement.
 * 3. Kept the brief's advice on foundations, tools, portfolio, interview and training, rewritten. Google SEO Starter Guide and Search Console pages were fetched on 22 October 2026 and quoted. The search marketing task list comes from the O*NET page for 13-1161.01, also fetched.
 * 4. Added New York City Workforce1 (nyc.gov, fetched 22 October 2026): free services, workshops, one-on-one counselling, centres listed by borough. dol.ny.gov blocked automated access, so no state job bank claim is made.
 * 5. Added the FTC job scam advice (fetched). The brief's ten-links FAQ is removed and replaced. No salary figure is given because none could be checked against an official page.
 * 6. Hosts proposed as new: developers.google.com, www.nyc.gov, consumer.ftc.gov. Already allowed: www.onetonline.org, reportfraud.ftc.gov.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class EntryLevelSeoJobsNewYorkCityBlogSeeder extends Seeder
{
    public const SLUG = 'entry-level-seo-jobs-new-york-city';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Entry-Level SEO Jobs in New York City, New York',
                'excerpt' => 'A guide to entry-level SEO jobs in New York City: what junior SEO work involves, how to read job titles, which skills and free tools to learn, how to build an honest portfolio and where free career help is.',
                'content' => $content,
                'featured_image' => 'blogs/entry-level-seo-jobs-new-york-city.jpg',
                'tags' => 'entry level seo jobs new york city, seo assistant jobs nyc, junior seo analyst nyc, seo internships new york, google search console, workforce1 career centers, seo portfolio, job scams',
                'meta_title' => 'Entry-Level SEO Jobs in New York City: Skills & Steps',
                'meta_description' => 'Entry-level SEO jobs in New York City: what the work involves, how to read titles, skills, a small portfolio and the free Workforce1 career help.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Entry-level SEO jobs in New York City usually involve keyword research, website content updates, basic technical checks and performance reporting.</strong> Titles such as SEO associate, junior SEO analyst, organic search intern and SEO coordinator all appear, and requirements differ: some employers accept coursework and personal projects, while others ask for a degree or a related internship even when the job is called entry-level. This guide explains the work, the skills and how to prepare, and it does not list or promise any vacancy.</p>

<h2>What an Entry-Level SEO Role Involves</h2>

<p>SEO, short for search engine optimisation, is work that helps search engines understand a website's content and helps people decide whether to visit it. Google's own SEO Starter Guide is blunt that there are no shortcuts: it says "there are no secrets here that'll automatically rank your site first in Google." Any job advertisement or course that promises a first position should be read with caution.</p>

<p>The US government's occupational database, O*NET, describes search marketing strategists with tasks such as these: managing tracking and reporting of search-related activities, optimising digital assets such as text, graphics or multimedia for search engines, collecting and analysing web metrics such as visits and page views, and coordinating with developers on site architecture and page construction. At the junior level this usually means a smaller share of that work: research, spreadsheets, page updates and reporting, done under supervision. See the <a href="https://www.onetonline.org/link/summary/13-1161.01" rel="noopener">O*NET summary for Search Marketing Strategists</a>.</p>

<h2>Why Job Titles Need Careful Reading</h2>

<p>Entry-level SEO titles in New York City vary a great deal. You may see SEO associate, SEO coordinator, junior SEO analyst, organic search intern or digital marketing assistant, and the same title can mean different seniority at different employers. This guide does not cite any single private vacancy, because advertisements close or change quickly. Instead, read every advertisement for these points.</p>

<ul>
<li><strong>Mandatory or preferred.</strong> A degree, a related internship or a number of years can appear under "requirements" for a job described as entry-level. Separate what is required from what is only nice to have.</li>
<li><strong>Analyst does not always mean beginner.</strong> Check the years of experience and who you would report to.</li>
<li><strong>Internship or job.</strong> An internship may pay by the hour and run for a fixed season. Ask about hours per week, the end date and whether it leads to anything.</li>
<li><strong>Pay type.</strong> An hourly rate and an annual salary are not the same thing, so check which one the advertisement states and how many hours are expected.</li>
<li><strong>Location.</strong> Confirm whether the office is in New York City or elsewhere in New York State, how many days you must attend and whether the commute is realistic.</li>
</ul>

<h2>Skills Worth Building First</h2>

<p>Start with the basics that Google's guide covers. It says a good page title "is unique to the page, clear and concise, and accurately describes the contents of the page", and that a good meta description "is short, unique to one particular page, and includes the most relevant points of the page." It also says link text "tells users and Google something about the page you're linking to", and it advises writing content that readers "will find helpful and reliable."</p>

<p>Learn search intent, which is what a person actually wants when they type a query, then practise writing titles, headings and internal links that match it. A junior SEO also needs spreadsheet confidence: sorting, filters, simple formulas and clear charts. Basic HTML helps you read a page's title and heading tags, but many entry-level tasks are research, writing and reporting rather than programming.</p>

<h2>Free Tools to Practise With</h2>

<p>You do not need to buy every SEO platform. Google describes Search Console as a tool that provides information on how Google crawls, indexes and serves websites, covering search performance by queries, pages and countries, indexing problems, sitemaps and structured data. The Starter Guide recommends setting up a Search Console account to monitor how your website performs on Google Search. Practise on a small site you control, such as a personal blog.</p>

<p>Official pages: <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide" rel="noopener">Google SEO Starter Guide</a> and <a href="https://developers.google.com/search/docs/monitor-debug/search-console-start" rel="noopener">Get started with Search Console</a>.</p>

<h2>Build a Small, Honest Portfolio</h2>

<p>Employers reading junior applications usually want to see how you think. Three small pieces are enough.</p>

<ol>
<li><strong>A keyword map.</strong> A spreadsheet that groups search phrases by topic and intent and matches each group to a page.</li>
<li><strong>A content brief.</strong> One page that tells a writer the topic, audience, suggested headings, questions to answer and pages to link to.</li>
<li><strong>A prioritised website review.</strong> Check a small site's titles, headings, broken links and mobile display, then rank your recommendations by effort and likely value.</li>
</ol>

<p>Label every piece as practice work. Do not invent traffic figures, rankings or clients, and do not claim results you did not produce. An interviewer can ask you to explain any number on your page.</p>

<h2>Free Career Help in New York City</h2>

<p>The New York City Department of Small Business Services says it "offers free services at our network of Workforce1 Career Centers." The Workforce1 page says the centres offer career workshops and one-on-one career counselling, connect job seekers to training opportunities, give interview advice and help with résumés and cover letters, and share job search tools and strategies. It says Workforce1 can help you find and prepare for the job you want "for free." The page lists centres by borough, with a map of all locations.</p>

<p>Official pages: <a href="https://www.nyc.gov/workforce1" rel="noopener">Workforce1 Career Centers</a> and the <a href="https://www.nyc.gov/site/sbs/careers/careers.page" rel="noopener">SBS careers page</a>. A career centre cannot promise a marketing job, but a résumé review is a free way to check your SEO application before you send it.</p>

<h2>Résumé and Interview Preparation</h2>

<p>Put your practice projects near the top of your résumé, name the tools you used and describe what you did in plain verbs. Expect questions about prioritisation, such as which three fixes you would make first on a site with limited developer time, and about problem solving, such as how you would investigate a fall in organic visits. A sound answer checks whether the fall is real, looks at which pages and queries changed, checks for recent site changes, and only then proposes a fix. It is fine to say you would verify before you conclude.</p>

<p>Ask about training too: who reviews junior work, how feedback is given, and which tools and reports you will be trusted with in the first months. Keep a simple log of applications with the date, the advertisement text and the employer's own careers page, so you can tell a repeated posting from a new one.</p>

<h2>Protect Yourself From Job Scams</h2>

<p>Marketing jobs advertised online attract fake offers as much as any other field. The Federal Trade Commission says that "honest employers, including the federal government, will never ask you to pay to get a job", and that "no honest potential employer will ever send you a check to deposit and then tell you to send on part of the money." So do not pay for starter kits, training, certifications or equipment before a written offer, and do not deposit a cheque and forward part of it.</p>

<p>If you paid a scammer, the FTC advises contacting the company you used to send the money straight away and asking to reverse the transaction if possible. Report the scam at <a href="https://reportfraud.ftc.gov/" rel="noopener">ReportFraud.ftc.gov</a>. Read the FTC's page on <a href="https://consumer.ftc.gov/articles/job-scams" rel="noopener">job scams</a> for the warning signs.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I start without professional SEO experience?</h3>
<p>Some internships and junior roles consider coursework and practice projects, while others ask for a related internship. Read each advertisement for what is required and what is only preferred.</p>

<h3>Is a degree always required?</h3>
<p>No, it varies by employer. Where an advertisement lists a degree as required, a portfolio is unlikely to replace it, but it can support an application where the degree is only preferred.</p>

<h3>Do I need programming experience?</h3>
<p>Basic HTML helps you read titles, headings and links on a page. Many junior tasks are research, writing and reporting rather than programming.</p>

<h3>Are entry-level SEO jobs in New York City fully remote?</h3>
<p>Not necessarily. Some roles ask for office days, so check the attendance rules, the office location and the commute before you apply.</p>

<h2>Bottom Line</h2>

<p>Entry-level SEO jobs in New York City come through internships, associate positions and junior support roles, and no guide can promise one. Read each advertisement for what is required, build a small honest portfolio, use the free career help available in the city and never pay to be placed. Platform and employer details change, so check the current page before you rely on it.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide" rel="noopener">Google Search Central: SEO Starter Guide</a></li>
<li><a href="https://developers.google.com/search/docs/monitor-debug/search-console-start" rel="noopener">Google Search Central: Get started with Search Console</a></li>
<li><a href="https://www.onetonline.org/link/summary/13-1161.01" rel="noopener">O*NET OnLine: Search Marketing Strategists</a></li>
<li><a href="https://www.nyc.gov/workforce1" rel="noopener">NYC: Workforce1 Career Centers</a></li>
<li><a href="https://www.nyc.gov/site/sbs/careers/careers.page" rel="noopener">NYC Department of Small Business Services: Careers</a></li>
<li><a href="https://consumer.ftc.gov/articles/job-scams" rel="noopener">Federal Trade Commission: Job scams</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/on-page-seo-assistant-jobs">On-Page SEO Assistant Jobs</a>: a related junior route with similar tasks.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a>: wider marketing roles compared.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

HTML;
    }
}
