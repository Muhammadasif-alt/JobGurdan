<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Union Apprenticeships in Southern California and the Inland Empire"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The apply and view-guide buttons are removed; official pages are linked in the text and in a Sources list.
 * 2. The brief says the LAETI page marks an "October 5" window closed and mentions a TradeSchool account. The LAETI site would not load, and a search of it did not show either detail (it showed January and April 2026 windows closed, a July testing week, online-only applications and age 18 with an algebra course). Both specifics are dropped and the guide describes LAETI generally.
 * 3. The brief says "Southern California Laborers Training School"; its site shows the Laborers Training School in Azusa, California, so that name is used. The brief said the IEETC September intake is full; the page confirms it and also says no further dates are scheduled and forms sent outside open periods are invalid.
 * 4. UA Local 364: the exam is 50 questions with a 76% minimum for an interview, no calculators or phones, original documents including a Social Security card and proof of work status. The brief's location detail is not on the page. The operating engineers application portal is only cited as the sponsor's closed notice, with no link.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class UnionApprenticeshipsSouthernCaliforniaInlandEmpireBlogSeeder extends Seeder
{
    public const SLUG = 'union-apprenticeships-southern-california-inland-empire';

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
                'title' => 'Union Apprenticeships in Southern California and the Inland Empire',
                'excerpt' => 'Explore union apprenticeships in Southern California and the Inland Empire: electrical, plumbing, carpentry, laborers and equipment operation, with entry rules.',
                'content' => $content,
                'featured_image' => 'blogs/union-apprenticeships-southern-california-inland-empire.jpg',
                'tags' => 'union apprenticeships southern california, inland empire apprenticeships, union trade programs california, paid apprenticeships riverside san bernardino, ieetc apprenticeship, ua local 364 apprenticeship, western states carpenters, operating engineers local 12',
                'meta_title' => 'Union Apprenticeships: Southern California, Inland Empire',
                'meta_description' => 'Union apprenticeships in Southern California and the Inland Empire: electrical, plumbing, carpentry, laborers and operating engineers, with entry rules.',
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
<p><strong>Union apprenticeships in Southern California and the Inland Empire offer routes into electrical work, plumbing, carpentry, construction labour and equipment operation.</strong> Start with the training sponsor, confirm your eligibility and apply during its official recruitment period. The programmes combine practical work with structured training, but applying does not guarantee selection or a job. On 17 October 2026 the Inland Empire electrical intake we checked was full, and other programmes follow their own schedules.</p>

<h2>Start With the Right Location</h2>

<p>For the Inland Empire, begin with Riverside and San Bernardino counties. For wider Southern California options, decide your realistic travel range before adding Los Angeles, Orange County or elsewhere. A training centre's address does not define every job site, so ask whether apprentices travel across counties, where classes meet and whether residency rules apply. California's <a href="https://www.dir.ca.gov/databases/das/aigstart.asp" rel="noopener">apprenticeship program search</a> helps verify registered programmes, but a registration does not mean recruitment is open, and the directory lists different sponsor types, so confirm union affiliation separately.</p>

<h2>Inland Empire Electrical Training Center</h2>

<p>IEETC in San Bernardino describes an Inside Wireman apprenticeship of five years of classroom study and 8,000 hours of paid on-the-job training, and a Sound and Telecommunications route of three years of classroom study and 4,800 hours (<a href="https://www.ieetc.org/en/the-ieetc-apprenticeship-program-cffaa1a4" rel="noopener">programme descriptions</a>). Its <a href="https://www.ieetc.org/en/how-to-apply-b733f5f3" rel="noopener">application page</a> says the 2 September 2026 intake is full and "we do not have any additional application dates scheduled at this time", and asks you to check back for the next open day. Applications are accepted only online during posted open periods, and forms sent outside those dates are invalid.</p>

<p>When recruitment returns, the page lists documents you must bring in person on the test date: a copy of your birth certificate showing you are at least 18, a copy of your diploma or GED, and official sealed transcripts showing algebra 1 or higher, the Electrical Training Alliance Technical Math course or the ALEKS algebra course. Preparing records early helps but does not reserve a place.</p>

<h2>Los Angeles Electrical Training Institute</h2>

<p>LAETI (the <a href="https://www.laett.com/" rel="noopener">Electrical Training Institute</a>) takes applicants for several electrical classifications. Its site says applications are online only, open during set windows that can close early once capacity is reached, and require uploaded documents and proof of an algebra course. When we checked, its January and April 2026 windows were marked closed. The sponsor must announce the next intake, so do not assume an earlier date will repeat. Use an email address you watch and keep every completion confirmation.</p>

<h2>Plumbing, Steamfitting and HVAC: UA Local 364</h2>

<p>The <a href="https://www.ualocal364.com/apprenticeship" rel="noopener">Local 364 page</a> says "applications are accepted on the last Tuesday of each month (January-November), between 1-4 pm in person". Applicants sit an exam of 50 maths and reading questions, with no calculators or cell phones, and need at least 76% to get an interview with the Local's business representatives. You must bring originals: a valid California driver's licence, Social Security card, diploma or GED (or sealed transcripts), birth certificate, proof of citizenship or legal work status and, for veterans, a DD-214.</p>

<p>Rated applicants go into a pool, and passing both the exam and interview does not guarantee initiation because available work is the main factor. Practise calculations without a calculator, and prepare examples of reliability, teamwork and interest in mechanical work.</p>

<h2>Carpentry: Western States Carpenters Training Fund</h2>

<p>The fund, which changed its name from SWMSCTF, publishes <a href="https://www.wsctf.org/join/" rel="noopener">entry guidance</a> for several carpentry crafts. You must be at least 18 (or 17 with parent or guardian approval), be able to perform the work of the trade and have your own transport or a reliable way to reach job sites and classes. You do not need prior experience on a job site or with hand tools or machinery. You pick a craft programme, get an application at any training centre and return it with all required documents. Contact the right union local first, and ask which crafts are recruiting nearby and how employer placement works.</p>

<h2>Construction Laborer Apprenticeships</h2>

<p>The <a href="https://www.laborerstrainingschool.com/applicant" rel="noopener">Laborers Training School</a> in Azusa, California says applicants must be at least 18 and pass a five-day Apprenticeship Entry Assessment: a drug test and enrolment paperwork on day one, a physical agility test on days two to four and an oral exit interview on day five. No prior experience is needed. Entry assessments are scheduled by local union need, so you join a waiting list and must check in during March, June, October and December or the application is deleted. Failing any part of the assessment means waiting six months to reapply. Our <a href="/blog/union-apprenticeship-waitlists-how-long">waitlist guide</a> explains more.</p>

<h2>Operating Engineers Local 12</h2>

<p>The California Nevada Operating Engineers Training Trust (<a href="https://www.cnoett.org/" rel="noopener">OETT</a>) runs Local 12 training for equipment operation and related classifications, which can include heavy equipment, grading, inspection or maintenance. Its news section says "OETT is currently closed for Apprenticeship Program applications" and does not say when it will reopen. Follow the sponsor's announcements rather than treating an old advert as active, and review classification details and exam guidance while you wait.</p>

<h2>Build Your Application Checklist</h2>

<p>Keep a separate checklist for each programme: minimum age, education, driving requirements, acceptable ID and any work-authorisation documents, sent only through the sponsor's approved process. Gather transcripts early, especially where maths coursework must be verified, and follow instructions on sealed transcripts and originals. Practise fractions, decimals, measurement and reading, adding algebra or mechanical reasoning only where the published test needs it. Prepare interview examples of punctuality, safe decisions and learning from mistakes.</p>

<h2>Compare Wages and Practical Costs</h2>

<p>Ask for the current apprentice wage schedule, how raises depend on hours and classroom progress, and when health cover starts. Keep cash wages separate from employer benefit contributions, and budget for transport, parking, tools, dues and training costs the sponsor names. Do not base your household budget on journeyworker pay or assumed overtime. IEETC's descriptions show why different classifications have different timelines, so compare the actual commitment, not an advertised maximum salary.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply?</h3>
<p>Some routes accept beginners. The Western States Carpenters fund and the Laborers Training School say prior experience is not needed. Each sponsor still sets its own rules.</p>

<h3>Is every union accepting applications now?</h3>
<p>No. Windows, monthly schedules and closed periods vary. IEETC says its September intake is full and Local 12 operating engineers are closed.</p>

<h3>Does passing the test guarantee employment?</h3>
<p>No. Interviews, applicant pools and employer demand all affect selection.</p>

<h3>Should I buy a preparation course?</h3>
<p>Start with the official exam guidance and check that any paid course matches your programme's test before you pay.</p>

<h3>What should I do while waiting?</h3>
<p>Keep working or studying, improve the relevant maths, keep reliable transport and watch official announcements.</p>

<h3>How often does Local 364 take applications?</h3>
<p>On the last Tuesday of each month from January to November, 1 to 4 p.m., in person.</p>

<h3>What is the Local 364 pass mark?</h3>
<p>At least 76% on the 50-question exam to get an interview.</p>

<h3>How old must I be?</h3>
<p>Eighteen for IEETC, LAETI and the Laborers Training School; the carpenters fund allows 17 with parent or guardian approval.</p>

<h2>People Also Search For</h2>

<h3>Union apprenticeships Southern California</h3>
<p>Several sponsors, separate schedules.</p>

<h3>Inland Empire apprenticeships</h3>
<p>IEETC in San Bernardino.</p>

<h3>Union trade programs California</h3>
<p>DAS directory lists registered programmes.</p>

<h3>Paid apprenticeships Riverside San Bernardino</h3>
<p>Check county rules.</p>

<h3>IEETC apprenticeship</h3>
<p>September intake full.</p>

<h3>UA Local 364 apprenticeship</h3>
<p>76% exam minimum.</p>

<h3>Western States Carpenters Training Fund</h3>
<p>Age 18, or 17 with approval.</p>

<h3>Operating engineers Local 12</h3>
<p>Applications closed.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>These programmes need work authorisation in the United States, and an apprenticeship notice does not sponsor a visa. Never pay an agent for a place in a union programme, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Choose a trade you can sustain, verify the sponsor and prepare your documents early. Follow the current application instructions and compare travel, apprentice wages and training obligations before you accept a place.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.dir.ca.gov/databases/das/aigstart.asp" rel="noopener">California DAS: Apprenticeship Program Search</a></li>
<li><a href="https://www.ieetc.org/en/how-to-apply-b733f5f3" rel="noopener">IEETC: Application Updates</a></li>
<li><a href="https://www.ieetc.org/en/the-ieetc-apprenticeship-program-cffaa1a4" rel="noopener">IEETC: Apprenticeship Program Descriptions</a></li>
<li><a href="https://www.laett.com/" rel="noopener">Los Angeles Electrical Training Institute</a></li>
<li><a href="https://www.ualocal364.com/apprenticeship" rel="noopener">UA Local 364: Apprenticeship Information</a></li>
<li><a href="https://www.wsctf.org/join/" rel="noopener">Western States Carpenters Training Fund: How to Join</a></li>
<li><a href="https://www.laborerstrainingschool.com/applicant" rel="noopener">Laborers Training School: Applicants</a></li>
<li><a href="https://www.cnoett.org/" rel="noopener">California Nevada Operating Engineers Training Trust</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>: application steps</li>
    <li><a href="/blog/union-apprenticeship-waitlists-how-long">Union Apprenticeship Waitlists: How Long Do They Take?</a>: waiting times</li>
    <li><a href="/blog/laborers-union-entrance-exam-guide">Laborers Union Entrance Exam Guide</a>: laborers entry</li>
    <li><a href="/blog/ua-plumber-pipefitter-apprenticeship-test-guide">UA Plumber and Pipefitter Apprenticeship Test Guide</a>: plumber test</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Recruitment status checked on 17 October 2026 against official notices, which change often. The examples are not an exhaustive list of sponsors, and no vacancy, admission or selection outcome is guaranteed.</p>
HTML;
    }
}
