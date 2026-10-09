<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CleanerJobUkFromPakistanBlogSeeder;
use Database\Seeders\HgvDriverJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\HgvDriverJobUkFromPakistanBlogSeeder;
use Database\Seeders\HgvDriverSalaryUkBlogSeeder;
use Database\Seeders\KitchenPorterJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\KitchenPorterSalaryUkBlogSeeder;
use Database\Seeders\WarehouseWorkerSalaryCanadaBlogSeeder;

dataset('guides', [
    'hgv visa' => [HgvDriverJobsUkVisaSponsorshipBlogSeeder::class, 'HGV Driver — UK Employers (Right to Work Needed)', ['8211', '£41,700', 'Temporary Shortage List', 'Driver CPC']],
    'hgv salary' => [HgvDriverSalaryUkBlogSeeder::class, 'HGV Driver — UK Employers (Pay Check)', ['£2,543', '£12.71', 'PKR 1,171,875', '48 hours']],
    'hgv pakistan' => [HgvDriverJobUkFromPakistanBlogSeeder::class, 'HGV Driver — UK Employers (From Pakistan: Existing Right to Work)', ['Graduate visa', 'Driver CPC', 'digital tachograph', 'Category C+E']],
    'cleaner pakistan' => [CleanerJobUkFromPakistanBlogSeeder::class, 'Cleaner — UK Employers (From Pakistan: Existing Right to Work)', ['£12.71', 'self-employed', 'Youth Mobility', '£41,700']],
    'kitchen porter visa' => [KitchenPorterJobsUkVisaSponsorshipBlogSeeder::class, 'Kitchen Porter — UK Employers (Right to Work Needed)', ['£41,700', 'Register of Licensed Sponsors', 'Youth Mobility', '£12.71']],
    'kitchen porter salary' => [KitchenPorterSalaryUkBlogSeeder::class, 'Kitchen Porter — UK Employers (Pay Check)', ['£12.71', 'PKR 826,125', '£1,880', '5.6 weeks']],
    'canada warehouse' => [WarehouseWorkerSalaryCanadaBlogSeeder::class, 'Warehouse Worker — Canadian Employers (Material Handler)', ['C$22.00', 'C$16.55', 'C$30.29', 'C$17.95']],
]);

function guideSlug(string $seeder): string
{
    return $seeder::SLUG;
}

it('seeds a published guide within the SEO limits', function (string $seeder) {
    $this->seed($seeder);

    $blog = Blog::where('slug', guideSlug($seeder))->firstOrFail();
    $tags = array_map('trim', explode(',', $blog->tags));

    expect($blog->status)->toBe('published')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($tags)->toHaveCount(8)
        ->and($blog->featured_image)->toStartWith('blogs/');
})->with('guides');

it('has eight FAQs and eight People Also Search For entries', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', guideSlug($seeder))->firstOrFail()->content;
    [$before, $pasf] = explode('<h2>People Also Search For</h2>', $content);
    [, $faq] = explode('<h2>Frequently Asked Questions</h2>', $before);
    [$pasfBlock] = explode('<h2>More Job Guides</h2>', $pasf);

    expect(substr_count($faq, '<h3>'))->toBe(8)
        ->and(substr_count($pasfBlock, '<h3>'))->toBe(8);
})->with('guides');

it('links only to JobGader pages and carries no apply buttons', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', guideSlug($seeder))->firstOrFail()->content;
    preg_match_all('/href="([^"]+)"/', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/');
    }

    expect(strtolower($content))->not->toContain('indeed')->not->toContain('apply now');
})->with('guides');

it('states the checked facts', function (string $seeder, string $position, array $facts) {
    $this->seed($seeder);

    $content = Blog::where('slug', guideSlug($seeder))->firstOrFail()->content;

    foreach ($facts as $fact) {
        expect($content)->toContain($fact);
    }
})->with('guides');

it('creates its own job listing without a salary', function (string $seeder, string $position) {
    $this->seed($seeder);

    $job = Job::where('position', $position)->firstOrFail();

    expect($job->application_url)->toStartWith('https://')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->description)->not->toContain('Apply Now')
        ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160);
})->with('guides');

it('renders the guide page and survives a re-seed', function (string $seeder, string $position) {
    $this->seed($seeder);
    $this->seed($seeder);

    expect(Blog::where('slug', guideSlug($seeder))->count())->toBe(1)
        ->and(Job::where('position', $position)->count())->toBe(1);

    $this->get('/blog/'.guideSlug($seeder))->assertOk();
})->with('guides');

it('cross-links only to guides that exist once every guide is seeded', function () {
    foreach ([
        HgvDriverJobsUkVisaSponsorshipBlogSeeder::class,
        HgvDriverSalaryUkBlogSeeder::class,
        HgvDriverJobUkFromPakistanBlogSeeder::class,
        CleanerJobUkFromPakistanBlogSeeder::class,
        KitchenPorterJobsUkVisaSponsorshipBlogSeeder::class,
        KitchenPorterSalaryUkBlogSeeder::class,
        WarehouseWorkerSalaryCanadaBlogSeeder::class,
        \Database\Seeders\TruckDriverSalaryUsaBlogSeeder::class,
        \Database\Seeders\HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::class,
        \Database\Seeders\WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::class,
        \Database\Seeders\WarehouseOperativeSalaryUkBlogSeeder::class,
        \Database\Seeders\WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder::class,
        \Database\Seeders\WarehouseWorkerSalaryUsaBlogSeeder::class,
    ] as $seeder) {
        $this->seed($seeder);
    }

    $slugs = Blog::pluck('slug')->all();

    foreach ([
        HgvDriverJobsUkVisaSponsorshipBlogSeeder::class,
        HgvDriverSalaryUkBlogSeeder::class,
        HgvDriverJobUkFromPakistanBlogSeeder::class,
        CleanerJobUkFromPakistanBlogSeeder::class,
        KitchenPorterJobsUkVisaSponsorshipBlogSeeder::class,
        KitchenPorterSalaryUkBlogSeeder::class,
        WarehouseWorkerSalaryCanadaBlogSeeder::class,
    ] as $seeder) {
        $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
        preg_match_all('#href="/blog/([^"]+)"#', $content, $matches);

        foreach ($matches[1] as $slug) {
            expect($slugs)->toContain($slug);
        }
    }
});
