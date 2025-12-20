<?php

use App\Models\Api\GithubCommitApi;
use Illuminate\Support\Facades\Http;

describe('GithubCommitApi', function () {
    it('should be able to load repository commits', function (array $commits, array $activity) {
        [$first, $last] = $commits;
        $baseUrl = 'https://api.github.com/repos/amiceli/preums/commits';
        $activityUrl = 'https://api.github.com/repos/amiceli/preums/stats/commit_activity';

        $firstUrl = url()->query(
            $baseUrl, array('page' => 1, 'per_page' => 1)
        );

        $nextUrl = url()->query(
            $baseUrl, array('page' => 2, 'per_page' => 1)
        );

        $lastUrl = url()->query(
            $baseUrl, array('page' => 8, 'per_page' => 10)
        );

        $linkHeaderValue = "<{$nextUrl}>; rel=\"next\", <{$lastUrl}>; rel=\"last\", <{$firstUrl}>; rel=\"first\"";

        Http::fake(array(
            $firstUrl => Http::response(
                array($first),
                200,
                array('Link' => $linkHeaderValue),
            ),
            $lastUrl => Http::response(
                array($last),
                200,
            ),
            $activityUrl => Http::response(
                $activity,
                200,
            ),
        ));

        Http::preventStrayRequests();

        [
            'totalCommits' => $totalCommits,
            'activity' => $activityDetails,
            'firstCommit' => $firstCommit,
            'lastCommit' => $lastCommit,
            'diff' => $diff,
        ] = GithubCommitApi::forRepository('amiceli/preums')->getCommits();

        expect($totalCommits)->toBe('8');
        expect($diff->days)->toBe(3);

        expect($firstCommit->url)->toBe($last['html_url']);
        expect($lastCommit->url)->toBe($first['html_url']);

        expect(
            $activityDetails['totalCommits']
        )->toBe(307);
        expect(
            $activityDetails['days']['Ma']
        )->toBe(77);

        Http::assertSentInOrder(
            array(
                fn ($r) => (
                    $r->method() === 'GET' && $r->url() === $firstUrl
                ),
                fn ($r) => (
                    $r->method() === 'GET' && $r->url() === $lastUrl
                ),
                fn ($r) => (
                    $r->method() === 'GET' && $r->url() === $activityUrl
                ),
            )
        );
        Http::assertNotSent(fn ($r) => (
            $r->method() === 'GET' && $r->url() === $nextUrl
        ));
    })->with('github-commits');
});
