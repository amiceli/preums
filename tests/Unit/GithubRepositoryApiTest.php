<?php

use App\Models\Api\GithubRepositoryApi;
use Illuminate\Support\Facades\Http;

describe('GithubRepositoryApi', function () {
    function mockRepoApi(array $repos) {
        Http::fake(array(
            'https://api.github.com/users/vier/repos' => Http::response(
                $repos,
                200,
            ),
        ));
    }

    it('should load user repositories', function (array $repos) {
        mockRepoApi($repos);

        $list = GithubRepositoryApi::forUser(
            'https://api.github.com/users/vier',
        )->getRepositories();

        expect(count($list))->toBe(count($repos));
    })->with('github-repos');

    it('should sort repos by created date', function (array $repos) {
        mockRepoApi($repos);

        $list = GithubRepositoryApi::forUser(
            'https://api.github.com/users/vier',
        )->getRepositories();

        expect($list[0]->name)->not->toBe($repos[0]['name']);

        $clone = array_merge(array(), $repos);

        usort($clone, function ($a, $b) {
            $ad = new \DateTime($a['created_at']);
            $bd = new \DateTime($b['created_at']);

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        expect($list[0]->name)->toBe($clone[0]['name']);
        expect($list[0]->url)->toBe($clone[0]['html_url']);
        expect($list[0]->topics)->toBe($clone[0]['topics']);
        expect($list[0]->language)->toBe($clone[0]['language']);
        expect($list[0]->watchers)->toBe($clone[0]['watchers']);

        expect(
            $list[0]->createdAtStr
        )->toBe(
            (new \DateTime($clone[0]['created_at']))->format('c')
        );
    })->with('github-repos');
});
