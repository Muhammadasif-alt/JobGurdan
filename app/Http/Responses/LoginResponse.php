<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

/**
 * Send users to the dashboard that matches their role after a successful login.
 * Replaces Fortify's default LoginResponse so /dashboard does the role dispatch.
 */
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(route($user->dashboardRouteName()));
    }
}
