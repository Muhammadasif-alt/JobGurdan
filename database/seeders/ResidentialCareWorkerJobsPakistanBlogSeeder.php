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
 * Residential care work in Pakistan, checked on 29 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. All four Indeed links are gone, along with every passage reporting what
 *     "current listings" show.
 *  2. Two pay claims dropped. The brief quoted Rs 25,000 to Rs 30,000 for a
 *     Lahore night attendant post and "up to Rs 35,000" for a clinical
 *     assistant post. Both are aggregator republications, and the first is the
 *     same band the elderly care brief carried, which suggests one recycled
 *     listing rather than two observations.
 *  3. The brief attributed a specific current vacancy to Rising Sun Institute
 *     for Special Children. The institute is real and publishes a jobs page,
 *     but that listing could not be confirmed on its own site, so it is named
 *     as an employer rather than as having a live opening.
 *  4. Holistic Healthcare Services could not be verified at all. Dropped.
 *  5. The SOS Children's Villages vacancy checked out and is kept, with the
 *     application instruction quoted from the careers page.
 *  6. Six FAQs and no People Also Search For block. Now eight of each.
 *
 * The spine the brief missed: residential care in Pakistan is institutional,
 * and the largest institutions are statutory. The Punjab Destitute and
 * Neglected Children Act 2004 requires the Government to establish the Child
 * Protection and Welfare Bureau, which runs the residential Child Protection
 * Institutions and recruits through the Punjab Public Service Commission. That
 * is a different employer, a different hiring route and a different set of
 * obligations from the household work the sibling guides cover.
 *
 * Pakistan Bait-ul-Mal's Sweet Homes were considered and left out: the figures
 * in circulation trace back to news reports, and the programme page on
 * pbm.gov.pk returns 404.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class ResidentialCareWorkerJobsPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'residential-care-worker-jobs-in-pakistan';

    public const APPLY_URL = 'https://cpwb.punjab.gov.pk/jobs';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'Residential Care Employers, Pakistan (Aggregated)'],
                ['display_reference' => 'pk-residential-care-worker-aggregated', 'type' => 'Government']
            );
            $location = Location::firstOrCreate(
                ['name' => 'Pakistan'],
                ['area' => 'Nationwide', 'country' => 'Pakistan']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Residential Care Worker, Pakistan Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Shift',
                    'job_type' => 'On-site',
                    'work_hours' => 'Rotating day and night shifts; some posts are residential with accommodation provided',
                    'language' => 'English, Urdu',
                    // Statutory institutions pay on government scales tied to
                    // the advertised post; charities and private providers do
                    // not publish figures at all.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'residential care worker jobs in pakistan, care home jobs lahore, night attendant jobs pakistan, child protection bureau jobs, residential support worker jobs',
                    'meta_description' => 'Residential care posts in Pakistan across statutory child protection institutions, charity-run villages and special education campuses, with the hiring route for each.',
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
                    'title' => 'Residential Care Worker Jobs in Pakistan',
                    'excerpt' => 'Residential care in Pakistan is institutional, not household work. The statutory bodies, charities and special education campuses that run the homes each hire through their own channel, and the Punjab bureau alone has admitted 79,106 children since 2004.',
                    'content' => $content,
                    'featured_image' => 'blogs/residential-care-worker-jobs-pakistan.jpg',
                    'tags' => 'residential care worker jobs in pakistan, care home jobs lahore, night attendant jobs pakistan, child protection bureau jobs, residential support worker jobs, childrens home jobs pakistan, live in care jobs pakistan, warden jobs pakistan',
                    'meta_title' => 'Residential Care Worker Jobs in Pakistan: Who Hires',
                    'meta_description' => 'Residential care worker jobs in Pakistan are institutional, not household work. Who runs the homes, how each one recruits, and what night duty really means.',
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
<p>This is an overview of residential care work with employers across Pakistan. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Residential care means the people you support live where you work. That covers statutory child protection institutions, charity-run children's villages, special education campuses with boarding, and private providers running live-in arrangements. Each recruits through a different channel.</p>
<p>The title "Residential Care Worker" is rare in Pakistani advertisements. The same work appears as Care Assistant, Caregiver, Night Attendant, Warden, House Parent, Residential Attendant or Support Worker.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every other care guide on this site describes someone who travels to the work and goes home afterwards. Residential care is the one where that is not true. <strong>The people you support live where you work, which means the building never closes</strong>, and that single fact changes who employs you, how you are hired, and what your week looks like.</p>

<p>It also explains why the title confuses people. In Pakistan you will rarely see "Residential Care Worker" advertised. You will see Warden, Night Attendant, Care Assistant, House Parent and Caregiver, because the employers here are institutions and institutions use institutional job titles.</p>

<h2>The largest residential employer in Punjab is a statutory body</h2>

<p>Most candidates look for residential care work in the private sector. In Punjab, the biggest operator is the government, and it exists because a law says it must.</p>

<p>The <strong>Punjab Destitute and Neglected Children Act 2004</strong> requires that "the Government shall establish a bureau to be known as the Child Protection and Welfare Bureau". That Bureau, set up in March 2004, runs the residential <strong>Child Protection Institutions</strong>. Its own description of them is worth reading closely if you want to know what the job involves:</p>

<blockquote><p>"For rescued children who cannot be placed in a safe family environment, the Bureau has established standardized alternative care institutions. The Child Protection Institutions (CPIs), managed by the Bureau, provide residence, food, education, healthcare, psychological counseling, skills training, and recreational activities to destitute and neglected children, from the time of admission until their reunification with families."</p></blockquote>

<p>The scale is not small. The Bureau reports that <strong>from 2004 to 2024, a total of 79,106 children have been admitted to CPIs</strong> across its district offices. It also runs the child helpline on <strong>1121</strong>.</p>

<p>Read that service list again as a job description: residence, food, education, healthcare, counselling, skills training, recreation. Residential care staff are involved in all seven, around the clock, which is why these posts are shift posts.</p>

<img src="/public/storage/blogs/residential-care-worker-jobs-pakistan-childrens-home.jpg" alt="Residential care staff supporting children in a Pakistani children's home" />

<h2>Statutory employers do not hire through job boards</h2>

<p>This is the practical difference between residential care and the household care work covered elsewhere on this site, and it is where most applicants lose their chance.</p>

<p>The Bureau advertises on its own <a href="https://cpwb.punjab.gov.pk/jobs" target="_blank" rel="noopener nofollow">jobs page</a>, and its recruitment runs through the <strong>Punjab Public Service Commission</strong>. The adverts are posted there as scanned PDFs, in English and Urdu, and they carry a closing date. There is no rolling application, no walk-in, and no shortcut through a job board.</p>

<p>What that means for you:</p>

<ul>
    <li><strong>Check the source page on a schedule.</strong> A government advert can open and close inside a few weeks, often before an aggregator indexes it.</li>
    <li><strong>Read the advert itself, not a summary of it.</strong> Job blogs routinely republish government adverts with invented salary figures and wrong closing dates.</li>
    <li><strong>Expect paperwork.</strong> Attested certificates, CNIC, domicile and a printed form are normal, and the deadline is for arrival, not for posting.</li>
    <li><strong>Apply through the named route only.</strong> Applications sent directly to a department that has nominated a testing service are usually discarded unread.</li>
</ul>

<p>The trade-off is real: the process is slow and rigid, but these are the posts in this field that come with a published pay scale, a formal grade and, at permanent level, a pension. Almost nothing else in Pakistani care work does.</p>

<h2>Children's residential care: the charity route</h2>

<p><strong>SOS Children's Villages Pakistan</strong> runs family-model residential care, where children live in household groups with a consistent carer rather than in a dormitory. That model is the reason its job titles sound domestic rather than clinical.</p>

<p>Its <a href="https://www.sos.org.pk/Careers" target="_blank" rel="noopener nofollow">careers page</a> currently shows an <strong>Assistant Director (Trainee) Residential</strong> vacancy, and states that candidates must have relevant departmental experience. The application instruction is a single line: "Send us Your Resume at jobs@sos.org.pk with mentioned title of the job in subject".</p>

<p>Two honest notes on that. It is a management trainee post, not a frontline care post, so it is not the entry point most readers of this guide are looking for. And charity career pages are often left up between recruitment rounds, so confirm the vacancy is open before you build an application around it.</p>

<h2>Special education campuses with boarding</h2>

<p><strong>Rising Sun Institute for Special Children</strong> is a Lahore example of the third employer type: a specialist education provider large enough to need residential and extended-hours staff. It operates three campuses in Lahore, including its DHA and Mughalpura sites and the Perveen and Abdul Tawwab Khan campus for visual impairment services, alongside community-based centres and a mobile screening service reaching 26 districts.</p>

<p>It publishes vacancies on its own <a href="https://www.risingsun.org.pk/jobs/" target="_blank" rel="noopener nofollow">jobs page</a> and runs a summer internship programme, which is a realistic way into the sector if you have no experience yet.</p>

<p>Specialist campuses expect you to understand the resident group. If the children are deaf, some sign language is not optional. Our <a href="/blog/disability-support-worker-jobs-in-pakistan">disability support worker guide</a> covers the qualifications and the statutory quota behind this part of the sector.</p>

<h2>Night duty is the job, not an extra</h2>

<img src="/public/storage/blogs/residential-care-worker-jobs-pakistan-night-duty.jpg" alt="Night attendant on duty in a residential care facility in Pakistan" />

<p>A residential facility that closed at night would not be residential. Night posts are therefore a permanent part of the staffing model, not overtime, and in many institutions they are the easiest vacancies to get because fewer people want them.</p>

<p>Night work in a residential setting is genuinely different from day work. You are usually the senior person present, often the only one on your wing, and the decisions you make are unsupervised. Settling residents, responding to calls, assisting with toileting and mobility, reassuring someone who wakes distressed, watching for a change in condition, and knowing when to escalate are all yours alone.</p>

<p>Before you accept a night post, get answers to these, in writing where you can:</p>

<ul>
    <li><strong>Exact start and finish times</strong>, and how many nights in a row.</li>
    <li><strong>Whether sleeping is permitted</strong> during any part of the shift, and whether sleeping hours are paid at the same rate.</li>
    <li><strong>How many staff are on site overnight</strong>, and who else is in the building.</li>
    <li><strong>Who you call in an emergency</strong>, how fast they arrive, and what you are authorised to do before they do.</li>
    <li><strong>Transport at shift end</strong>, which matters a great deal for a shift finishing before dawn.</li>
    <li><strong>Whether the night rate differs</strong> from the day rate, and whether an allowance is included or added.</li>
</ul>

<p>An employer that cannot answer the emergency question clearly is telling you something about how the place is run.</p>

<h2>Live-in and extended shifts in private care</h2>

<p>Private providers run the fourth arrangement: not an institution, but a roster that puts you in someone's home for long blocks. <strong>Care Nest</strong>, which recruits nurses, home care nurses, physiotherapists and caregivers for Islamabad and Rawalpindi, publishes its shift structure as <strong>12-hour day or night shifts, 24/7 live-in care, per-visit or per-procedure work, and part-time</strong>. It describes "market-leading salary packages with night shift and overtime allowances" without publishing a figure, which is normal for this sector.</p>

<p>Live-in work is where hours quietly become unlimited if nobody writes them down. If a private household is employing you directly in Punjab, that is domestic work with specific statutory entitlements, and our <a href="/blog/personal-care-assistant-jobs-in-pakistan">personal care assistant guide</a> sets out what the employer owes you, including a written letter of employment, a weekly day off and a bar on keeping your CNIC.</p>

<h2>What the residents' group changes</h2>

<p>"Residential care" describes a setting, not a population, and the population decides the job:</p>

<ul>
    <li><strong>Children in protective care</strong> &mdash; safeguarding, education and family reunification are central. Expect background checks and a strict reporting culture.</li>
    <li><strong>Children with disabilities</strong> &mdash; communication and specialist routines matter more than personal care technique.</li>
    <li><strong>Older residents</strong> &mdash; mobility, continence, medication timing and dementia awareness dominate. Our <a href="/blog/elderly-care-assistant-jobs-in-pakistan">elderly care assistant guide</a> covers the free NAVTTC qualification for this.</li>
    <li><strong>Adults needing clinical care</strong> &mdash; the line between support and nursing is a legal line, not a matter of confidence. Our <a href="/blog/home-healthcare-assistant-jobs-in-pakistan">home healthcare assistant guide</a> explains where it sits.</li>
</ul>

<p>Do not apply to all four with one CV. An institution reading an application that shows no understanding of its residents will assume you have not read the advert, and it will usually be right.</p>

<h2>Pay, and why no figure is quoted here</h2>

<p>Statutory posts pay on published government scales tied to the advertised grade, which you can check against the post number in the advert itself. Charity and private residential employers negotiate individually and do not publish ranges. Figures circulating on job boards for this work come from single adverts, are frequently stale, and are not republished here.</p>

<p>What actually moves the number is the employer type, whether the post is permanent or contract, the resident group, and how many of your hours are night hours. Ask about the night rate specifically, because in residential work it is often where the real difference sits.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does a residential care worker do in Pakistan?</h3>
<p>Supports people who live in a care setting: personal care, mobility, meals, routines, safety monitoring, companionship and reporting changes in a resident's condition. Because residents live on site, the work runs on rotating day and night shifts.</p>

<h3>What job titles should I search instead?</h3>
<p>Care Assistant, Caregiver, Night Attendant, Warden, House Parent, Residential Attendant and Support Worker. The exact phrase "Residential Care Worker" is rare in Pakistani advertisements.</p>

<h3>Who is the largest residential care employer in Punjab?</h3>
<p>The Child Protection and Welfare Bureau, a statutory body the Punjab Destitute and Neglected Children Act 2004 requires the Government to establish. It runs the residential Child Protection Institutions and reports 79,106 children admitted from 2004 to 2024.</p>

<h3>How do I apply to a government residential institution?</h3>
<p>Through the route named in the advert, usually the Punjab Public Service Commission or a nominated testing service, by the closing date. Adverts appear on the Bureau's own jobs page as PDFs. Applications sent directly to the department are normally not entertained.</p>

<h3>Do I need a nursing degree?</h3>
<p>Not for general residential and attendant posts. Clinical posts inside residential settings do require professional qualifications and valid registration, and that distinction is a legal one rather than a matter of experience.</p>

<h3>Are night shifts common in residential care?</h3>
<p>Yes, and they are permanent posts rather than overtime, because a residential facility is staffed around the clock. They are often the easiest vacancies to obtain, and they carry more solo responsibility than day shifts.</p>

<h3>Can beginners get residential care jobs?</h3>
<p>General support and night attendant posts are realistic entry points, and internships at specialist campuses are another. Posts involving children in protective care or clinical duties expect checks, qualifications or prior experience.</p>

<h3>What should I confirm before accepting a residential post?</h3>
<p>Exact shift times, whether sleeping is permitted and paid, overnight staffing levels, the emergency escalation route, transport at shift end, and whether the night rate differs from the day rate. Get the answers in writing where you can.</p>

<h2>People Also Search For</h2>

<h3>Care home jobs in Lahore</h3>
<p>Mostly advertised as attendant, caregiver or warden posts by special education campuses and private providers rather than under a care home label.</p>

<h3>Night attendant jobs in Pakistan</h3>
<p>Permanent residential posts, not overtime. Confirm sleeping arrangements, overnight staffing and the emergency escalation route before accepting.</p>

<h3>Child Protection and Welfare Bureau jobs</h3>
<p>Advertised on the Bureau's own jobs page and recruited through the Punjab Public Service Commission, with a fixed closing date and no walk-in route.</p>

<h3>SOS Children's Villages Pakistan careers</h3>
<p>Family-model residential care. The current listing is an Assistant Director (Trainee) Residential post, applied for by email with the job title in the subject line.</p>

<h3>House parent jobs in Pakistan</h3>
<p>The family-model title: a consistent carer living with a household group of children rather than staffing a dormitory shift.</p>

<h3>Live in caregiver jobs Pakistan</h3>
<p>Offered by private home care providers as 24/7 arrangements. In Punjab, direct employment by a household carries statutory entitlements.</p>

<h3>Warden jobs in Pakistan</h3>
<p>The usual institutional title for supervising a residential facility, common in government and boarding education adverts.</p>

<h3>Residential support worker jobs</h3>
<p>The closest international equivalent of this title. In Pakistan the work is advertised under attendant, caregiver and support titles instead.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/home-healthcare-assistant-jobs-in-pakistan">Home Healthcare Assistant Jobs in Pakistan</a> &mdash; where the clinical line sits and how to verify a licence.</li>
    <li><a href="/blog/elderly-care-assistant-jobs-in-pakistan">Elderly Care Assistant Jobs in Pakistan</a> &mdash; the free NAVTTC qualification and dementia work.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-pakistan">Personal Care Assistant Jobs in Pakistan</a> &mdash; what a household legally owes you for home-based work.</li>
    <li><a href="/blog/disability-support-worker-jobs-in-pakistan">Disability Support Worker Jobs in Pakistan</a> &mdash; the real job titles and the statutory employment quota.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://cpwb.punjab.gov.pk/jobs" target="_blank" rel="noopener nofollow">Child Protection and Welfare Bureau, Punjab, jobs</a></li>
    <li><a href="https://cpwb.punjab.gov.pk/child_protection_institutes" target="_blank" rel="noopener nofollow">Child Protection and Welfare Bureau, Child Protection Institutes</a></li>
    <li><a href="https://www.sos.org.pk/Careers" target="_blank" rel="noopener nofollow">SOS Children's Villages Pakistan careers</a></li>
    <li><a href="https://www.risingsun.org.pk/jobs/" target="_blank" rel="noopener nofollow">Rising Sun Institute for Special Children jobs</a></li>
    <li><a href="https://carenest.pk/careers.html" target="_blank" rel="noopener nofollow">Care Nest recruitment, Islamabad and Rawalpindi</a></li>
</ul>

<p><strong>Note:</strong> pay, qualifications and closing dates are set by each employer, not by JobGader. Confirm the details on the employer's own page before applying.</p>
HTML;
    }
}
