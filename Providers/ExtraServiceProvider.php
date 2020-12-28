<?php

namespace Laraxot\Extra\Providers;


use Illuminate\Support\ServiceProvider;
use Laraxot\Extra\Console\Commands\ModuleInstallCommand;

class ExtraServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
    }

    private function registerCommands()
    {
        $this->commands([ModuleInstallCommand::class]);
    }
}
