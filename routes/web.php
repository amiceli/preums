<?php

use App\Http\Controllers\GithubController;
use Illuminate\Support\Facades\Route;

Route
    ::get('/', [GithubController::class, 'index'])
    ->name('home');
Route::post('/github/search', [GithubController::class, 'search']);
