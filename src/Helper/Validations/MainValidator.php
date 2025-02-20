<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper\Validations;

use ReactMoreTech\MayarHeadlessAPI\Exceptions\InvalidContentType;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements;

class MainValidator
{
    public static function isContentTypeArray($content)
    {
        return is_array($content);
    }

    public static function getMissingFields($content, $fields)
    {
        return array_values(array_diff($fields, array_keys($content)));
    }

    public static function validateContentType($content)
    {
        if (!self::isContentTypeArray($content)) {
            throw new InvalidContentType();
        }
    }

    public static function validateContentFields($content, $fields)
    {
        $missingFields = self::getMissingFields($content, $fields);

        if (!empty($missingFields)) {
            throw new MissingArguements('Field ' . $missingFields[0] . ' is missing');
        }
    }

    /**
     * Validates nested fields inside a given array field.
     *
     * @param array $content The main array containing the nested field.
     * @param string $field The key of the nested array to validate.
     * @param array $nestedFields Required keys inside the nested array.
     * @throws MissingArguements If any required field is missing.
     */
    public static function validateNestedFields(array $content, string $field, array $nestedFields)
    {
        if (!isset($content[$field]) || !is_array($content[$field])) {
            throw new MissingArguements("Field '{$field}' is missing or invalid");
        }

        $missingFields = self::getMissingFields($content[$field], $nestedFields);
        if (!empty($missingFields)) {
            throw new MissingArguements("Field '{$field}.{$missingFields[0]}' is missing");
        }
    }

    public static function validateSingleArgument($argument, $fieldName)
    {
        if (empty($argument) || !is_string($argument)) {
            throw new MissingArguements("Field '{$fieldName}' is required and must be a string.");
        }
    }
}
