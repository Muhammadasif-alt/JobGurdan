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
use Illuminate\Support\Str;

/**
 * "EV Technician Jobs: Training and Career Guide" — the electric vehicle page
 * of the mechanic cluster, built on the IEA Global EV Outlook 2026, BLS OEWS
 * May 2025, the NAVTTC course catalogue and the IMI's high-voltage
 * qualification framework.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. "1,200 EV jobs on SEEK" and "1,100 openings on Job Bank", live counts
 *    from job boards that change daily.
 *
 * 2. A JLR vacancy with a job-ID link and its GBP 48,764 salary, and links
 *    to LKQ Electriq, Jaunt and Tesla careers pages.
 *
 * 3. An age limit of 18 to 40 for the NAVTTC course and a TEVTA Punjab EV
 *    centre at a named Lahore college, neither of which could be confirmed on
 *    an official page. The course is described from the NAVTTC catalogue only.
 *
 * The IEA figures (sales above 20 million in 2025, up 20 per cent, one in
 * four new cars; about 23 million and close to 30 per cent expected in 2026)
 * match the Global EV Outlook 2026. US pay is kept short and the full table
 * is left to Mechanic Salary by Country.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class EvTechnicianJobsTrainingBlogSeeder extends Seeder
{
    private const UK_APPLY_URL = 'https://www.gov.uk/find-a-job';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'EV Technician Jobs: Training and Career Guide';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'One in four new cars sold in 2025 was electric, says the IEA. How to train as an EV technician: IMI high-voltage levels in the UK, the free NAVTTC course in Pakistan, and where certified technicians find work.',
                'content' => $content,
                'featured_image' => 'blogs/ev-technician-jobs-training.jpg',
                'tags' => 'ev technician jobs, electric vehicle technician training, imi ev level 3, imi techsafe, navttc ev technician course, high voltage vehicle training, ev mechanic salary, ev technician career',
                'meta_title' => 'EV Technician Jobs: Training and Career Guide',
                'meta_description' => 'How to become an EV technician: IMI high-voltage levels 1 to 4, the free NAVTTC EV course in Pakistan, IEA demand data and where the jobs are.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $ukAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Franchised Dealers & EV Service Centres (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-ev-technician-aggregated']
        );

        $ukLocation = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            [
                'position' => 'EV Technician — UK Franchised Dealers and Service Centres',
                'advertiser_id' => $ukAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $ukLocation->id,
                'description' => $this->ukJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, 38 to 45 hours a week',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UK_APPLY_URL,
                'meta_description' => 'Electric and hybrid vehicle technician roles with UK dealers and service centres. IMI Level 3 high-voltage qualifications are expected.',
                'seo_keywords' => 'ev technician jobs uk, electric vehicle technician, hybrid vehicle technician, imi level 3 ev, high voltage technician',
            ]
        );
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Franchised dealers, fleet operators, breakdown companies and independent EV specialists across the United Kingdom recruit technicians qualified to work on electric and hybrid vehicles.</p>

<h3>What the work involves</h3>
<ul>
    <li>Making high-voltage systems safe before work starts</li>
    <li>Diagnosing faults in batteries, motors, inverters and charging systems</li>
    <li>Routine servicing of electric and hybrid cars</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li>A Level 3 light vehicle qualification or apprenticeship</li>
    <li>An IMI electric and hybrid vehicle qualification at Level 3 or above for live high-voltage work</li>
    <li>The right to work in the UK</li>
</ul>

<p><strong>Note:</strong> work visa rules are set by UK Visas and Immigration, not by JobGader. Most vehicle technician roles sit below the Skilled Worker skill threshold, so check eligibility before applying from abroad.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Every electric car sold today will need servicing for the next ten to fifteen years, and most garages still have few technicians trained to open a high-voltage system safely. That gap is the case for retraining as an EV technician. This guide covers the demand figures, the recognised qualifications in the UK and Pakistan, and what the work pays. For the conventional mechanic route in each country, see <a href="/blog/car-mechanic-jobs-in-australia-uk-and-canada">Car Mechanic Jobs in Australia, UK and Canada</a>.</p>

<h2>How Fast Is the EV Market Growing?</h2>

<p>The International Energy Agency's <strong>Global EV Outlook 2026</strong> reports that:</p>

<ul>
    <li>Electric car sales <strong>grew 20 per cent in 2025</strong> to more than <strong>20 million</strong></li>
    <li>That was <strong>one in four new cars</strong> sold worldwide</li>
    <li>Sales are expected to reach about <strong>23 million in 2026</strong>, close to 30 per cent of the market</li>
</ul>

<p>Sales growth is uneven: China accounts for most of it, while Europe and North America move more slowly. But every market is building a fleet of electric and hybrid cars that will need high-voltage-trained technicians when warranties run out.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/ev-technician-jobs-training-battery.jpg"
         alt="A technician in a navy cap working on an electric car's battery modules and orange high-voltage cables, with a charging station, a silver electric car and Sydney, London and Toronto scenes behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Does an EV Technician Do?</h2>

<ul>
    <li><strong>Making the vehicle safe.</strong> Isolating and proving dead the high-voltage system, which can run at several hundred volts, before any work begins.</li>
    <li><strong>Diagnosis.</strong> Reading battery management data, insulation resistance and charging faults with dedicated software.</li>
    <li><strong>Repair and replacement.</strong> Motors, inverters, on-board chargers, cooling circuits and, at the highest level, battery modules.</li>
    <li><strong>Everything else.</strong> Brakes, tyres, suspension and steering still need a mechanic; EV work is added to the trade, not separate from it.</li>
</ul>

<h2>Training in the United Kingdom: IMI Levels 1 to 4</h2>

<p>The Institute of the Motor Industry (IMI) electric and hybrid vehicle qualifications are the recognised UK credentials for high-voltage work, and its <strong>IMI TechSafe</strong> standard records who is qualified at which level:</p>

<ul>
    <li><strong>Level 1</strong> &mdash; awareness, for anyone who works around electric vehicles, such as valeters and service advisers.</li>
    <li><strong>Level 2</strong> &mdash; routine maintenance on a vehicle whose high-voltage system has been made safe.</li>
    <li><strong>Level 3</strong> &mdash; isolating, de-energising and repairing high-voltage systems. This is what most EV technician jobs ask for.</li>
    <li><strong>Level 4</strong> &mdash; working on live high-voltage components, diagnosis and battery repair.</li>
</ul>

<p>A mechanic already qualified at Level 3 in light vehicles can usually add the EV levels through short courses, often paid for by the employer.</p>

<h2>Training in Pakistan: the NAVTTC EV Technician Course</h2>

<p>The National Vocational and Technical Training Commission (NAVTTC) lists an <strong>EV Technician</strong> course in its automotive and transport sector. It is <strong>free</strong>, runs for <strong>six months</strong> and is certified at <strong>NVQF Level 3</strong>, combining foundations, core skills and a practical assessment. Intakes and eligibility change with each batch, so check the NAVTTC website or your nearest NAVTTC institute before applying, and do not pay anyone who offers to secure a place.</p>

<p>An NVQF certificate is a good start, but an employer abroad will judge you on your experience and on the qualifications its own country recognises, such as IMI in the UK or provincial certification in Canada.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/ev-technician-jobs-training-diagnostics.jpg"
         alt="A technician probing high-voltage battery connections in an electric car, beside a diagnostic tablet showing a vehicle battery layout and a white SUV at a charging point"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Are the EV Technician Jobs?</h2>

<ul>
    <li><strong>Franchised dealers.</strong> Brands selling electric cars must be able to service them, so their dealer networks train and recruit high-voltage technicians first.</li>
    <li><strong>Fleets and breakdown companies.</strong> Delivery fleets, taxi and ride-hailing operators and roadside assistance firms need technicians who can make an EV safe on the spot.</li>
    <li><strong>Independent EV specialists.</strong> Once warranties expire, owners look for cheaper repairs, and independent garages with Level 3 and Level 4 staff pick up the work.</li>
    <li><strong>Charging infrastructure.</strong> Installing and maintaining chargers is electrical work; see <a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a>.</li>
</ul>

<h2>What Do EV Technicians Earn?</h2>

<p>No government publishes a separate EV technician wage; EV work is paid within the mechanic trade. In the United States, BLS reports a May 2025 median of <strong>$50,620</strong> for automotive service technicians and <strong>$61,770</strong> for diesel technicians, with about <strong>66,200 openings a year</strong> projected to 2035. High-voltage qualifications move a technician towards the upper end of these ranges. For figures in Australia, the UK and Canada, see <a href="/blog/mechanic-salary-by-country">Mechanic Salary by Country</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>Is EV technician a good career?</h3>
<p>The IEA reports that electric cars were one in four new cars sold in 2025, so the number of vehicles needing high-voltage-trained technicians is rising every year.</p>

<h3>How many electric cars were sold in 2025?</h3>
<p>More than 20 million, up 20 per cent on 2024, according to the IEA Global EV Outlook 2026. About 23 million are expected in 2026.</p>

<h3>Which qualification do I need for EV work in the UK?</h3>
<p>An IMI electric and hybrid vehicle qualification. Level 3 covers isolating and repairing high-voltage systems; Level 4 covers live work and battery repair.</p>

<h3>What is IMI TechSafe?</h3>
<p>The IMI's professional standard that records which technicians hold which level of high-voltage qualification.</p>

<h3>Is the NAVTTC EV technician course free?</h3>
<p>Yes. NAVTTC lists it as a free, six-month course certified at NVQF Level 3. Check intakes and eligibility on the NAVTTC website.</p>

<h3>Do I need to be a mechanic before training on EVs?</h3>
<p>For Level 3 and above, yes in practice. Employers expect a light vehicle qualification first, with EV levels added on top.</p>

<h3>How much does an EV technician earn?</h3>
<p>There is no separate official figure. In the US the median for automotive technicians is $50,620 and for diesel technicians $61,770.</p>

<h3>Can I get an EV technician visa?</h3>
<p>There is no EV-specific visa. Technicians use the same routes as mechanics, which in the UK are now narrow for roles below degree level.</p>

<h2>People Also Search For</h2>

<h3>EV technician course</h3>
<p>IMI Levels 1 to 4 in the UK; NAVTTC in Pakistan.</p>

<h3>IMI Level 3 electric vehicle</h3>
<p>Isolating and repairing high-voltage systems.</p>

<h3>IMI Level 4 EV</h3>
<p>Live high-voltage work and battery repair.</p>

<h3>NAVTTC EV technician course</h3>
<p>Free, six months, NVQF Level 3.</p>

<h3>Global EV sales 2025</h3>
<p>More than 20 million, one in four new cars.</p>

<h3>EV technician salary</h3>
<p>Paid within the mechanic trade; $50,620 US median for auto technicians.</p>

<h3>Hybrid vehicle technician training</h3>
<p>The same IMI electric and hybrid levels apply.</p>

<h3>High voltage vehicle safety training</h3>
<p>Level 2 and above covers working on a made-safe vehicle.</p>

<h2>More Job Guides</h2>

<p>The rest of the mechanic cluster and related trades:</p>

<ul>
    <li><a href="/blog/car-mechanic-jobs-in-australia-uk-and-canada">Car Mechanic Jobs in Australia, UK and Canada</a> &mdash; skills assessment and certification in each country.</li>
    <li><a href="/blog/mechanic-salary-by-country">Mechanic Salary by Country</a> &mdash; official pay figures compared.</li>
    <li><a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a> &mdash; charger installation is electrical work.</li>
    <li><a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">Industrial vs House Wiring Electrician: Which Pays More</a> &mdash; the electrical side of the switch to EVs.</li>
    <li><a href="/blog/how-to-become-an-auto-mechanic-in-australia">How to Become an Auto Mechanic in Australia</a> &mdash; the apprenticeship route.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career, legal or immigration advice. Market figures are from the IEA Global EV Outlook 2026 and US pay from BLS OEWS May 2025. Confirm course details with NAVTTC and qualification levels with the IMI.</p>
HTML;
    }
}
