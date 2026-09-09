<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\AzureCloudEngineerJobsUsaBlogSeeder;

const AZURE_CLOUD_SLUG = 'azure-cloud-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(AzureCloudEngineerJobsUsaBlogSeeder::class);
});

it('publishes the azure guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', AZURE_CLOUD_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/azure-cloud-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('azure-cloud-engineer-jobs-in-usa-hybrid.jpg')
        ->and($blog->content)->toContain('azure-cloud-engineer-jobs-in-usa-identity.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        // VARCHAR(255) on MySQL; SQLite ignores the limit, so it is asserted.
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.AZURE_CLOUD_SLUG)
        ->assertOk()
        ->assertSee('Azure Cloud Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-azure-cloud-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', AZURE_CLOUD_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('AZ-104 jobs');

    $this->get('/blog/'.AZURE_CLOUD_SLUG)->assertSee('"FAQPage"', false);
});

it('treats the gap between the two published averages as the finding', function () {
    // $169,202 and $145,221 are both given as national averages for one job
    // title. Repeating either without the gap implies a precision neither has.
    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');

    expect($content)->toContain('$169,202')
        ->toContain('$145,221')
        ->toContain('$23,981 apart')
        ->toContain('neither is a measurement');

    expect($content)->toContain('$99,130')
        ->toContain('$134,050')
        ->toContain('$135,980');
});

it('warns that M365 administration is bundled into these engineering titles', function () {
    // The defining hazard of the Azure market, and visible in the job titles
    // the draft itself lists. It sets both the pay band and the next role.
    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');

    expect($content)->toContain('Cloud Engineer (Azure / M365 / Infrastructure)')
        ->toContain('administration rather than engineering')
        ->toContain('what proportion of the week is Microsoft 365 administration');

    expect(Job::where('position', 'like', 'Azure Cloud Engineer%')->value('description'))
        ->toContain('describing at least two jobs');
});

it('gives the AZ exams the order Microsoft actually enforces', function () {
    // The draft lists AZ-104, AZ-400, AZ-305 and AZ-500 flat. Two of them are
    // Expert credentials gated behind an Associate one.
    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');

    expect($content)->toContain('Requires the Azure Administrator Associate certification first')
        ->toContain('Requires either the Azure Administrator Associate or the Azure Developer Associate')
        ->toContain('AZ-104 first');

    // AZ-500 is associate level and must not be described as gated.
    expect($content)->toContain('Also associate level, no prerequisite certification');
});

it('anchors the entry-level figure against the occupation it belongs to', function () {
    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');

    expect($content)->toContain('$79,800')
        ->toContain('$62,640');
});

it('carries no stray non-ASCII in the fields search engines index', function () {
    $blog = Blog::where('slug', AZURE_CLOUD_SLUG)->first();

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Azure Cloud Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-azure-cloud-engineer-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('is wired into the cloud cluster in both directions', function () {
    $this->seed(Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\AwsCloudEngineerJobsUsaBlogSeeder::class);

    foreach (['cloud-engineer-jobs-in-usa', 'devops-engineer-jobs-in-usa', 'aws-cloud-engineer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.AZURE_CLOUD_SLUG);
    }

    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');
    expect($content)->toContain('/blog/cloud-engineer-jobs-in-usa')
        ->toContain('/blog/aws-cloud-engineer-jobs-in-usa')
        ->toContain('/blog/network-engineer-jobs-in-usa');
});

it('does not restate the pillar guide it hangs off', function () {
    $content = Blog::where('slug', AZURE_CLOUD_SLUG)->value('content');

    expect($content)->not->toContain('decline 4 per cent')
        ->not->toContain('13,400 openings')
        ->toContain('/blog/cloud-engineer-jobs-in-usa');
});
