<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Customs Officer Jobs in Vancouver, British Columbia: How to Apply"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The ten identical buttons became links in the text.
 * 2. GC Jobs notice fetched: CBSA Border Services Officer Trainee (Developmental) FB-02, closing June 24, 2027 at 23:59
 *    Pacific Time, selection process 2026-EA-OIM-FB-02-0010, various locations, accepting an assigned posting anywhere in
 *    Canada is a condition of employment; salary $80,344 to $89,462 (under review), rising to $85,211 to $101,058 as an
 *    FB-03 officer, $800 bilingual bonus; OITP of three weeks online and fifteen weeks in residence with a tax-free
 *    allowance of $525 a week plus lodging and meals; AI tools prohibited in assessments.
 * 3. CBSA qualification page fetched: 18 to start training, no maximum age, persons residing in Canada and Canadian
 *    citizens and permanent residents abroad, preference to veterans, citizens and permanent residents, unrestricted
 *    licence (Class 5, 5F or G), no combination of education and experience accepted instead of the diploma.
 * 4. Selection steps fetched: exam results valid indefinitely, one full year before a rewrite; psychological assessment
 *    of 2 exams and an interview, valid 2 years; CFSC and CRFSC tests before college, no PAL needed.
 * 5. Fitness pages fetched: the PAT is done during in-residence training and is "not a pass or fail exercise"; three
 *    components; PARE no longer required. College page: eight campuses, main campus in Rigaud, Quebec.
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for readers in
 *    Pakistan added.
 *
 * No poster was supplied; the featured image was generated in the house style. The open trainee inventory is a vacancy
 * in EmployerVacancyListingsSeeder. updateOrCreate, so re-running is safe.
 */
class CustomsOfficerJobsVancouverHowToApplyBlogSeeder extends Seeder
{
    public const SLUG = 'customs-officer-jobs-vancouver-how-to-apply';

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
                'title' => 'Customs Officer Jobs in Vancouver, British Columbia: How to Apply',
                'excerpt' => 'How to become a CBSA border services officer from Vancouver: the trainee notice open to 24 June 2027, $80,344 to $89,462 trainee pay, eligibility, exam and firearms steps, 18 weeks of training on a $525 weekly allowance, and the posting rule.',
                'content' => $content,
                'featured_image' => 'blogs/customs-officer-jobs-vancouver-how-to-apply.jpg',
                'tags' => 'customs officer jobs in vancouver british columbia, cbsa jobs vancouver, border services officer requirements, cbsa officer trainee application, customs officer canada training, border services officer salary, cbsa entrance exam, cbsa physical abilities test',
                'meta_title' => 'Customs Officer Jobs in Vancouver: How to Apply to CBSA',
                'meta_description' => 'Customs officer jobs in Vancouver: the CBSA trainee notice open to 24 June 2027, pay, eligibility, exams, firearms courses, training and postings.',
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
<p><strong>Customs officer jobs in Vancouver are Canada Border Services Agency (CBSA) Border Services Officer posts, and the way in is the national Border Services Officer Trainee notice on GC Jobs, which is open until 24 June 2027.</strong> You apply, pass the entrance exam, interview, psychological, medical and security steps, complete firearms safety courses and then the Officer Induction Training Program. Accepting a posting anywhere in Canada is a condition of employment, so naming Vancouver as your preference does not guarantee it. Trainees earn $80,344 to $89,462. This guide uses the GC Jobs notice and CBSA's recruitment pages, checked on 28 October 2026.</p>

<h2>Search for the Correct Job Title</h2>

<p>Search GC Jobs for "Border Services Officer Trainee". The current notice is CBSA Border Services Officer Trainee (Developmental), FB-02, selection process 2026-EA-OIM-FB-02-0010, for various locations across Canada. Officers work at airports, land crossings, marine terminals and postal centres, and the job goes well beyond checking declarations. Keep it separate from customs brokerage and freight forwarding jobs, which are private-sector careers with similar keywords. Source: <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/menu-eng.html" rel="noopener">CBSA: Border services officer recruitment</a>.</p>

<h2>Check Basic Eligibility</h2>

<ul>
    <li>At least 18 years old before you start the Officer Induction Training Program; there is no maximum age.</li>
    <li>A secondary school diploma or equivalency, or a satisfactory score on the Public Service Commission test that CBSA accepts as an alternative.</li>
    <li>A valid, unrestricted licence to drive in Canada, such as Class 5, 5F or G, that lets you drive unsupervised at any time.</li>
    <li>Willingness to accept a posting anywhere in Canada.</li>
    <li>Persons residing in Canada, and Canadian citizens and permanent residents abroad, can apply. Preference goes to eligible veterans, Canadian citizens and permanent residents.</li>
</ul>

<p>CBSA states that no combination of education, training and experience replaces the diploma or test, and graduated or probationary licences do not count. Source: <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/qualification-eng.html" rel="noopener">CBSA: Qualification requirements</a>.</p>

<h2>Know the Pay</h2>

<p>The notice lists trainee pay of $80,344 to $89,462 at FB-02 (under review). After training you are recommended for promotion to Border Services Officer at FB-03, at $85,211 to $101,058, and bilingual officers receive an $800 annual bonus. Source: <a href="https://emploisfp-psjobs.cfp-psc.gc.ca/psrs-srfp/applicant/page1800?poster=2439948&amp;toggleLanguage=en" rel="noopener">GC Jobs: CBSA Border Services Officer Trainee</a>.</p>

<h2>Submit Through GC Jobs</h2>

<p>The notice is an inventory: CBSA draws candidates from it as needs arise, so applying does not guarantee assessment. Answer the screening questions with specific examples rather than job titles, keep a copy of the notice, and use an email address you check, because CBSA communicates by email. Creating multiple accounts, submitting more than one application or giving false information gets you removed.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/customs-officer-jobs-vancouver-how-to-apply-steps.jpg"
         alt="Infographic of the CBSA trainee path: apply on GC Jobs, entrance exam, interview, psychological and medical checks, firearms courses and 18 weeks of training"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Prepare for the Entrance Exam and Interview</h2>

<p>The entrance exam tests reasoning, writing and analytical thinking; its results are valid indefinitely, but if you fail you must wait a full year to rewrite it. The interview then assesses competencies such as dealing with difficult situations, decisiveness, effective interaction, integrity and judgement. Use CBSA's sample questions and prepare examples you can explain under follow-up questions. The notice states that using AI tools in any assessment is cheating and can remove you from the process. Source: <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/steps-etapes-eng.html" rel="noopener">CBSA: Selection steps</a>.</p>

<h2>Understand Medical, Psychological and Security Checks</h2>

<p>The psychological assessment, run by clinical psychologists at offices across Canada, includes two exams and a face-to-face interview, checks how you handle stress and whether you are suited to the duty firearms course, and stays valid for two years. There is also a medical, a security screening and, for bilingual posts, a second-language evaluation. Before you are invited to the college you must pass the Canadian Firearms Safety Course (CFSC) and Canadian Restricted Firearms Safety Course (CRFSC) tests; you do not need a possession and acquisition licence. Answer every screening question honestly.</p>

<h2>Follow Current Physical Fitness Guidance</h2>

<p>CBSA now uses a self-evaluation tool and the Physical Abilities Test (PAT), done during in-residence training. CBSA says the PAT is not a pass or fail exercise but an evaluation; falling below the standard raises your risk of failing or getting injured in the programme. Its three components are physical control (a push-pull sled), ground defence and an agility drill. The older PARE test is no longer required, so do not pay to book one. Sources: <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/fitness-aptitude/prep-eng.html" rel="noopener">CBSA: Preparing for the fitness test</a> and <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/fitness-aptitude/abilities-aptitudes-eng.html" rel="noopener">CBSA: Physical Abilities Test</a>.</p>

<h2>Plan for Training Away From Vancouver</h2>

<p>The Officer Induction Training Program is about three weeks of online learning followed by fifteen weeks in residence. Recruits receive a tax-free allowance of $525 a week for basic expenses, plus lodging in a room with its own bathroom and meals while in residence. The Canada Border Services College has eight campuses, including a Pacific campus, with its main campus in Rigaud, Quebec, so plan for time away and do not sign a Vancouver lease on the strength of a preference. Source: <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/college-eng.html" rel="noopener">CBSA: Canada Border Services College</a>.</p>

<h2>Keep Expectations Realistic</h2>

<p>Because of the number of assessments, the process can take many months. Keep working or studying, keep certificates and contact details current, respond promptly and track what is still outstanding. Passing one stage does not mean you have met the rest.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Only people residing in Canada, or Canadian citizens and permanent residents abroad, can apply, so this is not a route for applicants in Pakistan without Canadian status. Read <a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a> for the Canadian routes that do exist.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is CBSA hiring now?</h3>
<p>Yes. The Border Services Officer Trainee notice is open until 24 June 2027 at 23:59 Pacific Time.</p>

<h3>How much do CBSA trainees earn?</h3>
<p>$80,344 to $89,462 at FB-02, rising to $85,211 to $101,058 as an FB-03 officer.</p>

<h3>Is a university degree mandatory?</h3>
<p>No. A secondary school diploma or the accepted Public Service Commission test is required.</p>

<h3>Can I apply directly from Pakistan?</h3>
<p>Only if you are a Canadian citizen or permanent resident living abroad.</p>

<h2>Bottom Line</h2>

<p>Apply through the official trainee notice before 24 June 2027, prepare accurate documents, complete the firearms safety courses early and accept that CBSA decides your posting. A Vancouver preference is worth stating, but it is not a promise.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://emploisfp-psjobs.cfp-psc.gc.ca/psrs-srfp/applicant/page1800?poster=2439948&amp;toggleLanguage=en" rel="noopener">GC Jobs: CBSA Border Services Officer Trainee (Developmental), FB-02</a></li>
<li><a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/qualification-eng.html" rel="noopener">CBSA: Qualification requirements</a></li>
<li><a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/menu-eng.html" rel="noopener">CBSA: Border services officer recruitment</a></li>
<li><a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/steps-etapes-eng.html" rel="noopener">CBSA: Selection steps</a></li>
<li><a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/fitness-aptitude/prep-eng.html" rel="noopener">CBSA: Preparing for the fitness test</a> and <a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/assessment-evaluation/fitness-aptitude/abilities-aptitudes-eng.html" rel="noopener">Physical Abilities Test</a></li>
<li><a href="https://www.cbsa-asfc.gc.ca/job-emploi/recruitment-recrutement/college-eng.html" rel="noopener">CBSA: Canada Border Services College</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/prison-officer-jobs-london-requirements-selection">Prison Officer Jobs in London</a>, another uniformed public-service career.</li>
    <li><a href="/blog/paramedic-jobs-toronto-training-certification">Paramedic Jobs in Toronto</a>, another Canadian public-service recruitment open now.</li>
    <li><a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a>, the Canadian permit rules.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

HTML;
    }
}
