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

    public function groupExistForClient($groupId, $clientId) {
        return $this->getConn()->table('groups')
            ->where('group_id', '=', $groupId)
            ->where('clients_id', '=', $clientId)
            ->exists();
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
    
    public function flushGroup($clientId, $groupId) {
        if(empty($clientId)) {
            throw new BadRequestException("Client id is missing from the request", BadRequestException::MISSING_CLIENT_ID);
        }

        $group = (array)$this->getConn()->table('groups')
            ->where('clients_id', '=', $clientId)
            ->where('group_id', '=', $groupId)
            ->first();

        if(empty($group)) {
            throw new NonExistingException("Group non-exiting for this client.", NonExistingException::GROUP_NOT_EXIST);
        }

        \DB::delete("delete persons from persons join persons_groups on (persons.person_id = persons_groups.person_id) where persons_groups.group_id = ".$groupId);
    }
    
    public function getGroupAndPersonsById($clientId, $groupId) {

        if(empty($clientId)) {
            throw new BadRequestException("Client id is missing from the request", BadRequestException::MISSING_CLIENT_ID);
        }

        $group = (array)$this->getConn()->table('groups')
            ->where('clients_id', '=', $clientId)
            ->where('group_id', '=', $groupId)
            ->first();

        if(empty($group)) {
            throw new NonExistingException("Group non-exiting for this client.", NonExistingException::GROUP_NOT_EXIST);
        }

        $group_id = $group['group_id'];
        $persons = $this->getPersonsByGroupId($group_id);
        $group['persons'] = $persons;
        return $group;
    }

    public function getPersonsByGroupId($groupId) {
        $persons = (array)$this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->leftJoin('faces as f', 'f.person_id', '=', 'p.person_id')
            ->leftJoin('images as i', 'i.image_id', '=', 'f.image_id')
            ->join('groups as g', 'pg.group_id', '=', 'g.group_id')
            ->where('g.group_id', '=', $groupId)
            ->groupBy('p.person_id')
            ->get(['p.person_id', 'p.name', 'i.img_path']);
        
        return $persons;
    }
}