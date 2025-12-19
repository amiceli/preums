<?php

namespace App\Models\Github;

class GithubOwner {
    public function __construct(
        public string $login,
        public string $avatarUrl,
        public string $url,
    ) {}
}
