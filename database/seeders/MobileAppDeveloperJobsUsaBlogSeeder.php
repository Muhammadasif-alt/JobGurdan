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
 * "Mobile App Developer Jobs in USA" — a sector guide rather than one vacancy,
 * so the apply link goes to an Indeed search and the post carries no
 * JobPosting markup.
 *
 * Corrections to the draft:
 *
 * 1. Every salary figure in it came from a single named listing — one React
 *    Native role at $120,000-$145,000. A sample of one is not a market. The
 *    guide anchors on the federal occupation instead.
 *
 * 2. It never says which occupation this is. Mobile developers are counted by
 *    BLS under software developers ($135,980 median, lowest tenth $82,460),
 *    not web developers ($92,650 median, lowest tenth $48,100). The gap in the
 *    floor — $34,360 — is the most useful fact available to someone choosing
 *    between mobile and web, and no draft mentions it.
 *
 * 3. It advises shipping apps to the stores as portfolio proof without saying
 *    what that now costs in time. A personal Google Play account created after
 *    13 November 2023 must run a closed test with 12 testers opted in
 *    continuously for 14 days before it can reach production at all.
 *
 * 4. It treats React Native as interchangeable with native work. The rate
 *    difference sits in whether you can write the native module, not in the
 *    framework.
 *
 * 5. Nothing on work authorisation. The H-1B section carries the same figures
 *    as the sibling guides, plus the 20 September 2026 expiry date built into
 *    Proclamation 10973 itself.
 *
 * Both records use updateOrCreate, so re-running is safe; it will overwrite
 * admin-panel edits to these two rows.
 */
class MobileAppDeveloperJobsUsaBlogSeeder extends Seeder
{
    private const APPLY_URL = 'https://www.indeed.com/q-mobile-app-developer-jobs.html';

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
        $title = 'Mobile App Developer Jobs in USA';

        Blog::updateOrCreate(
            ['slug' => Str::slug($title)],
            [
                'blog_catgories_id' => $category->id,
                'author_id' => $author?->id,
                'author_name' => $author?->name ?? 'Admin',
                'title' => $title,
                'excerpt' => 'What mobile app developer jobs in the USA pay against the $135,980 software developer median, why the floor matters more than the median when choosing between mobile and web, what actually separates an iOS, Android and React Native rate, and the two-week Google Play gate that stands between you and the shipped app employers screen for.',
                'content' => $content,
                'featured_image' => 'blogs/mobile-app-developer-jobs-in-usa.jpg',
                'tags' => 'mobile app developer jobs in usa, ios developer jobs usa, android developer jobs usa, react native developer jobs, remote mobile developer jobs, mobile app developer salary usa, swift developer jobs, kotlin developer jobs',
                'meta_title' => 'Mobile App Developer Jobs in USA',
                'meta_description' => 'Mobile app developer jobs in USA: the $135,980 BLS median, why the $82,460 floor matters more, iOS vs Android vs React Native pay, and the Google Play gate.',
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
            ['name' => 'US Mobile Product & Consumer App Teams (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'us-mobile-app-aggregated']
        );

        $location = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $category = Category::firstOrCreate(
            ['slug' => 'it-software'],
            ['name' => 'IT & Software']
        );

        Job::updateOrCreate(
            [
                'position' => 'Mobile App Developer — iOS, Android and React Native, US Product Teams',
                'advertiser_id' => $advertiser->id,
            ],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => $this->jobDescription(),
                'employment_type' => 'Full-time',
                'job_type' => 'Remote',
                'work_hours' => 'Standard US business hours, with core overlap expected on distributed teams',
                'language' => 'English',
                // BLS counts this work under software developers: under $82,460
                // to over $214,670, so no single range would be honest.
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => self::APPLY_URL,
                'meta_description' => 'iOS, Android and cross-platform mobile development roles with US product, media, health and fintech teams, on-site and remote. Apply through the employer listing.',
                'seo_keywords' => 'mobile app developer jobs in usa, ios developer jobs, android developer jobs, react native developer jobs, remote mobile developer jobs usa',
            ]
        );
    }

    private function jobDescription(): string
    {
        return <<<'JOBHTML'
<p>Product, media, health and fintech teams across the United States hire mobile developers to build and maintain the iOS and Android apps their customers actually use every day. Because the work is reviewable through a pull request and testable through TestFlight or a Play track, a large share of these roles are remote or offer a remote option.</p>

<h3>What the work involves</h3>
<p>Building screens and flows against a design system, integrating REST or GraphQL APIs, and handling the conditions the web rarely has to: no signal, a cold start, a backgrounded process, a device four years old. Beyond the code, you own the release &mdash; versioning, store submission, staged rollout, and watching the crash dashboard afterwards.</p>

<h3>Requirements</h3>
<ul>
    <li>One platform in real depth: Swift and SwiftUI for iOS, or Kotlin and Jetpack Compose for Android</li>
    <li>Or React Native with TypeScript &mdash; and, for the senior band, the ability to write the native module rather than wait for someone else to</li>
    <li>Offline-first data handling, caching and state management, which is where most mobile bugs actually live</li>
    <li>App Store Connect and Google Play Console: submission, versioning, phased release and the review process</li>
    <li>Crash and performance monitoring &mdash; Sentry, Crashlytics or equivalent &mdash; and the habit of acting on it</li>
    <li>At least one app you have shipped to a public store and can point at</li>
</ul>

<h3>How the pay is structured</h3>
<ul>
    <li><strong>Salaried roles</strong> sit against the BLS software developer occupation: a $135,980 median as of May 2025, with the lowest tenth under $82,460 and the highest tenth above $214,670</li>
    <li><strong>Platform depth pays more than platform breadth.</strong> "Ships production Swift" beats "familiar with iOS, Android and Flutter" at the same number of years</li>
    <li><strong>Cross-platform roles</strong> pay at the native band when the role expects native module work, and below it when it does not &mdash; the job description usually tells you which</li>
</ul>

<h3>Before you apply</h3>
<p><strong>Ask who owns the release.</strong> If one person has the signing certificates, the store accounts and the rollout decisions, and that person is leaving, you are inheriting a release process rather than joining one. It is a fair question and the answer is revealing.</p>

<p><strong>Note:</strong> pay, remote policy, stack requirements and any sponsorship decision are set by each employer &mdash; not by JobGader. Confirm the details on the employer's own advertisement before applying.</p>
JOBHTML;
    }

    private function postBody(): string
    {
        return <<<'HTML'
<p>Mobile is the best-paid corner of ordinary application development, and the one where the gap between a portfolio and a hireable portfolio is widest. Anyone can follow a tutorial to a running app on a simulator. Far fewer have an app a stranger can download, and that single difference is what most US mobile hiring screens on. This guide covers what the work pays against the published federal numbers, why the floor matters more than the median if you are choosing between mobile and web, what actually separates an iOS, Android and React Native rate, and the two-week gate now standing between a finished Android app and a public store link.</p>

<div style="text-align:center;margin:32px 0;">
    <a href="https://www.indeed.com/q-mobile-app-developer-jobs.html" target="_blank" rel="noopener nofollow" style="display:inline-flex;align-items:center;gap:10px;background:#1b3a6b;color:#fff;padding:14px 30px;border-radius:999px;font-weight:700;text-decoration:none;">
        📱 Browse Mobile App Developer Jobs in the USA &rarr;
    </a>
</div>

<h2>Mobile App Developer Salary in the USA</h2>

<p>There is no "mobile app developer" line in the federal statistics. The Bureau of Labor Statistics counts this work under <strong>software developers</strong>, and that classification is the single most useful thing on this page, because it tells you which pay band the title belongs to.</p>

<p>BLS puts the <strong>median annual wage for software developers at $135,980 as of May 2025</strong>. The <strong>lowest ten per cent earned under $82,460</strong> and the <strong>highest ten per cent above $214,670</strong>.</p>

<p>Now compare that with web development, which is where most people considering mobile are coming from. Web developers and digital designers sit at a <strong>$92,650 median</strong>, with the <strong>lowest tenth under $48,100</strong>.</p>

<p>The medians are $43,330 apart, and that gap gets quoted often enough. The number almost nobody quotes is the floor: <strong>$82,460 against $48,100, a difference of $34,360.</strong> That is the more important figure, because a median tells you what the middle of a career looks like and a floor tells you what a bad year, a small employer or a weak local market looks like. Web development has a long tail below $50,000. Mobile, as classified, does not. If you are weighing the two, weigh the floors.</p>

<p>Against that occupation median, the usual progression:</p>

<ul>
    <li><strong>Entry level:</strong> roughly $80,000 to $105,000, and note that the bottom decile of the whole occupation is $82,460 &mdash; junior mobile roles genuinely do start higher than junior web roles</li>
    <li><strong>Mid-level, two to five years:</strong> around $110,000 to $145,000, straddling the median</li>
    <li><strong>Senior:</strong> $150,000 to $200,000, with the top decile beginning above $214,670 and total compensation adding bonus and equity on top of base</li>
    <li><strong>Contract:</strong> commonly quoted at $60 to $120+ an hour, which is not salary-comparable until you subtract self-employment tax, your own health insurance and unpaid time between contracts</li>
</ul>

<p>One caution on the numbers you will see elsewhere. Salary aggregators report mobile titles anywhere from $95,000 to $145,000 depending on which job-board sample they scraped, and individual postings prove nothing on their own. A single listing at $120,000 to $145,000 is one employer's budget, not a market rate.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mobile-app-developer-jobs-in-usa-build.jpg"
         alt="A mobile app developer building and testing an iOS and Android application"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>iOS, Android or Cross-Platform: Which One Pays</h2>

<p>The framework question gets asked constantly and answered badly. Here is what the market actually rewards.</p>

<ul>
    <li><strong>iOS, with Swift and SwiftUI.</strong> Historically the best-paid of the three, largely because the iOS user base spends more and the companies that care about that are the ones with budget. Reported averages cluster around the mid-$130,000s.</li>
    <li><strong>Android, with Kotlin and Jetpack Compose.</strong> Reported averages run somewhat below iOS &mdash; commonly cited around $107,000 to $127,000 depending on the sample &mdash; but the spread inside that is wide and the gap narrows considerably at senior level.</li>
    <li><strong>React Native, with TypeScript.</strong> Reported averages sit close to iOS, around the high $120,000s, but this is the least reliable of the three averages because the title covers two very different jobs.</li>
</ul>

<p>That last point is the one worth your attention. <strong>"Write once, run anywhere" is a deployment property, not a hiring one.</strong> The React Native roles that pay at the native band expect you to write the native module &mdash; to drop into Swift or Kotlin when a platform API, a permission flow or a performance problem demands it, without handing off. The React Native roles that pay below it want screens assembled from an existing component library. Both are advertised with the same title. The job description usually tells you which one you are reading: if it names the New Architecture, TurboModules, Hermes or native module development, it is the first kind.</p>

<p>This is also why the talent pool is treated with suspicion. React Native goes onto a great many CVs after one tutorial app, so the filter employers reach for is a public store link. Which brings us to the part the guides skip.</p>

<h2>The Portfolio Gate Nobody Mentions</h2>

<p>Every article on this subject tells you to ship an app and link to it. Almost none of them say what shipping now costs in time and money, and on Android that has changed materially.</p>

<ul>
    <li><strong>Apple charges $99 a year</strong> for the Developer Program. It is a subscription: stop paying and your apps come off the store. Review is usually quick &mdash; most submissions clear in a day or two &mdash; but apps in regulated categories take longer, and rejection is a normal part of the process rather than a verdict on you.</li>
    <li><strong>Google charges $25, once.</strong> That part is cheaper. The rest is not.</li>
</ul>

<p>Since <strong>13 November 2023</strong>, a new <em>personal</em> Google Play developer account cannot publish to production until it has run a closed test with <strong>at least 12 testers opted in continuously for 14 days</strong>. The requirement started at 20 testers and was reduced to 12 in December 2024; the 14 days has never moved. Testers who drop out and rejoin restart the clock, and Google now also checks that the testers actually used the app rather than merely opting in.</p>

<p>Read that as a schedule, not a rule. <strong>Between "my app is finished" and "here is a Play Store link on my CV" there is a minimum of two weeks and twelve real human beings you have to find.</strong> Nobody planning a job search around a portfolio app budgets for that, and it is the most common reason a candidate has GitHub repositories but no store listing &mdash; precisely the evidence the screen is looking for.</p>

<p>Two ways round it. <strong>Organisation accounts registered to a legal business entity are exempt from the tester requirement</strong> &mdash; but they require a D-U-N-S number, which is free and can take up to 30 days to issue, so that is a different delay rather than none. Or start on iOS, where TestFlight and the store have no equivalent gate, and add Android once the test is running.</p>

<p>The practical advice: if you intend to job-hunt with a shipped app, start the Play closed test the week the app builds, not the week you start applying.</p>

<figure style="text-align:center;margin:34px 0;">
    <img src="/public/storage/blogs/mobile-app-developer-jobs-in-usa-testing.jpg"
         alt="A remote mobile developer reviewing an app release and crash reports on a phone and laptop"
         loading="lazy" style="max-width:100%;height:auto;border-radius:14px;display:block;margin:0 auto;">
</figure>

<h2>Remote Mobile App Developer Jobs</h2>

<p>Mobile work is highly remote-compatible and a large share of US postings say so outright. The code lives in a repository, builds run on a service, and distribution to testers is a link rather than a room.</p>

<p>Two things to settle early, and they are the same two that decide every remote software offer. First, <strong>time zone expectations</strong> &mdash; "remote" usually means remote inside a band of overlapping hours, and a four-hour overlap requirement reshapes your day. Second, <strong>whether pay is indexed to your location</strong>, because for the same role that can differ by tens of thousands of dollars. Both are ordinary questions in a first conversation and awkward ones at offer stage.</p>

<p>One mobile-specific point: ask <strong>how you get test devices</strong>. A team that ships to a wide device range and expects you to reproduce bugs on hardware you do not own is setting you up to fail politely. Employers who take mobile seriously either provide devices or reimburse them.</p>

<h2>Skills That Actually Get You Hired</h2>

<ul>
    <li><strong>One platform, properly.</strong> Swift and SwiftUI, or Kotlin and Jetpack Compose. Depth in one interviews far better than a list of four.</li>
    <li><strong>TypeScript</strong> if you are going the React Native route, plus the New Architecture &mdash; Fabric, TurboModules, JSI &mdash; and Hermes.</li>
    <li><strong>Native module development.</strong> The clearest single rate driver in cross-platform work, because it is the thing most React Native developers cannot do.</li>
    <li><strong>Offline-first data.</strong> Caching, sync, conflict handling. Mobile bugs are overwhelmingly state bugs, not layout bugs.</li>
    <li><strong>Release engineering.</strong> Store submission, versioning, staged rollout, over-the-air updates. Owning a release end to end is a senior signal.</li>
    <li><strong>Crash and performance monitoring.</strong> Sentry or Crashlytics, and being able to say what you changed as a result.</li>
    <li><strong>A shipped app with a public link.</strong> Worth more than every bullet above combined at the junior end.</li>
</ul>

<h2>Work Authorisation: What Applies If You Need Sponsorship</h2>

<p>For readers who do not already hold US work authorisation, this &mdash; not the portfolio &mdash; is usually the binding constraint.</p>

<p>The main route for a specialty occupation is the <strong>H-1B</strong>, capped at <strong>65,000 visas a year plus 20,000 reserved for holders of a US advanced degree</strong>. Demand exceeds supply, so selection runs by lottery, and USCIS has announced that both caps were reached for fiscal year 2027. A cap-subject petition therefore depends on being selected before it depends on anything about you.</p>

<p>On the <strong>$100,000 payment</strong> introduced by proclamation on 19 September 2025: the US District Court for the District of Massachusetts <strong>vacated the guidance implementing it on 8 June 2026</strong>, holding that it functioned as a tax the executive had no authority to impose, and the First Circuit <strong>denied the government's motion to stay that order on 24 July 2026</strong>. It is <strong>not currently being enforced</strong>. Two things to keep in view: the appeal is still live and the Supreme Court could be asked for emergency relief, and separately <strong>the proclamation carries a twelve-month term that expires on 20 September 2026 unless it is extended</strong>.</p>

<p>That is an unusually fast-moving position, so check current USCIS guidance before making any decision, and be sceptical of anyone &mdash; recruiter, agent or consultant &mdash; who quotes the fee as settled in either direction. Nobody should be charging you for that answer.</p>

<h2>Frequently Asked Questions</h2>

<h3>How much do mobile app developers make in the USA?</h3>
<p>BLS counts mobile developers under software developers, at a $135,980 median as of May 2025, with the lowest ten per cent under $82,460 and the highest ten per cent above $214,670, before bonus and equity.</p>

<h3>Does mobile pay more than web development?</h3>
<p>Yes, and the floor is the bigger part of the story. The medians are $43,330 apart &mdash; $135,980 against $92,650 &mdash; but the bottom decile is $82,460 against $48,100. Web development has a long tail below $50,000; mobile, as classified, does not.</p>

<h3>Should I learn iOS, Android or React Native?</h3>
<p>iOS reports the highest averages, Android somewhat lower with a narrowing gap at senior level, and React Native close to iOS but covering two different jobs. Depth in one platform beats familiarity with three. If you pick React Native, learn to write native modules &mdash; that is what separates the two React Native pay bands.</p>

<h3>Is it hard to publish an app on Google Play now?</h3>
<p>For a personal account created after 13 November 2023, yes. You must run a closed test with at least 12 testers opted in continuously for 14 days before you can apply for production access, and Google checks the testers genuinely used the app. Organisation accounts are exempt but need a D-U-N-S number, which can take up to 30 days.</p>

<h3>What does it cost to publish an app?</h3>
<p>Apple charges $99 a year and your apps are removed if you stop paying. Google charges $25 once. The real cost on Android is the two-week testing period, not the fee.</p>

<h3>Do I need a shipped app to get hired?</h3>
<p>Not formally, but it is the most effective filter employers have, precisely because React Native and Flutter appear on so many CVs after a tutorial. A public App Store or Play link answers the question a repository cannot.</p>

<h3>Are mobile developer jobs growing?</h3>
<p>BLS projects 10 per cent growth for software developers, quality assurance analysts and testers from 2025 to 2035 &mdash; much faster than the 3 per cent average across all occupations &mdash; with about 106,100 openings a year across the decade.</p>

<h3>Is the $100,000 H-1B fee still in effect?</h3>
<p>Not currently. A Massachusetts federal court vacated the implementing guidance on 8 June 2026 and the First Circuit denied a stay on 24 July 2026. The appeal is still live, and the proclamation's own twelve-month term runs out on 20 September 2026 unless extended. Verify the current position with USCIS before relying on it.</p>

<h2>People Also Search For</h2>

<h3>iOS developer jobs USA</h3>
<p>Swift and SwiftUI, and the best-reported averages of the three routes. Regulated categories mean longer App Store review, so build the schedule in.</p>

<h3>Android developer jobs USA</h3>
<p>Kotlin and Jetpack Compose. Reported averages run below iOS, but the spread is wide and the gap closes at senior level.</p>

<h3>React Native developer jobs</h3>
<p>Two pay bands under one title. Native module work, the New Architecture and Hermes are what move you into the higher one.</p>

<h3>Remote mobile app developer jobs</h3>
<p>Widely available. Clarify the required hours of overlap, whether salary is indexed to your location, and who pays for test devices.</p>

<h3>Entry level mobile developer salary</h3>
<p>Roughly $80,000 to $105,000, genuinely higher than the junior web band because the whole occupation's bottom decile sits at $82,460.</p>

<h3>Mobile app developer vs web developer salary</h3>
<p>$135,980 against $92,650 at the median, and $82,460 against $48,100 at the tenth percentile. The floor is the more decision-relevant number.</p>

<h3>Flutter developer jobs USA</h3>
<p>A smaller share of US postings than React Native, concentrated in agencies and in teams that adopted it early. The same native-depth rule applies.</p>

<h3>Mobile developer H1B sponsorship</h3>
<p>Capped at 65,000 plus 20,000 for US advanced degree holders, allocated by lottery, with FY2027 caps already reached. Check current USCIS guidance on the contested $100,000 payment.</p>

<h2>More Job Guides</h2>

<p>Looking across the rest of the US development market? These cover it:</p>

<ul>
    <li><a href="/blog/software-developer-jobs-in-usa">Software Developer Jobs in USA</a> &mdash; the occupation mobile work is actually counted under, in full.</li>
    <li><a href="/blog/web-developer-jobs-in-usa">Web Developer Jobs in USA</a> &mdash; the band below, and why its floor is $34,360 lower.</li>
    <li><a href="/blog/react-developer-jobs-in-usa">React Developer Jobs in USA</a> &mdash; the library React Native is built on, and which React work is commodity-priced.</li>
    <li><a href="/blog/front-end-developer-jobs-in-usa">Front End Developer Jobs in USA</a> &mdash; the closest web equivalent, and the two measurable skills that move you up its band.</li>
    <li><a href="/blog/full-stack-developer-jobs-in-usa">Full Stack Developer Jobs in USA</a> &mdash; which of two very different pay bands that title is hiding.</li>
    <li><a href="/blog/java-developer-jobs-in-usa">Java Developer Jobs in USA</a> &mdash; the language behind a great deal of Android's history and most of its backends.</li>
    <li><a href="/blog/python-developer-jobs-in-usa">Python Developer Jobs in USA</a> &mdash; the same title across three occupations, from $92,650 to $135,980.</li>
</ul>

<p style="font-size:14px;color:#6b7280;font-style:italic;border-top:1px solid #e5e7eb;padding-top:18px;margin-top:32px;">This article is for general informational purposes and is not legal, tax or immigration advice. Wage data, employment projections, store policies and immigration rules change &mdash; and the H-1B payment described above is subject to ongoing litigation and to a proclamation term expiring in September 2026. Confirm the current position with the Bureau of Labor Statistics, USCIS, the Apple and Google developer documentation, and the employer's own advertisement before applying or paying any fee.</p>
HTML;
    }
}
