<?php

use App\Models\Api\ApiClient;

describe('ApiClient', function () {
    class GithubParserTest extends ApiClient {
        public function parserOwnerTest(array $item) {
            return $this->parserOwner($item);
        }

        public function parseUserTest(array $item) {
            return $this->parseUser($item);
        }
    }

    it('should parse github owner from api', function (array $owner) {
        $result = new GithubParserTest()->parserOwnerTest($owner);

        expect($result)->toMatchObject(array(
            'avatarUrl' => $owner['avatar_url'],
            'login' => $owner['login'],
            'url' => $owner['html_url'],
        ));
    })->with('github-user');

    it('should parse github user from api', function (array $user) {
        $result = new GithubParserTest()->parseUserTest($user);
        $expectedDateTime = new \DateTime(
            $user['created_at']
        );

        expect($result)->toMatchObject(array(
            'avatarUrl' => $user['avatar_url'],
            'login' => $user['login'],
            'url' => $user['html_url'],
            'location' => $user['location'],
            'blog' => $user['blog'],
            'company' => $user['company'],
            'countRepos' => $user['public_repos'],
            'followers' => $user['followers'],
            'following' => $user['following'],
            'createdAt' => $expectedDateTime,
            'createdAtStr' => $expectedDateTime->format('c'),
        ));
    })->with('github-user');
});
