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
 * "Preschool Teacher Jobs in Canada" — in Canada the job is filed as an early
 * childhood educator (NOC 42202), so this guide translates the search into the
 * ECE system: the college program, provincial registration, Job Bank wages and
 * provincial wage top-ups, the employers that hire, and what internationally
 * trained educators need. The Teacher Jobs in USA guide covers school teaching,
 * and the Canada foreign worker guides cover work permits in general.
 *
 * Corrections and clarifications to the draft (checked against Canada.ca, Job
 * Bank, the ESDC Canadian Occupational Projection System, IRCC, the Ontario,
 * British Columbia, Alberta and Quebec governments, the College of Early
 * Childhood Educators and each employer's careers page, September 2026):
 *
 * 1. The draft says certification "varies" and leaves it there. Job Bank says
 *    licensing is required in Ontario and certification in British Columbia,
 *    and is usually required elsewhere; Alberta certifies at Levels 1, 2 and 3.
 *
 * 2. The draft quotes no pay. Job Bank lists a Canada median of $22.30 an
 *    hour, and Ontario's 2026 wage floor for RECEs in the Canada-Wide Early
 *    Learning and Child Care system is $25.86 an hour, with top-ups in BC and
 *    Alberta.
 *
 * 3. The draft's YMCA of Greater Toronto careers link returns a 404. The
 *    working page is the YMCA's child care team page.
 *
 * 4. The draft describes BrightPath Kids as operating across Canada; it lists
 *    over 250 locations across the United States and Canada. PLASP operates in
 *    Peel Region and Toronto, not the whole GTA.
 *
 * 5. The draft says ECE assistants may need secondary school. Job Bank says
 *    secondary school and child care experience are required.
 *
 * 6. The draft leaves out immigration. Early childhood educators and assistants
 *    are in the Express Entry education category, and the child care home care
 *    worker pilot is closed and never counted ECE experience.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class PreschoolTeacherJobsCanadaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ca.indeed.com/q-early-childhood-educator-jobs.html';

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
        $title = 'Preschool Teacher Jobs in Canada';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'In Canada, preschool teachers are hired as early childhood educators. You need a college ECE program, registration in Ontario or certification in BC, and the Job Bank median is $22.30 an hour before provincial wage top-ups.',
                'content' => $content,
                'featured_image' => 'blogs/preschool-teacher-jobs-in-canada.jpg',
                'tags' => 'preschool teacher jobs in canada, early childhood educator jobs canada, ece jobs canada, rece ontario, ece certification bc, early childhood educator salary canada, noc 42202, ece assistant jobs, early childhood educator express entry',
                'meta_title' => 'Preschool Teacher Jobs in Canada: ECE Pay and Registration',
                'meta_description' => 'Preschool teacher jobs in Canada are ECE roles: the college program, Ontario and BC registration, $22.30 median pay and which child care employers hire.',
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
            ['name' => 'Canadian Child Care Centres, Preschools & Early Learning Providers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'ca-early-childhood-educator-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Early Childhood Educator — Preschool, Toddler and ECE Assistant Roles, Canadian Child Care Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Child care centre hours, usually early morning to early evening on weekdays, in shifts',
                'language' => 'English',
                // Wages differ by province, certification level and provincial
                // wage top-ups, so no single band is quoted on the listing.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Early childhood educator and ECE assistant roles in Canadian child care centres and preschools. Provincial registration or certification is usually required.',
                'seo_keywords' => 'early childhood educator jobs, preschool teacher jobs canada, ece jobs, rece jobs ontario, ece assistant jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Child care centres, preschools, before- and after-school programs and early learning providers across Canada hire early childhood educators and ECE assistants to care for and teach young children.</p>

<h3>What the work involves</h3>
<p>Planning play-based learning, supporting children's social and emotional development, observing and recording progress, keeping the room safe and talking with parents.</p>

<h3>Requirements</h3>
<ul>
    <li>Early childhood educators: a two- to four-year college ECE program or a bachelor's degree in child development</li>
    <li>Registration with the College of Early Childhood Educators in Ontario, ECE certification in British Columbia, and certification in most other provinces</li>
    <li>ECE assistants: secondary school and child care experience</li>
    <li>A vulnerable sector check</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> wages, certification levels and hiring requirements are set by each employer and province &mdash; not by JobGader. Confirm the details on the employer's own posting before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>In Canada, preschool teachers are hired as early childhood educators (ECEs), NOC 42202.</strong> To be an ECE you usually need a <strong>two- to four-year college program in early childhood education</strong>, and you must be <strong>registered in Ontario</strong> or <strong>certified in British Columbia</strong>, with certification usually required in the other provinces too. Job Bank lists a national median wage of <strong>$22.30 an hour</strong>, and several provinces add wage top-ups. The federal government says over 100,000 new openings are expected by 2031.</p>

<p>This guide explains the qualifications, the rules in each province, what ECEs earn, which employers hire and what internationally trained educators need to do.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://ca.indeed.com/q-early-childhood-educator-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127464;&#127462; Browse Early Childhood Educator Jobs in Canada &rarr;
    </a>
</div>

<h2>A Preschool Teacher in Canada Is an Early Childhood Educator</h2>

<p>Search for "preschool teacher" in Canada and most postings say <strong>Early Childhood Educator</strong>, <strong>Registered Early Childhood Educator (RECE)</strong> or <strong>ECE Assistant</strong>. They all fall under NOC 42202, Early childhood educators and assistants, a TEER 2 occupation. Job Bank even lists "Early Childhood Educator - Preschool" as one of its titles.</p>

<p>ECEs plan play-based learning, support children's social, emotional and language development, observe and record progress, keep children safe and talk with parents. They work in:</p>

<ul>
    <li>Licensed child care and daycare centres</li>
    <li>Preschools and nursery schools</li>
    <li>Before- and after-school programs</li>
    <li>Full-day kindergarten classrooms in Ontario, alongside teachers</li>
    <li>EarlyON and family resource centres</li>
    <li>Programs for children with extra support needs</li>
</ul>

<h2>What Qualifications You Need</h2>

<p>Job Bank sets out the requirements for NOC 42202:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">What Job Bank says is required</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Early childhood educator</strong></td><td style="padding:10px;">"Completion of a two- to four-year college program in early childhood education or a bachelor's degree in child development is required."</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Licensing</strong></td><td style="padding:10px;">"Licensing is required in Ontario and certification is required in British Columbia for early childhood educators." Licensing is usually required in the other provinces and territories.</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>ECE assistant</strong></td><td style="padding:10px;">Completion of secondary school and <strong>child care experience are required</strong>. An ECE assistant certificate or college courses may also be required.</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Job Bank, employment requirements for Early childhood educators and assistants (NOC 42202), September 2026.</p>
</div>

<h2>The Rules in Your Province</h2>

<p>Early childhood education is regulated by each province and territory, so check the rules where you plan to work:</p>

<ul>
    <li><strong>Ontario:</strong> "To work as an early childhood educator, you need to be registered with the College of Early Childhood Educators." Membership is required to use the title ECE or RECE. ECE assistants are exempt.</li>
    <li><strong>British Columbia:</strong> the ECE Registry issues <strong>ECE Assistant</strong>, <strong>ECE One Year</strong> and <strong>ECE Five Year</strong> certificates, plus Infant and Toddler Educator and Special Needs Educator. The Five Year certificate needs 500 hours of supervised work experience.</li>
    <li><strong>Alberta:</strong> educators are certified at <strong>Level 1</strong> (a 45-hour course or orientation course), <strong>Level 2</strong> (a one-year certificate) or <strong>Level 3</strong> (a two-year diploma).</li>
    <li><strong>Quebec:</strong> the main training is the DEC (DCS) in Early Childhood Education, 322.A0, or an AEC (ACS) program, and Quebec says the designation "may be required or an asset".</li>
    <li><strong>Other provinces and territories:</strong> the Government of Canada's early childhood education careers page links to the certification rules for all 13.</li>
</ul>

<p>Child care employers also screen staff. In Ontario, every new employee must give the licensee a <strong>vulnerable sector check</strong> no older than six months before starting, and a new check every five years.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/preschool-teacher-jobs-in-canada-circle-time.jpg"
         alt="A smiling early childhood educator sitting on the floor holding up a picture card of a fox to four preschool children, with a Canadian flag on the wall and the Toronto skyline through the window"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How Much Early Childhood Educators Earn</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Area</th>
            <th style="padding:10px;text-align:left;">Low</th>
            <th style="padding:10px;text-align:left;">Median</th>
            <th style="padding:10px;text-align:left;">High</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada</strong></td><td style="padding:10px;">$16.95/hr</td><td style="padding:10px;"><strong>$22.30/hr</strong></td><td style="padding:10px;">$30.03/hr</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Ontario</td><td style="padding:10px;">$17.60/hr</td><td style="padding:10px;">$22.00/hr</td><td style="padding:10px;">$31.00/hr</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">British Columbia</td><td style="padding:10px;">$19.00/hr</td><td style="padding:10px;">$24.00/hr</td><td style="padding:10px;">$30.00/hr</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Alberta</td><td style="padding:10px;">$15.60/hr</td><td style="padding:10px;">$19.00/hr</td><td style="padding:10px;">$28.85/hr</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Quebec</td><td style="padding:10px;">$18.00/hr</td><td style="padding:10px;">$24.00/hr</td><td style="padding:10px;">$30.03/hr</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Job Bank wages for Early childhood educators and assistants (NOC 42202), updated November 19, 2025, reference period 2023&ndash;2024.</p>
</div>

<p>Those survey figures are a few years old, and provinces have since raised pay through the Canada-Wide Early Learning and Child Care system and their own wage programs:</p>

<ul>
    <li><strong>Ontario:</strong> the 2026 wage floor for RECE program staff in centres in the Canada-Wide Early Learning and Child Care system is <strong>$25.86 an hour</strong>, and $26.86 for RECE supervisors.</li>
    <li><strong>British Columbia:</strong> ECEs with a valid ECE Registry certificate can receive a wage enhancement of up to <strong>$6 an hour</strong>.</li>
    <li><strong>Alberta:</strong> certified educators get a top-up of $2.64 an hour at Level 1, $5.05 at Level 2 and <strong>$8.62 at Level 3</strong>.</li>
</ul>

<h2>Is There Demand for Preschool Teachers?</h2>

<p>Yes. The Government of Canada says: "Early childhood educators and assistants are in demand throughout Canada. Over 100,000 new job openings are anticipated between now and 2031." The federal Canadian Occupational Projection System rates the occupation at <strong>strong risk of shortage</strong> for 2024&ndash;2033, with 162,400 projected openings against 159,900 job seekers.</p>

<p>Local prospects still differ. Job Bank's three-year outlook rates Ontario and Alberta as good, British Columbia and Quebec as moderate, and Nova Scotia and Prince Edward Island as very good.</p>

<h2>Employers That Hire Early Childhood Educators</h2>

<p>These child care organisations post ECE roles on their own careers pages. Each sends applications to an outside hiring system:</p>

<ul>
    <li><strong>YMCA of Greater Toronto</strong> (ymcagta.org, child care team page): calls itself "Canada's largest non-profit child care provider", with over 300 child care centres across the GTA and an ECE Bridge to Work Program for newcomers.</li>
    <li><strong>PLASP</strong> (info.plasp.com/careers): a charitable, not-for-profit organisation with more than 200 locations in Brampton, Mississauga, Caledon and Toronto.</li>
    <li><strong>Upper Canada Child Care</strong> (uppercanadachildcare.com/careers): a non-profit licensed provider with 90 centres in Toronto, York Region and Simcoe County, operating since 1983.</li>
    <li><strong>BrightPath Kids</strong> (brightpathkids.com/careers): hires ECEs, RECEs, assistant educators and Montessori staff, with over 250 locations across the United States and Canada, including Ontario, Alberta and British Columbia.</li>
    <li><strong>Kids &amp; Company</strong> (kidsandcompany.com/careers): a child care provider opening new centres across Canada.</li>
</ul>

<p>Municipal child care, school boards' before- and after-school programs and independent licensed centres hire too.</p>

<h2>If You Trained Outside Canada</h2>

<p>Internationally trained educators can work as ECEs, but the credential has to be recognised in the province first:</p>

<ul>
    <li><strong>Ontario:</strong> if your program is not a recognised Ontario ECE diploma, the College of Early Childhood Educators can assess your education individually.</li>
    <li><strong>British Columbia:</strong> you need a BCIT International Credential Evaluation Service Comprehensive Report, and the province offers fee waivers and a translation subsidy of up to $2,500.</li>
    <li><strong>Alberta:</strong> check the province's certification guide to see whether you need a language test or other documents.</li>
</ul>

<p><strong>Immigration:</strong> early childhood educators and assistants (42202) are in the <strong>Express Entry education occupations category</strong>, which needs at least 12 months of full-time work experience in the occupation within the past three years. The child care stream of the Home Care Worker Immigration Pilots is closed, and it only ever counted experience as an assistant or helper, not as an early childhood educator.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/preschool-teacher-jobs-in-canada-story-time.jpg"
         alt="An early childhood educator reading a picture book to four smiling preschool children sitting on a colourful play mat with building blocks, in a bright classroom with a Canadian flag"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Get a Preschool Teacher Job, Step by Step</h2>

<ol>
    <li><strong>Choose the province first.</strong> Registration, certification levels and wage top-ups all depend on it.</li>
    <li><strong>Get the right qualification.</strong> A college ECE diploma or certificate, or an ECE assistant course if you want to start as an assistant.</li>
    <li><strong>Register or get certified</strong> with the College of Early Childhood Educators in Ontario, the ECE Registry in British Columbia, or your province's certification office.</li>
    <li><strong>Get a vulnerable sector check</strong> and first aid and CPR training, which most centres ask for.</li>
    <li><strong>Write your resume around children.</strong> Placements, child care experience, program planning and observation skills matter most.</li>
    <li><strong>Apply on employers' own careers pages</strong> and read each posting for the role level: RECE, ECE or assistant.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is a preschool teacher called in Canada?</h3>
<p>Usually an early childhood educator (ECE), or a registered early childhood educator (RECE) in Ontario. The occupation is NOC 42202, Early childhood educators and assistants.</p>

<h3>What qualifications do I need to be a preschool teacher in Canada?</h3>
<p>Job Bank says early childhood educators need a two- to four-year college program in early childhood education or a bachelor's degree in child development. Licensing is required in Ontario and certification in British Columbia.</p>

<h3>Can I work in a preschool without an ECE diploma?</h3>
<p>Often as an ECE assistant. Job Bank says assistants need secondary school and child care experience, and an assistant certificate may be required. In Ontario, ECE assistants do not need to register with the College.</p>

<h3>How much do preschool teachers earn in Canada?</h3>
<p>Job Bank lists a median of $22.30 an hour for early childhood educators and assistants in Canada. Ontario's 2026 wage floor for RECEs in the Canada-Wide Early Learning and Child Care system is $25.86 an hour.</p>

<h3>Do I need to register as an ECE in Ontario?</h3>
<p>Yes. Ontario says you need to be registered with the College of Early Childhood Educators to work as an early childhood educator or use the ECE or RECE title.</p>

<h3>Are early childhood educators in demand in Canada?</h3>
<p>Yes. The Government of Canada expects over 100,000 new job openings between now and 2031, and federal projections rate the occupation at strong risk of shortage for 2024 to 2033.</p>

<h3>Can internationally trained teachers work as ECEs in Canada?</h3>
<p>Yes, once the province recognises the credential. Ontario's College can assess your education individually, and British Columbia requires a BCIT International Credential Evaluation Service report.</p>

<h3>Can early childhood educators immigrate to Canada through Express Entry?</h3>
<p>Early childhood educators and assistants (NOC 42202) are in the Express Entry education category, which needs at least 12 months of full-time experience in the occupation within the past three years.</p>

<h2>People Also Search For</h2>

<h3>Early childhood educator jobs Canada</h3>
<p>The job title most Canadian preschools and child care centres advertise under.</p>

<h3>RECE jobs Ontario</h3>
<p>Registered early childhood educator roles that need College of ECE membership.</p>

<h3>ECE assistant jobs</h3>
<p>Support roles needing secondary school and child care experience.</p>

<h3>ECE salary in Canada</h3>
<p>A national median of $22.30 an hour on Job Bank, with provincial top-ups.</p>

<h3>ECE certification BC</h3>
<p>ECE Assistant, One Year and Five Year certificates from the ECE Registry.</p>

<h3>Alberta ECE Level 3</h3>
<p>Certification that needs a two-year diploma and carries an $8.62 hourly top-up.</p>

<h3>NOC 42202</h3>
<p>Early childhood educators and assistants, a TEER 2 occupation.</p>

<h3>Daycare jobs in Canada for foreigners</h3>
<p>Possible once your credential is recognised and you hold a valid work permit.</p>

<h2>More Job Guides</h2>

<p>Planning a career with children or a move to Canada? These cover it:</p>

<ul>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; work permits and the sectors that hire from abroad.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; the LMIA and the routes to permanent residence.</li>
    <li><a href="/blog/dental-assistant-jobs-in-canada">Dental Assistant Jobs in Canada</a> &mdash; another provincially regulated career with a college route.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; school teaching certification across the border.</li>
    <li><a href="/blog/online-teacher-jobs-worldwide">Online Teacher Jobs Worldwide</a> &mdash; teaching from home while you get qualified.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not immigration advice. Wages, certification rules, wage top-ups and immigration categories change over time. Confirm the current position with Job Bank, your province's early childhood education office, the employer and Immigration, Refugees and Citizenship Canada before relying on it.</p>
HTML;
    }
}
