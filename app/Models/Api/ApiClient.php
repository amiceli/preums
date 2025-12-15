<?php

namespace App\Models\Api;

use App\Models\ParseLinkHeader;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class ApiClient {
    private readonly string $token;

    protected readonly string $root;

    public function __construct(string $root = '') {
        $this->token = env('GITHUB_TOKEN');
        $this->root = $root;
    }

    protected function makeGet(
        string $url,
        ?array $options = null,
    ): Response {
        $response = Http::withHeaders(array(
            'Authorization' => 'Bearer '.$this->token,
        ))->get($url, $options);

        return $response;
    }

    protected function getLastPageUrl(Response $response): array|false {
        if ($response->header('link')) {
            $pages = new ParseLinkHeader($response->header('link'))->toArray();

            if (array_key_exists('last', $pages)) {
                return array(
                    'link' => $pages['last']['link'],
                    'count' => $pages['last']['page'],
                );
            }
        }

        return false;
    }
}
