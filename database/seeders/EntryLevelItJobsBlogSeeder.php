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
 * "Entry Level IT Jobs" — the hub for first jobs in American IT. The help
 * desk, network engineer, cybersecurity analyst and web developer guides own
 * each role in depth; this one owns the first-job comparison: BLS starting
 * pay (the lowest-paid tenth) against the medians, which roles are genuinely
 * entry level, the outlook for support work, and the current certification
 * exam versions.
 *
 * Corrections to the draft:
 *
 * 1. Its entry bands sit at or below the BLS May 2025 tenth percentiles. The
 *    lowest-paid tenth of network and computer systems administrators earned
 *    under $62,640 against its $45,000 to $60,000 band, and of information
 *    security analysts under $75,090 against its $55,000 to $70,000 band.
 *
 * 2. It says entry-level IT roles remain in steady demand. BLS projects
 *    employment of computer support specialists, the largest entry route, to
 *    fall 3 per cent from 2025 to 2035.
 *
 * 3. It lists junior cybersecurity analyst as an entry-level job. BLS gives a
 *    bachelor's degree plus work experience in a related occupation as the
 *    usual path into information security analysis.
 *
 * 4. It names CompTIA A+ without the exam change. The current V15 exams are
 *    220-1201 and 220-1202, launched on 25 March 2025.
 *
 * 5. Its apply link searches Indeed with a query string rather than the
 *    site's own entry level IT search page.
 *
 * 6. A later draft claims entry-level IT hiring is "up" year on year with an
 *    average of "$83,000 to $88,000". BLS shows the largest entry route,
 *    computer support, shrinking 3 per cent to 2035, and $83k-$88k sits above
 *    the median for every genuine first IT job here. This guide keeps the
 *    measured pay and adds only what is defensible from the 2026 shift:
 *    experience inflation on "entry level" postings and new demand around
 *    cloud and data roles, not an inflated average.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EntryLevelItJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-entry-level-it-jobs.html';

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
        $title = 'Entry Level IT Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Help desk pays a BLS median of $61,860 but support jobs are projected to shrink 3 per cent, junior cyber bands sit below the lowest-paid tenth because security roles usually need experience first, and CompTIA A+ moved to the 220-1201 exams.',
                'content' => $content,
                'featured_image' => 'blogs/entry-level-it-jobs.jpg',
                'tags' => 'entry level it jobs, entry level it jobs salary, it jobs no experience, help desk jobs entry level, comptia a+ 220-1201, comptia network+ n10-009, comptia security+ sy0-701, google it support certificate, junior cybersecurity analyst, it support specialist salary',
                'meta_title' => 'Entry Level IT Jobs 2026: Real Pay, Certifications, Outlook',
                'meta_description' => 'Entry level IT jobs in the USA: BLS pay for help desk, QA, sysadmin and security roles, the A+ V15 exams, why support jobs shrink and cyber needs experience.',
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
            ['name' => 'U.S. IT Departments, Managed Service Providers & Software Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-entry-level-it-aggregated']
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
                'position' => 'Entry Level IT — Help Desk, IT Support, QA Testing and Junior Technician Roles, U.S. Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time business hours; support desks often add evening, weekend or on-call shifts',
                'language' => 'English',
                // Starting pay differs by role and region, so no single range
                // is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry level IT roles with U.S. employers: help desk, desktop support, QA testing and junior technician positions.',
                'seo_keywords' => 'entry level it jobs, help desk jobs, desktop support technician jobs, junior qa tester jobs, it technician jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Corporate IT departments, managed service providers, hospitals, schools, government agencies and software companies across the United States hire for entry level help desk, desktop support, QA testing and field technician roles.</p>

<h3>What the work involves</h3>
<p>Resolving tickets by phone, chat and in person, setting up devices and accounts, basic network troubleshooting, testing software against test cases, and documenting fixes in a ticketing system.</p>

<h3>Requirements</h3>
<ul>
    <li>Some college, an associate degree or relevant certifications such as CompTIA A+ (current exams 220-1201 and 220-1202)</li>
    <li>Troubleshooting skills and clear communication with non-technical users</li>
    <li>For QA and development roles, a portfolio or projects showing testing or coding work</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Measured pay.</strong> The BLS May 2025 median for computer user support specialists is $61,860, with the lowest-paid tenth under $40,980</li>
    <li><strong>Shift work.</strong> Help desks with extended hours may pay differentials for evenings and weekends</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask what the ticket volume and escalation path look like,</strong> and whether the employer pays for certification exams.</p>

<p><strong>Note:</strong> pay, requirements and training support are set by employers &mdash; not by JobGader. Confirm the details with the employer before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>IT is still one of the few well-paid fields you can enter with certifications and projects rather than a four-year degree. But "entry level IT" covers very different jobs, and the market for first jobs has changed. Before you pick a certification or send applications, it helps to know what each first job really pays, which roles are genuinely open to beginners, where the number of jobs is growing or shrinking, and which exam versions are current.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-entry-level-it-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Browse Entry Level IT Jobs &rarr;
    </a>
</div>

<h2>What Entry Level IT Jobs Pay</h2>

<p>Guides quote $38,000 to $48,000 for help desk, $50,000 to $65,000 for junior developers and QA, $45,000 to $60,000 for junior network and systems administrators, and $55,000 to $70,000 for junior cybersecurity roles. BLS publishes the pay of the <strong>lowest-paid tenth</strong> of each occupation, a fair proxy for starting pay. May 2025 figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Lowest 10 per cent earn less than</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">Guide's entry band</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Computer user support specialists (help desk)</td><td style="padding:10px;">$40,980</td><td style="padding:10px;">$61,860</td><td style="padding:10px;">$38,000 &ndash; $48,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Computer network support specialists</td><td style="padding:10px;">&mdash;</td><td style="padding:10px;">$76,220</td><td style="padding:10px;">$45,000 &ndash; $60,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Network and computer systems administrators</td><td style="padding:10px;">$62,640</td><td style="padding:10px;">$99,130</td><td style="padding:10px;">$45,000 &ndash; $60,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Web developers</td><td style="padding:10px;">$48,100</td><td style="padding:10px;">$92,650</td><td style="padding:10px;">$50,000 &ndash; $65,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Software quality assurance analysts and testers</td><td style="padding:10px;">$61,440</td><td style="padding:10px;">$104,300</td><td style="padding:10px;">$50,000 &ndash; $65,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Information security analysts</td><td style="padding:10px;">$75,090</td><td style="padding:10px;">$129,180</td><td style="padding:10px;">$55,000 &ndash; $70,000</td></tr>
    </tbody>
</table>
</div>

<ul>
    <li><strong>Help desk is the realistic starting band.</strong> The guide's range sits around the lowest tenth, which fits a first job.</li>
    <li><strong>The systems administrator and cybersecurity bands are below the lowest tenth.</strong> That is a sign these are not usually first jobs, not that they pay badly.</li>
    <li><strong>Data entry is not an IT occupation.</strong> BLS counts it as office work, with a median of $41,340.</li>
</ul>

<h2>Which Jobs Are Genuinely Entry Level</h2>

<ul>
    <li><strong>Help desk and IT support.</strong> BLS says user support specialists often need only some college or a high school diploma plus IT certifications. This is the main door in.</li>
    <li><strong>Network support.</strong> Usually an associate degree, often after help desk experience.</li>
    <li><strong>QA testing and junior web development.</strong> Open to beginners with a portfolio, but competitive.</li>
    <li><strong>Systems administration.</strong> Usually reached after support experience, as the pay floor above suggests.</li>
    <li><strong>Cybersecurity analyst.</strong> Guides list "junior cybersecurity analyst" as entry level. BLS gives a <strong>bachelor's degree plus work experience in a related occupation</strong> as the typical path, usually from help desk, networking or systems work.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/entry-level-it-jobs-desk.jpg"
         alt="Three young entry level IT workers collaborating around a laptop in a bright office, code on a monitor behind them, a US flag and the New York skyline through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Outlook: Support Is Shrinking, Security Is Growing</h2>

<p>Guides say entry-level IT roles remain in steady demand. BLS projections for 2025 to 2035 are mixed:</p>

<ul>
    <li><strong>Computer support specialists: down 3 per cent.</strong> About 903,100 jobs in 2025, falling by 24,300 over the decade, as automation and self-service tools handle more tickets. There will still be about <strong>48,700 openings a year</strong> from people leaving the job.</li>
    <li><strong>Information security analysts: up 21 per cent</strong>, much faster than average, with about 14,100 openings a year, but mostly for people with experience.</li>
</ul>

<p>The practical route is to take a support job, which still has tens of thousands of openings a year, and use it to move into networking, systems, cloud or security within a few years.</p>

<h2>What the 2026 Market Shift Means</h2>

<p>The market for first IT jobs did not close in 2026, but it moved. Three shifts are worth planning around:</p>

<ul>
    <li><strong>Experience inflation.</strong> Many postings labelled "entry level" now ask for two to three years of experience. Treat those as mislabelled, and keep applying to genuine junior and help desk roles, which still hire beginners.</li>
    <li><strong>AI is absorbing routine tickets.</strong> Self-service portals and AI assistants now handle password resets and simple fixes, which is part of why BLS projects support roles to shrink. The junior seats that remain lean more on troubleshooting judgement than on ticket volume.</li>
    <li><strong>New demand around cloud and data.</strong> Cloud support built on AWS, Azure or Google Cloud fundamentals, and data-focused roles, are the fastest-growing entry-adjacent routes as companies move infrastructure to the cloud. A cloud fundamentals certificate plus a small project is a realistic way in. Note that an entry data analyst role is analytical work, not the office data entry job BLS prices at $41,340.</li>
</ul>

<p>The door has not shut; it has moved toward candidates who pair a foundational skillset with one or two relevant certifications and a hands-on project, rather than a degree alone.</p>

<h2>Certifications: The Current Versions</h2>

<ul>
    <li><strong>CompTIA A+.</strong> The current V15 version is two exams, <strong>220-1201 (Core 1) and 220-1202 (Core 2)</strong>, launched on <strong>25 March 2025</strong>. Study guides for 220-1101 and 220-1102 cover the previous version.</li>
    <li><strong>CompTIA Network+.</strong> The current exam is <strong>N10-009</strong>, for networking fundamentals.</li>
    <li><strong>CompTIA Security+.</strong> The current exam is <strong>SY0-701</strong>, a common baseline for security roles.</li>
    <li><strong>Google IT Support Professional Certificate.</strong> An online course for help desk fundamentals, useful alongside A+.</li>
    <li><strong>Cloud fundamentals.</strong> Entry cloud certifications from AWS, Microsoft and Google help support staff move toward cloud roles.</li>
</ul>

<p>CompTIA certifications are valid for three years and are renewed through continuing education. Pick the exam that matches the job you want next rather than collecting all of them.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/entry-level-it-jobs-coding.jpg"
         alt="A young woman and a colleague working through a task on a laptop while another IT worker reviews code on a monitor, the New York skyline and Statue of Liberty behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where to Find Entry Level IT Jobs</h2>

<ol>
    <li><strong>Job boards.</strong> Search "help desk", "desktop support" and "IT support specialist" as well as "entry level IT".</li>
    <li><strong>Managed service providers.</strong> MSPs hire many beginners and give exposure to many systems quickly.</li>
    <li><strong>Hospitals, schools and local government.</strong> Stable IT departments that hire support staff outside the tech industry.</li>
    <li><strong>Apprenticeships and internships.</strong> Registered IT apprenticeships and internships combine pay with training.</li>
    <li><strong>Staffing agencies.</strong> Contract help desk roles often convert to permanent jobs.</li>
</ol>

<h2>Tips for Landing Your First IT Job</h2>

<ul>
    <li><strong>Build a home lab.</strong> Set up a small network, a virtual machine and Active Directory, and write down what you did.</li>
    <li><strong>Match the certification to the job.</strong> A+ for help desk, Network+ for networking, Security+ once you aim at security.</li>
    <li><strong>Use your customer service experience.</strong> Support desks value calm, clear communication as much as technical knowledge.</li>
    <li><strong>Prepare for troubleshooting questions.</strong> Expect "a user cannot connect to the internet, what do you check?"</li>
    <li><strong>Apply outside tech companies.</strong> Health care, finance, education and government all run IT departments.</li>
</ul>

<h2>Career Progression</h2>

<p>A common path runs from help desk to desktop or network support within one to two years, then to systems administration, cloud, or security. BLS medians show why the move matters: $61,860 for user support, $99,130 for systems administrators and $129,180 for information security analysts.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do entry level IT jobs pay?</h3>
<p>For help desk work, the lowest-paid tenth of computer user support specialists earned under $40,980 in May 2025 and the median was $61,860. Starting pay for other IT roles is higher but usually needs experience.</p>

<h3>Can I get an IT job without a degree?</h3>
<p>Yes, especially in support. BLS says user support specialists may qualify with some college or a high school diploma plus IT certifications.</p>

<h3>Is cybersecurity an entry level job?</h3>
<p>Rarely. BLS gives a bachelor's degree plus work experience in a related occupation as the typical path into information security analysis.</p>

<h3>Are IT support jobs growing?</h3>
<p>No. BLS projects employment of computer support specialists to fall 3 per cent from 2025 to 2035, though about 48,700 openings a year will still arise from turnover.</p>

<h3>What are the current CompTIA A+ exams?</h3>
<p>The V15 exams, 220-1201 (Core 1) and 220-1202 (Core 2), launched on 25 March 2025.</p>

<h3>Which certification should I get first?</h3>
<p>CompTIA A+ for help desk roles, then Network+ or Security+ depending on whether you are aiming at networking or security.</p>

<h3>Is QA testing a good entry level IT job?</h3>
<p>It can be. The lowest-paid tenth of software QA analysts and testers earned under $61,440 and the median was $104,300, but roles are competitive.</p>

<h3>What is the best first IT job?</h3>
<p>Help desk or IT support. It has the most openings for beginners and leads to networking, systems, cloud and security roles.</p>

<h2>People Also Search For</h2>

<h3>IT jobs with no experience</h3>
<p>Help desk and support roles, starting around the lowest tenth of $40,980.</p>

<h3>Help desk salary entry level</h3>
<p>A BLS median of $61,860 for user support specialists.</p>

<h3>CompTIA A+ 220-1201</h3>
<p>Core 1 of the current V15 A+ certification.</p>

<h3>Junior cybersecurity analyst jobs</h3>
<p>Usually need related IT experience first.</p>

<h3>Google IT Support Certificate jobs</h3>
<p>Help desk and support roles, often alongside A+.</p>

<h3>Entry level network technician</h3>
<p>Network support, usually with an associate degree or help desk experience.</p>

<h3>Junior QA tester jobs</h3>
<p>Testing roles with a $61,440 lowest-tenth pay level.</p>

<h3>IT support jobs outlook</h3>
<p>Down 3 per cent over the decade to 2035.</p>

<h2>More Job Guides</h2>

<p>Ready to go deeper on one role? These cover them:</p>

<ul>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a> &mdash; the most common first IT job in detail.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; where networking experience leads.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the security role and what it takes to get there.</li>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a> &mdash; the development route and its pay.</li>
    <li><a href="/blog/qa-tester-jobs-in-canada">QA Tester Jobs in Canada</a> &mdash; testing work across the border.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the same first job in Britain.</li>
    <li><a href="/blog/how-to-apply-for-microsoft-it-support-jobs-in-the-usa">How to Apply for Microsoft IT Support Jobs in the USA</a> &mdash; the real entry-level pay band at Microsoft, and the categories that have no jobs in them.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers advice. Wage data, employment projections and certification exam versions change. Confirm current details with BLS, CompTIA and employers before choosing a course or applying.</p>
HTML;
    }
}
