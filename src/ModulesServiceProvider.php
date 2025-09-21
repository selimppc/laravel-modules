<?php

namespace Selimppc\LaravelModules;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge package config
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-modules.php', 'laravel-modules');

        // Register CLI commands only in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
                Commands\MakeModuleCommand::class,
            ]);
        }

        // Load per-module container bindings (optional)
        foreach (array_keys(config('laravel-modules.enabled', [])) as $name) {
            $modulePath = rtrim(config('laravel-modules.path'), '/')."/{$name}";
            $bindings = "{$modulePath}/bindings.php";
            if (is_file($bindings)) {
                $fn = require $bindings;   // should return a callable(Application $app): void
                is_callable($fn) && $fn($this->app);
            }
        }
    }

    public function boot(): void
    {
        // Publish config and stubs
        $this->publishes([
            __DIR__ . '/../config/laravel-modules.php' => config_path('laravel-modules.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../stubs/module' => base_path('stubs/laravel-modules/module'),
        ], 'stubs');

        // Wire modules
        $enabled = config('laravel-modules.enabled', []);
        if (empty($enabled)) {
            return;
        }

        $basePath       = rtrim(config('laravel-modules.path'), '/');
        $autoloadRoutes = (bool) config('laravel-modules.autoload_routes', true);
        $defaults       = (array) config('laravel-modules.defaults', []);

        foreach ($enabled as $name => $opts) {
            $opts       = array_replace_recursive($defaults, (array) $opts);
            $alias      = strtolower($name);
            $modulePath = "{$basePath}/{$name}";

            // Views & translations: lazy (resolved only when used)
            $views = "{$modulePath}/resources/views";
            if (is_dir($views)) {
                $this->loadViewsFrom($views, $alias); // view("{$alias}::file")
            }
            $lang = "{$modulePath}/resources/lang";
            if (is_dir($lang)) {
                $this->loadTranslationsFrom($lang, $alias);
            }

            // Routes: do nothing if routes are cached (zero cost in prod)
            if ($autoloadRoutes && ! $this->app->routesAreCached()) {
                $web = "{$modulePath}/routes/web.php";
                if (is_file($web) && !empty($opts['web'])) {
                    Route::middleware($opts['web']['middleware'] ?? ['web'])
                        ->prefix($opts['web']['prefix'] ?? $alias)
                        ->as($opts['web']['as'] ?? "{$alias}.")
                        ->group($web);
                }

                $api = "{$modulePath}/routes/api.php";
                if (is_file($api) && !empty($opts['api'])) {
                    Route::middleware($opts['api']['middleware'] ?? ['api'])
                        ->prefix($opts['api']['prefix'] ?? "api/{$alias}")
                        ->as($opts['api']['as'] ?? "{$alias}.api.")
                        ->group($api);
                }
            }
        }

        // NOTE: Not calling loadMigrationsFrom(): host app keeps default /database/migrations
    }
}
