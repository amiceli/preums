<?php

namespace App\Models;

use App\Models\Api\GithubCommitApi;
use App\Models\Api\GithubContributorsApi;
use App\Models\Api\GithubLanguagesApi;
use App\Models\Api\GithubReleasesApi;
use App\Models\Api\GithubRepositoryApi;
use DateTime;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function getOldestRepositories()
    {
        return GithubRepositoryApi::get()->getOldestRepositories();
    }

    public function searchRepository(string $search)
    {
        return GithubRepositoryApi::get()->searchRepository($search);
    }

    private function getRepoTopics(string $url): array
    {
        $response = $this->http->get("$url/topics");

        return $response->json()["names"];
    }

    public function getRepository(string $org, string $repo)
    {
        $mainUrl = "https://api.github.com/repos/$org/$repo";
        Log::info("action=get_repository, org=$org, repo=$repo");

        $details = GithubRepositoryApi::forRepository(
            $mainUrl,
        )->getRepository();
        Log::info("action=load_details, status=success");

        $commits = GithubCommitApi::forRepository(
            $mainUrl,
        )->getRepositoryCommits();
        Log::info("action=load_commits, status=success");

        $languages = GithubLanguagesApi::forRepository(
            $mainUrl,
        )->getRepoLanguages();
        Log::info("action=load_languages, status=success");

        $contributors = GithubContributorsApi::forRepository(
            $mainUrl,
        )->getContributors();
        Log::info("action=load_contributors, status=success");

        $topics = $this->getRepoTopics($mainUrl);
        Log::info("action=load_topics, status=success");

        $releases = GithubReleasesApi::forRepository($mainUrl)->getReleases();
        Log::info("action=load_release, status=success");

        return [
            "repository" => $details,
            "commits" => $commits,
            "languages" => $languages,
            "topics" => $topics,
            "releases" => $releases,
            "contributors" => $contributors,
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
