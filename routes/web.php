<?php

use App\Http\Controllers\GithubController;
use App\Http\Middleware\HandleGithubRateLimit;
use Illuminate\Support\Facades\Route;

Route::get('/', array(GithubController::class, 'index'))
    ->name('home')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/search', array(GithubController::class, 'search'))
    ->name('search')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/org/{name}', array(GithubController::class, 'showOrgHistory'))
    ->name('org-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/user/{name}', array(GithubController::class, 'showUserHistory'))
    ->name('user-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/{org}/{repo}', array(GithubController::class, 'showRepositoryHistory'))
    ->name('repository-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/rate-limit', array(GithubController::class, 'rateLimit'))
    ->name('rate-limit')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/road', array(GithubController::class, 'road'))
    ->name('project-road');

Route::get('/languages', array(GithubController::class, 'languages'))
    ->name('languages-stats');
