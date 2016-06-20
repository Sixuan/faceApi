<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:39 PM
 */

namespace App\Http\Controllers;


use App\Http\Models\Gateways\RecognitionGateway;
use App\Http\Models\GroupModelSql;
use App\Http\Models\ImageUploader;
use App\Http\Models\PersonModelSql;
use App\Http\Models\RecognitionModelSql;
use App\Http\Services\Socket\Exceptions\SocketException;
use Illuminate\Http\Request;

class RecognitionController extends Controller
{

    /**
     * Given a new photo, verify if it's matching an existing person
     * @param Request $request
     * @param $personId
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, $personId) {

        $file = $request->file('image');
        $photoPath = ImageUploader::uploadAndGetPath($file);

        if(!PersonModelSql::getInstance()->personsExistForClient($personId, self::$clientId)) {
            $return = [
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => 'person not exiting for client'
            ];
            return self::buildResponse($return, self::SUCCESS_CODE);
        }
        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->verify($photoPath, $personId);
            $content = $response->getContent();
            return self::buildResponse($content, self::SUCCESS_CODE);

        }catch (SocketException $e) {
            $content = array(
                'status' => self::SOCKET_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }

    /**
     * Give a new photo, check if face is in a group
     * @param Request $request
     * @param $groupId
     * @return \Illuminate\Http\Response
     */
    public function recognize(Request $request, $groupId) {
        $file = $request->file('image');
        $photoPath = ImageUploader::uploadAndGetPath($file);
        
        if(!GroupModelSql::getInstance()->groupExistForClient($groupId, self::$clientId)) {
            $return = [
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => 'group not exiting for client'
            ];
            return self::buildResponse($return, self::SUCCESS_CODE);
        }

        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->recognize($photoPath, $groupId);
            $content = $response->getContent();
            return self::buildResponse($content, self::SUCCESS_CODE);

        }catch (SocketException $e) {
            $content = array(
                'status' => self::SOCKET_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }

    /**
     * Given two existing faces, compare similarity
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function compare(Request $request) {

        $input = $request->all();

        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->compare($input['face_id1'], $input['face_id2']);
            $content = $response->getContent();
            $supplementInfo = RecognitionModelSql::getInstance()
                ->getSupplementaryInfoForFaces($input['face_id1'], $input['face_id2']);
            $content['supplement'] = $supplementInfo;
            return self::buildResponse($content, self::SUCCESS_CODE);

        }catch (SocketException $e) {
            $content = array(
                'status' => self::SOCKET_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }

    /**
     * Give two images, upload to server and call algo to find out matching faces
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\BadRequestException
     */
    public function matching(Request $request) {
        $file1 = $request->file('image1');
        $photoPath1 = ImageUploader::uploadAndGetPath($file1);

        $file2 = $request->file('image2');
        $photoPath2 = ImageUploader::uploadAndGetPath($file2);

        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->matching($photoPath1, $photoPath2);
            $content = $response->getContent();

            if($content['images']) {
                $images = [];
                foreach ($content['images'] as $image) {
                    $image['img_path'] = str_replace('/tmp', '', $image['img_path']);
                    $images[] = $image;
                }
                $content['images'] = $images;
            }
            
            return self::buildResponse($content, self::SUCCESS_CODE);

        }catch (SocketException $e) {
            $content = array(
                'status' => self::SOCKET_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }
}