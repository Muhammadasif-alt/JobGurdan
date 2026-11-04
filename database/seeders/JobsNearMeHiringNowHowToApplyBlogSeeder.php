<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Jobs Near Me: Latest Vacancies Hiring Now and How to Apply Today"
 *
 * Checked on 4 November 2026. What changed against the brief:
 *
 * 1. The brief's three Indeed buttons and its Indeed Pakistan salary listing (PKR 35,000-50,000) are dropped. Its
 *    inline-styled HTML buttons are replaced with ordinary links. The ibex, Target and Whataburger links carried job
 *    IDs, so the guide links each careers site and gives the requisition as text.
 * 2. ibex 5781 read: Customer Support Specialist - International, Lahore, O Levels/Matriculation or above, written and
 *    verbal English, MS Office (Excel and Word), suggesting alternatives to unavailable products.
 * 3. Target R0000458686 read: on-demand Guest Advocate at 7100 S Cicero Ave, Chicago 60629, part time, $17.75-$26.65.
 *    Chicago minimum wage is $17.05 from 1 July 2026 (checked for the cashier guide on 3 November).
 * 4. Whataburger JR10000493 read on 4 November for the Killeen guide: age 16, about 24 hours a week, weekly pay.
 *
 * Posters are the owner's (blog-359, 3 Nov 8.57 to 8.58 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class JobsNearMeHiringNowHowToApplyBlogSeeder extends Seeder
{
    public const SLUG = 'jobs-near-me-hiring-now-how-to-apply';

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
                'title' => 'Jobs Near Me: Latest Vacancies Hiring Now and How to Apply Today',
                'excerpt' => 'A practical way to find jobs near you today: search one title and one place, check the employer\'s own page, and compare real examples from Lahore, Chicago and Killeen with their pay, hours and requirements.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-near-me-hiring-now-how-to-apply.jpg',
                'tags' => 'jobs near me, jobs near me hiring now, jobs near me no experience, part time jobs near me, how to apply for jobs online, fresher jobs near me, customer support jobs lahore, how to find local jobs',
                'meta_title' => 'Jobs Near Me Hiring Now: How to Find and Apply Today',
                'meta_description' => 'Find jobs near you hiring now: how to search by title and place, check a vacancy is real, compare pay and hours, and apply the same day.',
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
<p><strong>To find jobs near you today, search for one job title and one place at a time, for example "customer support Lahore", "cashier Chicago" or "team member Killeen", then open the employer's own page to check the branch, the shift and whether it is still open.</strong> Typing "jobs near me" alone gives you a mix of old adverts, agencies and jobs miles away. If you have no paid experience, look for requirements you can already show: answering a customer in English, using a spreadsheet or being free on weekends. Below are three real examples we checked on 4 November 2026, from Pakistan and the United States, and a method that works in any city.</p>

<h2>Three Real Local Vacancies Worth Comparing</h2>

<h3>Lahore, Pakistan: International Customer Support at ibex</h3>

<p>ibex's requisition 5781, Customer Support Specialist - International, is based in Lahore. The minimum education is <strong>O Levels or Matriculation</strong>. It asks for excellent written and spoken English and good use of Microsoft Office, especially Excel and Word. The posting does not ask for a set number of years of experience, which makes it realistic for a first job. One duty stands out: suggesting alternatives when a product is not available on the client's shopping website. Practise explaining a substitute both out loud and in a short email before you apply. Search by number on <a href="https://careers.ibex.co/" rel="noopener">ibex Careers</a>.</p>

<h3>Chicago, Illinois: On-Demand Retail at Target</h3>

<p>Target's on-demand Guest Advocate job (R0000458686) at 7100 S Cicero Avenue pays from <strong>$17.75 an hour</strong>, in a range up to $26.65. Chicago's own minimum wage is $17.05 from 1 July 2026, so this starts above the legal floor. "On demand" means you pick up available shifts in an app rather than getting a fixed weekly schedule. That suits someone whose free time changes, but not someone who needs guaranteed hours to pay rent; for that, ask the store about scheduled positions. Official site: <a href="https://corporate.target.com/careers/job-search/store/stores" rel="noopener">Target store jobs</a>.</p>

<h3>Killeen, Texas: Weekend Restaurant Work at Whataburger</h3>

<p>Whataburger's weekend Restaurant Team Member job (JR10000493) at Unit 928, 2509 Trimmier Road, is part time at about 24 hours a week. You must be at least 16, and current high school students qualify. Weekly pay is advertised, but no hourly rate is shown, so ask before you accept. Official site: <a href="https://careers.whataburger.com/us/en" rel="noopener">Whataburger Careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-near-me-hiring-now-how-to-apply-2.jpg"
         alt="Young job seeker smiling at a laptop with a map pin and job search icons, Jobs Near Me Hiring Now"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How Much Do Jobs Near Me Pay Per Month?</h2>

<p>For hourly jobs, monthly pay depends on hours, not just the rate. Use this formula: hourly rate × hours per week × 52 ÷ 12. At the Chicago listing's $17.75:</p>

<ul>
<li>20 hours a week: about <strong>$1,538 a month</strong> before tax</li>
<li>30 hours a week: about <strong>$2,308 a month</strong> before tax</li>
</ul>

<p>Neither figure is guaranteed under an on-demand arrangement, and both assume steady work all year. In Pakistan, where jobs are usually quoted monthly, ask for the gross salary, any allowances and the deductions in writing. Job adverts sometimes show one figure in the headline and a lower one in the description, so budget around the written offer, not the higher number. Also check the offer against your province's minimum wage: Punjab's is Rs 40,000 a month for 26 working days, from 1 July 2025.</p>

<h2>Requirements for First-Time Applicants</h2>

<p>Read the word next to each requirement. "Preferred" means you can still apply without it if you meet the minimum. "Required" means you need it before applying, whether it is a shift, a licence or a language. A job serving customers in another country, like ibex's international support, still needs you to come to the local office, so confirm where you would report and when your shift ends.</p>

<p>For fresher roles, search each of these titles separately: "trainee", "team member", "assistant" and "customer support representative". One search combining them all can hide a suitable job just because the employer used a different word.</p>

<h2>How to Apply for Jobs Near You Today</h2>

<ol>
<li><strong>Set a travel limit.</strong> Check the journey at the real shift time. A branch ten minutes away by car can take two buses on a Sunday morning.</li>
<li><strong>Read the whole posting.</strong> Save the employer, address, job number and closing date. If it says "future opening", treat it as a waiting list.</li>
<li><strong>Match your CV to the duties.</strong> For support work, describe a question you solved. For a stockroom, describe checking a delivery or organising supplies.</li>
<li><strong>Apply through the stated channel.</strong> Check your phone number, email and availability before you send, and keep the confirmation.</li>
<li><strong>Follow up once.</strong> If you were given a response date, wait until it passes, then ask about that specific role.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-near-me-hiring-now-how-to-apply-3.jpg"
         alt="Job seeker with a laptop and a sign reading Jobs Near Me, Hiring Now, How to Apply"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Using Your Network for a First Job</h2>

<p>A classmate, teacher or neighbour who works somewhere can tell you things a job advert cannot. Ask something they can answer: "I saw the weekend opening at your branch. Do they need Saturday mornings or closing shifts?" You learn something useful without asking anyone to promise you a job. If a vacancy was shared in a WhatsApp group, ask which branch is hiring and whether the manager wants an online application first. And always complete the official application, even if someone has introduced you.</p>

<h2>Interview Questions and Sample Answers</h2>

<p><strong>"Why should we hire you without experience?"</strong> "At our school event, I checked orders against a list and fixed a missing item before pickup. I haven't used your system yet, but I'm good at following a checklist." Use an example you really remember.</p>

<p><strong>"When can you start?"</strong> "I can do training on Tuesday and work afternoons from Wednesday." Ask whether the interview, orientation and first paid shift are on different days.</p>

<p><strong>"What would you do if a customer was upset?"</strong> Listen, check the details and offer the options you are allowed to give. If a refund needs approval, bring in the supervisor rather than promising something you cannot authorise.</p>

<h2>Frequently Asked Questions</h2>

<h3>Which nearby job is best for a fresher?</h3>
<p>One whose main tasks and shifts fit your life. Support roles suit strong communicators; weekend restaurant work can suit students.</p>

<h3>Can I apply to several branches of the same company?</h3>
<p>Yes, if each has its own vacancy. Note each branch and job number so you do not mix up hours or pay.</p>

<h3>How do I know a job near me is still open?</h3>
<p>Open the employer's own listing before applying or travelling to an interview. Job-board copies can stay up after the job closes.</p>

<h3>Do I need experience for the ibex Lahore support job?</h3>
<p>The posting asks for O Levels or Matriculation, strong English and MS Office, and does not state a minimum number of years.</p>

<h3>Is "on demand" the same as part time?</h3>
<p>No. On-demand staff pick up shifts when available rather than getting a set weekly schedule.</p>

<h2>Bottom Line</h2>

<p>Choose a workplace you can reach, confirm what the shift pays and how many hours you will really get, and apply with one example that fits the duties. Those three checks beat sending the same CV everywhere.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://careers.ibex.co/" rel="noopener">ibex Careers</a>, Customer Support Specialist - International, Lahore, requisition 5781</li>
<li><a href="https://corporate.target.com/careers/job-search/store/stores" rel="noopener">Target store jobs</a>, R0000458686, 7100 S Cicero Ave, Chicago</li>
<li><a href="https://careers.whataburger.com/us/en" rel="noopener">Whataburger Careers</a>, JR10000493, Killeen</li>
<li><a href="https://www.chicago.gov/city/en/depts/bacp/supp_info/minimumwageinformation.html" rel="noopener">City of Chicago: Minimum wage</a>, all checked November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/killeen-tx-hiring-immediately-no-experience-jobs-2026">No Experience Jobs in Killeen, TX</a>, the full Killeen list.</li>
    <li><a href="/blog/cashier-jobs-chicago-no-experience">Cashier Jobs in Chicago</a>, more on Target and the city minimum wage.</li>
    <li><a href="/blog/call-centre-jobs-karachi-sindh-freshers">Call Centre Jobs in Karachi for Freshers</a>, support work in Pakistan.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you share documents.</li>
</ul>

HTML;
    }
}
