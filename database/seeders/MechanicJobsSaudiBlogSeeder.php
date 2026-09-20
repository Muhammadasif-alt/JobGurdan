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
 * "Mechanic Jobs in Saudi Arabia" — a trade guide rather than one vacancy, so
 * the apply link goes to an Indeed search and the post carries no JobPosting
 * markup. The driver, construction and no-experience guides own their own
 * occupations; this one owns the skills test that stands between a mechanic
 * and the work visa, and the line between what the Labour Law guarantees and
 * what is only a contract term.
 *
 * Corrections to the draft:
 *
 * 1. It quotes SAR 2,500 to SAR 5,500 a month, and SAR 6,000 to SAR 9,000+
 *    for experienced mechanics, with no source. Both bands are removed. The
 *    Labour Law sets no wage figure at all; Article 89 only lets the Council of
 *    Ministers set a minimum wage, and the SAR 4,000 figure is the Nitaqat
 *    threshold at which a Saudi national counts as one worker (ministerial
 *    decision reported by SPA on 23 November 2020).
 *
 * 2. It says a "certificate attestation process may be required". For this
 *    trade the real gate is a skills test. The ministry's Professional
 *    Examination program runs theoretical and practical tests, was first
 *    launched in Pakistan, India and Bangladesh, lists automotive mechanics
 *    and automotive electricity among its specialisations (Sri Lanka launch,
 *    12 July 2023, 23 specialisations targeted), and treats the certificate as
 *    an additional recruitment requirement. Heavy-equipment and industrial
 *    mechanics are not named in that announcement, so the guide does not claim
 *    they are covered.
 *
 * 3. It says "never pay upfront fees" without the rule behind it. Article 40
 *    of the Labour Law puts recruitment fees, Iqama and work permit fees and
 *    renewals, profession-change fees, exit and re-entry visas and the return
 *    ticket at the end of the contract on the employer.
 *
 * 4. It gives Pakistani applicants no way to check an agent. The Ministry of
 *    Overseas Pakistanis' BE&OE guidance: an Overseas Employment Promoter must
 *    hold a Federal Government licence, can be checked against the active
 *    licence list or at the Protector of Emigrants office, and every payment
 *    needs a receipt; complaints against unlicensed agents go to the FIA under
 *    Rule 29(4) of the Emigration Rules, 1979. The permitted service-charge
 *    amounts are not published here because the BE&OE page stating them could
 *    not be opened.
 *
 * 5. It lists housing, transport, an annual ticket and medical insurance as
 *    standard benefits. The law requires accommodation and meals only in
 *    remote locations, mines, quarries and oil exploration centres
 *    (Article 147), transport only where the workplace has no regular service
 *    (Article 148), and health care under Article 144. Annual tickets and city
 *    housing are contract terms. What is guaranteed everywhere is the
 *    end-of-service award (Article 84) and at least 21 days' annual leave,
 *    30 after five years (Article 109).
 *
 * 6. Its advice on converting a driving licence is removed, because the
 *    official traffic pages could not be opened to confirm it.
 *
 * 7. Its "strong and consistent demand" driven by Vision 2030 is dropped; no
 *    official figure for mechanic demand was found to support it.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MechanicJobsSaudiBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://sa.indeed.com/q-mechanic-jobs.html';

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
        $title = 'Mechanic Jobs in Saudi Arabia';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Auto, diesel and heavy equipment mechanic work in Saudi Arabia: the skills test before the visa, the costs the Labour Law puts on the employer, which benefits are guaranteed, and how to check an agent first.',
                'content' => $content,
                'featured_image' => 'blogs/mechanic-jobs-in-saudi-arabia.jpg',
                'tags' => 'mechanic jobs in saudi arabia, auto mechanic jobs saudi arabia, diesel mechanic jobs saudi arabia, heavy equipment mechanic saudi arabia, saudi skills verification program, professional examination saudi arabia, mechanic jobs for pakistani, saudi labour law benefits',
                'meta_title' => 'Mechanic Jobs in Saudi Arabia: Skills Test and Your Rights',
                'meta_description' => 'Mechanic jobs in Saudi Arabia: the skills test for auto mechanics, the costs the Labour Law puts on the employer, and how to check an agent.',
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
            ['name' => 'Saudi Employers & Licensed Agencies (Aggregated)'],
            ['type' => 'Agency', 'display_reference' => 'saudi-employers-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Saudi Arabia'],
            ['area' => 'Riyadh, Jeddah and Dammam', 'country' => 'Saudi Arabia']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Mechanic — Automotive, Diesel and Heavy Equipment',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time, with hours and rest days set in the contract under the Saudi Labour Law',
                'language' => 'Basic English or Arabic',
                // No source supports a mechanic pay band, and the Labour Law
                // sets no wage figure for expatriates, so none is printed.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Auto, diesel and heavy equipment mechanic roles in Saudi Arabia. Check whether your trade needs the skills test before the visa, and never pay recruitment fees.',
                'seo_keywords' => 'mechanic jobs in saudi arabia, auto mechanic jobs riyadh, diesel mechanic jobs dammam, heavy equipment mechanic saudi arabia, auto electrician jobs saudi arabia, saudi skills verification program',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Car dealerships, independent workshops, fleet and logistics operators, construction contractors and industrial plants across Riyadh, Jeddah and Dammam hire foreign mechanics &mdash; light vehicle and auto mechanics, auto electricians, diesel and heavy vehicle mechanics, and heavy equipment and plant mechanics.</p>

<h3>The skills test comes before the visa</h3>
<p>The Ministry of Human Resources and Social Development runs a Professional Examination program with theoretical and practical tests. It was first launched in Pakistan, India and Bangladesh, and automotive mechanics and automotive electricity are among its named specialisations. Where it applies, the certificate is an additional requirement during recruitment, so ask the employer or agency whether your visa profession needs it and book the test early.</p>

<h3>Requirements</h3>
<ul>
    <li>A trade certificate or diploma in automotive, diesel or mechanical work, and documented workshop experience</li>
    <li>A pass in the Professional Examination where your specialisation and country are covered</li>
    <li>Diagnostic skills, including scan tools for modern vehicles; OEM training helps for dealership roles</li>
    <li>A passport, police clearance and a medical examination before the visa is issued</li>
    <li>Basic English or Arabic for job cards, customers and supervisors</li>
</ul>

<h3>What the law guarantees, and what it does not</h3>
<ul>
    <li>The employer pays recruitment, Iqama and work permit fees, exit and re-entry visas and your return ticket at the end of the contract (Labour Law Article 40)</li>
    <li>An end-of-service award, and at least 21 days of paid annual leave, rising to 30 after five years</li>
    <li>Accommodation and meals are legally required only in remote locations, mines, quarries and oil exploration centres &mdash; elsewhere, housing, transport and annual tickets are contract terms to get in writing</li>
    <li>No pay band is quoted here, because the Labour Law sets no wage figure for expatriate workers</li>
</ul>

<h3>Before you pay anyone anything</h3>
<p><strong>Never pay for a visa or a job offer.</strong> Pakistani applicants should use only an Overseas Employment Promoter licensed by the Federal Government, check the licence on the Bureau of Emigration and Overseas Employment list or at the Protector of Emigrants office, and insist on a receipt for every payment.</p>

<p><strong>Note:</strong> pay, hours and contract terms are set by the individual employer or agency &mdash; not by JobGader. Listings on aggregator sites vary in quality; verify the employer and the contract before travelling.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Saudi Arabia runs on vehicles. Private cars, delivery fleets, trucks, buses and the plant on construction and industrial sites all need servicing, which keeps <strong>mechanic jobs in Saudi Arabia</strong> open to skilled foreign workers. But a mechanic's route into the Kingdom has one step most job adverts never mention: for several trades the ministry now tests your skills before you are recruited. This guide covers that test, the costs the Labour Law puts on your employer, which benefits are guaranteed and which are only promises, and how to check an agent before you hand over your passport.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-mechanic-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔧 Browse Mechanic Jobs in Saudi Arabia &rarr;
    </a>
</div>

<h2>Types of Mechanic Jobs Open to Foreigners</h2>

<h3>1. Light Vehicle and Auto Mechanics</h3>
<p>Servicing and repairing cars and light commercial vehicles at dealerships, quick-service centres and independent workshops: engines, brakes, suspension, transmissions and routine maintenance. Dealership roles increasingly want diagnostic scan-tool experience and brand training.</p>

<h3>2. Auto Electricians</h3>
<p>Wiring, starting and charging systems, sensors, electronic control units and vehicle air conditioning. As vehicles carry more electronics, this is the specialisation where diagnostic skill matters most.</p>

<h3>3. Diesel and Heavy Vehicle Mechanics</h3>
<p>Trucks, buses, trailers and delivery fleets for logistics, transport and distribution companies. Work is often shift-based, and some roles are on the road or at a depot rather than in a workshop.</p>

<h3>4. Heavy Equipment and Plant Mechanics</h3>
<p>Excavators, loaders, cranes, generators and compressors for construction, mining and industrial contractors. These postings are the most likely to be on a remote site or project camp, which changes what the law requires your employer to provide.</p>

<h3>5. Industrial and Maintenance Mechanics</h3>
<p>Pumps, conveyors and production machinery in factories, plants and facilities. Mechanical maintenance experience in a regulated industrial setting is what employers look for here.</p>

<h2>The Skills Test Before the Visa</h2>

<p>Most guides on this keyword say a certificate &quot;may need attesting&quot; and leave it there. For mechanics the bigger step is a test. The Ministry of Human Resources and Social Development runs a <strong>Professional Examination</strong> program, part of its Professional Accreditation system, to stop unqualified workers entering skilled trades. Here is what the ministry itself has said about it:</p>

<ul>
    <li>The program was first launched in Pakistan, India and Bangladesh, then extended to other countries.</li>
    <li>When it was activated in Sri Lanka, announced on 12 July 2023, it covered five specialisations out of 23 targeted by the ministry: plumbing, electricity, automotive electricity, automotive mechanics, and refrigeration and air conditioning.</li>
    <li>The worker registers on the Professional Examination portal, books theoretical and practical tests, has their identity checked at the centre, and receives a certificate after passing.</li>
    <li>The certificate is treated as an additional requirement for the worker during the recruitment process.</li>
</ul>

<p>What this means for you: if you are an auto mechanic or auto electrician from one of the covered countries, expect to sit a practical and theoretical test before your recruitment can go ahead. The announcement does not name heavy equipment or industrial mechanics, so do not assume either way &mdash; ask the employer or agency which profession will be written on your visa and whether that profession needs the certificate. Book the test through the official <strong>SVP</strong> portal (svp-international.pacc.sa) rather than through a middleman, and treat anyone selling a &quot;guaranteed pass&quot; as a scam.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mechanic-jobs-in-saudi-arabia-workshop.jpg" alt="Mechanic working under the bonnet in a Saudi workshop with the Riyadh skyline behind — mechanic jobs in Saudi Arabia" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Mechanics in Saudi Arabia Are Paid</h2>

<p>Guides on this keyword quote bands such as SAR 2,500 to SAR 5,500 a month, rising to SAR 6,000 to SAR 9,000 for experienced mechanics. None of them cites a source, and we could not find an official one, so we do not print a band. Two facts are on record instead.</p>

<p>First, <strong>the Labour Law sets no wage figure for expatriate workers</strong>. Article 89 says only that the Council of Ministers may set a minimum wage when necessary. Second, the SAR 4,000 you will see quoted is not a minimum wage for you. Under a ministerial decision reported by the Saudi Press Agency on 23 November 2020, it is the salary at which a Saudi national counts as one full worker in the Nitaqat Saudization calculation. It has nothing to do with a foreign mechanic's pay.</p>

<p>So judge every offer by its written contract: basic salary, allowances, overtime, housing, transport, medical cover and flights, each itemised. Two offers with the same basic salary can be worth very different amounts once those lines are filled in.</p>

<h2>Costs the Law Puts on Your Employer</h2>

<p>This is the rule behind the advice never to pay for a Saudi job. <strong>Article 40 of the Labour Law</strong> says the employer bears:</p>

<ul>
    <li>the fees for recruiting a non-Saudi worker;</li>
    <li>the fees for issuing and renewing the Iqama (residence permit) and the work permit, and the fines for renewing them late;</li>
    <li>the fees for a change of profession;</li>
    <li>exit and re-entry visa fees; and</li>
    <li>the worker's return ticket home at the end of the working relationship.</li>
</ul>

<p>The worker pays their own way home only if they are unfit for work or choose to leave without a legitimate reason. An agent asking you for &quot;visa money&quot; is asking you to pay costs the law gives to your employer.</p>

<h2>Guaranteed Benefits Versus Contract Promises</h2>

<p>Adverts for <strong>mechanic jobs in Saudi Arabia with free accommodation</strong> list housing, transport, food and an annual air ticket as if they came with every job. Some of these are legal duties; most are negotiated terms. Know which is which before you sign.</p>

<h3>Guaranteed by the Labour Law</h3>
<ul>
    <li><strong>End-of-service award (Article 84)</strong> &mdash; half a month's wage for each of the first five years and one month's wage for each year after that, calculated on your last wage and paid for part years in proportion. If you resign, Article 85 cuts it to one third after two to five years, two thirds after five to ten years, and the full award after ten years.</li>
    <li><strong>Annual leave (Article 109)</strong> &mdash; paid leave of not less than 21 days a year, rising to not less than 30 days after five consecutive years with the same employer. You cannot give it up or take cash instead while you are employed.</li>
    <li><strong>Health care (Article 144)</strong> &mdash; the employer must provide preventive and therapeutic health care, taking into account the Cooperative Health Insurance Law.</li>
    <li><strong>Wages into a bank account (Article 90)</strong> &mdash; firms must pay wages into workers' accounts through approved banks in the Kingdom.</li>
</ul>

<h3>Required only in some workplaces</h3>
<ul>
    <li><strong>Accommodation and meals (Article 147)</strong> &mdash; required for employers operating in remote locations, mines, quarries and oil exploration centres. This matters for heavy equipment mechanics on project sites.</li>
    <li><strong>Daily transport (Article 148)</strong> &mdash; required where the workplace is not served by regular transport at times that fit your working hours.</li>
</ul>

<h3>Contract terms, not legal rights</h3>
<ul>
    <li><strong>City housing or a housing allowance</strong> for a workshop job in Riyadh, Jeddah or Dammam.</li>
    <li><strong>An annual or two-yearly air ticket.</strong> Article 40 covers the ticket home at the end of the contract, not a holiday flight each year.</li>
    <li><strong>Food allowance, tool allowance and overtime rates beyond the legal minimum.</strong></li>
</ul>

<p>If a benefit is not written into your contract, assume you will not get it.</p>

<h2>Mechanic Jobs for Pakistani, Indian and Bangladeshi Applicants</h2>

<p>These three countries were the first to have the Professional Examination program, so applicants from them should expect the skills test for covered trades. For Pakistani applicants, the Ministry of Overseas Pakistanis and Human Resource Development sets out how to stay safe with the Bureau of Emigration and Overseas Employment (BE&amp;OE):</p>

<ul>
    <li><strong>Use a licensed Overseas Employment Promoter (OEP).</strong> An OEP must hold a licence from the Federal Government.</li>
    <li><strong>Check the licence yourself</strong> against the valid OEP licence list on the BE&amp;OE website, or at the Protector of Emigrants office.</li>
    <li><strong>Check the demand and the terms.</strong> Read the terms and conditions carefully and counter-verify them with the Protector of Emigrants.</li>
    <li><strong>Insist on a receipt</strong> for any payment you make.</li>
    <li><strong>Get protected.</strong> Under the Emigration Ordinance 1979, every Pakistani going abroad on a work visa has to register for protection, and the Foreign Service Agreement is countersigned by the Protector of Emigrants so its terms can be enforced.</li>
    <li><strong>Report fake agents.</strong> Complaints against someone who is not a valid OEP are referred to the Federal Investigation Agency under Rule 29(4) of the Emigration Rules, 1979.</li>
</ul>

<p>Indian applicants should use a recruiting agent registered on the eMigrate system, and Bangladeshi applicants an agency registered with BMET. Whatever your country, ask for the employer's name and confirm the company exists before you pay for a medical, a test or anything else.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mechanic-jobs-in-saudi-arabia-garage.jpg" alt="Young mechanic tightening an engine component in a busy Saudi garage — auto mechanic jobs in Saudi Arabia" loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Skills Employers Look For</h2>

<ul>
    <li><strong>Diagnostics</strong> &mdash; reading fault codes with scan tools and tracing electrical faults, not only replacing parts.</li>
    <li><strong>Engine, transmission, brake and suspension work</strong> on the vehicle types the employer runs.</li>
    <li><strong>Diesel systems</strong> for fleet, truck and plant roles, including hydraulics for heavy equipment.</li>
    <li><strong>Vehicle air conditioning</strong>, which gets heavy use in the Saudi climate.</li>
    <li><strong>Safe workshop practice</strong> with lifts, jacks, tools and fluids.</li>
    <li><strong>Job cards and basic English or Arabic</strong> to record work and talk to customers and supervisors.</li>
</ul>

<h2>How to Apply for Mechanic Jobs in Saudi Arabia</h2>

<ol>
    <li><strong>Write a trade-specific CV.</strong> Lead with your specialisation, years of documented experience, vehicle brands or equipment types, and diagnostic tools you use.</li>
    <li><strong>Gather your trade certificates and experience letters</strong> early, because they are what the test and the visa file rest on.</li>
    <li><strong>Search real listings</strong> on Indeed, Bayt, NaukriGulf and GulfTalent, and on dealership and contractor career pages.</li>
    <li><strong>Apply through the employer or a licensed agency</strong>, and verify the agency's licence with your own government.</li>
    <li><strong>Confirm whether your visa profession needs the Professional Examination</strong>, and book it through the official portal.</li>
    <li><strong>Read the contract line by line</strong> before the medical and visa stamping: basic salary, allowances, housing, transport, tickets and hours.</li>
</ol>

<h2>Career Growth for Mechanics in the Kingdom</h2>

<p>The usual ladder is mechanic, then senior or lead mechanic, then workshop supervisor or service advisor, and on to workshop or fleet maintenance manager. Specialising pays off: auto electrics and diagnostics, heavy equipment hydraulics, and brand-certified dealership training all make you harder to replace. Keep copies of every training certificate you earn, because they strengthen your next contract, in Saudi Arabia or elsewhere in the Gulf.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://sa.indeed.com/q-mechanic-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        🔍 Search Mechanic Job Listings in Saudi Arabia &rarr;
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Do mechanics need a skills test to work in Saudi Arabia?</h3>
<p>Many do. The ministry's Professional Examination program tests workers with theoretical and practical exams, and automotive mechanics and automotive electricity are among its named specialisations. It started in Pakistan, India and Bangladesh. Where it applies, the certificate is an extra requirement during recruitment.</p>

<h3>What is the salary of a mechanic in Saudi Arabia?</h3>
<p>It depends on the employer, trade and package, and we found no official pay band to quote. The Labour Law sets no wage figure for expatriate workers, so compare offers on the full written contract, including allowances, housing and flights.</p>

<h3>Is SAR 4,000 the minimum wage for mechanics?</h3>
<p>No. SAR 4,000 is the salary at which a Saudi national counts as one full worker under the Nitaqat Saudization rules. It does not apply to foreign workers.</p>

<h3>Who pays for the Saudi work visa and Iqama?</h3>
<p>The employer. Article 40 of the Labour Law puts recruitment fees, Iqama and work permit fees and renewals, exit and re-entry visas and the return ticket at the end of the contract on the employer.</p>

<h3>Is free accommodation guaranteed for mechanics?</h3>
<p>Only in some workplaces. The law requires accommodation and meals in remote locations, mines, quarries and oil exploration centres. For a workshop job in a city, housing is a contract term you should get in writing.</p>

<h3>How much annual leave does a mechanic get in Saudi Arabia?</h3>
<p>At least 21 days of paid leave a year, rising to at least 30 days after five consecutive years with the same employer, under Article 109 of the Labour Law.</p>

<h3>How can Pakistani mechanics check a recruitment agent?</h3>
<p>Use only a licensed Overseas Employment Promoter, check the licence on the BE&amp;OE list or at the Protector of Emigrants office, get a receipt for every payment, and report fake agents, whose cases go to the FIA.</p>

<h3>Can heavy equipment mechanics apply?</h3>
<p>Yes. Construction, mining and industrial contractors hire them. The skills test announcement names automotive trades rather than heavy equipment, so ask which profession your visa will carry and whether it needs the certificate.</p>

<h2>People Also Search For</h2>

<h3>Auto mechanic jobs in Saudi Arabia</h3>
<p>Dealerships and workshops in Riyadh, Jeddah and Dammam hire foreign auto mechanics. Expect the ministry's skills test if your country and specialisation are covered.</p>

<h3>Diesel mechanic jobs in Saudi Arabia</h3>
<p>Fleet, logistics and transport operators hire diesel and heavy vehicle mechanics, often on shift work at depots.</p>

<h3>Heavy equipment mechanic jobs in Saudi Arabia</h3>
<p>Mostly with construction, mining and industrial contractors, often on remote sites where the employer must provide accommodation and meals by law.</p>

<h3>Saudi skills verification program for mechanics</h3>
<p>The Professional Examination program runs theoretical and practical tests. Book through the official portal, never through a middleman.</p>

<h3>Mechanic jobs in Saudi Arabia for Pakistani applicants</h3>
<p>Apply through a licensed Overseas Employment Promoter, check the licence with BE&amp;OE, and register for protection before you travel.</p>

<h3>Mechanic jobs in Saudi Arabia with free visa</h3>
<p>The employer pays recruitment and Iqama fees by law. Anyone charging you for the visa itself is asking for money the law says the employer pays.</p>

<h2>More Job Guides</h2>

<p>Comparing trade and Gulf routes? These cover the rest:</p>

<ul>
    <li><a href="/blog/driver-jobs-in-saudi-arabia-for-foreigners">Driver Jobs in Saudi Arabia for Foreigners</a> &mdash; the Labour Law and Musaned split, and what each driving route pays.</li>
    <li><a href="/blog/construction-jobs-in-saudi-arabia-with-visa-sponsorship">Construction Jobs in Saudi Arabia with Visa Sponsorship</a> &mdash; giga-project hiring, and who actually employs you on site.</li>
    <li><a href="/blog/no-experience-jobs-in-saudi-arabia">No Experience Jobs in Saudi Arabia</a> &mdash; which entry-level roles are still open to foreign workers.</li>
    <li><a href="/blog/security-guard-jobs-in-saudi-arabia">Security Guard Jobs in Saudi Arabia</a> &mdash; another Saudi route, and the rules that decide who can hold the job.</li>
    <li><a href="/blog/welder-jobs-in-canada">Welder Jobs in Canada</a> &mdash; a skilled trade, and how certification differs by province.</li>
    <li><a href="/blog/electrician-jobs-in-uk">Electrician Jobs in UK</a> &mdash; a qualified trade with a real certification barrier.</li>
    <li><a href="/blog/cdl-driver-jobs-in-usa">CDL Driver Jobs in USA</a> &mdash; heavy vehicles in America, and the licence classes behind them.</li>
    <li><a href="/blog/heavy-truck-driver-jobs-in-saudi-arabia">Heavy Truck Driver Jobs in Saudi Arabia</a> &mdash; the drivers who run the fleets you would service, and their licence path.</li>
    <li><a href="/blog/how-to-apply-for-aramco-engineering-jobs-in-saudi-arabia">How to Apply for Aramco Engineering Jobs in Saudi Arabia</a> &mdash; where the same technical skills go with a degree behind them.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal or immigration advice. Saudi labour and visa rules change &mdash; confirm current requirements through the Ministry of Human Resources and Social Development, the Qiwa platform, or your own country's overseas employment authority before paying any fee or signing a contract.</p>
HTML;
    }
}
