<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "France scholarships without IELTS" — the Eiffel programme, the Campus
 * France Pakistan climate-change call and the smaller awards in the owner's
 * brief, rewritten around what each one actually pays and who can apply.
 *
 * Checked on 14 September 2026 against Campus France's Eiffel programme page
 * and brochures, the Eiffel regulations (September 2024 update) linked from
 * Campus France Pakistan, the Campus France Pakistan "France Excellence
 * Pakistan - Climate Change" 2026 call, Sorbonne Université and Sciences Po's
 * Eiffel pages, Service-Public's notice on 2026-2027 fees, UNHCR's UNIV'R
 * call, Classes Internationales and Studely. Corrections to the brief:
 *
 * 1. PhD stipend "€1,400" or "€2,100". Since 1 January 2024 it is €1,800 a
 *    month. The master's allowance is €1,181 a month; the brief's "€1,200"
 *    alternative has no source.
 *
 * 2. "Full tuition coverage". Eiffel does not pay tuition. French government
 *    scholarship holders are exempt from tuition only for national degrees at
 *    public institutions under the Ministry of Higher Education.
 *
 * 3. "Officially open for 2026" with a "January 8, 2026" deadline and a
 *    "December 10, 2025" institution date. That call closed; results came
 *    from 30 March 2026, not "beginning of April". Internal deadlines vary by
 *    institution, and no 2027 dates are published yet.
 *
 * 4. Age limits split by "developing" and "industrialised" countries. Campus
 *    France encourages applicants up to 29 at master's level and up to 35 at
 *    PhD level, with no country split.
 *
 * 5. Sciences Po as an example institution. Sciences Po will not take part in
 *    the 2027 Eiffel selection.
 *
 * 6. Annual value "€14,000-€18,000" and "€25,200". Twelve months of the
 *    allowance come to €14,172 (master's) and €21,600 (PhD).
 *
 * 7. Classes Internationales "A2 before joining". The programme is for
 *    students from seven named countries, not Pakistan, who already hold A2,
 *    and it is not a scholarship.
 *
 * 8. Studely "6 months or more" and the Ministry's social-criteria grants for
 *    "certain foreign students". Studely's grant is a one-off €1,000 for 30
 *    students; the social-criteria grant needs two years' residence and a
 *    tax household in France.
 *
 * The posters are used as supplied, at the owner's instruction; the text
 * corrects them.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class FranceScholarshipsWithoutIeltsScholarshipSeeder extends Seeder
{
    public const SLUG = 'france-scholarships-without-ielts';

    public const APPLY_URL = 'https://www.campusfrance.org/en/france-excellence-eiffel-scholarship-program';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'France Scholarships Without IELTS 2026: Eiffel and Pakistan Routes',
                'provider' => 'French Government (Campus France) and French institutions',
                'country' => 'France',
                'city' => 'Multiple cities',
                'study_level' => "Master's, PhD",
                'funding_type' => 'Fully Funded + Partial',
                'award_value' => '€1,181 a month (master\'s) or €1,800 a month (PhD) with Eiffel',
                'deadline' => null,
                'deadline_note' => 'Eiffel 2027 call expected end of September 2026; apply through a French institution',
                'excerpt' => 'Eiffel sets no English test of its own; your programme does. It pays €1,181 a month for master\'s and €1,800 for PhDs, does not cover tuition, and only a French institution can nominate you. The 2026 call closed on 8 January 2026.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'France Scholarships Without IELTS 2026: Eiffel Facts',
                'meta_description' => 'Eiffel pays €1,181 a month for master\'s and €1,800 for PhDs, not tuition, and only a French institution can apply. English rules and Pakistan routes.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-14 06:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>"France scholarships without IELTS" is a real search with a half-true answer. The French government's flagship award, the <strong>France Excellence Eiffel scholarship</strong>, sets no English test of its own, and many French master's programmes are taught in English. But the scholarship is not the admission. <strong>The programme that admits you decides what proof of English it accepts</strong>, and for Pakistani students the Campus France procedure looks at your English too. This guide sets out what Eiffel pays, who can put you forward, and the one French government call written only for Pakistani students.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you plan around the posters:</strong></p>
<ul>
<li><strong>The 2026 Eiffel call has closed.</strong> Institutions had to send files to Campus France by 8 January 2026, and results came out from 30 March 2026. No 2027 dates are published yet.</li>
<li><strong>You cannot apply for Eiffel yourself.</strong> Only applications submitted by French higher education institutions are accepted.</li>
<li><strong>Eiffel does not pay tuition fees.</strong> A monthly allowance, travel, insurance and housing help are covered; tuition is not.</li>
<li><strong>Sciences Po will not take part</strong> in the 2027 Eiffel selection, although many lists still name it.</li>
</ul>
</div>

<h2>France Scholarships at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Scholarship</th><th>Level</th><th>What it gives</th><th>Who applies</th><th>Status</th></tr></thead>
<tbody>
<tr><td>France Excellence Eiffel</td><td>Master's, PhD</td><td>&euro;1,181 a month (master's) or &euro;1,800 (PhD), plus travel, insurance and housing help</td><td>The French institution nominates you</td><td>2026 call closed; the call usually opens at the end of September</td></tr>
<tr><td>France Excellence Pakistan &ndash; Climate Change</td><td>Master's</td><td>Tuition, monthly allowance, return ticket, visa and Etudes en France fee waiver</td><td>Pakistani nationals, through Etudes en France</td><td>2026 call closed on 30 November 2025</td></tr>
<tr><td>UNIV'R (UNHCR and AUF)</td><td>Master's at Sciences Po</td><td>Support for recognised refugees</td><td>Refugees only</td><td>2026 cohort closed on 1 February 2026</td></tr>
<tr><td>Classes Internationales</td><td>Pathway to a Bachelor's</td><td>A year of French, then a French-taught degree</td><td>Seven named countries, not Pakistan</td><td>Not a scholarship</td></tr>
<tr><td>Studely grant</td><td>Any</td><td>&euro;1,000, once</td><td>30 students chosen by a private company</td><td>2025 edition closed on 30 June 2025</td></tr>
</tbody>
</table></div>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/{slug}-overview.jpg" alt="France scholarships without IELTS 2026 poster with the Eiffel Tower and the Seine" width="1200" height="628" loading="lazy">
<figcaption>The posters say "No IELTS". Eiffel names no English test, but the programme that admits you sets its own English requirement.</figcaption>
</figure>

<h2>The Eiffel Scholarship: What It Pays</h2>
<p>The France Excellence Eiffel programme was set up by the French Ministry for Europe and Foreign Affairs to help French institutions attract top international students to master's and PhD programmes. Campus France runs it. The amounts below come from the Eiffel regulations and Campus France's programme page.</p>

<div class="scholar-table"><table>
<thead><tr><th></th><th>Master's</th><th>PhD</th></tr></thead>
<tbody>
<tr><td>Monthly allowance</td><td>&euro;1,181</td><td>&euro;1,800 (since 1 January 2024)</td></tr>
<tr><td>Twelve months of allowance</td><td>&euro;14,172</td><td>&euro;21,600</td></tr>
<tr><td>Length</td><td>Up to 12 months entering M2, up to 24 months entering M1, up to 36 months for an engineering degree</td><td>12 or 18 months for a joint or dual PhD; 36 months only for new first-year PhD students</td></tr>
<tr><td>Tuition</td><td colspan="2">Not paid by Eiffel</td></tr>
</tbody>
</table></div>

<h3>What else is covered</h3>
<ul>
<li><strong>Travel:</strong> economy airfare to France and back, reimbursed on receipts up to a cap set by the Ministry, plus a second-class train or bus fare between the airport and your city. You have 12 months after the scholarship ends to claim the return ticket, and it depends on your final results.</li>
<li><strong>Health insurance:</strong> Campus France enrols you in a temporary plan until French Social Security, which is free for students, starts. Once you send proof of Social Security cover, you get supplementary insurance at no cost. Family members are not covered. Send that proof within two months, or payments can be suspended.</li>
<li><strong>Housing:</strong> Campus France offers a room in a CROUS student residence or a private home. You can decline and find your own.</li>
<li><strong>Culture:</strong> weekend outings and short trips at student prices. They are offered, not paid for.</li>
<li><strong>Visa:</strong> where applicable, no visa fee.</li>
</ul>
<p>The allowance is paid from the day you arrive in France, into a French bank account; the first payment is sent by Western Union. If you take a language course before your programme starts, the allowance covers at most one month of it, and the course fee is yours.</p>

<div class="scholar-note">
<p><strong>About tuition.</strong> The regulations say French government scholarship holders are exempt from tuition for national master's, doctorate and accredited engineering degrees at public institutions overseen by the Ministry of Higher Education. Apart from that, programme costs are not covered. At a private business school the fee is yours to pay.</p>
</div>

<h2>Who Can Get an Eiffel Scholarship</h2>
<ul>
<li><strong>Nationality:</strong> the programme is for international students. Dual nationals whose second nationality is French are not eligible. There is no list of eligible countries, so Pakistani students can be nominated.</li>
<li><strong>Age:</strong> Campus France encourages applicants up to 29 years old at master's level and up to 35 at PhD level.</li>
<li><strong>Master's:</strong> candidates already studying in France are not eligible. Programmes run by French institutions abroad and apprenticeship contracts do not count.</li>
<li><strong>PhD:</strong> the PhD must be a joint supervision or dual degree with a partner institution abroad. The 36-month award is only for new students enrolling in the first year of a PhD in France. Students studying abroad take priority over those living in France.</li>
<li><strong>Other scholarships:</strong> current holders of a French government scholarship are not eligible.</li>
<li><strong>Start date:</strong> the funded period must begin between 1 September and 30 November of the award year, and it cannot be postponed.</li>
</ul>
<p>Every file is judged on your academic record, the institution's reputation and international policy, and its cooperation with the Ministry. Some institutions add their own bar: Sorbonne Université asked for an average of at least 15/20 over the last three academic years for its 2026/2027 nominations.</p>

<h3>The seven fields of study</h3>
<div class="scholar-table"><table>
<thead><tr><th>Science and technology</th><th>Humanities and social sciences</th></tr></thead>
<tbody>
<tr><td>Biology and health</td><td>History, French language and civilisation</td></tr>
<tr><td>Ecological transition</td><td>Law and political science</td></tr>
<tr><td>Mathematics and digital sciences</td><td>Economics and management</td></tr>
<tr><td>Engineering sciences</td><td></td></tr>
</tbody>
</table></div>

<h2>Eiffel Timeline: The Closed 2026 Call and What to Expect for 2027</h2>
<div class="scholar-table"><table>
<thead><tr><th>Step</th><th>2026 call (closed)</th><th>Usual pattern</th></tr></thead>
<tbody>
<tr><td>Call opens</td><td>1 October 2025</td><td>End of September</td></tr>
<tr><td>Institution's internal deadline</td><td>Set by each institution (Sorbonne Université: 24 November 2025 for PhD, 28 November 2025 for master's)</td><td>Usually October to November</td></tr>
<tr><td>Institutions send files to Campus France</td><td>8 January 2026</td><td>First week of January</td></tr>
<tr><td>Results</td><td>From 30 March 2026</td><td>March</td></tr>
</tbody>
</table></div>
<p>Campus France had not published the 2027 call when we checked on 14 September 2026. If the pattern holds, the institutions you want should be announcing their internal deadlines in the coming weeks, so the time to contact them is now.</p>

<h2>How to Apply for Eiffel</h2>
<ol>
<li><strong>Choose a programme</strong> in one of the seven fields at a French institution that takes part in Eiffel. Check whether it is taught in English or French.</li>
<li><strong>Apply for admission.</strong> Pakistani students apply through the Campus France Etudes en France procedure.</li>
<li><strong>Ask the programme about Eiffel nomination</strong> and its internal deadline. Some institutions nominate only students they have already admitted.</li>
<li><strong>Send what the institution asks for:</strong> usually transcripts, a CV, a motivation letter, references and your passport.</li>
<li><strong>Let the institution submit your file</strong> to Campus France before the January deadline.</li>
<li><strong>Watch for results in March</strong>, then start your visa and Campus France steps.</li>
</ol>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/{slug}-students.jpg" alt="International students by the Seine with the Eiffel Tower, France scholarships without IELTS 2026" width="1200" height="628" loading="lazy">
<figcaption>"Fully funded" on the poster means Eiffel's allowance, travel and insurance. Tuition is only waived at public institutions.</figcaption>
</figure>

<h2>France Excellence Pakistan &ndash; Climate Change</h2>
<p>The French Embassy in Pakistan runs its own full scholarship for Pakistani master's students, aimed at programmes that help Pakistan adapt to climate change. It is closer to "fully funded" than Eiffel, because it includes tuition.</p>

<h3>What it covers</h3>
<ul>
<li>Tuition fees and a monthly living allowance</li>
<li>One return air ticket Pakistan&ndash;France&ndash;Pakistan</li>
<li>Student visa and Etudes en France fee waiver, reimbursed on request after the award</li>
<li>Social Security, and help finding student housing with priority access to CROUS residences</li>
<li>Free French classes before you leave</li>
</ul>

<h3>Who can apply (2026 call)</h3>
<ul>
<li>Pakistani nationals, 28 years old maximum at the time of application</li>
<li>A 4-year bachelor's degree, or in the 4th year, with a CGPA of at least 3.0/4.0 from an HEC-recognised Pakistani university</li>
<li>No more than 2 years since finishing the undergraduate degree</li>
<li>French is an asset but not required, except at ESTP, which asks for A2</li>
</ul>
<p>The 2026 call opened on 1 October 2025 and closed on 30 November 2025, with results in May 2026. Six programmes were eligible: IMT Atlantique's MSc Process and Bioprocess Engineering, INP Pagora's Biorefinery and Biomaterials master's, Avignon's MAFEN master's, ONIRIS's One-Health Emerge master's, Lille's International Economics master's and ESTP's Civil Engineering master's. The Etudes en France fee was <strong>PKR 30,000</strong>, refunded only if you win. For a two-year master's, the second year is renewed only if you pass every first-year exam without a retake.</p>
<p>Selection is based on academic excellence and your statement of purpose, and <strong>the level of English is also taken into account</strong>. The embassy says it pays special attention to gender balance and to applicants from every province.</p>

<h2>Can You Really Study in France Without IELTS?</h2>
<p>Here is what is true, and what is not:</p>
<ul>
<li><strong>Eiffel's rules name no IELTS or TOEFL score.</strong> The test, if any, is set by the programme that admits you.</li>
<li><strong>English-taught programmes usually ask for proof of English.</strong> Some accept other evidence; check the programme page before you rule out a test.</li>
<li><strong>French-taught programmes ask for French instead.</strong> ESTP's civil engineering master's, for example, asks for A2.</li>
<li><strong>The Pakistan climate-change call judges your English level</strong> as part of selection, even without a set score.</li>
</ul>

<h2>Tuition Fees for Non-EU Students in 2026-2027</h2>
<p>If your scholarship does not cover tuition, budget for it. From 1 September 2026, non-EU students at public institutions pay <strong>&euro;2,902 a year for a bachelor's</strong> and <strong>&euro;3,950 a year for a master's</strong>, against &euro;178 and &euro;255 for EU students. Doctoral enrolment is not part of these differentiated fees. Institutions may exempt up to 30% of their non-EU students in 2026-2027, falling to 25% in 2027-2028 and 20% after that. Private schools set their own fees.</p>

<h2>Other French Awards in the Brief</h2>
<h3>UNIV'R at Sciences Po (refugees only)</h3>
<p>UNHCR and the Agence Universitaire de la Francophonie support recognised refugees on a two-year master's at Sciences Po. Applicants must be recognised as refugees by their first country of asylum or by UNHCR, live in that country outside the EU, be no older than 35 and hold a bachelor's degree. Asylum seekers are not eligible. Support includes administrative, social and financial help, a visa fee exemption and a scholarship for travel and social security. The 2026 cohort closed on 1 February 2026. A Pakistani citizen cannot apply; a recognised refugee living in Pakistan may.</p>

<h3>Classes Internationales</h3>
<p>This is a pathway, not a scholarship: a year of intensive French (20 hours a week) with introductory courses, then a French-taught bachelor's. It is for students from India, Indonesia, China, Vietnam, South Africa, Nigeria and Kenya who already hold A2 French. Pakistan is not on the list.</p>

<h3>Studely grant</h3>
<p>Studely, a private student-services company, gave <strong>&euro;1,000 once</strong> to 30 students moving to France, Germany or Belgium in its 2025 edition, chosen on social criteria and the quality of their studies. Registrations closed on 30 June 2025. It will not fund a degree.</p>

<h3>Ministry social-criteria grants</h3>
<p>France's means-tested student grants (bourses sur crit&egrave;res sociaux) are for students already settled in France. A non-EU student needs a residence permit, at least two years living in France and a tax household in France for at least two years. A student arriving from Pakistan does not qualify.</p>

<h3>Other funding</h3>
<p>The Campus France Pakistan page points to the Campus Bourses search engine, which listed 134 scholarship programmes open to Pakistani applicants from the French government, universities and companies. Filter by nationality and level before you rely on any single award.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/{slug}-graduates.jpg" alt="Graduates in caps and gowns in Paris, France scholarships without IELTS 2026" width="1200" height="628" loading="lazy">
<figcaption>The "Apply Now" button on the poster does not exist for Eiffel: the French institution applies for you.</figcaption>
</figure>

<h2>Claims to Ignore</h2>
<ul>
<li><strong>"PhD stipend &euro;1,400" or "&euro;2,100".</strong> It is &euro;1,800 a month since 1 January 2024.</li>
<li><strong>"Master's &euro;1,200".</strong> It is &euro;1,181 a month.</li>
<li><strong>"Full tuition coverage" with Eiffel.</strong> Eiffel does not pay tuition; the exemption applies only to national degrees at public institutions.</li>
<li><strong>"Deadline January 8, 2026".</strong> That call has closed. Results were published from 30 March 2026, not in April.</li>
<li><strong>Age limits by "developing" and "industrialised" countries.</strong> Campus France gives 29 for master's and 35 for PhD, with no country split.</li>
<li><strong>"Total value &euro;14,000&ndash;&euro;18,000" and "&euro;25,200".</strong> Twelve months of allowance are &euro;14,172 and &euro;21,600; travel and insurance are services, not cash.</li>
<li><strong>"Apply to Campus France".</strong> Only institutions can submit Eiffel files.</li>
<li><strong>Sciences Po as an Eiffel host for 2027.</strong> It is not taking part.</li>
</ul>

<h2>France, Italy or Australia?</h2>
<p>Eiffel gives you a monthly allowance but leaves tuition to the institution, so it works best at a public university where the fee is waived. Italy asks you to fund yourself, then cuts tuition and adds a regional grant if your family income is low; see our <a href="/scholarships/{pavia}">University of Pavia</a>, <a href="/scholarships/{insubria}">University of Insubria</a> and <a href="/scholarships/{marche}">Polytechnic University of Marche</a> guides. Australia pays research students a full stipend but asks almost everyone for an English test; our <a href="/scholarships/{australia}">Australia scholarships without IELTS guide</a> sets out which tests count there.</p>

<h2>Frequently Asked Questions</h2>
<h3>Can I get a scholarship in France without IELTS?</h3>
<p>Yes, in the sense that Eiffel and the French government calls do not name IELTS. The programme that admits you still sets its own English requirement, and the Pakistan climate-change call takes your English level into account.</p>

<h3>How much does the Eiffel scholarship pay?</h3>
<p>&euro;1,181 a month for master's students and &euro;1,800 a month for PhD students, plus travel, health insurance, housing help and cultural activities at student prices.</p>

<h3>Does the Eiffel scholarship cover tuition fees?</h3>
<p>No. French government scholarship holders are exempt from tuition for national degrees at public institutions, but Eiffel itself does not pay fees, and private schools charge their own.</p>

<h3>Can I apply for the Eiffel scholarship directly?</h3>
<p>No. Only French higher education institutions can submit applications to Campus France. Apply for admission first, then ask the programme to nominate you.</p>

<h3>When is the Eiffel deadline for 2027?</h3>
<p>Not yet published. The call usually opens at the end of September, and institutions send files in the first week of January, but their internal deadlines come earlier.</p>

<h3>What is the age limit for the Eiffel scholarship?</h3>
<p>Campus France encourages applicants up to 29 years old at master's level and up to 35 at PhD level.</p>

<h3>Is there a French scholarship only for Pakistani students?</h3>
<p>Yes. France Excellence Pakistan &ndash; Climate Change covers tuition, an allowance and a return ticket for Pakistani master's students aged 28 or under with a CGPA of 3.0. The 2026 call closed on 30 November 2025.</p>

<h3>Is there a bachelor's scholarship in France for Pakistani students?</h3>
<p>Not through Eiffel, which is for master's and PhD only. Classes Internationales does not include Pakistan and is not a scholarship.</p>

<h2>Official Links and Contacts</h2>
<ul>
<li><a href="{apply}" target="_blank" rel="noopener">Campus France: France Excellence Eiffel scholarship program</a></li>
<li><a href="https://www.pakistan.campusfrance.org/eiffel-scholarship-program-of-excellence" target="_blank" rel="noopener">Campus France Pakistan: Eiffel page</a></li>
<li><a href="https://www.pakistan.campusfrance.org/2026-call-france-excellence-pakistan-climate-change" target="_blank" rel="noopener">Campus France Pakistan: France Excellence Pakistan &ndash; Climate Change</a></li>
<li><a href="https://www.pakistan.campusfrance.org/system/files/medias/documents/2025-04/REGULATIONS_EIFFEL_PROGRAM_2025_EN.pdf" target="_blank" rel="noopener">Eiffel scholarship regulations (PDF)</a></li>
<li><a href="https://www.service-public.gouv.fr/particuliers/actualites/A18927?lang=en" target="_blank" rel="noopener">Service-Public: 2026-2027 fees for non-EU students</a></li>
<li><a href="https://classesinternationales.org/" target="_blank" rel="noopener">Classes Internationales</a></li>
</ul>
<p>The Eiffel programme office is Campus France, Programme France Excellence Eiffel, 28 rue de la Grange-aux-Belles, 75010 Paris. Its address candidatures.eiffel@campusfrance.org is for institutions; students should ask their programme or Campus France Pakistan.</p>

<p><em>JobGader is not part of the French government, Campus France or any institution. This guide was checked against Campus France, the Eiffel regulations and the official pages linked above on 14 September 2026. Amounts, calls and deadlines change, so confirm them on the official page before you apply.</em></p>
HTML, [
            '{slug}' => self::SLUG,
            '{apply}' => self::APPLY_URL,
            '{australia}' => AustraliaScholarshipsWithoutIeltsScholarshipSeeder::SLUG,
            '{pavia}' => PaviaScholarshipSeeder::SLUG,
            '{insubria}' => InsubriaScholarshipSeeder::SLUG,
            '{marche}' => PolytechnicMarcheScholarshipSeeder::SLUG,
        ]);
    }
}
