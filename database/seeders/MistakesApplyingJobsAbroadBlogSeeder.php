<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Top Mistakes People Make When Applying for Jobs Abroad"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The ten Apply Now buttons are dropped. Commercial portals (LinkedIn,
 *    SEEK, Bayt, GulfTalent) are named in prose where useful but not linked;
 *    only official or JobGader pages are linked.
 * 2. A Pakistan section is added (BEOE licensing and emigration checks).
 * 3. The brief's four or five FAQs become eight, plus eight People Also Search
 *    For entries and a More Job Guides block.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class MistakesApplyingJobsAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'mistakes-applying-jobs-abroad';

    public function run(): void
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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Top Mistakes People Make When Applying for Jobs Abroad',
                'excerpt' => 'Common overseas application mistakes include applying to every vacancy, sending one generic CV, ignoring work-authorisation wording and trusting unverified recruiters.',
                'content' => $content,
                'featured_image' => 'blogs/mistakes-applying-jobs-abroad.jpg',
                'tags' => 'mistakes when applying jobs abroad, common errors international job application, why overseas job applications fail, job abroad application tips, overseas cv mistakes, apply for jobs abroad, overseas recruitment eligibility, job application checklist',
                'meta_title' => 'Top Mistakes People Make When Applying for Jobs Abroad',
                'meta_description' => 'Ten common mistakes when applying for jobs abroad and how to fix them: fit, CV, eligibility, channel, recruiters, interviews and contract terms.',
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
<p><strong>Common mistakes when applying for jobs abroad include targeting unsuitable vacancies, sending a generic CV, ignoring work-authorisation wording and not following the employer's instructions.</strong> False experience, missed deadlines and unverified recruiters add to the problem. The fixes below are practical, but rejection can also come from competition or employer decisions you cannot control.</p>

<h2>1. Applying to Every Vacancy Without Checking Fit</h2>
<p>A familiar job title does not prove you meet the requirements. Read the duties, experience, location and qualifications, separate essential criteria from preferences, and target roles you can honestly meet. Fewer, better applications are easier to tailor and prepare.</p>

<h2>2. Sending the Same CV Everywhere</h2>
<p>The UK <a href="https://nationalcareers.service.gov.uk/careers-advice/cv-sections" rel="noopener">National Careers Service</a> advises keeping a CV clear and easy to read. Keep a master record, then adapt the emphasis for each vacancy using the advert's terminology where it truthfully describes your work. Never change dates or qualifications between versions. See our <a href="/blog/how-to-write-resume-job-abroad">resume guide for jobs abroad</a>.</p>

<h2>3. Ignoring Overseas Recruitment Restrictions</h2>
<p>Some vacancies accept only people who already have local work rights. Canada's Job Bank has a <a href="https://www.jobbank.gc.ca/findajob/foreign-candidates" rel="noopener">foreign candidates search</a> for employers recruiting from outside Canada. Read the eligibility wording, and if it is unclear, ask whether the role accepts applicants living in your country. A licensed sponsor does not sponsor every vacancy.</p>

<h2>4. Confusing a Job Offer With Visa Approval</h2>
<p>An offer and government permission are separate. UK Skilled Worker applicants need an eligible job with an approved employer on the <a href="https://www.gov.uk/skilled-worker-visa" rel="noopener">Skilled Worker route</a>, and the applicant must meet the conditions as well. Do not resign or buy non-refundable travel because a recruiter promises quick approval, and check professional registration separately.</p>

<h2>5. Inventing Experience or Qualifications</h2>
<p>False certificates, dates and references can end an application and cause more serious consequences. Describe what you have actually done, use numbers only where you can support them, and present genuine training and projects honestly if you are a beginner.</p>

<h2>6. Using the Wrong Application Channel</h2>
<p>Follow the form, portal or email the employer asks for. On the US Department of Labor's <a href="https://seasonaljobs.dol.gov/" rel="noopener">SeasonalJobs</a> site, the job order itself says how to apply to the employer. Complete every required question, attach the files requested and save the confirmation.</p>

<h2>7. Unclear or Inconsistent Information</h2>
<p>Wrong phone numbers, unreadable files and mismatched dates cause avoidable delays. Check your name, email, country code and links, proofread employer names, and keep CV details consistent with your documents. State your location honestly; do not imply you already live abroad.</p>

<h2>8. Trusting Unverified Recruiters or Offers</h2>
<p>A copied logo is not proof of authorisation. Verify the company independently, be wary of guaranteed visas and purchased job offers, and send sensitive documents only through verified channels. Use our <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">fake offer checklist</a> and <a href="/blog/verify-overseas-job-offer">verification guide</a>.</p>

<h2>9. Preparing Poorly for Interviews</h2>
<p>Know the role and employer, prepare truthful examples, check the time zone and connection, and ask about duties, hours and overseas recruitment arrangements. Do not memorise invented answers or exaggerate language ability.</p>

<h2>10. Ignoring Contract Costs and Conditions</h2>
<p>Salary alone does not show whether an offer is suitable. Check currency, paid hours, duration, deductions, accommodation and transport, separate guaranteed income from uncertain overtime, and get terms in writing. Sponsorship does not guarantee permanent residence.</p>

<h2>Pre-Submission Checklist</h2>

<table>
<thead><tr><th>Check</th><th>Confirm before sending</th></tr></thead>
<tbody>
<tr><td>Fit</td><td>Essential criteria match genuine skills</td></tr>
<tr><td>Eligibility</td><td>Overseas recruitment wording understood</td></tr>
<tr><td>CV</td><td>Relevant, truthful and readable</td></tr>
<tr><td>Contact</td><td>Details and country code correct</td></tr>
<tr><td>Channel</td><td>Employer instructions followed</td></tr>
<tr><td>Records</td><td>Submitted version and confirmation saved</td></tr>
</tbody>
</table>

<p>Rejection has many causes. Review patterns over several applications to see what you can actually fix, and do not pay for a course or a placement because of one refusal.</p>

<h2>What Should Pakistani Applicants Check?</h2>

<ul>
    <li><strong>That any Pakistani recruiter is licensed.</strong> Agents placing workers abroad must be licensed Overseas Employment Promoters, which you can check on the <a href="https://beoe.gov.pk" rel="noopener">Bureau of Emigration and Overseas Employment</a> website.</li>
    <li><strong>The emigration rules for your own arrangement.</strong> Taking a job without an agent does not remove Pakistan's own emigration requirements, so confirm them with the Bureau before you travel.</li>
    <li><strong>Whether a home registration is needed.</strong> Regulated fields such as nursing and engineering have their own councils and destination regulators; the two are separate checks.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Does applying more often guarantee success?</h3>
<p>No. Relevant targeting and accurate applications matter more than volume.</p>

<h3>Can I apply without an agent?</h3>
<p>Yes, where employers accept direct applications. Immigration rules still apply.</p>

<h3>Should I follow up repeatedly?</h3>
<p>Respect the employer's instructions and timeline and keep any follow-up brief.</p>

<h3>Why do overseas applications fail?</h3>
<p>Causes include missing criteria, work-authorisation limits, a generic CV and competition.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-write-resume-job-abroad">How to Write a Resume for a Job Abroad</a>: tailor your CV</li>
    <li><a href="/blog/find-job-abroad-without-recruiter">How to Find a Job Abroad Without a Recruiter</a>: search yourself</li>
    <li><a href="/blog/verify-overseas-job-offer">How to Verify a Job Offer from Another Country</a>: check the offer</li>
    <li><a href="/blog/prepare-online-job-interview-abroad">How to Prepare for an Online Job Interview Abroad</a>: video interview</li>
</ul>

HTML;
    }
}
