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
        $uae = Location::firstOrCreate(
            ['name' => 'United Arab Emirates'],
            ['area' => 'Nationwide', 'country' => 'United Arab Emirates']
        );
        $pakistan = Location::firstOrCreate(
            ['name' => 'Pakistan'],
            ['area' => 'Nationwide', 'country' => 'Pakistan']
        );

        $apprenticeNote = '<p><strong>Note:</strong> apprenticeship requirements are set by each training programme, not by JobGader. This link opens an official page; it is not an application form, and nothing on it guarantees a place.</p>';
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
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Remote Employers (Aggregated)', 'usa-remote-aggregated'],
                'location' => $usa,
                'position' => 'Remote Worker in Pakistan — Check US Employer Eligibility (CareerOneStop)',
                'intro' => 'CareerOneStop is the US Department of Labor job search tool. Each posting states its location and work-authorisation wording, which is how a worker in Pakistan can tell a role open to overseas contractors from one limited to people who live in the US.',
                'items' => ['Many US remote postings are open only to people authorised to work in the US', 'Ask for the pay rate and payment method in writing before you start', 'No real employer may charge you a fee for a job, training or equipment'],
                'url' => 'https://www.careeronestop.org/Toolkit/Jobs/find-jobs.aspx',
                'keywords' => 'online jobs in usa for pakistani, remote jobs in usa from pakistan, work from home usa pakistan, remote work for us companies',
            ],
            [
                'category' => ['healthcare', 'Healthcare'],
                'advertiser' => ['UK Health and Care Employers (Aggregated)', 'uk-health-care-aggregated'],
                'location' => $uk,
                'position' => 'Healthcare Assistant — NHS Jobs UK (English Evidence Required)',
                'intro' => 'NHS Jobs is the official recruitment site for NHS organisations in England and Wales. Each advert says whether the employer can sponsor a worker and what English evidence the role needs.',
                'items' => ['Read the person specification and the sponsorship statement on every advert', 'A visa test result and a regulator or employer requirement can be different things', 'No NHS employer charges a candidate to apply'],
                'url' => 'https://www.jobs.nhs.uk',
                'keywords' => 'english requirement working abroad, ielts for work visa, nhs jobs english, healthcare assistant jobs uk',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['US Seasonal and Agricultural Employers (Aggregated)', 'usa-seasonal-aggregated'],
                'location' => $usa,
                'position' => 'Seasonal Worker — US Job Orders (Plan Your Remittances)',
                'intro' => 'Each H-2A and H-2B job order on the US Department of Labor portal states the wage, hours and housing terms. Use it to work out what you can realistically send home before you accept.',
                'items' => ['Wages and deductions are on the job order itself', 'Compare the amount that reaches your family, not only the transfer fee', 'The employer must not charge you recruitment or visa fees'],
                'url' => 'https://seasonaljobs.dol.gov/',
                'keywords' => 'send money home from abroad, seasonal worker usa pay, remittance planning, h-2a wage',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers Hiring Foreign Workers (Aggregated)', 'canada-foreign-workers-aggregated'],
                'location' => $canada,
                'position' => 'Work Permit Holder — Canada Job Bank (Employer-Specific vs Open Permit)',
                'intro' => 'Job Bank is the Government of Canada job search. Postings state whether a role is open to international candidates, which matters because an employer-specific permit ties you to one employer and an open permit does not.',
                'items' => ['Check which permit category the role needs before you apply', 'A job offer is not a work permit', 'A real employer does not charge you to be hired'],
                'url' => 'https://www.jobbank.gc.ca/findajob',
                'keywords' => 'what is a work permit, open work permit canada, employer specific work permit, job bank canada',
            ],
            [
                'category' => ['healthcare', 'Healthcare'],
                'advertiser' => ['UK Health and Care Employers (Aggregated)', 'uk-health-care-aggregated'],
                'location' => $uk,
                'position' => 'Care Worker — UK Licensed Sponsor Register Check (GOV.UK)',
                'intro' => 'The Home Office publishes the register of licensed sponsors. An employer on it may sponsor a worker, but the register does not list vacancies and a licence does not mean every role is sponsored.',
                'items' => ['Search the legal name of the employer, not a brand name', 'Care workers and senior care workers generally cannot bring dependants', 'Ask the employer whether this specific role is sponsored'],
                'url' => 'https://www.gov.uk/government/publications/register-of-licensed-sponsors-workers',
                'keywords' => 'what is visa sponsorship, uk licensed sponsor register, care worker visa sponsorship, sponsored work visa',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Skilled Worker Employers (Aggregated)', 'uk-skilled-worker-aggregated'],
                'location' => $uk,
                'position' => 'Skilled Worker — UK Overseas Application and Decision Time (GOV.UK)',
                'intro' => 'GOV.UK explains how to apply for a Skilled Worker visa from outside the UK and says a decision usually comes within 3 weeks once you have applied, proved your identity and provided your documents.',
                'items' => ['The estimate starts after your application and documents, not at the job offer', 'Verification or an interview can make it longer', 'Do not resign or book non-refundable travel before the decision'],
                'url' => 'https://www.gov.uk/skilled-worker-visa/apply-from-outside-the-uk',
                'keywords' => 'how long does work visa take, uk skilled worker processing time, work visa timeline, uk visa decision',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers Hiring Foreign Workers (Aggregated)', 'canada-foreign-workers-aggregated'],
                'location' => $canada,
                'position' => 'Skilled Worker in Canada — Spouse Open Work Permit Eligibility (IRCC)',
                'intro' => 'IRCC sets out when the spouse or partner and children of a foreign worker in Canada can apply for a work or study permit. Eligibility depends on the worker\'s own situation and not every spouse qualifies.',
                'items' => ['Joining as a visitor is different permission from working', 'Each family member applies and is decided separately', 'Old summaries that promise open permits to all spouses are out of date'],
                'url' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/work-canada/special-instructions/spouses-dependent-children/eligibility.html',
                'keywords' => 'bring family while working abroad, spouse open work permit canada, dependent visa for spouse, family work permit',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Skilled Worker Employers (Aggregated)', 'uk-skilled-worker-aggregated'],
                'location' => $uk,
                'position' => 'Worker Changing Employer — UK Skilled Worker Update Your Visa (GOV.UK)',
                'intro' => 'GOV.UK explains when a Skilled Worker must update their visa after a change of job or employer, including what to do if a sponsor loses its licence.',
                'items' => ['A new offer does not by itself authorise you to start the job', 'If a sponsor loses its licence, the visa is limited to 60 days or the time left if shorter', 'Keep your termination notice and contract'],
                'url' => 'https://www.gov.uk/skilled-worker-visa/update-your-visa-if-you-change-job-or-employer',
                'keywords' => 'employer cancels work visa, sponsor cancels visa, change employer uk visa, work visa problems abroad',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Canadian Employers Hiring Foreign Workers (Aggregated)', 'canada-foreign-workers-aggregated'],
                'location' => $canada,
                'position' => 'Overseas Applicant — Job Bank Foreign Candidates Search (Government of Canada)',
                'intro' => 'Job Bank lets you search for jobs from employers who are recruiting foreign candidates from outside Canada, so you can target roles that accept overseas applicants.',
                'items' => ['Read the eligibility wording on each posting', 'Tailor your CV to the duties and keep dates consistent', 'Follow the application instructions in the posting exactly'],
                'url' => 'https://www.jobbank.gc.ca/findajob/foreign-candidates',
                'keywords' => 'mistakes when applying jobs abroad, jobs for foreign candidates canada, job abroad application tips, overseas applicant',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Skilled Worker Employers (Aggregated)', 'uk-skilled-worker-aggregated'],
                'location' => $uk,
                'position' => 'Overseas Offer Check — UK Companies House Company Search',
                'intro' => 'Companies House shows whether a UK company exists and who is registered. It is one check on an overseas job offer; it does not prove the sender is genuine or that the vacancy exists.',
                'items' => ['Contact the employer through details you found yourself', 'Check the sponsor register separately if sponsorship is claimed', 'Never pay to receive a job offer'],
                'url' => 'https://find-and-update.company-information.service.gov.uk/',
                'keywords' => 'how to verify overseas job offer, check if job offer is real, verify foreign employer, companies house search',
            ],
            [
                'category' => ['education-training', 'Education & Training'],
                'advertiser' => ['UK Careers and Training Services (Aggregated)', 'uk-careers-training-aggregated'],
                'location' => $uk,
                'position' => 'Skills Learner — National Careers Service Courses and Advice (UK)',
                'intro' => 'The National Careers Service gives free careers advice and course information. Use it to see which skills employers ask for before you choose a free online course.',
                'items' => ['Match a course to the requirements in real job adverts', 'A certificate is not a job, a visa or a licence', 'Check any exam fee before you enrol'],
                'url' => 'https://nationalcareers.service.gov.uk/',
                'keywords' => 'free courses to get job abroad, online courses for overseas jobs, skills courses, careers advice',
            ],
            [
                'category' => ['education-training', 'Education & Training'],
                'advertiser' => ['UK Careers and Training Services (Aggregated)', 'uk-careers-training-aggregated'],
                'location' => $uk,
                'position' => 'Interview Candidate — National Careers Service Interview Advice (UK)',
                'intro' => 'The National Careers Service publishes free interview advice covering what to expect, how to prepare and how to answer common questions.',
                'items' => ['Confirm the time zone and the format with the employer', 'Use real examples that you can explain', 'Never pay a fee to be interviewed'],
                'url' => 'https://nationalcareers.service.gov.uk/careers-advice/interview-advice',
                'keywords' => 'online job interview abroad tips, video interview for overseas job, interview preparation, remote interview tips',
            ],
            [
                'category' => ['engineering', 'Engineering'],
                'advertiser' => ['European Skilled Worker Employers (Aggregated)', 'eu-skilled-worker-aggregated'],
                'location' => $eu,
                'position' => 'Qualified Professional — Germany EU Blue Card Route (Make it in Germany)',
                'intro' => 'Make it in Germany is the Federal Government portal. Its EU Blue Card page sets out the qualification, job and salary conditions for a residence title for qualified employment.',
                'items' => ['A degree alone is not enough: the job offer and salary threshold also count', 'An entry visa and a residence title can be different stages', 'The portal lists routes and vacancies; it does not guarantee a job'],
                'url' => 'https://www.make-it-in-germany.com/en/visa-residence/types/eu-blue-card',
                'keywords' => 'difference between work visa and work permit, eu blue card germany, work visa vs work permit, germany work visa',
            ],
            [
                'category' => ['construction', 'Construction'],
                'advertiser' => ['UAE Skilled Trade Employers (Aggregated)', 'uae-skilled-trade-aggregated'],
                'location' => $uae,
                'position' => 'Skilled Tradesperson — UAE Employment and Work Permit Information (UAE Government)',
                'intro' => 'The UAE Government portal explains employment in the private sector, including work permits. Use it to understand the process before you accept an offer from a Gulf employer.',
                'items' => ['There is no single Gulf trade visa: each country has its own procedure', 'The employer arranges the permit; you should not pay for a job', 'Verify the employer and the contract before you travel'],
                'url' => 'https://u.ae/en/information-and-services/jobs',
                'keywords' => 'best trade jobs gulf countries, skilled jobs uae, trade worker gulf visa, uae work permit',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['Overseas Recruitment Services Pakistan (Aggregated)', 'pakistan-overseas-recruitment-aggregated'],
                'location' => $pakistan,
                'position' => 'Overseas Job Seeker — Check a Licensed Promoter (BEOE Pakistan)',
                'intro' => 'The Bureau of Emigration and Overseas Employment publishes the list of licensed Overseas Employment Promoters. Check an agency here before you pay anyone or hand over documents.',
                'items' => ['A licence number in a message does not prove the sender works for that agency', 'Confirm the vacancy with the employer through its own website', 'Report fraud to the Bureau and to your bank at once'],
                'url' => 'https://beoe.gov.pk',
                'keywords' => 'fake job agents, verify recruitment agency pakistan, overseas employment promoter, beoe licence check',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UK Skilled Worker Employers (Aggregated)', 'uk-skilled-worker-aggregated'],
                'location' => $uk,
                'position' => 'Worker — UK Employment Status and Rights (GOV.UK)',
                'intro' => 'GOV.UK explains the difference between employee, worker and self-employed status and the rights each carries, including pay, holiday and protection from discrimination.',
                'items' => ['A contract that says self-employed does not settle your status', 'Keep payslips, contracts and a record of hours', 'Your visa conditions and employment rights are separate matters'],
                'url' => 'https://www.gov.uk/employment-status/worker',
                'keywords' => 'rights of foreign workers, expat worker rights, what rights do overseas workers have, uk worker rights',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['European Skilled Worker Employers (Aggregated)', 'eu-skilled-worker-aggregated'],
                'location' => $eu,
                'position' => 'Non-EU Worker — EURES Jobseekers Portal (European Union)',
                'intro' => 'EURES is the European job mobility portal. It lists vacancies across Europe, but a listing does not mean an employer will hire or sponsor a non-EU applicant, so read each requirement.',
                'items' => ['Check whether the employer needs existing work rights or local language', 'Your permit comes from the destination country, not from EURES', 'No genuine employer charges a fee for a job'],
                'url' => 'https://eures.europa.eu/jobseekers_en',
                'keywords' => 'job in europe non eu citizen, work permit europe for foreigners, how to work in europe, eures jobs',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['UAE Skilled Trade Employers (Aggregated)', 'uae-skilled-trade-aggregated'],
                'location' => $uae,
                'position' => 'Expat Worker — UAE Golden Visa Eligibility (UAE Government)',
                'intro' => 'The UAE Government portal lists who can apply for the Golden visa, a long-term residence for eligible categories. It is not available to every employee and it is not citizenship.',
                'items' => ['Check the category and the conditions on the official page', 'A Golden visa is separate from your employment contract and work permit', 'Nobody can guarantee eligibility for a fee'],
                'url' => 'https://u.ae/en/information-and-services/visa-and-emirates-id/residence-visas/golden-visa',
                'keywords' => 'gulf vs europe for work, uae golden visa, better to work in uae or uk, expat life gulf',
            ],
            [
                'category' => ['general-labour', 'General Labour'],
                'advertiser' => ['European Skilled Worker Employers (Aggregated)', 'eu-skilled-worker-aggregated'],
                'location' => $eu,
                'position' => 'Relocating Worker — EU Immigration Portal Route Check (European Commission)',
                'intro' => 'The EU Immigration Portal gives country-specific information on working and living in the European Union, which helps you shortlist routes before you start applying.',
                'items' => ['Each member state makes its own immigration decisions', 'An offer in one country does not authorise work in another', 'Verify any recruiter before you pay'],
                'url' => 'https://home-affairs.ec.europa.eu/policies/migration-and-asylum/eu-immigration-portal_en',
                'keywords' => 'how to move abroad for work, relocate for job abroad, moving abroad checklist, eu immigration portal',
            ],
            [
                'category' => ['agriculture', 'Agriculture'],
                'advertiser' => ['US Seasonal and Agricultural Employers (Aggregated)', 'usa-seasonal-aggregated'],
                'location' => $usa,
                'position' => 'Farm Worker — US H-2A Job Orders (Housing and Meals Terms)',
                'intro' => 'Each H-2A job order states the housing and meal arrangements. Employers must provide housing without charge to covered workers; meals are either provided at an allowed charge or the worker has cooking facilities.',
                'items' => ['Read the meal charge and the housing description on the order', 'Housing free does not mean food free', 'The employer must not charge you recruitment or visa fees'],
                'url' => 'https://seasonaljobs.dol.gov/',
                'keywords' => 'jobs abroad with free accommodation, farm worker housing usa, h-2a housing and meals, live in jobs abroad',
            ],
            [
                'category' => ['construction', 'Construction'],
                'advertiser' => ['US Skilled Trades Employers (Aggregated)', 'usa-skilled-trades-aggregated'],
                'location' => $usa,
                'position' => 'Electrician — US Employers (CareerOneStop Job Search)',
                'intro' => 'CareerOneStop, from the US Department of Labor, lists electrician and other skilled trade roles with the pay data and licensing notes for each state. Most roles need local licensing and US work authorisation.',
                'items' => ['Electrical work is licensed state by state', 'Most US employers do not sponsor a work visa for trade roles', 'Compare the pay data with the cost of living in the state'],
                'url' => 'https://www.careeronestop.org/Toolkit/Jobs/find-jobs.aspx',
                'keywords' => 'jobs abroad without degree, electrician jobs usa, no degree jobs overseas, skilled trades usa pay',
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Ironworker Apprentice — Ironworkers Apprenticeship Information (Iron Workers International)',
                'intro' => 'Iron Workers International is the national union body. Its site is the starting point for finding a local, because each local sets whether an aptitude test is used and what it covers.',
                'items' => ['Ask your local for the current test format and sample material', 'Some locals use a maths and mechanical test with a minimum score; others use none', 'Never pay anyone for a guaranteed place'],
                'url' => 'https://www.ironworkers.org',
                'keywords' => 'ironworker aptitude test, ironworker apprenticeship, ironworkers local, ironworker apprentice jobs usa',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Elevator Constructor Apprentice — NEIEP Recruitment Opportunities',
                'intro' => 'NEIEP lists current recruitment opportunities for IUEC elevator constructor apprenticeships. Applicants take the Elevator Industry Aptitude Test and a score of 70% or higher leads to an interview.',
                'items' => ['Applicants must be at least 17 to apply and 18 to be registered', 'A high school diploma or GED is needed at the time of hire', 'Applicants must be authorised to work in the United States'],
                'url' => 'https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/',
                'keywords' => 'elevator apprenticeship test, neiep recruitment, eiat, elevator constructor apprentice',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Sheet Metal Apprentice — International Training Institute Apprenticeship Information',
                'intro' => 'The International Training Institute explains sheet metal apprenticeship and the steps to apply. Testing is run by each local JATC, so the sections and pass mark differ.',
                'items' => ['Contact your local JATC for the test sections and timing', 'Some programmes are untimed and others use a timed maths test', 'A test result does not guarantee a place'],
                'url' => 'https://www.sheetmetal-iti.org/apprenticeship',
                'keywords' => 'sheet metal apprenticeship test, sheet metal workers aptitude test, sheet metal jatc, sheet metal apprentice',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Plumber and Pipefitter Apprentice — United Association Apprenticeship',
                'intro' => 'The United Association describes its paid apprenticeships, which combine on-the-job training with union contractors and classroom instruction. Applicants typically need to be 18 with a high school diploma or GED.',
                'items' => ['The process may include an interview, an aptitude test and physical requirements', 'The test and pass mark are set by each local', 'Ask your local for approved study material'],
                'url' => 'https://ua.org/career-paths/apprentice/',
                'keywords' => 'ua plumber apprenticeship test, pipefitter apprenticeship, steamfitter apprentice, ua apprenticeship',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Boilermaker Apprentice — Boilermakers Recruiting Information',
                'intro' => 'The International Brotherhood of Boilermakers lists recruiting and apprenticeship information. Requirements depend on the local lodge and region, and some programmes use no written test.',
                'items' => ['Check age, education and any welding experience groups for your local', 'Travel and demanding physical work are normal parts of the trade', 'Never pay for a guaranteed apprenticeship place'],
                'url' => 'https://boilermakers.org/recruiting',
                'keywords' => 'boilermaker apprenticeship requirements, boilermaker entrance test, boilermaker apprentice, boilermakers recruiting',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Construction Laborer Apprentice — LiUNA Training Centers',
                'intro' => 'The LiUNA Training and Education Fund runs registered apprenticeship and pre-apprenticeship programmes and lists training centres across the United States and Canada.',
                'items' => ['Entry rules are set by each local and training centre', 'Many programmes ask for a minimum age, a drug test and reliable transport', 'No experience is required in some programmes'],
                'url' => 'https://www.liunatraining.org',
                'keywords' => 'laborers union entrance exam, liuna apprenticeship, construction laborer apprentice, laborers training',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Operating Engineer Apprentice — IUOE Local Apprenticeship Information',
                'intro' => 'The International Union of Operating Engineers is the starting point for finding the local that runs an operator apprenticeship. Each local sets its own application, evaluation and testing.',
                'items' => ['Locals differ: some use an entrance exam and others a skills evaluation and interview', 'Typical requirements include age 18 and a diploma or GED', 'Apprenticeships usually involve 6,000 hours or more'],
                'url' => 'https://www.iuoe.org',
                'keywords' => 'operating engineers apprenticeship test, iuoe apprenticeship, heavy equipment operator apprentice, equipment operator apprenticeship',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Apprenticeship Applicant — Apprenticeship.gov Program Information',
                'intro' => 'Apprenticeship.gov is the US Department of Labor site for registered apprenticeships. It explains how programmes work and how to find one, but each programme sets its own test.',
                'items' => ['Confirm what maths and reading the programme tests', 'Practise fractions, percentages, conversions and basic algebra', 'Verify any fee directly with the programme'],
                'url' => 'https://www.apprenticeship.gov',
                'keywords' => 'apprenticeship test math, apprenticeship math practice, apprenticeship programs usa, registered apprenticeship',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Maintenance Technician Apprentice — Apprenticeship.gov Program Finder',
                'intro' => 'Apprenticeship.gov helps you find registered apprenticeship programmes for maintenance and mechanical trades, where entrance tests often include mechanical reasoning.',
                'items' => ['Mechanical reasoning covers levers, pulleys, gears and hydraulics', 'The test format is set by each programme', 'Practise with diagrams, not only reading'],
                'url' => 'https://www.apprenticeship.gov',
                'keywords' => 'mechanical reasoning questions, mechanical aptitude test, maintenance apprentice, mechanical apprenticeship',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Reapplying Apprentice — Apprenticeship.gov Programme Information',
                'intro' => 'If a test does not go well, retake rules depend on the programme: some allow a retest after days, others only in the next recruitment period. Apprenticeship.gov helps you find other programmes meanwhile.',
                'items' => ['Ask for the retake rule in writing', 'Use the waiting time to practise your weakest section', 'Never pay to skip a waiting period'],
                'url' => 'https://www.apprenticeship.gov',
                'keywords' => 'retake apprenticeship aptitude test, apprenticeship retake rules, reapply apprenticeship, apprenticeship waiting period',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Apprenticeship Applicant — Check Selection Criteria (Apprenticeship.gov)',
                'intro' => 'Selection criteria differ: some programmes use a fixed pass mark such as 70%, others rank applicants by score or by interview. Check the programme\'s own rule before you prepare.',
                'items' => ['Ask whether selection is pass/fail or ranked', 'Aim comfortably above any published cutoff', 'The interview can decide the final ranking'],
                'url' => 'https://www.apprenticeship.gov',
                'keywords' => 'what score do you need apprenticeship test, apprenticeship passing score, ranked selection, apprenticeship interview',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Apprenticeship Test Candidate — OSHA Training Information (OSHA)',
                'intro' => 'OSHA publishes information on safety training, including 10-hour courses that show safety awareness on an apprenticeship application while you prepare for your test.',
                'items' => ['A safety card does not replace an aptitude test', 'Use the final week to review your weakest topic', 'Confirm your test documents and identification'],
                'url' => 'https://www.osha.gov/training',
                'keywords' => 'one week before apprenticeship test, apprenticeship test study plan, osha 10 hour, apprenticeship test checklist',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Apprenticeship Applicant — Apprenticeship.gov Job Finder (Check Entry Requirements)',
                'intro' => 'Apprenticeship.gov lists registered apprenticeship openings across the United States. Each listing leads to the employer or sponsor, whose own notice sets the entry requirements, documents, test and deadline.',
                'items' => ['Read the current recruitment notice before ordering documents', 'Some programmes ask for official transcripts and algebra', 'Never pay anyone for a guaranteed place'],
                'url' => 'https://www.apprenticeship.gov/apprenticeship-job-finder',
                'keywords' => 'how to apply for a trade apprenticeship, trade apprenticeship requirements, apprenticeship application process, apply for apprenticeship without experience',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Elevator Constructor Applicant — NEIEP Ranked Hiring List Information',
                'intro' => 'NEIEP explains that elevator apprentice candidates are ranked by interview score and that companies with open positions hire off the recruitment list in ranked order. Rank stays valid for two years or until the list is exhausted.',
                'items' => ['A minimum interview score of 70% earns a rank on the local hiring list', 'Your position can move as people are hired or new candidates are interviewed', 'A ranking does not guarantee an offer or a start date'],
                'url' => 'https://www.neiep.org/elevator-apprenticeship-recruitment-opportunities/',
                'keywords' => 'how apprenticeship ranking lists work, apprenticeship ranked list, apprenticeship interview score, apprenticeship waiting list',
                'note' => $apprenticeNote,
            ],
            [
                'category' => ['construction-trades', 'Construction & Trades'],
                'advertiser' => ['US Building Trades Apprenticeships (Aggregated)', 'usa-building-trades-aggregated'],
                'location' => $usa,
                'position' => 'Construction Laborer Applicant — Laborers Training School Waiting List Information',
                'intro' => 'The Laborers Training School in Azusa, California explains how its apprenticeship waiting list works. Entry assessments are scheduled by local union need, and applicants must check in during set months to keep an application on file.',
                'items' => ['Check-in is required in March, June, October and December', 'Missing a required month results in the application being deleted', 'Never pay anyone to move you up a waiting list'],
                'url' => 'https://www.laborerstrainingschool.com/applicant',
                'keywords' => 'union apprenticeship waitlists, how long is an apprenticeship waiting list, apprenticeship check-in, laborers training school waiting list',
                'note' => $apprenticeNote,
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
            $footer = $listing['note'] ?? match ($listing['location']->country) {
                'United Kingdom' => '<p><strong>Note:</strong> pay and visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>',
                'European Union' => '<p><strong>Note:</strong> immigration and employment rules are set by each country, not by JobGader. This link opens an official European portal; it is not an application form, and a visible vacancy does not prove the employer is hiring or recruits from outside Europe.</p>',
                'Canada' => '<p><strong>Note:</strong> immigration and wage rules are set by the Government of Canada and the provinces, not by JobGader. This link opens an official Government of Canada page; it is not an application form, and a visible posting does not prove the employer is hiring.</p>',
                'United Arab Emirates' => '<p><strong>Note:</strong> employment and residence rules are set by the UAE Government, not by JobGader. This link opens an official UAE Government page; it is not an application form, and nothing on it guarantees a job, sponsorship or visa.</p>',
                'Pakistan' => '<p><strong>Note:</strong> recruitment licensing is set by the Government of Pakistan, not by JobGader. This link opens an official Government of Pakistan page; it is not an application form and not a job offer.</p>',
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
