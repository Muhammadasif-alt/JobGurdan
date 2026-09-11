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
 * "Cook Jobs in UK" — restaurant, hotel, care home, school and contract
 * catering kitchens. It sits beside the cleaner, warehouse and delivery driver
 * guides for the UK, using the same National Living Wage arithmetic, and beside
 * the care worker guide for readers weighing care home kitchens.
 *
 * Corrections to the draft:
 *
 * 1. It quotes entry-level cooks from GBP 18,000, experienced cooks from
 *    GBP 22,000 and care home cooks from GBP 20,000. At the GBP 12.71 National
 *    Living Wage from 1 April 2026, a 37.5-hour week is GBP 24,784.50 a year,
 *    so all three floors are below the legal minimum for a full-time worker
 *    aged 21 or over, and kitchen weeks are often longer.
 *
 * 2. It says a Level 1 or 2 food hygiene certificate is often expected, which
 *    readers take as a legal requirement. The Food Standards Agency says food
 *    handlers do not have to hold one; the law requires training or
 *    supervision appropriate to the work.
 *
 * 3. It says nothing about visas, and its posters promised sponsorship. Cooks
 *    (SOC 5435, including head cooks) and kitchen assistants (9263) are in
 *    Table 6 of Appendix Skilled Occupations and cannot be sponsored; chefs
 *    (5434) are in Table 1a, open only to workers sponsored before
 *    22 July 2025, and are not on the Temporary Shortage List.
 *
 * 4. It omits the Employment (Allocation of Tips) Act 2023, in force since
 *    1 October 2024, which governs the service charge most kitchens share.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CookJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-cook-jobs.html';

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
        $title = 'Cook Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'An £18,000 cook salary is below the legal minimum for a full-time worker aged 21 or over, a food hygiene certificate is not a legal requirement, and cooks cannot be sponsored for a UK work visa at all.',
                'content' => $content,
                'featured_image' => 'blogs/cook-jobs-in-uk.jpg',
                'tags' => 'cook jobs uk, commis chef jobs, chef jobs uk, care home cook jobs, school cook jobs, cook salary uk, food hygiene certificate level 2, kitchen jobs london, chef visa sponsorship uk',
                'meta_title' => 'Cook Jobs in UK 2026: Pay, Hygiene Rules and Visas',
                'meta_description' => 'Cook jobs in the UK: why a GBP 18,000 kitchen salary is below the legal minimum, whether you need a hygiene certificate, and why cooks cannot be sponsored.',
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
            ['name' => 'UK Restaurants, Hotels, Care Homes & Caterers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-cook-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cook — Restaurants, Hotels, Care Homes and Contract Catering, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, including evenings, weekends and bank holidays in most restaurant and hotel kitchens',
                'language' => 'English',
                // The advertised floors for cooks sit below the National Living
                // Wage for a full-time adult, so no range is quoted that the law
                // would not support.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Cook and commis chef roles in UK restaurants, hotels, care homes and schools. Right to work in the UK required; cooks cannot be sponsored.',
                'seo_keywords' => 'cook jobs uk, commis chef jobs, care home cook jobs, school cook jobs, cook salary uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Restaurants, pubs, hotels, care homes, schools, hospitals and contract caterers hire cooks across the UK all year. Entry-level kitchen roles rarely ask for qualifications, and many employers train new cooks on the job.</p>

<h3>What the work involves</h3>
<p>Preparing ingredients, cooking dishes to recipe and portion standards, keeping temperature and cleaning records, labelling food and allergen information correctly, and following the kitchen's food safety procedures. Care home and school cooks also plan menus for special diets.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>The right to work in the UK</strong> &mdash; cooks and kitchen assistants cannot be sponsored for a Skilled Worker visa</li>
    <li>Food hygiene training or supervision appropriate to the job, often evidenced by a Level 2 food hygiene certificate</li>
    <li>Knowledge of the 14 allergens and how to label food that contains them</li>
    <li>A DBS check for many school, care home and hospital kitchens</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The legal floor.</strong> The National Living Wage is &pound;12.71 an hour from 1 April 2026, which is &pound;24,784.50 a year on a 37.5-hour week for a worker aged 21 or over</li>
    <li><strong>Longer weeks.</strong> At 45 hours a week, the same hourly rate comes to &pound;29,741.40 a year</li>
    <li><strong>Tips and service charges</strong> must be passed on in full under the Employment (Allocation of Tips) Act 2023</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the weekly hours against the salary.</strong> A full-time cook offer below &pound;24,784.50 for a worker aged 21 or over is below the National Living Wage, and a longer week raises the legal floor further.</p>

<p><strong>Note:</strong> pay, hours, training and eligibility are set by each employer and by UK law and immigration rules &mdash; not by JobGader. Confirm the details on the official advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>UK kitchens hire all year. Restaurants, pubs, hotels, care homes, schools, hospitals and contract caterers all need cooks, and many of those jobs start with no formal qualification. It is also a trade where the salaries guides publish fall below the legal minimum, where a certificate most readers believe is compulsory is not, and where the visa sponsorship promised on so many posters does not exist for cooks at all.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-cook-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127859; Browse Cook Jobs in UK &rarr;
    </a>
</div>

<h2>Three of the Four Salary Floors Are Below the Legal Minimum</h2>

<p>Guides give UK cook pay in four bands:</p>

<ul>
    <li><strong>Entry-level cook or commis chef:</strong> &pound;18,000 to &pound;22,000 a year</li>
    <li><strong>Experienced restaurant or hotel cook:</strong> &pound;22,000 to &pound;28,000</li>
    <li><strong>Care home or institutional cook:</strong> &pound;20,000 to &pound;25,000</li>
    <li><strong>Head cook or kitchen supervisor:</strong> &pound;26,000 to &pound;34,000 or more</li>
</ul>

<p>The <strong>National Living Wage</strong>, the legal minimum for workers aged 21 and over, is <strong>&pound;12.71 an hour</strong> from 1 April 2026. On a standard 37.5-hour week over 52 weeks, that is <strong>&pound;24,784.50</strong> a year. Against that:</p>

<ul>
    <li>The entry-level floor of &pound;18,000 is <strong>&pound;6,784.50 short</strong>, and even its top of &pound;22,000 is &pound;2,784.50 short.</li>
    <li>The experienced cook floor of &pound;22,000 is <strong>&pound;2,784.50 short</strong>.</li>
    <li>The care home floor of &pound;20,000 is <strong>&pound;4,784.50 short</strong>.</li>
</ul>

<p>Kitchens also run long weeks. At <strong>45 hours a week</strong>, the legal minimum comes to <strong>&pound;29,741.40</strong> a year &mdash; above the head cook floor of &pound;26,000 as well. Those published figures only work for a younger worker, an apprentice on the apprentice rate of &pound;8.00 an hour, or someone on fewer hours. So <strong>ask for the weekly hours in writing</strong> and divide the salary by them before you accept.</p>

<h2>You Do Not Legally Need a Food Hygiene Certificate</h2>

<p>Guides say a Level 1 or Level 2 food hygiene certificate is "often expected", and most readers come away thinking it is compulsory. The Food Standards Agency is clear: in the UK, <strong>food handlers don't have to hold a food hygiene certificate to prepare or sell food</strong>.</p>

<p>What the law does require is that food business operators make sure food handlers are <strong>supervised and instructed or trained in food hygiene</strong> in a way that fits their work. A Level 2 certificate is simply the most common way an employer proves that training happened, which is why so many adverts ask for one. It is worth having, and it is cheap and quick to get &mdash; but a kitchen cannot tell you that the law bars you from starting without one.</p>

<p>Two other food rules matter more day to day:</p>

<ul>
    <li><strong>Allergens.</strong> Kitchens must give accurate information on the <strong>14 major allergens</strong>. Since 1 October 2021, <strong>Natasha's Law</strong> has required full ingredient labels, with allergens emphasised, on food prepacked on the premises for direct sale.</li>
    <li><strong>Hygiene ratings.</strong> Local authorities inspect and rate kitchens. Displaying the rating is compulsory in Wales and Northern Ireland and voluntary in England; Scotland runs its own pass-or-improvement scheme.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cook-jobs-in-uk-hygiene.jpg"
         alt="A cook plating a dish in a professional UK kitchen with the Houses of Parliament in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Cooks Cannot Be Sponsored for a UK Work Visa</h2>

<p>Many recruitment posters for UK kitchen jobs promise "visa sponsorship". For cooks, the Immigration Rules say otherwise, and the answer turns on the occupation code:</p>

<ul>
    <li><strong>5435 Cooks</strong> &mdash; which includes cook, cook-supervisor, fish fryer and <strong>head cook</strong> &mdash; is listed in <strong>Table 6</strong> of Appendix Skilled Occupations, the codes that are <strong>not eligible</strong> for the Skilled Worker route at all.</li>
    <li><strong>9263 Kitchen and catering assistants</strong>, including kitchen porters and fast food crew, are in Table 6 too.</li>
    <li><strong>5434 Chefs</strong> sit in Table 1a. Since 22 July 2025 that table is open only to workers who already held Skilled Worker permission from before that date, and chefs are <strong>not on the Temporary Shortage List</strong> that keeps some lower-skilled codes open.</li>
</ul>

<p>In plain terms: <strong>a new overseas applicant cannot be sponsored as a cook, kitchen assistant or chef</strong>. Anyone charging you for a sponsored UK kitchen job is selling something the rules do not allow. If you are already in the UK with the right to work &mdash; on a Graduate visa, as a dependant, or with settled or pre-settled status &mdash; you can apply for any of these jobs like any other candidate. Indian nationals aged 18 to 30 can also enter the ballot for the India Young Professionals Scheme, which allows work in any job for up to two years.</p>

<h2>Tips and Service Charges Belong to the Staff</h2>

<p>Guides skip the law that changed hospitality pay most recently. The <strong>Employment (Allocation of Tips) Act 2023</strong> came into force on <strong>1 October 2024</strong>. Employers must pass on tips, gratuities and service charges they control <strong>in full</strong>, without deductions other than tax, allocate them fairly under a statutory code of practice, keep a written tipping policy and pay them by the end of the month after they were received.</p>

<p>Fair allocation can include kitchen staff, so ask at interview whether cooks share in the service charge and how the split is decided. The written policy should answer it.</p>

<h2>The Kitchens That Hire Cooks</h2>

<ul>
    <li><strong>Restaurants and pubs.</strong> The most jobs and the longest evening and weekend shifts. Commis chef is the usual first title, often on an apprenticeship.</li>
    <li><strong>Hotels.</strong> Breakfast, banqueting and restaurant service, with split shifts and sometimes live-in roles at rural hotels.</li>
    <li><strong>Care homes.</strong> Daytime hours, menus for special diets, and texture-modified meals for residents with swallowing difficulties. A DBS check is standard.</li>
    <li><strong>Schools.</strong> Term-time hours and menus that must meet the school food standards. A DBS check is standard.</li>
    <li><strong>Hospitals and contract caterers.</strong> Large-scale cooking to strict temperature and allergen records, often for companies running staff restaurants and events.</li>
</ul>

<h2>Qualifications That Actually Help</h2>

<p>No qualification is needed to start as a cook. For progression, what counts is training employers recognise:</p>

<ul>
    <li><strong>A Level 2 food hygiene certificate</strong> &mdash; not compulsory, but the quickest way to evidence training.</li>
    <li><strong>Apprenticeships</strong> such as commis chef at Level 2 and chef de partie at Level 3, which combine paid work with training.</li>
    <li><strong>Professional cookery diplomas</strong> from awarding bodies such as City &amp; Guilds, which have largely replaced the old NVQ titles.</li>
    <li><strong>Allergen awareness training</strong>, which every kitchen needs and many ask for by name.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cook-jobs-in-uk-kitchen.jpg"
         alt="A chef garnishing a plated fish dish in a busy restaurant kitchen"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Jobs Are</h2>

<ul>
    <li><strong>London and the South East</strong> &mdash; the most restaurants, hotels and contract caterers, and the highest pay.</li>
    <li><strong>Manchester and the North West</strong> &mdash; a large and growing restaurant scene.</li>
    <li><strong>Birmingham and the Midlands</strong> &mdash; restaurants, hotels and institutional catering.</li>
    <li><strong>Edinburgh and Glasgow</strong> &mdash; tourism-driven demand, with seasonal peaks in Edinburgh.</li>
    <li><strong>Coastal and rural tourist areas</strong> &mdash; seasonal hotel and pub kitchens, some offering live-in accommodation.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Do I need qualifications to become a cook in the UK?</h3>
<p>No. Many entry-level kitchen jobs train you on the job. A Level 2 food hygiene certificate, an apprenticeship or a professional cookery diploma helps you progress.</p>

<h3>Is a food hygiene certificate a legal requirement in the UK?</h3>
<p>No. The Food Standards Agency says food handlers do not have to hold one. The law requires employers to make sure food handlers are trained or supervised in food hygiene appropriately for their work.</p>

<h3>How much do cooks earn in the UK?</h3>
<p>Guides quote &pound;18,000 to &pound;34,000 or more. For a full-time worker aged 21 or over on 37.5 hours a week, nothing below &pound;24,784.50 a year is lawful, and a 45-hour week raises that to &pound;29,741.40.</p>

<h3>Is &pound;18,000 a legal salary for a full-time cook?</h3>
<p>Not for a worker aged 21 or over on 37.5 hours a week. The National Living Wage of &pound;12.71 an hour comes to &pound;24,784.50 a year, so &pound;18,000 is &pound;6,784.50 short.</p>

<h3>Can I get a UK visa to work as a cook or chef?</h3>
<p>Not as a new overseas applicant. Cooks (5435) and kitchen assistants (9263) cannot be sponsored at all, and chefs (5434) can only be sponsored for workers already on the Skilled Worker route before 22 July 2025.</p>

<h3>What is the difference between a cook and a chef?</h3>
<p>The Home Office uses separate occupation codes: 5434 Chefs and 5435 Cooks, with head cooks counted as cooks. In kitchens the titles overlap, but the code matters for visas.</p>

<h3>Do kitchen staff get a share of tips in the UK?</h3>
<p>They can. Since 1 October 2024, employers must pass on tips and service charges in full and allocate them fairly under a written policy, and a fair split can include kitchen staff.</p>

<h3>Can I work as a cook part-time?</h3>
<p>Yes. Restaurants, cafés, schools and caterers all use part-time cooks, and the National Living Wage applies to every hour you work.</p>

<h2>People Also Search For</h2>

<h3>Commis chef jobs</h3>
<p>The usual first title in restaurant and hotel kitchens, often on a Level 2 apprenticeship.</p>

<h3>Care home cook jobs</h3>
<p>Daytime hours and special-diet menus, with a DBS check. Advertised floors of &pound;20,000 are below the legal minimum for a full-time adult.</p>

<h3>School cook jobs</h3>
<p>Term-time hours and menus built to the school food standards.</p>

<h3>Food hygiene certificate Level 2</h3>
<p>Not a legal requirement, but the most common way employers evidence food hygiene training.</p>

<h3>Chef jobs with visa sponsorship UK</h3>
<p>Not available to new overseas applicants since 22 July 2025. Cooks cannot be sponsored at all.</p>

<h3>Kitchen porter jobs</h3>
<p>Kitchen and catering assistant roles, in Table 6 and not eligible for sponsorship, but open to anyone with the right to work.</p>

<h3>Live-in chef jobs UK</h3>
<p>Mostly at rural and seasonal hotels and pubs. Check how accommodation is charged against your pay.</p>

<h3>Cook salary in London</h3>
<p>The highest in the UK, but still to be checked against the National Living Wage for the hours you work.</p>

<h2>More Job Guides</h2>

<p>Comparing UK routes and hospitality work elsewhere? These cover them:</p>

<ul>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; another entry-level UK route, and what it really pays.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; care home work, and why the care worker visa route closed.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why operative roles fail the sponsorship tests too.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the same National Living Wage arithmetic on the road.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; a UK role that is on the Temporary Shortage List, and the salary it needs.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a qualified trade with a real certification barrier.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; the sector where sponsorship is genuinely available.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; line cook and kitchen work on the American seasonal visa route.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the care home and hospital job with a real sponsorship rule, and its 2026 NHS pay.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration, careers or financial advice. Minimum wage rates, food safety rules, immigration rules and occupation codes change. Confirm the current position with GOV.UK, the Food Standards Agency and the employer before applying or accepting an offer.</p>
HTML;
    }
}
