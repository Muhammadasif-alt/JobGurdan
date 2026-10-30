<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Sweeper Jobs in Karachi, Sindh: Government and Private Options"
 *
 * Checked on 30 October 2026. What changed against the brief:
 *
 * 1. The brief repeated many keyword variants, which reads as keyword stuffing; the guide is rewritten in plain prose
 *    with the tags carrying the search terms. The three Indeed buttons and the Indeed advert's Rs 30,000 to 35,000 pay
 *    are dropped.
 * 2. Sindh Labour and Human Resources Department fetched: unskilled Rs 43,000, semi-skilled Rs 44,484, skilled
 *    Rs 53,350, highly skilled Rs 55,626, with effect from 1 July 2026 (posted 22 September 2026). The page gives no
 *    daily or hourly rate and no coverage text, so none is claimed.
 * 3. Sindh Solid Waste Management Board fetched: careers page lists no vacancies and says the Board works "by
 *    outsourcing its municipal functions".
 * 4. Aga Khan University fetched: the Domestic Aide post at Aga Khan Hospital, Garden (closed 25 October 2025) listed
 *    dusting, bins, bathrooms, mopping and reporting wear and tear, preferred Middle school, basic hygiene, simple Urdu
 *    instructions and shift work. AKU's vacancies page says Pakistan ancillary and non-management posts are applied for
 *    by email.
 * 5. FAQs expanded to eight; eight People Also Search For entries added.
 *
 * No Karachi poster was supplied; the featured image and steps graphic were generated in the house style. The portal
 * listing is in GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class SweeperJobsKarachiGovernmentPrivateOptionsBlogSeeder extends Seeder
{
    public const SLUG = 'sweeper-jobs-karachi-government-private-options';

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
                'title' => 'Sweeper Jobs in Karachi, Sindh: Government and Private Options',
                'excerpt' => 'Where to find sweeper and cleaner jobs in Karachi: government departments and the SSWMB, private offices, hospitals such as Aga Khan, what employers ask for, Sindh\'s Rs 43,000 minimum wage for unskilled workers from July 2026, and how to apply safely.',
                'content' => $content,
                'featured_image' => 'blogs/sweeper-jobs-karachi-government-private-options.jpg',
                'tags' => 'sweeper jobs in karachi, government sweeper jobs in karachi, private sweeper jobs in karachi, hospital sweeper jobs in karachi, office cleaner job in karachi, sweeper salary in karachi, sindh minimum wage 2026, safai ki jobs karachi',
                'meta_title' => 'Sweeper Jobs in Karachi: Government and Private Options',
                'meta_description' => 'Sweeper jobs in Karachi: government and SSWMB routes, private offices and hospitals, the Rs 43,000 Sindh minimum wage from July 2026, and how to apply.',
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
<p><strong>Sweeper and cleaner jobs in Karachi come from government departments and hospitals, private offices, malls, factories, schools and cleaning contractors.</strong> Search for cleaner, sanitary worker, janitorial staff and housekeeping assistant as well as "sweeper". Requirements vary: some posts prefer Middle school, others only ask that you follow instructions and work shifts. Sindh's minimum wage for unskilled workers is Rs 43,000 a month from 1 July 2026. That is a legal floor, not an average sweeper salary. This guide uses the Sindh Labour and Human Resources Department, the Sindh Solid Waste Management Board and Aga Khan University, checked on 30 October 2026.</p>

<h2>What Does a Sweeper Do?</h2>

<p>Sweepers and cleaners sweep and mop floors and corridors, clean washrooms, empty bins and keep shared areas presentable. Office cleaners also dust furniture and refill soap and tissues; building cleaners look after stairs and entrances. Ask whether the job includes outdoor work, lifting, more than one building or extra duties, because some "security and cleaning" posts combine both jobs. Reliability, care with equipment and respect for the people around you matter more than anything else.</p>

<h2>Government Sweeper Jobs</h2>

<p>Government cleaning posts are often advertised as sanitary worker, naib qasid or other support staff, by departments, public hospitals, colleges and municipal bodies, each with its own deadline and documents. For municipal work, check who is actually hiring. The Sindh Solid Waste Management Board says it runs Karachi's waste collection "by outsourcing its municipal functions", so many street and waste jobs are with contractors, not the government. Its careers page listed no sweeper vacancy when we checked. Check whether a post is permanent, contract or through a contractor, and apply exactly as the advert says; some still want a physical application. Official page: <a href="https://sswmb.gos.pk/careers/" rel="noopener">SSWMB: Careers</a>.</p>

<h2>Private Offices, Shops and Buildings</h2>

<p>Private jobs include office cleaner, janitor and housekeeping staff. An office job usually means one fixed workplace; contractor jobs can move you between sites. For factory and mall cleaning, ask about machinery areas, public toilets, protective equipment and training. Check the posting date, address, shift and employer before you travel for an interview.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/sweeper-jobs-karachi-government-private-options-steps.jpg"
         alt="Steps for a Karachi sweeper job: search sweeper, cleaner and sanitary worker, check the real employer, read the documents list, compare pay with Rs 43,000, never pay for selection, keep your offer in writing"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Hospitals and Schools</h2>

<p>Hospital cleaning needs extra care with hygiene and waste. Ask who trains you, who supplies protective equipment and how clinical waste is separated, and never pick up needles or unknown waste without the procedure and supervision. As an example of what hospitals ask for, Aga Khan Hospital, Garden advertised a <strong>Domestic Aide</strong> post in its housekeeping department that closed on 25 October 2025. It covered dusting, emptying bins, cleaning bathrooms, mopping floors and reporting wear and tear, and asked for Middle school preferably, basic knowledge of hygiene, the ability to follow simple instructions in Urdu, physical fitness and willingness to work shifts. That post is closed; it shows the requirements, not a current opening. Source: <a href="https://www.aku.edu/vacancies/pages/job-detail.aspx?JobID=10198" rel="noopener">Aga Khan University: Domestic Aide, Aga Khan Hospital, Garden</a>.</p>

<p>For current posts, use AKU's vacancies page: in Pakistan, ancillary and non-management staff positions are applied for by email, as each advert explains. School cleaning jobs cover classrooms, playgrounds and toilets; ask about holiday hours and any checks. Official page: <a href="https://www.aku.edu/vacancies/Pages/home.aspx" rel="noopener">Aga Khan University: Vacancies</a>.</p>

<h2>Qualifications and Documents</h2>

<p>There is no single requirement for every sweeper job. Some employers prefer primary or Middle school, others Matric or experience, and many only ask for reliability. "Fresher" adverts do not always include training, so ask. Keep ready:</p>

<ul>
<li>A short CV with your area, phone number and availability.</li>
<li>CNIC copies, given only through a verified process.</li>
<li>Education or experience papers the advert asks for.</li>
<li>Domicile, PRC and photographs for government posts that specify them.</li>
</ul>

<h2>Sweeper Salary in Karachi</h2>

<p>The Sindh Labour and Human Resources Department lists these minimum wages with effect from <strong>1 July 2026</strong>:</p>

<ul>
<li><strong>Unskilled: Rs 43,000</strong> a month</li>
<li>Semi-skilled: Rs 44,484</li>
<li>Skilled: Rs 53,350</li>
<li>Highly skilled: Rs 55,626</li>
</ul>

<p>Most sweeper and cleaner jobs fall in the unskilled category. Check with the Labour Department which category and rules apply to your job, and look out for any later notification. Government pay depends on the post's scale and allowances instead. No reliable average sweeper salary exists, so compare written offers on basic pay, hours, weekly rest, overtime, deductions and travel cost. For daily-wage work, ask for the daily rate and how many paid days to expect. Source: <a href="https://lhr.sindh.gov.pk/news/minimum-wage-unskilled-rs-43000-skilled-rs-53350-semi-skilled-rs-44484-highly-skilled-rs-55626-wef-17082026" rel="noopener">Sindh Labour and HR Department: Minimum wage from 1 July 2026</a>.</p>

<h2>Part-Time, Women and Household Work</h2>

<p>Part-time jobs suit people with other commitments, but check the hours and the travel time. Women applying for cleaning jobs should check each advert's eligibility and ask about safe transport, washrooms and how to report problems. Household cleaning is a separate kind of job: agree the rooms, tasks, days, pay and boundaries before you start.</p>

<h2>How to Apply</h2>

<ol>
<li>Search several job titles and nearby areas, such as Gulshan, Korangi, Clifton and Saddar.</li>
<li>Confirm the post is open and find the real employer or contractor.</li>
<li>Read the qualifications, location, duties and closing date.</li>
<li>Apply through the channel the advert names.</li>
<li>Keep your receipt and prepare for a short interview or practical test.</li>
</ol>

<p>"Urgent" adverts do not mean you start tomorrow. OLX adverts can be leads, but check the advertiser yourself. Complaint helplines are not recruitment numbers. Never pay anyone who promises guaranteed selection.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>What is the minimum salary for a sweeper in Karachi?</h3>
<p>Rs 43,000 a month for unskilled workers in Sindh from 1 July 2026.</p>

<h3>Can I apply without experience?</h3>
<p>Yes, where the advert accepts beginners; ask about training.</p>

<h3>What education do I need?</h3>
<p>It varies; Aga Khan's Domestic Aide post preferred Middle school.</p>

<h3>Is a municipal sweeper job a government job?</h3>
<p>Not always. SSWMB outsources its municipal functions, so many jobs are with contractors.</p>

<h3>How do I apply for hospital cleaning jobs at Aga Khan?</h3>
<p>Through AKU's vacancies page; Pakistan ancillary posts are applied for by email.</p>

<h3>Are government applications always online?</h3>
<p>No. Follow each advert; some need a physical application.</p>

<h3>Is there one contact number for sweeper jobs?</h3>
<p>No. Use the number in the employer's verified advert.</p>

<h3>Is accommodation free?</h3>
<p>Not unless the offer says so in writing, with any deductions.</p>

<h2>People Also Search For</h2>

<h3>Sweeper jobs in Karachi</h3>
<p>Government, hospital, office and contractor posts.</p>

<h3>Government sweeper jobs in Karachi</h3>
<p>Often advertised as sanitary worker.</p>

<h3>Private sweeper jobs in Karachi</h3>
<p>Offices, malls, factories and contractors.</p>

<h3>Hospital sweeper jobs in Karachi</h3>
<p>Ask about waste handling and protective equipment.</p>

<h3>Office cleaner job in Karachi</h3>
<p>Usually one fixed workplace.</p>

<h3>Sweeper salary in Karachi</h3>
<p>At least Rs 43,000 a month from July 2026.</p>

<h3>Sindh minimum wage 2026</h3>
<p>Rs 43,000 unskilled, Rs 53,350 skilled.</p>

<h3>Safai ki jobs Karachi</h3>
<p>Search cleaner and housekeeping titles too.</p>

<h2>Bottom Line</h2>

<p>Search widely, check who really employs you, compare pay with the Sindh minimum and choose duties and shifts you can do safely. This guide cannot promise a job or a wage.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://lhr.sindh.gov.pk/news/minimum-wage-unskilled-rs-43000-skilled-rs-53350-semi-skilled-rs-44484-highly-skilled-rs-55626-wef-17082026" rel="noopener">Sindh Labour and HR Department: Minimum wage from 1 July 2026</a></li>
<li><a href="https://sswmb.gos.pk/careers/" rel="noopener">Sindh Solid Waste Management Board: Careers</a></li>
<li><a href="https://www.aku.edu/vacancies/pages/job-detail.aspx?JobID=10198" rel="noopener">Aga Khan University: Domestic Aide (closed)</a></li>
<li><a href="https://www.aku.edu/vacancies/Pages/home.aspx" rel="noopener">Aga Khan University: Vacancies</a>, all checked 30 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/petrol-pump-attendant-jobs-lahore-duties-salary">Petrol Pump Attendant Jobs in Lahore</a>, entry-level work in Punjab.</li>
    <li><a href="/blog/janitor-jobs-philadelphia-no-experience">Janitor Jobs in Philadelphia</a>, the same work in the USA.</li>
    <li><a href="/blog/cleaner-and-janitor-jobs-with-visa-sponsorship">Cleaner and Janitor Jobs With Visa Sponsorship</a>, cleaning work abroad.</li>
    <li><a href="/blog/how-to-find-job-abroad-from-pakistan-2026">How to Find a Job Abroad From Pakistan</a>, routes overseas.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal or career advice. Reviewed on 30 October 2026 against the pages named in Sources. It does not promise a job or a wage.</p>
HTML;
    }
}
