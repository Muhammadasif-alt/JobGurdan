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
 * "Plumber Jobs in UAE and Saudi Arabia" — the Gulf half of the plumber
 * cluster. It owns the two things that actually decide whether a plumber
 * can take a Gulf job: who pays for the recruitment, and the Saudi skills
 * exam that has to be passed before the visa is issued.
 *
 * The draft was rewritten rather than corrected. What it carried could not
 * be published under this site's own rules:
 *
 * 1. Five "Apply Now - Indeed" buttons. No aggregator is ever linked here.
 *
 * 2. Salary figures republished from Indeed (AED 2,056 average, AED 2,555
 *    senior, from 623 self-reported salaries) and GulfTalent (SAR 2,500
 *    median). Aggregator pay data is not republished on this site. Neither
 *    country sets a statutory minimum wage for private-sector expatriate
 *    workers, so the honest answer is the contract and the Wage Protection
 *    System, which is what this page gives instead.
 *
 * 3. Three apply links carrying a job ID - jobs.kerzner.com/.../4167522/,
 *    careers.marriott.com/.../20A8E14628FFD74FEB4199FD7C7E51E5 and
 *    jobs.hilton.com/.../HOT0C8HU. Those URLs die with the vacancy.
 *
 * 4. Five named vacancies at Atlantis, Farnek, Hilton, Marriott and Grand
 *    Hyatt. The draft's own notes admit the Hyatt listing could not be
 *    found and the Hilton one needed re-checking before publishing.
 *
 * What the draft got right and this page keeps: the employer bears the
 * recruitment cost in the UAE, and Saudi Arabia requires a trade exam
 * passed outside the Kingdom before the visa.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class PlumberJobsUaeSaudiBlogSeeder extends Seeder
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
        $title = 'Plumber Jobs in UAE and Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Neither country sets a minimum wage for expatriate workers, so the contract is the only number that binds. UAE law makes the employer pay every recruitment cost, and Saudi Arabia will not issue the visa until you have passed a trade exam at home.',
                'content' => $content,
                'featured_image' => 'blogs/plumber-jobs-in-uae-and-saudi-arabia.jpg',
                'tags' => 'plumber jobs in uae, plumber jobs in saudi arabia, gulf plumber jobs, mohre work permit, saudi professional verification, wage protection system uae, plumber visa gulf, recruitment fees illegal uae',
                'meta_title' => 'Plumber Jobs in UAE and Saudi Arabia: Visa, Pay, Exam',
                'meta_description' => 'Plumber jobs in the UAE and Saudi Arabia: who pays the recruitment cost, the Saudi trade exam you must pass first, and what protects your wages.',
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
            ['name' => 'UAE Facilities Management, Contracting & Hospitality Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-plumber-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Plumber — UAE Facilities Management, Contracting and Hotel Maintenance',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Often shift-based on facilities contracts, which removes the statutory night overtime premium unless the contract restores it',
                'language' => 'English',
                // The UAE sets no minimum wage for private-sector expatriate
                // workers, and this site does not republish aggregator pay
                // data, so there is no figure that could honestly go here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'Plumber and plumbing technician roles with UAE facilities management, contracting and hotel maintenance employers, recruited on MOHRE work permits.',
                'seo_keywords' => 'plumber jobs in uae, plumber jobs dubai, plumbing technician uae, mohre work permit plumber, gulf plumber jobs',
            ]
        );

        $saudiAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi Contracting, Facilities & Hospitality Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'saudi-plumber-aggregated']
        );

        $saudiLocation = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Plumber — Saudi Arabia Contracting and Facilities Maintenance (Skills Exam Required Before Visa)',
                'advertiser_id' => $saudiAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $saudiLocation->id,
                'description' => $this->saudiJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under the Saudi Labour Law, with the contract registered on Qiwa',
                'language' => 'English',
                // Saudi Arabia's SAR 4,000 floor applies to Saudi nationals
                // for Nitaqat counting only. There is no statutory minimum for
                // expatriate workers, so no range can honestly be stated.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::SAUDI_APPLY_URL,
                'meta_description' => 'Plumber roles with Saudi contracting and facilities employers. The Professional Verification trade exam must be passed in your home country before the visa.',
                'seo_keywords' => 'plumber jobs in saudi arabia, saudi professional verification plumber, takamol skill test plumber, plumber visa saudi arabia, gulf plumber jobs',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Facilities management companies, contracting firms and hotel engineering departments across the UAE recruit plumbers and plumbing technicians from overseas on MOHRE work permits.</p>

<h3>What the law says about who pays</h3>
<p>Under Article 6 of Federal Decree-Law No. 33 of 2021, no worker may be employed without a work permit from the Ministry of Human Resources and Emiratisation, and <strong>an employer may not charge the worker, or collect from the worker, recruitment and employment costs either directly or indirectly</strong>. The permit, the entry permit, the medical test, the Emirates ID and the residence visa are the employer's cost.</p>

<h3>How the wage is protected</h3>
<ul>
    <li>The UAE sets <strong>no statutory minimum wage</strong> for private-sector expatriate workers. The figure in your contract is the only figure that binds</li>
    <li>The Wage Protection System runs under Ministerial Resolution No. 340 of 2026: wages fall due on the first day of each Gregorian month, and at least 85 per cent of them must be transferred on time</li>
    <li>Enforcement escalates by day: new work permits are suspended from the fifth day, a dispute opens automatically on the sixteenth, and a travel ban can be placed on the person in charge from the twenty-first</li>
</ul>

<h3>Two things the adverts do not mention</h3>
<ul>
    <li><strong>Shift work removes the night premium.</strong> Overtime is the basic wage plus 25 per cent, or plus 50 per cent between 10pm and 4am &mdash; but u.ae states that rule does not apply to workers on shifts. Facilities plumbers are usually on shifts, so get the allowance written into the contract</li>
    <li><strong>Free zone employers are outside the Labour Law.</strong> Free zone staff are generally not governed by it, each zone has its own employment rules, and they are sponsored by the free zone authority rather than by the employer</li>
</ul>

<p><strong>Never pay anyone for a UAE job.</strong> Charging a job seeker recruitment fees is illegal, and no legitimate recruiter will ask you for an OTP. Visa rules, wage rules and permit costs are set by MOHRE, not by JobGader; confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function saudiJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Contracting companies, facilities management providers and hotel engineering teams across Saudi Arabia recruit plumbers from overseas. One step comes before everything else.</p>

<h3>The trade exam comes before the visa</h3>
<p>The Ministry of Human Resources and Social Development runs the <strong>Professional Verification</strong> programme with the Technical and Vocational Training Corporation. Workers in covered professions must pass a theoretical and practical trade assessment and hold a Professional Accreditation certificate <strong>before the Saudi visa is issued</strong>. The exam is sat in your own country, not after arrival. The programme has been extended to 160 labour-exporting countries and more than 1,000 professions, and plumbing is among the construction trades covered.</p>

<h3>Pay</h3>
<ul>
    <li>Saudi Arabia sets <strong>no statutory minimum wage for expatriate workers</strong>. The SAR 4,000 figure that circulates applies to Saudi nationals, as the level at which an employee counts toward a company's Saudisation quota</li>
    <li>Your contract is therefore the only binding number. Get the basic wage, housing, transport and overtime treatment stated separately in it</li>
    <li>The Wage Protection System monitors that the contracted wage is actually paid</li>
</ul>

<h3>Before you travel</h3>
<ul>
    <li>Confirm your trade is on the verification list and book the assessment through the authorised provider in your country</li>
    <li>Check the contract is registered on Qiwa and matches the offer you accepted</li>
    <li>Keep the accreditation certificate: the Saudi embassy will ask for it at the visa stage</li>
</ul>

<p><strong>No genuine employer asks a worker to buy a visa.</strong> Visa, accreditation and labour rules are set by the Saudi authorities, not by JobGader; confirm them on hrsd.gov.sa before paying anyone or booking travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to Gulf plumbing jobs answer the wrong question. They quote an average salary scraped from a job board and list five hotel vacancies that will have closed by the time you read them. The two things that actually decide whether you can take the job are who pays for the recruitment, and whether you are allowed into the country in the first place. The UAE and Saudi Arabia answer both differently, and both answers are written in law.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-jobs-in-uae-and-saudi-arabia-worksite.jpg"
         alt="A plumber in blue overalls tightening a pipe fitting with a wrench, with the UAE and Saudi flags, a Dubai construction site and the city skyline behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The One Rule That Matters Most in the UAE</h2>

<p>Article 6 of Federal Decree-Law No. 33 of 2021 does two things. It makes a MOHRE work permit compulsory before anyone may be employed, and it states that <strong>an employer may not charge the worker, or collect from the worker, recruitment and employment costs either directly or indirectly</strong>.</p>

<p>That covers the work permit, the entry permit, the medical test, the Emirates ID and the residence visa. All of it is the employer's cost. If a recruiter tells you the visa is AED 5,000 and you can repay it from your first three salaries, the arrangement is not a fee you are negotiating. It is unlawful, and it is the single most common way plumbers and other trade workers lose money before they have earned any.</p>

<h2>What a Gulf Plumbing Job Actually Pays</h2>

<p>This is where most pages stop being useful, because the honest answer is uncomfortable:</p>

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
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>The figure people quote</strong></td><td style="padding:10px;">&mdash;</td><td style="padding:10px;">SAR 4,000, which applies to <strong>Saudi nationals</strong> as the level at which they count toward a company's Saudisation quota</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>What binds the employer</strong></td><td style="padding:10px;">The contract, enforced through the Wage Protection System</td><td style="padding:10px;">The contract, registered on Qiwa and monitored by the Wage Protection System</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who pays recruitment costs</strong></td><td style="padding:10px;">The employer, by law</td><td style="padding:10px;">The employer recruits through the official channels; never buy a visa</td></tr>
    </tbody>
</table>
</div>

<p><strong>Neither country sets a wage floor for a foreign plumber.</strong> That is why the salary averages circulating on job boards vary so wildly and why we do not republish them: they are self-reported figures from a handful of users describing different emirates, different employers and different allowance packages, presented as though they were a market rate.</p>

<p>What you can do instead is make the contract specific. Ask for the basic wage, the housing allowance or company accommodation, the transport allowance, the overtime rate, the annual ticket and the medical cover each stated separately and in figures. A single "total package" number hides which parts of it can be reduced later.</p>

<h2>How Your Wage Is Protected Once You Arrive</h2>

<p>The UAE Wage Protection System now runs under <strong>Ministerial Resolution No. 340 of 2026</strong>. Wages fall due on the first day of each Gregorian month, and at least 85 per cent of a company's wages must be transferred on time. Enforcement escalates on a calendar:</p>

<ul>
    <li><strong>Day 5.</strong> New work permits for that employer are suspended</li>
    <li><strong>Day 16.</strong> A labour dispute opens automatically</li>
    <li><strong>Day 21.</strong> A travel ban may be placed on the person in charge of the company</li>
</ul>

<p>Any guide still telling you wages must be paid "within 15 days" is quoting the superseded 2022 resolution.</p>

<h2>Two Exceptions That Catch Plumbers Specifically</h2>

<ul>
    <li><strong>Shift work cancels the night overtime premium.</strong> UAE overtime is the basic wage plus 25 per cent, and plus 50 per cent for work between 10pm and 4am &mdash; but u.ae states plainly that the rule does not apply to workers employed on a shift basis. Facilities management plumbers covering a hotel, an airport or a hospital are almost always on shifts, so that 50 per cent is not automatic. Get it written into the contract or treat it as not existing.</li>
    <li><strong>Free zone employers sit outside the Labour Law.</strong> Free zone employees are generally not governed by the UAE Labour Law; each zone has its own employment rules, and staff are sponsored by the free zone authority rather than by the employer. Many large contracting and facilities companies are registered in free zones, so check which regime your offer falls under before comparing it with another.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-jobs-in-uae-and-saudi-arabia-villa.jpg"
         alt="A plumber kneeling to fit a pipe under a villa sink with a service van outside, the UAE flag and the Dubai skyline at sunset in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Saudi Arabia: the Exam You Must Pass Before the Visa</h2>

<p>This is the step that stops more Gulf plumbing applications than any other, and most guides either omit it or mention it in a sentence.</p>

<p>The Ministry of Human Resources and Social Development runs the <strong>Professional Verification</strong> programme together with the Technical and Vocational Training Corporation. Workers recruited into covered professions must sit a theoretical and practical trade assessment and obtain a Professional Accreditation certificate, and <strong>the Saudi embassy requires that certificate before it issues the work visa</strong>.</p>

<ul>
    <li><strong>You sit it at home, not in Saudi Arabia.</strong> The assessment is taken in the country you are recruited from, before you travel.</li>
    <li><strong>The programme is now very wide.</strong> It has been extended to 160 labour-exporting countries and more than a thousand professions across every industry, having started with aviation, construction, health care, media and tourism.</li>
    <li><strong>Plumbing is covered.</strong> It sits among the civil and construction trades, and was in the first group of trades tested when the scheme began.</li>
    <li><strong>It is an assessment, not training.</strong> The provider verifies the skill you already have; it does not teach it to you and it does not find you a job.</li>
</ul>

<p>Plan for it in your timeline. A plumber who accepts an offer and then discovers the exam is three weeks from the start date has a problem that no employer can solve from Riyadh.</p>

<h2>What Employers Ask For</h2>

<p>Across both countries, trade adverts for hotel engineering, facilities management and contracting converge on a short list:</p>

<ul>
    <li>Two to six years of plumbing experience, with hotel or large-building maintenance experience valued above domestic work</li>
    <li>A recognised trade certificate or vocational qualification, which also makes the Saudi assessment easier to clear</li>
    <li>Experience with pumps, pressure systems, drainage and preventive maintenance schedules, not only repairs</li>
    <li>Basic English for work orders and safety briefings</li>
    <li>A driving licence in some roles, though a home licence usually has to be converted after arrival</li>
</ul>

<h2>How to Apply Without Losing Money</h2>

<ol>
    <li><strong>Apply on the employer's own careers system</strong>, or through your own country's official overseas employment channel. Do not apply through someone who contacts you first on a messaging app.</li>
    <li><strong>Check the employer exists</strong> independently, by searching the company's legal name rather than the brand in the message.</li>
    <li><strong>Never pay a recruitment, visa or placement fee.</strong> In the UAE it is unlawful; everywhere it is the clearest sign of a scam.</li>
    <li><strong>Never share an OTP.</strong> No employer or ministry needs a one-time code from your phone.</li>
    <li><strong>Get the offer in writing before you resign</strong>, with each allowance itemised.</li>
    <li><strong>For Saudi Arabia, start the trade assessment early</strong>, because the visa cannot be issued without the certificate.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Who pays for a UAE work visa, the employer or the plumber?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 prohibits an employer from charging a worker, or collecting from a worker, recruitment and employment costs either directly or indirectly. That covers the work permit, entry permit, medical test, Emirates ID and residence visa.</p>

<h3>Is there a minimum wage for plumbers in the UAE or Saudi Arabia?</h3>
<p>No. Neither country sets a statutory minimum wage for private-sector expatriate workers. The SAR 4,000 figure often quoted for Saudi Arabia applies to Saudi nationals, as the level at which an employee counts toward a company's Saudisation quota.</p>

<h3>Do I need a skills test to work as a plumber in Saudi Arabia?</h3>
<p>Yes, for covered professions. The Professional Verification programme requires a theoretical and practical trade assessment taken in your own country before the Saudi embassy issues the work visa. Plumbing sits among the construction trades covered.</p>

<h3>When do I take the Saudi trade exam, before or after I travel?</h3>
<p>Before. The accreditation certificate is part of the visa process, so the assessment must be completed in the country you are recruited from.</p>

<h3>How is my salary protected in the UAE?</h3>
<p>Through the Wage Protection System under Ministerial Resolution No. 340 of 2026. Wages are due on the first day of each Gregorian month and at least 85 per cent must be transferred on time, with new work permits suspended from day five and a travel ban possible on the person in charge from day twenty-one.</p>

<h3>Do UAE plumbers get the 50 per cent night overtime rate?</h3>
<p>Not automatically. The basic wage plus 50 per cent applies to work between 10pm and 4am, but u.ae states the rule does not apply to workers employed on a shift basis, which covers most facilities maintenance plumbers. Have the allowance written into the contract.</p>

<h3>Does the UAE Labour Law cover a job in a free zone?</h3>
<p>Generally not. Free zone employees are not governed by the UAE Labour Law, each zone has its own employment rules, and staff are sponsored by the free zone authority rather than by the employer.</p>

<h3>How much experience do Gulf employers want from a plumber?</h3>
<p>Typically two to six years. Hotel engineering and facilities management roles favour experience on large buildings, pumps, pressure systems and planned preventive maintenance over domestic repair work.</p>

<h2>People Also Search For</h2>

<h3>Plumber jobs in Dubai</h3>
<p>Recruited on MOHRE work permits, with every recruitment cost owed by the employer.</p>

<h3>Plumber salary in UAE</h3>
<p>No statutory floor exists; the contract is the only binding figure, enforced through the WPS.</p>

<h3>Saudi Professional Verification plumber</h3>
<p>A trade exam sat in your own country before the Saudi embassy issues the visa.</p>

<h3>MOHRE work permit</h3>
<p>Compulsory before employment, and the employer's cost under Article 6.</p>

<h3>Wage Protection System UAE</h3>
<p>Ministerial Resolution No. 340 of 2026, with an enforcement ladder starting on day five.</p>

<h3>Free zone jobs UAE labour law</h3>
<p>Free zone staff sit outside the Labour Law and are sponsored by the zone authority.</p>

<h3>Recruitment fees illegal UAE</h3>
<p>Charging a worker recruitment or employment costs is prohibited, directly or indirectly.</p>

<h3>Gulf plumber visa process</h3>
<p>UAE: offer, MOHRE permit, entry permit, medical, Emirates ID. Saudi: exam first, then visa.</p>

<h2>More Job Guides</h2>

<p>These cover the trades and the Gulf rules in more detail:</p>

<ul>
    <li><a href="/blog/plumber-salary-in-the-uk-and-usa">Plumber Salary in the UK and USA</a> &mdash; what the trade pays where official statistics actually measure it.</li>
    <li><a href="/blog/how-to-become-a-licensed-plumber-in-canada-or-australia">How to Become a Licensed Plumber in Canada or Australia</a> &mdash; the certification route where the trade is licensed.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; the licensing route in a country where the trade is regulated.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the wider construction market and its sponsorship rules.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; what the lower-paid end of the Saudi market really offers.</li>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; another trade with a licence conversion step.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the recruitment process end to end.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; how sponsorship works in a country that does set a wage floor.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. UAE and Saudi labour and visa rules change; confirm the current position on u.ae, mohre.gov.ae and hrsd.gov.sa, or with a licensed adviser, before accepting an offer or paying anyone.</p>
HTML;
    }
}
