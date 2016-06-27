<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 10:09 PM
 */

namespace App\Http\Models;


use App\Exceptions\BadRequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    /**
     * @param UploadedFile $file
     * @return string
     * @throws BadRequestException
     */
    public static function uploadAndGetPath(UploadedFile $file, $sufix = null){
        $mimeType = $file->getMimeType();
//        print $mimeType;
        if (strpos($mimeType, 'image') === false) {
            throw new BadRequestException("invalid image format");
        }
        $extension = substr($mimeType, 6);
        if($sufix) {
            $name = time().'_'.$sufix.'.'.$extension;
        } else {
            $name = time().'.'.$extension;
        }
        $path = "/tmp/tmpImages/";
        $file->move($path, $name);
        $photo = $path.$name;
        return $photo;
    }
}