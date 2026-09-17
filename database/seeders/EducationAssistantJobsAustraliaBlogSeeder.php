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
 * "Education Assistant Jobs in Australia" — a guide for people who want to
 * support teachers in Australian schools. Every state names the role
 * differently, so the guide maps the title, pay, requirements and job portal
 * for each state's government schools.
 *
 * Corrections and clarifications to the draft (checked against the WA
 * Department of Education jobs board, the NSW Department of Education careers
 * pages, role statement and Determination 1 of 2025, School Jobs Vic, the SA
 * Department for Education support-role pages and the Queensland teacher aide
 * role description, September 2026):
 *
 * 1. The draft says NSW education assistants need a Certificate III in
 *    Education Support. That minimum comes from the Educational Paraprofessional
 *    determination, a temporary role engaged only through strategic School
 *    Workforce initiatives. The main NSW role, the School Learning Support
 *    Officer, lists a Working with Children Check clearance and mandatory
 *    training as essential, with a Certificate I, II or III in Education
 *    Support or similar as desirable.
 *
 * 2. The draft says South Australia's Work While You Study pathway covers
 *    students working toward a Certificate III or Diploma. Those students can
 *    work as Early Childhood Workers in preschools. The School Services Officer
 *    option is for pre-service teachers studying at university, and 2026
 *    registrations have closed.
 *
 * 3. The draft says the WA Education Assistant Employment Register covers
 *    casual work. The register lists full-time and part-time, fixed-term and
 *    permanent positions.
 *
 * 4. The draft leaves out Queensland, where state schools call the role a
 *    teacher aide and require a blue card, and it lists "Education Support
 *    Officer" for "various employers" without naming any. It also leaves out
 *    the WA Special Needs Level 3 and Lead rates and the SA term-time and
 *    casual loadings.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class EducationAssistantJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-education-assistant-jobs.html';

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
        $title = 'Education Assistant Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Education assistant jobs in Australia go by a different title in each state: Education Assistant in WA, School Learning Support Officer in NSW, education support in Victoria, SSO in SA and teacher aide in Queensland. WA pays $34.75 to $43.28 an hour.',
                'content' => $content,
                'featured_image' => 'blogs/education-assistant-jobs-in-australia.jpg',
                'tags' => 'education assistant jobs australia, teacher aide jobs, school learning support officer jobs nsw, school services officer sa, education support jobs victoria, education assistant special needs wa, certificate iii in school based education support, education assistant pay australia',
                'meta_title' => 'Education Assistant Jobs in Australia: Pay and How to Apply',
                'meta_description' => 'Education assistant jobs in Australia by state: job titles, WA pay of $34.75 to $43.28 an hour, qualifications, checks and official job portals.',
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
            ['name' => 'Australian Schools & State Education Departments Hiring Education Assistants (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-education-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'graduate-entry-level'],
            ['name' => 'Graduate & Entry Level']
        );

        Job::updateOrCreate(
            [
                'position' => 'Education Assistant — Teacher Aide, School Learning Support Officer and School Services Officer Roles, Australian Schools',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time and part-time; many roles follow school terms',
                'language' => 'English',
                // Pay is set by each state's agreement or award and differs by
                // level, so no single band is quoted.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Education assistant, teacher aide, SLSO and SSO roles supporting teachers and students in Australian schools, full-time and part-time.',
                'seo_keywords' => 'education assistant jobs australia, teacher aide jobs, school learning support officer jobs, school services officer jobs, education support jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian schools hire education assistants to support teachers and students in classrooms, with students who have additional needs, and in school routines. The title changes by state: Education Assistant in Western Australia, School Learning Support Officer in New South Wales, education support class in Victoria, School Services Officer in South Australia and teacher aide in Queensland.</p>

<h3>Common requirements</h3>
<ul>
    <li>A working with children check for the state you work in, such as a blue card in Queensland</li>
    <li>A Certificate III level qualification for some roles, including Queensland teacher aides and NSW educational paraprofessionals</li>
    <li>Experience with children or students with additional needs, which many employers list as desirable</li>
    <li>Australian residency or a current work visa</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, hours and hiring decisions are set by each school, state education department and employer &mdash; not by JobGader. Government school jobs are free to apply for through each state's official job portal.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Education assistant jobs in Australia support teachers and students in classrooms, with students who have additional needs, and in daily school routines, but each state uses its own title: Education Assistant in Western Australia, School Learning Support Officer (SLSO) in New South Wales, education support in Victoria, School Services Officer (SSO) in South Australia and teacher aide in Queensland.</strong> Western Australian government schools were advertising Education Assistant roles at <strong>$34.75 to $43.28 an hour</strong> in September 2026, full-time and part-time. There is no single national qualification, but every state requires a working with children check, and Queensland teacher aides need a Certificate III level qualification or equivalent skills.</p>

<p>This guide shows the title, pay, requirements and official job portal for each state, so you search for the right words and apply in the right place.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-education-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127979; Browse Education Assistant Jobs in Australia &rarr;
    </a>
</div>

<h2>What the Job Is Called in Each State</h2>

<p>Search only for "education assistant" and you will miss most vacancies outside Western Australia. Government schools use these titles:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">State</th>
            <th style="padding:10px;text-align:left;">Title in government schools</th>
            <th style="padding:10px;text-align:left;">Where jobs are advertised</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Western Australia</strong></td><td style="padding:10px;">Education Assistant (Mainstream, Special Needs or Lead)</td><td style="padding:10px;">WA Department of Education current jobs</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>New South Wales</strong></td><td style="padding:10px;">School Learning Support Officer (SLSO); Educational Paraprofessional</td><td style="padding:10px;">NSW Department of Education careers</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Victoria</strong></td><td style="padding:10px;">Education support class</td><td style="padding:10px;">School Jobs Vic</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>South Australia</strong></td><td style="padding:10px;">School Services Officer (SSO)</td><td style="padding:10px;">EduJobs</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Queensland</strong></td><td style="padding:10px;">Teacher aide</td><td style="padding:10px;">Queensland Department of Education current vacancies</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">State education department career pages, September 2026. Catholic and independent schools choose their own titles, so also search for "learning support assistant" and "teacher aide".</p>
</div>

<h2>What Education Assistants Do</h2>

<p>The NSW Department of Education describes the role clearly: school learning support officers, <strong>"under the supervision and direction of a teacher, assist in classroom activities, school routines, and the care and management of students with special needs."</strong> In practice that means:</p>

<ul>
    <li>helping implement individual education programs (IEPs);</li>
    <li>working one-on-one or with small groups while the teacher leads the class;</li>
    <li>helping students build personal, social and independent living skills;</li>
    <li>attending to the personal care needs of students who need it;</li>
    <li>preparing and handing out learning materials, and some clerical work;</li>
    <li>supervising students in the playground, on excursions and at sport, which Queensland lists for teacher aides.</li>
</ul>

<p>South Australia's classroom support officers also help plan activities with teachers, deliver curriculum to small groups or individuals under the teacher's direction, and keep records.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/education-assistant-jobs-in-australia-classroom-support.jpg" alt="An education assistant helping primary school students with their writing in an Australian classroom" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Most of the job is one-on-one and small-group support while the teacher leads the class.</figcaption>
</figure>

<h2>How Much Education Assistants Earn</h2>

<p>Western Australia publishes the hourly rate on every vacancy. These are the rates on WA government school listings on 17 September 2026, all under the <strong>Education Assistants (Government) General Agreement 2025</strong>:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">WA role</th>
            <th style="padding:10px;text-align:left;">Level</th>
            <th style="padding:10px;text-align:left;">Hourly rate</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Education Assistant &ndash; Mainstream</td><td style="padding:10px;">Level 1/2</td><td style="padding:10px;"><strong>$34.75 &ndash; $39.05</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Education Assistant &ndash; Special Needs</td><td style="padding:10px;">Level 2/3</td><td style="padding:10px;"><strong>$36.87 &ndash; $41.37</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Education Assistant &ndash; Special Needs</td><td style="padding:10px;">Level 3</td><td style="padding:10px;"><strong>$39.78 &ndash; $41.37</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Education Assistant &ndash; Lead</td><td style="padding:10px;">EA (Lead)</td><td style="padding:10px;"><strong>$43.28</strong></td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">WA Department of Education current jobs, 17 September 2026. Rates are before superannuation.</p>
</div>

<p>In South Australia, SSOs employed part-time for <strong>school terms only receive a 16% loading</strong>, and casual SSOs receive a <strong>25% loading</strong> instead of paid leave. The SA department says university students working as SSOs or early childhood workers earn <strong>$30+ per hour</strong>.</p>

<h2>Western Australia: Education Assistant Jobs</h2>

<p>Western Australia uses the plain title <strong>Education Assistant</strong>, split into Mainstream, Special Needs and Lead positions. On the day we checked, Special Needs vacancies outnumbered mainstream ones, across primary schools, secondary colleges and education support centres in Perth and regional WA. Most listings offered <strong>full-time and part-time</strong> work, on fixed-term or permanent contracts.</p>

<p>If no school near you is advertising, apply to the <strong>Education Assistant Employment Register</strong>, which stays open until 31 December 2026 for full-time and part-time, fixed-term and permanent positions. Schools use the register to find staff when a job comes up.</p>

<p><strong>Where to apply:</strong> <a href="https://www.education.wa.edu.au/vi/current-jobs" target="_blank" rel="noopener">WA Department of Education current jobs</a>.</p>

<h2>New South Wales: School Learning Support Officers</h2>

<p>NSW public schools hire <strong>School Learning Support Officers (SLSOs)</strong>. Besides the general role there are specialist SLSO roles in pre-schools, braille transcription, sign interpreting and ethnic (bilingual) support. The NSW Department of Education says it has <strong>nearly 100,000 employees</strong> across the state.</p>

<p>The SLSO statement of duties lists a <strong>Working with Children Check clearance</strong> and completion of the department's mandatory training as essential. A <strong>Certificate I, II or III in Education Support or similar</strong> and a first aid certificate are listed as desirable, so you can apply without the certificate. The essential skills include working well in a team and the ability to work with students with emotional, physical or intellectual disabilities.</p>

<p>NSW also employs <strong>Educational Paraprofessionals</strong>, who "work under the guidance and supervision of teachers". This is a different, temporary role. Under Determination 1 of 2025, in effect from 1 January 2026, the minimum qualification is completing, or being part-way through, <strong>a Certificate III in Education Support or equivalent studies</strong>, and paraprofessionals are engaged only through strategic School Workforce initiatives approved by the department.</p>

<p><strong>Where to apply:</strong> <a href="https://careers.education.nsw.gov.au/" target="_blank" rel="noopener">NSW Department of Education careers</a>.</p>

<h2>Victoria: Education Support Jobs</h2>

<p>Victorian government schools employ principals, teachers and <strong>education support employees</strong>, the class that covers school staff who are not teachers, including classroom support roles. All jobs are advertised on <strong>School Jobs Vic</strong>, the department's recruitment system, which lists "teacher and education support class vacancies".</p>

<p>You can search vacancies and set up job alerts, or create a profile in the <strong>Applicant Pool</strong> so school recruiters can find you based on your preferences. Search for "education support", "integration aide" and "learning support".</p>

<p><strong>Where to apply:</strong> <a href="https://www.vic.gov.au/school-jobs" target="_blank" rel="noopener">School Jobs Vic</a>.</p>

<h2>South Australia: School Services Officers</h2>

<p>The SA Department for Education says an SSO <strong>"provides administration and classroom support within South Australian government schools and preschools."</strong> SSOs can work <strong>school terms (42 weeks)</strong> or be employed for the <strong>full year (52 weeks)</strong>. The department groups SSO work into six roles: administration officer, classroom support officer, resource centre officer, finance officer, laboratory officer and computer systems manager. If you want to support students, look for classroom support.</p>

<p>Before you can be hired as an SSO you need to:</p>

<ul>
    <li>be an Australian resident, with an Australian birth certificate, a residency permit or a <strong>current work visa</strong>;</li>
    <li>complete the full-day Responding to Risks of Harm, Abuse and Neglect &ndash; Education and Care training;</li>
    <li>hold a valid <strong>Working With Children Check</strong>;</li>
    <li>get an authority to work letter;</li>
    <li>register on the <strong>Employable Ancillary Register (EAR)</strong>.</li>
</ul>

<p><strong>Where to apply:</strong> <a href="https://www.education.sa.gov.au/working-us/careers-education/support-roles-schools-and-preschools-ancillary/types-roles/school-services-officers-ssos-about-role" target="_blank" rel="noopener">SA School Services Officer careers</a>, with vacancies on EduJobs.</p>

<h2>Queensland: Teacher Aide Jobs</h2>

<p>Queensland state schools call the role <strong>teacher aide</strong>. The department's role description says a teacher aide supports teachers, students and parents with learning activities and administrative duties. The work can include supervising students on the playground and on excursions in partnership with a teacher, first aid, and helping students with special needs with moving, meals, toileting and dressing.</p>

<p>Holding a <strong>blue card</strong> (Queensland's Working with Children Check) is a mandatory condition of the job. The role description also asks for a <strong>Certificate III level qualification</strong> or the ability to show equivalent skills and knowledge.</p>

<h2>Studying to Be a Teacher? Work While You Study</h2>

<p>South Australia's <strong>Work While You Study</strong> program lets <strong>pre-service teachers studying at university</strong> work as SSOs or early childhood workers in public schools and preschools. If you are working toward a <strong>Certificate III in Children's Services or Education Support, or a Diploma in Children's Services</strong>, you can work as an <strong>early childhood worker</strong> in a preschool while you study, but not as an SSO under this program.</p>

<p>Registrations for the 2026 program have closed, and the department says 2027 registrations will open later in the year. You still need to meet the eligibility requirements above and register on the EAR.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/education-assistant-jobs-in-australia-small-group.jpg" alt="An education assistant guiding a small group of students through a reading task at a classroom table" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Working as an assistant while you study teaching gives you paid classroom experience.</figcaption>
</figure>

<h2>What Qualifications Do You Need?</h2>

<p>There is no single Australia-wide rule. The table shows what the official role documents ask for:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Qualification</th>
            <th style="padding:10px;text-align:left;">Checks</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">NSW School Learning Support Officer</td><td style="padding:10px;">Certificate I, II or III in Education Support is desirable, not essential</td><td style="padding:10px;">Working with Children Check clearance and mandatory training</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">NSW Educational Paraprofessional</td><td style="padding:10px;">Certificate III in Education Support or equivalent, completed or in progress</td><td style="padding:10px;">Department requirements</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Queensland teacher aide</td><td style="padding:10px;">Certificate III level, or equivalent skills and knowledge</td><td style="padding:10px;">Blue card (mandatory)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">SA School Services Officer</td><td style="padding:10px;">Set by the position</td><td style="padding:10px;">Working With Children Check, child safety training, EAR registration</td></tr>
    </tbody>
</table>
</div>

<p>The national course most applicants take is the <strong>Certificate III in School Based Education Support (CHC30221)</strong>, the current name for what job ads often call a "Certificate III in Education Support". It is widely offered by TAFEs and private training providers, and some states subsidise it. A first aid certificate and experience with children, whether paid, volunteer or on placement, also make your application stronger.</p>

<h2>Can Overseas Applicants Get These Jobs?</h2>

<p>Only if they already have the right to work. Government school employers expect Australian residency or a current work visa, as South Australia's SSO requirements show, and you still need that state's working with children check. If you are moving to Australia, first compare the visa routes in our <a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> guide.</p>

<h2>How to Apply</h2>

<ol>
    <li><strong>Search every title.</strong> Use education assistant, teacher aide, school learning support officer, school services officer, education support and learning support assistant.</li>
    <li><strong>Get your working with children check</strong> for the state you want to work in before you apply, because most roles need it from day one.</li>
    <li><strong>Consider a Certificate III.</strong> It is required in Queensland and for NSW paraprofessionals, and it strengthens applications everywhere else.</li>
    <li><strong>Apply through the official portal</strong> for government schools, and through each school or diocese for Catholic and independent schools.</li>
    <li><strong>Address the selection criteria.</strong> Give examples of supporting children, working with a teacher, managing behaviour and keeping information confidential.</li>
    <li><strong>Join a register or pool</strong> such as WA's Education Assistant Employment Register, Victoria's Applicant Pool or South Australia's EAR, so schools can contact you when roles open.</li>
</ol>

<h2>Education Assistant vs Teacher</h2>

<p>Teachers plan lessons, teach and assess students, and must be registered with their state's teacher regulatory authority. Education assistants work under a teacher's direction and do not need teacher registration, so the job does not qualify you to teach. It is a common first step, though: Queensland runs a teacher aide to teacher pathway, and South Australia hires university teaching students as SSOs.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is a teacher aide called in Australia?</h3>
<p>It depends on the state: Education Assistant in Western Australia, School Learning Support Officer in New South Wales, education support class in Victoria, School Services Officer in South Australia and teacher aide in Queensland.</p>

<h3>How much do education assistants earn in Australia?</h3>
<p>WA government schools advertised $34.75 to $39.05 an hour for mainstream roles, $36.87 to $41.37 for special needs roles and $43.28 for lead roles in September 2026. Pay in other states is set by each state's award or agreement.</p>

<h3>Do I need a Certificate III to be an education assistant?</h3>
<p>Not everywhere. Queensland teacher aides need a Certificate III level qualification or equivalent skills, and NSW educational paraprofessionals need one completed or in progress, but for NSW School Learning Support Officers it is desirable rather than essential.</p>

<h3>Do education assistants need a working with children check?</h3>
<p>Yes. NSW lists a Working with Children Check clearance as essential, South Australia requires a valid check, and Queensland teacher aides must hold a blue card.</p>

<h3>Are there part-time education assistant jobs?</h3>
<p>Yes. Most WA listings offer full-time and part-time work, and South Australian SSOs can be employed for school terms only, with a 16% loading, or casually, with a 25% loading.</p>

<h3>Can I work as an education assistant while studying teaching?</h3>
<p>Yes. South Australia's Work While You Study program employs pre-service teachers as SSOs or early childhood workers. Registrations for 2026 have closed, and 2027 registrations open later in the year.</p>

<h3>Can international applicants apply for education assistant jobs?</h3>
<p>Only with existing work rights. South Australia, for example, requires Australian residency or a current work visa, and every state requires its own working with children check.</p>

<h3>Where do I apply for education assistant jobs in government schools?</h3>
<p>Through WA Department of Education current jobs, NSW Department of Education careers, School Jobs Vic, EduJobs in South Australia and the Queensland Department of Education vacancies page.</p>

<h2>People Also Search For</h2>

<h3>Teacher aide jobs</h3>
<p>Queensland's title for the role; other states use different names.</p>

<h3>School learning support officer jobs NSW</h3>
<p>Listed on NSW Department of Education careers; a WWCC is essential.</p>

<h3>Education assistant special needs WA pay</h3>
<p>$36.87 to $41.37 an hour at Level 2/3 in September 2026.</p>

<h3>SSO jobs South Australia</h3>
<p>Advertised on EduJobs; register on the Employable Ancillary Register first.</p>

<h3>Education support jobs Victoria</h3>
<p>Listed on School Jobs Vic, the department's recruitment system.</p>

<h3>Certificate III in School Based Education Support</h3>
<p>The current national course, CHC30221.</p>

<h3>Education assistant lead WA</h3>
<p>$43.28 an hour under the 2025 agreement.</p>

<h3>Work While You Study SA</h3>
<p>For pre-service teachers; 2027 registrations open later in the year.</p>

<h2>More Job Guides</h2>

<p>Looking at other education or entry-level work? These cover it:</p>

<ul>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; every visa route compared.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; another support role with a Certificate III pathway.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; entry-level work to start with.</li>
    <li><a href="/blog/preschool-teacher-jobs-in-canada">Preschool Teacher Jobs in Canada</a> &mdash; early childhood education work in Canada.</li>
    <li><a href="/blog/teacher-jobs-in-usa">Teacher Jobs in USA</a> &mdash; the step up to teaching, in the US.</li>
    <li><a href="/blog/school-administrator-jobs-in-uk">School Administrator Jobs in UK</a> &mdash; school office duties, FTE vs actual pay on term-time contracts, routes in and where schools advertise.</li>
    <li><a href="/blog/educational-support-jobs-in-usa">Educational Support Jobs in USA</a> &mdash; teacher assistant and paraprofessional pay, the Title I rule, the ParaPro test and who is hiring.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes. Pay rates, qualification requirements, checks and application rules change and differ between states and employers. Confirm the current requirements in the official vacancy and with the relevant state education department before applying.</p>
HTML;
    }
}
