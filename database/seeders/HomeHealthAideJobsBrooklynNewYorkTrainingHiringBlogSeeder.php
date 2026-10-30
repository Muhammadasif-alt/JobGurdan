<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Home Health Aide Jobs in Brooklyn, New York: Training and Hiring"
 *
 * Checked on 28 October 2026. What changed against the brief:
 *
 * 1. The brief repeated dozens of keyword variants ("home health aide jobs Brooklyn NY ..."), which reads as keyword
 *    stuffing; the guide is rewritten in plain prose with the tags carrying the search terms. The two Indeed buttons are
 *    dropped; the single VNS requisition is not linked (single adverts expire) and is quoted with its checked date.
 * 2. NY Department of Health fetched: HHA programmes provide core training of at least 75 hours, PCA training at least
 *    40 hours; the training FAQ gives 59 classroom and 16 supervised practical hours, at least 50% of the practical in a
 *    patient care setting, and 60 days to finish the 75 hours.
 * 3. Home care aide minimum wage: the DOL fact sheet linked by the brief is the 2024 edition; the DOL home care page (via
 *    search) gives $19.65 an hour for New York City, Long Island and Westchester and $18.65 elsewhere from 1 January 2026.
 * 4. Selfhelp fetched: free HHA and PCA training approved by the Department of Health, classes in English, Spanish and
 *    Russian, a Brooklyn site at 626 Sheepshead Bay Road, immediate placement for qualified aides. VNS Health fetched:
 *    welcomes certified HHAs or those who wish to become certified, people with resume gaps or returning to work; a
 *    Brooklyn HHA posting rendered by headless Chrome listed $19.86 to $22.00 an hour.
 * 5. FAQs expanded to eight and a note for readers in Pakistan added.
 *
 * No Brooklyn poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class HomeHealthAideJobsBrooklynNewYorkTrainingHiringBlogSeeder extends Seeder
{
    public const SLUG = 'home-health-aide-jobs-brooklyn-new-york-training-hiring';

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
                'title' => 'Home Health Aide Jobs in Brooklyn, New York: Training and Hiring',
                'excerpt' => 'How to become a home health aide in Brooklyn: New York\'s 75-hour HHA training, free courses such as Selfhelp\'s, the $19.65 home care minimum wage for 2026, what agencies like VNS Health pay, shifts, live-in work and how to apply.',
                'content' => $content,
                'featured_image' => 'blogs/home-health-aide-jobs-brooklyn-new-york-training-hiring.jpg',
                'tags' => 'home health aide jobs in brooklyn new york, home health aide training, hha certificate, home care agencies in brooklyn hiring, home health aide certification requirements new york, free hha training brooklyn, home health aide pay nyc, home health aide jobs no experience brooklyn',
                'meta_title' => 'Home Health Aide Jobs in Brooklyn, NY: Training and Pay',
                'meta_description' => 'Home health aide jobs in Brooklyn: 75-hour New York HHA training, free courses, the $19.65 home care minimum wage for 2026, agency pay and how to apply.',
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
<p><strong>You can start as a home health aide (HHA) in Brooklyn without experience if you complete a New York State Department of Health approved training programme, pass its competency evaluation and clear the agency's hiring checks.</strong> HHA core training is at least 75 hours, and some agencies run it for free. From 1 January 2026 the minimum wage for home care aides in New York City is $19.65 an hour. A certificate does not guarantee hours, a particular client or visa sponsorship. This guide uses the Department of Health, the Department of Labor, Selfhelp and VNS Health, checked on 28 October 2026.</p>

<h2>Complete Approved Training</h2>

<p>To become an HHA or a personal care aide (PCA) in New York, you must complete training and competency evaluation through a programme the Department of Health has approved. HHA programmes provide core training of at least 75 hours: 59 hours in the classroom and 16 hours of supervised practical training, with at least half of the practical in a patient care setting, all within 60 days of starting. PCA basic training is at least 40 hours, and there are upgrade routes from PCA or CNA to HHA. An HHA and a PCA certificate are not the same thing, so check which one a job asks for, and avoid anyone selling a certificate without the training. Sources: <a href="https://www.health.ny.gov/facilities/home_care/professional.htm" rel="noopener">NY Department of Health: Home health aides and personal care aides</a> and <a href="https://www.health.ny.gov/professionals/home_care/hhtap_training_program_faq.htm" rel="noopener">Training programme FAQs</a>.</p>

<h2>Free Training and Training-to-Hire Programmes</h2>

<p>Several Brooklyn agencies train new aides and then hire them. Selfhelp Community Services, for example, offers free HHA and PCA training approved by the Department of Health, with classes in English, Spanish and Russian, a Brooklyn site at 626 Sheepshead Bay Road, and immediate job placement for qualified aides. Ask any programme whether trainees are paid, get a stipend or neither, and what attendance and assessments it requires. Source: <a href="https://selfhelp.net/home-care-training/" rel="noopener">Selfhelp: Home care training</a>.</p>

<h2>Beginner Opportunities</h2>

<p>Agencies often hire newly certified aides while still checking references and competence. VNS Health says it welcomes certified HHAs and people who want to become certified, including people with resume gaps, those out of work for six months or more and people entering or returning to the workforce. Describe training placements honestly; family caregiving shows reliability but does not replace the certificate. Source: <a href="https://jobs.vnshealth.org/home-health-aides" rel="noopener">VNS Health: Home health aide careers</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/home-health-aide-jobs-brooklyn-new-york-training-hiring-steps.jpg"
         alt="Infographic of the Brooklyn home health aide path: approved 75-hour training, competency evaluation, agency hiring checks, and the 19.65 dollar minimum wage"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Duties and Related Job Titles</h2>

<p>HHAs help clients with personal hygiene, mobility, meals and medication reminders, and report changes to a supervising nurse, all within the care plan. Elderly care, disability care and companion work describe different clients, so ask about training, lifting equipment and clinical support. "Home care aide" and "caregiver" adverts can be broader roles; hospital posts usually want a certified nursing assistant or patient care technician rather than an HHA.</p>

<h2>Pay in Brooklyn</h2>

<p>Under Public Health Law section 3614-f, home care aides in New York City, Long Island and Westchester earn at least $19.65 an hour from 1 January 2026, and $18.65 in the rest of the state. That is a legal floor, not an average. Home care aides are also owed overtime at one and a half times the regular rate for hours over 40 a week (44 for residential employees), and may be owed call-in and spread-of-hours pay. A VNS Health Brooklyn HHA posting we checked on 28 October 2026 listed $19.86 to $22.00 an hour. On "$200 a day" adverts, do the maths: $25 for eight paid hours or $20 for ten. Ask for guaranteed hours, benefits and deductions in writing. Sources: <a href="https://dol.ny.gov/minimum-wage-home-care-aides-fare-grant" rel="noopener">NY Department of Labor: Minimum wage for home care aides</a> and <a href="https://dol.ny.gov/system/files/documents/2024/12/p105-home-health-aide-10-23-24.pdf" rel="noopener">DOL home care aide fact sheet</a>.</p>

<h2>Choosing Shifts and Living Arrangements</h2>

<p>Full-time and part-time posts can guarantee very different hours, so ask who chooses assignments and what happens if a client cancels. Morning, night, overnight and weekend shifts each change your week. Live-in cases differ from live-out work: do not divide a daily rate by hours you assume, because sleep time, breaks and interruptions follow specific pay rules. If transport is offered, ask what it covers.</p>

<h2>Agencies and Private Clients</h2>

<p>Check an agency's identity, supervision and the neighbourhoods it covers, since assignments can move across Brooklyn. Private clients need clear written terms, duties and emergency contacts. Never pay to be placed with a client.</p>

<h2>How to Apply</h2>

<p>Prepare your certificate, resume, references and availability, and send identity and work-authorisation documents only through the agency's secure onboarding. "Immediate start" adverts still need background checks and a health screening before your first case, so ask which checks remain.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Home health aide posts need existing US work authorisation, and agencies do not sponsor visas for them. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> for routes that do accept overseas applicants.</p>

<h2>Frequently Asked Questions</h2>

<h3>How long is HHA training in New York?</h3>
<p>At least 75 hours: 59 in the classroom and 16 supervised practical hours, finished within 60 days.</p>

<h3>Is HHA training free in Brooklyn?</h3>
<p>Some agencies, such as Selfhelp, offer free Department of Health approved training with job placement.</p>

<h3>What is the minimum wage for home health aides in Brooklyn?</h3>
<p>$19.65 an hour from 1 January 2026.</p>

<h3>Can I apply with no experience?</h3>
<p>Yes, once certified. Agencies like VNS Health welcome new and returning workers.</p>

<h2>Bottom Line</h2>

<p>Choose a Department of Health approved programme, compare agencies on hours, supervision and pay above the $19.65 floor, and get every term in writing before accepting a case.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.health.ny.gov/facilities/home_care/professional.htm" rel="noopener">NY Department of Health: Home health aides and personal care aides</a></li>
<li><a href="https://www.health.ny.gov/professionals/home_care/hhtap_training_program_faq.htm" rel="noopener">NY Department of Health: Training programme FAQs</a></li>
<li><a href="https://dol.ny.gov/minimum-wage-home-care-aides-fare-grant" rel="noopener">NY Department of Labor: Minimum wage for home care aides</a> and <a href="https://dol.ny.gov/system/files/documents/2024/12/p105-home-health-aide-10-23-24.pdf" rel="noopener">fact sheet</a></li>
<li><a href="https://selfhelp.net/home-care-training/" rel="noopener">Selfhelp: Home care training</a></li>
<li><a href="https://jobs.vnshealth.org/home-health-aides" rel="noopener">VNS Health: Home health aide careers</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/cna-jobs-atlanta-georgia-certification-pay">CNA Jobs in Atlanta</a>, a nursing assistant route.</li>
    <li><a href="/blog/caregiver-jobs-calgary-visa-sponsorship">Caregiver Jobs in Calgary</a>, home care work in Canada.</li>
    <li><a href="/blog/medical-assistant-jobs-phoenix-no-experience">Medical Assistant Jobs in Phoenix</a>, another short-training healthcare job.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

HTML;
    }
}
