<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Police Constable Recruitment in Uttar Pradesh: Eligibility and Physical Test"
 *
 * Checked on 27 October 2026. What changed against the brief:
 *
 * 1. The ten identical buttons became links in the text. uppbpb.gov.in did not resolve or connect from our network on
 *    27 October 2026, so neither the homepage nor the notification PDF could be read; the guide says so.
 * 2. Testbook, UPSeva and Times of India are not linked. Their figures, cross-checked against Physics Wallah,
 *    Careers360, Adda247 and IANS reports, are stated in prose as reported: 32,679 posts; the 31 December 2025
 *    notification lowered upper ages to 22 (men) and 25 (women); the 5 January 2026 government order gave a one-time
 *    three-year relaxation for this recruitment; registration closed 30 January 2026; written result declared,
 *    then DV, PST and PET. The brief's 14 October 2026 final-result notice could not be confirmed.
 * 3. PST/PET: 168 cm men (general, OBC), 160 cm ST men, 152 cm women, 147 cm ST women, chest 79/84 cm with 5 cm
 *    expansion, 4.8 km in 25 minutes and 2.4 km in 14 minutes. Sources disagree on SC men (168 or 160 cm), which
 *    the guide flags instead of picking one. The brief's 40 kg female weight is dropped as unconfirmed.
 * 4. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for readers in
 *    Pakistan added.
 *
 * Posters from public/public/blog-355 (images 7 and 8). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to this row.
 */
class PoliceConstableRecruitmentUttarPradeshBlogSeeder extends Seeder
{
    public const SLUG = 'police-constable-recruitment-uttar-pradesh';

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
                'title' => 'Police Constable Recruitment in Uttar Pradesh: Eligibility and Physical Test',
                'excerpt' => 'UP Police constable recruitment explained: the 32,679-post cycle and its one-time three-year age relaxation, Intermediate eligibility, PST heights and chest, the 4.8 km and 2.4 km PET runs, documents, and how to follow the next official notice.',
                'content' => $content,
                'featured_image' => 'blogs/police-constable-recruitment-uttar-pradesh.jpg',
                'tags' => 'up police constable eligibility, uttar pradesh police constable recruitment, up police physical test, up police constable running requirements, up police pst height, up police pet 4.8 km, up police age relaxation, uppbpb constable recruitment',
                'meta_title' => 'UP Police Constable Recruitment: Eligibility and PET',
                'meta_description' => 'UP Police constable recruitment: Intermediate eligibility, age limits and the one-time relaxation, PST heights, the 4.8 km and 2.4 km runs, and documents.',
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
<p><strong>Police constable recruitment in Uttar Pradesh asks candidates to meet the advertised education, age, nationality and category conditions and then qualify through a written test, document verification, the Physical Standard Test (PST) and the Physical Efficiency Test (PET).</strong> Measurement and running are separate stages: meeting the height standard does not mean you have passed the run. The most recent cycle, for 32,679 posts, closed for registration on 30 January 2026 and has since moved through results and physical tests, so it is not open for new applications. This guide explains the rules as reported for that cycle, so you can prepare for the next advertisement from the Uttar Pradesh Police Recruitment and Promotion Board (UPPRPB). We could not reach the board's website on 27 October 2026, so every figure below must be checked against the notification for your cycle.</p>

<h2>Start With the Official Recruitment Board</h2>

<p>The UPPRPB publishes advertisements, amendments, admit cards, test schedules and results on its own website, and authorised application links appear only there. Match the post name and the recruitment year before relying on anything: constable, sub-inspector, jail warder and PAC recruitments have different rules. Before registering, check whether a page is an application notice or a result or schedule for an earlier intake. Source: <a href="https://uppbpb.gov.in/" rel="noopener">Uttar Pradesh Police Recruitment and Promotion Board</a>.</p>

<h2>Check Education and Nationality Conditions</h2>

<p>The constable recruitment asks for Intermediate (Class 12) from a recognised board or an accepted equivalent, completed by the date in the notification. Nationality is set by the recruitment rules: this is an Indian public-service recruitment, not an overseas job or sponsorship route, so education alone does not make a foreign applicant eligible.</p>

<h2>Apply the Correct Age Cutoff and Relaxation</h2>

<p>Age rules for the 32,679-post cycle changed twice, which is why summaries disagree. Reports of the 31 December 2025 notification gave upper limits of 22 for men and 25 for women. On 5 January 2026 the state government approved a one-time relaxation of three years in the upper age limit for all categories in that recruitment. That relaxation was for that cycle only; do not assume it applies to the next one. Read the age limits with the notification's cutoff date and any amendment, and add category relaxations only where the notice says they apply.</p>

<h2>Understand the Physical Standard Test</h2>

<p>The PST measures height, and chest for men. Reports of the constable notification give these minimums:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Candidate group</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Minimum height</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Male chest, unexpanded and expanded</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">General and OBC men</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">168 cm</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">79 cm and 84 cm</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">ST men</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">160 cm</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">77 cm and 82 cm</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">General, OBC and SC women</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">152 cm</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Not measured</td></tr>
<tr><td style="padding:8px;">ST women</td><td style="padding:8px;">147 cm</td><td style="padding:8px;">Not measured</td></tr>
</tbody>
</table>

<p>Chest expansion must be at least 5 cm. Published summaries disagree on SC men, some listing 168 cm and others 160 cm, so check your category in the notification itself. The board's own measurement decides.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/police-constable-recruitment-uttar-pradesh-training.jpg"
         alt="Uttar Pradesh police constables in khaki with scenes of candidates running, training and taking a written test"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Understand the Physical Efficiency Test</h2>

<p>The reported constable PET is a 4.8 km run within 25 minutes for men and a 2.4 km run within 14 minutes for women. It is an endurance test, separate from the measurements, and it is qualifying: it does not add marks but failing it ends the application. Do not borrow timings from the sub-inspector recruitment or another state, and read the reporting instructions, timing method and rules on attempts for your own cycle.</p>

<h2>Prepare for Running Without Last-Minute Guesswork</h2>

<p>Find out the distance and time first, then time yourself on a measured route. Build up gradually with rest days rather than racing every day, and do not try new shoes or a sudden weight change just before the test. A coach can adjust practice to your starting fitness; if a health condition affects training, get medical advice first. This is general guidance, not a training plan.</p>

<h2>Prepare Documents Before Verification</h2>

<p>Use the notification and admit card to prepare identity proof, Class 10 and 12 certificates, date-of-birth proof and category, domicile or relaxation certificates where they apply, in the format and from the issuing authority the board asks for. Your name, parents' names and date of birth must match across records. Choosing a category on the form proves nothing without the prescribed certificate. Keep the submitted form, fee receipt and registration details safe.</p>

<h2>Follow the Application Process When Open</h2>

<p>When a constable window opens, reach the application portal only through the board's website. Register, select the correct recruitment, enter details carefully, upload documents in the stated format and pay only through the authorised payment route. Review before final submission, save the confirmation and payment record, and use the correction window if one is offered. An account registration alone may not complete an application, and an old application does not carry over to a new cycle.</p>

<h2>Track Written Tests, Physical Dates and Results</h2>

<p>Prepare for the written test from the published syllabus, marking scheme, duration and qualifying rules. Keep checking notices after your admit card arrives, because test centres and dates can change, and confirm your reporting details before you travel. A coaching website's post is not your selection letter.</p>

<h2>Avoid Preventable Disqualification</h2>

<p>The common mistakes are the wrong age rules, an unsupported category claim, missing documents and arriving late. A strong run cannot fix an eligibility problem. Never pay anyone who promises selection, never alter documents, keep your login private, and use the board's representation procedure if you believe an assessment was wrong.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>UP Police recruitment is for Indian nationals and is not open to applicants from Pakistan. If you are looking for a constable post at home, read <a href="/blog/police-constable-jobs-punjab-pakistan">Police Constable Jobs in Punjab, Pakistan</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is Class 10 alone sufficient?</h3>
<p>No. The constable recruitment asks for Intermediate (Class 12) or an accepted equivalent.</p>

<h3>Are PST and PET the same?</h3>
<p>No. The PST measures height and chest; the PET is the timed run.</p>

<h3>How far do candidates run?</h3>
<p>4.8 km within 25 minutes for men and 2.4 km within 14 minutes for women, as reported for the constable cycle.</p>

<h3>What is the height requirement?</h3>
<p>168 cm for general and OBC men, 160 cm for ST men, 152 cm for women and 147 cm for ST women, as reported. Check SC men's height in the notification.</p>

<h3>What age limit applies?</h3>
<p>The last cycle gave 22 for men and 25 for women plus a one-time three-year relaxation. The next notification sets its own limits.</p>

<h3>Does passing the run guarantee selection?</h3>
<p>No. The written merit, document verification and other conditions still apply.</p>

<h3>Can I apply to the completed cycle now?</h3>
<p>No. Registration closed on 30 January 2026. Wait for the next advertisement on the board's website.</p>

<h3>Can someone from Pakistan apply?</h3>
<p>No. This is an Indian state police recruitment for Indian nationals.</p>

<h2>Bottom Line</h2>

<p>Confirm the recruitment cycle, eligibility, amendments and documents first, prepare separately for measurement and the run, and follow the board's latest notices throughout selection. This guide cannot promise selection or appointment.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://uppbpb.gov.in/" rel="noopener">Uttar Pradesh Police Recruitment and Promotion Board</a>, which did not load from our network on 27 October 2026</li>
<li>Press and education-site reports of the 32,679-post notification, the 31 December 2025 age limits, the 5 January 2026 one-time relaxation, the 30 January 2026 registration close and the PST and PET standards, used only as reported figures to confirm against the notification</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/police-constable-jobs-punjab-pakistan">Police Constable Jobs in Punjab, Pakistan</a>, the district intake closing 31 October 2026.</li>
    <li><a href="/blog/back-office-executive-jobs-delhi-duties-salary">Back Office Executive Jobs in Delhi</a>, an entry route in north India that needs no physical test.</li>
    <li><a href="/blog/bpo-jobs-pune-what-to-expect-day-one">BPO Jobs in Pune</a>, what the first day of a call-centre job looks like.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 27 October 2026; the board's website and notification could not be opened, so the reported figures must be confirmed with the UPPRPB. It is not an announcement of open applications and does not promise selection.</p>
HTML;
    }
}
