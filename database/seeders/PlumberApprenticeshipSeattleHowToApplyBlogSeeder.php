<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Plumber Apprenticeship in Seattle, Washington: How to Apply"
 *
 * Checked on 3 November 2026. What changed against the brief:
 *
 * 1. The Indeed button is dropped.
 * 2. SAPT read: applications received 24 August 2026 to 19 February 2027 are contacted to interview in March 2027,
 *    online only, four prerequisites, drug test results within 15 days, residential plumbing shown for reference only.
 *    seattlepipetrades.org confirms age 18, diploma or GED, Washington residence, physical ability, Renton, and that an
 *    interview slot cannot be changed. Hours read on the plumber page.
 * 3. L&I read: trainee age 16, direct supervision, 8 hours of continuing education a year, $60 certificate and renewal,
 *    notarized affidavits per employer within 30 days of renewal; PL01 needs 8,000 hours with 4,000 commercial.
 * 4. CITC showed a security challenge and Seattle Public Utilities would not load, so both are left out.
 * 5. The older Seattle, Houston and Detroit guide covers the same programme in brief; this one links to it.
 *
 * Posters are the owner's (blog-357, 2 Nov 10.38 to 10.42 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class PlumberApprenticeshipSeattleHowToApplyBlogSeeder extends Seeder
{
    public const SLUG = 'plumber-apprenticeship-seattle-how-to-apply';

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
                'title' => 'Plumber Apprenticeship in Seattle, Washington: How to Apply',
                'excerpt' => 'How to apply to Seattle Area Pipe Trades for the March 2027 interviews: the 19 February 2027 cutoff, the four documents, the algebra or Math 90 rule, and Washington\'s plumber trainee certificate.',
                'content' => $content,
                'featured_image' => 'blogs/plumber-apprenticeship-seattle-how-to-apply.jpg',
                'tags' => 'plumber apprenticeship seattle, seattle area pipe trades application, local 32 plumbing apprenticeship, washington plumber trainee certificate, plumbing apprenticeship washington state, sapt apprenticeship requirements, pl01 journey level plumber, plumber apprentice renton',
                'meta_title' => 'Plumber Apprenticeship in Seattle: How to Apply',
                'meta_description' => 'Seattle plumber apprenticeship: the SAPT cutoff for March 2027 interviews, four required documents, the math rule and the Washington trainee certificate.',
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
<p><strong>To apply for a plumber apprenticeship in Seattle, start with Seattle Area Pipe Trades (SAPT), the programme run under an agreement between UA Local 32 and the Mechanical Contractors Association of Washington.</strong> It takes applications all year, online only, and interviews twice a year. Applications received from 24 August 2026 to <strong>19 February 2027</strong> are contacted to interview in March 2027. Gather your documents first and leave the drug test until last, because its result must be submitted within 15 days. An interview is a step towards selection, not a job offer. We checked SAPT and Washington Labor &amp; Industries on 3 November 2026.</p>

<h2>1. Check Eligibility and Choose the Right Programme</h2>

<p>SAPT asks that you are 18 or older, a high school graduate or GED holder, a Washington resident and physically able to do the work, and its portal asks whether you can legally work in the United States. You do not need plumbing experience; SAPT says it looks for a strong work ethic, willingness to learn and a positive attitude. The training centre is in Renton, so think about getting there as well as to job sites around Seattle.</p>

<p>The two plumbing paths are different lengths:</p>

<ul>
<li><strong>Commercial:</strong> 5 years, 10,000 work hours and at least 1,080 school hours</li>
<li><strong>Residential:</strong> 3 years, 6,000 work hours and at least 432 school hours</li>
</ul>

<p>The current online application does not offer residential plumbing; SAPT lists it for reference only. If residential is your goal, contact the centre about its intake rather than picking another trade just to submit something. Official page: <a href="https://sapt.org/eligibility" rel="noopener">SAPT: How to apply and eligibility</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-apprenticeship-seattle-how-to-apply-2.jpg"
         alt="Apprentice plumber working on a sink trap under supervision of an experienced plumber, with the Space Needle behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>2. Gather the Four Prerequisites in the Right Order</h2>

<ol>
<li><strong>Driver's licence.</strong> A clear copy of a valid licence. HVAC/R applicants also need their full driving record.</li>
<li><strong>Education record.</strong> Your high school transcript or GED scores. A copy of your diploma does not count, so ask your old school for the transcript early.</li>
<li><strong>Math evidence.</strong> Either an algebra class passed with a B or better, in high school or college, within the last 12 months, or math placement results showing you could enrol at Math 90 or higher, from a Washington community or technical college.</li>
<li><strong>Drug test.</strong> Negative results from a non-DOT urine panel (5 or 10), taken anywhere, at your own cost, submitted within 15 days of the test.</li>
</ol>

<p>For example, an A in algebra three years ago does not meet the 12-month rule. You would need a placement result instead, so ask the college how its result will show Math 90 eligibility before you book the test.</p>

<h2>3. Submit, Interview and Understand the Ranking</h2>

<p>Create a portal account, upload the four documents and submit. SAPT does not accept email or in-person applications. When it emails you an interview date and time, you must reply to secure it, and the slot cannot be changed by the applicant, so watch your inbox. If you do not reply, you lose that interview.</p>

<p>Prepare three short examples: following instructions, fixing a mistake and turning up reliably. A real story from a warehouse or kitchen is stronger than claiming experience with tools you have never used. After interviews, applicants are ranked and selected when places with long-term work are available, so a good ranking still does not give you a fixed start date. Do not hand in your notice until you have one.</p>

<h2>4. Get the Washington Plumber Trainee Certificate</h2>

<p>Being accepted by a programme and holding the state trainee certificate are separate. Labor &amp; Industries (L&amp;I) says trainees must:</p>

<ul>
<li>Be 16 or older (SAPT itself takes 18 and over)</li>
<li>Work under the direct supervision of a journey-level or certified plumber</li>
<li>Take 8 hours of approved continuing education each year to renew</li>
<li>Pay $60 for the certificate and $60 for each renewal</li>
<li>Send separate notarized affidavits of hours for each employer or supervising plumber, no later than 30 days after renewal</li>
</ul>

<p>For the journey-level PL01 exam, L&amp;I wants at least four years and 8,000 hours as a supervised trainee, with at least 4,000 hours in commercial or industrial work. Keep your own log of dates, sites and supervisors from day one. It does not replace the affidavit, but it stops gaps you cannot fix years later. Official page: <a href="https://www.lni.wa.gov/licensing-permits/plumbing/plumber-certification/plumber-trainee" rel="noopener">L&amp;I: Plumber trainee</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-apprenticeship-seattle-how-to-apply-3.jpg"
         alt="Smiling apprentice plumber with arms folded in a training workshop, colleagues fitting copper pipe behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>5. Helper Jobs While You Wait</h2>

<p>A plumbing helper job can give useful experience, but it is not automatically an apprenticeship. Ask who sponsors any training, who supervises you and how hours are recorded. Carrying materials or tidying the van may not count towards certification. If you do plumbing work at all, you need the trainee certificate first.</p>

<h2>Questions to Ask Before Accepting a Placement</h2>

<p>Ask where your first assignment starts, the report time and which tools and safety gear the contractor provides. The Renton address does not tell you where you go on Monday, so check both trips against your bus connections. Also ask who signs your hours when you change supervisor or contractor.</p>

<h2>Frequently Asked Questions</h2>

<h3>When is the next Seattle plumber apprenticeship deadline?</h3>
<p>Applications received by 19 February 2027 are contacted for the March 2027 interviews. SAPT accepts applications all year.</p>

<h3>Do I need plumbing experience?</h3>
<p>No. SAPT says applicants without mechanical experience are welcome.</p>

<h3>Can I use an old algebra grade?</h3>
<p>Only if it is a B or better from the last 12 months. Otherwise you need placement results at Math 90 or higher.</p>

<h3>Do I need a general aptitude test?</h3>
<p>Not for this SAPT cycle, which asks for algebra or placement evidence instead. Check before buying a test prep course.</p>

<h3>What does a first-year apprentice earn?</h3>
<p>Ask the programme for the current first-period wage for your track, when raises come and whether school hours are paid. A journey-level rate is not your first paycheck.</p>

<h2>Bottom Line</h2>

<p>Get your transcript and math evidence first, take the drug test last so it is current, and apply online before the February cutoff. Once you start, hold the state trainee certificate and log your supervised hours.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://sapt.org/eligibility" rel="noopener">SAPT: How to apply and eligibility</a></li>
<li><a href="https://www.seattlepipetrades.org/how-to-apply" rel="noopener">Seattle Area Pipe Trades: How to apply</a> and <a href="https://www.seattlepipetrades.org/apprenticeship-programs/plumber" rel="noopener">Plumber programme hours</a></li>
<li>Washington L&amp;I: <a href="https://www.lni.wa.gov/licensing-permits/plumbing/plumber-certification/plumber-trainee" rel="noopener">Plumber trainee</a> and <a href="https://www.lni.wa.gov/licensing-permits/plumbing/plumber-certification/" rel="noopener">Plumber certification</a>, all checked 3 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/plumber-apprenticeship-seattle-houston-detroit">Plumber Apprenticeship in Seattle, Houston and Detroit</a>, three cities compared.</li>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>, the general steps.</li>
    <li><a href="/blog/part-time-jobs-seattle-wa">Part-Time Jobs in Seattle</a>, work while you wait for an intake.</li>
    <li><a href="/blog/bus-driver-jobs-chicago-license-hiring-process">Bus Driver Jobs in Chicago</a>, another paid-training career.</li>
</ul>

HTML;
    }
}
