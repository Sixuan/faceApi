<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:45 PM
 */

namespace App\Http\Models;


use App\Exceptions\BadRequestException;
use App\Exceptions\NonExistingException;

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

    public function createGroup(array $input, $clientId) {

        if(empty($clientId)) {
            throw new BadRequestException("Client id is missing from the request", "missing_client_id");
        }

        $insertArray = array(
            'name' => $input['name'],
            'tag' => $input['tag'],
            'clients_id' => $clientId
        );

        $id = $this->getConn()->table('groups')
            ->insertGetId($insertArray);

        return $this->getConn()->table('groups')
            ->where('group_id', '=', $id)
            ->first();
    }
    
    public function getGroupsByClientId($clientId) {
        if(empty($clientId)) {
            throw new BadRequestException("Client id is missing from the request", "missing_client_id");
        }

        return $this->getConn()->table('groups')
            ->where('clients_id', '=', $clientId)
            ->get();
    }
    
    public function getGroupById($clientId, $groupId) {
        if(empty($clientId)) {
            throw new BadRequestException("Client id is missing from the request", BadRequestException::MISSING_CLIENT_ID);
        }

        $group = $this->getConn()->table('groups')
            ->where('clients_id', '=', $clientId)
            ->where('group_id', '=', $groupId)
            ->first();

        if(empty($group)) {
            throw new NonExistingException("Group non-exiting for this client.", NonExistingException::GROUP_NOT_EXIST);
        }

        return $group;
    }
}