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
 * Hospital support worker careers in the UK, checked on 28 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. The brief gave Band 3 as GBP 29,061 to GBP 31,364 "April 2026 scales"
 *     for the UK. Those are NHS Scotland rates from PCS(AFC)2025/5, which
 *     PCS(AFC)2026/1 superseded when the settlement's inflation guarantee was
 *     triggered. England pays a Band 3 GBP 25,760 to GBP 27,476.
 *  2. "A Band 2 post listed GBP 25,272 to GBP 27,476" merged two bands.
 *     Band 2 in England is a single point of GBP 25,272; GBP 27,476 is the
 *     top of Band 3.
 *  3. Every Indeed UK link is gone, as is the shift-pattern guidance the
 *     brief attributed to Indeed. The pattern rules come from the Agenda for
 *     Change handbook instead.
 *  4. A single 2025 charity advert quoting GBP 13.21 an hour is dropped. The
 *     National Living Wage of GBP 12.71 from 1 April 2026 is the benchmark a
 *     reader can still use next year.
 *  5. "Not all NHS roles are eligible for visa sponsorship, so check the
 *     advert" is not the answer. Care workers and senior care workers closed
 *     to new overseas applicants on 22 July 2025; nursing auxiliaries and
 *     assistants did not. The occupation code decides it.
 *  6. The Care Certificate has had 16 standards since March 2025.
 *  7. Four FAQs and no People Also Search For block. Now eight of each.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class HospitalSupportWorkerJobsUkBlogSeeder extends Seeder
{
    public const SLUG = 'hospital-support-worker-jobs-in-the-uk';

    public const APPLY_URL = 'https://www.jobs.nhs.uk/';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'NHS Acute Trusts and Health Boards, UK (Aggregated)'],
                ['display_reference' => 'uk-hospital-support-worker-aggregated', 'type' => 'Public']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United Kingdom'],
                ['area' => 'Nationwide', 'country' => 'United Kingdom']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Hospital Support Worker / Clinical Support Worker, UK Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Around 37.5 hours a week in England, 36 in Scotland, on inpatient rotas covering days, nights, weekends and bank holidays',
                    'language' => 'English',
                    // Band 2 and Band 3 pay differently in each nation, and
                    // charities and private hospitals set their own rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'hospital support worker jobs uk, clinical support worker jobs, nhs band 2 support worker, ward support worker jobs, care support worker nhs',
                    'meta_description' => 'Hospital support worker and clinical support worker roles across NHS acute trusts and health boards in the UK. Band 2 and Band 3 ward posts explained.',
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
                    'title' => 'Hospital Support Worker Jobs in the UK',
                    'excerpt' => 'NHS Band 2 hospital support workers earn £12.92 an hour, 21p above the legal minimum. A Saturday shift pays £18.22, more than a Band 3 on a weekday, and Scotland pays the same bands nearly £4,000 more than England.',
                    'content' => $content,
                    'featured_image' => 'blogs/hospital-support-worker-jobs-uk.jpg',
                    'tags' => 'hospital support worker jobs uk, clinical support worker jobs, nhs band 2 support worker, ward support worker jobs, care support worker nhs, nhs unsocial hours pay, healthcare support worker hospital, nhs jobs support worker',
                    'meta_title' => 'Hospital Support Worker Jobs UK: Band 2 and Band 3 Pay',
                    'meta_description' => 'Hospital support worker jobs in the UK: what NHS Band 2 and Band 3 really pay from April 2026, the ward shift enhancements, and how to apply on NHS Jobs.',
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
<p><strong>Hospital support worker and clinical support worker roles across NHS acute trusts and health boards in the United Kingdom.</strong> This page is a career and job-search overview covering many employers, not an advert for one guaranteed vacancy. Use the official link to search current openings by band and location.</p>
<h3>What the work involves</h3>
<p>Helping patients with personal care and mobility, taking and recording observations, supporting rehabilitation, and keeping the ward environment safe and stocked. All of it is carried out under the direction of a registered nurse, midwife or allied health professional.</p>
<h3>Requirements</h3>
<p>Most hospital support worker posts have no formal academic entry requirement. Employers look for care experience, paid or voluntary, and for values rather than qualifications. A background check is mandatory: DBS in England and Wales, PVG in Scotland, AccessNI in Northern Ireland.</p>
<h3>Pay and working hours</h3>
<p>NHS pay follows Agenda for Change and differs by nation. From April 2026 Band 2 in England is a single point of <strong>&pound;25,272</strong> and Band 3 pays <strong>&pound;25,760 to &pound;27,476</strong>. In Scotland the same bands pay &pound;26,696 to &pound;28,988 and &pound;29,103 to &pound;31,409. Inpatient rotas attract percentage enhancements for nights, Saturdays, Sundays and bank holidays.</p>
<h3>Where to apply</h3>
<p><a href="https://www.jobs.nhs.uk/" rel="noopener">Search NHS Jobs</a> for England, <a href="https://apply.jobs.scot.nhs.uk/" rel="noopener">the NHS Scotland recruitment portal</a> for Scotland and <a href="https://jobs.hscni.net/" rel="noopener">HSC Jobs</a> for Northern Ireland. Welsh health boards recruit through their own sites. Many trusts run rolling recruitment with fixed interview dates shown on the advert.</p>
<p>Read our <a href="/blog/hospital-support-worker-jobs-in-the-uk">Hospital Support Worker Jobs in the UK guide</a> for band-by-band pay, the shift enhancement tables and the sponsorship rules. No visa sponsorship is promised by this overview.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To apply for a hospital support worker job in the UK, search NHS Jobs in England, the NHS Scotland recruitment portal, or HSC Jobs in Northern Ireland, check whether the post is Band 2 or Band 3, and write an application that answers the person specification with real examples.</strong> Most posts carry no formal academic entry requirement. The job is advertised under several names, so search healthcare support worker, clinical support worker, care support worker and ward support worker as well.</p>
<p>The pay figure attached to this role in most 2026 guides is wrong, and it is wrong in a way that will cost you if you use it to judge an offer. That comes first.</p>

<h2>What a hospital support worker is paid in 2026/27</h2>
<p>Agenda for Change is negotiated separately in each UK nation, so there is no single Band 2 or Band 3 salary. From 1 April 2026:</p>
<table><thead><tr><th scope="col">Band</th><th scope="col">England, from 1 April 2026</th><th scope="col">Scotland, from 1 April 2026</th></tr></thead><tbody>
<tr><td><strong>Band 2</strong></td><td><strong>&pound;25,272</strong>, a single point &mdash; &pound;12.92 an hour</td><td><strong>&pound;26,696 to &pound;28,988</strong> &mdash; &pound;14.22 to &pound;15.44 an hour</td></tr>
<tr><td><strong>Band 3</strong></td><td><strong>&pound;25,760 to &pound;27,476</strong> &mdash; &pound;13.17 to &pound;14.05 an hour</td><td><strong>&pound;29,103 to &pound;31,409</strong> &mdash; &pound;15.50 to &pound;16.73 an hour</td></tr>
<tr><td>Band 4</td><td>&pound;28,392 to &pound;31,157 &mdash; &pound;14.52 to &pound;15.93 an hour</td><td>&pound;31,537 to &pound;34,303 &mdash; &pound;16.80 to &pound;18.27 an hour</td></tr>
</tbody></table>
<p>English hourly rates assume a 37.5-hour week. Scottish ones assume a <strong>36-hour week</strong>, which Scotland moved to on 1 April 2026. Sources: <a href="https://www.nhsemployers.org/articles/pay-scales-202627">NHS Employers, pay scales for 2026/27</a> and <a href="https://www.publications.scot.nhs.uk/files/pcs2026-afc-01.pdf">NHS Circular PCS(AFC)2026/1</a>.</p>
<p>Two corrections follow from that table. Band 2 in England is <strong>not a range</strong>; it is one pay point, and has been since the bottom of the structure was compressed. And if you have read that Band 3 pays &pound;29,061 to &pound;31,364, that figure is Scottish and it is out of date: the two-year Scottish settlement carried a guarantee that each rise would beat CPI by a percentage point, CPI for 2025 came in at 3.4 per cent, and the rates were recalculated in January 2026. For an English applicant the gap between &pound;29,061 and the real &pound;25,760 is <strong>&pound;3,301 a year</strong>.</p>

<h2>Band 2 is 21p above the legal minimum, so the rota is the negotiation</h2>
<p>The National Living Wage for workers aged 21 and over rose to <strong>&pound;12.71</strong> an hour on 1 April 2026. An English NHS Band 2 is &pound;12.92. The entry grade on a hospital ward pays 21p an hour more than the legal floor.</p>
<p>What changes the arithmetic is unsocial hours. Section 2 of the Agenda for Change handbook in England pays:</p>
<table><thead><tr><th scope="col">Band</th><th scope="col">Saturdays and weekday nights (8pm to 6am)</th><th scope="col">Sundays and public holidays</th></tr></thead><tbody>
<tr><td>Band 1</td><td>Time plus 47 per cent</td><td>Time plus 94 per cent</td></tr>
<tr><td>Band 2</td><td>Time plus 41 per cent</td><td>Time plus 83 per cent</td></tr>
<tr><td>Band 3</td><td>Time plus 35 per cent</td><td>Time plus 69 per cent</td></tr>
<tr><td>Bands 4 to 9</td><td>Time plus 30 per cent</td><td>Time plus 60 per cent</td></tr>
</tbody></table>
<p>The enhancement falls as the band rises, and it falls faster than basic pay climbs. An English Band 2 on a Saturday is paid <strong>&pound;18.22</strong> an hour. A Band 3 at the bottom of the band on the same Saturday is paid <strong>&pound;17.78</strong>. On a Sunday the Band 2 rate is &pound;23.64 against the Band 3's &pound;22.26.</p>
<p>This is the single most useful thing to understand before accepting a hospital post. A Band 2 job on a rota with nights and weekends in it can pay more than a Band 3 job on weekdays. When you compare two adverts, compare the shift patterns, not just the band. Source: <a href="https://www.nhsemployers.org/articles/unsocial-hours-payments">NHS Employers, unsocial hours payments</a>.</p>

<img src="/public/storage/blogs/hospital-support-worker-jobs-uk-overview.jpg" alt="Hospital support worker assisting a patient on an NHS ward in the UK" loading="lazy" width="1600" height="836">

<h2>Which job is actually being advertised</h2>
<p>NHS Health Careers treats healthcare support worker as an umbrella term covering around <strong>30 different roles across seven areas</strong> of the NHS: acute, mental health, community, primary care, midwifery, children's services and learning disability. Hospital-based posts sit mostly in the first of those, but the titles do not line up neatly.</p>
<table><thead><tr><th scope="col">Search this title</th><th scope="col">Usual band</th><th scope="col">What it usually means</th></tr></thead><tbody>
<tr><td>Care support worker</td><td>Band 2</td><td>Personal care, mobility, ward environment, stock and general duties</td></tr>
<tr><td>Healthcare support worker / healthcare assistant</td><td>Band 2 to 3</td><td>The same, plus clinical observations once trained and assessed</td></tr>
<tr><td>Clinical support worker / ward support worker</td><td>Band 2 to 3</td><td>Ward-based, often with a named specialty such as surgery or critical care</td></tr>
<tr><td>Theatre, therapy or maternity support worker</td><td>Band 3 upwards</td><td>Department-specific, with delegated technical tasks</td></tr>
<tr><td>Assistant practitioner</td><td>Band 4</td><td>Delegated work with wider responsibility, usually after a foundation degree</td></tr>
</tbody></table>
<p>The line between Band 2 and Band 3 is what you are trusted to do without a registered professional present. Taking and escalating observations such as blood pressure, pulse and temperature is the duty that most often moves a post from one to the other, and it is the thing to look for in the job description.</p>

<h2>What you need to start</h2>
<p>Usually no formal academic qualifications. Trusts recruit for values and train for skills, and many say explicitly that they welcome applicants from non-clinical backgrounds. What matters:</p>
<ul>
<li>Care experience of any kind, including unpaid, voluntary or family caring.</li>
<li>Willingness to work a full rota. Inpatient wards run day and night patterns; outpatient clinics and community teams are more likely to be daytime only.</li>
<li>Readiness to give personal care with dignity, and the communication to do it well.</li>
<li>A satisfactory background check: DBS in England and Wales, PVG membership in Scotland, AccessNI in Northern Ireland.</li>
</ul>
<p>You will be enrolled on the <a href="https://www.skillsforcare.org.uk/Developing-your-workforce/Care-Certificate/Care-Certificate-standards.aspx">Care Certificate</a> at induction. It has had <strong>16 standards</strong> since March 2025, when learning disability and autism awareness was added to the original 15. Employers commonly expect completion within twelve weeks, but that is an employer deadline rather than a national rule, and you complete it on paid time.</p>
<p>NHS terms bring 27 days of annual leave plus bank holidays, rising with service, the NHS pension scheme, and the High Cost Area Supplement in and around London: 20 per cent in inner London with a minimum of &pound;5,794, 15 per cent in outer London with a minimum of &pound;4,870, and 5 per cent on the fringe with a minimum of &pound;1,346. At Band 2 the minimum payment is worth more than the percentage, because 20 per cent of &pound;25,272 is below the inner London floor.</p>

<h2>Sponsorship: the occupation code decides it, not the advert</h2>
<p>The brief version of this advice says to check whether the advert offers sponsorship. That is not where the answer lives. On <strong>22 July 2025</strong> the UK closed the care worker and senior care worker route, occupation codes 6135 and 6136, to new applications from overseas. In-country switching stays open until 2028 for people already working in adult social care.</p>
<p>Hospital support worker posts are usually coded as <strong>6131, nursing auxiliaries and assistants</strong>, which remains on the list of occupations eligible for a new Health and Care Worker visa application. So the same person can be sponsored for a ward post in an NHS hospital and refused for an almost identical role in a care home, because the codes differ.</p>
<p>That is the short version. Our <a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> guide sets out the salary thresholds, the sponsor licence checks and what to do if an employer will not confirm the code. Sources: <a href="https://www.gov.uk/health-care-worker-visa/your-job">GOV.UK, Health and Care Worker visa eligible jobs</a> and <a href="https://www.gov.uk/government/news/overseas-recruitment-for-care-workers-to-end">GOV.UK on the end of overseas care worker recruitment</a>.</p>

<img src="/public/storage/blogs/hospital-support-worker-jobs-uk-guide.jpg" alt="NHS hospital ward corridor and equipment where support workers are based" loading="lazy" width="1600" height="836">

<h2>How to apply, step by step</h2>
<ol>
<li><strong>Use the portal for the nation.</strong> <a href="https://www.jobs.nhs.uk/">NHS Jobs</a> for England, <a href="https://apply.jobs.scot.nhs.uk/">the NHS Scotland recruitment portal</a> for Scottish health boards, <a href="https://jobs.hscni.net/">HSC Jobs</a> for Northern Ireland. Welsh health boards recruit through their own sites.</li>
<li><strong>Search four or five titles.</strong> Healthcare support worker, care support worker, healthcare assistant, clinical support worker and ward support worker return different results on the same portal.</li>
<li><strong>Read the band and the rota before the salary.</strong> Together they decide what you will actually be paid, and the job description tells you whether observations are included.</li>
<li><strong>Watch for rolling recruitment.</strong> Many trusts advertise support worker posts continuously and interview on fixed dates, which are printed on the advert. Applying a week early can mean waiting two months.</li>
<li><strong>Answer the person specification line by line.</strong> Shortlisting is scored against it. One concrete example per essential criterion beats a general statement about being caring.</li>
<li><strong>Prepare for a values-based interview.</strong> Dignity, compassion, teamwork, working safely and what you do when you notice something wrong.</li>
<li><strong>Allow for pre-employment checks.</strong> References, occupational health and the background check run before a start date is agreed, and they take weeks rather than days.</li>
</ol>

<h2>Where it leads</h2>
<p>A hospital support worker post is the standard way into the clinical professions for people without a degree. Band 3 with observations leads to assistant practitioner at Band 4, then to the nursing associate route, then to a registered nursing degree apprenticeship. Trusts fund these places, and the advert will not always mention them, so ask at interview. A registered nurse starts on Band 5, &pound;32,073 in England from April 2026.</p>
<p>Charities and private hospitals recruit for the same work. They set their own rates, which are not bound by Agenda for Change but are bound by the National Living Wage of &pound;12.71. Compare a private offer against the NHS package as a whole, including the pension, the leave and the unsocial hours enhancements, rather than against the basic hourly rate alone.</p>

<h2>Frequently Asked Questions</h2>
<h3>How much do hospital support workers earn in the UK?</h3>
<p>From April 2026 an English NHS Band 2 is &pound;25,272 a year and Band 3 is &pound;25,760 to &pound;27,476. In Scotland the same bands pay &pound;26,696 to &pound;28,988 and &pound;29,103 to &pound;31,409. Unsocial hours enhancements are paid on top.</p>
<h3>Can I become a hospital support worker with no experience?</h3>
<p>Often yes. Most posts carry no formal academic entry requirement and trusts train on the job. Caring experience of any kind, including unpaid and voluntary work, strengthens an application considerably.</p>
<h3>What is the difference between Band 2 and Band 3?</h3>
<p>What you are trusted to do unsupervised. Band 2 covers personal care, mobility and ward duties. Band 3 adds delegated clinical tasks, most commonly taking and escalating observations, after training and assessment.</p>
<h3>Do hospital support workers get paid more for nights and weekends?</h3>
<p>Yes, and the enhancement is largest at the lowest bands. A Band 2 is paid time plus 41 per cent on Saturdays and weekday nights and time plus 83 per cent on Sundays and public holidays, which takes an English Band 2 Saturday rate to &pound;18.22 an hour.</p>
<h3>Can a hospital support worker be sponsored from abroad?</h3>
<p>It depends on the occupation code. Nursing auxiliaries and assistants, code 6131, remain eligible for a new Health and Care Worker visa. Care workers and senior care workers, codes 6135 and 6136, closed to new overseas applications on 22 July 2025.</p>
<h3>What is the difference between a support worker and a nurse?</h3>
<p>A support worker delivers care under the direction of a registered professional. A nurse holds a degree and professional registration, assesses patients and is accountable for planning their care.</p>
<h3>How long does the Care Certificate take?</h3>
<p>It has 16 standards and most employers expect it within twelve weeks of induction. It is completed during paid working time, and the twelve weeks is an employer expectation rather than a legal deadline.</p>
<h3>What shifts do hospital support workers work?</h3>
<p>Inpatient wards run day and night patterns including weekends and bank holidays. Outpatient clinics, day units and community teams are more likely to be daytime only. Every advert states the pattern, and it is worth more than the band in pay terms.</p>

<h2>People Also Search For</h2>
<h3>NHS band 2 salary 2026</h3>
<p>&pound;25,272 in England as a single pay point, &pound;26,696 to &pound;28,988 in Scotland.</p>
<h3>Healthcare support worker jobs near me</h3>
<p>Search your nation's NHS portal by postcode, then compare the rota as well as the band.</p>
<h3>Clinical support worker job description</h3>
<p>Ward-based personal care and mobility support, usually with observations attached at Band 3.</p>
<h3>NHS unsocial hours pay calculator</h3>
<p>Band 2 is time plus 41 per cent on Saturdays and nights, time plus 83 per cent on Sundays and bank holidays.</p>
<h3>Care support worker NHS band 2</h3>
<p>The most common entry title in acute trusts, with no formal academic entry requirement.</p>
<h3>Hospital jobs with no experience UK</h3>
<p>Support worker, domestic, portering and ward clerk posts are the usual routes in without qualifications.</p>
<h3>NHS values based interview questions</h3>
<p>Dignity, compassion, teamwork, safe working and raising concerns. Answer with examples, not adjectives.</p>
<h3>Healthcare assistant visa sponsorship UK</h3>
<p>Possible under code 6131 in the NHS, closed under codes 6135 and 6136 in adult social care.</p>

<h2>Related career guides</h2>
<ul>
<li><a href="/blog/physiotherapy-assistant-jobs-in-the-uk">Physiotherapy Assistant Jobs in the UK</a> &mdash; the same bands in a therapy department, where Band 3 means delegated treatment work.</li>
<li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the full sponsorship answer, salary thresholds and sponsor licence checks.</li>
<li><a href="/blog/nursing-assistant-jobs-in-the-uk">Nursing Assistant Jobs in the UK</a> &mdash; the three job titles separated, and the route on to nursing.</li>
<li><a href="/blog/healthcare-support-jobs-in-uk">Healthcare Support Jobs in UK</a> &mdash; the wider family of support roles across all seven NHS areas.</li>
</ul>

<h2>Official sources and verification</h2>
<p>Checked on 28 September 2026: <a href="https://www.nhsemployers.org/articles/pay-scales-202627">NHS Employers pay scales for 2026/27</a> for the English annual, hourly and High Cost Area Supplement figures; <a href="https://www.publications.scot.nhs.uk/files/pcs2026-afc-01.pdf">NHS Circular PCS(AFC)2026/1</a> for the Scottish rates that replaced the earlier ones; <a href="https://www.nhsemployers.org/articles/unsocial-hours-payments">NHS Employers</a> for the Section 2 unsocial hours percentages; <a href="https://www.healthcareers.nhs.uk/explore-roles/healthcare-support-worker/roles-healthcare-support-worker">NHS Health Careers</a> for the 30 roles across seven areas; <a href="https://www.gov.uk/health-care-worker-visa/your-job">GOV.UK</a> for visa eligibility by occupation code; <a href="https://www.gov.uk/national-minimum-wage-rates">GOV.UK</a> for the National Living Wage; and <a href="https://www.skillsforcare.org.uk/Developing-your-workforce/Care-Certificate/Care-Certificate-standards.aspx">Skills for Care</a> for the Care Certificate standards. Application and interview suggestions are editorial guidance. Vacancies close without notice and pay scales change each April.</p>
HTML;
    }
}
