<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 7:32 PM
 */

namespace App\Http\Services\Socket\Contracts;


interface SocketClientInterface
{
    /**
     * @param SocketRequestInterface $request
     * @return SocketResponseInterface
     */
    public function send(SocketRequestInterface $request);
}