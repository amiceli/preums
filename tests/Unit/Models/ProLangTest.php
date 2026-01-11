<?php

use App\Models\ProLang;
use App\Models\YearGroup;

it('should be able to get all family paths', function () {
    $group = YearGroup::create(array(
        'apiId' => '',
        'name' => '1990',
        'position' => 1,
    ));

    $parents = array_map(function (string $name) use ($group) {
        $l = ProLang::create(array(
            'name' => $name,
            'apiId' => '',
            'link' => '',
            'company' => null,
            'years' => '["1990"]',
            'yearGroupId' => $group->id,
        ));

        return $l;
    }, array('LiveScript', 'Perl'));

    $lang = ProLang::create(array(
        'name' => 'JvaScript',
        'apiId' => '',
        'link' => '',
        'company' => null,
        'years' => '["1990"]',
        'yearGroupId' => $group->id,
    ));

    $anotherLang = ProLang::create(array(
        'name' => 'Awesome',
        'apiId' => '',
        'link' => '',
        'company' => null,
        'years' => '["1990"]',
        'yearGroupId' => $group->id,
    ));

    $lang->parents()->sync($parents);
    $anotherLang->children()->sync(array(
        $parents[0],
    ));

    expect(count($lang->paths))->toBe(2);
    expect($lang->paths)->toContain('Perl -> JvaScript');
    expect($lang->paths)->toContain('Awesome -> LiveScript -> JvaScript');
});

it('should be able to detect if it is an orphan lang', function () {
    $group = YearGroup::create(array(
        'apiId' => '',
        'name' => '1990',
        'position' => 1,
    ));

    $lang = ProLang::create(array(
        'name' => 'JvaScript',
        'apiId' => '',
        'link' => '',
        'company' => null,
        'years' => '["1990"]',
        'yearGroupId' => $group->id,
    ));

    $anotherLang = ProLang::create(array(
        'name' => 'Awesome',
        'apiId' => '',
        'link' => '',
        'company' => null,
        'years' => '["1990"]',
        'yearGroupId' => $group->id,
    ));

    expect($lang->isOrphan())->toBe(true);

    $lang->parents()->sync($anotherLang);

    expect($lang->isOrphan())->toBe(false);
});
