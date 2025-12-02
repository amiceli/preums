<?php

namespace App\Models;

use DateTime;

class GithubRepository
{
    public readonly string $createdAtStr;

    public readonly string $updatedAtStr;

    /**
     * @param string[] $topics
     */
    public function __construct(
        public string $id,
        public int $stars,
        public string $name,
        public string $fullName,
        public string|null $description,
        public string $url,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public string|null $language,
        public array $topics,
        public int $watchers,
        public int $forks,
        public GithubRepositoryOwner $owner,
    ) {
        $this->createdAtStr = $this->createdAt->format('c');
        $this->updatedAtStr = $this->updatedAt->format('c');
    }
}
