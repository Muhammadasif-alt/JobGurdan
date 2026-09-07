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
 * "Social Media Manager Jobs in USA" — a sector guide rather than one vacancy,
 * so the apply link goes to an Indeed search and the post carries no
 * JobPosting markup. The draft's link carried a ?vjk= search-preview
 * parameter, stripped here.
 *
 * Corrections to the draft:
 *
 * 1. It understates pay at both ends, which is unusual and works against the
 *    reader. It put entry level at $35,000-$45,000 when reported pay for under
 *    a year of experience is nearer $53,000 and the bottom tenth of the closest
 *    federal occupation, public relations specialists, is $44,110. It capped
 *    senior pay around $100,000 when the top tenth is above $135,150. Someone
 *    reading the draft would negotiate down.
 *
 * 2. It says nothing about the rules that now govern the actual work. Two
 *    apply directly: the FTC Endorsement Guides, effective 26 July 2023, on
 *    disclosing material connections; and the Rule on the Use of Consumer
 *    Reviews and Testimonials, effective 21 October 2024, which prohibits
 *    buying or selling fake indicators of social media influence — bot or
 *    hijacked-account followers and views — with civil penalties currently
 *    $51,744 per violation. Buying followers to hit a growth target is a
 *    standard shortcut in this job and is now specifically unlawful, so a
 *    careers guide that omits it is not doing its job.
 *
 * 3. Freelance income was described as scaling with client count with no
 *    mention that it is self-employment.
 *
 * The parent discipline is covered by the digital marketing guide, which this
 * links to and which links back; this page owns the social-specific ground
 * rather than restating channel strategy.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SocialMediaManagerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-social-media-manager-jobs.html';

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
        $title = 'Social Media Manager Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What social media manager jobs in the USA really pay, why the entry-level figures in most guides are too low to negotiate against, and the two FTC rules that now govern how you grow a client account — including the ban on buying followers.',
                'content' => $content,
                'featured_image' => 'blogs/social-media-manager-jobs-in-usa.jpg',
                'tags' => 'social media manager jobs in usa, remote social media jobs, freelance social media manager, social media specialist jobs, social media manager salary, entry level social media jobs, community manager jobs, social media jobs no experience',
                'meta_title' => 'Social Media Manager Jobs in USA',
                'meta_description' => 'Social media manager jobs in USA: what the role really pays, why the usual entry-level figures are too low, and the FTC rules on growing an account.',
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
            ['name' => 'US Brands, Agencies & Social Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-social-media-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'marketing'],
            ['name' => 'Marketing']
        );

        Job::updateOrCreate(
            [
                'position' => 'Social Media Manager — US Brands, Agencies and In-House Teams',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours, with community management and launches spilling outside them',
                'language' => 'English',
                // The band runs from about $44,110 to above $135,150 depending on
                // scope, industry and whether paid social is included.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Social media roles with US brands, agencies, e-commerce and SaaS companies, in-house and remote. Apply through the employer listing.',
                'seo_keywords' => 'social media manager jobs in usa, remote social media jobs, social media specialist, freelance social media manager, community manager',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Brands, agencies, e-commerce companies and SaaS businesses across the United States hire social media managers to plan content, publish it, respond to the audience and report on what it produced. Because the platforms are browser-based, most of these roles are remote or hybrid.</p>

<h3>What the work involves</h3>
<p>Planning and scheduling the content calendar, writing captions and short-form scripts, briefing designers and video editors, running community management across replies and direct messages, and reporting on reach, engagement and conversion. In smaller companies it usually also means producing the content yourself, and increasingly it includes paid social, which is a distinct and better-paid skill.</p>

<h3>Requirements</h3>
<ul>
    <li>Writing that works in short form &mdash; captions and hooks, not paragraphs</li>
    <li>Basic design and video editing: Canva and CapCut are the tools named most often</li>
    <li>Working understanding of how each platform distributes content, which changes often enough that this is ongoing rather than learned once</li>
    <li>Scheduling and analytics tools &mdash; Buffer, Later, Hootsuite, and native platform insights</li>
    <li>Community management judgement, including how to handle a complaint in public</li>
    <li>Awareness of the FTC Endorsement Guides and the Consumer Reviews and Testimonials Rule, both of which apply to this work</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Salaried roles</strong> sit against reported averages around $65,000, with the closest federal occupation &mdash; public relations specialists &mdash; showing a $74,750 median, a bottom tenth of $44,110 and a top tenth above $135,150</li>
    <li><strong>Paid social budget responsibility</strong> is the single biggest lever on pay; managing spend pays materially more than managing a calendar</li>
    <li><strong>Freelance</strong> work is typically a monthly retainer per client, and is self-employment with the tax treatment that follows</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask who is accountable for growth targets and what methods are acceptable.</strong> If a role is measured on follower count with no budget attached, find out what the employer expects you to do about it before you accept.</p>

<p><strong>Note:</strong> pay, scope and reporting lines are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Social media management is one of the easiest fields to enter and one of the easiest to be underpaid in, and those two facts are connected. The barrier is low, so the advice aimed at beginners is written to keep expectations modest &mdash; which is why most guides quote entry-level numbers that are below what the role actually pays. This one gives you the real figures, and then covers the part nobody writes about: the federal rules that now govern how you are allowed to grow an account, which have changed materially since 2023 and can put your client at genuine risk if you do not know them.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-social-media-manager-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        📱 Browse Social Media Manager Jobs in the USA &rarr;
    </a>
</div>

<h2>What Social Media Manager Jobs Actually Pay</h2>

<p>Most guides put entry level at $35,000 to $45,000. <strong>That is too low, and quoting it to yourself in a salary conversation costs you money.</strong> Reported pay for social media managers with under a year of experience sits closer to <strong>$53,000</strong>, and average pay across the role is commonly reported around <strong>$65,000</strong>, with the wider range running from roughly <strong>$40,900 to $103,700</strong>.</p>

<p>For a federal anchor, the closest tracked occupation is <em>public relations specialists</em>, which BLS puts at a <strong>median of $74,750 as of May 2025</strong>, with the <strong>lowest tenth under $44,110</strong> and the <strong>highest tenth above $135,150</strong>. The ceiling on this career is a long way above the $100,000 most articles cap it at.</p>

<ul>
    <li><strong>Entry level:</strong> realistically $45,000 to $55,000, not $35,000 &mdash; and lower offers deserve a question rather than a signature</li>
    <li><strong>Two to four years:</strong> roughly $55,000 to $75,000, around the median</li>
    <li><strong>Senior, agency and brand lead:</strong> $80,000 to $120,000, with the top of the distribution past $135,000</li>
    <li><strong>Freelance:</strong> commonly $500 to $2,000 per client per month, with most freelancers running two to five accounts</li>
</ul>

<p><strong>The single largest lever on your pay is whether you touch paid budget.</strong> A manager who plans and publishes organic content is priced as a producer. A manager who is trusted with ad spend and reports on return is priced as a marketer, and the gap between those two is tens of thousands of dollars. If you want the fastest available raise in this field, learn paid social.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/social-media-manager-jobs-in-usa-desk.jpg"
         alt="A social media manager planning a content calendar and reviewing engagement for a US brand"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Rules That Govern How You Grow an Account</h2>

<p>This is the section that separates a professional from someone who is about to cause their client a problem. Two federal rules apply directly to the daily work, and neither is covered in the courses that teach this job.</p>

<h3>Buying followers is now specifically prohibited</h3>
<p>The FTC's <strong>Rule on the Use of Consumer Reviews and Testimonials took effect on 21 October 2024</strong>. Among other things, it <strong>prohibits selling or buying fake indicators of social media influence</strong> &mdash; followers, views or other engagement generated by <strong>bots or hijacked accounts</strong> &mdash; where they are used to misrepresent a brand's popularity or to sell advertising. The rule also covers fake and AI-generated reviews, reviews written by company insiders, suppressing negative reviews, and paying for reviews expressing a particular sentiment. Civil penalties for knowing violations currently run to <strong>$51,744 per violation</strong>.</p>
<p>Why this matters to you specifically: you will be given follower growth targets, and buying followers is the oldest shortcut in this job. It is now a federal rule violation rather than merely a bad idea, and the person who bought them is the person who did it. If a client asks, say no and explain why &mdash; it is one of the clearest ways to demonstrate you are the professional option.</p>

<h3>Disclosure is your responsibility when you post for a brand</h3>
<p>The FTC's revised <strong>Endorsement Guides took effect on 26 July 2023</strong>. Any <strong>material connection</strong> &mdash; payment, free product, affiliate commission, or an employment relationship &mdash; must be disclosed <strong>clearly and conspicuously</strong> and in a form appropriate to the platform. In practice: at the <strong>start of an Instagram caption</strong> rather than buried in hashtags, <strong>spoken in the first seconds of a TikTok</strong> as well as written, and <strong>both spoken and written on YouTube</strong>, since the description box alone is not enough. A platform's built-in "paid partnership" label may not be sufficient on its own.</p>
<p>This bites hardest when you run an influencer or affiliate programme for a client, because you are the one briefing the creators. Building disclosure requirements into the brief is a small amount of work that removes a real liability, and serious clients notice.</p>

<h2>Getting Hired With No Experience</h2>

<p>Entry-level and no-experience roles genuinely exist here, more than in most fields, because the evidence is public and you can create it yourself.</p>

<ul>
    <li><strong>Grow one account and document the numbers.</strong> Your own, a friend's business, a local shop. Screenshots of reach and engagement over a defined period, with a short note on what you changed and why, beat any certificate. Growth you can explain is the portfolio.</li>
    <li><strong>Learn the tools that appear in postings.</strong> Canva, CapCut, and one scheduler &mdash; Buffer, Later or Hootsuite. All have free tiers.</li>
    <li><strong>Learn to read native analytics.</strong> Being able to say why a post worked, rather than that it worked, is what moves you from assistant to manager.</li>
    <li><strong>Pitch small businesses directly.</strong> Most have an abandoned account and no plan, and are not advertising a role. This is the least contested route to first paid work.</li>
    <li><strong>Add paid social as soon as you can.</strong> It is the difference between the two pay bands described above.</li>
</ul>

<h2>Freelance Social Media Work Is Self-Employment</h2>

<p>Managing two to five client accounts on monthly retainers is the standard freelance shape, and it is a business rather than a job. You owe the <strong>federal self-employment tax of 15.3%</strong> on top of income tax, file on <strong>Schedule C</strong>, receive <strong>1099-NEC</strong> forms, and must make <strong>quarterly estimated payments</strong> &mdash; 15 April, 15 June, 15 September and 15 January &mdash; once you expect to owe more than $1,000.</p>

<p>Two contract terms worth fixing in writing before you start: <strong>who owns the account and its login credentials</strong> (it should be the client, always &mdash; holding a client's account hostage is a fast way to end a career), and <strong>what is included in the retainer</strong>. Community management is the item that quietly expands to fill every evening. Define the response window and the volume, or price it separately.</p>

<h2>Where the Roles Are</h2>

<p>LinkedIn carries most in-house and agency postings and is where marketing hiring actually happens, so a complete profile with quantified results matters more here than in most fields. General job boards surface remote and local openings daily. Agency career pages hire specialists and freelancers continuously and often do not syndicate. And direct outreach to small businesses remains the fastest route to a first client, because it skips the hiring process entirely.</p>

<p>Social media sits inside the wider marketing function, and if you want to see how the surrounding roles pay and where the openings are, our <a href="/blog/digital-marketing-jobs-in-usa">guide to digital marketing jobs in the USA</a> covers the parent discipline &mdash; including why it has roughly 82,000 openings a year.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do social media managers make in the USA?</h3>
<p>Reported averages sit around $65,000, with the range running from roughly $40,900 to $103,700. The closest federal occupation, public relations specialists, shows a $74,750 median as of May 2025 with a top tenth above $135,150.</p>

<h3>Is $35,000 a fair entry-level social media salary?</h3>
<p>Generally no. Reported pay for under a year of experience is closer to $53,000, and the bottom tenth of the comparable federal occupation is $44,110. Treat a $35,000 offer as a starting point for a conversation rather than the market rate.</p>

<h3>Can I get a social media job with no experience?</h3>
<p>Yes, more readily than in most fields, because you can create the evidence yourself. Grow one real account, document what you changed and what happened, and be able to explain the numbers rather than just show them.</p>

<h3>Is it legal to buy followers for a client?</h3>
<p>No. The FTC's Consumer Reviews and Testimonials Rule, effective 21 October 2024, prohibits buying or selling fake indicators of social media influence such as bot-generated or hijacked-account followers and views, with civil penalties currently up to $51,744 per violation.</p>

<h3>Do I have to disclose sponsored content I post for a brand?</h3>
<p>Yes. Under the FTC Endorsement Guides, effective 26 July 2023, material connections must be disclosed clearly and conspicuously in a form suited to the platform — at the start of an Instagram caption, spoken early in a TikTok, and both spoken and written on YouTube.</p>

<h3>How do I earn more as a social media manager?</h3>
<p>Take responsibility for paid budget. Managing ad spend and reporting on return moves you from being priced as a content producer to being priced as a marketer, and it is the largest single step in this field.</p>

<h3>What should a freelance social media retainer include?</h3>
<p>Define the deliverables, the community management response window and volume, and the revision limits in writing. Community management is the item that expands without limit if it is left undefined.</p>

<h3>Who should own the client's social accounts?</h3>
<p>The client, always, including the login credentials and any business manager assets. Agree it before you start; withholding account access is the fastest way to end a freelance career.</p>

<h2>People Also Search For</h2>

<h3>Remote social media jobs</h3>
<p>The norm rather than the exception, since the work happens inside browser-based platforms. Confirm overlap hours and whether pay is indexed to your location.</p>

<h3>Freelance social media manager jobs USA</h3>
<p>Usually two to five clients on monthly retainers of roughly $500 to $2,000 each. It is self-employment: budget 15.3% self-employment tax and quarterly estimates.</p>

<h3>Social media specialist jobs</h3>
<p>A narrower remit than a manager role &mdash; often paid ads, analytics or content specifically. A common and sensible first title.</p>

<h3>Social media manager jobs remote no experience</h3>
<p>They exist. One documented account you grew, with the reasoning attached, is what gets you shortlisted.</p>

<h3>Social media manager salary</h3>
<p>Around $65,000 on average, with paid-budget responsibility the biggest single factor separating the bands.</p>

<h3>Community manager jobs</h3>
<p>The engagement half of the role as a standalone position, common at larger brands and in gaming and SaaS.</p>

<h3>Social media jobs LinkedIn</h3>
<p>Where most in-house and agency roles are posted, and where marketing hiring genuinely runs through networks. Keep quantified results on your profile.</p>

<h3>Social media manager portfolio examples</h3>
<p>Before-and-after account metrics with a short explanation of what you changed. Screenshots plus reasoning, not a gallery of posts.</p>

<h2>More Job Guides</h2>

<p>Looking across the rest of the marketing and creative market? These cover it:</p>

<ul>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the parent discipline, its pay bands and why it has the most openings in digital work.</li>
    <li><a href="/blog/copywriter-jobs-in-usa">Copywriter Jobs in USA</a> &mdash; the writing specialism, and the FTC substantiation rule on advertising claims.</li>
    <li><a href="/blog/graphic-designer-jobs-in-usa">Graphic Designer Jobs in USA</a> &mdash; who makes the assets, and what the AI copyright position means for them.</li>
    <li><a href="/blog/ai-content-writer-jobs-in-usa">AI Content Writer Jobs in USA</a> &mdash; the content side of the same market.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Pay data, advertising rules, platform policies and penalty amounts change &mdash; confirm the current position with the Bureau of Labor Statistics, the IRS, the FTC and the employer's own advertisement before applying or signing anything.</p>
HTML;
    }
}
