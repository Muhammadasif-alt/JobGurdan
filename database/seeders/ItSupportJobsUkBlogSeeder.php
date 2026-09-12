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
 * "IT Support Jobs in UK" — first and second line support, service desks and
 * desktop roles. It sits beside the electrician and delivery driver guides for
 * the UK, using the same National Living Wage arithmetic, and beside the US
 * network, cloud and cybersecurity guides that support careers lead into.
 *
 * Corrections to the draft:
 *
 * 1. It quotes entry-level help desk pay from GBP 20,000 and technicians from
 *    GBP 24,000. At the GBP 12.71 National Living Wage from 1 April 2026, a
 *    37.5-hour week is GBP 24,784.50 a year, so both floors are below the legal
 *    minimum for a full-time worker aged 21 or over.
 *
 * 2. It says nothing about visas, which is the first question for most readers
 *    outside the UK. IT user support technicians (SOC 3132) and IT operations
 *    technicians (SOC 3131) are skilled below RQF level 6, but both are on the
 *    Temporary Shortage List for certificates of sponsorship issued before
 *    31 December 2026. The salary must still reach GBP 41,700, or GBP 33,400
 *    for a new entrant, which is above every help desk and technician figure
 *    the draft quotes.
 *
 * 3. It lists CompTIA A+ as a single certification. Since V15 launched on
 *    25 March 2025 it is two exams, Core 1 (220-1201) and Core 2 (220-1202).
 *
 * 4. It omits security vetting. Public sector and many managed service
 *    contracts need BPSS or SC clearance, and SC normally expects five years
 *    of UK residence.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ItSupportJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-it-support-jobs.html';

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
        $title = 'IT Support Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A £20,000 help desk salary is below the legal minimum for a full-time worker aged 21 or over, CompTIA A+ is two exams, and a sponsored support job has to pay at least £33,400, above every figure the guides quote.',
                'content' => $content,
                'featured_image' => 'blogs/it-support-jobs-in-uk.jpg',
                'tags' => 'it support jobs uk, help desk jobs uk, it support technician salary uk, service desk analyst jobs, first line support jobs, second line support engineer, comptia a+ uk, it support jobs london, it support visa sponsorship uk',
                'meta_title' => 'IT Support Jobs in UK 2026: Pay, Certificates and Visas',
                'meta_description' => 'IT support jobs in the UK: why a GBP 20,000 help desk salary is below the legal minimum, which certificates matter, and the sponsorship rule since July 2025.',
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
            ['name' => 'UK Service Desks, MSPs & In-House IT Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-it-support-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'IT Support Technician — First and Second Line, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Business hours, with shift or on-call cover on some service desks',
                'language' => 'English',
                // Advertised ranges start below the National Living Wage for a
                // full-time adult, so no range is quoted that the law would not
                // support.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'First and second line IT support roles across the UK. Sponsorship needs the Temporary Shortage List and a salary of at least GBP 33,400.',
                'seo_keywords' => 'it support jobs uk, help desk jobs uk, service desk analyst, it support technician salary uk, second line support jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Managed service providers, in-house IT teams, the NHS, councils, schools, universities and businesses of every size hire IT support staff across the UK. It is one of the most common first jobs in technology, and one of the few that does not ask for a degree.</p>

<h3>What the work involves</h3>
<p>Logging and resolving support tickets, resetting passwords and accounts, setting up laptops, phones and printers, troubleshooting Windows, macOS and Microsoft 365, and escalating network and server problems. Second line roles add deeper troubleshooting, user and device administration, and on-site visits.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>The right to work in the UK</strong>, or a sponsored role that meets the Skilled Worker salary rules while IT support stays on the Temporary Shortage List</li>
    <li>Practical troubleshooting on Windows, macOS and common business software</li>
    <li>CompTIA A+ (Core 1 and Core 2), or Microsoft or ITIL certification, where the employer asks for it</li>
    <li>Clear communication with users who are not technical</li>
    <li>BPSS or SC security clearance for public sector and some contract roles</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The legal floor.</strong> The National Living Wage is &pound;12.71 an hour from 1 April 2026, which is &pound;24,784.50 a year on a 37.5-hour week for a worker aged 21 or over</li>
    <li><strong>Above it.</strong> Second line, network and team lead roles pay more, with London and the South East at the top</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the weekly hours against the salary.</strong> A full-time offer below &pound;24,784.50 for a worker aged 21 or over is below the National Living Wage. If you need sponsorship, the job must pay at least &pound;41,700, or &pound;33,400 for a new entrant, with the certificate of sponsorship issued before 31 December 2026 while the occupation is on the Temporary Shortage List.</p>

<p><strong>Note:</strong> pay, working hours, clearance and eligibility are set by each employer and by UK law and immigration rules &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>IT support is one of the most common first jobs in UK technology. Every business that runs laptops, email and a network needs someone to keep them working, and the job rarely asks for a degree. It is also a job where the salary ranges most guides publish start below the legal minimum, where the certificate they name is two exams rather than one, and where the question most readers outside the UK are asking &mdash; can I get a visa for it? &mdash; has a clear answer the guides leave out.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-it-support-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Browse IT Support Jobs in UK &rarr;
    </a>
</div>

<h2>The Bottom of the Salary Range Is Below the Legal Minimum</h2>

<p>Guides put UK IT support pay in four bands:</p>

<ul>
    <li><strong>Entry-level help desk or service desk:</strong> &pound;20,000 to &pound;26,000 a year</li>
    <li><strong>IT support technician:</strong> &pound;24,000 to &pound;32,000</li>
    <li><strong>Second line or network support engineer:</strong> &pound;30,000 to &pound;40,000</li>
    <li><strong>IT support team lead or manager:</strong> &pound;38,000 to &pound;50,000 or more</li>
</ul>

<p>Now put the bottom two floors against the law. The <strong>National Living Wage</strong>, the minimum for workers aged 21 and over, is <strong>&pound;12.71 an hour</strong> from 1 April 2026. On a standard 37.5-hour week over 52 weeks, that is <strong>&pound;24,784.50</strong> a year.</p>

<ul>
    <li>The help desk floor of &pound;20,000 is <strong>&pound;4,784.50 short</strong> of it.</li>
    <li>The technician floor of &pound;24,000 is <strong>&pound;784.50 short</strong> of it.</li>
</ul>

<p>For a full-time employee aged 21 or over, <strong>neither figure is a lawful salary</strong>. They only work for someone younger, an apprentice on the apprentice rate, or someone on fewer weekly hours. So when you see a full-time support job at &pound;20,000, ask two questions before anything else: <strong>how many hours a week</strong>, and <strong>is this an apprenticeship</strong>? If the answers are "37.5" and "no", the offer is below the legal minimum.</p>

<h2>Sponsorship Is Possible, but Not at These Salaries</h2>

<p>If you are outside the UK, this is the section that matters most, and the guides do not mention it.</p>

<p>Since <strong>22 July 2025</strong>, the Skilled Worker visa has required a job skilled to <strong>RQF level 6</strong>, broadly degree level, for new applicants. The Home Office classifies both support occupations below that:</p>

<ul>
    <li><strong>3132 IT user support technicians</strong> &mdash; help desk, service desk and desktop support</li>
    <li><strong>3131 IT operations technicians</strong> &mdash; including network and systems administrators at technician level</li>
</ul>

<p>Both codes sit in <strong>Table 1a</strong> of Appendix Skilled Occupations, the list of occupations skilled to RQF level 3 to 5. On their own, those codes are open only to workers who already held Skilled Worker permission from before 22 July 2025. What keeps IT support open to new applicants is the <strong>Temporary Shortage List</strong> in Appendix Skilled Worker: both codes are on it, for applications made with a certificate of sponsorship issued <strong>before 31 December 2026</strong>.</p>

<p>The catch is the salary. A sponsored job must pay at least:</p>

<ul>
    <li><strong>&pound;41,700 a year</strong> and the going rate for the occupation, which is <strong>&pound;33,400</strong> for IT user support technicians; or</li>
    <li><strong>&pound;33,400 a year</strong> and 70 per cent of the going rate, if you qualify as a <strong>new entrant</strong> at the start of your career.</li>
</ul>

<p>Every figure the guides quote for help desk and technician work, from &pound;20,000 to &pound;32,000, is below &pound;33,400. The occupation is eligible, but <strong>a support job paying the usual rates cannot meet the salary rules</strong>. In plain terms:</p>

<ul>
    <li><strong>Living outside the UK?</strong> Sponsorship is realistic only for a role paying at least &pound;41,700, or &pound;33,400 if you count as a new entrant, with the certificate issued before 31 December 2026. A recruiter offering a sponsored &pound;24,000 help desk job is offering something the rules do not allow.</li>
    <li><strong>Already in the UK with the right to work</strong> &mdash; on a Graduate visa, as a dependant, or with settled or pre-settled status? You can apply like any other candidate.</li>
    <li><strong>Already a sponsored support technician since before 22 July 2025?</strong> You are inside the transitional rules and can extend or change sponsor in the same code.</li>
</ul>

<p>The Temporary Shortage List is temporary by name. Unless the Home Office extends it, certificates issued after 31 December 2026 leave the support codes with the transitional route only.</p>

<p>The positive side is the direction of travel. Degree-level technology occupations such as <strong>2137 IT network professionals</strong> and <strong>2135 cyber security professionals</strong> remain on the main list, subject to the salary rules. Moving from support into networking or security is also moving into an occupation that does not depend on a temporary list.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/it-support-jobs-in-uk-helpdesk.jpg"
         alt="A service desk analyst wearing a headset works through a ticket with a colleague in a London office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>First, Second and Third Line</h2>

<p>UK job titles in IT support vary wildly, but almost every team is organised in lines of escalation. Knowing which line an advert means tells you more than its title.</p>

<ul>
    <li><strong>First line (service desk or help desk).</strong> Takes calls, chats and tickets, resets passwords and accounts, fixes common problems and escalates the rest. Most entry-level jobs, and most of the shift and weekend work.</li>
    <li><strong>Second line (desktop or technical support).</strong> Deeper troubleshooting, device and user administration in Microsoft 365 and Active Directory or Entra ID, software deployment and on-site visits.</li>
    <li><strong>Third line (infrastructure).</strong> Servers, networks, cloud platforms and the problems the first two lines cannot solve. Usually titled as an engineer or administrator role.</li>
</ul>

<p>Where you work changes the job as much as the line. A <strong>managed service provider</strong> (MSP) supports many client businesses at once, which means variety, pace and fast learning. An <strong>in-house team</strong> at one organisation &mdash; an NHS trust, a council, a school or university, or a large company &mdash; usually means steadier hours and deeper knowledge of one environment.</p>

<h2>Certificates: What CompTIA A+ Actually Involves</h2>

<p>Guides list "CompTIA A+" as if it were one exam. It is two. Since the current version, <strong>V15</strong>, launched on 25 March 2025, you must pass both:</p>

<ul>
    <li><strong>Core 1 (220-1201)</strong> &mdash; hardware, mobile devices, networking and troubleshooting</li>
    <li><strong>Core 2 (220-1202)</strong> &mdash; operating systems, security, software troubleshooting and operational procedures</li>
</ul>

<p>Budget for two exam fees and two sets of preparation. CompTIA certifications are valid for three years and are renewed through continuing education.</p>

<p>After A+, the certificates UK employers name most often are:</p>

<ul>
    <li><strong>CompTIA Network+</strong> &mdash; the bridge from support to network roles.</li>
    <li><strong>Microsoft certifications</strong> &mdash; Microsoft 365 Fundamentals to start, and Endpoint Administrator for second line work.</li>
    <li><strong>ITIL 4 Foundation</strong> &mdash; the service management framework most UK service desks run on.</li>
</ul>

<p>A certificate gets your CV past a filter. What wins the interview is showing you can troubleshoot calmly and explain a fix to someone who is not technical, so bring real examples: a home lab, a family business network, or a problem you solved in a previous job.</p>

<h2>Security Clearance Can Matter as Much as a Certificate</h2>

<p>This is the other requirement most guides skip. A large share of UK support work is for the public sector, defence, policing, and the managed service providers that hold those contracts &mdash; and those roles need security vetting.</p>

<ul>
    <li><strong>BPSS</strong>, the Baseline Personnel Security Standard, is the entry level: identity, right to work, employment history and a criminal record declaration.</li>
    <li><strong>SC</strong>, Security Check, is needed for roles with more sensitive access. Government guidance says applicants should <strong>normally have been resident in the UK for the last five years</strong>, although a shorter residence is not automatically a bar.</li>
    <li>Support roles in schools and healthcare may also need a <strong>DBS check</strong>.</li>
</ul>

<p>If you have recently moved to the UK, focus first on private sector employers and MSPs without government contracts, where clearance is less likely to decide the outcome.</p>

<h2>Where the Jobs Are</h2>

<ul>
    <li><strong>London and the South East</strong> &mdash; the most roles and the highest pay, especially in finance, law and corporate head offices.</li>
    <li><strong>Manchester and the North West</strong> &mdash; a large technology and MSP sector.</li>
    <li><strong>Birmingham and the Midlands</strong> &mdash; steady demand from corporate, public sector and industrial employers.</li>
    <li><strong>Leeds and Yorkshire</strong> &mdash; financial services, health and public sector IT.</li>
    <li><strong>Glasgow and Edinburgh</strong> &mdash; financial services, government and a growing technology sector.</li>
    <li><strong>Bristol and the South West</strong> &mdash; aerospace, defence and technology companies, where clearance often applies.</li>
</ul>

<p>Fully remote first line roles exist but are fewer than hybrid ones, because desktop support still means setting up hardware in person. A remote UK role still needs the right to work in the UK, and employers rarely allow the work to be done from another country.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/it-support-jobs-in-uk-second-line.jpg"
         alt="An IT support team troubleshooting computers and a server rack in an office overlooking the London skyline"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree to work in IT support in the UK?</h3>
<p>No. Most first and second line employers look for troubleshooting ability, clear communication and a certificate such as CompTIA A+ rather than a degree.</p>

<h3>How much do IT support jobs pay in the UK?</h3>
<p>Guides quote &pound;20,000 to &pound;26,000 for entry-level help desk roles, rising to &pound;38,000 to &pound;50,000 or more for team leads. For a full-time worker aged 21 or over, nothing below &pound;24,784.50 a year on a 37.5-hour week is lawful.</p>

<h3>Is &pound;20,000 a legal salary for a full-time IT support job?</h3>
<p>Not for a worker aged 21 or over on 37.5 hours a week. The National Living Wage of &pound;12.71 an hour comes to &pound;24,784.50 a year, so &pound;20,000 is &pound;4,784.50 short.</p>

<h3>Can I get a UK visa for an IT support job?</h3>
<p>Only if the salary is high enough. IT user support technicians (3132) and IT operations technicians (3131) are on the Temporary Shortage List for certificates of sponsorship issued before 31 December 2026, but the job must pay at least &pound;41,700, or &pound;33,400 for a new entrant.</p>

<h3>Is CompTIA A+ one exam or two?</h3>
<p>Two. The current V15 version, launched on 25 March 2025, needs both Core 1 (220-1201) and Core 2 (220-1202).</p>

<h3>Which IT support certificate should I get first?</h3>
<p>CompTIA A+ for most people, followed by Network+, a Microsoft certification or ITIL 4 Foundation depending on the roles you are targeting.</p>

<h3>Do IT support jobs need security clearance?</h3>
<p>Many public sector and contract roles do. BPSS is the baseline, and SC clearance normally expects five years of UK residence.</p>

<h3>Can I do a UK IT support job remotely from another country?</h3>
<p>Rarely. Employers need you to have the right to work in the UK, and most will not let the work be done from abroad.</p>

<h2>People Also Search For</h2>

<h3>Help desk jobs in London</h3>
<p>The largest cluster of first line roles in the UK. Check the weekly hours against the salary before you accept.</p>

<h3>First line support salary UK</h3>
<p>Often quoted from &pound;20,000, which is below the National Living Wage for a full-time worker aged 21 or over.</p>

<h3>Second line support engineer jobs</h3>
<p>Deeper troubleshooting and device administration, and the usual next step after a year or two on a service desk.</p>

<h3>IT support jobs with visa sponsorship UK</h3>
<p>Possible on the Temporary Shortage List until 31 December 2026, but only at &pound;41,700, or &pound;33,400 for a new entrant.</p>

<h3>IT support apprenticeship UK</h3>
<p>A paid route in with training, on the apprentice rate rather than the National Living Wage.</p>

<h3>CompTIA A+ Core 1 and Core 2</h3>
<p>The two exams, 220-1201 and 220-1202, that make up the current A+ certification.</p>

<h3>Service desk analyst jobs</h3>
<p>First line roles by another name, common at MSPs, banks and large public sector organisations.</p>

<h3>IT technician jobs in schools</h3>
<p>In-house support for classrooms and staff, usually on school hours and with a DBS check.</p>

<h2>More Job Guides</h2>

<p>Comparing UK routes or the technical careers support leads into? These cover them:</p>

<ul>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a UK trade with a real qualification barrier, priced against the same legal minimum.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the National Living Wage arithmetic in a job with no qualification barrier.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the same RQF level 6 rule applied to operative roles.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; the sector where UK sponsorship is genuinely available.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; the career most support technicians grow into.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the security route from a support background.</li>
    <li><a href="/blog/cloud-engineer-jobs-in-usa">Cloud Engineer Jobs in USA</a> &mdash; where much of the infrastructure work is moving.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; support skills without the technical escalation.</li>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; the same first-line work in America, and why the BLS projects it to shrink.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; public sector pay scales and the pension behind them.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Minimum wage rates, immigration rules, occupation codes, certification requirements and security vetting policies change. Confirm the current position with GOV.UK, the certification body and the employer before applying or accepting an offer.</p>
HTML;
    }
}
