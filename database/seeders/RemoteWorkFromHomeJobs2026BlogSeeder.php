<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Remote Work From Home Jobs 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online"
 *
 * Checked on 4 November 2026. What changed against the brief:
 *
 * 1. The Indeed button is dropped and the brief's inline-styled buttons replaced. The Lever and Greenhouse links carried
 *    job IDs, so the guide links each board and names each posting.
 * 2. Welo Global Search Quality Rater (US) read: remote, US-based, W2 part-time employee, 10 to 29 hours a week, paid
 *    every 2 weeks, 12-month project, 18+, reliable computer, internet and anti-virus, and current or past ads quality
 *    raters excluded. The brief's $14.50 an hour was not on the page we read, so no rate is stated.
 * 3. Wisetack Customer Experience Associate I read: $25 an hour, 2+ years of customer-facing experience, weekends.
 *    Welo Maps Personalization Relevance Rater read: freelance, $20.30 an hour, estimated 8 to 10 hours, weekly
 *    project, needs an actively used Gmail account with Google Maps history.
 * 4. The Welo Pakistan Urdu linguist role (5+ years, 2+ as lead) is covered in the remote jobs 2026 guide and only
 *    referred to here, so this guide's angle is task type: rating, support and maps work.
 *
 * Posters are the owner's (blog-359, 3 Nov 9.08 to 9.11 PM, all four). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class RemoteWorkFromHomeJobs2026BlogSeeder extends Seeder
{
    public const SLUG = 'remote-work-from-home-jobs-2026';

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
                'title' => 'Remote Work From Home Jobs 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online',
                'excerpt' => 'Three kinds of work-from-home job compared: search quality rating (10 to 29 hours a week, no prior rating work), borrower support at $25 an hour with two years\' experience, and Google Maps rating at $20.30 an hour.',
                'content' => $content,
                'featured_image' => 'blogs/remote-work-from-home-jobs-2026.jpg',
                'tags' => 'remote work from home jobs 2026, work from home jobs no experience, search quality rater jobs, remote customer support jobs, maps rater jobs, work from home salary per month, best websites for remote jobs, work from home job scams',
                'meta_title' => 'Remote Work From Home Jobs 2026: Vacancies and Pay',
                'meta_description' => 'Work-from-home jobs in 2026: search quality rating, $25 an hour support and $20.30 an hour maps rating compared, with requirements, monthly pay and scam checks.',
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
<p><strong>For a first work-from-home job in 2026, choose by the type of task, not by the word "remote": rating search results, supporting customers, or judging map recommendations each suit a different person.</strong> On 4 November 2026 we checked three US openings. A search quality rater role with Welo Global needs no prior technical experience but excludes anyone who has done ads rating before. A borrower support role at Wisetack pays $25 an hour but requires two years of customer-facing experience. And a Google Maps rating project pays $20.30 an hour for about 8 to 10 hours. Which one fits depends on where you live, what you have done before, and how steady your income needs to be.</p>

<h2>Three Types of Work-From-Home Job</h2>

<h3>1. Search Quality Rater: Welo Global, United States</h3>

<p>A search quality rater reads a search query, looks at the results and judges how well they answer it. For example, someone searching for a particular shop's opening hours probably wants that shop's local page, not a general article about the chain. The skill is making that call consistently, following written guidelines.</p>

<p>The posting lists:</p>

<ul>
<li>Remote and US-based, as a W2 part-time employee paid every two weeks</li>
<li>A minimum of 10 and up to 29 hours a week, on your own schedule, though weekly hours can vary</li>
<li>A 12-month project with possible extension</li>
<li>Age 18 or older, with a reliable computer, internet connection and anti-virus software</li>
</ul>

<p>One rule catches people out: if you currently work, or have ever worked, as an "Ads Quality Rater", you cannot join this search rating project. The posting we read did not show an hourly rate, so ask before you take the qualification test. Official board: <a href="https://jobs.lever.co/weloglobal" rel="noopener">Welo Global jobs</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/remote-work-from-home-jobs-2026-2.jpg"
         alt="Smiling remote worker at a laptop in a home office, Remote Work From Home Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>2. Customer Experience Associate I: Wisetack, United States</h3>

<p>Wisetack is a consumer financing company; this role helps borrowers sort out problems with their loans. It pays <strong>$25 an hour</strong>. The "I" in the title sounds junior, but the posting requires <strong>two or more years</strong> of experience in customer support, customer success, financial services, operations or another customer-facing role, plus willingness to work weekends. Experience in lending, banking, payments, insurance or healthcare earns bonus points.</p>

<p>The job is about owning a problem from first contact to resolution: following up, coordinating with other teams and keeping the borrower clear on what happens next. In your application, use one example where you stuck with an issue across several conversations until it was truly fixed. Official board: <a href="https://job-boards.greenhouse.io/wisetack" rel="noopener">Wisetack jobs</a>.</p>

<h3>3. Maps Personalization Relevance Rater: Welo Global, United States</h3>

<p>This freelance project pays <strong>$20.30 an hour</strong> for an estimated 8 to 10 hours, running week by week. You rate whether suggested places make sense given someone's past activity. It needs an existing, actively used Gmail account with Google Maps history, because part of the task uses that history. Think about whether you are comfortable using your personal account this way before applying.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/remote-work-from-home-jobs-2026-3.jpg"
         alt="Remote worker with a headset and laptop at home, Remote Work From Home Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Work-From-Home Salary Per Month</h2>

<p>Monthly earnings depend on paid hours. Using hourly rate × hours per week × 52 ÷ 12, before tax:</p>

<ul>
<li><strong>Wisetack, $25 an hour, 40 hours a week:</strong> about $4,333 a month (40 hours is our assumption; confirm the schedule)</li>
<li><strong>Maps rating, $20.30 an hour, 8 to 10 hours:</strong> about <strong>$162.40 to $203 for that week's work</strong></li>
</ul>

<p>Do not multiply one project week by four unless the employer confirms the same volume every week. For the search rater role, use the hourly rate Welo quotes you and the hours you can realistically keep between 10 and 29. Exclude bonuses, and remember part-time freelance income can stop when a project ends.</p>

<h2>Work From Home With No Experience</h2>

<p>If you have never worked remotely, list the tasks you have done elsewhere. Working through a queue of questions in a shop is relevant to phone support. Checking a student magazine against a style guide shows you can apply rating guidelines. Describe the setting honestly, and do not count a short practice exercise as a job.</p>

<p>If you have never had any paid job, focus on roles that train you on the task, like search rating, and read every requirement first. Rating needs no technical background, but residence, equipment and the ads-rater exclusion still apply. Wisetack's two-year rule is a hard requirement, so skip it until you have the experience.</p>

<p>Readers in Pakistan: these three are US roles. Welo also runs a remote English-to-Urdu marketing linguist role for Pakistan, but it needs at least five years of professional translation work. Our <a href="/blog/remote-jobs-2026-latest-vacancies">Remote Jobs 2026 guide</a> covers it in detail.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/remote-work-from-home-jobs-2026-4.jpg"
         alt="Remote worker giving a thumbs up at a home office desk, Remote Work From Home Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get a Work-From-Home Job Online</h2>

<ol>
<li><strong>Choose a schedule you can keep.</strong> Decide whether you need steady hours or can live with projects that come and go. If you share your space, check you can take private calls.</li>
<li><strong>Search by task and country.</strong> Use the employer's own posting to confirm the country or state, and convert training hours into your time zone.</li>
<li><strong>Show one relevant skill.</strong> For support, write a short reply to an imaginary customer with an unexplained charge. For rating, explain why one result answers a query better than another.</li>
<li><strong>Apply on the employer's page,</strong> check your contact details and make sure sample links open without a password.</li>
<li><strong>Ask how work is assigned.</strong> For projects, what happens when the task queue is empty? For support, who helps with an unresolved case at the end of a shift?</li>
</ol>

<h2>Work-From-Home Job Scams to Avoid</h2>

<p>The Federal Trade Commission warns about fake employers who send checks and ask you to buy equipment or send money back, and about "task" scams where you are paid for small online jobs until you are asked to deposit your own money. Never send money to start work. Find the real employer's careers page yourself; a familiar logo in a chat message proves nothing.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I do a US work-from-home job from Pakistan?</h3>
<p>Only if the employer allows it. All three roles above are US-based. Look for roles that name Pakistan.</p>

<h3>Which work-from-home job suits someone who cannot take calls?</h3>
<p>Rating work, such as search quality or maps rating, rather than borrower support.</p>

<h3>Do I need experience to be a search quality rater?</h3>
<p>No technical experience is needed, but you must be 18+, US-based, and not a current or former ads quality rater.</p>

<h3>How much does the Wisetack support role pay?</h3>
<p>$25 an hour, for people with at least two years of customer-facing experience who can work weekends.</p>

<h3>Is freelance rating work a steady income?</h3>
<p>Not usually. Hours vary and projects end, so budget only on confirmed work.</p>

<h2>Bottom Line</h2>

<p>Pick the type of work that fits you, rating, support or maps, check that you meet its location and experience rules, and if you need reliable income, choose confirmed paid hours over the highest advertised rate.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://jobs.lever.co/weloglobal" rel="noopener">Welo Global jobs</a>: Search Quality Rater (US) and Maps Personalization Relevance Rater (US)</li>
<li><a href="https://job-boards.greenhouse.io/wisetack" rel="noopener">Wisetack: Customer Experience Associate I</a></li>
<li><a href="https://consumer.ftc.gov/articles/job-scams" rel="noopener">Federal Trade Commission: Job scams</a>, all checked 4 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/remote-jobs-2026-latest-vacancies">Remote Jobs 2026</a>, which country you can work from.</li>
    <li><a href="/blog/remote-customer-service-work-from-home-jobs-2026">Remote Customer Service Jobs 2026</a>, phone support in detail.</li>
    <li><a href="/blog/work-from-home-jobs-michigan-no-experience">Work From Home Jobs in Michigan</a>, beginner options in one state.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
