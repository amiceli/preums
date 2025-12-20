<?php

use App\Models\Api\GithubRepositoryApi;
use App\Models\Github\GithubRepositoryOwner;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

describe('GithubRepositoryApi', function () {
    describe('User', function () {
        it('should load user repositories', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/users/amiceli/repos' => Http::response(
                    $repos,
                    200,
                ),
            ));

            $list = GithubRepositoryApi::forUser(
                'amiceli',
            )->getRepositories();

            expect(count($list))->toBe(count($repos));

            Http::assertSent(function ($r) {
                return $r->method() === 'GET' && $r->url() === 'https://api.github.com/users/amiceli/repos';
            });
        })->with('github-repos');
    });

    describe('Sort', function () {
        it('should sort repositories desc by created at', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/users/amiceli/repos' => Http::response(
                    $repos,
                    200,
                ),
            ));

            $list = GithubRepositoryApi::forUser(
                'amiceli',
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

    describe('Organization', function () {
        it('should load org repositories', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/orgs/pest/repos' => Http::response(
                    $repos,
                    200,
                ),
            ));

            $list = GithubRepositoryApi::forOrganization(
                'pest',
            )->getRepositories();

            expect(count($list))->toBe(count($repos));

            Http::assertSent(function ($r) {
                return $r->method() === 'GET' && $r->url() === 'https://api.github.com/orgs/pest/repos';
            });
        })->with('github-repos');
    });

    describe('Repository', function () {
        it('should get repository by name', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/repos/amiceli/preums' => Http::response(
                    $repos[0],
                    200,
                ),
            ));

            $repo = GithubRepositoryApi::fromName(
                'amiceli/preums',
            )->getRepository();

            expect(
                $repo->name
            )->toBe($repos[0]['name']);
            expect(
                $repo->description
            )->toBe($repos[0]['description']);

            Http::assertSent(function ($r) {
                return $r->method() === 'GET' && $r->url() === 'https://api.github.com/repos/amiceli/preums';
            });
        })->with('github-repos');

        it('should handle invalid repo full name', function () {
            try {
                GithubRepositoryApi::fromName(
                    'preums',
                )->getRepository();
            } catch (\Exception $e) {
                expect($e->getMessage())->toBe('Invalid repository full name');
            }
        });

        it('should parse repository', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/repos/amiceli/preums' => Http::response(
                    $repos[0],
                    200,
                ),
            ));

            $repo = GithubRepositoryApi::fromName(
                'amiceli/preums',
            )->getRepository();

            $expectedCreatedAt = new \DateTime($repos[0]['created_at']);
            $expectedUpdatedAt = new \DateTime($repos[0]['updated_at']);

            expect($repo)->toMatchObject(array(
                'name' => $repos[0]['name'],
                'id' => $repos[0]['id'],
                'stars' => $repos[0]['stargazers_count'],
                'fullName' => $repos[0]['full_name'],
                'description' => $repos[0]['description'],
                'url' => $repos[0]['html_url'],
                'createdAt' => $expectedCreatedAt,
                'updatedAt' => $expectedUpdatedAt,
                'createdAtStr' => $expectedCreatedAt->format('c'),
                'updatedAtStr' => $expectedUpdatedAt->format('c'),
                'language' => $repos[0]['language'],
                'topics' => $repos[0]['topics'],
                'watchers' => $repos[0]['watchers'],
                'forks' => $repos[0]['forks'],
                'ownerIsOrganization' => false,
                'owner' => new GithubRepositoryOwner(
                    login: $repos[0]['owner']['login'],
                    id: $repos[0]['owner']['id'],
                    avatarUrl: $repos[0]['owner']['avatar_url'],
                ),
            ));
        })->with('github-repos');
    });

    describe('Api', function () {
        it('should be able to search repositories', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/search/repositories*' => Http::response(
                    array(
                        'items' => $repos,
                    ),
                    200,
                ),
            ));

            $list = GithubRepositoryApi::get()->getOldestRepositories();

            expect(
                count($list)
            )->toBe(
                count($repos)
            );

            Http::assertSent(function (Request $r) {
                [
                    'q' => $q,
                    'sort' => $sort,
                    'order' => $order,
                    'per_page' => $perPage
                ] = $r->data();

                return $r->method() === 'GET' &&
                       $q === 'stars:>0' &&
                       $sort === 'stars' &&
                       $order === 'desc' &&
                       $perPage === 35 &&
                       str_contains(
                           $r->url(), 'https://api.github.com/search/repositories'
                       );
            });
        })->with('github-repos');

        it('should be able to load oldest / starred repositories', function (array $repos) {
            Http::fake(array(
                'https://api.github.com/search/repositories*' => Http::response(
                    array(
                        'items' => $repos,
                    ),
                    200,
                ),
            ));

            $list = GithubRepositoryApi::get()->searchRepository('AC_Unity');

            expect(
                count($list)
            )->toBe(
                count($repos)
            );

            Http::assertSent(function (Request $r) {
                [
                    'q' => $q,
                    'sort' => $sort,
                    'order' => $order,
                    'per_page' => $perPage,
                    'page' => $page,
                ] = $r->data();

                return $r->method() === 'GET' &&
                       $q === 'in:name AC_Unity' &&
                       $sort === 'created' &&
                       $order === 'asc' &&
                       $perPage === 20 &&
                       $page === 1 &&
                       str_contains(
                           $r->url(), 'https://api.github.com/search/repositories'
                       );
            });
        })->with('github-repos');
    });
});
