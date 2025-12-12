<?php

namespace App\Models\Github;

class GithubCommit {
    public readonly string $dateStr;

    public int $treeCount;

    public function __construct(
        public string $author,
        public string | null $authorImg,
        public string | null $authorUrl,
        public string $message,
        public string $sha,
        public string $url,
        public \DateTime $date,
        public string $treeUrl,
    ) {
        $this->dateStr = $this->date->format('c');
    }
}
