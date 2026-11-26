<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Database\Seeder;

/**
 * "Jobs in Pakistan 2026 by City and Province: Latest Vacancies, Eligibility, Last Date and How to Apply"
 *
 * One guide built from two overlapping owner briefs ("Job Search By City In Pakistan 2026" and "Jobs In Pakistan
 * 2026"), so the site does not carry two near-identical posts. Checked on 26 November 2026 against each commission's
 * own site. What changed against the briefs:
 *
 * 1. The briefs listed no live vacancy. Added the open recruitments by province: PPSC Advt 12/2026 (closes 30 Nov),
 *    SPSC Consolidated Advt 07/2026 (14 Dec), KPPSC Advt 10/2026 dated 25 Nov (17 Dec, 5 PM, fee Rs 500, 49 Sub
 *    Accountant posts), BPSC (all exams in Quetta, online fee through 1Link since 5 May 2026) and FPSC.
 * 2. City angle made concrete: Rescue 1122 Punjab's own district split (14 Nov 2026 advert) and the Railways
 *    apprentice test centres (Lahore, Rawalpindi, Multan).
 * 3. The AIPRM footer, affiliate links and generic city descriptions without facts are dropped.
 *
 * Posters are the owner's (blog-363, 24 Nov 4.04.40, 4.05, 4.06, 4.14 and 4.15 PM). updateOrCreate, so re-running is safe.
 */
class JobsInPakistan2026ByCityBlogSeeder extends Seeder
{
    public const SLUG = 'jobs-in-pakistan-2026-by-city';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'title' => 'Jobs in Pakistan 2026 by City and Province: Latest Vacancies, Eligibility, Last Date and How to Apply',
                'excerpt' => 'Which recruitment body covers your city, the government jobs open in Punjab, Sindh, KP, Balochistan and federal posts in late 2026, Rescue 1122 posts district by district, and how to apply without falling for fake adverts.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-in-pakistan-2026-by-city.jpg',
                'tags' => 'jobs in pakistan 2026, job search by city pakistan, jobs in lahore 2026, jobs in karachi 2026, jobs in peshawar 2026, kppsc jobs 2026, government jobs pakistan last date, rescue 1122 jobs district wise',
                'meta_title' => 'Jobs in Pakistan 2026 by City and Province: Last Dates',
                'meta_description' => 'Government jobs open across Pakistan in late 2026 by city and province: PPSC, SPSC, KPPSC, BPSC, FPSC and Rescue 1122 posts, with eligibility and last dates.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>The quickest way to search for jobs by city in Pakistan is to start from the recruitment body that covers your city, then filter by your qualification and domicile.</strong> Government posts in Lahore, Faisalabad, Multan and Rawalpindi mostly go through the Punjab Public Service Commission or Punjab departments. Karachi and Hyderabad go through the Sindh commission, Peshawar through KPPSC and Quetta through BPSC. Federal posts in Islamabad and nationwide go through FPSC or the National Job Portal. We checked each commission's own website on 26 November 2026. Open now are <strong>PPSC's CCE-2026</strong> (until <strong>30 November</strong>), <strong>SPSC Advertisement 07/2026</strong> (until <strong>14 December</strong>) and <strong>KPPSC Advertisement 10/2026</strong> (until <strong>17 December</strong>, including <strong>49 Sub Accountant posts</strong>), plus Rescue 1122 posts listed district by district across Punjab. This guide lists them by province and city, and explains how to apply and how to spot fake adverts.</p>

<h2>Which Recruitment Body Covers Your City</h2>

<table style="width:100%;border-collapse:collapse;margin:20px 0;">
<thead>
<tr><th style="border:1px solid #ddd;padding:8px;text-align:left;">City</th><th style="border:1px solid #ddd;padding:8px;text-align:left;">Provincial commission</th><th style="border:1px solid #ddd;padding:8px;text-align:left;">Usual domicile rule</th></tr>
</thead>
<tbody>
<tr><td style="border:1px solid #ddd;padding:8px;">Lahore, Faisalabad, Multan, Rawalpindi, Gujranwala, Sialkot</td><td style="border:1px solid #ddd;padding:8px;">Punjab Public Service Commission (ppsc.gop.pk)</td><td style="border:1px solid #ddd;padding:8px;">Punjab domicile for provincial posts</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Karachi, Hyderabad, Sukkur</td><td style="border:1px solid #ddd;padding:8px;">Sindh Public Service Commission, Hyderabad (spsc.gov.pk)</td><td style="border:1px solid #ddd;padding:8px;">Sindh domicile, split urban and rural</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Peshawar, Mardan, Abbottabad, Swat</td><td style="border:1px solid #ddd;padding:8px;">Khyber Pakhtunkhwa Public Service Commission (kppsc.gov.pk)</td><td style="border:1px solid #ddd;padding:8px;">KP domicile, with zonal allocation</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Quetta and Balochistan districts</td><td style="border:1px solid #ddd;padding:8px;">Balochistan Public Service Commission (bpsc.gob.pk)</td><td style="border:1px solid #ddd;padding:8px;">Balochistan domicile, often district-wise</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Islamabad and federal posts anywhere</td><td style="border:1px solid #ddd;padding:8px;">Federal Public Service Commission (fpsc.gov.pk) and the National Job Portal (njp.gov.pk)</td><td style="border:1px solid #ddd;padding:8px;">Provincial quotas apply to federal posts</td></tr>
</tbody>
</table>

<p>Two points trip people up. The office that advertises a job is not always where you will work: PPSC posts say "anywhere in Punjab", and KPPSC splits posts by zone. And a federal job in Islamabad is usually shared out by provincial quota, so your domicile still matters even for federal posts.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-pakistan-2026-by-city-2.jpg"
         alt="Young woman in a green shalwar kameez searching jobs on a laptop beside landmarks of Faisalabad, Lahore, Karachi and Islamabad, Job Search by City in Pakistan 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Government Jobs Open Now, by Province (Checked 26 November 2026)</h2>

<h3>Punjab: PPSC Combined Competitive Examination 2026</h3>

<p>PPSC Advertisement No. 12/2026 is for <strong>48 BS-17 posts</strong>: 45 Provincial Management Service, 1 Excise and Taxation Officer and 2 Assistant Director Local Fund Audit. Applicants need a <strong>graduate degree in second division</strong> and a Punjab domicile, and must be aged 21 to 32 on 1 January 2027 (raised from 30 for the 2026 and 2027 exams). The fee is Rs 1,000, applications are online only, and the closing date is <strong>30 November 2026</strong>. Our <a href="/blog/entry-level-no-experience-jobs-pakistan-2026">entry-level jobs guide</a> covers the exam papers and pass marks.</p>

<h3>Sindh: SPSC Consolidated Advertisement 07/2026</h3>

<p>The Sindh Public Service Commission's advertisement of 12 November 2026 closes on <strong>14 December 2026</strong>, with the portal shutting at 5 PM on the last day. It includes Assistant (BS-16) posts in Karachi offices that need a bachelor's in second division and an MS Office or computer certificate, with an age limit of 18 to 28 or 21 to 28. It also includes Stenographers, Assistant Directors (Technical) at the coal mines directorates in Karachi and Jamshoro, and professional posts in Karachi, Mithi and Hyderabad.</p>

<h3>Khyber Pakhtunkhwa: KPPSC Advertisement 10/2026</h3>

<p>KPPSC published Advertisement No. 10/2026 on 25 November 2026. It is open to Pakistani citizens with a KP domicile until <strong>17 December 2026 at 5:00 PM</strong>. The fee is <strong>Rs 500</strong>, paid through the 1Bill option in banking apps or Easypaisa and JazzCash. Among the posts:</p>

<ul>
<li><strong>49 Sub Accountant (Treasuries and Accounts), BPS-16</strong>, Finance Department: at least a second-class bachelor's in commerce or business administration, or ACMA or MBA; age 21 to 35; male and female. The posts are split across zones, with 6 for women, 3 for minorities and 1 for disabled candidates.</li>
<li><strong>21 Sub Divisional Forest Officer, BPS-17</strong>: a second-class M.Sc or four-year BS in forestry; age 21 to 32.</li>
<li><strong>17 Sub Divisional Wildlife Officer, BPS-17</strong>: a master's in wildlife, forestry or national park management, with zoology, botany or veterinary degrees considered only if no such candidate applies; age 21 to 32.</li>
<li>Veterinary Officer, GIS Specialist and a female Librarian post on the minority quota.</li>
</ul>

<p>Apply at <a href="https://www.kppsc.gov.pk/" rel="noopener">kppsc.gov.pk</a>. KPPSC also runs computer-based tests on a monthly schedule, which it publishes on the same site.</p>

<h3>Balochistan: BPSC</h3>

<p>The Balochistan Public Service Commission takes applications through its website or by post, as each advertisement states. Since 5 May 2026 it has accepted fees online through 1Link banking channels around the clock. BPSC's notice says all its exams are currently held <strong>in Quetta only</strong>, so candidates from other districts should choose Quetta as their test centre and plan the travel. Complete your BPSC profile, with degrees and mark sheets uploaded, before an advertisement closes.</p>

<h3>Federal: FPSC and the National Job Portal</h3>

<p>FPSC runs the CSS competitive examination and General Recruitment (GR) for federal posts. In late November 2026 it was holding CE-2026 viva voce interviews and had revised the schedule for Phase-IV GR tests, so check its Jobs page for new GR advertisements. Many federal departments, including Pakistan Railways for its Balochistan-quota posts, now take applications on the National Job Portal (<a href="https://www.njp.gov.pk/" rel="noopener">njp.gov.pk</a>) instead of by post.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-pakistan-2026-by-city-3.jpg"
         alt="Magnifying glass over a JOBS briefcase icon beside a Pakistan map, Job Search by City in Pakistan 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Rescue 1122 Posts by City in Punjab</h2>

<p>Rescue 1122's advertisement of 14 November 2026 is a rare case where the official notice splits posts by district, so you can see exactly what is open near you. It covers 32 Emergency Medical Technicians (EMT), 140 Fire DERT Rescuers (FDR) and 113 Rescue Drivers (LTV). The districts with the most posts are:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;">
<thead>
<tr><th style="border:1px solid #ddd;padding:8px;text-align:left;">District</th><th style="border:1px solid #ddd;padding:8px;text-align:left;">EMT</th><th style="border:1px solid #ddd;padding:8px;text-align:left;">FDR</th><th style="border:1px solid #ddd;padding:8px;text-align:left;">LTV driver</th></tr>
</thead>
<tbody>
<tr><td style="border:1px solid #ddd;padding:8px;">Gujrat</td><td style="border:1px solid #ddd;padding:8px;">4</td><td style="border:1px solid #ddd;padding:8px;">19</td><td style="border:1px solid #ddd;padding:8px;">12</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Gujranwala</td><td style="border:1px solid #ddd;padding:8px;">2</td><td style="border:1px solid #ddd;padding:8px;">11</td><td style="border:1px solid #ddd;padding:8px;">17</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Chakwal</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">24</td><td style="border:1px solid #ddd;padding:8px;">6</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Sargodha</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">25</td><td style="border:1px solid #ddd;padding:8px;">2</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Khushab</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">18</td><td style="border:1px solid #ddd;padding:8px;">7</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Sialkot</td><td style="border:1px solid #ddd;padding:8px;">4</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">18</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Narowal</td><td style="border:1px solid #ddd;padding:8px;">4</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">18</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Jhelum</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">12</td><td style="border:1px solid #ddd;padding:8px;">8</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Rawalpindi</td><td style="border:1px solid #ddd;padding:8px;">2</td><td style="border:1px solid #ddd;padding:8px;">3</td><td style="border:1px solid #ddd;padding:8px;">12</td></tr>
<tr><td style="border:1px solid #ddd;padding:8px;">Bahawalpur</td><td style="border:1px solid #ddd;padding:8px;">0</td><td style="border:1px solid #ddd;padding:8px;">14</td><td style="border:1px solid #ddd;padding:8px;">0</td></tr>
</tbody>
</table>

<p>Smaller numbers are open in Bahawalnagar, Murree, Muzaffargarh, Faisalabad, Hafizabad and other districts. Lahore has no district posts in this round; the 25 Field Worker posts are for the Rescue headquarters and academy. Applicants must be 20 to 30. Local residents, as shown on the CNIC, get preference. Apply through Central Testing Service (ctspak.com) by <strong>5 December 2026</strong>.</p>

<h2>Lahore: Railways Apprenticeships</h2>

<p>Pakistan Railways is taking <strong>227 Skilled (BPS-06) C-Class apprentices</strong>: 190 at the Moghalpura Workshops and 37 at the Signal Shops in Lahore. Applicants need Matric in second division plus a one-year trade certificate, and must be aged 18 to 25, with up to 5 years' relaxation. Forms go by registered post with a Rs 1,000 postal order by <strong>5 December 2026</strong>, and the written test centres are Lahore, Rawalpindi and Multan.</p>

<h2>Private Jobs by City</h2>

<p>Private employers do not publish through the commissions, so search their career pages and the main job boards with the city name and job title, for example "accountant jobs Lahore" or "customer support jobs Karachi". The broad pattern:</p>

<ul>
<li><strong>Karachi:</strong> banking and finance head offices, ports and logistics, textiles, FMCG and the largest call centre cluster.</li>
<li><strong>Lahore:</strong> software houses, banking, retail, education and healthcare.</li>
<li><strong>Islamabad and Rawalpindi:</strong> federal bodies, NGOs and international organisations, IT firms and consulting.</li>
<li><strong>Faisalabad:</strong> textile mills and garment exporters, with production, quality and merchandising roles.</li>
<li><strong>Sialkot:</strong> export manufacturing of sports goods, surgical instruments and leather.</li>
<li><strong>Multan, Peshawar and Quetta:</strong> regional offices of banks, hospitals, universities and development organisations.</li>
</ul>

<p>For jobs close to home, our <a href="/blog/jobs-near-me-hiring-now-how-to-apply">jobs near me guide</a> explains the local search phrases and the National Job Portal and Punjab Jobs Portal in more detail. For call centre work, see <a href="/blog/call-centre-jobs-karachi-sindh-freshers">call centre jobs for freshers in Karachi</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-pakistan-2026-by-city-4.jpg"
         alt="Magnifying glass reading JOBS over a newspaper beside a laptop and Pakistan flag, Jobs in Pakistan 2026 latest vacancies, eligibility, last date"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Eligibility: What Every Advert Checks</h2>

<ul>
<li><strong>Education and division:</strong> "second division" or "second class" appears in almost every advert above.</li>
<li><strong>Age on a stated date:</strong> PPSC counts age on 1 January 2027, Railways on the closing date and SPSC on the date in each advert.</li>
<li><strong>Domicile and zone:</strong> KPPSC allocates posts by zone, SPSC splits urban and rural Sindh, and federal posts use provincial quotas.</li>
<li><strong>Quotas:</strong> women, minorities and disabled candidates have reserved posts in KPPSC and Rescue 1122 adverts.</li>
<li><strong>Physical or skill tests:</strong> Rescue 1122 has physical standards, and stenographer posts need tested shorthand and typing speeds.</li>
</ul>

<h2>How to Apply</h2>

<ol>
<li><strong>Find the advertisement on the commission's or department's own website</strong> and note its number and closing date.</li>
<li><strong>Check eligibility line by line</strong>: division, age on the cut-off date, domicile, zone and quota.</li>
<li><strong>Create or update your profile</strong> on the portal with scanned CNIC, photo, degrees and mark sheets.</li>
<li><strong>Pay the fee only by the stated method</strong>: PSID or e-Pay for PPSC, 1Bill for KPPSC, 1Link for BPSC, a postal order for Railways.</li>
<li><strong>Submit before the last day.</strong> SPSC and KPPSC close at 5 PM on the closing date.</li>
<li><strong>Save the confirmation</strong> and check the site for roll number slips and test schedules.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-pakistan-2026-by-city-5.jpg"
         alt="Laptop showing a Find Jobs search box beside a Pakistan flag and a checklist of latest vacancies, eligibility, last date and how to apply, Jobs in Pakistan 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Spot a Fake Job Advert</h2>

<ul>
<li>The advert is only on WhatsApp or Facebook and is not on the department's own website.</li>
<li>The fee goes to a personal account or mobile wallet, not through the commission's official method.</li>
<li>Someone promises selection, an interview slot or a posting in your city for money.</li>
<li>A "recruiter" asks for your bank PIN, passwords or one-time codes.</li>
<li>The advertisement number, closing date or post count differs from the commission's website.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How do I search for government jobs in my city?</h3>
<p>Start with the commission for your province (PPSC, SPSC, KPPSC or BPSC) and FPSC or the National Job Portal for federal posts. Then check each advert's posting location, zone and domicile rules.</p>

<h3>Which government jobs are open in Pakistan right now?</h3>
<p>On 26 November 2026: PPSC CCE-2026 until 30 November, Rescue 1122 Punjab and Railways apprentices until 5 December, SPSC Advertisement 07/2026 until 14 December and KPPSC Advertisement 10/2026 until 17 December.</p>

<h3>What is the last date for KPPSC Advertisement 10/2026?</h3>
<p>17 December 2026 at 5:00 PM. The fee is Rs 500, and the advert includes 49 Sub Accountant (BPS-16) posts.</p>

<h3>Do I need a domicile for federal jobs in Islamabad?</h3>
<p>Usually yes. Federal posts are shared out by provincial quota, so your domicile decides which quota you compete in.</p>

<h3>Where are BPSC exams held?</h3>
<p>BPSC's notice says all exams are currently held in Quetta, so choose Quetta as your test centre when applying.</p>

<h2>Bottom Line</h2>

<p>Searching by city works best when you start from the right commission, then filter by your degree, division, age and domicile. Four provincial recruitments and several department drives are open until mid-December 2026. Apply on the official portal, pay only the official fee, and treat any advert you cannot find on a government website as fake until proven otherwise.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.ppsc.gop.pk/" rel="noopener">Punjab Public Service Commission</a>, Advertisement No. 12/2026</li>
<li><a href="https://www.spsc.gov.pk/" rel="noopener">Sindh Public Service Commission</a>, Consolidated Advertisement No. 07/2026</li>
<li><a href="https://www.kppsc.gov.pk/" rel="noopener">Khyber Pakhtunkhwa Public Service Commission</a>, Advertisement No. 10/2026, dated 25 November 2026</li>
<li><a href="https://www.bpsc.gob.pk/" rel="noopener">Balochistan Public Service Commission</a>, candidate notices of 2026</li>
<li><a href="https://www.fpsc.gov.pk/" rel="noopener">Federal Public Service Commission</a> and the <a href="https://www.njp.gov.pk/" rel="noopener">National Job Portal</a></li>
<li><a href="https://www.rescue.gov.pk/" rel="noopener">Rescue 1122 Punjab</a>, recruitment advertisement of 14 November 2026, and <a href="https://www.pakrail.gov.pk/" rel="noopener">Pakistan Railways</a>, Moghalpura apprentice advertisement of 13 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/entry-level-no-experience-jobs-pakistan-2026">Entry Level No Experience Jobs in Pakistan 2026</a>, fresh-candidate posts in detail.</li>
    <li><a href="/blog/jobs-near-me-hiring-now-how-to-apply">Jobs Near Me Hiring Now</a>, local job search step by step.</li>
    <li><a href="/blog/call-centre-jobs-karachi-sindh-freshers">Call Centre Jobs in Karachi for Freshers</a>, private-sector entry jobs.</li>
    <li><a href="/blog/remote-work-from-home-jobs-2026">Remote Work From Home Jobs 2026</a>, working from any city.</li>
</ul>

HTML;
    }
}
