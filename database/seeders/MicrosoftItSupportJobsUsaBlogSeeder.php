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
 * "How to Apply for Microsoft IT Support Jobs in the USA" — an employer guide
 * whose two decisive facts are both absent from the draft: Microsoft's own
 * postings say these roles are not eligible for visa sponsorship, and the
 * careers platform the draft links was retired.
 *
 * Corrections to the draft (checked against careers.microsoft.com,
 * apply.careers.microsoft.com, military.microsoft.com and bls.gov,
 * 23 September 2026):
 *
 * 1. The draft never mentions US work authorisation. Every frontline US
 *    support posting checked carries Microsoft's own sentence "This position
 *    is not eligible for visa sponsorship", and several add a US citizenship
 *    verification requirement. For this readership that is the headline.
 *
 * 2. All three of the draft's apply links point at the retired platform.
 *    careers.microsoft.com/?l=en_us and its variants now 302 to a marketing
 *    homepage and silently discard the query parameters, and
 *    jobs.careers.microsoft.com 301s to apply.careers.microsoft.com with the
 *    search terms stripped. The current board is apply.careers.microsoft.com.
 *
 * 3. The draft opens with Corporate Technology Support as the headline career
 *    path. Microsoft's own profession filter returns zero openings in that
 *    category, worldwide and in the US. The category exists on the marketing
 *    page and has no jobs in it.
 *
 * 4. The draft says pay "varies" and stops. Microsoft publishes an explicit
 *    numeric range on every US posting. The frontline range is USD 23.65 to
 *    37.07 an hour; the IC6 Technical Support Engineering range of USD
 *    130,900 to 277,200 belongs to roles asking for 15+ years and must not be
 *    presented as support pay.
 *
 * 5. The draft tells readers to search "Desktop Support", "Help Desk" and
 *    "Endpoint Support". Microsoft does not use those titles; the searches
 *    return commercial and legal roles instead.
 *
 * 6. The draft lists Fort Lauderdale as a US location. Microsoft files it
 *    under Latin America.
 *
 * 7. The draft treats "Engineering, Development, and Services" as three
 *    professions. It is one filter group heading.
 *
 * 8. The draft presents MSSA as an opportunity without its eligibility rule:
 *    US or UK forces service members and veterans only.
 *
 * 9. The draft omits that BLS projects computer support specialist employment
 *    to decline 3 percent over the decade.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MicrosoftItSupportJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://apply.careers.microsoft.com/careers';

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
        $title = 'How to Apply for Microsoft IT Support Jobs in the USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Microsoft prints the pay on every US posting, from USD 23.65 an hour at the front line upward. It also prints a sentence most guides skip: these roles are not eligible for visa sponsorship. Here is what is really on the board and who can apply.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-microsoft-it-support-jobs-in-the-usa.jpg',
                'tags' => 'microsoft jobs, microsoft it support jobs, microsoft careers usa, technical support engineer, data center technician, microsoft visa sponsorship, help desk jobs usa, mssa',
                'meta_title' => 'Microsoft IT Support Jobs USA: Pay and Who Can Apply',
                'meta_description' => 'Microsoft IT support jobs in the USA: the hourly pay Microsoft publishes, why these roles are not eligible for visa sponsorship, and the current careers site.',
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
            ['name' => 'Microsoft Corporation, US Support and Data Centre Roles'],
            ['type' => 'Company', 'display_reference' => 'microsoft-us-support']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'IT Support and Data Centre Technician, Microsoft, USA',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; frontline support and data centre roles are fully on-site and may include shift rotas',
                'language' => 'English',
                // Microsoft publishes a numeric range on every US posting, but
                // the range differs by level and by metro area, and the guide
                // quotes those figures directly rather than flattening them
                // into one band here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'IT support, technical support and data centre technician roles with Microsoft across the United States, for candidates who already hold US work authorisation.',
                'seo_keywords' => 'microsoft it support jobs, microsoft careers usa, technical support engineer microsoft, data center technician jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Microsoft hires IT support, technical support and data centre technician staff across the United States, covering end-user troubleshooting, customer technical support and the physical operation of its cloud data centres.</p>

<h3>What the work involves</h3>
<p>Diagnosing hardware, software, network and identity problems, restoring service, documenting cases, escalating to engineering teams, and, in data centre roles, installing, replacing and decommissioning server hardware on site.</p>

<h3>Common requirements</h3>
<ul>
    <li>Each posting sets its own qualifications; Microsoft states there is no single education rule</li>
    <li>Troubleshooting ability, clear communication and structured documentation</li>
    <li>Availability for on-site work; frontline support and data centre roles are listed as fully on-site</li>
    <li>Existing authorisation to work in the United States, and for some roles verified US citizenship</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Note:</strong> pay ranges, work-site rules and eligibility are set and published by Microsoft on each individual posting &mdash; not by JobGader. Microsoft states that frontline US support positions are not eligible for visa sponsorship. Applying to Microsoft is free; any request for payment is a scam.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Microsoft publishes the pay on every single US job posting, which makes this one of the easiest large employers to research honestly.</strong> It also publishes a sentence that almost every guide to these jobs leaves out, and that sentence decides whether the rest of the article is any use to you.</p>

<p>We checked Microsoft's live vacancy board, its professions and locations directories, its military careers pages and the US Bureau of Labor Statistics on <strong>23 September 2026</strong>. Two things had changed since the version of this article that circulates elsewhere was written: the careers platform, and the availability of the headline job category.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://apply.careers.microsoft.com/careers" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#0067b8;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128187; Open the Microsoft Careers Board &rarr;
    </a>
</div>

<h2>Read This Before Anything Else: Sponsorship</h2>

<p>We opened the full description of every US support posting Microsoft's own searches surfaced. <strong>Most of them carry this sentence, word for word:</strong></p>

<p style="border-left:4px solid #0067b8;padding-left:16px;margin:20px 0;"><strong>"This position is not eligible for visa sponsorship. Candidates must have authorization to work in the United States that does not now or in the future require employer sponsorship."</strong></p>

<p>Several go further and require verified US citizenship, using this wording: the position "requires verification of U.S. citizenship due to citizenship-based legal restrictions", and "citizenship will be verified via a valid passport."</p>

<p>Roughly two thirds of the current US support postings are closed to an overseas applicant on one of those two grounds. And the pattern is not random. <strong>Every genuine entry-level frontline role we found carries the no-sponsorship clause</strong> &mdash; the Data Center Technician roles in Phoenix, the Senior Data Center Technician IT Support role in Quincy, Washington, the Critical Environment Support Assistant in Wenatchee. All of them are also listed as fully on-site.</p>

<p>So the honest summary is this: <strong>there is no remote, sponsored, entry-level Microsoft support job in the United States to apply for from Pakistan.</strong> These jobs are for people who already hold a green card, US citizenship, or another status that carries work authorisation without employer sponsorship.</p>

<p>That is not a reason to close the tab. It is a reason to aim at the routes that are actually open. If you want IT support work you can genuinely reach, <a href="/blog/entry-level-it-jobs">entry level IT jobs</a> and <a href="/blog/it-support-jobs-in-uk">IT support jobs in the UK</a> cover markets with different rules, and <a href="/blog/remote-jobs-in-pakistan-with-no-experience">remote jobs in Pakistan with no experience</a> covers the work that does not need a visa at all.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-microsoft-it-support-jobs-in-the-usa-helpdesk.jpg" alt="A Microsoft support engineer working on a headset at a multi-monitor desk" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Every frontline US support role on the board is listed as fully on-site.</figcaption>
</figure>

<h2>The Careers Site Moved. Most Links You Will Find Are Broken.</h2>

<p>Microsoft has migrated its recruiting platform, and the guides written before the move now send readers nowhere useful. Here is what actually happens when you follow the old links:</p>

<ul>
    <li><code>careers.microsoft.com/?l=en_us</code> redirects to a generic marketing homepage. The <code>l=en_us</code> parameter is a leftover from the retired system; it is carried through the redirect and then ignored.</li>
    <li><code>jobs.careers.microsoft.com</code> returns a permanent redirect to the new board and <strong>strips your search terms on the way</strong>, so a carefully built search URL lands you on a bare landing page.</li>
    <li>The old search page at <code>careers.microsoft.com/v2/global/en/search.html</code> returns a 404 page.</li>
</ul>

<p><strong>The board that actually works is <code>apply.careers.microsoft.com/careers</code>.</strong> That is where every Microsoft-owned page now points. The older <code>careers.microsoft.com</code> domain is still alive and still maintained, but it is now an information hub &mdash; professions, locations, hiring tips &mdash; rather than a place to search vacancies.</p>

<p>One caveat Microsoft itself publishes on its hiring pages: a migration notice warning that "it may take a while for all of our job postings to show up on the Microsoft Careers Site." Treat any vacancy count, including the ones below, as a dated snapshot rather than a permanent fact.</p>

<h2>What Is Actually Open, and the Number Traps</h2>

<p>This section matters more than it sounds, because Microsoft's search box will happily hand you a large, confident and completely wrong number.</p>

<p>The board carries <strong>2,370 jobs worldwide and 1,195 in the United States</strong> across every function. If you then type "IT Support" into the keyword box you get 249 US results, and "Corporate Technology Support" returns 973. <strong>Neither figure means what it appears to mean.</strong> The search is a fuzzy relevance ranker, not a filter: it matches individual words and returns enormous irrelevant supersets. "IT Support" surfaces IT Director and IT Auditor in its top results. "Support Engineer" surfaces Principal Software Engineer. "Corporate Technology Support" is simply matching the words corporate, technology and support separately, which is why it returns a Senior Corporate Strategy manager.</p>

<p>Microsoft's own profession taxonomy gives the defensible numbers, and they are far smaller:</p>

<ul>
    <li><strong>Technical support</strong> &mdash; 57 roles worldwide, 4 in the United States. Two of those four are Principal Data Scientist roles bleeding into the category, so <strong>there are two genuine US Technical Support Engineer vacancies</strong>.</li>
    <li><strong>Corporate technology support</strong> &mdash; <strong>zero</strong> openings. Worldwide and in the US.</li>
    <li><strong>Customer success</strong> &mdash; 125 worldwide, 32 in the US.</li>
    <li><strong>Consulting services</strong> &mdash; 98 worldwide, 23 in the US.</li>
    <li><strong>Data centre</strong> &mdash; 211 worldwide, 54 in the US. This is where the frontline hands-on work actually is.</li>
</ul>

<p>That third bullet deserves emphasis, because it undoes the premise of most articles on this subject. <strong>Corporate Technology Support is the category every guide names as the closest match to traditional internal IT support, and it currently contains no jobs at all.</strong> The label exists on Microsoft's professions page and the "explore jobs" button beneath it leads to an empty result set. It is a description of a job family, not a place to apply.</p>

<p>Real titles on the board today, so you know what to look for: Senior Data Center Technician &ndash; IT Support in Quincy, Washington; Data Center Technician in Phoenix, Arizona; Critical Environment Support Assistant in Wenatchee, Washington; and Technical Support Engineer &ndash; Tooling Lead across Redmond, Charlotte, Fargo and Las Colinas.</p>

<h2>What Microsoft Actually Pays</h2>

<p>Microsoft publishes an explicit numeric range on every US posting, with a second, higher range for the San Francisco Bay Area and New York City. These are Microsoft's own figures, quoted from current postings:</p>

<ul>
    <li><strong>Data Center Technician and Critical Environment Support Assistant</strong> (Phoenix, Wenatchee): <strong>USD 23.65 to 37.07 an hour</strong>, rising to USD 31.54 to 40.91 in the Bay Area and New York City.</li>
    <li><strong>Senior Data Center Technician, IT Support</strong> (Quincy, Washington): <strong>USD 34.13 to 56.54 an hour</strong>, rising to USD 45.24 to 63.61 in those two metros.</li>
    <li><strong>Technical Support Engineer at IC6</strong>: USD 130,900 to 277,200 a year, rising to USD 165,600 to 303,600 in the Bay Area and New York City.</li>
</ul>

<p><strong>Do not let that last line mislead you, and be suspicious of anyone who quotes it without context.</strong> The IC6 Technical Support Engineering roles ask for fifteen or more years of technical support, consulting or IT experience, and are principal-level programme roles wearing a support job title. They are not what anyone means by an IT support job. The realistic frontline number is the first bullet: <strong>USD 23.65 to 37.07 an hour, on site, without sponsorship</strong>.</p>

<h2>Is That Good? The BLS Benchmark</h2>

<p>The US Bureau of Labor Statistics publishes the independent figures, and they tell a fuller story than the pay alone:</p>

<ul>
    <li><strong>Computer user support specialists</strong> &mdash; median annual wage <strong>USD 61,860</strong>, employment 750,600.</li>
    <li><strong>Computer network support specialists</strong> &mdash; median <strong>USD 76,220</strong>, employment 152,500.</li>
    <li><strong>All computer support specialists</strong> &mdash; median <strong>USD 62,890</strong>, employment 903,100.</li>
</ul>

<p>At 40 hours a week, Microsoft's frontline range of USD 23.65 to 37.07 an hour works out at roughly USD 49,000 to 77,000 a year, so the band straddles the national median rather than sitting above it.</p>

<p>The part almost nobody publishes is the direction of travel. <strong>BLS projects overall employment of computer support specialists to decline 3 per cent between 2025 and 2035</strong>, a loss of about 26,200 jobs, with user support falling and network support roughly flat. About 48,700 openings a year are still expected, mostly replacing people who leave. That does not make it a bad career, but it does mean the growth story you will read elsewhere is not supported by the official projection, and it is worth knowing before you build a five-year plan around it.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-microsoft-it-support-jobs-in-the-usa-remote.jpg" alt="A support specialist working from a home desk with Microsoft service icons" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Remote appears higher up the ladder, not at the support entry level.</figcaption>
</figure>

<h2>How Microsoft Groups Its Support Jobs</h2>

<p>Microsoft's professions directory is worth reading because it tells you which words to search. Its own descriptions, in its own sentence case:</p>

<ul>
    <li><strong>Corporate technology support</strong> &mdash; "Serve on the Microsoft frontline, leveraging your understanding of end-user technology to help diagnose and troubleshoot issues locally and globally." Currently empty, as above.</li>
    <li><strong>Technical support</strong> &mdash; "Our technical support professionals help to improve products and onboard customers as they learn to leverage new products, providing technical solutions that align with customer needs."</li>
    <li><strong>Customer success</strong> &mdash; helping customers achieve digital transformation and drive change management.</li>
    <li><strong>Consulting services</strong> &mdash; customer-facing delivery work on Microsoft technologies.</li>
</ul>

<p>One correction to a claim that travels widely: "Engineering, development, and services" is <strong>not</strong> three professions. It is one of nine filter group headings, and the professions inside it are software engineering, hardware engineering, security engineering, data centre and quantum computing. There is no Microsoft profession called Development or Services.</p>

<h2>Which Search Terms Work, and Which Waste Your Time</h2>

<p>Most guides hand you a list of fifteen search terms. We ran them. Three of the most commonly recommended are dead:</p>

<ul>
    <li><strong>"Help Desk"</strong> &mdash; 5 US results, none of them help desk roles. The top hits are Commercial Executive, Business Value Lead and Principal Legal Engineer.</li>
    <li><strong>"Desktop Support"</strong> &mdash; 11 US results, none of them desktop support roles.</li>
    <li><strong>"Endpoint Support"</strong> &mdash; same problem.</li>
</ul>

<p>Microsoft simply does not use those titles. <strong>The terms that return real matches are Technical Support, Support Engineer, Data Center Technician and Customer Success</strong>, and the reliable way to search is the profession filter rather than the keyword box.</p>

<h2>Where the Jobs Are, and One Location Error</h2>

<p>Microsoft's own North America locations directory lists exactly seven entries: Seattle Area, Atlanta, Bay Area, D.C. Metro Area, New England, New York and Vancouver.</p>

<p><strong>Fort Lauderdale is not a US location on Microsoft's directory.</strong> Microsoft files it under Latin America, alongside Mexico City, San Jose and Sao Paulo, because it is their LATAM hub. Guides that list it among US locations have misread the page.</p>

<p>More usefully, the directory is a marketing grouping and not a list of places jobs exist. The actual postings name many more cities: Quincy and Wenatchee in Washington, Phoenix in Arizona, Fargo in North Dakota, Charlotte in North Carolina, Las Colinas and Abilene in Texas, Cheyenne in Wyoming, Fayetteville in Georgia. Data centre work is where Microsoft's frontline hiring is concentrated, and data centres are not in city-centre offices.</p>

<h2>Remote, Hybrid or On-Site?</h2>

<p>Every Microsoft posting carries an explicit work-site field, which makes this easy to answer accurately. Across the US support postings we checked, the values were "Fully on-site", "3 days per week in-office", "4 days per week in-office" and "0 days per week in-office".</p>

<p>The pattern is consistent: <strong>every frontline support and data centre role is fully on-site</strong>. Fully remote appears further up the ladder, on architect and specialist roles, not at support entry level. "Remote" in a Microsoft posting also still carries a country and often a set of eligible states, so read the work-site block before you assume it means anywhere.</p>

<h2>Contractors, Veterans and the MSSA</h2>

<p>Two side doors get mentioned constantly. Both are narrower than they look.</p>

<p><strong>Contractor roles.</strong> Microsoft's contractor page is live and genuine, but the US link takes you off Microsoft entirely, to a third-party platform called TalentNet Community. Contractors engaged that way are <strong>not Microsoft employees</strong>; they are placed through supplier staffing firms. Check who the employer of record is before you hand over documents.</p>

<p><strong>The Microsoft Software and Systems Academy.</strong> MSSA is alive and running &mdash; a 17-week programme with cohorts scheduled through 2027 in Cybersecurity Operations, Cloud Application Development, and Server and Cloud Administration. But its eligibility rule is the part guides omit: it is for <strong>service members and veterans of US or UK forces</strong>, within six months of separation or retirement, plus Guard, Reserve and military spouses. No Pakistani applicant qualifies. The same page advertises cleared technology careers, which require an active US security clearance and therefore US citizenship.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li><strong>Check your work authorisation first.</strong> If you would need Microsoft to sponsor you, the frontline US support roles are closed. Read the sponsorship block on the posting itself; it is near the bottom, under the pay range.</li>
    <li><strong>Go to the current board</strong> at apply.careers.microsoft.com. Ignore any link that starts with jobs.careers.microsoft.com or ends in <code>?l=en_us</code>.</li>
    <li><strong>Filter by profession, not keyword.</strong> Use Technical Support or Data Center, then add the United States. The keyword box will inflate your results with unrelated senior roles.</li>
    <li><strong>Create a profile.</strong> Microsoft says it gives personalised recommendations, job alerts and saved jobs, and lets you track applications in the Action Center. Microsoft also says the AI match indicator "is meant to guide you, not decide for you" and guarantees no outcome. If you upload a resume without a profile, Microsoft states it is purged within 24 hours.</li>
    <li><strong>Read the work-site field before the salary.</strong> Fully on-site in Wenatchee is a different life from three days a week in Charlotte.</li>
    <li><strong>Apply early.</strong> Postings state they stay open "for a minimum of 5 days, with applications accepted on an ongoing basis until the position is filled."</li>
    <li><strong>Expect two to four interviews.</strong> Microsoft says most interview loops involve two to four conversations with potential teammates and cross-functional colleagues, each up to an hour.</li>
</ol>

<p>For the underlying skills, <a href="/blog/help-desk-technician-jobs-in-usa">help desk technician jobs in USA</a> covers the ladder these roles sit on, and <a href="/blog/how-to-apply-for-telkom-indonesia-it-jobs">Telkom Indonesia IT jobs</a> covers a large employer in a market with very different entry rules.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does Microsoft sponsor visas for IT support jobs in the USA?</h3>
<p>Not for frontline support roles. Microsoft's own postings state: "This position is not eligible for visa sponsorship. Candidates must have authorization to work in the United States that does not now or in the future require employer sponsorship." Several support roles go further and require verified US citizenship. Microsoft does sponsor for some senior engineering roles, but those are a different job.</p>

<h3>What does Microsoft pay for IT support in the USA?</h3>
<p>Microsoft publishes a range on every US posting. Data Center Technician and Critical Environment Support Assistant roles pay USD 23.65 to 37.07 an hour, and Senior Data Center Technician IT Support pays USD 34.13 to 56.54 an hour, with higher bands in the San Francisco Bay Area and New York City. The USD 130,900 to 277,200 figure that circulates belongs to IC6 roles requiring fifteen or more years of experience.</p>

<h3>Why do the Microsoft careers links in other guides not work?</h3>
<p>Because Microsoft migrated its recruiting platform. Links to careers.microsoft.com with an <code>l=en_us</code> parameter now redirect to a marketing homepage and discard the parameter, and jobs.careers.microsoft.com permanently redirects to the new board while stripping the search terms. The working board is apply.careers.microsoft.com/careers.</p>

<h3>How many Microsoft IT support jobs are open in the USA?</h3>
<p>Fewer than the search box suggests. Using Microsoft's own profession filter there are four US technical support listings, of which two are genuine support roles, and 54 US data centre roles. The Corporate Technology Support category has zero openings worldwide. Keyword searches return much larger numbers because the search ranks on relevance rather than filtering.</p>

<h3>Are Microsoft IT support jobs remote?</h3>
<p>Not at entry level. Every frontline support and data centre posting we checked lists a work site of "Fully on-site". Remote and low-office-day arrangements appear on senior architect and specialist roles. Each posting carries an explicit work-site field, so check it rather than assuming.</p>

<h3>Do I need a degree for a Microsoft IT support job?</h3>
<p>There is no single rule; each posting sets its own qualifications. For context, the US Bureau of Labor Statistics says network support specialists typically need an associate degree and user support specialists typically need some college courses, rather than a four-year degree.</p>

<h3>Is IT support a growing career in the USA?</h3>
<p>Not according to the official projection. BLS expects employment of computer support specialists to decline 3 per cent between 2025 and 2035, about 26,200 fewer jobs, while still generating roughly 48,700 openings a year mostly from people leaving the occupation.</p>

<h3>Can I join the Microsoft Software and Systems Academy?</h3>
<p>Only if you served in the US or UK armed forces. MSSA is a 17-week programme for transitioning service members, veterans, Guard and Reserve members and military spouses of US or UK MoD forces, generally within six months of separation. It is not open to civilian applicants from other countries.</p>

<h2>People Also Search For</h2>

<ul>
    <li>Microsoft data center technician salary</li>
    <li>Microsoft technical support engineer requirements</li>
    <li>Does Microsoft sponsor H1B for support roles</li>
    <li>apply.careers.microsoft.com search not working</li>
    <li>Microsoft careers profession filter</li>
    <li>Entry level IT support jobs no degree USA</li>
    <li>MSSA eligibility requirements</li>
    <li>Microsoft contractor roles TalentNet</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/help-desk-technician-jobs-in-usa">Help Desk Technician Jobs in USA</a></li>
    <li><a href="/blog/entry-level-it-jobs">Entry Level IT Jobs</a></li>
    <li><a href="/blog/it-support-jobs-in-uk">IT Support Jobs in UK</a></li>
    <li><a href="/blog/how-to-apply-for-telkom-indonesia-it-jobs">How to Apply for Telkom Indonesia IT Jobs</a></li>
    <li><a href="/blog/remote-jobs-in-pakistan-with-no-experience">Remote Jobs in Pakistan with No Experience</a></li>
    <li><a href="/blog/system-administrator-jobs-in-uae">System Administrator Jobs in UAE</a></li>
    <li><a href="/blog/how-to-apply-for-google-data-center-jobs-in-the-usa">How to Apply for Google Data Center Jobs in the USA</a> &mdash; technician work Google prices openly, with no sponsorship on any US posting.</li>
</ul>
HTML;
    }
}
