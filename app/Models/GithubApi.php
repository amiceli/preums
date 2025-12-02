<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class GithubItemOwner
{
    public function __construct(
        public string $login,
        public string $id,
        public string $avatarUrl,
    ) {}
}

class GithubItem
{
    public readonly string $createdAtStr;

    public readonly string $updatedAtStr;

    /**
     * @param string[] $topics
     */
    public function __construct(
        public string $id,
        public int $stars,
        public string $name,
        public string $fullName,
        public string|null $description,
        public string $url,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public string|null $language,
        public array $topics,
        public int $watchers,
        public int $forks,
        public GithubItemOwner $owner,
    ) {
        $this->createdAtStr = $this->createdAt->format('c');
        $this->updatedAtStr = $this->updatedAt->format('c');
    }
}

class GithubApi
{
    private function mapRepos(array $response): array
    {
        $list = array_map(function ($repo): GithubItem {
            $owner = new GithubItemOwner(
                $repo["owner"]["login"],
                $repo["owner"]["id"],
                $repo["owner"]["avatar_url"],
            );

            return new GithubItem(
                $repo["id"],
                $repo["stargazers_count"],
                $repo["name"],
                $repo["full_name"],
                $repo["description"],
                $repo["html_url"],
                new \DateTime($repo["created_at"]),
                new \DateTime($repo["updated_at"]),
                $repo["language"],
                $repo["topics"],
                $repo["watchers"],
                $repo["forks"],
                $owner,
            );
        }, $response["items"]);

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

    public function searchRepository(string $search)
    {
        $response = Http::get("https://api.github.com/search/repositories", [
            "q" => "in:name $search",
            "sort" => "created",
            "order" => "asc",
            "per_page" => 12,
            "page" => 1,
        ]);

        $link = $response->header("Link");
        $items = $this->mapRepos($response->json());

        return [
            "link" => $link,
            "items" => $items,
        ];
    }

    /**
     * @return GithubItem[]
     */
    public function getOldestRepositories() : array
    {
        $response = Http::get("https://api.github.com/search/repositories", [
            "q" => "stars:>0",
            "sort" => "stars",
            "order" => "desc",
            "per_page" => 30,
        ]);

        $list = $this->mapRepos($response->json());

        return $list;
    }
}
