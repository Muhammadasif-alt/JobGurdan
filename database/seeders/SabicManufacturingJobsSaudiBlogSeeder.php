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
 * "How to Apply for SABIC Manufacturing Jobs in Saudi Arabia" — a guide whose
 * decisive fact is a registration rule, not a vacancy: an engineer with under
 * five years' experience cannot register with the Saudi Council of Engineers
 * unless they are Saudi, and an unregistered engineer cannot be employed.
 *
 * Corrections to the draft (checked against sabic.com, jobs.sabic.com,
 * hrsd.gov.sa, spa.gov.sa, uqn.gov.sa and the Emigration Ordinance 1979,
 * 23 September 2026):
 *
 * 1. The draft presents TADARRUJ, TAMHEER, Cooperative Training and the
 *    scholarships as routes for its readers. All four are Saudi-nationals-only
 *    in SABIC's own words, and the page itself opens "SABIC aims to attract
 *    and nurture young Saudi talents".
 *
 * 2. The draft omits the Saudi Council of Engineers entirely. Registration is
 *    mandatory, and the Council states that registration "for those with less
 *    than five years of experience shall not be accepted, unless they are
 *    Saudi or Bedoon".
 *
 * 3. The draft omits Saudization. Engineering professions carry a 30 percent
 *    localisation requirement in force since 30 June 2026, and non-accredited
 *    engineers do not count toward it.
 *
 * 4. The draft has no pay or employment terms. There is no minimum wage for
 *    expatriates; the SAR 4,000 figure that circulates is a Nitaqat counting
 *    threshold for Saudi nationals.
 *
 * 5. The draft uses two United States postings as examples of Saudi work.
 *    Real Jubail and Yanbu engineering vacancies exist, so this is a defect
 *    rather than a workaround.
 *
 * 6. "More than 6,000 classes each year" is wrong. SABIC's Integrated Report
 *    2024 says over 3,000 classes to over 30,000 employees.
 *
 * 7. The affiliate list omits SABIC Gas and SABTANK, whose two branches each
 *    are what make the count fifteen.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SabicManufacturingJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://jobs.sabic.com/';

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
        $title = 'How to Apply for SABIC Manufacturing Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A foreign engineer with under five years of experience cannot register with the Saudi Council of Engineers, and an unregistered engineer cannot be employed. That one rule decides more than any CV advice, and no competing guide mentions it.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-sabic-manufacturing-jobs-in-saudi-arabia.jpg',
                'tags' => 'sabic jobs, sabic careers saudi arabia, jubail jobs, yanbu jobs, saudi council of engineers, saudization nitaqat, saudi end of service gratuity, petrochemical jobs saudi',
                'meta_title' => 'SABIC Manufacturing Jobs: The Five-Year Rule Explained',
                'meta_description' => 'SABIC jobs in Saudi Arabia: the Saudi Council of Engineers rule that blocks junior foreign engineers, which programmes are Saudi-only, and the real pay terms.',
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
            ['name' => 'SABIC, Jubail and Yanbu Operations'],
            ['type' => 'Company', 'display_reference' => 'sabic-saudi']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Process and Maintenance Engineer, SABIC, Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; plant operations run shift patterns, and hours reduce by two a day during Ramadan',
                'language' => 'English',
                // SABIC publishes no salary on any posting and Saudi law
                // requires only a private filing with the labour office, so
                // the guide explains the terms rather than inventing a band.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Process, control, instrument, planning and maintenance engineering roles with SABIC across its Jubail and Yanbu affiliates, for engineers who can meet the Saudi Council of Engineers requirements.',
                'seo_keywords' => 'sabic jobs saudi arabia, jubail petrochemical jobs, yanbu engineer jobs, saudi council of engineers registration',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>SABIC hires process, control system, instrument, planning, stationary and operations engineers across its petrochemical affiliates in Jubail and Yanbu, running large-scale chemical manufacturing plants.</p>

<h3>What the work involves</h3>
<p>Operating and monitoring plant processes, maintaining and troubleshooting rotating and static equipment, supporting instrumentation and control systems, inspection and reliability work, and process safety inside a regulated industrial environment.</p>

<h3>Common requirements</h3>
<ul>
    <li>A relevant engineering degree, with requirements set on each individual vacancy</li>
    <li>Registration with the Saudi Council of Engineers, which is mandatory to practise engineering in the Kingdom</li>
    <li>At least five years of experience for a foreign engineer, because the Council does not accept registration below that unless the applicant is Saudi or Bedoon</li>
    <li>Availability for shift work and for site-based work in Jubail or Yanbu</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> registration rules are set by the Saudi Council of Engineers, employment terms by the Saudi Labour Law, and recruitment fee limits in Pakistan by the Emigration Ordinance 1979 &mdash; not by JobGader. SABIC publishes no recruitment-fraud page, so verify every listing on jobs.sabic.com. Applying is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>One sentence decides whether this article is any use to you, and it is not on SABIC's website. It is on the Saudi Council of Engineers':</strong></p>

<p style="border-left:4px solid #00833e;padding-left:16px;margin:20px 0;"><strong>"Registration for those with less than five years of experience shall not be accepted, unless they are Saudi or Bedoon."</strong></p>

<p>Registration with the Council is mandatory to practise engineering in Saudi Arabia. So a foreign engineering graduate with three years behind them cannot register, and an engineer who cannot register cannot be employed as one. <strong>That single rule closes the entire "fresh graduate route into SABIC" that other guides describe.</strong></p>

<p>The rest of this page is written for the people it does not close: experienced engineers, and anyone planning the five years that get them there. Checked on <strong>23 September 2026</strong> against SABIC's own pages and the Saudi ministries.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobs.sabic.com/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00833e;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127981; Search SABIC Jobs &rarr;
    </a>
</div>

<h2>Every Training Programme SABIC Advertises Is Saudi-Only</h2>

<p>SABIC's students and fresh graduates page opens by saying it "aims to attract and nurture young Saudi talents", and its requirement lists make that literal. In SABIC's own words:</p>

<ul>
    <li><strong>TADARRUJ</strong>, the Basic Operation Training Program &mdash; <em>"The applicant has to be Saudi"</em>. Also a high school certificate, a medical examination, full availability, an interview and a level test, and <em>"Should not be more than 24 years of age"</em>, with fresh graduates prioritised. Its benefits &mdash; stipend, housing or housing allowance, transport, medical cover &mdash; are each qualified <em>"as per the company's policy"</em>, so they are not fixed entitlements.</li>
    <li><strong>TAMHEER</strong> &mdash; <em>"The program applies to Saudi graduates who meet the eligibility criteria set by the Human Resources Development Fund"</em>. Three to six months, minimum GPA 2.5 out of 4 or 3.5 out of 5, applications through the government TAQAT platform.</li>
    <li><strong>Cooperative Training</strong> &mdash; must be a graduation requirement, and you must be <strong>nominated by a national university or college</strong>. A student in Pakistan cannot be nominated. Minimum GPA 2.50 out of 4 or 3.50 out of 5.</li>
    <li><strong>Scholarships</strong>, both tracks &mdash; <em>"The applicant must be a Saudi national."</em></li>
</ul>

<p><strong>What is open to you is the experienced-hire vacancy board, and only that.</strong> SABIC publishes no nationality clause we could quote on its live postings, and its one general line &mdash; "We welcome people from more than 100 nationalities" &mdash; is marketing on the careers homepage, not an eligibility rule. Do not read it as one.</p>

<h2>The Saudi Council of Engineers, Step by Step</h2>

<p>This is the part no competing article covers, and it is the part that will actually take your time.</p>

<p>Registration is required by the Engineering Professions Practice Law and its executive regulations; no engineering work may be carried out without professional accreditation. There are three categories &mdash; Engineer, Specialist and Technician &mdash; and four professional degrees:</p>

<ul>
    <li><strong>Engineer</strong> &mdash; the base registration</li>
    <li><strong>Associate Engineer</strong> &mdash; a bachelor's degree from a programme meeting the Council's standards, valid membership, and a pass in the <strong>Fundamentals of Engineering (FE)</strong> exam</li>
    <li><strong>Professional Engineer</strong> &mdash; the above plus <strong>at least five years of documented experience</strong> in your specialty and a pass in the <strong>Practice of Engineering (PE)</strong> exam</li>
    <li><strong>Consultant Engineer</strong> &mdash; the Council publishes no requirements we could verify</li>
</ul>

<p>You may sit either the Council's own exam or the American NCEES equivalent. Renewal of the higher grades requires valid membership plus continuing professional development points.</p>

<p><strong>Two sequencing traps worth knowing before you plan around this.</strong></p>

<p>First, the documents the Council asks a Pakistani applicant for include a copy of your <strong>entry visa or Iqama</strong> and a recent employer letter <strong>certified by the Commercial Chamber</strong>. That means registration happens <em>after</em> you have an employer and are in the Kingdom &mdash; <strong>it is not a qualification you can obtain in Pakistan first.</strong> The historic sequence is a three-month window after arrival, with the work permit and Iqama issued once registration is complete electronically.</p>

<p>Second, your degree is judged against recognition <strong>in the country where you graduated</strong>, so an HEC-recognised, PEC-accredited Pakistani programme is the reference point. Certificates are checked by a third-party verification company, which takes time.</p>

<p><strong>We are not publishing a fee figure.</strong> The Council's website would not load from here, and the amounts circulating online could not be confirmed against it. Check the current schedule on saudieng.sa yourself &mdash; and note the official domain is <strong>saudieng.sa</strong>, not the "saudicouncil-eng" address some guides print, which resolves to nothing.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-sabic-manufacturing-jobs-in-saudi-arabia-plant.jpg" alt="A petrochemical plant at an industrial site" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Fifteen SABIC entries are listed across Jubail and Yanbu, counting branches.</figcaption>
</figure>

<h2>A Second Exam, Taken in Pakistan Before You Travel</h2>

<p>There is another gate, entirely separate from the Council, and it catches people out because it happens at home.</p>

<p>Saudi Arabia's Ministry of Human Resources runs a <strong>Professional Verification</strong> programme with its foreign affairs ministry and its technical training corporation: a mandatory skills and qualification test taken <strong>in the worker's own country, before travel</strong>, with the work visa for covered occupations linked to passing it. Over 209,500 workers have been accredited across more than 1,000 professions.</p>

<p><strong>Pakistan is one of the five countries covered</strong>, alongside India, Bangladesh, Sri Lanka and Egypt. So for a covered occupation the order is: offer, then the verification exam in Pakistan, then the visa, then arrival, then Council registration. Budget time for all four.</p>

<h2>Saudization Decides Whether They Can Hire You at All</h2>

<p>Your chances depend less on your CV than on the quota position of the specific company hiring, and that has tightened sharply.</p>

<p><strong>Engineering professions have carried a 30 per cent Saudization requirement since 30 June 2026</strong>, applying to any establishment with five or more workers in the covered professions. The decision names <strong>46 engineering professions</strong>, and they are exactly the ones a petrochemical plant hires: chemical, industrial, production, mechanical, electrical, automation, oil and gas, quality control, turbines, mechatronics. A separate, earlier decision put engineering <em>technical</em> professions on the same 30 per cent from July 2025.</p>

<p><strong>The part that affects you directly:</strong> for a Saudi engineer to count toward the quota they must earn at least SAR 8,000 a month and <strong>hold Council accreditation</strong> &mdash; the rule states that non-accredited engineers are not counted. Enforcement is automatic, checked against social insurance records and a live link to the Council. So the employer has its own hard reason to require your registration; it is not paperwork they can waive.</p>

<p>Above all of this sits <strong>Nitaqat</strong>, which grades the employer rather than the worker. A new three-year phase began in 2026, targeting 340,000 additional localised jobs. The bands are <strong>Red, Low Green, Medium Green, High Green and Platinum</strong> &mdash; note there is <strong>no Yellow band</strong>, despite most articles still listing one; the word does not appear once in the 2026 guide. The threshold is not a flat percentage either but a logarithmic formula that rises with headcount, so <strong>any article quoting "petrochemicals must be X per cent Saudi" has invented it.</strong></p>

<p>What it means in practice: a firm's band decides whether it can draw new visa quota and transfer sponsorship at all. Large, well-Saudized employers keep hiring foreigners. Firms sliding toward Red effectively cannot bring anyone new in, however much they want you.</p>

<h2>Pay, and the Costs Nobody Warns You About</h2>

<p><strong>There is no minimum wage for expatriates in Saudi Arabia.</strong> The Labour Law contains only a power to set one, never exercised for a general floor. The <strong>SAR 4,000 figure that circulates is a Nitaqat counting threshold for Saudi nationals</strong> &mdash; a Saudi on SAR 4,000 counts as one worker toward the quota, one on SAR 3,000 counts as half. It has nothing to do with what a foreign worker is paid. The SAR 3,000 minimum that also gets quoted applies to government agencies only.</p>

<p><strong>SABIC publishes no salary on any posting</strong>, and no Saudi rule requires it to; employers file wage details privately with the labour office within 15 days. Any SABIC salary figure you read is unsourceable.</p>

<p><strong>The end-of-service award is where the real money sits, and the resignation scale is what guides leave out.</strong> Under Article 84 you earn half a month's wage for each of the first five years and a full month's wage for each year after that, calculated on your last wage and pro-rated for part years. But Article 85 applies a sliding scale <strong>if you resign</strong>:</p>

<ul>
    <li>Under 2 years &mdash; <strong>nothing at all</strong></li>
    <li>2 to 5 years &mdash; <strong>one third</strong> of the award</li>
    <li>Over 5 and under 10 years &mdash; <strong>two thirds</strong></li>
    <li>10 years or more &mdash; <strong>the full award</strong></li>
</ul>

<p>If the employer ends the contract you get the full Article 84 award regardless. You also get the full award if you leave through force majeure beyond your control. Final settlement is due within one week if the employer ended it, two weeks if you did. <strong>The February 2025 amendments did not touch any of this</strong>, despite widespread claims that they did.</p>

<p>Those amendments did change other things worth knowing. Notice on an open-ended contract is now <strong>asymmetric</strong>: you give 30 days, but <strong>the employer still owes you 60</strong> if you are paid monthly &mdash; previously both sides owed 60. Probation is now a maximum of <strong>180 days in total</strong>, and both parties may terminate during it. A new resignation procedure treats your resignation as accepted after 30 days of silence, lets the employer defer for up to 60 days with written reasons, and lets you withdraw within 7 days. And a non-Saudi contract with no stated term now defaults to <strong>one year from the day you actually start</strong>, rather than being tied to your work permit as it used to be.</p>

<p><strong>Two levies, and only one of them touches you.</strong></p>

<p>The <strong>work permit levy</strong> &mdash; SAR 700 or 800 a month per foreign worker depending on the Saudi-to-foreign ratio &mdash; is the employer's, and it is illegal to pass it to you: making a worker bear costs the employer must bear is a listed violation carrying a fine multiplied by the number of workers affected. In SABIC's case it does not arise at all: <strong>on 17 December 2025 the Cabinet cancelled the levy for licensed industrial establishments</strong>, which SABIC is. Note the wording changed from the State <em>bearing</em> the levy for a fixed period, as it did from October 2019 to December 2025, to outright cancellation with no end date.</p>

<p><strong>The dependant fee is yours, and it is the number that will surprise you.</strong> Since July 2020 it has been <strong>SAR 400 per dependant per month</strong>, and the passports authority's own wording makes the charge "legally due on the expatriate worker". It is collected in advance when you issue or renew your Iqama, and on exit-and-return or final-exit visas. <strong>A wife and two children is SAR 1,200 a month &mdash; SAR 14,400 a year, out of your pocket.</strong> No abolition has been announced, though we could not reach a 2026-dated official page restating the figure, so treat it as current and verify at renewal.</p>

<p>Beyond that, the employer bears recruitment, Iqama, work permit, profession change, exit and re-entry costs, and your ticket home at the end.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-sabic-manufacturing-jobs-in-saudi-arabia-control.jpg" alt="Engineers monitoring plant systems in a control room" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">The job titles SABIC actually advertises are narrow and specific: process, control system, instrument, planning.</figcaption>
</figure>

<h2>Where the Jobs Are, and What They Are Called</h2>

<p>SABIC's headquarters are in Riyadh, but manufacturing sits in two industrial cities. Its careers page lists <strong>fifteen entries across Jubail and Yanbu</strong> &mdash; and it is fifteen only if you count branches, which is why the number confuses people.</p>

<p><strong>Jubail (11):</strong> Petrokemya, KEMYA, Ibn Zahr, SAMAC, SHARQ, United, SABIC Agri-Nutrients, Saudi Kayan, Al-Razi, plus SABIC Gas and SABTANK branches.<br>
<strong>Yanbu (4):</strong> YANPET, YANSAB, plus SABIC Gas and SABTANK branches.</p>

<p>Most guides list the nine or eleven named companies and omit SABIC Gas and SABTANK, then still claim fifteen.</p>

<p>Practical point that catches applicants out: <strong>the employer name on your contract may be the affiliate, not "SABIC"</strong>. Read the company and location fields carefully, because that name is who employs you.</p>

<p>The job titles SABIC uses are short and specific. Real Saudi postings we found: <strong>Engineer Process Engineering, Engineer Control System, Engineer Process, Engineer Process Control, Engineer Planning, Engineer Instrument, Engineer Stationary</strong> in Jubail, and <strong>Engineer Operation</strong> in Yanbu. Search those exact phrases rather than "manufacturing".</p>

<p>Two honest caveats. <strong>We could not get a live vacancy count.</strong> SABIC's board is a client-side application with an empty sitemap and no public search feed, so nobody &mdash; including the guides quoting numbers &mdash; can give you a defensible figure. And the specific postings above have since closed, which is the usual fate of any job link.</p>

<p><strong>One thing to be suspicious of:</strong> guides on this subject illustrate "Saudi manufacturing work" with two <em>United States</em> SABIC postings, an inspection engineer role and a manufacturing and EHSS role. Real Jubail and Yanbu engineering vacancies plainly exist, so there was no need to borrow American ones. If a guide does that, check what else it did not check.</p>

<p>While you are here: the widely repeated claim that SABIC delivers "more than 6,000 classes and online courses each year" is wrong. Its own Integrated Report says <strong>over 3,000 classes to over 30,000 employees</strong>, with over 200,000 total views.</p>

<h2>Protecting Yourself From a Fake Offer</h2>

<p>This matters more for Saudi than almost any destination, because the volume of fraudulent Gulf offers aimed at Pakistani workers is enormous.</p>

<p><strong>Start with an uncomfortable fact: SABIC publishes no recruitment-fraud page.</strong> We checked its careers pages, its job board and the obvious URLs; there is nothing. So unlike DP World or Microsoft, there is no official SABIC statement you can hold up against a suspicious offer. The only genuine sources of SABIC listings are <strong>sabic.com/en/careers</strong> and <strong>jobs.sabic.com</strong>. Anything else is somebody else's page.</p>

<p><strong>Do not use Musaned to check a SABIC contract.</strong> Guides recommend it; it is the wrong portal. Musaned handles <strong>domestic and support workers only</strong>, so an engineer's contract will never appear there. The correct tool is <strong>Qiwa</strong>, where your employer files the contract and you can approve, reject or propose an amendment from your own account, after which it becomes enforceable. But Qiwa is tied to your residency identity, so <strong>do not expect to verify a contract from Pakistan before you travel</strong> &mdash; that is a claim other guides make and it does not hold.</p>

<p><strong>On the Pakistani side</strong>, the Emigration Ordinance 1979 is what protects you. Emigration is lawful only through its process, which requires a letter of appointment, work permit or employment visa, or selection through a licensed Overseas Employment Promoter. The penalties run both ways: up to <strong>14 years</strong> for an unlicensed agent, or a licensed one charging above the prescribed amount, or for forging emigration documents &mdash; but also up to <strong>five years for the worker</strong> who emigrates outside the Ordinance. Going around the system is not a shortcut.</p>

<p>Verify any promoter's licence on the Bureau of Emigration's published list of active OEPs before you pay anything, and insist on a receipt for every rupee.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Count your years first.</strong> Under five, and the Council will not register you, so the engineering route is closed until you have them. Use the time deliberately &mdash; documented experience in your specialty is exactly what the Professional grade asks for.</li>
    <li><strong>Sit the FE exam.</strong> The Council's own or NCEES. It is the gate to Associate, and PE is the gate to Professional.</li>
    <li><strong>Search the real titles</strong> on jobs.sabic.com: Engineer Process, Engineer Control System, Engineer Instrument, Engineer Planning, Engineer Stationary, Engineer Operation. Add Jubail or Yanbu.</li>
    <li><strong>Expect the Professional Verification exam in Pakistan</strong> once you have an offer, and budget time for it.</li>
    <li><strong>Read the employer name.</strong> A contract from Petrokemya or YANSAB is a SABIC affiliate contract, which is normal &mdash; but know who you are signing with.</li>
    <li><strong>Do the dependant arithmetic before you accept.</strong> SAR 400 per person per month is a real cut in what reaches your family.</li>
    <li><strong>Get the contract onto Qiwa</strong> after arrival and read it against the offer you signed. That is your leverage.</li>
</ol>

<p>For neighbouring routes, see our guides to <a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">how to get a job in Saudi Arabia as a foreigner</a>, <a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">Aramco engineering jobs in Saudi Arabia</a> and <a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">construction jobs in Saudi Arabia with visa sponsorship</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can a fresh engineering graduate get a job at SABIC?</h3>
<p>Not as a foreign engineer. The Saudi Council of Engineers states that registration "for those with less than five years of experience shall not be accepted, unless they are Saudi or Bedoon", registration is mandatory to practise engineering in the Kingdom, and an unregistered engineer cannot be counted or employed as one. SABIC's own graduate programmes are all restricted to Saudi nationals.</p>

<h3>Is TADARRUJ open to Pakistani applicants?</h3>
<p>No. SABIC's requirement list begins "The applicant has to be Saudi". The same applies to TAMHEER, which runs through the government's Human Resources Development Fund, to Cooperative Training, which requires nomination by a national university, and to both scholarship tracks.</p>

<h3>Is there a minimum wage for foreign workers in Saudi Arabia?</h3>
<p>No. The Labour Law contains only an unexercised power to set one. The SAR 4,000 figure that circulates is a Nitaqat counting threshold for Saudi nationals, not a wage floor, and the SAR 3,000 minimum applies to government agencies only.</p>

<h3>How is the Saudi end-of-service award calculated if I resign?</h3>
<p>On a sliding scale. Nothing under two years; one third of the award between two and five years; two thirds between five and ten; the full award at ten years or more. If the employer ends the contract instead, you receive the full award &mdash; half a month's wage per year for the first five years and a full month per year thereafter, on your last wage.</p>

<h3>Does SABIC pay the Saudi expat levy?</h3>
<p>It does not arise. The levy of SAR 700 or 800 a month per foreign worker is the employer's, and passing it to the worker is a listed labour violation. In any case the Cabinet cancelled it for licensed industrial establishments on 17 December 2025, and SABIC is one.</p>

<h3>What is the Saudi dependant fee and who pays it?</h3>
<p>SAR 400 per dependant per month, and the worker pays it, not the employer. It has been at that level since July 2020 and is collected in advance at Iqama issue or renewal and on exit-and-return or final-exit visas. A spouse and two children costs SAR 1,200 a month, or SAR 14,400 a year.</p>

<h3>Where are SABIC's manufacturing jobs located?</h3>
<p>Jubail and Yanbu. The careers page lists fifteen entries across the two cities, counting branches: eleven in Jubail including Petrokemya, KEMYA, Ibn Zahr, SAMAC, SHARQ, United, SABIC Agri-Nutrients, Saudi Kayan and Al-Razi, and four in Yanbu including YANPET and YANSAB. Riyadh is the headquarters and carries corporate rather than plant roles.</p>

<h3>How do I check a Saudi job offer is genuine?</h3>
<p>Through Qiwa, where the employer files the contract and you can approve, reject or propose an amendment. Do not use Musaned, which covers domestic workers only. Note that Qiwa is tied to your residency identity, so a pre-departure check is not something you can rely on. In Pakistan, verify the promoter's licence on the Bureau of Emigration's active list before paying anything.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Saudi Council of Engineers registration requirements</li>
    <li>SABIC Jubail engineer vacancies</li>
    <li>Saudi end of service benefits resignation calculator</li>
    <li>Saudization engineering 30 percent 2026</li>
    <li>Saudi dependant fee SAR 400</li>
    <li>TADARRUJ program eligibility</li>
    <li>Qiwa contract authentication expat</li>
    <li>Professional verification exam Pakistan Saudi</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a></li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a></li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a></li>
    <li><a href="/blog/how-to-apply-for-dp-world-port-jobs-in-uae">How to Apply for DP World Port Jobs in UAE</a></li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a></li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a></li>
</ul>
HTML;
    }
}
