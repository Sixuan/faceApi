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
use App\Http\Services\Socket\Exceptions\SocketException;

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
     * @return SocketResponse
     * @throws SocketException
     */
    public function send(SocketRequestInterface $request) {

        if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new SocketException("Couldn't create socket: [$errorcode] $errormsg");
        }

        //echo "Socket created \n";

//        if(!socket_bind($sock , $request->getHost() , $request->getPort()))
//        {
//            $errorcode = socket_last_error();
//            $errormsg = socket_strerror($errorcode);
//
//            die("Could not bind: [$errorcode] $errormsg \n");
//        }

        //Connect socket to remote server
        \Log::info("Connecting to socket.", array('request' => $request->toArray()));
        if(!socket_connect($sock , $request->getHost() , $request->getPort()))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new SocketException("Couldn't connect socket: [$errorcode] $errormsg");
        }

        //echo "Connection established \n";

        //$message = "GET / HTTP/1.1\r\n\r\n";
        $message = json_encode($request->getPayload());
        \Log::info("Sending request to socket.", array('request' => $request->toArray()));
        //Send the message to the server
        if( ! socket_send ( $sock , $message , strlen($message) , 0))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new SocketException("Couldn't send socket request: [$errorcode] $errormsg");
        }

        if(!($data = socket_read($sock, 1024))) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new SocketException("Couldn't read socket response: [$errorcode] $errormsg");
        }

        $formattedData = addslashes(preg_replace('/\s+/', ' ', trim($data)));
        \Log::info("Received socket response.", array('data' => $data, 'formatted_data' => json_decode(array_map('utf8_encode', $data))));

        $content = is_array(json_decode(array_map('utf8_encode', $data))) ? json_decode(array_map('utf8_encode', $data)) : [];
        socket_close($sock);
        return new SocketResponse($content);
    }
}