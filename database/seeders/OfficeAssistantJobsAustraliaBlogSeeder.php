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
 * "Office Assistant Jobs in Australia" — administrative support work, and the
 * indoor companion to the construction guide. The two share a country and a
 * seniority level and disagree about one thing readers get wrong constantly:
 * which Australian work credential travels between states.
 *
 * Corrections to the draft:
 *
 * 1. It says Sydney "tends to sit above the national average" and gives
 *    Sydney as $59,000 to $60,500, three paragraphs after giving the national
 *    average as $62,200. Sydney is below its own stated average.
 *
 * 2. One of its quoted range floors, $50,475, is below a full-time year at
 *    the national minimum wage of $1,004.90 a week from 1 July 2026, which
 *    annualises to $52,254.80.
 *
 * 3. It says most roles are "quoted with superannuation on top" without
 *    saying which of its four averages include it. At the 12 per cent
 *    guarantee rate that is $7,464 on a $62,200 figure.
 *
 * 4. It lists a National Police Check and a Working with Children Check as
 *    one bullet. The police check is national; working with children checks
 *    are state-issued and are not transferable, and Queensland does not
 *    recognise interstate checks at all.
 *
 * 5. It requires full working rights without telling an international reader
 *    what that means for this occupation, on a site whose readers are mostly
 *    outside Australia.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class OfficeAssistantJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-office-assistant-jobs.html';

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
        $title = 'Office Assistant Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The published Sydney figure is below the same guide national average, one quoted range floor is below the legal minimum wage, and the background check everyone lists as routine does not travel between states.',
                'content' => $content,
                'featured_image' => 'blogs/office-assistant-jobs-in-australia.jpg',
                'tags' => 'office assistant jobs australia, administration assistant jobs, receptionist jobs australia, office administrator jobs, entry level office jobs, office assistant salary australia, working with children check, admin jobs sydney',
                'meta_title' => 'Office Assistant Jobs in Australia',
                'meta_description' => 'Office assistant jobs in Australia: what the salary figures really say, whether super is inside them, and which background checks travel between states.',
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
            ['name' => 'Australian Corporate, Council & Professional Offices (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'au-office-assistant-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'customer-support-admin'],
            ['name' => 'Customer Support & Admin']
        );

        Job::updateOrCreate(
            [
                'position' => 'Office Assistant — Reception, Administration and Office Support, Australian Employers',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Standard business hours, with part-time and set-day arrangements common',
                'language' => 'English',
                // Published averages differ by more than $6,000 and do not
                // agree on whether superannuation is included, so no single
                // band would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Reception, administration and office support roles with Australian employers. Confirm whether the quoted figure includes superannuation before comparing.',
                'seo_keywords' => 'office assistant jobs australia, administration assistant jobs, receptionist jobs australia, office administrator jobs, admin jobs sydney',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Law firms, healthcare providers, logistics companies, councils, universities and general corporate offices across Australia hire office assistants and administration assistants. The function is not tied to one sector, which is why it advertises steadily through every part of the economic cycle and why it is one of the more reliable entry points into Australian office work without a degree.</p>

<h3>What the work involves</h3>
<p>Answering and directing calls, greeting visitors, managing general correspondence, maintaining filing systems and records, ordering and monitoring supplies, coordinating meeting rooms and office maintenance, and supporting other departments with administrative tasks. Some roles add event, meeting and travel coordination.</p>

<h3>Requirements</h3>
<ul>
    <li>Microsoft Word, Excel and Outlook &mdash; named in almost every listing</li>
    <li>Clear written and verbal communication</li>
    <li>Prior administration, reception or customer-facing experience, usually desirable rather than mandatory</li>
    <li>Full working rights in Australia, stated explicitly on most listings</li>
    <li>A National Police Check, which is nationally recognised</li>
    <li>In education, healthcare and community services, a working with children or vulnerable people check <strong>for the state you will work in</strong></li>
</ul>

<h3>How the pay is quoted</h3>
<ul>
    <li><strong>Published averages sit between about $56,000 and $62,500</strong> depending on the source, and they disagree by more than $6,000 for the same job title</li>
    <li><strong>Superannuation is 12 per cent</strong> and Australian advertisements split on whether it is inside the quoted figure or on top of it</li>
    <li><strong>The floor is the national minimum wage</strong>, $1,004.90 a week from 1 July 2026, which annualises to about $52,255 for a full-time employee</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask whether the figure includes superannuation, and check which state's clearance the role needs.</strong> The super question is worth about $7,464 on a $62,200 figure, and a working with children check from another state will not transfer &mdash; Queensland does not recognise interstate checks at all.</p>

<p><strong>Note:</strong> pay, hours, clearance requirements and eligibility are set by each employer and by state law &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Office assistant is one of the steadiest jobs in Australia to get hired into and one of the hardest to research honestly, because the published salary data for it contradicts itself in a way that is easy to miss. This page walks through what the numbers actually say, the deduction that changes all of them, and the one requirement that will stop you starting a job you have already accepted.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-office-assistant-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; Browse Office Assistant Jobs in Australia &rarr;
    </a>
</div>

<h2>Sydney Is Not Above the National Average</h2>

<p>Read these two claims from the same salary section, in the order they normally appear.</p>

<p>First: the national average for an office assistant is about <strong>$62,200</strong> a year.</p>

<p>Then, a few paragraphs later: <strong>"Sydney tends to sit above the national average (~$59,000&ndash;$60,500/year)."</strong></p>

<p>Both numbers in that Sydney range are <strong>below $62,200</strong>. The sentence describes Sydney as above an average that the same article has already put above Sydney.</p>

<p>This is not a trivial slip, because Sydney is where a large share of these jobs are and it is the most expensive city in the country to live in. A reader planning around "Sydney pays above average" is planning around the opposite of what the data on the page says.</p>

<p>The likely explanation is mundane: the national figure and the city figure come from different sources with different samples, and were assembled into one paragraph without conversion. Which is exactly the problem with the whole table.</p>

<h2>Four Averages, and One Below the Legal Minimum</h2>

<p>Here is what the platforms say about this job:</p>

<ul>
    <li>Indeed &mdash; about <strong>$62,200</strong>, range $52,880 to $83,624</li>
    <li>Jora &mdash; about <strong>$62,500</strong></li>
    <li>SEEK &mdash; <strong>$60,000 to $70,000</strong></li>
    <li>Glassdoor &mdash; about <strong>$56,000</strong>, range $50,475 to $67,000</li>
</ul>

<p>The averages differ by about <strong>$6,500</strong>, which is manageable. The ranges are where the problem is.</p>

<p>Australia's <strong>National Minimum Wage from 1 July 2026 is $1,004.90 a week</strong>, which annualises to about <strong>$52,255</strong> for a full-time adult employee. Most office assistant roles are covered by the Clerks &mdash; Private Sector Award, which sets its own minimums above that floor.</p>

<p>Against $52,255:</p>

<ul>
    <li>Indeed's range floor of <strong>$52,880</strong> is essentially the legal minimum, about $625 above it.</li>
    <li>Glassdoor's range floor of <strong>$50,475</strong> is <strong>below the legal minimum</strong> for full-time work.</li>
</ul>

<p>So the bottom of these published ranges is not telling you what a low offer looks like. It is telling you the sample includes part-time roles, older data, or both. <strong>Anchor on the award or the minimum wage, and read everything above it as the real market.</strong></p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-assistant-jobs-in-australia-salary.jpg"
         alt="An office assistant working at a reception desk in an Australian office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Is Superannuation Inside That Number?</h2>

<p>Guides note that "most full-time roles are quoted with superannuation on top" and leave it there. They do not say whether their own averages include it, which means none of those four figures can be compared with an offer until you ask.</p>

<p>The <strong>Superannuation Guarantee is 12 per cent</strong>. On a $62,200 figure that is <strong>$7,464</strong> &mdash; larger than the entire spread between the four averages above, and larger than the gap between the national figure and the Sydney one.</p>

<p>So $62,200 is either $62,200 in your hand with $7,464 going into your fund, or about <strong>$55,536</strong> in your hand with the rest being super. Two materially different jobs wearing one number.</p>

<p><strong>Ask whether the rate is "plus super" or "inclusive of super", in writing.</strong> It is an ordinary Australian question and nobody will think it odd. The same question applies to any Australian role &mdash; our <a href="/blog/construction-worker-jobs-in-australia">construction worker jobs in Australia guide</a> covers it on the other side of the labour market, where the casual loading complicates it further.</p>

<h2>The Check That Does Not Travel Between States</h2>

<p>This is the practical trap in Australian administrative hiring, and guides bury it in a single bullet alongside something quite different.</p>

<p>They list, as one requirement: "A National Police Check and, in some sectors, a Working with Children or Vulnerable Person Check." Those are two different animals.</p>

<p><strong>A National Police Check is national.</strong> The name means what it says, and it is portable across the country.</p>

<p><strong>Working with children checks are not.</strong> They are issued by each state and territory, they are <strong>not transferable between them</strong>, and the names differ &mdash; a Working with Children Check in New South Wales, Victoria, South Australia, the Northern Territory and Queensland, a <strong>Blue Card</strong> in Queensland specifically, and a Working with Vulnerable People check in the ACT and Tasmania.</p>

<p><strong>Queensland is the strictest case: it does not recognise checks issued by another state or territory at all.</strong> A valid NSW check does not let you start a Queensland role that requires one. Elsewhere, a valid check from your home state will usually cover brief interstate work, but not an ongoing job.</p>

<p>Two things follow. If you are applying across state lines into education, healthcare, community services or a council role, <strong>start the clearance for the destination state early</strong>, because processing time is what delays start dates. And if you already hold one, do not assume it counts &mdash; check the destination's rules before you tell an employer you are cleared.</p>

<p>It is worth contrasting this with the construction industry's White Card, which <em>is</em> recognised in every state and territory. Australians move between states constantly and assume all credentials behave the same way. They do not, and this is the one that catches people.</p>

<h2>Search Under Several Titles, Because They Are the Same Job</h2>

<p>Volume changes substantially depending on what you type. These titles overlap heavily in day-to-day duties:</p>

<ul>
    <li><strong>Office Assistant</strong> and <strong>Office Administrator</strong></li>
    <li><strong>Administration Assistant</strong> and <strong>Administrative Assistant</strong></li>
    <li><strong>Receptionist and Office Assistant</strong>, or <strong>Junior Receptionist</strong></li>
    <li><strong>Front Office Assistant</strong></li>
    <li><strong>Data Entry and Office Support</strong></li>
</ul>

<p>Searching only "Office Assistant" hides most of the market. Two of those titles will pay differently for identical work at different employers, which is another reason the published averages disagree.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/office-assistant-jobs-in-australia-checks.jpg"
         alt="Administrative staff working together in an Australian corporate office"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Work Rights: What "Full Working Rights" Rules Out</h2>

<p>Australian office assistant listings state a requirement for full working rights more consistently than almost any other entry-level category, so it is worth being direct about what that means for a reader outside Australia.</p>

<p><strong>Office assistant is not a skilled migration occupation.</strong> There is no employer-sponsored permanent route for general administrative support, and applying from overseas asking for sponsorship is not a plan that works for this role.</p>

<p>The people who realistically hold these jobs are citizens and permanent residents, partners and dependants with work rights, graduates on a post-study work visa, and <strong>Working Holiday visa holders</strong> &mdash; for whom office work is a genuine and under-used option, though it does not count as specified work towards a second-year visa the way regional construction or agriculture does.</p>

<p>If relocation is the goal rather than this job specifically, the routes that do carry sponsorship are in healthcare, trades and technical occupations, not office support.</p>

<h2>What Actually Gets You Shortlisted</h2>

<ul>
    <li><strong>Excel beyond data entry.</strong> Named in nearly every listing and rarely tested for, so demonstrating it separates you immediately.</li>
    <li><strong>Reception experience from anywhere</strong> &mdash; retail, hospitality, a clinic. The transferable half is dealing with people who arrive with a problem.</li>
    <li><strong>Specific software.</strong> Name the practice management, case management or booking system you have used rather than "computer literate".</li>
    <li><strong>Discretion.</strong> Legal, healthcare and council roles screen for it, and it is worth a line in your application rather than a bullet.</li>
    <li><strong>A Certificate III or IV in Business Administration.</strong> Not required, and useful for standing out on more senior office support roles.</li>
    <li><strong>Clearances already in progress.</strong> A candidate whose checks are underway can start sooner, and start dates decide more hires than r&eacute;sum&eacute;s do.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do office assistants earn in Australia?</h3>
<p>Published averages sit between about $56,000 and $62,500 depending on the source. The floor is the national minimum wage of $1,004.90 a week from 1 July 2026, which annualises to about $52,255 full-time.</p>

<h3>Does Sydney pay more than the national average?</h3>
<p>Not on the published figures. Sydney is commonly quoted at $59,000 to $60,500 while the national average is given as $62,200, which puts Sydney below it despite being described as above it.</p>

<h3>Does the advertised salary include superannuation?</h3>
<p>Australian advertisements split both ways, so ask. At the 12 per cent guarantee rate it is worth $7,464 on a $62,200 figure &mdash; more than the gap between the published averages.</p>

<h3>Does a Working with Children Check work in another state?</h3>
<p>No. They are issued by each state and territory and are not transferable. Queensland does not recognise interstate checks at all, so a Blue Card application is required there.</p>

<h3>Is a National Police Check the same thing?</h3>
<p>No. A National Police Check is nationally recognised and portable. The two are frequently listed in the same bullet point and behave completely differently.</p>

<h3>Do I need a qualification to be an office assistant in Australia?</h3>
<p>Usually not. A Certificate III or IV in Business Administration helps for more senior office support roles, but Microsoft Office skills and any customer-facing experience matter more for entry level.</p>

<h3>Can I get visa sponsorship as an office assistant in Australia?</h3>
<p>Realistically no. It is not a skilled migration occupation. The people holding these roles are citizens, permanent residents, partners and dependants, graduates on post-study work visas, and working holiday makers.</p>

<h3>Which job titles should I search for?</h3>
<p>Office Assistant, Office Administrator, Administration Assistant, Administrative Assistant, Receptionist and Office Assistant, and Front Office Assistant. They overlap heavily and searching one hides most of the market.</p>

<h2>People Also Search For</h2>

<h3>Office assistant salary Australia</h3>
<p>About $56,000 to $62,500 on published averages, and the superannuation question is worth more than the spread between them.</p>

<h3>Administration assistant jobs</h3>
<p>The same work under another title, often at a different rate. Search both.</p>

<h3>Receptionist jobs Australia</h3>
<p>Heavily overlapping duties, and the most common route into office support from retail or hospitality.</p>

<h3>Entry level office jobs Australia</h3>
<p>Genuinely open without a qualification. Excel and customer-facing experience do most of the shortlisting.</p>

<h3>Working with children check Australia</h3>
<p>State-issued and not transferable. Queensland requires its own Blue Card regardless of what you hold elsewhere.</p>

<h3>Office jobs Sydney and Melbourne</h3>
<p>The highest volume markets. Sydney sits below the national average on published figures despite being the most expensive city.</p>

<h3>Certificate III in Business Administration jobs</h3>
<p>Not required for entry level, and useful for senior office support and coordination roles.</p>

<h3>Office assistant jobs for working holiday visa</h3>
<p>A genuine and under-used option, though office work does not count as specified work for a second-year visa.</p>

<h2>More Job Guides</h2>

<p>Comparing Australian and international routes? These cover them:</p>

<ul>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the other Australian entry route, where the credential does transfer between states.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the same seniority level under Canadian provincial wage floors.</li>
    <li><a href="/blog/retail-jobs-in-usa">Retail Jobs in USA</a> &mdash; American entry-level work and its very different minimum wage picture.</li>
    <li><a href="/blog/data-entry-jobs-in-usa">Data Entry Jobs in USA</a> &mdash; the adjacent clerical occupation and its projection.</li>
    <li><a href="/blog/remote-customer-service-jobs">Remote Customer Service Jobs</a> &mdash; administrative skills applied remotely.</li>
    <li><a href="/blog/virtual-assistant-jobs-in-pakistan">Virtual Assistant Jobs in Pakistan</a> &mdash; the same skills paid in foreign currency from home.</li>
    <li><a href="/blog/factory-worker-jobs-in-germany">Factory Worker Jobs in Germany</a> &mdash; a European route and what its visa options really require.</li>
    <li><a href="/blog/security-guard-jobs-in-uae">Security Guard Jobs in UAE</a> &mdash; a Gulf route with sponsorship genuinely available.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; a licensed Australian trade, and how its interstate recognition differs from the checks here.</li>
    <li><a href="/blog/receptionist-jobs-in-uae">Receptionist Jobs in UAE</a> &mdash; front-office work in the Gulf, where the package split decides the gratuity.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, migration or financial advice. Award rates, the national minimum wage, superannuation rules and screening requirements change, and salary figures on any job board are a moving average rather than a statistic. Confirm the current position with the Fair Work Ombudsman, the relevant state screening authority and the employer's own advertisement before applying.</p>
HTML;
    }
}
