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

/**
 * "Landscaper and Gardener Jobs in Canada and the UAE".
 *
 * The brief was already built on official sources, so it is kept close. What
 * changed:
 *
 * 1. Three Indeed "Apply Now" buttons for the UAE, repeated Job Bank and Desert
 *    Group links, and a Davey Tree search page. The guide links only to our own
 *    pages; the job listings carry one official link each (Job Bank, Desert
 *    Group careers).
 *
 * 2. The Davey Tree links are dropped: Davey is an arboriculture employer, and
 *    the guide is about landscaping and gardening roles.
 *
 * 3. "Job Bank information for overseas workers" was a generic landing page
 *    and is not linked.
 *
 * No pay figures are published; the brief had none.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class LandscaperGardenerJobsCanadaUaeBlogSeeder extends Seeder
{
    public const SLUG = 'landscaper-and-gardener-jobs-in-canada-and-the-uae';

    private const CANADA_APPLY_URL = 'https://www.jobbank.gc.ca/jobsearch/jobsearch?searchstring=landscaping';

    private const UAE_APPLY_URL = 'https://desertgroup.ae/career/';

    public function run(): void
    {
        $this->seedBlogPost();
        $this->seedJobs();
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

        Blog::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => 'Landscaper and Gardener Jobs in Canada and the UAE',
                'excerpt' => 'A landscaper job abroad needs a work permit first. What Canada\'s Job Bank and an LMIA posting really mean, what UAE gardener employers ask for, and which questions to put to an employer about pay, housing and heat before you travel.',
                'content' => $content,
                'featured_image' => 'blogs/landscaper-gardener-jobs-canada-uae.jpg',
                'tags' => 'landscaping jobs canada, gardener jobs uae, landscape labourer jobs, grounds maintenance jobs, irrigation technician jobs, lmia requested job bank, gardener work permit uae, overseas landscaping work',
                'meta_title' => 'Landscaper and Gardener Jobs in Canada and the UAE',
                'meta_description' => 'Landscaping jobs in Canada and gardener jobs in the UAE: duties, experience, work permit steps and how to check an offer before you travel.',
                'reading_time' => max(1, (int) ceil(str_word_count(strip_tags($content)) / 200)),
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
            ]
        );
    }

    private function seedJobs(): void
    {
        $listings = [
            [
                'advertiser' => ['Canadian Landscaping Employers (Aggregated)', 'canada-landscaping-aggregated'],
                'location' => ['Canada', 'Canada'],
                'position' => 'Landscape Labourer — Canadian Landscaping Employers (LMIA Requested Postings)',
                'apply' => self::CANADA_APPLY_URL,
                'hours' => 'Seasonal, outdoors in all weather',
                'language' => 'English',
                'description' => $this->canadaJobDescription(),
                'meta' => 'Landscape labourer and grounds maintenance roles on Canada\'s Job Bank. The employer\'s LMIA is not a guarantee of a work permit.',
                'keywords' => 'landscaping jobs canada, landscape labourer, grounds maintenance jobs canada, lmia requested',
                'category' => ['general-labour', 'General Labour'],
            ],
            [
                'advertiser' => ['UAE Landscaping Employers (Aggregated)', 'uae-landscaping-aggregated'],
                'location' => ['United Arab Emirates', 'United Arab Emirates'],
                'position' => 'Gardener and Landscape Maintenance Worker — UAE Landscaping Companies',
                'apply' => self::UAE_APPLY_URL,
                'hours' => 'Outdoor shifts; heat rules apply in summer',
                'language' => 'English',
                'description' => $this->uaeJobDescription(),
                'meta' => 'Gardener, irrigation and landscape maintenance roles with UAE landscaping companies. The employer arranges the work permit and may not charge you recruitment costs.',
                'keywords' => 'gardener jobs uae, landscaping jobs dubai, irrigation jobs uae, landscape maintenance uae',
                'category' => ['general-labour', 'General Labour'],
            ],
        ];

        foreach ($listings as $listing) {
            $category = Category::firstOrCreate(
                ['slug' => $listing['category'][0]],
                ['name' => $listing['category'][1]]
            );

            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $location = Location::firstOrCreate(
                ['name' => $listing['location'][0]],
                ['area' => 'Nationwide', 'country' => $listing['location'][1]]
            );

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'description' => $listing['description'],
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => $listing['hours'],
                    'language' => $listing['language'],
                    // Pay depends on the employer and the contract; this site
                    // does not republish job-board rates.
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['apply'],
                    'meta_description' => $listing['meta'],
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function canadaJobDescription(): string
    {
        return <<<'JOBHTML'
<p>Canadian landscaping and grounds maintenance companies post labourer, crew and maintenance vacancies on Job Bank.</p>

<h3>Requirements</h3>
<ul>
    <li>Some secondary education may be required for landscape labourers; employers add criteria such as driving eligibility, equipment experience and lifting ability</li>
    <li>Provincial licensing may be needed to apply certain chemicals, including pesticides and herbicides</li>
    <li>An employer-specific work permit usually needs a job offer and employer steps first, and an "LMIA requested" posting is not an approval</li>
</ul>

<p><strong>Note:</strong> permit and licensing rules are set by the Government of Canada and the provinces, not by JobGader. Apply only where your circumstances match the posting.</p>
JOBHTML;
    }

    private function uaeJobDescription(): string
    {
        return <<<'JOBHTML'
<p>UAE landscaping contractors and property managers recruit gardeners and maintenance staff for lawns, planting beds, irrigation and outdoor areas. The link goes to the official careers page of Desert Group, one UAE landscaping employer.</p>

<h3>Requirements</h3>
<ul>
    <li>A work permit and residence visa arranged by the employer. You cannot work on a visit or tourist visa</li>
    <li>Practical experience with the tasks in the vacancy: lawn care, pruning, watering and planting</li>
    <li>Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs</li>
</ul>

<p><strong>Note:</strong> a portal may have no suitable opening when you visit. Work permit and safety rules are set by the UAE authorities, not by JobGader. Confirm housing and transport in writing.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>You can apply for landscaper and gardener jobs overseas through employer career pages and Canada's Job Bank. Canada offers grounds maintenance, landscape labourer and related positions, and UAE searches include gardening, irrigation and landscape maintenance roles. Some employers consider beginners, while others need equipment or horticultural experience. Before you accept an offer, confirm the real duties, the written pay, the accommodation arrangements and your permission to work. A job advert does not include sponsorship automatically, and an offer does not guarantee immigration approval.</p>

<h2>Which Landscaping Role Should You Target?</h2>

<div style="overflow-x:auto;margin:24px 0;">
<table style="width:100%;border-collapse:collapse;font-size:15px;">
    <thead>
        <tr style="background:#1b3a6b;color:#fff;">
            <th style="padding:10px;text-align:left;">Role</th>
            <th style="padding:10px;text-align:left;">Typical responsibilities</th>
            <th style="padding:10px;text-align:left;">Useful experience</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Landscape labourer</strong></td><td style="padding:10px;">Preparing sites, moving materials, planting and cleanup</td><td style="padding:10px;">Outdoor work and safe manual handling</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Gardener</strong></td><td style="padding:10px;">Watering, pruning, weeding and maintaining plants</td><td style="padding:10px;">Plant care and routine garden maintenance</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Grounds maintenance worker</strong></td><td style="padding:10px;">Caring for lawns, beds and outdoor spaces</td><td style="padding:10px;">Mowers, trimming equipment and maintenance routines</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Irrigation technician</strong></td><td style="padding:10px;">Checking and repairing watering systems</td><td style="padding:10px;">Installation and troubleshooting skills</td></tr>
        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>Crew leader</strong></td><td style="padding:10px;">Coordinating workers, schedules and site standards</td><td style="padding:10px;">Practical experience and supervision</td></tr>
    </tbody>
</table>
</div>

<p>Choose a title that matches your skills. Landscape architecture, arboriculture and specialist technical work can involve different qualifications and responsibilities.</p>

<h2>What Do Landscaping Jobs in Canada Require?</h2>

<p>Job Bank's landscape labourer profile says some secondary education may be required. It also notes that provincial licensing may be needed to apply certain chemicals, including pesticides and herbicides, so check the rules where you plan to work. Do not assume every labourer needs a horticulture degree. Employers add their own requirements, such as driving eligibility, equipment experience, lifting ability or earlier maintenance work, and supervisor and specialist positions generally need stronger evidence of competence.</p>

<p>Explain which tasks you can perform safely, and separate your experience with mowing, planting, irrigation, paving and tree work instead of writing "landscaping" with no detail. Never claim permission to apply chemicals or operate specialised equipment unless you meet the applicable requirements.</p>

<p>An employer-specific Canadian work permit usually involves a job offer and employer steps before you apply. The employer must establish whether an LMIA is required or an exemption applies, and where one is required the employer gives you the documents from a positive LMIA. A Job Bank advert marked <strong>"LMIA requested"</strong> is not proof of approval. If you already hold an open work permit, check your own conditions, and never say you are authorised to work in Canada if you have not obtained the permission. Landscaping should not automatically be treated as agricultural programme work: the duties and the immigration category matter, even when both jobs involve plants and outdoor labour. See <a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> for the wider picture.</p>

<p>Use Job Bank to compare locations, duties, wages and who may apply. Overseas applicants should check whether the employer considers candidates without current Canadian work permission, and read the whole advert instead of relying on the title, because "landscaping" searches also return technical, supervisory and entry positions.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/landscaper-gardener-jobs-canada-uae-inline.jpg"
         alt="A smiling landscaper planting a shrub in a garden bed, with workers trimming a hedge, mowing a lawn and clearing leaves in smaller pictures beside him"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>What Do Gardener Jobs in the UAE Require?</h2>

<p>Gardener duties can include lawn care, pruning, watering, planting, cleaning outdoor areas and reporting plant problems. Landscape contractors may also advertise irrigation, nursery and maintenance roles. Show practical knowledge of the tasks in the vacancy: if you have maintained gardens, describe the tools you used, the types of work and your responsibilities. Ask whether the job involves private gardens, commercial properties, public grounds or several project sites, because travel and supervision differ. Confirm whether driving is required and whether your licence meets local rules.</p>

<p>Private sector employment in the UAE requires a valid work permit, and the employer completes the authorisation steps. A visit or tourist visa does not allow you to work. Under Article 6 of Federal Decree-Law No. 33 of 2021 the employer may not charge you recruitment and employment costs, directly or indirectly. For a household gardener, confirm which employment framework applies, because the rules and contract process can differ from company employment. Keep copies of the offer and contract, and check that pay and duties stay consistent.</p>

<p>Verify the company and the written offer through official channels before sharing sensitive documents. Desert Group is an example of a UAE landscaping employer with its own official careers page, although a suitable opening may not be available when you visit. Check experience, location, hours, housing, transport and employment conditions separately, and do not assume every advert includes accommodation or overseas recruitment.</p>

<h2>Pay, Working Conditions and Safety</h2>

<p>There is no single salary for all landscaping roles. Compare the written hourly or monthly rate, overtime, contract length, deductions and living costs, and ask whether Canadian work is seasonal and what happens when the contract ends. For UAE roles, confirm accommodation, meals, transport and allowances in writing, and ask how the employer handles outdoor heat, rest, drinking water and protective equipment. Official workplace guidance includes restrictions on some outdoor work during summer peak hours.</p>

<p>Ask about machinery training and chemical handling procedures, report unsafe equipment, and ask for instruction before you perform unfamiliar tasks. A strong application shows careful working habits as well as practical ability.</p>

<h2>Documents and Application Tips</h2>

<p>Prepare a clear CV, your passport, references and relevant training records. Canadian work permit applications use a personalised document checklist, and the documents depend on your route. Describe actual achievements and responsibilities. Photographs of finished work can support an application if you have permission to share them, and you should leave out confidential customer information. Tailor the CV to the vacancy and explain your availability honestly.</p>

<p>Keep a tracker with employer names, vacancy references, submission dates and responses. In interviews, ask who supervises your work, how teams move between sites and what equipment is supplied. Confirm the proposed start date against any immigration steps still outstanding, and keep written answers and contract documents so you can review your commitments before you accept.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="/categories/general-labour" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        Browse General Labour Jobs on JobGader →
    </a>
</div>

<h2>Frequently Asked Questions</h2>

<h3>Can beginners apply for landscaping jobs?</h3>
<p>Some labourer or gardener helper roles accept beginners. Check the training provided and the essential criteria before applying.</p>

<h3>Is a landscaping degree always required?</h3>
<p>No. Basic labourer roles differ from technical or professional positions, but employer and local licensing requirements still apply.</p>

<h3>Does a Canadian job offer guarantee a work permit?</h3>
<p>No. The employer's documents and your own immigration eligibility are assessed separately.</p>

<h3>Can I work in the UAE on a tourist visa?</h3>
<p>No. A visit or tourist visa does not authorise employment.</p>

<h3>Do I need a licence to spray pesticides in Canada?</h3>
<p>Provincial licensing may be needed to apply certain chemicals, including pesticides and herbicides. Check the rules where you will work.</p>

<h3>Is landscaping the same as agricultural work for a Canadian permit?</h3>
<p>Not automatically. The duties and the immigration category decide which route applies.</p>

<h3>Who pays recruitment costs for a UAE gardener job?</h3>
<p>The employer. Under Article 6 of Federal Decree-Law No. 33 of 2021 it may not charge you recruitment and employment costs.</p>

<h3>Is accommodation included in gardener jobs?</h3>
<p>Not always. Ask for housing, meals and transport terms in writing.</p>

<h2>People Also Search For</h2>

<h3>Landscaping jobs Canada</h3>
<p>Search Job Bank and read each advert for who may apply.</p>

<h3>Gardener jobs UAE</h3>
<p>Employer-sponsored work permits; check housing and transport in writing.</p>

<h3>Landscape labourer requirements</h3>
<p>Some secondary education may be required; employers add their own criteria.</p>

<h3>Grounds maintenance jobs</h3>
<p>Lawn, bed and outdoor space care for commercial and public sites.</p>

<h3>Irrigation technician jobs</h3>
<p>Installation and repair of watering systems.</p>

<h3>LMIA requested Job Bank</h3>
<p>The employer has applied to hire a foreign worker; it is not an approval.</p>

<h3>Gardener work permit UAE</h3>
<p>Arranged by the employer; a visit visa does not allow work.</p>

<h3>Seasonal landscaping jobs</h3>
<p>Ask what happens when the season and the contract end.</p>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/jobs-in-canada-for-foreign-workers">Jobs in Canada for Foreign Workers</a> &mdash; the wider Canadian picture.</li>
    <li><a href="/blog/farm-worker-jobs-in-canada">Farm Worker Jobs in Canada</a> &mdash; the agricultural route, which is different from landscaping.</li>
    <li><a href="/blog/fruit-picking-and-farm-jobs-in-australia-canada-and-the-uk">Fruit Picking and Farm Jobs in Australia, Canada and the UK</a> &mdash; seasonal outdoor work abroad.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, immigration or career advice. Information is drawn from Job Bank, the Government of Canada and u.ae, reviewed on 6 October 2026. Recruitment pages do not guarantee openings or sponsorship, and the rules change, so confirm them with the official authority before applying.</p>
HTML;
    }
}
