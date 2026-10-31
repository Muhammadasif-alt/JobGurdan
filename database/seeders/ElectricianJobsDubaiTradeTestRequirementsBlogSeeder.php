<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Electrician Jobs in Dubai, UAE: Latest Vacancies, Salary, Requirements, and How to Apply"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. All Indeed material is dropped, including the AED 2,414 salary.
 * 2. The Accor link carried a job ID, so the guide links the careers site and gives REF123019I as text.
 * 3. Duserve is left out: the post is dated 10 March 2026, its email domain differs from its website, and it asks for
 *    passport and visa copies by email. Told to the owner.
 * 4. Klima Global says "UAE transferable visa preferred" and lists the practical task as "if required"; the phrase "trade
 *    test" is not on its page, so the guide treats this as one employer's process, not a Dubai exam.
 * 5. DEWA's enrolment page says only that contractors and consultants use it to access DEWA online services, so the FAQ
 *    is limited to that. The brief's u.ae links return 404; live pages used, and Article 6(4) read in the Labour Law PDF.
 *
 * Posters are the owner's (blog-356, 31 Oct 10.03 to 10.09 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class ElectricianJobsDubaiTradeTestRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'electrician-jobs-dubai-trade-test-requirements';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Electrician Jobs in Dubai, UAE: Latest Vacancies, Salary, Requirements, and How to Apply',
                'excerpt' => 'Real Dubai electrician requirements from a contractor and a hotel, what a practical trade test involves and how to prepare, plus pay questions, work permits and how to apply from abroad.',
                'content' => $content,
                'featured_image' => 'blogs/electrician-jobs-dubai-trade-test-requirements.jpg',
                'tags' => 'electrician jobs in dubai uae, electrician trade test dubai, electrician salary in dubai uae, electrical helper jobs in dubai, maintenance electrician jobs in dubai, mep electrician jobs dubai, hotel electrician jobs dubai, electrician jobs dubai from pakistan',
                'meta_title' => 'Electrician Jobs in Dubai: Trade Test and Requirements',
                'meta_description' => 'Electrician jobs in Dubai: real employer requirements, how to prepare for a practical trade test, pay questions, work permits and applying from abroad.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To apply for electrician jobs in Dubai, match your trade certificate and experience to the equipment in the advert.</strong> A hotel maintaining guest rooms and a contractor wiring control panels want different proof. Klima Global, for example, asks for two to four years in low-voltage installation and panel wiring and may set a practical task; a Downtown Dubai hotel asks for five years of commercial experience. No official average salary exists, and UAE law sets no general minimum wage, so judge each written offer. Your employer must hold a work permit for you before you start. We checked these sources on 31 October 2026.</p>

<h2>Dubai Electrician Vacancies to Check</h2>

<p><strong>Commercial and industrial projects: Klima Global.</strong> This Dubai field role wants an electrical ITI or diploma, or equivalent trade certification, and two to four years in commercial or industrial low-voltage installations and panel wiring. Two years of residential lighting alone may not fit. It lists visa, medical insurance, paid leave and training among the benefits, but also says "UAE transferable visa preferred", so ask directly whether it will recruit from abroad before you make plans. Official page: <a href="https://www.klimaglobal.com/career-posts/electrician-dubai-uae/" rel="noopener">Klima Global: Electrician, Dubai</a>.</p>

<p><strong>Hotel maintenance: Mövenpick Hotel Apartments Downtown Dubai.</strong> Accor's post (reference REF123019I) is full-time and permanent, asks for electrician certification and five years of verifiable commercial experience, prefers a high school diploma and lists fully furnished accommodation. If your five years were in construction, show where you maintained or repaired equipment, not only where you installed it. Ask whether housing is shared and whether anything is deducted for it. Official page: <a href="https://careers.accor.com/" rel="noopener">Accor Careers</a>, search for the reference.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-dubai-trade-test-requirements-2.jpg"
         alt="Electrician in a yellow hard hat testing a distribution panel with the Dubai skyline behind, with tools and drawings in smaller scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What to Expect in an Electrician Trade Test</h2>

<p>There is no single Dubai trade test for every job; it depends on the employer. Klima Global's steps are CV screening, a short technical call or quiz, an interview with the project or service team, and a practical panel wiring or termination task if required. Ask which skills will be assessed, what equipment is provided and whether to bring certificates or protective gear.</p>

<p>Prepare around three things:</p>

<ul>
<li><strong>Reading a drawing.</strong> Practise finding the revision, component designations and terminal references on a sample schematic, and explain how you would report a mismatch between the drawing and the panel.</li>
<li><strong>Safe decisions.</strong> If asked what you would do when isolation cannot be confirmed, say you would stop and refer it to the authorised supervisor. Practise only on a de-energised training rig under qualified supervision.</li>
<li><strong>Writing a handover.</strong> Record the fault, what you saw, which checks you were authorised to do and what is still outstanding. Separate a suspected cause from one a test has proved.</li>
</ul>

<p>Here is a scenario for a maintenance interview: a hotel guest reports that the lights have failed twice. How you gather the room number, the timing and the previous work order says more than a guessed diagnosis. Explain how you would keep the duty engineer informed and pass the open issue to the next shift.</p>

<h2>Which Role Fits Your Experience?</h2>

<p><strong>No experience.</strong> Start with electrical helper or assistant roles that clearly accept trainees, and list workshop practice separately from paid site work. Ask who supervises helpers and which tasks they may do. An advert offering induction can still require a qualified electrician.</p>

<p><strong>Building and MEP.</strong> Say which stage you worked on: containment, installation or handover. For MEP, name the mechanical and plumbing teams you coordinated with.</p>

<p><strong>Senior and foreman.</strong> Give your crew size and one example of planning or reviewing another technician's work. Hospitals, plants and residential callouts have different access rules, so ask who authorises shutdowns.</p>

<h2>Pay, Accommodation and Working Hours</h2>

<p>We do not repeat salary averages from job sites, which mix helpers, maintenance staff and foremen. Compare basic pay, allowances and contracted hours separately. A furnished room has real value, but it does not add to the cash you can send home. Ask for a sample rota, especially for several sites or night shutdowns, and check transport after the last shift, overtime and the actual payday. Do not budget around overtime nobody has guaranteed.</p>

<h2>How to Apply From Abroad</h2>

<p>Prepare a short CV, your trade certificate and dated experience letters. If you are from Pakistan, India or Nepal without UAE experience, name the country, site and systems you worked on. Where an advert says commercial experience but does not mention the UAE, ask whether overseas references count.</p>

<p>Three rules protect you:</p>

<ul>
<li>You need a job offer in writing. It must be in Arabic and English, and you can ask for a third language such as Urdu, Hindi or Nepalese.</li>
<li>Article 6 of the UAE Labour Law prohibits employers from charging workers recruitment and employment fees and costs, directly or indirectly.</li>
<li>The UAE "strictly prohibits" working on a visit or tourist visa, paid or not, so do not accept a productive trial shift while waiting for the permit.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/electrician-jobs-dubai-trade-test-requirements-3.jpg"
         alt="Electrician in an orange vest with arms crossed beside a toolbox on the Dubai waterfront, with panel, meter and lighting scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What to Put on Your CV</h2>

<p>A useful entry names the building, the equipment and your own part: "Recorded lighting faults across three occupied office floors, assisted the shift electrician during authorised repairs and updated the maintenance log." Use wording like that only if it is true. A supervisor's name and checkable dates beat a long list of tools you have seen but never used. If asked for project photos, get permission and remove customer details, and never share confidential drawings.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is there one official electrician trade test in Dubai?</h3>
<p>Not that any employer here describes. Klima Global sets a practical task if required, and each employer decides its own process, so ask yours.</p>

<h3>Is DEWA registration compulsory for every applicant?</h3>
<p>DEWA's enrolment service is described as giving contractors and consultants access to its online services, not as a personal licence for job applicants. Ask your employer which approval your duties need.</p>

<h3>What does "free visa" mean in a vacancy?</h3>
<p>It is advertising language. Ask which employer arranges the permit, and do not pay for recruitment or book travel on the strength of it.</p>

<h3>Can I apply for part-time electrician jobs?</h3>
<p>MoHRE has a part-time permit, but do not assume permission to work for one company covers paid callouts for another. Check the permit first.</p>

<h3>Do I have to pay for my visa?</h3>
<p>Article 6 of the Labour Law bars employers from charging workers recruitment and employment costs.</p>

<h2>Bottom Line</h2>

<p>Choose a vacancy whose equipment and responsibilities match your background, prepare a supervised practical example, and verify the employer, permit and written pay before you commit.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.klimaglobal.com/career-posts/electrician-dubai-uae/" rel="noopener">Klima Global: Electrician, Dubai</a> and <a href="https://careers.accor.com/" rel="noopener">Accor Careers</a>, reference REF123019I</li>
<li><a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/work-permits" rel="noopener">UAE Government: Work permits</a>, <a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/preparing-to-work" rel="noopener">Preparing to work</a> and <a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/Job-offers-and-the-employment-process" rel="noopener">Job offers</a></li>
<li><a href="https://assets.u.ae/api/public/content/eb65a60bbff949fa912eddd566613014?v=7174ed78" rel="noopener">UAE Labour Law, Federal Decree-Law No. 33 of 2021, Article 6</a></li>
<li><a href="https://dewa.gov.ae/en/about-us/service-guide/general-information/enrollment-of-consultants-contractors" rel="noopener">DEWA: Enrolment of consultants and contractors</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/best-trades-skills-working-gulf-countries">Best Trades and Skills for the Gulf</a>, how electricians compare.</li>
    <li><a href="/blog/driver-jobs-dubai-with-visa">Driver Jobs in Dubai With Visa</a>, the same permit rules.</li>
    <li><a href="/blog/what-is-visa-sponsorship-how-it-works">What Is Visa Sponsorship?</a>, the basics.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
