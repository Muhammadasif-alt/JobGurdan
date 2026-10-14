<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Search-result snippet rewrites from the Search Console export (6 months):
 * pages that sit in positions 4 to 12 for their own queries but earn almost
 * no clicks. Each new title carries the phrase people actually search
 * ("salary", "visa sponsorship", the year) and every figure in a description
 * is one the guide itself already states from an official source.
 *
 * Only meta_title and meta_description change; the article body, slug and
 * canonical are untouched. Job pages also have double-encoded ampersands in
 * their stored descriptions repaired. Idempotent.
 */
class GscSnippetRewriteSeeder extends Seeder
{
    /**
     * @return array<string, array{title?: string, description?: string}>
     */
    public static function blogSnippets(): array
    {
        return [
            'welder-jobs-in-canada' => [
                'title' => 'Welder Jobs in Canada 2026: Salary, Red Seal, LMIA',
                'description' => 'Welder salary in Canada by province from Job Bank, where Red Seal is compulsory (Alberta, Quebec), the 125-question exam and LMIA rules for foreign welders.',
            ],
            'bus-driver-jobs-in-canada' => [
                'title' => 'Bus Driver Jobs in Canada 2026: Salary, Licence, LMIA',
                'description' => 'Canada bus driver salary from Job Bank by province, the licence class each province needs, why most roles fall in the low-wage LMIA stream and how to apply.',
            ],
            'dental-assistant-jobs-in-canada' => [
                'title' => 'Dental Assistant Salary in Canada 2026: $27/hr Median',
                'description' => 'Dental assistant salary in Canada: Job Bank median $27 an hour (about $52,650 a year), pay by province, where registration applies and the NDAEB exams.',
            ],
            'janitor-jobs-in-canada' => [
                'title' => 'Janitor Salary in Canada 2026: Pay by Province, LMIA',
                'description' => 'Janitor salary in Canada by province from Job Bank, the minimum wage floor, 2026 job prospects and why the LMIA low-wage limits make the visa route narrow.',
            ],
            'recruiter-jobs-in-canada' => [
                'title' => 'How Much Do Recruiters Make in Canada? 2026 Pay Guide',
                'description' => 'Recruiter pay in Canada: Job Bank national median $33.33 an hour (about $64,994 a year), Toronto $34.87, Ontario licences and pay transparency rules.',
            ],
            'how-to-apply-for-woolworths-supermarket-jobs-in-australia' => [
                'description' => 'Woolworths pay rates 2026: Store Team Member Level 1 was $26.07 an hour under the agreement and is about $28.26 after the 2026 rise (our calculation).',
            ],
            'how-to-apply-for-tesco-supermarket-jobs-in-uk' => [
                'title' => 'Tesco Jobs UK 2026: £13.28 an Hour and How to Apply',
                'description' => 'Tesco store colleagues earn £13.28 an hour at every age, with a 16-hour minimum and an over-18 rule on many adverts. How to apply, with a link that stays live.',
            ],
            'factory-worker-jobs-in-germany' => [
                'title' => 'Factory Worker Jobs in Germany 2026: Salary and Visas',
                'description' => 'Factory worker salary in Germany: minimum wage EUR 13.90 an hour from 2026 (about EUR 2,409 a month gross full-time), pay by job title and the visa route.',
            ],
            'heavy-truck-driver-jobs-in-saudi-arabia' => [
                'title' => 'Truck Driver Jobs in Saudi Arabia: Visa Sponsorship 2026',
                'description' => 'Truck driver jobs in Saudi Arabia with visa sponsorship: iqama and sponsor rules, the licence retest, the SAR 4,000 myth, pay by route and how to apply.',
            ],
            'security-guard-jobs-in-saudi-arabia' => [
                'title' => 'Security Guard Salary in Saudi Arabia: Real Monthly Pay',
                'description' => 'Security guard salary per month in Saudi Arabia: SAR 1,400 to 2,500 base for unarmed guards, SAR 2,000 to 3,500 with allowances, and why SAR 6,500 is no wage.',
            ],
            'driver-jobs-in-saudi-arabia-for-foreigners' => [
                'title' => 'Driver Jobs in Saudi Arabia for Foreigners: Visa, Pay 2026',
                'description' => 'Driver jobs in Saudi Arabia for foreigners: company versus private driver visas, the licence you need, real SAR pay ranges and how to apply with no fee.',
            ],
            'construction-jobs-in-saudi-arabia-with-visa-sponsorship' => [
                'title' => 'Construction Jobs in Saudi Arabia with Visa Sponsorship',
                'description' => 'Construction jobs in Saudi Arabia with visa sponsorship: SAR pay by trade, how the iqama and employer sponsorship work, the midday work ban and how to apply.',
            ],
            'teacher-jobs-in-pakistan' => ['title' => 'Teacher Jobs in Pakistan 2026: Pay, Rules and Grades'],
            'data-entry-jobs-in-pakistan' => ['title' => 'Data Entry Jobs in Pakistan 2026: Pay, BPS Grade, Test'],
            'receptionist-jobs-in-uae' => ['title' => 'Receptionist Jobs in UAE 2026: Pay, Visa and Gratuity'],
            'data-scientist-jobs-in-usa' => ['title' => 'Data Scientist Jobs in USA 2026: Salary and Outlook'],
            'python-developer-jobs-in-usa' => ['title' => 'Python Developer Jobs in USA 2026: Salary and Visas'],
            'wordpress-developer-jobs-in-usa' => ['title' => 'WordPress Developer Jobs in USA 2026: Salary, Remote'],
            'copywriter-jobs-in-usa' => ['title' => 'Copywriter Jobs in USA 2026: Salary and Freelance Rules'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function jobDescriptions(): array
    {
        return [
            'customer-assistant-marks-and-spencer-uk-stores-united-kingdom' => 'Marks & Spencer customer assistant jobs in UK stores: the M&S careers application, the hourly rate from £13.41 (April 2026) and the right-to-work rule.',
        ];
    }

    public function run(): void
    {
        foreach (self::blogSnippets() as $slug => $snippet) {
            $blog = Blog::where('slug', $slug)->first();

            if (! $blog) {
                continue;
            }

            $blog->update(array_filter([
                'meta_title' => $snippet['title'] ?? null,
                'meta_description' => $snippet['description'] ?? null,
            ]));
        }

        $descriptions = self::jobDescriptions();

        Job::with('location')->get()->each(function (Job $job) use ($descriptions): void {
            $slug = Str::slug($job->position.'-'.($job->location->name ?? ''));
            $description = $descriptions[$slug] ?? $job->meta_description;

            if ($description !== null && str_contains($description, '&amp;')) {
                $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5);
            }

            if ($description !== $job->meta_description) {
                $job->update(['meta_description' => $description]);
            }
        });
    }
}
