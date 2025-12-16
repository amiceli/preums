<?php

namespace App\Models\Github;

class GithubOrg {
    public function __construct(
        public string $url,
        public string $avatarUrl,
        public string $name,
        public int $countRepos,
        public int $followers,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public ?string $location,
        public ?string $blog,
    ) {}
}
