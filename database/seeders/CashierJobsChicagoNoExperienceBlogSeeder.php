<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Cashier Jobs in Chicago, Illinois With No Experience"
 *
 * Checked on 3 November 2026. What changed against the brief:
 *
 * 1. The two Indeed buttons are dropped. The Target and Chipotle links carried job IDs, so the guide links their careers
 *    search pages and gives the posting numbers as text.
 * 2. Target read: 630 N LaSalle R0000474749 on-demand at $18.50 (range to $27.75), not on the posted schedule, must
 *    work a shift every 4 weeks and at least one in 5 months; 2434 N Sacramento R0000465306 seasonal part-time, $18.50.
 * 3. Chipotle read: the Chicago crew post is at 2000 N Clybourn Ave, trains on grill, cashier, prep, salsa and expo,
 *    needs age 16, and shows base pay of $16.75 to $17.75 plus digital tips. The brief omitted the pay.
 * 4. Added the City of Chicago minimum wage from 1 July 2026: $17.05, $12.96 for tipped workers, employers with 4 or
 *    more employees. The low end of Chipotle's range is under $17.05, so the guide tells readers to ask.
 *
 * Posters are the owner's (blog-357, 2 Nov 10.27 to 10.29 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class CashierJobsChicagoNoExperienceBlogSeeder extends Seeder
{
    public const SLUG = 'cashier-jobs-chicago-no-experience';

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
                'title' => 'Cashier Jobs in Chicago, Illinois With No Experience',
                'excerpt' => 'Where Chicago hires cashiers with no experience, what Target\'s $18.50 on-demand and seasonal roles really mean, Chipotle crew pay against the $17.05 city minimum wage, and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/cashier-jobs-chicago-no-experience.jpg',
                'tags' => 'cashier jobs in chicago illinois with no experience, entry-level cashier jobs in chicago, part-time cashier jobs in chicago, chicago retail cashier jobs, target cashier jobs chicago, chicago minimum wage 2026, guest advocate jobs chicago, chipotle crew member chicago',
                'meta_title' => 'Cashier Jobs in Chicago With No Experience',
                'meta_description' => 'Cashier jobs in Chicago with no experience: Target and Chipotle roles, starting pay against the $17.05 minimum wage, shifts, and how to apply.',
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
<p><strong>You can get a cashier job in Chicago with no experience: look for employers that train new hires on the register, and search for "guest advocate", "front end associate" and "crew member" as well as "cashier".</strong> The detail that matters most is how you get your hours. Target advertised $18.50 an hour to start at its North LaSalle Drive store, but that role is on demand, so you pick up shifts rather than appearing on a weekly schedule. Chicago's minimum wage is $17.05 an hour from 1 July 2026 for employers with four or more workers, which gives you a floor to compare every offer against. We checked these adverts on 3 November 2026; vacancies close without notice.</p>

<h2>Chicago Employers and Roles to Check</h2>

<p><strong>Target, 630 N LaSalle Drive (posting R0000474749).</strong> An on-demand Guest Advocate role covering checkout, merchandise and order fulfilment, starting at $18.50 an hour. The $27.75 at the top of the advertised range is not the starting offer. Target says on-demand staff are not on the posted weekly schedule and pick up shifts through its myTime app, but must work at least one shift every four weeks, and anyone who works no shift in five months is let go. That suits a student fitting work around classes. It does not suit someone who needs the same pay every week, so ask how many shifts were actually available recently.</p>

<p><strong>Target, 2434 N Sacramento Avenue (posting R0000465306).</strong> A seasonal, part-time Guest Advocate role, also at $18.50, mixing cashiering with stocking and other store work. Seasonal is about how long the job lasts; on demand is about how shifts are given out. Ask when the season ends and whether you will be on a posted schedule.</p>

<p>Search for both by posting number. Official page: <a href="https://corporate.target.com/careers/job-search/store/stores" rel="noopener">Target: Store jobs search</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cashier-jobs-chicago-no-experience-2.jpg"
         alt="Cashier in a red apron handing a receipt across the counter in a Chicago store"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<p><strong>Chipotle crew member, 2000 N Clybourn Avenue.</strong> Chipotle trains crew to work any station: grill, cashier, prep, salsa and expo. It asks applicants to be at least 16 and welcomes people with no experience. The advert shows base pay of $16.75 to $17.75 an hour, plus digital tips. The bottom of that range is below Chicago's $17.05 minimum wage, which is only lawful if the tipped-worker rules apply, so ask which rate you would get and whether tips are counted towards it. If you want to spend most of a shift on the register, ask how often crew rotate. Official page: <a href="https://jobs.chipotle.com/" rel="noopener">Chipotle Careers</a>, search for Chicago.</p>

<h2>What Employers Mean by "No Experience"</h2>

<p>Read the qualifications before ruling yourself out. "Register experience preferred" leaves room for a first application; "one year of cash handling required" does not. A listing that teaches its till system may still need weekend availability or a minimum age.</p>

<p>The core of the job is knowing when to scan, when to count and when to call a supervisor. If a customer hands you $20 for a $13.47 purchase, the change is $6.53. Practise saying the amount received and counting the change back. If the customer then offers extra coins, stop and work it out again rather than juggling two totals while the queue grows.</p>

<h2>Compare Pay, Shifts and the Commute</h2>

<p>Compare jobs using hours you will really get. At $18.50, 16 hours a week is $296 before deductions and 20 hours is $370. Neither is a promise from Target. Ask whether breaks are paid, when schedules come out and whether training happens at the same store.</p>

<p>For evening work, plan the trip home as carefully as the trip in. A store that closes at 10pm may need staff to stay for cleanup, so check the bus or train for the time you would really leave. Search by neighbourhood or ZIP code, because a result labelled Chicago can be a distant suburb, where the city minimum wage does not apply.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cashier-jobs-chicago-no-experience-3.jpg"
         alt="Smiling cashier at a store register with the Chicago skyline and Lake Michigan in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply for a First Cashier Job</h2>

<p>Keep your resume to one page, with contact details and availability near the top. Use a real example from school, volunteering or family responsibilities. "Recorded each sale and counted the cash with the organizer at closing" beats "excellent customer service", but only if it is true.</p>

<p>Fill in the availability grid honestly. If the last bus means you must leave by 9pm, say so rather than ticking every closing shift. Save the posting number and street address, because several stores use the same job title and you want to know which manager is calling.</p>

<h2>What to Say at the Interview</h2>

<p><strong>"What would you do if a price scanned incorrectly?"</strong> Acknowledge the customer, check the item and the shelf label, and ask the authorised colleague to confirm any change under store policy. You do not need to promise a discount.</p>

<p><strong>"Have you used a register before?"</strong> If not, say so, then describe how you learned another task: watched, repeated it and asked someone to check your work. Finish by asking how new staff practise refunds and voids before doing them alone.</p>

<h2>A First-Shift Example</h2>

<p>Halfway through a sale, the customer says they already tapped their card, but the screen has not confirmed it. Tell them you are checking, look for the status message and ask your trainer if it is unclear. Do not ask them to pay again until you know what happened to the first attempt. At the end of training, ask who can approve a void, how to report a drawer that does not balance and where to find the next schedule.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum wage for cashiers in Chicago?</h3>
<p>$17.05 an hour from 1 July 2026 for employers with four or more employees, or $12.96 for tipped workers, whose employer must make up any shortfall to $17.05.</p>

<h3>Do I need a cashier certificate?</h3>
<p>Not for roles that train on the register. Check the advert's requirements before paying for a private course.</p>

<h3>Can a student get a part-time cashier job in Chicago?</h3>
<p>Yes. Look for shifts you can repeat all term, check the age rule (Chipotle asks for 16), and give the manager your earliest start and latest finish for each day.</p>

<h3>What does "on demand" mean at Target?</h3>
<p>You are not on the weekly schedule; you pick up shifts in its app. Target expects at least one shift every four weeks.</p>

<h3>Does "hiring immediately" mean I can start tomorrow?</h3>
<p>No. Ask about the interview, onboarding and first paid training shift, and check that the listing is still open.</p>

<h2>Bottom Line</h2>

<p>Pick a Chicago store you can reach that trains new staff and offers hours you can work, check the starting rate against the $17.05 minimum, and compare the real schedule before you accept.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://corporate.target.com/careers/job-search/store/stores" rel="noopener">Target: Store jobs</a>, postings R0000474749 and R0000465306</li>
<li><a href="https://jobs.chipotle.com/" rel="noopener">Chipotle Careers</a>, Chicago crew member, 2000 N Clybourn Ave</li>
<li><a href="https://www.chicago.gov/city/en/depts/bacp/supp_info/minimumwageinformation.html" rel="noopener">City of Chicago: Minimum wage</a>, all checked 3 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/gas-station-jobs-houston-hiring-age-requirements">Gas Station Jobs in Houston</a>, another first job with a till.</li>
    <li><a href="/blog/petrol-station-jobs-manchester-roles-and-how-to-apply">Petrol Station Jobs in Manchester</a>, the same work in England.</li>
    <li><a href="/blog/bus-driver-jobs-chicago-license-hiring-process">Bus Driver Jobs in Chicago</a>, a paid-training route in the same city.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you share documents.</li>
</ul>

HTML;
    }
}
