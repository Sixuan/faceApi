<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/1/16
 * Time: 10:51 PM
 */

namespace App\Exceptions;


class BadRequestException extends \Exception
{
    const MISSING_CLIENT_ID = 'missing_client_id';
    
    /**
     * @var array
     */
    protected $context;
    /**
     * @var string
     */
    protected $statusCode;

    public function __construct($message, $status_code = 'bad_request',
                                $context = [], $code = 0, \Exception $previous = null)
    {
        $this->context = $context;
        $this->statusCode = $status_code;
        parent::__construct($message, $code, $previous = null);
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    function __toString()
    {
        return "Request context: " . json_encode($this->context) .
        parent::__toString();
    }
}