<?php

$githubUser = json_decode(
    file_get_contents(PROJECT_ROOT.'/tests/samples/user.json'),
    true,
);

$githubUserRepositories = json_decode(
    file_get_contents(PROJECT_ROOT.'/tests/samples/user-repos.json'),
    true,
);

$githubContributors = json_decode(
    file_get_contents(PROJECT_ROOT.'/tests/samples/contirbutors.json'),
    true,
);

dataset('github-user', array(array($githubUser)));

dataset('github-repos', array(array($githubUserRepositories)));

dataset('github-user-history', array(
    array(
        'user' => $githubUser,
        'repos' => $githubUserRepositories,
    ),
));

dataset('github-contributors', array(array($githubContributors)));
