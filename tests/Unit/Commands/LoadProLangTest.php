<?php

use App\Console\Commands\LoadProLang;
use App\Models\ProLang;
use App\Models\YearGroup;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

describe('LoadProLang command', function () {
    function mockProlangApi(array $prolang) {
        Http::fake(array(
            'https://api.prolanghistory.com/languages*' => Http::response(
                $prolang,
                200,
            ),
        ));

        (new LoadProLang())->handle();
    }

    it('should call prolang api', function (array $prolang) {
        mockProlangApi($prolang);

        Http::assertSent(fn (Request $r) => (
            $r->method() === 'GET' &&
            str_contains(
                $r->url(), 'https://api.prolanghistory.com/languages'
            )
        ));
    })->with('prolang');

    it('should save year groups', function (array $prolang) {
        mockProlangApi($prolang);

        expect(
            YearGroup::all()->count()
        )->toBe(8);
        expect(
            YearGroup::where('apiId', '612103bd20aaf1e4d5e85519')->count()
        )->toBe(1);
        expect(
            YearGroup::where('apiId', '612103bd20aaf1e4d5e85519')->first()->name
        )->toBe('1990s');
    })->with('prolang');

    it('should save lang author', function (array $prolang) {
        mockProlangApi($prolang);

        $this->assertContains(
            'Brendan EichNetscape',
            ProLang::where('name', 'JavaScript')->first()->authorNames(),
        );
        expect(
            ProLang::where('name', 'INTERCAL')->first()->authors()->count()
        )->toBe(2);
        expect(
            ProLang::where('name', 'IBM BASICA')->first()->authors()->count()
        )->toBe(0);
    })->with('prolang');

    it('should save langs', function (array $prolang) {
        mockProlangApi($prolang);

        expect(
            ProLang::all()->count()
        )->toBe(29);
        expect(
            ProLang::where('name', 'Icon')->count()
        )->toBe(1);
        expect(
            ProLang::where('name', 'Icon')->first()->yearGroup->name
        )->toBe('1970s');
    })->with('prolang');

    it('should save predecessors', function (array $prolang) {
        mockProlangApi($prolang);

        $joy = ProLang::where('name', 'Joy')->first();
        $joyPrent = ProLang::where('name', 'Join Java')->first();

        expect(
            ProLang::where('name', 'IPL I')->first()->isOrphan()
        )->toBe(true);
        expect(
            $joy->parents->first()->name
        )->toBe('Join Java');
        expect(
            $joyPrent->children->first()->name
        )->toBe('Joy');
    })->with('prolang');
});
