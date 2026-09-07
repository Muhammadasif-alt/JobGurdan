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
 * "Graphic Designer Jobs in USA" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup. The draft's link carried a ?vjk= search-preview parameter, which is
 * stripped here.
 *
 * The draft's framing was the main problem. It opened and closed on demand
 * continuing to grow, which the Bureau of Labor Statistics contradicts:
 * employment of graphic designers is projected to decline 2% from 2025 to 2035,
 * and while about 16,000 openings a year are expected, all of them come from
 * replacement rather than growth. That is not a reason to avoid the field, but
 * it changes the advice, so the guide leads with it and with the BLS median of
 * $62,960 the draft never gave.
 *
 * It also points at where the growth went. BLS tracks web developers and
 * digital designers as a separate occupation, projected to grow 5% over the
 * same decade at a $92,650 median. The draft treated UI/UX as a nice-to-have
 * add-on; on the published numbers it is the difference between a shrinking
 * occupation and a growing one.
 *
 * Three further gaps. The draft compared freelance hourly rates against salary
 * bands with no adjustment for self-employment tax, Adobe's subscription, or
 * platform fees. It repeated the marketplace framing without noting Fiverr's
 * flat 20% or that Upwork's fee became a variable 0-15% on 1 May 2025. And it
 * said nothing about the copyright position on AI-generated work, which is now
 * a live professional liability for anyone delivering artwork to a client.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class GraphicDesignerJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-graphic-designer-jobs.html';

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
        $title = 'Graphic Designer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What graphic designer jobs in the USA pay against the BLS median, why the occupation is projected to shrink while still posting 16,000 openings a year, where the growth moved, and the software, platform and tax costs behind a freelance rate.',
                'content' => $content,
                'featured_image' => 'blogs/graphic-designer-jobs-in-usa.jpg',
                'tags' => 'graphic designer jobs in usa, entry level graphic design jobs, remote graphic designer jobs, freelance graphic designer usa, junior graphic designer salary, graphic design salary usa, ui ux designer jobs, adobe creative cloud designer jobs',
                'meta_title' => 'Graphic Designer Jobs in USA',
                'meta_description' => 'Graphic designer jobs in USA: the BLS pay and outlook figures, why every opening is a replacement, where the growth actually is, and what freelancing costs.',
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
            ['name' => 'US Design Studios, Agencies & In-House Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-graphic-design-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'design-creative'],
            ['name' => 'Design & Creative']
        );

        Job::updateOrCreate(
            [
                'position' => 'Graphic Designer — US Agencies, In-House Teams and Studios',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours, with core collaboration hours on distributed teams',
                'language' => 'English',
                // The band runs from under $39,520 to over $104,910 depending on
                // experience and metro, so no single range would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Graphic design roles with US agencies, in-house marketing teams, e-commerce brands and studios, on-site and remote. Apply through the employer listing.',
                'seo_keywords' => 'graphic designer jobs in usa, remote graphic designer jobs, entry level graphic design jobs, junior graphic designer, freelance graphic designer usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Agencies, in-house marketing teams, e-commerce brands, SaaS companies and studios across the United States hire graphic designers to produce brand, web, social and print assets. Because the deliverables are files, a large share of these roles are remote or offer a remote option, though many still expect overlap with US business hours.</p>

<h3>What the work involves</h3>
<p>Taking a brief and turning it into finished, on-brand assets: campaign and social graphics, landing pages and email templates, packaging and print collateral, presentation and pitch decks, and brand system work such as logo suites, type scales and colour palettes. Most of the job is not the drawing &mdash; it is interpreting the brief, working inside an existing brand system, taking feedback across revision rounds, and shipping files correctly prepared for whoever uses them next.</p>

<h3>Requirements</h3>
<ul>
    <li>A portfolio of 5&ndash;10 projects that shows the thinking, not only the finished frames</li>
    <li>Adobe Creative Cloud &mdash; Photoshop, Illustrator, InDesign &mdash; and increasingly Figma, which most product and web teams now use by default</li>
    <li>Working command of typography, layout, colour and brand consistency</li>
    <li>A bachelor's degree in graphic design or a related field is the typical stated requirement, though a strong portfolio regularly outweighs it</li>
    <li>Motion graphics and UI/UX are the add-on skills that most change what you can be hired for</li>
    <li>Written communication and self-management, which carry disproportionate weight on remote teams</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Salaried roles</strong> sit against a national median of $62,960 a year as of May 2025, with the lowest tenth under $39,520 and the highest tenth above $104,910</li>
    <li><strong>Metro premiums</strong> are real in New York, Los Angeles, Seattle and the Bay Area, and are usually offset by housing costs</li>
    <li><strong>Contract and freelance work</strong> is quoted per project or per hour, and that rate has to absorb self-employment tax, software subscriptions and unpaid time between briefs</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check whether a remote role prices by your location.</strong> Some US employers hiring across regions or internationally pay a flat rate; others adjust to where you live. It changes the offer substantially and is a fair question to ask early.</p>

<p><strong>Note:</strong> salaries, remote policies and portfolio expectations are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Graphic design is one of the most accessible creative careers in the United States: the barrier is a portfolio rather than a licence, much of the work is remote, and roles exist at agencies, in-house teams, e-commerce brands and as independent practice. It is also a field where the honest numbers and the marketing copy point in different directions, and you should see both before you commit years to it. This guide covers what the work actually pays, what the official outlook says, where the growth has moved, and what a freelance rate has to cover before it becomes income.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-graphic-designer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🎨 Browse Graphic Designer Jobs in the USA &rarr;
    </a>
</div>

<h2>What Graphic Designer Jobs in USA Pay</h2>

<p>Start with the figure employers benchmark against. The Bureau of Labor Statistics puts the <strong>median annual wage for graphic designers at $62,960 as of May 2025</strong>. The <strong>lowest ten per cent earned under $39,520</strong> and the <strong>highest ten per cent above $104,910</strong>. That is the real spread of the occupation, and it is the number to negotiate against rather than a range from a salary aggregator.</p>

<p>Within that band, roughly:</p>

<ul>
    <li><strong>Entry level, under a year:</strong> commonly $40,000 to $45,000, at the bottom of the BLS distribution</li>
    <li><strong>One to four years:</strong> around $50,000 to $65,000, which is where the median sits</li>
    <li><strong>Senior and specialist:</strong> $70,000 to $95,000, with the top decile past $104,910 &mdash; usually in tech, finance or legal in-house teams rather than at agencies</li>
    <li><strong>Freelance:</strong> quoted at $30 to $100+ an hour, which is not comparable to a salary until you subtract the costs further down this page</li>
</ul>

<p>Location moves this by 15&ndash;25% in the major metros &mdash; New York, Los Angeles, Seattle, San Francisco &mdash; and housing usually takes back more than the premium adds.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/graphic-designer-jobs-in-usa-tools.jpg"
         alt="A graphic designer working in Adobe Creative Cloud on a US-based design project"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Outlook: What the Official Projection Actually Says</h2>

<p>Almost every article on this subject opens by saying demand for designers keeps climbing. The published projection says something different, and you deserve to plan against it rather than around it.</p>

<p>BLS projects employment of graphic designers to <strong>decline 2 per cent between 2025 and 2035</strong> &mdash; roughly 4,200 fewer positions, from a base of about 253,100. At the same time, it expects <strong>about 16,000 openings a year</strong> across the decade. Both are true, and the reconciliation is the important part: <strong>those openings come from replacement, not growth.</strong> They are seats vacated by people retiring or moving into other occupations, not new seats being created.</p>

<p>So the field is not disappearing &mdash; 16,000 openings a year is a real, working job market, and plenty of people will build good careers in it. But you are competing for someone's vacated chair rather than riding an expanding one, which raises the value of everything that differentiates you: a niche, a second discipline, a portfolio that shows reasoning. Generalists feel a flat market first.</p>

<h2>Where the Growth Actually Moved</h2>

<p>Here is the part worth acting on. BLS counts <strong>web developers and digital designers</strong> as a separate occupation from graphic designers, and it is projected to <strong>grow 5 per cent from 2025 to 2035</strong>, faster than the average across all occupations, with a <strong>median wage of $92,650</strong>.</p>

<p>Same broad craft, adjacent skill set, roughly $30,000 more at the median and an outlook pointing the other way. That is why "pick up some UI/UX" is not the optional extra most guides treat it as. Learning Figma properly, understanding design systems and components, grasping responsive layout and accessibility basics, and being able to hand off to developers cleanly moves you toward the growing occupation without abandoning what you already know. If you are early in your career and choosing what to learn next, the published numbers make this the highest-return decision available to you. Our <a href="/blog/web-developer-jobs-in-usa">guide to web developer jobs in the USA</a> covers that occupation in full, including what the step up actually pays.</p>

<h2>Graphic Designer Jobs for Freshers</h2>

<p>Entry-level and junior roles are genuinely available, most often with in-house marketing teams, small agencies and e-commerce brands &mdash; the places with constant asset needs and no budget for a senior hire. A bachelor's in graphic design or a related field is the typical stated requirement, but portfolios routinely beat credentials in this field, and self-initiated or mock client projects count.</p>

<ul>
    <li><strong>Build 5&ndash;10 projects and write up the reasoning.</strong> A case study that explains the brief, the constraint and why you resolved it that way beats a grid of pretty frames. Hiring managers are screening for judgement, which is the thing that cannot be generated.</li>
    <li><strong>Learn Figma alongside Adobe, not after it.</strong> Most product, web and marketing teams now work there by default.</li>
    <li><strong>Apply to junior and associate titles specifically.</strong> They are written for people without professional experience; mid-level postings will filter you out on years.</li>
    <li><strong>Take internships and contract-to-hire seriously.</strong> In a replacement-driven market, being inside the building when a seat opens matters more than it would in a growing one.</li>
</ul>

<h2>Remote Graphic Designer Jobs</h2>

<p>Design remains one of the most remote-friendly creative careers, because the deliverable is a file. E-commerce, SaaS, agencies and media hire fully remote designers regularly, usually asking for overlap on core collaboration hours rather than a specific time zone.</p>

<p>One question to ask before you get attached to an offer: <strong>does the salary depend on where you live?</strong> Some US employers hiring across regions pay a flat rate regardless of location; others index to local cost of living, which can change an offer by tens of thousands of dollars. It is a normal, professional thing to ask in a first or second conversation, and finding out at offer stage is much worse.</p>

<p>Where to look: LinkedIn with the Remote filter and a saved alert, remote-specific boards focused on creative and marketing work, and the career pages of digital-first brands, which often post design roles directly and never syndicate them.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/graphic-designer-jobs-in-usa-portfolio.jpg"
         alt="A US graphic design workspace with branding, typography and UI/UX reference material"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Freelance Work: What the Rate Has to Cover</h2>

<p>About <strong>20 per cent of graphic designers are self-employed</strong>, so this is a mainstream route rather than a fringe one. But a freelance hourly rate is not comparable to a salary, and quoting the two side by side &mdash; as most articles do &mdash; is how people end up earning less than they did on staff. Here is what comes out of the rate first.</p>

<h3>Self-employment tax</h3>
<p>As an employee, your employer pays half your Social Security and Medicare. Self-employed, you pay both halves: the <strong>federal self-employment tax rate is 15.3%</strong>, on top of income tax. You report on <strong>Schedule C</strong>, receive <strong>1099-NEC</strong> forms from clients, and if you expect to owe more than $1,000 you must make <strong>quarterly estimated payments</strong> &mdash; due 15 April, 15 June, 15 September and 15 January. Missing them is the most common and most avoidable freelance mistake.</p>

<h3>Software</h3>
<p>Adobe restructured its plans in August 2025. <strong>Creative Cloud Standard is $54.99 a month and Creative Cloud Pro $69.99</strong>, with the Photoshop single-app plan at <strong>$22.99</strong>, on annual plans billed monthly. That is roughly <strong>$660 to $840 a year</strong> before you have earned a dollar. Figma's free tier covers a solo designer's needs for a long time, which is one more reason to learn it.</p>

<h3>Platform fees</h3>
<p><strong>Fiverr takes a flat 20% of every order</strong> &mdash; no tiers, no volume discount, so a $100 gig pays $80. <strong>Upwork's freelancer service fee has not been a flat 10% since 1 May 2025</strong>; it is now a variable <strong>0% to 15% set per contract</strong> and shown before you accept, with pre-2025 contracts keeping the older tiers. Direct clients and referrals cost nothing, which is why experienced freelancers treat marketplaces as a starting position rather than a destination.</p>

<p>Add unpaid time &mdash; pitching, revisions beyond scope, invoicing, chasing payment &mdash; plus your own health insurance and unpaid time off, and a $60 hourly rate lands a good deal closer to a $60,000 salary than the arithmetic first suggests. Price accordingly, and cap revision rounds in writing.</p>

<h2>AI, Copyright, and What You Can Legally Sell a Client</h2>

<p>Generative tools have taken the bottom out of commodity design work, and pretending otherwise helps nobody. What is less widely known &mdash; and matters more to your professional standing &mdash; is the legal position on what you deliver.</p>

<p>The US Copyright Office holds that <strong>human authorship is required for copyright protection</strong>, and that output <strong>wholly generated by AI is not copyrightable</strong>. Its guidance goes further: where a work contains more than a <strong>de minimis</strong> amount of AI-generated material, an applicant registering it must <strong>disclose that and disclaim the AI-generated parts</strong>, claiming authorship only in the human contribution. Human-authored elements remain protectable where they are sufficiently distinct.</p>

<p>For a working designer that is a practical risk, not a philosophical one. A client commissioning a logo or brand system generally assumes they are buying something they can own and register. Hand over largely AI-generated artwork without saying so and you may be delivering something they cannot protect &mdash; and misstating authorship on a registration application carries its own consequences. Use the tools for ideation, iteration and grunt work, keep the human authorship substantial and documented, and tell clients what went into the file. The designers who will be fine are the ones selling judgement, brand thinking and accountability. Our <a href="/blog/ai-content-writer-jobs-in-usa">guide to AI content writer jobs in the USA</a> covers how the same pressure is reshaping paid writing work.</p>

<h2>Skills That Actually Get You Hired</h2>

<ul>
    <li><strong>Adobe Creative Suite and Figma.</strong> Photoshop, Illustrator and InDesign remain the baseline; Figma is where most web and product work now happens.</li>
    <li><strong>A portfolio organised around problems, not galleries.</strong> Range and reasoning, five to ten pieces, each with the brief and the constraint stated.</li>
    <li><strong>Typography, layout and colour.</strong> The fundamentals are what separate a designer from someone operating the software.</li>
    <li><strong>Motion graphics or UI/UX.</strong> The two highest-return additions, and on the BLS figures UI/UX is the one that changes your occupation, not just your rate.</li>
    <li><strong>Written communication.</strong> On remote and freelance work, being able to explain a decision in writing is close to half the job.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do graphic designers make in the USA?</h3>
<p>The BLS median annual wage was $62,960 as of May 2025. The lowest ten per cent earned under $39,520 and the highest ten per cent over $104,910, with metro premiums of roughly 15 to 25 per cent in New York, Los Angeles, Seattle and San Francisco.</p>

<h3>Is graphic design a growing field in the USA?</h3>
<p>No. BLS projects employment to decline about 2 per cent from 2025 to 2035. It still expects roughly 16,000 openings a year, but those come from replacing people who retire or change occupation rather than from new positions being created.</p>

<h3>Is graphic design still worth getting into?</h3>
<p>It can be, but choose deliberately. A flat occupation with 16,000 annual openings is a real job market that rewards specialisation. Web and digital design, which BLS tracks separately, is projected to grow 5 per cent over the same decade at a $92,650 median &mdash; which is why adding UI/UX skills is the highest-return move available to most designers.</p>

<h3>Can I get a graphic design job without a degree?</h3>
<p>Often, yes. A bachelor's degree is the typical stated requirement, but portfolios carry more weight in practice than in most professions. Self-initiated and mock client projects count, provided the case studies explain your reasoning.</p>

<h3>What do freelance graphic designers charge in the USA?</h3>
<p>Commonly $30 to $100+ an hour depending on niche and portfolio. That rate is not a salary equivalent: it has to absorb 15.3% self-employment tax, roughly $660 to $840 a year in Adobe subscriptions, platform fees, your own health insurance, and unpaid time between briefs.</p>

<h3>How much does Fiverr or Upwork take from a designer?</h3>
<p>Fiverr takes a flat 20% of every order, so a $100 gig pays $80. Upwork moved off its flat 10% on 1 May 2025 to a variable 0% to 15% fee set per contract and shown before you accept it.</p>

<h3>Do remote design jobs pay the same everywhere?</h3>
<p>Not necessarily. Some US employers pay a flat rate wherever you live; others adjust to your local market, which can change an offer substantially. Ask early rather than at offer stage.</p>

<h3>Can I use AI images in client work?</h3>
<p>Carefully, and transparently. The US Copyright Office requires human authorship for copyright protection, so wholly AI-generated output is not copyrightable, and registering a work with more than a de minimis amount of AI material means disclosing it and disclaiming those parts. Clients buying a logo or brand system usually assume they can own and register it, so tell them what went into the file.</p>

<h2>People Also Search For</h2>

<h3>Entry level graphic design jobs USA</h3>
<p>Most commonly with in-house marketing teams, small agencies and e-commerce brands, starting around $40,000 to $45,000 against a national median of $62,960.</p>

<h3>Remote graphic designer jobs</h3>
<p>Widely available because the deliverable is a file. Check whether the employer indexes salary to your location before you get attached to a number.</p>

<h3>Freelance graphic designer USA</h3>
<p>About 20 per cent of graphic designers are self-employed. Price for self-employment tax, software and platform fees rather than against a salary figure.</p>

<h3>Junior graphic designer salary</h3>
<p>Roughly $40,000 to $52,000 to start, at the lower end of the BLS distribution, rising quickly with one to two years of employer or client work behind you.</p>

<h3>Graphic design jobs near me</h3>
<p>Agencies, in-house marketing departments, print and packaging firms, and e-commerce brands. Major metros pay 15 to 25 per cent more and cost more to live in.</p>

<h3>UI UX designer jobs USA</h3>
<p>A separate BLS occupation, projected to grow 5 per cent to 2035 at a $92,650 median &mdash; the adjacent field graphic designers most often move into.</p>

<h3>Graphic designer jobs remote worldwide</h3>
<p>US companies do hire internationally, but pay structures vary between flat global rates and location-adjusted ones. Clarify which applies during the interview.</p>

<h3>Is graphic design being replaced by AI?</h3>
<p>Commodity asset production is under real pressure. Brand thinking, art direction and accountability for what is delivered are not, and wholly AI-generated work cannot be copyrighted in the US.</p>

<h2>More Job Guides</h2>

<p>Looking across the rest of the remote and creative market? These cover it:</p>

<ul>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a> &mdash; the adjacent occupation the projection actually favours, and what it pays.</li>
    <li><a href="/blog/ai-content-writer-jobs-in-usa">AI Content Writer Jobs in USA</a> &mdash; how the same AI pressure is reshaping paid writing, and which parts are holding.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-usa">ATS Resume Writer Jobs in USA</a> &mdash; another portfolio-led remote career, and how applicant tracking systems really screen.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; one of the largest categories of genuinely remote hiring.</li>
    <li><a href="/blog/remote-data-entry-jobs">Remote Data Entry Jobs</a> &mdash; entry-level remote work, and the scam patterns clustered around it.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Wage data, employment projections, subscription prices, platform fees and copyright guidance change &mdash; confirm the current position with the Bureau of Labor Statistics, the IRS, the US Copyright Office and the employer's own advertisement before applying or paying any fee.</p>
HTML;
    }
}
