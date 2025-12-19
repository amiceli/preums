<?php

namespace App\Models\Github;

class GithubUser {
    public function __construct(
        public string $login,
        public string $avatarUrl,
        public string $url,
    ) {}
}
