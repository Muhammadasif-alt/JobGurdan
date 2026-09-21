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
 * "How to Get a Logistics Driver Job in the UAE" — a guide for someone asking
 * what licence, visa and paperwork a UAE driving job needs. The Security Guard
 * Jobs in UAE guide owns the wider UAE labour-law package questions, so this
 * one stays on licensing, the exchange list, the visa sequence and the rules
 * specific to truck and delivery drivers.
 *
 * Corrections and clarifications to the draft (checked against the Dubai
 * Roads and Transport Authority, u.ae, GDRFA Dubai, the UAE Labour Law and
 * Abu Dhabi Police, September 2026):
 *
 * 1. The draft uses "Category 3 (LMV)" and "Category 4/6" licences and a
 *    "public transport endorsement". The RTA names licences by vehicle type
 *    (light vehicle, motorcycle, heavy vehicle, light bus, heavy bus, light
 *    and heavy equipment); the numbered categories are not official terms.
 *    Minimum ages are 18 for a light vehicle, 20 for a heavy vehicle or
 *    equipment and 21 for buses.
 *
 * 2. The draft says licences from 57 countries, including India, Pakistan and
 *    the Philippines, can be exchanged with no tests. India, Pakistan and the
 *    Philippines are not on the RTA exchange list. The list has grown past the
 *    57 countries announced in January 2026, many entries are for nationals
 *    only and light vehicles only, and the holder needs UAE residence.
 *
 * 3. The draft puts the exchange at AED 950-1,500 and 1-2 hours. The RTA lists
 *    AED 1,050 in total (AED 1,010 with a cheaper eye test), and the service
 *    time as instant.
 *
 * 4. The draft gives new residents 30 days to convert. The RTA gives no grace
 *    period: residents must exchange before driving, and only visitors may
 *    drive on a GCC, exchange-country or international licence.
 *
 * 5. The draft says a visit visa "gets converted" once you are hired. Working
 *    on a visit visa is prohibited; the employer obtains a work permit, a new
 *    visa is issued and your status is changed inside the country.
 *
 * 6. The draft quotes a minimum driver salary of AED 1,800 from job sites.
 *    The UAE Labour Law sets no minimum salary for expatriate workers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LogisticsDriverJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ae.indeed.com/q-logistics-driver-jobs.html';

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
        $title = 'How to Get a Logistics Driver Job in the UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'UAE driving jobs need a UAE licence for the right vehicle type. Pakistani, Indian and Filipino licences cannot be exchanged in Dubai, working on a visit visa is illegal, and the employer must pay your recruitment and visa costs.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-logistics-driver-job-in-the-uae.jpg',
                'tags' => 'logistics driver jobs uae, driver jobs in dubai, uae driving licence exchange, rta licence categories, heavy truck driver uae, delivery rider dubai, driver visa uae, dubai driving licence for pakistani, truck ban dubai',
                'meta_title' => 'How to Get a Logistics Driver Job in the UAE (2026 Guide)',
                'meta_description' => 'How to get a logistics driver job in the UAE in 2026: RTA licence types and ages, whose licences can be exchanged, the visa steps and who pays the costs.',
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
            ['name' => 'UAE Logistics, Courier & Transport Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ae-logistics-driver-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Logistics Driver — Light Vehicle, Heavy Truck and Delivery Roles, UAE Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, often including early starts outside truck ban hours',
                'language' => 'English',
                // The UAE sets no minimum wage for expatriate workers and
                // packages vary employer by employer, so no band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Light vehicle, heavy truck and delivery driver roles with UAE logistics and courier companies. A UAE driving licence for the vehicle type is required.',
                'seo_keywords' => 'logistics driver jobs uae, driver jobs dubai, heavy truck driver uae, delivery driver uae, light vehicle driver jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Logistics, courier, freight and distribution companies across the UAE hire drivers to move goods between warehouses, ports, shops and customers, in vans, trucks and on delivery motorcycles.</p>

<h3>What the work involves</h3>
<p>Loading and checking consignments, planning routes around traffic and truck restrictions, making deliveries on schedule, completing delivery paperwork or app records, and basic vehicle checks.</p>

<h3>Requirements</h3>
<ul>
    <li>A UAE driving licence for the vehicle type: light vehicle, heavy vehicle, bus or motorcycle</li>
    <li>Minimum ages set by the RTA: 18 for a light vehicle, 20 for a heavy vehicle and 21 for a bus</li>
    <li>A valid UAE residence and work permit sponsored by the employer</li>
    <li>For Dubai delivery riders: a Dubai Police good conduct certificate and RTA rider training</li>
</ul>

<h3>How the package works</h3>
<ul>
    <li><strong>No statutory minimum wage.</strong> UAE Labour Law sets no minimum salary for expatriate workers, so compare the basic salary, allowances and accommodation line by line</li>
    <li><strong>Costs the employer pays.</strong> Recruitment, visa and residency costs must be borne by the employer, not the worker</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, licence requirements and visa sponsorship are set by each employer, the RTA and UAE law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying, and never pay a fee for a job or a visa.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To get a logistics driver job in the UAE you need a UAE driving licence for the vehicle you will drive, and an employer who sponsors your work permit and residence.</strong> The licence is the part most applicants get wrong. Holders of licences from the UK, the US and some other countries can exchange them in Dubai without driving tests, but <strong>licences from Pakistan, India and the Philippines cannot be exchanged</strong>: those drivers have to train and pass the RTA tests. And the UAE sets <strong>no minimum wage</strong> for expatriate workers, so the "minimum salary" figures quoted online come from job ads, not the law.</p>

<p>This guide covers the licence types and ages, the exchange list, what it costs, the visa steps, who pays for what, and the extra rules for truck drivers and delivery riders. The licensing rules below are Dubai's, set by the Roads and Transport Authority (RTA); Abu Dhabi and the other emirates run their own licensing, so check with the authority where you will be based.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ae.indeed.com/q-logistics-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127466; Browse Logistics Driver Jobs in the UAE &rarr;
    </a>
</div>

<h2>The Licence Types, and How Old You Must Be</h2>

<p>Many guides describe UAE licences as "Category 3" for light vehicles and "Category 4 or 6" for heavy vehicles. <strong>The RTA does not use those numbers.</strong> It names each licence by the vehicle it covers, and sets a minimum age for each:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">RTA licence</th>
            <th style="padding:10px;text-align:left;">What it covers</th>
            <th style="padding:10px;text-align:left;">Minimum age</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Light vehicle</strong></td><td style="padding:10px;">Cars, vans and pickups: most courier and light logistics work</td><td style="padding:10px;">18</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Motorcycle</strong></td><td style="padding:10px;">Delivery bikes</td><td style="padding:10px;">See delivery rider rules below</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Heavy vehicle</strong></td><td style="padding:10px;">All types of light and heavy vehicles: trucks and container transport</td><td style="padding:10px;">20</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Light bus</strong></td><td style="padding:10px;">Light vehicles and buses of up to 26 passengers</td><td style="padding:10px;">21</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Heavy bus</strong></td><td style="padding:10px;">Larger buses</td><td style="padding:10px;">21</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Light or heavy equipment</strong></td><td style="padding:10px;">Forklifts, tractors and similar machines</td><td style="padding:10px;">20</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Roads and Transport Authority, Dubai, driving licence service page, September 2026.</p>
</div>

<p>Bus and equipment licences also require <strong>6/6 vision in both eyes</strong>. School bus, taxi and tourist vehicle drivers need a separate RTA professional permit on top of the licence; the RTA does not list an equivalent permit for truck drivers.</p>

<h2>Can You Exchange Your Licence? Check the List First</h2>

<p>This is where most drafts go wrong. The RTA lets <strong>residents</strong> exchange a valid licence from countries on its exchange list, with an eye test but <strong>no driving lessons and no road test</strong>. The list is not the 57 countries often quoted: that was the figure in the RTA's January 2026 announcement, and the official page now covers more.</p>

<ul>
    <li><strong>Not on the list: Pakistan, India and the Philippines.</strong> Holders of those licences must go through RTA training and pass the tests. The RTA reduces the training to 20 sessions where the foreign licence was issued more than five years ago.</li>
    <li><strong>United Kingdom:</strong> open to all nationalities, for all licence categories.</li>
    <li><strong>United States:</strong> open to all nationalities and categories, except licences from Texas, which only Texas nationals can exchange, for light vehicles only.</li>
    <li><strong>Many other countries</strong> are listed for their own nationals only, and for light vehicles only or light vehicles and motorcycles. A heavy truck licence can only be exchanged if your country is one that can exchange all categories.</li>
    <li><strong>GCC nationals</strong> can exchange a valid GCC licence, surrendering the original.</li>
</ul>

<p>You also need a <strong>valid Emirates ID</strong>, which means residence comes before the exchange.</p>

<h3>What the exchange costs</h3>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">RTA fee</th>
            <th style="padding:10px;text-align:left;">AED</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Traffic file</td><td style="padding:10px;">200</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Driving licence</td><td style="padding:10px;">600</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Handbook manual</td><td style="padding:10px;">50</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Electronic eye test</td><td style="padding:10px;">140 to 180</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Knowledge and Innovation</td><td style="padding:10px;">20</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Total</strong></td><td style="padding:10px;"><strong>1,010 to 1,050</strong></td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">RTA licence exchange service page, September 2026. Delivery of the card costs extra.</p>
</div>

<p>The RTA lists the service time as <strong>instant</strong> once your documents are in order, not the "one to two hours" some guides give.</p>

<h3>There is no 30-day grace period</h3>

<p>Some guides say new residents have 30 days to convert a foreign licence. The RTA gives no such window. Once you live and work in the UAE, you <strong>must exchange or obtain a UAE licence before you drive</strong>. Only people on a <strong>visit visa</strong> can drive on a GCC licence, a licence from an exchange-list country or an international driving permit, and then only light vehicles and motorcycles.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-a-logistics-driver-job-in-the-uae-loading-yard.jpg"
         alt="A logistics driver in a high-visibility vest holding a clipboard beside a white articulated truck at a loading bay, with shipping containers, a forklift, the UAE flag and the Dubai skyline behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Visa: Why a Visit Visa Is Not a Shortcut</h2>

<p>Many guides suggest flying in on a visit visa, finding a driving job and having the visa "converted". The official position is blunter: <strong>UAE law strictly prohibits working while holding a visit or tourist visa</strong>, whether paid or unpaid. And a visit visa is not converted. The sequence is:</p>

<ol>
    <li><strong>A job offer</strong> from a UAE employer.</li>
    <li><strong>A work permit</strong> approved for you by the Ministry of Human Resources and Emiratisation (MOHRE).</li>
    <li><strong>A new entry permit for work</strong>. If you are already in the country, your status is then changed inside the UAE, which carries its own fee.</li>
    <li><strong>Medical test, Emirates ID and residence visa</strong>, after which you can exchange or apply for your licence.</li>
</ol>

<p>The UAE's <strong>job seeker visit visa</strong> does not help most drivers either. It is limited to applicants in MOHRE's first, second or third skill levels and asks for a bachelor's degree, so check whether you qualify before relying on it.</p>

<h2>Who Pays for What</h2>

<p>This is the rule to hold any recruiter to. Under the UAE Labour Law (Federal Decree-Law No. 33 of 2021), <strong>the employer is prohibited from charging the worker for recruitment and employment fees and costs</strong>, directly or indirectly. The official UAE government portal says the costs of recruitment, travel and your residency permit are borne by the employer, and that <strong>confiscating workers' passports is prohibited</strong>.</p>

<p>So if an agent asks you for money to "process" a driving job or a visa, that is a warning sign, not a normal step.</p>

<h2>What Drivers Earn: Why There Is No Official Minimum</h2>

<p>Guides often quote a "minimum" driver salary of AED 1,800 a month. That figure comes from job ads. <strong>The UAE Labour Law sets no minimum salary</strong> for expatriate workers; it only requires wages to be sufficient to meet employees' basic needs. The minimum salary rules that do exist apply elsewhere:</p>

<ul>
    <li><strong>Emiratis in the private sector:</strong> AED 6,000 a month from 1 January 2026.</li>
    <li><strong>Sponsoring your family:</strong> a salary of at least AED 4,000, or AED 3,000 plus accommodation.</li>
</ul>

<p>When you compare offers, look at the <strong>basic salary</strong> separately from allowances, whether <strong>accommodation and transport</strong> are provided, and what the working hours and overtime look like.</p>

<h2>Extra Rules for Truck Drivers</h2>

<p>Heavy vehicle drivers work around <strong>truck movement bans</strong>, which shape shift patterns:</p>

<ul>
    <li><strong>Dubai, Sheikh Zayed Road:</strong> a 16-hour ban on trucks from 6:00 AM to 10:00 PM.</li>
    <li><strong>Dubai peak-hour restrictions</strong> in set areas: 6:30 AM to 8:30 AM, 1:00 PM to 3:00 PM and 5:30 PM to 8:00 PM. The bans apply to trucks with a tare weight over 2.5 tonnes.</li>
    <li><strong>Abu Dhabi</strong> Police have announced their own peak-hour bans, 6:30 AM to 9:00 AM and 3:00 PM to 6:00 PM in Abu Dhabi city.</li>
</ul>

<p>Ban hours and roads change, so check the current rules with the RTA or the relevant police force before relying on them.</p>

<h2>Extra Rules for Delivery Riders in Dubai</h2>

<p>Much of the UAE's parcel and food delivery work is done on motorcycles, and Dubai regulates it closely. The RTA's delivery services manual requires a rider to have:</p>

<ul>
    <li>A <strong>UAE driving licence</strong>.</li>
    <li>A <strong>certificate of good conduct from Dubai Police</strong>.</li>
    <li>A <strong>valid residence, sponsored as a driver</strong>.</li>
    <li>An age of <strong>at least 21 and no more than 55</strong>.</li>
    <li><strong>Professional training and a permit</strong>, through an RTA-accredited institute.</li>
</ul>

<p>Since <strong>1 November 2025</strong>, delivery bikes may not use the two leftmost lanes on roads with five or more lanes, or the leftmost lane on roads with three or four. Fines start at AED 500, rise to AED 700, and a third violation suspends the permit.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-get-a-logistics-driver-job-in-the-uae-dispatch.jpg"
         alt="A logistics driver in a cap and high-visibility vest checking a delivery tablet beside a white truck at a port, with pallets, a forklift, cranes and the Burj Khalifa at sunrise behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get Hired, Step by Step</h2>

<ol>
    <li><strong>Match the job to the licence.</strong> Van and courier work needs a light vehicle licence, trucks need a heavy vehicle licence, and delivery bikes need a motorcycle licence plus Dubai's rider requirements.</li>
    <li><strong>Check whether your licence can be exchanged.</strong> Look up your country on the RTA list. If it is not there, as with Pakistan, India and the Philippines, budget for training and tests after you arrive.</li>
    <li><strong>Apply for jobs from where you are.</strong> Large courier and logistics companies post openings on their own career sites as well as job boards. Look for employers that state visa sponsorship.</li>
    <li><strong>Get the offer in writing</strong>, with the basic salary, allowances, accommodation and working hours spelled out.</li>
    <li><strong>Let the employer run the visa process</strong>: work permit, entry permit, medical, Emirates ID and residence. Do not start work before it is done.</li>
    <li><strong>Exchange or obtain your UAE licence</strong> before you drive for work.</li>
    <li><strong>Refuse any fee.</strong> Recruitment, visa and residency costs are the employer's to pay.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can I exchange my Pakistani or Indian driving licence in Dubai?</h3>
<p>No. Pakistan, India and the Philippines are not on the RTA's licence exchange list. Holders of those licences have to complete RTA training and pass the driving tests. Training is reduced to 20 sessions if the foreign licence was issued more than five years ago.</p>

<h3>Which countries' licences can be exchanged without a test in Dubai?</h3>
<p>Those on the RTA exchange list, which includes the UK and the US (except Texas) for all nationalities and categories. Many other listed countries are for their own nationals only and for light vehicles only. You need UAE residence and an Emirates ID to exchange.</p>

<h3>How much does a UAE licence exchange cost?</h3>
<p>The RTA lists AED 1,050 in total: AED 200 for the traffic file, AED 600 for the licence, AED 50 for the handbook, up to AED 180 for the eye test and AED 20 in knowledge fees. With a cheaper eye test it is AED 1,010.</p>

<h3>How old do you have to be to drive a truck in the UAE?</h3>
<p>In Dubai, 20 for a heavy vehicle licence and 21 for a light or heavy bus licence. A light vehicle licence is issued from 18. Bus and equipment licences also need 6/6 vision in both eyes.</p>

<h3>Can I work as a driver on a visit visa while I wait for my employment visa?</h3>
<p>No. UAE law prohibits working on a visit or tourist visa, paid or unpaid. Your employer must first obtain a work permit; a new entry permit is then issued and your status is changed inside the country.</p>

<h3>Is there a 30-day grace period to convert my licence?</h3>
<p>Not according to the RTA. Residents must exchange or obtain a UAE licence before driving. Only visitors can drive on a GCC, exchange-country or international licence, and only light vehicles and motorcycles.</p>

<h3>What is the minimum salary for a driver in the UAE?</h3>
<p>There is none for expatriate workers: the UAE Labour Law sets no minimum salary. The AED 6,000 minimum applies to Emiratis in the private sector, and sponsoring your family needs a salary of AED 4,000, or AED 3,000 with accommodation.</p>

<h3>Who pays for my visa and recruitment as a driver?</h3>
<p>The employer. The UAE Labour Law prohibits charging workers recruitment and employment fees and costs, and the UAE government portal says recruitment, travel and residency permit costs are borne by the employer.</p>

<h2>People Also Search For</h2>

<h3>Driver jobs in Dubai with visa</h3>
<p>The employer obtains your work permit and residence; you should not pay for either.</p>

<h3>Dubai driving licence exchange list</h3>
<p>The RTA list includes the UK and the US but not Pakistan, India or the Philippines.</p>

<h3>Heavy truck driver jobs UAE</h3>
<p>A heavy vehicle licence from age 20, with truck ban hours on Dubai and Abu Dhabi roads.</p>

<h3>RTA licence exchange fees</h3>
<p>AED 1,010 to 1,050 in total, depending on the eye test.</p>

<h3>Delivery rider jobs Dubai requirements</h3>
<p>A UAE licence, Dubai Police good conduct certificate, driver residence, age 21 to 55 and RTA training.</p>

<h3>Can I drive in UAE with an international driving permit</h3>
<p>Only on a visit visa, and only light vehicles and motorcycles.</p>

<h3>Minimum salary for drivers in UAE</h3>
<p>There is no statutory minimum wage for expatriate workers in the UAE.</p>

<h3>Truck ban timings Dubai</h3>
<p>Sheikh Zayed Road bans trucks from 6:00 AM to 10:00 PM, with set peak-hour bans elsewhere.</p>

<h2>More Job Guides</h2>

<p>Comparing driving routes and other Gulf jobs? These cover them:</p>

<ul>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the neighbouring Gulf route, and how Saudi licensing and sponsorship work.</li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a> &mdash; heavy freight work under the Saudi rules.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; another entry route into UAE work, and the labour-law package questions to ask.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; British delivery work and what self-employed really costs.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; which American visa routes are open to truck drivers.</li>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the height and reach rules, what Emirates publishes on pay, and how the Open Day works.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; why Abu Dhabi ground jobs are advertised by a company called Velora.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Licence rules, fees, exchange lists and visa procedures change, and each emirate licenses its own drivers. Confirm the current position with the RTA or your emirate's licensing authority, MOHRE and u.ae before relying on it.</p>
HTML;
    }
}
