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
 * "Security Guard Jobs in Saudi Arabia" — a sector guide rather than one
 * vacancy, so the apply link goes to an Indeed search and the post carries no
 * JobPosting markup.
 *
 * The correction the guide is built around: the SAR 4,000-6,500 figures that
 * circulate for this role are the monthly price a client pays for a manned
 * guard post, not what the guard is paid. A 24-hour post needs three or more
 * guards, so quoting it as a salary overstates the job by roughly three times.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SecurityGuardJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/jobs?q=security+guard&l=Saudi+Arabia';

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
        $title = 'Security Guard Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What security guards are really paid in SAR, why the widely quoted SAR 4,000 to 6,500 figure is a contract price rather than a salary, the Ministry of Interior licence you need, and how the visa process works from Pakistan.',
                'content' => $content,
                'featured_image' => 'blogs/security-guard-jobs-in-saudi-arabia.jpg',
                'tags' => 'security guard jobs in saudi arabia, security guard salary in saudi arabia, security jobs riyadh, security guard jobs for pakistani, moi security licence, security jobs saudi arabia visa sponsorship, watchman jobs saudi arabia, security supervisor jobs jeddah',
                'meta_title' => 'Security Guard Jobs in Saudi Arabia — Salary Guide',
                'meta_description' => 'Security guard jobs in Saudi Arabia: real SAR salary ranges, why the quoted SAR 4,000-6,500 is a contract price not a wage, the MOI licence, and how to apply.',
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
            ['name' => 'Saudi Employers & Licensed Agencies (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'saudi-employers-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Riyadh, Jeddah and Dammam', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'security'],
            ['name' => 'Security']
        );

        Job::updateOrCreate(
            [
                'position' => 'Security Guard — Malls, Hotels, Sites and Compounds',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => '48 hours a week under the Saudi Labour Law; shift patterns vary',
                'language' => 'Basic Arabic or English; incident reports are usually written in Arabic',
                'salary_currency' => 'SAR',
                'salary_period' => 'Monthly',
                'salary_minimum' => 1400,
                'salary_maximum' => 5000,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Security guard roles in Riyadh, Jeddah and Dammam. SAR 1,400-5,000 a month plus accommodation, with the Ministry of Interior licence arranged for you.',
                'seo_keywords' => 'security guard jobs in saudi arabia, security guard salary saudi arabia, security jobs riyadh, security guard jobs for pakistani, security supervisor jobs jeddah, moi security guard licence',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Licensed Saudi security companies staff malls, hotels, hospitals, residential compounds, construction sites, warehouses and corporate premises across Riyadh, Jeddah and Dammam, and recruit foreign guards through licensed agencies and through Iqama transfers inside the Kingdom.</p>

<h3>What the pay actually is</h3>
<p>Entry-level unarmed guards are commonly advertised at <strong>SAR 1,400&ndash;2,500 a month</strong> in base salary, rising to roughly <strong>SAR 2,600&ndash;3,800</strong> on longer 12-hour shift patterns, with supervisors above that. Ignore the SAR 4,000&ndash;6,500 figures that circulate for this role: those are the monthly price a client pays a security company to man a post around the clock, and a 24-hour post takes three or more guards to cover. It is a contract price, not a wage.</p>

<h3>Requirements</h3>
<ul>
    <li>Usually 21 or older, with a basic physical fitness assessment</li>
    <li>Able to read and write in Arabic or the company's working language &mdash; incident reports are a core part of the job</li>
    <li>A Ministry of Interior civilian security licence, arranged by the employer and issued to you as an individual</li>
    <li>Clean police record and a medical fitness certificate from an approved GAMCA / Wafid centre</li>
    <li>Employer-sponsored work visa, or a transferable Iqama if you are already in the Kingdom</li>
    <li>Prior security, military or police experience is preferred but not required for entry-level posts</li>
</ul>

<h3>What is on offer</h3>
<ul>
    <li>Accommodation, transport to site and often meals, on top of base salary</li>
    <li>Overtime at 150% of basic wage for hours beyond the legal limit</li>
    <li>Employer-sponsored Iqama, medical insurance and end-of-service benefit</li>
    <li>Annual return flight on most agency contracts &mdash; confirm it in writing</li>
    <li>Progression to supervisor and shift-in-charge roles with experience and language ability</li>
</ul>

<h3>Before you pay anyone anything</h3>
<p><strong>Never pay for a visa or a job offer.</strong> Saudi rules place recruitment costs on the employer. In Pakistan, use only an Overseas Employment Promoter licensed by the <strong>Bureau of Emigration and Overseas Employment</strong> &mdash; that is the licensing authority, not any trade association &mdash; and equivalently an eMigrate-registered agent in India, a BMET-registered agency in Bangladesh, or a Department of Migrant Workers-licensed agency in the Philippines. Treat any &quot;free visa&quot; or &quot;azad visa&quot; offer as illegal; working for anyone other than your registered sponsor exposes you to an absconding (huroob) report and deportation.</p>

<p><strong>Note:</strong> pay, hours and contract terms are set by the individual employer or agency &mdash; not by JobGader. Listings on aggregator sites vary in quality; verify the employer and the contract before travelling.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Malls, hotels, hospitals, compounds, warehouses and every active construction site in the Kingdom needs manned security, and Vision 2030 has added all of them at once. That makes <strong>security guard jobs in Saudi Arabia</strong> one of the few routes into the GCC that needs no degree and no trade certificate. It is also a role where the salary figures circulating online are wrong by roughly three times &mdash; not because anyone is lying, but because two different numbers get confused. This guide starts there, because it is the thing most likely to cost you money.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/jobs?q=security+guard&l=Saudi+Arabia" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🛡️ Search Security Guard Jobs in Saudi Arabia &rarr;
    </a>
</div>

<h2>The Salary Figure That Is Not a Salary</h2>

<p>You will find pages quoting <strong>SAR 4,200 to 6,500 a month for &quot;24-hour coverage&quot;</strong>, and others quoting an average of SAR 55,000 to 77,000 a year. Both come from real data. Neither is an entry-level guard's wage.</p>

<p>A <strong>manned guarding post</strong> covered 24 hours a day cannot be staffed by one person. Saudi Labour Law caps you at 8 hours a day and 48 hours a week, so a round-the-clock post takes three guards on rotation plus relief cover for rest days and leave. The SAR 4,200 to 6,500 figure is what a client company pays a security firm each month <em>for that post</em> &mdash; it covers three or more salaries, the firm's licensing and insurance, uniforms, supervision and margin. Divided across the people actually standing there, it is nothing like a per-person wage.</p>

<p>The annual averages have a different problem: they blend experienced, licensed and supervisory guards, including Saudi nationals, into one mean. Useful for the sector, misleading for someone applying to their first post from abroad.</p>

<h2>What Security Guards Are Actually Paid</h2>

<p>These are the ranges commonly advertised to foreign applicants. Treat them as a market picture, not a quotation:</p>

<table>
    <thead>
        <tr>
            <th>Level</th>
            <th>Commonly Advertised Monthly Salary (SAR)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Entry-level unarmed guard (base salary)</td>
            <td>1,400 &ndash; 2,500</td>
        </tr>
        <tr>
            <td>Entry-level including housing and food allowance</td>
            <td>2,000 &ndash; 3,500 total value</td>
        </tr>
        <tr>
            <td>Longer 12-hour shift patterns</td>
            <td>2,600 &ndash; 3,800</td>
        </tr>
        <tr>
            <td>Experienced or licensed guard</td>
            <td>3,000 &ndash; 5,000</td>
        </tr>
        <tr>
            <td>Supervisor / shift in charge</td>
            <td>Higher fixed salary, normally 5+ years and language ability</td>
        </tr>
    </tbody>
</table>

<p>Two rules make these numbers comparable. First, <strong>overtime beyond the legal limit is paid at 150% of your basic wage</strong> under the Saudi Labour Law, and for Muslim employees the working week drops to 36 hours during Ramadan. If a long-shift package looks generous, check how much of it is overtime you are legally owed anyway. Second, <strong>Saudi Arabia has no statutory minimum wage for expatriate workers</strong> &mdash; the SAR 4,000 figure repeated online is the Saudization threshold for counting a Saudi national as a full employee and has nothing to do with your pay.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-guard-jobs-in-saudi-arabia-riyadh.jpg"
         alt="Security guard on duty outside a corporate building in Riyadh — security guard jobs in Saudi Arabia"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Ministry of Interior Licence &mdash; and Who Pays for It</h2>

<p>Private security in Saudi Arabia is regulated by the <strong>Ministry of Interior</strong>. The security company must be licensed to operate, and guards are vetted and licensed as individuals rather than covered by a blanket company permit. In practice this means your name, your record and your medical clearance go through the process, and the licence follows you rather than the employer.</p>

<p>Two consequences worth knowing. The vetting is why a <strong>clean police record</strong> is non-negotiable in this sector, more so than in cleaning or construction. And because the licence is arranged by your employer as part of hiring you, <strong>you should not be asked to pay for it</strong>. An agent charging you a fee for a security licence is charging you for the employer's obligation.</p>

<p>Before you accept an offer, check that the employer is a licensed security company rather than an intermediary. Ask for the company's licence details and the name that will appear on your Iqama &mdash; a legitimate employer answers this without hesitation.</p>

<h2>Requirements for Foreign Applicants</h2>

<ul>
    <li><strong>Age</strong> &mdash; typically 21 or older.</li>
    <li><strong>Physical fitness</strong> &mdash; a basic strength and endurance assessment is standard, and posts are largely standing work across a full shift.</li>
    <li><strong>Literacy in Arabic or the company's working language.</strong> This is a real requirement, not a formality: writing incident reports and logging entries is a core part of the job, and it is the most common reason applicants are turned down at interview.</li>
    <li><strong>A Ministry of Interior civilian guard licence</strong>, arranged by the employer and issued to you personally.</li>
    <li><strong>A valid Iqama sponsored by the employer</strong>, or a transferable Iqama if you are already in the Kingdom.</li>
    <li><strong>Clean police record and a medical fitness certificate</strong> from an approved GAMCA / Wafid centre.</li>
</ul>

<p>Prior security, military or police service helps and often moves you up a pay band, but entry-level posts are genuinely open to applicants without it.</p>

<h2>Where Security Guards Work</h2>

<ul>
    <li><strong>Malls and retail</strong> &mdash; the largest single employer of guards, with fixed posts, patrols and CCTV monitoring roles.</li>
    <li><strong>Hotels and hospitality</strong> &mdash; front-of-house presence, so appearance, manner and language matter more here than elsewhere.</li>
    <li><strong>Hospitals and clinics</strong> &mdash; access control and de-escalation, often with additional training provided.</li>
    <li><strong>Residential compounds</strong> &mdash; gate control and visitor management, usually quieter posts on longer shifts.</li>
    <li><strong>Construction sites and warehouses</strong> &mdash; asset protection, often night shifts and often on the project camp.</li>
    <li><strong>Corporate and industrial premises</strong> &mdash; the best-paid non-supervisory posts, with stricter vetting.</li>
</ul>

<h2>Women in Saudi Security Roles</h2>

<p>Licensed female security guards are a relatively recent addition to the Saudi market and are concentrated in healthcare, retail and venues where female staff are needed for search and access control. Demand in those settings has grown faster than the number of licensed women available to fill it, so employers recruiting for these posts tend to compete harder on package than for equivalent male posts. Treat any specific percentage premium you read online with caution &mdash; the roles exist and are growing, but the numbers quoted for them are rarely sourced.</p>

<h2>The Visa Process from Pakistan, India and Bangladesh</h2>

<ol>
    <li><strong>A job offer comes first.</strong> There is no legal route to a Saudi work visa without employer sponsorship &mdash; nobody can sell you one in advance.</li>
    <li><strong>The employer obtains work authorisation</strong> from the Ministry of Human Resources and Social Development.</li>
    <li><strong>You prepare documents</strong> &mdash; passport, attested certificates, police clearance certificate, medical report and the employment contract.</li>
    <li><strong>The visa is stamped</strong> at the Saudi embassy or consulate. Budget roughly two to six weeks from complete documents, longer if attestation is outstanding.</li>
    <li><strong>You travel and the employer issues your Iqama</strong>, and arranges your Ministry of Interior guard licence.</li>
</ol>

<p>Once your Iqama is issued you may later be able to apply for a family visa, though this depends on your profession classification and salary level and is not automatic for entry-level posts.</p>

<h2>Use the Right Licensing Authority in Your Country</h2>

<p>This is worth being precise about, because scam agents rely on people not knowing the difference:</p>

<ul>
    <li><strong>Pakistan:</strong> the licensing authority is the <strong>Bureau of Emigration and Overseas Employment</strong>, and licensed agencies are Overseas Employment Promoters. Industry associations are not licensing bodies &mdash; check the OEP licence itself and confirm it is current.</li>
    <li><strong>India:</strong> use a Recruiting Agent registered on the Ministry of External Affairs <strong>eMigrate</strong> system, and complete emigration clearance where it applies to you.</li>
    <li><strong>Bangladesh:</strong> use an agency registered with <strong>BMET</strong> and keep your smart card and clearance documents.</li>
    <li><strong>Philippines:</strong> use an agency licensed by the <strong>Department of Migrant Workers</strong> and insist on a verified employment contract.</li>
</ul>

<p>In every case, ask for the employer's name and the visa authorisation number, verify the employer independently rather than through the agent, and keep every receipt.</p>

<h2>What a &quot;Free Visa&quot; Really Means</h2>

<p>Security work is often advertised on a &quot;free visa&quot; or &quot;azad visa&quot;. You would enter under one registered sponsor and work for whoever you like, usually paying that sponsor monthly. <strong>This is illegal in Saudi Arabia</strong> and it is a particularly bad idea in security, because the role requires an individual Ministry of Interior licence tied to a licensed employer. Working a guard post outside that framework means your sponsor can file an absconding report (<strong>huroob</strong>) at any time, your wages sit outside the Wage Protection System, and the realistic outcome is detention, fines, deportation and a re-entry ban.</p>

<h2>How to Search and Apply on Indeed</h2>

<ol>
    <li><strong>Search &quot;Security Guard&quot; with &quot;Saudi Arabia&quot;</strong> as the location, or narrow to Riyadh, Jeddah or Dammam. From Pakistan, <code>pk.indeed.com</code> surfaces the same listings with local formatting.</li>
    <li><strong>Filter to full-time</strong> and, where the option appears, to listings mentioning visa sponsorship.</li>
    <li><strong>Open the company profile before applying.</strong> A legitimate employer is a licensed Saudi security company, not an individual agent posting from a personal account.</li>
    <li><strong>Apply and upload your CV.</strong> Lead with any security, military or police service, your Arabic and English level, your fitness, and your visa or Iqama status &mdash; those four decide the shortlist.</li>
    <li><strong>Check the company's own careers page too.</strong> Direct listings are often more current than the aggregated copy.</li>
    <li><strong>Confirm the package in writing</strong> &mdash; base salary, shift pattern, overtime, accommodation, food, transport, medical cover and flight &mdash; before you complete any medical or visa step.</li>
</ol>

<p><strong>One rule above all:</strong> if a listing or an agent asks for an upfront payment for a visa or for processing, stop. Legitimate Saudi employers carry recruitment costs themselves. That single test filters out most of the fraud in this category.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/jobs?q=security+guard&l=Saudi+Arabia" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Browse Security Guard Listings in Saudi Arabia &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the real salary for a security guard in Saudi Arabia?</h3>
<p>Entry-level unarmed guards are commonly advertised at SAR 1,400 to 2,500 a month in base salary, reaching SAR 2,000 to 3,500 in total value once housing and food allowances are counted, and SAR 2,600 to 3,800 on longer 12-hour shift patterns. Experienced licensed guards reach SAR 3,000 to 5,000.</p>

<h3>Why do some sites say security guards earn SAR 6,500 a month?</h3>
<p>Because that figure is the monthly price a client pays a security company to man a post 24 hours a day. A round-the-clock post needs three or more guards on rotation, plus the firm's licensing, insurance, uniforms, supervision and margin. It is a contract price, not one person's wage.</p>

<h3>Do I need a licence to work as a security guard in Saudi Arabia?</h3>
<p>Yes. Private security is regulated by the Ministry of Interior, and guards are vetted and licensed individually rather than under a blanket company permit. Your employer arranges it as part of hiring you, and you should not be asked to pay for it.</p>

<h3>Do I need to speak Arabic?</h3>
<p>You need to read and write in Arabic or in the company's working language, because incident reporting and log-keeping are core parts of the job. This is a genuine requirement and the most common reason applicants are rejected at interview.</p>

<h3>Can I get a security guard job in Saudi Arabia without experience?</h3>
<p>Yes. Entry-level posts in malls, compounds and warehouses are open to applicants with no security background. Prior security, military or police service is preferred and often moves you up a pay band.</p>

<h3>How long does the visa process take from Pakistan?</h3>
<p>Roughly two to six weeks from the point your documents are complete, and longer if attestation or police clearance is outstanding. A job offer must exist first &mdash; there is no legal way to obtain a Saudi work visa without employer sponsorship.</p>

<h3>Should I ever pay an agent for a Saudi security job?</h3>
<p>No. Saudi rules place recruitment costs on the employer, and your own government caps what a licensed agency may charge. In Pakistan check the Overseas Employment Promoter licence issued by the Bureau of Emigration and Overseas Employment, not membership of any trade association.</p>

<h3>What are the working hours and overtime rules?</h3>
<p>Eight hours a day and 48 a week under the Saudi Labour Law, dropping to 36 hours a week for Muslim employees during Ramadan. Hours beyond the legal limit are paid at 150% of your basic wage, which is an entitlement rather than a bonus.</p>

<h2>People Also Search For</h2>

<h3>Security guard salary in Saudi Arabia per month</h3>
<p>SAR 1,400 to 2,500 base for entry-level, SAR 2,600 to 3,800 on 12-hour shift patterns, and SAR 3,000 to 5,000 for experienced licensed guards. The higher figures quoted elsewhere are per-post contract prices.</p>

<h3>Security guard jobs in Saudi Arabia for Pakistani applicants</h3>
<p>Steady demand across malls, compounds and construction sites. Apply only through an Overseas Employment Promoter licensed by the Bureau of Emigration and Overseas Employment.</p>

<h3>MOI security guard licence Saudi Arabia</h3>
<p>Issued by the Ministry of Interior to the individual guard, not to the company. Your employer arranges it during hiring, and you should not be charged for it.</p>

<h3>Security jobs in Riyadh and Jeddah</h3>
<p>Riyadh carries the highest volume across retail, corporate and compound posts; Jeddah is weighted towards hospitality, retail and port-adjacent industrial premises.</p>

<h3>Female security guard jobs in Saudi Arabia</h3>
<p>A newer and growing segment concentrated in healthcare, retail and venues needing female search and access control. Demand has outpaced the supply of licensed women, so packages can be more competitive.</p>

<h3>Security supervisor jobs Saudi Arabia</h3>
<p>Normally five or more years of experience plus language ability, on a higher fixed salary than guard posts. The usual progression route from an entry-level post.</p>

<h3>Saudi Arabia security guard visa free</h3>
<p>The &quot;free visa&quot; or azad visa arrangement is illegal, and it is especially unworkable in security because the role requires an individual Ministry of Interior licence tied to a licensed employer.</p>

<h3>Security guard jobs with accommodation Saudi Arabia</h3>
<p>Accommodation, transport and often meals are standard on agency contracts, which is why the base salary alone understates the package. Get every line itemised in writing.</p>

<h2>More Job Guides</h2>

<p>Comparing entry-level routes into the Gulf and beyond? These cover the rest:</p>

<ul>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Labour Law and Musaned split, and what each route pays.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; hotel, hospital and household cleaning routes into the Kingdom.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; giga-project hiring from trades to project management.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why most UK operative roles cannot be sponsored at all.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Saudi labour, security-licensing and visa rules change &mdash; confirm current requirements through the Ministry of Human Resources and Social Development, the Ministry of Interior, or your own country's overseas employment authority before paying any fee or signing a contract.</p>
HTML;
    }
}
