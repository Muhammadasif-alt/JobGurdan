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
 * "Heavy Truck Driver Jobs in Saudi Arabia" — a sector guide rather than one
 * vacancy, so the apply link goes to an Indeed search and the post carries no
 * JobPosting markup. It is scoped to the heavy-vehicle specialism (licence
 * class, conversion, long-haul and tanker work); the general driver guide owns
 * the visa mechanics and the company-versus-house-driver split, which this page
 * cross-links to rather than repeating.
 *
 * Corrections and additions (checked 16 September 2026 against the Saudi General
 * Department of Traffic / Absher guidance, MHRSD / hrsd.gov.sa and reciprocal
 * licence lists):
 *
 * 1. Licence conversion - the biggest fix. The draft says a driver can "convert
 *    an international licence locally after arrival". That is true only for an
 *    approved reciprocal list (GCC states, USA, UK, EU, Canada, Australia, New
 *    Zealand, Japan, South Korea, Singapore). Drivers from India, Pakistan,
 *    Bangladesh and the Philippines - the actual audience - cannot swap; they
 *    must complete the full Saudi process (theory lessons, computer theory test,
 *    practical lessons and a road test). A foreign or international licence is
 *    valid only for temporary use, up to about one year.
 *
 * 2. A heavy truck needs the public / heavy-transport licence class, not the
 *    private-car licence, and its minimum age is higher than the private
 *    licence's 18 (commonly cited as 20 to 22). Confirm the exact figure with
 *    Absher / Muroor.
 *
 * 3. No statutory minimum wage for expatriate private-sector workers. Pay is set
 *    by the contract alone. The widely quoted SAR 4,000 is a Nitaqat/Saudization
 *    counting threshold for Saudi nationals, not a legal floor for expats.
 *    Whatever the contract sets must be paid in full through the Wage Protection
 *    System (WPS / Mudad).
 *
 * 4. Saudization omission. App-based ride-hailing (Uber, Careem) has been 100%
 *    reserved for Saudi nationals since January 2021, so that is not an expat
 *    route. Heavy and long-haul truck driving remains open to expatriates as of
 *    September 2026, but MHRSD's 2026-2028 localization drive is a forward risk.
 *
 * 5. The Labour Reform Initiative took effect 14 March 2021: job mobility,
 *    exit/re-entry and final-exit requests can be made through Absher and Qiwa
 *    without employer consent under stated conditions.
 *
 * 6. Recruitment channel. Musaned is for domestic workers only, not truck
 *    drivers. Drivers should use licensed recruitment agencies plus their own
 *    country's official emigration system (Pakistan's BEOE / Protector of
 *    Emigrants, India's eMigrate) and never pay an illegal recruitment fee.
 *
 * 7. The apply link is cleaned to the Saudi site's own heavy-truck search page.
 *
 * Both records use updateOrCreate, so re-running is safe; it overwrites
 * admin-panel edits to these two rows.
 */
class HeavyTruckDriverJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/q-heavy-truck-driver-jobs.html';

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
        $title = 'Heavy Truck Driver Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'There is no minimum wage for expat workers, and drivers from India, Pakistan or Bangladesh cannot just swap their licence - they must pass the full Saudi test. App-taxi work is reserved for Saudis; heavy trucking stays open, paid through the WPS.',
                'content' => $content,
                'featured_image' => 'blogs/heavy-truck-driver-jobs-in-saudi-arabia.jpg',
                'tags' => 'heavy truck driver jobs in saudi arabia, truck driver jobs saudi arabia, hgv jobs saudi arabia, saudi driving licence conversion, truck driver salary saudi arabia, saudi work visa driver, long haul driver jobs ksa, driver jobs for pakistani',
                'meta_title' => 'Heavy Truck Driver Jobs in Saudi Arabia 2026 Guide',
                'meta_description' => 'Heavy truck driver jobs in Saudi Arabia: the heavy licence and who must retest, the no-minimum-wage reality, the Saudization rules, and how to apply safely.',
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
            ['name' => 'Saudi Logistics & Transport Employers (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'saudi-heavy-truck-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Riyadh, Jeddah and Dammam', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Heavy Truck Driver — Long-Haul, Tanker and Site Transport, Saudi Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, including long-haul intercity routes and shift work',
                'language' => 'English, Arabic helpful',
                // No statutory minimum wage applies to expatriate private-sector
                // workers; pay is contract-set and must be paid through WPS, so
                // no single range is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Heavy truck driver roles with Saudi logistics, construction and oil-and-gas employers: long-haul, tanker and site transport. Apply through the employer listing.',
                'seo_keywords' => 'heavy truck driver jobs in saudi arabia, truck driver jobs saudi arabia, hgv jobs saudi arabia, long haul driver jobs ksa, driver jobs for pakistani',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Logistics companies, construction contractors and oil-and-gas operators across Saudi Arabia hire heavy truck drivers to move goods, materials and equipment on long-haul routes, tanker runs and site transport, much of it tied to Vision 2030 projects.</p>

<h3>What the work involves</h3>
<p>Operating heavy trucks, trailers and specialised vehicles (tankers, flatbeds, dump trucks); loading and securing cargo; pre-trip and post-trip inspections; following Saudi road-safety rules; and keeping delivery and vehicle logs.</p>

<h3>Requirements</h3>
<ul>
    <li>A Saudi <strong>public / heavy-transport driving licence</strong> &mdash; a foreign licence is only valid temporarily (about a year), and drivers from India, Pakistan, Bangladesh and the Philippines must pass the full Saudi test, not simply convert</li>
    <li>Several years of verified heavy-vehicle experience, a medical fitness certificate and a clean driving record</li>
    <li>An employer-sponsored Iqama (residency permit)</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>No statutory minimum wage</strong> applies to expatriate private-sector workers; pay is set by the contract</li>
    <li><strong>Whatever the contract states must be paid through the Wage Protection System</strong> (WPS), on time and in full</li>
    <li>Many contracts add accommodation, transport and an annual flight, which raise the real value</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Confirm the licence path for your nationality, the salary in writing, and that pay runs through WPS</strong> &mdash; and use only a licensed recruiter.</p>

<p><strong>Note:</strong> pay, benefits and requirements are set by each employer &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Saudi Arabia's construction, logistics and oil-and-gas sectors keep demand for heavy truck drivers steady, and for experienced drivers willing to relocate the contracts can be worth it once accommodation and flights are counted. But two things in the usual version of this advice are wrong in ways that cost drivers money and time: the idea that you can just swap your home licence when you land, and the idea that a quoted salary is a legal floor. This guide fixes both and sets out how the job really works.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-heavy-truck-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#0b6b3a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128667; Browse Heavy Truck Driver Jobs &rarr;
    </a>
</div>

<h2>What a Heavy Truck Driver Does in Saudi Arabia</h2>

<ul>
    <li>Operating heavy trucks, trailers and specialised vehicles &mdash; tankers, flatbeds, dump trucks</li>
    <li>Loading and unloading cargo safely, often with a ground crew</li>
    <li>Pre-trip and post-trip vehicle inspections</li>
    <li>Following designated routes and Saudi road-safety regulations</li>
    <li>Keeping delivery logs, trip records and maintenance reports</li>
    <li>Securing cargo to prevent damage or hazards</li>
    <li>Coordinating with dispatchers and site supervisors</li>
</ul>

<p>Depending on the employer, you may run long-haul intercity routes, cross-border GCC trips, or short repeated runs inside a single industrial site or project.</p>

<h2>The Licence: The One Thing Most Guides Get Wrong</h2>

<p>This is the single most important fact for an overseas driver, and the point where the standard advice fails. A heavy truck needs the Saudi <strong>public / heavy-transport licence</strong> &mdash; not the private-car licence &mdash; and its minimum age is higher than the private licence's 18 (commonly cited as 20 to 22; confirm with Absher).</p>

<p>Whether you can convert your existing licence <strong>depends entirely on your nationality</strong>:</p>

<ul>
    <li><strong>Reciprocal-list countries convert without a test.</strong> The GCC states, the USA, the UK, EU members, Canada, Australia, New Zealand, Japan, South Korea and Singapore are on the approved list.</li>
    <li><strong>Most South Asian and source nationalities are not.</strong> Drivers from <strong>India, Pakistan, Bangladesh and the Philippines cannot simply convert</strong>. They must complete the full Saudi process: theory lessons, a computer theory test, practical lessons and a road test.</li>
    <li><strong>A foreign or international licence is temporary.</strong> It can be used for roughly one year after arrival &mdash; it is not a Saudi licence, and it does not remove the need to obtain one.</li>
</ul>

<p>So the common line "you can convert your international licence locally after arrival" is misleading for exactly the drivers most likely to read it. Budget time and money for the Saudi driving test unless you hold a licence from a reciprocal-list country.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/heavy-truck-driver-jobs-in-saudi-arabia-route.jpg"
         alt="A heavy truck driver in a hi-vis vest with a clipboard beside a truck on a Saudi highway, next to a Heavy Truck Driver Jobs in Saudi Arabia banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Pay: There Is No Minimum Wage for Expats</h2>

<p>The second correction. Saudi Arabia has <strong>no statutory minimum wage for expatriate private-sector workers</strong>. Your pay is whatever your contract says &mdash; nothing more, nothing less. The SAR 4,000 figure that circulates is a <strong>Nitaqat/Saudization counting threshold for Saudi nationals</strong>, not a legal floor for foreign workers.</p>

<p>What protects you is not a minimum wage but the <strong>Wage Protection System (WPS)</strong>: whatever the contract sets must be paid through WPS, on time and in full, and the ministry can act against employers who underpay or delay.</p>

<p>For scale only, third-party aggregator estimates (<strong>not official</strong>, and highly employer-dependent):</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#0b6b3a;color:#fff;">
            <th style="padding:10px;text-align:left;">Route type</th>
            <th style="padding:10px;text-align:left;">Estimated monthly pay (aggregator, not official)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Local / city delivery</td><td style="padding:10px;">SAR 1,500 &ndash; SAR 2,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Regional / medium-haul</td><td style="padding:10px;">SAR 2,500 &ndash; SAR 4,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Long-haul</td><td style="padding:10px;">SAR 4,500 &ndash; SAR 7,000+</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Cross-border GCC</td><td style="padding:10px;">adds roughly SAR 500 &ndash; SAR 1,200</td></tr>
    </tbody>
</table>
</div>

<p>Treat these as a rough guide, not a promise. The contract, not the internet, is what will be paid &mdash; so get it in writing and confirm accommodation, flights and the pay schedule before you accept.</p>

<h2>Saudization: What Is Open and What Is Not</h2>

<p>Not every driving job is open to foreigners, and the difference matters:</p>

<ul>
    <li><strong>App-based ride-hailing (Uber, Careem) is closed.</strong> Those "captain" roles have been 100% reserved for Saudi nationals since January 2021 &mdash; not an expat route.</li>
    <li><strong>Heavy and long-haul truck driving remains open</strong> to expatriates as of September 2026.</li>
    <li><strong>Watch the trend.</strong> MHRSD announced a 2026-2028 drive to localise hundreds of thousands of private-sector jobs, and transport is regulated by the Transport General Authority, so the rules can tighten. Check the current position before you commit.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/heavy-truck-driver-jobs-in-saudi-arabia-highway.jpg"
         alt="A heavy truck driver beside a container truck on a desert highway with the Riyadh skyline behind, next to a Heavy Truck Driver Jobs in Saudi Arabia banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Visa, Iqama and Your Rights</h2>

<p>A foreign driver works on an employer-sponsored <strong>Iqama (residency permit)</strong>. Since the <strong>Labour Reform Initiative of 14 March 2021</strong>, private-sector expat workers have more control than the old system allowed: job mobility to a new employer on contract expiry, and exit/re-entry and final-exit requests, can be made through <strong>Absher and Qiwa</strong> without employer consent under stated conditions. The reforms did not abolish sponsorship entirely, but they loosened it.</p>

<p>For how sponsorship is actually processed, and the important difference between a company driver and a private house driver, our <a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">driver jobs in Saudi Arabia guide</a> covers the visa mechanics in depth.</p>

<h2>How to Apply Safely</h2>

<ol>
    <li><strong>Use a licensed recruiter.</strong> Musaned is for domestic workers only, not truck drivers. Drivers go through licensed recruitment agencies plus their own country's official emigration system &mdash; Pakistan's Bureau of Emigration &amp; Overseas Employment (BEOE) / Protector of Emigrants, or India's eMigrate.</li>
    <li><strong>Never pay an illegal fee.</strong> Charging workers for a job is against the rules; verify the agency's licence and walk away from anyone demanding cash for a placement.</li>
    <li><strong>Get the contract in writing</strong> before you travel, with salary, hours, accommodation and flights stated.</li>
    <li><strong>Confirm the licence path</strong> for your nationality so you are not stranded unable to drive.</li>
</ol>

<h2>Skills Employers Look For</h2>

<ul>
    <li>Strong defensive driving and awareness of desert and long-haul conditions</li>
    <li>Physical stamina for long shifts and extended routes</li>
    <li>Basic mechanical knowledge to spot and report faults early</li>
    <li>Reliability and punctuality under tight project schedules</li>
    <li>A safety mindset around cargo securing and site hazards</li>
    <li>Ability to work in multinational teams</li>
</ul>

<h2>Where to Find Heavy Truck Driver Jobs</h2>

<ol>
    <li><strong>Online job boards.</strong> List heavy-truck vacancies across logistics, construction and oil-and-gas employers.</li>
    <li><strong>Licensed international recruitment agencies.</strong> Many place drivers from South Asia and the Middle East.</li>
    <li><strong>Contractor and logistics company sites.</strong> Vision 2030 contractors often post driver roles directly.</li>
    <li><strong>Government-approved emigration portals.</strong> Your country's official system for vetted, legal jobs.</li>
    <li><strong>Driver networks.</strong> Referrals from drivers already in the Kingdom, checked against the points above.</li>
</ol>

<h2>Tips for Landing a Heavy Truck Driver Job</h2>

<ul>
    <li><strong>Verify the agency's licence</strong> before anything else.</li>
    <li><strong>List your vehicle experience precisely</strong> &mdash; tankers, flatbeds, trailers.</li>
    <li><strong>Prepare documents early</strong> &mdash; licence, medical certificate, experience records.</li>
    <li><strong>Lead with your safety record;</strong> a clean history is heavily weighted.</li>
    <li><strong>Settle contract terms first</strong> &mdash; accommodation, pay schedule, duration.</li>
</ul>

<h2>Career Progression</h2>

<ul>
    <li>Senior or lead driver overseeing a small fleet</li>
    <li>Fleet supervisor or transport coordinator</li>
    <li>Specialised driver &mdash; hazardous materials, oversized loads, oil-and-gas logistics</li>
    <li>Logistics or dispatch operations, with further training</li>
    <li>Driving instructor or safety trainer</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can I convert my licence to drive trucks in Saudi Arabia?</h3>
<p>Only if it is from a reciprocal-list country (GCC states, USA, UK, EU, Canada, Australia, New Zealand, Japan, South Korea, Singapore). Drivers from India, Pakistan, Bangladesh and the Philippines must pass the full Saudi test &mdash; theory and a road test &mdash; not simply convert.</p>

<h3>Is there a minimum wage for truck drivers in Saudi Arabia?</h3>
<p>No. There is no statutory minimum wage for expatriate private-sector workers; pay is set by the contract. The SAR 4,000 figure often quoted is a Saudization counting threshold for Saudi nationals, not a legal floor for foreign workers.</p>

<h3>How much do heavy truck drivers earn in Saudi Arabia?</h3>
<p>It depends entirely on the contract. Third-party aggregators (not official) estimate roughly SAR 1,500-2,500 for local delivery, SAR 2,500-4,000 for regional routes and SAR 4,500-7,000+ for long-haul, often plus accommodation and flights.</p>

<h3>Do I need a special licence for heavy trucks?</h3>
<p>Yes &mdash; the Saudi public / heavy-transport licence, not the private-car licence. Its minimum age is higher than 18 (commonly cited as 20 to 22); confirm the exact figure with Absher.</p>

<h3>Are foreign truck drivers still allowed in Saudi Arabia?</h3>
<p>Heavy and long-haul truck driving remains open to expatriates as of September 2026. App-based ride-hailing (Uber, Careem) is closed &mdash; reserved for Saudi nationals since 2021 &mdash; and further localisation is planned for 2026-2028, so check the current rules.</p>

<h3>How is my salary protected?</h3>
<p>Through the Wage Protection System (WPS). Whatever your contract states must be paid through WPS, on time and in full, and the ministry can act against employers who underpay or delay.</p>

<h3>How do I apply without being scammed?</h3>
<p>Use a licensed recruitment agency together with your country's official emigration system (Pakistan's BEOE, India's eMigrate). Musaned is for domestic workers, not drivers. Never pay an illegal recruitment fee.</p>

<h3>Can I change employers once I am there?</h3>
<p>Since the Labour Reform Initiative of 14 March 2021, private-sector expat workers can transfer to a new employer on contract expiry and request exit/re-entry and final-exit through Absher and Qiwa under stated conditions.</p>

<h2>People Also Search For</h2>

<h3>Truck driver salary in Saudi Arabia</h3>
<p>Contract-set, with no legal minimum for expats; aggregators estimate SAR 1,500-2,500 local up to SAR 4,500-7,000+ long-haul.</p>

<h3>Saudi driving licence conversion</h3>
<p>Free swap only for reciprocal-list countries; India, Pakistan, Bangladesh and the Philippines must take the full Saudi test.</p>

<h3>HGV jobs in Saudi Arabia</h3>
<p>Heavy goods vehicle work in logistics, construction and oil-and-gas, on the public/heavy-transport licence.</p>

<h3>Driver jobs in Saudi Arabia for Pakistani</h3>
<p>Open through licensed recruiters and the BEOE; expect to sit the Saudi driving test rather than convert.</p>

<h3>Saudi work visa for drivers</h3>
<p>An employer-sponsored Iqama, with more mobility since the 2021 Labour Reform Initiative.</p>

<h3>Long haul truck driver jobs KSA</h3>
<p>The best-paid route type, estimated at SAR 4,500-7,000+ a month by aggregators, often with accommodation.</p>

<h3>Uber and Careem driver jobs Saudi Arabia</h3>
<p>Closed to expatriates &mdash; app-based ride-hailing has been reserved for Saudi nationals since 2021.</p>

<h3>Wage Protection System Saudi Arabia</h3>
<p>The mandatory system through which private-sector salaries must be paid, protecting workers where no minimum wage applies.</p>

<h2>More Job Guides</h2>

<p>Planning a move to the Kingdom? These cover the wider picture:</p>

<ul>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the visa mechanics and the company-versus-house-driver split, in depth.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the biggest Vision 2030 employer of foreign labour.</li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a> &mdash; a related trade that keeps the fleets running.</li>
    <li><a href="/blog/no-experience-jobs-in-saudi-arabia">No Experience Jobs in Saudi Arabia</a> &mdash; entry routes and the same minimum-wage and recruitment rules.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers or immigration advice. Licence rules, Saudization decisions and pay practices change and are set by Saudi authorities and each employer. Confirm the current details with Absher, MHRSD, a licensed recruiter and the employer before applying.</p>
HTML;
    }
}
