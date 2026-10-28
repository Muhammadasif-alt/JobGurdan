<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "CNA Jobs in Atlanta, Georgia: Certification and Pay Overview"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The brief's seven Indeed search buttons are dropped; Emory and Piedmont careers and the official pages stay as
 *    links in the text.
 * 2. Georgia Nurse Aide Program (Alliant Health Solutions) fetched: "a minimum of 85 hours, which includes classroom, lab
 *    and clinical rotation". Credentia fetched: 70 multiple-choice questions, oral option on request, five randomly
 *    selected skills; new candidates get one year after training and three attempts at each part; renewal needs at
 *    least eight paid hours every 24 consecutive months under a nurse, documented before expiry, with no renewal fee.
 * 3. O*NET local wages fetched for Atlanta-Sandy Springs-Roswell: hourly $16.15, $17.94, $18.92, $21.49, $23.32; annual
 *    $33,590 to $48,510, median $39,350. BLS API confirmed the same May 2025 figures and added employment of 23,260.
 * 4. Emory Healthcare and Piedmont careers pages confirmed live.
 * 5. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for readers in
 *    Pakistan added.
 *
 * Posters from public/public/blog-355 (27 October set, images 13 and 14). The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class CnaJobsAtlantaGeorgiaCertificationPayBlogSeeder extends Seeder
{
    public const SLUG = 'cna-jobs-atlanta-georgia-certification-pay';

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
                'title' => 'CNA Jobs in Atlanta, Georgia: Certification and Pay Overview',
                'excerpt' => 'How to become a CNA in Atlanta: Georgia\'s 85-hour training minimum, the 70-question written and five-skill NNAAP exam, the nurse aide registry and free renewal, and BLS pay for Atlanta nursing assistants with a $18.92 hourly median.',
                'content' => $content,
                'featured_image' => 'blogs/cna-jobs-atlanta-georgia-certification-pay.jpg',
                'tags' => 'cna jobs in atlanta georgia, cna certification georgia, cna salary atlanta, nursing assistant jobs atlanta, entry level cna jobs atlanta, georgia nurse aide registry, nnaap exam georgia, cna renewal georgia',
                'meta_title' => 'CNA Jobs in Atlanta, Georgia: Certification and Pay',
                'meta_description' => 'CNA jobs in Atlanta: the 85-hour Georgia training, the NNAAP exam, the nurse aide registry, renewal rules and BLS pay with a $18.92 hourly median.',
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
<p><strong>To get a CNA job in Atlanta, complete a Georgia-approved nurse aide training programme of at least 85 hours, pass both parts of the NNAAP exam, and get listed on the Georgia Nurse Aide Registry.</strong> Atlanta-Sandy Springs-Roswell had 23,260 nursing assistants in May 2025 at a median of $18.92 an hour, or $39,350 a year, according to the US Bureau of Labor Statistics. Actual offers depend on the employer, shift and experience. This guide uses Georgia's nurse aide programme, the exam provider Credentia, O*NET and the BLS, checked on 28 October 2026.</p>

<h2>What Does a CNA Do?</h2>

<p>Certified nursing assistants help patients with daily care under a nurse's supervision: bathing, dressing, toileting, repositioning, helping with meals and reporting changes to the nurse. Expect close patient contact, long periods on your feet and careful teamwork during transfers. Reliability, accurate observation and infection control matter as much as kindness. A CNA is not a registered nurse.</p>

<h2>Choose an Approved Training Program</h2>

<p>The Georgia Nurse Aide Program requires a minimum of 85 hours for every approved training course, covering classroom, lab and clinical rotation. Check that a school is approved before paying, and do not assume an online caregiver certificate counts. Ask about clinical placements, attendance rules, supplies, exam preparation, the total cost and refund terms. Source: <a href="https://nurseaide.allianthealth.org/NurseAide/SearchCNA" rel="noopener">Georgia Nurse Aide Program: Registry and training</a>.</p>

<h2>Pass Both Exam Components</h2>

<p>Credentia runs Georgia's NNAAP exam. The written part has 70 multiple-choice questions in English, and you can ask for an oral version when you register if reading English is difficult. The skills part asks you to perform five randomly selected nurse aide skills in front of a nurse evaluator. New candidates have one year after finishing an approved course to pass both parts, with three attempts at each. Practise with the official skills checklist and confirm fees and ID rules with Credentia before booking. Sources: <a href="https://kb-ga.credentia.com/en/article/exam-overview" rel="noopener">Credentia Georgia: Exam overview</a> and <a href="https://kb-ga.credentia.com/en/article/eligibility-criteria" rel="noopener">Eligibility criteria</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cna-jobs-atlanta-georgia-certification-pay-care.jpg"
         alt="A nursing assistant in navy scrubs with an older patient, against the Atlanta skyline and scenes of care home and hospital work"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>CNA Pay in Atlanta</h2>

<p>The BLS figures for nursing assistants in Atlanta-Sandy Springs-Roswell, May 2025, shown on O*NET, are:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Percentile</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Hourly</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Annual</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">10th</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$16.15</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$33,590</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">25th</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$17.94</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$37,320</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Median</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$18.92</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$39,350</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">75th</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$21.49</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$44,700</td></tr>
<tr><td style="padding:8px;">90th</td><td style="padding:8px;">$23.32</td><td style="padding:8px;">$48,510</td></tr>
</tbody>
</table>

<p>These cover the whole metro occupation, not a starting rate at one employer. As a rough guide, $19 an hour for 40 paid hours a week is about $3,293 a month before deductions (52 weeks divided by 12); at 36 hours it is about $2,964. Compare base pay, guaranteed hours, shift differentials, health insurance, paid leave, parking and commute. Sources: <a href="https://www.onetonline.org/link/localwages/31-1131.00?st=GA" rel="noopener">O*NET: Nursing assistant wages in Georgia</a> and <a href="https://www.bls.gov/oes/current/oes_12060.htm" rel="noopener">BLS: Atlanta metro occupational wages</a>.</p>

<h2>Where to Look for Atlanta CNA Jobs</h2>

<p>Search the big health systems' own careers sites for nursing assistant, patient care assistant and related titles, and read each posting's certification requirement, since titles vary. Emory Healthcare and Piedmont Healthcare both list openings across their Atlanta-area hospitals; filter by location and check the facility address and shift. Also look at skilled nursing, rehabilitation and home care employers, asking about orientation, lifting equipment, travel between clients and mileage. Sources: <a href="https://www.emoryhealthcare.org/careers" rel="noopener">Emory Healthcare: Careers</a> and <a href="https://piedmontcareers.org/search/" rel="noopener">Piedmont Healthcare: Job search</a>.</p>

<h2>Applying Without Previous Paid Experience</h2>

<p>Build a short resume around your approved training, clinical placement, registry status and certificates, and describe the placement as supervised training, not employment. Use examples of communication, safe assistance and reliable attendance. In interviews, be ready to talk about protecting privacy, responding to a patient's concern and asking for help when a task is beyond your training.</p>

<h2>Questions Before Accepting an Offer</h2>

<p>Ask who runs your orientation and for how long, whether you will have a preceptor, which unit and manager you report to, the shift length and whether you will float. Get the hourly rate, employment status and schedule in writing, check whether onboarding sessions are paid, and test the commute at your shift time.</p>

<h2>Keeping Certification Active</h2>

<p>To renew on the Georgia registry, you must work for pay doing nursing-related services for at least eight hours in every 24 consecutive months under a nurse's supervision, and document it through the online renewal form before your listing expires. The listing runs 24 months from your last exam or recertification, and there is no renewal fee. If it lapses, you may need a new course or exam, so call the registry. Source: <a href="https://kb-ga.credentia.com/en/article/the-registry" rel="noopener">Credentia Georgia: The registry</a>.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Georgia CNA training and jobs need existing US work authorisation, and CNA posts do not lead to visa sponsorship. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> for routes that do accept overseas applicants.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How many training hours does Georgia require?</h3>
<p>At least 85 hours, including classroom, lab and clinical rotation.</p>

<h3>How many questions are on the written exam?</h3>
<p>70 multiple-choice questions, with an oral option on request.</p>

<h3>How many skills are tested?</h3>
<p>Five randomly selected nurse aide skills.</p>

<h3>How much do CNAs earn in Atlanta?</h3>
<p>The median is $18.92 an hour, or $39,350 a year, for May 2025.</p>

<h3>How do I keep my certification?</h3>
<p>Work at least eight paid nursing-related hours every 24 months under a nurse and document it before expiry. There is no fee.</p>

<h3>Can I become a CNA without a college degree?</h3>
<p>Yes. The standard route is an approved training course and the exam.</p>

<h3>Can I transfer certification from another state?</h3>
<p>Georgia has a separate eligibility route for out-of-state nurse aides; ask the registry before enrolling again.</p>

<h3>Is a patient care technician always a CNA?</h3>
<p>No. Employers use different titles and requirements, so read the posting.</p>

<h2>People Also Search For</h2>

<h3>CNA jobs in Atlanta Georgia</h3>
<p>23,260 nursing assistants work in the metro area.</p>

<h3>CNA certification Georgia</h3>
<p>85-hour approved training plus the NNAAP exam.</p>

<h3>CNA salary Atlanta</h3>
<p>$18.92 median hourly, $39,350 a year.</p>

<h3>Nursing assistant jobs Atlanta</h3>
<p>Hospitals, nursing homes, rehabilitation and home care.</p>

<h3>Entry level CNA jobs Atlanta</h3>
<p>Look for postings that welcome new graduates and offer orientation.</p>

<h3>Georgia nurse aide registry</h3>
<p>Your listing confirms certification to employers.</p>

<h3>NNAAP exam Georgia</h3>
<p>Written or oral test plus five skills, run by Credentia.</p>

<h3>CNA renewal Georgia</h3>
<p>Eight paid hours every 24 months, no fee.</p>

<h2>Bottom Line</h2>

<p>Start with approved training and the NNAAP exam, get your registry listing, then compare Atlanta offers against the BLS benchmarks and the real working conditions. This guide cannot promise a job or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://nurseaide.allianthealth.org/NurseAide/SearchCNA" rel="noopener">Georgia Nurse Aide Program: Registry and training minimum</a></li>
<li><a href="https://www.onetonline.org/link/localwages/31-1131.00?st=GA" rel="noopener">O*NET: Nursing assistant wages in Georgia</a> and <a href="https://www.bls.gov/oes/current/oes_12060.htm" rel="noopener">BLS: Atlanta metro, May 2025</a></li>
<li><a href="https://kb-ga.credentia.com/en/article/exam-overview" rel="noopener">Credentia Georgia: Exam overview</a>, <a href="https://kb-ga.credentia.com/en/article/eligibility-criteria" rel="noopener">eligibility</a> and <a href="https://kb-ga.credentia.com/en/article/the-registry" rel="noopener">the registry</a></li>
<li><a href="https://www.emoryhealthcare.org/careers" rel="noopener">Emory Healthcare: Careers</a> and <a href="https://piedmontcareers.org/search/" rel="noopener">Piedmont Healthcare: Job search</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, the next step up from CNA to RN or LVN.</li>
    <li><a href="/blog/security-guard-jobs-houston-license-training">Security Guard Jobs in Houston</a>, another short-training US entry job.</li>
    <li><a href="/blog/nurse-jobs-london-overseas-registration">Nurse Jobs in London for Overseas Nurses</a>, the UK nursing route.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 28 October 2026 against the pages named in Sources. Pay figures are metro-wide benchmarks, not offers. It does not promise a job or a wage.</p>
HTML;
    }
}
