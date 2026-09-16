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
 * "Recruiter Jobs in Canada" — agency, in-house, technical and executive
 * recruiters. The ATS resume writer guide owns the candidate side of
 * applicant tracking systems; this one owns the Job Bank wage data for NOC
 * 12101, the provincial licensing of recruiters, the 2026 job posting rules
 * recruiters now write to, and which HR designation applies where.
 *
 * Corrections to the draft:
 *
 * 1. It describes a tight labour market. Statistics Canada's Labour Force
 *    Survey for August 2026 put unemployment at 6.4 per cent, with employment
 *    down 42,000 in the month.
 *
 * 2. It says nothing about licensing. Since 1 July 2024 Ontario requires
 *    anyone who finds work for people for a fee to hold a recruiter licence
 *    under the Employment Standards Act, and British Columbia licenses foreign
 *    worker recruiters. In-house recruiters finding staff for their own
 *    employer are exempt in Ontario.
 *
 * 3. It recommends the CPHR designation as a general credential. Ontario's
 *    HRPA awards the CHRP, CHRL and CHRE instead, and Quebec's professional
 *    order awards the CRHA.
 *
 * 4. It lists writing job ads as a duty without the rules. From 1 January
 *    2026 Ontario postings by employers with 25 or more employees must show
 *    pay or a range no wider than $50,000, disclose AI screening and whether
 *    the vacancy exists, and cannot ask for Canadian experience. British
 *    Columbia has required pay ranges since 1 November 2023.
 *
 * 5. It says Toronto, Vancouver and Calgary pay more with no source. Job
 *    Bank's medians for Ontario ($34.87), Alberta ($34.62) and British
 *    Columbia ($33.52) are close to the national $33.33; the territories are
 *    the outliers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class RecruiterJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-recruiter-jobs.html';

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
        $title = 'Recruiter Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the recruiter median at $33.33 an hour, Ontario agency recruiters need a licence, Ontario job ads must show pay and AI use from 2026, the CPHR is not awarded in Ontario, and unemployment was 6.4 per cent in August 2026.',
                'content' => $content,
                'featured_image' => 'blogs/recruiter-jobs-in-canada.jpg',
                'tags' => 'recruiter jobs canada, recruiter salary canada, talent acquisition jobs canada, ontario recruiter licence, ontario job posting rules 2026, bc pay transparency job postings, cphr designation, chrp ontario, noc 12101, agency recruiter jobs toronto',
                'meta_title' => 'Recruiter Jobs in Canada 2026: Pay, Licences and New Rules',
                'meta_description' => 'Recruiter jobs in Canada: the Job Bank median of $33.33 an hour, Ontario recruiter licences, 2026 job posting rules on pay and AI, and where CPHR applies.',
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
            ['name' => 'Canadian Staffing Agencies, Search Firms & Corporate Talent Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-recruiter-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Recruiter — Agency, Corporate Talent Acquisition, Technical and Executive Search Roles, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time office or hybrid hours; agency roles often add commission targets',
                'language' => 'English',
                // Base pay and commission vary too much between agency and
                // corporate roles to quote one range.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Recruiter roles in Canadian staffing agencies, search firms and corporate talent acquisition teams. Agency and in-house positions.',
                'seo_keywords' => 'recruiter jobs canada, talent acquisition specialist jobs, agency recruiter jobs, technical recruiter jobs canada, recruiter jobs toronto',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Staffing agencies, executive search firms and corporate talent acquisition teams across Canada hire recruiters for agency, in-house, technical and campus hiring roles.</p>

<h3>What the work involves</h3>
<p>Writing and posting job ads, sourcing candidates on LinkedIn and job boards, screening and interviewing, coordinating hiring managers, negotiating offers and keeping candidate records in an applicant tracking system.</p>

<h3>Requirements</h3>
<ul>
    <li>Sales, customer service or HR experience; a degree in HR or business helps but is not always required</li>
    <li>For agency recruiters in Ontario, work for a firm holding a recruiter licence under the Employment Standards Act</li>
    <li>Knowledge of provincial job posting rules, including Ontario's 2026 pay, AI and Canadian experience rules</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured wages.</strong> Job Bank reports $22.00 low, $33.33 median and $52.73 high an hour for NOC 12101</li>
    <li><strong>Commission.</strong> Agency and executive search roles often add commission or bonuses to a base salary</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask how the base salary and commission are split,</strong> and whether commission is paid on placement or after a guarantee period.</p>

<p><strong>Note:</strong> pay, licensing and posting rules are set by employers and provincial governments &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Recruiters find, screen and hire people for employers, either inside one company's talent acquisition team or at an agency placing candidates with many clients. The job suits people from sales, customer service and HR backgrounds, and it has a clear path into talent acquisition and HR management. Before you apply, it helps to know what official data says about pay and the job market, the licences agency recruiters now need, the job posting rules that changed how recruiters write ads in 2026, and which HR designation counts in your province.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-recruiter-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128269; Browse Recruiter Jobs in Canada &rarr;
    </a>
</div>

<h2>The Job Market Is Not as Tight as Guides Say</h2>

<p>Guides describe companies competing for talent in a tight labour market. Recruiting is closely tied to hiring activity, so the current picture matters. Statistics Canada's <strong>Labour Force Survey for August 2026</strong> reported:</p>

<ul>
    <li>An <strong>unemployment rate of 6.4 per cent</strong>, unchanged from July.</li>
    <li>Employment <strong>down 42,000</strong> in the month.</li>
    <li>Youth unemployment of <strong>12.9 per cent</strong>.</li>
</ul>

<p>That is a market with more job seekers per opening, not fewer. Recruiters who handle high application volumes, screening and candidate communication are still needed, but agency recruiting on commission is more exposed when employers slow hiring.</p>

<h2>What a Recruiter Does</h2>

<ul>
    <li>Writing and posting job ads that meet provincial posting rules</li>
    <li>Sourcing candidates through LinkedIn, job boards, databases and referrals</li>
    <li>Screening resumes and running phone or video interviews</li>
    <li>Coordinating interviews between candidates and hiring managers</li>
    <li>Negotiating offers, salaries and start dates</li>
    <li>Keeping records in an applicant tracking system such as Workday or Bullhorn</li>
    <li>Advising hiring managers on pay, the market and interview practice</li>
</ul>

<p>Job Bank classifies the work under <strong>NOC 12101, human resources and recruitment officers</strong>, a TEER 2 occupation. Agency recruiters juggle several clients and roles; corporate recruiters hire for one organization.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/recruiter-jobs-in-canada-interview.jpg"
         alt="A smiling recruiter in a black blazer reviewing a resume during an interview with a candidate in a Toronto office with the CN Tower outside the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Recruiter Salary in Canada</h2>

<p>Guides quote CAD 45,000 to 55,000 for entry-level recruiters, 55,000 to 75,000 for experienced recruiters and 75,000 to 100,000 or more for senior recruiters. Job Bank's wages for NOC 12101, updated in November 2025, broadly support that spread. Annual figures below assume a 37.5-hour week:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Canada</td><td style="padding:10px;">$22.00 ($42,900)</td><td style="padding:10px;">$33.33 ($64,994)</td><td style="padding:10px;">$52.73 ($102,824)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$20.00 ($39,000)</td><td style="padding:10px;">$34.87 ($67,997)</td><td style="padding:10px;">$52.73 ($102,824)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">$24.04 ($46,878)</td><td style="padding:10px;">$34.62 ($67,509)</td><td style="padding:10px;">$55.38 ($107,991)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">$24.04 ($46,878)</td><td style="padding:10px;">$33.52 ($65,364)</td><td style="padding:10px;">$51.28 ($99,996)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">$24.80 ($48,360)</td><td style="padding:10px;">$31.25 ($60,938)</td><td style="padding:10px;">$52.88 ($103,116)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Manitoba</td><td style="padding:10px;">$21.63 ($42,179)</td><td style="padding:10px;">$27.00 ($52,650)</td><td style="padding:10px;">$46.63 ($90,929)</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Big cities are not far ahead.</strong> Guides say Toronto, Vancouver and Calgary pay more. Provincial medians for Ontario, Alberta and British Columbia are within about $1.50 an hour of the national median; the territories are the real outliers, with medians above $50 an hour.</li>
    <li><strong>Commission is not in these figures.</strong> Job Bank measures wages. Agency and executive search recruiters often earn commission on top of base pay, which can raise total earnings well above the table and drops when placements slow.</li>
</ul>

<h2>Licensing: Agency Recruiters Now Need It</h2>

<p>Guides do not mention licensing at all. Several provinces now regulate people who recruit for a fee:</p>

<ul>
    <li><strong>Ontario.</strong> Since <strong>1 July 2024</strong>, the Employment Standards Act requires a <strong>recruiter licence</strong> for anyone who, for a fee, finds or attempts to find employment in Ontario for prospective employees, and a separate licence for temporary help agencies. Employers cannot knowingly use an unlicensed recruiter. The application fee is generally <strong>$1,500</strong> from 1 January 2026, a licence generally lasts <strong>two years</strong>, and applicants generally provide <strong>$25,000</strong> in security, with some exemptions.</li>
    <li><strong>In-house recruiters are exempt in Ontario.</strong> An employee who recruits as part of their job for their own employer, and an employer finding its own staff, do not need the licence.</li>
    <li><strong>British Columbia.</strong> The Temporary Foreign Worker Protection Act requires a <strong>foreign worker recruiter licence</strong>, even for recruiters based outside the province, with a <strong>$20,000</strong> security and a licence of up to three years.</li>
</ul>

<p>If you join an agency, ask whether it holds the licence for the province and the type of recruiting it does. It affects whether clients can legally use your work.</p>

<h2>The 2026 Job Posting Rules Recruiters Must Follow</h2>

<p>Writing job ads is a core recruiter duty, and the rules changed. In <strong>Ontario, from 1 January 2026</strong>, publicly advertised job postings by employers with <strong>25 or more employees</strong>, including postings made on their behalf, must:</p>

<ul>
    <li>Include the <strong>expected pay or a pay range no wider than $50,000 a year</strong>, unless the job pays more than $200,000.</li>
    <li><strong>Disclose the use of artificial intelligence</strong> to screen, assess or select applicants.</li>
    <li>State <strong>whether the posting is for an existing vacancy</strong>.</li>
    <li><strong>Not require Canadian experience</strong>, in the posting or the application form.</li>
</ul>

<p>Employers must also tell interviewed applicants within <strong>45 days</strong> whether a hiring decision has been made, and keep copies of postings for three years. General recruitment campaigns and help wanted signs that do not advertise a specific position are excluded.</p>

<p>In <strong>British Columbia</strong>, the Pay Transparency Act has required a <strong>wage or salary range on publicly advertised job postings since 1 November 2023</strong>. A recruiter who can write a compliant posting for both provinces is useful from day one.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/recruiter-jobs-in-canada-hiring.jpg"
         alt="A recruiter smiling and gesturing while talking with a candidate across a desk, with the Toronto waterfront, the CN Tower and a Canadian flag behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which HR Designation Counts Where</h2>

<p>Guides recommend the <strong>CPHR</strong> (Chartered Professional in Human Resources) as a credential that boosts credibility. It does, but not everywhere:</p>

<ul>
    <li><strong>Most provinces and the territories.</strong> The CPHR is awarded through CPHR Canada's member associations, with the National Knowledge Exam and an experience requirement.</li>
    <li><strong>Ontario.</strong> The Human Resources Professional Association awards its own three designations, <strong>CHRP, CHRL and CHRE</strong>, with the CHRP as the entry level. The CPHR is not Ontario's designation.</li>
    <li><strong>Quebec.</strong> HR is a professional order, and the designation is the <strong>CRHA</strong>.</li>
</ul>

<p>None of these is required to work as a recruiter. They matter more for corporate talent acquisition and HR roles than for agency sales-driven recruiting.</p>

<h2>Types of Recruiter Roles</h2>

<ul>
    <li><strong>Agency or staffing recruiter.</strong> Places candidates with client companies, usually with base pay plus commission.</li>
    <li><strong>Corporate or in-house recruiter.</strong> Hires for one employer, usually salaried, and exempt from Ontario's recruiter licence.</li>
    <li><strong>Executive recruiter.</strong> Fills senior roles on longer retained or contingent searches.</li>
    <li><strong>Technical recruiter.</strong> Hires for IT, engineering and specialist roles.</li>
    <li><strong>Campus recruiter.</strong> Runs graduate and co-op hiring with universities and colleges.</li>
</ul>

<h2>Where to Find Recruiter Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Indeed Canada and Job Bank list agency and corporate roles; search "talent acquisition" as well as "recruiter".</li>
    <li><strong>Staffing agencies.</strong> Many hire internal recruiters and train people from sales backgrounds.</li>
    <li><strong>Company career pages.</strong> Large employers post talent acquisition roles directly.</li>
    <li><strong>LinkedIn.</strong> Recruiting jobs are often shared by recruiters themselves.</li>
    <li><strong>HR associations.</strong> CPHR associations, HRPA in Ontario and the Ordre des CRHA in Quebec share postings.</li>
</ol>

<h2>Tips for Landing a Recruiter Job</h2>

<ul>
    <li><strong>Show numbers.</strong> Time-to-fill, hires made or sales targets met.</li>
    <li><strong>Know the posting rules.</strong> Explaining Ontario's pay range and AI disclosure rules in an interview sets you apart.</li>
    <li><strong>Learn the tools.</strong> LinkedIn Recruiter, an ATS such as Workday or Bullhorn, and Boolean search.</li>
    <li><strong>Ask about commission.</strong> For agency roles, ask for the base, the commission rate and when commission is paid.</li>
</ul>

<h2>Career Progression</h2>

<p>Recruiters move into senior recruiter or talent acquisition specialist roles, then team lead, talent acquisition manager and director, or head of people. Many move sideways into HR business partner and people operations roles, where an HR designation carries more weight. Experienced agency recruiters sometimes open their own search firm, which in Ontario means applying for a recruiter licence.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do recruiters make in Canada?</h3>
<p>Job Bank reports a national median of $33.33 an hour for human resources and recruitment officers, about $64,994 a year at 37.5 hours, with a high of $52.73 an hour. Agency recruiters can add commission.</p>

<h3>Do recruiters need a licence in Canada?</h3>
<p>In Ontario, anyone recruiting for a fee has needed a recruiter licence since 1 July 2024, but in-house recruiters hiring for their own employer are exempt. British Columbia licenses foreign worker recruiters.</p>

<h3>What must a job posting include in Ontario in 2026?</h3>
<p>For employers with 25 or more employees: the pay or a range no wider than $50,000, whether AI is used in screening, whether the vacancy exists, and no Canadian experience requirement.</p>

<h3>Does British Columbia require salary ranges in job ads?</h3>
<p>Yes. The Pay Transparency Act has required wage or salary ranges on publicly advertised job postings since 1 November 2023.</p>

<h3>Is the CPHR recognized in Ontario?</h3>
<p>Ontario uses the HRPA's CHRP, CHRL and CHRE designations instead. Quebec uses the CRHA.</p>

<h3>Do I need a degree to become a recruiter?</h3>
<p>No. A degree in HR or business helps, but many recruiters come from sales and customer service backgrounds.</p>

<h3>What NOC code is a recruiter in Canada?</h3>
<p>NOC 12101, human resources and recruitment officers, a TEER 2 occupation.</p>

<h3>Is it a good time to become a recruiter in Canada?</h3>
<p>Hiring is slower than guides suggest: unemployment was 6.4 per cent in August 2026 and employment fell by 42,000 that month, which hits commission-based agency roles hardest.</p>

<h2>People Also Search For</h2>

<h3>Talent acquisition specialist jobs Canada</h3>
<p>The corporate title for in-house recruiters.</p>

<h3>Recruiter salary Toronto</h3>
<p>Ontario's Job Bank median is $34.87 an hour, before commission.</p>

<h3>Ontario recruiter licence</h3>
<p>Required since 1 July 2024 for recruiting for a fee, with a $1,500 application fee.</p>

<h3>Ontario pay transparency job postings</h3>
<p>Pay or a range no wider than $50,000 on postings from 1 January 2026.</p>

<h3>CHRP vs CPHR</h3>
<p>The CHRP is Ontario's entry designation; the CPHR is used in most other provinces.</p>

<h3>Agency recruiter commission</h3>
<p>Paid on top of base salary, usually per placement.</p>

<h3>Technical recruiter jobs Canada</h3>
<p>IT and engineering hiring, often at the higher end of the wage range.</p>

<h3>Entry level recruiter jobs</h3>
<p>Often filled from sales and customer service backgrounds.</p>

<h2>More Job Guides</h2>

<p>Comparing hiring and Canadian office jobs? These cover them:</p>

<ul>
    <li><a href="/blog/ats-resume-writer-jobs-in-canada">ATS Resume Writer Jobs in Canada</a> &mdash; the candidate side of the applicant tracking systems recruiters use.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the background many recruiters start from.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how the LMIA works for the employers recruiters hire for.</li>
    <li><a href="/blog/qa-tester-jobs-in-canada">QA Tester Jobs in Canada</a> &mdash; a technical role technical recruiters hire for.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; office support work and its pay across the border.</li>
    <li><a href="/blog/occupational-therapist-jobs-in-canada">Occupational Therapist Jobs in Canada</a> &mdash; the allied-health route, its provincial registration and pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, labour market figures, licensing rules, job posting requirements and HR designations change and differ by province. Confirm the current position with Job Bank, Statistics Canada, your provincial employment standards office and the relevant HR association before applying.</p>
HTML;
    }
}
