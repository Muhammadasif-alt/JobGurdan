<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Janitor Jobs in Philadelphia, Pennsylvania With No Experience"
 *
 * Checked on 30 October 2026. What changed against the brief:
 *
 * 1. The brief repeated many keyword variants ("janitor jobs in Philadelphia PA ..."), which reads as keyword stuffing;
 *    the guide is rewritten in plain prose with the tags carrying the search terms. The three Indeed buttons are
 *    dropped.
 * 2. The ISS Union Cleaner posting (American Airlines Cargo, $18 to $18.54, no experience) returns 404, so it is closed;
 *    neither its pay nor its no-experience wording is quoted.
 * 3. SSC Services for Education fetched: Janitor (full time and part time) at La Salle University, 1900 West Olney
 *    Avenue, job 1563843, $13.50 an hour, prior janitorial experience preferred, medical, dental, vision and 401K
 *    listed, applications accepted on an ongoing basis, updated 08/19/2026.
 * 4. School District of Philadelphia fetched: General Cleaner SY 26-27, starting at $34,910 a year, six months of paid
 *    full-time custodial housekeeping experience required, high school diploma, open-competitive written exam, lifting
 *    up to 50 pounds, four days of paid training, school-year shifts 10am-6:30pm or 12pm-8:30pm, posted 14 July 2026.
 * 5. City of Philadelphia fetched: apply online at phila.gov/jobs; civil service jobs need an exam and one year's
 *    residence in Philadelphia before appointment; non-civil service jobs need a move within six months; job interest
 *    form for alerts. Pennsylvania L&I fetched: $7.25 minimum wage and overtime at 1.5 times after 40 hours.
 * 6. FAQs expanded to eight and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 29 Oct 10.47 and 10.51 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class JanitorJobsPhiladelphiaNoExperienceBlogSeeder extends Seeder
{
    public const SLUG = 'janitor-jobs-philadelphia-no-experience';

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
                'title' => 'Janitor Jobs in Philadelphia, Pennsylvania With No Experience',
                'excerpt' => 'How to get a janitor job in Philadelphia without experience: what contractors like SSC ask for, why School District cleaner posts need six months\' experience, City of Philadelphia civil service rules, pay from $13.50 an hour, shifts and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/janitor-jobs-philadelphia-no-experience.jpg',
                'tags' => 'janitor jobs in philadelphia, janitor jobs in philadelphia no experience, entry level janitor jobs in philadelphia pa, part time janitor jobs in philadelphia, custodian jobs in philadelphia pa, school district of philadelphia general cleaner, city of philadelphia jobs, pennsylvania minimum wage',
                'meta_title' => 'Janitor Jobs in Philadelphia, PA With No Experience',
                'meta_description' => 'Janitor jobs in Philadelphia for beginners: what SSC and the School District ask for, City civil service rules, pay from $13.50 an hour and how to apply.',
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
<p><strong>You can start as a janitor in Philadelphia without experience at employers whose adverts allow it, mainly cleaning contractors; school district and City posts usually want experience or an exam.</strong> SSC Services for Education, for example, hires janitors at La Salle University at $13.50 an hour and only prefers experience. The School District's General Cleaner post requires six months of paid custodial work. Pennsylvania's minimum wage is $7.25 an hour. None of this guarantees a job or hours. This guide uses SSC, the School District of Philadelphia, the City of Philadelphia and the Pennsylvania Department of Labor &amp; Industry, checked on 30 October 2026.</p>

<h2>What Entry-Level Janitors Do</h2>

<p>Janitors keep assigned areas clean and usable: sweeping, vacuuming, mopping, emptying bins, cleaning restrooms and refilling supplies. Office janitors focus on desks, corridors and shared kitchens; apartment building janitors on entrances, stairs and shared spaces; warehouse cleaners on large floors around moving equipment. Ask whether the job includes outdoor work, moving furniture, floor machines or travel between sites, and ask for training on any product or machine you have not used. A beginner should never have to guess how a chemical or machine works.</p>

<h2>A Contractor Post That Only Prefers Experience</h2>

<p>SSC Services for Education advertises full-time and part-time janitor jobs at La Salle University, 1900 West Olney Avenue (job 1563843), at <strong>$13.50 an hour</strong>, with schedules from Monday to Sunday that may vary. Prior janitorial experience is preferred, not required, and the advert mentions training and growth opportunities, medical, dental and vision insurance, and a 401K with a company match. It says applications are accepted on an ongoing basis. "Preferred" means experience strengthens an application; it does not promise that every beginner is hired. Source: <a href="https://careers.compass-usa.com/janitor-full-time-and-part-time/job/891C12497B86E3DD4B7E61C6F661D34A" rel="noopener">SSC: Janitor, La Salle University</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/janitor-jobs-philadelphia-no-experience-team.jpg"
         alt="Janitor in a blue uniform and gloves mopping an office lobby in Philadelphia, with a cleaning cart and the city skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>School District and City Jobs</h2>

<p>School custodian jobs are not all entry level. The School District of Philadelphia's <strong>General Cleaner SY 26-27</strong> post starts at <strong>$34,910 a year</strong> and requires six months of paid, full-time custodial housekeeping work in a large commercial or public building, a high school diploma or equivalent, an open-competitive written exam and the ability to lift up to 50 pounds. It includes four days of paid training, and school-year shifts run 10am to 6:30pm or 12pm to 8:30pm. If you have no paid cleaning experience yet, build it with a contractor first. Source: <a href="https://aa080.referrals.selectminds.com/Schoolleader/jobs/general-cleaner-sy-26-27-13683" rel="noopener">School District of Philadelphia: General Cleaner SY 26-27</a>.</p>

<p>City of Philadelphia jobs are posted at <a href="https://www.phila.gov/jobs/" rel="noopener">phila.gov/jobs</a> and applied for online; Free Library branches have computers if you need one. Civil service posts need a competitive exam and one year's residence in Philadelphia before your appointment; non-civil service posts have no exam but you must move to the city within six months. Fill in the job interest form to be told when a civil service job opens. Source: <a href="https://www.phila.gov/services/working-jobs/city-jobs-and-internships/apply-for-a-job-with-the-city-of-philadelphia/" rel="noopener">City of Philadelphia: Apply for a job with the City</a>.</p>

<p>Hospital cleaning jobs are often called environmental services; ask about infection-control training and protective equipment. Airport cleaning jobs are usually with a contractor, so confirm the employer and the access badge process.</p>

<h2>Requirements to Check</h2>

<ul>
<li>Whether experience is required, preferred or not needed.</li>
<li>Minimum age and any education requirement.</li>
<li>Standing, walking, lifting and equipment duties.</li>
<li>Background checks or site-access conditions.</li>
<li>Whether you need a driving licence to travel between sites.</li>
</ul>

<p>Most cleaning jobs need no licence or certificate, but "no experience" does not mean no checks. Punctuality, careful work, teamwork and reporting problems quickly are the strengths to show. Describe unpaid cleaning honestly; do not present it as paid commercial work.</p>

<h2>Pay</h2>

<p>The two employer figures above are $13.50 an hour at SSC and $34,910 a year for a School District General Cleaner. They are single employers' rates, not a city average. Pennsylvania's minimum wage is <strong>$7.25 an hour</strong>, and most employees get overtime at 1.5 times their regular rate after 40 hours a week. Philadelphia has extra city rules, such as paid sick leave, that the state does not enforce. As an illustration only, $20 an hour for 40 hours is $800 a week before deductions. Ask about guaranteed hours, paid training, uniform costs, benefits and how often you are paid. Source: <a href="https://www.pa.gov/agencies/dli/resources/compliance-laws-and-regulations/labor-management-relations/pennsylvania-s-minimum-wage-act/wage-faqs" rel="noopener">Pennsylvania L&amp;I: Wage FAQs</a>.</p>

<h2>Part-Time, Night and Weekend Shifts</h2>

<p>Part-time posts suit people with other commitments; full-time posts give more predictable hours, depending on the contract. Night and overnight cleaning is common in offices, so check transport home, who supervises you, how you get into the building and who to call in an emergency. Weekend shifts often cover events and public buildings.</p>

<h2>How to Apply With No Experience</h2>

<ol>
<li>Search janitor, cleaner, custodian, porter and housekeeping titles.</li>
<li>Read the full advert and separate requirements from preferences.</li>
<li>Prepare a short resume showing availability and dependable work.</li>
<li>Apply through the employer's own site, and save the confirmation.</li>
<li>Follow up politely after a week.</li>
</ol>

<p>"Hiring immediately" still means screening and onboarding, so ask for the real start date. Ask who supplies gloves, products and equipment and whether training is paid. Never pay a recruiter for a placement or send documents to an unverified social media account.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Philadelphia janitor posts need existing US work authorisation; these employers do not sponsor visas for them. Read <a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">cleaner and janitor jobs with visa sponsorship</a> for countries that do, and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you pay anyone.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a janitor job in Philadelphia with no experience?</h3>
<p>Yes, with employers whose adverts only prefer experience, such as SSC at La Salle University.</p>

<h3>What does SSC pay janitors at La Salle?</h3>
<p>$13.50 an hour, full time or part time.</p>

<h3>Are School District cleaner jobs entry level?</h3>
<p>No. The General Cleaner post requires six months of paid custodial experience and a written exam.</p>

<h3>What does a School District General Cleaner earn?</h3>
<p>The starting rate is $34,910 a year.</p>

<h2>Bottom Line</h2>

<p>Start with contractor posts that accept beginners, build six months of paid experience for School District and City jobs, and compare pay, hours and the commute in writing.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://careers.compass-usa.com/janitor-full-time-and-part-time/job/891C12497B86E3DD4B7E61C6F661D34A" rel="noopener">SSC Services for Education: Janitor, La Salle University</a></li>
<li><a href="https://aa080.referrals.selectminds.com/Schoolleader/jobs/general-cleaner-sy-26-27-13683" rel="noopener">School District of Philadelphia: General Cleaner SY 26-27</a></li>
<li><a href="https://www.phila.gov/services/working-jobs/city-jobs-and-internships/apply-for-a-job-with-the-city-of-philadelphia/" rel="noopener">City of Philadelphia: Apply for a job with the City</a></li>
<li><a href="https://www.pa.gov/agencies/dli/resources/compliance-laws-and-regulations/labor-management-relations/pennsylvania-s-minimum-wage-act/wage-faqs" rel="noopener">Pennsylvania L&amp;I: Wage FAQs</a>, all checked 30 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">Cleaner and Janitor Jobs With Visa Sponsorship</a>, cleaning work abroad.</li>
    <li><a href="/blog/hotel-housekeeping-jobs-las-vegas-no-experience">Hotel Housekeeping Jobs in Las Vegas</a>, another cleaning entry route.</li>
    <li><a href="/blog/gas-station-jobs-houston-hiring-age-requirements">Gas Station Jobs in Houston</a>, another beginner job.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

HTML;
    }
}
