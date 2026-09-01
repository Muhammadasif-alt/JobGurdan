<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allow only users whose `role` column matches one of the given roles.
 * Usage: ->middleware(EnsureRole::class.':company')
 *        ->middleware(EnsureRole::class.':company,job_seeker')
 *
 * If the role doesn't match, the user is redirected to their own dashboard
 * (or to login if not authenticated). Admins are always allowed through.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Admins can access any role-gated route
        if ($user->isAdmin()) {
            return $next($request);
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Wrong role — send them to their own dashboard.
        // Loop-guard: if their dashboard *is* the route we just denied, fall back
        // to the public home page instead (otherwise we'd redirect in a circle).
        $target = $user->dashboardRouteName();
        if ($request->routeIs($target)) {
            return redirect()->route('home')
                ->with('error', 'You do not have access to that page.');
        }

        return redirect()->route($target)
            ->with('error', 'You do not have access to that page.');
    }
}
