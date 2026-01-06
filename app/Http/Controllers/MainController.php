<?php

namespace App\Http\Controllers;

use App\Models\FrozenRepository;
use App\Models\GithubApi;
use App\Models\ProLang;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class MainController extends Controller {
    private GithubApi $client;

    public function __construct() {
        $this->client = new GithubApi();
    }

    /**
     * Homepage show oldest and starred repositories
     */
    public function index(): \Inertia\Response {
        $repositories = FrozenRepository::orderBy('year')
            ->orderBy('githubCreatedAt', 'asc')
            ->get()
            ->groupBy('year')
            ->toArray();

        $allLangs = ProLang::pluck('name')->toArray();

        shuffle($allLangs);

        return Inertia::render('HomePage', array(
            'oldestRepos' => $repositories,
            'allLangs' => $allLangs,
        ));
    }

    /**
     * Show result of search repository by name
     */
    public function search(Request $req): \Inertia\Response {
        $value = $req->get('name');
        $repositories = $this->client->searchRepositories($value);

        Log::info('action=search_repositories, value='.$value);

        return Inertia::render('SearchResults', array(
            'repositories' => $repositories,
        ));
    }

    /**
     * Show organization history / details
     */
    public function showOrganizationHistory(string $org) {
        $details = $this->client->getOrganizationDetails($org);

        Log::info('action=show_organization, name='.$org);

        return Inertia::render('OrgHistory', $details);
    }

    /**
     * Show user history
     */
    public function showUserHistory(string $name) {
        $userHistory = $this->client->getUserHistory($name);

        Log::info('action=show_user, name='.$name);

        return Inertia::render('UserHistory', $userHistory);
    }

    /**
     * Show repository history
     */
    public function showRepositoryHistory(string $org, string $repo) {
        $repository = $this->client->getRepository($org, $repo);

        Log::info("action=show_repository, org=$org, repo=$repo");

        return Inertia::render('RepositoryHistory', $repository);
    }

    /**
     * Handle Github API rate limit
     */
    public function rateLimit() {
        return Inertia::render('RateLimit', array(
            'nextReset' => Session::get('nextReset'),
        ));
    }

    /**
     * Search oldest repository by language
     */
    public function searchOldestRepository(Request $r) {
        $lang = $r->json()->get('lang');
        $data = $this->client->getOldestRepository($lang);

        return response()->json(
            $data
        );
    }

    /**
     * Search recent repository by language
     */
    public function searchRecentRepository(Request $r) {
        $lang = $r->json()->get('lang');
        $data = $this->client->getRecentRepository($lang);

        return response()->json(
            $data
        );
    }

    /**
     * Search starred repository by lang
     */
    public function searchStarredRepository(Request $r) {
        $lang = $r->json()->get('lang');
        $data = $this->client->getStarredRepository($lang);

        return response()->json(
            $data
        );
    }

    /**
     * Show lang history page
     */
    public function langHistory() {
        $groups = YearGroup::with('languages.authors')
            ->orderBy('position', 'ASC')
            ->get();

        return Inertia::render('LangHistory', array(
            'groups' => $groups,
        ));
    }

    public function road() {
        Log::info('action=show_road');

        return Inertia::render('Road', array(
            'beautifulCode' => FrozenRepository::whereIn('fullName', array(
                'biomejs/biome', 'laravel/pint',   'zed-industries/zed',
            ))->get()->toArray(),
            'builtOn' => FrozenRepository::whereIn('fullName', array(
                'laravel/laravel', 'vuejs/core',  'sqlite/sqlite', 'inertiajs/inertia', 'nanostores/nanostores',
            ))->get()->toArray(),
            'UiUx' => FrozenRepository::whereIn('fullName', array(
                'shoelace-style/webawesome', 'DerYeger/yeger',  'hackernoon/pixel-icon-library',
            ))->get()->toArray(),
            'cleanRepo' => FrozenRepository::whereIn('fullName', array(
                'evilmartians/lefthook',
                'conventional-changelog/commitlint',
                'pestphp/pest',
            ))->get()->toArray(),
            'surprises' => FrozenRepository::whereIn('fullName', array(
                'vwh/sqlite-online',
                'htzh/leanproved',
                'withastro/astro',
            ))->get()->toArray(),
        ));
    }
}
