<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "One Week Before the Apprenticeship Test: Study Plan"
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
class OneWeekBeforeApprenticeshipTestBlogSeeder extends Seeder
{
    public const SLUG = 'one-week-before-apprenticeship-test-study-plan';

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
                'title' => 'One Week Before the Apprenticeship Test: Study Plan',
                'excerpt' => 'In the last week before an apprenticeship test, spend days 1 to 5 on maths, reading and mechanical reasoning in short sessions, take one timed practice test on day 6, then rest and prepare documents on day 7.',
                'content' => $content,
                'featured_image' => 'blogs/one-week-before-apprenticeship-test-study-plan.jpg',
                'tags' => 'apprenticeship test study plan, one week before apprenticeship test, 7 day study plan apprenticeship, apprenticeship test last minute, apprenticeship test checklist, how to study for apprenticeship test, apprenticeship test day, aptitude test week plan',
                'meta_title' => 'One Week Before the Apprenticeship Test: Study Plan',
                'meta_description' => 'What to study the week before your apprenticeship test: a 7-day plan for maths, reading and mechanical reasoning, plus a short-notice version.',
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
<p><strong>In the week before your apprenticeship test, spend the first five days on maths, reading comprehension and mechanical reasoning in short daily sessions, take one full timed practice test on day 6, then rest and prepare your documents on day 7.</strong> This builds steady recall without burning you out before test day.</p>

<h2>First, Know What Is on Your Test</h2>

<p>This plan assumes maths, reading and mechanical reasoning, which many programmes include in some form. Your test may differ: the NEIEP elevator test, for example, has 35 reading, 35 mechanical and 30 maths questions, Local 20 (sheet metal) has four sections including workplace observation and locating information, and some programmes list no test at all. Ask your local for the exact format and adjust the daily focus. See <a href="/blog/what-score-needed-apprenticeship-test">what score you need</a>.</p>

<h2>Day 1: Maths Foundations (60 to 90 minutes)</h2>
<ul>
<li>Review adding, subtracting, multiplying and dividing fractions.</li>
<li>Practise converting between fractions, decimals and percentages.</li>
<li>Work 15 to 20 problems and check every answer by hand.</li>
<li>Start a short list of mistakes to revisit.</li>
</ul>

<h2>Day 2: Measurement and Geometry (60 to 90 minutes)</h2>
<ul>
<li>Review conversions: feet to inches, cubic feet to cubic yards, square feet to square yards.</li>
<li>Practise area and volume for rectangles, triangles and circles.</li>
<li>Practise reading a tape measure to the nearest 1/16 inch.</li>
<li>Redo 5 to 10 problems from day 1.</li>
</ul>

<h2>Day 3: Ratios, Rates and Basic Algebra (60 to 90 minutes)</h2>
<ul>
<li>Practise ratios and proportions, such as mixing materials in a set ratio.</li>
<li>Practise rate problems, such as work done per hour.</li>
<li>Solve one- and two-step equations for x.</li>
<li>Time yourself on 10 word problems.</li>
</ul>

<h2>Day 4: Reading Comprehension (45 to 60 minutes)</h2>
<ul>
<li>Read 3 to 5 short technical or safety-style passages.</li>
<li>Read the question first, then scan the passage for the answer.</li>
<li>Time each passage and its questions to simulate pressure.</li>
<li>Review each miss and note why the right answer was right.</li>
</ul>

<h2>Day 5: Mechanical Reasoning (60 to 90 minutes)</h2>
<ul>
<li>Review levers, pulleys, gears, hydraulics, inclined planes and centre of gravity (see <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning explained</a>).</li>
<li>Draw 2 or 3 diagrams yourself and label the forces.</li>
<li>Work 15 to 20 practice questions.</li>
<li>Link each concept to a real tool, such as a wheelbarrow, bicycle gears or a car jack.</li>
</ul>

<h2>Day 6: Full Timed Practice Test (2 to 3 hours with review)</h2>
<ul>
<li>Take one full-length practice test with no notes, and no calculator unless your real test allows one.</li>
<li>Sit somewhere quiet, as on test day.</li>
<li>Score it and find your two or three weakest areas.</li>
<li>Spend 20 to 30 minutes on those areas only.</li>
</ul>

<h2>Day 7: Rest, Review and Logistics (30 to 45 minutes)</h2>
<ul>
<li>Do a light review of your weakest topic from day 6.</li>
<li>Avoid heavy new studying: cramming the night before rarely helps and can raise anxiety.</li>
<li>Confirm the location, time and documents such as photo ID.</li>
<li>Lay out your documents and any allowed materials, sleep well and eat breakfast.</li>
</ul>

<h2>Tips for the Whole Week</h2>

<ol>
<li>Study in short, focused blocks.</li>
<li>Check your answers and understand why each mistake happened.</li>
<li>Keep a running mistake list and revisit it every day or two.</li>
<li>Practise under time pressure at least twice, once you know your real time limit.</li>
<li>Use real tools as mental models.</li>
</ol>

<h2>If You Only Have a Few Days</h2>

<ol>
<li>Take one timed practice test to find your weakest section.</li>
<li>Study that section intensively.</li>
<li>Skim the other sections to avoid losing easy points.</li>
<li>Sleep the night before, since fatigue costs more than one extra session.</li>
</ol>

<p>Revise the maths with <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a>. Programme information is on <a href="https://www.apprenticeship.gov" rel="noopener">Apprenticeship.gov</a>, and an OSHA 10-hour course from <a href="https://www.osha.gov/training" rel="noopener">OSHA training</a> shows safety awareness while you wait for your result. If the test does not go well, see <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is one week enough to prepare for an apprenticeship test?</h3>
<p>It can be for a solid review if your basic maths and reading are already sound. A weak base needs longer.</p>

<h3>Should I study the night before the test?</h3>
<p>Light review only. Prioritise sleep.</p>

<h3>What if I run out of time to cover everything?</h3>
<p>Start with your weakest section, where each hour gains the most points.</p>

<h3>Can I use this plan for any trade?</h3>
<p>The core sections cover many programmes, but confirm your test and adjust the daily focus.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/what-score-needed-apprenticeship-test">What Score Do You Need on the Apprenticeship Test?</a>: pass marks</li>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
</ul>

HTML;
    }
}
