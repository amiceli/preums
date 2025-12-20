<?php

use App\Models\Api\GithubUserApi;
use Illuminate\Support\Facades\Http;

describe('GithubUserApi', function () {
    function mockApi(array $user, array $repos) {
        Http::fake(array(
            'https://api.github.com/users/preums' => Http::response($user, 200),
            'https://api.github.com/users/preums/repos' => Http::response(
                $repos,
                200,
            ),
        ));
    }

    it('should load user history', function (array $user, array $repos) {
        mockApi($user, $repos);

        $history = GithubUserApi::forUser('preums')->getHistory();
        $hUser = $history['user'];
        $repositories = $history['repositories'];

        expect($hUser->login)->toBe($user['login']);
        expect(count($repositories))->toBe(count($repos));

        expect(
            array_map(function ($item) {
                return $item->name;
            }, $repositories),
        )->toEqualCanonicalizing(
            array_map(function ($item) {
                return $item['name'];
            }, $repos),
        );
    })->with('github-user-history');
});
