<?php

namespace App\Models;

class GithubCommit {
    public readonly string $dateStr;

    public function __construct(
        public string $author,
        public string $message,
        public string $sha,
        public string $url,
        public \DateTime $date,
    ) {
        $this->dateStr = $this->date->format('c');
    }
}
