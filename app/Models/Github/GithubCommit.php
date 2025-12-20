<?php

namespace App\Models\Github;

class GithubCommit {
    public readonly string $dateStr;

    public function __construct(
        public string $author,
        public string $authorLogin,
        public ?string $authorImg,
        public ?string $authorUrl,
        public string $message,
        public string $sha,
        public string $url,
        public \DateTime $date,
        public string $treeUrl,
    ) {
        $this->dateStr = $this->date->format('c');
    }
}
