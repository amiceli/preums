<?php

namespace App\Models\Api;

use App\Models\Github\GithubContributor;

class GithubContributorsApi extends ApiClient
{
    public static function forRepository(string $url)
    {
        return new GithubContributorsApi($url);
    }

    private function parseContributor(array $item): GithubContributor
    {
        return new GithubContributor(
            total: $item["total"],
            author: $item["author"]["login"],
            authorImg: $item["author"]["avatar_url"],
        );
    }

    public function getContributors(): array
    {
        $response = $this->http->get($this->mainUrl . "/stats/contributors");
        $list = $response->json();

        return array_map(function ($item) {
            return $this->parseContributor($item);
        }, $list);
    }
}
