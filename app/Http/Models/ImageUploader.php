<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 10:09 PM
 */

namespace App\Http\Models;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public static function uploadAndGetPath(UploadedFile $file){
        $mimeType = $file->getMimeType();
        $extension = substr($mimeType, -3);
        $name = time().'.'.$extension;
        $path = "/tmp/tmpImages/";
        $file->move($path, $name);
        $photo = $path.$name;
        return $photo;
    }
}