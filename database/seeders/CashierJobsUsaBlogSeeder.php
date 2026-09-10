<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Cashier Jobs in USA" — grocery, retail, pharmacy, fuel and quick-service
 * checkout work. It sits beside the retail jobs guide, which already separates
 * cashiers from sales associates and prices retail against state minimum
 * wages, so this one does not repeat that argument; it covers the checkout
 * specifics instead.
 *
 * Corrections to the draft:
 *
 * 1. It quotes entry-level cashier pay at $12 to $15 an hour and names
 *    California, Texas, New York, Florida and Ohio as the top states. $12 is
 *    below the 2026 state minimum wage in three of those five: California
 *    ($16.90), New York ($16.00 to $17.00) and Florida ($14.00, and $15.00
 *    from 30 September 2026).
 *
 * 2. Its "experienced cashier" band starts at $15, below the BLS median for
 *    all cashiers of $15.81 an hour in May 2025.
 *
 * 3. It lists fast food cashiers without saying that California's fast food
 *    minimum wage has been $20.00 an hour since 1 April 2024 at national
 *    chains with at least 60 establishments.
 *
 * 4. It omits the rule most new cashiers meet first: under the Fair Labor
 *    Standards Act, deductions for cash shortages are illegal if they take
 *    pay below the minimum wage.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CashierJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-cashier-jobs.html';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Cashier Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Twelve dollars an hour is below the state minimum wage in three of the five states cashier guides name, California fast food chains must pay at least $20, and a till shortage cannot be taken out of minimum wage.',
                'content' => $content,
                'featured_image' => 'blogs/cashier-jobs-in-usa.jpg',
                'tags' => 'cashier jobs usa, cashier salary, grocery store cashier jobs, fast food cashier jobs, gas station cashier jobs, part time cashier jobs, cashier jobs for teens, self checkout attendant, cashier minimum wage by state',
                'meta_title' => 'Cashier Jobs in USA 2026: Pay by State and Your Rights',
                'meta_description' => 'Cashier jobs in the USA: the BLS median of $15.81, why $12 an hour is illegal in California, New York and Florida, and the rules on till shortages.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Grocery, Retail & Quick-Service Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-cashier-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cashier — Grocery, Retail, Pharmacy and Quick-Service, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, including evenings, weekends and holidays; part-time schedules are common',
                'language' => 'English',
                // The legal floor runs from $7.25 to above $17 depending on the
                // state, and $20 at covered California fast food chains, so a
                // single national range would be wrong in most of the country.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Cashier roles in grocery, retail, pharmacy and quick-service stores across the US. Pay starts at your state minimum wage, not a national figure.',
                'seo_keywords' => 'cashier jobs usa, grocery store cashier, fast food cashier, part time cashier jobs, cashier salary by state',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Grocery chains, big-box and department stores, pharmacies, fuel and convenience stores and quick-service restaurants hire cashiers across the United States all year, with extra hiring before the holidays. It is one of the most common first jobs in the country and rarely asks for experience.</p>

<h3>What the work involves</h3>
<p>Scanning and bagging items, taking cash, card and mobile payments, processing returns and coupons, checking ID for age-restricted sales, balancing the till at the end of a shift, and helping customers at staffed and self-checkout lanes.</p>

<h3>Requirements</h3>
<ul>
    <li>Authorization to work in the United States, verified on Form I-9 when you are hired</li>
    <li>Basic maths, reliability and a friendly manner with customers</li>
    <li>Availability for evenings, weekends and holidays at most stores</li>
    <li>For workers aged 14 or 15, the federal limits on school-day and school-week hours</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The floor is set by your state.</strong> The federal minimum is $7.25 an hour, but many states set more, from $14.00 in Florida to $16.90 in California and $17.13 in Washington in 2026</li>
    <li><strong>California fast food.</strong> At least $20.00 an hour at covered national chains</li>
    <li><strong>Till shortages.</strong> A deduction for a cash shortage is illegal if it takes your pay below the minimum wage</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the offer against your state's minimum wage, and ask how till shortages are handled.</strong> An hourly rate below your state floor is not a starting rate; it is below the law.</p>

<p><strong>Note:</strong> pay, schedules and policies are set by each employer and by federal, state and local law &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Cashier is one of the most common first jobs in the United States. Grocery stores, pharmacies, fuel stations, big-box stores and fast food restaurants all need people on the register, and most hire with no experience at all. It is also a job where the pay ranges most guides publish are below the legal minimum in several of the states they recommend, and where the rules that protect a new cashier's paycheck go unmentioned.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-cashier-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128722; Browse Cashier Jobs in USA &rarr;
    </a>
</div>

<h2>$12 an Hour Is Illegal in Three of the Five States Guides Name</h2>

<p>Guides put entry-level cashier pay at <strong>$12 to $15 an hour</strong> and then list the top states for cashier jobs: California, Texas, New York, Florida and Ohio. Put the two together and the problem is obvious. The federal minimum wage is still <strong>$7.25 an hour</strong>, but most of those states set their own, higher floor for 2026:</p>

<ul>
    <li><strong>California:</strong> $16.90 an hour.</li>
    <li><strong>New York:</strong> $17.00 in New York City, Long Island and Westchester, and $16.00 in the rest of the state.</li>
    <li><strong>Florida:</strong> $14.00 an hour, rising to <strong>$15.00 from 30 September 2026</strong>.</li>
    <li><strong>Ohio:</strong> $11.00 an hour for most employers.</li>
    <li><strong>Texas:</strong> the federal $7.25.</li>
</ul>

<p>So $12 an hour is below the state minimum wage in <strong>three of the five</strong> states the guides name &mdash; California, New York and Florida &mdash; and even $15 is below the floor in California and New York. Washington's statewide minimum of <strong>$17.13</strong> is the highest in the country, and many cities set their own rate above their state's.</p>

<p>The practical rule is simple: <strong>your state's minimum wage is where cashier pay starts, not a national range</strong>. Our <a href="/blog/retail-jobs-in-usa">retail jobs in USA guide</a> sets out how far apart state floors sit and why a national average hides them; this guide does not repeat it.</p>

<h2>What Cashiers Actually Earn</h2>

<p>The Bureau of Labor Statistics measured the median cashier wage at <strong>$15.81 an hour in May 2025</strong>, about $32,880 a year for full-time hours. That is above the $15 floor guides give for an <em>experienced</em> cashier, so a typical cashier already earns more than the "experienced" band starts at.</p>

<p>Where you work matters as much as how long you have worked. The BLS medians by industry:</p>

<ul>
    <li><strong>Building material and garden supply stores:</strong> $16.87 an hour</li>
    <li><strong>Food and beverage retailers</strong> (grocery stores): $16.45</li>
    <li><strong>Restaurants and other food services:</strong> $15.15</li>
    <li><strong>General merchandise retailers</strong> (big-box and department stores): $15.02</li>
    <li><strong>Gasoline stations:</strong> $14.68</li>
</ul>

<p>Fuel and convenience stores pay least, and home improvement and grocery stores pay most. If you have a choice between two cashier jobs on the same street, the type of store is a better guide to the pay than the job title.</p>

<h2>Fast Food Cashiers in California Start at $20</h2>

<p>Guides list fast food cashier as one of the main types of cashier job without mentioning California's separate rule. Since <strong>1 April 2024</strong>, employees at limited-service restaurants belonging to a national chain with <strong>at least 60 establishments</strong> nationwide must be paid at least <strong>$20.00 an hour</strong> &mdash; more than $3 above the state's general minimum wage. A state Fast Food Council can raise that rate.</p>

<p>That means a cashier at a large burger or coffee chain in California starts well above a cashier at an independent café next door. Check which kind of employer you are applying to.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cashier-jobs-in-usa-grocery.jpg"
         alt="A cashier handing a card back to a customer at a grocery store checkout"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>A Till Shortage Cannot Come Out of Minimum Wage</h2>

<p>Every cashier eventually ends a shift with the drawer a few dollars short. Some employers respond by deducting the shortage from pay. Federal law limits that. The US Department of Labor states that deductions for items such as <strong>cash or merchandise shortages</strong> are not legal if they reduce an employee's wages below the minimum wage, or cut into overtime pay due under the Fair Labor Standards Act.</p>

<p>For a cashier paid at or near the minimum wage, that means a shortage deduction is very often simply unlawful, and signing an agreement to repay shortages does not change that. Several states restrict these deductions further. <strong>Ask at interview how till shortages are handled</strong>, and keep your own record of your cash counts.</p>

<h2>Self-Checkout Is Shrinking the Job, Not Closing It</h2>

<p>Guides say cashiers "remain essential" despite self-checkout. The BLS numbers are more precise. Employment of cashiers is projected to <strong>decline 6 per cent from 2025 to 2035</strong>, from about 3.1 million jobs, as self-checkout and online ordering absorb transactions.</p>

<p>Yet the same projection shows about <strong>521,300 openings every year</strong>, because so many people leave cashier jobs for other work. It is a shrinking occupation that still hires constantly. The self-checkout attendant role is part of that shift: fewer staffed lanes, with one worker supervising several machines, clearing errors and checking ID for age-restricted items.</p>

<h2>Starting at 14 or 15</h2>

<p>Cashier is one of the jobs federal law allows at 14. For workers aged <strong>14 and 15</strong>, the Fair Labor Standards Act limits work to:</p>

<ul>
    <li>No more than <strong>3 hours on a school day</strong> and 18 hours in a school week</li>
    <li>No more than 8 hours on a non-school day and 40 hours in a non-school week</li>
    <li>Between 7 a.m. and 7 p.m., extended to 9 p.m. from 1 June to Labor Day</li>
</ul>

<p>There are no federal hours limits at 16 and 17, although many states set their own rules and some require a work permit for minors. Selling tobacco and alcohol brings its own age rules too: the federal minimum age to <em>buy</em> tobacco has been 21 since December 2019, and states decide how old a cashier must be to sell alcohol.</p>

<h2>The Main Types of Cashier Job</h2>

<ul>
    <li><strong>Grocery store cashier.</strong> High volume, produce codes and SNAP/EBT payments. Some of the better-paying cashier work.</li>
    <li><strong>Retail and big-box cashier.</strong> Returns, store cards and seasonal peaks, with most hiring before the holidays.</li>
    <li><strong>Fast food and restaurant cashier.</strong> Order-taking as well as payment. In California, $20 an hour at covered chains.</li>
    <li><strong>Fuel station and convenience store cashier.</strong> Often night shifts and stocking duties, and the lowest industry median.</li>
    <li><strong>Pharmacy cashier.</strong> Prescription pick-up, privacy rules and insurance questions at the counter.</li>
    <li><strong>Self-checkout attendant.</strong> Supervising several machines at once, clearing errors and checking ID.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cashier-jobs-in-usa-pay.jpg"
         alt="A smiling cashier scanning groceries for a customer at a supermarket register"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Can Foreigners Get Cashier Jobs in the USA?</h2>

<p>Only with existing work authorization. Every new hire completes <strong>Form I-9</strong> to prove they may work in the United States, and cashier work is not a realistic visa route from abroad: US employers do not sponsor work visas for checkout jobs. If you already hold a green card, work-authorized status or an employment authorization document, you can apply like anyone else. Our <a href="/blog/unskilled-jobs-in-usa-for-foreigners">unskilled jobs in USA for foreigners guide</a> explains the H-2B and EB-3 routes that come closest, and our <a href="/blog/hotel-jobs-in-usa-for-foreigners">hotel jobs guide</a> covers seasonal hospitality work.</p>

<h2>How to Get Hired</h2>

<ul>
    <li><strong>Apply where the floor is higher.</strong> Grocery, home improvement and large fast food chains in higher-wage states pay the most.</li>
    <li><strong>Lead with availability.</strong> Evenings, weekends and the holiday season are what stores are short of.</li>
    <li><strong>Show cash confidence.</strong> Mention any job where you handled money, even informally.</li>
    <li><strong>Ask two questions at interview:</strong> the hourly rate against your state minimum, and how till shortages are handled.</li>
    <li><strong>Apply before the peaks.</strong> Holiday hiring starts in early autumn, and back-to-school hiring in July.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Do I need experience to get a cashier job in the USA?</h3>
<p>No. Most cashier jobs are entry-level with on-the-job training on the register. Basic maths, reliability and a friendly manner are usually enough.</p>

<h3>How much do cashiers earn in the USA?</h3>
<p>The BLS median was $15.81 an hour in May 2025. Grocery and home improvement stores pay more than that, and fuel stations less, and your state's minimum wage sets the floor.</p>

<h3>Is $12 an hour legal for a cashier?</h3>
<p>It depends on the state. It is below the 2026 minimum wage in California ($16.90), New York ($16.00 to $17.00) and Florida ($14.00), but legal in states that use the federal $7.25, such as Texas.</p>

<h3>How much do fast food cashiers make in California?</h3>
<p>At least $20.00 an hour since 1 April 2024 at limited-service restaurants in national chains with at least 60 establishments. Other restaurants follow the state minimum of $16.90.</p>

<h3>Can my employer take a cash shortage out of my pay?</h3>
<p>Not if the deduction takes your pay below the minimum wage or cuts overtime you are owed, according to the US Department of Labor. Several states restrict these deductions further.</p>

<h3>Can a 14-year-old work as a cashier?</h3>
<p>Yes, under federal law, with limits: no more than 3 hours on a school day, 18 hours in a school week, and only between 7 a.m. and 7 p.m. outside summer. States can set stricter rules.</p>

<h3>Are cashier jobs disappearing because of self-checkout?</h3>
<p>Shrinking, not disappearing. The BLS projects a 6 per cent decline from 2025 to 2035, but about 521,300 openings a year as workers move on.</p>

<h3>Can foreigners get cashier jobs in the USA with visa sponsorship?</h3>
<p>No. Employers do not sponsor work visas for cashier jobs. You need existing authorization to work in the US, which every employer checks on Form I-9.</p>

<h2>People Also Search For</h2>

<h3>Grocery store cashier jobs</h3>
<p>Among the better-paid cashier work, with a BLS median of $16.45 an hour in food and beverage retail.</p>

<h3>Gas station cashier pay</h3>
<p>The lowest industry median, $14.68 an hour, often with night shifts and stocking duties.</p>

<h3>Fast food cashier jobs California</h3>
<p>At least $20.00 an hour at national chains with 60 or more establishments.</p>

<h3>Cashier minimum wage by state</h3>
<p>From the federal $7.25 in states such as Texas to $16.90 in California and $17.13 in Washington in 2026.</p>

<h3>Part-time cashier jobs</h3>
<p>Common across grocery, retail and fast food, with evening and weekend shifts the easiest to get.</p>

<h3>Cashier jobs for 14 and 15 year olds</h3>
<p>Allowed under federal law with school-day limits of 3 hours and a 7 a.m. to 7 p.m. window outside summer.</p>

<h3>Self-checkout attendant jobs</h3>
<p>One worker supervising several machines, clearing errors and checking ID for age-restricted sales.</p>

<h3>Cashier cash shortage deduction</h3>
<p>Illegal under federal law if it takes your pay below the minimum wage or cuts overtime you are owed.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level work in the US and beyond? These cover it:</p>

<ul>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; cashier against sales associate, and how far apart state wage floors really are.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; another fast entry route, and what app pay guarantees leave out.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the H-2B and EB-3 routes, and who can realistically use them.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; seasonal hospitality work and the visas behind it.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; an office route with no degree requirement, and its real pay.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; customer-facing work without the register.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the same skills priced against provincial wage floors.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, careers or financial advice. Minimum wage rates, local ordinances, child labor rules and wage deduction rules change and differ by state and city. Confirm the current position with the US Department of Labor, your state labor department and the employer before applying or accepting an offer.</p>
HTML;
    }
}
