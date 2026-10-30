<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Gas Station Jobs in Houston, Texas: Hiring Age and Requirements"
 *
 * Checked on 30 October 2026. What changed against the brief:
 *
 * 1. The brief repeated many keyword variants, which reads as keyword stuffing; the guide is rewritten in plain prose
 *    with the tags carrying the search terms. The two Indeed buttons are dropped. The closed Circle K example
 *    (R599306, deadline passed) is not described.
 * 2. QuikTrip fetched: Store Clerk, Houston (Richey Rd), requisition 15317, must be 16 or older, retail store
 *    experience desired, no pay printed on the advert, posted 9 January 2026. 7-Eleven fetched: Restaurant Crew at
 *    4085 Gulf Freeway, entry level, experience "great, but not required".
 * 3. Texas sites refuse our network, so TWC, TABC and the Comptroller were fetched via the server. TWC: under 14 may
 *    not work in most situations; 14 and 15 only in non-hazardous jobs outside school hours with hour limits; 16 and 17
 *    any hours but no hazardous jobs; $7.25 minimum wage, the same under Texas law. TABC: no minimum employee age for
 *    off-premise beer and wine (BQ) or retail dealer (BF) permits, 16 for wine-only package stores, 18 on-premises; no
 *    minimum age for seller/server training. Comptroller: employees under 21 may sell tobacco where their duties need
 *    it; the federal purchase age is 21 with no exceptions; retailers must get a signed employee notification form.
 * 4. FAQs expanded to eight and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 29 Oct 10.36 and 10.41 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class GasStationJobsHoustonHiringAgeRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'gas-station-jobs-houston-hiring-age-requirements';

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
                'title' => 'Gas Station Jobs in Houston, Texas: Hiring Age and Requirements',
                'excerpt' => 'How old you must be to work at a Houston gas station, what cashiers do, why you do not need to be 21 to sell beer or tobacco, what QuikTrip and 7-Eleven ask beginners for, the $7.25 Texas minimum wage and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/gas-station-jobs-houston-hiring-age-requirements.jpg',
                'tags' => 'gas station jobs in houston tx, gas station cashier jobs in houston tx, entry level gas station jobs in houston tx, gas station jobs with no experience in houston tx, convenience store jobs in houston tx, how old to work at a gas station in texas, quiktrip jobs houston, tabc certification',
                'meta_title' => 'Gas Station Jobs in Houston, TX: Hiring Age and Requirements',
                'meta_description' => 'Gas station jobs in Houston: hiring age, cashier duties, why under-21s can sell beer and tobacco, what QuikTrip and 7-Eleven ask for, and how to apply.',
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
<p><strong>There is no single hiring age for Houston gas station jobs: Texas law lets most teenagers work from 14 with limits, and each employer sets its own minimum, often 16 or 18.</strong> QuikTrip's Houston store clerk post, for example, requires applicants to be 16 or older. You do not have to be 21 to sell beer or tobacco at a convenience store, even though customers must be 21 to buy tobacco. Cashier jobs are open to beginners at many stores; 7-Eleven calls its Houston restaurant crew post entry level. This guide uses QuikTrip, 7-Eleven, the Texas Workforce Commission, the Texas Alcoholic Beverage Commission and the Texas Comptroller, checked on 30 October 2026.</p>

<h2>What Gas Station Employees Do</h2>

<p>Cashiers and store clerks take payments, help customers with fuel purchases, stock shelves, clean counters and check ID for age-restricted sales. Many also tidy outdoor areas, report pump problems, prepare food or clean restrooms, so do not assume a cashier stays at the register all shift. QuikTrip describes its store clerk job as customer relations, merchandising, store appearance and business operations, and lists retail experience as desired rather than required. Source: <a href="https://careers.quiktrip.com/QuikTrip/job/Store-Clerk-Houston-%28Richey-Rd%29/15317-en_US/" rel="noopener">QuikTrip: Store Clerk, Houston (Richey Rd)</a>.</p>

<h2>How Old Must You Be?</h2>

<p>The Texas Workforce Commission explains the legal minimums: in most situations children under 14 may not work for an employer. At 14 and 15 you may work only in non-hazardous jobs, outside school hours, with limits on daily and weekly hours. At 16 and 17 you may work any hours but not in hazardous jobs, and from 18 there are no hour or duty limits beyond safety rules. Employers can and do set higher ages: QuikTrip's Houston clerk post requires 16 or older, and many stores hire only at 18. Search for adverts that state an age you meet. Source: <a href="https://efte.twc.texas.gov/flsa_does_and_doesnt_do.html" rel="noopener">TWC: What the FLSA does and does not do</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/gas-station-jobs-houston-hiring-age-requirements-team.jpg"
         alt="Gas station attendant in a red and black uniform at a Houston fuel station, with cashier and store scenes around him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Alcohol, Tobacco and TABC Certification</h2>

<p>The age at which a customer may buy something and the age at which you may sell it are different rules. For alcohol, TABC sets <strong>no minimum employee age</strong> at stores holding a Wine and Beer Retailer's Off-Premise Permit (BQ) or a Retail Dealer's Off-Premise License (BF), the permits most convenience stores hold. Wine-only package stores need employees of 16, liquor stores have their own rules, and no one under 18 may sell or serve alcohol at on-premises places such as bars. There is no minimum age to take TABC seller/server training, which many employers ask for even where the law does not. Source: <a href="https://www.tabc.texas.gov/faqs/" rel="noopener">TABC: FAQs</a>.</p>

<p>For tobacco, the Texas Comptroller confirms that an employee under 21 may sell cigarettes and e-cigarettes where the job requires it. The federal minimum age to buy tobacco is 21, with no exceptions, and the retailer must get a signed employee notification form showing you understand the law. Source: <a href="https://comptroller.texas.gov/taxes/tobacco/faq-reg.php" rel="noopener">Texas Comptroller: Tobacco retailer FAQs</a>.</p>

<h2>Applying Without Experience</h2>

<p>Many stores hire beginners. 7-Eleven's Restaurant Crew post at its 4085 Gulf Freeway store in Houston is described as entry level, and says cashier, customer service, retail or foodservice experience is great but not required. Search for store clerk, store associate, cashier and crew member as well as "gas station". Show dependable attendance, basic maths and clear communication, with examples from school, volunteering or other work, and be ready to explain how you would handle a wrong payment or an angry customer. Source: <a href="https://careers.7-eleven.com/job/houston/restaurant-crew/45445/76194108544" rel="noopener">7-Eleven: Restaurant Crew, Houston</a>.</p>

<h2>Where to Look</h2>

<p>Use each chain's own careers site and filter for Houston: QuikTrip, 7-Eleven and Circle K all list store jobs online. For Shell, Chevron or Exxon stations, check the employer named in the advert, because the fuel brand rarely employs the store staff; Shell's own careers site mostly lists energy-company jobs. Texas's state job board, WorkInTexas, is linked from our portal listing.</p>

<h2>Pay and Hours</h2>

<p>Pay varies by chain, shift and duties, and QuikTrip publishes its store pay rates on its careers site rather than on each advert. The legal floor is the federal minimum wage of <strong>$7.25 an hour</strong>, which is also the Texas rate, with overtime at time-and-a-half after 40 hours a week; that is a minimum, not a typical starting rate. As an illustration, $15 an hour for 40 hours is $600 a week before deductions. Check that an advertised rate is base pay, not pay including bonuses, and ask about guaranteed hours, paid training, benefits and how often you are paid.</p>

<h2>Part-Time and Overnight Shifts</h2>

<p>Part-time shifts suit students, within the hour limits for 14- and 15-year-olds. For overnight shifts, ask how many staff are on duty, what the robbery and emergency procedures are, who supervises and how you get home. "Hiring immediately" does not mean you start tomorrow.</p>

<h2>How to Apply</h2>

<p>Prepare a short resume with your availability and any customer-facing experience, apply through the chain's own site, and note the store address and confirmation. In the interview, talk about cash accuracy, customer service and following safety procedures, and ask about training and the real starting rate. Never send ID documents to an unverified social media account or pay anyone for a guaranteed job.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Gas station jobs in Houston need existing US work authorisation; store chains do not sponsor visas for them. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you pay anyone.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can a 16-year-old work at a Houston gas station?</h3>
<p>Yes, where the employer allows it; QuikTrip's Houston clerk post accepts 16-year-olds.</p>

<h3>Can a 14-year-old work at a gas station in Texas?</h3>
<p>The law allows non-hazardous work outside school hours with hour limits, but most chains hire older.</p>

<h3>Do I need to be 21 to sell beer at a convenience store?</h3>
<p>No. TABC sets no minimum employee age at off-premise beer and wine stores.</p>

<h3>Are manager jobs open to beginners?</h3>
<p>Rarely; they usually need retail or supervisory experience.</p>

<h2>Bottom Line</h2>

<p>Check each store's minimum age, duties and pay, apply on the chain's own site, and ask about training and safety on night shifts.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://careers.quiktrip.com/QuikTrip/job/Store-Clerk-Houston-%28Richey-Rd%29/15317-en_US/" rel="noopener">QuikTrip: Store Clerk, Houston (Richey Rd)</a></li>
<li><a href="https://careers.7-eleven.com/job/houston/restaurant-crew/45445/76194108544" rel="noopener">7-Eleven: Restaurant Crew, Houston</a></li>
<li><a href="https://efte.twc.texas.gov/flsa_does_and_doesnt_do.html" rel="noopener">Texas Workforce Commission: What the FLSA does and does not do</a></li>
<li><a href="https://www.tabc.texas.gov/faqs/" rel="noopener">TABC: FAQs</a></li>
<li><a href="https://comptroller.texas.gov/taxes/tobacco/faq-reg.php" rel="noopener">Texas Comptroller: Tobacco retailer FAQs</a>, all checked 30 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-houston-license-training">Security Guard Jobs in Houston</a>, another Houston entry route.</li>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, a licensed career in the same city.</li>
    <li><a href="/blog/delivery-driver-jobs-los-angeles-apps-requirements">Delivery Driver Jobs in Los Angeles</a>, another beginner job.</li>
    <li><a href="/blog/janitor-jobs-philadelphia-no-experience">Janitor Jobs in Philadelphia</a>, entry-level work with no experience.</li>
</ul>

HTML;
    }
}
