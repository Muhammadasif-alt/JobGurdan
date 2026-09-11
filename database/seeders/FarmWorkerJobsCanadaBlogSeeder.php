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
 * "Farm Worker Jobs in Canada" — Canadian agricultural work, written for
 * readers outside Canada, because that is who searches this and because the
 * usual draft sends them down a route that is closed to them.
 *
 * Corrections to the draft:
 *
 * 1. Its apply link points at www.indeed.com, the American site, filtered to
 *    Canada. Canadian postings are on ca.indeed.com.
 *
 * 2. It lists the Temporary Foreign Worker Program and the Seasonal
 *    Agricultural Worker Program together as though both were open to
 *    everyone. SAWP runs on bilateral agreements with Mexico and eleven
 *    Caribbean states only. A reader in Pakistan, India, Nigeria or the
 *    Philippines cannot use it at all, and this site's readership is mostly
 *    those countries.
 *
 * 3. It tells foreign applicants to find SAWP work on a job board. SAWP
 *    workers are recruited by their own country's labour ministry under the
 *    bilateral agreement, not by applying to a farm directly.
 *
 * 4. It says to confirm the employer is "authorized to sponsor foreign
 *    workers". Canada has no sponsorship for work permits; the employer needs
 *    a positive Labour Market Impact Assessment. Searching for the wrong word
 *    is how people end up with the wrong agent.
 *
 * 5. It gives pay as CAD $16 to $22 an hour. $16 is below the general
 *    minimum wage in both British Columbia and Ontario, the two provinces it
 *    names first as the largest hiring markets.
 *
 * 6. It describes housing as something "some employers" provide. Under the
 *    Agricultural Stream the employer must produce a housing inspection from
 *    within the last eight months as part of the LMIA.
 *
 * 7. It closes by pointing at a pathway to residency. The Agri-Food Pilot,
 *    the pathway that existed for this work, filled its 1,010 places and
 *    closed ahead of its 14 May 2025 end date.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FarmWorkerJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-farm-worker-jobs.html';

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
        $title = 'Farm Worker Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'One of the two farm work programs is open to twelve countries and cannot be applied for on a job board. Which route is actually yours, why Canada has no sponsor to look for, and what happened to the residency pathway.',
                'content' => $content,
                'featured_image' => 'blogs/farm-worker-jobs-in-canada.jpg',
                'tags' => 'farm worker jobs canada, agricultural jobs canada, sawp canada, seasonal agricultural worker program, lmia farm jobs, fruit picking jobs canada, greenhouse jobs canada, farm jobs with accommodation',
                'meta_title' => 'Farm Worker Jobs in Canada',
                'meta_description' => 'Farm worker jobs in Canada: which of the two programs you can actually use, why there is no sponsor to look for, and what the pay really is.',
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
            ['name' => 'Canadian Farm & Greenhouse Operators (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-farm-worker-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Farm Worker — Field, Greenhouse and Livestock Operations, Canadian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Long shifts through the growing season, with year-round hours in greenhouse and livestock operations',
                'language' => 'English',
                // Pay tracks provincial minimum wages, which range from $15.00
                // in Alberta to $18.25 in British Columbia. A national band
                // would misdescribe most of the country.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Field, greenhouse and livestock roles with Canadian farms. Check which of the two foreign worker programs your nationality can actually use.',
                'seo_keywords' => 'farm worker jobs canada, agricultural jobs canada, sawp canada, lmia farm jobs, greenhouse jobs canada',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Fruit and vegetable growers, greenhouse operators, dairy and poultry farms and grain producers across Canada hire farm workers year round, with large seasonal intakes for planting and harvest. It is one of the most accessible jobs in the country for someone with no qualifications, and one where the route you can use depends entirely on your nationality.</p>

<h3>What the work involves</h3>
<p>Planting, weeding, thinning and harvesting in fields and orchards; pruning, picking and packing in greenhouses; feeding, milking and general animal care on livestock operations; and equipment operation for those with the experience. Peak season means long days, and the weather sets the schedule rather than the calendar.</p>

<h3>Requirements</h3>
<ul>
    <li>Physical stamina and the ability to work outdoors in changing weather</li>
    <li>Willingness to work long hours through the peak of the season</li>
    <li>Basic farm safety awareness; formal education is rarely required</li>
    <li>Crop, livestock or machinery experience is an asset rather than a condition</li>
    <li>For applicants outside Canada: a work permit under the route that fits your nationality, not a "sponsor"</li>
</ul>

<h3>The two foreign worker routes, which are not interchangeable</h3>
<ul>
    <li><strong>Seasonal Agricultural Worker Program.</strong> Bilateral agreements with <strong>Mexico and eleven Caribbean countries only</strong>. Workers are selected by their own country's labour ministry, not through job boards. Maximum <strong>eight months</strong>, between 1 January and 15 December</li>
    <li><strong>Agricultural Stream of the Temporary Foreign Worker Program.</strong> Open to <strong>any nationality</strong>, requires the employer to hold a positive <strong>Labour Market Impact Assessment</strong>, and permits run up to <strong>two years</strong></li>
    <li><strong>Housing is a program requirement</strong> under the Agricultural Stream, with an inspection from within the last eight months filed as part of the LMIA</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Work out which route your passport allows before you spend a week applying.</strong> If your country is not one of the twelve in the seasonal program, that route is closed to you regardless of your experience, and the Agricultural Stream with an LMIA-holding employer is the one to pursue.</p>

<p><strong>Note:</strong> pay, housing arrangements, program eligibility and permit conditions are set by employers and by Canadian law &mdash; not by JobGader. No legitimate employer or agent should charge you a placement fee. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canadian farm work is one of the genuinely open doors into Canada for someone with no degree and no professional background. It is also surrounded by more bad advice than almost any other job on this site, and most of it comes from one place: guides that describe two completely different immigration programs as though they were one option with two names.</p>

<p>They are not, and which one applies to you is decided by your passport before anything else about you is considered.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-farm-worker-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127806; Browse Farm Worker Jobs in Canada &rarr;
    </a>
</div>

<h2>SAWP Is Open to Twelve Countries. That Is the Whole List.</h2>

<p>The <strong>Seasonal Agricultural Worker Program</strong> is the one every guide names, and it runs on <strong>bilateral agreements</strong> between Canada and specific countries. Not "developing countries", not "countries with labour shortages" &mdash; a fixed list, negotiated one at a time.</p>

<p>The participating countries are <strong>Mexico</strong>, and eleven Caribbean states: <strong>Anguilla, Antigua and Barbuda, Barbados, Dominica, Grenada, Jamaica, Montserrat, St Kitts and Nevis, St Lucia, St Vincent and the Grenadines, and Trinidad and Tobago</strong>.</p>

<p>If your passport is not on that list, <strong>SAWP is closed to you.</strong> Not difficult, not competitive &mdash; closed. No amount of farm experience, no employer willingness, and no agent changes it. For most readers of this site &mdash; Pakistan, India, Bangladesh, Nigeria, the Philippines &mdash; that is the position, and knowing it on day one is worth more than every other paragraph on this page.</p>

<p>There is a second thing about SAWP that guides get wrong even for people who <em>are</em> eligible. <strong>You do not apply for it on a job board.</strong> Under the bilateral agreements, workers are recruited and nominated by <strong>their own country's labour ministry</strong>, which handles the selection and sends workers to Canadian employers. Scrolling job listings looking for a SAWP position is not how anyone gets one. You go through your own government's programme.</p>

<p>For completeness, the terms: applicants must be at least 18, the placement runs a maximum of <strong>eight months between 1 January and 15 December</strong>, and the employer has to be able to offer at least <strong>240 hours of work within six weeks or less</strong>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/farm-worker-jobs-in-canada-sawp.jpg"
         alt="Seasonal farm workers harvesting produce in a Canadian field"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Route That Is Actually Open: the Agricultural Stream</h2>

<p>If SAWP is closed to you, the door that is not is the <strong>Agricultural Stream of the Temporary Foreign Worker Program</strong>. The differences that matter:</p>

<ul>
    <li><strong>It is open to any nationality.</strong> No bilateral agreement required.</li>
    <li><strong>Permits run up to two years</strong>, rather than eight months.</li>
    <li><strong>It is not restricted to the seasonal horticulture calendar</strong>, so it reaches year-round greenhouse, dairy, poultry and livestock work.</li>
    <li><strong>The employer must hold a positive Labour Market Impact Assessment</strong> for the position before you can be hired into it.</li>
</ul>

<p>That last point is the entire game, and it is where the language in most guides misleads.</p>

<h2>There Is No "Sponsor" in Canada. There Is an LMIA.</h2>

<p>Guides tell foreign applicants to "confirm the employer is authorized to sponsor foreign workers". Canada does not work that way, and the word does real damage.</p>

<p>Canada has <strong>no employer sponsorship for a work permit</strong>. What an employer needs is a <strong>Labour Market Impact Assessment</strong> &mdash; a decision from Employment and Social Development Canada that hiring a foreign worker for that specific position will not harm the Canadian labour market. The LMIA attaches to the job, the employer applies for it, and your work permit application follows from it.</p>

<p>Two practical consequences.</p>

<p><strong>Search for the right word.</strong> "Farm jobs in Canada with sponsorship" is not a category that exists. <strong>"LMIA approved"</strong> is, and it is what employers and recruiters actually write when they mean it.</p>

<p><strong>It is a filter against fraud.</strong> Anyone promising you a Canadian farm job without an LMIA-holding employer is either confused or selling something. And a point that should not need saying but does: <strong>a legitimate employer or recruiter does not charge you a placement fee.</strong> If money is moving from you to whoever is offering the job, stop.</p>

<p>One more thing worth understanding before you accept: a permit issued this way is normally <strong>employer-specific</strong>. It ties you to the named employer, so leaving means applying again rather than walking to the next farm. That is the single most important structural fact about the arrangement, and it is why the housing and pay questions below deserve to be settled in writing before you travel.</p>

<h2>The Pay Range Is Below the Legal Minimum in Two Provinces</h2>

<p>Guides give farm worker pay as <strong>CAD $16 to $22 an hour</strong>. Check the bottom of that against the law.</p>

<ul>
    <li><strong>British Columbia &mdash; $18.25</strong> an hour from 1 June 2026.</li>
    <li><strong>Ontario &mdash; $17.95</strong> an hour from 1 October 2026.</li>
    <li><strong>Alberta &mdash; $15.00</strong> an hour, unchanged since 2018.</li>
</ul>

<p><strong>$16 an hour is below the general minimum wage in both British Columbia and Ontario</strong> &mdash; and those are the first two provinces the same guides name as the biggest hiring markets, for berries, orchards, vineyards and greenhouses.</p>

<p>So the published range is not telling you what a low offer looks like. It is telling you the data is old, or that it is averaging across provinces with a <strong>$3.25 spread</strong> in their legal floors. Look up the minimum wage for the province the farm is in, and read the offer against that number rather than against a national range. The same logic runs through every entry-level Canadian job &mdash; our <a href="/blog/customer-service-jobs-in-canada">customer service jobs in Canada guide</a> works through it for indoor work.</p>

<h2>Housing Is Not a Perk. It Is a Program Requirement.</h2>

<p>Guides list housing among the things "some employers, especially those hiring seasonal or foreign workers, provide", in the same breath as meals and transport. That framing invites you to be grateful for it and not to ask questions about it.</p>

<p>Under the <strong>Agricultural Stream</strong>, where employer-provided accommodation applies, the employer must supply <strong>proof that the housing was inspected within the last eight months</strong> as part of the LMIA application. It is a condition of the employer being allowed to hire you, not a favour.</p>

<p>Which means you are entitled to ask, and should:</p>

<ul>
    <li><strong>Has the accommodation been inspected, and when?</strong> There is a document, and it is recent by requirement.</li>
    <li><strong>Is any amount deducted from my pay for it, and how much?</strong> Get the figure before you travel, not after.</li>
    <li><strong>How many people share the room, and how far is it from the fields?</strong></li>
    <li><strong>Is transport to the work site provided or deducted?</strong></li>
</ul>

<p>Asking these does not make you a difficult candidate. It makes you a candidate who has read the rules.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/farm-worker-jobs-in-canada-greenhouse.jpg"
         alt="A worker tending plants inside a Canadian commercial greenhouse"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Residency Pathway These Guides Point At Is Closed</h2>

<p>Almost every guide to this work ends the same way: farm work is a route to "longer-term employment or residency programs". It is worth being precise about what that referred to, because vagueness here is exactly what predatory agents sell against.</p>

<p>The programme in question was the <strong>Agri-Food Pilot</strong>, launched in 2020 as a permanent residence pathway for workers in greenhouse and nursery production, mushroom production, animal production and meat processing.</p>

<p><strong>It is closed.</strong> The pilot had <strong>1,010 places</strong>, and they filled before its scheduled end date of <strong>14 May 2025</strong>. Applications submitted before 13 February 2025 continue to be processed; nothing new is being accepted. Workers in this sector who did not apply in time have to look at other permanent residence routes entirely.</p>

<p>So take farm work for what it currently is: a real job, a real work permit, real Canadian work experience and real earnings. Those are worth having. But if someone tells you it comes with a residency pathway attached, ask them to name the programme &mdash; and if they name the Agri-Food Pilot, you now know more about it than they do.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>General farm labourer.</strong> Planting, weeding, harvesting and field maintenance. The largest category and the most seasonal.</li>
    <li><strong>Fruit and vegetable picker.</strong> Harvest work in Ontario, British Columbia and Quebec, often paid partly by volume.</li>
    <li><strong>Greenhouse worker.</strong> Planting, pruning and packing in a controlled environment &mdash; and, importantly, work that runs year round rather than for one season.</li>
    <li><strong>Livestock and dairy worker.</strong> Feeding, milking and animal care. Year-round hours and earlier starts.</li>
    <li><strong>Farm equipment operator.</strong> Tractors and combines. Pays above general labouring and screens on real experience.</li>
    <li><strong>Farm supervisor or foreman.</strong> Crew coordination, usually reached from years on the same operation.</li>
</ul>

<p>If a permit that is not tied to one employer matters more to you than Canada specifically, the working holiday route into <a href="/blog/construction-worker-jobs-in-australia">Australian construction work</a> is worth comparing &mdash; open work rights, and seasonal and regional work counts towards extending the visa.</p>

<h2>Where the Work Is</h2>

<ul>
    <li><strong>Ontario</strong> &mdash; the largest greenhouse sector in the country, plus fruit and vegetable farms. Minimum wage $17.95.</li>
    <li><strong>British Columbia</strong> &mdash; berries, orchards and vineyards. Highest general minimum wage of the major farming provinces at $18.25.</li>
    <li><strong>Quebec</strong> &mdash; dairy and vegetable production. Functional French is a genuine advantage here rather than a formality.</li>
    <li><strong>Alberta</strong> &mdash; grain, cattle and mixed farming, against the lowest minimum wage in the country.</li>
    <li><strong>Manitoba and Saskatchewan</strong> &mdash; large-scale grain and crop production, more machinery and fewer hands.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can anyone apply for the Seasonal Agricultural Worker Program?</h3>
<p>No. SAWP runs on bilateral agreements with Mexico and eleven Caribbean countries: Anguilla, Antigua and Barbuda, Barbados, Dominica, Grenada, Jamaica, Montserrat, St Kitts and Nevis, St Lucia, St Vincent and the Grenadines, and Trinidad and Tobago. Other nationalities cannot use it.</p>

<h3>How do I apply for SAWP if I am eligible?</h3>
<p>Through your own country's labour ministry, which recruits and nominates workers under the bilateral agreement. You do not find SAWP positions on job boards and apply directly to the farm.</p>

<h3>Which program can I use if my country is not on the SAWP list?</h3>
<p>The Agricultural Stream of the Temporary Foreign Worker Program. It is open to any nationality, permits run up to two years, and it requires the employer to hold a positive Labour Market Impact Assessment for the position.</p>

<h3>How do I find an employer who can sponsor me?</h3>
<p>Canada has no sponsorship for work permits. What you are looking for is an employer with a positive LMIA. Search for "LMIA approved" rather than "sponsorship", and never pay a placement fee to anyone.</p>

<h3>How much do farm workers earn in Canada?</h3>
<p>Commonly quoted as CAD $16 to $22 an hour, but $16 is below the general minimum wage in both British Columbia ($18.25) and Ontario ($17.95). Check the province's rate and read the offer against that.</p>

<h3>Is housing provided for farm workers in Canada?</h3>
<p>Where employer-provided accommodation applies under the Agricultural Stream, the employer must file proof of an inspection carried out within the last eight months as part of the LMIA. Ask what is deducted for it before you travel.</p>

<h3>Does farm work lead to permanent residence in Canada?</h3>
<p>The Agri-Food Pilot was the pathway for this sector and it is closed. Its 1,010 places filled before the scheduled end date of 14 May 2025, and only applications submitted before 13 February 2025 are still being processed.</p>

<h3>Is farm work in Canada seasonal or year round?</h3>
<p>Both. Field harvesting is seasonal, while greenhouse, dairy, poultry and livestock operations run all year &mdash; and those year-round roles suit the Agricultural Stream's longer permits better than the eight-month seasonal programme.</p>

<h2>People Also Search For</h2>

<h3>SAWP Canada</h3>
<p>Open to Mexico and eleven Caribbean countries only, and recruited through the sending country's labour ministry rather than job boards.</p>

<h3>LMIA approved farm jobs Canada</h3>
<p>The right search term. An employer with a positive LMIA is what a work permit application needs.</p>

<h3>Farm jobs in Canada with visa sponsorship</h3>
<p>Not a category that exists in Canadian immigration. The mechanism is the LMIA, not sponsorship.</p>

<h3>Fruit picking jobs Canada</h3>
<p>Seasonal harvest work in Ontario, British Columbia and Quebec, often with a volume component in the pay.</p>

<h3>Greenhouse jobs Canada</h3>
<p>Year-round rather than seasonal, which makes it a better fit for the Agricultural Stream's longer permits.</p>

<h3>Farm jobs in Canada with accommodation</h3>
<p>Housing is a program requirement rather than a perk. Ask for the inspection date and the deduction.</p>

<h3>Agri-Food Pilot Canada</h3>
<p>Closed. All 1,010 places filled before the 14 May 2025 end date, with only pre-13 February 2025 applications still processing.</p>

<h3>Farm worker salary Canada per hour</h3>
<p>Judge it against the province's minimum wage, which ranges from $15.00 in Alberta to $18.25 in British Columbia.</p>

<h2>More Job Guides</h2>

<p>Comparing routes into Canada and elsewhere? These cover them:</p>

<ul>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; indoor Canadian work, and how the federal hiring preference really ranks you.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-canada">ATS Resume Writer Jobs in Canada</a> &mdash; how Canadian applicant tracking systems read what you send.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; open work rights on a working holiday, and seasonal work that extends the visa.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; the European industrial route and what its visa options actually require.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why operative-level roles fail the British sponsorship tests.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest American position, including the seasonal visa categories.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a Gulf route where sponsorship genuinely exists, and what to check in the package.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; sponsored manual work, and how the contract is structured.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a skilled trade route into Canada, and why most welder LMIAs are low-wage.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Program eligibility, participating country lists, minimum wages and permanent residence pathways change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with Immigration, Refugees and Citizenship Canada, Employment and Social Development Canada and the employer's own advertisement before applying or paying any fee.</p>
HTML;
    }
}
