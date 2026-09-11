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
 * "Visa Sponsorship Jobs in Canada" — employer-supported work permits across
 * sectors. The welder, bus driver and farm worker guides cover single
 * occupations, so this one owns how the LMIA and its exemptions work, where
 * ESDC actually approves positions, the 2026 limits, the language rules and
 * the routes from a work permit to permanent residence.
 *
 * Corrections to the draft:
 *
 * 1. It says more than 395,000 permanent residents will arrive in 2026. That
 *    was the 2025 target; the 2026-2028 plan sets 380,000 a year.
 *
 * 2. It calls the Global Talent Stream LMIA-exempt. It is a Temporary Foreign
 *    Worker Program stream that needs an LMIA, processed in 10 business
 *    days, and a Provincial Nominee Program nomination is a permanent
 *    residence route, not a work permit.
 *
 * 3. It leads with tech and healthcare. ESDC's 2025 data put harvesting
 *    labourers at 61,584 positions on positive LMIAs, more than a third of
 *    the total, and it names Quebec last of the four top provinces when
 *    Quebec was second.
 *
 * 4. It equates CLB 5 with IELTS 5.5 to 6.0. CLB 5 is IELTS General 5.0 in
 *    listening, writing and speaking and 4.0 in reading; 6.0 across the
 *    board is CLB 7. An LMIA work permit sets no language test at all.
 *
 * 5. It says trades demand is strongest in Alberta, Manitoba and Ontario.
 *    Job Bank rates electricians, millwrights and heavy-duty mechanics
 *    Moderate there, and electricians Limited in Manitoba.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class VisaSponsorshipJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-visa-sponsorship-jobs.html';

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
        $title = 'Visa Sponsorship Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Canada plans 380,000 new permanent residents in 2026, not 395,000, the Global Talent Stream still needs an LMIA, and farm work, not tech, filled more than a third of positions on positive LMIAs in 2025.',
                'content' => $content,
                'featured_image' => 'blogs/visa-sponsorship-jobs-in-canada.jpg',
                'tags' => 'visa sponsorship jobs canada, lmia jobs canada, global talent stream, canada work permit 2026, lmia refusal to process, clb 5 ielts, express entry categories 2026, seasonal agricultural worker program, canada immigration levels plan',
                'meta_title' => 'Visa Sponsorship Jobs in Canada 2026: LMIA, Pay and Routes',
                'meta_description' => 'Visa sponsorship jobs in Canada: how the LMIA works, where positions are approved, Job Bank pay, the 2026 low-wage limits, language rules and the PR routes.',
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
            ['name' => 'Canadian Employers Hiring Through the LMIA (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-visa-sponsorship-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Visa Sponsorship Jobs — LMIA-Supported Farm, Food, Trades, Transport and Healthcare Roles, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Varies by sector; seasonal farm contracts run up to 8 months',
                'language' => 'English, French',
                // Sponsored roles run from harvest work to software development,
                // so no single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'LMIA-supported farm, food processing, trades, transport and healthcare jobs with Canadian employers. The employer pays the LMIA fee.',
                'seo_keywords' => 'visa sponsorship jobs canada, lmia jobs canada, farm jobs canada lmia, truck driver lmia jobs, cook lmia jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian employers who cannot find a Canadian or permanent resident for a role can apply for a Labour Market Impact Assessment (LMIA) and hire a foreign worker on an employer-specific work permit. Most positions approved in 2025 were in agriculture, food service and processing, and transport.</p>

<h3>How it works</h3>
<p>The employer applies to Employment and Social Development Canada for the LMIA and pays $1,000 per position. With a positive LMIA and a job offer, you apply to IRCC for a work permit tied to that employer.</p>

<h3>Requirements</h3>
<ul>
    <li>A genuine job offer from an employer with a positive LMIA, or an LMIA-exempt route such as an intra-company transfer</li>
    <li>The qualifications and any licence the job needs &mdash; nurses must register with a provincial regulator</li>
    <li>No set language test for an LMIA work permit, though permanent residence programs require one</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Job Bank medians.</strong> $18.00 an hour for harvesting labourers and cooks, $26.42 for truck drivers, $35.00 for electricians and $43.27 for registered nurses</li>
    <li><strong>Low-wage limits.</strong> Jobs below the provincial threshold face a 10% workforce cap, a one-year maximum and no processing in cities with 6% unemployment or more</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for an LMIA or a job offer.</strong> The employer cannot charge or recover the LMIA fee or any recruitment fee from you, and IRCC says no one can guarantee you a job or a visa.</p>

<p><strong>Note:</strong> LMIA decisions, wages and work permit eligibility are set by employers, ESDC and IRCC &mdash; not by JobGader. Confirm the details on canada.ca before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canadian employers do hire foreign workers, and a job offer backed by a Labour Market Impact Assessment is still the most common way in. But the rules tightened in 2025 and 2026, and most guides describe a market that no longer exists. Before you apply, it helps to know how sponsorship actually works, where the approved jobs really are, what the 2026 limits mean for low-wage roles, and what your language score has to be.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-visa-sponsorship-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127464;&#127462; Browse Visa Sponsorship Jobs in Canada &rarr;
    </a>
</div>

<h2>What Sponsorship Means in Canada</h2>

<p>Canada has no single "sponsorship visa". An employer supports your work permit in one of two ways:</p>

<ul>
    <li><strong>With an LMIA.</strong> The employer applies to Employment and Social Development Canada (ESDC), shows it could not find a Canadian or permanent resident, and pays <strong>$1,000</strong> for each position. With a positive LMIA you apply to IRCC for an <strong>employer-specific work permit</strong>, which lets you work only under its conditions.</li>
    <li><strong>Without an LMIA.</strong> The International Mobility Program covers exemptions such as <strong>intra-company transfers</strong> of executives, senior managers and specialized knowledge workers.</li>
</ul>

<p>Two things guides get wrong. The <strong>Global Talent Stream</strong> is not an exemption: it is a Temporary Foreign Worker Program stream that <strong>still needs an LMIA</strong>, processed in <strong>10 business days</strong> 80% of the time, and the employer must file a Labour Market Benefits Plan. And a <strong>Provincial Nominee Program</strong> nomination is a route to permanent residence, not a work permit.</p>

<h2>Canada Is Admitting Fewer Newcomers</h2>

<p>Guides say more than 395,000 permanent residents will arrive in 2026. That was the 2025 target. The <strong>2026-2028 Immigration Levels Plan</strong> sets:</p>

<ul>
    <li><strong>Permanent residents:</strong> <strong>380,000</strong> a year from 2026 to 2028</li>
    <li><strong>New temporary workers in 2026:</strong> 230,000 &mdash; 170,000 through the International Mobility Program and <strong>60,000</strong> through the Temporary Foreign Worker Program, falling to 50,000 in 2027</li>
</ul>

<h2>Where Sponsored Jobs Really Are: ESDC's Data</h2>

<p>Guides lead with tech and healthcare. ESDC's count of positions on positive LMIAs tells a different story. In 2025 the provinces approved:</p>

<ul>
    <li><strong>Ontario:</strong> <strong>54,817</strong> positions, down from 66,412 in 2024</li>
    <li><strong>Quebec:</strong> 51,715</li>
    <li><strong>British Columbia:</strong> 35,063</li>
    <li><strong>Alberta:</strong> 13,915, less than half its 32,998 in 2024</li>
</ul>

<p>Across Canada that is about 173,000 positions, down from about 238,000. The occupations with the most were:</p>

<ul>
    <li><strong>Harvesting labourers:</strong> <strong>61,584</strong></li>
    <li><strong>Nursery and greenhouse labourers:</strong> 10,722</li>
    <li><strong>Livestock labourers:</strong> 7,571</li>
    <li><strong>Food service supervisors:</strong> 6,052</li>
    <li><strong>Cooks:</strong> 5,730</li>
    <li><strong>Fish and seafood plant workers:</strong> 4,770</li>
    <li><strong>Transport truck drivers:</strong> 4,525</li>
</ul>

<p>So farm work alone filled more than a third of approved positions. The Seasonal Agricultural Worker Program recruits only from <strong>Mexico and 11 Caribbean countries</strong>, for up to 8 months between 1 January and 15 December; our <a href="/blog/farm-worker-jobs-in-canada">farm worker guide</a> covers the route open to everyone else.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/visa-sponsorship-jobs-in-canada-workers.jpg"
         alt="A construction worker, office worker, chef, nurse and delivery worker smiling in front of the Toronto skyline, with a passport and visa papers"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The 2026 Limits on Low-Wage Sponsorship</h2>

<p>Whether a job is high-wage or low-wage depends on the provincial threshold, which is the provincial median wage plus 20%. For LMIAs received from 17 July 2026 it is $36.92 an hour in Ontario, $38.40 in BC, $37.50 in Alberta, $36.00 in Quebec, $34.62 in Saskatchewan, $31.96 in Nova Scotia and $31.33 in Manitoba. Below it, three limits apply:</p>

<ul>
    <li><strong>A 10% cap</strong> on the share of low-wage foreign workers at a worksite, or 20% in construction, food manufacturing, hospitals and nursing care.</li>
    <li><strong>One year at most.</strong> Low-wage positions can be filled for a maximum of 1 year.</li>
    <li><strong>No processing in high-unemployment cities.</strong> Low-wage LMIAs are not processed where the census metropolitan area's unemployment is 6% or more. For applications from 10 July to 8 October 2026 that includes <strong>Toronto (7.3%)</strong>, Montr&eacute;al (6.8%), Vancouver (6.7%), Calgary (7.0%) and Edmonton (7.2%). Agriculture, construction, food manufacturing, hospitals, nursing care, some caregiver jobs and positions of 120 days or less are exempt.</li>
</ul>

<h2>What Sponsored Jobs Pay: Job Bank Medians</h2>

<p>Guides give salary bands by sector. Job Bank's national median hourly wages, covering 2023-2024, are a better guide for the jobs that are actually approved. The annual figures assume 40 hours a week all year:</p>

<ul>
    <li><strong>Food counter attendants:</strong> $16.55 an hour, about $34,400</li>
    <li><strong>Harvesting labourers:</strong> $18.00, about $37,400</li>
    <li><strong>Cooks:</strong> $18.00, about $37,400</li>
    <li><strong>Housekeeping room attendants:</strong> $19.74, about $41,100</li>
    <li><strong>General farm workers:</strong> $20.00, about $41,600</li>
    <li><strong>Labourers in food processing:</strong> $20.00, about $41,600</li>
    <li><strong>Personal support workers and nurse aides:</strong> $24.00, about $49,900</li>
    <li><strong>Transport truck drivers:</strong> $26.42, about $55,000</li>
    <li><strong>Welders:</strong> $30.00, about $62,400</li>
    <li><strong>Electricians:</strong> $35.00, about $72,800</li>
    <li><strong>Millwrights:</strong> $37.00, about $77,000</li>
    <li><strong>Heavy-duty equipment mechanics:</strong> $37.12, about $77,200</li>
    <li><strong>Registered nurses:</strong> <strong>$43.27</strong>, about $90,000</li>
    <li><strong>Software developers:</strong> $48.08, about $100,000</li>
</ul>

<p>Every median on this list, even for nurses and software developers, is below the high-wage threshold in Ontario and BC.</p>

<h2>Sector by Sector: What Job Bank's Outlook Says</h2>

<ul>
    <li><strong>Healthcare.</strong> The strongest case. Job Bank rates registered nurses <strong>Very good</strong> in Ontario and Good in Alberta and Manitoba for 2025-2027. But you must register before you practise: only nurses registered with the College of Nurses of Ontario can work as nurses there. For assessments obtained after 1 April 2025 the college also accepts WES, ICAS or ICES reports instead of NNAS. In Ontario, registration for personal support workers is voluntary.</li>
    <li><strong>Skilled trades.</strong> Guides call demand strongest in Alberta, Manitoba and Ontario. Job Bank rates electricians, millwrights and heavy-duty equipment mechanics <strong>Moderate</strong> in Ontario and Alberta, and electricians <strong>Limited</strong> in Manitoba.</li>
    <li><strong>Tech.</strong> Software developers are rated <strong>Limited</strong> in Ontario, where Job Bank expects some positions to be lost, and Moderate in Alberta and Manitoba. For Global Talent Stream roles such as software engineers and developers, ESDC sets the minimum wage requirement at the prevailing wage.</li>
    <li><strong>Transport.</strong> Truck drivers are rated Moderate in Ontario and Alberta and Limited in Manitoba.</li>
</ul>

<h2>Language: What CLB 5 Really Means</h2>

<p>Guides say sponsored jobs need CLB 5, "roughly IELTS 5.5 to 6.0". IRCC's own table says otherwise. In IELTS General Training:</p>

<ul>
    <li><strong>CLB 5:</strong> 5.0 in listening, <strong>4.0</strong> in reading, 5.0 in writing and 5.0 in speaking</li>
    <li><strong>CLB 7:</strong> 6.0 in all four</li>
</ul>

<p>And an LMIA work permit sets no language test: an officer refuses only if there are reasonable grounds to believe you cannot do the job. Language scores matter for permanent residence: <strong>CLB 7</strong> for the Federal Skilled Worker Program, CLB 5 in speaking and listening and CLB 4 in reading and writing for the Federal Skilled Trades Program, and CLB 7 or CLB 5 for the Canadian Experience Class, depending on the job's TEER level.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/visa-sponsorship-jobs-in-canada-team.jpg"
         alt="Five workers in construction, kitchen, office, healthcare and delivery uniforms standing by the Toronto waterfront with a passport on a map"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>From Work Permit to Permanent Residence</h2>

<ul>
    <li><strong>Express Entry.</strong> In 2026 IRCC runs 10 categories, including healthcare and social services, STEM, trade and education. A job offer no longer adds points since 25 March 2025, so Canadian experience and language scores do the work.</li>
    <li><strong>Atlantic Immigration Program.</strong> Accepts job offers of at least a year in TEER 0 to 3 occupations, and permanent offers in TEER 4.</li>
    <li><strong>Provincial nominee programs.</strong> A nomination leads to permanent residence, and a nominee who has applied can get an LMIA-exempt work permit.</li>
    <li><strong>Your spouse.</strong> Since <strong>21 January 2025</strong>, only spouses of workers in TEER 0 or 1 jobs and some TEER 2 and 3 jobs can get an open work permit, and the worker's permit must have at least 16 months left. Dependent children are no longer eligible.</li>
</ul>

<h2>How to Avoid Sponsorship Fraud</h2>

<p>IRCC says it plainly: <strong>"No one can guarantee you a job or a visa to Canada."</strong> The warning signs are being asked to pay up front for the LMIA, training or supplies, or being promised you can move and start work in a few weeks.</p>

<ul>
    <li><strong>The employer pays.</strong> Under the Immigration and Refugee Protection Regulations, an employer must not charge or recover the LMIA fee or any recruitment fee from you, directly or through a recruiter.</li>
    <li><strong>Buying or selling an LMIA is prohibited.</strong> ESDC says employers face penalties of up to $1 million a year and a ban from the program.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Is visa sponsorship the same as permanent residency?</h3>
<p>No. An LMIA leads to a temporary, employer-specific work permit. Canadian experience can later count toward Express Entry, a provincial nomination or the Atlantic Immigration Program.</p>

<h3>Does the Global Talent Stream need an LMIA?</h3>
<p>Yes. It is part of the Temporary Foreign Worker Program, with LMIAs processed in 10 business days 80% of the time and a $1,000 fee per position.</p>

<h3>What IELTS score equals CLB 5?</h3>
<p>In IELTS General Training, 5.0 in listening, writing and speaking and 4.0 in reading. CLB 7 is 6.0 in all four.</p>

<h3>Do I need a language test for a Canadian work permit?</h3>
<p>Not for an LMIA work permit, unless the employer asks for one. Permanent residence programs set minimum CLB scores.</p>

<h3>How much does an LMIA cost, and who pays?</h3>
<p>$1,000 per position, paid by the employer. It cannot be charged to or recovered from the worker, and neither can recruitment fees.</p>

<h3>Can employers in Toronto sponsor low-wage workers?</h3>
<p>Not for most jobs while Toronto's unemployment rate is 6% or more. It was 7.3% for applications from 10 July to 8 October 2026. Agriculture, construction, food manufacturing, hospitals and nursing care are exempt.</p>

<h3>Which jobs get the most LMIA approvals?</h3>
<p>Farm jobs. Harvesting labourers had 61,584 positions on positive LMIAs in 2025, followed by nursery and greenhouse labourers, livestock labourers, food service supervisors and cooks.</p>

<h3>How many immigrants will Canada accept in 2026?</h3>
<p>The levels plan targets 380,000 new permanent residents a year from 2026 to 2028, and 60,000 new arrivals through the Temporary Foreign Worker Program in 2026.</p>

<h2>People Also Search For</h2>

<h3>LMIA jobs in Canada</h3>
<p>About 173,000 positions on positive LMIAs in 2025, most of them in Ontario, Quebec and BC.</p>

<h3>Global Talent Stream Canada</h3>
<p>An LMIA stream for tech and specialist roles, processed in 10 business days.</p>

<h3>CLB 5 IELTS equivalent</h3>
<p>Listening 5.0, reading 4.0, writing 5.0 and speaking 5.0 in IELTS General Training.</p>

<h3>Seasonal Agricultural Worker Program countries</h3>
<p>Mexico and 11 Caribbean countries, for up to 8 months a year.</p>

<h3>Express Entry categories 2026</h3>
<p>Ten categories, including healthcare, STEM, trade, education and French.</p>

<h3>LMIA refusal to process 6%</h3>
<p>Low-wage LMIAs are not processed in cities with 6% unemployment or more, including Toronto, Montr&eacute;al and Vancouver.</p>

<h3>Nurse jobs in Canada for foreigners</h3>
<p>Rated Very good in Ontario, but you must register with the provincial regulator first.</p>

<h3>Canada spouse open work permit 2026</h3>
<p>Limited since 21 January 2025 to spouses of workers in TEER 0 or 1 and some TEER 2 and 3 jobs.</p>

<h2>More Job Guides</h2>

<p>Looking at a specific sponsored job in Canada? These cover it:</p>

<ul>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the most-approved LMIA jobs, and which farm program you can use.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a skilled trade route, and why most welder LMIAs are low-wage.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; the licence class each province requires, and the rules for foreign drivers.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; an entry-level Canadian job priced by the provincial minimum wage.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-canada">ATS Resume Writer Jobs in Canada</a> &mdash; how Canadian applicant tracking systems read what you send.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; another sponsored nursing route, and why the exam is not the licence.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. LMIA rules, wage thresholds, unemployment lists and immigration targets change often. Confirm the current position with ESDC and IRCC on canada.ca, or a licensed immigration consultant, before applying or paying any fee.</p>
HTML;
    }
}
