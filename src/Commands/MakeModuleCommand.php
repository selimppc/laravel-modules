<?php

namespace Selimppc\LaravelModules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class MakeModuleCommand extends Command
{
    protected $signature = 'laravel-modules:make {name : Module name in StudlyCase}';
    protected $description = 'Scaffold a new module under Modules/<Name>';

    public function handle(): int
    {
        $name         = Str::studly($this->argument('name'));
        $modulesPath  = rtrim(config('laravel-modules.path', base_path('Modules')), '/');
        $dst          = "{$modulesPath}/{$name}";
        $src          = base_path('stubs/laravel-modules/module');

        if (is_dir($dst)) {
            $this->error("Module already exists: {$dst}");
            return self::FAILURE;
        }
        if (! is_dir($src)) {
            $this->error("Missing stubs at {$src}. Run: php artisan laravel-modules:install");
            return self::FAILURE;
        }

        $this->copyDir($src, $dst);
        $this->info("Created module: {$dst}");
        $this->line("Remember to enable '{$name}' in config/laravel-modules.php under 'enabled'.");

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
