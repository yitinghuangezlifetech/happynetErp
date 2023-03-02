<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class ImageApi extends Controller {

    use fileService;

    public function upload(Request $request) {
        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();
      
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
      
            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
      
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
      
            //Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);
            $request->file('upload')->storeAs('public/uploads/thumbnail', $filenametostore);
     
            //Resize image here
            $thumbnailpath = public_path('storage/uploads/thumbnail/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(500, 150, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
     
            echo json_encode([
                'default' => asset('storage/uploads/'.$filenametostore),
                '500' => asset('storage/uploads/'.$filenametostore)
            ]);
        }
    }

    public function remove(Request $request) {
        $log = app($request->model)->find($request->id);
        if ($log) {
            $this->deleteFile($log->{$request->field});

            $log->{$request->field} = NULL;
            $log->save();
        }
        app($request->model)->where('id', $request->id)->update([
            $request->field => null
        ]);

        return response()->json([
            'status' => true,
            'message' => '刪除成功',
            'data' => null
        ], 200);
    }

    public function deleteImg(Request $request) {
        $log = app($request->model)->find($request->id);

        if ($log) {
            $this->deleteFile($log->{$request->field});

            $log->delete();
        }

        return response()->json([
            'status' => true,
            'message' => '刪除成功',
            'data' => null
        ], 200);
    }
}
