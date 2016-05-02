<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:46 PM
 */

namespace App\Http\Models;


class FaceModelSql extends BaseModelSql
{
    /**
     * @var FaceModelSql
     */
    private static $faceSqlSingleton;

    /**
     * @return FaceModelSql
     */
    public static function getInstance() {
        if(self::$faceSqlSingleton == null) {
            self::$faceSqlSingleton = new FaceModelSql();
        }
        return self::$faceSqlSingleton;
    }

}