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
 * "How to Apply for NEOM Construction Jobs in Saudi Arabia" — a guide whose
 * central fact is one no other NEOM article will tell you: the official job
 * board is empty. Every query against NEOM's own careers API returns zero.
 *
 * Corrections to the draft (checked against neom.com, careers.neom.com and its
 * Eightfold jobs API, NEOM's newsroom and PIF reporting, 22 September 2026):
 *
 * 1. The draft says the careers platform "currently displays roles including
 *    Project Manager" and "lists Senior Architect among its available job
 *    examples". Both are false. careers.neom.com returns count=0 for every
 *    query tried, including project manager, architect, civil and engineer.
 *    Those titles are the job board software's placeholder text.
 *
 * 2. The draft gives no URL at all. The portal is careers.neom.com/careers,
 *    and neom.com's own careers page does not link to it, which is why people
 *    never find it.
 *
 * 3. The draft omits NEOM's restructuring entirely. The Line is reported
 *    halted until after 2030, PIF booked an 8 billion dollar gigaproject
 *    write-down, and staff have been cut. A "thousands of jobs, apply now"
 *    framing is the opposite of the truth.
 *
 * 4. The Oxagon data centre is misattributed. HUMAIN and DataVolt announced
 *    it on 31 August 2026, not NEOM, and 360MW is the first phase of a
 *    planned 1.5GW campus, not the whole campus.
 *
 * 5. The draft implies NEOM sponsors visas. NEOM's posting boilerplate says
 *    the opposite in tone: offers are conditional on the candidate being able
 *    to obtain a visa. NEOM promises nothing about sponsoring one.
 *
 * 6. The accommodation and 100-nationalities claims check out verbatim and
 *    are kept, with their source named.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NeomConstructionJobsSaudiArabiaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.neom.com/careers';

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
        $title = 'How to Apply for NEOM Construction Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'NEOM job board is empty right now. Zero vacancies, checked against its own API. Here is the real portal link, why the board is empty, and who is actually hiring for NEOM work instead.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-neom-construction-jobs-in-saudi-arabia.jpg',
                'tags' => 'neom jobs, neom careers, neom construction jobs, saudi arabia construction jobs, oxagon jobs, neom recruitment, saudi work visa, neom the line',
                'meta_title' => 'NEOM Construction Jobs: How to Apply (2026)',
                'meta_description' => 'NEOM construction jobs in Saudi Arabia: the real careers portal link, how many vacancies are actually open, and what NEOM will and will not do about your visa.',
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
            ['name' => 'NEOM, Saudi Arabia'],
            ['type' => 'Company', 'display_reference' => 'neom-saudi-arabia']
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
                'position' => 'NEOM Construction and Engineering, Tabuk Province, Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Site-based, 8 hours a day or 48 hours a week under Saudi labour law',
                'language' => 'English',
                // NEOM publishes no pay for any role, and its job board is
                // empty, so there is not even a live advert to read a figure
                // from. Any NEOM salary table online is an estimate site.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Construction, engineering and project roles at NEOM in Saudi Arabia. Check the official portal before applying, and never pay a recruitment fee.',
                'seo_keywords' => 'neom jobs, neom careers, neom construction jobs, oxagon jobs, saudi arabia construction jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the construction and engineering roles associated with NEOM in Saudi Arabia, not a single vacancy and not a job advertised by JobGader. Applications are made on NEOM's own careers portal. These are on-site roles in Tabuk Province, in the north-west of the Kingdom.</p>

<h3>Read this before you apply</h3>
<p>NEOM's official careers portal was showing no open vacancies when this listing was last checked. The portal is live and working, but the requisition list is empty. Check it yourself before spending time on an application, and treat any site claiming to list hundreds of current NEOM jobs with suspicion.</p>

<h3>What the work involves</h3>
<ul>
    <li>Civil, structural, mechanical and electrical engineering on infrastructure and industrial projects.</li>
    <li>Construction management, site supervision, planning, cost control and project controls.</li>
    <li>Health and safety, quality assurance, procurement and contracts.</li>
    <li>NEOM's own posting boilerplate states that non office-based roles involve outdoor activities and that candidates should be prepared to work outdoors and on construction sites.</li>
</ul>

<h3>Pay</h3>
<p>NEOM publishes no salary for any role, and with an empty job board there is not even a live advert to read a figure from. Saudi Arabia sets no statutory minimum wage for expatriate workers. Any NEOM salary table you have seen came from an estimate site, not from NEOM.</p>

<h3>Accommodation</h3>
<p>NEOM states that employees have private accommodation within one of its secure gated communities, located near their workplace, with recreational amenities. The benefits an individual receives still depend on the contract they are offered.</p>

<h3>Visas and fees</h3>
<p>NEOM states that all offers are subject to the candidate being able to successfully obtain a work visa to enter and work in the Kingdom of Saudi Arabia. NEOM also states that its recruiters, including appointed third-party agents, will never ask candidates to make or facilitate any bank payments or fees at any stage of the recruitment process.</p>

<p>Pay, shifts, eligibility and visa rules are set by NEOM, the Saudi Ministry of Human Resources and Social Development and Saudi labour law &mdash; not by JobGader. Confirm the requirements on the live posting, and verify any offer through the official NEOM portal before you travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Let us start with the thing every other NEOM jobs article leaves out.</p>

<p><strong>NEOM's official careers portal currently has zero open vacancies.</strong></p>

<p>Not few. Zero. We queried NEOM's own job-board API directly &mdash; the same one the page itself calls &mdash; with no filters, then with "engineer", "construction", "project manager" and "architect". Every single query returned a count of zero and an empty results list. Every filter facet came back empty too: no sectors, no locations, no skills. That is what an empty requisition table looks like.</p>

<p>If you have been sending CVs into NEOM for weeks and hearing nothing, this is probably why. Here is the honest picture, and what you can actually do with it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-neom-construction-jobs-in-saudi-arabia-site.jpg" alt="Construction site work in progress on a large infrastructure project in the Saudi Arabian desert" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">NEOM tells applicants that non office-based roles involve outdoor activities and that candidates should be prepared to work outdoors and on construction sites.</figcaption>
</figure>

<h2>Where Is the Real NEOM Careers Portal?</h2>

<p>This is the link, and it is worth bookmarking because it is genuinely hard to find:</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.neom.com/careers" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Open the Official NEOM Careers Portal &rarr;</a>
</p>

<p>Now here is the part that explains a lot of wasted effort. <strong>NEOM's main website does not link to its own job board.</strong> If you go to neom.com and click careers, you land on a page called "Work at NEOM" that talks about culture, communities and benefits &mdash; and contains no link to any vacancy list anywhere on it. The only outbound links are social media.</p>

<p>So people search, land on the brochure page, find nothing to apply to, and end up on a scam site that promises them a list. Use the careers.neom.com address above and nothing else.</p>

<p>NEOM confirms that address itself, inside its own anti-fraud notice, which tells candidates to "verify that the job posting is legitimate by using the official NEOM career portal at careers.NEOM.COM".</p>

<h2>Why Is the Job Board Empty?</h2>

<p>Because NEOM is contracting, not expanding. This is the context that changes how you should spend your time.</p>

<p>NEOM has confirmed a leadership change on its own newsroom. On 11 May 2025 it announced the appointment of Eng. Aiman Al-Mudaifer as board member, Managing Director and Chief Executive Officer, noting he had served as Acting CEO since November 2024.</p>

<p>NEOM's own careers page now describes the organisation as one <strong>"transitioning from project to enterprise"</strong>. That is a careful phrase, and it is doing a lot of work.</p>

<p>On the financial side, the Public Investment Fund recorded an <strong>8 billion dollar write-down</strong> on its gigaprojects at the end of 2024, its first on-record impairment of this kind, with gigaproject investment falling to a smaller share of the fund's assets.</p>

<p>And in May 2026, Semafor reported that NEOM had delayed further work on The Line &mdash; the 170-kilometre linear city &mdash; until at least after 2030, with the 2030 population target cut to around 100,000 from an original 1.5 million, and Red Sea tourism destinations postponed. <strong>Those reports cite unnamed sources and NEOM has not confirmed them, so treat them as journalism rather than established fact.</strong> We will not pretend otherwise. But they match what the empty job board is telling you.</p>

<h2>Is Anything at NEOM Still Being Built?</h2>

<p>Yes, and this is the one genuinely positive part of the picture. <strong>Oxagon is the exception.</strong></p>

<p>On 31 August 2026 an AI data centre project at Oxagon moved into construction. The announcement was made by <strong>HUMAIN and DataVolt, not by NEOM</strong> &mdash; NEOM is the location, not the developer. The release states they are developing 100MW of a 360MW AI-ready data centre at Oxagon, with the first 100MW anticipated to be available in 2028, and that the 360MW is the first phase of a wider planned 1.5GW campus.</p>

<p>If you have seen a guide saying NEOM announced a "planned 360 MW campus", that is wrong twice over: wrong company, and 360MW is one phase of something much larger.</p>

<p>There is no headcount figure in that release. Do not let anyone convert it into a jobs number for you.</p>

<h2>Who Is Actually Hiring for NEOM Work?</h2>

<p>The contractors. This is the practical answer, and it is the one worth acting on.</p>

<p>NEOM does not build NEOM with its own payroll. Engineering and construction contractors win the packages and hire the site teams. Bechtel, for example, runs NEOM-related roles on its own careers board. <strong>That is the contractor hiring, not NEOM</strong>, and the employment relationship, pay, contract and visa all sit with that contractor.</p>

<p>So a realistic search looks like this:</p>

<ul>
    <li>Check careers.neom.com weekly for direct NEOM roles, and register for its talent network so you are in the system when requisitions reopen.</li>
    <li>Search the careers sites of the major international engineering and construction contractors directly, filtering for Saudi Arabia or Tabuk.</li>
    <li>Look at the wider Saudi construction market rather than NEOM alone, because that is where the live vacancies are.</li>
</ul>

<h2>What Does NEOM Actually Say About Pay and Benefits?</h2>

<p>On pay, nothing. NEOM publishes no salary for any role, and with an empty board there is not even a live advert to read a figure from. Saudi Arabia sets no statutory minimum wage for expatriate workers. Every "NEOM engineer salary" table online is an estimate site guessing, and we are not going to repeat a guess.</p>

<p>On accommodation, NEOM is specific, and this part checks out word for word:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">What NEOM publishes</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">The exact position</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Accommodation</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"private accommodation within one of our secure gated communities &mdash; conveniently located near your workplace"</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Workforce</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"We are proud to employ people from more than 100 countries and diverse professional backgrounds."</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Development</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"development programs that range from internships to coaching for senior staff"</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Salary</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Nothing at all, for any role, at any level.</td>
        </tr>
    </tbody>
</table>

<p>That "internships to coaching for senior staff" clause is the <em>entire</em> extent of what NEOM says about early careers. <strong>There is no named NEOM graduate scheme, no apprenticeship programme and no published intake date.</strong> If a site tells you about "the NEOM Graduate Programme" with application deadlines, it invented it.</p>

<h2>Does NEOM Sponsor Your Visa?</h2>

<p>NEOM does not say that it does. Read its own wording carefully, because the difference matters:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Additionally, all offers are subject to the candidate being able to successfully obtain a work visa to enter and work in the Kingdom of Saudi Arabia."</p>

<p><strong>That is a condition placed on you, not a promise made to you.</strong> NEOM publishes no statement anywhere about sponsoring visas, paying visa costs, funding relocation or providing flights. The accommodation quote above is the closest thing it offers, and that describes where you live once you are there, not how you get there.</p>

<p>In practice, Saudi law leaves you no choice about who does this work. <strong>Article 33 of the Saudi Labour Law states that a non-Saudi may not engage in any work except after obtaining a work permit from the Ministry</strong>, and that permit is issued to the establishment, not to you. You cannot apply for your own Saudi work permit. An employer has to.</p>

<p>So ask the question directly before you accept anything: who applies, and who pays?</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-neom-construction-jobs-in-saudi-arabia-oxagon.jpg" alt="Industrial and port infrastructure development of the kind under construction at Oxagon on the Red Sea coast" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Oxagon is the one part of NEOM still attracting new investment, driven by port and data-centre construction.</figcaption>
</figure>

<h2>Nobody Can Charge You to Get a NEOM Job</h2>

<p>This is the strongest and most useful thing NEOM publishes, and it appears only on the careers portal, not on the main site. Read it once and remember it:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"NEOM recruiters, including our appointed third-party agents, will never ask candidates to make or facilitate any bank payments or fees at any stage of the recruitment process. NEOM will never ask you for personal passwords, log-in details or any other private information related to any other platform, private or public. All of our recruitment conversations will be initiated and managed exclusively through NEOM.COM emails or official channels."</p>

<p>Combine that with the fact that the job board is empty and you have a simple fraud test. <strong>Anyone offering you a NEOM construction job right now, for a fee, is lying to you</strong> &mdash; there is no vacancy for them to be offering.</p>

<p>Saudi law backs this up, and it is worth knowing the article number. <strong>Article 40 of the Saudi Labour Law puts the cost of recruiting a non-Saudi worker on the employer</strong>, along with the Iqama and work-permit fees, profession-change fees, exit and re-entry visas, and your return ticket home at the end of the contract. One honest caveat, because we would rather you knew: Article 40 assigns those costs to the employer. It does not, in itself, make it a crime for someone to ask you for money, and it binds the Saudi employer rather than an agent operating in your own country.</p>

<p>NEOM also discloses that it uses artificial intelligence in its recruitment process to assist efficiency, while stating that all final hiring decisions are made by human recruiters.</p>

<h2>So What Should You Actually Do?</h2>

<ol>
    <li>Bookmark careers.neom.com/careers and check it weekly. Register for the talent network so your profile is already there when roles reopen.</li>
    <li>Stop treating NEOM as a hiring campaign. It is an employer with a closed board right now.</li>
    <li>Put your real effort into the contractors and into the wider Saudi construction market.</li>
    <li>Refuse every request for money, at every stage, from anyone.</li>
    <li>If you do get an offer, verify it against a posting on the official portal before you resign from anything or travel anywhere.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How many jobs does NEOM have open right now?</h3>
<p>None. NEOM's official careers portal returned zero results for every search we ran, including engineer, construction, project manager, architect and an unfiltered search. The portal works; it is simply empty.</p>

<h3>What is the official NEOM careers website?</h3>
<p>careers.neom.com/careers. NEOM's anti-fraud notice names that address itself. Note that neom.com does not link to it from anywhere on its careers page.</p>

<h3>Does NEOM hire civil engineers and project managers?</h3>
<p>Those disciplines are relevant to NEOM's work, but no civil engineering, project management or architecture vacancy is listed on the portal at present. Guides claiming otherwise are quoting the job board software's placeholder examples.</p>

<h3>Has NEOM been cancelled?</h3>
<p>No. The zone is operating and Oxagon is still attracting investment. But work on The Line is reported delayed until after 2030, PIF has written down gigaproject value, and NEOM describes itself as transitioning from project to enterprise. Hiring has contracted sharply.</p>

<h3>Does NEOM provide accommodation?</h3>
<p>NEOM states that employees have private accommodation within secure gated communities near their workplace, with recreational amenities. What an individual actually receives depends on their contract.</p>

<h3>Does NEOM sponsor work visas for foreigners?</h3>
<p>NEOM makes no such promise. Its published wording is that offers are conditional on the candidate being able to obtain a Saudi work visa. Ask the employer directly who applies and who pays before you accept.</p>

<h3>Is there a NEOM graduate programme?</h3>
<p>Not one that NEOM names or publishes. Its only statement is that development programmes range from internships to coaching for senior staff. Any site advertising a named NEOM graduate scheme with deadlines has made it up.</p>

<h3>Can Pakistani and Indian engineers apply to NEOM?</h3>
<p>NEOM says it employs people from more than 100 countries, so nationality is not a stated barrier. The barrier right now is that there is nothing open to apply for, plus the requirement that you be able to obtain a Saudi work visa.</p>

<h2>People Also Search For</h2>

<h3>NEOM careers login</h3>
<p>The portal at careers.neom.com/careers, built on the Eightfold platform. You can create a profile and join the talent network even while no vacancies are listed.</p>

<h3>NEOM salary for engineers</h3>
<p>NEOM publishes none. Every figure circulating online comes from a salary-estimate site, not from NEOM or from a live NEOM advert.</p>

<h3>Oxagon jobs</h3>
<p>Oxagon is NEOM's industrial city on the Red Sea coast and the only part still drawing major new investment, led by port and data-centre construction.</p>

<h3>NEOM The Line latest news 2026</h3>
<p>Reporting in May 2026 said further work on The Line was delayed until at least after 2030. NEOM has not confirmed this publicly.</p>

<h3>NEOM contractors list</h3>
<p>Major international engineering and construction firms hold NEOM packages and hire their own site teams. Applying to them is applying to them, not to NEOM.</p>

<h3>NEOM job scam</h3>
<p>NEOM states its recruiters will never ask for bank payments or fees at any stage. With an empty job board, any paid NEOM job offer right now is fraudulent.</p>

<h3>Saudi Arabia work visa for engineers</h3>
<p>Article 33 of the Saudi Labour Law requires a work permit issued to the establishment. A worker cannot apply for his own Saudi work permit.</p>

<h3>NEOM CEO</h3>
<p>Eng. Aiman Al-Mudaifer, appointed Managing Director and Chief Executive Officer on 11 May 2025, having been Acting CEO since November 2024.</p>

<h2>More Job Guides</h2>

<p>Saudi Arabia has employers that are actually hiring right now. These cover them:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; the Kingdom's largest engineering employer, with a live board.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the wider market beyond the gigaprojects.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; how hiring and sponsorship work in the Kingdom.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; another guide where the employer everyone searches for is the wrong one.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; an employer that does publish what it pays.</li>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; and which of the three Siemens companies actually hires you.</li>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; an employer that publishes real salary bands on its adverts.</li>
    <li><a href="/blog/how-to-apply-for-pdo-engineering-jobs-in-oman">How to Apply for PDO Engineering Jobs in Oman</a> &mdash; the government portal that replaced PetroJobs.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using NEOM's own careers pages and newsroom, the careers.neom.com job API, PIF financial reporting, named press coverage, and the Saudi Labour Law as published by the Ministry of Human Resources and Social Development, checked on 22 September 2026. Vacancy counts, programmes and immigration rules change. Always check the live portal and the official government source before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
