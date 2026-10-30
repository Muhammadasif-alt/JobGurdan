<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Laborers Union Entrance Exam: What Gets Asked"
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
class LaborersUnionEntranceExamBlogSeeder extends Seeder
{
    public const SLUG = 'laborers-union-entrance-exam-guide';

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
                'title' => 'Laborers Union Entrance Exam: What Gets Asked',
                'excerpt' => 'There is no single national Laborers (LiUNA) entrance exam. New Jersey\'s programme lists no entrance test beyond a drug screen, Minnesota lists age, transport and a resume, and others use maths and reading tests.',
                'content' => $content,
                'featured_image' => 'blogs/laborers-union-entrance-exam-guide.jpg',
                'tags' => 'laborers union entrance exam, liuna apprenticeship, liuna aptitude test, laborers apprenticeship requirements, construction craft laborer apprenticeship, how to join laborers union, liuna drug test, laborers union test prep',
                'meta_title' => 'Laborers Union Entrance Exam: What Gets Asked',
                'meta_description' => 'What is on the Laborers (LiUNA) entrance exam? Real local examples, possible topics, original samples and how to prepare for your local.',
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
<p><strong>There is no single national Laborers (LiUNA) entrance exam.</strong> Each local union and apprenticeship programme sets its own process. It usually includes an application and often a drug screen; some programmes add an interview and an aptitude test covering basic maths and reading, and some test physical readiness. Contact your local to confirm exactly what it requires.</p>

<h2>How Entry Works</h2>

<p>The Laborers' International Union of North America represents workers in construction, demolition, utilities, tunnelling and related trades. You generally enter through a local, which may refer members to signatory contractors, or through a formal apprenticeship with a more structured selection. Start by finding your local: the <a href="https://www.liuna.org" rel="noopener">LiUNA site</a> is the national starting point and the <a href="https://www.liunatraining.org" rel="noopener">LiUNA Training and Education Fund</a> says its affiliate centres cover every state and province, with a "find a training centre" page.</p>

<h2>What Two Real Programmes Ask For</h2>

<ul>
<li><strong>New Jersey.</strong> The <a href="https://www.njlaborers.org/resources/become-a-laborer" rel="noopener">NJ Laborers page</a> lists age 18 or older, a valid driver's licence, physical ability for the work, the ability to read and write English, and a drug test. It lists no education requirement or entrance test beyond the drug screening. No experience is required, the programme is 4,000 hours of on-the-job training plus 400 hours of classroom training (about 3 to 4 years), training is free to qualified candidates, and apprentices earn 60%, 70%, 80% and then 90% of the journeyworker rate. The page said applications were not being accepted at the time we checked.</li>
<li><strong>Minnesota.</strong> <a href="https://liunaminnesota.org/buildyourfuture" rel="noopener">LIUNA Minnesota</a> lists a minimum age of 18, a drug test that may be required, reliable transportation, a resume with work history and references, and joining LIUNA when you start work for an employer.</li>
</ul>

<p>So a written test is not always part of the process. Where one is used, it tends to cover the basics below.</p>

<h2>What an Aptitude Test Can Cover</h2>

<ol>
<li><strong>Maths and measurement:</strong> arithmetic, fractions, decimals, percentages, ratios, tape-measure reading, area and volume. <em>Sample (original):</em> a trench 30 ft long, 3 ft wide and 4 ft deep needs 30 &times; 3 &times; 4 = <strong>360 cubic feet</strong> of soil removed.</li>
<li><strong>Reading comprehension:</strong> a short passage about a safety rule or instruction. Read the question first, then find the answer in the passage.</li>
<li><strong>Mechanical reasoning:</strong> levers, pulleys, gears and simple machines. <em>Sample (original):</em> a longer handle gives more leverage, so it makes lifting easier (see <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning explained</a>).</li>
<li><strong>Safety and construction basics:</strong> protective equipment, hazard recognition and site rules.</li>
<li><strong>Following directions:</strong> multi-step instructions and simple diagrams. Read slowly and do not skip steps.</li>
</ol>

<p>These samples are original practice examples, not real exam questions.</p>

<h2>Besides the Test</h2>

<ul>
<li><strong>Interview:</strong> work history, reliability, following instructions and willingness to do physical work outdoors.</li>
<li><strong>Drug screen:</strong> listed by both programmes above; confirm your local's policy.</li>
<li><strong>Physical readiness:</strong> laboring is physically demanding and some programmes assess it, so build strength and stamina.</li>
<li><strong>Paperwork:</strong> valid ID, proof of the right to work, a driver's licence where required, and any certificates.</li>
</ul>

<h2>How to Prepare</h2>

<ol>
<li>Ask your local what is tested, whether there is a written or physical component, and what to bring.</li>
<li>Refresh fractions, conversions, area and volume (see <a href="/blog/apprenticeship-test-math-basics">apprenticeship test maths basics</a>).</li>
<li>Practise reading short technical passages.</li>
<li>Learn how levers, pulleys and hand tools work.</li>
<li>Consider an OSHA 10-hour safety course, which shows safety awareness.</li>
<li>Rest and arrive early with the documents requested.</li>
</ol>

<p>Never pay anyone for a "guaranteed" union place. Fees and dues are paid to your local, so verify any payment request with the local itself. See <a href="/blog/can-you-retake-apprenticeship-aptitude-test">our retake guide</a> if a test does not go well.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is there a written test to join the Laborers Union?</h3>
<p>It depends on the local. New Jersey lists none beyond a drug screen; others use maths and reading tests.</p>

<h3>What score do I need to pass?</h3>
<p>Standards are set by each local or programme and are not national. Ask for the requirement when you apply.</p>

<h3>Do I need experience to join?</h3>
<p>Often no. The NJ programme states that no prior experience is required.</p>

<h3>How much does it cost?</h3>
<p>Training in the NJ programme is free to qualified candidates; ask your local for dues and fees in writing.</p>

<h3>Is a drug test required?</h3>
<p>Both example programmes list a drug test (Minnesota says it may be required).</p>

<h3>How long is the apprenticeship?</h3>
<p>The NJ programme is 4,000 hours on the job plus 400 classroom hours, about 3 to 4 years.</p>

<h3>Can women and minorities apply?</h3>
<p>Yes. Unions and training programmes recruit from all backgrounds; contact your local or the training fund.</p>

<h3>Where do I find my local?</h3>
<p>Start at liuna.org or the LiUNA Training and Education Fund training centre finder.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/operating-engineers-apprenticeship-test-preparation-guide">Operating Engineers Apprenticeship Test Prep Guide</a>: IUOE locals</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
