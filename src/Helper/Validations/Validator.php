<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper\Validations;

class Validator
{
    public static function validateInquiryRequest($request, $fields)
    {
        MainValidator::validateContentType($request);
        MainValidator::validateContentFields($request, $fields);
    }

    public static function validateArrayRequest($request)
    {
        MainValidator::validateContentType($request);
    }

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

    public static function validateSingleArgument($argument, $fieldName)
    {
        MainValidator::validateSingleArgument($argument, $fieldName);
    }
}
