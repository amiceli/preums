<?php

namespace App\Models;

class GithubRateLimit
{
    public string $nextResetStr;

    public function __construct(
        public bool $remaining,
        public \DateTime $nextReset,
    ) {
        $this->nextResetStr = $nextReset->format("c");
    }
}
