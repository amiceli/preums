<?php

use App\Console\Commands\LoadProLang;
use Illuminate\Support\Facades\Http;

function getProLangSample(string $filename) {
    return json_decode(
        file_get_contents(PROJECT_ROOT.'/tests/samples/'.$filename),
        true,
    );
}

function mockProlangApi(array $prolang) {
    Http::fake(array(
        'https://api.prolanghistory.com/languages*' => Http::response(
            $prolang,
            200,
        ),
    ));

    (new LoadProLang())->handle();
}

$proLang = getProLangSample('prolang-api.json');

dataset('prolang', array(
    array($proLang))
);
