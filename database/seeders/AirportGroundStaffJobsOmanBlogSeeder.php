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
 * Airport ground staff jobs in Oman, checked on 30 September 2026 against the
 * employers' own portals, Oman Labour Law (Royal Decree 53/2023) and the
 * Ministry of Labour's Omanisation instruments.
 *
 * This page sits next to our Dubai ground staff guide and must not repeat it.
 * The Dubai page is built on the shift-worker exception to the UAE overtime
 * premium. Oman's genuinely different body of fact is Omanisation: the state is
 * actively closing occupations to expatriates on a published timetable, which
 * is the single thing a foreign reader most needs to know before spending money
 * on an application.
 *
 * The spine:
 *
 * 1. Omanisation. Ministry of Labour resolutions, among them 235/2022 and
 *    501/2024, restrict a growing list of occupations to Omani nationals on a
 *    phased timetable running 2 September 2024, 1 January 2025, 1 January 2026
 *    and 1 January 2027. The mechanism is the work permit: a restricted
 *    occupation stops new expatriate permits rather than dismissing people
 *    already holding one. There is no single Omanisation rate, and the page
 *    refuses to guess whether any particular airport job is on the list.
 * 2. Oman's labour law is not the UAE's, and the numbers most guides copy from
 *    Dubai are wrong here. 40 hours a week, not 48. Twenty-one days of annual
 *    leave, not 30. Overtime normally requires the employee's consent, which
 *    the UAE does not say.
 * 3. What the official portals actually showed on the day of writing. Oman
 *    Airports had three Muscat vacancies live and not one was a ground staff
 *    role. That is the honest answer to "are they hiring", and no competing
 *    guide gives it.
 *
 * Corrections applied to the supplied brief:
 *  1. Three om.indeed.com links were removed, along with the recommendation of
 *     "reputable job websites".
 *  2. The brief's Oman Air eRecruit link, on services.omanair.com, returns 502.
 *     The working address is on www.omanair.com and that is what is published.
 *  3. The brief states that TRANSOM's careers page showed no current openings.
 *     No TRANSOM website could be reached from any of three candidate domains
 *     during checking, so neither the company's careers page nor its vacancy
 *     status is asserted or linked.
 *  4. No salary figure is published. Oman's minimum wage of OMR 325 applies to
 *     Omani nationals only; there is no statutory minimum for expatriates.
 *
 * Deliberately NOT claimed:
 *  - That airport ground staff, or any specific airport job title, appears on
 *    the restricted list. The Ministry's own English-language list of restricted
 *    occupations could not be reached during checking, and guessing would be
 *    worse than useless to someone deciding whether to apply.
 *  - Any Omanisation percentage for aviation. The rate varies by activity,
 *    sector, size and location, and the Ministry publishes no single figure.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AirportGroundStaffJobsOmanBlogSeeder extends Seeder
{
    public const SLUG = 'airport-ground-staff-jobs-in-oman';

    private const APPLY_URL = 'https://services.omanair.com/om/en/careers';

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
        $title = 'Airport Ground Staff Jobs in Oman';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'Oman is closing occupations to expatriates on a published timetable, and the work permit is how it is enforced. Here is who employs ground staff at Omani airports, what the labour law guarantees, and how to check before you spend anything.',
                'content' => $content,
                'featured_image' => 'blogs/airport-ground-staff-jobs-oman.jpg',
                'tags' => 'airport ground staff jobs oman, oman air careers, oman airports careers, muscat airport jobs, omanisation rules, ground handling jobs oman, oman labour law working hours, oman work permit expatriate',
                'meta_title' => 'Airport Ground Staff Jobs in Oman: Omanisation Rules',
                'meta_description' => 'Airport ground staff jobs in Oman: who actually employs you, how Omanisation decides whether you can be hired at all, and what the labour law guarantees.',
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
            ['name' => 'Oman Airport Ground Handling Employers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'oman-ground-handling-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Oman'],
            ['area' => 'Nationwide', 'country' => 'Oman']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Airport Ground Staff, Oman',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts around the clock; 8 hours a day or 40 a week under Oman Labour Law',
                'language' => 'English',
                // Oman's minimum wage covers Omani nationals only and no airport
                // employer publishes ground staff pay, so there is no figure
                // this listing can honestly carry.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Passenger service, baggage, ramp and cargo roles at Omani airports. Check the Omanisation position for the occupation before you pay anyone anything.',
                'seo_keywords' => 'airport ground staff jobs oman, oman air careers, oman airports careers, muscat airport jobs, ground handling jobs oman',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated overview of airport ground roles in Oman, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own portal, and there is more than one employer at an Omani airport.</p>
<p>These are on-site roles on rotating shifts, including nights, weekends and public holidays. Oman Labour Law sets 8 hours a day and 40 hours a week, caps the total including overtime at 12 hours in a day, and gives at least 21 days of paid annual leave.</p>
<p>No salary is stated. Oman's minimum wage applies to Omani nationals only, there is no statutory minimum for expatriates, and no airport employer publishes ground staff pay.</p>
<p><strong>Before anything else, check the Omanisation position.</strong> The Ministry of Labour restricts a phased and growing list of occupations to Omani nationals, and the restriction works by refusing new expatriate work permits. If the occupation is closed, no amount of interviewing will produce a permit.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to airport ground staff jobs in Oman answer the wrong question. They tell you what a passenger services agent does, which you could guess, and they skip the question that actually decides your outcome: <strong>is an expatriate allowed to hold this job at all?</strong></p>

<p>In Oman that is not rhetorical. The Ministry of Labour is closing occupations to expatriates on a published timetable, and the mechanism is the work permit. If the occupation is restricted, the employer cannot get you a permit no matter how well the interview goes.</p>

<p>So this page starts there, then gives the labour law as Oman writes it rather than as Dubai writes it, and ends with what the official portals were actually showing on the day it was written. Everything here comes from the employers' own portals and from Oman's own legal instruments. No salary figure appears anywhere, for a reason set out below.</p>

<h2 id="omanisation">Omanisation Decides Whether You Can Be Hired</h2>

<p>Omanisation is a quota and reservation policy run by the Ministry of Labour. Private employers must employ a proportion of Omani nationals, and a growing list of occupations is reserved for Omanis outright.</p>

<p>Two things about it matter more than anything else on this page.</p>

<p><strong>First, it is phased, and it is still moving.</strong> The restrictions sit in a series of Ministry of Labour resolutions, among them Resolution 235/2022 and Resolution 501/2024, and they take effect in tranches rather than all at once. Reported commencement dates run 2 September 2024, then 1 January 2025, 1 January 2026 and 1 January 2027, with the total number of affected occupations reported at more than two hundred. The categories named in reporting include administrative and human resources roles, sales and <em>customer service</em> roles, and technical and supervisory posts. Customer service is worth noticing, because a passenger services agent is a customer service job.</p>

<p><strong>Second, the mechanism is the permit, not the person.</strong> A restriction stops <em>new</em> expatriate work permits in that occupation. Expatriates already holding a valid permit are generally reported as able to continue until it expires. That is why you can meet someone doing the job today and still be unable to be hired into it tomorrow.</p>

<p>Enforcement is not symbolic. Employers must hold an electronic compliance certificate from the Ministry of Labour showing they meet the prescribed Omanisation percentages, and non-compliance carries a reported penalty of OMR 500 per month for each unfilled position. An employer facing that bill has a strong reason to give the job to an Omani national.</p>

<p><strong>Here is what we are not going to do.</strong> We are not going to tell you that airport ground staff is, or is not, on the restricted list. The Ministry of Labour's own English-language list of restricted occupations could not be reached while this page was being checked, and a guess would be worse than useless to someone deciding whether to spend money on an application. There is also no single Omanisation percentage to quote: the rate depends on the registered activity, the sector, the size of the establishment, the location and the job title.</p>

<p>What you can do instead is make the employer answer it. Before you pay any agent, book any travel or resign anything, ask the employer one written question: <em>is this occupation open to expatriate work permits, and will you be applying for a permit for me?</em> A real employer can answer that in one line. An agent selling you a dream cannot.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/airport-ground-staff-jobs-oman-omanisation.jpg" alt="Ministry of Labour office signage in Oman representing the work permit and Omanisation process" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Omanisation is enforced through the work permit, so the question to settle first is whether a permit can be issued at all.</figcaption>
</figure>

<h2 id="who-employs-you">Who Actually Employs Ground Staff in Oman</h2>

<p>As at Dubai, the airport is a shared workplace and the name on the terminal is usually not the name on your contract.</p>

<ul>
    <li><strong>Oman Air</strong> recruits through its own careers pages at <a href="https://services.omanair.com/om/en/careers" rel="nofollow noopener" target="_blank">services.omanair.com</a>, which carry a Careers section, Employee Benefits, an Application Guide and Latest Vacancies. The airline states that it "recruits fresh graduates as well as those with a few years of working experience for their different administrative and technical divisions", and lists opportunities in "Engineering, Airport jobs, Administrative / Clerical, Cargo, Flight attendants, Management, Reservation". Its vacancy system is the eRecruit portal at <a href="https://www.omanair.com/erecruit/guest/latestActVacancy_getAllActiveVacancy.do" rel="nofollow noopener" target="_blank">omanair.com</a>. If you have seen an eRecruit link on <em>services.omanair.com</em> that fails to load, that is not your connection; that address returned a server error during checking and the working one is the address given here.</li>
    <li><strong>Oman Airports</strong> operates the civil airports themselves and recruits through its own portal at <a href="https://eservices.omanairports.co.om/Eservices/Recruitment/Vacancies.aspx" rel="nofollow noopener" target="_blank">eservices.omanairports.co.om</a>. The portal lets you filter by location, by technical or non-technical, and by division.</li>
    <li><strong>Ground handling and contracted operations.</strong> Baggage, ramp, cargo, cleaning, retail and catering work at an Omani airport is frequently carried out by handling companies and contractors rather than by the airline or the airport operator. These are real jobs under real employers, and every rule on this page applies to them identically. We are not naming or linking a handling company here: the one most often cited in guides to this subject had no reachable website from any of the addresses we tried while checking, and we will not send you to a page we could not open.</li>
</ul>

<p>Search by job title rather than by employer: <em>airport services agent</em>, <em>passenger service agent</em>, <em>ground handling agent</em>, <em>ramp agent</em>, <em>baggage services agent</em>, <em>cargo agent</em>, <em>airport operations assistant</em>. Employers rarely advertise the exact phrase "airport ground staff".</p>

<h2 id="what-the-portals-show">What the Official Portals Were Showing</h2>

<p>Guides to this subject tend to imply a permanent hiring wave. Here is the unglamorous truth, recorded on the day this page was written.</p>

<p>The Oman Airports recruitment portal had three live vacancies, all in Muscat: a Service Desk Operator in the Technology division, a Director of Digital Transformation and Innovation, also Technology, and a Senior Accountant in Finance. <strong>Not one of them was a ground staff role.</strong> Each carried a posting window of about a week.</p>

<p>That is not a reason to give up, and it is not evidence that Oman Airports never hires ground staff. It is evidence that these vacancies are episodic and short-lived, that the window between a posting opening and closing can be days rather than months, and that anyone promising you a currently available ground staff position at an Omani airport should be asked to show you the posting on the official portal.</p>

<p>One useful detail from that portal: Oman Airports does not only mean Muscat. Its location filter covers Muscat, Sohar, Al Duqm, Adam and a set of airfields including Marmul, Qarn Alam, Fahud and a PDO airport, which serve Oman's oil and gas interior. Those are genuine airport operations in places most jobseekers never search for, and they are less contested than Muscat.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/airport-ground-staff-jobs-oman-muscat.jpg" alt="Passenger terminal operations at Muscat International Airport in Oman" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Muscat is the obvious target, but Oman Airports also runs regional and oil-field airfields that attract far fewer applications.</figcaption>
</figure>

<h2 id="hours-and-overtime">Oman's Hours Are Not Dubai's Hours</h2>

<p>A great deal of writing about Gulf airport jobs quotes UAE figures and applies them across the region. For Oman that is wrong, and the differences favour you.</p>

<p>Oman Labour Law, issued by Royal Decree 53/2023, sets the framework.</p>

<ul>
    <li><strong>Eight hours a day and a maximum of 40 hours a week.</strong> The daily figure came down from nine under the previous law. Forty hours a week is eight fewer than the UAE's 48.</li>
    <li><strong>Twelve hours is the ceiling for everything combined.</strong> Normal hours plus overtime may not exceed 12 hours in a single day.</li>
    <li><strong>Overtime is normally voluntary.</strong> Under ordinary circumstances overtime requires the employee's consent. This is a genuine difference from the UAE position and it is worth knowing before a roster is handed to you.</li>
    <li><strong>Overtime is paid at the basic wage plus at least 25 per cent by day and at least 50 per cent at night.</strong> With the employee's agreement the employer may give time off in lieu instead of the money.</li>
    <li><strong>Compulsory overtime is bounded.</strong> An employer may require overtime without prior consent for defined annual operational tasks such as stocktaking, budget preparation and closing accounts, capped at 15 days in a year, and in genuine emergencies.</li>
    <li><strong>Working your rest day or a public holiday is paid extra.</strong> The entitlement is an additional day's basic pay on top of the normal daily pay, or an extra day of leave.</li>
    <li><strong>At least 21 days of paid annual leave</strong>, and up to 30 days of paid sick leave a year on a valid medical certificate.</li>
</ul>

<p>Note the honest comparison, because you will not find it elsewhere: on leave, Oman's statutory 21 days is <em>lower</em> than the UAE's 30. On weekly hours, Oman's 40 is lower, which is better. Neither country is uniformly more generous, and anyone who tells you the Gulf has one labour law has not read either.</p>

<h2 id="pay">Why There Is No Salary Figure on This Page</h2>

<p>Oman does have a minimum wage, and it does not protect you. The private sector minimum of OMR 325 a month, made up of OMR 225 basic plus OMR 100 allowance, applies to <strong>Omani nationals</strong>. There is no statutory minimum wage for expatriates. Pay for a foreign worker is whatever the contract says, which is exactly why the contract matters more than any number you read online.</p>

<p>None of the airport employers publishes ground staff pay either. Oman Air says only that it offers "a competitive total rewards package that few other companies match", which is a sentence about marketing rather than money.</p>

<p>So we are not publishing a figure. You will find sites quoting confident monthly ranges for Omani ground staff; they are estimates dressed as facts, and two of them rarely agree. What to do instead is concrete: get the offer in writing and confirm the basic salary separately from allowances, because in Oman the basic wage is the figure that drives your overtime rate and your end-of-service entitlement. An offer with a small basic and a large allowance is worth less than the same headline total structured the other way.</p>

<h2 id="permit-and-transfer">The Work Permit, and the Rule That Changed</h2>

<p>An expatriate works in Oman on a work permit obtained by the employer, and the employer must hold a licence permitting it to employ foreign workers. You cannot obtain a permit for yourself, and no agent can obtain one for you without an employer behind it.</p>

<p>One rule changed in your favour and is still widely misreported. <strong>The No Objection Certificate requirement was removed.</strong> Under Royal Oman Police Decision 157/2020, effective 1 January 2021, an expatriate no longer needs a No Objection Certificate from the existing employer in order to move to a new one. Before that change, leaving without an NOC meant a two-year ban on working for another employer in the Sultanate.</p>

<p>That does not mean moving is unconditional. A transfer still requires proof that the previous employment was terminated, and the new employer must have obtained labour clearance for an expatriate to occupy the role and must hold the licence that allows it to employ foreigners. But the old trap, where an employer could hold your career hostage by refusing a signature, is gone. If a recruiter tells you that you will be banned for two years if you change jobs without your employer's permission, they are describing the position before 2021.</p>

<h2 id="fees">Nobody May Charge You for the Job</h2>

<p>Recruitment costs are the employer's to bear, and an offer that requires you to pay for the job itself is the clearest signal of a scam there is. This holds regardless of what the agent calls the payment: registration, processing, visa deposit, uniform, training, medical or seat reservation.</p>

<p>If you are applying from Pakistan, the criminal exposure sits at your end as well. Under the Emigration Ordinance 1979, an unlicensed person who takes money for foreign employment, and a licensed promoter who charges more than the prescribed amount, face punishment that extends to "imprisonment for term, which may extend to fourteen years". Overseas employment is meant to be arranged through a licensed Overseas Employment Promoter and registered with the Protector of Emigrants. Our <a href="/blog/how-to-apply-for-dp-world-port-jobs-in-uae">DP World guide</a> sets out the Pakistani side of the paperwork in more detail.</p>

<p>Two practical tests that cost nothing. Ask for the vacancy reference and find it yourself on the employer's own portal; the Oman Airports portal shows a reference code such as HR/26/932 against every posting. And check the email domain: correspondence about an Oman Air or Oman Airports job comes from the employer's own domain, not from a free webmail account with the company name in the display name.</p>

<h2 id="apply">How to Apply</h2>

<ol>
    <li><strong>Settle the Omanisation question first,</strong> in writing, with the employer. Everything else is wasted effort if a permit cannot be issued.</li>
    <li><strong>Apply on the employer's own portal.</strong> Oman Air through its careers pages and the eRecruit system; Oman Airports through its recruitment portal. The Oman Airports system also tells applicants to "update your qualifications, experiences and documents before you applying on any job", so complete the profile before a short posting window opens rather than during it.</li>
    <li><strong>Search several job titles,</strong> not the phrase "ground staff", and set the location filter beyond Muscat.</li>
    <li><strong>Prepare for shift work honestly.</strong> Airport operations run through the night, weekends and public holidays, and operational roles involve standing, lifting and working around equipment.</li>
    <li><strong>Read the contract for the basic wage,</strong> the hours, the overtime treatment and the leave entitlement, and check them against the statutory floor set out above.</li>
    <li><strong>Pay nobody.</strong> No fee, no deposit, and no passport handed to an agent.</li>
</ol>

<h2 id="faq">Frequently Asked Questions</h2>

<h3>Can a foreigner get an airport ground staff job in Oman?</h3>
<p>Sometimes, and it depends on the occupation rather than on you. Oman's Ministry of Labour restricts a phased and growing list of occupations to Omani nationals, and the restriction operates by refusing new expatriate work permits. Ask the employer in writing whether the occupation is open to an expatriate permit before committing anything.</p>

<h3>What is Omanisation and how does it affect my application?</h3>
<p>It is a policy requiring private employers to employ a proportion of Omani nationals and reserving certain occupations to them entirely. It is enforced through Ministry of Labour resolutions, among them 235/2022 and 501/2024, on a timetable with tranches reported for 2 September 2024, 1 January 2025, 1 January 2026 and 1 January 2027. Employers must hold an electronic compliance certificate and face a reported penalty of OMR 500 a month for each unfilled position.</p>

<h3>What is the salary for airport ground staff in Oman?</h3>
<p>No employer publishes one, and we will not repeat a guess. Oman's minimum wage of OMR 325 a month, being OMR 225 basic plus OMR 100 allowance, applies to Omani nationals only; there is no statutory minimum for expatriates. Confirm the basic wage in writing, separately from allowances, because the basic figure drives your overtime rate and end-of-service entitlement.</p>

<h3>How many hours a week do airport staff work in Oman?</h3>
<p>Oman Labour Law sets 8 hours a day and a maximum of 40 hours a week, with normal hours plus overtime capped at 12 hours in a single day. That is eight hours a week fewer than the UAE, so do not apply Dubai figures to an Omani roster.</p>

<h3>Is overtime compulsory in Oman?</h3>
<p>Not normally. Under ordinary circumstances overtime requires the employee's consent. An employer may require it without consent for defined annual tasks such as stocktaking and closing accounts, capped at 15 days a year, and in genuine emergencies. Overtime is paid at the basic wage plus at least 25 per cent by day and at least 50 per cent at night, or time off in lieu if you agree.</p>

<h3>How much annual leave will I get?</h3>
<p>At least 21 days of paid annual leave, plus up to 30 days of paid sick leave a year on a valid medical certificate. Working a weekly rest day or a public holiday entitles you to an extra day's basic pay on top of your normal daily pay, or an additional day of leave.</p>

<h3>Do I still need a No Objection Certificate to change employer in Oman?</h3>
<p>No. Royal Oman Police Decision 157/2020 removed the requirement from 1 January 2021, ending the two-year ban that previously followed leaving without one. A transfer still requires proof the previous employment ended, and the new employer must have labour clearance and a licence to employ foreigners.</p>

<h3>Are there airport jobs in Oman outside Muscat?</h3>
<p>Yes. The Oman Airports recruitment portal filters by location and covers Sohar, Al Duqm, Adam and airfields including Marmul, Qarn Alam and Fahud serving the oil and gas interior. These attract far fewer applications than Muscat.</p>

<h2 id="people-also-search-for">People Also Search For</h2>

<h3>Oman Air careers</h3>
<p>The airline's own careers pages, covering Engineering, Airport jobs, Administrative and Clerical, Cargo, Flight attendants, Management and Reservation, with vacancies listed through its eRecruit system.</p>

<h3>Oman Airports careers portal</h3>
<p>The airport operator's own recruitment system, filterable by location, by technical or non-technical and by division, showing a reference code and a posting window against each vacancy.</p>

<h3>Omanisation list of restricted professions</h3>
<p>The Ministry of Labour's phased restrictions on occupations open to expatriates, issued through resolutions including 235/2022 and 501/2024.</p>

<h3>Oman Labour Law Royal Decree 53/2023</h3>
<p>The current labour law, which set eight hours a day, 40 hours a week and at least 21 days of paid annual leave.</p>

<h3>Oman work permit for expatriates</h3>
<p>The employer-obtained permit that makes foreign employment lawful, which requires the employer to hold a licence to employ foreign workers.</p>

<h3>Oman NOC rule for changing jobs</h3>
<p>Royal Oman Police Decision 157/2020, which removed the No Objection Certificate requirement from 1 January 2021.</p>

<h3>Muscat International Airport jobs</h3>
<p>Vacancies at Oman's main hub, advertised by the airline, the airport operator and contracted handling companies separately.</p>

<h3>Ground handling jobs Gulf</h3>
<p>Baggage, ramp and cargo work across Gulf airports, where the labour rules differ by country more than most guides admit.</p>

<h2 id="more-guides">More Job Guides</h2>

<ul>
    <li><a href="/blog/airport-ground-staff-jobs-in-dubai">Airport Ground Staff Jobs in Dubai</a> &mdash; the same work across the border, where the decisive rule is the shift-worker exception to the overtime premium rather than Omanisation.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">Etihad Airport Jobs in the UAE</a> &mdash; the UAE entitlements table, and how airline recruitment differs from airport recruitment.</li>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">Emirates Cabin Crew Jobs in the UAE</a> &mdash; the cabin side of the same industry, with its own selection process.</li>
    <li><a href="/blog/how-to-apply-for-dp-world-port-jobs-in-uae">DP World Port Jobs in the UAE</a> &mdash; the Pakistani emigration paperwork and costs, set out in full.</li>
    <li><a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">Heathrow Airport Jobs in the UK</a> &mdash; the same job under an entirely different immigration system.</li>
</ul>

<h2 id="sources">Official Sources</h2>

<ul>
    <li><a href="https://services.omanair.com/om/en/careers" rel="nofollow noopener" target="_blank">Oman Air Careers</a> and the <a href="https://www.omanair.com/erecruit/guest/latestActVacancy_getAllActiveVacancy.do" rel="nofollow noopener" target="_blank">Oman Air eRecruit vacancy list</a></li>
    <li><a href="https://eservices.omanairports.co.om/Eservices/Recruitment/Vacancies.aspx" rel="nofollow noopener" target="_blank">Oman Airports recruitment portal</a></li>
    <li><a href="https://www.mol.gov.om/" rel="nofollow noopener" target="_blank">Ministry of Labour, Sultanate of Oman</a></li>
    <li><a href="https://www.oman.om/" rel="nofollow noopener" target="_blank">Oman Government Portal</a></li>
</ul>

<p style="font-size:14px;color:#666;margin-top:26px;">Checked on 30 September 2026. Vacancy listings, Omanisation tranches and statutory figures change; confirm the current position on the employer's own portal and with the Ministry of Labour before acting. JobGader is not a recruiter and does not accept payment from applicants.</p>
HTML;
    }
}
