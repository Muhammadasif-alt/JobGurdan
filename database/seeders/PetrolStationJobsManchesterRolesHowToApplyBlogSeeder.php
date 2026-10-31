<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Petrol Station Jobs in Manchester, England: Roles and How to Apply"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. The two Indeed buttons are dropped.
 * 2. The Kay Group advert was read: Irlam Gateway, GBP 13.50 an hour, Friday and Saturday 11pm to 7am, 18 or over under
 *    petroleum licensing rules. No experience requirement is stated. Its web address still says Bolton Road, Darwen,
 *    which the guide warns about. M44 6FW is the only Greater Manchester site on its locations page.
 * 3. Dania Services: two stations owned by MFG, BP on Princess Road, Fallowfield and Esso on Stockport Road, Cheadle.
 *    The apply buttons on its three roles point to unrelated pages, so the guide tells readers to check before sending
 *    documents. The brief's "supervising a team" wording stays; the page actually calls the post shift supervisor.
 * 4. GOV.UK figures confirmed, including the April 2027 rates the page now lists. Codes 7111 and 7112 read in the table.
 *
 * Posters are the owner's (blog-356, 31 Oct 9.39 to 9.45 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class PetrolStationJobsManchesterRolesHowToApplyBlogSeeder extends Seeder
{
    public const SLUG = 'petrol-station-jobs-manchester-roles-and-how-to-apply';

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
                'title' => 'Petrol Station Jobs in Manchester, England: Roles and How to Apply',
                'excerpt' => 'What petrol station staff in Manchester do, who actually employs them, a real GBP 13.50 night vacancy in Irlam, the 2026 minimum wage, right-to-work checks and why these jobs cannot be sponsored from abroad.',
                'content' => $content,
                'featured_image' => 'blogs/petrol-station-jobs-manchester-roles-and-how-to-apply.jpg',
                'tags' => 'petrol station jobs in manchester, manchester petrol station cashier jobs, part-time petrol station jobs, greater manchester forecourt jobs, forecourt assistant jobs manchester, petrol station jobs no experience, tesco petrol filling station jobs, uk minimum wage 2026',
                'meta_title' => 'Petrol Station Jobs in Manchester: Roles and How to Apply',
                'meta_description' => 'Petrol station jobs in Manchester: real roles and pay, who employs forecourt staff, the 2026 minimum wage, night shifts, right to work and how to apply.',
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
<p><strong>For a first petrol station job in Manchester, search for "customer service assistant", "petrol filling station colleague" and "forecourt assistant", then apply to the company that runs the station.</strong> Start with adverts that name the site, the hourly rate and the shift. One Greater Manchester example: The Kay Group advertised Friday and Saturday nights at Irlam Gateway, 11pm to 7am, at £13.50 an hour, for applicants aged 18 or over. The advert lists no experience requirement. We checked every vacancy and rate here on 31 October 2026; vacancies close without warning, so read the live page before you rely on it.</p>

<h2>What Does a Petrol Station Assistant Do?</h2>

<p>Most of the job is the till. A customer brings a sandwich to the counter and pays for fuel at the same time, so you confirm the pump number, check the amount and take the payment. If the figures do not match, stop and ask a colleague. Nobody minds an extra few seconds in the queue as much as they mind being charged for someone else's fuel.</p>

<p>Between customers you may take deliveries, rotate dated stock and refill shelves. Forecourt roles add outdoor checks and reporting problems around the pumps, so ask how much of the shift is outside in a Manchester winter. If an interviewer asks what you would do about a spill or a pump fault, the good answer is short: alert the trained colleague and follow the site procedure.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/petrol-station-jobs-manchester-roles-and-how-to-apply-2.jpg"
         alt="Two petrol station assistants in high-visibility vests on a Manchester forecourt, with shop, till and fuel pump scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where to Look in Manchester</h2>

<p>The name over the pumps is often not the name on your contract, so search by street as well as by fuel brand.</p>

<p><strong>The Kay Group.</strong> Its Irlam Gateway station is on Liverpool Road, Irlam, M44 6FW, and it is the only Greater Manchester address on the group's locations page. The web address of the vacancy still says Bolton Road, Darwen, which is a different station in Lancashire, but the page itself is for Irlam. Mention Irlam by name when you apply. Official page: <a href="https://www.thekaygroup.co.uk/careers/friday-saturday-nights-customer-service-assistant-bolton-road-service-station-darwen/" rel="noopener">The Kay Group: Irlam Gateway vacancy</a>.</p>

<p><strong>Dania Services.</strong> It says it runs two stations owned by MFG: BP on Princess Road, Fallowfield, and Esso on Stockport Road, Cheadle. The careers page also invites CVs for future openings, which is not the same as applying for a live vacancy, so say which you are doing. When we checked, the apply buttons on its three named roles led to unrelated pages, so confirm with the station before you send any documents. Official page: <a href="https://www.daniaservices.co.uk/careers" rel="noopener">Dania Services: Careers</a>.</p>

<p><strong>Tesco.</strong> Try "petrol filling station", "PFS" and "Esso Express" in its search. A Chorlton Esso Express role appeared when we looked, but the same search also returned Leeds, Blackburn and Liverpool, so check the location of every result. Official page: <a href="https://apply.tesco-careers.com/v2/job/search?location=manchester&amp;location_country=1" rel="noopener">Tesco Careers: Manchester search</a>.</p>

<p>Supermarket adverts need one more check. A customer assistant vacancy at a store with a forecourt may be for the main shop, so if you want to work around fuel, ask before the interview. And time the journey at the hours you will actually work. A bus that gets you to Irlam on a Friday evening may not run the same way when you finish at 7am on a Sunday.</p>

<h2>Requirements for Beginners and Students</h2>

<p>Read the essential criteria, not the title. "Retail experience preferred" leaves room for a first application; "must have supervised a shift" does not. Where no qualifications are listed, use your CV to show how you handle customers, numbers and instructions. Someone who helped at a school fundraiser could honestly write: "Took cash payments, checked change and counted the float with the organiser." Label it as volunteering.</p>

<p>Students should give availability they can keep, such as "Saturday and Sunday mornings throughout term". If you cannot do nights, say so and look for daytime roles. If your visa limits your working hours, check the limit against the rota before you accept.</p>

<p>Manager posts are a step up even at a small site. Dania's Retail Manager description covers day-to-day operations and managing a team. At interview, ask who controls the rota, who deals with till discrepancies and who is responsible when the manager is away.</p>

<h2>Pay, Hours and the Minimum Wage</h2>

<p>From April 2026 the UK minimum hourly rates are:</p>

<ul>
<li><strong>£12.71</strong> for workers aged 21 and over</li>
<li><strong>£10.85</strong> for ages 18 to 20</li>
<li><strong>£8</strong> for under-18s and for apprentices who qualify for that rate</li>
</ul>

<p>So an advert still offering £12 an hour to an ordinary worker aged 21 or over is out of date or below the legal minimum. GOV.UK already lists the next rise, to £13.21 for those aged 21 and over from April 2027.</p>

<p>The Irlam rate is one advert, not a Manchester average. Two eight-hour shifts at £13.50 come to £216 before tax, but only if all 16 hours are paid. Ask about unpaid breaks, contracted hours, whether £13.50 already includes any night premium, and whether you are paid weekly or monthly.</p>

<h2>Can You Apply From Abroad?</h2>

<p>You must prove your right to work before you start. British and Irish citizens can use a passport or the other documents GOV.UK lists; other workers generally use a share code or their eligible immigration documents.</p>

<p><strong>If you need visa sponsorship, this job will not get you one.</strong> Sales and retail assistants (occupation code 7111) and retail cashiers and check-out operators (7112) are marked ineligible for the Skilled Worker visa. A station operator holding a sponsor licence does not change that, because the occupation is what counts. Anyone offering a UK petrol station job with a work visa, particularly for a fee, deserves suspicion.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/petrol-station-jobs-manchester-roles-and-how-to-apply-3.jpg"
         alt="Petrol station assistant smiling at the pumps, with scenes of a till, a shop and a forecourt in Manchester"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply</h2>

<p>Keep the CV to one page: contact details, availability and one customer service example. Save the advert before you submit it, along with the station postcode, role title and shift pattern, so you know which job it is when a manager rings. If the advert says "hiring immediately", ask for the induction date before you give notice anywhere.</p>

<h2>What to Ask at the Interview</h2>

<p>For overnight work, ask the manager to talk you through the first hour of a shift. Who hands over the till? Who can authorise a refund? Who answers if a pump alarm sounds while customers are waiting? For "flexible hours", find out whose flexibility it means, and ask to see an example rota.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I have to be 18 for every petrol station job?</h3>
<p>The Irlam advert requires 18 or over and cites petroleum licensing rules. Check each vacancy, since a supermarket shop role can have different duties.</p>

<h3>Can I get a petrol station job with no experience?</h3>
<p>Yes, where the advert does not require it. The Irlam advert lists no experience requirement, but read the criteria each time.</p>

<h3>Can a petrol station sponsor my UK visa?</h3>
<p>Not under the Skilled Worker route. Codes 7111 and 7112 are ineligible.</p>

<h3>How do I compare full-time and part-time offers?</h3>
<p>Ask for the guaranteed weekly hours and how far ahead the rota is published, then compare them with your travel costs. Two long shifts can leave more take-home pay than four short ones at the same rate.</p>

<h3>Which petrol station offers the best benefits?</h3>
<p>Compare the written offers against what you will actually use. A fuel discount is worth little if you commute by tram; predictable finishing times may be worth more.</p>

<h2>Bottom Line</h2>

<p>Pick a station you can reach, a shift you can reliably cover and a role that matches your experience, then apply to the named operator with one specific example of dependable work.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.thekaygroup.co.uk/careers/friday-saturday-nights-customer-service-assistant-bolton-road-service-station-darwen/" rel="noopener">The Kay Group: Irlam Gateway vacancy</a> and <a href="https://www.thekaygroup.co.uk/locations/" rel="noopener">station addresses</a></li>
<li><a href="https://www.daniaservices.co.uk/careers" rel="noopener">Dania Services: Careers</a></li>
<li><a href="https://apply.tesco-careers.com/v2/job/search?location=manchester&amp;location_country=1" rel="noopener">Tesco Careers: Manchester search</a></li>
<li><a href="https://www.gov.uk/national-minimum-wage-rates" rel="noopener">GOV.UK: Minimum wage rates</a></li>
<li><a href="https://www.gov.uk/prove-right-to-work" rel="noopener">GOV.UK: Prove your right to work</a></li>
<li><a href="https://www.gov.uk/government/publications/skilled-worker-visa-eligible-occupations/skilled-worker-visa-eligible-occupations-and-codes" rel="noopener">GOV.UK: Skilled Worker eligible occupations and codes</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/gas-station-jobs-houston-hiring-age-requirements">Gas Station Jobs in Houston</a>, the same work in the USA.</li>
    <li><a href="/blog/petrol-pump-attendant-jobs-lahore-duties-salary">Petrol Pump Attendant Jobs in Lahore</a>, the same work in Pakistan.</li>
    <li><a href="/blog/how-to-get-cleaner-job-uk-from-pakistan-2026">How to Get a Cleaner Job in the UK From Pakistan</a>, another UK job that is hard to sponsor.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
