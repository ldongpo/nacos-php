<?php

namespace nacosphp\exception;
use Exception;
class ResponseCodeErrorException extends Exception
{
    /**
     * 基础的返回异常
     * @param $code
     * @param $message
     */
    public function __construct($code = 0, $message = "")
    {
        parent::__construct($message, $code);
    }
}