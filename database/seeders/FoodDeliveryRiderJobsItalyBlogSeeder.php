<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Food Delivery Rider Jobs in Italy: Salary, Visa Requirements, and How to Apply"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. The Indeed buttons are dropped.
 * 2. Just Eat Italy and Deliveroo Italy show a security challenge to our browser and were not bypassed, so their contract,
 *    document and pay details in the brief (including Deliveroo's EUR 14) could not be confirmed and are left out. Both
 *    are linked, and the guide says plainly that the reader must read the terms there. Owner told on 31 October 2026.
 * 3. Glovo was read: self-employed couriers, registration steps, waiting list, EUR 3 gross minimum per completed offer
 *    from 9 February 2026, EUR 14 gross hourly minimum from 1 June 2026 on capped actual delivery time, 14-day billing.
 * 4. European Commission pages read: employer applies for the authorisation to work, annual quota, six months to collect
 *    the visa, residence permit within eight days, students 20 hours a week and 1,040 a year. The Commission calls the
 *    authorisation "authorisation to work" rather than nulla osta; the guide gives both names.
 * 5. The embassy page splits Pakistan by residence: Islamabad, Lahore, Multan and Faisalabad centres under Islamabad;
 *    Karachi and Quetta under the Karachi consulate. Uber confirms Uber Eats has left Italy.
 *
 * Posters are the owner's (blog-356, 31 Oct 9.58 to 10.00 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class FoodDeliveryRiderJobsItalyBlogSeeder extends Seeder
{
    public const SLUG = 'food-delivery-rider-jobs-in-italy';

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
                'title' => 'Food Delivery Rider Jobs in Italy: Salary, Visa Requirements, and How to Apply',
                'excerpt' => 'Why a rider app will not get you an Italian work visa, how Glovo\'s EUR 14 and EUR 3 minimums really work, the employer authorisation route for Pakistani applicants, and what to check before your first shift.',
                'content' => $content,
                'featured_image' => 'blogs/food-delivery-rider-jobs-in-italy.jpg',
                'tags' => 'food delivery rider jobs in italy, food delivery jobs in italy for foreigners, food delivery rider salary in italy, food delivery jobs in italy for pakistani workers, glovo rider italy, just eat rider italy, deliveroo rider italy, italy work visa nulla osta',
                'meta_title' => 'Food Delivery Rider Jobs in Italy: Pay, Visa, How to Apply',
                'meta_description' => 'Food delivery rider jobs in Italy: Glovo pay rules, bicycle and scooter requirements, the work visa route for Pakistani applicants and how to sign up.',
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
<p><strong>If you already have the right to work in Italy, you can check rider registration with Just Eat, Glovo or Deliveroo in your city. If you are in Pakistan and need a work visa, a delivery app will not get you one.</strong> For that you need an Italian employer who applies for authorisation for your job, inside the country's annual quota. Glovo describes its couriers as self-employed, which is a very different thing from being sponsored by an employer. We checked Glovo, the European Commission, the Italian Embassy in Islamabad and Uber on 31 October 2026. Just Eat and Deliveroo pages did not open for us, so check their current terms on their own sites.</p>

<h2>Choose a Platform Based on How You Want to Work</h2>

<p><strong>Glovo: independent courier.</strong> Glovo says its couriers are self-employed. You must be an adult, have a vehicle (bicycle, motorbike or car), a smartphone and the legal right to provide services in the country. You register with your phone number, email, city, vehicle and preferred hours, complete an information session of about 10 minutes, then upload your documents: tax code, residence permit, passport or identity card, and an IBAN. A driving licence and vehicle insurance apply where relevant.</p>

<p>Glovo uses a <strong>waiting list</strong> in some cities, so customers ordering in your area does not prove it is taking new riders. If you plan to rent a scooter, wait until you know your start date, because rent begins before your first delivery does. Official page: <a href="https://riderhub.glovoapp.com/it/blog/requisiti-per-lavorare-con-glovo/" rel="noopener">Glovo: Requirements</a>.</p>

<p><strong>Just Eat and Deliveroo.</strong> Both recruit riders in Italy through their own sites, and their arrangements are not the same as Glovo's. Before you apply, find out whether you would be an employee or independent, how hours are guaranteed, how pay is calculated and which documents they require. A contract with paid hours and a per-order fee can look similar in an advert and pay very differently. Official pages: <a href="https://www.justeat.it/rider" rel="noopener">Just Eat Italy: Riders</a> and <a href="https://riders.deliveroo.it/" rel="noopener">Deliveroo Italy: Riders</a>.</p>

<p><strong>Uber Eats is not an option.</strong> Uber says plainly that Uber Eats is no longer available in Italy, so ignore old sign-up pages.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/food-delivery-rider-jobs-in-italy-2.jpg"
         alt="Delivery rider in red gear on a scooter on an Italian street, with restaurant and doorstep delivery scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How Much Do Food Delivery Riders Earn in Italy?</h2>

<p>No reliable national average exists, because riders are paid in different ways. Glovo publishes two minimums:</p>

<ul>
<li><strong>€3 gross</strong> for each delivery offer you accept and complete, effective from 9 February 2026</li>
<li><strong>€14 gross an hour</strong>, all-inclusive, from 1 June 2026</li>
</ul>

<p>The €14 does not mean €14 for every hour you are logged in. It counts actual delivery time, subject to caps. If one stage of a delivery takes you 12 minutes and the cap for that stage is 10, only 10 count. Time spent waiting for an order is not what this minimum describes, so five hours online does not automatically earn €70. Glovo pays in billing periods of 14 days, so riders who need weekly money should plan for that.</p>

<p>Here is a made-up evening. You spend three hours out and earn €30 for completed orders, which is €10 an hour of your time. After €6 for fuel and parking, you have €24, or €8 an hour before tax and other costs. This is an illustration, not a salary estimate. Keep your own record of distance, waiting time and repairs, because a self-employed courier carries those costs.</p>

<p>If a restaurant offers an employment contract instead, ask for the gross hourly rate, weekly hours and payday, and keep tips out of your rent budget.</p>

<h2>Visa Requirements for Pakistani and Other Non-EU Applicants</h2>

<p>The standard route for an employed worker runs through the employer, not you:</p>

<ol>
<li>Your employer in Italy applies for an authorisation to work (often called a <em>nulla osta</em>) at the One-Stop-Shop for Immigration.</li>
<li>It is granted only if you fall within the annual quota for non-EU workers, though quotas do not apply to certain categories.</li>
<li>The consulate or embassy is told, and you have six months from the date of authorisation to collect the visa and enter Italy.</li>
<li>Within eight days of arriving, you apply for a residence permit.</li>
</ol>

<p>A rider app's registration email is not a work authorisation. None of the rider sign-up pages we could read offers overseas sponsorship.</p>

<p>In Pakistan, the visa centre depends on where you permanently live. Islamabad, Lahore, Multan and Faisalabad centres fall under the Embassy in Islamabad; Karachi and Quetta fall under the Consulate General in Karachi. Before paying for anything, ask the employer for its legal name, work location, draft contract and authorisation details. Anyone selling a "rider visa" should be treated with suspicion.</p>

<h2>Where to Look and How to Join</h2>

<p>Use Italian city names in the selectors: <em>Roma</em>, <em>Milano</em>, <em>Napoli</em>, <em>Firenze</em>, <em>Torino</em>, <em>Venezia</em>. Check the exact delivery zone and vehicle options before you choose where to live.</p>

<p>For restaurant jobs, search <em>fattorino pizzeria</em>; for groceries, <em>addetto alle consegne a domicilio</em>. Read who supplies the vehicle, where you start and whether you return after each delivery.</p>

<p>With no experience, list your vehicle, availability, work status and knowledge of the area. A few Italian phrases help: <em>Qual è il numero dell'ordine?</em> asks for the order number, and <em>Quale citofono devo suonare?</em> asks which intercom to ring. Do not assume onboarding and support are in English.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/food-delivery-rider-jobs-in-italy-3.jpg"
         alt="Rider in a red jacket holding a paper delivery bag and checking a phone, with a scooter and the Colosseum behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What to Check Before Your First Shift</h2>

<p>Glovo recommends a food-safe delivery backpack, a bicycle helmet and a reflective vest. The bag need not be Glovo's own, but it must meet food transport rules. On a bicycle, practise the ride from home to the restaurant area at your real start time, and include the trip home after the last order. On a scooter, tell your insurer you will deliver for payment and check what the policy covers.</p>

<p>Ask what happens when an order is late, an address is incomplete or a building has no way in. Save the official support contact and learn how to report a failed delivery before you accept your first order.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I deliver while studying in Italy?</h3>
<p>The European Commission gives a general limit of 20 hours a week and 1,040 hours over 52 weeks on a study permit. Check whether your permit covers independent courier work, and count any other job.</p>

<h3>Do delivery apps sponsor work visas from Pakistan?</h3>
<p>None of the sign-up pages we could read offers that. The route needs an employer's authorisation to work, within the annual quota.</p>

<h3>Is Uber Eats available in Italy?</h3>
<p>No. Uber says Uber Eats is no longer available in Italy.</p>

<h3>Does Glovo guarantee €14 for every hour online?</h3>
<p>No. The minimum applies to capped actual delivery time, not to all the time you are logged in.</p>

<h3>Can I get free visa sponsorship for a family driver job?</h3>
<p>That is a different job with a different employer. Ask for the actual offer and the immigration route before you treat any sponsorship claim as real.</p>

<h2>Bottom Line</h2>

<p>Start with your right to work, then compare the arrangements in a city where you can actually ride. Judge an offer on paid time, vehicle costs and available hours, and from Pakistan, verify the employer's authorisation before spending money on moving.</p>

<h2>Sources</h2>

<ol>
<li>Glovo Italy: <a href="https://riderhub.glovoapp.com/it/blog/requisiti-per-lavorare-con-glovo/" rel="noopener">Requirements</a>, <a href="https://riderhub.glovoapp.com/it/wiki/getting-started/" rel="noopener">Getting started</a> and <a href="https://riderhub.glovoapp.com/it/blog/incremento-del-compenso-minimo-per-proposta-di-consegna-e-del-compenso-orario-minimo/" rel="noopener">Minimum fee notice</a></li>
<li>European Commission: <a href="https://home-affairs.ec.europa.eu/policies/migration-and-asylum/eu-immigration-portal/employed-worker-italy_en" rel="noopener">Employed worker in Italy</a> and <a href="https://home-affairs.ec.europa.eu/policies/migration-and-asylum/eu-immigration-portal/student-italy_en" rel="noopener">Student in Italy</a></li>
<li><a href="https://ambislamabad.esteri.it/en/servizi-consolari-e-visti/servizi-per-il-cittadino-straniero/visti/dove-chiedere-un-visto/" rel="noopener">Embassy of Italy in Islamabad: Where to apply for a visa</a></li>
<li><a href="https://help.uber.com/driving-and-delivering/article/assistenza-di-uber%C2%A0eats?nodeId=ade33009-70d3-49f8-bfe4-19d25b5d78b1" rel="noopener">Uber: Italy delivery service status</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">Hotel and Restaurant Jobs in the UAE and Italy</a>, employer jobs in the same country.</li>
    <li><a href="/blog/delivery-driver-jobs-los-angeles-apps-requirements">Delivery Driver Jobs in Los Angeles</a>, how app delivery works in the USA.</li>
    <li><a href="/blog/gulf-vs-europe-for-work">Gulf vs Europe for Work</a>, comparing where to go.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
