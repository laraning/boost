<?php

namespace Laraning\Boost;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laraning\Boost\Commands\ViewHintsCommand;

class BoostServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootBladeDirectives();

        $this->loadTranslations();

        $this->registerPublishing();
    }

    public function register()
    {
        $this->registerCommands();
        $this->registerMacros();
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/boost'),
        ], 'boost-translations');
    }

    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(resource_path('lang/vendor/boost'), 'boost');
    }

    protected function registerCommands()
    {
        // view:hints -- Lists all the additional view
        $this->app->bind('command.view:hints', ViewHintsCommand::class);
        $this->commands([
            'command.view:hints',
        ]);
    }

    protected function registerMacros()
    {
        // Include all files from the Macros folder.
        Collection::make(glob(__DIR__.'/Macros/*.php'))
                  ->mapWithKeys(function ($path) {
                      return [$path => pathinfo($path, PATHINFO_FILENAME)];
                  })
                  ->each(function ($macro, $path) {
                      require_once $path;
                  });
    }

    protected function bootBladeDirectives()
    {
        Blade::if('action', function ($action) {
            if (Route::getCurrentRoute()->getActionMethod() == $action) {
                return $action;
            }
        });

        Blade::if('env', function ($env) {
            return app()->environment($env);
        });
    }
}
