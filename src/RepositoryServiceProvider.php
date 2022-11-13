<?php

namespace Waad\Repository;

use Waad\RepoMedia\GenerateRepo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateRepo::class,
                GenerateValidation::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->registerPublishables();
    }



}
