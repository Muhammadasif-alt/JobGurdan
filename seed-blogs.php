<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

$authorId = User::value('id') ?? 4;

// Ensure categories exist
$categories = [
    'career-advice'    => 'Career Advice',
    'industry-insights'=> 'Industry Insights',
];
foreach ($categories as $slug => $name) {
    BlogCatgories::firstOrCreate(
        ['slug' => $slug],
        ['name' => $name, 'description' => $name]
    );
}

$catCareer    = BlogCatgories::where('slug','career-advice')->value('id');
$catInsights  = BlogCatgories::where('slug','industry-insights')->value('id');
$catTech      = BlogCatgories::where('slug','technology')->value('id') ?? $catCareer;

// Theme image rotation (relative path that resolves out of /public/storage/ back to /public/user/images/)
$images = [
    '../user/images/blog-compact-post-01.jpg',
    '../user/images/blog-compact-post-02.jpg',
    '../user/images/blog-compact-post-03.jpg',
    '../user/images/blog-compact-post-04.jpg',
    '../user/images/blog-compact-post-05.jpg',
    '../user/images/blog-single-post-01.jpg',
];

$blogs = [
    [
        'title' => '10 Resume Tips That Will Get You Hired in 2026',
        'category' => $catCareer,
        'excerpt' => 'A modern resume is more than a list of jobs — it is a story. Here are the strategies hiring managers in the U.S. actually pay attention to.',
        'content' => "Your resume is the first impression a hiring manager gets, and in 2026 the bar has never been higher. Recruiters now spend an average of 7.4 seconds scanning a CV before deciding whether to read more.\n\n1. Lead with measurable achievements, not duties.\nInstead of writing \"managed a team,\" write \"led a team of 8 that increased revenue by 23% in 12 months.\" Numbers anchor your impact.\n\n2. Tailor every application.\nGeneric resumes get filtered out by ATS software. Mirror the language of the job description, especially the required skills.\n\n3. Keep it to one page if you have under 10 years of experience.\nClarity beats volume. Anything past 10 years can move into an addendum or LinkedIn profile.\n\n4. Use a clean, single-column layout.\nFancy multi-column designs often break in applicant tracking systems. Stick to professional, ATS-friendly formats.\n\n5. Add a short summary at the top.\nThree lines that capture who you are, what you bring, and what you are looking for. This is your elevator pitch.\n\n6. Quantify everything you can.\nDollars saved, percentages improved, hours reduced, customers added — concrete metrics make you stand out.\n\n7. Drop the objective statement.\nIt is outdated. Replace it with a value-focused summary or a portfolio link.\n\n8. Highlight remote and hybrid experience.\nIn 2026, employers want to know you can perform across distributed environments.\n\n9. Proofread three times.\nA single typo can disqualify you. Read it backwards, run it through a grammar tool, and ask a friend to review.\n\n10. Save as PDF unless told otherwise.\nThis preserves formatting across systems.\n\nA strong resume opens doors. Pair it with a tailored cover letter and a polished LinkedIn profile, and you give yourself the best possible shot at landing the role.",
    ],
    [
        'title' => 'How to Ace Your Next Job Interview: A Step-by-Step Guide',
        'category' => $catCareer,
        'excerpt' => 'Interviews are won in preparation, not in the room. Here is a complete framework for showing up confident and ready.',
        'content' => "Most candidates lose interviews not because they lack skills, but because they fail to prepare in the right way. The good news: preparation is a skill anyone can master.\n\nStep 1 — Research the company deeply.\nGo beyond the About page. Read recent press releases, study their products, and follow their leadership on LinkedIn. Be ready to answer \"Why us?\" with a specific, well-informed answer.\n\nStep 2 — Master the STAR method.\nFor every behavioral question, frame your story as Situation, Task, Action, Result. Practice 6 to 8 stories that cover leadership, conflict, success, and failure.\n\nStep 3 — Anticipate the hard questions.\n\"Tell me about yourself,\" \"What is your weakness,\" and \"Why are you leaving your current role\" trip up most candidates. Rehearse natural, honest answers.\n\nStep 4 — Prepare smart questions to ask.\nInterviewers remember candidates who are curious. Ask about team dynamics, success metrics for the role, or the biggest challenges the team faces.\n\nStep 5 — Dress one level up.\nIf the office is casual, wear smart-casual. If business, wear business formal. Always be the most polished person in the room.\n\nStep 6 — Plan logistics ahead.\nKnow the route or test the video link 30 minutes early. Tech failures and traffic are the easiest mistakes to avoid.\n\nStep 7 — Send a thank-you note within 24 hours.\nEmail every interviewer separately, reference something specific from the conversation, and reaffirm your interest.\n\nStep 8 — Reflect after every interview.\nWrite down what went well, what felt awkward, and what to improve. This compounds across multiple interviews.\n\nThe candidate who prepares deliberately almost always wins over the more talented but unprepared one. Treat every interview like a high-stakes meeting — because it is.",
    ],
    [
        'title' => 'The Top 10 Highest-Paying Jobs in the USA Right Now',
        'category' => $catInsights,
        'excerpt' => 'From physicians to AI engineers, here are the roles commanding the strongest salaries across the U.S. job market today.',
        'content' => "If you are mapping your career or planning a switch, salary data is one of the most useful signals. Based on the latest U.S. Bureau of Labor Statistics figures and major job board data, here are the top-paying roles in 2026.\n\n1. Anesthesiologist — average $400,000+\nMedical specialists remain at the top of the income ladder. Years of training, but consistent demand.\n\n2. Surgeon — $350,000+\nGeneral and specialty surgeons across hospitals and private practices.\n\n3. Psychiatrist — $260,000+\nDemand has surged post-2020 as mental health became a national priority.\n\n4. Software Engineering Manager — $230,000+\nTech leadership roles, especially at FAANG and growth-stage startups.\n\n5. Petroleum Engineer — $210,000+\nDriven by ongoing energy sector investment.\n\n6. Machine Learning Engineer — $200,000+\nThe AI boom has made this one of the fastest-growing high-comp roles.\n\n7. Data Science Director — $195,000+\nBusinesses competing for senior analytics leadership talent.\n\n8. Product Management Director — $190,000+\nEspecially in SaaS and fintech.\n\n9. Cloud Architect — $180,000+\nAWS, Azure, and GCP certifications can push compensation even higher.\n\n10. Cybersecurity Architect — $175,000+\nAs cyber threats expand, so do salaries for senior security professionals.\n\nThese numbers reflect base salary; total compensation including bonuses, equity, and benefits often pushes them significantly higher in major metros like SF, NYC, and Seattle.\n\nIf you're targeting these tiers, focus on specialized skills, certifications, and roles in industries that are growing fastest: healthcare, AI, cloud, and energy.",
    ],
    [
        'title' => 'Remote Work in 2026: What Has Changed and How to Thrive',
        'category' => $catCareer,
        'excerpt' => 'Remote work is no longer a perk — it is a permanent feature of the U.S. job market. Here is how to position yourself for it.',
        'content' => "The remote work landscape has matured significantly. What started as an emergency shift in 2020 is now a structured part of how American companies operate. Roughly 35% of full-time U.S. workers now work in fully remote or hybrid arrangements, and this is unlikely to reverse.\n\nWhat has changed since the early days?\n\n1. Hybrid is the new default.\nMost employers now expect 2 to 3 office days per week. Fully remote roles still exist but are more competitive.\n\n2. Compensation is location-adjusted.\nBig tech and finance increasingly pay based on cost of living. Earning a Bay Area salary in a low-cost city is rarer than it used to be.\n\n3. Async communication is a real skill.\nWriting clear updates, documenting decisions, and using tools like Slack, Notion, and Loom effectively are now baseline expectations.\n\n4. Trust and outcomes matter more than hours.\nManagers measure deliverables, not desk time. The best remote performers are proactive communicators.\n\n5. Burnout is a real risk.\nWithout natural commute boundaries, work expands to fill all hours. Successful remote workers protect their schedule aggressively.\n\nHow to position yourself:\n\n• Build a strong written communication portfolio.\n• Demonstrate ownership in your past roles — remote employers want self-starters.\n• Become proficient with the standard remote stack: Slack, Zoom, Notion, Linear, GitHub, etc.\n• Establish a dedicated workspace and routine.\n\nRemote work is not going away. The candidates who win in this environment are those who treat it as a craft, not a convenience.",
    ],
    [
        'title' => 'Healthcare Jobs Outlook: Why Demand Will Keep Surging',
        'category' => $catInsights,
        'excerpt' => 'An aging population and post-pandemic shifts have made healthcare one of the most resilient sectors in the U.S. job market.',
        'content' => "If you are looking for stability, healthcare is one of the safest bets in 2026. Multiple data points confirm what most economists have been saying for years: the U.S. healthcare workforce is structurally undersupplied.\n\nThe numbers tell the story.\n\n• The Bureau of Labor Statistics projects healthcare employment to grow 13% from 2024 to 2034 — far above the average for all occupations.\n• An aging baby boomer population is increasing demand for nurses, geriatric specialists, and home health aides.\n• Burnout from the pandemic years has left many hospitals understaffed, with no quick fix in sight.\n\nWhich roles are growing fastest?\n\n1. Registered Nurses (RNs) — perpetual demand at hospitals, clinics, and long-term care.\n2. Nurse Practitioners — rapidly growing as states expand scope-of-practice laws.\n3. Physician Assistants — flexible mid-level providers in high demand.\n4. Medical Assistants — strong entry point with low barrier to entry.\n5. Physical Therapists — driven by aging population and post-surgery rehab needs.\n6. Mental Health Counselors — surging demand post-pandemic.\n7. Home Health Aides — fastest-growing role in absolute numbers.\n8. Medical Coders and Health Information Techs — remote-friendly, high demand.\n\nWhat employers are paying for:\n\n• Bilingual capability (English + Spanish especially).\n• EMR/EHR system fluency (Epic, Cerner).\n• Telehealth experience.\n• Specialty certifications (oncology, ICU, pediatrics).\n\nIf you are starting out, even certified nursing assistant (CNA) or medical assistant programs offer fast entry into the industry. From there, the path to better-paying roles is clear and well-traveled.\n\nFor anyone valuing job security, healthcare remains one of the most reliable industries in the U.S. economy.",
    ],
    [
        'title' => 'Salary Negotiation: How to Get Paid What You Are Worth',
        'category' => $catCareer,
        'excerpt' => 'Most professionals leave money on the table because they fear the conversation. Here is how to negotiate confidently and respectfully.',
        'content' => "Salary negotiation is one of the highest-leverage skills in your career. A single successful negotiation can compound into hundreds of thousands of dollars over a lifetime — yet most candidates skip it entirely.\n\nWhy people don't negotiate:\n• Fear of losing the offer (almost never happens).\n• Lack of comparable salary data.\n• Discomfort with conflict.\n\nWhy you should negotiate every time:\n• Initial offers usually have built-in flexibility.\n• Future raises and bonuses are typically calculated as a percentage of your starting salary.\n• Employers respect candidates who advocate for themselves.\n\nThe framework — five steps:\n\n1. Research market rate.\nUse Glassdoor, Levels.fyi, LinkedIn Salary, Payscale, and recent job postings. Aim for the 75th percentile of the role at companies your size.\n\n2. Wait for the offer first.\nNever name a number before they do. If pushed, give a researched range and pivot back to fit and value.\n\n3. Ask for time to review.\nA polite \"I'd like 24 to 48 hours to review\" is normal and expected.\n\n4. Counter with confidence and warmth.\nFraming matters. Try: \"I'm really excited about this role. Based on my research and the level of responsibility, I was hoping for \$X. Is there flexibility?\"\n\n5. Negotiate the full package, not just base.\nSign-on bonus, equity, vacation days, remote flexibility, professional development budget, and start date are all negotiable.\n\nWhat NOT to do:\n• Don't lie about competing offers.\n• Don't make it personal — keep it data-driven and professional.\n• Don't reject the first counter without a final ask.\n\nA respectful, well-prepared negotiation almost never costs you the offer. It usually adds 5–20% to what you would otherwise have accepted, and signals to your future employer that you are someone who knows their worth.",
    ],
    [
        'title' => 'The Rise of AI Jobs: What You Need to Know',
        'category' => $catInsights,
        'excerpt' => 'AI is reshaping the workforce. Some roles are vanishing, but many more are being created. Here is the real picture.',
        'content' => "Few topics dominate the 2026 job market like AI. Headlines swing between \"AI will replace everyone\" and \"AI is creating millions of jobs.\" The truth, as usual, is more nuanced.\n\nWhat is actually happening:\n\n1. Repetitive cognitive work is being automated.\nData entry, basic copywriting, and routine analysis are increasingly handled by AI tools. Roles that consist primarily of these tasks are most exposed.\n\n2. AI-augmented roles are growing.\nProfessionals who learn to use AI effectively — engineers, marketers, lawyers, designers, doctors — are becoming significantly more productive and more valuable.\n\n3. Entirely new roles are emerging.\nAI/ML engineers, prompt engineers, AI ethicists, AI product managers, and MLOps specialists barely existed five years ago. Today they pay among the highest in tech.\n\nThe high-demand AI roles right now:\n\n• Machine Learning Engineer ($180k–$300k+)\n• AI/ML Research Scientist ($220k–$500k+)\n• Data Scientist with ML focus ($150k–$250k)\n• AI Product Manager ($170k–$280k)\n• MLOps Engineer ($160k–$240k)\n• AI Solutions Architect ($180k–$260k)\n• AI Trainer / Annotation Specialist (entry-level path)\n\nHow to position yourself:\n\n• Learn the fundamentals: Python, statistics, linear algebra.\n• Take a hands-on ML course (DeepLearning.ai, fast.ai, Stanford CS229).\n• Build a portfolio of small projects on GitHub.\n• Stay current — the field moves fast. Following 5 to 10 leading researchers on Twitter or LinkedIn is genuinely useful.\n\nIf you are already in tech, start by integrating AI tools into your daily workflow. Your productivity goes up, and you accumulate the practical experience that employers actually value.\n\nThe candidates who thrive in the AI era won't be the ones who fight it — they will be the ones who learn to wield it.",
    ],
    [
        'title' => 'Cover Letters That Actually Get Read',
        'category' => $catCareer,
        'excerpt' => 'Most cover letters are ignored. Yours can be the exception if you avoid the templates and write something genuinely human.',
        'content' => "A common myth is that cover letters are dead. They are not — but boring, generic ones definitely are. A great cover letter is still one of the most effective ways to stand out, especially for competitive roles.\n\nWhy most cover letters fail:\n• They repeat the resume.\n• They open with \"I am writing to apply for...\" — the most overused line in hiring.\n• They are templated, generic, and emotionally flat.\n\nWhat works:\n\n1. Open with a hook, not a formality.\nLead with a specific moment, observation, or insight. Example: \"When I saw your team shipped feature X last month, I immediately thought about how my work on Y could extend it.\"\n\n2. Show, don't tell.\nDon't say \"I'm a great communicator.\" Tell a 2-sentence story that shows it.\n\n3. Tie your experience to the company's actual needs.\nMention something specific about their product, mission, or recent news. This signals you've done the research.\n\n4. Keep it under 250 words.\nHiring managers read fast. Brevity beats density.\n\n5. Close with a clear next step.\nNot \"I look forward to hearing from you,\" but \"I'd love 20 minutes to discuss how I can help with X.\"\n\nA structure that works:\n• Paragraph 1 — A specific hook tied to the company.\n• Paragraph 2 — Your most relevant achievement, framed as a story with measurable results.\n• Paragraph 3 — A short, direct close.\n\nA personalized 200-word cover letter beats a generic 600-word one every time. Ten minutes of research before writing can change your hit rate dramatically.",
    ],
    [
        'title' => 'Tech Industry Layoffs: How to Stay Resilient',
        'category' => $catInsights,
        'excerpt' => 'The tech sector has seen waves of layoffs since 2022. Here is how professionals can build resilience and move forward stronger.',
        'content' => "If you work in tech, you likely know someone who has been laid off in the past two years. Major companies like Meta, Google, Amazon, Microsoft, and many others have cut tens of thousands of roles since 2022.\n\nWhy this is happening:\n• Over-hiring during the 2020–2021 boom.\n• Higher interest rates pressuring growth-stage spending.\n• AI efficiency gains reducing certain headcount needs.\n• Strategic refocusing toward high-margin business lines.\n\nWhat this means for tech workers:\n\n1. Job security in tech is no longer assumed.\nEven at FAANG, layoffs are now a periodic possibility. Plan accordingly.\n\n2. Versatile skills beat narrow specialization.\nBackend engineers who also know infra, frontend devs who can handle design, PMs who can ship and write — these multi-tool players are getting kept.\n\n3. Public visibility helps.\nA strong LinkedIn presence, GitHub portfolio, or technical blog drastically reduces the time between layoff and next role.\n\n4. Networking is your insurance policy.\nMost good roles are filled through warm intros. Build genuine relationships before you need them.\n\nIf you are laid off:\n\n• Don't panic — take 1 to 2 weeks to reset.\n• Update your resume and LinkedIn the same week.\n• Reach out to 30 to 50 people in your network within 30 days.\n• Apply selectively — quality over quantity.\n• Negotiate your severance if you have leverage.\n\nLayoffs are painful but rarely the end of a career. Many of the strongest comebacks in tech start with one. Use the time to reset, learn, and target roles that actually align with where you want to go.",
    ],
    [
        'title' => 'How to Use LinkedIn to Land Your Next Job',
        'category' => $catCareer,
        'excerpt' => "LinkedIn is the most powerful tool in your job search — if you actually use it correctly. Most people don't.",
        'content' => "LinkedIn has 200+ million U.S. users and most recruiters source candidates there before anywhere else. Yet the average profile looks like a CV from 2010. With a few targeted tweaks, you can dramatically increase the inbound interest you receive.\n\nProfile fundamentals:\n\n1. A clear, professional headshot.\nProfiles with photos get 14x more views. No selfies, no party crops.\n\n2. A headline that says what you do — and the value you bring.\nNot just \"Software Engineer at X.\" Try: \"Senior Backend Engineer | Distributed Systems | Built APIs serving 10M users at X.\"\n\n3. A banner that signals your industry or personality.\nA simple branded image beats the default blue gradient.\n\n4. A summary that reads like a story.\n3 to 4 short paragraphs. Who you are, what you do, what you're looking for, what makes you different.\n\n5. Detailed experience descriptions.\nFor each role, list 3 to 5 measurable achievements. Numbers and outcomes, not duties.\n\n6. Skills section, prioritized.\nPin the top 3 most relevant skills. Get endorsements from people you've actually worked with.\n\nActive strategies:\n\n• Post 1 to 2 times a week. Insights, lessons, project recaps. Algorithm rewards consistency.\n• Comment thoughtfully on posts in your industry. Builds visibility faster than posting alone.\n• Connect with 5 new relevant people every week — not random, just intentional.\n• Use LinkedIn's \"Open to Work\" feature with the recruiters-only privacy setting.\n• Search and message recruiters at target companies directly. Short, polite, specific messages get responses.\n\nLinkedIn is not just a static resume. Treat it as your professional landing page and your most consistent networking channel. Done right, it brings opportunities to you instead of the other way around.",
    ],
];

$count = 0;
foreach ($blogs as $i => $b) {
    $slug = Str::slug($b['title']);
    if (Blog::where('slug', $slug)->exists()) {
        echo "Skipping (exists): {$slug}\n";
        continue;
    }
    Blog::create([
        'blog_catgories_id' => $b['category'],
        'author_id'         => $authorId,
        'title'             => $b['title'],
        'slug'              => $slug,
        'excerpt'           => $b['excerpt'],
        'content'           => $b['content'],
        'featured_image'    => $images[$i % count($images)],
        'status'            => 'published',
        'published_at'      => Carbon::now()->subDays($i * 3),
    ]);
    $count++;
    echo "Inserted: {$slug}\n";
}

echo "\nTotal new blogs inserted: {$count}\n";
echo "Total blogs in DB: ".Blog::count()."\n";
