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
 * ERS Tech's Senior Frontend Developer opening in Lahore, plus the write-up
 * that breaks it down.
 *
 * Unlike the visa-guide listings, Apply points at one specific Indeed posting
 * rather than a search, so this page does describe a single real vacancy and
 * keeps its JobPosting markup.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class FrontendDeveloperLahoreSeeder extends Seeder
{
    private const APPLY_URL = 'https://pk.indeed.com/viewjob?jk=6775ed0027cf10b0';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJob();
    }

    private function seedBlogPost(): void
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
        $title = 'Senior Frontend Developer Job at ERS Tech, Lahore (React / Next.js / MERN)';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'ERS Tech is hiring a Senior Frontend Developer in Lahore — 6+ years, React and Next.js App Router, MERN fluency. Full breakdown of the stack, the salary range, and who the role actually suits.',
                'content' => $content,
                'featured_image' => 'blogs/senior-frontend-developer-lahore.jpg',
                'tags' => 'frontend developer jobs, react jobs lahore, nextjs developer, MERN stack jobs, senior developer pakistan, ERS Tech, typescript jobs',
                'meta_title' => 'Senior Frontend Developer Job in Lahore — React / Next.js (ERS Tech)',
                'meta_description' => 'ERS Tech is hiring a Senior Frontend Developer in Lahore: 6+ years, React, Next.js App Router and MERN. Full stack breakdown, salary range and how to apply.',
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
            ['name' => 'ERS Tech'],
            ['type' => 'Employer', 'display_reference' => 'ers-tech']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Lahore'],
            ['area' => 'Punjab', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Senior Frontend Developer (React / Next.js / MERN)',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, in-person at the Lahore office',
                'language' => 'English',
                'salary_currency' => 'PKR',
                'salary_period' => 'Monthly',
                'salary_minimum' => 24130,
                'salary_maximum' => 133254,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Senior Frontend Developer at ERS Tech, Lahore — 6+ years React, Next.js App Router, TypeScript and MERN. Full-time, on-site.',
                'seo_keywords' => 'frontend developer jobs lahore, react developer jobs pakistan, nextjs developer jobs, MERN stack developer, senior frontend engineer, typescript jobs lahore',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>ERS Tech, a Lahore-based development company, is hiring a Senior Frontend Developer to own the frontend lifecycle end to end &mdash; turning Figma, Adobe XD and Sketch designs into pixel-perfect production interfaces, building scalable component architecture, integrating REST and third-party APIs, and optimising for performance, SEO and accessibility.</p>

<p>You would work across dashboards, admin panels, customer portals, fintech interfaces and SaaS platforms, alongside UI/UX designers, backend developers, QA and project managers. Code reviews, coding standards and frontend architecture decisions are part of the role, so this is pitched at genuine senior/ownership level rather than pure execution.</p>

<h3>Requirements</h3>
<ul>
    <li>6+ years of professional frontend development experience</li>
    <li><strong>React.js</strong> &mdash; hooks, functional components, component architecture, state management, Context API, performance optimisation</li>
    <li><strong>Next.js</strong> &mdash; App Router, SSR, SSG, Server/Client Components, dynamic routing, middleware, SEO and performance work</li>
    <li>JavaScript (ES6+) and strong TypeScript</li>
    <li>HTML5, CSS3, responsive and mobile-first design, Flexbox, Grid, CSS animations</li>
    <li>Tailwind CSS, Bootstrap, and a UI framework such as Material UI or Ant Design</li>
    <li>REST APIs, JWT, OAuth, payment gateway integrations, Postman</li>
    <li>MERN stack fluency &mdash; MongoDB, Express.js, React, Node.js (hands-on Node/Express is a strong advantage)</li>
    <li>Git/GitHub/GitLab, npm/Yarn/PNPM, Figma, Chrome DevTools, ESLint, Prettier, CI/CD</li>
</ul>

<h3>Nice to have</h3>
<ul>
    <li>FinTech, payment platforms, digital wallets, crypto/blockchain or trading platform experience</li>
    <li>React Native or other cross-platform/PWA experience</li>
    <li>Docker, AWS, GraphQL, microservices, WebSockets</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Exposure to international projects across FinTech, Blockchain, AI and SaaS</li>
    <li>Competitive salary based on experience &mdash; the advertised Rs 24,130&ndash;Rs 133,254 a month is an Indeed-generated estimate, so confirm the real figure during interview</li>
    <li>Collaborative environment and career growth opportunities</li>
</ul>

<p><strong>Note:</strong> role details, salary range and company rating are taken from the employer's public Indeed listing and may change or close without notice. Confirm current terms directly with ERS Tech before applying or accepting an offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>If you're a frontend engineer in Pakistan with real React and Next.js chops, this is one worth a close look: <strong>ERS Tech</strong>, a Lahore-based development company rated <strong>4.7 out of 5</strong> on Indeed, is hiring a <strong>Senior Frontend Developer</strong> for a full-time, in-person role in Lahore. Here's a full breakdown of what the role actually involves, what they're asking for, and who this is a strong fit for.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/viewjob?jk=6775ed0027cf10b0" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        💻 View &amp; Apply on Indeed →
    </a>
</div>

<h2>The Role at a Glance</h2>

<ul>
    <li><strong>Title:</strong> Senior Frontend Developer &ndash; React / Next.js / MERN</li>
    <li><strong>Company:</strong> ERS Tech (4.7&#9733;, 3 reviews on Indeed)</li>
    <li><strong>Location:</strong> Lahore &mdash; on-site, in-person</li>
    <li><strong>Job type:</strong> Full-time</li>
    <li><strong>Experience required:</strong> 6+ years of professional frontend development</li>
    <li><strong>Advertised pay:</strong> Rs 24,130 &ndash; Rs 133,254 a month</li>
</ul>

<p>A quick note on that salary range: it's unusually wide, which typically means Indeed has generated an estimated range rather than the employer publishing an exact figure. For a genuinely senior, 6+ years React/Next.js hire in Lahore's current market, expect the real offer to land well above the lower end of that bracket &mdash; it's worth confirming the exact number directly with ERS Tech during the interview process rather than anchoring to the posted range.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/senior-frontend-developer-lahore-desk.jpg"
         alt="Senior frontend developer working on React and Next.js code — Senior Frontend Developer job at ERS Tech, Lahore"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What You'd Actually Be Doing</h2>

<p>This isn't a "just wire up components" role &mdash; ERS Tech is looking for someone who owns the frontend lifecycle end to end: turning Figma/Adobe XD/Sketch designs into pixel-perfect, production-ready interfaces, building and maintaining scalable component architecture, integrating REST and third-party APIs, and optimizing for performance, SEO, and accessibility. You'd be working across desktop, tablet, and mobile, building things like dashboards, admin panels, customer portals, fintech interfaces, and SaaS platforms &mdash; and collaborating closely with UI/UX designers, backend developers, QA, and project managers along the way.</p>

<p>Code reviews, coding standards, and contributing to frontend architecture decisions are explicitly part of the job too, so this is pitched at a genuine senior/ownership level rather than a pure execution role.</p>

<h2>The Tech Stack They're Looking For</h2>

<h3>Core</h3>
<ul>
    <li><strong>React.js</strong> &mdash; hooks, functional components, component architecture, state management, Context API, performance optimization</li>
    <li><strong>Next.js</strong> &mdash; App Router, SSR, SSG, Server/Client Components, dynamic routing, middleware, Next.js-specific performance and SEO work</li>
    <li><strong>JavaScript (ES6+)</strong> and strong <strong>TypeScript</strong></li>
</ul>

<h3>UI / Styling</h3>
<ul>
    <li>HTML5, CSS3, responsive/mobile-first design, Flexbox, Grid, CSS animations</li>
    <li>Tailwind CSS, Bootstrap, and a UI framework like Material UI or Ant Design</li>
</ul>

<h3>API &amp; Backend Awareness</h3>
<ul>
    <li>REST APIs, JWT, OAuth, payment gateway integrations, Postman</li>
    <li>MERN stack fluency &mdash; MongoDB, Express.js, React, Node.js &mdash; with hands-on Node/Express experience called out as "a strong advantage"</li>
</ul>

<h3>Tooling</h3>
<ul>
    <li>Git/GitHub/GitLab, VS Code, npm/Yarn/PNPM, Figma, Chrome DevTools, ESLint, Prettier, CI/CD</li>
</ul>

<h3>Nice-to-haves that stand out</h3>
<ul>
    <li>FinTech, payment platforms, digital wallets, crypto/blockchain, or trading platform experience</li>
    <li>React Native or other cross-platform/PWA experience</li>
    <li>Docker, AWS, GraphQL, microservices, WebSockets</li>
</ul>

<p>If your background touches fintech, SaaS dashboards, or blockchain/Web3 products, this posting specifically flags that as an advantage &mdash; worth highlighting prominently in your application if it applies to you.</p>

<h2>Who This Role Is a Strong Fit For</h2>

<p>This posting reads less like a generic "React developer" ad and more like a role built for someone who:</p>

<ul>
    <li>Has genuinely shipped production Next.js applications (not just React SPAs) &mdash; SSR, SSG, and App Router experience are called out specifically, not just "Next.js familiarity."</li>
    <li>Is comfortable being the frontend authority in the room &mdash; someone who can push back on designs where needed and make architecture calls, not just implement tickets.</li>
    <li>Has some backend literacy even if frontend is the focus &mdash; the MERN and Node/Express mentions suggest they value engineers who understand the full data flow, not just the UI layer.</li>
    <li>Has a strong design eye &mdash; the repeated emphasis on "pixel-perfect," spacing, typography, and visual hierarchy suggests UI craftsmanship is weighted heavily in how candidates get evaluated, not just technical correctness.</li>
</ul>

<h2>What's In It for You</h2>

<p>Per the listing, ERS Tech is offering exposure to international projects and modern tech across FinTech, Blockchain, AI, and SaaS &mdash; plus a collaborative environment and career growth opportunities. As with most listings, "competitive salary based on experience" means the real number is negotiable, so come prepared with a clear sense of your own market rate (senior React/Next.js talent in Lahore with 6+ years and fintech/SaaS exposure is in genuine demand right now) rather than accepting the first figure offered.</p>

<h2>How to Apply</h2>

<p>The listing is live on Indeed Pakistan. If you meet the core bar &mdash; 6+ years, strong React and Next.js, comfortable with API integration and pixel-perfect UI work &mdash; it's worth applying directly and tailoring your CV to highlight any fintech, SaaS dashboard, or design-to-code work you've shipped, since those are the specific differentiators this posting calls out.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much does a Senior Frontend Developer earn in Lahore?</h3>
<p>This posting advertises Rs 24,130&ndash;Rs 133,254 a month, but that wide bracket is an Indeed-generated estimate rather than a published figure. For a 6+ year React/Next.js engineer in Lahore, the realistic offer sits well above the bottom of that range &mdash; confirm the exact number during the interview.</p>

<h3>Is this role remote or on-site?</h3>
<p>On-site, in person, at ERS Tech's Lahore office. It is a full-time position.</p>

<h3>How many years of experience do I need?</h3>
<p>Six or more years of professional frontend development, with production Next.js work (SSR, SSG, App Router) rather than React SPA experience alone.</p>

<h3>Do I need backend experience for this frontend role?</h3>
<p>Not strictly, but hands-on Node.js and Express experience is called out as a strong advantage, and MERN stack fluency is listed among the requirements.</p>

<h3>What makes an application stand out for this job?</h3>
<p>Shipped production Next.js apps, fintech or SaaS dashboard work, blockchain/Web3 exposure, and a portfolio that demonstrates pixel-perfect design-to-code craftsmanship.</p>

<h2>People Also Search For</h2>

<h3>Senior frontend developer jobs in Lahore</h3>
<p>Lahore's development market is one of Pakistan's most active for React, Next.js and MERN roles, particularly with agencies serving international fintech and SaaS clients.</p>

<h3>React developer jobs in Pakistan</h3>
<p>React remains the most in-demand frontend skill across Pakistani job boards, usually paired with TypeScript and, increasingly, Next.js App Router experience.</p>

<h3>Next.js developer salary in Pakistan</h3>
<p>Senior Next.js engineers with 6+ years typically command well above entry-level frontend rates, especially with fintech, payments or SaaS dashboard experience on their CV.</p>

<h3>MERN stack developer jobs Lahore</h3>
<p>MongoDB, Express, React and Node.js fluency is commonly requested even for frontend-led roles, since employers value engineers who understand the full data flow.</p>

<h3>Frontend developer jobs with fintech experience</h3>
<p>Payment gateway integrations, digital wallets, trading platforms and crypto/blockchain products are repeatedly flagged as differentiators in senior frontend postings.</p>

<h3>ERS Tech Lahore jobs and reviews</h3>
<p>ERS Tech is rated 4.7 out of 5 on Indeed (3 reviews) and advertises exposure to international projects across FinTech, Blockchain, AI and SaaS.</p>

<h2>More Job Guides</h2>

<p>Looking further afield? These guides cover overseas routes we track:</p>

<ul>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; H-2B, EB-3 and H-1B routes plus 2026 pay benchmarks.</li>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; EB-3 and H-2B routes and CDL requirements.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; H-2B and J-1 hospitality sponsorship.</li>
    <li><a href="/blog/cleaner-jobs-in-london-no-experience-needed">Cleaner Jobs in London (No Experience Needed)</a> &mdash; shift patterns, 2026 pay and the truth about sponsorship.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://pk.indeed.com/viewjob?jk=6775ed0027cf10b0" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 View and Apply to the Role on Indeed →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">Role details, salary range and company rating are taken from the employer's public Indeed listing at the time of writing and may change or close without notice. Confirm current terms directly with ERS Tech before applying or accepting an offer.</p>
HTML;
    }
}
