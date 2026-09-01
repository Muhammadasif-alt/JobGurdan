<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Database\Seeder;

/**
 * Two evergreen, US-focused blog posts: salary negotiation + ATS resume tips.
 * Run once via: php artisan db:seed --class=NewBlogPostsSeeder
 * Idempotent — re-running skips posts whose slug already exists.
 */
class NewBlogPostsSeeder extends Seeder
{
    public function run(): void
    {
        $careerCat = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            ['name' => 'Career Advice', 'description' => 'Practical advice to grow your U.S. career.']
        );

        $insightsCat = BlogCatgories::firstOrCreate(
            ['slug' => 'job-search-tips'],
            ['name' => 'Job Search Tips', 'description' => 'Tactical tips to land interviews faster.']
        );

        $posts = [
            [
                'blog_catgories_id' => $careerCat->id,
                'title' => 'Salary Negotiation in 2026: The 3-Step Script That Gets U.S. Workers 15% More',
                'slug' => 'salary-negotiation-2026-3-step-script',
                'excerpt' => 'A field-tested script for negotiating your next U.S. job offer — backed by 2026 BLS data on what really moves the number.',
                'tags' => 'salary negotiation, job offer, career advice, compensation, USA jobs',
                'featured_image' => 'public/user/images/blog-compact-post-04.jpg',
                'meta_title' => 'Salary Negotiation 2026 — 3-Step Script for U.S. Workers | Jobs in USA',
                'meta_description' => 'A field-tested 3-step salary negotiation script for 2026 U.S. job offers. Real scripts, real numbers, average 15% lift.',
                'reading_time' => 7,
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subDays(2),
                'author_name' => 'Jobs in USA Editorial',
                'content' => <<<'HTML'
<p class="lead">Most U.S. workers leave between <strong>$3,000 and $14,000</strong> on the table at each job change — not because they're underqualified, but because they accept the first offer. The fix isn't bravado. It's a short, repeatable script you can run in under five minutes.</p>

<h2>Why 2026 is unusually good for negotiating</h2>
<p>Three things changed this year. Hiring budgets reset in January after the 2025 contractions, replacement-cost data is finally public for most knowledge-worker roles, and remote-eligible postings are up 9% year over year. Recruiters now expect a counter — and they have headroom to meet it.</p>
<p>Bureau of Labor Statistics wage-growth tracking shows median offer-to-accept lifts of <strong>12–18%</strong> when candidates counter once, professionally, with a single number backed by a market band.</p>

<h2>Step 1 — Anchor before the offer lands</h2>
<p>The moment you're asked your expected range — usually in the first screening call — you need a number ready. Not a range. A specific anchor with a one-line justification.</p>
<blockquote>"Based on roles I'm comparing at this level in {city}, I'm targeting <strong>$128,000</strong> base. That reflects the same scope I'm carrying today plus the equity I'd be walking away from."</blockquote>
<p>Why a specific number? Recruiter playbooks shave 8–12% off any range you give. A single anchor is harder to discount and signals you've done the work.</p>

<h2>Step 2 — When the offer arrives, slow it down</h2>
<p>Do not respond same-day. Do not say "yes" verbally even if you mean it. Say exactly this:</p>
<blockquote>"Thank you — I'm genuinely excited. I want to review the full package carefully and come back with any questions tomorrow. Can we plan a quick call for {time}?"</blockquote>
<p>You just bought 18 hours, a follow-up call, and the right to come back with one structured counter.</p>

<h2>Step 3 — Counter once, with a specific lift and a reason</h2>
<p>Open the follow-up call with appreciation, then a single sentence:</p>
<blockquote>"I've reviewed everything and I'd love to make this work at <strong>$142,000</strong> base — that brings it in line with the offer I'm weighing from a comparable role and reflects the year-1 scope we discussed. Is there room to get there?"</blockquote>
<p>Three things make this work: a specific number (not "more"), a market reason (not your bills), and a yes/no question (not a paragraph). Stop talking. The first one to fill silence loses ground.</p>

<h2>What recruiters cannot say no to</h2>
<ul>
    <li><strong>Sign-on bonus.</strong> Comes out of a different budget than base. Easier "yes."</li>
    <li><strong>Earlier first review.</strong> A 6-month review instead of 12 means your next raise comes faster.</li>
    <li><strong>Equity refresh.</strong> If base is capped, extra RSUs often aren't.</li>
    <li><strong>Remote days.</strong> Two flexible days per week is worth roughly $4,000/yr in commute and time.</li>
</ul>

<h2>The mistake that costs the most</h2>
<p>Negotiating <em>after</em> you've verbally accepted. Once "yes" is on the table, your leverage is gone. Always hold the verbal until the written offer matches the negotiated number.</p>

<h2>Two scripts to copy-paste</h2>
<p><strong>If they say "this is our best offer":</strong></p>
<blockquote>"I understand. To help me decide today — is there flexibility on a sign-on bonus or an earlier first review instead?"</blockquote>
<p><strong>If they ask what other offers you have:</strong></p>
<blockquote>"I'm in late stages with two other companies at similar scope. I'd rather not share specifics, but I want to be straightforward: I'd choose you at $142,000."</blockquote>

<h2>Bottom line</h2>
<p>You don't need to be a hard-charging negotiator. You need one anchor, one pause, and one specific counter. Run that loop and the 12–18% lift is sitting there waiting.</p>
HTML
            ],

            [
                'blog_catgories_id' => $insightsCat->id,
                'title' => 'Why Your Resume Isn\'t Getting Past ATS in 2026 (And the 5-Minute Fix)',
                'slug' => 'resume-not-passing-ats-2026-fix',
                'excerpt' => 'In 2026, 91% of U.S. employers run resumes through an Applicant Tracking System first. Here is exactly what gets you filtered out — and a 5-minute checklist that gets you back in.',
                'tags' => 'resume tips, ATS, job applications, resume writing, USA jobs',
                'featured_image' => 'public/user/images/blog-compact-post-05.jpg',
                'meta_title' => 'Resume Not Passing ATS in 2026? The 5-Minute Fix | Jobs in USA',
                'meta_description' => 'Why 2026 ATS filters reject good resumes — and the exact 5-minute checklist to get your resume past the screening robots and in front of a human.',
                'reading_time' => 6,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(1),
                'author_name' => 'Jobs in USA Editorial',
                'content' => <<<'HTML'
<p class="lead">In 2026, <strong>91% of U.S. employers</strong> route every resume through an Applicant Tracking System before a human ever sees it. If your resume gets filtered out, it doesn't matter how qualified you are. Here's exactly why good resumes still fail — and the 5-minute fix that gets yours through.</p>

<h2>What an ATS actually does</h2>
<p>An ATS isn't AI. It's a structured-data parser. It reads your resume top-to-bottom, extracts what it thinks are your name, contact info, job titles, dates, and skills, then scores you against keywords pulled from the job description.</p>
<p>If the parser can't find a field — or guesses wrong — your resume scores low and gets buried. Even a single layout choice can break it.</p>

<h2>The 6 things that get you auto-filtered</h2>

<h3>1. Header in a text box, sidebar, or graphic</h3>
<p>If your name and contact details sit inside a colored sidebar or a graphic block, most ATS parsers skip them entirely. Your resume gets indexed with <em>no name</em> attached. Move all contact info to plain text at the top.</p>

<h3>2. Tables for layout</h3>
<p>A two-column layout built with a table reads as a single mangled line. Use single-column layouts. If you need visual separation, use whitespace and bold headings — not table cells.</p>

<h3>3. Icons or symbols instead of words</h3>
<p>Phone-icon or email-icon images don't get parsed as a phone number or email. Use the actual words: "Email:" "Phone:" "Location:".</p>

<h3>4. PDF that was exported as an image</h3>
<p>If you can't highlight and copy text from your PDF, neither can the ATS. Always export as "Text PDF" from Word/Google Docs — never "scan" or "image PDF" from your phone.</p>

<h3>5. Fancy fonts and graphics</h3>
<p>Stick to Arial, Calibri, Helvetica, or Georgia at 10–12pt. Decorative fonts get rendered as glyphs and dropped. No charts, no progress bars, no logos.</p>

<h3>6. Missing the exact keywords from the job posting</h3>
<p>If the job description says "project management" 4 times and your resume says "managed projects" — the ATS may not match them. Mirror the exact phrasing for your top 5 skills.</p>

<h2>The 5-minute fix checklist</h2>
<ol>
    <li><strong>Copy-paste test.</strong> Open your PDF, select all, copy, paste into Notepad. If anything is missing or garbled, the ATS sees the same garbage.</li>
    <li><strong>Name at the very top, plain text.</strong> No box, no sidebar. Just your name on line 1.</li>
    <li><strong>Standard section headings.</strong> Use "Experience," "Education," "Skills." Not "My Journey" or "What I Bring."</li>
    <li><strong>Job titles as plain text.</strong> "Senior Software Engineer | Acme Corp | Jan 2023 – Present" — not in a table cell.</li>
    <li><strong>Mirror 5 keywords.</strong> Open the job description, pick the 5 most-repeated technical terms, ensure each appears verbatim somewhere in your resume.</li>
</ol>

<h2>What still matters after you pass the bot</h2>
<p>Getting past the ATS only gets you read. To get the call:</p>
<ul>
    <li><strong>Quantify three things per role.</strong> Numbers always outperform adjectives. "Reduced incidents by 38%" beats "improved reliability."</li>
    <li><strong>Top 1/3 is everything.</strong> Recruiters spend an average of 7.4 seconds scanning before deciding. Lead with your strongest, most recent role.</li>
    <li><strong>One page if under 10 years, two if over.</strong> Three pages signals padding.</li>
</ul>

<h2>One myth to drop</h2>
<p>You do <em>not</em> need to stuff invisible white-text keywords. Modern ATS systems flag this and recruiters who see it instant-reject. Mirror keywords naturally in your bullet points instead.</p>

<h2>Bottom line</h2>
<p>The 2026 hiring funnel is a parser, then a recruiter, then a hiring manager. Pass all three by writing a plain-text-friendly resume, mirroring the job description's exact wording, and quantifying outcomes. Five minutes of cleanup is the difference between "submitted" and "interviewing."</p>
HTML
            ],
        ];

        foreach ($posts as $data) {
            $existing = Blog::where('slug', $data['slug'])->first();
            if ($existing) {
                $this->command->info("Skip: '{$data['slug']}' already exists (id {$existing->id}).");

                continue;
            }
            $blog = Blog::create($data);
            $this->command->info("Created blog #{$blog->id}: {$blog->title}");
        }
    }
}
