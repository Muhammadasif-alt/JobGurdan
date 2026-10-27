<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Security Guard Jobs in Dubai, UAE With No Experience"
 *
 * Checked on 27 October 2026. What changed against the brief:
 *
 * 1. The brief's seven Indeed search buttons are dropped (the site links only official or employer pages); the
 *    SIRA and G4S buttons became links in the text.
 * 2. sira.gov.ae refused the connection from our network, so the Security Guard checklist (age 21 to 60, Emirates
 *    ID, valid residency, Dubai Police good conduct certificate, Security Guard course, physical fitness, fire
 *    safety, life support and People of Determination certificates) was matched against a SIRA licensing guide
 *    found by search. The brief's "fitness certificate valid for three months" could not be confirmed and is
 *    dropped; a job advert describing the SIRA fitness test as a bleep test and strength assessment is used instead.
 * 3. G4S candidate tips fetched: "G4S provides training for many of our roles", "You do not need to have a
 *    security license up front in order to apply for security guard jobs at G4S", "G4S will never ask you for money
 *    during the interview process", communications only from a G4S email address. A G4S careers search for Dubai
 *    returned no results on 27 October 2026, which the guide says.
 * 4. u.ae preparing-to-work quote reused from the 26 October 2026 check.
 * 5. Kept distinct from the existing UAE, UK and Canada security guide by focusing on applicants without
 *    experience, and links to it.
 * 6. Four FAQs rewritten and expanded to eight; eight People Also Search For entries added.
 *
 * No Dubai security poster was supplied; the featured image was generated in the house style. The portal listing
 * is in GuideJobListingsSeeder. updateOrCreate, so re-running is safe; it overwrites admin-panel edits to this row.
 */
class SecurityGuardJobsDubaiNoExperienceBlogSeeder extends Seeder
{
    public const SLUG = 'security-guard-jobs-dubai-no-experience';

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
                'title' => 'Security Guard Jobs in Dubai, UAE With No Experience',
                'excerpt' => 'How beginners get security guard jobs in Dubai: what SIRA asks for, why employers set their own experience rules, G4S training and fraud warnings, the visit-visa ban, and how to check pay and accommodation before you accept.',
                'content' => $content,
                'featured_image' => 'blogs/security-guard-jobs-dubai-no-experience.jpg',
                'tags' => 'security guard jobs dubai no experience, dubai security guard jobs freshers, sira security guard requirements, entry level security jobs uae, sira card for beginners, security guard jobs dubai for pakistanis, g4s jobs dubai, security guard salary dubai',
                'meta_title' => 'Security Guard Jobs in Dubai With No Experience',
                'meta_description' => 'Security guard jobs in Dubai for beginners: SIRA card requirements, employer screening, G4S training, the visit-visa ban and how to check an offer.',
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
<p><strong>You can get a security guard job in Dubai without previous security work, but only with an employer that hires beginners and only after you meet the licensing conditions set by the Security Industry Regulatory Agency (SIRA).</strong> "No experience" does not mean no training, fitness test, documents or work permit, and many Dubai adverts ask for an existing SIRA card or UAE experience. This guide explains the beginner route using SIRA's requirements, G4S's candidate guidance and UAE Government rules checked on 27 October 2026. It cannot promise a vacancy: no open Dubai vacancy that accepts overseas beginners was confirmed during our checks.</p>

<h2>Understand What Beginner Security Work Involves</h2>

<p>Security guards in Dubai register visitors, control access, patrol, report incidents and watch an assigned area. The duties depend on the site: a hotel entrance, a residential tower and a warehouse each have their own procedures and language needs. Ask who supervises you and how incidents are escalated. The job rewards attention, accurate reports and following instructions; it is rarely just sitting at a desk.</p>

<h2>Separate Employer Experience Rules From SIRA Conditions</h2>

<p>SIRA's checklist for the Security Guard category lists an age of 21 to 60, an Emirates ID, valid residency, a Dubai Police good conduct certificate addressed to SIRA, and approved training. It does not ask for previous security employment, but employers can. That is why the useful search is for adverts that say "freshers", "no experience" or "training provided". Explain your real background, and ask whether the company considers people from retail, hospitality or customer service. Source: <a href="https://www.sira.gov.ae/en/services/security-cadre-card" rel="noopener">SIRA: Security Cadre Card</a>.</p>

<h2>Check the Training and Fitness Requirements</h2>

<p>The same checklist lists the Security Guard course, a physical fitness certificate, and certificates in fire safety, life support and dealing with People of Determination from approved centres. Employers describe the SIRA fitness assessment as a bleep test with strength exercises, so build basic fitness before you are called. These are licensing steps, not a job offer. Before paying for anything, ask the employer which provider it accepts, who pays, and what a failed attempt costs. Ignore anyone selling a certificate that "guarantees" a job or a visa.</p>

<h2>Understand the Employer's Role in Licensing</h2>

<p>In Dubai a licensed security company, not the individual, submits the guard's SIRA application, and the guard's residency and profession are tied to that employer. So the sequence is: selection, work permit and residency through the employer, then training and the SIRA card. Course completion alone is not permission to work. The UAE Government states: "The UAE law strictly prohibits working while holding a visit or tourist visa (whether for pay or without compensation)." Never accept a trial shift on a visit visa. Source: <a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/preparing-to-work" rel="noopener">UAE Government: Preparing to work</a>.</p>

<h2>Use Official Company Recruitment Channels</h2>

<p>G4S is one of the large security employers in the UAE. Its candidate guidance says "G4S provides training for many of our roles" and that you do not need a security licence before applying for a G4S security guard job. It also warns that G4S will never ask for money during the interview process and only writes from a G4S email address, never Gmail. A search of its careers site for Dubai returned no results on 27 October 2026, so check it regularly rather than trusting a message that claims to be from G4S. Sources: <a href="https://careers.g4s.com/en/job-seeker-tips" rel="noopener">G4S: Job seeker tips and FAQs</a> and <a href="https://careers.g4s.com/en" rel="noopener">G4S careers</a>.</p>

<h2>Build a Resume Around Transferable Skills</h2>

<p>List your education, contact details, location, languages, work history and true training status. Show reliability, customer communication, record keeping and following procedures with real examples, such as keeping a visitor register at an event or handling customer queries in a shop. Never call yourself SIRA licensed if you have only asked about a course, and say clearly if you are applying from overseas.</p>

<h2>Practise Communication and Interview Scenarios</h2>

<p>Prepare a short introduction on why the role suits you. Practise giving directions, recording a visitor's details, reporting an observation and politely explaining an access rule. For a difficult visitor, describe staying courteous, checking your instructions and calling the supervisor, and never promise action beyond your authority. When describing an incident, separate what you saw from what you assume.</p>

<h2>Compare Salary and Accommodation Together</h2>

<p>Ask for the fixed monthly salary, overtime terms, hours, weekly rest day and any conditional allowances in writing; a high advertised total may depend on extra hours. If accommodation is offered, ask about room sharing, location, utilities, transport and deductions. The UAE Labour Law sets no minimum salary, so the written package is the whole offer. Recruitment costs belong to the employer, and a worker asked to pay for a visa or a job should treat it as a warning sign.</p>

<h2>Prepare Documents Through Verified Channels</h2>

<p>Ask for the employer's checklist and separate recruitment documents from later licensing and immigration steps. Keep your passport, attested education records and genuine experience letters ready, and never invent past employment. Send sensitive documents only through a verified process, keep copies, and before travelling get written confirmation of the employer, position, location and onboarding steps. A messaging-app chat is not proof that a job or visa has been approved.</p>

<h2>Keep Applications Focused and Realistic</h2>

<p>Track each role, employer, date, experience condition and reply. Apply to adverts you genuinely fit, and use silence to improve your CV or search terms. Ask whether training is provided instead of assuming it, never pay anyone to guarantee selection, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents. For the wider licence picture across countries, see <a href="/blog/security-guard-jobs-in-the-uae-uk-and-canada">security guard jobs in the UAE, UK and Canada</a>.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can a complete beginner become a Dubai security guard?</h3>
<p>Yes, if an employer accepts beginners and you meet SIRA's training, fitness, conduct and residency conditions.</p>

<h3>Does SIRA require previous security experience?</h3>
<p>Its Security Guard checklist does not, but many employers do. Look for adverts that say training is provided.</p>

<h3>What age do I need to be?</h3>
<p>SIRA's Security Guard category lists 21 to 60. Employers can set a narrower range.</p>

<h3>Can I get a SIRA card before I have a job?</h3>
<p>No. The licensed security company applies for it after hiring you, and it is tied to that employer.</p>

<h3>Does a training certificate guarantee a job?</h3>
<p>No. Recruitment, training, licensing and the work permit are separate steps.</p>

<h3>Is accommodation always free?</h3>
<p>No. Check housing, utilities, meals, transport and deductions in the contract.</p>

<h3>Can I start working on a tourist visa?</h3>
<p>No. The UAE Government prohibits work on a visit or tourist visa, paid or unpaid.</p>

<h3>Does G4S charge recruitment fees?</h3>
<p>No. G4S says it never asks for money during the interview process and writes only from G4S email addresses.</p>

<h2>People Also Search For</h2>

<h3>Security guard jobs Dubai no experience</h3>
<p>Look for employers who train beginners and sponsor the SIRA card.</p>

<h3>Dubai security guard jobs freshers</h3>
<p>Adverts saying "training provided" are the realistic target.</p>

<h3>SIRA security guard requirements</h3>
<p>Age 21 to 60, Emirates ID, residency, good conduct certificate, training and fitness.</p>

<h3>Entry level security jobs UAE</h3>
<p>Each emirate has its own regulator; SIRA covers Dubai.</p>

<h3>SIRA card for beginners</h3>
<p>Applied for by your employer after you are hired.</p>

<h3>Security guard jobs Dubai for Pakistanis</h3>
<p>Check the employer and any agent with BEOE before paying anything.</p>

<h3>G4S jobs Dubai</h3>
<p>Search G4S careers directly; it never charges candidates.</p>

<h3>Security guard salary Dubai</h3>
<p>No legal minimum; compare the fixed pay, overtime and housing in writing.</p>

<h2>Bottom Line</h2>

<p>Look for employers who say they hire and train beginners, understand that the SIRA card comes through your employer, and build evidence of reliability and communication. Confirm the written package and a lawful work permit before you travel or start duties. This guide cannot promise a job, a licence or an income.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.sira.gov.ae/en/services/security-cadre-card" rel="noopener">SIRA: Security Cadre Card</a>, which refused our connection on 27 October 2026; requirements matched against a published SIRA licensing guide</li>
<li><a href="https://u.ae/en/information-and-services/jobs/Sector-of-employment/employment-in-the-private-sector/preparing-to-work" rel="noopener">UAE Government: Preparing to work</a></li>
<li><a href="https://careers.g4s.com/en/job-seeker-tips" rel="noopener">G4S: Job seeker tips and FAQs</a> and <a href="https://careers.g4s.com/en" rel="noopener">G4S careers</a>, checked 27 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/security-guard-jobs-in-the-uae-uk-and-canada">Security Guard Jobs in the UAE, UK and Canada</a>, the licence in each country.</li>
    <li><a href="/blog/security-guard-jobs-houston-license-training">Security Guard Jobs in Houston, Texas</a>, how the Texas licence works.</li>
    <li><a href="/blog/receptionist-jobs-dubai-requirements-interview-tips">Receptionist Jobs in Dubai</a>, another Dubai entry role.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan (2026)</a>, routes and checks before you travel.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 27 October 2026; SIRA's website did not load from our network, so confirm the requirements with SIRA or your employer. It does not promise a job, a licence or an income.</p>
HTML;
    }
}
