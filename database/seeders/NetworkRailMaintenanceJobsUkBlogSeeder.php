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
 * "How to Apply for Network Rail Maintenance Jobs in the UK" - an employer
 * guide rebuilt on Network Rail's own corporate, careers and early careers
 * pages, Network Rail's media centre, Rail Delivery Group's Rail Staff Travel
 * scheme, RSSB's rail industry standard on drugs and alcohol, the Home Office
 * Appendix Skilled Occupations and the Railways Bill timetable, rather than the
 * job-board figures and stale campaign claims the draft relied on.
 *
 * Corrections to the draft (checked against networkrail.co.uk,
 * earlycareers.networkrail.co.uk, networkrailmediacentre.co.uk,
 * raildeliverygroup.com, rssb.co.uk, ons.gov.uk and gov.uk, September 2026):
 *
 * 1. The draft says Network Rail maintains "more than 20,000 miles of track,
 *    signals, bridges and level crossings". Network Rail's own figure is a flat
 *    20,000 miles of track plus 30,000 bridges, tunnels and viaducts, and
 *    thousands of signals, level crossings and stations. The draft inflates the
 *    track number and drops the 30,000 structures entirely. Network Rail also
 *    manages only 20 of the country's largest stations; train operators run the
 *    other 2,500-plus.
 *
 * 2. The draft says these roles are "part of the Railway 200 campaign
 *    celebrating 200 years of the modern railway". Railway 200 was a 2025
 *    campaign marking the bicentenary of the first fare-paying steam passenger
 *    journey on the Stockton and Darlington Railway on 27 September 1825. It
 *    was a celebration and a careers-inspiration programme, not a recruitment
 *    scheme, and its Inspiration exhibition train finished touring in June
 *    2026. No current Network Rail vacancy is "part of" it.
 *
 * 3. The draft claims a "national recruitment drive". Network Rail publishes no
 *    such campaign. It recruits continuously through its own external candidate
 *    portal, which sorts vacancies into Operations, Maintenance and Corporate
 *    Services. The claim is dropped rather than repeated.
 *
 * 4. The draft's salary figures - "Maintenance Technician roughly GBP 26,500 to
 *    GBP 30,000+", "Track Maintenance Technician GBP 38,000+ with experience" -
 *    cannot be traced to Network Rail or to any union or government source.
 *    They are job-board spreads. All four salary fields on the job record are
 *    therefore null. The one durable salary Network Rail publishes itself is
 *    the GBP 22,293 starting salary on its Level 3 Rail Engineering Technician
 *    Apprenticeship for the November 2026 intake, and that is a different role,
 *    so it is quoted in the body with its scope attached rather than used as a
 *    technician rate.
 *
 * 5. The draft lists five technician disciplines: Track, Signalling,
 *    Distribution & Plant, Telecoms and Overhead Line. Network Rail's own
 *    apprenticeship page lists six - Track, Signalling, Telecommunications,
 *    Overhead Lines, Locking Fitter, and Distribution and Plant - and states
 *    that the discipline is allocated by geography and local depot need rather
 *    than chosen by the applicant.
 *
 * 6. The draft's "up to 75% discount on leisure travel" collapses two separate
 *    benefits and omits the terms. Network Rail lists both "up to 75% off
 *    leisure travel for you and your family" and a separate "75% subsidy on
 *    rail and underground season tickets". The leisure half runs through Rail
 *    Staff Travel: the Rail Staff Leisure Card gives 75% off Anytime and
 *    Off-Peak fares and some Rover and Ranger tickets, is leisure-only and
 *    cannot be used for commuting, duty or business travel, and the underlying
 *    TOC Privilege Travel Arrangement is non-contractual, reviewed annually by
 *    train operators and can be withdrawn.
 *
 * 7. The draft's "strong pension, family benefits, on-the-job training" is not
 *    Network Rail's wording. Its published list is 28 days' annual leave plus
 *    bank holidays, 5 volunteer days, up to 75% off leisure travel, a 75%
 *    season ticket subsidy, an employee assistance programme, cycle to work,
 *    discounted online shopping, access to a range of pension and insurance
 *    schemes, and two weeks' paid reserve leave for the Armed Forces community.
 *
 * 8. The draft gives a flat "minimum age 18". Network Rail says applications
 *    are open from age 16, but you must be 18 or older by the start of the
 *    programme because of the safety checks that apply on the railway. It is a
 *    start-date rule, not an application rule.
 *
 * 9. The draft describes "drug and alcohol screening under a zero tolerance
 *    policy". Network Rail's own wording is that all roles include a drug and
 *    alcohol screening process which forms part of the pre-employment medical,
 *    taken before an offer is confirmed. The industry standard is a number, not
 *    a slogan: under RSSB's rail industry standard RIS-8070-TOM a positive
 *    alcohol result for safety-critical work is more than 29mg of alcohol per
 *    100ml of blood, about a quarter of the road driving limit in England and
 *    Wales, under the Transport and Works Act 1992 framework.
 *
 * 10. The draft says "NVQ Level 1/2 or equivalent useful for technical roles".
 *     The documented entry bar Network Rail publishes for its Level 3 Rail
 *     Engineering Technician Apprenticeship is GCSE grades 9-4 in English,
 *     Science and one other subject, and grade 9-5 in Maths, or equivalent.
 *
 * 11. The draft offers Indeed as an apply route. Aggregator links are against
 *     site policy, and individual Network Rail vacancy URLs carry job IDs and
 *     expire. The only durable link is Network Rail's own careers page, which
 *     feeds its external candidate portal.
 *
 * 12. The draft says Network Rail "has featured in Glassdoor's Top 50 Best
 *     Places to Work", in the present tense. Network Rail's own media centre
 *     dates that to 13 January 2021: 29th place, 4.2 out of 5, and described at
 *     the time as a first. It is a five-year-old award and is presented as one.
 *
 * 13. The draft ignores right to work and sponsorship completely, which is the
 *     question that decides everything for this site's readers. Network Rail
 *     does hold a sponsor licence, but sponsorship is per occupation code, not
 *     per employer. Rail construction and maintenance operatives are SOC 2020
 *     code 8153 (8143 under SOC 2010), and 8153 does not appear anywhere in the
 *     Home Office's Appendix Skilled Occupations, so that job cannot be
 *     sponsored at all. Codes that are eligible and that rail work can fall
 *     under carry going rates well above entry-level maintenance pay:
 *     engineering technicians 3113 at GBP 42,500, rail and rolling stock
 *     builders and repairers 5236 at GBP 57,000, electrical and electronic
 *     trades n.e.c. 5249 at GBP 45,800, electricians and electrical fitters
 *     5241 at GBP 38,800, telecoms and related network installers 5242 at
 *     GBP 36,700. The general Skilled Worker threshold has been GBP 41,700 a
 *     year since 22 July 2025 and a sponsor must pay the higher of that and the
 *     going rate. The Temporary Shortage List needs at least GBP 33,400 plus
 *     the going rate and only covers certificates of sponsorship issued before
 *     31 December 2026. Network Rail also states plainly that its early careers
 *     schemes require an existing right to work, and that a student visa holder
 *     must move to a Graduate visa to join.
 *
 * 14. The draft treats Network Rail as a fixed employer. The Railways Bill was
 *     introduced to Parliament on 5 November 2025 and is intended to fold
 *     Network Rail's infrastructure role into Great British Railways, with GBR
 *     expected to stand up roughly twelve months after Royal Assent and the
 *     consolidation targeted for the end of 2027. Operators are already moving:
 *     Chiltern transferred on 20 September 2026 and Great Western is scheduled
 *     for 13 December 2026. You apply to Network Rail today; the name on the
 *     contract may not stay Network Rail.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NetworkRailMaintenanceJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.networkrail.co.uk/careers/';

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
        $title = 'How to Apply for Network Rail Maintenance Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Network Rail apprentice pay is the only salary it publishes itself, Railway 200 was a 2025 campaign not a hiring drive, and a track maintenance operative job cannot be sponsored at all. Here is what Network Rail\'s own pages actually say.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk.jpg',
                'tags' => 'network rail jobs, network rail maintenance, track maintenance technician, rail engineering apprenticeship, network rail careers, railway jobs uk, great british railways, skilled worker visa uk',
                'meta_title' => 'Network Rail Maintenance Jobs UK: How to Apply',
                'meta_description' => 'Network Rail maintenance jobs: apply on Network Rail\'s own careers site, the real benefits, and which rail codes can be sponsored.',
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
            ['name' => 'Network Rail, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'network-rail-uk']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Maintenance Technician and Operative, Network Rail UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rostered shifts including nights, weekends and bank holidays; much maintenance work is done during engineering possessions when trains are not running',
                'language' => 'English',
                // Deliberately null. Network Rail prints a rate on each live
                // vacancy advert, but those adverts carry job IDs and expire,
                // and Network Rail publishes no durable pay scale for
                // maintenance technician or operative grades. The only salary
                // it publishes itself and keeps up is the GBP 22,293 starting
                // salary on the Level 3 Rail Engineering Technician
                // Apprenticeship for the November 2026 intake, which is a
                // different role, so it is quoted in the article rather than
                // used here. No aggregator figure is republished.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Track, signalling, telecoms, overhead line and distribution and plant maintenance roles with Network Rail across Britain. Safety-critical work with a pre-employment medical and drug and alcohol screening.',
                'seo_keywords' => 'network rail jobs, network rail maintenance jobs, track maintenance technician, signalling technician uk, overhead line operative, railway jobs uk, network rail careers apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Network Rail maintains the track, signalling, overhead line, telecoms and lineside plant on Britain's 20,000 miles of railway, and recruits maintenance operatives, technicians, team leaders and specialists at depots across England, Scotland and Wales.</p>

<h3>What the work involves</h3>
<p>Inspecting, repairing and renewing track and its components, fault-finding on signalling and telecoms equipment, working on overhead line and lineside power distribution, and monitoring asset condition. Much of it happens outdoors, at night and at weekends, inside engineering possessions when trains are not running.</p>

<h3>Common requirements</h3>
<ul>
    <li>The right to work in the United Kingdom &mdash; rail construction and maintenance operative work is not a sponsorable occupation code</li>
    <li>Aged 18 or over by the start date, because of the safety checks that apply on the railway</li>
    <li>A pre-employment medical, which includes the drug and alcohol screening that applies to every Network Rail role</li>
    <li>Willingness to work rostered nights, weekends and bank holidays outdoors in all weather</li>
    <li>GCSE-level English, maths and science, or equivalent, for the technician apprenticeship route; no rail experience needed for entry-level operative work</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, rosters and medical standards for these roles are set by Network Rail and the rail industry standards bodies, and visa rules are set by the Home Office &mdash; not by JobGader. Apply directly through Network Rail's own careers site and never pay anyone for a Network Rail job, a medical or a PTS card.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply through Network Rail's own careers site, which feeds its external candidate portal and sorts vacancies into Operations, Maintenance and Corporate Services.</strong> Pick the depot you would actually report to, read the rate printed on that advert, and expect a pre-employment medical that includes drug and alcohol screening before any offer is confirmed. There is no agent who can shorten that, and no shortcut worth paying for.</p>

<p>Two answers before you spend an evening on this. <strong>A track maintenance operative job at Network Rail cannot be sponsored for a UK work visa</strong> &mdash; the occupation code does not appear on the Home Office's eligible list at all, and holding a sponsor licence does not change that. And the salary figures circulating for these roles are job-board estimates; the only pay Network Rail publishes and keeps current is its apprenticeship rate, which is set out below with its scope attached rather than dressed up as a technician salary.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.networkrail.co.uk/careers/" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#0072ce;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128643; Network Rail Careers &rarr;
    </a>
</div>

<h2>What Network Rail Actually Looks After</h2>

<p>Get the scale right, because most guides do not. Network Rail's own figure is <strong>20,000 miles of track</strong> and <strong>30,000 bridges, tunnels and viaducts</strong>, together with thousands of signals, level crossings and stations. It is not "more than 20,000 miles of track, signals, bridges and level crossings" &mdash; that sentence inflates one number and quietly deletes the 30,000 structures that a maintenance workforce actually spends its nights on.</p>

<p>One more distinction worth carrying into an interview: Network Rail manages <strong>20 of the country's largest stations</strong> directly. The other 2,500-plus are operated by train companies. If you are applying for station-based work, check whose payroll you would be on.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk-track.jpg" alt="Railway track and lineside infrastructure maintained by Network Rail" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Railway 200 Was Last Year's Campaign, Not This Year's Vacancy List</h2>

<p>You will see rail maintenance jobs advertised as "part of Railway 200". They are not, and the claim dates the guide that makes it.</p>

<p><strong>Railway 200 ran through 2025</strong>, marking two centuries since the first fare-paying steam passenger journey on the Stockton and Darlington Railway on <strong>27 September 1825</strong>. It was a partnership campaign to celebrate the railway's history and to push rail careers at young people &mdash; genuinely useful, but a celebration, not a hiring programme. Its Inspiration exhibition train toured Britain into <strong>June 2026</strong> and has finished. No vacancy on Network Rail's portal today sits under that banner.</p>

<p>Nor is there a "national recruitment drive". Network Rail does not publish one. It recruits continuously, depot by depot, through its own portal. If a guide tells you a special window is open, ask it for the source.</p>

<h2>The Maintenance Roles and How They Are Structured</h2>

<p>Network Rail's maintenance workforce runs from entry-level operative work up through technician grades to team leader and specialist roles that monitor asset condition and system performance. The technician disciplines are where most applicants get the detail wrong.</p>

<p>Network Rail's own Level 3 Rail Engineering Technician Apprenticeship lists <strong>six</strong> disciplines, not five: <strong>Track, Signalling, Telecommunications, Overhead Lines, Locking Fitter, and Distribution and Plant</strong>. Locking Fitter is the one that gets dropped from every summary. And there is a sting in the wording most people miss: your discipline is <strong>allocated to you according to your geography and what the need is at your local depot</strong>. You are not picking signalling because signalling sounds interesting.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk-crew.jpg" alt="A Network Rail maintenance crew working on the railway" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<p>The shift pattern is the part worth thinking hardest about. Maintenance largely happens inside engineering possessions, when the line is closed to trains. In practice that means nights, weekends and bank holidays, outdoors, in weather nobody chose. Anyone selling you rail maintenance as a nine-to-five has never stood on a ballast shoulder at three in the morning.</p>

<h2>Pay: What Can Be Sourced, and What Cannot</h2>

<p>This section is shorter than you want it to be, and that is the honest answer.</p>

<p>Network Rail prints a salary on each live vacancy advert. Those adverts carry job IDs and expire, so no guide can quote them durably. What Network Rail publishes and maintains is the apprenticeship rate: the <strong>Level 3 Rail Engineering Technician Apprenticeship starts at &pound;22,293</strong>, runs three years, and the intake being advertised at the time of writing starts in <strong>November 2026</strong>. That is a training salary for a specific scheme. It is not what a qualified track technician earns, and it should never be presented as one.</p>

<p>What this guide will not do is repeat the "&pound;26,500 to &pound;30,000" and "&pound;38,000+ with experience" figures you will find elsewhere. Those come from job-board aggregation across different grades, regions, rosters and allowance structures, and they cannot be traced to Network Rail, to the RMT or TSSA, or to any government source. Rail maintenance pay is heavily shaped by shift allowances, standby and possession working that no headline figure captures.</p>

<p>The practical rule: <strong>the only pay figure that means anything is the one printed on the advert in front of you</strong>, and you should read it next to the roster before you read it next to any national average.</p>

<h2>The Medical, the Drug and Alcohol Screen, and the Real Numbers</h2>

<p>Network Rail's own wording is precise and worth learning. You will be asked to <strong>attend a medical before an offer is confirmed</strong>, as part of pre-employment checks, and <strong>all roles at Network Rail include a drug and alcohol screening process which forms part of that medical</strong>. It is not a separate hurdle you can prepare for afterwards.</p>

<p>"Zero tolerance" is a slogan. The railway uses a number. Under RSSB's rail industry standard <strong>RIS-8070-TOM</strong>, a positive alcohol result for someone doing safety-critical work is <strong>more than 29mg of alcohol per 100ml of blood</strong> &mdash; roughly a quarter of the road driving limit in England and Wales &mdash; within the framework set by the <strong>Transport and Works Act 1992</strong>. Safety-sensitive roles explicitly include maintenance staff, their supervisors and lookouts, not just drivers and signallers.</p>

<p>On the medical itself, safety-critical rail work carries sight and hearing standards, and the commonly applied hearing threshold is no more than <strong>30dB average loss over 0.5, 1 and 2 kHz</strong>. The brief's instinct that sight and hearing are tested is right; what it missed is that these are defined industry standards, so if you have a known hearing or vision issue it is worth getting the numbers checked before you apply rather than after.</p>

<h2>Age and Entry Requirements</h2>

<p>The "minimum age 18" line needs splitting in two. Network Rail says you are welcome to apply to its schemes if you meet the minimum criteria and are <strong>above the age of 16</strong>. Separately, you <strong>must be 18 or older by the start of the programme</strong>, because of the safety checks that apply in a railway environment. So 18 is a start-date rule, not an application rule, and a 17-year-old finishing school can apply for a programme that begins after their birthday.</p>

<p>On qualifications, the "NVQ Level 1/2" line in circulation is not Network Rail's published bar. For the Level 3 Rail Engineering Technician Apprenticeship, Network Rail asks for <strong>GCSE grades 9-4 in English, Science and at least one other subject, and grade 9-5 in Maths, or equivalent</strong>. Entry-level operative work does not require rail experience, but it does require the medical, the screening and the willingness to work the roster.</p>

<h2>Benefits: The Published List, Not the Paraphrase</h2>

<p>Network Rail's own benefits wording, taken from its early careers pages:</p>

<ul>
    <li><strong>28 days' annual leave, plus bank holidays</strong></li>
    <li><strong>5 volunteer days</strong> to contribute to your favourite causes</li>
    <li><strong>Up to 75% off leisure travel</strong> for you and your family</li>
    <li><strong>75% subsidy on rail and underground season tickets</strong> &mdash; a separate benefit from the leisure discount</li>
    <li>Access to a range of <strong>pension and insurance schemes</strong></li>
    <li>Employee assistance programme, cycle to work scheme, discounted online shopping</li>
    <li><strong>Two weeks' paid reserve leave</strong> for members of the Armed Forces community</li>
</ul>

<p>The travel benefit is the one most guides mangle into a flat "75% off". It is not flat. The leisure half runs through the rail industry's staff travel scheme: the <strong>Rail Staff Leisure Card</strong> gives 75% off Anytime and Off-Peak fares and some Rover and Ranger tickets, and privilege-rate tickets are <strong>leisure only</strong> &mdash; they cannot be used for commuting, duty or business travel. The underlying train operator privilege arrangement is <strong>non-contractual</strong>, reviewed annually by the operators and capable of being withdrawn. Commuting is covered instead by the separate 75% season ticket subsidy. Two schemes, two sets of rules.</p>

<p>On employer reputation, the claim that Network Rail "has featured in Glassdoor's Top 50 Best Places to Work" is true but stale. Network Rail's own media centre dates it to <strong>13 January 2021</strong>: 29th place, an overall rating of 4.2 out of 5, and described at the time as a first. Treat it as a five-year-old data point, not a current standing.</p>

<h2>Sponsorship: The Question the Brief Skipped</h2>

<p>For readers outside the UK this is the only section that decides anything, and it is the one most Network Rail guides leave out entirely.</p>

<p>Network Rail does hold a UK sponsor licence. That tells you almost nothing. <strong>Sponsorship is granted per occupation code, not per employer</strong>, so the question is never "does this company sponsor" but "is this job's code eligible, and does the salary clear the threshold".</p>

<p>Rail construction and maintenance operatives sit in <strong>SOC 2020 code 8153</strong> (it was 8143 under the older SOC 2010 classification). <strong>Code 8153 does not appear in the Home Office's Appendix Skilled Occupations at all</strong>, which means no employer can sponsor that job, however short-staffed the depot is.</p>

<p>Codes that rail engineering work can fall under, and that are eligible, carry going rates an entry-level maintenance job would not reach:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#0072ce;color:#fff;">
            <th style="padding:10px;text-align:left;">SOC 2020 code</th>
            <th style="padding:10px;text-align:left;">Occupation</th>
            <th style="padding:10px;text-align:left;">Going rate</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>8153</strong></td><td style="padding:10px;">Rail construction and maintenance operatives</td><td style="padding:10px;">Not on the list &mdash; cannot be sponsored</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>3113</strong></td><td style="padding:10px;">Engineering technicians</td><td style="padding:10px;">&pound;42,500</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>5236</strong></td><td style="padding:10px;">Rail and rolling stock builders and repairers</td><td style="padding:10px;">&pound;57,000</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>5249</strong></td><td style="padding:10px;">Electrical and electronic trades n.e.c.</td><td style="padding:10px;">&pound;45,800</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>5241</strong></td><td style="padding:10px;">Electricians and electrical fitters</td><td style="padding:10px;">&pound;38,800</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>5242</strong></td><td style="padding:10px;">Telecoms and related network installers</td><td style="padding:10px;">&pound;36,700</td></tr>
    </tbody>
</table>
</div>

<p>Read that table next to the thresholds. Since <strong>22 July 2025</strong> the general Skilled Worker salary floor has been <strong>&pound;41,700 a year</strong>, and a sponsor must pay <strong>the higher</strong> of that floor and the occupation's going rate. The <strong>Temporary Shortage List</strong> is a narrower door for some RQF 3 to 5 roles at a reduced floor of <strong>&pound;33,400</strong> plus the going rate, and it only covers certificates of sponsorship issued <strong>before 31 December 2026</strong>. None of that rescues a maintenance operative role whose code is not listed in the first place.</p>

<p>Network Rail itself is blunt about the practical position on its schemes: you need the right to work in the UK, and if you hold a student visa you will need to move to a <strong>Graduate visa</strong> before you can join. So this work is open to you if you already hold a right to work &mdash; British or Irish citizenship, settled or pre-settled status, indefinite leave to remain, a partner or family visa, a Graduate visa, or a dependant visa that permits work. It is not a route into the country. If you are applying from abroad, <a href="/blog/jobs-in-uk-for-foreigners">our guide to jobs in UK for foreigners</a> sets out the routes that genuinely exist, and <a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">our BP engineering jobs guide</a> covers the engineering codes that can clear the threshold.</p>

<h2>Great British Railways May Change Who Employs You</h2>

<p>One thing no Network Rail guide written before late 2025 accounts for. The <strong>Railways Bill was introduced to Parliament on 5 November 2025</strong> and is intended to bring Network Rail's infrastructure role, most passenger operations and key planning functions into a single body, <strong>Great British Railways</strong>. GBR is expected to become operational roughly twelve months after Royal Assent, with the full consolidation targeted for the <strong>end of 2027</strong>.</p>

<p>The passenger side is already moving: <strong>Chiltern Railways transferred into public ownership on 20 September 2026</strong>, and <strong>Great Western is scheduled for 13 December 2026</strong>, with all fourteen operators expected to be publicly owned by the end of 2027. For an applicant this does not change how you apply or what the job involves, but it does mean the name on your contract may not stay Network Rail for the whole of your career. Anyone telling you the restructuring creates or removes maintenance vacancies is guessing.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Start on Network Rail's own careers page</strong> rather than a job board. Individual vacancy links carry job IDs and expire; the careers page does not.</li>
    <li><strong>Filter by the Maintenance category</strong>, then by depot. Maintenance work is tied to a specific depot and a specific section of route, and that is where you would sign on.</li>
    <li><strong>Read the salary and the roster on the advert itself.</strong> Shift, standby and possession allowances differ by depot and discipline, and no national figure captures them.</li>
    <li><strong>Check your right to work before anything else.</strong> If you need sponsorship for an operative role, that route does not exist.</li>
    <li><strong>Expect an online application and pre-screening questions,</strong> followed by assessment stages that vary by role, and be ready to give a full work history.</li>
    <li><strong>Plan for the medical and the drug and alcohol screen.</strong> They come before the offer is confirmed, and the alcohol standard is a quarter of the road limit.</li>
    <li><strong>Do not pay anyone.</strong> Not for a job, not for a medical, not for a PTS card. Network Rail recruits and pays for its own checks.</li>
</ol>

<p>If Network Rail has nothing at your depot, the same medical discipline and shift tolerance transfer straight into other infrastructure and trades work. Our guide to <a href="/blog/electrician-jobs-in-uk">electrician jobs in UK</a> covers the nearest eligible occupation codes.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I apply for a Network Rail maintenance job?</h3>
<p>Through Network Rail's own careers page, which feeds its external candidate portal and sorts vacancies into Operations, Maintenance and Corporate Services. Filter by the Maintenance category and then by the depot you would report to, and apply on the advert itself.</p>

<h3>Does Network Rail sponsor work visas for maintenance jobs?</h3>
<p>Not for maintenance operative work. Rail construction and maintenance operatives are SOC 2020 code 8153, which does not appear on the Home Office's eligible occupation list, so no employer can sponsor it. Network Rail holds a sponsor licence, but sponsorship is granted per occupation code, not per employer.</p>

<h3>What does a Network Rail maintenance technician earn?</h3>
<p>Network Rail prints the rate on each live advert and publishes no durable pay scale for these grades, so no honest guide can quote one. The one figure it does publish is &pound;22,293 as the starting salary on its Level 3 Rail Engineering Technician Apprenticeship, which is a three-year training scheme rather than a qualified technician rate.</p>

<h3>How old do you have to be to work for Network Rail?</h3>
<p>You can apply to Network Rail's schemes from age 16 if you meet the minimum criteria, but you must be 18 or older by the start of the programme because of the safety checks that apply on the railway. It is a start-date requirement, not an application one.</p>

<h3>What happens at the Network Rail medical?</h3>
<p>Network Rail asks you to attend a medical before it confirms an offer, and says all roles include a drug and alcohol screening process which forms part of that medical. Safety-critical rail work also carries defined sight and hearing standards, with hearing commonly assessed at no more than 30dB average loss over 0.5, 1 and 2 kHz.</p>

<h3>What is the alcohol limit for railway maintenance workers?</h3>
<p>Under rail industry standard RIS-8070-TOM, a positive result for safety-critical work is more than 29mg of alcohol per 100ml of blood, roughly a quarter of the road driving limit in England and Wales, within the Transport and Works Act 1992 framework. Maintenance staff, their supervisors and lookouts are all covered.</p>

<h3>Which maintenance disciplines can I train in?</h3>
<p>Network Rail's Level 3 Rail Engineering Technician Apprenticeship lists six: Track, Signalling, Telecommunications, Overhead Lines, Locking Fitter, and Distribution and Plant. Your discipline is allocated according to your geography and what your local depot needs, rather than chosen by you.</p>

<h3>Will Great British Railways change Network Rail jobs?</h3>
<p>The Railways Bill, introduced on 5 November 2025, is intended to fold Network Rail's infrastructure role into Great British Railways, with the consolidation targeted for the end of 2027. It does not change how you apply today, but the employer name on the contract may change during your career.</p>

<h2>People Also Search For</h2>

<h3>Network Rail jobs near me</h3>
<p>Filter Network Rail's own portal by the Maintenance category and then by depot; maintenance vacancies are tied to a specific depot and section of route rather than a general town.</p>

<h3>Network Rail apprenticeship salary</h3>
<p>The Level 3 Rail Engineering Technician Apprenticeship starts at &pound;22,293 over three years, with the advertised intake beginning in November 2026.</p>

<h3>Track maintenance technician requirements</h3>
<p>Aged 18 by the start date, a pre-employment medical with drug and alcohol screening, and GCSE grades 9-4 in English, Science and one other subject plus 9-5 in Maths for the apprenticeship route.</p>

<h3>Railway 200</h3>
<p>A 2025 campaign marking 200 years since the first fare-paying steam passenger journey on the Stockton and Darlington Railway on 27 September 1825. Its exhibition train finished touring in June 2026.</p>

<h3>Rail Staff Leisure Card</h3>
<p>Gives 75% off Anytime and Off-Peak fares and some Rover and Ranger tickets, leisure travel only, and the underlying privilege arrangement is non-contractual and reviewed annually by train operators.</p>

<h3>Great British Railways timeline</h3>
<p>The Railways Bill was introduced on 5 November 2025; GBR is expected to become operational around twelve months after Royal Assent, with consolidation targeted for the end of 2027.</p>

<h3>SOC code for rail maintenance</h3>
<p>SOC 2020 code 8153, rail construction and maintenance operatives, previously 8143 under SOC 2010. It is not on the Home Office's eligible occupation list.</p>

<h3>Skilled Worker visa salary threshold</h3>
<p>&pound;41,700 a year or the occupation's going rate, whichever is higher, since 22 July 2025. The Temporary Shortage List route uses a &pound;33,400 floor and closes to new certificates of sponsorship after 31 December 2026.</p>

<h2>More Job Guides</h2>

<p>If Network Rail is one option rather than the only one, these cover the rest of the UK market:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">How to Apply for BP Engineering Jobs in the UK</a> &mdash; the engineering codes that can actually clear the sponsorship threshold.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the nearest eligible trade code to overhead line and distribution work.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the routes that remain once the ineligible occupation codes are ruled out.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; what sponsorship really covers in logistics, and what it does not.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; another large employer where the occupation codes rule out sponsorship.</li>
    <li><a href="/blog/how-to-get-a-warehouse-driver-job-in-uk">How to Get a Warehouse Driver Job in UK</a> &mdash; shift work indoors, for anyone who has had enough of night possessions.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the desk-based route for people drawn to the telecoms and signalling side.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; an employer that does publish its hourly rate up front.</li>
    <li><a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">How to Apply for Heathrow Airport Jobs in the UK</a> &mdash; the five year vetting and counter terrorism check that decide the application.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Network Rail's own corporate, careers and early careers pages for network size, roles, apprenticeship pay, entry requirements, medical and drug and alcohol screening, benefits and right-to-work wording; Network Rail's media centre for the Glassdoor ranking; Rail Delivery Group's Rail Staff Travel scheme for privilege travel terms; RSSB rail industry standard RIS-8070-TOM and the Transport and Works Act 1992 for the safety-critical alcohol limit; ONS SOC 2020 for occupation codes; and the Home Office Appendix Skilled Occupations, Temporary Shortage List and Skilled Worker guidance for the visa position. Network Rail vacancy adverts expire quickly, and pay, rosters, industry standards and immigration rules change. Always check the live advert and the official guidance before you commit.</p>
HTML;
    }
}
