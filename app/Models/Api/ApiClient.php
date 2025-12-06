<?php

namespace App\Models\Api;

use App\Models\ParseLinkHeader;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

abstract class ApiClient
{
    protected readonly PendingRequest $http;

    protected readonly string $mainUrl;

    public function __construct(string $mainUrl)
    {
        $token = env("GITHUB_TOKEN");
        $this->http = Http::withHeaders([
            "Authorization" => "Bearer $token",
        ]);
        $this->mainUrl = $mainUrl;
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
