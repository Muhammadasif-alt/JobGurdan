<?php

use App\Models\Blog;
use Database\Seeders\BestCountriesTruckDriversAbroadBlogSeeder;
use Database\Seeders\BestJobSitesWorkAbroadBlogSeeder;
use Database\Seeders\BlueCollarJobsAbroadOver3000BlogSeeder;
use Database\Seeders\CaregiverJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\CleanerJobUkFromPakistanBlogSeeder;
use Database\Seeders\CountriesHiringForeignWorkersBlogSeeder;
use Database\Seeders\DocumentsNeededToWorkAbroadBlogSeeder;
use Database\Seeders\FakeOverseasJobOfferBlogSeeder;
use Database\Seeders\FindJobAbroadWithoutRecruiterBlogSeeder;
use Database\Seeders\HgvDriverJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\HgvDriverJobUkFromPakistanBlogSeeder;
use Database\Seeders\HgvDriverSalaryUkBlogSeeder;
use Database\Seeders\HighestPayingBlueCollarJobsAbroadBlogSeeder;
use Database\Seeders\HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\JobAbroadWithoutExperienceBlogSeeder;
use Database\Seeders\ResumeForJobAbroadBlogSeeder;
use Database\Seeders\SaveMoneyWorkingAbroadBlogSeeder;
use Database\Seeders\TopSkillsJobAbroadFastBlogSeeder;
use Database\Seeders\TruckDriverSalaryUsaBlogSeeder;
use Database\Seeders\VisaSponsorshipCountriesSkilledWorkersBlogSeeder;
use Database\Seeders\WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\WarehouseOperativeSalaryUkBlogSeeder;
use Database\Seeders\WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\WorkAbroadVsStayHomeBlogSeeder;
use Database\Seeders\WorkVisaWithoutAgentBlogSeeder;

dataset('abroadGuides', [
    'countries hiring' => [CountriesHiringForeignWorkersBlogSeeder::class, ['Canada', 'Skills in Demand', '£41,700', '22 July 2025', 'Opportunity Card', 'Pakistan is not on the UK Youth Mobility list']],
    'blue collar' => [HighestPayingBlueCollarJobsAbroadBlogSeeder::class, ['$109,910', '$46,960', 'May 2025', 'PKR 30,774,800', 'Red Seal']],
    'no experience' => [JobAbroadWithoutExperienceBlogSeeder::class, ['jetprogramme.org', 'Pakistan is not on the current list', '6 points', 'A1', 'Pakistan is not on the list of eligible countries']],
    'fake offer' => [FakeOverseasJobOfferBlogSeeder::class, ['reportfraud.police.uk', 'Police Scotland on 101', 'Overseas Employment Promoters', 'beoe.gov.pk', 'Register of Licensed Sponsors']],
    'skilled worker countries' => [VisaSponsorshipCountriesSkilledWorkersBlogSeeder::class, ['€50,700', '€45,934.20', 'Skills in Demand', 'Certificate of Sponsorship', '22 July 2025', 'COMPASS']],
    'no agent' => [WorkVisaWithoutAgentBlogSeeder::class, ['Step 8', 'authorised immigration representative', 'beoe.gov.pk', 'Official Fees']],
    'over 3000' => [BlueCollarJobsAbroadOver3000BlogSeeder::class, ['$31.02', '$5,377', 'PKR 1,505,560', '$17.31', 'May 2025']],
    'documents' => [DocumentsNeededToWorkAbroadBlogSeeder::class, ['DS-160', 'certified translations', 'Ministry of Foreign Affairs', 'Work Abroad Checklist']],
    'job sites' => [BestJobSitesWorkAbroadBlogSeeder::class, ['SeasonalJobs', 'NHS Jobs', 'Overseas Employment Promoters', 'Official (Canada)']],
    'resume' => [ResumeForJobAbroadBlogSeeder::class, ['Europass', 'reverse-chronological', 'YourName_Resume.pdf', '/resume-writing-services']],
    'find without recruiter' => [FindJobAbroadWithoutRecruiterBlogSeeder::class, ['Step 8', 'Workforce Australia', 'SeasonalJobs', 'Overseas Employment Promoters', 'Subject:']],
    'work abroad vs stay home' => [WorkAbroadVsStayHomeBlogSeeder::class, ['$4,000', 'ten months', 'Accredited Employer Work Visa', 'Overseas Employment Promoters']],
    'save money abroad' => [SaveMoneyWorkingAbroadBlogSeeder::class, ['AED 2,700', '$8,400', '$6,400', 'how-much-it-costs', 'Overseas Employment Promoters']],
    'top skills' => [TopSkillsJobAbroadFastBlogSeeder::class, ['professions-in-demand', 'skill-occupation-list', 'Pakistan Nursing Council', 'English language requirement']],
    'truck drivers' => [BestCountriesTruckDriversAbroadBlogSeeder::class, ['Code 95', '$58,640', 'Temporary Shortage List', 'FMCSA', 'Pakistani licence is not accepted']],
]);

const OFFICIAL_HOSTS = [
    'www.jobbank.gc.ca', 'www.canada.ca', 'immi.homeaffairs.gov.au', 'www.immigration.govt.nz', 'www.gov.uk',
    'www.make-it-in-germany.com', 'ind.nl', 'enterprise.gov.ie', 'u.ae', 'www.mom.gov.sg', 'www.moj.go.jp',
    'www.udi.no', 'www.sem.admin.ch', 'jetprogramme.org', 'eures.europa.eu', 'reportfraud.ftc.gov',
    'reportfraud.police.uk', 'antifraudcentre-centreantifraude.ca', 'www.scamwatch.gov.au', 'beoe.gov.pk',
    'www.fmcsa.dot.gov', 'jobsearch.gov.au', 'www.jobs.nhs.uk', 'seasonaljobs.dol.gov', 'travel.state.gov', 'europass.europa.eu', 'nationalcareers.service.gov.uk',
];

it('seeds a published guide within the SEO limits', function (string $seeder) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $seeder::SLUG)->firstOrFail();
    $tags = array_map('trim', explode(',', $blog->tags));

    expect($blog->status)->toBe('published')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($tags)->toHaveCount(8)
        ->and(mb_check_encoding($blog->tags.$blog->meta_title.$blog->meta_description, 'ASCII'))->toBeTrue()
        ->and($blog->featured_image)->toStartWith('blogs/');
})->with('abroadGuides');

it('has eight FAQs and eight People Also Search For entries', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
    [$before, $pasf] = explode('<h2>People Also Search For</h2>', $content);
    [, $faq] = explode('<h2>Frequently Asked Questions</h2>', $before);
    [$pasfBlock] = explode('<h2>More Job Guides</h2>', $pasf);

    expect(substr_count($faq, '<h3>'))->toBe(8)
        ->and(substr_count($pasfBlock, '<h3>'))->toBe(8);
})->with('abroadGuides');

it('links only to JobGader pages and official portals', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
    preg_match_all('/href="([^"]+)"/', $content, $matches);

    foreach ($matches[1] as $href) {
        if (str_starts_with($href, '/')) {
            continue;
        }

        expect(OFFICIAL_HOSTS)->toContain(parse_url($href, PHP_URL_HOST));
    }

    expect(strtolower($content))->not->toContain('indeed.com')
        ->not->toContain('apply now')
        ->not->toContain('add real source');

    if ($seeder !== BestJobSitesWorkAbroadBlogSeeder::class) {
        expect(strtolower($content))->not->toContain('indeed');
    }
})->with('abroadGuides');

it('states the checked facts', function (string $seeder, array $facts) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;

    foreach ($facts as $fact) {
        expect($content)->toContain($fact);
    }
})->with('abroadGuides');

it('renders the guide page and survives a re-seed', function (string $seeder) {
    $this->seed($seeder);
    $this->seed($seeder);

    expect(Blog::where('slug', $seeder::SLUG)->count())->toBe(1);

    $this->get('/blog/'.$seeder::SLUG)->assertOk();
})->with('abroadGuides');

it('cross-links only to guides that exist once they are all seeded', function () {
    $new = [
        CountriesHiringForeignWorkersBlogSeeder::class,
        HighestPayingBlueCollarJobsAbroadBlogSeeder::class,
        JobAbroadWithoutExperienceBlogSeeder::class,
        FakeOverseasJobOfferBlogSeeder::class,
        BestCountriesTruckDriversAbroadBlogSeeder::class,
        VisaSponsorshipCountriesSkilledWorkersBlogSeeder::class,
        WorkVisaWithoutAgentBlogSeeder::class,
        BlueCollarJobsAbroadOver3000BlogSeeder::class,
        DocumentsNeededToWorkAbroadBlogSeeder::class,
        BestJobSitesWorkAbroadBlogSeeder::class,
        ResumeForJobAbroadBlogSeeder::class,
        FindJobAbroadWithoutRecruiterBlogSeeder::class,
        WorkAbroadVsStayHomeBlogSeeder::class,
        SaveMoneyWorkingAbroadBlogSeeder::class,
        TopSkillsJobAbroadFastBlogSeeder::class,
    ];

    foreach ([
        ...$new,
        TruckDriverSalaryUsaBlogSeeder::class,
        CaregiverJobsUsaVisaSponsorshipBlogSeeder::class,
        HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::class,
        WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder::class,
        WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::class,
        WarehouseOperativeSalaryUkBlogSeeder::class,
        HgvDriverJobsUkVisaSponsorshipBlogSeeder::class,
        HgvDriverSalaryUkBlogSeeder::class,
        HgvDriverJobUkFromPakistanBlogSeeder::class,
        CleanerJobUkFromPakistanBlogSeeder::class,
    ] as $seeder) {
        $this->seed($seeder);
    }

    $slugs = Blog::pluck('slug')->all();

    foreach ($new as $seeder) {
        $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
        preg_match_all('#href="/blog/([^"]+)"#', $content, $matches);

        foreach ($matches[1] as $slug) {
            if (in_array($slug, ['carpenter-jobs-in-usa'], true)) {
                continue;
            }

            expect($slugs)->toContain($slug);
        }
    }
});
