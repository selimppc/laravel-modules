<?php

use Illuminate\Support\Facades\Route;
use Modules\Example\Http\Controllers\ExampleController;

Route::get('/', [ExampleController::class, 'apiList'])->name('list');
// URL: /api/example  | route: example.api.list
