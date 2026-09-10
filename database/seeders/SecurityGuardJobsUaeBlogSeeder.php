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
 * "Security Guard Jobs in UAE" — the Gulf companion to the Saudi security
 * guide. Built around two facts the draft gets wrong or omits, both of which
 * cost a candidate real money.
 *
 * Corrections to the draft:
 *
 * 1. It says a SIRA card "is widely recognized across the UAE". It is not.
 *    SIRA is the Dubai regulator, under Dubai Police. Abu Dhabi, Sharjah,
 *    Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah are regulated by the
 *    Private Security Business Department of Abu Dhabi Police, and a licence
 *    from one authority does not permit work in the other's territory. The
 *    draft's own list of hiring emirates is mostly PSBD territory, so a
 *    reader could pay for the wrong licence.
 *
 * 2. It advises treating accommodation and transport as part of total
 *    compensation and accepting a lower basic salary for them. That is sound
 *    for monthly cash flow and wrong for end-of-service gratuity, which under
 *    Federal Decree-Law No. 33 of 2021 is calculated on basic salary alone.
 *    Allowances are excluded, so the split between basic and allowances
 *    quietly decides what leaves with you.
 *
 * 3. Its salary table runs to AED 3,000 and then resumes at AED 4,000 for
 *    supervisors with nothing in between, which reads as a ladder where the
 *    data shows a gap.
 *
 * 4. It gives monthly figures without asking whether they are for an eight
 *    hour day or the twelve hour shift the industry commonly runs, which is
 *    the difference between two very different hourly rates.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SecurityGuardJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ae.indeed.com/q-security-guard-jobs.html';

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
        $title = 'Security Guard Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'SIRA licenses Dubai and nowhere else, six emirates run a different regulator, and your end-of-service gratuity is calculated on basic salary alone. Two facts that decide which job you can take and what you leave with.',
                'content' => $content,
                'featured_image' => 'blogs/security-guard-jobs-in-uae.jpg',
                'tags' => 'security guard jobs uae, security guard jobs dubai, sira license, psbd license abu dhabi, security guard salary uae, mall security jobs dubai, gulf security jobs, security jobs with visa',
                'meta_title' => 'Security Guard Jobs in UAE',
                'meta_description' => 'Security guard jobs in UAE: why SIRA only covers Dubai, which regulator licenses the other emirates, and how the basic salary split decides your gratuity.',
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
            ['name' => 'UAE Manned Guarding & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ae-security-guard-aggregated']
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
                'position' => 'Security Guard — Mall, Hospital, Camp and Residential Sites, UAE Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work, commonly twelve-hour rotations including nights and weekends',
                'language' => 'English',
                // A quoted monthly figure means different things depending on
                // the basic-to-allowance split and the shift length, both of
                // which vary by employer.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Licensed security roles with UAE employers across Dubai and the northern emirates. Confirm which regulator licenses the site before you train.',
                'seo_keywords' => 'security guard jobs uae, security guard jobs dubai, sira license, psbd license abu dhabi, security guard salary uae',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Malls, hotels, hospitals, residential communities, construction camps, free zones and logistics sites across the United Arab Emirates hire licensed security guards through manned guarding and facilities management companies. Most listings are open to candidates from South Asia and Africa, and the majority include visa sponsorship, accommodation and transport.</p>

<h3>What the work involves</h3>
<p>Access control, patrolling, CCTV monitoring, incident reporting, crowd and queue management, and escorting where the site requires it. The specialisation changes the day considerably &mdash; a hospital post, a mall post and a labour camp post are different jobs sharing a job title.</p>

<h3>Requirements</h3>
<ul>
    <li>A valid security licence <strong>for the emirate the site is in</strong> &mdash; SIRA for Dubai, PSBD for Abu Dhabi and the northern emirates</li>
    <li>Prior security, military or police experience, typically one to five years depending on the role</li>
    <li>High school diploma or equivalent</li>
    <li>Medical fitness, and a certificate of good conduct or police clearance</li>
    <li>Working English; Arabic, Hindi or Urdu are frequently useful on site</li>
    <li>Some listings state a minimum height, commonly around 170cm for male candidates</li>
</ul>

<h3>How the package is structured</h3>
<ul>
    <li><strong>Basic salary and allowances are separate lines,</strong> and end-of-service gratuity is calculated on <strong>basic salary alone</strong> under Federal Decree-Law No. 33 of 2021</li>
    <li><strong>Accommodation, transport, visa and medical insurance</strong> are commonly provided and are genuine value, but they do not raise your gratuity</li>
    <li><strong>Pay is quoted monthly and tax free,</strong> since the UAE levies no personal income tax on salaries</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask for the basic-to-allowance split in writing, and ask which regulator licenses the site.</strong> Two offers with the same monthly total can differ substantially in what you leave with after several years, and a licence for the wrong emirate is money spent on a job you cannot take.</p>

<p><strong>Note:</strong> salary, package structure, licensing responsibility and visa terms are set by each employer &mdash; not by JobGader. Be cautious of any agent asking you to pay to be placed. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Security work is one of the most reliable routes into the Gulf for candidates from South Asia and Africa. The listings are real, the visa sponsorship is real, and the accommodation and transport are usually real. Two things about it are commonly reported wrongly, and both cost money: which licence you need, and how the package you sign decides what you take home when you leave.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ae.indeed.com/q-security-guard-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128737;&#65039; Browse Security Guard Jobs in the UAE &rarr;
    </a>
</div>

<h2>SIRA Licenses Dubai. It Does Not License the UAE.</h2>

<p>This is the correction worth the whole page, because getting it wrong means paying for training that does not let you take the job you were offered.</p>

<p>Guides routinely say a SIRA card is "widely recognised across the UAE" and "preferred even where it isn't mandatory". That is not how the UAE regulates private security.</p>

<ul>
    <li><strong>SIRA</strong> &mdash; the Security Industry Regulatory Agency, established under <strong>Dubai Police</strong> &mdash; licenses private security companies and individual security personnel <strong>in the Emirate of Dubai</strong>.</li>
    <li><strong>PSBD</strong> &mdash; the Private Security Business Department, a unit of <strong>Abu Dhabi Police</strong> under the Ministry of Interior &mdash; is the competent authority for <strong>Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah</strong>.</li>
</ul>

<p>They are separate regimes with separate examinations and separate cards, and <strong>a guard licensed under one cannot work in the other's territory.</strong></p>

<p>Now read that against the guides themselves, which list current vacancies "across Dubai, Abu Dhabi, Sharjah and Ras Al Khaimah". <strong>Three of those four emirates are PSBD territory.</strong> A candidate who reads "get SIRA-ready" and pays for a SIRA course has prepared for one of the four markets on that list.</p>

<p>So the first question about any UAE security vacancy is not what it pays. It is: <strong>which emirate is the site in, and which authority licenses it?</strong> Then match your training to the answer. If you are keeping options open across emirates, ask the employer directly which licence they will sponsor &mdash; because most reputable manned guarding and facilities companies do sponsor it as part of onboarding, which makes this a question to settle at interview rather than an expense to take on yourself.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-guard-jobs-in-uae-sira.jpg"
         alt="A licensed security guard on duty at a commercial building entrance in the UAE"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Your Gratuity Is Calculated on Basic Salary Alone</h2>

<p>This is the second expensive thing, and it runs directly against the advice most guides give.</p>

<p>Guides tell you to treat the benefits package as part of total compensation, and to accept a slightly lower basic salary in exchange for full accommodation and transport. For your monthly cash position, that is reasonable. For what you leave the country with, it is the wrong trade.</p>

<p>Under <strong>Federal Decree-Law No. 33 of 2021</strong>, end-of-service gratuity is calculated on the <strong>basic salary</strong> the worker was last entitled to. <strong>Housing, transport, utilities and other allowances are excluded from the calculation.</strong> An expatriate becomes eligible after <strong>one year</strong> of continuous service, the total is capped at <strong>two years' wage</strong>, and all final dues must be settled within <strong>14 days</strong> of the employment ending.</p>

<p>Work through what that means for two offers with the same headline.</p>

<p>Offer A: <strong>AED 2,000 basic</strong>, accommodation and transport provided in kind.<br>
Offer B: <strong>AED 1,200 basic plus AED 800 in allowances</strong>, same accommodation and transport.</p>

<p>Both are "AED 2,000 a month". Both feel identical every payday. But every year of service under Offer B builds gratuity on a base <strong>40 per cent smaller</strong>. Over several years that is a meaningful sum, and it is decided entirely by a line in a contract that nobody reads aloud at the interview.</p>

<p><strong>Ask for the basic-to-allowance split in writing before you accept.</strong> It is a normal question in the Gulf, employers expect it, and it is the single highest-value thing you can ask about a UAE offer.</p>

<h2>Read the Monthly Figure Against the Shift</h2>

<p>Published ranges for this job sit around <strong>AED 1,800 to 3,000 a month</strong> for entry to mid-experience guards, with Dubai and Abu Dhabi both inside that band, and supervisory roles quoted from <strong>AED 4,000 upward</strong>.</p>

<p>Two things about that table.</p>

<p><strong>It has a hole in the middle.</strong> The general band stops at 3,000 and the supervisory band starts at 4,000, with nothing between. Real careers do not jump a third overnight; what that gap actually reflects is that the data has plenty of entry-level postings and plenty of supervisor postings and comparatively few of the experienced-guard roles in between. Do not read it as a ladder you climb in one step.</p>

<p><strong>It says nothing about hours.</strong> Manned guarding in the Gulf commonly runs <strong>twelve-hour shifts</strong>. A monthly figure quoted against an eight-hour day and the same figure quoted against a twelve-hour day are not the same rate, and the advertisement will rarely tell you which. Ask what the standard shift is, how many days off per month, and whether overtime is paid separately or treated as included. That question reorders offers more often than the headline number does.</p>

<p>The tax position is genuinely as good as it sounds: the UAE levies no personal income tax on salaries, so a quoted figure is what arrives.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-guard-jobs-in-uae-salary.jpg"
         alt="A security officer monitoring CCTV screens in a UAE control room"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Getting Licensed, Step by Step</h2>

<p>The process is similar under both regulators, and in both cases it is normally handled <strong>through a licensed security company rather than by you individually</strong> &mdash; which is why the licence question belongs at interview.</p>

<ol>
    <li><strong>Confirm the role category.</strong> Guard, event security, cash escort, CCTV operator and supervisor are licensed separately, and the training differs.</li>
    <li><strong>Hold valid residency and an Emirates ID.</strong> The licence attaches to a resident, so the visa comes first in practice.</li>
    <li><strong>Obtain a certificate of good conduct and a medical fitness certificate</strong>, addressed as the authority requires.</li>
    <li><strong>Complete the approved training course</strong> covering procedures, access control and emergency response.</li>
    <li><strong>Pass the written and practical examination.</strong></li>
    <li><strong>Submit through the employer</strong>, since licensing is tied to a licensed security company.</li>
</ol>

<p>Processing typically takes a few weeks once training and documents are complete. If an employer says they sponsor the licence, get that in writing alongside the salary &mdash; it is a real benefit worth naming.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Mall and retail security officer.</strong> High footfall, customer-facing, long standing shifts. The most common Dubai posting.</li>
    <li><strong>Hospital and healthcare security.</strong> Access control and visitor management, with de-escalation weighted heavily. Often at the upper end of the general band.</li>
    <li><strong>Camp security guard.</strong> Construction and labour accommodation sites, usually with accommodation and transport included as standard.</li>
    <li><strong>Residential and community guard.</strong> Gated compounds and towers, rotating shifts, property management employers.</li>
    <li><strong>Cash escort.</strong> A separately licensed category with its own training and a higher bar.</li>
    <li><strong>Security supervisor.</strong> Rostering, incident escalation and team management, quoted from AED 4,000 upward.</li>
</ul>

<h2>Before You Pay Anyone Anything</h2>

<p>Recruitment for Gulf security work runs heavily through agents, and most are legitimate. A few are not, and the pattern is consistent enough to name.</p>

<p><strong>Be extremely cautious of any agent asking you for money to be placed.</strong> Ask who bears the recruitment cost, get the answer in writing, and check that the employing company is a licensed security company under the authority for the emirate concerned &mdash; both regulators license the companies as well as the individuals, so the employer's own licence is verifiable.</p>

<p>Ask specifically: who pays for the licence and training, who pays for the visa and medical, what the accommodation is and how many share a room, and whether transport to site is provided or deducted. Those five answers describe the job far better than the salary does. Our <a href="/blog/security-guard-jobs-in-saudi-arabia">security guard jobs in Saudi Arabia guide</a> covers the same questions under the Saudi system, where the sponsorship rules work differently.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is a SIRA licence valid across the UAE?</h3>
<p>No. SIRA is the Dubai regulator, under Dubai Police, and covers Dubai. Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah are regulated by the Private Security Business Department of Abu Dhabi Police, and a licence from one does not permit work in the other's territory.</p>

<h3>Which licence do I need for a job in Abu Dhabi or Sharjah?</h3>
<p>A PSBD licence, not SIRA. Confirm which emirate the site is in before you enrol on any training course, because the two regimes are separate.</p>

<h3>How much do security guards earn in the UAE?</h3>
<p>Published ranges run from about AED 1,800 to AED 3,000 a month for entry to mid-experience guards, with supervisory roles quoted from AED 4,000 upward. Salaries are not subject to personal income tax.</p>

<h3>How is end-of-service gratuity calculated in the UAE?</h3>
<p>On basic salary alone under Federal Decree-Law No. 33 of 2021. Housing, transport and other allowances are excluded. Eligibility begins after one year of continuous service, the total is capped at two years' wage, and final dues are payable within 14 days.</p>

<h3>Should I accept a lower basic salary for better allowances?</h3>
<p>It is fine for monthly cash flow and costly for gratuity. AED 1,200 basic plus AED 800 allowances builds gratuity on a base 40 per cent smaller than AED 2,000 basic, for the same monthly total.</p>

<h3>Who pays for SIRA or PSBD training?</h3>
<p>Many larger facilities management and manned guarding companies sponsor it as part of onboarding. Confirm it at interview and get it in writing rather than paying out of pocket first.</p>

<h3>Do security guard jobs in the UAE include visa and accommodation?</h3>
<p>Commonly yes &mdash; employment visa, shared accommodation, transport to site and medical insurance are frequently included. Ask specifically how many share a room and whether transport is provided or deducted.</p>

<h3>What working hours should I expect?</h3>
<p>Manned guarding commonly runs twelve-hour shifts. A monthly salary quoted against a twelve-hour day is a very different rate from the same figure against eight hours, so ask about shift length, days off and how overtime is treated.</p>

<h2>People Also Search For</h2>

<h3>Security guard jobs in Dubai</h3>
<p>SIRA territory. The largest single market, concentrated in malls, hotels and towers.</p>

<h3>SIRA licence requirements</h3>
<p>Residency and Emirates ID, good conduct and medical certificates, approved training, and a written and practical exam &mdash; normally submitted through the employer.</p>

<h3>PSBD licence Abu Dhabi</h3>
<p>The authority for Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah. A separate card from SIRA.</p>

<h3>Security guard salary in UAE per month</h3>
<p>Around AED 1,800 to 3,000 for general guards. Read it against the shift length and the basic-to-allowance split.</p>

<h3>Security jobs in UAE with visa sponsorship</h3>
<p>Widely available through manned guarding and facilities management companies, usually with accommodation and transport.</p>

<h3>Mall security jobs Dubai</h3>
<p>The most common Dubai posting. Customer-facing, long standing shifts, SIRA licensed.</p>

<h3>Camp security guard jobs UAE</h3>
<p>Construction and labour accommodation sites, typically with accommodation and transport included as standard.</p>

<h3>Security supervisor jobs UAE</h3>
<p>Quoted from AED 4,000 upward. Note the gap in the published data between guard and supervisor bands.</p>

<h2>More Job Guides</h2>

<p>Comparing Gulf routes and other markets? These cover them:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; the same trade under the Saudi Labour Law and sponsorship system.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; another Gulf route, and how Musaned processing really works.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; site work on a sponsored Gulf contract.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; the same package questions applied to cleaning contracts.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; a non-Gulf route where the pay floor is set by law.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; British entry-level work and what self-employed really costs.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; the European industrial route and its visa requirements.</li>
    <li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; office work at home instead of a Gulf contract.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-desk work under the same labour law, and the free zone that runs a different one.</li>
    <li><a href="/blog/accountant-jobs-in-uae">Accountant Jobs in UAE</a> &mdash; a graduate route into the Emirates, and the tax deadlines driving its hiring.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or financial advice. Licensing rules, labour law provisions and salary levels change, and figures on any job board are a moving average rather than a statistic. Confirm the current position with SIRA or the Private Security Business Department as applicable, and with the employer's own advertisement, before applying or paying for any training.</p>
HTML;
    }
}
