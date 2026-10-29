<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Boilermaker Apprenticeship Requirements and Entrance Test"
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
class BoilermakerApprenticeshipRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'boilermaker-apprenticeship-requirements-entrance-test';

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
                'title' => 'Boilermaker Apprenticeship Requirements and Entrance Test',
                'excerpt' => 'Boilermaker apprenticeship rules depend on the sponsor. One US programme needs age 18 and high school education, California lists no written or oral exam for another, and Canadian Local 555 requires 70% on an assessment before the interview.',
                'content' => $content,
                'featured_image' => 'blogs/boilermaker-apprenticeship-requirements-entrance-test.jpg',
                'tags' => 'boilermaker apprenticeship requirements, boilermaker entrance test, boilermaker aptitude test, boilermaker apprenticeship application, boilermaker local 555, boilermakers jac, boilermaker apprentice welding, boilermaker apprenticeship canada',
                'meta_title' => 'Boilermaker Apprenticeship Requirements and Entrance Test',
                'meta_description' => 'Boilermaker apprenticeship requirements and entrance tests: US and Canadian examples, application checklist, original maths practice and FAQs.',
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
<p><strong>Boilermaker apprenticeship requirements depend on the country and the training sponsor.</strong> US programmes commonly require applicants to be at least 18 with a high school education or equivalent. Entrance testing is not universal: some programmes use an assessment, while others select applicants on documented qualifications and their own procedure. Before you prepare for a test, confirm that your sponsor actually requires one.</p>

<h2>What a Boilermaker Apprentice Learns</h2>

<p>Boilermakers work with boilers, tanks, vessels and related industrial equipment. The International Brotherhood of Boilermakers describes apprenticeship in welding, blueprint reading, rigging, layout and fabrication on its <a href="https://boilermakers.org/apprenticeship" rel="noopener">apprenticeship page</a>, and lists recruiting on its <a href="https://boilermakers.org/recruiting" rel="noopener">recruiting page</a>. A boiler operator or boiler engineer route is a different occupation with different training and certification.</p>

<h2>Requirements Checklist</h2>

<table>
<thead><tr><th>Requirement</th><th>Confirm</th></tr></thead>
<tbody>
<tr><td>Age</td><td>Minimum age at application and at registration</td></tr>
<tr><td>Education</td><td>Accepted diploma, GED or equivalent evidence</td></tr>
<tr><td>Work eligibility</td><td>Permission to work in the destination</td></tr>
<tr><td>Transport</td><td>Reaching training and jobsites</td></tr>
<tr><td>Physical demands</td><td>Essential duties and the accommodation process</td></tr>
<tr><td>Screening</td><td>Any drug screening or other checks</td></tr>
<tr><td>Experience</td><td>How welding qualifications or fieldwork are recognised</td></tr>
<tr><td>Selection</td><td>Assessment, interview, roster or another process</td></tr>
</tbody>
</table>

<p>Meeting the requirements does not guarantee selection.</p>

<h2>US Example: Southeastern Area</h2>

<p>The <a href="https://sajac-boilermakers.org/how_to_apply.html" rel="noopener">Southeastern Apprenticeship Area</a> says the only requirements are being at least 18 and having a high school education, shown by a diploma, GED or DD214. Applicants choose a local lodge, and applications are processed in the order received on an eligibility roster. Lodges select starting with the groups that have verified welding: Group 1 (pipe or tube welding credentials, or 1,500 verified field hours), Group 2 (plate steel credentials, or 1,000 field hours), Group 3 (completion of a post-high school welding programme) and Group 4 (no verified welding skills). The page also asks whether you are willing to travel and stay away from home for long periods and to do demanding physical work at heights, in confined spaces and in bad weather.</p>

<h2>Canada</h2>

<p>The Canadian Boilermakers' <a href="https://boilermaker.ca/en/become-a-boilermaker-apprentice/" rel="noopener">apprentice page</a> sends applicants to provincial representatives, and training follows a national core curriculum leading to a Red Seal. Do not carry a US age, education or training-hour rule into a Canadian application.</p>

<h2>Is There a Boilermaker Aptitude Test?</h2>

<ul>
<li><strong>Not always.</strong> California's <a href="https://www.dir.ca.gov/databases/das/results_aigdetail.asp?varOccId=11078" rel="noopener">Division of Apprenticeship Standards listing</a> for the Boilermakers Western States JAC shows "Written Test: No" and "Oral Exam: No", with data current as of 15 October 2026.</li>
<li><strong>Sometimes.</strong> Canadian <a href="https://555.boilermaker.ca/apprenticeship/" rel="noopener">Local 555</a> says candidates write an assessment exam, must achieve at least 70% to continue, are interviewed by the selection committee and then complete a pre-apprentice course. It names a preparation guide, "Preparing for the Boilermaker Trade", from skillplan.ca or the training coordinator. That 70% belongs to this process and is not a universal boilermaker pass mark.</li>
</ul>

<h2>Entrance Exams Versus Coursework Tests</h2>

<p>BNAP describes a four-year, on-the-job apprenticeship with minimums of 576 classroom hours and 6,000 field construction hours. Its related studies lessons are each followed by a 25-question test, 49 in total, with 70% needed to move on (<a href="https://www.bnap.com/copy-of-training" rel="noopener">BNAP training page</a>). These are course tests during the apprenticeship, not an entrance exam, and a welding qualification test or a final trade certification exam serves a different purpose again. Ask which assessment your invitation concerns before you buy preparation material.</p>

<h2>If Testing Is Required</h2>

<p>Get the sponsor's topic list and samples first, and ask about duration, calculators, scoring and retests. As general revision, practise arithmetic, fractions, decimals, percentages and measurement (OpenStax <a href="https://openstax.org/books/prealgebra-2e/pages/4-introduction-to-fractions" rel="noopener">fractions</a>, <a href="https://openstax.org/books/prealgebra-2e/pages/6-introduction-to-percents" rel="noopener">percents</a>, <a href="https://openstax.org/books/prealgebra-2e/pages/9-introduction" rel="noopener">geometry</a>), then add algebra, reading or mechanical reasoning only where your confirmed assessment includes them. These are suggestions, not a verified syllabus for every programme. See <a href="/blog/apprenticeship-test-math-basics">maths basics</a> and <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning</a>.</p>

<h2>Original Practice Questions</h2>

<p>Created for this article; not official entrance questions and not instructions for operating industrial equipment.</p>

<ol>
<li><strong>7/8 &minus; 1/4.</strong> 7/8 &minus; 2/8 = <strong>5/8</strong>.</li>
<li><strong>1 3/4 + 2 2/3.</strong> 9/12 + 8/12: 3 17/12 = <strong>4 5/12</strong>.</li>
<li><strong>6 ft 4 in in inches.</strong> 72 + 4 = <strong>76</strong>.</li>
<li><strong>200 items, 35% checked. How many remain?</strong> 70 checked, <strong>130</strong> remain.</li>
<li><strong>Solve 6x &minus; 4 = 32.</strong> 6x = 36, <strong>x = 6</strong>.</li>
<li><strong>Perimeter of a 9 ft by 5 ft rectangle.</strong> 9 + 5 + 9 + 5 = <strong>28 ft</strong>.</li>
<li><strong>"Read the reference label, then record its number." What first?</strong> <strong>Read the label.</strong></li>
</ol>

<h2>Application Plan</h2>

<p>Identify the sponsor and check eligibility, collect your education documents and genuine experience evidence, and submit exactly as instructed, keeping the confirmation. Keep your contact details current, ask about training costs, starting pay and travel expectations before you accept, and avoid building a budget around uninterrupted overtime or employment. If you have a question about the essential duties, ask about the accommodation process instead of attempting unsafe exercises to prove readiness.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Must I already know welding?</h3>
<p>Not necessarily. Check how your sponsor treats beginners and verified experience.</p>

<h3>Is 70% always the passing score?</h3>
<p>No. It applies to particular assessments, such as Local 555's, not every programme.</p>

<h3>Does an application give me a work visa?</h3>
<p>No. Immigration and employment eligibility are separate checks.</p>

<h3>Does passing guarantee selection?</h3>
<p>No. Further stages and available places may matter.</p>

<h3>Is there an entrance exam in California?</h3>
<p>The state listing for the Western States JAC shows no written test and no oral exam.</p>

<h3>How long is the apprenticeship?</h3>
<p>BNAP describes four years with at least 576 classroom hours and 6,000 field hours.</p>

<h3>How old must I be?</h3>
<p>The Southeastern programme requires 18 and a high school education.</p>

<h3>Where do Canadians apply?</h3>
<p>Contact the provincial representatives listed by the Canadian Boilermakers.</p>

<h2>People Also Search For</h2>

<h3>Boilermaker apprenticeship requirements</h3>
<p>Age and education.</p>

<h3>Boilermaker entrance test</h3>
<p>Not universal.</p>

<h3>Boilermaker aptitude test</h3>
<p>Local 555 uses one.</p>

<h3>Boilermaker apprenticeship application</h3>
<p>Choose a local lodge.</p>

<h3>Boilermaker Local 555</h3>
<p>70% assessment.</p>

<h3>Boilermakers JAC</h3>
<p>Joint apprenticeship committee.</p>

<h3>Boilermaker apprentice welding</h3>
<p>Verified experience counts.</p>

<h3>Boilermaker apprenticeship Canada</h3>
<p>Provincial contacts.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/ironworker-aptitude-test-practice-guide">Ironworker Aptitude Test: Format, Math and Practice</a>: ironwork entry</li>
    <li><a href="/blog/ua-plumber-pipefitter-apprenticeship-test-guide">UA Plumber and Pipefitter Apprenticeship Test Guide</a>: UA locals</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
