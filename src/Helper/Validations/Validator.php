<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper\Validations;

class Validator
{
    public static function validateInquiryRequest($request, $fields)
    {
        MainValidator::validateContentType($request);
        MainValidator::validateContentFields($request, $fields);
    }
}