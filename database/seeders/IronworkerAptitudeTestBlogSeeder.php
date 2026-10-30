<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Ironworker Aptitude Test: Format, Math and Practice"
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
class IronworkerAptitudeTestBlogSeeder extends Seeder
{
    public const SLUG = 'ironworker-aptitude-test-practice-guide';

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
                'title' => 'Ironworker Aptitude Test: Format, Math and Practice',
                'excerpt' => 'There is no single ironworker aptitude test. Each local sets its own: Local 721 uses mechanical aptitude and math tests at Grade 10 level with a 70% minimum, Ironworkers 512 uses a 60-minute online assessment, and some Minnesota programmes use none.',
                'content' => $content,
                'featured_image' => 'blogs/ironworker-aptitude-test-practice-guide.jpg',
                'tags' => 'ironworker aptitude test, ironworker apprenticeship test, ironworker math test, ironworker aptitude practice, apprenticeship entrance test, ironworkers local 721, construction trades test, trade apprenticeship math',
                'meta_title' => 'Ironworker Aptitude Test: Format, Math and Practice',
                'meta_description' => 'Ironworker aptitude test: local formats (Local 721 70% minimum, Ironworkers 512 online test), maths topics, solved practice questions and a study plan.',
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
<p><strong>An ironworker aptitude test can assess the foundational skills needed for apprenticeship, but no single format is used by every training programme.</strong> Your local sponsor decides whether testing is required, which sections it has, the timing, calculator rules and the selection criteria. Start with its current applicant instructions, then practise arithmetic, fractions and measurement. Passing a test does not certify you to do ironwork and does not guarantee an apprenticeship place.</p>

<h2>Is a Test Required Everywhere?</h2>

<ul>
<li><strong>Ironworkers Local 721 (Ontario).</strong> The <a href="https://iw721.org/training/apprenticeship/ironworkers/" rel="noopener">Local 721 apprenticeship page</a> says the application is done together with a mechanical aptitude test and a math test, both based on Grade 10 equivalency, and applicants need a minimum of 70% on both tests to be placed on file for potential referral.</li>
<li><strong>Ironworkers 512 (Minnesota).</strong> The Minnesota Department of Labor and Industry's <a href="https://www.dli.mn.gov/business/workforce/ironworker-apprenticeships" rel="noopener">ironworker apprenticeship listing</a> says applicants take a 60-minute online assessment of aptitude, personality and cognitive capabilities during a testing appointment.</li>
<li><strong>Another Minnesota programme.</strong> The same listing says that the Associated Builders and Contractors programme has no application fees or aptitude tests.</li>
</ul>

<p>These are examples, not a nationwide specification. Do not assume the 70% mark of one local applies to yours.</p>

<h2>What to Confirm About the Format</h2>

<table>
<thead><tr><th>Detail</th><th>Question to ask the apprenticeship office</th></tr></thead>
<tbody>
<tr><td>Sections</td><td>Which abilities are assessed?</td></tr>
<tr><td>Delivery</td><td>Paper or computer?</td></tr>
<tr><td>Timing</td><td>A total limit or a limit per section?</td></tr>
<tr><td>Equipment</td><td>Are calculators and scratch paper allowed?</td></tr>
<tr><td>Scoring</td><td>Is there a qualifying score or a ranking?</td></tr>
<tr><td>Retesting</td><td>When can an unsuccessful applicant try again?</td></tr>
<tr><td>Accessibility</td><td>How do I request an accommodation?</td></tr>
</tbody>
</table>

<p>Ask for the current candidate notice or an official sample before you use commercial practice material. The Iron Workers International website, <a href="https://www.ironworkers.org" rel="noopener">ironworkers.org</a>, is the starting point for finding a local.</p>

<h2>Maths Topics Worth Reviewing</h2>

<p>These topics are a practical foundation, not a confirmed syllabus for every ironworker test.</p>

<ul>
<li><strong>Arithmetic and order of operations,</strong> including negative numbers and brackets. Estimate the answer first so you can spot an unreasonable result.</li>
<li><strong>Fractions, decimals and percentages.</strong> Revise with OpenStax Prealgebra: <a href="https://openstax.org/books/prealgebra-2e/pages/4-introduction-to-fractions" rel="noopener">fractions</a>, <a href="https://openstax.org/books/prealgebra-2e/pages/5-introduction-to-decimals" rel="noopener">decimals</a> and <a href="https://openstax.org/books/prealgebra-2e/pages/6-introduction-to-percents" rel="noopener">percents</a>.</li>
<li><strong>Measurement, ratios and geometry:</strong> feet and inches, scale drawings, perimeter, area and right triangles (<a href="https://openstax.org/books/prealgebra-2e/pages/9-introduction" rel="noopener">geometry chapter</a>). Write the unit with every answer.</li>
<li><strong>Basic algebra and word problems:</strong> do the same operation to both sides, identify the unknown, and check the answer fits the question.</li>
</ul>

<h2>Original Practice Questions With Answers</h2>

<p>These were written for this guide. They are not official test items and do not predict your sponsor's exam. Try each before reading the answer.</p>

<ol>
<li><strong>What is 3/4 + 5/8?</strong> Convert 3/4 to 6/8, then 6/8 + 5/8 = 11/8, or <strong>1 3/8</strong>.</li>
<li><strong>Find 7 1/2 &minus; 2 3/4.</strong> Rewrite 7 1/2 as 7 2/4, borrow to get 6 6/4, subtract 2 3/4: <strong>4 3/4</strong>.</li>
<li><strong>How many inches is 5 feet 7 inches?</strong> 5 &times; 12 = 60, plus 7 = <strong>67 inches</strong>.</li>
<li><strong>A box holds 240 bolts and 15% are set aside. How many remain?</strong> 0.15 &times; 240 = 36, so 240 &minus; 36 = <strong>204</strong>.</li>
<li><strong>A rectangle is 12 ft by 8 ft. What is its area?</strong> 12 &times; 8 = <strong>96 square feet</strong>.</li>
<li><strong>Solve 4x + 7 = 31.</strong> 4x = 24, so <strong>x = 6</strong> (check: 4 &times; 6 + 7 = 31).</li>
<li><strong>On a drawing 1 inch is 4 feet. What does 3.5 inches represent?</strong> 3.5 &times; 4 = <strong>14 feet</strong>.</li>
<li><strong>A right triangle has sides 6 ft and 8 ft. How long is the hypotenuse?</strong> 36 + 64 = 100, so <strong>10 feet</strong>.</li>
</ol>

<p>These are arithmetic exercises, not instructions for designing structures or calculating safe lifting loads.</p>

<h2>Mechanical Reasoning and Reading</h2>

<p>If your programme includes mechanical reasoning, practise reading diagrams of gears, levers and simple machines (see our <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning guide</a>). For reading tasks, find the instruction, the relevant facts and the sequence, and answer from the passage rather than from experience.</p>

<h2>A Seven-Day Plan and Test-Day Tips</h2>

<ol>
<li>Check the format and take a diagnostic set.</li>
<li>Arithmetic and order of operations.</li>
<li>Fractions and mixed numbers.</li>
<li>Decimals, percentages and measurement.</li>
<li>Algebra and geometry as required.</li>
<li>Programme-specific reading or mechanical exercises.</li>
<li>Review your mistakes and organise your documents.</li>
</ol>

<p>Keep an error log that separates misunderstanding from arithmetic slips. Time yourself only after you know the real conditions. On the day, bring the identification listed in your invitation, confirm the location and reporting time, and ask early about any accommodation. Eight right answers here show success on eight exercises only, so use a fresh set to check you understand the method rather than remember the answers. See also <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a> and <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is there a single ironworker aptitude test?</h3>
<p>No. Each local or programme decides whether to test and what the test contains.</p>

<h3>What score do I need for Ironworkers Local 721?</h3>
<p>Its page states a minimum of 70% on both the mechanical aptitude and the math test; other locals differ.</p>

<h3>Can I use a calculator?</h3>
<p>Follow your sponsor's policy. Do not assume permission.</p>

<h3>Does passing guarantee admission?</h3>
<p>No. Other selection stages and available places can matter.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/sheet-metal-workers-aptitude-test-study-plan">Sheet Metal Aptitude Test: Sections, Tips, Study Plan</a>: sheet metal entry</li>
</ul>

<p><em>Reviewed on 16 October 2026.</em></p>

HTML;
    }
}
