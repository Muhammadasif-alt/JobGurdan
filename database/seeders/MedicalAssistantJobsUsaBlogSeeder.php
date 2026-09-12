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
 * "Medical Assistant Jobs in USA" — the fastest accessible route into American
 * healthcare, and an occupation whose published pay range stops below its own
 * median.
 *
 * Corrections to the draft:
 *
 * 1. It gives "$35,000 to $45,000 per year, or roughly $17 to $22 per hour".
 *    The median is $45,690, so the top of that range sits below the middle of
 *    the occupation and more than half of all medical assistants earn above
 *    the figure presented as their ceiling. The measured spread runs from
 *    $36,050 at the 10th percentile to $59,310 at the 90th.
 *
 * 2. It says those in hospitals and specialty practices earn most. Outpatient
 *    care centres lead on pay at a $53,130 mean; hospitals follow at $47,660;
 *    and physicians' offices, which employ 55.3 per cent of all medical
 *    assistants, pay least of the three at $45,060.
 *
 * 3. It describes growth only as "one of the fastest-growing healthcare
 *    occupations" without the number. It is 13 per cent from 2025 to 2035
 *    against 3.5 per cent for all occupations, adding 107,600 jobs with about
 *    109,700 openings a year. The draft undersells a genuinely strong figure.
 *
 * 4. It presents the role as a stepping stone to nursing without pricing the
 *    step. Licensed practical nurses earn a $64,400 median, 41 per cent more,
 *    and registered nurses $97,550, 113 per cent more.
 *
 * 5. It treats phlebotomy and medical secretarial work as lesser roles. Both
 *    sit level with medical assisting on pay: $45,230 and $45,930.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MedicalAssistantJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-medical-assistant-jobs.html';

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
        $title = 'Medical Assistant Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The pay range every guide quotes stops below the median: half of all medical assistants earn more than the figure presented as their ceiling. The real spread is $36,050 to $59,310, and the best-paying setting is not the hospital.',
                'content' => $content,
                'featured_image' => 'blogs/medical-assistant-jobs-in-usa.jpg',
                'tags' => 'medical assistant jobs usa, certified medical assistant, cma certification, medical assistant salary, clinical medical assistant jobs, entry level healthcare jobs, medical assistant training, healthcare jobs no degree',
                'meta_title' => 'Medical Assistant Jobs in USA: Pay and Job Outlook',
                'meta_description' => 'Medical assistant jobs in the USA: the $45,690 median BLS measures, why outpatient clinics outpay hospitals, and the 13% growth behind the hiring.',
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
            ['name' => 'US Clinics, Physician Offices & Hospital Networks (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-medical-assistant-aggregated']
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
                'position' => 'Medical Assistant — Clinical and Administrative, US Clinics and Physician Offices',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Clinic hours, with some evening, weekend and urgent care rotas',
                'language' => 'English',
                // Pay runs from $36,050 to $59,310 and turns on the setting and
                // the state, so a single advertised band would misdescribe it.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Clinical and administrative medical assistant roles with US clinics, physician offices and hospitals. Check the setting: outpatient centres pay most.',
                'seo_keywords' => 'medical assistant jobs usa, certified medical assistant jobs, clinical medical assistant, medical assistant salary, entry level healthcare jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Physician offices, outpatient clinics, hospitals, urgent care centres and specialty practices across the United States employ 817,870 medical assistants, and the occupation is projected to add 107,600 more jobs by 2035. It is the fastest accessible route into clinical healthcare work, requiring a postsecondary certificate rather than a degree.</p>

<h3>What the work involves</h3>
<p>Taking vital signs, preparing patients and rooms for examinations, assisting during procedures, drawing blood and collecting specimens, administering injections and medications under supervision where state law allows, and maintaining electronic health records. Most roles also carry front-office duties: scheduling, check-in and check-out, insurance verification and supply ordering.</p>

<h3>Requirements</h3>
<ul>
    <li>A postsecondary nondegree award &mdash; a certificate or diploma from a medical assisting programme &mdash; is the typical entry-level education BLS records</li>
    <li>No prior work experience, and no formal on-the-job training period</li>
    <li>Certification is preferred by most employers and required by some, and the route to it depends on the programme you attended</li>
    <li>Current CPR or Basic Life Support certification, commonly required before a start date</li>
    <li>Electronic health record experience, named by system where you have it</li>
    <li>Awareness of what your state does and does not allow a medical assistant to do</li>
</ul>

<h3>What it pays</h3>
<ul>
    <li><strong>Median $45,690 a year, or $21.97 an hour</strong>, with the middle half between <strong>$38,470 and $49,180</strong> (BLS, May 2025)</li>
    <li><strong>The 90th percentile is $59,310</strong>, or $28.52 an hour &mdash; well above the range most careers pages quote</li>
    <li><strong>Outpatient care centres pay most</strong> at a $53,130 mean, ahead of hospitals at $47,660 and physicians' offices at $45,060</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Compare the setting, not just the hourly rate.</strong> The same job pays around $8,000 a year more in an outpatient care centre than in the physician office where most medical assistants work.</p>

<p><strong>Note:</strong> pay, certification requirements, scope of practice and eligibility are set by each employer and by state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Medical assistant is the quickest legitimate way into clinical healthcare work in the United States: no degree, no prior experience, and a certificate that takes months rather than years. It is also an occupation whose published salary range has drifted so far out of date that the top of it now sits below the middle of the profession. This page uses the measured figures and spends its length on the three things that actually change your pay: the setting you work in, the state you work in, and the step you take next.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-medical-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#129658; Browse Medical Assistant Jobs in the USA &rarr;
    </a>
</div>

<h2>The Published Range Stops Below the Median</h2>

<p>The figure in general circulation is <strong>"$35,000 to $45,000 a year, or roughly $17 to $22 an hour"</strong>. Here is what the Bureau of Labor Statistics measured across <strong>817,870 medical assistants in May 2025</strong>:</p>

<ul>
    <li><strong>10th percentile &mdash; $36,050</strong> a year, $17.33 an hour</li>
    <li><strong>25th percentile &mdash; $38,470</strong> a year, $18.50 an hour</li>
    <li><strong>Median &mdash; $45,690</strong> a year, <strong>$21.97</strong> an hour</li>
    <li><strong>75th percentile &mdash; $49,180</strong> a year, $23.65 an hour</li>
    <li><strong>90th percentile &mdash; $59,310</strong> a year, <strong>$28.52</strong> an hour</li>
</ul>

<p>Read the median again against the quoted range. <strong>$45,690 is above the $45,000 presented as the top of the scale</strong>, which means <strong>more than half of all medical assistants earn more than the figure most guides give as their ceiling</strong>. The published range describes roughly the bottom half of the occupation and omits the rest, including a top tenth clearing <strong>$59,310</strong>.</p>

<p>If you are negotiating a first offer, the number to anchor on is <strong>$45,690</strong>, and the number to know is <strong>$49,180</strong> &mdash; the point above which a quarter of the occupation already sits.</p>

<h2>The Setting Pays More Than the Certificate Does</h2>

<p>Most guides say hospitals and specialty practices pay best. The measured order is different, and it matters because the highest-paying setting is not the one where most medical assistants work.</p>

<ul>
    <li><strong>Outpatient care centres &mdash; $53,130 mean</strong>, and 85,840 jobs. The best-paying setting of any real size.</li>
    <li><strong>Specialty hospitals &mdash; $49,720 mean</strong>, but only 4,630 jobs nationally.</li>
    <li><strong>General medical and surgical hospitals &mdash; $47,660 mean</strong>, and 133,400 jobs.</li>
    <li><strong>Offices of physicians &mdash; $45,060 mean</strong>, and <strong>452,150 jobs</strong>.</li>
    <li><strong>Offices of other health practitioners &mdash; $40,600 mean</strong>, the low end at 63,000 jobs.</li>
</ul>

<p>Now put those side by side with where people actually work. <strong>55.3 per cent of all medical assistants are in physicians' offices</strong>, 16.3 per cent in hospitals and 10.5 per cent in outpatient care centres. So the majority of the occupation is employed in its lowest-paying major setting, and the highest-paying one holds barely a tenth of the jobs.</p>

<p>That is the single most actionable fact on this page. <strong>Moving from a physician office to an outpatient care centre is worth about $8,000 a year</strong> on the mean, with the same certificate and the same skills. Search on "outpatient", "ambulatory" and "surgery centre" as well as on clinic and practice names.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/medical-assistant-jobs-in-usa-patient-care.jpg"
         alt="A medical assistant in blue scrubs with a stethoscope and clipboard in a US clinic, with a colleague attending a patient behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Not Every Certification Is Open to You</h2>

<p>Guides list CMA, RMA and NCMA as though they were interchangeable badges you collect after the fact. They are not. <strong>Which exam you may sit is decided by the programme you attended, and one of them closes the door on work experience entirely.</strong></p>

<ul>
    <li><strong>CMA (AAMA)</strong> &mdash; the most recognised of the four, and the strictest. <strong>There is no work-experience route.</strong> Every eligibility category requires completion of a medical assisting programme accredited by <strong>CAAHEP</strong> or <strong>ABHES</strong>. Even the so-called Alternative Pathway requires a completed postsecondary programme of at least <strong>560 academic contact hours plus a 160-hour practicum</strong>, with at least ten documented injections and ten phlebotomies. Recertification falls due every <strong>five years</strong>.</li>
    <li><strong>RMA (AMT)</strong> &mdash; has a genuine work-experience route: <strong>three years</strong> of medical assisting employment within the past seven, covering clinical and administrative duties, plus a diploma or GED and a current CPR card. The fee is <strong>$150</strong>.</li>
    <li><strong>NCMA (NCCT)</strong> &mdash; also accepts experience: <strong>one year</strong> of full-time verifiable US medical assisting within the past five. The fee is <strong>$119</strong>.</li>
    <li><strong>CCMA (NHA)</strong> &mdash; omitted from most guides despite being one of the most common. Accepts either a training programme completed in the last five years <strong>or one year of clinical medical assisting experience</strong> in the last three. Renewal is every <strong>two years with ten continuing education credits</strong>.</li>
</ul>

<p>So the practical order is the reverse of how guides present it. <strong>If you are already working as a medical assistant without having completed an accredited programme, CMA (AAMA) is not available to you, but RMA, NCMA and CCMA are.</strong> If you have not started yet and want the widest choice, <strong>check that the programme is CAAHEP- or ABHES-accredited before you enrol</strong> &mdash; that single check decides which exams you can ever sit. ABHES accredits only three programme types, medical assistant among them; CAAHEP accredits across 31 professions through its medical assisting review board.</p>

<p>One naming trap worth knowing: when a job advertisement asks for <strong>BLS</strong>, it means <strong>Basic Life Support</strong> certification from the American Heart Association or the Red Cross &mdash; not the Bureau of Labor Statistics, whose initials appear all over this page for a completely different reason.</p>

<h2>What Your State Actually Lets You Do</h2>

<p>"Some states have additional regulations" is the line every guide uses, and it badly understates the position. Start from the structural fact: <strong>no state licenses medical assistants the way it licenses nurses.</strong> You work under <strong>delegation</strong> from a licensed practitioner, which means your legal authority comes from your supervisor rather than from a licence of your own. Then the states diverge sharply.</p>

<ul>
    <li><strong>Washington is the outlier: a state credential is mandatory before clinical work.</strong> The Department of Health issues five categories &mdash; medical assistant-certified, -registered, -phlebotomist, -hemodialysis technician and -EMT. The certified category requires an accredited programme and a passing national exam, and permits injections, vaccines, IV line placement under immediate supervision and venous draws. The registered category needs no exam but is far narrower: blood <strong>only by finger or heel stick</strong>, and no IVs or phlebotomy.</li>
    <li><strong>New Jersey requires national certification before you may inject or take blood at all.</strong> A physician may delegate intradermal, intramuscular and subcutaneous injections and venipuncture <strong>only to a certified medical assistant</strong>, from a programme of at least 330 clock hours, and the physician must stay on the premises within reasonable proximity to the treatment room.</li>
    <li><strong>California permits injections and blood draws but draws a hard line at judgement.</strong> An unlicensed person may not diagnose, treat, or perform any task that is <strong>invasive or requires assessment</strong>. In practice that bars independent telephone triage, starting or disconnecting IV infusions, urinary catheters, and even <strong>interpreting a skin test result</strong> &mdash; you may measure and record it, not read it. The supervising practitioner must be <strong>physically present in the facility</strong>.</li>
    <li><strong>Arizona requires documented training before employment</strong>: an approved programme, or an unapproved one plus an exam from a certifying body accredited by the NCCA or ANSI. Injections need the supervisor present.</li>
    <li><strong>Maryland tiers supervision by the act.</strong> Injections and establishing a peripheral IV line need on-site supervision; injecting a drug into an IV line needs direct supervision; phlebotomy and specimen collection need neither.</li>
</ul>

<p>Two consequences worth carrying into an interview. <strong>The same certificate buys different work in different states</strong>, so confirm what you are allowed to do before you accept a clinical role. And going beyond your delegation is not a disciplinary matter but <strong>unlicensed practice of medicine</strong>, which falls on you as well as on the practice.</p>

<p>On visas, the honest position for international readers: <strong>medical assistant does not meet the H-1B specialty occupation standard</strong>, which requires a bachelor's degree in a specific specialty as the normal minimum for entry. This occupation's documented entry requirement is a postsecondary certificate, so realistic H-1B sponsorship is not available for it.</p>

<h2>Thirteen Per Cent, Against 3.5 for Everything Else</h2>

<p>Guides call this "one of the fastest-growing healthcare occupations" and leave it there. The number is stronger than the phrase:</p>

<ul>
    <li><strong>Projected growth 2025 to 2035: 13 per cent</strong>, which BLS labels "much faster than the average".</li>
    <li><strong>The average for all occupations over the same decade is 3.5 per cent.</strong> Medical assisting is growing at roughly <strong>3.7 times</strong> that rate.</li>
    <li><strong>Net change: 107,600 additional jobs</strong>, on a base of 833,900.</li>
    <li><strong>Openings: about 109,700 a year</strong>, combining growth with replacement.</li>
</ul>

<p>This is the rare case where the careers-page enthusiasm understates the data rather than inflating it. Unlike most entry-level occupations, the openings here are not purely churn: roughly one in ten of them is a genuinely new post.</p>

<h2>Where the Pay Is Highest</h2>

<p>By median annual wage the leading states are <strong>Washington at $59,290</strong> &mdash; on its own more than the national 90th percentile &mdash; then <strong>Alaska at $52,560</strong>, the <strong>District of Columbia at $51,050</strong>, <strong>Minnesota at $50,480</strong>, <strong>Oregon at $50,410</strong> and <strong>California at $49,660</strong>. The lowest are <strong>Mississippi at $35,360</strong>, <strong>Alabama at $36,100</strong> and <strong>Louisiana at $36,320</strong>.</p>

<p>The metropolitan spread is wider still. <strong>Vallejo, California reaches a $77,410 median</strong>, with Santa Rosa-Petaluma at $64,820, Napa at $62,050, Sacramento at $61,970, San Jose at $61,940 and Seattle at $61,500.</p>

<p>A medical assistant on the median in Vallejo earns more than twice one on the median in Mississippi. Before concluding anything from that, price the rent &mdash; but if you are already choosing between states, this is the gap you are choosing between.</p>

<p>California employs the most medical assistants at 117,060, then Texas at 75,340, Florida at 66,110, New York at 40,710 and Ohio at 28,950.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/medical-assistant-jobs-in-usa-clinic.jpg"
         alt="A medical assistant at a US clinic reception desk with an examination room and monitoring equipment behind her"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What the Next Step Is Actually Worth</h2>

<p>Every guide calls medical assisting a launching pad. Few say what the launch pays, and the figures make the case better than the adjective does:</p>

<ul>
    <li><strong>Licensed practical and vocational nurses &mdash; $64,400 median.</strong> That is <strong>$18,710</strong> more, a <strong>41 per cent</strong> rise, and the nearest step.</li>
    <li><strong>Registered nurses &mdash; $97,550 median.</strong> <strong>$51,860</strong> more, a <strong>113 per cent</strong> rise.</li>
</ul>

<p>Two comparisons in the other direction are worth knowing before you assume medical assisting sits above the neighbouring roles. <strong>Phlebotomists earn $45,230</strong> and <strong>medical secretaries and administrative assistants $45,930</strong> &mdash; both effectively level with medical assistants rather than below them. If you are weighing a phlebotomy certificate against a medical assisting one, pay is not the deciding factor; scope of work and where each leads is.</p>

<p>The practical route most people take is to use an employer's tuition assistance while working as a medical assistant, then qualify as an LPN or RN. On these numbers that is the difference between $45,690 and $97,550 for the same working week.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do medical assistants earn in the USA?</h3>
<p>The median is $45,690 a year, or $21.97 an hour. The middle half earn between $38,470 and $49,180, the bottom tenth below $36,050 and the top tenth above $59,310 (BLS, May 2025).</p>

<h3>Is $35,000 to $45,000 an accurate medical assistant salary?</h3>
<p>No. The top of that range sits below the national median of $45,690, so more than half of all medical assistants earn above the figure it presents as a maximum. The measured spread runs to $59,310 at the 90th percentile.</p>

<h3>Which setting pays medical assistants the most?</h3>
<p>Outpatient care centres, at a $53,130 mean, ahead of hospitals at $47,660 and physicians' offices at $45,060. That is about $8,000 a year between the highest and the setting where most medical assistants actually work.</p>

<h3>Can you get certified as a medical assistant with work experience instead of a programme?</h3>
<p>For some certifications, not all. RMA (AMT) accepts three years of employment, NCMA (NCCT) one year, and CCMA (NHA) one year within the last three. CMA (AAMA) has no work-experience route at all &mdash; every route requires a CAAHEP- or ABHES-accredited programme.</p>

<h3>Is medical assistant a fast-growing job?</h3>
<p>Yes, genuinely. Employment is projected to grow 13 per cent from 2025 to 2035 against 3.5 per cent for all occupations, adding 107,600 jobs and producing about 109,700 openings a year.</p>

<h3>Do you need a degree to become a medical assistant?</h3>
<p>No. BLS records the typical entry-level education as a postsecondary nondegree award &mdash; a certificate or diploma &mdash; with no prior work experience and no formal on-the-job training period required.</p>

<h3>How much more do LPNs and RNs earn than medical assistants?</h3>
<p>Licensed practical nurses earn a $64,400 median, 41 per cent more, and registered nurses $97,550, 113 per cent more. Both are the usual next step, often funded through an employer's tuition assistance.</p>

<h3>Can medical assistants give injections and draw blood?</h3>
<p>In most states yes, but only as a delegated act under a licensed practitioner and with the supervision each state prescribes. Washington requires a state credential first, New Jersey requires national certification, and California bars any task that is invasive or requires assessment, including reading a skin test result.</p>

<h2>People Also Search For</h2>

<h3>Medical assistant salary</h3>
<p>A $45,690 median and $21.97 an hour, with the top tenth of the occupation above $59,310.</p>

<h3>Certified medical assistant jobs</h3>
<p>Certification is preferred by most employers and required by some. Which certification you can sit depends on the programme you attended.</p>

<h3>Medical assistant programs near me</h3>
<p>A postsecondary certificate is the documented entry route. Check the programme's accreditation before enrolling, because it decides which exams you can take.</p>

<h3>Clinical vs administrative medical assistant</h3>
<p>Most US roles combine both. Clinical duties are the ones state law limits, so the split varies by state as well as by employer.</p>

<h3>Medical assistant vs LPN</h3>
<p>An LPN earns a $64,400 median against $45,690, a 41 per cent difference, and holds a licence rather than a certificate.</p>

<h3>Highest paying states for medical assistants</h3>
<p>Washington, Alaska, the District of Columbia, Minnesota, Oregon and California. Vallejo, California leads all metro areas at $77,410.</p>

<h3>Entry level healthcare jobs no experience</h3>
<p>Medical assisting requires no prior work experience and no degree, which is why it is the most common first clinical job in the country.</p>

<h3>Medical assistant job outlook</h3>
<p>Thirteen per cent growth to 2035, 107,600 new jobs and about 109,700 openings a year &mdash; roughly 3.7 times the all-occupations rate.</p>

<h2>More Job Guides</h2>

<p>Comparing healthcare and entry-level routes? These cover them:</p>

<ul>
    <li><a href="/blog/registered-nurse-jobs-in-usa">Registered Nurse Jobs in USA</a> &mdash; the $97,550 step up, and what the licence actually takes.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the wider American nursing market and its shortage claims.</li>
    <li><a href="/blog/healthcare-assistant-jobs-in-uk">Healthcare Assistant Jobs in UK</a> &mdash; the closest British equivalent, and the visa route into it.</li>
    <li><a href="/blog/healthcare-jobs-in-the-uk">Healthcare Jobs in the UK</a> &mdash; NHS bands and how they compare with US clinic pay.</li>
    <li><a href="/blog/administrative-assistant-jobs-in-usa">Administrative Assistant Jobs in USA</a> &mdash; the front-office half of this job as a career of its own.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; the other large no-degree entry route, and what it pays.</li>
    <li><a href="/blog/nurse-jobs-in-saudi-arabia">Nurse Jobs in Saudi Arabia</a> &mdash; where American and British clinical experience is recruited from abroad.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; records work without the clinical side.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, legal or medical advice. Wage survey figures, employment projections, certification requirements and state scope-of-practice rules change. Confirm the current position with the Bureau of Labor Statistics, the certifying body, your state medical board and the employer's own advertisement before applying.</p>
HTML;
    }
}
