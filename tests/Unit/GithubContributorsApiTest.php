<?php

use App\Models\Api\GithubContributorsApi;
use App\Models\Github\GithubContributor;
use Illuminate\Support\Facades\Http;

describe('GithubContributorsApi', function () {
    it('should be able to load repository contributors', function (array $contributors) {
        Http::fake(array(
            'https://api.github.com/repos/amiceli/preums/stats/contributors' => Http::response(
                $contributors,
                200,
            ),
        ));

        $list = GithubContributorsApi::forRepository(
            'amiceli/preums',
        )->getContributors();

        expect(count($list))->toBe(count($contributors));

        expect(
            $list[0]
        )->toMatchObject(
            new GithubContributor(
                author: $contributors[0]['author']['login'],
                authorImg: $contributors[0]['author']['avatar_url'],
            )
        );

        Http::assertSent(function ($r) {
            return $r->method() === 'GET' &&
                   $r->url() === 'https://api.github.com/repos/amiceli/preums/stats/contributors';
        });
    })->with('github-contributors');
});
