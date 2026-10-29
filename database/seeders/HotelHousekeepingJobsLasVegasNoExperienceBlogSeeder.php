<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Hotel Housekeeping Jobs in Las Vegas, Nevada With No Experience"
 *
 * Checked on 29 October 2026. What changed against the brief:
 *
 * 1. The brief repeated dozens of keyword variants ("hotel housekeeping jobs Las Vegas ..."), which reads as keyword
 *    stuffing; the guide is rewritten in plain prose with the tags carrying the search terms. The two Indeed buttons are
 *    dropped.
 * 2. The two MGM requisitions (279886 Las Vegas Properties at $21.57, 279993 Park MGM at $25.0833) could not be opened:
 *    careers.mgmresorts.com refuses our network and the server's. Searches found the same title at other MGM properties
 *    with different rates ($21.57 at The Cosmopolitan, $23 to $23.17 at Park MGM), so no MGM rate is quoted; readers are
 *    told to read the rate printed on each MGM advert.
 * 3. Wynn's Guest Room Attendant specification fetched: part-time, at least two years serving guest rooms in a 4/5 Star
 *    or Diamond resort preferred, a high school diploma or any combination of education, training and experience, and a
 *    minimum age of 21.
 * 4. Culinary Academy of Las Vegas fetched: Guest Room Attendant 60 hours (2 weeks) at $1,200, GRA PLUS 100 hours
 *    (4 weeks) at $1,600, House Person/Utility Porter 60 hours at $1,200 with a minimum age of 21; minimum age 17 for the
 *    GRA courses; tuition assistance for qualifying students, no charge for employer partner team members, and Career
 *    Services job placement support.
 * 5. O*NET (BLS 2025) fetched for Las Vegas-Henderson-North Las Vegas maids and housekeeping cleaners: median $23.14 an
 *    hour ($48,140), 25th percentile $36,060 a year ($17.34), 75th $50,370 ($24.22), 10th $15.00, 90th $27.20.
 * 6. Added the Nevada minimum wage ($12.00 an hour, Labor Commissioner bulletin effective 1 July 2026) and the Culinary
 *    Union's role at unionised Strip properties, without the union's own wage estimates.
 * 7. FAQs expanded to eight; eight People Also Search For entries and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 28 Oct 10.54 and 10.55 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class HotelHousekeepingJobsLasVegasNoExperienceBlogSeeder extends Seeder
{
    public const SLUG = 'hotel-housekeeping-jobs-las-vegas-no-experience';

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

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Hotel Housekeeping Jobs in Las Vegas, Nevada With No Experience',
                'excerpt' => 'How beginners get hotel housekeeping jobs in Las Vegas: what MGM and Wynn adverts ask for, the Culinary Academy\'s 60-hour guest room attendant course, the $23.14 median wage, union hotels, shifts and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/hotel-housekeeping-jobs-las-vegas-no-experience.jpg',
                'tags' => 'housekeeping jobs las vegas no experience, hotel housekeeping jobs in las vegas, guest room attendant jobs las vegas, mgm housekeeping jobs, hotel housekeeping jobs las vegas with training, culinary academy housekeeping course, housekeeper pay las vegas, las vegas strip hotel jobs',
                'meta_title' => 'Hotel Housekeeping Jobs in Las Vegas With No Experience',
                'meta_description' => 'Hotel housekeeping jobs in Las Vegas for beginners: what MGM and Wynn ask for, the 60-hour Culinary Academy course, the $23.14 median wage and how to apply.',
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
<p><strong>You can start in hotel housekeeping in Las Vegas without hotel experience, but not at every property: read each advert's requirements before you apply.</strong> Some employers accept training or related work in place of experience, others prefer years in a luxury resort. The Culinary Academy of Las Vegas runs a 60-hour guest room attendant course, and the 2025 median wage for maids and housekeeping cleaners in the Las Vegas area was $23.14 an hour. An advert is not a guarantee of hiring, hours or sponsorship. This guide uses Wynn Resorts, the Culinary Academy, O*NET and the Nevada Labor Commissioner, checked on 29 October 2026.</p>

<h2>Can Beginners Get Hired?</h2>

<p>Yes, where the advert allows it. Read below the headline and separate what is required from what is only preferred. If you have never worked in a hotel, show what transfers: cleaning at home or in a previous job, customer service, volunteering, and above all reliability and attention to detail. Never claim paid hotel experience you do not have; supervisors check rooms, and it shows quickly. An advert that says no experience is needed should still explain the training, supervision and duties.</p>

<h2>Job Titles and Daily Duties</h2>

<p>The guest room attendant (GRA) is the core housekeeping job in Las Vegas: cleaning rooms, making beds, restocking supplies and reporting maintenance problems. "Room attendant", "hotel housekeeper" and "housekeeping attendant" usually mean similar work. Related titles widen the search:</p>

<ul>
<li><strong>House person or utility porter:</strong> moves linen and supplies and supports the room attendants.</li>
<li><strong>Public area attendant:</strong> cleans lobbies, corridors and shared spaces rather than guest rooms.</li>
<li><strong>Laundry attendant:</strong> processes linen and uniforms.</li>
</ul>

<p>Hospital housekeeping is a separate setting with infection-control procedures of its own, so do not treat it as the same job.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hotel-housekeeping-jobs-las-vegas-no-experience-team.jpg"
         alt="Hotel guest room attendant in a black uniform making a bed in a Las Vegas hotel room, with the Strip visible through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Employers Ask For</h2>

<p>Requirements are not uniform. Wynn's Guest Room Attendant specification, for example, is a part-time role that prefers at least two years serving guest rooms in a 4/5 Star or Diamond resort, accepts a high school diploma or any other combination of education, training and experience that gives the required skills, and requires applicants to be 21 or older. Those conditions belong to that posting only. Ask any employer about lifting, cart handling, safe use of cleaning chemicals, the number of rooms per shift and how to request a reasonable accommodation. Source: <a href="https://jobs.smartrecruiters.com/WynnResorts/744000095780785-housekeeping-guest-room-attendant" rel="noopener">Wynn Resorts: Guest Room Attendant</a>.</p>

<h2>Training: The Culinary Academy of Las Vegas</h2>

<p>The Culinary Academy of Las Vegas runs three housekeeping programmes:</p>

<ul>
<li><strong>Guest Room Attendant:</strong> 60 hours over 2 weeks, $1,200.</li>
<li><strong>GRA PLUS</strong> (with language and skills support): 100 hours over 4 weeks, $1,600, with an English assessment.</li>
<li><strong>House Person/Utility Porter:</strong> 60 hours over 2 weeks, $1,200, minimum age 21, and you must lift 50 lbs and control equipment up to 250 lbs.</li>
</ul>

<p>The guest room attendant courses take students from age 17 and ask for a valid driver's licence or state ID and a Social Security card. Tuition assistance is available for qualifying students, including Culinary Workers Union Local 226 and Bartenders Union Local 165 members, and team members of the Academy's employer partners pay nothing. Its Career Services team helps with job placement, CVs, applications and mock interviews. Training is not the same as paid training: if a hotel offers training, ask whether it happens after hiring and whether those hours are paid. Source: <a href="https://www.theculinaryacademy.org/programs/housekeeping-programs/" rel="noopener">Culinary Academy of Las Vegas: Housekeeping programs</a>.</p>

<h2>Where to Apply</h2>

<p>Apply on each employer's own careers site, not through a reposted copy. MGM Resorts lists guest room attendant jobs across its Las Vegas properties at <a href="https://careers.mgmresorts.com/" rel="noopener">careers.mgmresorts.com</a>, and each advert prints its own starting rate and property; rates differ between properties and between full-time, part-time and on-call posts, so read the figure on the advert in front of you. Wynn and Encore post at <a href="https://wynnresorts.com/careers" rel="noopener">wynnresorts.com/careers</a>. Check that a role is still open before treating an old search result as a live vacancy. Nevada's state job board, EmployNV, is linked from our portal listing.</p>

<h2>Pay and Benefits</h2>

<p>O*NET shows Bureau of Labor Statistics 2025 data for maids and housekeeping cleaners in Las Vegas-Henderson-North Las Vegas: a median of <strong>$23.14 an hour</strong> ($48,140 a year), with the 25th percentile at $17.34 and the 75th at $24.22. The lowest tenth earned under $15.00 and the top tenth over $27.20. These figures cover the whole occupation across employers, not only beginners in hotels. Source: <a href="https://www.onetonline.org/link/localwages/37-2012.00?st=NV" rel="noopener">O*NET: Maids and housekeeping cleaners, Nevada wages</a>.</p>

<p>The legal floor is lower: Nevada's minimum wage is <strong>$12.00 an hour</strong> from 1 July 2026, the same for every employer, with no tip credit. Many large Strip hotels are unionised, and housekeepers there are covered by agreements negotiated by the Culinary Workers Union Local 226, which set pay, workload and benefits for the covered job titles. Ask whether the post you are offered is covered and which agreement applies; do not assume every role in a property is. Weekly pay describes how often you are paid, not a higher wage. Ask when health insurance and paid leave start, and how they differ between part-time and full-time staff. Source: <a href="https://labor.nv.gov/uploadedFiles/labornvgov/content/Employer/26.06.29%20-%202026%20Minimum%20Wage%20Bulletin.pdf" rel="noopener">Nevada Labor Commissioner: 2026 Minimum Wage Bulletin</a>.</p>

<h2>Locations and Schedules</h2>

<p>Strip, downtown and North Las Vegas properties all hire housekeepers, and the commute can decide whether an early or late shift works for you. Full-time and part-time posts can guarantee very different hours, and a "flexible schedule" rarely means choosing your own shifts. Morning, swing, overnight and weekend shifts each change your week, so confirm the exact weekend commitment. Employee housing is not a standard benefit: get it in writing if it is offered.</p>

<h2>How to Apply</h2>

<p>Prepare a short CV that shows availability, relevant duties and dependable attendance, apply on the employer's own system, and keep the job reference and confirmation email. "Hiring immediately" adverts still need background screening and orientation, so ask which checks remain. Confirm any walk-in hiring event on the employer's own website before you go, and never pay anyone for a hotel job. In the interview, ask about room quotas, inspection standards, breaks, equipment and who trains new staff.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Las Vegas hotel housekeeping posts need existing US work authorisation, and none of the adverts reviewed here offers visa sponsorship. Read <a href="/blog/how-to-get-hotel-housekeeper-job-usa-from-pakistan-2026">how to get a hotel housekeeper job in the USA from Pakistan</a> for the visa routes that exist, and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you pay anyone.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a hotel housekeeping job in Las Vegas with no experience?</h3>
<p>Yes, at properties whose adverts accept training or transferable skills; others prefer resort experience.</p>

<h3>How long is the Culinary Academy housekeeping course?</h3>
<p>The Guest Room Attendant course is 60 hours over 2 weeks; GRA PLUS is 100 hours over 4 weeks.</p>

<h3>How much does the course cost?</h3>
<p>$1,200 for Guest Room Attendant and $1,600 for GRA PLUS, with tuition assistance for qualifying students.</p>

<h3>What do housekeepers earn in Las Vegas?</h3>
<p>The 2025 median for maids and housekeeping cleaners was $23.14 an hour.</p>

<h3>What is the minimum wage in Nevada?</h3>
<p>$12.00 an hour from 1 July 2026, with no tip credit.</p>

<h3>How old do I have to be?</h3>
<p>It depends on the employer; Wynn's guest room attendant posting requires 21 or older.</p>

<h3>Are Las Vegas hotel housekeepers unionised?</h3>
<p>Many Strip housekeepers are covered by Culinary Union Local 226 agreements; ask whether your post is.</p>

<h3>Do Las Vegas hotels sponsor visas for housekeepers?</h3>
<p>None of the adverts reviewed here does; you need existing US work authorisation.</p>

<h2>People Also Search For</h2>

<h3>Housekeeping jobs Las Vegas no experience</h3>
<p>Possible where the advert accepts training or related work.</p>

<h3>Hotel housekeeping jobs in Las Vegas</h3>
<p>Guest room, public area, house person and laundry roles.</p>

<h3>Guest room attendant jobs Las Vegas</h3>
<p>The core housekeeping title at Strip resorts.</p>

<h3>MGM housekeeping jobs</h3>
<p>Listed per property on MGM's own careers site.</p>

<h3>Hotel housekeeping jobs Las Vegas with training</h3>
<p>Ask whether training follows hiring and is paid.</p>

<h3>Culinary Academy housekeeping course</h3>
<p>60 hours, $1,200, with tuition assistance.</p>

<h3>Housekeeper pay Las Vegas</h3>
<p>$23.14 median an hour in 2025.</p>

<h3>Las Vegas Strip hotel jobs</h3>
<p>Compare commute, shifts and union coverage.</p>

<h2>Bottom Line</h2>

<p>Target adverts that accept beginners, describe your transferable skills honestly, consider the Culinary Academy course, and compare pay, hours and union coverage in writing before you accept. This guide cannot promise a job, hours or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://jobs.smartrecruiters.com/WynnResorts/744000095780785-housekeeping-guest-room-attendant" rel="noopener">Wynn Resorts: Guest Room Attendant specification</a></li>
<li><a href="https://www.theculinaryacademy.org/programs/housekeeping-programs/" rel="noopener">Culinary Academy of Las Vegas: Housekeeping programs</a></li>
<li><a href="https://www.onetonline.org/link/localwages/37-2012.00?st=NV" rel="noopener">O*NET: Maids and housekeeping cleaners, Nevada wages (BLS 2025)</a></li>
<li><a href="https://labor.nv.gov/uploadedFiles/labornvgov/content/Employer/26.06.29%20-%202026%20Minimum%20Wage%20Bulletin.pdf" rel="noopener">Nevada Labor Commissioner: 2026 Minimum Wage Bulletin</a></li>
<li><a href="https://careers.mgmresorts.com/" rel="noopener">MGM Resorts careers</a> and <a href="https://wynnresorts.com/careers" rel="noopener">Wynn careers</a>, all checked 29 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026">Hotel Housekeeper Jobs in the USA With Visa Sponsorship</a>, the visa side of the same job.</li>
    <li><a href="/blog/hotel-housekeeper-salary-usa-2026">Hotel Housekeeper Salary in the USA</a>, pay across states.</li>
    <li><a href="/blog/hotel-receptionist-jobs-orlando-florida">Hotel Receptionist Jobs in Orlando</a>, a front desk route.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in the USA for Foreigners</a>, hotel roles across the country.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 29 October 2026 against the pages named in Sources. It does not promise a job, hours, training places or a wage.</p>
HTML;
    }
}
