<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:45 PM
 */

namespace App\Http\Models;


use App\Exceptions\BadRequestException;

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

    public function getPerson($personId, $clientId) {
        $res = (array)$this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->where('p.person_id', '=', $personId)
            ->where('g.clients_id', '=', $clientId)
            ->get(['p.person_id', 'p.name', 'g.group_id']);

        $groups = [];
        foreach ($res as $r) {
            $groups[] = $r->group_id;
        }

        return array(
            'person_id' => $r->person_id,
            'name' => $r->name,
            'groups' => $groups
        );
    }

    public function createPerson(array $input, $clientId) {

        if(!isset($input['group_ids'])) {
            throw new BadRequestException("group_ids are missing.");
        }

        $insertPersonArray = array(
            'name' => $input['name']
        );

        $this->getConn()->beginTransaction();
        $personId = $this->getConn()->table('persons')
            ->insertGetId($insertPersonArray);

            $group_ids = $input['group_ids'];

        $exceptions = [];
        $insertPersonGroupArray = [];
        
        foreach ($group_ids as $group_id) {
            $exist = $this->getConn()->table('groups')
                ->where('group_id', '=', $group_id)
                ->where('clients_id', '=', $clientId)
                ->exists();

            if($exist) {
                $insertArray = array(
                    'group_id' => $group_id,
                    'person_id' => $personId
                );
                $this->getConn()->table('persons_groups')
                    ->insert($insertArray);

                $insertPersonGroupArray[] = $group_id;
            
            }else{
                $exceptions[] = "Invalid action on group_id: ". $group_id.", group not belongs to client_id: ".$clientId;
            }
        }

        $this->getConn()->commit();
        return array(
            'person_id' => $personId,
            'name' => $input['name'],
            'groups' => $insertPersonGroupArray,
            'exceptions' => $exceptions
        );
    }
}