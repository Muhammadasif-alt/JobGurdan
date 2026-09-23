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
 * "Canva Social Media Jobs" - a tool-specific production role, kept distinct
 * from Social Media Manager Jobs in USA, which owns the American market and the
 * platform growth rules, and from Graphic Designer Jobs in USA.
 *
 * The draft lists Brand Kit, Magic Resize and in-app scheduling as things a
 * beginner should learn, without saying which of them a free Canva account can
 * actually open. It also omits the licence entirely, which is the one thing
 * that can cost a freelancer money rather than time: Canva's Content License
 * Agreement restricts what you may do with its stock content in client work.
 *
 * Corrections and cautions applied (checked 23 September 2026):
 *
 * - Free versus paid features are stated explicitly, because a portfolio plan
 *   built on paid-only features is a plan a beginner cannot execute.
 *
 * - The licence terms are published, including the uses that are prohibited in
 *   paid client work and what happens to premium elements if a subscription
 *   lapses.
 *
 * - Pay figures from individual freelance postings are not presented as market
 *   rates, and ChatGPT citation artifacts are not published.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CanvaSocialMediaJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.mustakbil.com/';

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
        $title = 'Canva Social Media Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Half the Canva features these job descriptions ask for are behind a paid plan, and the licence limits what you may do with Canva content in client work. What is genuinely free, what the rules are, and how to build a portfolio.',
                'content' => $content,
                'featured_image' => 'blogs/canva-social-media-jobs.jpg',
                'tags' => 'canva social media jobs, canva assistant jobs, social media designer pakistan, canva pro free features, canva licence client work, instagram carousel design jobs, remote canva jobs, social media portfolio',
                'meta_title' => 'Canva Social Media Jobs: Skills, Pay and the Licence',
                'meta_description' => 'Canva social media jobs: which Canva features are actually free, what the content licence allows in client work, real pay examples and how to build a portfolio.',
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
            ['name' => 'Employers Hiring Canva Social Media Assistants (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'canva-social-media-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Remote'],
            ['area' => 'Worldwide', 'country' => 'Remote']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'marketing'],
            ['name' => 'Marketing']
        );

        Job::updateOrCreate(
            [
                'position' => 'Canva Social Media Assistant',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Contract',
                'job_type' => 'Remote',
                'work_hours' => 'Often deliverable-based rather than hours-based; confirm which before accepting',
                'language' => 'English, because captions and client feedback are written in it',
                // Freelance budgets in this category range from a few dollars a
                // project to monthly salaries, so a single band would mislead.
                // The guide quotes individual postings with their dates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aggregated Canva and social media design roles covering posts, carousels, stories and brand-consistent content production.',
                'seo_keywords' => 'canva social media jobs, canva assistant, social media designer, instagram carousel design, remote design jobs pakistan',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of Canva and social media design roles, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own site or on the platform where the role appears.</p>

<h3>What the work involves</h3>
<p>Working from a brief and a brand guide to produce social posts, carousels, stories and promotional graphics, adapting one design across platform formats, formatting captions, keeping files organised and preparing approved content for scheduling.</p>

<h3>Two questions to ask before accepting</h3>
<p>Does the client provide the Canva seat, or are you expected to pay for it? Several features these briefs assume &mdash; brand kits, resizing and in-app scheduling &mdash; sit on a paid plan. And is the pay per design, per week or per hour? Carousel work is priced per slide by some clients and per post by others, which is a large difference.</p>

<h3>The licence matters</h3>
<p>Canva's content licence restricts some commercial uses of its stock elements and templates, including in logos and resale products. Read it before agreeing to produce anything beyond social posts.</p>

<p>Requirements, rates and deliverables are set by individual clients &mdash; not by JobGader. Never pay anyone to secure work, and never publish a client's material in a portfolio without permission.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canva social media work is one of the fastest routes into paid remote work from Pakistan, because the barrier is a portfolio rather than a qualification. It also has two traps that cost beginners money, and neither appears in the job descriptions.</p>

<p>The first is that <strong>several of the Canva features these roles ask for are not on the free plan</strong>. The second is that <strong>Canva's content licence limits what you may do with its templates and stock elements in paid client work</strong>. This guide covers both, plus what the work actually pays.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/canva-social-media-jobs-workspace.jpg" alt="A designer working on social media graphics in Canva on a laptop" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Most of this job is adapting an approved design accurately, not inventing one.</figcaption>
</figure>

<h2>What the Job Actually Is</h2>

<p>You are not the designer deciding how the brand looks. You are the person turning a brief and an existing brand into finished, correctly formatted posts, on schedule and without errors.</p>

<p>A normal task cycle looks like this:</p>

<ol>
    <li>Read the brief and open the approved template.</li>
    <li>Drop in the supplied copy, images and logo.</li>
    <li>Adapt the layout for the platform it is going to.</li>
    <li>Check spelling, spacing, alignment and brand colours.</li>
    <li>Export in the right format, or prepare it for scheduling.</li>
    <li>File it where the client can find it, and mark the task done.</li>
</ol>

<p>The skill being paid for is <strong>consistency</strong>. A client who has to re-check every file loses the time they hired you to save, and that is the usual reason these contracts end.</p>

<h2>The Feature Trap: What Free Canva Cannot Do</h2>

<p>Job descriptions in this category routinely ask for brand kits, one-click resizing across formats, background removal and scheduling posts from inside Canva. Read them and it sounds like a list of things to practise.</p>

<p>Several of those sit on a paid plan. That matters in two concrete ways:</p>

<ul>
    <li><strong>Your portfolio plan may not be executable.</strong> If you set out to demonstrate brand-kit work and resizing on a free account, you will hit a wall partway through.</li>
    <li><strong>Someone has to pay for the seat.</strong> If a client expects you to arrive with a paid subscription, that cost comes out of your fee, and at the rates quoted in this category it can be a meaningful share of it.</li>
</ul>

<p>So before you accept work, ask one question: <strong>"Will you add me to your Canva account, or am I expected to have my own paid plan?"</strong> A client running a brand kit already has a paid team plan and can invite you to it at no extra cost to you. A client who wants you to fund it is quietly transferring an expense.</p>

<p>Check the current free-plan limits on Canva's own pricing page on the day you need them rather than trusting any article, including this one &mdash; the split between free and paid features moves.</p>

<h2>The Licence: What You May and May Not Do With Canva Content</h2>

<p>This is the section no competing article carries, and it is the one that can cost you rather than merely inconvenience you.</p>

<p>Canva's content licence governs what you are allowed to do with its templates, stock photos and graphic elements. Social posts for a client are squarely within normal use. Several adjacent requests are not, and they come up often enough that you should recognise them:</p>

<ul>
    <li><strong>Logos.</strong> Clients ask "can you make me a logo while you are in there?" Using Canva stock elements in a logo, and especially in anything to be trademarked, runs into the licence. If you take that work, build it from original shapes and type, not from stock elements.</li>
    <li><strong>Reselling designs as templates or stock.</strong> Packaging Canva templates and selling them as your own template pack is a distinct prohibited use, not a grey area.</li>
    <li><strong>Redistributing elements on their own.</strong> Supplying a client with Canva's stock images or graphics as loose files, rather than as part of a finished design, is not what the licence covers.</li>
    <li><strong>Merchandise for resale.</strong> Print-on-demand products carry their own restrictions and limits.</li>
</ul>

<p>There is also a practical trap with paid elements. If a design uses premium content and the subscription funding it lapses, you cannot assume the finished file stays free to use. Export and hand over final files while the plan is active, and keep a record of what was used.</p>

<p><strong>The safe habit:</strong> read Canva's licence once, keep client work to finished social designs, and if a client asks for a logo, merchandise or anything resold, say you will build it from original elements or decline. Ten minutes of reading protects you from the one mistake in this field that has a bill attached.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/canva-social-media-jobs-portfolio.jpg" alt="A set of finished social media designs laid out as a portfolio" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">A small portfolio of consistent work beats a large one of varied work.</figcaption>
</figure>

<h2>What the Work Pays</h2>

<p>Pay in this category has an unusually wide spread, because the same job title covers a five-dollar test project and a salaried marketing role. Treat the two ends as different jobs.</p>

<p><strong>Freelance project work</strong> starts very low. Entry-level Canva projects on the freelance platforms are frequently posted as small fixed-price briefs, sometimes only a few dollars, explicitly framed as a trial that may lead to ongoing work. Weekly retainers for a set number of posts are the next rung up. These are individual postings, not a market rate, and you should read them as the client testing you rather than as what the work is worth.</p>

<p><strong>Salaried Pakistani roles</strong> are the steadier end. Listings for remote social media specialists and content creators that name Canva among the duties have advertised in the range of roughly PKR 30,000 to 50,000 a month. As with every figure in this category, check the posting date before you use it to negotiate.</p>

<p>Two things move you up from the first tier to the second. Taking on the <em>calendar</em> rather than individual graphics, and being able to write the caption as well as make the image. Pure template-filling is the most replaceable version of this job and is priced accordingly.</p>

<h2>Building a Portfolio That Gets Replies</h2>

<p>Nobody hires from a CV here. They hire from work. Build this before you apply to anything:</p>

<ol>
    <li><strong>Invent two brands</strong> and give each a short brand sheet: two fonts, four colours, a logo mark, and a one-line description of its tone.</li>
    <li><strong>Produce a consistent set for each:</strong> three feed posts, one five-slide carousel, two stories and one promotional graphic.</li>
    <li><strong>Show one design adapted across formats</strong> &mdash; the same message as a square post, a story and a LinkedIn graphic. This is the single most requested skill and the easiest to demonstrate.</li>
    <li><strong>Include a before and after.</strong> Take a plain paragraph of raw information and show the finished post it became. That proves you can work from a brief, which is what clients actually need.</li>
    <li><strong>Add a one-week content calendar</strong> showing what posts when and on which platform.</li>
</ol>

<p>Use invented brands, or real work you have written permission to show. Do not put a client's unpublished material in a public portfolio.</p>

<p>Most clients ask for three to five examples. Send your strongest five, in one link, in the same style &mdash; consistency is the thing being assessed.</p>

<h2>What to Learn, in Order</h2>

<ul>
    <li><strong>Canva itself,</strong> properly: templates, text hierarchy, alignment, image handling and export settings. Making something that looks deliberate rather than assembled is the whole job.</li>
    <li><strong>Platform formats</strong> and what actually differs between them &mdash; dimensions, where text gets cropped, and what a safe margin is.</li>
    <li><strong>Caption writing.</strong> The assistants who can write move up fastest, because the client stops needing a second person.</li>
    <li><strong>One scheduling workflow,</strong> whichever the client uses.</li>
    <li><strong>Short-form video basics</strong> last. It is a genuine differentiator, but only once the static work is reliable.</li>
</ul>

<h2>Where This Leads</h2>

<p>The usual progression runs Canva assistant, then social media assistant, then coordinator, then manager. The step that actually changes your income is moving from producing assets to owning the calendar &mdash; deciding what goes out and when, not just making it.</p>

<p>The other route is specialising by industry rather than by seniority. An assistant who understands e-commerce, property or personal-brand content can charge more than a generalist at the same skill level, because the client spends less time explaining.</p>

<h2>How to Apply</h2>

<p><strong>Start here:</strong> Pakistani social media listings that publish a salary band are concentrated on <a href="https://www.mustakbil.com/" rel="nofollow noopener" target="_blank">https://www.mustakbil.com/</a>. Search it alongside the freelance platforms, and use it to calibrate what to ask for before you negotiate anywhere that hides pay.</p>

<ol>
    <li><strong>Search several titles:</strong> Canva Assistant, Social Media Assistant, Social Media Designer, Content Creator, Social Media Coordinator.</li>
    <li><strong>Lead with the portfolio link,</strong> not with a description of yourself.</li>
    <li><strong>Answer the brief specifically.</strong> If the listing names a platform or an industry, show the closest thing you have made to it.</li>
    <li><strong>Ask the Canva seat question</strong> before agreeing a rate.</li>
    <li><strong>Agree what a "post" means.</strong> A carousel is five to ten designs. Being paid per post for carousel work is the most common way people in this job end up underpaid.</li>
</ol>

<h2>Before You Accept: A Checklist</h2>

<ul>
    <li>Who supplies the Canva plan, and which features does the work need?</li>
    <li>Is a carousel priced as one deliverable or per slide?</li>
    <li>Does the client supply copy, or are you writing it too?</li>
    <li>How many revisions are included?</li>
    <li>Is anything being asked for beyond social posts &mdash; a logo, merchandise, resale templates?</li>
    <li>Are you paid per deliverable or per hour, and which suits the volume?</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>What is a Canva social media job?</h3>
<p>It is production work: turning a brief and a brand into finished social posts, carousels, stories and promotional graphics, formatted correctly for each platform. The client usually supplies the direction and often the copy; you supply consistent, accurate output on schedule.</p>

<h3>Do I need a design degree?</h3>
<p>No. This part of the field hires on portfolio and reliability. A consistent set of samples built around two invented brands will do more for you than any certificate, because it shows the exact thing the client is buying.</p>

<h3>Can I do this work on the free Canva plan?</h3>
<p>Partly. Several features these jobs name &mdash; brand kits, one-click resizing, background removal and in-app scheduling &mdash; sit on a paid plan. Check Canva's current pricing page before planning a portfolio around a feature, and ask any client whether they will add you to their account rather than assuming you must buy your own.</p>

<h3>Can I use Canva templates for paid client work?</h3>
<p>For ordinary social media designs, yes. The restrictions bite on adjacent requests: using Canva stock content in a logo or trademark, reselling designs as template packs, handing over stock elements as standalone files, and merchandise for resale. Read Canva's content licence once and keep client work to finished designs.</p>

<h3>What do Canva social media jobs pay?</h3>
<p>The spread is very wide. Entry-level freelance briefs can be a few dollars as a trial, while salaried Pakistani listings naming Canva have advertised roughly PKR 30,000 to 50,000 a month. The step that raises pay is taking on the content calendar and the captions rather than only producing graphics.</p>

<h3>Is Canva enough to become a social media manager?</h3>
<p>No. Canva is the production tool. A manager role adds content strategy, scheduling, community management, analytics and campaign planning. Canva skill gets you in the door; those skills are what move you up.</p>

<h3>How many portfolio pieces do I need?</h3>
<p>Five strong, consistent examples in one link. Most clients ask for three to five. A large portfolio of varied styles is weaker than a small one that shows you can hold a brand steady across a set.</p>

<h3>Can I get this work from Pakistan?</h3>
<p>Yes. It is among the more accessible remote categories because the output is a file and the assessment is a portfolio. Check each listing's country requirement, confirm who pays for the tools, and agree the deliverable definition in writing before starting.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Canva social media jobs in Pakistan</li>
    <li>Canva assistant job description</li>
    <li>Is Canva Pro needed for client work</li>
    <li>Canva licence commercial use</li>
    <li>Social media designer salary Pakistan</li>
    <li>Instagram carousel design rates</li>
    <li>Canva portfolio examples for beginners</li>
    <li>Remote social media assistant jobs</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/social-media-manager-jobs-in-usa">Social Media Manager Jobs in USA</a> &mdash; where this skill set leads, and the platform rules that govern account growth.</li>
    <li><a href="/blog/graphic-designer-jobs-in-usa">Graphic Designer Jobs in USA</a> &mdash; the design route, and what it pays internationally.</li>
    <li><a href="/blog/wordpress-content-upload-jobs">WordPress Content Upload Jobs</a> &mdash; adjacent production work, and the trap in per-post pricing.</li>
    <li><a href="/blog/how-to-become-a-remote-virtual-assistant">How to Become a Remote Virtual Assistant</a> &mdash; the broader remote route that often includes design tasks.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic entry points and what they pay.</li>
</ul>
HTML;
    }
}
