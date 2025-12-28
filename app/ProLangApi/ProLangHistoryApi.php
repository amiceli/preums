<?php

namespace App\ProLangApi;

class ProLangApi {
    private readonly string $baseUrl;

    public function __construct() {
        $this->baseUrl = 'https://api.prolanghistory.com';
    }

    public function getLanguages(int $page = 1) {
        // TODO, get langs from https://api.prolanghistory.com/languages
    }
}
