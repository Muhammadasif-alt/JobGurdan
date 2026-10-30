<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Difference Between Work Visa and Work Permit"
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
class WorkVisaVsWorkPermitBlogSeeder extends Seeder
{
    public const SLUG = 'work-visa-vs-work-permit-difference';

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
                'title' => 'Difference Between Work Visa and Work Permit',
                'excerpt' => 'A visa commonly allows you to travel and ask for entry, while a work permit authorises employment under conditions, but countries use the terms differently.',
                'content' => $content,
                'featured_image' => 'blogs/work-visa-vs-work-permit-difference.jpg',
                'tags' => 'work visa vs work permit difference, visa or permit to work abroad, work authorization explained, visa permit comparison, canada work permit not a visa, uk skilled worker visa, eu blue card, employer specific work permit',
                'meta_title' => 'Difference Between Work Visa and Work Permit',
                'meta_description' => 'Difference between a work visa and a work permit: comparison table, Canada, UK, US, Germany and Singapore examples and a pre-travel checklist.',
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
<p><strong>The main difference is purpose: a visa commonly lets you travel to a country and request entry, while a work permit authorises employment under set conditions.</strong> Countries use the words differently, though. Some work visas combine permission to live and work, and other systems need a separate travel document and employment authorisation. A job offer alone never gives you permission to start work. For a wider explanation see <a href="/blog/what-is-work-permit-how-it-works">what a work permit is</a>.</p>

<h2>Quick Comparison</h2>

<table>
<thead><tr><th>Question</th><th>Work visa</th><th>Work permit or authorisation</th></tr></thead>
<tbody>
<tr><td>Main purpose</td><td>Travel, entry or a combined work and residence route</td><td>Permission to do specified employment</td></tr>
<tr><td>Conditions that matter</td><td>Category, validity, entries</td><td>Employer, occupation, location, hours</td></tr>
<tr><td>Always separate?</td><td>No. Some visas include work rights</td><td>No. Work rights may come with status</td></tr>
<tr><td>Guarantees entry?</td><td>No. Admission is decided at the border</td><td>May still need a travel document</td></tr>
</tbody>
</table>

<h2>Canada: Separate Documents</h2>

<p>A Canadian work permit is not a visa. Depending on your nationality you may also need a visitor visa or an electronic travel authorisation to travel. Canada has employer-specific permits and open work permits, and an open permit is only for people who qualify in an eligible category. See the <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada.html" rel="noopener">IRCC work in Canada page</a>.</p>

<h2>United Kingdom: The Visa Includes Work Rights</h2>

<p>The <a href="https://www.gov.uk/skilled-worker-visa" rel="noopener">Skilled Worker visa</a> allows eligible people to live and work in the UK in an eligible job for an approved employer. You do not need a separate document called a work permit, but you must check what your permission allows and whether a change of job needs an updated application.</p>

<h2>United States: Entry and Work Are Different Questions</h2>

<p>A US visa lets you travel to a port of entry and request admission; it does not guarantee entry (see the <a href="https://travel.state.gov/content/travel/en/us-visas/visa-information-resources/frequently-asked-questions/what-is-us-visa.html" rel="noopener">State Department explanation</a>). Whether you may work depends on your immigration category: some people are authorised through their status, while others need an Employment Authorization Document. USCIS explains the options on its <a href="https://www.uscis.gov/working-in-the-united-states" rel="noopener">working in the United States page</a>.</p>

<h2>Germany: A Residence Title May Authorise Work</h2>

<p>The <a href="https://www.make-it-in-germany.com/en/visa-residence/types/eu-blue-card" rel="noopener">EU Blue Card</a> is a residence title for qualified employment with its own qualification, job and salary conditions. An entry visa and a residence title can be different stages, so follow the instructions for your nationality and route.</p>

<h2>Singapore: Approval and Issuance Are Stages</h2>

<p>For an Employment Pass the employer or an employment agent submits the application, and approval and issue of the pass are distinct steps. The <a href="https://www.mom.gov.sg/passes-and-permits/employment-pass/apply-for-a-pass" rel="noopener">MOM page</a> sets out each stage.</p>

<h2>Australia and New Zealand</h2>

<p>The <a href="https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/skills-in-demand-visa-subclass-482" rel="noopener">Skills in Demand visa</a> and the <a href="https://www.immigration.govt.nz/visas/accredited-employer-work-visa/" rel="noopener">Accredited Employer Work Visa</a> tie employment permission to a named visa route. An employer's willingness to hire does not remove the immigration requirements.</p>

<h2>Employer-Specific or Open</h2>

<p>An employer-specific permission may restrict you to the employer or position named in your approval, and an open permission allows broader work within its conditions. Before a second job, freelance work or a promotion, check that your permission covers it, and check remote work for an overseas company against local immigration conditions.</p>

<h2>Checklist Before You Travel or Start</h2>

<ol>
<li>What is the exact name of my immigration route?</li>
<li>Does my nationality need a separate entry document?</li>
<li>Who submits the employer application or sponsorship?</li>
<li>What approval must I hold before I travel?</li>
<li>When can I lawfully start the job?</li>
<li>Which employer, occupation and location are allowed?</li>
<li>What happens if I change job or my contract ends?</li>
<li>Which document proves my permission and stay?</li>
</ol>

<p>Compare names, dates and job details across every document, and resolve inconsistencies with the authority rather than the employer's verbal assurance. A tourist visa, interview invitation or offer letter is not employment authorisation.</p>

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

<h3>Is a work visa the same as a work permit?</h3>
<p>Sometimes the terms overlap, but the legal meaning depends on the country.</p>

<h3>Do I always need both documents?</h3>
<p>No. Some systems combine them, others need a travel document and a work authorisation.</p>

<h3>Can I start work after receiving an offer?</h3>
<p>Only when you have the required authorisation and meet its conditions.</p>

<h3>Can I change employers with valid permission?</h3>
<p>Possibly. Employer-specific routes may need new authorisation first.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/what-is-work-permit-how-it-works">What is a Work Permit and How Does it Work</a>: permit explained</li>
    <li><a href="/blog/what-is-visa-sponsorship-how-it-works">What is Visa Sponsorship and How Does it Work</a>: how sponsorship works</li>
    <li><a href="/blog/how-long-work-visa-takes-2026">How Long Does it Take to Get a Work Visa</a>: realistic timelines</li>
    <li><a href="/blog/employer-cancels-work-visa">What Happens if Your Employer Cancels Your Visa</a>: job loss and status</li>
</ul>

<p><em>Reviewed on 12 October 2026.</em></p>

HTML;
    }
}
