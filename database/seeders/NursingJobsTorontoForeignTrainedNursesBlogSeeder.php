<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Nursing Jobs in Toronto, Ontario for Foreign-Trained Nurses"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The brief's five Indeed search buttons are dropped; CNO, Sunnybrook and UHN stay as links in the text.
 * 2. CNO pages fetched: RN needs a baccalaureate nursing degree and RPN a practical nursing diploma "approved or
 *    recognized in any jurisdiction", otherwise a substantial equivalence review; ECA providers WES, ICAS and ICES, and
 *    NNAS expedited reports by CGFNS (now TruMerit) still accepted; application fee $433 in 2026 and $454 in 2027,
 *    non-refundable; NCLEX-RN and REx-PN plus the jurisprudence exam; TTP courses typically 7 to 14 weeks online with
 *    no clinical component, valid five years; IELTS (General or Academic) writing 6.5, speaking 7, listening 7,
 *    reading 6.5, total 7; evidence of practice within three years, with 400 hours of extra education for RNs out of
 *    practice 3 to 10 years; SPEP at least 140 supervised hours.
 * 3. UHN fetched: warns its name is used in a global recruitment scam and that it "is not running an international
 *    campaign seeking to hire health care professionals"; never charges applicants. Sunnybrook fetched: an IEN career
 *    pathway supporting nurses to meet CNO requirements.
 * 4. Ontario Health's nursing fee reimbursement page returned 403, so the guide mentions the programme without terms.
 * 5. Four FAQs rewritten and expanded to eight; eight People Also Search For entries added.
 *
 * Posters from public/public/blog-355 (27 October set, images 10 and 11). The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class NursingJobsTorontoForeignTrainedNursesBlogSeeder extends Seeder
{
    public const SLUG = 'nursing-jobs-toronto-foreign-trained-nurses';

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
                'title' => 'Nursing Jobs in Toronto, Ontario for Foreign-Trained Nurses',
                'excerpt' => 'How internationally educated nurses register with the College of Nurses of Ontario: WES, ICAS or ICES assessment, the $433 fee, NCLEX-RN or REx-PN, IELTS scores, Transition to Practice, evidence of practice, SPEP and Toronto hospital pathways.',
                'content' => $content,
                'featured_image' => 'blogs/nursing-jobs-toronto-foreign-trained-nurses.jpg',
                'tags' => 'nursing jobs in toronto for foreign-trained nurses, internationally educated nurses ontario, cno registration requirements, toronto rn jobs, rpn jobs toronto, cno ielts score, transition to practice cno, spep ontario',
                'meta_title' => 'Toronto Nursing Jobs for Foreign-Trained Nurses: CNO Steps',
                'meta_description' => 'Foreign-trained nurses in Toronto: CNO registration, credential assessment, the $433 fee, NCLEX-RN or REx-PN, IELTS, Transition to Practice and SPEP.',
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
<p><strong>Foreign-trained nurses who want to work in Toronto must register with the College of Nurses of Ontario (CNO), hold Canadian citizenship or an immigration status that allows nursing work, and then apply to employers in the category they are registered in.</strong> The CNO route is a credential assessment, an online application with a $433 fee in 2026, the NCLEX-RN or REx-PN and a jurisprudence exam, proof of English or French, a Transition to Practice course and evidence of recent practice. Overseas experience alone is not permission to practise. This guide uses the CNO's own pages and two Toronto hospitals' careers pages, checked on 28 October 2026.</p>

<h2>Choose the Correct Ontario Nursing Category</h2>

<p>Ontario has Registered Nurses (RNs) and Registered Practical Nurses (RPNs). CNO requires a baccalaureate nursing degree approved or recognised in any jurisdiction for RN applicants, and a practical nursing diploma for RPN applicants. If your education does not meet that, CNO reviews your nursing education and any extra courses for substantial equivalence to an approved Ontario programme; education in other health fields, such as medicine or midwifery, does not count. Ask for that individual assessment before paying for another course. Source: <a href="https://www.cno.org/become-a-nurse/registration-requirements/nursing-education" rel="noopener">CNO: Nursing education</a>.</p>

<h2>Arrange the Correct Credential Assessment</h2>

<p>Your education must be verified by one of CNO's approved educational credential assessment providers: World Education Services (WES), the International Credential Assessment Service of Canada (ICAS) or the International Credential Evaluation Service (ICES) at BCIT. CNO also still accepts National Nursing Assessment Service (NNAS) expedited reports prepared by CGFNS, now called TruMerit. Once your provider confirms it has sent your report to CNO, you can start your CNO application. An assessment made for immigration is not automatically the right report for nursing. Source: <a href="https://www.cno.org/become-a-nurse/approved-educational-credential-assessment-service-providers" rel="noopener">CNO: Approved credential assessment providers</a>.</p>

<h2>Open and Manage Your CNO Application</h2>

<p>Create an account on CNO's Maintain your Membership portal, start a new application and pay the non-refundable application fee: $433 for the General Class (RN or RPN) in 2026, rising to $454 in 2027. You must provide proof of Canadian citizenship or an immigration status that authorises nursing practice in Ontario, which you can submit at any point in the process. CNO warns never to share your login details with consultants or exam-preparation services. Sources: <a href="https://www.cno.org/become-a-nurse/registration-guides/outside-canada" rel="noopener">CNO: Applicants educated outside Canada</a> and <a href="https://www.cno.org/become-a-nurse/fees" rel="noopener">CNO: Fees</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nursing-jobs-toronto-foreign-trained-nurses-city.jpg"
         alt="A nurse in navy scrubs holding a clipboard in front of the Toronto skyline and CN Tower, with hospital scenes of nurses at work"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Complete the Examinations and Transition to Practice</h2>

<p>RN applicants take the NCLEX-RN and RPN applicants the REx-PN, and both take CNO's jurisprudence exam on Ontario's professional rules. Transition to Practice (TTP) is a separate requirement: CNO-approved courses typically run 7 to 14 weeks online in one term, have no clinical component, and stay valid for five years after completion. Passing an exam or finishing TTP does not mean you are registered until CNO says so. Sources: <a href="https://www.cno.org/become-a-nurse/examinations/examinations" rel="noopener">CNO: Examinations</a> and <a href="https://cno.org/become-a-nurse/registration-requirements/new-registration-requirements/transition-to-practice-requirement" rel="noopener">CNO: Transition to Practice</a>.</p>

<h2>Demonstrate Language Ability</h2>

<p>CNO accepts several kinds of language evidence, including education, experience, registration elsewhere and approved tests taken in the past two years. For IELTS, General Training or Academic, the minimum scores are writing 6.5, speaking 7, listening 7, reading 6.5 and an overall 7. IELTS is not compulsory for everyone, and studying in English does not automatically meet the requirement. Source: <a href="https://www.cno.org/become-a-nurse/registration-requirements/proficiency-in-english-or-french" rel="noopener">CNO: Proficiency in English or French</a>.</p>

<h2>Show Evidence of Recent Practice</h2>

<p>You must show nursing practice or nursing education within the past three years in the same category you are applying for. RN applicants who have not practised for 3 to 10 years must complete 400 hours of clinical or consolidation experience plus specified course content, and longer gaps need more. Ask CNO which route applies before enrolling in a refresher course. Source: <a href="https://www.cno.org/become-a-nurse/registration-requirements/evidence-of-practice" rel="noopener">CNO: Evidence of practice</a>.</p>

<h2>Understand Supervised Practice (SPEP)</h2>

<p>The Supervised Practice Experience Partnership is run by CNO, Ontario Health and CNO-approved organisations. Eligible applicants complete at least 140 hours of supervised practice with a qualified preceptor to show current knowledge, skill, judgement and language proficiency. It is not a job offer and does not replace the other requirements, so confirm with CNO that you qualify first. Ontario Health has also run a temporary programme reimbursing some registration costs for internationally educated nurses; its page was unavailable when we checked, so confirm the current terms before counting on it. Source: <a href="https://cno.org/become-a-nurse/registration-requirements/evidence-of-practice/supervised-practice-experience-partnership" rel="noopener">CNO: Supervised Practice Experience Partnership</a>.</p>

<h2>Search Toronto Employers Carefully</h2>

<p>Sunnybrook Health Sciences Centre runs an Internationally Educated Nurses career pathway that supports IENs in meeting CNO requirements and moving into practice. University Health Network (UHN), by contrast, warns that its name is being used in a global recruitment scam and that it "is not running an international campaign seeking to hire health care professionals"; it never asks candidates for money. Apply only through each hospital's own job system, and read whether a posting needs current CNO registration. Sources: <a href="https://sunnybrook.ca/join-our-team/" rel="noopener">Sunnybrook: Join our team</a> and <a href="https://www.uhn.ca/corporate/Careers/Pages/default.aspx" rel="noopener">UHN: Careers</a>.</p>

<h2>Prepare Your Application and Budget</h2>

<p>State your qualification, overseas registration, CNO status, experience and certifications exactly. Budget separately for the credential assessment, the $433 CNO fee, the NCLEX or REx-PN, language tests, the TTP course and living costs, keep receipts, and avoid paid "packages" that promise to skip assessment. CNO registration is not a work permit; read <a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a> for the Canadian permit rules.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How much is the CNO application fee?</h3>
<p>$433 for the General Class in 2026 and $454 in 2027, non-refundable.</p>

<h3>Is NNAS the only assessment option?</h3>
<p>No. CNO accepts WES, ICAS and ICES, and still accepts NNAS expedited reports by TruMerit.</p>

<h3>What IELTS score does CNO need?</h3>
<p>Writing 6.5, speaking 7, listening 7, reading 6.5 and 7 overall, in the past two years.</p>

<h3>How long is Transition to Practice?</h3>
<p>Approved courses typically take 7 to 14 weeks online and have no clinical component.</p>

<h3>What if I have not practised recently?</h3>
<p>RNs out of practice for 3 to 10 years need 400 hours of extra clinical experience and coursework.</p>

<h3>Does passing the NCLEX guarantee a Toronto job?</h3>
<p>No. Other CNO requirements and the employer's selection still apply.</p>

<h3>Is UHN recruiting nurses from overseas?</h3>
<p>UHN says it is not running an international recruitment campaign and warns of scams using its name.</p>

<h3>Does CNO registration give me a work permit?</h3>
<p>No. Registration and immigration are separate.</p>

<h2>Bottom Line</h2>

<p>Start with CNO's current pathway, get the right credential assessment and clear each registration requirement, then apply to Toronto employers with an accurate account of your status. Verify every recruitment claim. This guide cannot promise registration, a job or a work permit.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.cno.org/become-a-nurse/registration-guides/outside-canada" rel="noopener">CNO: Applicants educated outside Canada</a></li>
<li><a href="https://www.cno.org/become-a-nurse/registration-requirements/nursing-education" rel="noopener">CNO: Nursing education</a> and <a href="https://www.cno.org/become-a-nurse/approved-educational-credential-assessment-service-providers" rel="noopener">approved assessment providers</a></li>
<li><a href="https://www.cno.org/become-a-nurse/fees" rel="noopener">CNO: Fees</a> and <a href="https://www.cno.org/become-a-nurse/examinations/examinations" rel="noopener">Examinations</a></li>
<li><a href="https://cno.org/become-a-nurse/registration-requirements/new-registration-requirements/transition-to-practice-requirement" rel="noopener">CNO: Transition to Practice</a> and <a href="https://www.cno.org/become-a-nurse/registration-requirements/proficiency-in-english-or-french" rel="noopener">Proficiency in English or French</a></li>
<li><a href="https://www.cno.org/become-a-nurse/registration-requirements/evidence-of-practice" rel="noopener">CNO: Evidence of practice</a> and <a href="https://cno.org/become-a-nurse/registration-requirements/evidence-of-practice/supervised-practice-experience-partnership" rel="noopener">SPEP</a></li>
<li><a href="https://sunnybrook.ca/join-our-team/" rel="noopener">Sunnybrook: Join our team</a> and <a href="https://www.uhn.ca/corporate/Careers/Pages/default.aspx" rel="noopener">UHN: Careers</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/paramedic-jobs-toronto-training-certification">Paramedic Jobs in Toronto</a>, another Toronto healthcare career.</li>
    <li><a href="/blog/nurse-jobs-london-overseas-registration">Nurse Jobs in London for Overseas Nurses</a>, the NMC route in the UK.</li>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, how US licensing compares.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. It does not promise registration, a job or sponsorship; fees and requirements can change.</p>
HTML;
    }
}
