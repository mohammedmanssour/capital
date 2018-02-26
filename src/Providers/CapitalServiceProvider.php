<?php

namespace Helilabs\Capital\Providers;

use Illuminate\Support\ServiceProvider;
use Helilabs\Capital\Generators\ModelFactoryGenerator;
use Helilabs\Capital\Generators\ModelControllerGenerator;
use Helilabs\Capital\Generators\ModelRepositoryGenerator;

class CapitalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModelFactoryGenerator::class,
                ModelControllerGenerator::class,
                ModelRepositoryGenerator::class
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
