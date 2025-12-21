<?php

namespace App\Console\Commands;

use App\Models\LangStats;
use Illuminate\Console\Command;

class UpdateLangStats extends Command {
    private readonly string $csvUrl;

    public function __construct() {
        parent::__construct();

        $this->csvUrl = 'https://raw.githubusercontent.com/github/innovationgraph/refs/heads/main/data/languages.csv';
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-lang-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update languages stats';

    /**
     * Execute the console command.
     */
    public function handle() {
        LangStats::truncate();

        $fileHandle = fopen($this->csvUrl, 'r');
        $csvLines = array();

        print_r('ici');

        while (($csvRow = fgets($fileHandle)) !== false) {
            [
                $pushers,
                $language,
                $type,
                $iso,
                $year,
                $querter,
            ] = explode(',', $csvRow);
            $key = $year.'_'.$iso.'_'.$language.'_'.$type;

            if (isset($csvLines[$key])) {
                $csvLines[$key]['pushers'] += intval($pushers);
            } else {
                $csvLines[$key] = array(
                    'pushers' => intval($pushers),
                    'year' => intval($year),
                    'name' => $language,
                    'type' => $type,
                    'iso_code' => $iso,
                );
            }
        }

        fclose($fileHandle);
        array_shift($csvLines);

        $csvLines = array_values($csvLines);
        $items = array_chunk($csvLines, 1000);

        foreach ($items as $scope) {
            LangStats::insert($scope);
        }
    }
}
