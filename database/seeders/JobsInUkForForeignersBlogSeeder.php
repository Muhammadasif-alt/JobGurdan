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
 * "Jobs in UK for Foreigners" — the hub guide to working in the UK without
 * British or Irish citizenship. The occupation guides (care, healthcare
 * assistant, IT support, teaching, cook, warehouse) each own their own
 * sponsorship detail; this one owns the visa routes side by side, the
 * Skilled Worker rules after the July 2025 and January 2026 changes, the
 * Immigration Salary List and Temporary Shortage List, and who can work
 * without a sponsor.
 *
 * Corrections to the draft:
 *
 * 1. It puts the Skilled Worker threshold at around £26,200. The general
 *    threshold is now £41,700 or the occupation's going rate, whichever is
 *    higher.
 *
 * 2. It tells readers to check the shortage occupation list. That list was
 *    replaced by the Immigration Salary List, and medium-skilled jobs now
 *    qualify only through that list or the Temporary Shortage List, both of
 *    which expire on 31 December 2026.
 *
 * 3. It presents the Health and Care Worker visa as open to care workers.
 *    Care worker and senior care worker roles closed to new overseas
 *    applicants on 22 July 2025.
 *
 * 4. It says the Graduate visa lasts two to three years. It lasts two years
 *    for applications made by 31 December 2026 and 18 months from 1 January
 *    2027; only doctorate graduates get three years.
 *
 * 5. It lists an Intra-Company Transfer visa. That route was replaced by the
 *    Global Business Mobility Senior or Specialist Worker visa.
 *
 * 6. It says English is sometimes tested. New Skilled Worker applicants must
 *    prove English at level B2; B1 applies only to people extending a visa
 *    held before 8 January 2026.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class JobsInUkForForeignersBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-visa-sponsorship-jobs.html';

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
        $title = 'Jobs in UK for Foreigners';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The Skilled Worker threshold is now £41,700, not £26,200, new applicants need B2 English, the care worker route closed to overseas applicants in July 2025, and the Graduate visa drops to 18 months from 1 January 2027.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-in-uk-for-foreigners.jpg',
                'tags' => 'jobs in uk for foreigners, uk visa sponsorship jobs, skilled worker visa salary threshold, skilled worker visa 41700, immigration salary list, temporary shortage list, graduate visa 18 months, youth mobility scheme countries, uk licensed sponsors register, b2 english skilled worker',
                'meta_title' => 'Jobs in UK for Foreigners 2026: Visa Rules and Salaries',
                'meta_description' => 'Jobs in the UK for foreigners: the GBP 41,700 Skilled Worker threshold, B2 English, the closed care worker route, the 18-month Graduate visa and sponsors.',
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
            ['name' => 'UK Licensed Sponsors — NHS Trusts, Tech, Engineering & Schools (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-foreign-workers-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Skilled Worker Visa Jobs — Healthcare, IT, Engineering and Teaching Roles with UK Licensed Sponsors',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; hours and shift patterns depend on the sponsor and occupation',
                'language' => 'English',
                // Sponsored pay depends on the occupation's going rate and the
                // route, so no single range is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'UK roles with licensed visa sponsors in healthcare, IT, engineering and teaching for foreign nationals.',
                'seo_keywords' => 'uk visa sponsorship jobs, skilled worker visa jobs, health and care worker visa jobs, uk jobs for foreigners, licensed sponsor jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS trusts, technology companies, engineering firms and schools on the UK register of licensed sponsors hire foreign nationals for roles that meet Skilled Worker or Health and Care Worker visa rules.</p>

<h3>What the roles involve</h3>
<p>Clinical and allied health posts, software and IT roles, engineering and teaching positions that meet the skill level and salary rules for sponsorship.</p>

<h3>Requirements</h3>
<ul>
    <li>A job offer and certificate of sponsorship from a licensed UK sponsor</li>
    <li>Salary of at least £41,700 or the occupation's going rate, whichever is higher, unless a lower threshold applies</li>
    <li>English at level B2 for new Skilled Worker applicants</li>
    <li>Professional registration where the occupation requires it, such as the NMC for nurses</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Going rates.</strong> Each occupation code has its own going rate, and the higher of that or the general threshold applies</li>
    <li><strong>Visa costs.</strong> Skilled Worker applications cost £819 for up to three years plus the healthcare surcharge, which Health and Care Worker applicants do not pay</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check that the employer is on the register of licensed sponsors,</strong> and never pay a recruiter or employer for a certificate of sponsorship.</p>

<p><strong>Note:</strong> visa rules, salary thresholds and fees are set by the Home Office &mdash; not by JobGader. Confirm the current rules on gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The UK still hires foreign workers in health care, technology, engineering and teaching, but the rules changed sharply in 2025 and 2026. Salary thresholds rose, the skill level for sponsored jobs went up, care worker sponsorship closed to overseas applicants, English requirements tightened and the Graduate visa is getting shorter. Many guides still quote the old figures. This one sets out the current visa routes, what they require, which jobs can still be sponsored, and how to check an employer before you apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-visa-sponsorship-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127468;&#127463; Browse UK Visa Sponsorship Jobs &rarr;
    </a>
</div>

<h2>What Changed: The Rules Guides Get Wrong</h2>

<ul>
    <li><strong>The salary threshold is £41,700.</strong> Guides quote about £26,200. The Skilled Worker general threshold is now <strong>£41,700 a year</strong>, or the going rate for the occupation if that is higher.</li>
    <li><strong>There is no shortage occupation list.</strong> It was replaced by the <strong>Immigration Salary List</strong>, and a <strong>Temporary Shortage List</strong> now keeps some medium-skilled jobs open. Both lists <strong>expire on 31 December 2026</strong>.</li>
    <li><strong>Care worker sponsorship closed.</strong> Care worker and senior care worker roles closed to new overseas applicants on <strong>22 July 2025</strong>.</li>
    <li><strong>English is B2.</strong> New Skilled Worker applicants must prove English at <strong>level B2</strong>. Level B1 applies only to people extending a visa they held before 8 January 2026.</li>
    <li><strong>The Graduate visa is shrinking.</strong> It lasts two years if you apply by 31 December 2026 and <strong>18 months if you apply on or after 1 January 2027</strong>.</li>
</ul>

<h2>The Main Visa Routes Compared</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Who it is for</th>
            <th style="padding:10px;text-align:left;">Key rules</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skilled Worker</strong></td><td style="padding:10px;">Job offer from a licensed sponsor</td><td style="padding:10px;">£41,700 or going rate; B2 English; £819 fee up to 3 years, £1,618 over 3 years; healthcare surcharge usually £1,035 a year</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Health and Care Worker</strong></td><td style="padding:10px;">Eligible doctors, nurses and health professionals</td><td style="padding:10px;">No healthcare surcharge; care worker roles closed to new overseas applicants</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate</strong></td><td style="padding:10px;">People who completed an eligible UK degree</td><td style="padding:10px;">No sponsor; 2 years (18 months from 1 January 2027), 3 years for doctorates; £937 fee</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Youth Mobility Scheme</strong></td><td style="padding:10px;">Young people from listed countries</td><td style="padding:10px;">No job offer; up to 24 months; £2,530 savings; £340 fee</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Global Talent</strong></td><td style="padding:10px;">Leaders or potential leaders in research, arts and digital technology</td><td style="padding:10px;">Endorsement or eligible award instead of a job offer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Senior or Specialist Worker</strong></td><td style="padding:10px;">Staff moved to a UK branch of their employer</td><td style="padding:10px;">The Global Business Mobility route that replaced the Intra-Company Transfer visa</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-uk-for-foreigners-westminster.jpg"
         alt="A smiling man in a suit holding a laptop on Westminster Bridge, with the Houses of Parliament, Big Ben, a red double-decker bus and a Union Jack behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Skilled Worker Visa: What a Job Must Meet</h2>

<ul>
    <li><strong>A licensed sponsor.</strong> The employer must hold a sponsor licence and give you a certificate of sponsorship.</li>
    <li><strong>A higher-skilled occupation.</strong> The job's occupation code must be on the list of eligible "higher skilled" jobs, broadly degree level.</li>
    <li><strong>Medium-skilled jobs only through the lists.</strong> A "medium skilled" job qualifies only if it is on the Immigration Salary List or the Temporary Shortage List, until both expire on 31 December 2026.</li>
    <li><strong>Salary.</strong> At least £41,700 or the going rate for the occupation, whichever is higher.</li>
    <li><strong>English at B2.</strong> Citizens of majority English-speaking countries such as the USA, Canada, Australia and New Zealand are exempt, and a degree taught in English can also count.</li>
    <li><strong>Savings.</strong> At least £1,270 in your bank account to show you can support yourself, unless your sponsor certifies your maintenance.</li>
</ul>

<p>People who got their first certificate of sponsorship before 22 July 2025 can still extend under the older skill rules. New applicants from abroad cannot.</p>

<h2>Youth Mobility Scheme: Work Without a Sponsor</h2>

<p>Guides describe a "select list" of countries. The scheme lets you work in almost any job for up to 24 months without a job offer:</p>

<ul>
    <li><strong>Ages 18 to 35:</strong> Australia, Canada, New Zealand and South Korea.</li>
    <li><strong>Ages 18 to 30:</strong> Andorra, Iceland, Japan, Monaco, San Marino and Uruguay, plus some British nationals.</li>
    <li><strong>Ballot required:</strong> Hong Kong and Taiwan.</li>
    <li><strong>India</strong> has a separate India Young Professionals Scheme, also run by ballot.</li>
</ul>

<p>Pakistan, Nigeria, the Philippines and most other countries are not in the scheme, so sponsorship or study remains the route for their citizens.</p>

<h2>Which Jobs Can Still Be Sponsored</h2>

<ul>
    <li><strong>Doctors, nurses and allied health professionals.</strong> The main health care routes remain open, with professional registration required.</li>
    <li><strong>Software, data and cybersecurity roles.</strong> Graduate-level technology jobs usually meet the skill level, subject to salary.</li>
    <li><strong>Engineers.</strong> Chartered and graduate engineering roles generally qualify at the salary threshold.</li>
    <li><strong>Teachers.</strong> Qualified teacher roles are graduate level; see our teaching guide for the pay scales.</li>
    <li><strong>Trades and some IT support roles.</strong> Most are medium skilled, so they depend on the Temporary Shortage List until it expires.</li>
    <li><strong>Care workers, cooks and most hospitality jobs.</strong> Care workers closed to overseas applicants in July 2025, and cooks cannot be sponsored, so guides that list hospitality as a sponsorship route are out of date.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-uk-for-foreigners-tower-bridge.jpg"
         alt="A young woman holding a tablet beside a red telephone box on the Thames riverside, with Tower Bridge, Big Ben and the City of London skyline behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Salaries for Sponsored Jobs</h2>

<p>Guides quote £28,000 to £45,000 for health care, £35,000 to £65,000 for IT and engineering, and £22,000 to £30,000 for entry-level work on Youth Mobility or Graduate visas. For sponsored jobs, the question is not the average but whether the offer meets the threshold:</p>

<ul>
    <li><strong>Skilled Worker jobs must pay at least £41,700 or the going rate</strong>, so a £35,000 IT offer generally cannot be sponsored on the general threshold.</li>
    <li><strong>Health and Care Worker jobs</strong> have their own salary rules tied to NHS pay bands for eligible roles.</li>
    <li><strong>Graduate and Youth Mobility visa holders</strong> have no salary threshold and can take any job paying the National Living Wage or more.</li>
</ul>

<h2>How to Check an Employer and Avoid Scams</h2>

<ol>
    <li><strong>Search the register of licensed sponsors</strong> on gov.uk for the exact company name and the route it can sponsor.</li>
    <li><strong>Check the occupation code.</strong> A licensed sponsor still cannot sponsor a job that is not eligible, such as a care worker role from overseas.</li>
    <li><strong>Never pay for a certificate of sponsorship.</strong> The sponsor pays the licence and Immigration Skills Charge costs; a recruiter asking you for thousands of pounds for a "sponsored job" is a warning sign.</li>
    <li><strong>Use official channels.</strong> NHS Jobs, the government's Find a Job service and employers' own career pages are safer than social media offers.</li>
</ol>

<h2>Tips for Landing a UK Job as a Foreigner</h2>

<ul>
    <li><strong>Start with the rules, not the job board.</strong> Confirm the occupation is eligible and the salary meets the threshold before applying.</li>
    <li><strong>Prepare your English evidence.</strong> Book a secure English test for B2 early if you are not exempt.</li>
    <li><strong>Register with your regulator.</strong> Nurses, doctors and engineers should start professional registration before applying.</li>
    <li><strong>Use a UK-style CV.</strong> Two pages, no photo, and a clear note of your visa needs.</li>
    <li><strong>Watch the 31 December 2026 date.</strong> Jobs that depend on the Temporary Shortage List or Immigration Salary List may lose that route.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum salary for a UK Skilled Worker visa?</h3>
<p>The general threshold is £41,700 a year, or the going rate for the occupation if that is higher. Some lower thresholds apply in specific cases.</p>

<h3>Is the shortage occupation list still used?</h3>
<p>No. It was replaced by the Immigration Salary List, and medium-skilled jobs now qualify only through that list or the Temporary Shortage List, both of which expire on 31 December 2026.</p>

<h3>Can care workers still get a UK visa from overseas?</h3>
<p>No. Care worker and senior care worker roles closed to new overseas applicants on 22 July 2025.</p>

<h3>What English level do I need for a Skilled Worker visa?</h3>
<p>Level B2 for new applicants. Level B1 applies only to people extending a visa they held before 8 January 2026.</p>

<h3>How long is the UK Graduate visa?</h3>
<p>Two years if you apply by 31 December 2026, 18 months if you apply on or after 1 January 2027, and three years for doctorate graduates.</p>

<h3>Which countries can use the Youth Mobility Scheme?</h3>
<p>Australia, Canada, New Zealand and South Korea (ages 18 to 35), Andorra, Iceland, Japan, Monaco, San Marino and Uruguay (18 to 30), and Hong Kong and Taiwan by ballot.</p>

<h3>How much does a Skilled Worker visa cost?</h3>
<p>£819 for up to three years or £1,618 for longer when applying from outside the UK, plus the healthcare surcharge, usually £1,035 a year.</p>

<h3>How do I know if a UK employer can sponsor me?</h3>
<p>Search the register of licensed sponsors on gov.uk, then confirm the job's occupation code is eligible for the route.</p>

<h2>People Also Search For</h2>

<h3>UK visa sponsorship jobs</h3>
<p>Roles with licensed sponsors that meet the skill and salary rules.</p>

<h3>Skilled Worker visa salary threshold 2026</h3>
<p>£41,700 or the going rate, whichever is higher.</p>

<h3>Immigration Salary List</h3>
<p>The replacement for the shortage occupation list, expiring 31 December 2026.</p>

<h3>Temporary Shortage List</h3>
<p>Keeps some medium-skilled jobs sponsorable until 31 December 2026.</p>

<h3>Care worker visa UK closed</h3>
<p>Closed to new overseas applicants since 22 July 2025.</p>

<h3>Graduate visa 18 months</h3>
<p>Applies to applications made on or after 1 January 2027.</p>

<h3>Youth Mobility Scheme countries</h3>
<p>Ten countries plus Hong Kong and Taiwan by ballot.</p>

<h3>Register of licensed sponsors</h3>
<p>The gov.uk list of employers allowed to sponsor workers.</p>

<h2>More Job Guides</h2>

<p>Looking at a specific UK job? These cover the sponsorship detail:</p>

<ul>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; which health care support roles can still be sponsored.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; what the care worker closure means in practice.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; an IT role on the Temporary Shortage List.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; qualified teacher status and pay scales.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; why cooks cannot be sponsored and chefs rarely can.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the truth about sponsored warehouse work.</li>
    <li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the NHS and social care support roles, and why the care-worker visa route closed.</li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a> &mdash; a first UK admin job, the real pay and the visa reality.</li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; every route in compared: employer sponsorship, skilled visas without a job offer, working holidays, the PALM scheme and student work rights.</li>
    <li><a href="/blog/school-administrator-jobs-in-uk">School Administrator Jobs in UK</a> &mdash; school office duties, FTE vs actual pay on term-time contracts, routes in and where schools advertise.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; the ONS salary picture, apprenticeship and Civil Service routes, and the SOC 2431 visa rules.</li>
    <li><a href="/blog/how-to-get-a-warehouse-driver-job-in-uk">How to Get a Warehouse Driver Job in UK</a> &mdash; which licence each role needs, the D4 medical and CPC route, funded training and ONS pay.</li>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; the nine plants, the apprenticeship route and the honest visa answer.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; the same question in Italy, where a quota decree decides it.</li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a> &mdash; a supermarket that publishes its rate and pays it at every age.</li>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; a worked example of an employer that sponsors visas but not for these occupations.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; the union pay scale, and why no delivery role can be sponsored.</li>
    <li><a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">How to Apply for BP Engineering Jobs in the UK</a> &mdash; which engineering codes clear the UK salary threshold, and which bp adverts are really agencies.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; the hourly rate M and S announces itself, and why no store role can be sponsored.</li>
    <li><a href="/blog/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk">How to Apply for Network Rail Maintenance Jobs in the UK</a> &mdash; the medical and drug screening standard, and why the trackside code cannot be sponsored.</li>
    <li><a href="/blog/how-to-apply-for-lloyds-graduate-jobs-in-the-uk">How to Apply for Lloyds Graduate Jobs in the UK</a> &mdash; ten schemes with published salaries, and the sponsorship answer that rules most readers out.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration or legal advice. UK visa rules, salary thresholds, fees and eligible occupation lists change often. Confirm the current rules on gov.uk or with a regulated immigration adviser before applying or paying anyone.</p>
HTML;
    }
}
