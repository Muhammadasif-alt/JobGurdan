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
 * "Remote Jobs in USA" — remote and hybrid work with American employers. The
 * remote customer service and data entry guides cover those two roles and
 * the Pakistan guide covers entry-level remote work from Pakistan, so this
 * one owns how much Americans telework, BLS pay for the occupations most
 * done from home, the I-9, employment and tax rules, and FTC scam data.
 *
 * Corrections to the draft:
 *
 * 1. Its pay bands sit below the BLS. Software developers earned a median
 *    of $135,980 in May 2025, and even the lowest-paid tenth earned $82,460,
 *    against its $70,000 to $120,000 tech range. Marketing specialists earned
 *    $78,760 against its $45,000 to $65,000, and customer service
 *    representatives $44,770, above the top of its entry-level band.
 *
 * 2. It calls remote work one of the fastest-growing parts of the job
 *    market. The BLS telework rate was 21.6% in August 2026, down from 22.1%
 *    a year earlier, and the Census share working from home has fallen every
 *    year since its 2021 peak.
 *
 * 3. It never says who a US remote job is open to. A job performed in the
 *    United States needs a Form I-9, and someone working from abroad is paid
 *    foreign-source income and gives the payer a Form W-8, not a W-9.
 *
 * 4. It leaves out scams. The FTC says reported losses to job scams rose
 *    from $90 million in 2020 to $501 million in 2024.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RemoteJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-remote-jobs.html';

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
        $title = 'Remote Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Remote software developers are paid far above the range most guides quote, a US remote job usually still needs US work authorization, and job scam losses reported to the FTC rose from $90 million to $501 million in four years.',
                'content' => $content,
                'featured_image' => 'blogs/remote-jobs-in-usa.jpg',
                'tags' => 'remote jobs usa, work from home jobs usa, remote jobs salary, telework statistics, remote customer service jobs usa, remote software developer salary, remote job scams, 1099 remote jobs, remote jobs for foreigners',
                'meta_title' => 'Remote Jobs in USA 2026: Real Pay, Rules and Scams',
                'meta_description' => 'Remote jobs in the USA: BLS pay for the roles most done from home, how many Americans telework, the I-9 and tax rules, and the FTC job scam warning signs.',
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
            ['name' => 'US Remote Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-remote-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Remote Jobs — Customer Service, Sales, Marketing, Admin and Tech Roles, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Fully remote or hybrid; many roles set working hours in a US time zone',
                'language' => 'English',
                // Remote roles run from data entry to software engineering, so
                // no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Remote and hybrid customer service, sales, marketing, admin and tech roles with US employers. US work authorization usually required.',
                'seo_keywords' => 'remote jobs usa, work from home jobs, remote customer service jobs, remote software developer jobs, remote marketing jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US employers in customer service, sales, marketing, administration and technology hire remote staff, some fully remote and some hybrid.</p>

<h3>What the work involves</h3>
<p>It depends on the role: handling customers by phone, chat or email, running sales and marketing work, administrative support, or building and supporting software, all through video calls and shared tools with a distributed team.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>Authorization to work in the US</strong> for a job performed in the US &mdash; the employer completes Form I-9, and can check your documents remotely if it uses E-Verify</li>
    <li>A reliable computer and internet connection, and a quiet place to take calls</li>
    <li>The skills of the role itself, from customer handling to programming</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>BLS medians, May 2025.</strong> $44,770 for customer service representatives, $78,760 for marketing specialists and $135,980 for software developers</li>
    <li><strong>Employees and contractors.</strong> Employees are owed minimum wage and overtime; independent contractors are not, and pay 15.3% self-employment tax</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay to get a job.</strong> The FTC says honest employers will never ask you to pay for a job, and a company that sends you a cheque to buy equipment and asks for the change back is running a scam.</p>

<p><strong>Note:</strong> pay, hours, benefits and eligibility are set by each employer and by federal and state law &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Remote jobs with American employers are real and many pay well, but they are not the open door most guides describe. Before you apply, it helps to know four things they get wrong: how many Americans actually work from home, what the roles really pay, who a US remote job is open to, and how common the scams have become.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-remote-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Browse Remote Jobs in USA &rarr;
    </a>
</div>

<h2>How Many Americans Work Remotely</h2>

<p>Guides call remote work one of the fastest-growing parts of the job market. The official figures show it is large, but no longer growing:</p>

<ul>
    <li><strong>BLS, August 2026:</strong> <strong>21.6%</strong> of people at work teleworked, about 33.6 million, with 10.6% working from home for all their hours and 11.0% for some. A year earlier the rate was 22.1%.</li>
    <li><strong>BLS, 2025:</strong> an annual average of 22.4%, and the BLS said in April 2026 that the rate had ranged from 21.5% to 23.0% over the previous year.</li>
    <li><strong>Census Bureau:</strong> 13.3% of workers usually worked from home in 2024, down from 13.8% in 2023 and a peak of 17.9% in 2021, though still far above 5.7% in 2019.</li>
    <li><strong>Job postings:</strong> Indeed's Hiring Lab, a private source, found remote or hybrid terms in 8.44% of US postings at the end of August 2026, against a peak of 10.47% in February 2022.</li>
</ul>

<p>The federal government has moved the other way. A presidential memorandum of <strong>20 January 2025</strong> told agencies to end remote work arrangements and bring employees back to in-person work full time, with exemptions agency heads deem necessary.</p>

<h2>Which Jobs Are Actually Remote</h2>

<p>Whether you can work from home depends mostly on the occupation. BLS telework rates for August 2026:</p>

<ul>
    <li><strong>Computer and mathematical:</strong> <strong>60.8%</strong></li>
    <li><strong>Business and financial operations:</strong> 52.8%</li>
    <li><strong>Management:</strong> 36.4%</li>
    <li><strong>Sales and related:</strong> 23.9%</li>
    <li><strong>Office and administrative support:</strong> 22.8%</li>
    <li><strong>Healthcare practitioners and technical:</strong> 12.9%</li>
    <li><strong>Service:</strong> 5.8%</li>
    <li><strong>Transportation and material moving:</strong> 1.7%</li>
</ul>

<p>Education matters too: 37.7% of workers with a bachelor's degree or higher teleworked, against 8.7% of high school graduates with no college. Entry-level office roles such as customer service and data entry are remote far less often than tech and finance jobs.</p>

<h2>What Remote Jobs Pay: BLS Medians</h2>

<p>Guides give entry-level remote roles $30,000 to $42,000 a year, marketing $45,000 to $65,000 and tech $70,000 to $120,000. The Bureau of Labor Statistics does not publish pay for remote jobs separately, but its <strong>May 2025</strong> medians for the occupations those guides name are mostly higher:</p>

<ul>
    <li><strong>Data entry keyers:</strong> $41,340</li>
    <li><strong>Customer service representatives:</strong> <strong>$44,770</strong></li>
    <li><strong>Secretaries and administrative assistants:</strong> $47,540</li>
    <li><strong>Computer user support specialists:</strong> $61,860</li>
    <li><strong>Sales representatives of services:</strong> $69,990</li>
    <li><strong>Market research analysts and marketing specialists:</strong> $78,760</li>
    <li><strong>Web developers:</strong> $92,650</li>
    <li><strong>Data scientists:</strong> $120,230</li>
    <li><strong>Information security analysts:</strong> $129,180</li>
    <li><strong>Software developers:</strong> <strong>$135,980</strong></li>
</ul>

<p>The guides' tech range tops out below the software developer median, and even the lowest-paid tenth of software developers earned <strong>$82,460</strong>. Some remote employers set pay by where you live, so ask whether the advertised range depends on your location.</p>

<h2>A US Remote Job Usually Means Working in the US</h2>

<p>Guides suggest a remote job can be done from anywhere. For work performed in the United States, the employer must complete <strong>Form I-9</strong> for every person it hires, citizen or not, to check identity and permission to work. Since <strong>1 August 2023</strong>, employers in good standing with E-Verify can examine your documents remotely over a live video call; other employers must see them in person.</p>

<p>If you live outside the US, for example in Pakistan, the rules are different:</p>

<ul>
    <li><strong>No I-9.</strong> USCIS does not require one for people who are not physically working in the United States.</li>
    <li><strong>Foreign-source pay.</strong> The IRS treats pay for services performed outside the US by a nonresident alien as foreign-source income, generally not subject to US withholding.</li>
    <li><strong>A W-8, not a W-9.</strong> The IRS tells US payers to have foreign persons complete the appropriate Form W-8, such as the <strong>W-8BEN</strong>.</li>
</ul>

<p>Check the location line on every posting: a job labelled remote can still require you to live in a particular state or in the US.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/remote-jobs-in-usa-home-office.jpg"
         alt="A woman in headphones working on a laptop at a home office desk, with the Statue of Liberty and New York skyline through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Employee or Contractor: Check Before You Accept</h2>

<p>Many remote roles are independent contracts rather than jobs, and the difference decides what you are owed.</p>

<ul>
    <li><strong>Employees are covered by the Fair Labor Standards Act at home too.</strong> The Department of Labor says its protections apply equally to teleworkers: at least the <strong>$7.25</strong> federal minimum wage, or a higher state rate, and overtime at time and a half after 40 hours. Breaks of <strong>20 minutes or less</strong> must be paid even when you work from home, while a meal break of 30 minutes or more in which you are completely relieved of duty need not be.</li>
    <li><strong>Contractors are not owed minimum wage or overtime</strong>, and pay self-employment tax of <strong>15.3%</strong> &mdash; 12.4% for Social Security and 2.9% for Medicare &mdash; once net earnings reach $400. For payments made after 31 December 2025, a client files Form 1099-NEC once it pays you <strong>$2,000</strong> in a year, up from $600.</li>
    <li><strong>The home office deduction.</strong> Employees cannot claim it: the IRS says the deduction for employee business expenses was eliminated for tax years beginning after 2017. The self-employed can use the simplified method of $5 per square foot, for up to 300 square feet.</li>
</ul>

<h2>Remote Job Scams: What the FTC Sees</h2>

<p>Guides skip the biggest risk. The Federal Trade Commission says reported losses to job and employment agency scams jumped from <strong>$90 million in 2020 to $501 million in 2024</strong>, while the number of reports tripled. In 2025, one in three people who lost money to a job or business opportunity scam said it started on social media.</p>

<p>The FTC's warning signs:</p>

<ul>
    <li><strong>A cheque to buy equipment.</strong> The "company" sends more than you need and asks you to send the rest back. No honest employer will send you a cheque to deposit and then ask for part of the money.</li>
    <li><strong>Paying to get the job.</strong> Starter kits, training or certifications you must buy. Honest employers, including the federal government, will never ask you to pay to get a job.</li>
    <li><strong>An unexpected text.</strong> Generic messages on text, WhatsApp or Telegram offering online work. In "task scams" you are paid small amounts, then asked to deposit money to unlock earnings; about 20,000 people reported them in the first half of 2024, against about 5,000 in all of 2023.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/remote-jobs-in-usa-workspace.jpg"
         alt="A woman in a striped sweater and headphones working on a laptop at home, with the Brooklyn Bridge and Manhattan skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Frequently Asked Questions</h2>

<h3>How many Americans work remotely?</h3>
<p>The BLS says 21.6% of people at work teleworked in August 2026, about 33.6 million, and about half of them worked from home for all their hours.</p>

<h3>Do remote jobs pay well in the USA?</h3>
<p>Many do. The BLS does not track remote pay separately, but its May 2025 medians run from $44,770 for customer service representatives to $135,980 for software developers.</p>

<h3>Can I get a US remote job from Pakistan?</h3>
<p>A US company can pay you for work done from Pakistan. No Form I-9 is needed for work done outside the US, the pay is foreign-source income, and the company will ask for a Form W-8 such as the W-8BEN. Check the location line, because a posting marked remote can still require you to live in the US.</p>

<h3>Do remote employees get minimum wage and overtime?</h3>
<p>Yes, if they are employees. The Fair Labor Standards Act applies to teleworkers as it does in an office, including overtime after 40 hours. Independent contractors are not covered.</p>

<h3>Can I deduct my home office if I work remotely?</h3>
<p>Not as an employee: the IRS eliminated that deduction for tax years beginning after 2017. The self-employed can deduct $5 per square foot for up to 300 square feet.</p>

<h3>How do I spot a remote job scam?</h3>
<p>Walk away from anyone who asks you to pay for the job, training or equipment, sends a cheque and asks for money back, or offers work in an unexpected text or WhatsApp message.</p>

<h3>Which remote jobs pay the most?</h3>
<p>Among common remote occupations, software developers ($135,980), information security analysts ($129,180) and data scientists ($120,230) had the highest BLS medians in May 2025.</p>

<h3>How do employers verify remote workers?</h3>
<p>With Form I-9. Since 1 August 2023, employers enrolled in E-Verify can examine documents over a live video call; others must see them in person.</p>

<h2>People Also Search For</h2>

<h3>Telework rate US 2026</h3>
<p>21.6% in August 2026, against 22.1% a year earlier.</p>

<h3>Remote customer service jobs USA pay</h3>
<p>A BLS median of $44,770 for customer service representatives in May 2025.</p>

<h3>Remote software developer salary</h3>
<p>A median of $135,980, with the lowest-paid tenth at $82,460.</p>

<h3>Remote data entry jobs USA</h3>
<p>A median of $41,340 for data entry keyers, and a common target for fake job offers.</p>

<h3>1099 remote jobs taxes</h3>
<p>Self-employment tax of 15.3%, and a Form 1099-NEC once a client pays you $2,000 in a year.</p>

<h3>Remote jobs for foreigners in USA</h3>
<p>Work done abroad needs no Form I-9, is paid as foreign-source income and uses a Form W-8.</p>

<h3>Task scams</h3>
<p>Fake online "tasks" that ask you to deposit money to unlock pay, reported by about 20,000 people in the first half of 2024.</p>

<h3>Remote work breaks law</h3>
<p>Breaks of 20 minutes or less must be paid even when you work from home.</p>

<h2>More Job Guides</h2>

<p>Looking at a specific remote role, or at the jobs behind these medians? These cover it:</p>

<ul>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the largest remote role, its shifts and the postings that are not really remote.</li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a> &mdash; real pay, and how the fake version works.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; entry-level remote work from Pakistan, and how payment from abroad works.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; administrative skills sold remotely to overseas clients.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; office support pay, and the part of the field still growing.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; a common remote entry point into IT.</li>
    <li><a href="/blog/python-developer-jobs-in-usa">Python Developer Jobs in USA</a> &mdash; which occupation a developer title really sits in, and what it pays.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; a well-paid field with many remote roles.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Pay data, telework statistics, tax thresholds and employment rules change often. Confirm the current position with the employer, the IRS, the Department of Labor or USCIS, or a qualified adviser, before applying.</p>
HTML;
    }
}
