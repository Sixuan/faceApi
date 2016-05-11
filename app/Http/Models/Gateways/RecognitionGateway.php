<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:49 PM
 */

namespace App\Http\Models\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Services\Socket\Contracts\SocketClientInterface;
use App\Http\Services\Socket\Contracts\SocketResponseInterface;
use App\Http\Services\Socket\SocketClient;
use App\Http\Services\Socket\SocketRequest;

class RecognitionGateway implements RecognitionGatewayInterface
{
    
    const RECOGNITION_GATEWAY_HOST_DEFAULT = 'localhost';
    
    const RECOGNITION_PORT_DEFAULT = 12345;
    
    const RECOGNITION_GATEWAY_CONNECTION_TIMEOUT = 10;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;
    
    /**
     * @var SocketClientInterface
     */
    private $socketClient;

    /**
     * @var RecognitionGatewayInterface
     */
    private static $gateway;

    /**
     * @return RecognitionGatewayInterface
     */
    public static function getInstance() {
        if(self::$gateway == null) {
            self::$gateway = new RecognitionGateway(SocketClient::getInstance());
        }
        return self::$gateway;
    }

    /**
     * RecognitionGateway constructor.
     * @param SocketClientInterface $socketClient
     */
    public function __construct(SocketClientInterface $socketClient)
    {
        $this->socketClient = $socketClient;
        $this->host = env('SOCKET_HOST', self::RECOGNITION_GATEWAY_HOST_DEFAULT);
        $this->port = env('SOCKET_PORT', self::RECOGNITION_PORT_DEFAULT);
    }


    /**
     * @param string $photoPath
     * @param int $personId
     * @return SocketResponseInterface
     */
    public function addFace($photoPath, $personId)
    {
        /**
         * {
        "method" : "add_face",
        "payload" : {
        "img_path":"/opt/images/6/blah.jpg",
        "person_id" : 2
        }
        }
         */
        $payload = [
            'method' => 'addFace',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'img_path' => $photoPath,
                'person_id' => $personId,
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );
        
        $response = $this->socketClient->send($request);
        return $response;
    }

    public function detect($photoPath)
    {
        /**
         * {
        "method" : "detection",
        "payload" : {
        "img_path":"/opt/images/detection/blah.jpg"
        }
        }
         */
        $payload = [
            'method' => 'detect',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'img_path' => $photoPath,
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );

        $response = $this->socketClient->send($request);
        return $response;

    }
    
    public function test() {
        $payload = [
            'method' => 'test',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'img_path' => 'blah',
                'person_id' => 2
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );
        
        $response = $this->socketClient->send($request);
        return $response;
    }

    public function verify($photoPath, $personId)
    {
        /**
         * {
        "method" : "verfiy",
        "payload" : {
        "img_path":"/opt/images/6/blah.jpg",
        "person_id" : 2
        }
        }
         */

        $payload = [
            'method' => 'verify',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'img_path' => $photoPath,
                'person_id' => $personId
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );

        $response = $this->socketClient->send($request);
        return $response;

    }

    public function recognize($photoPath, $groupId)
    {
        /**
         * {
        "method" : "recognize",
        "payload" : {
        "face_id":2,
        "person_id" :3
        }
        }
         */

        $payload = [
            'method' => 'recognize',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'img_path' => $photoPath,
                'group_id' => $groupId
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );

        $response = $this->socketClient->send($request);
        return $response;
    }

    public function compare($faceId1, $faceId2)
    {
        /**
         * {
        "method" : "compare",
        "payload" : {
        "face_id_1": 1,
        "face_id_2" : 2
        }
        }
         */

        $payload = [
            'method' => 'compare',
            'payload' => [
                'client_id' => Controller::getClientId(),
                'face_id1' => $faceId1,
                'face_id2' => $faceId2
            ]
        ];

        $request = new SocketRequest(
            $payload,
            $this->host,
            $this->port,
            self::RECOGNITION_GATEWAY_CONNECTION_TIMEOUT
        );

        $response = $this->socketClient->send($request);
        return $response;
    }
}