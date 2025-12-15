<?php

namespace App\Models\Api;

use App\Models\Github\GithubRelease;

class GithubReleasesApi extends ApiClient {
    public static function forRepository(string $url) {
        return new GithubReleasesApi(root: $url);
    }

    private function parseRelease(array $item): GithubRelease {
        $reactions = array_key_exists('reactions', $item)
            ? $item['reactions']
            : array();

        unset($reactions['url']);
        unset($reactions['total_count']);

        return new GithubRelease(
            name: $item['tag_name'],
            date: new \DateTime($item['created_at']),
            author: $item['author']['login'],
            url: $item['html_url'],
            body: $item['body'],
            reactions: $reactions,
            authorImg: $item['author']['avatar_url'],
            authorUrl: $item['author']['html_url'],
        );
    }

    public function getReleases() {
        $response = $this->makeGet($this->root.'/releases', array(
            'page' => 1,
            'per_page' => 1,
        ));
        $list = $response->json();

        if (count($list) === 0) {
            return array(
                'totalReleases' => 0,
            );
        }

        $lastRelease = $this->parseRelease($list[0]);
        $firstRelease = null;

        $lastPage = $this->getLastPageUrl($response);

        if ($lastPage) {
            $response = $this->makeGet($lastPage['link']);
            $firstRelease = $this->parseRelease($response->json()[0]);
        }

        return array(
            'totalReleases' => $lastPage['count'],
            'lastRelease' => $lastRelease,
            'firstRelease' => $firstRelease,
            'diff' => $firstRelease
                ? $lastRelease->date->diff($firstRelease->date)
                : -1,
        );
    }
}
