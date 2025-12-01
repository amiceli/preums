<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use function GuzzleHttp\json_encode;

class GithubController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new \Github\Client();
    }

    private function mapRepos($response)
    {
        return array_map(function ($repo) {
            return [
                "id" => $repo["id"],
                "stars" => $repo["stargazers_count"],
                "name" => $repo["name"],
                "fullName" => $repo["full_name"],
                "description" => $repo["description"],
                "url" => $repo["html_url"],
                "createdAt" => $repo["created_at"],
                "updatedAt" => $repo["updated_at"],
                "language" => $repo["language"],
                "topics" => $repo["topics"],
                "watchers" => $repo["watchers"],
                "forks" => $repo["forks"],
                "owner" => [
                    "login" => $repo["owner"]["login"],
                    "id" => $repo["owner"]["id"],
                    "avatarUrl" => $repo["owner"]["avatar_url"],
                ],
            ];
        }, $response["items"]);
    }

    public function index()
    {
        $response = Http::get("https://api.github.com/search/repositories", [
            "q" => "stars:>0",
            "sort" => "stars",
            "order" => "desc",
            "per_page" => 12,
        ]);
        $oldestRepos = $this->mapRepos($response->json());

        usort($oldestRepos, function ($a, $b) {
            $ad = new DateTime($a["createdAt"]);
            $bd = new DateTime($b["createdAt"]);

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return Inertia::render("Welcome", [
            "oldestRepos" => $oldestRepos,
        ]);
    }

    public function search(Request $req)
    {
        $value = $req->input("framework");

        $response = Http::get("https://api.github.com/search/repositories", [
            "q" => "in:name $value",
            "sort" => "created",
            "order" => "asc",
        ]);

        $res = $response->json();
        $items = $this->mapRepos($res);

        return Inertia::render("Search", [
            "totalCount" => $res["total_count"],
            "items" => $items,
        ]);
    }
}
