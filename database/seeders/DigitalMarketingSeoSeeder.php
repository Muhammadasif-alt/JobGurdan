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
 * Urban Solar's remote Digital Marketing Expert (SEO) opening, plus the
 * write-up that breaks it down.
 *
 * Apply points at one specific Indeed posting rather than a search, so the
 * page describes a single real vacancy and keeps its JobPosting markup.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class DigitalMarketingSeoSeeder extends Seeder
{
    private const APPLY_URL = 'https://pk.indeed.com/viewjob?jk=4670dbdb8daeb9c1';

    public function run(): void
    {
        // Job first: the post stores its id so it can carry JobPosting markup.
        $this->seedBlogPost($this->seedJob());
    }

    private function seedBlogPost(Job $job): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'job-spotlights'],
            [
                'name' => 'Job Spotlights',
                'description' => 'Deep dives on specific openings — what the role involves, what employers actually want, and how to apply.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'Digital Marketing Expert (SEO) Job at Urban Solar — Remote, Pakistan';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'job_id' => $job->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Urban Solar is hiring a fully remote Digital Marketing Expert (SEO) across Pakistan — Rs 80,000–120,000 a month, 3–4 years, on-page, off-page and technical SEO plus Canva and AI design work.',
                'content' => $content,
                'featured_image' => 'blogs/digital-marketing-expert-seo-pakistan.jpg',
                'tags' => 'seo jobs pakistan, digital marketing jobs, remote seo jobs, technical seo, digital marketing salary pakistan, Urban Solar, canva jobs, AI design tools',
                'meta_title' => 'Digital Marketing Expert (SEO) Job - Remote Pakistan',
                'meta_description' => 'Urban Solar is hiring a remote Digital Marketing Expert (SEO) in Pakistan: Rs 80,000-120,000/month, 3-4 years, on-page, off-page and technical SEO.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJob(): Job
    {
        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'Urban Solar Pvt Ltd.'],
            ['type' => 'Employer', 'display_reference' => 'urban-solar']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Remote', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'marketing'],
            ['name' => 'Marketing']
        );

        return Job::updateOrCreate(
            [
                'position' => 'Digital Marketing Expert (SEO)',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Full-time, fully remote',
                'language' => 'English',
                'salary_currency' => 'PKR',
                'salary_period' => 'Monthly',
                'salary_minimum' => 80000,
                'salary_maximum' => 120000,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Remote Digital Marketing Expert (SEO) at Urban Solar, Pakistan — Rs 80,000-120,000/month, 3-4 years, on-page, off-page and technical SEO plus Canva and AI design.',
                'seo_keywords' => 'seo jobs pakistan, digital marketing jobs pakistan, remote seo jobs, technical seo jobs, seo specialist job, digital marketing salary pakistan, canva designer jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Urban Solar Pvt Ltd. is hiring a Digital Marketing Expert (SEO) for a full-time, fully remote role open across Pakistan. The position pairs genuine SEO ownership with hands-on creative work, so it suits an SEO generalist who is also fluent in Canva and AI design tools.</p>

<h3>SEO &amp; digital marketing</h3>
<ul>
    <li><strong>On-page SEO</strong> &mdash; keyword optimisation, meta tags, content structuring, internal linking, image SEO</li>
    <li><strong>Off-page SEO</strong> &mdash; backlink building, guest posting, directory submissions, influencer and blogger outreach</li>
    <li><strong>Technical SEO</strong> &mdash; site speed, crawlability, indexing, XML sitemaps, robots.txt, schema markup, mobile-friendliness and Core Web Vitals</li>
    <li>Keyword research and competitor analysis to find growth opportunities</li>
    <li>Tracking and reporting through Google Analytics, Search Console and SEMrush/Ahrefs/Moz</li>
    <li>Keeping strategy current with algorithm updates, and briefing content writers on SEO best practice</li>
</ul>

<h3>Design &amp; creative support</h3>
<ul>
    <li>Website banners, product images and landing page visuals in Canva or similar</li>
    <li>Original visuals for web and social using AI image tools &mdash; Midjourney, DALL&middot;E, Adobe Firefly, Canva AI</li>
</ul>

<h3>Requirements</h3>
<ul>
    <li>3&ndash;4 years of hands-on SEO experience across on-page, off-page and technical work</li>
    <li>Bachelor's in Marketing, Communications, IT or related &mdash; preferred but <strong>not mandatory</strong>; a strong portfolio carries equal weight</li>
    <li>At least one major SEO toolset: Google Analytics, Search Console, SEMrush, Ahrefs, Moz or Screaming Frog</li>
    <li>Working knowledge of Canva or a similar tool (Adobe Express, Figma)</li>
    <li>Practical experience with AI image and design generation tools</li>
    <li>Basic HTML/CSS is a plus for technical SEO fixes without waiting on a developer</li>
    <li>Strong written communication and the ability to work independently in a remote setup</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Rs 80,000&ndash;Rs 120,000 a month &mdash; a narrow, employer-set band rather than an auto-generated estimate, negotiable on experience and portfolio</li>
    <li>Fully remote, full-time, open to candidates anywhere in Pakistan</li>
    <li>End-to-end ownership of both the SEO strategy and the creative that supports it</li>
</ul>

<p><strong>Note:</strong> role details, salary range and company rating are taken from the employer's public Indeed listing and may change or close without notice. Confirm current terms directly with Urban Solar Pvt Ltd. before applying or accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you're an SEO specialist who can also hold your own on the design side, this listing is worth a look: <strong>Urban Solar Pvt Ltd.</strong> is hiring a <strong>Digital Marketing Expert (SEO)</strong> for a full-time, fully remote role open across Pakistan. It's a hybrid position &mdash; real SEO ownership paired with hands-on creative work &mdash; which makes it a slightly different ask than a typical pure-SEO role.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/viewjob?jk=4670dbdb8daeb9c1" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        📈 Apply for Digital Marketing Expert (SEO) at Urban Solar →
    </a>
</div>

<h2>The Role at a Glance</h2>

<ul>
    <li><strong>Title:</strong> Digital Marketing Expert (SEO)</li>
    <li><strong>Company:</strong> Urban Solar Pvt Ltd. (3.0&#9733; on Indeed)</li>
    <li><strong>Location:</strong> Pakistan &mdash; fully remote</li>
    <li><strong>Job type:</strong> Full-time</li>
    <li><strong>Experience required:</strong> 3&ndash;4 years</li>
    <li><strong>Advertised pay:</strong> Rs 80,000 &ndash; Rs 120,000 a month</li>
</ul>

<p>Unlike some auto-generated Indeed salary ranges, this one is specific and narrow (Rs 80,000&ndash;120,000), which usually signals the employer set the range deliberately rather than Indeed estimating it &mdash; a good sign that the number is realistic and negotiable within that band based on your experience and portfolio.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/digital-marketing-expert-seo-pakistan-desk.jpg"
         alt="SEO specialist reviewing organic traffic, keyword rankings and backlink dashboards — Digital Marketing Expert SEO job at Urban Solar, remote Pakistan"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What the Job Actually Involves</h2>

<p>This role splits fairly evenly into two halves:</p>

<h3>SEO &amp; Digital Marketing (the core of the job)</h3>
<ul>
    <li>Full <strong>On-Page SEO</strong> &mdash; keyword optimization, meta tags, content structuring, internal linking, image SEO</li>
    <li><strong>Off-Page SEO</strong> &mdash; backlink building, guest posting, directory submissions, influencer/blogger outreach</li>
    <li><strong>Technical SEO</strong> &mdash; site speed, crawlability, indexing, XML sitemaps, robots.txt, schema markup, mobile-friendliness, and Core Web Vitals</li>
    <li>Keyword research and competitor analysis to find growth opportunities</li>
    <li>Performance tracking and reporting via Google Analytics, Search Console, and tools like SEMrush/Ahrefs/Moz</li>
    <li>Staying current on algorithm updates and adjusting strategy accordingly</li>
    <li>Working with content writers to keep SEO best practices consistent</li>
</ul>

<h3>Design &amp; Creative Support (the differentiator)</h3>
<ul>
    <li>Designing website banners, product images, and landing page visuals in Canva or similar tools</li>
    <li>Using AI-based design/image tools (Midjourney, DALL&middot;E, Adobe Firefly, Canva AI, etc.) to produce original visuals for web and social</li>
</ul>

<p>That second half is what makes this posting stand out from a typical SEO opening &mdash; Urban Solar isn't just looking for someone who can move rankings, they want someone who can also visually execute the campaigns those rankings support.</p>

<h2>What They're Looking For</h2>

<ul>
    <li>A bachelor's degree in Marketing, Communications, IT, or a related field &mdash; preferred but explicitly <strong>not mandatory</strong>, so a strong portfolio can carry equal or more weight</li>
    <li>Solid, hands-on experience across On-Page, Off-Page, and Technical SEO &mdash; not just theory</li>
    <li>Familiarity with at least one major SEO toolset: Google Analytics, Search Console, SEMrush, Ahrefs, Moz, or Screaming Frog</li>
    <li>Working knowledge of Canva or a similar design tool (Adobe Express, Figma, etc.)</li>
    <li>Real experience using AI image/design generation tools as part of the workflow</li>
    <li>Basic HTML/CSS is a plus &mdash; useful for hands-on technical SEO fixes rather than always relying on a developer</li>
    <li>Strong communication skills and the ability to work independently, since this is a remote role</li>
</ul>

<h2>Who This Role Suits Best</h2>

<p>This is a strong fit if you're an SEO generalist who has picked up genuine design fluency along the way &mdash; someone comfortable moving between a technical SEO audit in the morning and knocking out social creatives in Canva by the afternoon. It's less suited to a pure technical SEO specialist with no design interest, or a designer with only surface-level SEO knowledge &mdash; the posting is clearly looking for someone credible on both fronts, not just one.</p>

<p>The "not mandatory" note on the degree requirement, combined with the emphasis on "proven work experience... with a strong portfolio," suggests Urban Solar is prioritizing demonstrated results (rankings moved, traffic grown, campaigns designed) over formal credentials &mdash; worth leading with concrete numbers and portfolio examples in your application.</p>

<h2>How to Apply</h2>

<p>If your background covers both sides of this role &mdash; solid SEO fundamentals and comfort with Canva/AI design tools &mdash; it's worth applying directly with a portfolio or case studies that show measurable SEO results alongside a few examples of your design work.</p>

<h2>Frequently Asked Questions</h2>

<h3>What is the salary for a Digital Marketing Expert (SEO) in Pakistan?</h3>
<p>This role advertises Rs 80,000&ndash;Rs 120,000 a month. Because the band is narrow and specific rather than a wide auto-generated estimate, it is likely the employer's own figure and negotiable within that range based on your experience and portfolio.</p>

<h3>Is this SEO job remote?</h3>
<p>Yes &mdash; it is a fully remote, full-time position open to candidates across Pakistan.</p>

<h3>How much experience do I need?</h3>
<p>Three to four years of hands-on SEO experience covering on-page, off-page and technical work.</p>

<h3>Do I need a degree to apply?</h3>
<p>No. A bachelor's in Marketing, Communications or IT is preferred but explicitly not mandatory, so a strong portfolio with measurable results can carry equal weight.</p>

<h3>Why does an SEO role ask for design skills?</h3>
<p>Urban Solar wants one person who can both move rankings and produce the visuals those campaigns need &mdash; banners, product images and landing page creatives in Canva, plus AI image tools like Midjourney, DALL&middot;E or Adobe Firefly.</p>

<h2>People Also Search For</h2>

<h3>SEO jobs in Pakistan remote</h3>
<p>Fully remote SEO roles are increasingly common with Pakistani companies serving local and international clients, usually asking for on-page, off-page and technical SEO in one person.</p>

<h3>Digital marketing salary in Pakistan per month</h3>
<p>Mid-level digital marketing and SEO roles with three to four years of experience commonly advertise in the Rs 80,000&ndash;Rs 120,000 a month range, rising with proven results and specialist skills.</p>

<h3>SEO specialist job description</h3>
<p>On-page optimization, backlink building and outreach, technical audits covering crawlability, sitemaps, schema and Core Web Vitals, keyword and competitor research, and reporting through Google Analytics and Search Console.</p>

<h3>Technical SEO jobs Pakistan</h3>
<p>Employers increasingly ask for Core Web Vitals, schema markup, robots.txt and indexing experience alongside basic HTML/CSS, so fixes do not always need a developer.</p>

<h3>Digital marketing jobs with Canva and AI tools</h3>
<p>A growing number of listings pair SEO with hands-on creative work in Canva plus AI image generation (Midjourney, DALL&middot;E, Adobe Firefly), looking for one hire who can plan and execute campaigns.</p>

<h3>Urban Solar Pvt Ltd jobs and reviews</h3>
<p>Urban Solar Pvt Ltd. is rated 3.0 out of 5 on Indeed and is hiring remotely across Pakistan for this full-time digital marketing role.</p>

<h2>More Job Guides</h2>

<p>Looking at other roles or countries? These guides cover the rest of what we track:</p>

<ul>
    <li><a href="/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern">Senior Frontend Developer at ERS Tech, Lahore</a> &mdash; React, Next.js and MERN, on-site in Lahore.</li>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; H-2B, EB-3 and H-1B routes plus 2026 pay benchmarks.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London (No Experience Needed)</a> &mdash; shift patterns, 2026 pay and the truth about sponsorship.</li>
    <li><a href="/blog/caregiver-jobs-in-uk-with-visa-sponsorship">Caregiver Jobs in UK with Visa Sponsorship</a> &mdash; what the July 2025 care worker route closure means.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/viewjob?jk=4670dbdb8daeb9c1" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Apply for Digital Marketing Expert (SEO) at Urban Solar →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">Role details, salary range and company rating are taken from the employer's public Indeed listing at the time of writing and may change or close without notice. Confirm current terms directly with Urban Solar Pvt Ltd. before applying or accepting an offer.</p>
HTML;
    }
}
