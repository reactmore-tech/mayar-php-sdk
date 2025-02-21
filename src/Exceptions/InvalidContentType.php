<?php

namespace ReactMoreTech\MayarHeadlessAPI\Exceptions;

class InvalidContentType extends BaseException
{
    public function setMessage()
    {
        return 'Content type must be array';
    }

    public function setCode()
    {
        return 400;
    }
}
