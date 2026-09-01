<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Brand-styled settings page for both seeker and company panels.
 * Handles account info (name/email/phone), company-specific fields,
 * and password updates — all without leaving the panel layout.
 */
class SettingsController extends Controller
{
    public function seeker()
    {
        return view('seeker.settings', ['user' => Auth::user()]);
    }

    public function company()
    {
        return view('company.settings', ['user' => Auth::user()]);
    }

    /** Update basic account info (name, email, phone) + optional avatar/logo. */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:191'],
            'email'  => ['required', 'email', 'max:191', Rule::unique('users')->ignore($user->id)],
            'phone'  => ['nullable', 'string', 'max:30'],
            'photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2 MB
        ]);

        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        return back()->with('success', 'Account information updated.');
    }

    /** Remove the current profile photo / company logo. */
    public function removePhoto()
    {
        Auth::user()->deleteProfilePhoto();
        return back()->with('success', 'Photo removed.');
    }

    /** Company-only: update company details (website, address, tagline, about, size). */
    public function updateCompanyDetails(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->role === User::ROLE_COMPANY, 403);

        $data = $request->validate([
            'website'      => ['nullable', 'url', 'max:255'],
            'address'      => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:50'],
            'headline'     => ['nullable', 'string', 'max:191'],
            'bio'          => ['nullable', 'string', 'max:5000'],
        ]);

        $user->update($data);

        return back()->with('success', 'Company details updated.');
    }

    /** Update password — works for any logged-in user. */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
