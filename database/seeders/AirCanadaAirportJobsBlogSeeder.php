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
 * "How to Apply for Air Canada Airport Jobs" — a guide that has to tell most of
 * its likely readers no, and then tell them something useful instead. Air Canada
 * publishes its ramp pay, which is rare and worth having; it also requires a
 * Transport Canada clearance that only an employer can start and that needs five
 * years of verifiable history, which settles the overseas question.
 *
 * Corrections to the draft (checked against careers.aircanada.com, its Phenom
 * job API, Air Canada's anti-fraud guide and tc.canada.ca, 22 September 2026):
 *
 * 1. The draft tells Pakistani readers they can apply from Pakistan. Every
 *    airport advert makes work authorisation "the sole responsibility of the
 *    candidates", and airport roles additionally need a Transport Canada
 *    security clearance the employer must initiate. That is a no, and the
 *    guide says so rather than leaving a false hope in place.
 *
 * 2. The draft gives no URL at all, only a "[Air Canada Careers]" placeholder.
 *    The portal is careers.aircanada.com/ca/en; the old Taleo address is dead.
 *
 * 3. Two category names in the draft do not exist. There is no "Customer
 *    Services (airport & call centre)" category (they are separate: Airport
 *    Operations and Contact Centre), and no "building and facility
 *    maintenance" category (it is Corporate Real Estate).
 *
 * 4. The draft says there is no single official salary. Air Canada publishes
 *    a rate on the ramp adverts themselves, so this guide quotes it.
 *
 * 5. The draft omits Air Canada's own anti-fraud guidance, which names the
 *    only three channels it advertises on. For an audience targeted by visa
 *    scams, that is the most protective thing on the page.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AirCanadaAirportJobsBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://careers.aircanada.com/ca/en/c/airport-operations-jobs';

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
        $title = 'How to Apply for Air Canada Airport Jobs';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Air Canada publishes what its ramp agents earn, which is rare. It also needs a Transport Canada clearance only an employer can start. Here is who can realistically be hired, and who cannot.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-air-canada-airport-jobs.jpg',
                'tags' => 'air canada jobs, air canada careers, airport jobs canada, ramp agent jobs, customer experience specialist, transport canada security clearance, raic, canada work permit',
                'meta_title' => 'Air Canada Airport Jobs: How to Apply',
                'meta_description' => 'Air Canada airport jobs: the real careers portal, what ramp agents are paid, the Transport Canada clearance you need, and who can actually be hired.',
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
            ['name' => 'Air Canada'],
            ['type' => 'Company', 'display_reference' => 'air-canada']
        );

        $location = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        Job::updateOrCreate(
            [
                'position' => 'Airport Operations, Air Canada, Canadian Airports',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Shift work including nights, weekends and holidays',
                'language' => 'English, with French or another language required at some stations',
                // Air Canada prints this rate on its own ramp agent adverts,
                // under the IAMAW collective agreement. It is the employer's
                // published figure, not an estimate site's.
                'salary_currency' => 'CAD',
                'salary_period' => 'Hourly',
                'salary_minimum' => 23.36,
                'salary_maximum' => 38.15,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Ramp agent, customer experience and ground support roles at Canadian airports. You must already be eligible to work in Canada.',
                'seo_keywords' => 'air canada jobs, ramp agent jobs canada, airport jobs canada, air canada careers, customer experience specialist',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the airport roles Air Canada advertises across Canadian airports, not a single vacancy and not a job advertised by JobGader. Applications are made on Air Canada's own careers portal.</p>

<h3>Read this before you apply</h3>
<p>Air Canada states on every airport advert that candidates must be eligible to work in the country of interest at the time any offer is made, and that obtaining any required work permits or visas is the sole responsibility of the candidate. Air Canada does not sponsor. If you are not already entitled to work in Canada, you cannot be hired into these roles.</p>

<h3>What the work involves</h3>
<ul>
    <li>Ramp Agent (Station Attendant) &mdash; baggage and aircraft ground handling, full and part time.</li>
    <li>Customer Experience Specialist &mdash; check-in, boarding and passenger support in the terminal.</li>
    <li>Ground Support Equipment Mechanic and Helper &mdash; maintaining airport vehicles and equipment.</li>
</ul>

<h3>Pay</h3>
<p>Air Canada publishes the rate on its ramp agent adverts: "Starting Salary: $23.36/ hour - Up to $38.15 after 8 years of continuous service as outlined in the applicable Collective Agreement". The category is Unionized (IAMAW). Other airport roles carry their own rates on their own adverts.</p>

<h3>Security clearance</h3>
<p>Airport adverts require the ability to obtain and maintain applicable transportation security clearances. Transport Canada states the clearance application must be initiated by the employer, is a prerequisite for a Restricted Area Identity Card, and requires verifiable information covering the past five years of work, study and residency.</p>

<h3>Language</h3>
<p>Requirements vary by station. Quebec City roles require fluent English and French. Toronto and Ottawa adverts ask for English plus one of a list that includes Hindi, Punjabi, Arabic, Mandarin, Cantonese and Spanish. Ramp agent adverts carry no language requirement.</p>

<p>Pay, eligibility, clearance and immigration rules are set by Air Canada, Transport Canada and the Government of Canada &mdash; not by JobGader. Confirm the requirements on the live posting, and never pay anyone to secure a job.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>We are going to start with the hard part, because you deserve it before you spend a week on applications.</p>

<p><strong>If you are reading this from outside Canada with no Canadian status, you cannot be hired into an Air Canada airport job.</strong> Not "it is difficult". Two separate locks, and neither of them opens from abroad.</p>

<p>That is not the end of the article. Air Canada publishes things most airlines hide &mdash; including exactly what a ramp agent earns &mdash; and there is a real route for people already in Canada. Let us go through all of it honestly.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-air-canada-airport-jobs-ramp.jpg" alt="Ground crew loading baggage beside an aircraft on the apron at a Canadian airport" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Air Canada had 13 Airport Operations vacancies open across Canada when we checked its careers portal.</figcaption>
</figure>

<h2>The Two Locks</h2>

<p><strong>Lock one: work authorisation.</strong> We opened Air Canada's airport adverts and this clause is on every single one of them, word for word:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Candidates must be eligible to work in the country of interest, at the time any offer of employment is made and seeking any required work permits/visas or other authorizations which may be required is the sole responsibility of the candidates applying for this position"</p>

<p>Newer postings go further and require proof of eligibility before your start date. Air Canada's own careers FAQ says the same thing: to be considered, "you must be legally entitled to work in the country where the position is located."</p>

<p>The word "sponsorship" does not appear in any advert we read. Neither does "LMIA". <strong>Air Canada does not sponsor these roles.</strong> It expects you to arrive already allowed to work.</p>

<p><strong>Lock two: the Transport Canada security clearance.</strong> This is the one almost nobody writes about, and it is the harder of the two. Nearly every airport advert requires the "ability to obtain and maintain any applicable transportation security clearances".</p>

<p>Transport Canada's own rules explain why that is decisive:</p>

<ul>
    <li>The clearance "is a pre-requisite to being issued a Restricted Area Identity Card (RAIC)" &mdash; the pass that lets you go airside at all.</li>
    <li><strong>"The clearance application process must be initiated by the employer."</strong> You cannot apply for one yourself, in advance, to make yourself more hireable.</li>
    <li>Applicants must provide "verifiable information on the past five years of their work, study and residency", checked with IRCC, the RCMP and CSIS.</li>
    <li>An application is flagged as complex if you have "spent more than six months outside Canada/U.S. in the last 5 years".</li>
</ul>

<p>Read that last point again. Someone who has just arrived in Canada, let alone someone still overseas, trips that flag automatically.</p>

<h2>So Who Can Actually Get These Jobs?</h2>

<p>People already living in Canada with the right to work: citizens, permanent residents, and holders of an open work permit. That is the honest answer, and it is who the rest of this guide is written for.</p>

<p>If you are outside Canada, the useful sequence is the opposite of what most guides tell you. <strong>Sort out the immigration route first, with the Government of Canada, then come back to this employer.</strong> Applying to Air Canada from Pakistan does not advance your immigration case by a single day.</p>

<h2>Where the Jobs Actually Are</h2>

<p>The portal moved. Any guide still linking aircanada.taleo.net is out of date &mdash; that address now just redirects.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://careers.aircanada.com/ca/en/c/airport-operations-jobs" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Open Air Canada Airport Operations Jobs &rarr;</a>
</p>

<p>The main careers site is careers.aircanada.com/ca/en. When we checked, Air Canada had <strong>57 vacancies company-wide and 13 in Airport Operations</strong> &mdash; a small board, so check it often rather than assuming something is always open.</p>

<p>The live airport roles were:</p>

<ul>
    <li><strong>Ramp Agent (Station Attendant)</strong> &mdash; Dorval, Quebec City, Moncton, Vancouver</li>
    <li><strong>Bilingual Customer Experience Specialist</strong> &mdash; Toronto, Ottawa, Quebec City</li>
    <li><strong>Ground Support Equipment Mechanic</strong> and Helper &mdash; Dorval, Vancouver, Toronto</li>
</ul>

<p>Two things to note. Several are tagged <strong>"Proactive hiring"</strong>, which means a pipeline requisition rather than a seat waiting for you. And two category names you may have read elsewhere do not exist: there is no "Customer Services (airport &amp; call centre)" category &mdash; Airport Operations and Contact Centre are separate &mdash; and facilities roles sit under <strong>Corporate Real Estate</strong>, not "building and facility maintenance".</p>

<h2>What Does It Pay?</h2>

<p>Here is where Air Canada does better than most airlines. It prints the rate on the advert:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Role</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Air Canada's published rate</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Ramp Agent (Station Attendant)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>$23.36 per hour, rising to $38.15 after 8 years</strong> of continuous service under the collective agreement</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Category</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Unionized (IAMAW)</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Branch</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">Airports North America</td>
        </tr>
    </tbody>
</table>

<p>That is Air Canada's own wording, not an estimate. Because these roles are unionised, the progression is contractual rather than discretionary &mdash; the eight-year ladder is written down. Other airport roles carry their own rates on their own adverts, so read the one you are applying to.</p>

<p>Relevant if you are aiming at the terminal rather than the ramp: in June 2026 Air Canada ratified a new four-year collective agreement with Unifor covering roughly 6,000 contact centre, customer relations, concierge and airport in-terminal employees, running until 28 February 2030.</p>

<h2>The Language Edge Nobody Mentions</h2>

<p>This is the one part of the picture that genuinely favours South Asian applicants who are already in Canada.</p>

<p>Air Canada's Toronto and Ottawa customer experience adverts ask for English plus one of a specific list &mdash; and that list includes <strong>Hindi, Punjabi, Arabic, Mandarin, Cantonese, Spanish, Japanese, Korean, Italian, Hebrew and Portuguese</strong>. Ottawa's advert does not even require French.</p>

<p>Quebec City is different: there, fluent English <em>and</em> French is a hard requirement. Ramp agent adverts carry no language requirement at all.</p>

<p>So if you speak Punjabi or Hindi and you are landed in Toronto, say so prominently. It is a listed requirement, not a nice-to-have.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-air-canada-airport-jobs-terminal.jpg" alt="Airline customer service agents assisting passengers at an airport check-in area" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Toronto and Ottawa customer experience roles list Hindi, Punjabi and Arabic among the languages they want.</figcaption>
</figure>

<h2>Air Canada Names the Only Places It Advertises</h2>

<p>This is the most protective thing on this page, and we want you to read it twice. Air Canada's anti-fraud guidance says:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Although many recruitment websites or search engines may repost job descriptions and claim to offer recruitment services, we only officially advertise on the following sites: Air Canada Careers Portal, LinkedIn, Indeed. The Air Canada Talent Acquisition team will never ask for payment or sensitive personal information, or send unsolicited job offers via email or social media."</p>

<p>Its careers FAQ is just as direct: "any such requests are scams. Air Canada will not ask prospective candidates to submit money for document processing. Furthermore, Air Canada will never request your identity or personal documents such as passports, drivers license, etc. be submitted via a social media platform."</p>

<p><strong>Air Canada airport jobs are a favourite bait for visa fraud.</strong> Put the two facts in this guide together and you have a complete test: Air Canada does not sponsor, and it advertises in only three places. Anyone offering you an Air Canada airport job with a visa attached, for a fee, on WhatsApp, is lying. If you are defrauded, Air Canada points to the Canadian Anti-Fraud Centre on 1-888-495-8501.</p>

<h2>What You Actually Get If You Are Hired</h2>

<p>Air Canada publishes its benefits, and for a unionised airport job they are substantial:</p>

<ul>
    <li>An Employee Share Ownership Plan with "an annual 33.33% company match" for eligible employees.</li>
    <li>Group benefits and retirement and savings plans.</li>
    <li>Travel privileges with Air Canada and partner airlines.</li>
    <li>24/7 telemedicine and an Employee Assistance Program.</li>
</ul>

<p>Eligibility varies by position and employment status, so confirm it for the role you are offered.</p>

<h2>How to Apply, Step by Step</h2>

<ol>
    <li>Confirm you are already entitled to work in Canada. If not, stop here and deal with immigration first.</li>
    <li>Go to careers.aircanada.com/ca/en and open the Airport Operations category.</li>
    <li>Read the whole advert, especially the language requirement and the clearance clause.</li>
    <li>Apply directly on the portal. You may apply for more than one role; Air Canada's FAQ encourages it.</li>
    <li>Join the Talent Network so recruiters can find your resume when a station opens up.</li>
    <li>Expect the security clearance to be started by Air Canada after an offer, not before.</li>
</ol>

<h2>Frequently Asked Questions</h2>

<h3>Can I get an Air Canada airport job from Pakistan?</h3>
<p>Realistically no. Every airport advert makes work permits the candidate's sole responsibility, Air Canada does not sponsor, and airport roles need a Transport Canada clearance that only an employer can initiate and that requires five years of verifiable history.</p>

<h3>Does Air Canada sponsor work visas?</h3>
<p>No. The words sponsorship and LMIA do not appear in its airport adverts. Its FAQ states you must be legally entitled to work in the country where the position is located.</p>

<h3>What does an Air Canada ramp agent earn?</h3>
<p>Air Canada publishes it on the advert: $23.36 per hour to start, rising to $38.15 after eight years of continuous service under the applicable collective agreement. The role is unionised with the IAMAW.</p>

<h3>What is a RAIC and can I get one in advance?</h3>
<p>A Restricted Area Identity Card, the pass for airside areas. You cannot get one in advance. Transport Canada states the clearance application must be initiated by the employer and only by someone whose job requires restricted-area access.</p>

<h3>Do I need to speak French for Air Canada airport jobs?</h3>
<p>It depends on the station. Quebec City requires fluent English and French. Toronto asks for English plus French, German or Greek. Ottawa does not list French at all, asking instead for languages including Hindi, Punjabi and Arabic.</p>

<h3>What is the official Air Canada careers website?</h3>
<p>careers.aircanada.com/ca/en, with airport roles at /c/airport-operations-jobs. The old aircanada.taleo.net address is retired and redirects.</p>

<h3>Does Air Canada hire people with no airport experience?</h3>
<p>Ramp agent and customer experience roles are entry points, but requirements sit on each advert. Read the qualifications section rather than assuming.</p>

<h3>How do I know an Air Canada job offer is real?</h3>
<p>Air Canada advertises only on its own careers portal, LinkedIn and Indeed. It never asks for payment, and never asks for passports or licences through social media. Anything else is a scam.</p>

<h2>People Also Search For</h2>

<h3>Air Canada careers login</h3>
<p>careers.aircanada.com/ca/en. The applicant system moved off Taleo, so old bookmarks and links in older articles no longer work.</p>

<h3>Ramp agent salary Canada</h3>
<p>Air Canada publishes $23.36 an hour to start and up to $38.15 after eight years of continuous service, under its IAMAW collective agreement.</p>

<h3>Transport Canada security clearance application</h3>
<p>Must be initiated by the employer, requires five years of verifiable work, study and residency, and is checked with IRCC, the RCMP and CSIS.</p>

<h3>Restricted Area Identity Card requirements</h3>
<p>The clearance is a prerequisite for the card, and only people who need restricted-area access for their job may apply.</p>

<h3>Air Canada flight attendant hiring</h3>
<p>A separate category from airport operations, based in Vancouver and Toronto, with a criminal background check for the Transport Canada clearance.</p>

<h3>Air Canada job scam</h3>
<p>Air Canada advertises only on its careers portal, LinkedIn and Indeed, never requests payment, and directs victims to the Canadian Anti-Fraud Centre on 1-888-495-8501.</p>

<h3>Canada work permit for airport jobs</h3>
<p>Most foreign nationals need a work permit, and an employer-specific permit needs a job offer. Air Canada does not provide one for airport roles.</p>

<h3>Air Canada employee travel benefits</h3>
<p>Air Canada lists travel privileges with itself and partner airlines, plus a share ownership plan with a 33.33 per cent company match for eligible employees.</p>

<h2>More Job Guides</h2>

<p>If you are working on the Canadian route rather than this one employer, start here:</p>

<ul>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the immigration route that has to come first.</li>
    <li><a href="/blog/visa-sponsorship-jobs-in-canada">Visa Sponsorship Jobs in Canada</a> &mdash; employers that actually do sponsor, unlike this one.</li>
    <li><a href="/blog/customer-service-jobs-in-canada">Customer Service Jobs in Canada</a> &mdash; the same skills, a wider set of employers.</li>
    <li><a href="/blog/how-to-get-a-fleet-driver-job-in-canada">How to Get a Fleet Driver Job in Canada</a> &mdash; shift work with a clearer entry path.</li>
    <li><a href="/blog/how-to-apply-for-etihad-airport-jobs-in-uae">How to Apply for Etihad Airport Jobs in UAE</a> &mdash; airport ground work in a market that does recruit from abroad.</li>
    <li><a href="/blog/how-to-apply-for-telkom-indonesia-it-jobs">How to Apply for Telkom Indonesia IT Jobs</a> &mdash; and the national ID requirement that closes the door first.</li>
    <li><a href="/blog/how-to-apply-for-airbus-aerospace-jobs-in-france">How to Apply for Airbus Aerospace Jobs in France</a> &mdash; 622 French vacancies, and how many need a security clearance.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Air Canada's own careers portal and job adverts, its careers FAQ and anti-fraud guidance, its newsroom, and Transport Canada's published Transportation Security Clearance requirements, checked on 22 September 2026. Vacancies, pay rates and immigration rules change. Always check the live posting and the official government source before acting, and never pay anyone to secure a job.</p>
HTML;
    }
}
