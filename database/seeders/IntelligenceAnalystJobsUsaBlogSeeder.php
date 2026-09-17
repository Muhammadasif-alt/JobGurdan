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
 * "Intelligence Analyst Jobs in USA" — the US market guide, priced from the
 * 2026 General Schedule and the nearest BLS proxy, and written to be honest
 * with an overseas audience about the citizenship wall.
 *
 * Corrections and additions to the draft (checked 15 September 2026 against
 * OPM 2026 GS tables, BLS OEWS May 2025, ODNI, Executive Order 12968 and
 * agency hiring pages):
 *
 * 1. It gives salary bands with no source. There is no BLS "intelligence
 *    analyst" wage series; the nearest official proxy is detectives and
 *    criminal investigators (SOC 33-3021), May 2025 median $93,790. Federal
 *    civilian analysts are paid on the General Schedule, quoted below with
 *    locality, because base-only figures understate pay by 17 to 34 per cent.
 *
 * 2. It treats the role as broadly open. Federal intelligence work requires a
 *    security clearance, and a clearance requires US citizenship (Executive
 *    Order 12968); non-citizens are effectively ineligible. This matters for
 *    this guide's mostly overseas readers, so it is stated plainly.
 *
 * 3. "Security clearance ranging from Secret to Top Secret/SCI." Intelligence
 *    Community roles generally need Top Secret plus SCI and often a polygraph,
 *    not merely a Secret clearance.
 *
 * 4. The Intelligence Community has 18 members (ODNI), and some agencies (the
 *    CIA) recruit only through their own portal, not USAJOBS.
 *
 * 5. Its apply link searches Indeed with a query string rather than the site's
 *    own intelligence analyst search page.
 *
 * The banners are used as supplied; the text carries the corrections. Both
 * records use updateOrCreate, so re-running is safe; it overwrites admin-panel
 * edits to these two rows.
 */
class IntelligenceAnalystJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-intelligence-analyst-jobs.html';

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
        $title = 'Intelligence Analyst Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Federal intelligence analysts are paid on the GS scale (GS-9 is about $70,600 in DC), there is no BLS "intelligence analyst" wage, and every role needs a security clearance, which requires US citizenship. Here is what the job pays and who can get it.',
                'content' => $content,
                'featured_image' => 'blogs/intelligence-analyst-jobs-in-usa.jpg',
                'tags' => 'intelligence analyst jobs usa, intelligence analyst salary, cia fbi nsa jobs, security clearance jobs, ts sci clearance, gs pay scale 2026, federal intelligence jobs, all source analyst',
                'meta_title' => 'Intelligence Analyst Jobs USA 2026: Pay, Clearance, Routes',
                'meta_description' => 'Intelligence analyst jobs in the USA: 2026 GS pay with locality, the BLS proxy, the TS/SCI clearance and US-citizenship rule, agencies and how to apply.',
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
            ['name' => 'U.S. Federal Agencies & Defense Contractors (Aggregated)'],
            ['type' => 'Government', 'display_reference' => 'us-intelligence-analyst-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'government-public-sector'],
            ['name' => 'Government & Public Sector']
        );

        Job::updateOrCreate(
            [
                'position' => 'Intelligence Analyst — Federal Agencies and Cleared Defense Contractors, U.S.',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time; secure-facility work with occasional shift or deployment requirements',
                'language' => 'English',
                // Federal analysts sit on the General Schedule; contractor pay
                // varies with clearance, so no single range is quoted here.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Intelligence analyst roles with US federal agencies and cleared defense contractors. US citizenship and a security clearance are required.',
                'seo_keywords' => 'intelligence analyst jobs, all-source analyst jobs, security clearance jobs, cleared analyst jobs, federal intelligence jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>US federal agencies &mdash; the CIA, FBI, NSA, DIA and others &mdash; and the cleared defense contractors that support them hire intelligence analysts to collect, evaluate and interpret information for decision-makers.</p>

<h3>What the work involves</h3>
<p>Gathering data from open-source, signals and human sources, finding patterns and threats in it, writing assessments and briefings, and presenting findings to leadership, often under deadline and always inside secure systems.</p>

<h3>Requirements</h3>
<ul>
    <li><strong>US citizenship</strong> &mdash; required for a security clearance and for almost all intelligence roles</li>
    <li>At least a bachelor's degree, commonly in international relations, political science or a national security field</li>
    <li>The ability to obtain a clearance, usually Top Secret with SCI, including a background investigation and often a polygraph</li>
    <li>Strong analytical writing and briefing skills; a relevant foreign language is an advantage</li>
</ul>

<h3>How the pay works</h3>
<ul>
    <li><strong>The General Schedule.</strong> Civilian analysts are graded on the GS scale; entry is often GS-7 or GS-9, journey level GS-11 or GS-12, with locality pay on top</li>
    <li><strong>Cleared contractors</strong> often pay a premium above the GS scale, especially for an active clearance</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Start the clearance conversation early,</strong> and be ready for a long background investigation. An active clearance makes you far more competitive.</p>

<p><strong>Note:</strong> pay, clearance and eligibility are set by each agency &mdash; not by JobGader. Confirm the requirements on the agency's own site before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Intelligence analysts turn scattered information into judgements that leaders act on, across national security, law enforcement and private-sector risk. It is demanding, well-paid work with real impact. It is also one of the hardest US careers to enter, for one reason most guides mention only in passing: almost every role needs a security clearance, and a clearance needs US citizenship. Before you plan around this career, it helps to know what it really pays, what the clearance involves, and whether you are eligible at all.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-intelligence-analyst-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128269; Browse Intelligence Analyst Jobs &rarr;
    </a>
</div>

<div style="border-left:4px solid #b91c1c;background:#fef2f2;padding:14px 18px;margin:24px 0;border-radius:6px;">
<p style="margin:0;"><strong>Read this first if you are not a US citizen.</strong> A security clearance can only be granted to US citizens (Executive Order 12968), and nearly every intelligence analyst role requires one. There is a narrow Limited Access Authorization for rare specialist skills, but in practice this is not a route for non-citizens or a visa-sponsored job. If you are not a US citizen or working toward citizenship, our other guides will serve you better.</p>
</div>

<h2>What an Intelligence Analyst Does</h2>

<p>The core work is consistent across employers:</p>

<ul>
    <li>Collecting and analysing information from open-source (OSINT), signals and human sources</li>
    <li>Finding patterns, trends and threats in large, often ambiguous data</li>
    <li>Writing reports, briefings and assessments for leaders and operators</li>
    <li>Monitoring geopolitical, criminal or cyber developments</li>
    <li>Supporting investigations with background research and risk assessments</li>
    <li>Briefing senior officials, sometimes under real time pressure</li>
</ul>

<p>What differs is the setting: military, federal law enforcement, homeland security, corporate risk or cyber threat intelligence each shape the day differently.</p>

<h2>What Intelligence Analysts Earn</h2>

<p>Start with an honest caveat: <strong>there is no BLS "intelligence analyst" occupation</strong>, so any single "average salary" you see online comes from job-ad aggregators, not official data. The nearest official proxy is <strong>detectives and criminal investigators (SOC 33-3021)</strong>, which bundles federal investigators with local detectives. Its May 2025 figures:</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">BLS 33-3021 (May 2025)</th>
            <th style="padding:10px;text-align:left;">Annual</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Lowest 10 per cent earn less than</td><td style="padding:10px;">$55,390</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Median</strong></td><td style="padding:10px;"><strong>$93,790</strong></td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Mean (average)</td><td style="padding:10px;">$99,430</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">Highest 10 per cent earn more than</td><td style="padding:10px;">$160,540</td></tr>
    </tbody>
</table>
</div>

<h3>The number that actually governs federal pay: the GS scale</h3>

<p>Most civilian federal analysts are paid on the <strong>General Schedule (GS)</strong>. The 2026 base salaries (step 1) are below, but base pay is never the whole story: <strong>locality pay adds 17 to 34 per cent</strong> on top, depending on where you work. The right-hand column shows the Washington-Baltimore locality (+33.94 per cent in 2026), where much of this work is based.</p>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Grade (step 1)</th>
            <th style="padding:10px;text-align:left;">2026 base</th>
            <th style="padding:10px;text-align:left;">With DC locality</th>
            <th style="padding:10px;text-align:left;">Typical stage</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">GS-7</td><td style="padding:10px;">$43,106</td><td style="padding:10px;">~$57,700</td><td style="padding:10px;">Entry</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">GS-9</td><td style="padding:10px;">$52,727</td><td style="padding:10px;">~$70,600</td><td style="padding:10px;">Entry with a master's or experience</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">GS-11</td><td style="padding:10px;">$63,795</td><td style="padding:10px;">~$85,400</td><td style="padding:10px;">Developmental</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">GS-12</td><td style="padding:10px;">$76,463</td><td style="padding:10px;">~$102,400</td><td style="padding:10px;">Journey (full performance)</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">GS-13</td><td style="padding:10px;">$90,925</td><td style="padding:10px;">~$121,800</td><td style="padding:10px;">Senior</td></tr>
    </tbody>
</table>
</div>

<p>Against this, the draft's bands &mdash; roughly $55,000 to $70,000 at entry, $70,000 to $95,000 experienced, and $95,000 to $130,000-plus senior &mdash; are broadly right, and map onto GS-7/9, GS-11/12 and GS-12/13 with locality. <strong>Cleared contractor roles often pay more</strong> than the equivalent GS grade, especially for an active TS/SCI clearance, which is in short supply.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/intelligence-analyst-jobs-in-usa-desk.jpg"
         alt="An intelligence analyst studying maps and dashboards on multiple monitors in a US office, beside an Intelligence Analyst Jobs in USA banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Security Clearance, and the Citizenship Wall</h2>

<p>This is the gate, and it is worth understanding before anything else:</p>

<ul>
    <li><strong>The levels are Confidential, Secret and Top Secret.</strong> Intelligence Community work generally needs <strong>Top Secret plus Sensitive Compartmented Information (TS/SCI)</strong>, and often a <strong>polygraph</strong> (counterintelligence or full-scope).</li>
    <li><strong>A clearance requires US citizenship.</strong> Executive Order 12968 limits access to classified information to US citizens. A narrow Limited Access Authorization exists for rare expertise, but it is the exception, not a route in.</li>
    <li><strong>The investigation takes time.</strong> Expect a thorough background check, so candidates who already hold an active clearance are prioritised for time-sensitive roles.</li>
</ul>

<h2>Where Intelligence Analysts Work</h2>

<ul>
    <li><strong>The Intelligence Community.</strong> The ODNI lists <strong>18 member organisations</strong>, including the CIA, FBI, NSA and DIA.</li>
    <li><strong>The military.</strong> Army, Navy, Air Force, Marine Corps and Space Force intelligence units.</li>
    <li><strong>Homeland security and law enforcement.</strong> DHS, state and local fusion centres, and police intelligence units.</li>
    <li><strong>Private-sector corporate intelligence.</strong> Risk consultancies, banks and multinationals assessing geopolitical and competitive risk &mdash; often hiring people with prior government or military experience.</li>
    <li><strong>Cyber threat intelligence.</strong> Security firms and contractors tracking cyber risk.</li>
</ul>

<h2>Qualifications and Skills</h2>

<p>Entry usually needs at least a <strong>bachelor's degree</strong>, commonly in international relations, political science, criminal justice or national security studies; a master's helps for senior or specialised roles. There is <strong>no single licensing body</strong> for intelligence analysts &mdash; no licence or national exam. Employers look for:</p>

<ul>
    <li>Strong analytical and critical thinking</li>
    <li>Clear writing and briefing that distils complex information</li>
    <li>Attention to detail and pattern recognition in ambiguous data</li>
    <li>Discretion with classified and sensitive material</li>
    <li>Composure under deadline</li>
    <li>Familiarity with analytical tools, databases and sometimes GIS</li>
    <li>Regional or language expertise for a specific area of focus</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/intelligence-analyst-jobs-in-usa-analysis.jpg"
         alt="An analyst reviewing global maps and data visualisations on screens in a secure office, beside an Intelligence Analyst Jobs in USA banner"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Where to Find Intelligence Analyst Jobs</h2>

<ol>
    <li><strong>USAJOBS.</strong> The federal government's official employment site lists many intelligence and homeland security roles &mdash; but not all.</li>
    <li><strong>Agency portals.</strong> The CIA and NSA recruit through their own sites (cia.gov/careers, nsa.gov/careers), and the ODNI runs intelligencecareers.gov for the wider community. The CIA does not post on USAJOBS.</li>
    <li><strong>Defense contractor career pages.</strong> Major contractors post cleared analyst roles directly.</li>
    <li><strong>Job boards.</strong> Search "all-source analyst" and "cleared analyst" as well as "intelligence analyst".</li>
    <li><strong>Military transition programs.</strong> Many analysts move from military intelligence through veteran hiring initiatives.</li>
</ol>

<h2>Tips for Landing an Intelligence Analyst Job</h2>

<ul>
    <li><strong>Confirm you are eligible first.</strong> If you cannot hold a clearance, focus elsewhere before investing in applications.</li>
    <li><strong>Start the clearance process early.</strong> Even an interim clearance makes you more competitive.</li>
    <li><strong>Highlight relevant study or research</strong> in security, geopolitics or a region.</li>
    <li><strong>Prepare for scenario questions</strong> on analysing incomplete information.</li>
    <li><strong>Sharpen your writing.</strong> Briefing skill is often tested directly in hiring.</li>
    <li><strong>Consider a military or law-enforcement route</strong> as a path to clearance-eligible civilian roles later.</li>
</ul>

<h2>Career Progression</h2>

<p>The path runs from analyst to senior analyst, then team lead or intelligence supervisor, regional or subject-matter expert, intelligence officer where agencies split the tracks, or director of intelligence or risk in the private sector. Analysts often move between government, military and contractor roles, carrying a clearance and specialism with them &mdash; which is why the clearance, once earned, is as valuable as the experience.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do intelligence analysts earn in the USA?</h3>
<p>Federal analysts are paid on the GS scale: GS-9 is about $70,600 and GS-12 about $102,400 in the Washington locality for 2026. There is no BLS "intelligence analyst" wage; the nearest proxy, detectives and criminal investigators, had a May 2025 median of $93,790.</p>

<h3>Can a non-US citizen become an intelligence analyst?</h3>
<p>In almost all cases, no. A security clearance requires US citizenship under Executive Order 12968, and nearly every role needs a clearance. A narrow Limited Access Authorization exists for rare expertise, but this is not a visa-sponsored route.</p>

<h3>What clearance do intelligence analysts need?</h3>
<p>Most Intelligence Community roles need Top Secret with SCI (TS/SCI), and often a polygraph, rather than only a Secret clearance.</p>

<h3>Is there a BLS salary for intelligence analysts?</h3>
<p>No. BLS has no dedicated series; single "average" figures online come from job-ad sites. The closest official occupation is detectives and criminal investigators.</p>

<h3>What degree do I need?</h3>
<p>Usually at least a bachelor's, commonly in international relations, political science or national security. A master's helps for senior roles. There is no licence for the role.</p>

<h3>Which agencies hire intelligence analysts?</h3>
<p>The 18 members of the Intelligence Community, including the CIA, FBI, NSA and DIA, plus the military, DHS, fusion centres, contractors and private risk firms.</p>

<h3>Do all these jobs post on USAJOBS?</h3>
<p>No. Many do, but the CIA and some others recruit only through their own portals, such as cia.gov/careers and intelligencecareers.gov.</p>

<h3>Does an active clearance raise my pay?</h3>
<p>Often, on the contractor side. An active TS/SCI clearance is in short supply, so cleared contractor roles frequently pay a premium over the equivalent GS grade.</p>

<h2>People Also Search For</h2>

<h3>Intelligence analyst salary</h3>
<p>GS-based for federal roles (GS-9 about $70,600 in DC for 2026); the BLS proxy median is $93,790.</p>

<h3>How to become an intelligence analyst</h3>
<p>A bachelor's degree, US citizenship, and the ability to obtain a TS/SCI clearance.</p>

<h3>CIA analyst jobs</h3>
<p>Recruited through cia.gov, requiring US citizenship, residency at application and a polygraph.</p>

<h3>TS/SCI clearance jobs</h3>
<p>The common requirement for Intelligence Community roles; an active clearance is highly sought after.</p>

<h3>All-source analyst jobs</h3>
<p>A common title for analysts who combine multiple intelligence sources; search it alongside "intelligence analyst".</p>

<h3>Cyber threat intelligence analyst</h3>
<p>The cyber corner of the field, often paid toward the top of the range.</p>

<h3>GS pay scale 2026</h3>
<p>GS-7 base $43,106 to GS-13 base $90,925, before locality pay of 17 to 34 per cent.</p>

<h3>Intelligence analyst jobs for non-citizens</h3>
<p>Effectively unavailable, because a clearance requires US citizenship.</p>

<h2>More Job Guides</h2>

<p>Looking at nearby analytical and federal careers? These cover them:</p>

<ul>
    <li><a href="/blog/cybersecurity-analyst-jobs-in-usa">Cybersecurity Analyst Jobs in USA</a> &mdash; the cyber threat side, and the certifications it takes.</li>
    <li><a href="/blog/federal-police-jobs-in-usa">Federal Police Jobs in USA</a> &mdash; FBI, CBP and other federal roles, their pay and age limits.</li>
    <li><a href="/blog/police-officer-jobs-in-usa">Police Officer Jobs in USA</a> &mdash; the state and local law-enforcement route.</li>
    <li><a href="/blog/data-scientist-jobs-in-usa">Data Scientist Jobs in USA</a> &mdash; the analytical career in the private sector, priced from BLS.</li>
    <li><a href="/blog/law-enforcement-jobs-in-usa">Law Enforcement Jobs in USA</a> &mdash; patrol, detective, transit, game warden and federal paths compared, with BLS pay and NYPD, FBI and DEA entry rules.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers or immigration advice. GS pay tables, locality rates, clearance rules and agency requirements change. Confirm current details with OPM, ODNI and the hiring agency before applying.</p>
HTML;
    }
}
