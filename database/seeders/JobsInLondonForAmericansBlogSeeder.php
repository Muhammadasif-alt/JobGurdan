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
 * "Jobs in London England for American Applicants" — the route map for a US
 * citizen specifically. The UK hub guide owns the general Skilled Worker
 * mechanics; this one owns what is different for an American: the English
 * test exemption, the Youth Mobility Scheme they cannot use, the High
 * Potential Individual route that needs no sponsor, the ETA for flying over
 * to interview, and the US tax filing that never stops.
 *
 * Corrections to the draft:
 *
 * 1. It never names a salary figure. The Skilled Worker general threshold is
 *    £41,700 a year or the occupation's going rate, whichever is higher, so a
 *    reader cannot tell whether a London offer is sponsorable without it.
 *
 * 2. It says to "check the individual job description for wording about visa
 *    sponsorship". The operative test is the job's SOC 2020 occupation code
 *    being listed as eligible in Appendix Skilled Occupations, not the
 *    advert's wording.
 *
 * 3. It tells Americans that some "may qualify for a route that does not
 *    depend on employer sponsorship" without naming one. The Youth Mobility
 *    Scheme — the route most such guides imply — does not include the United
 *    States. The sponsor-free route that does exist for Americans is the High
 *    Potential Individual visa, open to graduates of 33 named US universities.
 *
 * 4. It says UK employers "may ask whether you will require sponsorship" and
 *    to "answer accurately", but never mentions that US nationals are exempt
 *    from the Skilled Worker English language requirement, which rose from B1
 *    to B2 on 8 January 2026.
 *
 * 5. It covers flying to London for interviews under visitor rules but omits
 *    the Electronic Travel Authorisation. Americans have needed one since the
 *    scheme opened to US passports; it costs £20.
 *
 * 6. It tells readers to weigh London living costs against salary without
 *    noting that going rates are set nationally from ASHE data. There is no
 *    London uplift in the visa thresholds.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class JobsInLondonForAmericansBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.gov.uk/find-a-job';

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
        $title = 'Jobs in London England for American Applicants';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Americans skip the Skilled Worker English test but are not eligible for the Youth Mobility Scheme. The threshold is £41,700 with no London uplift, 33 US universities open the sponsor-free HPI visa, and an ETA costs £20.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-in-london-england-for-american-applicants.jpg',
                'tags' => 'jobs in london england for american applicants, london jobs for americans, uk skilled worker visa for us citizens, high potential individual visa usa, youth mobility scheme americans, uk eta for us citizens, london jobs with visa sponsorship, american expat taxes uk',
                'meta_title' => 'Jobs in London England for Americans 2026: Visa and Pay',
                'meta_description' => 'Jobs in London for Americans: the GBP 41,700 Skilled Worker threshold, no Youth Mobility route, the English test exemption, HPI visa and the GBP 20 ETA.',
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
            ['name' => 'London Licensed Sponsors — Finance, Technology, Universities & NHS Trusts (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'london-american-applicants-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Skilled Worker Visa Jobs in London — Finance, Technology, Research and Healthcare Roles for Overseas Applicants',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; the going rate is quoted against a 37.5 hour week and pro-rated for other patterns',
                'language' => 'English',
                // Sponsored pay is set by each occupation code's going rate,
                // so no single range can stand for the whole group.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'London roles with licensed visa sponsors in finance, technology, research and healthcare for overseas applicants.',
                'seo_keywords' => 'london jobs with visa sponsorship, skilled worker visa jobs london, jobs in london for americans, uk licensed sponsor jobs london, london finance technology visa jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Banks, technology firms, universities, research institutes and NHS trusts in London hold sponsor licences and recruit overseas applicants into roles that meet Skilled Worker visa rules.</p>

<h3>What the roles involve</h3>
<p>Graduate-level posts in software engineering, data, cybersecurity, quantitative finance, accounting, risk and compliance, academic research, and clinical and allied health work.</p>

<h3>Requirements</h3>
<ul>
    <li>A job offer and certificate of sponsorship from an employer on the register of licensed sponsors</li>
    <li>An occupation code listed as eligible in Appendix Skilled Occupations</li>
    <li>Salary of at least £41,700 or the occupation's going rate, whichever is higher, unless a lower threshold applies</li>
    <li>English at level B2, which US, Canadian, Australian, New Zealand and Irish nationals are exempt from proving</li>
    <li>£1,270 in savings held for 28 days, unless the sponsor certifies maintenance</li>
</ul>

<h3>What it costs the applicant</h3>
<ul>
    <li><strong>Application fee.</strong> £819 from outside the UK for up to three years, £1,618 for longer</li>
    <li><strong>Healthcare surcharge.</strong> Usually £1,035 for each year of the visa</li>
    <li><strong>Settlement.</strong> Indefinite leave to remain after five continuous years, currently £3,226 a person</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the employer on the register of licensed sponsors and then check the occupation code separately.</strong> A licensed sponsor still cannot sponsor a job that is not on the eligible list. Never pay anyone for a certificate of sponsorship.</p>

<p><strong>Note:</strong> visa rules, salary thresholds and fees are set by the Home Office &mdash; not by JobGader. Confirm the current rules on gov.uk before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>An American can work in London. US citizenship on its own does not give the right to do it. Between a job offer and a legal start date sits a visa route, an occupation code and a salary floor, and most guides written for this search never name any of the three. This one does, and it concentrates on what is actually different for a US passport holder rather than repeating the general rules that apply to every overseas applicant.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobgader.com/categories/it-software?location=United%20Kingdom" rel="noopener" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127468;&#127463; Browse London Visa Sponsorship Jobs &rarr;
    </a>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-london-england-for-american-applicants-citizens.jpg"
         alt="An American professional with a laptop bag beside Westminster Bridge, with the Stars and Stripes, the Union Jack, Big Ben and a red double-decker bus, under the heading London Jobs for American Citizens"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Five Things Americans Get Told Wrong</h2>

<ul>
    <li><strong>You are exempt from the English test.</strong> The Skilled Worker English requirement rose from B1 to B2 on 8 January 2026, but nationals of majority English-speaking countries, the USA included, do not have to prove it at all.</li>
    <li><strong>There is no working holiday visa for Americans.</strong> The Youth Mobility Scheme covers Australia, Canada, New Zealand, South Korea, Andorra, Iceland, Japan, Monaco, San Marino and Uruguay, plus Hong Kong and Taiwan by ballot. The United States is not on the list and never has been.</li>
    <li><strong>The salary floor is £41,700.</strong> Or the occupation's going rate if that is higher. A London offer below it generally cannot be sponsored, however senior it sounds.</li>
    <li><strong>London does not get an uplift.</strong> Going rates are calculated nationally from the Annual Survey of Hours and Earnings. The threshold is the same in Hull as in Mayfair, so London's cost of living works against you, not for you.</li>
    <li><strong>You need an ETA just to fly over and interview.</strong> It costs £20 and is separate from any work visa.</li>
</ul>

<h2>The Routes an American Can Actually Use</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Sponsor needed?</th>
            <th style="padding:10px;text-align:left;">Key rules and cost</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Skilled Worker</strong></td><td style="padding:10px;">Yes</td><td style="padding:10px;">£41,700 or the going rate; eligible occupation code; £819 up to 3 years or £1,618 over 3 years; healthcare surcharge usually £1,035 a year</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>High Potential Individual</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">Degree from a listed global university in the last 5 years; 2 years, or 3 with a doctorate; £880 fee; once in a lifetime</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Graduate</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">Only if you studied in the UK; 2 years if you apply by 31 December 2026, 18 months from 1 January 2027, 3 years for doctorates; £937</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Global Talent</strong></td><td style="padding:10px;">No</td><td style="padding:10px;">Endorsement or an eligible prestigious prize in research, arts or digital technology instead of a job offer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Senior or Specialist Worker</strong></td><td style="padding:10px;">Yes, your own employer</td><td style="padding:10px;">The Global Business Mobility route for a transfer into a UK branch of the company you already work for</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Youth Mobility Scheme</strong></td><td style="padding:10px;">&mdash;</td><td style="padding:10px;"><strong>Not open to US nationals.</strong> Ignore any guide that suggests otherwise</td></tr>
    </tbody>
</table>
</div>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-london-england-for-american-applicants-westminster.jpg"
         alt="A man in a navy suit holding a laptop beside the Thames at sunset, with Big Ben, the Palace of Westminster, the Shard, a red double-decker bus and a Union Jack behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What a London Job Must Meet to Be Sponsorable</h2>

<p>Sponsorship is decided job by job, not employer by employer. A bank with a sponsor licence can sponsor its quantitative analysts and not its branch cashiers. Four tests have to pass together:</p>

<ol>
    <li><strong>The employer holds a licence.</strong> Search the register of licensed sponsors on gov.uk for the exact legal entity name, not the brand.</li>
    <li><strong>The occupation code is eligible.</strong> The Immigration Rules work from SOC 2020 codes listed in Appendix Skilled Occupations, where every code is marked Higher Skilled, Medium Skilled or Ineligible. A medium-skilled code qualifies only through the Immigration Salary List or the Temporary Shortage List.</li>
    <li><strong>The salary clears both floors.</strong> £41,700 general threshold, and the going rate for that specific code. Whichever is higher is the number that counts. Lower floors exist: £37,500 with a relevant PhD, £33,400 with a STEM PhD, on the Immigration Salary List, or as a new entrant.</li>
    <li><strong>The hours match.</strong> Going rates are quoted against a 37.5 hour week and pro-rated for anything else, so a four-day week cuts the salary that counts toward the threshold.</li>
</ol>

<p>The Migration Advisory Committee reviewed these figures in December 2025 and recommended keeping the general threshold at £41,700, while setting out £48,400 as an option ministers could take instead. Treat the current number as the floor for now, not as settled for the next few years.</p>

<h2>The English Test You Do Not Have to Take</h2>

<p>Skilled Worker applicants must prove English at level B2 on the CEFR scale, up from B1 on 8 January 2026. US nationals are exempt because the United States is on the Home Office list of majority English-speaking countries. No secure English language test, no Ecctis assessment of your degree, no fee.</p>

<p>This is a genuine advantage over most of the applicant pool, and it is worth saying so in a covering letter when a London employer is weighing the administrative load of sponsoring someone.</p>

<h2>The High Potential Individual Visa: No Sponsor Required</h2>

<p>This is the route most guides written for Americans leave out, and for a recent graduate of a major US university it is often the fastest way in. It needs no job offer and no sponsor. You arrive, then look for work.</p>

<ul>
    <li><strong>Who qualifies.</strong> Anyone awarded a bachelor's degree or above by a university on the Global Universities List within the last five years.</li>
    <li><strong>How long.</strong> Two years, or three with a PhD or other doctoral qualification.</li>
    <li><strong>What it costs.</strong> £880 application fee, the healthcare surcharge at usually £1,035 a year, and £1,270 in savings.</li>
    <li><strong>The catch.</strong> You can only apply once, ever. A refused or withdrawn application uses up your single attempt.</li>
</ul>

<p>The list is republished each year and is matched to when your degree was awarded, so check the edition covering your graduation date rather than the newest one. The current edition covers qualifications awarded between 1 November 2025 and 31 October 2026, and names 33 US universities:</p>

<p>Boston University, Brown, Caltech, Carnegie Mellon, Columbia, Cornell, Duke, Harvard, Johns Hopkins, MIT, NYU, Northwestern, Princeton, Purdue West Lafayette, Stanford, UT Austin, UC Berkeley, UC Irvine, UCLA, UC San Diego, UC Santa Barbara, Chicago, Illinois Urbana-Champaign, Michigan Ann Arbor, Minnesota Twin Cities, North Carolina at Chapel Hill, Pennsylvania, Southern California, Washington, Wisconsin-Madison, Vanderbilt, Washington University in St Louis and Yale.</p>

<p>If your university is not on it, this route is closed and the Skilled Worker route is the realistic one.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-london-england-for-american-applicants-riverside.jpg"
         alt="A woman working on a laptop at a riverside cafe table in London at sunset, with Tower Bridge, the Shard, a red double-decker bus and a Union Jack in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Sector Gate Most Americans Hit Before the Visa</h2>

<p>Every guide tells Americans that London hires in technology, finance, healthcare and higher education. What they leave out is that four of those sectors put a gate in front of the job that has nothing to do with immigration, and that gate is usually slower than the visa.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-london-england-for-american-applicants-sectors.jpg"
         alt="An American professional in a navy suit holding a laptop above the Thames at sunset, with the US flag, the Union Jack, the Houses of Parliament and the City skyline behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<ul>
    <li><strong>Healthcare.</strong> Registration comes before employment, and employment comes before the visa. Doctors register with the General Medical Council, nurses and midwives with the Nursing and Midwifery Council, and physiotherapists, radiographers, paramedics and most other allied health professionals with the Health and Care Professions Council. A US licence does not transfer; each regulator assesses the qualification and may require an English or clinical test. Start the registration months before the job search.</li>
    <li><strong>Finance.</strong> London's regulated roles sit under the Senior Managers and Certification Regime, which obliges the hiring firm to collect regulatory references covering the past six years and to certify you as fit and proper. For an American whose employment history is entirely outside the UK, that reference-gathering is the slow part of onboarding, not the visa.</li>
    <li><strong>Schools.</strong> Teaching most classes in a state school means holding Qualified Teacher Status. A US teaching licence is not QTS, although teachers qualified in some countries can apply to have QTS awarded. Independent schools set their own rules.</li>
    <li><strong>Law and accountancy.</strong> A US JD does not make you a solicitor; qualification runs through the Solicitors Qualifying Examination, with exemptions possible for qualified foreign lawyers. A US CPA is not an ACA or ACCA qualification, though reciprocal arrangements and exemptions exist.</li>
    <li><strong>Technology, research and most professional services.</strong> No licence gate at all. This is why these are the sectors where an American most often moves quickly, and why a research post may qualify for Global Talent instead of needing a sponsor.</li>
</ul>

<p>The order that works is: check the professional registration first, then the occupation code, then the sponsor licence, then apply. Doing it in the other order is how people lose a year.</p>

<h2>Flying Over for an Interview</h2>

<p>Visitor rules let you attend interviews, meetings, conferences and seminars, negotiate and sign contracts, and make site visits. They do not let you start work, paid or unpaid, for a UK company. Receiving an offer and receiving permission to work are two separate events, and the second one takes weeks.</p>

<p>Americans travel on an Electronic Travel Authorisation rather than a visitor visa. It costs £20, covers stays of up to six months, and must be in place before you board. Apply through gov.uk; sites that imitate the government service charge more for the same thing.</p>

<h2>What London Actually Pays, and What It Costs</h2>

<p>Two separate numbers matter and they are often confused:</p>

<ul>
    <li><strong>The statutory minimum.</strong> The National Living Wage is £12.71 an hour for workers aged 21 and over from 1 April 2026. It is the legal floor for any job in the UK.</li>
    <li><strong>The voluntary London rate.</strong> The Living Wage Foundation's London Living Wage is £14.80 an hour. Accredited employers choose to pay it; nobody is compelled to.</li>
</ul>

<p>Neither number is relevant to sponsorship. A full-time job at the London Living Wage pays roughly £28,900 a year, which is well under £41,700, so hospitality, retail, warehouse and most entry-level administrative work in London cannot be sponsored at all. That is the single most useful thing to know before planning a move: the routes that bring Americans into London are graduate and professional ones.</p>

<p>Against that, budget for London rents, Transport for London fares and council tax before comparing a London offer with a US salary. The visa threshold makes no allowance for any of it.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/jobs-in-london-england-for-american-applicants-skyline.jpg"
         alt="A professional in glasses holding folders on the Thames embankment at sunset, with a red telephone box, Westminster Bridge, Big Ben and the Union Jack behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Your US Taxes Do Not Stop</h2>

<p>The United States taxes its citizens on worldwide income regardless of where they live, so moving to London adds a filing obligation rather than replacing one. This is not tax advice, but three things are worth knowing before you accept an offer:</p>

<ul>
    <li><strong>Foreign Earned Income Exclusion.</strong> For tax year 2026 you may exclude up to $132,900 of foreign earned income on Form 2555, if you meet the bona fide residence test or the physical presence test of 330 full days abroad in 12 consecutive months.</li>
    <li><strong>FBAR.</strong> If your foreign accounts together exceed $10,000 at any point in the year, you must file FinCEN Form 114. It is due 15 April with an automatic extension to 15 October.</li>
    <li><strong>Social security.</strong> The US and UK have had a totalization agreement since 1 January 1985, so you should not pay into both systems for the same work. A certificate of coverage is issued by your home country's social security agency.</li>
</ul>

<h2>How to Search Without Wasting Months</h2>

<ol>
    <li><strong>Start from the occupation code, not the job title.</strong> Find your SOC 2020 code in Appendix Skilled Occupations and confirm it is Higher Skilled before applying anywhere.</li>
    <li><strong>Check the going rate for that code</strong> and treat it as your minimum acceptable London salary. Negotiating below it ends the visa, not just the pay.</li>
    <li><strong>Apply on employers' own careers pages.</strong> Banks, universities, research institutes and NHS trusts in London all publish their own vacancies, and the government's Find a Job service lists employers directly.</li>
    <li><strong>Verify the sponsor licence yourself</strong> on the register, and verify the occupation separately. One does not imply the other.</li>
    <li><strong>Say you are exempt from the English requirement.</strong> It removes one of the objections a reluctant employer reaches for.</li>
    <li><strong>Never pay for sponsorship.</strong> The employer pays the Immigration Skills Charge, £1,320 per 12 months for a medium or large sponsor and £480 for a small or charitable one. Anyone asking you to fund it is running a scam.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can an American work in London without a visa?</h3>
<p>No. US citizenship gives no right to work in the UK. You need a visa route such as Skilled Worker, High Potential Individual, Graduate or Global Talent, or another immigration status that already permits work.</p>

<h3>What salary does a London job need to pay to sponsor an American?</h3>
<p>At least £41,700 a year or the going rate for the occupation code, whichever is higher. Lower floors of £37,500 or £33,400 apply with a relevant PhD, a STEM PhD, an Immigration Salary List job or new entrant status.</p>

<h3>Do Americans need to take an English test for a UK work visa?</h3>
<p>No. The requirement is level B2 since 8 January 2026, but US nationals are exempt as citizens of a majority English-speaking country.</p>

<h3>Can Americans use the UK Youth Mobility Scheme?</h3>
<p>No. The United States is not one of the participating countries. The sponsor-free routes open to Americans are the High Potential Individual visa, Global Talent, and the Graduate visa for those who studied in the UK.</p>

<h3>Which US universities qualify for the High Potential Individual visa?</h3>
<p>Thirty-three, including Harvard, MIT, Stanford, Yale, Columbia, Berkeley, Michigan, Purdue and Washington University in St Louis. The list is matched to the year your degree was awarded.</p>

<h3>Do I need an ETA to fly to London for a job interview?</h3>
<p>Yes. Americans need an Electronic Travel Authorisation, which costs £20 and covers visits of up to six months. Interviews are permitted; starting work is not.</p>

<h3>How much does a UK Skilled Worker visa cost an American?</h3>
<p>£819 from outside the UK for up to three years or £1,618 for longer, plus the healthcare surcharge at usually £1,035 a year, and £1,270 in savings unless the sponsor certifies maintenance.</p>

<h3>Do I still file US taxes while working in London?</h3>
<p>Yes. US citizens are taxed on worldwide income. For 2026 the Foreign Earned Income Exclusion is $132,900 on Form 2555, and foreign accounts over $10,000 in total require FinCEN Form 114.</p>

<h2>People Also Search For</h2>

<h3>London jobs with visa sponsorship for Americans</h3>
<p>Graduate and professional roles that clear £41,700 or the occupation's going rate.</p>

<h3>UK Skilled Worker visa for US citizens</h3>
<p>The main sponsored route; no English test for American applicants.</p>

<h3>High Potential Individual visa US universities</h3>
<p>Thirty-three American universities on the current Global Universities List.</p>

<h3>Youth Mobility Scheme for Americans</h3>
<p>Not available; the United States is not a participating country.</p>

<h3>UK ETA for US citizens</h3>
<p>£20, valid for visits of up to six months, required before boarding.</p>

<h3>Skilled Worker salary threshold 2026</h3>
<p>£41,700 or the going rate, with no London weighting.</p>

<h3>London Living Wage 2026</h3>
<p>£14.80 an hour, voluntary, and far below the sponsorship threshold.</p>

<h3>American expat taxes in the UK</h3>
<p>Form 2555, the $132,900 exclusion for 2026, and FinCEN Form 114 over $10,000.</p>

<h2>More Job Guides</h2>

<p>Working out a London move? These cover the neighbouring questions:</p>

<ul>
    <li><a href="/blog/vacancies-in-london">Vacancies in London</a> &mdash; the London market in ONS numbers, the pay floors and the right-to-work check.</li>
    <li><a href="/blog/uk-jobs-with-visa-sponsorship">UK Jobs with Visa Sponsorship</a> &mdash; how to read the sponsor register and what a Certificate of Sponsorship is.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; every visa route compared, including the ones that closed in 2025.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London (No Experience Needed)</a> &mdash; what London entry-level work really pays, and why none of it can be sponsored.</li>
    <li><a href="/blog/business-analyst-jobs-in-uk">Business Analyst Jobs in UK</a> &mdash; the SOC 2431 code worked through end to end.</li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a> &mdash; a medium-skilled code that only the Temporary Shortage List keeps open.</li>
    <li><a href="/blog/how-to-apply-for-lloyds-graduate-jobs-in-the-uk">How to Apply for Lloyds Graduate Jobs in the UK</a> &mdash; ten schemes with published salaries, and their sponsorship answer.</li>
    <li><a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">How to Apply for Heathrow Airport Jobs in the UK</a> &mdash; the five year vetting that decides the application.</li>
    <li><a href="/blog/teaching-jobs-in-uk">Teaching Jobs in UK</a> &mdash; qualified teacher status and the pay scales.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; the clinical routes and the registration that comes before the visa.</li>
    <li><a href="/blog/work-from-home-jobs-in-uk">Work From Home Jobs in UK</a> &mdash; remote UK work, and why it does not solve the right-to-work question.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a> &mdash; which marketing codes clear the salary threshold.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration, legal or tax advice. UK visa rules, salary thresholds, fees and eligible occupation lists change often, as do US filing thresholds. Confirm the current rules on gov.uk and irs.gov, or with a regulated adviser, before applying or paying anyone.</p>
HTML;
    }
}
