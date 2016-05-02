<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 11:01 PM
 */

namespace App\Http\Models;


use App\Exceptions\ClientNotExistingException;

class ClientModelSql extends BaseModelSql
{
    /**
     * @var ClientModelSql
     */
    private static $clientSqlSingleton;

    /**
     * @return ClientModelSql
     */
    public static function getInstance() {
        if(self::$clientSqlSingleton == null) {
            self::$clientSqlSingleton = new ClientModelSql();
        }
        return self::$clientSqlSingleton;
    }


    /**
     * @param $appKey
     * @param $appSecret
     * @return int
     * @throws ClientNotExistingException
     */
    public function getClientId($appKey, $appSecret) {
        $id = $this->getConn()->table('clients')
            ->where('api_key', '=', $appKey)
            ->where('api_secret', '=', $appSecret)
            ->pluck('clients_id');
        
        if(sizeof($id) > 0){
            return $id[0];
        }else{
            throw new ClientNotExistingException();
        }
    }
}