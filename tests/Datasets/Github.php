<?php

$githubUser = json_decode(
    file_get_contents(PROJECT_ROOT.'/tests/samples/user.json'), true
);

dataset('github-user', array(
    array($githubUser),
));
