<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 4/17/16
 * Time: 4:16 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\GroupModelSql;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function store(Request $request) {
        $input = $request->input();
        try{
            $group = GroupModelSql::getInstance()->createGroup($input, self::$clientId);
            return self::buildResponse(['group' => $group], self::SUCCESS_CODE);

        }catch (\Exception $e) {
            $content = array(
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }
    
    public function destroy(Request $request) {
        
    }

    //@todo add persons in the group response
    public function get($id) {
        try{
            $group = GroupModelSql::getInstance()->getGroupById(self::$clientId, $id);
            return self::buildResponse(['group' => $group], self::SUCCESS_CODE);

        }catch (\Exception $e) {
            $content = array(
                'status' => self::GENERAL_BAD_RESPONSE_MESSAGE,
                'message' => $e->getMessage(),
                'error' => (string)$e
            );
            return self::buildResponse($content, self::BAD_REQUEST);
        }
    }

    public function index() {
        try{
            $groups = GroupModelSql::getInstance()->getGroupsByClientId(self::$clientId);
            return self::buildResponse(['groups' => $groups], self::SUCCESS_CODE);

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