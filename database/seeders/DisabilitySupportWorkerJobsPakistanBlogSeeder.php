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
use Illuminate\Support\Facades\DB;

/**
 * Disability support work in Pakistan, checked on 29 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. All five Indeed links are gone, together with the passages describing an
 *     ABA Practitioner vacancy and "current Indeed results in Pakistan".
 *  2. The brief presented the National Disability and Development Forum as
 *     currently recruiting. NDF is a real organisation working in rural Sindh
 *     with a stated focus on persons with disabilities, but the vacancy on its
 *     careers page is dated 7 July 2026 and has closed. The guide names the
 *     organisation without implying a live opening.
 *  3. The brief referred vaguely to "current 2026 recruitment information
 *     connected with special education" and listed job titles with no source.
 *     Replaced with the Punjab Special Education Department's own published
 *     institution figures and its jobs page.
 *  4. The brief named a job platform for people with disabilities as though it
 *     were an employer of support workers. That claim could not be verified,
 *     so it is dropped; the distinction the brief drew is kept, because it is
 *     a good one.
 *  5. Six FAQs and no People Also Search For block. Now eight of each.
 *
 * The spine the brief missed: a statutory employment quota is the reason much
 * of this work exists at all. The Disabled Persons (Employment and
 * Rehabilitation) Ordinance 1981 sets it, Punjab raised its share to three per
 * cent in 2015, and the Punjab Council on Rights of Persons with Disabilities
 * publishes the current position.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class DisabilitySupportWorkerJobsPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'disability-support-worker-jobs-in-pakistan';

    public const APPLY_URL = 'https://sed.punjab.gov.pk/jobs';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'Disability Support Employers, Pakistan (Aggregated)'],
                ['display_reference' => 'pk-disability-support-worker-aggregated', 'type' => 'Government']
            );
            $location = Location::firstOrCreate(
                ['name' => 'Pakistan'],
                ['area' => 'Nationwide', 'country' => 'Pakistan']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Disability Support Worker, Pakistan Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'School hours in education settings; shift or live-in patterns in home-based support',
                    'language' => 'English, Urdu',
                    // Education posts follow government scales, home support
                    // posts do not, and neither publishes a range.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'disability support worker jobs in pakistan, special education jobs punjab, autism support assistant jobs, learning support assistant jobs, disability quota pakistan',
                    'meta_description' => 'Disability support roles in Pakistan across special education institutions, therapy centres and home-based care, with the titles these jobs are actually advertised under.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'career-advice'],
                ['name' => 'Career Advice', 'description' => 'Practical guides on finding work, applying and building a career.']
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Disability Support Worker Jobs in Pakistan',
                    'excerpt' => 'Searching this exact title in Pakistan returns almost nothing, so people assume the work does not exist. It does, under other names. Punjab alone runs 118 special education institutions, and a statutory 3 per cent quota sits behind the sector.',
                    'content' => $content,
                    'featured_image' => 'blogs/disability-support-worker-jobs-pakistan.jpg',
                    'tags' => 'disability support worker jobs in pakistan, special education jobs punjab, autism support assistant jobs, learning support assistant jobs, disability employment quota pakistan, therapy assistant jobs, inclusive education jobs, rehabilitation support jobs',
                    'meta_title' => 'Disability Support Worker Jobs in Pakistan: Real Titles',
                    'meta_description' => 'Disability support jobs in Pakistan are advertised under other titles. The real names, the 3 per cent employment quota, and the 118 institutions that hire.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p>This is an overview of disability support work with employers across Pakistan. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Disability support covers three quite different settings: government special education institutions, private therapy and rehabilitation centres, and home-based support for a disabled person in their own household. Entry requirements and pay differ sharply between them.</p>
<p>The title "Disability Support Worker" is rare in Pakistani advertisements. The same work appears as Special Education Teacher, Learning Support Assistant, Autism Support Assistant, Therapy Assistant, Attendant, Caregiver or Rehabilitation Assistant.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Search "disability support worker jobs in Pakistan" and you will find almost nothing, then conclude the field is not hiring. That conclusion is wrong, and the reason is boring: <strong>the job exists here under different names.</strong></p>

<p>This guide gives you the names that actually appear in Pakistani advertisements, the law that creates much of the demand, and the single largest employer in the field, which most candidates never think to approach.</p>

<h2>The titles this job is actually advertised under</h2>

<p>Search these instead, and search them separately:</p>

<ul>
    <li><strong>Special Education Teacher</strong> and <strong>Special Educator</strong> &mdash; the most common government titles</li>
    <li><strong>Learning Support Assistant</strong> and <strong>Inclusive Education Assistant</strong> &mdash; mainstream schools with integrated pupils</li>
    <li><strong>Autism Support Assistant</strong> and <strong>Shadow Teacher</strong> &mdash; usually one-to-one with a single child</li>
    <li><strong>Therapy Assistant</strong>, <strong>Speech Therapy Assistant</strong> and <strong>Occupational Therapy Assistant</strong> &mdash; clinic and rehabilitation centre roles</li>
    <li><strong>Attendant</strong> and <strong>Caregiver</strong> &mdash; the usual titles for home-based disability support</li>
    <li><strong>Rehabilitation Assistant</strong> and <strong>Community Inclusion Officer</strong> &mdash; NGO and programme roles</li>
</ul>

<p>These are not interchangeable. A shadow teacher post and an attendant post ask for different qualifications and pay differently, even though both are described as supporting a disabled person.</p>

<img src="/public/storage/blogs/disability-support-worker-jobs-pakistan-wheelchair.jpg" alt="Support worker assisting a wheelchair user in Pakistan" />

<h2>A statutory quota sits behind much of this sector</h2>

<p>Pakistan has required employers to hire disabled people since the <strong>Disabled Persons (Employment and Rehabilitation) Ordinance 1981</strong>. The original share was one per cent. Punjab raised its share to <strong>three per cent</strong> by amendment in 2015; the other provinces generally sit at two.</p>

<p>The Punjab <a href="https://crpd.punjab.gov.pk/" target="_blank" rel="noopener nofollow">Council on Rights of Persons with Disabilities</a>, the official provincial body, states the position plainly: "Any person with disabilities can get employment under 3% quota in Public or Private Sector", and confirms the quota applies to private factories and companies as well as government. Where an establishment does not employ disabled people, the law requires it to pay into the Disabled Persons Rehabilitation Fund the sum it would have paid in wages.</p>

<p>Two things follow for a job seeker. If you are yourself a person with a disability, the quota is a route, and the Council operates a management information system and a services app for registration. If you are looking for work <em>supporting</em> disabled people, the quota and the institutions built around it are a large part of why those posts exist.</p>

<p>Enforcement is the weak point. Compliance with the quota has been repeatedly criticised and litigated, so treat it as a right worth asserting rather than a process that runs itself.</p>

<h2>The largest employer is one most candidates never approach</h2>

<p>The <strong>Special Education Department, Government of the Punjab</strong> runs a network of institutions that dwarfs the private sector in this field. Its own published figures give roughly <strong>118 specialised institutions</strong>:</p>

<ul>
    <li><strong>45</strong> for hearing impaired children</li>
    <li><strong>36</strong> for children described as slow learners</li>
    <li><strong>18</strong> for visually impaired children</li>
    <li><strong>14</strong> for mentally challenged children</li>
    <li><strong>5</strong> for physically disabled children</li>
</ul>

<p>Every one of those institutions needs teaching, therapy and support staff. The department publishes vacancies on its own <a href="https://sed.punjab.gov.pk/jobs" target="_blank" rel="noopener nofollow">jobs page</a>, and recruitment has included teaching intern posts against vacant positions. Government recruitment is slower and more paperwork-heavy than a private centre, and it pays on a published scale with a pension, which is rare in this sector.</p>

<p>Check the department's page directly rather than waiting for a post to reach a job board. Government vacancies frequently close before aggregators index them.</p>

<h2>Education settings versus home-based support</h2>

<img src="/public/storage/blogs/disability-support-worker-jobs-pakistan-learning-support.jpg" alt="Learning support assistant working with a child in a Pakistani classroom" />

<p><strong>Education and therapy settings</strong> give you fixed hours, a team, supervision and a term structure. They usually want a relevant qualification: special education, psychology, speech and language therapy, occupational therapy or social work. Pay follows a scale in government institutions.</p>

<p><strong>Home-based disability support</strong> is closer to personal care work. You are in someone's house, often alone, and the job is personal care, mobility, routine and communication. Qualifications matter less; reliability and boundaries matter more. Pay is negotiated rather than scaled.</p>

<p>If the post is home-based and a family is employing you directly, read our <a href="/blog/personal-care-assistant-jobs-in-pakistan">personal care assistant guide</a> before you agree terms. In Punjab that arrangement carries specific legal entitlements, including a written letter of employment and a bar on the employer keeping your CNIC.</p>

<h2>The specialist end, described honestly</h2>

<p>Applied behaviour analysis, speech and language therapy and occupational therapy roles are advertised in this field and pay better than support work. They are also genuinely credentialed. An ABA role expects registered behaviour technician training or equivalent coursework and supervised hours; a therapy role expects the degree.</p>

<p>Short online courses do not substitute. If you want that end of the field, treat it as an education decision with a timeline, not a job you can talk your way into. Support and assistant posts are the realistic entry point, and experience in them counts when you do apply to train.</p>

<h2>NGOs and programme work</h2>

<p>Disability-focused organisations recruit for community development, inclusion, livelihoods and programme roles rather than direct support. The <strong>National Disability and Development Forum</strong> describes itself as "a non-governmental, non-profit organization working across rural districts of Sindh, Pakistan", with a focus on "empowering persons with disabilities and other marginalized communities", and maintains a careers page.</p>

<p>Be careful with dates on pages like this. The vacancy currently shown there is dated 7 July 2026 and has closed. NGO career pages are often left in place between rounds, so confirm a closing date before you invest time in an application.</p>

<p>One distinction worth holding on to: a job platform that helps disabled people find employment is not the same thing as an employer of disability support workers. Both are useful, for different people.</p>

<h2>Pay, and why no figure is quoted here</h2>

<p>Government special education posts pay on published scales, which you can check against the advertised post number. Everything else in this field, from private therapy centres to home-based attendant work, is negotiated individually, and no dependable published range exists. Figures circulating online come from individual adverts and are not republished here.</p>

<p>What moves the number is the setting, whether a qualification is required, whether the post is one-to-one with a child, and whether it involves personal care.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do disability support worker jobs exist in Pakistan?</h3>
<p>Yes, but rarely under that title. Search Special Education Teacher, Learning Support Assistant, Autism Support Assistant, Therapy Assistant, Attendant and Caregiver instead.</p>

<h3>What is the disability employment quota in Pakistan?</h3>
<p>The Disabled Persons (Employment and Rehabilitation) Ordinance 1981 set it at one per cent. Punjab raised its share to three per cent in 2015; other provinces are generally at two. It covers public and private employers.</p>

<h3>Who is the biggest employer of disability support staff?</h3>
<p>Government. The Punjab Special Education Department alone reports roughly 118 specialised institutions, including 45 for hearing impaired and 36 for slow learner pupils, and advertises on its own jobs page.</p>

<h3>Do I need a degree for disability support work?</h3>
<p>For education and therapy posts, usually yes: special education, psychology, speech therapy, occupational therapy or social work. Home-based attendant and caregiver posts weigh reliability and experience more heavily.</p>

<h3>Can I work in autism support without ABA training?</h3>
<p>As a classroom or shadow assistant, often yes. As an ABA practitioner, no. That role expects registered behaviour technician training or equivalent coursework with supervised hours, and short online courses are not a substitute.</p>

<h3>Is home-based disability support the same as personal care?</h3>
<p>In practice, largely yes: personal care, mobility, routine and communication in someone's home. If a family employs you directly in Punjab, the Domestic Workers Act entitlements apply to the arrangement.</p>

<h3>How much do disability support workers earn in Pakistan?</h3>
<p>Government posts follow published scales tied to the advertised grade. Private centres and home-based work are negotiated individually, and no reliable published range exists for either.</p>

<h3>Where should I look for these jobs?</h3>
<p>The Punjab Special Education Department jobs page, therapy and rehabilitation centres directly, home healthcare providers for attendant work, and disability-focused NGOs for programme roles. Government vacancies often close before job boards index them.</p>

<h2>People Also Search For</h2>

<h3>Special education jobs in Punjab</h3>
<p>Advertised by the Special Education Department across roughly 118 institutions, on published government pay scales.</p>

<h3>Autism support assistant jobs Pakistan</h3>
<p>Usually one-to-one classroom or shadow posts. Distinct from ABA practitioner roles, which require formal training.</p>

<h3>Learning support assistant jobs</h3>
<p>Mainstream schools with integrated pupils. Often the most accessible entry point into education-based disability support.</p>

<h3>Disability employment quota in Pakistan</h3>
<p>Three per cent in Punjab since 2015, generally two per cent elsewhere, under the 1981 Ordinance, with a rehabilitation fund levy for non-compliance.</p>

<h3>Therapy assistant jobs Pakistan</h3>
<p>Speech, occupational and physiotherapy centres recruit assistants. The assistant post is an entry point; the therapist post needs the degree.</p>

<h3>Jobs for disabled persons in Pakistan</h3>
<p>The quota route, through the Punjab Council on Rights of Persons with Disabilities registration system and its services app.</p>

<h3>Shadow teacher jobs in Pakistan</h3>
<p>One-to-one classroom support for a single pupil, most common in private schools in the larger cities.</p>

<h3>Rehabilitation assistant jobs Pakistan</h3>
<p>Centre-based work alongside therapists, and NGO programme roles in community inclusion and livelihoods.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/personal-care-assistant-jobs-in-pakistan">Personal Care Assistant Jobs in Pakistan</a> &mdash; what a household legally owes you for home-based support work.</li>
    <li><a href="/blog/elderly-care-assistant-jobs-in-pakistan">Elderly Care Assistant Jobs in Pakistan</a> &mdash; the free NAVTTC qualification and dementia work.</li>
    <li><a href="/blog/home-healthcare-assistant-jobs-in-pakistan">Home Healthcare Assistant Jobs in Pakistan</a> &mdash; where the clinical line sits and how to verify a licence.</li>
    <li><a href="/blog/teacher-jobs-in-pakistan">Teacher Jobs in Pakistan</a> &mdash; the mainstream education route, including government recruitment.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://sed.punjab.gov.pk/jobs" target="_blank" rel="noopener nofollow">Special Education Department, Government of the Punjab, jobs</a></li>
    <li><a href="https://crpd.punjab.gov.pk/" target="_blank" rel="noopener nofollow">Punjab Council on Rights of Persons with Disabilities</a></li>
</ul>

<p><strong>Note:</strong> pay, qualifications and closing dates are set by each employer, not by JobGader. Confirm the details on the employer's own page before applying.</p>
HTML;
    }
}
