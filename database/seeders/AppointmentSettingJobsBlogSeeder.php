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
 * "Appointment Setting Jobs" - the phone half of the outreach family.
 *
 * Kept apart from its two siblings by channel and by subject. The lead
 * generation assistant guide owns email and LinkedIn outreach and the law that
 * governs written contact; the LinkedIn guide owns search. This one owns the
 * telephone: American calling rules, the night shift, and the pay structures
 * that only exist in phone work.
 *
 * Every pay claim in the source material failed verification (checked
 * 23 September 2026 against 15 live Rozee listings and 5 live Mustakbil ones):
 *
 * - "$17 to $22.50 an hour" for a US remote setter: not present in any live
 *   listing on either board. The only USD hourly rate actually open to a
 *   Pakistan-based setter was $3.00. The claim is roughly six times too high
 *   and is not repeated here.
 * - "$50 per completed appointment" and "$500 to $1,000 a week": not found.
 *   The one real performance structure pays $3.00/hr plus $10 when the
 *   homeowner sits, $25 after they sign and $465 only after installation.
 * - "PKR 25,000 a month plus 5 percent commission": no listing at PKR 25,000
 *   and no 5 percent commission anywhere in either feed. The only percentage
 *   commission found was 30 percent per closed client, commission-only.
 * - "PKR 40,000 to 50,000 plus commission": closest live listing advertises
 *   PKR 50,000 plus commission. The real spread is PKR 30,000 to 150,000.
 *
 * Two BLS figures were rounded in the source and are given exactly here:
 * telemarketer employment falls 21.4 percent rather than 21, and sales
 * representatives of services grow 2.7 percent rather than 3. The growth
 * figure is tied to SOC 41-3091 explicitly, because the published profile may
 * aggregate 41-3011 with it and that would change both numbers.
 *
 * One claim was corrected rather than cut: the Occupational Requirements
 * Survey figure of 68.1 percent is real, but BLS's own frequency label for it
 * is "every few minutes", not "constantly", the population is "sales and
 * related occupations", and the estimate is flagged preliminary.
 *
 * One widely repeated compliance claim is simply out of date. The FTC's own
 * guidance page still says telemarketing records must be kept for two years.
 * The binding rule, 16 CFR 310.5(a) as amended at 89 FR 26784 (16 April 2024),
 * says five. The rule text is followed here, not the guidance page.
 *
 * Likewise the "one-to-one consent" rule is described by many 2026 articles as
 * current law. The Eleventh Circuit vacated it and the mandate issued on
 * 30 April 2025; it never took effect, and the FCC has reinstated the prior
 * text of 47 CFR 64.1200(f)(9). This guide says so.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AppointmentSettingJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.rozee.pk/';

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
        $title = 'Appointment Setting Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Eighteen live listings, a pay spread from PKR 30,000 to 150,000, and a night shift on nearly all of them. Plus the American calling rules that reach a dialer in Karachi, and the dollar rates that circulate online but exist in no listing.',
                'content' => $content,
                'featured_image' => 'blogs/appointment-setting-jobs.jpg',
                'tags' => 'appointment setting jobs, appointment setter jobs pakistan, cold calling jobs lahore, night shift sales jobs, sdr jobs pakistan, telemarketing sales rule, do not call registry, tcpa compliance',
                'meta_title' => 'Appointment Setting Jobs: Pay, Night Shifts and the Law',
                'meta_description' => 'What Pakistani appointment setter listings actually pay, why nearly all run night shifts, and the US calling rules that reach you from Karachi or Lahore.',
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
            ['name' => 'Employers Hiring Appointment Setters (Aggregated)'],
            ['type' => 'Company', 'display_reference' => 'appointment-setting-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Nationwide', 'country' => 'Pakistan']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales']
        );

        Job::updateOrCreate(
            [
                'position' => 'Appointment Setter',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site or Remote',
                'work_hours' => 'Evening or night shift on most listings, because the calls land in American, British or Australian business hours',
                'language' => 'English, spoken, with an accent the called party can follow without effort',
                // Live listings span PKR 30,000 to PKR 150,000 a month, and the
                // dollar-denominated ones pay an hourly rate plus milestone
                // bonuses that no single band can represent. Dated figures are
                // quoted in the guide instead.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Aggregated appointment setter and cold calling roles, with the real advertised pay, the shift pattern, and the American calling rules that apply offshore.',
                'seo_keywords' => 'appointment setter jobs, cold calling jobs pakistan, sdr jobs, night shift sales, telemarketing sales rule',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of appointment setting and cold calling roles, not a single vacancy and not a job advertised by JobGader. Applications are made on the employer's own site or on the board where the role appears.</p>

<h3>What the work involves</h3>
<p>Calling a list of people who did not ask to be called, holding the conversation long enough to establish whether there is a real need, and booking a meeting for somebody else to close. You are measured on booked meetings that are actually attended, not on dials.</p>

<h3>The shift is the job</h3>
<p>Almost every Pakistani listing in this category runs evening or night hours, because the calls land in American, British or Australian business hours. One advertised shift ran 7:00 PM to 4:00 AM, Monday to Friday, on site. Treat the shift as a permanent feature of the role, not a temporary arrangement.</p>

<h3>American calling law reaches you here</h3>
<p>The TCPA applies to "any person within the United States, or any person outside the United States if the recipient is within the United States". The FTC says the same of its Telemarketing Sales Rule. Calling a US residence outside 8:00 a.m. to 9:00 p.m. in the called party's own time zone breaches the rule, and the client who hired the campaign carries the liability. A script that instructs you to misrepresent who you are or what you are selling is not exempt under any circumstances.</p>

<h3>Before you accept</h3>
<p>Ask which market you will dial and what the compliance process is: who scrubs the do-not-call list and how often, whether calls are recorded, and what the script discloses in its first sentence. Ask how the pay actually works &mdash; a base plus a fee per attended meeting is very different from commission only.</p>

<p>Requirements, hours and pay are set by individual employers &mdash; not by JobGader. Never pay anyone to secure a job, and never accept a role that asks you to conceal the identity of the business you are calling for.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Appointment setting is the telephone end of sales. Somebody hands you a list, you call people who were not expecting it, and your job is to book a meeting that the person actually turns up to. In Pakistan it is one of the few sales roles that pays well without a degree, and it comes with two things the job adverts underplay: a permanent night shift, and a body of American law that follows the call across the ocean.</p>

<p>This guide is built on 15 live listings from one board and 5 from the other, read on <strong>23 September 2026</strong>, plus the current text of the rules themselves rather than anybody's summary of them. For the written side of outreach &mdash; email and LinkedIn &mdash; see <a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a>.</p>

<h2>What Pakistani Employers Are Actually Advertising</h2>

<p>Searching one board for "appointment setter" returned <strong>15 live listings</strong>, every one of them posted between 25 August and 23 September 2026. The broader phrase "appointment setting" returned 29, which pulls in cold callers and sales development representatives. The other board carried <strong>5</strong>. After removing the two employers who posted on both, the real figure is roughly <strong>18 distinct live vacancies</strong> nationally.</p>

<p>That is a small market but a live one, and unusually for this kind of work, much of it advertises a salary. These are the listings that publish a figure, with the details from their own pages:</p>

<ul>
    <li><strong>Lahore, US real estate management</strong> &mdash; <strong>PKR 150,000 a month</strong>, outbound sales representative calling American residential property owners. Posted 5 September 2026. The highest advertised figure in the whole set.</li>
    <li><strong>Lahore, USA market, Johar Town</strong> &mdash; <strong>PKR 60,000 to 120,000</strong>, two years of experience, explicitly advertised as a US night shift. Posted 12 September 2026.</li>
    <li><strong>Islamabad, B2B and B2C</strong> &mdash; <strong>PKR 100,000 a month</strong>, one year of experience. Posted 25 August 2026.</li>
    <li><strong>Rawalpindi</strong> &mdash; <strong>PKR 60,000 to 100,000</strong>, under a year of experience, night shift. Posted 25 August 2026.</li>
    <li><strong>Karachi, remote</strong> &mdash; <strong>PKR 60,000 flat</strong> plus performance upside, one year preferred, Pakistan night shift overlapping US business hours. Posted 27 July 2026.</li>
    <li><strong>Rawalpindi, lead verifier and setter</strong> &mdash; <strong>PKR 50,000 to 60,000</strong> plus what the advert calls uncapped performance bonuses, twelve months of experience, night shift. Posted 24 August 2026.</li>
    <li><strong>Lahore, onsite</strong> &mdash; <strong>PKR 55,000</strong>, shift stated as 7:00 PM to 4:00 AM, Monday to Friday. Posted 11 September 2026.</li>
    <li><strong>Lahore, B2B cold caller, USA market</strong> &mdash; advertised band PKR 35,000 to 60,000, with the body text stating <strong>PKR 50,000 plus performance commission</strong>. Posted 4 September 2026.</li>
</ul>

<p>So the honest range is <strong>PKR 30,000 at the very bottom to PKR 150,000 at the top</strong>, clustering at <strong>50,000 to 60,000</strong> for someone with under a year behind them. Experience moves the number, but the market you dial moves it more: the two highest-paying listings both call the United States.</p>

<p>One caution on reading these boards. One of them lets an employer enter a salary and then hide it from jobseekers, and it lets them print a single figure as a range with identical bounds. Where a listing shows no salary, that is a choice the employer made, not a missing field &mdash; and it usually means the number is negotiable downwards.</p>

<h2>The Dollar Rates That Circulate Online Do Not Exist Here</h2>

<p>Three figures travel with this job title and we could not find any of them in a live listing. They matter because people turn down local work while waiting for them.</p>

<p><strong>"$17 to $22.50 an hour."</strong> We checked every dollar amount and every "per hour" string across all 20 live listings on both boards. Nothing came close. <strong>The only US dollar hourly rate actually on offer to a Pakistan-based setter was $3.00 an hour.</strong> The circulating figure is roughly six times the real one.</p>

<p><strong>"$50 per completed appointment"</strong> and <strong>"$500 to $1,000 a week."</strong> Neither appears in any listing. What does exist is one American solar company advertising to remote candidates, with the note that applicants outside the United States are welcome. Its pay structure, in its own words: <strong>$3.00 an hour on the clock, plus $10 when the homeowner actually sits, plus $25 after they sign, plus $465 only after the job installs</strong> &mdash; and not if the customer cancels. The role asks for 40-plus hours a week, six days a week, covering US Eastern evening and night windows.</p>

<p>Do the arithmetic before it is done to you. Forty hours at $3.00 is <strong>$120 a week</strong> guaranteed. Everything above that depends on a homeowner keeping an appointment, signing a contract, and then not cancelling before installation &mdash; three separate events, none of which you control, the last of which can be months later. It is a real offer from a real company and it may still work out; it is simply not $500 to $1,000 a week, and the guaranteed part of it is below what several Lahore listings pay outright.</p>

<p><strong>"PKR 25,000 a month plus 5 percent commission."</strong> No listing in either feed pays PKR 25,000, and <strong>no listing anywhere states a 5 percent commission</strong>. The only percentage commission we found was 30 percent per closed client &mdash; from a UK-market role that is commission-only with no base at all, and which works over direct messages and email rather than the phone.</p>

<p>A note on searching, too. Search engines will summarise an expired listing for this job as paying PKR 75,000 to 200,000. We opened it: it closed in 2023 and its page carries no salary figure whatsoever. The number was invented somewhere between the listing and the summary.</p>

<h2>The Night Shift Is Not Negotiable</h2>

<p>Every one of the five listings on the smaller board states an evening or night shift &mdash; four tagged night, one evening. On the larger board only five of fifteen say so outright, but most of the rest name a US, UK or Australian market, which amounts to the same thing. Overall, <strong>around 10 of the 18 distinct listings either state a night shift or target a timezone that requires one</strong>.</p>

<p>Examples of what that means in practice, from the adverts themselves: 7:00 PM to 4:00 AM Monday to Friday, on site in Lahore. An Australian Eastern shift for a fintech client, with 100 to 150 dials a day on an automated dialer. US Eastern evening and night windows, six days a week.</p>

<p>Treat this as the defining condition of the job rather than a detail. It is why the pay is higher than daytime office work at the same experience level, and it is the part people leave over.</p>

<h2>What the Official Data Says About This Career</h2>

<p>There is no occupation called "appointment setter" in the US classification system. Searching the full list of example job titles across all 834 occupations, the only "appointment" title that exists is <strong>Appointment Clerk</strong>, filed under Receptionists and Information Clerks. The occupational match for outbound calling is <strong>Telemarketers</strong>, whose example titles include outbound telemarketer, telephone solicitor and telesales representative.</p>

<p>The numbers for it are not encouraging, and you should know them before you commit years to this:</p>

<table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:15px;">
    <thead>
        <tr style="background:#f3f4f6;">
            <th style="padding:10px;border:1px solid #e5e7eb;text-align:left;">Measure (Telemarketers, SOC 41-9041)</th>
            <th style="padding:10px;border:1px solid #e5e7eb;text-align:left;">Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Median annual wage, 2025</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>$35,450</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">US employment, 2025</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">60,900</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Projected employment, 2035</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">47,800</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Change, 2025 to 2035</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>-21.4%</strong> (-13,000 jobs)</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Typical entry-level education</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">No formal educational credential</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Typical training</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Short-term on-the-job training</td>
        </tr>
    </tbody>
</table>

<p>Read that honestly: the occupation is <strong>shrinking by more than a fifth over ten years</strong>, and it requires no credential to enter. Those two facts belong together. A job with no entry barrier and a declining headcount is a job you should treat as a starting point, not a destination.</p>

<p>The destination is one rung up. <strong>Sales representatives of services</strong> &mdash; the closer role you are booking meetings for &mdash; numbered <strong>1,272,700</strong> in 2025 and is projected to grow <strong>2.7 percent</strong> to 1,306,700 by 2035, with around 115,700 openings a year and a median wage of <strong>$69,990</strong>. It asks for a high school diploma and moderate-term on-the-job training. That is roughly double the telemarketer median for a job you reach by doing this one well.</p>

<p>One more official figure, because it describes the day rather than the career. In 2025, external verbal interactions were required <strong>every few minutes</strong> for <strong>68.1 percent</strong> of civilian workers in sales and related occupations, according to the Occupational Requirements Survey. The estimate is preliminary. It is also the clearest statistical description of this job you will find: there is no quiet part of the shift.</p>

<h2>American Calling Law Applies to You in Lahore</h2>

<p>This is the section most guides skip, and it is the one that can end a campaign or a career. Two separate American rule sets govern calls into the United States, and both are written to reach the caller wherever they sit.</p>

<p>The statute behind the TCPA makes it unlawful for <strong>"any person within the United States, or any person outside the United States if the recipient is within the United States"</strong> to place certain calls. That clause is not an interpretation; it is the wording of the law.</p>

<p>The FTC says the same about its Telemarketing Sales Rule: <strong>"it makes no difference whether the calls are made from outside the United States; so long as they are made to consumers in the United States."</strong> And it extends liability beyond the caller, to anyone who provides "substantial assistance or support" to a seller or telemarketer while knowing, or consciously avoiding knowing, that a violation is happening.</p>

<h3>The rules that bite most often</h3>

<ul>
    <li><strong>Calling hours.</strong> Without prior consent, calls to a person's residence are prohibited outside <strong>8:00 a.m. to 9:00 p.m., local time at the called person's location</strong>. From Pakistan that means computing the US time zone per area code, every call.</li>
    <li><strong>The first sentence of the call.</strong> The rule requires prompt, clear disclosure of the identity of the seller, that the purpose of the call is to sell goods or services, and the nature of those goods or services.</li>
    <li><strong>The do-not-call list, and the interval.</strong> The registry must be scrubbed against a version obtained <strong>no more than 31 days before the call is made</strong>, with records documenting the process. The FCC's parallel rule uses the identical 31-day wording.</li>
    <li><strong>Consent for automated dialing to mobiles.</strong> Autodialed or prerecorded telemarketing to a cellular number requires prior express written consent &mdash; a signed agreement naming the number.</li>
    <li><strong>Opting out.</strong> Since 11 April 2025, a consumer may revoke consent by <strong>any reasonable method</strong>, the request must be honoured within <strong>ten business days</strong>, and a caller may not designate an exclusive channel for opting out. Words like "stop", "quit", "end", "revoke", "opt out", "cancel" or "unsubscribe" in a reply text count automatically.</li>
    <li><strong>Records.</strong> Call records, scripts and service contracts must be kept for <strong>five years</strong>, with times recorded to the closest second in UTC.</li>
</ul>

<p>Two corrections worth carrying, because both are stated wrongly in most articles you will read.</p>

<p><strong>Records are kept for five years, not two.</strong> The FTC's own guidance page still says two years. The binding rule was amended in April 2024 and now says five. Where a guidance page and the rule disagree, the rule governs.</p>

<p><strong>The "one-to-one consent" rule is not law.</strong> It was adopted in 2023, challenged, and vacated by the Eleventh Circuit, whose mandate issued on <strong>30 April 2025</strong>. It never took effect, and the FCC has reinstated the previous definition of consent. Any 2026 article describing it as a current requirement is out of date.</p>

<h3>Is business-to-business calling exempt?</h3>

<p>Mostly, and this is why so much Pakistani work is B2B. Calls between a telemarketer and a business to induce a purchase are exempt from the do-not-call scrubbing, the 8-to-9 window, the oral disclosures and the record-keeping. But the exemption has two holes you must know:</p>

<ol>
    <li><strong>Misrepresentation is never exempt.</strong> The ban on false or misleading statements made to induce payment, and on misrepresenting material information such as total cost, survives the B2B exemption entirely. A B2B cold caller who lies is in breach, full stop.</li>
    <li><strong>The exemption turns on who actually answered</strong>, not on what the campaign was called. Sole traders and home businesses frequently sit on residential lines registered on the do-not-call list, and the rules require the caller to record whether each call was to a consumer or a business. The burden of showing it was B2B falls on the caller.</li>
</ol>

<h3>What it costs when it goes wrong</h3>

<p>Civil penalties under the FTC Act reach <strong>$53,088 per violation</strong> for penalties assessed after 17 January 2025. Separately, a consumer can sue under the TCPA for <strong>$500 for each violating call</strong>, and a court <em>may</em>, at its discretion, increase that to up to three times the amount if the violation was wilful or knowing. Note the word "may" &mdash; trebling is discretionary, not automatic, and articles that say damages "triple automatically" are overstating it.</p>

<p>Per call, multiplied by a dialer doing 100 to 150 attempts a day, is how a compliance failure becomes a company-ending number. That is also why serious US clients push scripts, scrub schedules and call-recording requirements down onto their offshore teams. If your employer has none of those things, that is information about your employer.</p>

<h2>What This Means for You, Practically</h2>

<p>You are unlikely to be the defendant. The seller on whose behalf the calls are made carries the liability, and the rules say so explicitly for do-not-call failures. But three things follow for you personally:</p>

<ul>
    <li><strong>A script that tells you to misrepresent who you are is a red flag about the whole operation</strong>, not a quirk. It is the one thing no exemption covers.</li>
    <li><strong>An employer with no scrub process is an employer whose client will eventually leave</strong>, taking your job with it. Ask how often the list is refreshed; the answer should be at least every 31 days.</li>
    <li><strong>Never spoof caller ID or let anyone tell you to.</strong> The same statute that reaches offshore callers covers transmitting misleading caller identification with intent to defraud or wrongfully obtain value.</li>
</ul>

<h2>Getting Good at the Part That Is Actually Skill</h2>

<p>The measurable skill in this job is not talking. It is getting through the first fifteen seconds without being hung up on, and then qualifying honestly enough that the meeting you book is one the closer thanks you for.</p>

<p>Three things separate the people who last from the people who burn out in a quarter:</p>

<ul>
    <li><strong>Open by disclosing.</strong> The law requires you to say who you are and that you are selling. Setters who treat that as an obstacle sound evasive and get hung up on faster. Setters who say it plainly in the first sentence and then ask one specific question do better on both compliance and conversion.</li>
    <li><strong>Book fewer, better meetings.</strong> You will be measured on meetings held, not meetings booked. Booking somebody who was never going to buy costs you the metric that matters and costs the closer an hour.</li>
    <li><strong>Log the disposition honestly.</strong> Whether the call connected, was dropped or was transferred is a required record. It is also the only way anyone can tell whether a list is bad or you are.</li>
</ul>

<p>The research half of the work &mdash; building and cleaning the list you dial &mdash; is a separate craft with its own market, covered in <a href="/blog/b2b-lead-research-jobs">B2B Lead Research Jobs</a> and <a href="/blog/contact-list-building-jobs">Contact List Building Jobs</a>.</p>

<h2>How to Apply</h2>

<p>Both Pakistani boards carry these roles, and they behave differently. Search <a href="https://www.rozee.pk/">https://www.rozee.pk/</a> for "appointment setter" and separately for "appointment setting", which surfaces cold caller and SDR titles the narrower phrase misses. Open each listing rather than trusting the search summary, and read the posting date on the page itself.</p>

<p>A closed listing on that board does not always redirect you elsewhere. It will also load the page normally and put a line inside it stating that the employer is no longer accepting CVs. Several listings that search engines presented as current had closed months or years earlier.</p>

<p>When you apply, ask three questions before the interview ends: which market you will dial, what the guaranteed portion of the pay is, and who maintains the do-not-call scrub. The answers tell you more about the employer than the advert does.</p>

<p>If you have no experience at all, the adjacent entry points are covered in <a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a>.</p>

<h2>Frequently Asked Questions</h2>

<h3>What does an appointment setter actually do?</h3>
<p>Calls people who were not expecting the call, establishes quickly whether there is a genuine need, and books a meeting for a closer to run. You are measured on meetings that are attended rather than on dials or on bookings. In Pakistan the role is usually attached to an American, British or Australian client, which is what sets the shift.</p>

<h3>How much do appointment setters earn in Pakistan?</h3>
<p>Advertised salaries in live listings on 23 September 2026 ran from PKR 30,000 to PKR 150,000 a month, clustering at PKR 50,000 to 60,000 for candidates with under a year of experience. The highest figures went to roles dialling the United States. Several listings add commission on top, usually described as uncapped and rarely quantified.</p>

<h3>Do appointment setter jobs really pay $17 to $22.50 an hour?</h3>
<p>Not in any listing we could find. We checked every dollar figure across 20 live listings on both Pakistani boards, and the only US dollar hourly rate open to a Pakistan-based setter was $3.00 an hour, attached to a role also paying milestone bonuses of $10, $25 and $465 at different stages. Treat the higher figures as unsourced until you see them in an advert you can open.</p>

<h3>Is appointment setting a night shift job?</h3>
<p>Almost always. Every listing on one board stated an evening or night shift, and most of the rest named a US, UK or Australian market, which requires one. Advertised shifts included 7:00 PM to 4:00 AM Monday to Friday on site, and US Eastern evening windows six days a week. Treat the shift as the defining condition of the role.</p>

<h3>Do American calling laws apply to someone dialling from Pakistan?</h3>
<p>Yes, by their own wording. The TCPA statute covers "any person within the United States, or any person outside the United States if the recipient is within the United States", and the FTC states that it makes no difference whether the calls originate outside the US so long as they reach US consumers. The client who commissioned the campaign carries the main liability, but the rules also reach anyone giving substantial assistance while knowing of a violation.</p>

<h3>What are the US calling hours and the do-not-call rule?</h3>
<p>Without prior consent, calls to a residence are prohibited outside 8:00 a.m. to 9:00 p.m. local time at the called person's location. The national do-not-call registry must be scrubbed using a version obtained no more than 31 days before any call, with records kept of the process. Business-to-business calls are largely exempt from both, but not from the ban on misrepresentation.</p>

<h3>How long must telemarketing records be kept?</h3>
<p>Five years. Many articles and the FTC's own older guidance page still say two years, but the rule was amended in April 2024 and now requires five years for call records, scripts and service contracts, with call times recorded to the closest second in UTC. Where the guidance page and the rule disagree, the rule governs.</p>

<h3>Is appointment setting a good long-term career?</h3>
<p>As a starting point rather than a destination. US employment in the nearest occupation, telemarketers, is projected to fall 21.4 percent between 2025 and 2035, from 60,900 to 47,800, and it requires no formal credential to enter. The step up is the closing role: sales representatives of services numbered 1.27 million in 2025, are projected to grow 2.7 percent, and carry a median wage of $69,990 against $35,450 for telemarketers.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Appointment setter jobs in Pakistan salary</li>
    <li>Cold calling jobs Lahore night shift</li>
    <li>Remote appointment setter jobs for Pakistanis</li>
    <li>What is the difference between SDR and appointment setter</li>
    <li>US do not call registry rules for call centers</li>
    <li>TCPA rules for international call centers</li>
    <li>Commission only appointment setting jobs</li>
    <li>How to pass an appointment setter interview</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/lead-generation-assistant-jobs">Lead Generation Assistant Jobs</a> &mdash; the written side of outreach, the pay ladder, and the rules covering email and LinkedIn.</li>
    <li><a href="/blog/b2b-lead-research-jobs">B2B Lead Research Jobs</a> &mdash; how the list you dial gets built, and why verification vendors cannot verify what they claim.</li>
    <li><a href="/blog/contact-list-building-jobs">Contact List Building Jobs</a> &mdash; the quota arithmetic behind list work and what the free tool tiers really allow.</li>
    <li><a href="/blog/how-to-find-lead-generation-jobs-on-linkedin">How to Find Lead Generation Jobs on LinkedIn</a> &mdash; searching for these roles without wasting weeks.</li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a> &mdash; realistic entry points and what they pay.</li>
</ul>
HTML;
    }
}
