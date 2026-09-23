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
 * "How to Apply for Woolworths Supermarket Jobs in Australia" — an employer
 * guide that answers the pay question the draft refuses to answer, because
 * Australian retail pay is set by a legally binding instrument with published
 * dollar figures.
 *
 * Corrections to the draft (checked against careers.woolworthsgroup.com.au,
 * woolworthsgroup.com.au, fwc.gov.au, fairwork.gov.au and
 * immi.homeaffairs.gov.au, 23 September 2026):
 *
 * 1. The draft says there is no national pay figure and tells readers to
 *    check the advertisement. Woolworths store adverts carry no rate at all,
 *    so that advice leads to a blank. The rate comes from the Woolworths
 *    Australian Food Group Agreement 2024 (AE525523), which displaces the
 *    General Retail Industry Award and runs to 17 April 2028.
 *
 * 2. The draft never mentions junior rates, while targeting students. A
 *    16-year-old is paid half the adult rate.
 *
 * 3. All four JobDetail links return HTTP 404 and redirect to a bare error
 *    page with no closure message at all. The draft's newest requisition ID
 *    is 54,171; exactly one of 1,966 live Australian vacancies sits below it.
 *
 * 4. An entire section rests on the job title "Online Assistant". There are
 *    zero live Online Assistant vacancies; the real titles are Assistant
 *    Online Manager, Online Manager, Fulfilment Team Member and Dispatch
 *    Team Member.
 *
 * 5. The draft attributes "entry-level jobs requiring minimal work
 *    experience" to the Regional Australia Report. Neither the 2024 nor the
 *    2025 edition contains that phrase or that claim.
 *
 * 6. The 70 per cent multiskilling figure is F25 data from the superseded
 *    2025 Sustainability Report, and the draft presents it as current.
 *
 * 7. The regional headcount is stale: the 2025 Regional Australia Report
 *    gives 48,000 across more than 530 regional sites, not 47,500.
 *
 * 8. The draft repeats Woolworths' own advice to verify jobs on WOWcareers.
 *    wowcareers.com.au no longer accepts connections on port 80 or 443.
 *
 * 9. The draft omits visa reality. ANZSCO 621111 Sales Assistant (General)
 *    appears on no Australian skilled occupation list, and Pakistan is
 *    eligible for neither working holiday subclass.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WoolworthsSupermarketJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.woolworthsgroup.com.au/en_GB/apply/search-jobs';

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
        $title = 'How to Apply for Woolworths Supermarket Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Woolworths never prints a rate on its job adverts, so most guides say pay varies and stop. It does not vary: it is set by an enterprise agreement. Here are the real hourly figures, the weekend loadings, the junior rates by age, and who can legally apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-woolworths-supermarket-jobs-in-australia.jpg',
                'tags' => 'woolworths jobs, woolworths careers australia, store team member, supermarket jobs australia, retail award pay rates, casual loading australia, junior pay rates, australia work visa',
                'meta_title' => 'Woolworths Jobs Australia: The Real Pay Rates for 2026',
                'meta_description' => 'Woolworths supermarket jobs in Australia: the enterprise agreement hourly rate, casual loading, weekend penalties, junior pay by age, and who can apply.',
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
            ['name' => 'Woolworths Group, Australian Supermarkets'],
            ['type' => 'Company', 'display_reference' => 'woolworths-supermarkets-au']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Store Team Member, Woolworths Supermarkets, Australia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Part-time',
                'job_type' => 'On-site',
                'work_hours' => 'Casual, part-time and full-time rosters including evenings, weekends and overnight nightfill shifts',
                'language' => 'English',
                // Woolworths publishes no rate on any store advertisement. The
                // figures come from its enterprise agreement and the Award,
                // which the guide quotes and sources rather than flattening
                // into a band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Store team member, nightfill, bakery and trolley collection roles with Woolworths Supermarkets across Australia, for applicants with Australian work rights.',
                'seo_keywords' => 'woolworths jobs australia, store team member woolworths, supermarket jobs australia, woolworths careers apply',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Woolworths Supermarkets hires store team members across Australia for the sales floor, checkouts, fresh food departments, overnight nightfill, bakery, trolley collection and online order fulfilment, on casual, part-time and full-time rosters.</p>

<h3>What the work involves</h3>
<p>Serving and directing customers, filling and facing shelves, presenting fresh produce and bakery, running a register or self-serve area, picking and packing online orders, and collecting trolleys and cleaning the site.</p>

<h3>Common requirements</h3>
<ul>
    <li>No prior retail experience for most store team member roles</li>
    <li>Availability for the roster in the posting, which may include evenings, weekends and overnight shifts</li>
    <li>Ability to stand, walk and lift stock for most of a shift</li>
    <li>An existing right to work in Australia, with work-hour limits if you hold a student visa</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay rates are set by the Woolworths enterprise agreement and the General Retail Industry Award, and visa rules are set by the Department of Home Affairs &mdash; not by JobGader. Woolworths warns that anyone contacted about a store job while living outside Australia or New Zealand is likely being scammed. Applying to Woolworths is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Woolworths does not print a pay rate on a single one of its store job adverts.</strong> We opened a live Store Team Member listing and searched it end to end: no dollar figure, no hourly rate, no mention of an award or agreement. That is why almost every guide to these jobs says pay "varies by role and location" and leaves it there.</p>

<p>It does not vary. Australian supermarket pay is set by a legally binding instrument with published numbers, and this guide gives you those numbers. We checked the Fair Work Commission register, the Award pay guide, Woolworths' own live vacancy board and the Department of Home Affairs occupation lists on <strong>23 September 2026</strong>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.woolworthsgroup.com.au/en_GB/apply/search-jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#178841;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128722; Search Woolworths Jobs &rarr;
    </a>
</div>

<h2>What Woolworths Actually Pays</h2>

<p>The first thing to understand is which rulebook applies, because it is not the one most articles assume.</p>

<p>Woolworths Supermarkets staff are <strong>not paid under the General Retail Industry Award</strong>. They are paid under the <strong>Woolworths Australian Food Group Agreement 2024</strong>, an enterprise agreement approved by a Full Bench of the Fair Work Commission on 22 July 2024, in operation since 21 October 2024, with a nominal expiry of <strong>17 April 2028</strong>. The agreement expressly displaces the Award. It also covers Metro stores, eStores and customer fulfilment centres, so online fulfilment staff sit on the same terms. BIG W and the distribution centres are on separate agreements.</p>

<p>The agreement set a Store Team Member Level 1 base of <strong>$990.56 a week, or $26.07 an hour</strong>, and provides that this rises each 1 July by the percentage awarded in the Fair Work Commission's Annual Wage Review. Those increases were 3.5 per cent in 2025 and 4.75 per cent in 2026, which puts the current adult Level 1 base at approximately <strong>$1,073.93 a week, or about $28.26 an hour</strong>.</p>

<p><strong>Be clear about the status of that last figure:</strong> it is arithmetic, not a published rate. Woolworths publishes no rate schedule, and the union's tables sit behind a member login. The base and the escalation clause are both published and verifiable; the 2026 product of the two is our calculation. Treat it as approximate.</p>

<p>What is fully published is the legal floor beneath it. From 1 July 2026 the <strong>General Retail Industry Award</strong> pays Retail Employee Level 1:</p>

<ul>
    <li>Adult full-time or part-time: <strong>$1,056.80 a week, or $27.81 an hour</strong></li>
    <li>Adult casual: <strong>$34.76 an hour</strong>, including the 25 per cent casual loading</li>
    <li>Level 2: $28.45 an hour. Level 3: $28.89 an hour</li>
</ul>

<p>So the Woolworths agreement sits roughly 1.6 per cent above the Award floor. That margin is deliberate and small. When the Full Bench approved the agreement it acknowledged that the deal "is somewhat less beneficial to part-time employees than the Award, in that it allows Woolworths to change a standard roster", and approved it anyway, adding that "nor does it matter that the margin is a small one."</p>

<h2>The Weekend Is Where the Money Is</h2>

<p>The base rate is not what most supermarket staff actually earn, because penalty rates change the arithmetic substantially. These are the Award Level 1 figures from 1 July 2026; the enterprise agreement mirrors the same percentages on its slightly higher base.</p>

<p><strong>If you are permanent (full-time or part-time):</strong></p>

<ul>
    <li>Monday to Friday after 6pm &mdash; 125 per cent, <strong>$34.76 an hour</strong></li>
    <li>Saturday &mdash; 125 per cent, <strong>$34.76 an hour</strong></li>
    <li>Sunday &mdash; 150 per cent, <strong>$41.72 an hour</strong></li>
    <li>Public holiday &mdash; 225 per cent, <strong>$62.57 an hour</strong></li>
</ul>

<p><strong>If you are casual:</strong></p>

<ul>
    <li>Monday to Friday after 6pm &mdash; 150 per cent, <strong>$41.72 an hour</strong></li>
    <li>Saturday &mdash; 150 per cent, <strong>$41.72 an hour</strong></li>
    <li>Sunday &mdash; 175 per cent, <strong>$48.67 an hour</strong></li>
    <li>Public holiday &mdash; 250 per cent, <strong>$69.53 an hour</strong></li>
</ul>

<p>One technical point that trips people up: <strong>the casual loading is added to the penalty, not multiplied by it.</strong> A casual Sunday is 175 per cent of the base, not 150 per cent multiplied by 1.25. If someone quotes you a higher number than the one above, they have done the sum wrong.</p>

<p>For context, the National Minimum Wage from 1 July 2026 is <strong>$1,004.90 a week, or $26.44 an hour</strong>. Note that this rose 6.00 per cent, while award rates rose 4.75 per cent; the two numbers are often confused because the minimum wage received an additional structural adjustment.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-woolworths-supermarket-jobs-in-australia-checkout.jpg" alt="A Woolworths team member scanning groceries at a checkout" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Casual roles outnumber part-time and full-time combined on the live board.</figcaption>
</figure>

<h2>If You Are Under 21, Read This Before You Do Any Sums</h2>

<p>This is the largest omission in every version of this article we could find, and it matters most to exactly the readers these guides target. <strong>Under-21s are paid a percentage of the adult rate.</strong></p>

<p>The Woolworths agreement is slightly more generous than the Award here, which is worth knowing:</p>

<ul>
    <li><strong>16 and under</strong> &mdash; 50 per cent (the Award pays 45 per cent under 16)</li>
    <li><strong>17</strong> &mdash; 60 per cent</li>
    <li><strong>18</strong> &mdash; 70 per cent</li>
    <li><strong>19</strong> &mdash; 80 per cent</li>
    <li><strong>20</strong> &mdash; <strong>100 per cent immediately</strong>, where the Award pays 90 per cent until six months' service</li>
</ul>

<p>In Award dollars at Level 1, that means a 16-year-old earns <strong>$13.91 an hour</strong> permanent or $17.39 casual; a 17-year-old $16.69 or $20.86; an 18-year-old $19.47 or $24.34; a 19-year-old $22.25 or $27.81. Only at 20 do you reach the full $27.81 or $34.76.</p>

<p>One development to watch: in March 2026 the Fair Work Commission issued a provisional decision to phase out junior rates for 18, 19 and 20-year-olds with more than six months' service across retail, fast food and pharmacy. <strong>It is provisional only.</strong> Final orders have not been made and nothing is in force, with a provisional start date of 1 December 2026.</p>

<p>Two other things that are genuinely part of the pay and rarely mentioned: <strong>superannuation is 12 per cent from 1 July 2026</strong>, and since the same date employers must pay it on every payday rather than quarterly.</p>

<h2>Can You Get a Visa for This? Almost Certainly Not</h2>

<p>If you are reading this from Pakistan, India or Bangladesh, this is the section that decides everything.</p>

<p><strong>A supermarket floor job cannot be sponsored.</strong> The occupation code that covers this work, ANZSCO 621111 Sales Assistant (General), appears on <strong>no Australian skilled occupation list at all</strong> &mdash; not the Core Skills Occupation List, not the medium or short-term lists, not the regional list. We checked the Department of Home Affairs' own occupation dataset and the published Core Skills list against each other, and they agree. Retail Supervisor, code 621511, is also absent. There is no nomination an employer could lodge even if it wanted to.</p>

<p>The only related code with a pathway is <strong>142111 Retail Manager (General)</strong>, which is on the Core Skills Occupation List and can be sponsored on a subclass 482, 186 or 494. That requires a skills assessment and a salary at or above the Core Skills Income Threshold, currently <strong>AUD 79,423</strong>. It is a store management career, not a first job.</p>

<p><strong>The working holiday route is closed to Pakistani passports.</strong> This one surprises people, because it is how many Europeans fund a year in Australia. Subclass 417 has nineteen eligible countries and subclass 462 has thirty. Pakistan is on neither list. India appears on the 462 list, subject to a ballot; Pakistan and Bangladesh do not appear at all.</p>

<p>So the realistic routes are narrow, and they are these:</p>

<ul>
    <li><strong>Student visa, subclass 500.</strong> You may work <strong>48 hours per fortnight</strong> while your course is in session, and unlimited hours during scheduled course breaks. You cannot work before the course starts. Masters by research and doctoral students have no cap.</li>
    <li><strong>Temporary Graduate visa, subclass 485.</strong> Unrestricted work rights, no hour cap, no employer restriction, so you can work full time. You must apply while in Australia holding an eligible student visa, and the age limit is 35.</li>
    <li><strong>Citizenship, permanent residence or a partner visa</strong>, all of which carry full work rights.</li>
</ul>

<p>If none of those apply to you, this guide is background reading rather than a plan. Our guides to <a href="/blog/visa-sponsorship-jobs-in-australia">visa sponsorship jobs in Australia</a> and <a href="/blog/how-foreign-workers-can-get-a-job-in-australia">how foreign workers can get a job in Australia</a> cover the occupations that are actually on the lists.</p>

<h2>Woolworths Says an Overseas Approach Is Probably a Scam</h2>

<p>Woolworths publishes this itself, and it is worth quoting in full because it is the clearest warning any large employer in this sector gives:</p>

<p style="border-left:4px solid #178841;padding-left:16px;margin:20px 0;"><strong>"For jobs in our stores (including Woolworths Supermarkets, Woolworths New Zealand, BIG W or Metro), we will typically advertise these roles in a way that attracts applicants who live near the store. If you live outside Australia or New Zealand and are contacted about a job at one of our stores, it is likely to be a scam."</strong></p>

<p>Woolworths also states plainly that it will never ask you to pay money for a job, flights, training or recruitment fees.</p>

<p>There is an awkward footnote to this. <strong>That same anti-scam page tells you to confirm a job exists on the "WOWcareers" site &mdash; and wowcareers.com.au no longer loads.</strong> The domain still resolves in DNS but the server accepts no connection on either port. The advice is sound; the link Woolworths gives for it is broken. Verify jobs on <strong>careers.woolworthsgroup.com.au</strong> instead.</p>

<h2>What Is Actually on the Board</h2>

<p>We read the whole Australian board rather than trusting a headline count. On the day we checked there were <strong>1,966 live vacancies across all Woolworths Group brands in Australia</strong>:</p>

<ul>
    <li>Woolworths Supermarkets &mdash; <strong>1,705</strong>, plus 43 at Woolworths Metro</li>
    <li>BIG W &mdash; 135. Primary Connect &mdash; 21. Woolworths Group support &mdash; 21</li>
</ul>

<p><strong>Store Team Member alone accounts for 1,076 of them</strong>, of which 908 are at Woolworths Supermarkets. The other titles worth searching: Cleaning and Trolley Collection (361), Baker (99), Trolley Collection Driver (60), Nightfill Manager (26), Assistant Nightfill Manager (25), Bakery Manager (17), Assistant Grocery Manager (15), Nightfill Team Member (13).</p>

<p>By employment type the split is <strong>942 casual, 659 part-time and 341 full-time</strong>, which confirms something worth planning around: casual is the normal entry point, not the exception. By state: Queensland 758, New South Wales 618, Victoria 238, Western Australia 233, South Australia 89, Tasmania 15, ACT 10, Northern Territory 5.</p>

<p><strong>One correction that undoes a whole section of most guides: there is no job called "Online Assistant".</strong> Zero live vacancies carry that title. A keyword search appears to return results only because it matches "Assistant Online Manager". The real online fulfilment titles are Assistant Online Manager (14), Dispatch Team Member (8), Online Manager (5) and Fulfilment Team Member (5). If you want online grocery work, those are the words to search.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-woolworths-supermarket-jobs-in-australia-online.jpg" alt="A Woolworths team member in the fresh produce aisle of a supermarket" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Store Team Member is 1,076 of the 1,966 live Australian vacancies.</figcaption>
</figure>

<h2>Why the Apply Links in Other Guides Are Dead</h2>

<p>Every guide to these jobs links specific vacancies. We tested four of them. <strong>All four return HTTP 404</strong> and redirect to a bare error page that says only:</p>

<p style="border-left:4px solid #178841;padding-left:16px;margin:20px 0;">"An error has occurred. Page not found. Continue to home page."</p>

<p>Woolworths does not even show a "this job has closed" message. You simply get an error, with no way to tell whether the job ended or the link was wrong.</p>

<p>The scale of the rot is easy to measure. Live Australian requisition numbers currently run from about 49,907 to 113,654. <strong>The newest ID cited in the guides we checked is 54,171 &mdash; and exactly one of the 1,966 live vacancies sits below it.</strong> Two of the linked IDs are below 27,000, where nothing at all is live. Those links are somewhere between 59,000 and 90,000 requisitions out of date.</p>

<p>It is not surprising when you look at the turnover: <strong>90 per cent of live Australian vacancies were posted in the last 30 days</strong>, and 37 per cent in the last seven. A supermarket job board is a stream, not a catalogue. Bookmark the search, never a vacancy.</p>

<h2>What Woolworths Says It Wants</h2>

<p>The job descriptions are boilerplate reused across thousands of listings, which means we can quote them safely without pointing you at a vacancy that will be gone next week. Woolworths describes team members as "the face of Woolies" and asks you to "greet, acknowledge, show care and thank our customers". It looks for someone who has "a genuine interest in food &amp; shopping" and who "embraces being flexible and can easily adapt to change".</p>

<p>Live adverts also mention team discounts across Woolworths Group brands and 24/7 wellbeing support through the Sonder app.</p>

<p>One claim to be careful with, because it circulates widely: Woolworths' Regional Australia Reports are <strong>often cited as saying regional supermarket jobs are "entry-level roles requiring minimal work experience". Neither the 2024 nor the 2025 report contains that phrase or that claim.</strong> What the 2024 report does say is that over 15,700 of its regional team members are under 25 and 55 per cent of the team are female &mdash; which supports the same point honestly.</p>

<p>Similarly, the widely quoted "70 per cent of the Australian Supermarkets team are multiskilled, with at least 44 per cent working in a second department each month" is real, but it is from the <strong>2025</strong> Sustainability Report and describes financial year 2025. Woolworths' 2026 reporting does not repeat it. The multiskilling point still stands; the vintage should be stated.</p>

<p>And the AI-enabled "Career Marketplace" that guides list under career progression for store staff is, by Woolworths' own description, a <strong>support office</strong> tool. It was provided initially to the support team and expanded to all support office teams. It is not something a store team member can use.</p>

<h2>Where Woolworths Is Growing</h2>

<p>Two announcements are worth knowing if you are choosing where to look.</p>

<p><strong>Tasmania.</strong> In September 2026 Woolworths began construction on a $29 million shopping centre at Lauderdale, expected to create more than 300 jobs over the life of the project, including more than 260 ongoing roles across the whole centre and 75 during construction. Lauderdale is one of four new Tasmanian supermarkets planned, together expected to create around 650 direct ongoing jobs. Woolworths already employs 5,200 people in Tasmania across 32 supermarkets &mdash; useful perspective for a state that currently shows only 15 live vacancies.</p>

<p><strong>Western Sydney.</strong> The St Marys eStore opened in 2026 with 150 new roles, including 15 refugees from the area through a community partnership, and Woolworths noted that many of the roles were people's first job. It is the third eStore after Maroochydore and Carrum Downs.</p>

<p>Across regional Australia, Woolworths' 2025 Regional Australia Report puts its regional workforce at <strong>48,000 team members across more than 530 regional sites</strong>, paying $1.8 billion in wages a year. Group-wide it employs over 200,000 people across Australia and New Zealand.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Check your work rights first.</strong> If you are on a student visa, know your 48-hour fortnightly cap and keep track of it; breaching it puts the visa at risk, not just the job.</li>
    <li><strong>Search the official board</strong> at careers.woolworthsgroup.com.au. Filter to Australia, then to store roles, then narrow by suburb and radius. Do not use a job link from an article, including this one; use the search.</li>
    <li><strong>Search titles, not categories.</strong> "Store Team Member" is the big one. Add "Nightfill", "Cleaning and Trolley Collection", "Baker" and "Dispatch Team Member" if you are flexible about the work.</li>
    <li><strong>Decide your real availability before you apply,</strong> and state it accurately. Casual roles are the majority, and the roster is the main thing that differs between two otherwise identical listings.</li>
    <li><strong>Apply near where you live.</strong> Woolworths advertises store roles to attract local applicants, and says so on its own scam page. An application from the other side of the country reads as noise.</li>
    <li><strong>Expect to discuss availability, customer service and safety.</strong> If you have no work history, use school, volunteering, sport or family responsibilities, and describe what you actually did.</li>
</ol>

<p>For comparison across markets, <a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">Tesco supermarket jobs in the UK</a> covers an employer that does publish its rate, <a href="/blog/store-assistant-jobs-in-uk">store assistant jobs in UK</a> covers the same work elsewhere, and <a href="/blog/retail-associate-jobs-in-usa">retail associate jobs in USA</a> gives the American equivalent.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does Woolworths pay per hour in Australia?</h3>
<p>Woolworths publishes no rate on its adverts. Its enterprise agreement set a Store Team Member Level 1 base of $26.07 an hour, rising each July by the Annual Wage Review percentage, which puts the current adult rate at roughly $28.26 an hour. The legal floor beneath it, the General Retail Industry Award, pays $27.81 an hour for permanent Level 1 and $34.76 for casual from 1 July 2026.</p>

<h3>What is the casual rate at Woolworths?</h3>
<p>Casuals receive a 25 per cent loading on top of the base rate. Under the Award that makes Level 1 casual work $34.76 an hour, rising to $41.72 on Saturdays and evenings after 6pm, $48.67 on Sundays and $69.53 on public holidays. The loading is added to the penalty rather than multiplied by it.</p>

<h3>How much does Woolworths pay a 16 year old?</h3>
<p>Half the adult rate. Under the Woolworths agreement a 16-year-old and under is paid 50 per cent, a 17-year-old 60 per cent, an 18-year-old 70 per cent and a 19-year-old 80 per cent, reaching the full adult rate at 20. In Award dollars that is about $13.91 an hour permanent or $17.39 casual at 16.</p>

<h3>Can Woolworths sponsor a work visa for a supermarket job?</h3>
<p>No. The occupation code for this work, ANZSCO 621111 Sales Assistant (General), is on no Australian skilled occupation list, so there is no nomination an employer could lodge. Retail Supervisor is also absent. Only Retail Manager (General), code 142111, has a sponsorship pathway, and it requires a skills assessment and a salary of at least AUD 79,423.</p>

<h3>Can Pakistanis get a working holiday visa for Australia?</h3>
<p>No. Pakistan is not among the nineteen countries eligible for subclass 417 or the thirty eligible for subclass 462. India appears on the 462 list subject to a ballot; Pakistan and Bangladesh do not appear on either. The realistic routes into this work are a student visa with its 48-hour fortnightly cap, or a Temporary Graduate visa with unrestricted work rights.</p>

<h3>How many Woolworths jobs are open right now?</h3>
<p>About 1,966 across all Woolworths Group brands in Australia, of which 1,705 are at Woolworths Supermarkets and 1,076 carry the title Store Team Member. The split by type is 942 casual, 659 part-time and 341 full-time, and Queensland and New South Wales together account for roughly 70 per cent of them.</p>

<h3>Why do Woolworths job links in other articles not work?</h3>
<p>Because they point at individual requisition IDs, which turn over extremely fast. Every one we tested returns a 404 and redirects to a page reading only "An error has occurred. Page not found." Ninety per cent of live Australian vacancies were posted within the last 30 days, and only one of nearly 2,000 live jobs has an ID lower than the newest one those articles link.</p>

<h3>Is there really no Online Assistant job at Woolworths?</h3>
<p>Not under that title. There are zero live vacancies called Online Assistant; searching it appears to return results only because it partially matches Assistant Online Manager. For online grocery work the real titles are Assistant Online Manager, Online Manager, Fulfilment Team Member and Dispatch Team Member.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Woolworths store team member pay rate 2026</li>
    <li>Woolworths casual hourly rate Sunday</li>
    <li>Woolworths enterprise agreement 2024 pay rates</li>
    <li>Retail award junior rates by age Australia</li>
    <li>Does Woolworths sponsor visa Australia</li>
    <li>Student visa work hours Australia 48 hours</li>
    <li>Woolworths careers application process</li>
    <li>Woolworths job scam warning</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a></li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a></li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a></li>
    <li><a href="/blog/store-assistant-jobs-in-uk">Store Assistant Jobs in UK</a></li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a></li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a></li>
    <li><a href="/blog/how-to-apply-for-csl-laboratory-jobs-in-australia">How to Apply for CSL Laboratory Jobs in Australia</a> &mdash; an Australian occupation that IS on the skilled list, and what it pays.</li>
</ul>
HTML;
    }
}
