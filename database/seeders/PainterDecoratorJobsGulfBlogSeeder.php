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
 * "Painter and Decorator Jobs in the Gulf" — UAE and Saudi Arabia rules for
 * painters recruited from abroad: work permits, who pays, the Saudi skills
 * exam, summer facade work and wage protection.
 *
 * The draft was rewritten. Almost all of it came from job boards and could
 * not be published:
 *
 * 1. GulfTalent vacancy counts and pay, a Naukrigulf average of SAR 2,978,
 *    and Indeed ranges of AED 2,000-3,000 and AED 1,400-1,600. Aggregator
 *    pay is not republished on this site, and neither country sets a
 *    minimum wage for expatriate workers.
 *
 * 2. Links to individual vacancies by job ID (a GMG posting 8093244 and two
 *    Accor postings, jid-111654 and jid-97469).
 *
 * Kept and checked: the MOHRE work permit and offer letter, the Saudi work
 * permit through HRSD, the skills list, CV advice and scam checks, plus the
 * verified Gulf rules shared with the AC technician and plumber guides.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class PainterDecoratorJobsGulfBlogSeeder extends Seeder
{
    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

    private const SAUDI_APPLY_URL = 'https://www.hrsd.gov.sa/en';

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
        $title = 'Painter and Decorator Jobs in the Gulf';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Painters are recruited into the UAE and Saudi Arabia in large numbers, with no expatriate minimum wage. What protects you: the employer pays recruitment costs, Saudi Arabia tests your trade before the visa, and facade work stops at midday in summer.',
                'content' => $content,
                'featured_image' => 'blogs/painter-decorator-jobs-gulf.jpg',
                'tags' => 'painter jobs dubai, painter jobs saudi arabia, painter and decorator gulf, mohre work permit, saudi professional verification painter, uae midday break, gulf painter visa, recruitment fees illegal uae',
                'meta_title' => 'Painter and Decorator Jobs in the Gulf: UAE and Saudi Rules',
                'meta_description' => 'Painter and decorator jobs in the UAE and Saudi Arabia: work permits, who pays recruitment costs, the Saudi skills exam, summer midday breaks and scam checks.',
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
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $uaeAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UAE Fit-Out, Facilities & Hotel Maintenance Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-painter-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Painter and Decorator — UAE Fit-Out, Facilities and Hotel Maintenance',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; outdoor work stops from 12:30pm to 3pm between 15 June and 15 September',
                'language' => 'English',
                // The UAE sets no minimum wage for private-sector expatriate
                // workers, and this site does not republish aggregator pay.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'Painter and decorator roles with UAE fit-out, facilities and hotel maintenance employers, recruited on MOHRE work permits.',
                'seo_keywords' => 'painter jobs dubai, painter jobs uae, decorator jobs dubai, mohre work permit, gulf painter',
            ]
        );

        $saudiAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi Construction, Fit-Out & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'saudi-painter-aggregated']
        );

        $saudiLocation = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Painter — Saudi Construction, Fit-Out and Facilities Employers',
                'advertiser_id' => $saudiAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $saudiLocation->id,
                'description' => $this->saudiJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under the Saudi Labour Law; outdoor work under direct sun is banned from noon to 3pm in summer',
                'language' => 'English',
                // There is no statutory minimum wage for expatriate workers in
                // Saudi Arabia, so no range can honestly be stated.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::SAUDI_APPLY_URL,
                'meta_description' => 'Painter roles in Saudi Arabia with construction, fit-out and facilities employers. Construction trades sit the Professional Verification exam before the visa.',
                'seo_keywords' => 'painter jobs saudi arabia, painter jobs riyadh, saudi professional verification, construction painter saudi, gulf painter',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Fit-out contractors, facilities management companies, developers and hotel maintenance teams across the UAE recruit painters and decorators from overseas on MOHRE work permits.</p>

<h3>What the work involves</h3>
<ul>
    <li>Surface preparation: filling, sanding, priming and masking</li>
    <li>Interior emulsion, enamel and decorative finishes</li>
    <li>Exterior facade and texture coatings, often from scaffolding or gondolas</li>
</ul>

<h3>What the law says</h3>
<ul>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021, the employer may not charge the worker recruitment and employment costs, directly or indirectly</li>
    <li>The UAE sets <strong>no statutory minimum wage</strong> for private-sector expatriate workers. The contract is the only binding figure</li>
    <li>The Wage Protection System runs under Ministerial Resolution No. 340 of 2026</li>
    <li>Outdoor work under direct sun stops from 12:30pm to 3pm between 15 June and 15 September</li>
</ul>

<p><strong>Never pay anyone for a UAE job.</strong> Visa, wage and permit rules are set by MOHRE, not by JobGader; confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function saudiJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Construction contractors, fit-out firms and facilities management providers across Saudi Arabia recruit painters from overseas.</p>

<h3>The trade exam comes before the visa</h3>
<p>The <strong>Professional Verification</strong> programme run by the Ministry of Human Resources and Social Development requires workers in covered professions, including construction trades, to pass a theoretical and practical assessment in their own country <strong>before the Saudi visa is issued</strong>.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>Experience with surface preparation, interior and exterior coatings</li>
    <li>Experience on scaffolding and with spray equipment for larger projects</li>
    <li>Basic English or Arabic for work instructions</li>
</ul>

<p><strong>Pay:</strong> Saudi Arabia sets no statutory minimum wage for expatriate workers. Get the basic wage, housing, transport and overtime stated separately in the contract.</p>

<p><strong>No genuine employer asks a worker to buy a visa.</strong> The employer bears the recruitment, residence permit and work permit fees. Visa and labour rules are set by the Saudi authorities, not by JobGader; confirm them on hrsd.gov.sa before paying anyone or booking travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every new tower, hotel and villa in the Gulf needs painting, and every existing one needs repainting on a cycle. Painters and decorators are recruited into the UAE and Saudi Arabia in large numbers, mostly from South Asia. Most guides answer the wrong question with a job-board salary. What decides whether a painter gets the job and keeps the money is written in law: who pays to recruit you, the trade test Saudi Arabia sets before it issues a visa, when facade work must stop in summer, and how your wage is protected.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/painter-decorator-jobs-gulf-facade.jpg"
         alt="Two painters in white caps and overalls rolling white paint onto a building facade above paint buckets, with the Dubai skyline, a mosque and the Burj Al Arab at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Hires Painters in the Gulf</h2>

<ul>
    <li><strong>Fit-out contractors</strong> finishing offices, shops, hotels and apartments. The largest source of interior decorating work.</li>
    <li><strong>Main contractors and painting subcontractors</strong> on new buildings, including facade coatings from scaffolding and gondolas.</li>
    <li><strong>Facilities management companies</strong> holding maintenance contracts for towers, malls and compounds, where repainting is routine.</li>
    <li><strong>Hotel engineering and maintenance teams</strong>, which keep painters on staff for rooms and public areas.</li>
</ul>

<p>Apply on the employer's own careers system or through your country's official overseas employment channel. Job boards repost the same adverts many times, and vacancy links expire within weeks.</p>

<h2>What a Painter Actually Earns</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">United Arab Emirates</th>
            <th style="padding:10px;text-align:left;">Saudi Arabia</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Statutory minimum wage for expatriates</strong></td><td style="padding:10px;">None</td><td style="padding:10px;">None</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>What binds the employer</strong></td><td style="padding:10px;">The contract, enforced through the Wage Protection System</td><td style="padding:10px;">The contract, registered on Qiwa and monitored by the Wage Protection System</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who pays recruitment costs</strong></td><td style="padding:10px;">The employer, by law</td><td style="padding:10px;">The employer, including recruitment, residence permit and work permit fees</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skills test before the visa</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">Yes, Professional Verification</td></tr>
    </tbody>
</table>
</div>

<p><strong>Neither country sets a wage floor for a foreign painter.</strong> The averages circulating online come from job boards, and they measure different people: one counts general labourers who paint, another counts skilled decorators and spray painters. We do not republish them. The number that binds is the one in your contract.</p>

<p>Make it specific. Ask for the basic wage, the housing allowance or company accommodation, transport, food allowance, overtime rate, annual ticket and medical cover each stated separately and in figures. Decorative finishes, spray painting and facade experience strengthen your bargaining position more than the country does.</p>

<h2>Getting a UAE Work Permit</h2>

<p>Article 6 of Federal Decree-Law No. 33 of 2021 makes a MOHRE work permit compulsory before anyone may be employed, and states that <strong>an employer may not charge the worker, or collect from the worker, recruitment and employment costs either directly or indirectly</strong>. The work permit, entry permit, medical test, Emirates ID and residence visa are all the employer's cost.</p>

<ul>
    <li><strong>The offer letter comes first.</strong> MOHRE issues a work permit on the basis of an offer letter that you sign, and the employment contract must match it. Keep a copy and compare the two before you sign the contract.</li>
    <li><strong>Check it is real.</strong> You can check the status of a work permit or offer with MOHRE through its website or app. A recruiter who refuses to give you the details needed to check is a warning sign.</li>
    <li><strong>Visa costs are not yours.</strong> A recruiter who asks you to repay "visa costs" from your salary is describing an unlawful arrangement.</li>
</ul>

<h2>Getting a Saudi Work Permit</h2>

<p>Saudi work permits are issued through the Ministry of Human Resources and Social Development, and contracts are registered on Qiwa. Saudi labour rules put the recruitment cost, the residence permit (iqama) and work permit fees, and their renewals, on the employer.</p>

<p>The ministry also runs the <strong>Professional Verification</strong> programme. Workers in covered professions must pass a theoretical and practical trade assessment, and the Saudi embassy requires the resulting certificate before it issues the work visa.</p>

<ul>
    <li><strong>Construction trades are covered.</strong> Confirm with the testing centre that your exact job title is on the list before you book.</li>
    <li><strong>You sit it at home.</strong> The assessment is taken in the country you are recruited from, before you travel.</li>
    <li><strong>It verifies, it does not train.</strong> The test checks the skill you already have. It does not find you a job.</li>
</ul>

<h2>Summer Facade Work: the Midday Rules</h2>

<p>Exterior painting is outdoor work under direct sun, so both countries' summer rules apply directly:</p>

<ul>
    <li><strong>UAE midday break.</strong> From 15 June to 15 September, work under direct sun in open areas stops from 12:30pm to 3pm. MOHRE fines employers AED 5,000 per worker found working during the break, up to AED 50,000.</li>
    <li><strong>Saudi Arabia.</strong> Work under direct sun is banned from noon to 3pm over the summer period.</li>
</ul>

<p>A supervisor who sends you up a scaffold to paint a facade at 1pm in July is breaking these rules. Interior work in air-conditioned spaces is not affected.</p>

<h2>How Your Wage Is Protected in the UAE</h2>

<p>The Wage Protection System runs under <strong>Ministerial Resolution No. 340 of 2026</strong>. Wages fall due on the first day of each Gregorian month, and at least 85 per cent of a company's wages must be transferred on time. New work permits are suspended from day five of a delay, a labour dispute opens automatically on day sixteen, and a travel ban can be placed on the person in charge from day twenty-one.</p>

<p><strong>Free zone employers sit outside the Labour Law.</strong> Free zone staff are generally governed by the zone's own rules and sponsored by the free zone authority, so read those rules if your employer is a free zone company.</p>

<h2>Skills Gulf Employers Ask For</h2>

<ul>
    <li>Surface preparation: scraping, filling, sanding, priming and masking</li>
    <li>Interior emulsion and enamel work to a hotel or fit-out finish standard</li>
    <li>Exterior and texture coatings, and waterproofing paints</li>
    <li>Spray painting with airless equipment</li>
    <li>Decorative finishes, wallpaper hanging and gypsum touch-up for decorator roles</li>
    <li>Safe work at height on scaffolding, ladders and gondolas</li>
    <li>Two or more years of experience; Gulf experience preferred but not always required</li>
</ul>

<h2>Writing a Painter's CV for the Gulf</h2>

<ol>
    <li><strong>Lead with the work, not a summary.</strong> List the types of buildings you have painted: villas, hotels, towers, hospitals.</li>
    <li><strong>Name the systems you know.</strong> Emulsion, enamel, epoxy, texture, spray. Gulf fit-out supervisors scan for these words.</li>
    <li><strong>State your height work.</strong> Scaffolding, gondola or rope access experience, and any safety training certificate.</li>
    <li><strong>Add photos of finished work</strong> as a short portfolio if you can. Decorators are hired on finish quality.</li>
    <li><strong>Include passport validity and trade certificates.</strong> For Saudi Arabia, add your Professional Verification result once you have it.</li>
</ol>

<h2>How to Spot a Fake Gulf Painter Job</h2>

<ul>
    <li><strong>Any fee is the giveaway.</strong> A recruitment, visa, medical or "processing" fee is unlawful in the UAE and the clearest sign of a scam everywhere.</li>
    <li><strong>No offer letter, or one that cannot be checked</strong> with MOHRE.</li>
    <li><strong>A Saudi visa without a skills test</strong> for a covered trade.</li>
    <li><strong>Interviews only on WhatsApp</strong>, with no company address or licence number.</li>
    <li><strong>A request for an OTP</strong> or your bank details before you have a contract.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much does a painter earn in Dubai?</h3>
<p>There is no official figure and no minimum wage for expatriate workers. Online averages come from job boards that measure different jobs. The contract is the only binding number, so get each allowance stated separately.</p>

<h3>Do painters need a skills test for Saudi Arabia?</h3>
<p>Construction trades are covered by the Professional Verification programme, and the test must be passed in your own country before the Saudi visa is issued. Confirm your exact job title with the testing centre.</p>

<h3>Who pays for a UAE work visa?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs, directly or indirectly.</p>

<h3>Can painters work outside at midday in summer?</h3>
<p>No. In the UAE, outdoor work under direct sun stops from 12:30pm to 3pm between 15 June and 15 September. Saudi Arabia bans it from noon to 3pm in summer.</p>

<h3>How do I check a UAE job offer is real?</h3>
<p>Ask for the details of the offer letter and check the permit status with MOHRE through its website or app. Never pay a fee to receive an offer.</p>

<h3>What skills do Gulf employers want in a painter?</h3>
<p>Surface preparation, interior and exterior coatings, spray painting and safe work at height. Decorative finishes and wallpaper hanging help for decorator roles.</p>

<h3>Is a painter job in the Gulf free of recruitment fees?</h3>
<p>It should be. In the UAE charging a worker recruitment costs is prohibited, and in Saudi Arabia the employer bears recruitment, iqama and work permit fees.</p>

<h3>Can a painter work in the UK instead?</h3>
<p>Painters and decorators (SOC 5323) were on the UK Temporary Shortage List when we checked, unlike carpenters. The list is temporary, so check it on gov.uk before applying.</p>

<h2>People Also Search For</h2>

<h3>Painter jobs in Dubai</h3>
<p>Recruited on MOHRE work permits, with every recruitment cost owed by the employer.</p>

<h3>Painter jobs in Saudi Arabia</h3>
<p>A Professional Verification test at home comes before the visa.</p>

<h3>Decorator jobs in UAE</h3>
<p>Mostly with fit-out contractors and hotel maintenance teams.</p>

<h3>UAE midday break 2026</h3>
<p>12:30pm to 3pm, from 15 June to 15 September.</p>

<h3>MOHRE offer letter check</h3>
<p>Check the permit status on the MOHRE website or app.</p>

<h3>Wage Protection System UAE</h3>
<p>Ministerial Resolution No. 340 of 2026, with enforcement from day five.</p>

<h3>Recruitment fees illegal UAE</h3>
<p>Charging a worker recruitment or employment costs is prohibited.</p>

<h3>Spray painter jobs Gulf</h3>
<p>Airless spray experience is one of the skills that raises a painter's offer.</p>

<h2>More Job Guides</h2>

<p>The Gulf trades and related guides:</p>

<ul>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; the same Gulf rules for another facilities trade.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; summer hours, the Saudi skills exam and recruitment costs.</li>
    <li><a href="/blog/construction-labourer-jobs-in-dubai-and-saudi-arabia">Construction Labourer Jobs in Dubai and Saudi Arabia</a> &mdash; site work across the two countries.</li>
    <li><a href="/blog/carpenter-jobs-in-the-uk-canada-and-australia">Carpenter Jobs in the UK, Canada and Australia</a> &mdash; another building trade, and why the UK shortage list matters.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the wider Saudi construction market.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the recruitment process end to end.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. UAE and Saudi labour and visa rules change; confirm the current position on u.ae, mohre.gov.ae and hrsd.gov.sa before accepting an offer or paying anyone.</p>
HTML;
    }
}
