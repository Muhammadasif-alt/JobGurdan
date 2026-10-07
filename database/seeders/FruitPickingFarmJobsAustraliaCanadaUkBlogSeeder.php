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
 * "Fruit Picking and Farm Jobs in Australia, Canada and the UK".
 *
 * The brief was already built on official sources, so it is kept close. What
 * changed:
 *
 * 1. Apply Now buttons repeated the same Workforce Australia, Costa and Job
 *    Bank search links several times under different labels. The guide links
 *    only to our own pages; the job listings carry one official link per
 *    country (Workforce Australia, Job Bank, Concordia).
 *
 * 2. The PALM scheme page did not load when checked, so it is described but
 *    not linked.
 *
 * 3. No pay figures are published. The brief had none either, and the guide
 *    keeps the Horticulture Award point about the piecework minimum wage
 *    guarantee without a number.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder extends Seeder
{
    public const SLUG = 'fruit-picking-and-farm-jobs-in-australia-canada-and-the-uk';

    private const AUSTRALIA_APPLY_URL = 'https://www.workforceaustralia.gov.au/individuals/jobs/search';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch/jobsearch?fsrc=32&searchstring=farm+worker';

    private const UK_APPLY_URL = 'https://www.concordia.org.uk/seasonal-work/information-for-workers/how-to-apply/';

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
                'title' => 'Fruit Picking and Farm Jobs in Australia, Canada and the UK',
                'excerpt' => 'Your visa decides whether you can pick fruit abroad. Australia\'s working holiday and PALM routes, Canada\'s SAWP and Agricultural Stream, and the UK Seasonal Worker route each carry their own nationality and sponsorship rules.',
                'content' => $content,
                'featured_image' => 'blogs/fruit-picking-farm-jobs-australia-canada-uk.jpg',
                'tags' => 'fruit picking jobs, farm worker visa, seasonal worker jobs abroad, fruit picking jobs australia, farm jobs canada lmia, uk seasonal worker visa, working holiday visa farm work, palm scheme workers',
                'meta_title' => 'Fruit Picking and Farm Jobs: Australia, Canada, UK',
                'meta_description' => 'Fruit picking jobs in Australia, Canada and the UK: working holiday, PALM, SAWP and UK Seasonal Worker visa routes, pay checks and how to apply.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $listings = [
            [
                'advertiser' => ['Australian Farm and Harvest Employers (Aggregated)', 'australia-farm-aggregated'],
                'location' => ['Australia', 'Australia'],
                'position' => 'Fruit Picker and Farm Worker — Australian Farms (Visa Permission Required)',
                'apply' => self::AUSTRALIA_APPLY_URL,
                'hours' => 'Seasonal, often early starts and weekends',
                'language' => 'English',
                'description' => $this->australiaJobDescription(),
                'meta' => 'Fruit picking and harvest roles on Australian farms. Your visa must permit the work, and working holiday and PALM routes have nationality rules.',
                'keywords' => 'fruit picking jobs australia, harvest jobs australia, working holiday visa farm work, palm scheme',
                'category' => ['general-labour', 'General Labour'],
            ],
            [
                'advertiser' => ['Canadian Farm Employers (Aggregated)', 'canada-farm-aggregated'],
                'location' => ['Canada', 'Canada'],
                'position' => 'Farm Worker — Canadian Farms (LMIA Requested Postings)',
                'apply' => self::CANADA_APPLY_URL,
                'hours' => 'Seasonal, varies by crop',
                'language' => 'English',
                'description' => $this->canadaJobDescription(),
                'meta' => 'Farm, greenhouse and fruit roles on Canada\'s Job Bank. The employer\'s LMIA is not a guarantee of a work permit.',
                'keywords' => 'farm worker jobs canada, agricultural stream, seasonal agricultural worker program, lmia requested',
                'category' => ['general-labour', 'General Labour'],
            ],
            [
                'advertiser' => ['UK Farm and Horticulture Employers (Aggregated)', 'uk-farm-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Seasonal Farm Worker — UK Farms (Approved Scheme Operator Required)',
                'apply' => self::UK_APPLY_URL,
                'hours' => 'Seasonal, up to six months',
                'language' => 'English',
                'description' => $this->ukJobDescription(),
                'meta' => 'Seasonal horticulture work on UK farms. Sponsorship comes only through an approved scheme operator, for up to six months.',
                'keywords' => 'seasonal worker visa uk, fruit picking jobs uk, concordia seasonal work, farm jobs uk',
                'category' => ['general-labour', 'General Labour'],
            ],
        ];

        foreach ($listings as $listing) {
            $category = Category::firstOrCreate(
                ['slug' => $listing['category'][0]],
                ['name' => $listing['category'][1]]
            );

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

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian farms and growers advertise harvest, picking, packing and crop maintenance work on Workforce Australia, the government job board.</p>

<h3>Requirements</h3>
<ul>
    <li>A visa that permits the work, such as a Working Holiday Maker visa (subclasses 417 and 462) where your nationality is eligible, or the PALM scheme for workers from participating Pacific island countries and Timor-Leste</li>
    <li>Workers covered by the Horticulture Award have a piecework minimum wage guarantee</li>
    <li>Ask for the employer's legal name, the farm address and a written contract</li>
</ul>

<p><strong>Note:</strong> visa and award rules are set by the Australian Government and the Fair Work Ombudsman, not by JobGader. A farm cannot sponsor you onto a route you are not eligible for.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian farms, greenhouses and orchards post agricultural vacancies on Job Bank, including postings for temporary foreign workers.</p>

<h3>Requirements</h3>
<ul>
    <li>Under the Seasonal Agricultural Worker Program (SAWP), workers come from participating countries, including Mexico and specified Caribbean countries, and are recruited by their own governments</li>
    <li>The Agricultural Stream can involve workers from any country, subject to programme and immigration requirements</li>
    <li>An "LMIA requested" posting means the employer has applied, not that approval has been granted</li>
</ul>

<p><strong>Note:</strong> programme and permit rules are set by the Government of Canada, not by JobGader. Apply only where your circumstances match the posting.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK farms recruit seasonal horticulture workers through approved scheme operators. The link goes to Concordia's guidance on how to apply, one of the operators GOV.UK lists.</p>

<h3>Requirements</h3>
<ul>
    <li>You must be at least 18 and meet the sponsorship, financial and other immigration requirements</li>
    <li>Horticulture work is temporary, with a maximum six-month period; the route does not allow dependants or an extension from inside the UK</li>
    <li>An individual farm cannot simply issue sponsorship under this route</li>
</ul>

<p><strong>Note:</strong> route rules are set by the Home Office, not by JobGader. Check with the operator before you send any money.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You can find fruit picking and farm jobs in Australia, Canada and the UK through official job boards, farm employers and approved recruitment operators. Some positions accept beginners, but you must have permission to work. Australia's working holiday and PALM routes have nationality restrictions. Canada's Agricultural Stream differs from its nationality-restricted Seasonal Agricultural Worker Program. The UK Seasonal Worker route needs sponsorship through an approved scheme operator. Check your immigration eligibility before you pay for travel or accept a job.</p>

<h2>Compare the Three Destinations</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Recruitment route</th>
            <th style="padding:10px;text-align:left;">Important eligibility check</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia</strong></td><td style="padding:10px;">Government job board and farm employers</td><td style="padding:10px;">Your visa must permit the work; nationality matters for specific schemes</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">Job Bank and agricultural employers</td><td style="padding:10px;">Employer approval and your work permit requirements</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK</strong></td><td style="padding:10px;">Approved seasonal scheme operators</td><td style="padding:10px;">Sponsorship, financial conditions and temporary visa restrictions</td></tr>
    </tbody>
</table>
</div>

<p>"Farm worker visa" is a search term, not one immigration category. Each country has its own rules.</p>

<h2>What Do Fruit Pickers and Farm Workers Actually Do?</h2>

<p>Fruit picking jobs can involve harvesting, sorting, carrying produce and preparing crops for packing. Other farm positions include planting, pruning, greenhouse work, irrigation support and general crop maintenance. Machinery and animal handling roles can need extra experience. Expect repeated movements, early starts, rural locations and changing weather, and ask about lifting, protective equipment, transport, training and the daily schedule. UK government guidance says seasonal farm work can suit different experience levels, with training provided, but can be physically demanding.</p>

<h2>Fruit Picking and Harvest Jobs in Australia</h2>

<p>Check your work permission first. Working Holiday Maker visas include subclasses 417 and 462, and eligibility depends on factors such as your passport nationality, your age and the subclass. A fruit picking offer does not make you eligible for a working holiday visa.</p>

<p>The PALM scheme is a separate route for eligible workers from nine participating Pacific island countries and Timor-Leste. Interested citizens should use their own country's official labour-sending arrangements. PALM is not open to every nationality. If neither route fits you, establish another lawful work pathway before applying, and do not assume a farm will sponsor you.</p>

<p>Australian Home Affairs identifies Workforce Australia as a source of agricultural and harvest vacancies. Search by crop, location and season. For workers covered by the Horticulture Award, piecework has a minimum wage guarantee, and being paid per tray or bin does not remove that protection. Confirm the applicable award, your classification and your written pay arrangement.</p>

<p>If you need specified work for another working holiday visa, check the qualifying activity, location, duration and subclass rules. Not every harvest job counts, and some applicants have different requirements. Keep your employment and payment records.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/fruit-picking-farm-jobs-australia-canada-uk-inline.jpg"
         alt="A smiling farm worker holding a crate of strawberries, blueberries and apples in a field, with pickers at work and the Sydney Opera House, Toronto's CN Tower and Big Ben behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Agricultural Jobs and Work Permits in Canada</h2>

<p>Canada's Seasonal Agricultural Worker Program (SAWP) is restricted to participating countries, including Mexico and specified Caribbean countries. Applicants must also be recruited by their own government and work for participating employers. The Agricultural Stream can involve workers from any country, subject to the programme and immigration requirements. An employer's access to that stream does not guarantee that you will receive a work permit.</p>

<p>For an LMIA-based route, the employer normally needs a positive Labour Market Impact Assessment before you apply for the permit. A Job Bank advert marked <strong>"LMIA requested"</strong> does not mean approval has been granted. Search Job Bank's temporary foreign worker listings for farm, fruit, greenhouse and crop roles, and read who can apply, the location, duties, wages and the employer's contact instructions. Apply only where your circumstances match the advert. The detail is in <a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a>.</p>

<p>Ask about housing, transport, contract duration and required documents. Agricultural Stream rules include employer responsibilities for transport and accommodation arrangements, so verify the conditions that apply to your offer.</p>

<h2>Seasonal Worker Jobs in the UK</h2>

<p>On the UK Seasonal Worker route, sponsorship comes through an approved scheme operator. An individual farm cannot simply issue sponsorship under this route. GOV.UK lists the authorised operators and advises applicants to use their verified recruitment channels. Availability differs by country and season, so follow each operator's current instructions rather than assuming applications are open everywhere.</p>

<p>Applicants must be at least 18 and meet the sponsorship, financial and other immigration requirements. Horticulture work is temporary, with a maximum six-month period and rules governing repeat participation. The route does not allow dependants, and you cannot extend from inside the UK. Check current guidance rather than older descriptions of annual limits, and ask about guaranteed paid hours, accommodation charges, transport and the work start date before you accept a placement.</p>

<h2>Documents and Application Preparation</h2>

<p>Prepare your passport, CV, contact details, employment references and the evidence your immigration route asks for. Health checks, police certificates or other records may be required. Describe your availability, outdoor work experience and willingness to follow safety procedures. Beginners should explain relevant transferable experience honestly, and you should not invent agricultural qualifications or machinery experience.</p>

<h2>How Do I Check a Farm Offer Before Travelling?</h2>

<p>Ask for the employer's legal name, the farm address, the crop, the expected season length and a written contract. Ask how weather interruptions affect paid hours and whether transport runs daily from the accommodation. Confirm room sharing, cooking facilities, internet access and the distance to shops and medical services. Keep a budget for living costs and emergencies, and compare income after tax and permitted deductions instead of judging an offer by a headline hourly rate. Record your daily hours and keep your payslips.</p>

<p>Verify recruitment messages using contact details from the official website. For UK seasonal recruitment, government guidance separates legitimate visa and travel costs from prohibited sponsorship charges. If a payment instruction looks unfamiliar, contact the operator directly before you send money, and keep copies of written recruitment messages and receipts.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply for farm jobs?</h3>
<p>Yes, some crop and picking roles provide training. Read the advert, because other positions need practical experience.</p>

<h3>Can every nationality use every seasonal programme?</h3>
<p>No. Australia's working holiday and PALM routes and Canada's SAWP have nationality restrictions. Canada's Agricultural Stream has different conditions.</p>

<h3>Does a farm job offer guarantee a visa?</h3>
<p>No. Recruitment and immigration approval are separate, so verify the actual route and the employer's arrangements.</p>

<h3>Is accommodation always free?</h3>
<p>No. Ask for written housing terms and lawful deductions. Arrangements vary by programme and employer.</p>

<h3>How long can I work on the UK Seasonal Worker route?</h3>
<p>Horticulture work is limited to a maximum of six months, and the route does not allow dependants or an extension from inside the UK.</p>

<h3>Is piecework pay legal in Australia?</h3>
<p>Yes, but for workers covered by the Horticulture Award piecework has a minimum wage guarantee, so pay per tray or bin does not remove that protection.</p>

<h3>What does "LMIA requested" mean on Job Bank?</h3>
<p>The employer has applied to hire a foreign worker. It is not an approval, and a work permit still has to be granted.</p>

<h3>Should I pay a recruiter for a farm job abroad?</h3>
<p>Be very careful. For the UK route, check with the approved operator before sending money, and never pay for a "guaranteed" visa.</p>

<h2>People Also Search For</h2>

<h3>Fruit picking jobs</h3>
<p>Harvest work in Australia, Canada and the UK, each with its own permission rules.</p>

<h3>Farm worker visa</h3>
<p>A search term, not one visa; each country has a different route.</p>

<h3>Seasonal worker jobs abroad</h3>
<p>Temporary harvest and crop work through official programmes.</p>

<h3>Working holiday visa 417 and 462</h3>
<p>Nationality- and age-based visas for working holiday makers in Australia.</p>

<h3>PALM scheme</h3>
<p>Australia's route for workers from nine Pacific island countries and Timor-Leste.</p>

<h3>Seasonal Agricultural Worker Program</h3>
<p>Canada's route for participating countries including Mexico and Caribbean countries.</p>

<h3>UK Seasonal Worker visa</h3>
<p>Sponsored through an approved scheme operator, for up to six months.</p>

<h3>Farm jobs in Canada</h3>
<p>Search Job Bank for farm, fruit, greenhouse and crop roles.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the Canadian route in detail.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the wider Canadian picture.</li>
    <li><a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">Cleaner and Janitor Jobs With Visa Sponsorship</a> &mdash; another role where sponsorship claims need checking.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Information is drawn from Australian Home Affairs, the Fair Work Ombudsman, the Government of Canada and GOV.UK, reviewed on 6 October 2026. Recruitment availability and immigration rules change, so confirm them with the official authority before applying.</p>
HTML;
    }
}
