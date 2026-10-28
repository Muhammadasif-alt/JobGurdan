<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Prison Officer Jobs in London, England: Requirements and Selection Process"
 *
 * Checked on 28 October 2026 against the pages cited in the post. What changed against the brief:
 *
 * 1. The ten identical buttons became links in the text.
 * 2. HMPPS prison officer page fetched: starting salary for a 39-hour week including a 20% unsocial hours allowance,
 *    Inner London £43,453, Outer London £41,290 to £43,290, South and South East £38,316 to £40,316, other locations
 *    £36,816; "No qualifications needed".
 * 3. HMPPS how-to-apply fetched: at least 18 when you start; right to work in the UK and Civil Service nationality
 *    rules; Category A needs UK residence for the 3 years immediately before applying; National Insurance number;
 *    about 10 minutes to apply; 6 to 7 months on average to start; the First Time Prison Officer Recruitment Scheme for
 *    people happy to work away from home for their first 23 months.
 * 4. Assessment portal fetched: task-based assessment before 29 April 2026, values-based assessment after; online
 *    assessment centre of three assessments in about 2.5 hours, including a 10-minute role play. Medical and fitness:
 *    grip 25kg in both hands, 7kg shield at shoulder height for 1 minute, bleep test to 5.4 (35 shuttles), agility run
 *    within 34 seconds.
 * 5. Training fetched: 10-day induction at the home prison, then a 7-week foundation programme in weeks 3 to 9 at a
 *    learning centre, staying Monday to Friday if it is not commutable; full pay and expenses.
 * 6. GOV.UK HMPS recruitment fetched: security and identity checks, health screening and fitness test, 12-month
 *    probation. National Careers Service: "You do not need qualifications".
 * 7. Four FAQs rewritten and expanded to eight; eight People Also Search For entries and a note for readers in
 *    Pakistan added.
 *
 * No poster was supplied; the featured image was generated in the house style. The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class PrisonOfficerJobsLondonRequirementsSelectionBlogSeeder extends Seeder
{
    public const SLUG = 'prison-officer-jobs-london-requirements-selection';

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
                'title' => 'Prison Officer Jobs in London, England: Requirements and Selection Process',
                'excerpt' => 'How to become an HMPPS prison officer in London: eligibility, the £41,290 to £43,453 London starting pay, online tests and assessment centre, the grip, shield, bleep and agility fitness test, vetting and the 9-week training, checked October 2026.',
                'content' => $content,
                'featured_image' => 'blogs/prison-officer-jobs-london-requirements-selection.jpg',
                'tags' => 'prison officer jobs in london england, london prison officer requirements, prison officer selection process, hmpps prison officer application, prison officer fitness test, prison officer salary london, prison officer training uk, prison officer bleep test',
                'meta_title' => 'Prison Officer Jobs in London: Requirements and Selection',
                'meta_description' => 'Prison officer jobs in London: HMPPS eligibility, GBP 41,290 to 43,453 starting pay, online assessments, the fitness test, vetting and 9-week training.',
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
<p><strong>To become a prison officer in London with His Majesty's Prison and Probation Service (HMPPS), you need to be at least 18 when you start, have the right to work in the UK and meet Civil Service nationality rules, then pass online tests, an online assessment centre, a medical and fitness test, and security checks.</strong> No qualifications are required. HMPPS lists a London starting salary of £41,290 to £43,453 for a 39-hour week, and says the process takes six to seven months on average from application to first day. This guide uses the HMPPS recruitment site, the official assessment portal, GOV.UK and the National Careers Service, all checked on 28 October 2026. It covers HMPPS public prisons; privately run prisons recruit separately.</p>

<h2>Understand the Job Before Applying</h2>

<p>HMPPS says prison officers must cope with the emotional and physical demands of the job, including stressful and at times traumatic situations, and work shifts that include evenings, weekends and bank holidays. The work is about keeping people safe, supporting rehabilitation and holding professional boundaries, not confrontation. Write down why you want the role using real examples of patience, communication and following procedures, because that is what the assessments look for.</p>

<h2>Check Eligibility and Work Permission</h2>

<p>You must have the right to work in the UK and meet the Civil Service nationality rules; these are separate checks. To work in a high security (Category A) prison you must have been resident in the UK for the three years immediately before you apply, not any other three-year period. Some visible tattoos are not accepted unless they are for cultural, religious or medical reasons. Prison officer is not a visa-sponsored role, so applicants outside the UK should not pay for travel on the strength of a job-board result. Source: <a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/how-to-apply/" rel="noopener">HMPPS: How to apply</a>.</p>

<h2>Know the London Pay</h2>

<p>HMPPS publishes Band 3 prison officer starting salaries for a 39-hour week, including a 20% unsocial hours allowance:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Location</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Starting salary</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Inner London</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">£43,453</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Outer London</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">£41,290 to £43,290, including a market supplement</td></tr>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">South and South East</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">£38,316 to £40,316, including a market supplement</td></tr>
<tr><td style="padding:8px;">Other locations</td><td style="padding:8px;">£36,816</td></tr>
</tbody>
</table>

<p>Compare that with London rents and early-morning or late-night travel before you choose a prison. Source: <a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/" rel="noopener">HMPPS: Prison officer</a>.</p>

<h2>Choose a Practical London Location</h2>

<p>The HMPPS current vacancies page lists open roles by area and lets you register interest so you are told when a post opens at your preferred prison. Check each prison's address, closing date and hours, and test the commute at shift times, including weekends. If you are willing to work away from home, HMPPS also runs a First Time Prison Officer Recruitment Scheme for people happy to do so for their first 23 months. Source: <a href="https://prisonandprobationjobs.gov.uk/current-vacancies/" rel="noopener">HMPPS: Current vacancies</a>.</p>

<h2>Submit an Accurate Application</h2>

<p>The application takes about 10 minutes: eligibility questions such as nationality and right to work, then personal details. You need your National Insurance number. You can also choose to be considered for an operational support grade (OSG) role at the same time. Use a reliable email address, save every confirmation and keep names and dates consistent with your identity documents.</p>

<h2>Prepare for the Online Assessments</h2>

<p>The first stage is an online test. Applicants before 29 April 2026 took a task-based assessment; those applying after it take a values-based assessment with several question styles. If you pass, you attend an online assessment centre of three assessments in about 2.5 hours, with short breaks between them: an interview about your strengths and behaviours, a 10-minute role play with an actor playing a prisoner, and a written exercise. Practise explaining things clearly, writing an incident account in order with facts separate from assumptions, and checking simple calculations. Source: <a href="https://www.prison-officer-online-ac.co.uk/preparing" rel="noopener">Prison officer assessment: Preparing</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/prison-officer-jobs-london-requirements-selection-fitness.jpg"
         alt="Infographic of the four prison officer fitness tests: 25kg grip, 7kg shield hold for one minute, bleep test to level 5.4 and an agility run in 34 seconds"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Understand Medical and Fitness Testing</h2>

<p>A medical comes before the fitness test. The fitness test has four stages:</p>

<ul>
    <li><strong>Grip test:</strong> at least 25kg in both hands, the strength needed for control and restraint.</li>
    <li><strong>Shield hold:</strong> a 7kg shield at shoulder height for 1 minute.</li>
    <li><strong>Bleep test:</strong> a 15-metre shuttle run to level 5.4, which is 35 shuttles.</li>
    <li><strong>Agility run:</strong> a set course within 34 seconds.</li>
</ul>

<p>The portal offers a practice web app for the bleep and agility tests and a fitness guidance booklet. Declare health conditions honestly and ask about reasonable adjustments; a condition does not automatically rule you out. Source: <a href="https://www.prison-officer-online-ac.co.uk/medical-fitness" rel="noopener">Prison officer assessment: Medical and fitness</a>.</p>

<h2>Complete Checks and Foundation Training</h2>

<p>Before starting you must pass security and identity checks, and then a 12-month probation. Training starts with a 10-day induction at your home prison, followed by a 7-week foundation programme in weeks 3 to 9 at a learning centre, usually near your prison; if it is not within commuting distance you stay there Monday to Friday and go home at weekends. It covers care of people in custody, searching and security, radios and keys, de-escalation and control and restraint, and you get full pay and expenses throughout. Sources: <a href="https://www.gov.uk/government/organisations/hm-prison-service/about/recruitment" rel="noopener">GOV.UK: Working for HM Prison Service</a> and <a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/prison-officer-training/" rel="noopener">HMPPS: Prison officer training</a>.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Prison officer posts need existing UK right to work and are not on the Skilled Worker route, so no agent can sell you one from Pakistan. Read <a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a> for the UK routes that do exist.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need qualifications?</h3>
<p>No. HMPPS and the National Careers Service both say no qualifications are needed; personal qualities matter more.</p>

<h3>How much do prison officers earn in London?</h3>
<p>£43,453 in Inner London and £41,290 to £43,290 in Outer London for 39 hours, including a 20% unsocial hours allowance.</p>

<h3>How long does recruitment take?</h3>
<p>Six to seven months on average from application to first day, according to HMPPS.</p>

<h3>What is in the fitness test?</h3>
<p>A 25kg grip, a 7kg shield hold for a minute, a bleep test to level 5.4 and an agility run in 34 seconds.</p>

<h3>Is the assessment entirely physical?</h3>
<p>No. The online tests and assessment centre check judgement, communication and writing; the fitness test is a separate stage.</p>

<h3>How long is training?</h3>
<p>A 10-day induction plus 7 weeks of foundation training, on full pay.</p>

<h3>Can I apply from overseas?</h3>
<p>Only if you already have the right to work in the UK and meet Civil Service nationality rules. The role is not visa sponsored.</p>

<h3>Does passing guarantee a job?</h3>
<p>No. Appointment also depends on checks, vacancy availability and a 12-month probation.</p>

<h2>People Also Search For</h2>

<h3>Prison officer jobs in London England</h3>
<p>HMPPS posts at London prisons, listed on the current vacancies page.</p>

<h3>London prison officer requirements</h3>
<p>18 at start, right to work, Civil Service nationality rules, checks and fitness.</p>

<h3>Prison officer selection process</h3>
<p>Online test, assessment centre, medical, fitness test and vetting.</p>

<h3>HMPPS prison officer application</h3>
<p>About 10 minutes online with your National Insurance number.</p>

<h3>Prison officer fitness test</h3>
<p>Grip, shield hold, bleep test to 5.4 and a 34-second agility run.</p>

<h3>Prison officer salary London</h3>
<p>£41,290 to £43,453 for a 39-hour week.</p>

<h3>Prison officer training UK</h3>
<p>A 10-day induction and 7-week foundation programme.</p>

<h3>Prison officer bleep test</h3>
<p>Level 5.4 over 15-metre shuttles, 35 runs.</p>

<h2>Bottom Line</h2>

<p>Choose a London prison you can reach at shift times, confirm your right to work, practise for the online stages and the four fitness tests, and keep your documents ready for vetting. Follow HMPPS's instructions throughout and wait for a confirmed start date before making big commitments. This guide cannot promise selection.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/how-to-apply/" rel="noopener">HMPPS: How to apply</a></li>
<li><a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/" rel="noopener">HMPPS: Prison officer</a>, including starting salaries</li>
<li><a href="https://prisonandprobationjobs.gov.uk/current-vacancies/" rel="noopener">HMPPS: Current vacancies</a></li>
<li><a href="https://www.prison-officer-online-ac.co.uk/preparing" rel="noopener">Prison officer assessment: Preparing</a> and <a href="https://www.prison-officer-online-ac.co.uk/medical-fitness" rel="noopener">Medical and fitness</a></li>
<li><a href="https://www.gov.uk/government/organisations/hm-prison-service/about/recruitment" rel="noopener">GOV.UK: Working for HM Prison Service</a></li>
<li><a href="https://prisonandprobationjobs.gov.uk/prison-service-roles/prison-officer/prison-officer-training/" rel="noopener">HMPPS: Prison officer training</a></li>
<li><a href="https://nationalcareers.service.gov.uk/job-profiles/prison-officer" rel="noopener">National Careers Service: Prison officer</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/customs-officer-jobs-vancouver-how-to-apply">Customs Officer Jobs in Vancouver</a>, another uniformed public-service career with its own training college.</li>
    <li><a href="/blog/office-assistant-jobs-glasgow-entry-guide">Office Assistant Jobs in Glasgow</a>, another UK public-sector entry route.</li>
    <li><a href="/blog/firefighter-jobs-chicago-application-physical-test">Firefighter Jobs in Chicago</a>, how another physical test is structured.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. It does not confirm an individual London vacancy or promise selection; pay and requirements can change.</p>
HTML;
    }
}
