<?php

namespace App\Http\Controllers\Traits;

use Storage;

trait FileServiceTrait
{
    /**
     * 儲存檔案
     * @author Wayne <wayne@howdesign.com.tw>
     * @param $file: 檔案物件
     * @param $directory: 檔案存放目錄
     * @param $disk: filesystems 之預設設定
     * @return string
     */
    public function storeFile($file, $directory, $disk='public')
    {

        if ($file === null || $file === '') {
            return null;
        }

        return $file->store($directory, $disk);
    }

    /**
     * 儲存指定檔名之檔案
     * @author Wayne <wayne@howdesign.com.tw>
     * @param $file: 檔案物件
     * @param $directory: 檔案存放目錄
     * @param $fileName: 指定檔名
     * @param $disk: filesystems 之預設設定
     * @return string
     */
    public function storeAsFile($file, $directory, $fileName, $disk='public')
    {

        if ($file === null || $file === '') {
            return null;
        }

        return $file->storeAs($directory, $fileName, $disk);
    }

    /**
     * 刪除檔案
     * @author Wayne <wayne@howdesign.com.tw>
     * @param $filePath: 檔案存在位置
     * @return void
     */
    public function deleteFile($filePath)
    {
        if ($filePath == null || $filePath == '') {
            return;
        }

        $filePath = str_replace(config('app.url') . '/storage/', '', $filePath);
        
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
