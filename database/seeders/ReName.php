<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReName extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 設定要讀取的資料夾路徑
        $dir_path = "C:/xampp/htdocs/erp/database/migrations";

        // 讀取資料夾內的所有檔案
        $files = scandir($dir_path);

        // 迭代每一個檔案
        foreach ($files as $k=>$file) {
        // 排除 . 和 .. 這兩個目錄
            if ($file == '.' || $file == '..') {
                continue;
            }

            // 取得檔案的完整路徑
            $file_path = $dir_path . '/' . $file;

            $substr = mb_substr($file, 0, 16, "UTF-8");

            $new_name = date("Y_m_d")."_".sprintf("%06d",$k);

            $new_file_name= str_replace($substr , $new_name, $file); 
            // 定義新的檔案名稱
            // $new_file_name = "new_" . $file;

            // 變更檔案名稱
            if (rename($file_path, $dir_path . '/' . $new_file_name)) {
                echo "已將檔案 $file_path 變更為 $new_file_name";
            } else {
                echo "無法將檔案 $file_path 變更為 $new_file_name";
            }
        }

    }
}
