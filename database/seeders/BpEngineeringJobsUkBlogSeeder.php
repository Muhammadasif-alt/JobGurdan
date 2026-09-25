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
 * "How to Apply for BP Engineering Jobs in the UK" - an employer guide rebuilt
 * on bp's own careers site, bp's SEC filings and press releases, the Engineering
 * Council register of licensed institutions and the Home Office register of
 * licensed sponsors, rather than the job-board averages and the mixture of
 * agency postings the draft relied on.
 *
 * Corrections to the draft (checked against careers.bp.com, bp's Q2 2026 Form
 * 6-K, engc.org.uk, gov.uk and Spelthorne Borough Council coverage, September
 * 2026):
 *
 * 1. The draft offers Indeed as an apply route. Aggregator links are against
 *    site policy, and bp's own recruitment fraud page is blunter than the
 *    policy is: bp says it will never "allow recruitment agencies or third
 *    parties to apply for roles on behalf of candidates". bp's individual
 *    adverts also sit on requisition URLs of the form
 *    careers.bp.com/job-description/RQ115312, which die with the vacancy. The
 *    only durable, ID-free link is the search page, careers.bp.com/listing.
 *
 * 2. The draft's "GBP 57,364 per year, 51% above the national average" is
 *    Indeed data and is removed outright. bp publishes no salary on its
 *    experienced engineering adverts; its own professionals FAQ says "We offer
 *    a competitive salary which takes into consideration your experience, as
 *    well as internal and external market data." The listing therefore carries
 *    no salary at all.
 *
 * 3. The only UK pay bp does publish is on its early careers pages, and the
 *    draft misses it entirely: a graduate starting salary of "GBP 37k to 50k
 *    (depending on business area)" with a GBP 3k to 5k settling-in allowance
 *    and a flexible allowance of 20% of base salary, and an apprentice salary
 *    "ranging from GBP 21,000 to GBP 32,000 per annum" with a GBP 3,000
 *    settling-in allowance. Both are quoted with their scope attached.
 *
 * 4. "UK hubs in Sunbury, London and Reading" is out of date on two of the
 *    three. bp has told Spelthorne Borough Council it intends to leave the
 *    Sunbury campus between 2027 and 2028; around 1,800 staff are based there,
 *    the council owns the site, and one lease does not expire until 2036.
 *
 * 5. "Reading" is the wrong town and, increasingly, the wrong company. The
 *    Castrol Technology Centre is at Pangbourne in Berkshire, and on 24
 *    December 2025 bp agreed to sell a 65% shareholding in Castrol to Stonepeak
 *    at an enterprise value of USD 10 billion, roughly USD 6 billion in cash,
 *    completion expected by the end of 2026, with bp retaining 35%. A "Senior
 *    Embedded Engineer, Castrol brand" is not a durable bp UK engineering hub.
 *
 * 6. The draft's "Remote/UK-wide via BP contractor partners" row is the core
 *    problem the brief itself flags: it files agency and contractor postings
 *    under bp. bp does not recruit that way and says so on its own recruitment
 *    fraud page. The role table is replaced with bp's own published job
 *    categories from its search page.
 *
 * 7. Qualifications "a graduate degree in Engineering, Business, Mathematics,
 *    Economics" is a trading and analyst entry line, not an engineering one.
 *    bp's own graduate page lists the streams as "business, digital,
 *    engineering, science or supply, trading & shipping", with applications
 *    opening on 14 October 2026.
 *
 * 8. "A recognised professional qualification to NVQ level 3 or equivalent" is
 *    agency wording. The technician route bp actually names is APTUS,
 *    previously the Oil and Gas Technical Apprenticeships Programme, in
 *    electrical maintenance, mechanical maintenance, process operations and
 *    instrumentation and control maintenance, opening on 4 November 2026.
 *
 * 9. "Chartered status via IChemE, IEEE, or BCS" is wrong on IEEE. The
 *    Engineering Council licenses the institutions that assess for CEng, IEng
 *    and EngTech in the UK; IMechE, IChemE, IET, the Energy Institute and BCS
 *    are licensed, IEEE is not and cannot award UK chartered status.
 *
 * 10. "BPSS or SC security clearance requiring 5 years of continuous UK address
 *     history" belongs to government contracting, not to bp. BPSS and SC are UK
 *     national security vetting standards for people with access to government
 *     assets, and gov.uk's clearance-levels guidance attaches no five-year
 *     address rule to BPSS. bp's own FAQ describes checks on "educational
 *     qualifications, employment history /references and right to work in the
 *     region of employment".
 *
 * 11. "Free single medical cover and digital GP service for UK employees at
 *     some BP-affiliated contractors" is a contractor benefit wearing bp's
 *     name. bp's own rewards pages describe a total reward package of direct
 *     pay - base pay, bonus and share options - plus core and flexible
 *     benefits including private medical insurance, access to online doctors,
 *     an employee assistance programme, the Thrive wellbeing portal and the
 *     Energize recognition programme, varying by country.
 *
 * 12. The draft ignores right to work, which is the question that decides
 *     everything for this site's readers. bp plc appears on the Home Office
 *     register of licensed sponsors dated 24 September 2026 with a Worker (A
 *     rating) licence for both Skilled Worker and Global Business Mobility:
 *     Senior or Specialist Worker, and BP Oil UK Ltd holds a Skilled Worker
 *     licence. That decides nothing on its own: the occupation code and the
 *     going rate do. SOC 2121, 2122, 2123, 2124, 2125, 2126, 2127, 2129 and
 *     2134 are all Higher Skilled and eligible, and the general threshold is
 *     GBP 41,700 or the occupation's going rate, whichever is higher.
 *
 * 13. The draft describes a company that no longer exists in that shape. Meg
 *     O'Neill became chief executive on 1 April 2026, bp reorganised into two
 *     segments - upstream and downstream - with the restructure effective at
 *     the start of July 2026, it is cutting around 700 of roughly 8,500
 *     non-frontline roles, and bp's own Q2 2026 Form 6-K records that "In July
 *     bp announced its intention to market its North Sea business for a
 *     potential sale": about 1,100 staff and five production hubs.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BpEngineeringJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.bp.com/listing';

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
        $title = 'How to Apply for BP Engineering Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'bp publishes no salary on its engineering adverts, it is leaving the Sunbury campus most guides still call its UK hub, and its North Sea business is up for sale. Here is what bp does publish, and the rules that decide sponsorship.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-bp-engineering-jobs-in-the-uk.jpg',
                'tags' => 'bp engineering jobs, bp careers uk, bp graduate programme 2026, aptus apprenticeship, oil and gas engineering jobs uk, skilled worker visa engineering, bp jobs uk, engineering jobs in uk',
                'meta_title' => 'BP Engineering Jobs UK: Pay, Sites and How to Apply',
                'meta_description' => 'bp publishes no salary on its UK engineering adverts. Here is the pay it does publish, where its UK sites really are, and the Skilled Worker rules.',
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
            ['name' => 'bp, United Kingdom'],
            ['type' => 'Company', 'display_reference' => 'bp-united-kingdom']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Engineer, BP UK Sites',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time; offshore and plant roles run on rotation, office-based engineering roles are hybrid where bp offers it',
                'language' => 'English',
                // No salary. bp prints none on its experienced engineering
                // adverts, and its own professionals FAQ says only that it
                // offers "a competitive salary which takes into consideration
                // your experience, as well as internal and external market
                // data". The GBP 57,364 average in circulation is Indeed data
                // and is not republished here. The only UK figures bp does
                // publish are its early careers bands, which describe the
                // graduate and apprentice entry points rather than this
                // listing, so they stay in the guide and out of these columns.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Mechanical, chemical, process, control and automation, subsurface, wells and software engineering roles across bp sites in the United Kingdom, applied for on bp\'s own careers search.',
                'seo_keywords' => 'bp engineering jobs, bp careers uk, bp jobs uk, oil and gas engineering jobs uk, process engineer jobs uk, bp graduate programme',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>bp recruits engineers in the United Kingdom across mechanical, chemical, process, control and automation, subsurface, wells, project and software disciplines, based at its UK offices and operating sites and applied for on bp's own careers search.</p>

<h3>What the work involves</h3>
<p>Designing, operating and maintaining energy assets safely: process and integrity engineering on producing facilities, control and automation work, wells and subsurface engineering, project engineering on new-build low carbon plant, and the digital and software engineering that supports all of it.</p>

<h3>Common requirements</h3>
<ul>
    <li>A relevant engineering degree, or an apprenticeship-trained technician background for maintenance and operations roles</li>
    <li>Chartered or incorporated registration through an Engineering Council licensed institution &mdash; IMechE, IChemE, IET, the Energy Institute or BCS for IT &mdash; is valued on senior roles</li>
    <li>The right to work in the United Kingdom, or an engineering occupation code that meets the Skilled Worker threshold of &pound;41,700 or the going rate, whichever is higher</li>
    <li>Willingness to travel to plant, and to work offshore on rotation for some roles</li>
    <li>A CV kept current and tailored to each posting, which bp asks for explicitly</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, hiring steps and benefits for these roles are set by bp, and visa rules are set by the Home Office &mdash; not by JobGader. Apply directly on bp's own careers site. bp says it never allows recruitment agencies or third parties to apply for roles on its behalf, and never asks candidates for money for visa fees, taxes or travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply on bp's own careers search at careers.bp.com, filter by United Kingdom and by the Engineering job category, and work through the five steps bp publishes: apply online, application review, a first conversation, interviews and assessments, then offer and welcome.</strong> No agency can put you through that door: bp states on its own recruitment fraud page that it will never allow recruitment agencies or third parties to apply for roles on behalf of candidates.</p>

<p>Two things before you spend an evening on this. <strong>bp publishes no salary on its experienced engineering adverts</strong> &mdash; the figure circulating for "bp engineers in the UK" is job-board data and is not repeated here. And <strong>the bp you are applying to changed shape in 2026</strong>: a new chief executive, two business segments instead of a sprawl of them, a UK North Sea business up for sale, and a departure from the Sunbury campus that most guides still call its UK technology hub.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://careers.bp.com/listing" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#007f3d;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9881; Search bp Engineering Jobs &rarr;
    </a>
</div>

<h2>The bp You Are Actually Applying To in 2026</h2>

<p>Most bp career guides describe the company as it looked before the reset, and the gap matters because it decides which UK sites still have engineering work in them.</p>

<p><strong>Meg O'Neill became chief executive on 1 April 2026.</strong> bp reorganised into two business segments, <strong>upstream and downstream</strong>, with the restructure taking effect at the start of July 2026, dismantling the separate low carbon division. An internal memo reported that month put the cuts at <strong>around 700 of roughly 8,500 non-frontline roles</strong>, with frontline operators, technicians and maintenance staff expected to be left alone. bp employs about <strong>93,700 people across 61 countries</strong>, of whom roughly <strong>13,960 are in the UK</strong>.</p>

<p>The biggest UK change is in bp's own filing rather than in any press summary. Its <strong>Q2 2026 Form 6-K</strong> records, flatly: "In July bp announced its intention to market its North Sea business for a potential sale." The announcement came on <strong>31 July 2026</strong>, after sixty years of North Sea production. The business is <strong>five production hubs</strong> &mdash; two in the central North Sea, three west of Shetland &mdash; employing about <strong>1,100 people</strong> and producing around <strong>117,000 barrels of oil equivalent a day in 2025</strong>. O'Neill's stated reasoning was that as bp focuses its portfolio, "we believe our North Sea business will be better positioned as part of another company".</p>

<p>That does not mean stop applying to Aberdeen. It means read an Aberdeen advert knowing the asset may sit with a different owner within a year or two, which in the North Sea is an ordinary fact of a career rather than a disaster. What it should stop you doing is treating a bp North Sea role as a fixed point in a visa plan.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bp-engineering-jobs-in-the-uk-site.jpg" alt="Industrial energy plant and processing site" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Where bp's UK Engineering Work Actually Is</h2>

<p>The "Sunbury, London and Reading" triangle you will see repeated is wrong on two of its three corners.</p>

<ul>
    <li><strong>Sunbury-on-Thames is being vacated.</strong> bp has told <strong>Spelthorne Borough Council</strong> that it intends to leave the campus <strong>between 2027 and 2028</strong>. Around <strong>1,800 staff</strong> are based there. The council owns the site, says its finances are not immediately affected because bp remains tied into lease agreements, and notes that <strong>one lease does not expire until 2036</strong>. bp has been in Sunbury since it bought a Georgian mansion there for research in 1916, so this is the end of something, but it is a real published plan and not a rumour.</li>
    <li><strong>"Reading" means Pangbourne, and it means Castrol.</strong> The Castrol Technology Centre sits at Pangbourne in Berkshire, not Reading. More to the point, on <strong>24 December 2025 bp agreed to sell a 65% shareholding in Castrol to Stonepeak</strong> at an enterprise value of <strong>USD 10 billion</strong>, with cash proceeds of about <strong>USD 6 billion</strong> and completion expected by the end of 2026. bp keeps 35%. A Castrol engineering job in 2026 is a job with a company bp is in the process of ceasing to control.</li>
    <li><strong>London is the one corner that holds.</strong> bp's head office is in London, and its digital, trading, shipping and corporate engineering roles cluster there.</li>
    <li><strong>Aberdeen</strong> remains the North Sea base, with the caveat above about the sale.</li>
    <li><strong>Teesside</strong> is where the new-build engineering is. <strong>NZT Power</strong>, the bp and Equinor joint venture, is under construction with <strong>start-up expected in 2028</strong>, up to <strong>742 megawatts</strong> of flexible low-carbon power and up to around <strong>2 million tonnes of CO&#8322; a year</strong> captured.</li>
</ul>

<p>Teesside also shows the limit of planning a career around announcements. bp cancelled its <strong>80 MW HyGreen Teesside</strong> green hydrogen project in March 2025 as part of the strategy reset, and on <strong>1 December 2025 withdrew the development consent application for H2Teesside</strong>, which would have been one of the UK's largest low-carbon hydrogen plants, after the land was earmarked for a data centre instead. Two projects that recruiters were describing as a decade of jobs are simply gone. NZT Power is the one with steel in the ground.</p>

<h2>What bp Pays, and the Number You Should Ignore</h2>

<p>The figure doing the rounds &mdash; "bp engineers in the UK earn an average of around GBP 57,364, 51% above the national average" &mdash; comes from a job aggregator's user-submitted data. It is not a bp figure, it is not tied to any discipline or grade, and it is not repeated in this guide.</p>

<p>bp's own position is stated in its professionals FAQ: <em>"We offer a competitive salary which takes into consideration your experience, as well as internal and external market data."</em> That is all. UK law does not require pay transparency in adverts, so unlike bp's postings in several US states, its UK engineering adverts carry no band.</p>

<p>There are two exceptions, and they are both on bp's own early careers pages, which is why they can be quoted here at all.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#007f3d;color:#fff;">
            <th style="padding:10px;text-align:left;">bp UK programme</th>
            <th style="padding:10px;text-align:left;">What bp publishes</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate</strong></td><td style="padding:10px;">"&pound;37k to &pound;50k starting salary (depending on business area)", a one-off settling-in allowance of &pound;3k to &pound;5k, and a flexible allowance of 20% of base salary. Applications open <strong>14 October 2026</strong>.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Apprenticeship</strong></td><td style="padding:10px;">"Competitive salary ranging from &pound;21,000 to &pound;32,000 per annum depending on your business area", a one-off &pound;3,000 settling-in allowance in your first month, and the same 20% flexible benefits allowance. Applications open <strong>4 November 2026</strong>.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Experienced engineer</strong></td><td style="padding:10px;">Nothing. bp publishes no band, and this guide publishes none on its behalf.</td></tr>
    </tbody>
</table>
</div>

<p>If you want a UK engineering number that is not guesswork, use the statutory one instead. The Home Office publishes a going rate for every occupation code, drawn from official earnings data, and those are the floors a sponsored job must clear. They are not bp's salaries, but they are real, dated and published by a government, which is more than the aggregator average can say.</p>

<h2>Can bp Sponsor an Engineer? Yes, With Conditions That Decide It</h2>

<p>This is the section the brief left out entirely, and for anyone reading from outside the UK it is the only one that settles anything.</p>

<p>Start with the licence. On the <strong>Home Office register of licensed sponsors dated 24 September 2026</strong>, <strong>BP plc, London</strong> appears with a <strong>Worker (A rating)</strong> licence for <strong>Skilled Worker</strong> and separately for <strong>Global Business Mobility: Senior or Specialist Worker</strong>. <strong>BP Oil UK Ltd</strong> in Central Milton Keynes holds a Skilled Worker licence too. Be careful reading that register, though: it also lists dozens of unrelated entries beginning "BP" &mdash; service stations, convenience stores, carpet firms &mdash; that have nothing to do with the energy company.</p>

<p>Then understand what a licence is worth on its own, which is almost nothing. Sponsorship is decided per occupation, not per employer. The good news for engineers is that the codes are eligible. Under the rules in force since <strong>22 July 2025</strong>, these are all classified <strong>Higher Skilled</strong> and eligible for the Skilled Worker route, with the going rates the Home Office publishes for them:</p>

<ul>
    <li><strong>2121 Civil engineers</strong> &mdash; &pound;50,400 a year</li>
    <li><strong>2122 Mechanical engineers</strong> &mdash; &pound;46,800</li>
    <li><strong>2123 Electrical engineers</strong> &mdash; &pound;58,700</li>
    <li><strong>2124 Electronics engineers</strong> &mdash; &pound;52,000</li>
    <li><strong>2125 Production and process engineers</strong> &mdash; &pound;45,000</li>
    <li><strong>2126 Aerospace engineers</strong> &mdash; &pound;52,400</li>
    <li><strong>2127 Engineering project managers</strong> &mdash; &pound;51,900</li>
    <li><strong>2129 Engineering professionals n.e.c.</strong> &mdash; &pound;46,100</li>
    <li><strong>2134 Programmers and software development professionals</strong> &mdash; &pound;54,700</li>
</ul>

<p>On top of that sits the <strong>general salary threshold of &pound;41,700 a year</strong>, with an hourly floor of <strong>&pound;17.13</strong> based on a 37.5-hour week. The rule is the higher of the two: threshold or going rate. So a mechanical engineering job must pay at least &pound;46,800 to be sponsorable, an electrical engineering job at least &pound;58,700, and the general &pound;41,700 only binds where the going rate is lower. New entrants and PhD holders have reduced thresholds &mdash; &pound;33,400 and &pound;37,500 respectively &mdash; and the second table of lower going rates applies to those routes.</p>

<p>The practical consequence: bp <em>can</em> sponsor an engineer, but only into a role priced above that floor, and only where it has chosen to sponsor rather than hire from the resident labour market. Its graduate programme pays from &pound;37,000, which sits below the &pound;41,700 general threshold and below every engineering going rate in the table above, so a graduate offer is not automatically a sponsorable one. If you are applying from abroad, read the advert for what it says about work authorisation and ask the recruiter directly before you build a plan on it. Our guide to <a href="/blog/jobs-in-uk-for-foreigners">jobs in UK for foreigners</a> sets out which routes actually exist.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-bp-engineering-jobs-in-the-uk-engineer.jpg" alt="Engineer working with equipment on an industrial site" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>Qualifications, Chartership and the Clearance Myth</h2>

<p>Three pieces of common advice about bp engineering jobs are wrong, and one of them will waste your money.</p>

<p><strong>The degree list is from the wrong department.</strong> "A graduate degree in Engineering, Business, Mathematics or Economics" is what a trading or analyst intake asks for. bp's own graduate page describes its streams as "business, digital, engineering, science or supply, trading &amp; shipping". If you want engineering, apply into engineering; the maths-and-economics wording belongs to supply and trading.</p>

<p><strong>"NVQ level 3 or equivalent" is agency phrasing.</strong> The technician route bp names on its own pages is <strong>APTUS</strong> &mdash; "previously known as the Oil and Gas Technical Apprenticeships Programme (OGTAP)", which bp says has been "offering prestigious onshore and offshore work experience for over 25 years". bp "supports the APTUS apprenticeship programme by providing site placement opportunities for apprentice technicians" in four disciplines: <strong>electrical maintenance, mechanical maintenance, process operations, and instrumentation and control maintenance</strong>.</p>

<p><strong>IEEE cannot make you a Chartered Engineer in the UK.</strong> The <strong>Engineering Council</strong> licenses the professional engineering institutions that assess for CEng, IEng and EngTech. <strong>IMechE, IChemE, IET, the Energy Institute and BCS</strong> are all licensed; <strong>IEEE is not on the list at all</strong>. IEEE membership is worth having for an international software or electronics career, but if your goal is post-nominals a UK employer recognises, route your application through a licensed institution.</p>

<p><strong>And nobody at bp is asking you for SC clearance.</strong> <strong>BPSS</strong> is the baseline standard for pre-employment screening of people with access to <em>government</em> assets, and <strong>SC</strong> is a national security clearance for access to SECRET material. They are UK government vetting standards, applied to civil servants, the armed forces and government contractors. gov.uk's own clearance-levels guidance attaches no blanket "5 years of continuous UK address history" rule to BPSS. bp's professionals FAQ describes its checks in ordinary terms: "educational qualifications, employment history /references and right to work in the region of employment". If a recruiter tells you a bp engineering job needs SC clearance and offers to arrange it for a fee, you are not talking to bp.</p>

<h2>Benefits: What bp Lists, and What It Does Not</h2>

<p>bp describes its total reward package as <strong>direct pay</strong> &mdash; base pay, bonus and share options &mdash; alongside a range of <strong>core and flexible benefits</strong> it calls cafeteria elements. Named on its own pages: a discretionary annual bonus, the <strong>Energize</strong> recognition points programme and spot awards, private medical insurance and access to online doctors, an employee assistance programme, the <strong>Thrive</strong> global wellbeing portal, free access to mindfulness resources, retirement and savings plans, life insurance, sick and compassionate leave, annual leave on top of public holidays, and a hybrid model of work for certain roles with flexible arrangements considered.</p>

<p>What bp does <em>not</em> publish is a country-by-country breakdown, which is exactly where the draft's "free single medical cover and digital GP service for UK employees at some BP-affiliated contractors" came from. Read that clause again: <em>at some BP-affiliated contractors</em>. That is a staffing agency's benefit sheet with bp's name attached to it, and it tells you nothing about what bp itself will give you. The same applies to "potential equity or stock options": share options do appear in bp's description of direct pay, but for UK early careers what bp actually prints is a flexible allowance of 20% of base salary. Take the benefits list from your own offer letter, not from a guide.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Start at careers.bp.com/listing</strong>, not at an aggregator. Filter by country for United Kingdom and by job category for Engineering; bp's other categories include Operations, Project Management, Research &amp; Technology, Subsurface, Wells, IT&amp;S, HSSE and Shipping, and engineering work is scattered across several of them.</li>
    <li><strong>Choose your experience level.</strong> bp splits the search into Professionals and Early careers, and the two have completely different calendars.</li>
    <li><strong>Diary the early careers dates if they apply to you.</strong> Graduate applications open <strong>14 October 2026</strong>; apprenticeship applications open <strong>4 November 2026</strong>. These are windows, not rolling adverts.</li>
    <li><strong>Keep the CV current and tailored,</strong> which bp asks for explicitly, and be aware bp offers an optional AI matching tool that reads an uploaded CV. It is optional, and you can apply for anything regardless of what it suggests.</li>
    <li><strong>Expect bp's five steps:</strong> apply online, application review by the talent acquisition team and often the hiring manager, a first conversation by phone or video, interviews and assessments including technical interviews or an assessment centre depending on the role, then a verbal offer followed by written confirmation and pre-employment checks.</li>
    <li><strong>Confirm the work authorisation position before you invest.</strong> A sponsored engineering role must clear &pound;41,700 or the going rate for its occupation code, whichever is higher.</li>
    <li><strong>Never pay anyone for a bp job.</strong> bp says it will never request money for visa fees, taxes or a percentage of travel expenses, never ask for passport and bank details early on, never email from Yahoo, Gmail or Live.com accounts, and never allow agencies or third parties to apply on a candidate's behalf.</li>
</ol>

<p>If bp's UK vacancy list is thin in your discipline this quarter &mdash; and after a 700-role cut and a North Sea sale it may well be &mdash; the same qualifications travel. Our <a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">Siemens engineering jobs in Germany</a> guide covers the nearest large European equivalent, and <a href="/blog/electrician-jobs-in-uk">electrician jobs in UK</a> covers the trade route into the same plants.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I apply for a bp engineering job in the UK?</h3>
<p>Through bp's own careers search at careers.bp.com/listing, filtering by United Kingdom and the Engineering job category. bp then runs five steps: apply online, application review, a first conversation, interviews and assessments, and offer and welcome. bp does not accept applications made by agencies on your behalf.</p>

<h3>What does bp pay its engineers in the UK?</h3>
<p>bp does not publish a figure. Its professionals FAQ says only that it offers a competitive salary based on your experience and internal and external market data. The averages quoted elsewhere come from job aggregators, not from bp. The only UK pay bp publishes is for early careers.</p>

<h3>What does the bp graduate programme pay, and when does it open?</h3>
<p>bp's own UK page gives a starting salary of &pound;37k to &pound;50k depending on business area, a one-off settling-in allowance of &pound;3k to &pound;5k, and a flexible allowance of 20% of base salary. Applications open on 14 October 2026.</p>

<h3>Does bp sponsor Skilled Worker visas for engineers?</h3>
<p>bp plc holds a Worker (A rating) Skilled Worker licence on the Home Office register, but a licence alone decides nothing. The role must sit in an eligible occupation code and pay at least &pound;41,700 or the published going rate for that code, whichever is higher.</p>

<h3>Which engineering occupation codes are eligible for sponsorship?</h3>
<p>Civil (2121), mechanical (2122), electrical (2123), electronics (2124), production and process (2125), aerospace (2126), engineering project managers (2127), engineering professionals n.e.c. (2129) and software development (2134) are all classified Higher Skilled and eligible.</p>

<h3>Is bp still based at Sunbury-on-Thames?</h3>
<p>Not for much longer. bp has told Spelthorne Borough Council it intends to leave the Sunbury campus between 2027 and 2028. Around 1,800 staff are based there, the council owns the site, and one lease runs to 2036.</p>

<h3>Do I need chartered status through IEEE to work for bp?</h3>
<p>No, and IEEE could not give you UK chartered status anyway. The Engineering Council licenses the institutions that assess for CEng, IEng and EngTech; IMechE, IChemE, IET, the Energy Institute and BCS are licensed, IEEE is not.</p>

<h3>Does a bp engineering job need SC or BPSS security clearance?</h3>
<p>No. BPSS and SC are UK government vetting standards for people with access to government assets and classified material, not bp's own checks. bp describes its screening as qualifications, employment history and references, and right to work in the region of employment.</p>

<h2>People Also Search For</h2>

<h3>bp careers UK</h3>
<p>All of it runs through careers.bp.com, with country and job category filters; individual advert URLs carry a requisition number and expire with the vacancy.</p>

<h3>bp graduate scheme 2026</h3>
<p>Streams in business, digital, engineering, science and supply, trading and shipping, rotational, with applications opening on 14 October 2026.</p>

<h3>APTUS apprenticeship</h3>
<p>Formerly the Oil and Gas Technical Apprenticeships Programme, covering electrical maintenance, mechanical maintenance, process operations and instrumentation and control maintenance. Applications open 4 November 2026.</p>

<h3>bp North Sea sale</h3>
<p>Announced 31 July 2026: five production hubs, about 1,100 staff and around 117,000 barrels of oil equivalent a day in 2025, put up for sale after sixty years of production.</p>

<h3>bp Sunbury closure</h3>
<p>bp intends to leave the Spelthorne-owned campus between 2027 and 2028, affecting around 1,800 staff, with one lease running to 2036.</p>

<h3>Castrol Stonepeak deal</h3>
<p>bp agreed on 24 December 2025 to sell 65% of Castrol to Stonepeak at a USD 10 billion enterprise value, about USD 6 billion cash, completing by the end of 2026, with bp retaining 35%.</p>

<h3>Net Zero Teesside jobs</h3>
<p>NZT Power, the bp and Equinor joint venture, is under construction with start-up expected in 2028: up to 742 megawatts and around 2 million tonnes of CO&#8322; captured a year.</p>

<h3>Skilled Worker going rate for engineers</h3>
<p>&pound;46,800 for mechanical, &pound;58,700 for electrical, &pound;45,000 for production and process, against a general threshold of &pound;41,700, with the higher of the two applying.</p>

<h2>More Job Guides</h2>

<p>If bp is one option rather than the only one, these cover the rest of the engineering market and the UK jobs around it:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; the nearest large European employer, in a country that does publish collective pay.</li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; the Gulf route for the same upstream disciplines.</li>
    <li><a href="/blog/how-to-apply-for-sabic-manufacturing-jobs-in-saudi-arabia">How to Apply for SABIC Manufacturing Jobs in Saudi Arabia</a> &mdash; process and plant engineering on the petrochemical side.</li>
    <li><a href="/blog/how-to-apply-for-pdo-engineering-jobs-in-oman">How to Apply for PDO Engineering Jobs in Oman</a> &mdash; oil and gas engineering where local hiring rules bite hardest.</li>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; where an aerospace occupation code goes instead.</li>
    <li><a href="/blog/how-to-apply-for-airbus-aerospace-jobs-in-france">How to Apply for Airbus Aerospace Jobs in France</a> &mdash; the other half of the same European aerospace market.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the routes that exist once the salary thresholds are taken seriously.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; the digital side of the same employers, at a lower entry bar.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; where an engineering brain goes when it stops touching plant.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the trade route into the same sites and refineries.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a> &mdash; the commercial side of an energy business.</li>
    <li><a href="/blog/how-to-apply-for-barclays-customer-service-jobs-in-the-uk">How to Apply for Barclays Customer Service Jobs in the UK</a> &mdash; a large UK employer that does print a salary on its adverts.</li>
    <li><a href="/blog/how-to-apply-for-network-rail-maintenance-jobs-in-the-uk">How to Apply for Network Rail Maintenance Jobs in the UK</a> &mdash; the medical and drug screening standard, and why the trackside code cannot be sponsored.</li>
    <li><a href="/blog/how-to-apply-for-lloyds-graduate-jobs-in-the-uk">How to Apply for Lloyds Graduate Jobs in the UK</a> &mdash; ten schemes with published salaries, and the sponsorship answer that rules most readers out.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using bp's own careers site for job categories, application steps, early careers programmes, published early careers pay and its recruitment fraud warnings; bp's Q2 2026 Form 6-K and its Castrol press release for the North Sea and Castrol positions; Spelthorne Borough Council coverage for the Sunbury departure; the NZT Power project's own site for the Teesside figures; the Engineering Council's register of licensed institutions for chartership; and the Home Office register of licensed sponsors, the eligible occupations list and Appendix Skilled Occupations for the visa position and going rates. bp vacancies, pay and immigration rules change. Always check the live advert and the official guidance before you commit.</p>
HTML;
    }
}
