<?php

namespace App\Models;

class GithubRelease {
    public readonly string $dateStr;

    public function __construct(
        public string $author,
        public string $name,
        public \DateTime $date,
        public string $body,
        public array $reactions,
        public string $url,
    ) {
        $this->dateStr = $this->date->format('c');
    }
}
