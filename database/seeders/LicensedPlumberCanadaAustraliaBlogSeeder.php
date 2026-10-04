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
 * "How to Become a Licensed Plumber in Canada or Australia" — the licensing
 * half of the plumber cluster. The Gulf guide owns recruitment cost and the
 * Saudi exam, the pay guide owns BLS and ONS earnings, and the existing
 * Plumber Jobs in Australia guide owns that country's vacancies. This page
 * owns the certification route itself in both countries.
 *
 * The draft was rewritten. What it carried could not be published:
 *
 * 1. Nine "Apply Now - Indeed" and SEEK buttons, and five named vacancies
 *    with employer pay quoted out of job-board listings.
 *
 * 2. A false claim about Express Entry, stated as the core immigration
 *    fact: that trade category draws "award an extra 600 CRS points if a
 *    province nominates you". Those are two unrelated mechanisms. A
 *    category-based round awards no points at all - it only restricts who
 *    is invited, and those candidates are still ranked on their existing
 *    score. The 600 points come solely from a provincial or territorial
 *    nomination, which is not granted through a category draw.
 *
 * 3. No mention of the change that matters most to a tradesperson: CRS
 *    points for arranged employment were removed on 25 March 2025. A job
 *    offer still establishes eligibility for the Federal Skilled Trades
 *    Program; it now scores zero.
 *
 * 4. "Certification is compulsory in Ontario, Alberta, Quebec, Nova Scotia,
 *    New Brunswick, Prince Edward Island and Saskatchewan" was correct and
 *    is kept, now sourced to the federal NOC profile and each provincial
 *    authority, with the voluntary six named as well.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class LicensedPlumberCanadaAustraliaBlogSeeder extends Seeder
{
    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch';

    private const AUSTRALIA_APPLY_URL = 'https://www.workforceaustralia.gov.au/individuals/jobs/search';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
    }

    private function seedBlogPost(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Guides on qualifications, pay, progression and how to get hired.',
            ]
        );

        $author = User::where('role', 'admin')->first();
        $title = 'How to Become a Licensed Plumber in Canada or Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Canada makes plumbing a compulsory trade in seven provinces and voluntary in six, with a 125-question Red Seal exam on top. Australia licenses it in every state separately. The immigration advice circulating about both is where most of the errors are.',
                'content' => $content,
                'featured_image' => 'blogs/licensed-plumber-canada-australia.jpg',
                'tags' => 'licensed plumber canada, plumber licence australia, red seal plumber exam, plumbing apprenticeship canada, certificate iii in plumbing, federal skilled trades program, noc 72300 plumber, trades recognition australia',
                'meta_title' => 'Licensed Plumber in Canada or Australia: Steps and Visas',
                'meta_description' => 'How to become a licensed plumber in Canada or Australia: the Red Seal exam, which provinces make it compulsory, state licensing, and the visa routes.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        $canadaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Canadian Mechanical Contractors & Apprenticeship Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'canada-plumber-aggregated']
        );

        $canadaLocation = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        Job::updateOrCreate(
            [
                'position' => 'Plumber and Plumbing Apprentice — Canadian Mechanical and Construction Contractors',
                'advertiser_id' => $canadaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $canadaLocation->id,
                'description' => $this->canadaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with apprenticeship terms alternating paid site work and block technical training',
                'language' => 'English',
                // Wages are set province by province and by apprenticeship
                // level under collective agreements, so no single range
                // describes the group.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::CANADA_APPLY_URL,
                'meta_description' => 'Plumber and plumbing apprentice roles with Canadian mechanical and construction contractors. Certification is compulsory in seven provinces.',
                'seo_keywords' => 'plumber jobs canada, red seal plumber, plumbing apprenticeship canada, journeyperson plumber, noc 72300',
            ]
        );

        $australiaAdvertiser = Advertiser::firstOrCreate(
            ['name' => 'Australian Plumbing Contractors & Facilities Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'australia-plumber-licence-aggregated']
        );

        $australiaLocation = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        Job::updateOrCreate(
            [
                'position' => 'Licensed Plumber and Apprentice — Australian Contractors and Facilities Maintenance',
                'advertiser_id' => $australiaAdvertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $australiaLocation->id,
                'description' => $this->australiaJobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with apprenticeships combining paid work and study at a registered training organisation',
                'language' => 'English',
                // Pay is set by the relevant modern award and by the state
                // licence a worker holds, so no single range applies.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::AUSTRALIA_APPLY_URL,
                'meta_description' => 'Licensed plumber and apprentice roles with Australian contractors and facilities employers. Every state and territory licenses plumbing separately.',
                'seo_keywords' => 'plumber jobs australia, plumbing licence australia, certificate iii in plumbing, plumbing apprenticeship australia, anzsco 334111',
            ]
        );
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Mechanical contractors, construction firms and service companies across Canada recruit journeyperson plumbers and register apprentices. Plumbers are NOC 72300, a TEER 2 occupation.</p>

<h3>Certification is compulsory in seven provinces</h3>
<ul>
    <li><strong>Compulsory:</strong> Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Saskatchewan and Alberta</li>
    <li><strong>Available but voluntary:</strong> Newfoundland and Labrador, Manitoba, British Columbia, Yukon, Northwest Territories and Nunavut</li>
</ul>

<h3>Apprenticeship</h3>
<p>Four to five years, combining paid site work with block technical training. Ontario sets the term at 9,000 hours, made up of 8,280 hours on the job and 720 hours in school across three levels. Alberta runs four periods of about a year, each with 1,560 hours of work experience and eight weeks of classroom instruction, and awards both a Plumber certificate and Gasfitter Class B.</p>

<h3>The Red Seal</h3>
<p>The interprovincial Red Seal examination for this trade has <strong>125 questions</strong>. The endorsement is a seal added to a provincial or territorial certificate showing the holder can practise across Canada; the compulsory part is the provincial certification, not the endorsement itself.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>A provincial Certificate of Qualification, or registration as an apprentice</li>
    <li>Red Seal endorsement, frequently preferred and sometimes required for travel between provinces</li>
    <li>A driving licence and clean abstract for service roles</li>
    <li>Gas certification where the province issues it alongside the plumbing ticket</li>
</ul>

<p><strong>Note:</strong> certification rules are set by each province and by the Canadian Council of Directors of Apprenticeship, not by JobGader. Confirm the requirement with the provincial apprenticeship authority before applying or relocating.</p>
JOBHTML;
    }

    private function australiaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Plumbing contractors, builders and facilities maintenance employers across Australia recruit licensed plumbers and apprentices. Plumbing is a licensed trade in every state and territory.</p>

<h3>The qualification</h3>
<p>The core trade qualification is the Certificate III in Plumbing, completed through an apprenticeship that combines paid work with study at a registered training organisation. A Construction Induction Card, commonly called the White Card, is required before working on a construction site.</p>

<h3>The licence</h3>
<p><strong>The qualification is not the licence.</strong> Each state and territory has its own plumbing regulator and its own licence classes, and a licence issued in one does not automatically authorise work in another. Contractor-level licensing, which allows you to take work in your own name and certify it, generally requires further qualification and documented experience on top of the trade certificate.</p>

<h3>Requirements employers list</h3>
<ul>
    <li>A current plumbing licence or registration for the state the work is in</li>
    <li>Certificate III in Plumbing, or an apprenticeship in progress</li>
    <li>A White Card for site work</li>
    <li>A driver's licence, with a vehicle usually supplied for service roles</li>
    <li>Endorsements for gas, drainage or roofing where the role covers them</li>
</ul>

<p><strong>Note:</strong> licence classes, scopes and fees are set by each state and territory regulator, not by JobGader. Confirm the requirement with the regulator for the state you intend to work in before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Canada and Australia both treat plumbing as a trade you are licensed to practise rather than one you simply claim. Getting there takes roughly the same four to five years in each, but the two systems hand out authority differently, and the immigration advice attached to them is where most published guidance goes wrong. This page separates the licence from the qualification, and the visa from the points.</p>

<h2>Canada: the Province Licenses You, the Red Seal Moves You</h2>

<p>Plumbers are <strong>NOC 72300</strong>, a <strong>TEER 2</strong> occupation. Two different credentials are often confused:</p>

<ul>
    <li><strong>The provincial Certificate of Qualification</strong> is what makes you a licensed plumber in that province. Where certification is compulsory, you cannot work unsupervised without it.</li>
    <li><strong>The Red Seal endorsement</strong> is a seal added to that certificate showing you can practise anywhere in Canada. It is an add-on, not a licence of its own, and the compulsory part is the provincial certificate.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/licensed-plumber-canada-australia-sink.jpg"
         alt="A plumber tightening a pipe under a kitchen sink, with the Canadian flag and Toronto skyline above the Australian flag and Sydney Harbour behind him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>Where certification is compulsory</h3>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Compulsory certification</th>
            <th style="padding:10px;text-align:left;">Available but voluntary</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Saskatchewan, Alberta</td><td style="padding:10px;">Newfoundland and Labrador, Manitoba, British Columbia, Yukon, Northwest Territories, Nunavut</td></tr>
    </tbody>
</table>
</div>

<p>Seven provinces require it; six provinces and territories do not. British Columbia is the one that surprises people: its compulsory trades list covers electricians, steamfitter-pipefitters, refrigeration mechanics, gasfitters and sheet metal workers, and <strong>plumber is not on it</strong>.</p>

<h3>The apprenticeship, in actual hours</h3>

<ul>
    <li><strong>Ontario.</strong> 9,000 hours in total: 8,280 hours of on-the-job experience and 720 hours of in-school training, delivered across three levels.</li>
    <li><strong>Alberta.</strong> Four periods of roughly a year, each with 1,560 hours of work experience and eight weeks of classroom instruction. Graduates receive a Plumber certificate <em>and</em> Gasfitter Class B.</li>
    <li><strong>Saskatchewan.</strong> Four levels of eight weeks' technical training, 1,800 hours a year, 7,200 trade hours and at least four years in the trade.</li>
</ul>

<p>The federal occupational profile puts it plainly: completion of a four to five year apprenticeship, or over five years of work experience plus some schooling in plumbing, is usually required to be eligible for trade certification.</p>

<h3>The Red Seal exam</h3>

<p>The interprovincial examination for plumbers has <strong>125 questions</strong>, weighted across the trade's major blocks. Provincial certifying exams, including Ontario's, are passed at <strong>70 per cent</strong>. The endorsement is optional in every province, but it is the only thing that lets a certificate travel without re-qualifying.</p>

<h2>The Canadian Immigration Advice That Is Wrong Almost Everywhere</h2>

<p>Nearly every guide to plumbing immigration repeats a version of this: "plumbing is one of the trades IRCC targets in category-based draws, which award an extra 600 CRS points if a province nominates you."</p>

<p><strong>That sentence welds together two unrelated mechanisms, and the result is false.</strong></p>

<ul>
    <li><strong>Category-based selection awards no points at all.</strong> In a category round, IRCC invites candidates who are eligible for a category established by the Minister. Those candidates are still ranked by the Comprehensive Ranking System score they already had. The category changes <em>who is drawn against which cut-off</em>; it does not change your score by a single point.</li>
    <li><strong>The 600 points come only from a provincial or territorial nomination.</strong> A nomination is granted by a province through its own programme. It has nothing to do with a category draw, and it is not awarded by one.</li>
</ul>

<p>Plumbers are genuinely inside the <strong>Trade occupations</strong> category, listed under NOC 72300. That is worth having, because it means you can be invited in a round with a lower cut-off than the general one. It is simply not worth 600 points.</p>

<h3>The change a tradesperson actually needs to know</h3>

<p><strong>CRS points for arranged employment were removed on 25 March 2025.</strong> A job offer used to be worth 50 points, or 200 for senior management. It is now worth zero in the ranking.</p>

<p>Crucially, that did not change eligibility. Where a job offer is part of the entry criteria for a programme &mdash; and it is one of the two ways into the Federal Skilled Trades Program &mdash; it still counts for that purpose. It just no longer lifts your score.</p>

<h3>The Federal Skilled Trades Program, as it stands</h3>

<ul>
    <li>At least <strong>two years of full-time work experience, or 3,120 hours</strong>, in a skilled trade within the five years before you apply</li>
    <li><strong>Either</strong> a valid job offer of full-time employment for at least one year, <strong>or</strong> a certificate of qualification issued by a Canadian provincial, territorial or federal authority</li>
    <li>Language at <strong>CLB 5 for speaking and listening and CLB 4 for reading and writing</strong></li>
    <li><strong>No education requirement</strong></li>
    <li>You must plan to live outside Quebec, which runs its own selection</li>
</ul>

<p>That second bullet is why the provincial certificate matters so much to an overseas plumber: it is an alternative to needing a Canadian employer to commit before you arrive.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/licensed-plumber-canada-australia-van.jpg"
         alt="A plumber working on pipework beside a fully stocked service van, with Canadian and Australian flags, the Toronto skyline and Sydney Harbour Bridge in the background"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Australia: the Qualification Is Not the Licence</h2>

<p>This is the distinction that catches migrating tradespeople, because in Australia the two are genuinely separate documents issued by different bodies.</p>

<ol>
    <li><strong>The Certificate III in Plumbing</strong> is the trade qualification, completed through an apprenticeship combining paid work with study at a registered training organisation. It takes about four years.</li>
    <li><strong>The Construction Induction Card</strong>, universally called the White Card, is required before you set foot on a construction site.</li>
    <li><strong>The state or territory licence</strong> is what authorises you to do plumbing work. Every state and territory regulates plumbing separately, with its own regulator, its own licence classes and its own scopes of work.</li>
    <li><strong>A contractor licence</strong>, which lets you take work in your own name and certify it rather than working under supervision, generally requires further qualification and documented experience on top of the trade certificate.</li>
</ol>

<h3>Does an Australian licence travel between states?</h3>

<p>Partly, and the detail matters. Under the <strong>Automatic Mutual Recognition</strong> scheme a licence held in one state or territory can be used to do the same work in other participating jurisdictions without applying for a second licence. Three limits apply, and they are exactly the ones that catch plumbers:</p>

<ul>
    <li><strong>Queensland does not participate</strong> in the scheme.</li>
    <li><strong>Notification is required before you start.</strong> Victoria, for example, requires a deemed-notification form through the regulator's portal before any work begins.</li>
    <li><strong>Specific plumbing classes are carved out.</strong> In Victoria, gasfitting, fire protection, mechanical services, roofing (stormwater), Type A appliance conversion and servicing, and Type B gasfitting classes are all excluded from AMR.</li>
</ul>

<p>So the licence may travel, but the class you hold and the state you are heading to decide whether it does. Check both before accepting work across a border.</p>

<h3>Coming from overseas</h3>

<ul>
    <li><strong>A skills assessment is not the same as a licence.</strong> Trades Recognition Australia assesses whether your overseas training and experience match the Australian qualification. Passing it does not let you work unsupervised; the state regulator still decides that.</li>
    <li><strong>Recognition of Prior Learning</strong> can shorten the path to a Certificate III by crediting work you have already done, but it does not remove the licence step.</li>
    <li><strong>The skills assessment requirement differs by visa.</strong> Points-tested skilled visas generally require a positive assessment before you apply; employer-sponsored routes may not, because the employer is vouching for the role instead.</li>
    <li><strong>Plan the licence into your timeline.</strong> Arriving on a valid visa with no state licence means you cannot legally do the work you were hired for.</li>
</ul>

<h2>Canada or Australia: Which Route Suits You</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;"></th>
            <th style="padding:10px;text-align:left;">Canada</th>
            <th style="padding:10px;text-align:left;">Australia</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Training length</strong></td><td style="padding:10px;">4 to 5 years</td><td style="padding:10px;">About 4 years</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Who licenses you</strong></td><td style="padding:10px;">The province or territory</td><td style="padding:10px;">The state or territory</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Is it compulsory everywhere?</strong></td><td style="padding:10px;">No &mdash; 7 provinces compulsory, 6 voluntary</td><td style="padding:10px;">Yes &mdash; every state and territory licenses plumbing</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Does it travel internally?</strong></td><td style="padding:10px;">With the Red Seal endorsement, yes</td><td style="padding:10px;">Often, under Automatic Mutual Recognition &mdash; but not to Queensland, and several plumbing classes are excluded</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Overseas credential route</strong></td><td style="padding:10px;">Provincial certificate of qualification, which also satisfies FSTP</td><td style="padding:10px;">Trades Recognition Australia assessment, then a state licence</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Does a job offer score points?</strong></td><td style="padding:10px;">No, not since 25 March 2025 &mdash; but it still establishes FSTP eligibility</td><td style="padding:10px;">Employer sponsorship is a separate visa route rather than a points add-on</td></tr>
    </tbody>
</table>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do I need the Red Seal to work as a plumber in Canada?</h3>
<p>No. The Red Seal endorsement is optional everywhere. What can be compulsory is the provincial certificate of qualification, and that is required in Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Saskatchewan and Alberta.</p>

<h3>How many questions are in the Red Seal plumber exam?</h3>
<p>125. Provincial certifying examinations, including Ontario's, are passed at 70 per cent.</p>

<h3>Is plumbing a compulsory trade in British Columbia?</h3>
<p>No. BC's compulsory trades are construction and industrial electricians, powerline technicians, steamfitter-pipefitters, refrigeration and air conditioning mechanics, gasfitters and sheet metal workers. Plumber is not among them.</p>

<h3>How long is a Canadian plumbing apprenticeship?</h3>
<p>Four to five years. Ontario requires 9,000 hours, made up of 8,280 on the job and 720 in school. Saskatchewan requires 7,200 trade hours over at least four years with four eight-week technical levels.</p>

<h3>Do Express Entry trade draws give you 600 extra points?</h3>
<p>No. Category-based rounds award no CRS points at all; they only decide who is invited, and those candidates are ranked on the score they already have. The 600 points come solely from a provincial or territorial nomination, which is granted separately.</p>

<h3>Does a job offer still help a plumber under Express Entry?</h3>
<p>For eligibility, yes; for points, no. CRS points for arranged employment were removed on 25 March 2025, but a job offer of at least one year is still one of the two ways to qualify for the Federal Skilled Trades Program.</p>

<h3>What do I need for the Federal Skilled Trades Program?</h3>
<p>Two years of full-time experience, or 3,120 hours, in the trade within the last five years; either a one-year job offer or a Canadian certificate of qualification; CLB 5 speaking and listening with CLB 4 reading and writing; and a plan to live outside Quebec. There is no education requirement.</p>

<h3>Is a Certificate III in Plumbing enough to work in Australia?</h3>
<p>No. The certificate is the trade qualification; the licence is issued separately by the state or territory regulator, and it is the licence that authorises the work. A licence from one state does not automatically authorise work in another.</p>

<h2>People Also Search For</h2>

<h3>Red Seal plumber exam</h3>
<p>125 questions; the endorsement lets a provincial certificate travel across Canada.</p>

<h3>Plumbing apprenticeship Canada</h3>
<p>Four to five years; Ontario 9,000 hours, Saskatchewan 7,200 trade hours.</p>

<h3>Compulsory trades by province</h3>
<p>Plumber is compulsory in seven provinces and voluntary in six jurisdictions.</p>

<h3>NOC 72300 plumber</h3>
<p>TEER 2, and the code listed in the Express Entry Trade occupations category.</p>

<h3>Federal Skilled Trades Program requirements</h3>
<p>3,120 hours in five years, plus a job offer or a Canadian certificate of qualification.</p>

<h3>Express Entry 600 points</h3>
<p>From a provincial nomination only. Category draws award none.</p>

<h3>Certificate III in Plumbing</h3>
<p>The Australian trade qualification, taken through an apprenticeship with an RTO.</p>

<h3>Automatic Mutual Recognition plumbers</h3>
<p>Lets a licence work interstate, but Queensland is out and several plumbing classes are excluded.</p>

<h2>More Job Guides</h2>

<p>The rest of the plumbing cluster, and the routes around it:</p>

<ul>
    <li><a href="/blog/electrician-jobs-abroad-with-visa-sponsorship">Electrician Jobs Abroad with Visa Sponsorship</a> &mdash; the same two countries for electricians, where certification comes first.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; the vacancies, the award floor and the visa routes in detail.</li>
    <li><a href="/blog/plumber-salary-in-the-uk-and-usa">Plumber Salary in the UK and USA</a> &mdash; the BLS and ONS figures, and why other sources disagree.</li>
    <li><a href="/blog/plumber-jobs-in-uae-and-saudi-arabia">Plumber Jobs in UAE and Saudi Arabia</a> &mdash; who pays the recruitment cost, and the Saudi trade exam.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; how LMIA-based sponsorship works alongside Express Entry.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-australia">Visa Sponsorship Jobs in Australia</a> &mdash; employer sponsorship and the lists it runs on.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the award floor and the credential that travels.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or employment advice. Certification, licensing and immigration rules in Canada and Australia change; confirm the current position with the provincial apprenticeship authority, the state plumbing regulator, canada.ca and immi.homeaffairs.gov.au before applying or relocating.</p>
HTML;
    }
}
