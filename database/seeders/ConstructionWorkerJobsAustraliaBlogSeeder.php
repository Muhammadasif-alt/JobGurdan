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
 * "Construction Worker Jobs in Australia" — the labouring and civil market,
 * written around the fact that Australia publishes a legally binding minimum
 * for this exact job. Once the award rate is on the page, the quoted average
 * stops looking like a market signal and starts looking like what it is.
 *
 * Corrections to the draft:
 *
 * 1. It reports $71,059 as the average construction labourer salary without
 *    saying it is a casual figure. The Building and Construction General
 *    On-site Award sets a CW1 base of $26.67 an hour from 1 July 2026, which
 *    is $52,700 a year permanent full-time and $65,880 with the 25 per cent
 *    casual loading. The quoted average is a casual rate compared against
 *    salaried jobs it is not comparable to.
 *
 * 2. It never says whether the figures include superannuation. At the 12 per
 *    cent guarantee rate that is $7,613 on $71,059, which is larger than the
 *    entire spread between the states it lists.
 *
 * 3. It lists state averages $3,344 apart as though they guided relocation.
 *    That spread is smaller than the cost-of-living gap between the cities
 *    involved, and the highest number belongs to the cheapest capital.
 *
 * 4. It lists the White Card among requested skills. It is a legal condition
 *    of entering a site, it is recognised in every state and territory, and
 *    it lapses after two consecutive years without construction work.
 *
 * 5. It says nothing about work rights, on a site aimed at international
 *    readers. General labouring is not a skilled migration occupation; the
 *    realistic route is a Working Holiday visa, where construction in
 *    regional Australia is specified work for a second or third year.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ConstructionWorkerJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-construction-worker-jobs.html';

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
        $title = 'Construction Worker Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What the award actually sets as the legal minimum for a labourer, why the quoted average is a casual rate that cannot be compared with a salary, whether superannuation is inside the number, and how the White Card really works.',
                'content' => $content,
                'featured_image' => 'blogs/construction-worker-jobs-in-australia.jpg',
                'tags' => 'construction jobs australia, construction labourer jobs, white card australia, civil construction jobs, labour hire jobs australia, construction labourer salary, working holiday visa construction, tradesman jobs australia',
                'meta_title' => 'Construction Worker Jobs in Australia',
                'meta_description' => 'Construction worker jobs in Australia: the award minimum behind the quoted average, the casual loading trade-off, and what the White Card really requires.',
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
            ['name' => 'Australian Construction & Labour Hire Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-construction-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Construction Labourer — Residential, Civil and Commercial Sites, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Ordinary hours of 38 per week, with early starts and overtime common on site',
                'language' => 'English',
                // Permanent and casual rates for the same classification differ
                // by the 25 per cent loading, and quoted figures may or may not
                // include the 12 per cent superannuation guarantee.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Labouring and civil construction roles with Australian employers and labour hire firms. Confirm casual or permanent, and whether super is included.',
                'seo_keywords' => 'construction jobs australia, construction labourer jobs, white card australia, labour hire jobs australia, civil construction jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Builders, civil contractors and labour hire firms across Australia hire construction labourers for residential, commercial and infrastructure sites. It is one of the most accessible paid roles in the country for someone with no trade qualification, and one where the pay structure is set by a public legal instrument rather than by negotiation.</p>

<h3>What the work involves</h3>
<p>Site preparation and clean-up, moving and staging materials, assisting trades, traffic and pedestrian control, concrete and formwork support, and operating small plant where you are ticketed for it. Civil work adds roads, rail and utilities, usually with earlier starts and more travel between sites.</p>

<h3>Requirements</h3>
<ul>
    <li>A construction induction card &mdash; the White Card &mdash; before you set foot on site. This is a legal condition, not a preference</li>
    <li>Physical fitness for sustained outdoor work and repeated manual handling</li>
    <li>Steel-capped boots, hard hat, hi-vis and safety glasses, sometimes supplied and sometimes not</li>
    <li>Reliability and an early start, which labour hire firms weight above everything else</li>
    <li>Full working rights in Australia &mdash; almost every listing states this outright</li>
    <li>Tickets for specific tasks where relevant: traffic control, working at heights, confined spaces, forklift</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>The award floor</strong> for a CW1 labourer is <strong>$26.67 an hour</strong> from 1 July 2026, which on 38 ordinary hours is about <strong>$52,700 a year</strong> permanent full-time</li>
    <li><strong>Casual engagement</strong> adds a <strong>25 per cent loading</strong> in place of paid leave &mdash; roughly $33.34 an hour, or about $65,880 across a full year of ordinary hours</li>
    <li><strong>Superannuation is 12 per cent</strong> on top, and whether a quoted figure includes it changes that figure by thousands</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask two questions before you accept: casual or permanent, and is super inside the number or on top of it.</strong> A casual rate looks higher because it is buying out your annual leave, sick leave, notice and redundancy. Neither answer is wrong, but they are different jobs and the difference is worth more than the headline gap between states.</p>

<p><strong>Note:</strong> pay, engagement type, allowances and ticket requirements are set by each employer and by the applicable award or agreement &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Australia is unusual, and unusually helpful, in one respect: for most jobs there is a legally binding minimum rate published by name, and construction labouring is one of them. That single fact changes how you should read every salary figure in every guide to this work &mdash; because once you know the floor, you can see what the average is actually made of.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-construction-worker-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128119; Browse Construction Worker Jobs in Australia &rarr;
    </a>
</div>

<h2>The $71,059 Average Is a Casual Rate</h2>

<p>The figure quoted everywhere for an Australian construction labourer is <strong>$71,059 a year</strong>. Set against the <strong>Building and Construction General On-site Award</strong>, which is public, it stops being mysterious.</p>

<p>From 1 July 2026 the award sets a <strong>CW1 base rate of $26.67 an hour</strong> for the entry classification. On 38 ordinary hours a week that is:</p>

<ul>
    <li><strong>Permanent full-time: about $52,700 a year</strong> at the award minimum, with four weeks of paid annual leave, paid personal and sick leave, notice and redundancy behind it.</li>
    <li><strong>Casual: about $65,880 a year</strong> at the same base plus the <strong>25 per cent casual loading</strong>, with none of those entitlements.</li>
</ul>

<p>The quoted average of $71,059 sits about <strong>8 per cent above the casual minimum</strong> and roughly <strong>35 per cent above the permanent one</strong>. That tells you what the sample is: this is a <strong>casual, labour-hire number</strong>. Which is exactly what you would expect, because labour hire dominates the entry end of this market and the draft guides say so themselves without joining the dots.</p>

<p>The practical consequence is important. If you are comparing a construction labouring job against a salaried role somewhere else, <strong>you are not comparing like with like.</strong> The 25 per cent is not a bonus. It is the price of your annual leave, your sick leave, your notice period and your redundancy entitlement, paid to you weekly instead of held for you. Take the casual role by all means &mdash; plenty of people should &mdash; but budget for the four weeks a year you will not be paid for.</p>

<p>For context on the wider floor: the <strong>National Minimum Wage from 1 July 2026 is $26.44 an hour</strong>, or $1,004.90 a week. The construction award base sits barely above it, which is why the tickets, the allowances and the engagement type matter more here than the base rate does.</p>

<h2>Is Superannuation Inside That Number or On Top of It?</h2>

<p>This is the question that changes an Australian offer most, and no salary guide asks it.</p>

<p>The <strong>Superannuation Guarantee is 12 per cent</strong>. Australian job advertisements are genuinely inconsistent about whether a quoted figure is base pay with super on top, or a total package including it. On $71,059 the difference is <strong>$7,613</strong> &mdash; more than twice the entire spread between the highest and lowest state averages the same guides publish.</p>

<p>So $71,059 means either roughly $71,059 in your hand plus $8,527 into your fund, or roughly $63,446 in your hand with $7,613 going into the fund. Those are two very different jobs wearing the same number.</p>

<p><strong>Ask, in writing, whether the rate is "plus super" or "inclusive of super".</strong> It is a completely standard question in Australia and nobody will find it odd.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/construction-worker-jobs-in-australia-white-card.jpg"
         alt="A construction worker in hard hat and hi-vis on an Australian building site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The White Card Is a Legal Gate, Not a Nice-to-Have</h2>

<p>Guides list the White Card among "skills in demand", between physical fitness and teamwork. That undersells it badly.</p>

<p>The construction induction card is a <strong>legal condition of entering a construction site in Australia</strong> &mdash; for labourers, for trades, and for supervisors, engineers and visitors who go on site routinely. No card, no entry. It is not something to obtain once you have the job; without it, you cannot start.</p>

<p>Two features of it are worth knowing precisely, because they are the ones people get wrong.</p>

<p><strong>It works in every state and territory.</strong> A card issued in one jurisdiction is recognised across Australia. If you are moving between states for work, you do not repeat the course. That is genuinely unusual among Australian work credentials &mdash; the Working with Children Check, for instance, does not transfer the same way &mdash; so it is worth knowing that this one does.</p>

<p><strong>It lapses through disuse, not through time.</strong> The card does not carry a simple expiry date, but it becomes void if you carry out <strong>no construction work for two consecutive years</strong>. Anyone returning to the industry after a break of that length needs to redo it. Check the date of your last construction work before assuming an old card still stands.</p>

<p>The course itself is short and inexpensive, and it is the single highest-return thing an applicant to this industry can do before applying rather than after.</p>

<h2>The State Averages Are Not a Relocation Signal</h2>

<p>Guides list state averages like this: South Australia <strong>$72,415</strong>, Sydney <strong>$70,893</strong>, Victoria <strong>$69,071</strong>. Presented as a ranking, it reads like advice.</p>

<p>Look at the size of it. The whole spread from top to bottom is <strong>$3,344</strong>, about 4.8 per cent. That is smaller than the superannuation question above, smaller than the gap between a casual and a permanent engagement, and far smaller than the difference in what it costs to live in Adelaide versus Sydney.</p>

<p>Notice also which way round it is. The highest average belongs to the <strong>cheapest capital city</strong> on the list, and Sydney &mdash; comfortably the most expensive place to live in the country &mdash; sits below it. In real terms, after housing, the ranking is not close, and it is the opposite of what a "highest paying state" headline implies.</p>

<p>Move for the project, the employer or the tickets you will pick up. Do not move for $3,344.</p>

<h2>Work Rights: The Realistic Route Is a Working Holiday</h2>

<p>Almost every Australian construction listing states that you need full working rights, and for readers outside Australia that is the whole question. Here is the honest position.</p>

<p><strong>General labouring is not a skilled migration occupation.</strong> There is no employer-sponsored permanent route for it, and applying to labour hire firms from overseas asking for sponsorship is not a plan that works.</p>

<p>The route that does work is the <strong>Working Holiday visa</strong>, and construction is treated well by it:</p>

<ul>
    <li>On the <strong>subclass 417</strong>, construction counts as <strong>specified work anywhere in regional Australia</strong>. <strong>88 days</strong> of it during your first year qualifies you for a second-year visa; <strong>179 days</strong> during the second year qualifies you for a third.</li>
    <li>On the <strong>subclass 462</strong>, construction also counts, in Northern Australia or regional Australia, with the same 88-day and 179-day thresholds.</li>
</ul>

<p>That makes regional construction labouring one of the more sensible ways to extend a working holiday &mdash; better paid and more reliably available than the fruit picking it is usually compared with. Check the eligible postcodes for the specific site before you take the job, because the work only counts if the location does. Trade-qualified applicants are in a different conversation entirely, and should be looking at skills assessment and sponsored routes rather than this one.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/construction-worker-jobs-in-australia-casual.jpg"
         alt="A construction supervisor reviewing plans on an Australian civil construction site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Construction labourer.</strong> The entry classification, CW1 on the award. Almost always casual through a labour hire firm at first.</li>
    <li><strong>Civil construction worker.</strong> Roads, rail and utilities. Earlier starts, more travel, and often better rates than residential.</li>
    <li><strong>Skilled labourer.</strong> A higher award classification once you hold the tickets and the site experience to justify it.</li>
    <li><strong>Leading hand.</strong> Running a small crew, with an award allowance attached to the responsibility.</li>
    <li><strong>Trade-qualified carpenter or plumber.</strong> A different market and a different visa conversation, with a licence requirement per state.</li>
    <li><strong>Construction supervisor.</strong> Compliance, coordination and the National Construction Code. The end of the ladder this page describes.</li>
</ul>

<h2>The Tickets That Move Your Rate</h2>

<p>Because the base rate is close to the national floor, the tickets are what separate two labourers on the same site. In rough order of how often listings ask for them:</p>

<ul>
    <li><strong>White Card</strong> &mdash; not a differentiator, a precondition.</li>
    <li><strong>Traffic control and implement traffic management plans</strong> &mdash; widely needed on civil projects.</li>
    <li><strong>Working at heights</strong> and <strong>confined spaces</strong> &mdash; common on commercial and infrastructure sites.</li>
    <li><strong>Forklift licence</strong> and <strong>elevated work platform</strong> tickets &mdash; steady demand across all sectors.</li>
    <li><strong>First aid</strong> &mdash; cheap, quick, and disproportionately useful to a crew.</li>
    <li><strong>Asbestos awareness</strong> &mdash; increasingly asked for on demolition and refurbishment.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do construction labourers earn in Australia?</h3>
<p>The award minimum for a CW1 labourer is $26.67 an hour from 1 July 2026, about $52,700 a year permanent or $65,880 casual with the 25 per cent loading. The commonly quoted $71,059 average is a casual figure.</p>

<h3>What is the casual loading and is it worth taking?</h3>
<p>25 per cent on top of the base rate, paid instead of annual leave, sick leave, notice and redundancy. It is worth taking if you budget for the roughly four weeks a year you will not be paid for.</p>

<h3>Does the quoted salary include superannuation?</h3>
<p>Australian advertisements split both ways, so you have to ask. At the 12 per cent guarantee rate, the answer is worth $7,613 on a $71,059 figure.</p>

<h3>Do I need a White Card to work in construction in Australia?</h3>
<p>Yes. It is a legal condition of entering a construction site, not a preferred qualification, and you need it before you start rather than after you are hired.</p>

<h3>Does a White Card work in another state?</h3>
<p>Yes. It is recognised across every Australian state and territory, so you do not repeat the course when you move for work.</p>

<h3>Does a White Card expire?</h3>
<p>It has no simple expiry date, but it becomes void if you do no construction work for two consecutive years. Anyone returning after a longer break has to redo it.</p>

<h3>Can I get visa sponsorship as a construction labourer in Australia?</h3>
<p>Not realistically. General labouring is not a skilled migration occupation. The Working Holiday visa is the route that works, and construction counts towards it.</p>

<h3>Does construction work count for a second year Working Holiday visa?</h3>
<p>Yes. On the subclass 417 it counts anywhere in regional Australia, and on the 462 in Northern or regional Australia &mdash; 88 days for a second year and 179 days for a third. Check the postcode of the site before you start.</p>

<h2>People Also Search For</h2>

<h3>Construction labourer salary Australia</h3>
<p>An award base of $26.67 an hour, which is about $52,700 permanent and $65,880 casual across a full year.</p>

<h3>White Card Australia</h3>
<p>Mandatory before site entry, valid in every state and territory, void after two consecutive years without construction work.</p>

<h3>Labour hire jobs Australia</h3>
<p>Where most entry-level construction hiring happens, and why the published averages describe casual rates.</p>

<h3>Civil construction jobs Australia</h3>
<p>Roads, rail and utilities. Earlier starts and more travel, usually with better rates than residential work.</p>

<h3>Construction jobs Australia for foreigners</h3>
<p>Working Holiday visa territory. Labouring is not a skilled migration occupation and cannot be sponsored.</p>

<h3>Working holiday visa specified work construction</h3>
<p>Counts in regional Australia on both the 417 and the 462. 88 days for a second year, 179 for a third.</p>

<h3>Construction jobs Sydney and Melbourne</h3>
<p>High volume, and averages within about 4.8 per cent of every other state &mdash; a smaller gap than the cost of living between them.</p>

<h3>No experience construction jobs Australia</h3>
<p>Genuinely available through labour hire. The White Card, boots and a reliable early start matter more than a r&eacute;sum&eacute;.</p>

<h2>More Job Guides</h2>

<p>Comparing markets and entry routes? These cover them:</p>

<ul>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; the indoor Australian entry route, and the checks that do not transfer between states.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the same trade on the American visa system.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the Gulf route, where sponsorship genuinely exists.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the same employed-versus-independent trade-off under British rules.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; industrial work in the EU and what the visa routes really require.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why operative-level roles fail the UK sponsorship tests.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a licensed Gulf role with accommodation and visa included.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest position on American entry-level sponsorship.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the same trade under British certification rules.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; seasonal work in Canada, and which program your passport allows.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; the licensed trade route, and the two places a plumbing licence does not travel.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, migration or financial advice. Award rates, the national minimum wage, superannuation rules and visa conditions change, and posting counts on any job board are a moving figure rather than a statistic. Confirm the current position with the Fair Work Ombudsman, the Department of Home Affairs and the employer's own advertisement before applying.</p>
HTML;
    }
}
