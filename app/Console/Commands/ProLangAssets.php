<?php

namespace App\Console\Commands;

use App\Models\LangAuthor;
use App\Models\ProLang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProLangAssets extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pro-lang-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prolang and author picture';

    private function getWikiImageSrc(string $authorLink) {
        $title = urldecode(basename(parse_url($authorLink, PHP_URL_PATH)));
        $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/'.rawurlencode($title);

        $res = Http::withHeaders(array(
            'User-Agent' => 'MyApp/1.0 (contact@example.com)',
            'Accept' => 'application/json',
        ))->get($url);

        if (! $res->ok()) {
            return null;
        }

        return $res->json('thumbnail.source');
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        Log::info('action=prolang_assets_command, status=started');

        LangAuthor::whereNotNull('link')->each(
            function ($author) {
                $img = $this->getWikiImageSrc($author->link);

                $author->update(
                    array('pictureUrl' => $img)
                );
            }
        );

        ProLang::whereNotNull('link')->each(
            function ($lang) {
                $img = $this->getWikiImageSrc($lang->link);

                $lang->update(
                    array('pictureUrl' => $img)
                );
            }
        );

        Log::info('action=prolang_assets_command, status=finished');
    }
}
