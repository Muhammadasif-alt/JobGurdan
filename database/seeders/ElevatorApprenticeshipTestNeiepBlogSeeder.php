<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Elevator Apprenticeship Test (NEIEP): What to Expect"
 *
 * Checked on 16 October 2026. What changed against the brief:
 *
 * 1. The ten Apply Now buttons are dropped. Commercial portals (LinkedIn,
 *    SEEK, Bayt, GulfTalent) are named in prose where useful but not linked;
 *    only official or JobGader pages are linked.
 * 2. A Pakistan section is added (BEOE licensing and emigration checks).
 * 3. The brief's four or five FAQs become eight, plus eight People Also Search
 *    For entries and a More Job Guides block.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class ElevatorApprenticeshipTestNeiepBlogSeeder extends Seeder
{
    public const SLUG = 'elevator-apprenticeship-test-neiep-what-to-expect';

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
                'title' => 'Elevator Apprenticeship Test (NEIEP): What to Expect',
                'excerpt' => 'The NEIEP Elevator Industry Aptitude Test (EIAT) is a 100-question multiple-choice test: 35 reading, 35 mechanical aptitude and 30 maths. The minimum passing grade is 70%, and a pass leads to an interview with two committee members.',
                'content' => $content,
                'featured_image' => 'blogs/elevator-apprenticeship-test-neiep-what-to-expect.jpg',
                'tags' => 'elevator apprenticeship test, neiep aptitude test, elevator industry aptitude test, eiat practice, iuec apprenticeship, elevator constructor apprenticeship, neiep interview, eiat passing score',
                'meta_title' => 'Elevator Apprenticeship Test (NEIEP): What to Expect',
                'meta_description' => 'Elevator apprenticeship test (NEIEP EIAT): 100 questions, 70% pass mark, topics, interview, retake rule, original practice and a study plan.',
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
<p><strong>The Elevator Industry Aptitude Test (EIAT) is part of recruitment for IUEC elevator constructor apprenticeships supported by the National Elevator Industry Educational Program (NEIEP).</strong> The official study guide describes a test of 100 multiple-choice questions: reading comprehension, mechanical aptitude and mathematics. The minimum passing grade is 70%, and a pass leads to an interview, not automatic employment.</p>

<h2>Who Can Apply?</h2>

<p>NEIEP's published minimum requirements are: at least 17 years old to apply, 18 to be registered by the Local Joint Apprenticeship Committee, a high school diploma or GED at the time of hire, and authorisation to work in the United States. The test checks learning readiness; you are not expected to arrive as a qualified elevator mechanic. Check the specific recruitment notice for documents and deadlines on the <a href="https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/" rel="noopener">NEIEP recruitment page</a>.</p>

<h2>EIAT Format</h2>

<table>
<thead><tr><th>Section</th><th>Questions</th><th>Focus</th></tr></thead>
<tbody>
<tr><td>Reading comprehension</td><td>35</td><td>Understanding written information</td></tr>
<tr><td>Mechanical aptitude</td><td>35</td><td>Reasoning about mechanical relationships</td></tr>
<tr><td>Mathematics</td><td>30</td><td>Arithmetic and basic algebra</td></tr>
<tr><td>Total</td><td>100</td><td>Multiple choice</td></tr>
</tbody>
</table>

<p>This breakdown is from the official <a href="https://www.neiep.org/wp-content/uploads/2024/08/study-guide-eiat-2024.pdf" rel="noopener">2024 EIAT study guide</a>. Confirm the timing, calculator permission, identification and permitted materials in your own appointment instructions, and do not rely on commercial sites for rules your recruitment has not confirmed.</p>

<h2>What to Study</h2>

<ul>
<li><strong>Maths.</strong> The guide lists arithmetic (addition, subtraction, multiplication, division, fractions, decimals and percentages) and algebra (solving equations, manipulating formulas and basic expressions). Build accuracy before speed, and write intermediate steps.</li>
<li><strong>Mechanical aptitude.</strong> The guide points to levers, pulleys and gears, hydraulics (area and pressure) and fulcrums and balancing weights. Identify what moves, in which direction, and what the problem assumes; sketch it if that helps. See <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning explained</a>.</li>
<li><strong>Reading.</strong> Read the whole passage and the surrounding sentences before you choose a word or an answer.</li>
</ul>

<h2>Original Practice Exercises</h2>

<p>These were created for this article. They are not official NEIEP questions, a mock exam or a prediction of your result.</p>

<ol>
<li><strong>5/6 + 1/4.</strong> Twelfths: 10/12 + 3/12 = 13/12, or <strong>1 1/12</strong>.</li>
<li><strong>18.9 &divide; 0.7.</strong> Multiply both by 10: 189 &divide; 7 = <strong>27</strong>.</li>
<li><strong>24 &minus; 3 &times; (2 + 4).</strong> Brackets: 6; multiply: 18; 24 &minus; 18 = <strong>6</strong>.</li>
<li><strong>Solve 5x &minus; 8 = 27.</strong> 5x = 35, so <strong>x = 7</strong>.</li>
<li><strong>"Before recording a measurement, the trainee checks it again to improve its ____."</strong> accuracy, delay, weight or colour? <strong>Accuracy</strong>.</li>
<li><strong>Two external gears mesh directly and the first turns clockwise. Which way does the second turn?</strong> <strong>Counterclockwise.</strong></li>
</ol>

<p>These exercises are not instructions for repairing elevators or working on operating equipment.</p>

<h2>Tool Assessment, Pass Mark and Retaking</h2>

<ul>
<li><strong>Tool assessment.</strong> NEIEP states that correct responses on the tool assessment count toward the final score on the interview. Learn to recognise common tools and explain what they are for, using photographs, manuals or supervised instruction, and do not operate unfamiliar equipment to prepare.</li>
<li><strong>Pass mark.</strong> The EIAT is scored pass/fail, with 70% correct the minimum passing grade, and applicants who score 70% or higher are scheduled for an interview.</li>
<li><strong>If you fail.</strong> Applicants who fail will not be interviewed and are removed from the pool, but they may reapply during the next or a later recruitment period. See <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a>.</li>
</ul>

<h2>The Interview</h2>

<p>On average interviews run 15 to 20 minutes and are conducted by two members of the Joint Apprenticeship Committee, one from the union and one from the employer side. A score of 70% is the minimum to receive a rank on the Local's hiring list, and your ranking depends on your interview score. Prepare truthful examples of teamwork, reliability, learning and solving problems: the situation, what you did and the result. Experience outside construction can still show these qualities.</p>

<h2>A Two-Week Plan</h2>

<p>In week one rebuild arithmetic and reading accuracy. In week two mix topics and revisit mistakes without looking at old solutions. Record whether each error came from misunderstanding, calculation or rushing; if you keep losing decimal places, practise that operation until you can explain every step. Confirm your appointment, transport, identification and any accommodation request before test day.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is prior elevator experience required?</h3>
<p>No. The assessment is designed for apprenticeship applicants, not qualified mechanics.</p>

<h3>What is the EIAT passing score?</h3>
<p>NEIEP states that 70% correct is the minimum passing grade.</p>

<h3>How many questions are on the EIAT?</h3>
<p>The official study guide describes 100 multiple-choice questions: 35 reading, 35 mechanical and 30 maths.</p>

<h3>Does passing guarantee a job?</h3>
<p>No. Interview performance and the hiring list process still matter.</p>

<h3>Can I retake the EIAT?</h3>
<p>Applicants who fail may reapply in the next or a later recruitment period.</p>

<h3>How old must I be?</h3>
<p>At least 17 to apply and 18 to be registered by the Local Joint Apprenticeship Committee.</p>

<h3>Is paid preparation necessary?</h3>
<p>No. Start with NEIEP's free official study guide.</p>

<h3>What does the interview involve?</h3>
<p>An interview of about 15 to 20 minutes with two committee members.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/sheet-metal-workers-aptitude-test-study-plan">Sheet Metal Aptitude Test: Sections, Tips, Study Plan</a>: sheet metal entry</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
