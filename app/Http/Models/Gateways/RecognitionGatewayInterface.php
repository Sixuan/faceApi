<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:48 PM
 */

namespace App\Http\Models\Gateways;

use App\Http\Services\Socket\Contracts\SocketResponseInterface;

interface RecognitionGatewayInterface
{
    /**
     * Give a new photo, detect face
     * @param string $photoPath
     * @param bool $saveToDB
     * @return SocketResponseInterface
     */
    public function detect($photoPath, $saveToDB);

    /**
     * Give a new photo, check if face is in a group
     * @param string $photoPath
     * @param int $faceId
     * @param int $groupId
     * @return SocketResponseInterface
     */
    public function recognize($photoPath, $faceId, $groupId);

    /**
     * Given two existing faces, compare similarity
     * @param int $faceId1
     * @param int $faceId2
     * @return SocketResponseInterface
     */
    public function compare($faceId1, $faceId2);

    /**
     * Given new photo, add to existing person
     * @param string $photoPath
     * @param integer $personId
     * @return SocketResponseInterface
     */
    public function addFace($photoPath, $personId);

    /**
     * Given a new photo, verify if it's matching an existing person
     * @param string $photoPath
     * @param int $faceId
     * @param integer $personId
     * @return SocketResponseInterface
     */
    public function verify($photoPath, $faceId, $personId);

    /**
     * @param $photoPath1
     * @param $photoPath2
     * @return SocketResponseInterface
     */
    public function matching($photoPath1, $photoPath2);

    /**
     * @return SocketResponseInterface
     */
    public function test();

}