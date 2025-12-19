<?php

namespace App\Models\Api;

class GithubUserApi extends ApiClient {
    public static function forUser(string $orgName) {
        $apiUrl = "https://api.github.com/users/$orgName";

        return new GithubUserApi(root: $apiUrl);
    }

    public static function get() {
        return new GithubUserApi('');
    }

    public function getHistory() {
        $user = $this->makeGet($this->root);
        $repositories = GithubRepositoryApi::forUser(
            $this->root,
        )->getRepositories();

        return array(
            'user' => $this->parseUser($user->json()),
            'repositories' => $repositories,
        );
    }
}
