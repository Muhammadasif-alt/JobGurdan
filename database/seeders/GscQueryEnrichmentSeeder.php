<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

/**
 * Adds one evidence-based section to existing US guides whose Search Console
 * queries are not answered on the page.
 *
 * The queries come from the owner's Search Console export (6 months to 9 Oct
 * 2026): pages with impressions but almost no clicks, where people search for
 * a salary, a pay rate or a requirement the guide never states. Each section
 * answers those queries with figures from the US Bureau of Labor Statistics
 * Occupational Employment and Wage Statistics (May 2025 national estimates),
 * puts the searched phrases in bold, links to related guides and adds a few
 * tags. Nothing is invented: where BLS publishes no figure for the exact job
 * (a React developer, an AI writer) the section says so and shows the nearest
 * occupation.
 *
 * Idempotent: a marker comment stops a second run adding the section again,
 * and slugs that are not in the database are skipped. It edits live rows, so
 * run it on the server after the seeders that create the posts.
 */
class GscQueryEnrichmentSeeder extends Seeder
{
    public const MARKER = '<!-- gsc-enrichment:v1 -->';

    private const PKR_PER_DOLLAR = 280;

    /**
     * BLS OEWS May 2025 national estimates: annual 10th percentile, median and
     * 90th percentile, and the median hourly wage, in US dollars.
     *
     * @var array<string, array{label: string, soc: string, p10: int, median: int, p90: int, hourly: float}>
     */
    private const WAGES = [
        'software' => ['label' => 'Software developers', 'soc' => '15-1252', 'p10' => 82460, 'median' => 135980, 'p90' => 214670, 'hourly' => 65.38],
        'web' => ['label' => 'Web developers', 'soc' => '15-1254', 'p10' => 48100, 'median' => 92650, 'p90' => 162290, 'hourly' => 44.54],
        'datasci' => ['label' => 'Data scientists', 'soc' => '15-2051', 'p10' => 67240, 'median' => 120230, 'p90' => 199130, 'hourly' => 57.80],
        'support' => ['label' => 'Computer user support specialists', 'soc' => '15-1232', 'p10' => 40980, 'median' => 61860, 'p90' => 100540, 'hourly' => 29.74],
        'netadmin' => ['label' => 'Network and computer systems administrators', 'soc' => '15-1244', 'p10' => 62640, 'median' => 99130, 'p90' => 155050, 'hourly' => 47.66],
        'infosec' => ['label' => 'Information security analysts', 'soc' => '15-1212', 'p10' => 75090, 'median' => 129180, 'p90' => 199850, 'hourly' => 62.11],
        'cashier' => ['label' => 'Cashiers', 'soc' => '41-2011', 'p10' => 24530, 'median' => 32880, 'p90' => 40410, 'hourly' => 15.81],
        'retail' => ['label' => 'Retail salespersons', 'soc' => '41-2031', 'p10' => 27210, 'median' => 35410, 'p90' => 47890, 'hourly' => 17.03],
        'carpenter' => ['label' => 'Carpenters', 'soc' => '47-2031', 'p10' => 40410, 'median' => 60580, 'p90' => 99910, 'hourly' => 29.12],
        'laborer' => ['label' => 'Construction laborers', 'soc' => '47-2061', 'p10' => 35090, 'median' => 47120, 'p90' => 78090, 'hourly' => 22.66],
        'writer' => ['label' => 'Writers and authors', 'soc' => '27-3043', 'p10' => 44310, 'median' => 76910, 'p90' => 139870, 'hourly' => 36.98],
        'nurse' => ['label' => 'Registered nurses', 'soc' => '29-1141', 'p10' => 68940, 'median' => 97550, 'p90' => 137470, 'hourly' => 46.90],
        'medassist' => ['label' => 'Medical assistants', 'soc' => '31-9092', 'p10' => 36050, 'median' => 45690, 'p90' => 59310, 'hourly' => 21.97],
        'dental' => ['label' => 'Dental assistants', 'soc' => '31-9091', 'p10' => 37130, 'median' => 48070, 'p90' => 62250, 'hourly' => 23.11],
        'forklift' => ['label' => 'Industrial truck and tractor operators', 'soc' => '53-7051', 'p10' => 36840, 'median' => 46420, 'p90' => 62520, 'hourly' => 22.32],
        'dispatch' => ['label' => 'Public safety telecommunicators (911 dispatchers)', 'soc' => '43-5031', 'p10' => 37320, 'median' => 53040, 'p90' => 79830, 'hourly' => 25.50],
        'police' => ['label' => 'Police and sheriff\'s patrol officers', 'soc' => '33-3051', 'p10' => 47510, 'median' => 76210, 'p90' => 115120, 'hourly' => 36.64],
        'housekeep' => ['label' => 'Maids and housekeeping cleaners', 'soc' => '37-2012', 'p10' => 27640, 'median' => 35510, 'p90' => 48570, 'hourly' => 17.07],
        'hoteldesk' => ['label' => 'Hotel, motel and resort desk clerks', 'soc' => '43-4081', 'p10' => 27120, 'median' => 35070, 'p90' => 45470, 'hourly' => 16.86],
        'truck' => ['label' => 'Heavy and tractor-trailer truck drivers', 'soc' => '53-3032', 'p10' => 40140, 'median' => 58640, 'p90' => 79380, 'hourly' => 28.19],
        'guard' => ['label' => 'Security guards', 'soc' => '33-9032', 'p10' => 30210, 'median' => 38020, 'p90' => 58840, 'hourly' => 18.28],
    ];

    /**
     * Software developer median wage by state, BLS OEWS May 2025.
     *
     * @var array<string, int>
     */
    private const SOFTWARE_BY_STATE = [
        'California' => 174410, 'Washington' => 166540, 'New York' => 166180, 'Massachusetts' => 165210,
        'Virginia' => 136460, 'Texas' => 132150, 'Georgia' => 131010, 'Florida' => 130980,
    ];

    public function run(): void
    {
        foreach ($this->entries() as $entry) {
            $this->enrich($entry);
        }
    }

    /**
     * @param  array<string, mixed>  $entry
     */
    public function enrich(array $entry): bool
    {
        $blog = Blog::where('slug', $entry['slug'])->first();

        if ($blog === null || str_contains((string) $blog->content, self::MARKER)) {
            return false;
        }

        $section = $this->section($entry);
        $content = (string) $blog->content;

        if (preg_match('/<h2[^>]*>\s*(Frequently Asked Questions|FAQs?)\b/i', $content, $match, PREG_OFFSET_CAPTURE)
            || preg_match('/<h2[^>]*>\s*(People Also Search For|More Job Guides|Related)/i', $content, $match, PREG_OFFSET_CAPTURE)) {
            $content = substr($content, 0, $match[0][1]).$section."\n\n".substr($content, $match[0][1]);
        } else {
            $content = rtrim($content)."\n\n".$section."\n";
        }

        $blog->content = $content;
        $blog->tags = $this->mergeTags((string) $blog->tags, $entry['tags'] ?? []);
        $blog->save();

        return true;
    }

    /**
     * @param  array<string, mixed>  $entry
     */
    private function section(array $entry): string
    {
        $rows = '';

        foreach ($entry['wages'] as $key) {
            $wage = self::WAGES[$key];
            $rows .= '        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;"><strong>'.$wage['label'].'</strong></td>'
                .'<td style="padding:10px;">$'.number_format($wage['p10']).'</td>'
                .'<td style="padding:10px;">$'.number_format($wage['median']).'</td>'
                .'<td style="padding:10px;">$'.number_format($wage['p90']).'</td>'
                .'<td style="padding:10px;">$'.number_format($wage['hourly'], 2).'</td>'
                .'<td style="padding:10px;">PKR '.number_format($wage['median'] * self::PKR_PER_DOLLAR).'</td></tr>'."\n";
        }

        $html = self::MARKER."\n<h2>".$entry['heading']."</h2>\n\n<p>".$entry['intro']."</p>\n\n"
            ."<div style=\"overflow-x:auto;margin:24px 0;\">\n<table style=\"width:100%;border-collapse:collapse;font-size:15px;\">\n    <thead>\n        <tr style=\"background:#1b3a6b;color:#fff;\">\n"
            ."            <th style=\"padding:10px;text-align:left;\">Occupation</th>\n            <th style=\"padding:10px;text-align:left;\">Lowest 10% earn up to</th>\n"
            ."            <th style=\"padding:10px;text-align:left;\">Median per year</th>\n            <th style=\"padding:10px;text-align:left;\">Highest 10% earn from</th>\n"
            ."            <th style=\"padding:10px;text-align:left;\">Median per hour</th>\n            <th style=\"padding:10px;text-align:left;\">Median in PKR (illustrative)</th>\n"
            ."        </tr>\n    </thead>\n    <tbody>\n".$rows."    </tbody>\n</table>\n</div>\n\n";

        if (! empty($entry['states'])) {
            $html .= "<p><strong>Software developer median pay by state</strong> (May 2025). React work is counted in this occupation, and pay follows the local market:</p>\n\n"
                ."<div style=\"overflow-x:auto;margin:24px 0;\">\n<table style=\"width:100%;border-collapse:collapse;font-size:15px;\">\n    <thead>\n        <tr style=\"background:#1b3a6b;color:#fff;\">\n"
                ."            <th style=\"padding:10px;text-align:left;\">State</th>\n            <th style=\"padding:10px;text-align:left;\">Median per year</th>\n        </tr>\n    </thead>\n    <tbody>\n";

            foreach (self::SOFTWARE_BY_STATE as $state => $median) {
                $html .= '        <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:10px;">'.$state.'</td><td style="padding:10px;">$'.number_format($median)."</td></tr>\n";
            }

            $html .= "    </tbody>\n</table>\n</div>\n\n";
        }

        $html .= '<p>'.$entry['note']."</p>\n\n<p><strong>Source:</strong> US Bureau of Labor Statistics, Occupational Employment and Wage Statistics, May 2025 national estimates, before tax. The PKR column uses an illustrative PKR ".self::PKR_PER_DOLLAR." to the dollar, so check your bank's rate. Wages move every year and by employer.</p>\n\n";

        $links = '';

        foreach ($entry['links'] as [$href, $text, $why]) {
            $links .= '    <li><a href="'.$href.'">'.$text.'</a>: '.$why."</li>\n";
        }

        return $html."<p>Related guides:</p>\n\n<ul>\n".$links.'</ul>';
    }

    /**
     * @param  list<string>  $new
     */
    private function mergeTags(string $existing, array $new): string
    {
        $tags = array_values(array_filter(array_map('trim', explode(',', $existing))));
        $lower = array_map('strtolower', $tags);

        foreach ($new as $tag) {
            $candidate = implode(',', [...$tags, $tag]);

            if (! in_array(strtolower($tag), $lower, true) && strlen($candidate) <= 240) {
                $tags[] = $tag;
                $lower[] = strtolower($tag);
            }
        }

        return implode(', ', $tags);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function entries(): array
    {
        $it = ['/categories/it-software', 'IT and Software Jobs', 'every IT vacancy on JobGader.'];
        $retail = ['/blog/retail-associate-jobs-in-usa', 'Retail Associate Jobs in the USA', 'store roles, requirements and pay.'];

        return [
            [
                'slug' => 'react-developer-jobs-in-usa',
                'heading' => 'React Developer Salary in the USA (2026)',
                'intro' => 'Looking for the <strong>react developer salary</strong> in the USA? The US Bureau of Labor Statistics does not publish a separate figure for React developers: React work is counted under software developers and web developers. In May 2025 the <strong>average react developer salary in the US</strong> is best read from those two occupations, which had a median of $135,980 a year for software developers and $92,650 for web developers. What a <strong>react js developer</strong> earns depends on the employer, the seniority and the city, so use the ranges and the state table below rather than one number.',
                'wages' => ['software', 'web'],
                'states' => true,
                'note' => 'For a <strong>starting salary for a react developer</strong>, BLS has no entry-level figure. The lowest 10% of software developers earned $82,460 or less and the lowest 10% of web developers $48,100 or less, which is the closest guide to what a junior hire may be offered. A <strong>react developer salary by state</strong> follows the local market, as the state table shows: California, Washington and New York sit well above the national median, while Texas, Georgia and Florida are close to it.',
                'links' => [
                    ['/blog/software-developer-jobs-in-usa', 'Software Developer Jobs in the USA', 'the wider developer market.'],
                    ['/blog/web-developer-jobs-in-usa', 'Web Developer Jobs in the USA', 'front-end and web roles.'],
                    ['/blog/full-stack-developer-jobs-in-usa', 'Full Stack Developer Jobs in the USA', 'React with a back end.'],
                    $it,
                ],
                'tags' => ['react developer salary', 'react developer salary usa', 'software developer salary usa', 'react jobs usa'],
            ],
            [
                'slug' => 'help-desk-technician-jobs-in-usa',
                'heading' => 'Help Desk Technician Salary in the USA (2026)',
                'intro' => 'What does a <strong>help desk technician</strong> earn in the USA? BLS counts help desk work under computer user support specialists, whose median pay in May 2025 was $61,860 a year, or $29.74 an hour. Entry-level <strong>help desk jobs</strong> sit towards the lower end of the range; experienced tier 2 and systems roles sit towards the top.',
                'wages' => ['support', 'netadmin'],
                'note' => 'Network and systems administration is the usual next step from the help desk, and it pays a median of $99,130. Certifications and a track record matter more than a degree for most support roles.',
                'links' => [
                    ['/blog/network-engineer-jobs-in-usa', 'Network Engineer Jobs in the USA', 'the next step up.'],
                    ['/blog/entry-level-it-jobs', 'Entry-Level IT Jobs', 'how to start in IT.'],
                    $it,
                ],
                'tags' => ['help desk salary usa', 'help desk jobs', 'it support salary usa'],
            ],
            [
                'slug' => 'cashier-jobs-in-usa',
                'heading' => 'Cashier Pay in the USA (2026)',
                'intro' => 'How much is <strong>cashier pay in the USA</strong>? BLS reports a median of $15.81 an hour, or $32,880 a year for full-time work, in May 2025. The lowest-paid tenth earned up to $24,530 a year and the highest-paid tenth from $40,410, and the real figure depends on the state minimum wage, the employer and the hours. Many <strong>usa cashier pay jobs</strong> are part time.',
                'wages' => ['cashier', 'retail'],
                'note' => 'Cashiers who move into retail sales or supervision earn more, and a few employers pay above the local minimum wage for night and weekend shifts. Check the state minimum wage for the state you are applying in.',
                'links' => [
                    $retail,
                    ['/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa', 'How to Apply for Walmart Store Associate Jobs', 'a large US retail employer.'],
                    ['/blog/unskilled-jobs-in-usa-for-foreigners', 'Unskilled Jobs in the USA for Foreigners', 'entry-level options.'],
                ],
                'tags' => ['cashier pay usa', 'usa cashier pay', 'cashier salary usa'],
            ],
            [
                'slug' => 'carpenter-jobs-in-usa',
                'heading' => 'Carpenter Salary in the USA (2026)',
                'intro' => 'What does a <strong>carpenter in the USA</strong> earn? BLS reports a median of $60,580 a year, or $29.12 an hour, for carpenters in May 2025, with the lowest-paid tenth earning up to $40,410 and the highest-paid tenth from $99,910. Pay rises with a licence, an apprenticeship and specialist work such as finish carpentry or commercial framing.',
                'wages' => ['carpenter', 'laborer'],
                'note' => 'Carpentry is a trade you can enter through an apprenticeship, and licensing rules depend on the state. Foreign carpenters need a work visa or green card through an employer, so check the visa routes before you accept any offer.',
                'links' => [
                    ['/blog/construction-jobs-in-usa-for-foreigners', 'Construction Jobs in the USA for Foreigners', 'trades, pay and the visa picture.'],
                    ['/categories/construction-trades', 'Construction Trades Jobs', 'open vacancies.'],
                ],
                'tags' => ['carpenter salary usa', 'carpenter in usa', 'construction trades usa'],
            ],
            [
                'slug' => 'construction-jobs-in-usa-for-foreigners',
                'heading' => 'What Do Construction Workers Earn in the USA? (2026)',
                'intro' => 'Before you search <strong>construction jobs in the USA for foreigners</strong>, check the pay. BLS reports a median of $47,120 a year for construction laborers and $60,580 for carpenters in May 2025. Most <strong>construction jobs with visa sponsorship in the USA</strong> need an employer who files for the worker first, and sponsorship for general labouring is limited.',
                'wages' => ['laborer', 'carpenter'],
                'note' => 'Skilled and licensed trades are easier to sponsor than general labour. If you are from outside the USA, never pay an agent for a promised construction visa: only the employer and the immigration authority can start the process.',
                'links' => [
                    ['/blog/carpenter-jobs-in-usa', 'Carpenter Jobs in the USA', 'one skilled trade in detail.'],
                    ['/blog/unskilled-jobs-in-usa-for-foreigners', 'Unskilled Jobs in the USA for Foreigners', 'what is realistic without a skill.'],
                    ['/blog/landscaper-jobs-usa-visa-sponsorship-2026', 'Landscaper Jobs in the USA With Visa Sponsorship', 'an H-2B route.'],
                ],
                'tags' => ['construction jobs usa for foreigners', 'construction visa sponsorship usa', 'construction worker salary usa'],
            ],
            [
                'slug' => 'data-scientist-jobs-in-usa',
                'heading' => 'Data Scientist Salary in the USA (2026)',
                'intro' => 'The <strong>data scientist salary in the USA</strong> had a median of $120,230 a year in May 2025, or $57.80 an hour. For <strong>data science jobs salary in the US</strong>, the lowest-paid tenth earned up to $67,240 and the highest-paid tenth from $199,130, so seniority, employer and city explain most of the spread.',
                'wages' => ['datasci', 'software'],
                'note' => 'Data scientists earn less than the top software engineering bands at some employers and more at others. A portfolio of real projects and the tools an employer asks for matter more than a title.',
                'links' => [
                    ['/blog/python-developer-jobs-in-usa', 'Python Developer Jobs in the USA', 'the language behind most data work.'],
                    ['/blog/cloud-engineer-jobs-in-usa', 'Cloud Engineer Jobs in the USA', 'a nearby role.'],
                    $it,
                ],
                'tags' => ['data scientist salary usa', 'data science jobs usa salary', 'data science salary us'],
            ],
            [
                'slug' => 'python-developer-jobs-in-usa',
                'heading' => 'Python Developer Salary in the USA (2026)',
                'intro' => 'What is the <strong>python developer salary in the USA</strong>? BLS counts Python work under software developers, with a median of $135,980 a year in May 2025, and under data scientists at $120,230. For <strong>python jobs in the USA</strong> the lowest-paid tenth of software developers earned up to $82,460, and the highest-paid tenth from $214,670.',
                'wages' => ['software', 'datasci'],
                'note' => 'BLS does not split developers by language, so these are ranges for the occupation, not for Python alone. Roles that combine Python with cloud, data or machine learning skills sit towards the top.',
                'links' => [
                    ['/blog/data-scientist-jobs-in-usa', 'Data Scientist Jobs in the USA', 'a common Python career.'],
                    ['/blog/software-developer-jobs-in-usa', 'Software Developer Jobs in the USA', 'the wider market.'],
                    $it,
                ],
                'tags' => ['python developer salary usa', 'python jobs usa', 'python developer usa'],
            ],
            [
                'slug' => 'wordpress-developer-jobs-in-usa',
                'heading' => 'WordPress Developer Salary in the USA (2026)',
                'intro' => 'The <strong>wordpress developer salary</strong> in the USA depends on whether the job is web development or a broader software role. BLS counts WordPress work under web developers, with a median of $92,650 a year in May 2025, or $44.54 an hour. For <strong>wordpress developer jobs salary</strong>, the lowest-paid tenth earned up to $48,100 and the highest-paid tenth from $162,290.',
                'wages' => ['web', 'software'],
                'note' => 'The <strong>average wordpress developer salary</strong> is lower for theme and site-building work and higher for custom plugin, WooCommerce and performance engineering. BLS has no WordPress-only figure, so treat these as the ranges for the occupation.',
                'links' => [
                    ['/blog/web-developer-jobs-in-usa', 'Web Developer Jobs in the USA', 'the occupation in detail.'],
                    ['/blog/react-developer-jobs-in-usa', 'React Developer Jobs in the USA', 'a common next skill.'],
                    $it,
                ],
                'tags' => ['wordpress developer salary', 'wordpress developer usa', 'web developer salary usa'],
            ],
            [
                'slug' => 'copywriter-jobs-in-usa',
                'heading' => 'Copywriter Salary in the USA (2026)',
                'intro' => 'How much do <strong>copywriters make</strong> in the USA? BLS counts copywriting under writers and authors, with a median of $76,910 a year in May 2025, or $36.98 an hour. For <strong>copywriter jobs in the USA</strong> and <strong>content writing jobs in the USA</strong>, the lowest-paid tenth earned up to $44,310 and the highest-paid tenth from $139,870, and agency, in-house and freelance rates all differ.',
                'wages' => ['writer'],
                'note' => 'Freelance rates are not in this table, because BLS covers employees. A portfolio and a niche such as finance, health or software usually matter more than years of experience.',
                'links' => [
                    ['/blog/content-writer-jobs-in-usa', 'Content Writer Jobs in the USA', 'a close role.'],
                    ['/blog/ai-content-writer-jobs-in-usa', 'AI Content Writer Jobs in the USA', 'the AI-assisted side.'],
                    ['/categories/writing-content', 'Writing and Content Jobs', 'open vacancies.'],
                ],
                'tags' => ['copywriter jobs usa', 'copywriter salary usa', 'content writing jobs usa'],
            ],
            [
                'slug' => 'ai-content-writer-jobs-in-usa',
                'heading' => 'AI Content Writer Pay in the USA (2026)',
                'intro' => 'What do <strong>AI writer jobs</strong> pay? BLS has no occupation called AI writer: <strong>AI content writing jobs</strong> are counted under writers and authors, with a median of $76,910 a year in May 2025, or $36.98 an hour. The lowest-paid tenth earned up to $44,310 and the highest-paid tenth from $139,870. Companies that <strong>hire AI content writers</strong> pay for editing, fact-checking and subject knowledge, not for pasting generated text.',
                'wages' => ['writer'],
                'note' => 'Employers that use AI tools expect a writer to check every claim, so a verifiable record of accurate work counts for more than the tool you use.',
                'links' => [
                    ['/blog/copywriter-jobs-in-usa', 'Copywriter Jobs in the USA', 'the established route.'],
                    ['/blog/content-writer-jobs-in-usa', 'Content Writer Jobs in the USA', 'a close role.'],
                    ['/categories/writing-content', 'Writing and Content Jobs', 'open vacancies.'],
                ],
                'tags' => ['ai writer jobs', 'ai content writing jobs', 'ai content writer salary'],
            ],
            [
                'slug' => 'registered-nurse-jobs-in-usa',
                'heading' => 'Registered Nurse Salary in the USA (2026)',
                'intro' => 'What is the <strong>nursing jobs in the USA salary</strong>? BLS reports a median of $97,550 a year for registered nurses in May 2025, or $46.90 an hour. The lowest-paid tenth earned up to $68,940 and the highest-paid tenth from $137,470. A <strong>nurse in the US</strong> who trained abroad must also pass the licensing steps before starting work.',
                'wages' => ['nurse'],
                'note' => 'Overseas-trained nurses normally need a credential review, the NCLEX exam and a state licence, and an employer-sponsored green card usually takes years. Never pay an agent for a promised nursing visa.',
                'links' => [
                    ['/blog/nurse-jobs-in-the-us', 'Nurse Jobs in the US', 'routes for overseas nurses.'],
                    ['/blog/caregiver-jobs-usa-visa-sponsorship-2026', 'Caregiver Jobs in the USA With Visa Sponsorship', 'how EB-3 works.'],
                    ['/categories/healthcare', 'Healthcare Jobs', 'open vacancies.'],
                ],
                'tags' => ['nurse salary usa', 'nursing jobs usa salary', 'registered nurse salary'],
            ],
            [
                'slug' => 'school-nurse-jobs-in-usa',
                'heading' => 'How Much Does a School Nurse Make? (2026)',
                'intro' => 'School nurses are registered nurses, so <strong>how much a school nurse makes</strong> follows registered nurse pay: a median of $97,550 a year in May 2025, with the lowest-paid tenth earning up to $68,940. School districts often pay on a teacher-style scale and offer a school-year calendar, so the figure for a school nurse can differ from a hospital nurse. For <strong>how to become a school nurse</strong>, start with a registered nurse licence; requirements vary by state and district.',
                'wages' => ['nurse'],
                'note' => 'Check the state education department for any school nurse certificate and the district for its pay scale, since BLS does not publish a school-nurse-only figure in this table.',
                'links' => [
                    ['/blog/registered-nurse-jobs-in-usa', 'Registered Nurse Jobs in the USA', 'the licence route.'],
                    ['/categories/healthcare', 'Healthcare Jobs', 'open vacancies.'],
                ],
                'tags' => ['school nurse salary', 'how to become a school nurse', 'nurse salary usa'],
            ],
            [
                'slug' => 'medical-assistant-jobs-in-usa',
                'heading' => 'Medical Assistant Pay in the USA (2026)',
                'intro' => 'What is <strong>medical assistant pay</strong> in the USA? BLS reports a median of $45,690 a year, or $21.97 an hour, for medical assistants in May 2025. The lowest-paid tenth earned up to $36,050 and the highest-paid tenth from $59,310. Dental assistants had a median of $48,070.',
                'wages' => ['medassist', 'dental'],
                'note' => 'Many employers prefer a certificate from an accredited programme, and pay rises with a specialty clinic or a supervisory role.',
                'links' => [
                    ['/blog/dental-assistant-jobs-in-usa', 'Dental Assistant Jobs in the USA', 'the dental route.'],
                    ['/categories/healthcare', 'Healthcare Jobs', 'open vacancies.'],
                ],
                'tags' => ['medical assistant pay', 'medical assistant salary usa', 'dental assistant salary usa'],
            ],
            [
                'slug' => 'emergency-dispatcher-jobs-in-usa',
                'heading' => '911 Emergency Dispatcher Salary in the USA (2026)',
                'intro' => 'What is the <strong>911 emergency dispatcher salary</strong>, and <strong>how much do dispatchers make</strong>? BLS counts 911 dispatchers as public safety telecommunicators, with a median of $53,040 a year in May 2025, or $25.50 an hour. The lowest-paid tenth earned up to $37,320 and the highest-paid tenth from $79,830.',
                'wages' => ['dispatch'],
                'note' => 'Dispatch is shift work with nights and holidays, and pay differs by county and agency. Most dispatchers are hired directly by local governments.',
                'links' => [
                    ['/blog/police-officer-jobs-in-usa', 'Police Officer Jobs in the USA', 'a related public safety role.'],
                    ['/categories/security', 'Security Jobs', 'open vacancies.'],
                ],
                'tags' => ['911 dispatcher salary', 'emergency dispatcher salary usa', 'dispatcher jobs usa'],
            ],
            [
                'slug' => 'police-officer-jobs-in-usa',
                'heading' => 'Police Officer Salary in the USA (2026)',
                'intro' => 'What do <strong>police jobs in the USA</strong> pay? BLS reports a median of $76,210 a year for police and sheriff\'s patrol officers in May 2025, or $36.64 an hour. The lowest-paid tenth earned up to $47,510 and the highest-paid tenth from $115,120. Citizenship and residency requirements apply to most agencies, so check the agency before you plan.',
                'wages' => ['police', 'dispatch'],
                'note' => 'Pay depends heavily on the city, county or state, and overtime and shift differentials change the total.',
                'links' => [
                    ['/blog/emergency-dispatcher-jobs-in-usa', 'Emergency Dispatcher Jobs in the USA', 'a related role.'],
                    ['/categories/security', 'Security Jobs', 'open vacancies.'],
                ],
                'tags' => ['police jobs usa', 'police officer salary usa', 'law enforcement jobs usa'],
            ],
            [
                'slug' => 'forklift-operator-jobs-in-usa',
                'heading' => 'Forklift Operator Pay in the USA (2026)',
                'intro' => 'What is a forklift operator\'s pay in the USA? BLS counts forklift drivers under industrial truck and tractor operators, with a median of $46,420 a year in May 2025, or $22.32 an hour. The lowest-paid tenth earned up to $36,840 and the highest-paid tenth from $62,520. People also ask about <strong>green card sponsorship forklift jobs</strong>: an employer sponsoring a permanent worker for this kind of role generally uses the EB-3 "other workers" category, which has a long wait, and no recruiter can guarantee it.',
                'wages' => ['forklift'],
                'note' => 'The EB-3 steps, the Visa Bulletin wait and the scam warning signs are explained in our caregiver guide, and they apply to other EB-3 roles too.',
                'links' => [
                    ['/blog/caregiver-jobs-usa-visa-sponsorship-2026', 'Caregiver Jobs in the USA With Visa Sponsorship', 'how EB-3 sponsorship works.'],
                    ['/blog/warehouse-worker-salary-usa-2026', 'Warehouse Worker Salary in the USA', 'the warehouse pay picture.'],
                    ['/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026', 'Warehouse Worker Jobs With Visa Sponsorship', 'the H-2B route.'],
                ],
                'tags' => ['forklift operator salary usa', 'forklift jobs usa', 'green card forklift jobs'],
            ],
            [
                'slug' => 'retail-associate-jobs-in-usa',
                'heading' => 'Retail Associate Pay in the USA (2026)',
                'intro' => 'How much do <strong>retail jobs in the USA</strong> pay? BLS reports a median of $17.03 an hour, or $35,410 a year, for retail salespersons in May 2025, and $15.81 an hour for cashiers. The lowest-paid tenth of retail salespersons earned up to $27,210 and the highest-paid tenth from $47,890.',
                'wages' => ['retail', 'cashier'],
                'note' => 'Commission, department and store type change the total, and state minimum wages set the floor.',
                'links' => [
                    ['/blog/cashier-jobs-in-usa', 'Cashier Jobs in the USA', 'the cashier role.'],
                    ['/blog/how-to-apply-for-walmart-store-associate-jobs-in-the-usa', 'How to Apply for Walmart Store Associate Jobs', 'a large employer.'],
                ],
                'tags' => ['retail jobs usa', 'retail associate salary usa', 'retail pay usa'],
            ],
            [
                'slug' => 'hotel-jobs-in-usa-for-foreigners',
                'heading' => 'What Do Hotel Jobs in the USA Pay? (2026)',
                'intro' => 'What do <strong>hotel jobs in the USA</strong> pay? BLS reports a median of $17.07 an hour ($35,510 a year) for housekeeping cleaners and $16.86 an hour ($35,070) for hotel desk clerks in May 2025. People also search for <strong>US hotel jobs for international students</strong> and <strong>hotel jobs in the USA with visa sponsorship</strong>: a student visa limits the hours you can work, and sponsorship for hotel work is limited to seasonal programmes, so check the rules for your own visa.',
                'wages' => ['housekeep', 'hoteldesk'],
                'note' => 'The seasonal H-2B route for hotel housekeepers, with its cap and its rules, is explained in our visa guide, and the salary guide shows the same BLS figures in more detail.',
                'links' => [
                    ['/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026', 'Hotel Housekeeper Jobs in the USA With Visa Sponsorship', 'the H-2B route.'],
                    ['/blog/hotel-housekeeper-salary-usa-2026', 'Hotel Housekeeper Salary in the USA', 'pay in detail.'],
                    ['/blog/how-to-apply-for-marriott-hotel-jobs-in-the-usa', 'How to Apply for Marriott Hotel Jobs', 'a large employer.'],
                ],
                'tags' => ['hotel jobs usa', 'hotel jobs usa visa sponsorship', 'hotel housekeeper pay usa'],
            ],
            [
                'slug' => 'truck-driver-jobs-in-usa-with-visa-sponsorship',
                'heading' => 'Truck Driver Pay in the USA (2026)',
                'intro' => 'What do <strong>truck driver jobs in the USA</strong> pay? BLS reports a median of $58,640 a year for heavy and tractor-trailer truck drivers in May 2025, or $28.19 an hour. The lowest-paid tenth earned up to $40,140 and the highest-paid tenth from $79,380. A <strong>truck driver visa sponsorship</strong> offer needs scrutiny: foreign drivers usually cannot get a work visa for driving alone, and a CDL has its own rules.',
                'wages' => ['truck'],
                'note' => 'The CDL position for foreign drivers has been tightened and challenged in court, so check the FMCSA page before you pay for training or an agent.',
                'links' => [
                    ['/blog/truck-driver-salary-usa-2026', 'Truck Driver Salary in the USA', 'pay in detail.'],
                    ['/blog/best-countries-for-truck-drivers-to-work-abroad-2026', 'Best Countries for Truck Drivers to Work Abroad', 'how the US compares.'],
                    ['/blog/how-to-become-a-long-haul-truck-driver-in-usa', 'How to Become a Long-Haul Truck Driver in the USA', 'the licence steps.'],
                ],
                'tags' => ['truck driver salary usa', 'cdl jobs usa', 'truck driver visa sponsorship usa'],
            ],
            [
                'slug' => 'unskilled-jobs-in-usa-for-foreigners',
                'heading' => 'What Do Unskilled Jobs in the USA Pay? (2026)',
                'intro' => 'What do <strong>unskilled jobs in the USA for foreigners</strong> pay? BLS reports a median of $15.81 an hour for cashiers, $17.07 for housekeeping cleaners and $22.66 for construction laborers in May 2025. A foreigner still needs the right to work, and most employers do not sponsor general labour, so be careful with any <strong>work visa</strong> offer that asks for money.',
                'wages' => ['cashier', 'housekeep', 'laborer', 'guard'],
                'note' => 'The legal routes are narrow: seasonal H-2A and H-2B visas, a limited EB-3 category and student or exchange visas with work limits. Our visa guides explain each one.',
                'links' => [
                    ['/blog/hotel-housekeeper-jobs-usa-visa-sponsorship-2026', 'Hotel Housekeeper Jobs With Visa Sponsorship', 'an H-2B route.'],
                    ['/blog/farm-worker-jobs-usa-visa-sponsorship-2026', 'Farm Worker Jobs With Visa Sponsorship', 'the H-2A route.'],
                    ['/blog/warehouse-worker-jobs-usa-visa-sponsorship-2026', 'Warehouse Worker Jobs With Visa Sponsorship', 'the warehouse route.'],
                ],
                'tags' => ['unskilled jobs usa', 'jobs usa for foreigners', 'entry level jobs usa'],
            ],
        ];
    }
}
