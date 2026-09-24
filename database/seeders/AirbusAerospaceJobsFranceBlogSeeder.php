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
 * "How to Apply for Airbus Aerospace Jobs in France" — a guide about an employer
 * with 622 open French vacancies, two in five of which need a French security
 * clearance, and whose internship rules quietly exclude most of the audience
 * that searches for it.
 *
 * Corrections to the draft (checked against airbus.com, Airbus's Workday job
 * API, service-public.gouv.fr and Legifrance, 22 September 2026):
 *
 * 1. The draft has no application URL at all, only a "[Airbus Careers]"
 *    placeholder. The real board is the Workday tenant; the old
 *    airbus.com/careers/search-and-apply address is a redirect loop.
 *
 * 2. The draft says Pakistani engineers "can apply". 243 of 622 French
 *    vacancies require a security clearance, 32 demand French nationality
 *    under IGI 1300, none offer an EU alternative, and none mention a visa.
 *
 * 3. The draft claims Airbus internships only require university enrolment.
 *    Airbus's own FAQ also requires the candidate to be "a French/EU citizen
 *    or have authorization to live and work in France with an appropriate
 *    Visa". That sentence is the one that decides it, and the draft omits it.
 *
 * 4. The apprenticeship wording is paraphrased in a way that drops its
 *    operative clause, and the draft omits the age-29 limit entirely.
 *
 * 5. "More than 50,000 people in France" is Airbus's careers page; its
 *    corporate page publishes more than 56,000 across 46 locations.
 *
 * 6. The draft's visa section is generic. The talent card route it implies
 *    requires a master's obtained in France, so the Blue Card is the honest
 *    recommendation for a Pakistani engineer.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class AirbusAerospaceJobsFranceBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://ag.wd3.myworkdayjobs.com/en-US/Airbus?locationCountry=54c5b6971ffb4bf0b116fe7651ec789a';

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
        $title = 'How to Apply for Airbus Aerospace Jobs in France';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'Airbus has 622 vacancies open in France. Two in five need a French security clearance and most adverts are written in French. Here is the real board and which visa route actually works.',
                'content' => $content,
                'featured_image' => 'blogs/how-to-apply-for-airbus-aerospace-jobs-in-france.jpg',
                'tags' => 'airbus jobs, airbus careers france, aerospace jobs france, airbus internship, airbus apprenticeship, france eu blue card, carte de sejour talent, france work visa',
                'meta_title' => 'Airbus Aerospace Jobs in France: How to Apply',
                'meta_description' => 'Airbus aerospace jobs in France: the real job board, how many roles need French nationality, the internship rule nobody quotes, and which visa route works.',
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
            ['name' => 'Airbus, France'],
            ['type' => 'Company', 'display_reference' => 'airbus-france']
        );

        $location = Location::firstOrCreate(
            ['name' => 'France'],
            ['area' => 'Nationwide', 'country' => 'France']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'construction-trades'],
            ['name' => 'Construction & Trades']
        );

        Job::updateOrCreate(
            [
                'position' => 'Aerospace Engineering, Airbus, French Sites',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Full-time under the French metallurgy collective agreement',
                'language' => 'French and English',
                // Airbus grades French roles on the metallurgy collective
                // agreement (Classe Emploi) but publishes no salary figure on
                // any French advert, so this listing carries none.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'Engineering, production and technical roles at Airbus sites across France. Many require a French security clearance.',
                'seo_keywords' => 'airbus jobs, airbus careers france, aerospace jobs france, engineering jobs france, airbus toulouse jobs',
                'status' => 'active',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>This is an aggregated listing of the roles Airbus advertises across its French sites, not a single vacancy and not a job advertised by JobGader. Applications are made on Airbus's own Workday careers portal.</p>

<h3>What is open</h3>
<p>Airbus listed 622 vacancies in France when this listing was last checked, across Commercial Aircraft, Helicopters, Defence and Space, and Airbus Atlantic. Main sites include Toulouse, Marignane, Elancourt, Saint-Nazaire, Rochefort and Paris-Le Bourget.</p>

<h3>Security clearance and nationality</h3>
<p>Roughly two in five French vacancies require a security clearance, and a number state that French nationality is mandatory under IGI 1300 of 9 August 2021. No French advert offers EU nationality as an alternative. Clearance requirements are concentrated in cybersecurity, secure communications, satellite and defence electronics roles.</p>

<h3>Language</h3>
<p>Most Airbus French adverts are written in French, and many state a French language requirement outright. Read the advert in its original language before applying.</p>

<h3>Pay</h3>
<p>Airbus publishes no salary on its French adverts. Roles are graded under the French metallurgy collective agreement, shown on adverts as Classe Emploi. Any Airbus salary figure you have read came from an estimate site.</p>

<h3>Visas</h3>
<p>Airbus states that visa and relocation support depend on the role, country and applicable legislation, and may be discussed during recruitment where relevant. No French advert mentions a visa or work permit. Airbus also states it never requests payment for interviews, background checks, visa processing, training or IT equipment.</p>

<p>Pay, eligibility, clearance and immigration rules are set by Airbus and the French authorities &mdash; not by JobGader. Confirm the requirements on the live posting before acting.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Airbus has <strong>622 vacancies open in France right now</strong>. We counted them on Airbus's own job board, not an estimate. That is a genuinely large number, and it is why this search is worth doing properly.</p>

<p>But before you start, two facts that most Airbus guides leave out, and that will decide whether your time is well spent.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-airbus-aerospace-jobs-in-france-assembly.jpg" alt="Aircraft final assembly work inside an aerospace production hall" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Airbus lists 622 open vacancies in France, across Commercial Aircraft, Helicopters, Defence and Space, and Airbus Atlantic.</figcaption>
</figure>

<h2>Fact One: Two in Five Roles Need a French Clearance</h2>

<p>We read the advert text of all 622 French vacancies. The numbers:</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">What the adverts say</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Out of 622</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Require a security clearance (<em>habilitation de sécurité</em>)</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>243 (39%)</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Require French nationality outright</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>32</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Accept EU nationality as an alternative</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>0</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Mention a visa, work permit or sponsorship</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>0</strong></td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">Advert body written in French</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>452 (73%)</strong></td>
        </tr>
    </tbody>
</table>

<p>Here is the actual wording, from a live cybersecurity engineer advert at Elancourt:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Security Clearance: This position requires a security clearance or will require being eligible for clearance by the recognized authorities. <strong>The candidate must possess French nationality in accordance with IGI 1300 of August 9, 2021 (§1.2.2.1).</strong>"</p>

<p>Note the two halves. Even where nationality is not stated, "eligible for clearance by the recognized authorities" is itself a barrier: a French <em>habilitation</em> involves a DGSI background investigation, and in practice that is not granted to third-country nationals.</p>

<p><strong>This is not confined to the defence division.</strong> Clearance requirements appear on 87 Marseille and Marignane helicopter roles, 66 in Toulouse, 54 around Paris and Elancourt, on Airbus Atlantic aerostructures jobs, and on at least 16 <em>internships</em>.</p>

<p>The honest summary: most Airbus French adverts say nothing about nationality, so you can apply. But the requirement is real, legally grounded, and concentrated precisely in the cyber, satellite and secure-communications roles that attract the most applicants.</p>

<h2>Fact Two: Nearly Three Quarters of the Adverts Are in French</h2>

<p>452 of 622 advert bodies are written in French, and 182 state a French language requirement outright. Titles like "Ingénieur Electronique Puissance/Analogique (h/f)" and "Technicien(ne) câblage de cartes électroniques" are the norm, not the exception.</p>

<p>If you cannot read the requirements section, you cannot meet it. Treat French as a core requirement for this employer, not a bonus.</p>

<h2>Where to Actually Apply</h2>

<p>Airbus's vacancies live on a Workday tenant, not on airbus.com. Be careful here: the old <em>airbus.com/careers/search-and-apply</em> address is now a redirect loop that never resolves, and it still appears in search results.</p>

<p style="text-align:center;margin:28px 0;">
    <a href="https://ag.wd3.myworkdayjobs.com/en-US/Airbus?locationCountry=54c5b6971ffb4bf0b116fe7651ec789a" target="_blank" rel="noopener nofollow" style="display:inline-block;background:#b3151a;color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;">Open Airbus France Vacancies &rarr;</a>
</p>

<p>If that filtered link ever stops working, go to <em>ag.wd3.myworkdayjobs.com/Airbus</em> and set the country filter to France yourself.</p>

<h2>How Big Is Airbus in France, Really?</h2>

<p>Airbus publishes two different numbers, and it is worth knowing which is which. Its careers page says "over 50,000 talented people" and "5 major sites". Its corporate page is more recent and more precise:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Airbus has more than 56,000 employees in France… Airbus and its subsidiaries have 46 locations in France. Its Toulouse and Marignane sites are respectively the 1st and 3rd industrial sites in the country."</p>

<p>Within that: <strong>Toulouse has around 35,000 employees</strong> and is the headquarters, with final assembly lines at Blagnac covering the A320, A330, A350 and A380 families. <strong>Marignane has nearly 9,000</strong> and is the home of Airbus Helicopters. <strong>Elancourt</strong> covers intelligence, secure and satellite communications, C4ISR, maritime and cyber security, and on-board space and defence electronics.</p>

<h2>The Internship Rule Nobody Quotes</h2>

<p>This is the correction that matters most if you are a student reading this from outside Europe.</p>

<p>Most guides tell you Airbus internships in France just require you to be enrolled at a university. Airbus's own FAQ says more than that:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"You must be looking for an internship that lasts between 1 and 6 months, and be comfortable working in an international environment. In addition, <strong>you must be a French/EU citizen or have authorization to live and work in France with an appropriate Visa.</strong> Finally, to qualify for an internship in France, you must be enrolled at a university that is willing to sign the French Airbus training agreement (i.e. Convention de Stage)."</p>

<p><strong>Airbus internships in France are not open to someone applying from Pakistan with no French status.</strong> The middle sentence settles it, and it is the sentence other guides drop.</p>

<p>Application windows, since they are narrow: <strong>September to December</strong> for 4 to 6-month placements, and <strong>January</strong> for summer placements of 4 to 8 weeks.</p>

<h2>Apprenticeships Are Different, and Better</h2>

<p>Here is the genuinely encouraging contrast. Airbus's apprenticeship FAQ says something the internship FAQ does not:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Apprenticeships at Airbus are open to students of all nationalities but you must be a student in a French school to be eligible for the apprenticeship contract."</p>

<p><strong>Nationality-blind, but school-bound.</strong> If you are already studying in France on a student residence permit, this route is open to you regardless of passport. If you are not, it is a reason to look at studying in France first rather than applying cold.</p>

<p>Two more things the draft guides miss:</p>

<ul>
    <li>Apprenticeships run <strong>1 to 3 years</strong>, from <strong>CAP to BAC +5</strong> level, with applications open <strong>February to June</strong>.</li>
    <li>There is an <strong>age limit of 29</strong> &mdash; "a maximum of 29 years old (30 years old minus 1 day)" &mdash; extended to 35 in specific cases such as progressing to a higher diploma.</li>
</ul>

<p>The selection process for both is the same shape: a video interview reviewed by a recruiter, then a one-to-one with your future manager.</p>

<figure style="margin:28px 0;">
    <img src="/public/storage/blogs/how-to-apply-for-airbus-aerospace-jobs-in-france-toulouse.jpg" alt="Engineers working with technical equipment at an aerospace facility in France" style="width:100%;height:auto;border-radius:10px;">
    <figcaption style="font-size:13px;color:#6b7280;margin-top:8px;">Toulouse employs around 35,000 people and is Airbus's headquarters; Marignane has nearly 9,000.</figcaption>
</figure>

<h2>Which Visa Route Actually Works?</h2>

<p>France renamed its main skilled route. What everyone still calls the "passeport talent" is now the <strong>carte de séjour pluriannuelle « talent »</strong>, after the 2024 immigration law. Within it, two categories matter to an engineer, and the difference between them is decisive.</p>

<table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:15px;">
    <thead>
        <tr style="background:#b3151a;color:#fff;">
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Route</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Qualification</th>
            <th style="padding:10px;text-align:left;border:1px solid #e5e7eb;">Minimum gross salary</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">talent &mdash; salarié qualifié</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">A master-level diploma <strong>obtained in France</strong></td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>&euro;39,582</strong> a year</td>
        </tr>
        <tr>
            <td style="padding:10px;border:1px solid #e5e7eb;">talent &mdash; carte bleue européenne</td>
            <td style="padding:10px;border:1px solid #e5e7eb;">3 years of higher education <strong>or</strong> 5 years of comparable experience</td>
            <td style="padding:10px;border:1px solid #e5e7eb;"><strong>&euro;59,373</strong> a year</td>
        </tr>
    </tbody>
</table>

<p><strong>Read the qualification column again.</strong> The salarié qualifié route requires a diploma "obtenu en France". A Pakistani or Indian master's does not satisfy it. So despite the higher salary bar, <strong>the EU Blue Card is the route to ask Airbus about</strong> &mdash; it accepts a foreign three-year degree, or five years of experience instead.</p>

<p>One genuinely helpful detail: a talent card holder does not need a separate work permit. French guidance states that the talent card allows you to do the work that earned it, and the employer is exempt from applying for a work authorisation.</p>

<p>And what Airbus itself says, which is honest but non-committal: "Visa and relocation support depend on the role, country and applicable legislation. This may be discussed during the recruitment process where relevant." That is not a promise. Ask early.</p>

<h2>Nobody Can Charge You for This</h2>

<p>Airbus's recruitment fraud notice is unusually specific about the exact scam aimed at overseas applicants:</p>

<p style="border-left:4px solid #b3151a;padding:12px 18px;background:#faf7f7;margin:22px 0;">"Our talent acquisition team will only contact you using official corporate email addresses ending strictly in @airbus.com… <strong>Airbus never requests payment for interviews, background checks, visa processing, training, or IT equipment.</strong>"</p>

<p>French law goes further than a company policy. <strong>Article L5321-3 of the Code du travail forbids requiring any payment, direct or indirect, from a jobseeker in return for placement services</strong>, and Article L5324-1 makes doing so punishable by six months' imprisonment and a &euro;3,750 fine. Both are in force.</p>

<p>The French Embassy in Pakistan also carries a live warning that individuals are impersonating the consulate, the embassy and the Ambassador to offer services. If someone offers you an Airbus job with a visa attached, for a fee, you now know three separate reasons it is false.</p>

<h2>Is Airbus Hiring or Cutting?</h2>

<p>Both, and the distinction matters for where you apply. In July 2025 Airbus Defence and Space confirmed it would reduce <strong>up to 2,043 positions</strong>, predominantly management overhead, while stating there would be <strong>no compulsory redundancies</strong>.</p>

<p>That is the same division where French nationality is demanded most often. Meanwhile Airbus Atlantic alone had around 110 French openings when we checked, so the restructuring has not closed French hiring.</p>

<h2>Frequently Asked Questions</h2>

<h3>Where do I apply for Airbus jobs in France?</h3>
<p>On Airbus's Workday portal at ag.wd3.myworkdayjobs.com/Airbus, filtered to France. The old airbus.com search-and-apply address is a redirect loop and should not be used.</p>

<h3>Does Airbus require French nationality?</h3>
<p>Not for most roles, but 32 of 622 current French vacancies demand it outright under IGI 1300, and 243 require a security clearance. No French advert offers EU nationality as an alternative.</p>

<h3>Can Pakistani engineers apply to Airbus in France?</h3>
<p>You can apply to roles without a clearance requirement, and Airbus publishes no blanket nationality bar. The practical obstacles are French language, the clearance concentration in the most sought-after roles, and the fact that no advert mentions visa sponsorship.</p>

<h3>Are Airbus internships in France open to international students?</h3>
<p>Only if you already have the right to be there. Airbus requires you to be "a French/EU citizen or have authorization to live and work in France with an appropriate Visa", plus a university willing to sign the Convention de Stage.</p>

<h3>Are Airbus apprenticeships open to foreigners?</h3>
<p>Yes by nationality, no by location. Airbus says apprenticeships are open to students of all nationalities but you must be a student in a French school to be eligible for the contract. There is also an age limit of 29.</p>

<h3>What is the EU Blue Card salary threshold in France?</h3>
<p>&euro;59,373 gross a year in 2026, which is 1.5 times the official reference salary. The cheaper talent card route at &euro;39,582 requires a master-level diploma obtained in France.</p>

<h3>How many people does Airbus employ in France?</h3>
<p>More than 56,000 across 46 locations according to its corporate page, with around 35,000 in Toulouse and nearly 9,000 at Marignane. Its careers page still says "over 50,000".</p>

<h3>Does Airbus sponsor visas?</h3>
<p>It does not commit either way. Its FAQ says visa and relocation support depend on the role, country and legislation, and may be discussed where relevant. None of its 622 French adverts mentions a visa.</p>

<h2>People Also Search For</h2>

<h3>Airbus careers Workday</h3>
<p>The real vacancy portal, separate from airbus.com. Filter by country rather than trying to build a URL by hand.</p>

<h3>Habilitation de sécurité Airbus</h3>
<p>The French security clearance required on about two in five Airbus French vacancies, granted by the French authorities after a background investigation.</p>

<h3>IGI 1300</h3>
<p>The interministerial instruction of 9 August 2021 that Airbus cites when an advert states French nationality is mandatory.</p>

<h3>Airbus Toulouse jobs</h3>
<p>Toulouse is the headquarters with around 35,000 employees, and the Blagnac final assembly lines cover the A320, A330, A350 and A380 families.</p>

<h3>Carte de séjour talent France 2026</h3>
<p>The renamed passeport talent. The salarié qualifié category needs &euro;39,582 and a France-obtained master; the Blue Card category needs &euro;59,373.</p>

<h3>Airbus apprenticeship age limit</h3>
<p>A maximum of 29 years old, extended to 35 in specific cases such as progressing to a higher diploma level.</p>

<h3>Airbus Atlantic CQPM</h3>
<p>A French metallurgy sector certificate, one to three months, leading to a temp-agency contract. It is a retraining route for people already in France, not an entry route from abroad.</p>

<h3>Airbus recruitment fraud</h3>
<p>Airbus contacts candidates only from addresses ending strictly in @airbus.com and never requests payment for interviews, background checks, visa processing, training or IT equipment.</p>

<h2>More Job Guides</h2>

<p>Comparing European aerospace and engineering employers? These cover the same ground:</p>

<ul>
    <li><a href="/blog/how-to-apply-for-leonardo-aerospace-jobs-in-italy">How to Apply for Leonardo Aerospace Jobs in Italy</a> &mdash; the same industry, and an employer that publishes its pay bands.</li>
    <li><a href="/blog/how-to-apply-for-siemens-engineering-jobs-in-germany">How to Apply for Siemens Engineering Jobs in Germany</a> &mdash; a much lower Blue Card threshold next door.</li>
    <li><a href="/blog/how-to-apply-for-air-canada-airport-jobs">How to Apply for Air Canada Airport Jobs</a> &mdash; aviation work with a very different set of gates.</li>
    <li><a href="/blog/how-to-apply-for-ferrari-factory-jobs-in-italy">How to Apply for Ferrari Factory Jobs in Italy</a> &mdash; European manufacturing with the same language barrier.</li>
    <li><a href="/blog/how-to-apply-for-bmw-factory-jobs-in-germany">How to Apply for BMW Factory Jobs in Germany</a> &mdash; production work with a clearer entry path.</li>
    <li><a href="/blog/how-to-apply-for-asml-semiconductor-jobs-in-netherlands">How to Apply for ASML Semiconductor Jobs in Netherlands</a> &mdash; published salary ranges, and which of them clear the Dutch visa threshold.</li>
    <li><a href="/blog/how-to-apply-for-bp-engineering-jobs-in-the-uk">How to Apply for BP Engineering Jobs in the UK</a> &mdash; which engineering codes clear the UK salary threshold, and which bp adverts are really agencies.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes, using Airbus's own careers pages, FAQ and Workday job API, its press releases, service-public.gouv.fr, the French Code du travail on Legifrance, and the French Embassy in Pakistan, checked on 22 September 2026. Vacancy counts, salary thresholds and immigration rules change. Always check the live posting and the official government source before acting, and never pay anyone to secure a job or a visa appointment.</p>
HTML;
    }
}
