<?php

namespace App\Http\Controllers;

use App\Models\GithubApi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GithubController extends Controller
{
    private GithubApi $client;

    public function __construct()
    {
        $this->client = new GithubApi();
    }

    public function index(): \Inertia\Response
    {
        $oldestRepos = $this->client->getOldestRepositories();
        $years = [];

        foreach ($oldestRepos as $key => $item) {
            $year = $item->createdAt->format("Y");
            $needKey = array_key_exists($year, $years) === false;

            if ($needKey) {
                $years[$year] = [];
            }

            array_push($years[$year], $item);
        }

        return Inertia::render("Welcome", [
            "oldestRepos" => $years,
        ]);
    }

    public function search(Request $req): \Inertia\Response
    {
        $value = $req->get("name");
        $res = $this->client->searchRepository($value);

        return Inertia::render("Search", $res);
    }
}
