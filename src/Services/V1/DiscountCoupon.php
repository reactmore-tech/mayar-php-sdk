<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Formatter\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\BaseException;
use GuzzleHttp\Exception\RequestException;

/**
 * Discount And Coupon Service for Mayar Headless API V1
 *
 * Provides functionalities for managing customer data, including retrieval,
 * creation, updating, and generating magic links.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class DiscountCoupon implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Discount And Coupon Service constructor.
     *
     * Initializes the Customer service with the provided HTTP adapter.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Create a new coupon.
     *
     * Generates a discount coupon for customers with customizable properties,
     * including expiration date, discount details, and eligibility criteria.
     *
     * @param array $payload Required parameters:
     *  - 'name' (string) Coupon name.
     *  - 'discount' (array) Discount details:
     *    - 'discountType' (string) Type of discount, either "monetary" or "percentage".
     *    - 'eligibleCustomerType' (string) Eligible customers: "all", "new", or "old".
     *    - 'minimumPurchase' (int) Minimum purchase amount required to use the coupon.
     *    - 'value' (int) Discount amount or percentage value.
     *    - 'totalCoupons' (int) Total number of available coupons.
     * 
     *  Optional parameters:
     *  - 'expiredAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'coupon' (array) Coupon details:
     *    - 'code' (string|null) Custom coupon code. If not provided, a code will be generated.
     *    - 'type' (string) Coupon usage type, either "onetime" or "reusable".
     *  - 'products' (array) List of applicable products, e.g., [{'id': 'product_id'}].
     *
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function create(array $payload = [])
    {
        try {
            Validator::validateCreateCoupon($payload);
            $request = $this->adapter->post('hl/v1/coupon/create', [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Coupon Detail by ID.
     *
     * Retrieves the details of a specific coupon by its unique identifier.
     *
     * @param string $couponId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function getCoupon(string $couponId = null)
    {
        try {
            Validator::validateSingleArgument($couponId, 'couponId');
            $request = $this->adapter->get("hl/v1/coupon/{$couponId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API request exceptions.
     *
     * Processes and formats exceptions that occur during API requests,
     * returning a structured error response.
     *
     * @param RequestException $e The caught exception.
     * @return array Formatted error response with status code.
     */
    private function handleException(RequestException $e)
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $responseBody = $response ? $response->getBody()->getContents() : null;

        if ($responseBody) {
            $errorData = json_decode($responseBody, true);
            $errorMessage = $errorData['messages'] ?? 'An error occurred';
            return ResponseFormatter::formatErrorResponse($errorMessage, $statusCode);
        }

        return ResponseFormatter::formatErrorResponse($e->getMessage(), $statusCode);
    }
}
