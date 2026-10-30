<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "How Do I Spot a Fake Overseas Job Offer?"
 *
 * Checked on 12 October 2026. What changed against the brief:
 *
 * 1. The brief sends UK victims to Action Fraud. That service was replaced by
 *    Report Fraud (reportfraud.police.uk) from December 2025 for England,
 *    Wales and Northern Ireland; Scotland uses Police Scotland on 101. The
 *    guide uses the new service.
 * 2. The brief's US reporting link is an advice site; the guide points to
 *    reportfraud.ftc.gov for reporting as well.
 * 3. A Pakistan section is added, since the guide's readers are largely
 *    Pakistani: the Bureau of Emigration and Overseas Employment licensing
 *    check, its complaint route, and the National Cyber Crime Investigation
 *    Agency for online fraud.
 * 4. The brief's step "find the job on Indeed" is replaced with checking the
 *    employer's own website; the Indeed links and the [ADD REAL SOURCE]
 *    placeholders are gone and no scam-loss statistic is quoted without a
 *    source the page can name.
 *
 * The featured image is not on disk yet; this seeder is held back from deploy
 * until the owner supplies it.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class FakeOverseasJobOfferBlogSeeder extends Seeder
{
    public const SLUG = 'how-to-spot-a-fake-overseas-job-offer-2026';

    public function run(): void
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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'How Do I Spot a Fake Overseas Job Offer?',
                'excerpt' => 'A fake overseas job offer asks for money upfront, skips a real interview, guarantees a visa or rushes you. See the seven warning signs, how to verify an employer step by step, the Pakistan checks and where to report a scam.',
                'content' => $content,
                'featured_image' => 'blogs/fake-overseas-job-offer.jpg',
                'tags' => 'fake overseas job offer, overseas job scams, how to verify a job offer, fake recruiter red flags, job scam warning signs, visa scam, fake job offer abroad, report job scam',
                'meta_title' => 'How to Spot Fake Overseas Job Offers (Red Flags)',
                'meta_description' => 'How do I spot a fake overseas job offer? The red flags, how to verify an employer, Pakistan checks and where to report a scam.',
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
<p><strong>A fake overseas job offer usually asks you to pay money upfront, skips a real interview, promises a guaranteed visa or pressures you to act fast.</strong> To verify an offer, confirm the employer through its official website and government registers, contact the company using details you found yourself, and never send money or passport copies until the job is proven real. Government consumer protection agencies in the US, UK, Canada and Australia all list advance-fee payments as a hallmark of job scams.</p>

<h2>What Are the Warning Signs of a Fake Job Offer Abroad?</h2>

<h3>1. You Are Asked to Pay Before You Work</h3>
<p>Legitimate employers do not charge for a job offer, interview or visa "processing" paid to a personal account. Fees for "medical checks", "training kits" or "work permit guarantees" are classic advance-fee scams. Official visa fees are paid to the government, not to a recruiter's private account.</p>

<h3>2. The Offer Arrives With No Interview</h3>
<p>A real employer wants to assess you. If you receive a high-paying contract after a short chat on WhatsApp or Telegram, treat it as a red flag.</p>

<h3>3. The Salary Looks Too Good</h3>
<p>Offers that pay far above the going wage for a role, with free housing and flights for little work, are bait. Compare the pay against the official wage data of that country and occupation, such as the Job Bank wage reports for Canada or the US Bureau of Labor Statistics for the United States.</p>

<h3>4. The Recruiter Uses a Free Email or Messaging App Only</h3>
<p>Be cautious if "HR" writes from a Gmail or Yahoo address, or refuses to speak on a company line or video call. Established companies normally use a domain email that matches their website.</p>

<h3>5. They Guarantee a Visa</h3>
<p>No recruiter or employer can guarantee visa approval, because only the immigration authority decides. A promise of "100% visa success" is a strong sign of fraud.</p>

<h3>6. You Are Pressured to Act Fast</h3>
<p>Phrases like "pay today or lose the slot" are designed to stop you from checking. A real employer allows time to review a contract.</p>

<h3>7. The Documents Look Wrong</h3>
<p>Watch for spelling errors, missing company registration details, a contract with no job description, or a "visa approval letter" issued by the recruiter rather than a government site.</p>

<h2>How Do You Verify a Job Offer Step by Step?</h2>

<ol>
    <li><strong>Search the company independently.</strong> Find its official website yourself instead of clicking links in the message, and check that the address, phone and staff are real.</li>
    <li><strong>Call or email using contact details from the official site</strong>, not from the offer.</li>
    <li><strong>Check government registers.</strong> For example, the UK publishes a <a href="https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers" rel="noopener">Register of Licensed Sponsors</a> that lists employers legally allowed to sponsor skilled workers. Being on it does not mean the role itself can be sponsored.</li>
    <li><strong>Confirm visa rules on the official immigration site</strong> and compare them with what the recruiter says: <a href="https://www.canada.ca/en/immigration-refugees-citizenship.html" rel="noopener">Canada</a>, <a href="https://immi.homeaffairs.gov.au" rel="noopener">Australia</a> and <a href="https://www.make-it-in-germany.com" rel="noopener">Germany</a>.</li>
    <li><strong>Find the same job on the employer's own website</strong> and check that the role and the contact details match the offer.</li>
    <li><strong>Read the contract carefully</strong> and have it reviewed by a lawyer, a union or a trusted adviser before signing.</li>
</ol>

<h2>What Should Pakistani Job Seekers Check?</h2>

<ul>
    <li><strong>Is the recruiter licensed?</strong> Recruiters who place Pakistanis abroad must be licensed Overseas Employment Promoters. Check the company on the <a href="https://beoe.gov.pk" rel="noopener">Bureau of Emigration and Overseas Employment</a> website, and keep the licence number with your papers.</li>
    <li><strong>Do not pay outside the rules.</strong> Pay only the fees the licensed promoter is allowed to charge, get a receipt, and never pay by transfer to a personal account.</li>
    <li><strong>Check the emigration clearance.</strong> Pakistani workers going abroad for employment go through the Bureau's emigration clearance (the Protector of Emigrants) before they travel; an offer that tells you to skip it is a warning sign.</li>
    <li><strong>Report fraud.</strong> Complain to the Bureau about a promoter, and report online fraud to the National Cyber Crime Investigation Agency (NCCIA).</li>
</ul>

<h2>What Should You Never Send to an Unverified Employer?</h2>

<ul>
    <li><strong>Money</strong>, including "deposits", "visa fees" and "insurance".</li>
    <li><strong>Passport or ID scans</strong>, until you have confirmed the employer.</li>
    <li><strong>Bank login details or one-time codes.</strong></li>
    <li><strong>Payment through gift cards or cryptocurrency</strong>, which are difficult to recover.</li>
</ul>

<h2>What Should You Do If You Think You Have Been Scammed?</h2>

<ol>
    <li><strong>Stop all contact</strong> and keep screenshots, emails and payment receipts.</li>
    <li><strong>Contact your bank</strong> immediately to report the payment and ask about a recall.</li>
    <li><strong>Report it</strong> to the official fraud body in your country, or the country the offer claimed to come from:
        <ul>
            <li><a href="https://reportfraud.ftc.gov" rel="noopener">US Federal Trade Commission</a></li>
            <li><a href="https://reportfraud.police.uk" rel="noopener">UK Report Fraud</a> (England, Wales and Northern Ireland; in Scotland call Police Scotland on 101)</li>
            <li><a href="https://antifraudcentre-centreantifraude.ca" rel="noopener">Canadian Anti-Fraud Centre</a></li>
            <li><a href="https://www.scamwatch.gov.au" rel="noopener">Australia Scamwatch</a></li>
        </ul>
    </li>
    <li><strong>Report the listing</strong> to the job board where you found it so it can be removed.</li>
    <li><strong>Change passwords</strong> if you shared account details, and watch for identity misuse if you sent ID documents.</li>
</ol>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/visa-sponsorship" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse Visa Sponsorship Guides on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>How can I tell if a recruiter is real?</h3>
<p>Check whether the recruiter works for a known agency with a verifiable address, a company email domain and a track record. In many countries agencies must be licensed, so look for a registration number and confirm it with the relevant authority.</p>

<h3>Is it normal to pay a recruitment fee?</h3>
<p>In many countries, charging workers for recruitment is restricted or prohibited, and the International Labour Organization's fair recruitment guidelines say workers should not bear recruitment costs. Rules differ by country, so check local law before paying anything.</p>

<h3>Can a fake offer look professional?</h3>
<p>Yes. Scammers copy real company logos, names and staff photos. A professional appearance proves nothing, so rely on independent verification instead.</p>

<h3>Are job boards safe?</h3>
<p>Job boards are generally legitimate, but scam listings can slip through. Treat any listing that moves you quickly to private messaging or asks for payment as suspicious, and report it.</p>

<h3>What if the employer says the visa fee must go to them?</h3>
<p>Official visa fees are paid through the government's own payment system. If the employer asks you to send visa money to a personal or third-party account, stop and verify on the official immigration website.</p>

<h3>Is a "visa approval letter" from a recruiter real?</h3>
<p>Not unless you can verify it on the government's own system. Only the immigration authority issues approvals.</p>

<h3>Can I get my money back?</h3>
<p>Sometimes, if you act fast. Contact your bank at once and report the scam, but recovery is never guaranteed.</p>

<h3>Is a company on the UK sponsor register guaranteed to sponsor me?</h3>
<p>No. It only shows the employer is licensed. The job must also be an eligible occupation and meet the salary and English rules.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/which-countries-are-hiring-foreign-workers-2026">Which Countries Are Hiring Foreign Workers in 2026?</a>: the real routes.</li>
    <li><a href="/blog/how-to-get-a-job-abroad-without-experience-2026">How to Get a Job Abroad Without Experience (2026)</a>: beginner routes and who can use them.</li>
    <li><a href="/blog/warehouse-operative-jobs-uk-visa-sponsorship-2026">Warehouse Operative Jobs in the UK With Visa Sponsorship (2026)</a>: a common "sponsored job" scam.</li>
    <li><a href="/blog/hgv-driver-jobs-uk-visa-sponsorship-2026">HGV Driver Jobs in the UK With Visa Sponsorship (2026)</a>: another one.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal advice. Reporting services and rules change, so confirm them on the official site. If you have lost money, contact your bank and the fraud body of your country straight away.</p>
HTML;
    }
}
