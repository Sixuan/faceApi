<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:46 PM
 */

namespace App\Http\Models;


use App\Exceptions\NonExistingException;

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

    /**
     * @param $faceId
     * @param $clientId
     * @throws NonExistingException
     */
    public function deleteFace($faceId, $clientId) {
        if($this->faceExistForClient($faceId, $clientId)) {
            $this->getConn()->table('faces')
                ->where('face_id', '=', $faceId)
                ->delete();
        }else{
            throw new NonExistingException('face not existing for client', 'face_not_exist');
        }
    }


    public function faceExistForClient($faceId, $clientId) {
        return $this->getConn()->table('faces as f')
            ->join('persons as p', 'f.person_id', '=', 'p.person_id')
            ->join('persons_groups as pg', 'p.person_id', '=', 'pg.person_id')
            ->join('groups as g', 'g.group_id', '=', 'pg.group_id')
            ->where('f.face_id', '=', $faceId)
            ->where('g.clients_id', '=', $clientId)
            ->exists();
    }

}