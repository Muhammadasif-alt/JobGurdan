<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[A-Za-z0-9_.]+$/',
                Rule::unique('users', 'username'),
            ],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => $this->passwordRules(),
            'role'     => ['required', Rule::in(User::PUBLIC_ROLES)],
            'terms'    => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'username.regex' => 'Username may only contain letters, numbers, dots and underscores.',
            'role.required'  => 'Please choose whether you are registering as a Company or a Job Seeker.',
            'role.in'        => 'Account type must be Company or Job Seeker.',
        ])->validate();

        return User::create([
            'name'      => $input['name'],
            'username'  => $input['username'],
            'email'     => $input['email'],
            'password'  => Hash::make($input['password']),
            'role'      => $input['role'],
            'is_active' => true,
        ]);
    }
}
