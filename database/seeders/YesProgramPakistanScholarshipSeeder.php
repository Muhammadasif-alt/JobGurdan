<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The Kennedy-Lugar Youth Exchange and Study (YES) Program for Pakistani
 * school students, written up as a plain-English guide.
 *
 * Checked on 14 September 2026, line by line, against the U.S. Embassy
 * Islamabad announcement "YES Program: Spring Semester 2027" and the official
 * Pakistani programme site yesprogram.pk, run by the Society for International
 * Education. Nothing here comes only from news or scholarship aggregator sites.
 * Corrections to the brief and to the four posters supplied with it:
 *
 * 1. The brief says to apply online. The Embassy says the application is a
 *    fillable PDF that must be printed, signed by hand in black ballpoint pen
 *    and sent with school-attested mark sheets "by post or courier", and that
 *    "APPLICATIONS WILL ONLY BE ACCEPTED THROUGH COURIER/POSTAL SERVICES". The
 *    15 September 2026 date is the last date for RECEIVING applications.
 *
 * 2. The brief and all four posters call this a year abroad ("One Year. One
 *    Lifetime Impact.", "Spring 2027 - Spring 2028", "return June 2028"). The
 *    round now open is the Spring 2027 semester: travel in January 2027 and
 *    return home in June 2027.
 *
 * 3. Three posters print "April 2027 Departure" and the brief's timeline
 *    repeats it. The programme begins in January 2027.
 *
 * 4. Every poster prints "YOUR COST: $0 (100% FREE!)". The official site says
 *    the programme does not provide the cost of obtaining a passport, required
 *    medical examinations and immunizations, or extra pocket money.
 *
 * 5. The posters and the brief put the package at "$25,000 - $30,000". No
 *    official page publishes a cash value, so the figure is not reproduced.
 *
 * 6. The brief gives the contact address as yesprogram@state.gov. The
 *    official contact is info@yesprogram.pk.
 *
 * 7. The brief claims "50-100 selected from Pakistan", "4,000+ worldwide" and
 *    a "15-20%" success rate. None of these is published.
 *
 * 8. News coverage of this round quotes a $200 monthly stipend, US visa costs
 *    and school tuition as covered. The official list says "a modest monthly
 *    stipend" and names neither visa costs nor tuition, so none of the three is
 *    stated here.
 *
 * 9. The brief lists "VISA-FREE Travel" as a future benefit. YES is a J-1
 *    exchange programme and confers no such thing.
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
                'award_value' => 'Airfare, host family, stipend and health insurance',
                'deadline' => '2026-09-15',
                // No later round has been announced, so the card reads "Closes"
                // until the date and "Closed" after it, with no note under it.
                'deadline_note' => null,
                'excerpt' => 'It is one semester, not one year: January to June 2027. Applications are not accepted online. They go by post or courier to Karachi and must arrive by 15 September 2026, and passport and medical costs are not covered.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'YES Program Pakistan 2027: Deadline and Real Rules',
                'meta_description' => 'YES Program Pakistan 2027: a January to June semester at a US high school. Applications go by courier and must arrive by 15 September 2026.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-13 18:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return <<<'HTML'
<p>The <strong>Kennedy-Lugar Youth Exchange and Study (YES) Program</strong> is a U.S. Department of State exchange, established by Congress in October 2002. It sends high school students from Pakistan to live with an American host family and attend a U.S. high school. In Pakistan it is run by the <strong>Society for International Education (SIE)</strong> and iEARN Pakistan.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Two things to know before anything else.</strong></p>
<ul>
<li><strong>You cannot apply online.</strong> The application is a PDF form that you fill in, print, sign by hand and send by post or courier to Karachi. The U.S. Embassy states that applications will only be accepted through courier or postal services, and <strong>15 September 2026 is the last date for receiving them</strong> &mdash; the date the envelope must arrive, not the date you send it.</li>
<li><strong>It is one semester.</strong> The round now open is the <strong>Spring 2027 semester</strong>: you travel in <strong>January 2027</strong> and return home in <strong>June 2027</strong> &mdash; about six months, not a year. Posters and social media posts circulating with "One Year", "April 2027 Departure" or "return in 2028" are describing something that is not on offer.</li>
</ul>
</div>

<h2>YES Program Pakistan at a Glance</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>Programme</th><td>Kennedy-Lugar Youth Exchange and Study (YES)</td></tr>
<tr><th>Funded by</th><td>U.S. Department of State</td></tr>
<tr><th>Run in Pakistan by</th><td>Society for International Education (SIE) and iEARN Pakistan</td></tr>
<tr><th>Round now open</th><td>Spring semester 2027</td></tr>
<tr><th>Travel</th><td>January 2027</td></tr>
<tr><th>Return home</th><td>June 2027</td></tr>
<tr><th>Who can apply</th><td>Pakistani citizens aged 15 to 17 by 1 January 2027, in grade 8, 9 or 10 or O Level grade 9 or 10</td></tr>
<tr><th>Date of birth</th><td>Between 1 January 2010 and 1 January 2012</td></tr>
<tr><th>Marks needed</th><td>60% in each academic year, 60% in English, Mathematics and Sciences, and no failing grades in the past three years</td></tr>
<tr><th>How to apply</th><td>Download the form from yesprogram.pk, then send it by post or courier</td></tr>
<tr><th>Must arrive by</th><td>15 September 2026</td></tr>
</tbody>
</table></div>

<h2>How to Apply: By Post or Courier Only</h2>
<p>This is where most applications go wrong, so follow the U.S. Embassy's instructions exactly.</p>
<ol>
<li><strong>Open yesprogram.pk</strong>, check the eligibility criteria and use the "Apply Now" button to get the application form. It is a <strong>fillable PDF</strong>, so type your answers on a computer.</li>
<li><strong>Print it in black and white.</strong> Colour printing is not required.</li>
<li><strong>Check every page</strong> for missing or wrong information.</li>
<li><strong>Sign by hand</strong> where indicated, and fill in anything else that must be handwritten, using only a black ballpoint pen. Blue or coloured ink, pencils and other pens are not accepted.</li>
<li><strong>Attach the documents</strong> listed below.</li>
<li><strong>Send it by post or courier</strong> to: YES Program: Spring Semester 2027 Application, Society for International Education, 88-H, P.E.C.H.S. Block-6, Karachi. Tel: 0333-2929960.</li>
</ol>

<div class="scholar-note scholar-note-warn">
<p><strong>Applications that are incomplete, unsigned, or do not follow these instructions may not be considered.</strong> And because 15 September 2026 is the last date for <em>receiving</em> applications, allow for courier time to Karachi. An application that leaves your city on the deadline day will arrive too late.</p>
</div>

<h3>Documents to attach</h3>
<ul>
<li><strong>Photocopies of your last three years' mark sheets</strong>, each school attested (stamped and signed by a school official): the final mark sheets for 2025&ndash;26, 2024&ndash;25 and 2023&ndash;24</li>
<li><strong>Cambridge (CAIE) students</strong> must send their final mock results for all the core subjects in the criteria, not their CAIE results</li>
<li><strong>A photocopy of one identity document</strong>: NADRA B-Form, Smart Card, Family Registration Certificate (FRC) or birth certificate</li>
</ul>
<p>Photocopies must be clear, readable and complete. Blurred or dark copies, and incomplete copies of mark sheets, will not be considered.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kennedy-lugar-yes-program-pakistan-students.jpg" alt="Kennedy-Lugar YES Program Pakistan students who will study at an American high school" width="1200" height="628" loading="lazy">
<figcaption>YES students live with an American host family and attend a U.S. high school.</figcaption>
</figure>

<h2>Who Can Apply</h2>
<p>The U.S. Embassy lists these requirements. You must meet every one.</p>

<h3>Age and date of birth</h3>
<p>You must be <strong>15 to 17 by the start of the programme, 1 January 2027</strong>, and born <strong>between 1 January 2010 and 1 January 2012</strong>. The official site has an age calculator if you are close to either end.</p>

<h3>Your class</h3>
<ul>
<li>Enrolled in a secondary school in Pakistan, in <strong>8th, 9th or 10th grade or O Levels</strong> when you apply</li>
<li>Only <strong>O Level grade 9 or grade 10</strong> students may apply. <strong>O Level 11th graders are not eligible</strong></li>
<li>Anyone who has <strong>completed or graduated from secondary school</strong> before the programme starts is not eligible</li>
</ul>

<h3>Marks</h3>
<ul>
<li>A total of at least <strong>60% in each academic year</strong></li>
<li>At least <strong>60% in all major subjects</strong>: English, Mathematics and Sciences</li>
<li><strong>No failing grades</strong> in the past three years (2025&ndash;26, 2024&ndash;25 and 2023&ndash;24)</li>
<li>Students with a <strong>gap year or a repeated year</strong> are not eligible</li>
</ul>

<h3>English</h3>
<p>You must be <strong>able to communicate in English</strong>. The criteria do not ask for an IELTS or TOEFL score. Shortlisted applicants take an English proficiency exam as part of selection.</p>

<h3>Citizenship and visa</h3>
<ul>
<li>A <strong>citizen of Pakistan, living in Pakistan</strong></li>
<li><strong>Dual nationals of any country will not be considered</strong></li>
<li>You must <strong>meet U.S. J-1 visa eligibility requirements</strong></li>
<li>You must <strong>not have travelled to the United States</strong></li>
</ul>

<h3>Family exclusions</h3>
<ul>
<li>Siblings and children of <strong>YES Program alumni</strong> are not eligible</li>
<li>Siblings and children of <strong>Society for International Education and iEARN Pakistan project staff</strong> are not eligible</li>
</ul>

<h2>What the Programme Provides</h2>
<p>According to the official site, YES provides:</p>
<ul>
<li><strong>Round-trip airfare</strong> from Pakistan to the United States</li>
<li>The cost of a <strong>pre-departure orientation</strong></li>
<li><strong>Placement with a U.S. host family</strong></li>
<li><strong>A modest monthly stipend</strong></li>
<li><strong>Health insurance</strong></li>
<li>The cost of <strong>programme activities and materials</strong></li>
</ul>

<h2>What It Does Not Provide</h2>
<div class="scholar-note scholar-note-warn">
<p>The official site says YES does <strong>not</strong> provide:</p>
<ul>
<li>The <strong>cost of obtaining a passport</strong></li>
<li><strong>Required medical examinations and immunizations</strong></li>
<li><strong>Extra pocket money</strong> while on the programme</li>
</ul>
<p>So "100% free" is close to the truth but not the whole truth. Budget for a passport and a medical.</p>
</div>

<p>You will also see the package valued at "$25,000 to $30,000" on posters. No official page publishes a cash value for a YES place, and the official site does not give the stipend as a figure either &mdash; it says "a modest monthly stipend". Treat amounts quoted elsewhere as other people's estimates.</p>

<h2>How the Selection Works</h2>
<p>The official site describes multiple rounds. <strong>Shortlisted applicants</strong> are required to:</p>
<ol>
<li>Take an <strong>English proficiency exam</strong></li>
<li>Write a <strong>proctored essay</strong> &mdash; under supervision, not at home</li>
<li>Complete a <strong>YES program application</strong></li>
<li>Take part in an <strong>interview</strong></li>
</ol>
<p>All finalists are selected on merit. The official site also warns that <strong>false or forged documents or information, or any discrepancy found at any stage</strong>, will count against the applicant, so make sure every mark sheet and date matches exactly.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/kennedy-lugar-yes-program-pakistan-campus.jpg" alt="YES Program Pakistan students on an American high school campus" width="1200" height="628" loading="lazy">
<figcaption>The Spring 2027 round runs from January to June 2027, not a full academic year.</figcaption>
</figure>

<h2>Avoiding Fake Pages</h2>
<div class="scholar-note scholar-note-warn">
<ul>
<li>SIE/YES Pakistan says it is <strong>not responsible for information from any fake website or Facebook page</strong>. Use only yesprogram.pk and the programme's official Facebook page.</li>
<li><strong>Questions go to info@yesprogram.pk</strong> or the official Facebook page. A widely forwarded version of this announcement gives "yesprogram@state.gov", which is not the programme's Pakistani contact.</li>
<li><strong>YES does not give you visa-free travel</strong> to the United States afterwards, whatever a forwarded message claims. It is a J-1 exchange programme.</li>
</ul>
</div>

<h2>Official Contacts</h2>
<div class="scholar-table"><table>
<tbody>
<tr><th>Website</th><td>yesprogram.pk</td></tr>
<tr><th>Email</th><td>info@yesprogram.pk</td></tr>
<tr><th>Phone</th><td>0333-2929960</td></tr>
<tr><th>Send applications to</th><td>YES Program: Spring Semester 2027 Application, Society for International Education, 88-H, P.E.C.H.S. Block-6, Karachi</td></tr>
<tr><th>Announcement</th><td>U.S. Embassy &amp; Consulates in Pakistan, "YES Program: Spring Semester 2027"</td></tr>
</tbody>
</table></div>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply for the YES Program Pakistan online?</h3>
<p>No. You fill in the PDF form from yesprogram.pk, print it, sign it by hand in black ballpoint pen and send it with your documents by post or courier to the Society for International Education in Karachi. The U.S. Embassy says applications are only accepted through courier or postal services.</p>

<h3>When is the YES Program Pakistan deadline?</h3>
<p>15 September 2026 is the last date for receiving applications for the Spring 2027 semester, so the envelope has to reach Karachi by then.</p>

<h3>Is the YES Program one year or one semester?</h3>
<p>The round open now is the Spring 2027 semester: students travel to the United States in January 2027 and return home in June 2027.</p>

<h3>Who is eligible for the YES Program in Pakistan?</h3>
<p>Pakistani citizens living in Pakistan, aged 15 to 17 by 1 January 2027 and born between 1 January 2010 and 1 January 2012, in 8th, 9th or 10th grade or O Level grade 9 or 10, with 60% in each academic year and in English, Mathematics and Sciences, and no failing grades in the past three years.</p>

<h3>What documents do I send with the YES application?</h3>
<p>School-attested photocopies of your final mark sheets for 2025&ndash;26, 2024&ndash;25 and 2023&ndash;24 (Cambridge students send final mock results for the core subjects), and a photocopy of your B-Form, Smart Card, FRC or birth certificate.</p>

<h3>Is the YES Program really free?</h3>
<p>It provides round-trip airfare, a pre-departure orientation, host family placement, a modest monthly stipend, health insurance and programme activities. It does not provide the cost of a passport, required medical examinations and immunizations, or extra pocket money.</p>

<h3>Do I need IELTS or TOEFL for the YES Program?</h3>
<p>No score is required. You must be able to communicate in English, and shortlisted applicants take an English proficiency exam and write a proctored essay.</p>

<h3>Who cannot apply for the YES Program?</h3>
<p>Dual nationals, anyone who has travelled to the United States, students with a gap year or repeated year, O Level 11th graders, anyone who has finished secondary school, and siblings and children of YES alumni or of SIE and iEARN Pakistan project staff.</p>

<h2>Other Fully Funded Options</h2>
<p>If you are older than the YES age window, these guides cover funded study abroad at university level:</p>
<ul>
<li><a href="/scholarships/university-of-sydney-rtp-international-scholarship">University of Sydney RTP International Scholarship</a> &mdash; a funded research degree in Australia.</li>
<li><a href="/scholarships/monash-university-rtp-scholarship">Monash University RTP Scholarship</a> &mdash; stipend and tuition for PhD study.</li>
<li><a href="/scholarships/university-of-pavia-scholarships">University of Pavia Scholarships</a> &mdash; fee waivers and grants in Italy.</li>
</ul>

<p><em>JobGader is not part of the U.S. Department of State, the Society for International Education or iEARN Pakistan. This guide was checked against the U.S. Embassy &amp; Consulates in Pakistan announcement and yesprogram.pk on 14 September 2026. Confirm the details on yesprogram.pk before you apply.</em></p>
HTML;
    }
}
