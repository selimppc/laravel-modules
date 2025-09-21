# Laravel Modules (lightweight)

**selimppc/laravel-modules** — convention-based modular structure for Laravel 11/12:
- Modules live under `/Modules/<Name>`
- Keep app migrations in `/database/migrations`
- One auto-discovered provider wires routes/views/translations
- Zero request-time overhead after `route:cache`

## Install

```
$ composer require selimppc/laravel-modules
$ php artisan laravel-modules:install --with-example
```


## Enable modules in config/laravel-modules.php:
```
'path' => base_path('Modules'),
'enabled' => [
    'Example' => [
        'web' => ['prefix' => 'example', 'middleware' => ['web'], 'as' => 'example.'],
        'api' => ['prefix' => 'api/example', 'middleware' => ['api'], 'as' => 'example.api.'],
    ],
],
```

#### Cache for prod:
```
$ php artisan route:cache
$ php artisan config:cache
$ php artisan view:cache
```

#### Visit:

    Web: /example
    API: /api/example



### Make a new module

```
$ php artisan laravel-modules:make Blog

# then enable 'Blog' in config/laravel-modules.php
```
