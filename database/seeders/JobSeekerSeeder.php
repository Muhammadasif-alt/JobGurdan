<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobSeekerSeeder extends Seeder
{
    /**
     * Seed demo job seeker + company accounts so the admin user list and
     * role-based dashboards have realistic data to render against.
     *
     * Default password for every seeded account: "password"
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // ---- 15 Job Seekers (realistic U.S. profiles) ----
        $jobSeekers = [
            ['name' => 'Emily Johnson',     'username' => 'emily.johnson',    'email' => 'emily.johnson@example.com',    'phone' => '+1 415-555-0142'],
            ['name' => 'Michael Smith',     'username' => 'michael.smith',    'email' => 'michael.smith@example.com',    'phone' => '+1 212-555-0167'],
            ['name' => 'Jessica Williams',  'username' => 'jessica.w',        'email' => 'jessica.williams@example.com', 'phone' => '+1 312-555-0119'],
            ['name' => 'David Brown',       'username' => 'david.brown',      'email' => 'david.brown@example.com',      'phone' => '+1 713-555-0198'],
            ['name' => 'Ashley Davis',      'username' => 'ashley.davis',     'email' => 'ashley.davis@example.com',     'phone' => '+1 305-555-0173'],
            ['name' => 'Christopher Miller','username' => 'chris.miller',     'email' => 'chris.miller@example.com',     'phone' => '+1 602-555-0114'],
            ['name' => 'Sarah Wilson',      'username' => 'sarah.wilson',     'email' => 'sarah.wilson@example.com',     'phone' => '+1 215-555-0157'],
            ['name' => 'Daniel Moore',      'username' => 'daniel.moore',     'email' => 'daniel.moore@example.com',     'phone' => '+1 619-555-0188'],
            ['name' => 'Amanda Taylor',     'username' => 'amanda.taylor',    'email' => 'amanda.taylor@example.com',    'phone' => '+1 214-555-0102'],
            ['name' => 'James Anderson',    'username' => 'james.anderson',   'email' => 'james.anderson@example.com',   'phone' => '+1 408-555-0136'],
            ['name' => 'Olivia Thomas',     'username' => 'olivia.thomas',    'email' => 'olivia.thomas@example.com',    'phone' => '+1 503-555-0149'],
            ['name' => 'Ryan Jackson',      'username' => 'ryan.jackson',     'email' => 'ryan.jackson@example.com',     'phone' => '+1 720-555-0125'],
            ['name' => 'Megan White',       'username' => 'megan.white',      'email' => 'megan.white@example.com',      'phone' => '+1 404-555-0181'],
            ['name' => 'Brandon Harris',    'username' => 'brandon.harris',   'email' => 'brandon.harris@example.com',   'phone' => '+1 617-555-0163'],
            ['name' => 'Lauren Martin',     'username' => 'lauren.martin',    'email' => 'lauren.martin@example.com',    'phone' => '+1 206-555-0191'],
        ];

        foreach ($jobSeekers as $i => $row) {
            User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name'              => $row['name'],
                    'username'          => $row['username'],
                    'password'          => $password,
                    'role'              => User::ROLE_JOB_SEEKER,
                    'phone'             => $row['phone'],
                    'is_active'         => true,
                    'email_verified_at' => now()->subDays($i + 1),
                    'created_at'        => now()->subDays(rand(1, 90)),
                ]
            );
        }

        // ---- 5 Demo Companies ----
        $companies = [
            ['name' => 'TechFlow Solutions',      'username' => 'techflow.hr',      'email' => 'hiring@techflow.example.com',      'phone' => '+1 415-555-2010'],
            ['name' => 'Atlas Logistics Group',   'username' => 'atlas.recruit',    'email' => 'careers@atlas-logistics.example.com', 'phone' => '+1 312-555-2031'],
            ['name' => 'Sunrise Healthcare Inc.', 'username' => 'sunrise.talent',   'email' => 'jobs@sunrise-health.example.com',  'phone' => '+1 305-555-2074'],
            ['name' => 'BlueRock Construction',   'username' => 'bluerock.hr',      'email' => 'hr@bluerock.example.com',          'phone' => '+1 713-555-2055'],
            ['name' => 'Aurora Retail Co.',       'username' => 'aurora.hiring',    'email' => 'team@aurora-retail.example.com',   'phone' => '+1 404-555-2099'],
        ];

        foreach ($companies as $i => $row) {
            User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name'              => $row['name'],
                    'username'          => $row['username'],
                    'password'          => $password,
                    'role'              => User::ROLE_COMPANY,
                    'phone'             => $row['phone'],
                    'is_active'         => true,
                    'email_verified_at' => now()->subDays($i + 1),
                    'created_at'        => now()->subDays(rand(5, 120)),
                ]
            );
        }

        $this->command?->info('Seeded '.count($jobSeekers).' job seekers and '.count($companies).' companies. Default password: "password"');
    }
}
