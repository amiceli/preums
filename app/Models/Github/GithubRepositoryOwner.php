<?php

namespace App\Models\Github;

class GithubRepositoryOwner
{
    public function __construct(
        public string $login,
        public string $id,
        public string $avatarUrl,
    ) {}
}
