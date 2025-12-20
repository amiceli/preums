<?php

namespace App\Models\Api;

class GithubUserApi extends ApiClient {
    public static function forUser(string $userName) {
        return new GithubUserApi($userName);
    }

    public static function get() {
        return new GithubUserApi('');
    }

    public function getHistory() {
        $user = $this->makeGet(
            'https://api.github.com/users/'.$this->root
        );
        $repositories = GithubRepositoryApi::forUser(
            $this->root,
        )->getRepositories();

        return array(
            'user' => $this->parseUser($user->json()),
            'repositories' => $repositories,
        );
    }
}
