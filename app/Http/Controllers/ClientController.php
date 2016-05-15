<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/15/16
 * Time: 5:30 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\ClientModelSql;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function store(Request $request) {
        $input = $request->input();
        try{
            $client = ClientModelSql::getInstance()->createClient($input);
            return self::buildResponse($client, self::SUCCESS_CODE);

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
            $clients = ClientModelSql::getInstance()->getClients();
            return self::buildResponse(['clients' => $clients], self::SUCCESS_CODE);

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
            $client = ClientModelSql::getInstance()->getClient($id);
            return self::buildResponse($client, self::SUCCESS_CODE);

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