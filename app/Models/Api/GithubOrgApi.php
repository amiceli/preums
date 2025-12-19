<?php

namespace App\Models\Api;

use App\Models\Github\GithubOrg;
use DateTime;

class GithubOrgApi extends ApiClient {
    public static function forOrg(string $orgName) {
        $apiUrl = "https://api.github.com/orgs/$orgName";

        return new GithubOrgApi(root: $apiUrl);
    }

    public static function get() {
        return new GithubOrgApi('');
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

    public function getDetails(): array {
        $response = $this->makeGet($this->root);
        $repositories = GithubRepositoryApi::forOrganization(
            $this->root,
        )->getRepositories();
        $members = $this->makeGet($this->root.'/members');

        return array(
            'org' => $this->parseOrg($response->json()),
            'repositories' => $repositories,
            'members' => array_map(function ($u) {
                return $this->parserOwner($u);
            }, $members->json()),
        );
    }
}
