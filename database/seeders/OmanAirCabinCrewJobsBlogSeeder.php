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
 * "How to Apply for Oman Air Cabin Crew Jobs" — an employer guide that leads
 * with the thing the draft left out entirely: Oman Air is running Omanisation
 * at 79.4% and has cut expatriate headcount, which changes the odds for a
 * foreign applicant more than any requirement in the draft.
 *
 * Corrections to the draft (checked against omanair.com, services.omanair.com,
 * rop.gov.om and Oman's legislation portal, September 2026):
 *
 * 1. The draft presents age, height, swimming and education criteria as the
 *    requirements. They come from a third-party repost of a one-off,
 *    female-only Open Day held on 19 July 2026, which has passed. Oman Air
 *    publishes no standing cabin crew criteria, so the guide labels them as
 *    that event's criteria rather than current rules.
 *
 * 2. The draft omits Omanisation. Oman Air's own press release puts it at
 *    79.4%, up from 74.8% in 2023, with expatriate headcount down by 487.
 *
 * 3. The draft's pay figures come from Aviation A2Z and Avio Space. Oman Air
 *    publishes no cabin crew pay at all, so no figure is quoted.
 *
 * 4. The draft links two eRecruit URLs as the vacancy search. One redirects
 *    to the careers page and the other returns a 502, so neither works. Oman
 *    Air's own careers page points applicants at LinkedIn instead.
 *
 * 5. The draft's application guide link redirects through a regional path.
 *    The canonical page is omanair.com/en_us/application-guide.
 *
 * 6. The draft implies a recruitment fraud warning from Oman Air. No such
 *    page exists on its site, so the guide gives the general advice without
 *    attributing it to the airline.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class OmanAirCabinCrewJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.omanair.com/en/careers';

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
        $title = 'How to Apply for Oman Air Cabin Crew Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Oman Air publishes no cabin crew pay and no standing requirements, and its Omanisation rate has reached 79.4% while expatriate headcount fell by 487. Here is what the airline does document, and what that means for a foreign applicant.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-oman-air-cabin-crew-jobs.jpg',
                'tags' => 'oman air cabin crew, cabin crew jobs oman, oman air careers, muscat airline jobs, omanisation, oman work visa, cabin crew open day, gulf cabin crew jobs',
                'meta_title' => 'Oman Air Cabin Crew Jobs: Requirements and How to Apply',
                'meta_description' => 'Oman Air cabin crew jobs: what the airline actually documents, the Omanisation rate foreign applicants should know, and the real application process.',
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
            ['name' => 'Oman Air, Muscat'],
            ['type' => 'Company', 'display_reference' => 'oman-air-muscat']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Oman'],
            ['area' => 'Muscat', 'country' => 'Oman']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cabin Crew, Oman Air, Muscat Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Roster based, including night flights and layovers',
                'language' => 'English',
                // Oman Air publishes no cabin crew pay figure anywhere, so
                // the guide says that rather than quoting an estimate.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Muscat-based cabin crew roles with Oman Air, recruited through advertised vacancies and occasional Open Days.',
                'seo_keywords' => 'oman air cabin crew jobs, cabin crew jobs oman, muscat airline jobs, oman air careers',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Oman Air recruits cabin crew for its Muscat base through advertised vacancies and occasional Open Days, alongside engineering, airport, cargo, administration and cadet pilot roles.</p>

<h3>What the work involves</h3>
<p>Safety and emergency duties on board, cabin service across the network, and customer care on a roster that includes night flights and layovers away from base.</p>

<h3>What the airline documents</h3>
<ul>
    <li>A response within 6 weeks if it wants to meet you</li>
    <li>Assessment by interview, psychometric analysis, presentation, assessment centre and job simulations depending on the role</li>
    <li>Referee checks, then medical and security clearance</li>
    <li>Advice not to resign your current job until you are formally cleared to join</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> Oman Air publishes no cabin crew pay and no standing entry requirements, and Omanisation policy is set by the Omani government &mdash; not by JobGader. Never pay anyone for an interview or a job offer.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Watch Oman Air's careers page and its LinkedIn listings, apply to an advertised vacancy, and expect a reply within six weeks only if the airline wants to meet you.</strong> That six-week rule is the airline's own, and it is one of the few things about this job Oman Air actually documents.</p>

<p>Before the requirements and the process, one number that most guides on this subject leave out entirely, and that changes the picture for anyone applying from abroad.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.omanair.com/en/careers" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#b3151a;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#9992; Oman Air Careers &rarr;
    </a>
</div>

<h2>Omanisation: Read This Before You Plan Around It</h2>

<p>Oman Air's own announcement puts its <strong>Omanisation rate at 79.4%</strong>, up from <strong>74.8% in 2023</strong>. In the same restructuring the airline reduced its <strong>expatriate headcount by 487</strong>.</p>

<p>That is not a reason never to apply, and non-Omani crew do fly for Oman Air. It is a reason to be realistic about the odds and about timing: roughly four in five jobs at the airline are now held by Omani nationals, the direction of travel is upward, and the Ministry of Labour operates a profession-by-profession Omanisation tool that determines which roles can go to expatriates at all.</p>

<p>If you are weighing Gulf carriers, this is the single most useful comparison point. Our <a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">Emirates cabin crew guide</a> covers an airline that recruits worldwide through regular Open Days and publishes its pay, which is a materially different proposition.</p>

<h2>The Requirements Everyone Quotes Are From One Past Event</h2>

<p>You will find a confident list of Oman Air cabin crew requirements on every site: age 18 to 31 for Omanis and 21 to 31 for non-Omanis, able to swim 10 metres unaided, fluent English, minimum 160cm in line with BMI standards, and a secondary school certificate.</p>

<p><strong>Those criteria come from a single Open Day held in Muscat on 19 July 2026, which was open to female applicants only.</strong> That date has passed, and the list reached the wider internet through a third-party site republishing the announcement. <strong>Oman Air's own careers pages publish no standing cabin crew criteria at all</strong> &mdash; the airline says only that candidates must meet its rigorous selection procedures.</p>

<p>So treat that list as a guide to the kind of thing Oman Air asks for, not as rules you can rely on. The next announcement can differ on age, gender and nationality, and the announcement always wins.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-oman-air-cabin-crew-jobs-crew.jpg" alt="Oman Air cabin crew preparing for a flight" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>The Hiring Process, In Oman Air's Own Words</h2>

<p>This part is properly documented, in the airline's own <a href="https://www.omanair.com/en_us/application-guide" target="_blank" rel="noopener nofollow">application guide</a>:</p>

<ol>
    <li><strong>Apply to an advertised job.</strong> Oman Air assesses your suitability and responds <strong>within 6 weeks if there is interest in meeting you</strong>. Its own instruction is blunt: if you have not heard within 6 weeks, assume you have not been shortlisted.</li>
    <li><strong>Assessment.</strong> Depending on the post this can involve an interview, psychometric analysis, a presentation, an assessment centre or role-specific job simulations. Oman Air aims to reply <strong>normally within 3 weeks of interview</strong>.</li>
    <li><strong>Referee checks.</strong> Candidates are contacted for work referee details, who may then be approached.</li>
    <li><strong>Medical and security clearance.</strong> Medical checks vary by position and seniority, and security checks are made before final clearance.</li>
    <li><strong>Do not resign early.</strong> Oman Air explicitly tells candidates not to resign from their current employment until formally advised they are cleared to join.</li>
</ol>

<p>That last instruction is worth taking literally. Contracts are sometimes issued before clearance completes and remain subject to it.</p>

<h2>Where the Vacancies Actually Are</h2>

<p>Here is a practical problem no other guide mentions. <strong>Oman Air's own eRecruit vacancy tools do not currently work.</strong> The "latest active vacancies" link redirects straight back to the careers page without showing a list, and the vacancy search returns a server error. Oman Air's careers page itself points applicants to its LinkedIn jobs listings.</p>

<p>So: start at the careers page, follow it through to LinkedIn, and set an alert there. Do not assume that an empty or broken vacancy page means the airline is not hiring.</p>

<h2>Pay: Oman Air Publishes Nothing</h2>

<p><strong>There is no Oman Air cabin crew salary figure on any Oman Air page.</strong> The airline describes a competitive total rewards package and an attractive remuneration package for candidates who meet its selection procedures, and stops there.</p>

<p>The monthly ranges circulating online, usually somewhere between OMR 600 and OMR 1,800, come from aviation blogs compiling unverified submissions. We are not repeating them as fact, because there is nothing behind them.</p>

<p>Two things you can rely on instead:</p>

<ul>
    <li><strong>Oman has no personal income tax on salary</strong>, so a gross figure is close to what you receive.</li>
    <li><strong>Oman's minimum wage does not protect you.</strong> The ministerial decision setting the private sector wage floor applies, by its own title, to <strong>Omanis working in the private sector</strong>. There is no statutory minimum wage for expatriate workers, which makes your written contract the only number that binds anyone.</li>
</ul>

<p>Get the basic, the flying pay rate, the layover allowance and the accommodation arrangement in writing before you accept.</p>

<h2>The Work Visa, If You Are Hired</h2>

<p>An employment visa in Oman is employer-driven and runs in two stages: the Ministry of Labour issues the labour clearance or permit, then the <strong>Royal Oman Police</strong> issues the employment visa. Key official details:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;">Item</th>
            <th style="padding:10px;text-align:left;">Rule</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Minimum age</td><td style="padding:10px;"><strong>21</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Validity</td><td style="padding:10px;">2 years, multiple entry</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Visa fee</td><td style="padding:10px;"><strong>OMR 20</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Late renewal fine</td><td style="padding:10px;">OMR 50 a month</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Match requirement</td><td style="padding:10px;">Your gender and occupation must match the labour permit</td></tr>
    </tbody>
</table>
</div>

<p>Applicants holding the nationality of India, Pakistan, the Philippines, Bangladesh, Indonesia, Sri Lanka, Egypt, Sudan, Ethiopia, Syria or Nepal must provide medical certificates attested by the Ministry of Health.</p>

<div style="text-align:center;margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-oman-air-cabin-crew-jobs-muscat.jpg" alt="Oman Air aircraft at Muscat International Airport" style="max-width:100%;height:auto;border-radius:12px;" loading="lazy">
</div>

<h2>How to Prepare</h2>

<ol>
    <li><strong>Follow the careers page and LinkedIn</strong> rather than waiting on the broken vacancy search.</li>
    <li><strong>Read the current announcement</strong> for age, gender, nationality and height, because these change between events.</li>
    <li><strong>Prepare the documents</strong> an Open Day typically asks for: an updated CV, education certificates, passport-size photographs and a passport copy.</li>
    <li><strong>Practise swimming.</strong> A short unaided swim has been part of past assessments and is a normal aviation requirement.</li>
    <li><strong>Work on your English.</strong> Fluency in spoken and written English is the one criterion that appears in every version of the requirements.</li>
    <li><strong>Never pay for an interview, a referral or a job offer.</strong> Oman Air does not publish a recruitment fraud page, so there is no official notice to point at &mdash; which makes the general rule more important, not less. No legitimate airline charges candidates.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>What are the Oman Air cabin crew requirements?</h3>
<p>Oman Air publishes no standing criteria. The widely quoted list of age 18 to 31, 160cm, swimming and a secondary certificate comes from one female-only Open Day held on 19 July 2026.</p>

<h3>How much do Oman Air cabin crew earn?</h3>
<p>Oman Air does not publish cabin crew pay anywhere. The monthly ranges circulating online come from aviation blogs compiling unverified figures, so treat them with caution.</p>

<h3>Does Oman Air hire non-Omani cabin crew?</h3>
<p>Yes, but Omanisation at the airline has reached 79.4%, up from 74.8% in 2023, and expatriate headcount fell by 487 in its restructuring. Plan accordingly.</p>

<h3>How long does Oman Air take to respond?</h3>
<p>Within 6 weeks if it wants to meet you. Oman Air says that if you have not heard in 6 weeks, you should assume you were not shortlisted.</p>

<h3>Where can I find Oman Air vacancies?</h3>
<p>Through the careers page and Oman Air's LinkedIn jobs listings. Its own eRecruit vacancy tools currently redirect away or return a server error.</p>

<h3>Is there a minimum wage for expatriates in Oman?</h3>
<p>No. The ministerial decision setting the private sector wage floor applies to Omanis working in the private sector, so your contract is the only binding figure.</p>

<h3>What does an Oman work visa cost?</h3>
<p>OMR 20 for a two-year multiple entry employment visa issued by the Royal Oman Police, with a late renewal fine of OMR 50 a month.</p>

<h3>Do I need to swim for the cabin crew assessment?</h3>
<p>The July 2026 Open Day required swimming 10 metres unaided, assessed on the day. Swimming ability is a normal aviation requirement, so prepare for it.</p>

<h2>People Also Search For</h2>

<h3>Oman Air careers cabin crew</h3>
<p>Advertised through the careers page and LinkedIn, with occasional Open Days in Muscat.</p>

<h3>Omanisation rate 2026</h3>
<p>79.4% at Oman Air, up from 74.8% in 2023, alongside a reduction of 487 expatriate roles.</p>

<h3>Oman Air open day requirements</h3>
<p>The July 2026 event asked for English fluency, a 10 metre unaided swim, 160cm minimum height and a secondary certificate.</p>

<h3>Oman work visa cost</h3>
<p>OMR 20, valid two years and multiple entry, issued by the Royal Oman Police after labour clearance.</p>

<h3>Oman minimum wage expatriate</h3>
<p>There is none. The private sector wage floor applies to Omani nationals only.</p>

<h3>Oman income tax on salary</h3>
<p>Oman levies no personal income tax on salary.</p>

<h3>Cabin crew jobs Muscat</h3>
<p>Oman Air's cabin crew are based in Muscat, with rosters including night flights and layovers.</p>

<h3>Oman Air hiring process time</h3>
<p>Six weeks for an initial response, then about three weeks for a reply after interview.</p>

<h2>More Job Guides</h2>

<p>Comparing Gulf employers and routes? These cover them:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; an airline that publishes its pay and recruits worldwide.</li>
    <li><a href="/blog/how-to-get-a-job-in-saudi-arabia-as-a-foreigner">How to Get a Job in Saudi Arabia as a Foreigner</a> &mdash; the sponsorship system and Saudization, the parallel of Omanisation.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; customer-facing Gulf work with lower entry barriers.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; where to build the hospitality experience airlines ask for.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; another sponsored Gulf route.</li>
    <li><a href="/blog/how-to-apply-for-kuwait-airways-cabin-crew-jobs">How to Apply for Kuwait Airways Cabin Crew Jobs</a> &mdash; the Gulf carrier whose careers portal most guides cannot find.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; why Abu Dhabi ground jobs are advertised by a company called Velora.</li>
    <li><a href="/blog/how-to-apply-for-pdo-engineering-jobs-in-oman">How to Apply for PDO Engineering Jobs in Oman</a> &mdash; the government portal that replaced PetroJobs.</li>
    <li><a href="/blog/how-to-apply-for-singapore-airlines-cabin-crew-jobs">How to Apply for Singapore Airlines Cabin Crew Jobs</a> &mdash; published pay, a five-year contract, and the service bond most guides omit.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Oman Air's own careers pages, application guide and press releases, Royal Oman Police visa information and Oman's published ministerial decisions. Oman Air does not publish cabin crew pay or standing entry requirements, and recruitment criteria change between announcements. Always follow the current official announcement.</p>
HTML;
    }
}
