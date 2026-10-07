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

/**
 * "Cleaner and Janitor Jobs With Visa Sponsorship" — blog-290.
 *
 * The draft was rewritten. What it carried could not be published as sent:
 *
 * 1. Indeed "Apply Now" buttons for the UK, Canada ("janitor LMIA") and Saudi
 *    Arabia. The guide links only to our own pages; the job listings carry the
 *    official portals.
 *
 * 2. Quotes from a Liverpool City Council and an NHS trust advert, a
 *    GulfTalent count of 61 jobs, a 19.00 per hour British Columbia wage and
 *    1,200–2,000 SAR BEOE salaries. None could be confirmed and the site does
 *    not republish job-board pay, so all were dropped. The UK point rests on
 *    GOV.UK's skill threshold instead.
 *
 * 3. The draft left out the Canadian low-wage LMIA freeze, which decides
 *    whether a janitor LMIA can be processed at all. It is added, with the
 *    exempt sectors, and cleaning is not among them.
 *
 * Confirmed: Job Bank carries an "LMIA requested" filter; since 22 July 2025
 * the Skilled Worker route needs an RQF level 6 job; Canada refuses to process
 * low-wage LMIAs in metropolitan areas at 6 percent unemployment or above.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class CleanerJanitorVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'cleaner-and-janitor-jobs-with-visa-sponsorship';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch/jobsearch?searchstring=cleaner';

    private const SAUDI_APPLY_URL = 'https://beoe.gov.pk/';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Cleaner and Janitor Jobs With Visa Sponsorship',
                'excerpt' => 'The UK does not sponsor cleaners, Canada can only process a janitor LMIA in some areas, and Saudi Arabia hires through licensed agencies. Where each route stands and how to avoid paying for a visa that does not exist.',
                'content' => $content,
                'featured_image' => 'blogs/cleaner-janitor-visa-sponsorship.jpg',
                'tags' => 'cleaner jobs with visa sponsorship, janitor jobs canada lmia, cleaner jobs saudi arabia, cleaning jobs uk visa sponsorship, light duty cleaner noc 65310, lmia requested job bank, beoe cleaner jobs, cleaner job scams',
                'meta_title' => 'Cleaner Jobs With Visa Sponsorship: UK, Canada, Saudi',
                'meta_description' => 'Can a cleaner get visa sponsorship? What the UK, Canada (LMIA) and Saudi Arabia actually allow, and how to avoid fake sponsorship offers.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'cleaning-facilities'],
            ['name' => 'Cleaning & Facilities']
        );

        $listings = [
            [
                'advertiser' => ['Canadian Cleaning and Janitorial Employers (Aggregated)', 'canada-cleaning-aggregated'],
                'location' => ['Canada', 'Canada'],
                'position' => 'Light Duty Cleaner — Canadian Employers (LMIA Requested Postings)',
                'apply' => self::CANADA_APPLY_URL,
                'hours' => 'Varies by employer; often shift-based',
                'language' => 'English',
                'description' => $this->canadaJobDescription(),
                'meta' => 'Light duty cleaner postings on Canada\'s Job Bank. Use the LMIA requested filter; the employer\'s LMIA is not a guarantee of a work permit.',
                'keywords' => 'janitor jobs canada, light duty cleaner, lmia requested, cleaner jobs canada visa sponsorship',
            ],
            [
                'advertiser' => ['Saudi Cleaning and Facility Employers (Aggregated)', 'saudi-cleaning-aggregated'],
                'location' => ['Saudi Arabia', 'Saudi Arabia'],
                'position' => 'Office and Facility Cleaner — Saudi Arabia (Licensed Agency Offers)',
                'apply' => self::SAUDI_APPLY_URL,
                'hours' => 'Varies by employer',
                'language' => 'English',
                'description' => $this->saudiJobDescription(),
                'meta' => 'Cleaner roles in Saudi Arabia filled through licensed overseas recruitment agencies. Pakistani applicants can check offers on the BEOE site.',
                'keywords' => 'cleaner jobs saudi arabia, facility cleaner riyadh, beoe cleaner jobs, cleaning jobs saudi visa',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['hours'],
                    'language' => $listing['language'],
                    // Pay depends on the employer and the contract; this site
                    // does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian employers post light duty cleaner and janitor vacancies on Job Bank. Duties usually include sweeping, mopping, vacuuming, emptying bins and disinfecting washrooms and common areas.</p>

<h3>Requirements</h3>
<ul>
    <li>Legal permission to work in Canada, or an employer whose LMIA is approved and who gives you a written job offer</li>
    <li>Many postings say they will train and ask for no degree</li>
    <li>On Job Bank, the "LMIA requested" filter shows employers who have applied to hire a temporary foreign worker</li>
</ul>

<p><strong>Note:</strong> an LMIA request is not an approval, and low-wage LMIAs are not processed in some areas. Work permit and LMIA rules are set by the Government of Canada, not by JobGader.</p>
JOBHTML;
    }

    private function saudiJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Saudi hospitals, offices and facility-management companies recruit cleaners from abroad, mostly through licensed overseas recruitment agencies, with Riyadh the main hiring centre.</p>

<h3>Requirements</h3>
<ul>
    <li>A written contract and an employer-sponsored work visa arranged before you travel</li>
    <li>For Pakistani applicants, an offer that appears on the Bureau of Emigration and Overseas Employment (BEOE) site with a permission number and a named licensed agency</li>
    <li>Other nationalities should check their own government's overseas employment office before using any recruiter</li>
</ul>

<p><strong>Note:</strong> visa and employment rules are set by the Saudi authorities and by your own government, not by JobGader. Never pay a fee for a "guaranteed" visa.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>It depends on the country. Canada and Saudi Arabia hire cleaners from abroad through employer-led processes, but the UK generally does not sponsor cleaners for a Skilled Worker visa. In every country the employer starts the process, and you should never pay anyone for a "guaranteed" visa.</p>

<h2>The Three Routes at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Is a cleaner sponsored?</th>
            <th style="padding:10px;text-align:left;">Who starts it</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK</strong></td><td style="padding:10px;">Generally no; the job is below the Skilled Worker skill level</td><td style="padding:10px;">Only relevant if you already have the right to work</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">Sometimes, through a low-wage LMIA, and not in every area</td><td style="padding:10px;">The employer applies for the LMIA first</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Saudi Arabia</strong></td><td style="padding:10px;">Yes, commonly, through licensed agencies</td><td style="padding:10px;">The employer, with a visa arranged before you travel</td></tr>
    </tbody>
</table>
</div>

<h2>Can I Get Cleaning Jobs in the UK With Visa Sponsorship?</h2>

<p>Usually not. A Skilled Worker visa needs a job on the eligible occupations list and an offer from an approved employer who issues a Certificate of Sponsorship. Since 22 July 2025 the job must be at a skill level equal to a degree (RQF level 6), and cleaning does not reach that level. Check the role against the GOV.UK Skilled Worker guidance before you believe an advert.</p>

<p>Many websites still claim "visa sponsorship available" for UK cleaner jobs. Treat those claims as a warning sign. Cleaning vacancies in the UK are open to people who already have the right to work, such as those with settled status or a Graduate visa, and those permissions do not need employer sponsorship. If that is you, see <a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London, No Experience Needed</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cleaner-janitor-visa-sponsorship-team.jpg"
         alt="A smiling cleaner holding a mop in a bright office lobby while two colleagues clean behind her, with a cleaning cart, a passport and a visa sticker in the foreground"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How Do I Find Janitor Jobs in Canada With Visa Sponsorship?</h2>

<p>Search Canada's official Job Bank for "light duty cleaner" (NOC 65310) and use the <strong>"LMIA requested"</strong> filter. That label means the employer has applied to the Temporary Foreign Worker Program to hire a foreign worker.</p>

<p>It is not a guarantee. The employer's application may still be refused, and many postings say that only people already authorised to work in Canada should apply. Typical postings need no degree and say the employer will train.</p>

<p>The bigger problem is where the job is. Janitor roles are low-wage positions, and Canada does not process low-wage LMIA applications in metropolitan areas where the unemployment rate is 6 percent or higher. The list of areas is updated every quarter. Primary agriculture, food and fish processing, construction and healthcare are exempt; cleaning is not. Check the current list on Canada.ca before you trust a posting in a large city. The full picture is in <a href="/blog/janitor-jobs-in-canada">Janitor Jobs in Canada</a> and <a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a>.</p>

<h2>What Does an LMIA Posting Actually Mean for Me?</h2>

<ol>
    <li><strong>The employer applies first.</strong> You cannot request an LMIA yourself.</li>
    <li><strong>You need a written job offer.</strong> Ask the employer to confirm their LMIA status in writing.</li>
    <li><strong>A work permit still has to be approved.</strong> An approved LMIA supports your work permit application; it is not the permit.</li>
</ol>

<p>If a recruiter asks for money to "buy an LMIA", walk away.</p>

<h2>Are There Cleaner Jobs in Saudi Arabia for Foreign Workers?</h2>

<p>Yes. Saudi cleaning jobs, mostly in Riyadh, are often filled through licensed overseas recruitment agencies. For Pakistani applicants, the Bureau of Emigration and Overseas Employment (BEOE) publishes approved offers. Each carries a permission number, an expiry date and a named licensed agency. Benefits and pay differ by offer, so read each one in full.</p>

<p>Applicants from other countries should check their own government's overseas employment office before using any recruiter. On BEOE, match the agency's licence number against any offer you are sent. If the agency is not listed, do not pay. More detail is in <a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/cleaning-facilities" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Cleaning Jobs on JobGader →
    </a>
</div>

<h2>How Do I Avoid Visa Sponsorship Scams?</h2>

<p><strong>Genuine employers do not charge you for a job or a visa.</strong> Watch for these red flags:</p>

<ul>
    <li><strong>A fee for a "guaranteed" visa, job offer or Certificate of Sponsorship.</strong></li>
    <li><strong>No written contract</strong> before you travel.</li>
    <li><strong>An employer who holds your passport or ID.</strong></li>
    <li><strong>An offer that sounds too good</strong>, such as a high wage for no experience.</li>
</ul>

<p>Verify the employer on the official source first: GOV.UK for the UK, Job Bank for Canada, and BEOE for Saudi offers made through Pakistani agencies.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need experience for cleaner jobs abroad?</h3>
<p>Often not. Many Canadian postings say the employer will train. Saudi employers often prefer some experience.</p>

<h3>Which country is easiest for a cleaner to get a work visa?</h3>
<p>None is easy, but Saudi Arabia, through licensed agencies, and Canada, through an LMIA, have clearer employer-led routes than the UK.</p>

<h3>Can I apply from my home country?</h3>
<p>For Saudi jobs, yes, through a licensed agency. For Canada, only when an employer's LMIA is approved and you hold a valid job offer. For the UK, generally not as a cleaner.</p>

<h3>Does "LMIA requested" mean I will get a visa?</h3>
<p>No. It means the employer has applied. The application can be refused, and a work permit must still be approved.</p>

<h3>Why can a Canadian janitor LMIA be refused outright?</h3>
<p>Low-wage LMIAs are not processed in areas where unemployment is 6 percent or higher, and cleaning is not an exempt sector.</p>

<h3>Is a UK "visa sponsorship cleaner" advert real?</h3>
<p>Treat it with suspicion. Cleaning is below the Skilled Worker skill level, so check GOV.UK before replying.</p>

<h3>Should I pay a recruiter for a cleaner visa?</h3>
<p>No. In every country the employer starts the process, and genuine employers do not charge you for a job or a visa.</p>

<h3>How do I check a Saudi recruitment agency?</h3>
<p>Pakistani applicants can match the agency's licence number on the BEOE site. Others should check their own government's overseas employment office.</p>

<h2>People Also Search For</h2>

<h3>Cleaner jobs with visa sponsorship</h3>
<p>Possible in Saudi Arabia and, in some areas, Canada; generally not in the UK.</p>

<h3>Janitor jobs in Canada LMIA</h3>
<p>Search Job Bank for "light duty cleaner" and filter for "LMIA requested".</p>

<h3>Light duty cleaner NOC 65310</h3>
<p>The Canadian occupation code used by Job Bank for cleaners.</p>

<h3>Cleaner jobs in Saudi Arabia</h3>
<p>Mostly in Riyadh, through licensed overseas agencies.</p>

<h3>Cleaning jobs UK visa sponsorship</h3>
<p>Below the Skilled Worker skill level, so adverts promising it need checking.</p>

<h3>BEOE cleaner jobs</h3>
<p>Approved offers for Pakistani applicants, each with a permission number and named agency.</p>

<h3>Low-wage LMIA Canada</h3>
<p>Not processed in areas with unemployment at 6 percent or higher.</p>

<h3>Cleaner job scams</h3>
<p>Real employers charge no fee for a job or a visa.</p>

<h2>More Job Guides</h2>

<p>Related cleaning and visa guides:</p>

<ul>
    <li><a href="/blog/janitor-jobs-in-canada">Janitor Jobs in Canada</a> &mdash; pay, provinces and the narrow visa route.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; the Saudi route in detail.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London, No Experience Needed</a> &mdash; for people who already have the right to work.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the wider Canadian picture.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; visas and agencies.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UK rules are from GOV.UK; Canadian rules from Job Bank and the Government of Canada; Saudi offers from BEOE. These rules change often, so confirm them with the official authority before applying.</p>
HTML;
    }
}
