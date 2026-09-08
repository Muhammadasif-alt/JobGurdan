<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // JobSeekerSeeder is demo data — fifteen invented US candidates on
        // @example.com addresses, all sharing the password "password". Running
        // it against production would publish fake people in the public
        // directory, so it is called explicitly in local work, never here.
        $this->call([
            RealJobSeekersSeeder::class,
        ]);
    }
}
