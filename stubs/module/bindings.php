<?php

use Illuminate\Contracts\Foundation\Application;
use Modules\Example\Repositories\Contracts\ExampleRepository;
use Modules\Example\Repositories\EloquentExampleRepository;

/*
|--------------------------------------------------------------------------
| Module container bindings
|--------------------------------------------------------------------------
| This file is required once during service provider registration.
| Keep it tiny and side-effect free. Return a callable that receives $app.
*/
return function (Application $app): void {
    $app->bind(ExampleRepository::class, EloquentExampleRepository::class);
};
