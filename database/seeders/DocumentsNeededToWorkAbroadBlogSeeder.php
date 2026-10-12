<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "What Documents Do You Need to Work Abroad"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The Apply Now buttons become plain official links, and the editorial
 *    note's "all ten" links are dropped (the guide names official pages for
 *    the UK, the US, New Zealand, Canada and Germany only).
 * 2. Pakistan-specific points are added: a police certificate is requested
 *    only where the route needs it, attestation by the Ministry of Foreign
 *    Affairs is a step some destinations ask for and should not be bought
 *    unless requested, and the licensed-recruiter check.
 * 3. Five FAQs become eight.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class DocumentsNeededToWorkAbroadBlogSeeder extends Seeder
{
    public const SLUG = 'documents-needed-to-work-abroad-2026';

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
                'title' => 'What Documents Do You Need to Work Abroad',
                'excerpt' => 'A passport, a job offer, the employer\'s approval details and your visa documents are the core. Qualifications, language results, medical and police certificates apply only on some routes. See the eight document groups and a checklist.',
                'content' => $content,
                'featured_image' => 'blogs/documents-needed-to-work-abroad.jpg',
                'tags' => 'documents needed to work abroad, work abroad checklist, what papers do you need for overseas job, work visa documents list, visa document checklist, police certificate for visa, certified translation visa, attestation of documents',
                'meta_title' => 'What Documents Do You Need to Work Abroad',
                'meta_description' => 'Prepare your overseas work documents with a practical checklist covering passports, contracts, visa evidence, qualifications and conditional requirements.',
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
<p><strong>The documents needed to work abroad commonly include a passport, a job offer or contract, the employer's approval details and your visa application documents.</strong> Depending on the destination, the occupation and your circumstances, you may also need qualifications, evidence of experience, language results, medical or police certificates and financial records. There is no universal checklist that requires every applicant to submit everything. Start with the official instructions for your exact work route, then prepare only the evidence that applies.</p>

<h2>1. Passport and Identity Documents</h2>

<p>Your passport establishes identity and nationality. Check its validity and condition and the destination's requirements before booking appointments; validity rules and exceptions differ, so do not assume one six-month rule applies everywhere. Prepare clear copies of the requested pages. National identity documents, previous passports or immigration records may also be requested.</p>

<p>Check that names, birth dates and passport numbers match your application. If records differ, follow the authority's instructions for explaining or correcting the discrepancy. Official US instructions: <a href="https://travel.state.gov/content/travel/en/us-visas/employment/temporary-worker-visas.html" rel="noopener">US temporary worker visas</a>.</p>

<h2>2. Job Offer and Employment Contract</h2>

<p>For employer-led routes, obtain the required offer or agreement and read the duties, employer details, location, pay, hours and employment dates before accepting. An offer letter does not automatically grant work permission: your employer may need to complete further immigration steps before you can apply.</p>

<p>Confirm the company and the recruiter using contact details you found yourself, keep the original vacancy and written correspondence, and ask for corrections if the contract differs from what was discussed. Official New Zealand requirements: <a href="https://www.immigration.govt.nz/visas/accredited-employer-work-visa/" rel="noopener">Accredited Employer Work Visa</a>.</p>

<h2>3. Sponsorship, Nomination or Petition Information</h2>

<p>The employer evidence you need depends on the destination. UK Skilled Worker applicants need sponsorship reference details, and many US temporary worker categories need an approved employer petition before the worker's visa application. Other systems use nominations, employment permits or employer-specific approvals, so ask which documents or reference numbers the employer must provide.</p>

<p>Self-application does not remove these prerequisites, and a recruiter's letter cannot replace the official employer approval your route requires. Official Canadian routes: <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada.html" rel="noopener">Work in Canada</a>.</p>

<h2>4. Qualifications and Work Experience</h2>

<p>Prepare genuine qualifications and employment evidence where the role or visa requires them: certificates, transcripts, references or detailed employment records. Describe your actual duties, dates and skills, and never invent experience or alter a certificate to match a vacancy.</p>

<p>Recognition of a qualification and permission to practise a profession are separate checks. Nurses, electricians and other regulated workers should verify the destination's requirements before assuming a home-country credential is enough. Official German routes: <a href="https://www.make-it-in-germany.com/en/visa-residence/types" rel="noopener">Make it in Germany visa types</a>.</p>

<h2>5. Language Evidence and Professional Registration</h2>

<p>Some work routes require language evidence, and employers or regulators may add communication standards. Check which test, qualification or exemption the authority accepts. IELTS is not a universal requirement for every overseas job, so do not pay for an unsuitable test because a recruiter recommends it without naming the rule. Where professional registration is required, confirm the stage, the accepted documents and whether you must complete it before employment.</p>

<h2>6. Medical, Police and Financial Records</h2>

<p>These requirements are conditional. Medical examinations, tuberculosis tests or police certificates depend on the destination, the route, your residence history and other circumstances, and financial evidence may be required or subject to exceptions. Use the specified clinic or issuing process where instructed, and check document validity before ordering certificates too early.</p>

<p>Do not assume a general medical report or a certificate from one country meets every requirement: follow the authority's checklist and any request for more evidence.</p>

<h2>7. Application Forms, Photos and Receipts</h2>

<p>Prepare the forms, photographs and confirmations your actual process requires. US temporary visa applications commonly involve the DS-160 form and petition information, while other destinations use their own systems. Follow the photograph specifications rather than reusing any picture, and keep submission confirmations, payment receipts and appointment details. Official UK application page: <a href="https://www.gov.uk/skilled-worker-visa/apply-from-outside-the-uk" rel="noopener">Apply from outside the UK</a>.</p>

<h2>8. Translations, Certification and Family Documents</h2>

<p>If your documents are in a language the authority does not accept, follow its translation requirements. UK Skilled Worker guidance asks for certified translations of documents that are not in English or Welsh. Translation, notarisation, attestation and legalisation are different processes, so do not pay for all of them automatically: check what your destination actually asks for. Official UK list: <a href="https://www.gov.uk/skilled-worker-visa/documents-you-must-provide" rel="noopener">Skilled Worker documents</a>.</p>

<p>If family members apply, relationship evidence may be required. Dependants have separate eligibility and document conditions, so your approval does not automatically establish theirs.</p>

<h2>Work Abroad Checklist: Organise Before Applying</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Document group</th>
            <th style="padding:10px;text-align:left;">Practical check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Identity</strong></td><td style="padding:10px;">Names, numbers and validity match</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Employment</strong></td><td style="padding:10px;">Employer, duties and terms are verified</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Employer approvals</strong></td><td style="padding:10px;">Required references are available</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Qualifications</strong></td><td style="padding:10px;">Evidence is genuine and relevant</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Conditional records</strong></td><td style="padding:10px;">Tests and certificates follow the instructions</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Translations</strong></td><td style="padding:10px;">Required standards are met</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Application records</strong></td><td style="padding:10px;">Forms, receipts and confirmations are saved</td></tr>
    </tbody>
</table>
</div>

<p>Create secure digital copies and keep the originals accessible for any appointment. Record expiry dates and deadlines, and submit sensitive documents only through verified employer or official channels. A tracking sheet with the document name, issuing authority, date obtained, expiry date, translation status and submission deadline makes it easy to see what is outstanding and prevents paying twice for papers you already hold.</p>

<h2>What Should Pakistani Applicants Check?</h2>

<ul>
    <li><strong>The destination's instructions for applications made from Pakistan.</strong> Do not assume a friend's checklist from another country or occupation applies to you.</li>
    <li><strong>Police certificates and attestation.</strong> Request a police certificate only when your route asks for it, and attest documents through the Ministry of Foreign Affairs only when the destination requires attestation.</li>
    <li><strong>The recruiter's licence.</strong> Check licensed promoters on the <a href="https://beoe.gov.pk" rel="noopener">Bureau of Emigration and Overseas Employment</a> website.</li>
    <li><strong>That you do not pay for documents you do not need.</strong> See <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What papers do you need for an overseas job application?</h3>
<p>Employers commonly ask for a CV and evidence of experience or qualifications. Visa documents depend on the route.</p>

<h3>Is a police certificate always compulsory?</h3>
<p>No. Follow the route and residence-history requirements.</p>

<h3>Can an offer letter replace a work visa?</h3>
<p>No. Employment documents and immigration authorisation serve different purposes.</p>

<h3>Should I attest every document first?</h3>
<p>Check the actual requirements before paying for certification or legalisation.</p>

<h3>Do complete documents guarantee approval?</h3>
<p>No. The authority assesses eligibility and the evidence provided.</p>

<h3>Do I need a certified translation?</h3>
<p>Only if your documents are not in a language the authority accepts. Check its translation standard first.</p>

<h3>How long should my passport be valid?</h3>
<p>It depends on the destination and route. Check the official page rather than assuming one rule.</p>

<h3>Can my family use my documents?</h3>
<p>Dependants have separate eligibility and document conditions. Your approval does not establish theirs.</p>

<h2>People Also Search For</h2>

<h3>Documents needed to work abroad</h3>
<p>Passport, offer, employer approval and visa documents.</p>

<h3>Work abroad checklist</h3>
<p>Seven document groups above.</p>

<h3>What papers do you need for an overseas job</h3>
<p>CV, evidence of experience and the visa documents.</p>

<h3>Work visa documents list</h3>
<p>Depends on the route and country.</p>

<h3>Police certificate for visa</h3>
<p>Only where the route asks for it.</p>

<h3>Attestation of documents</h3>
<p>Only where the destination requires it.</p>

<h3>Certified translation for visa</h3>
<p>Follow the authority's translation standard.</p>

<h3>Apply for a work visa without an agent</h3>
<p>See our step-by-step guide.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a> &mdash; the application steps.</li>
    <li><a href="/blog/how-to-write-resume-job-abroad">How to Write a Resume for a Job Abroad</a> &mdash; the CV that goes with these documents.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer (2026)</a> &mdash; verify before you pay.</li>
    <li><a href="/blog/countries-visa-sponsorship-skilled-workers-2026">Countries That Offer Visa Sponsorship for Skilled Workers</a> &mdash; eight routes compared.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general planning checklist, not legal advice or a universal submission list. Reviewed on 12 October 2026 against official sources. Select your own route and follow its current instructions.</p>
HTML;
    }
}
