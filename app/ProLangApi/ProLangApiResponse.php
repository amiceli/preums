<?php

namespace App\Models\ProLang;

class ProLangAuthor {
    public function __construct(
        public readonly ?string $birthDate,
        public readonly ?string $country,
        public readonly string $id,
        public readonly ?string $link,
        public readonly string $name,
        public readonly ?string $picture,
    ) {}
}

class ProLangLanguage {
    public function __construct(
        /**
         * @var ProLangAuthor[]
         */
        public readonly array $authors,
        public readonly ?string $company,
        public readonly string $id,
        public readonly ?string $link,
        public readonly string $name,
        /**
         * @var ProLangLanguage[]
         */
        public readonly array $predecessors,
        public readonly bool $yearConfirmed,
        /**
         * @var $year array<int>
         */
        public readonly array $years,
        public readonly array $yearGroup,
    ) {}
}
