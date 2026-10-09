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
        $this->seedSalaryAndPakistanListings($usa, $uk);
    }

    /**
     * Each guide gets its own listing, even when the occupation matches an
     * existing one, so the salary and from-Pakistan guides are not pointed at
     * the visa guide's job.
     */
    private function seedSalaryAndPakistanListings(Location $usa, Location $uk): void
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
            $footer = $listing['location']->country === 'United Kingdom'
                ? '<p><strong>Note:</strong> pay and visa rules are set by the UK Government, not by JobGader. This link opens the official job search; it is not an application form, and a visible advert does not prove the employer is hiring.</p>'
                : $note;

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
