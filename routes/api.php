<?php

declare(strict_types=1);

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::resource('profiles', ProfileController::class)->only(['index', 'show']);
Route::resource('posts', PostController::class)->only(['index', 'show']);
