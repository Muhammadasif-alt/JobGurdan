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
 * "How to Apply for Kuwait Airways Cabin Crew Jobs" — a guide whose whole
 * reason to exist is that the careers portal every other guide says is
 * missing is not missing. It is on a subdomain rather than the main site.
 *
 * Corrections to the draft (checked against kuwaitairways.com,
 * careers.kuwaitairways.com, manpower.gov.kw and archived copies of the
 * airline's own postings, September 2026):
 *
 * 1. The draft states Kuwait Airways has no working careers page. The main
 *    site path /en/careers does return 404, but the homepage footer links
 *    careers.kuwaitairways.com, which is a live Zoho Recruit portal on the
 *    airline's own subdomain. That is the correct place to apply.
 *
 * 2. The draft presents the age limits as contradictory, quoting 20-32,
 *    20-34 and 21+ from different third-party guides. The airline's own
 *    postings resolve it: not less than 20, not more than 34 for Kuwaiti
 *    applicants and not more than 32 for non-Kuwaiti applicants. Both
 *    numbers were right; third parties dropped the nationality split.
 *
 * 3. The draft names a recruitment agency. Kuwait Airways publishes no
 *    agency name anywhere, so the guide does not repeat it.
 *
 * 4. The draft quotes pay. Kuwait Airways publishes no salary, allowance or
 *    benefit figure in its own postings, so no figure is quoted.
 *
 * 5. The draft links job-ID pages. Those expire; the airline's own expired
 *    postings return "This job posting is no longer available". The guide
 *    links the portal root only.
 *
 * 6. Requirement details come from the airline's own archived postings
 *    rather than a standing published policy, so the guide says that
 *    plainly and tells readers the live posting always wins.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class KuwaitAirwaysCabinCrewJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.kuwaitairways.com/jobs/Careers';

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
        $title = 'How to Apply for Kuwait Airways Cabin Crew Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Most guides say Kuwait Airways has no careers page. It does, on a subdomain. Here is the real portal, why the age limits only looked contradictory, and what the airline never publishes.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-kuwait-airways-cabin-crew-jobs.jpg',
                'tags' => 'kuwait airways cabin crew, cabin crew jobs kuwait, kuwait airways careers, kuwait airways jobs, kuwait work permit, gulf cabin crew jobs, flight attendant jobs kuwait, kuwait airways recruitment',
                'meta_title' => 'Kuwait Airways Cabin Crew Jobs: How to Apply',
                'meta_description' => 'Kuwait Airways cabin crew jobs: the careers portal most guides miss, the age limits that only looked contradictory, and what the airline never publishes.',
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
            ['name' => 'Kuwait Airways, Kuwait City'],
            ['type' => 'Company', 'display_reference' => 'kuwait-airways-kuwait-city']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Kuwait'],
            ['area' => 'Kuwait City', 'country' => 'Kuwait']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cabin Crew, Kuwait Airways, Kuwait City Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Roster based, including night flights and layovers',
                'language' => 'English',
                // Kuwait Airways publishes no pay, allowance or benefit figure
                // in its own postings, so nothing is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Kuwait City based cabin crew roles with Kuwait Airways, advertised through the airline careers portal.',
                'seo_keywords' => 'kuwait airways cabin crew jobs, cabin crew jobs kuwait, kuwait airways careers, flight attendant jobs kuwait',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Kuwait Airways advertises cabin crew vacancies through its own careers portal, alongside engineering, airport, commercial and administrative roles at its Kuwait City base.</p>

<h3>What the work involves</h3>
<p>Safety and emergency duties on board, cabin service across the network, and customer care on a roster that includes night flights and layovers away from base.</p>

<h3>What the airline has asked for in its own postings</h3>
<ul>
    <li>Age from 20, with an upper limit that differs for Kuwaiti and non-Kuwaiti applicants</li>
    <li>A minimum height for female applicants</li>
    <li>High school education of 12 years</li>
    <li>Strong spoken and written English</li>
    <li>Well groomed and physically fit to work as a flight attendant</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> Kuwait Airways publishes no cabin crew pay, and the criteria above come from the airline's own advertised postings, which change &mdash; not by JobGader. Never pay an agent for an interview or a job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Kuwait Airways does have a working careers portal, and almost every guide on this subject says it does not.</strong> The confusion is simple: the path on the main website returns an error, while the real portal sits on a separate subdomain linked from the homepage footer.</p>

<p>That one fact is worth more than every requirement list you have read, because it is the difference between applying and not applying.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.kuwaitairways.com/jobs/Careers" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9992; Kuwait Airways Careers Portal &rarr;
    </a>
</div>

<h2>The Link That Works, and the One That Does Not</h2>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">Address</th>
            <th style="padding:12px;text-align:left;border:1px solid #e5e7eb;">What happens</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">careers.kuwaitairways.com</td>
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>Works.</strong> This is the airline's live recruitment portal, on its own subdomain.</td>
        </tr>
        <tr style="background:#f9fafb;">
            <td style="padding:12px;border:1px solid #e5e7eb;">kuwaitairways.com/en/careers</td>
            <td style="padding:12px;border:1px solid #e5e7eb;"><strong>404 error.</strong> This is the path guides try, and it is why they conclude no careers page exists.</td>
        </tr>
        <tr>
            <td style="padding:12px;border:1px solid #e5e7eb;">Any job-ID link you are sent</td>
            <td style="padding:12px;border:1px solid #e5e7eb;">Expires. Old postings load but read "This job posting is no longer available".</td>
        </tr>
    </tbody>
</table>

<p><strong>Bookmark the portal root, not a job link.</strong> Cabin crew campaigns open and close, and a link somebody shared in a group is usually already dead by the time it reaches you. Our <a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">Oman Air cabin crew guide</a> describes the same trap at another Gulf carrier.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-kuwait-airways-cabin-crew-jobs-crew.jpg" alt="Kuwait Airways cabin crew beside an aircraft" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Age Limits Were Never Contradictory</h2>

<p>You will find 20 to 32 on one site, 20 to 34 on another, and 21 and over on a third. People conclude the requirements are a mess. They are not. <strong>The airline's own postings split the upper limit by nationality, and third-party guides keep dropping that half of the sentence.</strong></p>

<p>Kuwait Airways has worded it like this in its own cabin crew advertisement: the age at the time of submission should not be less than <strong>20 years</strong>, and not more than <strong>34 years for Kuwaiti applicants</strong> and not more than <strong>32 years for non-Kuwaiti applicants</strong>.</p>

<p>So if you are applying from Pakistan, India or the Philippines, <strong>32 is your ceiling</strong>, not 34. Quoting 34 to yourself because a blog said so is how people waste an application.</p>

<h3>What else those postings have asked for</h3>

<ul>
    <li><strong>Minimum height of 160 cm</strong> for female applicants.</li>
    <li><strong>High school education of 12 years</strong> as a minimum.</li>
    <li><strong>A great command of English</strong>, spoken and written.</li>
    <li><strong>Well groomed and physically fit</strong> to work as a flight attendant.</li>
</ul>

<p>One honest caveat, because it matters: <strong>Kuwait Airways does not maintain a standing published list of cabin crew requirements.</strong> These lines come from its own advertised vacancies, and campaigns have been run for female applicants only. The criteria in the live posting always override anything in this guide or any other.</p>

<h2>What Kuwait Airways Does Not Publish</h2>

<p><strong>There is no Kuwait Airways cabin crew salary figure in its own job postings.</strong> No basic, no flying hours rate, no layover allowance, no accommodation detail. Any monthly figure you have seen in Kuwaiti dinar or rupees is a third-party estimate, not the airline's number.</p>

<p>The airline also publishes <strong>no named recruitment agency</strong>. If someone tells you they are the official hiring partner for Kuwait Airways in your city, the airline itself has never said so anywhere public. That is exactly the shape a recruitment scam takes.</p>

<h2>Kuwaitisation: Different From Omanisation, and Worth Understanding</h2>

<p>Kuwait's Public Authority of Manpower publishes national-workforce percentages that private employers must meet, by sector. The published table runs from <strong>banks at 64%</strong> and <strong>telecoms at 60%</strong> down through <strong>petrochemicals at 30%</strong>, <strong>insurance at 18%</strong>, and <strong>manufacturing and agriculture at 3%</strong>.</p>

<p><strong>There is no aviation or airline line in that published table.</strong> That is a meaningful difference from Oman, where a published Omanisation rate directly shapes an airline's hiring. It does not mean Kuwait Airways hires foreigners freely &mdash; it is a national carrier and national employment policy applies to it through other routes &mdash; but it does mean you should not repeat an airline-specific Kuwaitisation percentage, because Kuwait does not publish one.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-kuwait-airways-cabin-crew-jobs-kuwait.jpg" alt="Kuwait Airways aircraft at Kuwait International Airport" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Work Permit Is the Employer's Job, Not Yours</h2>

<p>This is the part that separates a real offer from a scam. Under Kuwait's system, <strong>the work permit application is filed by the employer</strong>, and the paperwork the labour authority asks for is employer paperwork &mdash; a wage transfer certificate and the employer's own declaration letter, among other documents. You do not walk in and apply for your own work permit.</p>

<p>Once the permit is issued and you enter Kuwait, your residency &mdash; the iqama &mdash; is processed against that employer. So the practical rule is simple: <strong>if there is no employer, there is no permit.</strong> Anyone offering to arrange a Kuwait work permit for you personally, for a fee, is selling something that does not work that way.</p>

<p>Kuwait's own English-language page describing the work permit steps has since been taken offline, so check the current process on the Public Authority of Manpower site rather than trusting a cached copy on a blog.</p>

<h2>Fake Kuwait Airways Job Ads Are a Known Problem</h2>

<p>In April 2025, Kuwaiti media reported that Kuwait Airways had <strong>denied advertisements circulating on social media about job openings</strong>, stressing that applicants should rely only on its official sources &mdash; its website and verified social media accounts &mdash; and saying it had taken legal action over the fake ads.</p>

<p>Treat that as the airline's position, reported through the press rather than published as a notice on its own site. The practical defence is the same either way:</p>

<ul>
    <li><strong>Apply only through the careers portal.</strong> Not through a WhatsApp forward, not by emailing a CV to a recruiter.</li>
    <li><strong>Pay nobody.</strong> Not for an application, an interview, a medical, a visa or a "seat".</li>
    <li><strong>Check the sender.</strong> A free email account is not an airline.</li>
    <li><strong>Be suspicious of certainty.</strong> A guaranteed job is the oldest tell there is.</li>
</ul>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Open the careers portal</strong> at careers.kuwaitairways.com and look for a live cabin crew posting. If there is none, there is no campaign open right now.</li>
    <li><strong>Read the live posting's own criteria,</strong> including whether that campaign is open to your nationality and gender.</li>
    <li><strong>Check your age against 32</strong> if you are not a Kuwaiti national.</li>
    <li><strong>Prepare documents:</strong> passport, high school certificate, English evidence, professional photographs, and a clean one-page CV.</li>
    <li><strong>Follow the airline's verified social accounts</strong> for campaign announcements, since postings appear and close quickly.</li>
    <li><strong>Do not resign your current job</strong> until you have a written offer and the employer has begun the work permit process.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Does Kuwait Airways have a careers page?</h3>
<p>Yes. It is at careers.kuwaitairways.com, linked from the homepage footer. The path kuwaitairways.com/en/careers returns a 404 error, which is why many guides claim no careers page exists.</p>

<h3>What is the age limit for Kuwait Airways cabin crew?</h3>
<p>The airline's own postings have specified not less than 20 years, and not more than 34 for Kuwaiti applicants or 32 for non-Kuwaiti applicants. The live posting always decides.</p>

<h3>What height does Kuwait Airways require?</h3>
<p>Its postings have specified a minimum of 160 cm for female applicants. No reach measurement appears in the airline's own wording.</p>

<h3>How much do Kuwait Airways cabin crew earn?</h3>
<p>Kuwait Airways does not publish a salary, allowance or benefit figure in its job postings. Every number circulating online is a third-party estimate.</p>

<h3>Can non-Kuwaitis apply to Kuwait Airways?</h3>
<p>Yes. Its postings set a separate, lower age ceiling for non-Kuwaiti applicants, which shows non-Kuwaitis are within scope. Individual campaigns may still restrict nationality or gender.</p>

<h3>Does Kuwait Airways use a recruitment agency?</h3>
<p>The airline publishes no agency name anywhere official. Any agency claiming to be its hiring partner, particularly one charging a fee, is not backed by anything Kuwait Airways has stated.</p>

<h3>Who applies for a Kuwait work permit?</h3>
<p>The employer. The documents the labour authority requires are employer documents, including a wage transfer certificate and an employer declaration letter.</p>

<h3>Is there a Kuwaitisation quota for airlines?</h3>
<p>Kuwait's Public Authority of Manpower publishes national-workforce percentages by sector, and no aviation or airline category appears in that published table.</p>

<h2>People Also Search For</h2>

<h3>Kuwait Airways careers login</h3>
<p>Candidate accounts are created on the recruitment portal at careers.kuwaitairways.com, not on the main airline website.</p>

<h3>Kuwait Airways cabin crew recruitment 2026</h3>
<p>Campaigns are announced through the careers portal and the airline's verified social accounts. There is no fixed annual recruitment calendar published.</p>

<h3>Kuwait Airways cabin crew salary in KD</h3>
<p>Not published by the airline. Figures quoted in Kuwaiti dinar come from estimate sites rather than from Kuwait Airways.</p>

<h3>Kuwait Airways cabin crew height requirement</h3>
<p>A minimum of 160 cm has appeared for female applicants in the airline's own postings.</p>

<h3>Kuwait Airways jobs for freshers</h3>
<p>Cabin crew postings have asked for 12 years of schooling and strong English rather than prior aviation experience, so freshers are within scope when a campaign is open.</p>

<h3>Kuwait work visa process</h3>
<p>Employer-led. The employer files the work permit, and the residency is then processed against that employer once you arrive.</p>

<h3>Kuwait Airways fake job offer</h3>
<p>Kuwaiti media reported in April 2025 that the airline denied job advertisements circulating on social media and said it had taken legal action. Apply only through the portal.</p>

<h3>Cabin crew jobs in Gulf airlines</h3>
<p>Emirates publishes pay and runs regular worldwide Open Days; Oman Air publishes neither pay nor standing requirements. The Gulf carriers differ more than they look.</p>

<h2>More Job Guides</h2>

<p>Comparing Gulf carriers and routes? These cover them:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; the carrier that publishes neither pay nor standing requirements.</li>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the one Gulf airline that does publish its pay.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; customer-facing Gulf work with lower entry barriers.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; another sponsored Gulf route.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; where to build the hospitality experience airlines ask for.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; why Abu Dhabi ground jobs are advertised by a company called Velora.</li>
    <li><a href="/blog/how-to-apply-for-singapore-airlines-cabin-crew-jobs">How to Apply for Singapore Airlines Cabin Crew Jobs</a> &mdash; published pay, a five-year contract, and the service bond most guides omit.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Kuwait Airways' own website and recruitment portal, the wording of its own advertised cabin crew vacancies, the Public Authority of Manpower's published national-workforce percentages, and Kuwaiti press reporting of the airline's statement on fake job advertisements. Kuwait Airways does not publish cabin crew pay or a standing requirements policy, and campaign criteria change. Always follow the criteria in the live posting.</p>
HTML;
    }
}
