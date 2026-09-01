<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse as CustomLoginResponse;
use App\Http\Responses\LogoutResponse as CustomLogoutResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(LoginResponseContract::class, CustomLoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, CustomLogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Site-wide pagination — clean Prev / numbers / Next with dark active page
        Paginator::defaultView('pagination::custom');
        Paginator::defaultSimpleView('pagination::custom');

        // Footer composer — only show categories + states that actually have active jobs,
        // so no footer link ever points to an empty results page.
        View::composer('user.layouts.master', function ($view) {
            $footerCategories = Cache::remember('footer.categories', 600, function () {
                return DB::table('categories')
                    ->join('jobs', 'jobs.category_id', '=', 'categories.id')
                    ->where(function ($q) {
                        $q->where('jobs.status', 'active')->orWhereNull('jobs.status');
                    })
                    ->select('categories.name', 'categories.slug', DB::raw('COUNT(jobs.id) as job_count'))
                    ->groupBy('categories.id', 'categories.name', 'categories.slug')
                    ->orderByDesc('job_count')
                    ->take(8)
                    ->get();
            });

            $footerStates = Cache::remember('footer.states', 600, function () {
                return DB::table('locations')
                    ->join('jobs', 'jobs.location_id', '=', 'locations.id')
                    ->where(function ($q) {
                        $q->where('jobs.status', 'active')->orWhereNull('jobs.status');
                    })
                    ->select('locations.name', DB::raw('COUNT(jobs.id) as job_count'))
                    ->groupBy('locations.name')
                    ->orderByDesc('job_count')
                    ->take(8)
                    ->get();
            });

            $view->with('footerCategories', $footerCategories)
                ->with('footerStates', $footerStates);
        });
    }
}
