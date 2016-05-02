<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:45 PM
 */

namespace App\Http\Models;


class PersonModelSql extends BaseModelSql
{
    /**
     * @var PersonModelSql
     */
    private static $personSqlSingleton;

    /**
     * @return PersonModelSql
     */
    public static function getInstance() {
        if(self::$personSqlSingleton == null) {
            self::$personSqlSingleton = new PersonModelSql();
        }
        return self::$personSqlSingleton;
    }

}