<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Driver Jobs in Dubai With Visa: How to Apply and What to Avoid"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. All Indeed material is dropped: the buttons, the NRTC advert (a job-ID link we could not verify, source of the
 *    AED 2,500-2,800 figure) and the AED 2,567 Indeed salary.
 * 2. The brief told Pakistani, Indian and Nepali applicants to "check with RTA". RTA's page was read: none of the three
 *    is on its 20-country exchange list, and the fallback is a theory and road test, so the guide says so plainly.
 *    Heavy categories are exchangeable only where the table allows all categories.
 * 3. RTA's Taxi or Luxury Vehicle permit page was read: the company applies, driver 21 to 65, residence under the same
 *    sponsor, one year, AED 200 permit, AED 2,045 training and AED 120 theory test.
 * 4. The brief's u.ae links return 404; live pages used. Article 6(4) read in the Labour Law PDF. The UAE lists
 *    personal/family driver as the 19th domestic occupation. The MoHRE lookup needs a transaction number.
 *
 * Posters are the owner's (blog-356, 31 Oct 9.52 to 9.55 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class DriverJobsDubaiWithVisaBlogSeeder extends Seeder
{
    public const SLUG = 'driver-jobs-dubai-with-visa';

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
                'title' => 'Driver Jobs in Dubai With Visa: How to Apply and What to Avoid',
                'excerpt' => 'Why Pakistani, Indian and Nepali licences cannot be exchanged in Dubai, how RTA driver permits work, who pays for your visa, and how to check a driver job offer before you pay anyone or travel.',
                'content' => $content,
                'featured_image' => 'blogs/driver-jobs-dubai-with-visa.jpg',
                'tags' => 'driver jobs in dubai with visa, house driver jobs in dubai, heavy driver jobs in dubai, dubai driver visa price, dubai driver visa requirements, dubai taxi driver jobs, uae driving licence exchange, driver jobs in dubai for pakistani',
                'meta_title' => 'Driver Jobs in Dubai With Visa: How to Apply, What to Avoid',
                'meta_description' => 'Driver jobs in Dubai with visa: licence exchange rules for Pakistan, India and Nepal, RTA permits, who pays visa costs and how to verify an offer.',
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
<p><strong>To get a driver job in Dubai with a visa, you need an employer who recruits from abroad and arranges your work permit, plus a UAE driving licence for the vehicle you will drive.</strong> Those are separate problems. A company can offer you a job and still be unable to put you behind the wheel. If your licence is from Pakistan, India or Nepal, you cannot simply swap it: none of the three is on the Dubai Roads and Transport Authority (RTA) exchange list, so plan on the theory and road test. Do not pay a recruiter for a "driver visa", and do not start driving on a visit visa. We checked these rules on 31 October 2026.</p>

<h2>Choose the Right Driving Job</h2>

<p><strong>Light vehicle and company drivers.</strong> You might take staff to appointments, collect documents or deliver stock. Write your licence class on your CV rather than simply saying you can drive. For hotel and airport transfer jobs, ask whether you will drive a staff vehicle, a guest shuttle or a commercial limousine, because the same pickup point can involve different vehicles and permits.</p>

<p><strong>House and personal drivers.</strong> If a family employs you, the UAE treats you as a domestic worker: "personal/family driver" is one of the 19 occupations on its list, and the employer must provide appropriate accommodation. Ask whose name goes on the contract, where you will live and how rest time works between school runs and evening pickups. A chauffeur with a transport company has a different arrangement.</p>

<p><strong>Truck and bus drivers.</strong> Match the licence category to the actual vehicle. Someone hiring a heavy bus driver may want passenger transport experience even if you have driven trucks for years, and many adverts ask for an existing UAE heavy licence and UAE experience. Read that line before you assume the job is open to overseas applicants.</p>

<p><strong>Taxi and school transport.</strong> RTA issues separate permits for school bus drivers, taxi or luxury vehicle drivers and tourist vehicle drivers. Dubai Taxi Company says priority "comes to those who have long experience in taxi driving". Its careers page has a Join our Fleet route but promises no visa or start date. Official page: <a href="https://www.dubaitaxi.ae/en/careers" rel="noopener">Dubai Taxi Company: Careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/driver-jobs-dubai-with-visa-2.jpg"
         alt="Driver in a high-visibility vest beside a white van with the Dubai skyline behind, with passport and vehicle scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Can You Use Your Pakistani, Indian or Nepali Licence?</h2>

<p>This is where most plans go wrong. RTA's exchange service is for residents holding a valid licence from one of 20 listed countries: Austria, Belgium, Britain, Denmark, Finland, France, Germany, Hong Kong, Italy, Japan, the Netherlands, Norway, Poland, Romania, South Africa, Spain, Sweden, Switzerland, Turkey and the United States. Pakistan, India and Nepal are not on it.</p>

<p>If your licence is not eligible, RTA says you can apply online for the theory and road test instead. Even listed countries are not all equal: the exchange table limits some to light vehicles, and heavy truck licences are exchangeable only where all categories are allowed. Check the page for your exact licence, because RTA updates it. Official page: <a href="https://www.rta.ae/wps/portal/rta/ae/home/rta-services/service-details?lang=en&amp;serviceId=121" rel="noopener">RTA: Foreign driving licence exchange</a>.</p>

<p>So read the advert carefully. "Valid UAE licence required" means the employer expects you to have it already. If overseas applicants are welcome, ask in writing whether you do the licence after selection, where the training happens, who pays for it and whether you are paid during it. Get those answers before you resign from a job at home.</p>

<h2>RTA Permits: The Taxi Example</h2>

<p>A UAE licence is not always the last step. For the Taxi or Luxury Vehicle permit, the company applies rather than the driver. RTA's rules include:</p>

<ul>
<li>Age 21 to 65</li>
<li>Your residence must be under the same sponsor as the company</li>
<li>The permit is digital and lasts one year</li>
<li>Listed new-permit fees: AED 200 permit, AED 2,045 training package, AED 120 theory test</li>
<li>You may drive "only for the company under which he's registered"</li>
</ul>

<p>That last rule answers a common question. A taxi or limousine permit from one operator does not let you pick up shifts for another. Ask your employer in writing who pays the fees above. Official page: <a href="https://rta.ae/wps/portal/rta/ae/home/rta-services/service-details?serviceId=629" rel="noopener">RTA: Professional driver permit</a>.</p>

<h2>Dubai Driver Visa Requirements and Price</h2>

<p>There is no separate "driver visa". For a mainland company, the usual path is:</p>

<ol>
<li>The employer signs a job offer and sends it to you, directly or through a recruitment agency.</li>
<li>You sign it; it must be in Arabic and English, and you can ask for a third language such as Urdu, Hindi or Nepalese.</li>
<li>The employer attaches it to the application for initial work permit approval from the Ministry of Human Resources and Emiratisation (MoHRE), which allows you to enter the UAE.</li>
<li>After you arrive, the offer is registered as your employment contract, within 14 days.</li>
</ol>

<p>Your passport must be valid for at least six months. And <strong>there is no "visa price" you should be paying an agent</strong>. Article 6 of the UAE Labour Law prohibits employers from charging workers recruitment and employment fees and costs, directly or indirectly, and only licensed firms may act as recruiters. Your own driving lessons or a licence exchange are a separate matter, so ask for an official itemised quotation, never a combined "visa and job reservation" payment.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/driver-jobs-dubai-with-visa-3.jpg"
         alt="Driver in a blue shirt giving a thumbs up from behind the wheel in Dubai, with an aircraft, a van and a passport in smaller scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Compare Salary, Accommodation and Commission</h2>

<p>We do not repeat salary averages from job sites, because they mix very different driving jobs. Judge the written offer. A taxi commission, delivery bonus or overtime estimate is not guaranteed basic pay.</p>

<p>For delivery jobs, ask who provides the vehicle and who pays for fuel, parking, tolls, insurance and maintenance. For jobs with accommodation, get the location, room arrangements and transport to the depot in writing. If an air ticket is included, ask whether it is the joining journey or an annual leave ticket.</p>

<h2>How to Apply and Verify an Urgent Offer</h2>

<p>Put your licence category, issuing country, expiry date and experience near the top of your CV, along with your current location and whether you need sponsorship. For trucks, describe recent vehicles and loads. For passengers, mention routes, passenger numbers and vehicle checks. Do not present personal driving as professional experience.</p>

<p>When a recruiter replies, match the legal employer, vehicle, salary and worksite against the written offer. MoHRE's lookup page takes a transaction number, passport number, nationality and date of birth, so ask for the transaction number and check it yourself: <a href="https://receipts.mohre.gov.ae/OfferInquiry/Index?langid=2" rel="noopener">MoHRE: Offer and contract inquiry</a>. If the recruiter's email or payment instructions differ from the company's published details, contact the company directly.</p>

<h2>A Useful Interview Question</h2>

<p>Ask the dispatcher to describe a recent shift from vehicle collection to handover. How many stops were assigned? What happened when a customer was absent? Did the return trip count as working hours? A family driver should ask how much notice comes with evening journeys, and a truck driver where loading happens and who signs it off. Those answers tell you more than the highest earnings figure in an advert.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I exchange my Pakistani driving licence in Dubai?</h3>
<p>No. Pakistan, India and Nepal are not on RTA's list of 20 countries. RTA says holders of non-eligible licences can apply online for the theory and road test.</p>

<h3>Can I start urgent driver jobs in Dubai immediately?</h3>
<p>Ask for the earliest lawful start date. Being available at once does not replace a work permit, the correct licence or a professional permit.</p>

<h3>Are driving jobs in Dubai with free visa legitimate?</h3>
<p>The phrase proves nothing. Verify the employer and the offer. A demand for money to reserve a vehicle, unlock a visa quota or secure an interview is a reason to stop.</p>

<h3>Can I work part-time for another operator?</h3>
<p>Not on a taxi or luxury vehicle permit, which covers only the company you are registered under. For other jobs, check your work permit first.</p>

<h3>Can I drive on a visit visa while I wait?</h3>
<p>No. The UAE prohibits working on a visit or tourist visa, paid or unpaid.</p>

<h2>Bottom Line</h2>

<p>Apply for the vehicle you are licensed for, or get a written training plan first. Verify the sponsor and offer before you travel, and compare guaranteed pay after your driving and housing costs.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.rta.ae/wps/portal/rta/ae/home/rta-services/service-details?lang=en&amp;serviceId=121" rel="noopener">RTA: Foreign driving licence exchange</a> and <a href="https://rta.ae/wps/portal/rta/ae/home/rta-services/service-details?serviceId=629" rel="noopener">Professional driver permit</a></li>
<li><a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/Job-offers-and-the-employment-process" rel="noopener">UAE Government: Job offers and the employment process</a> and <a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/preparing-to-work" rel="noopener">Preparing to work</a></li>
<li><a href="https://assets.u.ae/api/public/content/eb65a60bbff949fa912eddd566613014?v=7174ed78" rel="noopener">UAE Labour Law, Federal Decree-Law No. 33 of 2021, Article 6</a></li>
<li><a href="https://u.ae/en/information-and-services/jobs/Workplace-regulations/domestic-helpers" rel="noopener">UAE Government: Domestic workers</a></li>
<li><a href="https://www.dubaitaxi.ae/en/careers" rel="noopener">Dubai Taxi Company: Careers</a> and <a href="https://receipts.mohre.gov.ae/OfferInquiry/Index?langid=2" rel="noopener">MoHRE: Offer inquiry</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/best-countries-for-truck-drivers-to-work-abroad-2026">Best Countries for Truck Drivers Abroad</a>, if Dubai is not the right fit.</li>
    <li><a href="/blog/hotel-jobs-dubai-foreigners-roles-requirements">Hotel Jobs in Dubai for Foreigners</a>, the same permit rules.</li>
    <li><a href="/blog/cleaner-jobs-dubai-requirements-accommodation">Cleaner Jobs in Dubai</a>, another entry-level route.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
