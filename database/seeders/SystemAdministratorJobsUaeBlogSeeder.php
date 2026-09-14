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
 * "System Administrator Jobs in UAE" — Windows, Linux, Microsoft 365 and
 * infrastructure administration on the mainland and in the free zones. The
 * accountant, receptionist and security guard guides already cover the UAE
 * gratuity arithmetic and the licensing routes, so this one owns the platform
 * skills employers list, the hours and on-call rules, Emiratisation in IT and
 * the visa salary thresholds a sysadmin can reach.
 *
 * Corrections to the draft:
 *
 * 1. It lists Azure and Office 365 skills under names Microsoft has retired:
 *    Azure Active Directory has been Microsoft Entra ID since the rename
 *    announced on 11 July 2023. It also misses the deadline driving server
 *    work now: Windows Server 2016 leaves extended support on 12 January 2027.
 *
 * 2. It says roles can need 24/7 on-call cover without the legal limits. Under
 *    Federal Decree-Law No. 33 of 2021 overtime is capped at two hours a day
 *    and 144 hours in any three weeks, paid at a premium on basic wage.
 *
 * 3. The poster offers free accommodation and an air ticket as standard. An
 *    annual ticket is not a statutory entitlement; the law requires only the
 *    repatriation cost in Article 13(12), so both are contract terms.
 *
 * 4. It says some employers prefer Arabic speakers without the reason behind
 *    it: Emiratisation targets reach 10 per cent of skilled roles by the end
 *    of 2026, and smaller companies in information and communications have
 *    headcount targets of their own.
 *
 * 5. It calls the career path strongly growing with no source. The UAE
 *    publishes no occupation-level demand or pay data for system
 *    administrators, so the guide describes listings, not a forecast.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SystemAdministratorJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ae.indeed.com/q-system-administrator-jobs.html';

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
        $title = 'System Administrator Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Azure AD is now Microsoft Entra ID, Windows Server 2016 support ends on 12 January 2027, UAE law caps overtime at two hours a day, and an annual air ticket is a contract term, not a legal right.',
                'content' => $content,
                'featured_image' => 'blogs/system-administrator-jobs-in-uae.jpg',
                'tags' => 'system administrator jobs uae, sysadmin jobs dubai, system administrator jobs abu dhabi, windows server administrator, linux administrator jobs uae, microsoft entra id, az-104, uae overtime law, emiratisation it jobs',
                'meta_title' => 'System Administrator Jobs in UAE 2026: Skills, Visa, Pay',
                'meta_description' => 'System administrator jobs in the UAE: skills employers want, overtime and on-call rules, Emiratisation targets and the Green and Golden visas.',
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
            ['name' => 'UAE IT Services, Telecom & Enterprise Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-sysadmin-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'System Administrator — Windows, Linux, Microsoft 365 and Infrastructure Roles, UAE Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Office hours with scheduled maintenance windows; some roles add shifts or on-call rotas',
                'language' => 'English; Arabic is an advantage for government-linked employers',
                // The UAE has no general private sector minimum wage and no
                // official pay data for this title, so no range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'System administrator roles in the UAE covering Windows and Linux servers, Microsoft 365, Entra ID, virtualization and backups.',
                'seo_keywords' => 'system administrator jobs uae, sysadmin jobs dubai, windows server jobs uae, linux administrator jobs dubai, infrastructure engineer uae',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>IT services companies, telecom providers, logistics groups, banks, hospitals, schools and government-linked entities across Dubai, Abu Dhabi and the northern emirates hire system administrators to run their servers, identity systems and infrastructure.</p>

<h3>What the work involves</h3>
<p>Maintaining Windows Server and Linux systems, managing Active Directory, Microsoft Entra ID and Microsoft 365, patching and backups, virtualization and storage, first-line network troubleshooting and handling incidents through a ticketing system.</p>

<h3>Requirements</h3>
<ul>
    <li>Experience in IT support or system administration; most listings ask for two years or more</li>
    <li>A degree, usually attested for the work permit, and certifications such as AZ-104, MS-102, RHCSA or CCNA for many roles</li>
    <li>Availability for maintenance windows, and for shifts or on-call cover where the role requires it</li>
</ul>

<h3>How the package works</h3>
<ul>
    <li><strong>The visa is the employer's cost.</strong> Under Federal Decree-Law No. 33 of 2021 an employer may not charge you recruitment or employment costs</li>
    <li><strong>Overtime has limits.</strong> No more than two hours a day, paid at a premium on basic wage</li>
    <li><strong>Air tickets and housing are contract terms.</strong> Get them in writing in the offer</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for a UAE work visa or a job offer.</strong> Pakistani applicants should use only a licensed Overseas Employment Promoter.</p>

<p><strong>Note:</strong> pay, shifts, benefits and visa rules are set by each employer and the UAE authorities &mdash; not by JobGader. Confirm the details with the employer and MOHRE before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>System administrators keep the servers, identity systems and networks of UAE companies running, from Dubai's logistics and hospitality groups to Abu Dhabi's hospitals, schools and government-linked entities. It is a real and steady job market, but most guides describe it with retired product names, skip the legal limits on on-call work and treat contract perks as rights. Here is what to check before you apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ae.indeed.com/q-system-administrator-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128421; Browse System Administrator Jobs in UAE &rarr;
    </a>
</div>

<h2>What UAE System Administrators Do</h2>

<ul>
    <li>Installing, patching and maintaining <strong>Windows Server</strong> and <strong>Linux</strong> systems</li>
    <li>Managing on-premises <strong>Active Directory</strong>, <strong>Microsoft Entra ID</strong> and <strong>Microsoft 365</strong></li>
    <li>Backups and recovery with tools such as Veeam or Commvault</li>
    <li>Virtualization platforms and SAN or NAS storage</li>
    <li>Network basics: switching, VLANs, routing and structured cabling</li>
    <li>Monitoring, incident handling through ITSM ticketing, and documentation</li>
</ul>

<p>Senior roles add vulnerability remediation, capacity planning and supervising junior staff. The UAE publishes no occupation-level demand or pay data for system administrators, so treat claims of fast growth as a description of listings, not an official forecast.</p>

<h2>Use the Product Names Employers Now Use</h2>

<p>Guides ask for experience with "Azure and Office 365". Microsoft has renamed both, and a CV that still uses the old names looks dated to a technical interviewer:</p>

<ul>
    <li><strong>Azure Active Directory is now Microsoft Entra ID.</strong> Microsoft announced the rename on <strong>11 July 2023</strong>. Only the name changed; the features and licences did not. On-premises Active Directory Domain Services keeps its name.</li>
    <li><strong>Office 365 business plans are sold as Microsoft 365.</strong> Write "Microsoft 365 administration" on your CV, and name the admin centres you have used.</li>
</ul>

<h3>The deadline behind server projects</h3>

<p>Windows Server 2016 leaves extended support on <strong>12 January 2027</strong>, after which it gets no routine security updates. Companies still running it are planning migrations to newer Windows Server versions or to Azure now, so upgrade and migration experience is worth putting near the top of your CV.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/system-administrator-jobs-in-uae-server-room.jpg"
         alt="A system administrator working on a laptop at a desk beside server racks with the Burj Khalifa and UAE flag behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Experience and Certifications</h2>

<p>Most system administrator postings ask for <strong>three to six years</strong> of experience, and junior roles for about two. The realistic entry route is IT support or help desk work first. Certifications are rarely mandatory but help you pass the CV screen:</p>

<ul>
    <li><strong>AZ-104</strong> for Microsoft Azure Administrator Associate</li>
    <li><strong>MS-102</strong> for Microsoft 365 Administrator Expert</li>
    <li><strong>RHCSA (EX200)</strong> for Red Hat Linux, a hands-on practical exam</li>
    <li><strong>CCNA (200-301)</strong> for networking fundamentals</li>
</ul>

<p>Role-based Microsoft certifications <strong>expire one year after</strong> you earn them. Renewal is free: pass a short online assessment on Microsoft Learn in the six months before expiry. Let it lapse and you pay for the full exam again.</p>

<h2>On-Call Work and the Law on Overtime</h2>

<p>Guides say many roles need 24/7 availability. On the mainland, <strong>Federal Decree-Law No. 33 of 2021</strong> sets the limits:</p>

<ul>
    <li><strong>Normal hours.</strong> Article 17 sets <strong>8 hours a day or 48 hours a week</strong>.</li>
    <li><strong>Overtime cap.</strong> Article 19 allows overtime of no more than <strong>two hours a day</strong>, and total working time may not exceed <strong>144 hours in any three weeks</strong>.</li>
    <li><strong>Overtime pay.</strong> Your normal hourly wage, calculated on basic wage, plus <strong>at least 25 per cent</strong>. Overtime worked <strong>between 10 pm and 4 am</strong> earns at least 50 per cent extra.</li>
</ul>

<p>Before accepting an on-call role, ask how call-outs are counted and paid, and whether senior staff are treated as exempt. Get the answer in the offer letter.</p>

<h2>Air Tickets and Accommodation Are Contract Terms</h2>

<p>Many adverts list free accommodation, medical insurance and an air ticket. Read them as offers, not rights:</p>

<ul>
    <li><strong>Annual air ticket.</strong> A yearly flight home is <strong>not a statutory entitlement</strong> under the labour law. It exists only if your contract says so.</li>
    <li><strong>Return at the end.</strong> What the law does require, in <strong>Article 13(12)</strong>, is that the employer pays your repatriation to your point of hire, unless you join another employer or the contract ends for reasons due to you.</li>
    <li><strong>Accommodation.</strong> Professional roles usually pay a housing allowance rather than providing housing, so compare offers on basic salary plus allowances.</li>
    <li><strong>Gratuity.</strong> End-of-service gratuity is 21 days' basic pay for each of the first five years and 30 days for each year after, on basic salary only.</li>
</ul>

<p>There is no general minimum wage for private sector employees, and no official pay data for this job, so ask for the basic salary figure separately.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/system-administrator-jobs-in-uae-it-team.jpg"
         alt="A system administrator smiling at a laptop in a Dubai office with the city skyline and UAE flag in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Why Some Employers Prefer Emiratis and Arabic Speakers</h2>

<p>Guides mention that government-linked employers may prefer Arabic speakers. The bigger reason is Emiratisation:</p>

<ul>
    <li><strong>Companies with 50 or more employees</strong> must raise the Emirati share of their skilled roles by 2 per cent a year, reaching <strong>10 per cent by the end of 2026</strong>. IT roles count as skilled.</li>
    <li><strong>Companies with 20 to 49 employees</strong> in 14 economic activities, including <strong>information and communications</strong>, had to employ one Emirati in 2024 and two by the end of 2025.</li>
    <li>An Emirati must be paid at least AED 6,000 a month to count towards the target, a floor that does not apply to expatriate staff.</li>
</ul>

<p>This does not stop companies hiring expatriates, but it explains why some IT roles, especially in government-linked entities, are advertised for UAE nationals only.</p>

<h2>Visas: Employer Cost, Green Visa and Golden Visa</h2>

<ul>
    <li><strong>Employer-sponsored work visa.</strong> A mainland employer needs a MOHRE work permit before employing you and may not charge you recruitment or employment costs. Free zone employers issue visas through their free zone authority.</li>
    <li><strong>Green visa.</strong> A five-year self-sponsored residence for skilled employees at MOHRE skill level 1, 2 or 3, with a bachelor's degree and a salary of at least <strong>AED 15,000</strong> a month.</li>
    <li><strong>Golden visa for skilled professionals.</strong> A ten-year residence for professionals at MOHRE level 1 or 2, with a bachelor's degree and a monthly basic salary of at least <strong>AED 30,000</strong>, a level reached mainly by infrastructure managers.</li>
    <li><strong>DIFC and ADGM.</strong> A role in either financial centre follows its own employment law, not the federal one.</li>
</ul>

<p>Pakistani applicants should use only an Overseas Employment Promoter licensed by the Federal Government, and check the licence on the Bureau of Emigration and Overseas Employment list.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/system-administrator-jobs-in-uae-monitoring.jpg"
         alt="A system administrator checking server, network, security and backup status on a monitoring screen in a UAE data centre"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply</h2>

<ol>
    <li>Name the systems you ran: Windows Server versions, Linux distributions, Entra ID, Microsoft 365, VMware or Hyper-V, and the backup tools.</li>
    <li>Quantify the work: servers or endpoints managed, uptime, migrations completed and tickets resolved.</li>
    <li>Put current certifications near the top, with their expiry dates.</li>
    <li>State your availability for maintenance windows and on-call cover, and ask how overtime is paid.</li>
    <li>Keep the CV short and clear, with degree and certificates ready for attestation.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What skills do system administrators need in the UAE?</h3>
<p>Windows Server and Linux, Active Directory, Microsoft Entra ID and Microsoft 365, backups, virtualization, storage and network basics, plus troubleshooting and documentation.</p>

<h3>Is Azure AD still used in the UAE?</h3>
<p>The service is, under a new name. Microsoft announced on 11 July 2023 that Azure Active Directory would become Microsoft Entra ID, with the same features and licences.</p>

<h3>Which certifications help for sysadmin jobs in Dubai?</h3>
<p>AZ-104, MS-102, RHCSA and CCNA are common. Role-based Microsoft certifications expire after one year but can be renewed free online.</p>

<h3>Can a UAE employer make me work 24/7 on call?</h3>
<p>Overtime is limited to two hours a day and 144 hours in any three weeks under Federal Decree-Law No. 33 of 2021, paid at least 25 per cent extra, or 50 per cent between 10 pm and 4 am.</p>

<h3>Do UAE IT jobs include a free annual air ticket?</h3>
<p>Only if the contract says so. The annual ticket is not a statutory entitlement; the law requires the employer to pay repatriation at the end of the job.</p>

<h3>Who pays for a system administrator's UAE work visa?</h3>
<p>The employer. Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment or employment costs.</p>

<h3>Can a system administrator get a UAE Golden visa?</h3>
<p>Yes, at MOHRE level 1 or 2 with a bachelor's degree and a basic salary of at least AED 30,000 a month. The Green visa needs AED 15,000.</p>

<h3>Why are some UAE IT jobs for Emiratis only?</h3>
<p>Emiratisation targets require larger companies to reach 10 per cent Emiratis in skilled roles by the end of 2026, and government-linked entities often recruit nationals first.</p>

<h2>People Also Search For</h2>

<h3>System administrator jobs in Dubai</h3>
<p>The largest share of listings, across IT services, logistics, hospitality and media companies.</p>

<h3>System administrator jobs in Abu Dhabi</h3>
<p>Hospitals, schools, construction groups and government-linked entities, where Arabic can be an advantage.</p>

<h3>Windows Server 2016 end of support</h3>
<p>Extended support ends on 12 January 2027, driving migration projects.</p>

<h3>Microsoft Entra ID</h3>
<p>The name for Azure Active Directory since Microsoft's July 2023 rename.</p>

<h3>AZ-104 certification</h3>
<p>Microsoft Azure Administrator Associate, valid for one year with free online renewal.</p>

<h3>UAE overtime law</h3>
<p>At most two hours a day, paid at least 25 per cent extra on basic wage.</p>

<h3>Linux administrator jobs UAE</h3>
<p>RHCSA is the common credential, tested with a hands-on exam.</p>

<h3>UAE Green visa for IT professionals</h3>
<p>Five years, self-sponsored, with a bachelor's degree and a salary of at least AED 15,000 a month.</p>

<h2>More Job Guides</h2>

<p>Comparing IT roles and Gulf routes? These cover them:</p>

<ul>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the entry-level route into infrastructure work, and the certifications that count.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; the first rung before system administration.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; the neighbouring infrastructure specialism.</li>
    <li><a href="/blog/cloud-engineer-jobs-in-usa">Cloud Engineer Jobs in USA</a> &mdash; where Azure administration experience leads.</li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a> &mdash; the Green visa, the DIFC and ADGM rules and the tax deadlines in full.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; the gratuity arithmetic on a basic salary.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; shift work under the same labour law.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Labour law, Emiratisation targets, visa conditions, product lifecycles and certification rules change. Confirm the current position with MOHRE, the UAE visa authorities, Microsoft and the employer before applying or accepting an offer.</p>
HTML;
    }
}
