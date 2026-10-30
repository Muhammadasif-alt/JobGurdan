<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "What Score Do You Need on the Apprenticeship Test?"
 *
 * Checked on 16 October 2026. What changed against the brief:
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
class WhatScoreNeededApprenticeshipTestBlogSeeder extends Seeder
{
    public const SLUG = 'what-score-needed-apprenticeship-test';

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
                'title' => 'What Score Do You Need on the Apprenticeship Test?',
                'excerpt' => 'There is no single passing score for apprenticeship tests.',
                'content' => $content,
                'featured_image' => 'blogs/what-score-needed-apprenticeship-test.jpg',
                'tags' => 'apprenticeship test passing score, what score do you need apprenticeship test, apprenticeship aptitude test cutoff, ranked selection apprenticeship, neiep 70 percent, ironworkers local 721 70 percent, ua local 190 workkeys score, apprenticeship interview score',
                'meta_title' => 'What Score Do You Need on the Apprenticeship Test?',
                'meta_description' => 'What score do you need on the apprenticeship test? Real cutoffs (70%, WorkKeys 17, 35 of 45), ranked selection and how to find your rule.',
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
<p><strong>There is no single passing score for apprenticeship tests.</strong> Each trade and local sets its own cutoff, and some programmes rank applicants instead of using a strict pass/fail line. The published examples below show how far the rules differ. Your real target is whatever your programme requires, or the score that is competitive for the number of open places, so confirm it with your programme.</p>

<h2>Published Examples</h2>

<table>
<thead><tr><th>Programme</th><th>What it publishes</th></tr></thead>
<tbody>
<tr><td><a href="https://iw721.org/training/apprenticeship/ironworkers/" rel="noopener">Ironworkers Local 721</a></td><td>A minimum of 70% on both the mechanical aptitude and the math test to be placed on file for potential referral</td></tr>
<tr><td><a href="https://555.boilermaker.ca/apprenticeship/" rel="noopener">Boilermakers Local 555</a></td><td>A minimum of 70% on the assessment to continue to the interview</td></tr>
<tr><td>NEIEP elevator test (EIAT)</td><td>70% correct is the minimum passing grade; a 70% minimum interview score is needed to receive a rank on the hiring list, and the ranking is based on the interview score</td></tr>
<tr><td><a href="https://www.ua190.org/apprenticeship-faq.aspx" rel="noopener">UA Local 190</a></td><td>A cumulative WorkKeys score of 17 or higher to move on (the page also mentions 15 in a retake question, so confirm)</td></tr>
<tr><td><a href="https://www.socalsma.org/application-process" rel="noopener">Southern California sheet metal JATC</a></td><td>Pass the math test and score at least 35 of 45 points across three written tests to reach the oral interviews</td></tr>
<tr><td><a href="https://smw20training.com/how-to-apply/" rel="noopener">Sheet Metal Local 20</a></td><td>Applicants must pass all four sections of the assessment</td></tr>
<tr><td><a href="https://local150.org/asip/faq/" rel="noopener">IUOE Local 150</a></td><td>Applicants are ranked by application score, starting with the highest</td></tr>
</tbody>
</table>

<p>So 70% is common but not universal, and some programmes use a points total, a per-section pass or a ranking. Do not assume the number from a general guide, including this one.</p>

<h2>Two Selection Models</h2>

<ol>
<li><strong>Pass/fail with a cutoff.</strong> You must reach a minimum score to move forward, whatever other applicants score.</li>
<li><strong>Ranked selection.</strong> Applicants are scored and ranked, and the programme takes people from the top down until the places are filled. Here passing matters less than your rank in that cycle.</li>
</ol>

<p>Many programmes also combine the test with an interview, experience or other points, so one number rarely tells the whole story. The NEIEP example shows the combination: a 70% test score earns the interview, and the interview score decides the ranking.</p>

<h2>What Score to Aim For</h2>

<ul>
<li><strong>Fixed cutoff:</strong> find the exact number and aim comfortably above it, not exactly at it, so one bad day does not end your application.</li>
<li><strong>Ranked selection:</strong> aim as high as you can, because a pass may not be competitive if demand is high. Ask how many people usually apply per opening if the programme shares that.</li>
<li><strong>Not sure which applies:</strong> ask. It is the most useful question you can put to the programme, because it changes how you should prepare.</li>
</ul>

<h2>What Else Affects Your Chances</h2>

<ul>
<li>The number of places in that intake and the number of applicants.</li>
<li>Interview performance, which some programmes weigh heavily.</li>
<li>Experience or coursework that adds points.</li>
<li>Residency or other eligibility factors, such as Local 150's jurisdiction rule.</li>
</ul>

<h2>How to Find Your Programme's Requirement</h2>

<ol>
<li>Ask the local or training coordinator for the passing score or selection method.</li>
<li>Request the application materials in writing, since some programmes publish selection criteria in their standards.</li>
<li>Ask how earlier cycles went: how many applied and how many were accepted.</li>
<li>Check general programme information on <a href="https://www.apprenticeship.gov" rel="noopener">Apprenticeship.gov</a>.</li>
</ol>

<h2>How to Maximise Your Score</h2>

<ol>
<li>Treat every section as important, since you may not know the weighting.</li>
<li>Study to score high, not only to pass.</li>
<li>Spend the most practice time on your weakest area: <a href="/blog/apprenticeship-test-math-basics">maths</a>, reading or <a href="/blog/mechanical-reasoning-questions-explained">mechanical reasoning</a>.</li>
<li>Take timed practice once you know the real time limit, and review every mistake.</li>
</ol>

<p>If you only just pass, prepare strong interview answers, and ask whether you can retake the test for the next cycle (see <a href="/blog/can-you-retake-apprenticeship-aptitude-test">retake rules</a>). Never pay anyone for a guaranteed score or placement.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is 70% the passing score for apprenticeship tests?</h3>
<p>It is common (Local 721, Local 555 and NEIEP publish 70%) but not universal. Confirm your own programme.</p>

<h3>Does a higher score guarantee a place?</h3>
<p>No. Openings are limited and some programmes weigh interviews and experience.</p>

<h3>Can I ask what score I need?</h3>
<p>Yes, and you should. Asking directly is more reliable than assuming a number.</p>

<h3>What if I fail by a small margin?</h3>
<p>Policies differ: some allow a retest after a wait, others require a new application cycle.</p>

<h3>Does the interview matter if my score is high?</h3>
<p>Often yes. At NEIEP the interview score decides the ranking.</p>

<h3>What is a WorkKeys cumulative score?</h3>
<p>UA Local 190 requires a cumulative score of 17 or higher across its WorkKeys subjects.</p>

<h3>Do all programmes rank applicants?</h3>
<p>No. Some use a cutoff, some rank, and some combine both.</p>

<h3>Can I pay to improve my chances?</h3>
<p>No. Never pay anyone for a guaranteed score or placement.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/can-you-retake-apprenticeship-aptitude-test">Can You Retake the Apprenticeship Aptitude Test?</a>: retake rules</li>
    <li><a href="/blog/apprenticeship-test-math-basics">Apprenticeship Test Math: The Basics You Need to Know</a>: maths revision</li>
    <li><a href="/blog/mechanical-reasoning-questions-explained">Mechanical Reasoning Questions Explained</a>: levers, gears, hydraulics</li>
    <li><a href="/blog/one-week-before-apprenticeship-test-study-plan">One Week Before the Apprenticeship Test: Study Plan</a>: 7-day plan</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 16 October 2026 against official sources. Rules, fees and requirements change, so follow the official instructions for your own case.</p>
HTML;
    }
}
