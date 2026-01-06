<?php

use App\Console\Commands\FroozeRepositories;
use App\Models\FrozenRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

describe('FroozeRepositories', function () {
    it('should save repositories', function (array $repos) {
        Http::fake(array(
            'https://api.github.com/search/repositories*' => Http::response(
                array(
                    'items' => $repos,
                ),
                200,
            ),
        ));

        FrozenRepository::insert(array(
            'created_at' => Carbon::now(),
            'name' => 'remove-me',
            'githubId' => '',
            'fullName' => 'remove-me',
            'description' => '',
            'url' => '',
            'githubCreatedAt' => Carbon::now(),
            'githubUpdatedAt' => Carbon::now(),
            'language' => '',
            'topics' => '[]',
            'watchers' => 1,
            'forks' => 2,
            'stars' => 69,
            'ownerIsOrganization' => false,
            'ownerLogin' => '',
            'ownerGithubId' => '',
            'ownerAvatarUrl' => '',
            'year' => '',
        ));

        (new FroozeRepositories())->handle();

        expect(
            FrozenRepository::where('name', 'remove-me')->first()
        )->toBe(null);
        expect(
            FrozenRepository::where('forRoad', false)->count()
        )->toBe(count($repos));
        expect(
            FrozenRepository::where('name', 'beat.time')->first()
        )->toMatchArray(array(
            'name' => 'beat.time',
            'year' => 2019,
            'ownerIsOrganization' => false,
        ));
    })->with('github-repos');
});
