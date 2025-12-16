<?php

namespace App\Http\Controllers;

use App\Models\GithubApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class GithubController extends Controller {
    private GithubApi $client;

    public function __construct() {
        $this->client = new GithubApi();
    }

    public function rateLimit() {
        return Inertia::render('RateLimit', array(
            'nextReset' => Session::get('nextReset'),
        ));
    }

    public function index(): \Inertia\Response {
        $oldestRepos = $this->client->getOldestRepositories();
        $years = array();

        foreach ($oldestRepos as $key => $item) {
            $year = $item->createdAt->format('Y');
            $needKey = array_key_exists($year, $years) === false;

            if ($needKey) {
                $years[$year] = array();
            }

            array_push($years[$year], $item);
        }

        return Inertia::render('OldestRepositories', array(
            'oldestRepos' => $years,
        ));
    }

    public function search(Request $req): \Inertia\Response {
        $value = $req->get('name');
        $res = $this->client->searchRepository($value);

        return Inertia::render('SearchResults', $res);
    }

    public function showOrgHistory(string $org) {
        $org = $this->client->getOrg($org);

        return Inertia::render('OrgHistory', array(
            'org' => $org,
        ));
    }

    public function showUserHistory(string $name) {
        $org = $this->client->getOrg($name);

        return Inertia::render('UserHistory', array(
            'org' => $org,
        ));
    }

    public function showRepositoryHistory(string $org, string $repo) {
        $repository = $this->client->getRepository($org, $repo);

        return Inertia::render('RepositoryHistory', $repository);
    }
}
