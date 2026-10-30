<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Police Constable Jobs in Punjab, Pakistan: Eligibility and Application Process"
 *
 * Checked on 27 October 2026. What changed against the brief:
 *
 * 1. The ten identical buttons became links in the text. punjabpolice.gov.pk showed "Website Under Maintenance" and
 *    www.punjabpolice.gov.pk did not answer, so the official jobs index and node/28033 could not be read; the guide
 *    says so and links the Punjab Police home page and the Punjab Government Job Portal (jobs.punjab.gov.pk, reachable).
 * 2. The secondary site the brief cited (punjabjobspk.com) is not linked; its figures are named in prose as a press
 *    summary to confirm: 718 posts Rahim Yar Khan, 300 Rajanpur, 150 Tehsil Rojhan, one driver constable each;
 *    ages 18 to 22 settled, 18 to 25 de-excluded tribal areas, 18 to 30 Rojhan, as on 31 October 2026; Matric with
 *    50% (33% Rojhan); heights 5'7"/5'2" settled, 5'5"/5'2" tribal, 5'5"/5'1" Rojhan; chest 33"/34.5" or 31"/32.5";
 *    Rs. 300 form fee; in-person submission at the District Police Lines; 12 to 31 October 2026.
 * 3. A news report of 4 October 2026 (The Pakistan Connect) confirms the posts, the two-district domicile rule,
 *    12 to 31 October and submission at Police Lines; it is not linked.
 * 4. The separate province-wide Punjab, India drive (3,298 posts, March to July 2026) is flagged as a different force.
 * 5. Four FAQs rewritten and expanded to eight; eight People Also Search For entries added.
 *
 * Posters from public/public/blog-355 (images 4 and 6). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to this row.
 */
class PoliceConstableJobsPunjabPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'police-constable-jobs-punjab-pakistan';

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
                'title' => 'Police Constable Jobs in Punjab, Pakistan: Eligibility and Application Process',
                'excerpt' => 'How Punjab Police constable recruitment works: the Rahim Yar Khan and Rajanpur campaign closing 31 October 2026, reported age, Matric, height and chest rules, the Rs. 300 form and Police Lines submission, and how to confirm each detail.',
                'content' => $content,
                'featured_image' => 'blogs/police-constable-jobs-punjab-pakistan.jpg',
                'tags' => 'police constable jobs punjab pakistan, punjab police constable eligibility, punjab police application process, lady constable jobs punjab, punjab police jobs rahim yar khan, punjab police jobs rajanpur, punjab police height requirement, driver constable jobs punjab',
                'meta_title' => 'Police Constable Jobs in Punjab, Pakistan: Eligibility',
                'meta_description' => 'Punjab Police constable jobs: the Rahim Yar Khan and Rajanpur intake closing 31 October 2026, reported age, Matric and height rules, form fee and how to apply.',
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
<p><strong>To apply for a police constable job in Punjab, Pakistan, find the district advertisement that covers your domicile, check its age, education and physical rules for your area, then submit the prescribed form in person at the stated Police Lines before the closing date.</strong> Rules differ between constables, lady constables, driver constables and specialist units, and between settled and tribal areas of the same district, so no single notice applies across Punjab. The current intake is for Rahim Yar Khan and Rajanpur residents and closes on 31 October 2026. On 27 October 2026 the Punjab Police website showed a maintenance page, so the figures below come from press reports of the advertisement and must be confirmed at your District Police Lines before you pay or submit anything.</p>

<h2>Find the Correct Pakistani Recruitment Notice</h2>

<p>Punjab Police publishes its recruitment notices on its own website and through district offices. Check the district, the post, the advertisement reference and the closing date before anything else. Search results mix Punjab Police Pakistan with Punjab Police in India, a different force with its own board: India's statewide drive for 3,298 constables ran from March to July 2026 and has nothing to do with Pakistani candidates. On 27 October 2026 the Pakistani site answered with "Website Under Maintenance", so if it is still down, ask the Police Lines of your district for the advertisement and form, or check the Punjab Government Job Portal, which carries provincial department vacancies. Sources: <a href="https://punjabpolice.gov.pk/" rel="noopener">Punjab Police</a> and <a href="https://jobs.punjab.gov.pk/" rel="noopener">Punjab Job Portal</a>.</p>

<h2>The Rahim Yar Khan and Rajanpur Intake, October 2026</h2>

<p>News reports of 4 October 2026 said Punjab Police had advertised posts of constable, lady constable and driver constable for residents of Rahim Yar Khan and Rajanpur, with forms downloadable from the Punjab Police website and accepted at the relevant Police Lines from 12 to 31 October 2026. A recruitment summary published by a Pakistani jobs site gave the posts as 718 in Rahim Yar Khan, 300 in Rajanpur including the de-excluded tribal areas, 150 in Tehsil Rojhan and one driver constable in each district, all at BPS-07. Treat those numbers as a guide to the size of the intake, not as figures from the advertisement itself, which we could not open.</p>

<h2>Check District and Domicile Eligibility</h2>

<p>Start with geography. This intake is reported as open only to candidates whose domicile and CNIC are from Rahim Yar Khan or Rajanpur, and the tribal areas and Tehsil Rojhan carry their own conditions. Living near the district is not enough; the domicile decides. If your domicile, CNIC and certificates show different addresses, or a correction is pending, ask the recruitment office what evidence it accepts before the closing date, not after.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/police-constable-jobs-punjab-pakistan-guide.jpg"
         alt="Punjab Police constable and lady constable in front of Lahore landmarks, beside a list of eligibility, application, physical test, selection and preparation steps"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Verify Age and Education Together</h2>

<p>The same summary reports these limits, calculated as on 31 October 2026: ages 18 to 22 for settled areas, 18 to 25 for the de-excluded tribal areas and 18 to 30 for Tehsil Rojhan, with driver constables at 21 to 30. It reports Matric from a recognised board with at least 50% marks for settled areas and 33% for Tehsil Rojhan, and says no relaxation applies. Calculate your age on the advertisement's cutoff date, not today, and check that your result card shows the required percentage. If your qualification or category is unclear, get an answer from the office before you submit a form that may be rejected.</p>

<h2>Understand Physical Standards Before Training</h2>

<p>The reported standards depend on the area. Settled areas: men 5 feet 7 inches and women 5 feet 2 inches, male chest 33 inches unexpanded and 34.5 expanded. De-excluded tribal areas: men 5 feet 5 inches, women 5 feet 2 inches, male chest 31 and 32.5 inches. Tehsil Rojhan: men 5 feet 5 inches, women 5 feet 1 inch, male chest 31 and 32.5 inches. Women have no chest measurement. Measure yourself accurately at home to see where you stand, but the recruitment board's own measurement decides, and an old social media graphic for another district is not your standard.</p>

<h2>Treat Driver and Specialist Posts Separately</h2>

<p>Driver constables and specialist roles can require a valid driving licence, experience or a practical test on top of the general rules, and their age band is different. A general constable application does not make you eligible for a driving post. Read the exact post title, check the licence category and validity dates, and do not apply under a category only because its age limit is wider.</p>

<h2>Prepare Your Application Documents</h2>

<p>Build your checklist from the current advertisement and form. Expect to provide your CNIC, domicile, Matric and any higher certificates with marks sheets, photographs and category evidence, with attested copies where asked and originals at inspection. Fill in names, dates and contact details exactly as they appear on your CNIC. Never invent missing information or sign a declaration you have not read, and keep a copy of everything you submit. If you are already in government service, check whether you need a no-objection certificate.</p>

<h2>Follow the Correct Submission Method</h2>

<p>Press reports describe a downloadable form, in-person submission at your own district's Police Lines and a Rs. 300 form fee, with no online application. Confirm the fee and how it is paid at the office or on the advertisement before paying. Downloading a form is not the same as applying: submit it at the stated place, within office hours and before 31 October 2026, and keep the receipt. Never transfer recruitment money to a personal account or a mobile wallet because someone sends a police logo or promises help.</p>

<h2>Prepare for Tests Using the Notice</h2>

<p>Check the order of document scrutiny, physical measurement, running test, written test and interview in the campaign instructions rather than an assumed timetable. For the run, learn the exact distance and time before planning practice, then build up gradually. For the written test, use the advertised syllabus, language and format. No one can promise you repeated questions or a guaranteed pass.</p>

<h2>Attend Assessments With Complete Records</h2>

<p>Confirm the venue, reporting time and documents for every stage. In the interview, answer honestly and explain why you want police service through practical duties: discipline, teamwork, dealing respectfully with the public. Passing one stage does not guarantee appointment; follow the official notices and results yourself rather than relying on a message from another candidate.</p>

<h2>Avoid Common Application Mistakes</h2>

<p>The avoidable errors are the wrong district, an outdated form, a missing signature, details that do not match your CNIC and a late submission. Keep copies of the notice and your receipt, and report anyone demanding money for selection to the district police through a verified number. Read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake job offer</a> for the warning signs that apply to government posts too.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is Matric enough for every constable post?</h3>
<p>Matric is the reported minimum for this intake, with 50% marks in settled areas and 33% in Tehsil Rojhan. Other posts and campaigns can ask for more, so read the advertisement for your category.</p>

<h3>Can candidates apply from any Punjab district?</h3>
<p>Not for this intake. It is reported as open only to candidates domiciled in Rahim Yar Khan or Rajanpur.</p>

<h3>Is the application online?</h3>
<p>No. Reports say the form is downloaded and submitted in person at the district Police Lines.</p>

<h3>What is the last date?</h3>
<p>31 October 2026, according to the press reports. Confirm it at your Police Lines.</p>

<h3>How much is the form fee?</h3>
<p>Rs. 300 is reported. Pay only through the method stated on the advertisement, never to an individual.</p>

<h3>What height do lady constables need?</h3>
<p>5 feet 2 inches in settled and tribal areas and 5 feet 1 inch in Tehsil Rojhan, as reported. Women have no chest measurement.</p>

<h3>Is there age relaxation?</h3>
<p>The summary we checked says there is no relaxation in age, height, chest or education for this intake. Ask the office if you believe a government rule applies to you.</p>

<h3>Are the reported standards final?</h3>
<p>No. They come from press summaries; the advertisement and the board's measurements decide.</p>

<h2>Bottom Line</h2>

<p>Check your district, category, education, age and physical eligibility first, collect the official form, submit it at your Police Lines before 31 October 2026 and confirm every figure against the advertisement. This guide cannot promise selection or appointment.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://punjabpolice.gov.pk/" rel="noopener">Punjab Police</a>, showing a maintenance page when checked on 27 October 2026</li>
<li><a href="https://jobs.punjab.gov.pk/" rel="noopener">Government of the Punjab: Punjab Job Portal</a></li>
<li>Press reports of 4 October 2026 and a recruitment summary published by a Pakistani jobs site, used only for the reported posts, age, education, physical standards, fee and dates, which need confirming against the advertisement</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/police-constable-recruitment-uttar-pradesh">Police Constable Recruitment in Uttar Pradesh</a>, how another constable selection splits measurement and running tests.</li>
    <li><a href="/blog/call-centre-jobs-karachi-sindh-freshers">Call Centre Jobs in Karachi for Freshers</a>, another entry route open with Matric or Intermediate.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan (2026)</a>, if you are weighing work overseas.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 27 October 2026; the official advertisement could not be opened, so the reported figures must be confirmed with Punjab Police. It does not promise selection, and dates and rules can change.</p>
HTML;
    }
}
