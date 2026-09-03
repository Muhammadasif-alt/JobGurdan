<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Which countries the board actually has listings in.
 *
 * Every page used to name "the USA, UK and Pakistan" in hardcoded copy, meta
 * descriptions and Organization schema. The first Saudi listing made all of it
 * wrong at once, in 24 files, with nothing to catch it. Reading the countries
 * from the listings themselves means adding a job in a new country updates the
 * copy, the schema and the FAQs together.
 */
class SiteCoverage
{
    private const CACHE_KEY = 'site.coverage.countries';

    private const CACHE_TTL = 600;

    /** Countries whose full name reads badly in running copy. */
    private const SHORT_NAMES = [
        'United States' => 'USA',
        'United States of America' => 'USA',
        'United Kingdom' => 'UK',
        'United Arab Emirates' => 'UAE',
    ];

    private const NUMBER_WORDS = [
        1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
        6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
    ];

    /**
     * Full country names, busiest first.
     *
     * @return list<string>
     */
    public function countries(): array
    {
        $countries = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): array {
            return DB::table('locations')
                ->join('jobs', 'jobs.location_id', '=', 'locations.id')
                ->where(function ($query) {
                    $query->where('jobs.status', 'active')->orWhereNull('jobs.status');
                })
                ->whereNotNull('locations.country')
                ->where('locations.country', '!=', '')
                ->select('locations.country', DB::raw('COUNT(jobs.id) as job_count'))
                ->groupBy('locations.country')
                ->orderByDesc('job_count')
                ->orderBy('locations.country')
                ->pluck('locations.country')
                ->all();
        });

        // A board with no listings still has to render a sentence.
        return $countries !== [] ? $countries : config('site.fallback_countries');
    }

    public function count(): int
    {
        return count($this->countries());
    }

    /** "Four" — for headings that spell the number out. */
    public function countWord(): string
    {
        $count = $this->count();

        return self::NUMBER_WORDS[$count] ?? (string) $count;
    }

    /**
     * Short names joined for body copy: "USA, UK, Pakistan and Saudi Arabia".
     */
    public function shortList(string $conjunction = 'and'): string
    {
        return $this->join(array_map(
            fn (string $country): string => self::SHORT_NAMES[$country] ?? $country,
            $this->countries()
        ), $conjunction);
    }

    /**
     * Full names joined, for formal copy and schema descriptions.
     */
    public function fullList(string $conjunction = 'and'): string
    {
        return $this->join($this->countries(), $conjunction);
    }

    /**
     * Full names for an Organization areaServed node.
     *
     * @return list<string>
     */
    public function areaServed(): array
    {
        return $this->countries();
    }

    /**
     * Country nodes for an Organization areaServed graph.
     *
     * @return list<array{'@type': string, name: string}>
     */
    public function areaServedNodes(): array
    {
        return array_map(
            fn (string $country): array => ['@type' => 'Country', 'name' => $country],
            $this->countries()
        );
    }

    /** "four" — for headings that run the number into a sentence. */
    public function countWordLower(): string
    {
        return strtolower($this->countWord());
    }

    /**
     * @param  list<string>  $items
     */
    private function join(array $items, string $conjunction): string
    {
        if ($items === []) {
            return '';
        }

        if (count($items) === 1) {
            return $items[0];
        }

        $last = array_pop($items);

        return implode(', ', $items).' '.$conjunction.' '.$last;
    }
}
