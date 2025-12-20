<?php

use App\Models\Api\GithubLanguagesApi;
use Illuminate\Support\Facades\Http;

describe('GithubLanguagesApi', function () {
    it('should be able to load repository languages', function (array $langs) {
        Http::fake(array(
            'https://api.github.com/repos/amiceli/preums/languages' => Http::response(
                $langs,
                200,
            ),
        ));

        $list = GithubLanguagesApi::forRepository(
            'amiceli/preums',
        )->getLanguages();

        expect(count($list))->toBe(count($langs));
        expect($list)->toMatchArray(array(
            'TypeScript' => 96.4,
            'Gherkin' => 3.25,
            'JavaScript' => 0.32,
            'Just' => 0.03,
        ));

        Http::assertSent(function ($r) {
            return $r->method() === 'GET' &&
                $r->url() ===
                    'https://api.github.com/repos/amiceli/preums/languages';
        });
    })->with('github-languages');
});
