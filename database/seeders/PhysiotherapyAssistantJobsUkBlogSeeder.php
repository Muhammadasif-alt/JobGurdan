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
use Illuminate\Support\Facades\DB;

/**
 * Physiotherapy assistant careers in the UK, checked on 28 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. The brief gave Band 3 as GBP 29,061 to GBP 31,364 and Band 4 as
 *     GBP 31,492 to GBP 34,254 "April 2026 scales", presented as UK figures.
 *     Those are NHS Scotland rates, and they are the superseded ones from
 *     PCS(AFC)2025/5. The inflation guarantee in that settlement was
 *     triggered, so PCS(AFC)2026/1 re-based them. England pays a Band 3
 *     GBP 25,760 to GBP 27,476. Both nations are now given, correctly.
 *  2. "Band 2 ... GBP 25,272 to GBP 27,476" described a range. Band 2 in
 *     England is a single pay point of GBP 25,272.
 *  3. Every Indeed UK link and the "roughly 267 jobs" count are gone.
 *     Aggregator vacancy counts are not republished here.
 *  4. "Formal entry requirements are often just GCSE-level English and maths"
 *     overstated one trust's advert. The CSP records no national entry
 *     qualification at all.
 *  5. The Care Certificate has had 16 standards since March 2025, and the
 *     twelve-week deadline is an employer expectation, not a national rule.
 *  6. Four FAQs and no People Also Search For block. Now eight of each.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class PhysiotherapyAssistantJobsUkBlogSeeder extends Seeder
{
    public const SLUG = 'physiotherapy-assistant-jobs-in-the-uk';

    public const APPLY_URL = 'https://www.jobs.nhs.uk/';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'NHS Trusts, Health Boards and Private Clinics, UK (Aggregated)'],
                ['display_reference' => 'uk-physiotherapy-assistant-aggregated', 'type' => 'Public']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United Kingdom'],
                ['area' => 'Nationwide', 'country' => 'United Kingdom']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Physiotherapy Assistant / Therapy Support Worker, UK Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Around 37.5 hours a week in England, 36 in Scotland, often on a rota covering evenings, weekends and bank holidays',
                    'language' => 'English',
                    // The band, and the nation, decide the rate. No single
                    // range holds across England, Scotland and private clinics.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'physiotherapy assistant jobs uk, physiotherapy support worker jobs, therapy assistant jobs nhs, nhs band 3 physiotherapy assistant, rehabilitation assistant jobs',
                    'meta_description' => 'Physiotherapy assistant and therapy support worker roles across NHS trusts, health boards and private clinics in the UK. Band 2 to Band 4 posts explained.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'career-advice'],
                ['name' => 'Career Advice', 'description' => 'Practical guides on finding work, applying and building a career.']
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Physiotherapy Assistant Jobs in the UK',
                    'excerpt' => 'A Band 3 physiotherapy assistant earns £25,760 to £27,476 in England but £29,103 to £31,409 in Scotland, on a shorter week. Band 2 starts at £12.92 an hour, 21p above the legal minimum, so night and weekend enhancements decide the real pay.',
                    'content' => $content,
                    'featured_image' => 'blogs/physiotherapy-assistant-jobs-uk.jpg',
                    'tags' => 'physiotherapy assistant jobs uk, physiotherapy support worker, nhs band 3 physiotherapy assistant, therapy assistant jobs nhs, physio assistant salary uk, rehabilitation assistant jobs, technical instructor physiotherapy, nhs jobs physiotherapy',
                    'meta_title' => 'Physiotherapy Assistant Jobs UK: NHS Bands and 2026 Pay',
                    'meta_description' => 'Physiotherapy assistant jobs in the UK: real April 2026 NHS bands, why Scotland pays a Band 3 nearly GBP 4,000 more than England, and how to apply.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p><strong>Physiotherapy assistant and therapy support worker roles across NHS trusts, health boards and private clinics in the United Kingdom.</strong> This page is a career and job-search overview covering many employers, not an advert for one guaranteed vacancy. Use the official link to search current openings by band and location.</p>
<h3>What the work involves</h3>
<p>Carrying out treatment programmes delegated by a qualified physiotherapist, setting up equipment, showing patients how to use mobility aids, and helping patients prepare for and work through exercises. Departmental and clerical duties are usually part of the post.</p>
<h3>Requirements</h3>
<p>The Chartered Society of Physiotherapy records no national entry qualification for support workers. Employers expect good numeracy and literacy, and many ask for GCSEs in English and maths or a vocational health and social care qualification at level 2 or 3. Read each advert, because requirements are set locally.</p>
<h3>Pay and working hours</h3>
<p>NHS pay follows Agenda for Change and differs by nation. From April 2026 a Band 3 post pays <strong>&pound;25,760 to &pound;27,476</strong> in England and <strong>&pound;29,103 to &pound;31,409</strong> in Scotland. The standard week is around 37.5 hours in England and 36 in Scotland. Nights, Saturdays, Sundays and bank holidays attract percentage enhancements. Private clinics set their own rates.</p>
<h3>Where to apply</h3>
<p><a href="https://www.jobs.nhs.uk/" rel="noopener">Search NHS Jobs</a> for England, <a href="https://apply.jobs.scot.nhs.uk/" rel="noopener">the NHS Scotland recruitment portal</a> for Scotland and <a href="https://jobs.hscni.net/" rel="noopener">HSC Jobs</a> for Northern Ireland. Welsh health boards recruit through their own sites. The CSP also advises approaching trusts directly, because many support worker posts are advertised locally.</p>
<p>Read our <a href="/blog/physiotherapy-assistant-jobs-in-the-uk">Physiotherapy Assistant Jobs in the UK guide</a> for the band-by-band pay tables, entry requirements and official sources. No visa sponsorship is promised by this overview.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To apply for a physiotherapy assistant job in the UK, find a vacancy on NHS Jobs in England, the NHS Scotland recruitment portal, or HSC Jobs in Northern Ireland, check the band before anything else, and submit an application that evidences each point on the person specification.</strong> There is no national entry qualification for the role. Search several titles, because the same job is advertised as physiotherapy assistant, physiotherapy support worker, therapy assistant, rehabilitation assistant and technical instructor.</p>
<p>The pay figures circulating for this role are wrong in a specific and expensive way, so this guide starts there.</p>

<h2>Physiotherapy assistant pay in 2026/27</h2>
<p>NHS pay runs on Agenda for Change, and Agenda for Change is not one UK-wide set of numbers. England and Scotland negotiate separately, and from 1 April 2026 they are a long way apart for exactly the same band.</p>
<table><thead><tr><th scope="col">Band</th><th scope="col">England, from 1 April 2026</th><th scope="col">Scotland, from 1 April 2026</th></tr></thead><tbody>
<tr><td>Band 2</td><td>&pound;25,272 (a single point) &mdash; &pound;12.92 an hour</td><td>&pound;26,696 to &pound;28,988 &mdash; &pound;14.22 to &pound;15.44 an hour</td></tr>
<tr><td><strong>Band 3</strong></td><td><strong>&pound;25,760 to &pound;27,476</strong> &mdash; &pound;13.17 to &pound;14.05 an hour</td><td><strong>&pound;29,103 to &pound;31,409</strong> &mdash; &pound;15.50 to &pound;16.73 an hour</td></tr>
<tr><td>Band 4</td><td>&pound;28,392 to &pound;31,157 &mdash; &pound;14.52 to &pound;15.93 an hour</td><td>&pound;31,537 to &pound;34,303 &mdash; &pound;16.80 to &pound;18.27 an hour</td></tr>
</tbody></table>
<p>The English hourly rates are based on a 37.5-hour week. The Scottish ones are based on a <strong>36-hour week</strong>, which is why the hourly gap is wider than the annual one. Sources: <a href="https://www.nhsemployers.org/articles/pay-scales-202627">NHS Employers, pay scales for 2026/27</a> and <a href="https://www.publications.scot.nhs.uk/files/pcs2026-afc-01.pdf">NHS Circular PCS(AFC)2026/1</a>, both checked on 28 September 2026.</p>

<h2>If you have read that Band 3 pays &pound;29,061, here is what happened</h2>
<p>That figure, and its partner &pound;31,364, appear in several 2026 careers guides as UK Band 3 pay. They are neither UK-wide nor current.</p>
<p>They are Scottish, and they come from <a href="https://www.publications.scot.nhs.uk/files/pcs2025-afc-05.pdf">PCS(AFC)2025/5</a>, the May 2025 circular that set a two-year deal of 4.25 per cent for 2025-26 and 3.75 per cent for 2026-27. That deal carried a guarantee that each year's rise would be at least one percentage point above average CPI inflation. CPI for the 2025 calendar year came in at 3.4 per cent, which triggered the guarantee, so 2025-26 was re-based at 4.4 per cent and the 2026-27 rates were recalculated on the new baseline in January 2026. Scotland's Band 3 is now &pound;29,103 to &pound;31,409, not &pound;29,061 to &pound;31,364.</p>
<p>If you live in England, the error is far larger than the recalculation. An English Band 3 physiotherapy assistant is paid &pound;25,760 to &pound;27,476. Being told to expect &pound;29,061 overstates the entry rate by <strong>&pound;3,343</strong> and the top of the band by <strong>&pound;3,933</strong>. Check which nation a pay figure belongs to before you use it to judge an offer.</p>

<h2>Band 2 sits 21p above the legal minimum</h2>
<p>The National Living Wage rose to <strong>&pound;12.71</strong> an hour for workers aged 21 and over on 1 April 2026. An English NHS Band 2 is &pound;12.92. The entry grade for this role is therefore 21p an hour above what any employer in the country must pay by law.</p>
<p>That makes the unsocial hours enhancements the part of the offer worth reading closely. Under Section 2 of the Agenda for Change handbook in England:</p>
<table><thead><tr><th scope="col">Band</th><th scope="col">Saturdays and weekday nights (8pm to 6am)</th><th scope="col">Sundays and public holidays</th></tr></thead><tbody>
<tr><td>Band 2</td><td>Time plus 41 per cent</td><td>Time plus 83 per cent</td></tr>
<tr><td>Band 3</td><td>Time plus 35 per cent</td><td>Time plus 69 per cent</td></tr>
<tr><td>Bands 4 to 9</td><td>Time plus 30 per cent</td><td>Time plus 60 per cent</td></tr>
</tbody></table>
<p>The enhancement shrinks as the band rises, and it shrinks faster than basic pay grows. On a Saturday an English Band 2 is paid <strong>&pound;18.22</strong> an hour while a Band 3 at the bottom of the band is paid <strong>&pound;17.78</strong>. A rota with weekend and night cover in it is worth more than a one-band promotion onto weekdays. Source: <a href="https://www.nhsemployers.org/articles/unsocial-hours-payments">NHS Employers, unsocial hours payments</a>.</p>

<img src="/public/storage/blogs/physiotherapy-assistant-jobs-uk-overview.jpg" alt="Physiotherapy assistant supporting a patient with mobility exercises in a UK hospital department" loading="lazy" width="1600" height="836">

<h2>What the job involves, band by band</h2>
<p>The Chartered Society of Physiotherapy sets out what separates the three bands, and the difference is not seniority in a vague sense. It is how much of the treatment you carry out without the physiotherapist present.</p>
<ul>
<li><strong>Band 2</strong> supports patients with personal care, for example helping someone change for a therapy assessment, and handles general duties such as cleaning equipment and basic administration.</li>
<li><strong>Band 3</strong> supports clinical interventions and undertakes delegated work independently following a plan, such as supporting a patient with mobility practice and exercises and issuing walking aids.</li>
<li><strong>Band 4</strong>, usually titled assistant practitioner, supports some aspects of assessment and the coordination of care, including developing and adapting a treatment plan within agreed protocols or for particular groups of patients.</li>
</ul>
<p>NHS Health Careers describes the day-to-day work as setting up equipment, showing patients how to use mobility aids, helping patients prepare for treatment and working through exercises with them. You will meet the role on acute wards, in outpatient departments, in community rehabilitation teams, in neurological and stroke services, and in private clinics and independent hospitals.</p>
<p>The CSP also notes that support workers are <strong>not a regulated profession</strong>, so there is no register to join and no protected title. The competency workbooks the CSP publishes for Band 3 and Band 4 posts are the nearest thing to a national standard, and a trust that uses them will say so in the advert.</p>

<h2>What you actually need to start</h2>
<p>The brief version of this guide that circulates online says the requirement is GCSE-level English and maths. That is one trust's advert, not a rule. The CSP is explicit: <em>"At the moment there is no national recognised entry qualification to become a support worker"</em>, though a health or social care qualification at level 2 or 3, or SVQ level 5 to 7 in Scotland, is an advantage.</p>
<p>What employers do ask for:</p>
<ul>
<li>Good numeracy and literacy. Many adverts express this as GCSEs in English and maths, or equivalent.</li>
<li>Care experience, paid or voluntary. Health Careers notes this is often requested and is an advantage even where it is not.</li>
<li>Physical fitness for moving and handling patients and equipment.</li>
<li>Willingness to work a rota, because most inpatient and many community posts include evenings, weekends or bank holidays.</li>
<li>A clear background check: DBS in England and Wales, PVG membership in Scotland, AccessNI in Northern Ireland.</li>
</ul>
<p>Expect to be enrolled on the <a href="https://www.skillsforcare.org.uk/Developing-your-workforce/Care-Certificate/Care-Certificate-standards.aspx">Care Certificate</a> when you start. It has had <strong>16 standards</strong> since March 2025, when a standard on learning disability and autism awareness was added to the original 15. Many employers expect completion within twelve weeks of induction, but that deadline is set by the employer, not by any national rule.</p>

<h2>Hours, leave and what else comes with an NHS post</h2>
<p>The standard NHS week in England is around 37.5 hours, worked as a mix of shifts that can include nights, early starts, evenings and weekends. In Scotland the standard week fell to 36 hours from 1 April 2026, which is a genuine improvement in the hourly rate rather than an accounting detail.</p>
<p>NHS terms also bring 27 days of annual leave plus bank holidays, rising with length of service, membership of the NHS pension scheme, and access to health service discounts.</p>
<p>If the post is in or near London, add the High Cost Area Supplement on top of basic pay for 2026/27:</p>
<table><thead><tr><th scope="col">Zone</th><th scope="col">Percentage of basic pay</th><th scope="col">Minimum</th><th scope="col">Maximum</th></tr></thead><tbody>
<tr><td>Inner London</td><td>20 per cent</td><td>&pound;5,794</td><td>&pound;8,746</td></tr>
<tr><td>Outer London</td><td>15 per cent</td><td>&pound;4,870</td><td>&pound;6,137</td></tr>
<tr><td>Fringe</td><td>5 per cent</td><td>&pound;1,346</td><td>&pound;2,270</td></tr>
</tbody></table>
<p>A Band 2 post in inner London is therefore worth &pound;25,272 plus the &pound;5,794 minimum supplement, because 20 per cent of &pound;25,272 falls below that floor. At the bottom of the pay structure the minimum matters more than the percentage.</p>

<img src="/public/storage/blogs/physiotherapy-assistant-jobs-uk-guide.jpg" alt="Physiotherapy department equipment and treatment area used by assistants and support workers" loading="lazy" width="1600" height="837">

<h2>How to apply, step by step</h2>
<ol>
<li><strong>Search the right portal for the nation.</strong> <a href="https://www.jobs.nhs.uk/">NHS Jobs</a> covers England, <a href="https://apply.jobs.scot.nhs.uk/">the NHS Scotland recruitment portal</a> covers Scottish health boards, and <a href="https://jobs.hscni.net/">HSC Jobs</a> covers Northern Ireland. Welsh health boards recruit through their own recruitment sites.</li>
<li><strong>Search more than one title.</strong> Physiotherapy assistant, physiotherapy support worker, therapy assistant, rehabilitation assistant and technical instructor can all describe the same post.</li>
<li><strong>Read the band first.</strong> It decides the pay, the enhancement rate and how much delegated treatment work you will do. A Band 2 and a Band 3 advert in the same department are different jobs.</li>
<li><strong>Approach trusts directly as well.</strong> The CSP notes that support worker posts are often advertised locally by the recruiting trust, and advises sending a CV and covering letter explaining your interest and relevant skills.</li>
<li><strong>Answer the person specification point by point.</strong> NHS shortlisting is scored against it. Give a concrete example for each essential criterion rather than restating the criterion back.</li>
<li><strong>Prepare for a values-based interview.</strong> Expect questions on patient dignity, working safely, teamwork and what you would do when something goes wrong.</li>
<li><strong>Allow time for pre-employment checks.</strong> References, occupational health and a background check run before a start date is confirmed.</li>
</ol>

<h2>Where the job leads</h2>
<p>This is one of the better-mapped routes in the NHS. With experience you can move to team leader, or apply to train as an assistant practitioner at Band 4. With the qualifications needed for university entry you can train as a physiotherapist, which requires a degree and registration with the Health and Care Professions Council, and which puts you on Band 5 &mdash; &pound;32,073 to &pound;39,043 in England from April 2026.</p>
<p>Some trusts fund a degree apprenticeship, which lets you keep earning while you qualify. Ask about it at interview even when the advert does not mention it.</p>

<h2>Frequently Asked Questions</h2>
<h3>How much does a physiotherapy assistant earn in the UK?</h3>
<p>From April 2026 an English NHS Band 3 pays &pound;25,760 to &pound;27,476 a year and Band 2 pays &pound;25,272. In Scotland the same bands pay &pound;29,103 to &pound;31,409 and &pound;26,696 to &pound;28,988. Private clinics set their own rates.</p>
<h3>Do I need qualifications to be a physiotherapy assistant?</h3>
<p>No. The Chartered Society of Physiotherapy records no national recognised entry qualification. Employers expect good numeracy and literacy and often ask for GCSEs in English and maths, but a health or social care qualification at level 2 or 3 is an advantage rather than a requirement.</p>
<h3>What band is a physiotherapy assistant?</h3>
<p>Usually Band 2 or Band 3, with assistant practitioner posts at Band 4. Band 2 covers personal care and general duties, Band 3 covers delegated treatment work carried out independently to a plan.</p>
<h3>Why does Scotland pay so much more for the same band?</h3>
<p>Agenda for Change is negotiated separately in each nation. Scotland settled a two-year deal with an inflation guarantee attached and also moved to a 36-hour standard week from April 2026, so both the annual salary and the hourly rate are higher than in England.</p>
<h3>Is a physiotherapy assistant the same as a physiotherapist?</h3>
<p>No. An assistant carries out work delegated by a physiotherapist. A physiotherapist assesses patients, plans treatment, holds a degree and must be registered with the Health and Care Professions Council.</p>
<h3>Do physiotherapy assistants work nights and weekends?</h3>
<p>Many do, especially on acute wards. Those hours attract percentage enhancements, and at Band 2 the enhancement is 41 per cent on Saturdays and weekday nights and 83 per cent on Sundays and public holidays, so they make a large difference to take-home pay.</p>
<h3>Can I become a physiotherapist from an assistant post?</h3>
<p>Yes. With university entry qualifications you can apply for a physiotherapy degree, and some trusts fund a degree apprenticeship so you can keep earning while you study. Assistant practitioner at Band 4 is the intermediate step.</p>
<h3>How long does the Care Certificate take?</h3>
<p>It has 16 standards and most employers expect it to be completed within twelve weeks of induction. That deadline is set by the employer, not by a national rule, and the certificate is completed while you are working and being paid.</p>

<h2>People Also Search For</h2>
<h3>Physiotherapy assistant salary NHS</h3>
<p>Band 2 and Band 3 rates, which differ by nation. Check whether a quoted figure is English or Scottish.</p>
<h3>Physiotherapy support worker jobs</h3>
<p>The same role under the title the CSP prefers. Search both when looking for vacancies.</p>
<h3>Therapy assistant jobs NHS</h3>
<p>A broader title covering physiotherapy, occupational therapy and speech and language therapy support posts.</p>
<h3>Band 3 physiotherapy assistant job description</h3>
<p>Delegated clinical work carried out independently to a plan, including mobility practice and issuing walking aids.</p>
<h3>Rehabilitation assistant jobs</h3>
<p>Common in community and neurological teams, often combining physiotherapy and occupational therapy support.</p>
<h3>How to become a physiotherapist without a degree</h3>
<p>You cannot practise without one, but an assistant post plus a funded degree apprenticeship is the usual route in.</p>
<h3>NHS band 3 salary 2026</h3>
<p>&pound;25,760 to &pound;27,476 in England, &pound;29,103 to &pound;31,409 in Scotland, both from 1 April 2026.</p>
<h3>Physiotherapy assistant interview questions</h3>
<p>Values-based, covering patient dignity, safe moving and handling, teamwork and escalating concerns.</p>

<h2>Related career guides</h2>
<ul>
<li><a href="/blog/hospital-support-worker-jobs-in-the-uk">Hospital Support Worker Jobs in the UK</a> &mdash; the same bands and enhancements on a ward instead of in a therapy department.</li>
<li><a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a> &mdash; the three job titles this role is most often confused with, correctly separated.</li>
<li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the full sponsorship answer for overseas applicants at these bands.</li>
<li><a href="/blog/physical-therapist-jobs-in-usa">Physical Therapist Jobs in USA</a> &mdash; what the qualified profession earns on the other side of the Atlantic.</li>
</ul>

<h2>Official sources and verification</h2>
<p>Checked on 28 September 2026: <a href="https://www.nhsemployers.org/articles/pay-scales-202627">NHS Employers pay scales for 2026/27</a> for the English annual, hourly and High Cost Area Supplement figures; <a href="https://www.publications.scot.nhs.uk/files/pcs2026-afc-01.pdf">NHS Circular PCS(AFC)2026/1</a> for the Scottish rates and the inflation guarantee that superseded the earlier ones; <a href="https://www.nhsemployers.org/articles/unsocial-hours-payments">NHS Employers</a> for the Section 2 unsocial hours percentages; <a href="https://www.csp.org.uk/careers-jobs/become-support-worker">the Chartered Society of Physiotherapy</a> for the band descriptions and entry qualifications; <a href="https://www.healthcareers.nhs.uk/explore-roles/healthcare-support-worker/roles-healthcare-support-worker/physiotherapy-assistantssupport-workers">NHS Health Careers</a> for duties, hours and leave; <a href="https://www.gov.uk/national-minimum-wage-rates">GOV.UK</a> for the National Living Wage; and <a href="https://www.skillsforcare.org.uk/Developing-your-workforce/Care-Certificate/Care-Certificate-standards.aspx">Skills for Care</a> for the Care Certificate standards. Application and interview suggestions are editorial guidance. Vacancies close without notice and pay scales change each April.</p>
HTML;
    }
}
