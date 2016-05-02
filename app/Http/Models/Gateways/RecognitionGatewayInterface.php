<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:48 PM
 */

namespace App\Http\Models\Gateways;

interface RecognitionGatewayInterface
{
    public function detect();

    public function recognize();

    public function compare();

}