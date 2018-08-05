<?php

namespace Laraning\Boost\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laraning\Boost\Commands\ViewHintsCommand;

class BaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootBladeDirectives();
    }

    public function register()
    {
        $this->registerCommands();
        $this->registerMacros();
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
        Collection::make(glob(__DIR__.'/../Macros/*.php'))
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

        Blade::directive('pushonce', function ($expression) {
            $var = '$__env->{"__pushonce_" . md5(__FILE__ . ":" . __LINE__)}';

            return "<?php if(!isset({$var})): {$var} = true; \$__env->startPush({$expression}); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });
    }
}
