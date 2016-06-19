<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 6/19/16
 * Time: 2:45 PM
 */

namespace App\Http\Models;


class RecognitionModelSql extends BaseModelSql
{
    /**
     * @var RecognitionModelSql
     */
    private static $recognitionSqlSingleton;

    /**
     * @return RecognitionModelSql
     */
    public static function getInstance() {
        if(self::$recognitionSqlSingleton == null) {
            self::$recognitionSqlSingleton = new RecognitionModelSql();
        }
        return self::$recognitionSqlSingleton;
    }

    public function getSupplementaryInfoForFaces($faceId1, $faceId2) {
        return (array)$this->getConn()->table('faces as f')
            ->join('images as i', 'f.image_id', '=', 'i.image_id')
            ->whereIn('f.face_id', [$faceId1, $faceId2])
            ->get(['f.face_id', 'i.img_path']);
        
    }

}