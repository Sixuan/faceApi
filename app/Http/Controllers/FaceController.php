<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 9:58 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\FaceModelSql;
use App\Http\Models\Gateways\RecognitionGateway;
use App\Http\Models\ImageUploader;
use App\Http\Models\PersonModelSql;
use App\Http\Services\Socket\Exceptions\SocketException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaceController extends Controller
{

    public function socket() {
        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->test();
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
     * Given new photo, add to existing person
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        
        $input = $request->all();
        $file = $request->file('image');
        $photoPath = ImageUploader::uploadAndGetPath($file);
        $person_id = $input['person_id'];

        //@todo wrap in validation modal
        if(!PersonModelSql::getInstance()->personsExistForClient($person_id, self::$clientId)) {
            $return = [
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => 'person not exiting for client'
            ];
            return self::buildResponse($return, self::SUCCESS_CODE);
        }

        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->addFace($photoPath, $person_id);
            $content = $response->getContent();
            $return = array(
                'face_id' => $content['face_id'],
                //'image_id' => $content['image_id'],
                'person_id' => $person_id,
                //'face_img_path' => $content['face_img_path']
            );
            return self::buildResponse($return, self::SUCCESS_CODE);

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
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        try{
            FaceModelSql::getInstance()->deleteFace($id, self::$clientId);
            return self::buildSuccessResponse();
        }catch (\Exception $e) {
            $content = array(
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }

    /**
     * Give a new photo, detect face
     * @param Request $request
     * @return Response
     */
    public function detect(Request $request) {

        $file = $request->file('image');
        $photoPath = ImageUploader::uploadAndGetPath($file);

        try{
            $recognitionGateway = RecognitionGateway::getInstance();
            $response = $recognitionGateway->detect($photoPath);
            $content = $response->getContent();
            $content['img_path'] = str_replace('/tmp/', '/', $photoPath);
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