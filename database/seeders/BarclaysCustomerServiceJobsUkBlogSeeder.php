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
 * "How to Apply for Barclays Customer Service Jobs in the UK" — an employer
 * guide whose single most useful fact is one the draft never mentions: these
 * roles cannot be sponsored for a Skilled Worker visa at any salary.
 *
 * Corrections to the draft (checked against search.jobs.barclays, gov.uk and
 * ONS ASHE, 23 September 2026):
 *
 * 1. The draft omits visa eligibility entirely, then quotes Barclays' generic
 *    right-to-work boilerplate about sponsored visas. A reader in Pakistan
 *    would conclude sponsorship is possible. It is not. SOC 2020 codes 7211,
 *    7212, 7213, 7219 and 4123 are all marked Ineligible on the Home Office
 *    eligible-occupations table, and none appears on the Immigration Salary
 *    List or the Temporary Shortage List. Barclays does hold three Skilled
 *    Worker sponsor licences, which is exactly why this trap is easy to fall
 *    into: the licence is real, the occupation is barred.
 *
 * 2. The draft's primary apply link is a job ID that now returns HTTP 404
 *    reading "We are sorry this job post no longer exists." It is cited seven
 *    times and underpins six separate factual claims, all now unsourced. Job
 *    ID links are never published here.
 *
 * 3. "The official customer-service search page recently showed 14 results."
 *    The board returns 15 today, but two are miscategorised non-service roles
 *    and two are Isle of Man, a Crown Dependency outside the UK immigration
 *    system. The honest UK figure is eleven.
 *
 * 4. The draft treats GBP 27,700 as a Newcastle-specific salary. It is the
 *    standard rate across almost the whole board; the only outlier is the
 *    Isle of Wight role at GBP 26,700, and two roles publish nothing.
 *
 * 5. The draft has no independent benchmark. ONS ASHE 2025 puts the median
 *    for customer service occupations at GBP 28,036, so Barclays pays
 *    slightly below the sector median rather than a banking premium.
 *
 * 6. The draft builds an "Apply Now" button around an apprenticeship that
 *    closes on 25 September 2026, two days after it was written, and omits
 *    that the programme carries a three-year UK residence requirement and
 *    bars anyone dependent on a future visa application.
 *
 * 7. The draft says a Glasgow role is "explicitly hybrid" and generalises.
 *    Live postings split cleanly: contact centre and specialist roles are
 *    hybrid, branch roles are face to face in branch.
 *
 * 8. The draft names "Customer Service Associate" as a live role type. No
 *    such title is on the board.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class BarclaysCustomerServiceJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://search.jobs.barclays/uk-customer-care';

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
        $title = 'How to Apply for Barclays Customer Service Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Barclays pays GBP 27,700 for almost every UK customer service role, just under the national median. But the occupation codes are barred from the Skilled Worker visa at any salary, so these jobs are only for people who already hold the right to work.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-barclays-customer-service-jobs-in-the-uk.jpg',
                'tags' => 'barclays jobs, barclays customer service jobs, barclays careers uk, customer service advisor uk, barclays apprenticeship, bank jobs uk, uk contact centre jobs, skilled worker visa',
                'meta_title' => 'Barclays Customer Service Jobs UK: Pay and the Visa Truth',
                'meta_description' => 'Barclays pays GBP 27,700 for UK customer service roles. Why these jobs cannot be sponsored, what is actually live on the board, and where to apply.',
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
            ['name' => 'Barclays Bank UK PLC, Customer Care'],
            ['type' => 'Company', 'display_reference' => 'barclays-uk-customer-care']
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
                'position' => 'Customer Service Advisor, Barclays UK Branch and Contact Centre Roles',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Hybrid',
                'work_hours' => 'Full-time, including weekends; some roles run a 24/7 shift pattern',
                'language' => 'English',
                // Barclays publishes these figures on its own postings, so the
                // band is its own: GBP 26,700 on the Isle of Wight role and
                // GBP 27,700 everywhere else that quotes a salary at all.
                'salary_currency' => 'GBP',
                'salary_period' => 'Year',
                'salary_minimum' => 26700,
                'salary_maximum' => 27700,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Customer service advisor and specialist customer care roles with Barclays across the UK, in branch and in contact centres, for applicants who already hold UK work rights.',
                'seo_keywords' => 'barclays customer service jobs, barclays careers uk, customer service advisor barclays, bank customer service jobs uk',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Barclays hires customer service advisors, senior customer service advisors and specialist customer care advisors across the United Kingdom, in branches and in contact centres, handling banking queries, complaints and mortgage support by phone, chat and face to face.</p>

<h3>What the work involves</h3>
<p>Answering customer questions about accounts and products, investigating and resolving complaints end to end, supporting customers through the mortgage journey, and keeping accurate records inside a regulated process.</p>

<h3>Common requirements</h3>
<ul>
    <li>Barclays states you do not need customer service experience or financial services expertise for many roles</li>
    <li>Specialist telephony and mortgage roles do ask for proven telephony customer service experience</li>
    <li>Availability for the shift pattern in the posting, including weekends and, for some roles, 24/7 rotas</li>
    <li>An existing legal right to work in the United Kingdom</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> salaries, benefits and eligibility are set and published by Barclays, and visa eligibility is set by the UK Home Office &mdash; not by JobGader. Customer service occupation codes are currently ineligible for the Skilled Worker visa. Applying to Barclays is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Apply through Barclays' own Customer Care hub, expect a salary of &pound;27,700 for almost every advisor role, and check one thing before you spend an evening on the form: whether you already have the right to work in the UK.</strong> That last point decides everything, and it is the part every other guide leaves out.</p>

<p>We checked Barclays' live vacancy board, the Home Office sponsor register and the Home Office list of eligible occupations on <strong>23 September 2026</strong>. What follows is what those sources actually say, including where they contradict the advice circulating elsewhere.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://search.jobs.barclays/uk-customer-care" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00aeef;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Open Barclays UK Customer Care &rarr;
    </a>
</div>

<h2>Can Barclays Sponsor You? The Honest Answer Is No</h2>

<p>This is the section to read if you are applying from Pakistan, India, Nigeria or anywhere else outside the UK. It is also the section that is easiest to get wrong, because the first thing you check looks encouraging.</p>

<p><strong>Barclays genuinely is a licensed visa sponsor.</strong> The Home Office publishes its register of licensed sponsors as a downloadable file, and Barclays appears on it three times over &mdash; Barclays Bank PLC, Barclays Bank UK PLC and Barclays Execution Services Limited, all A-rated, all licensed for the Skilled Worker route. So if you search "does Barclays sponsor visas", you will find that it does, and you will assume the door is open.</p>

<p><strong>It is not, and the reason has nothing to do with Barclays.</strong> The Skilled Worker visa works on occupation codes, not employers. A sponsor licence lets a company sponsor roles that qualify; it does not make an unqualifying role qualify. And the customer service codes are all marked <strong>Ineligible</strong> on the Home Office's own occupation table:</p>

<ul>
    <li><strong>7211</strong> Call and contact centre occupations &mdash; Ineligible</li>
    <li><strong>7212</strong> Telephonists &mdash; Ineligible</li>
    <li><strong>7213</strong> Communication operators &mdash; Ineligible</li>
    <li><strong>7219</strong> Customer service occupations not elsewhere classified &mdash; Ineligible</li>
    <li><strong>4123</strong> Bank and post office clerks &mdash; Ineligible</li>
</ul>

<p>That last one covers bank and building society clerks, which is what a branch Personal Banker or Customer Service Advisor is in official classification terms. "Ineligible" is absolute: <strong>it means the role cannot be sponsored at any salary</strong>, however much the employer is willing to pay. These five codes do not even appear in the going-rates table, because there is no going rate for a job that cannot be sponsored.</p>

<p>There are two escape hatches in the system, and neither one helps here. A medium-skilled code can qualify if it is on the <strong>Immigration Salary List</strong> or the <strong>Temporary Shortage List</strong>. We read both lists in full. None of the customer service codes is on either. Since 22 July 2025 the route has required a higher skill level in the first place, with only people extending an older certificate of sponsorship grandfathered in.</p>

<p>And even if the codes were eligible, the money would not work. The Skilled Worker salary floor is the higher of <strong>&pound;41,700 a year</strong> or the going rate for the occupation, with a discounted floor of <strong>&pound;33,400</strong> for new entrants. Barclays pays &pound;27,700 for these roles. That is roughly &pound;14,000 short of the standard threshold and &pound;5,700 short of the discounted one.</p>

<p><strong>So who are these jobs for?</strong> People who already hold the right to work in the UK: British and Irish citizens, people with settled or pre-settled status, dependants on someone else's visa, and graduates on the Graduate route. If that is you, everything below applies. If it is not, your route into the UK runs through an eligible occupation instead &mdash; our guide to <a href="/blog/jobs-in-uk-for-foreigners">jobs in the UK for foreigners</a> covers which categories are actually open, and <a href="/blog/call-center-jobs-in-pakistan">call centre jobs in Pakistan</a> covers the same kind of work without leaving home.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-barclays-customer-service-jobs-in-the-uk-contactcentre.jpg" alt="A Barclays customer service adviser working on a headset in a contact centre" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Contact centre and specialist roles are the hybrid ones. Branch roles are not.</figcaption>
</figure>

<h2>What Is Actually on the Board Today</h2>

<p>Barclays' customer service category returned <strong>15 results</strong> when we checked it. That number is misleading in two directions, and it is worth unpicking because it tells you how to read any employer's job board.</p>

<p>Two of the 15 are <strong>miscategorised</strong> and are not customer service jobs at all: a Fraud Operations Team Manager in Noida, India, and a Structured Products Credit Analyst in New York. Two more are in <strong>Douglas, Isle of Man</strong>, which is a Crown Dependency with its own work permit system and is not the UK for immigration purposes.</p>

<p>Strip those out and <strong>the real number of live UK customer service vacancies is eleven</strong>:</p>

<ul>
    <li>Customer Service Advisor &mdash; Sunderland</li>
    <li>Customer Service Advisor, Personal Banker &mdash; Newcastle upon Tyne</li>
    <li>Senior Customer Service Advisor &mdash; Bury St Edmunds and Ipswich</li>
    <li>Specialist Customer Care, Mortgages &mdash; Glasgow</li>
    <li>Customer Service Advisor &mdash; Newport, Isle of Wight</li>
    <li>2027 Customer Care Apprenticeship &mdash; Greater Manchester</li>
    <li>Senior Customer Service Advisor &mdash; Witney and Oxford</li>
    <li>Specialist Customer Care Advisor, Telephony &mdash; Greater Manchester</li>
    <li>Senior Customer Service Advisor &mdash; Inverness</li>
    <li>Specialist Customer Care Advisor, Telephony &mdash; Glasgow</li>
    <li>Customer Service Advisor, 24/7 shift pattern &mdash; Glasgow</li>
</ul>

<p>One correction worth making explicitly, because it appears in every version of this article online: <strong>there is no live role called "Customer Service Associate."</strong> That title is quoted everywhere as the flagship Barclays customer service job. It is not on the board. The titles Barclays actually uses are Customer Service Advisor, Senior Customer Service Advisor, Customer Service Advisor &ndash; Personal Banker, Specialist Customer Care Advisor, and Specialist Customer Care &ndash; Mortgages.</p>

<h2>Why the Link in Most Guides Is Already Dead</h2>

<p>The article this one replaces linked a Glasgow Customer Service Associate vacancy as its main apply button, seven times over. That link returns <strong>HTTP 404</strong> today. The page reads:</p>

<p style="border-left:4px solid #00aeef;padding-left:16px;margin:20px 0;"><strong>"We are sorry this job post no longer exists."</strong></p>

<p>Two days. That is how long a job-ID link lasted. It is why this site links careers hubs and never individual vacancies, and it is why you should be suspicious of any guide that sends you straight to a numbered posting.</p>

<p>There is a subtler trap on the same site, and it is worse than a 404. Barclays' category pages carry numbers in the URL, like <code>/category/customer-service-jobs/22545/83361/1/1</code>. We tested each segment. The slug is cosmetic. The first number is cosmetic. <strong>Only the facet number is load-bearing</strong> &mdash; and if you change it, the page still returns HTTP 200 and simply shows no jobs. No error, no redirect, no 404. A reader would conclude Barclays is not hiring, and a link checker would never flag it.</p>

<p>So the durable answer is not a clever URL. It is Barclays' <strong>Customer Care hub</strong> for the evergreen description of the roles, the <strong>homepage search box</strong> for live vacancies (type "customer service" and your city), and the <strong>email job alert</strong> Barclays offers on the Customer Care page, which is the only genuinely rot-proof option.</p>

<h2>What Barclays Actually Pays</h2>

<p>Most guides quote &pound;27,700 as if it were specific to the Newcastle Personal Banker role. It is not. It is close to Barclays' standard UK customer service rate, and the board shows it repeatedly:</p>

<ul>
    <li><strong>&pound;27,700</strong> &mdash; Senior Customer Service Advisor in Bury St Edmunds and Ipswich, in Witney and Oxford, and in Inverness</li>
    <li><strong>&pound;27,700</strong> &mdash; Specialist Customer Care Advisor, Telephony, in both Glasgow and Greater Manchester</li>
    <li><strong>&pound;27,700</strong> &mdash; Customer Service Advisor, Personal Banker, Newcastle</li>
    <li><strong>&pound;26,700</strong> &mdash; Customer Service Advisor, Newport, Isle of Wight. The only outlier, and it is &pound;1,000 lower</li>
    <li><strong>No salary published</strong> &mdash; the Sunderland role and the Glasgow 24/7 role</li>
</ul>

<p>The Newcastle posting words it like this: you work five days a week including some weekends, and start on &pound;27,700, plus a core benefits package of a pension plan, private medical insurance, life insurance and income protection.</p>

<p>One oddity to be aware of, because it will confuse you when you read the postings: the Newcastle vacancy is <em>titled</em> "Customer Service Advisor &ndash; Personal Banker" but its body text opens by welcoming you "as a Senior Customer Service Advisor". The two share identical body copy and the same salary. Do not assume they are different grades.</p>

<h2>Is &pound;27,700 Good? Here Is the Honest Benchmark</h2>

<p>An employer's own number means nothing without something to measure it against, so here are two official ones.</p>

<p><strong>Against the sector.</strong> The Office for National Statistics publishes median gross annual pay by occupation in its Annual Survey of Hours and Earnings. For full-time employees in customer service occupations, the 2025 figures are:</p>

<ul>
    <li>Customer service occupations overall &mdash; <strong>&pound;28,036</strong></li>
    <li>Call and contact centre occupations &mdash; <strong>&pound;27,035</strong></li>
    <li>Customer service occupations not elsewhere classified &mdash; <strong>&pound;27,848</strong></li>
</ul>

<p>So Barclays' &pound;27,700 sits <strong>slightly below the overall median</strong> for the sector and slightly above the pure call centre median. It is a normal market rate. It is not a banking premium, and you should not let anyone tell you otherwise.</p>

<p><strong>Against the legal floor.</strong> The National Living Wage is <strong>&pound;12.71 an hour</strong> for workers aged 21 and over from 1 April 2026, with &pound;10.85 for 18 to 20 year olds and &pound;8.00 for under-18s and apprentices. At a 37.5-hour week the adult rate works out near &pound;24,800 a year. Barclays' &pound;27,700 is therefore roughly 12 per cent clear of the minimum you could legally be paid for full-time work.</p>

<p>That is the fair summary: a solid entry-level salary, comfortably above the floor, right on the sector average. If you want to see how that compares across borders, our guides to <a href="/blog/customer-service-jobs-in-usa">customer service jobs in the USA</a> and <a href="/blog/remote-customer-service-jobs">remote customer service jobs</a> give the equivalent numbers.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-barclays-customer-service-jobs-in-the-uk-branch.jpg" alt="A Barclays branch colleague helping a customer at a desk" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Branch roles are described as face to face, not hybrid, whatever the careers hub says.</figcaption>
</figure>

<h2>Which Roles Are Hybrid and Which Are Not</h2>

<p>Barclays' Customer Care hub says its branch network offers "a combination of in-branch and home working". Read the live vacancies and that is not quite what you find. The split is clean, and it runs along a different line than the marketing suggests:</p>

<p><strong>Genuinely hybrid:</strong> the Sunderland role states plainly that it is hybrid and based in Sunderland. The Glasgow 24/7 role lists the Barclays Campus "with hybrid working options". The two telephony specialist roles and the mortgages role carry Barclays' standard hybrid working section.</p>

<p><strong>Not hybrid in any practical sense:</strong> every branch Senior Customer Service Advisor and Personal Banker posting describes you as "the friendly face for our Retail Banking customers in branch or at one of our local hubs". The Isle of Wight role says "face to face guidance and support". You cannot do that from your kitchen.</p>

<p>So the rule of thumb is: <strong>contact centre and specialist roles are hybrid, branch roles are in branch.</strong> Where a posting mentions hybrid working, Barclays generally sets fixed anchor days decided by the business area rather than leaving it to you.</p>

<h2>The Apprenticeship, and Why It May Not Be for You</h2>

<p>Barclays is running a <strong>2027 Customer Care Apprenticeship Programme in Manchester</strong> at <strong>&pound;25,200 plus benefits</strong>: a permanent full-time job attached to a Senior Financial Services Customer Adviser apprenticeship, running August 2027 to August 2029, with in-person assessment days from October 2026.</p>

<p>Two things about it matter more than the salary.</p>

<p><strong>First, the timing.</strong> The posting states that applications close on <strong>25 September 2026</strong>, and warns that it may close earlier because of application volumes. If you are reading this after that date, the intake is gone; the next cycle will be advertised on Barclays' early careers pages rather than the main customer service search.</p>

<p><strong>Second, and this is the part nobody quotes:</strong> the programme is closed to anyone who needs a visa. Barclays states that government apprenticeship funding carries <strong>a three-year residence requirement</strong>, and that you must have the legal right to work for the whole programme at the point of enrolment, "with no gaps in your legal right to work or dependency on future visa applications" during the August 2027 to August 2029 period. So an overseas applicant is excluded twice over &mdash; once by the occupation codes and once by the funding rules.</p>

<p>The academic requirement, if you do qualify, is five GCSEs at grades A*&ndash;C (9&ndash;5) including Maths and English, or equivalent. The process runs application review, online assessments, an eligibility survey, an in-person assessment day, then an outcome with feedback.</p>

<h2>What Barclays Says It Wants</h2>

<p>The Customer Care hub is unusually direct, and the wording has not changed: "You don't need customer service experience or financial services expertise to excel at Barclays. If you've got the right attitude, we can teach you everything you need to know." It lists four qualities: being passionate, curious and empathetic; a desire to solve customer problems; strong communication skills; and the ability to work in a team.</p>

<p>That is the general statement, and it is genuine &mdash; but do not read it as a promise that every vacancy is entry level. The specialist roles set a real bar. The Glasgow mortgages role lists <strong>proven telephony customer service experience as essential</strong>, and lists mortgage knowledge and CeMAP qualification, or progress towards it, as highly valued.</p>

<p>Barclays also repeats a set of values on every posting &mdash; Respect, Integrity, Service, Excellence and Stewardship, with a mindset of Empower, Challenge and Drive. Use them to understand the language the interviewer will use. Do not paste them into your CV.</p>

<p>One small warning about a claim you will see repeated: Barclays' own postings disagree about its headcount, saying "around 100,000 people" on one page and "around 90,000" on another. Do not quote a staff number in an interview.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Confirm your right to work first.</strong> If you need sponsorship, stop here and read the first section again. Every Barclays posting carries boilerplate about sponsored visas, and it appears worldwide on roles that cannot be sponsored. It is not an offer.</li>
    <li><strong>Open the Customer Care hub</strong> to understand the role families, then use the search box on the Barclays careers homepage. Type "customer service" as the keyword and your city as the location. Do not trust a numbered category link from a third-party article.</li>
    <li><strong>Set up a job alert</strong> on the Customer Care page by category and location. With only eleven live UK vacancies at a time, the alert is worth more than repeated searching.</li>
    <li><strong>Read the work pattern before the salary.</strong> Check whether it is branch or contact centre, whether it is hybrid, and whether it runs a 24/7 rota. These differ more between postings than the pay does.</li>
    <li><strong>Tailor the examples, not the adjectives.</strong> For a complaints-heavy role, lead with a de-escalation example. For the mortgages role, put telephony experience and any CeMAP progress where it can be seen in seconds.</li>
    <li><strong>Expect four stages:</strong> application, assessment, interview, then next steps. The assessment covers behaviours and ability at work; the interview explores past experience against the role's skills.</li>
</ol>

<p>If you are building the underlying experience first, <a href="/blog/how-to-get-a-remote-customer-service-job-with-no-experience">how to get a remote customer service job with no experience</a> is the natural starting point, and <a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">Tesco supermarket jobs in the UK</a> covers another large UK employer that publishes its rates openly.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can Barclays sponsor a Skilled Worker visa for a customer service job?</h3>
<p>No. Barclays holds three A-rated Skilled Worker sponsor licences, but sponsorship depends on the occupation code, not the employer. Codes 7211, 7212, 7213, 7219 and 4123, which cover call centre and bank clerk work, are all marked Ineligible by the Home Office and appear on neither the Immigration Salary List nor the Temporary Shortage List. They cannot be sponsored at any salary.</p>

<h3>How much do Barclays customer service jobs pay in the UK?</h3>
<p>&pound;27,700 a year on almost every advisor and specialist role currently advertised, plus a core benefits package covering pension, private medical insurance, life insurance and income protection. The Newport, Isle of Wight role pays &pound;26,700. Two roles publish no salary at all. The apprenticeship pays &pound;25,200.</p>

<h3>Is &pound;27,700 a good salary for customer service in the UK?</h3>
<p>It is about average. ONS figures put the median gross annual pay for full-time customer service occupations at &pound;28,036, so Barclays sits just below it, and above the &pound;27,035 median for call and contact centre work specifically. It is roughly 12 per cent above what the National Living Wage of &pound;12.71 an hour would pay for a full-time week.</p>

<h3>How many Barclays customer service jobs are actually open right now?</h3>
<p>Eleven in the UK. The board displays fifteen results, but two are miscategorised roles in India and New York that are not customer service at all, and two are in Douglas, Isle of Man, which is outside the UK immigration system.</p>

<h3>Do I need banking experience to apply to Barclays?</h3>
<p>Not for most roles. Barclays states you do not need customer service experience or financial services expertise, and that it can teach the rest to people with the right attitude. Specialist roles are different: the Glasgow mortgages vacancy lists proven telephony customer service experience as essential and values CeMAP progress.</p>

<h3>Are Barclays customer service jobs remote or hybrid?</h3>
<p>It depends on the type of role rather than on Barclays' general policy. Contact centre and specialist telephony roles are hybrid, and the Sunderland role says so explicitly. Branch roles describe you as the face-to-face colleague in branch or at a local hub, which is not compatible with home working.</p>

<h3>Why do the Barclays job links in other guides not work?</h3>
<p>Because they point at individual job IDs. The Glasgow Customer Service Associate vacancy that most guides use as their main apply link now returns a 404 reading "We are sorry this job post no longer exists." Worse, Barclays' numbered category URLs return HTTP 200 with zero results when the facet number changes, so a broken link looks like an empty job board rather than an error.</p>

<h3>Can I still apply for the 2027 Customer Care Apprenticeship?</h3>
<p>Only if you are reading this before 25 September 2026, and only if you already have UK work rights. Barclays states the programme carries a three-year residence requirement and that you must hold the right to work for the full August 2027 to August 2029 period with no dependency on future visa applications.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Barclays customer service advisor salary UK</li>
    <li>Barclays jobs Glasgow customer care</li>
    <li>Barclays apprenticeship 2027 Manchester</li>
    <li>Does Barclays sponsor Skilled Worker visas</li>
    <li>Bank customer service jobs UK no experience</li>
    <li>Barclays personal banker job description</li>
    <li>UK contact centre jobs hybrid working</li>
    <li>Customer service occupations Skilled Worker eligibility</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a></li>
    <li><a href="/blog/how-to-apply-for-tesco-supermarket-jobs-in-uk">How to Apply for Tesco Supermarket Jobs in UK</a></li>
    <li><a href="/blog/customer-service-jobs-in-usa">Customer Service Jobs in USA</a></li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a></li>
    <li><a href="/blog/how-to-get-a-remote-customer-service-job-with-no-experience">How to Get a Remote Customer Service Job With No Experience</a></li>
    <li><a href="/blog/call-center-jobs-in-pakistan">Call Center Jobs in Pakistan</a></li>
    <li><a href="/blog/office-assistant-jobs-in-uk">Office Assistant Jobs in UK</a></li>
    <li><a href="/blog/how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk">How to Apply for Marks and Spencer Retail Jobs in the UK</a> &mdash; the hourly rate M and S announces itself, and why no store role can be sponsored.</li>
</ul>
HTML;
    }
}
