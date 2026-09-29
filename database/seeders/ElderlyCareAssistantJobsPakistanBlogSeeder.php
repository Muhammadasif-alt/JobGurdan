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
use Illuminate\Support\Facades\DB;

/**
 * Elderly care assistant careers in Pakistan, checked on 29 September 2026.
 *
 * Corrections applied to the supplied brief:
 *  1. All four Indeed links are gone, with the passages that described what
 *     "a current Lahore care-related listing on Indeed" shows.
 *  2. The brief quoted two Indeed salary bands: Rs 40,000 to Rs 90,000 for a
 *     Lahore Care Taker Nurse, and Rs 25,000 to Rs 30,000 for overnight
 *     resident support. Aggregator salary data is not republished here, and a
 *     single advert is not a market rate.
 *  3. The closing FAQ told readers to search Indeed Pakistan. It now points at
 *     the NAVTTC qualification register and employer career pages.
 *  4. The brief mentioned the NAVTTC curriculum without naming it. It is a
 *     real, free, government qualification: Elderly Care Giver, code
 *     090921ECG, nine weeks, inside the Domestic Worker Program. That is the
 *     single most useful fact for this reader and it is now the spine of the
 *     guide.
 *
 * This guide deliberately does not restate the clinical-versus-non-clinical
 * line, the absence of a CNA licence or the PNMC register. Those are argued in
 * the home healthcare assistant guide, which this one links to, so that the
 * two pages answer different questions instead of competing.
 *
 * The nationwide job overview and this career guide are not a single vacancy.
 */
class ElderlyCareAssistantJobsPakistanBlogSeeder extends Seeder
{
    public const SLUG = 'elderly-care-assistant-jobs-in-pakistan';

    public const APPLY_URL = 'https://carenest.pk/careers.html';

    public function run(): void
    {
        DB::transaction(function (): void {
            $category = Category::firstOrCreate(['slug' => 'healthcare'], ['name' => 'Healthcare']);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => 'Elderly Care Employers, Pakistan (Aggregated)'],
                ['display_reference' => 'pk-elderly-care-assistant-aggregated', 'type' => 'Private']
            );
            $location = Location::firstOrCreate(
                ['name' => 'Pakistan'],
                ['area' => 'Nationwide', 'country' => 'Pakistan']
            );
            Job::updateOrCreate(
                [
                    'position' => 'Elderly Care Assistant, Pakistan Employers',
                    'advertiser_id' => $advertiser->id,
                ],
                [
                    'application_url' => self::APPLY_URL,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $this->jobDescription(),
                    'employment_type' => 'Full-time / Part-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Commonly 12-hour day or night shifts, with 24/7 live-in and part-time arrangements also advertised',
                    'language' => 'English, Urdu',
                    // Elder care providers advertise without publishing pay, and
                    // dementia-experienced rates differ sharply from general ones.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'seo_keywords' => 'elderly care assistant jobs in pakistan, elderly caregiver jobs, dementia care jobs pakistan, navttc elderly care giver, live in caregiver jobs',
                    'meta_description' => 'Elderly care assistant and caregiver roles with providers in Islamabad, Rawalpindi, Lahore and Karachi, including dementia care and live-in arrangements.',
                ]
            );
            $blogCategory = BlogCatgories::firstOrCreate(
                ['slug' => 'career-advice'],
                ['name' => 'Career Advice', 'description' => 'Practical guides on finding work, applying and building a career.']
            );
            $author = User::where('role', 'admin')->first();
            $content = $this->postBody();
            Blog::updateOrCreate(
                ['slug' => self::SLUG],
                [
                    'blog_catgories_id' => $blogCategory->id,
                    'author_id' => $author?->id,
                    'author_name' => $author?->name ?? 'JobGader Editorial',
                    'title' => 'Elderly Care Assistant Jobs in Pakistan',
                    'excerpt' => 'There is a free government qualification for this job and almost nobody mentions it. NAVTTC runs a nine-week Elderly Care Giver course, code 090921ECG. This guide covers what it teaches, what dementia work actually demands, and who is hiring.',
                    'content' => $content,
                    'featured_image' => 'blogs/elderly-care-assistant-jobs-pakistan.jpg',
                    'tags' => 'elderly care assistant jobs in pakistan, elderly caregiver jobs, dementia care jobs pakistan, navttc elderly care giver course, live in caregiver jobs, alzheimers care jobs, old age care jobs pakistan, caregiver training pakistan',
                    'meta_title' => 'Elderly Care Assistant Jobs in Pakistan: Training and Pay',
                    'meta_description' => 'Elderly care assistant jobs in Pakistan: the free nine-week NAVTTC Elderly Care Giver qualification, dementia work, live-in terms, and who is hiring.',
                    'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                ]
            );
        });
    }

    private function jobDescription(): string
    {
        return <<<'HTML'
<p>This is an overview of elderly care work with providers across Pakistan. It is not an advert for one guaranteed vacancy, and no single employer has approved it.</p>
<p>Elderly care assistants support older adults with personal care, mobility, meals, medication reminders, companionship and household safety. Providers advertise the same work as Elderly Care Assistant, Caregiver, Elder Support Attendant, Home Care Assistant or Care Taker, so search several titles.</p>
<p>Two things separate a good post from a bad one: whether the employer is a registered provider or a private household, and whether the client has dementia. Both change the job, the risk and the pay.</p>
HTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Most guides to elderly care work in Pakistan tell you to be patient and compassionate, then send you to a job board. Neither helps you get hired over the hundred other people who are also patient and compassionate.</p>

<p>There is something that does, and it is free, government-run and almost never mentioned: a formal Elderly Care Giver qualification. This guide starts there, then covers the part of the work that actually pays more, and the two providers currently recruiting.</p>

<h2>There is a government qualification for this job</h2>

<p>The National Vocational and Technical Training Commission publishes a qualification called <strong>Elderly Care Giver</strong>, qualification code <strong>090921ECG</strong>, in the sector it lists as Aesthetics, Health Care and Services. NAVTTC gives the duration as <strong>nine weeks</strong>, and places it inside its Domestic Worker Program.</p>

<p>That matters for a simple reason. Elderly care in Pakistan is a field where almost nobody applying has any certificate at all, because families hire by word of mouth and providers train on the job. A nine-week state qualification is not a nursing degree and does not pretend to be, but it is a document, and a document separates you from an identical candidate who does not have one.</p>

<p>The qualification is listed on the <a href="https://navttc.gov.pk/qualifications/domestic-worker-3-elderly-care-giver/" target="_blank" rel="noopener nofollow">NAVTTC qualifications register</a>, which also carries the downloadable lesson plan. Courses under NAVTTC programmes are delivered through affiliated institutes, and the register is the place to confirm what is currently running rather than trusting a third-party advertisement.</p>

<img src="/public/storage/blogs/elderly-care-assistant-jobs-pakistan-companionship.jpg" alt="Elderly care assistant sitting with an older woman in Pakistan" />

<h2>What a nine-week course can and cannot do for you</h2>

<p>Be realistic about what you are buying with the time. A short vocational course covers the practical ground of elder care: personal care and hygiene, safe mobility and transfers, nutrition and meal preparation, basic health observation, documentation, household safety and communication.</p>

<p>What it does not do is make you a clinician. It does not authorise you to give injections, dress wounds, manage a catheter, or administer medication rather than remind someone to take it. Those are regulated nursing acts, and the line matters enough that our <a href="/blog/home-healthcare-assistant-jobs-in-pakistan">home healthcare assistant guide</a> is built around it, including how to verify any licence by CNIC.</p>

<p>If an employer offers you an elderly care post and then asks you to do clinical tasks, the certificate in your hand does not cover you. Say so.</p>

<h2>Dementia care is a different job, and should be paid as one</h2>

<img src="/public/storage/blogs/elderly-care-assistant-jobs-pakistan-dementia.jpg" alt="Caregiver supporting an older man with dementia in a Pakistani home" />

<p>The single largest split in elderly care work is whether the client has dementia or Alzheimer's disease. Families and agencies routinely advertise both under the same title and at a similar rate, and they are not the same job.</p>

<p>General elderly support is predictable. The person knows who you are, can tell you what hurts, and remembers the routine. Dementia care is not predictable. It can involve repeated questions, disorientation about time and place, resistance to personal care, disrupted sleep that turns a day post into a night one, wandering risk, and behaviour that is distressing before you learn to read what is causing it.</p>

<p>Reliable national figures for how many people in Pakistan live with dementia do not exist. Published estimates range from the low hundreds of thousands to over a million, and researchers themselves say the variation reflects a lack of study rather than genuine disagreement. Treat any single number you are quoted with suspicion, including in a job advertisement.</p>

<p>What you can act on is narrower and more useful. If a family describes memory loss, confusion, night waking or difficulty recognising people, you are being offered dementia care. Ask whether the person has a diagnosis, who else is involved, what has already been tried, and what happens at night. Then price the job as the specialist work it is, or decline it if you have not done it before.</p>

<h2>Who is recruiting right now</h2>

<p><strong>Care Nest, Islamabad and Rawalpindi.</strong> Its <a href="https://carenest.pk/careers.html" target="_blank" rel="noopener nofollow">recruitment page</a> carries a department it names Caregiving and Elder Support, alongside nursing, physiotherapy and rehabilitation. The listed shift options are 12-hour day or night, 24/7 live-in, part-time, and per-procedure clinical visits. Applications go through an online form.</p>

<p><strong>Sahara Home Healthcare, Karachi.</strong> Its <a href="https://saharahomehealthcare.com/jobs.php" target="_blank" rel="noopener nofollow">jobs page</a> lists a Home Health Aide role across Karachi on a part-time and full-time basis, covering companionship, help with daily activities, personal care, meal preparation, light housekeeping, medication reminders and patient safety. The employer states that training is provided, which makes it one of the few openings genuinely open to beginners.</p>

<p>Both are registered providers rather than private households, and that distinction decides which law protects you. Our <a href="/blog/personal-care-assistant-jobs-in-pakistan">personal care assistant guide</a> sets out what changes when the person paying you is a family instead of a company, and what you are entitled to put in writing.</p>

<h2>What the work involves day to day</h2>

<p>The core of it is steady rather than dramatic:</p>

<ul>
    <li><strong>Personal care.</strong> Bathing, dressing, grooming, oral care and toileting, done in a way that protects dignity and privacy.</li>
    <li><strong>Mobility.</strong> Transfers between bed, chair and bathroom, walking support, and preventing falls, which is the single most common serious incident in this work.</li>
    <li><strong>Meals.</strong> Preparation, and attention to what the person can actually chew and swallow safely.</li>
    <li><strong>Medication reminders.</strong> Prompting and recording, not administering.</li>
    <li><strong>Observation.</strong> Noticing that something has changed and telling the right person promptly, which is most of your clinical value.</li>
    <li><strong>Companionship.</strong> Not filler. Isolation measurably worsens outcomes for older adults, and this is often the part families value most.</li>
</ul>

<h2>Pay, and why no figure is quoted here</h2>

<p>You will find elderly care salary ranges quoted freely online. Almost all of them come from individual job adverts on aggregator sites, and this guide does not republish those, because one advert for one client in one city is not a rate you can plan around.</p>

<p>What is worth knowing is which variables actually move the number: whether the post is live-in or shift-based, whether the client has dementia, whether nights are involved, the city, and whether you are hired by a provider or directly by a family. Providers pay less per hour than a family might but usually pay on time and supervise the work. Families sometimes pay more and sometimes disappear.</p>

<p>Ask for the figure in writing before you accept, along with hours, days off and what happens if the client is hospitalised.</p>

<h2>Getting hired without experience</h2>

<p>The realistic sequence, in order:</p>

<ul>
    <li>Take the NAVTTC Elderly Care Giver qualification if a course is running near you. Nine weeks, and it gives you a document to show.</li>
    <li>Apply to a provider that says it trains, rather than to a private family. Supervision in your first year is worth more than a higher rate.</li>
    <li>Say plainly what you have not done. Claiming dementia experience you do not have ends badly for you and dangerously for the client.</li>
    <li>Keep a simple record of the clients you have supported, the conditions involved and the length of each post. That log becomes your CV in a field with no formal career ladder.</li>
</ul>

<h2>Frequently Asked Questions</h2>

<h3>Is there a recognised elderly care qualification in Pakistan?</h3>
<p>Yes. NAVTTC publishes an Elderly Care Giver qualification, code 090921ECG, listed at nine weeks within its Domestic Worker Program. It is a vocational qualification, not a nursing registration.</p>

<h3>Do I need a nursing degree to work as an elderly care assistant?</h3>
<p>No, for non-clinical elderly support. You do need registration to perform nursing acts such as injections, wound care or catheter management. Never accept clinical tasks you are not registered and trained to do.</p>

<h3>Can I get an elderly care job with no experience?</h3>
<p>Yes, with a provider that trains. Sahara Home Healthcare states training is provided on its Home Health Aide opening. Private families and dementia posts are far more likely to require prior experience.</p>

<h3>How much do elderly caregivers earn in Pakistan?</h3>
<p>No dependable published range exists, and the figures circulating online are taken from individual adverts. Pay moves with live-in versus shift work, dementia involvement, night duty, city and whether a provider or a family employs you. Get the figure in writing before accepting.</p>

<h3>What is the difference between elderly care and dementia care?</h3>
<p>Dementia care adds disorientation, disrupted sleep, resistance to personal care and wandering risk to the same physical tasks. It is specialist work, frequently advertised at the general rate. Ask about diagnosis and night-time behaviour before agreeing terms.</p>

<h3>Are live-in elderly care jobs common?</h3>
<p>Yes. Care Nest lists 24/7 live-in among its shift options. Before accepting one, settle rest hours, accommodation, meals, weekly time off and night duties in writing, because a live-in post in a private home carries specific legal entitlements.</p>

<h3>Which employers are hiring elderly carers in Pakistan?</h3>
<p>Care Nest recruits caregivers and elder support staff across Islamabad and Rawalpindi. Sahara Home Healthcare recruits Home Health Aides across Karachi. Both publish their own application routes rather than advertising through intermediaries.</p>

<h3>Is elderly care a career or a stopgap?</h3>
<p>It can be either. Workers who document their posts, take the vocational qualification and add dementia or palliative training progress into senior caregiver and coordination roles. Without a record of what you have done, progression is difficult because the field has no formal ladder.</p>

<h2>People Also Search For</h2>

<h3>Elderly caregiver jobs in Lahore</h3>
<p>Mostly private-household posts advertised through word of mouth. Confirm who your employer is before agreeing terms.</p>

<h3>NAVTTC elderly care giver course</h3>
<p>Qualification code 090921ECG, nine weeks, listed under the Domestic Worker Program on the NAVTTC register.</p>

<h3>Dementia care jobs Pakistan</h3>
<p>Specialist work regularly advertised at the general caregiving rate. Ask about diagnosis, night behaviour and who else is involved.</p>

<h3>Live-in caregiver jobs Pakistan</h3>
<p>Common in elder care. Rest hours, accommodation and weekly time off are entitlements, not favours, when a household employs you.</p>

<h3>Old age home jobs in Pakistan</h3>
<p>Residential facilities recruit attendants and care staff on shift patterns rather than live-in terms, usually with on-site supervision.</p>

<h3>Elderly care assistant salary in Pakistan</h3>
<p>No reliable published range exists. The figures circulating come from single adverts and should not be treated as a market rate.</p>

<h3>Alzheimers caregiver jobs</h3>
<p>Advertised under caregiver, care taker and attendant titles. Treat any post mentioning memory loss or night waking as dementia work.</p>

<h3>Caregiver jobs in Islamabad and Rawalpindi</h3>
<p>Care Nest recruits across the twin cities with 12-hour, live-in and part-time options through its own online form.</p>

<h2>Related career guides</h2>

<ul>
    <li><a href="/blog/home-healthcare-assistant-jobs-in-pakistan">Home Healthcare Assistant Jobs in Pakistan</a> &mdash; where the clinical line sits, and how to verify any licence by CNIC.</li>
    <li><a href="/blog/personal-care-assistant-jobs-in-pakistan">Personal Care Assistant Jobs in Pakistan</a> &mdash; what changes legally when a family employs you instead of a company.</li>
    <li><a href="/blog/disability-support-worker-jobs-in-pakistan">Disability Support Worker Jobs in Pakistan</a> &mdash; the same skills applied to a different client group, and the employment quota behind it.</li>
    <li><a href="/blog/healthcare-administrator-jobs-in-pakistan">Healthcare Administrator Jobs in Pakistan</a> &mdash; the non-clinical side of the same sector.</li>
    <li><a href="/blog/residential-care-worker-jobs-in-pakistan">Residential Care Worker Jobs in Pakistan</a> &mdash; the same care in a home the resident lives in, on shifts that run through the night.</li>
</ul>

<h2>Official sources</h2>

<ul>
    <li><a href="https://navttc.gov.pk/qualifications/domestic-worker-3-elderly-care-giver/" target="_blank" rel="noopener nofollow">NAVTTC Elderly Care Giver qualification, code 090921ECG</a></li>
    <li><a href="https://carenest.pk/careers.html" target="_blank" rel="noopener nofollow">Care Nest recruitment, Islamabad and Rawalpindi</a></li>
    <li><a href="https://saharahomehealthcare.com/jobs.php" target="_blank" rel="noopener nofollow">Sahara Home Healthcare jobs, Karachi</a></li>
</ul>

<p><strong>Note:</strong> pay, shift patterns and training requirements are set by each employer, not by JobGader. Confirm the details on the employer's own page before applying.</p>
HTML;
    }
}
