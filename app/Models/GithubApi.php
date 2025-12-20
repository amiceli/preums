<?php

namespace App\Models;

use App\Models\Api\ApiClient;
use App\Models\Api\GithubCommitApi;
use App\Models\Api\GithubContributorsApi;
use App\Models\Api\GithubLanguagesApi;
use App\Models\Api\GithubOrgApi;
use App\Models\Api\GithubReleasesApi;
use App\Models\Api\GithubRepositoryApi;
use App\Models\Api\GithubTopicApi;
use App\Models\Api\GithubUserApi;
use DateTime;
use Illuminate\Support\Facades\Log;

class GithubApi extends ApiClient {
    public function getOldestRepositories() {
        return GithubRepositoryApi::get()->getOldestRepositories();
    }

    public function searchRepositories(string $search) {
        return GithubRepositoryApi::get()->searchRepository($search);
    }

    public function getOrg(string $orgName) {
        return GithubOrgApi::forOrg($orgName)->getDetails();
    }

    public function getUserHistory(string $userName) {
        return GithubUserApi::forUser($userName)->getHistory();
    }

    public function getRepository(string $org, string $repo) {
        $repoFullName = "$org/$repo";

        $details = GithubRepositoryApi::fromName(
            $repoFullName,
        )->getRepository();
        Log::info('action=load_repostiory_details, status=success');

        $commits = GithubCommitApi::forRepository(
            $repoFullName,
        )->getCommits();
        Log::info('action=load_repository_commits, status=success');

        $languages = GithubLanguagesApi::forRepository(
            $repoFullName,
        )->getLanguages();
        Log::info('action=load_repository_languages, status=success');

        $contributors = GithubContributorsApi::forRepository(
            $repoFullName,
        )->getContributors();
        Log::info('action=load_repository_contributors, status=success');

        $releases = GithubReleasesApi::forRepository(
            $repoFullName,
        )->getReleases();
        Log::info('action=load_rrepository_elease, status=success');

        $topics = GithubTopicApi::forRepository(
            $repoFullName
        )->getTopics();
        Log::info('action=load_trepository_opics, status=success');

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
