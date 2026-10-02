<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AssistantProfessorJobsUsBlogSeeder;
use Database\Seeders\CollegesHiringProfessorsUsBlogSeeder;
use Database\Seeders\FacultyCareersUsBlogSeeder;
use Database\Seeders\ProfessorEmploymentUsBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const US_PROFESSOR_CLUSTER = [
    'professor-employment-in-the-us' => ProfessorEmploymentUsBlogSeeder::class,
    'assistant-professor-jobs-in-the-us' => AssistantProfessorJobsUsBlogSeeder::class,
    'colleges-hiring-professors-in-the-us' => CollegesHiringProfessorsUsBlogSeeder::class,
];

dataset('us professor cluster', [
    'professor employment' => ['professor-employment-in-the-us', ProfessorEmploymentUsBlogSeeder::class, 'Professor Employment in the US'],
    'assistant professor' => ['assistant-professor-jobs-in-the-us', AssistantProfessorJobsUsBlogSeeder::class, 'Assistant Professor Jobs in the US'],
    'colleges hiring' => ['colleges-hiring-professors-in-the-us', CollegesHiringProfessorsUsBlogSeeder::class, 'Colleges Hiring Professors in the US'],
]);

it('publishes each guide with its banner and SEO fields', function (string $slug, string $seeder) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.$slug.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters on {$slug}");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
})->with('us professor cluster');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $slug, string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('us professor cluster');

it('renders each page with its long-tail sections', function (string $slug, string $seeder, string $heading) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee($heading)
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('us professor cluster');

it('links to no aggregator', function (string $slug, string $seeder) {
    $this->seed($seeder);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', $slug)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'higheredjobs', 'monster', 'chronicle'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
})->with('us professor cluster');

it('cross-links with the faculty hub in both directions', function (string $slug, string $seeder) {
    $this->seed(FacultyCareersUsBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/faculty-careers-in-the-us')
        ->and(Blog::where('slug', 'faculty-careers-in-the-us')->value('content'))
        ->toContain('/blog/'.$slug);
})->with('us professor cluster');

it('cross-links the three cluster pages with each other', function () {
    foreach (US_PROFESSOR_CLUSTER as $seeder) {
        $this->seed($seeder);
    }

    foreach (array_keys(US_PROFESSOR_CLUSTER) as $slug) {
        $content = (string) Blog::where('slug', $slug)->value('content');

        foreach (array_keys(US_PROFESSOR_CLUSTER) as $other) {
            if ($other === $slug) {
                continue;
            }

            expect($content)->toContain('/blog/'.$other);
        }
    }
});

it('states the employment terms the professor draft left out', function (string $claim) {
    $this->seed(ProfessorEmploymentUsBlogSeeder::class);

    expect(Blog::where('slug', 'professor-employment-in-the-us')->value('content'))->toContain($claim);
})->with([
    // The contract length that changes what every advertised salary means.
    'nine month contract' => 'nine-month contracts',
    // AAUP 2025-26 averages by rank.
    'professor average' => '$163,836',
    'associate average' => '$113,427',
    'assistant average' => '$97,232',
    'lecturer average' => '$84,292',
    'instructor average' => '$74,087',
    // Teaching load is the real description of the job.
    'research load' => '2 courses a semester',
    'community college load' => '5 courses a semester',
    // The two promotions work nothing alike.
    'tenure clock' => 'seven years',
    'full professor has no clock' => 'no clock at all',
    // Tenure shares among full-time faculty.
    'tenured share' => '50.7 per cent',
    'non tenure track share' => '31.4 per cent',
    // The transatlantic title trap.
    'uk comparison' => 'Lecturer or Senior Lecturer',
]);

it('states the search mechanics the assistant professor draft left out', function (string $claim) {
    $this->seed(AssistantProfessorJobsUsBlogSeeder::class);

    expect(Blog::where('slug', 'assistant-professor-jobs-in-the-us')->value('content'))->toContain($claim);
})->with([
    'average salary' => '$97,232',
    'hiring calendar' => 'Deadlines cluster from October to December',
    'job talk' => 'job talk',
    'teaching demonstration' => 'teaching demonstration',
    'negotiated once' => 'The Offer Is Negotiated Once',
    'startup funds' => 'Startup funds',
    'third year review' => 'third-year review',
    'external letters' => 'External letters',
    'non tenure titles' => 'Research Assistant Professor',
]);

it('states the institution differences the colleges draft left out', function (string $claim) {
    $this->seed(CollegesHiringProfessorsUsBlogSeeder::class);

    expect(Blog::where('slug', 'colleges-hiring-professors-in-the-us')->value('content'))->toContain($claim);
})->with([
    // BLS medians by institution type.
    'state university' => '$96,120',
    'private university' => '$89,660',
    'local junior college' => '$81,640',
    'state junior college' => '$68,160',
    'the spread' => '$27,960',
    // AAUP per-section rates by institution category.
    'doctoral section rate' => '$5,115',
    'associate no ranks section rate' => '$3,348',
    'all institutions section rate' => '$4,093',
    // The credential rule the draft skipped.
    'credential hours' => 'graduate semester hours in the discipline',
    // Where vacancies really appear.
    'state portals' => 'State system portals',
    // The constraint on online teaching.
    'state authorisation' => 'authorised to operate where the instructor lives',
]);
