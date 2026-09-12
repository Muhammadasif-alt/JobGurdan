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
 * "Carpenter Jobs in USA" — the largest of the American building trades, and
 * the one whose careers guides repeat two pieces of advice that send readers
 * looking for things that do not exist.
 *
 * Corrections to the draft:
 *
 * 1. "OSHA safety certification" is not a thing. OSHA does not certify
 *    individual workers. The 10-hour and 30-hour courses belong to the OSHA
 *    Outreach Training Program, delivered by OSHA-authorised trainers, and
 *    OSHA states plainly that the programme is voluntary and that none of its
 *    courses is considered a certification. What is true, and more useful, is
 *    that Nevada mandates the card for all construction hires, New York City
 *    requires far more than it, and five further states require it on public
 *    work.
 *
 * 2. "Working under a licensed journeyman or master carpenter" describes a
 *    credential most states do not issue. Licensing in this trade attaches to
 *    the contracting business above a dollar threshold, not to the individual
 *    carpenter, and journeyman status is apprenticeship completion rather than
 *    a legal licence.
 *
 * 3. The poster supplied with this brief advertises visa support. Carpentry
 *    cannot meet the H-1B specialty occupation test, because that route
 *    requires a bachelor's degree in a specific specialty as the normal
 *    minimum for entry. H-2B is the realistic route and it is employer-led.
 *
 * 4. It says an apprenticeship requires a high school diploma. Sponsors set
 *    their own entry requirements and DOL's own sample qualifications allow
 *    alternatives; the federal minimum age is 16.
 *
 * 5. Its "$40,000 to $65,000" is right at the floor and wrong at the ceiling.
 *    $40,410 is the 10th percentile, but $65,000 is roughly the 57th, so the
 *    band omits the top 40 per cent of the trade. The median is $60,580, the
 *    75th percentile $76,830 and the 90th $99,910 — and the occupation's own
 *    mean, $65,630, is above the quoted maximum.
 *
 * 6. It describes carpentry as an employee job throughout. About 25 per cent
 *    of carpenters are self-employed, which is why the wage survey counts
 *    670,090 of them and the handbook 889,700. Every wage figure published
 *    anywhere, this page included, describes only the employed three-quarters.
 *
 * 7. It never mentions risk. Carpenters had 89 fatal injuries in 2024 at a
 *    rate of 7.5 per 100,000 full-time equivalent workers, against 3.3 for all
 *    US workers, and 51 of those 89 deaths were falls, slips and trips.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CarpenterJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-carpenter-jobs.html';

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
        $title = 'Carpenter Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'A quarter of American carpenters are self-employed, so every published pay figure describes only three-quarters of the trade. The measured median is $60,580, OSHA certifies nobody, and most states do not license carpenters at all.',
                'content' => $content,
                'featured_image' => 'blogs/carpenter-jobs-in-usa.jpg',
                'tags' => 'carpenter jobs usa, carpentry apprenticeship, framing carpenter jobs, finish carpenter jobs, carpenter salary usa, osha 10 construction, contractor license, h-2b carpenter jobs',
                'meta_title' => 'Carpenter Jobs in USA: Pay, Apprenticeships, Licences',
                'meta_description' => 'Carpenter jobs in the USA: the $60,580 median BLS measures, why OSHA certification does not exist, and which states actually license the work.',
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
            ['name' => 'US Builders, Remodellers & Specialty Trade Contractors (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-carpenter-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Carpenter — Framing, Finish and Formwork, US Contractors',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Site hours, often starting early, with overtime and seasonal variation',
                'language' => 'English',
                // Pay turns on specialism, union status, state and whether the
                // carpenter is employed or self-employed, so no single
                // advertised band would describe the trade honestly.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Framing, finish and formwork carpentry roles with US contractors. Check whether the employer requires an OSHA card and whether your state licenses the work.',
                'seo_keywords' => 'carpenter jobs usa, carpentry apprenticeship, framing carpenter jobs, finish carpenter jobs, h-2b carpenter jobs',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Home builders, commercial contractors, remodelling firms and specialty trade contractors across the United States hire carpenters continuously, in framing, finish work, cabinetmaking and concrete formwork. It is the largest of the building trades and one of the few skilled careers still entered mainly through paid apprenticeship rather than tuition.</p>

<h3>What the work involves</h3>
<p>Reading drawings and working to building codes; measuring, cutting and assembling timber, engineered lumber and other materials; framing walls, floors and roofs; installing doors, windows, cabinetry, stairs and trim; building and stripping concrete forms; and working alongside other trades on shared sites. Most roles involve sustained physical work, early starts and seasonal variation.</p>

<h3>Requirements</h3>
<ul>
    <li>No degree. Most carpenters enter through a registered apprenticeship, vocational training or on-the-job experience</li>
    <li>Blueprint reading and practical trade mathematics for layout and material take-offs</li>
    <li>Confidence with hand and power tools, and with the safety rules that govern them</li>
    <li>An OSHA 10 or 30 card where the employer, the contract, the state or the city requires one &mdash; it is training, not a certification</li>
    <li>A driver licence for travel between sites, commonly asked for</li>
    <li>A contractor licence only if you intend to contract for the work yourself, and then only where your state requires it</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Work out whether the advertisement wants an employee or a subcontractor.</strong> This is the trade where that distinction changes your tax, your insurance and your liability more than any other, and the job title rarely makes it clear.</p>

<p><strong>Note:</strong> pay, safety training requirements, licensing and eligibility are set by each employer, state and city &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Carpentry is the largest of the American building trades and the one most often described wrongly. Two pieces of advice appear in nearly every carpenter careers guide, and both send readers looking for something that does not exist: an OSHA certification, and a licensed journeyman carpenter to work under. This page corrects both, sets out what the trade actually asks for, and gives the honest answer on the visa route that the recruitment posters keep advertising.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-carpenter-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128296; Browse Carpenter Jobs in the USA &rarr;
    </a>
</div>

<h2>The Published Range Stops at the 57th Percentile</h2>

<p>The figure in circulation is <strong>"$40,000 to $65,000 a year, or roughly $19 to $31 an hour"</strong>. The bottom of it is accurate. The top is not. Here is what the Bureau of Labor Statistics measured across <strong>670,090 employed carpenters in May 2025</strong>:</p>

<ul>
    <li><strong>10th percentile &mdash; $40,410</strong> a year, $19.43 an hour</li>
    <li><strong>25th percentile &mdash; $48,510</strong> a year, $23.32 an hour</li>
    <li><strong>Median &mdash; $60,580</strong> a year, <strong>$29.12</strong> an hour</li>
    <li><strong>75th percentile &mdash; $76,830</strong> a year, $36.94 an hour</li>
    <li><strong>90th percentile &mdash; $99,910</strong> a year, <strong>$48.03</strong> an hour</li>
</ul>

<p>So $65,000 sits between the median and the 75th percentile &mdash; around the <strong>57th percentile</strong>. The quoted band describes roughly the bottom half of the trade and leaves out the top 40 per cent of it. <strong>A quarter of carpenters earn $76,830 or more, and a tenth clear $99,910.</strong> Even the <strong>mean of $65,630</strong> is above the figure presented as a ceiling.</p>

<p>Against the wider labour market, carpentry pays well for a job with no degree requirement: the median for <strong>all US occupations is $50,980</strong>, so the carpenter median runs about <strong>19 per cent above it</strong>. Against the neighbouring trades it sits slightly lower &mdash; <strong>electricians $63,190</strong> and <strong>plumbers $63,800</strong>, both licensed trades &mdash; but well above <strong>construction labourers at $47,120</strong>. The supervisor step, first-line supervisor of construction trades, pays <strong>$79,920</strong>.</p>

<p>Where you work moves this more than almost anything else. The highest state medians are <strong>Hawaii at $85,280</strong>, <strong>Illinois at $79,000</strong>, <strong>California at $75,920</strong>, <strong>Massachusetts at $75,200</strong> and <strong>Washington at $74,190</strong>. The lowest are <strong>Oklahoma at $46,910</strong>, <strong>Arkansas at $47,760</strong> and <strong>South Dakota at $48,140</strong>. Note that two of the biggest employers of carpenters, <strong>Florida</strong> and <strong>Texas</strong>, are high-volume and low-wage at <strong>$49,870</strong> and <strong>$48,900</strong> &mdash; plenty of work, below-median pay.</p>

<h2>A Quarter of the Trade Is Not Employed by Anyone</h2>

<p>This is the structural fact every carpenter guide leaves out, and it changes how you should read every number above. <strong>About 25 per cent of carpenters are self-employed</strong> &mdash; the single largest category in the occupation.</p>

<p>You can see it in the data itself. The wage survey counts <strong>670,090</strong> carpenters, because it only counts employees. The occupational handbook counts <strong>889,700</strong>, because it includes the self-employed. The <strong>219,610</strong> gap between those two numbers is the self-employed quarter of the trade.</p>

<p>Which means: <strong>every percentile on this page describes the three-quarters of carpenters who work for somebody else.</strong> Nobody publishes a reliable earnings distribution for the self-employed quarter, and anyone who quotes you one is estimating.</p>

<p>It also means the trade has a second career track built into it that the guides treat as an afterthought. If you end up self-employed, your income stops being a wage and becomes a business result &mdash; after tools, a vehicle, insurance, unpaid time between jobs, and the contractor licensing thresholds set out further down this page, which become your problem rather than your employer's.</p>

<h2>There Is No Such Thing as OSHA Certification</h2>

<p>Guides list "OSHA safety certification" among the qualifications employers look for. OSHA says otherwise, in its own words: <strong>none of the courses within the Outreach Training Program is considered a certification</strong>, and the programme is <strong>voluntary</strong> and does not meet the training requirements of any OSHA standard.</p>

<p>What actually exists is the <strong>OSHA Outreach Training Program</strong>, delivered by <strong>OSHA-authorised trainers</strong> rather than by OSHA itself, which issues <strong>course completion cards</strong>:</p>

<ul>
    <li><strong>OSHA 10</strong> &mdash; intended for entry-level workers.</li>
    <li><strong>OSHA 30</strong> &mdash; intended for workers with some safety responsibility.</li>
    <li>Construction courses must cover the <strong>Focus Four Hazards</strong> &mdash; falls, struck-by, caught-in or between, and electrocution &mdash; for at least half an hour each. The training emphasises <strong>hazard recognition and avoidance</strong>, not the text of the standards.</li>
    <li><strong>The federal card does not expire.</strong></li>
</ul>

<p>None of which makes the card optional in practice, and this is where the correction becomes useful rather than pedantic. <strong>Several jurisdictions make it a legal requirement, and each does it differently:</strong></p>

<ul>
    <li><strong>Nevada</strong> is the strictest and the broadest: OSHA 10 for non-supervisory construction workers and OSHA 30 for supervisors, produced <strong>within 15 days of being hired</strong>, across construction generally rather than only public work.</li>
    <li><strong>New York City</strong> goes much further than an OSHA card. Under Local Law 196, site workers need a <strong>Site Safety Training card showing at least 40 hours</strong> of training and supervisors <strong>62 hours</strong>. OSHA 10 counts toward that total but does not satisfy it, and <strong>SST cards run for five years</strong>.</li>
    <li><strong>Massachusetts</strong> requires OSHA 10 on public work estimated at <strong>over $10,000</strong> &mdash; a far lower trigger than other states, so it catches small jobs.</li>
    <li><strong>Connecticut</strong> and <strong>New Hampshire</strong> require it on public projects of <strong>$100,000 or more</strong>, and Connecticut will not accept a card issued <strong>more than five years</strong> before the project starts.</li>
    <li><strong>Missouri</strong> gives public works employees <strong>60 days</strong> from starting to complete it, with penalties of $2,500 plus $100 a day per non-compliant worker.</li>
    <li><strong>Rhode Island</strong> requires it on public works projects of $100,000 or more.</li>
</ul>

<p>So the accurate version of the advice is: <strong>the card is training rather than a certification, OSHA never requires it, and in Nevada, New York City and five states on public work the law does.</strong> Take the 10-hour before you apply; it costs little and removes an objection.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/carpenter-jobs-in-usa-framing.jpg"
         alt="A carpenter in a hard hat cutting timber with a circular saw on a US house framing site"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Most States Do Not License Carpenters at All</h2>

<p>The second piece of standard advice is that apprentices work "under a licensed journeyman or master carpenter". In most of the United States there is no such licence to hold.</p>

<p>Electricians and plumbers are licensed as individuals almost everywhere. Carpenters are not. Virginia sets it out cleanly by listing the trades that require individual state certification &mdash; electrical, plumbing, HVAC, gas fitting, water well construction, elevator mechanics, backflow prevention and building energy analysis &mdash; and <strong>carpentry is not among them</strong>.</p>

<p>Two distinctions follow, and they decide what you personally need:</p>

<ul>
    <li><strong>"Journeyman carpenter" is apprenticeship completion, not a legal credential</strong>, in the great majority of states. It matters to employers and to union pay scales; it is not a licence a regulator issues.</li>
    <li><strong>Licensing attaches to the contracting business, not to the carpenter.</strong> An employee working for a licensed contractor generally needs no personal licence anywhere. You need one when you start <strong>contracting for the work yourself</strong>.</li>
</ul>

<p>Where that line falls varies enormously, and it is worth knowing before you take your first side job:</p>

<ul>
    <li><strong>Oregon</strong> is the strictest in the country: the Construction Contractors Board sets <strong>no dollar threshold at all</strong>, and carpentry is named explicitly. Any paid work improving real property requires a licence.</li>
    <li><strong>California</strong> raised its threshold from $500 to <strong>$1,000</strong> with effect from <strong>1 January 2025</strong>, and the exemption only holds if no building permit is required and you do not advertise as a contractor. Splitting a larger job into smaller contracts to stay under it is expressly barred. The carpentry classifications are <strong>C-5 Framing and Rough Carpentry</strong> and <strong>C-6 Cabinet, Millwork and Finish Carpentry</strong>.</li>
    <li><strong>Virginia</strong> requires a contractor licence above <strong>$1,000</strong>, with the class stepping up by project value.</li>
    <li><strong>Arizona</strong> has a carpentry classification of its own, <strong>R-7 Carpentry</strong>, plus R-61 for remodelling work at $50,000 or less.</li>
    <li><strong>Florida</strong> treats <strong>Structural Carpentry</strong> as a specialty contractor category, with a trade exam and four years of experience, and distinguishes statewide certification from local registration.</li>
    <li><strong>Utah</strong> has a specialty class, <strong>S220 Carpentry and Flooring</strong>, which unlike the general contractor licence requires no prior experience.</li>
    <li><strong>Minnesota</strong> licenses at business level and exempts a specialty contractor offering only one skill, so a pure carpenter may need nothing from the state.</li>
    <li><strong>New Jersey</strong> requires annual <strong>registration</strong> rather than licensing, with $500,000 of liability cover, and <strong>New York City</strong> requires a Home Improvement Contractor licence for residential work.</li>
</ul>

<h2>What an Apprenticeship Actually Commits You To</h2>

<p>This is the part the guides get roughly right, and it is the strongest argument for the trade: <strong>you are paid from the first day, and the wage rises as your skills do</strong> rather than on a calendar. A registered apprenticeship ends in a nationally recognised credential without tuition debt.</p>

<p>The numbers, stated carefully:</p>

<ul>
    <li>Carpentry apprenticeships typically run <strong>about four years</strong> and roughly <strong>8,000 hours</strong> of on-the-job learning, with a recommended <strong>144 hours of classroom instruction a year</strong>. Those are the standard figures used by sponsors, not a single federal rule &mdash; programmes may be time-based, competency-based or a hybrid.</li>
    <li>The <strong>federal minimum age is 16</strong>, though many construction programmes set 18 in practice.</li>
    <li><strong>A high school diploma is not universally required to start an apprenticeship</strong>, even though BLS records one as the typical entry-level education for the occupation. Each sponsor sets its own standard, and the Department of Labor's own sample qualifications allow alternatives such as assessment scores or relevant experience. Do not rule yourself out on this without checking the sponsor.</li>
    <li>The <strong>United Brotherhood of Carpenters</strong> runs a four-year programme through its training fund, and describes graduates as finishing with four years of experience and no debt.</li>
</ul>

<p>One honest note on the union question. Guides routinely claim union carpenters earn more. That may well be so, but <strong>the UBC publishes no wage figures</strong> of its own &mdash; no pay scale, no union premium. Any percentage you see quoted for it comes from somewhere else, so treat it accordingly and ask your local for its actual published wage schedule.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/carpenter-jobs-in-usa-deck.jpg"
         alt="A carpenter kneeling to drive screws into deck framing outside a US home at sunrise"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Steady Growth, and the Risk Nobody Prints</h2>

<p>On demand, the guides overstate a figure that is decent on its own terms. Employment is projected to grow <strong>4 per cent from 2025 to 2035</strong>, which BLS calls "as fast as average" &mdash; against <strong>3.5 per cent for all occupations</strong>. That is a real but modest half-point edge, adding <strong>35,000 jobs</strong> on a base of 889,700, with about <strong>62,800 openings a year</strong> once replacement is counted.</p>

<p>What no careers guide mentions is the other side of the trade, and it is a citable number rather than an impression:</p>

<ul>
    <li><strong>89 carpenters died at work in 2024</strong>, a fatal injury rate of <strong>7.5 per 100,000 full-time equivalent workers</strong>.</li>
    <li>The rate for <strong>all US workers is 3.3</strong>. Carpentry is therefore about <strong>2.3 times</strong> the national average.</li>
    <li><strong>Falls, slips and trips caused 51 of those 89 deaths</strong> &mdash; well over half, and the reason fall protection dominates site safety training.</li>
    <li>Non-fatal injury is common rather than rare: <strong>165.2 cases with days away from work per 10,000 full-time workers</strong>.</li>
    <li>For context, construction and extraction occupations as a whole run at <strong>12.6</strong>, so carpentry is safer than the construction average while remaining well above the national one.</li>
</ul>

<p>This is not an argument against the trade. It is the reason the Focus Four training above exists, the reason employers ask for the card, and the reason the fall-protection habits you learn in your first month are the ones worth keeping.</p>

<h2>The Visa Answer the Posters Get Wrong</h2>

<p>Recruitment material for American trade jobs routinely advertises visa support. For carpentry the position is narrower than that suggests, and it is worth knowing before anyone pays a fee to an agent.</p>

<p><strong>H-1B does not fit.</strong> That route is for specialty occupations, and the test is a bachelor's degree or higher <strong>in a directly related specific specialty</strong> as the normal minimum for entry, with general degrees excluded. Carpentry has no degree entry requirement, so it fails that test on its face.</p>

<p><strong>H-2B is the realistic route</strong>, and carpentry roles genuinely are certified under it &mdash; the Department of Labor's own seasonal jobs portal carries helper-carpenter postings. Three things to understand about it:</p>

<ul>
    <li>It covers <strong>temporary</strong> need only &mdash; one-time, seasonal, peakload or intermittent.</li>
    <li>The cap is <strong>66,000 a year</strong>, split into 33,000 for each half of the year.</li>
    <li><strong>The employer drives it.</strong> The employer must obtain a temporary labour certification from the Department of Labor first. You cannot apply for an H-2B by yourself, and anyone offering to sell you one is not describing the process correctly.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>How much do carpenters earn in the USA?</h3>
<p>The median is $60,580 a year, or $29.12 an hour, and the mean is $65,630. A quarter earn more than $76,830 and a tenth more than $99,910, while the bottom tenth earn under $40,410 (BLS, May 2025, employed carpenters only).</p>

<h3>Is $40,000 to $65,000 accurate for a carpenter?</h3>
<p>Only at the bottom. $40,410 is the 10th percentile, so the floor is right, but $65,000 is about the 57th percentile. The range leaves out the top 40 per cent of the trade, and the occupation's own mean is higher than its stated ceiling.</p>

<h3>How many carpenters are self-employed?</h3>
<p>About 25 per cent, the largest single category in the occupation. The wage survey counts 670,090 employed carpenters while the handbook counts 889,700 including the self-employed, so published pay figures describe only three-quarters of the trade.</p>

<h3>Is there an OSHA certification for carpenters?</h3>
<p>No. OSHA does not certify individual workers. The 10-hour and 30-hour courses are part of the voluntary OSHA Outreach Training Program, delivered by authorised trainers, and OSHA states that none of its Outreach courses is considered a certification. The card does not expire.</p>

<h3>Do I need an OSHA 10 card to work as a carpenter?</h3>
<p>It depends where. Nevada requires it for construction workers within 15 days of hire, New York City requires a 40-hour Site Safety Training card, and Massachusetts, Connecticut, New Hampshire, Missouri and Rhode Island require it on public projects. Elsewhere it is an employer or contract requirement rather than a legal one.</p>

<h3>Do carpenters need a licence in the USA?</h3>
<p>Most states do not license carpenters as individuals, unlike electricians and plumbers. Licensing generally applies to the contracting business above a dollar threshold &mdash; $1,000 in California and Virginia, and any amount at all in Oregon. An employee of a licensed contractor usually needs no personal licence.</p>

<h3>How long is a carpentry apprenticeship?</h3>
<p>Typically about four years and roughly 8,000 hours of paid on-the-job learning, with around 144 hours of classroom instruction a year. Apprentices are paid from day one, with increases tied to skills gained rather than time served.</p>

<h3>Can a carpenter get a US work visa?</h3>
<p>Not through H-1B, which requires a bachelor's degree in a specific specialty as the normal entry route. H-2B covers temporary and seasonal carpentry work, is capped at 66,000 a year, and must be started by the employer through a Department of Labor labour certification.</p>

<h2>People Also Search For</h2>

<h3>Carpentry apprenticeship near me</h3>
<p>Paid from day one, typically four years and about 8,000 hours, ending in a nationally recognised credential without tuition debt.</p>

<h3>OSHA 10 construction card</h3>
<p>Voluntary federal training that does not expire, but legally mandated in Nevada, New York City and five states on public work.</p>

<h3>Contractor licence requirements by state</h3>
<p>No threshold at all in Oregon, $1,000 in California and Virginia, business-level only in Minnesota, and registration rather than licensing in New Jersey.</p>

<h3>Framing vs finish carpentry</h3>
<p>Two different trades in practice, and California licenses them separately as C-5 framing and rough carpentry and C-6 cabinet, millwork and finish carpentry.</p>

<h3>Journeyman carpenter meaning</h3>
<p>Apprenticeship completion, and a step on an employer or union pay scale &mdash; not a licence a regulator issues in most states.</p>

<h3>Carpenter apprenticeship entry requirements</h3>
<p>Set by each sponsor. A diploma is common but not universal, DOL sample qualifications allow alternatives, and the federal minimum age is 16.</p>

<h3>Union carpenter pay</h3>
<p>Often claimed to be higher, but the United Brotherhood of Carpenters publishes no wage figures, so ask your local for its own schedule.</p>

<h3>H-2B carpenter jobs</h3>
<p>The realistic visa route for this trade, capped at 66,000 a year and started by the employer rather than the worker.</p>

<h3>Carpenter salary USA</h3>
<p>A $60,580 median and $29.12 an hour, with the top quarter above $76,830 and the top tenth above $99,910.</p>

<h3>Highest paying states for carpenters</h3>
<p>Hawaii at $85,280, Illinois at $79,000, California at $75,920, Massachusetts at $75,200 and Washington at $74,190.</p>

<h3>Self employed carpenter</h3>
<p>About a quarter of the trade. No official earnings distribution exists for it, so treat any self-employed income figure as an estimate.</p>

<h2>More Job Guides</h2>

<p>Comparing trades and construction routes? These cover them:</p>

<ul>
    <li><a href="/blog/construction-jobs-in-usa-for-foreigners">Construction Jobs in USA for Foreigners</a> &mdash; the wider American site trades and the visa categories that reach them.</li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a> &mdash; the same work under award pay and the White Card.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a trade that genuinely does license individuals, and what that takes.</li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a> &mdash; another licensed trade, and where mutual recognition stops.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; certification by province and what it is worth.</li>
    <li><a href="/blog/unskilled-jobs-in-usa-for-foreigners">Unskilled Jobs in USA for Foreigners</a> &mdash; the honest position on entry-level American sponsorship.</li>
    <li><a href="/blog/delivery-driver-jobs-in-usa">Delivery Driver Jobs in USA</a> &mdash; another route that turns on employee versus contractor status.</li>
    <li><a href="/blog/retail-associate-jobs-in-usa">Retail Associate Jobs in USA</a> &mdash; the largest no-degree entry route, for comparison.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not careers, legal or immigration advice. Wage figures, safety training mandates, contractor licensing thresholds and visa rules change and differ by state and city. Confirm the current position with the Bureau of Labor Statistics, OSHA, your state licensing board and the employer's own advertisement before applying.</p>
HTML;
    }
}
