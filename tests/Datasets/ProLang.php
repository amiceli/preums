<?php

function getProLangSample(string $filename) {
    return json_decode(
        file_get_contents(PROJECT_ROOT.'/tests/samples/'.$filename),
        true,
    );
}

$proLang = getGithubSample('prolang-api.json');

dataset('prolang', array(
    array($proLang))
);
