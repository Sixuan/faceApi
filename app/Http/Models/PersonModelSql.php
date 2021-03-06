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
        $img_path = null;
        
        $newestFaceImageId = $this->getConn()->table('faces')
            ->where('person_id', '=', $personId)
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->pluck('image_id');
        
        if($newestFaceImageId) {
            $img_path = $this->getConn()->table('images')
                ->where('image_id', '=', $newestFaceImageId[0])
                ->orderBy('timestamp', 'desc')
                ->limit(1)
                ->pluck('img_path');
        }
        \Log::info('img_path', array('path' => $img_path, '$newestFaceImageId' => $newestFaceImageId));

        if($img_path) {
            return str_replace('/tmp/', '/', $img_path[0]);
        }
        return null;
    }

    public function personsExistForClient($personId, $clientId) {
        return $this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->where('p.person_id', '=', $personId)
            ->where('g.clients_id', '=', $clientId)
            ->exists();
    }


    /**
     * @param $faceId
     * @param $personId
     * @param $clientId
     * @throws NonExistingException
     */
    public function addToPerson($faceId, $personId, $clientId)
    {
        if($this->personsExistForClient($personId, $clientId)) {
            $this->getConn()->table('faces')
                ->where('face_id', '=', $faceId)
                ->update(['person_id' => $personId]);
        }else{
            throw new NonExistingException('person not existing for client', 'person_not_exist');
        }
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
//        $res = (array)$this->getConn()->table('persons as p')
//            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
//            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
//            ->leftJoin('faces as f', 'f.person_id', '=', 'p.person_id')
//            ->leftJoin('images as i', 'i.image_id', '=', 'f.image_id')
//            ->where('p.person_id', '=', $personId)
//            ->where('g.clients_id', '=', $clientId)
//            ->get(['p.person_id', 'p.name',
//                'g.group_id', 'f.face_id', 'i.img_path',
//                'f.left', 'f.right',
//                'f.top', 'f.bottom']);

        $groups = (array)$this->getConn()->table('persons as p')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->where('p.person_id', '=', $personId)
            ->where('g.clients_id', '=', $clientId)
            ->lists('pg.group_id');

        if(empty($groups)) {
            throw new NonExistingException("person not existing for client", 'person_not_exist');
        }

        $face = $this->getConn()->table('persons as p')
            ->leftJoin('faces as f', 'f.person_id', '=', 'p.person_id')
            ->leftJoin('images as i', 'f.image_id', '=', 'i.image_id')
            ->where('p.person_id', '=', $personId)
            ->orderBy('f.face_id', 'desc')
            ->first([
                'f.left',
                'f.right',
                'f.top',
                'f.bottom',
                'i.image_id',
                'i.img_path',
                'p.name',
                'f.face_id'
            ]);

        return array(
            'person_id' => $personId,
            'name' => $face->name,
            'face_id' => $face->face_id,
            'img_path' => str_replace('/tmp/', '/', $face->img_path),
            'top' => $face->top,
            'bottom' => $face->bottom,
            'right' => $face->right,
            'left' => $face->left,
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