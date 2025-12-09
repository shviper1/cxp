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

        $siteSettings = [];

        try {
            $siteSettings = SiteSetting::allCached();

            if (! empty($siteSettings['site_name'])) {
                config(['app.name' => $siteSettings['site_name']]);
            }

            if (! empty($siteSettings['contact_email'])) {
                config([
                    'mail.from.address' => $siteSettings['contact_email'],
                    'mail.from.name' => $siteSettings['site_name'] ?? config('mail.from.name'),
                ]);
            }
        } catch (Throwable $exception) {
            $siteSettings = [];
        }

        View::share('siteSettings', $siteSettings);

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
