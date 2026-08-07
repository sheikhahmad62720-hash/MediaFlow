<?php

use Illuminate\Support\Facades\Route;

/**
 * Single-page application shell.
 *
 * Every non-API request is served by the Vue SPA which owns client-side
 * routing. API routes live in routes/api.php.
 */
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
