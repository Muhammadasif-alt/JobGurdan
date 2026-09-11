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
 * "Bus Driver Jobs in Canada" — transit, school bus, coach and paratransit
 * driving. The welder guide covers the skilled trades route into Canada and
 * the delivery and Saudi driver guides cover goods and sponsored driving, so
 * this one owns Canadian bus licence classes, Job Bank pay by province and
 * city, the part-time pattern, the outlook and the rules for foreign drivers.
 *
 * Corrections to the draft:
 *
 * 1. It says drivers need a Class 2 or Class 4 licence. Ontario, the largest
 *    market, licenses bus drivers under Classes B, C, E and F, and school bus
 *    licences need you to be 21 and to pass an improvement course. Quebec
 *    uses Class 2 and 4B.
 *
 * 2. Its pay bands split school and transit work, which Job Bank does not.
 *    The provincial medians run from $22.43 in Ontario to $31.11 in British
 *    Columbia, and the city is what moves them: $30.06 in the Toronto region
 *    and $35.00 in BC's Lower Mainland.
 *
 * 3. It lists Quebec among the provinces with strong demand. Job Bank rates
 *    Quebec's 2025-2027 outlook Moderate, and rates the Atlantic provinces
 *    the draft leaves out as Good.
 *
 * 4. Its $25 to $35 transit band stops below the agencies' own top rates,
 *    $41.72 an hour at Coast Mountain Bus Company in Vancouver and $37.91 at
 *    OC Transpo, and its intercity section ignores that Greyhound Canada
 *    closed in May 2021.
 *
 * 5. It says nothing on hiring from abroad. A foreign commercial licence
 *    converts to at most a car licence in Ontario, bus driver medians fall in
 *    the low-wage LMIA stream, and no 2026 Express Entry category covers bus
 *    drivers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BusDriverJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-bus-driver-jobs.html';

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
        $title = 'Bus Driver Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Ontario licenses bus drivers under Classes B, C, E and F rather than 2 or 4, Job Bank puts the median at $22.43 an hour in Ontario but $30.06 in Toronto, and the 2025-2027 outlook is Good in most provinces.',
                'content' => $content,
                'featured_image' => 'blogs/bus-driver-jobs-in-canada.jpg',
                'tags' => 'bus driver jobs canada, school bus driver jobs, transit bus operator jobs, class b licence ontario, class 2 licence bus, bus driver salary canada, ttc bus operator, bus driver lmia, paratransit driver jobs',
                'meta_title' => 'Bus Driver Jobs in Canada 2026: Licence, Pay and Outlook',
                'meta_description' => 'Bus driver jobs in Canada: the licence class each province requires, Job Bank pay by province and city, the 2025-2027 outlook and the rules for foreign drivers.',
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
            ['name' => 'Canadian Transit Agencies, School Bus & Coach Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-bus-driver-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Bus Driver — Transit, School, Coach and Paratransit Routes, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Split shifts on school routes; early, late, weekend and holiday shifts in transit',
                'language' => 'English, French',
                // Bus driver pay differs by up to $9 an hour between provincial
                // medians and more between cities, so no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Transit, school bus, coach and paratransit driving jobs in Canada. The licence class varies by province, and many employers train new drivers.',
                'seo_keywords' => 'bus driver jobs canada, school bus driver jobs, transit bus operator jobs, coach driver jobs, paratransit driver jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Public transit agencies, school bus contractors, coach and charter companies and paratransit services across Canada hire bus drivers, from new drivers they train to experienced operators.</p>

<h3>What the work involves</h3>
<p>Driving scheduled routes safely and on time, inspecting the bus before each shift, helping passengers including those with mobility needs, checking fares and passes, and reporting incidents.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>The right licence class for the bus</strong> &mdash; Class 2 or 4 in most provinces, Class B, C, E or F in Ontario and Class 2 or 4B in Quebec, often gained through employer training</li>
    <li>A clean driving record, a medical report and a criminal record check, with a vulnerable sector check for many school routes</li>
    <li>The right to work in Canada, or an employer able to obtain an LMIA for the position</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank medians.</strong> $31.11 an hour in BC, $27.50 in Alberta, $25.00 in Nova Scotia, $23.50 in Quebec and $22.43 in Ontario</li>
    <li><strong>Cities pay more.</strong> $35.00 in BC's Lower Mainland and $30.06 in the Toronto region</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for a job offer or an LMIA.</strong> The employer pays the $1,000 LMIA fee, and it cannot be recovered from the worker.</p>

<p><strong>Note:</strong> wages, licence rules and immigration eligibility are set by employers, provincial licensing authorities and the Government of Canada &mdash; not by JobGader. Confirm the details with the employer and your province's licensing office before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Transit agencies, school bus contractors and coach companies across Canada hire bus drivers every year, and many will train a new driver who holds only a regular licence. Before you apply, especially from abroad, it helps to know four things most guides get wrong: which licence class your province actually uses, what drivers earn in each province and city, how much of the work is part-time, and how hard it is to be hired from outside Canada.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-bus-driver-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128652; Browse Bus Driver Jobs in Canada &rarr;
    </a>
</div>

<h2>The Licence Class Depends on the Province</h2>

<p>Guides say you need a Class 2 or Class 4 licence. That is the system in most provinces, but not in Ontario, the largest market, and even where the class numbers match, the seat limits and school bus rules differ.</p>

<h3>Ontario: Classes B, C, E and F</h3>

<ul>
    <li><strong>Class B</strong> &mdash; a school bus with seating for more than 24 passengers</li>
    <li><strong>Class C</strong> &mdash; a regular, non-school bus with seating for more than 24 passengers</li>
    <li><strong>Class E</strong> &mdash; a school bus with seating for up to 24 passengers</li>
    <li><strong>Class F</strong> &mdash; a regular bus with seating for up to 24 passengers, including a 15-passenger van and an ambulance</li>
</ul>

<p>You must be <strong>at least 21</strong> for a school bus licence (B or E) and at least 18 for C or F, and already hold a full licence rather than a G1 or G2. Every applicant passes a vision test and a road test, submits a medical report and needs a <strong>criminal record and judicial matters (CRJM) check</strong>. School bus drivers must also complete a government-approved <strong>School Bus Driver Improvement Course</strong> of at least 6.25 hours, and its certificate is valid for 5 years. A bus with air brakes needs a <strong>Z endorsement</strong>.</p>

<p>Once licensed, you submit a medical report every five years under age 46, every three years from 46 to 64, and every year from 65. And since <strong>11 May 2026</strong>, anyone applying for a Class A to F licence must show that Ontario is their primary residence, that they are legally in Canada and that they are <strong>eligible to work in Canada</strong>.</p>

<h3>The other provinces</h3>

<ul>
    <li><strong>British Columbia:</strong> Class 2 covers any bus, including school buses, and Class 4 covers a bus seating up to 25 people including the driver. You must be 19, and ICBC requires two years of non-learner driving experience for Class 4.</li>
    <li><strong>Alberta:</strong> Class 2 covers any bus, and Class 4 a bus, including a school bus, that seats fewer than 25 people including the driver. You must be 18 with a full Class 5 licence. Since 1 March 2019, new Class 2 drivers must complete mandatory entry-level training (MELT) before the road test, and school bus drivers need an <strong>S endorsement</strong>, plus a Q endorsement for air brakes.</li>
    <li><strong>Quebec:</strong> Class 2 for a bus with more than 24 passengers and <strong>Class 4B</strong> for a minibus or bus with 24 or fewer. School bus drivers also need a certificate of competence, which takes 15 hours of training and a 6-hour refresher every three years.</li>
    <li><strong>Manitoba:</strong> Class 2 for a bus with more than 24 passengers or a school bus with more than 36, and Class 4 below those limits, with an A endorsement for air brakes.</li>
    <li><strong>Saskatchewan:</strong> Class 2 for a bus with more than 24 passengers and Class 4 for 24 or fewer, from age 18 and not as a novice driver, plus an S endorsement to drive a school bus.</li>
</ul>

<h2>Many Employers Train You for the Licence</h2>

<p>Guides are right that you often do not need the bus licence before you apply. Job Bank's outlook for Nova Scotia notes that applicants are typically only required to have a <strong>regular Class 5 licence</strong> and a clean driving history, that a <strong>free training course</strong> is usually provided to those without a Class 2 licence, and that some employers pay an hourly wage during training or a bonus when you finish.</p>

<p>The big city transit agencies hire the same way:</p>

<ul>
    <li><strong>Edmonton Transit Service</strong> runs a <strong>29-day paid training program</strong> and covers the cost of training you to Class 2, though you pay for the licence card itself. You need five consecutive years of licensed driving.</li>
    <li><strong>Calgary Transit</strong> pays for 25 business days of training and will train you for a Class 2 licence if you do not hold one, but you pay for the air brake endorsement yourself.</li>
    <li><strong>Coast Mountain Bus Company</strong>, which runs TransLink's buses in Metro Vancouver, hires with a Class 5 licence, pays for 30 days of training, and requires a Class 2 learner's licence with an air brake endorsement.</li>
    <li><strong>OC Transpo</strong> in Ottawa asks for five years of driving experience, pays trainees half the full operator rate, and requires you to pass the Class C medical before you are hired.</li>
</ul>

<h2>What Bus Drivers Earn: Job Bank Wages</h2>

<p>Guides put school bus drivers at $20 to $28 an hour and transit drivers at $25 to $35. Job Bank's wage data for <strong>bus drivers, subway operators and other transit operators (NOC 73301)</strong>, covering 2023-2024 and updated on 19 November 2025, does not split school and transit work, but it shows where you drive matters most:</p>

<ul>
    <li><strong>British Columbia:</strong> median <strong>$31.11</strong> an hour, from $21.00 to $40.19</li>
    <li><strong>Alberta:</strong> median $27.50, from $16.25 to $37.00</li>
    <li><strong>Nova Scotia:</strong> median $25.00, from $19.98 to $30.40</li>
    <li><strong>Manitoba:</strong> median $24.00, from $18.00 to $31.00</li>
    <li><strong>Quebec:</strong> median $23.50, from $17.86 to $34.60</li>
    <li><strong>Ontario:</strong> median <strong>$22.43</strong>, from $17.60 to $38.00</li>
    <li><strong>Saskatchewan:</strong> median $22.00, from $16.00 to $30.00</li>
    <li><strong>Canada:</strong> median $25.00, from $17.18 to $37.68</li>
</ul>

<p>The big cities pay more: a median of <strong>$35.00</strong> in BC's Lower Mainland&ndash;Southwest region, <strong>$30.06</strong> in the Toronto region, $30.00 in Edmonton, $28.94 in Calgary, $26.00 in Winnipeg and Halifax, $25.64 in Montr&eacute;al and $23.00 in Ottawa. A Toronto driver's median is more than $7 an hour above Ontario's.</p>

<p>The agencies' own pay scales show where a transit career goes. At the top rate, city operators earn more than the guides' $35 ceiling:</p>

<ul>
    <li><strong>Coast Mountain Bus Company, Metro Vancouver:</strong> $29.20 an hour in training and <strong>$41.72</strong> at the top rate, from April 2025</li>
    <li><strong>OC Transpo, Ottawa:</strong> $32.22 in the first eight months, rising to <strong>$37.91</strong> after 24 months on its 2026 scale</li>
    <li><strong>TTC, Toronto:</strong> the contract with ATU Local 113 raises wages by 13.04% between April 2024 and April 2026, and new operators now start as customer service agents before being trained to drive</li>
</ul>

<h2>Expect Part-Time Work and Split Shifts</h2>

<p>Job Bank's figures show how much of this work is part-time. In Ontario, <strong>37%</strong> of bus drivers work part-time, against 19% of all workers, and <strong>54%</strong> work only part of the year. In Alberta 40% work part-time, and in Saskatchewan 48%, where 31% work for elementary and secondary schools. Job Bank also notes the seasonal nature of the work in New Brunswick, Newfoundland and Labrador and Prince Edward Island.</p>

<p>The guides' school bus range holds up. School routes follow the school day and the school year: Ontario's Ministry of Education funds driver wages at a benchmark of <strong>$24 an hour</strong>, and tops up funding so a round-trip route covers at least three hours a day. A First Student posting in Ottawa in September 2026 offered $23.34 an hour with a four-hour daily minimum, split morning and afternoon shifts and no summer work. Before you accept, ask how many paid hours a day and paid weeks a year the job offers.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/bus-driver-jobs-in-canada-operator.jpg"
         alt="A smiling bus driver in a uniform and cap at the wheel of a city bus, with the CN Tower and Toronto skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Jobs Are: Job Bank's Outlook</h2>

<p>Guides name Ontario, BC, Alberta and Quebec as the main markets. Job Bank's official <strong>2025-2027 employment outlook</strong> for bus drivers rates prospects as:</p>

<ul>
    <li><strong>Ontario:</strong> <strong>Good</strong>, with about 39,400 people in the occupation</li>
    <li><strong>Alberta:</strong> Good, about 13,600</li>
    <li><strong>British Columbia:</strong> Good, about 10,950</li>
    <li><strong>Manitoba:</strong> Good, about 3,700</li>
    <li><strong>Saskatchewan:</strong> Good, about 2,550</li>
    <li><strong>Nova Scotia:</strong> Good, about 2,000</li>
    <li><strong>New Brunswick</strong> and <strong>Newfoundland and Labrador:</strong> Good</li>
    <li><strong>Quebec:</strong> <strong>Moderate</strong>, about 22,450</li>
    <li><strong>Prince Edward Island:</strong> Moderate</li>
</ul>

<p>Retirements open positions in every province. Nova Scotia's municipal transit systems and school transportation providers have reported difficulty hiring drivers, several New Brunswick school districts are short of drivers, and rising elementary school enrolment is adding school bus work in Alberta and Saskatchewan. In BC, the federal government announced in March 2025 more than <strong>$1.5 billion over ten years</strong> for TransLink.</p>

<h2>Intercity Coach Driving Since Greyhound</h2>

<p>Guides list intercity coach work as a main option. <strong>Greyhound Canada closed all its Canadian services on 13 May 2021</strong>, so that work is now spread across smaller operators, including the Ontario government agency Ontario Northland, Orl&eacute;ans Express in Quebec and Rider Express, which advertises scheduled intercity service across Canada.</p>

<p>The pay can beat the guides' $50,000 to $65,000. Ontario Northland says its spare motor coach operators averaged <strong>$3,200 every two weeks</strong> in 2025, about $83,000 over a full year, and it asks for a Class B or C licence with a Z endorsement.</p>

<h2>Coming From Abroad: The Harder Route</h2>

<p>Guides do not cover hiring from overseas, and it is where most applicants get stuck.</p>

<ul>
    <li><strong>Your licence will not transfer to a bus class.</strong> Ontario exchanges licences only from other provinces, US states and a limited list of countries, and a commercial licence issued outside Canada earns at most a <strong>Class G</strong> car licence there. You would still pass the bus tests in Canada, and since 11 May 2026 you must also prove you are eligible to work.</li>
    <li><strong>Most bus driver LMIAs are low-wage.</strong> An employer needs a Labour Market Impact Assessment to hire you, and for LMIAs received from 17 July 2026 the high-wage thresholds are $36.92 an hour in Ontario, $38.40 in BC, $37.50 in Alberta, $36.00 in Quebec, $34.62 in Saskatchewan, $31.96 in Nova Scotia and $31.33 in Manitoba. Every provincial bus driver median is below its threshold, so most positions fall in the <strong>low-wage stream</strong>.</li>
    <li><strong>No processing in most big cities.</strong> Low-wage LMIAs are refused where the census metropolitan area's unemployment rate is 6% or more. For applications from 10 July to 8 October 2026 that includes <strong>Toronto (7.3%)</strong>, Ottawa-Gatineau (6.7%), Montr&eacute;al (6.8%), Calgary (7.0%), Edmonton (7.2%) and Vancouver (6.7%), and transportation has no exemption. Halifax, Winnipeg, Regina, Qu&eacute;bec City and Victoria were below 6%.</li>
    <li><strong>The employer pays the $1,000 LMIA fee</strong>, and it cannot be recovered from you.</li>
</ul>

<h3>Permanent residence</h3>

<ul>
    <li><strong>Express Entry programs.</strong> Bus drivers (NOC 73301, TEER 3) are in NOC Major Group 73, which the Federal Skilled Trades Program accepts, and Canadian experience in the job counts toward the Canadian Experience Class. A job offer no longer adds points since 25 March 2025.</li>
    <li><strong>No 2026 category for bus drivers.</strong> The Transport category that returned in 2026 covers pilots, aircraft mechanics and inspectors, avionics technicians, and automotive service technicians and truck and bus mechanics &mdash; not drivers.</li>
    <li><strong>Ontario.</strong> The Workforce Priority stream accepts bus driver job offers but requires at least 6 months of consecutive paid experience, and Ontario has named NOC 73301 in regional Employer Job Offer draws in 2026.</li>
    <li><strong>Atlantic Canada.</strong> The Atlantic Immigration Program accepts TEER 3 job offers of at least a year, and Job Bank rates the outlook Good in three of the four Atlantic provinces.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/bus-driver-jobs-in-canada-driving.jpg"
         alt="A bus driver in a navy uniform and cap driving a bus, with the Canadian flag and the Toronto waterfront through the windscreen"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Frequently Asked Questions</h2>

<h3>What licence do I need to drive a bus in Canada?</h3>
<p>Most provinces use Class 2 for larger buses and Class 4 for smaller ones. Ontario uses Class B or E for school buses and Class C or F for other buses, and Quebec uses Class 2 or 4B.</p>

<h3>How old do you have to be to drive a school bus in Ontario?</h3>
<p>At least 21 for a Class B or E school bus licence, and at least 18 for a Class C or F licence for other buses.</p>

<h3>How much do bus drivers earn in Canada?</h3>
<p>Job Bank's median is $25.00 an hour nationally, from $22.43 in Ontario to $31.11 in BC. City medians are higher: $30.06 in the Toronto region and $35.00 in BC's Lower Mainland.</p>

<h3>Do I need experience to become a bus driver?</h3>
<p>Often not. Many employers hire drivers with a regular licence and a clean record and train them for the bus class, and some pay during training.</p>

<h3>Are bus driver jobs part-time?</h3>
<p>Many are. In Ontario 37% of bus drivers work part-time, against 19% of all workers, and 54% work only part of the year.</p>

<h3>Which provinces have the best outlook for bus drivers?</h3>
<p>Job Bank rates the 2025-2027 outlook Good in Ontario, Alberta, BC, Manitoba, Saskatchewan, Nova Scotia, New Brunswick and Newfoundland and Labrador, and Moderate in Quebec and Prince Edward Island.</p>

<h3>Can foreigners get bus driver jobs in Canada?</h3>
<p>It is hard. A commercial licence from outside Canada converts at most to a car licence in Ontario, most bus driver jobs fall in the low-wage LMIA stream, which is not processed in Toronto, Montr&eacute;al, Calgary, Edmonton or Vancouver while unemployment there is 6% or more, and no 2026 Express Entry category covers bus drivers.</p>

<h3>What is the School Bus Driver Improvement Course?</h3>
<p>A government-approved course of at least 6.25 hours that Ontario requires for a Class B or E licence. The certificate is valid for 5 years.</p>

<h2>People Also Search For</h2>

<h3>Class B licence Ontario</h3>
<p>For school buses with more than 24 passengers, from age 21, with a knowledge test, road test and improvement course.</p>

<h3>Class 2 licence Alberta</h3>
<p>Covers any bus, from age 18 with a full Class 5 licence, after mandatory entry-level training.</p>

<h3>TransLink bus driver salary</h3>
<p>Coast Mountain Bus Company pays $29.20 an hour in training and $41.72 at the top rate, from April 2025.</p>

<h3>Bus driver jobs Vancouver</h3>
<p>A Job Bank median of $35.00 an hour in the Lower Mainland&ndash;Southwest region, with a Good outlook for BC.</p>

<h3>Bus driver jobs Nova Scotia</h3>
<p>A Good outlook, where employers usually ask only for a Class 5 licence and provide free Class 2 training.</p>

<h3>Bus driver LMIA</h3>
<p>Usually in the low-wage stream at Job Bank medians, with no exemption from the 6% unemployment rule in big cities.</p>

<h3>Quebec school bus certificate</h3>
<p>15 hours of training, then a 6-hour refresher every three years.</p>

<h3>Air brake endorsement for bus drivers</h3>
<p>Z in Ontario, Q in Alberta and A in Manitoba and Saskatchewan.</p>

<h2>More Job Guides</h2>

<p>Comparing driving jobs and routes into Canada? These cover it:</p>

<ul>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a skilled trade route into Canada, and why most welder LMIAs are low-wage too.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the seasonal agricultural route, and how its LMIA works.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; another Canadian job with no degree needed, priced by the provincial minimum wage.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; commercial driving south of the border, and what a CDL takes.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; driving work where app pay counts only engaged time.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; courier driving in Britain, and the insurance class new drivers miss.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; sponsored driving work in the Gulf.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA and its 2026 limits across every sector.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, licensing rules, LMIA thresholds and immigration programs change often. Confirm the current position with the employer, your provincial licensing authority, Job Bank and IRCC, or a licensed immigration consultant, before applying.</p>
HTML;
    }
}
