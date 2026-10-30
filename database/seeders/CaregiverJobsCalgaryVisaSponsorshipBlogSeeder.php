<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Caregiver Jobs in Calgary With Visa Sponsorship"
 *
 * Built on 28 October 2026 from the owner's outline (no frontmatter; slug and SEO fields written here). Checked
 * against official pages. What changed against the brief:
 *
 * 1. The brief's six Indeed buttons and the Kijiji suggestion are dropped (the site links only official or employer
 *    pages); Job Bank stays. Every "[ADD REAL SOURCE]" placeholder is replaced with a checked source or removed.
 * 2. IRCC Ministerial Instructions MI86, MI87, MI88 and MI91 page fetched: IRCC "will not accept new applications under
 *    the Home Care Worker Immigration Pilots"; the instructions "take effect on March 31, 2026" and "will remain in
 *    effect until March 30, 2030, unless repealed earlier". The caregivers page lists the older Home Child Care Provider
 *    and Home Support Worker pilots as closed, last day 17 June 2024.
 * 3. Job Bank fetched for Personal Support Worker - Home Support (NOC 44101): Calgary Region $17.00 low, $20.00 median,
 *    $26.00 high, Alberta $15.50, $20.00, $25.25, reference period 2023-2024, updated November 19, 2025 (the brief said
 *    November 2023); 52.9% of Alberta workers in the occupation get at least one non-wage benefit.
 * 4. ALIS health care aide profile fetched: average wage $20.27, average salary $26,304, 39,705 employed in Alberta,
 *    full-time HCA programmes 15 to 27 weeks, standard provincial curriculum. The brief's respite worker range and its
 *    24.8 hours a week could not be confirmed and are dropped.
 * 5. Canadian Anti-Fraud Centre job fraud page fetched for the scam section (task and crypto job fraud, counterfeit
 *    cheque schemes).
 * 6. FAQs expanded to eight; eight People Also Search For entries and a note for applicants in Pakistan added.
 *
 * Posters from public/public/blog-355 (28 October set, images 1 and 2). The portal listing is in
 * GuideJobListingsSeeder. updateOrCreate, so re-running is safe.
 */
class CaregiverJobsCalgaryVisaSponsorshipBlogSeeder extends Seeder
{
    public const SLUG = 'caregiver-jobs-calgary-visa-sponsorship';

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
                'title' => 'Caregiver Jobs in Calgary With Visa Sponsorship: Pay, Requirements and the PR Pause',
                'excerpt' => 'Can you get a caregiver job in Calgary with visa sponsorship? How LMIA-based work permits work, why the Home Care Worker PR pilots take no new applications until March 2030, Job Bank pay of $17 to $26 an hour, requirements and how to avoid scams.',
                'content' => $content,
                'featured_image' => 'blogs/caregiver-jobs-calgary-visa-sponsorship.jpg',
                'tags' => 'caregiver jobs in calgary with visa sponsorship, caregiver jobs calgary, home support worker calgary, caregiver salary calgary, lmia caregiver jobs canada, home care worker pilot 2026, health care aide alberta, live in caregiver calgary',
                'meta_title' => 'Caregiver Jobs in Calgary With Visa Sponsorship (2026)',
                'meta_description' => 'Caregiver jobs in Calgary with visa sponsorship: LMIA work permits, the PR pilot pause to March 2030, Job Bank pay of $17 to $26, requirements and scams.',
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
<p><strong>Yes, some Calgary employers hire foreign caregivers through a work permit backed by a Labour Market Impact Assessment (LMIA), but sponsorship is not guaranteed and is hard to find.</strong> Canada's permanent residence route for caregivers is paused: IRCC will not accept new applications under the Home Care Worker Immigration Pilots from 31 March 2026 until 30 March 2030. So treat any Calgary caregiver offer as a temporary work permit, not a promise of PR. Job Bank lists Calgary home support workers at $17 to $26 an hour, with a median of $20. This guide uses IRCC, Job Bank, Alberta's ALIS and the Canadian Anti-Fraud Centre, checked on 28 October 2026.</p>

<h2>What Visa Sponsorship Actually Means for Caregivers</h2>

<p>In Canada, "sponsorship" for a caregiver usually means an employer applies for an LMIA, which shows Employment and Social Development Canada that no suitable Canadian or permanent resident was available and that the job pays the prevailing wage. With a positive LMIA and a job offer, you then apply to IRCC for an employer-specific work permit. The employer must prove the job is genuine. Under the Temporary Foreign Worker Program, employers may not charge you, or recover from you, recruitment fees, so anyone asking you to pay for the job is a warning sign. Read <a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a> for the Canadian permit documents and fees.</p>

<h2>Is the Caregiver PR Pathway Still Open?</h2>

<p>No. The Home Care Worker Immigration Pilots (Child Care and Home Support) opened on 31 March 2025 and filled their caps quickly. IRCC's Ministerial Instructions say it "will not accept new applications under the Home Care Worker Immigration Pilots", taking effect on 31 March 2026 and staying in effect until 30 March 2030 unless repealed earlier. The older Home Child Care Provider and Home Support Worker pilots closed on 17 June 2024. Calgary work can still build Canadian experience and references, but PR would have to come through other routes, such as Alberta's provincial nominee streams or Express Entry. Speak to a licensed immigration consultant (RCIC) or a lawyer about your own case. Sources: <a href="https://www.canada.ca/en/immigration-refugees-citizenship/corporate/mandate/policies-operational-instructions-agreements/ministerial-instructions/other-goals/mi86-87-88.html" rel="noopener">IRCC: Ministerial Instructions for the Home Care Worker Immigration Pilots</a> and <a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/immigrate-canada/caregivers.html" rel="noopener">IRCC: Caregivers</a>.</p>

<h2>How Much Do Caregivers Earn in Calgary?</h2>

<p>Job Bank's prevailing wages for Personal Support Worker - Home Support (NOC 44101), updated on 19 November 2025 for the 2023-2024 reference period, are:</p>

<table style="width:100%;border-collapse:collapse;margin:18px 0;">
<thead><tr><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Area</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Low</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">Median</th><th style="text-align:left;border-bottom:2px solid #e5e7eb;padding:8px;">High</th></tr></thead>
<tbody>
<tr><td style="padding:8px;border-bottom:1px solid #f1f5f9;">Calgary Region</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$17.00</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$20.00</td><td style="padding:8px;border-bottom:1px solid #f1f5f9;">$26.00</td></tr>
<tr><td style="padding:8px;">Alberta</td><td style="padding:8px;">$15.50</td><td style="padding:8px;">$20.00</td><td style="padding:8px;">$25.25</td></tr>
</tbody>
</table>

<p>Job Bank also says 52.9% of Alberta workers in this occupation receive at least one non-wage benefit. For health care aides, Alberta's ALIS reports an average wage of $20.27 an hour and an average salary of $26,304, which shows how many caregiving jobs are part-time. At the $20 median, a full-time 40-hour week comes to about $41,600 a year before tax. Overnight shifts, full-time hours and live-in roles change the total. Sources: <a href="https://www.jobbank.gc.ca/marketreport/wages-occupation/24584/geo25411" rel="noopener">Job Bank: Personal support worker wages, Calgary Region</a> and <a href="https://alis.alberta.ca/occinfo/occupations-in-alberta/occupation-profiles/health-care-aide/" rel="noopener">ALIS: Health care aide</a>.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/caregiver-jobs-calgary-visa-sponsorship-care.jpg"
         alt="A caregiver in navy scrubs helping an elderly woman with a walker by the Bow River, with the Calgary skyline and Rocky Mountains behind"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Are the Requirements to Work as a Caregiver in Calgary?</h2>

<p>Home support work usually needs a high school diploma or some on-the-job training. In practice employers often ask for:</p>

<ul>
    <li>A high school diploma or equivalent.</li>
    <li>A Health Care Aide (HCA) certificate for hospital and continuing care roles. Alberta has a standard provincial HCA curriculum, and full-time programmes run 15 to 27 weeks.</li>
    <li>First Aid and CPR certification.</li>
    <li>A criminal record check, including a vulnerable sector check.</li>
    <li>Strong English for reading care plans and talking with families.</li>
    <li>Proof that you are authorised to work in Canada.</li>
</ul>

<p>ALIS counts 39,705 health care aides employed in Alberta. Private home roles may accept less formal training; hospitals and care facilities generally want the HCA certificate.</p>

<h2>Can You Get a Caregiver Job in Calgary With No Experience?</h2>

<p>Yes, mostly if you already have the right to work in Canada: companion, respite and private-family roles often train on the job. From overseas, having no experience makes sponsorship much harder, because the employer has to justify hiring from abroad through the LMIA. A First Aid and CPR course and an HCA programme are the best ways to stand out.</p>

<h2>What Types of Caregiver Jobs Are Available in Calgary?</h2>

<ul>
    <li><strong>Home support worker:</strong> personal care and daily tasks in a client's home.</li>
    <li><strong>Senior caregiver:</strong> meals, mobility help and medication reminders.</li>
    <li><strong>Companion:</strong> social support, errands and light housekeeping.</li>
    <li><strong>Disability support worker:</strong> support for adults or children with disabilities.</li>
    <li><strong>Overnight and weekend caregiver:</strong> shift work suited to part-time schedules.</li>
    <li><strong>Live-in caregiver:</strong> room and board may be offered; get the accommodation terms and any deductions in writing first.</li>
    <li><strong>Health care aide:</strong> hospital and continuing care roles, usually requiring the HCA certificate.</li>
</ul>

<h2>Where Can You Find Caregiver Jobs in Calgary?</h2>

<p>Job Bank is the Government of Canada's own job board; search for "caregiver", "home support worker" and "personal support worker" in Calgary, and check whether a posting says the employer will consider an LMIA. Home care agencies and continuing care operators also post on their own careers pages. Classified sites used by private families carry a higher scam risk, so never send money or copies of your passport before you have verified the employer. Source: <a href="https://www.jobbank.gc.ca/findajob" rel="noopener">Job Bank: Find a job</a>.</p>

<h2>How Do You Apply for Caregiver Jobs in Calgary?</h2>

<ol>
    <li>Build a Canadian-style resume: one to two pages, no photo, age or marital status.</li>
    <li>Add your certificates: HCA, First Aid, CPR and any care experience, including family caregiving.</li>
    <li>Search Job Bank and agency sites using "caregiver", "home support worker" and "personal support worker".</li>
    <li>Tell employers your work status honestly and ask whether they support LMIA applications.</li>
    <li>Prepare for scenario questions, such as how you would handle a client who refuses care.</li>
    <li>Check the written offer lists the wage, hours, duties and the employer's legal name and address.</li>
</ol>

<h2>How Can You Avoid Caregiver Job and Visa Scams?</h2>

<p>Be cautious if someone guarantees a visa, asks for an upfront payment, or contacts you only through WhatsApp or a free email account. The Canadian Anti-Fraud Centre warns about fake jobs that pay a small first commission to win trust and then ask for deposits, and about counterfeit cheques sent with instructions to forward part of the money. Verify the company's address and phone number yourself, check any immigration representative on the official register of licensed consultants, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a>. Source: <a href="https://antifraudcentre-centreantifraude.ca/scams-fraudes/job-emploi-eng.htm" rel="noopener">Canadian Anti-Fraud Centre: Job scams</a>.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>Check any Pakistani agent's licence with the Bureau of Emigration and Overseas Employment before paying anything, and remember that a genuine Canadian employer applies for the LMIA itself. Read <a href="/blog/how-to-find-job-abroad-from-pakistan-2026">how to find a job abroad from Pakistan</a> for the checks before you leave.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/blog?category=career-advice" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Career Advice Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can foreigners apply for caregiver jobs in Calgary?</h3>
<p>Yes, but the employer usually needs an LMIA and you need a work permit. People already in Canada with valid work authorisation have the easiest path.</p>

<h3>Is the caregiver PR pilot open in 2026?</h3>
<p>No. IRCC will not accept new Home Care Worker pilot applications from 31 March 2026 until 30 March 2030.</p>

<h3>How much do caregivers earn in Calgary?</h3>
<p>Job Bank gives $17 to $26 an hour with a $20 median for home support workers in the Calgary Region.</p>

<h3>How much is that per year?</h3>
<p>About $41,600 before tax at $20 an hour full time; many roles are part time, so actual earnings are often lower.</p>

<h3>Do Calgary caregiver jobs offer accommodation?</h3>
<p>Some live-in roles include a room. Get the terms, any deduction and your time-off rights in writing.</p>

<h3>Do I need an HCA certificate?</h3>
<p>For hospital and continuing care roles, usually yes; private home roles may accept less training.</p>

<h3>Can an employer charge me for the job?</h3>
<p>No. Temporary Foreign Worker Program employers may not charge or recover recruitment fees from workers.</p>

<h3>What is the best website for caregiver jobs in Calgary?</h3>
<p>Job Bank, the official government board, plus home care agencies' own careers pages.</p>

<h2>Bottom Line</h2>

<p>A Calgary caregiver job with sponsorship is possible through an employer's LMIA, but it is a temporary work permit while the PR pilots are paused until March 2030. Compare offers against Job Bank's $17 to $26 range, get the HCA and First Aid certificates, and verify every employer before you pay or share documents. This guide cannot promise a job, a work permit or PR.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://www.canada.ca/en/immigration-refugees-citizenship/corporate/mandate/policies-operational-instructions-agreements/ministerial-instructions/other-goals/mi86-87-88.html" rel="noopener">IRCC: Ministerial Instructions MI86, MI87, MI88 and MI91, Home Care Worker Immigration Pilots</a></li>
<li><a href="https://www.canada.ca/en/immigration-refugees-citizenship/services/immigrate-canada/caregivers.html" rel="noopener">IRCC: Caregivers</a></li>
<li><a href="https://www.jobbank.gc.ca/marketreport/wages-occupation/24584/geo25411" rel="noopener">Job Bank: Personal support worker - home support wages, Calgary Region</a>, updated 19 November 2025</li>
<li><a href="https://alis.alberta.ca/occinfo/occupations-in-alberta/occupation-profiles/health-care-aide/" rel="noopener">Alberta ALIS: Health care aide</a></li>
<li><a href="https://antifraudcentre-centreantifraude.ca/scams-fraudes/job-emploi-eng.htm" rel="noopener">Canadian Anti-Fraud Centre: Job scams</a>, all checked 28 October 2026</li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/nursing-jobs-toronto-foreign-trained-nurses">Nursing Jobs in Toronto for Foreign-Trained Nurses</a>, the next step up in Canadian care work.</li>
    <li><a href="/blog/cna-jobs-atlanta-georgia-certification-pay">CNA Jobs in Atlanta</a>, how the US nursing assistant route compares.</li>
    <li><a href="/blog/work-visas-pakistanis-2026-country-guide">Work Visas for Pakistanis in 2026</a>, Canadian work permit fees and documents.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>, the warning signs of a recruitment scam.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is a general guide and not legal, immigration or career advice. Reviewed on 28 October 2026 against the pages named in Sources. Confirm current rules on canada.ca before paying anyone or applying. It does not promise a job, a work permit, sponsorship or PR.</p>
HTML;
    }
}
