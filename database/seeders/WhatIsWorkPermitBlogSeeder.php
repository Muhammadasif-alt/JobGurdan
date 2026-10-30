<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "What is a Work Permit and How Does it Work"
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
class WhatIsWorkPermitBlogSeeder extends Seeder
{
    public const SLUG = 'what-is-work-permit-how-it-works';

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
                'title' => 'What is a Work Permit and How Does it Work',
                'excerpt' => 'A work permit is government permission to work under set conditions. Depending on the country it may be called a permit, pass, residence permit or work visa.',
                'content' => $content,
                'featured_image' => 'blogs/what-is-work-permit-how-it-works.jpg',
                'tags' => 'what is a work permit, how does work permit work, difference between work permit and visa, work permit explained, open work permit canada, employer specific work permit, employment pass singapore, work permit conditions',
                'meta_title' => 'What is a Work Permit and How Does it Work',
                'meta_description' => 'What is a work permit? How it differs from a visa and an offer letter, open versus employer-specific permits, and UK, NZ and Singapore examples.',
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
<p><strong>A work permit is government authorisation to work under specified conditions.</strong> Depending on the country it may be called a work permit, employment pass, residence permit or work visa, and it may name your employer or allow wider employment. It is not an offer letter, and the difference between a work permit and a visa depends on the national system.</p>

<h2>The Basic Idea</h2>

<p>A country decides who may work, through which route and with what limits. The approval may restrict the duration, occupation, employer or location. A job offer supports recruitment, but only the relevant authority grants immigration permission. Paying an agent or holding a company letter does not authorise you to work.</p>

<h2>Work Permit, Visa and Residence</h2>

<table>
<thead><tr><th>Term</th><th>General purpose</th><th>Important limit</th></tr></thead>
<tbody>
<tr><td>Visa</td><td>Travel or entry permission</td><td>Does not always allow work</td></tr>
<tr><td>Work authorisation</td><td>Permission to do eligible employment</td><td>Conditions can restrict the work</td></tr>
<tr><td>Residence permission</td><td>Permission to remain under a status</td><td>Work rights depend on that status</td></tr>
<tr><td>Job offer</td><td>An employer's proposed employment</td><td>Does not replace government permission</td></tr>
</tbody>
</table>

<p>These are general descriptions, not identical legal definitions. In the United States a visa allows you to travel and seek admission; it does not guarantee entry (see the <a href="https://travel.state.gov/content/travel/en/us-visas/visa-information-resources/frequently-asked-questions/what-is-us-visa.html" rel="noopener">US State Department explanation</a>). Some countries combine residence and work rights inside one visa route.</p>

<h2>Employer-Specific and Open Permits</h2>

<p>Canada distinguishes employer-specific work permits, which need a job offer, from open work permits, which have separate eligibility and generally do not. "Open" does not mean anyone can get one or ignore its conditions. For an employer-linked route, changing company may need an update or a new approval, so do not start a different job because a new employer says the document is enough. See the <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada.html" rel="noopener">IRCC work in Canada page</a>.</p>

<h2>How It Works in Practice</h2>

<ol>
<li>Identify the right employment route.</li>
<li>Obtain a suitable offer where one is required.</li>
<li>The employer completes any sponsorship or approval steps.</li>
<li>You prepare your evidence.</li>
<li>Submit through the authorised process.</li>
<li>Complete requested checks or appointments.</li>
<li>Read the approval and entry instructions.</li>
<li>Start work only when you are lawfully authorised.</li>
</ol>

<p>The sequence varies by country, and an application receipt does not by itself give permission to begin work.</p>

<h2>Examples</h2>

<ul>
<li><strong>United Kingdom.</strong> The <a href="https://www.gov.uk/skilled-worker-visa" rel="noopener">Skilled Worker visa</a> allows eligible work with an approved employer. Here the visa and the work permission are one route, with conditions attached.</li>
<li><strong>New Zealand.</strong> The <a href="https://www.immigration.govt.nz/visas/accredited-employer-work-visa/" rel="noopener">Accredited Employer Work Visa</a> ties you to a suitable job with an accredited employer. Changing employer, job or location may need a variation or a new application.</li>
<li><strong>Singapore.</strong> Different passes cover different categories. The <a href="https://www.mom.gov.sg/passes-and-permits/employment-pass" rel="noopener">Employment Pass</a> is a route for eligible professional employment with its own requirements, so "work permit" does not mean one pass for every job.</li>
</ul>

<h2>Documents, Costs and Processing</h2>

<p>Evidence can include a passport, employer information, qualifications and experience. There is no universal checklist. Check official fees before you spend money, and do not resign or buy non-refundable travel on a rumour that your permit will arrive quickly.</p>

<h2>Expiry, Renewal and Changes</h2>

<p>Track the expiry date and conditions. Whether you may keep working while an extension is pending depends on the country and your circumstances. Losing a job has different outcomes under different routes, so check the official instructions promptly. Professional licensing is separate where it applies: a permit does not allow you to practise a regulated occupation.</p>

<h2>Common Misunderstandings</h2>

<ul>
<li>An offer letter is not employment authorisation.</li>
<li>A visitor visa does not usually permit work.</li>
<li>One country's permit does not authorise work elsewhere.</li>
<li>Sponsorship is not guaranteed permanent residence.</li>
<li>The conditions matter as much as the expiry date.</li>
</ul>

<p>Before you travel, compare the employer name, role and dates on your approval with your contract. If something differs, ask the authority rather than assuming it is harmless, and keep copies of decisions and receipts.</p>

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

<h3>Can I work immediately after receiving an offer?</h3>
<p>Only when your status and the rules of the country authorise that employment.</p>

<h3>Is a work permit the same as a visa?</h3>
<p>Not always. Some countries issue both, others combine them in one route.</p>

<h3>Can a work permit let me change employers?</h3>
<p>It depends on the permit category. Employer-specific permits usually need an update first.</p>

<h3>What is an open work permit?</h3>
<p>A permit that is not tied to one employer, with its own eligibility rules, as in Canada.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/what-is-visa-sponsorship-how-it-works">What is Visa Sponsorship and How Does it Work</a>: how sponsorship works</li>
    <li><a href="/blog/how-long-work-visa-takes-2026">How Long Does it Take to Get a Work Visa</a>: realistic timelines</li>
    <li><a href="/blog/how-to-apply-work-visa-without-agent-2026">How to Apply for a Work Visa Without an Agent (2026)</a>: apply yourself</li>
    <li><a href="/blog/english-language-requirements-working-abroad">English Language Requirements for Working Abroad</a>: tests and exemptions</li>
</ul>

<p><em>Reviewed on 12 October 2026.</em></p>

HTML;
    }
}
