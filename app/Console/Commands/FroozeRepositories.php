<?php

namespace App\Console\Commands;

use App\Models\FrozenRepository;
use App\Models\GithubApi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FroozeRepositories extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:frooze-repositories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load and frooze oldest and starred repositories';

    /**
     * Execute the console command.
     */
    public function handle() {
        $client = new GithubApi();
        $repositories = $client->getOldestStarredRepositories();

        FrozenRepository::truncate();

        FrozenRepository::insert(
            array_map(
                function ($rep) {
                    return array(
                        'created_at' => Carbon::now(),
                        'name' => $rep->name,
                        'githubId' => $rep->id,
                        'fullName' => $rep->fullName,
                        'description' => $rep->description,
                        'url' => $rep->url,
                        'githubCreatedAt' => $rep->createdAt,
                        'githubUpdatedAt' => $rep->updatedAt,
                        'language' => $rep->language,
                        'topics' => json_encode($rep->topics),
                        'watchers' => $rep->watchers,
                        'forks' => $rep->forks,
                        'stars' => $rep->stars,
                        'ownerIsOrganization' => $rep->ownerIsOrganization,
                        'ownerLogin' => $rep->owner->login,
                        'ownerGithubId' => $rep->owner->id,
                        'ownerAvatarUrl' => $rep->owner->avatarUrl,
                        'year' => $rep->createdAt->format('Y'),
                    );
                }, $repositories)
        );
    }
}
