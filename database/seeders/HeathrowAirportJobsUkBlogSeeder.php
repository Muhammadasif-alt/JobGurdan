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
 * "How to Apply for Heathrow Airport Jobs in the UK" - built on Heathrow's own
 * careers, benefits and application process pages, its 2025 results and the
 * Living Wage Foundation rates, rather than the aggregator listing the draft
 * used.
 *
 * Corrections to the draft (checked against heathrow.com careers, benefits and
 * application process pages, mediacentre.heathrow.com, livingwage.org.uk and
 * Home Office guidance, September 2026):
 *
 * 1. The draft made an aggregator the second apply route. Dropped; Heathrow's
 *    own careers site is the only link.
 *
 * 2. "More than 80 million passengers" is stale and vague. Heathrow carried
 *    84.5 million in 2025, a record, on just under 480,000 flights, and its
 *    busiest single day was 1 August 2025 at around 270,000 passengers.
 *
 * 3. "Hourly roles typically pay GBP 13 to GBP 14.50" cannot be right for a
 *    direct Heathrow role. Heathrow is Living Wage accredited and says all its
 *    colleagues are paid at least the London Living Wage, which is GBP 14.80.
 *    The draft's range sits below the floor Heathrow commits to.
 *
 * 4. The draft omits the real gate entirely. Heathrow is regulated by the
 *    Department for Transport: every new employee must evidence five years of
 *    employment history including gaps, and security roles and anything with
 *    restricted zone access need a Criminal Record Check and a Counter
 *    Terrorism Check. That is now the first section, because it decides who
 *    can realistically apply.
 *
 * 5. The draft omits the timescale. Heathrow says referencing typically takes
 *    six to eight weeks and can run to fourteen.
 *
 * 6. The draft lists Harrods, Hugo Boss and Premier Inn as if Heathrow hires
 *    for them. Those are partner employers at the airport; Heathrow does not
 *    employ their staff and its pay and benefits do not apply to them.
 *
 * 7. The draft's benefit list is replaced with the one Heathrow publishes,
 *    including the free Heathrow Express travel and the 31-day free parking
 *    allowance it actually names.
 *
 * 8. The draft ignores right to work and sponsorship. Heathrow requires right
 *    to work evidence at the assessment stage, and an hourly airport role
 *    comes nowhere near the Skilled Worker salary threshold.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HeathrowAirportJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.heathrow.com/company/careers';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on finding work, applying well and understanding what a job really pays.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for Heathrow Airport Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Heathrow pays at least the London Living Wage, but the wage is not what decides your application. Five years of referenced employment history and a counter terrorism check do. This guide covers the vetting, the real benefits and the visa answer.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-heathrow-airport-jobs-in-the-uk.jpg',
                'tags' => 'heathrow airport jobs, heathrow careers, airport jobs uk, heathrow security officer jobs, airside pass, counter terrorism check, london living wage, ground handling jobs uk',
                'meta_title' => 'Heathrow Airport Jobs: Pay, Vetting and How to Apply',
                'meta_description' => 'Heathrow airport jobs in the UK: the London Living Wage floor, the five year vetting most guides skip, real benefits and the visa answer.',
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
            ['name' => 'Heathrow Airport, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'heathrow-airport-uk']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Airport Operations and Security Roles, Heathrow UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work across a 24-hour operation, including nights, weekends and bank holidays',
                'language' => 'English',
                // Heathrow is Living Wage accredited and publishes a floor
                // rather than a range: all its colleagues are paid at least
                // the London Living Wage, GBP 14.80. There is no published
                // ceiling, so none is invented here.
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 14.80,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Security, operations, engineering and corporate roles employed directly by Heathrow Airport, with Department for Transport vetting before you start.',
                'seo_keywords' => 'heathrow airport jobs, heathrow careers, airport jobs uk, heathrow security officer jobs, ground handling jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Heathrow employs people directly in airport security, operations, engineering, technology and corporate functions at the United Kingdom's busiest airport.</p>

<h3>What the work involves</h3>
<p>Screening passengers and staff, keeping terminals and airfield operations running, maintaining airport systems and infrastructure, and the finance, technology and safety functions behind them.</p>

<h3>Common requirements</h3>
<ul>
    <li>The right to work in the United Kingdom, evidenced at the assessment stage</li>
    <li>Five years of employment history, including any gaps, that can be referenced</li>
    <li>A Criminal Record Check, and a Counter Terrorism Check for security roles and restricted zone access</li>
    <li>Willingness to work shifts across a 24-hour operation</li>
    <li>No prior airport experience for most entry-level roles</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, vetting standards and benefits here are set by Heathrow and the Department for Transport &mdash; not by JobGader. Apply directly on Heathrow's careers site, and never pay anyone for an airport job or an airside pass.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on Heathrow's own careers site, and expect vetting to take longer than the hiring.</strong> Heathrow pays all of its colleagues at least the London Living Wage, and asks for five years of referenced employment history before you can start.</p>

<p>That second sentence is the whole article. Most guides lead with the pay and the perks. The thing that actually decides whether you can take a Heathrow job is the background check, so it goes first here.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.heathrow.com/company/careers" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1c2b6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Search Heathrow Careers &rarr;
    </a>
</div>

<h2>The Vetting Is the Real Gate</h2>

<p>Heathrow is regulated by the Department for Transport, and its own application process page is direct about what that means:</p>

<ul>
    <li><strong>Five years of employment history, including any gaps.</strong> Every new employee must provide it, and it must be referenceable. Not a CV &mdash; contactable references covering the whole five years.</li>
    <li><strong>A Criminal Record Check</strong>, because of the DfT regulation.</li>
    <li><strong>A Counter Terrorism Check</strong> for Heathrow Security positions and any role with access to restricted zones or restricted files.</li>
</ul>

<p>Heathrow says referencing <strong>typically takes six to eight weeks, and can take up to fourteen weeks</strong> depending on complexity. So if you need money next month, this is not that job.</p>

<p>Two honest readings of this. First, if you have lived in the UK for less than five years, or your last five years span employers abroad who will not answer a reference request, this is the part that will stop you &mdash; not your CV. Second, <strong>a criminal record does not automatically disqualify you.</strong> Heathrow says you can still apply, and an offer then depends on qualifying criteria; the check covers the last five years.</p>

<h2>Who Actually Employs You</h2>

<p>This trips people up constantly. Heathrow the company employs a few thousand people. The airport as a place hosts hundreds of other employers &mdash; airlines, ground handling companies, retail brands, hotels, cargo and logistics firms.</p>

<p><strong>Heathrow does not employ the staff in the shops, the lounges or the hotels.</strong> A job advertised at a retail brand in Terminal 5 is that brand's job, on that brand's pay and terms. Heathrow's London Living Wage commitment, its pension and its Heathrow Express travel do not reach those staff.</p>

<p>What that means practically: read the employer name on the advert before anything else. If it is not Heathrow, the rest of this article's pay and benefits section does not apply to you &mdash; though the airside vetting usually still does.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-heathrow-airport-jobs-in-the-uk-terminal.jpg" alt="Passengers moving through a large airport terminal building" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Scale You Are Applying Into</h2>

<p>Heathrow's own results for 2025 put the airport at <strong>84.5 million passengers</strong>, its busiest year on record and Europe's busiest hub. Just under <strong>480,000 flights</strong> operated, and its busiest single day ever was <strong>1 August 2025</strong>, with around 270,000 passengers through the terminals.</p>

<p>That is why the shift pattern is not negotiable. The airport does not close, so security, operations and engineering roles run nights, weekends and bank holidays.</p>

<h2>What It Pays</h2>

<p>Heathrow is a <strong>Living Wage Foundation accredited employer</strong>, and its own benefits page states plainly that <strong>all Heathrow colleagues are paid at least the London Living Wage</strong>.</p>

<div style="background:#f9fafb;border-left:4px solid #1c2b6b;padding:16px 20px;margin:24px 0;">
    <p style="margin:0;">The London Living Wage is <strong>&pound;14.80 an hour</strong> for 2025-26, against a UK rate of &pound;13.45. The legal minimum, the National Living Wage, is <strong>&pound;12.71</strong> from 1 April 2026 for workers aged 21 and over. The next Living Wage rates are announced on 15 October 2026.</p>
</div>

<p>This is worth knowing because of what it rules out. <strong>Guides quoting &pound;13 to &pound;14.50 an hour for a direct Heathrow role are quoting a figure below the floor Heathrow has committed to.</strong> If an advert for a Heathrow job pays under the London Living Wage, the employer is not Heathrow.</p>

<h3>The benefits Heathrow publishes</h3>

<ul>
    <li><strong>Discretionary bonus plans</strong></li>
    <li><strong>Defined contribution pension</strong> with flexible monthly contributions, plus life assurance and income protection cover</li>
    <li><strong>Free travel on the Heathrow Express</strong>, at any time</li>
    <li><strong>Free long-term parking at the airport</strong>, up to a maximum of 31 days a year</li>
    <li><strong>Interest-free travel loans</strong>, to a maximum level</li>
    <li>A health and wellbeing programme including an online GP, relaxation rooms and occupational health</li>
    <li>Discounts with major retailers, restaurants and leisure companies</li>
    <li>A cycle purchase scheme, and 24-hour free confidential advice on financial, legal, relationship and work matters</li>
</ul>

<p>The Heathrow Express benefit is worth more than it looks if you live along the Paddington line, because the fare is high and you would otherwise pay it twice a day.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-heathrow-airport-jobs-in-the-uk-staff.jpg" alt="Airport staff working at a terminal service desk" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Can You Apply From Overseas?</h2>

<p>Heathrow's application process page says non-British citizens can apply but must hold <strong>a valid work permit or right to work documentation</strong> under Home Office rules, and must bring that evidence to assessment events.</p>

<p>Read that carefully: it means <strong>Heathrow expects you to already have the right to work</strong>, not that it will get you one. Two things follow.</p>

<ul>
    <li><strong>The salary maths does not work for sponsorship.</strong> The Skilled Worker general salary threshold is &pound;41,700 a year, or the going rate for the occupation, whichever is higher. An hourly airport role paid at the London Living Wage is a long way below that.</li>
    <li><strong>The vetting is the harder wall.</strong> Even where a role is sponsorable on paper, five years of referenceable UK-checkable history is not something a new arrival can produce.</li>
</ul>

<p>If you are outside the UK, the honest answer is that Heathrow's hourly roles are not a migration route. Anyone charging you for an "airside pass" or a guaranteed Heathrow job is running a scam &mdash; the pass is issued through the employer after vetting and cannot be bought.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Search Heathrow's own careers site</strong> and check the employer name on each advert before you read anything else.</li>
    <li><strong>Gather your five-year history first.</strong> Dates, employers, contactable referees, and an explanation for every gap. Doing this before you apply saves weeks later.</li>
    <li><strong>Apply online.</strong> Every Heathrow application starts on its careers website.</li>
    <li><strong>Prepare for the assessment stage</strong>, which may be tests, an interview, a presentation or an assessment centre depending on the role.</li>
    <li><strong>Bring your right to work evidence</strong> to the assessment event, as Heathrow asks.</li>
    <li><strong>Expect six to eight weeks of referencing and clearance</strong> before you start, and up to fourteen in complex cases.</li>
    <li><strong>Never pay for a job or a pass.</strong> Heathrow does not charge applicants.</li>
</ol>

<p>If the vetting timeline does not suit you, our <a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">Marks and Spencer retail guide</a> and our <a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">Royal Mail delivery guide</a> cover UK roles that hire far faster.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does Heathrow pay its staff?</h3>
<p>Heathrow says all its colleagues are paid at least the London Living Wage, which is &pound;14.80 an hour for 2025-26. It publishes no ceiling, and pay varies by role.</p>

<h3>What background check does Heathrow require?</h3>
<p>Five years of employment history including gaps, a Criminal Record Check, and a Counter Terrorism Check for security roles and anything with restricted zone access.</p>

<h3>How long does the Heathrow hiring process take?</h3>
<p>Heathrow says referencing typically takes six to eight weeks and can take up to fourteen weeks in complex cases, on top of the application and assessment stages.</p>

<h3>Can I work at Heathrow with a criminal record?</h3>
<p>You can apply. Heathrow says an offer then depends on qualifying criteria, and the check covers the last five years. It is not an automatic bar.</p>

<h3>Does Heathrow sponsor work visas?</h3>
<p>Heathrow asks applicants to already hold a valid work permit or right to work documentation. An hourly airport role is also far below the &pound;41,700 Skilled Worker salary threshold.</p>

<h3>Is every job at Heathrow airport a Heathrow job?</h3>
<p>No. Airlines, ground handlers, retailers, hotels and cargo firms employ most of the people on site. Heathrow's pay and benefits apply only to its own colleagues.</p>

<h3>How many passengers does Heathrow handle?</h3>
<p>84.5 million in 2025, its busiest year on record, on just under 480,000 flights. Its busiest single day was 1 August 2025 at around 270,000 passengers.</p>

<h3>Do I need airport experience to apply?</h3>
<p>Not for most entry-level roles. The vetting and the shift pattern matter more than prior aviation experience.</p>

<h2>People Also Search For</h2>

<h3>Heathrow airside pass requirements</h3>
<p>Five years of referenced employment history, a Criminal Record Check, and a Counter Terrorism Check for restricted zone access.</p>

<h3>Heathrow security officer jobs</h3>
<p>Employed directly by Heathrow, and among the roles that always need the Counter Terrorism Check.</p>

<h3>London Living Wage 2026</h3>
<p>&pound;14.80 an hour, against a UK rate of &pound;13.45. The next rates are announced on 15 October 2026.</p>

<h3>Heathrow employee benefits</h3>
<p>Free Heathrow Express travel, up to 31 days free airport parking a year, a DC pension with life assurance, and interest-free travel loans.</p>

<h3>Heathrow passenger numbers 2025</h3>
<p>84.5 million, a record, on just under 480,000 flights, making it Europe's busiest hub.</p>

<h3>Counter Terrorism Check UK airport</h3>
<p>Required for Heathrow Security roles and any position with access to restricted zones or restricted files.</p>

<h3>Airport jobs no experience UK</h3>
<p>Most entry-level airport roles train you. The barrier is vetting and shift availability, not experience.</p>

<h3>Heathrow jobs visa sponsorship</h3>
<p>Heathrow asks for existing right to work. Hourly roles sit far below the &pound;41,700 Skilled Worker threshold.</p>

<h2>More Job Guides</h2>

<p>Comparing airport and transport employers? These cover the alternatives:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-qantas-ground-staff-jobs-in-australia">How to Apply for Qantas Ground Staff Jobs in Australia</a> &mdash; the same work under a different security regime.</li>
    <li><a href="/blog/how-to-apply-for-air-canada-airport-jobs">How to Apply for Air Canada Airport Jobs</a> &mdash; an airport employer that publishes its ramp rate.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; where the sponsorship answer is different.</li>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the cabin route rather than the ground route.</li>
    <li><a href="/blog/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk">How to Apply for Network Rail Maintenance Jobs in the UK</a> &mdash; another safety-critical UK employer with medical screening.</li>
    <li><a href="/blog/how-to-apply-for-royal-mail-delivery-jobs-in-the-uk">How to Apply for Royal Mail Delivery Jobs in the UK</a> &mdash; a union pay scale published in writing.</li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; a faster route into UK hourly work.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; what the sponsorship rules actually allow.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; the honest position on sponsored warehouse work.</li>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a> &mdash; retail hours instead of airport shifts.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Heathrow's own careers, benefits and application process pages, Heathrow's 2025 results, Living Wage Foundation rates and Home Office immigration guidance. Pay, vetting standards, benefits and immigration rules change, and most employers at the airport are not Heathrow. Always read the live advert and check the employer name before you apply.</p>
HTML;
    }
}
