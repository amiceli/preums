<?php

namespace App\Models;

use DateTime;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GithubApi
{
    private PendingRequest $http;

    public function __construct()
    {
        $token = env("GITHUB_TOKEN");
        $this->http = Http::withHeaders([
            "Authorization" => "Bearer $token",
        ]);
    }

    private function parseRepository($repo): GithubRepository
    {
        $owner = new GithubRepositoryOwner(
            $repo["owner"]["login"],
            $repo["owner"]["id"],
            $repo["owner"]["avatar_url"],
        );

        return new GithubRepository(
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
    }

    private function mapRepos(array $response): array
    {
        $list = array_map(function ($repo): GithubRepository {
            return $this->parseRepository($repo);
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
        $response = $this->http->get(
            "https://api.github.com/search/repositories",
            [
                "q" => "in:name $search",
                "sort" => "created",
                "order" => "asc",
                "per_page" => 12,
                "page" => 1,
            ],
        );

        $link = $response->header("Link");
        $items = $this->mapRepos($response->json());

        return [
            "link" => $link,
            "items" => $items,
        ];
    }

    /**
     * @return GithubRepository[]
     */
    public function getOldestRepositories(): array
    {
        $response = $this->http->get(
            "https://api.github.com/search/repositories",
            [
                "q" => "stars:>0",
                "sort" => "stars",
                "order" => "desc",
                "per_page" => 30,
            ],
        );

        $list = $this->mapRepos($response->json());

        return $list;
    }

    private function getRepoFirstReleases(string $url)
    {
        $response = $this->http->get("$url/releases", [
            "page" => 1,
            "per_page" => 10,
        ]);

        if ($response->header("link")) {
            $pages = new ParseLinkHeader($response->header("link"))->toArray();

            if (array_key_exists("last", $pages)) {
                $response = $this->http->get($pages["last"]["link"]);
            }
        }

        $releases = $response->json();

        $list = array_map(function ($item): GithubRelease {
            $reactions = array_key_exists("reactions", $item)
                ? $item["reactions"]
                : [];

            $reactions["total"] = array_key_exists("total_count", $reactions)
                ? $reactions["total_count"]
                : 0;

            unset($reactions["url"]);
            unset($reactions["total_count"]);

            return new GithubRelease(
                author: $item["author"]["login"],
                body: $item["body"],
                date: new \DateTime($item["created_at"]),
                reactions: $reactions,
                url: $item["html_url"],
                name: $item["name"],
            );
        }, $releases);

        usort($list, function ($a, $b) {
            $ad = $a->date;
            $bd = $b->date;

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return $list;
    }

    private function getRepoFirstCommit(string $url, string $date)
    {
        $time = new DateTime($date);
        $time->modify("+1 days");

        $response = $this->http->get("$url/commits", [
            "until" => $time->format(DateTime::ATOM),
        ]);

        $list = $response->json();

        usort($list, function ($a, $b) {
            $ad = new \DateTime($a["commit"]["committer"]["date"]);
            $bd = new \DateTime($b["commit"]["committer"]["date"]);

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        $split = array_slice(array_values($list), 0, 7);

        return array_map(function ($item): GithubCommit {
            return new GithubCommit(
                author: $item["commit"]["author"]["name"],
                sha: $item["sha"],
                message: $item["commit"]["message"],
                url: $item["html_url"],
                date: new \DateTime($item["commit"]["author"]["date"]),
            );
        }, $split);
    }

    private function getRepoTopics(string $url): array
    {
        $response = $this->http->get("$url/topics");

        return $response->json()["names"];
    }

    private function getRepoLanguages(string $url): array
    {
        $response = $this->http->get("$url/languages");

        return $response->json();
    }

    private function getRepoDetails(string $url)
    {
        $response = $this->http->get($url);

        return $response->json();
    }

    public function getRepository(string $org, string $repo)
    {
        $mainUrl = "https://api.github.com/repos/$org/$repo";
        $details = $this->getRepoDetails($mainUrl);

        $languages = $this->getRepoLanguages($mainUrl);
        $commit = $this->getRepoFirstCommit($mainUrl, $details["created_at"]);
        $topics = $this->getRepoTopics($mainUrl);
        $releases = $this->getRepoFirstReleases($mainUrl);

        return [
            "repository" => $this->parseRepository($details),
            "languages" => $languages,
            "commits" => $commit,
            "topics" => $topics,
            "releases" => $releases,
        ];
    }

    public function getRateLimit(): GithubRateLimit
    {
        $response = $this->http->get("https://api.github.com/rate_limit");
        $states = $response->json();

        $nextReset = new DateTime()->setTimestamp($states["rate"]["reset"]);

        return new GithubRateLimit(
            remaining: $states["rate"]["remaining"] > 0,
            nextReset: $nextReset,
        );
    }
}
