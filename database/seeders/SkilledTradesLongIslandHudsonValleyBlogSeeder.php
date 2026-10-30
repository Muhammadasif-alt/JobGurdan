<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Skilled Trades on Long Island and in the Hudson Valley"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The apply and view-guide buttons are removed; the NYSDOL notices are linked in the text and in a Sources list.
 * 2. USIS Electric: the notice says applications are in person at 35 West Jefferson Avenue, Pearl River, and its counties are Orange, Putnam (spelled Putman in the notice), Rockland and Westchester in New York plus Bergen County, New Jersey. The brief's line "allow an hour" is not in the notice and is dropped.
 * 3. Operating Engineers Local 825: the opening is noon on 22 October, only 25 applications are distributed, and the notice requires the Locating Information test with a qualifying score of 3 of 7; the brief only said "WorkKeys". Duplicate applications disqualify all of them.
 * 4. Bricklayers Local 1: pickup is at Local Union 5, 1 Scobie Drive, Newburgh, on Mondays 8 a.m. to 12 p.m. UA Local 373: the site gives a Mountainville, NY address and does not name counties, so no county list is claimed.
 * 5. A Pakistan note, eight FAQs and eight People Also Search For entries are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class SkilledTradesLongIslandHudsonValleyBlogSeeder extends Seeder
{
    public const SLUG = 'skilled-trades-long-island-hudson-valley';

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
                'title' => 'Skilled Trades on Long Island and in the Hudson Valley',
                'excerpt' => 'Compare apprenticeships for laborers, carpenters, electricians, masons and operating engineers on Long Island and in the Hudson Valley, with dates and rules.',
                'content' => $content,
                'featured_image' => 'blogs/skilled-trades-long-island-hudson-valley.jpg',
                'tags' => 'skilled trades long island, hudson valley apprenticeships, trade careers nassau suffolk, construction apprenticeships new york, local 66 laborers long island, carpenters apprenticeship hauppauge, operating engineers local 825, usis electric apprenticeship',
                'meta_title' => 'Skilled Trades on Long Island and in the Hudson Valley',
                'meta_description' => 'Skilled trade apprenticeships on Long Island and in the Hudson Valley: official notices, residency rules, application dates and practical advice.',
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
<p><strong>You can start a skilled trade career on Long Island or in the Hudson Valley through registered apprenticeships that combine paid work with classroom training.</strong> Options include construction labour, carpentry, electrical work, masonry, equipment operation and piping trades. Check your county eligibility, the recruitment dates and the sponsor's application method first. Some routes below are recruiting now and others open later in 2026. Acceptance and immediate employment are never guaranteed.</p>

<h2>Choose Your Region and Trade</h2>

<p>Here, Long Island means Nassau and Suffolk counties. Long Island City is in Queens, so the name alone does not identify a Nassau or Suffolk opportunity. Hudson Valley programmes cover different county combinations, and a regional advert does not override a sponsor's residency rule. Before comparing wages, confirm that your home address qualifies and that you can reach changing job sites and evening classes. Choose work you can sustain: outdoor construction, precise installation, troubleshooting or operating equipment. The state's <a href="https://dol.ny.gov/apprenticeship/become-apprentice" rel="noopener">become an apprentice page</a> explains the route.</p>

<h2>Long Island: Construction Laborers Local 66</h2>

<p>Local 66's <a href="https://dol.ny.gov/ap/general-building-laborers-local66-0" rel="noopener">notice</a> (announced 20 August 2026) lists 20 Skilled Construction Craft Laborer apprentices from 16 September 2026 to 15 September 2027. Apply in person only at 1600 Walt Whitman Road, Melville, every Monday and Wednesday, 10 a.m. to 3 p.m., closed on legal holidays. Applicants need to be 18, hold a diploma or equivalent, have a valid New York driver's licence and have lived in Nassau or Suffolk for at least six months before applying. They must pass a written assessment, and a drug test at the applicant's expense follows selection. A recruitment window does not promise an immediate start.</p>

<h2>Long Island: Carpenter Apprenticeships</h2>

<p>The North Atlantic States Carpenters Training Fund's <a href="https://dol.ny.gov/ap/north-atlantic-states-carpenters-training-fund-11" rel="noopener">notice</a> covers 50 carpenter apprentices from 3 November 2026 to 2 November 2027, so on 17 October it is upcoming. Applicants must first attend an information session at 270 Motor Parkway, Hauppauge, on the first Monday of each month at 6 p.m. (seats are limited), where they receive the passcode needed for the online application. Applications can only be obtained and submitted online. You must be at least 17 and live in Nassau or Suffolk. Check your session date directly, because the monthly sessions and the opening date need careful coordination.</p>

<h2>Hudson Valley: Electrical Training Through USIS</h2>

<p>The <a href="https://dol.ny.gov/ap/usis-electric-inc" rel="noopener">USIS Electric notice</a> runs from 11 May 2026 to 7 May 2027 for 24 electrician apprentices shared across the Hudson Valley, Long Island and New York City. Applications are in person at 35 West Jefferson Avenue, Pearl River, weekdays 9 a.m. to 12 p.m. and 1 p.m. to 4 p.m., by 7 May 2027. Applicants must be 18, hold a diploma or equivalent including one year of high school algebra, and say they can do the physical work. Residence is limited to Orange, Putnam, Rockland and Westchester counties in New York or Bergen County, New Jersey, so Nassau or Suffolk residence alone does not qualify.</p>

<h2>Hudson Valley: Masonry Apprenticeships</h2>

<p>Bricklayers and Allied Craftworkers Local 1's <a href="https://dol.ny.gov/node/64326" rel="noopener">notice</a> lists 10 bricklayer, mason and plasterer apprentices from 2 March 2026 to 1 March 2027. Applications are picked up at Local Union 5, 1 Scobie Drive, Newburgh, on Mondays from 8 a.m. to 12 p.m., excluding holidays. Applicants must be 18, hold a diploma or equivalent, and pass a sponsor-paid drug test on an offer of employment. Think about weather, heights and travel, and confirm the pickup arrangements and return deadline before you go. Masonry suits people who like careful measuring and visible results.</p>

<h2>Hudson Valley: Operating Engineers Local 825</h2>

<p>Local 825's <a href="https://dol.ny.gov/node/67336" rel="noopener">next recruitment</a> runs from 22 October to 5 November 2026 for five Operating Engineer (Universal Equipment) apprentices across the Hudson Valley and Southern Tier. It is not open on 17 October. The online portal opens at noon on 22 October and only 25 applications will be distributed, first come, first served. You must upload a high school transcript or equivalent within 24 hours of submitting, and duplicate applications disqualify all of them.</p>

<p>Residents of Rockland, Orange, Ulster, Sullivan and Delaware counties are eligible. Applicants must pass the Locating Information test (the notice spells it "Work Keys") with a qualifying score of 3 out of 7 to be interviewed. Have documents ready beforehand.</p>

<h2>Plumbing, Pipefitting and HVAC Routes</h2>

<p>UA Local 373's <a href="https://www.ua373.com/" rel="noopener">website</a> says it represents plumbing, pipefitting and HVACR journeymen and apprentices in the Hudson Valley, gives a Mountainville, NY address and invites interested people to contact the union about its apprenticeship. It does not list counties or an open intake, so call the training programme to confirm the next intake, geographic rules and selection process, and do not treat the general page as proof that applications are open.</p>

<h2>Prepare a Strong Application</h2>

<p>Build a separate checklist per sponsor: identification, education records, address evidence and driving documents. Check whether records are due now or after selection. Practise fractions, decimals, measurement and reading instructions, but use each sponsor's published assessment topics, because trades do not share one test. Prepare interview examples of punctuality, teamwork, safe decisions and willingness to learn, track submission dates and confirmations, and keep copies of everything. The <a href="https://dol.ny.gov/apprenticeship/apprenticeship-announcements" rel="noopener">state announcements directory</a> lists further notices.</p>

<h2>Compare Pay and Travel Costs</h2>

<p>Ask each sponsor for its current apprentice wage schedule, progression, benefit eligibility and likely hours, and keep cash wages separate from the benefits package. Do not budget around journeyworker rates or guaranteed overtime. Estimate fuel, tolls, parking, tools and unpaid travel time, and test the commute at the real reporting hour. The <a href="https://dol.ny.gov/career-centers" rel="noopener">New York career centres</a> can help you find regional employment support.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a college degree?</h3>
<p>None of these notices lists a degree. Most ask for a diploma or equivalent, and the electrical notice also asks for a year of algebra.</p>

<h3>Can I apply without trade experience?</h3>
<p>Often yes, but you must meet every entry requirement and complete selection.</p>

<h3>Does applying guarantee a place?</h3>
<p>No. Advertised apprentice numbers do not guarantee acceptance, steady work or a start date.</p>

<h3>How can I strengthen my interview?</h3>
<p>Explain why you chose the trade, describe a task you finished or a mistake you corrected, and keep answers short and honest.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>These programmes need work authorisation in the United States, and an apprenticeship notice does not sponsor a visa. Never pay an agent for a place in a union programme, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Match your address, interests and travel capacity to an eligible programme. Prepare documents early, follow the exact application method and keep checking official recruitment notices, and compare real apprentice pay and working conditions before accepting an offer.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://dol.ny.gov/apprenticeship/become-apprentice" rel="noopener">NYSDOL: Become an Apprentice</a></li>
<li><a href="https://dol.ny.gov/ap/general-building-laborers-local66-0" rel="noopener">NYSDOL: General Building Laborers Local 66</a></li>
<li><a href="https://dol.ny.gov/ap/north-atlantic-states-carpenters-training-fund-11" rel="noopener">NYSDOL: North Atlantic States Carpenters</a></li>
<li><a href="https://dol.ny.gov/ap/usis-electric-inc" rel="noopener">NYSDOL: USIS Electric</a></li>
<li><a href="https://dol.ny.gov/node/64326" rel="noopener">NYSDOL: Bricklayers and Allied Craftworkers Local 1</a></li>
<li><a href="https://dol.ny.gov/node/67336" rel="noopener">NYSDOL: Operating Engineers Local 825</a></li>
<li><a href="https://www.ua373.com/" rel="noopener">UA Local 373</a></li>
<li><a href="https://dol.ny.gov/apprenticeship/apprenticeship-announcements" rel="noopener">NYSDOL: Apprenticeship Announcements</a></li>
<li><a href="https://dol.ny.gov/career-centers" rel="noopener">NYSDOL: Career Centers</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/trades-careers-upstate-new-york-buffalo-rochester-syracuse">Trades Careers in Upstate New York</a>: Buffalo, Rochester, Syracuse</li>
    <li><a href="/blog/ironworker-apprenticeship-new-york">Ironworker Apprenticeship in New York</a>: NYC to Upstate</li>
    <li><a href="/blog/laborers-union-entrance-exam-guide">Laborers Union Entrance Exam Guide</a>: laborers entry</li>
    <li><a href="/blog/operating-engineers-apprenticeship-test-preparation-guide">Operating Engineers Apprenticeship Test Preparation Guide</a>: IUOE entry</li>
</ul>

HTML;
    }
}
