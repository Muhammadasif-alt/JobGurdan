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
 * Airport ground staff jobs at Dubai International Airport, checked on
 * 30 September 2026 against u.ae, the Emigration Ordinance 1979 as amended to
 * 2021, and the employers' own careers sites.
 *
 * The supplied brief had no checkable fact in it. Its one genuinely good
 * observation was buried in a single sentence: working at Dubai International
 * Airport does not mean working for Emirates. This page takes that seriously
 * and builds on the thing a ground staff advert never spells out -- that the
 * job is shift work, and UAE labour law treats shift workers differently from
 * the way most guides describe.
 *
 * The spine, none of which appears on our Abu Dhabi guide:
 *
 * 1. The overtime premium everyone quotes carries an exception. The UAE
 *    Government's own wording on the 25 per cent and the 50 per cent night
 *    premium ends: "This rule does not apply on workers who work on basis of
 *    shifts." Airport ground staff are the textbook shift workers, so this is
 *    the single most useful correction on the page.
 * 2. The medical is a named list of tests under Cabinet Resolution No. 5 of
 *    2016, not a vague "medical fitness test", and two of them deny residency
 *    outright on a positive result.
 * 3. The Wage Protection System changed. It now runs under Ministerial
 *    Resolution No. 340 of 2026, with wages due on the first day of each
 *    Gregorian month and a day-by-day enforcement ladder. Anything still
 *    quoting a 15-day rule is describing the superseded 2022 resolution.
 *
 * Corrections applied to the supplied brief:
 *  1. The ae.indeed.com link was removed. So was the closing line recommending
 *     "reputable job portals such as Indeed UAE".
 *  2. The brief asserts benefits -- health insurance, annual leave, flight
 *     allowances, transport -- as though they were standard. Only the leave
 *     entitlement is law. The rest is employer-specific and is not repeated.
 *  3. No salary figure is published. The UAE has no statutory minimum wage and
 *     none of the three employers publishes ground staff pay, so the page gives
 *     the statutory entitlements instead of an estimate.
 *
 * Deliberately NOT claimed:
 *  - The maximum service charge a licensed Pakistani Overseas Employment
 *    Promoter may take. That cap lives in Rule 15 of the Emigration Rules 1979
 *    and every BEOE host was behind a challenge page during checking. The
 *    Ordinance's own criminal provision is quoted instead, which is stronger,
 *    and readers are sent to our DP World guide for the costs breakdown.
 *  - Any article number for the recruitment fee ban. The UAE Government states
 *    the rule plainly; its own copies of the decree-law PDF return 404, so the
 *    prohibition is quoted from the Government portal without a citation it
 *    could not stand behind.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AirportGroundStaffJobsDubaiBlogSeeder extends Seeder
{
    public const SLUG = 'airport-ground-staff-jobs-in-dubai';

    private const APPLY_URL = 'https://www.emiratesgroupcareers.com/search-and-apply/';

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
        $title = 'Airport Ground Staff Jobs in Dubai';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'Ground staff work at Dubai International Airport is shift work, and UAE labour law treats shift workers differently from the way most guides describe. Here is who actually employs you, what the law guarantees, and the medical that decides it.',
                'content' => $content,
                'featured_image' => 'blogs/airport-ground-staff-jobs-dubai.jpg',
                'tags' => 'airport ground staff jobs dubai, dubai airport jobs, dnata ground handling jobs, emirates group careers, dubai airports careers, ramp agent jobs dubai, uae labour law shift work, uae work visa medical test',
                'meta_title' => 'Airport Ground Staff Jobs in Dubai: Shift and Pay Rules',
                'meta_description' => 'Airport ground staff jobs in Dubai: who employs you at DXB, why the night overtime premium may not reach shift workers, and the medical that gates the visa.',
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
            ['name' => 'Dubai International Airport Ground Handling Employers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'dxb-ground-handling-aggregated']
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
                'position' => 'Airport Ground Staff, Dubai International Airport',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts around the clock; 8 hours a day or 48 a week under UAE labour law',
                'language' => 'English',
                // The UAE sets no statutory minimum wage and none of the three
                // DXB employers publishes ground staff pay, so there is no
                // figure this listing can honestly carry.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Passenger service, baggage, ramp and cargo roles at Dubai International Airport. UAE employers may not charge you recruitment fees at any stage.',
                'seo_keywords' => 'airport ground staff jobs dubai, dubai airport jobs, dnata ground handling jobs, ramp agent jobs dubai, emirates group careers',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated overview of the ground staff roles advertised at Dubai International Airport, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own careers site, and there is more than one employer at this airport.</p>
<p>These are on-site roles on rotating shifts, including nights, weekends and public holidays. UAE labour law sets 8 hours a day or 48 a week, a break after five consecutive hours, and 30 days of paid annual leave after a year of service.</p>
<p>No salary is stated. The UAE has no statutory minimum wage and none of the airport employers publishes ground staff pay, so any figure here would be a guess.</p>
<p><strong>Nobody may charge you to get this job.</strong> The UAE Government states that charging recruitment fees to prospective employees is illegal, and that the employer bears the cost of recruitment, travel and your residency permit.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Search for airport ground staff jobs in Dubai and you will be told to send your CV to Emirates. Most people who work at Dubai International Airport do not work for Emirates, and the ones who do usually did not apply through the page you were shown.</p>

<p>That is the first thing worth fixing. The second is bigger, and almost nothing written about these jobs mentions it: <strong>ground staff work is shift work, and UAE labour law carves shift workers out of the overtime premium that every guide quotes.</strong> If you take one thing from this page, take that.</p>

<p>Everything below is from the UAE Government's own portal and the employers' own careers sites. No salary estimate appears anywhere on this page, for a reason explained further down.</p>

<h2 id="who-employs-you">Who Actually Employs You at DXB</h2>

<p>Dubai International Airport is a workplace shared by several separate employers, and which one is hiring decides where you apply, what the job is called, and who answers if something goes wrong.</p>

<ul>
    <li><strong>The Emirates Group</strong> recruits through one careers site, <a href="https://www.emiratesgroupcareers.com/search-and-apply/" rel="nofollow noopener" target="_blank">emiratesgroupcareers.com</a>. Its <a href="https://www.emiratesgroupcareers.com/customer-services/" rel="nofollow noopener" target="_blank">customer services</a> and <a href="https://www.emiratesgroupcareers.com/airline-airport-operations/" rel="nofollow noopener" target="_blank">airline and airport operations</a> areas are where the passenger-facing and operational airport roles sit.</li>
    <li><strong>dnata</strong> runs its own job board at <a href="https://www.dnata.com/en/careers/" rel="nofollow noopener" target="_blank">dnata.com</a>, filtered by location, job category and <em>brand</em> &mdash; because dnata is several brands. Its service lines are listed on its own site as ground handling, cargo, premium services, private aviation, catering and retail, and travel. If you have seen a vacancy for a <em>marhaba</em> service agent and could not work out who the employer was, that is why: marhaba is one of the dnata brands.</li>
    <li><strong>Dubai Airports</strong> runs the airport itself and recruits separately at <a href="https://careers.dubaiairports.ae/en/search-and-apply/" rel="nofollow noopener" target="_blank">careers.dubaiairports.ae</a>. Terminal operations, airside services, facilities and technology roles are advertised here rather than by any airline.</li>
    <li><strong>Everyone else.</strong> An airport terminal also holds retail, food, cleaning, transport and other contracted operations, run by employers whose names never come up in a search for "Dubai airport jobs". They are real vacancies, and every rule in this guide applies to them in exactly the same way.</li>
</ul>

<p>So search by the job title rather than the airline: <em>airport services agent</em>, <em>passenger services agent</em>, <em>ramp agent</em>, <em>ground handling agent</em>, <em>cargo operations agent</em>, <em>baggage services agent</em>. And before you accept anything, read the employer name on the offer, not the airport name on the building.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/airport-ground-staff-jobs-dubai-terminal.jpg" alt="Ground staff marshalling an aircraft on the apron at a Dubai terminal gate" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Several separate employers operate at Dubai International Airport. The name on your contract is the one that matters.</figcaption>
</figure>

<h2 id="shift-work">You Are a Shift Worker, and the Law Treats That Differently</h2>

<p>Airports run around the clock, so almost every ground staff job is a rotating shift. Here is what UAE labour law actually sets, quoted from the Government portal.</p>

<p><strong>Hours.</strong> Article 17 of Federal Decree-Law No. 33 of 2021 "identifies the normal working hours for the private sector as 8 hours per day, or 48 hours per week". Your commute does not count towards that.</p>

<p><strong>Breaks.</strong> "The worker has the right to have one or more breaks, if he works five consecutive hours. These breaks must not be less than one hour. Breaks are not calculated within the working hours." That last sentence is the one to remember on a long turnaround: the break sits outside your eight hours, so a shift with a one-hour break is nine hours at the airport.</p>

<p><strong>Overtime, and the exception nobody mentions.</strong> Extra hours "shall not exceed two hours in one day". Work beyond normal hours earns normal pay on the basic salary "plus 25 per cent of that pay", and "it could increase to 50 per cent if overtime is done between 10 pm and 4 am". Then comes the sentence that matters most to you:</p>

<blockquote><p>"This rule does not apply on workers who work on basis of shifts."</p></blockquote>

<p><strong>Read that carefully before you build a budget around night pay.</strong> A great many guides to Gulf airport work quote the 50 per cent night premium as though a rostered night shift automatically earns it. On the Government's own wording, the overtime premium rule is expressly disapplied to shift workers, and a rostered ground staff roster is shift work. Whatever you are actually paid for nights will come from your contract and your employer's work regulations, not from that percentage. So ask for the shift allowance in writing, at offer stage, and do not assume the law will supply it.</p>

<p><strong>Rest days are different, and here the premium does hold.</strong> If you are required to work on your off day as set by the contract or the work regulations, you are entitled "to a substitute rest day, or to a pay equal to normal working hours' remuneration (which is based on basic salary) plus 50 per cent of that pay".</p>

<p><strong>If you work outside, the summer rule is real.</strong> Under Ministerial Resolution No. 44 of 2022, "all work performed directly under the sun and in open places shall not be allowed between the peak hours of 12.30 pm and 3 pm from 15 June to 15 September every year". Ramp, baggage loading and equipment work on the apron is exactly the work that ban exists for.</p>

<p><strong>Leave.</strong> "Employees are entitled to a fully paid annual leave of 30 days, if they have completed one year of service and if the period of service exceeds six months but is less than one year, the employee is entitled to 2 days of leave for each month of service."</p>

<h2 id="pay">What It Pays, and Why No Number Appears Here</h2>

<p>None of the airport employers publishes a salary for ground staff roles, and <strong>"there is no minimum salary stipulated in the UAE Labour Law"</strong> &mdash; only a general requirement that wages must be sufficient to meet an employee's basic needs. Any figure you have seen for Dubai ground staff pay came from a salary-estimate site guessing, and we will not repeat a guess.</p>

<p>What we can give you instead is the part of your pay that is guaranteed, and the rules that now govern whether it arrives.</p>

<p><strong>The Wage Protection System changed in 2026, and most guides have not caught up.</strong> Wages are now paid in accordance with <strong>Ministerial Resolution No. 340 of 2026</strong>. Under it, "salaries for the previous month are due on the first day of each Gregorian month", and employers "must transfer at least 85 per cent of the total wages due to their employees on time (where lawful deductions apply)". If a guide tells you wages must arrive within 15 days of the due date, it is describing the superseded 2022 resolution.</p>

<p>The resolution replaces that single deadline with an escalating timetable, which is worth knowing because it tells you what is already happening while you wait:</p>

<ul>
    <li><strong>From the due date</strong> &mdash; electronic monitoring of the establishment until payment is proven.</li>
    <li><strong>From the second day</strong> &mdash; notifications and alerts to the non-compliant employer.</li>
    <li><strong>On the 5th day</strong> &mdash; new work permits for that employer are suspended.</li>
    <li><strong>On the 11th day</strong> &mdash; an administrative fine, and reclassification into the third category, on a repeated violation within six months.</li>
    <li><strong>On the 16th day</strong> &mdash; "automatic registration of an individual or collective labour dispute for the affected workers". This step names <strong>transport and storage</strong> among the sectors it reaches, which is where airport ground handling sits.</li>
    <li><strong>On the 21st day</strong> &mdash; an executive instrument for payment, precautionary attachment against the establishment, and "imposing a travel ban on the person in charge of the establishment".</li>
</ul>

<p>Salaries reach you "through banks, exchange houses or financial institutions authorised by the Central Bank of the UAE". A cash envelope from a supervisor is not the Wage Protection System.</p>

<p><strong>End of service.</strong> After one year of continuous service you are entitled to gratuity: "21 days' salary for each year of work" for one to five years, then "30 days' salary for each year of work following the first 5 years", capped at two years' wage in total. It is calculated on the basic salary, so housing and transport allowances are excluded, and the employer must pay all outstanding wages and gratuity "within 14 days of the termination of the contract". Our <a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">Emirates cabin crew guide</a> works through how badly applicants tend to misjudge that calculation.</p>

<h2 id="medical">The Medical Is a Named List, and Two Results End the Application</h2>

<p>Every guide says "you will need a medical". Almost none says what is tested. Under Cabinet Resolution No. 5 of 2016, amending Cabinet Decree No. 7 of 2008, expatriates entering the UAE for work or residence undergo tests for <strong>HIV/AIDS, hepatitis B, leprosy, syphilis and tuberculosis</strong>.</p>

<p>Two of those are decisive. On the Government's own wording, for HIV/AIDS, "residency is denied or not renewed for positive cases", and for leprosy, "residency is denied or not renewed for positive cases". For drug-resistant TB the individual "will be treated in the UAE until recovery", after which residency may be renewed.</p>

<p>This is not written here to alarm anyone. It is written because people sell everything they own, pay an agent, fly to Dubai and fail a test they did not know existed. <strong>Find out where you stand at home, before you spend money.</strong></p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/airport-ground-staff-jobs-dubai-ramp.jpg" alt="Ground handling crew working an aircraft turnaround on the apron at sunset" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Ramp and baggage work is outdoor work. The midday summer ban between 12.30pm and 3pm exists for exactly these roles.</figcaption>
</figure>

<h2 id="visa">The Visa Sequence, in Order</h2>

<p>The employer applies for the work permit; you do not. The order matters because getting it wrong is what visit-visa job scams rely on.</p>

<ol>
    <li><strong>Your passport must be valid for at least six months</strong> before you can obtain an entry permit.</li>
    <li><strong>You sign the job offer in your home country.</strong> The employer "must sign the job offer electronically and send it to the worker in their home country either directly, through a recruitment agency, or via any designated entity, for review and approval by the worker".</li>
    <li><strong>The signed offer goes with the work permit application.</strong> "The signed job offer must be attached to the application for initial work permit approval... This approval allows the worker to enter the UAE."</li>
    <li><strong>You travel on an entry permit</strong>, which "remains valid for two months from the date of issue".</li>
    <li><strong>Within one month of arriving</strong> you complete the residency procedures &mdash; the medical, the security check, the Emirates ID &mdash; for a residency visa "which is valid for two years".</li>
    <li><strong>You sign the contract on arrival, and it is registered.</strong> "Upon arrival, both the employer and worker must sign the job offer, which is then officially registered with MoHRE as a legally binding employment contract", and it "must be submitted to MoHRE within 14 days of the employee's arrival in the UAE".</li>
</ol>

<p>One rule catches people out constantly: <strong>"The UAE law strictly prohibits working while holding a visit or tourist visa (whether for pay or without compensation). Violators, whether employees or employers, will face fines and legal accountability."</strong> If anyone tells you to come on a visit visa and start work while the paperwork catches up, they are asking you to break the law and take the fine.</p>

<h2 id="two-documents">Two Documents, and They Must Match</h2>

<p>This is the protection most worth understanding, because it is the one that fails quietly.</p>

<p>You sign a <strong>job offer</strong> at home and an <strong>employment contract</strong> in Dubai, and the UAE's own guidance to migrant workers is that "the terms and provisions of your employment contract must be consistent with the job offer you have signed in your country". It also tells you plainly to "maintain a copy of the job offer you have signed". Keep it. Photograph it. Email it to yourself. If the contract put in front of you on arrival has a smaller salary, a different job title or different hours, that mismatch is the whole point of the rule.</p>

<p><strong>You can demand it in a language you read.</strong> The job offer "must be provided in Arabic and English, as well as in a third language that the worker understands", chosen from nine: "Bengali, Chinese, Dari, Hindi, Malayalam, Nepalese, Sinhalese, Tamil and Urdu". If Urdu is your language, ask for the Urdu version. It is not a favour, it is the rule.</p>

<p>The UAE also publishes its "Know Your Rights" guide for migrant workers in Urdu, Hindi, Bengali, Malayalam, Chinese and English. Reading it before you fly costs nothing.</p>

<h2 id="fees">Nobody Can Charge You to Get This Job</h2>

<p>Read this section twice, because it is where the money is lost.</p>

<p><strong>"Charging recruitment fees to prospective employees is illegal in the UAE."</strong> The Government's message to workers is more specific still: "The costs of the recruitment and travel, as well as the expenses for obtaining your residency permit in the UAE shall be borne by the employer with whom you have agreed to conclude a contract." It adds that "the confiscation of workers' passports is prohibited and workers do not require their employer's permission to leave the country".</p>

<p>On the Pakistani side the law is blunter than most workers realise. Section 22 of the Emigration Ordinance 1979 makes it an offence for anyone who is <strong>not</strong> a licensed Overseas Employment Promoter to demand or receive any money for securing foreign employment, and an offence for a licensed promoter to charge "any fee in addition to the prescribed amount". Either way the punishment is the same: <strong>"imprisonment for term, which may extend to fourteen years, or with fine, or with both"</strong>. Forging emigration documents, or inducing someone to emigrate "by means of intoxication, coercion, fraud or wilful misrepresentation", carries the same fourteen years under section 18.</p>

<p>Two practical consequences:</p>

<ul>
    <li><strong>Check the licence before you hand over anything.</strong> Only a person licensed under section 12 may recruit you, and the Bureau of Emigration publishes the list of valid licences. A complaint against an unlicensed agent is referred to the Federal Investigation Agency.</li>
    <li><strong>Appear before the Protector of Emigrants.</strong> Section 15 requires it: before you emigrate you appear in person, together with the promoter who engaged you, and your name is registered. Skipping that step is skipping your own paper trail.</li>
</ul>

<p>We are not printing a maximum service charge figure here. That cap sits in the Emigration Rules rather than the Ordinance, and every official Bureau of Emigration page was unreachable while this guide was checked, so the number is not asserted on a secondary source. Our <a href="/blog/how-to-apply-for-dp-world-port-jobs-in-uae">DP World port jobs guide</a> breaks down the documented costs a licensed promoter may pass on, and what is refundable.</p>

<h2 id="scams">How the Airport Job Scam Actually Works</h2>

<p>Airport jobs are among the most impersonated in the Gulf, because the employer names are famous and the roles sound reachable. The pattern rarely varies:</p>

<ul>
    <li><strong>An offer you did not apply for.</strong> Real recruitment at these employers starts with an application on their own site.</li>
    <li><strong>A fee with a respectable name</strong> &mdash; visa processing, medical, uniform, training, "security deposit". All of them are the employer's cost by law.</li>
    <li><strong>An email address that is not the company's domain.</strong> A genuine Emirates Group or Dubai Airports message does not arrive from a free webmail account.</li>
    <li><strong>Pressure and a deadline.</strong> Genuine work permits run on government timetables, not on a countdown.</li>
    <li><strong>A logo instead of a licence.</strong> Anyone can copy a crest. Ask for the licence number and check it.</li>
</ul>

<p>If it has already gone wrong after you arrive, complaining is free and does not require a lawyer. MoHRE's Labour Claims and Advisory Call Centre is on the toll-free number <strong>80084</strong>, and "workers are exempt from paying litigation fees for claims less than AED 100,000". Where a settlement cannot be reached within 14 days the ministry refers the dispute to the competent court. Note one hard deadline: "no claim for any rights due will be heard after one year from the date of violation."</p>

<h2 id="apply">How to Apply</h2>

<ol>
    <li><strong>Decide which employer you are applying to</strong>, then use that employer's own site. The Emirates Group, dnata and Dubai Airports each run their own.</li>
    <li><strong>Search job titles, not "ground staff".</strong> Airport services agent, passenger services agent, ramp agent, ground handling agent, cargo operations agent, baggage services agent.</li>
    <li><strong>Put the shift reality in your CV.</strong> Nights, weekends and public holidays are the job. Prior shift experience in hospitality, retail, security or logistics is directly relevant and worth naming.</li>
    <li><strong>Evidence your English rather than asserting it</strong>, and any additional language you speak. An international hub values both.</li>
    <li><strong>Get the offer in writing before you resign anything.</strong> Then check that the contract you are asked to sign in Dubai matches it.</li>
</ol>

<p>Comparing markets? Our <a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">Etihad airport jobs guide</a> covers the same work in Abu Dhabi, where the employer is not the airline at all, and our <a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">Heathrow airport jobs guide</a> covers the five-year vetting that gates the equivalent UK roles.</p>

<h2>Frequently Asked Questions</h2>

<h3>Who actually employs airport ground staff at Dubai International Airport?</h3>
<p>Several separate employers. The Emirates Group recruits through its own careers site, dnata handles most ground handling, baggage, ramp and cargo work, Dubai Airports runs the airport itself and recruits separately, and retail, cleaning and security are contracted out again. Working at DXB does not mean working for Emirates.</p>

<h3>Do airport ground staff get extra pay for night shifts in the UAE?</h3>
<p>Not automatically. The 25 per cent overtime premium, and the 50 per cent for work between 10pm and 4am, are followed on the UAE Government's own page by the sentence "this rule does not apply on workers who work on basis of shifts". A rostered ground staff shift is shift work, so any night allowance has to come from your contract. Ask for it in writing.</p>

<h3>What is the salary for airport ground staff jobs in Dubai?</h3>
<p>No employer publishes one and the UAE Labour Law stipulates no minimum salary, only that wages must be sufficient to meet basic needs. Any figure circulating online is an estimate. What is guaranteed is 30 days of annual leave after a year, end of service gratuity, and payment through the Wage Protection System.</p>

<h3>Can a recruiter charge me a fee for a Dubai airport job?</h3>
<p>No. The UAE Government states that charging recruitment fees to prospective employees is illegal and that the employer bears recruitment, travel and residency costs. In Pakistan, anyone unlicensed who takes money for foreign employment faces up to fourteen years' imprisonment under the Emigration Ordinance 1979.</p>

<h3>What medical tests are required for a UAE work visa?</h3>
<p>Tests for HIV/AIDS, hepatitis B, leprosy, syphilis and tuberculosis, under Cabinet Resolution No. 5 of 2016. A positive HIV or leprosy result means residency is denied or not renewed, so it is worth knowing where you stand before you spend money on the move.</p>

<h3>Can I go to Dubai on a visit visa and start work while the paperwork is processed?</h3>
<p>No. UAE law strictly prohibits working on a visit or tourist visa, paid or unpaid, and both the worker and the employer face fines and legal accountability. You travel on an entry permit, valid two months, and complete residency within a month of arriving.</p>

<h3>Can I get the job offer in Urdu?</h3>
<p>Yes. The job offer must be provided in Arabic and English plus a third language the worker understands, and Urdu is one of the nine listed options alongside Bengali, Chinese, Dari, Hindi, Malayalam, Nepalese, Sinhalese and Tamil.</p>

<h3>Do freshers get hired for Dubai airport ground jobs?</h3>
<p>Some passenger-service and operational roles are open to candidates without aviation experience, while others require prior airline or airport work. Check each vacancy rather than each employer, and lead with shift, customer service, hospitality, retail or logistics experience.</p>

<h2>People Also Search For</h2>

<h3>Dubai airport jobs</h3>
<p>Spread across several employers at DXB. The Emirates Group, dnata and Dubai Airports each recruit through a different site.</p>

<h3>dnata ground handling jobs</h3>
<p>Where most baggage, ramp, cargo and equipment work at Dubai International Airport actually sits.</p>

<h3>Emirates Group careers</h3>
<p>The airline's own portal, covering customer services and airline and airport operations as separate career areas.</p>

<h3>Dubai Airports careers</h3>
<p>The airport operator, a separate employer from the airlines and handlers working in its terminals.</p>

<h3>Ramp agent jobs Dubai</h3>
<p>Outdoor apron work, covered by the summer midday ban between 12.30pm and 3pm from 15 June to 15 September.</p>

<h3>UAE labour law working hours</h3>
<p>8 hours a day or 48 a week, a break of at least an hour after five consecutive hours, and that break is not counted inside your hours.</p>

<h3>UAE work visa medical test</h3>
<p>HIV/AIDS, hepatitis B, leprosy, syphilis and tuberculosis. Two of those results end the residency application.</p>

<h3>MOHRE labour complaint number</h3>
<p>The Labour Claims and Advisory Call Centre is toll free on 80084, and claims under AED 100,000 are exempt from litigation fees.</p>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; the same work in Abu Dhabi, where the employer is not the airline.</li>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the cabin route instead of the ground route, and the gratuity rule applicants misjudge.</li>
    <li><a href="/blog/how-to-apply-for-dp-world-port-jobs-in-uae">How to Apply for DP World Port Jobs in UAE</a> &mdash; what a licensed Pakistani promoter may lawfully charge, and what is refundable.</li>
    <li><a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">How to Apply for Heathrow Airport Jobs in the UK</a> &mdash; the five-year vetting and counter terrorism check that decide the UK equivalent.</li>
    <li><a href="/blog/how-to-apply-for-air-canada-airport-jobs">How to Apply for Air Canada Airport Jobs</a> &mdash; an airport employer that does publish its ramp rate.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; other shift work in the Emirates with employer sponsorship.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; another sponsored route into the UAE labour market.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li>The Official Portal of the UAE Government (u.ae) &mdash; protection of workers' rights; working hours and overtime; payment of salaries and wages; types of leaves; end of service benefits; expatriates' employment in the private sector; preparing to work; labour disputes.</li>
    <li>Federal Decree-Law No. 33 of 2021 on the Regulation of Labour Relations in the Private Sector, as cited by the UAE Government portal; Ministerial Resolution No. 340 of 2026 on the Wage Protection System; Ministerial Resolution No. 44 of 2022 on the midday break; Cabinet Resolution No. 5 of 2016 on medical testing.</li>
    <li>Emigration Ordinance 1979 as amended to 2021, published by the Ministry of Overseas Pakistanis and Human Resource Development &mdash; sections 12, 15, 18 and 22.</li>
    <li>The Emirates Group, dnata and Dubai Airports careers sites, each checked for this guide.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. UAE labour regulations and Pakistani emigration rules change, and how a rule applies can turn on facts this page cannot know &mdash; confirm the current position with MoHRE and with the Bureau of Emigration and Overseas Employment before relying on any of it. JobGader is not a recruiter and charges nothing.</p>
HTML;
    }
}
