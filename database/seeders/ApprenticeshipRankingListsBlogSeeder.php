<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How Apprenticeship Ranking Lists Work"
 *
 * Checked on 17 October 2026 against the Oregon BOLI, ETA Silicon Valley,
 * NEIEP and Portland NIETC pages. What changed against the brief:
 *
 * 1. The View Guide and Apply Now buttons are removed; the cited official
 *    pages are linked in the text and in a Sources list.
 * 2. Oregon's page says the pool of eligibles are "not yet apprentices" and
 *    that applicants then move to an out-of-work apprentices list before
 *    going to work. The brief's "may find work or receive a referral" is
 *    reworded to that.
 * 3. NEIEP's rank-validity page and the Portland expiry rules are quoted as
 *    each programme's own rule. Portland's six-month, 1,000-hour and
 *    six-credit re-interview conditions are added from its page.
 * 4. A Pakistan note, eight FAQs and eight People Also Search For entries
 *    are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class ApprenticeshipRankingListsBlogSeeder extends Seeder
{
    public const SLUG = 'how-apprenticeship-ranking-lists-work';

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
                'title' => 'How Apprenticeship Ranking Lists Work',
                'excerpt' => 'Scores, ranks, list expiry and re-interviews explained with NEIEP, Portland and Silicon Valley examples, plus what to do while you wait.',
                'content' => $content,
                'featured_image' => 'blogs/how-apprenticeship-ranking-lists-work.jpg',
                'tags' => 'how apprenticeship ranking lists work, apprenticeship ranked list, apprenticeship interview score, apprenticeship waiting list, neiep hiring list, apprenticeship re-interview, pool of eligibles, apprenticeship rank expiry',
                'meta_title' => 'How Apprenticeship Ranking Lists Work',
                'meta_description' => 'How apprenticeship ranking lists work: interview scores, rank changes, two-year expiry, re-interviews and what to do while waiting for selection.',
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
<p><strong>An apprenticeship ranking list puts eligible applicants in an order set by a programme's published scoring rules, and your position helps decide when you may get an offer as openings appear.</strong> Being ranked does not guarantee acceptance, a job or a start date, and some lists change when new applicants are assessed even if your own score stays the same.</p>

<p>This guide uses official electrical and elevator programme examples from the United States. Scoring, list duration and improvement options vary, so always check your own sponsor's current procedure.</p>

<h2>What Is an Apprenticeship Ranking List?</h2>

<p>A ranked list is an ordered pool of applicants who have finished the selection stages. Oregon's <a href="https://www.oregon.gov/boli/apprenticeship/pages/become-an-apprentice.aspx" rel="noopener">apprenticeship guidance</a> calls it a "Pool of Eligibles (not yet apprentices)" and says applicants then move to a list of out-of-work apprentices before they can go to work.</p>

<p>"Eligible", "ranked" and "selected" are different stages. Keep your result letter, because it may carry deadlines that decide your next action.</p>

<h2>Score, Rank and Applicant Number Are Different</h2>

<p>Your score measures your performance under the programme's system. Your rank is your position among eligible applicants. An applicant number usually identifies your record and is not your place unless the notice says so. The <a href="https://www.etasv.org/faqs" rel="noopener">Electrical Training Alliance of Silicon Valley FAQ</a> says "the higher your interview score, the higher you are on the Ranking List". A score of 92 does not mean rank 92, and rank 12 does not mean you scored 12 percent.</p>

<h2>How Programs Calculate Rankings</h2>

<p>There is no universal formula. Ask whether the programme ranks on an interview score, combined results or documented qualifications, and whether the entrance test only qualifies you for the next stage or counts towards the final rank.</p>

<p>NEIEP, the elevator programme, says a candidate's interview score is the average of the two interviewers' scores, that 70% is the minimum passing score to receive a rank on the local's hiring list, and that the ranking is based on that interview score (<a href="https://www.neiep.org/iuec-apprenticeship-faq/what-happens-after-my-interview/" rel="noopener">NEIEP FAQ</a>). That is one programme's rule, not a nationwide cutoff.</p>

<h2>Why Your Position Can Change</h2>

<p>The Silicon Valley FAQ says your rank can move up and down as people are placed for employment or as new applicants are interviewed. A simplified example of our own:</p>

<ul>
<li>You start at number 20.</li>
<li>Five applicants ahead of you are selected, so you move to 15.</li>
<li>Eight new applicants then enter ahead of you, so you move to 23.</li>
</ul>

<p>Your score did not change. The example shows movement only and does not reproduce any sponsor's real list.</p>

<h2>How Selection From the List Works</h2>

<p>NEIEP's <a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">recruitment page</a> says companies with open apprentice positions hire off the recruitment list in ranked order, starting at the top. A list therefore does not prove that employers have vacancies right now.</p>

<p>Ask what happens when your position is reached, because an invitation may need extra steps before you start. Do not resign from a job because your rank looks promising until you have clear start arrangements in writing.</p>

<h2>How Long Will You Wait?</h2>

<p>Your rank alone cannot give a reliable waiting time. The <a href="https://nietc.org/applicants/preparing-to-apply/preparing-for-the-aptitude-test-and-the-interview/" rel="noopener">Portland NECA-IBEW Electrical Training Center</a> says it starts a new apprentice class when its contractors need apprentices and that the economy is the biggest influence on the timeline. Rank 10 can mean very different things in two programmes with different hiring needs. Do not turn a past monthly average into a promised start date, and keep working, studying or applying elsewhere while your application is active. See also <a href="/blog/union-apprenticeship-waitlists-how-long">how long union waitlists take</a>.</p>

<h2>Do Ranked Lists Expire?</h2>

<p>They can. NEIEP's <a href="https://www.neiep.org/iuec-apprenticeship-faq/how-long-will-my-rank-on-the-hiring-list-be-valid/" rel="noopener">rank validity answer</a> is "for two years, or until the recruitment list is exhausted". Portland says applications expire two years after the initial interview, and that a re-interview does not extend that date. These are examples, not universal rules, so record your own expiry date and ask what happens afterwards. Do not assume an application moves to the next recruitment cycle on its own.</p>

<h2>Can You Improve Your Ranking?</h2>

<p>Ask whether your programme allows a re-interview, an application update or a fresh application, and confirm permission before spending money on courses. Portland publishes its conditions: at least six months (180 days) since the initial interview, plus at least 1,000 hours of trade-related work or qualifying post-secondary classes (state-accredited, at least six credits, including one electrical class; distance learning does not count). It adds that the committee decides and there is no guarantee a re-interview will be granted.</p>

<p>Prepare evidence of progress, such as accurate work records, transcripts and examples of practical learning, and explain what changed since your first assessment. A new certificate adds nothing unless the selection rules recognise it.</p>

<h2>What to Do While Waiting</h2>

<p>Keep your contact details current and check email, spam and voicemail. Portland says it does not issue current rankings after the initial email and contacts you only if your rank comes up and you are invited to a Basic Skills Class, so repeated requests may not produce new information.</p>

<p>Keep a simple record of the programme name, your initial result, your expiry date and any required actions. Ask how missed invitations, declined offers or temporary unavailability affect your eligibility. Consider other suitable programmes and compare each one on its own terms, because ranks do not transfer between sponsors. Our guide on <a href="/blog/how-to-apply-for-trade-apprenticeship">how to apply for a trade apprenticeship</a> covers the earlier steps.</p>

<p>Before you contact the office, write down focused questions: Is my application active? Which date controls expiry? Are new applicants added continuously? What documents support an improvement request? Note the answers and the date you received them.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does passing the entrance test guarantee acceptance?</h3>
<p>No. Passing may qualify you for further stages. Ask how the test relates to ranking and final selection.</p>

<h3>Is a high interview score enough?</h3>
<p>It helps, but your position depends on the selection system, the other applicants and the openings available.</p>

<h3>Can my rank get worse without another interview?</h3>
<p>Yes, where newly interviewed candidates place ahead of you. Your score can stay the same while your position changes.</p>

<h3>Can I pay to move up the list?</h3>
<p>No. Do not pay anyone claiming to sell a better rank. Verify genuine charges directly with the official programme.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>A US ranked list is part of a US employment process and needs work authorisation in the United States. Never pay an agent for a better rank or a guaranteed place, and check any offer against <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">our fake job offer guide</a>.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.oregon.gov/boli/apprenticeship/pages/become-an-apprentice.aspx" rel="noopener">Oregon BOLI: Become an Apprentice</a></li>
<li><a href="https://www.etasv.org/faqs" rel="noopener">Electrical Training Alliance of Silicon Valley: FAQs</a></li>
<li><a href="https://www.neiep.org/iuec-apprenticeship-faq/what-happens-after-my-interview/" rel="noopener">NEIEP: What Happens After My Interview?</a></li>
<li><a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">NEIEP: Apprenticeship Recruitment Opportunities</a></li>
<li><a href="https://nietc.org/applicants/preparing-to-apply/preparing-for-the-aptitude-test-and-the-interview/" rel="noopener">NECA-IBEW Electrical Training Center, Portland: Test, Interview, Re-interview</a></li>
<li><a href="https://www.neiep.org/iuec-apprenticeship-faq/how-long-will-my-rank-on-the-hiring-list-be-valid/" rel="noopener">NEIEP: How Long Will My Rank Be Valid?</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/union-apprenticeship-waitlists-how-long">Union Apprenticeship Waitlists: How Long Do They Take?</a>: waiting times</li>
    <li><a href="/blog/what-score-needed-apprenticeship-test">What Score Do You Need on an Apprenticeship Test?</a>: pass marks</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/elevator-apprenticeship-test-neiep-what-to-expect">Elevator Apprenticeship Test (NEIEP): What to Expect</a>: the NEIEP test</li>
</ul>

<p><em>Reviewed on 17 October 2026.</em></p>

HTML;
    }
}
