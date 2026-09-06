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
 * "Healthcare Jobs in the UK" — a sector guide rather than one vacancy, so the
 * apply link goes to an Indeed search and the post carries no JobPosting
 * markup.
 *
 * The draft said the Health and Care Worker visa is available "increasingly for
 * care workers and healthcare assistants". That has been false since 22 July
 * 2025, when overseas sponsorship closed for occupation codes 6135 and 6136.
 * A healthcare assistant post inside an NHS trust is still one of those codes.
 * What remains sponsorable is registered clinical staff, where the real gate is
 * professional registration — NMC, GMC or HCPC — rather than the visa.
 *
 * This post deliberately routes every care-worker-visa question to
 * /blog/caregiver-jobs-in-uk-with-visa-sponsorship rather than restating it, so
 * the two pages do not compete for the same query.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class HealthcareJobsUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/q-healthcare-jobs.html?vjk=4ff6f1084283ad4e';

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
        $title = 'Healthcare Jobs in the UK';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Which UK healthcare roles can still be sponsored from overseas and which cannot, why professional registration is the real gate rather than the visa, how NHS pay bands work, and how TRAC applications are actually assessed.',
                'content' => $content,
                'featured_image' => 'blogs/healthcare-jobs-in-uk.jpg',
                'tags' => 'healthcare jobs in uk, nhs jobs, trac jobs nhs, healthcare assistant jobs, nhs pay bands, health and care worker visa, nmc registration, allied health professional jobs uk',
                'meta_title' => 'Healthcare Jobs in the UK: Routes and NHS Pay',
                'meta_description' => 'Healthcare jobs in the UK: which roles can still be sponsored from overseas, how NHS pay bands and TRAC applications work, and what registration you need.',
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
            ['name' => 'NHS Trusts & UK Care Providers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-healthcare-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Healthcare Roles — NHS Trusts and Private Providers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift-based, including nights and weekends in most clinical roles',
                'language' => 'English',
                // NHS pay follows Agenda for Change bands and private providers
                // set their own, so no single range would hold across these.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Clinical and support roles across NHS trusts and UK private healthcare providers. Right to work required for support roles; registered clinical staff may be sponsored.',
                'seo_keywords' => 'healthcare jobs uk, nhs jobs, trac jobs, healthcare assistant jobs uk, nhs pay bands, allied health professional jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>NHS trusts and private healthcare providers across the UK recruit continuously: registered nurses and midwives, doctors, allied health professionals such as radiographers, physiotherapists, operating department practitioners and biomedical scientists, alongside healthcare assistants, ward clerks, porters and support staff.</p>

<h3>Important &mdash; who can be sponsored from overseas</h3>
<p>The Health and Care Worker visa <strong>closed to new overseas applicants in the care worker and senior care worker occupation codes (6135 and 6136) on 22 July 2025</strong>, and remains closed. A healthcare assistant post inside an NHS trust falls within those codes, so it is open to candidates who already hold the right to work in the UK rather than to applicants abroad. <strong>Registered clinical staff</strong> &mdash; nurses, midwives, doctors and allied health professionals &mdash; can still be sponsored, provided they hold or can obtain the relevant professional registration.</p>

<h3>Requirements</h3>
<ul>
    <li>For nursing: NMC registration, reached through the CBT and OSCE examinations plus an approved English test</li>
    <li>For medical roles: GMC registration; for allied health professions: HCPC registration</li>
    <li>For support roles: the right to work in the UK, with training provided on the job</li>
    <li>An enhanced DBS check for every patient-facing role</li>
    <li>Availability for shift work, including nights and weekends in most clinical settings</li>
</ul>

<h3>What is on offer</h3>
<ul>
    <li>NHS pay under Agenda for Change, with bands set nationally and enhancements for unsocial hours</li>
    <li>NHS pension, annual leave rising with service, and funded development routes into clinical roles</li>
    <li>Apprenticeship and trainee nursing associate pathways from support roles into registered practice</li>
    <li>Private providers setting their own pay and terms, often with different shift patterns</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check the employer on the gov.uk register of licensed sponsors</strong> before accepting any offer that involves sponsorship, and remember that being licensed does not permit sponsoring a closed occupation code. <strong>Charging a worker for sponsorship is illegal in the UK</strong>, so any fee requested for a Certificate of Sponsorship or a job offer is a fraud.</p>

<p><strong>Note:</strong> pay bands, registration requirements and immigration rules are set by the NHS, the regulators and the Home Office &mdash; not by JobGader. Confirm current requirements at their source before applying or paying any fee.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Healthcare is one of the largest employers in the UK and it is genuinely short of staff. But the question people arrive at this page with &mdash; can I be sponsored from abroad? &mdash; now has two different answers depending on which job you mean, and a lot of published advice is still giving the answer from before July 2025. This guide separates the roles that can still be sponsored from the ones that cannot, and covers what the process actually looks like on either side.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/q-healthcare-jobs.html?vjk=4ff6f1084283ad4e" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🏥 Browse Healthcare Jobs in the UK &rarr;
    </a>
</div>

<h2>Who Can Still Be Sponsored, and Who Cannot</h2>

<p>This is the dividing line, and it is not about the employer &mdash; NHS or private makes no difference. It is about the occupation code.</p>

<p><strong>Closed to new overseas applicants since 22 July 2025:</strong> care worker and senior care worker roles, occupation codes 6135 and 6136. Crucially, a <strong>healthcare assistant post inside an NHS trust sits inside those codes</strong>. Working for the NHS does not reopen the route, and the fact that a trust is a licensed sponsor does not change it either. These roles are now open to people who already hold the right to work in the UK.</p>

<p><strong>Still sponsorable under the Health and Care Worker visa:</strong> registered clinical staff &mdash; nurses, midwives, doctors, and allied health professionals such as radiographers, physiotherapists, operating department practitioners, paramedics and biomedical scientists. For these roles the visa is usually the easier half; the harder half is registration.</p>

<p>Our guide to <a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">caregiver jobs in the UK with visa sponsorship</a> covers the closure in full &mdash; what changed, who is still covered by the transitional arrangements to 2028, and the scams that grew around the change. If you are searching for a care role specifically, start there.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-jobs-in-uk-team.jpg"
         alt="Clinical staff working across NHS and private healthcare in the UK"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Registration Is the Real Gate</h2>

<p>For clinical roles, the visa follows the job and the job follows the registration. Three regulators cover almost everyone:</p>

<ul>
    <li><strong>NMC</strong> &mdash; nurses and midwives. Overseas-trained nurses complete a computer-based test (CBT), then an OSCE practical examination taken in the UK, alongside an approved English test. Many employers sponsor the OSCE stage and provide preparation, which is why the first job and the registration are usually arranged together.</li>
    <li><strong>GMC</strong> &mdash; doctors. Usually via PLAB or an accepted postgraduate qualification, plus English evidence.</li>
    <li><strong>HCPC</strong> &mdash; allied health professionals, from radiographers to paramedics, each with its own standards for international applicants.</li>
</ul>

<p>Plan on the registration timeline rather than the visa timeline. The Certificate of Sponsorship is issued in days once an employer decides; registration takes months and is the part that determines when you can actually start.</p>

<h2>How NHS Pay Actually Works</h2>

<p>NHS pay in England is set nationally under <strong>Agenda for Change</strong>, in bands, rather than negotiated per job. That has a useful consequence: the band in the advertisement tells you the salary, and it is the same at every trust. What varies between posts is the high cost area supplement in and around London, and the enhancements paid for nights, weekends and bank holidays &mdash; which are substantial in shift-based roles.</p>

<table style="width:100%;border-collapse:collapse;margin:24px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;text-align:left;">
            <th style="padding:10px;border:1px solid #e5e7eb;">Role</th>
            <th style="padding:10px;border:1px solid #e5e7eb;">Typical position</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Healthcare assistant / support worker</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Entry bands, commonly around &pound;24,000 &ndash; &pound;26,000</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Experienced support / senior care</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Roughly &pound;26,000 &ndash; &pound;30,000</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Registered nurse (newly registered)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Band 5, from around &pound;31,000</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Senior and specialist clinical</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">&pound;30,000 &ndash; &pound;40,000+ depending on band</td>
        </tr>
    </tbody>
</table>

<p>Private providers are outside Agenda for Change and set their own pay, so compare the whole package rather than the headline &mdash; pension, leave and unsocial-hours enhancements differ, and the NHS pension is a significant part of the value of an NHS post.</p>

<h2>TRAC and How NHS Applications Are Assessed</h2>

<p>Most NHS trusts run recruitment through <strong>TRAC</strong>, the applicant tracking system behind the vacancy listings. You create a profile once and apply through it repeatedly, tracking each application's status in the same place.</p>

<p>The thing worth knowing about NHS applications is that they are not read like a CV. Every advertisement comes with a <strong>person specification</strong> splitting requirements into <em>essential</em> and <em>desirable</em>, and shortlisting is done by scoring your supporting statement against those lines &mdash; often by someone working through a list rather than reading for impression.</p>

<ol>
    <li><strong>Open the person specification before you write anything.</strong> It is the marking scheme.</li>
    <li><strong>Answer every essential criterion explicitly</strong>, in the order it is listed, with an example. An unaddressed essential criterion is usually an automatic rejection regardless of the rest.</li>
    <li><strong>Use their words.</strong> If it says &quot;evidence of working within a multidisciplinary team&quot;, use that phrase, then give the example.</li>
    <li><strong>Do not send one statement to every vacancy.</strong> The specification changes between posts and the scoring follows it.</li>
    <li><strong>Fill in the employment history completely</strong>, including dates and gaps. NHS pre-employment checks are strict and unexplained gaps slow everything down.</li>
</ol>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/healthcare-jobs-in-uk-ward.jpg"
         alt="Nurses and clinical staff on a hospital ward in the UK"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where the Vacancies Are Posted</h2>

<ul>
    <li><strong>NHS trust vacancy pages and the NHS jobs listings</strong>, which route into each trust's TRAC portal.</li>
    <li><strong>Private and social care providers</strong>, advertising on their own sites and on care-sector job boards.</li>
    <li><strong>Specialist medical and locum agencies</strong> for doctor and advanced practitioner posts.</li>
    <li><strong>General job boards</strong>, useful for scanning the market, though NHS applications almost always finish on TRAC.</li>
</ul>

<h2>Getting In Without a Clinical Qualification</h2>

<p>If you already have the right to work in the UK, support roles are the accessible entry point &mdash; healthcare assistant, ward clerk, porter, domestic and catering staff. They train on the job and recruit constantly.</p>

<p>What makes them worth more than the starting salary suggests is the route out of them. The NHS funds progression: <strong>trainee nursing associate</strong> and <strong>registered nurse degree apprenticeship</strong> programmes take existing support staff into registered practice while they keep earning. That path is not open to someone applying from abroad, but it is a genuine reason to take an entry-level post if you are already here.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I get a UK healthcare job with visa sponsorship from overseas?</h3>
<p>It depends on the role. Registered clinical staff &mdash; nurses, midwives, doctors, allied health professionals &mdash; can still be sponsored under the Health and Care Worker visa. Care worker and healthcare assistant roles closed to new overseas applicants on 22 July 2025 and remain closed, including inside NHS trusts.</p>

<h3>Does working for the NHS make a healthcare assistant role sponsorable?</h3>
<p>No. The restriction is on the occupation code, not the employer. A healthcare assistant post in an NHS trust sits in the closed codes, and a trust being a licensed sponsor does not change that.</p>

<h3>What is TRAC Jobs?</h3>
<p>The applicant tracking system most NHS trusts use to advertise vacancies and manage applications. You build one profile and apply through it, tracking each application's progress in the same place.</p>

<h3>What is the easiest NHS job to get?</h3>
<p>Healthcare assistant and other support roles, which need no prior degree and recruit continuously &mdash; but only if you already hold the right to work in the UK, since they cannot be sponsored from overseas.</p>

<h3>How do NHS pay bands work?</h3>
<p>NHS pay in England follows Agenda for Change, set nationally in bands, so the band in the advertisement tells you the salary and it is the same at every trust. What varies is the high cost area supplement near London and the enhancements for nights and weekends.</p>

<h3>What registration do I need as an overseas nurse?</h3>
<p>NMC registration, reached through a computer-based test, an OSCE practical examination taken in the UK, and an approved English test. Many employers sponsor and prepare candidates for the OSCE, which is why the job and the registration are usually arranged together.</p>

<h3>Why was my NHS application rejected without an interview?</h3>
<p>Usually because the supporting statement did not address every essential criterion in the person specification. Shortlisting is scored against that list, so an unaddressed essential line is normally an automatic rejection whatever else the application says.</p>

<h3>Can a support role lead to a registered clinical career?</h3>
<p>Yes. Trainee nursing associate and registered nurse degree apprenticeship routes take existing support staff into registered practice while they continue earning. It is one of the strongest reasons to take an entry-level NHS post.</p>

<h2>People Also Search For</h2>

<h3>Healthcare jobs in UK for foreigners</h3>
<p>Registered clinical roles remain sponsorable; care worker and healthcare assistant roles do not, following the July 2025 closure of those occupation codes to overseas applicants.</p>

<h3>Healthcare jobs in UK with visa sponsorship</h3>
<p>Nurses, midwives, doctors and allied health professionals, where professional registration rather than the visa is the limiting step.</p>

<h3>NHS healthcare jobs in UK</h3>
<p>Advertised by individual trusts and applied for through TRAC, with pay set nationally under Agenda for Change bands.</p>

<h3>TRAC jobs NHS</h3>
<p>The recruitment system behind most NHS vacancies. One profile, repeated applications, and shortlisting scored against the person specification.</p>

<h3>TRAC jobs NHS healthcare assistant</h3>
<p>Among the most frequently advertised NHS roles, needing no prior degree &mdash; open to candidates who already hold the right to work in the UK.</p>

<h3>Health care jobs in UK salary</h3>
<p>Around &pound;24,000 to &pound;26,000 for entry support roles, from about &pound;31,000 for a newly registered Band 5 nurse, with enhancements for unsocial hours on top.</p>

<h3>Highest paying jobs in healthcare UK</h3>
<p>Consultants, senior and specialist nurses, and advanced practitioners, reached through registration and years of practice rather than by moving employer.</p>

<h3>NHS pay bands explained</h3>
<p>Agenda for Change sets pay nationally by band, so the same band pays the same at every English trust, adjusted for the high cost area supplement.</p>

<h2>More Job Guides</h2>

<p>Looking at care roles or other destinations? These cover them:</p>

<ul>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; the full picture on the care worker closure, who is still covered, and the scams around it.</li>
    <li><a href="/blog/nurse-jobs-in-the-us">Nurse Jobs in the US</a> &mdash; the American route for registered nurses, and the green card queue behind it.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London</a> &mdash; entry-level work in the UK for people who already hold the right to work.</li>
    <li><a href="/blog/warehouse-jobs-uk-visa-sponsorship">Warehouse Jobs UK Visa Sponsorship</a> &mdash; what sponsorship looks like outside healthcare.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Immigration rules, pay bands and registration requirements change &mdash; confirm the current position on gov.uk, with the relevant regulator, and on the employer's own advertisement before applying or paying any fee.</p>
HTML;
    }
}
