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
 * "DevOps Engineer Jobs in Germany" — cloud, CI/CD and platform engineering
 * for German employers. The US DevOps guide covers the American market, and
 * this one owns German pay from the Federal Employment Agency, the EU Blue
 * Card thresholds and what a salary is worth after social contributions.
 *
 * Corrections to the draft:
 *
 * 1. Its junior floor of €45,000 is below the 2026 EU Blue Card minimum for
 *    IT specialists, €45,934.20, so a non-EU junior on that salary cannot get
 *    the card. The general threshold is €50,700.
 *
 * 2. Its salary bands have no source. The Federal Employment Agency files
 *    DevOps engineers under KldB 43323, where the median full-time gross was
 *    €5,864 a month at the end of 2025, about €70,368 a year.
 *
 * 3. Its city ranking puts Munich, Berlin and Frankfurt top for pay. For this
 *    occupation Stuttgart pays most and Frankfurt least of the five, and
 *    North Rhine-Westphalia, which it omits, employs the most.
 *
 * 4. It cites a skills shortage as settled fact. The agency's 2025 shortage
 *    analysis no longer lists software development as a shortage occupation;
 *    Bitkom, an industry association, still reports 109,000 unfilled IT posts.
 *
 * 5. Its poster promised visa sponsorship. Germany has no sponsorship system:
 *    you apply for the Blue Card yourself with a job offer, so the label was
 *    changed to EU Blue Card.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DevOpsEngineerJobsGermanyBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://de.indeed.com/q-devops-engineer-jobs.html';

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
        $title = 'DevOps Engineer Jobs in Germany';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'German DevOps engineers earn a median of €5,864 a month, a €45,000 junior offer misses the 2026 Blue Card minimum for IT specialists, Stuttgart pays more than Munich, and software development is no longer an official shortage job.',
                'content' => $content,
                'featured_image' => 'blogs/devops-engineer-jobs-in-germany.jpg',
                'tags' => 'devops engineer jobs germany, devops salary germany, eu blue card it specialist, blue card salary 2026, devops jobs berlin, devops jobs munich, opportunity card germany, it jobs germany english, kubernetes jobs germany',
                'meta_title' => 'DevOps Engineer Jobs in Germany 2026: Pay and Blue Card',
                'meta_description' => 'DevOps engineer jobs in Germany: 2025 pay by city, the EUR 45,934.20 Blue Card minimum for IT specialists, net pay after contributions and the skills shortage.',
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
            ['name' => 'German Tech Companies, Enterprises & Startups (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'de-devops-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Germany'],
            ['area' => 'Nationwide', 'country' => 'Germany']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'DevOps Engineer — Cloud, CI/CD and Platform Teams, German Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, often hybrid, with on-call rotas in many operations teams',
                'language' => 'English, German',
                // Pay varies by city, seniority and company, and the official
                // figures stop at the pension contribution ceiling, so no single
                // range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'DevOps, SRE and platform engineering roles with German employers. EU Blue Card eligible where the salary meets the 2026 threshold.',
                'seo_keywords' => 'devops engineer jobs germany, site reliability engineer germany, platform engineer jobs, cloud engineer germany, kubernetes jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Software companies, banks, carmakers, industrial groups and startups across Germany hire DevOps engineers, site reliability engineers and platform engineers to run cloud infrastructure and delivery pipelines. Many international teams work in English.</p>

<h3>What the work involves</h3>
<p>Building and maintaining CI/CD pipelines, running containers on Kubernetes, managing infrastructure as code with tools such as Terraform, monitoring systems and responding to incidents, and working with developers to ship software safely.</p>

<h3>Requirements</h3>
<ul>
    <li>Hands-on experience with Linux, containers, a major cloud platform and at least one scripting language</li>
    <li>A recognised degree, or at least three years of IT experience in the last seven for the EU Blue Card without a degree</li>
    <li>For non-EU applicants, a job offer paying at least &euro;45,934.20 a year in 2026 to qualify for the Blue Card as an IT specialist</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Official median.</strong> &euro;5,864 a month gross for full-time staff at the end of 2025, according to the Federal Employment Agency</li>
    <li><strong>By age.</strong> &euro;3,977 under 25, rising to &euro;7,046 at 55 and over</li>
    <li><strong>Deductions.</strong> Employees pay roughly a fifth of gross pay in social contributions before income tax</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the gross annual salary against the Blue Card threshold</strong> before you accept an offer from outside the EU.</p>

<p><strong>Note:</strong> pay, working language and visa eligibility are set by each employer and by German immigration law &mdash; not by JobGader. Confirm the details with the employer and the German mission before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>DevOps engineers run the pipelines and cloud platforms German companies ship software on, and many teams in Berlin, Munich and beyond work in English. Before you apply from abroad, it helps to know four things most guides get wrong: what the official pay figures show, whether a junior salary clears the EU Blue Card, which cities really pay most, and whether the skills shortage is still what it was.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://de.indeed.com/q-devops-engineer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128640; Browse DevOps Engineer Jobs in Germany &rarr;
    </a>
</div>

<h2>What DevOps Engineers Earn: The Official Figures</h2>

<p>Guides put junior DevOps engineers at &euro;45,000 to &euro;58,000, mid-level at &euro;58,000 to &euro;75,000, seniors at &euro;75,000 to &euro;95,000 and leads at &euro;95,000 to &euro;120,000, without saying where the numbers come from. Germany's Federal Employment Agency publishes real pay from social insurance records in its <strong>Entgeltatlas</strong>. It files DevOps engineers under <strong>IT coordination, complex specialist tasks (KldB 43323)</strong>, and at the end of 2025 full-time employees in that group earned:</p>

<ul>
    <li><strong>Median:</strong> <strong>&euro;5,864 a month</strong> gross, about <strong>&euro;70,368 a year</strong></li>
    <li><strong>Middle half:</strong> &euro;4,649 to &euro;7,250 a month, about &euro;55,788 to &euro;87,000 a year</li>
    <li><strong>Under 25:</strong> &euro;3,977 a month, about &euro;47,724 a year</li>
    <li><strong>25 to 54:</strong> &euro;5,709 a month</li>
    <li><strong>55 and over:</strong> &euro;7,046 a month</li>
</ul>

<p>For comparison, software development experts (KldB 43414) had a median of &euro;6,301 a month, and the median for all full-time employees in Germany was &euro;4,217. The official figures cannot show the top of the market: pay above the pension contribution ceiling of &euro;8,050 a month in 2025 is not recorded in full, so the lead-engineer salaries guides quote above roughly &euro;96,600 a year cannot be checked against them.</p>

<h2>A &euro;45,000 Junior Offer Misses the Blue Card</h2>

<p>For anyone from outside the EU, the salary is not just pay &mdash; it decides the visa. The <strong>EU Blue Card</strong> minimum gross salaries for 2026 are:</p>

<ul>
    <li><strong>General threshold:</strong> <strong>&euro;50,700</strong> a year</li>
    <li><strong>IT specialists, shortage occupations and recent graduates:</strong> <strong>&euro;45,934.20</strong> a year, with approval from the Federal Employment Agency</li>
</ul>

<p>The guides' junior floor of &euro;45,000 is <strong>&euro;934.20 below</strong> even the reduced threshold, so a junior offer at that level does not qualify. The thresholds rise every year &mdash; in 2025 they were &euro;48,300 and &euro;43,759.80 &mdash; so an offer that cleared last year's figure may not clear this year's.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/devops-engineer-jobs-in-germany-berlin.jpg"
         alt="A DevOps engineer working on a laptop near the Brandenburg Gate in Berlin"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Stuttgart Pays More Than Munich, and Frankfurt Least</h2>

<p>Guides say salaries are highest in Munich, Berlin and Frankfurt. The Entgeltatlas medians for KldB 43323 at the end of 2025 rank the five cities they name differently:</p>

<ul>
    <li><strong>Stuttgart:</strong> &euro;6,394 a month</li>
    <li><strong>Munich:</strong> &euro;6,258</li>
    <li><strong>Berlin:</strong> &euro;6,049</li>
    <li><strong>Hamburg:</strong> &euro;5,987</li>
    <li><strong>Frankfurt am Main:</strong> &euro;5,897</li>
</ul>

<p>By state, Baden-W&uuml;rttemberg (&euro;6,232) and Bavaria (&euro;6,213) pay most, while Saxony (&euro;5,214) pays least among the states with published figures. The guides also miss the biggest employer: <strong>North Rhine-Westphalia</strong>, home to Cologne and D&uuml;sseldorf, has 6,280 full-time employees in this group, more than Bavaria's 5,669 or Berlin's 2,093.</p>

<h2>Is There Still a Skills Shortage?</h2>

<p>Guides call the IT skills shortage "widely reported". The official picture has changed:</p>

<ul>
    <li>The Federal Employment Agency's <strong>2025 shortage analysis</strong> found <strong>no longer any shortage in software development</strong> or IT sales.</li>
    <li>Its June 2026 report on IT jobs found shortages only in computer science without a specialisation and in technical computer science at specialist level, with software development "under observation".</li>
    <li><strong>Bitkom</strong>, the German digital industry association, still reports about <strong>109,000 unfilled IT jobs</strong> in its 2025 survey &mdash; down from 149,000 two years earlier &mdash; and says a vacancy takes 7.7 months to fill on average.</li>
</ul>

<p>In practice, experienced DevOps engineers with Kubernetes, cloud and security skills remain in demand, but junior applicants from abroad face more competition than the shortage headlines suggest.</p>

<h2>EU Blue Card Rules That Matter for DevOps Engineers</h2>

<ul>
    <li><strong>No degree needed for IT specialists</strong> with at least <strong>three years</strong> of comparable experience gained in the last seven years. You still need a job offer of at least six months and the reduced salary threshold.</li>
    <li><strong>Degree recognition.</strong> If you rely on a degree, the university must be rated H+ in the anabin database and the degree rated as equivalent. Otherwise you need a Statement of Comparability from the ZAB, which costs <strong>&euro;208</strong>.</li>
    <li><strong>No German required</strong> for the Blue Card itself.</li>
    <li><strong>Permanent residence</strong> after <strong>27 months</strong> with basic German (A1), or <strong>21 months</strong> with B1.</li>
    <li><strong>Family.</strong> Spouses do not need German before arriving and can work without restriction.</li>
    <li><strong>Fees and process.</strong> The national visa costs &euro;75, and an employer can use the fast-track skilled worker procedure for &euro;411. Applications can be filed online through the Federal Foreign Office portal, though an in-person appointment is still needed. Since 1 July 2025, a refused visa can only be challenged in court, not by an informal appeal.</li>
</ul>

<h2>Looking From Abroad: The Opportunity Card</h2>

<p>If you do not have an offer yet, the <strong>Opportunity Card</strong> (Chancenkarte), available since <strong>1 June 2024</strong>, lets you move to Germany to look for work. You need at least <strong>six points</strong> for qualifications, language, experience and age, proof of about <strong>&euro;1,091 a month</strong> in 2026, and you may work up to <strong>20 hours a week</strong> while you search, plus two-week trial jobs.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/devops-engineer-jobs-in-germany-career.jpg"
         alt="A DevOps engineer at a desk with a Kubernetes and CI/CD dashboard, the Berlin TV tower and Brandenburg Gate in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>From Gross to Net: What Comes Off Your Salary</h2>

<p>German gross salaries look higher than take-home pay. Employees pay half of most social insurance contributions in 2026:</p>

<ul>
    <li><strong>Pension:</strong> 18.6% in total, so 9.3% from you, up to &euro;8,450 a month of pay</li>
    <li><strong>Unemployment:</strong> 2.6%, so 1.3% from you</li>
    <li><strong>Health:</strong> 14.6% plus an average additional contribution of 2.9%, split with the employer, up to &euro;5,812.50 a month</li>
    <li><strong>Long-term care:</strong> 3.6%, of which you pay 1.8%, or more if you have no children</li>
</ul>

<p>On a &euro;70,000 salary, that comes to about <strong>&euro;1,230 a month</strong>, roughly 21% of gross pay, before income tax &mdash; a little more if you are childless or your health insurer charges above the average. Compare offers on net pay, not on the headline figure.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does a DevOps engineer earn in Germany?</h3>
<p>The Federal Employment Agency put the median for full-time employees in the DevOps occupation group at &euro;5,864 a month gross at the end of 2025, about &euro;70,368 a year. The middle half earned &euro;4,649 to &euro;7,250 a month.</p>

<h3>What is the EU Blue Card salary for IT specialists in 2026?</h3>
<p>&euro;45,934.20 a year, with Federal Employment Agency approval. The general threshold is &euro;50,700.</p>

<h3>Can I get a Blue Card as a DevOps engineer without a degree?</h3>
<p>Yes, if you have at least three years of comparable IT experience in the last seven years, a job offer of at least six months and a salary above the reduced threshold.</p>

<h3>Do I need to speak German to work as a DevOps engineer in Germany?</h3>
<p>Not for the Blue Card, and many international teams work in English. German does shorten the route to permanent residence, from 27 months with A1 to 21 months with B1.</p>

<h3>Which German city pays DevOps engineers the most?</h3>
<p>Stuttgart, with a median of &euro;6,394 a month for the DevOps occupation group at the end of 2025, followed by Munich at &euro;6,258 and Berlin at &euro;6,049.</p>

<h3>Is there a shortage of DevOps engineers in Germany?</h3>
<p>Less than guides suggest. The Federal Employment Agency no longer lists software development as a shortage occupation, although the industry association Bitkom still reports about 109,000 unfilled IT jobs.</p>

<h3>How much tax and social insurance do I pay in Germany?</h3>
<p>Employee social contributions take roughly 21% of a &euro;70,000 salary in 2026, before income tax, depending on your health insurer and whether you have children.</p>

<h3>Can I move to Germany to look for a DevOps job?</h3>
<p>With the Opportunity Card, if you score at least six points and show about &euro;1,091 a month in funds. You can work up to 20 hours a week while you search.</p>

<h2>People Also Search For</h2>

<h3>DevOps engineer salary Germany</h3>
<p>A median of &euro;5,864 a month gross at the end of 2025, about &euro;70,368 a year.</p>

<h3>Blue Card salary 2026 Germany</h3>
<p>&euro;50,700 in general and &euro;45,934.20 for IT specialists and recent graduates.</p>

<h3>DevOps jobs Berlin</h3>
<p>A median of &euro;6,049 a month for the DevOps occupation group, below Stuttgart and Munich.</p>

<h3>DevOps jobs Munich</h3>
<p>A median of &euro;6,258 a month, second among the five largest tech cities.</p>

<h3>IT jobs in Germany for English speakers</h3>
<p>The Blue Card needs no German, though B1 German shortens the wait for permanent residence to 21 months.</p>

<h3>Opportunity Card Germany</h3>
<p>A points-based job search visa since June 2024, with part-time work allowed while you look.</p>

<h3>Anabin H+ degree recognition</h3>
<p>An H+ university and an equivalent degree rating, or a ZAB Statement of Comparability for &euro;208.</p>

<h3>Net salary Germany 70000</h3>
<p>About &euro;1,230 a month goes to employee social contributions before income tax.</p>

<h2>More Job Guides</h2>

<p>Comparing DevOps and cloud work in Germany and the US? These cover it:</p>

<ul>
    <li><a href="/blog/devops-engineer-jobs-in-usa">DevOps Engineer Jobs in USA</a> &mdash; the same role in the American market, and the visa routes there.</li>
    <li><a href="/blog/cloud-engineer-jobs-in-usa">Cloud Engineer Jobs in USA</a> &mdash; the platform work DevOps teams build on.</li>
    <li><a href="/blog/aws-cloud-engineer-jobs-in-usa">AWS Cloud Engineer Jobs in USA</a> &mdash; the certifications behind the most common cloud platform.</li>
    <li><a href="/blog/python-developer-jobs-in-usa">Python Developer Jobs in USA</a> &mdash; the scripting language most pipelines run on.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; the infrastructure side of operations work.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; the other German route, and why it works so differently.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, tax or financial advice. Salary thresholds, contribution rates and immigration rules change every year. Confirm the current position with the employer, the German mission, Make it in Germany and a qualified tax adviser before applying or accepting an offer.</p>
HTML;
    }
}
