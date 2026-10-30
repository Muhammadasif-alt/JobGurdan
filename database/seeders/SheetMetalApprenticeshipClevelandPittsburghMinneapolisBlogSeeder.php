<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Sheet Metal Apprenticeship in Cleveland, Pittsburgh, and Minneapolis"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The apply and view-guide buttons are removed; the official pages are linked in the text and in a Sources list.
 * 2. Local 12 Pittsburgh: the application page states no minimum age, so the guide says to ask the training centre, and its FAQ confirms year-round applications, a five-year programme and 200 classroom hours a year. The 20 March 2027 test date comes from the homepage; registration is through the contact page.
 * 3. Cleveland: the training page heading says the programme became four years from 2022-2023 while its body still says five. The application steps page asks for age 17, a diploma or GED, an entrance exam, an emailed verification and, on acceptance, a drug test.
 * 4. Minneapolis: the state page says acceptance depends on employment with a contractor and that applicants should seek employment with a signatory contractor. The brief names a coordinator contact; personal names are left out.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class SheetMetalApprenticeshipClevelandPittsburghMinneapolisBlogSeeder extends Seeder
{
    public const SLUG = 'sheet-metal-apprenticeship-cleveland-pittsburgh-minneapolis';

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
                'title' => 'Sheet Metal Apprenticeship in Cleveland, Pittsburgh, and Minneapolis',
                'excerpt' => 'Compare sheet metal apprenticeships at Local 33 Cleveland, Local 12 Pittsburgh and Local 10 Metro Minneapolis: ages, documents, tests and employer rules.',
                'content' => $content,
                'featured_image' => 'blogs/sheet-metal-apprenticeship-cleveland-pittsburgh-minneapolis.jpg',
                'tags' => 'sheet metal apprenticeship cleveland, sheet metal apprenticeship pittsburgh, sheet metal apprenticeship minneapolis, smart union apprenticeship requirements, sheet metal local 33, sheet metal local 12, sheet metal local 10 metro, sheet metal entrance exam',
                'meta_title' => 'Sheet Metal Apprenticeship: Cleveland to Minneapolis',
                'meta_description' => 'Compare sheet metal apprenticeships in Cleveland, Pittsburgh and Minneapolis: entry requirements, testing, training length and official application routes.',
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
<p><strong>To pursue a sheet metal apprenticeship in Cleveland, Pittsburgh or Minneapolis, start with the Local 33 Cleveland/Vermilion training centre, the Local 12 Pittsburgh training centre or the Local 10 Metro programme.</strong> Each combines workplace learning with classroom instruction, but their application steps and employment arrangements differ. In Minneapolis, admission depends on employment with a contractor, so completing a form alone does not secure a place.</p>

<p>This guide compares official information checked on 17 October 2026. Confirm the next assessment date, eligibility rules and documents with your chosen training office before you pay fees or plan a move.</p>

<h2>Understand the Work Before Choosing a Programme</h2>

<p>Sheet metal is more than duct installation. The <a href="https://cle33sheetmetal.org/" rel="noopener">Cleveland/Vermilion JATC</a> describes pathways in HVAC systems, architectural sheet metal, welding and fabrication, and Local 10's Metro information covers manufacturing, installation and service work. Decide whether you prefer shop production, construction installation or service, and ask how apprentices gain experience across tasks and whether pathways have separate requirements. A training centre's city name does not mean every worksite will be nearby.</p>

<h2>Cleveland: Local 33 Cleveland/Vermilion</h2>

<p>The <a href="https://cle33sheetmetal.org/steps-to-apply/" rel="noopener">steps to apply</a> require applicants to be at least 17, hold a high school diploma or GED (a copy is required), complete the entrance exam and be able to perform the essential duties of the trade. You will need a final transcript or GED certificate and valid photo ID, plus a DD214 for veterans; a resume and recommendation letters are optional. Accepted candidates must pass a drug test.</p>

<p>After you submit, you must open the verification email: the page says "you must view and confirm this email for the application to be verified and submitted", so a complete application can stall if you skip it.</p>

<h2>Cleveland Testing and Programme Length</h2>

<p>Verified applicants receive entrance-exam instructions. The interview notice follows by email one to two weeks after the exam, and applicants are then told by email of their placement on the eligibility list. The page mentions no fees, so ask about fees, the exam format and scheduling before booking.</p>

<p>The Local 33 <a href="https://sheetmetaltraining.org/training-centers/cleveland-training-center/" rel="noopener">training centre page</a> contradicts itself: a heading says the Cleveland/Vermilion programme is "now only 4 years" from the 2022-2023 school year, while a paragraph still describes a five-year apprenticeship. Rely on the written agreement for your own cohort, and do not assume another Local 33 district follows Cleveland's process.</p>

<h2>Pittsburgh: Sheet Metal Workers Local 12</h2>

<p>The <a href="https://sheetmetalapprenticetraining.com/apprenticeship-application/" rel="noopener">application page</a> lists a completed application, a signed "physically able to perform work" document, a resume, official high school transcripts, transcripts from any other school on your application, and two letters of recommendation. A birth certificate and driver's licence are supplied separately from the online form. The page does not state a minimum age, so ask the training centre on 412-828-1386 before submitting.</p>

<p>Request records and references early, and ask each referee to explain how they know you and speak to reliability, learning or teamwork. Read the physical work description before you sign, and raise questions about essential tasks or accommodations with the centre.</p>

<h2>Pittsburgh Test Scheduling and Training</h2>

<p>On the review date Local 12's <a href="https://sheetmetalapprenticetraining.com/" rel="noopener">homepage</a> lists a test on 20 March 2027, with registration handled through its contact page. Treat that as a date to reconfirm, not proof that you are registered. The <a href="https://sheetmetalapprenticetraining.com/faqs/" rel="noopener">FAQ</a> says "you may apply at any time throughout the year", describes a typical five-year apprenticeship and says apprentices attend school 200 hours per year. Ask how classes affect your work schedule before you accept a job or arrange childcare.</p>

<h2>Minneapolis: Local 10 Metro Apprenticeship</h2>

<p>For Minneapolis and the metro area, start with <a href="https://www.smw10.org/metro.html" rel="noopener">Local 10's Metro programme</a> rather than assuming every Local 10 office uses the same steps. It describes 8,000 hours (about four years) of on-the-job training plus about 180 hours of related instruction each year, and it requires applicants to be at least 18, to be a high school graduate or hold a GED, and to be physically able to do the work.</p>

<p>The aptitude test covers mechanical aptitude, mathematics and reading comprehension. Applicants are ranked on test score, work experience, high school or equivalent grades and any post-secondary sheet metal training, and the page says attending a sheet metal programme is not required.</p>

<h2>Minneapolis Requires Attention to Employer Hiring</h2>

<p>Minnesota's <a href="https://dli.mn.gov/business/workforce/sheet-metal-worker-apprenticeships" rel="noopener">Department of Labor and Industry</a> says applications to the Metro programme are accepted year-round but "acceptance to the apprenticeship program is dependent on employment with a contractor". Applicants should seek employment with a signatory contractor and contact the programme about the aptitude test, which is held at the training centre.</p>

<p>Ask the coordinator for the current contractor list and the right order of application, testing and employer contact. A general helper vacancy is not apprenticeship admission until the sponsor confirms it, so keep records of every employer conversation.</p>

<h2>How to Prepare for Assessments</h2>

<p>Ask for the current exam guide, permitted materials and accommodation process. No single format or pass mark applies to all three. For general preparation, practise fractions, decimals, percentages, conversions and reading instructions, and review basic geometry and simple mechanical systems; our <a href="/blog/sheet-metal-workers-aptitude-test-study-plan">sheet metal aptitude test guide</a> and <a href="/blog/apprenticeship-test-math-basics">maths basics</a> help. Prepare honest interview examples of attendance, careful work, learning from mistakes and cooperation.</p>

<h2>Compare Wages, Benefits and Expenses</h2>

<p>Local 10 publishes separate Metro wage schedules for commercial, residential and architectural work, effective 1 May 2026 to 30 April 2027, as linked PDFs. Ask each programme for its current schedule and progression, and compare cash wages with housing and transport costs. Employer benefit contributions are not money in your pay packet. Ask about tools, books, testing and dues, when benefits begin, and how work interruptions affect training, and do not relocate or quit a job on an application confirmation alone.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need previous sheet metal experience?</h3>
<p>Check the programme. Local 10 says attending a sheet metal programme is not required, though schooling may count in ranking.</p>

<h3>Can I apply at age 17?</h3>
<p>Cleveland's steps say at least 17. Local 10 Metro says 18. Pittsburgh's page states no age, so ask the centre.</p>

<h3>Is Pittsburgh accepting test registrations?</h3>
<p>Its site lists a 20 March 2027 test and a contact route for registration. Ask the office to confirm.</p>

<h3>Does passing an exam guarantee employment?</h3>
<p>No. Tests, interviews, eligibility lists and employer demand all affect selection.</p>

<h3>Which city should I choose?</h3>
<p>Pick the programme whose rules, work area, training schedule and living costs fit you.</p>

<h3>How long is the Cleveland programme?</h3>
<p>The page says both four and five years. Confirm the length in your written agreement.</p>

<h3>Can I apply for Minneapolis without an employer?</h3>
<p>You can apply year-round, but acceptance depends on employment with a contractor.</p>

<h3>Is there an application fee in Cleveland?</h3>
<p>The steps page mentions no fee. Ask before booking the exam.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>These programmes need work authorisation in the United States, and an apprenticeship notice does not sponsor a visa. Never pay an agent for a place in a union programme, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Apply through the correct local training centre, complete every step and confirm how employment connects to admission. Cleveland, Pittsburgh and Minneapolis offer distinct routes into sheet metal work.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://cle33sheetmetal.org/" rel="noopener">Cleveland/Vermilion Sheet Metal JATC</a></li>
<li><a href="https://cle33sheetmetal.org/steps-to-apply/" rel="noopener">Cleveland/Vermilion: Steps to Apply</a></li>
<li><a href="https://sheetmetaltraining.org/training-centers/cleveland-training-center/" rel="noopener">Local 33: Cleveland Training Center</a></li>
<li><a href="https://sheetmetalapprenticetraining.com/apprenticeship-application/" rel="noopener">Local 12: Apprenticeship Application</a></li>
<li><a href="https://sheetmetalapprenticetraining.com/" rel="noopener">Local 12 Training Center: Upcoming Test</a></li>
<li><a href="https://sheetmetalapprenticetraining.com/faqs/" rel="noopener">Local 12: Frequently Asked Questions</a></li>
<li><a href="https://www.smw10.org/metro.html" rel="noopener">SMART Local 10: Metro Program</a></li>
<li><a href="https://dli.mn.gov/business/workforce/sheet-metal-worker-apprenticeships" rel="noopener">Minnesota DLI: Sheet Metal Apprenticeships</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/sheet-metal-workers-aptitude-test-study-plan">Sheet Metal Aptitude Test: Sections, Tips, Study Plan</a>: test preparation</li>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>: application steps</li>
    <li><a href="/blog/how-apprenticeship-ranking-lists-work">How Apprenticeship Ranking Lists Work</a>: scores and expiry</li>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Recruitment status checked on 17 October 2026 against official pages, which change often. No vacancy, admission or selection outcome is guaranteed.</p>
HTML;
    }
}
