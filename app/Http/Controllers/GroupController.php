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
            $store = GroupModelSql::getInstance()->createGroup($input);
            return self::buildResponse($store, self::SUCCESS_CODE);

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
    
    public function get($id) {
        
    }

    public function index(Request $request) {
        return response()->json(['name' => 'Abigail', 'state' => 'CA', 'client' => $this->getClientId()]);
    }
}