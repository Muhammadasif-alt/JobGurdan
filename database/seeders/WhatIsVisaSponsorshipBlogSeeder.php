<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "What is Visa Sponsorship and How Does it Work"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The ten Apply Now buttons are dropped. Commercial portals (LinkedIn,
 *    SEEK, Bayt, GulfTalent) are named in prose where useful but not linked;
 *    only official or JobGader pages are linked.
 * 2. A Pakistan section is added (BEOE licensing and emigration checks).
 * 3. The brief's four or five FAQs become eight, plus eight People Also Search
 *    For entries and a More Job Guides block.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class WhatIsVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'what-is-visa-sponsorship-how-it-works';

    public function run(): void
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
                'title' => 'What is Visa Sponsorship and How Does it Work',
                'excerpt' => 'Visa sponsorship means an employer supports your immigration application through the process a country requires. It does not guarantee a visa, free relocation or permanent residence.',
                'content' => $content,
                'featured_image' => 'blogs/what-is-visa-sponsorship-how-it-works.jpg',
                'tags' => 'what is visa sponsorship, how does visa sponsorship work, employer visa sponsorship explained, sponsored work visa guide, certificate of sponsorship uk, h-1b petition employer, aewv accredited employer, genuine sponsored job offer',
                'meta_title' => 'What is Visa Sponsorship and How Does it Work',
                'meta_description' => 'What is visa sponsorship? How employer sponsorship works in the UK, US, New Zealand and the Netherlands, who pays, and how to spot a genuine offer.',
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
<p><strong>Visa sponsorship means an employer supports your immigration application through the process a country requires.</strong> The employer may issue sponsorship details, nominate a position or file a petition. You must still meet the route's requirements, and the government makes the decision. Sponsorship does not guarantee free relocation, entry or permanent residence. This guide covers employment sponsorship; family and other sponsorship categories follow different rules.</p>

<h2>What the Employer, Worker and Government Each Do</h2>

<table>
<thead><tr><th>Party</th><th>Typical responsibility</th></tr></thead>
<tbody>
<tr><td>Employer</td><td>An eligible offer and the required sponsorship steps</td></tr>
<tr><td>Worker</td><td>An accurate application and evidence of eligibility</td></tr>
<tr><td>Government</td><td>Assessment and the immigration decision</td></tr>
<tr><td>Professional regulator</td><td>Authorisation to practise where required</td></tr>
</tbody>
</table>

<p>Immigration permission and professional licensing are separate checks. An employer's support does not let a nurse or electrician practise locally on its own.</p>

<h2>How It Usually Works</h2>

<ol>
<li>Find a vacancy that recruits from overseas.</li>
<li>Check employer, role and personal eligibility.</li>
<li>Agree written employment terms.</li>
<li>The employer completes its approvals or filings.</li>
<li>You receive the details needed for your application.</li>
<li>You submit your evidence through the official process.</li>
<li>Complete checks and wait for a decision.</li>
<li>Follow the entry and work conditions if approved.</li>
</ol>

<p>The order differs by country. An application receipt does not authorise work.</p>

<h2>Country Examples</h2>

<ul>
<li><strong>United Kingdom.</strong> The <a href="https://www.gov.uk/skilled-worker-visa" rel="noopener">Skilled Worker route</a> needs an eligible job with a Home Office-approved employer and a Certificate of Sponsorship reference. A licensed sponsor does not sponsor every vacancy, so confirm the particular position is covered. You can check licensed employers in the <a href="https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers" rel="noopener">register of licensed sponsors</a>.</li>
<li><strong>United States.</strong> Many temporary worker categories need the employer to file a petition, with labour certification for some routes. After approval you follow the visa process, and an issued visa still does not guarantee admission at the border. See the <a href="https://travel.state.gov/content/travel/en/us-visas/employment/temporary-worker-visas.html" rel="noopener">US temporary worker visa page</a>.</li>
<li><strong>New Zealand.</strong> The <a href="https://www.immigration.govt.nz/visas/accredited-employer-work-visa/" rel="noopener">Accredited Employer Work Visa</a> involves an accredited employer and an approved job. Your conditions may link you to a specific employer, role or location.</li>
<li><strong>Netherlands.</strong> The <a href="https://ind.nl/en/residence-permits/work/highly-skilled-migrant" rel="noopener">highly skilled migrant route</a> involves an IND-recognised sponsor and its own salary requirements. Dutch recognition is not interchangeable with a UK licence or an Australian nomination.</li>
</ul>

<h2>Does the Employer Pay Everything?</h2>

<p>No universal rule applies. Costs depend on the country, the route and the lawful contract. New Zealand states that employers or agents cannot charge you fees for a job or make you pay any of their recruitment costs. Ask for a written breakdown of application, travel and accommodation costs and check it against the official rules instead of relying on the phrase "fully sponsored".</p>

<h2>How to Find a Genuine Sponsored Job</h2>

<ul>
<li>Verify the company, the vacancy and the contact through independent sources.</li>
<li>Ask which route it supports and whether it recruits from your country.</li>
<li>Keep the written offer, job description and correspondence.</li>
<li>Walk away from guaranteed visas, purchased job offers and unexplained payment demands.</li>
</ul>

<p>See our <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">fake offer checklist</a> before you pay anyone.</p>

<h2>After Approval</h2>

<p>Read your conditions, restrictions and expiry date. Changing employer or losing a job affects what you can do next, and the rules differ by route. Sponsorship does not give unrestricted work rights or automatic permanent residence, and family members need their own eligibility checks. Never agree to false duties, salary figures or qualifications to make a role look eligible, and keep copies of everything you submit.</p>

<h2>What Should Pakistani Applicants Check?</h2>

<ul>
    <li><strong>That any Pakistani recruiter is licensed.</strong> Agents placing workers abroad must be licensed Overseas Employment Promoters, which you can check on the <a href="https://beoe.gov.pk" rel="noopener">Bureau of Emigration and Overseas Employment</a> website.</li>
    <li><strong>The emigration rules for your own arrangement.</strong> Taking a job without an agent does not remove Pakistan's own emigration requirements, so confirm them with the Bureau before you travel.</li>
    <li><strong>Whether a home registration is needed.</strong> Regulated fields such as nursing and engineering have their own councils and destination regulators; the two are separate checks.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is visa sponsorship in simple words?</h3>
<p>An employer supports your application through the steps a country requires. The government still decides.</p>

<h3>Does a sponsored offer guarantee a visa?</h3>
<p>No. The government assesses eligibility and evidence.</p>

<h3>Can every company sponsor foreign workers?</h3>
<p>No. The employer needs the approval the route requires, such as a licence or accreditation.</p>

<h3>Can I sponsor myself through an employer route?</h3>
<p>No. Employer-led routes need the employer. Other categories have separate rules.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/countries-visa-sponsorship-skilled-workers-2026">Countries That Offer Visa Sponsorship for Skilled Workers</a>: compare routes</li>
    <li><a href="/blog/what-is-work-permit-how-it-works">What is a Work Permit and How Does it Work</a>: permit versus visa</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer (2026)</a>: verify before you pay</li>
    <li><a href="/blog/find-job-abroad-without-recruiter">How to Find a Job Abroad Without a Recruiter</a>: search yourself</li>
</ul>

HTML;
    }
}
