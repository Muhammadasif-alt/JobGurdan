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
 * "How to Apply for PDO Engineering Jobs in Oman" — a guide that exists mainly
 * to stop people walking into a door that was bricked up in May 2026. Every
 * other PDO article still points at PetroJobs, which is now a redirect stub.
 *
 * Corrections to the draft (checked against petrojobs.om, kwader.mem.gov.om,
 * pdo.co.om and PDO's own Sustainability Report 2025, 22 September 2026):
 *
 * 1. PetroJobs is dead. petrojobs.om serves a 214-byte meta-refresh to
 *    kwader.mem.gov.om, the Ministry of Energy and Minerals platform that
 *    replaced it. The draft's two headline links both fail.
 *
 * 2. The "PDO Candidate User Guide" PDF the draft cites returns 404. The
 *    archived copy recommends Internet Explorer 6, so it was never current.
 *
 * 3. PDO has no vacancies on the replacement portal. Kwader carried three
 *    adverts in total when checked, two from OQ and one from CC Energy
 *    Development. PDO's partner page has no job tab at all.
 *
 * 4. The four named vacancies in the draft (Lead Mechanical Static/Piping,
 *    Senior Process, Senior Contracts, Senior Cost and Planning) appear on no
 *    official source. They are aggregator reposts and are dropped.
 *
 * 5. The Shababuna specifics (2.50 CGPA, four-year contract, under three
 *    years' experience) cannot be quoted from any live official page, and
 *    "Shababuna" does not appear once in PDO's own 2025 report. They are
 *    reported as unverifiable rather than restated as fact.
 *
 * 6. The draft never tells an expatriate the one number that decides this for
 *    them: PDO is 92 per cent Omanised and employs about 701 non-Omanis in a
 *    workforce of 9,265. That is now the lead.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PdoEngineeringJobsOmanBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://kwader.mem.gov.om/jobs';

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
        $title = 'How to Apply for PDO Engineering Jobs in Oman';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'PetroJobs is gone. Oman oil and gas recruitment moved to a government portal called Kwader, and PDO is 92 per cent Omanised. Here is the live link and an honest read on your chances.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-pdo-engineering-jobs-in-oman.jpg',
                'tags' => 'pdo jobs, pdo careers, petroleum development oman, kwader jobs, petrojobs oman, oman oil and gas jobs, oman work visa, omanisation',
                'meta_title' => 'PDO Engineering Jobs in Oman: How to Apply',
                'meta_description' => 'PDO engineering jobs in Oman: why PetroJobs is dead, the government portal that replaced it, and what 92 per cent Omanisation means for foreign engineers.',
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
            ['name' => 'Oman Oil and Gas Operators, via Kwader'],
            ['type' => 'Company', 'display_reference' => 'kwader-oman-oil-gas']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Oman'],
            ['area' => 'Nationwide', 'country' => 'Oman']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Oil and Gas Engineering, Oman Operators, Sultanate of Oman',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Site and rotation based, subject to Oman Labour Law',
                'language' => 'English',
                // No Oman operator publishes pay on Kwader, and PDO publishes
                // none anywhere. The salary ranges circulating for PDO come
                // from aggregator sites and have no official basis.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Engineering roles advertised by Oman oil and gas operators on the government Kwader portal. Check nationality eligibility before applying.',
                'seo_keywords' => 'pdo jobs, kwader jobs oman, oman oil and gas jobs, petroleum development oman careers, petrojobs oman',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the engineering roles advertised by Oman's oil and gas operators, not a single vacancy and not a job advertised by JobGader. Applications start on the Ministry of Energy and Minerals portal and finish on the operator's own system.</p>

<h3>Where these jobs are advertised</h3>
<p>PetroJobs no longer exists. Oman's oil and gas operators now advertise through Kwader at kwader.mem.gov.om, run by the Ministry of Energy and Minerals. Kwader states that you will be redirected to the operator's website and that the rest of the application process is completed there.</p>

<h3>Read this before you apply</h3>
<p>The board is thin. When this listing was last checked, Kwader carried three adverts in total across the whole sector, and Petroleum Development Oman had none of them. Check the live portal yourself rather than trusting any site that claims to list dozens of current PDO vacancies.</p>

<h3>Omanisation</h3>
<p>PDO reported a record Omanisation rate of 92 per cent in its 2025 Sustainability Report, with 8,564 Omani and 701 non-Omani employees in a workforce of 9,265. Many roles, and most graduate programmes, are restricted to Omani nationals. Check the nationality requirement on every advert.</p>

<h3>Pay</h3>
<p>No Oman operator publishes a salary on Kwader, and PDO publishes none anywhere. Any "PDO engineer salary" range you have read came from an aggregator, not from PDO.</p>

<h3>Visas and fees</h3>
<p>The Royal Oman Police states a work visa is granted at the request and on the responsibility of an employer, and that the occupation on the visa must match the labour permit. Engineers need an additional letter from the labour ministry. Article 31 of Oman's Labour Law prohibits charging any sums from a recruited worker in return for employing him.</p>

<p>Pay, eligibility, nationality rules and visa requirements are set by the operators, the Oman Ministry of Labour and Oman labour law &mdash; not by JobGader. Confirm the requirements on the live posting before you act on anything here.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Before anything else, the link in almost every PDO jobs article on the internet is dead.</p>

<p><strong>PetroJobs no longer exists.</strong> Type petrojobs.om into a browser and you do not reach a job board. You reach a 214-byte holding page whose only job is to bounce you somewhere else. We fetched it and read the source: it is a meta-refresh, and nothing more.</p>

<p>Where it bounces you is the good news, and it is the thing worth knowing.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-pdo-engineering-jobs-in-oman-field.jpg" alt="Oil and gas production facility and pipework at a desert field site in Oman" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">PDO contributes approximately 70 per cent of Oman's crude oil production, according to the Ministry of Energy and Minerals.</figcaption>
</figure>

<h2>What Replaced PetroJobs?</h2>

<p>A government platform called <strong>Kwader</strong>, run by Oman's Ministry of Energy and Minerals. It describes itself plainly:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Kwader (formerly PetroJobs) is a unified electronic recruitment platform for the oil and gas sector in the Sultanate of Oman. It aims to organize and enhance the efficiency of recruitment processes across operating companies through a centralized interface that connects job seekers with employers based on clear and transparent standards."</p>

<p>This is where the oil and gas operators advertise now. Petroleum Development Oman is listed among its partners, alongside OQ, bp Oman, Daleel Petroleum, Oxy Oman, CC Energy Development, ARA Petroleum, Oman LNG, Masar Petroleum and Tethys Oil.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://kwader.mem.gov.om/jobs" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Open the Kwader Job Search &rarr;</a>
</p>

<p>Two practical notes from using it. The site loads in Arabic with an English toggle in the header, so do not panic if the first screen is not in English. And Kwader tells you what it is: <strong>"You will be redirected to the operator website. The rest of the application process can be completed in operator website."</strong> Kwader gathers the adverts. The application itself finishes on the employer's own system.</p>

<h2>How Many PDO Jobs Are Actually Open?</h2>

<p>None. And we want to be precise rather than dramatic about that.</p>

<p>When we checked the portal, Kwader carried <strong>three adverts in total across the entire Omani oil and gas sector</strong>:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Role</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Operator</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Closing</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Manager, Environmental Management</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">OQ</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">5 October 2026</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Lead Engineer Energy Performance</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">OQ</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">5 October 2026</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Production Technologist (Bachelor, 7 years)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">CC Energy Development</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">25 September 2026</td>
        </tr>
    </tbody>
</table>

<p><strong>Not one of them is a PDO vacancy.</strong> The portal's own company filter offered only OQ and CC Energy Development, because no other operator had anything live.</p>

<p>So if you have seen a page listing "Senior Process Engineer", "Lead Mechanical Static/Piping Engineer", "Senior Contracts Engineer" or "Senior Cost &amp; Planning Engineer" as current PDO openings, check where it came from. <strong>None of those four appears on any official Omani source.</strong> They are aggregator reposts, and some of the same pages attach invented salary ranges to them.</p>

<h2>The Number That Actually Decides This</h2>

<p>Here is the fact that no PDO jobs article seems willing to lead with, and it is the one that should shape your expectations. It comes from PDO's own Sustainability Report 2025.</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"A record Omanisation rate of 92%."</p>

<p>The workforce table in the same report breaks it down:</p>

<ul>
    <li>Total employees: 9,278 in 2024, <strong>9,265 in 2025</strong></li>
    <li>Omani employees: 8,486 in 2024, <strong>8,564 in 2025</strong></li>
    <li>Non-Omani employees: 792 in 2024, <strong>701 in 2025</strong></li>
</ul>

<p><strong>PDO employs roughly 701 non-Omanis in total, and that number fell by 91 in a single year.</strong> This is not a company running an international recruitment drive. It is a company whose stated, celebrated objective is to employ fewer foreigners each year.</p>

<p>That does not make it impossible. Specialist and senior technical roles are where the remaining 701 sit. But it does mean a fresh graduate from overseas should be realistic, and it means anyone promising you a PDO job is promising you a seat in a shrinking room.</p>

<h2>What About the Shababuna Graduate Programme?</h2>

<p>You will find a lot written about it. We could verify almost none of it, and we would rather tell you that than repeat it.</p>

<p>The programme is real &mdash; PDO has publicly celebrated Shababuna graduations. But PDO's press-release archive has been restructured and those pages now return 404, and <strong>the word "Shababuna" does not appear once in PDO's own 2025 Sustainability Report</strong>, which refers only generally to "the intake of graduates, trainees, and scholars".</p>

<p>The specifics circulating online &mdash; a minimum CGPA of 2.50 without rounding, a four-year contract, a cut-off of under three years' experience, and Omani nationality only &mdash; all trace back to a PetroJobs advert that no longer exists, or to aggregator sites. <strong>We cannot quote any of them from a live official page, so we are not going to state them as current requirements.</strong></p>

<p>One point does stand regardless: every account of Shababuna agrees it is for Omani nationals. If you are not Omani, this is not your route, and no amount of detail about its CGPA rule would help you.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-pdo-engineering-jobs-in-oman-desert.jpg" alt="Engineers working at an industrial oil and gas installation in the Omani interior" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">PDO's 2025 report records 701 non-Omani employees out of a workforce of 9,265.</figcaption>
</figure>

<h2>Does PDO's Own Website Help?</h2>

<p>No, and this is worth saying because people waste hours there. PDO's "Working at PDO" page exists and loads, but it contains no link to Kwader, no link to PetroJobs, no vacancy list and no application instructions of any kind. There is no careers.pdo.co.om or jobs.pdo.co.om either; neither resolves.</p>

<p>The connection between PDO and the recruitment portal is published by the Ministry, not by PDO. So go to Kwader directly. Do not go hunting on pdo.co.om.</p>

<h2>What Does It Pay?</h2>

<p>Nothing we can honestly tell you, and that is the accurate answer rather than a dodge.</p>

<p>No operator publishes salary on Kwader's adverts, and PDO publishes no pay figure anywhere. One aggregator page quotes a range of "300 OMR to 4,000 OMR per month" for a supposed PDO recruitment drive; that has no official basis at all, and the width of the range should tell you it was guessed. We will not repeat it as a figure.</p>

<h2>The Visa, and Who Pays for It</h2>

<p>If you do land an offer, the process is not yours to run. The Royal Oman Police states that a work visa "is granted at the request and on the responsibility of an employer", under a labour permit, and that <strong>"the occupation mentioned in the visa application shall be the same as in the labour permit"</strong>. A worker cannot apply for his own Omani employment visa.</p>

<p>Three details from the ROP requirements that are worth knowing before you spend money on anything:</p>

<ul>
    <li><strong>Engineers need an extra letter.</strong> ROP requires a "Letter from the Ministry of Manpower for certain occupations, like engineers." That is nationality-neutral &mdash; it applies to every engineer.</li>
    <li><strong>Pakistani, Indian, Bangladeshi, Sri Lankan and Nepali applicants need a medical certificate attested by the Ministry of Health</strong>, done before arrival through an approved centre. ROP names those countries specifically, along with the Philippines, Indonesia, Egypt, Sudan, Ethiopia and Syria.</li>
    <li>The applicant must be at least 21. ROP publishes no maximum age, whatever you may have read.</li>
</ul>

<p>And the fee rule is the one to hold on to. <strong>Article 31 of Oman's Labour Law (Royal Decree 53/2023) prohibits charging any sums from the worker being recruited in return for employing him.</strong> Recruitment activity itself requires a Ministry licence. So anyone asking you for money to "arrange" an Oman job is breaking Omani law before you have even left home.</p>

<p>One more, in your favour: Article 14 requires the employer to return a non-Omani worker to his country within 60 days of the contract ending, and to give a clearance letter on request.</p>

<p>Be equally careful with the anti-fraud advice you find elsewhere. PDO has warned the public about a fake recruitment agency before, but the statement it issued told people that recruitment runs solely through petrojobs.om &mdash; the domain that is now a redirect stub. Advice can go stale and still sound authoritative. In 2026 the honest version is: PDO recruitment appears on Kwader, and no legitimate employer charges you a fee.</p>

<h2>What to Actually Do</h2>

<ol>
    <li>Go to kwader.mem.gov.om/jobs, switch it to English, and register an account so you can be notified.</li>
    <li>Filter by Engineering and check the company and nationality fields on every advert before you invest time.</li>
    <li>Widen your search beyond PDO. OQ and CC Energy Development are the operators currently advertising, and the other partners post there too.</li>
    <li>Ignore any site presenting a list of open PDO vacancies. Compare it against Kwader and you will see the difference.</li>
    <li>Never pay a fee, and never accept a job offer that did not come through an official process.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Is PetroJobs still the way to apply for PDO jobs?</h3>
<p>No. petrojobs.om now serves only a redirect to kwader.mem.gov.om. The PDO Candidate User Guide PDF that many articles link to returns a 404, and the archived version recommends Internet Explorer 6, so it was never current guidance.</p>

<h3>What is Kwader?</h3>
<p>The unified electronic recruitment platform for Oman's oil and gas sector, run by the Ministry of Energy and Minerals. It describes itself as "Kwader (formerly PetroJobs)". Job search is at kwader.mem.gov.om/jobs.</p>

<h3>How many PDO engineering jobs are open right now?</h3>
<p>None on the official portal. Kwader carried three adverts in total when checked, two from OQ and one from CC Energy Development. PDO's partner page on Kwader shows no job listings tab at all.</p>

<h3>Can Pakistani and Indian engineers get a job at PDO?</h3>
<p>It is possible but structurally hard. PDO is 92 per cent Omanised and employed about 701 non-Omanis in 2025, down from 792 the year before. Specialist and senior technical roles are the realistic target; graduate programmes are not.</p>

<h3>Is the Shababuna programme open to foreigners?</h3>
<p>No account of it suggests so; every version describes it as being for Omani nationals. Note that the detailed entry rules circulating online cannot be verified from any live official PDO page.</p>

<h3>What does a PDO engineer earn?</h3>
<p>PDO publishes no salary figure, and no operator publishes pay on Kwader. Any range you have seen is an aggregator's estimate.</p>

<h3>Who applies for an Oman work visa?</h3>
<p>The employer. ROP states it "is granted at the request and on the responsibility of an employer", and the occupation on the visa must match the labour permit. Engineers also need a letter from the labour ministry. You cannot apply for your own.</p>

<h3>Does pdo.co.om list vacancies?</h3>
<p>No. PDO's own careers page carries no vacancy list, no application instructions and no link to any recruitment portal. Use Kwader instead.</p>

<h2>People Also Search For</h2>

<h3>PetroJobs Oman login</h3>
<p>The account system moved with the platform. Registration is now at kwader.mem.gov.om/register, under the Ministry of Energy and Minerals.</p>

<h3>Kwader Oman jobs</h3>
<p>The current national oil and gas recruitment portal, covering PDO, OQ, bp Oman, Daleel Petroleum, Oxy Oman, CC Energy Development, ARA Petroleum, Oman LNG, Masar Petroleum and Tethys Oil.</p>

<h3>PDO careers Oman</h3>
<p>PDO's own site has a "Working at PDO" page but publishes no vacancies and links to no portal. Its adverts, when it posts them, appear on Kwader.</p>

<h3>Omanisation rate PDO</h3>
<p>92 per cent in 2025, described in PDO's Sustainability Report as a record, with 8,564 Omani and 701 non-Omani employees.</p>

<h3>Oman oil and gas companies list</h3>
<p>The operators on Kwader are PDO, OQ, bp Oman, Daleel Petroleum, Oxy Oman, CC Energy Development, ARA Petroleum, Oman LNG, Masar Petroleum and Tethys Oil.</p>

<h3>PDO Shababuna programme 2026</h3>
<p>A real PDO graduate scheme for Omani nationals, but its published entry rules cannot currently be verified from any live official PDO or Kwader page.</p>

<h3>Oman work visa requirements</h3>
<p>Employer applies, minimum age 21, occupation must match the labour permit, an extra ministry letter for engineers, and a Ministry of Health attested medical certificate for Pakistani and several other nationalities.</p>

<h3>PDO salary for engineers</h3>
<p>Not published by PDO, and not shown on Kwader adverts. Every figure in circulation is an estimate from a third-party site.</p>

<h2>More Job Guides</h2>

<p>Widening the search across the Gulf is usually the better move. These cover it:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; the region's largest engineering employer, with a live board.</li>
    <li><a href="/blog/how-to-apply-for-neom-construction-jobs-in-saudi-arabia">How to Apply for NEOM Construction Jobs in Saudi Arabia</a> &mdash; another famous name whose job board is currently empty.</li>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; the other big Omani employer people search for.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; where the employer everyone searches for turns out to be the wrong one.</li>
    <li><a href="/blog/how-to-get-a-logistics-driver-job-in-the-uae">How to Get a Logistics Driver Job in the UAE</a> &mdash; Gulf work with a much shorter queue.</li>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; an employer that publishes real salary bands on its adverts.</li>
    <li><a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">How to Apply for BP Engineering Jobs in the UK</a> &mdash; which engineering codes clear the UK salary threshold, and which bp adverts are really agencies.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using the live state of petrojobs.om and kwader.mem.gov.om, PDO's own Sustainability Report 2025, pdo.co.om, the Royal Oman Police work-visa requirements and the text of Oman's Labour Law (Royal Decree 53/2023) published by the Ministry of Labour, checked on 22 September 2026. Vacancies, nationality rules and immigration requirements change. Always check the live portal and the official government source before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
