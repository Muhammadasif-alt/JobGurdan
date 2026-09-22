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
 * "How to Apply for Singapore Airlines Cabin Crew Jobs" — an airline that
 * publishes its cabin crew pay, which almost none of them do, and that runs
 * recruitment country by country rather than opening one global door. Pakistan
 * is not one of those countries, and saying so plainly is the point of the page.
 *
 * Corrections to the draft (checked against singaporeair.com and the live
 * careers.singaporeair.com listings, 22 September 2026):
 *
 * 1. The draft's careers URL 404s. The cabin crew page is
 *    singaporeair.com/en_UK/sg/careers/cabin-crew-career/, and the live
 *    vacancies sit on a separate host, careers.singaporeair.com.
 *
 * 2. The draft dates the Singapore interview to 26 September 2026. SIA
 *    reposted that listing on 22 September with the date stripped out. The
 *    guide therefore leads on the campaigns, not on dates that die in weeks.
 *
 * 3. The draft presents five O-Level credits as SIA's education bar. That is
 *    the Singapore rule. Japan needs junior college or a degree, Taiwan needs
 *    a bachelor's degree, and Malaysia accepts SPM credits as well.
 *
 * 4. The draft says every campaign requires you to start within three months.
 *    The live Japan listing says six.
 *
 * 5. The draft omits the compulsory service bond that both overseas campaigns
 *    require. For a reader weighing relocation, that is a material omission.
 *
 * 6. The draft implies Pakistani readers can find a campaign. SIA's portal
 *    returns no results for Pakistan or India at all, and Pakistan is not even
 *    among the countries SIA lists as closed. There is no channel to wait for.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class SingaporeAirlinesCabinCrewJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.singaporeair.com/sia/go/Cabin-Crew/689244/';

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
        $title = 'How to Apply for Singapore Airlines Cabin Crew Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Singapore Airlines publishes what its cabin crew earn, which is rare. It also recruits country by country, and Pakistan is not one of them. Here is who can actually apply, and the service bond most guides leave out.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-singapore-airlines-cabin-crew-jobs.jpg',
                'tags' => 'singapore airlines cabin crew, sia cabin crew jobs, singapore airlines careers, cabin crew salary singapore, cabin crew jobs singapore, sia recruitment, cabin crew height requirement, singapore airlines service bond',
                'meta_title' => 'Singapore Airlines Cabin Crew Jobs: How to Apply',
                'meta_description' => 'Singapore Airlines cabin crew jobs: the live country campaigns, the published SGD 4,000-5,000 package, the service bond, and who can actually apply.',
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
            ['name' => 'Singapore Airlines, Singapore'],
            ['type' => 'Company', 'display_reference' => 'singapore-airlines']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Singapore'],
            ['area' => 'Singapore', 'country' => 'Singapore']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'hospitality-tourism'],
            ['name' => 'Hospitality & Tourism']
        );

        Job::updateOrCreate(
            [
                'position' => 'Cabin Crew, Singapore Airlines, Singapore Based',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Rostered flying duties including nights, weekends and public holidays',
                'language' => 'Fluent English; additional languages valued on some campaigns',
                // Singapore Airlines prints this package on its own cabin crew
                // careers page. It is the employer's published figure, not an
                // estimate site's, and it keeps SIA's own hedges.
                'salary_currency' => 'SGD',
                'salary_period' => 'Monthly',
                'salary_minimum' => 4000,
                'salary_maximum' => 5000,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Singapore-based cabin crew roles recruited through country campaigns. SIA publishes a monthly package of about SGD 4,000 to SGD 5,000.',
                'seo_keywords' => 'singapore airlines cabin crew, sia cabin crew jobs, cabin crew salary singapore, singapore airlines careers, cabin crew recruitment',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the cabin crew campaigns Singapore Airlines runs, not a single vacancy and not a job advertised by JobGader. Applications are made on Singapore Airlines' own careers portal.</p>

<h3>Read this before you apply</h3>
<p>Every cabin crew role is based in Singapore, whatever country the interview is held in. SIA recruits through country-specific campaigns and opens and closes them without notice. When we checked on 22 September 2026, its portal returned no results at all for Pakistan or India.</p>

<h3>What the work involves</h3>
<ul>
    <li>Onboard safety and emergency procedures, which is what the role legally exists for.</li>
    <li>Cabin service, food and beverage, and passenger handling across a long-haul network.</li>
    <li>Rostered duties including nights, weekends and public holidays, with layovers away from base.</li>
</ul>

<h3>Pay</h3>
<p>Singapore Airlines publishes this on its own cabin crew careers page: "You will receive a basic salary during your training period. Upon graduation and the start of your flying duties, you will enjoy an attractive monthly salary package of approximately SGD 4,000 to SGD 5,000, inclusive of flight allowance based on hours flown." The figure is a package that moves with hours flown, not a guaranteed basic.</p>

<h3>Contract</h3>
<p>SIA states that crew are based in Singapore on an initial 5-year contract, with the opportunity for extension depending on performance. Its overseas campaigns additionally require willingness to serve a compulsory service bond.</p>

<h3>Requirements</h3>
<p>Minimum age 18. Fluent English. Minimum height of 1.58m for females and 1.65m for males, which SIA ties to safety and emergency procedures. Education differs by campaign: five GCE O-Level credits including English or Higher Nitec and above for Singapore, a junior college or university qualification for Japan, and a bachelor's degree for Taiwan.</p>

<p>Pay, eligibility, contract terms and recruitment schedules are set by Singapore Airlines &mdash; not by JobGader. SIA states it conducts direct recruitment and engages no third-party agencies for cabin crew. Confirm the requirements on the live posting, and never pay anyone to secure a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Singapore Airlines does something most airlines refuse to do: it tells you what the job pays before you apply. That alone makes this one worth reading properly.</p>

<p>But there is a hard part first, and you deserve it before you spend a month preparing. <strong>Singapore Airlines is not recruiting cabin crew from Pakistan, and there is no closed Pakistani campaign waiting to reopen.</strong> We searched its careers portal on 22 September 2026. "Pakistan" returns <em>"There are currently no open positions matching 'Pakistan'."</em> So does "India". Pakistan is not even listed among the countries SIA names as currently closed.</p>

<p>That is not the end of the article. There is a real route, it is just not the one the recruitment pages on Facebook are selling you. Let us go through all of it honestly.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-singapore-airlines-cabin-crew-jobs-crew.jpg" alt="Singapore Airlines cabin crew in the batik sarong kebaya uniform walking through an airport terminal towards an aircraft" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Singapore Airlines had exactly four live cabin crew campaigns open when we checked its careers portal, and every one of them is a Singapore-based job.</figcaption>
</figure>

<h2>The Thing Almost Every Guide Gets Wrong</h2>

<p>Singapore Airlines does not run one global cabin crew vacancy. It runs <strong>country campaigns</strong>, each with its own requirements, its own interview city and its own closing behaviour. When we checked, there were four:</p>

<ul>
    <li><strong>Singapore</strong> &mdash; interviews held in Singapore.</li>
    <li><strong>Malaysia</strong> &mdash; interviews held in Kuala Lumpur.</li>
    <li><strong>Japan</strong> &mdash; interviews held in Tokyo.</li>
    <li><strong>Taiwan</strong> &mdash; interviews held in Taipei.</li>
</ul>

<p><strong>Here is the part that confuses people: the country name is where the interview happens, not where the job is.</strong> All four roles are Singapore-based. You do not become "Singapore Airlines cabin crew in Malaysia". You relocate to Singapore.</p>

<p>SIA also lists several countries as explicitly closed &mdash; India, Sri Lanka, South Korea, Hong Kong SAR (China) and Thailand &mdash; each reading <em>"Please note that applications for Cabin Crew positions in Singapore are currently closed. Do check our career website for future recruitment."</em> Pakistan is absent from both lists. There is nothing to monitor.</p>

<h2>Why We Are Not Printing Interview Dates</h2>

<p>Most articles on this topic lead with a date. We are not going to, and the reason is instructive.</p>

<p>SIA's own marketing page said interviews would be held in Singapore on 26 September 2026. When we opened the actual job listing, reposted the same day we checked, the date was <strong>gone</strong> &mdash; replaced with a flat <em>"We will be conducting interviews for Cabin Crew in Singapore."</em> The airline had stripped it on repost.</p>

<p>Of the campaigns that still carried dates when we looked, Malaysia showed 17 October 2026 and Taiwan showed 21 November 2026, with Japan giving only "October 2026" with no day. <strong>Treat all of those as already expired.</strong> SIA rotates these continuously. The only reliable move is to open the live listing yourself, which is linked at the bottom of this guide.</p>

<p>If you find an article confidently naming an interview date, check when it was written. A stale date is the clearest sign nobody has looked at the source in months.</p>

<h2>What Singapore Airlines Actually Pays</h2>

<p>This is SIA's own wording, from its cabin crew careers page:</p>

<p style="border-left:4px solid #1a3668;padding:12px 18px;background:#f7f9fc;margin:22px 0;">"You will receive a basic salary during your training period. Upon graduation and the start of your flying duties, you will enjoy an attractive monthly salary package of approximately SGD 4,000 to SGD 5,000, inclusive of flight allowance based on hours flown."</p>

<p>Read the hedges, because they matter. It says <strong>"approximately"</strong>, it says <strong>"package"</strong>, and it says <strong>"inclusive of flight allowance based on hours flown"</strong>. This is not a guaranteed basic salary of SGD 4,000. A light roster month pays less. Any article quoting "SGD 5,000 salary" flat has dropped the qualifiers the airline deliberately put there.</p>

<p>You are also paid during the four-month training, but at a basic rate, not the flying package.</p>

<h2>The Service Bond Nobody Mentions</h2>

<p>Both overseas campaigns &mdash; Japan and Taiwan &mdash; carry a requirement most guides omit entirely: <em>"Willingness and commitment to serve a compulsory service bond."</em></p>

<p>A service bond is a contractual commitment to stay for a fixed period, with money owed back if you leave early. SIA does not publish the amount or duration on the listing, which means <strong>you must ask before you sign anything.</strong> For someone relocating internationally on a five-year contract, this is the single most important financial term on the page, and it is the one that gets left out.</p>

<h2>Requirements, Campaign by Campaign</h2>

<p>The requirements are not uniform. Presenting them as one list, which most articles do, is how people apply to the wrong campaign.</p>

<h3>What applies everywhere</h3>

<ul>
    <li><strong>Minimum age 18</strong>, which SIA attributes to legislative requirements.</li>
    <li><strong>Fluent English</strong> with good communication skills for servicing international customers.</li>
    <li><strong>Willingness to relocate to Singapore</strong>, because that is where the job is.</li>
</ul>

<h3>Height</h3>

<p>SIA lists <strong>a minimum height of 1.58m for females and 1.65m for males</strong>, which it ties to carrying out safety and emergency procedures onboard. Note this is a genuine minimum height, not the "able to reach 212cm" test some other airlines use. Curiously, the Taiwan listing omits the height requirement altogether.</p>

<h3>Education, which is where campaigns diverge</h3>

<ul>
    <li><strong>Singapore:</strong> five GCE 'O' Level credits including English, or Higher Nitec and above.</li>
    <li><strong>Malaysia:</strong> five SPM credits including English, or the O-Level equivalent, or Higher Nitec and above.</li>
    <li><strong>Japan:</strong> junior college (TANDAI) or university degree holders. Those graduating by September 2027 may apply. <strong>No O-Level route.</strong></li>
    <li><strong>Taiwan:</strong> a bachelor's degree from a recognised university. <strong>No O-Level route.</strong></li>
</ul>

<h3>How soon you must start</h3>

<p>Singapore, Malaysia and Taiwan require you to be able to commence employment <strong>within three months</strong> of applying. <strong>Japan's live listing says six months.</strong> SIA's own marketing page still says three for Japan, which contradicts its listing. The listing is the operative document.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-singapore-airlines-cabin-crew-jobs-cabin.jpg" alt="Singapore Airlines cabin crew serving a passenger in the cabin and walking through Changi Airport with the Singapore skyline behind" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Every campaign leads to the same place: a Singapore base on an initial five-year contract.</figcaption>
</figure>

<h2>The Four-Month Training</h2>

<p>SIA describes it as <em>"a comprehensive four-month training programme, from safety procedures to product knowledge to grooming, equipping you with the knowledge and skillsets you need to operate as a SIA Cabin Crew."</em></p>

<p>Those three areas &mdash; <strong>safety procedures, product knowledge, grooming</strong> &mdash; are the only subjects SIA publishes. Longer syllabus lists you see elsewhere are invented. The airline does add that crew get opportunities to expand their skillsets, "whether it is becoming a sommelier, mastering a new language, or deepening your expertise in customer service".</p>

<h2>What You Actually Get If You Are Hired</h2>

<p>SIA publishes four benefits, preceded by "In addition to a competitive salary, you will enjoy:"</p>

<ul>
    <li>Once-a-year free travel to any SIA-operated destination.</li>
    <li>Unlimited discounted travel for you and your eligible family member(s) on flights operated by SIA and its airline partners.</li>
    <li>Medical, dental, and insurance coverage.</li>
    <li>Ongoing training and career development opportunities.</li>
</ul>

<p>The contract is an <strong>initial 5-year contract based in Singapore</strong>, with extension depending on performance.</p>

<h2>Singapore Airlines Names the Only Places It Advertises</h2>

<p>This is the most protective paragraph on this page, so read it twice. SIA states on every cabin crew listing:</p>

<p style="border-left:4px solid #1a3668;padding:12px 18px;background:#f7f9fc;margin:22px 0;">"Singapore Airlines conducts direct recruitment and does not engage any third-party agencies / companies for our cabin crew recruitment. Interested applicants are encouraged to submit your application via the official Singapore Airlines career page only. Any parties or media platforms that claim to represent Singapore Airlines have no authority to recruit or engage with applicants on behalf of the airline."</p>

<p>Its phishing advisory adds that <em>"all available job vacancies at Singapore Airlines are advertised at our official Careers portal, as well as on LinkedIn"</em>, and names fake addresses already in circulation, including <code>hr@singaporeairlinehr.com</code> and <code>members@singaporeair-mail.com</code>.</p>

<p>SIA also states it <em>"does not request for payment or fees for the processing of job applications"</em>. <strong>Anyone in Pakistan offering you an SIA cabin crew interview, a training slot or a referral for money is running a scam.</strong> There is no Pakistani campaign for them to be recruiting into. Report it, and lodge a police report if you have already paid.</p>

<h2>So What Should You Actually Do?</h2>

<p>If you hold a passport or residency that lets you attend one of the live campaigns, apply directly on the portal and prepare properly.</p>

<p>If you are in Pakistan with no other status, the honest answer is that this employer has no door open to you right now, and no guide can invent one. The Gulf carriers are the realistic route into cabin crew from Pakistan, because they recruit from the region and they sponsor. We have written those up separately, and they are linked at the end of this guide.</p>

<h2>How to Apply, Step by Step</h2>

<p><strong>Official application route:</strong> <a href="https://careers.singaporeair.com/sia/go/Cabin-Crew/689244/" rel="nofollow noopener" target="_blank">https://careers.singaporeair.com/sia/go/Cabin-Crew/689244/</a> &mdash; this is the live cabin crew listing page on Singapore Airlines' own careers portal. The salary and benefits detail sits separately on <a href="https://www.singaporeair.com/en_UK/sg/careers/cabin-crew-career/" rel="nofollow noopener" target="_blank">singaporeair.com/en_UK/sg/careers/cabin-crew-career/</a>.</p>

<ol>
    <li>Open the official cabin crew listings at careers.singaporeair.com and see which campaigns are live today.</li>
    <li>Check which campaign you are actually eligible for, including its education bar, which differs by country.</li>
    <li>Read the commencement window &mdash; three months for most, six for Japan &mdash; and be honest about whether you can meet it.</li>
    <li>If it is Japan or Taiwan, ask about the service bond amount and duration before committing.</li>
    <li>Submit through the official portal only. Selected applicants receive a link to complete a video interview online.</li>
    <li>Shortlisted candidates are invited to the final interviews; Japan's listing describes face-to-face interviews after the video stage.</li>
    <li>Expect four months of training in Singapore before you fly.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply for Singapore Airlines cabin crew from Pakistan?</h3>
<p>Not at present. SIA's careers portal returns no results for Pakistan, and Pakistan is not listed even among the countries SIA names as closed. It recruits through country campaigns, and there is currently no Pakistani one open or paused.</p>

<h3>How much do Singapore Airlines cabin crew earn?</h3>
<p>SIA publishes a monthly package of approximately SGD 4,000 to SGD 5,000 once flying duties begin, inclusive of flight allowance based on hours flown. A basic salary is paid during the four-month training. It is a package that varies with hours, not a fixed basic.</p>

<h3>What is the height requirement for Singapore Airlines cabin crew?</h3>
<p>A minimum of 1.58m for females and 1.65m for males, which SIA ties to carrying out safety and emergency procedures onboard. The Taiwan campaign listing omits the requirement.</p>

<h3>Do I need a degree to be Singapore Airlines cabin crew?</h3>
<p>It depends entirely on the campaign. Singapore accepts five GCE 'O' Level credits including English or Higher Nitec and above. Japan requires junior college or a degree, and Taiwan requires a bachelor's degree from a recognised university.</p>

<h3>What is the Singapore Airlines cabin crew service bond?</h3>
<p>Both the Japan and Taiwan campaigns require willingness to serve a compulsory service bond, a commitment to stay for a fixed period with money repayable if you leave early. SIA does not publish the amount or duration, so ask before signing.</p>

<h3>Are Singapore Airlines cabin crew jobs based in the country of the interview?</h3>
<p>No. Every campaign leads to a Singapore-based role on an initial five-year contract. The country in the listing title is where interviews are held, not where you will live.</p>

<h3>How long is Singapore Airlines cabin crew training?</h3>
<p>Four months. SIA publishes only three subject areas for it: safety procedures, product knowledge and grooming. You are paid a basic salary throughout.</p>

<h3>Does Singapore Airlines use recruitment agents in Pakistan?</h3>
<p>No. SIA states it conducts direct recruitment and does not engage any third-party agencies for cabin crew recruitment, and that it never requests payment or fees to process applications. Anyone charging you for an SIA interview is running a scam.</p>

<h2>People Also Search For</h2>

<h3>Singapore Airlines careers login</h3>
<p>The live vacancies sit on careers.singaporeair.com, a different host from the main site. The marketing page at singaporeair.com carries the salary and benefits detail but not the current openings.</p>

<h3>Singapore Airlines cabin crew salary in rupees</h3>
<p>SIA publishes the package only in Singapore dollars, approximately SGD 4,000 to SGD 5,000 a month inclusive of flight allowance. Converted figures in other articles are that airline's number run through a rate that has since moved.</p>

<h3>SIA cabin crew age limit</h3>
<p>SIA publishes a minimum age of 18 due to legislative requirements. It does not publish a maximum age on its current cabin crew listings.</p>

<h3>Singapore Airlines cabin crew interview questions</h3>
<p>SIA publishes the process, not the questions: a video interview online for selected applicants, then final interviews for those shortlisted. Japan's listing describes face-to-face interviews after the video stage.</p>

<h3>Singapore Airlines walk-in interview</h3>
<p>SIA has run walk-in interview campaigns in Singapore, but the 2026 walk-in listing now returns "Sorry, this position has been filled." Walk-ins are announced and closed on the portal without notice.</p>

<h3>Cabin crew jobs with visa sponsorship</h3>
<p>SIA's campaigns lead to a Singapore base, and eligibility is set by the campaign you apply through rather than by sponsorship. The Gulf carriers recruit more openly from South Asia.</p>

<h3>Singapore Airlines job scam</h3>
<p>SIA advertises only on its official careers portal and LinkedIn, never charges application fees, and engages no third-party agencies for cabin crew. Its advisory names fake domains including singaporeairlinehr.com.</p>

<h3>Singapore Airlines cabin crew contract length</h3>
<p>An initial 5-year contract based in Singapore, with extension depending on performance. Overseas campaigns add a compulsory service bond on top.</p>

<h2>More Job Guides</h2>

<p>If cabin crew is the goal and Singapore is closed to you, these are the routes that actually recruit from Pakistan and the Gulf:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-emirates-cabin-crew-jobs-in-uae">How to Apply for Emirates Cabin Crew Jobs in UAE</a> &mdash; the largest recruiter of cabin crew from South Asia.</li>
    <li><a href="/blog/how-to-apply-for-oman-air-cabin-crew-jobs">How to Apply for Oman Air Cabin Crew Jobs</a> &mdash; a smaller fleet, and a clearer path in.</li>
    <li><a href="/blog/how-to-apply-for-kuwait-airways-cabin-crew-jobs">How to Apply for Kuwait Airways Cabin Crew Jobs</a> &mdash; and the age limits its own pages contradict.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; ground roles in a market that does recruit from abroad.</li>
    <li><a href="/blog/how-to-apply-for-qantas-ground-staff-jobs-in-australia">How to Apply for Qantas Ground Staff Jobs in Australia</a> &mdash; published enterprise agreement pay, and who can realistically apply.</li>
    <li><a href="/blog/how-to-apply-for-air-canada-airport-jobs">How to Apply for Air Canada Airport Jobs</a> &mdash; another airline that publishes its pay, and two locks that close it from abroad.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Singapore Airlines' own cabin crew careers page, its live listings on careers.singaporeair.com and its phishing advisory, checked on 22 September 2026. Campaigns, interview dates, pay and requirements change without notice. Always check the live listing before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
