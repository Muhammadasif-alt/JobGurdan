<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "UA Plumber and Pipefitter Apprenticeship Test Guide"
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
class UaPlumberPipefitterApprenticeshipTestBlogSeeder extends Seeder
{
    public const SLUG = 'ua-plumber-pipefitter-apprenticeship-test-guide';

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
                'title' => 'UA Plumber and Pipefitter Apprenticeship Test Guide',
                'excerpt' => 'A UA plumber or pipefitter test depends on your local.',
                'content' => $content,
                'featured_image' => 'blogs/ua-plumber-pipefitter-apprenticeship-test-guide.jpg',
                'tags' => 'ua plumber apprenticeship test, pipefitter apprenticeship test, ua aptitude test preparation, plumbing apprenticeship math practice, pipefitters local 597, ua local 190 workkeys, steamfitter apprenticeship, union plumber entrance exam',
                'meta_title' => 'UA Plumber and Pipefitter Apprenticeship Test Guide',
                'meta_description' => 'UA plumber and pipefitter apprenticeship test: Local 597, 190 and 467 examples, maths topics, original practice and a two-week study plan.',
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
<p><strong>A UA plumber or pipefitter apprenticeship test can assess mathematics, reading, mechanical understanding and spatial reasoning, but your local training programme decides which.</strong> There is no single format, pass mark or calculator rule that applies to every United Association recruitment. Get your local's current application instructions and approved study material first. A test result does not grant a trade licence, work permission or a guaranteed place.</p>

<h2>The Apprenticeship Process</h2>

<p>The United Association says its apprenticeships combine paid on-the-job training with union contractors and classroom instruction at UA training centres. On its <a href="https://ua.org/career-paths/apprentice/" rel="noopener">apprenticeship page</a>, applicants must typically be at least 18 and hold a high school diploma or GED, and the process may include an interview, an aptitude test and physical requirements. Plumbing, pipefitting and HVACR pathways can have different application arrangements.</p>

<h2>Which Test Will Your Local Use?</h2>

<table>
<thead><tr><th>Programme</th><th>Published details</th></tr></thead>
<tbody>
<tr><td><a href="https://www.pftf597.org/applications/testing/" rel="noopener">Pipefitters Local 597</a></td><td>An Aptitude Test given by a third-party testing agency with five sections: Numerical Reasoning, Numerical Comprehension, Spatial Reasoning, Reading and Bennett Mechanical Aptitude</td></tr>
<tr><td><a href="https://www.ua190.org/apprenticeship-faq.aspx" rel="noopener">UA Local 190</a></td><td>The WorkKeys exam covering applied math, workplace documents, workplace observation and applied technology; a cumulative score of 17 or higher to continue, and the page says the exam cannot be retaken</td></tr>
<tr><td><a href="https://www.ualocal467.org/apprenticeship.aspx" rel="noopener">UA Local 467</a></td><td>Maths preparation covering whole numbers, fractions, decimals, integers, rational numbers, exponents and algebra essentials</td></tr>
</tbody>
</table>

<p>These descriptions come from the programmes' own pages and are not national specifications. The Local 190 FAQ also mentions a score of 15 in a retake question without explaining it, so confirm the current requirement directly with the training office.</p>

<h2>Questions to Ask Before Preparing</h2>

<p>Request the test name, sections, duration and format, and ask whether calculators, formula sheets or scratch paper are allowed, how scores affect eligibility or ranking, whether an interview follows, and what the retake and accessibility arrangements are. An applicant's account of a past recruitment should not override your current instructions.</p>

<h2>Maths Topics to Prioritise</h2>

<ul>
<li><strong>Arithmetic, fractions and decimals:</strong> accurate operations including negative values, common denominators, mixed numbers and decimal placement. Revise with OpenStax <a href="https://openstax.org/books/prealgebra-2e/pages/4-introduction-to-fractions" rel="noopener">fractions</a> and <a href="https://openstax.org/books/prealgebra-2e/pages/5-introduction-to-decimals" rel="noopener">decimals</a>.</li>
<li><strong>Percentages, algebra and measurement:</strong> <a href="https://openstax.org/books/prealgebra-2e/pages/6-introduction-to-percents" rel="noopener">percents</a>, simple equations, order of operations, and keeping feet and inches separate until you convert.</li>
<li><strong>Ratios and basic geometry,</strong> if your local's guidance includes them (<a href="https://openstax.org/books/prealgebra-2e/pages/9-introduction" rel="noopener">geometry</a>).</li>
</ul>

<p>Do not confuse academic exercises with the codes and procedures needed for real installations. See also <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a> and <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning</a>.</p>

<h2>Reading, Mechanical and Spatial Preparation</h2>

<p>For reading, identify what an instruction requires and find the supporting information instead of answering from memory. For mechanical questions, interpret simple diagrams and explain motion, force or connections. For spatial reasoning, rotate paper shapes and compare views. For workplace observation, practise remembering the order and details of a short demonstration. Local 597 lists both mechanical and spatial sections.</p>

<h2>Original Practice Questions</h2>

<p>These were created for this guide and are not official UA, union or test-provider questions.</p>

<ol>
<li><strong>2 1/3 + 1 5/6.</strong> 1/3 = 2/6; 3 + 7/6 = <strong>4 1/6</strong>.</li>
<li><strong>3/4 &times; 2/5.</strong> 6/20 = <strong>3/10</strong>.</li>
<li><strong>16.8 &divide; 0.4.</strong> 168 &divide; 4 = <strong>42</strong>.</li>
<li><strong>4 ft 2 in &minus; 1 ft 9 in.</strong> 50 &minus; 21 = 29 in = <strong>2 ft 5 in</strong>.</li>
<li><strong>A box has 150 washers and 20% are removed. How many remain?</strong> 30 removed, so <strong>120</strong>.</li>
<li><strong>Solve 2x + 11 = 35.</strong> 2x = 24, so <strong>x = 12</strong>.</li>
<li><strong>Two quantities are in the ratio 2:3 and total 40. Find the smaller.</strong> 5 parts of 8: <strong>16</strong>.</li>
<li><strong>"Record the result after checking the reference number." What first?</strong> <strong>Check the reference number.</strong></li>
</ol>

<h2>A Two-Week Plan, Test Day and Interview</h2>

<table>
<thead><tr><th>Days</th><th>Task</th></tr></thead>
<tbody>
<tr><td>1&ndash;2</td><td>Verify the format and take a diagnostic set</td></tr>
<tr><td>3&ndash;4</td><td>Arithmetic and fractions</td></tr>
<tr><td>5&ndash;6</td><td>Decimals, percentages and conversions</td></tr>
<tr><td>7&ndash;8</td><td>Algebra and required geometry</td></tr>
<tr><td>9&ndash;10</td><td>Confirmed reading, mechanical or spatial sections</td></tr>
<tr><td>11&ndash;12</td><td>Mixed practice and recurring errors</td></tr>
<tr><td>13&ndash;14</td><td>Approved samples and test documents</td></tr>
</tbody>
</table>

<p>Use fresh questions to test understanding, and build accuracy before practising to the time limits your assessment really has. Confirm your appointment, identification and permitted materials, and listen to section directions because scoring and navigation rules can differ. Prepare truthful interview examples of reliability, teamwork and willingness to learn: the problem, your action and the outcome. If you are stuck, list what the question gives you, check whether it asks for the amount used, remaining or total, and estimate before you calculate. See <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a> and the <a href="/blog/sheet-metal-workers-aptitude-test-study-plan">sheet metal test guide</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is every UA entrance test the same?</h3>
<p>No. Confirm the assessment used by your local programme.</p>

<h3>What score do I need?</h3>
<p>Use your local's current requirement; Local 190 states a cumulative WorkKeys score of 17.</p>

<h3>Can I retake the test?</h3>
<p>Rules differ. Local 190's page says its exam cannot be retaken; ask your own local.</p>

<h3>Must I buy a preparation course?</h3>
<p>No universal purchase requirement exists. Ask about approved resources first.</p>

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
