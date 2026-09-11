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
 * "Customer Service Jobs in Canada" — the Canadian contact centre, retail and
 * public sector market. Written around the measured Job Bank median rather
 * than the job board average, because for a job this close to the legal wage
 * floor the floor explains more than the average does.
 *
 * Corrections to the draft:
 *
 * 1. It gives a national average of $20.43 an hour and, three sentences
 *    later, an entry-level average of $49,142 a year. On a 2,080-hour year
 *    the hourly figure is $42,494. Entry level cannot be $6,648 above the
 *    overall average; the two figures come from different populations of
 *    postings and are silently mixed.
 *
 * 2. It gives the bottom of the range as $18.15 an hour. British Columbia's
 *    general minimum wage is $18.25 from 1 June 2026, so that floor is
 *    already unlawful in the province holding two of the three cities the
 *    draft names as highest paying.
 *
 * 3. It uses only job board figures. Job Bank publishes a measured wage for
 *    this occupation from the Labour Force Survey, and its median is higher
 *    than the average the draft leads with.
 *
 * 4. It lists bilingualism as a skill. For federal roles it is a formal
 *    linguistic profile assessed by the Public Service Commission, and
 *    whether the job is bilingual imperative or non-imperative decides
 *    whether you need the level on the day you are appointed.
 *
 * 5. It presents Service Canada and the CRA as simply available. The Public
 *    Service Employment Act gives preference to veterans, then to Canadian
 *    citizens and permanent residents. A candidate on a work permit is
 *    behind both, which is the single most useful thing to know before
 *    spending a week on a federal application.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CustomerServiceJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-customer-service-jobs.html';

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
        $title = 'Customer Service Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Why the published entry-level figure is higher than the published average, the quoted pay floor that is already below British Columbia minimum wage, and what bilingual imperative really asks of you before a federal job is offered.',
                'content' => $content,
                'featured_image' => 'blogs/customer-service-jobs-in-canada.jpg',
                'tags' => 'customer service jobs canada, call centre jobs canada, customer service representative salary, bilingual customer service jobs, remote customer service jobs canada, government customer service jobs, entry level customer service jobs',
                'meta_title' => 'Customer Service Jobs in Canada',
                'meta_description' => 'Customer service jobs in Canada: the measured Job Bank median, the minimum wage floor that sets the pay, and how federal bilingual hiring really works.',
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
            ['name' => 'Canadian Contact Centre & Retail Service Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-customer-service-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Customer Service Representative — Contact Centre, Retail and Public Sector, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Shift-based, with evening and weekend rotations on most contact centre teams',
                'language' => 'English',
                // Pay for this occupation tracks provincial minimum wages,
                // which range from $15.00 in Alberta upward. A single national
                // band would describe none of those markets accurately.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Contact centre, retail and public sector customer service roles with Canadian employers. Check the provincial minimum wage before judging any offer.',
                'seo_keywords' => 'customer service jobs canada, call centre jobs canada, bilingual customer service jobs, remote customer service jobs canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Telecommunications carriers, banks, insurers, retailers, health authorities and federal departments across Canada hire customer service representatives for phone, chat and in-person work. It is one of the largest entry points into Canadian employment, and one where the legal wage floor in your province explains more about the offer than any national average does.</p>

<h3>What the work involves</h3>
<p>Handling inbound enquiries, complaints and transactions across phone, email and chat; recording everything in a CRM or ticketing system; escalating what cannot be resolved at first contact; and meeting handling-time and quality targets. Sales-adjacent roles add retention and upsell objectives with a commission component attached.</p>

<h3>Requirements</h3>
<ul>
    <li>Clear written and spoken English; French as well for national and federal roles</li>
    <li>Comfort moving between phone, chat and email inside one shift</li>
    <li>CRM, ticketing or contact centre software &mdash; named specifically on most postings</li>
    <li>Conflict resolution, and the patience that goes with escalated calls</li>
    <li>Reliable home internet and a quiet space for remote positions</li>
    <li>Legal authorisation to work in Canada &mdash; federal roles apply a further preference order</li>
</ul>

<h3>How the pay is set</h3>
<ul>
    <li><strong>The measured figure</strong> from Job Bank, drawn from the Labour Force Survey, is a <strong>$22.00 median</strong> for call centre representatives, low $16.00 and high $33.14</li>
    <li><strong>The floor is provincial,</strong> not national: $18.25 in British Columbia, $17.95 in Ontario, $15.00 in Alberta &mdash; a spread of $3.25 an hour before any employer decides anything</li>
    <li><strong>Bilingual roles pay more</strong> and federal ones attach a bilingualism bonus, but only against a tested linguistic profile rather than a claim on a r&eacute;sum&eacute;</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check your province's minimum wage first, then read the offer against it.</strong> For an occupation whose measured low is $16.00 an hour, the legal floor is doing most of the work. An offer that looks generous in Alberta and one that looks ordinary in British Columbia can be the same number.</p>

<p><strong>Note:</strong> pay, shift patterns, language requirements and any hiring preference are set by each employer and by law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Customer service is the largest open door into Canadian employment. It hires in every province, it takes people with no Canadian experience, and it posts constantly. It is also an occupation where almost every published salary figure is describing something slightly different from what you will be offered, and the reason is specific enough to be useful.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-customer-service-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127760; Browse Customer Service Jobs in Canada &rarr;
    </a>
</div>

<h2>Entry Level Cannot Be Above the Average</h2>

<p>Here is what the published data says about this job, in the order it is usually presented.</p>

<p>The national average for a customer service representative is <strong>$20.43 an hour</strong>. Entry-level customer service representatives average <strong>$49,142 a year</strong>.</p>

<p>Work the first one out. A standard full-time year is 2,080 hours, so $20.43 an hour is <strong>$42,494 a year</strong>. The entry-level figure is <strong>$6,648 higher than the overall average</strong> &mdash; which cannot be true of the same population, because entry level is inside the average and pulls it down.</p>

<p>Both numbers are real. They are simply computed from different sets of postings. Hourly figures come from the hourly-paid end of the market: retail desks, part-time shifts, contact centre seats. Annual figures come from postings written as salaries, which skew towards office-based, full-time, often specialised roles. Averaging one against the other produces a picture of a job that nobody holds.</p>

<p>So when you see an hourly average and an annual average in the same article, do not reconcile them. Pick the one that matches the shape of the job you are applying for and ignore the other.</p>

<h2>The Measured Number, and Why It Is Higher</h2>

<p>Canada publishes a real wage measurement for this work. Job Bank reports wages for <strong>customer service representative &mdash; call centre (NOC 64409)</strong> from Statistics Canada's Labour Force Survey:</p>

<ul>
    <li><strong>Low: $16.00 an hour</strong></li>
    <li><strong>Median: $22.00 an hour</strong></li>
    <li><strong>High: $33.14 an hour</strong></li>
</ul>

<p>The government median is <strong>$1.57 above the job board average</strong> the draft guides lead with. That is worth knowing at offer stage, because it means the widely repeated $20.43 is a below-median number being presented as a typical one.</p>

<p>It is worth knowing something else about that table. The $16.00 low comes from a 2023&ndash;2024 reference period, and minimum wages have moved since. <strong>The bottom of any published wage range is history, not an offer.</strong> Which brings us to the number that actually matters.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/customer-service-jobs-in-canada-bilingual.jpg"
         alt="A bilingual contact centre team taking customer calls in a Canadian office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Your Province Sets Your Pay, Not the National Average</h2>

<p>For an occupation whose measured low is $16.00 an hour, the legal minimum wage is not background information. It is the main determinant of the offer. And in Canada it is provincial, so it varies enormously:</p>

<ul>
    <li><strong>British Columbia &mdash; $18.25</strong> an hour from 1 June 2026.</li>
    <li><strong>Ontario &mdash; $17.95</strong> an hour from 1 October 2026, up from $17.60.</li>
    <li><strong>Alberta &mdash; $15.00</strong> an hour, unchanged since October 2018.</li>
</ul>

<p>That is a <strong>$3.25 spread</strong>, or about $6,760 across a full-time year, decided entirely by which side of a provincial border the job sits on and before any employer makes a single decision.</p>

<p>Two consequences follow, and both cut against how these guides are normally written.</p>

<p><strong>The quoted pay floor is already out of date.</strong> Guides give the range for this job as $18.15 to $31.23 an hour. <strong>$18.15 is below British Columbia's minimum wage of $18.25.</strong> Nobody in BC can lawfully be paid the bottom of that published range. If a number in a salary guide is illegal in the province you are applying to, it is telling you about the data's age rather than about your prospects.</p>

<p><strong>The "highest paying cities" list is mostly a map of minimum wages.</strong> Guides name Burnaby, Victoria and Montr&eacute;al. Two of those three are in British Columbia, the province with the highest general minimum wage in the list above. That is not employers in Burnaby being generous; that is the floor being higher. Meanwhile Alberta is regularly described as a strong market, and it has the lowest minimum wage in the country and has not raised it in eight years.</p>

<p>Before you judge any offer, look up your province's current rate and subtract. The gap between the offer and the floor is the only part the employer chose.</p>

<h2>Bilingual Is Not a Skill Line &mdash; It Is a Tested Profile</h2>

<p>Every guide lists "bilingual ability (English/French)" among the skills, next to multitasking and attention to detail. For private sector work that is roughly fair. For the federal roles those same guides recommend &mdash; Service Canada, the Canada Revenue Agency &mdash; it is a serious understatement.</p>

<p>Federal positions carry a <strong>linguistic profile</strong>: three letters, one each for reading, writing and oral interaction. You will see profiles like <strong>BBB</strong> on frontline roles and <strong>CBC</strong> on supervisory and professional ones. Those letters are levels, and they are established by the <strong>Second Language Evaluation</strong> run by the Public Service Commission, not by what you write on your r&eacute;sum&eacute;.</p>

<p>One distinction decides whether you can apply at all today:</p>

<ul>
    <li><strong>Bilingual imperative.</strong> You must already meet the profile at the moment of appointment. No training window, no grace period.</li>
    <li><strong>Bilingual non-imperative.</strong> You can be appointed without the level and complete language training afterwards, under conditions.</li>
</ul>

<p>If you are working towards French, the non-imperative postings are the ones worth your time and the imperative ones are not yet. That single word in a job advertisement saves more effort than any r&eacute;sum&eacute; advice.</p>

<h2>The Federal Preference Order Nobody Explains</h2>

<p>This is the most useful thing on this page for anyone applying to Canada from abroad or on a work permit.</p>

<p>Federal customer service jobs are attractive for the obvious reasons &mdash; security, pension, published pay. But external advertised appointments in the federal public service run under the <strong>Public Service Employment Act</strong>, which sets a statutory order of preference. Eligible veterans come first. Then <strong>Canadian citizens and permanent residents</strong>, who since the 2021 amendments are treated the same as each other.</p>

<p>Everyone else &mdash; including a fully qualified candidate on a valid work permit &mdash; sits <strong>behind both groups</strong>. Meeting the essential qualifications does not move you up; the preference applies among candidates who all qualify.</p>

<p>That does not mean do not apply. It means budget your effort honestly: if you are on a work permit, the private contact centres are your realistic market and the federal process is a long-odds parallel track. If you hold permanent residence, the calculation reverses and federal competitions become one of the strongest things you can be doing.</p>

<p>One more note on federal figures. Guides quote Service Canada customer service roles at <strong>$45,869 to $95,075</strong>. A $49,206 spread does not describe one job. The top of that range belongs to classifications well above a frontline service agent, and reading it as your potential is a mistake. The CRA range of <strong>$53,055 to $63,573</strong> is far more representative of what frontline federal service work actually pays.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/customer-service-jobs-in-canada-remote.jpg"
         alt="A customer service representative working remotely from home in Canada"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Remote Roles, and the One Thing to Confirm</h2>

<p>A large share of Canadian contact centre work is now advertised as remote or hybrid, which is genuinely useful if you are outside the big metros. Two things to establish before accepting.</p>

<p><strong>Which province are you employed in?</strong> Remote work does not detach you from provincial employment standards; your minimum wage, overtime rules and statutory holidays follow the province of employment. An employer in one province hiring you in another needs to be clear about which set applies.</p>

<p><strong>What is monitored, and what is the equipment position?</strong> Contact centre remote work usually comes with call recording, screen monitoring and adherence metrics. That is normal for the industry. What varies is who supplies the headset, the second monitor and the internet allowance, and it is much easier to ask before you start than after.</p>

<p>If remote work is the priority rather than Canada specifically, our <a href="/blog/remote-customer-service-jobs">remote customer service jobs guide</a> covers the worldwide version of this role, including how to tell a genuine posting from the fake ones.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Customer service representative.</strong> The core title, hourly paid, phone and chat. Priced off the provincial floor.</li>
    <li><strong>Call centre representative.</strong> Higher volume, tighter metrics, more scripted. Frequently the fastest to hire.</li>
    <li><strong>Bilingual customer service representative.</strong> Pays a real premium, and for federal work requires a tested profile rather than conversational French.</li>
    <li><strong>Customer service agent, government or contract.</strong> Service Canada and CRA work, subject to the preference order above.</li>
    <li><strong>Senior customer service representative.</strong> Escalations and mentoring. Around $60,019 a year in Ontario on published figures.</li>
    <li><strong>Customer service manager.</strong> Team and department performance, around $72,207 a year nationally.</li>
</ul>

<h2>Skills That Keep Recurring in Listings</h2>

<ul>
    <li><strong>CRM and ticketing software</strong> &mdash; name the actual systems you have used, not "CRM experience".</li>
    <li><strong>Written English</strong>, because chat and email are now most of the volume on many teams.</li>
    <li><strong>French</strong>, tested rather than claimed for anything federal or national.</li>
    <li><strong>De-escalation</strong>, which is what the interview scenario question is really testing.</li>
    <li><strong>Shift reliability</strong>, weighted heavily because scheduling is the industry's biggest operational problem.</li>
    <li><strong>Basic sales</strong> for hybrid service-and-retention roles, where commission is part of the pay.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do customer service representatives make in Canada?</h3>
<p>Job Bank's measured median for call centre representatives is $22.00 an hour, with a low of $16.00 and a high of $33.14. Job board averages sit lower, around $20.43, because they weight different postings.</p>

<h3>Why do the published salary figures disagree with each other?</h3>
<p>Because hourly figures and annual figures are drawn from different sets of postings. That is how a quoted entry-level average of $49,142 ends up above an overall average of $20.43 an hour, which is $42,494 across a full-time year.</p>

<h3>Which province pays customer service best?</h3>
<p>Largely the one with the highest minimum wage, because this occupation sits close to the floor. British Columbia is $18.25 an hour, Ontario $17.95, and Alberta $15.00 and unchanged since 2018.</p>

<h3>What does bilingual imperative mean on a federal job posting?</h3>
<p>That you must already meet the position's linguistic profile when you are appointed. Bilingual non-imperative lets you be appointed first and complete language training afterwards, so those are the postings to target while you are still learning.</p>

<h3>Can I apply for CRA or Service Canada jobs on a work permit?</h3>
<p>You can apply, but the Public Service Employment Act gives preference to veterans first and then to Canadian citizens and permanent residents. A qualified candidate without either sits behind both groups in external advertised processes.</p>

<h3>What is a linguistic profile like BBB or CBC?</h3>
<p>Three levels covering reading, writing and oral interaction, assessed by the Public Service Commission's Second Language Evaluation. BBB is common on frontline roles, CBC on supervisory and professional ones.</p>

<h3>Are remote customer service jobs in Canada legitimate?</h3>
<p>Many are, particularly with established carriers, banks and outsourcers. Confirm which province you are employed in, because your minimum wage and overtime rules follow that rather than where the employer's head office is.</p>

<h3>Do I need Canadian experience to get hired?</h3>
<p>Usually not for contact centre work, which is one of the reasons it is such a common first Canadian job. Named software, clear written English and shift availability count for more than where your previous employer was based.</p>

<h2>People Also Search For</h2>

<h3>Customer service representative salary Canada</h3>
<p>A measured median of $22.00 an hour, well above the $20.43 job board average usually quoted.</p>

<h3>Call centre jobs Canada</h3>
<p>The highest volume entry point, and typically the fastest hiring process in the sector.</p>

<h3>Bilingual customer service jobs Canada</h3>
<p>A genuine pay premium. For federal roles, a tested profile rather than a claim on a r&eacute;sum&eacute;.</p>

<h3>Remote customer service jobs Canada</h3>
<p>Widely available. Confirm your province of employment, because your wage floor and overtime rules follow it.</p>

<h3>Government customer service jobs Canada</h3>
<p>Service Canada and CRA work, behind a statutory preference for veterans, citizens and permanent residents.</p>

<h3>Entry level customer service jobs Canada</h3>
<p>Open to candidates with no Canadian experience. Priced off the provincial minimum wage more than anything else.</p>

<h3>Customer service jobs Toronto and Vancouver</h3>
<p>Ontario's floor is $17.95 and British Columbia's is $18.25, which explains most of the gap between the two cities.</p>

<h3>Customer service manager salary Canada</h3>
<p>Around $72,207 a year on published figures &mdash; the top of the ladder this occupation actually reaches.</p>

<h2>More Job Guides</h2>

<p>Comparing markets and routes? These cover them:</p>

<ul>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the worldwide version of this role and how to spot a fake posting.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-canada">ATS Resume Writer Jobs in Canada</a> &mdash; another Canadian entry route, and how applicant tracking systems actually read a r&eacute;sum&eacute;.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; the adjacent clerical occupation, and the projection you should see before choosing it.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the same customer-facing work priced against a very different wage floor.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; administrative support under Australian award rules.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; the remote support route from outside North America.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest position on US entry-level sponsorship.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a Gulf route where the package structure matters more than the headline.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the agricultural route, and which of its two foreign worker programs you can use.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; client-facing work in the Emirates, and who legally pays for the visa.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; customer-facing sales under Australian award minimums, and when commission may replace a wage.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how employer sponsorship works in Canada, and what CLB 5 really means.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Minimum wages, wage survey figures, language requirements and hiring preferences change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with Job Bank, your provincial employment standards authority and the employer's own advertisement before applying.</p>
HTML;
    }
}
