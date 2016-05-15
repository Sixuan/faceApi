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

    public function createClient(array $input) {
        $appKey = uniqid("");
        $appSecret = uniqid("");

        $id = $this->getConn()->table('clients')
            ->insertGetId(
                array(
                    'company' => $input['company'],
                    'api_key' => $appKey,
                    'api_secret' => $appSecret
                )
            );

        return $this->getClient($id);
    }
    
    public function getClient($id) {
        return (array)$this->getConn()->table('clients')
            ->where('clients_id', '=', $id)
            ->first();
    }

    public function getClients() {
        return (array)$this->getConn()->table('clients')
            ->where('active', '=', 'Y')
            ->get();
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