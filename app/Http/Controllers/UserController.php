<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $role = $request->input('role');

        $allowedRoles = ['admin', 'company', 'job_seeker', 'user'];

        $query = User::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (in_array($role, $allowedRoles, true)) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'total'       => User::count(),
            'admins'      => User::where('role', 'admin')->count(),
            'companies'   => User::where('role', 'company')->count(),
            'job_seekers' => User::where('role', 'job_seeker')->count(),
            'active'      => User::where('is_active', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:191'],
                'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z0-9_.]+$/', 'unique:users,username'],
                'email' => ['required', 'email', 'max:191', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'role' => ['required', 'in:user,admin,company,job_seeker'],
                'phone' => ['nullable', 'string', 'max:50'],
                'is_active' => ['sometimes', 'boolean'],
                'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ], [
                'username.regex' => 'Username may only contain letters, numbers, dots and underscores.',
            ]);

            $photoFile = $request->file('photo');
            unset($data['photo']);
            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = $request->has('is_active');

            $user = User::create($data);

            if ($user && $photoFile) {
                $user->updateProfilePhoto($photoFile);
            }

            if ($user) {
                return redirect()->route('admin.users.index')
                    ->with('success', 'User created successfully!');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('User validation failed:', $e->errors());

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            \Log::error('User creation error: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Error creating user: '.$e->getMessage())
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z0-9_.]+$/', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:191', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:user,admin,company,job_seeker'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'username.regex' => 'Username may only contain letters, numbers, dots and underscores.',
        ]);

        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }
        unset($data['photo']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->has('is_active');

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    /** Remove the user's profile photo (admin action). */
    public function removePhoto(User $user)
    {
        $user->deleteProfilePhoto();
        return back()->with('success', 'Photo removed.');
    }
}
