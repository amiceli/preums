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
                    'apiId' => $yearGroup['id'],
                    'position' => $yearGroup['position'],
                    'name' => $yearGroup['name'],
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
     * Execute the console command.
     */
    public function handle() {
        $this->getLanguages(1);
    }
}
