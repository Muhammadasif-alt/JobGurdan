<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Petrol Pump Attendant Jobs in Lahore, Punjab: Duties and Salary"
 *
 * Checked on 30 October 2026. What changed against the brief:
 *
 * 1. The brief repeated many keyword variants, which reads as keyword stuffing; the guide is rewritten in plain prose
 *    with the tags carrying the search terms. The two Indeed buttons are dropped.
 * 2. The Punjab Gazette notification (No. SO(L&P)MW/2024, dated 8 September 2025, published 9 September 2025) was read
 *    from the scanned page in the CLR PDF: Rs 40,000 a month for 26 working days, Rs 1,538.46 a day for eight hours,
 *    Rs 192.31 an hour, from 1 July 2025, for adult and adolescent workers in all industrial and commercial
 *    establishments. It also allows agreed deductions of Rs 100 a meal, Rs 1,800 a month for transport and Rs 2,000 a
 *    month for accommodation, and requires equal pay for women. No 2026-27 Punjab notification was found.
 * 3. PSO fetched: online applications only, vacancies under Opportunities; retail outlet business is a separate
 *    business-partner section. Wafi Energy fetched: exclusive licensee of the Shell brand in Pakistan; Shell Pakistan
 *    Limited became Wafi Energy Pakistan Limited in 2025; applications only to specific vacancies. TOTALPARCO careers
 *    showed "No current openings".
 * 4. FAQs expanded to eight.
 *
 * The featured image is the owner's poster (blog-355, 29 Oct 10.07 PM); the steps graphic was generated in the house style. The portal
 * listing is in GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class PetrolPumpAttendantJobsLahoreDutiesSalaryBlogSeeder extends Seeder
{
    public const SLUG = 'petrol-pump-attendant-jobs-lahore-duties-salary';

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
                'title' => 'Petrol Pump Attendant Jobs in Lahore, Punjab: Duties and Salary',
                'excerpt' => 'What petrol pump attendants in Lahore do, who actually employs them (usually the dealer, not PSO or Shell), Punjab\'s Rs 40,000 minimum wage and the deductions it allows for food, transport and housing, shifts and how to apply safely.',
                'content' => $content,
                'featured_image' => 'blogs/petrol-pump-attendant-jobs-lahore-duties-salary-poster.jpg',
                'tags' => 'petrol pump attendant jobs in lahore, petrol pump jobs in lahore pakistan, petrol pump attendant salary in lahore, petrol pump jobs in lahore for freshers, how to apply for petrol pump jobs in lahore, punjab minimum wage 2025, pso petrol pump jobs, shell petrol pump jobs lahore',
                'meta_title' => 'Petrol Pump Attendant Jobs in Lahore: Duties and Salary',
                'meta_description' => 'Petrol pump attendant jobs in Lahore: duties, who employs pump staff, the Rs 40,000 Punjab minimum wage and allowed deductions, shifts and safe hiring.',
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
<p><strong>Petrol pump attendants in Lahore dispense fuel, serve customers, take payments and follow the station's safety rules, and most stations train beginners on the job.</strong> The person who hires you is usually the station's dealer or operator, not PSO, Shell or Total. Punjab's minimum wage is Rs 40,000 a month for 26 working days, from 1 July 2025, and the law limits what an employer may deduct for food, transport and housing. No reliable average salary for attendants exists, so compare each offer with the legal minimum. This guide uses the Punjab Gazette, PSO, Wafi Energy and TOTALPARCO, checked on 30 October 2026.</p>

<h2>What Does a Petrol Pump Attendant Do?</h2>

<p>The job combines customer service, careful fuelling and accurate records. Typical duties include:</p>

<ul>
<li>Confirming the fuel type and amount before filling.</li>
<li>Showing the customer the dispenser reading.</li>
<li>Using equipment only after training and permission.</li>
<li>Taking cash or card payments and recording sales.</li>
<li>Keeping the forecourt tidy and reporting leaks or hazards.</li>
<li>Handing over cash, equipment and records at the end of the shift.</li>
</ul>

<p>Some stations separate the filler (fuel only) from the cashier (payments and daily reconciliation); others combine them. Searches also show tuck shop, car wash, oil change and cleaning jobs at fuel stations, so read the duties before you apply. Never learn fuelling or fire safety by guesswork; follow the station's procedures and your supervisor.</p>

<h2>Qualifications and Freshers</h2>

<p>There is no single qualification for every pump. Matric is often enough for an attendant who can read, count and speak politely with customers; cashier and record-keeping posts may ask for Intermediate. Expect to show your CNIC, references and availability, and give copies only to the station or company itself. If you are a fresher, ask who will train you, how mistakes are reported and when you will work alone. Shop work, cash handling and customer service count as useful experience. Supervisor posts are not entry level even if they appear in the same search.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/petrol-pump-attendant-jobs-lahore-duties-salary-steps.jpg"
         alt="Checklist before joining a Lahore petrol pump: find who employs you, check duties and shift length, compare pay with PKR 40,000, ask about training and safety, get the offer in writing, keep records"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Salary: The Punjab Minimum Wage</h2>

<p>The Punjab Labour and Human Resource Department's notification dated 8 September 2025 fixes the minimum wage for adult and adolescent workers in all industrial and commercial establishments in the province, from 1 July 2025:</p>

<ul>
<li><strong>Rs 40,000 a month</strong> for 26 working days</li>
<li><strong>Rs 1,538.46 a day</strong> for eight working hours</li>
<li><strong>Rs 192.31 an hour</strong></li>
</ul>

<p>The same notification allows the employer to deduct, only by agreement with the worker, <strong>Rs 100 a meal</strong> for food, <strong>Rs 1,800 a month</strong> for transport and <strong>Rs 2,000 a month</strong> for accommodation. Higher existing wages may not be reduced, and a woman doing work of equal value must get the same wage as a man. These are legal floors, not proof that a particular pump pays them, and Punjab figures do not apply in other provinces. Check with the Labour Department for any newer notification before you sign. Source: <a href="https://clr.org.pk/Labour-Laws/Minimum%20Wage%20Notification/Punjab.%20Minimum%20Wages%20Notification.%202025.pdf" rel="noopener">Punjab Gazette: Minimum wages notification, 8 September 2025</a>.</p>

<p>When you compare offers, ask about basic pay, shift length, breaks and weekly rest, overtime, every deduction, and how and when you are paid. A 12-hour shift at the same monthly pay is a much lower hourly rate than an 8-hour one. Tips are not guaranteed pay.</p>

<h2>Company Careers or Dealer Hiring?</h2>

<p>A logo on the canopy does not tell you who employs the staff. Most forecourt staff are hired by the station's dealer or operator, so ask whose name will be on your contract.</p>

<p><strong>PSO:</strong> PSO accepts online applications only, for the posts listed under Opportunities on its careers page, and runs retail outlet business through a separate business-partner section. For attendant jobs at a PSO station, ask the station's manager. Official page: <a href="https://www.psopk.com/en/career/opportunities" rel="noopener">PSO: Career opportunities</a>.</p>

<p><strong>Shell stations:</strong> Wafi Energy is the exclusive licensee of the Shell brand in Pakistan; Shell Pakistan Limited became Wafi Energy Pakistan Limited in 2025. Its careers site accepts applications only for specific advertised vacancies, which are mostly company jobs rather than forecourt posts. Official page: <a href="https://wafi-energy.com/careers/" rel="noopener">Wafi Energy: Careers</a>.</p>

<p><strong>Total stations:</strong> the TOTALPARCO careers portal showed "No current openings" when we checked; use it to watch for company vacancies, not as proof of an attendant job. Official page: <a href="https://careers.totalparco.com.pk/" rel="noopener">TOTALPARCO: Careers</a>.</p>

<h2>Locations, Part-Time Work and Accommodation</h2>

<p>Compare the station's exact address with your travel cost: DHA, Johar Town, Walton Road and the airport area are far apart. Airport aviation fuelling is a different job from a roadside pump. For part-time work, confirm the hours and that handover time is paid. If accommodation is offered, ask for the address, how many share a room and what is deducted, remembering the Rs 2,000 a month limit.</p>

<h2>How to Apply</h2>

<p>Prepare a one-page CV with your phone number, education, experience and availability. Ask the station manager, at a quiet time and away from the pumps, whether they are hiring and how to apply. There is no single application form for every pump. Verify any "contact number" through the station or company itself; OLX and WhatsApp adverts are leads, not proof. Never pay anyone to get a job. Before joining, ask how cash shortages, customer disputes and equipment faults are handled, and keep copies of your offer, attendance and pay.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum salary for a petrol pump attendant in Lahore?</h3>
<p>Rs 40,000 a month for 26 working days under the Punjab notification from 1 July 2025.</p>

<h3>What is the daily minimum wage in Punjab?</h3>
<p>Rs 1,538.46 for eight working hours, or Rs 192.31 an hour.</p>

<h3>Can the employer deduct for food and housing?</h3>
<p>Only by agreement, up to Rs 100 a meal, Rs 1,800 a month for transport and Rs 2,000 a month for accommodation.</p>

<h3>Do PSO or Shell hire pump attendants directly?</h3>
<p>Usually the station's dealer or operator hires forecourt staff; ask whose name is on the contract.</p>

<h2>Bottom Line</h2>

<p>Find out who employs you, learn the duties properly, and compare hours, pay and deductions with the Punjab minimum before you accept.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://clr.org.pk/Labour-Laws/Minimum%20Wage%20Notification/Punjab.%20Minimum%20Wages%20Notification.%202025.pdf" rel="noopener">Punjab Gazette: Minimum wages notification, 8 September 2025</a> and <a href="https://www.radio.gov.pk/10-09-2025/punjab-govt-fixes-minimum-wage-for-workers-at-rs40000-per-month" rel="noopener">Radio Pakistan</a></li>
<li><a href="https://www.psopk.com/en/career/career-faqs" rel="noopener">PSO: Career FAQs</a> and <a href="https://www.psopk.com/en/career/opportunities" rel="noopener">Opportunities</a></li>
<li><a href="https://wafi-energy.com/corporate-information/" rel="noopener">Wafi Energy: Corporate information</a> and <a href="https://wafi-energy.com/careers/" rel="noopener">Careers</a></li>
<li><a href="https://careers.totalparco.com.pk/" rel="noopener">TOTALPARCO: Careers</a>, all checked 30 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/lab-technician-jobs-lahore-punjab-qualifications-salary">Lab Technician Jobs in Lahore</a>, a qualified job in the same city.</li>
    <li><a href="/blog/sweeper-jobs-karachi-government-private-options">Sweeper Jobs in Karachi</a>, entry-level work in Sindh.</li>
    <li><a href="/blog/gas-station-jobs-houston-hiring-age-requirements">Gas Station Jobs in Houston</a>, the same work in the USA.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan</a>, routes overseas.</li>
</ul>

HTML;
    }
}
