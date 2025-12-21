<?php

namespace App\Http\Controllers;

use App\Models\GithubApi;
use App\Models\LangStats;
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

    public function oldStars(Request $r) {
        $lang = $r->json()->get('lang');
        $data = $this->client->getOldStarred($lang);

        return response()->json(
            $data
        );
    }

    // TODO move year logic in Model
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

        return Inertia::render('HomePage', array(
            'oldestRepos' => $years,
        ));
    }

    public function search(Request $req): \Inertia\Response {
        $value = $req->get('name');
        $repositories = $this->client->searchRepositories($value);

        return Inertia::render('SearchResults', array(
            'repositories' => $repositories,
        ));
    }

    public function showOrgHistory(string $org) {
        $details = $this->client->getOrg($org);

        return Inertia::render('OrgHistory', $details);
    }

    public function showUserHistory(string $name) {
        $userHistory = $this->client->getUserHistory($name);

        return Inertia::render('UserHistory', $userHistory);
    }

    public function showRepositoryHistory(string $org, string $repo) {
        $repository = $this->client->getRepository($org, $repo);

        return Inertia::render('RepositoryHistory', $repository);
    }

    public function languages(string $iso) {
        // $isos = DB::table('lang_stats')
        $isos = LangStats::distinct()
            ->pluck('iso_code')
            ->toArray();

        $langs = LangStats::where('iso_code', strtoupper($iso))
            ->orderBy('pushers', 'desc')
            ->orderBy('year', 'desc')
            ->get()
            ->groupBy('year');

        return Inertia::render('LanguageStats', array(
            'langs' => $langs,
            'currentIso' => $iso,
            'isoList' => $isos,
        ));
    }

    public function road() {
        return Inertia::render('Road', array());
    }
}
