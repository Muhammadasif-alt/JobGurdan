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
 * "How to Apply for Etihad Airport Jobs in UAE" — the guide whose answer is
 * that the reader is applying to the wrong company. Ground handling at Zayed
 * International Airport left Etihad for ADQ and now trades as Velora, so the
 * ramp, baggage and check-in jobs people search for under Etihad's name are
 * advertised by a different employer entirely.
 *
 * Corrections to the draft (checked against etihad.com, careers.etihad.com,
 * Etihad's SmartRecruiters postings, velora.ae, careers.velora.ae, u.ae and
 * the official text of Federal Decree-Law 33 of 2021, 22 September 2026):
 *
 * 1. The draft's premise is wrong. Etihad Airport Services was included in
 *    the December 2021 transfer to ADQ and rebranded as Velora on 5 November
 *    2025. Velora runs ground services at Zayed International Airport, so
 *    this guide leads with careers.velora.ae rather than Etihad.
 *
 * 2. Etihad advertises no baggage, ramp, loader or check-in vacancy at all.
 *    Its one Abu Dhabi airport role is an Airport Duty Officer marked UAE
 *    Nationals Only, so the draft's "depending on the vacancy" hedging is
 *    replaced with the structural answer.
 *
 * 3. The draft treats the International category as a route into Abu Dhabi
 *    ground work. It is outstation management, at Manama, Asmara, Harare,
 *    Male and Zurich.
 *
 * 4. "Tax-free salary, travel benefits and accommodation in Abu Dhabi" is not
 *    a sentence Etihad writes. Etihad offers cabin crew a tax-free salary and
 *    "a modern, fully serviced home"; the word accommodation, and the word
 *    free attached to housing, appear nowhere on that page.
 *
 * 5. The draft gives no URLs. etihad.com/en/careers is dead, and so is
 *    abudhabiairport.ae. Every link published here was checked.
 *
 * 6. The draft omits the one fact that protects this audience most: Article
 *    6(4) of Federal Decree-Law 33 of 2021 forbids an employer from charging
 *    a worker recruitment costs, directly or indirectly.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EtihadAirportJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.velora.ae/search/';

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
        $title = 'How to Apply for Etihad Airport Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Etihad no longer handles its own baggage, ramp and check-in work at Abu Dhabi. That went to a company called Velora, which is where those jobs are advertised. Here is the whole picture, with the UAE law that protects you.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-etihad-airport-jobs-in-uae.jpg',
                'tags' => 'etihad airport jobs, etihad careers, velora careers, abu dhabi airport jobs, ground handling jobs uae, zayed international airport jobs, uae work visa, mohre recruitment fees',
                'meta_title' => 'Etihad Airport Jobs in UAE: How to Apply',
                'meta_description' => 'Etihad airport jobs in the UAE: why ground handling at Abu Dhabi is now run by Velora, where the real vacancies are, and the UAE law that bans recruitment fees.',
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
            ['name' => 'Velora, formerly Etihad Airport Services'],
            ['type' => 'Company', 'display_reference' => 'velora-abu-dhabi']
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
                'position' => 'Airport Ground Services, Velora, Zayed International Airport',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work, 8 hours a day or 48 hours a week under UAE labour law',
                'language' => 'English',
                // Neither Velora nor Etihad publishes pay for any airport or
                // ground role, and the UAE sets no statutory minimum wage, so
                // there is no figure this listing can honestly carry.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Ground handling, cargo and security roles at Zayed International Airport, advertised by Velora. UAE employers may not charge you recruitment fees.',
                'seo_keywords' => 'velora careers, abu dhabi airport jobs, ground handling jobs uae, etihad airport jobs, zayed international airport jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the ground services roles advertised at Zayed International Airport in Abu Dhabi, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own careers site. These are in-person roles at a physical airport.</p>

<h3>Who the employer is</h3>
<p>Ground handling at Abu Dhabi is not run by Etihad Airways. Etihad Airport Services was included in the December 2021 transfer of Etihad's support businesses to ADQ, Abu Dhabi's sovereign investor, and on 5 November 2025 it rebranded as Velora. Velora states it employs more than 5,000 people and that ground services at Zayed International Airport handled 28 million travellers in 2024.</p>

<h3>What the work involves</h3>
<ul>
    <li>Velora lists travellers services, baggage handling, aircraft cleaning and travellers assistance under its ground services.</li>
    <li>The IATA Ground Handling Partner directory lists Velora at Zayed International Airport for passenger services, ramp services, load control and flight operations, and cargo and mail warehouse services.</li>
</ul>

<h3>Pay</h3>
<p>Neither Velora nor Etihad publishes a salary figure for any airport or ground role. The UAE sets no statutory minimum wage; the government states only that wages must be sufficient to meet an employee's basic needs. Any hourly or monthly figure you read for these jobs came from a salary-estimate site, not from the employer.</p>

<h3>What UAE law gives you instead</h3>
<ul>
    <li>End-of-service gratuity after one year: 21 days' wage per year for the first five years, 30 days a year after that.</li>
    <li>30 days of paid annual leave after one year of service.</li>
    <li>8 hours a day or 48 hours a week, with overtime at the basic wage plus 25 per cent, or plus 50 per cent between 10pm and 4am.</li>
    <li>Wages paid through the Wage Protection System, which employers registered with the Ministry of Human Resources and Emiratisation must use.</li>
    <li>No outdoor work between 12:30pm and 3pm from 15 June to 15 September, which matters on a ramp in Abu Dhabi.</li>
</ul>

<h3>Recruitment fees</h3>
<p>Article 6(4) of Federal Decree-Law No. 33 of 2021 states that the employer is prohibited from charging the worker the fees and costs of recruitment and employment, or collecting them from him, whether directly or indirectly. The UAE Government puts it plainly: if a company or agency asks you for money to process a visa or a medical test, it is not a genuine company.</p>

<p>Pay, shifts, eligibility and visa rules are set by the employer, the Ministry of Human Resources and Emiratisation and UAE labour law &mdash; not by JobGader. Confirm the requirements on the live posting, and verify any job offer with MOHRE before you travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you have been searching "Etihad airport jobs" hoping to load bags, work a check-in desk or drive a tug at Abu Dhabi, there is something you need to know before you spend another evening on it.</p>

<p><strong>Etihad Airways does not employ the ground staff at Abu Dhabi airport any more.</strong></p>

<p>That work was moved out of the airline. Today it belongs to a company most jobseekers have never heard of, and that company advertises its own vacancies, on its own site. Searching Etihad for these jobs is searching the wrong company.</p>

<p>Here is what actually happened, where the jobs really are, and the UAE law that protects you on the way in.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-etihad-airport-jobs-in-uae-terminal.jpg" alt="Airport ground crew working beside an aircraft on the apron at Zayed International Airport in Abu Dhabi" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Ground services at Zayed International Airport handled 28 million travellers in 2024, according to Velora, the company that now runs them.</figcaption>
</figure>

<h2>Who Runs Ground Handling at Abu Dhabi Now?</h2>

<p>A company called <strong>Velora</strong>.</p>

<p>The trail is short and entirely on the record. In December 2021 Etihad announced it was transferring a group of its support businesses to ADQ, Abu Dhabi's sovereign investor. The list named <strong>Etihad Airport Services Ground</strong> alongside Etihad Engineering, Etihad Airport Services Cargo and others. On <strong>5 November 2025, Etihad Airport Services rebranded as Velora</strong>, bringing ground handling, cargo and logistics, and security services under one name.</p>

<p>Velora says it has been providing aviation services since 1982, is part of ADQ, employs more than 5,000 people, and that its ground services at Zayed International Airport managed 28 million travellers in 2024. IATA's Ground Handling Partner directory lists Velora at the airport for passenger services, ramp services, load control and flight operations.</p>

<p>So the job exists. It is just not an Etihad job.</p>

<h2>Where Do I Apply?</h2>

<p>Velora runs its own careers site. This is the link that matters:</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.velora.ae/search/" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Search Velora Jobs at Abu Dhabi Airport &rarr;</a>
</p>

<p>Velora groups its vacancies into categories that do not expire, so these are safe to bookmark:</p>

<ul>
    <li><a href="https://careers.velora.ae/go/Ground-Service/4197322/" target="_blank" rel="noopener nofollow">Ground Service</a> &mdash; the ramp, baggage and passenger-handling roles.</li>
    <li><a href="https://careers.velora.ae/go/Cargo-Services/4197522/" target="_blank" rel="noopener nofollow">Cargo Services</a></li>
    <li><a href="https://careers.velora.ae/go/Security-Services/4197422/" target="_blank" rel="noopener nofollow">Security Services</a></li>
</ul>

<p>Now the honest part, because you deserve it before you get your hopes up. <strong>Velora had only two vacancies open when we checked, and neither was a ramp or baggage role.</strong> Velora is the right door. The door is not always open. Bookmark the category pages, check them weekly, and do not let anyone tell you there is a shortcut.</p>

<h2>What About Etihad Itself?</h2>

<p>Etihad Airways is still a large employer, and its careers site is at careers.etihad.com, with vacancies listed on SmartRecruiters. But for the job you are searching for, the numbers are stark.</p>

<p>Across Etihad's entire live posting list worldwide, <strong>there is not one baggage handler, ramp agent, loader or check-in agent vacancy.</strong> The only airport-operations role in Abu Dhabi is an Airport Duty Officer, and it is marked <strong>UAE Nationals Only</strong>, requiring a valid family book and a bachelor's degree completed in the last three years.</p>

<p>Two more things worth clearing up, because guides get them wrong:</p>

<p><strong>The "International" category is not a route into Abu Dhabi ground work.</strong> It is outstation management &mdash; Airport Manager and Duty Supervisor roles at overseas stations such as Manama, Asmara, Harare, Male and Zurich. Those are management jobs abroad, not ramp jobs at home.</p>

<p><strong>Etihad's airport trainee programmes are for UAE Nationals.</strong> Etihad's Airports and Ground Operations Programmes do rotate trainees through check-in, boarding, transfer services, baggage operations and arrivals at Zayed International Airport, combined with classroom learning. But Etihad describes them as part of its Emiratisation strategy, creating career pathways for UAE Nationals. If you are not Emirati, this route is closed.</p>

<h2>What Does It Pay?</h2>

<p>We are going to disappoint you here, and then give you something better.</p>

<p><strong>Neither Velora nor Etihad publishes a salary for any airport or ground role.</strong> Etihad does publish pay, but only for cabin crew and pilots. Of its Abu Dhabi postings, not one airport role carries a figure. Any number you have seen for Abu Dhabi ground jobs came from a salary-estimate site guessing, and we will not repeat a guess.</p>

<p>The UAE also has <strong>no statutory minimum wage</strong>. The government states there is no minimum salary in the UAE Labour Law, only a general requirement that wages must be sufficient to meet an employee's basic needs.</p>

<p>So instead of a fake number, here is what the law actually guarantees you, which is worth more than an estimate:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Entitlement</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">What UAE law sets</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">End-of-service gratuity</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">After one year: 21 days' wage per year for the first five years, then 30 days per year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Annual leave</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">30 days paid, after one year of service</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Working hours</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">8 hours a day or 48 a week; overtime at basic wage +25%, or +50% between 10pm and 4am</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Getting paid</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Through the Wage Protection System, which registered employers must use</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Summer outdoor work</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Banned 12:30pm to 3pm, 15 June to 15 September</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Going home</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">The employer bears the cost of returning you to where you were recruited from</td>
        </tr>
    </tbody>
</table>

<h2>Nobody Can Charge You to Get This Job</h2>

<p>This is the most important paragraph on the page, so read it twice.</p>

<p><strong>Article 6(4) of Federal Decree-Law No. 33 of 2021 states that the employer is prohibited from charging the worker the fees and costs of recruitment and employment, or collecting them from him, whether directly or indirectly.</strong> Agencies are bound by the same article. The UAE Government says it in plainer words still: do not pay, and if the hiring company or the agency asks you for money to process the visa or the medical test, it is not a genuine company.</p>

<p>The Government adds that confiscating a worker's passport is prohibited, and that you do not need your employer's permission to leave the country.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-etihad-airport-jobs-in-uae-crew.jpg" alt="Airline ground and cabin staff in uniform inside an Abu Dhabi airport terminal" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Etihad publishes a recruitment fraud warning stating it never asks applicants for payment of any kind. Velora publishes no such warning, so lean on UAE law instead.</figcaption>
</figure>

<h2>How the Visa Actually Works</h2>

<p>One line clears up most of the confusion: <strong>the employer applies for the work permit, not you.</strong> The UAE Government states that an individual legally authorised to represent the company must submit the work permit application.</p>

<ol>
    <li><strong>You sign the job offer at home first.</strong> The employer signs it electronically and sends it to you in your home country, in Arabic and English plus a third language you understand.</li>
    <li><strong>The employer applies to MOHRE</strong> with your signed offer attached, for initial work permit approval. A permit to recruit a worker from outside the UAE is valid for two years.</li>
    <li><strong>You enter on an entry permit,</strong> valid two months from issue.</li>
    <li><strong>Within one month of arriving</strong> you complete the residency procedures: medical fitness test, security check, Emirates ID and a two-year residence visa.</li>
    <li><strong>Your contract is registered with MOHRE</strong> within 14 days of your arrival.</li>
</ol>

<p>Two rules that catch people out. A residency visa <strong>cannot</strong> be processed while you are outside the UAE; you must enter on an entry permit first. And working on a visit or tourist visa is strictly prohibited, paid or unpaid, with fines for the worker and the employer both.</p>

<h2>Check the Offer Before You Travel</h2>

<p>The UAE Government publishes a way to verify a job offer, and it costs nothing:</p>

<ul>
    <li>A genuine offer letter is <strong>issued by MOHRE</strong> and signed by the authorised manager.</li>
    <li>You can <strong>verify the offer by its reference number</strong> through the application-status enquiry service on the MOHRE website.</li>
    <li>You can confirm the company legally exists by searching its name in the National Economic Register.</li>
    <li>MOHRE for overseas jobseekers: <strong>009716-802-7666</strong>, or ask@mohre.gov.ae.</li>
    <li>Labour Claims and Advisory Call Centre, toll free inside the UAE: <strong>80084</strong>.</li>
</ul>

<p>And if it goes wrong after you arrive, complaining is free: labour claims under AED 100,000 are exempt from judicial fees.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does Etihad hire baggage handlers at Abu Dhabi?</h3>
<p>No. Ground handling moved to ADQ and now trades as Velora. Etihad advertises no baggage, ramp or check-in vacancy anywhere in its live postings.</p>

<h3>What is Velora?</h3>
<p>The renamed Etihad Airport Services. It rebranded on 5 November 2025, is part of ADQ, employs more than 5,000 people and runs ground services, cargo and security at Zayed International Airport.</p>

<h3>Can Pakistanis or Indians apply for Abu Dhabi airport jobs?</h3>
<p>For Velora roles, yes, subject to the requirements on each posting. Etihad's one Abu Dhabi airport role is marked UAE Nationals Only, and its airport trainee programmes are part of its Emiratisation strategy.</p>

<h3>What do Abu Dhabi airport ground jobs pay?</h3>
<p>Neither employer publishes a figure, and the UAE has no statutory minimum wage. Treat any number you see elsewhere as an estimate rather than an offer.</p>

<h3>Do I have to pay a recruitment fee?</h3>
<p>No, and it is illegal to ask. Article 6(4) of Federal Decree-Law 33 of 2021 prohibits an employer from charging you recruitment costs directly or indirectly, and agencies are bound by the same article.</p>

<h3>Does Etihad provide free accommodation?</h3>
<p>Etihad offers cabin crew a tax-free salary and what it calls a modern, fully serviced home in Abu Dhabi. It does not use the word free about housing, and this applies to cabin crew, not airport roles.</p>

<h3>Can I work in the UAE on a visit visa while I look?</h3>
<p>No. UAE law strictly prohibits working on a visit or tourist visa, paid or unpaid, and both worker and employer face penalties.</p>

<h3>How do I check a UAE job offer is real?</h3>
<p>A genuine offer is issued by MOHRE. Verify it by reference number through the application-status service on the MOHRE website, and check the company in the National Economic Register.</p>

<h2>People Also Search For</h2>

<h3>Velora careers Abu Dhabi</h3>
<p>The careers site of the company that runs ground handling at Zayed International Airport, with categories for Ground Service, Cargo Services and Security Services.</p>

<h3>Zayed International Airport jobs</h3>
<p>Three separate employers operate there: Velora for ground handling, Abu Dhabi Airports for the airport itself, and Etihad Airways for airline roles.</p>

<h3>Etihad careers official site</h3>
<p>careers.etihad.com, with vacancies listed on SmartRecruiters. The older etihad.com careers address no longer resolves.</p>

<h3>Etihad cabin crew requirements</h3>
<p>Minimum age 21 at application, minimum height 163 cm, fluent written and spoken English, and Grade 12 or equivalent. These are cabin crew rules, not airport rules.</p>

<h3>UAE minimum wage 2026</h3>
<p>There is none. UAE Labour Law sets no minimum salary, requiring only that wages meet an employee's basic needs.</p>

<h3>UAE end of service gratuity calculation</h3>
<p>After one year of service, 21 days' basic wage for each of the first five years and 30 days a year after that, capped at two years' wage.</p>

<h3>MOHRE complaint number</h3>
<p>80084 toll free inside the UAE for labour claims, or 009716-802-7666 for jobseekers contacting from outside the country.</p>

<h3>UAE recruitment fee law</h3>
<p>Article 6(4) of Federal Decree-Law No. 33 of 2021, which forbids an employer from charging or collecting recruitment costs from a worker, directly or indirectly.</p>

<h2>More Job Guides</h2>

<p>Looking at the Gulf more broadly, or at the airline side rather than the ground? These cover it:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the other big UAE airline, and what it really asks for.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; ground work you can actually get hired into.</li>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; a smaller Gulf carrier with clearer entry rules.</li>
    <li><a href="/blog/how-to-apply-for-kuwait-airways-cabin-crew-jobs">How to Apply for Kuwait Airways Cabin Crew Jobs</a> &mdash; and the age limits that actually apply.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; an employer that does publish what it pays.</li>
    <li><a href="/blog/how-to-apply-for-neom-construction-jobs-in-saudi-arabia">How to Apply for NEOM Construction Jobs in Saudi Arabia</a> &mdash; the famous employer whose job board is currently empty.</li>
    <li><a href="/blog/how-to-apply-for-pdo-engineering-jobs-in-oman">How to Apply for PDO Engineering Jobs in Oman</a> &mdash; the government portal that replaced PetroJobs.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Etihad's own newsroom and careers pages, Velora's corporate and careers sites, the IATA Ground Handling Partner directory, the UAE Government portal u.ae, and the official text of Federal Decree-Law No. 33 of 2021, checked on 22 September 2026. Vacancies, programme intakes and immigration rules change. Always check the live posting and the official government source before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
