<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Remote Jobs at Aebi Schmidt 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online"
 *
 * Checked on 4 November 2026. What changed against the brief:
 *
 * 1. The Indeed button and the Indeed Maryland salary average (USD 102,988) are dropped: JobGader does not republish
 *    aggregator salaries. The LocalJobNetwork copy of the Monroe advert is described but not linked.
 * 2. Aebi Schmidt North America read: merged with The Shyft Group, now one group listed as AEBI on NASDAQ, positions on
 *    three platforms during system updates; Monroe, Towmaster, Swenson, Meyer, MB and ELP hire in several states "or
 *    remote"; fraud alert says no group entity asks for any upfront payment or deposit; notificationhr@aebi-schmidt.com.
 * 3. Monroe Regional Sales Manager read on LocalJobNetwork: remote, Virginia and Maryland focus, live in that market, up
 *    to 50% travel, CRM, Microsoft Office, mechanical aptitude. No salary. Monroe's own careers page only says "Apply
 *    Below", so the live status could not be confirmed there.
 * 4. Gatwick story read: three electric eSwingo 200+, IntelliOPS telematics with battery state of charge, up to 10 hours
 *    without recharging; the 2030 net-zero Scope 1 and 2 goal is Gatwick's, under its Decade of Change policy.
 * 5. The 2017 apprentice story and the Utilimaster page are not used; the group jobs portal did not render a list.
 *
 * Posters are the owner's (blog-359, 3 Nov 8.54 to 8.57 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class AebiSchmidtRemoteJobsBlogSeeder extends Seeder
{
    public const SLUG = 'aebi-schmidt-remote-jobs-2026';

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
                'title' => 'Remote Jobs at Aebi Schmidt 2026: Latest Remote Vacancies, Requirements, Salary and How to Apply Online',
                'excerpt' => 'How Aebi Schmidt hires after its merger with The Shyft Group, what its "remote" sales jobs really involve (up to 50% travel in a territory), how to judge sales pay, and how to report recruitment fraud.',
                'content' => $content,
                'featured_image' => 'blogs/aebi-schmidt-remote-jobs-2026.jpg',
                'tags' => 'aebi schmidt remote jobs 2026, aebi schmidt careers, monroe truck equipment jobs, aebi schmidt north america jobs, shyft group careers, remote sales jobs truck equipment, regional sales manager remote, recruitment scam warning',
                'meta_title' => 'Aebi Schmidt Remote Jobs 2026: Vacancies and How to Apply',
                'meta_description' => 'Aebi Schmidt remote jobs in 2026: where the group posts vacancies after the Shyft merger, what remote territory sales involves, pay questions and fraud checks.',
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
<p><strong>Aebi Schmidt does advertise some jobs as remote, but in this group "remote" usually means a field role covering a sales territory, not a desk at home every day.</strong> When we checked on 4 November 2026, the clearest example was a Monroe Truck Equipment Regional Sales Manager role: remote, but looking for someone who lives in the Virginia and Maryland market, with up to 50% travel. No salary was published, and we found no fully home-based, no-experience vacancy. This guide explains how the group now posts jobs after merging with The Shyft Group, what its remote roles really involve, and how to avoid recruitment scams that use its name.</p>

<h2>Who Aebi Schmidt Is and Where It Posts Jobs</h2>

<p>Aebi Schmidt makes specialist vehicles and equipment: street sweepers, snow and ice control, airport cleaning machines and, since its merger with The Shyft Group, truck bodies and work truck upfits. The merged company now operates under the Aebi Schmidt Group name and is listed on NASDAQ as AEBI.</p>

<p>That merger changes how you search. The group says its systems are being updated and, in the meantime, positions are spread across <strong>three platforms</strong>. Its North America careers page groups the brands like this:</p>

<ul>
<li><strong>Monroe, Towmaster, Swenson, Meyer, MB and ELP:</strong> positions in Illinois, Kentucky, Michigan, Minnesota, North Carolina, Ohio, Pennsylvania, Wisconsin and other regions, "or remote"</li>
<li><strong>Utilimaster, Royal Truck Body, DuraMag and Magnum, Strobes-R-Us, Spartan RV Chassis, Builtmore and Blue Arc:</strong> operations in ten states and two countries, recruited through the former Shyft Group careers site</li>
</ul>

<p>So if a saved link for a brand stops working, go back to the group's North America page and choose the right platform for that brand. Official page: <a href="https://www.aebi-schmidt.com/en-us/careers/careers-north-america/" rel="noopener">Aebi Schmidt: Careers North America</a>.</p>

<h2>A Real Remote Role: What the Work Involves</h2>

<p>The Monroe Truck Equipment Regional Sales Manager advert appeared on a job board under a Virginia Beach heading. Monroe itself is based in Winston-Salem, North Carolina, but the advert names Virginia, West Virginia and Maryland as the territory and says it wants a sales representative who lives in the Virginia and Maryland market. The office named in a listing and the place a company wants you to live can be different, so check that your home address qualifies before you apply.</p>

<p>The job mixes looking after existing equipment customers with finding new ones. The advert lists:</p>

<ul>
<li>Up to 50% travel within the territory</li>
<li>Recording customer activity and opportunities in a CRM</li>
<li>Proficiency in Microsoft Office: Excel, Word, PowerPoint and Outlook</li>
<li>Mechanical aptitude, described as extremely beneficial</li>
</ul>

<p>Someone who has explained truck equipment options to a fleet buyer has far more relevant experience than someone who has only processed online orders. Monroe's own careers page simply says "Apply Below", so we could not confirm whether this vacancy was still open on the employer's system. Treat a job-board copy as a lead and confirm it with the company.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/aebi-schmidt-remote-jobs-2026-2.jpg"
         alt="Remote worker with a headset at a laptop, with a snow plough truck in the background, Aebi Schmidt Remote Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Aebi Schmidt Remote Jobs: Salary Per Month</h2>

<p>The Monroe advert does not publish pay, and we do not repeat salary averages from job-board websites, which mix different employers and seniority levels. Instead, here is how to judge a sales offer when it arrives.</p>

<p>Ask for three numbers separately: the guaranteed annual base salary, the target commission, and how often commission is paid. For example, an imaginary offer of $72,000 base plus $24,000 target commission is $6,000 a month of base pay. Calling it an $8,000-a-month salary assumes you will earn every dollar of commission. Mileage, hotels and meals are repayments of business costs, not earnings, so keep them out of your comparison.</p>

<h2>Can Beginners Apply Without Experience?</h2>

<p>The territory sales role is a poor first job: it expects previous sales experience and independent travel. Beginners have better luck searching the group's brands for titles such as "apprentice", "trainee", "parts support" or "sales support", then reading each posting's location and experience lines. Many of those jobs are at production sites rather than remote.</p>

<p>Be careful with office titles at factories. Production control, scheduling and production support involve computer planning, but also solving problems on the factory floor. Ask how often you would need to be on the production line before assuming a planning job can be done from home.</p>

<h2>How to Use Product Research in Your Application</h2>

<p>Sales interviews reward people who understand what customers buy and why. Aebi Schmidt's own case study is a good example. London Gatwick added three all-electric Schmidt eSwingo 200+ sweepers for airside and landside cleaning. They use Aebi Schmidt's IntelliOPS telematics system, which shows each machine's location, the battery's state of charge and service intervals, and they can work up to 10 hours without recharging.</p>

<p>One detail to get right: the goal of net-zero Scope 1 and Scope 2 emissions by 2030 belongs to Gatwick, under its "Decade of Change" sustainability policy. It is not Aebi Schmidt's own group-wide target, so do not describe it that way in an interview. You could turn the research into a smart customer question instead: "How does your team decide when to charge a sweeper without interrupting the cleaning schedule?"</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/aebi-schmidt-remote-jobs-2026-3.jpg"
         alt="Remote professional with a headset beside an Aebi Schmidt snow truck and a mountain road, Remote Jobs 2026"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply Online</h2>

<ol>
<li><strong>Find the brand first.</strong> Decide whether the role is with Monroe, Swenson, Utilimaster or another brand, then use the group's North America page to reach the right platform.</li>
<li><strong>Read the territory and travel lines</strong> before you write anything. "Remote" plus "up to 50% travel" means roughly half your working time on the road.</li>
<li><strong>Tailor your resume to the brand's customers:</strong> the territory you know, the customer types you have served and the equipment you understand. Add one short example of a sale or service problem you handled.</li>
<li><strong>Ask about sponsorship and location</strong> if you need a visa or live outside the US. A remote US territory role does not mean worldwide hiring.</li>
</ol>

<h2>Recruitment Scams to Avoid</h2>

<p>Aebi Schmidt publishes a clear fraud warning: scammers post fake jobs or pose as recruiters, and may ask for personal or financial details or for fees "to increase the chance of getting the job" or for office supplies. The group says <strong>no Aebi Schmidt entity will ever ask for any upfront payment or deposit</strong>. If you think you have been targeted, the company asks you to report it to <strong>notificationhr@aebi-schmidt.com</strong>. Use the address from the official page, not one supplied in a suspicious message.</p>

<p>Before an interview, ask the hiring manager to describe a recent week in the territory: how many days on the road, how many nights away, and which meetings were online. Also ask who supplies the vehicle, books travel and pays expenses. Those answers matter more than the word "remote" if you need a predictable schedule.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does remote mean I can stay at home every day?</h3>
<p>Not in the Monroe example. It covers a defined territory with up to 50% travel to customers.</p>

<h3>Where does Aebi Schmidt post jobs after the Shyft merger?</h3>
<p>On three platforms while its systems are updated. Start from the group's North America careers page and pick the brand.</p>

<h3>Does Aebi Schmidt ever charge a fee to apply?</h3>
<p>No. The group says none of its entities will ever ask for an upfront payment or deposit.</p>

<h3>Which salary should I write on the application?</h3>
<p>State the currency and whether you mean base salary or total pay including commission.</p>

<h3>Can I apply from Pakistan?</h3>
<p>Check each posting. The Monroe role wanted someone living in its US territory, so look for roles that name your country.</p>

<h2>Bottom Line</h2>

<p>Search by brand, confirm the vacancy on the employer's own platform, and read the territory and travel requirement before comparing pay. If you need a fully home-based job, get the location and travel expectations in writing first.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.aebi-schmidt.com/en-us/careers/careers-north-america/" rel="noopener">Aebi Schmidt: Careers North America and fraud alert</a></li>
<li>Monroe Truck Equipment: Regional Sales Manager advert, Virginia and Maryland territory, and <a href="https://www.monroetruck.com/careers/" rel="noopener">Monroe careers page</a></li>
<li><a href="https://www.aebi-schmidt.com/it-it/novita/2025/11/12/london-gatwick-airport-adds-electric-swingos-as-part-of-its-sustainability-program/" rel="noopener">Aebi Schmidt: London Gatwick electric sweepers</a>, all checked 4 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/carfax-remote-us-technology-jobs-2026">CARFAX Technology Jobs in the US</a>, another "remote or hybrid" check.</li>
    <li><a href="/blog/remote-marketing-jobs-2026">Remote Marketing Jobs 2026</a>, US-remote office roles.</li>
    <li><a href="/blog/truck-driver-jobs-dallas-cdl-requirements-training">Truck Driver Jobs in Dallas</a>, the work truck industry from the driver's seat.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Job Offer</a>, before you pay anyone.</li>
</ul>

HTML;
    }
}
