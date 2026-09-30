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
 * React software engineer jobs in the USA, checked on 30 September 2026.
 *
 * A spoke off the React developer hub. The supplied brief was titled for
 * another near-synonym of the head term, but its own comparison table named the
 * one real difference between an enterprise "software engineer" offer and a
 * staffing-firm "developer" salary: equity and bonus. That is the axis here,
 * because it is what the title actually buys and nothing on this site covers
 * how any of it is taxed.
 *
 * The spine:
 *
 * 1. RSUs are taxed at vesting as ordinary wages, on the W-2, with FICA. The
 *    share price on the vest date sets the bill whether or not you sell.
 * 2. A section 83(b) election CANNOT be made on an RSU. This is the single most
 *    repeated error in commercial careers writing, and it is fully verifiable:
 *    26 CFR 1.83-3(e) excludes "an unfunded and unsecured promise to pay money
 *    or property in the future" from the definition of property, and the IRS
 *    audit guide states the conclusion outright.
 * 3. Withholding on that vest is a flat 22 per cent, which is a withholding
 *    rate rather than a calculation of what you owe.
 * 4. ISO against NSO, and the AMT adjustment that catches people who exercise
 *    and hold.
 *
 * Corrections applied to the supplied brief:
 *  1. Every Indeed and LinkedIn link removed.
 *  2. The entire pay section was ZipRecruiter: a $147,524 average, a $120,000
 *     to $173,000 band and a $205,000 90th percentile. Not republished; BLS May
 *     2025 figures are used instead.
 *  3. The brief's claim that the highest-paying market is Nome, Alaska is a
 *     small-sample artefact of job board data and is not repeated.
 *  4. Named employers were dropped. This page is about the offer structure.
 *
 * Sourcing caution carried into the page: the two IRS documents that state the
 * RSU rule most clearly, Publication 5992 and Information Letter 2024-0010,
 * both disclaim precedential weight. They are cited as IRS explanatory material
 * and the regulatory chain is given alongside.
 *
 * Deliberately NOT claimed: that the IRS describes the 22 per cent as "not your
 * actual tax". No IRS text says that. Publication 505's instruction to make
 * withholding match actual liability is quoted instead.
 *
 * Also not claimed: a 15 per cent ESPP discount as IRS wording. Neither the
 * statute nor Publication 525 uses that number; both express it as an 85 per
 * cent price floor, so the page paraphrases and says so.
 */
class ReactSoftwareEngineerJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'react-software-engineer-jobs-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=software%20engineer';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Enterprise and Product Engineering Employers (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-react-swe-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'React Software Engineer, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Standard US business hours, usually on a levelled engineering ladder',
                    'language' => 'English',
                    // Total compensation is base plus equity plus bonus and no
                    // single salary figure would describe it honestly.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'react software engineer jobs, react software engineer salary usa, rsu tax usa, equity compensation software engineer, react engineer levels usa',
                    'meta_description' => 'React software engineer roles with US enterprises and product companies, where the offer is base pay plus equity and bonus rather than salary alone.',
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
                    'title' => 'React Software Engineer Jobs in USA',
                    'excerpt' => 'The difference between an engineer title and a developer title is usually equity. RSUs are taxed as wages on the day they vest, whether or not you sell, and the election everyone tells you to file cannot be made on them at all.',
                    'content' => $content,
                    'featured_image' => 'blogs/react-software-engineer-jobs-usa.jpg',
                    'tags' => 'react software engineer jobs, react software engineer salary usa, rsu tax usa, equity compensation software engineer, stock options tax usa, react engineer levels usa, espp rules usa, react developer jobs usa',
                    'meta_title' => 'React Software Engineer Jobs in USA: Equity and Tax',
                    'meta_description' => 'React software engineer jobs in the USA: what BLS pays, how RSUs and options are taxed, the 22 per cent withholding trap, and why 83(b) does not apply.',
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
<p>This is an overview of React software engineer work with enterprises and product companies across the United States. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>These roles usually sit on a levelled ladder and pay in three parts: base salary, equity, and a bonus. The equity is the part candidates understand least and is where most of the money is decided, so the guide explains how each part is actually taxed.</p>
<p>The federal search linked here covers the government side of this market, where pay is a published GS grade and there is no equity. The commercial market is where the title and the stock grant belong.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>"React software engineer" and "React developer" describe nearly the same work. What reliably differs is who is hiring and how they pay. Developer titles cluster at staffing firms and consultancies and pay a salary or an hourly rate. Engineer titles cluster at large product companies, banks and enterprises, sit on a numbered ladder, and pay in three parts: <strong>base, equity and bonus</strong>.</p>

<p>That third structure is the reason this page exists. The equity is where the money is decided and it is the part almost nobody explains, so candidates accept offers without knowing when they will be taxed, on what, or how much will be withheld. Everything below is from the IRS, the Internal Revenue Code and the SEC.</p>

<p>For the market picture &mdash; which occupation the role is benchmarked against and what separates the bands &mdash; see our <a href="/blog/react-developer-jobs-in-usa">React developer jobs guide</a>. For what the word "senior" changes legally, see the <a href="/blog/senior-react-developer-jobs-in-usa">senior React guide</a>.</p>

<h2 id="what-bls-pays">What the Cash Part Is Measured Against</h2>

<p>No federal source publishes a wage for a React engineer title, and none publishes one by company level either. The occupation figures for <strong>May 2025</strong> are:</p>

<ul>
    <li><strong>Software developers</strong> &mdash; median <strong>$135,980</strong>, with the lowest tenth under <strong>$82,460</strong> and the highest tenth above <strong>$214,670</strong>.</li>
    <li><strong>Web developers</strong> &mdash; median <strong>$92,650</strong>, which is where a React role scoped as implementation rather than engineering sits.</li>
</ul>

<p>Those describe base pay in an occupation, not total compensation at a company. An engineer offer with meaningful equity can exceed the ninetieth percentile of the occupation without the base salary coming close to it &mdash; which is exactly why comparing offers on base alone goes wrong. No vacancy count or salary-site average appears on this page.</p>

<h2 id="how-rsus-are-taxed">RSUs: Taxed On the Day They Vest, Sold or Not</h2>

<p>Most large US employers grant restricted stock units rather than options. The mechanics that matter:</p>

<p>An RSU is, in the IRS's description, one of a class of "unsecured, unfunded promises to pay cash or stock in the future". Nothing happens at grant. As the IRS's own audit guide for equity compensation puts it, <strong>"a taxable event does not take place until the vesting of the Restricted Stock Unit"</strong>, and units settled in stock fall under section 83 "only when the stock is actually transferred to the employee".</p>

<p>What happens then is the part to internalise. From IRS Information Letter 2024-0010:</p>

<blockquote><p>"In general, equity compensation such as restricted stock and RSUs are wages subject to federal income tax withholding... Regardless of whether the award is restricted stock or an RSU, the amount included in gross income is reported in Box 1 of the employee's Form W-2... They are subject to social security and Medicare (FICA) taxes, and, if applicable, the Additional Medicare Tax."</p></blockquote>

<p>So a vest is a payday. The value of the shares on the vesting date is ordinary wage income, taxed at your rates, whether you sell the shares or keep every one of them. If the price falls afterwards, the tax does not: you were taxed on the vest-date value and you now hold a capital loss, which is a different and much less useful thing.</p>

<p>Two caveats on sourcing, stated plainly because they matter for how much weight to give this: both IRS documents quoted above are explanatory material and each disclaims precedential status. They describe the law accurately, but they are not themselves authority.</p>

<h3>The 83(b) election that cannot be made on an RSU</h3>

<p>You will be told, often confidently, to file a section 83(b) election on your grant. For an RSU that advice is not merely bad, it is impossible, and this is probably the most widely repeated error in careers writing about equity.</p>

<p>The chain is short. The regulation at <strong>26 CFR 1.83-3(e)</strong> defines what section 83 applies to:</p>

<blockquote><p>"the term 'property' includes real and personal property other than either money or an unfunded and unsecured promise to pay money or property in the future."</p></blockquote>

<p>An RSU is precisely such a promise. The IRS states the conclusion directly: Restricted Stock Units "are not considered property for purposes of IRC &sect; 83 since no actual property has been transferred, and therefore an IRC &sect; 83(b) election cannot be made with respect to the grant of a Restricted Stock Unit."</p>

<p><strong>Where 83(b) does apply</strong> is <em>restricted stock</em> &mdash; an actual transfer of shares subject to vesting, which start-ups use far more than large employers. There the election lets you be taxed on the value at transfer rather than at vesting, so later appreciation is not compensation income. The deadline is unforgiving: the election "shall be filed not later than 30 days after the date the property was transferred", with no extensions, and it cannot be revoked except with the Commissioner's consent in the narrow case of a mistake of fact. The IRS now publishes Form 15620 for it, so a self-drafted statement is no longer the only route. It also cannot be made on an option, statutory or not.</p>

<img src="/public/storage/blogs/react-software-engineer-jobs-usa-equity.jpg" alt="A React software engineer reviewing an equity grant and vesting schedule" />

<h2 id="the-22-per-cent">The 22 Per Cent That Is Not Your Tax Bill</h2>

<p>When your RSUs vest, your employer withholds. Equity and bonuses are <em>supplemental wages</em>, and for 2026 the IRS employer guide states that the withholding rate on them "remains 22%", rising to 37 per cent on the portion of supplemental wages above $1 million in a calendar year. Where the flat method is used, the instruction is absolute: <strong>"Withhold a flat 22% (no other percentage allowed)."</strong></p>

<p>Bonuses are named in the same definition of supplemental wages, alongside commissions, overtime, severance, awards and back pay.</p>

<p>The practical consequence is the one that catches people in their first well-paid US year. Twenty-two per cent is a withholding rate set by rule, not a calculation of what you owe. If your marginal rate is higher than that &mdash; and on a large vest it often is &mdash; too little has been withheld and the difference is due at filing. The IRS does not put it in those words anywhere, so here is what it does say, from its guidance to taxpayers on withholding:</p>

<blockquote><p>"You should try to have your withholding match your actual tax liability. If not enough tax is withheld, you will owe tax at the end of the year and may have to pay interest and a penalty."</p></blockquote>

<p>Two related details worth knowing before a big vest. The 37 per cent rate above $1 million applies to the excess only, aggregates across businesses under common control, and is applied "without regard to the employee's Form W-4". And for Additional Medicare Tax, an employer must withhold on wages above $200,000 "without regard to the individual's filing status or wages paid by another employer", with the result that "an individual may owe more than the amount withheld by the employer". The fix in both directions is the same: adjust your W-4, or make an estimated payment.</p>

<p>One more timing point specific to a large vest. The Social Security wage base for <strong>2026 is $184,500</strong>, above which the 6.2 per cent stops; Medicare has no wage base at all. A vest that carries you over the base mid-year changes your take-home for the rest of it. The self-employment equivalent of all this, if you also contract on the side, is in our <a href="/blog/remote-react-developer-jobs-in-usa">remote React developer guide</a>.</p>

<h2 id="options">Options: Two Kinds, Taxed Completely Differently</h2>

<p>Smaller and earlier-stage employers grant options rather than units. Which kind you hold decides everything.</p>

<h3>Nonstatutory options (NSOs)</h3>
<p>Nothing at grant, for an option without a readily determinable market value. At exercise you "must include in income the fair market value of the stock received on exercise, less the amount paid". That spread is wages: it appears in box 12 of your W-2 with code V, and in boxes 1, 3 and 5.</p>

<h3>Incentive stock options (ISOs)</h3>
<p>For regular tax, the IRS is blunt: "If you exercise a statutory stock option, don't include any amount in income when you exercise the option." That is the attraction, and it comes with a trap most people meet only once:</p>

<blockquote><p>"For the AMT, you must treat stock acquired through the exercise of an ISO as if no special treatment applied... you must include as an adjustment in figuring alternative minimum taxable income the amount by which the FMV of the stock exceeds the option price. Enter this adjustment on Form 6251, line 2i."</p></blockquote>

<p>So exercising ISOs and holding can create a tax bill on money you have not received, in a year you received no cash. The same passage contains the escape, which is rarely reported: <strong>"no adjustment is required if you dispose of the stock in the same year you exercise the option."</strong> Exercise-and-sell in one calendar year, and the AMT adjustment does not arise.</p>

<p>Two more ISO rules worth carrying into a negotiation:</p>
<ul>
    <li><strong>The holding period for favourable treatment</strong> is statutory: no disposition "within 2 years from the date of the granting of the option nor within 1 year after the transfer of such share to him". The one-year clock runs from exercise, not from vesting.</li>
    <li><strong>The $100,000 limit.</strong> Where options becoming exercisable for the first time in a calendar year exceed $100,000 in value, the excess "shall be treated as options which are not incentive stock options". The IRS notes the value is measured "at the time the option is granted and not at the time the option vests".</li>
</ul>

<p>After the event you should receive an information return: <strong>Form 3921</strong> for an ISO exercise, and <strong>Form 3922</strong> for a transfer of stock acquired under an employee stock purchase plan. Keep both; the cost basis reported to you elsewhere is frequently wrong for equity compensation.</p>

<h3>ESPP, briefly</h3>
<p>If the offer includes a share purchase plan, two statutory limits define it. The purchase price may not be below 85 per cent of the market value &mdash; usually described as a maximum 15 per cent discount, though neither the statute nor the IRS publication uses that number, both expressing it as the 85 per cent floor. And rights may not accrue "at a rate which exceeds $25,000 of fair market value of such stock (determined at the time such option is granted) for each calendar year". Both limits are measured at grant, which is why the $25,000 is so often misdescribed as a cap on what you can spend.</p>

<img src="/public/storage/blogs/react-software-engineer-jobs-usa-offer.jpg" alt="A React software engineer comparing base salary, equity and bonus in a written offer" />

<h2 id="public-or-private">Is the Stock Worth Anything Yet?</h2>

<p>A grant from a listed company and a grant from a private one are different assets, and US securities law draws the line in a way you can check.</p>

<p>A public employer registers employee share offerings on <strong>Form S-8</strong>, which is available for "securities of the registrant to be offered under any employee benefit plan to its employees" &mdash; and only to a company already subject to Exchange Act reporting. A private company instead relies on <strong>Rule 701</strong>, which by its terms "is available to any issuer that is <em>not</em> subject to the reporting requirements" of the Exchange Act, and which caps what may be sold in any twelve months at the greatest of $1,000,000, 15 per cent of the issuer's total assets, or 15 per cent of the outstanding amount of that class.</p>

<p>Two things follow. First, which exemption your employer uses tells you which side of the line you are on. Second, as the rule's own preliminary note says, these transactions "are not exempt from the antifraud, civil liability, or other provisions of the federal securities laws" &mdash; you are entitled to accurate information about what you are being granted.</p>

<h2 id="what-to-ask">What To Ask Before You Accept</h2>

<ul>
    <li><strong>Units or options, and if options, ISO or NSO?</strong> These are three different tax outcomes wearing one word, "equity".</li>
    <li><strong>What is the vesting schedule and the cliff?</strong> And what happens to unvested equity if you leave, or are let go.</li>
    <li><strong>How is tax handled at vest?</strong> Many employers sell a portion of the shares to cover withholding. Ask whether that is automatic and at what rate.</li>
    <li><strong>What is the grant worth on what basis?</strong> For a private company, ask what valuation the number rests on and when it was set.</li>
    <li><strong>Is the bonus discretionary or formulaic?</strong> A discretionary bonus in a written offer is a number, not a commitment.</li>
    <li><strong>Where does the level sit on the ladder, and what is the band?</strong> Our <a href="/blog/senior-react-developer-jobs-in-usa">senior React guide</a> covers what seniority does and does not mean in US law.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do React software engineers make in the USA?</h3>
<p>No federal source publishes a wage for the title. BLS gives software developers a $135,980 median as of May 2025, with the top tenth above $214,670, and web developers $92,650. Those are base-pay occupation figures and do not include equity or bonus.</p>

<h3>When are RSUs taxed?</h3>
<p>At vesting, as ordinary wages. The IRS treats equity compensation such as RSUs as wages subject to income tax withholding, reported in box 1 of your W-2 and subject to FICA. You are taxed on the vest-date value whether or not you sell.</p>

<h3>Should I file an 83(b) election on my RSUs?</h3>
<p>You cannot. An RSU is an unfunded, unsecured promise, which 26 CFR 1.83-3(e) excludes from the definition of property, so no section 83(b) election can be made on it. The election applies to restricted stock, and must be filed within 30 days of transfer.</p>

<h3>Why was only 22 per cent withheld from my vest?</h3>
<p>Because equity and bonuses are supplemental wages, withheld at a flat 22 per cent, rising to 37 per cent above $1 million in a year. That is a withholding rate, not your liability. If your marginal rate is higher, the difference is due at filing.</p>

<h3>What is the difference between an ISO and an NSO?</h3>
<p>An NSO is taxed at exercise on the spread, as wages on your W-2. An ISO is not taxed at exercise for regular tax, but the spread is an AMT adjustment on Form 6251, line 2i.</p>

<h3>How do I avoid the AMT problem on ISOs?</h3>
<p>The regulation's own answer is that no adjustment is required if you dispose of the stock in the same year you exercise. Holding across a year end is what creates the exposure, and the favourable holding period requires two years from grant and one year from exercise.</p>

<h3>What are Forms 3921 and 3922?</h3>
<p>Form 3921 reports the exercise of an incentive stock option; Form 3922 reports a transfer of stock acquired under an employee stock purchase plan. Keep both, because reported cost basis for equity compensation is often wrong.</p>

<h3>How do I know if my private company shares are worth anything?</h3>
<p>Ask which exemption the plan uses. A reporting company registers on Form S-8; a non-reporting one relies on Rule 701, which caps twelve-month sales at the greatest of $1,000,000, 15 per cent of total assets, or 15 per cent of the class.</p>

<h2>People Also Search For</h2>

<h3>React software engineer salary USA</h3>
<p>Compare total compensation, not base. BLS publishes base-pay occupation medians of $135,980 for software developers and $92,650 for web developers.</p>

<h3>RSU tax USA</h3>
<p>Ordinary wage income at vesting, on the W-2, with FICA. Vest-date value sets the bill even if the price later falls.</p>

<h3>83(b) election RSU</h3>
<p>Not available. An RSU is not property under section 83. The election is for restricted stock and is due within 30 days of transfer.</p>

<h3>Supplemental wage withholding rate</h3>
<p>A flat 22 per cent, and 37 per cent on supplemental wages above $1 million in a calendar year, applied without regard to your W-4.</p>

<h3>ISO AMT adjustment</h3>
<p>The spread at exercise is an AMT adjustment on Form 6251, line 2i, unless you dispose of the stock in the same calendar year.</p>

<h3>Stock options tax USA</h3>
<p>An NSO is taxed at exercise as wages; an ISO is not, for regular tax. Which one you hold changes the whole calculation.</p>

<h3>ESPP rules USA</h3>
<p>A price floor of 85 per cent of market value, and accrual limited to $25,000 of stock value a year, both measured at grant.</p>

<h3>React engineer levels USA</h3>
<p>Numbered ladders are an employer construct. No federal source defines them, which the senior React guide covers.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a> &mdash; the main guide: the two occupations, the pay bands and the clearance filter.</li>
    <li><a href="/blog/senior-react-developer-jobs-in-usa">Senior React Developer Jobs in USA</a> &mdash; what seniority changes in law, including the overtime exemption.</li>
    <li><a href="/blog/react-front-end-engineer-jobs-in-usa">React Front End Engineer Jobs in USA</a> &mdash; which states force the base band into the advert, and how the remote rules reach out-of-state roles.</li>
    <li><a href="/blog/full-stack-react-developer-jobs-in-usa">Full Stack React Developer Jobs in USA</a> &mdash; what changes once you own the backend, and the data duties that come with it.</li>
    <li><a href="/blog/remote-react-developer-jobs-in-usa">Remote React Developer Jobs in USA</a> &mdash; the contractor side: self-employment tax, quarterly payments and misclassification.</li>
    <li><a href="/blog/react-js-developer-contract-jobs-in-usa">React JS Developer Contract Jobs in USA</a> &mdash; the staffing layer, where there is no equity and the pay rules are different.</li>
    <li><a href="/blog/frontend-developer-coding-tests-in-usa">Frontend Developer Coding Tests in USA</a> &mdash; the assessment stage these ladders hire through.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li>Bureau of Labor Statistics &mdash; Occupational Outlook Handbook for software developers and for web developers and digital designers, May 2025 wages.</li>
    <li>Internal Revenue Service &mdash; Publication 525 (2025), Publication 15 (2026), Publication 505 (2026), Publication 5992 (Equity (Stock)-Based Compensation Audit Technique Guide) and Information Letter 2024-0010.</li>
    <li>26 CFR 1.83-2 and 1.83-3; 26 U.S.C. 422 and 423; IRS Tax Topic 427 on stock options.</li>
    <li>Social Security Administration &mdash; contribution and benefit base for 2026; and the IRS questions and answers on the Additional Medicare Tax.</li>
    <li>Securities and Exchange Commission &mdash; Form S-8; and 17 CFR 230.701.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not tax, legal or investment advice. Tax rules, thresholds and wage data change, and an individual position can turn on facts this page cannot know &mdash; confirm the current position with the Internal Revenue Service and a qualified adviser before relying on any of it.</p>
HTML;
    }
}
