<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How to Write a Resume for a Job Abroad"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The commercial job-board "Apply Now" links (Indeed, SEEK, Bayt,
 *    GulfTalent, LinkedIn) are dropped and the official ones become plain
 *    links (EURES, Job Bank, SeasonalJobs, NHS Jobs, Make it in Germany).
 * 2. The Europass and National Careers Service links are kept as resources.
 * 3. A link to JobGader's own resume-writing page and a Pakistan note are
 *    added (state your real location, do not imply work authorisation).
 * 4. Four FAQs become eight.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class ResumeForJobAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-write-resume-job-abroad';

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
                'title' => 'How to Write a Resume for a Job Abroad',
                'excerpt' => 'A strong overseas resume shows relevant skills, truthful experience and qualifications in a clear format tailored to the vacancy. See the nine steps, the formatting checks, a fill-in example and what Pakistani applicants should state honestly.',
                'content' => $content,
                'featured_image' => 'blogs/resume-for-job-abroad.jpg',
                'tags' => 'resume for job abroad, international resume format, cv for overseas job, how to write cv for foreign employer, overseas cv template, cv for pakistani applicants, europass cv, resume tips abroad',
                'meta_title' => 'How to Write a Resume for a Job Abroad',
                'meta_description' => 'Write a clear overseas resume with relevant skills, truthful experience, country-specific formatting, a sample CV and practical application checks.',
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
<p><strong>A strong resume for a job abroad shows your relevant skills, truthful work experience, qualifications and contact details in a clear format tailored to the vacancy.</strong> Start with the employer's requirements, describe what you have actually done and follow its application instructions. There is no single mandatory international template: country conventions, occupation and employer preferences differ. Your resume helps an employer assess suitability; it does not prove visa eligibility or guarantee sponsorship. This guide explains the structure and includes an adaptable example.</p>

<h2>1. Read the Vacancy Before Writing</h2>

<p>Highlight the duties, skills, experience and qualifications requested, and identify which you genuinely meet and which need more training or clarification. Use the employer's terminology where it accurately describes your background: include "room attendant" or "preventive maintenance" when those terms match your real work. Avoid copying an advertisement into your skills section without evidence. A tailored application connects the employer's needs to specific experience rather than repeating keywords.</p>

<h2>2. Choose a Clear International Resume Format</h2>

<p>For most applications, use straightforward headings and an easy-to-read layout. A reverse-chronological work history, with the most recent job first, is a useful starting point. Keep the length proportionate to your relevant experience: one or two pages is a practical target, while academic or specialist applications may need more detail. Follow any stated limit.</p>

<p>Europass offers a recognised CV creation tool, but it is not compulsory for every European employer; use it when it suits you or when it is requested. Official tool: <a href="https://europass.europa.eu/en/create-europass-cv" rel="noopener">Europass CV</a>. European vacancies: <a href="https://eures.europa.eu/index_en" rel="noopener">EURES</a>.</p>

<h2>3. Add Essential Contact Information</h2>

<p>Include your name, a professional email, a phone number with the country code and your current city and country. Add a relevant portfolio or professional profile if it strengthens the application. Pakistani applicants should state their location honestly: do not imply that you already live abroad or hold work authorisation when you do not.</p>

<p>Leave passport numbers, banking details and unrelated personal information off an ordinary resume, and provide sensitive documents only through a verified process when they are requested. Canadian vacancies open to international applicants: <a href="https://www.jobbank.gc.ca/findajob/foreign-candidates" rel="noopener">Job Bank, foreign candidates</a>.</p>

<h2>4. Write a Specific Professional Summary</h2>

<p>Use a short summary of your occupation, your relevant experience and your strongest job-related abilities. Avoid generic claims such as "hardworking person seeking any opportunity". An illustrative summary could read:</p>

<blockquote style="border-left:4px solid #1b3a6b;padding:8px 16px;margin:16px 0;color:#374151;">Housekeeping professional with three years of hotel experience in Pakistan, including guest-room cleaning, linen handling and reporting maintenance issues. Seeking a room-attendant position matching my experience and availability.</blockquote>

<p>Use that wording only if the facts are true, and replace the occupation and details with your own background. Do not add unsupported performance claims or certifications.</p>

<h2>5. Describe Experience With Evidence</h2>

<p>For each relevant job, give the employer, location, job title and dates, then use concise points about duties and results. Instead of "responsible for everything", write specific statements such as "Prepared guest rooms using the hotel's cleaning checklist" or "Recorded stock movements and reported shortages". These are examples, not lines to copy.</p>

<p>Include numbers only when you can support them, and never invent customers served, rooms cleaned or percentage improvements. Explain employment gaps briefly when useful, and keep dates consistent with your other application documents. Housekeeping and seasonal roles: <a href="https://seasonaljobs.dol.gov/" rel="noopener">SeasonalJobs (US Department of Labor)</a>.</p>

<h2>6. Include Qualifications, Skills and Languages</h2>

<p>List relevant qualifications with the institution and the completion date, and include genuine licences, certificates and training with the issuing body where useful. Do not claim foreign recognition or local registration unless you have obtained it: a home-country qualification and permission to practise overseas are different matters.</p>

<p>Describe practical skills precisely (software, tools, equipment or procedures you actually know) and state your language ability honestly rather than writing "fluent" automatically. If a test result is relevant, give accurate details and check its validity separately. German vacancies and qualification expectations: <a href="https://www.make-it-in-germany.com/en/working-in-germany/job-listings" rel="noopener">Make it in Germany job listings</a>.</p>

<h2>7. Adapt Your CV for the Destination</h2>

<p>A CV for an overseas job should follow the vacancy's language and formatting instructions. Photo and personal-detail conventions differ between countries and employers, so do not assume a photo is always required. For UK applications, the official National Careers Service guidance recommends focusing on relevant information and leaving out unnecessary personal details: <a href="https://nationalcareers.service.gov.uk/careers-advice/cv-sections" rel="noopener">writing a CV</a>.</p>

<p>Check local job titles and explain unfamiliar qualifications briefly. Use clear dates and consistent spelling, and when a translation is needed, make sure the translated content still states your experience accurately. The employer's instructions take priority over a generic template. UK healthcare vacancies: <a href="https://www.jobs.nhs.uk/candidate" rel="noopener">NHS Jobs</a>.</p>

<h2>8. An Adaptable Resume Example</h2>

<p>This fictional template shows how to write a CV for a foreign employer. Replace every placeholder with truthful information.</p>

<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px 20px;margin:16px 0;">
<p><strong>[Your Name]</strong><br>[City, Pakistan] | [Phone with country code] | [Professional email]</p>
<p><strong>Professional Summary</strong><br>[Occupation] with [actual experience] in [relevant setting]. Skilled in [verified tasks]. Interested in [specific position].</p>
<p><strong>Relevant Skills</strong></p>
<ul>
    <li>[Skill supported by your experience]</li>
    <li>[Relevant equipment, procedure or software]</li>
    <li>[Accurately stated language ability]</li>
</ul>
<p><strong>Work Experience</strong><br>[Job Title] | [Employer, Location] | [Dates]</p>
<ul>
    <li>[Specific duty you performed]</li>
    <li>[Genuine responsibility or supported achievement]</li>
</ul>
<p><strong>Education and Training</strong><br>[Qualification] | [Institution] | [Year]<br>[Relevant certificate and issuing body, if applicable]</p>
<p><strong>Availability</strong><br>[Truthful notice period, relocation interest or other relevant information]</p>
</div>

<h2>9. Check Formatting and Application Instructions</h2>

<p>Use readable fonts, consistent spacing and ordinary section headings. A simple layout is a sensible starting point for online systems, but no design guarantees that automated screening will pass you. Follow the requested file type, use a searchable document rather than an image-only scan where appropriate, and name the file clearly, such as YourName_Resume.pdf.</p>

<p>Proofread the dates, spelling, phone details and links, and check any application form separately, because uploading a resume may not complete the required questions. Keep a copy of the submitted version. Keep a master record of your genuine experience and create a shorter version for each vacancy, so you tailor the emphasis without changing facts between applications. If you want a professional to polish it, see our <a href="/resume-writing-services">resume writing service</a>.</p>

<h2>What Should Pakistani Applicants Remember?</h2>

<ul>
    <li><strong>Be honest about your location and work rights.</strong> State your city and country, and do not imply authorisation you do not have.</li>
    <li><strong>Do not pay for a CV that "guarantees" a visa.</strong> A resume is one part of an application, and only the immigration authority decides a visa. See <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>.</li>
    <li><strong>Prepare the documents that go with the CV.</strong> See <a href="/blog/documents-needed-to-work-abroad-2026">What Documents Do You Need to Work Abroad</a>.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/resume-writing-services" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        See JobGader Resume Writing Services →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is there one compulsory international format?</h3>
<p>No. Follow the destination's conventions and the employer's instructions.</p>

<h3>Should I include a photograph?</h3>
<p>Check the destination and the vacancy rather than assuming it is required.</p>

<h3>Can beginners write a useful resume?</h3>
<p>Yes. Include genuine training, projects and transferable skills without inventing employment.</p>

<h3>Does a good resume guarantee sponsorship?</h3>
<p>No. Employer recruitment and immigration eligibility are separate checks.</p>

<h3>How long should my resume be?</h3>
<p>One or two pages is a practical target for most applications. Follow any limit the employer states.</p>

<h3>Should I write a cover letter?</h3>
<p>Only when the role asks for one. Use it to explain why your relevant background fits that position.</p>

<h3>Can I use AI to write my CV?</h3>
<p>You can, but review every sentence for accuracy and remove invented results or exaggerated skills.</p>

<h3>Should I send my passport with my CV?</h3>
<p>No. Provide identity documents only through a verified process when they are requested.</p>

<h2>People Also Search For</h2>

<h3>Resume for job abroad</h3>
<p>Clear, truthful and tailored to the vacancy.</p>

<h3>International resume format</h3>
<p>Reverse-chronological is a good start.</p>

<h3>CV for overseas job</h3>
<p>Follow the employer's instructions first.</p>

<h3>How to write a CV for a foreign employer</h3>
<p>Use the template above and your real facts.</p>

<h3>Europass CV</h3>
<p>An optional recognised European format.</p>

<h3>Resume with no experience</h3>
<p>Lead with skills, training and transferable work.</p>

<h3>CV photo abroad</h3>
<p>Depends on the destination and employer.</p>

<h3>Resume writing service</h3>
<p>See our resume writing page.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/best-job-sites-work-abroad-2026">Best Job Sites to Find Work Abroad (2026)</a> &mdash; where to send it.</li>
    <li><a href="/blog/documents-needed-to-work-abroad-2026">What Documents Do You Need to Work Abroad (2026)</a> &mdash; the checklist.</li>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a> &mdash; the application steps.</li>
    <li><a href="/blog/how-to-get-a-job-abroad-without-experience-2026">How to Get a Job Abroad Without Experience (2026)</a> &mdash; beginner routes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Reviewed on 12 October 2026. The examples are fictional templates, not claims about a real applicant, and no job offer or visa sponsorship is guaranteed.</p>
HTML;
    }
}
