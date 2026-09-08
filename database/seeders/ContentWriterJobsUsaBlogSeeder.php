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
 * "Content Writer Jobs in USA" — a sector guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup.
 *
 * Two sibling guides already exist and this one has to stay off both. The
 * copywriter guide owns the BLS distribution, the 65% self-employment figure
 * and the FTC and copyright rules; the AI content writer guide owns Google's
 * position on AI drafts and why per-word pay is wrong for editing work. Both
 * are linked rather than restated.
 *
 * What is left for this page, and what no guide on the subject covers, is the
 * attribution problem: most content work is work for hire or ghostwritten, so
 * a writer can finish two years with nothing they are allowed to show — while
 * every posting screens on a portfolio.
 *
 * Corrections to the draft:
 *
 * 1. It opens on "growing demand". BLS projects employment of writers and
 *    authors to show little or no change from 2025 to 2035. Accessible and
 *    growing are not the same thing, and here the first is the reason the
 *    market is crowded.
 *
 * 2. It treats salaried roles and per-article freelance work as one market.
 *    65% of the occupation is self-employed; the $76,910 median describes a
 *    job with benefits, which is the smaller half of what the title covers.
 *
 * 3. It says to include a strong portfolio without saying that most content
 *    contracts assign the copyright and the byline to the client.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class ContentWriterJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-content-writer-jobs.html';

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
        $title = 'Content Writer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Why two very different jobs share this title, what the work pays against a $76,910 median that BLS projects flat, which niches actually pay more, and the attribution trap that leaves writers with two years of work and no portfolio.',
                'content' => $content,
                'featured_image' => 'blogs/content-writer-jobs-in-usa.jpg',
                'tags' => 'content writer jobs in usa, remote content writer jobs, seo content writer jobs, freelance content writing jobs usa, content writer salary usa, entry level writing jobs, content strategist jobs, ghostwriting jobs usa',
                'meta_title' => 'Content Writer Jobs in USA',
                'meta_description' => 'Content writer jobs in USA: the $76,910 median BLS projects flat, why two jobs share one title, which niches pay more, and the portfolio trap to avoid.',
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
            ['name' => 'US Marketing, Agency & Publisher Content Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-content-teams-aggregated']
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
                'position' => 'Content Writer — Marketing, Agency and Publisher Teams, US Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours for salaried roles; freelance contracts range from a few hours a week upward',
                'language' => 'English',
                // Writers and authors run from under $44,310 to over $139,870,
                // and 65% of the occupation is self-employed, so no single
                // salary range would describe this listing honestly.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Content writing roles with US marketing teams, agencies, publishers and brands, salaried and freelance, mostly remote. Apply through the employer listing.',
                'seo_keywords' => 'content writer jobs in usa, remote content writer jobs, seo content writer jobs, freelance content writing jobs, content writing jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Marketing teams, agencies, e-commerce brands, publishers and health and finance companies across the United States hire content writers to produce the articles, guides, product copy and email that carry their marketing. Most of this work is remote, and a large share of it is contract rather than salaried.</p>

<h3>What the work involves</h3>
<p>Researching a subject well enough to write about it credibly, working to a brief and a keyword, writing to a house style, and revising against feedback from people who are not writers. In-house roles add content calendars and coordination with design, product and SEO; freelance work adds finding the next client while delivering the current one.</p>

<h3>Requirements</h3>
<ul>
    <li>Published samples relevant to the niche &mdash; and ones you are contractually allowed to show</li>
    <li>Working SEO literacy: search intent, keyword research and on-page structure, without writing for a crawler instead of a person</li>
    <li>Research and fact-checking, which is the entire job in health, finance and legal content</li>
    <li>A content management system, and the ability to publish without breaking a template</li>
    <li>Range across formats &mdash; long-form, product copy, email and social all read differently</li>
    <li>Comfort editing AI drafts, now a stated requirement on a growing share of postings</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Salaried roles</strong> sit against the BLS writers and authors occupation: a $76,910 median as of May 2025, with the lowest tenth under $44,310 and the highest tenth above $139,870</li>
    <li><strong>Freelance work</strong> is quoted per word, per article or per hour, and none of those is salary-comparable until self-employment tax, unpaid research and gaps between contracts come out of it</li>
    <li><strong>Niche decides the rate</strong> more than experience does: regulated subjects pay multiples of general blog content because the liability and the required expertise are real</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask whether you keep the byline.</strong> Most content work is work for hire, which means the client owns the copyright and often the credit. That is normal and not sinister &mdash; but if you cannot show the work, it does nothing for your next application, and that is worth knowing before you sign rather than two years later.</p>

<p><strong>Note:</strong> pay, contract structure, attribution terms and any sponsorship decision are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Content writing is the most accessible professional writing work in the United States, and that is the single most important fact about it &mdash; not because it is good news, but because accessibility and demand are different things, and almost every guide on this subject conflates them. This one covers what the work pays, why one job title describes two quite different working lives, which niches actually move the rate, and a contractual detail that quietly costs writers years of career progress while everyone tells them to build a portfolio.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-content-writer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        ✍️ Browse Content Writer Jobs in the USA &rarr;
    </a>
</div>

<h2>Two Different Jobs Share This Title</h2>

<p>When a posting says "Content Writer" it is describing one of two working lives, and they have almost nothing in common except the writing.</p>

<ul>
    <li><strong>The salaried in-house role.</strong> One employer, a content calendar, colleagues, health cover, a 401(k), and a manager. This is the job the federal wage data describes.</li>
    <li><strong>The freelance or per-article contract.</strong> Many clients, no benefits, income that varies month to month, and time spent on invoicing and finding work that nobody pays for.</li>
</ul>

<p>The proportions are not close. <strong>65 per cent of writers and authors were self-employed in 2025.</strong> So when you search this title, the majority of what you find is not a job in the ordinary sense &mdash; it is self-employment advertised as one, and it should be evaluated as a business decision rather than an employment one.</p>

<p>Neither is better. But the "5&ndash;40 hours a week, fully flexible" postings and the salaried ones are not competing offers, and comparing their headline numbers directly will mislead you every time.</p>

<h2>What Content Writers Actually Earn</h2>

<p>The federal occupation is <strong>writers and authors</strong>. BLS puts the <strong>median annual wage at $76,910 as of May 2025</strong>, with the <strong>lowest ten per cent under $44,310</strong> and the <strong>highest ten per cent above $139,870</strong>.</p>

<p>And now the part the draft this page was built from gets wrong. It opens by describing rising demand. <strong>BLS projects employment of writers and authors to show little or no change from 2025 to 2035</strong> &mdash; flat, against a 3 per cent average across all occupations &mdash; with about <strong>11,900 openings a year</strong>, nearly all of them replacing people who leave rather than new positions.</p>

<p>This is not a reason to avoid the field. It is a reason to be precise about where you aim inside it, because a flat occupation with the lowest entry barrier of any role covered on this site is, by definition, a crowded one. The writers doing well in it are not the ones who write fastest. They are the ones who are hard to replace, and the two sections below are about how that happens.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/content-writer-jobs-in-usa-portfolio.jpg"
         alt="A content writer working on a draft at a home office desk"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Portfolio Trap Nobody Warns You About</h2>

<p>Every guide ends the same way: build a strong portfolio, tailor your samples to the niche. Good advice. What none of them mention is that <strong>most content writing is work for hire</strong>, and under a work-for-hire arrangement the client owns the copyright &mdash; and usually the byline too.</p>

<p>The consequence is specific and common. A writer spends two years producing genuinely good work for agencies and content marketplaces, applies for a better role, and discovers they have <strong>almost nothing they are allowed to show</strong>. The work exists, it is public, and it has someone else's name on it or a contract term forbidding its use as a sample.</p>

<p>Four things to do about it, in order of how much they are worth:</p>

<ul>
    <li><strong>Ask about attribution before you sign, not after.</strong> "Do I keep the byline, and may I use this as a portfolio sample?" is an ordinary question. Many clients say yes to the second even when the answer to the first is no.</li>
    <li><strong>Get portfolio permission in writing.</strong> A one-line clause allowing you to display the work as a sample costs the client nothing and is routinely granted if you ask at contract stage.</li>
    <li><strong>Keep your own copy of everything</strong> as you go, including the brief and your drafts. Even where you cannot publish it, an anonymised extract in a private portfolio you send on request is far better than nothing.</li>
    <li><strong>Deliberately keep some bylined work.</strong> Accept a slightly lower rate now and then for a credit you can show. Bylines in a recognised publication in your niche do more for the next three years of your rate than the rate difference does.</li>
</ul>

<p>Ghostwriting pays well and is completely legitimate. Just do not let it be all of your work, or you will be as invisible on paper in year five as you were in year one.</p>

<h2>The Niche Is the Pay Lever</h2>

<p>Nothing moves a content rate like the subject. General marketing blog content is the most commoditised writing there is, and it is the part of the market most exposed to being drafted by a machine. The subjects that pay multiples of it have a common feature: <strong>being wrong has consequences.</strong></p>

<ul>
    <li><strong>Health and medical.</strong> Clinical accuracy, review processes and often a requirement for a credentialled reviewer. Rates reflect the liability.</li>
    <li><strong>Finance, insurance and legal.</strong> Regulated claims, compliance review, and real cost attached to an error.</li>
    <li><strong>Technical and developer content.</strong> Priced on the scarcity of people who can both write and read code.</li>
    <li><strong>B2B and industry-specific work</strong> &mdash; manufacturing, logistics, enterprise software. Unglamorous, well paid, and short of writers who understand the sector.</li>
</ul>

<p>The move that actually raises income in this field is not writing more articles. It is picking one of these, learning it properly, and becoming the writer a client in that sector does not want to replace. Related: our <a href="/blog/copywriter-jobs-in-usa">copywriter guide</a> covers the persuasion-focused side of this work and the FTC and copyright rules freelancers get caught by.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/content-writer-jobs-in-usa-remote.jpg"
         alt="A remote content writer reviewing SEO performance for a published article"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where AI Actually Sits in This Job</h2>

<p>A growing number of postings ask for writers who can edit AI drafts, and the draft this page was built from is right that this is a real and expanding category. Two things worth being clear-eyed about.</p>

<p>First, <strong>editing is not cheaper work than writing</strong>, and it is often slower &mdash; fixing a confidently wrong paragraph takes longer than writing a correct one. Being paid per word to fix machine output is the worst pay structure in this field, and our <a href="/blog/ai-content-writer-jobs-in-usa">AI content writer guide</a> covers how to price it and where Google actually stands on AI-assisted content.</p>

<p>Second, the flat BLS projection already exists in a world with these tools in it. The realistic reading is not that writing disappears but that the commodity end compresses while judgement, subject expertise and accountability hold their value. That is the same conclusion the niche section reaches by a different route, which is usually a sign it is right.</p>

<h2>Skills That Keep Recurring in Listings</h2>

<ul>
    <li><strong>SEO fundamentals</strong> &mdash; search intent, keyword research, on-page structure &mdash; without letting the keyword write the sentence.</li>
    <li><strong>Research and synthesis</strong>, the actual differentiator once everyone can produce fluent prose on demand.</li>
    <li><strong>Fact-checking and sourcing</strong>, non-negotiable in health, finance and news-adjacent work.</li>
    <li><strong>A CMS</strong>, usually WordPress, and enough discipline to publish cleanly.</li>
    <li><strong>Format range</strong>: long-form, product copy, email and social are different crafts.</li>
    <li><strong>Editing AI output</strong>, increasingly stated outright rather than implied.</li>
    <li><strong>A showable portfolio</strong> in the relevant niche &mdash; see the attribution section above before you assume you have one.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do content writers make in the USA?</h3>
<p>BLS puts the median annual wage for writers and authors at $76,910 as of May 2025, with the lowest ten per cent under $44,310 and the highest ten per cent above $139,870. That describes salaried work; freelance income is quoted per word or per article and is not directly comparable.</p>

<h3>Is content writing a growing field?</h3>
<p>No. BLS projects employment of writers and authors to show little or no change from 2025 to 2035, with about 11,900 openings a year, mostly replacing people who leave. It is accessible rather than growing, and those are different things.</p>

<h3>Are most content writing jobs actually freelance?</h3>
<p>Largely, yes. 65 per cent of writers and authors were self-employed in 2025, so a majority of what is advertised under this title is self-employment rather than employment, and should be evaluated as a business decision.</p>

<h3>Do I keep the byline on content I write?</h3>
<p>Often not. Most content work is work for hire, which assigns the copyright and usually the credit to the client. Ask about attribution and portfolio permission at contract stage — many clients grant the second even when they cannot grant the first.</p>

<h3>Which content niches pay the most?</h3>
<p>Health and medical, finance and legal, technical and developer content, and specialised B2B. What they share is that being wrong has consequences, which is what supports the rate.</p>

<h3>Do I need a degree to be a content writer?</h3>
<p>Rarely as a hard requirement. Postings screen on samples relevant to the niche. In health and finance, a relevant credential or a named expert reviewer often matters more than a degree in writing.</p>

<h3>Will AI replace content writers?</h3>
<p>The commodity end is already compressing, and BLS's flat projection is a projection made in a world that has these tools. Research, subject expertise, fact-checking and accountability are what hold value, and editing AI drafts is now a paid category in its own right.</p>

<h3>Are content writer jobs remote?</h3>
<p>Most are, which is genuinely one of the field's advantages. It also means you are competing nationally rather than locally, which is part of why the niche matters so much.</p>

<h2>People Also Search For</h2>

<h3>Remote content writer jobs USA</h3>
<p>The default rather than the exception. It also means national competition, so a defined niche matters more than location.</p>

<h3>Freelance content writing jobs</h3>
<p>The majority of this market. Price for self-employment tax, unpaid research and gaps between contracts before comparing to a salary.</p>

<h3>SEO content writer jobs</h3>
<p>Search intent and on-page structure, not keyword density. The roles that pay well want a writer who understands search, not a keyword filler.</p>

<h3>Entry level content writer jobs</h3>
<p>The lowest barrier of any role on this site, which is exactly why it is crowded. Samples in a chosen niche beat general writing ability.</p>

<h3>Content writer salary USA</h3>
<p>$76,910 at the median for salaried work, with a $44,310 tenth percentile. Freelance rates are not comparable without deducting tax and unpaid time.</p>

<h3>Content strategist jobs</h3>
<p>The planning and coordination step above writing, and the most common in-house progression from a content writer role.</p>

<h3>Ghostwriting jobs USA</h3>
<p>Well paid and legitimate. Keep some bylined work alongside it or you will have nothing to show later.</p>

<h3>Health and medical writing jobs</h3>
<p>Among the best-paid niches, because accuracy is reviewed and errors carry consequences. Often wants a credentialled reviewer in the loop.</p>

<h2>More Job Guides</h2>

<p>Looking across the rest of the writing and digital market? These cover it:</p>

<ul>
    <li><a href="/blog/copywriter-jobs-in-usa">Copywriter Jobs in USA</a> &mdash; the persuasion side of this occupation, and the FTC and copyright rules freelancers get caught by.</li>
    <li><a href="/blog/ai-content-writer-jobs-in-usa">AI Content Writer Jobs in USA</a> &mdash; what editing work should pay, why per-word is the wrong structure, and where Google really stands.</li>
    <li><a href="/blog/ats-resume-writer-jobs-in-usa">ATS Resume Writer Jobs in USA</a> &mdash; a specialised writing niche with a defined client and a clear deliverable.</li>
    <li><a href="/blog/digital-marketing-jobs-in-usa">Digital Marketing Jobs in USA</a> &mdash; the widest door in digital work, and where content sits inside it.</li>
    <li><a href="/blog/social-media-manager-jobs-in-usa">Social Media Manager Jobs in USA</a> &mdash; the adjacent role most content writers are asked to cover as well.</li>
    <li><a href="/blog/graphic-designer-jobs-in-usa">Graphic Designer Jobs in USA</a> &mdash; the other creative half of the same marketing teams.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or careers advice. Wage data, employment projections and contract norms change, and nothing here is a substitute for reading the contract you are asked to sign. Confirm the current position with the Bureau of Labor Statistics, a qualified professional and the employer's own advertisement before applying or signing.</p>
HTML;
    }
}
