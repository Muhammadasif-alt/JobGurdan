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
 * "Nurse Jobs in the US" — a sector guide rather than one vacancy, so the apply
 * link goes to an Indeed search and the post carries no JobPosting markup.
 *
 * The draft described the route as NCLEX, English test, VisaScreen, then a
 * sponsored job. That is the licensing half. The half it omits is the one that
 * decides when you actually arrive: staff RN roles do not generally qualify for
 * H-1B, so nurses immigrate on an employment-based green card, and EB-3 has
 * been retrogressed for every country. Telling a nurse the process is four
 * steps, without saying that the last one is a queue measured in years, is the
 * error that makes people sign multi-year contracts they do not understand.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class NurseJobsUsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-registered-nurse-jobs.html';

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
        $title = 'Nurse Jobs in the US';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What internationally trained nurses actually face: state licensure and NCLEX, why staff RN roles rarely qualify for H-1B, how long the EB-3 green card queue really is by country, and the contract terms to read before signing.',
                'content' => $content,
                'featured_image' => 'blogs/nurse-jobs-in-us.jpg',
                'tags' => 'nurse jobs in us, nursing jobs usa with visa sponsorship, nclex rn, visascreen cgfns, eb-3 nurse green card, registered nurse salary usa, hospital nurse jobs, nursing jobs without nclex, international nurse recruitment',
                'meta_title' => 'Nurse Jobs in the US: Licensing and Visa Routes',
                'meta_description' => 'Nurse jobs in the US for international nurses: NCLEX and state licensure, why H-1B rarely applies, how long the EB-3 queue is, and real salary ranges.',
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
            ['name' => 'US Hospitals & Health Systems (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-nursing-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Registered Nurse — Hospitals and Health Systems',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Usually three or four 12-hour shifts a week, including nights and weekends',
                'language' => 'English',
                // Pay is set per state, per system and per specialty, so no
                // single national range would be true.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Registered nurse roles across US hospitals and health systems: ICU, ER, Med-Surg and Labor & Delivery. State licence and NCLEX-RN required. Apply on the employer portal.',
                'seo_keywords' => 'nurse jobs usa, registered nurse jobs, nclex rn, international nurse jobs usa, hospital nurse jobs, icu nurse jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US hospitals and health systems recruit registered nurses continuously across ICU, emergency, medical-surgical, labour and delivery, theatre and community roles, alongside travel assignments that fill short-term gaps. Hospital posts are the most common route for both newly qualified and experienced nurses, and they carry the most structured orientation for staff arriving from abroad.</p>

<h3>Licensing comes before the job, and it is per state</h3>
<p>There is no national US nursing licence. You are licensed by a <strong>state Board of Nursing</strong>, each with its own requirements, and the job you accept has to be in a state you are licensed in. Every route requires the <strong>NCLEX-RN</strong> examination. Internationally trained nurses also need a credentials evaluation and, for the visa, a <strong>VisaScreen</strong> certificate from CGFNS, which itself requires an English test unless you are exempt.</p>

<h3>Requirements</h3>
<ul>
    <li>A nursing qualification evaluated as equivalent by an approved credentials service</li>
    <li>A passing NCLEX-RN result and a licence from the state you will work in</li>
    <li>English proficiency &mdash; IELTS Academic, OET or TOEFL, at the score your board and CGFNS require</li>
    <li>VisaScreen certification from CGFNS for the visa application</li>
    <li>BLS certification, and ACLS or PALS for many acute specialties</li>
</ul>

<h3>What is on offer</h3>
<ul>
    <li>Structured orientation and preceptorship, longer for internationally educated nurses</li>
    <li>Shift differentials for nights and weekends, on top of base pay</li>
    <li>Health cover, retirement contributions and tuition support at most large systems</li>
    <li>Specialty progression into ICU, emergency, theatre or advanced practice</li>
</ul>

<h3>Before you sign anything</h3>
<p><strong>Read the contract term and the exit clause.</strong> International nurse recruitment commonly uses multi-year contracts with a substantial sum payable if you leave early. Ask what the figure is, what triggers it, and whether it reduces over time. Ask separately who pays for NCLEX, VisaScreen, licensure and travel, and whether any of it is recoverable from you. Charging a worker a recruitment fee is prohibited by most reputable employers and by law in several jurisdictions.</p>

<p><strong>Note:</strong> licensure requirements, visa timelines and pay are set by state boards, the US government and each employer &mdash; not by JobGader. Verify current requirements with the state Board of Nursing and CGFNS before paying any fee.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>The demand is real. US hospitals are short of nurses, the pay is among the best in the world for the profession, and hospitals genuinely do sponsor internationally trained staff. What most guides on this subject leave out is the part that decides <em>when</em> you actually start: the licensing steps are the easy half, and the immigration queue behind them is measured in years, not months. This guide covers both, so you can plan against the real timeline rather than the advertised one.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-registered-nurse-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🩺 Browse Nurse Jobs in the US &rarr;
    </a>
</div>

<h2>The Visa Reality Nobody Puts First</h2>

<p>Start here, because it changes how you should plan everything else.</p>

<p><strong>A staff registered nurse post does not usually qualify for an H-1B visa.</strong> H-1B is for specialty occupations that normally require a bachelor's degree, and because RN licensure can be reached by several educational routes, most general staff RN roles fail that test. H-1B does get used for advanced practice &mdash; nurse practitioners, clinical nurse specialists and some highly specialised posts &mdash; but it is not the route for a ward nurse.</p>

<p>So internationally trained nurses are sponsored for a <strong>green card</strong> instead, normally in the employment-based third preference, <strong>EB-3</strong>. That has one real advantage: nursing is on <strong>Schedule A</strong>, a pre-certified shortage list, which lets an employer skip the labour certification stage that most other occupations must complete. It also has one serious constraint.</p>

<p><strong>EB-3 is retrogressed.</strong> As of early 2026 every country has a final action date behind the present, meaning approved petitions wait for a visa number to become available:</p>

<ul>
    <li><strong>India</strong> &mdash; final action date in November 2013.</li>
    <li><strong>China (mainland)</strong> &mdash; May 2021.</li>
    <li><strong>Philippines</strong> &mdash; August 2023.</li>
    <li><strong>Mexico</strong> &mdash; October 2023.</li>
    <li><strong>All other countries</strong>, including Pakistan &mdash; also retrogressed, though the queue moves faster than for India or the Philippines.</li>
</ul>

<p>In practice, nurses from the Philippines and India have been facing backlogs in the range of three to seven years on new petitions. The dates move &mdash; they advanced somewhat during 2026 &mdash; but they move unpredictably and they can go backwards. Check the current month's Visa Bulletin for your country of birth before making any plan that depends on a date.</p>

<p>Two exceptions worth knowing. <strong>Canadian and Mexican nurses</strong> can use the <strong>TN</strong> category under the North American trade agreement, which is far quicker and does not involve this queue. And nurses <em>already</em> in the US on another status sometimes have options a nurse abroad does not.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nurse-jobs-in-us-hospital.jpg"
         alt="Nurses and clinicians on a hospital unit in the United States"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Licensing: NCLEX, State Boards and VisaScreen</h2>

<p>There is no single US nursing licence. You are licensed by an individual <strong>state Board of Nursing</strong>, and requirements differ between states &mdash; on credential evaluation, on English testing, on clinical hour minimums, and on how they treat a foreign qualification. Choose the state before you start the paperwork, because doing it in the wrong order costs months.</p>

<ol>
    <li><strong>Credentials evaluation.</strong> Your nursing education is assessed against US standards by an approved evaluation service.</li>
    <li><strong>NCLEX-RN.</strong> The national licensing examination, taken after a board authorises you to test. It can be sat at international test centres.</li>
    <li><strong>English proficiency.</strong> IELTS Academic, OET or TOEFL, at the score your board and CGFNS require. Some boards exempt nurses educated in English-speaking countries.</li>
    <li><strong>VisaScreen.</strong> A certificate issued by CGFNS confirming education, licence and English meet US standards. It is required for the visa, separately from the state licence.</li>
</ol>

<p>The <strong>Nurse Licensure Compact</strong> lets a nurse hold one multistate licence valid across participating states &mdash; but it is based on your primary state of residence, so it becomes relevant after you arrive rather than before.</p>

<h2>Nursing Jobs Without the NCLEX</h2>

<p>Roles that do not require an RN licence exist &mdash; nursing assistant, patient care technician, medical assistant, unit secretary. They are a genuine way to be inside a health system while you complete licensure, and some employers support staff through the process.</p>

<p>Be clear about what they are, though. The pay is a fraction of an RN's, the scope of practice is much narrower, and &mdash; crucially &mdash; these roles are not on Schedule A and generally cannot be sponsored from overseas. They are an option for someone already in the US with work authorisation, not a back door into the country.</p>

<h2>What Nurses Earn in the US</h2>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;text-align:left;">
            <th style="padding:10px;border:1px solid #e5e7eb;">Level</th>
            <th style="padding:10px;border:1px solid #e5e7eb;">Typical annual range</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">New graduate RN</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$60,000 &ndash; $70,000</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Experienced RN</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$75,000 &ndash; $95,000</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">ICU, emergency and specialised</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">$95,000 &ndash; $120,000+</td>
        </tr>
    </tbody>
</table>

<p>Two things move those numbers more than experience does. <strong>State</strong> &mdash; the spread between the highest and lowest paying states is enormous, and the highest-paying ones are also the most expensive to live in, so compare against local rent rather than against home. And <strong>shift differentials</strong> &mdash; nights, weekends and on-call premiums are paid on top of base and can add a meaningful percentage for anyone working an unsocial rota.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/nurse-jobs-in-us-team.jpg"
         alt="A nursing team at a US hospital"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Jobs Are Actually Posted</h2>

<ul>
    <li><strong>Hospital and health system career pages</strong> &mdash; the primary source. Large systems post everything they have and run their own international programmes.</li>
    <li><strong>General job boards</strong> &mdash; useful for scanning the market and comparing pay across states.</li>
    <li><strong>Federal and public roles</strong> &mdash; the government jobs portal and the Indian Health Service carry public-sector nursing posts with their own hiring process.</li>
    <li><strong>International recruitment agencies</strong> &mdash; they coordinate credentialing, NCLEX, VisaScreen and the petition. Genuinely useful, and the part of the market that needs the most care.</li>
</ul>

<h2>Reading a Recruitment Agency Contract</h2>

<p>Agencies do real work: they front the cost of credentialing and immigration, and they place you. In exchange, most ask for a commitment &mdash; typically a two or three year contract with a sum payable if you leave early. That structure is normal in this sector. What varies enormously is how fair the terms are.</p>

<p>Ask these before signing anything:</p>

<ul>
    <li><strong>What exactly is the early-exit figure</strong>, and does it reduce as you complete the term, or stay flat until the last day?</li>
    <li><strong>What counts as leaving early?</strong> Redundancy, illness and the agency failing to place you should not trigger it.</li>
    <li><strong>Who pays for what</strong> &mdash; NCLEX, evaluation, VisaScreen, licensure, flights, initial housing &mdash; and is any of it recoverable from you?</li>
    <li><strong>What is the pay rate</strong>, in writing, and how does it compare to what the hospital pays its directly hired nurses in the same unit?</li>
    <li><strong>What happens during retrogression?</strong> If the queue means a two-year wait, are you supported, and does the contract clock start at signature or at arrival?</li>
</ul>

<p>And one absolute: <strong>be extremely wary of anyone charging you a placement fee</strong> to be hired. Reputable employers do not permit it, and it is the most common shape of exploitation in international nurse recruitment.</p>

<h2>Frequently Asked Questions</h2>

<h3>How do I get a nursing job in the USA as a foreigner?</h3>
<p>Get your credentials evaluated, pass the NCLEX-RN, meet the English requirement, obtain VisaScreen from CGFNS, and secure a licence from the state you will work in. Then a US employer sponsors you &mdash; normally for an employment-based green card rather than a temporary work visa.</p>

<h3>Can nurses get an H-1B visa?</h3>
<p>Usually not for a staff RN post. H-1B requires a specialty occupation normally needing a bachelor's degree, and most general RN roles do not meet that test. It is used for nurse practitioners and some advanced or highly specialised positions.</p>

<h3>How long does the EB-3 green card take for nurses?</h3>
<p>It depends on your country of birth and it is currently retrogressed for everyone. Filipino and Indian nurses have faced backlogs of roughly three to seven years on new petitions; other countries move faster but still wait. Check the current Visa Bulletin, because the dates move in both directions.</p>

<h3>What is Schedule A and why does it matter for nurses?</h3>
<p>Schedule A is a pre-certified shortage list that includes registered nursing. It lets an employer skip the labour certification stage, which removes months from the front of the process. It does not remove the visa queue behind it.</p>

<h3>Which English test do US nursing boards accept?</h3>
<p>IELTS Academic, OET and TOEFL are the usual options, with minimum scores set by each state board and by CGFNS for VisaScreen. Nurses educated in certain English-speaking countries may be exempt, so check your specific board's rule.</p>

<h3>Can I work in the US as a nurse without the NCLEX?</h3>
<p>Not as a registered nurse. Support roles such as nursing assistant or patient care technician do not require it, but they pay far less, have a narrower scope, and are generally not sponsorable from overseas.</p>

<h3>What does a registered nurse earn in the US?</h3>
<p>Broadly $60,000 to $70,000 for a new graduate, $75,000 to $95,000 with experience, and $95,000 to $120,000 or more in ICU, emergency and specialised roles. State and shift differentials move those figures more than years of experience do.</p>

<h3>Should I sign a multi-year contract with a nurse recruitment agency?</h3>
<p>It is a normal structure, but read the early-exit sum, what triggers it, whether it reduces over the term, and who bears the cost of NCLEX, VisaScreen and travel. Never pay a placement fee to be hired.</p>

<h2>People Also Search For</h2>

<h3>Nursing jobs in USA with visa sponsorship</h3>
<p>Sponsorship is real but usually means an employment-based green card rather than a temporary work visa, so plan against the Visa Bulletin for your country of birth.</p>

<h3>Nurse jobs in US for foreigners</h3>
<p>Credentials evaluation, NCLEX-RN, English test, VisaScreen and a state licence, then employer sponsorship. Licensing is per state, not national.</p>

<h3>USA nursing job requirements</h3>
<p>NCLEX-RN, an approved credentials evaluation, English proficiency at your board's score, VisaScreen from CGFNS, and BLS with ACLS or PALS for acute specialties.</p>

<h3>Nursing jobs in USA without NCLEX</h3>
<p>Support roles such as nursing assistant or patient care technician, which pay far less, have a narrower scope, and are generally not sponsorable from abroad.</p>

<h3>EB-3 nurse green card</h3>
<p>The main immigration route for nurses. Nursing sits on Schedule A, which skips labour certification, but the category is retrogressed for every country.</p>

<h3>Hospital nurse jobs in US</h3>
<p>ICU, emergency, medical-surgical and labour and delivery are the volume specialties, and hospitals offer the most structured orientation for internationally educated nurses.</p>

<h3>Nursing jobs in USA salary per month</h3>
<p>Most registered nurses land between roughly $5,000 and $8,000 a month before shift differentials, with specialised and travel roles higher.</p>

<h3>International nurse recruitment agencies</h3>
<p>They coordinate credentialing and immigration in exchange for a multi-year commitment. Read the early-exit clause, and never pay a placement fee.</p>

<h2>More Job Guides</h2>

<p>Comparing destinations or other sponsored routes? These cover the rest:</p>

<ul>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; why the UK care worker route closed to overseas applicants, and what remains open for clinical staff.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; the H-2B, J-1 and EB-3 routes into US hospitality and how the caps work.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; another Schedule A adjacent route, with CDL requirements and realistic pay.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; trades sponsorship and where the demand actually sits.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Visa Bulletin dates, state licensure rules and CGFNS requirements change &mdash; confirm the current position with the state Board of Nursing, CGFNS and the monthly Visa Bulletin, or with a licensed immigration attorney, before paying any fee or signing a contract.</p>
HTML;
    }
}
