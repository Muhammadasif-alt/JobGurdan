<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Jobs Hiring Now in Houston, TX: Employers, Openings and How to Apply"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief opens with named live listings (Account Representative JR-14441, Research Assistant I, Service Driver, Cleaner Bus Maintenance). Those pages cannot be re-checked and expire within days, so the guide does not state them as current openings.
 * 2. The brief links ten portals with apply buttons, including a commercial job board. The job board section is dropped (it would break the no-commercial-board rule), and private employers (Houston Methodist, MD Anderson, Memorial Hermann, United, H-E-B) are named in prose only. Public bodies are linked: the City of Houston, Harris County, METRO and the state job service.
 * 3. The City of Houston job board is a NEOGOV page: you create an account, upload a resume and reuse the application; incomplete work history can lead to rejection; a Job Interest Card gives notice of future jobs. METRO's careers site states applicants must pass drug testing. The Harris County page and the TWC resources page would not load here, so only their purpose is stated.
 * 4. Eight FAQs, eight People Also Search For entries and a Pakistan note are added.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it. The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class JobsHiringNowHoustonTxBlogSeeder extends Seeder
{
    public const SLUG = 'jobs-hiring-now-houston-tx';

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
                'title' => 'Jobs Hiring Now in Houston, TX: Employers, Openings and How to Apply',
                'excerpt' => 'Where to find jobs hiring now in Houston: City of Houston, Harris County, METRO and WorkInTexas portals, plus private employers to search on their own sites.',
                'content' => $content,
                'featured_image' => 'blogs/jobs-hiring-now-houston-tx.jpg',
                'tags' => 'jobs hiring now in houston tx, houston jobs hiring, entry level jobs houston, full time jobs houston tx, city of houston jobs, metro houston careers, harris county jobs, workintexas',
                'meta_title' => 'Jobs Hiring Now in Houston, TX: Employers and How to Apply',
                'meta_description' => 'Find jobs hiring now in Houston, TX: the City, Harris County, METRO and WorkInTexas portals, plus healthcare, retail and airline employers to search directly.',
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
<p><strong>For jobs hiring now in Houston, TX, start with the official City of Houston, Harris County, METRO and WorkInTexas portals, then search healthcare, retail and airline employers on their own career sites.</strong> Houston Methodist, MD Anderson, Memorial Hermann, United Airlines and H-E-B all recruit in the city. No page can promise an opening on the day you read it, so use the links below to see what is posted now and read the full posting before you apply. An active listing does not guarantee immediate hiring.</p>

<h2>Check Current Listings Before You Spend Time</h2>

<p>Search the exact job title on the employer's portal and read the full posting for qualifications, location and schedule. A recent posting date does not give a closing date, and a listing can be removed at any time. Listings we saw during research are not repeated here as openings because they change daily.</p>

<h2>1. City of Houston Government Jobs</h2>

<p>The City of Houston recruits through its <a href="https://www.governmentjobs.com/careers/houston" rel="noopener">official job board</a>, which also explains its process on the <a href="https://www.houstontx.gov/hr/careers.html" rel="noopener">City HR careers page</a>. On the board you create an account, upload a resume and submit an application you can reuse for several openings. The page warns that applications must show all relevant education and experience or they may be rejected, and a Job Interest Card lets you ask for notice of future jobs when nothing fits today. Choose positions that match your background, answer supplemental questions carefully and keep your login secure so you can check status later. An acknowledgement is not an offer, and government hiring often has several stages.</p>

<h2>2. Harris County Employment</h2>

<p><a href="https://hrrm.harriscountytx.gov/Careers/Career-Opportunities" rel="noopener">Harris County's career opportunities page</a> is the county's own recruitment route and is separate from City of Houston hiring. Review the department, workplace and minimum qualifications before applying, because a county role may be outside central Houston. Save the announcement and your confirmation, answer any document request through the approved process and watch the deadline.</p>

<h2>3. METRO Transit Jobs</h2>

<p>METRO, the Metropolitan Transit Authority of Harris County, lists openings at <a href="https://jobs.ridemetro.org/viewalljobs/" rel="noopener">jobs.ridemetro.org</a> in categories including operators, mechanics, administration, trades and operations. Its site says applicants must pass drug testing before employment and that safety-sensitive roles follow US Department of Transportation and Federal Transit Administration testing rules. Read each posting's driving, experience, physical and schedule requirements separately, and note that "trainee" does not mean every applicant qualifies. Confirm where shifts begin and how you will travel home after late work. Use the public vacancies page, since internal promotions may be limited to current staff.</p>

<h2>4. WorkInTexas</h2>

<p>WorkInTexas is the Texas Workforce Commission's free job matching service, with job search and resume tools (see the <a href="https://www.workintexas.com/" rel="noopener">WorkInTexas site</a>). Search Houston with a sensible travel radius, follow each employer's own instructions and keep a tracker of titles, employers, dates, advertised pay and next steps.</p>

<h2>5. Healthcare Employers</h2>

<p>Houston Methodist, MD Anderson Cancer Center and Memorial Hermann all recruit across nursing, research, administration, facilities, food services, finance and IT. Search each system's own careers page by your real qualifications, and filter by facility, job type and shift, because Greater Houston has many separate communities. A hybrid role does not mean you can work from anywhere or choose every office day. For licensed roles, check every credential rule; for support roles, show customer service, organisation, reliability and following procedures. Confirm whether a post is full-time, part-time or PRN.</p>

<h2>6. Airline and Retail Employers</h2>

<p>United Airlines lists Houston as a location filter on its careers site, and H-E-B lets applicants search store jobs by location on its careers site. Airline roles range from operations to corporate and technical work, so check the workplace, travel and shifts, and distinguish the airline from contractors operating at the same airport. For store jobs, look at the exact store address and employment type, state your availability honestly (especially evenings and weekends) and compare the hourly rate with scheduled hours.</p>

<h2>Search Smart and Avoid Duplicates</h2>

<p>Where a vacancy appears on a third-party aggregator site, confirm it on the employer's own careers page before you share personal details. Check whether a listing is direct or agency-posted, and remember repeated adverts do not always mean separate openings. Use the employer's own application, keep the job number, and avoid sending the same application twice.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can I get hired without experience?</h3>
<p>Look for vacancies whose minimum requirements match your background and show reliability and transferable skills. Entry level can still need training or a licence.</p>

<h3>Does hiring now mean starting tomorrow?</h3>
<p>No. Employers may still need interviews, assessments and checks. Ask about the timeline.</p>

<h3>How should I compare pay?</h3>
<p>Compare base wage, expected hours, benefits and commuting costs. Do not budget around bonuses, tips or overtime unless the terms are clear.</p>

<h3>How do I avoid fake offers?</h3>
<p>Verify the employer through its official site. Be wary of requests for interview payments, gift cards or depositing cheques to buy equipment.</p>

<h3>What should I prepare before applying?</h3>
<p>A clear resume, accurate employment dates, relevant certificates, references who agreed to be listed, and a professional email and voicemail.</p>

<h3>Is the City of Houston the same as Harris County?</h3>
<p>No. They recruit separately, with their own portals and rules.</p>

<h3>Do METRO jobs need a drug test?</h3>
<p>METRO states applicants must pass drug testing before employment.</p>

<h3>Where can I find state help?</h3>
<p>WorkInTexas, run by the Texas Workforce Commission, offers job search and resume tools.</p>

<h2>People Also Search For</h2>

<h3>Jobs hiring now in Houston TX</h3>
<p>Check portals daily.</p>

<h3>Houston jobs hiring</h3>
<p>Postings change quickly.</p>

<h3>Entry level jobs Houston</h3>
<p>Match minimum qualifications.</p>

<h3>Full time jobs Houston TX</h3>
<p>Confirm hours before applying.</p>

<h3>City of Houston jobs</h3>
<p>Official NEOGOV job board.</p>

<h3>METRO Houston careers</h3>
<p>Drug testing required.</p>

<h3>Harris County jobs</h3>
<p>Separate from the City.</p>

<h3>WorkInTexas</h3>
<p>Free state job matching.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>A job listing on an employer's portal is not a visa offer, and most of these employers do not sponsor workers for entry-level roles. Never pay an agent for an interview or a "guaranteed" US job, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> first.</p>

<h2>Bottom Line</h2>

<p>Start with verified Houston portals, match your qualifications and apply through official channels. Check availability again before you submit, track replies and compare complete offers before accepting.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.governmentjobs.com/careers/houston" rel="noopener">City of Houston job board</a></li>
<li><a href="https://www.houstontx.gov/hr/careers.html" rel="noopener">City of Houston HR careers guidance</a></li>
<li><a href="https://hrrm.harriscountytx.gov/Careers/Career-Opportunities" rel="noopener">Harris County career opportunities</a></li>
<li><a href="https://jobs.ridemetro.org/viewalljobs/" rel="noopener">METRO jobs</a></li>
<li><a href="https://www.workintexas.com/" rel="noopener">WorkInTexas</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/jobs-in-chicago-il">Jobs in Chicago, IL</a> &mdash; another city guide</li>
    <li><a href="/blog/part-time-jobs-seattle-wa">Part Time Jobs in Seattle, WA</a> &mdash; another city guide</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a> &mdash; stay safe</li>
    <li><a href="/blog/plumber-apprenticeship-seattle-houston-detroit">Plumber Apprenticeship in Seattle, Houston, and Detroit</a> &mdash; trade route</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, financial or immigration advice. Reviewed on 17 October 2026. It links public-sector and state portals; private employers are named in the text so you can search their own career pages. It does not imply visa sponsorship, a confirmed opening or guaranteed hiring.</p>
HTML;
    }
}
