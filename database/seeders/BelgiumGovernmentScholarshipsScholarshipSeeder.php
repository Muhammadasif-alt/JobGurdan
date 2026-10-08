<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;

/**
 * "Belgium Government Scholarships 2026 - Fully Funded".
 *
 * There is no single "Belgium Government Scholarship". Belgium's development
 * scholarships for study are run by two regions' university bodies: ARES for
 * the Wallonia-Brussels Federation and VLIR-UOS for Flanders. Checked on 8
 * October 2026 against the ARES scholarships page (ares-ac.be) and the VLIR-UOS
 * study scholarships page (vliruos.be). The brief's link was unicafscholarship
 * .com, a third-party site. Corrections to the brief:
 *
 * 1. "Bachelor's, Master's & PhD". ARES's 2027-28 call offers one specialised
 *    Bachelor's, eight specialised Master's programmes of up to 12 months and
 *    one six-month training course. There is no PhD route in either call.
 *
 * 2. "Open to international students". Both schemes are limited to listed
 *    developing and partner countries. Pakistan is not among VLIR-UOS's 29
 *    eligible countries and is not on the 2027-28 ARES list.
 *
 * 3. "Apply 2026". ARES's 2027-28 call closed on 18 September 2026 and the next
 *    opens in August 2027. VLIR-UOS expects its 2027-28 round to open from
 *    mid-November 2026, with each programme setting its own deadline.
 *
 * 4. "Accommodation Support" is only partly true: VLIR-UOS covers board and
 *    lodging inside its living costs, and ARES pays a subsistence allowance;
 *    neither is a separate housing grant.
 *
 * 5. The brief's apply link is replaced with the official ARES and VLIR-UOS
 *    pages. The posters say "Open to international students"; the captions say
 *    who can actually apply.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class BelgiumGovernmentScholarshipsScholarshipSeeder extends Seeder
{
    public const SLUG = 'belgium-government-scholarships-2026';

    public const APPLY_URL = 'https://www.ares-ac.be/en/scholarships';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Belgium Government Scholarships 2026: ARES and VLIR-UOS, and Who Can Apply',
                'provider' => 'ARES (Wallonia-Brussels) and VLIR-UOS (Flanders)',
                'country' => 'Belgium',
                'city' => 'Various Belgian universities',
                'study_level' => "Bachelor's, Master's, short courses",
                'funding_type' => 'Fully funded, listed countries only',
                'award_value' => 'Tuition, travel, living allowance and insurance',
                'deadline' => null,
                'deadline_note' => 'ARES 2027-28 closed 18 Sep 2026; VLIR-UOS 2027-28 expected from mid-Nov 2026',
                'excerpt' => 'Belgium has no single government scholarship. ARES and VLIR-UOS fund study for listed developing countries, and Pakistan is on neither list. ARES closed on 18 September 2026; VLIR-UOS opens from mid-November. Here is who can apply and what to do instead.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Belgium Government Scholarships 2026: ARES, VLIR-UOS',
                'meta_description' => 'Belgium government scholarships: how ARES and VLIR-UOS work, eligible countries, 2027-28 deadlines and what Pakistani students can do instead.',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>There is no single "Belgium Government Scholarship". What exists are two regional schemes: <strong>ARES</strong> for the French-speaking Wallonia-Brussels Federation and <strong>VLIR-UOS</strong> for Flanders. Both fund study in Belgium for people from a <strong>fixed list of developing and partner countries</strong>. <strong>Pakistan is not on either list</strong>, so a Pakistani applicant cannot use these two routes, whatever a poster says. The posters, and the third-party page that the brief links to, imply a scholarship open to every international student, which is not true.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you plan around the posters:</strong></p>
<ul>
<li><strong>"Open to international students" is wrong.</strong> Both schemes are limited to listed countries. VLIR-UOS names 29 eligible countries in Africa, Asia and Latin America, and Pakistan is not among them.</li>
<li><strong>ARES has closed.</strong> The 2027-28 call closed on 18 September 2026, and the next call opens in August 2027.</li>
<li><strong>There is no PhD route.</strong> The ARES 2027-28 call offers one specialised Bachelor's, eight specialised Master's programmes and one short training course.</li>
<li><strong>No separate housing grant.</strong> Living costs, which include board and lodging, are paid as an allowance.</li>
<li><strong>Apply on the official pages.</strong> The links at the end go to ARES and VLIR-UOS themselves.</li>
</ul>
</div>

<h2>Belgium's Two Scholarship Schemes at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Item</th><th>ARES (Wallonia-Brussels)</th><th>VLIR-UOS (Flanders)</th></tr></thead>
<tbody>
<tr><td>Who it is for</td><td>Residents of partner countries with a higher-education degree and professional experience</td><td>Nationals of 29 eligible countries; preference for people working in higher education, government or civil society</td></tr>
<tr><td>Study offered</td><td>One specialised Bachelor's, eight specialised Master's (up to 12 months) and one 6-month course in 2027-28</td><td>Bachelor, initial Master and advanced Master programmes</td></tr>
<tr><td>What it covers</td><td>International travel, a living allowance, tuition fees, insurance and visa costs</td><td>Tuition fees, travel, insurance and living expenses (board and lodging) for the whole programme</td></tr>
<tr><td>2027-28 round</td><td>Closed 18 September 2026; next call opens August 2027</td><td>Expected to open from mid-November 2026; each programme sets its deadline</td></tr>
<tr><td>Is Pakistan eligible?</td><td>Not on the 2027-28 list</td><td>No; not among the 29 countries</td></tr>
</tbody>
</table></div>

<h2>Who Can Apply for ARES?</h2>
<p>ARES's international training scholarships are for people who permanently live in one of its partner countries, hold a higher-education degree and have professional experience. For 2027-28 the call listed 32 partner countries across Africa, Latin America, the Caribbean and Asia, and applicants could choose only one programme. Applications were online and closed on 18 September 2026, so the earliest you can apply for the next round is the call that opens in August 2027.</p>

<h2>Who Can Apply for VLIR-UOS?</h2>
<p>VLIR-UOS scholarships are for nationals of 29 eligible countries in Africa, Asia and Latin America. The age limit is 35 for Bachelor and initial Master applicants and 45 for advanced Master applicants. The scholarship covers the whole programme or nothing, so partial awards do not exist. You apply once a year, through the institution's website, for admission and the scholarship together, and each programme has its own deadline. Results are communicated by mid-May.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/belgium-government-scholarships-2026-student.jpg" alt="Belgium Government Scholarships 2026 poster with a smiling student holding books in front of a Belgian town square and the Belgian flag" width="1200" height="628" loading="lazy">
<figcaption>The posters say "Open to International Students". In fact the Belgian development scholarships are limited to listed countries, and Pakistan is not one of them.</figcaption>
</figure>

<h2>What Can Pakistani Students Do Instead?</h2>
<p>Pakistan is not on the ARES or VLIR-UOS lists, but it is not shut out of study in Belgium or in Europe. The routes to look at are:</p>
<ul>
<li><strong>Erasmus Mundus Joint Master's.</strong> These EU-funded programmes are open to students from any country and are run by consortia that often include Belgian universities. Each programme sets its own deadline.</li>
<li><strong>University scholarships.</strong> Belgian universities run their own awards, with their own criteria and deadlines, so check the scholarship page of the university and the programme you want.</li>
<li><strong>Other funded routes.</strong> The scholarships linked below are open to Pakistani applicants and fund study in other European countries and the UK.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Is there a Belgium government scholarship for Pakistani students?</h3>
<p>Not through ARES or VLIR-UOS, the two main schemes, because Pakistan is not on their eligible-country lists. Look at Erasmus Mundus programmes and Belgian universities' own scholarships.</p>

<h3>Is the Belgium government scholarship fully funded?</h3>
<p>For eligible countries, yes. ARES and VLIR-UOS cover tuition, travel, a living allowance and insurance, but they fund study only for the whole programme and only for listed countries.</p>

<h3>When is the ARES deadline?</h3>
<p>The 2027-28 call closed on 18 September 2026. The next call opens in August 2027.</p>

<h3>When does VLIR-UOS open?</h3>
<p>VLIR-UOS expects its 2027-28 round to open from mid-November 2026. Each programme sets its own deadline.</p>

<h3>Can I do a PhD with these scholarships?</h3>
<p>No. The 2027-28 ARES call offers a Bachelor's, Master's programmes and one short course, and VLIR-UOS's study scholarships cover Bachelor and Master programmes.</p>

<h3>Is accommodation paid?</h3>
<p>Not as a separate grant. VLIR-UOS includes board and lodging in its living expenses, and ARES pays a subsistence allowance.</p>

<h3>Do I need IELTS?</h3>
<p>The programme sets its own language requirement, so check the programme page. ARES asks for proficiency in the language the programme is taught in.</p>

<h3>Is the unicafscholarship page the official application?</h3>
<p>No. It is a third-party page. Apply only through the ARES and VLIR-UOS pages listed below.</p>

<h2>Related Scholarship Guides</h2>
<ul>
<li><a href="/scholarships/{france}">France scholarships without IELTS</a> &mdash; what Eiffel pays and who applies for you.</li>
<li><a href="/scholarships/{portugal}">Portugal scholarships</a> &mdash; funded study in another European country.</li>
<li><a href="/scholarships/{commonwealth}">Commonwealth Scholarship UK</a> &mdash; a fully funded UK Master's or PhD that Pakistani applicants can apply for through HEC.</li>
</ul>

<h2>Official Links</h2>
<ul>
<li><a href="{apply}" target="_blank" rel="noopener">ARES: international training scholarships</a></li>
<li><a href="https://www.vliruos.be/en/scholarships/" target="_blank" rel="noopener">VLIR-UOS: study scholarships</a></li>
</ul>

<p><em>JobGader is not part of ARES, VLIR-UOS or any Belgian university. This guide was checked against the ARES and VLIR-UOS pages on 8 October 2026. Eligible countries, deadlines and programmes change each round, so confirm them on the official pages before you apply.</em></p>
HTML, [
            '{apply}' => self::APPLY_URL,
            '{france}' => FranceScholarshipsWithoutIeltsScholarshipSeeder::SLUG,
            '{portugal}' => PortugalScholarshipsSeeder::SLUG,
            '{commonwealth}' => CommonwealthScholarshipUkScholarshipSeeder::SLUG,
        ]);
    }
}
