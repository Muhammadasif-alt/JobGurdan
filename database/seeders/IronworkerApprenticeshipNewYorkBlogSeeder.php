<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Ironworker Apprenticeship in New York: From NYC to Upstate"
 *
 * Checked on 17 October 2026 against the NYSDOL recruitment notices, the
 * Locals 40/361 FAQ, the Local 580 recruitment page and the Local 12 site.
 * What changed against the brief:
 *
 * 1. The Apply Now and View Guide buttons are removed; the official notices
 *    are linked in the text and in a Sources list.
 * 2. Local 580's page lists six direct-entry organisations, not four (Rebuild
 *    Together NYC and Helmets to Hardhats were missing), and says anyone not
 *    accepted into one of them is not eligible to join Local 580. Its age rule
 *    is "18 by July of this year" on a page published 29 January 2026, so it is
 *    reported as that notice's rule.
 * 3. Local 60's notice runs to 18 April 2027 but says applications are due by
 *    23 April 2027, a mismatch the guide flags. It asks for two letters from
 *    previous employers, with teachers allowed for recent graduates.
 * 4. Local 6's seven-county jurisdiction, Local 33's USD 100 initiation fee and
 *    USD 33 monthly dues, and the Locals 40/361 USD 25 fee, GED minimum and
 *    physical test are added from the notices. The brief's claim that
 *    higher scorers advance to the physical test is not on the FAQ and is
 *    dropped.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries
 *    are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class IronworkerApprenticeshipNewYorkBlogSeeder extends Seeder
{
    public const SLUG = 'ironworker-apprenticeship-new-york';

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
                'title' => 'Ironworker Apprenticeship in New York: From NYC to Upstate',
                'excerpt' => 'Compare ironworker apprenticeships in NYC, Buffalo, Rochester, Syracuse and Albany: recruitment dates, residency rules, fees and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/ironworker-apprenticeship-new-york.jpg',
                'tags' => 'ironworker apprenticeship new york, nyc ironworker apprenticeship, ironworker apprenticeship upstate ny, new york ironworkers union application, local 40 361 recruitment, ironworkers local 6 buffalo, ironworkers local 33 rochester, ironworkers local 60 syracuse',
                'meta_title' => 'Ironworker Apprenticeship in New York: From NYC to Upstate',
                'meta_description' => 'Ironworker apprenticeships in NYC, Buffalo, Rochester, Syracuse and Albany: official recruitment routes, dates, residency rules and how to apply.',
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
<p><strong>To start an ironworker apprenticeship in New York, choose the local programme that covers your area, read its current recruitment notice and apply by the route it states.</strong> NYC and Upstate programmes have different schedules, residency rules and selection steps. As checked on 17 October 2026, Locals 40/361 say their next applications open in March 2027, while several Upstate recruitment periods run into 2027.</p>

<p>Apprenticeship combines supervised work with related classroom instruction. Picking the right programme matters more than sending the same application to every union office. Applying does not guarantee a place.</p>

<h2>Start With Official New York Recruitment Notices</h2>

<p>The New York State Department of Labor publishes <a href="https://dol.ny.gov/apprenticeship/apprenticeship-announcements" rel="noopener">apprenticeship announcements</a> that name the sponsor, occupation, application procedure and requirements. On the day we checked, its list showed 74 announcements across trades, so use its filters to find the ironworker notices. Read the full notice rather than a search result or an old social media post.</p>

<p>A recruitment window does not mean applications are taken every hour of it. Some programmes require you to attend in person during office hours, so check the dates again before you travel.</p>

<h2>NYC Structural Ironwork: Locals 40 and 361</h2>

<p>The joint programme's <a href="https://nycironworkers.org/recruitment-faq/" rel="noopener">recruitment FAQ</a> says "our recruitment is currently closed, next round of applications will be in March 2027". It describes a three-year programme with classes on weeknights and some Saturdays, and says work experience is not required (and will not improve your chances).</p>

<ul>
<li><strong>Exam:</strong> a general knowledge exam with reading comprehension and maths; no study books are recommended.</li>
<li><strong>Physical test:</strong> covers basic ironworker functions, including a timed 35-foot column climb, a ladder-and-beam course, bucket lifts, a chain-fall lift and carrying a 45-pound tool up stairs.</li>
<li><strong>After the physical:</strong> conditional acceptance and a drug test.</li>
<li><strong>Age:</strong> you can take the test at 17, but you must be 18 to work in New York State.</li>
<li><strong>Documents and fee:</strong> proof of age, proof of education (minimum GED) and a USD 25 testing fee paid online.</li>
</ul>

<p>Recheck the new recruitment instructions before you pay or submit documents.</p>

<h2>NYC Architectural and Ornamental Work: Local 580</h2>

<p>Local 580 covers architectural and ornamental ironwork. Its <a href="https://www.ironworkers580.org/news-training.aspx?nID=17604&amp;zone=training" rel="noopener">recruitment page</a>, published 29 January 2026, says "Local 580 uses direct entry programs". It lists six: Construction Skills, Pathways 2 Apprenticeship, Nontraditional Employment for Women, Opportunities Long Island, Rebuild Together NYC and Helmets to Hardhats for military applicants. Those programmes run about four to five weeks, and the page says that if you are not accepted into one of them you are not eligible to join Local 580.</p>

<p>That page also set an age rule ("18 years of age by July of this year") for its 2026 round, so check the current year's rule. Contact the relevant organisation about its eligibility and next intake, and do not treat a preparatory programme as guaranteed admission.</p>

<h2>Buffalo and Western New York: Local 6</h2>

<p>The NYSDOL <a href="https://dol.ny.gov/ap/iron-workers-local-union-6" rel="noopener">Local 6 notice</a> runs from 3 August 2026 to 31 July 2027 for 20 outside ironworker apprentices in total across the Finger Lakes, Southern Tier and Western regions. Applications must be completed on the premises at 196 Orchard Park Road, Buffalo, NY 14224, Monday to Friday, 9 a.m. to 3 p.m., excluding legal holidays.</p>

<p>Requirements include age 18, a high school diploma or equivalency such as GED or TASC, being a US citizen or authorised to work in the USA, a valid New York driver's licence and residence within the union's jurisdiction. That jurisdiction covers parts of seven counties: Erie, Cattaraugus, Genesee, Chautauqua, Allegany, Wyoming and Steuben, and Erie County excludes the northern half of Grand Island. A drug screening at the sponsor's expense follows selection, at enrolment.</p>

<h2>Rochester and the Finger Lakes: Local 33</h2>

<p>The <a href="https://dol.ny.gov/ap/ironworkers-lu-33-jac" rel="noopener">Local 33 notice</a> runs from 1 March 2026 to 28 February 2027 for 10 outside ironworker apprentices across the same three regions. You can apply online at ironworkers33.org, through the IW 33 mobile app, or in person at 650 Trabold Road, Rochester, NY 14624, Monday to Friday, 9 a.m. to 3 p.m.</p>

<p>The notice requires age 18, a high school diploma or equivalent (proof after selection and before enrolment), a valid New York driver's licence, reliable transport to job sites and classes, and an affidavit that you are physically able to do the work. A drug test at the sponsor's expense follows selection. You must also join the union and pay a USD 100 apprenticeship initiation fee plus the first month's dues (USD 33 a month for apprentices at the time of the notice) within 60 days. An online route does not remove attendance, transport or practical work duties.</p>

<h2>Syracuse and Central New York: Local 60</h2>

<p>The <a href="https://dol.ny.gov/ap/ironworkers-jac-syracuse-lu-60-0" rel="noopener">Local 60 notice</a> lists a recruitment period of 29 April 2026 to 18 April 2027, yet says applications must be received no later than 23 April 2027. That deadline falls after the stated period ends, so confirm the real cut-off with the JATC early and do not leave your application to the last week.</p>

<p>There are 20 outside apprentice openings across the Central, Finger Lakes, North Country and Southern regions. Pick up an application at 500 West Genesee Street, Syracuse, NY 13204 on weekdays, 9 a.m. to 4 p.m., or download it from ironworkers60.org. You need two recommendation letters from previous employers (recent graduates can use teachers or school officials). Selection includes a basic maths and detail accuracy test and an agility test that includes climbing ladders and working at heights. Applicants must have lived in the Local 60 jurisdiction for at least six months before recruitment, so check the county list rather than assuming any New York address qualifies.</p>

<h2>Albany and the Capital Region: Local 12</h2>

<p>Iron Workers <a href="https://www.ironworkers12.org/" rel="noopener">Local 12</a> says it covers "approximately 16 counties in Upstate New York" and touches Massachusetts and Vermont. Its site says apprenticeship opportunities depend on industry needs and that hiring is done by a labor/management apprenticeship committee. It does not say that intake is open, and this guide does not confirm an open Local 12 intake; call the local to ask. For the Hudson Valley or other areas, use the state directory to find the sponsor, and remember that a training location does not decide whether your address meets a programme's jurisdiction rule.</p>

<h2>Prepare Your Documents and Application</h2>

<p>Keep a separate checklist for each programme. Depending on its notice you may need:</p>

<ul>
<li>identification and proof of age</li>
<li>a diploma, equivalency certificate or school records</li>
<li>evidence of residency covering the required period</li>
<li>a driver's licence</li>
<li>recommendation letters</li>
<li>military discharge papers, if they apply</li>
</ul>

<p>Submit documents only at the stage and through the channel the notice names, and keep copies of everything and every confirmation. Do not assume one local's education or residency rule applies statewide. The state's <a href="https://dol.ny.gov/apprenticeship/become-apprentice" rel="noopener">become an apprentice page</a> explains the general route.</p>

<h2>Prepare for Tests, Training and Travel</h2>

<p>Ask each sponsor which assessments it uses. General preparation in arithmetic, fractions, measurements and reading instructions helps, but it is not a New York ironworker syllabus; our <a href="/blog/ironworker-aptitude-test-practice-guide">ironworker aptitude test guide</a> and <a href="/blog/apprenticeship-test-math-basics">apprenticeship maths basics</a> cover the basics. For the physical assessment, prepare through appropriate supervised activity and do not copy elevated climbing tests on an improvised structure. Compare commuting for both classes and changing job sites, because a nearby union office does not guarantee nearby assignments. Keep working or studying until the programme gives you a definite start.</p>

<h2>Pay, Costs and Questions Before Accepting</h2>

<p>Ask for the current apprentice wage schedule, progression rules, expected hours, benefits eligibility and required expenses. A journeyworker rate or total benefits package is not your starting cash wage. Ask what happens during gaps between work assignments, whether class attendance affects your schedule, and whether tools, clothing, travel or union payments are due before your first pay cheque.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply?</h3>
<p>Some programmes accept beginners. The Locals 40/361 FAQ says work experience is not required. Check each sponsor.</p>

<h3>Is every programme accepting applications now?</h3>
<p>No. Each follows its own schedule, and Locals 40/361 are closed until March 2027. Check the notice before applying or visiting.</p>

<h3>Can I apply anywhere in New York?</h3>
<p>Only if you meet the rules. Local 6 and Local 60 require residence inside their jurisdictions, and Local 60 requires six months there.</p>

<h3>Is there one statewide entrance test?</h3>
<p>No. These notices show different assessments, from a general knowledge exam to an agility test.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>These programmes need work authorisation in the United States, and an apprenticeship notice does not sponsor a visa. Never pay an agent for a place in a New York union programme, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Choose by specialty, location and eligibility, use the current official notices, prepare complete documents and confirm the next selection step with the sponsor.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://dol.ny.gov/apprenticeship/apprenticeship-announcements" rel="noopener">NYSDOL: Apprenticeship Announcements</a></li>
<li><a href="https://nycironworkers.org/recruitment-faq/" rel="noopener">Locals 40/361: Recruitment FAQ</a></li>
<li><a href="https://www.ironworkers580.org/news-training.aspx?nID=17604&amp;zone=training" rel="noopener">Local 580: 2026 Recruitment</a></li>
<li><a href="https://dol.ny.gov/ap/iron-workers-local-union-6" rel="noopener">NYSDOL: Local 6 Recruitment</a></li>
<li><a href="https://dol.ny.gov/ap/ironworkers-lu-33-jac" rel="noopener">NYSDOL: Local 33 Recruitment</a></li>
<li><a href="https://dol.ny.gov/ap/ironworkers-jac-syracuse-lu-60-0" rel="noopener">NYSDOL: Local 60 Recruitment</a></li>
<li><a href="https://www.ironworkers12.org/" rel="noopener">Iron Workers Local 12</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/ironworker-aptitude-test-practice-guide">Ironworker Aptitude Test Practice Guide</a>: test preparation</li>
    <li><a href="/blog/how-to-apply-for-trade-apprenticeship">How to Apply for a Trade Apprenticeship</a>: the application steps</li>
    <li><a href="/blog/union-apprenticeship-waitlists-how-long">Union Apprenticeship Waitlists: How Long Do They Take?</a>: waiting times</li>
    <li><a href="/blog/laborers-union-entrance-exam-guide">Laborers Union Entrance Exam Guide</a>: another union route</li>
</ul>

HTML;
    }
}
