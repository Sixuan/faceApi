<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/6/16
 * Time: 9:28 PM
 */

namespace App\Http\Services\Socket;


use App\Http\Services\Socket\Contracts\SocketResponseInterface;

class SocketResponse implements SocketResponseInterface
{

    /**
     * @var array
     */
    private $content;

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
    
}