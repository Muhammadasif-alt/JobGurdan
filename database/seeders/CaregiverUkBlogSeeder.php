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
 * "Caregiver Jobs in UK with Visa Sponsorship" — the post covers the July 2025
 * closure of the overseas care worker route, so the matching listing is written
 * for candidates who already hold the right to work rather than promising
 * sponsorship the route no longer offers.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class CaregiverUkBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://uk.indeed.com/jobs?q=care+assistant';

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
        $title = 'Caregiver Jobs in UK with Visa Sponsorship';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'The overseas care worker route closed on 22 July 2025 — here is who is still covered, which pathways stay open, real UK care salaries, and how to spot the recruiters still selling a visa that no longer exists.',
                'content' => $content,
                'featured_image' => 'blogs/caregiver-jobs-in-uk-visa-sponsorship.jpg',
                'tags' => 'caregiver jobs uk, uk visa sponsorship, health and care worker visa, care assistant jobs, support worker jobs, jobs for foreigners, uk care salary',
                'meta_title' => 'Caregiver Jobs in UK with Visa Sponsorship (2026 Update)',
                'meta_description' => 'The UK closed overseas care worker sponsorship in July 2025. Who is still covered, which routes remain open, real salaries, and how to avoid visa scams.',
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
            ['name' => 'UK Care Providers (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'uk-care-providers']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        Job::updateOrCreate(
            [
                'position' => 'Care Assistant / Support Worker — United Kingdom',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shifts, including nights and weekends',
                'language' => 'English',
                'salary_currency' => 'GBP',
                'salary_period' => 'Hourly',
                'salary_minimum' => 11,
                'salary_maximum' => 14,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'UK care assistant and support worker openings, GBP 11-14 per hour. Right to work required — overseas care worker sponsorship closed in July 2025.',
                'seo_keywords' => 'caregiver jobs uk, care assistant jobs, support worker jobs uk, uk care salary, health and care worker visa, care jobs near me',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>UK care providers hire care assistants and support workers year-round across residential, supported living and domiciliary settings. Many providers offer paid induction training, so prior care experience is often not required.</p>

<h3>Important &mdash; visa sponsorship status</h3>
<p>The Health and Care Worker visa closed to <strong>new overseas applicants</strong> in the care worker and senior care worker occupation codes (6135 and 6136) on <strong>22 July 2025</strong>, and remains closed. These roles are therefore open to candidates who <strong>already hold the right to work in the UK</strong> &mdash; British or Irish citizens, settled and pre-settled status holders, and anyone on a visa that permits this work. Existing Health and Care Worker visa holders may switch employers in-country under transitional rules running to 22 July 2028.</p>

<h3>Requirements</h3>
<ul>
    <li>Right to work in the UK (citizenship, settled/pre-settled status, or a visa permitting the work)</li>
    <li>Enhanced DBS check &mdash; standard for all roles supporting vulnerable people</li>
    <li>Spoken and written English strong enough for care records and safety procedures</li>
    <li>References; formal care experience is welcome but frequently not required</li>
    <li>Willingness to work shifts, including nights and weekends</li>
</ul>

<h3>What's on offer</h3>
<ul>
    <li>Roughly &pound;11&ndash;&pound;14 an hour in the private sector, with enhanced evening, weekend and overtime rates</li>
    <li>Around &pound;24,000&ndash;&pound;27,000 a year for NHS and public-sector banded support worker posts</li>
    <li>Paid induction and Care Certificate training with many national providers</li>
    <li>Live-in care roles that combine pay with accommodation, usually on a day rate</li>
</ul>

<p><strong>Note:</strong> pay, contracts and eligibility are set by the individual employer and by UK immigration rules &mdash; not by JobGader. Verify any sponsor on the official gov.uk register of licensed sponsors, and never pay a recruiter for a job offer or visa: charging jobseekers for sponsorship is illegal in the UK.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Before anything else, here's the fact that changes everything about this search: as of <strong>22 July 2025</strong>, the UK government closed the Health and Care Worker visa route to <em>new</em> overseas applicants in care worker and senior care worker roles (occupation codes 6135 and 6136), and that closure remains in effect through 2026. If you're currently living outside the UK and hoping to be sponsored directly into a <strong>caregiver jobs in UK</strong> role from abroad, that specific route is not currently available &mdash; no matter what an agency or job ad might imply. This article explains exactly what changed, who is still protected, what alternatives exist, and how to avoid the recruitment scams that have sprung up around this exact search term.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/jobs?q=care+assistant" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🩺 Browse Care Assistant Jobs in the UK →
    </a>
</div>

<h2>What Actually Changed in 2025&ndash;2026</h2>

<p>The Migration Advisory Committee found widespread abuse of the overseas care worker visa route &mdash; including illegal recruitment fees charged abroad, workers arriving to find no real job waiting, and sponsors having their licences revoked mid-placement. In response, the government closed new overseas sponsorship for:</p>

<ul>
    <li><strong>Care Worker</strong> (SOC code 6135)</li>
    <li><strong>Senior Care Worker</strong> (SOC code 6136)</li>
</ul>

<p>This closure applies specifically to <strong>new applications from outside the UK</strong>. It does not retroactively cancel anyone's existing visa.</p>

<h3>Who Is Still Covered</h3>

<ul>
    <li><strong>Existing Health and Care Worker visa holders</strong> can extend their stay, switch employers within the UK, and work toward settlement (Indefinite Leave to Remain) under transitional arrangements running until <strong>22 July 2028</strong>.</li>
    <li>Switching employers requires meeting a <strong>three-month prior employment rule</strong> with a UK-based sponsor.</li>
    <li>From <strong>8 January 2026</strong>, new applicants under this route (in the limited cases where it still applies) need English at <strong>CEFR level B2</strong>, though people already in the UK renewing their visa can continue at the previous B1 level.</li>
    <li>Employers can still recruit care workers who are <strong>already living in the UK</strong> with valid immigration status &mdash; just not new hires from overseas.</li>
</ul>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/caregiver-jobs-in-uk-visa-sponsorship-carer.jpg"
         alt="Care worker supporting an elderly woman at home — caregiver jobs in UK with visa sponsorship"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h3>What's Still Open</h3>

<p>The wider Health and Care Worker visa route hasn't disappeared &mdash; it's just narrower now. It remains available for:</p>

<ul>
    <li>Doctors, nurses, paramedics, and allied health professionals sponsored by the NHS, an NHS supplier, or an approved health/social care employer.</li>
    <li>These clinical roles are exempt from the Immigration Health Surcharge and generally follow NHS Agenda for Change pay bands (for example, a Band 5 registered nurse role typically starts around &pound;31,000+ a year).</li>
</ul>

<p>If your background and qualifications fit a clinical role rather than a generic care assistant role, that pathway is genuinely still active &mdash; the closure specifically targets the non-clinical care worker codes.</p>

<h2>Caregiver Jobs in UK for Foreigners: The Honest Current Picture</h2>

<p>If you're outside the UK and searching <strong>caregiver jobs in UK for foreigners</strong> or <strong>UK caregiver jobs with visa sponsorship 2026</strong>, here's the realistic state of play:</p>

<ul>
    <li><strong>Direct overseas sponsorship into a standard care assistant or support worker role is currently closed.</strong> Any agency or website promising to place you directly from your home country into a UK care job with visa sponsorship in 2026 should be treated with real skepticism.</li>
    <li><strong>If you already hold a UK visa</strong> (student, graduate, dependant, or another route that grants the right to work), you can apply for care roles like any other candidate in the domestic labour market &mdash; no separate sponsorship needed.</li>
    <li><strong>If you're already in the UK on a Health and Care Worker visa</strong>, you can switch employers within the sector under the transitional rules through 2028.</li>
    <li>Roles outside the closed occupation codes &mdash; nursing, allied health, and some senior clinical/managerial care positions &mdash; may still qualify for sponsorship under the broader Skilled Worker or Health and Care Worker routes, depending on salary and occupation code eligibility.</li>
</ul>

<h2>Avoiding Recruitment Scams</h2>

<p>This is the single most important section for anyone searching this topic right now. Because the phrase "caregiver jobs in UK with visa sponsorship" still gets searched heavily, it has become a magnet for scam recruiters who:</p>

<ul>
    <li>Advertise care assistant roles with "guaranteed visa sponsorship" despite the route being closed to new overseas applicants.</li>
    <li>Ask for upfront fees for "processing," "job placement," or "visa guarantee" &mdash; charging jobseekers for sponsorship is illegal under UK rules, and legitimate sponsors do not do this.</li>
    <li>Use outdated marketing pages (some written years ago, before July 2025) that never mention the closure at all.</li>
</ul>

<p><strong>Before trusting any offer:</strong> verify the employer holds a valid, active sponsor licence by checking the <a href="https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers" target="_blank" rel="noopener">official gov.uk register of licensed sponsors</a>, and confirm current eligibility rules directly on gov.uk rather than relying on a recruiter's word.</p>

<h2>Caregiver Jobs in UK: Requirements (For Those Currently Eligible)</h2>

<p>For people who do qualify &mdash; whether through an existing visa, transitional in-country switching, or a clinical role still open to sponsorship &mdash; typical requirements include:</p>

<ul>
    <li><strong>Right to work in the UK</strong>: British/Irish citizenship, settled or pre-settled status, or a valid visa permitting the work.</li>
    <li><strong>Enhanced DBS check</strong>: a background check specific to roles involving vulnerable people is standard across the sector.</li>
    <li><strong>English language proficiency</strong>: for visa-linked roles this is now B2 for new cases (B1 for existing visa renewals); domestically hired candidates typically just need strong spoken/written English to meet care documentation and safety standards.</li>
    <li><strong>Experience</strong>: often not mandatory for entry-level care assistant roles &mdash; many providers, including large national chains, run their own paid training and induction programs for people without prior care experience.</li>
    <li><strong>References and character checks</strong>: professional or personal caregiving references are commonly requested even when formal experience isn't required.</li>
</ul>

<h3>Caregiver Jobs in UK Without IELTS</h3>

<p>IELTS is one accepted way to prove English ability, but it isn't the only one &mdash; some visa categories accept other approved English tests, a degree taught in English, or being a national of a majority English-speaking country. If you're asking about this because you're hoping to avoid a formal test altogether for a <em>new overseas</em> care worker sponsorship, note that the underlying visa route is currently closed regardless of which English test you'd use &mdash; the English requirement question is currently only practically relevant for the roles and pathways still open (clinical roles, in-country switching, or non-sponsored domestic hiring where employers set their own language expectations).</p>

<h2>Caregiver Jobs in UK Salary (What the Data Actually Shows)</h2>

<p>Pay varies significantly by role, employer, region, and sector (NHS/public sector vs. private care providers). Recent real advertised examples give a useful picture:</p>

<ul>
    <li>A <strong>Band 3 Adult Support Worker</strong> role with a UK Health and Social Care Trust was recently listed at <strong>&pound;24,937&ndash;&pound;26,598 a year</strong> (permanent, public sector).</li>
    <li>A <strong>private-sector Support Worker</strong> role (supported living, learning disabilities/autism/mental health) was recently advertised at <strong>&pound;13.41 an hour</strong>, with enhanced evening/weekend rates and overtime premiums on top.</li>
    <li>Live-in carer and domiciliary care pay structures vary widely and often include a day rate rather than a standard hourly wage, since live-in roles combine work and accommodation.</li>
    <li>Broadly, UK care sector pay for entry-level roles clusters in the <strong>&pound;11&ndash;&pound;14 an hour</strong> range in the private sector, rising into the <strong>&pound;24,000&ndash;&pound;27,000+</strong> range for structured NHS/public-sector support worker bands, with senior and specialist roles paying more.</li>
</ul>

<p><strong>Caregiver jobs in UK salary per month</strong>, converting a typical full-time hourly rate, generally lands somewhere around <strong>&pound;1,800&ndash;&pound;2,300 a month</strong> before tax for entry-level roles, and higher for NHS-banded or senior positions &mdash; though actual take-home pay depends on hours worked, region, night/weekend enhancements, and tax code.</p>

<h2>What to Do Instead, Depending on Your Situation</h2>

<ul>
    <li><strong>If you're already in the UK with the right to work:</strong> apply directly through platforms like Indeed UK or Carehome.co.uk, and look at national providers such as Agincare, Home Instead, or Care UK, many of which offer paid induction training for candidates without prior experience.</li>
    <li><strong>If you're a nurse, paramedic, or allied health professional abroad:</strong> the Health and Care Worker visa route may still apply to you &mdash; check current eligibility directly on gov.uk before assuming the general closure applies to your qualification.</li>
    <li><strong>If you're outside the UK with no existing visa and a non-clinical care background:</strong> be realistic that direct sponsorship into a standard care assistant role isn't currently available, and be very cautious of anyone claiming otherwise for a fee.</li>
    <li><strong>If you're already sponsored under the Health and Care Worker visa:</strong> you can switch employers or extend your stay under the transitional rules until July 2028 &mdash; a licensed immigration adviser can help confirm your specific eligibility window.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Can I still get a UK caregiver job with visa sponsorship from overseas in 2026?</h3>
<p>Not through the standard care worker/senior care worker route &mdash; that was closed to new overseas applicants on 22 July 2025 and remains closed. Clinical health roles (nursing, allied health) may still be sponsorable depending on your qualifications.</p>

<h3>What happens to people who already have a Health and Care Worker visa?</h3>
<p>They keep their status, can extend or switch employers within the UK under transitional rules, and can work toward settlement, with the transition period currently running to 22 July 2028.</p>

<h3>Is it true some sites still advertise "caregiver jobs in UK with visa sponsorship for foreigners"?</h3>
<p>Yes, and many of these are outdated or misleading. Always verify a sponsor's licence on the official gov.uk register before engaging with any recruiter or paying any fee.</p>

<h3>Do I need IELTS specifically?</h3>
<p>No &mdash; IELTS is one accepted English test among several recognized options, but the more important question right now is whether the underlying visa route applies to you at all.</p>

<h3>What's a realistic monthly salary for a UK care role?</h3>
<p>Roughly &pound;1,800&ndash;&pound;2,300 a month for entry-level hourly roles, more for NHS-banded support worker positions or senior/specialist care roles.</p>

<h2>People Also Search For</h2>

<h3>Caregiver jobs in UK salary</h3>
<p>Entry-level care roles typically pay &pound;11&ndash;&pound;14 an hour in the private sector, rising to roughly &pound;24,000&ndash;&pound;27,000+ a year for structured public-sector/NHS-banded support worker posts. Senior, specialist, and clinical roles pay more.</p>

<h3>Caregiver jobs in UK for foreigners</h3>
<p>Direct overseas sponsorship into a standard care assistant role is currently closed (since 22 July 2025). Foreigners already holding a UK visa that grants the right to work can apply like any other domestic candidate; those with nursing or allied health qualifications may still qualify for sponsorship under the narrower clinical routes.</p>

<h3>UK caregiver jobs with visa sponsorship salary</h3>
<p>Before the closure, sponsored care worker roles generally had to meet a minimum salary threshold around &pound;25,000 a year (or the going rate for the role, if higher). That threshold is now mostly relevant to the clinical roles still open to sponsorship, since new non-clinical care worker sponsorship from overseas isn't available.</p>

<h3>Caregiver jobs in UK with visa sponsorship no experience</h3>
<p>Domestically, many UK care providers hire without prior experience and offer paid induction training. For overseas applicants, this question is currently moot for standard care worker roles since new overseas sponsorship in that occupation code is closed regardless of experience level.</p>

<h3>UK caregiver jobs with visa sponsorship 2026</h3>
<p>Still closed to new overseas care worker/senior care worker applicants as of 2026, with the transitional period for existing visa holders running to 22 July 2028. No reversal has been announced.</p>

<h3>Caregiver jobs in UK without IELTS</h3>
<p>IELTS is one of several accepted English tests &mdash; others, or a degree taught in English, may qualify depending on the route. The bigger issue for most searchers isn't which English test to take, but whether the underlying visa route is open to them at all right now.</p>

<h3>Caregiver jobs in UK requirements</h3>
<p>Right to work in the UK, an enhanced DBS check, adequate English proficiency, and often references &mdash; formal prior experience is frequently not required for entry-level domestic hires, since many employers train on the job.</p>

<h3>Caregiver jobs in UK salary per month</h3>
<p>Roughly &pound;1,800&ndash;&pound;2,300 a month before tax for typical entry-level, full-time hourly roles; higher for NHS-banded or senior/specialist positions, and variable for live-in care arrangements that combine pay with accommodation.</p>

<h2>Still Looking for Sponsored Work?</h2>

<p>If the UK care route is closed to you right now, it's worth comparing countries where sponsorship for non-clinical roles is still open. Two guides worth reading next:</p>

<ul>
    <li><a href="/blog/truck-driver-jobs-in-usa-with-visa-sponsorship">Truck Driver Jobs in USA with Visa Sponsorship</a> &mdash; the EB-3 and H-2B routes, CDL requirements and realistic pay.</li>
    <li><a href="/blog/hotel-jobs-in-usa-for-foreigners">Hotel Jobs in USA for Foreigners</a> &mdash; H-2B and J-1 hospitality sponsorship, which roles get hired, and how to spot a scam agency.</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://uk.indeed.com/jobs?q=care+assistant" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Care Assistant Jobs in the UK →
    </a>
</div>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and isn't legal or immigration advice. UK immigration rules change frequently and the care worker route closure has already changed several times since 2025 &mdash; always confirm current eligibility directly on gov.uk or with a regulated immigration adviser before making decisions or paying any fees.</p>
HTML;
    }
}
