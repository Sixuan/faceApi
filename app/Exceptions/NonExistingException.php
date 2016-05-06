<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/5/16
 * Time: 12:16 AM
 */

namespace App\Exceptions;


class NonExistingException extends BadRequestException
{
    const GROUP_NOT_EXIST = 'group_not_exist';
}