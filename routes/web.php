<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/{vue_capture?}', function () {
    return view('index');
})->where('vue_capture', '[\/\w\.-]*');
