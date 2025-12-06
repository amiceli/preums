<?php

namespace App\Models\Api;

class GithubLanguagesApi extends ApiClient
{
    public static function forRepository(string $url)
    {
        return new GithubLanguagesApi($url);
    }

    private function calculatePercentage(array $langs)
    {
        $total = array_sum($langs);
        $percentages = [];

        foreach ($langs as $lang => $bytes) {
            $percentages[$lang] = round(($bytes / $total) * 100, 2);
        }

        return $percentages;
    }

    public function getRepoLanguages(): array
    {
        $response = $this->http->get($this->mainUrl . "/languages");

        return $this->calculatePercentage($response->json());
    }
}
