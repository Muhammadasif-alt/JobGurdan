<?php

namespace App\Imports;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /** Hashes seen in the current import batch — prevents duplicates within the same file. */
    protected array $seenHashes = [];

    /** Counters exposed for after-import reporting. */
    public int $imported = 0;
    public int $skipped  = 0;


    /**
     * Map Excel row to Job model and normalize data.
     */
    public function model(array $row)
    {
        // 1) Handle Category from classification
        $classification = trim((string) ($row['classification'] ?? $row['class'] ?? $row['classifcation'] ?? ''));
        $categoryId = null;

        if ($classification !== '') {
            $slug = Str::slug($classification);

            $category = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $classification, 'slug' => $slug]
            );

            $categoryId = $category->id;
        }

        // 2) Handle Advertiser - try to find existing by name or references, otherwise create
        $advertiserName = trim((string) ($row['advertisername'] ?? $row['advertiser_name'] ?? ''));
        $advertiserType = trim((string) ($row['advertisertype'] ?? $row['advertiser_type'] ?? ''));
        $senderReference = trim((string) ($row['senderreference'] ?? $row['sender_reference'] ?? ''));
        $displayReference = trim((string) ($row['displayreference'] ?? $row['display_reference'] ?? ''));
        $advertiserId = null;

        if ($advertiserName !== '' || $senderReference !== '' || $displayReference !== '' || $advertiserType !== '') {
            // Prefer matching by name (case-insensitive), then sender_reference, then display_reference
            $advertiserQuery = Advertiser::query();

            if ($advertiserName !== '') {
                $advertiserQuery->whereRaw('LOWER(name) = ?', [mb_strtolower($advertiserName)]);
            } elseif ($senderReference !== '') {
                $advertiserQuery->where('sender_reference', $senderReference);
            } elseif ($displayReference !== '') {
                $advertiserQuery->where('display_reference', $displayReference);
            }

            $advertiser = $advertiserQuery->first();

            if (!$advertiser) {
                $advertiser = Advertiser::create([
                    'name' => $advertiserName !== '' ? $advertiserName : null,
                    'type' => $advertiserType !== '' ? $advertiserType : null,
                    'sender_reference' => $senderReference !== '' ? $senderReference : null,
                    'display_reference' => $displayReference !== '' ? $displayReference : null,
                ]);
            }

            $advertiserId = $advertiser->id;
        }

        // 3) Handle Location - try to find existing by postal_code or name, otherwise create
        $locationName = trim((string) ($row['location'] ?? ''));
        $area = trim((string) ($row['area'] ?? ''));
        $country = trim((string) ($row['country'] ?? ''));
        $postalCode = trim((string) ($row['postalcode'] ?? $row['postal_code'] ?? ''));
        $locationId = null;

        if ($locationName !== '' || $area !== '' || $country !== '' || $postalCode !== '') {
            // Prefer matching by postal_code (if present), then name (case-insensitive)
            if ($postalCode !== '') {
                $location = Location::where('postal_code', $postalCode)->first();
            } elseif ($locationName !== '') {
                $location = Location::whereRaw('LOWER(name) = ?', [mb_strtolower($locationName)])->first();
            } else {
                $location = null;
            }

            if (!$location) {
                $location = Location::create([
                    'name' => $locationName !== '' ? $locationName : null,
                    'area' => $area !== '' ? $area : null,
                    'country' => $country !== '' ? $country : null,
                    'postal_code' => $postalCode !== '' ? $postalCode : null,
                ]);
            }

            $locationId = $location->id;
        }

        $position = $row['position'] ?? null;

        // === Duplicate guard ===
        $hash = Job::makeDedupeHash($position, $advertiserId, $locationId);

        if ($hash !== null) {
            // Skip if same hash already seen earlier in this same file
            if (isset($this->seenHashes[$hash])) {
                $this->skipped++;
                return null;
            }
            // Skip if a job with this hash already exists in DB
            if (Job::where('dedupe_hash', $hash)->exists()) {
                $this->skipped++;
                return null;
            }
            $this->seenHashes[$hash] = true;
        }

        $this->imported++;

        return new Job([
            'category_id' => $categoryId,
            'advertiser_id' => $advertiserId,
            'location_id' => $locationId,
            'position' => $position,
            'description' => $row['description'] ?? null,
            'language' => $row['language'] ?? null,
            'employment_type' => $row['employmenttype'] ?? $row['employment_type'] ?? null,
            'work_hours' => $row['workhours'] ?? $row['work_hours'] ?? null,
            'salary_currency' => $row['salarycurrency'] ?? $row['salary_currency'] ?? null,
            'salary_period' => $row['salaryperiod'] ?? $row['salary_period'] ?? null,
            'job_type' => $row['jobtype'] ?? $row['job_type'] ?? null,
            'sell_price' => is_numeric($row['sellprice'] ?? $row['sell_price'] ?? null) ? ($row['sellprice'] ?? $row['sell_price']) : null,
            'sell_price_currency' => $row['sellpricecurrency'] ?? $row['sell_price_currency'] ?? null,
            'revenue_type' => $row['revenuetype'] ?? $row['revenue_type'] ?? null,
            'salary_minimum' => is_numeric($row['salaryminimum'] ?? $row['salary_minimum'] ?? null) ? ($row['salaryminimum'] ?? $row['salary_minimum']) : null,
            'salary_maximum' => is_numeric($row['salarymaximum'] ?? $row['salary_maximum'] ?? null) ? ($row['salarymaximum'] ?? $row['salary_maximum']) : null,
            'application_url' => $row['applicationurl'] ?? $row['application_url'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
