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
 * "No Experience Jobs in Saudi Arabia" — the entry-level search for the Gulf,
 * written against two things the standard draft gets backwards: which jobs a
 * foreigner may actually hold, and what protects the wage once they hold one.
 *
 * Corrections to the draft:
 *
 * 1. It puts "Retail Sales Associate" first on its list of jobs to target. The
 *    Ministry of Human Resources and Social Development raised Saudization in
 *    sales professions to 60 per cent effective 19/01/2026, in establishments
 *    employing three or more workers in sales roles, and the targeted list
 *    names Retail Sales Representative, Wholesale Sales Representative, Sales
 *    Representative, Sales Specialist, ICT Sales Specialist, Commercial
 *    Specialist, Sales Manager and Commodity Broker. Pointing foreign readers
 *    at the one category being closed to them is the worst advice in the piece.
 *
 * 2. It describes the system as "employer sponsorship (Kafala-based)" as if
 *    nothing had changed. The Labour Reform Initiative took effect on
 *    14 March 2021: a worker covered by the labour law can transfer employer
 *    without consent after a year or at contract expiry, and can obtain exit
 *    and re-entry and final exit without the employer's approval. Domestic
 *    workers and farm workers are excluded from those reforms, which is the
 *    detail a job seeker actually needs.
 *
 * 3. Its salary range is presented as though it were a floor. There is no
 *    statutory minimum wage for expatriate private-sector workers in Saudi
 *    Arabia. The SAR 4,000 figure that circulates online is a Nitaqat counting
 *    rule for Saudi nationals, raised from SAR 3,000, under which a Saudi paid
 *    SAR 3,000 counts as half a unit. It has never applied to foreign workers.
 *
 * 4. Where a wage floor does exist, it exists by decision, not by default. The
 *    same ministry decision that set marketing Saudization at 60 per cent set a
 *    minimum monthly wage of SAR 5,500 for those roles. Floors in Saudi Arabia
 *    are attached to specific Saudization decisions, not to the labour law.
 *
 * 5. It treats HRDF as a footnote for Saudi nationals. HRDF (Hadaf) funds
 *    recruitment, training and job-stability incentives that make hiring a
 *    Saudi cheaper than hiring a foreigner, which is precisely why entry-level
 *    competition is tightening for expatriates.
 *
 * 6. Its scam warning is right but toothless. The concrete rule is that a
 *    legitimate offer is issued and tracked through the ministry's own systems
 *    and paid through the Wage Protection System, so a job that exists only in
 *    a WhatsApp thread is the warning sign, not the fee alone.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NoExperienceJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/q-no-experience-jobs.html';

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
        $title = 'No Experience Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Sales and retail roles are closing to foreign workers: Saudization in sales professions rose to 60 per cent effective 19 January 2026. There is no statutory minimum wage for expatriates, and the 4,000 SAR figure applies only to Saudi nationals.',
                'content' => $content,
                'featured_image' => 'blogs/no-experience-jobs-in-saudi-arabia.jpg',
                'tags' => 'no experience jobs in saudi arabia, entry level jobs saudi arabia, saudization nitaqat, iqama work permit, saudi arabia minimum wage, warehouse jobs saudi arabia, hospitality jobs saudi arabia, wage protection system',
                'meta_title' => 'No Experience Jobs in Saudi Arabia: Rules and Real Pay',
                'meta_description' => 'No experience jobs in Saudi Arabia: which roles are closing to foreign workers, why there is no expat minimum wage, and what the 2021 labour reforms changed.',
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
            ['name' => 'Saudi Employers Advertising Entry-Level Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'saudi-no-experience-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Entry-Level Roles — Warehousing, Hospitality and Facilities, Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Commonly 48 hours a week under the Saudi Labour Law, reduced during Ramadan for Muslim workers',
                'language' => 'English or Arabic',
                // Saudi Arabia sets no statutory minimum wage for expatriate
                // private-sector workers, and the SAR 4,000 Nitaqat figure
                // applies only to Saudi nationals, so any band printed here
                // would imply a protection that does not exist.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Entry-level roles with employers in Saudi Arabia. Check whether the occupation is open to non-Saudis and confirm the wage in the contract, because no statutory floor applies to expatriates.',
                'seo_keywords' => 'no experience jobs saudi arabia, entry level jobs riyadh, warehouse jobs jeddah, hospitality jobs saudi arabia, iqama jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Employers across Riyadh, Jeddah, Dammam and the Vision 2030 development sites advertise entry-level roles continuously, in warehousing and logistics, hospitality, facilities and cleaning, and site support. Training is given on the job. What varies, and what decides whether you can take the role at all, is whether the occupation is open to non-Saudi workers.</p>

<h3>What the work involves</h3>
<p>Receiving, picking and dispatching stock; kitchen and housekeeping support in hotels and restaurants; cleaning and maintaining offices, malls and residential compounds; assisting on construction and fit-out sites; and basic record-keeping. Shifts are long, often six days a week, and much of the work is done standing or outdoors in heat.</p>

<h3>Requirements</h3>
<ul>
    <li>No formal qualifications for most roles, and no prior experience</li>
    <li>A valid Iqama, or a work visa arranged by the employer before you travel</li>
    <li>Basic English or Arabic, depending on the employer and the site</li>
    <li>Physical fitness, and a medical clearance where the employer requires one</li>
    <li>For some occupations, professional verification before the work permit is issued</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check two things before anything else: that the occupation is not restricted to Saudi nationals, and that the wage is written in the contract.</strong> Saudization decisions have closed a growing list of sales and marketing job titles to foreign workers, and there is no statutory minimum wage for expatriates, so the contract is the only floor you have.</p>

<p><strong>Note:</strong> pay, contract terms, accommodation and eligibility are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying, and never pay a fee for a job offer or a visa.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to entry-level work in Saudi Arabia open with the same list of jobs to target. The list begins with the one category the Kingdom is actively closing to foreign workers. This page sets out which roles a non-Saudi can realistically hold in 2026, what actually protects your wage once you have one, and what the labour reforms of 2021 changed about leaving a job or leaving the country.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/no-experience-jobs-in-saudi-arabia-retail.jpg" alt="Entry-level retail and customer-facing work in a Saudi Arabian shopping centre" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Jobs That Are Closing, and the Ones That Are Not</h2>

<p>On 26 January 2026 the Ministry of Human Resources and Social Development announced two decisions raising Saudization rates in the private sector. The one that matters most to a foreign job seeker raised the <strong>Saudization rate in sales professions to 60 per cent, effective 19/01/2026</strong>, applying to establishments employing three or more workers in sales roles. The ministry named the targeted professions: Sales Manager, <strong>Retail Sales Representative</strong>, Wholesale Sales Representative, Sales Representative, Information and Communications Technology (ICT) Sales Specialist, Sales Specialist, Commercial Specialist and Commodity Broker.</p>

<p>A parallel decision raised Saudization in marketing professions to 60 per cent on the same date, covering Marketing Manager, Advertising Agent, Advertising Manager, Graphic Designer, Advertising Designer, Public Relations Specialist, Advertising and Promotion Specialist, Marketing Specialist, Public Relations Manager and Photographer &mdash; and it set <strong>a minimum monthly wage of SAR 5,500</strong> for those roles. Both decisions come into force three months from announcement, and the ministry has published procedural guides setting out how the rates are calculated and what compliance requires.</p>

<p>Read that against the advice you will find elsewhere. "Retail Sales Associate" is the first suggestion in almost every entry-level guide to Saudi Arabia. It is also the job title sitting inside a 60 per cent Saudization quota. An establishment that must fill six in ten of its sales roles with Saudi nationals is not looking to sponsor an inexperienced foreigner into the seventh.</p>

<p>The restrictions are occupation-specific, not sector-wide, and that distinction is the whole trick to searching well. A shop still needs stock handled, a warehouse still needs pickers, a hotel still needs housekeeping and kitchen staff, a site still needs labourers, and a mall still needs cleaning and maintenance. Those occupations sit outside the sales and marketing job-title lists. <strong>Search by the task, not by the shop.</strong></p>

<h2>What Actually Protects Your Wage</h2>

<p>This is where most guides quote a salary range and leave the reader assuming it is a floor. It is not.</p>

<p><strong>Saudi Arabia sets no statutory minimum wage for expatriate private-sector workers.</strong> Pay is whatever the employment contract says. The SAR 4,000 figure that circulates in every "minimum wage in Saudi Arabia" article is a <em>Nitaqat counting rule</em> and it applies to Saudi nationals: the Minister of Human Resources raised it from SAR 3,000 so that a Saudi employee paid at least SAR 4,000 counts as one full worker in an establishment's Saudization percentage, while a Saudi paid SAR 3,000 counts as only half a unit. It is a lever to make employers pay Saudis more. It has never been a floor for foreign workers.</p>

<p>So what does protect you? Three things, in order of usefulness:</p>

<ul>
    <li><strong>The written contract.</strong> Because there is no statutory floor, the number in the contract is the number you are owed. Get it in writing before you travel, in a language you read, and keep a copy</li>
    <li><strong>The Wage Protection System.</strong> Private-sector wages are paid and monitored electronically through WPS, which is what makes non-payment visible to the ministry rather than a private dispute between you and an employer who holds your paperwork</li>
    <li><strong>Occupation-specific decisions.</strong> Where a wage floor exists, it was created by a Saudization decision, as the SAR 5,500 marketing minimum was. It attaches to that occupation, not to you</li>
</ul>

<p>One more figure worth knowing: where a worker is registered with GOSI, the wage subject to social insurance contributions is not treated as less than SAR 1,500. That is a contributions rule, not a wage guarantee, and it should not be read as a minimum salary.</p>

<h2>What the 2021 Labour Reforms Changed</h2>

<p>Guides that describe Saudi employment as simply "Kafala-based" are describing the system as it was before 14 March 2021. On that date the <strong>Labour Reform Initiative</strong> took effect, and for workers covered by the labour law it changed three things that used to define the relationship:</p>

<ul>
    <li><strong>Job transfer.</strong> You can move to another employer without your current employer's consent after completing one year with them, or when the contract expires. Transfer during a contract is possible where you notify the employer within the set period</li>
    <li><strong>Exit and re-entry.</strong> You can obtain exit and re-entry visas without the employer's approval. The permit was not abolished: the request goes to the ministry, which notifies the employer electronically</li>
    <li><strong>Final exit.</strong> You can obtain the final exit stamp without the sponsor's approval</li>
</ul>

<p><strong>The exclusions matter as much as the reforms.</strong> Domestic workers and farm workers are not covered by the labour law and so did not gain these freedoms. If a recruiter offers you a "household" or "farm" position, understand that you are being offered a contract outside the protections described above, whatever the advertisement calls the role.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/no-experience-jobs-in-saudi-arabia-warehouse.jpg" alt="Warehouse and logistics work, an entry-level route that remains open to foreign workers in Saudi Arabia" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Why Entry-Level Competition Is Tightening</h2>

<p>The Saudization decisions are only half the picture. The other half is money. The ministry's own announcement notes that establishments benefit from a package of incentives through the <strong>Human Resources Development Fund (HRDF &mdash; Hadaf)</strong>: support for recruitment, training and upskilling, employment and job stability, plus priority access to Saudization support programmes.</p>

<p>Put plainly, the state subsidises the cost of hiring and training a Saudi national. An employer weighing an inexperienced Saudi against an inexperienced foreigner is not comparing two equal costs, and in a quota-covered occupation the comparison does not happen at all. This is why "no experience needed" advice written for Western labour markets transfers badly here: in Saudi Arabia, being inexperienced is not the obstacle. Being inexperienced <em>in a quota-covered occupation</em> is.</p>

<p>It also explains where the genuine openings are. Vision 2030 construction, logistics, hospitality and facilities work continues to expand faster than the national workforce covers it, and those occupations have not been the target of the sales and marketing decisions.</p>

<h2>Getting the Job Offer Right</h2>

<p>The single most common way a Saudi job goes wrong for a foreign worker is that the job never properly existed. A legitimate offer leaves a paper trail in the Kingdom's own systems: the employer holds a valid work visa allocation, the offer is documented, the Iqama is issued after arrival, and wages then flow through the Wage Protection System. A job that exists only in a chat thread, with a fee attached and a promise to sort the paperwork later, is the pattern to walk away from.</p>

<ul>
    <li><strong>Never pay for a job offer or a visa.</strong> Recruitment costs belong to the employer. A demand for payment is the clearest single signal that the offer is not real</li>
    <li><strong>Get the occupation title in writing</strong> and check it against the Saudization decisions, because the title on your work permit is what governs what you may legally do</li>
    <li><strong>Confirm what is included.</strong> Accommodation, transport and annual leave are contract terms in Saudi Arabia, not statutory add-ons, and their absence is a pay cut in disguise</li>
    <li><strong>Do not hand over your passport</strong> or original documents to an intermediary</li>
    <li><strong>Use licensed recruitment channels</strong> rather than informal agents, so there is a regulated counterparty if the placement fails</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-no-experience-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; See Current Saudi Entry-Level Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can a foreigner still get a retail sales job in Saudi Arabia?</h3>
<p>It is getting much harder. Saudization in sales professions rose to 60 per cent effective 19/01/2026 in establishments with three or more sales workers, and the targeted list names Retail Sales Representative among others. Stock, warehouse, cleaning and hospitality roles in the same buildings are not on that list.</p>

<h3>What is the minimum wage in Saudi Arabia for foreign workers?</h3>
<p>There is none. No statutory minimum wage applies to expatriate private-sector workers, so your pay is whatever the contract states. The widely quoted SAR 4,000 is a Nitaqat counting threshold for Saudi nationals, raised from SAR 3,000, not a floor for foreigners.</p>

<h3>Has Saudi Arabia abolished the Kafala system?</h3>
<p>It has been reformed rather than abolished. Since 14 March 2021, workers covered by the labour law can change employer without consent after a year or at contract expiry, and can arrange exit, re-entry and final exit without the employer's approval, though the request still goes through the ministry, which notifies the employer.</p>

<h3>Do the 2021 reforms apply to every worker?</h3>
<p>No. Domestic workers and farm workers are excluded because they fall outside the labour law. That exclusion is the reason to check the exact occupation on any offer, rather than the job title used in the advertisement.</p>

<h3>Do I need experience to get a job in Saudi Arabia?</h3>
<p>For warehousing, hospitality, cleaning, facilities and construction support, generally no, and training is given on the job. The real barrier is not experience but whether the occupation is open to non-Saudi workers.</p>

<h3>Can I change employer in Saudi Arabia without permission?</h3>
<p>If you are covered by the labour law, yes, after completing one year with your current employer or when your contract expires. Transfer during a contract is possible where you give notice within the prescribed period.</p>

<h3>How do I know a Saudi job offer is genuine?</h3>
<p>A real offer is documented by an employer with a valid visa allocation, results in an Iqama after arrival, and pays through the Wage Protection System. Any request that you pay for the job or the visa is the signal to stop; recruitment costs are the employer's.</p>

<h3>Is there a wage floor for any private-sector job in Saudi Arabia?</h3>
<p>Only where a specific decision creates one. The ministry's marketing Saudization decision set a minimum monthly wage of SAR 5,500 for the covered marketing professions. Floors like that attach to an occupation under a named decision; they are not a general rule.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Saudization list of jobs for Saudis only</strong> &mdash; occupation-specific decisions, most recently sales and marketing at 60 per cent from 19/01/2026</li>
    <li><strong>Minimum salary in Saudi Arabia 2026</strong> &mdash; SAR 4,000 is a Nitaqat counting rule for Saudi nationals, not an expatriate minimum wage</li>
    <li><strong>Saudi Arabia work visa without experience</strong> &mdash; available in warehousing, hospitality, cleaning and construction support, arranged by the employer before travel</li>
    <li><strong>How to change job in Saudi Arabia</strong> &mdash; permitted without employer consent after one year or at contract expiry, for workers covered by the labour law</li>
    <li><strong>Saudi Arabia exit re-entry visa rules</strong> &mdash; obtainable without employer approval since 14 March 2021, via a request to the ministry</li>
    <li><strong>Wage Protection System Saudi Arabia</strong> &mdash; the electronic payroll monitoring that makes unpaid wages visible to the ministry</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a></li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a></li>
    <li><a href="/blog/kitchen-helper-jobs-in-saudi-arabia">Kitchen Helper Jobs in Saudi Arabia</a></li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a></li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a></li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a></li>
</ul>
HTML;
    }
}
