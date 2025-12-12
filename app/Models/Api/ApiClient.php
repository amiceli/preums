<?php

namespace App\Models\Api;

use App\Models\ParseLinkHeader;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

abstract class ApiClient
{
    private readonly string $token;

    protected readonly string $root;

    public function __construct(string $root = "")
    {
        $this->token = env("GITHUB_TOKEN");
        $this->root = $root;
    }

    protected function makeGet(
        string $url,
        array|null $options = null,
    ): Response {
        $response = Http::withHeaders([
            "Authorization" => "Bearer " . $this->token,
        ])->get($url, $options);

        return $response;
    }

    protected function getLastPageUrl(Response $response): array|false
    {
        if ($response->header("link")) {
            $pages = new ParseLinkHeader($response->header("link"))->toArray();

            if (array_key_exists("last", $pages)) {
                return [
                    "link" => $pages["last"]["link"],
                    "count" => $pages["last"]["page"],
                ];
            }
        }

        return false;
    }
}
