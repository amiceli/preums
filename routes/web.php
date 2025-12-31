<?php

use App\Http\Controllers\MainController;
use App\Http\Middleware\HandleGithubRateLimit;
use Illuminate\Support\Facades\Route;

Route::get('/', array(MainController::class, 'index'))
    ->name('homepage')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/search', array(MainController::class, 'search'))
    ->name('search-search')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/org/{name}', array(MainController::class, 'showOrganizationHistory'))
    ->name('organization-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/user/{name}', array(MainController::class, 'showUserHistory'))
    ->name('user-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/github/{org}/{repo}', array(MainController::class, 'showRepositoryHistory'))
    ->name('repository-history')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/rate-limit', array(MainController::class, 'rateLimit'))
    ->name('rate-limit')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/lang-stats', array(MainController::class, 'langStats'))
    ->name('lang-stats')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/lang-history', array(MainController::class, 'langHistory'))
    ->name('lang-history')
    ->middleware(HandleGithubRateLimit::class);

Route::post('/search-oldest', array(MainController::class, 'searchOldestRepository'))
    ->name('search-oldest')
    ->middleware(HandleGithubRateLimit::class);

Route::post('/search-starred', array(MainController::class, 'searchStarredRepository'))
    ->name('search-starred')
    ->middleware(HandleGithubRateLimit::class);

Route::post('/search-recent', array(MainController::class, 'searchRecentRepository'))
    ->name('search-recent')
    ->middleware(HandleGithubRateLimit::class);

Route::get('/road', array(MainController::class, 'road'))
    ->name('project-road');
