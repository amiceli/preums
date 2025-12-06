<?php

namespace App\Models\Api;

use App\Models\Github\GithubCommit;

class GithubCommitApi extends ApiClient
{
    private function parseCommit($item)
    {
        return new GithubCommit(
            author: $item["commit"]["author"]["name"],
            authorImg: $item["author"]
                ? $item["author"]["avatar_url"]
                : null,
            authorUrl: $item["author"]
                ? $item["author"]["html_url"]
                : null,
            sha: $item["sha"],
            message: $item["commit"]["message"],
            url: $item["html_url"],
            date: new \DateTime($item["commit"]["author"]["date"]),
        );
    }

    public static function forRepository(string $url)
    {
        return new GithubCommitApi(mainUrl: $url);
    }

    public function getRepositoryCommits()
    {
        $response = $this->http->get($this->mainUrl . "/commits", [
            "page" => 1,
            "per_page" => 1,
        ]);
        $lastCommit = $this->parseCommit($response->json()[0]);
        $firstCommit = null;

        $lastPage = $this->getLastPageUrl($response);

        if ($lastPage) {
            $response = $this->http->get($lastPage["link"]);
            $firstCommit = $this->parseCommit($response->json()[0]);
        }

        return [
            "totalCommits" => $lastPage["count"],
            "lastCommit" => $lastCommit,
            "firstCommit" => $firstCommit,
            "diff" => $firstCommit
                ? $lastCommit->date->diff($firstCommit->date)
                : -1,
        ];
    }
}
