<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Adjunct teaching jobs in the UK, checked on 30 September 2026 against the
 * Employment Rights Act 1996, the Exclusivity Terms for Zero Hours Workers
 * (Unenforceability and Redress) Regulations 2022 and GOV.UK.
 *
 * Fourth page in the UK academic cluster, and it must not repeat the other
 * three. The professor page covers the honorary title. The lecturer page
 * covers what a teaching hour pays. The vacancies page covers where the work
 * is. This page covers the reader who already has a job or a career and wants
 * to teach alongside it, which is who most sessional staff actually are.
 *
 * The spine:
 *
 * 1. The exclusivity clause almost every guide tells you to check is, in the
 *    contracts this reader will be offered, unenforceable by statute. Section
 *    27A of the Employment Rights Act 1996 makes any term of a zero hours
 *    contract prohibiting outside work "unenforceable against the worker", and
 *    an hourly-paid teaching contract with no guaranteed hours meets the
 *    statutory definition. The 2022 Regulations extend the same protection to
 *    low-earning workers on contracts that are not zero hours.
 * 2. Because of that, the real constraint on a second teaching job is not the
 *    university's contract but the primary employer's own policy, which is a
 *    different document and a different conversation.
 * 3. Building a teaching record from nothing, for someone whose experience is
 *    professional rather than academic.
 *
 * Corrections applied to the supplied brief:
 *  1. jobs.ac.uk, Times Higher Education jobs and LinkedIn are not linked.
 *  2. The brief's advice to "check each contract for exclusivity clauses" is
 *     reversed, because the clause is void in the contracts it is warning
 *     about. Readers are pointed at the enforceable constraint instead.
 *  3. Every "[ADD REAL SOURCE]" placeholder is replaced with a named source or
 *     the claim is dropped.
 *  4. The section 27A argument now carries the one statistic that qualifies it.
 *     HESA Statistical Bulletin SB274 records 3,440 academic staff on zero
 *     hours contracts in 2024/25, 92% hourly paid. That is small because it
 *     counts a provider-reported contract marker and excludes atypical staff,
 *     so it measures the label rather than the statutory test. Saying so is
 *     more honest than quoting the section without it.
 *
 * Deliberately NOT claimed:
 *  - That any given hourly-paid contract is a zero hours contract within
 *    section 27A. That depends on its own terms, so the page gives the
 *    statutory test and lets the reader apply it rather than asserting it.
 *  - Subject-by-subject demand figures. The brief asked for them; they are
 *    covered on the vacancies guide where the workforce data belongs.
 */
class AdjunctTeachingJobsUkBlogSeeder extends Seeder
{
    public const SLUG = 'adjunct-teaching-jobs-in-the-uk';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides to getting hired, written from official and employer sources.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Adjunct Teaching Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'Most people who teach part-time at a UK university already have another job. The exclusivity clause you have been told to worry about is unenforceable by statute, and the constraint that actually binds you sits somewhere else entirely.',
                'content' => $content,
                'featured_image' => 'blogs/adjunct-teaching-jobs-uk.jpg',
                'tags' => 'adjunct teaching jobs uk, part time lecturing uk, second job teaching, zero hours exclusivity clause, sessional tutor uk, teaching alongside full time job, guest lecture uk university, uk right to work teaching',
                'meta_title' => 'Adjunct Teaching Jobs in the UK: Teaching on the Side',
                'meta_description' => 'Teaching part-time at a UK university alongside another job. Why the exclusivity clause is unenforceable, and what actually limits you instead.',
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
<p>Most people who teach part-time at a UK university are not trying to become academics. They are accountants, nurses, engineers, lawyers, designers and developers who teach one module a term alongside the job they already have. Universities want them, because a practitioner teaching a professional subject is worth more to students than another PhD who has never done the work.</p>

<p>If that is you, the practical questions are different from the ones a career-academic asks. Can you legally do it alongside your main job? What stops you? And how do you get a first booking when you have never taught in a UK institution?</p>

<p>This page answers those three. What a teaching hour actually pays is covered in a separate guide, and so is the honorary "professor" title that is not a job at all.</p>

<h2 id="second-job">The Clause You Were Told to Worry About Does Not Work</h2>

<p>Every guide to this subject tells you to check your teaching contract for an exclusivity clause. That advice is out of date by more than a decade, and in the contracts you are most likely to be offered it is simply wrong.</p>

<p><strong>Section 27A of the Employment Rights Act 1996 makes those clauses void.</strong> The wording is short enough to quote in full:</p>

<blockquote><p>"Any provision of a zero hours contract which&mdash;<br>(a) prohibits the worker from doing work or performing services under another contract or under any other arrangement, or<br>(b) prohibits the worker from doing so without the employer's consent,<br>is unenforceable against the worker."</p></blockquote>

<p>The section is in force and legislation.gov.uk records it as up to date as at 30 September 2026.</p>

<p><strong>The question is whether your teaching contract counts as a zero hours contract, and the statute defines that too.</strong> A zero hours contract is one where "the undertaking to do or perform work or services is an undertaking to do so conditionally on the employer making work or services available to the worker, and there is no certainty that any such work or services will be made available to the worker".</p>

<p>Read that against a standard hourly-paid teaching engagement. You are on a register. The department offers you a module when it needs cover. Nothing guarantees you any hours in any given term. That is the statutory definition almost word for word, which means an exclusivity term in it cannot be enforced against you.</p>

<p><strong>And if your contract does guarantee hours,</strong> so that it is not a zero hours contract, there is a second route. The Exclusivity Terms for Zero Hours Workers (Unenforceability and Redress) Regulations 2022 extended the same protection to workers on other contracts whose net average weekly wage is at or below the lower earnings limit. The Regulations define an "exclusivity term" identically: any provision "which (a) prohibits the worker from doing work or performing services under another contract or under any other arrangement; or (b) prohibits the worker from doing so without the employer's consent".</p>

<p>The protection is not merely that the clause fails. A worker also has the right not to be subjected to a detriment for breaching such a term, with a route to an employment tribunal.</p>

<p><strong>One number is worth knowing before you read your own contract, because it cuts both ways.</strong> HESA's Statistical Bulletin SB274, published on 19 February 2026, records only 3,440 academic staff on zero hours contracts across UK higher education in 2024/25 &mdash; 92% of them paid by the hour. Against the scale of hourly-paid teaching in the sector, that is a strikingly small figure, and the reason is instructive: it counts what providers recorded against a contract marker, and it leaves out the tens of thousands of people on academic atypical contracts, who are returned with only a minimum data set. So the figure tells you how many contracts are <em>labelled</em> zero hours. It does not tell you how many meet the section 27A test, which turns on what a contract says rather than what it is called.</p>

<p>One honest caveat, because this is law and not a slogan: whether a particular contract meets the section 27A definition turns on its own wording. The page gives you the test. Read your offer against it, and if the answer is unclear, put the question to the department in writing or ask your union branch.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-teaching-jobs-uk-classroom.jpg" alt="A practitioner teaching a seminar group at a UK university in the evening" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Evening and block teaching is built around people who work during the day. That is the market you are entering.</figcaption>
</figure>

<h2 id="what-actually-stops-you">What Actually Constrains You</h2>

<p>Since the university's clause is unenforceable, the real limits are elsewhere. There are three, and they are worth checking in this order.</p>

<ol>
    <li><strong>Your main employer's policy on outside work.</strong> This is the one that can genuinely cost you. Many employment contracts require you to declare or seek approval for secondary employment, and that obligation runs to your main employer, not to the university. Section 27A protects you against the <em>teaching</em> contract's restriction; it says nothing about your day job's. Read your primary contract and, if approval is needed, get it before you accept a module.</li>
    <li><strong>Conflict of interest, especially in regulated professions.</strong> If you teach on a course that assesses people you also supervise, employ or regulate professionally, declare it. Universities have their own conflict procedures and will normally manage it rather than refuse you, but only if they know.</li>
    <li><strong>The timetable, which is the constraint nobody warns you about.</strong> Teaching slots are fixed weeks in advance and cannot move around your day job. A programme leader will not book someone who might be unavailable in week seven. Being specific and reliable about availability is worth more in this market than another qualification.</li>
</ol>

<h2 id="right-to-work">Right to Work Comes Before Everything</h2>

<p>You must be legally allowed to work in the UK before you start. GOV.UK's requirements for a Skilled Worker visa are an approved sponsor, a certificate of sponsorship, an eligible occupation and a salary of at least &pound;41,700 a year or the going rate for the job, whichever is higher.</p>

<p>Hourly-paid teaching cannot reach that floor, which is explained in detail in our lecturer guide. The practical consequence for this page is simple: <strong>part-time university teaching is realistically open to people who already hold the right to work in the UK</strong> &mdash; British and Irish citizens, settled and pre-settled status, dependant and partner visas, and graduate route visas, among others.</p>

<p>If you already have status through your main job, check whether your visa route permits supplementary employment before you take teaching work, because some routes restrict it. Verify your own position on GOV.UK rather than relying on what a colleague did.</p>

<h2 id="no-record">Getting a First Booking With No UK Teaching Record</h2>

<p>The cold start is the real obstacle for a practitioner. Departments book people they have seen teach, which is circular until you break it. Four things break it, roughly in order of how well they work.</p>

<ol>
    <li><strong>Offer a single guest lecture, free, on something only you can teach.</strong> Not "I could help with your programme" but "I can deliver a 50-minute session on how tax investigations actually run, with two real anonymised cases". Programme leaders take these, because a practitioner session is a highlight of the module and costs them nothing. It also puts you in a room with the person who books sessional staff, teaching, which is the audition you could not otherwise get.</li>
    <li><strong>Count the teaching you have already done.</strong> Most practitioners have more than they think: training colleagues, inducting new starters, running CPD sessions, conference workshops, supervising apprentices or trainees, presenting to clients. Write it as teaching, with numbers. "Designed and delivered a six-session internal training programme to 40 staff" is teaching experience.</li>
    <li><strong>Ask to join the register, explicitly.</strong> Most hourly-paid work is allocated from a departmental pool rather than advertised, so joining the pool matters more than watching vacancy pages. Say the words: <em>please add me to your list of approved hourly-paid teaching staff</em>.</li>
    <li><strong>Name the module and the gap.</strong> Find the programme's module list on the university's own site and write to the programme leader about specific modules by title and code, saying what you would add that the current syllabus does not have. A generic offer to teach is the single most common reason for no reply.</li>
</ol>

<p>If you want a recognised teaching credential to go with that, Associate Fellowship of Advance HE is the relevant one, and our lecturer guide sets out what it requires and the framework change that dates most advice about it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-teaching-jobs-uk-start.jpg" alt="A professional preparing teaching materials and a CV at a desk" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">The first booking is the hard one. A free guest lecture is the cheapest way to buy it.</figcaption>
</figure>

<h2 id="application">What to Put in the Approach</h2>

<p>This is not a graduate application, and treating it like one costs you the booking. A programme leader is solving a staffing problem for a specific module in a specific term.</p>

<ul>
    <li><strong>A short email, not a covering letter.</strong> Which module, what you do professionally, what you would bring that the syllabus lacks, and your availability.</li>
    <li><strong>A UK-style academic CV attached,</strong> two to four pages, leading with your professional practice if that is your strength rather than a publication list you do not have.</li>
    <li><strong>Evidence with numbers.</strong> Learners taught, sessions delivered, feedback scores if you have them, pass rates if you know them.</li>
    <li><strong>Assessment experience named separately,</strong> because marking is the part departments struggle to staff and the part they most need to trust you with.</li>
    <li><strong>Your right-to-work status stated plainly,</strong> in one line. Departments will not pursue someone whose status is unclear, and saying it up front removes the objection before it forms.</li>
    <li><strong>Availability as a grid, not a sentence.</strong> "Tuesday and Thursday after 4pm, and all day Friday in semester one" is bookable. "Fairly flexible" is not.</li>
</ul>

<h2 id="faq">Frequently Asked Questions</h2>

<h3>Can I teach at a UK university while working full time?</h3>
<p>Yes, and most sessional teachers do. The teaching contract usually cannot stop you: section 27A of the Employment Rights Act 1996 makes an exclusivity term in a zero hours contract "unenforceable against the worker". The constraint that does bind you is your main employer's own policy on outside work.</p>

<h3>Is an exclusivity clause in a sessional teaching contract enforceable?</h3>
<p>Not if the contract is a zero hours contract, which most hourly-paid teaching engagements are, because nothing guarantees you hours. The 2022 Regulations extend the same protection to workers on other contracts earning at or below the lower earnings limit. Whether a specific contract qualifies depends on its wording, so read it against the statutory test.</p>

<h3>Do I need to tell my employer I am teaching?</h3>
<p>Usually yes, and this is the obligation that actually matters. Many employment contracts require secondary employment to be declared or approved. That duty runs to your main employer and is unaffected by the protection in section 27A.</p>

<h3>Can I teach at more than one university at the same time?</h3>
<p>Yes. Many practitioners hold sessional work at two or three institutions. Concentrating hours at one of them is usually the better strategy if you eventually want a fractional contract, because some universities only count hours accrued within one faculty.</p>

<h3>How do I get my first UK teaching booking with no experience?</h3>
<p>Offer one free guest lecture on something only you can teach, to a named module, and use it to get into the room with the person who books sessional staff. Then ask explicitly to be added to the department's register of approved hourly-paid teaching staff.</p>

<h3>Does professional experience count instead of a PhD?</h3>
<p>In vocational and professional subjects it often does, because the point of booking a practitioner is the practice. Write your training, CPD delivery and supervision history as teaching experience, with numbers.</p>

<h3>Do I need the right to work in the UK for part-time teaching?</h3>
<p>Yes, before you start. Hourly-paid teaching cannot meet the Skilled Worker salary floor of GBP 41,700 a year or the going rate, so it is realistically open to people who already hold permission to work. If your status comes through another job, check whether your route permits supplementary employment.</p>

<h3>When in the year should I approach a department?</h3>
<p>Before the term you want to teach, not during it. Timetables are built weeks ahead, so approaches land best in the late spring for the autumn term and in the late autumn for the spring term. Cover requests also appear at short notice throughout the year once you are on the register.</p>

<h2 id="people-also-search-for">People Also Search For</h2>

<h3>Zero hours contract exclusivity clause</h3>
<p>A term restricting outside work, made unenforceable against the worker by section 27A of the Employment Rights Act 1996.</p>

<h3>Second job while employed UK</h3>
<p>Generally lawful, but many employment contracts require a secondary role to be declared or approved by the primary employer.</p>

<h3>Guest lecture UK university</h3>
<p>A single invited session, often unpaid, and the most reliable way for a practitioner to get a first foot inside a department.</p>

<h3>Teaching pool university register</h3>
<p>Getting your name onto one is the real application for a practitioner, because bookings that follow are handled administratively rather than competitively.</p>

<h3>Practitioner lecturer vocational subjects</h3>
<p>Teaching on professional courses where current industry practice is valued alongside, or instead of, a doctorate.</p>

<h3>Lower earnings limit exclusivity regulations 2022</h3>
<p>The threshold below which the ban on exclusivity terms was extended to contracts that are not zero hours.</p>

<h3>Supplementary employment visa UK</h3>
<p>Additional work permitted alongside sponsored employment on some immigration routes, subject to route-specific conditions on GOV.UK.</p>

<h3>Academic CV for practitioners</h3>
<p>A two to four page UK-style CV that leads with professional practice and delivered training rather than with publications.</p>

<h2 id="more-guides">More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-become-an-adjunct-lecturer-in-the-uk">How to Become an Adjunct Lecturer in the UK</a> &mdash; what a teaching hour actually pays once the multiplier is accounted for.</li>
    <li><a href="/blog/adjunct-faculty-vacancies-in-the-uk">Adjunct Faculty Vacancies in the UK</a> &mdash; the four kinds of institution that hire, and why further education is the easiest door.</li>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the honorary title, conferred by nomination and unpaid.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the sponsorship routes that clear the salary floor.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; teaching income that does not depend on a UK visa.</li>
    <li><a href="/blog/adjunct-teaching-opportunities">Adjunct Teaching Opportunities</a> &mdash; the international picture: what a US course section pays, and what the job is called in each country.</li>
</ul>

<h2 id="sources">Official Sources</h2>

<ul>
    <li><a href="https://www.legislation.gov.uk/ukpga/1996/18/section/27A" rel="nofollow noopener" target="_blank">Employment Rights Act 1996, section 27A</a> &mdash; exclusivity terms unenforceable in zero hours contracts</li>
    <li><a href="https://www.legislation.gov.uk/uksi/2022/1145/made" rel="nofollow noopener" target="_blank">The Exclusivity Terms for Zero Hours Workers (Unenforceability and Redress) Regulations 2022</a></li>
    <li><a href="https://www.hesa.ac.uk/news/19-02-2026/sb274-higher-education-staff-statistics" rel="nofollow noopener" target="_blank">HESA &mdash; Statistical Bulletin SB274, Higher Education Staff Statistics: UK, 2024/25</a>, published 19 February 2026, for the zero hours contract count</li>
    <li><a href="https://www.gov.uk/skilled-worker-visa" rel="nofollow noopener" target="_blank">GOV.UK &mdash; Skilled Worker visa</a>, for the sponsorship and salary requirements</li>
    <li><a href="https://www.advance-he.ac.uk/fellowship" rel="nofollow noopener" target="_blank">Advance HE &mdash; Fellowship</a>, for the recognised teaching credential</li>
</ul>

<p style="font-size:14px;color:#666;margin-top:26px;">Checked on 30 September 2026. This is general information, not legal advice; whether a particular contract falls within section 27A depends on its own terms. Confirm your immigration position on GOV.UK before accepting work. JobGader is not a recruiter and does not accept payment from applicants.</p>
HTML;
    }
}
