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
 * "Plumber Jobs in Australia" — the licensed trade companion to the
 * construction labouring and office assistant guides. Together those three
 * pages answer a question Australians get wrong constantly: which credential
 * travels between states. The White Card does, a working with children check
 * does not, and a plumbing licence does with two specific holes in it.
 *
 * Corrections to the draft:
 *
 * 1. It quotes self-employed plumbers at AUD $80 to $150 an hour without
 *    saying that is a charge-out rate rather than earnings. Materials, van,
 *    insurance, licence fees, your own superannuation and every unbilled hour
 *    come out of it, and unbilled hours are the largest of those.
 *
 * 2. It gives apprentice pay as $45,000 to $60,000 without noting that the
 *    bottom of that is below the national minimum wage and lawfully so,
 *    because award apprentice rates are percentages of the qualified trade
 *    rate. An apprentice checking the wrong number reaches the wrong
 *    conclusion in both directions.
 *
 * 3. It says a licence lets you "work legally" without saying the licence is
 *    issued by a state. Automatic Mutual Recognition covers most of the
 *    country, but Queensland is outside the scheme in both directions, and
 *    gasfitting classes are exempt from it even where it applies.
 *
 * 4. It says gasfitting "is often required" as a separate licence. It is
 *    always a separate authorisation, and it is the class that does not
 *    travel.
 *
 * 5. It says nothing about work rights, on a site read mostly from outside
 *    Australia — and unlike labouring, this is an occupation where the
 *    answer is genuinely positive.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PlumberJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-plumber-jobs.html';

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
        $title = 'Plumber Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'An hourly charge-out rate is not an hourly wage, apprentice pay below the minimum wage is lawful here, and the licence that lets you work in most states stops at the Queensland border and does not cover gasfitting.',
                'content' => $content,
                'featured_image' => 'blogs/plumber-jobs-in-australia.jpg',
                'tags' => 'plumber jobs australia, plumber salary australia, plumbing licence australia, gasfitter jobs, certificate iii in plumbing, apprentice plumber wage, self employed plumber, drainer jobs australia',
                'meta_title' => 'Plumber Jobs in Australia',
                'meta_description' => 'Plumber jobs in Australia: why a charge-out rate is not a wage, which states your licence reaches, and what gasfitting really requires.',
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
            ['name' => 'Australian Plumbing & Mechanical Services Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-plumber-aggregated']
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
                'position' => 'Plumber — Residential, Commercial and Maintenance Services, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Ordinary hours of 38 per week, with on-call rotations on maintenance contracts',
                'language' => 'English',
                // Employed salaries and self-employed charge-out rates are not
                // the same quantity, and quoted figures may or may not include
                // the 12 per cent superannuation guarantee.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Residential, commercial and maintenance plumbing roles with Australian employers. Check which state licence the position needs before applying.',
                'seo_keywords' => 'plumber jobs australia, plumber salary australia, plumbing licence australia, gasfitter jobs, drainer jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Plumbing contractors, builders, facilities managers and utilities across Australia hire licensed plumbers, gasfitters and drainers for residential, commercial and infrastructure work. It is a licensed trade, which means the qualification is only half the requirement &mdash; the other half is a licence issued by the state you intend to work in.</p>

<h3>What the work involves</h3>
<p>Installing and repairing water, waste and drainage systems; fitting fixtures and hot water units; roof and stormwater work; and fault finding on existing installations. Commercial and industrial work adds larger pipework and mechanical services; gasfitting is a separately authorised specialisation on top.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>Certificate III in Plumbing</strong>, completed through an apprenticeship of about four years</li>
    <li><strong>A plumbing licence from the relevant state or territory authority</strong> &mdash; this is what makes the work lawful, not the certificate alone</li>
    <li><strong>White Card</strong> construction induction training for site access, recognised across every state and territory</li>
    <li>Working knowledge of the Plumbing Code of Australia and the relevant standards</li>
    <li><strong>A separate gasfitting authorisation</strong> for any gas work &mdash; always separate, never bundled</li>
    <li>Physical fitness, fault-finding ability, and a driver's licence for almost every role</li>
</ul>

<h3>How the pay is quoted</h3>
<ul>
    <li><strong>Employed.</strong> Qualified plumbers are advertised around $70,000 to $90,000, with experienced and licensed roles from $90,000 upward</li>
    <li><strong>Apprentice.</strong> Around $45,000 to $60,000. Award apprentice rates are percentages of the qualified trade rate, so this can lawfully sit below the national minimum wage</li>
    <li><strong>Self-employed.</strong> $80 to $150 an hour is a <strong>charge-out rate</strong>, not earnings. Materials, vehicle, insurance, licence fees, your own superannuation and every unbilled hour come out of it</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check which state licence the role needs, and whether it includes gas.</strong> Automatic Mutual Recognition lets most licences travel between states, but Queensland sits outside the scheme in both directions and gasfitting classes are exempt from it even where it applies.</p>

<p><strong>Note:</strong> pay, licensing requirements, contract type and any sponsorship decision are set by each employer and by state regulators &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Plumbing is one of the best-paid trades in Australia and one of the few where the shortage story is genuinely true rather than marketing. It also has the most misread number of any trade guide on this site, and a licensing rule with two holes in it that decide where you can actually work.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-plumber-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128295; Browse Plumber Jobs in Australia &rarr;
    </a>
</div>

<h2>$80 to $150 an Hour Is a Charge-Out Rate, Not a Wage</h2>

<p>Every guide to this trade puts these two lines near each other:</p>

<ul>
    <li>Qualified plumber, employed &mdash; <strong>$70,000 to $90,000</strong> a year.</li>
    <li>Self-employed contractor &mdash; <strong>$80 to $150 an hour</strong>.</li>
</ul>

<p>Read together they say something dramatic. $100 an hour across a 38-hour week and 48 working weeks is $182,400, against $80,000 employed. Nobody says it out loud, but the comparison is doing the work.</p>

<p>It is wrong, and the reason is not that the rate is fake. The rate is real. It is a <strong>charge-out rate</strong> &mdash; what the customer is billed &mdash; and it is not an hourly wage.</p>

<p>Four things come out of it before anything reaches you:</p>

<p><strong>Unbilled hours, which are the big one.</strong> Quoting, travelling between jobs, ordering and collecting materials, invoicing, chasing payment and the job that fell through. A self-employed plumber working a 45-hour week may bill 25 to 30 of those hours. At $100 an hour that is $2,500 to $3,000 of turnover, not $4,500.</p>

<p><strong>Costs.</strong> Vehicle and fuel, tools, materials you carry, public liability insurance, licence and registration fees, accounting.</p>

<p><strong>Superannuation.</strong> As an employee, 12 per cent goes into your fund on top of your salary and someone else administers it. Self-employed, nobody pays it for you. Matching an $80,000 salary means finding roughly $9,600 a year for your own fund out of the same turnover.</p>

<p><strong>Every day you do not work.</strong> No annual leave, no sick leave, no public holidays paid.</p>

<p>None of that is an argument against going out on your own &mdash; plenty of plumbers should and do. It is an argument against comparing a charge-out rate with a salary. <strong>Work out your realistic billable hours first</strong>, subtract the costs, and then compare. The honest comparison is usually much closer than the headline, and it turns on how good you are at filling the diary rather than on the rate.</p>

<h2>Apprentice Pay Below the Minimum Wage Is Lawful Here</h2>

<p>Apprentice plumbers are quoted at <strong>$45,000 to $60,000</strong> a year. Check the bottom of that against the floor and something looks wrong.</p>

<p>The <strong>National Minimum Wage from 1 July 2026 is $26.44 an hour</strong>, or $1,004.90 a week, which is about <strong>$52,255</strong> across a full-time year. So $45,000 works out at roughly <strong>$22.77 an hour</strong> &mdash; well below it.</p>

<p>That is not an error and it is not underpayment. <strong>Award apprentice rates are set as percentages of the qualified tradesperson's rate</strong>, rising each year of the apprenticeship, and they legitimately sit below the national minimum wage in the early years. It is the trade-off at the heart of an apprenticeship: you are paid less because you are being trained, and the qualification at the end is worth considerably more than the difference.</p>

<p>The practical consequence is a specific one. <strong>Do not check your apprentice pay against the national minimum wage &mdash; check it against the award percentage for your year and your qualification stage.</strong> Those percentages step up on anniversaries and on completed training milestones, and an increase you are entitled to does not always arrive by itself. That is the check worth doing every year, and almost nobody does it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-jobs-in-australia-licence.jpg"
         alt="A licensed plumber installing pipework on an Australian residential site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Your Licence Travels &mdash; Except to Queensland, and Except for Gas</h2>

<p>Guides say plumbing is a licensed trade and that you need "a plumbing license, issued by the relevant state or territory licensing authority". True, and it leaves the important question unanswered: what happens when you move?</p>

<p>Australia has <strong>Automatic Mutual Recognition</strong>, a national scheme that lets you use your home state licence to work in other participating states without applying for a second one. For a mobile trade that is genuinely useful. But it has two holes, and they are both in places that matter.</p>

<p><strong>Queensland is outside the scheme, in both directions.</strong> AMR operates in every state and territory except Queensland. A practitioner whose home registration is Queensland cannot use AMR to work elsewhere under it, and practitioners from other states cannot work in Queensland under it either. Given that Queensland is one of the strongest markets in the country for plumbers, this is not a footnote &mdash; if Brisbane or the Gold Coast is your plan, budget for a Queensland licence application rather than assuming your existing one carries.</p>

<p><strong>Gasfitting classes are exempt from AMR.</strong> Even where AMR applies, the gas-related registration classes &mdash; gasfitting, Type A and Type B work, and several mechanical and fire protection classes &mdash; are carved out of it. So the part of your ticket that earns the most is the part least likely to travel.</p>

<p>It is worth seeing this next to the other two Australian credentials covered on this site, because they behave three different ways:</p>

<ul>
    <li><strong>The White Card is recognised everywhere.</strong> One card, every state and territory &mdash; covered in our <a href="/blog/construction-worker-jobs-in-australia">construction worker jobs in Australia guide</a>.</li>
    <li><strong>A working with children check does not transfer at all</strong>, and Queensland refuses interstate checks outright &mdash; covered in our <a href="/blog/office-assistant-jobs-in-australia">office assistant jobs in Australia guide</a>.</li>
    <li><strong>A plumbing licence travels under AMR</strong>, with Queensland and gasfitting carved out.</li>
</ul>

<p>Three credentials, three different answers, and the assumption that they behave the same way is what costs people start dates.</p>

<h2>Gasfitting Is Always Separate, Not "Often"</h2>

<p>Guides describe gasfitting as something where "a separate gasfitting license is often required". Drop the "often". Gas work carries its own authorisation, and doing it without one is not a paperwork problem &mdash; it is the kind of unlicensed work that ends careers and, occasionally, worse.</p>

<p>Two things follow. Gasfitting is one of the clearest ways to raise your rate inside the trade, because it is a genuinely restricted class rather than a training badge. And it is, as above, the class most likely to need a fresh application when you cross a border. Add it for the earnings, and plan the paperwork if you move.</p>

<h2>Is Superannuation Inside the Salary?</h2>

<p>Briefly, because it applies to every Australian job and is covered at length elsewhere on this site: the <strong>Superannuation Guarantee is 12 per cent</strong>, and Australian advertisements are inconsistent about whether a quoted figure includes it. On an $80,000 figure that is <strong>$9,600</strong>.</p>

<p>Ask whether the rate is "plus super" or "inclusive of super" before comparing two offers. The <a href="/blog/construction-worker-jobs-in-australia">construction worker guide</a> works through why that question is worth more than the differences between states.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/plumber-jobs-in-australia-selfemployed.jpg"
         alt="A self-employed plumber loading tools into a work van in Australia"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Coming from Outside Australia: This One Is Different</h2>

<p>Most of the manual and entry-level work covered on this site has the same disappointing answer on migration. Plumbing does not, and it is worth being clear about why.</p>

<p><strong>Plumbing is a skilled trade occupation.</strong> Unlike general labouring, which has no employer-sponsored permanent route at all, licensed trades sit inside Australia's skilled migration framework and inside employer sponsorship. That makes it one of the genuinely realistic ways to move to Australia permanently for someone without a degree.</p>

<p>What it requires, in order:</p>

<ul>
    <li><strong>A formal skills assessment</strong> of your overseas qualifications and experience against the Australian standard, carried out by the designated trades assessing authority. This is a substantial process and often includes practical assessment, so start it early.</li>
    <li><strong>A visa</strong>, through skilled migration or employer sponsorship.</li>
    <li><strong>A state licence, separately, after you arrive.</strong> This is the step people miss. The skills assessment satisfies the migration system; it does not license you. The state regulator makes its own decision.</li>
</ul>

<p>Budget time and money for all three, and treat anyone who tells you a visa alone lets you work as a plumber as not knowing the system. If you are comparing routes, our <a href="/blog/construction-worker-jobs-in-australia">construction worker guide</a> covers the working holiday alternative, and the <a href="/blog/electrician-jobs-in-uk">UK electrician guide</a> covers the same trade question in Britain.</p>

<h2>The Roles You Will Actually Meet</h2>

<ul>
    <li><strong>Residential plumber.</strong> Installation and repair in homes. The largest slice, and the one most exposed to housing cycles.</li>
    <li><strong>Commercial plumber.</strong> Offices, retail and commercial buildings, usually on contractor-run sites with White Card access.</li>
    <li><strong>Industrial plumber.</strong> Large-scale pipework and plant. Higher rates, more shutdown and shift work.</li>
    <li><strong>Gasfitter.</strong> Separately authorised, better paid, and the class least likely to transfer between states.</li>
    <li><strong>Drainer.</strong> Stormwater and sewer. Steady civil and council work.</li>
    <li><strong>Maintenance plumber.</strong> Facilities and property management contracts, with an on-call rotation. The most stable employed option.</li>
</ul>

<h2>Where the Work Is</h2>

<ul>
    <li><strong>New South Wales.</strong> The largest market, driven by construction and infrastructure around Sydney.</li>
    <li><strong>Victoria.</strong> Strong residential and commercial volume around Melbourne.</li>
    <li><strong>Queensland.</strong> Booming housing and population growth &mdash; and the state your interstate licence will not carry you into.</li>
    <li><strong>Western Australia.</strong> Resource-linked infrastructure alongside residential construction.</li>
    <li><strong>South Australia.</strong> Steady residential and commercial demand at a much lower cost of living.</li>
    <li><strong>Regional areas.</strong> Often the strongest demand of all, because the shortage bites hardest where there are fewest tradespeople.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do plumbers earn in Australia?</h3>
<p>Qualified plumbers are advertised around $70,000 to $90,000, with experienced and licensed roles from $90,000 upward. Self-employed figures of $80 to $150 an hour are charge-out rates rather than earnings.</p>

<h3>Why is a charge-out rate not the same as a wage?</h3>
<p>Because unbilled hours, materials, vehicle, insurance, licence fees and your own superannuation all come out of it. A 45-hour week might contain 25 to 30 billable hours, which changes the arithmetic completely.</p>

<h3>Is apprentice pay of $45,000 legal if the minimum wage is higher?</h3>
<p>Yes. Award apprentice rates are percentages of the qualified trade rate and lawfully sit below the national minimum wage of $26.44 an hour in the early years. Check your award percentage for your year rather than the minimum wage.</p>

<h3>Does my plumbing licence work in another Australian state?</h3>
<p>Usually, through Automatic Mutual Recognition &mdash; with two exceptions. Queensland is outside the scheme in both directions, and gasfitting classes are exempt from AMR even where it applies.</p>

<h3>Can I work in Queensland on an interstate plumbing licence?</h3>
<p>Not under Automatic Mutual Recognition. Queensland does not participate, so plan for a Queensland application rather than assuming your existing licence carries.</p>

<h3>Do I need a separate licence for gas work?</h3>
<p>Always, not "often". Gasfitting is a separately authorised class, it pays better for exactly that reason, and it is the part of your ticket least likely to transfer interstate.</p>

<h3>Does the advertised salary include superannuation?</h3>
<p>Australian advertisements split both ways, so ask. At the 12 per cent guarantee rate it is worth $9,600 on an $80,000 figure.</p>

<h3>Can I migrate to Australia as a plumber?</h3>
<p>Yes &mdash; unlike general labouring, this is a skilled trade inside the migration framework. It needs a formal skills assessment against the Australian standard, then a visa, then a separate state licence after arrival. The assessment does not license you.</p>

<h2>People Also Search For</h2>

<h3>Plumber salary Australia</h3>
<p>Around $70,000 to $90,000 employed. Treat self-employed hourly figures as charge-out rates.</p>

<h3>Plumbing licence Australia</h3>
<p>Issued by each state. Automatic Mutual Recognition covers most moves, with Queensland and gasfitting carved out.</p>

<h3>Apprentice plumber wage Australia</h3>
<p>Set as a percentage of the qualified trade rate, and lawfully below the national minimum wage in the early years.</p>

<h3>Gasfitter jobs Australia</h3>
<p>Separately authorised and better paid, and the class most likely to need a fresh application interstate.</p>

<h3>Certificate III in Plumbing</h3>
<p>The qualification, completed over about four years. It is not the licence &mdash; the state regulator issues that separately.</p>

<h3>Self employed plumber hourly rate</h3>
<p>$80 to $150 charged out. Work out billable hours and costs before comparing it with a salary.</p>

<h3>Plumber jobs Brisbane and Gold Coast</h3>
<p>Strong demand, and the market your interstate licence will not carry you into under AMR.</p>

<h3>Plumber skilled migration Australia</h3>
<p>A genuine route. Skills assessment first, then visa, then a separate state licence on arrival.</p>

<h2>More Job Guides</h2>

<p>Comparing trades and routes? These cover them:</p>

<ul>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the award floor, the casual loading, and the credential that does travel everywhere.</li>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; the Australian check that does not transfer between states at all.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; the same licensed-trade question under British rules, with a certification deadline attached.</li>
    <li><a href="/blog/delivery-driver-jobs-in-uk">Delivery Driver Jobs in UK</a> &mdash; the same self-employed arithmetic without the qualification barrier.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; European industrial work and what its visa routes really require.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the Canadian route, and which of its two programs your passport allows.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; sponsored trade work in the Gulf.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a licensed Gulf role, and the licence that only covers one emirate.</li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a> &mdash; another state-registered occupation in real estate sales, and the entry-level certificate each state names differently.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, migration or financial advice. Award rates, licensing rules, mutual recognition arrangements and visa requirements change, and pay figures on any job board are a moving average rather than a statistic. Confirm the current position with the Fair Work Ombudsman, your state plumbing regulator and the employer's own advertisement before applying.</p>
HTML;
    }
}
