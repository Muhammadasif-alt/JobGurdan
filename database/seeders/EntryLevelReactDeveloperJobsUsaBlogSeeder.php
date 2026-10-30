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
 * Entry level React developer work in the USA, checked on 29 September 2026.
 *
 * A spoke off the React developer hub, which keeps the head term. The hub
 * lists what separates junior candidates; this page answers the question
 * underneath it, which is whether the door is open at all, and what the
 * official record actually says about that.
 *
 * The spine: the BLS Quick Facts tables for both occupations React work is
 * counted in record "Work Experience in a Related Occupation: None" and
 * "On-the-job Training: None". No brief mentioned this, and it is the single
 * most useful fact a candidate with no job history can be given.
 *
 * Corrections applied to the supplied brief:
 *  1. Every Indeed and LinkedIn link removed, with the 1,624 and 435 vacancy
 *     counts they were quoted to support.
 *  2. The $100,265 entry level average, the $88,976 junior average and the
 *     $63,500 to $106,000 band are ZipRecruiter figures and are not
 *     republished. BLS publishes no wage for a junior React title, so the page
 *     uses tenth percentile figures and labels them as such rather than
 *     passing them off as entry level pay.
 *  3. The brief's skill and requirement lists were drawn from job board
 *     postings. Replaced with React's own documentation, which is the source
 *     that actually decides whether a portfolio looks current.
 *  4. Four FAQs and no People Also Search For block. Now eight of each.
 *
 * Deliberately not claimed: that React states JavaScript prerequisites. It
 * publishes no such statement, only a pointer to MDN for unfamiliar syntax.
 * Nor is any count of apprentices or apprenticeship programmes given, because
 * apprenticeship.gov could not be reached; the regulation at 29 CFR Part 29
 * and a Department of Labor bulletin carry the claims instead.
 *
 * Merged on 30 October 2026 with the internship and how-to-apply guides, whose old URLs 301 here (config/blog_redirects.php).
 */
class EntryLevelReactDeveloperJobsUsaBlogSeeder extends Seeder
{
    public const SLUG = 'entry-level-react-developer-jobs-in-usa';

    public const APPLY_URL = 'https://www.usajobs.gov/Search/Results?k=junior%20software%20developer';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'US Junior and Graduate React Employers (Aggregated)'],
                ['type' => 'Private', 'display_reference' => 'us-entry-react-aggregated']
            );
            $location = Location::firstOrCreate(
                ['name' => 'United States'],
                ['area' => 'Nationwide', 'country' => 'United States']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Entry Level React Developer, US Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Apprenticeship',
                    'job_type' => 'On-site / Hybrid / Remote',
                    'work_hours' => 'Standard US business hours, with supervised work and code review expected',
                    'language' => 'English',
                    // BLS publishes no wage line for a junior React title, and
                    // aggregator averages are barred, so nothing is asserted.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'entry level react developer jobs, junior react developer jobs usa, react developer jobs no experience, react apprenticeship usa, react portfolio projects',
                    'meta_description' => 'Entry level and junior React developer roles with US employers, including graduate tracks, supervised production work and registered apprenticeships.',
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
                    'title' => 'Entry Level React Developer Jobs in USA: How to Get Your First Role',
                    'excerpt' => 'The federal record is blunter than the adverts. For both occupations React work is counted in, the Bureau of Labor Statistics lists work experience as None and on-the-job training as None. The barrier is not your CV. It is what you can show.',
                    'content' => $content,
                    'featured_image' => 'blogs/entry-level-react-developer-jobs-usa.jpg',
                    'tags' => 'entry level react developer jobs, junior react developer jobs usa, react developer jobs no experience, react apprenticeship usa, react portfolio projects, graduate developer jobs usa, first developer job usa, react developer career start',
                    'meta_title' => 'Entry Level React Developer Jobs in USA: Your First Role',
                    'meta_description' => 'Your first React job in the USA: what BLS records about experience, what to build, whether an internship must pay, and how the application process works.',
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
<p>This is an overview of entry level and junior React developer work with employers across the United States. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Entry level React work appears in three shapes: graduate and junior engineering roles at product companies, supervised production roles at agencies and consultancies, and registered apprenticeships, which are paid jobs carrying a nationally recognised credential.</p>
<p>The federal search linked here covers the government side of this market. The commercial market is larger, and the guide explains how to read a junior advert from either.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every junior developer hears the same thing: every role wants experience, and you cannot get experience without a role. It is worth checking that against the official record, because the official record does not say it.</p>

<p>This guide takes you from a standing start to a first React job in the USA, in three parts: what the first job actually requires and what to build, how an internship works and whether it has to pay you, and how the application process runs, including the parts of it that are written down in law. Our <a href="/blog/react-developer-jobs-in-usa">React developer guide</a> covers the market as a whole.</p>

<h2 id="bls-experience-requirement">What the Federal Record Actually Requires</h2>

<p>React roles are counted in one of two Bureau of Labor Statistics occupations. Both publish a Quick Facts table, and on the two rows that matter here <strong>both say the same thing</strong>:</p>

<ul>
    <li><strong>Web Developers and Digital Designers</strong>: Work Experience in a Related Occupation: <strong>None</strong>. On-the-job Training: <strong>None</strong>.</li>
    <li><strong>Software Developers, Quality Assurance Analysts, and Testers</strong>: Work Experience in a Related Occupation: <strong>None</strong>. On-the-job Training: <strong>None</strong>.</li>
</ul>

<p>Both list a bachelor's degree as the typical entry-level education. But the web developer page qualifies even that, and the qualification is the most quotable sentence on it for anyone without a degree: educational requirements "range from a high school diploma to a bachelor's degree", and web developers and digital designers <strong>"may not need specific education credentials if they can demonstrate their abilities through prior work experience or projects"</strong>.</p>

<p>Read those together and the position is clear. The occupation has no structural experience requirement. Individual employers impose one, and many do. But "3+ years required" is an employer's preference, not a rule of the field, and the official alternative it offers you is <em>projects</em>.</p>

<h2 id="entry-level-pay">The Pay Floor, Honestly Labelled</h2>

<p>There is no federal wage line for "junior React developer", and the averages circulating for that title come from job boards, which this site does not republish. What does exist is the bottom of each occupation, as of <strong>May 2025</strong>:</p>

<ul>
    <li><strong>Web developers</strong>: median $92,650, with the lowest 10 per cent earning less than <strong>$48,100</strong> and the highest 10 per cent more than $162,290.</li>
    <li><strong>Software developers</strong>: median $135,980, with the lowest 10 per cent earning less than <strong>$82,460</strong> and the highest 10 per cent more than $214,670.</li>
</ul>

<p>Those tenth percentile numbers are not "entry level pay", and it would be dishonest to present them that way. They are the bottom decile of everyone in the occupation, which includes people well past their first job. What they are useful for is calibration: a first offer near $48,100 is inside the distribution rather than an insult, and the two occupations start from very different floors, which is why it matters which one an advert is benchmarked against.</p>

<p>On outlook, the two also differ more than people expect. Web developers and digital designers are projected to grow <strong>5 per cent from 2025 to 2035</strong>, with about <strong>13,600 openings</strong> a year. Software developers, QA analysts and testers are projected to grow <strong>10 per cent</strong> over the same decade, with about <strong>106,100 openings</strong> a year.</p>

<img src="/public/storage/blogs/entry-level-react-developer-jobs-usa-portfolio.jpg" alt="Entry level React developer portfolio projects on screen" />

<h2 id="react-portfolio">The Portfolio Is the Credential, So Build the Right Thing</h2>

<p>If projects are the official substitute for a credential, the projects have to be current. A hiring manager can check that in about thirty seconds, and most juniors fail on it without knowing.</p>

<p>React's own documentation is unambiguous about how a new project should start: <strong>"If you want to build a new app or website with React, we recommend starting with a framework."</strong> It names three:</p>

<ul>
    <li><strong>Next.js (App Router)</strong>: "a React framework that takes full advantage of React's architecture to enable full-stack React apps"</li>
    <li><strong>React Router (v7)</strong>: "the most popular routing library for React and can be paired with Vite to create a full-stack React framework"</li>
    <li><strong>Expo</strong>: for "universal Android, iOS, and web apps with truly native UIs"</li>
</ul>

<p>If you would rather not use a framework, React points you at a build tool: <strong>Vite, Parcel or RSbuild</strong>.</p>

<p>The common objection is that a framework means you need a server. React answers it directly: all the frameworks it recommends "support client-side rendering (CSR) and single-page apps (SPA), and can be deployed to a CDN or static hosting service without a server".</p>

<h2 id="create-react-app">The One Thing That Dates You Instantly</h2>

<p><strong>Create React App is deprecated.</strong> React's installation page answers the question in three words: "No. Create React App has been deprecated."</p>

<p>The announcement was published on <strong>14 February 2025</strong>: "Today, we're deprecating Create React App for new apps, and encouraging existing apps to migrate to a framework, or to migrate to a build tool like Vite, Parcel, or RSBuild."</p>

<p>A portfolio built on Create React App still runs. It also tells a reviewer, before they read a line of your code, that your information is more than a year out of date. If you have CRA projects you are proud of, migrating one is itself a portfolio-worthy exercise and gives you something specific to talk about in an interview.</p>

<h2 id="learn-react">A Learning Order That Is Not Someone's Opinion</h2>

<p>React publishes its own curriculum, which is more reliable than any roadmap graphic. The Learn React section runs in four chapters, in this order:</p>

<ul>
    <li><strong>Describing the UI</strong></li>
    <li><strong>Adding Interactivity</strong></li>
    <li><strong>Managing State</strong></li>
    <li><strong>Escape Hatches</strong></li>
</ul>

<p>The Quick Start describes itself as "an introduction to 80% of the React concepts that you will use on a daily basis", and the Tic-Tac-Toe tutorial states that it "does not assume any existing React knowledge".</p>

<p>On JavaScript, be careful with what you are told. React publishes no formal prerequisite statement. What it does say, in the Quick Start, is: "If you're not familiar with some piece of JavaScript syntax, MDN and javascript.info have great references." That is a pointer, not a gate. In practice most failed React interviews are failed JavaScript interviews, so the advice stands on its own merits, but do not let anyone tell you React requires a syllabus it has never published.</p>

<h2 id="react-apprenticeship">The Paid Route Almost Nobody Looks At</h2>

<p>Registered Apprenticeship is a federal programme, and software work is inside it. The Department of Labor describes it as "an industry-driven, high-quality career pathway where employers can develop and prepare their future workforce, and individuals can obtain paid work experience, classroom instruction, and a portable, nationally-recognized credential", combining "on-the-job training with a steady paycheck".</p>

<p>The federal regulation at <strong>29 CFR Part 29</strong> sets the terms, and they are worth knowing before you are offered something calling itself an apprenticeship:</p>

<ul>
    <li><strong>You are paid, by regulation.</strong> Programmes must set "a progressively increasing schedule of wages to be paid to the apprentice consistent with the skill acquired", and "the entry wage must not be less than the minimum wage prescribed by the Fair Labor Standards Act, where applicable".</li>
    <li><strong>It is substantial.</strong> An apprenticeable occupation must require at least <strong>2,000 hours</strong> of on-the-job supervised learning.</li>
    <li><strong>Classroom time is part of it.</strong> "A minimum of 144 hours for each year of apprenticeship is recommended" in related instruction.</li>
    <li><strong>The minimum age is 16.</strong></li>
</ul>

<p>Software is covered in practice, not just in theory: a Department of Labor Office of Apprenticeship bulletin publishes full work-process schedules for IT Specialist roles covering application software, with tasks including writing, testing and debugging programs and designing web pages. The Department also runs an official occupation finder at apprenticeship.gov.</p>

<p>An unpaid "apprenticeship" is not a Registered Apprenticeship. If someone offers you one, that is the question to ask.</p>

<img src="/public/storage/blogs/entry-level-react-developer-jobs-usa-first-role.jpg" alt="Junior React developer starting a first role with a supervising engineer" />

<h2 id="reading-a-junior-advert">Reading a Junior Advert</h2>

<p>Four things to look for, in this order:</p>

<ul>
    <li><strong>Which occupation is it benchmarked against?</strong> An advert about implementing designs sits near the web developer floor. One about owning features sits near the software developer floor, and those floors are $34,000 apart.</li>
    <li><strong>Is there anyone to learn from?</strong> A junior role with no senior engineer on the team is not a junior role; it is a solo role with a junior title and a junior salary.</li>
    <li><strong>Is the "3+ years" a requirement or a wish?</strong> Adverts routinely list a preference as a bar. If the rest of the advert describes supervised work and code review, it is a wish.</li>
    <li><strong>Does it name a stack you can evidence?</strong> Apply where your projects match. Two strong matching applications beat forty generic ones, and the forty cost you the time the two needed.</li>
</ul>

<p>If you are applying from outside the US, read the eligibility line before anything else. A large part of the federal and federal-adjacent market requires US citizenship, which the <a href="/blog/react-developer-jobs-in-usa">main React guide</a> covers in full.</p>

<h2 id="beyond-react">What Junior Adverts Ask For Beyond React</h2>

<p>Junior React adverts rarely stop at React, and the additions are worth reading carefully, because one of them has a published standard behind it that most candidates get slightly wrong.</p>

<ul>
    <li><strong>Accessibility, and which version of it.</strong> Adverts increasingly ask for WCAG 2.2 rather than 2.1. These are not the same bar, and US law currently sits on the older one: the Department of Justice's ADA Title II rule requires <strong>WCAG 2.1 Level AA</strong>, while <strong>WCAG 2.2 became a W3C Recommendation on 12 December 2024</strong> and adds <strong>nine success criteria</strong> on top of 2.1. Only four of those nine are Level AA, and they are the ones worth being able to name: <em>Focus Not Obscured (Minimum)</em>, <em>Dragging Movements</em>, <em>Target Size (Minimum)</em> and <em>Accessible Authentication (Minimum)</em>. An advert asking for 2.2 is asking for more than the federal rule requires: knowing that, and knowing which four criteria account for the difference, is a genuinely strong answer in a junior interview.</li>
    <li><strong>TypeScript rather than JavaScript alone.</strong> Treat this as part of the core ask now, not an extra. It is the same language plus a type checker, so it is a days-to-weeks addition for someone who already knows JavaScript, which makes it one of the cheapest gaps on a junior application to close.</li>
    <li><strong>A component system, not loose components.</strong> Adverts naming a design system, or an existing library of primitives, are describing maintenance of a shared surface rather than building screens. If your portfolio has one reusable, documented component set behind two projects, say so; it answers this directly.</li>
    <li><strong>Tests.</strong> A junior is rarely expected to design a test strategy, but is expected to write a test for the component they just wrote, and to be able to read a failing run. One project with real tests evidences it.</li>
</ul>

<p>One thing worth not being surprised by: a junior title can still carry a clearance requirement. Government-adjacent contractors attach eligibility conditions by the work rather than by the seniority of the post, so a first job can ask for US citizenship and a degree that a private-sector equivalent would not. The <a href="/blog/react-developer-jobs-in-usa">main React guide</a> sets out how that eligibility filter actually works, including the point that you cannot apply for a clearance yourself.</p>

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

<h2 id="what-the-pay-figures-mean">What an Advertised Intern Pay Figure Actually Means</h2>

<p>Two traps, both common enough to be worth naming.</p>

<p><strong>An annualised figure is not what you will be paid.</strong> Large employers frequently state intern pay as an annual salary equivalent even where the internship runs twelve weeks. A twelve-week placement pays roughly a quarter of an annual figure, and the advert is not being dishonest: it is quoting a rate. Convert it to the hours you will actually work before comparing offers.</p>

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

<p>The alternative is optional practical training taken before you finish. The total OPT allowance is <strong>12 months</strong>, and any pre-completion OPT used is <strong>deducted from the post-completion period</strong>. So this route spends the same allowance you would otherwise use after graduating, which is why CPT is usually the better instrument for a term-time or summer internship: up to the one-year limit above.</p>

<p>If the internship is offered through a staffing or consulting firm, the rules that apply once you are inside one are set out in our <a href="/blog/react-js-developer-contract-jobs-in-usa">React JS contract jobs guide</a>, including the ones about being paid when there is no assigned work.</p>

<img src="/public/storage/blogs/react-developer-internship-jobs-usa-paid.jpg" alt="A student comparing paid React developer internship offers and terms" />

<h2 id="finding-and-preparing">Finding an Internship, and Being Ready For It</h2>

<p><strong>Search the titles, not the technology.</strong> "React intern" returns a fraction of this market. The same role is advertised as software engineering intern, frontend engineering intern, web development intern, SDE intern, application developer intern, and under university recruiting, student programmes, early career and new graduate headings. Search all of them on the employer's own careers site.</p>

<p><strong>Read the eligibility line before the technology line.</strong> Internship programmes frequently require current enrolment, a specific graduation window, and at least one academic term remaining afterwards. A strong candidate who graduates in the wrong month is ineligible, and no amount of React changes that.</p>

<p><strong>Build one complete thing rather than five partial ones.</strong> A deployed application with an API, real error and loading states, a test or two and a README that explains the decisions will out-argue a row of tutorial clones. Say plainly what you personally built if it was a group project. The portfolio section above covers what to build in more detail, including which toolchain dates a portfolio instantly.</p>

<p><strong>Expect the assessment stage.</strong> Internship processes usually include a coding exercise. That stage is regulated, and if you need an adjustment for a disability you are entitled to ask for one: in plain English, at any point, without putting it in writing. Our <a href="/blog/frontend-developer-coding-tests-in-usa">guide to coding tests</a> sets out what applies.</p>

<p><strong>Never pay to get an internship.</strong> Placement services charging students for "guaranteed" internships are a recurring scam, and the position is the same as for any job: an honest employer, including the federal government, will not ask you to pay to be hired.</p>

<h2 id="two-hiring-systems">You Are Applying Into One of Two Systems</h2>

<p>American React work is hired through two quite different machines, and candidates lose time by treating them as one:</p>

<ul>
    <li><strong>Commercial hiring.</strong> The employer designs its own process. Typically a CV and portfolio screen, a recruiter call, a take-home or live coding exercise, a system design conversation for senior roles, and an offer. Speed varies from days to months.</li>
    <li><strong>Federal competitive hiring.</strong> A published procedure with defined stages, run by or under the rules of the Office of Personnel Management. It is slower, far more structured, and it does not reward the things commercial applications reward.</li>
    </ul>

<p>A large slice of US React work sits on the federal side, through agencies and the contractors who build for them. Before you invest in it, check the eligibility line: much of it requires US citizenship or a clearance, which the <a href="/blog/react-developer-jobs-in-usa">main React guide</a> sets out in full.</p>

<h2 id="federal-hiring-process">The Federal Process, Stage by Stage</h2>

<p>OPM's competitive hiring process runs in six stages:</p>

<ul>
    <li><strong>Vacancy announcement</strong>: the public notice of the opening, with its own open period.</li>
    <li><strong>Application and assessment</strong>: candidates apply and are evaluated, which can mean written tests or a review of education and experience.</li>
    <li><strong>Rating and ranking</strong>: applicants are rated against job-related competencies.</li>
    <li><strong>Certification</strong>: eligible candidates are certified to the hiring manager.</li>
    <li><strong>Selection</strong>: the manager selects from the certified candidates.</li>
    <li><strong>Appointment</strong>: the successful candidate receives a career or career-conditional appointment.</li>
</ul>

<p>Two features of that process explain most of the frustration people report with it.</p>

<p><strong>Category rating replaced the old rule of three.</strong> The traditional approach let a manager pick only from the top three scorers. Under category rating, agencies "evaluate candidates and place them into two or more pre-determined quality categories", and the manager may select anyone in the highest category. So your goal is not to be ranked first. It is to land in the top category, which is a different target and a more achievable one.</p>

<p><strong>Veterans' preference is not a tiebreak.</strong> Preference eligibles "receive absolute preference within each category", and an agency "may not select a non-preference eligible unless the agency requests to pass over the preference eligible". If you are not a veteran, that is simply part of the landscape. If you are, it is worth far more than most applicants realise.</p>

<p>The practical consequence for a React developer: the federal application is assessed against stated competencies, not against how impressive your portfolio feels. Answer what the announcement asks, in the terms it asks it, and do it before the announcement closes.</p>

<img src="/public/storage/blogs/how-to-apply-react-developer-jobs-usa-application.jpg" alt="Completing a React developer job application in the United States" />

<h2 id="what-employers-cannot-ask">What an Employer Is Not Allowed to Ask</h2>

<p>The laws the EEOC enforces protect applicants, not only employees. The protected characteristics are <strong>"race, color, religion, sex (including transgender status, sexual orientation, and pregnancy), national origin, age (40 or older), disability or genetic information"</strong>, along with protection from retaliation for having complained about discrimination or taken part in an investigation.</p>

<p>What that rules out at application stage:</p>

<ul>
    <li><strong>Job adverts that signal a preference.</strong> An employer may not "publish a job advertisement that shows a preference for or discourages someone from applying for a job" on those grounds. The EEOC's own examples include adverts seeking "females" or <strong>"recent college graduates"</strong>, which is worth knowing in a field where that phrasing is common.</li>
    <li><strong>Recruitment that filters people out.</strong> Recruiting "in a way that discriminates" is unlawful, and the EEOC names word-of-mouth hiring that produces a homogeneous workforce as a possible violation.</li>
    <li><strong>Hiring on assumptions.</strong> Decisions may not be "based on stereotypes and assumptions" about protected characteristics.</li>
    <li><strong>Disability questions before an offer.</strong> Employers are "explicitly prohibited from making pre-offer inquiries about disability".</li>
</ul>

<p>The governing principle for the whole application stage: "the information obtained and requested through the pre-employment process should be limited to those essential for determining if a person is qualified for the job".</p>

<p>On salary history, be careful with what you read. <strong>There is no single federal ban.</strong> Restrictions exist, but they are state and local law and they differ, so check the rule where the role is based rather than assuming either way.</p>

<h2 id="background-check-rights">The Background Check Has Rules, and They Are Mostly Yours</h2>

<p>If an employer uses a consumer reporting agency to check you, the Fair Credit Reporting Act applies, and it is unusually specific.</p>

<p><strong>Before the check</strong>, the employer must tell you it "might use the information for decisions about his or her employment", and that notice "must be in writing and in a stand-alone format": it cannot be buried in the application form. It must then "get the applicant's or employee's written permission to do the background check".</p>

<p><strong>Before rejecting you</strong> because of what the report says, the employer must give you a pre-adverse action notice that includes <strong>a copy of the consumer report it relied on</strong> and <strong>a copy of "A Summary of Your Rights Under the Fair Credit Reporting Act"</strong>. The point of that step is to let you see the report and challenge anything wrong in it <em>before</em> the decision is final.</p>

<p><strong>After rejecting you</strong>, it must tell you that the decision was based on the report, give you the reporting company's name, address and phone number, state that the reporting company did not make the decision, and tell you that you may dispute the report's accuracy and get a free copy within 60 days.</p>

<p>Inaccurate background reports are common. If you are rejected after a check and receive none of that, something has gone wrong in the employer's process, and the report itself is worth obtaining.</p>

<h2 id="work-authorisation">Work Authorisation and Form I-9</h2>

<p>Every US employer must verify that a new hire is authorised to work, using <strong>Form I-9</strong>. The timing is fixed: "within three business days of the date employment begins", the employer or an authorised representative must complete Section 2, and if someone is hired for fewer than three business days, Section 2 must be completed "no later than the first day of employment".</p>

<p>Employers who fail to complete the form properly risk violating section 274A of the Immigration and Nationality Act and civil penalties, and they may terminate an employee who does not present acceptable documentation inside the three-day window.</p>

<p>Two things follow. Have your documents ready before the start date, because the clock is short and it is not negotiable. And note that this is a verification step after hiring, not a screening question at application: an employer asking for immigration documents before an offer is not following the normal sequence.</p>

<img src="/public/storage/blogs/how-to-apply-react-developer-jobs-usa-interview.jpg" alt="React developer technical interview with a live coding exercise" />

<h2 id="commercial-pipeline">The Commercial Pipeline, and What Each Stage Is Testing</h2>

<p>Nothing regulates this one, so it rewards preparation instead:</p>

<ul>
    <li><strong>The screen.</strong> A human spends well under a minute. Deployed project links beat repository links, because a live URL gets clicked and a repository often does not.</li>
    <li><strong>The recruiter call.</strong> Ask which occupation the role is benchmarked against and whether there is a published band. Both answers change what the rest of the process is worth to you.</li>
    <li><strong>The take-home.</strong> Scope it before you start, and ask how long they expect it to take. Handle error and loading states; that is usually what separates submissions more than styling does.</li>
    <li><strong>The live exercise.</strong> Narrate your reasoning. Most interviewers are scoring how you think far more than whether you finish.</li>
    <li><strong>System design, for senior roles.</strong> Rendering strategy, where state lives, and what you would choose not to build.</li>
</ul>

<p>If the role is remote, settle the employment shape before the offer, because it changes your take-home pay substantially: our <a href="/blog/remote-react-developer-jobs-in-usa">remote React guide</a> works through it.</p>

<h2 id="job-scams">Before You Send Anything, Check It Is Real</h2>

<p>Developer job scams are common, and the FTC gives one test that settles most of them: <strong>"Honest employers, including the federal government, will never ask you to pay to get a job. Anyone who does is a scammer."</strong></p>

<p>Alongside that:</p>

<ul>
    <li><strong>Money for nothing.</strong> "If someone offers you a job and claims that you can make a lot of money in a short period of time with little work, that's almost certainly a scam."</li>
    <li><strong>Cheques you are asked to forward.</strong> "No honest potential employer will ever send you a check to deposit and then tell you to send on part of the money." The cheque bounces later and you owe the bank the money.</li>
    <li><strong>Fees for equipment, training or certification.</strong> A real employer buys its own laptops.</li>
</ul>

<p>The FTC's own advice is to search the company independently and use official sources rather than responding to an unsolicited offer. For federal roles that means USAJOBS. For commercial ones, apply through the employer's own careers page wherever you can, and be wary of any process that moves to a messaging app and asks for bank details before an offer exists.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a React developer job with no experience?</h3>
<p>The occupation does not require it. The Bureau of Labor Statistics lists work experience in a related occupation as None, and on-the-job training as None, for both occupations React work is counted in. Individual employers set their own bars, but the field has no structural one.</p>

<h3>How much do entry level React developers earn in the USA?</h3>
<p>There is no federal wage for a junior React title. As calibration, the lowest 10 per cent of web developers earned less than $48,100 in May 2025, and the lowest 10 per cent of software developers less than $82,460. Those are occupation-wide tenth percentiles, not entry level averages.</p>

<h3>Are there paid React apprenticeships in the USA?</h3>
<p>Registered Apprenticeship covers IT and software work, and payment is a regulatory requirement: programmes must set a progressively increasing wage schedule with an entry wage not below the FLSA minimum. An unpaid apprenticeship is not a Registered Apprenticeship.</p>

<h3>Is junior different from entry level?</h3>
<p>Mostly they are the same posting under two names, so search both. "Entry level" appears more often on graduate engineering tracks and "junior" on supervised agency work, but it is a tendency and not worth filtering on.</p>

<h3>Do React developer internships have to be paid?</h3>
<p>At a for-profit employer, usually yes. The FLSA requires for-profit employers to pay employees, and whether an intern is an employee is decided by the seven-factor primary beneficiary test. If the test says employee, minimum wage and overtime are owed.</p>

<h3>Does CPT affect my OPT later?</h3>
<p>It can, permanently. Students who receive one year or more of full-time CPT are ineligible for post-completion practical training. Part-time CPT and shorter full-time periods do not trigger it.</p>

<h3>How do I apply for a React developer job in the USA?</h3>
<p>Through one of two systems. Commercial employers run their own process, usually a portfolio screen, a recruiter call, a take-home or live exercise and an offer. Federal agencies run OPM's competitive hiring process, which has six published stages and closes on a fixed date.</p>

<h3>How do I tell if a developer job advert is a scam?</h3>
<p>The clearest test is money. The FTC states that honest employers, including the federal government, will never ask you to pay to get a job. Treat forwarded cheques, equipment fees and quick-money promises as scams.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a>: the main guide: pay bands, the toolchain that dates you, and the clearance filter on federal work.</li>
    <li><a href="/blog/remote-react-developer-jobs-in-usa">Remote React Developer Jobs in USA</a>: W-2 against 1099, self-employment tax, and which state ends up taxing you.</li>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a>: the occupation with the lower floor, and how freelance rates work against US tax.</li>
    <li><a href="/blog/front-end-developer-jobs-in-usa">Front End Developer Jobs in USA</a>: accessibility law and Core Web Vitals, the two measurable skills that move an offer.</li>
    <li><a href="/blog/frontend-developer-coding-tests-in-usa">Frontend Developer Coding Tests in USA</a>: the coding test and take-home stage, and the accommodation you are entitled to ask for.</li>
    <li><a href="/blog/senior-react-developer-jobs-in-usa">Senior React Developer Jobs in USA</a>: the far end of the same ladder, and the overtime exemption the grade crosses into.</li>
    <li><a href="/blog/react-js-developer-contract-jobs-in-usa">React JS Developer Contract Jobs in USA</a>: the staffing route many first roles arrive through, and the STEM OPT rule agencies get wrong.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://www.bls.gov/ooh/computer-and-information-technology/web-developers.htm" target="_blank" rel="noopener nofollow">BLS, Web Developers and Digital Designers</a></li>
    <li><a href="https://www.bls.gov/ooh/computer-and-information-technology/software-developers.htm" target="_blank" rel="noopener nofollow">BLS, Software Developers, Quality Assurance Analysts, and Testers</a></li>
    <li><a href="https://react.dev/learn/creating-a-react-app" target="_blank" rel="noopener nofollow">React, Creating a React App</a></li>
    <li><a href="https://react.dev/blog/2025/02/14/sunsetting-create-react-app" target="_blank" rel="noopener nofollow">React, Sunsetting Create React App</a></li>
    <li><a href="https://www.dol.gov/agencies/odep/program-areas/apprenticeship" target="_blank" rel="noopener nofollow">US Department of Labor, Apprenticeship</a></li>
    <li><a href="https://www.apprenticeship.gov/apprenticeship-occupations" target="_blank" rel="noopener nofollow">Apprenticeship.gov, apprenticeable occupations</a></li>
    <li><a href="https://www.opm.gov/policy-data-oversight/hiring-information/competitive-hiring/" target="_blank" rel="noopener nofollow">OPM, competitive hiring process</a></li>
    <li><a href="https://www.eeoc.gov/prohibited-employment-policiespractices" target="_blank" rel="noopener nofollow">EEOC, prohibited employment policies and practices</a></li>
    <li><a href="https://www.ftc.gov/business-guidance/resources/background-checks-what-employers-need-know" target="_blank" rel="noopener nofollow">FTC, background checks and the Fair Credit Reporting Act</a></li>
    <li><a href="https://consumer.ftc.gov/articles/job-scams" target="_blank" rel="noopener nofollow">FTC, job scams</a></li>
    <li><a href="https://www.uscis.gov/i-9-central" target="_blank" rel="noopener nofollow">USCIS, I-9 Central</a></li>
    <li><a href="https://www.usajobs.gov/" target="_blank" rel="noopener nofollow">USAJOBS, the official federal job site</a></li>
    <li>Department of Labor, Wage and Hour Division: Fact Sheet #71 on internship programs under the Fair Labor Standards Act, and Field Assistance Bulletin 2018-2.</li>
    <li>8 CFR 214.2(f)(10): curricular practical training and optional practical training for F-1 students.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or careers advice. Wage data, hiring rules and library recommendations change: confirm the current position with the official sources above and the employer before relying on any of it.</p>
HTML;
    }
}
