<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Elevator Mechanic Apprenticeship in Chicago, Philadelphia, and Boston"
 *
 * Checked on 17 October 2026 against the three NEIEP recruitment records, the
 * NEIEP applicant FAQ and the Local 4 education page. What changed against
 * the brief:
 *
 * 1. The Apply Now, View Guide and Study Now buttons are removed; the official
 *    records are linked in the text and in a Sources list.
 * 2. The Chicago record uses the America/Chicago timezone and lists the
 *    recruitment location as 5860 W 111th St, Chicago Ridge. The Philadelphia
 *    record has a typo in its details text ("110/19/2026") and no status
 *    field; its Open Date field (19 October 2026, 9 a.m.) is used. Boston's
 *    record says Local 4 Portland, Maine opened a separate recruitment on
 *    9 March.
 * 3. The brief's interview line ("union and employer representatives",
 *    "consistent questions") is not on the pages read, so it is replaced by
 *    NEIEP's own line that the score is the average of two interviewers.
 * 4. Local 4 says "over 8000 hours" of on-the-job training, a minimum, not
 *    exactly 8,000.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries
 *    are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class ElevatorApprenticeshipChicagoPhiladelphiaBostonBlogSeeder extends Seeder
{
    public const SLUG = 'elevator-mechanic-apprenticeship-chicago-philadelphia-boston';

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
                'title' => 'Elevator Mechanic Apprenticeship in Chicago, Philadelphia, and Boston',
                'excerpt' => 'Compare NEIEP elevator apprenticeship recruitments for IUEC Locals 2, 5 and 4: dates, application limits, fees, requirements and EIAT preparation.',
                'content' => $content,
                'featured_image' => 'blogs/elevator-mechanic-apprenticeship-chicago-philadelphia-boston.jpg',
                'tags' => 'elevator mechanic apprenticeship chicago, elevator apprenticeship philadelphia, elevator mechanic apprenticeship boston, neiep apprenticeship application, iuec local 2, iuec local 5, iuec local 4, eiat preparation',
                'meta_title' => 'Elevator Apprenticeship: Chicago, Philadelphia, Boston',
                'meta_description' => 'Elevator mechanic apprenticeships in Chicago, Philadelphia and Boston: recruitment dates, application limits, requirements and EIAT preparation.',
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
<p><strong>To pursue a union elevator mechanic apprenticeship in Chicago, Philadelphia or Boston, apply through the matching NEIEP recruitment for IUEC Local 2, Local 5 or Local 4.</strong> As checked on 17 October 2026, the Chicago and Boston recruitments we reviewed are marked filled, and Philadelphia's window opens on 19 October 2026 subject to an application limit. Applying does not guarantee selection or an immediate job.</p>

<p>Each city has its own schedule and service area. Start with the official notice, then prepare your documents, aptitude test study and interview examples.</p>

<h2>Understand the Union Apprenticeship Route</h2>

<p>NEIEP, the National Elevator Industry Educational Program, provides education for IUEC apprentices. Its <a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">recruitment page</a> says companies with open apprentice positions hire off the recruitment list in ranked order, and ranking is based on an interview score. You are applying for a supervised training and employment pathway, and work assignments may reach beyond the city in the announcement, so read the recruitment area carefully.</p>

<p>Use the official recruitment system to tell active, upcoming and filled recruitments apart, because an old application page can stay visible after its deadline. The first full guide on this site, <a href="/blog/elevator-apprenticeship-test-neiep-what-to-expect">Elevator Apprenticeship Test (NEIEP): What to Expect</a>, covers the test itself.</p>

<h2>Chicago: IUEC Local 2</h2>

<p>Chicago's <a href="https://neieprecruitment.org/start/recruitments/a1d19f7c-d324-45bf-8b77-bbeedcbf5c6d" rel="noopener">October 2026 recruitment</a> is marked "Recruitment Filled" because "the recruitment applicant limit has been met". Its request window ran from 5 October (9 a.m.) to 12 October (5 p.m.), completed applications were due 13 October at 5 p.m. (America/Chicago), and the limit was 600 applications. The listed location is 5860 W 111th St, Chicago Ridge, IL, and the nonrefundable fee was USD 25.</p>

<p>These details describe that recruitment, not an invitation to apply late. If you missed it, watch NEIEP for the next announcement. If you did apply, follow the instructions sent to your registered email and keep your confirmation number.</p>

<h2>Philadelphia: IUEC Local 5</h2>

<p>The <a href="https://neieprecruitment.org/start/recruitments/a2c70a16-0ee3-4365-8006-84ee4244b7ba" rel="noopener">Philadelphia recruitment</a> opens Monday 19 October 2026 at 9 a.m. and closes Thursday 29 October at 5 p.m., or when 400 applications have been taken. Completed applications are due Friday 30 October at 5 p.m., all in America/New_York time. The page says you must upload proof of education when you complete the application and pay a USD 25 nonrefundable fee, and that the recruitment covers the entire jurisdiction of Local 5. (A typo in the page's details text gives the date as "110/19/2026"; its Open Date field shows 10/19/2026.)</p>

<p>Prepare before opening day. The cap can close access before the advertised end date, so recheck the official page right before you apply. If you read this after the window has closed, treat the dates here as history and look for the next notice.</p>

<h2>Boston: IUEC Local 4</h2>

<p>Boston's <a href="https://neieprecruitment.org/start/recruitments/a134e6f6-448f-4606-a183-6abfa1e3b619" rel="noopener">2026 recruitment</a> is also marked "Recruitment Filled". The request period ran from 23 March (8 a.m.) to 6 April (5 p.m.), final applications were due 7 April at 5 p.m., the limit was 500 applications and the fee was USD 25. The page notes that Local 4 Portland, Maine was opening a separate recruitment on 9 March, so a Local 4 opening elsewhere is not automatically a Boston opening.</p>

<p>Local 4's <a href="https://iueclocal4.com/education/" rel="noopener">education page</a> says applicants must register online during opening dates and to check your spam folder for NEIEP emails. Do not assume the same dates will repeat next year.</p>

<h2>Check the Entry Requirements</h2>

<p>The NEIEP <a href="https://www.neiep.org/iuec-apprenticeship-faq/" rel="noopener">applicant FAQ</a> says you must be at least 17 to apply and 18 to be registered by the Local Joint Apprenticeship Committee, must be authorised to work in the United States, and need a high school diploma or GED at the time of hire. All three notices bar anyone who has previously accepted an apprentice job in the IUEC.</p>

<p>Follow each notice's document deadlines (Philadelphia asks for education evidence with the application). Ask the coordinator about any eligibility doubt before you pay, and never assume an apprenticeship notice offers visa sponsorship.</p>

<h2>Organize Your Application</h2>

<p>Prepare readable copies of your education and identification documents, and check that names and contact details match across them. Use this checklist:</p>

<ul>
<li>Correct city and recruitment area selected.</li>
<li>Opening time converted to your own timezone.</li>
<li>Files ready in acceptable formats.</li>
<li>A payment method for the USD 25 fee.</li>
<li>Application completed before both deadlines (requesting and completing are separate).</li>
<li>Confirmation and applicant number saved.</li>
</ul>

<p>Save the notice so you can refer to the exact instructions later.</p>

<h2>Prepare for the Elevator Industry Aptitude Test</h2>

<p>The NEIEP FAQ says the EIAT covers basic arithmetic, reading comprehension, mechanical comprehension and general tool knowledge, is pass/fail, and needs 70% correct to pass. NEIEP provides an approved study guide through its FAQ page. Passing leads to the interview, not a job. Study in short sessions, track your mistakes, practise fractions, decimals, percentages and written instructions, and for mechanical topics practise explaining why a simple mechanism behaves as it does.</p>

<p>Ask about permitted materials and accommodations before test day, and do not assume a commercial practice test reproduces real questions. Our <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning guide</a> and <a href="/blog/apprenticeship-test-math-basics">maths basics</a> help with the topics.</p>

<h2>Prepare for the Interview and Hiring List</h2>

<p>NEIEP says a candidate's interview score is the average of the two interviewers' scores, and a score of 70% or more earns a rank on the local hiring list. Prepare true examples of teamwork, reliability, learning and problem solving, with your own action and the outcome. Once ranked, stay reachable and follow the coordinator's instructions, because a place on a list does not set a start date. See <a href="/blog/how-apprenticeship-ranking-lists-work">how ranking lists work</a>, and note that NEIEP says rank is valid for two years or until the list is exhausted.</p>

<h2>Training, Pay and Choosing a City</h2>

<p>Local 4 says its apprentices complete a five-year education programme with 600 hours of classroom training and over 8,000 hours of on-the-job training. Confirm the schedule for your recruitment and ask the local or employer for current apprentice wage rates. Compare starting pay, benefits, commuting, housing and travel before you relocate, and do not use experienced mechanic earnings as your starting wage.</p>

<p>Choose a city where you can reach both classes and job sites. Write down monthly housing, transport and living costs, compare them with the real starting wage, and move only after the programme gives you clear written instructions. An open window alone is not a reason to move.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply without elevator experience?</h3>
<p>Yes. NEIEP says no specific background is required, but you must meet eligibility rules and succeed in selection.</p>

<h3>Are Chicago and Boston accepting applications now?</h3>
<p>The records we reviewed on 17 October 2026 are marked filled. Check NEIEP for later announcements.</p>

<h3>When can I apply in Philadelphia?</h3>
<p>The published opening is 19 October 2026 at 9 a.m. Eastern. The 400-application cap may end access before 29 October.</p>

<h3>Does passing the EIAT guarantee a job?</h3>
<p>No. It qualifies you for the interview. Hiring depends on your rank and the openings that companies have.</p>

<h3>Can I submit after a recruitment fills?</h3>
<p>Follow the official notice. Do not assume late applications are accepted or that paying reserves a future place.</p>

<h3>How much does it cost to apply?</h3>
<p>Each of the three notices states a USD 25 nonrefundable fee. Never pay anyone else for a place.</p>

<h3>Can I apply to more than one city?</h3>
<p>The notices restrict only people who already accepted an IUEC apprentice job. Check each notice and its dates.</p>

<h3>How old must I be?</h3>
<p>At least 17 to apply and 18 to be registered, according to the NEIEP FAQ.</p>

<h2>People Also Search For</h2>

<h3>Elevator mechanic apprenticeship Chicago</h3>
<p>IUEC Local 2, recruitment filled.</p>

<h3>Elevator apprenticeship Philadelphia</h3>
<p>IUEC Local 5, opens 19 October 2026.</p>

<h3>Elevator mechanic apprenticeship Boston</h3>
<p>IUEC Local 4, recruitment filled.</p>

<h3>NEIEP apprenticeship application</h3>
<p>Online, with a USD 25 fee.</p>

<h3>IUEC Local 2</h3>
<p>Chicago Ridge, Illinois.</p>

<h3>IUEC Local 5</h3>
<p>Philadelphia jurisdiction.</p>

<h3>IUEC Local 4</h3>
<p>Boston, with a separate Portland, Maine round.</p>

<h3>EIAT preparation</h3>
<p>70% correct to pass.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>The notices require authorisation to work in the United States and do not offer visa sponsorship. Never pay an agent for an application slot, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before sending money or documents.</p>

<h2>Bottom Line</h2>

<p>Match the recruitment to your city, prepare documents early and follow NEIEP's current instructions. Keep practical work plans going while you pursue selection and wait for a confirmed offer.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">NEIEP: Recruitment Process and Opportunities</a></li>
<li><a href="https://neieprecruitment.org/start/recruitments/a1d19f7c-d324-45bf-8b77-bbeedcbf5c6d" rel="noopener">NEIEP: Chicago Local 2, October 2026 Recruitment</a></li>
<li><a href="https://neieprecruitment.org/start/recruitments/a2c70a16-0ee3-4365-8006-84ee4244b7ba" rel="noopener">NEIEP: Philadelphia Local 5, October 2026 Recruitment</a></li>
<li><a href="https://neieprecruitment.org/start/recruitments/a134e6f6-448f-4606-a183-6abfa1e3b619" rel="noopener">NEIEP: Boston Local 4, March 2026 Recruitment</a></li>
<li><a href="https://iueclocal4.com/education/" rel="noopener">IUEC Local 4: Education</a></li>
<li><a href="https://www.neiep.org/iuec-apprenticeship-faq/" rel="noopener">NEIEP: Applicant FAQs</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/elevator-apprenticeship-test-neiep-what-to-expect">Elevator Apprenticeship Test (NEIEP): What to Expect</a>: the EIAT</li>
    <li><a href="/blog/how-apprenticeship-ranking-lists-work">How Apprenticeship Ranking Lists Work</a>: scores and expiry</li>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>: the application steps</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: mechanical section</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Recruitment status checked on 17 October 2026: Philadelphia is upcoming, and the Chicago and Boston records are filled. Recheck application caps, dates and requirements before submitting; no vacancy or selection outcome is guaranteed.</p>
HTML;
    }
}
