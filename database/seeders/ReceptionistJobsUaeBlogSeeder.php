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
 * "Receptionist Jobs in UAE" — front-desk work across hotels, clinics and
 * corporate offices, and the indoor companion to the UAE security guard
 * guide. The two share a labour law and the same package trap; this one adds
 * the free zone that runs a different law altogether.
 *
 * Corrections to the draft:
 *
 * 1. Its apply link points at www.indeed.com, the American site, filtered to
 *    the UAE. UAE postings are on ae.indeed.com.
 *
 * 2. It lists housing and transport allowances as benefits without noting
 *    that end-of-service gratuity under Federal Decree-Law No. 33 of 2021 is
 *    calculated on basic salary alone. How a package is split between basic
 *    and allowances decides what a receptionist leaves with.
 *
 * 3. It lists JAFZA, DMCC and DIFC together as free zones hiring front-desk
 *    staff. DIFC runs its own employment law and replaced gratuity with the
 *    DEWS workplace savings plan in 2020, so a DIFC receptionist is on a
 *    different end-of-service system from one in the other two.
 *
 * 4. It tells candidates to "confirm visa sponsorship details" without saying
 *    that recruitment costs, the visa included, are the employer's by law and
 *    cannot be recovered from the worker directly or indirectly.
 *
 * 5. It says nothing about the visit visa, which is how many candidates
 *    arrive to interview. Starting work before a work permit is issued is
 *    illegal for both the worker and the employer.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ReceptionistJobsUaeBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ae.indeed.com/q-receptionist-jobs.html';

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
        $title = 'Receptionist Jobs in UAE';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Housing and transport allowances do not count towards your gratuity, DIFC runs a different end-of-service system from every other free zone, and the visa is legally the employer cost. What to check before you accept a front-desk offer.',
                'content' => $content,
                'featured_image' => 'blogs/receptionist-jobs-in-uae.jpg',
                'tags' => 'receptionist jobs uae, receptionist jobs dubai, hotel receptionist jobs, front desk jobs uae, medical receptionist jobs dubai, receptionist salary uae, difc jobs, uae gratuity',
                'meta_title' => 'Receptionist Jobs in UAE',
                'meta_description' => 'Receptionist jobs in the UAE: why allowances do not count towards gratuity, how DIFC differs from other free zones, and who pays for your visa.',
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
            ['name' => 'UAE Hotels, Clinics & Corporate Offices (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ae-receptionist-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Receptionist — Hotel, Clinic and Corporate Front Desk, UAE Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Office hours in corporate roles; rotating shifts including nights in hotels and clinics',
                'language' => 'English, Arabic',
                // The monthly figure means little until the basic-to-allowance
                // split is known, and DIFC roles sit on a different
                // end-of-service system entirely.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Hotel, clinic and corporate front-desk roles with UAE employers. Ask for the basic salary figure before comparing packages.',
                'seo_keywords' => 'receptionist jobs uae, receptionist jobs dubai, hotel receptionist jobs, front desk jobs uae, receptionist salary uae',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hotels, clinics and hospitals, real estate firms, corporate offices and free zone companies across the United Arab Emirates hire receptionists and front-desk staff. It is one of the most accessible office roles in the country for candidates from abroad, and one where the structure of the package matters more than its headline total.</p>

<h3>What the work involves</h3>
<p>Greeting and registering visitors, handling calls and correspondence, managing appointments or reservations, coordinating with the teams behind the desk, and keeping the front office running. Hotel roles add check-in, check-out and shift work; clinic roles add patient scheduling and records.</p>

<h3>Requirements</h3>
<ul>
    <li>A high school diploma; a degree helps for corporate front-office roles</li>
    <li>Fluent spoken and written English; Arabic is a genuine advantage, especially with government-linked employers</li>
    <li>MS Office and a booking, reservation or clinic management system</li>
    <li>Front-desk, hospitality or customer service experience, preferred rather than always required</li>
    <li>A professional manner &mdash; the desk is the first thing a client sees</li>
    <li>An employment visa and work permit arranged by the employer before you start work</li>
</ul>

<h3>How the package works</h3>
<ul>
    <li><strong>Basic salary and allowances are separate lines,</strong> and end-of-service gratuity under Federal Decree-Law No. 33 of 2021 is calculated on <strong>basic salary alone</strong></li>
    <li><strong>DIFC is different.</strong> It runs its own employment law, and instead of gratuity the employer pays into the DEWS workplace savings plan</li>
    <li><strong>The visa is the employer's cost.</strong> Recruitment costs cannot be recovered from the worker, directly or through salary deductions</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask for the basic salary figure in writing, and ask which employment law the role sits under.</strong> Two offers with the same monthly total can build very different end-of-service entitlements, and a DIFC contract works on a different system from one in any other free zone.</p>

<p><strong>Note:</strong> salary, package structure, visa arrangements and working hours are set by each employer and by UAE law &mdash; not by JobGader. Never pay an agent to be placed. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Front-desk work is one of the steadiest ways into the UAE for someone with good English and a professional manner. The vacancies are real, the sectors are broad, and many packages include housing and transport. The problem is that most guides describe those packages as though the total were the thing that mattered. In the UAE, it is the split.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ae.indeed.com/q-receptionist-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128100; Browse Receptionist Jobs in the UAE &rarr;
    </a>
</div>

<h2>Your Gratuity Is Built on Basic Salary, Not the Package</h2>

<p>Guides list the benefits that come with a UAE receptionist job &mdash; housing allowance, transportation, medical insurance, annual flights &mdash; as though every one of them added to what the job is worth. For your monthly budget, they do. For what you leave the country with, most of them add nothing.</p>

<p>Under <strong>Federal Decree-Law No. 33 of 2021</strong>, end-of-service gratuity is calculated on <strong>basic salary only</strong>. Housing, transport and other allowances are excluded. For the first five years of service it builds at <strong>21 days of basic salary per year</strong>, and at 30 days for each year after that.</p>

<p>Take two offers that both say <strong>AED 4,500 a month</strong>:</p>

<ul>
    <li><strong>Offer A:</strong> AED 4,500 basic. Each of the first five years builds roughly <strong>AED 3,150</strong> of gratuity.</li>
    <li><strong>Offer B:</strong> AED 2,700 basic plus AED 1,800 in housing and transport allowances. Each year builds roughly <strong>AED 1,890</strong>.</li>
</ul>

<p>Same monthly total, same payday feeling &mdash; and a difference of about <strong>AED 1,260 a year</strong>, or around <strong>AED 6,300</strong> across five years, decided entirely by a line in the contract that nobody reads aloud at the interview.</p>

<p><strong>Ask for the basic salary figure, in writing, before you accept.</strong> It is a completely normal question in the Gulf, and the answer tells you more than the headline does. Our <a href="/blog/security-guard-jobs-in-uae">security guard jobs in UAE guide</a> works through the same trap at a lower salary level, where it bites even harder.</p>

<h2>DIFC Is Not Like the Other Free Zones</h2>

<p>Guides list <strong>JAFZA, DMCC and DIFC</strong> together as free zones with steady demand for corporate front-desk staff. The demand is real. But one of those three runs on a different legal system, and it changes what happens when you leave.</p>

<p>The <strong>Dubai International Financial Centre</strong> has its own employment law. And since <strong>1 February 2020</strong> it has replaced end-of-service gratuity for expatriate employees with <strong>DEWS</strong> &mdash; the DIFC Employee Workplace Savings plan.</p>

<p>Instead of a lump sum calculated when your contract ends, your employer pays contributions into a managed savings plan while you work: <strong>5.83 per cent of basic salary</strong> for employees with fewer than five years' service, and <strong>8.33 per cent</strong> from five years onward. The rates are set to broadly match what gratuity would have cost, but the money is invested and held for you rather than owed to you.</p>

<p>That has real consequences. The entitlement does not depend on the employer still having the cash on your last day, and it is visible while you work. It is also invested, so its value moves. Neither system is simply better &mdash; but they are different, and a receptionist comparing a DIFC offer with a JAFZA one needs to know that before comparing the two as like for like. <strong>Ask which employment law the contract sits under.</strong></p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/receptionist-jobs-in-uae-freezone.jpg"
         alt="A receptionist handing a visitor pass across a marble front desk in a Dubai office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Visa Is the Employer's Cost, by Law</h2>

<p>Guides tell candidates from abroad to "confirm visa sponsorship details before applying". That advice is right and incomplete, and the missing half is the part that protects you.</p>

<p>Under <strong>Federal Decree-Law No. 33 of 2021</strong>, recruitment costs &mdash; hiring, the visa, the medical examination, the residence permit &mdash; are the <strong>employer's</strong> to bear. The employer is prohibited from collecting them from the worker, <strong>directly or indirectly</strong>. Deducting visa costs from your salary to "recover" them is a violation, and it is one you can complain to the Ministry of Human Resources and Emiratisation about.</p>

<p>Which gives you a very simple rule for the whole process: <strong>if you are being asked to pay for your own visa, or to pay an agent to be placed, something is wrong.</strong> Legitimate employers in hotels, clinics and corporate offices know this and do not ask.</p>

<h2>Do Not Start Work on a Visit Visa</h2>

<p>A common route for receptionist candidates is to arrive on a visit visa, interview in person, and look for an offer. Interviewing on a visit visa is fine. <strong>Working on one is not.</strong></p>

<p>Starting work &mdash; including a "trial week" or an unpaid "training period" &mdash; before your work permit has been issued is illegal, and the penalties fall on both sides: fines for the employer, and fines with a real risk of deportation for the worker. A front desk is a very visible place to be working without a permit.</p>

<p>If an employer suggests you start while the paperwork catches up, the correct answer is that you will start on the day the permit is issued. An employer who presses the point is telling you how they will treat the rest of your contract.</p>

<h2>Where the Pay Actually Separates</h2>

<p>Published ranges for this work look like this:</p>

<ul>
    <li><strong>Entry-level receptionist:</strong> AED 3,000 to 4,500 a month</li>
    <li><strong>Experienced receptionist:</strong> AED 4,500 to 6,500</li>
    <li><strong>Multilingual hotel or corporate receptionist:</strong> AED 6,000 to 9,000 or more</li>
</ul>

<p>The jump into the top bracket is almost entirely about <strong>languages</strong> and <strong>sector</strong>. Arabic is the obvious premium, particularly with government-linked employers and businesses serving local clients. In luxury hospitality, the languages of the guests the hotel actually receives carry their own premium, and it is worth reading hotel advertisements for which ones they name.</p>

<p>Read every figure in that list against the split above. An AED 6,500 package with a low basic can build less gratuity than an AED 5,000 package that is mostly basic. And ask about working hours: hotel front office runs shifts including nights, and a corporate desk does not, which is a difference in the job rather than in the pay.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/receptionist-jobs-in-uae-package.jpg"
         alt="A UAE hotel receptionist greeting a guest at a front desk beside the national flag"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Hotel receptionist.</strong> Check-in, check-out, reservations and concierge support. Shift work, and the strongest multilingual premium.</li>
    <li><strong>Corporate office receptionist.</strong> Visitors, calls and meeting rooms. Office hours, and more likely to sit in a free zone.</li>
    <li><strong>Medical or clinic receptionist.</strong> Patient scheduling and records. Steady demand, and system experience counts.</li>
    <li><strong>Salon and spa receptionist.</strong> Booking and client service. Entry-friendly, usually at the lower end of the range.</li>
    <li><strong>Real estate receptionist.</strong> Enquiries and viewings. Closely tied to the property market's pace.</li>
    <li><strong>Multilingual receptionist.</strong> Arabic or other in-demand languages alongside English. The top of the published range.</li>
</ul>

<h2>Where the Work Is</h2>

<ul>
    <li><strong>Dubai</strong> &mdash; the largest market, across hospitality, corporate offices and real estate.</li>
    <li><strong>Abu Dhabi</strong> &mdash; government-linked companies, healthcare and corporate offices, where Arabic is most valuable.</li>
    <li><strong>Sharjah</strong> &mdash; retail, healthcare and smaller offices, with lower living costs than Dubai.</li>
    <li><strong>Ajman and Ras Al Khaimah</strong> &mdash; growing hospitality and real estate demand.</li>
    <li><strong>Free zones</strong> &mdash; steady corporate front-desk demand, with DIFC on its own employment law and DEWS rather than gratuity.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do receptionists earn in the UAE?</h3>
<p>Published ranges run from about AED 3,000 to 4,500 a month at entry level, AED 4,500 to 6,500 with experience, and AED 6,000 to 9,000 or more for multilingual hotel and corporate roles. Always ask how much of it is basic salary.</p>

<h3>Do housing and transport allowances count towards gratuity?</h3>
<p>No. Under Federal Decree-Law No. 33 of 2021, gratuity is calculated on basic salary alone. AED 4,500 all basic builds about AED 3,150 a year for the first five years; AED 2,700 basic plus AED 1,800 allowances builds about AED 1,890.</p>

<h3>Do receptionists in DIFC get gratuity?</h3>
<p>Not in the usual form. DIFC runs its own employment law and replaced gratuity with the DEWS savings plan from 1 February 2020. Employers contribute 5.83 per cent of basic salary for under five years' service and 8.33 per cent after that.</p>

<h3>Who pays for my UAE work visa?</h3>
<p>The employer. Under Federal Decree-Law No. 33 of 2021, recruitment costs including the visa, medical and residence permit are the employer's, and cannot be collected from the worker directly or indirectly.</p>

<h3>Can I work while I am on a visit visa?</h3>
<p>No. You can interview on a visit visa, but starting work &mdash; including a trial or training period &mdash; before your work permit is issued is illegal, with fines for the employer and fines and deportation risk for the worker.</p>

<h3>Is Arabic required for receptionist jobs in the UAE?</h3>
<p>Not usually required, but a strong advantage, especially with government-linked employers and businesses serving local clients. English is the baseline for almost every role.</p>

<h3>Do I need experience to become a receptionist in the UAE?</h3>
<p>Not always. Many salon, retail and small-office roles accept candidates with little experience, while hotel and corporate desks often prefer at least a year of front-desk work.</p>

<h3>What should I ask before accepting a receptionist offer?</h3>
<p>The basic salary figure, which employment law the contract sits under, who pays for the visa, what the working hours and shifts are, and whether benefits such as annual flights are written into the contract.</p>

<h2>People Also Search For</h2>

<h3>Receptionist jobs in Dubai</h3>
<p>The largest market in the country. Ask for the basic salary figure before comparing any two offers.</p>

<h3>Hotel receptionist jobs UAE</h3>
<p>Shift work with the strongest language premium, and usually housing or transport in the package.</p>

<h3>Medical receptionist jobs Dubai</h3>
<p>Steady clinic and hospital demand, where scheduling system experience counts.</p>

<h3>Receptionist salary in UAE per month</h3>
<p>About AED 3,000 to 9,000 depending on experience, languages and sector. The basic share decides gratuity.</p>

<h3>DIFC receptionist jobs</h3>
<p>Corporate front-desk roles under DIFC's own employment law, with DEWS contributions instead of gratuity.</p>

<h3>UAE gratuity calculation</h3>
<p>Basic salary only: 21 days per year for the first five years, 30 days for each year after.</p>

<h3>Receptionist jobs in UAE with visa</h3>
<p>Widely available. The visa is the employer's cost by law, never yours.</p>

<h3>Front desk jobs Abu Dhabi</h3>
<p>Government-linked, healthcare and corporate roles, where Arabic carries the most weight.</p>

<h2>More Job Guides</h2>

<p>Comparing Gulf routes and front-office work elsewhere? These cover them:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; the same gratuity trap, and the licence that only covers one emirate.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; Gulf work under the Saudi system instead.</li>
    <li><a href="/blog/cleaner-jobs-in-saudi-arabia-for-foreigners">Cleaner Jobs in Saudi Arabia for Foreigners</a> &mdash; the package questions applied to a Saudi contract.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; front-office work where the pay floor is published by law.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; client-facing work under provincial wage floors.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; the same skills without relocating.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; hospitality front office on the American visa system.</li>
    <li><a href="/blog/data-entry-jobs-in-pakistan">Data Entry Jobs in Pakistan</a> &mdash; office work at home rather than a Gulf contract.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or financial advice. Labour law provisions, DIFC rules, visa requirements and salary levels change, and figures on any job board are a moving average rather than a statistic. Confirm the current position with the Ministry of Human Resources and Emiratisation, the DIFC and the employer's own advertisement before applying or accepting an offer.</p>
HTML;
    }
}
