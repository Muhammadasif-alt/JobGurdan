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
 * "Help Desk Technician Jobs in USA" — computer user support, measured by the
 * BLS as 15-1232. Most readers want a first IT job, so the outlook and the
 * certification get as much space as the pay.
 *
 * Corrections to the draft:
 *
 * 1. Its Tier 1 floor of $35,000 is below the lowest-paid tenth of computer
 *    user support specialists, who earned under $40,980 in May 2025. The
 *    median is $61,860.
 *
 * 2. It calls demand high. The BLS projects employment of user support
 *    specialists to fall 3% from 2025 to 2035 as organisations automate
 *    troubleshooting with tools such as chatbots; the openings that remain
 *    come from replacing people who leave.
 *
 * 3. It lists Virginia among the top five states. Virginia is 12th by
 *    employment; Pennsylvania is fifth. The District of Columbia pays the
 *    highest mean.
 *
 * 4. It says a high school diploma or GED is enough. The BLS says user
 *    support specialists typically need some college, and a high school
 *    diploma qualifies when paired with IT certifications.
 *
 * 5. It recommends CompTIA A+ without saying the exams changed: the V15
 *    series (220-1201 and 220-1202) launched on 25 March 2025.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HelpDeskTechnicianJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-help-desk-technician-jobs.html';

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
        $title = 'Help Desk Technician Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The BLS median for help desk and user support specialists is $61,860, and $35,000 is below the lowest-paid tenth. Virginia is 12th for jobs, not top five, and the BLS projects a 3% decline to 2035 as chatbots take routine tickets.',
                'content' => $content,
                'featured_image' => 'blogs/help-desk-technician-jobs-in-usa.jpg',
                'tags' => 'help desk technician jobs usa, help desk salary, computer user support specialist salary, it help desk jobs entry level, comptia a+ v15, tier 1 help desk jobs, desktop support technician salary, remote help desk jobs',
                'meta_title' => 'Help Desk Technician Jobs in USA 2026: Pay, A+ and Outlook',
                'meta_description' => 'Help desk technician jobs in the USA: the BLS median of $61,860, why $35,000 is below the bottom tenth, the new A+ V15 exams and a projected 3% decline.',
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
            ['name' => 'US Service Desks, MSPs & In-House IT Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-help-desk-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Help Desk Technician — Tier 1 and Tier 2 IT Support, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Business hours in most posts, with shift, evening and on-call rotas at 24/7 service desks',
                'language' => 'English',
                // User support pay runs from a lowest-paid tenth under $40,980
                // to a District of Columbia mean of $85,690, so no single range
                // is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Help desk, service desk and desktop support roles with US employers, managed service providers and government contractors. Tier 1 and Tier 2 support.',
                'seo_keywords' => 'help desk technician jobs usa, it help desk jobs, desktop support jobs, service desk analyst jobs, comptia a+ jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Companies, managed service providers, hospitals, schools, universities and government contractors across the United States recruit help desk and desktop support technicians throughout the year, on site, hybrid and remote.</p>

<h3>What the work involves</h3>
<p>Answering support tickets, calls and chats, troubleshooting hardware, software, accounts and network connections, setting up devices, escalating complex problems and documenting fixes in a knowledge base.</p>

<h3>Requirements</h3>
<ul>
    <li>Some college, or a high school diploma plus IT certifications such as <strong>CompTIA A+</strong></li>
    <li>Working knowledge of Windows, macOS, Microsoft 365 and common business applications</li>
    <li>Clear communication with non-technical users</li>
    <li>For some government contractor roles, certifications that meet DoD 8140 work role requirements</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured median.</strong> $61,860 a year, or $29.74 an hour, in May 2025, according to the BLS</li>
    <li><strong>Location.</strong> The District of Columbia and California have the highest mean wages, above $85,000</li>
    <li><strong>Progression.</strong> Network support specialists have a median of $76,220</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check which A+ exam version a course teaches.</strong> The current exams are 220-1201 and 220-1202.</p>

<p><strong>Note:</strong> pay, certification and security requirements are set by each employer and contract &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Help desk jobs are still one of the most common ways into IT in the United States. You can start without a degree, learn on real systems and move on to networking, cloud or security. But the usual advice lowballs the pay, misses the change in the A+ exams and describes a job market the government's own projections do not support.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-help-desk-technician-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Browse Help Desk Technician Jobs in USA &rarr;
    </a>
</div>

<h2>Help Desk Pay Is Higher Than the Guides Say</h2>

<p>Guides put Tier 1 technicians at <strong>$35,000 to $45,000</strong>, Tier 2 at $42,000 to $55,000 and desktop support at $45,000 to $58,000. The Bureau of Labor Statistics counts help desk and desktop support staff as <strong>computer user support specialists</strong>, and measured this across 717,190 of them in May 2025:</p>

<ul>
    <li><strong>Median:</strong> <strong>$61,860</strong> a year, or $29.74 an hour</li>
    <li><strong>Mean:</strong> $67,330</li>
    <li><strong>Lowest-paid tenth:</strong> under <strong>$40,980</strong></li>
    <li><strong>Lowest-paid quarter:</strong> under $49,000</li>
    <li><strong>Highest-paid quarter:</strong> over $79,040, and the top tenth over $100,540</li>
</ul>

<p>A <strong>$35,000</strong> offer is below what the lowest-paid tenth earns, and the guides' Tier 2 and desktop support bands both sit below the median. Their team lead band of $55,000 to $70,000 is closer to the middle of the market than to the top.</p>

<p>The next step up pays more. <strong>Computer network support specialists</strong> have a median of <strong>$76,220</strong>.</p>

<h2>The Top States: Virginia Is Not One of Them</h2>

<p>Guides name California, Texas, New York, Florida and Virginia. By employment, the BLS top five are:</p>

<ul>
    <li><strong>California:</strong> 74,490 user support specialists</li>
    <li><strong>Texas:</strong> 72,030</li>
    <li><strong>Florida:</strong> 51,220</li>
    <li><strong>New York:</strong> 39,100</li>
    <li><strong>Pennsylvania:</strong> 30,560</li>
</ul>

<p><strong>Virginia is 12th</strong>, with 20,910 jobs. It still pays above the national mean, at $70,650, and so does Maryland at $71,450, which reflects the government contracting work around Washington. The highest mean wages are in the <strong>District of Columbia ($85,690)</strong>, California ($85,030), Washington ($80,340), Colorado ($77,230) and Massachusetts ($76,460).</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/help-desk-technician-jobs-in-usa-tickets.jpg"
         alt="A help desk technician with a headset working through open support tickets on two monitors"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Outlook: A Projected Decline, Not High Demand</h2>

<p>Guides describe help desk work as in high demand. The BLS projects the opposite for this occupation:</p>

<ul>
    <li><strong>Computer user support specialists:</strong> employment falls <strong>3% from 2025 to 2035</strong>, a loss of 26,200 jobs.</li>
    <li><strong>The reason:</strong> the BLS says organizations continue to implement automated tools, <strong>such as chatbots</strong>, for troubleshooting.</li>
    <li><strong>Network support specialists:</strong> up 1% over the same period.</li>
    <li><strong>Openings still exist.</strong> Across computer support specialists there are about <strong>48,700 openings a year</strong>, all from replacing workers who move to other jobs or leave the workforce.</li>
</ul>

<p>That changes the strategy. The password resets and simple fixes are the tickets being automated first, so the technicians who stay employable are the ones who move towards networking, identity and access, cloud administration or security, rather than waiting in Tier 1.</p>

<h2>Do You Need a Degree?</h2>

<p>Guides say a high school diploma or GED is enough. The BLS is more precise: <strong>user support specialists typically need some college courses</strong>, and network support specialists typically need an associate degree. Candidates for either can qualify with <strong>a high school diploma plus relevant IT certifications</strong>. So the diploma works, but not on its own.</p>

<h2>CompTIA A+: Make Sure You Study the Current Exams</h2>

<p>Guides are right that A+ is the certification to get; CompTIA says it appears in more tech support job listings than any other IT credential. What they miss is that the exams changed:</p>

<ul>
    <li><strong>Current version:</strong> V15, exams <strong>220-1201 (Core 1)</strong> and <strong>220-1202 (Core 2)</strong>, launched on <strong>25 March 2025</strong>. You cannot mix a V15 exam with an older one.</li>
    <li><strong>Format:</strong> up to 90 questions in 90 minutes per exam.</li>
    <li><strong>Passing scores:</strong> 675 for Core 1 and 700 for Core 2, on a scale of 900.</li>
    <li><strong>Experience:</strong> CompTIA recommends 12 months of hands-on work.</li>
    <li><strong>Renewal:</strong> every three years, through continuing education or a later exam.</li>
</ul>

<p>Study guides written for the 220-1101 and 220-1102 exams are for the previous version.</p>

<h2>Government Contractor Help Desks</h2>

<p>Help desk roles on Defense Department contracts often require certifications that meet <strong>DoD 8140</strong>, the department's cyber workforce qualification policy. CompTIA lists A+ as approved for the technical support specialist work role under 8140. Check the contract's own requirements, because they depend on the work role and can also require a security clearance.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/help-desk-technician-jobs-in-usa-support.jpg"
         alt="A support technician wearing a headset at a service desk with a ticket queue on screen"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Help Desk Careers Lead</h2>

<ul>
    <li><strong>Network support and administration</strong> &mdash; the median rises from $61,860 to $76,220 for network support specialists.</li>
    <li><strong>Systems and cloud administration</strong> &mdash; the infrastructure behind the tickets you resolve.</li>
    <li><strong>Security operations</strong> &mdash; many analysts start by handling phishing reports and account lockouts at the help desk.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do help desk technicians make in the USA?</h3>
<p>The BLS median for computer user support specialists was $61,860 a year, or $29.74 an hour, in May 2025. The lowest-paid tenth earned under $40,980 and the highest-paid tenth over $100,540.</p>

<h3>Is $35,000 a fair salary for a Tier 1 help desk job?</h3>
<p>It is below what the lowest-paid tenth of user support specialists earned nationally in May 2025, which was $40,980.</p>

<h3>Which states have the most help desk jobs?</h3>
<p>California, Texas, Florida, New York and Pennsylvania. Virginia is 12th.</p>

<h3>Which state pays help desk technicians the most?</h3>
<p>The District of Columbia, with a mean of $85,690, followed by California, Washington, Colorado and Massachusetts.</p>

<h3>Are help desk jobs in demand?</h3>
<p>The BLS projects employment of computer user support specialists to fall 3% from 2025 to 2035 as organizations automate troubleshooting, though about 48,700 openings a year come up across computer support roles as people leave.</p>

<h3>Do I need a degree for a help desk job?</h3>
<p>Not necessarily. The BLS says user support specialists typically need some college, but a high school diploma plus relevant IT certifications can qualify.</p>

<h3>What are the current CompTIA A+ exams?</h3>
<p>The V15 exams, 220-1201 (Core 1) and 220-1202 (Core 2), launched on 25 March 2025. Each has up to 90 questions in 90 minutes.</p>

<h3>What is the passing score for CompTIA A+?</h3>
<p>675 for Core 1 and 700 for Core 2, on a scale of 100 to 900.</p>

<h2>People Also Search For</h2>

<h3>Help desk salary per hour</h3>
<p>A BLS median of $29.74 an hour for computer user support specialists in May 2025.</p>

<h3>Entry level IT help desk jobs</h3>
<p>Some college, or a high school diploma with certifications such as CompTIA A+, and no prior IT experience required by the BLS definition.</p>

<h3>Desktop support technician salary</h3>
<p>Measured with help desk staff as user support specialists: a median of $61,860.</p>

<h3>Network support specialist salary</h3>
<p>A BLS median of $76,220 in May 2025.</p>

<h3>CompTIA A+ 220-1201</h3>
<p>Core 1 of the V15 A+ exams, launched in March 2025, with a passing score of 675.</p>

<h3>Will AI replace help desk jobs?</h3>
<p>The BLS projects a 3% decline in user support jobs to 2035 and names automated tools such as chatbots as the reason.</p>

<h3>Help desk jobs in Virginia</h3>
<p>20,910 user support specialists with a mean wage of $70,650, 12th by employment.</p>

<h3>DoD 8140 help desk certification</h3>
<p>CompTIA lists A+ as approved for the technical support specialist work role under DoD 8140.</p>

<h2>More Job Guides</h2>

<p>Planning the next step in IT? These cover it:</p>

<ul>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the same first-line work in Britain, priced against the UK legal minimum.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; where many help desk careers go next.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the security route, and the clearance rules in full.</li>
    <li><a href="/blog/cloud-engineer-jobs-in-usa">Cloud Engineer Jobs in USA</a> &mdash; the infrastructure work moving off the desk.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; support skills without the technical escalation.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; another entry-level office role facing automation.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers or financial advice. Pay, projections, certification exams and contract requirements change and differ between employers and states. Confirm the current position with the employer, the BLS and CompTIA before applying or paying for training.</p>
HTML;
    }
}
