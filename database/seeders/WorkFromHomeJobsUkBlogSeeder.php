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
 * "Work From Home Jobs in UK" — the search term where the standard careers
 * advice quotes pay that would be unlawful to offer and tax relief that no
 * longer exists.
 *
 * Corrections to the draft:
 *
 * 1. Its headline pay band is illegal. "Entry-level remote roles typically pay
 *    between GBP 18,000 and GBP 24,000 per year" describes full-time pay below
 *    the National Living Wage. From 1 April 2026 the rate for workers aged 21
 *    and over is GBP 12.71 an hour, so a 37.5-hour week must pay at least
 *    GBP 24,784.50 a year and a 40-hour week GBP 26,436.80. Even the top of the
 *    quoted band falls short of a full-time wage. Publishing it unchanged would
 *    teach readers to accept underpayment.
 *
 * 2. It never mentions that working from home does not suspend the minimum
 *    wage. HMRC's own manual says home workers are usually entitled to it even
 *    where the supplier of the work calls them self-employed, and there is a
 *    specific statutory method for piece-rate work done at home.
 *
 * 3. It says remote demand "shows no signs of slowing down". The ONS describes
 *    hybrid working as having risen gradually since 2022 and settled at around
 *    28 per cent of workers, which is a plateau, not an acceleration.
 *
 * 4. It omits the single most useful right a UK remote worker has. Since
 *    6 April 2024 the request for flexible working is a day-one right, twice a
 *    year, answerable within two months and not refusable without consultation.
 *    It is still only a right to ask.
 *
 * 5. It implies remote work is something employers simply offer. There is no
 *    legal right to work from home in the UK and no statutory right to
 *    disconnect.
 *
 * 6. Any advice to claim the GBP 6 a week working-from-home tax relief is out
 *    of date twice over: it never applied to people who chose to work from
 *    home, and GOV.UK states it cannot be claimed at all for the tax year
 *    6 April 2026 to 5 April 2027.
 *
 * 7. Its scam paragraph is right in spirit and wrong on the detail that
 *    matters. Charging a work-seeker a work-finding fee is a criminal offence
 *    under section 6 of the Employment Agencies Act 1973, and enforcement moved
 *    from the Employment Agency Standards Inspectorate to the Fair Work Agency
 *    on 7 April 2026.
 *
 * 8. It treats every listing as a job. Much home-based work is offered on
 *    self-employed terms, which removes the minimum wage, paid holiday and sick
 *    pay; "worker" status keeps the first two.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class WorkFromHomeJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-work-from-home-jobs.html';

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
        $title = 'Work From Home Jobs in UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The entry-level pay quoted for UK remote work sits below the National Living Wage: at 12.71 GBP an hour a full-time week must pay at least 24,784 GBP a year. Flexible working is a right to ask, not a right to have.',
                'content' => $content,
                'featured_image' => 'blogs/work-from-home-jobs-in-uk.jpg',
                'tags' => 'work from home jobs uk, remote jobs uk, flexible working request, national living wage uk, hybrid working uk, virtual assistant jobs uk, remote customer service uk, work from home scams',
                'meta_title' => 'Work From Home Jobs in UK: Real Pay and Your Rights',
                'meta_description' => 'Work from home jobs in the UK: why an 18,000 GBP full-time salary is unlawful, your day-one right to request flexible working, and how to spot a scam.',
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
            ['name' => 'UK Employers Advertising Home-Based and Hybrid Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-work-from-home-aggregated']
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
                'position' => 'Work From Home — Customer Service, Admin and Support Roles, UK',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Varies by employer; many home-based roles are part-time or shift-based',
                'language' => 'English',
                // "Work from home" spans everything from piece-rate data entry to
                // senior technical roles, and many listings are self-employed
                // contracts rather than jobs, so no single band would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Home-based and hybrid roles with UK employers. Check the advertised hourly rate against the National Living Wage and whether the contract is employment or self-employment.',
                'seo_keywords' => 'work from home jobs uk, remote jobs uk, hybrid jobs uk, home based customer service uk, remote admin jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Employers across the United Kingdom advertise home-based and hybrid roles continuously, in customer service, administration, bookkeeping, technical support, marketing and sales. The listings range from piece-rate data entry to senior professional work, and they are not all offered on the same legal terms.</p>

<h3>What the work involves</h3>
<p>Handling customers by phone, email or live chat; keeping records and diaries; processing data and transactions; supporting colleagues through shared tools such as Teams, Slack or Zoom; and working to targets or deadlines without a supervisor in the room. Most roles expect a reliable connection and a workspace where you can concentrate and keep information confidential.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree for most entry-level roles. Specialist and technical positions set their own qualifications</li>
    <li>Clear written and spoken English, because almost all supervision happens in writing or on a call</li>
    <li>Comfort with everyday digital tools, and the self-discipline to manage your own day</li>
    <li>A workspace suitable for the work, including somewhere to keep personal data secure</li>
    <li>The right to work in the UK, which the employer must check before you start</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Work out two things from the advertisement: the hourly rate, and whether it is a job or a contract.</strong> Divide any annual figure by the hours expected and compare it against the National Living Wage. If the listing signs you up as self-employed, the minimum wage, paid holiday and sick pay do not come with it.</p>

<p><strong>Note:</strong> pay, contract type, equipment and eligibility are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying, and never pay a fee to be found work.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Almost every guide to working from home in the UK quotes the same entry-level salary band. That band describes pay an employer could not lawfully offer for full-time work. This page gives the figures the law actually sets, the rights you have when you ask to work from home, what your employer must and must not pay for, and the one piece of tax advice that has quietly stopped being true.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-work-from-home-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127968; Browse Work From Home Jobs in the UK &rarr;
    </a>
</div>

<h2>The Quoted Entry-Level Pay Is Below the Legal Minimum</h2>

<p>The figure in circulation is <strong>"GBP 18,000 to GBP 24,000 a year"</strong> for entry-level remote work such as customer service or data entry. Set it against the rate the law requires. From <strong>1 April 2026</strong> the National Minimum Wage and National Living Wage rates are:</p>

<ul>
    <li><strong>21 and over (National Living Wage) &mdash; &pound;12.71</strong> an hour</li>
    <li><strong>18 to 20 &mdash; &pound;10.85</strong> an hour</li>
    <li><strong>Under 18 &mdash; &pound;8.00</strong> an hour</li>
    <li><strong>Apprentice &mdash; &pound;8.00</strong> an hour</li>
</ul>

<p>For someone aged 21 or over, that means a full-time year cannot lawfully pay less than:</p>

<ul>
    <li><strong>35 hours a week &mdash; &pound;23,132.20</strong> a year</li>
    <li><strong>37.5 hours a week &mdash; &pound;24,784.50</strong> a year</li>
    <li><strong>40 hours a week &mdash; &pound;26,436.80</strong> a year</li>
</ul>

<p>So <strong>&pound;18,000 a year buys about 1,416 hours, or roughly 27 hours a week</strong>. As a full-time salary it is not low, it is unlawful. And the top of the quoted band fails the same test: <strong>&pound;24,000 covers only about 36 hours a week</strong>, so a 37.5-hour or 40-hour role advertised at that salary is also paying below the minimum. If you see that band attached to full-time hours, the advertisement is the problem, not your expectations.</p>

<p>For context, the ONS Annual Survey of Hours and Earnings found that in <strong>April 2025 the median full-time employee in the UK earned &pound;39,039 a year</strong>, or <strong>&pound;19.67 an hour</strong> excluding overtime. That is the middle of the whole workforce, and it sits comfortably inside the "GBP 30,000 to GBP 50,000" band the same guides quote for specialised remote roles.</p>

<h2>Working From Home Does Not Suspend the Minimum Wage</h2>

<p>This matters because a large share of home-based work is offered by the piece &mdash; so much per record typed, per audio minute transcribed, per item assembled. HMRC's internal manual on home workers is direct: they <strong>are usually entitled to the National Minimum Wage even if the supplier of the work tells them that they are self-employed</strong>.</p>

<p>Where you are genuinely paid per task rather than per hour, the employer must either pay at least the minimum wage for every hour worked, or set a <strong>fair rate</strong> using a statutory method: test how much an average worker produces in an hour, <strong>divide that average by 1.2</strong> so newer workers are not penalised for being slower, then divide the hourly minimum wage by the result. You must be told the piece rate and the assumed hourly output <strong>in writing before you start</strong>. If you are not, you are entitled to the full hourly minimum wage instead.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/work-from-home-jobs-in-uk-home-office.jpg" alt="A UK home worker at a laptop in a home office" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How Much of the UK Actually Works From Home</h2>

<p>The claim that remote demand "shows no signs of slowing down" overstates what the official statistics say. The ONS, using its Opinions and Lifestyle Survey for <strong>8 January to 30 March 2025</strong>, found that <strong>28 per cent of workers in Great Britain were hybrid workers</strong>. Its own description of the trend is that the proportion of hybrid workers has risen while travel-to-work-only has declined &mdash; a gradual shift since 2022 that now looks settled rather than surging.</p>

<p>Where those roles are concentrated matters more to a job search than the headline:</p>

<ul>
    <li><strong>Highest by occupation</strong> &mdash; managers, directors and senior officials; professional; associate professional; and administrative and secretarial roles</li>
    <li><strong>Highest by industry</strong> &mdash; information and communication, and professional, scientific and technical activities</li>
    <li><strong>Lowest</strong> &mdash; retail, construction and hospitality</li>
</ul>

<p>The honest reading is that home working is now normal in office-based and professional work and remains rare everywhere else. If your experience is in retail, care or hospitality, moving to home-based work usually means changing occupation, not just changing employer.</p>

<h2>The Jobs That Are Genuinely Advertised From Home</h2>

<p>These are the role types that appear most consistently in UK home-based listings:</p>

<ul>
    <li><strong>Customer service adviser</strong> &mdash; phone, email or live chat support, often on shifts</li>
    <li><strong>Virtual assistant</strong> &mdash; diary, inbox and administrative support for businesses or executives</li>
    <li><strong>Data entry and transcription</strong> &mdash; frequently paid per piece, so check the fair-rate rules above</li>
    <li><strong>Bookkeeper or accounts assistant</strong> &mdash; ledgers, invoicing and payroll support</li>
    <li><strong>Content writer or copywriter</strong> &mdash; website, blog and marketing copy</li>
    <li><strong>Digital marketing and SEO</strong> &mdash; campaigns, social media and analytics</li>
    <li><strong>IT support technician</strong> &mdash; remote troubleshooting and service desk work</li>
    <li><strong>Online tutor</strong> &mdash; lessons delivered by video call</li>
    <li><strong>Recruitment consultant</strong> &mdash; sourcing and screening candidates</li>
    <li><strong>Inside sales or account management</strong> &mdash; client relationships handled by phone and video</li>
</ul>

<h2>Your Right to Request Flexible Working</h2>

<p>This is the most useful thing a UK employee can know about home working, and it is missing from most guides. Since <strong>6 April 2024</strong> the right to request flexible working is a <strong>day-one right</strong>: the old 26-week service requirement is gone. The mechanics are set out in the Employment Rights Act 1996 and the Acas statutory Code of Practice that took effect the same day:</p>

<ul>
    <li>You may make <strong>two statutory requests in any 12-month period</strong></li>
    <li>Your employer must decide <strong>within two months</strong> of receiving the request, unless you agree a longer period</li>
    <li>Your employer <strong>must consult you before rejecting</strong> the request</li>
    <li>There is <strong>no statutory right of appeal</strong> &mdash; offering one is good practice, not law</li>
</ul>

<p>An employer may refuse only for one of <strong>eight business reasons</strong>: the burden of additional costs; a detrimental effect on meeting customer demand; inability to reorganise work among existing staff; inability to recruit additional staff; a detrimental impact on quality; a detrimental impact on performance; insufficiency of work during the periods you propose to work; or planned structural changes.</p>

<p>Two limits are worth stating plainly. It is a right to <strong>request</strong>, not a right to have &mdash; the government's own wording is that the reforms do not introduce an entitlement to work flexibly. And it belongs to <strong>employees only</strong>. If you are engaged as a worker rather than an employee, the right to request flexible working is not one of yours.</p>

<h2>What the Employment Rights Act 2025 Changes, and When</h2>

<p>The Employment Rights Act 2025 received Royal Assent on <strong>18 December 2025</strong>. Its section 9 would tighten the refusal test: an employer rejecting a request would have to say which of the eight business reasons applies <em>and</em> explain why it considers the refusal reasonable, so the reasonableness can be tested rather than just the sincerity.</p>

<p><strong>That change is not in force.</strong> Section 9 was commenced on 6 January 2026 for one narrow purpose only &mdash; making the regulations &mdash; not as a duty on employers. The government's implementation timeline, updated on <strong>25 August 2026</strong>, lists flexible working among the measures taking effect in <strong>2027</strong>, with no specific date. Anyone telling you today that your employer must already justify a refusal as reasonable is ahead of the law.</p>

<p>Parts of the Act that <em>are</em> already in force include day-one paternity leave and whistleblowing protections from 6 April 2026, the Fair Work Agency from 7 April 2026, and the extension of employment tribunal time limits from three months to six on 1 October 2026.</p>

<h2>There Is No Right to Work From Home</h2>

<p>It is worth saying without hedging, because the phrasing of most articles implies otherwise. UK law gives you a right to <strong>ask</strong> to work from home and to have that request handled properly. It does not give you a right to work from home, and there is <strong>no statutory right to disconnect</strong>. The government's stated intention is to deliver a "right to switch off" through a statutory Code of Practice rather than legislation, which would make it guidance with legal weight rather than an enforceable individual right.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/work-from-home-jobs-in-uk-flexible-hours.jpg" alt="A home worker at a laptop with a pet nearby, illustrating flexible home-based hours" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Employee, Worker or Self-Employed: Why the Label Decides Your Pay</h2>

<p>A great many home-based listings are contracts rather than jobs, and the difference is not cosmetic. GOV.UK recognises three statuses that matter here:</p>

<ul>
    <li><strong>Employee</strong> &mdash; everything a worker gets, plus unfair dismissal protection, statutory redundancy pay, minimum notice, family leave and the right to request flexible working</li>
    <li><strong>Worker</strong> &mdash; the National Minimum Wage, statutory paid holiday, rest breaks, protection from unlawful deductions, discrimination and whistleblowing protection, and no less favourable treatment for part-time work. No unfair dismissal protection, no redundancy pay, no minimum notice, and no right to request flexible working</li>
    <li><strong>Self-employed</strong> &mdash; employment law does not cover you in most cases. <strong>No minimum wage, no paid holiday, no sick pay.</strong> You keep health and safety protection, discrimination protection in some circumstances, and whatever your contract gives you</li>
</ul>

<p>One useful exception sits inside that last line: GOV.UK is explicit that <strong>homeworkers paid by the number of items they make are entitled to the minimum wage</strong>, even though genuinely self-employed people running their own business are not. Being labelled self-employed by whoever sends you the work does not settle the question.</p>

<h2>What Your Employer Must Pay For</h2>

<p>The HSE is unambiguous: an employer has <strong>the same health and safety responsibilities for people working at home as for any other worker</strong>. In practice that means three concrete duties.</p>

<ul>
    <li><strong>Risk assessment.</strong> It must cover home workers, including stress and mental health, use of computers and laptops, and the working environment. The HSE expects a proportionate approach &mdash; self-assessment tools, guidance and video calls, rather than home visits</li>
    <li><strong>Display screen equipment.</strong> The DSE Regulations 1992 apply at home to DSE "users" &mdash; people using screens daily for continuous periods of an hour or more, whether permanently home-based or hybrid. They do not cover occasional short use. Assessment and training are required, and self-assessment is acceptable if you have been trained</li>
    <li><strong>Employers' liability insurance.</strong> Compulsory from the moment someone becomes an employer, with cover of <strong>at least &pound;5 million</strong>. The fine is <strong>&pound;2,500 for every day</strong> uninsured and <strong>&pound;1,000</strong> for failing to display the certificate</li>
</ul>

<p>The decisive sentence on cost is the HSE's own: where a workstation assessment shows you need a piece of DSE equipment, <strong>your workers cannot be charged for this</strong>. What is <em>not</em> a legal requirement is a general home-office allowance or a contribution to your broadband. GOV.UK treats those as benefits that trigger tax and reporting obligations when an employer provides them &mdash; not as duties. So an equipment budget is a perk worth asking about, while a chair or monitor identified by a DSE assessment is something your employer has to provide.</p>

<h2>The Tax Relief Most Guides Still Recommend Is Gone</h2>

<p>The familiar advice is to claim tax relief of &pound;6 a week, or your exact costs, for working from home. Two things are wrong with repeating it now.</p>

<p>First, it never applied to people who <em>chose</em> to work from home. GOV.UK's conditions are that you can claim if you <strong>have to</strong> work from home &mdash; for example because your job requires you to live far from the office, or your employer has no office. You <strong>cannot</strong> claim if your contract merely lets you work from home some or all of the time, or if the office is full when you want to go in. That excludes most hybrid workers.</p>

<p>Second, and more simply: GOV.UK states that <strong>from the tax year 6 April 2026 to 5 April 2027 you will not be able to claim tax relief for working from home</strong>. For the current tax year the relief is unavailable to everyone. Claims for earlier tax years can still be made within the normal time limits, so it is worth checking whether you qualified in a year when you were required to work from home and did not claim.</p>

<h2>Scams, and the Law on Fees</h2>

<p>The rule to carry into every application is simple and it has statutory force. Under <strong>section 6 of the Employment Agencies Act 1973</strong>, an agency must not request or receive a fee from a work-seeker for providing services to find them employment. Breaching it is a <strong>criminal offence</strong>. The only exceptions, set out in regulation 26 of the Conduct of Employment Agencies and Employment Businesses Regulations 2003, are narrow: certain entertainment and modelling occupations where commission comes out of earnings, inclusion in publications, and company work-seekers. There is no exception for ordinary work-finding fees.</p>

<p>Enforcement has changed, and this is where older advice sends people to the wrong place. The <strong>Employment Agency Standards Inspectorate was replaced by the Fair Work Agency on 7 April 2026</strong>. The Fair Work Agency now enforces the 1973 Act and the 2003 Regulations, including agencies charging unlawful fees to work-seekers, and can be reached at contact@fairworkagency.gov.uk or on 0345 161 6000.</p>

<p>A fake job advertisement is a separate matter: report it to <strong>Action Fraud on 0300 123 2040</strong> or through its online tool. The pattern Action Fraud describes is an advert or approach followed by a request for upfront payment for equipment, training or security checks, after which the "employer" disappears. Applied to home-based listings, the practical tests are: you are asked to pay for anything; you are asked for bank details before an offer; the interview happens entirely over text-based chat; or the pay works out below the minimum wage once you divide by the hours.</p>

<h2>Can a Work From Home Job Be Sponsored for a Visa?</h2>

<p>This deserves a straight answer because home-based listings attract a lot of hopeful applications from outside the UK. An employer <strong>must conduct a right to work check before you start</strong>, and the civil penalty for getting it wrong is up to <strong>&pound;60,000 per illegal worker</strong> &mdash; which is why employers will not simply take your word for it.</p>

<p>On sponsorship, the Home Office guidance for sponsors is telling. A sponsor must <strong>report when a worker is, or will be, working remotely from home on a permanent or full-time basis</strong>, with little or no requirement to attend a workplace. Routine hybrid patterns do not need reporting; permanent home-based work does. The Skilled Worker route exists to recruit people to work <em>in the UK</em>, so a fully home-based role is a poor candidate for sponsorship and invites the question of why UK sponsorship is needed at all. Treat any advertisement promising visa sponsorship for a fully remote job with real caution.</p>

<h2>How to Actually Land One</h2>

<ul>
    <li><strong>Convert every salary to an hourly rate</strong> before you apply. Divide the annual figure by the hours expected and compare it against &pound;12.71. This one habit filters out most of the bad listings</li>
    <li><strong>Check the contract type in the advert.</strong> "Self-employed", "freelance" and "contractor" mean no minimum wage, no holiday pay and no sick pay</li>
    <li><strong>Search by occupation, not by the phrase.</strong> Home-based work clusters in IT, professional and administrative roles, so searching those job titles plus "remote" or "hybrid" returns far more than searching "work from home"</li>
    <li><strong>Use the day-one right in your current job.</strong> If you already work somewhere, a properly made flexible working request is often faster than changing employer</li>
    <li><strong>Describe your setup concretely</strong> &mdash; connection, workspace, and how you keep data secure. Employers ask because they carry the health, safety and data duties</li>
    <li><strong>Never pay a fee.</strong> It is a criminal offence to charge you one</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-work-from-home-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; See Current UK Work From Home Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is GBP 18,000 a year legal for a full-time work from home job?</h3>
<p>No, not for an employee aged 21 or over. The National Living Wage is &pound;12.71 an hour from 1 April 2026, so a 37.5-hour week must pay at least &pound;24,784.50 a year. &pound;18,000 covers only about 27 hours a week. Working from home makes no difference to this.</p>

<h3>Do I have the right to work from home in the UK?</h3>
<p>No. You have the right to <em>request</em> flexible working from your first day, twice in any 12-month period, and your employer must consult you and respond within two months. It can still refuse for any of eight business reasons. There is no right to work from home and no statutory right to disconnect.</p>

<h3>Can I still claim the GBP 6 a week working from home tax relief?</h3>
<p>Not for the current tax year. GOV.UK states that from 6 April 2026 to 5 April 2027 you cannot claim tax relief for working from home. Even before that it was limited to people who had to work from home, not those who chose to, which excluded most hybrid workers.</p>

<h3>Does my employer have to pay for my desk, chair or broadband?</h3>
<p>Not as a general allowance. But if a display screen equipment assessment shows you need a particular piece of equipment, the HSE is clear that you cannot be charged for it. Your employer must also include you in its risk assessment and hold at least &pound;5 million of employers' liability insurance.</p>

<h3>Can a work from home job be sponsored for a visa?</h3>
<p>It is a poor candidate. The Skilled Worker route is for people working in the UK, and a sponsor must report to the Home Office when a worker becomes permanently home-based. Employers must also complete a right to work check before you start, with penalties of up to &pound;60,000 per illegal worker.</p>

<h3>What is the difference between an employee, a worker and a self-employed contractor?</h3>
<p>Workers get the minimum wage, paid holiday and rest breaks but not unfair dismissal protection, redundancy pay or the right to request flexible working. Employees get all of it. Self-employed contractors get none of the pay protections, though homeworkers paid per item are still entitled to the minimum wage.</p>

<h3>Is it legal for an agency to charge me a fee to find remote work?</h3>
<p>No. Section 6 of the Employment Agencies Act 1973 makes it a criminal offence to charge a work-seeker a fee for finding them employment, with only narrow exceptions for certain entertainment and modelling work. Report it to the Fair Work Agency, which took over enforcement on 7 April 2026.</p>

<h3>How many people in the UK actually work from home?</h3>
<p>ONS figures for January to March 2025 put hybrid workers at 28 per cent of workers in Great Britain. The proportion has risen gradually since 2022 and now looks settled rather than accelerating, and it is concentrated in information and communication, professional and administrative occupations.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Work from home jobs UK no experience</strong> &mdash; entry-level home-based work exists mainly in customer service and data entry; check the hourly rate against &pound;12.71 before applying</li>
    <li><strong>Part time work from home jobs UK</strong> &mdash; the minimum wage applies per hour, so part-time hours at a lawful rate can legitimately total less than &pound;24,784 a year</li>
    <li><strong>Is working from home a legal right in the UK</strong> &mdash; no; it is a right to request, held by employees from day one since 6 April 2024</li>
    <li><strong>Flexible working request template UK</strong> &mdash; the Acas statutory Code of Practice that took effect on 6 April 2024 sets out what a request must contain</li>
    <li><strong>Work from home tax relief 2026</strong> &mdash; unavailable for the 6 April 2026 to 5 April 2027 tax year</li>
    <li><strong>Work from home job scams UK</strong> &mdash; charging a work-seeker a fee is a criminal offence; report fake adverts to Action Fraud on 0300 123 2040</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a></li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a></li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a></li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a></li>
    <li><a href="/blog/remote-jobs-in-usa">Remote Jobs in USA</a></li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a></li>
</ul>
HTML;
    }
}
