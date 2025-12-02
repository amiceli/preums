<?php

namespace App\Models;

class GithubRepositoryOwner
{
    public function __construct(
        public string $login,
        public string $id,
        public string $avatarUrl,
    ) {}
}
