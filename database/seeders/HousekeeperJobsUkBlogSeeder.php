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
 * "Housekeeper Jobs in UK" — hotel, hospital, care home and private household
 * housekeeping. The London cleaner guide covers office and contract cleaning,
 * the healthcare assistant guide covers NHS pay bands in full, and this one
 * owns housekeeper pay, the live-in accommodation rule, checks and visas.
 *
 * Corrections to the draft:
 *
 * 1. Three of its four salary floors are below the National Living Wage.
 *    £12.71 an hour for 37.5 hours a week is £24,784.50 a year, so £19,000,
 *    £20,000 and £22,000 are all short for a full-time worker aged 21 or over.
 *    The ONS median for full-time housekeepers (6231) was £25,335 in 2025.
 *
 * 2. It says live-in roles may include accommodation and meals as part of the
 *    pay. Only accommodation counts towards the minimum wage, capped at
 *    £11.10 a day (£77.70 a week) from April 2026. Meals never count.
 *
 * 3. It says hospital, care home and some private household roles need an
 *    enhanced DBS check. Hospital ward domestics get a standard check, care
 *    home domestics an enhanced check without the barred lists, and a private
 *    household cannot request a check at all. Scotland uses PVG.
 *
 * 4. It says nothing about visas, and its poster promised free flights and
 *    free accommodation. Housekeepers (6231), cleaners and domestics (9223)
 *    and their supervisors (6240) are all in Table 6 of Appendix Skilled
 *    Occupations, and the Overseas Domestic Worker visa only covers staff
 *    already employed abroad by the household, for up to 6 months.
 *
 * 5. It omits the NHS banding (domestics are Band 2 Support Workers, Band 1
 *    closed to new starters in England in December 2018), the tips law hotel
 *    housekeepers are covered by, and day-one Statutory Sick Pay since
 *    6 April 2026.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HousekeeperJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-housekeeper-jobs.html';

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
        $title = 'Housekeeper Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A £19,000 full-time housekeeper salary is below the legal minimum, meals never count towards it, NHS domestics start on Band 2 at £25,272, and housekeepers cannot be sponsored for a UK work visa.',
                'content' => $content,
                'featured_image' => 'blogs/housekeeper-jobs-in-uk.jpg',
                'tags' => 'housekeeper jobs uk, hotel housekeeping jobs, live-in housekeeper jobs uk, private housekeeper jobs, nhs domestic assistant jobs, care home housekeeper jobs, room attendant jobs, housekeeper salary uk, housekeeper visa sponsorship uk',
                'meta_title' => 'Housekeeper Jobs in UK 2026: Pay, Live-in Rules and Visas',
                'meta_description' => 'Housekeeper jobs in the UK: why GBP 19,000 full-time is below the legal minimum, the live-in pay rule, NHS Band 2 pay, and why visas are not sponsored.',
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
            ['name' => 'UK Hotels, NHS Trusts, Care Homes & Private Households (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-housekeeper-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'cleaning-facilities'],
            ['name' => 'Cleaning & Facilities']
        );

        Job::updateOrCreate(
            [
                'position' => 'Housekeeper — Hotels, Hospitals, Care Homes and Private Households, UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Early, day and weekend shifts in hotels and hospitals; set hours or live-in arrangements in private households',
                'language' => 'English',
                // Hotel, NHS and private household pay sit on different scales,
                // and live-in offers depend on the accommodation offset, so no
                // single range holds.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Housekeeper, room attendant and domestic roles in UK hotels, NHS hospitals, care homes and private homes. Right to work in the UK required.',
                'seo_keywords' => 'housekeeper jobs uk, hotel housekeeping jobs, live-in housekeeper jobs, nhs domestic jobs, care home housekeeper jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hotels, NHS hospitals, care homes, holiday lets and private households across the UK hire housekeepers, room attendants and domestic staff all year. Most entry-level roles need no qualifications, and employers train new starters on the job.</p>

<h3>What the work involves</h3>
<p>Cleaning and preparing rooms, changing linen, restocking supplies, cleaning public areas and reporting maintenance faults. Hospital and care home housekeepers work to infection control schedules, and private housekeepers may also do laundry and household management.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>The right to work in the UK</strong> &mdash; housekeepers and cleaners cannot be sponsored for a Skilled Worker visa</li>
    <li>A criminal record check where the role is eligible: standard or enhanced DBS for hospitals and care homes, PVG in Scotland, AccessNI in Northern Ireland</li>
    <li>Safe use of cleaning chemicals under COSHH, and safe manual handling</li>
    <li>Availability for early, weekend and bank holiday shifts in hotels and hospitals</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The legal floor.</strong> The National Living Wage is &pound;12.71 an hour from 1 April 2026, which is &pound;24,784.50 a year on a 37.5-hour week for a worker aged 21 or over</li>
    <li><strong>NHS domestics.</strong> Band 2 in England pays &pound;25,272 in 2026/27</li>
    <li><strong>Live-in roles.</strong> Accommodation can count towards the minimum wage up to &pound;77.70 a week; meals cannot</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Get the weekly hours in writing.</strong> Divide the salary by the hours to check it against the National Living Wage, especially for live-in roles.</p>

<p><strong>Note:</strong> pay, hours, checks and visa eligibility are set by each employer, NHS pay agreements and UK law &mdash; not by JobGader. Confirm the details with the employer and on gov.uk before applying, and never pay a fee for a job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Housekeepers keep UK hotels, hospitals, care homes and private homes running, and it is one of the few jobs you can start with no qualifications. Before you apply, it helps to know the four things most guides get wrong: what a housekeeper must legally be paid, what a live-in job can take off your pay, which background check you really need, and whether anyone can sponsor you from overseas.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-housekeeper-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129529; Browse Housekeeper Jobs in UK &rarr;
    </a>
</div>

<h2>Three of the Four Salary Floors Guides Quote Are Below the Legal Minimum</h2>

<p>Guides give UK housekeeper pay in four bands:</p>

<ul>
    <li><strong>Entry-level hotel or care home housekeeper:</strong> &pound;19,000 to &pound;22,000 a year</li>
    <li><strong>Experienced housekeeper:</strong> &pound;22,000 to &pound;26,000</li>
    <li><strong>Private or domestic housekeeper:</strong> &pound;20,000 to &pound;30,000 or more</li>
    <li><strong>Head housekeeper or supervisor:</strong> &pound;26,000 to &pound;32,000 or more</li>
</ul>

<p>The <strong>National Living Wage</strong>, the legal minimum for workers aged 21 and over, is <strong>&pound;12.71 an hour</strong> from 1 April 2026. On a 37.5-hour week over 52 weeks, that is <strong>&pound;24,784.50</strong> a year. Against that:</p>

<ul>
    <li>The entry-level floor of &pound;19,000 is <strong>&pound;5,784.50 short</strong>. It is below even the <strong>18 to 20 rate of &pound;10.85</strong>, which comes to &pound;21,157.50 a year full-time.</li>
    <li>The private housekeeper floor of &pound;20,000 is <strong>&pound;4,784.50 short</strong>.</li>
    <li>The experienced housekeeper floor of &pound;22,000 is <strong>&pound;2,784.50 short</strong>.</li>
    <li>The head housekeeper floor of &pound;26,000 clears a 37.5-hour week, but not a 40-hour one, where the minimum is <strong>&pound;26,436.80</strong>.</li>
</ul>

<p>Those published figures only work for a worker under 21, an apprentice on the &pound;8 apprentice rate, or someone on fewer hours. So <strong>ask for the weekly hours in writing</strong> and divide the salary by them before you accept.</p>

<p>What housekeepers actually earn is higher than guides suggest. The ONS Annual Survey of Hours and Earnings measured these medians for full-time employees in April 2025, a year before the latest minimum wage rise:</p>

<ul>
    <li><strong>Housekeepers and related occupations:</strong> <strong>&pound;25,335</strong> a year, or &pound;13.07 an hour</li>
    <li><strong>Cleaners and domestics:</strong> &pound;24,130, or &pound;12.89 an hour</li>
    <li><strong>Cleaning and housekeeping managers and supervisors:</strong> &pound;28,605, or &pound;14.50 an hour</li>
</ul>

<p>Some employers go further and pay the voluntary <strong>Real Living Wage</strong> set by the Living Wage Foundation: <strong>&pound;13.45 an hour</strong> across the UK and <strong>&pound;14.80 in London</strong>. The next rates are due in October 2026.</p>

<h2>Live-in Jobs: Meals Never Count, and Accommodation Only Up to &pound;77.70 a Week</h2>

<p>Guides say live-in roles "may include accommodation and meals as part of the overall compensation package". The law is narrower than that:</p>

<ul>
    <li><strong>Accommodation</strong> is the only benefit that can count towards minimum wage pay. From April 2026 the <strong>accommodation offset</strong> is <strong>&pound;11.10 a day</strong>, or <strong>&pound;77.70 a week</strong>. If an employer charges more than that for your room, the extra reduces your pay for minimum wage purposes.</li>
    <li><strong>Meals do not count at all.</strong> GOV.UK says no other kind of benefit, such as food, counts towards the minimum wage.</li>
    <li><strong>Treated as one of the family is not an exemption.</strong> Only members of the employer's own family who live in the family home and share in its tasks and activities are outside the minimum wage. Since April 2024, live-in workers from outside the family, including au pairs, are entitled to it.</li>
</ul>

<p>Here is what that means in cash. A live-in housekeeper aged 21 or over working 37.5 hours a week must be paid at least &pound;476.63 a week. With free accommodation counted at the full offset, the lowest lawful cash pay is <strong>&pound;398.93 a week</strong>, or <strong>&pound;20,744.10 a year</strong>. A live-in offer of &pound;20,000 for those hours is still short, and every extra hour you work raises the minimum. Live-in hours blur easily, so agree them in writing before you move in.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/housekeeper-jobs-in-uk-private-home.jpg"
         alt="A housekeeper in a navy uniform polishing a marble table in a London apartment overlooking the Thames"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>NHS Housekeepers and Domestics Start at Band 2</h2>

<p>Hospital housekeeping is paid on the NHS Agenda for Change scale, not on hotel rates. NHS Employers matches cleaning and domestic jobs to its <strong>Support Services profiles</strong>: a <strong>Support Worker</strong> providing "support, security, cleaning or catering services" is <strong>Band 2</strong>, a Support Service Supervisor is Band 3, a higher level supervisor Band 4 and a team manager Band 5. <strong>Band 1 closed to new starters in England on 1 December 2018</strong>.</p>

<p>After the 3.3% pay award from 1 April 2026, the rates are:</p>

<ul>
    <li><strong>England:</strong> Band 2 <strong>&pound;25,272</strong>; Band 3 &pound;25,760 to &pound;27,476.</li>
    <li><strong>Scotland:</strong> Band 2 &pound;26,696, then &pound;28,988, on a 36-hour week.</li>
    <li><strong>Wales:</strong> Band 2 &pound;26,300.</li>
    <li><strong>Northern Ireland:</strong> the latest confirmed rates are 2025/26, with Band 2 at &pound;24,465.</li>
</ul>

<p>Weekend and night shifts pay more. In England, Wales and Northern Ireland, Band 2 staff get <strong>41% extra</strong> for Saturdays and nights and <strong>83% extra</strong> for Sundays and bank holidays. Our <a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> guide sets out the bands and enhancements in full.</p>

<p>Hospital cleaning is done to the <strong>National Standards of Healthcare Cleanliness 2025</strong>, published by NHS England on 5 February 2025 to replace the 2021 standards. They set cleaning frequencies by risk category, regular audits and cleanliness star ratings on display, so expect to follow a schedule and sign off your work.</p>

<h2>The Background Check Depends on Where You Work</h2>

<p>Guides say hospital, care home and some private household roles need an <strong>enhanced DBS check</strong>. The official DBS guidance for England and Wales is more specific:</p>

<ul>
    <li><strong>Care homes:</strong> cleaning and other auxiliary staff can get an <strong>enhanced check without the barred lists</strong> if they work there more than 3 days in 30, overnight between 2am and 6am, or once a week or more. Staff who work there less often get a standard check. Barred list checks are for regulated activity such as personal care.</li>
    <li><strong>Hospitals:</strong> a domestic working on wards with patient contact gets a <strong>standard check</strong>. In a children's hospital it is an enhanced check without the barred lists, and staff working only in offices or public areas get a basic check.</li>
    <li><strong>Hotels:</strong> hotel housekeeping is not a role eligible for a standard or enhanced check, so a <strong>basic check</strong> is the most an employer can ask for.</li>
    <li><strong>Private households:</strong> a household <strong>cannot request a DBS check</strong> for someone it employs. You apply for a basic check yourself. A home help who cleans for an adult who needs it because of age, illness or disability can be eligible for an enhanced check without the barred lists.</li>
</ul>

<p>The other nations run their own schemes. In <strong>Scotland</strong>, domestic work in a hospital or care home is a regulated role, so you must join the <strong>Protecting Vulnerable Groups (PVG) scheme</strong>. In <strong>Northern Ireland</strong>, <strong>AccessNI</strong> provides basic, standard and enhanced checks, and only an employer can apply for the last two.</p>

<h2>Hotel Housekeeping: Tips, Sick Pay and Holiday</h2>

<ul>
    <li><strong>Tips and service charges.</strong> The Employment (Allocation of Tips) Act 2023 came into force on <strong>1 October 2024</strong>. Where the employer handles tips, it must pass them all on without deductions other than tax, share them fairly under a written policy, pay them by the end of the month after they were received and keep records for three years. A duty to consult staff on the policy is expected in late 2026 but is not yet in force.</li>
    <li><strong>Sick pay from day one.</strong> Since <strong>6 April 2026</strong>, Statutory Sick Pay has no waiting days and no lower earnings limit, which matters for part-time housekeepers who were previously excluded. It pays &pound;123.25 a week, or 80% of your average weekly earnings if that is lower.</li>
    <li><strong>Holiday.</strong> Every worker is entitled to <strong>5.6 weeks</strong> of paid holiday a year, capped at 28 days, and part-timers get the same number of weeks.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/housekeeper-jobs-in-uk-hotel.jpg"
         alt="A hotel housekeeper making a bed in a London hotel room with Big Ben and the Thames outside the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Housekeepers Cannot Be Sponsored for a UK Work Visa</h2>

<p>Recruitment posters for UK housekeeping jobs promise free flights, free accommodation and visa support. The Immigration Rules say otherwise, and the answer turns on the occupation code:</p>

<ul>
    <li><strong>6231 Housekeepers and related occupations</strong> is listed in <strong>Table 6</strong> of Appendix Skilled Occupations, the codes that are <strong>not eligible</strong> for the Skilled Worker route at all.</li>
    <li><strong>6240 Cleaning and housekeeping managers and supervisors</strong>, whose example job titles include hotel housekeeper and hotel services supervisor, is in Table 6 too, so a head housekeeper job cannot be sponsored either.</li>
    <li><strong>9223 Cleaners and domestics</strong> is also in Table 6.</li>
    <li><strong>1221 Hotel and accommodation managers</strong> can only be sponsored for workers who already held Skilled Worker permission before <strong>22 July 2025</strong>, when the skill threshold returned to degree level (RQF 6).</li>
    <li>None of these codes is on the <strong>Temporary Shortage List</strong> or the Immigration Salary List.</li>
</ul>

<p>The <strong>Overseas Domestic Worker visa</strong> is not a way in either. It is only for a domestic worker in a private household who has already worked for that employer for <strong>at least a year</strong> and is coming to the UK with them. It lasts up to <strong>6 months</strong>, cannot be extended, costs <strong>&pound;726</strong> and does not allow dependants. You can move to another private household job, but only within the same 6 months.</p>

<p>The <strong>Seasonal Worker visa</strong> covers horticulture and poultry work only, not hotels. The routes that do allow housekeeping work are narrow: the <strong>Youth Mobility Scheme</strong> lets young nationals of a short list of countries and territories, including Australia, Canada, New Zealand and Japan, work in most jobs for up to 24 months, and Indian nationals aged 18 to 30 can enter the ballot for the <strong>India Young Professionals Scheme</strong>. Pakistan is not on either list. If you already hold the right to work in the UK, you can apply for any housekeeping job like any other candidate.</p>

<p>Anyone charging you for a sponsored UK housekeeping job, a flight or a work permit is selling something the rules do not allow. Never pay a fee for a job offer.</p>

<h2>Is Demand Really That High?</h2>

<p>Guides describe housekeeping demand as strong and rising. The official figures are more mixed:</p>

<ul>
    <li><strong>Hospitality vacancies are well below their peak.</strong> The ONS counted <strong>71,000</strong> vacancies in accommodation and food services in <strong>May to July 2026</strong>, against a record <strong>177,000</strong> in April to June 2022 and 93,000 in the same months of 2019.</li>
    <li><strong>Visitors have come back.</strong> ONS travel figures put overseas visits to the UK in 2024 above their 2019 level of 40.9 million, which keeps hotels and holiday lets busy.</li>
    <li><strong>Hospitals and care homes hire all year</strong>, because cleaning is a fixed part of how they run rather than a seasonal service.</li>
</ul>

<p>No official source ranks UK regions for housekeeper hiring, so treat lists of top regions with care. The one regional difference the figures do show is pay: the voluntary Real Living Wage is &pound;1.35 an hour higher in London than in the rest of the UK.</p>

<h2>The Main Types of Housekeeping Job</h2>

<ul>
    <li><strong>Hotel housekeeper or room attendant.</strong> Cleaning and turning over guest rooms and public areas, usually on early and weekend shifts.</li>
    <li><strong>Private or domestic housekeeper.</strong> Cleaning, laundry and household management for a family, live-in or live-out.</li>
    <li><strong>Hospital domestic or ward housekeeper.</strong> NHS Band 2 work to the national cleanliness standards.</li>
    <li><strong>Care home housekeeper.</strong> Cleaning residents' rooms and shared areas, usually with an enhanced check.</li>
    <li><strong>Holiday let and short-let cleaner.</strong> Often self-employed and paid per clean. The minimum wage does not apply to people genuinely running their own business, so price your time carefully.</li>
    <li><strong>Head housekeeper or supervisor.</strong> Running a team, rotas and stock, with a full-time median of &pound;28,605 in the ONS survey.</li>
</ul>

<h2>Safety Rules Housekeepers Work Under</h2>

<ul>
    <li><strong>Cleaning chemicals.</strong> The Control of Substances Hazardous to Health (COSHH) Regulations 2002 cover how you use them, and the HSE warns that some cleaning products are corrosive. Employers must train you and provide protective equipment where needed.</li>
    <li><strong>Lifting and bending.</strong> Employers must follow the Manual Handling Operations Regulations 1992. Muscle and joint strain is the most common work-related ill health cleaners report to the HSE, so ask how beds, linen trolleys and heavy loads are handled.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do housekeepers earn in the UK in 2026?</h3>
<p>At least &pound;24,784.50 a year for a full-time 37.5-hour week at 21 or over. The ONS put the median full-time housekeeper at &pound;25,335 in April 2025, and NHS Band 2 domestics earn &pound;25,272 in England in 2026/27.</p>

<h3>Is &pound;19,000 a legal salary for a full-time housekeeper?</h3>
<p>Not for a worker aged 21 or over on 37.5 hours a week. The National Living Wage of &pound;12.71 an hour comes to &pound;24,784.50 a year, so &pound;19,000 is &pound;5,784.50 short.</p>

<h3>Can a live-in housekeeper be paid less because accommodation and meals are provided?</h3>
<p>Only for accommodation, and only up to &pound;11.10 a day or &pound;77.70 a week from April 2026. Meals cannot count towards the minimum wage at all.</p>

<h3>What band is an NHS housekeeper or domestic?</h3>
<p>Band 2, which pays &pound;25,272 in England in 2026/27. Supervisors are Band 3 and above, and Band 1 closed to new starters in England in December 2018.</p>

<h3>Do housekeepers need a DBS check?</h3>
<p>It depends on the workplace. Care home domestics usually get an enhanced check without the barred lists, hospital ward domestics a standard check, and hotel and private household housekeepers a basic check at most. Scotland uses PVG and Northern Ireland uses AccessNI.</p>

<h3>Can I get a UK visa to work as a housekeeper?</h3>
<p>No. Housekeepers (6231), cleaners and domestics (9223) and cleaning and housekeeping supervisors (6240) are all in Table 6 of Appendix Skilled Occupations and cannot be sponsored. The Overseas Domestic Worker visa only covers staff who have worked for a household abroad for a year, for up to 6 months.</p>

<h3>Do hotel housekeepers get a share of tips?</h3>
<p>Where the hotel handles tips or service charges, it must pass them all on and share them fairly under a written policy, under the Employment (Allocation of Tips) Act 2023 in force since 1 October 2024.</p>

<h3>Do I need experience to become a housekeeper?</h3>
<p>No. Most hotels, hospitals and care homes train new starters, including safe use of cleaning chemicals and manual handling. Experience and reliable references help for private household roles.</p>

<h2>People Also Search For</h2>

<h3>Live-in housekeeper jobs UK</h3>
<p>Accommodation can count towards the minimum wage up to &pound;77.70 a week, meals never do, and the hours should be agreed in writing.</p>

<h3>Hotel housekeeping jobs no experience</h3>
<p>Most hotels train new room attendants on the job. A basic DBS check is the most a hotel can ask for.</p>

<h3>NHS domestic assistant Band 2</h3>
<p>&pound;25,272 a year in England from 1 April 2026, with 41% extra for Saturdays and nights.</p>

<h3>Care home housekeeper jobs</h3>
<p>Usually an enhanced DBS check without the barred lists, or PVG membership in Scotland.</p>

<h3>Housekeeper jobs with visa sponsorship UK</h3>
<p>Not available. Housekeepers, cleaners and housekeeping supervisors are all in Table 6 and cannot be sponsored as Skilled Workers.</p>

<h3>Private housekeeper salary London</h3>
<p>No official survey covers private household pay. Divide the offer by the weekly hours and check it against the &pound;12.71 National Living Wage.</p>

<h3>Head housekeeper salary UK</h3>
<p>A median of &pound;28,605 for full-time cleaning and housekeeping supervisors in the April 2025 ONS survey.</p>

<h3>Room attendant jobs</h3>
<p>The hotel title for housekeeping staff, with tips and service charges protected by law since October 2024.</p>

<h2>More Job Guides</h2>

<p>Comparing housekeeping with other entry-level work in the UK and abroad? These cover it:</p>

<ul>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; office, school and contract cleaning across the capital.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the NHS bands in full, and the one care job that can still be sponsored.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the care worker visa closure and its transitional rules.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; the kitchen job in the same hotels and care homes, and its tips rules.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; another UK entry-level job that cannot be sponsored.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; housekeeping on the American seasonal visa route.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; the Gulf route for cleaning and housekeeping staff.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Minimum wage rates, NHS pay awards, checking rules and immigration rules change and differ between the four UK nations. Confirm the current position with the employer, GOV.UK, NHS Employers and a regulated immigration adviser before applying or accepting an offer.</p>
HTML;
    }
}
