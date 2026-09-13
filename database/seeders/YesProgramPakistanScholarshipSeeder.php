<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The Kennedy-Lugar Youth Exchange and Study (YES) Program for Pakistani
 * school students, written up as a plain-English guide.
 *
 * Checked on 14 September 2026 against the U.S. Embassy Islamabad announcement
 * for the Spring 2027 semester and the official Pakistani application site run
 * by the Society for International Education. Corrections to the brief and to
 * the four posters supplied with it:
 *
 * 1. The brief and all four posters call this a year abroad ("One Year. One
 *    Lifetime Impact.", "Spring 2027 - Spring 2028", "return June 2028").
 *    The round now open is the SPRING 2027 SEMESTER: students leave in
 *    January 2027 and come home in June 2027. It is roughly six months.
 *
 * 2. Three posters print "April 2027 Departure" and the brief's timeline
 *    repeats it. The programme begins in January 2027.
 *
 * 3. Every poster prints "YOUR COST: $0 (100% FREE!)" and the brief states
 *    "آپ کو دینا ہے $0 (ZERO)". Passport costs, the required medical
 *    examinations and extra spending money are NOT covered. A family that
 *    budgets nothing will be caught out by the passport alone.
 *
 * 4. The posters and the brief put the package at "$25,000 - $30,000". Neither
 *    the State Department nor the Pakistani administrator publishes a cash
 *    value for the scholarship, so the figure is not reproduced here.
 *
 * 5. The brief gives the contact address as yesprogram@state.gov. The
 *    administrator's address is info@yesprogram.pk.
 *
 * 6. The brief claims "50-100 selected from Pakistan", "4,000+ worldwide" and
 *    a "15-20%" success rate. None of these is published. What is on record is
 *    that more than 1,000 Pakistani students have taken part since 2003.
 *
 * 7. The brief lists "VISA-FREE Travel" as a future benefit of the programme.
 *    It confers no such thing.
 *
 * 8. The brief's FAQ answers a question about going home for the summer break
 *    and about staying "the whole year". Neither applies to a January-June
 *    semester.
 *
 * The posters' eligibility box (ages 15-17, grades 8, 9, 10 and O-Levels) and
 * the 15 September 2026 deadline are correct and are used as supplied.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class YesProgramPakistanScholarshipSeeder extends Seeder
{
    public const SLUG = 'kennedy-lugar-yes-program-pakistan';

    public const APPLY_URL = 'https://www.yesprogram.pk/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Kennedy-Lugar YES Program Pakistan 2027',
                'provider' => 'U.S. Department of State',
                'country' => 'United States',
                'city' => 'Nationwide',
                'study_level' => 'High School (Grades 8, 9, 10 and O Levels)',
                'funding_type' => 'Fully Funded Exchange',
                'award_value' => 'Airfare, tuition, host family and $200 a month',
                'deadline' => '2026-09-15',
                'deadline_note' => 'Applications closed 15 Sep 2026 for Spring 2027',
                'excerpt' => 'It is one semester, not one year: YES students leave in January 2027 and return in June 2027. Applications close on 15 September 2026, and passport and medical examination costs are not covered by the scholarship.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'YES Program Pakistan 2027: Deadline and Real Rules',
                'meta_description' => 'The Kennedy-Lugar YES Program sends Pakistani students to a US high school free. The 15 September 2026 deadline, the real dates and what is not covered.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-13 18:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return <<<'HTML'
<p>The <strong>Kennedy-Lugar Youth Exchange and Study (YES) Program</strong> is funded by the <strong>U.S. Department of State</strong>. It places high school students from Pakistan with an American host family, enrols them in a local American high school and pays for it. In Pakistan it is administered by the <strong>Society for International Education (SIE)</strong> together with iEARN Pakistan.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Read this before you read anything else.</strong> The round now open is the <strong>Spring 2027 semester</strong>. Students leave in <strong>January 2027</strong> and return in <strong>June 2027</strong> &mdash; about six months, not a year. Posters and social media posts circulating with "One Year", "April 2027 Departure" or "return in 2028" are describing something that is not on offer. Applications close on <strong>15 September 2026</strong>.</p>
</div>

<h2>YES Program Pakistan at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>Programme</th><td>Kennedy-Lugar Youth Exchange and Study (YES)</td></tr>
<tr><th>Funded by</th><td>U.S. Department of State</td></tr>
<tr><th>Run in Pakistan by</th><td>Society for International Education (SIE) with iEARN Pakistan</td></tr>
<tr><th>Round now open</th><td>Spring semester 2027</td></tr>
<tr><th>You leave</th><td>January 2027</td></tr>
<tr><th>You come home</th><td>June 2027</td></tr>
<tr><th>How long</th><td>One school semester, roughly six months</td></tr>
<tr><th>Who can apply</th><td>Pakistani citizens aged 15 to 17 on 1 January 2027, in grade 8, 9 or 10 or O Levels</td></tr>
<tr><th>Date of birth</th><td>Between 1 January 2010 and 1 January 2012</td></tr>
<tr><th>Marks needed</th><td>At least 60% in each of the last three academic years, and at least 60% in English, Mathematics and Sciences</td></tr>
<tr><th>Monthly stipend</th><td>$200</td></tr>
<tr><th>Deadline</th><td>15 September 2026</td></tr>
<tr><th>Apply at</th><td>yesprogram.pk</td></tr>
</tbody>
</table></div>

<h2>What the Scholarship Pays For</h2>
<p>YES covers the major costs of the exchange. What it covers is generous and specific:</p>
<ul>
<li><strong>Round-trip airfare</strong> from Pakistan to the United States and back</li>
<li><strong>U.S. visa costs</strong></li>
<li><strong>School tuition</strong> at an American high school</li>
<li><strong>Accommodation and meals</strong> with a screened American host family</li>
<li><strong>Health insurance</strong> for the duration of the programme</li>
<li><strong>Programme activities</strong> and pre-departure orientation</li>
<li>A <strong>monthly stipend of $200</strong> for personal expenses</li>
</ul>

<h2>What It Does Not Pay For</h2>
<p>This is the part that is missing from almost every poster and forwarded message about YES, and it is the part that costs families money.</p>
<div class="scholar-note scholar-note-warn">
<p>The programme does <strong>not</strong> cover:</p>
<ul>
<li><strong>Your passport.</strong> If your child does not already hold one, that is your cost and your queue.</li>
<li><strong>The required medical examinations</strong> and any immunisations.</li>
<li><strong>Extra spending money</strong> beyond the $200 monthly stipend.</li>
</ul>
<p>So "100% free" is close to the truth but not the whole truth. Budget for a passport and a medical before you are asked for them.</p>
</div>

<p>You will also see the package valued at "$25,000 to $30,000" on posters and in forwarded messages. Neither the State Department nor the Pakistani administrator publishes a cash value for a YES place, so treat any such figure as somebody's estimate rather than a number you can rely on.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kennedy-lugar-yes-program-pakistan-students.jpg" alt="Kennedy-Lugar YES Program Pakistan students who will study at an American high school" width="1200" height="628" loading="lazy">
<figcaption>YES students live with a screened American host family and attend a local high school.</figcaption>
</figure>

<h2>Who Can Apply</h2>

<h3>Age and date of birth</h3>
<p>You must be <strong>15 to 17 years old on 1 January 2027</strong>, which means you must have been born <strong>between 1 January 2010 and 1 January 2012</strong>. This is an absolute cut-off. If you turn 15 in February 2027 you cannot apply this round, and if you turned 18 before the start you cannot either.</p>

<h3>Which class you are in</h3>
<ul>
<li>Grade <strong>8, 9 or 10</strong> in a Pakistani secondary school, or</li>
<li><strong>O Levels</strong> &mdash; but only students in the equivalent of grade 9 or 10</li>
</ul>
<p>O Level students in the eleventh grade equivalent <strong>cannot</strong> apply. Neither can anyone who has already completed or graduated from secondary school before the programme begins.</p>

<h3>Marks</h3>
<ul>
<li>At least <strong>60% in each of the past three academic years</strong></li>
<li>At least <strong>60% in the major subjects</strong>: English, Mathematics and Sciences</li>
<li><strong>No failed subjects</strong> in the past three years</li>
<li><strong>No repeated year and no gap year</strong> &mdash; your schooling has to be continuous</li>
</ul>

<h3>Citizenship</h3>
<ul>
<li>You must be a <strong>citizen of Pakistan</strong> and <strong>living in Pakistan</strong></li>
<li><strong>Dual nationals of any country will not be considered.</strong> This one disqualifies more applicants than families expect</li>
</ul>

<h3>English</h3>
<p>You must be able to <strong>communicate in English</strong>. You do not need IELTS or TOEFL to apply. The selection process includes its own English proficiency test, so what matters is that you can actually hold a conversation and write clearly, not that you hold a certificate.</p>

<h2>Who Cannot Apply</h2>
<p>Beyond the age, marks and citizenship rules above, these exclusions apply:</p>
<ul>
<li>Anyone who has <strong>previously travelled to the United States</strong></li>
<li><strong>Dual nationals</strong> of any country</li>
<li>Students with a <strong>gap year or a repeated year</strong></li>
<li>Students who have <strong>already finished secondary school</strong> before the programme starts</li>
<li><strong>Children and siblings of YES alumni</strong>, and of staff of the Society for International Education and iEARN Pakistan</li>
</ul>

<h2>How the Selection Works</h2>
<p>YES is a merit-based competition, and it is a real one. The stages are:</p>
<ol>
<li><strong>Submit the online application</strong> at yesprogram.pk before the deadline</li>
<li><strong>English proficiency examination</strong></li>
<li><strong>A proctored essay</strong> &mdash; written under supervision, not at home</li>
<li><strong>Review of the completed application</strong> and school records</li>
<li><strong>Interview</strong></li>
<li>Final selection, then visa processing and a pre-departure orientation</li>
</ol>

<div class="scholar-note">
<p><strong>The essay is written under supervision.</strong> That is worth knowing in advance: there is no point preparing a polished essay written by somebody else, because you will be asked to write in a controlled setting. Practise writing about yourself, by hand, against a clock.</p>
</div>

<h2>How Many Students Get In</h2>
<p>Neither the State Department nor the Pakistani administrator publishes an annual intake number or a success rate for Pakistan, so any specific figure you see quoted &mdash; "50 to 100 selected", "15 to 20 per cent" &mdash; is not something you can check. What is on record is that <strong>more than 1,000 Pakistani students</strong> have taken part since the programme began in 2003.</p>

<p>The practical conclusion is the same either way: it is competitive, the criteria are strictly applied, and an application that misses a stated requirement will not be rescued by a good essay.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kennedy-lugar-yes-program-pakistan-campus.jpg" alt="YES Program Pakistan students on an American high school campus" width="1200" height="628" loading="lazy">
<figcaption>The Spring 2027 round runs from January to June 2027, not a full academic year.</figcaption>
</figure>

<h2>What to Prepare</h2>
<ul>
<li><strong>School results for the last three years</strong> &mdash; you need to show 60% in each year and in the core subjects</li>
<li><strong>Identity documents</strong>: B-Form or CNIC and your birth certificate, so the 1 January 2010 to 1 January 2012 window can be verified</li>
<li><strong>A passport</strong> if you have one; if not, start the process now, because it is your cost and it takes time</li>
<li><strong>Parent or guardian consent</strong></li>
<li><strong>Your own writing.</strong> The personal statement and the proctored essay both need to sound like a 15-to-17-year-old, because they will be compared</li>
</ul>

<h2>Warnings Worth Taking Seriously</h2>
<div class="scholar-note scholar-note-warn">
<ul>
<li><strong>YES never charges an application fee.</strong> Anyone asking you to pay to apply, or offering guaranteed selection, is not connected to the programme.</li>
<li><strong>Apply only through yesprogram.pk.</strong> The administrator has publicly warned about fake pages using the YES name.</li>
<li><strong>The contact address is info@yesprogram.pk.</strong> A widely forwarded version of this announcement gives "yesprogram@state.gov", which is not the programme's Pakistani contact.</li>
<li><strong>YES does not give you visa-free travel</strong> to the United States afterwards, whatever a forwarded message claims. It is a J-1 exchange programme with a return requirement.</li>
</ul>
</div>

<h2>Official Contacts</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>Apply</th><td>yesprogram.pk</td></tr>
<tr><th>Email</th><td>info@yesprogram.pk</td></tr>
<tr><th>Phone</th><td>0333-2929960</td></tr>
<tr><th>Office</th><td>Society for International Education, 88-H, P.E.C.H.S. Block-6, Karachi</td></tr>
<tr><th>Announcement</th><td>U.S. Embassy Islamabad, "YES Program: Spring Semester 2027"</td></tr>
</tbody>
</table></div>

<h2>Frequently Asked Questions</h2>

<h3>Is the YES Program one year or one semester?</h3>
<p>The round open now is the Spring 2027 semester: students leave in January 2027 and return in June 2027, about six months. Posters describing "one year" or a return in 2028 do not match this round.</p>

<h3>When is the YES Program Pakistan deadline?</h3>
<p>15 September 2026, for the Spring 2027 semester.</p>

<h3>Who is eligible for the YES Program in Pakistan?</h3>
<p>Pakistani citizens living in Pakistan, aged 15 to 17 on 1 January 2027 and born between 1 January 2010 and 1 January 2012, enrolled in grade 8, 9 or 10 or in O Levels, with at least 60% in each of the past three years and in English, Mathematics and Sciences.</p>

<h3>Is the YES Program really free?</h3>
<p>It covers airfare, US visa costs, school tuition, host family accommodation, health insurance, programme activities and a $200 monthly stipend. It does not cover your passport, the required medical examinations or extra spending money.</p>

<h3>Do I need IELTS or TOEFL for the YES Program?</h3>
<p>No. You need to be able to communicate in English, and the selection process includes its own English proficiency examination and a proctored essay.</p>

<h3>Can dual nationals apply for the YES Program?</h3>
<p>No. Dual nationals of any country are not considered, and applicants who have previously travelled to the United States are also excluded.</p>

<h3>Can O Level students apply?</h3>
<p>Yes, but only those in the grade 9 or grade 10 equivalent. O Level students in the eleventh grade equivalent cannot apply, and neither can anyone who has already completed secondary school.</p>

<h3>How much is the YES Program monthly stipend?</h3>
<p>Pakistani participants receive $200 a month for personal expenses.</p>

<h3>Does the YES Program lead to a US green card or visa-free travel?</h3>
<p>No. It is a J-1 exchange programme for school students with a requirement to return home, and it confers no future immigration benefit.</p>

<h2>Other Fully Funded Options</h2>
<p>If you are older than the YES age window, these guides cover funded study abroad at university level:</p>
<ul>
<li><a href="/scholarships/university-of-sydney-rtp-international-scholarship">University of Sydney RTP International Scholarship</a> &mdash; a funded research degree in Australia.</li>
<li><a href="/scholarships/monash-university-rtp-scholarship">Monash University RTP Scholarship</a> &mdash; stipend and tuition for PhD study.</li>
<li><a href="/scholarships/university-of-pavia-scholarships">University of Pavia Scholarships</a> &mdash; fee waivers and grants in Italy.</li>
</ul>

<p><em>JobGader is not part of the U.S. Department of State, the Society for International Education or iEARN Pakistan. This guide was checked against the U.S. Embassy Islamabad announcement and the official Pakistani programme site on 14 September 2026. Dates, criteria and what is covered can change, so confirm them on yesprogram.pk before you apply.</em></p>
HTML;
    }
}
