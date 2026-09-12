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
 * "Sales Jobs in Australia" — retail, B2B, telesales and real estate sales,
 * and the third Australian guide on the site alongside the office and
 * construction ones. Sales is the easiest Australian category to be hired
 * into and the easiest to be underpaid in, because it is quoted three
 * incompatible ways: an award wage, a base plus commission, and commission
 * only.
 *
 * Corrections to the draft:
 *
 * 1. It gives retail sales assistants $50,000 to $60,000 a year. A full-time
 *    adult at Level 1 of the General Retail Industry Award earns at least
 *    $1,056.80 a week from 1 July 2026, about $54,953.60 a year, so the floor
 *    is $4,953.60 below the award and below the National Minimum Wage
 *    annualised at $52,254.80.
 *
 * 2. It presents uncapped commission as the norm without saying when pay may
 *    be commission-only. The retail award has no commission-only provision,
 *    the Commercial Sales Award floors commercial travellers at $1,122.80 a
 *    week, and the Real Estate Industry Award allows commission-only pay only
 *    for Level 2+ salespeople aged 21 or over with a written agreement, income
 *    of at least 125 per cent of the award rate and at least 31.5 per cent of
 *    the agency's gross commission.
 *
 * 3. It says real estate needs "a Certificate of Registration or Real Estate
 *    License depending on the state". The entry level is a registration in
 *    every state covered, under a different name and with different training.
 *
 * 4. It says nothing about moving between states. Queensland is outside
 *    Automatic Mutual Recognition, and Victoria does not accept interstate
 *    salesperson registrations for agent's representative work.
 *
 * 5. It is silent on visas for an international readership. Technical and
 *    ICT sales representatives, retail buyers and retail managers are on the
 *    Core Skills Occupation List; sales assistants, retail supervisors and
 *    real estate agents and representatives are not.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SalesJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-sales-jobs.html';

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
        $title = 'Sales Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The $50,000 retail floor quoted in most guides is below the legal minimum for full-time work, commission-only pay is lawful in far fewer sales jobs than advertised, and real estate registration is a different certificate in every state.',
                'content' => $content,
                'featured_image' => 'blogs/sales-jobs-in-australia.jpg',
                'tags' => 'sales jobs australia, retail sales assistant jobs, real estate sales jobs, business development jobs australia, sales representative jobs, commission only jobs australia, retail award pay rates, sales jobs sydney',
                'meta_title' => 'Sales Jobs in Australia: Pay, Commission and Licences',
                'meta_description' => 'Sales jobs in Australia: the award floor under retail pay, when commission-only is legal, and the real estate registration each state requires.',
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
            ['name' => 'Australian Retailers, Agencies & B2B Sales Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-sales-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );

        Job::updateOrCreate(
            [
                'position' => 'Sales Consultant — Retail, B2B, Telesales and Real Estate, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Retail rosters including weekends, or business hours for B2B and real estate roles',
                'language' => 'English',
                // Retail pay is set by award level, B2B pay by base plus
                // commission and some real estate pay by commission alone,
                // so no single band would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Retail, B2B, telesales and real estate sales roles with Australian employers. Check the award rate and whether commission is on top of a wage.',
                'seo_keywords' => 'sales jobs australia, retail sales assistant jobs, business development jobs australia, real estate sales jobs, commission only jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Retailers, real estate agencies, software and telecommunications companies, wholesalers, insurers and call centres across Australia hire sales staff continuously. It is one of the few Australian job families with genuine entry points at every level, from a weekend retail roster to an account executive role carrying a quota.</p>

<h3>What the work involves</h3>
<p>In retail: serving customers, processing sales, handling stock and meeting store targets. In B2B and telesales: prospecting, booking meetings, running a CRM pipeline and closing deals. In real estate: listing appraisals, open homes, negotiating with buyers and managing vendor expectations.</p>

<h3>Requirements</h3>
<ul>
    <li>Strong spoken English and comfort with rejection</li>
    <li>No formal qualification for most retail and telesales roles</li>
    <li>CRM experience, such as Salesforce or HubSpot, for most B2B roles</li>
    <li>A driver licence for territory, field and real estate roles</li>
    <li>For real estate sales, your state's entry-level registration &mdash; such as an Assistant Agent certificate in NSW or an agent's representative in Victoria</li>
    <li>Full working rights in Australia, stated on most listings</li>
</ul>

<h3>How the pay is quoted</h3>
<ul>
    <li><strong>Retail roles follow the General Retail Industry Award</strong> &mdash; Level 1 is $1,056.80 a week, or $27.81 an hour, from 1 July 2026, with a 25 per cent casual loading</li>
    <li><strong>Field sales representatives</strong> covered by the Commercial Sales Award cannot be paid less than $1,122.80 a week, however much of the pay is commission</li>
    <li><strong>Commission-only employment in real estate</strong> is limited by its award to experienced salespeople aged 21 or over who meet an income threshold and have a written agreement</li>
    <li><strong>Superannuation is 12 per cent</strong>, and advertisements split on whether it is inside the quoted figure or on top of it</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask whether the base is an award wage, whether commission is on top of it, and whether super is included.</strong> A role described as commission-only needs a closer look: an employee's pay cannot fall below the minimum rate for the job however much of it is commission.</p>

<p><strong>Note:</strong> pay, commission structures, registration requirements and eligibility are set by each employer, the relevant award and state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Sales is the easiest job category in Australia to get hired into and one of the easiest to be underpaid in. The reason is that sales pay is quoted in three incompatible ways &mdash; an award wage, a base salary plus commission, and commission alone &mdash; and most guides mix all three into one table. This page separates them, corrects the figures that are wrong, and covers the real estate registration that every state names differently.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-sales-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Sales Jobs in Australia &rarr;
    </a>
</div>

<h2>The $50,000 Retail Floor Is Below the Legal Minimum</h2>

<p>The figure you will see most often for this job is <strong>"Retail Sales Assistant: AUD $50,000 to $60,000 per year"</strong>. The bottom of that range is not a low offer. For a full-time adult employee, it is an unlawful one.</p>

<p>Australia's <strong>National Minimum Wage from 1 July 2026 is $26.44 an hour</strong>, or $1,004.90 for a 38-hour week. Across a full-time year that is <strong>$52,254.80</strong>. A $50,000 salary is <strong>$2,254.80 short</strong> of it, which works out at about <strong>$25.30 an hour</strong> &mdash; $1.14 below the legal floor.</p>

<p>And the national minimum is not what retail staff are paid anyway. Sales assistants are covered by the <strong>General Retail Industry Award 2020</strong>, which sets its own rates above that floor:</p>

<ul>
    <li><strong>Level 1</strong>, where most new sales assistants start: <strong>$1,056.80 a week, or $27.81 an hour</strong>, from 1 July 2026 &mdash; about <strong>$54,953.60</strong> across a full-time year</li>
    <li><strong>Level 3</strong>: $1,097.80 a week, about $57,085.60 a year</li>
    <li><strong>Level 4</strong>: $1,119.10 a week, about $58,193.20 a year</li>
    <li><strong>Level 8</strong>, the top retail classification: $1,291.80 a week, about $67,173.60 a year</li>
</ul>

<p>Award rates rose by <strong>4.75 per cent</strong> from 1 July 2026 after the Fair Work Commission's Annual Wage Review. Against Level 1, a $50,000 full-time salary is <strong>$4,953.60 short</strong>, and even the top of the draft's range, $60,000, is below a full-time Level 5 salary of $60,585.20.</p>

<p>So the bottom of the published range can only describe part-time hours, a junior rate, or a figure that already includes superannuation. If $50,000 is a package with the 12 per cent super inside it, the salary part is only <strong>$44,642.86</strong>. <strong>Anchor on the award level in the contract, and read anything above it as the market.</strong></p>

<h2>Retail Pay Depends on Level, Engagement and the Day You Work</h2>

<p>An annual salary is the least useful way to describe retail pay, because most retail staff are not paid one. What decides your earnings under the award is:</p>

<ul>
    <li><strong>Your classification level.</strong> A new sales assistant usually starts at Level 1; responsibility for keys, stock ordering or supervising others moves you up.</li>
    <li><strong>Whether you are casual.</strong> Casuals receive a <strong>25 per cent loading</strong> instead of paid annual and personal leave &mdash; at Level 1 that is about <strong>$34.76 an hour</strong>.</li>
    <li><strong>When you work.</strong> Evenings, Saturdays, Sundays and public holidays attract penalty rates above the base rate, which is why weekend availability matters so much to retail hiring.</li>
    <li><strong>Your age.</strong> Employees under 21 at Levels 1 to 3 can be paid a junior percentage of the adult rate.</li>
</ul>

<p>A part-time sales assistant working 25 hours over a weekend-heavy roster can earn more per hour than a full-time colleague on weekdays. Compare hourly rates and rosters, not annual figures.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/sales-jobs-in-australia-retail.jpg"
         alt="A retail sales assistant handing a customer a shopping bag at a store counter in Australia"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Commission-Only Is Legal in Far Fewer Sales Jobs Than Advertised</h2>

<p>The usual guide says many sales roles offer "uncapped commission structures". Commission is lawful. <strong>Commission instead of a wage is a different matter</strong>, and it is where most of the underpayment in Australian sales happens.</p>

<p>It depends on which award covers the job, and the three that cover most sales work treat it very differently:</p>

<ul>
    <li><strong>Retail sales staff</strong> &mdash; the General Retail Industry Award has <strong>no commission-only provision</strong>. The minimum rate for your level has to be paid for the hours you work, and commission can only sit on top of it.</li>
    <li><strong>Field and commercial sales representatives</strong> &mdash; the Commercial Sales Award 2020 covers commercial travellers, and says none "will be remunerated solely by commission payment, salary or retainer, that is lower than the minimum rate". From 1 July 2026 that minimum is <strong>$1,122.80 a week</strong>, about $58,385.60 a year. Commission can make up the pay, but the total can never fall below it.</li>
    <li><strong>Real estate salespeople</strong> &mdash; the Real Estate Industry Award 2020 is the only one of the three that sets out true commission-only employment, and only under strict conditions.</li>
</ul>

<p>Under the Real Estate Industry Award, a salesperson can only be paid commission-only if <strong>all</strong> of these apply:</p>

<ul>
    <li>They are <strong>at least 21</strong> and work at <strong>Level 2 or above</strong> &mdash; never as a Level 1 associate, trainee, junior, part-time or casual employee</li>
    <li>They hold a real estate licence or registration, and have worked <strong>at least 12 consecutive months</strong> at Level 2 or above in the past three years, or run their own real estate business</li>
    <li>There is a <strong>written agreement</strong> setting out how the commission is calculated</li>
    <li>In a 12-month period within the past three years they earned at least <strong>125 per cent of the award rate</strong> for their level, excluding super &mdash; at the Level 2 rate from 1 July 2026 that works out at about <strong>$72,741.50</strong></li>
    <li>Their share is at least <strong>31.5 per cent of the agency's gross commission</strong> on their sales</li>
</ul>

<p>Earnings are reviewed every year, and if they fall below that 125 per cent threshold the commission-only arrangement <strong>must end</strong>. A new salesperson fresh from registration training cannot be put on commission-only at all.</p>

<p>The other place commission-only pay appears is <strong>independent contracting</strong>. But a role with a roster, a manager, a uniform and targets set by someone else is not independent contracting just because the paperwork says so. Since <strong>26 August 2024</strong> the Fair Work Act has required the real working relationship to be assessed as a whole, rather than the label in the contract, and disguising an employee as a contractor is <strong>sham contracting</strong>, which the Act prohibits.</p>

<h2>Uncapped Commission Also Means Uncapped Downside</h2>

<p>An uncapped commission plan is only as good as the target it is measured against. Before accepting one, get answers to these in writing:</p>

<ul>
    <li><strong>What is the on-target earnings figure, and what share of the current team actually reached it last year?</strong> A generous plan nobody hits is a low salary.</li>
    <li><strong>How is the pay split between base and variable?</strong> A 50/50 split carries twice the risk of an 80/20 one at the same headline figure.</li>
    <li><strong>When is commission earned</strong> &mdash; on signature, on payment, or after a cancellation period &mdash; and what happens to commission on deals that close after you leave?</li>
    <li><strong>What can be clawed back?</strong> An employer cannot simply deduct clawed-back commission from your wages: under <strong>section 324 of the Fair Work Act</strong>, deductions generally need your written authorisation and must be principally for your benefit.</li>
</ul>

<h2>Real Estate: A Different Certificate in Every State</h2>

<p>The draft line most readers rely on is "a Certificate of Registration or Real Estate License is required, depending on the state". It gets the choice wrong: a new salesperson needs a registration, not a licence, and that registration has a different name, a different regulator and different training in each state.</p>

<p>In every state below, the entry-level role is a <strong>registration</strong> that lets you sell under a licensed agent. The full <strong>licence</strong> is what the agent in charge of the agency holds, and it comes later.</p>

<ul>
    <li><strong>New South Wales</strong> &mdash; an <strong>Assistant Agent certificate of registration</strong>, requiring five core units (CPPREP4001 to CPPREP4005) of the CPP41419 Certificate IV in Real Estate Practice. It lasts four years and cannot be renewed, so holders must progress to a Class 2 licence, and they work under a Class 1 licensee in charge.</li>
    <li><strong>Victoria</strong> &mdash; an <strong>agent's representative</strong>. You must be 18 or over and complete 18 units of CPP41419. There is no application to Consumer Affairs Victoria: the employing agent checks your eligibility and notifies the regulator.</li>
    <li><strong>Queensland</strong> &mdash; a <strong>real estate salesperson registration</strong>, requiring 12 units of CPP41419. Salespeople can only work as employees.</li>
    <li><strong>Western Australia</strong> &mdash; a <strong>sales representative registration</strong>, requiring 13 units of CPP41419 for sales only or 18 for unrestricted registration, plus a police check dated within three months.</li>
    <li><strong>South Australia</strong> &mdash; a <strong>registered sales representative</strong> with Consumer and Business Services, requiring 19 units. You can register under supervision while enrolled, with the training expected to be completed within 12 months.</li>
</ul>

<p><strong>Your registration may not cross the border.</strong> Automatic Mutual Recognition lets many occupational registrations work in other states, but real estate sales has two gaps that matter. <strong>Queensland is not part of the scheme</strong>, and <strong>Victoria does not accept interstate salesperson registrations</strong> for agent's representative work. If you are moving to either state for a real estate job, plan to meet that state's requirements rather than assuming your registration carries over.</p>

<p><strong>Start the training before you apply.</strong> A candidate who has already completed the required units can be registered and selling far sooner than one who has not started.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/sales-jobs-in-australia-interview.jpg"
         alt="A candidate shaking hands with an interviewer at a sales job interview in a Sydney office overlooking the harbour"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What the B2B and Sales Manager Figures Are Based On</h2>

<p>The draft gives <strong>$60,000 to $85,000 plus commission</strong> for business development representatives and <strong>$90,000 to $130,000 or more</strong> for sales managers. Those are advertised ranges from job boards, not measured earnings, and they do not say whether super is included.</p>

<p>The most recent official measure is the ABS Employee Earnings survey for <strong>August 2025</strong>, which counts part-time as well as full-time employees. Median weekly earnings were <strong>$742</strong> for sales workers and <strong>$631</strong> for sales assistants and salespersons, against <strong>$1,425</strong> for all employees. We could not find an official measured figure for business development representatives or sales managers, so treat both ranges as what employers advertise rather than what people earn.</p>

<p>There is one official floor for field sales. Commercial travellers and advertising sales representatives covered by the Commercial Sales Award cannot be paid less than <strong>$1,122.80 a week</strong> from 1 July 2026, about <strong>$58,385.60</strong> a year &mdash; so a field sales offer starting at $60,000 is only just above the award minimum.</p>

<p>The superannuation question matters more at these salaries. At 12 per cent it is worth <strong>$10,200</strong> on an $85,000 figure and <strong>$15,600</strong> on a $130,000 one. Ask whether the base is "plus super" or a total package before you compare two offers.</p>

<h2>Visa Sponsorship for Sales Jobs</h2>

<p>The main employer-sponsored route is the <strong>Skills in Demand visa</strong>, and its Core Skills stream only covers occupations on the <strong>Core Skills Occupation List</strong>. Sales splits cleanly in two on that list.</p>

<p><strong>On the list:</strong></p>

<ul>
    <li>Sales Representative (Industrial Products) &mdash; ANZSCO 225411</li>
    <li>Sales Representative (Medical and Pharmaceutical Products) &mdash; 225412</li>
    <li>ICT Sales Representative (225213), ICT Account Manager (225211) and ICT Business Development Manager (225212)</li>
    <li>Technical Sales Representatives nec &mdash; 225499</li>
    <li>Retail Buyer (639211), Retail Manager (General) (142111) and Sales and Marketing Manager (131112)</li>
</ul>

<p><strong>Not on the list:</strong> Sales Assistant (General) 621111, Retail Supervisor 621511, Real Estate Agent 612114 and Real Estate Representative 612115. The retail, telesales and real estate jobs that make up most sales hiring cannot be sponsored through the Core Skills stream.</p>

<p>The listed roles are technical or senior, and the salary also has to meet an income threshold &mdash; the Core Skills Income Threshold was <strong>$76,515</strong> from 1 July 2025. Check the current figure with the Department of Home Affairs before relying on it.</p>

<p>For most readers the realistic route into sales is a <strong>Working Holiday visa</strong>. Its condition 8547 limits you to <strong>six months with one employer</strong> unless you have permission. Since 1 January 2024 some work is exempt &mdash; including working for the same employer at different locations, for no more than six months at each, and work in critical sectors such as tourism and hospitality, health, aged and disability care, childcare, agriculture and food processing. An ordinary shop or office sales job is not in those sectors, so plan to move on, or to change stores within a chain, at the six-month mark.</p>

<h2>Search Under the Titles Employers Actually Use</h2>

<p>"Sales" on its own returns a mix of everything. These are the titles that separate the markets:</p>

<ul>
    <li><strong>Retail Assistant</strong>, <strong>Sales Assistant</strong> and <strong>Store Associate</strong> &mdash; award-covered shop-floor roles</li>
    <li><strong>Sales Development Representative</strong> and <strong>Business Development Representative</strong> &mdash; entry-level B2B prospecting</li>
    <li><strong>Account Executive</strong> &mdash; the closing role, usually with a quota</li>
    <li><strong>Account Manager</strong> &mdash; growing existing clients rather than finding new ones</li>
    <li><strong>Telesales</strong>, <strong>Inside Sales</strong> and <strong>Outbound Sales</strong> &mdash; phone-based selling</li>
    <li><strong>Sales Associate</strong>, <strong>Property Sales Associate</strong> and <strong>Assistant Agent</strong> &mdash; the entry level in real estate</li>
</ul>

<h2>What Actually Gets You Shortlisted</h2>

<ul>
    <li><strong>Numbers from any previous job.</strong> Sales managers read r&eacute;sum&eacute;s for targets, conversion rates and average order values, even from retail or hospitality.</li>
    <li><strong>Weekend availability</strong> for retail, stated plainly. It decides more retail hires than experience does.</li>
    <li><strong>The CRM you have used</strong>, named, rather than "familiar with sales software".</li>
    <li><strong>Real estate training already underway</strong>, for agency roles.</li>
    <li><strong>A clear answer on rejection.</strong> Every sales interview asks how you handle it; prepare a real example rather than a slogan.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do retail sales assistants earn in Australia?</h3>
<p>Under the General Retail Industry Award, a full-time adult at Level 1 earns at least $1,056.80 a week from 1 July 2026, about $54,953.60 a year, plus 12 per cent super. Casuals at Level 1 get about $34.76 an hour including the 25 per cent loading.</p>

<h3>Is $50,000 a legal salary for full-time retail work?</h3>
<p>No. The National Minimum Wage from 1 July 2026 annualises to $52,254.80 for a full-time adult, and the General Retail Industry Award sets higher rates still. $50,000 only fits part-time, junior or super-inclusive figures.</p>

<h3>Can a sales job in Australia be commission-only?</h3>
<p>Rarely, for employees. Retail staff must get their award rate with commission on top, commercial travellers cannot be paid below $1,122.80 a week, and real estate commission-only pay needs Level 2 or above, age 21, a written agreement and income of at least 125 per cent of the award rate.</p>

<h3>Do I need a licence to sell real estate in Australia?</h3>
<p>Yes. Entry-level salespeople need a state registration rather than a full licence &mdash; an Assistant Agent certificate in NSW, an agent's representative in Victoria, or a salesperson or sales representative registration in Queensland, Western Australia and South Australia &mdash; each with its own required training units.</p>

<h3>Does a real estate registration from one state work in another?</h3>
<p>Not always. Automatic Mutual Recognition covers many registrations, but Queensland is outside the scheme and Victoria does not accept interstate salesperson registrations for agent's representative work, so check the destination state's rules first.</p>

<h3>Does the advertised sales salary include superannuation?</h3>
<p>Australian advertisements split both ways, so ask. At the 12 per cent guarantee rate the difference is worth $10,200 on an $85,000 figure and $15,600 on a $130,000 one.</p>

<h3>Can I get visa sponsorship for a sales job in Australia?</h3>
<p>Only for some roles. Technical and ICT sales representatives, retail buyers, retail managers and sales and marketing managers are on the Core Skills Occupation List. Sales assistants, retail supervisors, and real estate agents and representatives are not.</p>

<h3>Do I need experience to get a sales job in Australia?</h3>
<p>Not for most retail and telesales roles. Weekend availability, clear communication and any customer-facing experience matter more at entry level, while B2B roles usually want CRM experience and measurable results.</p>

<h2>People Also Search For</h2>

<h3>Retail sales assistant pay rate Australia</h3>
<p>Level 1 under the General Retail Industry Award is $27.81 an hour from 1 July 2026, or about $34.76 for casuals, with higher penalty rates on Sundays and public holidays.</p>

<h3>Commission only jobs Australia</h3>
<p>Lawful for genuine contractors and, under strict conditions, some real estate employees. For most award-covered employees, the award minimum has to be paid whatever the commission.</p>

<h3>Real estate sales jobs</h3>
<p>Open without experience at assistant level, once the state's entry-level registration and training are complete.</p>

<h3>Business development representative jobs Australia</h3>
<p>The entry point to B2B sales. Advertised ranges are not measured earnings, so ask for the base, the on-target figure and the super treatment.</p>

<h3>Telesales jobs Australia</h3>
<p>Accessible without experience. Check whether the role is employed on an award wage or offered as contracting, because that decides whether commission-only is lawful.</p>

<h3>Sales manager salary Australia</h3>
<p>Commonly advertised at $90,000 to $130,000 or more. At 12 per cent, super alone moves the top of that range by $15,600.</p>

<h3>Sales jobs Sydney and Melbourne</h3>
<p>The largest markets for corporate and real estate sales, with the same award rates as everywhere else in the country.</p>

<h3>Sales jobs for working holiday visa</h3>
<p>A realistic entry route, with a six-month limit per employer. Working at different stores of the same chain, for up to six months at each, is exempt from that limit.</p>

<h2>More Job Guides</h2>

<p>Comparing Australian and international routes? These cover them:</p>

<ul>
    <li><a href="/blog/office-assistant-jobs-in-australia">Office Assistant Jobs in Australia</a> &mdash; administrative work under a different award, and the background check that does not travel between states.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the casual loading and the Working Holiday route in a trade setting.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; another state-issued credential, and where mutual recognition stops.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; the same shop-floor work under American minimum wage law.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; how American retailers actually hire, and what the big chains start you on.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; customer-facing work under provincial wage floors.</li>
    <li><a href="/blog/cashier-jobs-in-usa">Cashier Jobs in USA</a> &mdash; the checkout side of retail, and the till shortage rule.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; phone and chat skills applied from home.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; client-facing work in the Gulf, and who pays for the visa.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; the award floors for first jobs, junior percentages and the 12% super rate.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, migration or financial advice. Award rates, the national minimum wage, superannuation rules, real estate registration requirements and visa occupation lists change, and salary figures on any job board are a moving average rather than a statistic. Confirm the current position with the Fair Work Ombudsman, your state's real estate regulator, the Department of Home Affairs and the employer's own advertisement before applying.</p>
HTML;
    }
}
