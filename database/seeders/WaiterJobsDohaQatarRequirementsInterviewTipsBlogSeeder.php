<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Waiter Jobs in Doha, Qatar: Requirements and Interview Tips"
 *
 * Checked on 29 October 2026. What changed against the brief:
 *
 * 1. The brief repeated dozens of keyword variants ("waiter jobs in Doha Qatar ..."), which reads as keyword stuffing;
 *    the guide is rewritten in plain prose with the tags carrying the search terms. The two Indeed buttons and the
 *    Indeed server salary (QAR 1,928 from 304 salaries) are dropped: JobGader does not republish aggregator salaries.
 * 2. Accor fetched: a Pullman Waiter/Waitress post in Doha is full time and permanent, calls fine-dining or guest-facing
 *    experience an advantage, and asks for fluent English and familiarity with POS systems. Banyan Tree Doha's
 *    Waiter/Waitress post lists menu knowledge, POS transactions, unobtrusive table service and hygiene rules.
 * 3. gov.qa, hukoomi.gov.qa and gco.gov.qa refuse our network and the server's; mol.gov.qa does not answer. The minimum
 *    wage (Law No. 17 of 2020: QAR 1,000 basic, plus at least QAR 300 food and QAR 500 housing unless provided) and the
 *    NOC change (Law No. 19 of 2020, adopted 30 August 2020; notice under Law No. 18 of 2020 of one month for two years'
 *    service or less, two months above) are taken from the ILO, which was fetched.
 * 4. FAQs expanded to eight; eight People Also Search For entries and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 28 Oct 11.12 and 11.13 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class WaiterJobsDohaQatarRequirementsInterviewTipsBlogSeeder extends Seeder
{
    public const SLUG = 'waiter-jobs-doha-qatar-requirements-interview-tips';

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
                'title' => 'Waiter Jobs in Doha, Qatar: Requirements and Interview Tips',
                'excerpt' => 'How to get a waiter job in Doha: what Accor and Banyan Tree adverts ask for, Qatar\'s QAR 1,000 minimum wage plus food and housing allowances, changing jobs without an NOC, accommodation, and interview questions to practise.',
                'content' => $content,
                'featured_image' => 'blogs/waiter-jobs-doha-qatar-requirements-interview-tips.jpg',
                'tags' => 'waiter jobs in doha qatar, waiter jobs in qatar for freshers, hotel waiter jobs in doha qatar, waiter salary in doha qatar per month, waiter jobs in doha qatar with visa sponsorship, qatar minimum wage, change job in qatar without noc, waiter interview questions',
                'meta_title' => 'Waiter Jobs in Doha, Qatar: Requirements and Interview Tips',
                'meta_description' => 'Waiter jobs in Doha: what Accor and Banyan Tree ask for, the QAR 1,000 minimum wage plus food and housing, changing jobs without an NOC, and interview tips.',
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
<p><strong>Waiter jobs in Doha suit people with good customer service, clear English and reliable availability; some hotels prefer hospitality experience, others train motivated beginners.</strong> Overseas applicants need an employer that arranges their work visa and residence. Qatar's minimum wage is QAR 1,000 a month basic, plus at least QAR 300 for food and QAR 500 for housing unless the employer provides them. Since 2020 workers can change jobs without their employer's No Objection Certificate (NOC). None of this guarantees a job or sponsorship. This guide uses Accor's hotel adverts and the International Labour Organization (ILO), checked on 29 October 2026.</p>

<h2>Restaurant and Hotel Service Roles</h2>

<p>Waiters greet guests, take orders, serve and clear, and process payments. Hotel waiters may work in several outlets with different service styles. Compare the duties before you apply:</p>

<ul>
<li><strong>Cafe waiter:</strong> often counter and table service together.</li>
<li><strong>Barista and waiter:</strong> needs coffee preparation skills.</li>
<li><strong>Room service waiter:</strong> takes orders to guest rooms.</li>
<li><strong>Banquet and catering waiter:</strong> serves events, often in large teams.</li>
<li><strong>Buffet waiter:</strong> clears tables and keeps the buffet stocked.</li>
</ul>

<p>Fine dining and VIP outlets expect detailed menu knowledge and polished service. A luxury name does not by itself tell you the salary or benefits.</p>

<h2>What Employers Ask For</h2>

<p>Hotels usually look at English, teamwork, order accuracy, presentation and point-of-sale (POS) skills. A Pullman Waiter/Waitress post in Doha advertised by Accor is full time and permanent; it calls previous fine-dining or guest-facing experience an advantage, not a requirement, and asks for fluent English, communication skills and familiarity with POS systems. Banyan Tree Doha's Waiter/Waitress post asks staff to know every menu item, its garnishes, contents and preparation, to use the POS to ring up sales and close checks, to serve and clear in an unobtrusive and professional manner, and to follow safety, sanitation and hygiene policies. Sources: <a href="https://careers.accor.com/global/en/job/waiter-waitress-in-doha-qatar-jid-109426" rel="noopener">Accor: Waiter/Waitress, Doha</a> and <a href="https://careers.accor.com/job/waiter-waitress-in-banyan-tree-doha-doha-qatar-jid-105876" rel="noopener">Accor: Waiter/Waitress, Banyan Tree Doha</a>.</p>

<p>If you have no restaurant experience, show transferable skills from retail, volunteering or customer support, and prove you are dependable and willing to learn. Practise explaining dishes, confirming orders and asking a supervisor for help. Ask which health and food-safety checks the employer requires before you start; no-experience roles do not skip them.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/waiter-jobs-doha-qatar-requirements-interview-tips-team.jpg"
         alt="Waiter in a black waistcoat and bow tie serving guests at a fine-dining restaurant in Doha with the city skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Salary and Total Package</h2>

<p>Your written offer is what counts. Qatar's minimum wage under Law No. 17 of 2020 is <strong>QAR 1,000 a month</strong> basic. Employers who do not provide food and housing directly must also pay allowances of at least <strong>QAR 300 for food</strong> and <strong>QAR 500 for housing</strong>, and they must make sure workers have decent accommodation and food. These are legal floors, not typical salaries. Source: <a href="https://www.ilo.org/resource/article/dismantling-kafala-system-and-introducing-minimum-wage-mark-new-era-qatar" rel="noopener">ILO: Dismantling the kafala system and introducing a minimum wage</a>.</p>

<p>Ask how tips and service charge are shared, and do not budget on money you have not been promised in writing. Ask how often salary is paid and what is deducted.</p>

<h2>Accommodation, Meals and Benefits</h2>

<p>If accommodation is provided, ask for the address, how many people share a room and what facilities there are. Ask whether meals cover rest days or only shifts, and whether transport runs to match late finishes. Request the medical insurance coverage and the leave entitlement in writing. If an advert promises paid training, ask how long it lasts and who supervises it.</p>

<h2>Work Visas, Residence and Changing Jobs</h2>

<p>An overseas waiter can only start once the employer has arranged the work visa and residence permit. When an advert says "visa sponsorship", ask which company employs you and when you can lawfully start. "Free visa" is advertising language, not a permit that lets you work for anyone. Never pay a recruiter or a stranger for a job or a visa.</p>

<p>Qatar's Law No. 19 of 2020, adopted on 30 August 2020, lets workers change jobs before their contract ends without first getting an NOC from their employer. Under Law No. 18 of 2020 you must give at least one month's written notice if you have worked for the employer for two years or less, and two months if longer. An advert that demands an NOC is not describing the general law. Source: <a href="https://www.ilo.org/resource/article/dismantling-kafala-system-and-introducing-minimum-wage-mark-new-era-qatar" rel="noopener">ILO: Dismantling the kafala system</a>.</p>

<h2>Documents and Direct Applications</h2>

<p>Hotels usually ask for a CV, passport details, references and certificates. Get the official checklist from the employer before you pay for attestations or medical tests. To find a job from abroad, start on the hotel group's own careers site: Accor lists its Doha jobs, including Pullman and Banyan Tree, on <a href="https://careers.accor.com/" rel="noopener">careers.accor.com</a>, and Hilton lists Doha jobs on <a href="https://jobs.hilton.com/" rel="noopener">jobs.hilton.com</a>. Check whether overseas applicants are considered.</p>

<h2>Interview Questions to Practise</h2>

<ul>
<li>How would you handle a complaint about delayed food?</li>
<li>What would you do if a guest mentioned an allergy?</li>
<li>How would you correct a billing mistake?</li>
<li>How would you prioritise several tables at once?</li>
</ul>

<p>For allergies, say you would follow the venue's procedure, check with the kitchen and never guess. For complaints, listen, acknowledge the problem and involve a supervisor when needed. Ask the interviewer about station size, training, breaks, shifts and who you go to when service gets busy.</p>

<h2>How to Apply</h2>

<p>Tailor your CV, track each application and check the original posting date; "urgent" and "immediate start" adverts still need screening and a visa. Verify any walk-in interview on the employer's own website. West Bay, The Pearl, Lusail and the airport area have very different commutes, so ask about staff transport.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Pakistani workers going abroad through a recruitment agent should use one licensed by the Bureau of Emigration and Overseas Employment, and never pay for a "guaranteed" job. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can freshers get waiter jobs in Doha?</h3>
<p>Yes, where the advert allows beginners; Accor's Pullman post treats experience as an advantage, not a requirement.</p>

<h3>What is the minimum wage for waiters in Qatar?</h3>
<p>QAR 1,000 a month basic, plus at least QAR 300 food and QAR 500 housing unless provided.</p>

<h3>Do I need to speak English?</h3>
<p>Usually; Accor's Doha post asks for fluent English.</p>

<h3>Can I change jobs in Qatar without an NOC?</h3>
<p>Yes, since Law No. 19 of 2020, after giving the required notice.</p>

<h3>How much notice must I give?</h3>
<p>At least one month for two years' service or less, two months if longer.</p>

<h3>Is accommodation provided?</h3>
<p>Often, but confirm the address, sharing and facilities in writing.</p>

<h3>Is visa sponsorship guaranteed?</h3>
<p>No. Get written confirmation from the employer before you travel.</p>

<h3>Do nationality or gender decide eligibility?</h3>
<p>No. Each role's requirements and the visa process apply individually.</p>

<h2>People Also Search For</h2>

<h3>Waiter jobs in Doha Qatar</h3>
<p>Hotel, restaurant, cafe and banquet service.</p>

<h3>Waiter jobs in Qatar for freshers</h3>
<p>Roles whose adverts accept beginners.</p>

<h3>Hotel waiter jobs in Doha Qatar</h3>
<p>Listed on the hotel groups' own careers sites.</p>

<h3>Waiter salary in Doha Qatar per month</h3>
<p>From QAR 1,000 basic plus food and housing.</p>

<h3>Waiter jobs in Doha Qatar with visa sponsorship</h3>
<p>The employer arranges the work visa and residence.</p>

<h3>Qatar minimum wage</h3>
<p>QAR 1,000 basic under Law No. 17 of 2020.</p>

<h3>Change job in Qatar without NOC</h3>
<p>Allowed since 2020, with notice.</p>

<h3>Waiter interview questions</h3>
<p>Complaints, allergies, billing and busy tables.</p>

<h2>Bottom Line</h2>

<p>Apply through the hotel's own site, compare basic pay, allowances, housing and meals in writing, and prepare real service examples for the interview. This guide cannot promise a job, a visa or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://careers.accor.com/global/en/job/waiter-waitress-in-doha-qatar-jid-109426" rel="noopener">Accor: Waiter/Waitress, Doha</a></li>
<li><a href="https://careers.accor.com/job/waiter-waitress-in-banyan-tree-doha-doha-qatar-jid-105876" rel="noopener">Accor: Waiter/Waitress, Banyan Tree Doha</a></li>
<li><a href="https://www.ilo.org/resource/article/dismantling-kafala-system-and-introducing-minimum-wage-mark-new-era-qatar" rel="noopener">ILO: Dismantling the kafala system and introducing a minimum wage</a></li>
<li><a href="https://jobs.hilton.com/" rel="noopener">Hilton careers</a>, all checked 29 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/chef-jobs-paris-france-entry-paths">Chef Jobs in Paris</a>, the kitchen side of hospitality.</li>
    <li><a href="/blog/hotel-jobs-dubai-foreigners-roles-requirements">Hotel Jobs in Dubai for Foreigners</a>, hotel roles in the UAE.</li>
    <li><a href="/blog/receptionist-jobs-dubai-requirements-interview-tips">Receptionist Jobs in Dubai</a>, front desk work in the Gulf.</li>
    <li><a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">Hotel and Restaurant Jobs in the UAE and Italy</a>, hospitality in two markets.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 29 October 2026 against the pages named in Sources. It does not promise a job, a visa, accommodation or a wage.</p>
HTML;
    }
}
