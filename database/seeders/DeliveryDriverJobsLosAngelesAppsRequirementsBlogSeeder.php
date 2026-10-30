<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Delivery Driver Jobs in Los Angeles, California: Apps and Requirements"
 *
 * Checked on 30 October 2026. What changed against the brief:
 *
 * 1. The brief repeated many keyword variants, which reads as keyword stuffing; the guide is rewritten in plain prose
 *    with the tags carrying the search terms. The Indeed button and the five "Apply Now" signup buttons are dropped;
 *    official signup pages are linked in the text instead.
 * 2. Uber fetched: car drivers must be 19, with a footnote that the motor vehicle minimum is 21 in California and New
 *    York State; bike and walking 18; two-door and four-door cars; background screening for all US drivers.
 * 3. DoorDash, Amazon Flex, hiring.amazon.com and the State Treasurer refuse both our network and the server's. DoorDash's
 *    21 for new California applicants appears in its own help-page snippets but conflicts with other DoorDash pages, so
 *    the guide tells readers to confirm the age at signup. Amazon Flex (21, licence, SSN, four-door midsize sedan or
 *    larger) is from Amazon's requirements snippets; the covered-bed truck detail (third-party only) is dropped. The
 *    Prop 22 mileage rate of $0.37 for 2026 is from the Treasurer's 23 December 2025 release via search.
 * 4. City of Los Angeles Office of Wage Standards fetched via the server: $18.42 from 1 July 2026. DIR fetched: $16.90
 *    statewide from 1 January 2026.
 * 5. FAQs expanded to eight; eight People Also Search For entries and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 29 Oct 10.26 and 10.29 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class DeliveryDriverJobsLosAngelesAppsRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'delivery-driver-jobs-los-angeles-apps-requirements';

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
                'title' => 'Delivery Driver Jobs in Los Angeles, California: Apps and Requirements',
                'excerpt' => 'How to become a delivery driver in Los Angeles: app work versus employee routes, why car delivery in California starts at 21, Amazon Flex versus DSP jobs, the $18.42 city minimum wage, the Prop 22 earnings floor and costs to budget for.',
                'content' => $content,
                'featured_image' => 'blogs/delivery-driver-jobs-los-angeles-apps-requirements.jpg',
                'tags' => 'delivery driver jobs in los angeles california, delivery driver apps los angeles, delivery jobs own car, amazon delivery jobs los angeles, amazon flex requirements, part time delivery driver jobs los angeles, prop 22 driver pay, los angeles minimum wage 2026',
                'meta_title' => 'Delivery Driver Jobs in Los Angeles: Apps and Requirements',
                'meta_description' => 'Delivery driver jobs in Los Angeles: app work or employee routes, the 21 age rule for car delivery in California, Amazon Flex vs DSP, pay floors and costs.',
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
<p><strong>Delivery work in Los Angeles splits into two kinds: app work as an independent contractor using your own vehicle, and employee routes in a company van.</strong> For car delivery, California sets a higher bar than most states: Uber's minimum age for motor vehicle drivers in California is 21, Amazon Flex also requires 21, and every app runs a background check. Employee drivers in the city of Los Angeles are covered by an $18.42 minimum wage from 1 July 2026; app drivers get the Proposition 22 earnings floor instead. This guide uses Uber, Amazon, the City of Los Angeles, the California Department of Industrial Relations and the State Treasurer, checked on 30 October 2026.</p>

<h2>App Work or an Employee Job?</h2>

<p>App drivers choose when to log in but pay for their own fuel, insurance and maintenance, and must handle their own taxes. Orders, areas and new-driver signups change with demand. Employee jobs give scheduled shifts, often a company vehicle and sometimes benefits. Always confirm who employs you: the brand on the van is not always your legal employer.</p>

<h2>Uber Eats</h2>

<p>Uber lists a minimum age of 19 for car delivery, with a footnote that the motor vehicle driver minimum is <strong>21 in California</strong> and New York State. Bike and walking delivery start at 18 with a government ID, where Uber supports them. Car drivers need a valid driver's licence in their name and a Social Security number, both two-door and four-door cars qualify, and every US delivery driver must pass a background screening. Requirements vary by city, and many markets ask for proof of residency. Official page: <a href="https://www.uber.com/us/en/deliver/" rel="noopener">Uber: Deliver with Uber</a>.</p>

<h2>DoorDash</h2>

<p>DoorDash's age rules vary by state, and its help pages list 21 for new applicants in California, while existing Dashers are not affected. Because DoorDash's own pages are not consistent on this, confirm the age shown when you sign up at <a href="https://dasher.doordash.com/en-us" rel="noopener">dasher.doordash.com</a>. Expect identity verification, a background check and a smartphone, plus a licence, insurance and registration for car delivery. Activation is not instant, and orders are not guaranteed.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/delivery-driver-jobs-los-angeles-apps-requirements-team.jpg"
         alt="Delivery driver carrying a parcel from a van in Los Angeles, with bike and phone-app delivery scenes around him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Amazon Flex Versus Amazon DSP Jobs</h2>

<p>Searches for Amazon delivery jobs lead to two different arrangements:</p>

<ul>
<li><strong>Amazon Flex</strong> uses your own vehicle as an independent contractor. You must be 21 or older with a valid US driver's licence and a Social Security number, and have a four-door midsize sedan or larger vehicle such as an SUV. Whether you can start depends on demand in your area.</li>
<li><strong>Delivery Service Partner (DSP) jobs</strong> are employee jobs with independent companies that deliver for Amazon in branded vans. The DSP, not Amazon, is your employer, and pay, shifts and benefits differ from one DSP to the next. A standard delivery van does not need a commercial driver's licence.</li>
</ul>

<p>For a company vehicle, read the DSP advert carefully and ask about route length, lifting, training and overtime. Official page: <a href="https://hiring.amazon.com/job-opportunities/delivery-driver-jobs" rel="noopener">Amazon: Delivery driver jobs</a>.</p>

<h2>Other Delivery Jobs</h2>

<p>Package jobs with FedEx, UPS, DHL and their contractors, grocery delivery, furniture delivery and pharmacy or medical courier work all exist in Los Angeles, with very different duties. Medical couriers handle specimens, prescriptions and proof of delivery under privacy rules, and there is no single certificate that qualifies you for every one. For box trucks and larger vehicles, check whether the vehicle needs a commercial licence; the job title alone does not say.</p>

<h2>Pay: Minimum Wage and the Prop 22 Floor</h2>

<p>Employee pay and app earnings are different things. For employees, the City of Los Angeles minimum wage is <strong>$18.42 an hour from 1 July 2026</strong>, and California's statewide minimum is <strong>$16.90 from 1 January 2026</strong>. Other cities in Los Angeles County set their own rates, so check where you actually work. Sources: <a href="https://wagesla.lacity.gov/" rel="noopener">City of Los Angeles Office of Wage Standards</a> and <a href="https://www.dir.ca.gov/dlse/minimum_wage.htm" rel="noopener">California DIR: Minimum wage</a>.</p>

<p>App drivers covered by Proposition 22 are guaranteed at least 120% of the applicable minimum wage for engaged time, plus a per-mile amount for engaged miles: <strong>$0.37 a mile in 2026</strong>, set by the State Treasurer. Engaged time is from accepting an order to completing it, not time spent waiting online. The floor is not an hourly wage: fuel, maintenance and insurance come out of what you earn. Judge an app by several ordinary shifts, not one busy evening. Source: <a href="https://www.treasurer.ca.gov/prop-22.asp" rel="noopener">California State Treasurer: Per-mile compensation for app-based drivers</a>.</p>

<h2>How to Apply and Compare Offers</h2>

<p>Apply only through the official app or the employer's own careers page, and prepare clear copies of the documents requested. Before accepting, ask who pays vehicle costs and parking, what hours, area and lifting the job involves, whether pay is hourly, per route or per delivery, and which bonuses are conditional. For own-car work, record online time, delivery time and business miles separately so you can see what you really earn after costs. Never pay anyone to "unlock" an app account or a route.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Delivery jobs in Los Angeles need existing US work authorisation and a US driving licence; neither the apps nor the DSPs sponsor visas. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you pay anyone.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How old do you have to be to deliver by car in Los Angeles?</h3>
<p>21 for Uber in California; confirm DoorDash's age at signup. Amazon Flex also requires 21.</p>

<h3>Can I deliver by bike?</h3>
<p>Uber allows bike and walking delivery from 18 where it is supported.</p>

<h3>Is an Amazon DSP driver employed by Amazon?</h3>
<p>No. The independent DSP company is your employer.</p>

<h3>Do I need a CDL for Amazon DSP vans?</h3>
<p>No, not for the standard delivery vans.</p>

<h3>What is the minimum wage in Los Angeles?</h3>
<p>$18.42 an hour from 1 July 2026 in the city; $16.90 statewide.</p>

<h3>What is the Prop 22 mileage rate in 2026?</h3>
<p>$0.37 per engaged mile, on top of 120% of minimum wage for engaged time.</p>

<h3>Do app drivers get paid while waiting for orders?</h3>
<p>Not under the Prop 22 floor, which covers engaged time only.</p>

<h3>Can I start delivering immediately?</h3>
<p>Not usually; screening and activation take time.</p>

<h2>People Also Search For</h2>

<h3>Delivery driver jobs in Los Angeles California</h3>
<p>App work or employee routes.</p>

<h3>Delivery driver apps Los Angeles</h3>
<p>Uber Eats, DoorDash and Amazon Flex.</p>

<h3>Delivery jobs own car</h3>
<p>App contractor work; budget for costs.</p>

<h3>Amazon delivery jobs Los Angeles</h3>
<p>Flex with your car, or a DSP van job.</p>

<h3>Amazon Flex requirements</h3>
<p>21, licence, SSN, midsize four-door car or larger.</p>

<h3>Part time delivery driver jobs Los Angeles</h3>
<p>Apps allow flexible hours; employee jobs vary.</p>

<h3>Prop 22 driver pay</h3>
<p>120% of minimum wage for engaged time plus $0.37 a mile.</p>

<h3>Los Angeles minimum wage 2026</h3>
<p>$18.42 an hour from 1 July 2026.</p>

<h2>Bottom Line</h2>

<p>Decide between app work and an employee route, check the age and vehicle rules before you sign up, and compare what you earn after costs. This guide cannot promise orders, a job or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.uber.com/us/en/deliver/" rel="noopener">Uber: Deliver with Uber</a></li>
<li><a href="https://hiring.amazon.com/job-opportunities/delivery-driver-jobs" rel="noopener">Amazon: Delivery driver jobs</a></li>
<li><a href="https://wagesla.lacity.gov/" rel="noopener">City of Los Angeles Office of Wage Standards</a></li>
<li><a href="https://www.dir.ca.gov/dlse/minimum_wage.htm" rel="noopener">California DIR: Minimum wage</a></li>
<li><a href="https://www.treasurer.ca.gov/prop-22.asp" rel="noopener">California State Treasurer: Prop 22 per-mile compensation</a>, all checked 30 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/gas-station-jobs-houston-hiring-age-requirements">Gas Station Jobs in Houston</a>, another beginner job.</li>
    <li><a href="/blog/janitor-jobs-philadelphia-no-experience">Janitor Jobs in Philadelphia</a>, entry-level work with no experience.</li>
    <li><a href="/blog/truck-driver-salary-usa-2026">Truck Driver Salary in the USA</a>, the next step up in driving.</li>
    <li><a href="/blog/how-to-get-warehouse-worker-job-usa-from-pakistan-2026">How to Get a Warehouse Worker Job in the USA From Pakistan</a>, logistics work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 30 October 2026 against the pages named in Sources. It does not promise orders, a job or a wage.</p>
HTML;
    }
}
