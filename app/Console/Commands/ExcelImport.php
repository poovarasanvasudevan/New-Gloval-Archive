<?php

namespace App\Console\Commands;

use App\ArtefactType;
use Excel;
use Illuminate\Console\Command;

class ExcelImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:excel {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');

        $arr = explode("_", $id);
        $fileName = $arr[0];
        $artefactTypeId = $arr[1];

        $path = storage_path('config/excel/');
        $cols = array('ArtefactName');

        $attr = ArtefactType::find($artefactTypeId)->attributes;
        foreach ($attr as $item) {
            array_push($cols, $item->attribute_title);
        }

        Excel::selectSheetsByIndex(0)
            ->load($path . $id . '.csv', function ($reader) use ($cols) {
            $result = $reader->select($cols)->get()->toArray();

            foreach ($result as $item) {

            }

        });

    }
}
