<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadMultiRequest;
use Illuminate\Http\Request;

use App\Uploads;
use App\Http\Requests\UploadRequest;

use File;
use Session;

class UploadController extends Controller
{
    //

    public function list()
    {
        $uploads = Uploads::all();

        if (!$uploads->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => "we cant found uploaded files",
            ]);
        } else {

            foreach ($uploads as $upload) {
                $data[] = [
                    'id' => $upload->id,
                    'name' => $upload->name,
                    'description' => $upload->description,
                    'file' => asset($upload->file),
                ];
            }
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }


    public function create(UploadRequest $request)
    {

        $request->all();
        #se obtiene el id del Auth Token con el objetivo de guardar el archivo en su propia carpeta.
        $auth_id = $request->get('auth.id');

        $file_name = time() . '_' . $request->file->getClientOriginalName();
        #se hace upload del archivo en una carpeta
        $file_path = $request->file('file')->storeAs('uploads/' . $auth_id, $file_name, 'public_access');
        $file_mime = $request->file->getMimeType();
        $new = new Uploads();
        $new->name = $file_name;
        $new->file = $file_path;
        $new->mime = $file_mime;
        $new->save();

        return response()->json([
            'success' => true,
            'message' => "Your file has been uploaded successfully",
            'data' => ['id' => $new->id]
        ]);

    }

    public function show($id)
    {
        $upload = Uploads::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => "we cant found uploaded file with your id",
            ]);
        } else {
            $data = [
                'id' => $upload->id,
                'name' => $upload->name,
                'description' => $upload->description,
                'file' => asset($upload->file),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function delete($id, Request $request)
    {
        $upload = Uploads::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => "we cant found uploaded file with your id",
            ]);
        } else {
            $r = $request->all();
            $erase_msg = null;
            if (isset($request["erase"]) && $request["erase"]) {
                if (File::exists(public_path($upload->file))) {
                    File::delete(public_path($upload->file));
                    $erase_msg = " and erase ";
                }
            }

            return response()->json([
                'success' => true,
                'message' => "We are delete " . $erase_msg . " your upload register",
            ]);
        }

    }

    public function createMultiple(UploadMultiRequest $request)
    {

        $uploads=$request->all();
        $auth_id = $request->get('auth.id');
        #var_dump($uploads);
        $ids=[];
        foreach ($uploads["file"] as $upload) {

            $file_name = time() . '_' . $upload->getClientOriginalName();
            #dd($file_name);
            #se hace upload del archivo en una carpeta
            $file_path = $upload->storeAs('uploads/' . $auth_id, $file_name, 'public_access');
            $file_mime = $upload->getMimeType();
            $new = new Uploads();
            $new->name = $file_name;
            $new->file = $file_path;
            $new->mime = $file_mime;
            $new->save();
            $ids[]=$new->id;

        }
        return response()->json([
            'success' => true,
            'message' => "Your files has been uploaded successfully",
            'data' => ['ids' => $ids]
        ]);

    }
}
