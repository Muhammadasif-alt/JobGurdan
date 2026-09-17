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
 * "How to Become a Correctional Officer in Canada" — a guide for someone
 * deciding between federal and provincial corrections, and working out which
 * rules, training and pay apply to them. Every employer runs its own process,
 * so the guide sets the federal Correctional Service of Canada beside two
 * provincial examples rather than pretending there is one national route.
 *
 * Corrections and clarifications to the draft (checked against Job Bank NOC
 * 43201, the Correctional Service of Canada, the Treasury Board CX collective
 * agreement, ontario.ca and princeedwardisland.ca, September 2026):
 *
 * 1. The draft makes "18, and a Canadian citizen or permanent resident" a
 *    national rule. It is not. Ontario asks for 18 or over and proof of
 *    eligibility to work in Canada on the first day; PEI's program asks for
 *    citizenship or permanent residence and age 19; CSC's most recent national
 *    poster was open to people residing in Canada and to citizens and
 *    permanent residents abroad.
 *
 * 2. The draft says a college diploma is required in New Brunswick, Quebec,
 *    Alberta and PEI. Job Bank says "post-secondary education in correctional
 *    services, police studies or criminology" is required there, and repeats
 *    the national wording on each provincial page. PEI's own training program
 *    admits applicants with Grade 12, so the guide sends readers to the
 *    province's posting.
 *
 * 3. The draft gives CSC pre-training as "30-40 hours over 3-4 weeks". CSC's
 *    Correctional Training Program has three stages: about 80 hours over four
 *    weeks, about 40 hours over three to four weeks, then about 14 weeks of
 *    in-person training with a $400-a-week allowance, up to $5,600, plus meals
 *    and lodging.
 *
 * 4. The draft names Ontario's training "COTA" and says provincial academies
 *    "often" run 17+ weeks. Ontario's is Corrections Foundational Training for
 *    Correctional Officers (CFT-CO), an eight-week program with a stipend.
 *    PEI's six-month program is 17 weeks of course work plus seven weeks on
 *    the job, and only guarantees graduates an interview for casual work.
 *
 * 5. The draft says most applicants are working within 6 to 9 months. No
 *    official source gives that. Ontario says its stages can take up to six
 *    months, and a hiring decision up to 12 months after that.
 *
 * 6. The draft gives one median wage and nothing else. Job Bank's national
 *    figures are $28.85 low, $36.15 median and $46.15 high (updated
 *    19 November 2025). Federal CX-01 pay runs $77,510 to $97,266 from
 *    1 June 2025, and Ontario starts officers at $32.15 an hour, rising to
 *    $37.79.
 *
 * 7. The draft names FITCO as though it were national. FITCO is Ontario's
 *    test; PEI uses COPAT; CSC's careers page names no entry test but expects
 *    recruits to be able to run 5 km by the end of training.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CorrectionalOfficerJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-correctional-officer-jobs.html';

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
        $title = 'How to Become a Correctional Officer in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Job Bank puts the correctional officer median at $36.15 an hour. Federal CSC recruits train in three stages on a $400-a-week allowance, Ontario trains officers for eight weeks and starts them at $32.15, and each employer sets who can apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-become-a-correctional-officer-in-canada.jpg',
                'tags' => 'correctional officer jobs in canada, how to become a correctional officer, correctional service of canada careers, csc correctional training program, ontario correctional officer, fitco test, correctional officer salary canada, noc 43201, cx-01 pay',
                'meta_title' => 'How to Become a Correctional Officer in Canada (2026 Guide)',
                'meta_description' => 'How to become a correctional officer in Canada in 2026: federal CSC vs provincial hiring, CSC and Ontario training, Job Bank pay and who can apply.',
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
            ['name' => 'Canadian Federal & Provincial Correctional Services (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'ca-correctional-officer-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Correctional Officer — Federal CSC and Provincial Institutions, Canadian Governments',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rotating shifts, including nights, weekends and holidays',
                'language' => 'English, French',
                // Federal CX pay, provincial grids and Job Bank's survey figures
                // measure different things, so no single band is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Correctional officer roles with the Correctional Service of Canada and provincial corrections ministries. Each employer runs its own testing, screening and paid training.',
                'seo_keywords' => 'correctional officer jobs canada, csc correctional officer, provincial correctional officer, noc 43201, correctional officer training canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Correctional officers in Canada work either for the federal Correctional Service of Canada (CSC), in penitentiaries holding people serving two years or more, or for a provincial or territorial corrections ministry, in jails holding people on remand or serving shorter sentences. Each employer runs its own recruitment, screening and training.</p>

<h3>What the work involves</h3>
<p>Supervising and escorting inmates, conducting searches and counts, writing reports, responding to incidents and supporting rehabilitation programs, on rotating shifts that include nights and weekends.</p>

<h3>Requirements</h3>
<ul>
    <li>Completion of secondary school; Job Bank lists post-secondary education in corrections, police studies or criminology as required in New Brunswick, Quebec, Alberta and Prince Edward Island</li>
    <li>First aid, CPR and AED certification, usually before the first day or before training</li>
    <li>Medical, fitness and security screening; Ontario uses the FITCO fitness test</li>
    <li>A valid, unrestricted driver's licence for CSC</li>
    <li>Eligibility rules on age, citizenship and residence that differ by employer</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank.</strong> $28.85 low, $36.15 median and $46.15 high an hour nationally (updated 19 November 2025)</li>
    <li><strong>Federal CX-01.</strong> $77,510 to $97,266 a year from 1 June 2025</li>
    <li><strong>Ontario.</strong> $32.15 an hour to start, rising to $37.79</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> eligibility, testing and pay are set by the Correctional Service of Canada and each provincial or territorial government &mdash; not by JobGader. Confirm the current requirements on the employer's own job posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To become a correctional officer in Canada you need at least a high school diploma, current first aid and CPR, and to pass medical, fitness and security screening, then complete your employer's paid training.</strong> You apply either to the federal Correctional Service of Canada (CSC) or to a provincial or territorial corrections ministry, and each one sets its own rules on age, citizenship and education. Job Bank puts the national median wage at <strong>$36.15 an hour</strong>.</p>

<p>This guide sets the federal route beside two provincial examples, Ontario and Prince Edward Island, because there is no single national hiring process &mdash; and several of the rules most guides repeat only apply to one employer.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-correctional-officer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127464;&#127462; Browse Correctional Officer Jobs in Canada &rarr;
    </a>
</div>

<h2>Federal or Provincial: Know Which One You Are Applying To</h2>

<p>This is the first decision, because the institutions, the training and the pay are all different.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Federal (CSC)</th>
            <th style="padding:10px;text-align:left;">Provincial or territorial</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who is held</strong></td><td style="padding:10px;">People serving sentences of two years or more</td><td style="padding:10px;">People on remand, and sentences of less than two years</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Where</strong></td><td style="padding:10px;">Federal penitentiaries</td><td style="padding:10px;">Provincial jails and detention centres</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Training</strong></td><td style="padding:10px;">Correctional Training Program, three stages</td><td style="padding:10px;">Each province's own, e.g. Ontario's eight-week CFT-CO</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Pay</strong></td><td style="padding:10px;">CX-01: $77,510 &ndash; $97,266 a year</td><td style="padding:10px;">Provincial grids, e.g. Ontario $32.15 &ndash; $37.79 an hour</td></tr>
    </tbody>
</table>
</div>

<p>Job postings use "Correctional Officer", "Correctional Service Officer" and, informally, "prison guard" for the same core role, classified as <strong>NOC 43201, Correctional service officers</strong>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-a-correctional-officer-in-canada-winter-shift.jpg"
         alt="A Canadian correctional officer in a dark uniform and duty vest outside a snow-lined penitentiary with a watchtower, razor-wire fence and Canadian flag at sunrise"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Can Apply: The Rules Differ by Employer</h2>

<p>Most guides give one national rule: "at least 18, and a Canadian citizen or permanent resident". In practice, each employer sets its own:</p>

<ul>
    <li><strong>Ontario</strong> asks you to be <strong>at least 18</strong> and to show <strong>proof of eligibility to work in Canada</strong> on your first day &mdash; not specifically citizenship or permanent residence.</li>
    <li><strong>Prince Edward Island's</strong> training program asks for <strong>Canadian citizenship or permanent residence</strong> and that you are <strong>19</strong> by the program start date.</li>
    <li><strong>CSC's</strong> most recent national recruitment poster was open to <strong>people residing in Canada</strong>, and to <strong>Canadian citizens and permanent residents living abroad</strong>.</li>
</ul>

<p>None of these is a visa route: corrections employers hire people who can already work in Canada. Read the eligibility section of the specific posting before you start.</p>

<h2>Minimum Requirements</h2>

<p><strong>Education.</strong> Job Bank's national requirement is <strong>completion of secondary school</strong>. It adds that <strong>post-secondary education in correctional services, police studies or criminology is required in New Brunswick, Quebec, Alberta and Prince Edward Island</strong>. That is Job Bank's wording, not a college diploma rule, and PEI's own training program admits applicants with Grade 12 &mdash; the program is the post-secondary training. Federally, CSC has accepted a secondary school diploma or a satisfactory score on the Public Service Commission test.</p>

<p><strong>First aid and CPR.</strong> CSC asks for standard first aid, <strong>CPR Level C and an AED certificate</strong>. Ontario requires emergency first aid, CPR and AED certification by your first day of employment.</p>

<p><strong>Driver's licence.</strong> CSC requires a <strong>valid and unrestricted</strong> provincial or territorial licence, and a Class 4A licence for positions in Quebec. PEI's program asks for an unrestricted Class 5.</p>

<p><strong>Fitness.</strong> The test differs by employer. Ontario uses the <strong>FITCO</strong> (Fitness Test for Ontario Correctional Officer Applicants). PEI uses <strong>COPAT</strong>, with four minutes allowed at the start and 3 minutes 20 seconds required to graduate. CSC's careers page names no entry test, but by the end of training you are expected to be able to <strong>run 5 km</strong>.</p>

<p><strong>Screening.</strong> Expect a pre-employment medical exam and a security check &mdash; in Ontario, a Vulnerable Sector Screening Check.</p>

<p><strong>Language.</strong> CSC positions are English-only, French-only or bilingual depending on the region. Ontario's bilingual positions require oral French at the <strong>advanced-minus</strong> level.</p>

<h2>The Federal Route: CSC's Correctional Training Program</h2>

<ol>
    <li><strong>Complete the self-assessment questionnaire.</strong> CSC encourages it before you apply, so you understand the job first. It is not an official assessment.</li>
    <li><strong>Apply</strong> to a correctional officer poster on the federal government jobs site.</li>
    <li><strong>Pass the assessments and screening</strong> &mdash; testing, interviews, medical and a security clearance.</li>
    <li><strong>Stage 1:</strong> about <strong>80 hours of learning over four weeks</strong>.</li>
    <li><strong>Stage 2:</strong> about <strong>40 hours of learning over three to four weeks</strong>. Most guides quote only this stage.</li>
    <li><strong>Receive a conditional offer</strong> that names where you will work if you complete Stage 3.</li>
    <li><strong>Stage 3:</strong> about <strong>14 weeks</strong> of in-person training, with an allowance of <strong>$400 a week, up to $5,600</strong>, plus meals and lodging. Public servants already on salary do not receive the allowance.</li>
</ol>

<p>Graduates start as <strong>CX-01</strong> officers. Under the collective agreement, pay runs from <strong>$77,510 at step 1 to $97,266 at step 5</strong> from 1 June 2025. That agreement expired on 31 May 2026, so new rates may follow once the next one is settled.</p>

<h2>Provincial Routes: Ontario and PEI</h2>

<p><strong>Ontario</strong> runs its own recruitment for provincial institutions. Successful applicants complete <strong>Corrections Foundational Training for Correctional Officers (CFT-CO)</strong>, an <strong>eight-week</strong> training and assessment program with a stipend &mdash; not "COTA", as some guides call it. Officers start at <strong>$32.15 an hour</strong>, rising to <strong>$37.79</strong>. Two conditions matter if you live elsewhere:</p>

<ul>
    <li>Canadian citizens living outside Canada can apply, but you must <strong>travel to Ontario at your own expense</strong> for the selection process.</li>
    <li>If you are hired, you <strong>pay your own moving and relocation costs</strong>.</li>
</ul>

<p><strong>Prince Edward Island</strong> runs a six-month Correctional Officer Training Program in partnership with the <strong>Atlantic Police Academy</strong>: <strong>17 weeks of course work and seven weeks of on-the-job training</strong>. The current intake began on 17 August 2026. Students pay for their own food and accommodation, and graduates are guaranteed an <strong>interview for casual employment</strong>, not a job. Seventeen weeks is PEI's figure, not a typical provincial length &mdash; Ontario's is eight.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-a-correctional-officer-in-canada-institution.jpg"
         alt="A correctional officer in uniform and radio vest standing outside a Canadian correctional institution entrance, with a chain-link fence, watchtower and Canadian flag behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Correctional Officers Earn</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">Figure</th>
            <th style="padding:10px;text-align:left;">Source</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National low</td><td style="padding:10px;">$28.85 an hour</td><td style="padding:10px;">Job Bank, NOC 43201</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>National median</strong></td><td style="padding:10px;"><strong>$36.15 an hour</strong></td><td style="padding:10px;">Job Bank, NOC 43201</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National high</td><td style="padding:10px;">$46.15 an hour</td><td style="padding:10px;">Job Bank, NOC 43201</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Federal CX-01</td><td style="padding:10px;">$77,510 &ndash; $97,266 a year</td><td style="padding:10px;">CX collective agreement, from 1 June 2025</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$32.15 &ndash; $37.79 an hour</td><td style="padding:10px;">Government of Ontario</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Job Bank national wages for NOC 43201, reference period 2023&ndash;2024, updated 19 November 2025.</p>
</div>

<p>Job Bank's figures are measured wages across all employers and experience levels, so use them as a baseline, not a starting salary. Shift premiums, overtime and seniority all add to take-home pay.</p>

<h2>How Long Hiring Takes</h2>

<p>Many guides promise you will be working within <strong>six to nine months</strong>. We could not find an official source for that. Ontario says its selection stages can take <strong>up to six months</strong>, and a hiring decision can take <strong>up to 12 months</strong> after that. CSC's process adds seven to eight weeks of online learning before the 14-week in-person stage. Plan for <strong>a year or more</strong>, and keep your first aid and CPR certificates current throughout, because they expire.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the difference between a federal and a provincial correctional officer?</h3>
<p>Federal officers work for the Correctional Service of Canada in penitentiaries holding people serving two years or more. Provincial officers work in jails holding people on remand or serving less than two years, and each province runs its own hiring and training.</p>

<h3>Do I need a criminology degree to become a correctional officer in Canada?</h3>
<p>Not nationally. Job Bank's baseline is secondary school, and it lists post-secondary education in corrections, police studies or criminology as required in New Brunswick, Quebec, Alberta and Prince Edward Island. PEI's own training program admits applicants with Grade 12, so check the province's posting.</p>

<h3>Do I have to be a Canadian citizen?</h3>
<p>It depends on the employer. Ontario asks for proof of eligibility to work in Canada. PEI's program asks for citizenship or permanent residence. CSC's most recent national poster was open to people residing in Canada and to citizens and permanent residents abroad.</p>

<h3>How long is correctional officer training?</h3>
<p>CSC's Correctional Training Program has about 80 hours of online learning over four weeks, about 40 hours over three to four weeks, then about 14 weeks in person. Ontario's CFT-CO is eight weeks. PEI's program is 17 weeks of course work plus seven weeks on the job.</p>

<h3>Is correctional officer training paid?</h3>
<p>CSC pays a $400-a-week allowance during the 14-week Stage 3, up to $5,600, plus meals and lodging. Ontario offers a training stipend. PEI's students pay for their own food and accommodation.</p>

<h3>What fitness test do correctional officers take?</h3>
<p>It depends on the employer. Ontario uses FITCO and PEI uses COPAT. CSC's careers page names no entry test but expects recruits to be able to run 5 km by the end of training.</p>

<h3>How much does a correctional officer earn in Canada?</h3>
<p>Job Bank puts the national median at $36.15 an hour, with a low of $28.85 and a high of $46.15. Federal CX-01 officers earn $77,510 to $97,266 a year, and Ontario starts officers at $32.15 an hour.</p>

<h3>Can I apply from outside Canada or another province?</h3>
<p>Canadian citizens living abroad can apply to Ontario, but must travel there at their own expense for selection and pay their own relocation if hired. CSC's most recent poster accepted citizens and permanent residents abroad.</p>

<h2>People Also Search For</h2>

<h3>CSC correctional officer salary</h3>
<p>CX-01 pay runs from $77,510 to $97,266 a year from 1 June 2025.</p>

<h3>Correctional Training Program CSC</h3>
<p>Three stages: about 80 hours online, about 40 hours online, then about 14 weeks in person with a $400-a-week allowance.</p>

<h3>Ontario correctional officer requirements</h3>
<p>Age 18 or over, Grade 12, first aid, CPR and AED, the FITCO test, a medical, and a Vulnerable Sector Screening Check.</p>

<h3>FITCO test</h3>
<p>The Fitness Test for Ontario Correctional Officer Applicants, used by Ontario's provincial corrections.</p>

<h3>CFT-CO training Ontario</h3>
<p>Corrections Foundational Training for Correctional Officers, Ontario's eight-week training and assessment program.</p>

<h3>Correctional officer NOC code</h3>
<p>NOC 43201, Correctional service officers, with a Job Bank median of $36.15 an hour.</p>

<h3>PEI correctional officer training program</h3>
<p>A six-month program with the Atlantic Police Academy: 17 weeks of course work and seven weeks on the job.</p>

<h3>Correctional officer jobs for permanent residents</h3>
<p>Accepted by PEI's program and CSC's most recent poster; Ontario asks for eligibility to work in Canada.</p>

<h2>More Job Guides</h2>

<p>Considering public safety work in Canada or elsewhere? These cover the neighbouring routes:</p>

<ul>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; state and local departments, and which ones hire non-citizens.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; FBI, CBP, ICE and US Marshals jobs, their pay and age limits.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; what the 2026 levels plan changed and which sectors actually hire.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA, the language rules and the routes to permanent residence.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; licensed security work in the Gulf.</li>
    <li><a href="/blog/government-security-jobs-in-australia">Government Security Jobs in Australia</a> &mdash; AFP protective service officer pay, Border Force, ASIO and ASD requirements, and how security clearances work.</li>
    <li><a href="/blog/public-safety-jobs-in-canada">Public Safety Jobs in Canada</a> &mdash; CBSA border officer pay and rules, RCMP and CSC requirements, emergency management jobs and who can apply.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not employment or legal advice. Eligibility, training and pay are set by the Correctional Service of Canada and each provincial or territorial government, and change over time. Confirm the current requirements on the employer's own job posting before applying.</p>
HTML;
    }
}
