<?php

namespace VCComponent\Laravel\Dictionary\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Dictionary\Dictionarys\Dictionary;

class DictionaryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->publishes([
            __DIR__ . '/../../config/dictionary.php' => config_path('dictionary.php'),
            __DIR__ . '/../../resources/lang' => base_path('/resources/lang'),
        ]);

    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dictionary', Dictionary::class);
    }
}
