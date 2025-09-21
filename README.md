# Laravel Modules (lightweight)

**selimppc/laravel-modules** — convention-based modular structure for Laravel 11/12:
- Modules live under `/Modules/<Name>`
- Keep app migrations in `/database/migrations`
- One auto-discovered provider wires routes/views/translations
- Zero request-time overhead after `route:cache`

## Requirements
    PHP 8.3+
    Laravel 11 / 12

## Install

```
$ composer require selimppc/laravel-modules
$ php artisan laravel-modules:install --with-example
```
This will:
- Create /Modules (if missing)
- Publish config/laravel-modules.php
- Publish stubs under /stubs/laravel-modules/module
- Scaffold an Example module and enable it (when using --with-example)
✅ Laravel 12: No need to edit bootstrap/app.php. The service provider is auto-discovered.


## Enable modules 
Edit in `config/laravel-modules.php`:
```
'path' => base_path('Modules'),
'enabled' => [
    'Example' => [
        'web' => ['prefix' => 'example', 'middleware' => ['web'], 'as' => 'example.'],
        'api' => ['prefix' => 'api/example', 'middleware' => ['api'], 'as' => 'example.api.'],
    ],
],
```

## Autoload mapping (host app)
Add PSR-4 for your app (not the package) so Composer can load `Modules\…` classes:
```
"autoload": {
  "psr-4": {
    "App\\": "app/",
    "Modules\\": "Modules/"
  }
}
```
Rebuild autoload files:

```
$ composer dump-autoload -o
```


#### Try it
Start the dev server and visit the example routes:

```
$ php artisan serve
```

Web → http://localhost:8000/example

API → http://localhost:8000/api/example


### Make a new module

```
$ php artisan laravel-modules:make Blog
```

Enable it in `config/laravel-modules.php`:

```
'enabled' => [
    'Example' => [...],
    'Blog' => [
        'web' => ['prefix' => 'blog',      'middleware' => ['web'], 'as' => 'blog.'],
        'api' => ['prefix' => 'api/blog',  'middleware' => ['api'], 'as' => 'blog.api.'],
    ],
],
```
Your module structure (convention):
```
Modules/
  Blog/
    routes/web.php
    routes/api.php
    Http/Controllers/...
    Repositories/Contracts/...
    Repositories/Eloquent...
    resources/views/...
    resources/lang/...
    bindings.php      # optional DI bindings for this module
```
| Migrations remain in the host app’s `/database/migrations`.

### Production (performance)
Cache everything:
```
$ php artisan route:cache
$ php artisan config:cache
$ php artisan view:cache
```
Why this is fast:
- Routes are skipped entirely at runtime when cached
- Views/translations are lazy; Blade can be precompiled with view:cache
- No directory scans on requests (uses your config list)


####  Troubleshooting
###### Target class does not exist (e.g., Modules\Example\Http\Controllers\…)
1. Ensure PSR-4 mapping exists in your app’s composer.json (not dev):
`"autoload": { "psr-4": { "Modules\\": "Modules/" } }`

Then:
```
$ composer dump-autoload -o
$ php artisan optimize:clear
```
2. Check namespace & casing match the path exactly (case-sensitive FS):
- Modules/Example/Http/Controllers/ExampleController.php
- namespace Modules\Example\Http\Controllers;

3. Clear route/config caches:
```
$ php artisan route:clear
$ php artisan config:clear
```
Verify:
```
$ php artisan route:list | grep example
$ php artisan tinker

>>> class_exists(\Modules\Example\Http\Controllers\ExampleController::class)
Editor warns “Undefined function base_path()”
```

### Customization
- Disable route autoloading and wire routes yourself:

```
'autoload_routes' => false,
```

- Change module base path (e.g., to `src/Modules`):
```
'path' => base_path('src/Modules'),
```

- Update PSR-4 accordingly:
```
"Modules\\": "src/Modules/"
```

### FAQ
Q: Can I keep using my global routes and just use modules for controllers/views?

    A: Yes. Set 'autoload_routes' => false and require your module route files manually or define routes in the app.
Q: Where do I put models?

    A: Inside each module (e.g., Modules/Blog/Models/Post.php) or in your app—your choice. The package is unopinionated here.
Q: Can I use repositories/services?

    A: Yes. Add contracts/implementations under Repositories/... and wire them in bindings.php.

### Contributing
PRs welcome! Please include tests and update the README when changing behavior.
License

MIT © selimppc

