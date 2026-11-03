<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Courier Jobs in Manchester, England: Self-Employed vs Employed"
 *
 * Checked on 3 November 2026. What changed against the brief:
 *
 * 1. The two Indeed buttons are dropped. The Tesco link carried a record ID, so the advert is described as text and
 *    the guide links Tesco's Manchester search instead.
 * 2. DPD's driver site returned Access Denied, so its GBP 50,000-60,000 owner-driver revenue claim is left out.
 * 3. Tesco Manchester Gorton Extra read: from GBP 13.28 an hour, at least 16 guaranteed hours (12 by choice), shifts
 *    scheduled inside availability windows with 3 weeks' notice. The brief missed the 16-hour guarantee.
 * 4. Evri FAQ read: full valid licence, car or van with MOT and insurance, right to work, no unspent convictions; paid
 *    monthly by BACS with no tax deducted; optional top-up insurance; hire vehicles allowed if insured for courier work.
 * 5. Ocado Manchester read: a Delivery Driver post at Spoke Manchester, about 50 minutes from the city centre.
 *    GOV.UK worker status and the GBP 1,000 sole trader registration rule read.
 *
 * Posters are the owner's (blog-357, 2 Nov 10.36 to 10.37 PM), used as supplied on the owner's instruction. The
 * portal listing is in GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class CourierJobsManchesterSelfEmployedVsEmployedBlogSeeder extends Seeder
{
    public const SLUG = 'courier-jobs-manchester-self-employed-vs-employed';

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
                'title' => 'Courier Jobs in Manchester, England: Self-Employed vs Employed',
                'excerpt' => 'Compare employed and self-employed courier work in Manchester: Tesco\'s GBP 13.28 with 16 guaranteed hours, Evri\'s monthly pay with no tax deducted, Ocado, worker rights, and pay after van costs.',
                'content' => $content,
                'featured_image' => 'blogs/courier-jobs-manchester-self-employed-vs-employed.jpg',
                'tags' => 'courier jobs in manchester england, self-employed courier jobs in manchester, employed delivery driver jobs manchester, manchester parcel delivery jobs, evri courier manchester, tesco delivery driver manchester, ocado delivery driver manchester, sole trader courier tax uk',
                'meta_title' => 'Courier Jobs in Manchester: Self-Employed vs Employed',
                'meta_description' => 'Courier jobs in Manchester: employed versus self-employed pay, guaranteed hours, Evri, Tesco and Ocado, van costs, worker rights and tax registration.',
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
<p><strong>For courier jobs in Manchester, choose an employed role if you need guaranteed hours, paid holiday and a vehicle supplied for the shift, and self-employed work if you want to pick rounds and can carry the vehicle costs when work is quiet.</strong> Compare what you keep per hour after costs, not the biggest number in an advert. Tesco's Manchester Gorton Extra delivery driver advert, for example, pays from £13.28 an hour with at least 16 guaranteed hours a week; Evri's self-employed couriers are paid monthly with no tax taken off, so they handle their own. We checked these on 3 November 2026.</p>

<h2>What Changes With Each Contract?</h2>

<p>An employed driver is paid through payroll. Ask how many hours are guaranteed, whether loading time is paid and where the shift ends. The employer may own the van, but that does not mean you can take it home, so check how you will reach the depot for an early start.</p>

<p>A genuinely self-employed courier runs a small business and pays its costs. UK law also has a middle category, the <strong>worker</strong>. GOV.UK says someone is generally a worker if they must do the work personally, have only a limited right to send someone else, and are not running their own business with the "employer" as a client. Workers are entitled to the National Minimum Wage, paid holiday and rest breaks. What happens in practice decides your status, not the word "partner" in a contract.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/courier-jobs-manchester-self-employed-vs-employed-2.jpg"
         alt="Courier in a blue polo holding a parcel and giving a thumbs up, with a delivery van and route map on a phone"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Manchester Roles and Where to Look</h2>

<p><strong>Tesco grocery delivery, Gorton (employed).</strong> The Customer Delivery Driver advert at Manchester Gorton Extra pays from <strong>£13.28 an hour</strong>. Tesco offers new staff a minimum of <strong>16 guaranteed hours</strong> a week, or as few as 12 if you prefer. The advert lists availability windows (Sunday and Saturday 8am to 7pm, Thursday 8am to 4pm) and schedules your contracted hours inside them with three weeks' notice. Being available all Saturday does not mean you are paid for all Saturday. Official page: <a href="https://apply.tesco-careers.com/v2/job/search?location=manchester&amp;location_country=1" rel="noopener">Tesco Careers: Manchester search</a>.</p>

<p><strong>Evri parcel rounds (self-employed).</strong> To apply you need a full, valid driving licence, a car or van with a valid MOT and insurance, the right to work in the UK and no unspent criminal convictions. Any reasonably sized, reliable vehicle is accepted, and a hire vehicle is fine if your insurance covers courier work. Evri pays monthly by BACS with no income tax deducted, so if you need weekly money, plan for several fuel bills before the first payment. A licence from your home country lets you drive in the UK for at least 12 months; after that you must exchange it or pass the UK test. Official page: <a href="https://www.evri.com/careers/become-a-courier-faq" rel="noopener">Evri: Courier FAQs</a>.</p>

<p><strong>Ocado Logistics (employed).</strong> Its Manchester page listed a Delivery Driver post at Spoke Manchester. Ocado says the site is about 50 minutes from Manchester city centre by public transport and 45 minutes by car, so check that journey for an early start before you apply. Official page: <a href="https://www.ocado-logistics.com/city-locations/manchester" rel="noopener">Ocado Logistics: Manchester</a>.</p>

<h2>A Worked Example: Which Shift Pays Better?</h2>

<p>An employed shift at £13.28 for eight paid hours is <strong>£106.24</strong> before tax. Now imagine a self-employed round that brings in £144 for eight hours from loading to the last return. Take off £18 for fuel, £8 for insurance and maintenance and £14 for van hire. That leaves <strong>£104 before tax</strong>, or £13 an hour. If one extra hour goes on returning parcels, it falls to about £11.56 an hour. These costs are made up; use your own, and do not count both hire and depreciation for the same van. Compare a quiet week too, and remember the self-employed round has no holiday pay built in.</p>

<h2>Costs and Questions Before Accepting a Round</h2>

<p>Ask for a recent example from the same depot: collection time, parcel count, mileage and finish time. Ask whether you are paid per parcel, per stop or per route, and what a failed delivery pays. Thirty parcels for one building is not the same day as thirty separate addresses.</p>

<p>Tell your insurer you will deliver for payment. Evri offers a top-up insurance option, but check what your own policy covers, including damage to your vehicle. If you lease a van, get the weekly fee, mileage cap, accident excess and cancellation terms in writing.</p>

<p>Keep every receipt and mileage record. GOV.UK says you must register as a sole trader, through Self Assessment, if you earn more than £1,000 in a tax year. That threshold is about income before expenses, not profit after fuel.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/courier-jobs-manchester-self-employed-vs-employed-3.jpg"
         alt="Courier holding a parcel beside delivery vans in Manchester, with a map of the UK"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Compare Two Manchester Offers</h2>

<p>Write four figures beside each: paid hours, guaranteed income, unavoidable costs and total time away from home. If a recruiter cannot guarantee a volume, leave that box empty rather than filling it with their busiest day. Ask how much notice you get when a round is cancelled or moved. Then check both journeys at the real times, including Sundays: a round near Salford may still mean collecting from a depot across Greater Manchester.</p>

<h2>Frequently Asked Questions</h2>

<h3>Which option suits someone who needs predictable income?</h3>
<p>An employed role with written guaranteed hours. Tesco's Gorton advert, for example, guarantees at least 16 hours a week.</p>

<h3>Can I use my own car instead of a van?</h3>
<p>Evri accepts any reasonably sized, reliable car or van with a valid MOT and insurance that covers courier work. Check parcel sizes first.</p>

<h3>Do self-employed couriers pay their own tax?</h3>
<p>Yes. Evri pays without deducting income tax, and you must register for Self Assessment once you earn more than £1,000 in a tax year.</p>

<h3>Do both types of driver get paid holiday?</h3>
<p>Employees and workers are entitled to statutory paid holiday. Genuinely self-employed couriers usually have to fund their own time off.</p>

<h3>Can I drive for Evri on a foreign licence?</h3>
<p>Evri says a valid home-country licence lets you drive in the UK for at least 12 months; after that you need a UK licence.</p>

<h2>Bottom Line</h2>

<p>Pick the contract that matches your need for steady income, your vehicle and your schedule, and get the depot postcode, likely hours, pay basis and vehicle costs in writing before you accept.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://apply.tesco-careers.com/v2/job/search?location=manchester&amp;location_country=1" rel="noopener">Tesco Careers: Manchester</a>, Customer Delivery Driver, Manchester Gorton Extra</li>
<li><a href="https://www.evri.com/careers/become-a-courier-faq" rel="noopener">Evri: Courier FAQs</a></li>
<li><a href="https://www.ocado-logistics.com/city-locations/manchester" rel="noopener">Ocado Logistics: Manchester</a></li>
<li>GOV.UK: <a href="https://www.gov.uk/employment-status/worker" rel="noopener">Worker status</a> and <a href="https://www.gov.uk/become-sole-trader/register-sole-trader" rel="noopener">Register as a sole trader</a>, all checked 3 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/petrol-station-jobs-manchester-roles-and-how-to-apply">Petrol Station Jobs in Manchester</a>, another first job in the city.</li>
    <li><a href="/blog/delivery-driver-jobs-los-angeles-apps-requirements">Delivery Driver Jobs in Los Angeles</a>, app delivery in the USA.</li>
    <li><a href="/blog/food-delivery-rider-jobs-in-italy">Food Delivery Rider Jobs in Italy</a>, self-employed riders in Europe.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you pay for a van.</li>
</ul>

HTML;
    }
}
