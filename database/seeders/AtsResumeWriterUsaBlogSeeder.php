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
 * "ATS Resume Writer Jobs in USA" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup.
 *
 * Two corrections to the draft. It listed NCOPE beside CPRW as a resume-writing
 * credential; NCOPE is the NRWA's online-profile certification and covers
 * LinkedIn, while the NRWA's resume credential is the NCRW. And the whole
 * category is sold on the claim that applicant tracking systems reject three
 * quarters of resumes before a human sees them, which traces back to a 2012
 * sales pitch by a company that folded in 2013 and which recruiters
 * overwhelmingly contradict. A writer who sells on that loses the client the
 * first time it is checked, so the guide gives them the accurate version.
 *
 * The featured image is a placeholder until the dedicated set arrives;
 * replacing the file under storage/app/public/blogs swaps it without touching
 * this seeder.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AtsResumeWriterUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-resume-writer-jobs.html';

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
        $title = 'ATS Resume Writer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What an ATS resume writer is actually paid to do, which certifications cover resumes rather than LinkedIn profiles, why the statistic the whole industry quotes is false, and how per-resume pay structures work.',
                'content' => $content,
                'featured_image' => 'blogs/ats-resume-writer-jobs-in-usa.jpg',
                'tags' => 'ats resume writer jobs, resume writer jobs usa, remote resume writer jobs, cprw certification, ncrw certification, resume writing jobs from home, ats resume writing, freelance resume writer',
                'meta_title' => 'ATS Resume Writer Jobs in USA',
                'meta_description' => 'ATS resume writer jobs in the USA: what the work involves, which certifications count, how writers are paid, and the ATS statistic you should never quote.',
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
            ['name' => 'Career Services & Resume Firms (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-resume-writing-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'writing-content'],
            ['name' => 'Writing & Content']
        );

        Job::updateOrCreate(
            [
                'position' => 'ATS Resume Writer — Career Services and Staffing Firms',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Flexible; staff roles follow US business hours, contract work follows turnaround deadlines',
                'language' => 'English',
                // Staff roles pay a base plus per-resume bonuses and contract
                // work is priced per project, so no single range would be true.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Remote and on-site resume writing roles with US career coaching firms, staffing agencies and resume services. Apply on the employer portal.',
                'seo_keywords' => 'ats resume writer jobs, resume writer jobs usa, remote resume writer, cprw, ncrw, freelance resume writing jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Career coaching firms, HR consultancies, staffing agencies and online resume services across the United States hire writers to rewrite and format resumes so they parse cleanly in applicant tracking systems and read well to a recruiter. The work is fully document-based, which is why most of these roles are remote or offer a remote option.</p>

<h3>What the work involves</h3>
<p>Interviewing or reviewing a client's background, reading the target job description, and producing a resume that a system can parse and a person wants to read: standard section headings, no text boxes or tables carrying essential content, no graphics doing the work of words, and the terminology from the posting used honestly rather than stuffed. Many writers also advise clients on tailoring per application instead of sending one static document.</p>

<h3>Requirements</h3>
<ul>
    <li>Strong written English and the discipline to write in someone else's voice</li>
    <li>Familiarity with how the major systems parse a document &mdash; Workday, Taleo, iCIMS and Greenhouse are the ones named most often</li>
    <li>A portfolio of before-and-after samples; practice pieces are accepted when starting out</li>
    <li>A recognised certification is an advantage &mdash; the CPRW, or the NRWA's NCRW &mdash; though many firms hire on portfolio alone</li>
    <li>An HR, recruiting or career coaching background helps but is not usually required</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Staff writer roles</strong> at established firms commonly pay a base salary plus a per-resume bonus</li>
    <li><strong>Freelance and contract work</strong> is priced per project, with the rate varying by the client's industry and seniority</li>
    <li>Before accepting piecework, confirm the <strong>turnaround time and the revision policy</strong> &mdash; an unlimited-revisions clause turns a fixed fee into an open-ended commitment</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Be careful with the industry's favourite statistic.</strong> The claim that applicant tracking systems automatically reject around three quarters of resumes is not supported by any published research, and recruiters say their systems do not auto-reject on formatting. What actually happens is volume, recruiter keyword searches inside the system, and genuine parsing errors. A writer who understands that difference gives better advice than one repeating the sales line.</p>

<p><strong>Note:</strong> pay, turnaround expectations and certification requirements are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Resume writing is one of the few writing jobs with steady, year-round demand in the United States, and almost all of it is remote &mdash; the work is a document, a job description and a conversation. It is also an industry built on a claim that is not true, and knowing which parts of the standard pitch hold up is most of what separates a credible writer from a template seller. This guide covers what the job is, what it pays, which credentials mean what, and the statistic you should never repeat to a client.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-resume-writer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        📝 Browse Resume Writer Jobs in the USA &rarr;
    </a>
</div>

<h2>What an ATS Resume Writer Actually Does</h2>

<p>The job is to produce a document that survives being read twice: once by software that extracts structured data from it, and once by a person deciding in a few seconds whether to keep reading. Those two readers want different things, and reconciling them is the whole skill.</p>

<p>In practice that means standard section headings the parser recognises, no essential content trapped in a text box, table or image, a font and layout that do not confuse extraction, and the vocabulary of the target job description used honestly &mdash; the terms the employer used, where they are true of the candidate. The other half of the job is editorial: cutting duties down to achievements, putting numbers on results, and getting the top third of page one to say the thing the candidate is applying to do.</p>

<h2>The Statistic the Industry Sells On — and Why It Is Wrong</h2>

<p>You will meet this claim within a day of entering the field: that applicant tracking systems automatically reject about 75 per cent of resumes before a human sees them. It is worth knowing that <strong>no published research supports it</strong>. It traces to a 2012 sales pitch by a resume-optimisation company that was out of business by 2013, and no methodology was ever released. Surveys of recruiters find the overwhelming majority saying their systems do not auto-reject resumes on formatting, design or content at all.</p>

<p>What actually happens is less dramatic and more useful to know. An ATS is a database: it stores, sorts and searches. Recruiters run keyword searches inside it and read what surfaces. Applications disappear for three ordinary reasons &mdash; sheer volume, because a popular posting can draw hundreds or thousands of applicants in days; keyword mismatch, because the resume never surfaces in the search the recruiter actually ran; and real parsing failures, where a layout hides content from extraction.</p>

<p>That correction is not bad for business, it is the business. Every one of those three is something a good writer fixes. A candidate who has been told a robot deleted their resume learns nothing; a candidate who is shown that their resume never matched the search terms the recruiter used can act on it.</p>

<h2>Resume Writer Jobs in the USA: Who Hires</h2>

<p>Four kinds of employer, and they want different things:</p>

<ul>
    <li><strong>Career coaching firms and resume services</strong> &mdash; the largest employer of staff writers, usually volume-based with defined turnaround times.</li>
    <li><strong>Staffing and recruiting agencies</strong> &mdash; writers who reformat candidate resumes to a house standard before submission to clients.</li>
    <li><strong>HR consultancies and outplacement providers</strong> &mdash; work attached to redundancy programmes, often the best paid and the most seasonal.</li>
    <li><strong>Direct clients and freelance platforms</strong> &mdash; the most accessible entry point, and the one where your own portfolio does the selling.</li>
</ul>

<h2>How Resume Writers Get Paid</h2>

<p>Staff writer roles at established firms commonly pay a base salary plus a per-resume bonus. Freelance and contract writers are usually paid per project, with rates set by the client's industry and seniority level &mdash; an executive resume is priced differently from a first-job one, and reasonably so.</p>

<p>Before you accept piecework, settle two things that decide what the fee is actually worth: <strong>the turnaround time</strong> and <strong>the revision policy</strong>. An unlimited-revisions clause attached to a flat fee turns a fixed price into an open-ended commitment, and it is the single most common way writers in this field end up underpaid. Two rounds of revisions within a stated window is a normal, professional term to ask for.</p>

<h2>Certifications: Which One Covers What</h2>

<p>This is worth getting right, because the credentials are easy to confuse and one of them is not about resumes at all.</p>

<ul>
    <li><strong>CPRW</strong> &mdash; Certified Professional Résumé Writer. The most widely recognised resume-writing credential and the one employers name most often.</li>
    <li><strong>NCRW</strong> &mdash; Nationally Certified Résumé Writer, from the National Résumé Writers' Association. The other established resume credential, with a demanding examination.</li>
    <li><strong>NCOPE</strong> &mdash; Nationally Certified Online Profile Expert, also from the NRWA. This one is <strong>not a resume certification</strong>: it covers LinkedIn and online career profiles. Useful, frequently sold alongside resume work, and regularly listed in the wrong place in job guides.</li>
</ul>

<p>None of them is mandatory. Plenty of firms hire on portfolio alone, and a strong set of before-and-after samples will get you further at the start than a certificate with nothing behind it. If you are choosing one, pick the credential that matches the work you intend to sell.</p>

<h2>Remote Resume Writer Jobs</h2>

<p>Because the work is a document, remote is the default rather than a concession. Employers often prefer it, since it lets them hire across US time zones and, for contract work, internationally for US-focused clients. Roles range from salaried positions at resume firms to flexible contract work you schedule yourself.</p>

<p>For writers outside the United States, the practical questions are timezone overlap for client calls and how you will be paid. Confirm both before accepting, in the same conversation as the rate.</p>

<h2>Skills and Requirements</h2>

<ul>
    <li><strong>Writing in someone else's voice.</strong> The resume has to sound like the candidate, not like you.</li>
    <li><strong>Interviewing.</strong> Most of the material worth putting on a resume has to be drawn out of the client, because they do not think of it as an achievement.</li>
    <li><strong>Formatting discipline.</strong> Knowing what parses cleanly and what does not, and being able to explain why to a client who wants a designed template.</li>
    <li><strong>Familiarity with the major systems</strong> &mdash; Workday, Taleo, iCIMS, Greenhouse &mdash; enough to speak about parsing and search behaviour specifically.</li>
    <li><strong>Judgement about keywords.</strong> Using the employer's terminology where it is true, and refusing to stuff terms that are not.</li>
</ul>

<h2>How to Apply for ATS Resume Writer Jobs</h2>

<ol>
    <li><strong>Build two or three before-and-after samples.</strong> Practice pieces are fine; what is being assessed is whether you can show the improvement and explain it.</li>
    <li><strong>Put any certification or HR and recruiting background near the top</strong> of your own resume &mdash; which will be read as a work sample whether or not anyone says so.</li>
    <li><strong>Learn the major ATS platforms well enough to be specific</strong> in an interview about parsing and how recruiters search inside them.</li>
    <li><strong>Apply through job boards and firms' own careers pages</strong> rather than unsolicited offers with no verifiable client history.</li>
    <li><strong>For contract work, agree the pay structure, turnaround and revision limit in writing</strong> before the first assignment.</li>
    <li><strong>Do not build your pitch on the 75 per cent claim.</strong> Explain volume, keyword search and parsing instead; it is accurate and it holds up when a client checks.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a certification to become an ATS resume writer?</h3>
<p>Not always. Some employers require the CPRW or the NRWA's NCRW, while many hire on a strong portfolio and demonstrated understanding of formatting and parsing. A certification helps most when you have no client history to show yet.</p>

<h3>Do applicant tracking systems really reject 75 per cent of resumes?</h3>
<p>No. That figure has no published research behind it and traces to a 2012 sales pitch by a company that closed the following year. Surveys of recruiters find the large majority saying their systems do not auto-reject on formatting or content. Volume, keyword mismatch and genuine parsing errors explain what people experience.</p>

<h3>What is the difference between CPRW, NCRW and NCOPE?</h3>
<p>CPRW and NCRW are resume-writing credentials, the NCRW being the National Résumé Writers' Association's examination-based designation. NCOPE, also from the NRWA, covers LinkedIn and online career profiles rather than resumes, and is frequently listed as a resume certification by mistake.</p>

<h3>Are ATS resume writer jobs mostly remote?</h3>
<p>Yes. The work is entirely document-based, so most roles are remote or offer a remote option, and employers often prefer it because it widens the hiring pool across time zones.</p>

<h3>How are resume writers paid?</h3>
<p>Staff roles at established firms typically pay a base salary plus a per-resume bonus. Freelance and contract writers are paid per project, with rates depending on the client's industry and seniority level.</p>

<h3>What should I check before accepting per-resume work?</h3>
<p>The turnaround time and the revision policy. An unlimited-revisions clause attached to a flat fee makes the effective rate impossible to predict; two rounds within a stated window is a normal term to ask for.</p>

<h3>Can I do this as freelance work instead of a job?</h3>
<p>Yes, and many writers do. Freelance and contract work through platforms, agencies or your own client base is the most common entry point, since clients weigh sample quality more heavily than formal credentials.</p>

<h3>Which ATS platforms should I know?</h3>
<p>Workday, Taleo, iCIMS and Greenhouse come up most often. You do not need administrator-level knowledge; you need to be able to speak specifically about how documents are parsed and how recruiters search inside them.</p>

<h2>People Also Search For</h2>

<h3>ATS resume writer jobs</h3>
<p>Hired by career coaching firms, staffing agencies and online resume services, almost always with a remote option, on a portfolio or certification basis.</p>

<h3>Resume writer jobs in USA</h3>
<p>Staff roles at established firms pay a base plus per-resume bonuses; contract and freelance work is priced per project by industry and seniority.</p>

<h3>Remote resume writer jobs</h3>
<p>The default arrangement in this field, since the work is document-based and employers prefer hiring across time zones.</p>

<h3>ATS resume writing skills and requirements</h3>
<p>Strong written English, formatting discipline, interviewing skill, and enough familiarity with Workday, Taleo, iCIMS and Greenhouse to be specific about parsing.</p>

<h3>CPRW certification</h3>
<p>The most widely recognised resume-writing credential, and the one job advertisements name most often.</p>

<h3>NCRW certification</h3>
<p>The National Résumé Writers' Association's examination-based resume credential, and the more demanding of the two established designations.</p>

<h3>Freelance resume writer jobs</h3>
<p>The most accessible entry point. Agree the pay structure, turnaround time and revision limit before the first assignment.</p>

<h3>Do ATS systems reject resumes automatically</h3>
<p>Recruiters overwhelmingly say they do not. An ATS stores, sorts and searches; people decide. Volume and keyword mismatch account for most unanswered applications.</p>

<h2>More Job Guides</h2>

<p>Looking at the rest of the remote writing and support market? These cover it:</p>

<ul>
    <li><a href="/blog/ai-content-writer-jobs-in-usa">AI Content Writer Jobs in USA</a> &mdash; the other fast-growing remote writing category, and what employers actually screen for.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; how international remote work and payment routes actually operate.</li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a> &mdash; the largest entry-level remote category, and the scam patterns that come with it.</li>
    <li><a href="/blog/digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan">Digital Marketing Expert (SEO) &mdash; Remote, Pakistan</a> &mdash; an adjacent remote content role with a defined skill set.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or careers advice. Certification requirements, pay structures and employer terms change &mdash; confirm the current details with the certifying body and on the employer's own advertisement before applying.</p>
HTML;
    }
}
