<?php

use App\Models\Api\GithubReleasesApi;
use Illuminate\Support\Facades\Http;

describe('GithubReleasesApi', function () {
    it('should be able to load first release', function (array $releases) {
        [$first] = $releases;
        $baseUrl = 'https://api.github.com/repos/amiceli/preums/releases';

        $firstUrl = url()->query(
            $baseUrl, array('page' => 1, 'per_page' => 1)
        );

        // $linkHeaderValue = "<{$firstUrl}>; rel=\"next\", <{$firstUrl}>; rel=\"last\", <{$firstUrl}>; rel=\"first\"";

        Http::fake(array(
            $firstUrl => Http::response(
                array($first),
                200,
                // array('Link' => $linkHeaderValue),
            ),
        ));

        Http::preventStrayRequests();

        [
            'totalReleases' => $totalReleases,
            'diff' => $diff,
            'firstRelease' => $firstRelease,
            'lastRelease' => $lastRelease,
        ] = GithubReleasesApi::forRepository('amiceli/preums')->getReleases();

        expect($totalReleases)->toBe(1);
        expect($diff)->toBe(-1);

        expect($lastRelease->name)->toBe($first['name']);
        expect($firstRelease)->toBe(null);

        Http::assertSent(
            fn ($r) => (
                $r->method() === 'GET' && $r->url() === $firstUrl
            )
        );
    })->with('github-releases');

    it('should be able to load first and last releases', function (array $releases) {
        [$first, $last] = $releases;
        $baseUrl = 'https://api.github.com/repos/amiceli/preums/releases';

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
        ));

        Http::preventStrayRequests();

        [
            'totalReleases' => $totalReleases,
            'diff' => $diff,
            'firstRelease' => $firstRelease,
            'lastRelease' => $lastRelease,
        ] = GithubReleasesApi::forRepository('amiceli/preums')->getReleases();

        expect($totalReleases)->toBe('8');
        expect($diff->days)->toBe(7);

        expect($firstRelease->name)->toBe($last['name']);
        expect($lastRelease->name)->toBe($first['name']);

        Http::assertSentInOrder(
            array(
                fn ($r) => (
                    $r->method() === 'GET' && $r->url() === $firstUrl
                ),
                fn ($r) => (
                    $r->method() === 'GET' && $r->url() === $lastUrl
                ),
            )
        );
        Http::assertNotSent(fn ($r) => (
            $r->method() === 'GET' && $r->url() === $nextUrl
        ));
    })->with('github-releases');
});
