<?php

namespace ReactMoreTech\MayarHeadlessAPI\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    public function __construct($message = null, $code = null)
    {
        $this->code = empty($code) ? $this->setCode() : $code;
        $this->message = empty($message) ? $this->setMessage() : $message;
    }

    abstract public function setMessage();

    abstract public function setCode();
}
