<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Real, consenting candidates who asked to be listed in the public directory.
 *
 * Only facts we were actually given go in here: name, email, the city and
 * experience the candidate stated, and links they published themselves.
 * Headline, skills and bio are the candidate's to write — the accounts are
 * created without them, and each person completes their own profile after
 * signing in. Until they do, User::hasPublishableProfile() keeps the page
 * out of the search index.
 *
 * No password is set here. Each candidate uses "Forgot password" with the
 * email below to claim their account.
 */
class RealJobSeekersSeeder extends Seeder
{
    /**
     * @var list<array{name: string, username: string, email: string, preferred_city: string, experience_years: int, open_to: string, links: array<string, string>, headline?: string, skills?: string}>
     */
    public const SEEKERS = [
        [
            'name' => 'Rana Asif Riyasat',
            'username' => 'rana.asif',
            'email' => 'raoasifriyasat@gmail.com',
            'preferred_city' => 'Lahore, Pakistan',
            'experience_years' => 5,
            'open_to' => 'Full-time',
            'links' => ['LinkedIn' => 'https://www.linkedin.com/in/ranaasifriyasat/'],
        ],
        [
            'name' => 'Niaz Bhatti',
            'username' => 'niaz.bhatti',
            'email' => 'niazbhatti8750@gmail.com',
            'preferred_city' => 'Islamabad, Pakistan',
            'experience_years' => 4,
            'open_to' => 'Full-time',
            'links' => ['Portfolio' => 'https://developmentbyniaz.com'],
        ],
        [
            'name' => 'Abdullah Zaheer',
            'username' => 'abdullah.zaheer',
            'email' => 'workleadsgen@gmail.com',
            'preferred_city' => 'Karachi, Pakistan',
            'experience_years' => 3,
            'open_to' => 'Full-time',
            'links' => ['LinkedIn' => 'https://www.linkedin.com/in/abdullah-zaheer-470496411/'],
        ],
        [
            // Headline, employer, city and skills are as published on his own
            // LinkedIn profile, so they are his claims rather than ours.
            'name' => 'Ali Akbar',
            'username' => 'ali.akbar',
            'email' => 'alibhatti5306@gmail.com',
            'headline' => 'Technical SEO executive at IDEA Digital Advertising',
            'skills' => 'Technical SEO, On-Page SEO, Off-Page SEO, Keyword Research, Link Building, SEO Audit, Schema Markup',
            'preferred_city' => 'Bahawalpur, Pakistan',
            'experience_years' => 3,
            'open_to' => 'Full-time',
            'links' => ['LinkedIn' => 'https://www.linkedin.com/in/ali-akbar-7581673a0'],
        ],
    ];

    public function run(): void
    {
        foreach (self::SEEKERS as $row) {
            $user = User::firstOrNew(['email' => $row['email']]);

            // Never reset the password of an account the candidate already uses.
            if (! $user->exists) {
                $user->password = Hash::make(Str::password(32));
            }

            $user->fill([
                'name' => $row['name'],
                'username' => $row['username'],
                'role' => User::ROLE_JOB_SEEKER,
                'is_active' => true,
                'headline' => $row['headline'] ?? null,
                'skills' => $row['skills'] ?? null,
                'preferred_city' => $row['preferred_city'],
                'experience_years' => $row['experience_years'],
                'open_to' => $row['open_to'],
                'links' => $row['links'],
            ])->save();
        }

        $this->command?->info('Seeded '.count(self::SEEKERS).' consenting job seekers. Each claims their account via "Forgot password".');
    }
}
