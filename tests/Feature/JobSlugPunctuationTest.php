<?php

use App\Models\Job;
use Illuminate\Support\Str;

/**
 * Str::slug removes punctuation rather than splitting on it, so a title like
 * "CI/CD" produces the slug word "cicd". The resolver used to look that word
 * up against the untouched column, which excluded the only row that could
 * match — so the page 404'd while its URL stayed in sitemap-jobs.xml.
 */
it('serves a job whose title contains a slash', function () {
    $this->seed(Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class);

    $job = Job::with('location')->where('position', 'like', 'DevOps Engineer%')->firstOrFail();
    $slug = Str::slug($job->position.'-'.($job->location->name ?? ''));

    expect($slug)->toContain('cicd')
        ->and($job->position)->toContain('CI/CD');

    $this->get('/jobs/'.$slug)->assertOk()->assertSee('DevOps Engineer', false);
});

/**
 * This one survived the bug only because the resolver stops after four
 * qualifying tokens and "nextjs" is the fifth. It is here so that widening
 * that cutoff — a reasonable future change — cannot quietly reintroduce the
 * 404 on a punctuated title.
 */
it('serves a job whose title contains a dot', function () {
    $this->seed(Database\Seeders\FrontendDeveloperLahoreSeeder::class);

    $job = Job::with('location')->where('position', 'like', '%Next.js%')->firstOrFail();
    $slug = Str::slug($job->position.'-'.($job->location->name ?? ''));

    expect($slug)->toContain('nextjs')
        ->and($job->position)->toContain('Next.js');

    $this->get('/jobs/'.$slug)->assertOk();
});

it('serves the plain titles it always did', function () {
    $this->seed(Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class);

    $job = Job::with('location')->where('position', 'like', 'Cloud Engineer%')->firstOrFail();

    $this->get('/jobs/'.Str::slug($job->position.'-'.($job->location->name ?? '')))->assertOk();
});

it('still refuses a slug that belongs to no job', function () {
    $this->seed(Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class);

    // Close enough to reach the candidate query, wrong enough to fail the
    // exact comparison — the loosened filter must not turn this into a match.
    $this->get('/jobs/devops-engineer-cicd-platform-and-reliability-us-employers-canada')
        ->assertNotFound();
});

it('resolves every seeded job from the slug the sitemap publishes', function () {
    foreach ([
        Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\AwsCloudEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\AzureCloudEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\NetworkEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\FrontendDeveloperLahoreSeeder::class,
    ] as $seeder) {
        $this->seed($seeder);
    }

    Job::with('location')->get()->each(function (Job $job) {
        $slug = Str::slug($job->position.'-'.($job->location->name ?? ''));

        if ($slug === '') {
            return;
        }

        $this->get('/jobs/'.$slug)->assertOk();
    });
});
