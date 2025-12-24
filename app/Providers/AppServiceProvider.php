<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

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
        // === SHARE SETTINGS WITH ALL VIEWS ===
        $settings = Cache::rememberForever('app_settings', function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        View::share('settings', $settings);

        // Global helper function for settings
        if (!function_exists('setting')) {
            function setting($key, $default = null) {
                static $settingsCache = null;

                if ($settingsCache === null) {
                    $settingsCache = Cache::rememberForever('app_settings', function () {
                        return Setting::pluck('value', 'key')->toArray();
                    });
                }

                return $settingsCache[$key] ?? $default;
            }
        }

        // === SHARE MAIN CATEGORIES WITH ALL VIEWS ===
        View::composer('*', function ($view) {
            $mainCategories = Category::whereNull('parent_id')
                                      ->with('children') // load subcategories
                                      ->orderBy('name')
                                      ->get();

            $view->with('mainCategories', $mainCategories);
        });
    }
}