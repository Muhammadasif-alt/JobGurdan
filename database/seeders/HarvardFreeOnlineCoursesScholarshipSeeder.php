<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * "Harvard University Free Online Courses 2026".
 *
 * Checked on 8 October 2026 against Harvard Online (pll.harvard.edu, the free
 * course catalogue) and the CS50 pages. Corrections to the brief:
 *
 * 1. "Free certificates after completion" is wrong for most courses. The
 *    catalogue marks courses "Free*": the learning is free to audit, but a
 *    verified certificate usually carries a fee that varies by course. CS50x is
 *    the well-known exception, with a free certificate of completion beside
 *    the paid verified one.
 *
 * 2. "Apply link" pointed at alexandragrants.com, a third-party blog. The
 *    official catalogue is on pll.harvard.edu, and that is the link used.
 *
 * 3. There is nothing to apply for. These are open online courses, so there is
 *    no deadline, no selection and no scholarship. The page says so, and the
 *    deadline field carries a "rolling" note.
 *
 * 4. The closing line "Boost Your Skills & Career with UNICEF Certification"
 *    belongs to a different post and is left out.
 *
 * 5. "Open for International Students" and "No IELTS required" are softened:
 *    the catalogue shows no geographic limit, but US sanctions rules can bar
 *    learners in some countries, and each course sets its own prerequisites.
 *
 * Two of the three posters say "Scholarships 2026", "Fully Funded Options" and
 * "Undergraduate & Graduate". Harvard's free online courses are none of those,
 * so only the poster that says "Free Online Courses" is used.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class HarvardFreeOnlineCoursesScholarshipSeeder extends Seeder
{
    public const SLUG = 'harvard-university-free-online-courses-2026';

    public const APPLY_URL = 'https://pll.harvard.edu/catalog/free';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'Harvard University Free Online Courses 2026: Subjects, Certificates and How to Enrol',
                'provider' => 'Harvard University (Harvard Online)',
                'country' => 'United States',
                'city' => 'Online',
                'study_level' => 'Short online courses',
                'funding_type' => 'Free to audit; certificates may cost',
                'award_value' => 'Free course access; a verified certificate usually has a fee',
                'deadline' => null,
                'deadline_note' => 'No deadline. Courses are online and self-paced; enrol any time',
                'excerpt' => 'Harvard Online lists over a hundred free courses in computer science, data science, business, health and humanities. Learning is free to audit, but most certificates cost money. There is no application, no scholarship and no deadline.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'Harvard Free Online Courses 2026: Subjects and Certificates',
                'meta_description' => 'Harvard free online courses: what is free, which certificates cost money, subjects, who can enrol and how to start. No application or deadline.',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => Carbon::parse('2026-10-08 06:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return strtr(<<<'HTML'
<p>Harvard University does list free online courses, and anyone can start one. But this is <strong>not a scholarship and there is nothing to apply for</strong>. The courses are open and self-paced, with no deadline and no selection. The learning is free to audit; a <strong>verified certificate usually costs money</strong>, and that is the point most social posts leave out.</p>

<div class="scholar-note scholar-note-warn">
<p><strong>Before you share the poster or the "free certificate" posts:</strong></p>
<ul>
<li><strong>"Free certificates" is only half true.</strong> Harvard Online marks its courses "Free*": the course content is free to audit, but a verified certificate normally carries a fee that differs by course. Check the price on the course page before you start.</li>
<li><strong>The exception is CS50.</strong> Harvard's CS50x offers a free certificate of completion as well as a paid verified one, and the course page explains the difference.</li>
<li><strong>This is not a scholarship.</strong> There is no application, no selection and no funding. You simply enrol.</li>
<li><strong>Use Harvard's own page.</strong> The "apply link" going around points to a third-party blog. The official free catalogue is on Harvard Online.</li>
</ul>
</div>

<h2>Harvard Free Online Courses at a Glance</h2>
<div class="scholar-table"><table>
<thead><tr><th>Item</th><th>What to know</th></tr></thead>
<tbody>
<tr><td>Where</td><td>Online, through Harvard Online (the university's professional and lifelong learning arm)</td></tr>
<tr><td>How many</td><td>Over a hundred free courses are listed in the catalogue</td></tr>
<tr><td>Subjects</td><td>Art and design, business, computer science, data science, education, health and medicine, humanities, mathematics, programming, science, social sciences and theology</td></tr>
<tr><td>Cost to learn</td><td>Free to audit</td></tr>
<tr><td>Certificate</td><td>Usually a paid verified certificate; CS50x also has a free one</td></tr>
<tr><td>Deadline</td><td>None; courses are self-paced</td></tr>
<tr><td>Application</td><td>None; you create an account and enrol</td></tr>
</tbody>
</table></div>

<h2>Who Can Take These Courses?</h2>
<p>The catalogue shows no country limit, and the courses are taught in English. In practice there are two things to check. First, each course sets its own background: some are for beginners and some assume earlier study or programming skills. Second, US sanctions rules can stop learners in some countries from using US-based learning platforms, so check the platform's terms for your country before you build a study plan around a course.</p>

<p>No IELTS or other English test is asked for when you enrol on a free course. You do need enough English to follow lectures and readings, so read the course description first.</p>

<h2>How Do I Enrol?</h2>
<ol>
<li>Open the free course catalogue on Harvard Online and filter by subject.</li>
<li>Read the course page: the length, the weekly time commitment, the prerequisites and the certificate price.</li>
<li>Create a free account and enrol in the free (audit) option.</li>
<li>Study at your own pace. Decide on the paid certificate only if you need proof of completion.</li>
</ol>

<h2>Is a Harvard Certificate Worth Paying For?</h2>
<p>It depends on what you need it for. A certificate shows that you finished a course; it is not a Harvard degree and does not make you a Harvard student. Learners often add it to a CV or a LinkedIn profile, and employers weigh it by the skills you can show. If you only want the knowledge, audit the course for free. If you need a record to show an employer or a scholarship panel, compare the certificate fee with the benefit, and remember that a free CS50 certificate may be enough for a programming CV.</p>

<h2>Frequently Asked Questions</h2>

<h3>Are Harvard's online courses really free?</h3>
<p>The learning is free to audit. Harvard Online marks these courses "Free*" because a verified certificate usually costs money.</p>

<h3>Do I get a free certificate?</h3>
<p>For most courses, no; the verified certificate has a fee that varies by course. CS50x offers a free certificate of completion as well as a paid verified one.</p>

<h3>Is this a Harvard scholarship?</h3>
<p>No. There is no application, no selection and no funding. You enrol in an open online course.</p>

<h3>Is there a deadline?</h3>
<p>No. The courses are self-paced and online, so you can start any time. Some paid or cohort programmes elsewhere on Harvard Online do have dates, so read the course page.</p>

<h3>Can international students enrol?</h3>
<p>The catalogue shows no country limit, but US sanctions rules can restrict some countries. Check the platform's terms for yours.</p>

<h3>Do I need IELTS?</h3>
<p>No English test is asked for when you enrol on a free course, but you need enough English to follow the lectures.</p>

<h3>Which subjects can I study?</h3>
<p>Art and design, business, computer science, data science, education, health and medicine, humanities, mathematics, programming, science, social sciences and theology.</p>

<h3>Will this get me into Harvard?</h3>
<p>No. Free online courses are separate from admission to Harvard, and completing one does not give you a place or a scholarship.</p>

<h2>Related Scholarship Guides</h2>
<ul>
<li><a href="/scholarships/{yale}">Yale University Scholarship</a> &mdash; need-based aid and PhD funding at another Ivy League university.</li>
<li><a href="/scholarships/{pearson}">Lester B. Pearson International Scholarship</a> &mdash; a fully funded undergraduate award in Canada.</li>
</ul>

<h2>Official Links</h2>
<ul>
<li><a href="{apply}" target="_blank" rel="noopener">Harvard Online: free courses</a></li>
<li><a href="https://cs50.harvard.edu/x/" target="_blank" rel="noopener">Harvard CS50x: Introduction to Computer Science</a></li>
</ul>

<p><em>JobGader is not part of Harvard University. This guide was checked against Harvard Online and the CS50 pages on 8 October 2026. Course lists, prices and certificate options change, so confirm them on Harvard's official pages before you enrol.</em></p>
HTML, [
            '{apply}' => self::APPLY_URL,
            '{yale}' => YaleUniversityScholarshipSeeder::SLUG,
            '{pearson}' => LesterBPearsonScholarshipSeeder::SLUG,
        ]);
    }
}
