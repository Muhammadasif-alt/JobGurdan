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
 * "Visa Sponsorship Jobs in Australia" — the highest-intent migration search
 * for Australia, and the one where the standard careers article is written
 * against a visa system that was renamed and restructured underneath it.
 *
 * Corrections to the draft:
 *
 * 1. It leads with the "Temporary Skill Shortage (TSS) visa (subclass 482)".
 *    Home Affairs now publishes subclass 482 as the Skills in Demand visa,
 *    with a Core Skills stream, a Specialist Skills stream and a Labour
 *    agreement stream. TSS is the former name.
 *
 * 2. It refers throughout to "Australia's Skilled Occupation List", singular,
 *    as though one list governed everything. The departmental page publishes a
 *    combined list flagging four: the Core Skills Occupation List (CSOL), the
 *    Medium and Long-term Strategic Skills List (MLTSSL), the Short-term
 *    Skilled Occupation List (STSOL) and the Regional Occupation List (ROL).
 *    Which one applies depends on the visa; the 482 Core Skills stream uses
 *    the CSOL.
 *
 * 3. It tells readers to check their occupation against that list before
 *    pursuing permanent residence. Home Affairs states the opposite for the
 *    main employer route: the Temporary Residence Transition streams of
 *    subclass 186 and 187 have no occupation list, and eligibility is based on
 *    the occupation from the most recently held temporary skilled visa.
 *
 * 4. It says employers must meet the Temporary Skilled Migration Income
 *    Threshold. The threshold that applies to a 482 nomination is the Core
 *    Skills Income Threshold, and the nomination must meet both it and the
 *    annual market salary rate for the role. The amount is indexed, so this
 *    guide points readers at the departmental salary requirements page rather
 *    than printing a figure that expires each 1 July.
 *
 * 5. It states a skills assessment is "required for many sponsored visa
 *    categories" as a flat rule. The combined list names an assessing
 *    authority per occupation, but whether an assessment is required depends
 *    on the visa stream and the occupation.
 *
 * 6. It never mentions caveats. The departmental page states that caveats
 *    exclude the use of an occupation in certain circumstances and apply to
 *    the ENS Direct Entry stream and the SID visas — which is exactly how an
 *    otherwise eligible occupation fails a nomination.
 *
 * 7. Its salary bands (AUD 70,000 to 110,000, and 55,000 to 80,000) are not
 *    attributable to any official Australian source and conflate a visa income
 *    threshold with market pay. They are not reproduced here.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class VisaSponsorshipJobsAustraliaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://au.indeed.com/q-visa-sponsorship-jobs.html';

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
        $title = 'Visa Sponsorship Jobs in Australia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Subclass 482 is no longer the TSS visa and there is no single Skilled Occupation List: Australia flags four of them. The main employer route to permanent residence has no occupation list at all, because it follows the visa you already hold.',
                'content' => $content,
                'featured_image' => 'blogs/visa-sponsorship-jobs-in-australia.jpg',
                'tags' => 'visa sponsorship jobs in australia, skills in demand visa 482, core skills occupation list, employer nomination scheme 186, subclass 494 regional, core skills income threshold, australia work visa, sponsored jobs australia',
                'meta_title' => 'Visa Sponsorship Jobs in Australia: 482, 186 and 494',
                'meta_description' => 'Visa sponsorship jobs in Australia: subclass 482 is now the Skills in Demand visa, which occupation list applies to you, and what a sponsor must legally pay.',
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
            ['name' => 'Australian Approved Sponsors Advertising Skilled Roles (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'australia-visa-sponsorship-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Australia'],
            ['area' => 'Nationwide', 'country' => 'Australia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'general-labour'],
            ['name' => 'General Labour']
        );

        Job::updateOrCreate(
            [
                'position' => 'Visa Sponsorship Jobs — Skills in Demand, Employer Nomination and Regional Roles, Australian Sponsors',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, as required for a nominated position under an approved sponsorship',
                'language' => 'English',
                // Sponsored roles span nursing, engineering, IT, trades and
                // agriculture, and the enforceable floor is the Core Skills
                // Income Threshold or the market salary rate, whichever is
                // higher, indexed each 1 July. A fixed band would misstate it.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Skilled roles with Australian employers approved to sponsor. Check that your occupation is on the list your visa stream uses, and that the salary meets both the income threshold and the market rate.',
                'seo_keywords' => 'visa sponsorship jobs australia, skills in demand visa 482, 186 visa sponsorship, 494 regional sponsorship, sponsored jobs australia',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Australian employers approved as sponsors nominate skilled workers where they cannot source an appropriately skilled Australian worker. Nominations run across healthcare, engineering, information technology, construction trades, agriculture, hospitality and education, and regional employers carry their own dedicated route under subclass 494.</p>

<h3>What sponsorship actually means</h3>
<p>Three separate approvals must line up: the business must be an approved sponsor, the position must be nominated and approved, and you must then be granted the visa. A job advertisement mentioning sponsorship is only the first of the three, and none of it happens without a genuine nominated position.</p>

<h3>Requirements</h3>
<ul>
    <li>An occupation on the list that your visa stream uses, checked in the current legislative instrument</li>
    <li>The work experience, qualifications and English required for that stream</li>
    <li>A skills assessment where your occupation and stream call for one</li>
    <li>Registration or licensing for regulated occupations, such as nursing or the electrical trades</li>
    <li>Health and character requirements for the visa grant</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Check two things in the advertisement: whether the business is an approved sponsor, and whether your occupation carries a caveat.</strong> A caveat can exclude an occupation in particular circumstances even when the occupation itself is eligible, and it is a common reason a nomination fails after an offer has been made.</p>

<p><strong>Note:</strong> salary, sponsorship capacity and eligibility are set by each employer and by the Department of Home Affairs &mdash; not by JobGader. Confirm the current requirements on the departmental website before applying, and never pay an employer for a nomination.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Almost every guide to visa sponsorship in Australia still describes a visa that has been renamed, points at a single occupation list that does not exist, and gives the wrong advice about how sponsorship turns into permanent residence. The system itself is straightforward once you see its actual shape. This page sets out what subclass 482 is called now, which of Australia's four occupation lists applies to you, what a sponsor is legally required to pay, and the one rule about permanent residence that most articles get backwards.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/visa-sponsorship-jobs-in-australia-workplace.jpg" alt="Skilled workers in an Australian workplace under employer sponsorship" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The 482 Is Not the TSS Visa Any More</h2>

<p>If your research keeps returning the phrase "Temporary Skill Shortage (TSS) visa", you are reading material written before the visa was restructured. The Department of Home Affairs now publishes subclass 482 as the <strong>Skills in Demand visa</strong>, and it runs in three streams:</p>

<ul>
    <li><strong>Core Skills stream</strong> &mdash; the mainstream employer-sponsored route, which uses the Core Skills Occupation List</li>
    <li><strong>Specialist Skills stream</strong> &mdash; for highly paid specialist roles</li>
    <li><strong>Labour agreement stream</strong> &mdash; for employers working under a negotiated labour agreement</li>
</ul>

<p>The Core Skills stream lets you stay <strong>up to 4 years</strong>, and <strong>Hong Kong passport holders may stay up to 5 years</strong>. Home Affairs also notes that <strong>from 1 July 2026 a lower visa cost applies to eligible Pacific Island and Timor-Leste citizens</strong> who lodge a valid application.</p>

<p>The name matters for a practical reason. If an agent, a recruiter or a website is still selling you "TSS visa" advice, they are working from an older version of the rules, and the parts you cannot see may be out of date too.</p>

<h2>There Is No Single "Skilled Occupation List"</h2>

<p>This is the error that does the most damage, because it sends people to check the wrong list and draw the wrong conclusion about their chances.</p>

<p>Home Affairs publishes a <strong>combined</strong> skilled occupation list. For each occupation it specifies the ANZSCO code, any caveats, the assessing authority for skills assessments, and &mdash; the part that matters &mdash; whether the occupation sits on the:</p>

<ul>
    <li><strong>Core Skills Occupation List (CSOL)</strong></li>
    <li><strong>Medium and Long-term Strategic Skills List (MLTSSL)</strong></li>
    <li><strong>Short-term Skilled Occupation List (STSOL)</strong></li>
    <li><strong>Regional Occupation List (ROL)</strong></li>
</ul>

<p>Different visas draw on different lists. The combined list is used by the Employer Nomination Scheme (subclass 186), the Regional Sponsored Migration Scheme (subclass 187) Temporary Residence Transition stream, Skilled Independent (189) points-tested, Skilled Nominated (190), Training (407), the <strong>Skills in Demand visa (subclass 482) Core Skills stream</strong>, Temporary Graduate (485), Skilled Regional (Provisional) (489), Skilled Work Regional (Provisional) (491) and Skilled Employer Sponsored Regional (Provisional) (494).</p>

<p>So "is my occupation on the Skilled Occupation List?" is not a question that has an answer. The question is: <em>is my occupation on the list that my visa stream uses?</em> A nurse, a carpenter and an electrician all appear on the Core Skills Occupation List. A taxi driver does not appear on it at all. Same country, same combined list, entirely different answers.</p>

<p>One more warning that most guides skip entirely. The combined list also flags <strong>caveats, which exclude the use of an occupation in certain circumstances</strong>, and these apply to the ENS Direct Entry stream and to the SID visas. A caveat is how a job that looks eligible on paper fails at nomination &mdash; because of the size of the business, the nature of the position, or the salary attached to it. Check the caveat before you accept an offer, not after.</p>

<p>Finally, treat any list you find on a blog as a summary. Home Affairs is explicit that the current list of eligible skilled occupations for a visa programme is in that programme's <strong>legislative instrument</strong>. That is the authoritative source, and it is the one that changes.</p>

<h2>What a Sponsor Must Legally Pay You</h2>

<p>Here the standard article is out of date in a way that could cost you money. It says employers must meet the Temporary Skilled Migration Income Threshold. For a subclass 482 nomination, the threshold that applies is the <strong>Core Skills Income Threshold</strong>.</p>

<p>The structure of the obligation is what you need to understand, because it is a two-part test and most readers only know about one part:</p>

<ul>
    <li><strong>The income threshold</strong> &mdash; a floor below which a nomination cannot be approved at all</li>
    <li><strong>The annual market salary rate</strong> &mdash; what an Australian doing that same job in that same workplace would be paid</li>
</ul>

<p><strong>The nomination must satisfy both, which in practice means whichever is higher.</strong> If Australians in that role are paid well above the threshold, meeting the threshold alone is not enough. This is the provision that stops sponsorship from becoming a route to cheap labour, and it is the one to point at if a prospective employer suggests a "sponsorship discount" on your salary.</p>

<p>These thresholds are indexed, and the departmental salary requirements page carries a current figure that changes. This guide deliberately does not print an amount, because a number copied from an article is exactly how applicants end up relying on a threshold that lapsed on 1 July. Read the current figure off the Home Affairs salary requirements page before you negotiate.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/visa-sponsorship-jobs-in-australia-skilled-worker.jpg" alt="Trades and technical occupations on the Core Skills Occupation List in Australia" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>The Permanent Residence Rule Everyone Gets Backwards</h2>

<p>Standard advice tells you to check your occupation against the occupation list before pursuing permanent residence. For the main employer-sponsored route, that advice is wrong, and Home Affairs says so directly.</p>

<p><strong>The Temporary Residence Transition streams in the subclass 186 and 187 programmes do not have an occupation list.</strong> Occupation eligibility is based on <strong>the occupation from your most recently held temporary skilled visa</strong>.</p>

<p>Think about what that means for the order of your decisions. The occupation that decides whether you can transition to permanent residence is the one you were sponsored in years earlier. If your occupation later comes off a list, the transition stream is not reading that list &mdash; it is reading your visa history. And conversely, taking a sponsored role in an occupation that does not match your long-term intentions locks that occupation into your permanent residence pathway.</p>

<p>The 186 also has a Direct Entry stream, which does use the combined list and is where caveats bite. The two streams behave differently, and knowing which one you are heading for changes what you should check.</p>

<h2>The Regional Route Is a Separate Visa, Not a Discount</h2>

<p>Subclass 494, the Skilled Employer Sponsored Regional (Provisional) visa, <strong>enables regional employers to address identified labour shortages within their region by sponsoring skilled workers where employers cannot source an appropriately skilled Australian worker</strong>.</p>

<p>Two things follow that guides tend to blur. First, it is a <em>provisional</em> visa: it is a defined stage with its own conditions, not a lighter version of the 482. Second, the incentive that makes regional employers more willing to sponsor is the same fact that makes the shortage real &mdash; they genuinely cannot fill the role locally. That is a strong position for a candidate who actually wants to live regionally, and a poor one for a candidate treating a regional visa as a back door to a capital city.</p>

<h2>Where Sponsorship Actually Happens</h2>

<p>Sponsorship follows shortage, and shortage is occupation-specific rather than industry-wide. Healthcare is the clearest case: nursing occupations appear extensively on the Core Skills Occupation List. Construction and the licensed trades are well represented &mdash; carpenters and electricians both appear. Engineering, information technology, agriculture, hospitality at the chef and management level, and teaching in hard-to-staff subjects all generate nominations.</p>

<p>What does not generate nominations is instructive too. Occupations that Australia has no shortage in do not appear on the list at all, regardless of how many vacancies are advertised. A job being hard to fill locally and an occupation being sponsorable are two different things, and only the second one is decided by the legislative instrument.</p>

<h2>How to Approach It</h2>

<ul>
    <li><strong>Find your ANZSCO code first.</strong> Everything downstream &mdash; list membership, caveats, assessing authority, market salary comparison &mdash; is organised by occupation code, not job title</li>
    <li><strong>Check the list your stream uses,</strong> then check the caveats attached to your occupation, then confirm both in the legislative instrument</li>
    <li><strong>Start the skills assessment early</strong> if your occupation and stream require one; assessing authorities set their own timeframes</li>
    <li><strong>Target approved sponsors.</strong> A business that has sponsored before knows the process; one that has not must first be approved as a sponsor, which adds a stage before your visa can even be lodged</li>
    <li><strong>Negotiate against the market rate,</strong> not the threshold. The threshold is a floor, and a sponsor cannot lawfully pay you less than an Australian doing the same job</li>
    <li><strong>Decide about regional life honestly</strong> before pursuing 494, because the conditions attached to a provisional regional visa are not cosmetic</li>
</ul>

<div style="text-align:center;margin:32px 0;">
    <a href="https://au.indeed.com/q-visa-sponsorship-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        &#128188; See Current Australian Sponsorship Listings &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Is the TSS visa still called the TSS visa?</h3>
<p>No. Home Affairs publishes subclass 482 as the Skills in Demand visa, with a Core Skills stream, a Specialist Skills stream and a Labour agreement stream. Advice still using the TSS name predates the restructure, which is a reason to check the rest of it.</p>

<h3>Which occupation list do I need to be on?</h3>
<p>It depends on your visa stream. The combined list flags four: the Core Skills Occupation List, the MLTSSL, the STSOL and the Regional Occupation List. The subclass 482 Core Skills stream uses the CSOL. The authoritative version is the legislative instrument for that visa programme.</p>

<h3>How long can I stay on a subclass 482 Core Skills visa?</h3>
<p>Up to 4 years. Hong Kong passport holders may stay up to 5 years.</p>

<h3>Do I need my occupation on a list to get permanent residence?</h3>
<p>Not on the main employer route. The Temporary Residence Transition streams of subclass 186 and 187 have no occupation list; eligibility is based on the occupation from your most recently held temporary skilled visa. The 186 Direct Entry stream does use the combined list.</p>

<h3>What salary must a sponsor pay me?</h3>
<p>The nomination must meet the Core Skills Income Threshold and the annual market salary rate for the position, so in practice the higher of the two. The threshold is indexed, so check the current amount on the Home Affairs salary requirements page rather than relying on a figure quoted in an article.</p>

<h3>Do I always need a skills assessment?</h3>
<p>No. The combined list names an assessing authority for each occupation, but whether you need an assessment depends on your visa stream and your occupation. Check the requirement for your specific stream rather than assuming it applies to all sponsored visas.</p>

<h3>What is a caveat and why does it matter?</h3>
<p>A caveat excludes the use of an occupation in certain circumstances, and caveats apply to the ENS Direct Entry stream and the SID visas. It is how an otherwise eligible occupation fails at nomination, so check it before accepting an offer rather than afterwards.</p>

<h3>Is the regional 494 visa easier to get?</h3>
<p>It is a different visa, not an easier one. It lets regional employers sponsor skilled workers where they cannot source an appropriately skilled Australian worker in their region, and it is provisional, with conditions attached to where you live and work.</p>

<h2>People Also Search For</h2>

<ul>
    <li><strong>Skills in Demand visa 482 occupation list</strong> &mdash; the Core Skills stream uses the Core Skills Occupation List, confirmed in the legislative instrument</li>
    <li><strong>TSMIT 2026 Australia</strong> &mdash; the threshold for a 482 nomination is now the Core Skills Income Threshold, indexed and published by Home Affairs</li>
    <li><strong>Companies that sponsor visas in Australia</strong> &mdash; only businesses approved as sponsors can nominate, which is the first of three separate approvals</li>
    <li><strong>186 visa Temporary Residence Transition requirements</strong> &mdash; no occupation list applies; eligibility follows your most recently held temporary skilled visa</li>
    <li><strong>494 regional sponsored visa Australia</strong> &mdash; provisional, for regional employers with identified local shortages</li>
    <li><strong>ANZSCO code list Australia</strong> &mdash; the code, not the job title, decides list membership, caveats and the assessing authority</li>
</ul>

<h2>More Job Guides</h2>

<ul>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a></li>
    <li><a href="/blog/construction-worker-jobs-in-australia">Construction Worker Jobs in Australia</a></li>
    <li><a href="/blog/plumber-jobs-in-australia">Plumber Jobs in Australia</a></li>
    <li><a href="/blog/no-experience-jobs-in-australia">No Experience Jobs in Australia</a></li>
    <li><a href="/blog/sales-jobs-in-australia">Sales Jobs in Australia</a></li>
    <li><a href="/blog/taxi-driver-jobs-in-australia">Taxi Driver Jobs in Australia</a></li>
    <li><a href="/blog/personal-care-assistant-jobs-in-australia">Personal Care Assistant Jobs in Australia</a> &mdash; a genuinely sponsorable aged care route and its real award pay.</li>
    <li><a href="/blog/how-to-become-a-sales-representative-in-australia">How to Become a Sales Representative in Australia</a> &mdash; the licence, working rights and training employers ask for, and the award floor under field sales pay.</li>
    <li><a href="/blog/how-to-become-an-auto-mechanic-in-australia">How to Become an Auto Mechanic in Australia</a> &mdash; the apprenticeship, the AUR30620 qualification, award pay for apprentices and the routes for overseas-trained mechanics.</li>
    <li><a href="/blog/how-foreign-workers-can-get-a-job-in-australia">How Foreign Workers Can Get a Job in Australia</a> &mdash; every route in compared: employer sponsorship, skilled visas without a job offer, working holidays, the PALM scheme and student work rights.</li>
    <li><a href="/blog/how-to-get-a-delivery-job-in-australia">How to Get a Delivery Job in Australia</a> &mdash; award pay, the new gig minimum standards order, ABN and GST rules.</li>
    <li><a href="/blog/how-to-apply-for-bhp-mining-jobs-in-australia">How to Apply for BHP Mining Jobs in Australia</a> &mdash; the no-experience pathways, what the FutureFit Academy really offers and official pay benchmarks.</li>
    <li><a href="/blog/how-to-apply-for-qantas-ground-staff-jobs-in-australia">How to Apply for Qantas Ground Staff Jobs in Australia</a> &mdash; the pay Qantas will not publish, from the agreement that does.</li>
    <li><a href="/blog/how-to-apply-for-woolworths-supermarket-jobs-in-australia">How to Apply for Woolworths Supermarket Jobs in Australia</a> &mdash; why supermarket work sits on no occupation list, and the visas that do allow it.</li>
    <li><a href="/blog/how-to-apply-for-csl-laboratory-jobs-in-australia">How to Apply for CSL Laboratory Jobs in Australia</a> &mdash; a worked example: which lab codes are sponsorable and which are PR-only.</li>
</ul>
HTML;
    }
}
