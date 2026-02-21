<?php

namespace ReactMoreTech\MayarHeadlessAPI\Exceptions;

/**
 * Exception thrown when required arguments are missing.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Exceptions
 */
class MissingArguments extends BaseException
{
    public function setMessage()
    {
        return 'Missing arguments exception. Content fields must be complete';
    }

    public function setCode()
    {
        return 400;
    }
}
