<?php

namespace App\Console\Commands;

use App\Models\LangStats;
use Illuminate\Console\Command;

class UpdateLangStats extends Command {
    private readonly string $csvUrl;

    private readonly int $statsYear;

    public function __construct() {
        parent::__construct();

        $this->csvUrl = 'https://raw.githubusercontent.com/github/innovationgraph/refs/heads/main/data/languages.csv';
        $this->statsYear = env('GITHUB_STATS_YEAR', date('Y'));
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

        while (($csvRow = fgets($fileHandle)) !== false) {
            [
                $pushers,
                $language,
                $type,
                $iso,
                $year,
                $querter,
            ] = explode(',', $csvRow);

            if ($year == $this->statsYear) {
                $key = $language.'_'.$type;

                if (isset($csvLines[$key])) {
                    $csvLines[$key]['pushers'] += intval($pushers);
                } else {
                    $csvLines[$key] = array(
                        'pushers' => intval($pushers),
                        'name' => $language,
                        'type' => $type,
                    );
                }
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
