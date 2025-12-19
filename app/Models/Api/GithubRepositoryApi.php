<?php

namespace App\Models\Api;

use App\Models\Github\GithubRepository;
use App\Models\Github\GithubRepositoryOwner;
use DateTime;

class GithubRepositoryApi extends ApiClient {
    public static function forOrganization(string $url) {
        return new GithubRepositoryApi(root: $url);
    }

    public static function forRepository(string $url) {
        return new GithubRepositoryApi(root: $url);
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

    /**
     * @return GithubRepository[]
     */
    public function getOldestRepositories(): array {
        $response = $this->makeGet(
            'https://api.github.com/search/repositories',
            array(
                'q' => 'stars:>0',
                'sort' => 'stars',
                'order' => 'desc',
                'per_page' => 35,
            ),
        );

        $list = array_map(function (array $item) {
            return $this->parseRepository($item);
        }, $response->json()['items']);

        return $this->sortRepositoriesByDate($list);
    }
}
