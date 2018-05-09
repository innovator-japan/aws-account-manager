<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class DevelopServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $providers = [
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        \Barryvdh\Debugbar\ServiceProvider::class,
    ];
    /**
     * @var array
     */
    protected $facadeAliases = [
        \Barryvdh\Debugbar\Facade::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->isLocal()) {
            $this->registerServiceProviders();
            $this->registerFacadeAliases();
        }
    }

    /**
     * register service providers
     */
    protected function registerServiceProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * add class aliases
     */
    protected function registerFacadeAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->facadeAliases as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }
}
