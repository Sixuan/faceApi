<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 7:41 PM
 */

namespace App\Http\Services\Socket;


use App\Http\Services\Socket\Contracts\SocketRequestInterface;

class SocketRequest implements SocketRequestInterface
{
    /**
     * @var array
     */
    private $payload;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var int
     */
    private $port;
    
    /**
     * SocketRequest constructor.
     * @param array $payload
     * @param string $host
     * @param int $port
     * @param int $ttl
     */
    public function __construct(array $payload, $host, $port, $ttl)
    {
        $this->payload = $payload;
        $this->host = $host;
        $this->port = $port;
        $this->ttl = $ttl;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
    
    public function toArray()
    {
        return array(
            'host' => $this->host,
            'port' => $this->port,
            'payload' => $this->payload
        );
    }

}