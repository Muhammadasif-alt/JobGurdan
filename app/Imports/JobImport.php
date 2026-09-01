<?php

namespace App\Imports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobsImport implements ToModel, WithHeadingRow
{
    /**
     * Map Excel row to Job model.
     * The spreadsheet headers you provided are camelCase (e.g. AdvertiserName).
     * WithHeadingRow those become lowercase keys like 'advertisername'.
     */
    public function model(array $row)
    {
        return new Job([
            'advertiser_name'      => $row['advertisername'] ?? $row['advertiser_name'] ?? null,
            'advertiser_type'      => $row['advertisertype'] ?? $row['advertiser_type'] ?? null,
            'sender_reference'     => $row['senderreference'] ?? $row['sender_reference'] ?? null,
            'display_reference'    => $row['displayreference'] ?? $row['display_reference'] ?? null,
            'classification'       => $row['classification'] ?? null,
            'position'             => $row['position'] ?? null,
            'description'          => $row['description'] ?? null,
            'country'              => $row['country'] ?? null,
            'location'             => $row['location'] ?? null,
            'area'                 => $row['area'] ?? null,
            'postal_code'          => $row['postalcode'] ?? $row['postal_code'] ?? null,
            'application_url'      => $row['applicationurl'] ?? $row['application_url'] ?? null,
            'language'             => $row['language'] ?? null,
            'employment_type'      => $row['employmenttype'] ?? $row['employment_type'] ?? null,
            'work_hours'           => $row['workhours'] ?? $row['work_hours'] ?? null,
            'salary_currency'      => $row['salarycurrency'] ?? $row['salary_currency'] ?? null,
            'salary_period'        => $row['salaryperiod'] ?? $row['salary_period'] ?? null,
            'job_type'             => $row['jobtype'] ?? $row['job_type'] ?? null,
            'sell_price'           => is_numeric($row['sellprice'] ?? $row['sell_price'] ?? null) ? ($row['sellprice'] ?? $row['sell_price']) : null,
            'sell_price_currency'  => $row['sellpricecurrency'] ?? $row['sell_price_currency'] ?? null,
            'revenue_type'         => $row['revenuetype'] ?? $row['revenue_type'] ?? null,
            'salary_minimum'       => is_numeric($row['salaryminimum'] ?? $row['salary_minimum'] ?? null) ? ($row['salaryminimum'] ?? $row['salary_minimum']) : null,
            'salary_maximum'       => is_numeric($row['salarymaximum'] ?? $row['salary_maximum'] ?? null) ? ($row['salarymaximum'] ?? $row['salary_maximum']) : null,
        ]);
    }
}
