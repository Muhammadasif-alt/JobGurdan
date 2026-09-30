<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Adjunct professor jobs in the UK, checked on 30 September 2026 against four
 * UK universities' own published titles policies and against GOV.UK.
 *
 * This page and our "How to Become an Adjunct Lecturer in the UK" guide must
 * not overlap, and they do not, because in the UK they are two different
 * things. A professorship is a rank; an adjunct or honorary professorship is a
 * title conferred by nomination and, at every university whose policy was
 * opened for this page, one that cannot be paid. A lectureship is a job you
 * apply for and are paid to do. This page is about the title. The other page
 * is about the job.
 *
 * The spine:
 *
 * 1. You do not apply. The title is proposed by a Dean or Head of School, goes
 *    to the Vice-Chancellor and is conferred by Senate. Bath's code of practice
 *    calls it "an honour in the gift of the University" with "no appeal process".
 * 2. Honorary means unpaid, and the policies enforce it structurally. Bath will
 *    not confer the title "until any contract of employment between the
 *    individual and the University has ended". Heriot-Watt requires an honorary
 *    title to be "put into abeyance" for any period of paid work. Essex
 *    suspends the honorary agreement and issues a separate fixed-term contract.
 * 3. Honorary and Visiting are not the same word. Heriot-Watt draws the line in
 *    one sentence: honorary and emeritus holders "are not permitted to receive
 *    Remuneration from the University", visiting holders "are permitted to
 *    receive Remuneration". That distinction is the most useful thing on the
 *    page and almost nothing written about UK academic titles mentions it.
 * 4. It is not an immigration route. A Skilled Worker visa needs an approved
 *    sponsor, a certificate of sponsorship and a salary at or above GBP 41,700
 *    or the going rate. An unpaid title produces none of those.
 *
 * Corrections applied to the supplied brief:
 *  1. The brief told readers to search jobs.ac.uk, Times Higher Education jobs
 *     and LinkedIn. Those are aggregators and are not linked. The academic
 *     board is named once, because pretending it does not exist would mislead,
 *     but every link on this page goes to a university or to GOV.UK.
 *  2. The brief's framing, that an adjunct professorship is a job you find and
 *     apply for, is wrong in the UK and is replaced.
 *  3. The brief offered no salary source. None is invented; the honorary title
 *     has no salary to report.
 *
 * Deliberately NOT claimed:
 *  - That every UK university runs this the same way. Four policies were read
 *    in full and they agree on the substance, but the page tells the reader to
 *    find the policy for the institution they care about rather than
 *    generalising from four.
 */
class AdjunctProfessorJobsUkBlogSeeder extends Seeder
{
    public const SLUG = 'adjunct-professor-jobs-in-the-uk';

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
        $title = 'Adjunct Professor Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'JobGader Editorial',
                'title' => $title,
                'excerpt' => 'In the UK an adjunct or honorary professorship is not a job. It is a title conferred by nomination through Senate, and the universities that publish their policies say plainly that an honorary holder may not be paid at all.',
                'content' => $content,
                'featured_image' => 'blogs/adjunct-professor-jobs-uk.jpg',
                'tags' => 'adjunct professor jobs uk, honorary professor uk, visiting professor uk, uk academic titles policy, unpaid academic title, how to become a professor uk, uk university nomination, skilled worker visa academic',
                'meta_title' => 'Adjunct Professor Jobs in the UK: The Title Is Unpaid',
                'meta_description' => 'In the UK an adjunct or honorary professorship is a title conferred by nomination, not a job you apply for, and honorary holders may not be paid at all.',
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
<p>If you are searching for adjunct professor jobs in the UK, the honest answer is that you are searching for something that does not exist under that name, and the thing that does exist under that name will not pay you.</p>

<p>That sounds discouraging. It is actually the most useful sentence anyone can give you, because it stops you spending months applying for a thing that is not advertised, is not applied for, and carries no salary. There <em>is</em> a paid, part-time, teaching-focused route into a UK university. It has a different name, and it is covered in a separate guide linked below.</p>

<p>Everything here comes from four UK universities' own published titles policies and from GOV.UK.</p>

<h2 id="not-a-job">In the UK It Is a Title, Not a Post</h2>

<p>The word "professor" does not mean the same thing on both sides of the Atlantic, and almost every confusion about this subject starts there.</p>

<p>In the United States, most academic staff who teach are called professors of some kind, and "adjunct professor" is an ordinary part-time teaching job. <strong>In the UK, Professor is the most senior academic rank there is.</strong> So when a British university attaches "Honorary", "Visiting" or "Adjunct" to it, it is not describing a junior or casual version of a job. It is conferring an honour on someone it considers already distinguished.</p>

<p>The University of Bath's code of practice, approved on 13 March 2024, says how that works. The title "may be conferred on persons who are of sufficient standing and distinction in their own profession or area of activity that were they a member of staff of the University they would be appointed at the level of the title to be conferred". The people nominated are typically "senior academics at other universities, persons who have left the University to undertake work of an essentially academic nature outside of higher education or recent retirees".</p>

<p>And there is no application form, because there is no application:</p>

<blockquote><p>"The title is an honour in the gift of the University. There is no appeal process in the event that the University decides not to confer the title."</p></blockquote>

<p>At Bath the route runs from the Dean of Faculty or Head of School, who submits a proposal to the Vice-Chancellor, who if he or she agrees recommends conferment to Senate. Senate decides. You are not in that process; someone inside the university puts you into it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-professor-jobs-uk-lecture.jpg" alt="A lecture theatre in a UK university during a teaching session" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">The teaching happens under a different job title. The professorship is an honour conferred alongside it, or instead of it.</figcaption>
</figure>

<h2 id="unpaid">Honorary Means Unpaid, and the Policies Enforce It</h2>

<p>This is not a soft convention. The universities build it into the structure of the title, and two of them go as far as saying you cannot hold the title and be paid at the same time.</p>

<p>Bath: <strong>"The conferment of the title is on an unpaid basis (although payment is permitted on an independent consultancy basis for a specific project/activity)."</strong> And before that, a line that tells you everything about what kind of thing this is: "The title may not be conferred until any contract of employment between the individual and the University has ended."</p>

<p>Heriot-Watt University's policy on honorary, visiting and emeritus titles is blunter still:</p>

<blockquote><p>"A holder of an Honorary Title cannot also be a paid employee of the University. If the holders of such a status receive payment for services to the University, regardless of whether the work is in a different PAU from where the Honorary Title was given, the Honorary Title must be put into abeyance for the period of the paid work for the University."</p></blockquote>

<p>The University of Essex reaches the same place by a different mechanism. It issues an "honorary agreement" for each honorary title, and says of it: "The honorary agreement makes very clear that this is not an employment relationship." Its annex adds that "these are unpaid appointments by their very nature although expenses may be claimed". If paid work does come up, the agreement "will be suspended whilst the individual partakes in paid work and a separate fixed-term contract issued to cover the paid work role. Once the fixed-term contract has ended, the honorary title can be reinstated, but not before."</p>

<p>Queen Mary University of London states the legal position directly: granting honorary or visiting status "does not create or imply the creation of a contract of employment between QMUL and the individual", and holders "do not thereby become employees of QMUL".</p>

<p>So if you have been offered an honorary or adjunct professorship and you were assuming a salary would follow, check the paperwork before you plan around it.</p>

<h2 id="honorary-versus-visiting">Honorary and Visiting Are Not the Same Word</h2>

<p>Here is the distinction that almost nothing written about UK academic titles bothers to make, and it is the one worth carrying away.</p>

<p>Heriot-Watt's policy sets the two side by side in its remuneration section:</p>

<blockquote><p>"Holders of Honorary and Emeritus Titles are not permitted to receive Remuneration from the University. Holders of Visiting Titles are permitted to receive Remuneration from the University."</p></blockquote>

<p>And on the visiting side it explains why: a visiting title holder "shall be eligible to receive payment for the work they are asked to carry out as agreed in advance of their appointment, and it is recognised that without offering some form of payment, the University might not benefit from their contribution".</p>

<p><strong>So the single most valuable question you can ask about any UK academic title you are offered is which of the two it is.</strong> "Honorary" and "Emeritus" are honours and are structurally unpaid. "Visiting" may be paid, and whether it is paid is settled in advance, in writing, at the point of appointment. Do not assume either way from the word "Professor" in the middle of the phrase.</p>

<p>One caution: these are four universities' policies, and each institution writes its own under its own Ordinances. The substance agreed across all four, but the wording did not. Find the policy for the university you actually care about. It will be on their website under a name like "Honorary and Visiting Titles Policy" or "Honorary Appointments", and it is public.</p>

<h2 id="what-you-get">What the Title Actually Gives You</h2>

<p>An unpaid title is not a worthless title. Bath's policy lists what an Honorary Professor, Reader or Lecturer becomes entitled to, and the list is real:</p>

<ul>
    <li>Membership of the University, for the normal period of <strong>three years, renewable</strong></li>
    <li>Library membership and a university computing account</li>
    <li>Use of the university's sports, recreational and social facilities</li>
    <li>An invitation, at the Dean or Head of School's discretion, to continue contributing to academic work, including supervision of undergraduate and postgraduate taught projects subject to Board of Studies approval</li>
    <li>Appointment on an annual basis to supervise research students, provided they are not the lead supervisor</li>
    <li>Where the funder allows, to be named as a co-investigator on research funding</li>
</ul>

<p>Library access, a named affiliation, research supervision and co-investigator eligibility are genuinely useful to a working academic or a senior professional. They are not income, and they are not a route to residence.</p>

<h2 id="visa">It Is Not a UK Immigration Route</h2>

<p>This matters most if you are reading from outside the UK, so it gets stated plainly: <strong>an honorary or adjunct professorship will not get you a UK work visa.</strong></p>

<p>GOV.UK sets out what a Skilled Worker visa requires. You must "work for a UK employer that's been approved by the Home Office", have "a 'certificate of sponsorship' (CoS) from your employer", "do a job that's on the list of eligible occupations", and be paid a minimum salary. GOV.UK also states: "You must have a confirmed job offer before you apply for your visa."</p>

<p>On the money, GOV.UK's wording is: "The minimum salary for the type of work you'll be doing is whichever is the highest of: <strong>&pound;41,700 per year</strong> / the 'going rate' for the type of work you'll be doing." The current rules came into force on 22 July 2025.</p>

<p>Now hold that against an honorary title. There is no employer, because the policies say it is not an employment relationship. There is no certificate of sponsorship, because there is no job. There is no salary, because the title is unpaid by definition. It fails every limb of the test at once.</p>

<p>If your goal is to live and work in the UK, the honorary title is not the door. The paid teaching route is a better conversation, and it has its own hard truths about sponsorship that are set out in the companion guide.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/adjunct-professor-jobs-uk-pay.jpg" alt="Academic paperwork and a contract on a desk representing a university titles policy" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:14px;color:#666;margin-top:8px;">Honorary agreement or contract of employment. Which document you are handed decides whether the role pays.</figcaption>
</figure>

<h2 id="how-people-get-it">How People Actually Get One</h2>

<p>Since you cannot apply, the question becomes how people end up nominated. From the criteria the four policies publish, the pattern is consistent.</p>

<ol>
    <li><strong>Distinction in your own field, judged at the level of the title.</strong> Bath's test is whether you would be appointed at that level if you were staff. A Chair-equivalent reputation earns an Honorary Professorship; it is not given for occasional guest teaching.</li>
    <li><strong>An existing, substantive relationship with a department.</strong> Every route runs through a Dean or Head of School who already knows your work well enough to write the proposal. Collaboration comes first and the title follows it, never the other way round.</li>
    <li><strong>A defined contribution the university can name.</strong> Bath requires the proposal to include "details of the role which the nominated person will perform" during the appointment. Vague goodwill does not pass.</li>
    <li><strong>A CV that stands on its own,</strong> submitted with the proposal as evidence.</li>
</ol>

<p>The practical implication: if you want one, do not chase the title. Build a real research or teaching collaboration with a UK department, do work they value, and let the department raise it. A request from you for an honorary chair is not part of the process at any of the four universities whose policies were read for this page.</p>

<h2 id="how-to-check">How to Check Any Offer in Ten Minutes</h2>

<ol>
    <li><strong>Search the university's own site</strong> for "honorary titles policy" or "visiting and honorary academic appointments". These are public documents; all four used here were.</li>
    <li><strong>Find the remuneration section</strong> and read whether your category is permitted payment. Honorary and Emeritus normally are not; Visiting often is.</li>
    <li><strong>Ask which document you will be issued.</strong> A contract of employment means a paid job. An honorary agreement, letter of appointment or letter of association means an unpaid title.</li>
    <li><strong>Ask for the term and the review.</strong> Three years renewable, subject to annual review, is typical.</li>
    <li><strong>If the answer is unpaid and you need income,</strong> ask the same department about hourly-paid or associate lecturer work instead. That is a different conversation, a different form, and a different budget.</li>
</ol>

<h2 id="faq">Frequently Asked Questions</h2>

<h3>Are adjunct professor jobs in the UK paid?</h3>
<p>An honorary or adjunct professorship is normally not paid. The University of Bath states that "the conferment of the title is on an unpaid basis", and Heriot-Watt states that holders of honorary and emeritus titles "are not permitted to receive Remuneration from the University". A Visiting title is the exception: Heriot-Watt permits visiting title holders to be paid, agreed in advance of appointment.</p>

<h3>How do I apply for an adjunct professorship in the UK?</h3>
<p>You do not. The title is conferred, not applied for. At Bath a Dean of Faculty or Head of School submits a proposal to the Vice-Chancellor, who recommends conferment to Senate. Bath describes the title as "an honour in the gift of the University" with "no appeal process" if it is refused.</p>

<h3>What is the UK equivalent of an American adjunct professor?</h3>
<p>The paid, part-time, teaching-focused roles are called hourly-paid lecturer, associate lecturer, sessional lecturer or visiting lecturer. Those are jobs with contracts and hourly rates. The professorial titles are honours and sit in a different system entirely.</p>

<h3>Can I get a UK visa with an honorary professorship?</h3>
<p>No. A Skilled Worker visa requires an approved sponsor, a certificate of sponsorship, an eligible occupation and a salary of at least GBP 41,700 a year or the going rate, whichever is higher. An unpaid honorary title involves no employer, no certificate of sponsorship and no salary, so it fails every requirement.</p>

<h3>Can I hold an honorary title and a paid university job at the same time?</h3>
<p>Generally no, at the same institution. Heriot-Watt requires the honorary title to be "put into abeyance for the period of the paid work". Essex suspends the honorary agreement and issues a separate fixed-term contract for the paid work, reinstating the title only afterwards. Bath will not confer the title until any employment contract with the university has ended.</p>

<h3>How long does an honorary professorship last?</h3>
<p>Three years is the normal period at Bath, and it is renewable. Policies typically make titles subject to review, with Emeritus titles the usual exception to a fixed term.</p>

<h3>What do I actually get from the title?</h3>
<p>At Bath: membership of the university, library membership and a computing account, use of sports and social facilities, the possibility of supervising taught projects and research students where approved, and eligibility to be named as a co-investigator on research funding where the funder allows.</p>

<h3>Is "Visiting Professor" the same as "Honorary Professor" in the UK?</h3>
<p>Not in the policies. Heriot-Watt separates them explicitly: honorary and emeritus holders may not be paid, visiting holders may be. Always ask which category an offer falls into before assuming anything about money.</p>

<h2 id="people-also-search-for">People Also Search For</h2>

<h3>Honorary professor UK meaning</h3>
<p>A title conferred by a university on someone of standing in their field, normally unpaid, usually for a renewable three-year term.</p>

<h3>Visiting professor UK salary</h3>
<p>There is no general figure. Some universities permit visiting title holders to be paid for agreed work; the amount is settled in advance of appointment rather than published.</p>

<h3>UK university honorary titles policy</h3>
<p>The public document each institution publishes under its own Ordinances, setting out who may be nominated, who confers the title and whether payment is permitted.</p>

<h3>Difference between professor and lecturer UK</h3>
<p>Professor is the most senior academic rank in the UK, unlike the United States where most teaching staff are called professors. Lecturer is the standard academic post.</p>

<h3>Hourly paid lecturer UK</h3>
<p>The paid, part-time teaching role that people searching for adjunct work are usually actually looking for, with published hourly rates at many universities.</p>

<h3>Skilled Worker visa salary threshold</h3>
<p>GBP 41,700 a year or the going rate for the occupation, whichever is higher, under the rules that came into force on 22 July 2025.</p>

<h3>How to become a professor in the UK</h3>
<p>Through the substantive academic career track, which is a different process from the conferment of an honorary or visiting title.</p>

<h3>Emeritus professor meaning UK</h3>
<p>A title for a retired academic. Heriot-Watt groups it with honorary titles as one that may not be remunerated, and requires any contract of employment to have ended first.</p>

<h2 id="more-guides">More Job Guides</h2>

<ul>
    <li><a href="/blog/how-to-become-an-adjunct-lecturer-in-the-uk">How to Become an Adjunct Lecturer in the UK</a> &mdash; the paid route, what a "teaching hour" actually buys, and why hourly work cannot usually be sponsored.</li>
    <li><a href="/blog/adjunct-teaching-jobs-in-the-uk">Adjunct Teaching Jobs in the UK</a> &mdash; teaching alongside another career, and the exclusivity clause that turns out to be unenforceable.</li>
    <li><a href="/blog/adjunct-faculty-vacancies-in-the-uk">Adjunct Faculty Vacancies in the UK</a> &mdash; the four kinds of institution that hire part-time teachers, and why so little is advertised.</li>
    <li><a href="/blog/jobs-in-uk-for-foreigners">Jobs in UK for Foreigners</a> &mdash; the sponsorship routes that do work, and the ones that do not.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; teaching income that does not depend on a UK visa.</li>
    <li><a href="/blog/how-to-get-an-esl-teaching-job-in-japan">How to Get an ESL Teaching Job in Japan</a> &mdash; a teaching route with a far more accessible visa.</li>
    <li><a href="/blog/how-to-apply-for-heathrow-airport-jobs-in-the-uk">How to Apply for Heathrow Airport Jobs in the UK</a> &mdash; UK employment with a different vetting and sponsorship picture.</li>
</ul>

<h2 id="sources">Official Sources</h2>

<ul>
    <li><a href="https://www.bath.ac.uk/legal-information/honorary-and-visiting-academic-appointments/" rel="nofollow noopener" target="_blank">University of Bath &mdash; Honorary and Visiting Academic Appointments</a>, code of practice, approved 13 March 2024</li>
    <li><a href="https://www.hw.ac.uk/uk/services/policy-governance/senate/honorary-titles.htm" rel="nofollow noopener" target="_blank">Heriot-Watt University &mdash; Honorary, Visiting and Emeritus Titles Policy</a>, last reviewed 27 April 2022</li>
    <li><a href="https://www.essex.ac.uk/-/media/documents/directories/human-resources/visiting-and-honorary-academic-titles-policy-procedure.pdf" rel="nofollow noopener" target="_blank">University of Essex &mdash; Visiting and Honorary Academic Titles Policy and Procedure</a>, amended February 2026</li>
    <li><a href="https://www.qmul.ac.uk/human-resources/" rel="nofollow noopener" target="_blank">Queen Mary University of London &mdash; Honorary and Visiting Status and Titles Policy</a>, last reviewed January 2026</li>
    <li><a href="https://www.gov.uk/skilled-worker-visa" rel="nofollow noopener" target="_blank">GOV.UK &mdash; Skilled Worker visa</a>, including the eligibility and salary requirements</li>
</ul>

<p style="font-size:14px;color:#666;margin-top:26px;">Checked on 30 September 2026. University policies and immigration rules change; read the policy of the institution you are dealing with and confirm visa requirements on GOV.UK before acting. JobGader is not a recruiter and does not accept payment from applicants.</p>
HTML;
    }
}
