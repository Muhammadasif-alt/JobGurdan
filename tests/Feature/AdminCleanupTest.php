<?php

namespace Tests\Feature;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_cleanup_all_records()
    {
        // Create a verified user
        $user = User::factory()->create(['email_verified_at' => now()]);

        // Seed some data
        $advertiser = Advertiser::create(['name' => 'ACME']);
        $category = Category::create(['name' => 'IT', 'slug' => 'it']);
        $location = Location::create(['name' => 'London']);

        Job::create(['position' => 'Developer', 'advertiser_id' => $advertiser->id, 'category_id' => $category->id, 'location_id' => $location->id]);

        $this->assertGreaterThan(0, Job::count());
        $this->assertGreaterThan(0, Category::count());
        $this->assertGreaterThan(0, Advertiser::count());
        $this->assertGreaterThan(0, Location::count());

        $response = $this->actingAs($user)
            ->post(route('admin.cleanup'), ['confirmation' => 'DELETE']);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals(0, Job::count());
        $this->assertEquals(0, Category::count());
        $this->assertEquals(0, Advertiser::count());
        $this->assertEquals(0, Location::count());
    }
}
