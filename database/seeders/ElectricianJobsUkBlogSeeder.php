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
 * "Electrician Jobs in UK" — the skilled trade counterpart to the delivery
 * driver guide, and the one page in this set with a genuine deadline on it.
 *
 * Corrections to the draft:
 *
 * 1. Its apprentice range of GBP 15,000 to 22,000 sits below the law at both
 *    ends. The apprentice minimum wage rate is GBP 8.00 an hour and applies
 *    only to apprentices under 19 or in their first year; past that, a
 *    21-year-old apprentice is on GBP 12.71, which is GBP 24,784.50 across a
 *    37.5-hour year. The top of the quoted range is below the floor.
 *
 * 2. It names the 18th Edition without the amendment. BS 7671:2018+A4:2026
 *    was published on 15 April 2026 and the previous version is withdrawn on
 *    15 October 2026. A guide dated 2026 that does not say so is sending
 *    readers to certify against a standard that is about to lapse.
 *
 * 3. There is no 19th Edition. A full rewrite is not expected before 2027-28.
 *    Courses advertising one are selling something that does not exist.
 *
 * 4. It says an NVQ and an ECS card are needed "to work legally". Those are
 *    different kinds of requirement: the ECS card is contractual site access,
 *    while the legal regime for domestic work is Part P of the Building
 *    Regulations. Competent Person Scheme membership is not compulsory, but
 *    without it notifiable work has to go to building control with fees and
 *    an inspection.
 *
 * 5. It quotes self-employed day rates with no mention of the Construction
 *    Industry Scheme, which takes 20 per cent at source from a registered
 *    subcontractor and 30 per cent from an unregistered one.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ElectricianJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-electrician-jobs.html';

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
        $title = 'Electrician Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The wiring regulations version most guides name is withdrawn on 15 October 2026, there is no 19th Edition to train for, the quoted apprentice pay is below the legal minimum, and CIS takes 20 per cent of a day rate at source.',
                'content' => $content,
                'featured_image' => 'blogs/electrician-jobs-in-uk.jpg',
                'tags' => 'electrician jobs uk, electrician salary uk, 18th edition amendment 4, part p building regulations, ecs card, ev charging installer jobs, self employed electrician, electrical apprenticeship uk',
                'meta_title' => 'Electrician Jobs in UK',
                'meta_description' => 'Electrician jobs in the UK: the wiring regs amendment that lands in October 2026, what Part P really requires, and what CIS takes from a day rate.',
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
            ['name' => 'UK Electrical Contractors & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-electrician-aggregated']
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
                'position' => 'Electrician — Domestic, Commercial and Industrial Installation, UK Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard site hours, with call-out rotations on maintenance contracts',
                'language' => 'English',
                // Employed salaries and self-employed day rates are quoted in
                // different units, and CIS takes 20 per cent from the second
                // before it reaches the subcontractor.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Domestic, commercial and industrial electrical roles with UK employers. Check which amendment of BS 7671 the position requires before applying.',
                'seo_keywords' => 'electrician jobs uk, electrician salary uk, part p building regulations, ecs card, ev charging installer jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Electrical contractors, housebuilders, facilities management companies and renewable energy installers across the United Kingdom hire electricians for domestic, commercial and industrial work. It is one of the most secure skilled trades in the country, and one where the qualifications have a live expiry problem in 2026 that catches out people who qualified only a couple of years ago.</p>

<h3>What the work involves</h3>
<p>First and second fix installation, rewiring, consumer unit replacement, fault finding, inspection and testing, and increasingly EV charge point and solar or battery storage installation. Maintenance roles add planned upkeep and call-outs; industrial work adds machinery, three-phase systems and shutdowns.</p>

<h3>Requirements</h3>
<ul>
    <li>NVQ Level 3 in Electrical Installation, or an equivalent apprenticeship qualification</li>
    <li>A current <strong>BS 7671</strong> wiring regulations qualification &mdash; check which amendment, because the older version is withdrawn on 15 October 2026</li>
    <li>An <strong>ECS card</strong> for site access, which is a contractual requirement rather than a legal one</li>
    <li>City &amp; Guilds 2391 inspection and testing for anything beyond installation work</li>
    <li>Competent Person Scheme registration if you intend to self-certify domestic notifiable work</li>
    <li>EV charge point or renewable installation credentials for the fastest-growing specialisms</li>
</ul>

<h3>Two different pay structures</h3>
<ul>
    <li><strong>Employed.</strong> Qualified electricians are advertised around &pound;28,000 to &pound;38,000, and experienced or specialist roles from &pound;40,000 upward</li>
    <li><strong>Self-employed.</strong> Day rates of &pound;150 to &pound;300 or more &mdash; before the <strong>Construction Industry Scheme</strong> takes 20 per cent at source from a registered subcontractor, or 30 per cent from an unregistered one</li>
    <li><strong>Apprentice.</strong> The apprentice minimum wage rate applies only under 19 or in the first year; after that the ordinary age rate applies</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the amendment on your wiring regulations certificate before you apply for anything.</strong> BS 7671:2018+A4:2026 was published on 15 April 2026, and the version before it is withdrawn on 15 October 2026. If you are self-employed, work out the day rate after the CIS deduction rather than before it.</p>

<p><strong>Note:</strong> pay, certification requirements, scheme registration and contract type are set by each employer and by regulation &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Electrical work is one of the strongest trades in Britain right now, and this is the one guide on this site with an actual date in it. If you hold a wiring regulations qualification and have not looked at which amendment it covers, that is the most useful thing on this page and it is five weeks away.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-electrician-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9889; Browse Electrician Jobs in the UK &rarr;
    </a>
</div>

<h2>The 18th Edition Is Not One Thing, and Part of It Expires in October</h2>

<p>Every guide to this trade lists the <strong>"18th Edition Wiring Regulations certificate (BS 7671)"</strong> among the requirements and stops there. In 2026 that is not enough information to act on, because the 18th Edition has been amended four times and they are not interchangeable.</p>

<p>Here is the current position:</p>

<ul>
    <li><strong>BS 7671:2018+A4:2026</strong> &mdash; Amendment 4 &mdash; was <strong>published on 15 April 2026</strong>.</li>
    <li>The version before it, <strong>BS 7671:2018+A2:2022+A3:2024</strong>, is <strong>withdrawn on 15 October 2026</strong>.</li>
</ul>

<p>So if your certificate covers Amendment 2 or Amendment 3, the standard behind it stops being current in <strong>five weeks</strong>. That does not invalidate your Level 3 qualification or your experience, and it does not stop you working. What it does is put you on the wrong side of a question that contractors, scheme assessors and clients will start asking, at exactly the moment everyone else is booking the update course.</p>

<p><strong>Book the update before the rush rather than after it.</strong> That is the whole advice, and it is worth more than the rest of this page.</p>

<h2>There Is No 19th Edition. Do Not Pay for One.</h2>

<p>Because Amendment 4 is a substantial change, "19th Edition" courses and study guides have started appearing. <strong>The 19th Edition does not exist.</strong></p>

<p>Amendment 4 is an amendment to the 18th Edition, not a new edition of the regulations. A full rewrite &mdash; a genuine 19th Edition &mdash; is not expected before <strong>2027 or 2028</strong>, and some in the industry expect it closer to 2030.</p>

<p>Treat any training provider selling a 19th Edition course as telling you something useful about the provider. What you actually need is the <strong>Amendment 4 update</strong> to your existing 18th Edition qualification.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-in-uk-qualifications.jpg"
         alt="A qualified electrician testing a consumer unit on a UK domestic installation"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The ECS Card and Part P Are Different Kinds of Requirement</h2>

<p>Guides say you need an NVQ Level 3, an 18th Edition certificate and an ECS card "to work legally on most UK job sites". That sentence blends two things that behave very differently, and the difference decides what work you can take on for yourself.</p>

<p><strong>The ECS card is contractual.</strong> It is the electrotechnical industry's competence card, and principal contractors require it for site access. No card, no gate pass. But it is a condition imposed by the people running the site, not a statute.</p>

<p><strong>Part P of the Building Regulations is the law</strong>, and it applies to electrical work in dwellings in England and Wales. Certain work &mdash; new circuits, consumer unit replacement and similar &mdash; is <strong>notifiable</strong>, and there are only two lawful ways to handle it:</p>

<ul>
    <li><strong>Be registered with a Competent Person Scheme</strong> &mdash; NICEIC, NAPIT and others &mdash; which lets you <strong>self-certify</strong> the work as compliant.</li>
    <li><strong>Notify building control</strong> before the work, and accept the inspection and the fees that come with it.</li>
    </ul>

<p>Here is the part guides never say: <strong>scheme membership is not compulsory.</strong> You can lawfully carry out electrical work without it. What you cannot do is self-certify notifiable domestic work, which in practice makes small domestic jobs uneconomic &mdash; the building control route costs money and time on every single job.</p>

<p>And joining a scheme is more than paperwork. Assessment covers your Level 3 qualifications, a current BS 7671, <strong>calibrated test instruments</strong> and a review of sample installations you have completed. The test gear alone is a real capital cost, and it is the thing newly qualified electricians most often have not budgeted for.</p>

<p>So the honest sequence for someone planning to work for themselves is: qualification, current amendment, test equipment, sample work, then scheme registration. Employment first, self-employment later, is the normal order for good reason.</p>

<h2>The Apprentice Pay Range Is Below the Law at Both Ends</h2>

<p>Guides give apprentice electrician pay as <strong>&pound;15,000 to &pound;22,000 a year</strong>. Both ends of that need checking against the minimum wage, because the apprentice rate is narrower than people assume.</p>

<p>From 1 April 2026 the rates are <strong>&pound;12.71</strong> for workers aged 21 and over, <strong>&pound;10.85</strong> for 18 to 20, and <strong>&pound;8.00</strong> for the apprentice rate. The apprentice rate applies <strong>only to apprentices under 19, or in the first year of their apprenticeship</strong>. After the first year, an apprentice aged 19 or over moves onto the ordinary rate for their age.</p>

<p>Run the arithmetic on a 37.5-hour week:</p>

<ul>
    <li>At the apprentice rate of &pound;8.00, a full year is <strong>&pound;15,600</strong>. The quoted floor of &pound;15,000 is <strong>below</strong> that.</li>
    <li>A 21-year-old apprentice past their first year is entitled to &pound;12.71, which is <strong>&pound;24,784.50</strong> a year. The quoted ceiling of &pound;22,000 is <strong>&pound;2,784.50 short</strong> of it.</li>
</ul>

<p>So a published apprentice range that runs from &pound;15,000 to &pound;22,000 does not describe a lawful full-time apprenticeship for anyone past year one and over 20. If you are being offered something in that band, <strong>check your age and your apprenticeship year against the rate you are entitled to</strong>, because the entitlement changes underneath you on an anniversary and nobody sends a reminder.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-in-uk-selfemployed.jpg"
         alt="A self-employed electrician working on a commercial distribution board"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Day Rate Is Not What Reaches You</h2>

<p>Self-employed electricians are quoted at <strong>&pound;150 to &pound;300 or more a day</strong>, which sits attractively against an employed salary of &pound;28,000 to &pound;38,000. There is a deduction between the two that guides never mention.</p>

<p>Electrical work for a contractor in construction falls under the <strong>Construction Industry Scheme</strong>. The contractor deducts tax from your payment <strong>at source</strong>, before it reaches you:</p>

<ul>
    <li><strong>20 per cent</strong> if you are registered with HMRC as a subcontractor.</li>
    <li><strong>30 per cent</strong> if you are not registered.</li>
    <li><strong>0 per cent</strong> if you hold gross payment status, which has its own qualifying conditions.</li>
</ul>

<p>The deduction applies to the <strong>labour portion only</strong> &mdash; materials you have bought for the job are taken out of the calculation first, which is a good reason to itemise them properly on every invoice.</p>

<p>So a &pound;250 day at 20 per cent is <strong>&pound;200</strong> reaching your account on the labour element, and at 30 per cent it is <strong>&pound;175</strong>. It is not lost money &mdash; it counts against your tax bill and is frequently refunded &mdash; but it is not available to you during the year, and registering rather than staying unregistered is worth ten percentage points of cash flow for one form.</p>

<p>Add public liability insurance, tools, the van, calibrated test equipment and unpaid days, and the comparison with an employed salary looks different again. The same arithmetic runs through every self-employed trade in Britain &mdash; our <a href="/blog/delivery-driver-jobs-in-uk">delivery driver jobs in UK guide</a> works through the version of it that catches out couriers.</p>

<h2>Where the Growth Actually Is</h2>

<p>The demand story in these guides is real, and two of the drivers are worth taking more seriously than the rest.</p>

<p><strong>EV charge point installation</strong> is the fastest-growing specialism in the trade, and it is a genuine differentiator on a CV rather than a nice-to-have. It carries its own qualification route on top of your Level 3.</p>

<p><strong>Solar, battery storage and retrofit</strong> work is the other, and it is where the domestic market is heading as older housing stock is upgraded. Both are additive to a standard installation qualification rather than alternatives to it.</p>

<p>Inspection and testing &mdash; <strong>City &amp; Guilds 2391</strong> &mdash; is the least glamorous and possibly the highest-return addition. Periodic inspection work is steady, it is required by law in the rental sector, and it is not going anywhere.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Domestic electrician.</strong> Wiring, rewiring and consumer units. The area where Part P and scheme registration matter most.</li>
    <li><strong>Commercial electrician.</strong> Offices, retail and commercial buildings. ECS card territory, and usually contractor-run sites.</li>
    <li><strong>Industrial electrician.</strong> Machinery, three-phase and plant. Higher rates, shutdown work, and more specialised.</li>
    <li><strong>Maintenance electrician.</strong> Facilities upkeep with a call-out rotation. The most stable employed option.</li>
    <li><strong>EV charge point installer.</strong> The fastest-growing specialism, with its own credential on top of Level 3.</li>
    <li><strong>Electrical contractor or supervisor.</strong> Running projects and teams, and the route most self-employed electricians end up on.</li>
</ul>

<h2>A Note on Coming from Outside the UK</h2>

<p>Unlike most entry-level work covered on this site, electrical installation is a genuinely skilled occupation, so the sponsorship question has a real answer rather than a flat no. It is still not simple: an overseas electrical qualification has to be assessed against UK requirements, and BS 7671 is specific to Britain, so expect to sit the wiring regulations qualification here regardless of what you hold.</p>

<p>The practical route for most people is to establish the right to work first and the qualifications second, in that order. Our <a href="/blog/healthcare-jobs-in-the-uk">healthcare jobs in the UK guide</a> covers how the sponsored route works in the sector where it is most available, and the same mechanics apply.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is my 18th Edition certificate still valid in 2026?</h3>
<p>Your qualification stands, but check the amendment. BS 7671:2018+A4:2026 was published on 15 April 2026, and the previous version, A2:2022+A3:2024, is withdrawn on 15 October 2026. Book the Amendment 4 update rather than waiting.</p>

<h3>Is there a 19th Edition of the wiring regulations?</h3>
<p>No. Amendment 4 is an amendment to the 18th Edition, not a new edition. A full rewrite is not expected before 2027 or 2028. Courses advertising a 19th Edition are selling something that does not exist.</p>

<h3>Do I legally need an ECS card to work as an electrician?</h3>
<p>No. The ECS card is a contractual requirement imposed by principal contractors for site access, not a legal one. The legal regime for domestic work is Part P of the Building Regulations.</p>

<h3>Do I have to join NICEIC or NAPIT?</h3>
<p>No, scheme membership is not compulsory. But without it you cannot self-certify notifiable domestic work, and every such job has to be notified to building control with inspection and fees, which makes small domestic work uneconomic.</p>

<h3>What does joining a Competent Person Scheme require?</h3>
<p>Your Level 3 qualifications, a current BS 7671, calibrated test instruments and a review of sample installations you have completed. The test equipment is a real capital cost that newly qualified electricians often have not budgeted for.</p>

<h3>How much do electricians earn in the UK?</h3>
<p>Qualified electricians are advertised around &pound;28,000 to &pound;38,000, with experienced and specialist roles from &pound;40,000 upward. Self-employed day rates of &pound;150 to &pound;300 are quoted before CIS deductions.</p>

<h3>What is CIS and how much does it take?</h3>
<p>The Construction Industry Scheme. A contractor deducts 20 per cent at source from a registered subcontractor, 30 per cent from an unregistered one, and nothing from someone with gross payment status. It applies to the labour portion only.</p>

<h3>Is apprentice electrician pay of &pound;15,000 to &pound;22,000 legal?</h3>
<p>Not necessarily. The &pound;8.00 apprentice rate only applies under 19 or in the first year. A 21-year-old past year one is entitled to &pound;12.71 an hour, which is &pound;24,784.50 across a 37.5-hour year &mdash; above the top of that range.</p>

<h2>People Also Search For</h2>

<h3>Electrician salary UK</h3>
<p>Around &pound;28,000 to &pound;38,000 employed. Day rates are quoted before a CIS deduction of 20 or 30 per cent.</p>

<h3>18th Edition Amendment 4</h3>
<p>BS 7671:2018+A4:2026, published 15 April 2026. The previous version is withdrawn on 15 October 2026.</p>

<h3>19th Edition wiring regulations</h3>
<p>Does not exist. A full rewrite is not expected before 2027 or 2028.</p>

<h3>Part P building regulations</h3>
<p>The law for domestic electrical work. Notifiable jobs need either scheme self-certification or a building control notification.</p>

<h3>ECS card application</h3>
<p>Site access, required by contractors rather than by statute. Separate from Part P entirely.</p>

<h3>EV charging point installer jobs UK</h3>
<p>The fastest-growing specialism in the trade, with its own credential on top of Level 3.</p>

<h3>Electrical apprenticeship UK</h3>
<p>Three to four years. Check your rate against your age and apprenticeship year, because the entitlement changes on an anniversary.</p>

<h3>Self employed electrician day rate</h3>
<p>&pound;150 to &pound;300 quoted, less 20 per cent CIS if registered, plus insurance, van, tools and calibrated test gear.</p>

<h2>More Job Guides</h2>

<p>Comparing trades and routes? These cover them:</p>

<ul>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the same self-employed arithmetic in a trade with no qualification barrier.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; why operative-level UK roles fail the sponsorship tests.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; the sector where UK sponsorship is genuinely available.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; site work where a published award sets the legal pay floor.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; skilled and semi-skilled industrial work in the EU.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the Canadian route, and which of its two programs you can use.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; sponsored trade work in the Gulf.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; a UK entry route with no qualification requirement at all.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; the licensed trade question in Australia, where each state issues the licence.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Wage rates, standards, amendment dates, scheme requirements and tax rules change, and pay figures on any job board are a moving average rather than a statistic. Confirm the current position with GOV.UK, the IET, your Competent Person Scheme and HMRC before applying or paying for any course.</p>
HTML;
    }
}
