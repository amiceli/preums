<?php

namespace App\Console\Commands;

use App\Models\LangAuthor;
use App\Models\ProLang;
use App\Models\YearGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadProLang extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-pro-lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from prolang.com api';

    private function saveProLang(array $item) {
        try {
            [
                'yearGroup' => $yearGroup,
                'authors' => $authors,
                'predecessors' => $predecessors,
            ] = $item;

            $langAuthors = array_map(function ($author) {
                return LangAuthor::firstOrCreate(
                    array(
                        'name' => $author['name'],
                    ),
                    array(
                        'name' => $author['name'],
                        'pictureUrl' => $author['picture'] ?? null,
                        'country' => $author['country'] ?? null,
                        'link' => $author['link'] ?? null,
                    )
                );
            }, $authors);

            $newYearGroup = YearGroup::firstOrCreate(
                array(
                    'name' => $yearGroup['name'],
                ),
                array(
                    'apiId' => $yearGroup['id'] ?? null,
                    'position' => $yearGroup['position'] ?? null,
                    'name' => $yearGroup['name'] ?? null,
                ));

            $updatedLang = ProLang::updateOrCreate(
                array(
                    'name' => $item['name'],
                ),
                array(
                    'company' => $item['company'] ?? null,
                    'apiId' => $item['id'],
                    'name' => $item['name'],
                    'link' => $item['link'],
                    'years' => json_encode($item['years']),
                    'yearGroupId' => $newYearGroup->id,
                )
            );

            $updatedLang->authors()->attach(
                array_map(fn ($i) => ($i->id), $langAuthors)
            );

            $parentIds = ProLang::whereIn(
                'apiId',
                array_map(fn ($i) => $i['id'], $predecessors)
            )->pluck('id');

            $updatedLang->parents()->attach($parentIds);

            $updatedLang->save();
        } catch (\Exception $e) {
            Log::error(
                'action=save_lang, status=failed, app_id'.$item['id'].' error='.$e->__toString()
            );
        }
    }

    private function callApi(int $page = 1) {
        $baseUrl = 'https://api.prolanghistory.com';
        $url = $baseUrl.'/languages';
        $response = Http::get($url, array(
            'page' => $page,
        ));

        return $response->json()['data'];
    }

    private function getLanguages(int $page) {
        [
            'currentPage' => $currentPage,
            'items' => $items,
            'totalPages' => $totalPages,
        ] = $this->callApi($page);

        foreach ($items as $item) {
            $this->saveProLang($item);
        }

        Log::info("action=save_pro_lang, page=$currentPage, count=".count($item)."total_pages=$totalPages");

        if ($currentPage < $totalPages) {
            $this->getLanguages($currentPage + 1);
        }
    }

    /**
     * Add some languages like Phel missing in prolang api
     */
    private function addProLanguage() {
        $langToAdd = array(
            // Data : https://phel-lang.org/ and preums itselft
            array(
                'authors' => array(),
                'company' => null,
                'id' => uniqid().'_Phel',
                'link' => '',
                'name' => 'Phel',
                'predecessors' => array(),
                'yearGroup' => array(
                    'name' => '2020s',
                ),
                'years' => array(2020),
            ),
            // Data : https://janet-lang.org/ and preums itselft
            array(
                'authors' => array(),
                'company' => null,
                'id' => uniqid().'_Janet',
                'link' => '',
                'name' => 'Janet',
                'predecessors' => array(),
                'yearGroup' => array(
                    'name' => '2010s',
                ),
                'years' => array(2017),
            ),
        );

        foreach ($langToAdd as $item) {
            $this->saveProLang($item);
            Log::info('action=add_missing_lang, status=succes, lang='.$item['name']);
        }
    }

    /**
     * Add links between languages
     *  - Some data are missing from prolang api
     *  - Ex : Php is Hack's predecessors
     */
    private function updateLangFamily() {
        $langFamilies = array(
            // Data: https://github.com/janet-lang/janet
            'Janet' => array('C'),
            // Data : https://phel-lang.org/
            'Phel' => array('PHP', 'Clojure', 'Janet', 'Lisp'),
            // https://en.wikipedia.org/wiki/PHP
            'PHP' => array('C', 'C++', 'Perl'),
            // Data : https://en.wikipedia.org/wiki/Hack_(programming_language)
            'Hack' => array('PHP', 'OCaml', 'Java', 'Scala', 'Haskell', 'C#'),
            // Data : https://crystal-lang.org/ and https://en.wikipedia.org/wiki/Crystal_(programming_language)
            'Crystal' => array('Ruby', 'Go'),
            // Data : https://en.wikipedia.org/wiki/Ruby_(programming_language)
            'Ruby' => array('Ada', 'Eiffel', 'Lua', 'Dylan'),
            // Data : https://en.wikipedia.org/wiki/Dylan_(programming_language)
            'Dylan' => array('ALGOL 60', 'EuLisp'),
            // Data : https://en.wikipedia.org/wiki/Lasso_(programming_language)
            'Lasso' => array('Dylan', 'Scala'),
            // Data : https://en.wikipedia.org/wiki/JavaScript
            'ActionScript' => array('JavaScript'),
            'CoffeeScript' => array('JavaScript'),
            'JavaScript' => array('Java', 'Self', 'AWK'),
        );

        foreach ($langFamilies as $name => $value) {
            $lang = ProLang::where('name', $name)->first();
            if ($lang) {
                $parents = ProLang::whereIn(
                    'name', $value
                )->pluck('id');

                $lang->parents()->sync($parents);
                $lang->save();

                Log::info("action=save_lang, status=success, lang=$name");
            } else {
                Log::info("action=save_lang, status=failed, reason=lang $name not found");
            }
        }
    }

    public function handle() {
        Log::info('action=load_prolang_command, status=started');

        $this->getLanguages(1);
        $this->addProLanguage();
        $this->updateLangFamily();

        Log::info('action=load_prolang_command, status=finished');
    }
}
