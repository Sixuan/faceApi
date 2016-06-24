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

    public function getImagePath($personId) {

    }

    public function personsExistForClient($personId, $clientId) {
        return $this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->where('p.person_id', '=', $personId)
            ->where('g.clients_id', '=', $clientId)
            ->exists();
    }

    public function deletePerson($personId, $clientId) {

        if($this->personsExistForClient($personId, $clientId)) {
            $this->getConn()->table('persons')
                ->where('person_id', '=', $personId)
                ->delete();

        } else {
            throw new NonExistingException('person not exiting for client', 'person_not_exist');
        }
    }

    public function getPerson($personId, $clientId) {
        $res = (array)$this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->leftJoin('faces as f', 'f.person_id', '=', 'p.person_id')
            ->leftJoin('images as i', 'i.image_id', '=', 'f.image_id')
            ->where('p.person_id', '=', $personId)
            ->where('g.clients_id', '=', $clientId)
            ->groupBy('p.person_id')
            ->get(['p.person_id', 'p.name', 'g.group_id', 'f.face_id', 'i.img_path']);

        if(empty($res)) {
            throw new NonExistingException("person not existing for client", 'person_not_exist');
        }

        $groups = [];
        foreach ($res as $r) {
            $groups[] = $r->group_id;
        }

        return array(
            'person_id' => $r->person_id,
            'name' => $r->name,
            'face_id' => $r->face_id,
            'img_path' => $r->img_path,
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
            if(GroupModelSql::getInstance()->groupExistForClient($group_id, $clientId)) {
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