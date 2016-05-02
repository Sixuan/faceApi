<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 9:58 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaceController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id) {
        $file = $request->file('image');
        $mimeType = $file->getMimeType();
        $extension = substr($mimeType, -3);
        $name = time().'.'.$extension;
        $path = "/tmp/tmpImages/";
        $file->move($path, $name);
        $content = [
            'name' => $name,
            'path' => $path
        ];
        return self::buildResponse($content, self::SUCCESS_CODE);
    }
    
    public function delete(Request $request, $id) {
        
    }

    public function detect(Request $request) {

    }
}