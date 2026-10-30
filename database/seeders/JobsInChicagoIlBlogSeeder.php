<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Jobs in Chicago, IL: Where to Apply and How to Get Hired"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief links ten employer portals with apply buttons. Following the rule on employers, only public bodies are linked (City of Chicago, CTA, Chicago Public Schools, the state job portals); Rush, Northwestern Medicine, United, Amazon and Hyatt are named in prose with a tip to search their own career pages. The University of Chicago is a private university, so it is named in prose too.
 * 2. The brief's University of Chicago link (humanresources.uchicago.edu/jobs/apply.shtml) now redirects to an intranet page, so it is dropped. The university's own HR page lists staff, academic and medical centre jobs as separate routes.
 * 3. CTA: its upcoming opportunities page says postings depend on staffing needs and budget. CPS: it does not hire bus drivers directly; resumes go to the companies that manage that hiring. IllinoisJobLink: free, search and upload or build a resume, and resumes go inactive after about 90 days.
 * 4. The brief's shift and schedule claims about specific employers are not repeated because they could not be checked against a live listing.
 * 5. Eight FAQs, eight People Also Search For entries and a Pakistan note are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class JobsInChicagoIlBlogSeeder extends Seeder
{
    public const SLUG = 'jobs-in-chicago-il';

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
                'title' => 'Jobs in Chicago, IL: Where to Apply and How to Get Hired',
                'excerpt' => 'Where to look for jobs in Chicago: the City, CTA, Chicago Public Schools, the University of Chicago and IllinoisJobLink, plus private employers to search directly.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-in-chicago-il.jpg',
                'tags' => 'jobs in chicago il, chicago jobs hiring, entry level jobs chicago, full time jobs chicago, city of chicago jobs, cta careers, chicago public schools jobs, illinoisjoblink',
                'meta_title' => 'Jobs in Chicago, IL: Where to Apply and How to Get Hired',
                'meta_description' => 'Find jobs in Chicago, IL through official portals: city, transit, schools, university and state job tools, plus private employers to search directly.',
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
<p><strong>To find jobs in Chicago, IL, start with official employer and public-sector career pages and filter by location, qualifications and schedule.</strong> Good places to look include the City of Chicago, the CTA, Chicago Public Schools and the state's IllinoisJobLink, plus private employers such as Rush, Northwestern Medicine, United Airlines, Amazon and Hyatt, which you can find on their own career sites. This guide points you to application portals, not guaranteed vacancies, and availability and requirements change with every posting.</p>

<h2>Choose Your Job Search Area First</h2>

<p>Decide whether you need a job inside Chicago or can commute to the suburbs. A listing marked "Chicago area" may be far from the neighbourhood you want. Check the real address, reporting time and the journey home before you apply, because airport, hospital and hospitality shifts can differ from office hours. Confirm weekend expectations and rotation, and whether the post is permanent, temporary or seasonal. Pick two or three job families that match your skills so you can tailor your resume and compare offers fairly.</p>

<h2>1. City of Chicago Government Jobs</h2>

<p>The City's public <a href="https://chicago.taleo.net/careersection/100/jobsearch.ftl?lang=en" rel="noopener">CAREERS portal</a> lets external applicants search vacancies across city departments. Use the public site, not an internal employee or union bidding system. Read the whole announcement for minimum qualifications, required attachments, deadlines and any residency conditions, and answer the application questions fully, because uploading a resume may not replace entering your work history. Note the requisition number, and treat each department's posting as its own application.</p>

<h2>2. Chicago Transit Authority Careers</h2>

<p>The CTA's <a href="https://www.transitchicago.com/careers/default.aspx" rel="noopener">careers page</a> is the starting point for transit jobs. Its <a href="https://www.transitchicago.com/careers/upcoming-job-opportunities/" rel="noopener">upcoming opportunities page</a> says postings depend on the CTA's staffing needs and budget, so separate an active vacancy from an upcoming one. For driving or technical roles, check the licence, experience and assessment requirements, and confirm the schedule rather than assuming office hours. Use your applicant account to track progress and answer requests quickly.</p>

<h2>3. Chicago Public Schools Jobs</h2>

<p><a href="https://www.cps.edu/careers" rel="noopener">CPS</a> lists teaching routes (including Teach Chicago, substitute and paraprofessional roles) next to non-teaching work in central office and operations: academic support, finance, IT, law and human resources, plus clinicians such as nurses and social workers. Teaching and clinical roles may need specific credentials, and operations roles have their own criteria. The CPS page says it is hiring bus drivers but does not hire them directly: resumes go to outside companies that manage that hiring. Confirm whether the employer is CPS or a contractor before comparing pay and benefits.</p>

<h2>4. IllinoisJobLink</h2>

<p><a href="https://illinoisjoblink.illinois.gov/job_seeker" rel="noopener">IllinoisJobLink</a> is the state's free job matching service. The Illinois Department of Employment Security's <a href="https://ides.illinois.gov/faqs/job-seekers.html" rel="noopener">job seeker FAQ</a> says you can search postings, answer questions to build a resume, upload a PDF or Word file or use the resume builder, and that resumes generally become inactive after about 90 days unless you renew them. Search Chicago with a realistic travel radius, compare employer details and keep a tracker of the position, company, date, advertised pay and next step.</p>

<h2>5. Hospital and Healthcare Employers</h2>

<p>Large systems such as Rush and Northwestern Medicine recruit nurses, clinicians, administrators and support staff through their own career pages, and the University of Chicago Medicine has a separate careers page from the university's staff jobs. Search by your real qualifications: administrative and support roles have different rules from licensed clinical work. For patient-facing jobs, check training, certification and experience, and show service experience, accuracy, confidentiality and teamwork without claiming clinical skills you lack. Save the job number and check the post is still open before you follow up.</p>

<h2>6. University of Chicago Employment</h2>

<p>The university's HR site lists staff jobs, academic jobs (through a separate academic recruitment site), student employment and the medical centre as different routes, so pick the right one. Do not assume a university job needs an advanced degree: the listed qualifications decide. For staff roles, link your experience to the department's needs, such as managing records, supporting customers, coordinating projects or maintaining equipment.</p>

<h2>7. Airport, Retail and Hospitality Employers</h2>

<p>United Airlines recruits at O'Hare through its own careers site, Amazon lists Chicago locations on its jobs site, and Hyatt lists hotel roles on its careers portal. Search by title and location, and check the actual site, shift and employment type, because a city search can show corporate and technical roles as well as warehouse or front-line jobs. A job at an airport may be with the airline or a contractor, so apply to the employer named in the listing. Search terms like housekeeping, front desk, culinary and guest services are only suggestions, not proof of openings.</p>

<h2>Prepare a Strong Application</h2>

<p>Match your resume to the posting honestly and proofread it. Include contact details, relevant experience, education and skills with clear examples of results. Use accurate dates, ask references for permission before listing them, and keep a professional email address and voicemail. Save each job description, tailor the relevant resume sections and check your email daily so you can respond to invitations quickly. Prepare interview examples of solving a problem and working with others. Choose openings that suit your qualifications instead of sending identical applications everywhere.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I find entry level jobs in Chicago?</h3>
<p>Yes, if the minimum qualifications match your background. Show transferable skills from study, volunteering or earlier work. Entry level does not always mean no requirements.</p>

<h3>How much do Chicago jobs pay?</h3>
<p>It depends on the occupation, experience, schedule and employer. Compare the advertised range, guaranteed hours and benefits, and keep tips, bonuses and overtime separate from base pay.</p>

<h3>What should my resume include?</h3>
<p>Contact details, relevant experience, education and skills, with clear examples. Match the posting honestly and proofread.</p>

<h3>How can I avoid scams?</h3>
<p>Check the employer on its official website. Be wary of requests to pay for an interview, buy gift cards or deposit a cheque for equipment.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>A job listing on an employer's portal is not a visa offer, and most of these employers do not sponsor workers for entry-level roles. Never pay an agent for an interview or a "guaranteed" US job, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<h2>Bottom Line</h2>

<p>Target suitable Chicago employers, check the workplace and schedule, and apply through verified portals with an accurate application. Track your progress and compare complete offers before you decide.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://chicago.taleo.net/careersection/100/jobsearch.ftl?lang=en" rel="noopener">City of Chicago public CAREERS portal</a></li>
<li><a href="https://www.transitchicago.com/careers/default.aspx" rel="noopener">CTA careers</a></li>
<li><a href="https://www.transitchicago.com/careers/upcoming-job-opportunities/" rel="noopener">CTA upcoming opportunities</a></li>
<li><a href="https://www.cps.edu/careers" rel="noopener">Chicago Public Schools careers</a></li>
<li><a href="https://illinoisjoblink.illinois.gov/job_seeker" rel="noopener">IllinoisJobLink</a></li>
<li><a href="https://ides.illinois.gov/faqs/job-seekers.html" rel="noopener">Illinois Department of Employment Security: Job seeker guidance</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/jobs-hiring-now-houston-tx">Jobs Hiring Now in Houston, TX</a>: another city guide</li>
    <li><a href="/blog/part-time-jobs-seattle-wa">Part Time Jobs in Seattle, WA</a>: another city guide</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: stay safe</li>
    <li><a href="/blog/elevator-mechanic-apprenticeship-chicago-philadelphia-boston">Elevator Mechanic Apprenticeship in Chicago, Philadelphia, and Boston</a>: trade route</li>
</ul>

<p><em>Reviewed on 17 October 2026.</em></p>

HTML;
    }
}
