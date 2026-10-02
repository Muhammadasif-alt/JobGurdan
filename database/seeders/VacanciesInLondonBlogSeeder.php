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
 * "Vacancies in London" — the general London vacancy guide, written for
 * someone who already has the right to work here. The American guide owns
 * the visa question and this one deliberately does not repeat it; it owns
 * the market numbers, the contract types, the statutory pay floors and the
 * official places the vacancies are actually published.
 *
 * Corrections to the draft:
 *
 * 1. The draft carries no numbers at all. UK vacancies were 702,000 in the
 *    June to August 2026 ONS early estimate, the lowest since February to
 *    April 2021, with 2.5 unemployed people per vacancy. A guide that says
 *    "job availability changes frequently" and stops is not useful.
 *
 * 2. It presents London as a market of abundance. London had the highest
 *    unemployment rate of any UK region at 6.8 percent in May to July 2026,
 *    against 4.9 percent for the UK, and an employment rate below the UK
 *    average. That is the single most important thing a London jobseeker
 *    should know before planning a search.
 *
 * 3. It lists "salary" as something to check without naming any floor. The
 *    National Living Wage is GBP 12.71 an hour from 1 April 2026, with
 *    separate 18-to-20, 16-to-17 and apprentice rates, and the London
 *    Living Wage is a voluntary GBP 14.80.
 *
 * 4. It treats full-time, part-time, temporary and agency work as a list of
 *    options with no legal difference between them. Agency workers get equal
 *    treatment on pay only after 12 weeks with the same hirer under the
 *    Agency Workers Regulations 2010, and every worker gets 5.6 weeks of
 *    paid holiday whatever the pattern.
 *
 * 5. It tells international applicants to "check whether the vacancy can
 *    support the relevant immigration route" without saying that almost no
 *    London hospitality, retail or warehouse job can be sponsored, because
 *    the Skilled Worker threshold of GBP 41,700 sits far above what those
 *    jobs pay.
 *
 * 6. It never mentions the right-to-work check, which is the first thing a
 *    London employer does and the step most applications actually fail on.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class VacanciesInLondonBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.gov.uk/find-a-job';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'job-search'],
            [
                'name' => 'Job Search',
                'description' => 'Practical guides on finding, comparing and applying for advertised vacancies.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Vacancies in London';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'UK vacancies fell to 702,000 in the ONS June to August 2026 estimate and London now has the highest unemployment rate of any UK region at 6.8 per cent. What is really open in London, what the law says it must pay, and where the listings are published.',
                'content' => $content,
                'featured_image' => 'blogs/vacancies-in-london.jpg',
                'tags' => 'vacancies in london, jobs in london, london job vacancies, part time jobs in london, full time jobs london, entry level jobs london, london minimum wage 2026, london jobs with visa sponsorship',
                'meta_title' => 'Vacancies in London 2026: What Is Open and What It Pays',
                'meta_description' => 'Vacancies in London: UK vacancies down to 702,000, London unemployment at 6.8 per cent, the GBP 12.71 pay floor and where the listings are published.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );

        $this->seedJob();
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'London Employers — Boroughs, NHS Trusts, Universities & Private Sector (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'london-vacancies-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Vacancies in London — Full-Time, Part-Time and Entry-Level Roles Across the 32 Boroughs',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, part-time, fixed-term and agency patterns; 5.6 weeks paid holiday is a statutory minimum on all of them',
                'language' => 'English',
                // Pay is set job by job across every London sector, so no
                // single range stands for the group. The statutory floor is
                // quoted in the description instead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Full-time, part-time and entry-level vacancies across London boroughs, NHS trusts, universities and private employers, published on official services.',
                'seo_keywords' => 'vacancies in london, jobs in london, part time jobs london, full time jobs london, entry level jobs london',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>London boroughs, NHS trusts, universities, Transport for London and private employers recruit continuously across full-time, part-time, fixed-term and agency patterns. Vacancies are published on each employer's own system first and on the government's Find a Job service.</p>

<h3>Where the work is</h3>
<p>Administration, customer service, healthcare and healthcare support, education and school support, retail, hospitality, logistics and warehousing, technology, finance, construction and local government services.</p>

<h3>What the law sets</h3>
<ul>
    <li>National Living Wage &pound;12.71 an hour for workers aged 21 and over from 1 April 2026; &pound;10.85 for 18 to 20 year olds; &pound;8.00 for 16 to 17 year olds and apprentices</li>
    <li>The London Living Wage of &pound;14.80 is voluntary and paid only by accredited employers</li>
    <li>5.6 weeks of paid holiday a year, which is 28 days for a five-day week</li>
    <li>Agency workers get equal treatment on pay after 12 weeks in the same role with the same hirer</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Have your right-to-work evidence ready.</strong> Most applicants prove it with a share code generated on GOV.UK, which is valid for 90 days. British and Irish citizens can use a passport.</p>

<p><strong>Note:</strong> London is the UK region with the highest unemployment rate, at 6.8 per cent in May to July 2026 against 4.9 per cent for the UK. Apply early; London vacancies often close before the advertised deadline. Never pay an agency to be put forward for work. Wage rates, holiday entitlement and labour market statistics are set by law and published by the ONS, not by JobGader; confirm the current figures on gov.uk and ons.gov.uk.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>London advertises more vacancies than anywhere else in the UK and is also the hardest region in the country to find work in. Both of those things are true at once, and a guide that only tells you the first one will waste your time. This page gives you the market as the Office for National Statistics measures it, the pay floors the law actually sets, the difference between the four contract types you will be offered, and the places London vacancies are published before they reach any job board.</p>

<h2>The London Market in Numbers</h2>

<p>Two official datasets matter and they say different things. The ONS Vacancy Survey counts advertised jobs across the UK. The Labour Force Survey measures how many people are chasing them, region by region.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Measure</th>
            <th style="padding:10px;text-align:left;">London</th>
            <th style="padding:10px;text-align:left;">UK</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Unemployment rate</strong> (May to July 2026)</td><td style="padding:10px;">6.8%</td><td style="padding:10px;">4.9%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Employment rate</strong> (May to July 2026)</td><td style="padding:10px;">73.9%</td><td style="padding:10px;">75.1%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Economic inactivity rate</strong></td><td style="padding:10px;">20.5%</td><td style="padding:10px;">20.9%</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Vacancies advertised</strong> (June to August 2026)</td><td style="padding:10px;">Not published separately</td><td style="padding:10px;">702,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Unemployed people per vacancy</strong></td><td style="padding:10px;">&mdash;</td><td style="padding:10px;">2.5</td></tr>
    </tbody>
</table>
</div>

<p><strong>London has the highest unemployment rate of any UK region.</strong> That is not a detail; it is the context for every application you send. At the same time UK vacancies fell by 8,000 on the quarter to 702,000 in the ONS early estimate for June to August 2026, the lowest reading since February to April 2021. On the year vacancies are down 36,000, or 4.9 per cent, and they sit 86,000 below where they were before the pandemic in January to March 2020.</p>

<p>The ONS does not publish a separate vacancy count for London, so treat any site quoting one as an estimate from its own listings rather than an official figure. What the ONS does publish by industry is where the falls are concentrated: human health and social work activities lost the most vacancies over the year, down 9,000 or 6.6 per cent, with professional, scientific and technical activities and accommodation and food service each down 8,000.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/vacancies-in-london-commuter.jpg"
         alt="A commuter in a suit holding a coffee on Westminster Bridge at sunrise, with the Houses of Parliament, Big Ben, the London Eye and red buses behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Full-Time, Part-Time, Fixed-Term or Agency: What Actually Changes</h2>

<p>London adverts use these four words loosely. Legally they are not interchangeable, and the difference decides your pay, your notice and your holiday.</p>

<ul>
    <li><strong>Permanent full-time.</strong> No end date. Unfair dismissal protection builds up with service, and redundancy pay becomes available after two years.</li>
    <li><strong>Part-time.</strong> Identical rights to a full-timer, pro-rated. A part-timer may not lawfully be paid a lower hourly rate, given a worse pension or denied training simply for working fewer hours.</li>
    <li><strong>Fixed-term.</strong> An end date or an end event. Fixed-term employees must not be treated less favourably than comparable permanent staff, and four years of continuous fixed-term service can convert the contract to permanent.</li>
    <li><strong>Agency.</strong> You are paid by the agency, not the hirer. Under the Agency Workers Regulations 2010 you get equal treatment on pay and basic conditions only after <strong>12 weeks in the same role with the same hirer</strong> &mdash; and the 12-week clock can be broken.</li>
</ul>

<p>Whatever the pattern, <strong>5.6 weeks of paid holiday is a statutory minimum</strong>, which is 28 days for someone working five days a week and can include bank holidays. A London advert offering "20 days plus bank holidays" is at the floor, not being generous.</p>

<h2>What London Vacancies Must Pay</h2>

<p>Three numbers are routinely confused in London job adverts. Only one of them is enforceable.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Rate</th>
            <th style="padding:10px;text-align:left;">From 1 April 2026</th>
            <th style="padding:10px;text-align:left;">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">National Living Wage, 21 and over</td><td style="padding:10px;">£12.71</td><td style="padding:10px;">Legal minimum</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">18 to 20</td><td style="padding:10px;">£10.85</td><td style="padding:10px;">Legal minimum</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">16 to 17, and apprentices</td><td style="padding:10px;">£8.00</td><td style="padding:10px;">Legal minimum</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">London Living Wage</td><td style="padding:10px;">£14.80</td><td style="padding:10px;"><strong>Voluntary.</strong> Accredited employers only</td></tr>
    </tbody>
</table>
</div>

<p>There is no London weighting in the minimum wage. An employer in Croydon and an employer in Carlisle owe the same £12.71. The London Living Wage is a Living Wage Foundation figure that employers choose to sign up to, so "London Living Wage employer" in an advert is worth something and "competitive London salary" is worth nothing until you see a number.</p>

<p>A full-time job at the London Living Wage comes to roughly £28,900 a year. Hold that figure in mind for the sponsorship section below.</p>

<h2>The Right-to-Work Check Comes First</h2>

<p>Before a London employer can put you on the payroll it must carry out a right-to-work check, and this is where a large share of applications quietly end.</p>

<ul>
    <li><strong>If you hold a biometric residence permit, eVisa or settled status</strong>, you prove your right to work with a <strong>share code</strong> generated on GOV.UK. The employer enters it with your date of birth. The code is valid for 90 days.</li>
    <li><strong>If you are a British or Irish citizen</strong>, a passport is enough, and an expired British passport is still acceptable for this check.</li>
    <li><strong>If you need sponsorship</strong>, the employer must hold a sponsor licence <em>and</em> the job itself must be an eligible occupation. Both tests, separately.</li>
</ul>

<p>Get your share code before you start applying rather than after an offer. A London employer filling a vacancy this month will move to the next candidate rather than wait.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/vacancies-in-london-jobseeker.jpg"
         alt="A jobseeker carrying a laptop and bag on the Thames embankment at sunset, with Westminster Bridge, Big Ben and the London skyline behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which London Vacancies Can Be Sponsored, and Which Cannot</h2>

<p>This is the question that wastes the most time for overseas applicants, and the arithmetic settles it quickly. The Skilled Worker salary threshold is <strong>£41,700 a year, or the going rate for the occupation code, whichever is higher</strong>. A full-time London Living Wage job pays about £28,900.</p>

<p>So London hospitality, retail, warehouse, cleaning, security, care support and most entry-level administrative vacancies <strong>cannot be sponsored at all</strong>, however many of them are advertised and however urgently they are being recruited. The routes that bring people into London work are graduate and professional ones. If an advert for a London warehouse role promises visa sponsorship, that is a reason to walk away, not to apply.</p>

<h2>Where London Vacancies Are Actually Published</h2>

<p>Large London employers advertise on their own systems first and syndicate afterwards, so the listings you reach through an official portal are both earlier and more complete.</p>

<ul>
    <li><strong>Find a Job</strong> &mdash; the government's own service, where employers post directly: <a href="https://www.gov.uk/find-a-job" rel="noopener">gov.uk/find-a-job</a></li>
    <li><strong>NHS Jobs</strong> &mdash; every NHS trust in London, clinical and non-clinical: <a href="https://www.jobs.nhs.uk" rel="noopener">jobs.nhs.uk</a></li>
    <li><strong>Civil Service Jobs</strong> &mdash; departments and agencies, many of them London-based: <a href="https://www.civilservicejobs.service.gov.uk" rel="noopener">civilservicejobs.service.gov.uk</a></li>
    <li><strong>Transport for London careers</strong> &mdash; one of the largest single employers in the capital: <a href="https://tfl.gov.uk/corporate/careers/" rel="noopener">tfl.gov.uk/corporate/careers</a></li>
    <li><strong>Borough council career pages</strong> &mdash; 32 boroughs plus the City of London, each recruiting separately for schools, social care, planning, libraries and environmental services.</li>
    <li><strong>University and college HR pages</strong> &mdash; academic and professional services posts, usually advertised on the institution's own site well before anywhere else.</li>
</ul>

<p>Searching by borough rather than by "London" narrows a list of thousands into something you can actually commute to. Zone 1 vacancies attract the most applicants; the same job title in Barking, Croydon or Harrow will have a fraction of the competition.</p>

<h2>Making an Application That Gets Read</h2>

<ol>
    <li><strong>Mirror the person specification.</strong> Public sector London employers &mdash; councils, the NHS, universities &mdash; shortlist against the essential criteria mechanically. Answer each one in order, with evidence.</li>
    <li><strong>Use the employer's form.</strong> Many London public bodies will not accept a CV at all. An attached CV where a form was asked for is usually scored as incomplete.</li>
    <li><strong>Put the right-to-work answer in writing.</strong> If you need no sponsorship, say so plainly. It removes a recruiter's first objection.</li>
    <li><strong>Check the commute before you apply, not after.</strong> Work out the real door-to-door time and the monthly travel cost for the zones involved, then compare it with the advertised salary.</li>
    <li><strong>Apply in the first week.</strong> London vacancies routinely close early when application volume is high, and the advertised deadline is not a promise.</li>
    <li><strong>Never pay to be put forward.</strong> A genuine recruitment agency is paid by the employer. An agency asking you for a registration, training or placement fee is not one.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How many vacancies are there in London right now?</h3>
<p>The ONS does not publish a separate vacancy count for London. The UK-wide figure was 702,000 in the June to August 2026 early estimate, the lowest since February to April 2021, with 2.5 unemployed people per vacancy.</p>

<h3>Is it hard to find a job in London?</h3>
<p>Harder than anywhere else in the UK on the official measure. London's unemployment rate was 6.8 per cent in May to July 2026 against 4.9 per cent for the UK, the highest of any UK region, and its employment rate was below the UK average.</p>

<h3>What is the minimum wage for a London job in 2026?</h3>
<p>£12.71 an hour for workers aged 21 and over from 1 April 2026, £10.85 for 18 to 20 year olds, and £8.00 for 16 to 17 year olds and apprentices. There is no higher legal rate for London.</p>

<h3>What is the London Living Wage and is it compulsory?</h3>
<p>£14.80 an hour. It is set by the Living Wage Foundation and is entirely voluntary, so only accredited employers pay it.</p>

<h3>How much holiday does a London job have to give?</h3>
<p>5.6 weeks a year, which is 28 days for someone working five days a week. Bank holidays can be counted within that minimum. Part-time workers get the same entitlement pro-rated.</p>

<h3>Do agency workers in London get the same pay as permanent staff?</h3>
<p>Only after 12 weeks in the same role with the same hirer, under the Agency Workers Regulations 2010. Before that, equal treatment on pay does not apply.</p>

<h3>Can a London retail or hospitality vacancy sponsor a visa?</h3>
<p>Almost never. The Skilled Worker threshold is £41,700 or the occupation's going rate, and a full-time London Living Wage job pays around £28,900. An advert promising sponsorship for that kind of role should be treated as a scam.</p>

<h3>Where should I look for London vacancies first?</h3>
<p>The government's Find a Job service, NHS Jobs, Civil Service Jobs, Transport for London careers, the 32 borough council career pages and university HR pages. Large London employers publish on their own systems before anywhere else.</p>

<h2>People Also Search For</h2>

<h3>Jobs in London</h3>
<p>The same market, searched more broadly; narrowing by borough cuts the competition sharply.</p>

<h3>Part-time jobs in London</h3>
<p>Identical legal rights to full-time work, pro-rated, including the 5.6 week holiday minimum.</p>

<h3>Full-time jobs London</h3>
<p>Permanent full-time work carries unfair dismissal protection and redundancy rights with service.</p>

<h3>Entry-level jobs London</h3>
<p>Graduate, trainee and apprentice routes; the apprentice minimum is £8.00 an hour.</p>

<h3>Immediate start jobs London</h3>
<p>Usually agency work, where equal pay treatment only begins after 12 weeks with the same hirer.</p>

<h3>London minimum wage 2026</h3>
<p>£12.71 for 21 and over. There is no statutory London weighting.</p>

<h3>London jobs with visa sponsorship</h3>
<p>Graduate and professional roles only; the floor is £41,700 or the going rate.</p>

<h3>London unemployment rate</h3>
<p>6.8 per cent in May to July 2026, the highest rate of any UK region.</p>

<h2>More Job Guides</h2>

<p>These cover the questions that come next:</p>

<ul>
    <li><a href="/blog/jobs-in-london-england-for-american-applicants">Jobs in London England for American Applicants</a> &mdash; the routes, thresholds and tax rules that apply to a US passport holder.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; how to read the sponsor register and what a Certificate of Sponsorship actually is.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; every visa route compared, including the ones that closed.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London (No Experience Needed)</a> &mdash; what London entry-level work really pays.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why the warehouse sponsorship adverts do not add up.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; a medium-skilled code and what keeps it open.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; one occupation code worked through end to end.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; remote UK work and the right-to-work question it does not solve.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, employment or immigration advice. Labour market statistics, wage rates and immigration rules change; confirm the current figures on ons.gov.uk and gov.uk before relying on them.</p>
HTML;
    }
}
