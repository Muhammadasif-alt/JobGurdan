<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo'        => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'phone'        => ['nullable', 'string', 'max:30'],
            // Company-specific (ignored for non-companies)
            'website'      => ['nullable', 'url', 'max:255'],
            'address'      => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:50'],
            'headline'     => ['nullable', 'string', 'max:191'],
            'bio'          => ['nullable', 'string', 'max:5000'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Build the column-update array based on the user's role
        $base = [
            'name'  => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
        ];
        if ($user->role === User::ROLE_COMPANY) {
            $base = array_merge($base, [
                'website'      => $input['website'] ?? null,
                'address'      => $input['address'] ?? null,
                'company_size' => $input['company_size'] ?? null,
                'headline'     => $input['headline'] ?? null,
                'bio'          => $input['bio'] ?? null,
            ]);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $base);
        } else {
            $user->forceFill($base)->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill(array_merge($input, [
            'email_verified_at' => null,
        ]))->save();

        $user->sendEmailVerificationNotification();
    }
}
