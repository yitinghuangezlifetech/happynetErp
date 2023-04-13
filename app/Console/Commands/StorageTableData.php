<?php

namespace App\Console\Commands;

use DB;
use Storage;
use Illuminate\Console\Command;

class StorageTableData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:table {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將目前資料表中的資料存成json格式並寫入檔案';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sql = '';
        $tableData = DB::table($this->argument('tableName'))->get();

        foreach ($tableData as $row) {
            $row = (array) $row;
            $sql .= "INSERT INTO ".$this->argument('tableName')." (";
            $sql .= implode(', ', array_keys($row)) . ') VALUES (';
            $sql .= implode(', ', array_map(function ($value) {
                return '"' . addslashes($value) . '"';
            }, array_values($row))) . ");\n";
        }

        if (!Storage::disk('public')->exists('tableData/'.$this->argument('tableName').'.sql'))
        {
            Storage::disk('public')->put('tableData/'.$this->argument('tableName').'.sql', $sql);
        }
        else
        {
            Storage::disk('public')->put('tableData/'.$this->argument('tableName').'.sql', $sql);
        }
    }
}
