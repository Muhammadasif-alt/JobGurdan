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

/**
 * "Security Guard Jobs in the UAE, UK and Canada" — blog-291.
 *
 * The draft was rewritten. What it carried could not be published as sent:
 *
 * 1. Indeed "Apply Now" buttons for the UAE, UK and Canada, and two
 *    [ADD REAL LINK] / [ADD REAL SOURCE] placeholders. The guide links only to
 *    our own pages; the job listings carry the official links.
 *
 * 2. "One training-provider guide lists around AED 1,000", a SIRA bleep test,
 *    the claim that every Ontario application has needed a criminal record
 *    check since 18 February 2024, and "you must be in Canada to start the
 *    course". All came from provider marketing and could not be confirmed.
 *
 * 3. "SIRA certificate in Dubai" was kept, but the guide says in plain words
 *    that SIRA licenses Dubai only, which matches the existing UAE guide.
 *
 * Confirmed: the SIA needs applicants to be 18 or over, hold a licence-linked
 * qualification and, in most cases, have the right to work in the UK; Ontario
 * needs applicants to be 18 or over and legally entitled to work in Canada,
 * with a 40-hour approved course, an exam and a criminal record and judicial
 * matters check.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class SecurityGuardJobsUaeUkCanadaBlogSeeder extends Seeder
{
    public const SLUG = 'security-guard-jobs-in-the-uae-uk-and-canada';

    private const UAE_APPLY_URL = 'https://u.ae/en/information-and-services/jobs';

    private const UK_APPLY_URL = 'https://www.gov.uk/guidance/apply-for-an-sia-licence';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch/jobsearch?searchstring=security+guard';

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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Security Guard Jobs in the UAE, UK and Canada',
                'excerpt' => 'You need the local licence and permission to work first: SIRA in Dubai, an SIA licence in the UK and a provincial licence in Canada. What each one asks for, and why the UK and Canada licences cannot be got before you arrive.',
                'content' => $content,
                'featured_image' => 'blogs/security-guard-jobs-uae-uk-canada.jpg',
                'tags' => 'security guard jobs dubai, sira security guard certificate, sia licence security guard, security guard licence ontario, security guard jobs canada, security guard jobs uk, security guard job scams, licensed security work abroad',
                'meta_title' => 'Security Guard Jobs in UAE, UK and Canada: Licences',
                'meta_description' => 'Security guard jobs in Dubai, the UK and Canada: the SIRA, SIA and Ontario licences, who can apply from abroad, and how to avoid fake offers.',
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
            ['slug' => 'security'],
            ['name' => 'Security']
        );

        $listings = [
            [
                'advertiser' => ['UAE Security Employers (Aggregated)', 'uae-security-guard-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Security Guard — UAE Security Companies (Licence Required)',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Shift-based, including nights and weekends',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Licensed security guard roles with UAE employers. In Dubai the guard needs a SIRA certificate; check which regulator covers the site.',
                'keywords' => 'security guard jobs dubai, sira security guard, security officer jobs uae, watchman jobs uae',
            ],
            [
                'advertiser' => ['UK Security Employers (Aggregated)', 'uk-security-guard-aggregated'],
                'location' => ['United Kingdom', 'United Kingdom'],
                'position' => 'Security Guard — UK Security Employers (SIA Licence Required)',
                'apply' => self::UK_APPLY_URL,
                'hours' => 'Shift-based, including nights and weekends',
                'description' => $this->ukJobDescription(),
                'meta' => 'Security guard roles in the UK need an SIA licence, and in most cases the right to work in the UK before you can apply for one.',
                'keywords' => 'security guard jobs uk, sia licence, security officer jobs uk, door supervisor sia',
            ],
            [
                'advertiser' => ['Canadian Security Employers (Aggregated)', 'canada-security-guard-aggregated'],
                'location' => ['Canada', 'Canada'],
                'position' => 'Security Guard — Canadian Security Employers (Provincial Licence Required)',
                'apply' => self::CANADA_APPLY_URL,
                'hours' => 'Shift-based, including nights and weekends',
                'description' => $this->canadaJobDescription(),
                'meta' => 'Security guard roles in Canada need a provincial licence. In Ontario you must be 18 or over and legally entitled to work in Canada.',
                'keywords' => 'security guard jobs canada, ontario security guard licence, security guard job bank, security officer jobs canada',
            ],
        ];

        foreach ($listings as $listing) {
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['hours'],
                    'language' => 'English',
                    // Pay depends on the employer, the site and the contract;
                    // this site does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Security companies and in-house security teams across the UAE recruit guards, security officers and watchmen for malls, residential towers, offices and construction sites.</p>

<h3>Requirements</h3>
<ul>
    <li>A security licence for the emirate the site is in. SIRA covers Dubai only; other emirates have their own regulator</li>
    <li>A work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>Ask in writing who arranges and pays for the licence training before you accept</li>
</ul>

<p><strong>Note:</strong> licensing and work permit rules are set by the UAE authorities, not by JobGader. Confirm them before accepting an offer.</p>
JOBHTML;
    }

    private function ukJobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK security companies recruit licensed guards for retail, events, offices and public venues. This listing links to the official SIA licence application, because no guard can work in a licensable role without one.</p>

<h3>Requirements</h3>
<ul>
    <li>An SIA licence: you must be 18 or over, hold an SIA-recognised licence-linked qualification and pass identity and criminal record checks</li>
    <li>In most cases the right to work in the UK. The SIA checks it against Home Office records, so you generally cannot get licensed first and then move</li>
    <li>Security guard roles are not a route to a Skilled Worker visa, so check any sponsorship claim on GOV.UK</li>
</ul>

<p><strong>Note:</strong> licence and visa rules are set by the SIA and the Home Office, not by JobGader.</p>
JOBHTML;
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian security firms hire licensed guards for buildings, events, retail and patrol work. Licensing is run by each province.</p>

<h3>Requirements</h3>
<ul>
    <li>In Ontario: 18 or over and legally entitled to work in Canada, an approved 40-hour training course, the licensing exam and a criminal record and judicial matters check</li>
    <li>A work permit makes you eligible to be licensed; a visitor visa does not allow you to work</li>
    <li>Other provinces have their own rules, so check the one you are moving to</li>
</ul>

<p><strong>Note:</strong> licensing rules are set by each province, not by JobGader. Confirm fees and current steps with the provincial authority.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You need the local security licence and the legal right to work first. That means a SIRA certificate in Dubai, an SIA licence in the UK, and a provincial licence in Canada, such as Ontario's. In the UK and Canada you must already be allowed to work in the country before you can be licensed, so no recruiter can sell you a licence from abroad. Never pay anyone for a "guaranteed" security job.</p>

<h2>The Three Licences at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Country</th>
            <th style="padding:10px;text-align:left;">Licence</th>
            <th style="padding:10px;text-align:left;">Can you get it before you arrive?</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Dubai</strong></td><td style="padding:10px;">SIRA certificate</td><td style="padding:10px;">Usually arranged with the employer once you hold a work permit</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>UK</strong></td><td style="padding:10px;">SIA licence</td><td style="padding:10px;">No; in most cases you need the right to work first</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Canada (Ontario)</strong></td><td style="padding:10px;">Provincial security guard licence</td><td style="padding:10px;">No; you must be legally entitled to work in Canada</td></tr>
    </tbody>
</table>
</div>

<h2>How Do I Get Security Guard Jobs in Dubai?</h2>

<p>Search for the exact titles employers use: "Security Guard", "Security Officer" and "Watchman". Read each advert for its licence requirement. Many Dubai listings state a valid SIRA security guard certificate as mandatory, and some ask for UAE experience.</p>

<p>SIRA is the Security Industry Regulatory Agency, set up under Dubai Police. It licenses security work in the Emirate of Dubai, and it does not license the rest of the UAE. Other emirates have their own regulator, so ask which one covers the site before you pay for a course. The detail is in <a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a>.</p>

<p>You cannot work on a visit or tourist visa. The employer arranges the work permit, and under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly.</p>

<h2>Do I Need a SIRA Licence Before I Apply in Dubai?</h2>

<p>For most listings, yes, or you must complete it soon after you are hired. Guards complete SIRA-approved training before they are certified. Some employers enrol new hires in approved training, while others only hire guards who already hold the certificate. <strong>Ask in writing who arranges and pays for the training before you accept.</strong> Course prices differ by provider, so ask for the figure in writing.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/security-guard-jobs-uae-uk-canada-sites.jpg"
         alt="A security guard with a radio in front of the Dubai skyline, beside smaller scenes of guards at a mall entrance, a CCTV control room, an airport and a park with a dog, with the UAE, UK and Canada flags and a passport"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Is an SIA Licence, and Can I Get One From Abroad?</h2>

<p>An SIA licence is the legal permit to work as a security guard in the UK. The Security Industry Authority says you must be 18 or over, pass identity and criminal record checks, and hold an SIA-recognised licence-linked qualification before you can apply for a security guarding licence.</p>

<p>The catch for overseas applicants is that in most cases you must have the right to work in the UK. The SIA checks it against Home Office records, so you generally cannot get licensed first and then move. Security guard work is also not a route to a Skilled Worker visa, so treat any advert promising sponsorship with caution and check GOV.UK. The same warning applies to <a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">cleaner and janitor jobs</a>.</p>

<p>If you already have the right to work in the UK, the steps are:</p>

<ol>
    <li><strong>Complete an SIA-approved licence-linked training course.</strong></li>
    <li><strong>Apply for your licence</strong> through the SIA.</li>
    <li><strong>Apply for jobs</strong> and show your licence and right-to-work documents.</li>
</ol>

<p>Note that you do not usually need an SIA licence if you are employed directly by the company that uses your services, which the SIA calls working in-house.</p>

<h2>Can a Newcomer Get a Security Guard Licence in Canada?</h2>

<p>Yes, if you are legally entitled to work in Canada. Licensing is handled by each province. In Ontario you must be 18 or older and legally entitled to work in Canada. Canadian citizenship or permanent residence is not required; a valid work permit can make you eligible. A visitor visa does not allow you to work.</p>

<p>The Ontario process:</p>

<ol>
    <li><strong>Complete the 40-hour security guard training course</strong> with a provider approved by the Ministry of the Solicitor General.</li>
    <li><strong>Pass the licensing exam.</strong></li>
    <li><strong>Get a criminal record and judicial matters check</strong> issued recently enough to be accepted with your application.</li>
    <li><strong>Apply for your licence</strong> and pay the fee. Check the current fee with the province before you pay.</li>
</ol>

<p>Other provinces have their own rules, so check the one you are moving to. For the wider picture on working in Canada, see <a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/security" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Security Jobs on JobGader →
    </a>
</div>

<h2>How Do I Avoid Security Job Scams?</h2>

<p><strong>Real employers do not charge you for a job offer or a visa.</strong> Watch for these red flags:</p>

<ul>
    <li><strong>A fee for a "guaranteed" security job or work visa.</strong></li>
    <li><strong>A recruiter who says you can skip the licence.</strong> Working without the required licence is illegal in Dubai, the UK and Ontario.</li>
    <li><strong>No written contract</strong> before you travel.</li>
    <li><strong>Pressure to pay quickly</strong> for training with a specific, unnamed provider.</li>
</ul>

<p>Check the licensing body's official site before you pay for any course: SIRA in Dubai, the SIA in the UK, and your province's government in Canada.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I work as a security guard without a licence?</h3>
<p>No. Dubai requires SIRA approval, Ontario requires a valid licence, and the UK requires an SIA licence for security guarding.</p>

<h3>Do I need experience to become a security guard?</h3>
<p>Not always, but it helps. Some Dubai employers ask for UAE experience, while UK and Canadian licences depend on completing the required training.</p>

<h3>Which country is best for a first security job abroad?</h3>
<p>It depends on your permission to work. In the UAE, employers hire guards directly and often state the licence requirement upfront. In the UK and Canada you must already be allowed to work before you can be licensed.</p>

<h3>Does a SIRA certificate work in the whole UAE?</h3>
<p>No. SIRA licenses Dubai. Other emirates have their own regulator, so ask which one covers the site.</p>

<h3>Can I get an SIA licence from outside the UK?</h3>
<p>In most cases no, because you need the right to work in the UK, which the SIA checks against Home Office records.</p>

<h3>Can a work permit holder get an Ontario security licence?</h3>
<p>Yes. You must be 18 or over and legally entitled to work in Canada; a work permit counts, and citizenship or permanent residence is not required.</p>

<h3>Is security guard work a route to a UK Skilled Worker visa?</h3>
<p>No. Check any sponsorship claim on GOV.UK before you reply to it.</p>

<h3>Who pays for security guard licence training?</h3>
<p>It varies. Ask in writing before you accept, and in the UAE remember the employer may not charge you recruitment costs.</p>

<h2>People Also Search For</h2>

<h3>Security guard jobs in Dubai</h3>
<p>Search for security guard, officer and watchman, and check the licence requirement in each advert.</p>

<h3>SIRA security guard certificate</h3>
<p>The Dubai licence; it does not cover the other emirates.</p>

<h3>SIA licence security guard</h3>
<p>The UK licence; you need 18 plus a licence-linked qualification and, in most cases, the right to work.</p>

<h3>Security guard licence Ontario</h3>
<p>A 40-hour approved course, an exam and a criminal record check.</p>

<h3>Security guard jobs in Canada</h3>
<p>Search Job Bank, once you are legally entitled to work.</p>

<h3>Security guard jobs in the UK</h3>
<p>Open to people who already have the right to work and an SIA licence.</p>

<h3>Security guard job scams</h3>
<p>Real employers charge no fee for a job or a visa.</p>

<h3>Licensed security work abroad</h3>
<p>Every destination licenses guards, and none lets you skip the licence.</p>

<h2>More Job Guides</h2>

<p>Related security and visa guides:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; why SIRA covers Dubai only.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; the Saudi route.</li>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the wider Canadian picture.</li>
    <li><a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">Cleaner and Janitor Jobs With Visa Sponsorship</a> &mdash; another role where sponsorship claims need checking.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. UK rules are from the Security Industry Authority and GOV.UK; Ontario rules from the Ontario government's private security licensing. Licence rules and fees change, so confirm them with the official authority before paying for a course.</p>
HTML;
    }
}
