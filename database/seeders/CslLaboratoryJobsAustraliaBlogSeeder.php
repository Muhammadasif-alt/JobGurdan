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
 * "How to Apply for CSL Laboratory Jobs in Australia" — a guide whose value is
 * two numbers the draft never reaches: the occupation code that decides
 * whether the job can be sponsored at all, and the classification at which
 * CSL's own enterprise agreement crosses the sponsorship salary floor.
 *
 * Corrections to the draft (checked against jobs.csl.com, cslbehring.com.au,
 * immi.homeaffairs.gov.au, fwc.gov.au and fairwork.gov.au, 23 September 2026):
 *
 * 1. The draft omits visa eligibility, which here is genuinely good news but
 *    turns on the code. ANZSCO 234513 is Biochemist, not Medical Laboratory
 *    Scientist, which is 234611. Biochemist is on the Core Skills Occupation
 *    List and can be sponsored; Medical Laboratory Scientist is not on the
 *    CSOL at all, though it remains on the MLTSSL for points-tested PR.
 *
 * 2. The draft says pay cannot be stated. CSL has a current enterprise
 *    agreement, AE533409, approved 16 July 2026, whose published scale starts
 *    at AUD 77,276 and which expressly covers its research staff.
 *
 * 3. One of the draft's four named vacancies is gone, and CSL publishes no
 *    expiry message at all: a removed requisition returns a generic 404
 *    identical to the one a mistyped URL returns.
 *
 * 4. The draft treats the Graduate Program as an entry route without saying
 *    that it requires Australian or New Zealand citizenship or Australian
 *    permanent residency at the time of application.
 *
 * 5. The draft says Parkville matters for research. CSL still employs R&D
 *    staff there, but influenza manufacturing has moved to Tullamarine and
 *    there are no Parkville vacancies.
 *
 * 6. The draft says the careers platform filters by job family, entity,
 *    location and job type. jobs.csl.com has no entity filter, and the
 *    Workday site that does carries only 15 of the 28 Australian roles.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CslLaboratoryJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://jobs.csl.com/en/jobs';

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
        $title = 'How to Apply for CSL Laboratory Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'CSL lab work can be sponsored, but only under the right occupation code. Its own enterprise agreement starts at AUD 77,276, and the sponsorship floor is AUD 79,423, so the two cross exactly one classification above entry level.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-csl-laboratory-jobs-in-australia.jpg',
                'tags' => 'csl jobs, csl behring careers, csl seqirus jobs, laboratory jobs australia, medical laboratory scientist australia, csol biochemist, australia 482 visa, csl graduate program',
                'meta_title' => 'CSL Laboratory Jobs Australia: Pay and Visa Reality',
                'meta_description' => 'CSL laboratory jobs in Australia: which occupation codes can be sponsored, the enterprise agreement pay scale, and where sponsorship actually starts.',
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
            ['name' => 'CSL Limited, Australian Operations'],
            ['type' => 'Company', 'display_reference' => 'csl-australia']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Laboratory Scientist, CSL Behring and CSL Seqirus, Australia',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; quality control roles may run rotating day, afternoon and night shifts including weekends',
                'language' => 'English',
                // CSL publishes no salary on its Australian postings, but its
                // enterprise agreement AE533409 publishes a full scale, which
                // the guide quotes with the agreement's own floors caveat.
                'salary_currency' => 'AUD',
                'salary_period' => 'Year',
                'salary_minimum' => 77276,
                'salary_maximum' => 134764,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Quality control, quality assurance, research and clinical science roles with CSL Behring and CSL Seqirus across Melbourne, Broadmeadows, Tullamarine and Woodend.',
                'seo_keywords' => 'csl laboratory jobs australia, csl behring careers melbourne, csl seqirus jobs, qc analyst jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>CSL hires laboratory and scientific staff across its Australian operations for quality control testing, laboratory quality and compliance, upstream process development, clinical science and validation, under the CSL Behring and CSL Seqirus businesses.</p>

<h3>What the work involves</h3>
<p>GMP assay testing and LIMS record keeping, instrument qualification and calibration, laboratory quality systems and inspection readiness under GLP, GCLP and ISO/IEC 17025, cell culture and bioreactor scale-up, and clinical science supporting vaccine and biotherapy development.</p>

<h3>Common requirements</h3>
<ul>
    <li>A relevant science degree; senior roles often ask for an MSc, MEng or PhD</li>
    <li>Laboratory technique, accurate documentation and comfort inside a regulated, audited process</li>
    <li>Availability for the shift pattern in the posting; some quality control roles run rotating day, afternoon and night shifts including weekends</li>
    <li>Australian work rights, or an occupation that can be sponsored under the Core Skills Occupation List</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay rates come from CSL's enterprise agreement registered with the Fair Work Commission, and visa eligibility is set by the Department of Home Affairs &mdash; not by JobGader. CSL publishes no recruitment-fraud page, so verify every listing on jobs.csl.com. Applying is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>A CSL laboratory job in Australia genuinely can be sponsored. But whether it can turns on a five-digit occupation code, and the code most guides quote is the wrong one.</strong></p>

<p>There is a second number that matters just as much, and nobody publishes it: CSL's own pay scale starts at <strong>AUD 77,276</strong>, while the salary floor for sponsoring a foreign worker is <strong>AUD 79,423</strong>. Those two figures cross exactly one classification above entry level, and that tells you more about your real chances than any amount of CV advice.</p>

<p>Everything below was checked on <strong>23 September 2026</strong> against CSL's live board, its registered enterprise agreement and the Department of Home Affairs occupation lists.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://jobs.csl.com/en/jobs" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#00a0af;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129514; Search CSL Jobs &rarr;
    </a>
</div>

<h2>The Occupation Code Decides Everything</h2>

<p>Australia sponsors occupations, not people and not employers. So before anything else, find out which code your work falls under, because the answer changes completely between two codes that sound almost identical.</p>

<p><strong>First, a correction that could cost you months.</strong> ANZSCO <strong>234513 is Biochemist</strong>. It is not Medical Laboratory Scientist, which is <strong>234611</strong>. Guides confuse these constantly, and they sit on opposite sides of the line that matters.</p>

<p><strong>On the Core Skills Occupation List, so sponsorable on a subclass 482:</strong></p>

<ul>
    <li>234513 Biochemist</li>
    <li>234511 Life Scientist (General)</li>
    <li>234599 Life Scientists not elsewhere classified</li>
    <li>234211 Chemist</li>
    <li>234612 Respiratory Scientist</li>
    <li>139913 Laboratory Manager and 139916 Quality Assurance Manager</li>
    <li>233914 Engineering Technologist (assessed by Engineers Australia; the rest by VETASSESS)</li>
    <li>311411 Chemistry Technician, 311299 Medical Technicians nec, 311499 Science Technicians nec</li>
</ul>

<p><strong>Not on the Core Skills list, so cannot be nominated for a 482 under that title:</strong> 234611 Medical Laboratory Scientist, 234517 Microbiologist, 234514 Biotechnologist, 311413 Life Science Technician, 311213 Medical Laboratory Technician.</p>

<p>Read that carefully, because it is not as bad as it looks. <strong>Medical Laboratory Scientist is still on the MLTSSL</strong>, which means it does not work for employer sponsorship but it does work for <strong>points-tested permanent residence</strong> through subclasses 189, 190 and 491. Same qualification, different door. Knowing which door your code opens is the single most useful thing on this page.</p>

<p>One technical trap: the 482 and 186 visas use the ANZSCO 2022 edition while every other skilled visa uses ANZSCO 2013, so <strong>the same number can mean a different occupation depending on the visa</strong>. And ignore OSCA codes entirely &mdash; the statistics agency has moved to them, but Home Affairs has not.</p>

<h2>What Sponsorship Actually Requires</h2>

<p>The settings for the financial year running to 30 June 2027:</p>

<ul>
    <li><strong>Core Skills Income Threshold: AUD 79,423.</strong> Your employer must pay at least this <em>and</em> the annual market salary rate for the role.</li>
    <li>Specialist Skills Income Threshold: AUD 146,576.</li>
    <li><strong>Experience: one year</strong> in the occupation or a related field, gained in the last five years, and it need not be continuous. It may be gained during a Masters or PhD. The old two-year rule is gone, and guides still print it.</li>
    <li>Stay of up to four years, with a permanent residence pathway.</li>
    <li>Visa application charge AUD 4,015.</li>
    <li>English from 13 September 2025: IELTS 5.0 in <em>each</em> component with no overall band requirement. Exempt if guaranteed earnings are at least AUD 96,400.</li>
    <li><strong>A skills assessment is not mandatory for a 482</strong> in these occupations &mdash; the mandatory list is trades only. It <em>is</em> mandatory for 189, 190, 491, the 485 vocational stream and 186 Direct Entry.</li>
</ul>

<p><strong>One thing you cannot check, and should not be told otherwise:</strong> Australia publishes no public register of approved sponsors. You cannot verify that CSL is one; you have to ask. What does exist is the opposite list &mdash; the Register of Sanctioned Sponsors &mdash; and CSL, CSL Behring and CSL Seqirus do not appear on it.</p>

<p>Also worth knowing: we read the full text of all 28 of CSL's Australian postings. <strong>Not one mentions sponsorship, visas, work rights or citizenship</strong> in any direction. Any guide quoting CSL's "sponsorship policy" has invented it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-csl-laboratory-jobs-in-australia-bench.jpg" alt="A laboratory scientist working at a bench in a biotechnology facility" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Twelve of CSL's 28 Australian vacancies are in quality or research and development.</figcaption>
</figure>

<h2>What CSL Actually Pays</h2>

<p>CSL publishes no salary on its Australian job adverts. But it does not need to, because <strong>its pay scale is a public document</strong> &mdash; and almost nobody writing about these jobs has looked it up.</p>

<p>The <strong>CSL Agreement 2025</strong> was approved by the Fair Work Commission on 16 July 2026, operates from 23 July 2026 and runs to a nominal expiry of 31 August 2028. Its coverage clause names the workplaces: Bio21 and 45 Poplar Road in Parkville, 189&ndash;209 Camp Road in Broadmeadows, and 655 Elizabeth Street in Melbourne &mdash; and it expressly covers staff engaged in <strong>CSL Behring or CSL Research Development &amp; Sciences</strong>. That is exactly the laboratory population.</p>

<p>The published annual base rates, from 1 September 2026 and from 1 September 2027:</p>

<ul>
    <li><strong>CSL 1.1 &mdash; AUD 77,276</strong>, rising to AUD 79,787</li>
    <li><strong>CSL 1.2 &mdash; AUD 80,093</strong>, rising to AUD 82,696</li>
    <li>CSL 1.3 &mdash; AUD 82,909, rising to AUD 85,604</li>
    <li>CSL 2.1 &mdash; AUD 84,348. CSL 2.2 &mdash; AUD 87,410. CSL 2.3 &mdash; AUD 91,215</li>
    <li>CSL 3.1 &mdash; AUD 95,257. CSL 3.4 &mdash; AUD 106,394</li>
    <li>CSL 4 &mdash; AUD 112,202. CSL 5 &mdash; AUD 134,765</li>
</ul>

<p><strong>An important caveat, in the agreement's own words:</strong> pay increases "will be applied to the employee's actual base salary rate, not necessarily to the Salary rate provided below." These are floors, not what people are actually on. CSL Seqirus staff sit on a separate agreement with a very similar scale, starting at AUD 77,476.</p>

<h2>Where the Pay Scale Meets the Visa Floor</h2>

<p>Now put the two numbers side by side, because this is the part that decides whether an application from overseas is realistic.</p>

<ul>
    <li>Sponsorship floor: <strong>AUD 79,423</strong></li>
    <li>CSL's entry classification, CSL 1.1: <strong>AUD 77,276</strong> &mdash; <strong>below it</strong></li>
    <li>The next classification, CSL 1.2: <strong>AUD 80,093</strong> &mdash; <strong>above it</strong></li>
</ul>

<p><strong>So CSL cannot realistically sponsor an overseas hire into its entry-level classification.</strong> Sponsorship starts at about CSL 1.2 or 1.3. That is not a rule anyone publishes; it falls out of putting the enterprise agreement next to the income threshold. The practical consequence: if you are applying from Pakistan, target roles that sit a step above graduate level, and be able to show why you belong there.</p>

<p>For a benchmark outside CSL, the <strong>Professional Employees Award</strong> sets the legal floor for degree-qualified scientists at <strong>AUD 66,825</strong> a year for a graduate with a three-year degree, rising through AUD 78,836 at Level 2 to AUD 117,064 at Level 5. CSL's entry rate is therefore roughly <strong>AUD 10,450 above the award minimum</strong>. The Pharmaceutical Industry Award also covers CSL but contains no laboratory or scientist classification at all, so it is not a scientist's floor.</p>

<p>Wider context: the national minimum wage from 1 July 2026 is AUD 1,004.90 a week, or AUD 26.44 an hour, and award rates rose 4.75 per cent. Superannuation is 12 per cent and, since 1 July 2026, must be paid every payday rather than quarterly. Australian Bureau of Statistics figures put median weekly earnings at AUD 1,436, with professionals on the second-highest hourly rate of any occupation group at AUD 64.50.</p>

<h2>What Is Actually on the Board</h2>

<p>CSL had <strong>28 live vacancies in Australia</strong> when we checked: <strong>CSL Behring 17, CSL Seqirus 11</strong>. CSL Vifor and CSL Plasma had none &mdash; and CSL Plasma has no Australian collection network at all, so do not expect its roles here.</p>

<p>By location: <strong>Melbourne 18, Broadmeadows 6, Tullamarine 3, Woodend 1.</strong> Twelve of the 28 sit in quality or research and development, and eight are genuinely hands-on laboratory work.</p>

<p>Real titles on the board, so you know what to search for: QC Analyst in Woodend; Senior Quality Associate for Preclinical and Laboratory Quality in Melbourne; Senior Scientist Digital Quality Control in Broadmeadows; Principal Scientist Cell Culture Development in Melbourne; Principal Clinical Scientist in Melbourne; Senior Validation Specialist in Tullamarine; Senior Director Immunology Clinical Research in Melbourne; Production Technician in Tullamarine.</p>

<p>The clearest example of what this work really is: the <strong>Woodend QC Analyst</strong> &mdash; permanent, Bachelor level, GMP assay testing and LIMS, on <strong>rotating day, afternoon and night shifts including weekends</strong>. Laboratory work in a regulated manufacturer is shift work, and the guides that describe it as a quiet bench career are not describing this job.</p>

<h2>CSL Gives You No Way to Tell a Dead Job From a Typo</h2>

<p>One of the four vacancies most guides name &mdash; a QCE Scientist Instrumentation role at Broadmeadows, described as a fixed-term contract to August 2027 &mdash; <strong>no longer exists</strong>. Searching the whole board for "QCE" returns nothing, and CSL currently has no fixed-term Australian roles at all.</p>

<p>Here is what makes CSL unusual, and worth a warning. Most employers show a message when a job closes. <strong>CSL deletes the requisition outright</strong>, and the URL returns a generic page:</p>

<p style="border-left:4px solid #00a0af;padding-left:16px;margin:20px 0;"><strong>"404 error. Page not found. Sorry, we couldn't find the page you're looking for."</strong></p>

<p>We confirmed this is generic by requesting a completely made-up requisition number and getting a byte-identical page. <strong>So you cannot tell a withdrawn CSL job from a mistyped address.</strong> Never trust a CSL job link from an article, including the dates in one; go to the search.</p>

<p>One more correction, because it wastes time: guides say CSL's platform filters by job family, entity, location and job type. <strong>jobs.csl.com has no entity filter at all</strong>, and job category and family are sort options rather than filters. CSL's Workday site does have all four facets &mdash; but it carries only <strong>15 of the 28</strong> Australian roles, because the other 13 route through a different system. Filter on Workday alone and you hide nearly half the jobs.</p>

<p>Useful for spotting fraud: CSL's genuine "Apply Now" buttons leave csl.com for either <code>csl.wd1.myworkdayjobs.com</code> or <code>olivia.paradox.ai</code>. Both are legitimate. This matters because <strong>CSL publishes no recruitment-fraud page anywhere</strong>, so there is no official reference to check a suspicious offer against. The only genuine source of listings is jobs.csl.com.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-csl-laboratory-jobs-in-australia-quality.jpg" alt="Quality control staff reviewing laboratory documentation" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Regulated laboratory work is as much documentation as it is bench technique.</figcaption>
</figure>

<h2>The Graduate Program Is Closed to You</h2>

<p>CSL runs a two-year Australian Graduate Program, and guides recommend it to international readers. They should not. The selection page states the requirement plainly:</p>

<p style="border-left:4px solid #00a0af;padding-left:16px;margin:20px 0;"><strong>"You will need to be an Australian or New Zealand Citizen or have Australian Permanent Residency status at the time of application."</strong></p>

<p>That closes it to offshore applicants and to international students on a Temporary Graduate visa. The other conditions: a degree completed between 2024 and 2026 or due by January 2027, availability to start in February 2027, and Melbourne-based work with relocation and assessment-centre attendance at your own cost.</p>

<p>Two more things worth knowing if you do qualify. <strong>There is no science, research or laboratory stream.</strong> The six streams are Engineering, Manufacturing, Quality, Finance, Information &amp; Technology and Automation &mdash; the laboratory-adjacent one is <strong>Quality</strong>, which promises experience "in a laboratory setting" and prefers microbiology, immunology, pharmaceutical science, chemistry, biotechnology or chemical, process and biomedical engineering.</p>

<p>And CSL's own pages contradict each other on dates: the banner says expressions of interest are open for the <strong>2028</strong> programme while the selection page still lists a June 2026 application window and an August 2026 assessment centre, both long past. Treat the 2028 intake as the live one.</p>

<h2>Where to Look, and Where Not To</h2>

<p><strong>Broadmeadows is the growth site.</strong> It is CSL Behring's new plasma fractionation facility, the largest in the Southern Hemisphere at around AUD 900 million, and ISPE's 2025 Facility of the Year. Six live vacancies.</p>

<p><strong>Parkville needs care.</strong> CSL still employs research staff there &mdash; the July 2026 enterprise agreement names both Parkville sites as covered workplaces &mdash; but influenza manufacturing has moved to <strong>Tullamarine</strong>, and there are currently <strong>no Parkville vacancies</strong>. CSL's own Seqirus website still describes Parkville as an active manufacturing site, which is behind its operations. Do not build a job search around it.</p>

<p>Two pieces of honest context the guides leave out. First, <strong>CSL announced an intention in August 2025 to demerge CSL Seqirus</strong> as a separately listed company, then dropped the target date at its October 2025 annual meeting; its latest results do not mention it. Seqirus is still part of CSL, and anyone telling you it is being spun off this year is wrong.</p>

<p>Second, <strong>CSL is mid-restructure.</strong> Its financial year to June 2026 showed revenue of USD 15.8 billion but a reported net loss of USD 2.6 billion after restructuring costs and impairments, and the company currently has an interim chief executive. None of that stops it hiring &mdash; 28 live roles say otherwise &mdash; but you should know it before you move continents.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Establish your occupation code first.</strong> If your work maps to Biochemist, Life Scientist, Chemist, Chemistry Technician or a laboratory or QA manager title, sponsorship is on the table. If it maps to Medical Laboratory Scientist or Microbiologist, aim at points-tested permanent residence instead.</li>
    <li><strong>Get the skills assessment for the route you are on.</strong> Not needed for a 482, but mandatory for 189, 190, 491 and 186 Direct Entry. VETASSESS handles most of these codes; Engineers Australia handles Engineering Technologist.</li>
    <li><strong>Search jobs.csl.com</strong>, not Workday alone. Use scientist, quality control, quality assurance, analytical, validation, cell culture and clinical science rather than the word "laboratory".</li>
    <li><strong>Aim one classification above entry.</strong> The arithmetic above means a graduate-level role cannot carry a sponsored salary. Lead your CV with the experience that puts you at CSL 1.2 or higher.</li>
    <li><strong>Read the shift pattern before the title.</strong> Quality control roles can run rotating nights and weekends, and that is a life decision, not a detail.</li>
    <li><strong>Write evidence, not adjectives.</strong> Name the techniques, the instruments, the standards you worked to &mdash; GMP, GLP, GCLP, ISO/IEC 17025 &mdash; and what you personally did. Do not claim a standard you have only read about; regulated employers test this in interview.</li>
</ol>

<p>For other routes into Australia, our guides to <a href="/blog/visa-sponsorship-jobs-in-australia">visa sponsorship jobs in Australia</a> and <a href="/blog/how-foreign-workers-can-get-a-job-in-australia">how foreign workers can get a job in Australia</a> cover the occupation lists in full, and <a href="/blog/how-to-apply-for-woolworths-supermarket-jobs-in-australia">Woolworths supermarket jobs in Australia</a> shows what the same visa rules look like for an occupation that is on no list at all.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can CSL sponsor a visa for a laboratory job in Australia?</h3>
<p>It depends entirely on the occupation code. Biochemist, Life Scientist, Chemist, Respiratory Scientist, Laboratory Manager, Quality Assurance Manager and several technician codes are on the Core Skills Occupation List and can be nominated for a subclass 482. Medical Laboratory Scientist, Microbiologist and Biotechnologist are not on that list, although they remain on the MLTSSL for points-tested permanent residence.</p>

<h3>Is ANZSCO 234513 Medical Laboratory Scientist?</h3>
<p>No. 234513 is Biochemist. Medical Laboratory Scientist is 234611. The distinction matters because Biochemist is on the Core Skills list and can be sponsored, while Medical Laboratory Scientist is not. Guides confuse the two constantly.</p>

<h3>How much does CSL pay laboratory staff in Australia?</h3>
<p>CSL publishes no salary on its job adverts, but its enterprise agreement does. The scale starts at AUD 77,276 at classification CSL 1.1 from September 2026, rising through AUD 80,093 at CSL 1.2 and AUD 95,257 at CSL 3.1 to AUD 134,765 at CSL 5. The agreement notes these are floors rather than actual salaries.</p>

<h3>Why can CSL not sponsor at entry level?</h3>
<p>Because of where two numbers fall. The Core Skills Income Threshold is AUD 79,423, and CSL's entry classification pays AUD 77,276 &mdash; just under it. The next classification up pays AUD 80,093, just over. So a sponsored role realistically starts at about CSL 1.2 or 1.3 rather than at graduate level.</p>

<h3>How many CSL jobs are open in Australia?</h3>
<p>Twenty-eight when we checked: 17 at CSL Behring and 11 at CSL Seqirus, with none at CSL Vifor or CSL Plasma. By location, 18 in Melbourne, 6 in Broadmeadows, 3 in Tullamarine and 1 in Woodend, with none in Parkville or Bendigo. Twelve are in quality or research and development.</p>

<h3>Can international students apply for the CSL Graduate Program?</h3>
<p>No. CSL requires Australian or New Zealand citizenship, or Australian permanent residency, at the time of application. That excludes offshore applicants and international students on a Temporary Graduate visa. There is also no dedicated science stream; the laboratory-adjacent one is Quality.</p>

<h3>Why do CSL job links in other articles not work?</h3>
<p>Because CSL deletes closed requisitions rather than marking them closed. The URL then returns a generic "404 error. Page not found." that is identical to what a mistyped address returns, so you cannot tell a withdrawn job from a typo. Always search from jobs.csl.com.</p>

<h3>Does CSL still hire at Parkville?</h3>
<p>CSL still employs research staff at Parkville &mdash; its current enterprise agreement names both Parkville sites as covered workplaces &mdash; but influenza manufacturing has transferred to Tullamarine and there are no Parkville vacancies at present. Seqirus hiring is now at Tullamarine and Woodend.</p>

<h2>People Also Search For</h2>

<ul>
    <li>CSL Behring salary Australia enterprise agreement</li>
    <li>ANZSCO 234513 Biochemist skills assessment</li>
    <li>Core Skills Occupation List laboratory jobs</li>
    <li>CSL graduate program eligibility 2028</li>
    <li>QC analyst jobs Melbourne shift work</li>
    <li>Core Skills Income Threshold 2026 2027</li>
    <li>CSL Broadmeadows plasma facility jobs</li>
    <li>Medical Laboratory Scientist Australia PR pathway</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a></li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a></li>
    <li><a href="/blog/how-to-apply-for-woolworths-supermarket-jobs-in-australia">How to Apply for Woolworths Supermarket Jobs in Australia</a></li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a></li>
    <li><a href="/blog/medical-assistant-jobs-in-usa">Medical Assistant Jobs in USA</a></li>
    <li><a href="/blog/entry-level-healthcare-jobs">Entry Level Healthcare Jobs</a></li>
</ul>
HTML;
    }
}
