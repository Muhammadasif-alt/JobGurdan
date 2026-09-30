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
use Illuminate\Support\Facades\DB;

/**
 * React developer internships in the USA, checked on 30 September 2026.
 *
 * A spoke off the React developer hub, sitting below the entry level guide.
 * The supplied brief was unusually good on structure and unusually quiet on
 * the only question that decides whether an internship is worth taking, which
 * is whether it has to be paid at all. That is the axis here.
 *
 * The spine:
 *
 * 1. The FLSA requires for-profit employers to pay employees, and whether an
 *    intern is an employee turns on the seven-factor primary beneficiary test
 *    in Fact Sheet #71. If the test says employee, minimum wage and overtime
 *    are owed. Unpaid internships are generally permissible in the public and
 *    non-profit sectors, not at a for-profit software company.
 * 2. The old six-factor DOL test was rescinded on 5 January 2018 by Field
 *    Assistance Bulletin 2018-2, so anything quoting six factors is stale.
 * 3. For international students the mechanism is CPT or pre-completion OPT,
 *    and the CPT regulation carries a trap: a year or more of full-time CPT
 *    destroys eligibility for post-completion practical training.
 * 4. An annualised figure in an internship advert is not what a twelve-week
 *    intern is paid, and the BLS occupation median is not an intern wage.
 *
 * Corrections applied to the supplied brief:
 *  1. Stripped the AIPRM prompt credit, the Semrush affiliate link and the
 *     Pro Article Writer promotion it arrived wrapped in.
 *  2. Removed every link containing an employer requisition id, per the site
 *     rule against linking a URL with a job id in it. Those adverts expire and
 *     the link rots; the employer's own careers search is linked instead.
 *  3. Dropped the named employer's annualised intern pay figure. The brief's
 *     own observation about annualisation is kept and made general.
 *  4. The 95,300 and 1,717,800 projection figures come from a different BLS
 *     series than the Occupational Outlook Handbook group this site quotes
 *     elsewhere. Only the figures verified against the OOH are used, so the
 *     page does not contradict its siblings.
 *
 * Deliberately not claimed: that an unpaid internship is always unlawful. The
 * test is flexible and no single factor decides it. The page gives the factors
 * and the framing rather than a verdict it cannot support.
 */
class ReactDeveloperInternshipJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'react-developer-internship-jobs-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=software%20developer%20intern';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Student and Early Career Engineering Employers (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-react-internship-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'React Developer Internships, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Internship',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Usually full-time over a fixed summer term, or part-time alongside study',
                    'language' => 'English',
                    // No federal wage exists for an intern title and advertised
                    // annualised figures are not what a fixed term pays.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'react developer internship jobs, software engineering internship usa, paid internship usa, cpt internship react, react intern portfolio',
                    'meta_description' => 'React and frontend developer internships with US employers, including whether the role has to be paid and how students on F-1 status take one.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'visa-sponsorship'],
                [
                    'name' => 'Visa Sponsorship',
                    'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
                ]
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'React Developer Internship Jobs in USA',
                    'excerpt' => 'Before anything else, settle whether it has to be paid. The Fair Labor Standards Act requires for-profit employers to pay their employees, and whether an intern counts as one turns on a seven-factor test you can apply yourself.',
                    'content' => $content,
                    'featured_image' => 'blogs/react-developer-internship-jobs-usa.jpg',
                    'tags' => 'react developer internship jobs, software engineering internship usa, paid internship usa, unpaid internship law usa, cpt internship react, react intern portfolio, student developer jobs usa, react developer jobs usa',
                    'meta_title' => 'React Developer Internship Jobs in USA: Paid or Not',
                    'meta_description' => 'React developer internships in the USA: when an unpaid internship is unlawful, the seven-factor test, and how F-1 students take one on CPT or OPT.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p>This is an overview of React and frontend developer internships with employers across the United States. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>These roles are advertised under many names: software engineering intern, frontend engineering intern, web development intern, SDE intern and university or early career programmes. Searching only for "React intern" will miss most of them.</p>
<p>The federal search linked here covers the government side, where student roles run through structured programmes. The commercial market is larger, and the guide explains what the law requires of it.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Internship advice usually starts with the portfolio. Start somewhere else: with whether the internship has to pay you.</p>

<p>That is not a moral question, it is a legal one with a published test, and it is the difference between a first job and several months of unpaid production work. The rest &mdash; what to build, what to search for, and how an F-1 student takes an internship at all &mdash; follows from the same set of official documents.</p>

<p>If you are past the student stage, our <a href="/blog/entry-level-react-developer-jobs-in-usa">entry level React developer guide</a> covers what the federal record says about needing experience, and the paid apprenticeship route.</p>

<h2 id="does-it-have-to-be-paid">Does an Internship Have to Be Paid?</h2>

<p>The starting point is blunt. In the Department of Labor's own words, <strong>"The FLSA requires 'for-profit' employers to pay employees for their work."</strong> There is no internship exception. The only question is whether an intern is an employee, and that is decided by the <strong>primary beneficiary test</strong>:</p>

<blockquote><p>"Courts have used the 'primary beneficiary test' to determine whether an intern or student is, in fact, an employee under the FLSA. In short, this test allows courts to examine the 'economic reality' of the intern-employer relationship to determine which party is the 'primary beneficiary' of the relationship."</p></blockquote>

<p>Seven factors are used. You can read them against any offer you are given:</p>

<ol>
    <li>"The extent to which the intern and the employer clearly understand that there is no expectation of compensation. Any promise of compensation, express or implied, suggests that the intern is an employee&mdash;and vice versa."</li>
    <li>"The extent to which the internship provides training that would be similar to that which would be given in an educational environment."</li>
    <li>"The extent to which the internship is tied to the intern's formal education program by integrated coursework or the receipt of academic credit."</li>
    <li>"The extent to which the internship accommodates the intern's academic commitments by corresponding to the academic calendar."</li>
    <li>"The extent to which the internship's duration is limited to the period in which the internship provides the intern with beneficial learning."</li>
    <li>"The extent to which the intern's work complements, rather than displaces, the work of paid employees while providing significant educational benefits to the intern."</li>
    <li>"The extent to which the intern and the employer understand that the internship is conducted without entitlement to a paid job at the conclusion of the internship."</li>
</ol>

<p>Two things about how to use that list. It is not a checklist to be totted up: the Department describes it as "a flexible test, and no single factor is determinative". And the consequence when it comes out the other way is concrete: <strong>"If analysis of these circumstances reveals that an intern or student is actually an employee, then he or she is entitled to both minimum wage and overtime pay under the FLSA."</strong></p>

<p>Where unpaid internships are ordinarily fine is stated separately: they "for public sector and non-profit charitable organizations, where the intern volunteers without expectation of compensation, are generally permissible". A commercial software company is neither.</p>

<p><strong>Factor 6 is the one that decides most real cases.</strong> If you are shipping features that would otherwise be built by a paid engineer, you are displacing paid work, not complementing it. Reading a React internship advert with that factor in mind tells you a great deal: an internship built around a self-contained project with review and mentorship looks different from one that hands you the backlog.</p>

<p>One historical note, because out-of-date versions circulate widely. The Department used a <em>six</em>-factor test until it was formally rescinded on <strong>5 January 2018</strong>, when it adopted the courts' primary beneficiary test instead. Any guide quoting six factors is eight years stale.</p>

<img src="/public/storage/blogs/react-developer-internship-jobs-usa-mentor.jpg" alt="A React developer intern working with a supervising engineer during a code review" />

<h2 id="what-the-pay-figures-mean">What the Advertised Pay Figure Actually Means</h2>

<p>Two traps, both common enough to be worth naming.</p>

<p><strong>An annualised figure is not what you will be paid.</strong> Large employers frequently state intern pay as an annual salary equivalent even where the internship runs twelve weeks. A twelve-week placement pays roughly a quarter of an annual figure, and the advert is not being dishonest &mdash; it is quoting a rate. Convert it to the hours you will actually work before comparing offers.</p>

<p><strong>The occupation median is not an intern wage.</strong> BLS publishes no wage for an intern title. It reports a <strong>$135,980</strong> median for software developers as of May 2025, and that figure describes people doing the job, not people learning it. It is useful as a direction of travel and misleading as a benchmark.</p>

<p>What BLS does publish that helps: employment of software developers, quality assurance analysts and testers is projected to grow <strong>10 per cent from 2025 to 2035</strong> with about <strong>106,100 openings a year</strong>. And, usefully for anyone worried they are too inexperienced to apply, the Quick Facts for that occupation record work experience in a related occupation as <strong>None</strong>.</p>

<h2 id="international-students">If You Are an International Student</h2>

<p>For an F-1 student the internship is not just a job offer; it needs work authorisation, and there are two routes with different consequences.</p>

<h3>Curricular Practical Training (CPT)</h3>

<p>CPT is the route built for internships. The regulation defines it as training that is <strong>"an integral part of an established curriculum"</strong>, and:</p>

<blockquote><p>"Curricular practical training is defined to be alternative work/study, internship, cooperative education or any other type of required internship or practicum that is offered by sponsoring employers through cooperative agreements with the school."</p></blockquote>

<p>Three mechanics to get right:</p>
<ul>
    <li><strong>Your DSO authorises it, not your employer.</strong> "A request for authorization for curricular practical training must be made to the DSO", and the training must be "directly related to the student's major area of study". The DSO records whether it is full or part time, the employer, the location and the dates.</li>
    <li><strong>Do not start before the paperwork exists.</strong> "A student may begin curricular practical training only after receiving their Form I-20 or successor form with the DSO endorsement", signed and returned "prior to the student's commencement of employment".</li>
    <li><strong>The trap, and it is permanent:</strong> "Students who have received one year or more of full time curricular practical training are ineligible for post-completion academic training." A year of full-time CPT costs you your post-completion OPT. There are exceptions for graduate programmes that require immediate participation, but the general rule ends careers plans that were counting on OPT afterwards.</li>
</ul>

<h3>Pre-completion OPT</h3>

<p>The alternative is optional practical training taken before you finish. The total OPT allowance is <strong>12 months</strong>, and any pre-completion OPT used is <strong>deducted from the post-completion period</strong>. So this route spends the same allowance you would otherwise use after graduating, which is why CPT is usually the better instrument for a term-time or summer internship &mdash; up to the one-year limit above.</p>

<p>If the internship is offered through a staffing or consulting firm, the rules that apply once you are inside one are set out in our <a href="/blog/react-js-developer-contract-jobs-in-usa">React JS contract jobs guide</a>, including the ones about being paid when there is no assigned work.</p>

<img src="/public/storage/blogs/react-developer-internship-jobs-usa-paid.jpg" alt="A student comparing paid React developer internship offers and terms" />

<h2 id="finding-and-preparing">Finding One, and Being Ready For It</h2>

<p><strong>Search the titles, not the technology.</strong> "React intern" returns a fraction of this market. The same role is advertised as software engineering intern, frontend engineering intern, web development intern, SDE intern, application developer intern, and under university recruiting, student programmes, early career and new graduate headings. Search all of them on the employer's own careers site.</p>

<p><strong>Read the eligibility line before the technology line.</strong> Internship programmes frequently require current enrolment, a specific graduation window, and at least one academic term remaining afterwards. A strong candidate who graduates in the wrong month is ineligible, and no amount of React changes that.</p>

<p><strong>Build one complete thing rather than five partial ones.</strong> A deployed application with an API, real error and loading states, a test or two and a README that explains the decisions will out-argue a row of tutorial clones. Say plainly what you personally built if it was a group project. Our <a href="/blog/entry-level-react-developer-jobs-in-usa">entry level guide</a> covers what to build in more detail, including which toolchain dates a portfolio instantly.</p>

<p><strong>Expect the assessment stage.</strong> Internship processes usually include a coding exercise. That stage is regulated, and if you need an adjustment for a disability you are entitled to ask for one &mdash; in plain English, at any point, without putting it in writing. Our <a href="/blog/frontend-developer-coding-tests-in-usa">guide to coding tests</a> sets out what applies.</p>

<p><strong>Never pay to get an internship.</strong> Placement services charging students for "guaranteed" internships are a recurring scam, and the position is the same as for any job: an honest employer, including the federal government, will not ask you to pay to be hired.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do React developer internships have to be paid?</h3>
<p>At a for-profit employer, usually yes. The FLSA requires for-profit employers to pay employees, and whether an intern is an employee is decided by the seven-factor primary beneficiary test. If the test says employee, minimum wage and overtime are owed.</p>

<h3>When is an unpaid internship allowed?</h3>
<p>Unpaid internships "for public sector and non-profit charitable organizations, where the intern volunteers without expectation of compensation, are generally permissible". At a commercial software company the primary beneficiary test decides it, and no single factor is determinative.</p>

<h3>What is the primary beneficiary test?</h3>
<p>A seven-factor examination of the economic reality of the relationship, asking which party benefits more. It replaced the Department of Labor's earlier six-factor test, which was rescinded on 5 January 2018.</p>

<h3>Which factor matters most for a software internship?</h3>
<p>Usually the sixth: whether your work "complements, rather than displaces, the work of paid employees while providing significant educational benefits to the intern". Shipping the team's backlog is displacement.</p>

<h3>How do F-1 students take an internship?</h3>
<p>Through CPT, which must be an integral part of an established curriculum and is authorised by your DSO, or through pre-completion OPT. You may not begin CPT before your Form I-20 carries the DSO endorsement.</p>

<h3>Does CPT affect my OPT later?</h3>
<p>It can, permanently. Students who receive one year or more of full-time CPT are ineligible for post-completion practical training. Part-time CPT and shorter full-time periods do not trigger it.</p>

<h3>Is the advertised internship salary what I will receive?</h3>
<p>Not if it is annualised. A twelve-week internship quoted as an annual figure pays roughly a quarter of it. Convert to the hours you will work before comparing offers.</p>

<h3>Do I need experience to get a React internship?</h3>
<p>No. BLS records work experience in a related occupation as None for the occupation this work sits in. Employers set their own bars, but projects and coursework are the recognised substitute.</p>

<h2>People Also Search For</h2>

<h3>React developer internship jobs</h3>
<p>Advertised under many titles. Search software engineering intern and frontend engineering intern as well, on the employer's own careers site.</p>

<h3>Paid internship USA</h3>
<p>At a for-profit employer the default is that employees must be paid. The seven-factor test decides whether an intern is one.</p>

<h3>Unpaid internship law USA</h3>
<p>Governed by the FLSA and the primary beneficiary test. The six-factor version people still quote was rescinded in January 2018.</p>

<h3>CPT internship React</h3>
<p>Must be an integral part of an established curriculum, authorised by your DSO, and endorsed on your Form I-20 before you start.</p>

<h3>Software engineering internship USA</h3>
<p>The broader title, and the one that surfaces most of this market. Check the graduation-date requirement before applying.</p>

<h3>React intern portfolio</h3>
<p>One complete deployed application with an API, real error states and a clear README beats several tutorial builds.</p>

<h3>Student developer jobs USA</h3>
<p>Look for university recruiting, student programmes, early career and new graduate headings as well as the word internship.</p>

<h3>Does an internship lead to a job?</h3>
<p>Factor seven of the official test is that both sides understand the internship carries no entitlement to a paid job at the end of it.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/entry-level-react-developer-jobs-in-usa">Entry Level React Developer Jobs in USA</a> &mdash; the step after this one: what BLS records about experience, and the paid apprentice route.</li>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a> &mdash; the main guide: the two occupations, pay bands and the eligibility filter.</li>
    <li><a href="/blog/frontend-developer-coding-tests-in-usa">Frontend Developer Coding Tests in USA</a> &mdash; the assessment stage, and the adjustment you are entitled to ask for.</li>
    <li><a href="/blog/react-js-developer-contract-jobs-in-usa">React JS Developer Contract Jobs in USA</a> &mdash; the staffing route, and the STEM OPT rule agencies get wrong.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li>Department of Labor, Wage and Hour Division &mdash; Fact Sheet #71 on internship programs under the Fair Labor Standards Act, and Field Assistance Bulletin 2018-2.</li>
    <li>8 CFR 214.2(f)(10) &mdash; curricular practical training and optional practical training for F-1 students.</li>
    <li>US Citizenship and Immigration Services &mdash; optional practical training for F-1 students.</li>
    <li>Bureau of Labor Statistics &mdash; Occupational Outlook Handbook, Software Developers, Quality Assurance Analysts, and Testers.</li>
    <li>Federal Trade Commission &mdash; guidance on job and employment scams.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Whether a particular internship must be paid turns on facts this page cannot know, and immigration rules change &mdash; confirm the current position with the Department of Labor, your Designated School Official and the employer before relying on any of it.</p>
HTML;
    }
}
