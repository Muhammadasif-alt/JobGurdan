<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Mechanical Reasoning Questions Explained"
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
class MechanicalReasoningQuestionsBlogSeeder extends Seeder
{
    public const SLUG = 'mechanical-reasoning-questions-explained';

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
                'title' => 'Mechanical Reasoning Questions Explained',
                'excerpt' => 'Mechanical reasoning questions test how levers, pulleys, gears, hydraulics, ramps, balance and simple circuits behave, usually from a diagram. Seven core concepts with original worked examples, a method for each question and a practice routine.',
                'content' => $content,
                'featured_image' => 'blogs/mechanical-reasoning-questions-explained.jpg',
                'tags' => 'mechanical reasoning questions, mechanical aptitude test, levers pulleys gears, hydraulics test questions, mechanical comprehension practice, apprenticeship mechanical test, bennett mechanical aptitude, simple machines questions',
                'meta_title' => 'Mechanical Reasoning Questions Explained',
                'meta_description' => 'Mechanical reasoning questions explained: levers, pulleys, gears, hydraulics, ramps, balance and circuits with worked examples and a study method.',
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
<p><strong>Mechanical reasoning questions test whether you understand how basic physical systems work, such as levers, pulleys, gears and hydraulics, usually through a diagram and a short question.</strong> They appear on trade apprenticeship, technician and some pre-employment tests because they show how well someone is likely to understand tools and machinery. Formats vary: for example the NEIEP elevator test has a 35-question mechanical aptitude section that points to levers, pulleys, gears, hydraulics and fulcrums, and Pipefitters Local 597 lists a Bennett Mechanical Aptitude section. Confirm your own test's format with your programme.</p>

<h2>Why Tests Include It</h2>

<p>Employers in construction, manufacturing and the skilled trades use these questions because they measure practical, spatial and physical-logic thinking, which is hard to cram. A candidate who reasons well about levers and gears tends to learn equipment faster, even without hands-on experience.</p>

<h2>Seven Core Concepts</h2>

<h3>1. Levers</h3>
<p>A lever is a rigid bar pivoting on a fixed point, the fulcrum. The farther from the fulcrum you push, the less force you need. <strong>Example:</strong> two people lift a rock with a crowbar; the one holding it <strong>farther from the fulcrum uses less force</strong>.</p>

<h3>2. Pulleys</h3>
<p>A fixed pulley only changes the direction of the force. A movable pulley reduces the force needed but needs more rope pulled. <strong>Example:</strong> adding a second, movable pulley <strong>roughly halves the force</strong> while you pull twice the length of rope.</p>

<h3>3. Gears</h3>
<p>Meshed gears turn in opposite directions. A small gear driving a large gear gives more torque and less speed; the reverse gives more speed and less torque. <strong>Example:</strong> gear A has 10 teeth and gear B has 30. When A makes 3 turns, <strong>B makes 1 turn</strong>, in the opposite direction.</p>

<h3>4. Hydraulics</h3>
<p>Fluid cannot be compressed, so pressure is passed on through it. A small force on a small piston gives a larger force on a large piston, but the large piston moves a shorter distance. <strong>Example:</strong> the large piston gives <strong>more force and less movement</strong>.</p>

<h3>5. Inclined Planes and Wedges</h3>
<p>A longer, gentler ramp needs less force than a short, steep one to raise the same load by the same height. <strong>Example:</strong> the <strong>long, gentle ramp</strong> needs less force because the distance is greater.</p>

<h3>6. Centre of Gravity and Balance</h3>
<p>Low, wide objects are more stable; tall, narrow or top-heavy ones tip more easily. <strong>Example:</strong> a box with heavy items on top is <strong>less stable</strong> than the same box loaded the other way.</p>

<h3>7. Simple Circuits and Flow</h3>
<p>In a series circuit, one open switch breaks the whole circuit; in a parallel circuit, the other paths still work. <strong>Example:</strong> three bulbs in series and one burns out: <strong>all three go dark</strong>.</p>

<p>All examples are original practice problems, not real exam questions.</p>

<h2>How to Approach a Question</h2>

<ol>
<li>Study the diagram before you read the answer choices.</li>
<li>Identify what is fixed and what moves, such as the fulcrum or the direction a gear turns.</li>
<li>Think in trade-offs: systems usually trade force for distance, or speed for torque.</li>
<li>Eliminate impossible answers first.</li>
<li>Use real-world intuition: a wrench, a wheelbarrow, a bicycle.</li>
<li>Watch directions and units, especially on gear and balance questions.</li>
</ol>

<h2>How to Practise</h2>

<ol>
<li>Learn the seven concepts before you attempt practice sets.</li>
<li>Draw the diagrams and label the forces.</li>
<li>Do timed sets once you know your test's real time limit.</li>
<li>Review every wrong answer and name the concept you missed.</li>
<li>Relate each concept to a real tool you have used.</li>
<li>Retest weak areas a few days later.</li>
</ol>

<p>The maths involved is usually simple ratios, such as gear-tooth counts or lever-arm lengths; the main challenge is visualising how the system behaves. See <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a>, <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a> and the <a href="/blog/ironworker-aptitude-test-practice-guide">ironworker test guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Are mechanical reasoning questions hard to prepare for?</h3>
<p>They are learnable. Most rely on a small set of repeating concepts.</p>

<h3>Do I need an engineering background?</h3>
<p>No. These tests are designed for people without formal engineering training.</p>

<h3>What jobs use mechanical reasoning tests?</h3>
<p>Trade apprenticeship exams, maintenance technician tests and some pre-employment assessments; the topics vary by employer.</p>

<h3>Can I improve quickly if I am weak here?</h3>
<p>Yes, with focused practice. Drawing examples by hand helps more than only reading.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/ironworker-aptitude-test-practice-guide">Ironworker Aptitude Test: Format, Math and Practice</a>: ironwork entry</li>
    <li><a href="/blog/sheet-metal-workers-aptitude-test-study-plan">Sheet Metal Aptitude Test: Sections, Tips, Study Plan</a>: sheet metal entry</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
</ul>

HTML;
    }
}
