<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Database\Seeder;

/**
 * Job listings that sit behind the truck driver, caregiver and UK warehouse
 * guides. The hotel, landscaper and warehouse USA guides already have their H-2B
 * job order listings, so their salary and from-Pakistan guides share those.
 *
 * Every listing links only to an official portal, carries no salary (this site
 * does not republish job-board rates) and says plainly what the portal is.
 *
 * updateOrCreate, so re-running is safe; it overwrites admin-panel edits to
 * these rows.
 */
class GuideJobListingsSeeder extends Seeder
{
    public function run(): void
    {
        $usa = Location::firstOrCreate(
            ['name' => 'United States'],
            ['area' => 'Nationwide', 'country' => 'United States']
        );

        $uk = Location::firstOrCreate(
            ['name' => 'United Kingdom'],
            ['area' => 'Nationwide', 'country' => 'United Kingdom']
        );

        $this->seedTruckDriver($usa);
        $this->seedCaregiver($usa);
        $this->seedUkWarehouse($uk);
        $canada = Location::firstOrCreate(
            ['name' => 'Canada'],
            ['area' => 'Nationwide', 'country' => 'Canada']
        );

        $eu = Location::firstOrCreate(
            ['name' => 'European Union'],
            ['area' => 'Multiple countries', 'country' => 'European Union']
        );

        $this->seedSalaryAndPakistanListings($usa, $uk, $canada, $eu);
    }

    /**
     * Each guide gets its own listing, even when the occupation matches an
     * existing one, so the salary and from-Pakistan guides are not pointed at
     * the visa guide's job.
     */
    private function seedSalaryAndPakistanListings(Location $usa, Location $uk, Location $canada, Location $eu): void
    {
        $h2b = 'https://seasonaljobs.dol.gov/';
        $h2bPage = 'https://www.uscis.gov/working-in-the-united-states/temporary-workers/h-2b-temporary-non-agricultural-workers';
        $note = '<p><strong>Note:</strong> visa and wage rules are set by the US Government, not by JobGader. This link opens an official US Government page; it is not an application form, and a visible order does not prove recruitment is still open.</p>';

        $listings = [
            [
                'category' => ['hospitality-tourism', 'Hospitality & Tourism'],
                'advertiser' => ['US Hotels and Resorts (Aggregated)', 'usa-hotels-aggregated'],
                'location' => $usa,
                'position' => 'Hotel Housekeeper — US Hotels (Wage Check on H-2B Job Orders)',
                'intro' => 'Each H-2B job order on the US Department of Labor portal states the hourly wage the employer must pay. Read the wage line before you contact an employer.',
                'items' => ['The wage on a job order must meet the prevailing wage set for that occupation and area', 'Hours, dates and location are on the order itself', 'The employer must not charge you recruitment or visa fees'],
                'url' => $h2b,
                'keywords' => 'hotel housekeeper salary usa, hotel housekeeper pay, h-2b wage rates, housekeeper jobs usa',
            ],
            [
                'category' => ['hospitality-tourism', 'Hospitality & Tourism'],
                'advertiser' => ['US Hotels and Resorts (Aggregated)', 'usa-hotels-aggregated'],
                'location' => $usa,
                'position' => 'Hotel Housekeeper — US Hotels (Applying From Pakistan)',
                'intro' => 'A worker in Pakistan cannot apply to the US Government for an H-2B visa alone: the US employer files the petition with USCIS first. USCIS explains each step on its H-2B page.',
                'items' => ['The employer obtains labor certification, then petitions USCIS', 'Only after approval do you apply for the visa at a US embassy or consulate', 'Anyone asking you for a fee to "guarantee" a visa is not following the rules'],
                'url' => $h2bPage,
                'keywords' => 'hotel housekeeper job usa from pakistan, h-2b visa pakistan, housekeeper visa usa, how to apply h-2b',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Warehouse and Logistics Employers (Aggregated)', 'usa-warehouse-aggregated'],
                'location' => $usa,
                'position' => 'Warehouse Worker — US Employers (Wage Check on H-2B Job Orders)',
                'intro' => 'Each H-2B job order on the US Department of Labor portal states the hourly wage the employer must pay. Read the wage line before you contact an employer.',
                'items' => ['The wage on a job order must meet the prevailing wage set for that occupation and area', 'Hours, dates and location are on the order itself', 'The employer must not charge you recruitment or visa fees'],
                'url' => $h2b,
                'keywords' => 'warehouse worker salary usa, warehouse pay, h-2b wage rates, warehouse jobs usa',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Warehouse and Logistics Employers (Aggregated)', 'usa-warehouse-aggregated'],
                'location' => $usa,
                'position' => 'Warehouse Worker — US Employers (Applying From Pakistan)',
                'intro' => 'A worker in Pakistan cannot apply to the US Government for an H-2B visa alone: the US employer files the petition with USCIS first. USCIS explains each step on its H-2B page.',
                'items' => ['The employer obtains labor certification, then petitions USCIS', 'Only after approval do you apply for the visa at a US embassy or consulate', 'Anyone asking you for a fee to "guarantee" a visa is not following the rules'],
                'url' => $h2bPage,
                'keywords' => 'warehouse job usa from pakistan, h-2b visa pakistan, warehouse worker visa, how to apply h-2b',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Landscaping Employers (Aggregated)', 'usa-landscaping-aggregated'],
                'location' => $usa,
                'position' => 'Landscaper — US Employers (Wage Check on H-2B Job Orders)',
                'intro' => 'Each H-2B job order on the US Department of Labor portal states the hourly wage the employer must pay. Read the wage line before you contact an employer.',
                'items' => ['The wage on a job order must meet the prevailing wage set for that occupation and area', 'Hours, dates and location are on the order itself', 'The employer must not charge you recruitment or visa fees'],
                'url' => $h2b,
                'keywords' => 'landscaper salary usa, landscaping pay, h-2b wage rates, landscaper jobs usa',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Landscaping Employers (Aggregated)', 'usa-landscaping-aggregated'],
                'location' => $usa,
                'position' => 'Landscaper — US Employers (Applying From Pakistan)',
                'intro' => 'A worker in Pakistan cannot apply to the US Government for an H-2B visa alone: the US employer files the petition with USCIS first. USCIS explains each step on its H-2B page.',
                'items' => ['The employer obtains labor certification, then petitions USCIS', 'Only after approval do you apply for the visa at a US embassy or consulate', 'Anyone asking you for a fee to "guarantee" a visa is not following the rules'],
                'url' => $h2bPage,
                'keywords' => 'landscaper job usa from pakistan, h-2b visa pakistan, landscaping visa usa, how to apply h-2b',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Agricultural Employers (Aggregated)', 'usa-farm-aggregated'],
                'location' => $usa,
                'position' => 'Farm Worker — US Farms (Wage Check on H-2A Job Orders)',
                'intro' => 'Each H-2A job order on the US Department of Labor portal states the wage the employer must pay. Read the wage line before you contact an employer.',
                'items' => ['The wage on a job order must meet the wage rate the H-2A programme requires for that area', 'Hours, dates and location are on the order itself', 'The employer must not charge you recruitment or visa fees'],
                'url' => $h2b,
                'keywords' => 'farm worker salary usa, farm worker pay, h-2a wage rates, farm jobs usa',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Agricultural Employers (Aggregated)', 'usa-farm-aggregated'],
                'location' => $usa,
                'position' => 'Farm Worker — US Farms (Applying From Pakistan)',
                'intro' => 'A worker in Pakistan cannot apply to the US Government for an H-2A visa alone: the US employer files the petition with USCIS first. USCIS explains each step on its H-2A page.',
                'items' => ['The employer obtains labor certification, then petitions USCIS', 'Only after approval do you apply for the visa at a US embassy or consulate', 'Anyone asking you for a fee to "guarantee" a visa is not following the rules'],
                'url' => 'https://www.uscis.gov/working-in-the-united-states/temporary-workers/h-2a-temporary-agricultural-workers',
                'keywords' => 'farm worker job usa from pakistan, h-2a visa pakistan, farm work visa usa, how to apply h-2a',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers (Aggregated)', 'canada-employers-aggregated'],
                'location' => $canada,
                'position' => 'Skilled Worker — Canadian Employers (Check Who Can Apply)',
                'intro' => 'Job Bank is the Government of Canada\'s official job site. Each posting states the duties, the wage and who may apply, so you can see whether the employer is open to candidates who need a work permit.',
                'items' => ['You need legal permission to work in Canada, such as a valid work permit, before you can take a job', 'Check the occupation against the official shortage and in-demand lists on the immigration site', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.jobbank.gc.ca/findajob',
                'keywords' => 'countries hiring foreign workers, jobs for foreigners abroad, canada job bank, work permit canada',
                'footer' => 'Canada',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers (Aggregated)', 'canada-employers-aggregated'],
                'location' => $canada,
                'position' => 'Skilled Trades Worker — Canadian Employers (Welders, Electricians, Truck Drivers)',
                'intro' => 'Job Bank is the Government of Canada\'s official job site. It lists trades vacancies such as welders, electricians and truck drivers, with the wage and certification each employer asks for.',
                'items' => ['Trades usually need a recognised certification; check the requirements for your trade before you apply', 'Wages differ by province, experience and certification, and each posting states its own', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.jobbank.gc.ca/findajob',
                'keywords' => 'highest paying blue collar jobs abroad, skilled trades jobs canada, welder jobs canada, electrician jobs abroad',
                'footer' => 'Canada',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers (Aggregated)', 'canada-employers-aggregated'],
                'location' => $canada,
                'position' => 'Entry-Level Worker — Canada (Working Holiday Route, No Experience)',
                'intro' => 'International Experience Canada is the Government of Canada\'s official page for its working holiday and young professional programmes, which let eligible young people work in Canada without years of experience.',
                'items' => ['These programmes are open only to citizens of partner countries and have age limits, so check your passport on the official page first', 'Pakistan is not on the UK Youth Mobility Scheme list, and many working holiday schemes use partner lists, so confirm your own eligibility', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada/iec.html',
                'keywords' => 'job abroad without experience, working holiday visa, international experience canada, entry level jobs abroad',
                'footer' => 'Canada',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Employers (Aggregated)', 'uk-employers-aggregated'],
                'location' => $uk,
                'position' => 'Overseas Job Offer Check — Verify a UK Employer Before You Pay',
                'intro' => 'The UK Government publishes the Register of Licensed Sponsors: the list of employers that are allowed to sponsor skilled workers. If a UK job offer says it comes with sponsorship, check that the employer is on this register before you reply.',
                'items' => ['A sponsor must be on the register, and it still cannot sponsor a role that is not eligible', 'No employer or recruiter can guarantee a visa, and no sponsor should charge you for a Certificate of Sponsorship', 'Never send money, passport scans or bank details until you have verified the employer yourself'],
                'url' => 'https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers',
                'keywords' => 'fake overseas job offer, how to verify a job offer, overseas job scams, register of licensed sponsors',
            ],
            [
                'category' => ['transport-logistics', 'Transport & Logistics'],
                'advertiser' => ['Canadian Trucking and Freight Employers (Aggregated)', 'canada-trucking-aggregated'],
                'location' => $canada,
                'position' => 'Truck Driver — Canadian Employers (Class 1 Licence Needed)',
                'intro' => 'Job Bank is the Government of Canada\'s official job site. It lists truck driver vacancies from Canadian employers, with the licence class, pay and location set out in each posting.',
                'items' => ['You need legal permission to work in Canada, such as a valid work permit, and a provincial heavy-vehicle licence (Class 1 in most provinces)', 'A foreign licence is not accepted as it is, so conversion or testing is part of the process', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.jobbank.gc.ca/findajob',
                'keywords' => 'truck driver jobs abroad, truck driver jobs canada, class 1 driver jobs, job bank truck driver',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Employers (Aggregated)', 'uk-employers-aggregated'],
                'location' => $uk,
                'position' => 'Skilled Worker — UK Employers (Check the Sponsor Route)',
                'intro' => 'Find a job is the UK Government\'s official job search. For a skilled worker, each advert states the role and pay, and you then check whether the employer holds a sponsor licence and whether the job is eligible for the Skilled Worker route.',
                'items' => ['The job must generally be at degree level (RQF Level 6) or on a shortage list, with a salary of at least the general threshold or the going rate', 'An employer on the sponsor register does not sponsor every vacancy it advertises', 'No sponsor may charge you for a Certificate of Sponsorship, and no one can guarantee a visa'],
                'url' => 'https://findajob.dwp.gov.uk/',
                'keywords' => 'visa sponsorship countries, skilled worker visa uk, employer sponsored visa, work visa sponsorship 2026',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Employers (Aggregated)', 'uk-employers-aggregated'],
                'location' => $uk,
                'position' => 'Work Visa Applicant — Apply Without an Agent (UK Skilled Worker Route)',
                'intro' => 'GOV.UK explains, step by step, how a worker applies for a Skilled Worker visa from outside the UK. You apply to the Home Office yourself once an approved employer has given you a Certificate of Sponsorship.',
                'items' => ['The employer must be a licensed sponsor and the job must be eligible; an agent cannot replace either', 'Pay only the official fees through the government payment system', 'No sponsor may charge you for a Certificate of Sponsorship, and no one can guarantee a visa'],
                'url' => 'https://www.gov.uk/skilled-worker-visa/apply-from-outside-the-uk',
                'keywords' => 'how to apply work visa without agent, self apply work visa, work visa application guide, avoid visa agent scam',
            ],
            [
                'category' => ['construction-trades', 'Construction Trades'],
                'advertiser' => ['US Skilled Trades Employers (Aggregated)', 'usa-trades-aggregated'],
                'location' => $usa,
                'position' => 'Skilled Trades Worker — US Employers (Electrician, Plumber, HVAC, Welder)',
                'intro' => 'CareerOneStop is the US Department of Labor job search tool. It lists skilled trades vacancies from US employers, such as electricians, plumbers, HVAC technicians and welders, with the pay and licence each employer asks for.',
                'items' => ['Most states license electricians, plumbers and HVAC technicians, so check the state requirements first', 'You need the right to work in the US; a trade wage benchmark does not create a visa route', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.careeronestop.org/Toolkit/Jobs/find-jobs.aspx',
                'keywords' => 'blue collar jobs that pay well abroad, trade jobs 3000 per month, electrician jobs usa, skilled trade jobs salary abroad',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Employers (Aggregated)', 'uk-employers-aggregated'],
                'location' => $uk,
                'position' => 'Overseas Worker — Documents for a UK Skilled Worker Application',
                'intro' => 'GOV.UK lists the documents you must provide when you apply for a Skilled Worker visa, including the sponsorship reference, your passport and any evidence your route asks for. Use it as a checklist before you start the application.',
                'items' => ['Only the documents your route asks for are needed; check the official list for your case', 'Documents not in English or Welsh need a certified translation', 'Never pay for documents or attestations that the official list does not ask for'],
                'url' => 'https://www.gov.uk/skilled-worker-visa/documents-you-must-provide',
                'keywords' => 'documents needed to work abroad, work abroad checklist, work visa documents list, uk skilled worker documents',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers (Aggregated)', 'canada-employers-aggregated'],
                'location' => $canada,
                'position' => 'Overseas Worker — Canadian Employers Recruiting Internationally',
                'intro' => 'Job Bank is the Government of Canada official job site, and it has a page for candidates outside Canada. Postings show whether an employer is recruiting internationally, and the page links to immigration and credential-recognition information.',
                'items' => ['An employer willing to consider overseas applicants does not guarantee a work permit', 'Check licensing and credential recognition for your occupation before you pay relocation costs', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.jobbank.gc.ca/findajob/foreign-candidates',
                'keywords' => 'best job sites for work abroad, canada job bank foreign candidates, where to find overseas jobs, overseas job portals',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['European Employers (Aggregated)', 'eu-employers-aggregated'],
                'location' => $eu,
                'position' => 'Job Seeker Abroad — European Vacancies (EURES)',
                'intro' => 'EURES is the European job mobility portal. It lists vacancies from employers across Europe with the language, qualification and location requirements for each, so you can tailor your CV to the role before you apply.',
                'items' => ['Applicants from outside the EU or EEA must check immigration eligibility separately', 'Follow the language and formatting instructions in the advert; there is no single mandatory CV format', 'No real employer or agency may charge you a fee for a job'],
                'url' => 'https://eures.europa.eu/index_en',
                'keywords' => 'resume for job abroad, international resume format, cv for overseas job, eures vacancies',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers (Aggregated)', 'canada-employers-aggregated'],
                'location' => $canada,
                'position' => 'Overseas Worker — Apply Directly to Canadian Employers (No Recruiter)',
                'intro' => 'Job Bank is the Government of Canada official job site and has a page for candidates outside Canada. You apply to the employer through the way the posting describes, so no recruiter is needed to start a search.',
                'items' => ['An employer who accepts overseas applicants does not guarantee a work permit', 'Apply only through the method named in the posting and keep a record of each application', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.jobbank.gc.ca/findajob/foreign-candidates',
                'keywords' => 'find job abroad without recruiter, apply overseas job directly, avoid recruitment agency, self apply international job',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['European Employers (Aggregated)', 'eu-employers-aggregated'],
                'location' => $eu,
                'position' => 'Worker Comparing Offers — European Vacancies With Stated Terms (EURES)',
                'intro' => 'EURES lists vacancies from employers across Europe with the pay, location and requirements each one states. Comparing written terms is the first step in deciding whether a move abroad is worth it.',
                'items' => ['Compare take-home pay, housing and relocation costs, not the headline salary alone', 'Applicants from outside the EU or EEA must check immigration eligibility separately', 'No real employer or agency may charge you a fee for a job'],
                'url' => 'https://eures.europa.eu/index_en',
                'keywords' => 'work abroad vs stay home, pros and cons of working abroad, is it worth working overseas, benefits of working in another country',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Employers (Aggregated)', 'uk-employers-aggregated'],
                'location' => $uk,
                'position' => 'Overseas Worker — Check UK Skilled Worker Costs Before You Budget',
                'intro' => 'GOV.UK sets out what a Skilled Worker visa costs, including the application fee, the healthcare surcharge and the money you may need to show. Add these to your budget before you work out what you could save.',
                'items' => ['Costs differ by route and length of stay, so use the official page for your case', 'Ask the employer which costs it pays and get the answer in writing', 'No sponsor may charge you for a Certificate of Sponsorship'],
                'url' => 'https://www.gov.uk/skilled-worker-visa/how-much-it-costs',
                'keywords' => 'how much can you save working abroad, savings working overseas, expat salary savings, money working in gulf countries',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['European Employers (Aggregated)', 'eu-employers-aggregated'],
                'location' => $eu,
                'position' => 'Skilled Worker — Germany Professions in Demand (Make it in Germany)',
                'intro' => 'Make it in Germany is the Federal Government of Germany official portal. Its professions page shows which fields, such as nursing, engineering, IT and skilled crafts, are highlighted and what each one needs to be recognised.',
                'items' => ['A highlighted field does not mean every employer will hire from abroad', 'Check whether your qualification needs recognition before you apply', 'No real employer or agency may charge you a fee for a job or a visa'],
                'url' => 'https://www.make-it-in-germany.com/en/working-in-germany/professions-in-demand',
                'keywords' => 'skills needed to work abroad, in demand skills overseas, skills for international jobs, what skills do employers want abroad',
            ],
            [
                'category' => ['transport-logistics', 'Transport & Logistics'],
                'advertiser' => ['UK Warehouse and Logistics Employers (Aggregated)', 'uk-warehouse-aggregated'],
                'location' => $uk,
                'position' => 'Warehouse Operative — UK Employers (Pay Check)',
                'intro' => 'Find a job is the UK Government\'s official job search. Every advert states its hourly rate; check it against the National Living Wage for your age before you apply.',
                'items' => ['The National Living Wage is the legal minimum for workers aged 21 and over', 'Hours, shifts and location are in each advert', 'No UK employer or agency may charge you a fee for finding work'],
                'url' => 'https://findajob.dwp.gov.uk/',
                'keywords' => 'warehouse operative salary uk, warehouse pay uk, national living wage, warehouse jobs uk',
            ],
        ];

        foreach ($listings as $listing) {
            $category = Category::firstOrCreate(['slug' => $listing['category'][0]], ['name' => $listing['category'][1]]);
            $advertiser = Advertiser::firstOrCreate(
                ['name' => $listing['advertiser'][0]],
                ['type' => 'Private', 'display_reference' => $listing['advertiser'][1]]
            );

            $items = collect($listing['items'])->map(fn (string $item): string => '    <li>'.$item.'</li>')->implode("\n");
            $footer = match ($listing['location']->country) {
                'United Kingdom' => '<p><strong>Note:</strong> pay and visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>',
                'European Union' => '<p><strong>Note:</strong> immigration and employment rules are set by each country, not by JobGader. This link opens an official European portal; it is not an application form, and a visible vacancy does not prove the employer is hiring or recruits from outside Europe.</p>',
                'Canada' => '<p><strong>Note:</strong> immigration and wage rules are set by the Government of Canada and the provinces, not by JobGader. This link opens an official Government of Canada page; it is not an application form, and a visible posting does not prove the employer is hiring.</p>',
                default => $note,
            };

            Job::updateOrCreate(
                ['position' => $listing['position'], 'advertiser_id' => $advertiser->id],
                [
                    'category_id' => $category->id,
                    'location_id' => $listing['location']->id,
                    'description' => '<p>'.$listing['intro']."</p>\n\n<h3>Requirements</h3>\n<ul>\n".$items."\n</ul>\n\n".$footer,
                    'employment_type' => 'Full-time',
                    'job_type' => 'On-site',
                    'work_hours' => 'Set by each employer',
                    'language' => 'English',
                    'salary_currency' => null,
                    'salary_period' => null,
                    'salary_minimum' => null,
                    'salary_maximum' => null,
                    'application_url' => $listing['url'],
                    'meta_description' => mb_substr(strip_tags($listing['intro']), 0, 157).(mb_strlen(strip_tags($listing['intro'])) > 157 ? '...' : ''),
                    'seo_keywords' => $listing['keywords'],
                ]
            );
        }
    }

    private function seedTruckDriver(Location $location): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Trucking and Freight Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'usa-trucking-aggregated']
        );

        Job::updateOrCreate(
            ['position' => 'Truck Driver — US Employers (CDL Roles)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>CareerOneStop is the US Department of Labor's job search tool. It lists truck driver roles from US employers, including CDL Class A and Class B positions.</p>

<h3>Requirements</h3>
<ul>
    <li>A US commercial driver's licence (CDL) of the right class, plus a valid medical card; a foreign licence is not accepted for commercial driving in the US</li>
    <li>Authorisation to work in the US: employers hiring truck drivers do not normally sponsor a work visa, so check each listing before you apply</li>
    <li>English proficiency is required under federal driver rules</li>
</ul>

<p><strong>Note:</strong> licensing and visa rules are set by the US Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible listing does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://www.careeronestop.org/Toolkit/Jobs/find-jobs.aspx',
                'meta_description' => 'Truck driver roles from US employers on the US Department of Labor job search. A US CDL and work authorisation are needed; most employers do not sponsor visas.',
                'seo_keywords' => 'truck driver jobs usa, cdl driver jobs, truck driver visa usa, foreign truck driver usa',
            ]
        );
    }

    private function seedCaregiver(Location $location): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'healthcare'],
            ['name' => 'Healthcare']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'US Home Care and Care Facility Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'usa-caregiver-aggregated']
        );

        Job::updateOrCreate(
            ['position' => 'Caregiver — US Employers (EB-3 Sponsorship Route)', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>US employers who want to sponsor a foreign caregiver for a green card must first obtain a PERM labor certification from the US Department of Labor, then file an immigrant petition for the worker (EB-3). The Department of Labor's FLAG site explains the PERM process for both employers and workers.</p>

<h3>Requirements</h3>
<ul>
    <li>The employer must file first: you cannot apply for an EB-3 visa on your own</li>
    <li>Processing is long, and the wait for a visa number depends on the monthly Visa Bulletin</li>
    <li>No legitimate employer or recruiter charges the worker for the labor certification</li>
</ul>

<p><strong>Note:</strong> immigration rules are set by the US Government, not by JobGader. This link opens the official PERM programme page; it is not a job board or an application form, and no vacancy is implied.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://flag.dol.gov/programs/perm',
                'meta_description' => 'How US employers sponsor a foreign caregiver through the PERM labor certification and EB-3 route, from the US Department of Labor. The employer must file first.',
                'seo_keywords' => 'caregiver jobs usa, eb-3 caregiver sponsorship, perm labor certification, caregiver visa usa',
            ]
        );
    }

    private function seedUkWarehouse(Location $location): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'transport-logistics'],
            ['name' => 'Transport & Logistics']
        );

        $advertiser = Advertiser::firstOrCreate(
            ['name' => 'UK Warehouse and Logistics Employers (Aggregated)'],
            ['type' => 'Private', 'display_reference' => 'uk-warehouse-aggregated']
        );

        Job::updateOrCreate(
            ['position' => 'Warehouse Operative — UK Employers', 'advertiser_id' => $advertiser->id],
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'description' => <<<'JOBHTML'
<p>Find a job is the UK Government's official job search. It lists warehouse operative roles from UK employers, with the pay and location set out in each advert.</p>

<h3>Requirements</h3>
<ul>
    <li>The right to work in the UK: warehouse operative is not on the Skilled Worker route (RQF level 6 from 22 July 2025), so a sponsored visa is not normally available</li>
    <li>Pay is at least the National Living Wage for the worker's age, and each advert states the actual rate</li>
    <li>No UK employer or agency may charge you a fee for finding work</li>
</ul>

<p><strong>Note:</strong> visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>
JOBHTML,
                'employment_type' => 'Full-time',
                'job_type' => 'On-site',
                'work_hours' => 'Set by each employer, often shifts',
                'language' => 'English',
                'salary_currency' => null,
                'salary_period' => null,
                'salary_minimum' => null,
                'salary_maximum' => null,
                'application_url' => 'https://findajob.dwp.gov.uk/',
                'meta_description' => 'Warehouse operative roles from UK employers on the UK Government Find a job service. The right to work is needed; the role is not on the Skilled Worker route.',
                'seo_keywords' => 'warehouse operative jobs uk, warehouse jobs uk visa, uk warehouse salary, find a job uk',
            ]
        );
    }
}
