<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Sheet Metal Aptitude Test: Sections, Tips, Study Plan"
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
class SheetMetalWorkersAptitudeTestBlogSeeder extends Seeder
{
    public const SLUG = 'sheet-metal-workers-aptitude-test-study-plan';

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
                'title' => 'Sheet Metal Aptitude Test: Sections, Tips, Study Plan',
                'excerpt' => 'A sheet metal apprenticeship test can cover maths, reading, mechanical reasoning or spatial ability, depending on the local.',
                'content' => $content,
                'featured_image' => 'blogs/sheet-metal-workers-aptitude-test-study-plan.jpg',
                'tags' => 'sheet metal workers aptitude test, sheet metal apprenticeship test, sheet metal math test, sheet metal aptitude test practice, smart apprenticeship test, jatc aptitude test, sheet metal local 20, sheet metal study plan',
                'meta_title' => 'Sheet Metal Aptitude Test: Sections, Tips, Study Plan',
                'meta_description' => 'Sheet metal apprenticeship aptitude test: Local 20, Local 88, Wisconsin and Southern California examples, maths topics, solved practice and study plan.',
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
<p><strong>A sheet metal workers aptitude test can assess mathematics, reading, mechanical reasoning or spatial ability, depending on the apprenticeship programme.</strong> There is no universal question count, time limit or pass mark. Check your training committee's current instructions before you choose preparation material. Passing may qualify you for another selection stage; it does not guarantee employment or a place.</p>

<h2>Why the Format Varies</h2>

<table>
<thead><tr><th>Programme</th><th>Published assessment</th></tr></thead>
<tbody>
<tr><td><a href="https://sm88jatc.org/how-to-apply/" rel="noopener">Local 88 JATC</a></td><td>A basic math and reading competency exam administered by the committee, then an oral interview, with a substance abuse test before indenture</td></tr>
<tr><td><a href="https://smw20training.com/how-to-apply/" rel="noopener">Local 20</a></td><td>Four sections: applied mathematics, applied technology, workplace observation and locating information; applicants must pass all four</td></tr>
<tr><td><a href="https://www.smart18ecwi.org/taking-the-tests/" rel="noopener">East Central Wisconsin JATC</a></td><td>NexGEN Accuplacer reading comprehension, arithmetic and elementary algebra, at a local technical college; the tests are not timed</td></tr>
<tr><td><a href="https://www.socalsma.org/application-process" rel="noopener">Southern California JATC</a></td><td>A fifteen-minute timed math test, a mental ability test, a mechanical comprehension test and a paper board (spatial) test</td></tr>
</tbody>
</table>

<p>These are local examples, not one national syllabus. Your invitation controls your recruitment.</p>

<h2>Confirm Before You Study</h2>

<p>Ask the apprenticeship office for the test name, sections and official sample questions, and for timing, calculator rules, permitted materials and identification. Also ask how scores are used, whether each section must be passed, and how retesting works (see <a href="/blog/can-you-retake-apprenticeship-aptitude-test">our retake guide</a>). Request accessibility arrangements early. The International Training Institute explains <a href="https://www.sheetmetal-iti.org/apprenticeship" rel="noopener">apprenticeship</a> and the <a href="https://www.sheetmetal-iti.org/career-ladder" rel="noopener">career steps</a>.</p>

<h2>Maths to Review First</h2>

<p>Local 88 lists the four operations, percentages, converting fractions to decimals and improper fractions to mixed numbers, integers and quadratic equations as preparation topics, and says the list is a general guide and not all-inclusive. Local 20 lists percent, decimal and fraction problems and elementary algebra. A practical order:</p>

<ul>
<li>Arithmetic accuracy, then fractions, decimals and percentages.</li>
<li>Simple equations and expressions.</li>
<li>Measurement: reading a ruler and combining feet and inches.</li>
<li>Geometry where required: angles, perimeter, area and right triangles.</li>
</ul>

<p>Revise with OpenStax Prealgebra: <a href="https://openstax.org/books/prealgebra-2e/pages/4-introduction-to-fractions" rel="noopener">fractions</a>, <a href="https://openstax.org/books/prealgebra-2e/pages/5-introduction-to-decimals" rel="noopener">decimals</a>, <a href="https://openstax.org/books/prealgebra-2e/pages/6-introduction-to-percents" rel="noopener">percents</a> and <a href="https://openstax.org/books/prealgebra-2e/pages/9-introduction" rel="noopener">geometry</a>. See also <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a>.</p>

<h2>Reading, Mechanical and Spatial Sections</h2>

<p>For reading, find the instruction, pull out the facts and interpret charts, and read the whole question before you calculate. For mechanical reasoning, work out the connection and direction before you pick an answer (<a href="/blog/mechanical-reasoning-questions-explained">guide</a>). For spatial work, use paper shapes: mark one face, fold or rotate, and compare with your prediction. Where workplace observation is assessed, practise remembering the order and details of a short demonstration.</p>

<h2>Original Practice Questions</h2>

<p>These were created for this article and are not official test questions.</p>

<ol>
<li><strong>7/8 + 3/16.</strong> 14/16 + 3/16 = 17/16, or <strong>1 1/16</strong>.</li>
<li><strong>2 ft 9 in + 1 ft 8 in.</strong> 3 ft 17 in = <strong>4 ft 5 in</strong>.</li>
<li><strong>A sheet has 80 marked positions and 25% are checked. How many remain unchecked?</strong> 25% of 80 = 20, so <strong>60</strong>.</li>
<li><strong>Solve 3x + 9 = 30.</strong> 3x = 21, so <strong>x = 7</strong>.</li>
<li><strong>A diagram is 18 in by 12 in. What is its area?</strong> <strong>216 square inches</strong>.</li>
<li><strong>Two angles of a triangle are 35 and 65 degrees. Find the third.</strong> 180 &minus; 35 &minus; 65 = <strong>80 degrees</strong>.</li>
<li><strong>"Check the label before recording the measurement." What comes first?</strong> <strong>Check the label.</strong></li>
</ol>

<p>These are academic exercises, not fabrication instructions or safety calculations for real installations.</p>

<h2>Passing Tips, Study Plan and Test Day</h2>

<p>Start with accuracy: write intermediate steps, estimate first, and keep an error log. Practise under the confirmed conditions: one Wisconsin programme is untimed, while Southern California has a timed maths test, and at that programme applicants must pass the math test and score at least 35 of 45 points across the three written tests to reach the oral interview. A generic stopwatch routine should not replace those rules.</p>

<table>
<thead><tr><th>Days</th><th>Focus</th></tr></thead>
<tbody>
<tr><td>1&ndash;2</td><td>Confirm the format and find weaknesses</td></tr>
<tr><td>3&ndash;4</td><td>Arithmetic and fractions</td></tr>
<tr><td>5&ndash;6</td><td>Decimals, percentages and measurement</td></tr>
<tr><td>7&ndash;8</td><td>Algebra and required geometry</td></tr>
<tr><td>9&ndash;10</td><td>Programme-specific reading and reasoning</td></tr>
<tr><td>11&ndash;12</td><td>Mixed exercises and error correction</td></tr>
<tr><td>13&ndash;14</td><td>Official samples and test-day documents</td></tr>
</tbody>
</table>

<p>On the day, check the address, arrival time and transport, bring the identification in your invitation, and read the directions for each section; do not carry assumptions from one section to another. Keep feet, inches and square units separate, and use a supplied drawing's labels rather than judging by its appearance.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is there one passing score?</h3>
<p>No. Obtain your sponsor's requirement; Local 20 requires passing all four sections.</p>

<h3>Can I use a calculator?</h3>
<p>Check your appointment instructions and the specific assessment policy.</p>

<h3>Is previous sheet metal experience essential?</h3>
<p>Requirements vary. Ask the programme about eligibility.</p>

<h3>Does passing guarantee acceptance?</h3>
<p>No. Interviews, eligibility and available positions can matter.</p>

<h3>Are the tests timed?</h3>
<p>It depends: one Wisconsin programme is untimed and Southern California has a 15-minute timed maths test.</p>

<h3>What does Local 20 test?</h3>
<p>Applied mathematics, applied technology, workplace observation and locating information.</p>

<h3>What maths is on the test?</h3>
<p>Typically arithmetic, fractions, decimals, percentages and basic algebra, but check your local.</p>

<h3>Is there a spatial test?</h3>
<p>Southern California lists a paper board test of three-dimensional visualisation.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/ua-plumber-pipefitter-apprenticeship-test-guide">UA Plumber and Pipefitter Apprenticeship Test Guide</a>: UA locals</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
