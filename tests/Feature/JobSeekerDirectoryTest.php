<?php

use App\Http\Controllers\Public\JobSeekerPublicController;
use App\Models\User;
use Database\Seeders\RealJobSeekersSeeder;

use function Pest\Laravel\get;

/** Build a seeker with only the columns the test cares about. */
function seeker(array $attributes = []): User
{
    return User::create(array_merge([
        'name' => 'Test Candidate',
        'username' => 'test.candidate',
        'email' => 'test.candidate@example.test',
        'password' => 'secret-password',
        'role' => User::ROLE_JOB_SEEKER,
        'is_active' => true,
    ], $attributes));
}

it('shows the profile the candidate wrote rather than one generated from their id', function () {
    $user = seeker([
        'headline' => 'Technical SEO executive',
        'skills' => 'Keyword Research, Link Building, Schema Markup',
        'preferred_city' => 'Bahawalpur, Pakistan',
        'experience_years' => 3,
        'open_to' => 'Full-time',
    ]);

    $profile = JobSeekerPublicController::profileFor($user);

    expect($profile['headline'])->toBe('Technical SEO executive')
        ->and($profile['city'])->toBe('Bahawalpur, Pakistan')
        ->and($profile['experience_years'])->toBe(3)
        ->and($profile['skills'])->toBe(['Keyword Research', 'Link Building', 'Schema Markup']);
});

it('leaves fields empty instead of inventing a headline, city or experience', function () {
    // The directory used to pick these from a bank of US templates keyed on
    // crc32($user->id): a Lahore developer could be shown as a nurse in Miami.
    $profile = JobSeekerPublicController::profileFor(seeker());

    expect($profile['headline'])->toBeNull()
        ->and($profile['city'])->toBeNull()
        ->and($profile['experience_years'])->toBeNull()
        ->and($profile['open_to'])->toBeNull()
        ->and($profile['skills'])->toBe([]);
});

it('renders a bare profile without claiming an experience level or location', function () {
    seeker();

    get('/job-seekers/test.candidate')->assertOk()
        ->assertSee('Test Candidate')
        ->assertDontSee('yrs experience')
        ->assertDontSee('year veteran', false)
        ->assertDontSee('Proven track record in', false);
});

it('keeps a thin profile out of the search index and lets a full one in', function () {
    $thin = seeker(['headline' => 'Developer']);

    expect($thin->hasPublishableProfile())->toBeFalse();

    get('/job-seekers/test.candidate')->assertSee('name="robots" content="noindex, follow"', false);

    $thin->update([
        'skills' => 'PHP, Laravel, MySQL',
        'links' => ['LinkedIn' => 'https://www.linkedin.com/in/example/'],
    ]);

    expect($thin->fresh()->hasPublishableProfile())->toBeTrue();

    get('/job-seekers/test.candidate')->assertSee('name="robots" content="index, follow"', false);
});

it('splits skills typed with any separator the profile form allows', function () {
    expect(seeker(['skills' => "PHP, Laravel | React\nMySQL /  Redis "])->skillList())
        ->toBe(['PHP', 'Laravel', 'React', 'MySQL', 'Redis']);
});

it('marks outbound candidate links so they are not treated as endorsements', function () {
    seeker(['links' => ['LinkedIn' => 'https://www.linkedin.com/in/example/']]);

    get('/job-seekers/test.candidate')
        ->assertSee('rel="nofollow noopener ugc"', false);
});

it('finds candidates by skill, which is what the page promises', function () {
    seeker(['skills' => 'Schema Markup, Link Building']);
    seeker(['name' => 'Other Person', 'username' => 'other.person', 'email' => 'other@example.test', 'skills' => 'Forklift']);

    get('/job-seekers?q=Schema')->assertOk()
        ->assertSee('Test Candidate')
        ->assertDontSee('Other Person');
});

it('seeds only consenting candidates, with no password and no invented detail', function () {
    $this->seed(RealJobSeekersSeeder::class);

    $seekers = User::where('role', User::ROLE_JOB_SEEKER)->get();

    expect($seekers)->toHaveCount(4);

    foreach ($seekers as $user) {
        expect($user->links)->not->toBeEmpty()
            ->and($user->preferred_city)->toContain('Pakistan')
            // Nobody is verified until they confirm the address themselves.
            ->and($user->email_verified_at)->toBeNull();
    }

    // Only the candidate whose LinkedIn we could read has a headline and skills.
    expect(User::where('email', 'alibhatti5306@gmail.com')->value('headline'))
        ->toBe('Technical SEO executive at IDEA Digital Advertising')
        ->and(User::where('email', 'raoasifriyasat@gmail.com')->value('headline'))->toBeNull();
});

it('does not reset a password when the seeder runs a second time', function () {
    $this->seed(RealJobSeekersSeeder::class);

    $before = User::where('email', 'niazbhatti8750@gmail.com')->value('password');

    $this->seed(RealJobSeekersSeeder::class);

    expect(User::where('role', User::ROLE_JOB_SEEKER)->count())->toBe(4)
        ->and(User::where('email', 'niazbhatti8750@gmail.com')->value('password'))->toBe($before);
});

it('never auto-seeds the invented demo candidates', function () {
    // JobSeekerSeeder creates fifteen fake US profiles on @example.com; it must
    // not be reachable from a plain `php artisan db:seed` against production.
    $this->seed(Database\Seeders\DatabaseSeeder::class);

    expect(User::where('email', 'like', '%@example.com')->count())->toBe(0);
});

it('lets a candidate save their own portfolio links and drops the blanks', function () {
    $user = seeker();

    $this->actingAs($user)->put(route('seeker.profile.update'), [
        'name' => $user->name,
        'links' => [
            'LinkedIn' => 'https://www.linkedin.com/in/example/',
            'Upwork' => 'https://www.upwork.com/freelancers/example',
            'Fiverr' => '',
            'Portfolio' => '',
        ],
    ])->assertRedirect();

    expect($user->fresh()->links)->toBe([
        'LinkedIn' => 'https://www.linkedin.com/in/example/',
        'Upwork' => 'https://www.upwork.com/freelancers/example',
    ]);
});

it('lists only completed profiles in the sitemap', function () {
    seeker([
        'headline' => 'Technical SEO executive',
        'skills' => 'SEO Audit, Link Building, Schema Markup',
        'links' => ['LinkedIn' => 'https://www.linkedin.com/in/example/'],
    ]);
    seeker(['name' => 'Stub Person', 'username' => 'stub.person', 'email' => 'stub@example.test']);

    get('/sitemap-core.xml')->assertOk()
        ->assertSee('/job-seekers/test.candidate', false)
        ->assertDontSee('/job-seekers/stub.person', false);
});
