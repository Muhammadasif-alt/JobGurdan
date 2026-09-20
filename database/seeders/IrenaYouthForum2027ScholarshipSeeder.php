<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * The IRENA Youth Forum 2027 in Abu Dhabi, written up as a plain-English
 * guide.
 *
 * Checked on 20 September 2026 against the official IRENA event page,
 * irena.org/Events/2027/Jan/IRENA-Youth-Forum-2027, which was read directly.
 * Every date, figure and contact below comes from that page. Corrections to
 * the brief and the four posters supplied with it:
 *
 * 1. This is not a scholarship. It is IRENA's flagship youth forum, held on
 *    the margins of the IRENA Assembly. Nothing here funds a degree, so the
 *    page says so rather than filing it as a university award.
 *
 * 2. All four posters print "8 Days Duration". The Forum itself is 6 and
 *    7 January, with a closing session on 8 January during the Seventeenth
 *    IRENA Assembly. The 9 to 13 January dates are youth-led activities
 *    around Abu Dhabi Sustainability Week, not eight days of forum.
 *
 * 3. Being selected does not mean being funded. IRENA funds up to
 *    40 participants; additional participants are invited on a partially
 *    funded or self-funded basis.
 *
 * 4. The funded package is travel, accommodation and visa support. No
 *    official page mentions a stipend, so none is claimed here.
 *
 * 5. Visa support is not a visa. Entry to the UAE remains the decision of
 *    the UAE authorities.
 *
 * 6. IELTS is not waived, because IRENA never asks for an English test. The
 *    requirement it does state is that applications be submitted in English.
 *
 * 7. All four posters carry a third-party site's URL and branding. That site
 *    does not organise, fund or select for this programme; IRENA does. The
 *    posters were cropped and the third-party links are not reproduced.
 *
 * updateOrCreate keeps re-runs safe; it overwrites admin edits to this row.
 */
class IrenaYouthForum2027ScholarshipSeeder extends Seeder
{
    public const SLUG = 'irena-youth-forum-2027';

    public const APPLY_URL = 'https://www.irena.org/Events/2027/Jan/IRENA-Youth-Forum-2027';

    public function run(): void
    {
        Scholarship::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'title' => 'IRENA Youth Forum 2027 in Abu Dhabi, Fully Funded',
                'provider' => 'International Renewable Energy Agency (IRENA)',
                'country' => 'United Arab Emirates',
                'city' => 'Abu Dhabi',
                'study_level' => 'Youth Forum, ages 18 to 35',
                'funding_type' => 'Fully Funded, up to 40 places',
                'award_value' => 'Travel, accommodation and visa support',
                'deadline' => '2026-09-30',
                'deadline_note' => 'Midnight Abu Dhabi local time. Results by 15 November 2026.',
                'excerpt' => 'A youth forum, not a degree scholarship. IRENA funds up to 40 places covering travel, accommodation and visa support, and the expression of interest closes at midnight Abu Dhabi time on 30 September 2026.',
                'content' => $this->guide(),
                'featured_image' => 'scholarships/'.self::SLUG.'.jpg',
                'apply_url' => self::APPLY_URL,
                'meta_title' => 'IRENA Youth Forum 2027 Abu Dhabi: Fully Funded, 30 Sept',
                'meta_description' => 'IRENA Youth Forum 2027 in Abu Dhabi: up to 40 fully funded places covering travel, accommodation and visa support. Apply by 30 September 2026.',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => Carbon::parse('2026-09-19 09:00:00'),
            ]
        );
    }

    private function guide(): string
    {
        return <<<'HTML'
<p><strong>This is a youth forum, not a university scholarship.</strong> IRENA does not fund a degree here. It funds up to 40 young people to attend its flagship youth event in Abu Dhabi alongside the Seventeenth IRENA Assembly, covering travel, accommodation and visa support.</p>

<p>If you are looking for tuition funding, this is the wrong page and the guides at the bottom will serve you better. If you work on renewable energy, climate or the energy transition and want to be in the room where international energy policy is discussed, read on. <strong>The expression of interest closes on 30 September 2026.</strong></p>

<h2>IRENA Youth Forum 2027 at a Glance</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Organiser</strong></td><td style="padding:10px;">International Renewable Energy Agency (IRENA)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Where</strong></td><td style="padding:10px;">Abu Dhabi, United Arab Emirates</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Programme dates</strong></td><td style="padding:10px;">6 to 13 January 2027</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Theme</strong></td><td style="padding:10px;">Youth Energising the Future: Grids, Storage and Flexibility for Renewable Energy Systems</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who can apply</strong></td><td style="padding:10px;">Aged 18 to 35 at the time of the Forum, from any country</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Funded places</strong></td><td style="padding:10px;"><strong>Up to 40</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>What funding covers</strong></td><td style="padding:10px;">Travel, accommodation and visa support</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Fees</strong></td><td style="padding:10px;">No application fee, no participation fee</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Deadline</strong></td><td style="padding:10px;"><strong>30 September 2026</strong>, midnight Abu Dhabi local time</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Results</strong></td><td style="padding:10px;">By 15 November 2026</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Enquiries</strong></td><td style="padding:10px;">youth@irena.org</td></tr>
    </tbody>
</table>
</div>

<h2>What the Eight Days Actually Are</h2>

<p>Posters for this forum print "8 Days Duration", which oversells it. IRENA's own programme splits the week into three quite different things:</p>

<ul>
    <li><strong>6 and 7 January: the Youth Forum itself</strong>, in Abu Dhabi, including the High-Level Opening and interactive workshops. This is the core event.</li>
    <li><strong>8 January: the closing session</strong>, held during the Seventeenth IRENA Assembly at St. Regis Saadiyat Island. This is the day you are in the room with the Assembly.</li>
    <li><strong>9 to 13 January: youth-led activities</strong>, at the Youth Booth and Lounge at St. Regis and the IRENA Pavilion at Abu Dhabi Sustainability Week (ADNEC).</li>
</ul>

<p>So the forum is two days, with a closing session on the third and an optional week of surrounding activity. That matters if you are asking an employer or university for time off.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/scholarships/irena-youth-forum-2027-forum.jpg"
         alt="IRENA Youth Forum 2027 in Abu Dhabi, fully funded, 6 to 13 January 2027"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Who Can Apply</h2>

<p>IRENA states the Forum is open to young people <strong>aged 18 to 35 at the time of the Forum</strong> who are:</p>

<ul>
    <li>Actively engaged in renewable energy, sustainability, innovation or related fields.</li>
    <li>Leading or contributing to projects, startups, research or advocacy initiatives that advance the energy transition.</li>
    <li>Motivated to collaborate with peers and partners to scale impact globally.</li>
</ul>

<p><strong>Applications are welcome from all countries</strong>, so Pakistani, Indian, Nigerian, Bangladeshi and every other nationality can apply. IRENA says selection will take into account <strong>geographic and gender balance</strong> as well as demonstrated commitment to the energy transition, which in practice means a strong application from an under-represented country is not competing only on paper quality.</p>

<p>Note the age rule carefully: it is your age <strong>at the time of the Forum</strong> in January 2027, not at the deadline.</p>

<h2>Is IELTS Required?</h2>

<p>No, and it is worth being precise about why. <strong>IRENA does not ask for IELTS, TOEFL or any English test.</strong> What it does require is that <strong>all applications be submitted in English</strong>, and the forum runs in English.</p>

<p>So do not read this as "IELTS waived". There is no English test requirement to waive. You simply need enough English to write your application and take part.</p>

<h2>What the Funding Covers, and What It Does Not</h2>

<p>IRENA's wording is that <strong>up to 40 participants will be selected for fully funded participation, covering travel, accommodation and visa support</strong>, with no application or participation fee.</p>

<p>Three honest qualifications that the promotional posters skip:</p>

<ul>
    <li><strong>Selection is not the same as funding.</strong> IRENA says additional participants will be invited on a partially funded or self-funded basis. An acceptance email is not automatically a funded place, so read it carefully before booking anything.</li>
    <li><strong>There is no stipend.</strong> No official page mentions pocket money, a living allowance or a cash award. Budget for your own incidental costs in Abu Dhabi.</li>
    <li><strong>Visa support is not a visa.</strong> IRENA can support your application; the decision on entry to the UAE belongs to the UAE authorities.</li>
</ul>

<p>IRENA also does not publish the airline, travel class, number of nights or the hotel. Wait for your own instructions rather than assuming.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/scholarships/irena-youth-forum-2027-benefits.jpg"
         alt="Fully funded benefits for the IRENA Youth Forum 2027: travel, accommodation and visa support"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>How to Apply</h2>

<p>There is one step: complete IRENA's online <strong>expression of interest</strong> before the deadline. There is no fee at any stage.</p>

<ol>
    <li><strong>Read the official event page</strong> on irena.org and check the age rule against January 2027.</li>
    <li><strong>Write down what you have actually done.</strong> This is the part that decides the outcome, and it is covered below.</li>
    <li><strong>Submit the expression of interest in English</strong> before <strong>30 September 2026, midnight Abu Dhabi local time</strong>. Abu Dhabi is UTC+4, so if you are in Pakistan the deadline is 1am on 1 October, and in India it is 1:30am.</li>
    <li><strong>Wait for results by 15 November 2026.</strong> Do not book flights before you have written confirmation of a funded place.</li>
</ol>

<p>Enquiries go to <strong>youth@irena.org</strong>. Apply through IRENA's own page rather than a third-party site.</p>

<h2>Write About What You Did, Not What You Care About</h2>

<p>IRENA's criteria reward <strong>demonstrated commitment</strong>, which is a polite way of saying that enthusiasm alone will not get you a place. Forty places against a worldwide applicant pool is a hard filter, and the thing that clears it is evidence.</p>

<p>Weak: "I am deeply passionate about climate change and the future of our planet."</p>

<p>Strong, and specific to this year's theme of grids, storage and flexibility:</p>

<ul>
    <li>A solar installation you worked on, with its size and who it served.</li>
    <li>Research you conducted, with the question and what you found.</li>
    <li>A clean-energy startup or product you built, and its stage.</li>
    <li>An energy-access project in an off-grid or under-served area.</li>
    <li>A campaign or student society you led, with what changed because of it.</li>
    <li>Work on batteries, mini-grids, metering, demand response or grid integration, which map directly onto the 2027 theme.</li>
</ul>

<p>If your work touches grids, storage or system flexibility, say so in the first two lines. That is what this edition is about.</p>

<h2>Avoiding Fake Pages and Paid "Help"</h2>

<p>This opportunity is widely reposted, and some of the material circulating carries a third-party website's branding and links. Those sites do not organise, fund or select for the Forum. <strong>IRENA does.</strong></p>

<ul>
    <li>Apply only through the official IRENA page and the expression of interest it links to.</li>
    <li><strong>Never pay anyone</strong> for an application, a "processing fee", a referral or a guaranteed place. IRENA states plainly that there is no application or participation fee.</li>
    <li>Treat any offer arriving from a free email account or a messaging app as fake until you verify it through youth@irena.org.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Is the IRENA Youth Forum a scholarship?</h3>
<p>No. It is an international youth forum held alongside the IRENA Assembly. It funds attendance, not tuition or a degree.</p>

<h3>What is the deadline?</h3>
<p>30 September 2026, midnight Abu Dhabi local time. That is 1am on 1 October in Pakistan and 1:30am in India.</p>

<h3>How many people get funded?</h3>
<p>Up to 40. Additional participants may be invited on a partially funded or self-funded basis, so being selected does not guarantee funding.</p>

<h3>What does the funding cover?</h3>
<p>Travel, accommodation and visa support. IRENA does not publish a stipend, and there is no application or participation fee.</p>

<h3>Can Pakistani applicants apply?</h3>
<p>Yes. IRENA says applications are welcome from all countries, and selection takes geographic and gender balance into account.</p>

<h3>Is IELTS required?</h3>
<p>No English test is required. IRENA requires only that applications be submitted in English.</p>

<h3>What is the age limit?</h3>
<p>18 to 35 at the time of the Forum in January 2027, not at the application deadline.</p>

<h3>Do I need to be an engineering student?</h3>
<p>No. IRENA asks for active engagement in renewable energy, sustainability, innovation or related fields, which includes researchers, entrepreneurs, advocates and policy people.</p>

<h3>When will I hear the result?</h3>
<p>By 15 November 2026. Do not book travel before you have written confirmation.</p>

<h3>What is the 2027 theme?</h3>
<p>Youth Energising the Future: Grids, Storage and Flexibility for Renewable Energy Systems.</p>

<h2>Other Fully Funded Options</h2>

<p>If you want funding for a degree rather than a conference, these guides cover it:</p>

<ul>
<li><a href="/scholarships/yale-university-scholarship">Yale University Scholarship</a> &mdash; need-based aid for international undergraduates in the United States.</li>
<li><a href="/scholarships/university-of-leeds-commonwealth-masters-scholarship">University of Leeds Commonwealth Masters Scholarship</a> &mdash; fully funded master's study in the UK.</li>
<li><a href="/scholarships/kennedy-lugar-yes-program-pakistan">Kennedy-Lugar YES Program Pakistan</a> &mdash; a funded exchange semester for school students.</li>
<li><a href="/scholarships/university-of-pavia-scholarships">University of Pavia Scholarships</a> &mdash; fee waivers and grants in Italy.</li>
</ul>

<p><em>JobGader is not part of IRENA and does not organise, fund or select for this Forum. This guide was checked against the official IRENA event page on 20 September 2026. IRENA can change dates, criteria and deadlines, so confirm everything on irena.org before you apply.</em></p>
HTML;
    }
}
