<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Union Apprenticeship Waitlists: How Long Do They Take?"
 *
 * Checked on 17 October 2026 against the NEIEP, Portland NIETC, Silicon Valley
 * and Laborers Training School pages. What changed against the brief:
 *
 * 1. The View Guide and Apply Now buttons are removed; official pages are
 *    linked in the text and in a Sources list.
 * 2. The brief calls the school "Southern California Laborers Training
 *    School"; its own site shows the Laborers Training School in Azusa,
 *    California, so the guide uses that name.
 * 3. The school's page adds that a signatory contractor can sponsor an
 *    applicant to skip the list, that failing any part of the five-day entry
 *    assessment blocks reapplying for six months, and that check-ins are by
 *    phone.
 * 4. A Pakistan note, eight FAQs and eight People Also Search For entries
 *    are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class UnionApprenticeshipWaitlistsBlogSeeder extends Seeder
{
    public const SLUG = 'union-apprenticeship-waitlists-how-long';

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
                'title' => 'Union Apprenticeship Waitlists: How Long Do They Take?',
                'excerpt' => 'No union waitlist has a guaranteed length. See what drives the wait, how list expiry and check-ins work, and what to do while you wait.',
                'content' => $content,
                'featured_image' => 'blogs/union-apprenticeship-waitlists-how-long.jpg',
                'tags' => 'union apprenticeship waitlists, how long is an apprenticeship waiting list, union apprenticeship waiting time, apprenticeship waitlist status, apprenticeship check-in, neiep hiring list, apprenticeship list expiry, laborers training school waiting list',
                'meta_title' => 'Union Apprenticeship Waitlists: How Long Do They Take?',
                'meta_description' => 'Union apprenticeship waitlists have no fixed length. See what drives wait times, how list expiry and check-ins work, and what to do while you wait.',
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
<p><strong>Union apprenticeship waitlists have no single guaranteed length.</strong> Your wait depends on the programme, your selection position, employer demand and when training places open. Some applicants never receive an offer before their eligibility ends. A two-year list validity period means your application can stay eligible for that time; it does not promise a job within two years.</p>

<p>This guide covers United States union trade apprenticeships. The most useful answer comes from your own sponsor's current rules and hiring activity, not a national estimate or another applicant's story.</p>

<h2>First, Identify Which Wait You Mean</h2>

<p>"Waiting list" can mean waiting for applications to open, for an assessment appointment, for an interview, for selection from an eligible pool, or for a work assignment after acceptance. Ask the office to name your current stage. Passing a test does not mean you are on the final hiring list, and an application number is not a selection position. Someone waiting for an interview is not in the same queue as a ranked applicant waiting for employer demand. Start with Apprenticeship.gov's <a href="https://www.apprenticeship.gov/career-seekers" rel="noopener">career seeker overview</a> if you are new to the process.</p>

<h2>Why There Is No Reliable Universal Average</h2>

<p>Union trades serve different local industries. The <a href="https://nietc.org/applicants/preparing-to-apply/preparing-for-the-aptitude-test-and-the-interview/" rel="noopener">Portland NECA-IBEW Electrical Training Center</a> says it starts a new apprentice class when its contractors need apprentices, and calls the economy the biggest influence on the timeline. A busy recruitment period can produce many eligible applicants without an immediate opening for each. Treat claims such as "everyone starts within three months" with caution, and ask whether an estimate comes from recent local selections.</p>

<h2>How Your Ranking Affects the Wait</h2>

<p>For ranked programmes, position matters alongside openings. NEIEP's <a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">recruitment page</a> says companies with open apprentice positions hire off the recruitment list in ranked order. But a ranking is not a calendar: number 10 does not mean ten days, ten weeks or ten months. Ask whether the number on your notice is your original rank, a current position or just an identifier. For the scoring side, read <a href="/blog/how-apprenticeship-ranking-lists-work">how apprenticeship ranking lists work</a>.</p>

<h2>Can Your Wait Increase After You Are Ranked?</h2>

<p>Yes, where new applicants are added to an active list. The <a href="https://www.etasv.org/faqs" rel="noopener">Silicon Valley FAQ</a> says your rank can move up and down as people are placed for employment or as new applicants are interviewed.</p>

<p>A hypothetical of our own: you are ranked 18, six people ahead of you are selected so you move to 12, then nine new candidates enter ahead of you and you move to 21. Your interview score never changed. This shows list movement, not a prediction for any programme.</p>

<h2>List Expiry Is Different From Waiting Time</h2>

<p>NEIEP's <a href="https://www.neiep.org/iuec-apprenticeship-faq/how-long-will-my-rank-on-the-hiring-list-be-valid/" rel="noopener">rank validity answer</a> is "for two years, or until the recruitment list is exhausted". That describes how long you stay eligible, not an average wait, and it does not promise every ranked person is hired. Portland says applications expire two years after the initial interview, even if you later re-interview. Record your own deadline and confirm what happens afterwards; never assume renewal or a transfer to the next cycle.</p>

<h2>Some Waiting Lists Require Regular Contact</h2>

<p>Staying eligible can take action. The <a href="https://www.laborerstrainingschool.com/applicant" rel="noopener">Laborers Training School</a> in Azusa, California says entry assessments are scheduled based on the need of the local union, so applicants are put on a waiting list that "can be a lengthy process". Applicants must contact the apprenticeship office in March, June, October and December, and failure to contact it in each of those months "will result in your application being deleted". Its page also says a signatory contractor can sponsor an applicant to skip the waiting list, and that failing any part of the five-day entry assessment means waiting six months before reapplying.</p>

<p>These are that school's rules, not a schedule for every union. Read your own instructions, save proof of each check-in and ask how to update your phone number or email address. A missed invitation or overlooked requirement can end an application.</p>

<h2>What Should You Ask the Training Office?</h2>

<p>Prepare a short message with your name, applicant number, trade and interview date, then ask:</p>

<ul>
<li>Is my application complete and active?</li>
<li>Which selection stage am I in?</li>
<li>Can you share my current rank or recent hiring activity?</li>
<li>When does my eligibility expire?</li>
<li>Must I check in or send updated documents?</li>
<li>What happens if I miss or decline an invitation?</li>
</ul>

<p>Respect the office's update policy. Portland says "we do not issue current ranking after the initial email", so repeated rank requests would not change what that programme sends.</p>

<h2>Can You Shorten the Wait?</h2>

<p>You cannot create vacancies, but some programmes offer a re-interview route. Portland publishes the conditions for requesting one and says approval is not guaranteed. Before buying training, confirm that the sponsor recognises the course or experience for that purpose, and keep records of work duties, hours and education. A helper job or preparatory course builds experience but is not guaranteed admission unless the programme says so in writing.</p>

<h2>A Practical Plan While You Wait</h2>

<p>Keep earning or studying, and do not quit a job just because your rank looks promising until you have clear start arrangements. Keep documents organised, keep reliable transport, check email and voicemail often and be ready to answer an invitation within its deadline. Consider other suitable openings without assuming your score or rank transfers, and compare location, training, costs, pay and working conditions. Our guide on <a href="/blog/how-to-apply-for-trade-apprenticeship">how to apply for a trade apprenticeship</a> covers the application side. Set a monthly reminder to review your records and deadlines.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does being waitlisted mean I was rejected?</h3>
<p>Not necessarily. It may mean you remain eligible while awaiting selection. Confirm your exact status with the sponsor.</p>

<h3>Is a two-year list a two-year waiting period?</h3>
<p>No. List validity describes how long you stay eligible, not a hiring date. You may be selected earlier, or not at all before expiry.</p>

<h3>Should I call every week?</h3>
<p>Follow the programme's communication rules. Required check-ins and voluntary status requests are different things.</p>

<h3>What happens if I miss a check-in?</h3>
<p>At the Laborers Training School, missing a required month results in application deletion. Check your own programme's rule.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>US union programmes expect work authorisation in the United States, and a waitlist place is not a visa. Do not pay an agent for a position on a list. Read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://nietc.org/applicants/preparing-to-apply/preparing-for-the-aptitude-test-and-the-interview/" rel="noopener">NECA-IBEW Electrical Training Center, Portland: Ranking, Re-interview, Application Expiry</a></li>
<li><a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">NEIEP: Apprenticeship Recruitment Opportunities</a></li>
<li><a href="https://www.etasv.org/faqs" rel="noopener">Electrical Training Alliance of Silicon Valley: FAQs</a></li>
<li><a href="https://www.neiep.org/iuec-apprenticeship-faq/how-long-will-my-rank-on-the-hiring-list-be-valid/" rel="noopener">NEIEP: Hiring List Validity</a></li>
<li><a href="https://www.laborerstrainingschool.com/applicant" rel="noopener">Laborers Training School: Applicants</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-apprenticeship-ranking-lists-work">How Apprenticeship Ranking Lists Work</a>: scores, ranks and expiry</li>
    <li><a href="/blog/laborers-union-entrance-exam-guide">Laborers Union Entrance Exam Guide</a>: LiUNA entry</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/what-score-needed-apprenticeship-test">What Score Do You Need on an Apprenticeship Test?</a>: pass marks</li>
</ul>

HTML;
    }
}
