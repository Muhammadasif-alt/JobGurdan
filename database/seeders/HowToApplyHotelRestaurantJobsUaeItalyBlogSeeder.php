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

/**
 * "How to Apply for Hotel and Restaurant Jobs in the UAE and Italy" — blog-292.
 *
 * The second hotel brief, published as its own page at the owner's request.
 * It is the application-process guide; the rules guide is blog-289 and is
 * linked from it. What the brief carried that could not be published as sent:
 *
 * 1. Ten "Apply Now" buttons, four of them Indeed searches. Body links stay
 *    internal; the employer portals that resolve (Jumeirah, Rotana, Accor
 *    Italy, Marriott) are the job listings' apply URLs. IHG's careers site
 *    returned 403 to every check, so IHG is named but not linked. Marriott's
 *    search URL ignores its location filter, so its listing links the home.
 *
 * 2. The "Recruitment sources checked: 6 October 2026" line and its links,
 *    replaced by the site's own sourcing note.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-apply-for-hotel-and-restaurant-jobs-in-uae-and-italy';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'How to Apply for Hotel and Restaurant Jobs in the UAE and Italy',
                'excerpt' => "Pick one role, tailor your CV to it, apply through the hotel group's own careers portal and check the written offer. A job advert does not include visa sponsorship, and in Italy the employer must request a nulla osta.",
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-hotel-restaurant-jobs-uae-italy.jpg',
                'tags' => 'hotel jobs dubai how to apply, restaurant jobs italy, housekeeping jobs uae, cameriere jobs italy, hotel careers portal, hospitality cv tips, apply for hotel jobs abroad, rotana jumeirah accor marriott',
                'meta_title' => 'How to Apply for Hotel and Restaurant Jobs in UAE and Italy',
                'meta_description' => 'Step-by-step way to apply for hotel, housekeeping and waiter jobs in the UAE and Italy: which roles to target, CV tips and work permission checks.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        $listings = [
            [
                'advertiser' => ['Jumeirah Careers (Aggregated)', 'jumeirah-careers-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Hotel and Guest Service Staff — Jumeirah Careers (UAE)',
                'apply' => 'https://www.jumeirah.com/en/careers',
                'meta' => "Guest service, housekeeping and food and beverage roles on Jumeirah's own careers page. Choose a vacancy and apply there.",
                'keywords' => 'jumeirah careers, hotel jobs dubai, hospitality jobs uae, hotel jobs uae how to apply',
                'country' => 'the UAE',
                'language' => 'English',
            ],
            [
                'advertiser' => ['Rotana Careers (Aggregated)', 'rotana-careers-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Hotel Staff — Rotana Careers (UAE)',
                'apply' => 'https://www.rotanacareers.com/',
                'meta' => "Rotana's own careers portal, with hotel vacancies in the UAE and other countries. Check the property and department before you apply.",
                'keywords' => 'rotana careers, hotel jobs uae, rotana hotel jobs, hospitality jobs dubai',
                'country' => 'the UAE',
                'language' => 'English',
            ],
            [
                'advertiser' => ['Accor Careers Italy (Aggregated)', 'accor-careers-italy-aggregated'],
                'location' => ['Italy', 'Italy'],
                'position' => 'Hotel and Restaurant Staff — Accor Careers Italy',
                'apply' => 'https://careers.accor.com/global/en/italy',
                'meta' => "Accor's Italy careers page, with student and graduate routes. Open it, pick a vacancy and read its requirements before applying.",
                'keywords' => 'accor careers italy, hotel jobs italy, hospitality jobs italy, hotel jobs italy how to apply',
                'country' => 'Italy',
                'language' => 'Italian',
            ],
            [
                'advertiser' => ['Marriott Careers (Aggregated)', 'marriott-careers-aggregated'],
                'location' => ['Italy', 'Italy'],
                'position' => 'Hotel Staff — Marriott Careers (Italy and the UAE)',
                'apply' => 'https://careers.marriott.com/',
                'meta' => "Marriott's careers site. Search by country or city, then read each hotel vacancy on its own before you apply.",
                'keywords' => 'marriott careers, hotel jobs italy, hotel jobs dubai, marriott hotel jobs abroad',
                'country' => 'Italy and the UAE',
                'language' => 'Italian',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription($listing['country']),
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Shift-based, including evenings, weekends and holidays',
                    'language' => $listing['language'],
                    // Pay depends on the employer, the contract and the season;
                    // this site does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function jobDescription(string $country): string
    {
        return <<<HTML
<p>This listing links to the hotel group's own careers page for {$country}. It is a starting point, not a single open vacancy: open the page, choose a role that matches your experience and read its requirements.</p>

<h3>Before you apply</h3>
<ul>
    <li>Check that the vacancy names the hotel, the department and the duties</li>
    <li>A job advert does not include visa sponsorship. Ask the employer directly and get any offer in writing</li>
    <li>You should not pay anyone for a job or a work permit</li>
</ul>

<p><strong>Note:</strong> vacancies, requirements and work permit rules are set by the employer and the authorities, not by JobGader. Confirm them on the official page before you travel.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You can find hotel and restaurant jobs in the UAE and Italy by searching the careers pages of the hotel groups themselves. Look for waiter, housekeeping, receptionist, kitchen helper, cook and restaurant supervisor roles, prepare one focused CV, and check the experience, language and work permission requirements before you apply. A job advert does not automatically include visa sponsorship. For the rules behind each country, read <a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">Hotel and Restaurant Jobs in the UAE and Italy</a>; this guide is about the application itself.</p>

<h2>Which Hospitality Jobs Should You Target?</h2>

<p>Choose roles that match what you can actually do, rather than applying for everything. Hotels and restaurants have separate departments, and each one wants different evidence.</p>

<ul>
    <li><strong>Waiter or waitress:</strong> taking orders, serving food, explaining menus and handling guest requests.</li>
    <li><strong>Housekeeping attendant:</strong> cleaning rooms, changing linen and following hygiene procedures.</li>
    <li><strong>Receptionist:</strong> reservations, arrivals, payments and guest communication.</li>
    <li><strong>Kitchen helper or steward:</strong> washing equipment, keeping the kitchen clean and supporting the cooks.</li>
    <li><strong>Cook or chef:</strong> preparing dishes, managing ingredients and keeping to food safety rules.</li>
    <li><strong>Restaurant supervisor:</strong> coordinating staff, service standards, schedules and guest complaints.</li>
</ul>

<h2>Where Do I Apply for Hotel Jobs in the UAE?</h2>

<p>Start with the hotel group's own careers page, because that is where genuine vacancies are posted. Jumeirah, Rotana, Marriott and Accor all run portals that cover UAE hotels, and the job listings on this page link to the official pages. Pick your city, filter by department, then read the actual vacancy: the hotel, the duties and the requirements matter more than the job title.</p>

<p>Save the vacancy reference and your application confirmation, so you can follow up on the right role. If an advert sends you to a third-party site, go back to the employer's own page and look for the same job there. See also <a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> for front desk roles.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-apply-hotel-restaurant-jobs-uae-italy-front-desk.jpg"
         alt="A smiling hotel receptionist at a front desk with a service bell, with a waiter, a chef plating a dish, the Dubai skyline, the Colosseum and the UAE and Italian flags in smaller pictures around her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Do I Apply for Hotel and Restaurant Jobs in Italy?</h2>

<p>Search with both English and Italian job titles. Useful terms are <strong>cameriere</strong> (waiter), <strong>cuoco</strong> (cook), <strong>lavapiatti</strong> (dishwasher) and <strong>receptionist</strong> for front desk roles. Accor's Italy careers page includes student and graduate routes, and Marriott and IHG also list hotels in Italy on their own portals. IHG's site did not open for our check, so search it directly rather than through a link here.</p>

<p>Do not assume that a restaurant advertising locally will arrange immigration permission. A global hotel brand being present in Italy does not guarantee sponsorship for every role either. Check the language requirement as well: employers usually expect you to deal with guests in Italian, so learn basic restaurant Italian first.</p>

<h2>What Documents Do I Need to Apply?</h2>

<p>Prepare a readable CV, your contact details, your employment history, references and any training certificates. Mention reservation systems, cuisines, languages and food safety training where they apply. Share passport copies only through a verified recruitment process, and only when the employer asks for them.</p>

<p>Entry roles may accept limited experience. Specialist kitchen jobs and supervisor roles usually want stronger evidence, so match your CV to the level you are applying for.</p>

<h2>Check Work Permission Before You Travel</h2>

<p>In the UAE the employer arranges your work permit, and you <strong>cannot work on a visit or tourist visa</strong>. Under <strong>Article 6 of Federal Decree-Law No. 33 of 2021</strong> the employer may not charge you recruitment and employment costs, directly or indirectly. Confirm the written offer and the employer's identity before you make any travel commitment.</p>

<p>For a non-EU applicant in Italy, <strong>the employer applies, not you</strong>: the employer requests a nulla osta (work authorisation) under the Decreto Flussi quota, and a work visa and residence permit follow. Quotas and exceptions decide who is eligible, so verify your route before you accept any promise about relocation. The dates and numbers are in <a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">our Decreto Flussi guide</a>.</p>

<h2>How Do I Check a Salary and an Offer?</h2>

<p>There is no single pay figure that covers every hospitality role. Ask for the details in writing: base pay, hours, overtime arrangements, deductions, accommodation, meals, transport and the length of the contract. Compare the take-home income with the cost of living where you will work. Treat tips and service charges as uncertain unless the contract documents them.</p>

<p>Hotel work is shift work, and the UAE night overtime premium <strong>does not apply to workers on shift patterns</strong>, so get any night or weekend allowance written into the contract.</p>

<h2>How Do I Build a Strong Application?</h2>

<p>Keep your CV focused on the advertised position. A waiter should describe table service, order accuracy and handling complaints. A housekeeper should explain cleaning standards and room preparation. A cook should name the cuisines, the kitchen stations and the responsibilities. Use honest examples from jobs, training, volunteering or a family business.</p>

<p>Write a short introduction that says which role you want, what experience you have, where you are now and when you can start. State your work authorisation honestly, and do not claim permission or sponsorship that you do not have. Upload the file format the employer asks for, and check your phone number and email address twice.</p>

<h2>A Simple Application Plan</h2>

<ol>
    <li>Choose two job titles and your preferred cities.</li>
    <li>Check the official careers portals for matching vacancies.</li>
    <li>Read the requirements and tailor your CV to each role.</li>
    <li>Record the employer, vacancy reference, date and status of every application.</li>
    <li>Prepare interview examples about guest service, teamwork, hygiene and busy shifts.</li>
    <li>Read the written offer carefully before you agree to relocate.</li>
</ol>

<p>In an interview, ask who supervises the role, how shifts are arranged and what training is provided. For seasonal work, ask about the start and end dates and where you will live after the contract finishes. If housing is included, ask about shared rooms, travel distance and any deductions.</p>

<p>Follow up through the channel named in the advert, and avoid sending the same application again and again. If the role closes, keep searching for positions that fit your skills, and keep copies of your applications and any written offer.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/hospitality-tourism" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Hospitality Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply for hotel and restaurant jobs?</h3>
<p>Yes, when the vacancy accepts beginners. Target trainee, steward, housekeeping or kitchen support roles, and highlight reliability, communication and practical experience.</p>

<h3>Is visa sponsorship guaranteed?</h3>
<p>No. Ask the employer directly. Neither a job advert nor an official careers portal guarantees sponsorship or immigration approval.</p>

<h3>Do the apply links on this page submit my application?</h3>
<p>No. They open the employer's recruitment page. Choose a vacancy, read its requirements and complete the application there.</p>

<h3>Should I pay to secure a UAE job?</h3>
<p>No. UAE employers cannot charge workers recruitment and employment costs. Question any payment request, and verify the employer through official channels.</p>

<h3>Do I need Italian to apply for a restaurant job in Italy?</h3>
<p>Employers usually expect you to communicate with guests and staff, so learn basic restaurant Italian before you apply.</p>

<h3>Can I work in a hotel on a visit visa?</h3>
<p>No. You need a work permit arranged through the employer, and a visit or tourist visa does not allow you to work.</p>

<h3>How do I know a hotel job offer is real?</h3>
<p>Find the same vacancy on the hotel group's own careers page, check that the email comes from its official domain, and insist on a formal written contract before you pay or travel.</p>

<h3>What should I check before accepting an offer?</h3>
<p>The employer's legal name and the hotel's address, plus the duties, pay, hours, accommodation and contract length. Ask for clarification when the advert, the interview and the contract describe different terms.</p>

<h2>People Also Search For</h2>

<h3>How to apply for hotel jobs in Dubai</h3>
<p>Use the hotel group's own careers page and apply to a specific vacancy.</p>

<h3>Rotana careers</h3>
<p>Rotana runs its own careers portal with hotel vacancies in the UAE and other countries.</p>

<h3>Jumeirah careers</h3>
<p>Jumeirah lists hotel roles on its official careers page.</p>

<h3>Accor careers Italy</h3>
<p>Accor's Italy page includes student and graduate routes.</p>

<h3>Cameriere jobs in Italy</h3>
<p>Search with the Italian title for waiter roles.</p>

<h3>Housekeeping jobs in the UAE</h3>
<p>A common entry route; the employer arranges the work permit.</p>

<h3>Hotel CV tips</h3>
<p>Match your CV to one role and give honest, specific examples.</p>

<h3>Work permit for hotel jobs in Italy</h3>
<p>The employer requests a nulla osta under the Decreto Flussi quota.</p>

<h2>More Job Guides</h2>

<p>Related hospitality and Gulf guides:</p>

<ul>
    <li><a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">Hotel and Restaurant Jobs in the UAE and Italy</a> &mdash; the visa routes and the Italian quota.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the US hotel route.</li>
    <li><a href="/blog/how-to-apply-for-marriott-hotel-jobs-in-the-usa">How to Apply for Marriott Hotel Jobs in the USA</a> &mdash; one hotel group in detail.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front desk roles in the Emirates.</li>
    <li><a href="/blog/airport-ground-staff-jobs-in-dubai">Airport Ground Staff Jobs in Dubai</a> &mdash; shift work and the night premium exception.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UAE rules are from u.ae and Italian rules from the Interior and Labour ministries' 2027 Decreto Flussi announcements. Vacancies change, and an employer's portal is not a guarantee of an open vacancy or of sponsorship. Confirm everything with the official authority before you act.</p>
HTML;
    }
}
