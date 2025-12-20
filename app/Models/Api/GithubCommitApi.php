<?php

namespace App\Models\Api;

use App\Models\Github\GithubCommit;

class GithubCommitApi extends ApiClient {
    private function parseCommit($item) {
        return new GithubCommit(
            author: $item['commit']['author']['name'],
            authorImg: $item['author'] ? $item['author']['avatar_url'] : null,
            authorUrl: $item['author'] ? $item['author']['html_url'] : null,
            sha: $item['sha'],
            message: $item['commit']['message'],
            url: $item['html_url'],
            date: new \DateTime($item['commit']['author']['date']),
            treeUrl: $item['commit']['tree']['url'],
        );
    }

    public static function forRepository(string $repoFullName) {
        return new GithubCommitApi(
            "https://api.github.com/repos/$repoFullName"
        );
    }

    private function sumWeeksDay(array $activity, int $index) {
        return array_sum(
            array_map(function ($item) use ($index) {
                return $item['days'][$index];
            }, $activity),
        );
    }

    private function getCommitsActivity() {
        $response = $this->makeGet($this->root.'/stats/commit_activity');
        $activy = $response->json();

        $totalCommits = array_sum(
            array_map(function ($item) {
                return $item['total'];
            }, $activy),
        );

        $days = array(
            'Di' => $this->sumWeeksDay($activy, 0),
            'Lu' => $this->sumWeeksDay($activy, 1),
            'Ma' => $this->sumWeeksDay($activy, 2),
            'Mr' => $this->sumWeeksDay($activy, 3),
            'Je' => $this->sumWeeksDay($activy, 4),
            'Ve' => $this->sumWeeksDay($activy, 5),
            'Sa' => $this->sumWeeksDay($activy, 6),
        );

        return array(
            'totalCommits' => $totalCommits,
            'days' => $days,
        );
    }

    public function getCommits() {
        $response = $this->makeGet($this->root.'/commits', array(
            'page' => 1,
            'per_page' => 1,
        ));
        $lastCommit = $this->parseCommit($response->json()[0]);
        $activity = $this->getCommitsActivity();

        $firstCommit = null;
        $lastPage = $this->getLastPageUrl($response);

        if ($lastPage) {
            $lastResponse = $this->makeGet($lastPage['link']);
            $firstCommit = $this->parseCommit($lastResponse->json()[0]);
        }

        return array(
            'totalCommits' => $lastPage['count'],
            'lastCommit' => $lastCommit,
            'firstCommit' => $firstCommit,
            'activity' => $activity,
            'diff' => $firstCommit
                ? $lastCommit->date->diff($firstCommit->date)
                : -1,
        );
    }
}
