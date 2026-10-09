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
