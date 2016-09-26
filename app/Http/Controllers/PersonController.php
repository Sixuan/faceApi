<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:21 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\PersonModelSql;
use Illuminate\Http\Request;

class PersonController extends Controller
{

    public function store(Request $request) {
        $input = $request->input();
        try{
            $result = PersonModelSql::getInstance()->createPerson($input, self::$clientId);
            return self::buildResponse(['person' => $result], self::SUCCESS_CODE);

        }catch (\Exception $e) {
            $content = array(
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }

    }

    public function destroy($id) {
        try{
            PersonModelSql::getInstance()->deletePerson($id, self::$clientId);
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

    public function get($id) {
        try{
            $result = PersonModelSql::getInstance()->getPerson($id, self::$clientId);
            return self::buildResponse(['person' => $result], self::SUCCESS_CODE);

        }catch (\Exception $e) {
            $content = array(
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }
    
}