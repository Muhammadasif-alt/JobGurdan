<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Work From Home Jobs in Michigan With No Experience"
 *
 * Checked on 22 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief is condensed (structure and checkable claims only). Its three sources are private employers (Morley, Concentrix, TranscribeMe); none is named, linked or quoted. Dropped: Morley remote hiring states, workspace rules and experience requirements; the Concentrix search advice; the TranscribeMe $15-$22 per audio hour rate and entrance exam claim.
 * 2. The audio hour illustration ($20 per audio hour, four hours, $5 per working hour) is kept as a labelled arithmetic example with no company named.
 * 3. Added from pages fetched on 22 October 2026: Michigan Works! (16 regional agencies, more than 90 centers, 1-800-285-9675; the page does not say services are free, so the guide does not either); BLS customer service representatives and secretaries and administrative assistants (education, training, May 2025 median pay, 2025 to 2035 outlook, page modified 27 August 2026); IRS self-employment and the $400 threshold; FTC job scam advice.
 * 4. Not verified and left out: Michigan minimum wage figures (michigan.gov and legislature.mi.gov blocked the fetch) and anything from mitalent.org (Cloudflare block).
 * 5. The BLS figures are national and say so in the text. The brief's five FAQs were rewritten and expanded to eight; eight People Also Search For entries added.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class WorkFromHomeJobsMichiganNoExperienceBlogSeeder extends Seeder
{
    public const SLUG = 'work-from-home-jobs-michigan-no-experience';

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
                'title' => 'Work From Home Jobs in Michigan With No Experience',
                'excerpt' => 'Beginner routes to work from home in Michigan: customer support, scheduling, admin and data entry, what the BLS says about training and pay, free help from Michigan Works! and how to avoid fake offers.',
                'content' => $content,
                'featured_image' => 'blogs/work-from-home-jobs-michigan-no-experience.jpg',
                'tags' => 'work from home jobs michigan no experience, entry level remote jobs michigan, remote customer service jobs michigan, no experience online jobs michigan, michigan works, remote administrative jobs, freelance self employment tax, remote job scams',
                'meta_title' => 'Work From Home Jobs in Michigan With No Experience',
                'meta_description' => 'Work from home jobs in Michigan with no experience: beginner routes, what BLS says about pay and training, free state help and how to avoid fake offers.',
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
<p><strong>You can look for work from home jobs in Michigan with no experience by targeting trainee customer support, appointment scheduling, basic administrative work and assessment-based freelance tasks.</strong> Start with posts that say they accept beginners, confirm that you are eligible to work from Michigan, and prepare a resume that shows communication and computer skills. A "remote" label alone does not mean an employer takes inexperienced applicants, so read every requirement before you apply.</p>

<h2>Start With Beginner Remote Searches</h2>

<p>Search with phrases such as "no experience", "entry level" and "training provided", combined with Michigan and remote. Results often mix in hybrid posts, commission-only sales and jobs restricted to other states, so open each post and check where you must live and whether any office attendance is needed.</p>

<p>Michigan Works! is a good official starting point. Its site says it gives job seekers access to employment, training and career resources through its American Job Centers, with 16 regional agencies operating more than 90 centers across the state. You can enter your zip code to find an office near you or call 1-800-285-9675. The page does not say which services are free, so ask when you contact an office. Official page: <a href="https://www.michiganworks.org/" rel="noopener">Michigan Works!</a>.</p>

<p>You have more transferable experience than you may think. Retail, restaurant work, volunteering, school projects and caring for family all show reliability, communication and problem solving. Say so plainly, without inventing job titles.</p>

<h2>Customer Service and Call Support</h2>

<p>Customer support is the most common beginner route. The US Bureau of Labor Statistics says the typical entry-level education for customer service representatives is a high school diploma or equivalent, with no work experience in a related occupation needed and short-term on-the-job training, typically 2 to 4 weeks. It gives a 2025 median pay of $44,770 per year ($21.53 per hour), and projects employment to decline 5% from 2025 to 2035. Those are national figures for the whole occupation, not Michigan or remote-only figures, and a falling projection is a reason to build several skills rather than one.</p>

<p>Find out whether a post is inbound support, where customers contact you, or outbound calling with sales targets. Ask about evening and weekend shifts, paid training and how performance is measured. Paid training does not remove an experience requirement, so read what the post actually asks for. Source: <a href="https://www.bls.gov/ooh/office-and-administrative-support/customer-service-representatives.htm" rel="noopener">BLS: Customer Service Representatives</a>.</p>

<h2>Home Office Requirements to Expect</h2>

<p>Employers set their own standards, so check each post. Common asks are reliable internet, a quiet workspace, a computer that meets their specification, a headset and sometimes a webcam. Ask who supplies the equipment, when it arrives and what you must own before training starts. Do not buy expensive equipment until the employer confirms the specification in writing.</p>

<h2>Administrative Assistant and Scheduling Work</h2>

<p>Remote administrative work includes managing calendars, confirming appointments, organising files and answering email. The BLS lists a high school diploma or equivalent as the typical entry-level education for secretaries and administrative assistants and says training typically lasts a few weeks. It also notes that executive secretaries and executive administrative assistants typically need several years of related experience, so beginner posts are usually for the general roles. The 2025 median pay was $48,310 per year ($23.23 per hour), and employment is projected to decline 2% from 2025 to 2035. Source: <a href="https://www.bls.gov/ooh/office-and-administrative-support/secretaries-and-administrative-assistants.htm" rel="noopener">BLS: Secretaries and Administrative Assistants</a>.</p>

<p>Build practice samples that show the skill: a weekly calendar with no clashes, a tidy contact spreadsheet, and a polite appointment confirmation email. Use made-up details and label them as practice.</p>

<h2>Data Entry and Records Support</h2>

<p>Data entry means typing information accurately into forms or spreadsheets. Practise entering a list into a spreadsheet, finding duplicates and keeping formats consistent, and measure your accuracy as well as speed.</p>

<p>Be cautious with data entry offers that promise high pay for simple typing. A real employer does not charge you to start. The FTC says paying for starter kits, so-called training or certifications that are useless is a common result of job scams, and that no honest potential employer will ever send you a check to deposit. Read the advice at <a href="https://consumer.ftc.gov/articles/job-scams" rel="noopener">FTC: Job scams</a>, and report scams at <a href="https://reportfraud.ftc.gov" rel="noopener">ReportFraud.ftc.gov</a>.</p>

<h2>Freelance Transcription Is a Separate Choice</h2>

<p>Freelance transcription is usually priced per audio hour, meaning an hour of recording, not an hour of your work. As an illustration only, a task paying $20 per audio hour that takes four hours to finish works out at $5 for each working hour. Time your own tasks before you decide whether it is worth doing. Our <a href="/blog/transcription-jobs-from-home-ohio">transcription guide</a> explains the pay unit in more detail.</p>

<p>Freelancers are self-employed. The IRS says you have to file an income tax return if your net earnings from self-employment were $400 or more, and that self-employed people generally pay estimated taxes quarterly. Source: <a href="https://www.irs.gov/businesses/small-businesses-self-employed/self-employed-individuals-tax-center" rel="noopener">IRS Self-Employed Individuals Tax Center</a>.</p>

<h2>Part-Time Remote Work</h2>

<p>Part-time does not always mean flexible. Many posts have fixed shifts, a minimum number of weekly hours or required training days. Ask for the schedule, the minimum hours, how shifts are assigned and whether you can swap them. Compare guaranteed hourly pay with commission, and get the terms in writing before you accept.</p>

<h2>Write a Resume Without Inventing Experience</h2>

<p>Put your Michigan location and availability near the top, list real skills, and add practice projects with honest labels. Measure your typing speed before you list it. Keep one version for customer support and another for administrative roles so each matches the duties in the post.</p>

<h2>Apply Consistently and Track Results</h2>

<p>Keep a simple spreadsheet with the date, the post, where you saw it, the requirements and any reply. Prepare two or three short examples of solving a problem or meeting a deadline for interviews. If an offer arrives, ask for the pay, hours, training, supervision, equipment and schedule in writing.</p>

<h2>A Simple Weekly Routine</h2>

<p>Spend a set time each week searching and applying, practising one skill and following up. Test your microphone and connection before any call or assessment. Ask about training, supervision, equipment and schedule at every interview so you can compare offers fairly.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree?</h3>
<p>Often not. The BLS lists a high school diploma or equivalent as the typical entry-level education for customer service representatives and for secretaries and administrative assistants, but each employer sets its own rules.</p>

<h3>Will the employer provide a computer?</h3>
<p>It varies. Some provide equipment and some expect you to own it. Ask who supplies it, when it arrives and what you need before training.</p>

<h3>How much can a beginner earn?</h3>
<p>It depends on the role and employer. The BLS gives national 2025 median pay of $44,770 per year for customer service representatives and $48,310 for administrative assistants, but those are medians for all workers, not beginner offers.</p>

<h3>Are these jobs definitely hiring today?</h3>
<p>No. This guide describes routes, not open vacancies. Check current availability on the employer's own site and through Michigan Works!.</p>

<h2>Bottom Line</h2>

<p>Focus on realistic beginner roles in Michigan, show your skills with honest samples, and verify every requirement before you apply. Favour clear pay, training and schedule terms over promises of effortless income, use Michigan Works! for free guidance where it is offered, and treat any request for money before you start as a warning sign.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.michiganworks.org/" rel="noopener">Michigan Works!</a></li>
<li><a href="https://www.bls.gov/ooh/office-and-administrative-support/customer-service-representatives.htm" rel="noopener">BLS Occupational Outlook Handbook: Customer Service Representatives</a></li>
<li><a href="https://www.bls.gov/ooh/office-and-administrative-support/secretaries-and-administrative-assistants.htm" rel="noopener">BLS Occupational Outlook Handbook: Secretaries and Administrative Assistants</a></li>
<li><a href="https://www.irs.gov/businesses/small-businesses-self-employed/self-employed-individuals-tax-center" rel="noopener">IRS: Self-Employed Individuals Tax Center</a></li>
<li><a href="https://consumer.ftc.gov/articles/job-scams" rel="noopener">FTC Consumer Advice: Job scams</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/how-to-get-a-remote-customer-service-job-with-no-experience">How to Get a Remote Customer Service Job With No Experience</a>: the support route in more detail.</li>
    <li><a href="/blog/transcription-jobs-from-home-ohio">Transcription Jobs From Home in Ohio</a>: how recording-based pay works.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p><em>Reviewed on 22 October 2026.</em></p>

HTML;
    }
}
