<?php

namespace App\Http\Controllers;

use App\Http\Models\ClientModelSql;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const SUCCESS_CODE = 200;
    const BAD_REQUEST = 400;
    const BAD_AUTH = 401;
    const GENERAL_BAD_RESPONSE_MESSAGE = 'general_error';
    const SOCKET_BAD_RESPONSE_MESSAGE = 'socket_error';
    const BAD_RESQUEST_RESPONSE_MESSAGE = 'bad_request';

    protected static $clientId;
    
//    public function __construct(Request $request)
//    {
//        $appKey = $request->input('app_key');
//        $appSecret = $request->input('app_secret');
//        $this->clientId = ClientModelSql::getInstance()->getClientId($appKey, $appSecret);
//    }

    public static function buildResponse($content, $httpCode) {
        return new Response($content, $httpCode);
    }

    public static function buildSuccessResponse() {
        $content = [
            'code' => self::SUCCESS_CODE,
            'status' => 'request_success',
            'message' => []
        ];
        return new Response($content, self::SUCCESS_CODE);
    }

    public static function buildBadResponse() {
        $content = [
            'code' => self::BAD_REQUEST,
            'status' => 'request_failed',
            'message' => []
        ];
        return new Response($content, self::BAD_REQUEST);

    }

    /**
     * @return mixed
     */
    public static function getClientId()
    {
        return self::$clientId;
    }

    /**
     * @param $id
     */
    public static function setClientId($id) {
        self::$clientId = $id;
    }
}
