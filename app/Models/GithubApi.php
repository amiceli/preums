<?php

namespace App\Models;

use App\Models\Api\ApiClient;
use App\Models\Api\GithubCommitApi;
use App\Models\Api\GithubContributorsApi;
use App\Models\Api\GithubLanguagesApi;
use App\Models\Api\GithubReleasesApi;
use App\Models\Api\GithubRepositoryApi;
use DateTime;
use Illuminate\Support\Facades\Log;

class GithubApi extends ApiClient {
    public function getOldestRepositories() {
        return GithubRepositoryApi::get()->getOldestRepositories();
    }

    public function searchRepository(string $search) {
        return GithubRepositoryApi::get()->searchRepository($search);
    }

    private function getRepoTopics(string $url): array {
        $response = $this->makeGet("$url/topics", null);

        return $response->json()['names'];
    }

    public function getRepository(string $org, string $repo) {
        $repoApiUrl = "https://api.github.com/repos/$org/$repo";
        Log::info("action=get_repository, org=$org, repo=$repo");

        $details = GithubRepositoryApi::forRepository(
            $repoApiUrl,
        )->getRepository();
        Log::info('action=load_details, status=success');

        $commits = GithubCommitApi::forRepository(
            $repoApiUrl,
        )->getRepositoryCommits();
        Log::info('action=load_commits, status=success');

        $languages = GithubLanguagesApi::forRepository(
            $repoApiUrl,
        )->getRepoLanguages();
        Log::info('action=load_languages, status=success');

        $contributors = GithubContributorsApi::forRepository(
            $repoApiUrl,
        )->getContributors();
        Log::info('action=load_contributors, status=success');

        $topics = $this->getRepoTopics($repoApiUrl);
        Log::info('action=load_topics, status=success');

        $releases = GithubReleasesApi::forRepository(
            $repoApiUrl,
        )->getReleases();
        Log::info('action=load_release, status=success');

        return array(
            'repository' => $details,
            'commits' => $commits,
            'languages' => $languages,
            'topics' => $topics,
            'releases' => $releases,
            'contributors' => $contributors,
        );
    }

    public function getRateLimit(): GithubRateLimit {
        $response = $this->makeGet('https://api.github.com/rate_limit', null);
        $states = $response->json();

        $nextReset = new DateTime()->setTimestamp($states['rate']['reset']);

        return new GithubRateLimit(
            remaining: $states['rate']['remaining'] > 0,
            nextReset: $nextReset,
        );
    }
}
