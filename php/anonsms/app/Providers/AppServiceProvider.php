<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //https://stackoverflow.com/questions/42244541/laravel-migration-error-syntax-error-or-access-violation-1071-specified-key-wa
        //\Illuminate\Support\Facades\Schema::defaultStringLength(191); // %HACK until we upgrade AWS's mysql or use RDS

        // http://stackoverflow.com/questions/14174070/automatically-deleting-related-rows-in-laravel-eloquent-orm
        // https://laravel.com/docs/5.2/eloquent-relationships   Attaching/Detaching
        \App\Models\User::deleting(function ($user) {
            //remove associations inside the pivot tables for this user
            $user->roles()->detach();
        });


        // --- Morph Map for Polymorphic Relations ---

        // https://laravel.com/docs/5.6/eloquent-relationships#polymorphic-relations
        // Note: the plural form of the noun is the default that laravel 5.2 uses as the key
        // if no key is specified in ANY of the class mappings, so that is reflected in this manual key map.
        Relation::morphMap([
            'users'=>\App\Models\User::class,
            //'forecasts'=>\App\Models\Forecast::class,
            //'projects'=>\App\Models\Project::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
