<?php

namespace App\Exports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Job::select([
            'advertiser_name',
            'advertiser_type',
            'sender_reference',
            'display_reference',
            'classification',
            'position',
            'description',
            'country',
            'location',
            'area',
            'postal_code',
            'application_url',
            'language',
            'employment_type',
            'work_hours',
            'salary_currency',
            'salary_period',
            'job_type',
            'sell_price',
            'sell_price_currency',
            'revenue_type',
            'salary_minimum',
            'salary_maximum',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'AdvertiserName',
            'AdvertiserType',
            'SenderReference',
            'DisplayReference',
            'Classification',
            'Position',
            'Description',
            'Country',
            'Location',
            'Area',
            'PostalCode',
            'ApplicationURL',
            'Language',
            'EmploymentType',
            'WorkHours',
            'SalaryCurrency',
            'SalaryPeriod',
            'JobType',
            'SellPrice',
            'SellPriceCurrency',
            'RevenueType',
            'SalaryMinimum',
            'SalaryMaximum',
        ];
    }
}
