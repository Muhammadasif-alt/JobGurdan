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
 * "Kitchen Helper Jobs in Saudi Arabia" — entry-level kitchen work in hotels,
 * restaurants, hospitals and catering. The cleaner, driver, security guard and
 * construction guides own their own occupations, so this one owns kitchen pay
 * against the referral wages, the food handler health certificate, the Labour
 * Law hours and the Saudization rules in hospitality.
 *
 * Corrections to the draft:
 *
 * 1. It calls the iqama a work visa and says "your employer or recruitment
 *    agency typically sponsors" it. The iqama is the residence permit, and
 *    Article 40 of the Labour Law puts recruitment, iqama and work permit
 *    fees, profession changes, exit and re-entry visas and the return ticket
 *    on the employer.
 *
 * 2. Its entry band of SAR 1,100 to 1,500 is below the Indian Embassy's 2025
 *    minimum referral wage of SAR 1,600 for helpers. Saudi Arabia sets no
 *    minimum wage for expatriate workers at all.
 *
 * 3. It never mentions the health certificate that every worker in a food
 *    establishment needs, issued through the Balady platform.
 *
 * 4. It gives 21 to 30 as a typical age range as though it were a rule. The
 *    Labour Law sets 15 as the minimum working age and no upper limit of 30.
 *
 * 5. It leaves out Saudization. Localization of 41 tourism professions began
 *    on 22 April 2026, and Madinah already localizes 40% of restaurant and
 *    50% of cafe jobs.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class KitchenHelperJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/q-kitchen-helper-jobs.html';

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
        $title = 'Kitchen Helper Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Saudi Arabia sets no minimum wage for foreign workers, the Indian Embassy puts the referral wage for helpers at SAR 1,600 a month, the employer must pay every visa and iqama fee, and food workers need a health certificate.',
                'content' => $content,
                'featured_image' => 'blogs/kitchen-helper-jobs-in-saudi-arabia.jpg',
                'tags' => 'kitchen helper jobs saudi arabia, kitchen helper salary saudi, saudi labour law article 40, balady health certificate, saudi work visa iqama, hotel jobs saudi arabia, saudization hospitality, overseas employment promoter, minimum referral wage saudi',
                'meta_title' => 'Kitchen Helper Jobs in Saudi Arabia 2026: Pay and Rules',
                'meta_description' => 'Kitchen helper jobs in Saudi Arabia: what the job pays, who pays for the visa and iqama, the food health certificate, hours and how to apply safely.',
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
            ['name' => 'Saudi Hotels, Restaurants & Catering Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'sa-kitchen-helper-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Kitchen Helper — Hotels, Restaurants, Hospitals and Catering Companies, Saudi Arabia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => '8 hours a day and 48 a week, reduced to 6 a day for Muslim workers during Ramadan',
                'language' => 'English, Arabic',
                // Packages differ by whether housing, food and transport are
                // provided, so the basic salary alone does not compare.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Kitchen helper roles in Saudi hotels, restaurants, hospitals and catering. Employer-sponsored visa and iqama; food health certificate required.',
                'seo_keywords' => 'kitchen helper jobs saudi arabia, kitchen staff jobs saudi, hotel kitchen jobs saudi arabia, catering jobs saudi arabia, helper jobs gulf',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Hotels, restaurants, hospital kitchens and catering companies across Saudi Arabia hire kitchen helpers, usually on Labour Law contracts with an employer-sponsored visa and iqama, and often with accommodation, meals and transport provided.</p>

<h3>What the work involves</h3>
<p>Washing and preparing vegetables, fruit and meat, helping chefs with basic preparation, washing dishes and equipment, keeping workstations clean, and following food hygiene rules.</p>

<h3>Requirements</h3>
<ul>
    <li>A health certificate for food establishment workers, issued through the Balady platform after a medical examination</li>
    <li>Basic English; Arabic helps but is rarely required</li>
    <li>The stamina for long shifts, and a contract recorded on Qiwa</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>No statutory minimum for foreign workers.</strong> The Indian Embassy's 2025 referral wage for helpers is <strong>SAR 1,600</strong> a month, and SAR 2,000 for a hotel cook</li>
    <li><strong>Overtime</strong> is the hourly wage plus 50% of basic pay, and wages must be paid by bank transfer under the Wage Protection System</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Never pay for the visa.</strong> Under Article 40 of the Labour Law the employer pays recruitment, iqama and work permit fees, profession changes, exit and re-entry visas and your return ticket, and no employer may keep your passport.</p>

<p><strong>Note:</strong> wages, contracts and visa rules are set by employers, the Ministry of Human Resources and Social Development and your own country's emigration authority &mdash; not by JobGader. Confirm the terms before you pay any agency or sign.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Kitchen helper is one of the most advertised entry-level jobs in the Gulf, and Saudi hotels, restaurants, hospitals and catering companies do hire freshers. What most guides leave out is the part that decides whether the offer is worth taking: the wage floor your own embassy sets, who pays for the visa, the health certificate you cannot work without, and what the Labour Law guarantees you once you arrive.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-kitchen-helper-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127869; Browse Kitchen Helper Jobs in Saudi Arabia &rarr;
    </a>
</div>

<h2>What the Job Pays, and the Floor to Measure It Against</h2>

<p>Guides give a fresher band of SAR 1,100 to 1,500 a month. Two things put that in context:</p>

<ul>
    <li><strong>Saudi Arabia sets no minimum wage for expatriate workers.</strong> The SAR 4,000 figure you see quoted applies to Saudi nationals counted in the Nitaqat system, not to you.</li>
    <li><strong>Your embassy sets one instead.</strong> The Indian Embassy in Riyadh's 2025 referral wages list <strong>SAR 1,600 a month for "all kind of helpers"</strong>, SAR 1,600 for waiters and stewards and <strong>SAR 2,000 for a hotel cook</strong>. An offer below that is below the wage India will endorse.</li>
</ul>

<p>Free accommodation, food and transport are common and change the value of an offer, so get every line in writing: basic salary, housing, food, transport, medical cover, annual leave flights and the end-of-service award. Wages must be paid by bank transfer through the <strong>Wage Protection System</strong>, which has run since 2013 and now covers over a million establishments, about 94% of the private sector.</p>

<h2>Who Pays for the Visa: Article 40</h2>

<p>Guides say "your employer or recruitment agency typically sponsors the work visa (iqama)". The iqama is the <strong>residence permit</strong>, not the visa, and the law is specific about the bill. <strong>Article 40</strong> of the Labour Law requires the employer to bear:</p>

<ul>
    <li>Recruitment fees for the non-Saudi worker</li>
    <li>Fees for issuing and renewing the <strong>iqama and work permit</strong>, and any late fines</li>
    <li>Fees for changing profession, and exit and re-entry visas</li>
    <li>The return ticket home at the end of the relationship</li>
</ul>

<p>You pay the cost of going home only if you are unfit for work or you leave without a legitimate reason. The ministry's guide is equally clear that no employer may keep your passport or iqama.</p>

<h2>The Health Certificate Every Food Worker Needs</h2>

<p>No guide mentions it. Workers in food and public health establishments need a <strong>health certificate</strong>, issued electronically through the Balady platform. It requires a valid medical examination report from a health centre approved by the Ministry of Health, plus a health education course in the regions where one applies, and takes around 10 days. Ask your employer who arranges and pays for it before you travel.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/kitchen-helper-jobs-in-saudi-arabia-kitchen.jpg"
         alt="Kitchen staff in white jackets and aprons preparing salads and vegetables in a large hotel kitchen in Riyadh"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Hours, Overtime and Leave Under the Labour Law</h2>

<ul>
    <li><strong>Hours:</strong> no more than <strong>8 hours a day or 48 a week</strong>, reduced to 6 a day and 36 a week for Muslim workers during Ramadan.</li>
    <li><strong>Breaks and rest:</strong> at least 30 minutes after five consecutive hours, and a weekly rest day, Friday by default, which cannot be exchanged for cash.</li>
    <li><strong>Overtime:</strong> the hourly wage plus <strong>50% of the basic wage</strong>, or compensatory paid leave if you agree to it. Work on a public holiday counts as overtime.</li>
    <li><strong>Annual leave:</strong> at least <strong>21 days</strong>, rising to 30 days after five continuous years with the employer.</li>
    <li><strong>End-of-service award:</strong> half a month's wage for each of the first five years, then a full month for each year after. If you resign you get a third of it after two to five years, two thirds after five to ten, and all of it after ten.</li>
    <li><strong>Probation:</strong> no more than <strong>180 days</strong>.</li>
</ul>

<h2>Saudization: Which Hospitality Jobs Are Reserved</h2>

<p>Guides never mention localization, and it decides which jobs are open to you. The Ministry of Human Resources and Social Development, with the Ministry of Tourism, is localizing <strong>41 tourism professions</strong> in phases starting <strong>22 April 2026</strong>, then January 2027 and January 2028. The first phase covers 28 professions, with roles such as receptionist and hotel receptionist reserved 100% for Saudis. Kitchen roles are not among the professions named in that phase.</p>

<p>Regional rules go further: in Madinah, <strong>40% of restaurant jobs and 50% of cafe jobs</strong> are localized, although restaurants and cafes inside hotels and hotel apartments, and cafeterias in factories, offices, hospitals and schools, are excluded.</p>

<h2>Is the Sector Really Growing?</h2>

<p>Yes, and the official numbers are better than the vague ones guides use. GASTAT counted <strong>983,253 people working in tourism activities</strong> in the first quarter of 2025, up from 944,299 a year earlier, and <strong>75.2% of them were non-Saudi</strong>. Tourism is about 5.4% of all employment in the economy, and Vision 2030's visitor target was raised to <strong>150 million</strong> a year.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/kitchen-helper-jobs-in-saudi-arabia-hotel.jpg"
         alt="Hotel housekeeping and room service staff working in a guest room while a receptionist takes a call behind them"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Age: What the Law Actually Says</h2>

<p>Guides give a "typical age range" of 21 to 30 as if it were a rule. The Labour Law only sets a minimum: <strong>no one under 15</strong> may be employed. Employers may prefer a certain age, but there is no legal ceiling at 30, and work permit rules for basic-skill categories set the upper limit at 60.</p>

<h2>Applying Safely From Pakistan or India</h2>

<ul>
    <li><strong>Pakistan:</strong> use an <strong>Overseas Employment Promoter</strong> licensed by the Bureau of Emigration and Overseas Employment, check the licence on the active list, and register with the Protector of Emigrants. The Bureau caps what an agency may charge.</li>
    <li><strong>India:</strong> use a recruiting agent registered on eMigrate. Indian missions say an agent <strong>cannot charge more than Rs 30,000 plus GST</strong>, and that the salary should match the embassy's minimum referral wage.</li>
    <li><strong>Everywhere:</strong> the Saudi employer pays the recruitment and visa costs. A demand for a large advance payment for a "guaranteed" job is the clearest sign of a scam.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is the salary of a kitchen helper in Saudi Arabia?</h3>
<p>There is no statutory minimum for foreign workers. The Indian Embassy's 2025 referral wage for helpers is SAR 1,600 a month, and SAR 2,000 for a hotel cook, usually with accommodation, food and transport provided.</p>

<h3>Do kitchen helper jobs need experience?</h3>
<p>Often not. Hotels and catering companies hire freshers and train them, but you still need the food establishment health certificate.</p>

<h3>Who pays for the work visa and iqama?</h3>
<p>The employer. Article 40 of the Labour Law puts recruitment, iqama and work permit fees, profession changes, exit and re-entry visas and the return ticket on the employer.</p>

<h3>What is the Balady health certificate?</h3>
<p>The certificate workers in food establishments must hold. It is issued electronically after a medical examination at a Ministry of Health-approved centre, with a health education course in some regions, and takes about 10 days.</p>

<h3>How many hours does a kitchen helper work?</h3>
<p>Up to 8 hours a day and 48 a week, or 6 and 36 for Muslim workers in Ramadan, with a 30-minute break after five hours and a weekly rest day.</p>

<h3>Is overtime paid in Saudi Arabia?</h3>
<p>Yes, at the hourly wage plus 50% of the basic wage, or as compensatory paid leave if you agree.</p>

<h3>Is there an age limit for kitchen helper jobs?</h3>
<p>The law sets 15 as the minimum working age and no ceiling at 30. Basic-skill work permits set an upper limit of 60.</p>

<h3>Do I need to speak Arabic?</h3>
<p>Basic English is usually enough in hotel and catering kitchens. Arabic helps but is rarely a strict requirement.</p>

<h2>People Also Search For</h2>

<h3>Kitchen helper salary in Saudi Arabia</h3>
<p>No legal minimum for foreign workers; India's referral wage for helpers is SAR 1,600 a month.</p>

<h3>Saudi Labour Law Article 40</h3>
<p>Puts recruitment, iqama, work permit, profession change, exit and re-entry fees and the return ticket on the employer.</p>

<h3>Balady health certificate for food workers</h3>
<p>Issued online after a medical exam at an approved health centre, in about 10 days.</p>

<h3>Saudi overtime calculation</h3>
<p>The hourly wage plus 50% of the basic wage, with public holiday work counted as overtime.</p>

<h3>End of service benefit Saudi Arabia</h3>
<p>Half a month's wage per year for the first five years, then a full month per year.</p>

<h3>Saudization in hospitality</h3>
<p>41 tourism professions are being localized from 22 April 2026, and Madinah localizes 40% of restaurant jobs.</p>

<h3>Hotel jobs in Saudi Arabia for freshers</h3>
<p>Tourism employed 983,253 people in early 2025, and 75.2% of them were non-Saudi.</p>

<h3>Overseas Employment Promoter licence check</h3>
<p>Verify the licence on the Bureau of Emigration and Overseas Employment's active list before paying anything.</p>

<h2>More Job Guides</h2>

<p>Comparing Gulf routes and kitchen work elsewhere? These cover it:</p>

<ul>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Labour Law and Musaned split, and what it means for your contract.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; sponsored manual work and how the package is structured.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; another entry-level Saudi route and its licensing.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; the licensed end of the same labour market.</li>
    <li><a href="/blog/cook-jobs-in-uk">Cook Jobs in UK</a> &mdash; kitchen work in Britain, and what the law says about tips.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; seasonal hospitality routes on the other side of the world.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Saudi labour rules, localization decisions and referral wages change. Confirm current requirements with the Ministry of Human Resources and Social Development, the Qiwa and Balady platforms, or your own country's emigration authority, before paying any fee or signing a contract.</p>
HTML;
    }
}
