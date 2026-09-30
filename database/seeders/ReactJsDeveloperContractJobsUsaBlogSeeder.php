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
 * React JS developer contract work through US staffing and consulting firms,
 * checked on 30 September 2026.
 *
 * A spoke off the React developer hub. The supplied brief was titled for a
 * near-synonym of the hub's head term, and its own FAQ conceded the titles are
 * "largely" the same. What it did contain that nothing on this site covers is
 * the staffing-firm layer: contract-to-hire, corp-to-corp, and the visa status
 * line that these adverts lead with. That is what this page takes, at its own
 * URL, because it is the part of the US market that treats this site's readers
 * worst and the protections are real and quotable.
 *
 * The spine, in order of how much money it saves the reader:
 *
 * 1. Benching is illegal. 20 CFR 655.731(c)(7)(i) requires the full wage during
 *    nonproductive time caused by the employer, "e.g., because of lack of
 *    assigned work". 655.731(c)(6)(i) puts "waiting for an assignment" inside
 *    the definition of having entered into employment.
 * 2. The wage starts on a deadline the worker can count: 30 days after
 *    admission, or 60 days after becoming eligible if already in the US.
 * 3. Deductions. The employer may not recoup its own business expenses,
 *    including attorney and LCA filing costs, may not charge a penalty for
 *    leaving early, and may not take back the statutory filing fee even
 *    indirectly through a third party. Signing a contract that says otherwise
 *    is expressly not voluntary authorisation.
 * 4. STEM OPT through a staffing agency. Corrected against an assumption I held
 *    going in: SEVP permits agencies to help place students. What it forbids is
 *    the agency signing Form I-983. Only the E-Verified employer that provides
 *    the actual training may sign, and a new I-983 is needed per employer.
 *
 * Corrections applied to the supplied brief:
 *  1. Every Indeed and LinkedIn link removed, with the 4,781 and 5,582 vacancy
 *     counts they were quoted to support.
 *  2. The $129,348 average and the $106,000 to $157,000 band are ZipRecruiter
 *     figures and are not republished. BLS May 2025 medians are used instead.
 *  3. The brief listed named staffing firms as examples. Not reproduced: this
 *     page is about the arrangement, not about directing readers at vendors.
 *
 * Deliberately NOT claimed, and this is the important one: that an H-1B, OPT or
 * CPT holder can challenge a "US Citizens only" advert. Under 8 U.S.C.
 * 1324b(a)(3) they are not "protected individuals", and 1324b(a)(4) expressly
 * permits preferring an equally qualified US citizen. The page says who the
 * provision does protect and points the reader at the claims that are actually
 * available, because telling a Pakistani developer otherwise would be worse
 * than telling them nothing.
 *
 * Also not claimed: the ACWIA fee amount. The regulation still names the 1998
 * and 2000 figures, which are superseded, so the prohibition is quoted and the
 * number is left out.
 */
class ReactJsDeveloperContractJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'react-js-developer-contract-jobs-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=react%20developer';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Staffing and Consulting React Employers (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-react-contract-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'React JS Developer Contract Roles, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Contract / Contract-to-hire',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Set by the client engagement, with the wage payable whether or not work is assigned',
                    'language' => 'English',
                    // Contract rates are negotiated per engagement and the
                    // brief's figures were aggregator data, so none is asserted.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'react js developer contract jobs, react developer c2c jobs usa, h1b react developer jobs, opt react developer jobs, react contract to hire usa',
                    'meta_description' => 'React JS developer contract and contract-to-hire roles through US staffing and consulting firms, and the pay rules that apply to sponsored workers.',
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
                    'title' => 'React JS Developer Contract Jobs in USA',
                    'excerpt' => 'If a staffing firm stops paying you because there is no client project, that is illegal. Federal rules require the full wage during nonproductive time caused by the employer, and waiting for an assignment counts as being employed.',
                    'content' => $content,
                    'featured_image' => 'blogs/react-js-developer-contract-jobs-usa.jpg',
                    'tags' => 'react js developer contract jobs, react developer c2c jobs usa, h1b react developer jobs, opt react developer jobs, react contract to hire usa, h1b benching rules, stem opt staffing agency, react js developer jobs usa',
                    'meta_title' => 'React JS Developer Contract Jobs in USA: Your Rights',
                    'meta_description' => 'React JS contract jobs through US staffing firms: why unpaid bench time is illegal, what may not be deducted, and the STEM OPT rule agencies get wrong.',
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
<p>This is an overview of React JS developer contract work offered through staffing and consulting firms across the United States. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>These roles arrive in three shapes: a fixed-term contract with the staffing firm as your employer, contract-to-hire with a conversion date, and corp-to-corp where a second company employs you and bills the first. The guide explains which protections follow you in each, and which do not.</p>
<p>The federal search linked here covers the government side of this market. The staffing market is larger, and the guide sets out the pay rules that apply to it.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>A large share of US React adverts come from staffing and consulting firms rather than from the company you would actually work at. You can usually tell from the first two lines: a precise stack, and an eligibility list &mdash; US Citizen, Green Card, OPT-EAD, CPT, H-1B.</p>

<p>These roles are real work and often a genuine route in. They are also where this market treats people worst, and almost nothing written for candidates explains the rules that apply. Most of those rules are not about immigration status at all. They are wage rules, they are enforceable, and the biggest one is this: <strong>if a staffing firm stops paying you because there is no project, that is unlawful.</strong></p>

<p>If you want the market picture instead &mdash; what the work pays and which occupation it is benchmarked against &mdash; that is on our <a href="/blog/react-developer-jobs-in-usa">React developer jobs guide</a>.</p>

<h2 id="benching-is-illegal">Unpaid Bench Time Is Illegal</h2>

<p>"Benching" is when a firm sponsors you, places you on a client project, and then stops paying when the project ends while keeping you available for the next one. For an H-1B worker, the regulation at <strong>20 CFR 655.731(c)(7)</strong> addresses this directly:</p>

<blockquote><p>"If the H-1B nonimmigrant is not performing work and is in a nonproductive status due to a decision by the employer (e.g., because of lack of assigned work), lack of a permit or license, or any other reason... the employer is required to pay the salaried employee the full pro-rata amount due, or to pay the hourly-wage employee for a full-time week... at the required wage for the occupation listed on the LCA."</p></blockquote>

<p>The Department of Labor states the same thing plainly in its fact sheet on nonproductive time: H-1B workers "must be paid the required wage rate for all nonproductive time caused by conditions related to employment", such as "lack of assigned work, lack of a permit, or studying for a licensing exam".</p>

<p>There are genuine exceptions, and it is worth knowing them so you can tell a real one from an excuse. Under 655.731(c)(7)(ii) the wage need not be paid where the nonproductive period is "due to conditions unrelated to employment which take the nonimmigrant away from his/her duties at his/her voluntary request and convenience" &mdash; the regulation's own examples are touring the US or caring for an ill relative &mdash; or where the worker is unable to work, for instance maternity leave or an accident. It also does not apply after "a bona fide termination of the employment relationship", and a bona fide termination requires the employer to notify immigration authorities so the petition is cancelled.</p>

<p><strong>The distinction to hold on to:</strong> no work available is the employer's problem, and is paid. Your own voluntary absence is not. "We will put you back on payroll when you are placed" is the first category wearing the language of the second.</p>

<h3>When the wage is supposed to start</h3>

<p>The same regulation removes the other common ambiguity, which is a firm that sponsors you and then keeps you unpaid for months while "looking for a project". Under 655.731(c)(6)(i) you have entered into employment when you "first make yourself available for work or otherwise come under the control of the employer" &mdash; and the regulation's list of examples begins with <strong>"waiting for an assignment"</strong>. Reporting for orientation or training, going to a client interview, and studying for a licensing exam are all in the same list.</p>

<p>And there is a hard backstop even if none of that has happened. Under 655.731(c)(6)(ii) an employer with a certified LCA and an approved petition must pay the required wage <strong>beginning 30 days after the worker is first admitted to the US</strong> under the petition, or, if the worker is already in the country, <strong>beginning 60 days after becoming eligible to work</strong> for that employer.</p>

<img src="/public/storage/blogs/react-js-developer-contract-jobs-usa-staffing.jpg" alt="A React JS developer on a contract engagement through a staffing firm" />

<h2 id="what-cannot-be-deducted">What Cannot Come Out of Your Pay</h2>

<p>Staffing contracts in this market frequently contain terms that the regulation does not permit. Four of them:</p>

<ul>
    <li><strong>The firm's own costs of sponsoring you.</strong> A deduction "may not recoup a business expense(s) of the employer (including attorney fees and other costs connected to the performance of H-1B program functions which are required to be performed by the employer, e.g., preparation and filing of LCA and H-1B petition)".</li>
    <li><strong>A penalty for leaving early.</strong> "The employer is not permitted to require (directly or indirectly) that the nonimmigrant pay a penalty for ceasing employment with the employer prior to an agreed date." The regulation does allow <em>bona fide liquidated damages</em>, which is a real distinction and turns on state law, but a flat exit penalty is not that.</li>
    <li><strong>The statutory filing fee, even indirectly.</strong> The employer "may not receive, and the H-1B nonimmigrant may not pay, any part of" it, "whether directly or indirectly, voluntarily or involuntarily" &mdash; and the regulation closes the obvious loophole: if a third party pays the fee and you reimburse that third party, the employer is still in violation, "since the employer would in such circumstances have been spared the expense of the fee which the H-1B nonimmigrant paid".</li>
    <li><strong>Anything you signed for as a condition of the job.</strong> This is the one that surprises people most. The regulation's note to 655.731(c)(9)(iii)(A) states that "an employee's mere acceptance of a job which carries a deduction as a condition of employment does not constitute voluntary authorization, even if such condition were stated in writing".</li>
</ul>

<p>The Department treats an improper deduction as non-payment of wages: "Any unauthorized deduction taken from wages is considered by the Department to be non-payment of that amount of wages, and in the event of an investigation, will result in back wage assessment".</p>

<p>One deliberate omission: the regulation still names the statutory fee at the amounts set in 1998 and 2000, which have since been superseded. The prohibition is what matters and is quoted above; the current amounts should be checked against USCIS rather than taken from the regulatory text.</p>

<h2 id="stem-opt-and-staffing-agencies">STEM OPT Through a Staffing Agency</h2>

<p>This section corrects something widely repeated, including by people who mean well.</p>

<p>It is often said that a staffing or temporary agency cannot be used for STEM OPT at all. <strong>That is not what SEVP says.</strong> Its published guidance is that F-1 students on the STEM OPT extension "may find their training opportunity with assistance from a temporary or staffing agency", provided all regulatory requirements are still met.</p>

<p>The real constraint is narrower and sharper, and it is the one that defeats the layered vendor model:</p>

<blockquote><p>"If a student uses a temporary or staffing agency to place them in a training opportunity, the agency cannot complete and sign the Form I-983, 'Training Plan for STEM OPT Students.' Only the E-verified employer that provides the actual training relevant to the student's qualifying STEM degree is authorized to sign and complete the Form I-983."</p></blockquote>

<p>The current STEM OPT Hub states the rule as a condition of qualifying: "F-1 students cannot qualify for STEM OPT extensions unless they will be bona fide employees of the employer signing the Form I-983", and "the employer that signs the Training Plan must be the same entity that employs the student and provides the practical training experience."</p>

<p>So the arrangement to be wary of is not "a staffing agency is involved". It is <strong>an agency that signs your I-983 while a different company supplies the work</strong>. And if an agency moves you between clients, SEVP is explicit that "the student will need to complete a new Form I-983 for every new training opportunity with each employer".</p>

<p>Three further requirements, from the official overview. Your STEM OPT employer must be <strong>enrolled in E-Verify</strong>, must provide "formal training and learning objectives", and you must <strong>work a minimum of 20 hours per week per employer</strong>. Note also that DHS "may conduct a site visit of any employer" to check that it can actually deliver what the I-983 promises, normally on 48 hours' notice &mdash; and without notice where there has been a complaint.</p>

<h3>The OPT numbers worth knowing exactly</h3>
<ul>
    <li><strong>12 months</strong> of post-completion OPT, and any pre-completion OPT used is deducted from it.</li>
    <li><strong>24 months</strong> for the STEM extension, on top.</li>
    <li><strong>90 days</strong> is the maximum aggregate unemployment during post-completion OPT. With the STEM extension the total allowance rises to <strong>150 days</strong> across the whole OPT period.</li>
    <li>If you file the STEM extension on time and your OPT expires while it is pending, employment authorisation is automatically extended for <strong>180 days</strong>.</li>
</ul>

<p>Those unemployment limits are the reason unpaid bench time is dangerous beyond the lost money: on OPT, time without a qualifying employer is counted against you.</p>

<img src="/public/storage/blogs/react-js-developer-contract-jobs-usa-visa-status.jpg" alt="A React developer checking the work authorisation line on a US contract job advert" />

<h2 id="the-eligibility-line">The "US Citizens Only" Line, Honestly</h2>

<p>Adverts in this market lead with eligibility. It is worth knowing precisely what the law says about that, because the popular version of it is wrong in a way that could waste your time.</p>

<p>Federal law does restrict these adverts. The Department of Justice's Immigrant and Employee Rights Section tells employers to avoid, unless legally required, phrasing that limits eligibility by citizenship status &mdash; and its own list of examples includes <em>"Only U.S. Citizens"</em>, <em>"Only U.S. Citizens or Green Card Holders"</em>, <em>"Must have a green card"</em>, and, in the other direction, <em>"H-1Bs Only"</em> and <em>"H-1Bs and OPT Preferred"</em>. The same statute that constrains a citizens-only advert also constrains a firm that recruits only visa holders.</p>

<p><strong>But here is the part you need before you rely on it.</strong> The citizenship-status protection at 8 U.S.C. 1324b applies to a defined class of "protected individuals": US citizens and nationals, lawful permanent residents, certain temporary residents, refugees and asylees. <strong>An H-1B worker, an OPT or STEM OPT student and a CPT student are not in that class.</strong> The statute also expressly permits an employer "to prefer to hire, recruit, or refer an individual who is a citizen or national of the United States over another individual who is an alien if the two individuals are equally qualified", and it does not apply where citizenship is "required in order to comply with law, regulation, or executive order, or required by Federal, State, or local government contract".</p>

<p>So if you are on an H-1B or OPT and you meet a "US Citizens or Green Card only" advert, the honest position is that the citizenship-status provision is not your route. What may still be available is a claim of <strong>national origin</strong> discrimination, which protects any individual, or one for <strong>document abuse</strong> &mdash; asking for "more or different documents than are required", or refusing documents that reasonably appear genuine, where done to discriminate.</p>

<p>The practical use of all this is simpler than the law: read the eligibility line first, and do not spend applications on roles that exclude you. The clearance and citizenship filter on the federal part of this market is set out in full in our <a href="/blog/react-developer-jobs-in-usa">React developer guide</a>.</p>

<h2 id="what-to-ask">What To Ask Before You Sign</h2>

<ul>
    <li><strong>Who is my legal employer, and who pays me?</strong> In a corp-to-corp arrangement those can be different companies from the one whose work you do. The answer decides who owes you the wage rules above.</li>
    <li><strong>What happens to my pay between projects?</strong> Ask it in exactly those words, and get the answer in writing.</li>
    <li><strong>What is being deducted, and for what?</strong> Compare the list against the four items above.</li>
    <li><strong>Is there an exit penalty?</strong> If so, ask whether it is characterised as liquidated damages and on what basis.</li>
    <li><strong>For STEM OPT: who signs my I-983?</strong> It must be the E-Verified employer that actually provides your training. If the agency offers to sign, that is the problem, not a convenience.</li>
    <li><strong>Is the rate "open"?</strong> An open rate means it is set per candidate. Decide your number before that conversation, not during it.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can a staffing firm stop paying me between projects?</h3>
<p>Not if you are on an H-1B. 20 CFR 655.731(c)(7) requires the full required wage during nonproductive time caused by the employer, and the regulation gives "lack of assigned work" as its own example. Unpaid bench time is treated as non-payment of wages.</p>

<h3>When does my H-1B wage have to start?</h3>
<p>When you enter into employment, which includes "waiting for an assignment". Failing that, no later than 30 days after you are first admitted under the petition, or 60 days after you become eligible to work for the employer if you are already in the US.</p>

<h3>Can my employer deduct its legal and filing fees from my pay?</h3>
<p>No. Deductions may not recoup the employer's business expenses, and the regulation names attorney fees and the cost of preparing and filing the LCA and petition. The statutory filing fee may not be recovered from you even indirectly through a third party.</p>

<h3>I signed a contract agreeing to a deduction. Does that make it valid?</h3>
<p>Not by itself. The regulation states that an employee's mere acceptance of a job carrying a deduction as a condition of employment "does not constitute voluntary authorization, even if such condition were stated in writing".</p>

<h3>Can I do STEM OPT through a staffing agency?</h3>
<p>Yes. SEVP says students may find a training opportunity with a staffing agency's assistance. What the agency cannot do is sign your Form I-983 &mdash; only the E-Verified employer that provides the actual training may sign, and a new I-983 is needed for each employer.</p>

<h3>How long can I be unemployed on OPT?</h3>
<p>An aggregate of 90 days during post-completion OPT, rising to a total of 150 days across the whole period if you get the 24-month STEM extension. This is why unpaid bench time carries a second cost on OPT.</p>

<h3>Is a "US Citizens only" job advert illegal?</h3>
<p>It can be, and the Department of Justice tells employers to avoid such phrasing unless a law, regulation, executive order or government contract requires it. But the citizenship-status protection covers citizens, permanent residents, refugees and asylees, not H-1B or OPT holders.</p>

<h3>What claim do I have if an advert excludes my visa status?</h3>
<p>Not a citizenship-status claim, because H-1B, OPT and CPT holders are not protected individuals for that purpose. National origin discrimination, which protects any individual, and document abuse are the provisions that may apply.</p>

<h2>People Also Search For</h2>

<h3>React JS developer contract jobs</h3>
<p>Usually offered through staffing firms. The arrangement, not the stack, decides which protections follow you.</p>

<h3>React developer C2C jobs USA</h3>
<p>Corp-to-corp means a second company employs you. Establish who your legal employer is before anything else.</p>

<h3>H-1B React developer jobs</h3>
<p>The wage must be paid during nonproductive time caused by the employer, and starts on a deadline you can count from your admission date.</p>

<h3>H-1B benching rules</h3>
<p>Unpaid bench time due to lack of assigned work is non-payment of wages under 20 CFR 655.731(c)(7), and is recoverable as back wages.</p>

<h3>OPT React developer jobs</h3>
<p>Watch the unemployment clock: 90 days on post-completion OPT, 150 days in total with the STEM extension.</p>

<h3>STEM OPT staffing agency</h3>
<p>Permitted. But only the E-Verified employer providing the actual training may sign your Form I-983, and each employer needs its own.</p>

<h3>React contract to hire USA</h3>
<p>Ask what the conversion date is, what triggers it, and whether an exit penalty applies before it.</p>

<h3>React developer jobs USA</h3>
<p>The market picture, the two occupations React work is benchmarked against, and the pay bands are on the main React guide.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a> &mdash; the main guide: pay bands, the citizenship and clearance filter, and the toolchain that dates a portfolio.</li>
    <li><a href="/blog/remote-react-developer-jobs-in-usa">Remote React Developer Jobs in USA</a> &mdash; W-2 against 1099, self-employment tax and the misclassification test.</li>
    <li><a href="/blog/senior-react-developer-jobs-in-usa">Senior React Developer Jobs in USA</a> &mdash; the prevailing wage levels, which decide the floor on a sponsored senior role.</li>
    <li><a href="/blog/entry-level-react-developer-jobs-in-usa">Entry Level React Developer Jobs in USA</a> &mdash; what the federal record says about experience, and the paid apprentice route.</li>
    <li><a href="/blog/frontend-developer-coding-tests-in-usa">Frontend Developer Coding Tests in USA</a> &mdash; the assessment stage, and the adjustment you are entitled to ask for.</li>
    <li><a href="/blog/react-software-engineer-jobs-in-usa">React Software Engineer Jobs in USA</a> &mdash; the other end of this market, where the offer carries equity and bonus instead of an hourly rate.</li>
    <li><a href="/blog/react-developer-internship-jobs-in-usa">React Developer Internship Jobs in USA</a> &mdash; CPT and pre-completion OPT, and the year of full-time CPT that costs you post-completion OPT.</li>
    <li><a href="/blog/javascript-react-developer-jobs-in-usa">JavaScript React Developer Jobs in USA</a> &mdash; the mixed-codebase work many of these engagements actually are.</li>
    <li><a href="/blog/react-typescript-developer-jobs-in-usa">React TypeScript Developer Jobs in USA</a> &mdash; the typed codebases at the other end of the same market.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li>20 CFR 655.731 &mdash; the H-1B required wage, nonproductive status, and authorised and prohibited deductions.</li>
    <li>Department of Labor, Wage and Hour Division &mdash; Fact Sheets 62G, 62H and 62I on the guaranteed wage, deductions and nonproductive time.</li>
    <li>Department of Homeland Security, Study in the States &mdash; STEM OPT Hub overview, and SEVP guidance on temporary and staffing agencies.</li>
    <li>8 CFR 214.2(f)(10) &mdash; optional practical training, unemployment limits and employer site visits; and USCIS on OPT for F-1 students.</li>
    <li>8 U.S.C. 1324b &mdash; unfair immigration-related employment practices; and the Department of Justice Immigrant and Employee Rights Section, best practices for advertising employment positions.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Regulations, fee amounts and agency guidance change, and an individual case can turn on facts this page cannot know &mdash; confirm the current position with the Department of Labor, USCIS, the Department of Justice and a licensed adviser before relying on any of it.</p>
HTML;
    }
}
