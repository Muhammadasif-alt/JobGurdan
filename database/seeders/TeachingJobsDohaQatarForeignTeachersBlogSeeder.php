<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Teaching Jobs in Doha, Qatar for Foreign Teachers: Salary, Benefits, Requirements, and How to Apply"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. The Indeed button is dropped.
 * 2. Qatar Academy Doha read: QAR 165,708 to 213,204 (US$ 45,400 to 58,412) covers basic salary plus transport allowance;
 *    two years' experience; the August 2026 post has passed. The monthly split is our arithmetic.
 * 3. Doha Academy says it can help facilitate the Early Career Teacher (ECT) programme; the brief's "induction" is
 *    corrected. Its ICT post has no date, so the guide says to check it is still open.
 * 4. DESS: accommodation, utilities, flights and health insurance are for sponsored staff only, and it never takes
 *    payment from applicants. ASD and the Qatar government and MOFA pages read and confirmed.
 *
 * Posters are the owner's (blog-356, 31 Oct 10.12 to 10.15 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class TeachingJobsDohaQatarForeignTeachersBlogSeeder extends Seeder
{
    public const SLUG = 'teaching-jobs-doha-qatar-foreign-teachers';

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
                'title' => 'Teaching Jobs in Doha, Qatar for Foreign Teachers: Salary, Benefits, Requirements, and How to Apply',
                'excerpt' => 'What Doha schools really ask of foreign teachers, one school\'s published QAR 165,708 to 213,204 salary, which benefits are for sponsored staff only, plus visas, attestation and safeguarding checks.',
                'content' => $content,
                'featured_image' => 'blogs/teaching-jobs-doha-qatar-foreign-teachers.jpg',
                'tags' => 'teaching jobs in doha qatar for foreign teachers, international school teacher jobs in doha, teacher salary in doha, teaching jobs in doha with visa sponsorship, british curriculum jobs doha, american school of doha jobs, qatar degree attestation teachers, newly qualified teacher jobs qatar',
                'meta_title' => 'Teaching Jobs in Doha, Qatar for Foreign Teachers',
                'meta_description' => 'Teaching jobs in Doha for foreign teachers: school requirements, a published salary range, housing, sponsorship, attestation and how to apply.',
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
<p><strong>For teaching jobs in Doha as a foreign teacher, make three checks first: does your degree match the subject or age group, does the school accept your teaching qualification, and does it recruit from overseas for that post?</strong> Requirements differ by school. Qatar Academy Doha asks for an education or teaching degree for elementary posts, a subject degree for secondary, a teaching certification, and at least two years of experience. It publishes pay of QAR 165,708 to 213,204 a year, which is rare and useful, but it is one school's package, not a Doha average. We checked these pages on 31 October 2026.</p>

<h2>Which Doha Schools Should You Approach?</h2>

<p><strong>Qatar Academy Doha: IB teaching.</strong> Its recruitment page is a good benchmark for experienced applicants. The one start date it mentions, August 2026, has already passed, so check for the next intake rather than applying to an old post. Say exactly which IB programme, subject and age range you have taught. Official page: <a href="https://qad.edu.qa/working-at-qad/" rel="noopener">Qatar Academy Doha: Working at QAD</a>.</p>

<p><strong>Doha English Speaking School: British curriculum.</strong> DESS invites UK-trained teachers to see its vacancies. It marks accommodation, utilities, annual flights to the UK and private health insurance as "available for sponsored staff only", and it also employs people in unsponsored roles. So if you apply for a teaching assistant post, ask whether it is sponsored and whether you must already live in Qatar. Official page: <a href="https://www.dess.org/working-for-dess" rel="noopener">DESS: Working for DESS</a>.</p>

<p><strong>American School of Doha: overseas faculty.</strong> The school says HR supports new faculty through pre-employment, immigration, relocation and induction once an offer is accepted, and that overseas hires are housed in a purpose-built compound. For an American curriculum post, use grade levels and give an example of how you assessed progress against a learning standard. Official page: <a href="https://asd.sch.qa/careers" rel="noopener">American School of Doha: Careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teaching-jobs-doha-qatar-foreign-teachers-2.jpg"
         alt="Teacher with a tablet in a Doha classroom while pupils raise their hands, with the Qatar flag and city skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Match Your Qualifications to the Vacancy</h2>

<p>For primary posts, name the year groups and subjects you have taught. For kindergarten, a short example of introducing a routine or observing language development beats a list of activities. For secondary maths, science or English, give the highest level taught and whether you prepared pupils for external exams.</p>

<p>Doha Academy shows how specific a post can be. Its ICT teacher opening requires a computer-related degree, a PGCE or B.Ed. with QTS, and at least three years of experience. The page shows no date, so ask whether it is still open. The same school says it can help facilitate the Early Career Teacher programme for newly qualified teachers, but that does not remove the three years from this post. Official page: <a href="https://dohaacademy.sch.qa/careers/career-openings/" rel="noopener">Doha Academy: Career openings</a>.</p>

<p>For French, PE or special education, choose evidence that fits: progress in spoken French, safe participation in a mixed-ability lesson, or an adaptation that helped a learner reach the same task. University teaching has its own recruitment, so use the department's academic vacancy rather than a school form.</p>

<h2>Teacher Salary in Doha and What Housing Adds</h2>

<p>Qatar Academy Doha publishes <strong>QAR 165,708 to 213,204 a year</strong> (US$ 45,400 to 58,412) for basic salary plus transport allowance. Divided by twelve, that is QAR 13,809 to 17,767 a month. Transport is already included, so do not add it again. The school also lists:</p>

<ul>
<li>Fully furnished accommodation in a managed compound</li>
<li>Electricity, water and garbage services paid</li>
<li>Worldwide medical cover and a dental plan</li>
<li>Annual economy flights for you and eligible dependants</li>
<li>Free tuition at Qatar Foundation schools for up to three eligible children</li>
</ul>

<p>Housing can change the value of two similar offers. Doha Academy's ICT post describes a furnished two-bedroom apartment with a shared kitchen and lounge, so ask who shares it and whether family housing exists. If you are offered an allowance, confirm whether it sits on top of the quoted salary.</p>

<h2>Documents, Visa Sponsorship and Safeguarding</h2>

<p>The Qatar government says all newcomers working in Qatar need a Work Residence Permit, for which they need a Qatari employer, and that the employer typically handles the paperwork. A reposted advert promising "free visa and accommodation" proves neither, so ask HR who arranges each step.</p>

<p>Prepare your degree, transcript, teaching certificate, dated experience letters and references. Qatar's Ministry of Foreign Affairs says a bachelor's degree issued abroad must be submitted with its transcript, and the document needs a chain of seals ending with Qatari missions abroad or foreign missions accredited to the Consular Affairs Department. The fee listed there is QAR 100. Confirm the sequence for the country that issued your degree before you pay for stamps. If you are a Pakistani, Indian or Filipino teacher who studied elsewhere, tell HR where the degree was awarded.</p>

<p>Expect safeguarding checks. DESS runs child protection, safeguarding and reference checks, and Qatar Academy Doha wants a clear criminal record check attested by a notary in the issuing country and by the Qatari Embassy. Start this early and ask which dates the school accepts.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/teaching-jobs-doha-qatar-foreign-teachers-3.jpg"
         alt="Teacher holding books on the Doha waterfront with the Qatar flag, with classroom and school building scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply and Prepare for Interview</h2>

<p>Open with your subject, credential, years of classroom responsibility and earliest start date. Separate supervised placements from paid teaching. If you taught in English, name the subject and level, because speaking English is not the same as being qualified to teach it.</p>

<p>Bring one short lesson example with evidence of learning. A maths teacher might describe how pupils confused area with perimeter, how two rectangles exposed the mistake and what the exit question showed. Keep pupil names and faces out of it.</p>

<h2>What to Ask Before Signing</h2>

<p>Ask which salary step your experience earns and whether years before certification count. With children, ask for a written breakdown per child rather than a general promise. A teaching couple should ask whether they get one housing allocation or two. Check the induction reporting date against the first pupil day, since it affects flights and ending your current contract.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can newly qualified teachers apply with no experience?</h3>
<p>Where the vacancy accepts them. Doha Academy says it can help facilitate the Early Career Teacher programme, while Qatar Academy Doha asks for at least two years. Do not count placement months as years of independent teaching.</p>

<h3>Do teachers need to pay a recruitment fee?</h3>
<p>DESS says it will never request or accept payment or a security deposit from applicants at any stage. Treat a fee demand in any school's name as a warning, and verify it through the school's own website.</p>

<h3>Is tuition for my children included?</h3>
<p>It depends on the school. Qatar Academy Doha covers up to three eligible children at Qatar Foundation schools; others differ, so check seats and any uncovered fees.</p>

<h3>Who arranges my visa?</h3>
<p>The Qatar government says the employer typically handles the Residence Permit paperwork. Ask HR to confirm each step.</p>

<h3>Does urgent hiring mean I can skip document checks?</h3>
<p>No. Confirm the start date with HR and explain which documents are ready.</p>

<h2>Bottom Line</h2>

<p>Apply where your qualifications match the subject and age group, and before moving, get the salary, housing, sponsorship and safeguarding requirements in writing from the school that will employ you.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://qad.edu.qa/working-at-qad/" rel="noopener">Qatar Academy Doha: Working at QAD</a></li>
<li>Doha Academy: <a href="https://dohaacademy.sch.qa/careers/" rel="noopener">Careers</a> and <a href="https://dohaacademy.sch.qa/careers/career-openings/" rel="noopener">Career openings</a></li>
<li><a href="https://www.dess.org/working-for-dess" rel="noopener">Doha English Speaking School: Working for DESS</a></li>
<li><a href="https://asd.sch.qa/careers" rel="noopener">American School of Doha: Careers</a></li>
<li><a href="https://portal.www.gov.qa/wps/wcm/connect/hukoomi%2Bweb%2Bcontent/hukoomi/topics/visas%2Band%2Bofficial%2Bdocuments/residenceandworkpermits" rel="noopener">Qatar Government: Residence and work permits</a></li>
<li><a href="https://mofa.gov.qa/en/eservices/attestation/academic-certificates" rel="noopener">Qatar Ministry of Foreign Affairs: Academic certificates</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/waiter-jobs-doha-qatar-requirements-interview-tips">Waiter Jobs in Doha</a>, other work in the same city.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan</a>, routes overseas.</li>
    <li><a href="/blog/gulf-vs-europe-for-work">Gulf vs Europe for Work</a>, comparing where to go.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
