<?php

namespace App\Models\Github;

class GithubUser extends GithubOwner {
    public readonly string $createdAtStr;

    public function __construct(
        public string $login,
        public string $avatarUrl,
        public string $url,
        public ?string $location,
        public ?string $blog,
        public ?string $company,
        public int $countRepos,
        public int $followers,
        public int $following,
        public \DateTime $createdAt,
    ) {
        parent::__construct(
            url: $url,
            login: $login,
            avatarUrl: $avatarUrl,
        );

        $this->createdAtStr = $this->createdAt->format('c');
    }
}
