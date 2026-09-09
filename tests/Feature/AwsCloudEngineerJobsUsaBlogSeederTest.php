<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\AwsCloudEngineerJobsUsaBlogSeeder;

const AWS_CLOUD_SLUG = 'aws-cloud-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(AwsCloudEngineerJobsUsaBlogSeeder::class);
});

it('publishes the aws guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', AWS_CLOUD_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/aws-cloud-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('aws-cloud-engineer-jobs-in-usa-govcloud.jpg')
        ->and($blog->content)->toContain('aws-cloud-engineer-jobs-in-usa-certification.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        // VARCHAR(255) on MySQL; SQLite ignores the limit, so it is asserted.
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.AWS_CLOUD_SLUG)
        ->assertOk()
        ->assertSee('AWS Cloud Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-aws-cloud-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', AWS_CLOUD_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('AWS GovCloud jobs');

    $this->get('/blog/'.AWS_CLOUD_SLUG)->assertSee('"FAQPage"', false);
});

it('names the contradiction between the two quoted averages instead of picking one', function () {
    // The draft quotes $136,050 nationally and $130,802 for AWS specifically
    // while arguing AWS pays a premium. The second number is the lower one.
    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');

    expect($content)->toContain('$136,050')
        ->toContain('$130,802')
        ->toContain('The AWS-specific number is lower than the general one')
        ->toContain('self-reported submissions');

    // And the honest anchors are the occupations the work is counted under.
    expect($content)->toContain('$99,130')
        ->toContain('$134,050')
        ->toContain('$135,980');
});

it('separates GovCloud eligibility from a security clearance', function () {
    // Two different bars, constantly described as one. A green card holder is
    // eligible for the first and not the second, which changes who applies.
    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');

    expect($content)->toContain('AWS GovCloud (US) requires a US Person')
        ->toContain('lawful permanent resident')
        ->toContain('A security clearance requires US citizenship');

    expect(Job::where('position', 'like', 'AWS Cloud Engineer%')->value('description'))
        ->toContain('A security clearance is a separate and higher bar');
});

it('states what the missing certification prerequisite actually means', function () {
    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');

    expect($content)->toContain('AWS removed prerequisites for its Professional exams in 2018')
        ->toContain('two or more years of hands-on experience')
        ->toContain('an empty gate looks like permission')
        // Named against the two adjacent paths that do enforce an order.
        ->toContain('AZ-305 requires the Azure Administrator Associate')
        ->toContain('will not register you for CKS without an active CKA');
});

it('keeps the hardware role out of the cloud engineering list', function () {
    // The draft lists "AWS Hardware Engineer" among cloud engineering roles.
    // It is data centre hardware work inside Amazon, and someone applying
    // from a cloud background is a mismatch on both sides.
    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');

    expect($content)->toContain('is not a cloud engineering job')
        ->toContain('data centre and hardware roles inside Amazon itself');
});

it('carries no stray non-ASCII in the fields search engines index', function () {
    $blog = Blog::where('slug', AWS_CLOUD_SLUG)->first();

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'AWS Cloud Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-aws-cloud-engineer-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('is wired into the cloud cluster in both directions', function () {
    $this->seed(Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class);

    foreach (['cloud-engineer-jobs-in-usa', 'devops-engineer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.AWS_CLOUD_SLUG);
    }

    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');
    expect($content)->toContain('/blog/cloud-engineer-jobs-in-usa')
        ->toContain('/blog/azure-cloud-engineer-jobs-in-usa')
        ->toContain('/blog/cybersecurity-analyst-jobs-in-usa');
});

it('does not restate the pillar guide it hangs off', function () {
    // The pillar owns the "which occupation is this" argument and the
    // declining sysadmin projection. If this page repeats them the two
    // compete for the same query instead of covering different intents.
    $content = Blog::where('slug', AWS_CLOUD_SLUG)->value('content');

    expect($content)->not->toContain('decline 4 per cent')
        ->not->toContain('13,400 openings')
        ->toContain('/blog/cloud-engineer-jobs-in-usa');
});
