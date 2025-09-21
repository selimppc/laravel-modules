<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base path for modules inside the host app
    |--------------------------------------------------------------------------
    | Default: project-root/Modules
    */
    'path' => base_path('Modules'),

    /*
    |--------------------------------------------------------------------------
    | Enabled modules (no directory scans in prod)
    |--------------------------------------------------------------------------
    | Keys are folder names inside the modules path.
    | You can set per-module web/api routing options.
    */
    'enabled' => [
        // 'Blog' => [
        //   'web' => ['prefix' => 'blog', 'middleware' => ['web'], 'as' => 'blog.'],
        //   'api' => ['prefix' => 'api/blog', 'middleware' => ['api'], 'as' => 'blog.api.'],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Defaults applied to each module (unless overridden)
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'web' => ['middleware' => ['web']],
        'api' => ['middleware' => ['api']],
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoload routes automatically
    |--------------------------------------------------------------------------
    | If false, the package won’t load module routes; the app can wire them manually.
    */
    'autoload_routes' => true,
];
