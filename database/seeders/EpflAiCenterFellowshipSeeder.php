<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * EPFL's AI Center and Swiss AI Initiative Postdoctoral Fellowships, third
 * call — a salary contribution of about 50 per cent for up to two years,
 * with two EPFL host laboratories paying the rest.
 *
 * Checked on 28 September 2026 against EPFL's own call page at
 * epfl.ch/research/funding/epfl-programmes/ai-center/ and the EPFL news item
 * announcing the third call.
 *
 * The brief is unusually accurate: the 9 November 2026 17:00 CET deadline,
 * the roughly 50 per cent salary contribution with host laboratories paying
 * the remainder, the CHF 5,000 a year for research and travel, the two-year
 * maximum, the PhD obtained no more than two years before the deadline, the
 * eligibility of final-year doctoral students, the requirement for two EPFL
 * host professors affiliated with the AI Center, all nationalities, no age
 * limit, one application per person and English-only documents all check out.
 * It is also right that this should not be called a fully funded scholarship.
 *
 * What the guide adds rather than corrects:
 *
 * 1. This is not a scholarship and it is filed here only because the owner's
 *    brief arrived in that batch. It is a co-funded postdoctoral post. You
 *    cannot win it and then look for a lab: two EPFL professors must agree to
 *    host you and to pay half your salary before you may submit.
 *
 * 2. The brief lists "approximately 50% of postdoctoral salary" without
 *    saying what the other half depends on. It depends on a laboratory's
 *    budget, which is the real gate on this award.
 *
 * 3. "No age restriction" is true and worth keeping, but the two-year PhD
 *    recency rule is a much harder bar and does the same work.
 *
 * 4. The brief's research-area list is its own invention. EPFL asks for
 *    projects relevant to the AI Center's activities rather than publishing
 *    a list of accepted topics.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class EpflAiCenterFellowshipSeeder extends Seeder
{
    public const SLUG = 'epfl-ai-center-postdoctoral-fellowship';

    public const APPLY_URL = 'https://www.epfl.ch/research/funding/epfl-programmes/ai-center/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'EPFL AI Center Postdoctoral Fellowship 2026',
                'provider' => 'EPFL and the Swiss AI Initiative',
                'country' => 'Switzerland',
                'city' => 'Lausanne',
                'study_level' => 'Postdoctoral',
                'funding_type' => 'Partially Funded',
                'award_value' => 'About 50% of salary, up to 2 years',
                'deadline' => '2026-11-09',
                'deadline_note' => 'Third call closes 17:00 CET',
                'excerpt' => 'EPFL pays about half a postdoc salary for up to two years, plus CHF 5,000 a year for research and travel. Two EPFL AI Center professors must agree to host you and fund the other half before you may apply. Third call closes 9 November 2026.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/epfl-ai-center-postdoctoral-fellowship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'EPFL AI Center Postdoc Fellowship 2026: Funding, Deadline',
                'meta_description' => 'EPFL funds about 50% of a postdoc salary for up to two years, plus CHF 5,000 a year. Two AI Center hosts required. Third call closes 9 November 2026.',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => Carbon::parse('2026-09-28 01:30:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>EPFL AI Center and Swiss AI Initiative Postdoctoral Fellowships</strong> fund early-career AI researchers at <strong>EPFL in Lausanne, Switzerland</strong>. The <strong>third call is open</strong> and closes on <strong>9 November 2026 at 17:00 CET</strong>.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>This is not a scholarship, and it is not fully funded.</strong> EPFL contributes <strong>about 50 per cent</strong> of your salary. The other half is paid by the EPFL laboratories that host you &mdash; which means <strong>two EPFL professors have to agree to take you on, and to find the money, before you are allowed to submit</strong>. Securing those hosts is the application.</p>
</div>

<h2>The Fellowship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>Host</th><td>EPFL (Ecole polytechnique federale de Lausanne), Switzerland</td></tr>
<tr><th>Supported by</th><td>The EPFL AI Center and the Swiss AI Initiative</td></tr>
<tr><th>Level</th><td>Postdoctoral research</td></tr>
<tr><th>Length</th><td>Up to two years</td></tr>
<tr><th>Salary</th><td>EPFL contributes approximately 50% a year, on EPFL salary scales; host laboratories pay the rest</td></tr>
<tr><th>Research budget</th><td>Up to CHF 5,000 per fellowship year</td></tr>
<tr><th>Who can apply</th><td>Any nationality, no age limit, PhD obtained no more than two years before the deadline</td></tr>
<tr><th>Hosts required</th><td>Two EPFL professors affiliated with the EPFL AI Center</td></tr>
<tr><th>Applications each</th><td>One</td></tr>
<tr><th>Language</th><td>English</td></tr>
<tr><th>Deadline</th><td><strong>9 November 2026, 17:00 CET</strong></td></tr>
</tbody>
</table></div>

<h2>What the Fellowship Pays</h2>

<h3>1. About half your salary</h3>
<p>EPFL contributes <strong>approximately 50 per cent of the postdoctoral salary per year</strong>, at EPFL's own salary scales, with EPFL's human resources and social security rules applying. The <strong>remaining half comes from the host laboratories</strong>. That split is the single most important thing to understand about this call: the fellowship is a subsidy that makes you cheaper to hire, not a grant you can carry anywhere.</p>

<h3>2. Up to CHF 5,000 a year</h3>
<p>A maximum of <strong>CHF 5,000 per fellowship year</strong> is available for research costs, open research data costs, open access publication charges, travel and conferences. It has to be justified in your proposed budget and reported on. Anything beyond it is the host professor's problem, not EPFL's.</p>

<h3>3. Infrastructure</h3>
<p>The host laboratories commit to providing what you need to do the work: office and laboratory space, equipment and administrative support.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/epfl-ai-center-postdoctoral-fellowship-campus.jpg" alt="The EPFL campus on the shore of Lake Geneva with the Swiss Alps behind and a Swiss flag, for the AI Center postdoctoral fellowships" width="1200" height="628" loading="lazy">
<figcaption>Up to two years at EPFL in Lausanne. The third call closes 9 November 2026 at 17:00 CET.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<ul>
<li><strong>A PhD in a field relevant to AI</strong> &mdash; computer science, physics, engineering, applied mathematics or another relevant discipline.</li>
<li><strong>Obtained no more than two years before the deadline.</strong> This is the hard bar. There is no age limit, but a PhD awarded in 2023 or earlier does not qualify for this call.</li>
<li><strong>Final-year doctoral students may apply</strong>, provided the PhD is completed before the fellowship begins.</li>
<li><strong>Any nationality.</strong> There are no citizenship restrictions and no age restrictions.</li>
<li><strong>Two EPFL host professors</strong> who are faculty affiliated with the EPFL AI Center, and who support your project.</li>
<li><strong>One application each.</strong> You may not submit more than one.</li>
</ul>

<p>Researchers already at EPFL &mdash; senior doctoral students and junior postdocs &mdash; may apply, though priority goes to external candidates.</p>

<h2>The Part That Decides It: Finding Two Hosts</h2>

<p>Most applicants treat the proposal as the work and the hosts as a formality. It is the other way round.</p>

<ol>
<li><strong>Identify professors, not departments.</strong> You need two, both affiliated with the EPFL AI Center, whose current work your project would genuinely extend.</li>
<li><strong>Write to them early, with something to react to.</strong> A CV and a short abstract of the project, not a request for a meeting about a project you have not written yet.</li>
<li><strong>Expect the money question.</strong> They are being asked to cover half your salary for up to two years. A professor who likes your project but has no budget cannot host you.</li>
<li><strong>Shape the project around the collaboration.</strong> The point of the programme is work that crosses two laboratories. A proposal that only needs one of them is a weaker fit.</li>
</ol>

<p>Start this now rather than in late October. Two professors have to read your abstract, agree between themselves and commit funding before you can submit on 9 November.</p>

<h2>How Applications Are Judged</h2>

<p>An Evaluation Committee of AI researchers assesses proposals, with external reviewers consulted where needed, against three things:</p>

<ul>
<li><strong>Scientific excellence</strong> &mdash; fit with the call's topics, originality, the quality of the approach, and your own record.</li>
<li><strong>Impact</strong> &mdash; what the research would change, its applications, and how much it draws on collaboration with EPFL groups and the AI Center community.</li>
<li><strong>Feasibility</strong> &mdash; whether the project can actually be delivered with the resources, planning and collaboration proposed.</li>
</ul>

<h2>How to Apply, Step by Step</h2>

<ol>
<li><strong>Read the official call guidelines</strong> on EPFL's page before writing anything.</li>
<li><strong>Check your PhD date</strong> against the two-year rule, or confirm you will have graduated before the fellowship starts.</li>
<li><strong>Approach potential hosts</strong> with a CV and a short project abstract.</li>
<li><strong>Write the proposal</strong>: the problem, objectives, originality, method, expected results, relevance to AI, impact, the EPFL collaboration and why it is feasible.</li>
<li><strong>Build the budget</strong>, justifying anything you want from the CHF 5,000.</li>
<li><strong>Submit online in English</strong> before <strong>9 November 2026, 17:00 CET</strong>. One application only.</li>
</ol>

<h2>Claims About This Fellowship to Ignore</h2>

<ul>
<li><strong>"Fully funded postdoc in Switzerland."</strong> EPFL pays about half. Your hosts pay the rest, and if they cannot, there is no post.</li>
<li><strong>"Open to all researchers."</strong> Only if your PhD is at most two years old at the deadline.</li>
<li><strong>"Apply and then find a supervisor."</strong> Two host professors must already support you when you submit.</li>
<li><strong>"No age limit means no limit."</strong> True on age, but the PhD recency rule closes the same door for most mid-career researchers.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Is the EPFL AI Center fellowship fully funded?</h3>
<p>No. EPFL contributes approximately 50 per cent of the postdoctoral salary per year and the host laboratories pay the remainder, plus up to CHF 5,000 a year for research, publication and travel costs.</p>

<h3>When is the deadline?</h3>
<p>The third call closes on 9 November 2026 at 17:00 CET.</p>

<h3>How recent must my PhD be?</h3>
<p>It must have been obtained no more than two years before the submission deadline. Final-year doctoral students may apply if they complete the PhD before the fellowship starts.</p>

<h3>Do I need an EPFL supervisor before applying?</h3>
<p>Yes, two. You need the support of two EPFL host professors who are faculty affiliated with the EPFL AI Center, and they cover the half of your salary EPFL does not.</p>

<h3>How long is the fellowship?</h3>
<p>Up to two years.</p>

<h3>Is there an age limit or a nationality restriction?</h3>
<p>Neither. Citizens of any nationality may apply and no age restrictions apply. The binding limit is the two-year PhD recency rule.</p>

<h3>How many applications can I submit?</h3>
<p>One. Applicants may submit only one application to the programme.</p>

<h3>What language must the application be in?</h3>
<p>English. All documents must be submitted in English.</p>

<h2>People Also Search For</h2>

<h3>EPFL postdoc fellowship deadline</h3>
<p>9 November 2026 at 17:00 CET for the third call.</p>

<h3>Swiss AI Initiative funding</h3>
<p>Supports this fellowship programme alongside the EPFL AI Center.</p>

<h3>EPFL AI Center faculty</h3>
<p>The affiliated professors from whom your two hosts must come.</p>

<h3>Postdoc salary Switzerland</h3>
<p>Set by EPFL's own salary scales, with EPFL human resources rules applying.</p>

<h3>AI postdoc fellowships Europe</h3>
<p>Most, like this one, expect a host laboratory to be arranged before you apply.</p>

<h3>EPFL postdoc eligibility PhD two years</h3>
<p>The PhD must be no more than two years old at the submission deadline.</p>

<h3>Fellowship vs scholarship difference</h3>
<p>A fellowship at this level is a funded research post with a salary, not tuition support.</p>

<h3>CHF 5000 research budget EPFL</h3>
<p>The annual ceiling for research, open access, travel and conference costs.</p>

<h2>Contact and Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official call page: EPFL AI Center and Swiss AI Initiative Postdoctoral Fellowships</a></li>
<li><a href="https://ai.epfl.ch/" target="_blank" rel="noopener">EPFL AI Center</a></li>
</ul>

<h2>Other Awards to Compare</h2>
<ul>
<li><a href="/scholarships/australian-national-university-rtp-scholarship">ANU RTP Scholarship</a> &mdash; a research award that does pay a full stipend, at PhD rather than postdoc level.</li>
<li><a href="/scholarships/university-of-sydney-rtp-international-scholarship">Sydney RTP International</a> &mdash; the Australian equivalent for research students.</li>
<li><a href="/scholarships/yale-university-scholarship">Yale University Scholarship</a> &mdash; need-based undergraduate aid, and Yale's separate PhD funding.</li>
<li><a href="/scholarships/lester-b-pearson-international-scholarship">Lester B. Pearson International Scholarship</a> &mdash; the undergraduate end of the ladder, where your school applies for you.</li>
</ul>

<p><em>JobGader is not part of EPFL, the EPFL AI Center or the Swiss AI Initiative. This guide was checked against EPFL's official call page on 28 September 2026. Funding, eligibility and dates change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
