<?php

namespace App\Models\Github;

class GithubContributor
{
    public function __construct(
        public int $total,
        public string $author,
        public string $authorImg,
    ) {}
}
