<?php

namespace ReactMoreTech\MayarHeadlessAPI\Exceptions;

class MissingArguements extends BaseException
{
    public function setMessage()
    {
        return 'Missing arguements exception. Content fields must be complete';
    }
}