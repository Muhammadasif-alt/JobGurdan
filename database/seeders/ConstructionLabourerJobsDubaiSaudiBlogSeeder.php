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
 * "Construction Labourer Jobs in Dubai and Saudi Arabia" — the helper-level
 * page of the construction cluster. It sets the UAE and Saudi rules side by
 * side and shows how a labourer moves up to a skilled trade. The wider Saudi
 * market (giga-projects, engineers, managers) stays on the existing
 * construction-jobs-in-saudi-arabia-with-visa-sponsorship page.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Pay ranges of AED 1,500-2,500 and SAR 2,000-2,200 taken from job boards
 *    and Saudi listing sites, and a "1,877 vacancies" count. Job-board pay
 *    and counts are not republished, and neither country sets a minimum
 *    wage for expatriate workers.
 *
 * 2. Links to job boards and individual listings.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class ConstructionLabourerJobsDubaiSaudiBlogSeeder extends Seeder
{
    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

    private const SAUDI_APPLY_URL = 'https://www.hrsd.gov.sa/en';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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
        $title = 'Construction Labourer Jobs in Dubai and Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Labourer and helper jobs in Dubai and Saudi Arabia compared side by side: who pays the visa, how your wage is protected, the summer midday break, and how a helper moves up to a skilled trade.',
                'content' => $content,
                'featured_image' => 'blogs/construction-labourer-jobs-dubai-saudi.jpg',
                'tags' => 'construction labourer jobs dubai, labour jobs saudi arabia, construction helper jobs gulf, uae wage protection system, saudi labour law article 40, uae midday break, helper to mason, gulf labourer visa',
                'meta_title' => 'Construction Labourer Jobs in Dubai and Saudi Arabia',
                'meta_description' => 'Construction labourer and helper jobs in Dubai and Saudi Arabia: who pays the visa, wage protection, the midday break and moving up to a trade.',
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

        $uaeAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UAE Building & Infrastructure Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-construction-labourer-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Construction Labourer and Helper — UAE Building and Infrastructure Contractors',
                'advertiser_id' => $uaeAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $uaeLocation->id,
                'description' => $this->uaeJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time site hours; outdoor work stops from 12:30pm to 3pm between 15 June and 15 September',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UAE_APPLY_URL,
                'meta_description' => 'General labourer and trade helper roles with UAE building and infrastructure contractors, recruited on MOHRE work permits.',
                'seo_keywords' => 'construction labourer jobs dubai, helper jobs uae, general labour uae, mohre work permit, gulf labourer',
            ]
        );

        $saudiAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Saudi Building & Infrastructure Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'saudi-construction-labourer-aggregated']
        );

        $saudiLocation = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Nationwide', 'country' => 'Saudi Arabia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Construction Labourer and Helper — Saudi Building and Infrastructure Contractors',
                'advertiser_id' => $saudiAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $saudiLocation->id,
                'description' => $this->saudiJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under the Saudi Labour Law; outdoor work under direct sun is banned from noon to 3pm in summer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::SAUDI_APPLY_URL,
                'meta_description' => 'General labourer and trade helper roles with Saudi building and infrastructure contractors, on employer-sponsored work visas registered on Qiwa.',
                'seo_keywords' => 'construction labourer jobs saudi arabia, helper jobs saudi, labour visa saudi, qiwa contract, gulf labourer',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Building and infrastructure contractors across the UAE recruit general labourers and trade helpers from overseas on MOHRE work permits.</p>

<h3>What the work involves</h3>
<ul>
    <li>Carrying and stacking materials, mixing mortar and concrete</li>
    <li>Assisting masons, steel fixers, shuttering carpenters and tilers</li>
    <li>Keeping the site clean and access routes clear</li>
</ul>

<h3>What the law says</h3>
<ul>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021, the employer may not charge the worker recruitment and employment costs, directly or indirectly</li>
    <li>The UAE sets <strong>no statutory minimum wage</strong> for private-sector expatriate workers. The contract is the only binding figure</li>
    <li>The Wage Protection System runs under Ministerial Resolution No. 340 of 2026</li>
    <li>Outdoor work under direct sun stops from 12:30pm to 3pm between 15 June and 15 September</li>
</ul>

<p><strong>Never pay anyone for a UAE job.</strong> Visa, wage and permit rules are set by MOHRE, not by JobGader; confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function saudiJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Building and infrastructure contractors across Saudi Arabia recruit general labourers and trade helpers from overseas on employer-sponsored work visas.</p>

<h3>What the employer pays</h3>
<p>Article 40 of the Saudi Labour Law puts recruitment fees, the iqama and work permit fees and their renewals, and the return ticket on the employer. Contracts are registered on Qiwa.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>Physical fitness and a medical test</li>
    <li>Site experience is preferred but not always required</li>
    <li>Basic English or Arabic for safety instructions</li>
</ul>

<p><strong>Pay:</strong> Saudi Arabia sets no statutory minimum wage for expatriate workers. Get the basic wage, food, housing, transport and overtime stated separately in the contract.</p>

<p><strong>No genuine employer asks a worker to buy a visa.</strong> Visa and labour rules are set by the Saudi authorities, not by JobGader; confirm them on hrsd.gov.sa before paying anyone or booking travel.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>A labourer or helper job is how most workers first get to a Gulf building site. It is also the level where workers are most often overcharged, underpaid or sold a visa that does not exist. This guide sets the UAE and Saudi Arabia side by side for the entry-level worker: who pays to bring you, how your wage is protected, the hours you may not work in summer, and how to move from helper to a skilled trade, which is where the pay improves.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/construction-labourer-jobs-dubai-saudi-concrete.jpg"
         alt="Construction labourers in hard hats and high-visibility vests pouring and levelling concrete from a crane bucket on a high-rise site, with the Dubai skyline and tower cranes at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What a Construction Labourer Actually Does</h2>

<ul>
    <li><strong>General labourer</strong>: carrying and stacking materials, loading and unloading, cleaning the site, digging and backfilling.</li>
    <li><strong>Trade helper</strong>: working alongside a mason, steel fixer, shuttering carpenter, tiler or plasterer, mixing mortar, passing materials and learning the trade.</li>
    <li><strong>Concrete hand</strong>: helping with pours, vibrating and levelling, and curing.</li>
</ul>

<p>A helper job is worth more than a general labour job over time, because you are learning a trade on the employer's time. If you are offered both, ask which trade you would be helping.</p>

<h2>UAE and Saudi Arabia Side by Side</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">United Arab Emirates</th>
            <th style="padding:10px;text-align:left;">Saudi Arabia</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Minimum wage for expatriates</strong></td><td style="padding:10px;">None</td><td style="padding:10px;">None</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who pays recruitment and visa costs</strong></td><td style="padding:10px;">The employer (Article 6, Federal Decree-Law No. 33 of 2021)</td><td style="padding:10px;">The employer, plus iqama and work permit fees, renewals and the return ticket (Labour Law Article 40)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Where the contract lives</strong></td><td style="padding:10px;">MOHRE work permit and contract</td><td style="padding:10px;">Registered on Qiwa</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Summer midday break</strong></td><td style="padding:10px;">12:30pm to 3pm, 15 June to 15 September</td><td style="padding:10px;">Noon to 3pm in summer</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Change of job type</strong></td><td style="padding:10px;">Through a new or amended permit</td><td style="padding:10px;">You may not work in a profession different from your work permit</td></tr>
    </tbody>
</table>
</div>

<h2>What a Labourer Earns: Read the Contract, Not the Advert</h2>

<p><strong>Neither the UAE nor Saudi Arabia sets a minimum wage for foreign workers.</strong> The SAR 4,000 often quoted for Saudi Arabia is the wage at which a Saudi national counts towards Nitaqat quotas; it does not apply to you. The monthly figures on job boards and agents' posters are adverts, not law, and we do not republish them.</p>

<p>For labourers, the allowances often matter as much as the basic wage. Ask for each of these in figures:</p>

<ul>
    <li>Basic monthly wage</li>
    <li>Accommodation: company camp or housing allowance</li>
    <li>Food: provided, or a food allowance</li>
    <li>Transport to and from site</li>
    <li>Overtime rate and normal daily hours</li>
    <li>Annual leave ticket and medical insurance</li>
</ul>

<h2>Your Legal Protections</h2>

<h3>You should never pay for the job</h3>

<p>In the UAE, Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs, directly or indirectly. In Saudi Arabia, Article 40 of the Labour Law puts recruitment fees, the iqama and work permit fees and their renewals, and the return ticket on the employer. An agent who asks you for "visa money", or an employer who deducts it from your wage, is breaking these rules.</p>

<h3>Your wage is tracked</h3>

<p>In the UAE, the Wage Protection System runs under <strong>Ministerial Resolution No. 340 of 2026</strong>. Wages fall due on the first of each month. If they are late, new work permits for the company are suspended from day five, a labour dispute opens automatically on day sixteen, and a travel ban can be placed on the person in charge from day twenty-one. Saudi Arabia runs its own wage protection monitoring against the contract registered on Qiwa.</p>

<h3>You may not work under the midday sun in summer</h3>

<p>In the UAE, from 15 June to 15 September, work under direct sun in open areas stops from 12:30pm to 3pm, and employers are fined AED 5,000 per worker found working, up to AED 50,000. Saudi Arabia bans outdoor work under direct sun from noon to 3pm over the summer. A supervisor who keeps you pouring concrete at 1pm in July is breaking the law.</p>

<h3>Two exceptions to know in the UAE</h3>

<ul>
    <li><strong>Night overtime does not apply to shift workers.</strong> The 50 per cent rate for work between 10pm and 4am excludes workers on shifts.</li>
    <li><strong>Free zones sit outside the Labour Law.</strong> Free zone staff are generally governed by the zone's own rules.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/construction-labourer-jobs-dubai-saudi-tile-cutting.jpg"
         alt="A worker in a yellow hard hat and safety glasses cutting stone tiles with a wet saw while helpers carry and fix materials around stacked pallets, with the Dubai skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>From Helper to Skilled Trade</h2>

<p>The pay gap between a labourer and a skilled tradesman is where the money is. The usual route on Gulf sites:</p>

<ol>
    <li><strong>Labourer</strong>: learn the site, safety rules and the trades around you.</li>
    <li><strong>Trade helper</strong>: attach yourself to one trade, such as masonry, steel fixing, shuttering or tiling, and learn it properly.</li>
    <li><strong>Get it on paper</strong>: a trade certificate from your home country, or a recognised course, makes the experience count.</li>
    <li><strong>Change the profession officially</strong>: in Saudi Arabia you may not work in a profession different from the one on your permit, so the employer has to change it. For a new Saudi visa as a tradesman, you will usually need to pass the Professional Verification trade test in your home country first.</li>
    <li><strong>Skilled trade, then charge hand</strong>: from there, the route leads to foreman and site supervisor.</li>
</ol>

<p>Workers who go home after a first contract with a trade certificate and documented experience come back on a better visa. Keep photographs of your work and a reference letter from the site engineer.</p>

<h2>How to Apply Safely</h2>

<ol>
    <li><strong>Use your country's official overseas employment channel</strong> or an agency it licenses.</li>
    <li><strong>Never pay a recruitment, visa or medical fee</strong> to an agent. Your own passport and documents are yours to pay for; the work visa is not.</li>
    <li><strong>Get a written contract before you travel</strong> and check it matches the job title on your visa.</li>
    <li><strong>Do not accept a "free visa".</strong> Working for someone other than your sponsor, or in another profession, leaves you without legal protection.</li>
    <li><strong>Keep your passport.</strong> Your employer should not hold it.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What is the salary of a construction labourer in Dubai?</h3>
<p>There is no official figure. The UAE has no minimum wage for expatriate workers, and online figures come from job boards and agents. The contract is the only binding number, so get the basic wage and each allowance in writing.</p>

<h3>Is there a minimum wage for labourers in Saudi Arabia?</h3>
<p>Not for foreign workers. The SAR 4,000 level is used for counting Saudi nationals towards Nitaqat quotas, not as a floor for expatriates.</p>

<h3>Who pays for a labourer's visa to Saudi Arabia?</h3>
<p>The employer. Article 40 of the Saudi Labour Law puts recruitment fees, the iqama and work permit fees and renewals, and the return ticket on the employer.</p>

<h3>Can I do labour work without experience in the Gulf?</h3>
<p>Yes. Many general labourer and helper jobs do not require site experience, though employers prefer it and test physical fitness.</p>

<h3>Do labourers work in the afternoon heat?</h3>
<p>Not legally in summer. The UAE stops outdoor work from 12:30pm to 3pm between 15 June and 15 September; Saudi Arabia from noon to 3pm.</p>

<h3>What happens if my wages are late in the UAE?</h3>
<p>Under Ministerial Resolution No. 340 of 2026, the employer's new permits are suspended from day five, a labour dispute opens on day sixteen, and a travel ban is possible from day twenty-one.</p>

<h3>Can a helper become a mason or steel fixer?</h3>
<p>Yes, and it is the main way pay improves. In Saudi Arabia the profession on your permit has to be changed officially, and a new trade visa usually needs a Professional Verification test.</p>

<h3>Which is better for labourers, Dubai or Saudi Arabia?</h3>
<p>Neither publishes official pay for the job. Compare the full package: basic wage, food, accommodation, transport, overtime and ticket.</p>

<h2>People Also Search For</h2>

<h3>Labour jobs in Dubai</h3>
<p>On MOHRE work permits, with recruitment costs owed by the employer.</p>

<h3>Construction helper jobs in Saudi Arabia</h3>
<p>Contracts registered on Qiwa; no expatriate minimum wage.</p>

<h3>Labour visa for Saudi Arabia</h3>
<p>Paid for by the employer under Labour Law Article 40.</p>

<h3>Free visa Saudi Arabia</h3>
<p>Working outside your sponsor and profession leaves you unprotected.</p>

<h3>UAE Wage Protection System</h3>
<p>Ministerial Resolution No. 340 of 2026.</p>

<h3>UAE midday break 2026</h3>
<p>12:30pm to 3pm, from 15 June to 15 September.</p>

<h3>General labourer jobs Gulf</h3>
<p>Ask which trade you would be helping.</p>

<h3>Helper to mason career</h3>
<p>Get the trade on paper and the profession changed on your permit.</p>

<h2>More Job Guides</h2>

<p>The rest of the construction cluster:</p>

<ul>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the Saudi market from labourer to project manager.</li>
    <li><a href="/blog/tiler-plasterer-and-mason-jobs-overseas">Tiler, Plasterer and Mason Jobs Overseas</a> &mdash; the trades a helper most often moves into.</li>
    <li><a href="/blog/how-to-become-a-site-supervisor-or-foreman-abroad">How to Become a Site Supervisor or Foreman Abroad</a> &mdash; the top of the site ladder.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; the same Gulf rules for a building services trade.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; the Gulf rules for HVAC work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. UAE and Saudi labour and visa rules change; confirm the current position on u.ae, mohre.gov.ae and hrsd.gov.sa before accepting an offer or paying anyone.</p>
HTML;
    }
}
