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
 * "Jobs in Canada for Foreign Workers" — the entry hub for someone abroad
 * deciding whether Canada is still worth it in 2026: what the levels plan
 * changed, which sectors actually hire, what Job Bank says they pay, the new
 * gatekeeping rules on low-wage jobs and how to spot a scam. The visa
 * sponsorship guide owns the LMIA mechanics, language rules and PR routes in
 * depth, and the farm, welder, bus driver and heavy equipment guides own each
 * occupation, so this one links out rather than repeating them.
 *
 * Corrections and clarifications to the draft (checked against IRCC and ESDC
 * on canada.ca, September 2026):
 *
 * 1. The draft says Canada cut "TFWP admissions to roughly 60,000 for 2026".
 *    That 60,000 is the Temporary Foreign Worker Program target only, down
 *    from 82,000 in the 2025-2027 plan. It is not Canada's whole foreign-worker
 *    intake: the 2026-2028 plan also sets 170,000 through the LMIA-exempt
 *    International Mobility Program, for 230,000 new worker arrivals in 2026.
 *    Presenting 60,000 as the total understates the real opening.
 *
 * 2. The draft lists sector salary "ranges" with no source. This guide anchors
 *    them to Job Bank national median wages, so a reader can check them:
 *    harvesting labourers $18.00 an hour, cooks $18.00, truck drivers $26.42,
 *    welders $30.00, electricians $35.00 and registered nurses $43.27.
 *
 * 3. The draft never mentions the rules that decide whether a low-wage LMIA is
 *    even accepted: no processing where a city's unemployment is 6% or more, a
 *    10% workforce cap (20% in a few sectors), a one-year maximum and a
 *    six-month LMIA validity. A foreign worker needs these before applying.
 *
 * 4. The draft implies "high-wage LMIA roles and the Global Talent Stream"
 *    offer "the strongest connection to permanent residency". The Global
 *    Talent Stream is a fast LMIA stream (about 10 business days), not an
 *    exemption, and since 25 March 2025 a job offer adds no Express Entry
 *    points, so a work permit no longer shortens the PR queue on its own.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class JobsInCanadaForeignWorkersBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/Foreign-Worker-Canada-jobs';

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
        $title = 'Jobs in Canada for Foreign Workers';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The 60,000 TFWP target is not Canada\'s whole 2026 intake, the International Mobility Program adds 170,000, and the real openings are in farm work, healthcare, trades and trucking, priced by Job Bank, not the vague bands most guides quote.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-in-canada-for-foreign-workers.jpg',
                'tags' => 'jobs in canada for foreign workers, tfwp 2026, lmia jobs canada, canada work permit 2026, in demand jobs canada, canada immigration levels plan, international mobility program, farm jobs canada lmia, high wage lmia, canada job scams',
                'meta_title' => 'Jobs in Canada for Foreign Workers 2026: Pay & Rules',
                'meta_description' => 'Jobs in Canada for foreign workers in 2026: what the levels plan changed, which sectors hire through the LMIA, Job Bank pay, the new low-wage rules and scams.',
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
            ['name' => 'Canadian Employers Hiring Foreign Workers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-foreign-worker-aggregated']
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
                'position' => 'Foreign Worker Jobs — LMIA-Supported Agriculture, Healthcare, Trades and Transport Roles, Canadian Employers',
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
                // Roles run from harvest work to registered nursing, so no
                // single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'LMIA-supported farm, healthcare, trades and transport jobs with Canadian employers. The employer applies for and pays the LMIA fee, never the worker.',
                'seo_keywords' => 'jobs in canada for foreign workers, lmia jobs canada, farm jobs canada lmia, truck driver lmia jobs, caregiver jobs canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian employers who cannot find a Canadian or permanent resident for a role can apply for a Labour Market Impact Assessment (LMIA) and hire a foreign worker on an employer-specific work permit. In 2025 most approved positions were in agriculture, food service and processing, transport and caregiving.</p>

<h3>How it works</h3>
<p>The employer applies to Employment and Social Development Canada (ESDC) for the LMIA and pays $1,000 per position. With a positive LMIA and a job offer, you apply to IRCC for a work permit tied to that employer.</p>

<h3>Requirements</h3>
<ul>
    <li>A genuine job offer from an employer with a positive LMIA, or an LMIA-exempt route such as an intra-company transfer</li>
    <li>The qualifications and any licence the job needs &mdash; nurses must register with a provincial regulator before they practise</li>
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
<p>Canada is still one of the top destinations for foreign workers, but 2026 has changed how the system works. The government has cut one part of the temporary worker program while fast-tracking permanent residence for thousands of workers already here. Before you spend money on applications, it helps to know what actually changed, which sectors really hire foreign workers, what Job Bank says they pay, and how to tell a genuine offer from a scam.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/Foreign-Worker-Canada-jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127464;&#127462; Browse Foreign Worker Jobs in Canada &rarr;
    </a>
</div>

<h2>What Changed for Foreign Workers in 2026</h2>

<p>The 2026-2028 Immigration Levels Plan reshaped the numbers. The most quoted figure is a cut, but it is easy to misread:</p>

<ul>
    <li><strong>The Temporary Foreign Worker Program (TFWP) target is 60,000 for 2026</strong>, down from 82,000 in the previous plan and falling to 50,000 in 2027 and 2028. Guides that stop here make the door look almost shut.</li>
    <li><strong>The International Mobility Program (IMP) is far larger, at 170,000.</strong> It covers LMIA-exempt work permits, so the two programs together plan for <strong>230,000 new worker arrivals in 2026</strong> &mdash; part of about 385,000 new temporary residents overall.</li>
    <li><strong>Up to 33,000 workers already in Canada will be moved to permanent residence across 2026 and 2027</strong> under the In-Canada Workers Initiative, weighted toward in-demand jobs in rural and smaller communities.</li>
</ul>

<p><strong>The takeaway:</strong> opportunities still exist, but they are concentrated in skilled, high-wage and shortage-critical roles rather than general low-wage placements. A high-wage job offer is now the stronger bet, both for approval and for staying long term.</p>

<h2>How the LMIA Works, in Brief</h2>

<p>Canada has no single "sponsorship visa". An employer supports your work permit in one of two ways, and our <a href="/blog/visa-sponsorship-jobs-in-canada">visa sponsorship guide</a> covers the mechanics, language rules and PR routes in full.</p>

<ul>
    <li><strong>With an LMIA.</strong> The employer applies to ESDC, shows no Canadian or permanent resident was available, and pays <strong>$1,000</strong> per position. A positive LMIA lets you apply to IRCC for an employer-specific work permit. <strong>You never pay for the LMIA.</strong></li>
    <li><strong>Without an LMIA.</strong> The International Mobility Program covers exemptions such as intra-company transfers and free-trade agreements like CUSMA.</li>
</ul>

<p>One point guides get wrong: the <strong>Global Talent Stream</strong> is not an exemption. It is a Temporary Foreign Worker Program stream that still needs an LMIA, processed in about <strong>10 business days</strong> for eligible tech and specialist roles.</p>

<h2>What the Main Sectors Pay: Job Bank Medians</h2>

<p>Most guides give salary bands with no source. Job Bank's national median wages are a firmer guide for the jobs that are actually approved. Annual figures assume 40 hours a week, all year:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Sector</th>
            <th style="padding:10px;text-align:left;">Typical role (Job Bank median)</th>
            <th style="padding:10px;text-align:left;">Approx. annual (CAD)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Agriculture</td><td style="padding:10px;">Harvesting labourer, $18.00/hr</td><td style="padding:10px;">$37,400</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hospitality &amp; food service</td><td style="padding:10px;">Cook, $18.00/hr</td><td style="padding:10px;">$37,400</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Trucking &amp; logistics</td><td style="padding:10px;">Long-haul truck driver, $26.42/hr</td><td style="padding:10px;">$55,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Skilled trades</td><td style="padding:10px;">Welder $30.00, electrician $35.00/hr</td><td style="padding:10px;">$62,400 &ndash; $72,800</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Healthcare</td><td style="padding:10px;">Support worker $24.00, RN $43.27/hr</td><td style="padding:10px;">$49,900 &ndash; $90,000</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-canada-for-foreign-workers-sectors.jpg"
         alt="A construction worker, nurse, chef and businesswoman standing in front of the Toronto skyline and a Canadian flag, under a Jobs in Canada for Foreign Workers banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Sectors Are Actually Hiring</h2>

<ul>
    <li><strong>Agriculture.</strong> The biggest source of approved positions by far. Harvesting, greenhouse and livestock labour fill more than a third of positive LMIAs, and primary agriculture is exempt from most of the 2026 low-wage restrictions below.</li>
    <li><strong>Healthcare.</strong> The strongest long-term case. Job Bank rates registered nurses and support workers well in several provinces, but you must register with the provincial regulator before you can work as a nurse.</li>
    <li><strong>Skilled trades.</strong> Welders, electricians and heavy-duty mechanics are hired nationwide for construction and industry. Demand is real but rated Moderate, not desperate, in most provinces.</li>
    <li><strong>Trucking &amp; logistics.</strong> Long-haul drivers see steady LMIA approvals, with a median well above the low-wage line in most provinces.</li>
    <li><strong>Hospitality &amp; food service.</strong> Cooks, kitchen staff and food-service supervisors remain common LMIA roles, especially outside the big high-unemployment cities.</li>
</ul>

<h2>The 2026 Rules That Decide a Low-Wage Job</h2>

<p>If a job pays below the provincial threshold (the median wage plus 20%), it is a low-wage position and four rules now decide whether the LMIA is even accepted. Agriculture, construction, food manufacturing, hospitals and nursing care are exempt from several of them:</p>

<ul>
    <li><strong>No processing in high-unemployment cities.</strong> ESDC will not process a low-wage LMIA where the census metropolitan area's unemployment is <strong>6% or more</strong>. That currently includes Toronto, Montr&eacute;al, Vancouver, Calgary and Edmonton.</li>
    <li><strong>Eight weeks of advertising.</strong> Since <strong>1 April 2026</strong>, low-wage roles must be advertised for at least eight consecutive weeks before the employer applies, double the old four weeks.</li>
    <li><strong>A 10% workforce cap.</strong> No more than 10% of a worksite's staff can be low-wage foreign workers (20% in construction, food manufacturing, hospitals and nursing care).</li>
    <li><strong>One year, and a six-month LMIA.</strong> A low-wage position can be filled for a maximum of one year, and a positive LMIA is valid for only six months, so you must apply for the work permit inside that window.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-canada-for-foreign-workers-workers.jpg"
         alt="A young man holding a passport with a nurse, construction worker, chef and mechanic behind him, the Toronto skyline, a plane and a Canadian flag in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply the Right Way</h2>

<ol>
    <li><strong>Target high-demand, high-wage sectors</strong> &mdash; healthcare, skilled trades and agriculture see the strongest approvals and the fewest restrictions.</li>
    <li><strong>Search verified employer postings</strong> rather than agents promising a guaranteed placement.</li>
    <li><strong>Confirm the employer will handle the LMIA</strong> &mdash; the $1,000 fee is theirs to pay, never yours.</li>
    <li><strong>Prepare your documents early</strong> &mdash; resume, proof of qualifications, a language test if a PR program needs it, and any licensing for a regulated job.</li>
    <li><strong>Apply to IRCC for the work permit</strong> once you have the offer and a positive LMIA, within the six-month validity window.</li>
    <li><strong>Ask about permanent residence</strong> &mdash; Express Entry, a Provincial Nominee Program or the Atlantic Immigration Program, and whether the role fits the In-Canada Workers Initiative.</li>
</ol>

<h2>How to Avoid Foreign Worker Scams</h2>

<p>IRCC says it plainly: <strong>"No one can guarantee you a job or a visa to Canada."</strong> The warning signs are being asked to pay up front for the LMIA, training or supplies, or being promised you can move and start work in a few weeks.</p>

<ul>
    <li><strong>The employer pays the LMIA.</strong> Under the Immigration and Refugee Protection Regulations, an employer must not charge or recover the LMIA fee or any recruitment fee from you, directly or through a recruiter.</li>
    <li><strong>Buying or selling an LMIA is prohibited.</strong> ESDC says employers face penalties of up to $1 million a year and a ban from the program.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Has it become harder to get a job in Canada as a foreign worker in 2026?</h3>
<p>For general low-wage roles, yes &mdash; reduced targets and new restrictions have tightened the process. For skilled, high-wage and shortage-occupation roles in healthcare, trades and agriculture, demand remains strong.</p>

<h3>Is 60,000 the total number of foreign workers Canada will admit in 2026?</h3>
<p>No. That is the Temporary Foreign Worker Program target only. The International Mobility Program adds 170,000, so the plan sets 230,000 new worker arrivals in 2026.</p>

<h3>Who pays for the LMIA, me or the employer?</h3>
<p>The employer applies for and pays the $1,000 LMIA fee. By law it cannot be charged to or recovered from the worker, and neither can recruitment fees.</p>

<h3>Which sectors have the best chance of an LMIA in 2026?</h3>
<p>Agriculture by volume, then healthcare, skilled trades and trucking. Primary agriculture and several care sectors are also exempt from the low-wage city and cap rules.</p>

<h3>Can a foreign-worker job lead to permanent residence?</h3>
<p>Yes, but less directly than before. Since 25 March 2025 a job offer adds no Express Entry points, so Canadian work experience, language scores and category-based draws now carry the weight.</p>

<h3>Do I need to work in a big city like Toronto?</h3>
<p>Often the opposite. Low-wage LMIAs are not processed where a city's unemployment is 6% or more, which includes Toronto, so many approved jobs are in smaller centres and rural areas.</p>

<h3>How long is an LMIA valid, and how long can I work?</h3>
<p>A positive LMIA is valid for six months, the window to apply for your work permit. A low-wage position can be filled for a maximum of one year; high-wage and agricultural roles can run longer.</p>

<h3>How do I avoid Canada job scams?</h3>
<p>Never pay for a job or an LMIA, distrust guaranteed placements, and verify the employer. IRCC states that no one can guarantee you a job or a visa.</p>

<h2>People Also Search For</h2>

<h3>LMIA jobs in Canada</h3>
<p>Employer-supported jobs where a positive Labour Market Impact Assessment backs your work permit, most of them in agriculture, food, transport and care.</p>

<h3>Canada work permit 2026</h3>
<p>An employer-specific permit follows a positive LMIA; the LMIA is valid six months and the permit is tied to that job.</p>

<h3>TFWP vs International Mobility Program</h3>
<p>The TFWP needs an LMIA and targets 60,000 in 2026; the IMP is LMIA-exempt and targets 170,000.</p>

<h3>In-demand jobs in Canada 2026</h3>
<p>Farm labour, nursing and personal support, skilled trades and long-haul trucking see the most approvals.</p>

<h3>Canada eight-week advertising rule</h3>
<p>From 1 April 2026, low-wage positions must be advertised for at least eight consecutive weeks before an LMIA is filed.</p>

<h3>High-wage LMIA jobs</h3>
<p>Roles paying at or above the provincial threshold, processed under a separate stream with fewer low-wage restrictions.</p>

<h3>Seasonal Agricultural Worker Program</h3>
<p>A stream recruiting from Mexico and Caribbean countries for up to eight months; other farm workers use the general agricultural stream.</p>

<h3>Canada TR to PR pathway 2026</h3>
<p>The In-Canada Workers Initiative will move up to 33,000 temporary workers to permanent residence across 2026 and 2027.</p>

<h2>More Job Guides</h2>

<p>Looking at a specific route into Canada? These cover it:</p>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA in full, the language rules and the routes to permanent residence.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the most-approved LMIA jobs, and which farm program you can use.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a skilled trade route, and why most welder LMIAs are low-wage.</li>
    <li><a href="/blog/heavy-equipment-operator-jobs-in-canada">Heavy Equipment Operator Jobs in Canada</a> &mdash; site and resource work, and the tickets each province wants.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; the licence class each province requires, and the rules for foreign drivers.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; an entry-level Canadian job priced by the provincial minimum wage.</li>
    <li><a href="/blog/occupational-therapist-jobs-in-canada">Occupational Therapist Jobs in Canada</a> &mdash; the allied-health route, its provincial registration and pay.</li>
    <li><a href="/blog/how-to-become-a-correctional-officer-in-canada">How to Become a Correctional Officer in Canada</a> &mdash; federal CSC versus provincial jails, who can apply, training and Job Bank pay.</li>
    <li><a href="/blog/preschool-teacher-jobs-in-canada">Preschool Teacher Jobs in Canada</a> &mdash; early childhood educator qualifications, provincial registration, wages and how internationally trained educators get recognised.</li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; every route in compared: employer sponsorship, skilled visas without a job offer, working holidays, the PALM scheme and student work rights.</li>
    <li><a href="/blog/public-safety-jobs-in-canada">Public Safety Jobs in Canada</a> &mdash; CBSA border officer pay and rules, RCMP and CSC requirements, emergency management jobs and who can apply.</li>
    <li><a href="/blog/finance-analyst-jobs-in-canada">Finance Analyst Jobs in Canada</a> &mdash; the $43.27 Job Bank median, degree and CFA rules, and where the big banks hire.</li>
    <li><a href="/blog/how-to-get-a-fleet-driver-job-in-canada">How to Get a Fleet Driver Job in Canada</a> &mdash; AZ and Class 1 licences, provincial training hours, air brakes and Job Bank wages.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. LMIA rules, wage thresholds, unemployment lists and immigration targets change often. Confirm the current position with ESDC and IRCC on canada.ca, or a licensed immigration consultant, before applying or paying any fee.</p>
HTML;
    }
}
