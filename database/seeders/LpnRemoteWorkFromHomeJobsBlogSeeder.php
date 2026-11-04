<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "LPN Remote Work From Home Jobs 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online"
 *
 * Checked on 4 November 2026. What changed against the brief:
 *
 * 1. The Indeed button and the Sweeten Health pay seen only on Indeed are dropped. The Rippling links carried job IDs,
 *    so the guide links HealthSnap's board and names each posting.
 * 2. HealthSnap postings read. The brief was wrong on three points: every navigator posting asks for 1+ year of
 *    experience (not 3+), a compact licence is preferred rather than required (single-state holders may qualify and must
 *    apply for a compact licence before starting), and the Michigan posting no longer exists. A New York version is
 *    listed instead. The brief also missed the quarterly bonus of up to $1,500 and the Monday-to-Friday Eastern schedule.
 * 3. General navigator: $21-$25, 40 hours Monday to Friday Eastern, week 1 training 9am to 5:30pm ET, week 2 with a
 *    clinical coach, 100% remote, extra state licences reimbursed. Bilingual: $21-$26 and Spanish. Illinois: Illinois
 *    licence required, $21-$25.
 * 4. NCSBN read ("Practice is where the patient is", including care by telephone) and the FTC's April 2026 job offer text
 *    scam alert read.
 *
 * Posters are the owner's (blog-359, 3 Nov 8.59 to 9.02 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class LpnRemoteWorkFromHomeJobsBlogSeeder extends Seeder
{
    public const SLUG = 'lpn-remote-work-from-home-jobs-2026';

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
                'title' => 'LPN Remote Work From Home Jobs 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online',
                'excerpt' => 'Real remote LPN and LVN care navigator jobs for 2026: $21 to $26 an hour plus a quarterly bonus, one year of experience, which licence you need, monthly pay, and why nursing practice is where the patient is.',
                'content' => $content,
                'featured_image' => 'blogs/lpn-remote-work-from-home-jobs-2026.jpg',
                'tags' => 'lpn remote work from home jobs, remote lpn jobs 2026, lvn remote jobs, remote care navigator lpn, lpn work from home salary per month, compact nursing license remote jobs, chronic care management lpn remote, telehealth lpn jobs',
                'meta_title' => 'LPN Remote Work From Home Jobs 2026: Pay and Requirements',
                'meta_description' => 'Remote LPN and LVN jobs in 2026: care navigator roles at $21 to $26 an hour, the one-year experience rule, licence and state rules, monthly pay and scams.',
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
<p><strong>If you are a licensed practical or vocational nurse (LPN or LVN) in the United States who wants to work from home, the most common route is a remote care navigator or chronic care management role.</strong> HealthSnap's remote navigator jobs, checked on 4 November 2026, pay <strong>$21 to $25 an hour</strong> (up to $26 for the bilingual role), plus a quarterly bonus of up to $1,500. They ask for at least <strong>one year</strong> of experience in primary care, cardiology, internal medicine, home care or care management. Remote experience is not required, so a clinic nurse can qualify without ever having worked from home. A newly licensed nurse with no clinical job yet is in a different position, and we explain the options below.</p>

<h2>Remote LPN Vacancies to Compare</h2>

<p>HealthSnap runs remote patient monitoring: nurses support patients between doctor visits using readings sent from home devices. It listed four navigator postings when we checked. They look alike, but the licence rule differs, so read the one that matches your licence. Official board: <a href="https://ats.rippling.com/healthsnapcareers/jobs" rel="noopener">HealthSnap Careers</a>.</p>

<h3>1. Remote LPN/LVN Care Navigator, Care Management</h3>

<p>The main posting. It is full time, 40 hours a week, Monday to Friday on Eastern Time, and 100% remote. The first week of training runs 9am to 5:30pm Eastern; the second week adds hands-on training with a clinical coach, including shadowing. HealthSnap pays for training on its monitoring platform and reimburses extra state licences it asks you to get. If you live in a Central or Pacific time zone, convert those hours to your local time before applying.</p>

<h3>2. Remote LPN/LVN Care Navigator, Bilingual</h3>

<p>The same role for Spanish speakers, at <strong>$21 to $26 an hour</strong>. You must speak and write Spanish, with the same one-year experience requirement. If you have used Spanish at work, say exactly how: for example, making follow-up calls and noting a patient's questions for the care team.</p>

<h3>3. Illinois and New York Licence Versions</h3>

<p>Separate postings require an active Illinois LPN/LVN licence or a New York one. The Illinois version pays $21 to $25 an hour. If your only licence is from one of those states, start with that posting rather than assuming every navigator job needs a multistate licence. Pick the one that matches the licence you actually hold, rather than applying to every version.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/lpn-remote-work-from-home-jobs-2026-2.jpg"
         alt="Licensed practical nurse in scrubs with a headset working from home at a laptop, LPN Remote Work From Home Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Licence Do You Need?</h2>

<p>This is the part most applicants get wrong. HealthSnap says a <strong>multistate (compact) licence is preferred, not required</strong>. If you hold a single-state licence, you may still qualify depending on where you live, but you will need to apply for your compact licence before your start date.</p>

<p>There is a deeper rule behind this. The National Council of State Boards of Nursing (NCSBN) says nursing "practice is where the patient is", and that includes telehealth and care by telephone. So a remote job does not let you care for patients in any state you like: you need authorisation for the states your patients are in. Ask the employer which patient states the role covers, and check your authorisation with the relevant board of nursing.</p>

<h2>What Does a Remote LPN Actually Do?</h2>

<p>Picture a morning of scheduled calls. One patient has not sent a reading from their home device; another has a question about the plan from their last appointment. The navigator checks the record, follows the outreach protocol, documents what the patient says, and passes any concern to the right clinician. The job rewards careful listening and a clear handover, especially when a question is outside an LPN's scope.</p>

<p>In an interview, ask how much time is set aside for documentation and who is available when a call needs escalating. Two jobs with the same hourly rate feel very different if one gives you a steady group of patients and the other has back-to-back calls from a queue.</p>

<h2>LPN Remote Job Salary Per Month</h2>

<p>At 40 paid hours a week, the advertised rates work out to these average monthly amounts before tax (hourly rate × 40 × 52 ÷ 12):</p>

<ul>
<li><strong>$21 an hour:</strong> about $3,640 a month</li>
<li><strong>$23 an hour:</strong> about $3,987 a month</li>
<li><strong>$25 an hour:</strong> about $4,333 a month</li>
<li><strong>$26 an hour:</strong> about $4,507 a month</li>
</ul>

<p>The quarterly bonus of up to $1,500 is on top, but "up to" means it is not guaranteed, so leave it out of your budget until you know how it is earned. HealthSnap sets the rate by experience and state of residence. Before comparing two offers, ask for the guaranteed hours, the rate during training and when benefits start.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/lpn-remote-work-from-home-jobs-2026-3.jpg"
         alt="Smiling LPN wearing a headset and scrubs at a home office desk, Remote Work From Home Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Can a New LPN Apply With No Experience?</h2>

<p>Separate two things: no remote experience, and no nursing experience. A nurse who has spent two years making follow-up calls after clinic visits already has directly relevant work. A new graduate does not meet HealthSnap's one-year rule, and paid software training does not waive it.</p>

<p>If you are not there yet, take a local outpatient LPN job that includes phone follow-up and charting, and build your year. When describing school placements, call them supervised clinical rotations, not employment.</p>

<h2>How to Get a Remote LPN Job Online</h2>

<ol>
<li><strong>Search two ways.</strong> Try "remote LPN care management" and "remote LVN patient monitoring", adding your licensed state. Use each employer's own board to confirm the exact posting.</li>
<li><strong>Screen before applying.</strong> Note the licence required, where you may work from, the clinical experience and the training hours.</li>
<li><strong>Write a specific resume.</strong> Put your licence state and clinical setting near the top. Replace "good communication skills" with something real, such as documenting follow-up calls and escalating unresolved questions to the supervising nurse. Never include patient details.</li>
<li><strong>Keep a copy of the posting,</strong> with the title, pay and date, so you can check the schedule a recruiter later offers.</li>
</ol>

<h2>Phone and Video Interview Tips</h2>

<p>Practise this question: "What would you do if a caller asked for advice outside your scope?" A strong answer explains that you would follow the approved protocol, bring in the right clinician and document the handover. For video interviews, test your microphone in the room you would actually work from. Afterwards, send a short thank-you that mentions one specific detail, such as how new navigators are coached.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do remote LPN jobs pay?</h3>
<p>HealthSnap's navigator roles pay $21 to $25 an hour, or up to $26 for the bilingual role, about $3,640 to $4,507 a month at 40 hours, plus a possible quarterly bonus of up to $1,500.</p>

<h3>Do I need a compact nursing licence?</h3>
<p>It is preferred, not required. Single-state holders may qualify depending on where they live, but must apply for a compact licence before starting.</p>

<h3>How much experience do I need?</h3>
<p>At least one year in primary care, cardiology, internal medicine, home care or care management.</p>

<h3>Can I do a US remote LPN job from another country?</h3>
<p>Do not assume so. These roles are US remote, and nursing practice is where the patient is. Check residence, work authorisation and licence rules first.</p>

<h3>What job scams should remote nurses watch for?</h3>
<p>The FTC warns about fake recruiters texting about "remote positions" and then asking you to deposit a check and send money back. Find the employer's careers page yourself before sharing onboarding details.</p>

<h2>Bottom Line</h2>

<p>Start with the posting that matches your licence and clinical background, compare guaranteed pay and hours, and lead your application with one clear example of patient follow-up rather than simply saying you want to work from home.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://ats.rippling.com/healthsnapcareers/jobs" rel="noopener">HealthSnap Careers</a>: Remote LPN/LVN Care Navigator (general, Bilingual, Illinois and New York licence versions)</li>
<li><a href="https://ncsbn.org/nursing-regulation/licensure/nurse-licensure-guidance.page" rel="noopener">NCSBN: Nurse licensure guidance</a></li>
<li><a href="https://consumer.ftc.gov/consumer-alerts/2026/04/job-offer-text-probably-scam" rel="noopener">FTC: That job offer text is probably a scam</a>, all checked 4 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nursing-jobs-houston-licensing-entry-requirements">Nursing Jobs in Houston</a>, licensing and entry rules in Texas.</li>
    <li><a href="/blog/cna-jobs-atlanta-georgia-certification-pay">CNA Jobs in Atlanta</a>, an earlier step in nursing.</li>
    <li><a href="/blog/remote-marketing-jobs-2026">Remote Marketing Jobs 2026</a>, other US-remote work.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you share documents.</li>
</ul>

HTML;
    }
}
