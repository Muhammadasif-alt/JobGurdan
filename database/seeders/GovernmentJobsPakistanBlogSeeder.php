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
 * "Government Jobs in Pakistan" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup.
 *
 * The distinction the guide is built around is BPS against PPS. Every role
 * people find under this keyword on the job boards is PPS — a project post,
 * hired directly by the ministry, fixed to the life of the project and outside
 * the civil-service pension. The permanence and pension this keyword is
 * searched for belong to BPS posts, which are recruited through FPSC and PPSC
 * instead. Guides on this keyword routinely mix the two up.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class GovernmentJobsPakistanBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://pk.indeed.com/q-government-l-lahore-jobs.html?vjk=f3eb3a2cbd99869c';

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
        $title = 'Government Jobs in Pakistan';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What separates a BPS civil-service post from a PPS project post, why almost everything advertised under this keyword is the second kind, how FPSC and PPSC recruitment differs, and how to apply without wasting an application.',
                'content' => $content,
                'featured_image' => 'blogs/government-jobs-in-pakistan.jpg',
                'tags' => 'government jobs in pakistan, federal government jobs in pakistan, government jobs in pakistan for female, government jobs in pakistan punjab, fpsc jobs, ppsc jobs, pps scale jobs, bps scale jobs, government jobs online apply pakistan, matric base government jobs',
                'meta_title' => 'Government Jobs in Pakistan — BPS vs PPS Explained',
                'meta_description' => 'Government jobs in Pakistan: why most advertised roles are PPS project posts with no pension, how FPSC and PPSC differ from direct hiring, and how to apply.',
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
            ['name' => 'Federal Boards & Ministries (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'pk-federal-boards-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Islamabad, Lahore and Karachi', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Government & Public Sector — Federal Boards and Ministries',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard government office hours; project posts follow the contract',
                'language' => 'English and Urdu',
                // PPS and BPS pay is set per scale and per advertisement, so no
                // single range would be true across these roles.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Federal board and ministry roles across Pakistan, from project posts on the PPS scale to permanent BPS civil-service seats. Apply through the official portal named on each ad.',
                'seo_keywords' => 'government jobs in pakistan, federal government jobs pakistan, fpsc jobs, ppsc jobs, pps scale jobs, government jobs islamabad, government jobs lahore, government jobs online apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Federal boards, ministries and provincial departments across Pakistan advertise continuously &mdash; policy and research officers, project and finance managers, monitoring and evaluation staff, IT and telecom specialists, communications roles and internships, alongside the permanent civil-service seats filled through competitive examination.</p>

<h3>Which scale you are hired on matters more than the job title</h3>
<p>Check this before you apply. A <strong>BPS (Basic Pay Scale)</strong> post is the regular civil service: permanent, pensionable, with seniority and a defined promotion path, recruited through the <strong>FPSC</strong> federally or the relevant <strong>Provincial Public Service Commission</strong>. A <strong>PPS (Project Pay Scale)</strong> post belongs to a development project: hired directly by the ministry or board, fixed to the life of that project, normally without the civil-service pension or seniority. The pay on a PPS post is often higher than the equivalent BPS grade &mdash; that is the trade for the security you are giving up. Almost every vacancy people find under &quot;government jobs&quot; on the job boards is PPS.</p>

<h3>Requirements</h3>
<ul>
    <li>Qualification and experience matching the exact scale on the advertisement &mdash; applications outside the stated criteria are rejected at screening</li>
    <li>CNIC, updated CV, attested degree and transcript copies, and a domicile certificate</li>
    <li>For BPS posts, registration and the written examination through FPSC or the provincial commission</li>
    <li>For PPS and contract posts, application through the board or ministry's own careers portal</li>
    <li>Age within the limits printed on the advertisement, allowing for the general age relaxation where it applies to you</li>
</ul>

<h3>What is on offer</h3>
<ul>
    <li>BPS posts: permanent appointment, pension, seniority and a defined promotion path</li>
    <li>PPS posts: higher headline pay on a fixed-term contract tied to the project</li>
    <li>Quota seats for women, minorities and people with disabilities, at the share printed on each advertisement</li>
    <li>Postings concentrated in Islamabad, with provincial departments hiring in Lahore, Karachi, Peshawar and Quetta</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Apply only through the official portal named on the advertisement</strong> &mdash; FPSC or the provincial commission for civil-service posts, or the board or ministry's own careers page for project roles. No genuine government recruitment asks for a payment to shortlist you, and the fee for an FPSC or PPSC application is paid to the commission through a bank challan, never to a person. Keep your tracking number; it is what you need to check your shortlist status later.</p>

<p><strong>Note:</strong> scales, closing dates and eligibility are set by the recruiting department &mdash; not by JobGader. Verify each vacancy on the official portal before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Government jobs remain the most searched career path in Pakistan, and the reasons people give are always the same three: security, a defined pay scale, and a pension. What almost nobody explains is that the vacancies you actually find under this search &mdash; the project officer, the research officer, the finance manager on a board &mdash; usually offer none of those three. They sit on a different scale entirely. This guide starts there, because getting it wrong is what makes people take a job they thought was permanent and find out otherwise.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/q-government-l-lahore-jobs.html?vjk=f3eb3a2cbd99869c" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🏛️ Browse Government Jobs in Pakistan &rarr;
    </a>
</div>

<h2>BPS or PPS? This Decides Whether the Job Is Permanent</h2>

<p>Read this before any listing. Two people can both say they work a government job in Pakistan and be in completely different positions:</p>

<ul>
    <li><strong>BPS &mdash; Basic Pay Scale.</strong> The regular civil service. Permanent appointment, pension, seniority and a defined promotion ladder. Recruited through the <strong>Federal Public Service Commission (FPSC)</strong> federally, or the relevant <strong>Provincial Public Service Commission</strong> &mdash; PPSC in Punjab, SPSC in Sindh, KPPSC in Khyber Pakhtunkhwa, BPSC in Balochistan. Entry is by advertised competition and, for most grades, a written examination.</li>
    <li><strong>PPS &mdash; Project Pay Scale.</strong> A post attached to a specific development project. Hired directly by the ministry or board that runs the project, on a contract fixed to that project's life, normally without the civil-service pension or seniority. There is usually no commission examination; you are shortlisted and interviewed by the organisation itself.</li>
</ul>

<p>Neither is a lesser job, and PPS pay is frequently <em>higher</em> than the equivalent BPS grade &mdash; that is the trade you are being offered for the security you give up. But a PPS-9 contract is not the pensionable government seat that this keyword is usually searched for. If an advertisement says PPS, read it as a fixed-term specialist contract with the government, and ask what happens at the end of the project.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/government-jobs-in-pakistan-islamabad.jpg"
         alt="The Supreme Court building in Islamabad, one of the federal institutions that advertises government jobs in Pakistan"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Federal Roles Actually Look Like</h2>

<p>The table below is a snapshot of roles that federal boards and ministries were advertising at the time of writing, with the number of applications each had already drawn on Indeed. It is here to show the shape of the market &mdash; the scales used, the experience asked for, and how heavily each level is contested &mdash; not as a live vacancy list. Individual posts close, and the counts move daily. Use the search link for what is open today.</p>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Organisation</th>
            <th>Experience</th>
            <th>Applications</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>Assistant Manager Monitoring &amp; Evaluation (PPS-8)</td><td>Pakistan Software Export Board</td><td>4+ years</td><td>43</td></tr>
        <tr><td>Project Officer (PPS-8)</td><td>Pakistan Software Export Board</td><td>4+ years</td><td>35</td></tr>
        <tr><td>Finance Manager / DDO (PPS-9)</td><td>Pakistan Software Export Board</td><td>8+ years</td><td>19</td></tr>
        <tr><td>Project Manager (PPS-9)</td><td>Pakistan Software Export Board</td><td>8+ years</td><td>21</td></tr>
        <tr><td>Senior Telecom Policy and Strategy Consultant</td><td>Ministry of IT &amp; Telecommunication</td><td>18+ years</td><td>10</td></tr>
        <tr><td>Research Officer (PPS-7)</td><td>Ministry of Planning, Development &amp; Special Initiatives</td><td>1+ years</td><td>481</td></tr>
        <tr><td>Social Media Expert and Content Writer (PPS-9)</td><td>Ministry of Planning, Development &amp; Special Initiatives</td><td>5+ years</td><td>10</td></tr>
        <tr><td>Specialist Maritime &amp; Blue Economy (PPS-9)</td><td>Ministry of Planning, Development &amp; Special Initiatives</td><td>5+ years</td><td>13</td></tr>
        <tr><td>Specialist Regional Connectivity and Infrastructure (PPS-9)</td><td>Ministry of Planning, Development &amp; Special Initiatives</td><td>5+ years</td><td>16</td></tr>
        <tr><td>Interns</td><td>Ministry of Planning, Development &amp; Special Initiatives</td><td>Entry level</td><td>474</td></tr>
    </tbody>
</table>

<p>Two things are worth reading off that table. <strong>Every one of these is PPS</strong> &mdash; project posts, not civil-service seats, which is exactly the confusion this guide opened with. And <strong>competition is inverted</strong>: the entry-level research officer and internship drew 481 and 474 applications, while the senior specialist roles drew ten to twenty. The bottleneck in Pakistani public-sector hiring is not that there are no jobs; it is that everyone applies to the same handful of entry-level ones.</p>

<p>The practical read: if you have five or more years in a specialist area, your odds on a PPS-9 post are far better than the general mood around government hiring suggests.</p>

<h2>Federal Government Jobs: Two Different Doors</h2>

<p>Federal recruitment runs through two separate routes, and applying through the wrong one wastes the application:</p>

<ul>
    <li><strong>FPSC</strong> handles the permanent civil-service (BPS) posts, including the CSS competitive examination and the departmental posts advertised in the commission's consolidated advertisements. You register on the FPSC portal, pay the fee by bank challan, and sit the written test.</li>
    <li><strong>Direct ministry and board hiring</strong> covers PPS and other contract posts &mdash; PSEB, MOITT, the Planning Ministry and similar. These are advertised on the organisation's own careers page and in the press, skip the commission examination, and move straight to shortlisting and interview. That is why they close faster.</li>
</ul>

<p>If you are aiming at a permanent seat, your route is the commission and its examination calendar. If you are aiming at specialist project work, watch the boards' own portals, because those vacancies come and go inside a few weeks.</p>

<h2>Government Jobs in Pakistan for Female Candidates</h2>

<p>A women's quota applies to federal recruitment and to the provincial services, and the reserved share is not the same in each &mdash; the federal quota is commonly applied at 10% and Punjab's at 15%, but the figure that governs your application is the one printed on the advertisement itself. Two things about it are widely misunderstood:</p>

<ul>
    <li><strong>It is a floor, not a ceiling.</strong> Women compete on merit for the open seats as well; the quota only guarantees a minimum share. Being selected on merit does not consume a quota seat.</li>
    <li><strong>It has to be claimed correctly on the form.</strong> If the application asks which quota you are applying against, answer it. A blank field is not treated as a claim.</li>
</ul>

<p>Policy, research and communications roles in the federal boards and ministries draw a large share of women applicants, and the interview-only PPS route avoids the examination scheduling that makes the commission path harder to fit around other commitments. Field-heavy specialist posts &mdash; maritime, regional connectivity, infrastructure &mdash; carry travel that is worth clarifying at interview rather than assuming either way.</p>

<h2>Government Jobs in Punjab</h2>

<p>Punjab recruits separately through the <strong>Punjab Public Service Commission (PPSC)</strong>, which is a different portal, a different fee, and a different advertisement calendar from the federal commission. Health, education, and excise and taxation are its largest recruiters. Eligibility, age limits and quota shares differ from the federal rules, so if you are applying in both places, read each advertisement on its own terms rather than assuming the federal criteria carry across. Sindh, Khyber Pakhtunkhwa and Balochistan each run their own commission on the same pattern.</p>

<h2>Matric-Base and Entry-Level Government Jobs</h2>

<p>Not every government post needs a degree. Clerical, dispatch, driver, naib qasid and support-staff positions across ministries and departments are advertised regularly for matric and intermediate candidates, and these sit in the lower <strong>BPS</strong> grades &mdash; roughly BPS-1 to BPS-5 &mdash; which means they are the permanent, pensionable kind rather than project contracts.</p>

<p>They are also the most heavily contested vacancies in the country. Expect thousands of applications for a handful of seats, expect a written test even for support roles, and apply to every relevant advertisement rather than waiting for the ideal one.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/government-jobs-in-pakistan-apply-online.jpg"
         alt="A desk prepared for an online government job application in Pakistan"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply Online</h2>

<ol>
    <li><strong>Match yourself against the exact scale.</strong> Qualification and years of experience are screened mechanically against what the advertisement states. Applying one grade above your eligibility is a wasted application, not an ambitious one.</li>
    <li><strong>Prepare the documents before you open the form</strong> &mdash; CNIC, updated CV, attested degree and transcripts, domicile certificate, and a recent photograph at the size the portal specifies.</li>
    <li><strong>Use the official channel named on the advertisement.</strong> FPSC or your provincial commission for civil-service posts; the board or ministry's own careers page for project roles. Third-party sites that offer to submit on your behalf are not part of the process.</li>
    <li><strong>Pay any fee the way the advertisement says.</strong> Commission fees are paid by bank challan to the commission. Nobody in a genuine process asks you to pay a person to be shortlisted.</li>
    <li><strong>Check the closing date on the advertisement itself.</strong> Portals close at the stated deadline. Extensions do happen, but they are announced on the same portal &mdash; never plan around one.</li>
    <li><strong>Save your tracking or roll number.</strong> It is how you check shortlisting, download the admit card, and follow the result.</li>
</ol>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/q-government-l-lahore-jobs.html?vjk=f3eb3a2cbd99869c" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 See Government Job Listings in Pakistan &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the difference between BPS and PPS?</h3>
<p>BPS is the Basic Pay Scale used for the regular civil service: permanent, pensionable, with seniority and a promotion ladder, recruited through FPSC or a provincial commission. PPS is the Project Pay Scale, used for posts attached to a development project, hired directly by the ministry or board on a contract fixed to that project's life and normally without the civil-service pension.</p>

<h3>Do I need to pass the FPSC exam to apply for PSEB or MOITT roles?</h3>
<p>No. PSEB, MOITT and Planning Ministry project posts are hired directly by the organisation. You apply on their careers portal and go to shortlisting and interview without a commission examination.</p>

<h3>Are government jobs in Pakistan open to female candidates in every department?</h3>
<p>Yes, and a women's quota applies across federal and provincial recruitment. The reserved share differs between the federal government and each province, so read the figure on the advertisement. The quota is a minimum, not a limit &mdash; women are also selected on merit against the open seats.</p>

<h3>Where do I check the last date to apply?</h3>
<p>On the advertisement itself, and on the portal you are applying through. Dates are occasionally extended and occasionally brought forward, so secondhand listings are not reliable for deadlines.</p>

<h3>Can I apply for government jobs on a matric certificate?</h3>
<p>Yes. Clerical, dispatch, driver, naib qasid and support-staff posts in roughly BPS-1 to BPS-5 are advertised for matric and intermediate candidates. They are permanent posts, and they are the most heavily contested vacancies advertised.</p>

<h3>Is Punjab recruitment separate from federal recruitment?</h3>
<p>Yes. Punjab hires through the Punjab Public Service Commission on its own portal, calendar and criteria. Sindh, Khyber Pakhtunkhwa and Balochistan each run their own commission the same way. Federal eligibility rules do not carry across.</p>

<h3>Should I ever pay someone to get a government job?</h3>
<p>No. Application fees for FPSC and the provincial commissions are paid by bank challan to the commission itself. Any request to pay a person for shortlisting, a test result or an appointment letter is a fraud, whatever documents are shown to you.</p>

<h3>Which government jobs are least competitive?</h3>
<p>Senior specialist posts. In the snapshot above, entry-level research and internship positions drew over 470 applications each while PPS-9 specialist roles drew ten to twenty. Experience in a defined specialism is worth far more here than it is in the general market.</p>

<h2>People Also Search For</h2>

<h3>Government jobs in Pakistan today online apply</h3>
<p>Federal project posts are applied for on the board or ministry's own careers portal; civil-service posts through FPSC or your provincial commission. Both take the application online, but only one involves an examination.</p>

<h3>Federal government jobs in Pakistan</h3>
<p>Two routes: FPSC for permanent BPS seats, and direct hiring by boards and ministries for PPS project posts. The second closes far faster than the first.</p>

<h3>Government jobs in Pakistan for female candidates</h3>
<p>A quota applies federally and in every province at a share printed on each advertisement. It is a minimum rather than a cap, and it has to be claimed on the form to count.</p>

<h3>Government jobs in Pakistan Punjab</h3>
<p>Recruited through the Punjab Public Service Commission on its own portal and calendar, with health, education and excise and taxation the largest departments.</p>

<h3>PPS scale jobs in Pakistan</h3>
<p>Project Pay Scale posts attached to a development project, hired directly by the ministry or board, fixed-term, usually better paid than the equivalent BPS grade and without the pension.</p>

<h3>Matric base government jobs in Pakistan</h3>
<p>Clerical, dispatch, driver and support-staff posts in roughly BPS-1 to BPS-5. Permanent and pensionable, and the most heavily contested vacancies advertised.</p>

<h3>Government jobs in Pakistan last date to apply</h3>
<p>Printed on each advertisement. Extensions are announced on the same portal, so never plan an application around one.</p>

<h3>FPSC and PPSC jobs</h3>
<p>FPSC recruits federally, PPSC for Punjab, with SPSC, KPPSC and BPSC covering the other provinces. Each has its own portal, fee and advertisement calendar.</p>

<h2>More Job Guides</h2>

<p>Looking at private-sector and overseas options alongside the public sector? These cover the rest:</p>

<ul>
    <li><a href="/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern">Senior Frontend Developer at ERS Tech, Lahore</a> &mdash; what a mid-senior engineering seat in Lahore actually asks for and pays.</li>
    <li><a href="/blog/digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan">Digital Marketing Expert (SEO) &mdash; Remote, Pakistan</a> &mdash; a remote private-sector route out of the same graduate pool.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Gulf route, and how Saudi sponsorship is really processed.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; giga-project hiring from trades to project management.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or careers advice. Scales, quotas, age limits and closing dates are set by the recruiting department and change &mdash; confirm every requirement on the official advertisement and on the FPSC or provincial commission portal before applying or paying any fee.</p>
HTML;
    }
}
