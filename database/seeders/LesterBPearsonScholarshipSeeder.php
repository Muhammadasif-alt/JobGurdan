<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The University of Toronto's Lester B. Pearson International Scholarship —
 * about 37 awards a year covering tuition, books, incidental fees and full
 * residence support for four years of a first-entry undergraduate degree.
 *
 * Checked on 28 September 2026 against future.utoronto.ca/pearson and
 * /pearson-scholarships, the school participation form at
 * apply.adm.utoronto.ca/register/pearson-participate, and U of T's own
 * statement of what the award covers. Corrections to the brief and posters:
 *
 * 1. The brief and all three posters are headed "2026". The round open now is
 *    for students who begin at U of T in September 2027. 2026 is the year you
 *    apply, not the year you start.
 *
 * 2. Neither the brief nor the posters mention the one rule that decides
 *    whether a reader can enter at all: you cannot apply yourself. Your
 *    school has to nominate you, and a school may nominate only one student
 *    a year. The brief refers vaguely to "nomination requirements" once.
 *
 * 3. The brief gives no deadlines. There are three, and the first is close:
 *    school nomination 9 October 2026, admission application 16 October 2026,
 *    scholarship application and documents 6 November 2026.
 *
 * 4. The first poster promises a "Living Stipend". U of T's wording is
 *    tuition, books, incidental fees and full residence support. Residence
 *    support pays for a room and a meal plan; there is no cash stipend.
 *
 * 5. Two posters give the deadline as "Varies (Check Official Site)". The
 *    dates are published and fixed.
 *
 * 6. The brief omits the bar that disqualifies most late applicants: you must
 *    currently be in your final year of secondary school, or have graduated
 *    no earlier than June 2026, and you must not already have begun
 *    post-secondary study.
 *
 * The brief's "approximately 37 students annually", the four-year duration,
 * the first-entry undergraduate restriction and the benefit list were all
 * correct and are kept.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class LesterBPearsonScholarshipSeeder extends Seeder
{
    public const SLUG = 'lester-b-pearson-international-scholarship';

    public const APPLY_URL = 'https://future.utoronto.ca/pearson/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Lester B. Pearson International Scholarship 2027',
                'provider' => 'University of Toronto',
                'country' => 'Canada',
                'city' => 'Toronto',
                'study_level' => 'Undergraduate',
                'funding_type' => 'Fully Funded',
                'award_value' => 'Tuition, books, fees and residence',
                'deadline' => '2026-10-09',
                'deadline_note' => 'Your school nominates by 9 Oct 2026',
                'excerpt' => 'About 37 awards covering tuition, books, incidental fees and four years of residence at the University of Toronto. You cannot apply yourself: your school nominates one student, by 9 October 2026, for entry in September 2027.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/lester-b-pearson-international-scholarship.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Lester B. Pearson Scholarship: Deadlines and How to Apply',
                'meta_description' => 'U of T names about 37 Pearson Scholars a year. Your school must nominate you by 9 October 2026 for September 2027 entry. Benefits, eligibility and dates.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-28 01:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        $applyUrl = self::APPLY_URL;

        return <<<HTML
<p>The <strong>Lester B. Pearson International Scholarship</strong> is the University of Toronto's flagship award for international undergraduates. About <strong>37 students a year</strong> are named Pearson Scholars, and the award covers <strong>tuition, books, incidental fees and full residence support for four years</strong>.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>You cannot apply for this yourself.</strong> Your secondary school has to nominate you, and <strong>each school may nominate only one student a year</strong>. If your school has not registered to nominate, nothing else on this page can happen. The nomination deadline is <strong>9 October 2026</strong>.</p>
</div>

<div class="scholar-note">
<p><strong>The round open now is for entry in September 2027.</strong> The posters and most write-ups call it the "2026" scholarship because 2026 is the year you apply. You would begin your degree in September 2027.</p>
</div>

<h2>Pearson Scholarship at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>University</th><td>University of Toronto, Ontario, Canada</td></tr>
<tr><th>Awards each year</th><td>Approximately 37</td></tr>
<tr><th>Study level</th><td>First-entry undergraduate programs only</td></tr>
<tr><th>Who can apply</th><td>International students who require a Canadian study permit, nominated by their school</td></tr>
<tr><th>What it covers</th><td>Tuition, books, incidental fees and full residence support</td></tr>
<tr><th>How long</th><td>Four years</td></tr>
<tr><th>Where it can be used</th><td>The University of Toronto only</td></tr>
<tr><th>School nomination</th><td><strong>9 October 2026</strong></td></tr>
<tr><th>U of T admission application</th><td><strong>16 October 2026</strong></td></tr>
<tr><th>Scholarship application and documents</th><td><strong>6 November 2026</strong></td></tr>
<tr><th>Studies begin</th><td>September 2027</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays</h2>

<p>U of T's own wording is that the scholarship covers <strong>tuition, books, incidental fees and full residence support for four years</strong>. Taken apart:</p>

<ul>
<li><strong>Tuition.</strong> International undergraduate tuition at U of T is among the highest in Canada, and this is the largest part of the award by far.</li>
<li><strong>Books and incidental fees.</strong> Course materials and the compulsory ancillary fees every student pays.</li>
<li><strong>Residence support.</strong> A room in university residence and a meal plan, for the full four years.</li>
</ul>

<div class="scholar-note scholar-note-warn">
<p><strong>There is no living stipend.</strong> One of the posters circulating for this award lists "Tuition, Living Stipend &amp; More". U of T does not pay Pearson Scholars a cash allowance. Your housing and meals are covered through residence; personal spending, travel to Canada and the study permit are not.</p>
</div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/lester-b-pearson-international-scholarship-overview.jpg" alt="University College at the University of Toronto under a Canadian flag with the Lester B. Pearson International Scholarship facts" width="1200" height="628" loading="lazy">
<figcaption>About 37 awards a year, for first-entry undergraduate programs, tenable only at the University of Toronto.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<p>U of T sets four conditions, and the second and fourth are where most hopeful applicants fall out:</p>

<ul>
<li>You are an <strong>international student</strong> who will require a Canadian study permit. Canadian citizens and permanent residents are not eligible.</li>
<li>You are <strong>currently in your final year of secondary school</strong> (2026/2027), <strong>or</strong> you graduated <strong>no earlier than June 2026</strong>.</li>
<li>You will <strong>begin your studies at U of T in September 2027</strong>.</li>
<li>You are <strong>not already in post-secondary study</strong>, and you are not starting a post-secondary program in January 2027.</li>
</ul>

<p>The award is for <strong>first-entry undergraduate programs</strong> — the degrees you enter straight from secondary school. It is not for graduate study, and not for second-entry professional programs you apply to after a first degree.</p>

<h2>The Nomination: the Step Nobody Mentions</h2>

<p>This is the part most guides skip, and it is the part that decides everything.</p>

<ol>
<li><strong>Your school must be registered with U of T</strong> to take part. Schools that have nominated before are emailed nomination instructions; a school taking part for the first time completes U of T's school participation form.</li>
<li><strong>Your school chooses one student.</strong> Not a shortlist — one. Whatever your results, if your school nominates someone else you are out of this award for the year.</li>
<li><strong>The nomination closes on 9 October 2026.</strong> U of T's school registration form gives a cut-off of noon Eastern time on that date, so treat 9 October as a morning deadline, not an evening one.</li>
</ol>

<p>If you want to be considered, the useful thing to do this week is not polishing an essay. It is asking your school counsellor, principal or head of year whether the school is registered and who it intends to nominate.</p>

<h2>Deadlines</h2>
<div class="scholar-table"><table>
<thead><tr><th>Step</th><th>Who does it</th><th>Deadline</th></tr></thead>
<tbody>
<tr><td>School nomination</td><td>Your school</td><td><strong>9 October 2026</strong></td></tr>
<tr><td>U of T admission application</td><td>You</td><td><strong>16 October 2026</strong></td></tr>
<tr><td>Pearson scholarship application and all documents</td><td>You</td><td><strong>6 November 2026</strong></td></tr>
<tr><td>Studies begin</td><td>&mdash;</td><td>September 2027</td></tr>
</tbody>
</table></div>

<p>The three dates run in that order for a reason: the nomination unlocks the scholarship application, and the scholarship application is only considered if you have also applied for admission. Miss the first and the other two never open.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/lester-b-pearson-international-scholarship-student.jpg" alt="An international student holding books on the University of Toronto campus with the Pearson Scholarship award details" width="1200" height="628" loading="lazy">
<figcaption>The round open now is for September 2027 entry. Nominations close 9 October 2026.</figcaption>
</figure>

<h2>How to Apply, Step by Step</h2>

<ol>
<li><strong>Ask your school first.</strong> Is it registered with U of T, and who will it nominate? Everything else depends on this.</li>
<li><strong>Be worth nominating.</strong> Schools choose on academic results and on what U of T asks them to look for: creativity, initiative, and an impact on the life of the school.</li>
<li><strong>Once nominated, apply for admission</strong> to a first-entry undergraduate program at U of T by <strong>16 October 2026</strong>. You choose the program; the scholarship follows you into it.</li>
<li><strong>Complete the Pearson scholarship application</strong> and upload every document by <strong>6 November 2026</strong>. An incomplete file at the deadline is not assessed.</li>
<li><strong>Keep your grades up.</strong> Offers are conditional on the final results your school predicted.</li>
</ol>

<h2>What It Does Not Cover</h2>

<ul>
<li><strong>Your flight to Canada</strong> and the cost of getting there</li>
<li><strong>The study permit fee</strong> and biometrics</li>
<li><strong>Personal spending money</strong> — there is no stipend</li>
<li><strong>A fifth year</strong>, or a program change that extends your degree beyond four years</li>
<li><strong>Graduate study</strong> afterwards</li>
</ul>

<h2>Claims About This Scholarship to Ignore</h2>

<ul>
<li><strong>"Apply now for 2026."</strong> The open round is for entry in September 2027.</li>
<li><strong>"Includes a living stipend."</strong> It includes residence, which is a room and a meal plan, not cash.</li>
<li><strong>"Deadline varies, check the official site."</strong> The three dates are fixed and published.</li>
<li><strong>"Apply directly to U of T for the Pearson."</strong> There is no direct route. No nomination, no scholarship.</li>
<li><strong>"Open to all international students."</strong> Not if you have already started post-secondary study anywhere.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply for the Pearson Scholarship myself?</h3>
<p>No. Your secondary school must nominate you, and each school may nominate only one student a year. Ask your school whether it is registered with U of T and who it plans to nominate.</p>

<h3>What is the Pearson Scholarship deadline?</h3>
<p>Three dates: your school nominates by 9 October 2026, you apply for admission to U of T by 16 October 2026, and you complete the scholarship application with all documents by 6 November 2026.</p>

<h3>Is the Pearson Scholarship for 2026 or 2027 entry?</h3>
<p>The round open now is for students who begin at U of T in September 2027. It is called the 2026 scholarship because 2026 is the application year.</p>

<h3>What does the Pearson Scholarship cover?</h3>
<p>Tuition, books, incidental fees and full residence support for four years, tenable only at the University of Toronto. There is no cash living stipend.</p>

<h3>How many Pearson Scholarships are awarded?</h3>
<p>Approximately 37 students are named Pearson Scholars each year.</p>

<h3>Who is eligible for the Pearson Scholarship?</h3>
<p>International students requiring a Canadian study permit who are in their final year of secondary school in 2026/2027 or graduated no earlier than June 2026, who will start at U of T in September 2027, and who have not already begun post-secondary study.</p>

<h3>Which programs can I use the Pearson Scholarship for?</h3>
<p>First-entry undergraduate programs at the University of Toronto — the degrees you enter straight from secondary school. It cannot be transferred to another university.</p>

<h3>Does the Pearson Scholarship pay for my flight or study permit?</h3>
<p>No. Travel to Canada, the study permit fee and personal expenses are yours. The award covers tuition, books, incidental fees and residence.</p>

<h2>People Also Search For</h2>

<h3>Lester B. Pearson scholarship nomination deadline</h3>
<p>9 October 2026, with U of T's school form giving a noon Eastern cut-off.</p>

<h3>University of Toronto scholarship for international students</h3>
<p>The Pearson is the flagship; U of T also awards admission scholarships that need no nomination.</p>

<h3>Pearson scholarship eligibility</h3>
<p>Final-year secondary students, or 2026 graduates, who require a study permit and have not started post-secondary study.</p>

<h3>How many Pearson scholars are selected</h3>
<p>About 37 a year, from nominations worldwide.</p>

<h3>Pearson scholarship benefits</h3>
<p>Tuition, books, incidental fees and residence for four years — no cash stipend.</p>

<h3>Fully funded undergraduate scholarships in Canada</h3>
<p>Rare at this level; most Canadian funding for international students is partial and entry-based.</p>

<h3>U of T first-entry undergraduate programs</h3>
<p>The degrees entered directly from secondary school, which are the only ones the Pearson covers.</p>

<h3>Pearson scholarship school nomination form</h3>
<p>Completed by the school, not the student, through U of T's participation form.</p>

<h2>Contact and Official Links</h2>
<ul>
<li><a href="{$applyUrl}" target="_blank" rel="noopener">Official Lester B. Pearson International Scholarship page</a></li>
<li><a href="https://future.utoronto.ca/pearson-scholarships" target="_blank" rel="noopener">Pearson scholarships: dates and requirements</a></li>
<li><a href="https://apply.adm.utoronto.ca/register/pearson-participate" target="_blank" rel="noopener">School participation form</a> &mdash; for your school, not for you</li>
<li><a href="https://future.utoronto.ca/apply/" target="_blank" rel="noopener">Applying to the University of Toronto</a></li>
</ul>

<h2>Other Scholarships to Compare</h2>
<ul>
<li><a href="/scholarships/yale-university-scholarship">Yale University Scholarship</a> &mdash; need-based aid for international undergraduates, with no nomination step.</li>
<li><a href="/scholarships/university-of-leeds-commonwealth-masters-scholarship">Leeds Commonwealth Master's Scholarship</a> &mdash; a fully funded UK route, at master's level.</li>
<li><a href="/scholarships/kings-college-london-chevening-scholarship">King's College London Chevening Scholarship</a> &mdash; another award with a separate process running alongside admission.</li>
<li><a href="/scholarships/epfl-ai-center-postdoctoral-fellowship">EPFL AI Center Postdoctoral Fellowship</a> &mdash; the opposite end of the ladder, and a reminder that not everything called a scholarship is one.</li>
</ul>

<p><em>JobGader is not part of the University of Toronto. This guide was checked against U of T's official pages on 28 September 2026. Dates, benefits and eligibility change, so confirm them on the official page before you apply.</em></p>
HTML;
    }
}
