<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Freelance Writing Jobs in Nairobi, Kenya: How to Get Your First Client"
 *
 * Checked on 21 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief has ten apply buttons: six searches on a commercial job board, plus platform and job board pages. No buttons are used, and the job board is not named. The FAQ about the ten links is removed. No platform registration or job board pages are linked.
 * 2. Upwork's M-Pesa help article was found: the name on the M-Pesa account must match the verified Upwork name, a new withdrawal method becomes active after three days, and M-Pesa sets a limit of up to 70,000 shillings per transaction. These details are added to the guide. Upwork's fee article could not be opened from here, so the 0% to 15% figure is attributed to Upwork's fee calculator, which states a variable fee of 0% to 15%. The brief's phrase "per contract" is not repeated.
 * 3. Fiverr's help page could not be opened from here. Its help centre text says a freelancer receives 80% of the client-approved payment once an order is completed, with a 14-day clearance period. The brief's "including extras and tips" could not be confirmed, and Fiverr's client help says tips pay the service fee too, so that clause is removed.
 * 4. ProBlogger's board was read: it has a blog and article writing category, most listings are remote or unspecified, one was US only and one was Florida remote. The guide says that.
 * 5. Five FAQs and eight People Also Search For entries are added; the brief had three FAQs once the ten-links question is removed. A Pakistan note is not needed; this guide is for Kenya.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class FreelanceWritingJobsNairobiKenyaBlogSeeder extends Seeder
{
    public const SLUG = 'freelance-writing-jobs-nairobi-kenya';

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
                'title' => 'Freelance Writing Jobs in Nairobi, Kenya: How to Get Your First Client',
                'excerpt' => 'How to get a first freelance writing client in Nairobi: pick one service, write honest samples, pitch businesses and platforms, agree price and scope and check how Upwork pays into M-Pesa.',
                'content' => $content,
                'featured_image' => 'blogs/freelance-writing-jobs-nairobi-kenya.jpg',
                'tags' => 'freelance writing jobs nairobi, freelance writing kenya beginners, how to get first writing client, online writing jobs kenya, upwork mpesa withdrawal, fiverr fees, problogger job board, writing portfolio',
                'meta_title' => 'Freelance Writing Jobs in Nairobi: First Client Guide',
                'meta_description' => 'Get your first freelance writing client in Nairobi: choose a service, build honest samples, pitch businesses, price the work and check Upwork M-Pesa payouts.',
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
<p><strong>To get your first freelance writing client in Nairobi, choose a clear writing service, create two or three relevant samples, and send personalised proposals to businesses or suitable platform listings.</strong> Start with a manageable paid project, such as a blog article or service page, and agree on scope, payment, and revisions before writing. You do not need an impressive client list to demonstrate ability, but you do need credible samples. Freelancing offers opportunities to work locally or internationally; it does not guarantee regular income.</p>

<h2>Choose One Service You Can Explain Clearly</h2>

<p>Avoid presenting yourself as someone who writes everything for everyone. Begin with a specific service and a client type you understand.</p>

<p>Possible starting points include blog articles for travel businesses, website content for local service companies, product descriptions for online shops, or newsletters for small organisations. Choose a subject you can research accurately and discuss confidently.</p>

<p>Write a simple positioning statement: "I write clear website pages for businesses that need customers to understand their services." A specific offer makes it easier to select samples, identify prospects, and explain why your work is relevant.</p>

<h2>Create Samples Before Searching for Clients</h2>

<p>Prepare two or three original pieces matching your chosen service. For example, create a sample service page, an informative article, and a short email newsletter for a fictional business.</p>

<p>Label unpublished samples honestly. Do not invent clients, testimonials, traffic results, or publication history. A beginner portfolio can still show research, structure, clear language, and careful editing.</p>

<p>Include a short note explaining the intended audience and purpose of each piece. Store samples in a clean portfolio page or accessible document folder. Check that links open correctly without requiring the prospective client to request permission.</p>

<h2>Platforms to Explore From Nairobi</h2>

<h3>Upwork</h3>

<p>Upwork connects freelancers with clients through project listings and proposals. Create a focused profile, upload relevant samples, and read each brief before applying. Check the proposal requirements and any Connects cost shown before submitting.</p>

<p>Upwork's fee calculator says freelancers pay a variable service fee of 0% to 15% on their earnings. Review the fee shown for your opportunity rather than assuming every contract has the same deduction.</p>

<p>Start with projects whose deliverables you understand. A narrow, realistic assignment is easier to price and complete well than an unclear request for unlimited content.</p>

<h3>Fiverr</h3>

<p>Fiverr allows freelancers to offer defined services. Describe what your package includes: content type, approximate length, research depth, turnaround, and revision allowance.</p>

<p>Fiverr's help centre says a freelancer receives 80% of the client-approved payment once an order is completed, and that the funds go through a 14-day clearance period before they can be withdrawn. Consider that deduction when setting prices.</p>

<p>Avoid promising instant rankings, unlimited revisions, or expertise you do not have. A clear service description helps prevent misunderstandings and attracts clients looking for the work you actually provide.</p>

<h3>ProBlogger</h3>

<p>ProBlogger maintains a job board with a blog and article writing category. Read each listing's topic, experience requirements, application instructions and location restrictions. On 21 October 2026 the board showed one listing limited to the US only and another was marked as Florida remote, so a remote advertisement does not necessarily accept applicants worldwide.</p>

<p>Research the advertiser before sharing personal information. A job board listing is a lead to assess, not a guarantee of payment or suitability. Follow the requested application format and send only the samples most relevant to that assignment.</p>

<h2>Find Clients Beyond Freelance Platforms</h2>

<p>Look for businesses whose customers need useful written information. Nairobi agencies, consultants, retailers, hospitality businesses, and professional service providers can be prospects when your skills match their needs.</p>

<p>Review the business website before contacting anyone. Identify one concrete opportunity, such as an unclear service page or missing customer guide. Avoid making unsupported claims about lost sales or search rankings.</p>

<p>Prepare a short pitch explaining the issue, the deliverable you propose, and a relevant sample. Use appropriate public business contact channels and keep follow ups respectful. Quality research is more useful than sending identical messages everywhere.</p>

<h2>Use a Simple First Client Pitch</h2>

<p>Adapt this example to the actual business:</p>

<p>"Hello, I noticed your website explains your services but does not answer several common booking questions. I write practical website content for service businesses. I could prepare one clear FAQ page covering your process, requirements, and next steps. Here is a relevant sample. Would a short outline be useful?"</p>

<p>Keep the message specific and easy to answer. Offer a defined paid project after discussing the brief. A short outline can demonstrate understanding without committing to a complete unpaid article tailored for commercial use.</p>

<h2>Agree on Price, Scope, and Payment</h2>

<p>Before starting, confirm the audience, objective, deliverables, deadline, source requirements, revision limits, and approval process. Clarify whether interviews, images, uploading, or keyword research are included.</p>

<p>Price the total work rather than the word count alone. Research, meetings, editing, and administration all take time. Agree how additional requests will be priced.</p>

<p>For direct clients, document payment dates and any deposit or milestones. For platform clients, use the platform's approved contract and payment processes. Read payment protection conditions carefully; merely having an account does not guarantee every transaction is protected.</p>

<h2>Receiving Payments in Kenya</h2>

<p>Upwork supports M-Pesa withdrawals for freelancers in Kenya. Its help article says your first and last name must exactly match the name on your M-Pesa account and your verified Upwork name, that a new withdrawal method becomes active after three days for security, and that M-Pesa sets a limit of up to 70,000 Kenyan shillings per transaction. Confirm current limits, fees and processing conditions before you choose it.</p>

<p>For other platforms or direct clients, verify which receiving methods work for your account. Agree on currency and who covers transfer costs. Keep invoices, contracts, and payment records organised, and distinguish invoiced work from money actually received.</p>

<h2>Turn the First Assignment Into Repeat Work</h2>

<p>Deliver on the agreed date and explain how the draft addresses the brief. Ask focused questions when feedback is unclear, and keep revisions within the agreed scope. After approval, request permission before displaying the work publicly or quoting a testimonial. Suggest a relevant next assignment only when it serves the client's needs. Reliable communication and careful editing give clients practical reasons to work with you again.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I Need a Degree?</h3>
<p>Requirements depend on the client and subject. Relevant samples can demonstrate writing ability, but specialist assignments may require credentials or professional experience.</p>

<h3>Can I Use AI Tools?</h3>
<p>Follow the client's instructions and disclosure requirements. Verify facts, protect confidential information, and ensure the final work meets the brief. Never invent quotations or sources.</p>

<h3>How Quickly Can I Find a Client?</h3>
<p>There is no guaranteed timeline. Improve your samples and targeting based on responses, and avoid depending on immediate freelance income for essential expenses.</p>

<h3>Can I get paid through M-Pesa?</h3>
<p>Upwork supports M-Pesa withdrawals in Kenya. The name on the M-Pesa account must exactly match your verified Upwork name, and M-Pesa sets a limit of up to 70,000 shillings per transaction.</p>

<h2>Bottom Line</h2>

<p>Your first freelance writing client in Nairobi is more likely to understand your value when you offer a specific service, relevant samples, and a clear process. Apply selectively, agree on payment terms, and deliver dependable work that can support repeat business.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://support.upwork.com/hc/en-us/articles/115001615647-How-to-use-M-Pesa-to-withdrawal-your-earnings" rel="noopener">Upwork Help: How to use M-Pesa to withdraw your earnings</a></li>
<li><a href="https://help.fiverr.com/hc/de/articles/34069565843985" rel="noopener">Fiverr Help Center: How Fiverr works for freelancers</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/online-tutoring-jobs-new-jersey">Online Tutoring Jobs in New Jersey</a>: platform fees compared before you commit.</li>
    <li><a href="/blog/virtual-assistant-jobs-manila-philippines">Virtual Assistant Jobs in Manila, Philippines</a>: another remote route with a practice portfolio.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p><em>Reviewed on 21 October 2026.</em></p>

HTML;
    }
}
