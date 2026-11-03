<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Bus Driver Jobs in Chicago, Illinois: License and Hiring Process"
 *
 * Checked on 3 November 2026. What changed against the brief:
 *
 * 1. The two Indeed buttons are dropped.
 * 2. CTA read: age 21, acceptable driving record, no commercial experience or CLP needed to apply, 30 days from the
 *    offer to get the CLP with knowledge, passenger and air brakes endorsements, then 30 to 45 days of paid training
 *    including CDL training, about two months from the test, a reply within 48 hours of interview. Careers page:
 *    $28.15 start, $42.46 after 45 months, medical, dental and vision.
 * 3. Pace read: free Class B permit courses, paid training, Thursday Hire-on-the-Spot events at Arlington Heights and
 *    Markham with a 90-minute Customer Service Assessment, and a driving record abstract to bring.
 * 4. The Illinois Secretary of State school bus pages returned Access Denied to our browser, so the brief's age and
 *    three-year licence conditions are left out and readers are sent to the employer and the SOS.
 *
 * Posters are the owner's (blog-357, 2 Nov 10.32 to 10.36 PM). The portal listing is in GuideJobListingsSeeder.
 * updateOrCreate, so re-running is safe.
 */
class BusDriverJobsChicagoLicenseHiringProcessBlogSeeder extends Seeder
{
    public const SLUG = 'bus-driver-jobs-chicago-license-hiring-process';

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
                'title' => 'Bus Driver Jobs in Chicago, Illinois: License and Hiring Process',
                'excerpt' => 'How to become a CTA bus operator with no CDL: the 30-day permit deadline after an offer, 30 to 45 days of paid training, $28.15 starting pay, plus Pace hiring events and school bus credentials.',
                'content' => $content,
                'featured_image' => 'blogs/bus-driver-jobs-chicago-license-hiring-process.jpg',
                'tags' => 'bus driver jobs in chicago illinois, chicago bus operator jobs, cta bus driver hiring process, illinois school bus driver permit, paid cdl training chicago, cta bus operator pay, pace bus driver jobs, class b cdl illinois',
                'meta_title' => 'Bus Driver Jobs in Chicago: License and Hiring Process',
                'meta_description' => 'Chicago bus driver jobs: CTA hiring with no CDL, the 30-day permit deadline, paid training, $28.15 starting pay, Pace events and school bus rules.',
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
<p><strong>You can apply to be a Chicago Transit Authority (CTA) bus operator with no commercial driving experience and no commercial licence.</strong> The deadline that matters comes after the offer: CTA gives you 30 days from the job offer to get an Illinois commercial learner's permit (CLP), and you cannot start without it. You must be at least 21 with an acceptable driving record. CTA advertises full-time starting pay of $28.15 an hour and 30 to 45 days of paid training that includes CDL training. School bus driving uses different credentials. We checked CTA and Pace on 3 November 2026.</p>

<h2>City Transit, Suburban Transit or School Routes?</h2>

<p><strong>City transit: CTA.</strong> Search for "bus operator", not only "bus driver". The job is passenger service as much as driving: explaining a detour, helping someone find their stop, keeping calm through a delay. Applications are online only. Official page: <a href="https://www.transitchicago.com/careers/bus-operator/" rel="noopener">CTA: Bus operator</a>.</p>

<p><strong>Suburban transit: Pace.</strong> Pace provides free Class B permit courses and paid training, and holds "Hire-on-the-Spot" events every Thursday at 9am or 1pm. Divisions in the north and west go to Pace headquarters in Arlington Heights; Fox Valley, Heritage, South and Southwest go to Markham. You can apply, interview and take a 90-minute customer service assessment on the day. Apply online first, then bring the confirmation, your Illinois driver's licence and a court purpose driving record abstract. The garage you report to may differ from where you interview. Official page: <a href="https://www.pacebus.com/employee-recruitment-events" rel="noopener">Pace: Recruitment events</a>.</p>

<p><strong>School routes.</strong> Find the actual school transportation company and ask whether it hires trainees. A morning run and an afternoon run are often two separate blocks of work, so ask how many hours are paid and what happens over school holidays.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/bus-driver-jobs-chicago-license-hiring-process-2.jpg"
         alt="Bus operator in a light blue uniform shirt at the wheel of a city bus in Chicago, with the Bean sculpture outside"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Licence Do You Need for CTA?</h2>

<p>CTA requires an Illinois Class B CDL, or a CLP, with three endorsements: knowledge, passenger and air brakes. If you have neither, apply anyway. After an offer you have 30 days to get the CLP, then CTA's paid training prepares you for the full CDL.</p>

<p>The federal rules still apply. For a first Class B CDL or a first passenger endorsement, you must complete entry-level driver training from a provider on the FMCSA Training Provider Registry, and hold the CLP for at least 14 days before the skills test. Fourteen days is a minimum wait, not a test date. A learner's permit lets you practise under supervision; it does not let you carry passengers alone.</p>

<h2>How CTA Hiring Works</h2>

<ol>
<li><strong>Apply online.</strong> List experience with customers, safety rules or reliable early shifts if this is your first driving job.</li>
<li><strong>Take the online assessment.</strong> Instructions come by email; if nothing arrives in 24 hours, check spam. This is CTA's test, separate from the state permit exams. Passing scores are valid for three years.</li>
<li><strong>Interview.</strong> CTA says you should hear back within 48 hours.</li>
<li><strong>Screening and permit.</strong> After an offer come medical and background checks, federally required drug and alcohol testing, and your CLP within 30 days.</li>
<li><strong>Orientation and training.</strong> New Employee Orientation, then 30 to 45 days of paid training.</li>
</ol>

<p>CTA puts the whole process at about two months from your test date. If you interview and are not selected, you can apply again after a year.</p>

<h2>School Bus Credentials in Illinois</h2>

<p>A yellow school bus needs more than a transit licence. Alongside the right CDL class and the passenger endorsement, school bus drivers need the school bus endorsement and an Illinois school bus driver permit, which comes with its own age, record and screening conditions. The Illinois Secretary of State pages would not open for us when we checked, so ask the school transportation employer which conditions apply to you and which paperwork it arranges before you book anything.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/bus-driver-jobs-chicago-license-hiring-process-3.jpg"
         alt="Bus operator in a high-visibility vest beside a bus in downtown Chicago, with steering wheel, licence and checklist scenes"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Pay and Schedules</h2>

<p>CTA lists:</p>

<ul>
<li><strong>$28.15 an hour</strong> starting pay for full-time operators</li>
<li><strong>$42.46 an hour</strong> after 45 months</li>
<li>Medical, dental and vision plans, a pension and paid training</li>
</ul>

<p>At $28.15, 40 hours is $1,126 a week before deductions. Ask whether the training rate is the same and when benefits begin; do not assume these terms apply at Pace or a school contractor. Then plan the trip to your garage for the earliest possible report time, because a route that serves your neighbourhood in the daytime may not get you in before a morning pull-out. Ask whether shifts can be split and how far ahead you learn your days off.</p>

<h2>Questions to Ask Before You Choose a Training Route</h2>

<p>Tell the recruiter exactly what you hold now: a regular licence, a CLP, or a CDL with certain endorsements. Ask what is needed at application and at orientation, and who supplies the vehicle for the skills test. A delivery van driver may have years of city driving but no passenger endorsement; a school bus driver may have the endorsements but still needs the transit agency's training. Bring a copy of your credential and ask for any missing endorsement or restriction to be named before you pay for a course.</p>

<h2>Frequently Asked Questions</h2>

<h3>Can I apply to CTA without a CDL?</h3>
<p>Yes. You can apply with no CDL or CLP and no commercial experience; you must get the CLP within 30 days of an offer.</p>

<h3>Should I pay for a private bus driving school first?</h3>
<p>Not for CTA or Pace before you apply. CTA pays for training, and Pace offers free permit courses.</p>

<h3>How long does CTA hiring take?</h3>
<p>CTA estimates about two months from the test date, followed by 30 to 45 days of paid training.</p>

<h3>Does a Pace hiring event guarantee a job?</h3>
<p>Pace says you can be hired on the spot for available positions, but ask which steps remain afterwards and keep your confirmation.</p>

<h3>How should I prepare for the interview?</h3>
<p>Have a real example of staying calm under pressure, such as an upset customer, and explain how you kept safety first.</p>

<h2>Bottom Line</h2>

<p>For a first Chicago transit job, apply to CTA or Pace before paying for training, and have your CLP plan ready for the 30 days after an offer. For school routes, confirm the endorsement and permit with the employer.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.transitchicago.com/careers/bus-operator/" rel="noopener">CTA: Bus operator requirements and FAQs</a> and <a href="https://www.transitchicago.com/careers/" rel="noopener">CTA Careers: pay and benefits</a></li>
<li><a href="https://www.pacebus.com/employee-recruitment-events" rel="noopener">Pace: Employee recruitment events</a></li>
<li><a href="https://tpr.fmcsa.dot.gov/" rel="noopener">FMCSA: Training Provider Registry</a>, all checked 3 November 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/truck-driver-jobs-dallas-cdl-requirements-training">Truck Driver Jobs in Dallas</a>, the CDL route for trucks.</li>
    <li><a href="/blog/firefighter-jobs-chicago-application-physical-test">Firefighter Jobs in Chicago</a>, another city career with its own tests.</li>
    <li><a href="/blog/cashier-jobs-chicago-no-experience">Cashier Jobs in Chicago</a>, a first job in the same city.</li>
    <li><a href="/blog/driver-jobs-dubai-with-visa">Driver Jobs in Dubai</a>, driving work abroad.</li>
</ul>

HTML;
    }
}
