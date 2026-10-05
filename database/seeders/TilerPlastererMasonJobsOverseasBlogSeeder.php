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
 * "Tiler, Plasterer and Mason Jobs Overseas" — the finishing-trades page of
 * the construction cluster: the Gulf rules, the UK sponsorship position by
 * occupation code, and how the skills are tested before a visa.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Every pay figure and vacancy count came from recruiters and job boards
 *    ("180 openings at AED 1,600", "SAR 1,100 plus 200 food", sponsored-job
 *    pay of 35,000 and 37,002 pounds). Recruiter and listing pay is not
 *    republished, and unknown recruiters are treated as a scam risk.
 *
 * 2. "Plasterers and tilers are eligible medium-skilled occupations" for the
 *    UK. Since 22 July 2025 medium-skilled jobs qualify only if they are on
 *    the Temporary Shortage List: 5322 floorers and wall tilers is listed,
 *    5321 plasterers and 5312 bricklayers are not.
 *
 * 3. Australian and Canadian wage figures that could not be confirmed
 *    against Jobs and Skills Australia or Job Bank were dropped.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class TilerPlastererMasonJobsOverseasBlogSeeder extends Seeder
{
    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

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
            ['slug' => 'visa-sponsorship'],
            [
                'name' => 'Visa Sponsorship',
                'description' => 'Guides on work visas, sponsorship routes, and how foreign workers can apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Tiler, Plasterer and Mason Jobs Overseas';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Where tilers, plasterers and masons can work abroad: the Gulf rules on recruitment costs and trade tests, which UK occupation codes can still be sponsored, and how to spot a fake recruiter before you pay anyone.',
                'content' => $content,
                'featured_image' => 'blogs/tiler-plasterer-mason-jobs-overseas.jpg',
                'tags' => 'tiler jobs abroad, plasterer jobs overseas, mason jobs gulf, tiler jobs dubai, uk temporary shortage list tiler, saudi professional verification, construction trades visa, finishing trades jobs',
                'meta_title' => 'Tiler, Plasterer and Mason Jobs Overseas: Visas and Rules',
                'meta_description' => 'Tiler, plasterer and mason jobs abroad: Gulf recruitment rules, the Saudi trade test, which UK codes can be sponsored, and how to avoid fake recruiters.',
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
            ['name' => 'UAE Building Contractors & Fit-Out Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uae-tiler-mason-aggregated']
        );

        $uaeLocation = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );

        Job::updateOrCreate(
            [
                'position' => 'Mason and Tiler — UAE Building Contractors and Fit-Out Companies',
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
                'meta_description' => 'Mason, blockwork and tiling roles with UAE building contractors and fit-out companies, recruited on MOHRE work permits.',
                'seo_keywords' => 'mason jobs dubai, tiler jobs uae, blockwork mason uae, fit-out tiler dubai, mohre work permit',
            ]
        );

        $ukAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Construction Sponsors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-floorer-tiler-aggregated']
        );

        $ukLocation = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        Job::updateOrCreate(
            [
                'position' => 'Floorer and Wall Tiler — UK Licensed Sponsors (SOC 5322)',
                'advertiser_id' => $ukAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $ukLocation->id,
                'description' => $this->ukJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::UK_APPLY_URL,
                'meta_description' => 'Floorer and wall tiler roles with UK licensed sponsors. SOC 5322 is on the Skilled Worker Temporary Shortage List.',
                'seo_keywords' => 'tiler jobs uk visa sponsorship, soc 5322, floorer jobs uk, skilled worker tiler, temporary shortage list',
            ]
        );
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Building contractors and interior fit-out companies across the UAE recruit masons, blockwork masons and tilers from overseas on MOHRE work permits.</p>

<h3>What the work involves</h3>
<ul>
    <li>Blockwork and brickwork to drawings and levels</li>
    <li>Floor and wall tiling, including large-format porcelain and stone</li>
    <li>Setting out, cutting and finishing to the site engineer's tolerances</li>
</ul>

<h3>What the law says</h3>
<ul>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021, the employer may not charge the worker recruitment and employment costs, directly or indirectly</li>
    <li>The UAE sets <strong>no statutory minimum wage</strong> for private-sector expatriate workers. The contract is the only binding figure</li>
    <li>Outdoor work under direct sun stops from 12:30pm to 3pm between 15 June and 15 September</li>
</ul>

<p><strong>Never pay anyone for a UAE job.</strong> Visa, wage and permit rules are set by MOHRE, not by JobGader; confirm them on u.ae before accepting an offer.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK flooring and tiling contractors that hold a Home Office sponsor licence can sponsor floorers and wall tilers under SOC code 5322.</p>

<h3>Why this code and not the others</h3>
<p>Since 22 July 2025, medium-skilled occupations can be sponsored only if they appear on the Temporary Shortage List. <strong>5322 floorers and wall tilers is on it.</strong> 5321 plasterers and 5312 bricklayers and masons are not, so a sponsored "plasterer" or "bricklayer" offer needs careful checking.</p>

<h3>Requirements</h3>
<ul>
    <li>A job offer from a licensed sponsor, with a certificate of sponsorship for SOC 5322</li>
    <li>English at the level the Home Office currently requires for Skilled Worker visas</li>
    <li>Pay at or above the going rate published for the occupation</li>
</ul>

<p>Visa rules are set by the Home Office, not by JobGader. Check the current Temporary Shortage List and sponsor register on gov.uk before you apply or pay anyone.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Tiling, plastering and masonry are among the trades most often advertised to overseas workers, and among the most often used as bait by fake recruiters. Most guides to these jobs repeat a recruiter's headline: a hundred openings, a monthly figure, "visa free". This guide covers what actually decides whether you can take one of these jobs and keep your pay: who pays to recruit you, the trade test Saudi Arabia sets before it issues a visa, and which of the three trades the UK can still sponsor at all.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/tiler-plasterer-mason-jobs-overseas-trio.jpg"
         alt="Three tradesmen in hard hats on a Gulf building site: one plastering a wall with a trowel, one laying floor tiles with spacers, one building a block wall, with the Dubai skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Three Trades, Three Different Jobs</h2>

<ul>
    <li><strong>Tilers</strong> set floor and wall tiles in ceramic, porcelain and stone: preparing the surface, setting out, cutting, fixing, grouting and sealing. Fit-out work in hotels, malls and apartments uses large-format porcelain, so experience with levelling systems and wet saws is valued.</li>
    <li><strong>Plasterers</strong> apply internal plaster, external render and, on many Gulf sites, gypsum and drywall finishes. Some employers advertise "plasterer" when they mean drywall installer; ask which.</li>
    <li><strong>Masons</strong> build in block, brick and stone. In the Gulf most of the work is concrete blockwork; stone masonry is a smaller, more specialist market.</li>
</ul>

<p>Employers list the three together because the same contractors hire them, but your trade test, visa code and pay are set for one trade at a time. Apply as the trade you can prove.</p>

<h2>Where the Jobs Are</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Destination</th>
            <th style="padding:10px;text-align:left;">Route</th>
            <th style="padding:10px;text-align:left;">The catch</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UAE</strong></td><td style="padding:10px;">Employer-sponsored MOHRE work permit</td><td style="padding:10px;">No minimum wage for expatriates; the contract is the only binding figure</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Saudi Arabia</strong></td><td style="padding:10px;">Employer-sponsored work visa</td><td style="padding:10px;">Professional Verification trade test in your home country before the visa</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>United Kingdom</strong></td><td style="padding:10px;">Skilled Worker visa from a licensed sponsor</td><td style="padding:10px;">Only floorers and wall tilers (5322) are on the Temporary Shortage List</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Australia</strong></td><td style="padding:10px;">Employer sponsorship or skilled migration</td><td style="padding:10px;">A trades skills assessment comes first</td></tr>
    </tbody>
</table>
</div>

<h2>The Gulf: What the Law Says About Your Job</h2>

<h3>UAE</h3>

<ul>
    <li><strong>The employer pays to recruit you.</strong> Article 6 of Federal Decree-Law No. 33 of 2021 makes a MOHRE work permit compulsory and bars the employer from charging the worker recruitment and employment costs, directly or indirectly.</li>
    <li><strong>No minimum wage for expatriates.</strong> The UAE sets no statutory minimum wage for private-sector expatriate workers, so the figure in your contract is the only one that binds.</li>
    <li><strong>Wages are protected.</strong> The Wage Protection System runs under Ministerial Resolution No. 340 of 2026: wages are due on the first of each month, new permits are suspended from day five of a delay, a labour dispute opens on day sixteen and a travel ban is possible from day twenty-one.</li>
    <li><strong>The midday break.</strong> From 15 June to 15 September, outdoor work under direct sun stops from 12:30pm to 3pm. Employers face a fine of AED 5,000 per worker, up to AED 50,000.</li>
</ul>

<h3>Saudi Arabia</h3>

<ul>
    <li><strong>The trade test comes before the visa.</strong> The Professional Verification programme requires workers in covered professions, including the construction trades, to pass a theoretical and practical assessment in their own country before the work visa is issued.</li>
    <li><strong>The employer bears the costs.</strong> Article 40 of the Saudi Labour Law puts recruitment fees, the iqama and work permit fees and their renewals, and the return ticket on the employer.</li>
    <li><strong>You work in the trade on your permit.</strong> Working in a profession different from the one on your work permit is not allowed. A tiler visa is for tiling.</li>
    <li><strong>Contracts are registered on Qiwa</strong>, and outdoor work under direct sun is banned from noon to 3pm in summer.</li>
    <li><strong>No expatriate minimum wage.</strong> The SAR 4,000 figure often quoted is the level at which a Saudi national counts towards Nitaqat quotas. It is not a floor for foreign workers.</li>
</ul>

<h2>The United Kingdom: Check the Occupation Code</h2>

<p>Since 22 July 2025, Skilled Worker sponsorship for medium-skilled jobs (RQF levels 3 to 5) is only possible if the occupation is on the <strong>Temporary Shortage List</strong>. The three trades come out differently:</p>

<ul>
    <li><strong>5322 Floorers and wall tilers</strong>: on the Temporary Shortage List. The list sets a standard going rate of &pound;33,400 a year (&pound;17.13 an hour).</li>
    <li><strong>5321 Plasterers</strong>: not on the list.</li>
    <li><strong>5312 Bricklayers and masons</strong>: not on the list.</li>
</ul>

<p>Older guides still describe plasterers and masons as "eligible medium-skilled occupations". That stopped being true in July 2025. If a UK offer for a plasterer or bricklayer comes with a promised visa, ask the sponsor which occupation code the certificate of sponsorship will use, and check it against the current list on gov.uk. The list is temporary and is reviewed, so check it on the day you apply. A sponsor licence alone says nothing; the occupation code decides.</p>

<p>Skilled Worker applicants also need English at B2 level. Jobs at RQF 6 and above use a general salary threshold of &pound;41,700.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/tiler-plasterer-mason-jobs-overseas-blockwork.jpg"
         alt="A mason in a yellow hard hat spreading mortar on a concrete block wall while a tiler in a white hard hat lays floor tiles behind him, with stacked tiles, a spirit level and a Gulf skyline at sunset"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Australia: Skills Assessment First</h2>

<p>Australia lists wall and floor tilers, solid plasterers, fibrous plasterers, and bricklayers and stonemasons as separate occupations. Overseas tradespeople generally need a skills assessment through Trades Recognition Australia, or an assessing body it approves, before a skilled visa. Wall and floor tiling in Australia also covers waterproofing in wet areas, which is regulated work in most states, so expect to be tested on it.</p>

<h2>Fake Recruiters Target These Trades</h2>

<p>Tiling and masonry adverts are a favourite of visa scams because they promise large numbers of openings to workers who are less likely to check. Treat any recruiter you cannot verify as a risk, and walk away if you see any of these:</p>

<ul>
    <li>A "processing", "visa" or "medical" fee before you have a signed contract. In the UAE, charging recruitment costs to the worker is unlawful; in Saudi Arabia, the employer bears them.</li>
    <li>A "free visa" that lets you work for anyone. In Saudi Arabia you may only work in the profession on your permit, for your sponsor.</li>
    <li>A UK "sponsored plasterer" job. Plasterers are not on the Temporary Shortage List.</li>
    <li>An offer by WhatsApp with no company name, no trade licence number and no contract.</li>
    <li>Anyone asking for an OTP or your passport original before a contract.</li>
</ul>

<p>Use your own country's official overseas employment channel, and check that the recruiting agency holds a licence there.</p>

<h2>What Employers Ask For</h2>

<ul>
    <li>A trade certificate or vocational diploma in tiling, plastering or masonry, or several years of documented experience</li>
    <li>A portfolio: photographs of finished floors, walls and blockwork, with dates and sites</li>
    <li>The ability to read drawings and work to levels and tolerances</li>
    <li>For Saudi Arabia, a Professional Verification certificate</li>
    <li>For the UK, an occupation on the Temporary Shortage List and B2 English</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do tilers earn in Dubai?</h3>
<p>There is no official figure, and the UAE has no minimum wage for expatriate workers. The numbers circulating online come from recruiters and job boards. The contract is the only binding figure, so get the basic wage, housing, transport and overtime written separately.</p>

<h3>Can a plasterer get a UK Skilled Worker visa?</h3>
<p>Not at present under SOC 5321. Since 22 July 2025 medium-skilled occupations need to be on the Temporary Shortage List, and plasterers are not on it. Check the current list on gov.uk before relying on any offer.</p>

<h3>Can a tiler get a UK Skilled Worker visa?</h3>
<p>Yes, if a licensed sponsor offers the job under SOC 5322 floorers and wall tilers, which is on the Temporary Shortage List, and the pay meets the going rate.</p>

<h3>Do I need a trade test for Saudi Arabia?</h3>
<p>Yes. The Professional Verification programme requires a theoretical and practical assessment in your home country before the Saudi work visa is issued.</p>

<h3>Who pays the visa costs for a UAE mason job?</h3>
<p>The employer. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs.</p>

<h3>Can I switch from mason to tiler after arriving in Saudi Arabia?</h3>
<p>Not on the same permit. Working in a profession different from the one on your work permit is not allowed; the employer has to change the profession officially.</p>

<h3>Is stone masonry in demand abroad?</h3>
<p>It is a smaller market than blockwork. Heritage, villa and landscaping projects hire stone masons, but most Gulf mason jobs are concrete blockwork.</p>

<h3>How do I know a recruiter is genuine?</h3>
<p>A genuine recruiter names the employer, gives you a written contract before travel and never charges you a fee. Check the agency's licence with your own country's overseas employment authority.</p>

<h2>People Also Search For</h2>

<h3>Tiler jobs in Dubai</h3>
<p>Fit-out and building contractors recruit on MOHRE work permits.</p>

<h3>Mason jobs in Saudi Arabia</h3>
<p>Professional Verification test at home before the visa.</p>

<h3>Plasterer jobs in UK with visa sponsorship</h3>
<p>SOC 5321 is not on the Temporary Shortage List.</p>

<h3>Floor tiler jobs UK sponsorship</h3>
<p>SOC 5322 is on the list, with a &pound;33,400 standard going rate.</p>

<h3>Gypsum plasterer jobs Gulf</h3>
<p>Ask whether the job is plaster, render or drywall.</p>

<h3>Construction trades visa scams</h3>
<p>Never pay a fee before a signed contract.</p>

<h3>Tile fixer jobs abroad</h3>
<p>A portfolio of finished work matters as much as a certificate.</p>

<h3>Block mason jobs UAE</h3>
<p>No expatriate minimum wage; the contract is the binding figure.</p>

<h2>More Job Guides</h2>

<p>The rest of the construction trades cluster:</p>

<ul>
    <li><a href="/blog/construction-labourer-jobs-in-dubai-and-saudi-arabia">Construction Labourer Jobs in Dubai and Saudi Arabia</a> &mdash; the helper level, and how to move up to a trade.</li>
    <li><a href="/blog/how-to-become-a-site-supervisor-or-foreman-abroad">How to Become a Site Supervisor or Foreman Abroad</a> &mdash; the next step for an experienced tradesman.</li>
    <li><a href="/blog/scaffolder-roofer-and-crane-operator-jobs-abroad">Scaffolder, Roofer and Crane Operator Jobs Abroad</a> &mdash; three more site trades and their shortage-list status.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; the wider Saudi construction market.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; the same Gulf rules for another trade.</li>
    <li><a href="/blog/ac-technician-jobs-in-dubai-and-saudi-arabia">AC Technician Jobs in Dubai and Saudi Arabia</a> &mdash; the Gulf rules for HVAC work.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Visa and labour rules change; confirm the current position on gov.uk, u.ae, mohre.gov.ae and hrsd.gov.sa before accepting an offer or paying anyone.</p>
HTML;
    }
}
