<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper\Validations;

/**
 * Validator Class
 *
 * Provides validation methods for API requests, including checking content types,
 * required fields, and nested data structures.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Helper\Validations
 */
class Validator
{
    /**
     * Validate inquiry request data.
     *
     * Ensures the request contains the required fields and is of the correct type.
     *
     * @param mixed $request The request data.
     * @param array $fields Required fields.
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\InvalidContentType
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements
     */
    public static function validateInquiryRequest($request, $fields)
    {
        MainValidator::validateContentType($request);
        MainValidator::validateContentFields($request, $fields);
    }

    /**
     * Validate if the request is an array.
     *
     * @param mixed $request The request data.
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\InvalidContentType If the request is not an array.
     */
    public static function validateArrayRequest($request)
    {
        MainValidator::validateContentType($request);
    }

    /**
     * Validate payload for creating a coupon.
     *
     * Ensures the required fields exist and validates nested discount and coupon fields.
     *
     * @param array $payload The request payload.
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements If required fields are missing.
     */
    public static function validateCreateCoupon(array $payload)
    {
        MainValidator::validateContentFields($payload, ['name', 'discount']);
        MainValidator::validateNestedFields($payload, 'discount', [
            'discountType',
            'eligibleCustomerType',
            'minimumPurchase',
            'value',
            'totalCoupons'
        ]);
        if (!empty($payload['coupon'])) {
            MainValidator::validateNestedFields($payload, 'coupon', ['type']);
        }
    }

    /**
     * Validate payload for creating an installment.
     *
     * Ensures required fields exist and validates nested installment fields.
     *
     * @param array $payload The request payload.
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements If required fields are missing.
     */
    public static function validateCreateInstallment(array $payload)
    {
        MainValidator::validateContentFields($payload, ['email', 'mobile', 'name', 'amount', 'installment']);
        MainValidator::validateNestedFields($payload, 'installment', [
            'description',
            'interest',
            'tenure',
            'dueDate'
        ]);
    }

    /**
     * Validate a single argument.
     *
     * Ensures the argument is not empty and is a string.
     *
     * @param mixed $argument The argument to validate.
     * @param string $fieldName The field name for error reporting.
     * @throws \ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements If the argument is empty or invalid.
     */
    public static function validateSingleArgument($argument, $fieldName)
    {
        MainValidator::validateSingleArgument($argument, $fieldName);
    }
}
