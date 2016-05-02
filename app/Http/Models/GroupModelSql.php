<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:45 PM
 */

namespace App\Http\Models;


class GroupModelSql extends BaseModelSql
{
    /**
     * @var GroupModelSql
     */
    private static $groupSqlSingleton;

    /**
     * @return GroupModelSql
     */
    public static function getInstance() {
        if(self::$groupSqlSingleton == null) {
            self::$groupSqlSingleton = new GroupModelSql();
        }
        return self::$groupSqlSingleton;
    }

}