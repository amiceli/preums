<?php

namespace App\Models\Api;

use App\Models\Github\GithubRepository;
use App\Models\Github\GithubRepositoryOwner;
use DateTime;

class GithubRepositoryApi extends ApiClient {
    public static function forOrganization(string $orgName) {
        return new GithubRepositoryApi(
            'https://api.github.com/orgs/'.$orgName
        );
    }

    public static function forUser(string $userName) {
        return new GithubRepositoryApi(
            'https://api.github.com/users/'.$userName
        );
    }

    public static function fromName(string $repoFullName) {
        if (! str_contains($repoFullName, '/')) {
            throw new \Exception('Invalid repository full name');
        }

        return new GithubRepositoryApi(
            'https://api.github.com/repos/'.$repoFullName
        );
    }

    public static function get() {
        return new GithubRepositoryApi('');
    }

    private function parseRepository(array $item): GithubRepository {
        return new GithubRepository(
            id: $item['id'],
            stars: $item['stargazers_count'],
            name: $item['name'],
            fullName: $item['full_name'],
            description: $item['description'],
            url: $item['html_url'],
            createdAt: new DateTime($item['created_at']),
            updatedAt: new DateTime($item['updated_at']),
            language: $item['language'],
            topics: $item['topics'],
            watchers: $item['watchers'],
            forks: $item['forks'],
            owner: new GithubRepositoryOwner(
                login: $item['owner']['login'],
                id: $item['owner']['id'],
                avatarUrl: $item['owner']['avatar_url'],
            ),
            ownerIsOrganization: $item['owner']['type'] === 'Organization',
        );
    }

    /**
     * @return GithubRepository[]
     */
    public function searchRepository(string $search): array {
        $response = $this->makeGet(
            'https://api.github.com/search/repositories',
            array(
                'q' => "in:name $search",
                'sort' => 'created',
                'order' => 'asc',
                'per_page' => 20,
                'page' => 1,
            ),
        );

        $list = array_map(function (array $item) {
            return $this->parseRepository($item);
        }, $response->json()['items']);

        return $list;
    }

    public function getRepository() {
        $response = $this->makeGet($this->root);

        return $this->parseRepository($response->json());
    }

    public function getRepositories() {
        $response = $this->makeGet($this->root.'/repos');

        return $this->sortRepositoriesByDate(
            array_map(function ($item) {
                return $this->parseRepository($item);
            }, $response->json()),
        );
    }

    /**
     * @return GithubRepository[]
     */
    private function sortRepositoriesByDate(array $list): array {
        $clone = array_merge(array(), $list);

        usort($clone, function ($a, $b) {
            $ad = $a->createdAt;
            $bd = $b->createdAt;

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return $clone;
    }

    public function getStarredRepository(string $lang): GithubRepository {
        $response = $this->makeGet(
            'https://api.github.com/search/repositories',
            array(
                'q' => 'stars:>0 language:'.$lang,
                'sort' => 'stars',
                'order' => 'desc',
                'per_page' => 1,
                'page' => 1,
            ),
        );
        [$item] = $response->json()['items'];

        return $this->parseRepository($item);
    }

    public function getOldestRepository(string $lang): GithubRepository {
        $response = $this->makeGet(
            'https://api.github.com/search/repositories',
            array(
                'q' => 'language:'.$lang,
                'sort' => 'updated',
                'order' => 'asc',
                'per_page' => 1,
                'page' => 1,
            ),
        );
        [$item] = $response->json()['items'];

        return $this->parseRepository($item);
    }

    /**
     * @return GithubRepository[]
     */
    public function getOldestStarredRepositories(): array {
        $response = $this->makeGet(
            'https://api.github.com/search/repositories',
            array(
                'q' => 'stars:>0',
                'sort' => 'stars',
                'order' => 'desc',
                'per_page' => 50,
            ),
        );

        $list = array_map(function (array $item) {
            return $this->parseRepository($item);
        }, $response->json()['items']);

        return $this->sortRepositoriesByDate($list);
    }
}
