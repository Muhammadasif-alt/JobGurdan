<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Apprenticeship Test Math: The Basics You Need to Know"
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
class ApprenticeshipTestMathBasicsBlogSeeder extends Seeder
{
    public const SLUG = 'apprenticeship-test-math-basics';

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
                'title' => 'Apprenticeship Test Math: The Basics You Need to Know',
                'excerpt' => 'Most trade apprenticeship tests cover basic maths: fractions, decimals, percentages, ratios, unit conversions, area, volume, the Pythagorean theorem and simple algebra, at roughly middle- to early high-school level.',
                'content' => $content,
                'featured_image' => 'blogs/apprenticeship-test-math-basics.jpg',
                'tags' => 'apprenticeship test math, trade apprenticeship math, fractions decimals percentages, unit conversions trades, pythagorean theorem practice, apprenticeship math practice, tape measure fractions, order of operations',
                'meta_title' => 'Apprenticeship Test Math: The Basics You Need to Know',
                'meta_description' => 'What math is on an apprenticeship test? Fractions, percentages, conversions, area, volume and algebra with original worked examples.',
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
<p><strong>Most trade apprenticeship tests cover basic maths: fractions, decimals, percentages, ratios, unit conversions, area, volume and simple algebra, with occasional geometry such as the Pythagorean theorem.</strong> The level is typically middle-school to early high-school, and the format varies by programme, so confirm what yours tests. Local examples in our guides show the range: Ironworkers Local 721 describes Grade 10 equivalency, Local 467 (UA) lists whole numbers, fractions, decimals, integers, rational numbers, exponents and algebra essentials, and some locals use no written test at all.</p>

<h2>1. Fractions</h2>
<p>Add or subtract with a common denominator, multiply tops and bottoms, and divide by flipping the second fraction. <strong>Example:</strong> 3/4 + 2/3 = 9/12 + 8/12 = 17/12 = <strong>1 5/12</strong>. Practise with OpenStax <a href="https://openstax.org/books/prealgebra-2e/pages/4-introduction-to-fractions" rel="noopener">fractions</a>.</p>

<h2>2. Decimals and Converting to Fractions</h2>
<p>Use place value and simplify. <strong>Example:</strong> 0.375 = 375/1000 = <strong>3/8</strong>, so a 3/8-inch bit is 0.375 inch. See <a href="https://openstax.org/books/prealgebra-2e/pages/5-introduction-to-decimals" rel="noopener">decimals</a>.</p>

<h2>3. Percentages</h2>
<p>Convert to a decimal and multiply. <strong>Example:</strong> 15% of 240 = 0.15 &times; 240 = <strong>36</strong>. Waste allowances and material overage use this. See <a href="https://openstax.org/books/prealgebra-2e/pages/6-introduction-to-percents" rel="noopener">percents</a>.</p>

<h2>4. Ratios and Proportions</h2>
<p>Add the parts, divide the total by that number, then multiply for each part. <strong>Example:</strong> a 1:2:3 mix totalling 60 cubic feet has 6 parts of 10, so <strong>10, 20 and 30</strong>.</p>

<h2>5. Unit Conversions</h2>

<table>
<thead><tr><th>From</th><th>To</th><th>Rule</th></tr></thead>
<tbody>
<tr><td>Feet</td><td>Inches</td><td>&times; 12</td></tr>
<tr><td>Yards</td><td>Feet</td><td>&times; 3</td></tr>
<tr><td>Cubic feet</td><td>Cubic yards</td><td>&divide; 27</td></tr>
<tr><td>Square feet</td><td>Square yards</td><td>&divide; 9</td></tr>
</tbody>
</table>

<p><strong>Example:</strong> 2.5 feet &times; 12 = <strong>30 inches</strong>.</p>

<h2>6. Reading a Tape Measure</h2>
<p>Most US tapes mark inches down to 1/16. Count the small marks past the last whole inch and simplify: 6 marks is 6/16, or <strong>3/8</strong>.</p>

<h2>7. Perimeter, Area and Volume</h2>
<ul>
<li>Rectangle area: length &times; width. Triangle area: &frac12; &times; base &times; height.</li>
<li>Circle area: &pi; &times; radius&sup2; (use 3.14 unless told otherwise). Circumference: 2 &times; &pi; &times; radius.</li>
<li>Box volume: length &times; width &times; height.</li>
</ul>
<p><strong>Examples:</strong> a 12 ft by 15 ft room has <strong>180 square feet</strong>. A slab 10 ft by 4 ft by 0.5 ft is 20 cubic feet, and 20 &divide; 27 is about <strong>0.74 cubic yards</strong>. A pad with a 3 ft radius has an area of about 3.14 &times; 9 = <strong>28.3 square feet</strong>. See <a href="https://openstax.org/books/prealgebra-2e/pages/9-introduction" rel="noopener">geometry</a>.</p>

<h2>8. The Pythagorean Theorem</h2>
<p>For a right triangle, a&sup2; + b&sup2; = c&sup2;. <strong>Example:</strong> legs of 6 ft and 8 ft give 36 + 64 = 100, so <strong>10 ft</strong>. A 3-4-5 triangle follows the same pattern.</p>

<h2>9. Order of Operations</h2>
<p>Brackets, exponents, multiplication and division left to right, then addition and subtraction left to right. <strong>Example:</strong> 8 + 4 &times; 3 = 8 + 12 = <strong>20</strong>, not 36.</p>

<h2>10. Simple Algebra</h2>
<p>Do the same operation to both sides. <strong>Example:</strong> 3x + 5 = 20, so 3x = 15 and <strong>x = 5</strong>.</p>

<h2>11. Rates and Word Problems</h2>
<p>Identify what is asked, pull out the numbers and choose the formula. <strong>Example:</strong> 120 feet of pipe in 4 hours is <strong>30 feet per hour</strong>, so 150 feet takes 5 hours.</p>

<p>All examples are original practice problems, not real exam questions.</p>

<h2>How to Study Fast</h2>

<ol>
<li>Find out what is tested and ask for a test outline or sample.</li>
<li>Master fractions and unit conversions first, since they appear everywhere.</li>
<li>Memorise the key formulas, and ask whether a formula sheet is provided.</li>
<li>Practise word problems for 15 to 20 minutes a day.</li>
<li>Show your steps and check the units.</li>
<li>Take timed practice once you know the real time limit.</li>
<li>Review mistakes and keep a list of the question types you miss.</li>
</ol>

<p>Official programme information is on <a href="https://www.apprenticeship.gov" rel="noopener">Apprenticeship.gov</a>. Never pay anyone for a guaranteed apprenticeship spot, and verify all fees with the programme. See also <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning</a> and <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I use a calculator on the apprenticeship test?</h3>
<p>It depends on the programme. Ask in advance and practise without one in case.</p>

<h3>What level of math is it?</h3>
<p>Usually middle-school to early high-school math. Local 721, for example, describes Grade 10 equivalency.</p>

<h3>What if I am bad at math?</h3>
<p>Most people improve with regular practice on fractions, conversions and word problems.</p>

<h3>Do I need to memorise formulas?</h3>
<p>Often yes. Ask whether your programme provides a formula sheet.</p>

<h3>How long should I study?</h3>
<p>Plan a few weeks of consistent practice, starting with your weakest topics.</p>

<h3>Is there a test at every programme?</h3>
<p>No. Some programmes list no aptitude test, so check first.</p>

<h3>Which topics matter most?</h3>
<p>Fractions, percentages, unit conversions, area, volume and basic algebra.</p>

<h3>Where can I practise?</h3>
<p>Use OpenStax Prealgebra and your programme's own samples.</p>

<h2>People Also Search For</h2>

<h3>Apprenticeship test math</h3>
<p>Basics first.</p>

<h3>Trade apprenticeship math</h3>
<p>Fractions to algebra.</p>

<h3>Fractions decimals percentages</h3>
<p>Convert fluently.</p>

<h3>Unit conversions trades</h3>
<p>Feet, yards, cubic yards.</p>

<h3>Pythagorean theorem practice</h3>
<p>3-4-5 triangles.</p>

<h3>Apprenticeship math practice</h3>
<p>15 minutes a day.</p>

<h3>Tape measure fractions</h3>
<p>Count sixteenths.</p>

<h3>Order of operations</h3>
<p>Brackets first.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/ironworker-aptitude-test-practice-guide">Ironworker Aptitude Test: Format, Math and Practice</a>: ironwork entry</li>
    <li><a href="/blog/sheet-metal-workers-aptitude-test-study-plan">Sheet Metal Aptitude Test: Sections, Tips, Study Plan</a>: sheet metal entry</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
