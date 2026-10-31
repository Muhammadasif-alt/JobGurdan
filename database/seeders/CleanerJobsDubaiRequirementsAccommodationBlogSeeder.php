<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Cleaner Jobs in Dubai, UAE: Requirements and Accommodation"
 *
 * Checked on 31 October 2026. What changed against the brief:
 *
 * 1. All Indeed material is dropped: the three buttons, the Maid Squad advert (a job-ID link we could not verify, and the
 *    source of the AED 2,000/2,600 figures) and the AED 2,175 Indeed salary. The worked housing comparison stays, using
 *    plainly invented figures.
 * 2. The Accor link carried a job ID, so the guide links the careers site and gives reference REF122596D as text.
 * 3. Five u.ae links in the brief return 404; the live pages sit under /jobs/Sector-of-employment/ and
 *    /jobs/Workplace-regulations/. The MoHRE absher link also returned 404, so the family-sponsorship permit comes from
 *    u.ae's work-permits page. Article 6(4) was read in the Labour Law PDF.
 * 4. DMaids: cdn.dmaids.ae redirects to dmaids.ae. The page says both "all 7 emirates" and "2 emirates", does not state
 *    that it accepts beginners, and only offers "Less than 1 year" on the form, so the guide says that.
 *
 * Posters are the owner's (blog-356, 31 Oct 9.47 to 9.50 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class CleanerJobsDubaiRequirementsAccommodationBlogSeeder extends Seeder
{
    public const SLUG = 'cleaner-jobs-dubai-requirements-accommodation';

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
                'title' => 'Cleaner Jobs in Dubai, UAE: Requirements and Accommodation',
                'excerpt' => 'Who hires cleaners in Dubai, what "accommodation provided" really means, which roles suit beginners, the work permit rules, why no one may charge you recruitment costs, and how to compare offers.',
                'content' => $content,
                'featured_image' => 'blogs/cleaner-jobs-dubai-requirements-accommodation.jpg',
                'tags' => 'cleaner jobs in dubai uae, cleaner jobs in dubai with accommodation, office cleaner jobs in dubai, house cleaner jobs in dubai, cleaner salary in dubai, hotel cleaner jobs dubai, cleaning jobs dubai no experience, dubai work permit cleaner',
                'meta_title' => 'Cleaner Jobs in Dubai: Requirements and Accommodation',
                'meta_description' => 'Cleaner jobs in Dubai: company, hotel and household roles, what accommodation really includes, beginner options, work permits and how to compare offers.',
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
<p><strong>You can apply for cleaner jobs in Dubai through cleaning companies, hotels or private households, and two questions matter most: who employs you, and where will you live?</strong> The answers change your contract, your rights and how much money you actually keep. You cannot start work until your employer has a work permit, and a visit visa does not count, even for an unpaid trial. No official average salary exists for cleaners and UAE law sets no general minimum wage, so judge each offer on its own written terms. We checked the sources here on 31 October 2026.</p>

<h2>What Accommodation Is Actually Included?</h2>

<p>"Accommodation provided" usually means a bed in shared staff housing. A housing allowance means you receive money and find your own room. Those are different offers, and an allowance may already be counted inside the salary figure on the advert, so do not add it twice.</p>

<p>Here is a made-up comparison to show the method. Offer A pays AED 2,000 with a free company bed. Offer B pays AED 2,600 and you find your own room. If your room and utilities cost AED 800 a month, Offer B leaves AED 1,800, which is less than Offer A. If you can share with family for AED 300, Offer B wins. These figures are illustrations, not Dubai salaries or rents. Run the sum with your own costs.</p>

<p>Before you sign, ask for:</p>

<ul>
<li>The housing address, and how many people share each room</li>
<li>Any deductions for the room, utilities or food</li>
<li>Cooking and laundry facilities</li>
<li>Where the staff bus starts, and whether it runs after the last job of a night shift</li>
</ul>

<p>A bus from company housing to a client's site does nothing for you if you live elsewhere and cannot reach the pickup point.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cleaner-jobs-dubai-requirements-accommodation-2.jpg"
         alt="Cleaner in a navy uniform and yellow gloves with the Dubai skyline behind, alongside hotel room and office cleaning scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Which Cleaning Job Fits Your Experience?</h2>

<p><strong>Office and commercial cleaning.</strong> You may prepare meeting rooms before staff arrive, clean washrooms and replace supplies. Malls, schools and other public buildings often need cleaning while people are around. Ask whether you stay at one building or move between sites. Hospital and airport work can have extra access and training rules, so read those before you apply.</p>

<p><strong>Hotel housekeeping.</strong> A room attendant prepares guest bedrooms, while a public area attendant looks after shared spaces. Accor's Public Area Attendant post at Sofitel Dubai Downtown (reference REF122596D) covers dusting, vacuuming, mopping, polishing and deep cleaning, plus guest restrooms. It asks for at least one year in the position, so it is not a first job. If you have used a vacuum but never a polishing machine, say so. Official page: <a href="https://careers.accor.com/" rel="noopener">Accor Careers</a>, search for the reference.</p>

<p><strong>House and villa cleaning.</strong> A company cleaner may visit several clients and return to staff housing. A housemaid hired directly by a family falls under the UAE's separate domestic worker rules, and the government lists "provide appropriate accommodation" among the employer's duties. Agree the duties, privacy and rest arrangements first, and ask directly whether cooking, childcare or laundry is part of the job. Official page: <a href="https://u.ae/en/information-and-services/jobs/Workplace-regulations/domestic-helpers" rel="noopener">UAE Government: Domestic workers</a>.</p>

<h2>Requirements for Beginners and Supervisors</h2>

<p>Training on an advert does not mean beginners are welcome, because experienced cleaners also get an induction at a new site. Look for wording that accepts first-time applicants, or ask.</p>

<p>DMAIDS, the cleaning division of Delight Investment Group, is one company to ask. It describes paid in-house training, shared accommodation with utilities included, and transport from accommodation to jobs for eligible roles. Its form has a "Less than 1 year" experience option, but the page does not say outright that it hires beginners, and it contradicts itself on coverage, mentioning both all seven emirates and two. Specify Dubai and ask both questions. These are the company's own claims. Official page: <a href="https://dmaids.ae/careers/" rel="noopener">DMAIDS: Careers</a>.</p>

<p>A beginner's CV can describe unpaid work honestly: "Cleaned the community hall after weekend events, checked washroom supplies and reported broken fittings." For deep cleaning, list the equipment you can use safely. For supervisor posts, give an example of assigning rooms, checking finished work or helping a new colleague reach a client's standard.</p>

<h2>Cleaner Salary in Dubai: What to Compare</h2>

<p>We do not repeat salary averages from job sites here, since they mix very different cleaning jobs. Compare the figure on each written offer instead, split into guaranteed basic pay, housing allowance, overtime and bonuses. If a residential route pays extra after a target number of client hours, ask how travel, waiting and cancelled bookings are recorded. Two offers with the same monthly pay can have very different working days. If you need weekly pay, get the payday in writing.</p>

<h2>Work Permits, Sponsorship and "Free Visa" Adverts</h2>

<p>"Free visa" is a search phrase, not a type of permit. Three rules cut through most of the confusion:</p>

<ul>
<li><strong>You need a permit.</strong> Article 6 of the UAE Labour Law says an employer may not recruit or employ a worker without a work permit from the Ministry of Human Resources and Emiratisation (MoHRE).</li>
<li><strong>You should not pay for it.</strong> The same article prohibits the employer from charging the worker recruitment and employment fees and costs, "directly or indirectly".</li>
<li><strong>A visit visa is not enough.</strong> The government says the law "strictly prohibits" working on a visit or tourist visa, paid or unpaid.</li>
</ul>

<p>"Own visa" adverts need checking too. Someone living in Dubai on family sponsorship can still need a permit for the job: MoHRE has a specific permit that lets companies hire family-sponsored residents, and a separate part-time permit. Ask which one applies before accepting bookings.</p>

<p>A proper offer is also a document you can check. It must describe your terms in Arabic and English, and you may ask for a third language; Urdu, Hindi and Nepalese are among the nine options. Compare that offer with what you were told.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/cleaner-jobs-dubai-requirements-accommodation-3.jpg"
         alt="Cleaner with a supplies trolley on a Dubai waterfront, with scenes of hotel rooms and floor cleaning equipment"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply</h2>

<p>Put the job title in your CV heading and state your current location, languages, notice period and preferred shift. For a hotel, describe how you prepared a room for the next guest. For an office, describe finishing a round before the building opened. Send it through the employer's careers page.</p>

<p>At interview, ask the supervisor to describe yesterday's route: when the team left the accommodation, how many properties it visited and when the last cleaner got back. That tells you more than a promise of flexible hours. Before accepting, compare the final contract with the offer you saved, and if housing or transport has changed, ask for written clarification.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply from Pakistan, India or Nepal?</h3>
<p>Ask whether the employer recruits from abroad. Some adverts offering a company visa still want people already in the UAE, so check before you spend money on travel.</p>

<h3>Do I have to pay for my visa or recruitment?</h3>
<p>The Labour Law bars employers from charging workers recruitment and employment costs, directly or indirectly.</p>

<h3>Can I start work on a visit visa while my permit is processed?</h3>
<p>No. The UAE prohibits working on a visit or tourist visa, including unpaid work.</p>

<h3>How do I compare female and male cleaner vacancies?</h3>
<p>Read the assignment details. If a post is for women on residential visits, ask whether you travel alone, who meets you at each property and how you reach a supervisor if a booking changes.</p>

<h3>What does immediate hiring mean?</h3>
<p>Ask for the interview date, the first shift and any checks still outstanding. An urgent advert still needs a verifiable employer and a written offer.</p>

<h2>Bottom Line</h2>

<p>Compare what you will keep after housing and travel, apply where your experience and location fit, and get the pay, accommodation and transport terms in writing.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/work-permits" rel="noopener">UAE Government: Work permits</a> and <a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/preparing-to-work" rel="noopener">Preparing to work</a></li>
<li><a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/Job-offers-and-the-employment-process" rel="noopener">UAE Government: Job offers and the employment process</a></li>
<li><a href="https://assets.u.ae/api/public/content/eb65a60bbff949fa912eddd566613014?v=7174ed78" rel="noopener">UAE Labour Law, Federal Decree-Law No. 33 of 2021, Article 6</a></li>
<li><a href="https://u.ae/en/information-and-services/jobs/Workplace-regulations/domestic-helpers" rel="noopener">UAE Government: Domestic workers</a></li>
<li><a href="https://careers.accor.com/" rel="noopener">Accor Careers</a> and <a href="https://dmaids.ae/careers/" rel="noopener">DMAIDS: Careers</a>, all checked 31 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/hotel-jobs-dubai-foreigners-roles-requirements">Hotel Jobs in Dubai for Foreigners</a>, more roles in the same hotels.</li>
    <li><a href="/blog/security-guard-jobs-dubai-no-experience">Security Guard Jobs in Dubai</a>, another entry-level route.</li>
    <li><a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">Cleaner and Janitor Jobs With Visa Sponsorship</a>, other countries compared.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
