<?php

use Illuminate\Support\Facades\Route;
use Modules\Example\Http\Controllers\ExampleController;

Route::get('/', [ExampleController::class, 'index'])->name('index');
// URL: /example  | route: example.index
