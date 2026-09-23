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
 * "How to Apply for DP World Port Jobs in UAE" — a guide for readers who can
 * genuinely reach this employer, so the value is not "can you go" but what the
 * terms actually are and which advertised routes are shut.
 *
 * Corrections to the draft (checked against dpworld.com, its Oracle
 * Recruiting Cloud board, u.ae, uaelegislation.gov.ae and the Emigration
 * Ordinance 1979, 23 September 2026):
 *
 * 1. The draft lists Ruwad, Bedaya, Ta'heel and the experienced-professionals
 *    pathway as general opportunities. Every one requires a Family Book and is
 *    open to UAE nationals only. dpworld.com/en/careers/uae 301-redirects to
 *    the Emiratisation page: there is no separate UAE careers page.
 *
 * 2. The draft never mentions that DP World hires into JAFZA. Free zone
 *    employees are sponsored by the zone authority, not the employer, and sit
 *    outside the ordinary MOHRE complaint route.
 *
 * 3. The draft has no pay or contract terms at all. There is no minimum wage
 *    for expatriates; the AED 4,000 figure that circulates is a family
 *    sponsorship threshold, and the AED 6,000 one is Emiratis only.
 *
 * 4. Its URLs are wrong in two places: /en/uae/careers redirects to the global
 *    careers page and is not a UAE source, and /careers/uae/emiratisation-
 *    initiative is a 404.
 *
 * 5. Headcount: DP World publishes three different numbers. The Annual Report
 *    2025 figure is more than 125,000 across more than 80 countries.
 *
 * 6. The Fahad Albanna profile is misread: he is senior manager of operations
 *    at Terminal 2 and participated in establishing Container Terminal 4 as a
 *    project; CT4 was not his post.
 *
 * 7. The draft names P&O Maritime Logistics, which was rebranded to Maritime
 *    Solutions in 2025.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DpWorldPortJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.dpworld.com/en/careers/vacancies';

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
        $title = 'How to Apply for DP World Port Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Every graduate and student programme DP World advertises in the UAE requires a Family Book. What is open to you is the ordinary vacancy board, where 19 UAE roles sit today. Here are the real terms, the gratuity formula and how to spot a fake offer.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-dp-world-port-jobs-in-uae.jpg',
                'tags' => 'dp world jobs, dp world careers uae, jebel ali port jobs, jafza jobs, uae gratuity calculation, uae labour law 2026, port jobs dubai, overseas employment promoter',
                'meta_title' => 'DP World Port Jobs in UAE: What Is Really Open to You',
                'meta_description' => 'DP World UAE jobs: why the graduate programmes are closed to non-Emiratis, what JAFZA employment means, the gratuity formula and how to check an offer is real.',
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
            ['name' => 'DP World, United Arab Emirates'],
            ['type' => 'Company', 'display_reference' => 'dp-world-uae']
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
                'position' => 'Port and Terminal Operations, DP World, United Arab Emirates',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; terminal and marine operations run shift patterns, and outdoor work stops between 12:30 and 15:00 from 15 June to 15 September',
                'language' => 'English',
                // DP World publishes no salary on any of its 19 live UAE
                // postings, and the UAE sets no minimum wage for expatriates,
                // so the guide explains the terms instead of inventing a band.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Terminal, marine, engineering, HSE and logistics roles with DP World across Dubai and Fujairah, for applicants without a UAE Family Book as well as Emiratis.',
                'seo_keywords' => 'dp world jobs uae, jebel ali port jobs, dp world careers dubai, jafza employment',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>DP World hires across terminal and marine operations, engineering, technology, HSE, logistics and commercial functions in the United Arab Emirates, supporting Jebel Ali Port, Jafza and its wider trade network.</p>

<h3>What the work involves</h3>
<p>Vessel berthing, loading and departure planning, container yard operations, crane and equipment work, maintenance and welding, safety and security, and the logistics and commercial functions behind them.</p>

<h3>Common requirements</h3>
<ul>
    <li>Requirements are set on each vacancy; DP World's Emiratisation pathway asks for a minimum of two years' experience but is open only to UAE nationals</li>
    <li>Safety discipline and the ability to follow controlled procedures around heavy equipment</li>
    <li>Availability for shift work, including nights and weekends in operational roles</li>
    <li>A right to work in the UAE; most DP World UAE roles are sponsored by the Jebel Ali Free Zone Authority rather than by DP World</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> employment terms come from UAE Federal Decree-Law 33 of 2021 and, for free zone staff, the zone authority's own rules; recruitment fee limits in Pakistan come from the Emigration Ordinance 1979 &mdash; not by JobGader. DP World states it will never charge or collect any fee or require money deposits from jobseekers at any stage. Applying is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>If you are reading this from Pakistan, the good news is that you can work in the UAE.</strong> There is no points test and no occupation list to clear, and a very large Pakistani workforce is already there. So this guide does not waste your time on whether you can go.</p>

<p>It answers the questions that actually decide things: which of DP World's advertised routes are closed to you, what your gratuity will really be worth, who your legal sponsor is, and how to tell a genuine offer from the fake ones that target Pakistani jobseekers by the thousand.</p>

<p>Checked on <strong>23 September 2026</strong> against DP World's live vacancy board, the UAE Government portal and the UAE employment law itself.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.dpworld.com/en/careers/vacancies" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#0a5c8f;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128674; Search DP World Vacancies &rarr;
    </a>
</div>

<h2>Every Graduate Programme Named in Other Guides Is Closed to You</h2>

<p>This is the correction that matters most, and the structure of DP World's own website gives it away.</p>

<p><strong>The address <code>dpworld.com/en/careers/uae</code> permanently redirects to <code>/en/careers/uae/emiratisation</code>.</strong> DP World's UAE careers page <em>is</em> its Emiratisation page. There is no separate UAE careers section for anyone else.</p>

<p>Everything that sits under that path requires a Family Book, and DP World's own wording says so in the requirements list of each one:</p>

<ul>
    <li><strong>Ruwad Graduate Programme (bachelor's)</strong> &mdash; "Family Book/Junsiya (For UAE Nationals Only)", plus a National Service Completion Letter for men. 12-month development plan, minimum GPA 2.8.</li>
    <li><strong>Ruwad (high school and diploma)</strong> &mdash; same nationality requirement, minimum 70 per cent. Note its plan is <strong>six months</strong>, not the 12 that gets quoted; the 12-month figure belongs to the bachelor's track.</li>
    <li><strong>Ta'heel Scholarship</strong> &mdash; "UAE Nationals (Family Book)", minimum GPA 3.0.</li>
    <li><strong>Bedaya Summer Training</strong> &mdash; "UAE Nationals (Family Book)".</li>
    <li><strong>Forsa Internship</strong> and <strong>Tumoohi</strong> &mdash; both the same.</li>
    <li><strong>Even the "Experienced Professionals" pathway</strong>, which guides present as the general route, sits on the Emiratisation path and opens with "The Emiratisation career opportunities at DP World include the following fields". Its "minimum of two years of experience" is real, but it is an Emirati pathway.</li>
</ul>

<p>There is a reason for all of this, and it is worth understanding rather than resenting. Under the <strong>Nafis</strong> programme, UAE private firms with 50 or more staff must raise Emirati employment in skilled roles by two percentage points a year to reach 10 per cent, with a penalty of <strong>AED 6,000 a month for every unfilled Emirati post</strong>. These programmes exist to meet that quota.</p>

<p><strong>So what is open to you?</strong> The ordinary global vacancy board, and only that. DP World's general internship page advertises a 12-week programme with no nationality bar, but its application button leads to a board carrying <strong>zero live internship postings</strong>, and across all 570 of DP World's global vacancies only two carry an early-career title &mdash; an apprentice in Australia and an intern in Mozambique. Neither is in the UAE.</p>

<p>The honest summary: <strong>DP World currently offers non-Emiratis no early-career entry point in the UAE.</strong> You need to arrive with experience.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-dp-world-port-jobs-in-uae-terminal.jpg" alt="Container terminal operations at a large port" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Nineteen DP World vacancies were live in the UAE, out of 570 worldwide.</figcaption>
</figure>

<h2>Your Sponsor Is Not DP World. It Is JAFZA.</h2>

<p>This changes your legal position and almost no guide mentions it.</p>

<p>DP World's registered office is <strong>JAFZA 17, Jebel Ali Free Zone</strong>, and its UAE vacancies carry that free zone address as their work location. Most DP World UAE hires are therefore <strong>free zone employees</strong>. The UAE Government's own portal states the consequence plainly:</p>

<p style="border-left:4px solid #0a5c8f;padding-left:16px;margin:20px 0;"><strong>"Those working in free zones are generally not governed by the UAE Labour Law. Each free zone authority has its own employment law... Moreover, free zone employees are sponsored by the respective free zone authority and not by their employer."</strong></p>

<p>Two practical effects. First, <strong>your residence visa is held by the zone authority</strong>, not by DP World. Second, and more importantly, <strong>the Ministry of Human Resources and Emiratisation complaint route does not apply to you</strong> &mdash; disputes go through the free zone's own channel instead. Any article that tells you "MOHRE will protect you" without this caveat is giving you advice you cannot use.</p>

<p>The free zone's rules must still be consistent with the federal Labour Law, so the substantive protections below remain your benchmark. But know which door you would knock on.</p>

<h2>What You Will Actually Be Paid, and What You Will Keep</h2>

<p><strong>There is no minimum wage for expatriate workers in the UAE.</strong> The Government portal states it outright: "There is no minimum salary stipulated in the UAE Labour Law." The law contains a power to set one that has never been used for a general floor.</p>

<p>Two figures circulate as a UAE minimum wage and neither is one:</p>

<ul>
    <li><strong>AED 4,000</strong> is the threshold for <strong>sponsoring your family</strong> to join you, or AED 3,000 plus accommodation. It is an immigration test, not a wage floor.</li>
    <li><strong>AED 6,000</strong> is a genuine minimum, in force since 1 January 2026 &mdash; <strong>for Emiratis in the private sector only</strong>.</li>
</ul>

<p>DP World publishes no salary on any of its 19 UAE vacancies; we checked every one through the board's own data. So treat any salary figure you read about these jobs as invented.</p>

<p><strong>The number that really matters is your gratuity</strong>, because it is a large part of Gulf pay and most guides get it wrong. Under Article 51:</p>

<ul>
    <li>Under one year of service &mdash; <strong>nothing</strong></li>
    <li>One to five years &mdash; <strong>21 days' basic pay for each year</strong></li>
    <li>Beyond five years &mdash; <strong>30 days' basic pay for each additional year</strong></li>
    <li><strong>Capped at two years' wages</strong> in total</li>
</ul>

<p><strong>It is calculated on basic salary only.</strong> Housing, transport, utilities and furniture allowances are excluded &mdash; and in Gulf packages those allowances are often a third or more of the total. When you compare two offers, compare the <em>basic</em>, not the headline. Gratuity must be paid within 14 days of the contract ending.</p>

<p>There is also a newer voluntary savings scheme in which the employer pays 5.83 per cent of basic monthly (or 8.33 per cent after five years) into an approved fund instead. Ask which system applies to you.</p>

<h2>The Contract Terms Worth Knowing Before You Sign</h2>

<p>These come from Federal Decree-Law 33 of 2021 as amended, and several of them are commonly reported wrongly.</p>

<p><strong>Contracts are fixed-term, and the three-year cap is gone.</strong> Unlimited contracts were abolished. Article 8 now places no maximum on the term. Be aware that one page on the UAE Government's own portal still says "not exceeding three years" &mdash; the statute governs, and the page is stale.</p>

<p><strong>Probation is a maximum of six months and cannot be extended.</strong> The employer terminates on 14 days' written notice. If you want to leave during probation for another UAE employer you give <strong>one month</strong>; if you are leaving the country you give <strong>14 days</strong>. And here is the part people get wrong: <strong>the recruitment cost is repaid by your new employer, not by you.</strong> The law puts that obligation on the incoming company.</p>

<p><strong>Notice after probation</strong> is between 30 and 90 days, and must be the same for both sides unless the difference favours you. If the employer terminated, you also get one unpaid day a week to job-hunt.</p>

<p><strong>Annual leave</strong> is 30 days a year after your first year, and two days per month between six and twelve months' service.</p>

<p><strong>Hours and overtime:</strong> eight hours a day or 48 a week, reduced by two hours a day during Ramadan. Overtime is capped at two extra hours a day and paid at basic plus 25 per cent &mdash; rising to <strong>basic plus 50 per cent for hours worked between 10pm and 4am</strong>, though shift workers are excluded from that uplift. Work on your rest day earns a substitute day or basic plus 50 per cent.</p>

<p><strong>The weekend is not Friday by law.</strong> The statute requires only "not less than one day" of paid rest a week, set by your contract or the employer's work regulations. The Saturday&ndash;Sunday change applied to federal government bodies, not the private sector.</p>

<p><strong>One rule specific to port work:</strong> outdoor work in direct sun or open places is banned between <strong>12:30 and 15:00 from 15 June to 15 September</strong>. On a container yard in a Dubai summer, that is not a technicality.</p>

<h2>Two Protections That Are Genuinely Yours</h2>

<p><strong>The Wages Protection System.</strong> Since Ministerial Resolution 340 of 2026, wages for the previous month fall due on the <strong>first day of each month</strong>, and employers must transfer at least <strong>85 per cent</strong> of total wages on time. If they do not, the consequences escalate on a published timetable: alerts from day two, new work permits suspended from day five, fines and reclassification from day eleven, automatic registration of a labour dispute from day sixteen, and by day twenty-one an executive instrument for payment, precautionary attachment, <strong>a travel ban on the person in charge of the establishment</strong>, and referral to the Public Prosecution. Any guide still saying employers have fifteen days to pay is quoting the old rule.</p>

<p><strong>Unemployment insurance is compulsory and it covers you.</strong> The scheme applies to all private-sector employees, not just Emiratis. It costs <strong>AED 5 a month</strong> if your basic salary is under AED 16,000, or AED 10 above that &mdash; and you pay it, not your employer. If you lose your job through no fault of your own you receive <strong>60 per cent of your subscription salary for up to three months</strong>, capped at AED 10,000 or AED 20,000 a month depending on your band. You need twelve consecutive months of subscription to qualify, you must claim within 30 days and be in the country, and there is a lifetime ceiling of twelve months of benefit. <strong>Not subscribing carries an AED 400 fine</strong>, so check it is being deducted.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-dp-world-port-jobs-in-uae-crane.jpg" alt="Ship-to-shore cranes working a container vessel" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Only one live vacancy carried a Jebel Ali badge, despite Jebel Ali being the flagship port.</figcaption>
</figure>

<h2>What Is Actually on the Board</h2>

<p>DP World had <strong>570 live vacancies worldwide and 19 in the UAE</strong>. That second number deserves attention, because the UAE is DP World's home market and yet it ranks <strong>tenth</strong> by vacancy count &mdash; behind India with 141, Germany 64, the United States 44, Turkey 43, Australia 36 and the United Kingdom 30.</p>

<p>Within the UAE: <strong>Dubai 14, unspecified UAE 4, Fujairah 1.</strong> And despite Jebel Ali being the flagship, <strong>only one vacancy carried a Jebel Ali badge</strong>. Do not plan around a large Jebel Ali intake that is not there.</p>

<p>Real titles live today: Global Manager for Project Management in Logistics (Dubai); Supervisor (Dubai); Administrator (UAE); Site Project Management Officer on a fixed-term contract (UAE); Project HSE Manager on a two-year contract (Fujairah); Manager Contract (Dubai); Credit Manager (Dubai); and <strong>Technician &ndash; Welding (Dubai)</strong>, described as carrying out welding on port plant and equipment from fabrication to structural repairs.</p>

<p><strong>That welding role is the clearest blue-collar entry point on the board</strong>, and along with Supervisor and Administrator it is one of only three genuinely junior roles open regardless of nationality. One further UAE listing, "Emirati Talent &ndash; Future Opportunities", is restricted on its face and has sat open since November 2024 &mdash; it is a pipeline post, not a vacancy.</p>

<p>Two things to correct while you are reading DP World's own material. Its headcount is published three different ways across its site; the <strong>Annual Report 2025 figure is more than 125,000 people across more than 80 countries, with 169 nationalities</strong>. And the marine brands were renamed in 2025: Unifeeder is now <strong>Shipping Solutions</strong>, P&amp;O Ferrymasters is <strong>Multimodal Solutions</strong>, and P&amp;O Maritime Logistics is <strong>Maritime Solutions</strong>. Guides still using the old names are out of date, though one stale DP World posting uses them too.</p>

<p>One genuinely encouraging line for Pakistani readers: DP World's 2026 capital budget of roughly three billion dollars is to be invested mainly in Jebel Ali, Drydocks World, Jafza <strong>and Karachi</strong>.</p>

<h2>How to Tell a Real Offer From a Fake One</h2>

<p>Pakistani jobseekers are targeted more heavily than almost any other group by fake Gulf job offers. This section is the most useful part of this page.</p>

<p><strong>Start with what DP World itself says</strong>, on its careers page:</p>

<p style="border-left:4px solid #0a5c8f;padding-left:16px;margin:20px 0;"><strong>"DP World, and any recruitment agencies authorised to represent us, will not charge or collect any fee, nor require any money deposits from jobseekers at any stage of the recruitment process. In addition, we would not send you an employment contract without engaging with you first, nor communicate with you from a publicly available email service such as Google, Hotmail etc."</strong></p>

<p><strong>Then know the UAE law.</strong> Article 6(4) prohibits an employer from charging you recruitment costs, directly or indirectly. The Government portal puts it plainly: <em>"Charging recruitment fees to prospective employees is illegal in the UAE. The confiscation of workers' passports is prohibited and workers do not require their employer's permission to leave the country."</em> Recruitment, travel and residency-permit costs are the employer's.</p>

<p><strong>Then know the Pakistani law, because this is where the money is taken from you.</strong></p>

<ul>
    <li>A licensed Overseas Employment Promoter may take <strong>Rs 15,000</strong> &mdash; and it is not a fee, it is a <strong>refundable deposit</strong>. It is released to the promoter only once the Protector of Emigrants certifies you actually took up the job; if the promoter fails to place you, <strong>the money comes back to you</strong>. That is your main protection and most workers do not know it exists.</li>
    <li>On top of that, only <strong>documented actual costs with receipts</strong> &mdash; ticketing, medical, work permit, levy, visa, documentation. Nothing else. The published total through an OEP is <strong>Rs 22,200</strong> (the Rs 15,000 deposit, Rs 4,000 OPF welfare fund, Rs 2,500 insurance, Rs 500 registration, Rs 200 OEC), or Rs 9,200 for direct employment.</li>
    <li><strong>Registration with the Protector of Emigrants before you leave is mandatory</strong>, and insurance of Rs 1 million for two years comes with it.</li>
    <li><strong>The penalties are severe on both sides.</strong> An unlicensed agent, or a licensed one charging above the prescribed amount, faces up to <strong>14 years</strong>. But note that a worker who emigrates outside the Ordinance faces up to <strong>five years</strong> &mdash; going around the system does not only risk your money.</li>
    <li>Promoters are explicitly barred from helping anyone use a <strong>visit, study or Umrah visa</strong> for employment. If that is the plan being offered to you, it is illegal for them to offer it.</li>
</ul>

<p><strong>Practical checks before you pay anyone anything:</strong> verify the promoter's licence on the Bureau of Emigration's published list of active OEPs; verify the job offer's reference number through the MOHRE enquiry service; and check the company exists on the UAE National Economic Register. Your signed job offer must match the contract you sign on arrival &mdash; keep a copy, because that match is enforceable. The offer must be given to you in Arabic, English and a third language you understand, and <strong>Urdu is on the approved list</strong>.</p>

<p>If something goes wrong: MOHRE's labour advisory line is 80084 in the UAE. In Pakistan, complaints go to the Bureau of Emigration, and cases against unlicensed agents go to the FIA's Anti-Human Trafficking wing.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Ignore the UAE careers page.</strong> It redirects to the Emiratisation section. Go to DP World's global vacancy search instead.</li>
    <li><strong>Search by function, not by "port".</strong> Terminal, marine, logistics, supply chain, engineering, HSE, security, technology, technician.</li>
    <li><strong>Apply through the careers site only.</strong> DP World states on its contact page that the recruitment team cannot process applications sent through the enquiry form.</li>
    <li><strong>Read the work location.</strong> A JAFZA address tells you the free zone will be your sponsor.</li>
    <li><strong>Check the basic salary, not the package.</strong> Your gratuity, and a large part of what you take home over years, depends on it.</li>
    <li><strong>Prepare operational examples</strong> &mdash; a safety concern you raised, a problem you solved under time pressure, a handover you coordinated across teams. Port work is judged on judgement, not qualifications alone.</li>
</ol>

<p>For neighbouring markets and routes, see our guides to <a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">logistics driver jobs in the UAE</a>, <a href="/blog/security-guard-jobs-in-uae">security guard jobs in UAE</a> and <a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">how to get a job in Saudi Arabia as a foreigner</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can a Pakistani apply for DP World's Ruwad graduate programme?</h3>
<p>No. Ruwad, Bedaya, Ta'heel, Forsa and Tumoohi all require a UAE Family Book, and so does the experienced-professionals pathway. DP World's UAE careers page redirects to its Emiratisation page, so all of these sit under a nationals-only section. Non-Emiratis must use the ordinary global vacancy board.</p>

<h3>Is there a minimum wage in the UAE for expatriates?</h3>
<p>No. The Government portal states there is no minimum salary stipulated in the UAE Labour Law. The AED 4,000 figure that circulates is the threshold for sponsoring your family, and the AED 6,000 minimum introduced in January 2026 applies to Emiratis in the private sector only.</p>

<h3>How is UAE gratuity calculated?</h3>
<p>Nothing under one year of service. From one to five years, 21 days' basic pay per year. Beyond five years, 30 days' basic pay per additional year. The total is capped at two years' wages, it is calculated on <strong>basic salary only</strong> with housing and transport allowances excluded, and it must be paid within 14 days of the contract ending.</p>

<h3>Who sponsors a DP World employee in the UAE?</h3>
<p>Usually the Jebel Ali Free Zone Authority rather than DP World. Its registered office is in JAFZA and its UAE vacancies carry that address. Free zone employees are sponsored by the zone authority, are generally outside the federal Labour Law's direct application, and use the free zone's dispute channel rather than MOHRE's.</p>

<h3>How many DP World jobs are open in the UAE?</h3>
<p>Nineteen when we checked, out of 570 worldwide, which puts the UAE tenth by country behind India, Germany, the United States, Turkey, Australia and the United Kingdom. The split was 14 in Dubai, four unspecified and one in Fujairah, with only one carrying a Jebel Ali badge.</p>

<h3>How much can a Pakistani recruitment agent legally charge for a Gulf job?</h3>
<p>Rs 15,000, and it is a refundable deposit rather than a fee &mdash; released to the promoter only once the Protector of Emigrants certifies you joined the job, and returned to you if they fail to place you. Beyond that, only documented actual costs with receipts. The published total through a licensed promoter is Rs 22,200. Charging more carries up to 14 years' imprisonment.</p>

<h3>Does the UAE private sector get a two-day weekend?</h3>
<p>Not by law. The statute requires at least one paid rest day a week, with the day set by your contract or the employer's work regulations. The Saturday and Sunday change in January 2022 applied to federal government entities, not to private employers.</p>

<h3>Am I covered by UAE unemployment insurance as an expatriate?</h3>
<p>Yes, and it is compulsory. It costs AED 5 a month if your basic salary is below AED 16,000 and AED 10 above, paid by you. The benefit is 60 per cent of your subscription salary for up to three months, capped at AED 10,000 or AED 20,000 monthly. You need twelve consecutive months of subscription, and there is an AED 400 fine for not subscribing.</p>

<h2>People Also Search For</h2>

<ul>
    <li>DP World Jebel Ali careers vacancies</li>
    <li>UAE gratuity calculator basic salary</li>
    <li>JAFZA free zone employment rules</li>
    <li>Ruwad programme eligibility DP World</li>
    <li>UAE labour law 2026 notice period</li>
    <li>BEOE overseas employment promoter fee</li>
    <li>MOHRE offer letter verification</li>
    <li>UAE unemployment insurance ILOE expatriates</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a></li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a></li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a></li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a></li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a></li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a></li>
</ul>
HTML;
    }
}
