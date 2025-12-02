<?php

use App\Http\Controllers\GithubController;
use Illuminate\Support\Facades\Route;

Route
    ::get('/', [GithubController::class, 'index'])
    ->name('home');
Route
    ::get('/github/search', [GithubController::class, 'search'])
    ->name('search');
Route
    ::get('/github/{org}/{repo}', [GithubController::class, 'showRepo'])
    ->name('repository');

// http://localhost:8000/github/amiceli/vitest-cucumber
