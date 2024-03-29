<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Settings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        View::composer(["front.*", "mail::header", "email.*", "layouts.admin.*"], function ($view){
            $settings   = Settings::first();
            $categories = Category::query()
                ->with("childCategories")
                ->where("status", 1)
                ->orderBy("order", "DESC")
                ->get();

            $view->with("settings", $settings)->with("categories", $categories);
        });
    }
}
