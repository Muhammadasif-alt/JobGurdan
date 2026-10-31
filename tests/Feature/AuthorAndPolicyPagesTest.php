<?php

use App\Models\Author;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

function makePublishedPost(array $overrides = []): Blog
{
    $category = BlogCatgories::firstOrCreate(['slug' => 'career-advice'], ['name' => 'Career Advice', 'description' => 'Guides']);

    return Blog::create(array_merge([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => 'A Sample Guide',
        'slug' => 'a-sample-guide-'.uniqid(),
        'excerpt' => 'A short excerpt.',
        'content' => '<p>Body text of the guide.</p>',
        'status' => 'published',
        'published_at' => now(),
    ], $overrides));
}

it('never shows the generic Admin account name as the byline', function () {
    $post = makePublishedPost();

    expect($post->display_author_name)->toBe('JobGader Editorial');

    $this->get('/blog/'.$post->slug)
        ->assertOk()
        ->assertSee('JobGader Editorial')
        ->assertDontSee('<strong>Admin</strong>', false);
});

it('shows the author profile name, not the stored Admin name, on blog cards', function (string $url) {
    $author = Author::create(['name' => 'Card Byline Author', 'slug' => 'card-byline-author']);
    makePublishedPost(['author_profile_id' => $author->id, 'author_name' => 'Admin', 'title' => 'Card Byline Guide']);

    $this->get($url)->assertOk()->assertSee('Card Byline Author');
})->with(['blog index' => '/blog']);

it('shows an author profile with a link to the author page', function () {
    $author = Author::create(['name' => 'Sara Khan', 'slug' => 'sara-khan', 'job_title' => 'Editor', 'bio' => 'Writes guides about trades and visas.']);
    $post = makePublishedPost(['author_profile_id' => $author->id]);

    $this->get('/blog/'.$post->slug)
        ->assertOk()
        ->assertSee('Sara Khan')
        ->assertSee('Writes guides about trades and visas.')
        ->assertSee(route('authors.show', $author), false)
        ->assertSee('"@type": "Person"', false);

    $this->get('/authors/sara-khan')
        ->assertOk()
        ->assertSee('Sara Khan')
        ->assertSee($post->title);
});

it('gives new posts the default author automatically and lets one command assign the old ones', function () {
    $old = makePublishedPost(['slug' => 'old-post']);
    expect($old->author_profile_id)->toBeNull();

    Artisan::call('authors:create', ['name' => 'Sara Khan', '--title' => 'Editor', '--bio' => 'Real bio.', '--default' => true]);
    $author = Author::where('slug', 'sara-khan')->firstOrFail();
    expect($author->is_default)->toBeTrue();

    $new = makePublishedPost(['slug' => 'new-post']);
    expect($new->author_profile_id)->toBe($author->id);

    Artisan::call('authors:assign', ['slug' => 'sara-khan', '--dry-run' => true]);
    expect($old->fresh()->author_profile_id)->toBeNull();

    Artisan::call('authors:assign', ['slug' => 'sara-khan']);
    expect($old->fresh()->author_profile_id)->toBe($author->id)
        ->and($old->fresh()->author_name)->toBe('Sara Khan')
        ->and(Blog::count())->toBe(2);
});

it('shares the posts between the named authors in turn without touching updated_at', function () {
    $asif = Author::create(['name' => 'Muhammad Asif', 'slug' => 'muhammad-asif', 'is_default' => true]);
    $ali = Author::create(['name' => 'Ali Rehman', 'slug' => 'ali-rehman']);
    $abdullah = Author::create(['name' => 'Abdullah Zeheer', 'slug' => 'abdullah-zeheer']);
    $posts = collect(range(1, 7))->map(fn () => makePublishedPost(['author_profile_id' => $asif->id]));
    Blog::query()->toBase()->update(['updated_at' => now()->subYear()]);

    Artisan::call('authors:spread', ['slugs' => ['ali-rehman', 'muhammad-asif', 'abdullah-zeheer'], '--dry-run' => true]);
    expect(Blog::where('author_profile_id', $asif->id)->count())->toBe(7);

    Artisan::call('authors:spread', ['slugs' => ['ali-rehman', 'muhammad-asif', 'abdullah-zeheer']]);

    expect(Blog::where('author_profile_id', $ali->id)->count())->toBe(3)
        ->and(Blog::where('author_profile_id', $asif->id)->count())->toBe(2)
        ->and(Blog::where('author_profile_id', $abdullah->id)->count())->toBe(2)
        ->and(Blog::whereNull('author_profile_id')->count())->toBe(0)
        ->and($posts->first()->fresh()->author_name)->toBe('Ali Rehman')
        ->and($posts->first()->fresh()->updated_at->isToday())->toBeFalse();
});

it('refuses to spread posts to an author that does not exist', function () {
    Author::create(['name' => 'Ali Rehman', 'slug' => 'ali-rehman']);
    $post = makePublishedPost();

    expect(Artisan::call('authors:spread', ['slugs' => ['ali-rehman', 'nobody']]))->toBe(1)
        ->and($post->fresh()->author_profile_id)->toBe(Author::first()->id);
});

it('gives a new post to the author with the fewest posts', function () {
    $asif = Author::create(['name' => 'Muhammad Asif', 'slug' => 'muhammad-asif', 'is_default' => true]);
    $ali = Author::create(['name' => 'Ali Rehman', 'slug' => 'ali-rehman']);

    expect(makePublishedPost()->author_profile_id)->toBe($asif->id)
        ->and(makePublishedPost()->author_profile_id)->toBe($ali->id)
        ->and(makePublishedPost()->author_profile_id)->toBe($asif->id);
});

it('publishes an editorial policy and a disclaimer and links them in the footer', function () {
    $this->get('/editorial-policy')
        ->assertOk()
        ->assertSee('Editorial Policy')
        ->assertSee('How a guide is researched')
        ->assertSee('Corrections');

    $this->get('/disclaimer')
        ->assertOk()
        ->assertSee('not an employer, a recruiter')
        ->assertSee('Not legal, immigration or financial advice');

    $this->get('/')
        ->assertOk()
        ->assertSee(route('pages.editorial-policy'), false)
        ->assertSee(route('pages.disclaimer'), false)
        ->assertSee(route('jobs.portals'), false);

    $this->get('/sitemap-core.xml')->assertSee('/editorial-policy', false);
});

it('shows the owner on the About and Contact pages only once the details are set', function () {
    $this->get('/about-us')->assertOk()->assertDontSee('JobGader is run by');

    config(['site.owner.name' => 'Test Owner', 'site.owner.location' => 'Lahore, Pakistan']);

    $this->get('/about-us')->assertOk()->assertSee('JobGader is run by Test Owner')->assertSee('Lahore, Pakistan');
    $this->get('/contact-us')->assertOk()->assertSee('Test Owner')->assertSee('Lahore, Pakistan');
});

it('keeps a user account called Admin out of public bylines', function () {
    $admin = User::factory()->create(['name' => 'Admin']);
    $post = makePublishedPost(['author_id' => $admin->id, 'author_name' => null]);

    expect($post->display_author_name)->toBe('JobGader Editorial');
});

it('keeps Talent out of the main menu and puts it in the footer with About and Contact', function () {
    $html = $this->get('/')->assertOk()->getContent();

    $nav = substr($html, 0, strpos($html, '<!-- Footer -->'));
    $footer = substr($html, strpos($html, '<!-- Footer -->'));

    expect($nav)->not->toMatch('#<a href="'.preg_quote(route('job-seekers.index'), '#').'"[^>]*>\s*Talent\s*</a>#')
        ->and($footer)->toContain('>Talent</a>')
        ->toContain('>About Us</a>')
        ->toContain('>Contact</a>');

    $bar = substr($footer, strpos($footer, 'utf-footer-copyright-item"'));
    expect($bar)->toContain('Copyright &copy; 2026 JobGader')
        ->toContain('>Privacy Policy</a>')
        ->toContain('>Terms of Service</a>');
});
