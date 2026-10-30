<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Copywriter Jobs in Birmingham, England for Beginners"
 *
 * Checked on 22 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief cites a Birmingham recruitment firm, a Birmingham agency and a part-time SEO Content Writer post, with vacancy details. These are private-employer examples that cannot be checked against a live listing and go stale, so they are dropped. The advice to treat a general invitation as different from a vacancy is kept in our own words.
 * 2. No apply buttons or job board search links are used. The junior social media executive vacancy at a Birmingham company is dropped, and the related-roles section is rewritten as generic advice.
 * 3. The National Careers Service advertising copywriter page was read on 22 October 2026. It confirms GBP 24,000 starter and GBP 45,000 experienced, 37 to 40 hours, and the university, apprenticeship, direct application and graduate scheme routes, so these are used as the brief claimed. The page says nothing specific about Birmingham, so the guide says the figures are national.
 * 4. Added from GOV.UK pages read the same day: national minimum wage rates effective 1 April 2026 (21 and over GBP 12.71, apprentice GBP 8.00), the apprenticeship page (16 or over, a previous degree does not stop you) and the Find an apprenticeship service. The brief had no pay floor.
 * 5. Three FAQs (apprentice minimum wage, apprenticeship length, paying for courses) and eight People Also Search For entries are added to reach the required counts.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class CopywriterJobsBirminghamEnglandBeginnersBlogSeeder extends Seeder
{
    public const SLUG = 'copywriter-jobs-birmingham-england-beginners';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Copywriter Jobs in Birmingham, England for Beginners',
                'excerpt' => 'A guide to beginner copywriter jobs in Birmingham: the entry routes the National Careers Service names, apprenticeships, what to put in a portfolio, how writing tests work and what pay figures really mean.',
                'content' => $content,
                'featured_image' => 'blogs/copywriter-jobs-birmingham-england-beginners.jpg',
                'tags' => 'copywriter jobs birmingham, junior copywriter birmingham, entry level copywriting jobs, trainee copywriter west midlands, copywriting apprenticeship, copywriter portfolio, copywriter salary uk, writing test',
                'meta_title' => 'Copywriter Jobs in Birmingham for Beginners',
                'meta_description' => 'Beginner copywriter jobs in Birmingham: entry routes, apprenticeships, portfolio ideas, writing tests and pay context from official UK pages.',
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
<p><strong>Beginners can look for copywriter work in Birmingham through junior writing vacancies, agency careers pages, apprenticeships and related content roles.</strong> A relevant portfolio, clear written English and the ability to follow a brief are the usual starting points. This guide describes the routes that the National Careers Service names, not confirmed beginner vacancies, and nobody can promise you an interview or a job.</p>

<h2>What a Copywriter Actually Writes</h2>

<p>Copywriters produce words that are meant to make someone do something: headlines, product descriptions, promotional emails, advertising concepts, social media captions and web pages. The National Careers Service says an advertising copywriter will "write clear and persuasive content to appeal to the target audience", work with the creative team to understand client briefs, refine content after feedback and present ideas to clients.</p>

<p>Every piece starts with four questions: who is reading, what do they need to know, which facts are true and checkable, and what should they do next. If you can answer those before you write, you are already thinking like a copywriter.</p>

<h2>Routes Into Copywriting</h2>

<p>The National Careers Service advertising copywriter profile lists several ways in, and none of them is the only one:</p>

<ul>
<li><strong>University.</strong> A foundation degree or degree in a subject such as English, creative writing, journalism, communication studies or advertising. The profile says a foundation degree needs at least 1 A level and a degree usually 2 to 3 A levels, and that some unrelated degrees can still lead into marketing roles.</li>
<li><strong>Apprenticeship.</strong> The profile names the Junior Advertising Creative Level 3 Advanced Apprenticeship and the Advertising Creative Level 6 Apprenticeship, and says apprenticeships can take up to 2 years.</li>
<li><strong>Direct application.</strong> You can apply to agencies directly if you can show creativity, writing and business skills. No formal qualification is specified for this route.</li>
<li><strong>Graduate training scheme.</strong> Graduates can apply to a training scheme run by an advertising agency.</li>
</ul>

<p>The profile also suggests volunteering, internships and temporary jobs as ways to gain experience, and networking on LinkedIn. It recommends building a portfolio of your work, which is the thread running through the rest of this guide.</p>

<p>Read the live page before you plan anything, because entry requirements change: <a href="https://nationalcareers.service.gov.uk/job-profiles/advertising-copywriter" rel="noopener">National Careers Service: Advertising copywriter</a>.</p>

<h2>Apprenticeships in England</h2>

<p>If you are in England and not in full-time or part-time education, an apprenticeship lets you work, get paid and train towards a qualification. GOV.UK says you can start one if you are 16 or over, and that having a previous qualification, such as a degree, does not stop you applying. Use the government service <a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">Find an apprenticeship</a> to search by job or company and by city or postcode, and read the GOV.UK page on <a href="https://www.gov.uk/apply-apprenticeship" rel="noopener">how to apply for an apprenticeship</a> first.</p>

<p>Search for advertising, marketing and content titles rather than only the word copywriter. Check the level, the employer, how long the programme lasts and who the training provider is before you apply.</p>

<h2>Agency Careers Pages and Open Applications</h2>

<p>Many Birmingham agencies, in-house marketing teams and publishers describe their roles on their own careers pages. Treat these pages as places to monitor. An invitation to send a CV is not the same as a trainee copywriter vacancy, so look for a dated job description with a clear closing date, and read what the employer expects you to have already done.</p>

<p>When you contact a team without a vacancy, keep it short: name one thing you noticed about their work, attach one relevant sample and say what kind of role you are looking for. Do not mass-send the same message.</p>

<h2>Related Roles That Build the Same Skills</h2>

<p>Junior copywriter vacancies are not common, so many people start in a neighbouring role and move across. Social media coordinator, content assistant, marketing assistant, proofreader and email assistant roles all involve writing to a brief and being edited. They are related roles, not copywriter vacancies, so read the duties and be honest about how much of the job is writing.</p>

<h2>Build a Small Portfolio</h2>

<p>You do not need dozens of samples. Three well-chosen pieces are easier for a busy hiring manager to read than twenty average ones:</p>

<ul>
<li>A landing page or product page for a real or imaginary product.</li>
<li>A promotional email with a subject line and a clear call to action.</li>
<li>A small campaign with several headline options and a sentence on why you chose one.</li>
</ul>

<p>Label practice work as practice. Never present an invented brand as a client, and never copy another writer's text. Put the samples on one simple page or in one folder, and check that every link opens without asking permission.</p>

<h2>Tone of Voice and Editing</h2>

<p>Write the same message twice for two different readers, for example a first-time customer and a long-standing one. Then cut each version by a third. Ask someone to explain the message back to you in their own words; if they get it wrong, the copy is unclear, not the reader.</p>

<p>The National Careers Service lists "excellent written communication skills", a strong knowledge of English, attention to detail and the ability to work to tight deadlines among the skills for this job. Proofreading your own work out loud is a cheap way to practise all of them.</p>

<h2>If You Are Given a Writing Test</h2>

<p>Employers often set a short task, such as rewriting a webpage, writing headlines or answering a fictional brief. Before you start, clarify the audience, the word limit, the deadline and whether AI tools are allowed. If a fact is missing, state your assumption instead of inventing a statistic or a claim. Ask whether a large task is paid, and be careful with requests for lengthy unpaid work that looks like real client content.</p>

<h2>Pay and Working Conditions</h2>

<p>The National Careers Service gives advertising copywriters a national guide of £24,000 a year at the starter level and £45,000 a year at the experienced level, with typical hours of 37 to 40 a week and occasional evening work. These figures are national, not specific to Birmingham, and they are a guide, not an offer.</p>

<p>Apprentice pay has its own floor. GOV.UK lists the national minimum wage rates effective 1 April 2026 as £12.71 an hour for workers aged 21 and over and £8.00 an hour for apprentices, who get the apprentice rate if they are under 19 or in the first year of their apprenticeship. Check the <a href="https://www.gov.uk/national-minimum-wage-rates" rel="noopener">official rates table</a> because the rates change each April. For a part-time role, compare the full-time equivalent salary with the actual pay, and count the cost and time of your commute.</p>

<h2>Your CV and First Message</h2>

<p>Lead with a link to your portfolio and say which sample is closest to the role. Describe results only if you can prove them, and avoid sales claims you cannot support. A short, accurate CV with clean writing is the first sample the employer reads.</p>

<h2>Searching Across the West Midlands</h2>

<p>Decide how far you can travel, and search for the wider set of titles: content, marketing, communications, social media and copywriting. Keep a simple log with the date, role, link, what you sent and the closing date. Ask in the interview who reviews junior work and what feedback you can expect in the first months.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply without paid writing experience?</h3>
<p>Yes, for some roles. The National Careers Service says you can apply to agencies directly if you can show creativity, writing and business skills. A portfolio is the best way to show them.</p>

<h3>Is a degree essential to become a copywriter?</h3>
<p>No. The National Careers Service lists university, apprenticeship, direct application and graduate scheme routes, and a degree is only one of them.</p>

<h3>How many portfolio samples do I need?</h3>
<p>There is no official number. Three clear samples that match the roles you want are more useful than a long list of weak ones.</p>

<h3>Are content writing and copywriting the same job?</h3>
<p>They overlap but are not identical. Copywriting aims to persuade a reader to act, while content writing often informs or explains. Read each job description for the actual duties.</p>

<h3>Can beginners work remotely as copywriters?</h3>
<p>Some can, but junior roles often involve feedback and team work, so many employers ask for office days. Check the working arrangement in the posting.</p>

<h3>What is the national minimum wage for an apprentice?</h3>
<p>GOV.UK lists £8.00 an hour for apprentices from 1 April 2026, for those under 19 or in the first year of an apprenticeship. Many employers pay more, so read the advert.</p>

<h3>How long does an apprenticeship take?</h3>
<p>The National Careers Service says advertising apprenticeships can take up to 2 years. The length depends on the level and the provider.</p>

<h3>Should I pay for a copywriting course to get a job?</h3>
<p>You never need to pay anyone to be placed in a job. Free portfolio practice costs nothing, and any course should be judged on what you can produce afterwards.</p>

<h2>Bottom Line</h2>

<p>Look for a few well-matched openings rather than ten hopeful clicks. Build a short portfolio, practise answering briefs, compare related roles and apprenticeships, and read each official page before you rely on it. A guide cannot promise a vacancy or an interview, but a clear sample of your writing is the one thing you control.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://nationalcareers.service.gov.uk/job-profiles/advertising-copywriter" rel="noopener">National Careers Service: Advertising copywriter</a></li>
<li><a href="https://www.gov.uk/national-minimum-wage-rates" rel="noopener">GOV.UK: National Minimum Wage and National Living Wage rates</a></li>
<li><a href="https://www.gov.uk/apply-apprenticeship" rel="noopener">GOV.UK: Apply for an apprenticeship</a></li>
<li><a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">GOV.UK: Find an apprenticeship</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/copywriter-jobs-in-usa">Copywriter Jobs in USA</a>: how the same craft is hired in the United States.</li>
    <li><a href="/blog/marketing-jobs-in-uk">Marketing Jobs in UK</a>: related marketing routes across the country.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or career advice. Reviewed on 22 October 2026 against the pages named in Sources. It does not promise a vacancy, an interview, an income or an offer, and pay rules and postings can change.</p>
HTML;
    }
}
