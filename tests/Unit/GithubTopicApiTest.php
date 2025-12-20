<?php

use App\Models\Api\GithubTopicApi;
use Illuminate\Support\Facades\Http;

describe('GithubTopicApi', function () {
    it('should be able to load repository contributors', function () {
        $expectedUrl = 'https://api.github.com/repos/amiceli/preums/topics';

        Http::fake(array(
            $expectedUrl => Http::response(
                array(
                    'names' => array('Php', 'JavaScript', 'Go'),
                ),
                200,
            ),
        ));

        $list = GithubTopicApi::forRepository('amiceli/preums')->getTopics();

        expect(count($list))->toBe(3);
        expect($list)->toMatchArray(array('Php', 'JavaScript', 'Go'));

        Http::assertSent(function ($r) use ($expectedUrl) {
            return $r->method() === 'GET' && $r->url() === $expectedUrl;
        });
    });
});
