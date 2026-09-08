<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Real, consenting candidates who asked to be listed in the public directory.
 *
 * Only facts we were actually given go in here: the name, headline, skills
 * and city each person publishes on their own LinkedIn profile, plus the
 * experience they stated and the links they shared. Nothing is written for
 * them. The bio is deliberately left empty because it is theirs to write,
 * and any profile that falls below User::hasPublishableProfile() stays out
 * of the search index until the candidate fills it in.
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
            // Name, headline, skills and city are as published on his own
            // LinkedIn profile and banner, so they are his claims, not ours.
            // The LinkedIn slug reads "rana", the display name and email read
            // "rao"; the latter two agree, so "Rao" is used.
            'name' => 'Rao Asif Riyasat',
            'username' => 'rao.asif',
            'email' => 'raoasifriyasat@gmail.com',
            'headline' => 'Full Stack Developer | MERN, Next.js, Elixir/Phoenix | WordPress & Shopify | AI Prompt Engineer | SEO & E-commerce | Serving USA, UK, Australia & Portugal',
            'skills' => 'MERN Stack, React.js, Next.js, Node.js, MongoDB, Elixir/Phoenix, WordPress, Shopify, E-commerce, SEO, Web Design',
            'preferred_city' => 'Lahore, Pakistan',
            'experience_years' => 5,
            // His banner reads "Open to Freelance & Client Projects Worldwide".
            'open_to' => 'Contract',
            'links' => ['LinkedIn' => 'https://www.linkedin.com/in/ranaasifriyasat/'],
        ],
        [
            'name' => 'Muhammad Niaz',
            'username' => 'muhammad.niaz',
            'email' => 'niazbhatti8750@gmail.com',
            'headline' => 'Full-Stack Developer | WordPress & Shopify Expert | SEO & Performance Optimization | Conversion-Focused Websites',
            'skills' => 'WordPress, Shopify, Full-Stack Development, SEO, Performance Optimization, Conversion Optimization',
            'preferred_city' => 'Lahore, Pakistan',
            'experience_years' => 4,
            'open_to' => 'Any',
            // His LinkedIn URL was not shared, only the profile itself; the
            // portfolio site is the link we actually have.
            'links' => ['Portfolio' => 'https://developmentbyniaz.com'],
        ],
        [
            'name' => 'Abdullah Zaheer',
            'username' => 'abdullah.zaheer',
            'email' => 'workleadsgen@gmail.com',
            'headline' => 'I fix your Upwork profile & proposals so clients actually reply | 100+ freelancers helped',
            'skills' => 'Lead Generation, Upwork Profile Optimization, Proposal Writing, Client Acquisition',
            'preferred_city' => 'Bahawalpur, Pakistan',
            'experience_years' => 3,
            'open_to' => 'Any',
            'links' => ['LinkedIn' => 'https://www.linkedin.com/in/abdullah-zaheer-470496411/'],
        ],
        [
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
