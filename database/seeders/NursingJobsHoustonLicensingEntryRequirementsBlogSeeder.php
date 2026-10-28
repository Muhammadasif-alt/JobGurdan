<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Nursing Jobs in Houston, Texas: Licensing and Entry Requirements"
 *
 * Checked on 28 October 2026. What changed against the brief:
 *
 * 1. The brief's four Indeed search buttons are dropped; the employer and official buttons became links in the text.
 * 2. bon.texas.gov did not connect from our network, so the Texas Board of Nursing steps are described as the Board
 *    publishes them in general terms and linked, without new figures.
 * 3. Added BLS Occupational Employment and Wage Statistics for the Houston-The Woodlands-Sugar Land metro area, May
 *    2025, via the BLS API: registered nurses 65,910 employed, annual median $99,830, mean $100,290; licensed practical
 *    and licensed vocational nurses 10,730 employed, annual median $63,670.
 * 4. Nurse Licensure Compact FAQ fetched: a multistate licence lets you practise in all compact states; "You have 60
 *    days from the time you move to apply for your license."
 * 5. Memorial Hermann fetched: GPA 3.0, accredited ADN or BSN, less than 12 months of experience, current Texas RN
 *    licence or temporary permit, BLS; cohorts at least three times a year (spring, summer, fall); NCLEX before the
 *    first day. Houston Methodist fetched: accredited BSN or entry-level MSN, ADN accepted at Baytown only with a BSN
 *    within 3 years, Texas RN eligibility or GN permit, less than six months of licensed experience, 3.0 GPA preferred.
 *    Harris Health fetched: Nurse Residency for graduates with less than a year of experience, Nurse Fellowship for RNs
 *    with a year of experience (BSN preferred, ADN accepted), an ambulatory LVN residency, and the NEXT student programme.
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for internationally
 *    educated nurses added.
 *
 * No poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class NursingJobsHoustonLicensingEntryRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'nursing-jobs-houston-licensing-entry-requirements';

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
                'title' => 'Nursing Jobs in Houston, Texas: Licensing and Entry Requirements',
                'excerpt' => 'How to get a nursing job in Houston: the Texas licence by exam or endorsement, compact licences, what Memorial Hermann, Houston Methodist and Harris Health ask of new graduates, and BLS pay of $99,830 for RNs and $63,670 for LVNs.',
                'content' => $content,
                'featured_image' => 'blogs/nursing-jobs-houston-licensing-entry-requirements-poster.jpg',
                'tags' => 'nursing jobs in houston texas, texas nursing license requirements, houston new graduate nurse jobs, rn jobs houston, nurse residency houston, lvn jobs houston, registered nurse salary houston, nurse licensure compact texas',
                'meta_title' => 'Nursing Jobs in Houston: Licensing and Entry Requirements',
                'meta_description' => 'Nursing jobs in Houston: the Texas licence by exam or endorsement, compact licences, new graduate residencies and BLS pay for RNs and LVNs.',
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
<p><strong>To work as a nurse in Houston you need authorisation to practise in Texas, either a Texas licence from the Texas Board of Nursing (by examination or endorsement) or a multistate licence from another Nurse Licensure Compact state, and you must meet each hospital's own entry rules.</strong> New graduates take the NCLEX-RN or NCLEX-PN route; nurses licensed elsewhere can transfer. Houston employed 65,910 registered nurses in May 2025 at a median of $99,830 a year, according to the US Bureau of Labor Statistics. This guide uses the BLS, the Nurse Licensure Compact and three Houston health systems' own pages, checked on 28 October 2026.</p>

<h2>Choose the Right Nursing Pathway</h2>

<p>Registered nurse (RN) and licensed vocational nurse (LVN) roles need different education and licences: the NCLEX-RN for registered nursing and the NCLEX-PN for vocational nursing. A nursing assistant post does not give RN or LVN authorisation. Search with your exact title and level, such as "graduate nurse", "nurse resident", "RN" or "LVN", and read whether a vacancy needs the licence at application or only by the start date. List student placements as clinical education, not employment.</p>

<h2>Know Houston Nursing Pay</h2>

<p>The BLS Occupational Employment and Wage Statistics for the Houston-The Woodlands-Sugar Land metro area, May 2025, show:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Occupation</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Employed</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Annual median</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Registered nurses</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">65,910</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$99,830 (mean $100,290)</td></tr>
<tr><td style="padding:8px;">Licensed practical and vocational nurses</td><td style="padding:8px;">10,730</td><td style="padding:8px;">$63,670</td></tr>
</tbody>
</table>

<p>These are averages across all experience levels; a new graduate's offer will usually start lower. Source: <a href="https://www.bls.gov/oes/current/oes_26420.htm" rel="noopener">BLS: Occupational employment and wages, Houston metro area</a>.</p>

<h2>New Graduates: Apply by Examination</h2>

<p>The Texas Board of Nursing's examination route asks for graduation from an approved nursing programme, a fingerprint background check, the Texas Nursing Jurisprudence Examination and NCLEX registration; the Board issues Authorization to Test once you meet its requirements. Keep the Board application and the NCLEX registration as two separate tasks, and remember that a booked exam is not a licence. The Board's website did not load from our network on 28 October 2026, so follow its live instructions for fees and documents. Source: <a href="https://www.bon.texas.gov/licensure_examination.asp.html" rel="noopener">Texas Board of Nursing: Licensure by examination</a>.</p>

<h2>Already Licensed: Check Endorsement or Compact Privileges</h2>

<p>Nurses licensed in another US state can apply to Texas by endorsement without retaking the NCLEX, if they meet the Board's standards. Texas is a member of the Nurse Licensure Compact: a multistate licence issued by your home compact state lets you practise in all compact states, but a single-state licence does not. If you move your primary residence to Texas, the Compact says "You have 60 days from the time you move to apply for your license." Sources: <a href="https://bon.texas.gov/licensure_endorsement.asp.html" rel="noopener">Texas Board of Nursing: Licensure by endorsement</a> and <a href="https://nursecompact.com/FAQs.page" rel="noopener">Nurse Licensure Compact: FAQs</a>.</p>

<h2>Compare Houston Nurse Residency Requirements</h2>

<p><strong>Memorial Hermann</strong> asks for a nursing GPA of 3.0 or higher, an accredited ADN or BSN, less than 12 months of experience, a current Texas RN licence or temporary permit and BLS certification, and expects you to pass the NCLEX before your first day; the degree requirement may vary by campus. It takes applications at least three times a year, in spring, summer and fall. Source: <a href="https://jobs.memorialhermann.org/us/en/nurse-residency-program" rel="noopener">Memorial Hermann: Nurse Residency Program</a>.</p>

<p><strong>Houston Methodist</strong> asks for an accredited BSN or entry-level MSN, eligibility for a Texas RN licence or a graduate nurse permit, and less than six months of licensed experience, with a 3.0 nursing GPA preferred. At Houston Methodist Baytown only, ADN graduates can join if they actively pursue a BSN and complete it within three years. Sources: <a href="https://www.houstonmethodist.org/education/nursing/houston-methodist-nursing-residency-program/" rel="noopener">Houston Methodist: Nursing residency</a> and <a href="https://www.houstonmethodistcareers.org/graduatenurse/" rel="noopener">Houston Methodist: Graduate nurse careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nursing-jobs-houston-licensing-entry-requirements-residency.jpg"
         alt="Table comparing Memorial Hermann, Houston Methodist and Harris Health nurse residency requirements for degree, GPA, experience and licence"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Consider Harris Health and LVN Opportunities</h2>

<p>Harris Health, Harris County's public health system, runs a Nurse Residency Program for graduate nurses with less than a year of experience, a Nurse Fellowship Program for RNs with one year of full-time experience (BSN preferred, ADN accepted), and an ambulatory care LVN Residency for new LVNs and those new to clinic work, where you are first hired into a clinic. Senior nursing students can join its NEXT programme as nurse techs. Source: <a href="https://jobs.harrishealth.org/nursing-program" rel="noopener">Harris Health: Nursing programs</a>.</p>

<h2>Prepare Employer Documents and Certifications</h2>

<p>Keep your resume, transcripts, licence or permit details, references and certifications in one folder with expiry dates. BLS (Basic Life Support) is required by all three systems; check which provider each accepts and whether other certifications are needed before or after hire. Do not buy courses the vacancy does not ask for.</p>

<h2>Build a Focused Application</h2>

<p>Tailor your resume to each vacancy with your qualification, graduation date, licence status, placements and certifications. Prepare interview examples about teamwork, communication, accountability and escalating concerns, while protecting patient confidentiality. Compare shifts, orientation, commute and pay before accepting.</p>

<h2>A Note for Internationally Educated Nurses</h2>

<p>Nurses educated outside the US apply to the Texas Board by examination with a credential evaluation, licence verification and English evidence where required. A Texas licence is not US work authorisation, and passing the NCLEX does not create a visa or a sponsorship offer. Read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before paying any agency.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is a BSN compulsory for every Houston nursing job?</h3>
<p>No. Memorial Hermann accepts an ADN or BSN; Houston Methodist wants a BSN except at Baytown.</p>

<h3>How much do registered nurses earn in Houston?</h3>
<p>The BLS gives an annual median of $99,830 for May 2025, with 65,910 RNs employed.</p>

<h3>How much do LVNs earn in Houston?</h3>
<p>An annual median of $63,670, with 10,730 employed, per the BLS.</p>

<h3>Can I work immediately after passing the NCLEX?</h3>
<p>Only once the Board has issued your licence or permit and the employer has cleared you.</p>

<h3>Can nurses from another state work in Houston?</h3>
<p>Yes, by Texas endorsement or with a multistate compact licence from their home state.</p>

<h3>How long do I have to switch my compact licence after moving?</h3>
<p>60 days from the move, according to the Nurse Licensure Compact.</p>

<h3>When do Houston residencies take applications?</h3>
<p>Memorial Hermann takes cohorts at least three times a year; check each system's current posting.</p>

<h3>Does a Texas licence give me a US visa?</h3>
<p>No. Licensing and immigration are separate.</p>

<h2>People Also Search For</h2>

<h3>Nursing jobs in Houston Texas</h3>
<p>RN and LVN posts across Houston's health systems.</p>

<h3>Texas nursing license requirements</h3>
<p>Approved programme, background check, jurisprudence exam and NCLEX.</p>

<h3>Houston new graduate nurse jobs</h3>
<p>Residencies at Memorial Hermann, Houston Methodist and Harris Health.</p>

<h3>RN jobs Houston</h3>
<p>65,910 RNs employed at a $99,830 median.</p>

<h3>Nurse residency Houston</h3>
<p>Programmes for graduates with under six or twelve months of experience.</p>

<h3>LVN jobs Houston</h3>
<p>10,730 employed; Harris Health runs an ambulatory LVN residency.</p>

<h3>Registered nurse salary Houston</h3>
<p>$99,830 median, $100,290 mean, May 2025.</p>

<h3>Nurse Licensure Compact Texas</h3>
<p>Texas is a compact state; multistate licences are valid there.</p>

<h2>Bottom Line</h2>

<p>Pick the right licence route, confirm your Texas authorisation, and compare each hospital's degree, GPA and experience rules before applying. Licensing, residency admission and a job offer are separate decisions. This guide cannot promise a job or a licence.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.bls.gov/oes/current/oes_26420.htm" rel="noopener">US Bureau of Labor Statistics: Occupational employment and wages, Houston metro, May 2025</a></li>
<li><a href="https://www.bon.texas.gov/licensure_examination.asp.html" rel="noopener">Texas Board of Nursing: Examination</a> and <a href="https://bon.texas.gov/licensure_endorsement.asp.html" rel="noopener">Endorsement</a>, which did not load from our network on 28 October 2026</li>
<li><a href="https://nursecompact.com/FAQs.page" rel="noopener">Nurse Licensure Compact: FAQs</a></li>
<li><a href="https://jobs.memorialhermann.org/us/en/nurse-residency-program" rel="noopener">Memorial Hermann: Nurse Residency Program</a></li>
<li><a href="https://www.houstonmethodist.org/education/nursing/houston-methodist-nursing-residency-program/" rel="noopener">Houston Methodist: Nursing residency</a></li>
<li><a href="https://jobs.harrishealth.org/nursing-program" rel="noopener">Harris Health: Nursing programs</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nurse-jobs-london-overseas-registration">Nurse Jobs in London for Overseas Nurses</a>, the NMC route in the UK.</li>
    <li><a href="/blog/nursing-jobs-riyadh-requirements-contract-basics">Nursing Jobs in Riyadh</a>, the SCFHS route in Saudi Arabia.</li>
    <li><a href="/blog/paramedic-jobs-toronto-training-certification">Paramedic Jobs in Toronto</a>, another healthcare career with its own certification.</li>
    <li><a href="/blog/data-entry-jobs-houston-no-experience">Data Entry Jobs in Houston</a>, another Houston entry route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. It does not promise a job, a residency place, a licence or sponsorship.</p>
HTML;
    }
}
