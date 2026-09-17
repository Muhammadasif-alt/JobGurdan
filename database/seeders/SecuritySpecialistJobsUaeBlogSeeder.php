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
 * "Security Specialist Jobs in UAE" — a job title UAE employers use for two
 * different careers: licensed physical security above guard level, and
 * information security. The security guard guide owns entry-level guarding
 * pay and the basic salary split; this one owns the licensing map for
 * supervisors, control rooms and specialists, the certification rules for
 * cybersecurity roles, and the labour law on shifts, overtime and benefits.
 *
 * Corrections to the draft:
 *
 * 1. It says most security personnel in Dubai need SIRA and that other
 *    emirates use "Abu Dhabi Police or other local regulatory authorities".
 *    SIRA licenses Dubai only; the Private Security Business Department of
 *    Abu Dhabi Police covers Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al
 *    Khaimah and Fujairah. There is no separate Sharjah regulator.
 *
 * 2. Its entry-level band starts at AED 3,000. Published ranges for guards sit
 *    around AED 1,800 to 3,000, with supervisors from AED 4,000, and the UAE
 *    publishes no official salary data for either track.
 *
 * 3. It lists housing allowance and annual flights home as package benefits.
 *    Neither is required by Federal Decree-Law No. 33 of 2021; the law only
 *    makes the employer pay the repatriation ticket, so both are contract
 *    terms.
 *
 * 4. It lists CISSP as a certification to bring to an application. ISC2
 *    requires five years of cumulative paid work in two or more of the eight
 *    CISSP domains; a candidate without it becomes an Associate of ISC2.
 *
 * 5. Its apply link searches the American Indeed site with a UAE location.
 *    The listing points at ae.indeed.com instead.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SecuritySpecialistJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ae.indeed.com/q-security-specialist-jobs.html';

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
        $title = 'Security Specialist Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Security specialist covers two jobs: licensed guarding, where SIRA covers Dubai and PSBD the other six emirates, and cybersecurity, where CISSP needs five years of experience. Flights and housing are contract terms, not rights.',
                'content' => $content,
                'featured_image' => 'blogs/security-specialist-jobs-in-uae.jpg',
                'tags' => 'security specialist jobs uae, security jobs dubai, sira license dubai, psbd abu dhabi security license, cctv operator jobs uae, security supervisor salary uae, cyber security jobs uae, cissp requirements, uae labour law overtime, security jobs abu dhabi',
                'meta_title' => 'Security Specialist Jobs in UAE 2026: Licences and Pay',
                'meta_description' => 'Security specialist jobs in UAE: SIRA covers Dubai only, PSBD licenses six emirates, the CISSP experience rule, overtime limits and gratuity on basic pay.',
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
            ['name' => 'UAE Security Companies, Corporate Security & Cyber Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ae-security-specialist-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'security'],
            ['name' => 'Security']
        );

        Job::updateOrCreate(
            [
                'position' => 'Security Specialist — Supervisors, Control Rooms, Corporate Security and Cybersecurity Teams, UAE Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts for site and control-room roles; office hours with on-call duty for cybersecurity roles',
                'language' => 'English',
                // The title spans guarding and cybersecurity, and the UAE
                // publishes no official pay data for either, so no range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Security specialist roles in the UAE: supervisors, CCTV control rooms, corporate security and cybersecurity teams.',
                'seo_keywords' => 'security specialist jobs uae, security supervisor jobs dubai, cctv operator jobs uae, cyber security jobs uae, security jobs abu dhabi',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Security companies, hotels, malls, banks, free zones, infrastructure operators and corporate security teams across the UAE hire security specialists for supervisory, control room and information security roles.</p>

<h3>What the work involves</h3>
<p>Physical roles cover access control, CCTV monitoring, incident response, patrol supervision and risk assessments. Cybersecurity roles cover network and endpoint monitoring, incident handling, vulnerability management and security policy.</p>

<h3>Requirements</h3>
<ul>
    <li>For physical security, a licence for the emirate the site is in: SIRA for Dubai, PSBD for Abu Dhabi and the northern emirates</li>
    <li>For cybersecurity, certifications such as CompTIA Security+, CEH or CISSP, which requires five years of relevant experience</li>
    <li>English; Arabic is an advantage</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Gratuity on basic salary.</strong> End-of-service gratuity is calculated on basic salary alone under Federal Decree-Law No. 33 of 2021</li>
    <li><strong>Overtime has limits.</strong> No more than two hours a day, paid at a premium on basic wage</li>
    <li><strong>Housing and air tickets are contract terms.</strong> Get them in writing in the offer</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask which emirate's licence the role needs and how the package splits basic salary and allowances,</strong> before accepting an offer.</p>

<p><strong>Note:</strong> pay, licensing and visa rules are set by employers, SIRA, Abu Dhabi Police and the Ministry of Human Resources and Emiratisation &mdash; not by JobGader. Confirm the details with the employer and the regulator before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"Security specialist" is one of the broadest job titles in the UAE. The same words appear on a mall's CCTV supervisor post, a hotel's security coordinator role and a bank's cybersecurity analyst vacancy. Those are different careers with different licences, certifications and pay. Before you apply, it helps to know which track a vacancy belongs to, which regulator licenses the emirate you will work in, what cybersecurity certifications really require, and what the labour law guarantees in a package.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ae.indeed.com/q-security-specialist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128737; Browse Security Specialist Jobs in UAE &rarr;
    </a>
</div>

<h2>Two Jobs Share One Title</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Physical security</th>
            <th style="padding:10px;text-align:left;">Cybersecurity</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Typical roles</strong></td><td style="padding:10px;">Security supervisor, CCTV or control room operator, event security lead, security coordinator</td><td style="padding:10px;">SOC analyst, information security specialist, security engineer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>What you need</strong></td><td style="padding:10px;">A licence from the regulator of the emirate, plus training for the role category</td><td style="padding:10px;">IT experience and certifications; no security guard licence</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Hours</strong></td><td style="padding:10px;">Rotating shifts, including nights</td><td style="padding:10px;">Office hours or SOC shifts, often with on-call duty</td></tr>
    </tbody>
</table>
</div>

<p>Read the duties before the title. A vacancy that mentions patrols, access control and incident reports is a licensed security role; one that mentions SIEM tools, endpoint alerts and vulnerability scans is an IT role.</p>

<h2>Licensing: SIRA Covers Dubai Only</h2>

<p>Guides say most security personnel in Dubai need a SIRA licence and that other emirates use "Abu Dhabi Police or other local regulatory authorities". The actual map is simpler, and it decides which jobs you can take:</p>

<ul>
    <li><strong>SIRA</strong>, the Security Industry Regulatory Agency under <strong>Dubai Police</strong>, licenses private security companies and individual security personnel <strong>in the Emirate of Dubai</strong>, under Dubai's Law No. 12 of 2016 regulating the security industry.</li>
    <li><strong>PSBD</strong>, the Private Security Business Department of <strong>Abu Dhabi Police</strong> under the Ministry of Interior, is the authority for <strong>Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah</strong>. There is no separate Sharjah security regulator.</li>
    <li><strong>A licence does not travel.</strong> A SIRA card does not let you work in Abu Dhabi or Sharjah, and a PSBD licence does not let you work in Dubai.</li>
    <li><strong>Categories are licensed separately.</strong> Guard, event security, cash escort, CCTV operator and supervisor are different categories, with different training.</li>
    <li><strong>The employer applies.</strong> Individual licences are processed through a licensed security company, with an Emirates ID, a good conduct certificate and a medical check, followed by training and an exam.</li>
</ul>

<p>So "get licensed before applying" only works if you know the emirate. Most candidates cannot hold a licence before an employer sponsors them, and a course for the wrong regulator does not help.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-specialist-jobs-in-uae-patrol.jpg"
         alt="A security officer in a black tactical vest and cap speaking into a radio outside an office entrance in Dubai, with the Burj Khalifa, a patrol vehicle and a UAE flag behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Cybersecurity Certifications: What They Require</h2>

<p>Guides list CompTIA Security+, CISSP and CEH as certifications employers value. They do, but they are at very different levels:</p>

<ul>
    <li><strong>CompTIA Security+</strong> is an entry-level certification. The current exam is <strong>SY0-701</strong>, and there is no formal experience requirement.</li>
    <li><strong>CEH</strong> (Certified Ethical Hacker) is issued by <strong>EC-Council</strong> and focuses on offensive security techniques.</li>
    <li><strong>CISSP</strong>, from <strong>ISC2</strong>, requires <strong>five years of cumulative paid work in two or more of its eight domains</strong>. A relevant degree or approved credential can waive one year. Someone who passes without the experience becomes an <strong>Associate of ISC2</strong> and has six years to earn it. The exam costs <strong>US$749</strong>.</li>
</ul>

<p>So CISSP is a senior credential, not something to list as a target for a first security job. The UAE Cyber Security Council, set up by the federal government in 2020, leads national cybersecurity policy, and many of its workforce programmes are aimed at Emirati nationals.</p>

<h2>Salary: What Can and Cannot Be Supported</h2>

<p>Guides quote <strong>AED 3,000 to AED 5,000 a month</strong> for entry-level security officers and specialists, AED 5,000 to 9,000 for experienced staff, and AED 10,000 to 20,000 or more for senior managers and IT security specialists. Three things to know before relying on those bands:</p>

<ul>
    <li><strong>No official data.</strong> The UAE has no general minimum wage for expatriate private sector workers, and MOHRE publishes no salary data by job title. Every range is an advertised or survey figure.</li>
    <li><strong>The entry band is high for guarding.</strong> Published ranges for guards sit around <strong>AED 1,800 to 3,000 a month</strong>, with supervisors quoted from <strong>AED 4,000</strong>. AED 3,000 is a realistic start for CCTV, control room or junior specialist posts, not for a new guard.</li>
    <li><strong>The top band is the IT track.</strong> AED 10,000 and above is typical of cybersecurity and security management roles, where experience and certifications drive pay.</li>
</ul>

<h2>What the Labour Law Guarantees in a Package</h2>

<p>Guides say packages include housing, transport and annual flights home. Some do, but <strong>Federal Decree-Law No. 33 of 2021</strong> sets a narrower floor:</p>

<ul>
    <li><strong>Air tickets.</strong> An annual flight home is <strong>not a statutory entitlement</strong>. The law requires the employer to pay the <strong>repatriation ticket</strong> at the end of employment, so any annual ticket must be written into the contract.</li>
    <li><strong>Housing.</strong> A housing allowance or accommodation is also a contract term, not a legal right.</li>
    <li><strong>Gratuity.</strong> End-of-service gratuity is <strong>21 days' basic salary for each of the first five years and 30 days for each year after</strong>, calculated on <strong>basic salary alone</strong>, after one year of service, capped at two years' wage.</li>
    <li><strong>Hours and overtime.</strong> Normal hours are <strong>8 a day and 48 a week</strong>. Overtime is capped at <strong>two hours a day and 144 hours in any three weeks</strong>, paid at 25 per cent above basic wage, or 50 per cent between 10pm and 4am for workers who are not on shift rotas.</li>
</ul>

<p>A high package with a low basic salary builds less gratuity. Ask for the basic salary in writing and compare offers on basic plus what the contract guarantees.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-specialist-jobs-in-uae-control-room.jpg"
         alt="A security officer speaking into a radio while watching CCTV feeds on several monitors in a Dubai control room overlooking the Burj Khalifa"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Skills Employers Look For</h2>

<ul>
    <li>Calm judgement under pressure and clear incident reporting</li>
    <li>CCTV, access control and alarm system experience for control room and supervisory posts</li>
    <li>Fitness for patrol and event roles</li>
    <li>Network, endpoint and SIEM tool experience for cybersecurity posts</li>
    <li>English for reports; Arabic is a real advantage</li>
    <li>Military, police or private security experience, which employers commonly value for physical roles</li>
</ul>

<h2>Where to Find Security Specialist Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Search ae.indeed.com rather than the American site, and separate guarding searches from cybersecurity searches.</li>
    <li><strong>Licensed security companies.</strong> Most physical security staff are employed by licensed companies that deploy them to malls, hotels and offices.</li>
    <li><strong>Direct employers.</strong> Banks, airports, hotels, free zones and infrastructure operators hire in-house security and SOC teams.</li>
    <li><strong>Recruitment agencies.</strong> Specialist agencies fill supervisory, risk and cybersecurity roles.</li>
</ol>

<h2>Tips for Landing a Security Specialist Job</h2>

<ul>
    <li><strong>Match the emirate.</strong> For physical roles, say whether you hold or have held a SIRA or PSBD licence and for which category.</li>
    <li><strong>Separate your CV by track.</strong> A cybersecurity CV should lead with tools and incidents handled, not guarding experience.</li>
    <li><strong>Prepare scenario answers.</strong> An unauthorized access attempt, a medical emergency on site, or a phishing incident spreading across a network.</li>
    <li><strong>Check the basic salary.</strong> It decides your gratuity and overtime pay, so compare it, not only the package total.</li>
</ul>

<h2>Career Progression</h2>

<p>Physical security careers move from officer to supervisor, security operations manager and head of security, often with risk and compliance responsibilities. Cybersecurity careers move from SOC analyst to security engineer, security architect and information security manager, where senior certifications such as CISSP become relevant.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is a SIRA licence valid in all of the UAE?</h3>
<p>No. SIRA licenses security personnel in Dubai only. Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah are covered by the Private Security Business Department of Abu Dhabi Police.</p>

<h3>Who licenses security staff in Sharjah?</h3>
<p>The Private Security Business Department of Abu Dhabi Police, which covers Sharjah along with Abu Dhabi and the other northern emirates.</p>

<h3>How much do security specialists earn in the UAE?</h3>
<p>There is no official salary data. Published ranges for guards sit around AED 1,800 to 3,000 a month, supervisors from AED 4,000, and cybersecurity roles run much higher.</p>

<h3>Is an annual air ticket a legal right in the UAE?</h3>
<p>No. The law requires the employer to pay the repatriation ticket at the end of employment; an annual ticket is a contract term.</p>

<h3>How is end-of-service gratuity calculated?</h3>
<p>On basic salary alone: 21 days for each of the first five years and 30 days for each year after, capped at two years' wage.</p>

<h3>What are the overtime limits for security jobs in the UAE?</h3>
<p>Overtime is capped at two hours a day and 144 hours in any three weeks, paid at a premium on basic wage.</p>

<h3>Do I need five years of experience for CISSP?</h3>
<p>Yes, five years of cumulative paid work in two or more of the eight domains, with one year waivable. Without it you become an Associate of ISC2.</p>

<h3>Can I get a security licence before I have a job?</h3>
<p>Usually not. Individual licences are processed through a licensed security company for a specific emirate and role category.</p>

<h2>People Also Search For</h2>

<h3>SIRA license Dubai</h3>
<p>Dubai's security licence, processed through a licensed security company.</p>

<h3>PSBD license Abu Dhabi</h3>
<p>The licence for Abu Dhabi, Sharjah and the other northern emirates.</p>

<h3>CCTV operator jobs UAE</h3>
<p>A separately licensed category for physical security roles.</p>

<h3>Security supervisor salary UAE</h3>
<p>Quoted from about AED 4,000 a month, with no official data.</p>

<h3>Cyber security jobs Dubai</h3>
<p>IT roles with certifications instead of a guard licence.</p>

<h3>CISSP requirements</h3>
<p>Five years of paid work in two or more of the eight domains.</p>

<h3>UAE labour law overtime</h3>
<p>Two hours a day and 144 hours in any three weeks at most.</p>

<h3>Security jobs with accommodation UAE</h3>
<p>Common in guarding, but a contract term rather than a legal right.</p>

<h2>More Job Guides</h2>

<p>Comparing security and UAE jobs? These cover them:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; entry-level guarding, and why the basic salary split decides your gratuity.</li>
    <li><a href="/blog/system-administrator-jobs-in-uae">System Administrator Jobs in UAE</a> &mdash; the IT route into security, and the law on on-call work.</li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a> &mdash; professional work under the same labour law.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-desk work, and how a package split changes gratuity.</li>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; public law enforcement careers in the United States.</li>
    <li><a href="/blog/government-security-jobs-in-australia">Government Security Jobs in Australia</a> &mdash; AFP protective service officer pay, Border Force, ASIO and ASD requirements, and how security clearances work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Licensing rules, certification requirements, exam fees and labour law provisions change. Confirm the current position with SIRA, Abu Dhabi Police, ISC2, CompTIA, EC-Council and the Ministry of Human Resources and Emiratisation before applying or accepting an offer.</p>
HTML;
    }
}
