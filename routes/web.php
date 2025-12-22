<?php

use App\Http\Controllers\GithubController;
use App\Http\Middleware\HandleGithubRateLimit;
use Illuminate\Support\Facades\Route;

Route::get('/', array(GithubController::class, 'index'))
    ->name('homepage')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/search', array(GithubController::class, 'search'))
    ->name('search-search')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/org/{name}', array(GithubController::class, 'showOrganizationHistory'))
    ->name('organization-history')
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

Route::get('/lang-stats', array(GithubController::class, 'langStats'))
    ->name('lang-stats');

Route::post('/search-oldest', array(GithubController::class, 'searchOldestRepository'))
    ->name('search-oldest')
    ->middleware(HandleGithubRateLimit::class);

Route::post('/search-starred', array(GithubController::class, 'searchStarredRepository'))
    ->name('search-starred')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/road', array(GithubController::class, 'road'))
    ->name('project-road');
