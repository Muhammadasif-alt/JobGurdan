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
 * "Cloud Engineer Jobs in USA" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup.
 *
 * The reading the guide is built around: BLS has no cloud engineer
 * occupation, and the occupation it grew out of — network and computer
 * systems administrators — is projected to *decline* 4 per cent. That decline
 * is not the work disappearing. It is the same work being re-titled and
 * re-tooled, which makes it the clearest possible signal to anyone currently
 * administering servers.
 *
 * Corrections to the draft:
 *
 * 1. It implies a cloud engineer salary figure exists. It does not. Any
 *    number quoted for the title is a job-board aggregate of self-reported
 *    pay, so the guide anchors on the three occupations this work is
 *    actually counted under and says which is which.
 *
 * 2. It says junior roles welcome candidates "learning Infrastructure as Code
 *    tools like Terraform". Learning Terraform is not the barrier; having
 *    operated systems under load is. This is usually a second job.
 *
 * 3. It lists cost optimisation last among the skills. In practice it is the
 *    one that gets a cloud engineer promoted, because it is the only part of
 *    the job with a dollar figure attached to it.
 *
 * 4. It names GovCloud and federal postings without saying those roles need a
 *    clearance. Stated briefly and linked to the analyst guide, which covers
 *    the rules properly.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CloudEngineerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-cloud-engineer-jobs.html';

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
        $title = 'Cloud Engineer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Which federal occupation this title actually belongs to, why the declining one is the best news in it for anyone administering servers today, what the work pays, and the skill that gets cloud engineers promoted ahead of the ones who know more tools.',
                'content' => $content,
                'featured_image' => 'blogs/cloud-engineer-jobs-in-usa.jpg',
                'tags' => 'cloud engineer jobs in usa, aws cloud engineer jobs, azure cloud engineer jobs, remote cloud engineer jobs, cloud infrastructure engineer jobs, cloud engineer salary usa, terraform jobs usa, finops jobs',
                'meta_title' => 'Cloud Engineer Jobs in USA',
                'meta_description' => 'Cloud engineer jobs in USA: which BLS occupation this title belongs to, why the declining one matters, what the work pays, and the skill that gets you promoted.',
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
            ['name' => 'US Cloud Platform & Infrastructure Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-cloud-platform-aggregated']
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
                'position' => 'Cloud Engineer — AWS, Azure and GCP Platform Teams, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours, with an on-call rotation on most platform teams',
                'language' => 'English',
                // Counted across three occupations paying $99,130 to $135,980
                // at the median, so no single range would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Cloud infrastructure and platform engineering roles on AWS, Azure and GCP with US employers, on-site and remote. Some require a security clearance.',
                'seo_keywords' => 'cloud engineer jobs in usa, aws cloud engineer jobs, azure cloud engineer jobs, remote cloud engineer jobs, terraform infrastructure jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Technology companies, banks, health systems, retailers and government contractors across the United States hire cloud engineers to build and run the infrastructure their applications sit on. The work is mostly AWS and Azure, increasingly written as code rather than clicked in a console, and it is one of the more genuinely remote-friendly engineering roles.</p>

<h3>What the work involves</h3>
<p>Designing and provisioning cloud environments, writing the Terraform or CloudFormation that defines them, building the pipelines that deploy into them, and owning what happens when something breaks at three in the morning. Beyond that, the parts that are less advertised and matter more: identity and access design, network boundaries, and keeping the monthly bill defensible.</p>

<h3>Requirements</h3>
<ul>
    <li>Real operational experience &mdash; systems, networks or applications you have run under load, not only built</li>
    <li>One cloud platform in depth: AWS or Azure most often, GCP in specific sectors</li>
    <li>Infrastructure as code, most often Terraform, treated as software rather than as scripts</li>
    <li>Containers and orchestration, Docker and Kubernetes, and an understanding of what they cost</li>
    <li>CI/CD pipelines, and enough scripting &mdash; usually Python &mdash; to automate anything done twice</li>
    <li>Identity and access management, which is where most cloud incidents actually begin</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>There is no federal figure for this title.</strong> The work is counted across network and computer systems administrators at a $99,130 median, computer network architects at $134,050, and software developers at $135,980</li>
    <li><strong>Which of those you resemble sets your band</strong> more than the word "cloud" does: running an estate pays like administration, designing one pays like architecture, and building platform software pays like development</li>
    <li><strong>Cleared and GovCloud roles</strong> pay a premium, because the eligible pool is small</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask who owns the cloud bill.</strong> If nobody does, you will inherit it in month three without the authority to change anything, and cost work done without a mandate is the fastest route to a frustrating year.</p>

<p><strong>Note:</strong> pay, on-call expectations, clearance requirements and any sponsorship decision are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Cloud engineering is one of the best-paid and most portable technical careers in the United States, and it is also one of the hardest to research honestly, because the job did not exist as a category long enough to be counted properly. Everything you read about what it pays is an aggregate of self-reported numbers. This guide starts by working out which occupations the work is actually counted under, which turns out to answer more than a salary question &mdash; including where the people doing these jobs came from, and where the next wave will come from.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-cloud-engineer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        ☁️ Browse Cloud Engineer Jobs in the USA &rarr;
    </a>
</div>

<h2>Which Occupation Is This, Actually?</h2>

<p><strong>The Bureau of Labor Statistics has no "cloud engineer" occupation.</strong> That is not an oversight to work around; it is genuinely useful information, because it means the work is being counted somewhere else, and which somewhere tells you what a given role really is.</p>

<p>Three occupations absorb almost all of it:</p>

<ul>
    <li><strong>Network and computer systems administrators</strong> &mdash; a <strong>$99,130 median</strong> as of May 2025, the lowest tenth under <strong>$62,640</strong> and the highest tenth above <strong>$155,050</strong>. Running the estate.</li>
    <li><strong>Computer network architects</strong> &mdash; a <strong>$134,050 median</strong>, the lowest tenth under <strong>$79,900</strong> and the highest tenth above <strong>$202,680</strong>. Designing it. BLS notes this typically needs a bachelor's degree <em>and</em> five years or more in a related occupation.</li>
    <li><strong>Software developers</strong> &mdash; a <strong>$135,980 median</strong>, the highest tenth above <strong>$214,670</strong>. Building platform tooling as software.</li>
</ul>

<p>So the practical question at offer stage is not "what do cloud engineers earn". It is <strong>which of those three the role actually is</strong>. A job that provisions and maintains environments pays like administration. A job that designs them pays like architecture. A job that builds internal platform software pays like development. The word "cloud" in the title moves the number far less than which of those three verbs the work is.</p>

<p>Read the requirements with that in mind and the pay band usually predicts itself.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cloud-engineer-jobs-in-usa-platform.jpg"
         alt="A cloud platform team working across infrastructure dashboards and code"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Declining Occupation That Feeds This One</h2>

<p>Here is the number nobody puts in a cloud careers article. BLS projects employment of <strong>network and computer systems administrators to decline 4 per cent from 2025 to 2035</strong>, with about <strong>13,400 openings a year</strong> &mdash; almost all of them replacements rather than growth.</p>

<p>Read carelessly, that looks like bad news adjacent to a good career. Read properly, it is the single most useful fact on this page, and it says two things.</p>

<p><strong>First, the work is not disappearing &mdash; it is being re-titled.</strong> Servers still need provisioning, patching, monitoring and recovering. What changed is that the server is now an API call, the configuration is now code, and the job title on the advertisement is now "cloud engineer". The decline in one occupation and the growth of these postings are largely the same phenomenon seen from two sides.</p>

<p><strong>Second, if you are administering systems today, that projection is addressed to you.</strong> A declining occupation with 13,400 mostly-replacement openings a year is not somewhere to spend a decade. And the migration is unusually achievable, because you already have the thing that is genuinely hard to acquire: operational experience of systems under load. What you are missing &mdash; Terraform, a cloud platform, pipelines &mdash; is learnable, and demonstrably so.</p>

<p>By contrast, computer network architects are projected to <strong>grow 8 per cent</strong> on <strong>9,600 openings a year</strong>. The direction of travel inside this field is from running things towards designing them, and the pay gap between those two BLS medians &mdash; <strong>$34,920</strong> &mdash; is what that journey is worth.</p>

<h2>Why "Learning Terraform" Is Not the Entry Requirement</h2>

<p>Postings for junior cloud engineers do exist, and the draft this page was built from is right that some welcome people still learning infrastructure as code. But the framing is backwards. <strong>Terraform is the easy part.</strong> It is well documented, free to practise, and a determined person can be productive in it inside a month.</p>

<p>What cannot be learned from documentation is the judgement that comes from having run something in production: what fails first under load, why the obvious fix makes the outage worse, how a change looks at three in the morning. That is why cloud engineering is usually a <strong>second job rather than a first</strong>, reached from systems administration, networking, support or development.</p>

<p>If you are starting out, the honest route is to take a role that gives you operational responsibility for something real, and do the cloud learning alongside it. If you are already in one, you are much closer than the job descriptions make you feel.</p>

<h2>Cost Is the Skill That Gets You Promoted</h2>

<p>Every listing puts cost optimisation near the bottom of the requirements. In practice it is the fastest route to seniority in this field, for a reason that has nothing to do with engineering: <strong>it is the only part of the job with a dollar figure attached.</strong></p>

<p>An engineer who improves reliability has to explain the value of an outage that did not happen. An engineer who takes 30 per cent off a monthly cloud bill has a number their manager can take to a finance meeting. Both matter; only one is legible outside the team.</p>

<p>Practically, that means learning where the money actually goes &mdash; idle compute, over-provisioned instances, storage tiers nobody reviewed, egress, forgotten environments &mdash; and being able to talk about commitments and rightsizing with the people who sign the invoice. It is unglamorous and it is the difference between "runs our infrastructure" and "runs our infrastructure and saved us $400,000 last year" on a promotion case.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cloud-engineer-jobs-in-usa-cost.jpg"
         alt="A cloud engineer reviewing cloud console usage and spending dashboards"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Certifications Do and Do Not Do</h2>

<p>Cloud certifications are more useful in this field than in most, because the platforms change fast enough that a current certificate genuinely signals current knowledge. Two honest limits.</p>

<p>They get your application read; they do not substitute for having run anything. An associate-level AWS or Azure certification alongside a real project you can describe in detail beats a professional-level certificate with nothing behind it, because the interview will go straight to what you actually did.</p>

<p>And pick the platform your target employers run, not the one with the best course. If the postings you want say Azure, an AWS certification is evidence of general aptitude rather than a match. If you are heading towards Kubernetes security specifically, note the ordering trap covered in our <a href="/blog/cybersecurity-engineer-jobs-in-usa">cybersecurity engineer guide</a>: the security exam requires the administrator one first.</p>

<h2>GovCloud, Clearance and the Federal Market</h2>

<p>A meaningful share of US cloud postings &mdash; particularly around Washington DC and Northern Virginia &mdash; are federal or defence work, and ask for AWS GovCloud experience alongside an <strong>active security clearance</strong>. A clearance requires <strong>US citizenship</strong> and an <strong>employer to sponsor it</strong>, and cannot be obtained independently.</p>

<p>If you are not a US citizen, read for that line before investing time in the application; the commercial market is large, better paid at the top end, and considerably more remote-friendly. Our <a href="/blog/cybersecurity-analyst-jobs-in-usa">cybersecurity analyst guide</a> covers the clearance rules in full.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Cloud Engineer.</strong> The general title: provisioning, automation and maintenance of cloud environments.</li>
    <li><strong>Cloud Infrastructure or Systems Engineer.</strong> Compute, storage and networking design. Closest to the architect band.</li>
    <li><strong>Cloud DevOps Engineer.</strong> Infrastructure plus the delivery pipeline. Expect to own both.</li>
    <li><strong>Site Reliability Engineer.</strong> Uptime, scaling and incident response, usually with a software engineering bar and pay to match.</li>
    <li><strong>Cloud Security Engineer.</strong> Identity, guardrails and compliance &mdash; the strongest premium of the specialisms and covered in our <a href="/blog/cybersecurity-engineer-jobs-in-usa">security engineering guide</a>.</li>
    <li><strong>Platform Engineer.</strong> Building the internal tooling other engineers deploy through. The most software-like of these, and paid accordingly.</li>
</ul>

<h2>Skills That Keep Recurring in Listings</h2>

<ul>
    <li><strong>Infrastructure as code</strong> &mdash; Terraform above CloudFormation in most postings, and treated as code that gets reviewed.</li>
    <li><strong>One cloud platform in depth</strong>, AWS or Azure, rather than three at surface level.</li>
    <li><strong>Containers and Kubernetes</strong>, including what they cost to run.</li>
    <li><strong>CI/CD</strong> &mdash; GitHub Actions, GitLab CI or Jenkins &mdash; and the discipline to keep pipelines boring.</li>
    <li><strong>Identity and access management</strong>, the most common root cause in cloud incidents and the least advertised skill.</li>
    <li><strong>Python</strong> for automation, and enough software practice to write code others maintain.</li>
    <li><strong>Cost management and capacity planning</strong> &mdash; last on their list, first on your promotion case.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do cloud engineers make in the USA?</h3>
<p>There is no federal figure for the title. The work is counted under network and computer systems administrators at a $99,130 median, computer network architects at $134,050 and software developers at $135,980. Which the role resembles matters more than the word "cloud" in it.</p>

<h3>Is cloud engineering a good career given sysadmin roles are declining?</h3>
<p>The decline is the reason to move, not a reason to worry. BLS projects network and computer systems administrators to fall 4 per cent from 2025 to 2035 while network architects grow 8 per cent. The work is being re-titled rather than removed, and operational experience is the hard part you already have.</p>

<h3>Can I become a cloud engineer with no experience?</h3>
<p>Rarely straight away. Terraform and the platforms are learnable in months; judgement about systems under load is not. This is usually a second job, reached from support, systems administration, networking or development.</p>

<h3>Which cloud certification should I take?</h3>
<p>An associate-level certification on the platform your target employers actually run, backed by a project you can describe in detail. A current certificate signals current knowledge in a fast-moving field, but it does not substitute for having run something.</p>

<h3>AWS or Azure?</h3>
<p>Whichever appears in the postings you want. AWS dominates overall US listings; Azure is stronger in enterprises already committed to Microsoft, and in a great deal of government-adjacent work.</p>

<h3>What actually gets a cloud engineer promoted?</h3>
<p>Cost. It is the only part of the job with a dollar figure attached, so it is the part a manager can take to a finance meeting. Reliability matters as much and argues for itself far less.</p>

<h3>Do cloud engineer jobs require a security clearance?</h3>
<p>Federal and GovCloud roles usually do, which means US citizenship and an employer sponsor. The commercial market does not, and is larger and more remote-friendly.</p>

<h3>Are these roles remote?</h3>
<p>A large share are, particularly at mid and senior level with commercial employers. Expect an on-call rotation on most platform teams regardless of where you sit.</p>

<h2>People Also Search For</h2>

<h3>AWS cloud engineer jobs USA</h3>
<p>The largest share of US cloud postings, and the deepest applicant pool with it. Covered in full in our <a href="/blog/aws-cloud-engineer-jobs-in-usa">AWS cloud engineer guide</a>, including the difference between GovCloud eligibility and a security clearance.</p>

<h3>Azure cloud engineer jobs</h3>
<p>Strong in Microsoft-committed enterprises and government-adjacent work. Our <a href="/blog/azure-cloud-engineer-jobs-in-usa">Azure cloud engineer guide</a> covers the certification order Microsoft enforces and the Microsoft 365 work bundled into many of those postings.</p>

<h3>Remote cloud engineer jobs</h3>
<p>Common at commercial employers and rare in cleared work. Ask about the on-call rotation before the offer stage.</p>

<h3>Entry level cloud engineer jobs</h3>
<p>Scarce as a first job. The realistic entry is a role with operational responsibility, learning the cloud stack alongside it.</p>

<h3>Cloud engineer salary USA</h3>
<p>Depends which of three occupations the role belongs to: $99,130, $134,050 or $135,980 at the median. Read the requirements to find out which.</p>

<h3>Terraform jobs USA</h3>
<p>The most requested infrastructure-as-code tool in these listings, and the easiest part of the job to learn.</p>

<h3>FinOps and cloud cost optimisation jobs</h3>
<p>A growing specialism, and the most legible value a cloud engineer can produce.</p>

<h3>GovCloud cloud engineer jobs</h3>
<p>Federal work requiring US citizenship and a sponsored clearance. Check for that line before applying.</p>

<h2>More Job Guides</h2>

<p>Comparing the infrastructure routes? These cover them:</p>

<ul>
    <li><a href="/blog/aws-cloud-engineer-jobs-in-usa">AWS Cloud Engineer Jobs in USA</a> &mdash; the largest platform market, and why GovCloud and a clearance are two different bars.</li>
    <li><a href="/blog/azure-cloud-engineer-jobs-in-usa">Azure Cloud Engineer Jobs in USA</a> &mdash; the enterprise platform, its gated exam path, and the M365 work hidden in the job title.</li>
    <li><a href="/blog/cybersecurity-engineer-jobs-in-usa">Cybersecurity Engineer Jobs in USA</a> &mdash; the security specialisation of this work, and the Kubernetes certification ordering trap.</li>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the clearance rules in full, and the shorter door into security.</li>
    <li><a href="/blog/software-developer-jobs-in-usa">Software Developer Jobs in USA</a> &mdash; the best-paid of the three occupations this work is counted under.</li>
    <li><a href="/blog/python-developer-jobs-in-usa">Python Developer Jobs in USA</a> &mdash; the automation language that carries most of this job.</li>
    <li><a href="/blog/mobile-app-developer-jobs-in-usa">Mobile App Developer Jobs in USA</a> &mdash; the application side, and the store gate in front of a portfolio.</li>
    <li><a href="/blog/full-stack-developer-jobs-in-usa">Full Stack Developer Jobs in USA</a> &mdash; which of two very different pay bands that title is hiding.</li>
    <li><a href="/blog/network-engineer-jobs-in-usa">Network Engineer Jobs in USA</a> &mdash; the occupation the operations work moved out of, and the $34,920 between running a network and designing one.</li>
    <li><a href="/blog/devops-engineer-jobs-in-usa">DevOps Engineer Jobs in USA</a> &mdash; the delivery half of platform work, and the four measures senior interviews run on.</li>
    <li><a href="/blog/data-scientist-jobs-in-usa">Data Scientist Jobs in USA</a> &mdash; the workloads these platforms increasingly exist to run.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, employment projections, certification requirements and clearance policy change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with the Bureau of Labor Statistics, your target cloud provider and the employer's own advertisement before applying or paying for any course.</p>
HTML;
    }
}
