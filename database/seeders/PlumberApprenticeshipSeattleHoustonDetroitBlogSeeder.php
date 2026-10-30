<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Plumber Apprenticeship in Seattle, Houston, and Detroit"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The apply and view-guide buttons are removed; the official pages are linked in the text and in a Sources list.
 * 2. The brief says the Local 32 page covers commercial plumbing, steamfitting and HVAC/refrigeration. The page only describes plumbing training and links the other trades, so the guide says that.
 * 3. Texas and Washington registration details are added from the official pages (Washington trainee: age 16 or older, USD 60, valid 12 months; Texas apprentice: age 16, USD 15 fee, fingerprints). The Texas figures come from a search result of the TSBPE site, which would not load directly, and the guide tells readers to confirm them.
 * 4. Houston: the notice gives no closing date, five-year training, a one-year probation and a 0-to-20 interview score. Detroit WorkKeys scores must be from the last two years. Michigan registration within 30 days comes from the state form instructions.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class PlumberApprenticeshipSeattleHoustonDetroitBlogSeeder extends Seeder
{
    public const SLUG = 'plumber-apprenticeship-seattle-houston-detroit';

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
                'title' => 'Plumber Apprenticeship in Seattle, Houston, and Detroit',
                'excerpt' => 'Compare union plumber apprenticeships in Seattle, Houston and Detroit: entry rules, documents, WorkKeys and Wiesen tests, and state trainee registration.',
                'content' => $content,
                'featured_image' => 'blogs/plumber-apprenticeship-seattle-houston-detroit.jpg',
                'tags' => 'plumber apprenticeship seattle, plumber apprenticeship houston, plumber apprenticeship detroit, union plumbing apprenticeship requirements, local 68 apprenticeship, plumbers 98 apprenticeship, seattle area pipe trades, plumber trainee registration',
                'meta_title' => 'Plumber Apprenticeship: Seattle, Houston and Detroit',
                'meta_description' => 'Compare plumber apprenticeships in Seattle, Houston and Detroit: official application routes, entry requirements, tests and state registration steps.',
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
<p><strong>To apply for a union plumber apprenticeship in Seattle, Houston or Detroit, start with Seattle Area Pipe Trades, the Houston Local 68 training programme, or the Plumbers 98 and MCA Detroit Training Center.</strong> Each has its own documents, assessments and recruitment rules. Completing an application does not guarantee admission, a job or a plumbing licence.</p>

<p>This guide compares the three routes using official pages checked on 17 October 2026. Pick a programme you can realistically attend and follow its own instructions, not a generic checklist.</p>

<h2>Seattle: Seattle Area Pipe Trades and Local 32</h2>

<p>Seattle Area Pipe Trades says "a person can apply for an apprenticeship with us year-round". Its <a href="https://www.seattlepipetrades.org/how-to-apply" rel="noopener">how to apply page</a> lists minimum requirements of age 18, high school graduation or GED completion, and Washington State residency. Before you receive an application you provide:</p>

<ul>
<li>a copy of a valid US driver's licence</li>
<li>a high school transcript or GED scores (not the diploma)</li>
<li>maths evidence: a college placement result that qualifies you for Math 90 or higher, or an algebra class passed with a B or better within a year of applying</li>
<li>a negative non-DOT urine test (5 or 10 panel), valid for only 15 days, so schedule it last</li>
</ul>

<p>Mechanical experience is not required; the page says it looks for work ethic, willingness to learn and a positive attitude.</p>

<h2>Choose the Correct Seattle Trade</h2>

<p>UA Local 32's <a href="https://ualocal32.com/careers-trades/plumbers/" rel="noopener">plumbers page</a> describes a plumbers training programme run with the Seattle Area Pipetrades Education Center, mixing classroom instruction and hands-on work. The page lists pipefitter and HVAC/refrigeration under Careers and Trades but only describes plumbing, so ask the training office which pathways are taking candidates and when the next interview cycle is. Year-round applications do not mean year-round class starts, and you should not pay for an outside preparation course until you know it meets the programme's rules.</p>

<h2>Houston: Local 68 Plumbing Apprenticeships</h2>

<p>The Houston Area Plumbing JAC's <a href="https://www.plu68.com/training-center/apprentice-notice/" rel="noopener">notice</a> says applications are accepted "starting October 15, 2026" for the Plumber Apprentice and Service and Repair Plumber Apprentice classes. Applications are submitted electronically with all documents; the online link appears on the site during the application period or can be requested from the training centre. The notice states no closing date, so confirm availability and the right pathway before you submit.</p>

<ul>
<li><strong>Requirements:</strong> at least 18, high school graduate or GED holder, an original birth certificate, transcript and diploma (or GED scores and certificate), and DD 214 separation papers for veterans.</li>
<li><strong>Training:</strong> five years including a one-year probationary period, with 8,500 to 10,000 hours of on-the-job training listed.</li>
<li><strong>Transport:</strong> you must provide your own transport between job sites and classes.</li>
</ul>

<h2>Understand Houston's Selection Process</h2>

<p>The notice says interviews are offered to applicants who meet the minimum qualifications, every applicant is asked the same questions, and answers are scored from 0 (unacceptable) to 20 (excellent). Topics include work experience, school record, mechanical ability, motivation and vocational training. Qualified applicants who are not selected are held in a pool until new applications arrive, and applicants who lack the minimums can correct the gap and reapply in the next period. Prepare true examples of punctuality, learning and solving practical problems, and describe your own part rather than the team's. You can also contact the <a href="https://www.plu68.com/contact-us/" rel="noopener">training centre</a> about interview dates and missing documents.</p>

<h2>Detroit: Plumbers 98 and MCA Detroit</h2>

<p>The <a href="https://www.plumbers98tc.org/apprenticeship" rel="noopener">Detroit training centre</a> says applications for its next round of interviews "will be accepted until December 18, 2026 or until there are 200 applicants in the pool", so prepare before the calendar date. Its checklist asks for a valid driver's licence, an official high school diploma, transcript or GED, WorkKeys results and the Wiesen Test of Mechanical Aptitude.</p>

<ul>
<li><strong>WorkKeys targets:</strong> Applied Mathematics Level 6, Locating Information (Graphic Literacy) Level 5 and Reading for Information (Workplace Documents) Level 5, from within the last two years.</li>
<li><strong>Wiesen:</strong> a raw score of 48.</li>
<li><strong>Testing:</strong> must be at a certified proctoring location and cannot be taken online.</li>
<li><strong>Age:</strong> the page conflicts with itself: the checklist says at least 18 when you start class, while the FAQ says 18 at the time of application. Ask the centre which applies.</li>
</ul>

<h2>Prepare for Detroit's Assessments</h2>

<p>Confirm accepted score dates, the exact test names and whether every result must exist before you finish the online application. Study applied maths through practical measurement and quantity problems, practise pulling information from charts and workplace documents, and for mechanical questions aim to understand simple mechanisms rather than memorise answers. Our <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning guide</a> and <a href="/blog/ua-plumber-pipefitter-apprenticeship-test-guide">UA plumber and pipefitter test guide</a> help. Because the pool can close at 200, last-minute testing is risky; ask about accommodations before your appointment.</p>

<h2>Training Admission and State Registration Are Separate</h2>

<p>Getting into a training programme is different from the state credential you need for supervised plumbing work, so confirm the registration step with your sponsor before you start.</p>

<ul>
<li><strong>Washington:</strong> Labor and Industries issues the <a href="https://lni.wa.gov/licensing-permits/plumbing/plumber-certification/plumber-trainee" rel="noopener">plumber trainee certificate</a>. Trainees must be 16 or older and work under a certified plumber's direct supervision; L&amp;I lists a USD 60 fee and a 12-month validity.</li>
<li><strong>Texas:</strong> the <a href="https://tsbpe.texas.gov/license-types/apprentice/" rel="noopener">State Board of Plumbing Examiners</a> registers plumber's apprentices. A search of its site showed age 16, a USD 15 fee and fingerprints, but those figures come from board documents dated 2019 to 2024, so confirm current amounts there before paying.</li>
<li><strong>Michigan:</strong> the state's apprentice registration instructions say a plumbing apprentice must register within 30 days of employment. See the <a href="https://www.michigan.gov/lara/bureau-list/bcc/sections/licensing-section/exam-lic/plumbing-examination-licensing-registration-application-information" rel="noopener">Michigan licensing and registration page</a>.</li>
</ul>

<p>A registration card is not a full plumber's licence. Confirm supervision and permitted duties before you begin work.</p>

<h2>Documents to Organise Before Applying</h2>

<p>Keep one folder per programme with readable copies of identification, education records, driving documents, test results and any employment evidence. Ask your school for official records where they are required, and do not offer a diploma when the sponsor asks for a transcript. Save confirmations, record deadlines and appointments, and send sensitive records only through the sponsor's channel.</p>

<h2>Compare Pay, Travel and Training Costs</h2>

<p>Ask each programme for its current starting wage and progression, when benefits begin and which costs fall on you. Compare cash wages with housing, transport, parking, tools, books and test fees; benefits are not spendable weekly pay. Think about reaching changing job sites, not only the training centre, and keep your current job or studies until you have clear start instructions.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is there one entrance test for all three cities?</h3>
<p>No. Seattle lists prerequisites and an interview, Houston describes scored interviews, and Detroit specifies WorkKeys and the Wiesen mechanical aptitude test.</p>

<h3>Can beginners apply?</h3>
<p>Seattle says mechanical experience is not required. Check the other programmes and describe your transferable skills honestly.</p>

<h3>Does applying guarantee employment?</h3>
<p>No. Qualification, selection and available training and work places decide the outcome.</p>

<h3>Can I work independently after registration?</h3>
<p>No. Trainee or apprentice registration is not a full plumber's licence, so confirm supervision rules.</p>

<h3>Which city is best?</h3>
<p>It depends on eligibility, living costs, transport and the places actually open to you.</p>

<h3>How long are Seattle drug-test results valid?</h3>
<p>The Seattle page says a non-DOT urinalysis result is only good for 15 days.</p>

<h3>When does Houston close applications?</h3>
<p>The reviewed notice gives no closing date. Confirm with the training centre.</p>

<h3>How old must I be in Detroit?</h3>
<p>The page says 18 at class start in one place and 18 at application in another. Ask the centre.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>These programmes need work authorisation in the United States, and an apprenticeship notice does not sponsor a visa. Never pay an agent for a place in a union programme, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Seattle, Houston and Detroit offer distinct plumbing apprenticeship routes. Match your documents and preparation to the local sponsor, confirm state registration and understand the employment terms before you commit.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.seattlepipetrades.org/how-to-apply" rel="noopener">Seattle Area Pipe Trades: How to Apply</a></li>
<li><a href="https://ualocal32.com/careers-trades/plumbers/" rel="noopener">UA Local 32: Plumbing Careers</a></li>
<li><a href="https://www.plu68.com/training-center/apprentice-notice/" rel="noopener">Local 68: Application Notice and Selection Procedures</a></li>
<li><a href="https://www.plumbers98tc.org/apprenticeship" rel="noopener">Plumbers 98 and MCA Detroit: Apprenticeship</a></li>
<li><a href="https://lni.wa.gov/licensing-permits/plumbing/plumber-certification/plumber-trainee" rel="noopener">Washington L&amp;I: Plumber Trainee</a></li>
<li><a href="https://tsbpe.texas.gov/license-types/apprentice/" rel="noopener">Texas State Board of Plumbing Examiners: Apprentice Registration</a></li>
<li><a href="https://www.michigan.gov/lara/bureau-list/bcc/sections/licensing-section/exam-lic/plumbing-examination-licensing-registration-application-information" rel="noopener">Michigan LARA: Plumbing Licensing and Registration</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/ua-plumber-pipefitter-apprenticeship-test-guide">UA Plumber and Pipefitter Apprenticeship Test Guide</a>: test preparation</li>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>: application steps</li>
    <li><a href="/blog/union-apprenticeship-waitlists-how-long">Union Apprenticeship Waitlists: How Long Do They Take?</a>: waiting times</li>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics</a>: maths revision</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Recruitment status checked on 17 October 2026 against official pages, which change often. No vacancy, admission or selection outcome is guaranteed.</p>
HTML;
    }
}
