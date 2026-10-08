<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "Commonwealth Scholarship UK 2026 (Fully Funded)" - the Commonwealth
 * Scholarship Commission's Master's and PhD awards, written for Pakistani
 * applicants.
 *
 * The brief's link was jpascholarships.com, a third-party site. Checked on 8
 * October 2026 against the CSC pages (cscuk.fcdo.gov.uk: apply, Master's, PhD,
 * Shared and Fellowship pages) and the HEC Pakistan call for 2027/28.
 * Corrections to the brief:
 *
 * 1. "Apply Now" went to the third-party site. The guide links to the CSC and
 *    HEC pages.
 *
 * 2. "Commonwealth Scholarship UK 2026" is the wrong year. The open round is
 *    for study from September 2027, and closes at 16:00 BST on Tuesday 20
 *    October 2026.
 *
 * 3. "Accommodation Support" is not a CSC benefit. The monthly stipend covers
 *    living costs, including rent, and there is no separate housing grant.
 *    One poster that shows it is not used.
 *
 * 4. The brief implies you apply to the CSC. For Master's and PhD awards the
 *    CSC takes no direct applications: you apply through a nominating agency,
 *    which for Pakistan is the HEC, and HEC adds its own rules (a HAT score of
 *    at least 60 out of 100, a first division, and a second application on its
 *    own portal).
 *
 * 5. "Fellowships" are not an open application. Professional and Academic
 *    Fellowships are bid for by UK host organisations, and the 2026 bidding
 *    round closed on 24 September 2026.
 *
 * 6. Shared Scholarships, which UK universities select candidates for, are
 *    closed for 2026/27, and the guide says so.
 *
 * The posters say "Commonwealth Scholarship UK 2026" and "Fully Funded"; the
 * caption explains the entry year.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class CommonwealthScholarshipUkScholarshipSeeder extends Seeder
{
    public const SLUG = 'commonwealth-scholarship-uk-fully-funded';

    public const APPLY_URL = 'https://cscuk.fcdo.gov.uk/apply/';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Commonwealth Scholarship UK 2026 (Fully Funded): Master\'s and PhD for 2027/28 Entry',
                'provider' => 'Commonwealth Scholarship Commission (UK FCDO)',
                'country' => 'United Kingdom',
                'city' => 'Various UK universities',
                'study_level' => "Master's, PhD",
                'funding_type' => 'Fully Funded',
                'award_value' => 'Tuition, monthly stipend, return airfare + allowances',
                'deadline' => Carbon::parse('2026-10-20'),
                'deadline_note' => 'Closes 16:00 BST on 20 Oct 2026; HEC Pakistan and other nominators may set earlier dates',
                'excerpt' => 'The Commonwealth Scholarship Commission pays tuition, a monthly stipend and return airfare for a UK Master\'s or PhD from September 2027. You apply through a nominating agency, in Pakistan the HEC, by 20 October 2026. HEC requires a HAT score of 60.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Commonwealth Scholarship UK: Fully Funded, Apply by 20 Oct',
                'meta_description' => 'Commonwealth Scholarship UK for 2027/28: Master\'s and PhD funding, stipend, eligibility, HEC Pakistan rules and the 20 October 2026 deadline.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-10-08 08:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>The Commonwealth Scholarship UK is real and fully funded, but it is not the "apply now on any website" award that the posters suggest. <strong>The Commonwealth Scholarship Commission (CSC) pays tuition fees, a monthly stipend and an approved return airfare for a full-time Master's or PhD at a UK university, and it does not take direct applications for those awards</strong>. You apply through a nominating agency in your country. For Pakistan that is the Higher Education Commission (HEC), and the round open now closes on <strong>Tuesday 20 October 2026</strong> for study from September 2027.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you apply or share the posters:</strong></p>
<ul>
<li><strong>The year on the posters is wrong.</strong> The round open now is for the 2027/28 academic year, with study starting in September or October 2027.</li>
<li><strong>The deadline is days away.</strong> The CSC closes at 16:00 BST on 20 October 2026, and your nominating agency can set an earlier date.</li>
<li><strong>There is no "accommodation support".</strong> The monthly stipend is meant to cover living costs, including rent. One poster shows an accommodation grant that the CSC does not offer.</li>
<li><strong>You cannot apply to the CSC alone.</strong> For Master's and PhD awards you go through a nominator, and HEC Pakistan has its own extra rules.</li>
<li><strong>Use official pages.</strong> The "apply" links on third-party scholarship sites are not the application. The CSC and HEC pages are listed at the end.</li>
</ul>
</div>

<h2>Commonwealth Scholarship UK at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Item</th><th>What to know</th></tr></thead>
<tbody>
<tr><td>Who pays</td><td>The UK government's Foreign, Commonwealth and Development Office, through the Commonwealth Scholarship Commission</td></tr>
<tr><td>Awards</td><td>Master's and PhD scholarships; the CSC says it offers around 800 awards a year across all schemes</td></tr>
<tr><td>Study starts</td><td>September or October 2027</td></tr>
<tr><td>Deadline (CSC)</td><td>16:00 BST, Tuesday 20 October 2026</td></tr>
<tr><td>How to apply</td><td>Through a nominating agency, using the online CSC portal; no direct applications</td></tr>
<tr><td>Pakistan nominator</td><td>Higher Education Commission (HEC)</td></tr>
<tr><td>Stipend</td><td>&pound;1,712 a month, or &pound;2,000 in the London area (as listed on the CSC pages)</td></tr>
</tbody>
</table></div>

<h2>What Does the Scholarship Cover?</h2>
<ul>
<li><strong>Tuition fees</strong>, paid to the university.</li>
<li><strong>A monthly stipend</strong> of &pound;1,712, or &pound;2,000 in the London area, according to the CSC's Master's and PhD pages.</li>
<li><strong>An approved return airfare</strong> from your home country to the UK.</li>
<li><strong>Study travel grants</strong> for travel connected to your course.</li>
<li><strong>Fieldwork provisions</strong> for PhD scholars where the research needs them.</li>
<li><strong>Family allowances</strong> for eligible scholars: a child allowance for Master's scholars with dependants, and spouse and child allowances for PhD scholars under set conditions.</li>
</ul>

<p>The brief also lists "additional allowances" and "accommodation support". The extras the CSC names are the travel, fieldwork and family allowances above. There is no separate housing grant, so plan your rent from the stipend.</p>

<figure class="scholar-figure">
<img src="/public/storage/scholarships/commonwealth-scholarship-uk-fully-funded-campus.jpg" alt="Commonwealth Scholarships poster with a smiling student holding books in front of Big Ben, the Union Jack and a plane taking off" width="1200" height="628" loading="lazy">
<figcaption>The posters say "2026". The Commonwealth round open now is for study from September 2027, and closes on 20 October 2026.</figcaption>
</figure>

<h2>Which Awards Can You Apply For?</h2>
<div class="scholar-table"><table>
<thead><tr><th>Award</th><th>Who it is for</th><th>How you apply</th><th>Status in October 2026</th></tr></thead>
<tbody>
<tr><td>Commonwealth Master's Scholarships</td><td>Graduates from eligible Commonwealth countries, Pakistan included</td><td>Through a nominator (HEC in Pakistan), then CSC Central</td><td>Open; closes 20 October 2026</td></tr>
<tr><td>Commonwealth PhD Scholarships</td><td>Applicants from the 17 eligible Commonwealth countries, Pakistan included</td><td>Through a nominator, with a UK supervisor's supporting statement</td><td>Open; closes 20 October 2026</td></tr>
<tr><td>Commonwealth Shared Scholarships</td><td>Candidates chosen by UK universities for specific courses</td><td>Through CSC Central and the university</td><td>Closed for 2026/27</td></tr>
<tr><td>Commonwealth Fellowships</td><td>Mid-career professionals and academics hosted by UK organisations</td><td>The UK host organisation bids, not you</td><td>Host bids closed on 24 September 2026</td></tr>
</tbody>
</table></div>

<h2>Who Is Eligible?</h2>
<p>For the Master's and PhD awards you must:</p>
<ul>
<li>Be a citizen of, or have refugee status in, an eligible Commonwealth country, and be permanently resident there.</li>
<li>Hold a first degree of at least upper second-class (2:1) honours standard, or the equivalent. For the PhD, a lower second-class degree is acceptable if you also have a Master's.</li>
<li>Be unable to afford to study in the UK without this scholarship.</li>
<li>Be available to start in the UK in September 2027.</li>
<li>For the PhD, not have been registered for a PhD or MPhil before September 2026, and have a supporting statement from a proposed UK supervisor.</li>
</ul>

<p>Your application also has to include a development impact statement explaining how your studies will benefit your home country.</p>

<h2>What Does HEC Pakistan Require?</h2>
<p>The CSC sets the scholarship rules, but Pakistani applicants are nominated by the HEC, and the HEC adds conditions of its own. For the 2027/28 call:</p>
<ul>
<li>You must be a Pakistani or Azad Jammu and Kashmir national and permanent resident. Dual nationals are not eligible, and there is no upper age limit.</li>
<li>For a Master's, a first division in a 16-year bachelor's degree. For a PhD, a first division in a 17 or 18-year Master's, MS or MPhil.</li>
<li>A <strong>HAT score of at least 60 out of 100</strong>, from HAT categories I to IV only. National university admission tests do not count.</li>
<li>Applications on <strong>both portals</strong>: the CSC's online portal and the HEC portal at scholarship.hec.gov.pk. An incomplete or unsubmitted application is not accepted.</li>
</ul>

<p>HEC nominates a limited number of candidates, and the call lists 26 Master's and 30 PhD nominations. A nomination does not guarantee an award: the CSC makes the final selection. HEC's page quotes the deadline in its own local time, while the CSC quotes 16:00 BST, so aim to submit well before 20 October.</p>

<h2>How Do You Apply?</h2>
<ol>
<li>Check that you are eligible and, if you are in Pakistan, that you hold a valid HAT score of 60 or more.</li>
<li>Decide which UK courses you want and read their entry requirements, because your application has to name your choices.</li>
<li>Register on the CSC's online portal and complete the application: transcripts, two references, your study plan or research proposal and the development impact statement.</li>
<li>For a PhD, get the supporting statement from a UK supervisor.</li>
<li>Apply on the HEC portal as well, and submit both before the deadline.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Is the Commonwealth Scholarship UK fully funded?</h3>
<p>Yes. It pays tuition fees, a monthly stipend and an approved return airfare, with extra travel, fieldwork and family allowances for those who qualify.</p>

<h3>What is the deadline?</h3>
<p>The CSC closes at 16:00 BST on Tuesday 20 October 2026 for Master's and PhD study from September 2027. Your nominating agency may set an earlier date.</p>

<h3>Can I apply directly to the Commonwealth Scholarship Commission?</h3>
<p>Not for Master's and PhD awards. The CSC takes applications only through nominating agencies, using its online portal.</p>

<h3>Is Pakistan eligible?</h3>
<p>Yes, for both the Master's and PhD awards, and the HEC is the nominator. HEC requires a first division and a HAT score of at least 60.</p>

<h3>Does the scholarship cover accommodation?</h3>
<p>There is no separate accommodation grant. The monthly stipend is meant to cover living costs, including rent.</p>

<h3>How much is the monthly stipend?</h3>
<p>The CSC lists &pound;1,712 a month, or &pound;2,000 in the London area, for the Master's and PhD awards.</p>

<h3>Do I need IELTS?</h3>
<p>The UK university sets the English requirement for your course, so check the course page and the CSC's guidance.</p>

<h3>Is this the same as the Commonwealth Shared Scholarship?</h3>
<p>No. Shared Scholarships are bid for by UK universities for specific courses, and applications for 2026/27 are closed.</p>

<h2>Related Scholarship Guides</h2>
<ul>
<li><a href="/scholarships/{leeds}">University of Leeds Commonwealth Master's Scholarship</a> &mdash; one UK university's route to a Commonwealth Master's award.</li>
<li><a href="/scholarships/{kcl}">King's College London Chevening Scholarship</a> &mdash; the UK government's other fully funded Master's award.</li>
</ul>

<h2>Official Links</h2>
<ul>
<li><a href="{apply}" target="_blank" rel="noopener">Commonwealth Scholarship Commission: how to apply</a></li>
<li><a href="https://cscuk.fcdo.gov.uk/scholarships/commonwealth-masters-scholarships/" target="_blank" rel="noopener">CSC: Commonwealth Master's Scholarships</a></li>
<li><a href="https://cscuk.fcdo.gov.uk/scholarships/commonwealth-phd-scholarships/" target="_blank" rel="noopener">CSC: Commonwealth PhD Scholarships</a></li>
<li><a href="https://www.hec.gov.pk/english/HECAnnouncements/Pages/CW-Scholarship.aspx" target="_blank" rel="noopener">HEC Pakistan: Commonwealth Scholarships 2027-28</a></li>
</ul>

<p><em>JobGader is not part of the Commonwealth Scholarship Commission or the HEC. This guide was checked against the CSC pages and the HEC call on 8 October 2026. Deadlines, stipend rates and eligible countries change each round, so confirm them on the official pages before you apply.</em></p>
HTML, [
            '{apply}' => self::APPLY_URL,
            '{leeds}' => UniversityOfLeedsCommonwealthMastersScholarshipSeeder::SLUG,
            '{kcl}' => KingsCollegeLondonCheveningScholarshipSeeder::SLUG,
        ]);
    }
}
