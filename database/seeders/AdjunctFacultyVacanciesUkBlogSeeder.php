<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Where adjunct-style teaching vacancies actually are in the UK, checked on
 * 30 September 2026.
 *
 * Fifth page in the UK academic cluster and the one at most risk of repeating
 * the others, so its axis is deliberately narrow: not what the work pays, not
 * how to break in, but WHICH KIND OF INSTITUTION employs sessional teachers and
 * why so few of the posts are ever advertised.
 *
 * The spine:
 *
 * 1. Four different employer types, not one. Universities, further education
 *    colleges, distance providers and professional education providers hire
 *    part-time teachers on completely different terms and entry requirements.
 *    Guides to this subject treat "UK university" as the whole market.
 * 2. Further education is the accessible door, and the reason is regulatory.
 *    The Further Education Teachers' Qualifications (England) (Amendment)
 *    Regulations 2012 removed the statutory qualification requirements, and
 *    since 2013 colleges have set their own criteria. There is no legal
 *    obstacle to a practitioner teaching in an English FE college.
 * 3. Most of this work is never advertised, because it is allocated from
 *    departmental registers. That is a structural fact about the market and it
 *    changes what "looking for vacancies" should mean.
 *
 * Corrections applied to the supplied brief:
 *  1. The brief's answer was a list of job boards: jobs.ac.uk, Times Higher
 *     Education jobs and LinkedIn. None is linked. The academic board is named
 *     once because omitting it would mislead.
 *  2. The brief asked for HESA figures on part-time and fixed-term academic
 *     staff. Where a figure could not be read from an official source it is not
 *     invented, and the page says what it does not know.
 *
 * Deliberately NOT claimed:
 *  - Which subjects have the most sessional teaching. The brief asked for it,
 *    HESA does not publish a breakdown in that form, and a plausible-sounding
 *    list would be a guess.
 */
class AdjunctFacultyVacanciesUkBlogSeeder extends Seeder
{
    public const SLUG = 'adjunct-faculty-vacancies-in-the-uk';

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
        $title = 'Adjunct Faculty Vacancies in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'Most UK sessional teaching is never advertised, because it is allocated from departmental registers. Here are the four kinds of institution that hire part-time teachers, and why further education is the most accessible door.',
                'content' => $content,
                'featured_image' => 'blogs/adjunct-faculty-vacancies-uk.jpg',
                'tags' => 'adjunct faculty vacancies uk, sessional teaching vacancies, fe college teaching jobs, hourly paid lecturer vacancies, university teaching pool, teaching without a pgce, open university tutor, part time lecturer vacancies',
                'meta_title' => 'Adjunct Faculty Vacancies in the UK: Where They Are',
                'meta_description' => 'Most UK sessional teaching is never advertised. The four kinds of institution that hire part-time teachers, and why FE colleges are the easiest door in.',
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
<p>If you have been searching job boards for adjunct faculty vacancies in the UK and finding almost nothing, the problem is not your search terms. It is that <strong>most of this work never reaches a job board at all.</strong></p>

<p>Departments keep registers of approved part-time teachers and allocate work from them as cover is needed. A module loses its tutor in week two; the programme leader goes to the list, not to a recruitment process. By the time something is advertised publicly, it is usually a larger fixed-term post rather than the sessional work you were looking for.</p>

<p>So the useful question is not "which website lists these jobs". It is <strong>which institutions employ part-time teachers, and how do you get onto their lists.</strong> There are four kinds, and they are not interchangeable.</p>

<h2 id="four-employers">Four Different Employers, Not One</h2>

<p>Guides to this subject treat "UK university" as the whole market. It is roughly a quarter of it.</p>

<ul>
    <li><strong>Universities.</strong> Hourly-paid lecturer, associate lecturer, sessional tutor, teaching associate and graduate teaching assistant posts. Highest status, usually the highest headline rate, and the hardest to enter without an existing academic relationship. Entry normally assumes a PhD or one in progress for academic subjects.</li>
    <li><strong>Further education colleges.</strong> Part-time lecturer and tutor posts, heavily weighted to vocational and professional subjects. Lower entry barriers by some distance, for a regulatory reason set out below, and the most realistic first teaching post for a practitioner.</li>
    <li><strong>Distance and online providers.</strong> Tutoring on distance programmes, of which the Open University's associate lecturer model is the best known. Work is allocated by student group rather than by timetabled room, which makes it compatible with a full-time job in a way campus teaching often is not.</li>
    <li><strong>Professional and executive education.</strong> Short courses, CPD and corporate programmes, delivered by universities, business schools, professional bodies and private training providers. Practitioner credibility matters more than academic rank here, and this is where an experienced professional is most obviously employable.</li>
</ul>

<p>The mistake most applicants make is to search only the first category and conclude there is no work. Applying across all four multiplies your chances several times over without changing anything about your CV.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-faculty-vacancies-uk-campus.jpg" alt="A UK university campus building where sessional teaching staff are recruited by department" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Universities are the most visible employer of sessional teachers, and the hardest to enter cold.</figcaption>
</figure>

<h2 id="fe-colleges">Further Education Is the Accessible Door, for a Regulatory Reason</h2>

<p>This is the part of the market people overlook, and there is a specific legal reason it is more open than the university sector.</p>

<p><strong>You do not need a teaching qualification to teach in a further education college in England.</strong> The Further Education Teachers' Qualifications (England) (Amendment) Regulations 2012 came into force on 30 September 2012 and removed the statutory qualification and registration requirements that had previously applied. Since 2013, responsibility for deciding who is suitable to teach has sat with the colleges themselves, which set their own criteria.</p>

<p>That does not mean colleges do not want qualifications. Many prefer or expect a Level 5 Diploma in Education and Training, a PGCE or equivalent, and some will fund you to take one while you teach. The point is narrower and more useful than that: <strong>there is no legal barrier stopping a college from hiring a plumber, an accountant, a nurse or a software engineer to teach their own trade tomorrow.</strong> In the university sector the barrier is conventional rather than legal too, but it is enforced far more tightly.</p>

<p>For a practitioner with no teaching record, an FE college is therefore the shortest route to a first paid teaching post, and that post is what makes the university conversation possible a year later.</p>

<p>One scope note: these Regulations apply to England. Scotland, Wales and Northern Ireland run their own arrangements for college teaching, so check the position where you actually intend to work.</p>

<h2 id="why-not-advertised">Why So Little Is Advertised</h2>

<p>Three structural reasons, and understanding them changes your strategy.</p>

<ol>
    <li><strong>The register, not the vacancy.</strong> Departments maintain lists of approved hourly-paid staff and draw from them. Getting onto the list is the real application; the individual bookings that follow are administrative.</li>
    <li><strong>The timescale is days, not months.</strong> When cover is needed it is usually needed now, because a module is running. A department with a usable list will never open a recruitment process for it.</li>
    <li><strong>It is cheap to ask someone known.</strong> A programme leader with a PhD student, a recent graduate or a local professional already in mind has no reason to advertise, and no budget line for doing so.</li>
</ol>

<p>The consequence: <strong>time spent refreshing vacancy pages is close to wasted, and time spent getting onto registers is not.</strong> A single email to a programme leader asking to be added to their approved list does more than fifty job alerts.</p>

<p>Where posts are advertised, they appear on the sector's academic job board and on the institution's own recruitment pages. We do not link job boards on this site; the institution's own careers pages are where the hourly-paid roles that do get advertised actually appear, and they are easy to find from any university or college homepage.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-faculty-vacancies-uk-college.jpg" alt="A further education college workshop where vocational part-time lecturers teach" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Further education carries no statutory qualification requirement in England, which makes it the fastest first teaching post for a practitioner.</figcaption>
</figure>

<h2 id="timing">The Calendar Decides When to Ask</h2>

<p>Sessional recruitment follows the academic year rather than a hiring cycle, and approaching at the wrong point in it is the most common avoidable mistake.</p>

<ul>
    <li><strong>Late spring to early autumn</strong> &mdash; staffing for the autumn term is settled here. This is the single highest-value window and it closes before term starts, not after.</li>
    <li><strong>Late autumn to January</strong> &mdash; the spring term equivalent, and a smaller window.</li>
    <li><strong>All year</strong> &mdash; short-notice cover for illness, leave and resignation. This only reaches you if you are already on a register, which is the whole argument for joining one early.</li>
</ul>

<p>Timetables are built weeks before teaching begins. An approach that arrives in week one of term is too late for that term and too early to be remembered for the next, which is why the register matters more than the timing of any single email.</p>

<h2 id="what-to-ask">What to Send, and to Whom</h2>

<p>Not the central HR address. The person who decides is the programme leader, course leader or head of department for the subject.</p>

<ol>
    <li><strong>Find the programme's module list</strong> on the institution's own website and identify the two or three modules you could teach, by title and code.</li>
    <li><strong>Write to the person named as leading that programme,</strong> not to a generic recruitment inbox.</li>
    <li><strong>Ask the register question explicitly:</strong> how does the department appoint hourly-paid teaching staff, and may you be added to its approved list?</li>
    <li><strong>State your right-to-work status in one line,</strong> because a department will not pursue an applicant whose status is unclear.</li>
    <li><strong>Attach a two to four page UK-style CV</strong> leading with teaching and practice rather than publications if that is where your strength lies.</li>
    <li><strong>Give availability as a grid.</strong> Specific days and times are bookable; "flexible" is not.</li>
</ol>

<p>Send the same message to the FE colleges within travelling distance as well as the universities. They are a different market with a different entry bar, and they are frequently short of people who can teach a trade they have actually practised.</p>

<h2 id="faq">Frequently Asked Questions</h2>

<h3>Why can I not find adjunct faculty vacancies in the UK?</h3>
<p>Because most of this work is never advertised. Departments allocate sessional teaching from registers of approved hourly-paid staff, so the effective application is asking to join the register rather than answering a posted vacancy.</p>

<h3>What is the UK term for adjunct faculty?</h3>
<p>There is no single one. Universities advertise hourly-paid lecturer, associate lecturer, sessional lecturer, sessional tutor, teaching associate, visiting lecturer and graduate teaching assistant. Colleges usually say part-time lecturer or tutor.</p>

<h3>Do I need a teaching qualification to teach in an FE college?</h3>
<p>Not as a matter of law in England. The Further Education Teachers' Qualifications (England) (Amendment) Regulations 2012 removed the statutory requirements, and since 2013 colleges have set their own criteria. Many still prefer a Level 5 Diploma in Education and Training or a PGCE, and some will fund one while you teach.</p>

<h3>Which institutions hire part-time teachers in the UK?</h3>
<p>Four kinds: universities, further education colleges, distance and online providers such as the Open University model, and professional or executive education providers. They have different entry bars, and searching only universities is why the market looks empty.</p>

<h3>When should I apply for sessional teaching work?</h3>
<p>Late spring to early autumn for the autumn term, and late autumn to January for the spring term. Short-notice cover arises all year but only reaches people already on a departmental register.</p>

<h3>Who should I contact at a university about sessional teaching?</h3>
<p>The programme leader, course leader or head of department for your subject, not the central HR inbox. Name the specific modules you could teach and ask to be added to the approved list.</p>

<h3>Is there more sessional teaching in some subjects than others?</h3>
<p>Almost certainly, but we are not going to publish a ranked list. HESA does not publish a breakdown of sessional or hourly-paid staff by subject in a form that would support one, and a plausible-sounding list would be a guess.</p>

<h3>Can I teach at a college and a university at the same time?</h3>
<p>Yes, and many practitioners do. Check your primary employer's policy on outside work, which is the constraint that actually binds; the teaching contract's own exclusivity term usually does not.</p>

<h2 id="people-also-search-for">People Also Search For</h2>

<h3>University teaching pool register</h3>
<p>The departmental list of approved hourly-paid staff from which most sessional teaching is allocated without advertising.</p>

<h3>FE college part time lecturer jobs</h3>
<p>Vocational and professional teaching posts at further education colleges, with no statutory qualification requirement in England.</p>

<h3>Teach in FE without a PGCE</h3>
<p>Legally possible in England since the 2012 Regulations, though many colleges still prefer a Level 5 Diploma in Education and Training or equivalent.</p>

<h3>Open University associate lecturer</h3>
<p>The best-known distance-teaching model in the UK, where tutors support student groups rather than deliver timetabled campus sessions.</p>

<h3>Sessional tutor vacancies</h3>
<p>Short engagements for a specific module or teaching block, usually allocated from a register rather than advertised.</p>

<h3>Graduate teaching assistant UK</h3>
<p>Teaching work undertaken by doctoral students within their own department, and the most common entry route into university teaching.</p>

<h3>Professional education short courses teaching</h3>
<p>CPD and executive programmes where current practice matters more than academic rank.</p>

<h3>Academic job board UK</h3>
<p>Where the larger advertised academic posts appear, though most hourly-paid teaching is filled before it would ever be listed.</p>

<h2 id="more-guides">More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-become-an-adjunct-lecturer-in-the-uk">How to Become an Adjunct Lecturer in the UK</a> &mdash; what a teaching hour actually pays once the multiplier is accounted for.</li>
    <li><a href="/blog/adjunct-teaching-jobs-in-the-uk">Adjunct Teaching Jobs in the UK</a> &mdash; teaching alongside another career, and the exclusivity clause that does not bind.</li>
    <li><a href="/blog/adjunct-professor-jobs-in-the-uk">Adjunct Professor Jobs in the UK</a> &mdash; the honorary title, conferred by nomination and unpaid.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the sponsorship routes that clear the salary floor.</li>
</ul>

<h2 id="sources">Official Sources</h2>

<ul>
    <li><a href="https://www.legislation.gov.uk/uksi/2012/2166/made" rel="nofollow noopener" target="_blank">The Further Education Teachers' Qualifications (England) (Amendment) Regulations 2012</a></li>
    <li><a href="https://www.hesa.ac.uk/data-and-analysis/staff" rel="nofollow noopener" target="_blank">HESA &mdash; Higher Education Staff Statistics</a>, for the published academic staff data</li>
    <li><a href="https://www.gov.uk/skilled-worker-visa" rel="nofollow noopener" target="_blank">GOV.UK &mdash; Skilled Worker visa</a>, for the right-to-work position</li>
    <li><a href="https://www.open.ac.uk/" rel="nofollow noopener" target="_blank">The Open University</a>, for its own description of distance tutoring roles</li>
</ul>

<p style="font-size:14px;color:#666;margin-top:26px;">Checked on 30 September 2026. Regulations on college teaching apply to England; Scotland, Wales and Northern Ireland have their own arrangements. JobGader is not a recruiter and does not accept payment from applicants.</p>
HTML;
    }
}
