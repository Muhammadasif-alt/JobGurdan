<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Nursing Jobs in Riyadh, Saudi Arabia: Requirements and Contract Basics"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The brief's five Indeed search buttons are dropped; the SCFHS, KFSHRC and HRSD links stay in the text.
 * 2. SCFHS Mumaris FAQ fetched: classification and re-classification can be applied for from outside the Kingdom through
 *    Mumaris Plus and do not need a valid work permit at the first stage. Classification requirements fetched:
 *    "Experience must not be below one year for non-Saudis", except graduates of Saudi universities; qualifications
 *    verified by the SCFHS-approved company. Registration fetched: employment letter issued within three months,
 *    residence permit for non-Saudis. Exams page fetched: booked through Prometric.
 * 3. DataFlow SCFHS FAQ fetched: verification usually takes 15 to 25 working days from payment and a complete submission.
 * 4. KFSHRC careers fetched: online application, screening, the English Language Competency Test with IELTS 5.5 or
 *    above exempt, interviews, a formal offer letter, help with visa documentation.
 * 5. HRSD Article 40 fetched: the employer bears the recruitment fees, residence fees, work permit and renewals, fines
 *    it causes, change-of-profession and exit and return fees, and the worker's return ticket. The 180-day probation
 *    cap comes from the Labour Law amendments in force since February 2025, as reported by law firms; HRSD's own
 *    labour relations page did not resolve, so the guide attributes it to the amended law and links no secondary site.
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries added.
 *
 * No poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class NursingJobsRiyadhRequirementsContractBasicsBlogSeeder extends Seeder
{
    public const SLUG = 'nursing-jobs-riyadh-requirements-contract-basics';

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
                'title' => 'Nursing Jobs in Riyadh, Saudi Arabia: Requirements and Contract Basics',
                'excerpt' => 'How overseas nurses get jobs in Riyadh: SCFHS classification from abroad through Mumaris Plus, the one-year experience rule, DataFlow in 15 to 25 working days, Prometric exams, KFSHRC hiring steps and what the employer pays under Article 40.',
                'content' => $content,
                'featured_image' => 'blogs/nursing-jobs-riyadh-requirements-contract-basics-poster.jpg',
                'tags' => 'nursing jobs in riyadh saudi arabia, saudi nursing requirements, scfhs nurse registration, riyadh nurse contract, overseas nurses saudi arabia, scfhs classification from pakistan, dataflow verification scfhs, nurse jobs saudi arabia for pakistanis',
                'meta_title' => 'Nursing Jobs in Riyadh: Requirements and Contract Basics',
                'meta_description' => 'Nursing jobs in Riyadh: SCFHS classification from abroad, the one-year experience rule, DataFlow, Prometric exams and the contract terms to check.',
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
<p><strong>To work as a nurse in Riyadh, you need professional classification and registration with the Saudi Commission for Health Specialties (SCFHS), a job with a licensed employer and a work visa and residence permit arranged by that employer.</strong> You can start SCFHS classification from Pakistan or any other country through the Mumaris Plus system, without a Saudi work permit, but non-Saudi nurses need at least one year of experience. Before you accept an offer, check the basic salary, allowances, housing, hours, leave, probation and termination terms in writing. This guide uses SCFHS, the Ministry of Human Resources and Social Development (HRSD), DataFlow and King Faisal Specialist Hospital and Research Centre (KFSHRC) pages, checked on 28 October 2026.</p>

<h2>Understand the Role You Are Applying For</h2>

<p>Search for the exact nursing category and specialty that matches your education and experience: a general ward post, intensive care and senior nursing roles have different requirements. Read each job description for essential and preferred requirements, describe your real clinical duties, and never present student placements as employment.</p>

<h2>Start With SCFHS Classification</h2>

<p>Classification decides the professional category your qualification and experience allow. SCFHS says you can apply for classification and re-classification from outside the Kingdom through Mumaris Plus, and that you do not need a valid Saudi work permit at that first stage. Its requirements state that experience "must not be below one year for non-Saudis", except for graduates of Saudi universities. Create your own Mumaris Plus account, keep your login secure, and ask SCFHS before choosing a category if your history does not fit. Sources: <a href="https://scfhs.org.sa/en/mumaris-faq" rel="noopener">SCFHS: Mumaris FAQ</a> and <a href="https://scfhs.org.sa/en/professional-classification-requirements" rel="noopener">SCFHS: Professional classification requirements</a>.</p>

<h2>Prepare Documents and Verification</h2>

<p>SCFHS asks for your qualifications, transcripts, professional registration, experience letters and identity documents, and requires qualifications issued outside the Kingdom to be verified by its approved company. That company, DataFlow, checks each document with the issuing body; its SCFHS FAQ says verification usually takes 15 to 25 working days from payment and a complete submission, longer if documents are missing or an issuer replies late. A DataFlow report is not a licence. Contact your college and former employers early so they can answer verification requests. Source: <a href="https://dataflowgroup.com/wp-content/uploads/2024/09/FAQ_SCFHS-4.pdf" rel="noopener">DataFlow: SCFHS verification FAQ</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nursing-jobs-riyadh-requirements-contract-basics-steps.jpg"
         alt="Infographic of the Riyadh nursing route: Mumaris Plus account, DataFlow verification, Prometric exam, classification, job offer, visa, registration"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Confirm Assessment and Registration Requirements</h2>

<p>SCFHS classification exams are booked through Prometric from the SCFHS exams page; confirm which exam your category needs before buying any preparation package. Professional registration is a separate service: it needs an employment letter issued within the last three months and, for non-Saudis, a residence permit, so it is completed once you are hired. Ask your employer what you may do before registration is issued. Sources: <a href="https://scfhs.org.sa/en/classification-exams" rel="noopener">SCFHS: Classification exams</a> and <a href="https://scfhs.org.sa/en/node/1985" rel="noopener">SCFHS: Professional registration</a>.</p>

<h2>Apply Through Verified Employer Channels</h2>

<p>KFSHRC describes its process as an online application, screening, an English Language Competency Test (candidates with IELTS 5.5 or above are exempt), interviews with the hiring team, a formal offer letter with pay, benefits and terms, and help with visa documentation. Other hospitals differ, so read each one's careers page. Verify any recruiter's link to the hospital independently, and check a Pakistani agent's licence with the Bureau of Emigration and Overseas Employment. Source: <a href="https://www.kfshrc.edu.sa/en/careers" rel="noopener">KFSHRC: Careers</a>.</p>

<h2>Check Contract Duration and Probation</h2>

<p>Under the Saudi Labour Law a non-Saudi worker's contract is written and for a fixed term. Since the amendments that took effect in February 2025, a probation period can be up to 180 days, must be stated in the contract, and either side can end the contract during it. Some government hospitals hire under their own employment rules, so ask which framework applies. Check the employer's legal name, workplace, job title, start date, duration and renewal terms before signing.</p>

<h2>Compare Salary and Benefits Separately</h2>

<p>Ask for a written breakdown of basic salary, fixed allowances, variable pay and deductions, keeping guaranteed pay separate from overtime or incentives. For housing, confirm whether you get accommodation or an allowance, who you share with, utilities and transport. Check whether flights are annual, joining only or end of contract, and what health insurance covers. A "competitive package" with no figures is not an offer.</p>

<h2>Know What the Employer Must Pay</h2>

<p>Article 40 of the Labour Law, as published by HRSD, says the employer bears the non-Saudi worker's recruitment fees, residence fees, work permit and their renewal, fines caused by the employer, change-of-profession and exit and re-entry fees, and the worker's return ticket home at the end of the contract. That does not automatically cover your SCFHS, DataFlow or Prometric fees, so ask about those separately and keep receipts for everything. Source: <a href="https://www.hrsd.gov.sa/en/knowledge-centre/articles/64434" rel="noopener">HRSD: What does the employer bear?</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I begin classification from Pakistan?</h3>
<p>Yes. SCFHS accepts classification applications from outside the Kingdom through Mumaris Plus, without a work permit at that stage.</p>

<h3>How much experience do I need?</h3>
<p>At least one year for non-Saudis, unless you graduated from a Saudi university.</p>

<h3>How long does DataFlow verification take?</h3>
<p>Usually 15 to 25 working days from payment and a complete set of documents.</p>

<h3>Does DataFlow completion guarantee a licence?</h3>
<p>No. It is one step; SCFHS still decides classification and registration.</p>

<h3>Where do I book the SCFHS exam?</h3>
<p>Through Prometric, from the SCFHS classification exams page.</p>

<h3>Do I need IELTS for KFSHRC?</h3>
<p>KFSHRC uses its own English test; candidates with IELTS 5.5 or above are exempt.</p>

<h3>Who pays the visa and residence fees?</h3>
<p>The employer, under Article 40 of the Labour Law, along with recruitment and work permit fees.</p>

<h3>How long can probation last?</h3>
<p>Up to 180 days under the amended Labour Law, and it must be written in the contract.</p>

<h2>Bottom Line</h2>

<p>Start SCFHS classification early, get your documents verified, apply through verified hospital channels and read the full contract before you travel. Keep professional approval, the job and the visa separate, and guaranteed pay separate from optional benefits. This guide cannot promise a job, a licence or sponsorship.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://scfhs.org.sa/en/mumaris-faq" rel="noopener">SCFHS: Mumaris FAQ</a></li>
<li><a href="https://scfhs.org.sa/en/professional-classification-requirements" rel="noopener">SCFHS: Professional classification requirements</a></li>
<li><a href="https://scfhs.org.sa/en/node/1985" rel="noopener">SCFHS: Professional registration</a> and <a href="https://scfhs.org.sa/en/classification-exams" rel="noopener">Classification exams</a></li>
<li><a href="https://dataflowgroup.com/wp-content/uploads/2024/09/FAQ_SCFHS-4.pdf" rel="noopener">DataFlow: SCFHS verification FAQ</a></li>
<li><a href="https://www.kfshrc.edu.sa/en/careers" rel="noopener">KFSHRC: Careers</a></li>
<li><a href="https://www.hrsd.gov.sa/en/knowledge-centre/articles/64434" rel="noopener">HRSD: What does the employer bear?</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nurse-jobs-london-overseas-registration">Nurse Jobs in London for Overseas Nurses</a>, the NMC route in the UK.</li>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, how US licensing compares.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan (2026)</a>, the BEOE checks before you leave.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. Labour Law provisions apply to employment under that law. It does not promise a job, a licence, a package or sponsorship.</p>
HTML;
    }
}
