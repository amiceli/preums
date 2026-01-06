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
use App\Models\Github\GithubRepository;
use Illuminate\Support\Facades\Log;

class GithubApi extends ApiClient {
    /**
     * @return GithubRepository[]
     */
    public function getOldestStarredRepositories(): array {
        $repositories = GithubRepositoryApi::get()->getOldestStarredRepositories();

        return $repositories;
    }

    public function getOldestRepository(string $lang) {
        return GithubRepositoryApi::get()->getOldestRepository($lang);
    }

    public function getRecentRepository(string $lang) {
        return GithubRepositoryApi::get()->getRecentRepository($lang);
    }

    public function getStarredRepository(string $lang) {
        return GithubRepositoryApi::get()->getStarredRepository($lang);
    }

    public function searchRepositories(string $search) {
        return GithubRepositoryApi::get()->searchRepository($search);
    }

    public function getOrganizationDetails(string $orgName) {
        return GithubOrgApi::forOrg($orgName)->getOrganizationDetails();
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

        try {
            $states = $response->json();

            $nextReset = (new \DateTime())->setTimestamp($states['rate']['reset']);

            Log::info('action=rate_limit, next_reset='.$nextReset->format('c'));

            return new GithubRateLimit(
                remaining: $states['rate']['remaining'] > 0,
                nextReset: $nextReset,
            );
        } catch (\Exception $e) {
            Log::error('action=load_rate_limit, status=failed, status='.$response->status().", reason=$e");

            return new GithubRateLimit(
                remaining: false,
                nextReset: new \DateTime(),
            );
        }
    }

    private function getRepositories(array $tools) {
        return array_map(fn ($name) => (
            GithubRepositoryApi::fromName(
                $name
            )->getRepository()
        ), $tools);
    }

    public function getRoad(): array {
        $builtOn = $this->getRepositories(
            array(
                'laravel/laravel', 'vuejs/core',  'sqlite/sqlite', 'inertiajs/inertia', 'nanostores/nanostores',
            )
        );
        $beautifulCode = $this->getRepositories(
            array(
                'biomejs/biome', 'laravel/pint',   'zed-industries/zed',
            )
        );
        $UiUx = $this->getRepositories(
            array(
                'shoelace-style/webawesome', 'DerYeger/yeger',  'hackernoon/pixel-icon-library',
            )
        );
        $cleanRepo = $this->getRepositories(
            array(
                'evilmartians/lefthook',
                'conventional-changelog/commitlint',
                'pestphp/pest',
            )
        );
        $surprises = $this->getRepositories(
            array(
                'vwh/sqlite-online',
                'htzh/leanproved',
                'withastro/astro',
            )
        );

        return array_merge(
            array(),
            $builtOn,
            $beautifulCode,
            $UiUx,
            $cleanRepo,
            $surprises,
        );
    }
}
