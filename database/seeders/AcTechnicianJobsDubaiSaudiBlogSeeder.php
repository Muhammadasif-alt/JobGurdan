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
 * "AC Technician Jobs in Dubai and Saudi Arabia" — the Gulf page of the
 * HVAC/R cluster. It covers what decides whether an AC technician can take a
 * Gulf job and keep their pay: recruitment costs, the Saudi skills exam,
 * the summer midday break and wage protection.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Every pay figure came from Indeed, Glassdoor, GulfTalent or
 *    SalaryExpert (AED 2,000-6,500, AED 13,770, SAR 2,000-4,000,
 *    SAR 10,300), plus "highest-paying area" medians by Dubai district.
 *    Aggregator pay data is not republished on this site, and neither
 *    country sets a minimum wage for expatriate workers.
 *
 * 2. Ten Indeed buttons, and the jobs.kerzner.com/.../4167522/ link this
 *    site already treats as a vacancy URL.
 *
 * 3. "There's no single nationwide trade licence" for Saudi Arabia. True of
 *    licences, but it left out the Professional Verification exam, which
 *    covers refrigeration and air conditioning and must be passed before
 *    the visa is issued.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class AcTechnicianJobsDubaiSaudiBlogSeeder extends Seeder
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
        $title = 'AC Technician Jobs in Dubai and Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Gulf AC technician pay is set by the contract alone, as neither country has an expatriate minimum wage. What matters: the employer pays recruitment costs, Saudi Arabia tests your trade before the visa, and summer rooftop work stops at midday.',
                'content' => $content,
                'featured_image' => 'blogs/ac-technician-jobs-dubai-saudi.jpg',
                'tags' => 'ac technician jobs dubai, ac technician jobs saudi arabia, hvac technician gulf, saudi professional verification, uae midday break, chiller technician jobs, recruitment fees illegal uae, gulf ac technician visa',
                'meta_title' => 'AC Technician Jobs in Dubai and Saudi Arabia: Visa, Rules',
                'meta_description' => 'AC technician jobs in Dubai and Saudi Arabia: who pays recruitment costs, the Saudi skills exam before the visa, summer midday breaks and wage protection.',
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
            ['name' => 'UAE Facilities Management, MEP & Hotel Engineering Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-ac-technician-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'AC Technician — UAE Facilities Management, MEP and Hotel Engineering',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Often shift-based; outdoor work stops from 12:30pm to 3pm between 15 June and 15 September',
                'language' => 'English',
                // The UAE sets no minimum wage for private-sector expatriate
                // workers, and this site does not republish aggregator pay.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'AC and HVAC technician roles with UAE facilities management, MEP and hotel engineering employers, recruited on MOHRE work permits.',
                'seo_keywords' => 'ac technician jobs dubai, hvac technician uae, chiller technician dubai, mohre work permit, gulf ac technician',
            ]
        );

        $saudiAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi HVAC Contractors, Facilities & Hospitality Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'saudi-ac-technician-aggregated']
        );

        $saudiLocation = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        Job::updateOrCreate(
            [
                'position' => 'AC and Refrigeration Technician — Saudi Arabia (Skills Exam Required Before Visa)',
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
                'meta_description' => 'AC and refrigeration technician roles in Saudi Arabia. The Professional Verification trade exam must be passed in your home country before the visa.',
                'seo_keywords' => 'ac technician jobs saudi arabia, refrigeration technician saudi, saudi professional verification ac, hvac jobs riyadh, gulf ac technician',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Facilities management companies, MEP contractors and hotel engineering departments across the UAE recruit AC technicians from overseas on MOHRE work permits.</p>

<h3>What the work involves</h3>
<ul>
    <li>Servicing split, ducted, package and VRF systems, and chillers on larger sites</li>
    <li>Planned preventive maintenance and reactive call-outs</li>
    <li>Refrigerant recovery, charging and leak testing</li>
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
<p>HVAC contractors, facilities management providers and hotel engineering teams across Saudi Arabia recruit AC and refrigeration technicians from overseas.</p>

<h3>The trade exam comes before the visa</h3>
<p>The <strong>Professional Verification</strong> programme run by the Ministry of Human Resources and Social Development requires workers in covered professions to pass a theoretical and practical assessment in their own country <strong>before the Saudi visa is issued</strong>. Refrigeration and air conditioning was among the first five trades tested when the scheme opened in Pakistan.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>A diploma or vocational certificate in refrigeration and air conditioning</li>
    <li>Several years of service experience, with chiller or VRF experience valued</li>
    <li>Basic English for work orders</li>
</ul>

<p><strong>Pay:</strong> Saudi Arabia sets no statutory minimum wage for expatriate workers. Get the basic wage, housing, transport and overtime stated separately in the contract.</p>

<p><strong>No genuine employer asks a worker to buy a visa.</strong> Visa, accreditation and labour rules are set by the Saudi authorities, not by JobGader; confirm them on hrsd.gov.sa before paying anyone or booking travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The Gulf runs on air conditioning, and the people who keep it running are recruited from abroad in large numbers. Most guides to these jobs quote a salary scraped from a job board and list a few hotel vacancies. The things that actually decide whether you get the job and keep your pay are written in law: who pays to recruit you, the trade test Saudi Arabia sets before it issues a visa, the hours you may not work in summer, and how your wage is protected.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/ac-technician-jobs-dubai-saudi-gauges.jpg"
         alt="An AC technician in a navy cap connecting refrigerant gauges to a rooftop unit, with the Dubai skyline and Riyadh's Kingdom Centre behind him and the UAE and Saudi flags"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Hires AC Technicians in the Gulf</h2>

<ul>
    <li><strong>Facilities management companies</strong> holding maintenance contracts for towers, malls, hospitals and airports. The largest employers of AC technicians, and usually on shift work.</li>
    <li><strong>MEP contractors</strong> installing systems on new buildings.</li>
    <li><strong>Hotel engineering departments</strong> at international chains and resort operators, covering chillers, air handling units and guest-room systems.</li>
    <li><strong>Manufacturers and their service arms</strong>, which hire technicians trained on their own split, VRF and chiller equipment.</li>
</ul>

<p>Apply on the employer's own careers system or through your country's official overseas employment channel. Job boards repost the same adverts many times, and vacancy links expire within weeks.</p>

<h2>What an AC Technician Actually Earns</h2>

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

<p><strong>Neither country sets a wage floor for a foreign AC technician.</strong> The averages circulating online come from job boards and salary-survey sites, and they disagree with each other by a factor of four or five because they measure different people: one counts adverts for split-unit servicing, another counts senior chiller specialists. We do not republish them. The number that binds is the one in your contract.</p>

<p>Make it specific. Ask for the basic wage, the housing allowance or company accommodation, transport, overtime rate, annual ticket and medical cover each stated separately and in figures. Chiller, VRF and manufacturer training strengthen your bargaining position more than the country does.</p>

<h2>The Rule That Matters Most in the UAE</h2>

<p>Article 6 of Federal Decree-Law No. 33 of 2021 makes a MOHRE work permit compulsory before anyone may be employed, and states that <strong>an employer may not charge the worker, or collect from the worker, recruitment and employment costs either directly or indirectly</strong>. The work permit, entry permit, medical test, Emirates ID and residence visa are all the employer's cost. A recruiter who asks you to repay "visa costs" from your salary is describing an unlawful arrangement.</p>

<h2>Saudi Arabia: the Exam Before the Visa</h2>

<p>The Ministry of Human Resources and Social Development runs the <strong>Professional Verification</strong> programme. Workers in covered professions must pass a theoretical and practical trade assessment, and the Saudi embassy requires the resulting certificate before it issues the work visa.</p>

<ul>
    <li><strong>AC technicians are covered.</strong> Refrigeration and air conditioning was among the first five trades tested when the programme opened its centres in Pakistan.</li>
    <li><strong>You sit it at home.</strong> The assessment is taken in the country you are recruited from, before you travel.</li>
    <li><strong>It verifies, it does not train.</strong> The test checks the skill you already have. It does not find you a job.</li>
</ul>

<p>Saudi labour rules also put the recruitment cost, the residence permit and work permit fees and their renewals on the employer. Never buy a visa.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/ac-technician-jobs-dubai-saudi-rooftop.jpg"
         alt="An AC technician in a white hard hat checking pressures on a rooftop condenser, with a refrigerant cylinder beside him and the Dubai and Riyadh skylines at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Summer Is the Busy Season, and the Rules Change</h2>

<p>Demand for AC work peaks in the hot months, which is also when both countries restrict outdoor work:</p>

<ul>
    <li><strong>UAE midday break.</strong> From 15 June to 15 September, work under direct sun in open areas stops from 12:30pm to 3pm. MOHRE fines employers AED 5,000 per worker found working during the break, up to AED 50,000.</li>
    <li><strong>Saudi Arabia.</strong> Work under direct sun is banned from noon to 3pm over the same summer period.</li>
</ul>

<p>Rooftop condenser and chiller-plant work falls squarely under these rules. An employer who schedules you on a roof at 1pm in July is breaking them.</p>

<h2>How Your Wage Is Protected in the UAE</h2>

<p>The Wage Protection System runs under <strong>Ministerial Resolution No. 340 of 2026</strong>. Wages fall due on the first day of each Gregorian month, and at least 85 per cent of a company's wages must be transferred on time. New work permits are suspended from day five of a delay, a labour dispute opens automatically on day sixteen, and a travel ban can be placed on the person in charge from day twenty-one.</p>

<h3>Two exceptions that catch AC technicians</h3>

<ul>
    <li><strong>Shift work removes the night premium.</strong> UAE overtime is the basic wage plus 25 per cent, or plus 50 per cent between 10pm and 4am, but u.ae states the rule does not apply to workers on shifts. Facilities AC technicians usually are, so get any allowance written into the contract.</li>
    <li><strong>Free zone employers sit outside the Labour Law.</strong> Free zone staff are generally governed by the zone's own rules and sponsored by the free zone authority.</li>
</ul>

<h2>What Employers Ask For</h2>

<ul>
    <li>A diploma or vocational certificate in refrigeration and air conditioning, such as an ITI or DAE-level trade qualification</li>
    <li>Two to five years of service experience, with GCC experience preferred but not always required</li>
    <li>Hands-on work with split, ducted and package units; chiller and VRF/VRV experience for better-paid roles</li>
    <li>Manufacturer training on Daikin, Carrier, LG or similar systems, which raises both pay and prospects</li>
    <li>Basic English for work orders and safety briefings</li>
</ul>

<h2>How to Apply Without Losing Money</h2>

<ol>
    <li><strong>For Saudi Arabia, book the Professional Verification test early.</strong> No certificate, no visa.</li>
    <li><strong>Apply through the employer's careers system</strong> or your country's official overseas employment channel.</li>
    <li><strong>Never pay a recruitment, visa or placement fee.</strong> In the UAE it is unlawful; everywhere it is the clearest sign of a scam.</li>
    <li><strong>Never share an OTP</strong> with anyone offering a job.</li>
    <li><strong>Get every allowance in writing</strong> before you resign from your current job.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much does an AC technician earn in Dubai?</h3>
<p>There is no official figure and no minimum wage for expatriate workers. Online averages come from job boards and salary sites that disagree by a factor of four or five. The contract is the only binding number, so get each allowance stated separately.</p>

<h3>Do I need a skills test for an AC technician job in Saudi Arabia?</h3>
<p>Yes. Refrigeration and air conditioning is covered by the Professional Verification programme, and the theoretical and practical test must be passed in your own country before the Saudi visa is issued.</p>

<h3>Who pays for a UAE work visa?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs, directly or indirectly.</p>

<h3>Can AC technicians work on rooftops at midday in summer?</h3>
<p>No. In the UAE, outdoor work under direct sun stops from 12:30pm to 3pm between 15 June and 15 September. Saudi Arabia bans it from noon to 3pm over the same period.</p>

<h3>Is AC technician demand seasonal in the Gulf?</h3>
<p>Yes. Breakdowns and call-outs peak in the hottest months, so maintenance contractors staff up in spring. That makes late winter and early spring a good time to apply.</p>

<h3>Do Gulf AC technicians get night overtime?</h3>
<p>Not automatically. The 50 per cent rate for work between 10pm and 4am does not apply to workers on shifts, which covers most facilities technicians. Have it written into the contract.</p>

<h3>Which qualifications help most?</h3>
<p>A trade diploma in refrigeration and air conditioning, chiller and VRF experience, and manufacturer training. These matter more than whether the job is in Dubai or Riyadh.</p>

<h3>Which pays more, Dubai or Saudi Arabia?</h3>
<p>Neither country publishes official pay data for the trade. Compare offers on the full package: basic wage, housing, transport, overtime, ticket and medical cover.</p>

<h2>People Also Search For</h2>

<h3>AC technician jobs in Dubai</h3>
<p>Recruited on MOHRE work permits, with every recruitment cost owed by the employer.</p>

<h3>HVAC jobs in Saudi Arabia</h3>
<p>A Professional Verification test at home comes before the visa.</p>

<h3>Chiller technician jobs</h3>
<p>The better-paid end of Gulf AC work, usually with facilities or hotel teams.</p>

<h3>UAE midday break 2026</h3>
<p>12:30pm to 3pm, from 15 June to 15 September.</p>

<h3>Saudi skill verification AC technician</h3>
<p>Refrigeration and AC was among the first five trades tested in Pakistan.</p>

<h3>Wage Protection System UAE</h3>
<p>Ministerial Resolution No. 340 of 2026, with enforcement from day five.</p>

<h3>Recruitment fees illegal UAE</h3>
<p>Charging a worker recruitment or employment costs is prohibited.</p>

<h3>AC technician salary in Saudi Arabia</h3>
<p>No expatriate minimum wage; the registered contract is the binding figure.</p>

<h2>More Job Guides</h2>

<p>The rest of the HVAC/R cluster and the Gulf trades:</p>

<ul>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; demand and official pay data on both sides of the border.</li>
    <li><a href="/blog/how-to-start-a-career-in-refrigeration-and-air-conditioning">How to Start a Career in Refrigeration and Air Conditioning</a> &mdash; training, certification and the industrial route.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; the same Gulf rules for another facilities trade.</li>
    <li><a href="/blog/electrician-salary-in-the-uae-uk-and-usa">Electrician Salary in the UAE, UK and USA</a> &mdash; what the UAE pay rules mean for a neighbouring trade.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the wider Saudi construction market.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the recruitment process end to end.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. UAE and Saudi labour and visa rules change; confirm the current position on u.ae, mohre.gov.ae and hrsd.gov.sa before accepting an offer or paying anyone.</p>
HTML;
    }
}
