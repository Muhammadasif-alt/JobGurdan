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
 * "Copywriter Jobs in USA" — a sector guide rather than one vacancy, so the
 * apply link goes to an Indeed search and the post carries no JobPosting
 * markup. The draft's link carried a ?vjk= search-preview parameter, stripped
 * here.
 *
 * Kept deliberately distinct from the AI content writer guide, which owns the
 * AI-assisted content and "what Google says about AI content" ground. This one
 * owns persuasion and conversion work: ads, landing pages, email sequences,
 * brand voice. The two link to each other rather than competing.
 *
 * Corrections to the draft:
 *
 * 1. It opened on demand for writers never being higher and called this a
 *    fast-growing path. BLS projects writers and authors at 0% — little or no
 *    change — for 2025-35, and names AI directly: "Increasing use of artificial
 *    intelligence (AI) for writing is projected to dampen demand for these
 *    workers." The same sentence continues that these workers will still be
 *    needed for online media and advertising, which is this niche, so the
 *    guide gives both halves rather than either alone.
 *
 * 2. It contained no pay figures at all, which is a large gap in a careers
 *    article. The median is $76,910 as of May 2025.
 *
 * 3. It listed freelancing as one of five "types" of copywriter job. BLS puts
 *    65% of writers and authors in self-employment, so freelancing is the
 *    default state of this occupation, not one branch of it — which is why the
 *    business and legal sections are central here rather than a footnote.
 *
 * 4. Two legal points a freelance copywriter carries and is rarely told about:
 *    the FTC substantiation rule on objective claims, and that work made for
 *    hire needs both a statutory category and a signed agreement, failing which
 *    the writer keeps the copyright.
 *
 * SEO note: the draft repeated bolded keyword variants ("jobs remote
 * copywriter", "copywriter jobs in USA from home") and gave a section to
 * "remote copywriter jobs europe" inside a page about the USA. Those queries
 * are served from People Also Search For instead, keeping the body on one
 * topic.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CopywriterJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-copywriter-jobs.html';

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
        $title = 'Copywriter Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What copywriter jobs in the USA pay against the BLS median of $76,910, why the occupation is projected flat while advertising copy holds up, what it means that 65% of writers are self-employed, and the two legal rules freelancers get caught by.',
                'content' => $content,
                'featured_image' => 'blogs/copywriter-jobs-in-usa.jpg',
                'tags' => 'copywriter jobs in usa, remote copywriter jobs, freelance copywriter jobs, entry level copywriter jobs, copywriter salary usa, direct response copywriting, seo copywriter jobs, email copywriter jobs',
                'meta_title' => 'Copywriter Jobs in USA',
                'meta_description' => 'Copywriter jobs in USA: the BLS pay and outlook, why 65% of the field is self-employed, and the FTC and copyright rules freelance copywriters get caught by.',
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
            ['name' => 'US Agencies, Brands & DTC Companies (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-copywriting-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'writing-content'],
            ['name' => 'Writing & Content']
        );

        Job::updateOrCreate(
            [
                'position' => 'Copywriter — US Agencies, Brands and DTC Companies',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours for staff roles; freelance work follows campaign and launch deadlines',
                'language' => 'English',
                // The band runs from under $44,310 to over $139,870, and most of
                // the occupation is self-employed on project rates.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Copywriting roles with US agencies, in-house brand teams, e-commerce and DTC companies, on-site and remote. Apply through the employer listing.',
                'seo_keywords' => 'copywriter jobs in usa, remote copywriter jobs, freelance copywriter, entry level copywriter jobs, direct response copywriter',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Agencies, in-house brand teams, e-commerce and direct-to-consumer companies across the United States hire copywriters to produce the words that carry a commercial decision: landing pages, ad copy, email sequences, product descriptions and brand voice work. The deliverable is a document, so most of these roles are remote or offer a remote option.</p>

<h3>What the work involves</h3>
<p>Reading a brief and a customer, then writing to a specific action rather than to a word count. Landing pages and sales pages, paid social and search ads, email flows, product and category copy, and the brand voice guidelines that keep all of it consistent. In staff roles you will also be defending your choices in review, which is a larger part of the job than most people expect.</p>

<h3>Requirements</h3>
<ul>
    <li>Persuasive writing that survives being measured &mdash; the copy is usually attached to a conversion rate</li>
    <li>A portfolio of real samples; speculative pieces are accepted when starting out, provided they are marked as such</li>
    <li>The ability to write in someone else's voice and hold it consistently</li>
    <li>Research speed: understanding a new product or industry well enough to write about it in days</li>
    <li>SEO literacy for web and content-adjacent roles &mdash; search intent rather than keyword density</li>
    <li>A bachelor's degree is the typical stated requirement, though a portfolio routinely substitutes for it here</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Salaried roles</strong> sit against a national median of $76,910 as of May 2025 for writers and authors, with the lowest tenth under $44,310 and the highest tenth above $139,870</li>
    <li><strong>Agency versus in-house</strong>: agencies pay less at the same title and expose you to far more brands and formats per year</li>
    <li><strong>Freelance work</strong> is priced per project or per retainer, and it is the majority of this occupation rather than the exception</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Get the ownership terms in writing.</strong> For a freelance copywriter, whether the client owns the copy depends on the contract, not on having been paid. Settle it before the work starts, not at invoice time.</p>

<p><strong>Note:</strong> pay, deadlines, revision policies and contract terms are set by each employer or client &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Copywriting is one of the few careers where nobody can stop you from starting: no licence, no gatekeeper, and a portfolio you can build this week. That accessibility is real, and it is also why the field is full of guides telling you demand has never been higher. The federal numbers say something more complicated and more useful than that, and knowing the difference is what lets you pick the part of this field that is actually holding up. This guide covers what the work pays, what the outlook really is, what separates copywriting from content writing, and the two legal rules that catch freelance copywriters out.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-copywriter-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        ✍️ Browse Copywriter Jobs in the USA &rarr;
    </a>
</div>

<h2>Copywriting Is Not Content Writing</h2>

<p>Start here, because it decides what you are paid. <strong>Copywriting is writing to cause an action</strong> &mdash; a purchase, a signup, a click, a booking. It is measured: the page converted at some rate or it did not. <strong>Content writing builds awareness and trust over time</strong> &mdash; blog posts, guides, SEO articles &mdash; and is measured in traffic and rankings.</p>

<p>They pay differently because they carry different risk. A blog post that underperforms costs a slot in a calendar; a sales page that underperforms costs the campaign. Writers who describe themselves as "content and copy" generalists are usually paid content rates for both. If you can attach a number to what your words did, you are a copywriter and should be priced as one. Our <a href="/blog/ai-content-writer-jobs-in-usa">guide to AI content writer jobs in the USA</a> covers the content side, including what Google actually says about AI-assisted material.</p>

<h2>What Copywriter Jobs in USA Pay</h2>

<p>The draft version of most articles on this topic gives no figures at all, which is a strange omission in a careers guide. Here is the anchor. The Bureau of Labor Statistics tracks copywriters under <em>writers and authors</em>, and puts the <strong>median annual wage at $76,910 as of May 2025</strong>, with the <strong>lowest ten per cent under $44,310</strong> and the <strong>highest ten per cent above $139,870</strong>.</p>

<ul>
    <li><strong>Entry level and junior:</strong> commonly $45,000 to $60,000 in staff roles, at the bottom of that distribution</li>
    <li><strong>Mid-level:</strong> around $65,000 to $95,000, straddling the median</li>
    <li><strong>Senior, lead and specialist:</strong> $100,000 upward, with the top tenth past $139,870 &mdash; concentrated in direct response, SaaS, finance and health, where the copy is tied to revenue that can be counted</li>
    <li><strong>Freelance:</strong> priced per project or retainer rather than hourly, which is the right structure here and is explained below</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/copywriter-jobs-in-usa-desk.jpg"
         alt="A copywriter drafting campaign and landing page copy for a US brand"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Outlook: Flat Overall, and the Exception Matters</h2>

<p>This is the correction worth making carefully, because the honest answer is not simply "bad".</p>

<p>BLS projects employment of writers and authors at <strong>0 per cent &mdash; little or no change &mdash; from 2025 to 2035</strong>, with about <strong>11,900 openings a year</strong>. And it names the cause directly: <em>"Increasing use of artificial intelligence (AI) for writing is projected to dampen demand for these workers."</em> An article claiming demand for writers has never been higher is not describing the same occupation the federal statisticians are.</p>

<p>But read the very next sentence, because it is the one that matters to you: <em>"However, these workers will continue to be needed for online media and advertising."</em> <strong>Advertising is this niche.</strong> The part of the writing occupation BLS singles out as continuing is precisely the persuasion and conversion work described above &mdash; not general article production, which is where the AI pressure lands hardest.</p>

<p>So the strategy follows from the data rather than from optimism: the flat headline is real, the squeeze is real, and the sheltered ground inside it is copy that someone measures. Writers who sell volume are competing with a tool that produces volume for free. Writers who can say "this page lifted signups by nine per cent" are selling something a tool cannot claim.</p>

<h2>Most of This Occupation Is Self-Employed</h2>

<p>Here is the structural fact that reframes everything and that most guides bury under a subheading: <strong>65 per cent of writers and authors are self-employed</strong>. Freelancing is not one of five "types" of copywriter job. It is the default condition of the field, and roughly two thirds of the people doing this work are running a business whether or not they think of it that way.</p>

<p>Which means the business side is not optional reading. You owe the <strong>federal self-employment tax of 15.3%</strong> on top of income tax, file on <strong>Schedule C</strong>, receive <strong>1099-NEC</strong> forms from clients, and must make <strong>quarterly estimated payments</strong> &mdash; due 15 April, 15 June, 15 September and 15 January &mdash; once you expect to owe more than $1,000.</p>

<h3>Price per project, not per word</h3>
<p>Per-word pricing is the single most damaging habit in this field. A sales page that converts might be 400 words that took three days of research; an article that does nothing might be 2,000 words written in an afternoon. Per-word pricing pays you more for the second, which is exactly backwards, and it puts you in direct price competition with generative tools whose marginal cost is zero. Price the outcome and the research, cap revision rounds in writing, and charge for the strategy call.</p>

<h2>Two Legal Rules Freelance Copywriters Get Caught By</h2>

<p>Both of these are ordinary parts of the job that almost no copywriting course mentions, and knowing them is a genuine selling point with serious clients.</p>

<h3>You will be asked to write claims the client cannot support</h3>
<p>Under the FTC's advertising substantiation policy, an advertiser must have a <strong>reasonable basis for an objective claim before the advertisement runs</strong>. Making a claim without that substantiation in hand is a violation of <strong>Section 5 of the FTC Act</strong>, and the substantiation has to keep supporting the claim for as long as it is used.</p>
<p>In practice a client will ask you to write "clinically proven", "doubles your revenue", or a specific percentage, and will have nothing behind it. The professional move is to ask what the evidence is before you write the line, and to offer honest alternatives when there is none. You are not the advertiser and the liability is primarily theirs, but you are the person putting the words on the page, and a copywriter who raises this is worth more to a serious client than one who does not.</p>

<h3>Getting paid does not transfer the copyright</h3>
<p>This surprises almost everyone. Under US copyright law a <strong>work made for hire</strong> by an independent contractor requires <strong>both</strong> that the work falls within one of nine statutory categories <strong>and</strong> that there is a <strong>signed written agreement</strong> saying it is a work made for hire. If either element is missing, <strong>the copyright stays with the writer</strong>, whatever the invoice says &mdash; and ownership can then only move by a written assignment.</p>
<p>Two consequences. If you are the client's writer, they may not own what they paid for, which is a real problem for them and something you can solve by using a clear agreement. And if you are the freelancer, your rights are worth something: retaining the ability to show work in your portfolio, or licensing rather than assigning, is negotiable. Settle it in writing before the work starts.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/copywriter-jobs-in-usa-remote.jpg"
         alt="A remote copywriter working on email and landing page copy for US clients"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Remote and Entry-Level Copywriter Jobs</h2>

<p>Copywriting is about as portable as work gets, and remote roles are the norm rather than the exception across agencies, e-commerce brands and SaaS companies. As with any remote role, settle two things early: the <strong>overlap hours</strong> expected, and whether <strong>pay is indexed to your location</strong>.</p>

<p>For a first role, the useful moves are narrow and specific:</p>

<ul>
    <li><strong>Write three to five speculative pieces for real companies</strong> &mdash; a landing page, an email sequence, a set of ads &mdash; and label them as speculative. Include a short note on who the reader is and what the piece is trying to make them do. That note is what gets you hired; it shows you think about the reader rather than the sentence.</li>
    <li><strong>Pick a niche early.</strong> SaaS, health and wellness, finance, e-commerce, real estate. Specialists are hired faster and paid more because the research cost falls with every project.</li>
    <li><strong>Consider an agency first.</strong> Lower pay, faster learning, and a volume of brands and formats you cannot get in-house.</li>
    <li><strong>Pitch small businesses directly.</strong> Most have nobody writing their copy and are not posting jobs. This is the least contested route into paid work.</li>
    <li><strong>Learn one measurement tool.</strong> Being able to read what your copy did in GA4 puts you in a different category of writer entirely.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do copywriters make in the USA?</h3>
<p>BLS puts writers and authors, which covers copywriters, at a median of $76,910 as of May 2025. The lowest ten per cent earned under $44,310 and the highest ten per cent over $139,870, with the upper end concentrated in direct response and in industries where copy is tied to countable revenue.</p>

<h3>Is copywriting still a good career with AI?</h3>
<p>It depends which half you are in. BLS projects the writing occupation flat at 0 per cent to 2035 and names AI as dampening demand, but says in the same breath that writers will still be needed for online media and advertising. Volume writing is exposed; measured conversion copy is the sheltered part.</p>

<h3>What is the difference between a copywriter and a content writer?</h3>
<p>Copywriting drives an action and is measured by conversion. Content writing builds awareness and is measured by traffic and rankings. Copy is paid more because it carries more commercial risk, so it is worth being clear about which one you are selling.</p>

<h3>Do I need a degree to become a copywriter?</h3>
<p>No. A bachelor's is the typical stated requirement, but a portfolio substitutes for it more readily in copywriting than in almost any comparable field. Speculative samples count if you label them honestly.</p>

<h3>Should I charge per word or per project?</h3>
<p>Per project. Per-word pricing rewards length rather than effect, undervalues short high-stakes work like a sales page, and puts you in direct price competition with tools whose output costs nothing. Price the outcome, the research and a capped number of revisions.</p>

<h3>Is freelance copywriting common or is it a fallback?</h3>
<p>It is the norm. BLS reports 65 per cent of writers and authors are self-employed, so budget for 15.3% self-employment tax, Schedule C and quarterly estimated payments from your first client rather than your first busy year.</p>

<h3>Who owns the copy after the client pays for it?</h3>
<p>Not automatically the client. A work made for hire by a contractor needs both a qualifying statutory category and a signed written agreement; without both, the copyright stays with the writer and only a written assignment transfers it. Agree the terms before starting.</p>

<h3>What if a client asks me to write a claim they cannot prove?</h3>
<p>Ask what the evidence is first. The FTC requires an advertiser to hold a reasonable basis for an objective claim before the advertisement runs, and lacking one breaches Section 5 of the FTC Act. Offering an honest alternative is part of the job, not an obstacle to it.</p>

<h2>People Also Search For</h2>

<h3>Remote copywriter jobs</h3>
<p>The default rather than the exception across agencies, e-commerce and SaaS. Confirm overlap hours and whether pay is adjusted to your location.</p>

<h3>Freelance copywriter jobs USA</h3>
<p>Where most of this occupation actually sits, with 65 per cent self-employed. Price per project, and settle copyright ownership in writing before you start.</p>

<h3>Entry level copywriter jobs</h3>
<p>Commonly $45,000 to $60,000 in staff roles. Speculative samples with a short note on the reader and the intended action are what get juniors hired.</p>

<h3>Copywriter jobs near me</h3>
<p>Local agencies, in-house marketing departments and hybrid roles. Direct pitching to small businesses is the least contested route to first paid work.</p>

<h3>Remote copywriter jobs worldwide</h3>
<p>US companies do hire across borders, but pay may be flat or indexed to your location, and payment routes and tax treatment differ outside the US.</p>

<h3>Direct response copywriter</h3>
<p>The specialism where copy is tied directly to measurable revenue, and where the top of the pay distribution sits.</p>

<h3>SEO copywriter jobs</h3>
<p>The overlap between copy and content. Search intent matters far more than keyword density, and the role often reports into marketing rather than creative.</p>

<h3>Email copywriter jobs</h3>
<p>Lifecycle and retention writing, one of the most reliably measured formats and a strong niche for freelancers building recurring retainers.</p>

<h2>More Job Guides</h2>

<p>Comparing this against the other writing and digital paths? These cover them:</p>

<ul>
    <li><a href="/blog/ai-content-writer-jobs-in-usa">AI Content Writer Jobs in USA</a> &mdash; the content side of the same market, and what Google actually says about AI-assisted writing.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the discipline copy sits inside, and the one with roughly seven times the annual openings.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-usa">ATS Resume Writer Jobs in USA</a> &mdash; another writing niche with steadier, year-round demand.</li>
    <li><a href="/blog/graphic-designer-jobs-in-usa">Graphic Designer Jobs in USA</a> &mdash; the visual half of the same campaigns, facing the same pressure.</li>
    <li><a href="/blog/content-writer-jobs-in-usa">Content Writer Jobs in USA</a> &mdash; the content side of the same occupation, and the attribution trap that leaves writers with no portfolio.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Wage data, employment projections, advertising rules and copyright law change, and contract terms vary &mdash; confirm the current position with the Bureau of Labor Statistics, the IRS, the FTC, a qualified adviser and the employer's own advertisement before applying or signing anything.</p>
HTML;
    }
}
