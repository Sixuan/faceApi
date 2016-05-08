<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 7:32 PM
 */

namespace App\Http\Services\Socket;

use App\Http\Services\Socket\Contracts\SocketClientInterface;
use App\Http\Services\Socket\Contracts\SocketRequestInterface;

class SocketClient implements SocketClientInterface
{
    /**
     * @var SocketClient
     */
    private static $socketClient;

    /**
     * @return SocketClient
     */
    public static function getInstance() {
        if(self::$socketClient == null){
            self::$socketClient = new SocketClient();
        }
        return self::$socketClient;
    }

    /**
     * @param SocketRequestInterface $request
     * @return SocketRequestInterface
     */
    public function send(SocketRequestInterface $request) {
        if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }

        echo "Socket created \n";

        //Connect socket to remote server
        if(!socket_connect($sock , $request->getHost() , $request->getPort()))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            die("Could not connect: [$errorcode] $errormsg \n");
        }

        echo "Connection established \n";

        //$message = "GET / HTTP/1.1\r\n\r\n";
        $message = json_encode($request->getPayload());

        //Send the message to the server
        if( ! socket_send ( $sock , $message , strlen($message) , 0))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            die("Could not send data: [$errorcode] $errormsg \n");
        }

        return new SocketResponse(['yo' => 'test', 'message' => $message]);
    }
}