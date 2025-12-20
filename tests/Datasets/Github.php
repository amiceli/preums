<?php

function getGithubSample(string $filename) {
    return json_decode(
        file_get_contents(PROJECT_ROOT.'/tests/samples/'.$filename),
        true,
    );
}

// samples from github api

$githubUser = getGithubSample('user.json');
$githubOrg = getGithubSample('org.json');
$githubUserRepositories = getGithubSample('user-repos.json');
$githubContributors = getGithubSample('contirbutors.json');
$githubOrgMembers = getGithubSample('org-members.json');
$githubLanguages = getGithubSample('languages.json');
$githubReleases = getGithubSample('releases.json');

// datasets

dataset('github-user', array(
    array($githubUser))
);

dataset('github-languages', array(
    array($githubLanguages))
);

dataset('github-releases', array(
    array($githubReleases))
);

dataset('github-repos', array(
    array($githubUserRepositories))
);

dataset('github-user-history', array(
    array(
        'user' => $githubUser,
        'repos' => $githubUserRepositories,
    ),
));

dataset('github-contributors', array(
    array($githubContributors),
));

dataset('github-org-history', array(
    array(
        'org' => $githubOrg,
        'repos' => $githubUserRepositories,
        'members' => $githubOrgMembers,
    ),
));
