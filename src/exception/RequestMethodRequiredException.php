<?php

namespace nacosphp\exception;
use Exception;
use Throwable;
class RequestMethodRequiredException extends Exception
{
    public function __construct($message = "缺少请求方式", $code = -1,Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}