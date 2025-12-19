<?php

use App\Models\Api\ApiClient;

it('should parse github owner from api', function (array $owner) {
    class TestParserOwner extends ApiClient {
        public function parse(array $item) {
            return $this->parserOwner($item);
        }
    }

    $result = (new TestParserOwner())->parse($owner);

    expect($result->avatarUrl)->toBe(
        $owner['avatar_url']
    );
    expect($result->login)->toBe(
        $owner['login']
    );
    expect($result->url)->toBe(
        $owner['html_url']
    );
})->with('github-owner');
