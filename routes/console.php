<?php

use App\Console\Commands\FroozeRepositories;
use App\Console\Commands\LoadProLang;
use App\Console\Commands\ProLangAssets;
use Illuminate\Support\Facades\Schedule;

Schedule::command(LoadProLang::class)->monthly();
Schedule::command(ProLangAssets::class)->monthly();
Schedule::command(FroozeRepositories::class)->monthly();
