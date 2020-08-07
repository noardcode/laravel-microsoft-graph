<?php

namespace Noardcode\MicrosoftGraph;

use Illuminate\Support\ServiceProvider;

/**
 * Class MicrosoftGraphServiceProvider
 * @package Noardcode\MicrosoftGraph
 */
class MicrosoftGraphServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/microsoft-graph.php' => config_path('microsoft-graph.php'),
        ], 'config');
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->singleton(MicrosoftGraphClient::class, function ($app) {
          return new MicrosoftGraphClient();
        });
    }
}
