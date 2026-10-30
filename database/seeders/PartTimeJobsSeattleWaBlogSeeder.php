<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Part Time Jobs in Seattle, WA: Where to Apply and What to Check"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief links ten portals with apply buttons, including a commercial job board. That section is dropped (no commercial boards), and private employers (Target, Starbucks, YMCA, Seattle Children's, PCC) are named in prose only. Public bodies are linked: the University of Washington, UW Housing and Food Services, the City of Seattle and WorkSource Washington.
 * 2. The brief names a live YMCA Membership Lead listing; it cannot be re-checked and is not repeated. Target On-Demand and the Starbucks scheduling claims could not be verified from the pages and are not repeated.
 * 3. UW Housing and Food Services (HFS): over 1,000 students are employed, applicants should be eligible students who can work at least 10 hours a week, all jobs are work-study eligible, and hiring depends mainly on whether availability fits open shifts. The City of Seattle internships page says most internships are part-time in the academic year and may be full-time in summer; it says applications are only accepted while a post is open (typically about two weeks), not that a resume cannot replace the application, so that line is dropped.
 * 4. The WorkSource Washington address redirects to a new site; the guide links the address given in the brief. Eight FAQs, eight People Also Search For entries and a Pakistan note are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class PartTimeJobsSeattleWaBlogSeeder extends Seeder
{
    public const SLUG = 'part-time-jobs-seattle-wa';

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
                'title' => 'Part Time Jobs in Seattle, WA: Where to Apply and What to Check',
                'excerpt' => 'Where to look for part time jobs in Seattle: University of Washington, UW Housing and Food Services, the City and WorkSource, plus private employers to search directly.',
                'content' => $content,
                'featured_image' => 'blogs/part-time-jobs-seattle-wa.jpg',
                'tags' => 'part time jobs in seattle wa, seattle part time jobs, weekend jobs seattle, student jobs seattle, university of washington jobs, uw housing and food services jobs, city of seattle jobs, worksource washington',
                'meta_title' => 'Part Time Jobs in Seattle, WA: Where to Apply',
                'meta_description' => 'Find part time jobs in Seattle, WA: University of Washington, City of Seattle and WorkSource portals, plus retail and healthcare employers to search directly.',
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
<p><strong>To find part time jobs in Seattle, WA, start with public employers such as the University of Washington, UW Housing and Food Services, the City of Seattle and WorkSource Washington, then search retail, coffee, healthcare and community employers on their own career pages.</strong> Target, Starbucks, the YMCA of Greater Seattle, Seattle Children's and PCC Community Markets all recruit in the area. Filter for part-time work, confirm the actual Seattle location and ask about weekly hours before you apply. A part-time label does not promise flexible shifts, weekend-only work or a minimum number of hours.</p>

<h2>Decide What Part-Time Means for You</h2>

<p>Write down the days and hours you can reliably work, including travel time, classes, childcare and any other job. Decide whether you need predictable weekly hours or can accept variable shifts. Separate permanent part-time work from seasonal, temporary and on-call jobs: a temporary assignment can be full-time, and an on-call job may give fewer hours than you need. Read the schedule description rather than the title, and check the address, because "Seattle area" can mean Bellevue, Shoreline or another city.</p>

<h2>1. University of Washington Staff and Temporary Jobs</h2>

<p>The <a href="https://www.washington.edu/jobs/" rel="noopener">UW jobs page</a> separates staff jobs, temporary jobs, academic jobs and student jobs, so choose the route that fits your situation first, then look for a post whose stated schedule matches your needs. Do not assume every university job is a student job or needs an advanced degree: read the qualifications and check whether the work is in Seattle or at another UW site. Where a posting gives an FTE percentage, ask how that converts to weekly hours and shifts, because a smaller percentage does not mean you choose your days.</p>

<h2>2. UW Housing and Food Services Student Jobs</h2>

<p><a href="https://hfs.uw.edu/experience/student-jobs/" rel="noopener">UW Housing and Food Services</a> employs over 1,000 students in dining and other campus operations. It asks applicants to be eligible students who can work at least 10 hours a week, says all HFS jobs are work-study eligible and most positions are entry level, and notes that work schedules are built around class and personal commitments. Hiring depends mainly on whether your availability matches open shifts: if it does not, HFS will not contact you, so check back after a few weeks and update your availability. The page says availability in the 11 a.m. to 2 p.m. midday window improves your chances.</p>

<h2>3. City of Seattle Employment</h2>

<p>The City publishes <a href="https://www.seattle.gov/human-resources/working-at-the-city" rel="noopener">employment information</a> and routes to current vacancies. Its <a href="https://www.seattle.gov/human-resources/working-at-the-city/internships" rel="noopener">internships page</a> says "most internships are part-time during the academic year and may be full-time during the summer" and that applications are only accepted while a position is open, typically for about two weeks. Search suitable part-time or temporary announcements and verify the hours, complete every requested section and attach documents before the deadline.</p>

<h2>4. WorkSource Washington</h2>

<p><a href="https://www.worksourcewa.com/" rel="noopener">WorkSource Washington</a> is the state's job search service. Narrow it to Seattle and part-time work, then read the employer details and the original application instructions. Use a realistic distance limit, check the company independently and compare the advertised schedule with your availability. Record the job title, employer and date so you do not apply twice by accident.</p>

<h2>5. Retail and Grocery Employers</h2>

<p>Target hires hourly store staff for guest service, fulfilment and merchandise, and PCC Community Markets recruits for its Seattle stores, each through its own careers site. Search Seattle and read the employment type on every posting. Ask about expected hours, weekend and early-start availability and whether shifts are consistent, and check lifting, standing and food-handling duties. Show customer service, teamwork and reliability, and do not say you are fully available if school or another job limits you.</p>

<h2>6. Coffee, Community and Healthcare Employers</h2>

<p>Starbucks lists barista and coffeehouse roles on its careers site, the YMCA of Greater Seattle posts full-time and part-time jobs on its own site, and Seattle Children's runs a careers portal for hospital and related work. Check the store or branch address, ask whether the role needs opening, closing or weekend shifts and how many hours are normally scheduled, and review certification rules for youth-related and clinical roles. A Greater Seattle listing may be outside the city. Highlight customer service, communication and responsibility, and ask how weekend rotation, orientation and shift length work.</p>

<h2>Make Your Application Stronger</h2>

<p>Match your resume honestly to each posting, with clear examples from study, volunteering or earlier work. Keep written notes of interview talks, including availability, training dates and promised follow-up. Compare hourly pay with expected weekly hours, travel cost and benefits; tips and optional extra shifts are not dependable income. Ask when benefits begin and which hours qualify, because eligibility varies by employer and role.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I find weekend-only work?</h3>
<p>Search for weekend availability, but ask whether weekday training or occasional shifts are required, and confirm the schedule before accepting.</p>

<h3>How should I compare earnings?</h3>
<p>Compare hourly pay and expected weekly hours together, then subtract travel costs. Keep tips and optional extra shifts separate from dependable income.</p>

<h3>Can beginners apply?</h3>
<p>Yes, where the minimum requirements match their background. Show transferable skills from study or volunteering without exaggerating.</p>

<h3>Does part-time work include benefits?</h3>
<p>It varies by employer and role. Ask about qualifying hours, waiting periods and which benefits apply.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>A job listing on an employer's portal is not a visa offer, and most of these employers do not sponsor workers for entry-level roles. Never pay an agent for an interview or a "guaranteed" US job, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<h2>Bottom Line</h2>

<p>Choose Seattle jobs that fit your real availability, verify the location and clarify the expected hours. Apply through trusted portals and compare the full working arrangement before you accept.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.washington.edu/jobs/" rel="noopener">University of Washington: UW Jobs</a></li>
<li><a href="https://hfs.uw.edu/experience/student-jobs/" rel="noopener">UW Housing and Food Services: Student Jobs</a></li>
<li><a href="https://www.seattle.gov/human-resources/working-at-the-city" rel="noopener">City of Seattle: Working at the City</a></li>
<li><a href="https://www.seattle.gov/human-resources/working-at-the-city/internships" rel="noopener">City of Seattle: Internships</a></li>
<li><a href="https://www.worksourcewa.com/" rel="noopener">WorkSource Washington</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/jobs-in-chicago-il">Jobs in Chicago, IL</a>: another city guide</li>
    <li><a href="/blog/jobs-hiring-now-houston-tx">Jobs Hiring Now in Houston, TX</a>: another city guide</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: stay safe</li>
    <li><a href="/blog/plumber-apprenticeship-seattle-houston-detroit">Plumber Apprenticeship in Seattle, Houston, and Detroit</a>: trade route</li>
</ul>

<p><em>Reviewed on 17 October 2026.</em></p>

HTML;
    }
}
