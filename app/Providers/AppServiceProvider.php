<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        View::composer('*', function ($view) {
            try {
                $view->with('siteSettings', SiteSetting::allCached());
            } catch (Throwable $exception) {
                $view->with('siteSettings', []);
            }
        });

        View::composer('components.site.footer', function ($view) {
            try {
                $links = FooterLink::allCached()->groupBy('type');
            } catch (Throwable $exception) {
                $links = collect();
            }

            $view->with('footerLinks', $links);
        });
    }
}
