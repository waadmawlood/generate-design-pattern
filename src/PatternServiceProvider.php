<?php

namespace Waad\Pattern;

use Waad\Pattern\GenerateRepo;
use Illuminate\Support\ServiceProvider;

class PatternServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->publishes([
            __DIR__ . '/Repositories/BaseRepository.php' => base_path('app/Http/Repositories/BaseRepository.php'),
        ]);

        $this->publishes([
            __DIR__ . '/Models/Media.php' => base_path('app/Models/Media.php'),
        ]);

        $this->publishes([
            __DIR__.'/../database/migrations/create_media_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_media_table.php'),
        ], 'migrations');

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
