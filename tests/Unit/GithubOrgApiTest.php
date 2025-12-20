<?php

use App\Models\Api\GithubOrgApi;
use App\Models\Github\GithubOwner;
use Illuminate\Support\Facades\Http;

describe('GithubOrgApiTest', function () {
    it('should load org details', function (
        array $org,
        array $repos,
        array $members,
    ) {
        $prgBaseUrl = 'https://api.github.com/orgs/PSN';

        Http::fake(array(
            $prgBaseUrl => Http::response($org, 200),
            "$prgBaseUrl/members" => Http::response($members, 200),
            "$prgBaseUrl/repos" => Http::response($repos, 200),
        ));

        $details = GithubOrgApi::forOrg('PSN')->getDetails();

        expect(
            $details['org']->name
        )->toBe($org['name']);

        expect(
            $details['org']->avatarUrl
        )->toBe($org['avatar_url']);

        expect($details['members'][0])->toMatchObject(
            new GithubOwner(
                login: $members[0]['login'],
                url: $members[0]['html_url'],
                avatarUrl: $members[0]['avatar_url'],
            ),
        );

        Http::assertSent(function ($r) use ($prgBaseUrl) {
            return $r->url() === $prgBaseUrl && $r->method() === 'GET';
        });
        Http::assertSent(function ($r) use ($prgBaseUrl) {
            return $r->url() === "$prgBaseUrl/members" &&
                $r->method() === 'GET';
        });
        Http::assertSent(function ($r) use ($prgBaseUrl) {
            return $r->url() === "$prgBaseUrl/repos" && $r->method() === 'GET';
        });
    })->with('github-org-history');
});
