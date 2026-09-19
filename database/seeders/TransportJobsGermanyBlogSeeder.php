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
 * "How to Get a Transport Job in Germany" — the licence, the Code 95
 * qualification and the visa route for professional drivers, priced from the
 * Bundesagentur's own pay statistics rather than recruitment blogs.
 *
 * Corrections to the draft (checked against bgl-ev.de, the Bundesagentur für
 * Arbeit pay statistics, gesetze-im-internet.de for FeV, BKrFQG, FPersV,
 * BeschV and AufenthG, and balm.bund.de, September 2026):
 *
 * 1. The draft says Germany is short 80,000 to 100,000 drivers. The BGL-backed
 *    industry position paper of January 2026 says "more than 70,000". The
 *    100,000 figure comes from a 2023 release.
 *
 * 2. The draft reverses the retirement figures. The same paper says about
 *    30,000 to 35,000 drivers retire each year, against only 15,000 to 20,000
 *    new entrants.
 *
 * 3. The draft says 75% of drivers are near retirement. The paper says about
 *    one third are older than 55.
 *
 * 4. The draft gives pay of EUR 2,400 to 4,600 a month and an average of
 *    EUR 34,200 a year. The Bundesagentur's median for Berufskraftfahrer
 *    (Güterverkehr/LKW) was EUR 3,048 a month at 31 December 2024, and the
 *    agency states it cannot calculate an average at all.
 *
 * 5. The draft says a C or CE licence can be held at 18 with the accelerated
 *    basic qualification. FeV § 10 and BKrFQG § 3 require the full
 *    Grundqualifikation for 18; the accelerated route qualifies from 21.
 *
 * 6. The draft treats the driver card as a formality. It is issued by the
 *    authorities of each federal state under FPersV § 4, not by the KBA, and
 *    it records 28 days of driving, or 56 on the second-generation card.
 *
 * 7. The draft presents section 24a BeschV as a visa. It is a rule on the
 *    Federal Employment Agency's consent, which can be given for up to 15
 *    months even before the licence and Code 95 are held, if the contract
 *    obliges the driver to obtain them.
 *
 * 8. The draft gives the Opportunity Card's language rule loosely. It is A1
 *    German or B2 English, with at least six points and a qualification of at
 *    least two years' training.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class TransportJobsGermanyBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://de.indeed.com/q-berufskraftfahrer-jobs.html';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Get a Transport Job in Germany';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'German haulage is short more than 70,000 drivers and loses 30,000 to 35,000 to retirement a year. The official median pay is 3,048 euros a month, and the law lets an employer sponsor a driver who does not hold the licence yet.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-get-a-transport-job-in-germany.jpg',
                'tags' => 'transport jobs germany, truck driver jobs germany, berufskraftfahrer, code 95 germany, ce licence germany, chancenkarte, skilled worker visa germany, driver shortage germany',
                'meta_title' => 'How to Get a Transport Job in Germany: Licence and Visa',
                'meta_description' => 'Transport jobs in Germany: the CE licence and Code 95 rules, official pay of 3,048 euros a month, the driver visa route and how the shortage really looks.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'German Haulage and Logistics Employers (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'de-transport-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Germany'],
            ['area' => 'Nationwide', 'country' => 'Germany']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Professional Driver (Berufskraftfahrer) — Freight and Passenger Transport Roles with German Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under EU driving and rest time rules, recorded on a digital tachograph',
                'language' => 'German or English, depending on the employer',
                // Pay is set by each haulier and collective agreement, so only
                // the official median appears in the guide.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Professional driver roles with German haulage and logistics employers. A C or CE licence, the Code 95 qualification and a driver card are required.',
                'seo_keywords' => 'transport jobs germany, berufskraftfahrer jobs, truck driver germany, ce licence jobs, code 95 jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>German haulage companies, logistics groups and bus operators recruit professional drivers across the country, and the law allows employers to sponsor drivers from outside the EU.</p>

<h3>What the work involves</h3>
<p>Driving freight or passenger vehicles over 3.5 tonnes, loading and securing cargo, checking the vehicle, keeping to EU driving and rest times, and recording everything on a digital tachograph.</p>

<h3>Common requirements</h3>
<ul>
    <li>A category C or CE driving licence, recognised or converted for Germany</li>
    <li>The professional driver qualification, shown as EU code 95 on the licence</li>
    <li>A driver card for the digital tachograph</li>
    <li>An ADR certificate for dangerous goods work</li>
    <li>German or English, depending on the employer and the routes</li>
    <li>A residence title permitting work, for non-EU applicants</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, licence recognition and visa decisions are made by employers and German authorities &mdash; not by JobGader. Check the current rules with the German mission in your country before you pay anyone a fee.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>To work as a professional driver in Germany you need a category C or CE licence that Germany recognises, the professional driver qualification shown as EU <em>code 95</em> on your licence, and a driver card for the tachograph.</strong> Non-EU applicants also need a residence title that allows work. The Bundesagentur f&uuml;r Arbeit puts median pay for freight drivers at <strong>3,048 euros a month</strong> gross, and German law has a special rule that lets an employer hire a driver who has not obtained the licence yet.</p>

<p>This guide covers the licence and qualification, the real size of the shortage, what the job pays officially, and the two visa routes that actually apply.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://de.indeed.com/q-berufskraftfahrer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128667; Browse Transport Jobs in Germany &rarr;
    </a>
</div>

<h2>The Shortage Is Real, but Not the Number You Have Read</h2>

<p>Most guides quote 80,000 to 100,000 missing drivers. The industry's own position paper of <strong>January 2026</strong>, signed by eleven associations including the BGL, says something more specific:</p>

<ul>
    <li><strong>More than 70,000</strong> drivers are missing in Germany today.</li>
    <li><strong>30,000 to 35,000</strong> drivers retire every year, against only <strong>15,000 to 20,000</strong> new entrants &mdash; guides usually print those two figures the wrong way round.</li>
    <li>About <strong>one third</strong> of freight drivers are older than 55, not the 75% often claimed.</li>
</ul>

<p>The Bundesagentur f&uuml;r Arbeit backs the demand up from the official side: professional drivers appear among the occupations it names as being in shortage in its 2025 analysis. BALM, the federal logistics office, reports the same thing from the employer end &mdash; firms say they struggle to fill driver vacancies, and some park vehicles or shrink their fleets because they cannot staff them.</p>

<h2>What Transport Jobs in Germany Actually Pay</h2>

<p>The official anchor is the Bundesagentur's pay statistics, not recruitment blogs. At <strong>31 December 2024</strong>, full-time employees in <strong>Berufskraftfahrer (G&uuml;terverkehr/LKW)</strong> at skilled-worker level had a <strong>median gross wage of 3,048 euros a month</strong> &mdash; about 36,600 euros a year &mdash; which was 30% higher than in 2016.</p>

<p>Two things follow. First, the "average of 34,200 euros a year" repeated across guides has no official source: the agency states plainly that <strong>an arithmetic mean cannot be determined</strong> for this data. Second, the floor is the statutory minimum wage, which rose to <strong>13.90 euros an hour on 1 January 2026</strong> and rises to <strong>14.60 euros on 1 January 2027</strong>.</p>

<p>On top of base pay, German haulage jobs typically add tax-free expense allowances (<em>Spesen</em>), night premiums, and holiday or Christmas payments where a collective agreement applies. Compare the full package, and ask which collective agreement the employer follows.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-transport-job-in-germany-driver.jpg" alt="A professional driver standing by his truck on a German autobahn with the Brandenburg Gate behind him" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Freight drivers are among the occupations the Federal Employment Agency lists as in shortage.</figcaption>
</figure>

<h2>The Licence: Category C and CE</h2>

<p>German licensing law (FeV &sect; 6) defines them precisely:</p>

<ul>
    <li><strong>Category C:</strong> vehicles over <strong>3,500 kg</strong> built to carry no more than eight passengers besides the driver, with a trailer up to 750 kg.</li>
    <li><strong>Category CE:</strong> a category C tractor unit with trailers or a semi-trailer over 750 kg &mdash; the licence international freight work asks for.</li>
</ul>

<p><strong>The age rule is stricter than most guides say.</strong> C and CE are normally issued at <strong>21</strong>. You can hold them at <strong>18 only with the full Grundqualifikation</strong> &mdash; the complete basic qualification with an IHK examination. The shorter <em>accelerated</em> basic qualification does not lower the age to 18.</p>

<h2>Code 95: The Qualification That Makes You Employable</h2>

<p>The Berufskraftfahrer-Qualifikations-Gesetz (BKrFQG) sets a professional qualification separate from the driving licence. You can obtain it by:</p>

<ul>
    <li>the <strong>Grundqualifikation</strong>: theory and practical examinations at a chamber of industry and commerce (IHK);</li>
    <li>the <strong>beschleunigte Grundqualifikation</strong>: classes at a recognised training centre plus an IHK theory examination;</li>
    <li>completing a German <strong>Berufskraftfahrer or Fachkraft im Fahrbetrieb apprenticeship</strong>.</li>
</ul>

<p>After that, periodic training keeps it alive: the first refresher must be completed <strong>five years</strong> after the basic qualification, and then every five years. Proof is entered on the licence as the harmonised EU <strong>code 95</strong>, or issued as a driver qualification certificate. Without it you cannot legally drive professionally, however many years you have driven at home.</p>

<h2>The Driver Card and ADR</h2>

<ul>
    <li><strong>Driver card (Fahrerkarte).</strong> Required for the digital tachograph under EU Regulation 165/2014. In Germany it is issued by the <strong>authorities designated by each federal state</strong> &mdash; not by the KBA, which produces the cards and runs the register. A first-generation card stores 28 days of driving; the second-generation version 2 card stores 56.</li>
    <li><strong>ADR certificate</strong> for dangerous goods. Training, examinations and the certificate are handled by the <strong>chambers of industry and commerce</strong>, and the law requires the training and examination to be in <strong>German</strong>.</li>
</ul>

<h2>If Your Licence Was Issued Outside the EU</h2>

<p>A foreign licence lets you drive in Germany for <strong>six months</strong> after you establish normal residence, extendable by up to six more months if you can show your stay will not exceed a year. After that you need a German licence.</p>

<p>Whether you must retake tests depends on the country. German law waives the practical examination only for the states and licence classes listed in its Annex 11, and for C and CE that list is short. For everyone else, the rule that is relaxed is the <strong>driving school requirement</strong>, not the tests: you must still pass the <strong>theory and practical examinations</strong>, and you surrender the foreign licence when the German one is issued.</p>

<h2>The Visa Route Nobody Explains Properly</h2>

<p>Section <strong>24a of the Employment Ordinance (BeschV)</strong> is not a visa. It is the rule that lets the <strong>Federal Employment Agency consent</strong> to employing a foreign professional driver in road freight or bus transport &mdash; and it goes further than most guides realise:</p>

<ul>
    <li>Consent can be given <strong>even if you do not yet hold the German licence or code 95</strong>, provided your contract obliges you to obtain them.</li>
    <li>The conditions must allow the licence and qualification documents to be obtained <strong>within 15 months</strong>.</li>
    <li>There must be a concrete job offer with the same employer for afterwards, and proof that you hold the equivalent licence in your home country.</li>
    <li>Consent is granted for <strong>up to 15 months</strong>, and in justified cases up to six months more.</li>
</ul>

<p>So a driver from outside the EU does not need a recognised German vocational qualification to start &mdash; but does need an employer, a contract and the agency's approval.</p>

<h3>Skilled Worker residence permit</h3>

<p>If you already hold a recognised qualification, the Residence Act gives a skilled worker with vocational training a residence permit "for any qualified employment", subject to the general conditions.</p>

<h3>Opportunity Card (Chancenkarte)</h3>

<p>The Opportunity Card lets you come to look for work <strong>without a job offer</strong>, on a points system. The minimum requirements are:</p>

<ul>
    <li>a state-recognised foreign vocational qualification requiring at least <strong>two years</strong> of training, or a recognised higher-education degree;</li>
    <li><strong>A1 German or B2 English</strong>;</li>
    <li>at least <strong>six points</strong> on the statutory scale;</li>
    <li>proof that your living costs are covered &mdash; which the law defines as including <strong>adequate health insurance</strong>.</li>
</ul>

<p>It is issued for up to <strong>one year</strong> to search, allows <strong>20 hours a week</strong> of work plus two-week trial employment, and can be extended for up to two more years once you hold a contract or binding job offer with agency consent.</p>

<figure style="margin:32px 0;">
    <img src="/public/storage/blogs/how-to-get-a-transport-job-in-germany-logistics.jpg" alt="A logistics worker with a tablet beside a truck, freight train, container ship and cargo plane in Germany" style="width:100%;height:auto;border-radius:12px;" loading="lazy">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;text-align:center;">Road freight is the largest employer, but rail, air and port logistics hire too.</figcaption>
</figure>

<h2>How Many People Do This Job?</h2>

<p>Official headcounts are sparse. The BGL's own statistics compendium, built from federal data, reports <strong>652,557 people employed</strong> in commercial road freight, of whom <strong>481,180 were drivers</strong> &mdash; a figure collected only every five years, most recently in 2020. That is the best official order of magnitude available; be sceptical of any newer number quoted without a source.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Check your licence category</strong> against C and CE, and confirm what Germany requires to recognise or convert it.</li>
    <li><strong>Plan the code 95 route</strong> &mdash; full or accelerated basic qualification &mdash; and budget for the IHK examination.</li>
    <li><strong>Find an employer first</strong> if you are outside the EU, because the section 24a route runs through a contract and agency consent.</li>
    <li><strong>Apply for the right title:</strong> a work visa with agency consent, a skilled worker permit if your qualification is recognised, or the Opportunity Card to search on the spot.</li>
    <li><strong>Arrange health insurance</strong> before the visa appointment: covering your living costs, including insurance, is a legal condition.</li>
    <li><strong>Apply for the driver card</strong> with your state's authority once you are in Germany, and add ADR if you want dangerous goods work.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>How much do truck drivers earn in Germany?</h3>
<p>The Bundesagentur f&uuml;r Arbeit puts the median gross wage for full-time freight drivers at 3,048 euros a month at the end of 2024, about 36,600 euros a year. The agency publishes no average for this occupation.</p>

<h3>How short of drivers is Germany really?</h3>
<p>The industry position paper of January 2026 says more than 70,000 drivers are missing, with 30,000 to 35,000 retiring each year against 15,000 to 20,000 new entrants.</p>

<h3>What is code 95?</h3>
<p>The EU harmonised code entered on a licence to show the professional driver qualification under the BKrFQG. It is separate from the driving licence and must be refreshed every five years.</p>

<h3>Can I drive in Germany on a non-EU licence?</h3>
<p>For six months after establishing residence, extendable by up to six more months in some cases. After that you need a German licence, and for most third countries that means passing the theory and practical tests.</p>

<h3>Can I get a visa as a driver without a German licence?</h3>
<p>Yes, in principle. Section 24a of the Employment Ordinance lets the Federal Employment Agency consent for up to 15 months where your contract obliges you to obtain the licence and code 95 within that time.</p>

<h3>What is the Opportunity Card?</h3>
<p>A points-based card to come and look for work without a job offer. It needs a two-year qualification or a degree, A1 German or B2 English, at least six points and secured living costs including health insurance.</p>

<h3>Do I need German to work as a driver?</h3>
<p>It depends on the employer and the routes. The ADR dangerous goods training and examination, however, must be in German.</p>

<h3>Who issues the driver card in Germany?</h3>
<p>The authorities designated by each federal state, under the Fahrpersonalverordnung. The KBA produces the cards and maintains the register.</p>

<h2>People Also Search For</h2>

<h3>Berufskraftfahrer salary</h3>
<p>3,048 euros a month is the official median for full-time freight drivers.</p>

<h3>Code 95 Germany</h3>
<p>The professional driver qualification, refreshed every five years.</p>

<h3>CE licence Germany</h3>
<p>Tractor plus trailer or semi-trailer over 750 kg; normally issued at 21.</p>

<h3>Truck driver visa Germany</h3>
<p>Section 24a BeschV allows agency consent for up to 15 months.</p>

<h3>Chancenkarte requirements</h3>
<p>Six points, a two-year qualification or degree, and A1 German or B2 English.</p>

<h3>German minimum wage 2026</h3>
<p>13.90 euros an hour, rising to 14.60 euros in 2027.</p>

<h3>Fahrerkarte application</h3>
<p>Applied for at the authority designated by your federal state.</p>

<h3>ADR certificate Germany</h3>
<p>Training and examination run by the IHK, in German.</p>

<h2>More Job Guides</h2>

<p>Comparing driving and European work routes? These cover them:</p>

<ul>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; another route into German employment and what it pays.</li>
    <li><a href="/blog/devops-engineer-jobs-in-germany">DevOps Engineer Jobs in Germany</a> &mdash; the skilled worker visa from the technology side.</li>
    <li><a href="/blog/how-to-become-a-long-haul-truck-driver-in-usa">How to Become a Long-Haul Truck Driver in USA</a> &mdash; the American route, its permit rules and pay.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; US licensing, hours of service and what the work really pays.</li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a> &mdash; Gulf driving work and its licence rules.</li>
    <li><a href="/blog/bus-driver-jobs-in-canada">Bus Driver Jobs in Canada</a> &mdash; passenger driving with a provincial licence and Job Bank pay.</li>
    <li><a href="/blog/how-to-get-a-fleet-driver-job-in-canada">How to Get a Fleet Driver Job in Canada</a> &mdash; AZ and Class 1 licences, provincial training hours, air brakes and Job Bank wages.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using BGL, Bundesagentur f&uuml;r Arbeit and BALM publications and German law (FeV, BKrFQG, FPersV, BeschV and AufenthG). Rules, pay and visa conditions change. Always confirm with the German authorities or the employer before applying.</p>
HTML;
    }
}
