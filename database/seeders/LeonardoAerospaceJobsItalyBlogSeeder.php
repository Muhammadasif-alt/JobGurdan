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
use Illuminate\Support\Str;

/**
 * "How to Apply for Leonardo Aerospace Jobs in Italy" — the rare guide where
 * the employer publishes real salary bands and the real barrier turns out to
 * be Italian, not the security clearance everyone assumes.
 *
 * Corrections to the draft (checked against leonardo.com, Leonardo's Workday
 * careers API, its Integrated Report 2025, normattiva.it and the Italian
 * Embassy in Islamabad, 22 September 2026):
 *
 * 1. "Look Up" is not a compensation tool. It is a video in the careers page
 *    gallery, carrying the alt text "look up video image". The draft invented
 *    a product out of a carousel label, so it is removed.
 *
 * 2. The draft recommends Leonardo's thesis programme without the one fact
 *    that matters: the Deep Dive page states it is "aimed only at Italian
 *    students". Recommending it to Pakistani readers would waste their time.
 *
 * 3. The draft never mentions language. 85 per cent of Leonardo's Italian
 *    adverts are written in Italian and a few demand native Italian. That,
 *    not clearance, is what stops most international applicants.
 *
 * 4. The draft implies clearance is pervasive. It appears in roughly 2 per
 *    cent of Italian adverts, and then as willingness to obtain one. No
 *    Italian advert scanned carried a citizenship requirement.
 *
 * 5. "1,360 training pathways" are partnerships with the education system,
 *    not internal staff courses; staff training is a separate figure.
 *
 * 6. The draft's visa section omits the two things a Pakistani engineer most
 *    needs: that the Blue Card sits outside the Decreto Flussi quota, and
 *    that work nulla osta for Pakistani nationals were suspended under
 *    Decree-Law 145/2024 and were still being unwound in September 2026.
 *
 * 7. Leonardo S.p.A. publishes no recruitment-fraud warning. The one the
 *    draft paraphrases belongs to its US subsidiaries and is attributed here.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class LeonardoAerospaceJobsItalyBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://leonardocompany.wd3.myworkdayjobs.com/LeonardoCareerSite';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Apply for Leonardo Aerospace Jobs in Italy';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Leonardo publishes real salary bands on its own adverts, which almost no aerospace employer does. But the barrier is not security clearance, it is Italian. Here is the honest version.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-leonardo-aerospace-jobs-in-italy.jpg',
                'tags' => 'leonardo jobs, leonardo careers, aerospace jobs italy, leonardo spa recruitment, italy eu blue card, decreto flussi, italy work visa, engineering jobs italy',
                'meta_title' => 'Leonardo Aerospace Jobs in Italy: How to Apply',
                'meta_description' => 'Leonardo aerospace jobs in Italy: the real careers portal, the salary bands it publishes, the Italian-language barrier, and which visa route works.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Leonardo S.p.A., Italy'],
            ['type' => 'Company', 'display_reference' => 'leonardo-spa-italy']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Italy'],
            ['area' => 'Nationwide', 'country' => 'Italy']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Aerospace Engineering, Leonardo S.p.A., Italian Sites',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under the applicable Italian national collective agreement',
                'language' => 'Italian and English',
                // Leonardo does publish a Total Base Pay Range on many of its
                // individual adverts, but this listing aggregates hundreds of
                // roles from junior to principal across a dozen sites. A single
                // junior band would misrepresent the rest, so the figures stay
                // in the guide where the context fits.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aerospace, avionics, software and systems engineering roles at Leonardo sites across Italy. Most adverts are written in Italian.',
                'seo_keywords' => 'leonardo jobs, leonardo careers italy, aerospace jobs italy, avionics engineer jobs, engineering jobs italy',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the engineering roles Leonardo S.p.A. advertises across its Italian sites, not a single vacancy and not a job advertised by JobGader. Applications are made on Leonardo's own Workday careers portal.</p>

<h3>What is actually open</h3>
<p>Leonardo's portal carried 731 vacancies group-wide when last checked, with more than 300 located in Italy. Real current titles include Avionic Systems Engineer, Embedded Software Engineer, Radar System Engineer, Satellite Communication Engineer, Hardware Engineer and ATM System and Integration Engineer.</p>

<h3>Language</h3>
<p>The large majority of Leonardo's Italian adverts are written in Italian, with requirement sections in Italian, and a small number ask for native Italian. Read the advert in its original language before applying; this is the single biggest filter on international applicants.</p>

<h3>Pay</h3>
<p>Leonardo publishes a Total Base Pay Range on many individual adverts, which is unusual and genuinely useful. One live example: ELI - Avionic Systems Engineer at Cascina Costa, permanent, "Total Base Pay Range: 32.731,92 - 43.000". Read the band on the specific advert rather than any estimate site.</p>

<h3>Security clearance and nationality</h3>
<p>No Leonardo Italian advert scanned carried a citizenship requirement, and clearance appears in only a small minority, phrased as willingness or eligibility to obtain one. Roles on classified programmes require a Nulla Osta di Sicurezza issued by the Italian state, and Leonardo does not publish which roles those are.</p>

<h3>Visas</h3>
<p>Leonardo publishes nothing about visa sponsorship or relocation. For a qualified engineer the EU Blue Card route sits outside the Decreto Flussi quota, so it does not depend on the annual click day.</p>

<p>Pay, eligibility, language requirements and immigration rules are set by Leonardo, the Italian Ministry of the Interior and Italian law &mdash; not by JobGader. Confirm the requirements on the live posting before acting.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Start with the good news, because there genuinely is some, and it is rarer than you would think.</p>

<p><strong>Leonardo publishes what it pays.</strong> Not a vague band on a corporate page &mdash; an actual salary range printed on individual job adverts. Here is one, copied from a live posting:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">ELI - Avionic Systems Engineer<br>Primary Location: IT - Varese - Cascina Costa<br>Contract Type: Permanent<br><strong>Total Base Pay Range: 32.731,92 - 43.000</strong><br>Hybrid Working: Hybrid</p>

<p>Most aerospace employers publish nothing and leave you at the mercy of estimate sites. Leonardo hands you the number. That alone makes its adverts worth reading properly.</p>

<p>Now the part the other guides get wrong.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-leonardo-aerospace-jobs-in-italy-hangar.jpg" alt="Aircraft maintenance and assembly work inside an aerospace hangar" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Leonardo's portal carried 731 vacancies group-wide when we checked, with more than 300 of them in Italy.</figcaption>
</figure>

<h2>Where Do You Actually Apply?</h2>

<p>Leonardo's careers pages on leonardo.com are brochures. The vacancies live on a Workday portal:</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://leonardocompany.wd3.myworkdayjobs.com/LeonardoCareerSite" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Open the Leonardo Careers Portal &rarr;</a>
</p>

<p>Use the Location filter once you are there rather than trying to build a country URL by hand; the portal does not respond to a country parameter. There is also an "Introduce Yourself" option for a speculative application.</p>

<p>Real Italian engineering titles live on it right now, so you can see what the work looks like:</p>

<ul>
    <li>ELI - Avionic Systems Engineer &mdash; Varese, Cascina Costa</li>
    <li>Embedded Software Engineer &mdash; Chieti Scalo</li>
    <li>Radar System Engineer &mdash; Milano, Nerviano</li>
    <li>SPZ - Satellite Communication Engineer &mdash; Roma</li>
    <li>Mod Retrofit Engineer &mdash; Cameri</li>
    <li>Telespazio - System Engineer &mdash; Fucino</li>
</ul>

<h2>The Real Barrier Is Italian, Not Security Clearance</h2>

<p>Almost every article about Leonardo warns you about security clearance. We went and read the adverts &mdash; hundreds of them, in bulk &mdash; and that framing is the wrong way round.</p>

<p><strong>No Leonardo Italian advert we scanned carried a citizenship or nationality requirement.</strong> Security clearance appeared in roughly two per cent, and where it did, it was phrased as availability or eligibility to obtain one, not as something you must already hold. A typical engineering advert's full requirements read like this:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Bachelor's or Master's degree in Aeronautical, Electrical, Electronic, or Automation Engineering / Good command of English (written and spoken) / Strong problem-solving skills and analytical mindset / Ability to work effectively both independently and as part of a team"</p>

<p>No clearance. No citizenship. No visa clause.</p>

<p><strong>What will actually stop you is the language.</strong> The overwhelming majority of Leonardo's Italian adverts are written entirely in Italian &mdash; job title in English, everything that matters in Italian: <em>Titolo di studio</em>, <em>Competenze comportamentali</em>, <em>Conoscenze linguistiche</em>. A handful go further and require <strong>"Italiano madrelingua"</strong>, native Italian.</p>

<p>If you cannot read the requirements section, you cannot meet it. That is the filter, and it is the one nobody writes about.</p>

<h2>So Is Clearance Ever an Issue?</h2>

<p>Yes, and we are not going to pretend otherwise, because Leonardo is an aerospace, defence and security company and roughly 30 per cent of it is held by Italy's Ministry of Economy and Finance.</p>

<p>Where clearance appears, the wording is specific. One cyber role asks you to "possess the requirements to obtain personal security clearance up to EU/ESA/NATO SECRET". Another says eligibility for the <strong>Nulla Osta di Sicurezza</strong> "is appreciated" &mdash; preferred, not required. A supply-chain security role on the GCAP programme asks for eligibility for DV, Developed Vetting.</p>

<p>The honest position is this. A Nulla Osta di Sicurezza is issued by the Italian state, and in practice is not something a non-EU national obtains. Leonardo does not publish which roles need one. Its export work is also governed by Italian Law 185/1990 and by US ITAR and EAR rules, which Leonardo's own Integrated Report names as binding constraints.</p>

<p>So: apply freely, because Leonardo publishes no bar. But understand that a classified programme may close later in the process for reasons nobody puts in the advert.</p>

<h2>What About the Graduate and Thesis Programmes?</h2>

<p>This is where we have to correct something that could cost you months.</p>

<p><strong>Leonardo's Deep Dive thesis programme is closed to you unless you are Italian.</strong> Leonardo's own students and graduates page says the programme is "aimed only at Italian students". It offers up to six months in a Leonardo site with an expense reimbursement of 800 euros a month &mdash; and it is not open to international applicants.</p>

<p>Two other things worth knowing before you go looking: <strong>Officina Leonardo and the Leonardo Hackathons are all marked COMPLETED</strong> on the live page. Any guide presenting them as open intakes is out of date.</p>

<p>Internships do exist, both curricular and extracurricular, but they are routed through agreements with Italian universities. If you are not enrolled at one, that door is narrow too.</p>

<h2>The Numbers Leonardo Publishes</h2>

<p>These check out word for word on Leonardo's careers page, so you can use them with confidence:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Claim</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Leonardo's own wording</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Size</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"more than 62,000 employees, who have mostly STEM profiles, present in more than 150 countries"</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">STEM share</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"64% of employees with STEM-based training"</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Research</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"17,000 people in R&amp;D"</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Training</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">"1,360 training opportunities activated (internships, apprenticeships, traineeships, work placement programmes)"</td>
        </tr>
    </tbody>
</table>

<p>One correction while we are here. Those 1,360 are <strong>partnerships with the education system, not internal staff courses.</strong> Leonardo counts staff training separately, at roughly 1.6 million hours delivered to employees.</p>

<p>And one thing to delete from your notes entirely: <strong>there is no Leonardo compensation tool called "Look Up".</strong> We checked the page source. "Look Up" is a video in the careers video gallery, with the alt text "look up video image". Somebody scraped a carousel label and turned it into a product, and it has been repeated ever since.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-leonardo-aerospace-jobs-in-italy-lab.jpg" alt="Engineers working on electronic and avionics test equipment in a laboratory" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Leonardo's busiest Italian hiring sites include Turin Corso Francia, Venegono Superiore, Genova Fiumara and Rome.</figcaption>
</figure>

<h2>Which Visa Route Actually Works?</h2>

<p>There are two doors into Italy for employed work, and they are not remotely equal. Most guides describe the wrong one.</p>

<p><strong>Door one: the Decreto Flussi quota.</strong> Ordinary employed work runs through an annual quota with a literal click day. For 2026 the decree admits 164,850 people in total across all work categories, and for non-seasonal employed work the applications open at 09:00 on <strong>16 February</strong>. Pakistan is on the eligible country list &mdash; that part is genuinely good news &mdash; but it shares an allocation of 25,000 with 37 other nationalities, and the forms must be pre-compiled the previous autumn. Miss the window and you wait a year.</p>

<p><strong>Door two: the EU Blue Card, which is outside the quota entirely.</strong> Italian law states that entry for highly qualified workers is permitted "al di fuori delle quote" &mdash; outside the quotas. No click day. Applications year-round. For an engineer with a degree and a real Leonardo offer, <strong>this is the route to ask about</strong>, and it is the one to raise with the employer.</p>

<p>What the Blue Card needs: a higher education qualification of at least three years or EQF level 6, or five years of comparable professional experience; a binding job offer of at least six months; and a salary that is not below the applicable national collective agreement rate and not below the ISTAT national average gross annual salary.</p>

<p><strong>Note what that last point does not say: a fixed euro figure.</strong> Italian law sets no single Blue Card salary number. If you have read that the threshold is 24,789 euros, that figure comes from the repealed 2012 regime and is wrong. The nulla osta for a Blue Card is now issued within 30 days rather than 90.</p>

<h2>Two Things Pakistani Applicants Must Check</h2>

<p>These are specific, current and easy to miss.</p>

<p><strong>First, the nulla osta suspension.</strong> Following Decree-Law 145 of 11 October 2024, the validity of work nulla osta already issued to citizens of several countries, Pakistan included, was suspended pending verification by the Sportello Unico. As recently as <strong>7 September 2026</strong> the Italian Embassy in Islamabad was still publishing notices telling applicants whose cases were closed under that suspension that they may request a new appointment if their nulla osta has since been reactivated. If your case falls in that window, check its status before doing anything else.</p>

<p><strong>Second, the appointment waiting list.</strong> In February 2026 the Embassy activated an online waiting list for work visa appointments, covering seasonal work, employed and highly skilled work, and self-employment. Registration is online, appointments are allocated in chronological order of registration, and the Embassy is blunt about the alternative: it warns that intermediaries illegally offer visa appointments in exchange for money, and that <strong>visa appointments should never be purchased</strong>.</p>

<p>Italian law backs that up. Licensed employment intermediaries are forbidden from demanding or receiving payment from a worker, directly or indirectly.</p>

<p>One more correction that trips people up on arrival: the residence contract and permesso di soggiorno must be dealt with <strong>within 15 days</strong> of entering Italy, not the 8 days that older articles still quote.</p>

<h2>Frequently Asked Questions</h2>

<h3>Where do I apply for Leonardo jobs in Italy?</h3>
<p>On Leonardo's Workday portal at leonardocompany.wd3.myworkdayjobs.com/LeonardoCareerSite, then use the Location filter. The pages on leonardo.com are informational and do not list vacancies.</p>

<h3>Does Leonardo require Italian citizenship or security clearance?</h3>
<p>No Italian advert we scanned carried a citizenship requirement, and clearance appeared in only about two per cent, phrased as willingness to obtain one. Classified programmes do require a Nulla Osta di Sicurezza, which the Italian state issues and Leonardo does not itemise by role.</p>

<h3>Do I need to speak Italian to work at Leonardo?</h3>
<p>For most Italian vacancies, realistically yes. The large majority of adverts are written in Italian, including their requirement sections, and a few ask for native Italian.</p>

<h3>How much does Leonardo pay engineers in Italy?</h3>
<p>Leonardo publishes a Total Base Pay Range on many adverts. A live junior avionics systems engineering role at Cascina Costa showed 32.731,92 to 43.000. Read the band on the advert you are applying to.</p>

<h3>Can Pakistani engineers apply to Leonardo?</h3>
<p>Yes, Leonardo publishes no nationality bar. The practical obstacles are Italian-language requirements, the Italian visa route, and the nulla osta suspension affecting Pakistani nationals that was still being unwound in September 2026.</p>

<h3>Is the Leonardo Deep Dive thesis programme open to international students?</h3>
<p>No. Leonardo's own page states the programme is aimed only at Italian students. Officina Leonardo and the Leonardo Hackathons are marked completed.</p>

<h3>Does Leonardo sponsor work visas?</h3>
<p>Leonardo publishes nothing about visa sponsorship or relocation, and its adverts are silent on it. Ask directly, and ask specifically about the EU Blue Card, which sits outside the Italian quota system.</p>

<h3>What is the EU Blue Card salary threshold in Italy?</h3>
<p>There is no single figure in Italian law. The salary must meet the applicable national collective agreement rate and be no lower than the ISTAT average annual gross salary. The often-quoted 24,789 euros belongs to a repealed 2012 regime.</p>

<h2>People Also Search For</h2>

<h3>Leonardo careers Workday</h3>
<p>The live vacancy portal, separate from leonardo.com. It also offers an Introduce Yourself route for speculative applications.</p>

<h3>Leonardo SpA salary engineer</h3>
<p>Published on the adverts themselves as a Total Base Pay Range, which makes aggregator estimates unnecessary for this employer.</p>

<h3>Nulla Osta di Sicurezza</h3>
<p>Italy's national security clearance, issued by the state. Required for classified programmes and, in practice, not obtainable by non-EU nationals.</p>

<h3>Decreto Flussi 2026 click day</h3>
<p>Non-seasonal employed work opens at 09:00 on 16 February 2026, with forms pre-compiled the previous autumn. Pakistan is among the eligible countries.</p>

<h3>Carta Blu UE Italia requisiti</h3>
<p>A three-year degree or EQF level 6, or five years of comparable experience, a job offer of at least six months, and pay at or above the collective agreement rate and the ISTAT average.</p>

<h3>Leonardo Italy locations</h3>
<p>Rome, Turin and Caselle, Cascina Costa di Samarate, Venegono Superiore, Genova Fiumara, Nerviano, Pomigliano d'Arco, La Spezia, Cameri, Foggia and Fucino, among others.</p>

<h3>Italy permesso di soggiorno after arrival</h3>
<p>The residence contract and permit application are handled within 15 days of entry. The old 8-day figure comes from superseded guidance.</p>

<h3>Italy visa appointment Pakistan</h3>
<p>Through the Embassy's online waiting list, allocated in registration order. The Embassy warns that appointments should never be purchased.</p>

<h2>More Job Guides</h2>

<p>Comparing European engineering employers, or looking wider? These cover it:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; the other big European engineering name, and a very different visa system.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; Italian industry with the same language barrier.</li>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; manufacturing with a clearer entry route.</li>
    <li><a href="/blog/how-to-apply-for-neom-construction-jobs-in-saudi-arabia">How to Apply for NEOM Construction Jobs in Saudi Arabia</a> &mdash; a famous employer whose job board is currently empty.</li>
    <li><a href="/blog/how-to-apply-for-pdo-engineering-jobs-in-oman">How to Apply for PDO Engineering Jobs in Oman</a> &mdash; Gulf engineering, and the portal that replaced PetroJobs.</li>
    <li><a href="/blog/how-to-apply-for-telkom-indonesia-it-jobs">How to Apply for Telkom Indonesia IT Jobs</a> &mdash; and the national ID requirement that closes the door first.</li>
    <li><a href="/blog/how-to-apply-for-airbus-aerospace-jobs-in-france">How to Apply for Airbus Aerospace Jobs in France</a> &mdash; 622 French vacancies, and how many need a security clearance.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Leonardo's own careers pages and Workday job API, its Integrated Report 2025, the Italian immigration code on normattiva.it, the Decreto Flussi published in the Gazzetta Ufficiale, and notices from the Italian Embassy in Islamabad, checked on 22 September 2026. Vacancy counts, programme intakes and immigration rules change. Always check the live posting and the official government source before acting, and never pay anyone to secure a job or a visa appointment.</p>
HTML;
    }
}
