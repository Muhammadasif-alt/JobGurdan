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
 * "How to Start a Career in Refrigeration and Air Conditioning" — the
 * training and specialisation page of the HVAC/R cluster. It owns the route
 * in (training, EPA 608, the apprentice rule) and the move into commercial
 * and industrial refrigeration; the pay and demand comparison lives on the
 * USA and Canada page.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. A specialisation pay table ($48,000-$72,000 residential up to
 *    $66,000-$135,000+ ammonia, and a marine row) built from Glassdoor,
 *    ZipRecruiter and listings. BLS does not split the occupation by
 *    specialism; its industry medians are used instead.
 *
 * 2. Lineage hourly ranges and a union rate with a sign-on bonus quoted
 *    from listings, five Indeed buttons, and career links for CIMCO and
 *    CoolSys that no longer resolve.
 *
 * 3. "RETA CARO and CIRO are effectively required". They are widely asked
 *    for in ammonia plants but are voluntary credentials.
 *
 * 4. A Texas step that described only the contractor licence. Technicians
 *    working under a licensed contractor need their own TDLR registration
 *    or certification.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class RefrigerationAcCareerBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.usa.gov/job-search';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
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
        $title = 'How to Start a Career in Refrigeration and Air Conditioning';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Train for six months to two years or apprentice, pass EPA Section 608, then specialise. On BLS data, warehousing and food plants pay above the $60,070 median at HVAC contractors, though not by the margins job boards claim.',
                'content' => $content,
                'featured_image' => 'blogs/refrigeration-ac-career.jpg',
                'tags' => 'refrigeration technician career, how to become an hvac technician, epa 608 types, refrigeration technician salary, industrial refrigeration jobs, reta caro ciro, ammonia refrigeration technician, hvacr training',
                'meta_title' => 'How to Start a Career in Refrigeration and AC',
                'meta_description' => 'Start a refrigeration and AC career: training routes, EPA 608 types and the apprentice rule, Texas registration, RETA credentials and BLS pay by industry.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Cold Storage, Food Processing & Commercial Refrigeration Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-refrigeration-technician-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        Job::updateOrCreate(
            [
                'position' => 'Refrigeration Technician — US Cold Storage, Food Plants and Commercial Refrigeration',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; plant roles often run rotating shifts because refrigeration systems run around the clock',
                'language' => 'English',
                // BLS industry medians run from about $60,000 at contractors to
                // over $74,000 at wholesalers, so no single range is honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Refrigeration technician roles in US cold storage, food processing and commercial refrigeration. EPA 608 Universal is the usual entry ticket.',
                'seo_keywords' => 'refrigeration technician jobs, industrial refrigeration jobs, ammonia refrigeration technician, cold storage maintenance jobs, commercial refrigeration technician',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Cold storage warehouses, food and beverage plants, supermarket chains and commercial refrigeration contractors across the United States recruit refrigeration technicians, including entry-level candidates who will be trained on industrial systems.</p>

<h3>What the work involves</h3>
<ul>
    <li>Routine inspections, logs and preventive maintenance on compressors, condensers and evaporators</li>
    <li>Servicing supermarket cases, walk-in coolers and freezers</li>
    <li>On industrial sites, operating and maintaining ammonia or CO2 systems under Process Safety Management procedures</li>
</ul>

<h3>Requirements employers list</h3>
<ul>
    <li><strong>EPA Section 608 certification</strong>, Universal preferred</li>
    <li>A trade school programme or apprenticeship</li>
    <li>For ammonia plants, RETA CARO or CIRO credentials are often preferred and can be earned on the job</li>
</ul>

<p><strong>Pay:</strong> BLS reports May 2025 medians of $66,150 in warehousing and storage and $64,760 in food manufacturing for the occupation. Individual employers set their own rates.</p>

<p><strong>Note:</strong> certification and licensing rules are set by the EPA, OSHA and each state, not by JobGader. Confirm them before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Refrigeration and air conditioning is one trade with two directions. Most people enter it the same way &mdash; training, then a federal refrigerant certificate, then a first job under an experienced technician &mdash; and then choose between comfort cooling in homes and offices, or refrigeration in supermarkets, cold stores and food plants. This page sets out the route in and what the official data says about where specialising pays.</p>

<p>The official name for the trade is HVAC/R: heating, ventilation, air conditioning and refrigeration. BLS counts all of it as one occupation, heating, air conditioning and refrigeration mechanics and installers, with a May 2025 median of <strong>$61,010</strong>.</p>

<h2>Step 1: Finish School</h2>

<p>A high school diploma or equivalent is the baseline for training programmes. BLS recommends courses in maths, physics, electronics and shop or mechanical drawing for anyone still at school.</p>

<h2>Step 2: Choose a Training Route</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Trade school or college</th>
            <th style="padding:10px;text-align:left;">Apprenticeship</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Length</strong></td><td style="padding:10px;">Six months to two years</td><td style="padding:10px;">Several years, usually three to five</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Cost</strong></td><td style="padding:10px;">Tuition</td><td style="padding:10px;">Paid work from the start</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Covers</strong></td><td style="padding:10px;">Electricity, refrigeration cycle, comfort systems, troubleshooting</td><td style="padding:10px;">The same, learned on live jobs alongside classroom hours</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>EPA 608 benefit</strong></td><td style="padding:10px;">None during training</td><td style="padding:10px;">A registered apprentice may work under supervision for up to two years</td></tr>
    </tbody>
</table>
</div>

<h2>Step 3: Pass EPA Section 608</h2>

<p>Federal rules under Section 608 of the Clean Air Act require anyone who maintains, services, repairs or disposes of equipment that could release refrigerant to be certified. The EPA issues four certifications:</p>

<ul>
    <li><strong>Type I</strong> &mdash; small appliances</li>
    <li><strong>Type II</strong> &mdash; high-pressure and very high-pressure equipment</li>
    <li><strong>Type III</strong> &mdash; low-pressure equipment</li>
    <li><strong>Universal</strong> &mdash; all types of equipment</li>
</ul>

<p>Each exam pairs a <strong>core section</strong> with the type section. For Universal certification the core test must be taken proctored; an open-book core result does not count. <strong>Section 608 credentials do not expire.</strong> Commercial and industrial refrigeration employers almost always ask for Universal.</p>

<p><strong>The apprentice exception is narrower than most guides say.</strong> An apprentice may work without certification only while closely and continually supervised by a certified technician, only if registered with the Department of Labor's Office of Apprenticeship or a recognised state council, and only for two years from first registering.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/refrigeration-ac-career-rooftop.jpg"
         alt="A refrigeration technician in a white hard hat connecting a gauge manifold to a large rooftop condensing unit, with a city skyline at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Step 4: Get a First Job and Learn Under Supervision</h2>

<p>Most technicians start as a helper or apprentice, carrying out inspections, filter changes and simple repairs while a senior technician supervises. This is where the specialism choice gets made. Large cold storage and food companies hire entry-level candidates with trade school coursework or an EPA Universal certificate and then train them on industrial ammonia systems, which is one of the most direct ways into the higher-paid end of the trade.</p>

<h2>Step 5: Add Credentials That Match the Direction You Choose</h2>

<ul>
    <li><strong>NATE</strong> (North American Technician Excellence) &mdash; the best-known voluntary credential for HVAC/R service technicians.</li>
    <li><strong>RETA CARO and CIRO</strong> &mdash; the Refrigerating Engineers and Technicians Association's Certified Assistant Refrigeration Operator and Certified Industrial Refrigeration Operator credentials. They are voluntary, but ammonia plants ask for them often, and many employers pay for them once you are hired.</li>
    <li><strong>Manufacturer training</strong> on specific compressor, rack and control systems.</li>
</ul>

<h2>Step 6: Check State and Local Licensing</h2>

<p>There is no national licence beyond EPA 608. Some states license only contractors, some license or register technicians, and some leave it to cities. Texas is a useful example of how detailed it gets:</p>

<ul>
    <li>A technician doing air conditioning and refrigeration work for a licensed contractor needs a <strong>TDLR technician registration or certification</strong>.</li>
    <li>A <strong>contractor licence</strong> needs 48 months of practical experience under a licensed contractor within the previous 72 months, or 36 months within 48 months for someone who has held technician certification for the past year.</li>
</ul>

<h2>Which Pays More: Comfort Cooling or Refrigeration?</h2>

<p>BLS does not publish separate pay for residential, commercial and industrial refrigeration technicians. It does publish the occupation's pay by industry, which is the closest official proxy. May 2025 medians:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Where the technician works</th>
            <th style="padding:10px;text-align:left;">Median annual wage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hotels and accommodation</td><td style="padding:10px;">$56,570</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Building equipment contractors (most residential and light commercial work)</td><td style="padding:10px;">$60,070</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Administrative and support services</td><td style="padding:10px;">$61,210</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Educational services</td><td style="padding:10px;">$62,520</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Food manufacturing</td><td style="padding:10px;">$64,760</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Warehousing and storage (including cold storage)</td><td style="padding:10px;">$66,150</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Hospitals</td><td style="padding:10px;">$69,450</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Real estate</td><td style="padding:10px;">$69,870</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Federal government</td><td style="padding:10px;">$73,900</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Merchant wholesalers, durable goods</td><td style="padding:10px;">$74,210</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Local government</td><td style="padding:10px;">$79,360</td></tr>
    </tbody>
</table>
</div>

<p>What this shows:</p>

<ul>
    <li><strong>Refrigeration-heavy industries pay more, but modestly.</strong> Warehousing and storage is about 10 per cent above building equipment contractors, food manufacturing about 8 per cent.</li>
    <li><strong>The biggest jumps are elsewhere.</strong> Hospitals, wholesalers and government pay more than any refrigeration-heavy industry shown.</li>
    <li><strong>Job-board ranges of $66,000 to $135,000 for ammonia technicians</strong> mix senior operators, overtime and on-call pay into one figure. They are not a typical salary.</li>
</ul>

<h3>Why industrial refrigeration still pays a premium</h3>

<p>Ammonia and transcritical CO2 systems are high-hazard. Facilities holding more than 10,000 pounds of anhydrous ammonia fall under OSHA's Process Safety Management standard, with written procedures, training and inspection requirements. Plants also run around the clock, so shift and overtime pay add to the base. The straight-time medians above leave overtime out.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/refrigeration-ac-career-service.jpg"
         alt="An AC and refrigeration technician in a navy uniform working on the wiring and compressor of a condensing unit, with refrigerant gauges attached and a service van behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where Refrigeration Technicians Work</h2>

<ul>
    <li><strong>Cold storage and logistics</strong> &mdash; warehouses holding frozen and chilled goods, often on ammonia systems.</li>
    <li><strong>Food and beverage plants</strong> &mdash; meat, dairy, breweries and processed food, with large industrial systems.</li>
    <li><strong>Commercial refrigeration contractors</strong> &mdash; supermarket racks, display cases and walk-ins.</li>
    <li><strong>Ice rinks and sports facilities</strong> &mdash; a small but specialised niche.</li>
</ul>

<p>Apply through the employer's own careers page. Search "refrigeration technician", "refrigeration operator" or "maintenance technician &ndash; refrigeration" rather than "HVAC", which mostly returns comfort-cooling jobs.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the difference between an HVAC technician and a refrigeration technician?</h3>
<p>BLS counts them as one occupation. In practice, HVAC technicians focus on heating and comfort cooling in homes and offices, while refrigeration technicians work on cold storage, supermarket and food plant systems. Both need EPA 608 certification.</p>

<h3>What are the EPA 608 certification types?</h3>
<p>Type I for small appliances, Type II for high-pressure equipment, Type III for low-pressure equipment, and Universal for all three. Each exam combines a core section with the type section, and certification does not expire.</p>

<h3>Can an apprentice work on refrigerant without EPA 608?</h3>
<p>Only if registered with the Department of Labor's Office of Apprenticeship or a recognised state council, closely supervised by a certified technician, and within two years of first registering.</p>

<h3>Do refrigeration technicians earn more than HVAC technicians?</h3>
<p>Modestly, on BLS data. The occupation's median was $66,150 in warehousing and storage and $64,760 in food manufacturing, against $60,070 at building equipment contractors. Overtime on plant shifts widens the gap.</p>

<h3>Is RETA certification required?</h3>
<p>No. CARO and CIRO are voluntary, but ammonia plants ask for them often and many employers pay for them after hiring.</p>

<h3>Do I need a licence to be a refrigeration technician?</h3>
<p>EPA 608 is required everywhere in the US. State rules vary: Texas requires a TDLR technician registration or certification, while other states license only contractors or leave it to cities.</p>

<h3>Can I start in refrigeration with no experience?</h3>
<p>Yes. Large cold storage and food companies hire entry-level candidates with trade school coursework or an EPA Universal certificate and train them on industrial systems.</p>

<h3>How long does it take to start working?</h3>
<p>A trade school programme takes six months to two years. An apprenticeship lets you earn from the first day, but full competence in industrial refrigeration takes several years.</p>

<h2>People Also Search For</h2>

<h3>Refrigeration technician salary</h3>
<p>$66,150 median in warehousing and storage, May 2025.</p>

<h3>EPA 608 Universal</h3>
<p>Covers all equipment types and never expires.</p>

<h3>RETA CARO vs CIRO</h3>
<p>Assistant operator and industrial operator credentials for ammonia plants.</p>

<h3>Ammonia refrigeration technician</h3>
<p>High-hazard plant work under OSHA Process Safety Management.</p>

<h3>HVAC/R apprenticeship</h3>
<p>Usually three to five years of paid work and classroom hours.</p>

<h3>Texas HVAC license requirements</h3>
<p>TDLR registration for technicians; 48 months' experience for a contractor licence.</p>

<h3>Commercial refrigeration technician</h3>
<p>Supermarket racks, display cases and walk-in coolers and freezers.</p>

<h3>NATE certification</h3>
<p>A voluntary credential widely recognised by HVAC/R employers.</p>

<h2>More Job Guides</h2>

<p>The rest of the HVAC/R cluster and related trades:</p>

<ul>
    <li><a href="/blog/hvac-technician-jobs-in-the-usa-and-canada">HVAC Technician Jobs in the USA and Canada</a> &mdash; demand, pay by state and province, and Canadian certification.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; Gulf recruitment rules and the Saudi skills exam.</li>
    <li><a href="/blog/industrial-vs-house-wiring-electrician-which-pays-more">Industrial vs House Wiring Electrician: Which Pays More</a> &mdash; the same residential-versus-industrial question for electricians.</li>
    <li><a href="/blog/maintenance-technician-jobs-in-usa">Maintenance Technician Jobs in USA</a> &mdash; plant and building maintenance roles.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the wider US trades market and its visa routes.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not career or legal advice. Pay data is from the BLS Occupational Employment and Wage Statistics survey for May 2025; certification rules are from the EPA and the Texas Department of Licensing and Regulation. Confirm current rules with your state board.</p>
HTML;
    }
}
