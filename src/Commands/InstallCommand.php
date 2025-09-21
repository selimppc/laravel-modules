<?php

namespace Selimppc\LaravelModules\Commands;

use Illuminate\Console\Command;

final class InstallCommand extends Command
{
    protected $signature = 'laravel-modules:install {--with-example : Create an Example module from stubs}';
    protected $description = 'Prepare the host app for Selimppc/Laravel-Modules (config + stubs + Modules folder)';

    public function handle(): int
    {
        // Ensure Modules directory exists
        $modulesPath = config('laravel-modules.path', base_path('Modules'));
        if (! is_dir($modulesPath)) {
            @mkdir($modulesPath, 0775, true);
            $this->info("Created: {$modulesPath}");
        }

        // Publish config
        $this->callSilent('vendor:publish', [
            '--provider' => 'Selimppc\\LaravelModules\\ModulesServiceProvider',
            '--tag'      => 'config',
            '--force'    => false,
        ]);
        $this->info('Published config/laravel-modules.php');

        // Publish stubs
        $this->callSilent('vendor:publish', [
            '--provider' => 'Selimppc\\LaravelModules\\ModulesServiceProvider',
            '--tag'      => 'stubs',
            '--force'    => false,
        ]);
        $this->info('Published stubs to stubs/laravel-modules/module');

        // Optionally create Example module
        if ($this->option('with-example')) {
            $src = base_path('stubs/laravel-modules/module');
            $dst = rtrim($modulesPath, '/').'/Example';

            if (! is_dir($src)) {
                $this->error('Stubs folder missing. Re-run this command after publishing stubs.');
                return self::FAILURE;
            }
            $this->copyDir($src, $dst);
            $this->info("Created Example module at: {$dst}");

            // Enable Example in config if not present
            $configFile = config_path('laravel-modules.php');
            if (is_file($configFile)) {
                $contents = file_get_contents($configFile);
                if (strpos($contents, "'Example'") === false) {
                    $needle  = "'enabled' => [";
                    $inject  = "        'Example' => [ 'web' => ['prefix' => 'example'], 'api' => ['prefix' => 'api/example'] ],";
                    $contents = str_replace($needle, $needle."\n".$inject, $contents);
                    file_put_contents($configFile, $contents);
                    $this->info('Enabled Example module in config/laravel-modules.php');
                }
            }
        }

        $this->line('');
        $this->info('Laravel Modules installed. Next steps:');
        $this->line('  - Edit config/laravel-modules.php to enable your modules.');
        $this->line('  - php artisan route:cache && php artisan config:cache && php artisan view:cache');
        return self::SUCCESS;
    }

    private function copyDir(string $src, string $dst): void
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $file) {
            $target = $dst.'/'.substr($file->getPathname(), strlen($src) + 1);
            if ($file->isDir()) {
                @mkdir($target, 0775, true);
            } else {
                @mkdir(dirname($target), 0775, true);
                @copy($file->getPathname(), $target);
            }
        }
    }
}
