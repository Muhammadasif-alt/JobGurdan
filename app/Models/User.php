<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'headline',
        'bio',
        'skills',
        'cv_path',
        'preferred_city',
        'experience_years',
        'open_to',
        'website',
        'address',
        'company_size',
        'resume_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'resume_data' => 'array',
        ];
    }

    /** Allowed account roles for the public site (admin is set via the admin panel). */
    public const ROLE_ADMIN      = 'admin';
    public const ROLE_COMPANY    = 'company';
    public const ROLE_JOB_SEEKER = 'job_seeker';
    public const ROLE_USER       = 'user';

    /** Roles that the public register form can choose from. */
    public const PUBLIC_ROLES = [self::ROLE_COMPANY, self::ROLE_JOB_SEEKER];

    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isCompany(): bool    { return $this->role === self::ROLE_COMPANY; }
    public function isJobSeeker(): bool  { return $this->role === self::ROLE_JOB_SEEKER; }

    /** Where this user should land after login (and on the generic /dashboard route). */
    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN      => 'admin.dashboard',
            self::ROLE_COMPANY    => 'company.dashboard',
            self::ROLE_JOB_SEEKER => 'seeker.dashboard',
            default               => 'home', // legacy 'user' or unknown roles → public home (no auth) so we never loop
        };
    }
}
