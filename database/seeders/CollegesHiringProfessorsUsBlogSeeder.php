<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * "Colleges Hiring Professors in the US" — the institution-type page. The
 * faculty hub owns the appointment types, the professor employment guide
 * owns the contract and the assistant professor guide owns the search. This
 * page answers the only question the others leave open: which kind of
 * college will hire someone with your credential, what it pays, and where
 * it publishes its vacancies.
 *
 * Corrections to the draft:
 *
 * 1. The draft lists institution types as interchangeable options. They are
 *    not. The BLS median moves $27,960 between a state university and a
 *    state junior college, and the AAUP per-section rate moves from $5,115
 *    at doctoral institutions to $3,348 at associate's institutions without
 *    ranks. The institution decides the pay more than the discipline does.
 *
 * 2. It says "some community-college teaching positions may accept a
 *    relevant master's degree" without the actual credential rule most
 *    regional accreditors apply, which is a master's plus eighteen graduate
 *    semester hours in the teaching discipline.
 *
 * 3. It sends readers to "higher-education job boards" generically. US
 *    institutions publish on their own HR systems first, and public systems
 *    often publish to a state-wide portal that no board syndicates in full.
 *
 * 4. It treats online colleges as a straightforward option. State
 *    authorisation and payroll registration decide where an online
 *    instructor may live, which is why those adverts list eligible states.
 *
 * 5. It gives no hiring seasons, although community colleges and
 *    four-year institutions run on different calendars.
 *
 * 6. It never warns about the one sector where "hiring professors" adverts
 *    are most often course-by-course piecework rather than employment.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class CollegesHiringProfessorsUsBlogSeeder extends Seeder
{
    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Colleges Hiring Professors in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The institution decides the pay more than the discipline does: $96,120 at a state university against $68,160 at a state junior college. Which colleges hire on which credential, what each pays, and where each publishes.',
                'content' => $content,
                'featured_image' => 'blogs/colleges-hiring-professors-in-the-us.jpg',
                'tags' => 'colleges hiring professors us, community college faculty jobs, university faculty openings, college professor jobs usa, teaching with a masters degree, online faculty jobs, liberal arts college jobs, academic hiring season',
                'meta_title' => 'Colleges Hiring Professors in the US: Who Hires and Pays',
                'meta_description' => 'Colleges hiring professors in the US: which institutions hire on a masters, what each type pays from $96,120 to $68,160, and where vacancies appear.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you are searching for colleges hiring professors, the useful question is not which ones are recruiting this month. It is which kind of institution will hire someone with your credential, what that kind of institution pays, and where it publishes. Those three things move together, and they vary far more between institution types than between academic disciplines.</p>

<h2>What Each Kind of College Pays</h2>

<p>The federal wage data makes the point on its own. These are BLS median annual wages for postsecondary teachers, May 2025, by the industry the institution sits in:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Institution</th>
            <th style="padding:10px;text-align:left;">Median salary</th>
            <th style="padding:10px;text-align:left;">Usual credential for a full-time post</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges, universities and professional schools, state</td><td style="padding:10px;">$96,120</td><td style="padding:10px;">Doctorate, in hand by the start date</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Colleges, universities and professional schools, private</td><td style="padding:10px;">$89,660</td><td style="padding:10px;">Doctorate; terminal professional degree in applied fields</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, local</td><td style="padding:10px;">$81,640</td><td style="padding:10px;">Master's in the discipline, commonly with graduate credit hours specified</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Junior colleges, state</td><td style="padding:10px;">$68,160</td><td style="padding:10px;">Master's in the discipline</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All postsecondary teachers</strong></td><td style="padding:10px;"><strong>$85,330</strong></td><td style="padding:10px;">&mdash;</td></tr>
    </tbody>
</table>
</div>

<p>That is a spread of $27,960 between the top and bottom rows for the same occupation. Across the whole occupation, the lowest tenth earn under $49,540 and the highest tenth over $203,580, and the institution you work for explains most of that distance.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/colleges-hiring-professors-in-the-us-campus.jpg"
         alt="An academic with a briefcase and books walking through a US campus, with a lecture hall, a student group and a globe in a collage around him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Part-Time Rates Move With the Institution Too</h2>

<p>If you are entering through course-by-course teaching, the institution type is again the main variable. AAUP figures for average pay per standard three-credit section in 2024&ndash;25:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Institution category</th>
            <th style="padding:10px;text-align:left;">Average per course section</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Doctoral institutions</td><td style="padding:10px;">$5,115</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Baccalaureate institutions</td><td style="padding:10px;">$4,804</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Master's institutions</td><td style="padding:10px;">$3,629</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Associate's institutions with ranks</td><td style="padding:10px;">$3,575</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Associate's institutions without ranks</td><td style="padding:10px;">$3,348</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>All institutions</strong></td><td style="padding:10px;"><strong>$4,093</strong></td></tr>
    </tbody>
</table>
</div>

<p>A doctoral institution pays roughly 53 per cent more for the same section than an associate's institution without ranks. AAUP collects these from a few hundred reporting institutions and says the part-time results are not nationally representative, so treat them as a ranking rather than a quotation.</p>

<h2>Community Colleges and the 18-Hour Rule That No Longer Exists</h2>

<p>Almost every guide to teaching at a US community college repeats the same requirement: a master's degree plus <strong>18 graduate semester hours in the discipline you teach</strong>. It is quoted as though an accreditor enforces it. It does not, and has not for years.</p>

<ul>
    <li><strong>The Higher Learning Commission deleted that language.</strong> The 18-credit-hour sentence was struck from its Assumed Practices by Board action on <strong>2 November 2023</strong>. The current standard asks only that an institution "establishes and maintains reasonable policies and procedures to determine that faculty are qualified", which may weigh academic credentials, progress toward credentials, equivalent experience, or a combination.</li>
    <li><strong>HLC's current guidance leaves the number to the college.</strong> Where an instructor's degree is in another discipline, its September 2025 guideline asks for "a reasonable amount of coursework in the discipline or subfield in which they teach, <em>as defined by the institution</em>".</li>
    <li><strong>SACSCOC says the same.</strong> Writing in October 2025, the president of the Commission on Colleges and Universities described the master's-plus-18-hours formula as "a historic rule of thumb" that was once a requirement but has "not been for close to two decades", and stated plainly that no federal or state law sets an 18-hour rule.</li>
</ul>

<p>So the practical position is this: <strong>there is no national credential rule, and the college's own published faculty qualifications policy is the only one that binds.</strong> Many colleges still use 18 graduate hours as their internal benchmark, which is why the figure survives &mdash; but it is their number, not an accreditor's, and some set it differently or use an equivalence route.</p>

<p>Two things follow:</p>

<ul>
    <li><strong>Your transcript still matters more than your diploma.</strong> An MBA holder may be eligible to teach management and not economics, depending on which graduate courses are on the transcript.</li>
    <li><strong>Career and technical programmes run on different rules entirely.</strong> Licensure, certification and documented industry experience routinely substitute for graduate credit in nursing, welding, automotive, culinary and allied health teaching.</li>
</ul>

<p>Read the specific college's faculty credentials policy before assuming you do or do not qualify, and do not let a third-party article's "18 hours" talk you out of applying.</p>

<h2>Research Universities, Liberal Arts Colleges and Professional Schools</h2>

<ul>
    <li><strong>Doctoral, research-intensive.</strong> Doctorate required, usually in hand at the start date. Hiring turns on publications, external funding and the plausibility of the next five years. Teaching load around two courses a semester.</li>
    <li><strong>Master's institutions.</strong> Doctorate expected, with a real but lighter publication requirement and a three-course load. Often the best balance for someone who wants both research and teaching.</li>
    <li><strong>Baccalaureate and liberal arts colleges.</strong> Doctorate expected, but the teaching demonstration decides the search. Undergraduate research supervision and advising carry weight that they do not at a research university.</li>
    <li><strong>Professional schools</strong> &mdash; nursing, business, law, education, social work. Licensure and current practice can outweigh publications, and clinical titles are common.</li>
    <li><strong>For-profit and fully online institutions.</strong> Hire heavily, often per course. Check whether the post is employment or piecework before anything else.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/colleges-hiring-professors-in-the-us-library.jpg"
         alt="An academic working at a laptop with books and coffee beside a campus library, with students walking past a college building and a lecturer teaching in an inset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Colleges Actually Publish Their Vacancies</h2>

<p>US institutions post to their own systems first and syndicate afterwards, so a board listing is both later and partial.</p>

<ul>
    <li><strong>The institution's own HR or careers system.</strong> Always the first and most complete source, and the only place the full job description usually appears.</li>
    <li><strong>State system portals.</strong> Public university and community college systems frequently run one recruitment site covering every campus. These carry postings that never reach a national board.</li>
    <li><strong>Discipline associations.</strong> Most academic fields have a professional body that publishes vacancies to its members, often ahead of general advertising.</li>
    <li><strong>Federal institutions.</strong> The service academies and federal research bodies recruit through <a href="https://www.usajobs.gov" rel="noopener">USAJOBS</a>, on the federal hiring process rather than the academic one.</li>
    <li><strong>Departmental contact.</strong> For adjunct and visiting work, a short note to the department chair with a CV and the courses you can teach is still how a large share of those posts are filled.</li>
</ul>

<h2>The Seasons Are Not the Same Everywhere</h2>

<ul>
    <li><strong>Four-year institutions, tenure-track.</strong> Advertise from late summer through autumn for an August start, with campus visits in winter and offers in spring.</li>
    <li><strong>Community colleges.</strong> Run full-time searches on a shorter cycle, often in spring for the same autumn, and recruit adjuncts continuously.</li>
    <li><strong>Visiting and replacement posts.</strong> Appear late, sometimes weeks before term, when a search fails or someone resigns.</li>
    <li><strong>Online programmes.</strong> Hire on a rolling basis, driven by enrolment rather than by an academic calendar.</li>
</ul>

<h2>Online Colleges and the State Authorisation Limit</h2>

<p>Online faculty adverts usually list eligible states, and the reason is regulatory rather than academic: the institution has to be authorised to operate where the instructor lives and registered to run payroll there. "Remote" in US higher education therefore rarely means anywhere. Read the eligible-state list before you spend an afternoon on the application.</p>

<h2>Frequently Asked Questions</h2>

<h3>Which US colleges hire professors without a PhD?</h3>
<p>Mostly community and junior colleges, along with career and technical programmes and some adjunct teaching at four-year institutions. A master's in the discipline is the usual expectation, and the exact coursework requirement is set by each college rather than by an accreditor.</p>

<h3>Do community colleges require 18 graduate credit hours?</h3>
<p>Not as an accreditation rule. The Higher Learning Commission removed that language in November 2023, and SACSCOC says it has not been a requirement for close to two decades. Many colleges still use 18 hours as their own benchmark, so check the college's published faculty credentials policy.</p>

<h3>Which kind of college pays professors the most?</h3>
<p>State colleges and universities, at a $96,120 median in May 2025, against $89,660 at private ones, $81,640 at local junior colleges and $68,160 at state junior colleges.</p>

<h3>Do community colleges pay adjuncts less per course?</h3>
<p>Yes. AAUP figures for 2024&ndash;25 average $5,115 a section at doctoral institutions and $3,348 at associate's institutions without ranks, against $4,093 across all institutions.</p>

<h3>Where do US colleges advertise faculty vacancies?</h3>
<p>On their own HR systems first, then state system portals for public institutions, discipline association listings, and USAJOBS for federal institutions. National boards carry a later and partial copy.</p>

<h3>When do colleges hire professors?</h3>
<p>Four-year tenure-track searches advertise in autumn for an August start. Community colleges often run full-time searches in spring and recruit adjuncts all year. Visiting posts appear late.</p>

<h3>Do for-profit and online colleges hire professors?</h3>
<p>Yes, and heavily, but often course by course rather than as salaried employment. Check whether the post carries a contract and benefits before applying.</p>

<h3>Can I teach online for a US college from another state?</h3>
<p>Only where the institution is authorised to operate and registered for payroll. That is why online faculty adverts list eligible states.</p>

<h2>People Also Search For</h2>

<h3>Community college faculty jobs</h3>
<p>Usually open on a master's with graduate credit hours in the discipline taught.</p>

<h3>Universities hiring professors</h3>
<p>Doctorate expected, in hand by the start date, with publications deciding research-intensive searches.</p>

<h3>Teaching college with a master's degree</h3>
<p>Possible, and the transcript decides it; the graduate hours in the subject matter more than the degree title.</p>

<h3>College professor jobs USA</h3>
<p>$85,330 median across all postsecondary teachers, from under $49,540 to over $203,580.</p>

<h3>Online faculty jobs</h3>
<p>Limited by state authorisation and payroll registration, which is why eligible states are listed.</p>

<h3>Liberal arts college faculty</h3>
<p>Doctorate expected, but the teaching demonstration usually decides the appointment.</p>

<h3>Academic hiring season</h3>
<p>Autumn adverts and spring offers at four-year institutions; rolling adjunct hiring everywhere.</p>

<h3>Adjunct pay by institution type</h3>
<p>$5,115 a section at doctoral institutions down to $3,348 at associate's institutions without ranks.</p>

<h2>More Job Guides</h2>

<p>These cover the rest of the US academic picture:</p>

<ul>
    <li><a href="/blog/faculty-careers-in-the-us">Faculty Careers in the US</a> &mdash; every appointment type compared, with the BLS data behind each.</li>
    <li><a href="/blog/professor-employment-in-the-us">Professor Employment in the US</a> &mdash; the nine-month contract, teaching loads and promotion.</li>
    <li><a href="/blog/assistant-professor-jobs-in-the-us">Assistant Professor Jobs in the US</a> &mdash; the hiring calendar, the job talk and the offer.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; per-section pay and benefits eligibility in full.</li>
    <li><a href="/blog/adjunct-faculty-vacancies-in-the-uk">Adjunct Faculty Vacancies in the UK</a> &mdash; where British hourly-paid academic work is advertised.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the school-level route and state certification.</li>
    <li><a href="/blog/tutor-jobs-in-usa">Tutor Jobs in USA</a> &mdash; teaching work that needs no faculty appointment.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; the non-teaching roles inside the same institutions.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or employment advice. Pay data is from the US Bureau of Labor Statistics and the American Association of University Professors; faculty credential rules are set by each institution and its accreditor. Confirm requirements with the college before applying.</p>
HTML;
    }
}
