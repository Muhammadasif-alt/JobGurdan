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
 * Clinic receptionist careers in the USA, checked against BLS on 28 September 2026.
 *
 * Corrections applied to the first published version of this guide:
 *  1. The outlook said a "2% decline". BLS projects -1.7% for 2025-35
 *     (947,500 to 931,600), so the figure overstated the fall.
 *  2. A "$19.00 healthcare and social assistance median" could not be found in
 *     any BLS series. Replaced with the industry rows BLS does publish, which
 *     turn out to disagree with each other by more than $2 an hour.
 *  3. A single Kaiser requisition number was published with its hourly rate.
 *     The number is gone; the location-premium point it illustrated is kept
 *     with its date attached, so the page does not rot when the advert closes.
 *  4. Six FAQs and no People Also Search For block. Now eight of each.
 *  5. Artwork shipped as three 1.7 MB PNGs. Re-encoded as JPEG, 5.2 MB to
 *     692 KB across the page.
 *  6. firstOrCreate meant a published mistake could never be corrected by
 *     re-seeding. Now updateOrCreate, like every sibling guide.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class ClinicReceptionistJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'clinic-receptionist-jobs-in-usa';

    public const APPLY_URL = 'https://www.kaiserpermanentejobs.org/';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Clinics, Medical Practices & Health Systems (Aggregated)'],
                ['display_reference' => 'us-clinic-receptionist-aggregated', 'type' => 'Private']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                ['position' => 'Clinic Receptionist / Medical Front Desk, US Employers', 'advertiser_id' => $advertiser->id],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Schedules vary by employer; check full-time, part-time, evening and on-call requirements',
                    'language' => 'English',
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'clinic receptionist jobs usa, medical receptionist jobs, healthcare front desk jobs, patient services representative',
                    'meta_description' => 'Clinic receptionist and medical front desk roles across US employers. Compare duties, entry requirements and official employer application routes.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'visa-sponsorship'],
                ['name' => 'Visa Sponsorship', 'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.']
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Clinic Receptionist Jobs in USA',
                    'excerpt' => 'Clinic reception is not one pay band: hospitals pay $19.35 an hour and nursing homes $17.23, against an $18.27 national median. Compare the settings, then apply on the employer\'s own careers site.',
                    'content' => $content,
                    'featured_image' => 'blogs/clinic-receptionist-jobs-usa.jpg',
                    'tags' => 'clinic receptionist jobs usa, medical receptionist jobs, healthcare front desk jobs, patient services representative, receptionist salary usa',
                    'meta_title' => 'Clinic Receptionist Jobs USA 2026: Pay and How to Apply',
                    'meta_description' => 'Clinic receptionist jobs in the USA: the $18.27 BLS median, why a hospital front desk pays more than a doctors office, and how to apply direct to employers.',
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
<p><strong>Clinic receptionist and medical front desk roles across the United States.</strong> This page is a career and job-search overview covering many employers, not an advert for one guaranteed vacancy. Use the official careers link to search current openings by location.</p>
<h3>What the work involves</h3>
<p>Patient reception, appointment administration, telephone enquiries and accurate data entry. Billing, insurance and other responsibilities depend on the clinic and the specific role.</p>
<h3>Requirements</h3>
<p>BLS records a high school diploma or equivalent as the typical entry requirement for receptionists, with short-term on-the-job training and no prior work experience. Individual employers can and do ask for more, so check each advert's minimum education, experience and software requirements.</p>
<h3>Pay and working hours</h3>
<p>Pay and schedules are set by each employer. Receptionists had a median of <strong>$18.27 an hour</strong> nationally in May 2025, but the figure moves by setting: $19.35 in hospitals, $17.23 in nursing and residential care. These are benchmarks, not an advertised salary. Check guaranteed hours, shift patterns and benefits before accepting an offer.</p>
<h3>Where to apply</h3>
<p><a href="https://www.kaiserpermanentejobs.org/" rel="noopener">Search Kaiser Permanente Careers</a> using receptionist or patient services and your preferred location. Review the requirements on the current advert and apply through the employer. Private practices and other health systems also publish roles on their own careers websites.</p>
<p>Read our <a href="/blog/clinic-receptionist-jobs-in-usa">Clinic Receptionist Jobs in USA guide</a> for pay by setting, application steps and official sources. No visa sponsorship is promised by this overview.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To apply for a clinic receptionist job in the USA, find a vacancy on the employer's own careers website, check its experience and shift requirements, and submit a resume that shows your customer service and office skills.</strong> Search several titles: clinic receptionist, medical receptionist, patient services representative and front desk coordinator. Read the duties before applying, because similar titles can describe very different work.</p>
<p>The national pay figures below are benchmarks, not a salary offer for every clinic. They are also more varied than most guides admit, which is where this one starts.</p>

<h2>Clinic receptionist salary in the USA</h2>
<p>"Healthcare reception" is not one pay band. The Bureau of Labor Statistics publishes receptionist wages by industry, and the settings most people think of as clinics sit on opposite sides of the national median.</p>
<table><thead><tr><th scope="col">Where the receptionist works</th><th scope="col">Median hourly</th><th scope="col">Median annual</th><th scope="col">Employed</th></tr></thead><tbody>
<tr><td>Ambulatory health care services</td><td>$19.54</td><td>$40,640</td><td>301,830</td></tr>
<tr><td>Hospitals</td><td>$19.35</td><td>$40,240</td><td>35,630</td></tr>
<tr><td>Offices of physicians</td><td>$19.01</td><td>$39,550</td><td>135,030</td></tr>
<tr><td>Offices of other health practitioners</td><td>$18.30</td><td>$38,060</td><td>60,490</td></tr>
<tr><td><strong>All receptionists, all industries</strong></td><td><strong>$18.27</strong></td><td><strong>$38,010</strong></td><td><strong>910,180</strong></td></tr>
<tr><td>Nursing and residential care facilities</td><td>$17.23</td><td>$35,830</td><td>41,230</td></tr>
</tbody></table>
<p>Across all industries the lowest 10 per cent of receptionists earned under <strong>$13.83</strong> an hour and the highest 10 per cent over <strong>$24.01</strong>. Source: <a href="https://www.bls.gov/ooh/office-and-administrative-support/receptionists.htm">BLS Receptionists, Occupational Outlook Handbook</a> and the May 2025 OEWS industry estimates, checked 28 September 2026.</p>
<p>Two things follow. A nursing home front desk pays about <strong>$2.31 an hour less</strong> than an ambulatory clinic for work that reads almost identically on paper, which is roughly $4,800 a year. And the gap between the tenth and ninetieth percentile is wider than the gap between any two of these settings, so location and employer matter more than the job title. Compare an offer using its city, scheduled hours and benefits as well as the hourly rate, and ask whether the hours are guaranteed.</p>

<h2>What does a clinic receptionist do?</h2>
<p>Front-desk work typically includes greeting patients, managing calls, arranging appointments and keeping records organised. Healthcare reception adds patient details, insurance verification and payment handling. Duties vary by employer.</p>
<p>When reading a vacancy, separate reception duties from clinical ones. If an advert includes taking vital signs or assisting with procedures, the employer is recruiting a medical assistant rather than a receptionist, and the pay and training expectations are different.</p>
<figure><img src="/public/storage/blogs/clinic-receptionist-jobs-usa-overview.jpg" alt="Clinic receptionist jobs USA illustration showing a front-desk worker helping a patient" width="1734" height="907" loading="lazy"><figcaption>Illustrative artwork supplied for this guide; working conditions and benefits depend on the employer.</figcaption></figure>

<h2>Education and experience: can you start without a degree?</h2>
<p>BLS records the typical entry requirement as a <strong>high school diploma or equivalent</strong>, with <strong>no prior work experience</strong> and <strong>short-term on-the-job training</strong>. That is the occupation-wide picture, not a promise about any one advert: an individual clinic can still ask for six months of reception experience or familiarity with a particular scheduling system.</p>
<p>Make a checklist from the advert's required and preferred sections. Next to each requirement, write one truthful example from your own experience. Retail, hospitality and office work often supply good examples of handling enquiries, booking appointments or taking payments; the employer decides whether that counts.</p>

<h2>Why one advert can pay far above the median</h2>
<p>In September 2026 Kaiser Permanente was advertising on-call reception work at a medical center in Wailuku, Hawaii at <strong>$25.87 an hour</strong> - about 42 per cent above the national median, for a role asking six months of clerical experience and a diploma. That is a real rate from an employer's own careers site, and it is exactly why a median should never be read as an offer.</p>
<p>It is also a warning. The premium was attached to a high-cost island location and to <em>on-call</em> hours, which carry no guaranteed weekly pay. An advertised rate always has conditions underneath it. Individual adverts expire within weeks, so use the stable careers hub rather than chasing a specific listing.</p>
<p><a href="https://www.kaiserpermanentejobs.org/" rel="noopener"><strong>Search current roles on Kaiser Permanente Careers</strong></a>, or read the <a href="/jobs/clinic-receptionist-medical-front-desk-us-employers-united-states">JobGader job summary</a>. Other large systems publish the same way; try the careers site of any hospital group in your own state.</p>

<h2>How to apply: a practical checklist</h2>
<ol>
<li><strong>Choose your location and availability.</strong> Decide your commuting limit and which shifts you can realistically accept, including evenings and on-call.</li>
<li><strong>Search the employer's careers website.</strong> Use related titles and location filters. Save the vacancy number so you can find the exact role again.</li>
<li><strong>Check the minimum requirements.</strong> Read education, experience and schedule details before spending time on the application.</li>
<li><strong>Tailor your resume.</strong> Put relevant reception, scheduling, telephone and customer service experience near the top. Use the employer's terminology only where it accurately describes what you did.</li>
<li><strong>Submit through the official application page.</strong> Review contact details and attachments, then keep the confirmation and a copy of the advert.</li>
<li><strong>Prepare for the interview.</strong> Practise explaining how you would handle a waiting patient while another caller needs help, and when you would ask a supervisor to step in.</li>
</ol>

<h2>Resume tips for healthcare front-desk applications</h2>
<p>Replace vague claims such as "excellent people skills" with a short example of what you did. If accurate, describe the type of enquiries you handled, the appointment system you used or how you checked information for errors. Never invent patient volumes, software experience or qualifications.</p>
<p>A sample bullet to adapt truthfully: "Scheduled appointments, confirmed contact details and passed urgent enquiries to the appropriate team." Add a number only if you can support it. For a first healthcare application, explain your transferable skills without claiming medical experience you do not have.</p>
<figure><img src="/public/storage/blogs/clinic-receptionist-jobs-usa-guide.jpg" alt="Clinic receptionist jobs USA illustration showing telephone work at a medical reception desk" width="1734" height="907" loading="lazy"><figcaption>Use concrete examples of communication and organisation in your application.</figcaption></figure>

<h2>Job outlook: the openings come from turnover, not growth</h2>
<p>BLS projects receptionist employment to fall <strong>1.7 per cent</strong> between 2025 and 2035, from 947,500 to 931,600 - a loss of about 16,000 jobs, against growth of 3.5 per cent across all occupations. Automated check-in and online booking explain most of it.</p>
<p>Despite that, BLS still expects about <strong>105,100 openings a year</strong>. Almost all of them come from people leaving the occupation rather than from new posts being created, which is why the hiring does not feel like a shrinking field from the inside. It does mean employers are replacing leavers rather than expanding teams, so a decisive, well-targeted application matters more than volume.</p>
<p>These are occupation-wide projections, not a live count of clinic vacancies. Use active employer advertisements to judge opportunities in your own area.</p>

<h2>Frequently Asked Questions</h2>
<h3>How much does a clinic receptionist earn in the USA?</h3>
<p>The national median for receptionists was $18.27 an hour, or $38,010 a year, in May 2025. In ambulatory health care it was $19.54 and in nursing and residential care $17.23, so the setting matters.</p>
<h3>Can I become a clinic receptionist without experience?</h3>
<p>BLS records no prior work experience as the typical requirement, with short-term on-the-job training. Individual clinics still set their own bar, so read each advert rather than assuming.</p>
<h3>How do I find clinic receptionist jobs near me?</h3>
<p>Search employer careers pages with your city and terms such as medical receptionist, patient services representative and clinic front desk. Compare duties and working hours before applying.</p>
<h3>Is the national median a guaranteed starting wage?</h3>
<p>No. A median describes a whole group of workers. Half earn less. Your advertised rate and final offer depend on the employer, the setting and the location.</p>
<h3>Are all clinic receptionist jobs full time?</h3>
<p>No. Read the schedule carefully. On-call and part-time reception roles are common, and a high hourly rate on an on-call advert is not a promise of full-time hours.</p>
<h3>Do I need to know medical terminology?</h3>
<p>It is usually listed as preferred rather than required for reception roles. Employers more often require accurate data entry and clear telephone communication.</p>
<h3>Is a clinic receptionist the same as a medical records clerk?</h3>
<p>No. A front-desk role centres on reception and patient contact. A records role focuses on health information and, once certified, pays a higher median. Compare the actual duties rather than the title.</p>
<h3>Does this guide promise visa sponsorship?</h3>
<p>No. Nothing here establishes that sponsorship is available for these roles. Check the employer's eligibility information on the particular vacancy.</p>

<h2>People Also Search For</h2>
<h3>Clinic receptionist salary</h3>
<p>$18.27 an hour nationally in May 2025, from under $13.83 at the tenth percentile to over $24.01 at the ninetieth.</p>
<h3>Medical receptionist jobs near me</h3>
<p>Best found on hospital-group and practice careers pages by city, rather than by a nationwide search count.</p>
<h3>Patient services representative</h3>
<p>A common employer title for the same front-desk work, often with added insurance and registration duties.</p>
<h3>Front desk coordinator jobs</h3>
<p>Usually a senior reception title covering scheduling and workflow for a whole clinic team.</p>
<h3>Receptionist job outlook</h3>
<p>A 1.7 per cent decline to 2035, but roughly 105,100 openings a year from turnover.</p>
<h3>Kaiser Permanente careers</h3>
<p>One of the larger US health systems publishing reception roles, with hourly rates shown on each advert.</p>
<h3>Medical terminology for receptionists</h3>
<p>Preferred rather than required in most adverts; useful for moving into records or medical assisting later.</p>
<h3>Entry level healthcare jobs no experience</h3>
<p>Reception is one of the few healthcare roles BLS lists with no experience requirement at entry.</p>

<h2>Related career guides</h2>
<ul>
<li><a href="/blog/medical-records-clerk-jobs-in-usa">Medical Records Clerk Jobs in USA</a> &mdash; the same clinic's back office, where a $199 exam lifts the median to $51,140.</li>
<li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a> &mdash; the clinical role that front-desk adverts are sometimes quietly describing.</li>
<li><a href="/blog/entry-level-healthcare-jobs">Entry Level Healthcare Jobs</a> &mdash; how reception compares with the other no-experience ways in.</li>
<li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the same office skills outside healthcare.</li>
</ul>
<h2>Official sources and verification</h2>
<p>Checked on 28 September 2026: <a href="https://www.bls.gov/ooh/office-and-administrative-support/receptionists.htm">BLS: Receptionists</a> for national wages, typical education and the 2025-35 projection; the May 2025 OEWS national industry estimates for the pay-by-setting table; and <a href="https://www.kaiserpermanentejobs.org/">Kaiser Permanente Careers</a> for the September 2026 employer observation. Application and resume suggestions are editorial guidance. Employer vacancies close without notice.</p>
HTML;
    }
}
