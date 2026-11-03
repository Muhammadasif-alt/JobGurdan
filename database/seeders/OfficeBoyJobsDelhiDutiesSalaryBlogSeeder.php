<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Office Boy Jobs in Delhi: Duties and Salary"
 *
 * Checked on 3 November 2026. What changed against the brief:
 *
 * 1. The three Indeed buttons and the Saket Indeed advert are dropped. The JobX link carried a vacancy ID, so the
 *    vacancy is described as text and the guide points readers to the portals instead.
 * 2. The Delhi Labour Department pages do not resolve from our network; they were read from the server with a plain
 *    request. The newest order on the list is dated 15 April 2025, effective 1 April 2025: unskilled Rs 18,456,
 *    semi-skilled Rs 20,371 (19,929 + 442), skilled Rs 22,411 a month. No later order is listed.
 * 3. JobX vacancy 80591 read: VIVEA MANAGEMENT SERV, New Delhi, Rs 19,000-22,000 CTC, 10th pass, day and night rotation,
 *    meals, PF and ESI, no accommodation, male and female, valid until 6 November 2026, same-state candidates. Its
 *    company address is a Hyderabad pincode, which the guide tells readers to check.
 * 4. The owner's posters print salaries from Rs 15,000, below the unskilled minimum. The owner chose to use them as
 *    they are (3 November 2026); the text states the legal figures.
 *
 * Posters are the owner's (blog-357, 2 Nov 10.30 to 10.32 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class OfficeBoyJobsDelhiDutiesSalaryBlogSeeder extends Seeder
{
    public const SLUG = 'office-boy-jobs-delhi-duties-salary';

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
                'title' => 'Office Boy Jobs in Delhi: Duties and Salary',
                'excerpt' => 'What office boys in Delhi actually do, the Rs 18,456 minimum wage for unskilled work from 1 April 2025, why a CTC figure is not your take-home pay, and how freshers can apply.',
                'content' => $content,
                'featured_image' => 'blogs/office-boy-jobs-delhi-duties-salary.jpg',
                'tags' => 'office boy jobs in delhi, office boy salary in delhi, office attendant jobs in delhi, office helper jobs for freshers, delhi minimum wage 2025, peon jobs in delhi, pantry boy jobs delhi, office assistant jobs delhi',
                'meta_title' => 'Office Boy Jobs in Delhi: Duties and Salary',
                'meta_description' => 'Office boy jobs in Delhi: daily duties, the Rs 18,456 minimum wage for unskilled work, CTC versus take-home pay, and how freshers can apply.',
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
<p><strong>Office boy jobs in Delhi usually mix pantry service, keeping the office tidy and running errands: preparing a meeting room, scanning a signed letter and handing a parcel to a courier can all happen in one shift.</strong> Before you accept any offer, compare it with Delhi's minimum wage. Under the Labour Department's order effective 1 April 2025, the monthly minimum is <strong>Rs 18,456 for unskilled work</strong>, Rs 20,371 for semi-skilled and Rs 22,411 for skilled. An offer below the right rate for your work is a warning sign, whatever an advert or poster says. We checked the order and a live vacancy on 3 November 2026.</p>

<h2>What Does an Office Boy Do?</h2>

<p><strong>Pantry and meeting rooms.</strong> Before a meeting, confirm the room, the number of visitors and what refreshments are needed. Afterwards, clear the cups, wipe the table and refill drinking water. Ask who cleans washrooms and mops floors, because some offices combine housekeeping with office support and others do not.</p>

<p><strong>Documents and couriers.</strong> A runner may deliver signed papers, collect parcels and bring back receipts. If accounts gives you an envelope for another branch, confirm the person, address and deadline, and whether you need a signed acknowledgement. Keep it sealed and return the receipt to whoever gave you the errand. Ask how fares are repaid before you spend your own money.</p>

<p><strong>Printing, scanning and supplies.</strong> You may need two copies of a document, both sides scanned and the file saved where a colleague can find it. Ask whether the job also includes spreadsheet entries, covering reception or email. The title does not tell you how much computer work there is.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-boy-jobs-delhi-duties-salary-2.jpg"
         alt="Young office assistant with an ID lanyard holding files and giving a thumbs up, with India Gate in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Delhi Minimum Wage: The Figure to Check</h2>

<p>The Delhi Labour Department's order dated 15 April 2025 sets these monthly rates, effective 1 April 2025, for scheduled employments in Delhi:</p>

<ul>
<li><strong>Unskilled:</strong> Rs 18,456</li>
<li><strong>Semi-skilled:</strong> Rs 20,371</li>
<li><strong>Skilled:</strong> Rs 22,411</li>
</ul>

<p>It is the newest order listed on the department's page. There are separate clerical and supervisory rates too, so ask which category your work falls under. These rates are for Delhi; an advert that says "Delhi NCR" may really be in Noida or Gurugram, where other rules apply. Source: <a href="https://labour.delhi.gov.in/labour/current-minimum-wage-rate" rel="noopener">Delhi Labour Department: Current minimum wage rates</a>.</p>

<h2>A Real Vacancy and What Its Pay Means</h2>

<p>On the government-backed NSDC JobX portal, VIVEA MANAGEMENT SERV advertised a full-time office boy post in New Delhi at <strong>Rs 19,000 to 22,000 a month CTC</strong>. It asks for 10th pass, works day and night rotational shifts, lists meals, PF and ESI, does not provide accommodation, is open to both men and women, and was listed as valid until 6 November 2026. Its company address carries a Hyderabad pincode, so confirm where you would actually work and who will pay you before you go anywhere.</p>

<p>CTC means cost to company. It includes the employer's own PF and ESI contributions, so the money in your account each month will be lower than the CTC. Ask HR for three figures in writing: gross monthly wages, your deductions and your expected take-home pay. Compare the gross wage, not the CTC, with the minimum wage.</p>

<p>Count travel too. If one job costs Rs 80 a day more to reach and you work 26 days, that is Rs 2,080 a month. An offer paying Rs 2,000 more would leave you slightly worse off, before you count the extra time.</p>

<h2>Can Freshers Apply?</h2>

<p>Yes, where the advert does not ask for experience. Search "office helper", "office attendant", "pantry assistant" and "dispatch runner" as well as "office boy". Read education and experience as separate conditions; 10th pass alone does not make a post a fresher role. If Hindi is your first language, ask which tasks need English: reading names on envelopes is not the same as writing emails to clients.</p>

<p>Keep your CV to one page with your area, education, availability and any machines you can use. If you helped in a family shop, say what you did, for example "Received parcels, matched quantities to delivery slips and filed receipts", and label it as family shop experience.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-boy-jobs-delhi-duties-salary-3.jpg"
         alt="Office assistant in a white shirt holding a folder in a Delhi office, with India Gate and duty icons"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply and Prepare for Interview</h2>

<p>Search in areas you can reach by the start time. Save the employer name, office address, salary wording and vacancy number. If a staffing company supplies you to a client office, ask who issues your appointment letter, pays your salary and approves leave. Government job portals such as the National Career Service and NSDC JobX list Delhi vacancies; never pay anyone to get a job.</p>

<p>Prepare one example of handling two requests at once. If a manager asks for tea just as a courier arrives for an urgent pickup, explain how you would check the deadline, tell the manager, and finish both without losing the receipt.</p>

<h2>A Practical Test You Can Prepare For</h2>

<p>Practise at home or in a print shop, with permission: copy a sample letter without personal details, scan it and name the file so someone else can find it by date and subject. Check that all four corners show and the pages are in order. If you have never used a particular machine, ask for a demonstration rather than pretending.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum salary for an office boy in Delhi?</h3>
<p>Under the order effective 1 April 2025, Rs 18,456 a month for unskilled work, Rs 20,371 for semi-skilled and Rs 22,411 for skilled. Ask which category applies to your duties.</p>

<h3>Is CTC my salary?</h3>
<p>No. CTC includes the employer's contributions. Ask for the gross wage and expected take-home pay separately.</p>

<h3>Is a bike compulsory?</h3>
<p>Only if the employer requires it. For a field runner role, ask how often trips happen and who pays fuel and parking.</p>

<h3>Are office boy jobs open to women?</h3>
<p>Check the eligibility section. The JobX vacancy above invited both men and women; "office attendant" searches also help.</p>

<h3>What should I ask about late shifts?</h3>
<p>The usual finish time, the weekly day off and transport home, including the last metro or bus at the time you would really leave.</p>

<h2>Bottom Line</h2>

<p>Choose a reachable office with clear duties, get the gross wage and take-home pay in writing, and check them against Delhi's minimum wage before you judge an offer by its headline figure.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://labour.delhi.gov.in/labour/current-minimum-wage-rate" rel="noopener">Delhi Labour Department: Current minimum wage rates</a>, order dated 15 April 2025, effective 1 April 2025</li>
<li>NSDC JobX: VIVEA MANAGEMENT SERV office boy vacancy, New Delhi, valid until 6 November 2026</li>
<li><a href="https://www.ncs.gov.in/" rel="noopener">National Career Service</a>, all checked 3 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/back-office-executive-jobs-delhi-duties-salary">Back Office Executive Jobs in Delhi</a>, the next step up in an office.</li>
    <li><a href="/blog/cashier-jobs-chicago-no-experience">Cashier Jobs in Chicago</a>, another first job, abroad.</li>
    <li><a href="/blog/petrol-pump-attendant-jobs-lahore-duties-salary">Petrol Pump Attendant Jobs in Lahore</a>, entry-level work in Pakistan.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
