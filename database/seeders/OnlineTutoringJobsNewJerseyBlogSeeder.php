<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Online Tutoring Jobs in New Jersey: Platforms and Requirements"
 *
 * Checked on 21 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief has ten apply buttons: seven searches on a commercial job board and three platform application pages. No buttons are used, and the job board is not named. The FAQ about the ten links is removed. The state career page from the New Jersey Department of Labor is linked in their place.
 * 2. Tutor.com's applicant FAQ was read: US residence, a valid Social Security number, US work eligibility, at least five hours a week, college sophomore or higher or a four-year degree, subject expertise, English. Added from the page: applicants who need visa sponsorship (H-1B, F-1, OPT) cannot currently be accepted, and a competency exam exists for high-demand subjects. The page gives a Windows computer specification, which replaces the brief's vague line.
 * 3. Wyzant's fee page was not readable from here, so the fee was checked through its help centre text: tutors keep 75% of the posted rate and Wyzant keeps 25%, with an exception for referred new students, and payouts are on the 1st and 15th. The brief's sentence that Wyzant requires tutors to be 18, live in the US and have a Social Security number could not be checked and is removed.
 * 4. Preply was read: no specific certification or teaching experience required; profiles reviewed (up to three business days); 100% commission on a new student's trial lesson; later lessons from 33% down to 18%. The brief said the application asks for strengths, a photo, a video and availability; the page confirms the photo, video and description only, so that is what the guide says.
 * 5. Five FAQs and eight People Also Search For entries are added; the brief had three FAQs once the ten-links question is removed.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class OnlineTutoringJobsNewJerseyBlogSeeder extends Seeder
{
    public const SLUG = 'online-tutoring-jobs-new-jersey';

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
                'title' => 'Online Tutoring Jobs in New Jersey: Platforms and Requirements',
                'excerpt' => 'Compare Tutor.com, Wyzant and Preply for online tutoring from New Jersey: who can apply, what each platform says about fees and equipment, and the state career resources worth checking.',
                'content' => $content,
                'featured_image' => 'blogs/online-tutoring-jobs-new-jersey.jpg',
                'tags' => 'online tutoring jobs new jersey, remote tutor jobs nj, online tutoring platforms, online tutor requirements, tutor com requirements, wyzant fee, preply commission, new jersey department of labor',
                'meta_title' => 'Online Tutoring Jobs in New Jersey: Platform Guide',
                'meta_description' => 'Compare online tutoring platforms for New Jersey residents: Tutor.com, Wyzant and Preply requirements, fees, equipment and the state career resources to check.',
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
<p><strong>Online tutoring jobs in New Jersey can suit teachers, college students, subject specialists, and beginners who can explain their knowledge clearly.</strong> Platforms such as Tutor.com, Wyzant, and Preply offer different application routes, eligibility rules, and payment models. Start by choosing a subject, checking your qualifications, and preparing a short teaching demonstration. Confirm that the opportunity accepts applicants working from New Jersey. Platform approval does not guarantee student bookings, and a displayed lesson rate may differ from earnings after fees and preparation time.</p>

<h2>Choose the Right Type of Tutoring</h2>

<p>Academic tutoring can involve mathematics, science, writing, programming, or other school and college subjects. Language tutoring may focus on conversation, grammar, pronunciation, or specific learning goals.</p>

<p>Decide which subject and learner level you can teach confidently. Being comfortable with basic algebra does not automatically qualify you to teach advanced calculus. Describe your scope accurately and prepare examples suitable for your intended students.</p>

<p>Also distinguish scheduled lessons from on demand homework support. These formats can require different availability, preparation, and communication styles. Read the actual responsibilities before applying.</p>

<h2>Tutor.com: Check Education and Availability</h2>

<p>Tutor.com's applicant FAQ requires US residency, a valid Social Security number, US work eligibility, and availability for at least five tutoring hours weekly. It says applicants who need employment visa sponsorship, such as H-1B, F-1 or OPT, cannot currently be accepted. Applicants must be college sophomores or higher pursuing a four year degree at an accredited university, or already hold a four year degree or higher. Subject expertise and strong spoken and written English are also required.</p>

<p>It says a competency exam is available for high-demand subjects and that pay details follow the initial application and subject exam stages. Review current state availability and subject needs before investing time in an application. Meeting the baseline criteria does not guarantee acceptance.</p>

<h2>Wyzant: Build a Subject Focused Profile</h2>

<p>Wyzant's fee page refers to the tutor's posted hourly rate, which means tutors publish their own rate. Check the platform's current eligibility rules, including age, residence and tax details, on its own registration pages before you apply.</p>

<p>Prepare an accurate profile describing subjects, experience, and teaching approach. Explain what students can expect from a session rather than promising particular grades or examination results.</p>

<p>Wyzant's help centre says tutors keep 75% of their posted hourly rate and Wyzant keeps a 25% platform fee, with an exception for lessons with a new student the tutor refers. It also says tutors who enter accurate bank details are paid on the 1st and 15th of each month. Check current payment policies before deciding whether your chosen rate meets your needs.</p>

<h2>Preply: Review Approval and Commission Rules</h2>

<p>Preply says no specific certification or teaching experience is required, although its Tutor Success team reviews each new profile, which can take up to three business days. Profiles are sometimes rejected at first if the photo, video or description does not meet its requirements.</p>

<p>This can be worth exploring if you have suitable subject knowledge but limited formal teaching experience. It still requires a convincing profile and the ability to support students effectively.</p>

<p>Preply publishes 100% commission on a new student's trial lesson. Subsequent lesson commission starts at 33% and decreases to 18% with accumulated teaching hours. Account for this before estimating income.</p>

<h2>New Jersey Public Career Resources</h2>

<p>The New Jersey Department of Labor's career services page points to Job Source, the state's job search, and My Career NJ, which it describes as a connected suite of career, job and training tools. It also says One-Stop Career Centers offer career support as a walk-in or by appointment. These are public services, so use them to compare tutoring work with school and college openings in the state.</p>

<p>Official page: <a href="https://www.nj.gov/labor/career-services/" rel="noopener">New Jersey Department of Labor career services</a>.</p>

<h2>Requirements New Jersey Applicants Should Check</h2>

<p>Location, work eligibility, education, age, and subject approval are separate questions. Do not assume that living in New Jersey makes you eligible for every online platform.</p>

<p>Read the exact opportunity's credential requirements. A private tutoring marketplace and a school based instructional position can have different expectations. If a listing requires a teaching certificate, confirm the specified credential directly with the employer.</p>

<p>Review any identity verification, screening, or background check instructions before submitting sensitive documents. Use the official application process and ask support about unclear requirements. Avoid claiming a credential you have not earned.</p>

<h2>Equipment and Online Teaching Skills</h2>

<p>You need a dependable teaching setup that meets your chosen platform's specifications. Test your microphone, camera, internet connection, and screen sharing before an assessment or first lesson.</p>

<p>Tutor.com asks for a Windows 10 or 11 computer with at least 4 GB of RAM, a connection of at least 15 Mbps down and 5 Mbps up, a microphone and a webcam, and says Mac users can apply if they can run Windows. Preply lists a laptop or desktop computer, a stable internet connection, a webcam and a microphone. Read current specifications rather than assuming a phone or older device will work.</p>

<p>Practise using an online whiteboard and explaining a concept while writing clearly. Keep lesson materials organised and close unrelated tabs. Check that shared screens do not expose personal information or documents belonging to other learners.</p>

<h2>Prepare a Strong Application</h2>

<p>Choose one subject and write a concise introduction explaining your background, learner level, and teaching approach. Use genuine qualifications and relevant experience, including mentoring or supervised educational work where appropriate.</p>

<p>Prepare a brief sample lesson. Begin with a learning goal, ask a diagnostic question, explain the idea, and give the learner a chance to practise. Finish by checking understanding.</p>

<p>If the platform requests an introduction video, use clear audio and a tidy background. Speak naturally and explain how you help students. Follow the platform's rules on contact details, promotional claims, and permitted profile content.</p>

<h2>Understand Earnings Before Committing</h2>

<p>Compare the payment model, not just the advertised number. Ask whether compensation covers booked lessons, preparation, waiting time, cancellations, or training. Establish when payments arrive and whether fees apply.</p>

<p>For illustration, a $30 lesson rate with a 25% platform deduction leaves $22.50 before other applicable deductions and unpaid work. This is arithmetic, not a promise of bookings or take home income.</p>

<p>Tutor.com says tutors receive a fixed hourly rate that depends on the subjects they teach, plus possible incentives for high-quality sessions. Request the relevant terms before accepting an arrangement.</p>

<h2>Build a Reliable Teaching Routine</h2>

<p>Publish availability you can maintain and leave time between sessions for notes. Confirm time zones when teaching students outside New Jersey, especially around seasonal clock changes.</p>

<p>Keep progress notes focused on learning goals, topics covered, and next steps. Adapt explanations when a student struggles rather than simply repeating the same answer.</p>

<p>Support learning instead of completing assessed work for the student. Use practice examples, questions, and feedback to help them develop their own understanding. Follow the platform's communication and privacy rules when working with students or parents.</p>

<p>Review your weekly workload regularly and adjust availability before accepting more lessons than manageable.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can College Students Apply?</h3>
<p>Some platforms accept them. Tutor.com specifies sophomore level or higher toward an eligible four year degree. Other platforms set different requirements, so check individually.</p>

<h3>Do I Need Teaching Experience?</h3>
<p>Not universally. Preply states that specific teaching experience is not required, while other opportunities may request credentials or demonstrated tutoring ability. Approval and bookings remain uncertain.</p>

<h3>Are These Salaried Jobs?</h3>
<p>Not necessarily. Opportunities can include marketplace tutoring, contract work, or employment. Read the agreement and payment terms before making assumptions about benefits or guaranteed hours.</p>

<h3>Does Tutor.com accept applicants who need visa sponsorship?</h3>
<p>It says applicants who need employment visa sponsorship, such as H-1B, F-1 or OPT, cannot currently be accepted.</p>

<h3>How much does Wyzant keep?</h3>
<p>Its help centre says tutors keep 75% of their posted hourly rate and Wyzant keeps a 25% platform fee, with an exception for lessons with a new student the tutor refers.</p>

<h3>How much commission does Preply take?</h3>
<p>Preply says it takes 100% of the fee for a trial lesson with a new student, then 33% on later lessons, falling to 18% as you teach more hours.</p>

<h3>What computer do I need for Tutor.com?</h3>
<p>It lists a Windows 10 or 11 computer with at least 4 GB of RAM, 15 Mbps down and 5 Mbps up, a microphone and a webcam. Check the current page before you buy anything.</p>

<h3>Where can I find New Jersey career help?</h3>
<p>The New Jersey Department of Labor career services page points to Job Source, My Career NJ and One-Stop Career Centers.</p>

<h2>People Also Search For</h2>

<h3>Online tutoring jobs New Jersey</h3>
<p>Compare platform rules, fees and equipment, and check state career resources too.</p>

<h3>Remote tutor jobs NJ</h3>
<p>Check whether a platform accepts applicants who live in New Jersey and what credentials it asks for.</p>

<h3>Online tutoring platforms</h3>
<p>Tutor.com, Wyzant and Preply use different application routes and payment models.</p>

<h3>Online tutor requirements</h3>
<p>Platforms set their own rules for residence, work eligibility, education and subject exams.</p>

<h3>Tutor.com requirements</h3>
<p>US residence, a valid Social Security number, US work eligibility and five hours a week.</p>

<h3>Wyzant fee for tutors</h3>
<p>Tutors keep 75% of the posted rate under the help centre's standard structure.</p>

<h3>Preply commission rates</h3>
<p>Commission on later lessons starts at 33% and falls to 18% with more teaching hours.</p>

<h3>New Jersey Department of Labor career services</h3>
<p>It lists Job Source, My Career NJ and One-Stop Career Centers.</p>



<h2>Bottom Line</h2>

<p>To pursue online tutoring jobs in New Jersey, choose a subject you know well, compare platform requirements, and prepare a clear teaching demonstration. Check fees and equipment needs, then build consistent availability and a teaching approach centred on student understanding.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.tutor.com/apply/tutoring-faq" rel="noopener">Tutor.com: Online tutor applicant FAQ</a></li>
<li><a href="https://support.wyzant.com/tutors/tutor-payments/what-is-the-fee-structure-for-tutors-listed-on-wyzant/" rel="noopener">Wyzant Support: Fee structure for tutors</a></li>
<li><a href="https://preply.com/en/teach" rel="noopener">Preply: Become an online tutor</a></li>
<li><a href="https://www.nj.gov/labor/career-services/" rel="noopener">New Jersey Department of Labor: Career services</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/virtual-assistant-jobs-manila-philippines">Virtual Assistant Jobs in Manila, Philippines</a>: another remote route with equipment to check.</li>
    <li><a href="/blog/freelance-writing-jobs-nairobi-kenya">Freelance Writing Jobs in Nairobi, Kenya</a>: platform fees and getting a first client.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or career advice. Reviewed on 21 October 2026 against the pages named in Sources. It does not promise a vacancy, a reward, an income or an offer, and platform rules and postings can change.</p>
HTML;
    }
}
