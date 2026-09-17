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
 * "How to Become an Auto Mechanic in Australia" — a guide for someone asking
 * how to qualify as a light vehicle mechanic. The Mechanic Jobs in Saudi Arabia
 * guide covers the Gulf market, and the Visa Sponsorship Jobs in Australia
 * guide covers sponsorship in general, so this one stays on the apprenticeship,
 * the qualification, award pay while training, state licences and the routes
 * for experienced and overseas-trained mechanics.
 *
 * Corrections and clarifications to the draft (checked against training.gov.au,
 * NSW and Victorian apprenticeship bodies, Your Career, the Fair Work Vehicle
 * Repair, Services and Retail Award, NSW Fair Trading, the WA Government, the
 * Australian Refrigeration Council and Home Affairs, September 2026):
 *
 * 1. The draft says there is no way to qualify without an apprenticeship. NSW
 *    recognises trade skills gained through on-the-job experience, with at
 *    least three years' experience and recognition of prior learning, and WA
 *    accepts third-party skills assessments for its repairer certificate.
 *
 * 2. The draft says Year 10 is the minimum. AUR30620 lists no entry
 *    requirements, and the award sets apprentice rates for people who have
 *    not completed Year 12.
 *
 * 3. The draft says the apprenticeship runs three to four years. The nominal
 *    term is 48 months in NSW and Victoria, or 36 months with advanced entry.
 *
 * 4. The draft's pay comes from SEEK ($80,000-$90,000) and SalaryExpert. Your
 *    Career lists $1,405 a week for Motor Mechanics (General), about $73,000 a
 *    year, and the award tradesperson minimum is $1,119.10 a week from 1 July
 *    2026.
 *
 * 5. The draft points readers to "Australian Apprenticeships Pathways". That
 *    site no longer carries apprenticeship content; the official portal is
 *    apprenticeships.gov.au.
 *
 * 6. The draft leaves out state licences. NSW requires a motor vehicle
 *    tradesperson certificate and WA a motor vehicle repairer's certificate,
 *    and air conditioning work needs a refrigerant handling licence.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AutoMechanicJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-motor-mechanic-jobs.html';

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
        $title = 'How to Become an Auto Mechanic in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Most auto mechanics in Australia qualify through a four-year apprenticeship and AUR30620. There is no Year 10 rule, experienced workers can have skills recognised, and official pay is $1,405 a week, below the figures most guides quote.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-become-an-auto-mechanic-in-australia.jpg',
                'tags' => 'how to become a mechanic in australia, auto mechanic apprenticeship australia, aur30620, certificate iii in light vehicle mechanical technology, mechanic apprentice wage australia, motor mechanic salary australia, motor vehicle tradesperson certificate, motor mechanic 321211, mechanic visa australia',
                'meta_title' => 'How to Become an Auto Mechanic in Australia (2026 Guide)',
                'meta_description' => 'How to become an auto mechanic in Australia in 2026: the AUR30620 apprenticeship, apprentice award pay, state licences and routes for experienced workers.',
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
            ['name' => 'Australian Workshops, Dealerships & Service Centres (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-motor-mechanic-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Motor Mechanic — Light Vehicle Apprentice and Qualified Technician Roles, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Workshop hours, usually weekdays with some Saturday work',
                'language' => 'English',
                // Apprentice and qualified rates differ by year, age and
                // employer, so no single band is quoted on the listing.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Light vehicle mechanic apprenticeships and qualified technician roles with Australian workshops, dealerships and service centres. AUR30620 is the trade qualification.',
                'seo_keywords' => 'motor mechanic jobs australia, apprentice mechanic jobs, light vehicle technician jobs, automotive technician australia, mechanic apprenticeship',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Independent workshops, dealerships and franchise service centres across Australia hire apprentice and qualified light vehicle mechanics to service, diagnose and repair cars and light commercial vehicles.</p>

<h3>What the work involves</h3>
<p>Scheduled servicing, brake, steering and suspension work, engine and transmission repairs, electrical and computer diagnostics, and increasingly hybrid and electric vehicle systems.</p>

<h3>Requirements</h3>
<ul>
    <li>Qualified roles: AUR30620 Certificate III in Light Vehicle Mechanical Technology or an equivalent recognised trade qualification</li>
    <li>Apprentice roles: no formal entry requirement for the qualification itself; employers set their own</li>
    <li>In NSW, a motor vehicle tradesperson certificate; in WA, a motor vehicle repairer's certificate to work unsupervised</li>
    <li>An automotive air conditioning licence for air conditioning work</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>Award minimum.</strong> A qualified tradesperson under the Vehicle Repair, Services and Retail Award earns at least $1,119.10 a week from 1 July 2026</li>
    <li><strong>Apprentices.</strong> Junior apprentices are paid a rising percentage of that rate, from 50 per cent in the first year; adult apprentices start at $895.28 a week</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay, licence requirements and apprenticeship terms are set by each employer, the award and state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Most people become an auto mechanic in Australia through an apprenticeship that leads to AUR30620 Certificate III in Light Vehicle Mechanical Technology.</strong> The standard term is <strong>four years</strong>, working in a workshop while studying with a registered training organisation such as TAFE. The qualification itself has <strong>no entry requirements</strong>, so there is no Year 10 rule, and experienced workers can have their skills recognised instead. Official data puts earnings at about <strong>$1,405 a week</strong>, lower than the $80,000 to $90,000 many guides quote from job ads.</p>

<p>This guide covers the qualification, how the apprenticeship works, what apprentices and qualified mechanics are paid under the award, the state licences you need, and the routes for experienced and overseas-trained mechanics.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-motor-mechanic-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#127462;&#127482; Browse Motor Mechanic Jobs in Australia &rarr;
    </a>
</div>

<h2>The Qualification: AUR30620</h2>

<p>The national trade qualification for car mechanics is <strong>AUR30620 Certificate III in Light Vehicle Mechanical Technology</strong>. On training.gov.au it is current (Release 5, July 2026), made up of <strong>36 units: 20 core and 16 elective</strong>, and it replaced the older AUR30616, which it is mapped as equivalent to.</p>

<ul>
    <li><strong>Entry requirements:</strong> the qualification lists none. Employers choose who they take on as apprentices.</li>
    <li><strong>Before an apprenticeship:</strong> AUR20520 Certificate II in Automotive Servicing Technology is a current pre-apprenticeship option. It is not required, but it gives you basic workshop skills and something to show employers.</li>
    <li><strong>Electric vehicles:</strong> AUR30620 includes electric vehicle electives such as AURETH101 Depower and reinitialise battery electric vehicles. There is also a separate qualification, AUR32721 Certificate III in Automotive Electric Vehicle Technology, and skill sets for battery electric and hybrid vehicle servicing and repair.</li>
</ul>

<h2>How Long the Apprenticeship Takes</h2>

<p>Many guides say "three to four years". The official terms are more specific:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">Official term</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Full-time apprenticeship, NSW</strong></td><td style="padding:10px;"><strong>48 months nominal term</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Advanced entry, NSW</td><td style="padding:10px;">36 months nominal term</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Full-time apprenticeship, Victoria</td><td style="padding:10px;">Up to 48 months</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Part-time apprenticeship, Victoria</td><td style="padding:10px;">Up to 72 months</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">School-based, NSW</td><td style="padding:10px;">Two years part-time at school, then three years full-time</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Training Services NSW, Victorian Registration and Qualifications Authority, Apprenticeships Victoria and NSW Department of Education, September 2026.</p>
</div>

<p>In your first year, expect servicing work under a qualified mechanic, such as oil and filter changes, tyres and brake checks, while you start the classroom part of the qualification. The work gets more complex as you move through the units.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-an-auto-mechanic-in-australia-under-the-hood.jpg"
         alt="A mechanic in navy overalls and black gloves tightening a bolt in the engine bay of a car in a workshop, with a car on a hoist, a tool chest and an Australian flag behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Is an Apprenticeship the Only Way?</h2>

<p>For most school leavers, yes: it is how the trade is learned. But the claim that you <strong>cannot</strong> qualify without one is not right everywhere. The government's Your Career site says "extensive experience or an apprenticeship is usually required".</p>

<ul>
    <li><strong>NSW, experienced workers:</strong> the NSW Government recognises trade skills gained "through formal training or on-the-job experience, in Australia or overseas", including light vehicle mechanical technology. Its trade pathway for experienced workers asks for at least three years' work experience and uses recognition of prior learning (RPL).</li>
    <li><strong>Western Australia:</strong> alongside a qualification, WA accepts a third-party skills assessment or certification test, approved in-house training, or a Trades Recognition Australia assessment for its motor vehicle repairer's certificate.</li>
    <li><strong>NSW apprentices</strong> must finish their apprenticeship before applying for a tradesperson certificate.</li>
</ul>

<h2>What Apprentices and Mechanics Are Paid</h2>

<p>Most guides quote $80,000 to $90,000 a year from SEEK listings. The government's Your Career profile for Motor Mechanics (General) lists <strong>$1,405 a week</strong>, about <strong>$73,000 a year</strong>. Advertised salaries for experienced mechanics can be higher, but they are advertised figures, not the typical wage.</p>

<h3>Award minimums from 1 July 2026</h3>

<p>Most workshop mechanics are covered by the <strong>Vehicle Repair, Services and Retail Award 2020</strong>. A qualified <strong>tradesperson (Level R6)</strong> must be paid at least <strong>$1,119.10 a week</strong>, or $29.45 an hour. Apprentice rates are set as a share of that rate:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Year of apprenticeship</th>
            <th style="padding:10px;text-align:left;">Junior, not completed Year 12</th>
            <th style="padding:10px;text-align:left;">Junior, completed Year 12</th>
            <th style="padding:10px;text-align:left;">Adult apprentice</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">First year</td><td style="padding:10px;">50%</td><td style="padding:10px;">55%</td><td style="padding:10px;">$895.28 a week</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Second year</td><td style="padding:10px;">60%</td><td style="padding:10px;">65%</td><td style="padding:10px;">$978.10 a week</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Third year</td><td style="padding:10px;">75%</td><td style="padding:10px;">75%</td><td style="padding:10px;">$1,004.90 a week</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Fourth year</td><td style="padding:10px;">88%</td><td style="padding:10px;">88%</td><td style="padding:10px;">$1,029.10 a week</td></tr>
    </tbody>
</table>
<p style="font-size:13px;color:#6b7280;margin-top:8px;">Vehicle Repair, Services and Retail Award 2020 (MA000089), clauses 16.2, 16.9 and 16.10, rates from 1 July 2026. Junior percentages are of the $1,119.10 tradesperson rate.</p>
</div>

<p>That puts a junior first-year apprentice who has not finished Year 12 on at least <strong>$559.55 a week</strong>, rising to <strong>$984.81</strong> in the fourth year. Employers can pay more than the award, so check the offer.</p>

<h2>Licences You May Need</h2>

<p>This is what most guides leave out. Whether you need a licence depends on the state:</p>

<ul>
    <li><strong>New South Wales:</strong> you need a <strong>motor vehicle tradesperson certificate</strong> to carry out work that affects the mechanical operation or structure of a vehicle. Motor mechanic is one of the certificate classes, and AUR30620 is an accepted qualification. Apprentices work under a certificate holder, and the business needs a motor vehicle repairer licence.</li>
    <li><strong>Western Australia:</strong> you need a <strong>motor vehicle repairer's certificate</strong> to work unsupervised, in classes such as general mechanical repair, and the business needs its own licence.</li>
    <li><strong>Queensland:</strong> the state's motor industry licences cover dealers, salespeople, wreckers and brokers, not repairers.</li>
    <li><strong>Air conditioning, everywhere:</strong> handling refrigerant without a licence is an offence. Vehicle air conditioning work needs the Australian Refrigeration Council's <strong>automotive air conditioning licence (AAC02)</strong>.</li>
</ul>

<p>Check the rules with the fair trading or consumer protection agency in your state before you work unsupervised.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/how-to-become-an-auto-mechanic-in-australia-under-the-car.jpg"
         alt="A smiling mechanic in navy overalls working with a ratchet under a car raised on a hoist in a bright workshop, with tool chests and an Australian flag in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>For Overseas-Trained Mechanics</h2>

<p><strong>Motor Mechanic (General), ANZSCO 321211</strong>, is on both the <strong>Core Skills Occupation List</strong> and the <strong>Medium and Long-term Strategic Skills List</strong>. Home Affairs lists it for the Skills in Demand (subclass 482) Core Skills stream, the 186, 189, 190, 491 and 494 visas.</p>

<p>The assessing authority is <strong>Trades Recognition Australia (TRA)</strong>, which runs different skills assessment programs depending on your country and the visa you are applying for. Check TRA's current requirements for your passport before you apply.</p>

<h2>How to Get Started, Step by Step</h2>

<ol>
    <li><strong>Get a feel for the trade.</strong> Work experience in a workshop, or AUR20520 Certificate II in Automotive Servicing Technology, helps you decide and gives employers something to see.</li>
    <li><strong>Find an employer.</strong> Apply to independent workshops, dealerships and service centres, and search the official portal at apprenticeships.gov.au.</li>
    <li><strong>Sign the training contract.</strong> An Apprentice Connect Australia Provider helps you and your employer set up the apprenticeship and explains any government support.</li>
    <li><strong>Complete AUR30620.</strong> Work in the workshop and study with your training organisation for the nominal term, usually four years.</li>
    <li><strong>Get licensed where your state requires it</strong>, such as the NSW tradesperson certificate or the WA repairer's certificate, plus the automotive air conditioning licence if you do that work.</li>
    <li><strong>Keep training.</strong> Hybrid and electric vehicle skill sets are the obvious next step.</li>
</ol>

<p>Motor mechanics appear on the Australian Apprenticeships Priority List as Automotive Technician (General), which can affect the government support available. Check the current incentives on apprenticeships.gov.au, because the amounts change.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I become a mechanic in Australia without an apprenticeship?</h3>
<p>Usually the apprenticeship is the route, but not the only one. NSW recognises trade skills gained on the job, with at least three years' experience and recognition of prior learning, and WA accepts third-party skills assessments for its repairer's certificate.</p>

<h3>How long does a mechanic apprenticeship take in Australia?</h3>
<p>The nominal term is 48 months, or four years, full-time in NSW and Victoria. Advanced entry in NSW is 36 months, and a part-time apprenticeship in Victoria can run up to 72 months.</p>

<h3>Do I need Year 10 or Year 12 to start?</h3>
<p>No. AUR30620 lists no entry requirements, and the award sets apprentice rates for people who have not completed Year 12. Individual employers can still set their own preferences.</p>

<h3>What qualification do mechanics need in Australia?</h3>
<p>AUR30620 Certificate III in Light Vehicle Mechanical Technology, which has 36 units and replaced AUR30616. AUR20520 Certificate II in Automotive Servicing Technology is an optional pre-apprenticeship course.</p>

<h3>How much do apprentice mechanics earn?</h3>
<p>Under the Vehicle Repair, Services and Retail Award from 1 July 2026, junior apprentices earn 50 to 55 per cent of the $1,119.10 tradesperson rate in the first year, rising to 88 per cent. Adult apprentices start at $895.28 a week.</p>

<h3>How much does a qualified mechanic earn in Australia?</h3>
<p>Your Career lists $1,405 a week for Motor Mechanics (General), about $73,000 a year. The award minimum for a qualified tradesperson is $1,119.10 a week from 1 July 2026.</p>

<h3>Do mechanics need a licence in Australia?</h3>
<p>In some states. NSW requires a motor vehicle tradesperson certificate and WA a motor vehicle repairer's certificate to work unsupervised. Vehicle air conditioning work needs an automotive air conditioning licence everywhere.</p>

<h3>Can overseas mechanics get a visa for Australia?</h3>
<p>Motor Mechanic (General), 321211, is on the Core Skills Occupation List and the Medium and Long-term Strategic Skills List, with Trades Recognition Australia as the assessing authority.</p>

<h2>People Also Search For</h2>

<h3>AUR30620 Certificate III in Light Vehicle Mechanical Technology</h3>
<p>The current national trade qualification for car mechanics, with 36 units.</p>

<h3>Apprentice mechanic wage Australia</h3>
<p>From 50 per cent of the $1,119.10 tradesperson rate for a first-year junior apprentice.</p>

<h3>Mechanic salary Australia</h3>
<p>About $1,405 a week according to Your Career, around $73,000 a year.</p>

<h3>Pre-apprenticeship mechanic course</h3>
<p>AUR20520 Certificate II in Automotive Servicing Technology, optional but useful.</p>

<h3>Motor vehicle tradesperson certificate NSW</h3>
<p>Required in NSW for work that affects a vehicle's mechanical operation or structure.</p>

<h3>Mechanic skills assessment Australia</h3>
<p>Trades Recognition Australia assesses Motor Mechanic (General) for migration.</p>

<h3>Is motor mechanic on the skilled occupation list</h3>
<p>Yes, 321211 is on the Core Skills Occupation List and the MLTSSL.</p>

<h3>Electric vehicle mechanic course Australia</h3>
<p>AUR32721 Certificate III in Automotive Electric Vehicle Technology, plus EV and hybrid skill sets.</p>

<h2>More Job Guides</h2>

<p>Comparing trades and routes into work in Australia? These cover them:</p>

<ul>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; another licensed trade, and how its apprenticeship and licensing work.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; entry into the building trades and the award pay behind it.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; how employer sponsorship works and which occupation lists apply.</li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a> &mdash; entry-level roles while you look for an apprenticeship.</li>
    <li><a href="/blog/mechanic-jobs-in-saudi-arabia">Mechanic Jobs in Saudi Arabia</a> &mdash; the same trade in the Gulf, under Saudi sponsorship rules.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or migration advice. Award rates, licence rules, apprenticeship terms and occupation lists change over time. Confirm the current position with training.gov.au, the Fair Work Ombudsman, your state's apprenticeship and licensing bodies and the Department of Home Affairs before relying on it.</p>
HTML;
    }
}
