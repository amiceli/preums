<?php

namespace App\Models\Api;

use App\Models\Github\GithubRepositoryOwner;
use App\Models\Github\GithubRepository;
use DateTime;

class GithubRepositoryApi extends ApiClient
{
    public static function forRepository(string $url)
    {
        return new GithubRepositoryApi(root: $url);
    }

    public static function get()
    {
        return new GithubRepositoryApi("");
    }

    private function parseRepository(array $item): GithubRepository
    {
        return new GithubRepository(
            id: $item["id"],
            stars: $item["stargazers_count"],
            name: $item["name"],
            fullName: $item["full_name"],
            description: $item["description"],
            url: $item["html_url"],
            createdAt: new DateTime($item["created_at"]),
            updatedAt: new DateTime($item["updated_at"]),
            language: $item["language"],
            topics: $item["topics"],
            watchers: $item["watchers"],
            forks: $item["forks"],
            owner: new GithubRepositoryOwner(
                login: $item["owner"]["login"],
                id: $item["owner"]["id"],
                avatarUrl: $item["owner"]["avatar_url"],
            ),
        );
    }

    public function searchRepository(string $search)
    {
        $response = $this->makeGet(
            "https://api.github.com/search/repositories",
            [
                "q" => "in:name $search",
                "sort" => "created",
                "order" => "asc",
                "per_page" => 20,
                "page" => 1,
            ],
        );

        $list = array_map(function (array $item) {
            return $this->parseRepository($item);
        }, $response->json()["items"]);

        return [
            "items" => $list,
        ];
    }

    public function getRepository()
    {
        $response = $this->makeGet($this->root);

        return $this->parseRepository($response->json());
    }

    /**
     * @return GithubRepository[]
     */
    public function getOldestRepositories()
    {
        $response = $this->makeGet(
            "https://api.github.com/search/repositories",
            [
                "q" => "stars:>0",
                "sort" => "stars",
                "order" => "desc",
                "per_page" => 35,
            ],
        );

        $list = array_map(function (array $item) {
            return $this->parseRepository($item);
        }, $response->json()["items"]);

        usort($list, function ($a, $b) {
            $ad = $a->createdAt;
            $bd = $b->createdAt;

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return $list;
    }
}
