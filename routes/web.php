<?php

use App\Http\Controllers\GithubController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HandleGithubRateLimit;

Route
    ::get('/', [GithubController::class, 'index'])
    ->name('home')
    ->middleware(HandleGithubRateLimit::class);
Route
    ::get('/github/search', [GithubController::class, 'search'])
    ->name('search')
    ->middleware(HandleGithubRateLimit::class);
Route
    ::get('/github/{org}/{repo}', [GithubController::class, 'showRepositoryHistory'])
    ->name('repository-history')
    ->middleware(HandleGithubRateLimit::class);
Route
    ::get('/rate-limit', [GithubController::class, 'rateLimit'])
    ->name('rate-limit')
    ->middleware(HandleGithubRateLimit::class);
