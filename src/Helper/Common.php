<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper;

class Common
{
    public static function strReplace($search, $replace, $str)
    {
        if (!empty($str)) {
            return str_replace($search, $replace, $str);
        }
        return '';
    }

    public static function generateUniqId()
    {
        $id = uniqid("", TRUE);
        $id = self::strReplace(".", "-", $id);
        return $id . "-" . rand(10000000, 99999999);
    }
}
