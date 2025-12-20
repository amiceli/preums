<?php

namespace App\Models\Api;

class GithubTopicApi extends ApiClient {
    public static function forRepository(string $repoFullName) {
        return new GithubTopicApi(
            "https://api.github.com/repos/$repoFullName"
        );
    }

    public function getTopics(): array {
        $response = $this->makeGet(
            $this->root.'/topics', null
        );

        return $response->json()['names'];
    }
}
