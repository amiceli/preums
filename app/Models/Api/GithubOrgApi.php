<?php

namespace App\Models\Api;

use App\Models\Github\GithubOrg;
use App\Models\Github\GithubOwner;
use App\Models\Github\GithubRepository;
use DateTime;

class GithubOrgApi extends ApiClient {
    public static function forOrg(string $orgName) {
        return new GithubOrgApi($orgName);
    }

    private function parseOrg(array $org) {
        return new GithubOrg(
            name: $org['name'] ?? $org['login'],
            countRepos: $org['public_repos'],
            followers: $org['followers'],
            createdAt: new DateTime($org['created_at']),
            updatedAt: new DateTime($org['updated_at']),
            location: $org['location'],
            blog: $org['blog'] || null,
            url: $org['html_url'],
            avatarUrl: $org['avatar_url'],
        );
    }

    /**
     * @return array{
     *     org: GithubOrg,
     *     repositories: GithubRepository[],
     *     members: GithubOwner[]
     * }
     */
    public function getDetails(): array {
        $response = $this->makeGet(
            'https://api.github.com/orgs/'.$this->root
        );
        $repositories = GithubRepositoryApi::forOrganization(
            $this->root,
        )->getRepositories();
        $members = $this->makeGet(
            'https://api.github.com/orgs/'.$this->root.'/members'
        );

        return array(
            'org' => $this->parseOrg($response->json()),
            'repositories' => $repositories,
            'members' => array_map(function ($u) {
                return $this->parserOwner($u);
            }, $members->json()),
        );
    }
}
