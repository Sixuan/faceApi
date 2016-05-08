<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 7:41 PM
 */

namespace App\Http\Services\Socket\Contracts;


interface SocketRequestInterface
{
    public function getHost();
    
    public function getPort();
    
    public function getTtl();
    
    public function getPayload();
    
    public function toArray();
    
}