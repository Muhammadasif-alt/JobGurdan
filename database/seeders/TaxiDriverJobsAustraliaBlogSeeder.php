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
 * "Taxi Driver Jobs in Australia" — an occupation where the licensing advice in
 * circulation describes a permit that was abolished nine years ago, and where
 * the published salary figures exclude most of the people doing the job.
 *
 * Corrections to the draft:
 *
 * 1. The occupation code is wrong. ANZSCO 7313 is Train and Tram Drivers.
 *    Taxi Driver is ANZSCO 731112, within unit group 7311 Automobile Drivers.
 *    Under the replacement OSCA 2024 classification it is 711134, and rideshare
 *    driving is counted separately for the first time as 711133.
 *
 * 2. "Taxi Driver Authority" does not exist in New South Wales. Driver
 *    authorities were issued under the Passenger Transport Act 1990 and have
 *    not been valid since 1 November 2017. Drivers are now on-boarded by an
 *    authorised service provider, which issues a Driver ID and carries the
 *    eligibility and criminal-history duties itself.
 *
 * 3. The licence-holding requirement does not "vary slightly". It runs from six
 *    months in Victoria to three years in Queensland and Western Australia.
 *
 * 4. The pay band cannot be verified, and the reason matters more than the
 *    number. Official earnings series cover employees, and most taxi drivers
 *    are bailees rather than employees, so no published figure describes the
 *    bulk of the workforce.
 *
 * 5. It describes taxi driving as employment throughout. Bailment sits outside
 *    the Fair Work system, the Passenger Vehicle Transportation Award does not
 *    mention taxis at all, and superannuation generally does not follow.
 *
 * 6. It presents the outlook as stable. Automobile Drivers is one of the
 *    occupations Jobs and Skills Australia projects to shrink, against national
 *    employment growth of 13.7 per cent, and it is flagged as no shortage.
 *
 * 7. The poster supplied with this brief advertises visa sponsorship. Taxi
 *    Driver does not appear anywhere on the Core Skills Occupation List.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TaxiDriverJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-taxi-driver-jobs.html';

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
        $title = 'Taxi Driver Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'New South Wales stopped issuing taxi driver authorities in 2017, the licence you must have held runs from six months to three years by state, and most taxi drivers are bailees rather than employees, so no official salary figure describes them.',
                'content' => $content,
                'featured_image' => 'blogs/taxi-driver-jobs-in-australia.jpg',
                'tags' => 'taxi driver jobs australia, taxi driver accreditation, nsw driver id, driver authorisation queensland, ptd authorisation wa, taxi bailment, rideshare driver australia, taxi driver salary australia',
                'meta_title' => 'Taxi Driver Jobs in Australia: Licensing and Pay',
                'meta_description' => 'Taxi driver jobs in Australia: why NSW no longer issues driver authorities, what each state actually requires, and why no official salary figure covers the job.',
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
            ['name' => 'Australian Taxi Networks, Service Providers and Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-taxi-driver-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Taxi Driver — Metropolitan and Regional Networks, Australia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work including nights and weekends; many drivers set their own hours under a bailment agreement',
                'language' => 'English',
                // Most taxi drivers are bailees paid from a share of takings
                // rather than employees on a wage, so there is no advertised
                // band that would describe the work honestly.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Taxi driving roles with Australian networks and operators. Check your state accreditation requirements and whether the offer is employment or a bailment agreement.',
                'seo_keywords' => 'taxi driver jobs australia, taxi driver accreditation, nsw taxi driver id, driver authorisation queensland, taxi bailment agreement',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Taxi networks, authorised service providers and vehicle operators across Australia take on drivers continuously, in capital cities and regional centres. Work is available on day and night shifts, and a large share of it is arranged as a bailment agreement rather than a wage.</p>

<h3>What the work involves</h3>
<p>Carrying passengers safely by metered fare and by booking; rank and hail work as well as app and phone dispatch; airport and hotel runs; handling payments and receipts; assisting passengers with luggage and, in a wheelchair accessible vehicle, with loading and restraint; and keeping the vehicle clean, roadworthy and compliant with your state's safety standards.</p>

<h3>Requirements</h3>
<ul>
    <li>A full or unrestricted driver licence, held for between six months and three years depending on the state</li>
    <li>Your state's authorisation &mdash; a Driver ID from an authorised service provider in NSW, driver accreditation in Victoria and South Australia, a driver authorisation in Queensland, or a PTD authorisation in Western Australia</li>
    <li>A medical assessment against the commercial driver standards, and a criminal history check</li>
    <li>Local street knowledge and the ability to communicate with passengers about the hiring</li>
    <li>Separate training or an endorsement if you intend to drive a wheelchair accessible taxi</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Establish whether you are being offered employment or a bailment.</strong> Under a bailment agreement you are usually not an employee, which changes your pay, your leave, your superannuation and the tribunal you could go to if something goes wrong. Ask for the agreement in writing before you drive.</p>

<p><strong>Note:</strong> accreditation rules, fees, pay arrangements and eligibility are set by each state regulator and operator &mdash; not by JobGader. Confirm the details with your state's transport authority and the operator's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to taxi driving in Australia tell you to apply for a permit that one of the biggest states stopped issuing in 2017, and quote a salary drawn from a survey that does not count most taxi drivers. This page sets out what each state actually requires today, why the pay question has no clean answer, and what the official projections say about where the work is heading.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-taxi-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128661; Browse Taxi Driver Jobs in Australia &rarr;
    </a>
</div>

<h2>New South Wales Abolished the Driver Authority in 2017</h2>

<p>The advice to "apply for a Taxi Driver Authority" is the single most repeated error in this field. The NSW Point to Point Transport Commissioner states it plainly: <strong>driver authorities were issued under the previous Passenger Transport Act 1990 and have not been valid since 1 November 2017</strong>.</p>

<p>What replaced them is a different model. You are not licensed by the government as a driver. You are <strong>on-boarded by an authorised service provider</strong>, which verifies your eligibility, issues you a <strong>Driver ID</strong> card or electronic document, and carries the compliance duty itself. The Commissioner regulates the provider, not you. There is no government fee, because there is no authority to buy.</p>

<p>The eligibility criteria you must meet, which the provider checks, are these:</p>

<ul>
    <li><strong>An unrestricted Australian driver licence</strong>, held for <strong>a total of at least 12 months in the preceding four years</strong></li>
    <li><strong>The medical standards for commercial vehicle drivers.</strong> Drivers who already hold a Passenger Transport licence code (a PT Code) on their licence meet this standard</li>
    <li><strong>No disqualifying offences</strong>, with criminal history checked before you start and on an ongoing basis afterwards</li>
</ul>

<p>Two extra conditions apply narrowly rather than statewide. A <strong>Sydney metropolitan taxi driver</strong> must also be able to communicate effectively in English with passengers about a hiring. And any driver of a <strong>wheelchair accessible</strong> taxi, hire or rideshare vehicle anywhere in NSW must demonstrate competence in safe loading, restraint and unloading &mdash; and must remain competent, which is why one-off training is not treated as sufficient.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/taxi-driver-jobs-in-australia-driver.jpg" alt="A taxi driver standing beside a yellow cab in an Australian city" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Each State Actually Calls It</h2>

<p>There is no national taxi driver licence. Each state runs its own scheme under its own name, and the requirement that differs most is how long you must have held a driver licence.</p>

<ul>
    <li><strong>New South Wales</strong> &mdash; no government authority. A <strong>Driver ID</strong> issued by an authorised service provider. Unrestricted licence held 12 months in the preceding four years</li>
    <li><strong>Victoria</strong> &mdash; <strong>commercial passenger vehicle driver accreditation</strong>, now administered by <strong>Safe Transport Victoria</strong> after Commercial Passenger Vehicles Victoria was folded into it. A full licence held <strong>at least six months</strong>, probationary excluded. The health check is a <strong>self-assessment</strong> against the Austroads fitness-to-drive guidelines. The accreditation does not expire, but an annual fee is invoiced on its anniversary</li>
    <li><strong>Queensland</strong> &mdash; <strong>driver authorisation</strong> from the Department of Transport and Main Roads. You must have held an open, restricted, provisional or probationary licence for <strong>at least three years in total, including at least one continuous year</strong>. Drivers aged <strong>75 and over need an annual medical certificate</strong></li>
    <li><strong>Western Australia</strong> &mdash; a <strong>passenger transport driver (PTD) authorisation</strong>, renewed annually. You must be <strong>at least 20 years old</strong> and have held a licence for <strong>three years</strong>. A medical and a National Police Certificate are required at application and <strong>every five years</strong> after. From 1 July 2026 the fees are <strong>&#36;98.00 to apply and &#36;99.00 a year</strong> for the authorisation</li>
    <li><strong>South Australia</strong> &mdash; <strong>driver accreditation</strong> from the Department for Infrastructure and Transport. Metropolitan taxi work additionally requires a <strong>certificate of fitness for commercial vehicle drivers dated within the last three months</strong> and completion of an approved <strong>taxi driver training course</strong>. There is <strong>no cost to apply</strong> for the accreditation itself, and it runs for three years or until your working visa, licence or child-related screening expires, whichever comes first</li>
</ul>

<p>The older "F extension" that appears in Western Australian guides belongs to the pre-2019 system and no longer appears in the state's current requirements.</p>

<h2>You Do Not Need Permanent Residency</h2>

<p>This is worth stating because the assumption stops people applying. <strong>No state was found to require citizenship or permanent residency.</strong></p>

<p>Queensland accepts Australian citizens, permanent residents and New Zealand citizens on standard evidence, and asks other visa holders to complete a <strong>VEVO work-entitlement check</strong>. South Australia lists the criterion as Australian citizenship, Australian residency <strong>or a valid working visa</strong>, and ties the accreditation's expiry to the visa's. In New South Wales there is no published government residency test at all, because eligibility is assessed by the service provider that on-boards you.</p>

<p>What you do need, everywhere, is the legal right to work in Australia. The accreditation follows your visa rather than replacing it.</p>

<h2>Why No One Can Tell You the Salary</h2>

<p>The figure in circulation is <strong>"&#36;50,000 to &#36;65,000 a year"</strong>. It cannot be verified against any official Australian source, and the reason is more useful to know than the number would have been.</p>

<p>Australia's official earnings statistics measure <strong>employees</strong>. Jobs and Skills Australia publishes median weekly earnings for full-time non-managerial employees paid at the adult rate, and the ABS survey of employee earnings and hours covers employees by definition. <strong>Most taxi drivers are not employees.</strong> They drive under a <strong>bailment agreement</strong>, taking a share of the fares and paying the licence holder for use of the vehicle. Every official earnings series therefore excludes the bulk of the workforce, and any "average taxi driver salary" you read has either been drawn from a broader category or assembled from job advertisements.</p>

<p>The closest official figure is too broad to use: across the whole <strong>Machinery operators and drivers</strong> group, average weekly total cash earnings were <strong>&#36;1,695.70 in May 2025</strong>. That group includes truck, bus, train and plant operators, so do not convert it into a taxi wage.</p>

<p>What you can do is work out the real question before you accept anything: <strong>what share of the metered fare do you keep, what do you pay for the vehicle and the shift, and who pays for fuel?</strong> Under a bailment those three numbers determine your income far more than any advertised salary.</p>

<h2>Bailment Is Not Employment, and That Changes Everything</h2>

<p>The distinction has been tested and settled. In <em>Voros v Alan Dick</em> [2013] FWCFB 9339 a Full Bench of the Fair Work Commission held that a bailee taxi driver was not an employee &mdash; observing that it was the licence holder being paid by the driver, not the other way round &mdash; and dismissed the unfair dismissal application for want of jurisdiction.</p>

<p>The consequences are concrete:</p>

<ul>
    <li><strong>No modern award covers taxi driving as such.</strong> The Passenger Vehicle Transportation Award exists and reaches vehicles carrying fewer than eight people for hire or reward, but the word "taxi" does not appear in it, and it applies only where genuine employment exists</li>
    <li><strong>Superannuation generally does not follow.</strong> The courts have treated the licence owner and driver relationship as bailment rather than employment, so the superannuation guarantee does not apply where you are not an employee</li>
    <li><strong>New South Wales is the exception worth knowing.</strong> Bailees and bailors in the Sydney Metropolitan Transport District sit outside the national system and under the <strong>Taxi Industry (Contract Drivers) Contract Determination 1984</strong>, which cannot be varied by private agreement. Under it, permanent bailees receive paid annual, sick and long service leave. Casual bailees receive none</li>
</ul>

<p>If an operator offers you a bailment agreement, read it as a commercial contract, not a job offer.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/taxi-driver-jobs-in-australia-airport.jpg" alt="A taxi driver opening the door for a passenger at an airport terminal" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Outlook Is Declining, Not Stable</h2>

<p>Jobs and Skills Australia does not publish projections for taxi driving on its own; they are produced for the parent group, <strong>Automobile Drivers</strong>, which covers taxi and rideshare driving together. That group has <strong>around 50,800 people employed and a projected annual change of about &minus;1,400</strong> &mdash; a shrinking occupation, measured over the May 2024 to May 2034 projection period, against <strong>national employment growth of 13.7 per cent</strong> over the same decade. Taxi driving is also flagged as <strong>no shortage</strong> in the national shortage analysis.</p>

<p>The passenger data explains why. IPART's survey of point to point transport use in New South Wales found <strong>48 per cent of people had used rideshare in the past six months against 39 per cent for taxis</strong>, with taxi use in Sydney now below its 2019 level and median fares for both falling since the previous survey. The total market has grown; the taxi share of it has not.</p>

<p>Two structural changes sit behind that. New South Wales <strong>removed the restrictions on the number of taxi licences</strong> under its 2022 framework, and paid out a licence assistance package of <strong>&#36;905 million</strong> &mdash; up to <strong>&#36;150,000 per licence in metropolitan Sydney</strong>, capped at six per owner, and between <strong>&#36;40,000 and &#36;195,000</strong> regionally. Those applications closed on 31 May 2023. Victoria ran a comparable scheme after metropolitan licence values fell from roughly <strong>&#36;500,000 in 2009</strong> to around <strong>&#36;150,000</strong> by 2016.</p>

<p>The practical reading for a driver: this is a job you can start quickly and one where the licence itself is no longer an asset worth buying.</p>

<h2>The Levy Is Not Charged to You</h2>

<p>New South Wales applies a <strong>Passenger Service Levy of &#36;1.20 per passenger service transaction</strong>, which funds the licence assistance package and runs until 31 December 2030 unless collected sooner. It is payable by <strong>authorised taxi and booking service providers</strong>, not by drivers. Providers may absorb it or pass it to the passenger, and if passed on it attracts GST, making it <strong>&#36;1.32</strong> on the fare.</p>

<h2>Visa Sponsorship: the Honest Answer</h2>

<p>Recruitment posters for this occupation frequently advertise visa sponsorship. <strong>Taxi Driver does not appear on the Core Skills Occupation List</strong>, the list that governs which occupations can be nominated under the skills in demand route. Nursing, carpentry and electrical trades all appear on it; taxi driving does not appear anywhere in it.</p>

<p>That is consistent with how the list is built: it covers occupations at the higher ANZSCO skill levels, and taxi driving sits below that band and is assessed as being in no shortage. If an advertisement offers to sponsor you to drive a taxi in Australia, treat it as a reason to walk away.</p>

<p>The realistic position is the opposite way round: several states will accredit you <strong>if you already hold a visa with work rights</strong>, and tie that accreditation to the life of the visa.</p>

<h2>Getting the Occupation Code Right</h2>

<p>If you are checking official data yourself, use the right code. <strong>ANZSCO 7313 is Train and Tram Drivers</strong>, not taxi drivers, and it is quoted wrongly in a great deal of careers material. Taxi Driver is <strong>ANZSCO 731112</strong>, inside unit group <strong>7311 Automobile Drivers</strong>.</p>

<p>The classification is also being replaced. Under <strong>OSCA 2024</strong>, Taxi Driver is <strong>711134</strong> and <strong>Rideshare Driver is 711133</strong> &mdash; the first time the two have been counted as separate occupations, which should finally make it possible to measure them apart.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-taxi-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128663; See Current Australian Taxi Driver Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I still need a Taxi Driver Authority in New South Wales?</h3>
<p>No. Driver authorities were issued under the Passenger Transport Act 1990 and have not been valid since 1 November 2017. You are on-boarded by an authorised service provider, which checks your eligibility and issues you a Driver ID.</p>

<h3>How long must I have held a driver licence?</h3>
<p>It depends on the state, and the range is wide. Victoria requires a full licence held six months; New South Wales an unrestricted licence held 12 months within the preceding four years; Queensland three years in total including one continuous year; and Western Australia three years, with a minimum age of 20.</p>

<h3>Can I drive a taxi in Australia on a temporary visa?</h3>
<p>Yes, in the states that publish the rule. Queensland accepts a visa with work rights through a VEVO check, and South Australia accepts a valid working visa but ties your accreditation to its expiry. No state was found to require permanent residency.</p>

<h3>How much do taxi drivers earn in Australia?</h3>
<p>There is no reliable official figure. Australia's earnings statistics cover employees, and most taxi drivers work under bailment agreements rather than as employees, so they fall outside the surveys. What matters is your share of the fares and what you pay for the vehicle and fuel.</p>

<h3>Is a taxi driver an employee?</h3>
<p>Usually not. Bailment was held in Voros v Alan Dick to fall outside employment, so unfair dismissal, award coverage and superannuation generally do not apply. Sydney metropolitan bailees are the exception, covered by the Taxi Industry (Contract Drivers) Contract Determination 1984.</p>

<h3>Can I get visa sponsorship to drive a taxi in Australia?</h3>
<p>No. Taxi Driver does not appear on the Core Skills Occupation List, which governs skilled nomination, and the occupation is assessed as being in no shortage. Any advertisement promising sponsorship for taxi driving should be treated with suspicion.</p>

<h3>Do I pay the New South Wales passenger service levy?</h3>
<p>No. The &#36;1.20 per trip levy is payable by authorised taxi and booking service providers, not by drivers. Providers may pass it on to passengers, in which case it attracts GST and appears as &#36;1.32.</p>

<h3>What extra training do I need for a wheelchair accessible taxi?</h3>
<p>It varies. Victoria issues a formal WAV endorsement on the accreditation after a theory and practical assessment. Queensland requires mandatory training before an operator may engage you. New South Wales has no endorsement but requires you to be assessed as competent and to remain so.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Taxi driver requirements NSW</strong> &mdash; an unrestricted licence held 12 months in four years, commercial medical standards, and on-boarding by an authorised service provider</li>
    <li><strong>How to get a driver authorisation in Queensland</strong> &mdash; three years of licence history including one continuous year, plus a criminal history check through Transport and Main Roads</li>
    <li><strong>PTD authorisation WA cost</strong> &mdash; &#36;98.00 to apply and &#36;99.00 a year from 1 July 2026</li>
    <li><strong>Taxi bailment agreement Australia</strong> &mdash; a commercial contract, not employment; check your fare share and vehicle costs before signing</li>
    <li><strong>Is taxi driver on the skilled occupation list Australia</strong> &mdash; no, it does not appear on the Core Skills Occupation List</li>
    <li><strong>Taxi vs rideshare driver Australia</strong> &mdash; OSCA 2024 finally counts them separately, as 711134 and 711133</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a></li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a></li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a></li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a></li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a></li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a></li>
</ul>
HTML;
    }
}
