<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Hotel Jobs in Dubai, UAE for Foreigners: Roles and Requirements"
 *
 * Checked on 29 October 2026. What changed against the brief:
 *
 * 1. The brief repeated dozens of keyword variants ("hotel jobs in Dubai UAE for foreigners ..."), which reads as keyword
 *    stuffing; the guide is rewritten in plain prose with the tags carrying the search terms. The two Indeed buttons and
 *    the Indeed room attendant salary (AED 2,246 from 23 salaries) are dropped: JobGader does not republish aggregator
 *    salaries.
 * 2. The brief's two u.ae links return 404; the current work permits page (sector-of-employment path) was fetched:
 *    MoHRE issues 13 types of work permit to registered establishments, including recruiting a worker from outside the
 *    UAE and a part-time permit, it is illegal to work without a valid work permit, and permit fees run from AED 250 to
 *    AED 3,450 depending on the company's classification.
 * 3. Article 6 of Federal Decree-Law No. 33 of 2021 (no recruitment and employment costs charged to the worker) and the
 *    absence of a Labour Law minimum salary are the facts already checked for the Dubai receptionist guide.
 * 4. Jumeirah's careers page fetched: it mentions opportunities "at every stage" and links to its vacancies, but says
 *    nothing about internships, so the brief's internship claim is dropped. Radisson's careers site fetched: a UAE
 *    location filter with 26 Dubai listings on 29 October 2026, including a receptionist at Park Inn Dubai Motor City.
 *    Anantara's careers page refused both our network and the server's, so it is not linked.
 * 5. FAQs expanded to eight and a note for readers in Pakistan added.
 *
 * Posters are the owner's (blog-355, 28 Oct 10.58 and 10.59 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class HotelJobsDubaiForeignersRolesRequirementsBlogSeeder extends Seeder
{
    public const SLUG = 'hotel-jobs-dubai-foreigners-roles-requirements';

    public function run(): void
    {
        $content = $this->postBody();

        $category = BlogCatgories::firstOrCreate(
            ['slug' => 'career-advice'],
            [
                'name' => 'Career Advice',
                'description' => 'Practical guides on apprenticeships, entrance tests, applications and starting a trade career.',
            ]
        );

        $author = User::where('role', 'admin')->first();

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Hotel Jobs in Dubai, UAE for Foreigners: Roles and Requirements',
                'excerpt' => 'Which Dubai hotel jobs foreigners can apply for, what each department asks for, how the employer\'s MoHRE work permit works, why you must never pay recruitment fees, and how to compare salary, housing and meals before you accept.',
                'content' => $content,
                'featured_image' => 'blogs/hotel-jobs-dubai-foreigners-roles-requirements.jpg',
                'tags' => 'hotel jobs in dubai uae for foreigners, hotel jobs in dubai with visa sponsorship, hotel jobs in dubai uae for freshers, dubai hotel jobs apply online, hotel job requirements in dubai uae for foreigners, uae work permit hotel jobs, hotel jobs dubai with accommodation, hotel jobs dubai from pakistan',
                'meta_title' => 'Hotel Jobs in Dubai for Foreigners: Roles and Requirements',
                'meta_description' => 'Hotel jobs in Dubai for foreigners: roles by department, the employer\'s MoHRE work permit, no recruitment fees, and how to compare salary and housing.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p><strong>Foreigners can work in Dubai hotels in housekeeping, food and beverage, front office, maintenance, security and management, but only once the employer has obtained a work permit for them.</strong> In the UAE the employer, not the worker, applies for the permit, and it is illegal to work without one. The law bars employers from charging workers recruitment costs, and the Labour Law sets no minimum salary, so compare the written offer line by line. A job advert is not a visa. This guide uses the UAE Government portal and the hotel groups' own careers sites, checked on 29 October 2026.</p>

<h2>Which Hotel Roles Can Foreigners Apply For?</h2>

<p>Dubai hotels hire across every department, and the requirements differ sharply. Pick the role that fits your experience instead of sending one CV everywhere.</p>

<h3>Front office and guest services</h3>
<p>Receptionists and front desk agents handle check-ins, reservations, payments and guest requests. Customer service experience, accurate record-keeping and familiarity with a hotel booking system help. English is the working language in most hotels; another language helps where the property's guests speak it. Our <a href="/blog/receptionist-jobs-dubai-requirements-interview-tips">Dubai receptionist guide</a> covers this role in detail.</p>

<h3>Housekeeping and food and beverage</h3>
<p>Room attendants and public area attendants keep rooms and shared spaces clean and restocked. Waiters and waitresses work to the outlet's service standards. A kitchen helper or steward role is not the same as a chef post, which usually needs culinary training and kitchen experience you can prove.</p>

<h3>Technical, security and leadership</h3>
<p>Maintenance technicians need trade skills, and security staff need the licence and training their role requires; see our <a href="/blog/security-guard-jobs-dubai-no-experience">Dubai security guard guide</a>. Management posts expect evidence of leadership, operations or commercial results.</p>

<h2>Starting Without Hotel Experience</h2>

<p>Junior service and support roles are the usual way in, where the advert allows beginners. Show what transfers from retail, cleaning, volunteering or customer support. "Fresher" and "fresh graduate" are different searches: graduate and management trainee programmes often need a hospitality or related qualification. No-degree roles still have their own standards, so ask about supervision, training and assessment before you accept.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/hotel-jobs-dubai-foreigners-roles-requirements-team.jpg"
         alt="Hotel front office, housekeeping and restaurant staff at work in a Dubai hotel with the city skyline behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Work Permits and Visa Sponsorship</h2>

<p>The Ministry of Human Resources and Emiratisation (MoHRE) issues 13 types of work permit, and it issues them to registered establishments, not to individuals. The one a hotel uses to hire you from abroad is the work permit to recruit a worker from outside the UAE; a part-time work permit is a separate type. u.ae states that it is illegal for a person to work in the UAE without a valid work permit, so a visit or tourist visa never lets you start a hotel job, paid or unpaid. Permit fees run from AED 250 to AED 3,450 depending on the company's classification. When an advert says "visa sponsorship", ask which legal entity will employ you and when you may lawfully begin. Source: <a href="https://u.ae/en/information-and-services/jobs/sector-of-employment/employment-in-the-private-sector/work-permits" rel="noopener">UAE Government: Work permits</a>.</p>

<p>"Free visa" is advertising language, not a permit category. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging workers recruitment and employment costs, directly or indirectly. Never pay anyone for a "guaranteed" hotel job or a visa. Free zone employers are generally outside the UAE Labour Law, so ask which rules apply to the company that hires you. Source: <a href="https://u.ae/en/information-and-services/jobs" rel="noopener">UAE Government portal: Jobs</a>.</p>

<h2>Documents and Qualifications</h2>

<p>Hotels usually ask for a CV, a passport copy, photographs and evidence of qualifications or past employment. Some roles need certificates attested before the permit is issued; get the exact checklist from the employer rather than paying an agent for attestations you may not need. Send documents only through the hotel's own recruitment system or a verified email address. Before accepting, confirm the shift pattern, physical demands and any training the job requires.</p>

<h2>Salary, Accommodation and Benefits</h2>

<p>The UAE Labour Law sets no minimum salary, so your pay is whatever the written offer says. Compare offers line by line rather than by the headline:</p>

<ul>
<li><strong>Basic salary and allowances:</strong> separate basic pay from housing, transport and food allowances, and from service charge or tips, which vary.</li>
<li><strong>Accommodation:</strong> ask for the location, how many people share a room, and whether anything is deducted.</li>
<li><strong>Meals:</strong> ask whether staff meals cover rest days or only duty days.</li>
<li><strong>Medical insurance:</strong> request the coverage and when it starts.</li>
<li><strong>Flights and relocation:</strong> confirm who pays and on what conditions.</li>
</ul>

<p>A five-star hotel with accommodation does not always leave you with the most money each month. Ask when salary is paid each month.</p>

<h2>Where to Find Official Openings</h2>

<p>Use each group's own careers site and filter for Dubai. Radisson's site has a UAE location filter; on 29 October 2026 it showed 26 Dubai listings, including a receptionist post at Park Inn by Radisson Dubai Motor City. Its worldwide results are not all local, so filter carefully: <a href="https://radissoncareers.com/jobs" rel="noopener">Radisson careers</a>. Jumeirah links its current vacancies from <a href="https://www.jumeirah.com/en/careers" rel="noopener">jumeirah.com/careers</a>. Compare Dubai Marina, Downtown and airport-area properties by commute and staff transport as well as by pay.</p>

<h2>How to Apply From Abroad</h2>

<p>Write a focused CV that states your experience, languages, location and availability honestly. Apply through official portals, and check posting dates: "hiring immediately" still means selection and a work permit before you start. Use the phone numbers on the hotel's own website, not numbers from unverified WhatsApp adverts, and check any recruitment agency before you send documents. A genuine employer never asks you to pay for the job.</p>

<h2>A Note for Readers in Pakistan</h2>

<p>Pakistani workers going abroad through a recruitment agent should use one licensed by the Bureau of Emigration and Overseas Employment, and the UAE rule still applies: the employer bears recruitment costs. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> and <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you pay anyone.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can foreigners get hotel jobs in Dubai?</h3>
<p>Yes, once the hotel obtains a MoHRE work permit for them.</p>

<h3>Who applies for the work permit?</h3>
<p>The employer. MoHRE issues work permits to registered establishments, not to individuals.</p>

<h3>Can I work in a Dubai hotel on a visit visa?</h3>
<p>No. It is illegal to work in the UAE without a valid work permit.</p>

<h3>Do I have to pay for my visa or recruitment?</h3>
<p>No. Article 6 of Federal Decree-Law No. 33 of 2021 bars employers from charging these costs to workers.</p>

<h2>Bottom Line</h2>

<p>Choose a role you fit, apply through the hotel's own site, insist that the employer handles and pays for the work permit, and compare salary, housing and meals in writing before you travel.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://u.ae/en/information-and-services/jobs/sector-of-employment/employment-in-the-private-sector/work-permits" rel="noopener">UAE Government: Work permits</a></li>
<li><a href="https://u.ae/en/information-and-services/jobs" rel="noopener">UAE Government portal: Jobs</a></li>
<li><a href="https://radissoncareers.com/jobs" rel="noopener">Radisson careers</a></li>
<li><a href="https://www.jumeirah.com/en/careers" rel="noopener">Jumeirah careers</a>, all checked 29 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/receptionist-jobs-dubai-requirements-interview-tips">Receptionist Jobs in Dubai</a>, the front desk route in detail.</li>
    <li><a href="/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy">Hotel and Restaurant Jobs in the UAE and Italy</a>, hospitality work in two markets.</li>
    <li><a href="/blog/security-guard-jobs-dubai-no-experience">Security Guard Jobs in Dubai</a>, another entry route.</li>
    <li><a href="/blog/airport-ground-staff-jobs-in-dubai">Airport Ground Staff Jobs in Dubai</a>, guest-facing work at DXB.</li>
</ul>

HTML;
    }
}
