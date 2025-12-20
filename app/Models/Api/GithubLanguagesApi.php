<?php

namespace App\Models\Api;

class GithubLanguagesApi extends ApiClient {
    public static function forRepository(string $repoFullName) {
        return new GithubLanguagesApi(
            "https://api.github.com/repos/$repoFullName"
        );
    }

    private function calculatePercentage(array $langs) {
        $total = array_sum($langs);
        $percentages = array();

        foreach ($langs as $lang => $bytes) {
            $percentages[$lang] = round(($bytes / $total) * 100, 2);
        }

        return $percentages;
    }

    public function getLanguages(): array {
        $response = $this->makeGet($this->root.'/languages');

        return $this->calculatePercentage($response->json());
    }
}
