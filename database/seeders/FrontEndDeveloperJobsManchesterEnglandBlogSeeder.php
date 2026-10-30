<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * "Front-End Developer Jobs in Manchester, England: A Beginner Guide"
 *
 * Checked on 17 October 2026 against the official pages cited in the post.
 * What changed against the brief:
 *
 * 1. The brief has ten apply buttons to job board searches, Tecmark, the government apprenticeship service and a Booking.com page. No buttons are used. The big job boards, Tecmark and Booking.com are named in prose only; the official links are the National Careers Service, the GOV.UK Government Digital and Data role page, Find an apprenticeship and MDN.
 * 2. National Careers Service was checked: starter salary 27,000 pounds, experienced 60,000 pounds, 37 to 39 hours a week; routes are university, college (including a T Level in Digital Software Development), apprenticeship from Level 3 to Levels 6 and 7 degree apprenticeships, and working up as a junior. The page does not name HTML, CSS or JavaScript, so those come from the Government Digital and Data role page, which does.
 * 3. The brief's Tecmark example is dropped: the brief itself says the page has older wording and is not a confirmed vacancy. Booking.com's page was read: a Manchester four-year software engineering degree apprenticeship, with the line "We are not accepting applications for 2026". The guide states that and does not call it open.
 * 4. The brief says the Government Digital and Data page describes work with user needs and web standards; the page says a frontend developer "designs, builds and improves website software that meets user needs" and builds user interfaces with accessible code and progressive enhancement. The guide uses that wording.
 * 5. Eight FAQs, eight People Also Search For entries and a Pakistan note are added; the brief had four FAQs.
 *
 * The job listing is in GuideJobListingsSeeder.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * this row.
 */
class FrontEndDeveloperJobsManchesterEnglandBlogSeeder extends Seeder
{
    public const SLUG = 'front-end-developer-jobs-manchester-england';

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
                'title' => 'Front-End Developer Jobs in Manchester, England: A Beginner Guide',
                'excerpt' => 'A beginner guide to front-end developer jobs in Manchester: the skills to learn, the entry routes the National Careers Service lists, apprenticeship search and a portfolio plan.',
                'content' => $content,
                'featured_image' => 'blogs/front-end-developer-jobs-manchester-england.jpg',
                'tags' => 'front end developer jobs manchester, junior frontend developer manchester, beginner web developer jobs england, frontend apprenticeships manchester, national careers service web developer, find an apprenticeship, html css javascript, web developer portfolio',
                'meta_title' => 'Front-End Developer Jobs in Manchester: Beginner Guide',
                'meta_description' => 'Front-end developer jobs in Manchester for beginners: skills to learn, entry routes from the National Careers Service, apprenticeships and portfolio advice.',
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
<p><strong>To start applying for front-end developer jobs in Manchester, England, learn HTML, CSS and JavaScript, build a small portfolio and look for junior, trainee or apprenticeship roles.</strong> A degree is one route, but the National Careers Service also lists college, apprenticeship and working-up routes into web development. Employers set their own requirements, so check each vacancy, confirm the workplace and show what you can build instead of relying on course certificates alone.</p>

<h2>What Does a Front-End Developer Do?</h2>

<p>Front-end developers build the parts of websites and applications that people use. The UK Government's <a href="https://understand-digital-data-roles-skills.service.gov.uk/role/frontend-developer/" rel="noopener">Digital and Data role framework</a> says a frontend developer "designs, builds and improves website software that meets user needs", and it describes accessible code, progressive enhancement and a working knowledge of HTML, CSS and JavaScript.</p>

<p>For a beginner that means more than making a page look good. You need to think about navigation, screen sizes, keyboard access, forms and what happens when something fails. Read each vacancy carefully, because some agency roles mix front-end work with WordPress or content management, while product teams may stress JavaScript applications.</p>

<h2>Learn the Core Skills First</h2>

<p>Start with semantic HTML and CSS layouts. Practise headings, links, labelled form controls, flexible grids and readable mobile pages, and learn to find and fix layout problems with browser developer tools.</p>

<p>Then practise JavaScript fundamentals: variables, functions, arrays, events and working with data. Build interfaces with loading, empty and error states, not only the successful case. Use Git to record changes, and keep projects tidy so another developer can follow them. MDN's <a href="https://developer.mozilla.org/en-US/docs/Learn_web_development" rel="noopener">Learn web development</a> curriculum is a structured, free resource for these foundations.</p>

<h2>Add Frameworks When They Match Your Target</h2>

<p>Compare suitable Manchester adverts before you choose a framework. If the roles you want keep asking for React, practise components, state and data flow after the JavaScript basics. Other employers use different frameworks or content management systems.</p>

<p>Avoid filling your CV with tools you have only tried briefly. A working application that you can explain is better evidence than a long list. For agency roles, show that you can turn a design into a responsive page. For application roles, show interactions, data handling and clear behaviour when a request fails.</p>

<h2>Entry Routes for Beginners</h2>

<p>The <a href="https://nationalcareers.service.gov.uk/job-profiles/web-developer" rel="noopener">National Careers Service</a> lists these routes into web development: university (a foundation degree, HND or degree in a related subject), college (courses in computing, or a T Level in Digital Software Development), an apprenticeship (from a Level 3 advanced apprenticeship up to Level 6 and 7 degree apprenticeships) and working your way up as a junior developer.</p>

<p>Entry criteria depend on the programme, and the requirements for starting a course are not the same as the requirements for a junior job. If you are changing career, connect your earlier work to communication, organisation and problem-solving, show your technical preparation through projects and describe past jobs accurately. Do not present personal practice as paid commercial development.</p>

<h2>Where to Search in Manchester</h2>

<p>The government's <a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">Find an apprenticeship</a> service lets you search apprenticeships by what you want to do and where. Search software, web and digital roles near Manchester, and read each one's entry requirements and technology focus: a software engineering apprenticeship is not necessarily about front-end work.</p>

<p>Large job boards have junior front-end searches for Manchester, but their results can include nearby occupations and roles above beginner level, so read the description instead of trusting the filter. Try "junior frontend", "trainee web developer" and "graduate developer", and check whether a graduate scheme needs a degree or a particular graduation date. Check agency and technology employers' own career pages too, and save your searches with the employer, title, requirements and date.</p>

<h2>Employer Apprenticeships: Check the Intake</h2>

<p>Booking.com describes a four-year degree apprenticeship in software engineering at its Manchester hub, but its page says "We are not accepting applications for 2026". Do not treat it as an open vacancy. Watch for future announcements, record application windows and prepare your documents early, and do not assume a past intake's dates or rules will repeat.</p>

<h2>Manchester, Salford and Hybrid Work</h2>

<p>A Manchester search can include Salford, Stockport and other parts of Greater Manchester. Check the real office address and attendance pattern before you work out your commute.</p>

<p>Hybrid does not mean fully remote. Ask how many office days are expected, whether onboarding differs from the normal schedule and whether you will travel to clients. If you need sponsorship or have location limits, check the employer's own rules, because a remote label does not show a role can employ you from anywhere.</p>

<h2>Salary Expectations</h2>

<p>The National Careers Service gives web developer pay of £27,000 for starters up to £60,000 for experienced workers, and typical hours of 37 to 39 a week. That is national career guidance for web development, not a Manchester-only survey or a promised junior offer.</p>

<p>Use the pay in a live vacancy to compare roles. Apprenticeship pay, junior employment and freelance fees work differently. Ask about hours, probation, pension, training and progression, compare travel costs and equipment alongside the salary, and do not assume the top of an advertised range is open to beginners.</p>

<h2>Build a Portfolio That Shows You Are Ready</h2>

<p>Make three focused examples: a responsive business website, an interactive application and an improvement to an existing personal project. These are suggested exercises, not employer requirements.</p>

<p>Publish a working demonstration and readable code where it helps, and explain the purpose, your part, your decisions and the limits. Add set-up instructions and remove passwords and private data. Check keyboard navigation, mobile layouts, form feedback and broken links, and say how you tested. If you followed a tutorial or used an AI assistant, explain what you changed and be ready to discuss the code on your own.</p>

<h2>Apply and Prepare for Interviews</h2>

<p>Tailor your CV to the essential requirements, include portfolio links and follow the employer's instructions. Answer screening questions honestly.</p>

<p>Practise explaining a layout bug, a JavaScript interaction and a decision you made in a project. Explain your approach instead of guessing when you do not know an answer. Before an assessment, clarify its scope, deadline and allowed tools, and ask about mentoring, code review and what you will do in your first months.</p>

<h2>Frequently Asked Questions</h2>

<h3>Do I need a degree to be a front-end developer?</h3>
<p>Not for every role. The National Careers Service lists college, apprenticeship and working-up routes as well as university, so check each vacancy's essential qualifications and show relevant skills through honest project evidence.</p>

<h3>Is React compulsory?</h3>
<p>Only where the employer requires it. Build strong HTML, CSS and JavaScript foundations, then learn the tools your target vacancies ask for.</p>

<h3>What does a front-end developer earn in the UK?</h3>
<p>The National Careers Service gives £27,000 for starters up to £60,000 for experienced workers, as national guidance.</p>

<h3>Do I need to live in Manchester?</h3>
<p>Check each advert. Some roles are hybrid with set office days, and a remote label may still have location limits.</p>

<h2>A Note for Applicants in Pakistan</h2>

<p>You need the right to work in the United Kingdom before you can take a job there, and an advertised developer role does not sponsor a visa on its own. Never pay an agent for an interview or a "guaranteed" offer, and read <a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">how to spot a fake overseas job offer</a> before you send money or documents.</p>

<h2>Bottom Line</h2>

<p>Build dependable front-end skills, make a clear portfolio and target suitable Manchester roles and apprenticeships. Check the intake, the workplace and the requirements before you apply or accept an offer.</p>

<h2>Sources</h2>

<ol>
<li><a href="https://nationalcareers.service.gov.uk/job-profiles/web-developer" rel="noopener">National Careers Service: Web developer</a></li>
<li><a href="https://understand-digital-data-roles-skills.service.gov.uk/role/frontend-developer/" rel="noopener">Government Digital and Data: Frontend developer role</a></li>
<li><a href="https://www.findapprenticeship.service.gov.uk/" rel="noopener">Find an apprenticeship (GOV.UK)</a></li>
<li><a href="https://developer.mozilla.org/en-US/docs/Learn_web_development" rel="noopener">MDN: Learn web development</a></li>
</ol>

<h2>More Job Guides</h2>

<p>Related guides:</p>

<ul>
    <li><a href="/blog/junior-web-developer-jobs-chicago-no-degree">Junior Web Developer Jobs in Chicago With No Degree</a>: a degree-light route into web development.</li>
    <li><a href="/blog/software-developer-jobs-seattle-washington">Software Developer Jobs in Seattle, Washington</a>: pay and entry requirements in a US tech city.</li>
    <li><a href="/blog/how-to-spot-a-fake-overseas-job-offer-2026">How to Spot a Fake Overseas Job Offer</a>: check this before you send money or documents.</li>
</ul>

<p><em>Reviewed on 17 October 2026.</em></p>

HTML;
    }
}
